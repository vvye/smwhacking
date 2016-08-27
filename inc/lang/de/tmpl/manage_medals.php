<h2>Medaillen verwalten</h2>

<br />
<a class="button primary" href="?p=admin&s=new-medal"><i class="fa fa-plus"></i> Medaille erstellen</a>

<table class="medals">
	<thead>
	<tr>
		<th class="image">Bild</th>
		<th>Name</th>
		<th>Beschreibung</th>
		<th>Kategorie</th>
		<th>Bedingung</th>
		<th>Aktion</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($medals as $medal): ?>
		<tr>
			<td class="image">
				<img src="img/medals/<?= $medal['image_filename'] ?>" />
				<p><?= $medal['image_filename'] ?></p>
			</td>
			<td><?= $medal['name'] ?></td>
			<td><?= $medal['description'] ?></td>
			<td><?= $medal['category_name'] ?></td>
			<td><?= $medal['award_condition_text'] ?></td>
			<td>
				<a class="small button" href="?p=admin&s=edit-medal&id=<?= $medal['id'] ?>&token=<?= $token ?>">bearbeiten</a>
				<a class="small button" href="?p=admin&s=delete-medal&id=<?= $medal['id'] ?>&token=<?= $token ?>">löschen</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>


</table>