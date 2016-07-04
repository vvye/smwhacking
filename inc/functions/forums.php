<?php

	require_once __DIR__ . '/../config/forums.php';
	require_once __DIR__ . '/../config/misc.php';

	require_once __DIR__ . '/session.php';
	require_once __DIR__ . '/database.php';


	function getForumCategories()
	{
		global $database;

		$categories = $database->select('forum_categories', [
			'id',
			'name'
		], [
			'ORDER' => 'sort_order ASC'
		]);

		return $categories;
	}


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
			'forums.num_threads',
			'forums.num_posts',
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


	function getNumThreadsByForum()
	{
		global $database;

		$threadsByForum = $database->query('
			SELECT forums.id AS forum, COUNT(threads.id) AS num_threads
			FROM forums
			LEFT JOIN threads ON forums.id = threads.forum
			GROUP BY forums.id
		')->fetchAll();

		$numThreadsByForum = [];
		foreach ($threadsByForum as $row)
		{
			$numThreadsByForum[$row['forum']] = $row['num_threads'];
		}

		return $numThreadsByForum;
	}


	function getNumThreadsInForum($forumId)
	{
		global $database;

		return $database->count('threads', [
			'forum' => $forumId
		]);
	}


	function getNumPostsByForum()
	{
		global $database;

		$postsByForum = $database->query('
			SELECT forums.id AS forum, COUNT(posts.id) AS num_posts
			FROM forums
			LEFT JOIN threads ON forums.id = threads.forum
			LEFT JOIN posts ON threads.id = posts.thread
			GROUP BY forums.id
		')->fetchAll();

		$numPostsByForum = [];
		foreach ($postsByForum as $row)
		{
			$numPostsByForum[$row['forum']] = $row['num_posts'];
		}

		return $numPostsByForum;
	}


	function getNumPostsInForum($forumId)
	{
		global $database;

		return $database->count('forums', [
			'[>]threads' => ['id' => 'forum'],
			'[>]posts'   => ['threads.id' => 'thread']
		], 'posts.id', [
			'forums.id' => $forumId
		]);
	}


	function getLastPostInForum($forumId)
	{
		global $database;

		$lastPost = $database->select('forums', [
			'[>]threads' => ['id' => 'forum'],
			'[>]posts'   => ['threads.id' => 'thread'],
			'[>]users'   => ['posts.author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(id)',
			'posts.post_time'
		], [
			'forums.id' => $forumId,
			"ORDER"     => "posts.post_time DESC",
			"LIMIT"     => 1
		]);

		if (count($lastPost) !== 1 || $lastPost[0]['id'] == '')
		{
			return null;
		}

		return $lastPost[0];
	}


	function getPostById($postId)
	{
		global $database;

		$lastPost = $database->select('posts', [
			'[>]threads' => ['thread' => 'id'],
			'[>]users'   => ['author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(id)',
			'posts.post_time'
		], [
			'posts.id' => $postId
		]);

		if (count($lastPost) !== 1 || $lastPost[0]['id'] == '')
		{
			return null;
		}

		return $lastPost[0];
	}


	function getLastPostCellContent($lastPost)
	{
		if ($lastPost === null)
		{
			$lastPostCellContent = '<em>' . MSG_NONE . '</em>';
		}
		else
		{
			$threadId = $lastPost['thread_id'];
			$postId = $lastPost['id'];
			$authorId = $lastPost['author_id'];
			$authorName = $lastPost['author_name'];
			$postTime = date(DEFAULT_DATE_FORMAT, $lastPost['post_time']);
			$lastPostCellContent = 'von <a href="?p=user&id=' . $authorId . '">' . $authorName . '</a>'
				. ' <a href="?p=thread&id=' . $threadId . '#post-' . $postId . '"><i class="fa fa-arrow-right"></i></a>'
				. '<p>' . $postTime . '</p>';
		}

		return $lastPostCellContent;
	}


	function getThreadsInForum($forumId, $page)
	{
		global $database;

		/*
		// medoo doesn't seem to support the join syntax on the threads_read_table
		$threads = $database->select('threads', [
			'[>]posts'        => ['id' => 'thread'],
			'[>]users'        => ['posts.author' => 'id'],
			'[>]threads_read' => ['id' => 'thread', 'threads_read.user' => 2],
		], [
			'threads.id',
			'threads.name',
			'threads.creation_time',
			'threads.last_post_time',
			'threads.views',
			'threads.closed',
			'threads.sticky',
			'users.id(author_id)',
			'users.name(author_name)',
			'threads_read.last_read_time'
		], [
			'AND'   => [
				'threads.forum'     => $forumId
			],
			'GROUP' => 'threads.id',
			'ORDER' => [
				'threads.sticky DESC',
				'threads.last_post_time DESC'
			],
			'LIMIT' => [($page - 1) * THREADS_PER_PAGE, THREADS_PER_PAGE]
		]);
		*/

		$readCondition = isLoggedIn() ? 'threads_read.user = ' . $database->quote($_SESSION['userId']) : '1';

		$threads = $database->query('
			SELECT threads.id,
				threads.name,
				threads.creation_time,
				threads.last_post_time,
				threads.views,
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

		return $database->select('threads', [
			'[>]forums' => ['forum' => 'id']
		], [
			'threads.id',
			'threads.name',
			'forums.id(forum_id)',
			'forums.name(forum_name)'
		], [
			'threads.id' => $threadId
		]);
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


	function getNumPostsInThread($threadId)
	{
		global $database;

		return $database->count('posts', [
			'thread' => $threadId,
		]);
	}


	function getLastPostInThread($threadId)
	{
		global $database;

		$lastPost = $database->select('threads', [
			'[>]posts' => ['threads.id' => 'thread'],
			'[>]users' => ['posts.author' => 'id']
		], [
			'threads.id(thread_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'posts.id(id)',
			'posts.post_time'
		], [
			'threads.id' => $threadId,
			"ORDER"      => "posts.post_time DESC",
			"LIMIT"      => 1
		]);

		if (count($lastPost) !== 1 || $lastPost[0]['id'] == '')
		{
			return null;
		}

		return $lastPost[0];
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


	function getPostPageInThread($postId, $threadId)
	{
		global $database;

		$numPostsUpTo = $database->count('posts', [
			'AND' => [
				'thread' => $threadId,
				'id[<=]' => $postId
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


	function markThreadAsRead($threadId, $lastReadTime)
	{
		global $database;

		if (isset($_SESSION['userId']))
		{
			// medoo doesn't support REPLACE INTO
			$userId = $database->quote($_SESSION['userId']);
			$threadId = $database->quote($threadId);
			$lastReadTime = $database->quote($lastReadTime);
			$database->query('
				REPLACE INTO threads_read(user, thread, last_read_time)
				VALUES (' . $userId . ', ' . $threadId . ', ' . $lastReadTime . ')
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


	function markForumAsRead($forumId)
	{
		global $database;

		if (!isLoggedIn())
		{
			renderMessage('Du kannst Foren nur als gelesen markieren, wenn du eingeloggt bist.');

			return;
		}

		$threadIds = $database->select('threads', 'id', [
			'forum' => $forumId
		]);

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
			renderSuccessMessage(MSG_MARK_READ_SUCCESS);
		}
		catch (Exception $e)
		{
			renderErrorMessage(MSG_MARK_READ_ERROR);
		}
	}


	function markAllForumsAsRead()
	{
		global $database;

		if (!isLoggedIn())
		{
			renderMessage(MSG_MARK_READ_NOT_LOGGED_IN);

			return;
		}

		try
		{
			$database->delete('threads_read', [
				'user' => $_SESSION['userId'],
			]);

			$database->delete('forums_read', [
				'user' => $_SESSION['userId'],
			]);

			$database->update('users', [
				'last_read_time' => time()
			], [
				'id' => $_SESSION['userId']
			]);

			renderSuccessMessage(MSG_MARK_READ_SUCCESS);
		}
		catch (Exception $e)
		{
			renderErrorMessage(MSG_MARK_READ_ERROR);
		}
	}