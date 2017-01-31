<?php

	require_once __DIR__ . '/../config/chat.php';
	require_once __DIR__ . '/../config/misc.php';


	function getChatMessages()
	{
		global $database;

		$messages = $database->select('chat_messages', [
			'[>]users' => ['author' => 'id']
		], [
			'chat_messages.id',
			'chat_messages.author(author_id)',
			'users.name(author_name)',
			'chat_messages.post_time',
			'chat_messages.content',
			'chat_messages.edited'
		], [
			'deleted' => 0,
			'ORDER'   => 'id DESC',
			'LIMIT'   => MAX_CHAT_MESSAGES,
		]);
		
		$messages = array_reverse($messages);

		return $messages;
	}