<?php

	// redirect old phpBB URLs

	require_once __DIR__ . '/../inc/functions/database.php';
	require_once __DIR__ . '/../inc/functions/post.php';

	require_once __DIR__ . '/../inc/config/misc.php';

	$database = getDatabase();


	if (isset($_GET['t']) && is_int($_GET['t'] * 1))
	{
		$threadId = $_GET['t'] * 1;

		$newUrl = WEBSITE_URL . '/?p=thread&id=' . $threadId;

		if (isset($_GET['p']) && is_int($_GET['p'] * 1))
		{
			$postId = $_GET['p'] * 1;
			$page = getPostPageInThread($postId, $threadId);

			$newUrl .= '&page=' . $page . '#post-' . $postId;
		}
	}
	else
	{
		$newUrl = WEBSITE_URL . '/?p=forums';
	}

	header('Location: ' . $newUrl);