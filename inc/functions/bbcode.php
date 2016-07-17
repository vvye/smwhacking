<?php

	require_once __DIR__ . '/../config/bbcode.php';
	require_once __DIR__ . '/../vendor/bbcode/stringparser_bbcode.class.php';


	function getBBCodeParser()
	{
		$bbcodeParser = new StringParser_BBCode();

		$bbcodes = BBCODES;
		foreach ($bbcodes as $bbcode)
		{
			$bbcodeParser->addCode($bbcode['code'], $bbcode['type'], $bbcode['callback'], $bbcode['params'],
				$bbcode['content_type'], $bbcode['allowed_in'], $bbcode['not_allowed_in']);
		}

		$bbcodeParser->addParser('block', 'nl2br');

		return $bbcodeParser;
	}


	function parseBBCode(StringParser_BBCode $bbcodeParser, $text)
	{
		return $bbcodeParser->parse($text);
	}
