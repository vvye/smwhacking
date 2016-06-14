<?php

	require_once __DIR__ . '/../config/user.php';

	require_once __DIR__ . '/database.php';


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