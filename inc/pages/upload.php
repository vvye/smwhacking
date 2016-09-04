<?php

	require_once __DIR__ . '/../functions/files.php';


	$success = false;
	if (isset($_POST['submit']))
	{
		do
		{
			$name = getFieldValue('title');
			$shortDescription = getFieldValue('short-description');
			$longDescription = getFieldValue('long-description');

			if (trim($name) === '')
			{
				renderErrorMessage(MSG_FILE_NAME_MISSING);
				break;
			}
			if (trim($shortDescription) === '')
			{
				renderErrorMessage(MSG_SHORT_DESCRIPTION_MISSING);
				break;
			}

			list($newId, $extension, $errorMessages) = processUploadedFile($name);
			if (!empty($errorMessages))
			{
				foreach ($errorMessages as $errorMessage)
				{
					renderErrorMessage($errorMessage);
				}
				break;
			}

			if (!createDatabaseEntryForFile($newId, $name, $extension, $shortDescription, $longDescription, $_SESSION['userId']))
			{
				renderErrorMessage(MSG_UPLOAD_GENERAL_ERROR);
				break;
			}

			renderSuccessMessage(MSG_FILE_UPLOADED);
			renderTemplate('upload_after', [
				'id' => $newId
			]);
			$success = true;
		}
		while (false);
	}
	else
	{
		$name = $shortDescription = $longDescription = '';
	}

	if (!$success)
	{
		renderTemplate('upload_form', [
			'name'             => $name,
			'shortDescription' => $shortDescription,
			'longDescription'  => $longDescription
		]);
	}
