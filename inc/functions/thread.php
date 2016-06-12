<?php

	require_once __DIR__ . '/../functions/database.php';


	function getPostsInThread($threadId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$posts = $database->select('posts', [
			'[>]users' => ['author' => 'id'],
		], [
			'posts.id',
			'posts.post_time',
			'posts.content',
			'users.id(author_id)',
			'users.name(author_name)',
			'users.powerlevel(author_powerlevel)',
			'users.title(author_title)',
			'users.signature(author_signature)',
			'users.registration_time(author_registration_time)',
			'users.banned(author_banned)'
		], [
			'thread' => $threadId,
			'ORDER'  => 'post_time ASC'
		]);

		return $posts;
	}