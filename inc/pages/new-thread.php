<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/bbcode.php';
	require_once __DIR__ . '/../functions/smileys.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_NEW_THREAD_NOT_LOGGED_IN);
			break;
		}

		if (isBanned())
		{
			renderErrorMessage(MSG_NEW_THREAD_BANNED);
			break;
		}

		if (!isset($_GET['forum']) || !is_numeric($_GET['forum']))
		{
			renderErrorMessage(MSG_FORUM_DOESNT_EXIST);
			break;
		}
		$forumId = $_GET['forum'];

		$forum = getForum($forumId);
		if ($forum === null)
		{
			renderErrorMessage(MSG_FORUM_DOESNT_EXIST);
			break;
		}

		if (!canView($forum['view_powerlevel']))
		{
			renderErrorMessage(MSG_NEW_THREAD_NOT_ALLOWED);
			break;
		}

		if (!canMakeThread($forum))
		{
			renderErrorMessage(MSG_NEW_THREAD_NOT_ALLOWED);
			break;
		}

		$success = false;

		$threadTitle = trim(getFieldValue('thread-title'));
		$postText = trim(getFieldValue('post-text'));

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			if ($threadTitle === '')
			{
				renderErrorMessage(MSG_THREAD_TITLE_EMPTY);
				$error = true;
			}
			else if ($postText === '')
			{
				renderErrorMessage(MSG_POST_TEXT_EMPTY);
				$error = true;
			}
			else if (isset($_POST['preview']))
			{
				renderTemplate('post_preview', [
					'postTime' => date(DEFAULT_DATE_FORMAT, time()),
					'content'  => parseBBCode(delimitSmileys(htmlspecialchars($postText))),
					'author'   => [
						'id'           => $_SESSION['userId'],
						'name'         => $_SESSION['username'],
						'powerlevelId' => (int)$_SESSION['powerlevel'],
						'powerlevel'   => POWERLEVEL_DESCRIPTIONS[$_SESSION['powerlevel']],
						'banned'       => $_SESSION['banned'],
						'title'        => $_SESSION['title'],
						'rank'         => getRank($_SESSION['userId']),
						'hasAvatar'    => hasAvatar($_SESSION['userId']),
						'signature'    => parseBBCode(delimitSmileys($_SESSION['signature']))
					]
				]);
			}
			else
			{
				$newThreadId = createThread($forumId, $threadTitle, $postText);
				if ($newThreadId === null)
				{
					renderErrorMessage(MSG_GENERAL_ERROR);
					break;
				}

				$success = true;

				header('Location: ?p=thread&id=' . $newThreadId);
			}
		}

		if (!$success)
		{
			renderTemplate('new_thread', [
				'forumId'     => $forumId,
				'forumName'   => $forum['name'],
				'threadTitle' => $threadTitle,
				'postText'    => $postText
			]);
		}

	}
	while (false);