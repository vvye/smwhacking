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
			$cssClass = (isMenuItemActive($item)) ? ' class="active"' : '';
		}
		else
		{
			$link = isset($item['link']) ? $item['link'] : '';
			$cssClass = '';
		}

		$caption = isset($item['caption']) ? $item['caption'] : '';

		echo '<li' . $cssClass . '><a href="' . $link . '">' . $caption . '</a></li>';
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
		if (!isLoggedIn())
		{
			?>
			<nav class="user-menu">
				<ul>
					<li><a href="?p=login">Einloggen</a></li>
					<li><a href="?p=register">Registrieren</a></li>
				</ul>
			</nav>
			<?php
		}
		else
		{
			?>
			<nav class="user-menu">
				<ul>
					<li>Eingeloggt als <em><?php echo $_SESSION['username']; ?></em>.</li>
					<li><a href="?p=pm">Private Nachrichten (0)</a></li>
					<li><a href="?p=usercp">Einstellungen</a></li>
					<li><a href="logout.php">Ausloggen</a></li>
				</ul>
			</nav>
			<?php
		}
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