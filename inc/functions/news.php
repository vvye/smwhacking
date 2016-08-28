<?php

	require_once __DIR__ . '/../config/news.php';


	function getLatestNews()
	{
		global $database;

		$threads = $database->query('
			SELECT threads.id, threads.name, threads.creation_time, threads.posts, first_posts.author, users.name AS author_name, first_posts.content
			FROM threads
			LEFT JOIN (
				SELECT MIN(id), thread, author, content
				FROM posts
				GROUP BY thread) first_posts
			ON threads.id = first_posts.thread
			LEFT JOIN users ON first_posts.author = users.id
			WHERE threads.forum = ' . NEWS_FORUM_ID . '
			AND threads.deleted = 0
			ORDER BY creation_time DESC
			LIMIT ' . NUM_LATEST_NEWS . '
		')->fetchAll(PDO::FETCH_ASSOC);

		return $threads;
	}


	function truncateNewsPost($text)
	{
		if (strlen($text) <= NEWS_POST_TRUNCATE_LENGTH)
		{
			return $text;
		}

		return substr($text, 0, NEWS_POST_TRUNCATE_LENGTH) . '&hellip;';
	}