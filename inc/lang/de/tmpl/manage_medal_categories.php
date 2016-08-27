<h2>Medaillen-Kategorien verwalten</h2>

<ul>
	<?php foreach ($categories as $category): ?>
		<li><?= $category['name'] ?> (<a
				href="?p=admin&s=edit-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>">bearbeiten</a> | <a
				href="?p=admin&s=delete-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>">löschen</a>)
		</li>
	<?php endforeach; ?>
</ul>