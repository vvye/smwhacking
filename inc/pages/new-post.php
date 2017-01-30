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

		if (!canView($thread['view_powerlevel']))
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
					. htmlspecialchars_decode(removeSmileyDelimiters($quotedPost['content'])) . '[/quote]';
			}
		}

		$success = false;

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$postText = trim(getFieldValue('post-text'));

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
						'signature'    => parseBBCode(delimitSmileys($_SESSION['signature']))
					]
				]);
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

				notifyThreadWatchers([
					'id'   => $threadId,
					'name' => $thread['name']
				], [
					'id'   => $_SESSION['userId'],
					'name' => $_SESSION['username']
				], $postText);

				$page = getPostPageInThread($newPostId, $threadId);
				header('Location: ?p=thread&id=' . $threadId . '&page=' . $page . '#post-' . $newPostId);
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

			$posts = getLastPostsInThread($threadId);

			foreach ($posts as $post)
			{
				renderTemplate('post', [
					'inThread'      => true,
					'id'            => $post['id'],
					'threadId'      => $threadId,
					'postTime'      => date(DEFAULT_DATE_FORMAT, $post['post_time']),
					'content'       => parseBBCode($post['content']),
					'pageInThread'  => getPostPageInThread($post['id'], $threadId),
					'unread'        => false,
					'lastEdit'      => getLastEdit($post['id']),
					'canPost'       => isLoggedIn(),
					'canModifyPost' => canModifyPost($post),
					'token'         => getCsrfToken(),
					'author'        => [
						'id'                => $post['author_id'],
						'name'              => $post['author_name'],
						'powerlevelId'      => (int)$post['author_powerlevel'],
						'powerlevel'        => POWERLEVEL_DESCRIPTIONS[$post['author_powerlevel']],
						'banned'            => $post['author_banned'],
						'title'             => $post['author_title'],
						'rank'              => getRank($post['author_id']),
						'hasAvatar'         => hasAvatar($post['author_id']),
						'favoriteMedals'    => getFavoriteMedals($post['author_id']),
						'registrationTime'  => date(DEFAULT_DATE_FORMAT, $post['author_registration_time']),
						'currentPostNumber' => getCurrentPostNumber($post['author_id'], $post['id']),
						'numTotalPosts'     => getNumPostsByUser($post['author_id']),
						'signature'         => ''
					]
				]);
			}

			renderTemplate('spoiler_js', []);
		}
	}
	while (false);


