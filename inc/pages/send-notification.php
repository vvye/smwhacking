<?php

	require_once __DIR__ . '/../config/notifications.php';

	ignore_user_abort(true);
	set_time_limit(0);

	$emails = $_GET['emails'] ?? '[]';
	$subject = $_GET['subject'] ?? '';
	$message = $_GET['message'] ?? '';
	$token = $_GET['token'] ?? '';

	$actualToken = file_get_contents(__DIR__ . '/../../notification_token');
	unlink(__DIR__ . '/../../notification_token');

	if ($token !== $actualToken)
	{
		die();
	}

	$emails = json_decode($emails);

	foreach ($emails as $email)
	{
		$headers = 'From: ' . NOTIFICATION_SENDER_ADDRESS . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

		mail($email, $subject, $message, $headers);

		// debug
		file_put_contents(__DIR__ . '/../tmp/emails/' . $email . '.email', $subject . "\n\n" . $message);
	}