<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_DELETE_POST_NOT_LOGGED_IN);
			break;
		}
		if (isBanned())
		{
			renderErrorMessage(MSG_DELETE_POST_BANNED);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_POST_DOESNT_EXIST);
			break;
		}
		$postId = $_GET['id'];

		$post = getPostById($postId);
		$threadId = $post['thread_id'];
		$thread = getThread($threadId);

		if ($post === null)
		{
			renderErrorMessage(MSG_POST_DOESNT_EXIST);
			break;
		}

		if (!canModifyPost($post))
		{
			renderErrorMessage(MSG_DELETE_POST_NOT_ALLOWED);
			break;
		}

		$firstPost = isFirstPostOfThread($postId, $threadId);

		if (isset($_POST['submit']))
		{
			deletePost($postId, $threadId);
			if ($firstPost)
			{
				deleteThread($threadId);
				renderSuccessMessage(MSG_DELETE_THREAD_SUCCESS);
			}
			else
			{
				renderSuccessMessage(MSG_DELETE_POST_SUCCESS);
			}
			
			renderTemplate('delete_after', [
				'firstPost' => $firstPost,
				'threadId'  => $threadId,
				'forumId'   => $thread['forum_id']
			]);
		}
		else
		{
			renderTemplate('delete_post', [
				'firstPost'  => $firstPost,
				'postId'     => $postId,
				'threadId'   => $threadId,
				'threadName' => $thread['name'],
				'forumId'    => $thread['forum_id'],
				'forumName'  => $thread['forum_name']
			]);
		}
	}
	while (false);