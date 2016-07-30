<?php

	function getAllMedals()
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
		]);

		return $medals;
	}


	function getMedalsByIds($medalIds)
	{
		global $database;

		$medals = $database->select('medals', [
			'medals.name',
			'medals.description'
		], [
			'id' => $medalIds
		]);

		return $medals;

	}


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


	function awardMedalToMultipleUsers($userIds, $medalId)
	{
		global $database;

		$awardTime = time();
		$data = [];
		foreach ($userIds as $userId)
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


	function ensureCorrectRankFormat(&$favoriteMedalRanks)
	{
		$ranksFormatCorrect = (count(array_unique($favoriteMedalRanks)) === count($favoriteMedalRanks));
		if ($ranksFormatCorrect)
		{
			$rankValues = array_values($favoriteMedalRanks);
			for ($i = 1; $i <= min(count($rankValues), MAX_FAVORITE_MEDALS); $i++)
			{
				if (!in_array($i, $rankValues))
				{
					$ranksFormatCorrect = false;
					break;
				}
			}
		}

		if (!$ranksFormatCorrect)
		{
			$i = 1;
			foreach ($favoriteMedalRanks as $medal => $rank)
			{
				$favoriteMedalRanks[$medal] = $i++;
			}
		}
	}


	function setFavoriteMedals($userId, $medals)
	{
		global $database;

		$database->update('awarded_medals', [
			'favorite' => 0
		], [
			'user' => $userId
		]);

		foreach ($medals as $medalId => $rank)
		{
			$database->update('awarded_medals', [
				'favorite' => $rank
			], [
				'AND' => [
					'user'  => $userId,
					'medal' => $medalId
				]
			]);
		}
	}


	function getFavoriteMedals($userId)
	{
		global $database;

		$favoriteMedals = $database->select('awarded_medals', [
			'[>]medals'           => ['medal' => 'id'],
			'[>]medal_categories' => ['medals.category' => 'id']
		], [
			'medals.id',
			'medal_categories.name(category_name)',
			'medals.name',
			'medals.description',
			'medals.image_filename',
			'awarded_medals.award_time',
			'awarded_medals.favorite(rank)'
		], [
			'AND'   => [
				'awarded_medals.user'        => $userId,
				'awarded_medals.favorite[!]' => 0
			],
			'ORDER' => 'awarded_medals.favorite ASC'
		]);

		return $favoriteMedals;
	}


	function getUserIdsByMedal($medalId)
	{
		global $database;

		$userIds = $database->select('awarded_medals', 'user', [
			'medal' => $medalId
		]);

		return $userIds;
	}


	function getAutomaticMedals()
	{
		global $database;

		$automaticMedals = $database->select('medals', '*', [
			'award_condition[!]' => 'manual'
		]);

		return $automaticMedals;
	}


	function getUserIdsEligibleForAutomaticMedal($medal)
	{
		global $database;

		$medalId = $medal['id'];
		$awardCondition = $medal['award_condition'];
		$value = $medal['value'];

		$userIdsWithMedal = getUserIdsByMedal($medalId);

		if ($awardCondition === 'post_count')
		{
			$users = $database->query('
				SELECT users.id
				FROM users
				LEFT JOIN posts ON users.id = posts.author AND posts.deleted = 0
				GROUP BY users.id
				HAVING COUNT(posts.id) >= ' . $database->quote($value) . '
			')->fetchAll(PDO::FETCH_ASSOC);

			$userIds = array_map(function ($user)
			{
				return $user['id'];
			}, $users);
		}
		else if ($awardCondition === 'registration_time')
		{
			$registrationTime = time() - $value;

			$userIds = $database->select('users', 'id', [
				'registration_time[<=]' => $registrationTime
			]);
		}
		else
		{
			$userIds = [];
		}

		$userIds = array_diff($userIds, $userIdsWithMedal);

		return $userIds;
	}