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

		$categories = getMedalCategories();
		$replacements = array_filter($categories, function ($category) use ($categoryId)
		{
			return $category['id'] != $categoryId;
		});

		if (isset($_POST['submit']))
		{
			if (!isset($_POST['replacement']) || $_POST['replacement'] == $categoryId)
			{
				renderErrorMessage(MSG_INVALID_REPLACEMENT);
				break;
			}
			else
			{
				deleteCategory($categoryId, $_POST['replacement']);
				renderSuccessMessage(MSG_CATEGORY_DELETED);
				break;
			}
		}

		renderTemplate('delete_medal_category', [
			'category'     => $category,
			'replacements' => $replacements,
			'token'        => getCsrfToken()
		]);

	}
	while (false);