<?php

	require_once __DIR__ . '/misc.php';


	function renderTemplate($templateName, $data, $lang = 'de')
	{
		if (file_exists($filePath = __DIR__ . '/../lang/' . $lang . '/tmpl/' . sanitize($templateName) . '.php'))
		{
			extract($data);
			include $filePath;
		}
	}
