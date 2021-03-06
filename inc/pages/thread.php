<?php

	require_once __DIR__ . '/../config/user.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/../functions/pagination.php';
	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';
	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/medals.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/bbcode.php';


	do
	{
		if (!isset($_GET['id']))
		{
			include __DIR__ . '/error.php';
			break;
		}
		$threadId = $_GET['id'];

		$thread = getThread($threadId);

		if ($thread === null)
		{
			include __DIR__ . '/error.php';
			break;
		}

		if (!canView($thread['view_powerlevel']))
		{
			renderErrorMessage(MSG_VIEW_THREAD_NOT_ALLOWED);
			break;
		}

		addViewToThread($threadId);

		$threadName = $thread['name'];
		$forumId = $thread['forum_id'];
		$forumName = $thread['forum_name'];
		$closed = $thread['closed'];
		$sticky = $thread['sticky'];
		$watched = isThreadWatched($threadId);

		$canPost = isBanned() ? false : isModerator() || !$closed;

		renderTemplate('thread_top', [
			'top'           => true,
			'threadId'      => $threadId,
			'threadName'    => $threadName,
			'forumId'       => $forumId,
			'forumName'     => $forumName,
			'canTakeAction' => isLoggedIn() && !isBanned(),
			'canWatch'      => isLoggedIn(),
			'moderator'     => isModerator(),
			'canPost'       => $canPost,
			'closed'        => $closed,
			'sticky'        => $sticky,
			'watched'       => $watched,
			'token'         => getCsrfToken()
		]);

		$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;

		$numPosts = $thread['posts'];
		$numPages = (int)ceil($numPosts / POSTS_PER_PAGE);
		makeBetween($page, 1, $numPages);
		renderPagination('?p=thread&id=' . $threadId, $page, $numPages);

		$posts = getPostsInThread($threadId, $page);

		if (isLoggedIn())
		{
			$newLastReadTime = $posts[count($posts) - 1]['post_time'];
			updateThreadLastReadTime($threadId, $thread['last_read_time'], $newLastReadTime);
		}

		foreach ($posts as $post)
		{
			$unread = isLoggedIn() && $post['post_time'] > $thread['last_read_time'];

			renderTemplate('post', [
				'inThread'      => true,
				'id'            => $post['id'],
				'threadId'      => $threadId,
				'postTime'      => date(DEFAULT_DATE_FORMAT, $post['post_time']),
				'content'       => parseBBCode($post['content']),
				'pageInThread'  => getPostPageInThread($post['id'], $threadId),
				'unread'        => $unread,
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
					'signature'         => parseBBCode($post['author_signature'])
				]
			]);
		}

		renderTemplate('thread_top', [
			'top'           => false,
			'threadId'      => $threadId,
			'threadName'    => $threadName,
			'forumId'       => $forumId,
			'forumName'     => $forumName,
			'canTakeAction' => isLoggedIn() && !isBanned(),
			'canWatch'      => isLoggedIn(),
			'moderator'     => isModerator(),
			'canPost'       => $canPost,
			'closed'        => $closed,
			'sticky'        => $sticky,
			'watched'       => $watched,
			'token'         => getCsrfToken()
		]);

		renderPagination('?p=thread&id=' . $threadId, $page, $numPages);

		renderTemplate('spoiler_js', []);
	}
	while (false);