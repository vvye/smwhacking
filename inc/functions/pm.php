<?php

	function getNumPmsToUser($userId)
	{
		global $database;

		return $database->count('private_messages', [
			'recipient' => $userId
		]);
	}


	function getNumUnreadPmsToUser($userId)
	{
		global $database;

		return $database->count('private_messages', [
			'AND' => [
				'recipient' => $userId,
				'unread'    => 1
			]
		]);
	}


	function getNumPmsFromUser($userId)
	{
		global $database;

		return $database->count('private_messages', [
			'author' => $userId
		]);
	}


	function getPmsToUser($userId, $page)
	{
		global $database;

		$pms = $database->select('private_messages', [
			'[>]users' => ['author' => 'id']
		], [
			'private_messages.id',
			'private_messages.send_time',
			'private_messages.author(author_id)',
			'private_messages.subject',
			'private_messages.unread',
			'users.name(author_name)',
		], [
			'recipient' => $userId,
			'ORDER'     => 'send_time DESC',
			'LIMIT'     => [(int)(($page - 1) * PMS_PER_PAGE), PMS_PER_PAGE]
		]);

		return $pms;
	}


	function getPmsFromUser($userId, $page)
	{
		global $database;

		$pms = $database->select('private_messages', [
			'[>]users' => ['recipient' => 'id']
		], [
			'private_messages.id',
			'private_messages.send_time',
			'private_messages.recipient(recipient_id)',
			'private_messages.subject',
			'private_messages.unread',
			'users.name(recipient_name)',
		], [
			'author' => $userId,
			'ORDER'  => 'send_time DESC',
			'LIMIT'  => [(int)(($page - 1) * PMS_PER_PAGE), PMS_PER_PAGE]
		]);

		return $pms;
	}


	function getPm($pmId)
	{
		global $database;

		$pms = $database->select('private_messages', [
			'[>]users' => ['author' => 'id'],
		], [
			'private_messages.id',
			'private_messages.send_time',
			'private_messages.content',
			'private_messages.subject',
			'private_messages.unread',
			'private_messages.author(author_id)',
			'private_messages.recipient(recipient_id)',
			'users.id(author_id)',
			'users.name(author_name)',
			'users.powerlevel(author_powerlevel)',
			'users.banned(author_banned)',
			'users.title(author_title)',
			'users.signature(author_signature)',
			'users.registration_time(author_registration_time)',
			'users.banned(author_banned)'
		], [
			'private_messages.id' => $pmId
		]);


		if ($pms === false || count($pms) !== 1)
		{
			return null;
		}

		return $pms[0];
	}


	function canViewPm($pm)
	{
		if (!isLoggedIn())
		{
			return false;
		}

		return $pm['author_id'] === $_SESSION['userId'] || $pm['recipient_id'] === $_SESSION['userId'];
	}


	function markPmAsRead($pmId)
	{
		global $database;

		$database->update('private_messages', [
			'unread' => 0
		], [
			'id' => $pmId
		]);
	}


	function createPm($recipientId, $subject, $pmText)
	{
		global $database;

		if (!isLoggedIn())
		{
			return null;
		}

		$newPmId = $database->insert('private_messages', [
			'id'        => null,
			'send_time' => time(),
			'author'    => $_SESSION['userId'],
			'recipient' => $recipientId,
			'subject'   => $subject,
			'content'   => $pmText,
			'unread'    => 1
		]);

		return $newPmId;
	}