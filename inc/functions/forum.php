<?php

	require_once __DIR__ . '/../config/forum.php';

	require_once __DIR__ . '/database.php';


	function getThreadsInForum($forumId, $page, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$threads = $database->select('threads', [
			'[>]posts' => ['id' => 'thread'],
			'[>]users' => ['posts.author' => 'id']
		], [
			'threads.id',
			'threads.name',
			'threads.creation_time',
			'threads.last_post_time',
			'threads.views',
			'threads.closed',
			'threads.sticky',
			'threads.id',
			'users.id(author_id)',
			'users.name(author_name)'
		], [
			'threads.forum' => $forumId,
			'GROUP'         => 'threads.id',
			'ORDER'         => [
				'threads.sticky DESC',
				'threads.last_post_time DESC'
			],
			'LIMIT'         => [($page - 1) * THREADS_PER_PAGE, THREADS_PER_PAGE]
		]);

		return $threads;
	}
	
	
	function getNumStickiesInForum($forumId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('threads', [
			'AND' => [
				'forum'  => $forumId,
				'sticky' => 1
			]
		]);
	}


	function getNumPostsInThread($threadId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('posts', [
			'thread' => $threadId,
		]);
	}


	function getLastPostInThread($threadId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$lastPost = $database->select('threads', [
			'[>]posts' => ['threads.id' => 'thread'],
			'[>]users' => ['posts.author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(post_id)',
			'posts.post_time'
		], [
			'threads.id' => $threadId,
			"ORDER"      => "posts.post_time DESC",
			"LIMIT"      => 1
		]);

		if (count($lastPost) !== 1 || $lastPost[0]['post_id'] == '')
		{
			return null;
		}

		return $lastPost[0];
	}