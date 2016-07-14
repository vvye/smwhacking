<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';

	do
	{
		if (!isLoggedIn() || !isModerator() || isBanned())
		{
			renderErrorMessage(MSG_NOT_ALLOWED);
			break;
		}

		if (!isset($_GET['action']) || !isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_PARAMETERS_MISSING);
			break;
		}
		$action = $_GET['action'];
		$threadId = $_GET['id'];

		if (!in_array($action, ['close', 'open', 'sticky', 'unsticky']))
		{
			renderErrorMessage(MSG_UNKNOWN_ACTION);
			break;
		}

		if ($action === 'close')
		{
			if (isThreadClosed($threadId))
			{
				renderMessage(MSG_THREAD_ALREADY_CLOSED);
			}
			else
			{
				closeThread($threadId);
				renderSuccessMessage(MSG_CLOSE_THREAD_SUCCESS);
			}
		}
		else if ($action === 'open')
		{
			if (!isThreadClosed($threadId))
			{
				renderMessage(MSG_THREAD_ALREADY_OPEN);
			}
			else
			{
				openThread($threadId);
				renderSuccessMessage(MSG_OPEN_THREAD_SUCCESS);
			}
		}
		else if ($action === 'sticky')
		{
			if (isThreadSticky($threadId))
			{
				renderMessage(MSG_THREAD_ALREADY_STICKIED);
			}
			else
			{
				stickyThread($threadId);
				renderSuccessMessage(MSG_STICKY_THREAD_SUCCESS);
			}
		}
		else if ($action === 'unsticky')
		{
			if (!isThreadSticky($threadId))
			{
				renderMessage(MSG_THREAD_ALREADY_UNSTICKIED);
			}
			else
			{
				unstickyThread($threadId);
				renderSuccessMessage(MSG_UNSTICKY_THREAD_SUCCESS);
			}
		}

		$oppositeActions = [
			'close'    => 'open',
			'open'     => 'close',
			'sticky'   => 'unsticky',
			'unsticky' => 'sticky'
		];

		renderTemplate('moderate_thread_after', [
			'threadId'       => $threadId,
			'oppositeAction' => $oppositeActions[$action] ?? ''
		]);
	}
	while (false);