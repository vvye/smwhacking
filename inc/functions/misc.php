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
		if ($var < $min)
		{
			$var = $min;
		}
		else if ($var > $max)
		{
			$var = $max;
		}
	}


	function renderMessage($msg)
	{
		renderTemplate('message', [
			'type' => '',
			'message' => $msg
		]);
	}


	function renderSuccessMessage($msg)
	{
		renderTemplate('message', [
			'type' => 'success',
			'message' => $msg
		]);
	}


	function renderErrorMessage($msg)
	{
		renderTemplate('error', [
			'type' => '',
			'message' => $msg
		]);
	}