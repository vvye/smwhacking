<?php

	session_start();

	require_once __DIR__ . '/inc/lang/de/strings.php';

	require_once __DIR__ . '/inc/functions/environment.php';
	require_once __DIR__ . '/inc/functions/engine.php';
	require_once __DIR__ . '/inc/functions/template.php';
	require_once __DIR__ . '/inc/functions/database.php';

	$database = getDatabase();

	require_once __DIR__ . '/inc/functions/session.php';

	$baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);


	if (isset($_GET['action']) && $_GET['action'] === 'login')
	{
		$loginSuccess = doLogin();

		if (!$loginSuccess)
		{
			header('Location: ' . $baseUrl . '?p=login&error');
		}
		else
		{
			header('Location: ' . $baseUrl . '?p=home');
		}
	}
	else if (isset($_GET['action']) && $_GET['action'] === 'logout')
	{
		doLogout();

		header('Location: ' . $baseUrl . '?p=home');
	}
	else
	{
		header('Location: ' . $baseUrl . '?p=home');
	}
