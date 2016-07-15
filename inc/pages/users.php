<?php

	require_once __DIR__ . '/../functions/user.php';
	require_once __DIR__ . '/../functions/pagination.php';

	require_once __DIR__ . '/../config/misc.php';


	$sortableColumns = [
		'id'                => 'id',
		'name'              => 'name',
		'registration-time' => 'registration_time',
		'last-login-time'   => 'last_login_time',
		'num-posts'         => 'num_posts'
	];
	$sortColumn = isset($_GET['sort']) && array_key_exists($_GET['sort'], $sortableColumns)
		? $sortableColumns[$_GET['sort']]
		: 'id';
	$sortDirection = isset($_GET['dir']) && $_GET['dir'] === 'desc' ? 'desc' : 'asc';
	$sortDirections = [];
	foreach ($sortableColumns as $linkName => $databaseName)
	{
		$sortDirections[$linkName] = ($sortColumn === $databaseName && $sortDirection === 'asc') ? 'desc' : 'asc';
	}

	$numUsers = getNumUsers();

	$page = (isset($_GET['page']) && is_int($_GET['page'] * 1)) ? ($_GET['page'] * 1) : 1;
	$numPages = (int)ceil($numUsers / USERS_PER_PAGE);
	makeBetween($page, 1, $numPages);
	$paginationLink = '?p=users&sort=' . array_flip($sortableColumns)[$sortColumn] . '&dir=' . $sortDirection;
	renderPagination($paginationLink, $page, $numPages);

	$users = getUsers($page, $sortColumn, $sortDirection);

	$usersForTemplate = [];
	foreach ($users as $user)
	{
		$userForTemplate = $user;
		$userForTemplate['rank'] = getRank($user['id']);
		$userForTemplate['powerlevel_description'] = POWERLEVEL_DESCRIPTIONS[$user['powerlevel']];

		$usersForTemplate[] = $userForTemplate;
	}

	renderTemplate('users', [
		'users'          => $usersForTemplate,
		'sortDirections' => $sortDirections
	]);

	renderPagination($paginationLink, $page, $numPages);