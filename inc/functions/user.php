<?php

	require_once __DIR__ . '/../config/user.php';

	require_once __DIR__ . '/database.php';


	function getUser($userId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();
		
		$users = $database->select('users', [
			'id',
		    'name',
			'title',
			'powerlevel',
			'signature',
			'registration_time',
			'last_login_time',
			'bio',
			'website',
			'email',
		    'banned'
		], [
			'id' => $userId,
		    'LIMIT' => 1
		]);

		if (count($users) !== 1)
		{
			return null;
		}

		return $users[0];
	}


	function getPostsByUser($userId, $page, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$posts = $database->select('posts', [
			'[>]threads' => ['thread' => 'id'],
		], [
			'posts.id',
			'posts.post_time',
			'posts.content',
			'threads.id(thread_id)',
		    'threads.name(thread_name)'
		], [
			'author' => $userId,
			'ORDER'  => 'post_time ASC',
			'LIMIT'  => [($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE]
		]);

		return $posts;
	}
	
	
	function getNumPostsByUser($userId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('posts', [
			'author' => $userId
		]);
	}


	function getCurrentPostNumber($userId, $postId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('posts', [
			'AND' => [
				'author' => $userId,
				'id[<=]' => $postId
			]
		]);
	}


	function getAvatarHtml($userId)
	{
		return '<img class="avatar" src="img/avatars/' . $userId . '.png" alt="Avatar" />';
	}


	function getRankHtml($userId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$ranks = $database->select('ranks', '*', [
			'min_posts[<=]' => getNumPostsByUser($userId, $database),
			'ORDER'         => 'min_posts DESC',
			'LIMIT'         => '1',
		]);
		$rank = $ranks[0];

		// TODO check if file exists?
		$imageHtml = $rank['has_image'] ? '<img src="img/ranks/' . $rank['id'] . '.png" alt="' . $rank['name'] . '" />' : '';
		
		return '<p>' . $rank['name'] . '</p>' . $imageHtml;
	}

	// TODO refactor?
	function getProfileRankHtml($userId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$ranks = $database->select('ranks', '*', [
			'min_posts[<=]' => getNumPostsByUser($userId, $database),
			'ORDER'         => 'min_posts DESC',
			'LIMIT'         => '1',
		]);
		$rank = $ranks[0];

		// TODO check if file exists?
		$imageHtml = $rank['has_image'] ? '<img src="img/ranks/' . $rank['id'] . '.png" alt="' . $rank['name'] . '" />' : '';

		return $imageHtml . ' ' . $rank['name'];
	}


	function getLastPost($userId, $database)
	{
		$database = ($database !== null) ? $database : getDatabase();
		
		$posts = $database->select('posts', [
			'[>]threads' => ['thread' => 'id']
		], [
			'posts.id',
			'posts.post_time',
		    'threads.id(thread_id)',
		    'threads.name(thread_name)'
		], [
			'author' => $userId,
		    'ORDER' => 'post_time DESC',
		    'LIMIT' => 1
		]);

		if (count($posts) !== 1)
		{
			return null;
		}
		
		return $posts[0];
	}