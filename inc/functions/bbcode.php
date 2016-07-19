<?php

	require_once __DIR__ . '/../config/bbcode.php';


	function parseBBCode($text)
	{
		$bbcodes = BBCODES;

		foreach ($bbcodes as $bbcode)
		{
			$text = parseSingleBBCode($text, $bbcode);
		}

		return nl2br($text);
	}


	function parseSingleBBCode($text, $bbcode, $startPos = 0, $endPos = 0)
	{
		$tag = $bbcode['tag'];

		$openingTag = '[' . $tag . ']';
		$closingTag = '[/' . $tag . ']';

		$openingReplacement = $bbcode['replacement']['start'];
		$closingReplacement = $bbcode['replacement']['end'];



		return $text;
	}
