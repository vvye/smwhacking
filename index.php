<?php

	/*
		smwhacking.de
		WYE, 2016
	*/

	require_once 'inc/lang/de/strings.php';

	require_once 'inc/functions/environment.php';
	require_once 'inc/functions/engine.php';
	require_once 'inc/functions/template.php';
	require_once 'inc/functions/theme.php';
	require_once 'inc/functions/database.php';

	$database = getDatabase();

	session_start();

	$theme = getTheme();

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />

		<title>smwhacking.de - die deutschsprachige Seite übers SMW-Hacken</title>

		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic" rel="stylesheet"
			  type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/common/normalize.min.css" />
		<link rel="stylesheet" type="text/css" href="css/common/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/main.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/form.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/forums.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/user.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/cuteedit.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/files.css" />
		<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/chat.css" />

		<?php if (isAdmin()): ?>
			<link rel="stylesheet" type="text/css" href="css/default/admin.css" />
		<?php endif; ?>

	</head>

	<body>

		<header>
			<div class="banner">
				<h1><a href="?p=home"><img src="img/logo.png" alt="smwhacking.de" /></a></h1>
				<p>die deutschsprachige Seite übers SMW-Hacken</p>
			</div>
			<?php

				renderMenu();
				renderUserMenu();

			?>
		</header>

		<div class="main">
			<?php

				renderPage();

			?>
		</div>

	</body>

</html>