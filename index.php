<?php

	/*
		smwhacking.de
		WYE, 2016
	*/
	
	require_once 'inc/functions/engine.php';

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />

		<title>smwhacking.de - die deutschsprachige Seite übers SMW-Hacken</title>

		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic" rel="stylesheet"
		      type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/normalize.min.css" />
		<link rel="stylesheet" type="text/css" href="css/main.css" />
		<link rel="stylesheet" type="text/css" href="css/form.css" />
		<link rel="stylesheet" type="text/css" href="css/forums.css" />
		<link rel="stylesheet" type="text/css" href="css/user.css" />

		<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />

	</head>

	<body>

		<header>
			<div class="banner">
				<h1><a href="/"><img src="img/logo.png" alt="smwhacking.de" /></a></h1>
				<p>die deutschsprachige Seite übers SMW-Hacken</p>
			</div>
			<?php

				renderMenu();

			?>
			<nav class="user-menu">
				<ul>
					<li><a href="?p=login">Einloggen</a></li>
					<li><a href="?p=register">Registrieren</a></li>
				</ul>
			</nav>
		</header>

		<div class="main">
			<?php

				renderPage();

			?>
		</div>

	</body>

</html>
