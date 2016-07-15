<?php


	function getAwardableMedals()
	{
		global $database;

		$medals = $database->select('medals', [
			'[>]medal_categories' => ['category' => 'id']
		], [
			'medals.id',
			'medal_categories.name(category_name)',
			'medals.name',
			'medals.description',
			'medals.image_filename',
		], [
			'award_condition' => 'manual'
		]);

		return $medals;
	}


	function getAwardedMedalsByUser($userId)
	{
		global $database;

		$medals = $database->select('awarded_medals', [
			'[>]medals'           => ['medal' => 'id'],
			'[>]medal_categories' => ['medals.category' => 'id']
		], [
			'medals.id',
			'medal_categories.name(category_name)',
			'medals.name',
			'medals.description',
			'medals.image_filename',
			'awarded_medals.award_time'
		], [
			'awarded_medals.user' => $userId
		]);

		return $medals;
	}


	function getAwardableMedalsByUser($userId)
	{
		$medals = getAwardableMedals();
		$awardedMedals = getAwardedMedalsByUser($userId);
		$awardedMedalIds = array_map('getMedalId', $awardedMedals);
		$awardableMedals = array_filter($medals, function ($medal) use ($awardedMedalIds)
		{
			return !in_array($medal['id'], $awardedMedalIds);
		});

		return $awardableMedals;
	}


	function getMedalId($medal)
	{
		return $medal['id'];
	}


	function getMedalsByCategory($medals)
	{
		$medalsByCategory = [];
		foreach ($medals as $key => $medal)
		{
			$medalsByCategory[$medal['category_name']][$key] = $medal;
			$medalsByCategory[$medal['category_name']] = array_values($medalsByCategory[$medal['category_name']]);
		}

		return $medalsByCategory;
	}


	function awardMedals($userId, $medalIds)
	{
		global $database;

		$awardTime = time();
		$data = [];
		foreach ($medalIds as $medalId)
		{
			$data[] = [
				'user'       => $userId,
				'medal'      => $medalId,
				'award_time' => $awardTime
			];
		}

		$database->insert('awarded_medals', $data);
	}


	function removeMedals($userId, $medalIds)
	{
		global $database;

		$database->delete('awarded_medals', [
			'AND' => [
				'user'  => $userId,
				'medal' => $medalIds,
			]
		]);

	}