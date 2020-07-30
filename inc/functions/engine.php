<?php

	require_once __DIR__ . '/../config/engine.php';

	require_once __DIR__ . '/session.php';
	require_once __DIR__ . '/pm.php';
	require_once __DIR__ . '/user.php';
	require_once __DIR__ . '/misc.php';


	function getCurrentPageName()
	{
		return isset($_GET['p']) ? sanitize($_GET['p']) : DEFAULT_PAGE_NAME;
	}


	function getCurrentSubpageName()
	{
		return isset($_GET['s']) ? sanitize($_GET['s']) : DEFAULT_SUBPAGE_NAME;
	}


	function renderMenu()
	{
		$menuItems = prepareMenuItemsForTemplate(MENU_ITEMS);

		renderTemplate('menu', [
			'menuItems' => $menuItems
		]);
	}


	function prepareMenuItemsForTemplate($menuItems)
	{
		$menuItemsForTemplate = [];

		foreach ($menuItems as $item)
		{
			if (isset($item['page']))
			{
				if (isset($item['subpage']))
				{
					$link = '/?p=' . $item['page'] . '&s=' . $item['subpage'];
				}
				else
				{
					$link = '/?p=' . $item['page'];
				}
				$active = isMenuItemActive($item);
			}
			else
			{
				$link = isset($item['link']) ? $item['link'] : '';
				$active = false;
			}

			$caption = $item['caption'] ?? '';
			$secret = $item['secret'] ?? false;

			$menuItemsForTemplate[] = [
				'active'  => $active,
				'link'    => $link,
				'caption' => $caption,
				'secret'  => $secret
			];
		}

		return $menuItemsForTemplate;
	}


	function isMenuItemActive($item)
	{
		$currentPageName = getCurrentPageName();

		if ($item['page'] === $currentPageName)
		{
			return true;
		}
		if (isset($item['relatedPages']))
		{
			return in_array($currentPageName, $item['relatedPages']);
		}

		return false;
	}


	function renderUserMenu()
	{
		$numUnreadPms = isLoggedIn() ? getNumUnreadPmsToUser($_SESSION['userId']) : 0;

		renderTemplate('user_menu', [
			'loggedIn'     => isLoggedIn(),
			'admin'        => isAdmin(),
			'userId'       => $_SESSION['userId'] ?? '',
			'username'     => $_SESSION['username'] ?? '',
			'numUnreadPms' => $numUnreadPms,
			'token'        => getCsrfToken()
		]);
	}


	function renderPage()
	{
		if (file_exists($pageFile = __DIR__ . '/../pages/' . getCurrentPageName() . '.php'))
		{
			include $pageFile;
		}
		else if (file_exists($subpageFile = __DIR__ . '/../pages/' . getCurrentPageName() . '/'
			. getCurrentSubpageName() . '.php'))
		{
			include $subpageFile;
		}
		else
		{
			include __DIR__ . '/../pages/error.php';
		}
	}


	function renderFooter()
	{
		$onlineUsers = getOnlineUsers();

		renderTemplate('footer', [
			'onlineUsers'    => $onlineUsers,
			'numOnlineUsers' => count($onlineUsers)
		]);
	}
