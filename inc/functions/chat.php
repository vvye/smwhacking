<?php

	require_once __DIR__ . '/avatar.php';
	require_once __DIR__ . '/bbcode.php';
	require_once __DIR__ . '/user.php';
	require_once __DIR__ . '/register.php';

	require_once __DIR__ . '/../config/chat.php';
	require_once __DIR__ . '/../config/bbcode.php';
	require_once __DIR__ . '/../config/user.php';
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
				'chat_messages.id[<>]' => [$firstId, $lastId]
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
			'deleted' => 0,
			'ORDER'   => 'chat_messages.id DESC',
			'LIMIT'   => 1
		]);

		processMessages($messages);

		return $messages[0];
	}


	function processMessages($messages)
	{
		foreach ($messages as $key => $message)
		{
			$messages[$key]['content'] = linkifyMentions(parseBBCode($message['content']));
			$messages[$key]['avatar_url'] = getAvatarUrlFromMessage($message);
			$messages[$key]['post_time'] = date(DEFAULT_DATE_FORMAT, $message['post_time']);
			$messages[$key]['can_delete'] = isLoggedIn()
				&& (isAdmin() || $message['author_id'] === $_SESSION['userId']);

			if ($messages[$key]['author_id'] * 1 === CHAT_BOT_USER_ID)
			{
				$messages[$key]['author_name'] = CHAT_BOT_USER_NAME;
			}
		}

		return $messages;
	}


	function getChatMessagesForArchive($page)
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
		$avatarEasterEggs = AVATAR_EASTER_EGGS;
		foreach ($avatarEasterEggs as $regex => $directory)
		{
			if (preg_match($regex, $message['content']))
			{
				return getRandomImageFilename($directory);
			}
		}

		return hasAvatar($message['author_id']) ? 'img/avatars/' . $message['author_id'] . '.png' :
			'img/avatars/default.png';
	}


	function renderChatBar()
	{
		if (!isLoggedIn() || !$_SESSION['showChatBar'])
		{
			return;
		}

		$currentPageName = getCurrentPageName();
		if (in_array($currentPageName, ['chat']))
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

		return truncatePreservingHtml($text, CHAT_BAR_TRUNCATE_LENGTH);
	}


	function linkifyMentions($text)
	{
		global $database;

		$text = preg_replace_callback('/@(' . VALID_USERNAME_REGEX . ')/', function ($match) use ($database)
		{

			$matchedUsername = $match[1];
			$possibleUsernames = [];
			for ($i = strlen($matchedUsername); $i > 0; $i--)
			{
				$possibleUsernames[] = substr($matchedUsername, 0, $i);
			}

			$user = $database->get('users', [
				'id',
				'name'
			], [
				'name' => $possibleUsernames
			]);

			if ($user !== null && $user !== false && !empty($user))
			{
				return '@' . '<a href="?p=user&id=' . $user['id'] . '">' . $user['name'] . '</a>'
					. substr($matchedUsername, strlen($user['name']));
			}

			return $match[0];

		}, $text);

		return $text;
	}


	function handleBotMessage($content)
	{
		$content = trim($content);

		if (stristr($content, 'aktiv'))
		{
			createBotMessage('[b]AKTIVITÄT![/b]');
		}
		if ($content === '@Bot history')
		{
			createBotMessage(getRandomMessage(), true);
		}
		$matches = [];
		if (preg_match('/^@Bot history (' . VALID_USERNAME_REGEX . ')$/', $content, $matches))
		{
			if (usernameExists($matches[1]))
			{
				$userId = getUserIdByName($matches[1]);
				createBotMessage(getRandomMessage($userId), true);
			}
		}
		$matches = [];
		if (preg_match('/^@Bot imitate (' . VALID_USERNAME_REGEX . ')$/', $content, $matches))
		{
			if (usernameExists($matches[1]))
			{
				$userId = getUserIdByName($matches[1]);
				createBotMessage(generateMarkovMessageByUser($userId));
			}
		}
	}


	function createBotMessage($content, $raw = false)
	{
		global $database;

		$postTime = time();

		if (!$raw)
		{
			$content = delimitSmileys(htmlspecialchars($content));
		}

		$database->insert('chat_messages', [
			'id'        => null,
			'author'    => CHAT_BOT_USER_ID,
			'post_time' => $postTime,
			'content'   => $content,
			'deleted'   => 0
		]);

	}


	function getRandomMessage($userId = null)
	{
		global $database;

		$id = getRandomMessageId($userId);

		$messages = $database->select('chat_messages', [
			'[>]users' => ['author' => 'id']
		], [
			'chat_messages.id',
			'chat_messages.author(author_id)',
			'users.name(author_name)',
			'chat_messages.post_time',
			'chat_messages.content',
		], [
			'chat_messages.id' => '' . $id
		]);
		$message = $messages[0];

		$messageId = $message['id'];
		$page = getMessagePage($message['id']);
		$authorId = $message['author_id'];
		$authorName = $message['author_name'];
		$postTime = $message['post_time'];
		$content = $message['content'];

		return '[b][url="' . WEBSITE_URL . '/?p=user&id=' . $authorId . '"]' . $authorName . '[/url][/b] ([url="'
			. WEBSITE_URL . '/?p=chat-archive&page=' . $page . '#message-' . $messageId . '"]'
			. date(DEFAULT_DATE_FORMAT, $postTime) . '[/url]):[br][/br] ' . $content;

	}


	function generateMarkovMessageByUser($userId)
	{
		global $database;

		$words = explode(' ', strip_tags(join(' ', $database->select('chat_messages', 'content', [
			'AND' => [
				'author'      => $userId,
				'content[!~]' => '@Bot'
			]
		]))));

		$followers = [];
		for ($i = 0; $i < count($words) - 1; $i++)
		{
			$word = $words[$i];
			if (!isset($followers[$word]))
			{
				$followers[$word] = [];
			}
			$followers[$word][] = $words[$i + 1];
		}

		$startingWord = $words[array_rand($words)];
		$newWords = [$startingWord];
		$currentWord = $startingWord;
		for ($i = 0; $i < 50; $i++)
		{
			$possibleFollowers = $followers[$currentWord];
			$follower = $possibleFollowers[array_rand($possibleFollowers)];
			$newWords[] = $follower;
			$currentWord = $follower;
		}

		return join(' ', $newWords);
	}


	function getRandomMessageId($userId = null)
	{
		global $database;

		$messageQuery = 'SELECT id FROM chat_messages WHERE deleted = 0';

		if ($userId !== null)
		{
			$messageQuery .= ' AND author = ' . ((int)$userId);
		}

		$query = '
			SELECT id
			FROM (' . $messageQuery . ') AS r1
			JOIN (SELECT (RAND() * (SELECT MAX(id) FROM (SELECT id FROM chat_messages WHERE deleted = 0) AS dummy)) AS id2) AS r2
			WHERE r1.id >= r2.id2
			ORDER BY r1.id ASC
			LIMIT 1
		';

		return $database->query($query)->fetch()['id'];
	}


	function getMessagePage($messageId)
	{
		global $database;

		$numMessagesUpTo = $database->count('chat_messages', [
			'AND' => [
				'id[<=]'  => $messageId,
				'deleted' => 0
			]
		]);

		return ceil($numMessagesUpTo / INIT_CHAT_MESSAGES);
	}