<?php

	function sanitize($str)
	{
		return preg_replace('/[^A-Za-z0-9- ]/', '', $str);
	}