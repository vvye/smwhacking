<?php

	require_once __DIR__ . '/avatar.php';
	require_once __DIR__ . '/bbcode.php';

	require_once __DIR__ . '/../config/chat.php';
	require_once __DIR__ . '/../config/bbcode.php';
	require_once __DIR__ . '/../config/misc.php';


	function getRecentChatMessages($lastId = null, $returnRefreshTime = false)
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

		foreach ($messages as $key => $message)
		{
			$messages[$key]['content'] = parseBBCode($message['content']);
			$messages[$key]['has_avatar'] = hasAvatar($message['author_id']);
			$messages[$key]['post_time'] = date(DEFAULT_DATE_FORMAT, $message['post_time']);
			$messages[$key]['can_delete'] = isAdmin() || $message['author_id'] === $_SESSION['userId'];
		}

		if ($returnRefreshTime)
		{
			return [
				'refreshTime' => date(DEFAULT_DATE_FORMAT),
				'messages'    => $messages
			];
		}

		return $messages;
	}


	function createMessage($content)
	{
		global $database;

		if (!isLoggedIn() || isBanned())
		{
			return;
		}

		$postTime = time();
		$content = delimitSmileys(htmlspecialchars($content));

		$database->insert('chat_messages', [
			'id'        => null,
			'author'    => $_SESSION['userId'],
			'post_time' => $postTime,
			'content'   => $content,
			'deleted'   => 0
		]);
	}


	function deleteMessage($id)
	{
		global $database;

		if (isAdmin())
		{
			$canDelete = true;
		}
		else
		{
			$authorId = $database->get('chat_messages', 'author', [
				'id' => $id
			]);
			$canDelete = ($authorId === $_SESSION['userId']);
		}

		if (!$canDelete)
		{
			return false;
		}

		$database->update('chat_messages', [
			'deleted' => 1
		], [
			'id' => $id
		]);

		return true;
	}