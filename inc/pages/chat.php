<?php

	require_once __DIR__ . '/../functions/chat.php';


	$messages = getChatMessages();

	renderTemplate('chat', [
		'loggedIn' => isLoggedIn(),
		'messages' => $messages
	]);