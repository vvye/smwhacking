<?php

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
		mail($email, $subject, $message, 'From: ' . NOTIFICATION_SENDER_ADDRESS);

		// debug
		file_put_contents(__DIR__ . '/../../' . $email . '.email', $subject . "\n\n" . $message);
	}