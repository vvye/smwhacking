<?php

	require_once __DIR__ . '/../functions/medals.php';


	if (isset($_POST['submit']))
	{
		do
		{
			if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
			{
				renderErrorMessage(MSG_BAD_TOKEN);
				break;
			}

			$name = trim($_POST['name']);
			$description = trim($_POST['description']);
			$categoryId = $_POST['category'];
			$awardCondition = $_POST['award-condition'];
			$value = ($awardCondition === 'manual') ? 0 : $_POST['value'];

			if ($name === '' || $description === '')
			{
				renderErrorMessage(MSG_ENTER_NAME_AND_DESCRIPTION);
				break;
			}

			$imageFilename = isset($_POST['upload-image']) ? processUploadedMedalImage() : $_POST['image-filename'];

			createMedal([
				'name'            => $name,
				'description'     => $description,
				'category'        => $categoryId,
				'award_condition' => $awardCondition,
				'value'           => $value,
				'image_filename'  => $imageFilename
			]);

			renderSuccessMessage(MSG_MEDAL_CREATED);
		}
		while (false);
	}


	renderTemplate('new_medal', [
		'categories'      => getMedalCategories(),
		'awardConditions' => MEDAL_AWARD_CONDITIONS,
		'imageFilenames'  => getMedalImageFilenames(),
		'name'            => $name ?? '',
		'description'     => $description ?? '',
		'categoryId'      => $categoryId ?? '',
		'awardCondition'  => $awardCondition ?? '',
		'value'           => $value ?? '',
		'imageFilename'   => $imageFilename ?? '',
		'token'           => getCsrfToken()
	]);