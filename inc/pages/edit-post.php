<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/bbcode.php';
	require_once __DIR__ . '/../functions/smileys.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_EDIT_POST_NOT_LOGGED_IN);
			break;
		}

		if (isBanned())
		{
			renderErrorMessage(MSG_EDIT_POST_BANNED);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_POST_DOESNT_EXIST);
			break;
		}
		$postId = $_GET['id'];

		$post = getPostById($postId);

		if (!canModifyPost($post))
		{
			renderErrorMessage(MSG_EDIT_POST_NOT_ALLOWED);
			break;
		}

		$thread = getThread($post['thread_id']);
		$threadId = $thread['id'];

		if ($post === null)
		{
			renderErrorMessage(MSG_POST_DOESNT_EXIST);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		$isThread = isFirstPostOfThread($postId, $threadId);

		$threadTitle = htmlspecialchars_decode($thread['name']);
		$postText = htmlspecialchars_decode($post['content']);

		$success = false;

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$postText = trim(getFieldValue('post-text'));

			if ($isThread && $threadTitle === '')
			{
				renderErrorMessage(MSG_THREAD_TITLE_EMPTY);
				$error = true;
			}
			if ($postText === '')
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
						'signature'    => parseBBCode($_SESSION['signature'])
					]
				]);
			}
			else
			{
				editPost($postId, $postText);
				if ($isThread)
				{
					$threadTitle = trim(getFieldValue('thread-title'));
					editThreadTitle($threadId, $threadTitle);
				}

				$success = true;
				renderSuccessMessage(MSG_EDIT_POST_SUCCESS);
				renderTemplate('new_post_success', [
					'threadId' => $threadId,
					'page'     => getPostPageInThread($postId, $threadId),
					'postId'   => $postId
				]);
			}
		}
		if (!$success)
		{
			renderTemplate('edit_post', [
				'isThread'    => $isThread,
				'postId'      => $postId,
				'threadId'    => $threadId,
				'threadTitle' => $threadTitle,
				'forumId'     => $thread['forum_id'],
				'forumName'   => $thread['forum_name'],
				'postText'    => removeSmileyDelimiters($postText),
				'token'       => $token
			]);
		}

	}
	while (false);