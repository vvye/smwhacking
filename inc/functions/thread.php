<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/forums.php';
	require_once __DIR__ . '/post.php';
	require_once __DIR__ . '/permissions.php';
	require_once __DIR__ . '/session.php';


	function getThreadsInForum($forumId, $page)
	{
		global $database;

		$readCondition = isLoggedIn() ? 'threads_read.user = ' . $database->quote($_SESSION['userId']) : '1';

		$threads = $database->query('
			SELECT threads.id,
				threads.name,
				threads.creation_time,
				threads.last_post_time,
				threads.views,
				threads.posts,
				threads.last_post,
				threads.closed,
				threads.sticky,
				users.id AS author_id,
				users.name AS author_name,
				threads_read.last_read_time
			FROM threads
			LEFT JOIN posts ON threads.id = posts.thread
			LEFT JOIN users ON posts.author = users.id
			LEFT JOIN threads_read ON threads.id = threads_read.thread AND ' . $readCondition . '
			WHERE threads.forum = ' . $database->quote($forumId) . '
			AND threads.deleted = 0
			GROUP BY threads.id
			ORDER BY threads.sticky DESC,
			threads.last_post_time DESC
			LIMIT ' . (int)(($page - 1) * THREADS_PER_PAGE) . ', ' . POSTS_PER_PAGE . '
		');

		return $threads;
	}


	function getThread($threadId)
	{
		global $database;

		if (isLoggedIn())
		{
			$threads = $database->query('
				SELECT threads.id,
				threads.name,
				threads.posts,
				threads.last_post,
				threads.closed,
				threads.sticky,
				forums.id AS forum_id,
				forums.name AS forum_name,
				forums.min_powerlevel,
				threads_read.last_read_time
				FROM threads
				LEFT JOIN forums ON threads.forum = forums.id
				LEFT JOIN threads_read ON threads.id = threads_read.thread AND ' . 'threads_read.user = ' . $database->quote($_SESSION['userId']) . '
				WHERE threads.id = ' . $database->quote($threadId) . ' AND threads.deleted = 0;
			')->fetchAll();
		}
		else
		{
			$threads = $database->query('
				SELECT threads.id,
				threads.name,
				threads.posts,
				threads.last_post,
				threads.closed,
				threads.sticky,
				forums.id AS forum_id,
				forums.name AS forum_name,
				forums.min_powerlevel
				FROM threads
				LEFT JOIN forums ON threads.forum = forums.id
				WHERE threads.id = ' . $database->quote($threadId) . ' AND threads.deleted = 0;		
			')->fetchAll();
		}

		if (count($threads) !== 1 || $threads[0]['id'] == '')
		{
			return null;
		}

		return $threads[0];
	}


	function updateThreadLastReadTime($threadId, $oldLastReadTime, $newLastReadTime)
	{
		global $database;

		if (isLoggedIn() && $newLastReadTime > $oldLastReadTime)
		{
			$userId = $database->quote($_SESSION['userId']);
			$threadId = $database->quote($threadId);

			$newLastReadTime = $database->quote($newLastReadTime);
			$database->query('
				REPLACE INTO threads_read(user, thread, last_read_time)
				VALUES (' . $userId . ', ' . $threadId . ', ' . $newLastReadTime . ')
			');
		}
	}


	function addViewToThread($threadId)
	{
		global $database;

		$wasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		if (!$wasRefreshed)
		{
			$database->update('threads', [
				'views[+]' => 1
			], [
				'id' => $threadId
			]);
		}
	}


	function createThread($forumId, $threadTitle, $postText)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$postTime = time();

		$newThreadId = $database->insert('threads', [
			'id'             => null,
			'forum'          => $forumId,
			'name'           => htmlspecialchars($threadTitle),
			'creation_time'  => $postTime,
			'posts'          => 0,
			'last_post_time' => $postTime,
			'views'          => 0
		]);

		createPost($newThreadId, $postText, $postTime);

		$database->update('forums', [
			'threads[+]' => 1
		], [
			'id' => $forumId
		]);

		return $newThreadId;
	}


	function editThreadTitle($threadId, $threadTitle)
	{
		global $database;

		$database->update('threads', [
			'name' => htmlspecialchars($threadTitle)
		], [
			'id' => $threadId
		]);
	}


	function isThreadClosed($threadId)
	{
		global $database;

		return $database->count('threads', [
			'AND' => [
				'id'     => $threadId,
				'closed' => 1
			]
		]) === 1;
	}


	function isThreadSticky($threadId)
	{
		global $database;

		return $database->count('threads', [
			'AND' => [
				'id'     => $threadId,
				'sticky' => 1
			]
		]) === 1;
	}


	function closeThread($threadId)
	{
		global $database;

		$database->update('threads', [
			'closed' => 1,
		], [
			'id' => $threadId
		]);
	}


	function openThread($threadId)
	{
		global $database;

		$database->update('threads', [
			'closed' => 0,
		], [
			'id' => $threadId
		]);
	}


	function stickyThread($threadId)
	{
		global $database;

		$database->update('threads', [
			'sticky' => 1,
		], [
			'id' => $threadId
		]);
	}


	function unstickyThread($threadId)
	{
		global $database;

		$database->update('threads', [
			'sticky' => 0,
		], [
			'id' => $threadId
		]);
	}


	function deleteThread($threadId)
	{
		global $database;

		$postIds = $database->select('posts', 'id', [
			'AND' => [
				'thread'  => $threadId,
				'deleted' => 0
			]
		]);
		$numPostsToDelete = count($postIds);
		$database->update('posts', [
			'deleted' => 1
		], [
			'id' => $postIds,
		]);
		$database->update('threads', [
			'deleted'  => 1,
			'posts[-]' => $numPostsToDelete
		], [
			'id' => $threadId,
		]);

		$forumId = getForumIdByThreadId($threadId);

		$database->update('forums', [
			'posts[-]' => $numPostsToDelete
		], [
			'id' => $forumId,
		]);

		$database->update('forums', [
			'threads[-]' => 1
		], [
			'id' => $forumId,
		]);

		updateLastPostInForum($forumId);
	}


	function updateLastPostInThread($threadId)
	{
		global $database;

		$lastPostIds = $database->select('posts', 'id', [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'id DESC',
			'LIMIT' => 1
		]);

		if (!isset($lastPostIds[0])) // only post in thread
		{
			return;
		}

		$lastPostId = $lastPostIds[0];
		$lastPost = getPostById($lastPostId);

		$database->update('threads', [
			'last_post'      => $lastPostId,
			'last_post_time' => $lastPost['post_time']
		], [
			'id' => $threadId,
		]);

		$forumId = getForumIdByThreadId($threadId);

		updateLastPostInForum($forumId);
	}