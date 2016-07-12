<?php

	require_once __DIR__ . '/../config/user.php';

	require_once __DIR__ . '/database.php';


	function getUser($userId)
	{
		global $database;

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
			'id'    => $userId,
			'LIMIT' => 1
		]);

		if (count($users) !== 1)
		{
			return null;
		}

		return $users[0];
	}


	function getPostsByUser($userId, $page)
	{
		global $database;

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

		if ($posts === false)
		{
			return [];
		}

		return $posts;
	}


	function getNumPostsByUser($userId)
	{
		global $database;

		return $database->count('posts', [
			'AND' => [
				'author'  => $userId,
				'deleted' => 0
			]
		]);
	}


	function getCurrentPostNumber($userId, $postId)
	{
		global $database;

		return $database->count('posts', [
			'AND' => [
				'author' => $userId,
				'id[<=]' => $postId,
				'deleted' => 0
			]
		]);
	}


	function getRank($userId)
	{
		global $database;

		$ranks = $database->select('ranks', '*', [
			'min_posts[<=]' => getNumPostsByUser($userId),
			'ORDER'         => 'min_posts DESC',
			'LIMIT'         => '1',
		]);

		return $ranks[0];
	}


	function getLastPost($userId)
	{
		global $database;

		$posts = $database->select('posts', [
			'[>]threads' => ['thread' => 'id']
		], [
			'posts.id',
			'posts.post_time',
			'threads.id(thread_id)',
			'threads.name(thread_name)'
		], [
			'author' => $userId,
			'ORDER'  => 'post_time DESC',
			'LIMIT'  => 1
		]);

		if (count($posts) !== 1)
		{
			return null;
		}

		return $posts[0];
	}


	function getMedals($userId)
	{
		global $database;

		$medals = $database->select('awarded_medals', [
			'[>]medals'           => ['medal' => 'id'],
			'[>]medal_categories' => ['medals.category' => 'id']
		], [
			'medals.id',
			'medal_categories.name(category_name)',
			'medals.name',
			'medals.description',
			'medals.image_filename',
			'awarded_medals.award_time'
		], [
			'awarded_medals.user' => $userId
		]);

		return $medals;
	}