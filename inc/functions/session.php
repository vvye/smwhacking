<?php

	require_once __DIR__ . '/form.php';
	require_once __DIR__ . '/phpbb_auth.php';


	function doLogin($database = null)
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
			'banned'
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
		return (isset($_SESSION['banned']) && $_SESSION['banned'] !== 0);
	}