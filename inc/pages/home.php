<?php

	require_once __DIR__ . '/../functions/news.php';
	require_once __DIR__ . '/../functions/avatar.php';
	require_once __DIR__ . '/../functions/bbcode.php';

	require_once __DIR__ . '/../config/misc.php';


	$news = getLatestNews();
	$newsForTemplate = [];

	foreach ($news as $newsArticle)
	{
		$newsForTemplate[] = [
			'id'                => $newsArticle['id'],
			'name'              => $newsArticle['name'],
			'creation_time'     => $newsArticle['creation_time'],
			'replies'           => $newsArticle['posts'] - 1,
			'author'            => $newsArticle['author'],
			'author_name'       => $newsArticle['author_name'],
			'author_has_avatar' => hasAvatar($newsArticle['author']),
			'content'           => truncateNewsPost(removeBBCodeAndLineBreaks($newsArticle['content']))
		];
	}

	renderTemplate('home', [
		'news'    => $newsForTemplate,
		'numNews' => count($news)
	]);