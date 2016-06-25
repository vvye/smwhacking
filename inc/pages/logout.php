<?php

	require_once __DIR__ . '/../functions/session.php';

	doLogout();

	header('Location: ?p=home');