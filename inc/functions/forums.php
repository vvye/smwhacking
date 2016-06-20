<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/database.php';


	function getNumThreadsInForum($forumId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('threads', [
			'forum' => $forumId
		]);
	}


	function getNumPostsInForum($forumId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		return $database->count('forums', [
			'[>]threads' => ['id' => 'forum'],
			'[>]posts'   => ['threads.id' => 'thread']
		], 'posts.id', [
			'forums.id' => $forumId
		]);
	}


	function getLastPostInForum($forumId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$lastPost = $database->select('forums', [
			'[>]threads' => ['id' => 'forum'],
			'[>]posts'   => ['threads.id' => 'thread'],
			'[>]users'   => ['posts.author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(post_id)',
			'posts.post_time'
		], [
			'forums.id' => $forumId,
			"ORDER"     => "posts.post_time DESC",
			"LIMIT"     => 1
		]);

		if (count($lastPost) !== 1 || $lastPost[0]['post_id'] == '')
		{
			return null;
		}

		return $lastPost[0];
	}


	function getLastPostCellContent($lastPost)
	{
		if ($lastPost === null)
		{
			$lastPostCellContent = '<em>Keiner</em>';
		}
		else
		{
			$threadId = $lastPost['thread_id'];
			$postId = $lastPost['post_id'];
			$authorId = $lastPost['author_id'];
			$authorName = $lastPost['author_name'];
			$postTime = date(DEFAULT_DATE_FORMAT, $lastPost['post_time']);
			$lastPostCellContent = 'von <a href="?p=user&id=' . $authorId . '">' . $authorName . '</a>'
				. ' <a href="?p=thread&id=' . $threadId . '#post-' . $postId . '"><i class="fa fa-arrow-right"></i></a>'
				. '<p>' . $postTime . '</p>';
		}

		return $lastPostCellContent;
	}


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


	function getPostsInThread($threadId, $page, $database = null)
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
			'ORDER'  => 'post_time ASC',
			'LIMIT'  => [($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE]
		]);

		if ($posts === false)
		{
			return [];
		}

		return $posts;
	}


	function getPostPageInThread($postId, $threadId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$numPostsUpTo = $database->count('posts', [
			'AND' => [
				'thread' => $threadId,
				'id[<=]' => $postId
			]
		]);

		return ceil($numPostsUpTo / POSTS_PER_PAGE);
	}


	function getLastEdit($postId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		$edits = $database->select('edits', [
			'[>]users' => ['user' => 'id']
		], [
			'edits.edit_time',
			'users.id(editor_id)',
			'users.name(editor_name)'
		], [
			'post'  => $postId,
			'ORDER' => 'edit_time DESC',
			'LIMIT' => 1
		]);

		if (count($edits) !== 1)
		{
			return null;
		}

		return $edits[0];
	}


	function addViewToThread($threadId, $database = null)
	{
		$database = ($database !== null) ? $database : getDatabase();

		// TODO reactivate this when testing is done
		/*
		$wasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		if (!$wasRefreshed)
		{
			$database->update('threads', [
				'views[+]' => 1
			], [
				'id' => $threadId
			]);
		}
		*/
	}