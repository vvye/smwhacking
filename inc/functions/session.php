<?php

	require_once __DIR__ . '/form.php';
	require_once __DIR__ . '/phpbb_auth.php';


	function doLogin()
	{
		global $database;

		$givenEmail = strtolower(getFieldValue('email'));
		$givenPassword = getFieldValue('password');

		$users = $database->select('users', [
			'id',
			'name',
			'password',
			'legacy_login',
			'powerlevel',
			'banned',
			'csrf_token',
			'csrf_token_expiry_time'
		], [
			'AND' => [
				'email'     => $givenEmail,
				'activated' => 1
			]
		]);

		if (empty($users) || count($users) !== 1)
		{
			return false;
		}
		$user = $users[0];
		$passwordHash = $user['password'];

		$passwordCorrect = $user['legacy_login']
			? phpbb_check_hash($givenPassword, $passwordHash)
			: password_verify($givenPassword, $passwordHash);

		if (!$passwordCorrect)
		{
			return false;
		}

		$_SESSION['userId'] = $user['id'];
		$_SESSION['username'] = $user['name'];
		$_SESSION['loggedIn'] = true;
		$_SESSION['powerlevel'] = $user['powerlevel'];
		$_SESSION['banned'] = $user['banned'];
		$_SESSION['csrfToken'] = $user['csrf_token'];
		$_SESSION['csrfTokenExpiryTime'] = $user['csrf_token_expiry_time'];

		handleCsrfTokenRenewal();

		$database->update('users', [
			'last_login_time' => time(),
		], [
			'id' => $user['id']
		]);

		if ($user['legacy_login'])
		{
			$database->update('users', [
				'password'     => password_hash($givenPassword, PASSWORD_DEFAULT),
				'legacy_login' => 0
			], [
				'id' => $user['id']
			]);
		}

		return true;
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


	function handleCsrfTokenRenewal()
	{
		global $database;

		$expiryTime = $_SESSION['csrfTokenExpiryTime'];
		if ($expiryTime <= time())
		{
			$newToken = bin2hex(random_bytes(8));
			$database->update('users', [
				'csrf_token' => $newToken,
				'csrf_token_expiry_time' => strtotime('+1 day')
			], [
				'id' => $_SESSION['userId']
			]);
		}
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
		return (isset($_SESSION['powerlevel']) && $_SESSION['powerlevel'] >= 1);
	}


	function isAdmin()
	{
		return (isset($_SESSION['powerlevel']) && $_SESSION['powerlevel'] >= 2);
	}


	function isBanned()
	{
		return (isset($_SESSION['banned']) && (int)$_SESSION['banned'] !== 0);
	}