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


	function renderErrorMessage($msg)
	{
		echo '<div class="message error">' . $msg . '</div>';
	}