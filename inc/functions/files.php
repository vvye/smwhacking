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

		return $database->get('files', [
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
	}


	function canDeleteFile($file)
	{
		if (!isLoggedIn())
		{
			return false;
		}
		if (!isAdmin())
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