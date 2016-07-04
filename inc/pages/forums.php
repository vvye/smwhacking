<h2>Forum</h2>

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

			$forumsForTemplate[] = [
				'id'                  => $forum['id'],
				'name'                => $forum['name'],
				'new'                 => $unread ? MSG_NEW : '',
				'description'         => $forum['description'],
				'numThreads'          => $forum['num_threads'],
				'numPosts'            => $forum['num_posts'],
				'lastPostCellContent' => getLastPostCellContent(getPostById($forum['last_post']))
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
