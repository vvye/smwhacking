<?php

	require_once __DIR__ . '/../functions/forums.php';;


	$forumsByCategory = getForumsByCategory();
	$unreadForums = getUnreadForums();

	$categories = [];
	foreach ($forumsByCategory as $categoryName => $forums)
	{
		$forumsForTemplate = [];
		foreach ($forums as $forum)
		{
			$unread = isLoggedIn() && in_array($forum['id'], $unreadForums);
			$lastPost = getPostById($forum['last_post']);
			
			$forumsForTemplate[] = [
				'id'          => $forum['id'],
				'name'        => $forum['name'],
				'unread'      => $unread,
				'description' => $forum['description'],
				'numThreads'  => $forum['num_threads'],
				'numPosts'    => $forum['num_posts'],
				'lastPost'    => $lastPost,
				'lastPostPage' => '' // TODO // getPostPageInThread($forum['last_post'])
			];
		}

		$categories[] = [
			'name'   => $categoryName,
			'forums' => $forumsForTemplate
		];
	}

	renderTemplate('forum_list', [
		'categories' => $categories
	]);
