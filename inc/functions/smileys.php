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

		$smileyCodeRegexes = array_map(function ($smiley)
		{
			return '/(^|[^a-z0-9])' . preg_quote($smiley['code']) . '([^a-z0-9]|$)/i';
		}, $smileys);

		$smileyCodesWithDelimiterRegex = array_map(function ($smiley)
		{
			$delimiter = '<!-- s' . $smiley['code'] . ' -->';

			return '$1' . $delimiter . $smiley['code'] . $delimiter . '$2';
		}, $smileys);

		return preg_replace($smileyCodeRegexes, $smileyCodesWithDelimiterRegex, $text);
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


	function removeSmileyDelimiters($text)
	{
		global $smileys;
		if ($smileys === null)
		{
			$smileys = getSmileys();
		}

		$smileyDelimiters = array_map(function ($smiley)
		{
			return '<!-- s' . $smiley['code'] . ' -->';
		}, $smileys);

		$text = str_replace($smileyDelimiters, '', $text);

		return $text;
	}