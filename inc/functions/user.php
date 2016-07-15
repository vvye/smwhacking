<?php

	require_once __DIR__ . '/../config/user.php';
	require_once __DIR__ . '/../config/forums.php';

	require_once __DIR__ . '/session.php';


	function getNumUsers()
	{
		global $database;

		return $database->count('users');
	}


	function getUsers($page, $sortColumn, $sortDirection)
	{
		global $database;

		$offset = (int)(($page - 1) * USERS_PER_PAGE);
		$numRows = (int)USERS_PER_PAGE;

		$users = $database->query('
			SELECT
				users.id,
				users.name,
				users.location,
				users.website,
				users.powerlevel,
				users.banned,
				users.registration_time,
				users.last_login_time,
				COUNT(posts.id) AS num_posts
			FROM users
			LEFT JOIN posts ON users.id = posts.author AND posts.deleted = 0
			GROUP BY users.id
			ORDER BY ' . $sortColumn . ' ' . $sortDirection . ', id ' . $sortDirection . '
			LIMIT ' . $offset . ', ' . $numRows . '
		')->fetchAll(PDO::FETCH_ASSOC);

		return $users;
	}


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


	function banUser($userId)
	{
		global $database;

		$database->update('users', [
			'banned' => 1
		], [
			'id' => $userId
		]);
	}


	function unbanUser($userId)
	{
		global $database;

		$database->update('users', [
			'banned' => 0
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