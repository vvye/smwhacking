<?php

	require_once __DIR__ . '/misc.php';
	
	
	function renderTemplate($filename, $data, $lang = 'de')
	{
		if (file_exists($fullFilePath = __DIR__ . '/../tmpl/' . $lang . '/' . sanitize($filename) . '.php'))
		{
			extract($data);
			include $fullFilePath;
		}
	}