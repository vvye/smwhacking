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
			$numRemovedMedals = deleteMedal($medalId);

			$message = str_replace('{{NUM}}', $numRemovedMedals, MSG_MEDAL_DELETED);
			renderSuccessMessage($message);
		}
		else
		{
			renderTemplate('delete_medal', [
				'id'    => $medal['id'],
				'name'  => $medal['name'],
				'token' => getCsrfToken()
			]);
		}
	}
	while (false);