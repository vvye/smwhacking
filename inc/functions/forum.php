<?php

	require_once __DIR__ . '/database.php';


	function getThreadsInForum($forumId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		// FIXME why does this not work
		/*
		$threads = $database->select('threads', [
			'[>]posts' => ['id' => 'thread'],
			'[>]users' => ['posts.author' => 'id']
		], [
			'threads.*',
			'users.id(author_id)',
			'users.name(author_name)'
		], [
			'threads.forum' => $forumId,
			'GROUP'         => 'threads.id',
			'ORDER'         => [
				'threads.sticky DESC',
				'threads.last_post_time DESC'
			]
		]);
		*/

		$threads = $database->query('
			SELECT threads.*, users.id AS author_id, users.name AS author_name
			FROM threads
			LEFT JOIN posts ON threads.id = posts.thread
			LEFT JOIN users ON posts.author = users.id
			WHERE threads.forum = ' . $database->quote($forumId) . '
			GROUP BY threads.id
			ORDER BY threads.sticky DESC,threads.last_post_time DESC 
		')->fetchAll();

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