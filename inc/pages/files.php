<?php

	require_once __DIR__ . '/../functions/files.php';


	$files = getFiles();

	$filesForTemplate = [];
	foreach ($files as $file)
	{
		$tmpFile = $file;
		$tmpFile['can_delete'] = canDeleteFile($file);
		$filesForTemplate[] = $tmpFile;
	}

	renderTemplate('files', [
		'files' => $filesForTemplate
	]);