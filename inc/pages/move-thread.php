<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_MOVE_THREAD_NOT_LOGGED_IN);
			break;
		}

		if (isBanned())
		{
			renderErrorMessage(MSG_MOVE_THREAD_BANNED);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}
		$threadId = $_GET['id'];

		$thread = getThread($threadId);

		if (!canView($thread['view_powerlevel']))
		{
			renderErrorMessage(MSG_MOVE_THREAD_NOT_ALLOWED);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		$success = false;

		if (isset($_POST['submit']))
		{
			$error = false;

			$targetForumId = $_POST['target-forum-id'];

			$targetForum = getForum($targetForumId);

			if ($targetForum === null)
			{
				renderErrorMessage(MSG_FORUM_DOESNT_EXIST);
				$error = true;
			}

			if (!canView($targetForum['view_powerlevel']))
			{
				renderErrorMessage(MSG_MOVE_THREAD_NOT_ALLOWED);
				$error = true;
			}

			if ((int)$targetForumId === (int)$thread['forum_id'])
			{
				renderErrorMessage(MSG_MOVE_THREAD_SAME_FORUM);
				$error = true;
			}

			if (!$error)
			{
				moveThread($threadId, $thread['forum_id'], $targetForumId);

				renderSuccessMessage(MSG_MOVE_THREAD_SUCCESS);
				renderTemplate('move_thread_after', [
					'threadId' => $threadId
				]);

				$success = true;
			}
		}

		if (!$success)
		{
			$targetForums = getAllVisibleForums();

			$targetForums = array_map(function (&$forum) use ($thread)
			{
				$forum['current'] = ($forum['id'] == $thread['forum_id']);

				return $forum;
			}, $targetForums);

			renderTemplate('move_thread', [
				'threadId'     => $threadId,
				'threadTitle'  => $thread['name'],
				'forumId'      => $thread['forum_id'],
				'forumName'    => $thread['forum_name'],
				'targetForums' => $targetForums,
				'token'        => $token
			]);
		}
	}
	while (false);