<?php

	session_start();

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/chat.php';
	require_once __DIR__ . '/../functions/misc.php';

	require_once __DIR__ . '/../config/ajax.php';

	$database = getDatabase();

	// but why
	date_default_timezone_set('Europe/Berlin');


	header('Content-type: text/json');


	function updateMessages()
	{
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

		$messages = getRecentChatMessages($lastId, true);
		$messages['deletedMessages'] = getDeletedMessages($lastId - MAX_CHAT_MESSAGES, $lastId);

		echo json_encode($messages);
	}


	function postMessage()
	{
		if (!isLoggedIn() || isBanned() || !isset($_POST['token']) || !isCsrfTokenCorrect($_POST['token']))
		{
			http_response_code(403);
			die();
		}

		if (isset($_SESSION['lastMessagePostTime'])
			&& $_SESSION['lastMessagePostTime'] > time() - CHAT_MESSAGE_POST_COOLDOWN_TIME
		)
		{
			http_response_code(429);
			die();
		}
		$_SESSION['lastMessagePostTime'] = time();

		if (!isset($_POST['last_id']))
		{
			http_response_code(403);
			die();
		}
		$lastId = (int)$_POST['last_id'] * 1;

		$content = $_POST['content'] ?? '';
		if (trim($content) === '')
		{
			http_response_code(403);
			die();
		}

		createMessage($content);

		handleBotMessage($content);

		$messages = getRecentChatMessages($lastId, true);
		$messages['deletedMessages'] = getDeletedMessages($lastId - MAX_CHAT_MESSAGES, $lastId);

		echo json_encode($messages);
	}


	function delete()
	{
		if (!isset($_GET['id']))
		{
			die();
		}
		$id = (int)$_GET['id'] * 1;

		if (!isCsrfTokenCorrect($_GET['token']))
		{
			http_response_code(403);
			die();
		}

		$success = deleteMessage($id);
		if (!$success)
		{
			http_response_code(403);
			die();
		}
	}


	if (!isset($_GET['action']))
	{
		die();
	}

	switch ($_GET['action'])
	{
		case 'update_messages':
			updateMessages();
			break;
		case 'post_message':
			postMessage();
			break;
		case 'delete':
			delete();
			break;
		default:
			break;
	}