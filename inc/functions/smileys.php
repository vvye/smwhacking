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


	function parseSmileys($text)
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

		$smileyImages = array_map(function ($smiley)
		{
			return '<img src="img/smileys/' . $smiley['image_filename'] . '" />';
		}, $smileys);

		$text = str_replace($smileyCodes, $smileyImages, $text);

		return $text;
	}