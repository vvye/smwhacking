<?php

	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/post.php';
	require_once __DIR__ . '/../functions/permissions.php';


	if (isset($_GET['mark-read']))
	{
		markAllForumsAsRead();
	}

	$forumsByCategory = getForumsByCategory();
	$unreadForums = getUnreadForumIds();

	$categories = [];
	foreach ($forumsByCategory as $categoryName => $forums)
	{
		$forumsForTemplate = [];
		foreach ($forums as $forum)
		{
			if (!canView($forum['view_powerlevel']))
			{
				continue;
			}
			
			$unread = isLoggedIn() && in_array($forum['id'], $unreadForums);
			$lastPost = getPostById($forum['last_post']);

			$forumsForTemplate[] = [
				'id'           => $forum['id'],
				'name'         => $forum['name'],
				'unread'       => $unread,
				'description'  => $forum['description'],
				'numThreads'   => $forum['threads'],
				'numPosts'     => $forum['posts'],
				'lastPost'     => $lastPost,
				'lastPostPage' => getPostPageInThread($forum['last_post'], $lastPost['thread_id'])
			];
		}

		$categories[] = [
			'name'   => $categoryName,
			'forums' => $forumsForTemplate
		];
	}

	renderTemplate('forum_list', [
		'loggedIn'   => isLoggedIn(),
		'categories' => $categories
	]);
