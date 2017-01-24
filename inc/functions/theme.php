<?php

	function getTheme()
	{
		if (!isLoggedIn() || !isset($_SESSION['theme']))
		{
			return 'default';
		}

		return $_SESSION['theme'];
	}


	function getAllThemes()
	{
		return ['default', 'dark'];
	}