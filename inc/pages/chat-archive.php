<?php

	require_once __DIR__ . '/../functions/chat.php';
	require_once __DIR__ . '/../functions/pagination.php';

	renderTemplate('chat_archive_top', []);

	$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;

	$numMessages = getNumChatMessages();
	$numPages = (int)ceil($numMessages / MAX_CHAT_MESSAGES);
	makeBetween($page, 1, $numPages);
	renderPagination('?p=chat-archive', $page, $numPages);

	$messages = getChatMessagesForAchive($page);

	renderTemplate('chat_archive', [
		'messages' => $messages
	]);

	renderPagination('?p=chat-archive', $page, $numPages);
