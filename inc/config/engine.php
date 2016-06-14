<?php

	define('DEFAULT_PAGE_NAME', 'home');
	
	define('DEFAULT_SUBPAGE_NAME', '');
	
	define('MENU_ITEMS', [
		[
			'page'    => 'home',
			'caption' => 'Startseite'
		], [
			'page'    => 'about',
			'caption' => 'Was ist SMW-Hacken?'
		], [
			'page'         => 'forums',
			'caption'      => 'Forum',
			'relatedPages' => ['forum', 'thread', 'new-reply', 'user']
		], [
			'page'    => 'chat',
			'caption' => 'Chat'
		], [
			'page'    => 'files',
			'caption' => 'Uploader'
		],
	]);