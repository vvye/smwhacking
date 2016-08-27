<?php

	require_once __DIR__ . '/../functions/medals.php';

	do
	{
		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_MEDAL_DOESNT_EXIST);
			break;
		}
		$medalId = $_GET['id'] * 1;

		$medal = getMedal($medalId);

		if ($medal === null)
		{
			renderErrorMessage(MSG_MEDAL_DOESNT_EXIST);
			break;
		}

		if (isset($_POST['submit']))
		{
			$error = false;

			$name = trim($_POST['name']);
			$description = trim($_POST['description']);
			$categoryId = $_POST['category'];
			$awardCondition = $_POST['award-condition'];
			$value = ($awardCondition === 'manual') ? 0 : $_POST['value'];

			if ($name === '' || $description === '')
			{
				renderErrorMessage(MSG_ENTER_NAME_AND_DESCRIPTION);
				$error = true;
			}

			$imageFilename = isset($_POST['upload-image']) ? processUploadedMedalImage() : $_POST['image-filename'];

			if (!$error)
			{
				editMedal($medalId, [
					'name'            => htmlspecialchars($name),
					'description'     => htmlspecialchars($description),
					'category'        => htmlspecialchars($categoryId),
					'award_condition' => htmlspecialchars($awardCondition),
					'value'           => htmlspecialchars($value),
					'image_filename'  => htmlspecialchars($imageFilename)
				]);

				renderSuccessMessage(MSG_MEDAL_EDITED);
				$medal = getMedal($medalId);
			}
			else
			{
				renderTemplate('edit_medal', [
					'categories'      => getMedalCategories(),
					'awardConditions' => MEDAL_AWARD_CONDITIONS,
					'imageFilenames'  => getMedalImageFilenames(),
					'id'              => $medalId,
					'name'            => $name,
					'description'     => $description,
					'categoryId'      => $categoryId,
					'awardCondition'  => $awardCondition,
					'value'           => $value,
					'imageFilename'   => $imageFilename,
					'token'           => getCsrfToken()
				]);
				break;
			}
		}

		renderTemplate('edit_medal', [
			'categories'      => getMedalCategories(),
			'awardConditions' => MEDAL_AWARD_CONDITIONS,
			'imageFilenames'  => getMedalImageFilenames(),
			'id'              => $medal['id'],
			'name'            => $medal['name'],
			'description'     => $medal['description'],
			'categoryId'      => $medal['category'],
			'awardCondition'  => $medal['award_condition'],
			'value'           => $medal['value'],
			'imageFilename'   => $medal['image_filename'],
			'token'           => getCsrfToken()
		]);

	}
	while (false);