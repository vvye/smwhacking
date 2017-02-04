<?php

	session_start();

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/chat.php';

	require_once __DIR__ . '/../config/ajax.php';

	$database = getDatabase();


	header('Content-type: text/json');


	function lastUnreadMessages()
	{
		if (!isLoggedIn() || isBanned())
		{
			die();
		}

		if (isset($_SESSION['lastRequestTime']) && $_SESSION['lastRequestTime'] > time() - CHAT_REFRESH_COOLDOWN_TIME)
		{
			die();
		}
		$_SESSION['lastRequestTime'] = time();

		if (!isset($_GET['last_id']))
		{
			die();
		}
		$lastId = (int)$_GET['last_id'] * 1;

		$unreadMessages = getRecentChatMessages($lastId, true);

		echo json_encode($unreadMessages);
	}


	function postMessage()
	{
		if (!isLoggedIn() || isBanned())
		{
			die();
		}

		if (isset($_SESSION['lastMessagePostTime'])
			&& $_SESSION['lastMessagePostTime'] > time() - CHAT_MESSAGE_POST_COOLDOWN_TIME
		)
		{
			http_response_code(403);
			die();
		}
		$_SESSION['lastMessagePostTime'] = time();

		if (!isset($_GET['last_id']))
		{
			die();
		}
		$lastId = (int)$_GET['last_id'] * 1;

		$content = $_GET['content'] ?? '';
		if (trim($content) === '')
		{
			die();
		}

		createMessage($content);

		$unreadMessages = getRecentChatMessages($lastId, true);

		echo json_encode($unreadMessages);
	}


	if (!isset($_GET['action']))
	{
		die();
	}

	switch ($_GET['action'])
	{
		case 'last_unread_messages':
			lastUnreadMessages();
			break;
		case 'post_message':
			postMessage();
			break;
		default:
			break;
	}