<h2>Medaillen-Kategorien verwalten</h2>

<a class="button" href="?p=admin&s=new-medal-category&token=<?= $token ?>"><i class="fa fa-plus"></i> Kategorie
	hinzufügen</a>

<ul>
	<?php foreach ($categories as $category): ?>
		<li><?= $category['name'] ?> (<a
					href="?p=admin&s=edit-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>">bearbeiten</a> |
			<a
					href="?p=admin&s=delete-medal-category&id=<?= $category['id'] ?>&token=<?= $token ?>">löschen</a>)
		</li>
	<?php endforeach; ?>
</ul>