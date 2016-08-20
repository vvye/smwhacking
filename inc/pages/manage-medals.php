<?php

	require_once __DIR__ . '/../functions/medals.php';

	$medals = getAllMedals();
	$medalsForTemplate = [];

	foreach ($medals as $medal)
	{
		$newMedal = $medal;
		switch ($medal['award_condition'])
		{
			case 'manual':
				$newMedal['award_condition_text'] = MEDAL_MANUAL;
				break;
			case 'registration_time':
				$newMedal['award_condition_text'] = MEDAL_REGISTRATION_TIME . ' (' . $medal['value'] . ' Sekunden)';
				break;
			case 'post_count':
				$newMedal['award_condition_text'] = MEDAL_POST_COUNT . ' (' . $medal['value'] . ')';
				break;
			default:
				$newMedal['award_condition_text'] = '';
				break;
		}
		$medalsForTemplate[] = $newMedal;
	}

	renderTemplate('manage_medals', [
		'medals' => $medalsForTemplate
	]);