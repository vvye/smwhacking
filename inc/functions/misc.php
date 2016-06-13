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
	

	function renderPagination($link, $page, $numPages)
	{
		if ($numPages === 1)
		{
			return;
		}

		$elision = false;

		echo '<ul class="pagination">';
		for ($i = 1; $i <= $numPages; $i++)
		{
			$range = 2;

			if ($i <= $range + 1 || $i >= $numPages - $range || ($page - $range <= $i && $page + $range >= $i))
			{
				$selected = ($i === $page) ? ' class="selected"' : '';
				echo '<li' . $selected . '><a href="' . $link . '&page=' . $i . '">' . $i . '</a></li>';
				$elision = false;
			}
			else if (!$elision)
			{
				echo '<li class="elision">&hellip;</li>';
				$elision = true;
			}
		}
		echo '</ul>';
	}