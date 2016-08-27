<h2>Medaille löschen: <?= $name ?></h2>

<p>Bitte bestätige, dass du die Medaille <em><?= $name ?></em> löschen möchtest.</p>
<p>Allen Nutzern, die diese Medaille besitzen, wird sie aberkannt. Das kann nicht rückgängig gemacht werden!</p>

<form action="?p=admin&s=delete-medal&id=<?= $id ?>&token=<?= $token ?>" method="post">
	<a class="button primary" href="?p=admin&s=manage-medals">Abbrechen</a>
	<button class="subtle" type="submit" name="submit">Medaille löschen</button>
</form>
