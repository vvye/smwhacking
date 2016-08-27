<?php

	require_once __DIR__ . '/../../functions/medals.php';


	$medalCategories = getMedalCategories();

	renderTemplate('manage_medal_categories', [
		'categories' => $medalCategories,
		'token'      => getCsrfToken()
	]);