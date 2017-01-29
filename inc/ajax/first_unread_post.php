<?php

	session_start();

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/thread.php';
	require_once __DIR__ . '/../functions/post.php';

	$database = getDatabase();


	header('Content-type: text/json');


	if (!isset($_GET['thread']))
	{
		die();
	}

	$threadId = (int)$_GET['thread'] * 1;
	$thread = getThread($threadId);
	if ($thread === null)
	{
		die();
	}

	$postId = getFirstUnreadPostIdInThread($thread);
	$postPage = getPostPageInThread($postId, $threadId);

	$response = [
		'id'   => $postId,
		'page' => $postPage
	];

	echo json_encode($response);
