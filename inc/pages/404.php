<?php
	
	require_once __DIR__ . '/../config/misc.php';
	
	
	renderTemplate('404', [
		'link' => DEFAULT_PAGE_NAME
	]);