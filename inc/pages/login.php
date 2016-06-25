<?php

	require_once __DIR__ . '/../functions/session.php';
	require_once __DIR__ . '/../functions/database.php';


	if (isset($_POST['submit']))
	{
		$database = getDatabase();

		$loginSuccess = doLogin();

		if (!$loginSuccess)
		{
			renderErrorMessage('Das Einloggen hat nicht geklappt. Stimmen E-Mail-Adresse und Passwort?<br />'
			. 'Wenn das Problem weiterhin auftritt, wende dich an info@smwhacking.de.');
		}
		else
		{
			header('Location: ?p=home');
		}
	}


?><h2>Einloggen</h2>

<form action="?p=login" method="post">
	<table>
		<tr>
			<td>E-Mail-Adresse:</td>
			<td><label><input type="email" name="email" /></label></td>
		</tr>
		<tr>
			<td>Passwort:</td>
			<td><label><input type="password" name="password" /></label></td>
		</tr>
		<tr>
			<td class="final-action" colspan="2"><button class="primary" type="submit" name="submit">Einloggen</button></td>
		</tr>
	</table>
</form>