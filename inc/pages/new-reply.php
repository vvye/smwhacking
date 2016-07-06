<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/misc.php';

	$success = false;

	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_NEW_REPLY_NOT_LOGGED_IN);
			break;
		}

		if (!isBanned())
		{
			renderErrorMessage(MSG_NEW_REPLY_BANNED);
			break;
		}

		if (!isset($_GET['thread']) || !is_numeric($_GET['thread']))
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}
		$threadId = $_GET['thread'];
		
		$threads = getThread($threadId);

		if (count($threads) !== 1)
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}
		$thread = $threads[0];

		if (isset($_POST['submit']))
		{
			$postText = trim(getFieldValue('post-text'));

			if ($postText === '')
			{
				renderErrorMessage(MSG_POST_TEXT_EMPTY);
				break;
			}

			$newPostId = doPost($threadId, $postText);
			if ($newPostId === null)
			{
				break;
			}

			$success = true;
			renderSuccessMessage(MSG_NEW_REPLY_SUCCESS);
			renderTemplate('new_reply_success', [
				'threadId' => $threadId,
				'page'     => getPostPageInThread($newPostId, $threadId),
				'postId'   => $newPostId
			]);
		}
	}
	while (false);

	if (!$success)
	{
		renderTemplate('new_reply', [
			'threadId'   => $threadId,
			'threadName' => $thread['name'],
			'forumId'    => $thread['forum_id'],
			'forumName'  => $thread['forum_name']
		]);
	}


