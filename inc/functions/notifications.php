<?php

	require_once __DIR__ . '/../config/notifications.php';
	require_once __DIR__ . '/../config/misc.php';


	function sendNotification($userIds, $subject, $message)
	{
		global $database;

		$emails = $database->select('users', 'email', [
			'AND' => [
				'id'                   => $userIds,
				'enable_notifications' => 1
			]
		]);

		// send emails asynchronously by sending a separate HTTP request
		// (otherwise they'd delay the page load for the user who caused the action)

		$emails = urlencode(json_encode($emails));
		$subject = urlencode($subject);
		$message = urlencode($message);

		$token = bin2hex(random_bytes(16));
		file_put_contents(__DIR__ . '/../../notification_token', $token);

		$url = WEBSITE_URL . "/?p=send-notification&emails=$emails&subject=$subject&message=$message&token=$token";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);

		curl_exec($ch);
		curl_close($ch);
	}