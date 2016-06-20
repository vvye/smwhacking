<?php

	function sanitize($str)
	{
		return preg_replace('/[^A-Za-z0-9- ]/', '', $str);
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


	function renderSuccessMessage($msg)
	{
		echo '<div class="message success">' . $msg . '</div>';
	}
	

	function renderErrorMessage($msg)
	{
		echo '<div class="message error">' . $msg . '</div>';
	}