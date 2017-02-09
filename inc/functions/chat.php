<?php

	require_once __DIR__ . '/avatar.php';
	require_once __DIR__ . '/bbcode.php';

	require_once __DIR__ . '/../config/chat.php';
	require_once __DIR__ . '/../config/bbcode.php';
	require_once __DIR__ . '/../config/misc.php';


	function getNumChatMessages()
	{
		global $database;

		return $database->count('chat_messages', [
			'deleted' => 0
		]);
	}


	function getRecentChatMessages($lastId = null, $returnRefreshTime = false)
	{
		global $database;

		$where = [
			'AND'   => [
				'deleted' => 0
			],
			'ORDER' => 'chat_messages.id DESC',
			'LIMIT' => INIT_CHAT_MESSAGES,
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

		$messages = processMessages($messages);

		if ($returnRefreshTime)
		{
			return [
				'refreshTime'    => date(DEFAULT_DATE_FORMAT),
				'unreadMessages' => $messages
			];
		}

		return $messages;
	}


	function getDeletedMessages($firstId, $lastId)
	{
		global $database;

		$deletedMessages = $database->select('chat_messages', [
			'[>]users' => ['author' => 'id']
		], [
			'chat_messages.id'
		], [
			'AND' => [
				'deleted'              => 1,
				'chat_messages.id[>=]' => $firstId,
				'chat_messages.id[<=]' => $lastId
			]
		]);

		return $deletedMessages;
	}


	function getLatestChatMessage()
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
		], [
			'ORDER' => 'chat_messages.id DESC',
			'LIMIT' => 1
		]);

		return $messages[0];
	}


	function processMessages($messages)
	{
		foreach ($messages as $key => $message)
		{
			$messages[$key]['content'] = parseBBCode($message['content']);
			$messages[$key]['avatar_url'] = getAvatarUrlFromMessage($message);
			$messages[$key]['post_time'] = date(DEFAULT_DATE_FORMAT, $message['post_time']);
			$messages[$key]['can_delete'] = isLoggedIn()
				&& (isAdmin() || $message['author_id'] === $_SESSION['userId']);
		}

		return $messages;
	}


	function getChatMessagesForAchive($page)
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
		], [
			'deleted' => 0,
			'ORDER'   => 'post_time ASC',
			'LIMIT'   => [($page - 1) * INIT_CHAT_MESSAGES, INIT_CHAT_MESSAGES]
		]);

		$messages = processMessages($messages);

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


	function getAvatarUrlFromMessage($message)
	{
		if (preg_match('/\bf(?:ü|ue)rst\b/i', $message['content']))
		{
			return 'img/avatars/fuersten/' . getRandomFuerstAvatarFilename();
		}

		return hasAvatar($message['author_id']) ? 'img/avatars/' . $message['author_id'] . '.png' :
			'img/avatars/default.png';
	}


	function renderChatBar()
	{
		if (!isLoggedIn() || !$_SESSION['showChatBar'] || getCurrentPageName() === 'chat')
		{
			return;
		}

		$message = getLatestChatMessage();
		$message['content'] = truncateChatMessage($message['content']);
		$message = processMessages([$message])[0];

		renderTemplate('chat_bar', [
			'message' => $message
		]);
	}


	function truncateChatMessage($text)
	{
		$text = str_replace(["\r", "\n", '<br>', '<br />'], ' ', $text);

		if (strlen($text) <= CHAT_BAR_TRUNCATE_LENGTH)
		{
			return $text;
		}

		return substr($text, 0, CHAT_BAR_TRUNCATE_LENGTH) . '&hellip;';
	}