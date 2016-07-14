<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/misc.php';


	do
	{
		if (!isLoggedIn())
		{
			renderErrorMessage(MSG_NEW_POST_NOT_LOGGED_IN);
			break;
		}

		if (isBanned())
		{
			renderErrorMessage(MSG_NEW_POST_BANNED);
			break;
		}

		if (!isset($_GET['thread']) || !is_int($_GET['thread'] * 1))
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}
		$threadId = $_GET['thread'];

		$thread = getThread($threadId);

		if ($thread === null)
		{
			renderErrorMessage(MSG_THREAD_DOESNT_EXIST);
			break;
		}

		if (!canView($thread['min_powerlevel']))
		{
			renderErrorMessage(MSG_VIEW_THREAD_NOT_ALLOWED);
			break;
		}

		if (!canPostInThread($thread))
		{
			renderErrorMessage(MSG_NEW_POST_NOT_ALLOWED);
			break;
		}

		$postText = '';
		if (isset($_GET['quote']) && is_int($quotedPostId = ($_GET['quote'] * 1)))
		{
			$quotedPost = getPostById($quotedPostId);
			if ($quotedPost !== null)
			{
				// TODO outsource to template? 
				$postText = '[quote=' . $quotedPost['author_name'] . ']'
					. htmlspecialchars_decode($quotedPost['content']) . '[/quote]';
			}
		}

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
				$newPostId = createPost($threadId, $postText);
				if ($newPostId === null)
				{
					renderErrorMessage(MSG_GENERAL_ERROR);
					break;
				}

				$success = true;
				renderSuccessMessage(MSG_NEW_POST_SUCCESS);
				renderTemplate('new_post_success', [
					'threadId' => $threadId,
					'page'     => getPostPageInThread($newPostId, $threadId),
					'postId'   => $newPostId
				]);
			}
		}
		if (!$success)
		{
			renderTemplate('new_post', [
				'threadId'   => $threadId,
				'threadName' => $thread['name'],
				'forumId'    => $thread['forum_id'],
				'forumName'  => $thread['forum_name'],
				'postText'   => $postText
			]);
		}
	}
	while (false);


