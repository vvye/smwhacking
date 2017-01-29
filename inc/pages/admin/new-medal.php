<?php

	require_once __DIR__ . '/../../functions/medals.php';


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
			$secret = isset($_POST['secret']) ? 1 : 0;

			if ($name === '' || $description === '')
			{
				renderErrorMessage(MSG_ENTER_NAME_AND_DESCRIPTION);
				break;
			}

			$imageFilename = isset($_POST['upload-image']) ? processUploadedMedalImage() : $_POST['image-filename'];

			createMedal([
				'name'            => htmlspecialchars($name),
				'description'     => htmlspecialchars($description),
				'category'        => htmlspecialchars($categoryId),
				'award_condition' => htmlspecialchars($awardCondition),
				'value'           => htmlspecialchars($value),
				'image_filename'  => htmlspecialchars($imageFilename),
				'secret'          => htmlspecialchars($secret)
			]);

			renderSuccessMessage(MSG_MEDAL_CREATED);
		} while (false);
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
		'secret'          => $secret ?? 0,
		'token'           => getCsrfToken()
	]);