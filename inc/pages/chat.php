<?php

	require_once __DIR__ . '/../functions/chat.php';

	require_once __DIR__ . '/../config/ajax.php';


	$messages = getRecentChatMessages();

	renderTemplate('chat', [
		'loggedIn'    => isLoggedIn(),
		'enterToSend' => isLoggedIn() && $_SESSION['chatKeyBehavior'] === 'enter-to-send',
		'messages'    => $messages
	]);