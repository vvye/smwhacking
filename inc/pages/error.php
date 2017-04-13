<?php

	require_once __DIR__ . '/../config/misc.php';


	$errors = [401, 403, 404, 500];

	if (isset($_GET['e']) && in_array($error = $_GET['e'] * 1, $errors, true))
	{
		$templateName = $error;
	}
	else
	{
		$templateName = '404';
	}

	renderTemplate($templateName, [
		'link' => DEFAULT_PAGE_NAME
	]);