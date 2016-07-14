<?php

	require_once __DIR__ . '/../config/user.php';

	require_once __DIR__ . '/session.php';


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
			'location',
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
			'[>]forums'  => ['threads.forum' => 'id']
		], [
			'posts.id',
			'posts.post_time',
			'posts.content',
			'threads.id(thread_id)',
			'threads.name(thread_name)',
			'forums.min_powerlevel(min_powerlevel)'
		], [
			'AND'   => [
				'author'        => $userId,
				'posts.deleted' => 0
			],
			'ORDER' => 'post_time ASC',
			'LIMIT' => [($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE]
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
				'author'  => $userId,
				'id[<=]'  => $postId,
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


	function getLastPostByUser($userId)
	{
		global $database;

		$posts = $database->select('posts', [
			'[>]threads' => ['thread' => 'id'],
			'[>]forums'  => ['threads.forum' => 'id']
		], [
			'posts.id',
			'posts.post_time',
			'threads.id(thread_id)',
			'threads.name(thread_name)',
			'forums.min_powerlevel'
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


	function setUserData($userId, $data)
	{
		global $database;

		$database->update('users', [
			'email'     => strtolower(htmlspecialchars($data['email'])),
			'location'  => htmlspecialchars($data['location']),
			'website'   => htmlspecialchars($data['website']),
			'bio'       => htmlspecialchars($data['bio']),
			'signature' => htmlspecialchars($data['signature'])
		], [
			'id' => $userId
		]);
	}


	function setUserTitle($userId, $title)
	{
		global $database;

		$database->update('users', [
			'title' => htmlspecialchars($title)
		], [
			'id' => $userId
		]);
	}


	function hasAvatar($userId)
	{
		return file_exists(__DIR__ . '/../../img/avatars/' . $userId . '.png');
	}


	function banUser($userId)
	{
		global $database;

		$database->update('users', [
			'banned' => 1
		], [
			'id' => $userId
		]);
	}


	function setPowerlevel($userId, $powerlevel)
	{
		global $database;

		$database->update('users', [
			'powerlevel' => $powerlevel
		], [
			'id' => $userId
		]);
	}