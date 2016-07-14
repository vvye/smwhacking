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


	function getUnreadForumIds()
	{
		global $database;

		if (!isLoggedIn())
		{
			return false;
		}

		$unreadForumIds = $database->query('
			SELECT forums.id
			FROM forums
			LEFT JOIN threads ON forums.id = threads.forum
			LEFT OUTER JOIN threads_read ON threads.id = threads_read.thread AND threads_read.user = ' . $database->quote($_SESSION['userId']) . '
			WHERE (threads_read.last_read_time IS NULL OR threads_read.last_read_time < threads.last_post_time)
			AND threads.deleted = 0
			GROUP BY forums.id 
		')->fetchAll();

		$unreadForumIdsFlattened = [];
		foreach ($unreadForumIds as $unreadForum)
		{
			$unreadForumIdsFlattened[] = $unreadForum['id'];
		}

		return $unreadForumIdsFlattened;
	}


	function getForum($forumId)
	{
		global $database;

		$forum = $database->get('forums', '*', [
			'id' => $forumId
		]);

		if (!is_array($forum) || empty($forum))
		{
			return null;
		}

		return $forum;
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


	function getForumIdByThreadId($threadId)
	{
		global $database;

		return $database->get('threads', 'forum', [
			'id' => $threadId
		]);
	}