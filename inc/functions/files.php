<?php

	require_once __DIR__ . '/misc.php';

	require_once __DIR__ . '/../config/files.php';
	require_once __DIR__ . '/../config/misc.php';


	function sanitizeFilename($filename)
	{
		$filename = str_replace([
			'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'ß', ' '
		], [
			'Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss', '_'
		], $filename);

		return preg_replace('/[^A-Za-z0-9-_ ]/', '', $filename);
	}


	function getFiles()
	{
		global $database;

		$files = $database->select('files', [
			'[>]users' => ['user' => 'id']
		], [
			'files.id',
			'files.user(user_id)',
			'files.name',
			'files.extension',
			'files.short_description',
			'files.long_description',
			'files.upload_time',
			'users.name(username)'
		]);

		return $files;
	}


	function getFileById($id)
	{
		global $database;

		$file = $database->get('files', [
			'[>]users' => ['user' => 'id']
		], [
			'files.id',
			'files.user(user_id)',
			'files.name',
			'files.extension',
			'files.short_description',
			'files.long_description',
			'files.upload_time',
			'users.name(username)'
		], [
			'files.id' => $id
		]);

		if ($file === false)
		{
			return null;
		}

		return $file;
	}


	function canDeleteFile($file)
	{
		if (!isLoggedIn())
		{
			return false;
		}
		if (isAdmin())
		{
			return true;
		}

		return $file['user_id'] = $_SESSION['userId'];
	}


	function deleteFile($file)
	{
		global $database;

		$database->delete('files', [
			'id' => $file['id']
		]);

		$fileName = $file['id'] . '_' . sanitizeFilename($file['name']) . '.' . $file['extension'];
		$filePath = 'files/' . $fileName;

		unlink($filePath);
	}


	function processUploadedFile($fileTitle)
	{
		global $database;

		$errorMessages = [];

		do
		{
			$file = $_FILES['uploaded-file'];

			if (!isset($file['error'])
				|| is_array($file['error'])
				|| $file['error'] !== UPLOAD_ERR_OK
			)
			{
				if ($file['error'] === UPLOAD_ERR_NO_FILE)
				{
					$errorMessages[] = MSG_NO_FILE_SELECTED;
				}
				else
				{
					$errorMessages[] = MSG_UPLOAD_GENERAL_ERROR;
				}
				break;
			}

			if ($file['size'] === 0)
			{
				$errorMessages[] = 'Wähle eine Datei zum Hochladen aus.';
				break;
			}

			$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
			if (in_array(strtolower($extension), ['smc', 'sfc']))
			{
				$errorMessages[] = 'Bitte lade keine ROM hoch.';
				break;
			}

			$maxId = $database->max('files', 'id');
			$newId = $maxId + 1;
			$finalFileName = $newId . '_' . sanitizeFilename($fileTitle) . '.' . $extension;

			if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../files/' . $finalFileName))
			{
				$errorMessages[] = MSG_UPLOAD_GENERAL_ERROR;
				break;
			}
		}
		while (false);

		return [$newId ?? null, $extension ?? null, $errorMessages];
	}


	function createDatabaseEntryForFile($newId, $name, $extension, $shortDescription, $longDescription, $userId)
	{
		global $database;

		if ($newId === null || $extension === null)
		{
			return false;
		}

		$database->insert('files', [
			'id'                => $newId,
			'name'              => $name,
			'extension'         => $extension,
			'short_description' => $shortDescription,
			'long_description'  => $longDescription,
			'user'              => $userId,
			'upload_time'       => time()
		]);

		return true;
	}
