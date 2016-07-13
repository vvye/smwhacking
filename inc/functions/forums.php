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
			'forums.min_powerlevel',
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
			WHERE (threads_read.last_read_time IS NULL OR threads_read.last_read_time < threads.last_post_time)
			AND threads.deleted = 0
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


	function getNumStickiesInForum($forumId)
	{
		global $database;

		return $database->count('threads', [
			'AND' => [
				'forum'   => $forumId,
				'sticky'  => 1,
				'deleted' => 0
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


	function updateThreadLastReadTime($threadId, $oldLastReadTime, $newLastReadTime)
	{
		global $database;

		if (isLoggedIn() && $newLastReadTime > $oldLastReadTime)
		{
			// medoo doesn't support REPLACE INTO
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


	function canView($minPowerlevel)
	{
		if (!isLoggedIn() || isBanned())
		{
			return (int)$minPowerlevel === 0;
		}

		return (int)$minPowerlevel <= $_SESSION['powerlevel'];
	}


	function canPostInThread($thread)
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}
		if (isModerator())
		{
			return true;
		}
		if (!canView($thread['min_powerlevel']) || $thread['closed'])
		{
			return false;
		}
		return true;
	}


	function canModifyPost($post)
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}
		if (isModerator())
		{
			return true;
		}

		return $post['author_id'] === $_SESSION['userId'];
	}


	function isFirstPostOfThread($postId, $threadId)
	{
		global $database;

		$post = $database->select('posts', 'id', [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'id ASC',
			'LIMIT' => 1
		]);

		if (count($post) !== 1 || $post[0] == '')
		{
			return false;
		}

		return $post[0] === $postId;
	}


	function isLastPostOfThread($postId, $threadId)
	{
		global $database;

		$post = $database->select('posts', 'id', [
			'AND'   => [
				'thread'  => $threadId,
				'deleted' => 0
			],
			'ORDER' => 'id DESC',
			'LIMIT' => 1
		]);

		if (count($post) !== 1 || $post[0] == '')
		{
			return false;
		}

		return $post[0] === $postId;
	}


	function isPostDeleted($postId)
	{
		global $database;

		return $database->count('posts', [
			'AND' => [
				'id'      => $postId,
				'deleted' => 1
			]
		]) === 1;
	}


	function deletePost($postId, $threadId)
	{
		global $database;

		if (isPostDeleted($postId))
		{
			return;
		}

		$wasLastPost = isLastPostOfThread($postId, $threadId);

		$database->update('posts', [
			'deleted' => 1
		], [
			'id' => $postId,
		]);

		$database->update('threads', [
			'posts[-]' => 1
		], [
			'id' => $threadId,
		]);

		$forumId = getForumIdFromThread($threadId);

		$database->update('forums', [
			'posts[-]' => 1
		], [
			'id' => $forumId,
		]);

		if ($wasLastPost)
		{
			updateLastPostInThread($threadId);
		}
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

		$forumId = getForumIdFromThread($threadId);

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

		$forumId = getForumIdFromThread($threadId);

		updateLastPostInForum($forumId);
	}


	function updateLastPostInForum($forumId)
	{
		global $database;

		$lastPostIds = $database->select('posts', [
			'[>]threads' => ['thread' => 'id'],
			'[>]forums'  => ['threads.forum' => 'id']
		], 'posts.id', [
			'AND'   => [
				'forums.id'     => $forumId,
				'posts.deleted' => 0
			],
			'ORDER' => 'id DESC',
			'LIMIT' => 1
		]);
		$lastPostId = $lastPostIds[0];

		$database->update('forums', [
			'last_post' => $lastPostId
		], [
			'id' => $forumId,
		]);
	}


	function getForumIdFromThread($threadId)
	{
		global $database;

		return $database->get('threads', 'forum', [
			'id' => $threadId
		]);
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