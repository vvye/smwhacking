<?php

	require_once __DIR__ . '/../config/session.php';

	require_once __DIR__ . '/form.php';
	require_once __DIR__ . '/phpbb_auth.php';


	function doLogin()
	{
		global $database;

		$givenEmail = strtolower(getFieldValue('email'));
		$givenPassword = getFieldValue('password');

		$user = $database->get('users', [
			'id',
			'name',
			'password',
			'legacy_login',
			'powerlevel',
			'banned',
			'title',
			'signature',
			'csrf_token',
			'theme',
			'show_chat_bar',
			'chat_key_behavior'
		], [
			'AND' => [
				'email'     => $givenEmail,
				'activated' => 1
			]
		]);

		if (!is_array($user) || empty($user))
		{
			return false;
		}

		if (!isPasswordCorrect($user['id'], $givenPassword))
		{
			return false;
		}

		$_SESSION['userId'] = $user['id'];
		$_SESSION['username'] = $user['name'];
		$_SESSION['loggedIn'] = true;
		$_SESSION['powerlevel'] = $user['powerlevel'];
		$_SESSION['banned'] = $user['banned'];
		$_SESSION['title'] = $user['title'];
		$_SESSION['signature'] = $user['signature'];
		$_SESSION['theme'] = $user['theme'];
		$_SESSION['showChatBar'] = $user['show_chat_bar'];
		$_SESSION['chatKeyBehavior'] = $user['chat_key_behavior'];

		$_SESSION['csrfToken'] = renewCsrfToken();

		$database->update('users', [
			'last_login_time' => time(),
		], [
			'id' => $user['id']
		]);

		if ($user['legacy_login'])
		{
			updatePassword($user['id'], $givenPassword);
		}

		return true;
	}


	function isPasswordCorrect($userId, $givenPassword)
	{
		global $database;

		$user = $database->get('users', '*', [
			'id' => $userId
		]);

		if (!is_array($user) || empty($user))
		{
			return false;
		}

		$passwordHash = $user['password'];

		return $user['legacy_login']
			? phpbb_check_hash($givenPassword, $passwordHash)
			: password_verify($givenPassword, $passwordHash);
	}


	function updatePassword($userId, $password)
	{
		global $database;

		$database->update('users', [
			'password'     => password_hash($password, PASSWORD_DEFAULT),
			'legacy_login' => 0
		], [
			'id' => $userId
		]);
	}


	function updateLastActivityTime()
	{
		global $database;

		if (!isLoggedIn())
		{
			return;
		}

		$database->update('users', [
			'last_activity_time' => time()
		], [
			'id' => $_SESSION['userId']
		]);
	}


	function getCsrfToken()
	{
		if (!isLoggedIn())
		{
			return '';
		}

		return $_SESSION['csrfToken'];
	}


	function isCsrfTokenCorrect($token)
	{
		return $token === $_SESSION['csrfToken'];
	}


	function renewCsrfToken()
	{
		global $database;

		$newToken = bin2hex(random_bytes(8));
		$database->update('users', [
			'csrf_token' => $newToken
		], [
			'id' => $_SESSION['userId']
		]);

		return $newToken;
	}


	function doLogout()
	{
		session_unset();
		session_destroy();
	}


	function isLoggedIn()
	{
		return $_SESSION['loggedIn'] ?? false;
	}


	function isModerator()
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}

		return (isset($_SESSION['powerlevel']) && $_SESSION['powerlevel'] >= 1);
	}


	function isAdmin()
	{
		if (!isLoggedIn() || isBanned())
		{
			return false;
		}

		return (isset($_SESSION['powerlevel']) && $_SESSION['powerlevel'] >= 2);
	}


	function isBanned()
	{
		return (isset($_SESSION['banned']) && (int)$_SESSION['banned'] !== 0);
	}