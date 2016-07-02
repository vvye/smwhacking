<h2>Forum</h2>

<?php

	require_once __DIR__ . '/../functions/forums.php';


	$categories = getForumCategories();

	$categoriesForTemplate = [];
	foreach ($categories as $category)
	{
		$forums = getForumsByCategory($category['id']);

		$forumsForTemplate = [];
		foreach ($forums as $forum)
		{
			$forumsForTemplate[] = [
				'id'                  => $forum['id'],
				'name'                => $forum['name'],
				'new'                 => 'NEU', // isLoggedIn() && $forum['last_read_time'] < $forum['last_post_time'] ? 'NEU' : '', // TODO
				'description'         => $forum['description'],
				'numThreads'          => getNumThreadsInForum($forum['id']),
				'numPosts'            => getNumPostsInForum($forum['id']),
				'lastPostCellContent' => getLastPostCellContent(getLastPostInForum($forum['id']))
			];
		}

		$categoriesForTemplate[] = [
			'name'   => $category['name'],
			'forums' => $forumsForTemplate
		];
	}

	renderTemplate('forum_list', [
		'categories' => $categoriesForTemplate
	]);
