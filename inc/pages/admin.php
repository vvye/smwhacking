<?php

	require_once __DIR__ . '/../config/misc.php';


	do
	{
		if (!isLoggedIn() || !isAdmin())
		{
			// don't want to give them any hints
			renderTemplate('404', [
				'link' => DEFAULT_PAGE_NAME
			]);
			break;
		}

		$validActions = [
			'manage-forums',
			'manage-ranks',
			'manage-medals',
			'new-medal',
			'edit-medal',
			'delete-medal',
			'manage-medal-categories',
			'new-medal-category',
			'edit-medal-category',
			'delete-medal-category',
		];

		$subpageName = getCurrentSubpageName();

		if ($subpageName === '')
		{
			renderTemplate('admin_hub', []);
		}
		else if (!in_array($subpageName, $validActions))
		{
			renderTemplate('404', [
				'link' => DEFAULT_PAGE_NAME
			]);
		}
		else
		{
			include __DIR__ . '/admin/' . $subpageName . '.php';
		}
	}
	while (false);