<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/session.php';


	function getForumsByCategory()
	{
		global $database;

		$forums = $database->select('forums', [
			'[>]forum_categories' => ['category' => 'id']
		], [
			'forums.id',
			'forums.name',
			'forums.description',
			'forums.min_powerlevel',
			'forums.threads',
			'forums.posts',
			'forums.last_post',
			'forum_categories.name(category_name)'
		], [
			'ORDER' => ['forum_categories.sort_order ASC', 'forums.sort_order ASC']
		]);

		$forumsByCategory = [];
		foreach ($forums as $forum)
		{
			$forumsByCategory[$forum['category_name']][] = $forum;
		}

		return $forumsByCategory;
	}


	function getUnreadForums()
	{
		global $database;

		if (!isLoggedIn())
		{
			return false;
		}

		$unreadForums = $database->query('
			SELECT forums.id
			FROM forums
			LEFT JOIN threads ON forums.id = threads.forum
			LEFT OUTER JOIN threads_read ON threads.id = threads_read.thread AND threads_read.user = ' . $database->quote($_SESSION['userId']) . '
			WHERE threads_read.last_read_time IS NULL OR threads_read.last_read_time < threads.last_post_time
			GROUP BY forums.id 
		')->fetchAll();

		$unreadForumsFlattened = [];
		foreach ($unreadForums as $unreadForum)
		{
			$unreadForumsFlattened[] = $unreadForum['id'];
		}

		return $unreadForumsFlattened;
	}


	function getForum($forumId)
	{
		global $database;

		return $database->select('forums', '*', [
			'id' => $forumId
		]);
	}


	function getPostById($postId)
	{
		global $database;

		$posts = $database->select('posts', [
			'[>]threads' => ['thread' => 'id'],
			'[>]users'   => ['author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(id)',
			'posts.content',
			'posts.post_time'
		], [
			'posts.id' => $postId
		]);

		if (count($posts) !== 1 || $posts[0]['id'] == '')
		{
			return null;
		}

		return $posts[0];
	}


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
				threads_read.last_read_time
				FROM threads
				LEFT JOIN forums ON threads.forum = forums.id
				LEFT JOIN threads_read ON threads.id = threads_read.thread AND ' . 'threads_read.user = ' . $database->quote($_SESSION['userId']) . '
				WHERE threads.id = ' . $database->quote($threadId) . '
			')->fetchAll();
		}
		else
		{
			$threads = $database->query('
				SELECT threads.id,
				threads.name,
				threads.posts,
				threads.last_post,
				forums.id AS forum_id,
				forums.name AS forum_name
				FROM threads
				LEFT JOIN forums ON threads.forum = forums.id
				WHERE threads.id = ' . $database->quote($threadId) . '
			')->fetchAll();
		}

		if (count($threads) !== 1 || $threads[0]['id'] == '')
		{
			return null;
		}

		return $threads[0];
	}


	function getNumStickiesInForum($forumId)
	{
		global $database;

		return $database->count('threads', [
			'AND' => [
				'forum'  => $forumId,
				'sticky' => 1
			]
		]);
	}


	function getPostsInThread($threadId, $page)
	{
		global $database;

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
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
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


	function getPostPageInThread($postId, $threadId)
	{
		global $database;

		$numPostsUpTo = $database->count('posts', [
			'AND' => [
				'thread'  => $threadId,
				'id[<=]'  => $postId,
				'deleted' => 0
			]
		]);

		return ceil($numPostsUpTo / POSTS_PER_PAGE);
	}


	function getLastEdit($postId)
	{
		global $database;

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


	function updateThreadLastReadTime($threadId, $oldLastReadTime, $newlastReadTime)
	{
		global $database;

		if (isLoggedIn() && $newlastReadTime > $oldLastReadTime)
		{
			// medoo doesn't support REPLACE INTO
			$userId = $database->quote($_SESSION['userId']);
			$threadId = $database->quote($threadId);
			$newlastReadTime = $database->quote($newlastReadTime);
			$database->query('
				REPLACE INTO threads_read(user, thread, last_read_time)
				VALUES (' . $userId . ', ' . $threadId . ', ' . $newlastReadTime . ')
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


	function markForumAsRead($forumId = null)
	{
		global $database;

		if (!isLoggedIn())
		{
			renderMessage(MSG_MARK_READ_NOT_LOGGED_IN);

			return;
		}

		$condition = ($forumId !== null) ? [
			'forum' => $forumId
		] : null;

		$threadIds = $database->select('threads', 'id', $condition);

		$userId = $database->quote($_SESSION['userId']);
		$lastReadTime = time();

		$data = [];
		foreach ($threadIds as $threadId)
		{
			$data[] = '(' . $userId . ', ' . $threadId . ', ' . $lastReadTime . ')';
		}
		$values = join(', ', $data);

		try
		{
			$database->query('
				REPLACE INTO threads_read(user, thread, last_read_time)
				VALUES ' . $values
			);
			renderSuccessMessage(($forumId !== null) ? MSG_MARK_READ_SUCCESS : MSG_MARK_ALL_READ_SUCCESS);
		}
		catch (Exception $e)
		{
			renderErrorMessage(MSG_MARK_READ_ERROR);
		}
	}


	function markAllForumsAsRead()
	{
		markForumAsRead(null);
	}


	function createPost($threadId, $postText, $postTime = null)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$postTime = $postTime ?: time();

		$newPostId = $database->insert('posts', [
			'id'        => null,
			'thread'    => $threadId,
			'author'    => $_SESSION['userId'],
			'post_time' => $postTime,
			'content'   => htmlspecialchars($postText),
			'deleted'   => 0
		]);
		$database->update('threads', [
			'posts[+]'       => 1,
			'last_post'      => $newPostId,
			'last_post_time' => $postTime
		], [
			'id' => $threadId
		]);

		$forumIds = $database->select('forums', [
			'[>]threads' => ['id' => 'forum']
		], 'forums.id', [
			'threads.id' => $threadId
		]);
		$forumId = $forumIds[0];

		$database->update('forums', [
			'posts[+]'  => 1,
			'last_post' => $newPostId
		], [
			'id' => $forumId
		]);

		return $newPostId;
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


	function isClosed($threadId)
	{
		global $database;

		return $database->count('threads', [
			'AND' => [
				'id'     => $threadId,
				'closed' => 1
			]
		]) === 1;
	}


	function isSticky($threadId)
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


	function editPost($postId, $postText)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$newPostId = $database->update('posts', [
			'content' => htmlspecialchars($postText),
		], [
			'id' => $postId
		]);

		$database->insert('edits', [
			'post'      => $postId,
			'user'      => $_SESSION['userId'],
			'edit_time' => time()
		]);

		return $newPostId;
	}


	function canModifyPost($post)
	{
		return ($post['author_id'] === $_SESSION['userId'] || isModerator()) && !isBanned();
	}