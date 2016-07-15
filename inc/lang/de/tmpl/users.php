<table class="users">

	<thead>
	<tr>
		<th class="id"><a href="?p=users&sort=id&dir=<?= $sortDirections['id'] ?>">#</a></th>
		<th class="name"><a href="?p=users&sort=name&dir=<?= $sortDirections['name'] ?>">Name</a></th>
		<th class="registration-time"><a
				href="?p=users&sort=registration-time&dir=<?= $sortDirections['registration-time'] ?>">registriert
				am</a></th>
		<th class="last-login-time"><a
				href="?p=users&sort=last-login-time&dir=<?= $sortDirections['last-login-time'] ?>">zuletzt eingeloggt
				am</a></th>
		<th class="num-posts"><a href="?p=users&sort=num-posts&dir=<?= $sortDirections['num-posts'] ?>">Beiträge</a>
		</th>
		<th class="rank">Rang</th>
		<th class="location">Wohnort</th>
		<th class="website">Website</th>
		<th class="status">Status</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($users as $user): ?>
		<tr>
			<td class="id"><?= $user['id'] ?></td>
			<td class="name"><a href="?p=user&id=<?= $user['id'] ?>"><?= $user['name'] ?></a></td>
			<td class="registration-time"><?= date(DEFAULT_DATE_FORMAT, $user['registration_time']) ?></td>
			<td class="last-login-time"><?= date(DEFAULT_DATE_FORMAT, $user['last_login_time']) ?></td>
			<td class="num-posts"><?= $user['num_posts'] ?></td>
			<td class="rank">
				<?php if ($user['rank']['has_image']): ?>
					<img src="img/ranks/<?= $user['rank']['id'] ?>.png" alt="" />
				<?php endif; ?>
				<?= $user['rank']['name'] ?>
			</td>
			<td class="location"><?= $user['location'] ?></td>
			<td class="website"><?= $user['website'] ?></td>
			<td class="status">
				<?php if ($user['banned']): ?>
					<i class="fa fa-ban"></i> gesperrt
				<?php elseif ((int)$user['powerlevel'] !== 0): ?>
					<?= $user['powerlevel_description'] ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>

</table>