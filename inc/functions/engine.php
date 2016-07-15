<?php

	require_once __DIR__ . '/../config/engine.php';

	require_once __DIR__ . '/session.php';
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
		$menuItems = MENU_ITEMS;

		echo '<nav>';
		echo '<ul>';
		foreach ($menuItems as $item)
		{
			renderMenuItem($item);
		}
		echo '</ul>';
		echo '</nav>';
	}


	function renderMenuItem($item)
	{
		if (isset($item['page']))
		{
			if (isset($item['subpage']))
			{
				$link = '?p=' . $item['page'] . '&s=' . $item['subpage'];
			}
			else
			{
				$link = '?p=' . $item['page'];
			}
			$active = isMenuItemActive($item);
		}
		else
		{
			$link = isset($item['link']) ? $item['link'] : '';
			$active = false;
		}

		$caption = isset($item['caption']) ? $item['caption'] : '';

		renderTemplate('menu_item', [
			'active'  => $active,
			'link'    => $link,
			'caption' => $caption
		]);
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
		renderTemplate('user_menu', [
			'loggedIn' => isLoggedIn(),
			'admin'    => isAdmin(),
			'userId'   => $_SESSION['userId'] ?? '',
			'username' => $_SESSION['username'] ?? '',
			'token'    => getCsrfToken()
		]);
	}


	function renderPage()
	{
		if (file_exists($pageFile = __DIR__ . '/../pages/' . getCurrentPageName() . '.php'))
		{
			include $pageFile;
		}
		else if (file_exists($subpageFile = __DIR__ . '/../pages/' . getCurrentPageName() . '/' . getCurrentSubpageName() . '.php'))
		{
			include $subpageFile;
		}
		else
		{
			include __DIR__ . '/../pages/404.php';
		}
	}