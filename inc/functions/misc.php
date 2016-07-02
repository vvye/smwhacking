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
		echo '<div class="message">' . $msg . '</div>';
	}


	function renderSuccessMessage($msg)
	{
		echo '<div class="message success">' . $msg . '</div>';
	}


	function renderErrorMessage($msg)
	{
		echo '<div class="message error">' . $msg . '</div>';
	}