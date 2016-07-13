<?php

	require_once __DIR__ . '/../functions/forums.php';
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

		if (!canView($forum['min_powerlevel']))
		{
			renderErrorMessage(MSG_NEW_THREAD_NOT_ALLOWED);
			break;
		}

		$success = false;

		$threadTitle = trim(getFieldValue('thread-title'));
		$postText = trim(getFieldValue('post-text'));

		if (isset($_POST['submit']))
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
			else
			{
				$newThreadId = createThread($forumId, $threadTitle, $postText);
				if ($newThreadId === null)
				{
					renderErrorMessage(MSG_GENERAL_ERROR);
					break;
				}

				$success = true;
				renderSuccessMessage(MSG_NEW_THREAD_SUCCESS);
				renderTemplate('new_thread_success', [
					'threadId' => $newThreadId
				]);
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