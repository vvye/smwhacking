<?php

	require_once __DIR__ . '/../functions/user.php';

	$ranks = getAllRanks();

	if (isset($_POST['submit']))
	{
		$id = 1;
		$newRanks = [];

		while (isset($_POST['name-' . $id]))
		{
			if ($_POST['name-' . $id] !== '' && $_POST['min-posts-' . $id] !== '')
			{
				if (isset($_POST['change-image-' . $id]))
				{
					$hasImage = isset($_POST['delete-image-' . $id]) ? false : processUploadedRankImage($id);
				}
				else
				{
					$hasImage = $ranks[$id - 1]['has_image'];
				}

				$newRanks[] = [
					'id'        => null,
					'name'      => trim($_POST['name-' . $id]),
					'min_posts' => $_POST['min-posts-' . $id] * 1,
					'has_image' => $hasImage ? 1 : 0
				];
			}

			$id++;
		}

		editRanks($newRanks);

		renderSuccessMessage(MSG_MANAGE_RANKS_SUCCESS);

		$ranks = getAllRanks();
	}

	renderTemplate('manage_ranks', [
		'ranks'    => $ranks,
		'numRanks' => count($ranks)
	]);