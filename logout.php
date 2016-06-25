<?php

	require_once __DIR__ . '/inc/functions/session.php';

	doLogout();

	header('Location: /?p=home');