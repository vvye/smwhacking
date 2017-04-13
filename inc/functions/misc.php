<?php

	function sanitize($str)
	{
		return preg_replace('/[^A-Za-z0-9-_ ]/', '', $str);
	}


	function obfuscateEmail($email)
	{
		return str_ireplace(['@', '.'], [' <i class="fa fa-at"></i> ', ' <i class="fa fa-circle"></i> '], $email);
	}


	function makeBetween(&$var, $min, $max)
	{
		if ($max < $min)
		{
			$tmp = $max;
			$max = $min;
			$min = $tmp;
		}

		if ($var < $min)
		{
			$var = $min;
		}
		else if ($var > $max)
		{
			$var = $max;
		}
	}


	function startsWith($haystack, $needle)
	{
		return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}


	function truncatePreservingHtml($text, $length, $postfix = '&hellip;')
	{
		// https://stackoverflow.com/a/12310457/3972493

		$text = trim($text);
		$postfix = (strlen(strip_tags($text)) > $length) ? $postfix : '';
		$i = 0;
		$tags = [];

		preg_match_all('/<[^>]+>([^<]*)/', $text, $tagMatches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach ($tagMatches as $tagMatch)
		{
			if ($tagMatch[0][1] - $i >= $length)
			{
				break;
			}

			$tag = substr(strtok($tagMatch[0][0], " \t\n\r\0\x0B>"), 1);
			if ($tag[0] != '/')
			{
				$tags[] = $tag;
			}
			else if (end($tags) == substr($tag, 1))
			{
				array_pop($tags);
			}

			$i += $tagMatch[1][1] - $tagMatch[0][1];
		}

		return substr($text, 0, $length = min(strlen($text), $length + $i)) . (count($tags = array_reverse($tags)) ?
				'</' . implode('></', $tags) . '>' : '') . $postfix;
	}


	function logMessage($msg)
	{
		$logFilename = __DIR__ . '/../../log.txt';
		$logFile = fopen($logFilename, 'a');

		$callingFunctionName = debug_backtrace()[1]['function'];

		fwrite($logFile, date(DEFAULT_DATE_FORMAT) . "\t");
		fwrite($logFile, ($_SESSION['username'] ?? getClientIp()) . "\t");
		fwrite($logFile, $callingFunctionName . "\t");
		fwrite($logFile, $msg . "\r\n");

		fclose($logFile);
	}


	function getClientIp()
	{
		return $_SERVER['REMOTE_ADDR'];
	}


	function renderMessage($msg)
	{
		renderTemplate('message', [
			'type'    => '',
			'message' => $msg
		]);
	}


	function renderSuccessMessage($msg)
	{
		renderTemplate('message', [
			'type'    => 'success',
			'message' => $msg
		]);
	}


	function renderErrorMessage($msg)
	{
		renderTemplate('message', [
			'type'    => 'error',
			'message' => $msg
		]);
	}