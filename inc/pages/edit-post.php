<?php

	require_once __DIR__ . '/../functions/forums.php';
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
			// TODO permission to view thread
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
		$thread = getThread($post['thread_id']);
		$threadId = $thread['id'];

		if ($post === null)
		{
			renderErrorMessage(MSG_POST_DOESNT_EXIST);
			break;
		}

		if (!canModifyPost($post))
		{
			renderErrorMessage(MSG_EDIT_POST_NOT_ALLOWED);
			break;
		}

		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}
		$token = $_GET['token'];

		// TODO edit thread title if post is first post of a thread
		$postText = htmlspecialchars_decode($post['content']);

		$success = false;

		if (isset($_POST['submit']))
		{
			$postText = trim(getFieldValue('post-text'));

			if ($postText === '')
			{
				renderErrorMessage(MSG_POST_TEXT_EMPTY);
				$error = true;
			}
			else
			{
				editPost($postId, $postText);

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
				'postId'     => $postId,
				'threadId'   => $threadId,
				'threadName' => $thread['name'],
				'forumId'    => $thread['forum_id'],
				'forumName'  => $thread['forum_name'],
				'postText'   => $postText,
				'token'      => $token
			]);
		}

	}
	while (false);