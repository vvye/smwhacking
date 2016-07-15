<h2>Nutzer sperren</h2>

<p>Bitte bestätige, dass du <?= $username ?> sperren möchtest.</p>
<p>Gesperrte Nutzer können keine Beiträge schreiben und ihr Profil nicht ändern.</p>

<form method="post" action="?p=ban&user=<?= $userId ?>&token=<?= $token ?>">
	<a class="button primary" href="?p=user&id=<?= $threadId ?>">Abbrechen</a>
	<button class="subtle" type="submit" name="submit"><?= $username ?> sperren</button>
</form>