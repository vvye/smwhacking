<?php

	require_once __DIR__ . '/../functions/forums.php';


	$forumsByCategory = getForumsByCategory();
	$unreadForums = getUnreadForums();

	$categories = [];
	foreach ($forumsByCategory as $categoryName => $forums)
	{
		$forumsForTemplate = [];
		foreach ($forums as $forum)
		{
			if (!canView($forum['min_powerlevel']))
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
		'categories' => $categories
	]);
