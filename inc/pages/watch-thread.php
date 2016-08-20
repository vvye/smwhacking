<?php

	require_once __DIR__ . '/../functions/forums.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_WATCH_NOT_LOGGED_IN);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}
		$threadId = $_GET['id'];

		$thread = getThread($threadId);

		if ($thread === null)
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}

		if (!canView($thread['view_powerlevel']))
		{
			renderErrorMessage(MSG_VIEW_THREAD_NOT_ALLOWED);
			break;
		}

		$watched = isThreadWatched($threadId);
		$unwatch = isset($_GET['action']) && $_GET['action'] == 'unwatch';

		if ($unwatch)
		{
			if (!$watched)
			{
				renderMessage(MSG_THREAD_NOT_WATCHED);
				break;
			}
			unwatchThread($threadId);
			renderSuccessMessage(MSG_UNWATCH_SUCCESS);
		}
		else
		{
			if ($watched)
			{
				renderMessage(MSG_THREAD_ALREADY_WATCHED);
				break;
			}
			watchThread($threadId);
			renderSuccessMessage(MSG_WATCH_SUCCESS);
		}

		renderTemplate('watch_thread_after', [
			'threadId' => $threadId,
			'unwatch'  => $unwatch
		]);

	}
	while (false);