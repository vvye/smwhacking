<?php

	require_once __DIR__ . '/../functions/files.php';


	$showList = true;

	if (isset($_GET['action']) && $_GET['action'] === 'delete')
	{
		$showList = false;

		do
		{
			if (!isset($_GET['id']) || !is_numeric($_GET['id'] * 1))
			{
				renderErrorMessage(MSG_FILE_DOESNT_EXIST);
				break;
			}
			$id = $_GET['id'] * 1;
			$file = getFileById($id);

			if (!canDeleteFile($file))
			{
				renderErrorMessage(MSG_NOT_ALLOWED);
				break;
			}

			if (isset($_POST['submit']))
			{
				deleteFile($file);
				renderSuccessMessage(MSG_FILE_DELETED);
				$showList = true;
			}
			else
			{
				renderTemplate('delete_file', [
					'file' => $file
				]);
			}
		}
		while (false);
	}

	if ($showList)
	{
		$files = getFiles();

		$filesForTemplate = [];
		foreach ($files as $file)
		{
			$tmpFile = $file;
			$tmpFile['can_delete'] = canDeleteFile($file);
			$tmpFile['can_open_directly'] = in_array(strtolower($file['extension']), FILE_TYPES_TO_OPEN_IN_BROWSER);

			$filesForTemplate[] = $tmpFile;
		}

		renderTemplate('files', [
			'loggedIn' => isLoggedIn(),
			'files'    => $filesForTemplate
		]);
	}
