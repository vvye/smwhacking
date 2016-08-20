<h2>Ränge verwalten</h2>
<p>Ränge, bei denen die Felder "Name" oder "Beiträge" nicht ausgefüllt sind, werden beim Speichern gelöscht.</p>

<form action="?p=admin&s=manage-ranks" method="post" enctype="multipart/form-data">
	<table class="ranks">
		<thead>
		<tr>
			<th class="name">Name</th>
			<th class="min-posts">Beiträge</th>
			<th>Bild</th>
		</tr>
		</thead>
		<tbody id="rank-list">
		<?php foreach ($ranks as $rank): ?>
			<tr>
				<td class="name">
					<input type="text" name="name-<?= $rank['id'] ?>" value="<?= $rank['name'] ?>" />
				</td>
				<td class="min-posts">
					<input type="number" name="min-posts-<?= $rank['id'] ?>" value="<?= $rank['min_posts'] ?>"
					       min="0" />
				</td>
				<td>
					<?php if ($rank['has_image']): ?>
						<img src="img/ranks/<?= $rank['id'] ?>.png" alt="<?= $rank['name'] ?>" />
					<?php else: ?>
						<em>Kein Bild</em><br />
					<?php endif; ?>
					<div class="custom-checkbox-group">
						<input type="checkbox" class="custom-checkbox" name="change-image-<?= $rank['id'] ?>"
						       id="change-image-<?= $rank['id'] ?>" />
						<label class="custom-checkbox-label" for="change-image-<?= $rank['id'] ?>"> Bild ändern</label>
					</div>
					<div>
						<div id="edit-image-box-<?= $rank['id'] ?>">
							<input type="file" class="file-input" name="image-<?= $rank['id'] ?>"
							       id="image-<?= $rank['id'] ?>" />
							<div class="custom-checkbox-group">
								<input type="checkbox" class="custom-checkbox" name="delete-image-<?= $rank['id'] ?>"
								       id="delete-image-<?= $rank['id'] ?>" />
								<label class="custom-checkbox-label" for="delete-image-<?= $rank['id'] ?>"> Bild
									löschen</label>
							</div>
						</div>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<a class="button" id="add-rank"><i class="fa fa-plus"></i> Rang hinzufügen</a>
	<button class="primary" type="submit" name="submit">Änderungen speichern</button>
</form>

<script type="text/javascript" src="js/fileInput.js"></script>
<script type="text/javascript">

	var numRanks = <?= $numRanks ?>;

	// what
	/*
	 fileInput('file-input', {
	 buttonText: 'Durchsuchen&hellip;',
	 noFileText: 'Keine Datei ausgewählt.'
	 });

	 function handleImageInputs(i) {
	 var editAvatarBox = document.getElementById('edit-image-box-' + i);
	 var avatarInput = document.getElementById('image-' + i);
	 var changeAvatarCheckbox = document.getElementById('change-image-' + i);
	 var deleteAvatarCheckbox = document.getElementById('delete-image-' + i);

	 (changeAvatarCheckbox.onchange = function () {
	 editAvatarBox.style.display = changeAvatarCheckbox.checked ? '' : 'none';
	 })();

	 (deleteAvatarCheckbox.onchange = function () {
	 avatarInput.style.display = deleteAvatarCheckbox.checked ? 'none' : '';
	 })()
	 }

	 function handleAllImageInputs() {
	 for (var i = 1; i <= numRanks; i++) {
	 handleImageInputs(i);
	 }
	 }
	 */

	document.getElementById('add-rank').onclick = function () {
		var k = ++numRanks;
		document.getElementById('rank-list').innerHTML += '<tr><td class="name"><input type="text" name="name-' + k + '" value="" /></td><td class="min-posts"><input type="number" name="min-posts-' + k + '" value="" min="0" /></td><td><div><em>Kein Bild</em><br /><div class="custom-checkbox-group"><input type="checkbox" class="custom-checkbox" name="change-image-' + k + '" id="change-image-' + k + '" /><label class="custom-checkbox-label" for="change-image-' + k + '"> Bild ändern</label></div><div><div id="edit-image-box-' + k + '"><input type="file" class="file-input" name="image-' + k + '" id="image-' + k + '" /><div class="custom-checkbox-group"><input type="checkbox" class="custom-checkbox" name="delete-image-' + k + '"		id="delete-image-' + k + '" /><label class="custom-checkbox-label" for="delete-image-' + k + '"> Bild löschen</label></div></div></div></td></tr>';
	}


</script>