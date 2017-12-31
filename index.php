<?php

	/*
		smwhacking.de
		WYE, 2016-17
	*/

	require_once 'inc/lang/de/strings.php';

	require_once 'inc/functions/environment.php';
	require_once 'inc/functions/engine.php';
	require_once 'inc/functions/template.php';
	require_once 'inc/functions/theme.php';
	require_once 'inc/functions/chat.php';
	require_once 'inc/functions/database.php';
	require_once 'inc/functions/session.php';

	session_start();

	$database = getDatabase();

	handlePersistentLogin();

	$theme = getTheme();
	$celebrationCssClass = getCelebrationCssClass();

	updateLastActivityTime();

	date_default_timezone_set('Europe/Berlin');

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
		<link rel="stylesheet" type="text/css" href="css/common/responsive.css" />

		<?php if (isAdmin()): ?>
			<link rel="stylesheet" type="text/css" href="css/<?= $theme ?>/admin.css" />
		<?php endif; ?>

		<link rel="icon" type="image/x-icon" href="favicon.ico">

	</head>

	<body>

		<header>
			<div class="banner <?= $celebrationCssClass; ?>">
				<h1><a href="?p=home"><img src="img/logo.png" alt="smwhacking.de" /></a></h1>
				<p>die deutschsprachige Seite übers SMW-Hacken</p>
			</div>
			<?php

				renderMenu();
				renderUserMenu();
				renderChatBar();

			?>
		</header>

		<div class="main">
			<?php

				renderPage();

			?>
		</div>

		<?php

			renderFooter();

		?>

		<script type="text/javascript" src="js/secret_code.js"></script>

	</body>

</html>