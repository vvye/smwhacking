<?php

	function getSmileys()
	{
		global $database;

		return $database->select('smileys', '*');
	}


	function getDistinctSmileys()
	{
		global $database;

		return $database->select('smileys', '*', [
			'GROUP' => 'name',
			'ORDER' => 'id'
		]);
	}


	// mark smileys with delimiters (called before saving text to the database)
	// without delimiters, smileys would mistakenly be parsed in cases like (&gt;)
	function delimitSmileys($text)
	{
		global $smileys;
		if ($smileys === null)
		{
			$smileys = getSmileys();
		}

		$smileyCodes = array_map(function ($smiley)
		{
			return $smiley['code'];
		}, $smileys);

		$smileyCodesWithDelimiter = array_map(function ($smiley)
		{
			$delimiter = '<!-- s' . $smiley['code'] . ' -->';

			return $delimiter . $smiley['code'] . $delimiter;
		}, $smileys);

		return str_replace($smileyCodes, $smileyCodesWithDelimiter, $text);
	}


	// turn smileys with delimiters into images (called before displaying text)
	function parseSmileys($text)
	{
		global $smileys;
		if ($smileys === null)
		{
			$smileys = getSmileys();
		}

		$smileyCodesWithDelimiter = array_map(function ($smiley)
		{
			$delimiter = '<!-- s' . $smiley['code'] . ' -->';

			return $delimiter . $smiley['code'] . $delimiter;
		}, $smileys);

		$smileyImages = array_map(function ($smiley)
		{
			return '<img src="img/smileys/' . $smiley['image_filename'] . '" />';
		}, $smileys);

		$text = str_replace($smileyCodesWithDelimiter, $smileyImages, $text);

		return $text;
	}