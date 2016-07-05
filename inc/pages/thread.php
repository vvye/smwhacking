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

		$threads = getThread($threadId);

		if (count($threads) !== 1)
		{
			include __DIR__ . '/404.php';
			break;
		}
		$thread = $threads[0];

		addViewToThread($threadId);

		$threadName = $thread['name'];
		$forumId = $thread['forum_id'];
		$forumName = $thread['forum_name'];

		renderTemplate('thread_top', [
			'top'        => true,
			'threadId'   => $threadId,
			'threadName' => $threadName,
			'forumId'    => $forumId,
			'forumName'  => $forumName
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
				'inThread'     => true,
				'id'           => $post['id'],
				'threadId'     => $threadId,
				'postTime'     => date(DEFAULT_DATE_FORMAT, $post['post_time']),
				'content'      => nl2br($post['content']),
				'pageInThread' => getPostPageInThread($post['id'], $threadId),
				'unread'       => $unread,
				'lastEdit'     => getLastEdit($post['id']),
				'author'       => [
					'id'                => $post['author_id'],
					'name'              => $post['author_name'],
					'powerlevelId'      => (int)$post['author_powerlevel'],
					'powerlevel'        => POWERLEVEL_DESCRIPTIONS[$post['author_powerlevel']],
					'title'             => $post['author_title'],
					'rankHtml'          => getRankHtml($post['author_id']),
					'avatarHtml'        => getAvatarHtml($post['author_id']),
					'registrationTime'  => date(DEFAULT_DATE_FORMAT, $post['author_registration_time']),
					'currentPostNumber' => getCurrentPostNumber($post['author_id'], $post['id']),
					'numTotalPosts'     => $numPosts,
					'signature'         => nl2br(trim($post['author_signature']))
				]
			]);
		}

		renderTemplate('thread_top', [
			'top'        => false,
			'threadId'   => $threadId,
			'threadName' => $threadName,
			'forumId'    => $forumId,
			'forumName'  => $forumName
		]);

		renderPagination('?p=thread&id=' . $threadId, $page, $numPages);
	}
	while (false);