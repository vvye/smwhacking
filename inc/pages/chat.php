<?php

	require_once __DIR__ . '/../functions/chat.php';

	require_once __DIR__ . '/../config/ajax.php';


	$messages = getRecentChatMessages();

	renderTemplate('chat', [
		'loggedIn'        => isLoggedIn(),
		'chatKeyBehavior' => $_SESSION['chatKeyBehavior'],
		'messages'        => $messages
	]);

	renderTemplate('spoiler_js', []);