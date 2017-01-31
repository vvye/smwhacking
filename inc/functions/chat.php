<?php

	require_once __DIR__ . '/../config/chat.php';
	require_once __DIR__ . '/../config/misc.php';


	function getRecentChatMessages($lastId = null)
	{
		global $database;

		$where = [
			'AND'   => [
				'deleted' => 0
			],
			'ORDER' => 'chat_messages.id DESC',
			'LIMIT' => MAX_CHAT_MESSAGES,
		];
		if ($lastId !== null)
		{
			$where['AND']['chat_messages.id[>]'] = $lastId;
		}

		$messages = $database->select('chat_messages', [
			'[>]users' => ['author' => 'id']
		], [
			'chat_messages.id',
			'chat_messages.author(author_id)',
			'users.name(author_name)',
			'chat_messages.post_time',
			'chat_messages.content',
		], $where);

		$messages = array_reverse($messages);

		return $messages;
	}


	function createMessage($content)
	{
		global $database;

		if (!isLoggedIn() || isBanned())
		{
			return;
		}

		$database->insert('chat_messages', [
			'id'        => null,
			'author_id' => $_SESSION['userId'],
			'post_time' => time(),
			'content'   => $content,
			'deleted'   => 0
		]);

	}