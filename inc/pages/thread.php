<?php

	require_once __DIR__ . '/../config/user.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/../functions/pagination.php';
	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/user.php';


	do
	{
		if (!isset($_GET['id']))
		{
			include __DIR__ . '/404.php';
			break;
		}
		$threadId = $_GET['id'];

		$thread = getThread($threadId);

		if ($thread === null)
		{
			include __DIR__ . '/404.php';
			break;
		}

		if (!canView($thread['min_powerlevel']))
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

		$canPost = isBanned() ? false : isModerator() || !$closed;

		renderTemplate('thread_top', [
			'top'           => true,
			'threadId'      => $threadId,
			'threadName'    => $threadName,
			'forumId'       => $forumId,
			'forumName'     => $forumName,
			'canTakeAction' => isLoggedIn() && !isBanned(),
			'moderator'     => isModerator(),
			'canPost'       => $canPost,
			'closed'        => $closed,
			'sticky'        => $sticky
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
				'content'       => nl2br($post['content']),
				'pageInThread'  => getPostPageInThread($post['id'], $threadId),
				'unread'        => $unread,
				'lastEdit'      => getLastEdit($post['id']),
				'canModifyPost' => canModifyPost($post),
				'token'         => getCsrfToken(),
				'author'        => [
					'id'                => $post['author_id'],
					'name'              => $post['author_name'],
					'powerlevelId'      => (int)$post['author_powerlevel'],
					'powerlevel'        => POWERLEVEL_DESCRIPTIONS[$post['author_powerlevel']],
					'title'             => $post['author_title'],
					'rank'              => getRank($post['author_id']),
					'registrationTime'  => date(DEFAULT_DATE_FORMAT, $post['author_registration_time']),
					'currentPostNumber' => getCurrentPostNumber($post['author_id'], $post['id']),
					'numTotalPosts'     => getNumPostsByUser($post['author_id']),
					'signature'         => nl2br(trim($post['author_signature']))
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
			'moderator'     => isModerator(),
			'canPost'       => $canPost,
			'closed'        => $closed,
			'sticky'        => $sticky
		]);

		renderPagination('?p=thread&id=' . $threadId, $page, $numPages);
	}
	while (false);