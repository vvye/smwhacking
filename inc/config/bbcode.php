<?php

	// http://christian-seiler.de/projekte/php/bbcode/doc/de/kapitel2.php

	function bbcodeQuote($action, $attributes, $content, $params, &$node_object)
	{
		$headText = !isset ($attributes['default']) ? BBCODE_QUOTE : BBCODE_QUOTE_BY . ' ' . $attributes['default'] . ':';

		return '<div class="quote"><span class="head">' . htmlspecialchars($headText)
		. '</span><div class="box">' . $content . '</div></div>';
	}


	define('BBCODES', [
		[
			'code'           => 'b',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<strong>',
				'end_tag'   => '</strong>'
			],
			'content_type'   => 'inline',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'i',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<em>',
				'end_tag'   => '</em>'
			],
			'content_type'   => 'inline',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'u',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<u>',
				'end_tag'   => '</u>'
			],
			'content_type'   => 'inline',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 's',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<del>',
				'end_tag'   => '</del>'
			],
			'content_type'   => 'inline',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'center',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<div style="text-align: center;">',
				'end_tag'   => '</div>'
			],
			'content_type'   => 'block',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'quote',
			'type'           => 'callback_replace',
			'callback'       => 'bbcodeQuote',
			'params'         => [],
			'content_type'   => 'block',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'code',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<div class="code"><span class="head">' . BBCODE_CODE . '</span><div class="box">',
				'end_tag'   => '</div></div>'
			],
			'content_type'   => 'block',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		], [
			'code'           => 'spoiler',
			'type'           => 'simple_replace',
			'callback'       => null,
			'params'         => [
				'start_tag' => '<div class="spoiler"><span class="head">Spoiler <a class="small subtle button">anzeigen</a></span><div class="box">',
				'end_tag'   => '</div></div>'
			],
			'content_type'   => 'block',
			'allowed_in'     => ['block', 'inline'],
			'not_allowed_in' => []
		]
	]);