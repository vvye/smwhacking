<?php

	require_once __DIR__ . '/inc/functions/database.php';
	require_once __DIR__ . '/inc/functions/files.php';
	require_once __DIR__ . '/inc/lang/de/strings.php';

	$database = getDatabase();


	if (!isset($_GET['id']) || !is_numeric($_GET['id'] * 1))
	{
		die(MSG_FILE_DOESNT_EXIST);
	}
	$id = $_GET['id'] * 1;

	$file = getFileById($id);

	$fileName = $file['id'] . '_' . sanitizeFilename($file['name']) . '.' . $file['extension'];
	$filePath = 'files/' . $fileName;
	echo $filePath . '<br>';
	if (!file_exists($filePath))
	{
		die(MSG_FILE_DOESNT_EXIST);
	}

	$fileTypesToOpenInBrowser = [
		'png',
		'jpg',
		'jpeg',
		'gif',
		'bmp',
		'txt'
	];

	if (in_array(strtolower($file['extension']), $fileTypesToOpenInBrowser))
	{
		header('Location: ' . $filePath);
	}
	else
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $fileName);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));

		ob_clean();
		flush();
		readfile($filePath);
	}



