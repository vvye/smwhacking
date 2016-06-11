<?php

	require_once __DIR__ . '/../functions/misc.php';


	class Engine
	{
		static $menuItems = [
			[
				'page'    => 'home',
				'caption' => 'Startseite'
			], [
				'page'    => 'about',
				'caption' => 'Was ist SMW-Hacken?'
			], [
				'page'    => 'forums',
				'caption' => 'Forum'
			], [
				'page'    => 'chat',
				'caption' => 'Chat'
			], [
				'page'    => 'files',
				'caption' => 'Uploader'
			],
		];

		static $defaultPageName = 'home';
		static $defaultSubpageName = '';


		public function getCurrentPageName()
		{
			return isset($_GET['p']) ? sanitize($_GET['p']) : self::$defaultPageName;
		}


		public function getCurrentSubpageName()
		{
			return isset($_GET['s']) ? sanitize($_GET['s']) : self::$defaultSubpageName;
		}


		public function renderMenu()
		{
			echo '<nav>';
			echo '<ul>';
			foreach (self::$menuItems as $item)
			{
				$this->renderMenuItem($item);
			}
			echo '</ul>';
			echo '</nav>';
		}


		public function renderPage()
		{
			if (file_exists($pageFile = 'inc/pages/' . $this->getCurrentPageName() . '.php'))
			{
				include $pageFile;
			}
			else if (file_exists($subpageFile = 'inc/pages/' . $this->getCurrentPageName() . '/' . $this->getCurrentSubpageName() . '.php'))
			{
				include $subpageFile;
			}
			else
			{
				include 'inc/pages/404.php';
			}
		}

		
		private function renderMenuItem($item)
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
				$cssClass = ($item['page'] === $this->getCurrentPageName()) ? ' class="active"' : '';
			}
			else
			{
				$link = isset($item['link']) ? $item['link'] : '';
				$cssClass = '';
			}

			$caption = isset($item['caption']) ? $item['caption'] : '';

			echo '<li' . $cssClass . '><a href="' . $link . '">' . $caption . '</a></li>';
		}




	}