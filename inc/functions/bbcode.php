<?php

	require_once __DIR__ . '/smileys.php';

	require_once __DIR__ . '/../config/bbcode.php';

	require_once __DIR__ . '/../vendor/JBBCode/Parser.php';


	function getBBCodeParser()
	{
		$parser = new JBBCode\Parser();
		$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

		$builder = new JBBCode\CodeDefinitionBuilder('s', '<del>{param}</del>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('center', '<div style="text-align: center;">{param}</div>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('size', '<span style="font-size: {option}%;">{param}</span>');
		$builder->setUseOption(true)->setOptionValidator(new class implements JBBCode\InputValidator
		{
			function validate($input)
			{
				return ctype_digit($input);
			}
		});
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('ispoiler', '<div class="inline-spoiler"><div class="inner">{param}</div></div>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('code', '<div class="code"><span class="head">Code</span><div class="box">{param}</div></div>');
		$builder->setParseContent(false);
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('quote', '<div class="quote"><span class="head">' . BBCODE_QUOTE . '</span><div class="box">{param}</div></div>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('quote', '<div class="quote"><span class="head">' . BBCODE_QUOTE_BY . ' {option}: </span><div class="box">{param}</div></div>');
		$builder->setUseOption(true);
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('spoiler', '<div class="spoiler"><span class="head">' . BBCODE_SPOILER . ' <a class="small button">anzeigen</a></span><div class="box">{param}</div></div>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('spoiler', '<div class="spoiler"><span class="head">{option} <a class="small button">' . BBCODE_SPOILER_SHOW . '</a></span><div class="box">{param}</div></div>');
		$builder->setUseOption(true);
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('spoiler', '<div class="spoiler"><span class="head">{option} <a class="small button">' . BBCODE_SPOILER_SHOW . '</a></span><div class="box">{param}</div></div>');
		$builder->setUseOption(true);
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('youtube', '<iframe width="480" height="320" src="https://www.youtube-nocookie.com/embed/{param}" frameborder="0" allowfullscreen></iframe>');
		$builder->setParseContent(false);
		$builder->setBodyValidator(new class implements JBBCode\InputValidator
		{
			function validate($input)
			{
				return preg_match('/^[a-zA-Z0-9_-]{11}$/', $input) === 1;
			}
		});
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('list', '<ul class="bbcode-list">{param}</ul>');
		$parser->addCodeDefinition($builder->build());

		$builder = new JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
		$parser->addCodeDefinition($builder->build());

		return $parser;
	}


	function parseBBCode($text)
	{
		global $parser;
		if ($parser === null)
		{
			$parser = getBBCodeParser();
		}

		$text = nl2br($text);

		$parser->parse($text);
		$text = $parser->getAsHTML();

		$text = parseSmileys($text);

		return $text;
	}


	function removeBBCode($text)
	{
		global $parser;
		if ($parser === null)
		{
			$parser = getBBCodeParser();
		}

		$text = nl2br($text);

		$parser->parse($text);
		$text = $parser->getAsText();

		return $text;
	}


	function removeBBCodeAndLineBreaks($text)
	{
		global $parser;
		if ($parser === null)
		{
			$parser = getBBCodeParser();
		}

		$parser->parse($text);
		$text = $parser->getAsText();

		return $text;
	}