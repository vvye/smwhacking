<?php

	require_once __DIR__ . '/database.php';


	function getNumThreads($forumId, $database)
	{
		return $database->count('threads', [
			'forum' => $forumId
		]);
	}


	function getNumPosts($forumId, $database)
	{
		return $database->count('forums', [
			'[>]threads' => ['id' => 'forum'],
			'[>]posts'   => ['threads.id' => 'thread']
		], 'posts.id', [
			'forums.id' => $forumId
		]);
	}


	function getLastPost($forumId, $database = null)
	{
		if ($database === null)
		{
			$database = getDatabase();
		}

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


	function getLastPostCellContent($forumId, $database = null)
	{
		if ($database === null)
		{
			$database = getDatabase();
		}

		$lastPost = getLastPost($forumId, $database);

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