<?php

	require_once __DIR__ . '/../../functions/medals.php';

	do
	{
		if (!isset($_GET['token']) || !isCsrfTokenCorrect($_GET['token']))
		{
			renderErrorMessage(MSG_BAD_TOKEN);
			break;
		}

		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage(MSG_MEDAL_CATEGORY_DOESNT_EXIST);
			break;
		}
		$categoryId = $_GET['id'] * 1;

		$category = getMedalCategory($categoryId);

		if ($category === null)
		{
			renderErrorMessage(MSG_MEDAL_CATEGORY_DOESNT_EXIST);
			break;
		}

		if (isset($_POST['submit']))
		{
			$name = trim($_POST['name']);

			if ($name === '')
			{
				renderErrorMessage(MSG_NAME_EMPTY);
				$category['name'] = '';
			}
			else
			{
				editCategoryName($categoryId, $name);
				$category['name'] = $name;
				renderSuccessMessage(MSG_CATEGORY_EDITED);
			}
		}

		renderTemplate('edit_medal_category', [
			'category' => $category,
			'token'    => getCsrfToken()
		]);

	}
	while (false);