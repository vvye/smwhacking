<?php

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