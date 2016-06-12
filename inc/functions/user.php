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