<?php

	define('DEFAULT_PAGE_NAME', 'home');
	
	define('DEFAULT_SUBPAGE_NAME', '');
	
	define('MENU_ITEMS', [
		[
			'page'    => 'home',
			'caption' => CAPTION_HOME
		], [
			'page'    => 'about',
			'caption' => CAPTION_ABOUT
		], [
			'page'         => 'forums',
			'caption'      => CAPTION_FORUM,
			'relatedPages' => ['forum', 'thread', 'new-post', 'edit-post', 'delete-post', 'moderate-thread', 'user']
		], [
			'page'    => 'chat',
			'caption' => CAPTION_CHAT
		], [
			'page'    => 'files',
			'caption' => CAPTION_FILES
		],
	]);