<?php

	require_once __DIR__ . '/../functions/medals.php';


	$medals = getAllMedals();
	$medalsByCategory = getMedalsByCategory($medals);

	$awardedMedals = isLoggedIn() ? getAwardedMedalsByUser($_SESSION['userId']) : [];
	$awardedMedalIds = array_map('getMedalId', $awardedMedals);

	renderTemplate('all_medals', [
		'medalsByCategory' => $medalsByCategory,
		'awardedMedalIds'  => $awardedMedalIds
	]);