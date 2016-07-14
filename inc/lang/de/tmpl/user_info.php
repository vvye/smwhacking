<section class="user-section">
	<h3>Nutzer-Info</h3>
	<div class="content">
		<div class="sidebar">
			<?php if ($hasAvatar): ?>
				<img class="avatar" src="img/avatars/<?= $id ?>.png" alt="Avatar" />
			<?php else: ?>
				<img class="avatar" src="img/avatars/default.png" alt="Avatar" />
			<?php endif; ?>
		</div>
		<table>
			<tr>
				<td>Powerlevel:</td>
				<td><?php echo $powerlevel; ?></td>
			</tr>
			<tr>
				<td>Rang:</td>
				<td>
					<?php if ($rank['has_image']): ?>
						<img src="img/ranks/<?= $rank['id'] ?>.png" alt="<?= $rank['name'] ?>" />
					<?php endif; ?>
					<?= $rank['name'] ?>
				</td>
			</tr>
			<tr>
				<td>Titel:</td>
				<td><?php echo $title; ?></td>
			</tr>
			<tr>
				<td>Registriert am:</td>
				<td><?php echo $registrationTime; ?></td>
			</tr>
			<tr>
				<td>Letzte Anmeldung:</td>
				<td><?php echo $lastLoginTime; ?></td>
			</tr>
			<tr>
				<td>Beiträge:</td>
				<td><?php echo $numPosts; ?></td>
			</tr>
			<tr>
				<td>Letzter Beitrag:</td>
				<td>
					<?php if ($lastPost === null): ?>
						<em><?= MSG_NONE ?></em>
					<?php elseif (!$canViewLastPost): ?>
						<em><?= MSG_VIEW_POST_NOT_ALLOWED ?></em>
					<?php else: ?>
						<?= date(DEFAULT_DATE_FORMAT, $lastPost['post_time']) ?>
						in <a
							href="?p=thread&id=<?= $lastPost['thread_id'] ?>&page=<?= $lastPostPage ?>#post-<?= $lastPost['id'] ?>"><?= $lastPost['thread_name'] ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan=" 2">&nbsp;</td>
			</tr>

			<tr>
				<td>Wohnort:</td>
				<td><?= $location ?></td>
			</tr>
			<tr>
				<td>Website:</td>
				<td>
					<?php if ($website !== ''): ?>
						<a href="<?= $website ?>"><?= $website ?></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php if (false): // TODO choose to make email public ?>
				<tr>
					<td>E-Mail:</td>
					<td><?php echo $emailHtml; ?></td>
				</tr>
			<?php endif; ?>
		</table>
		<div class="clearfix"></div>
	</div>
</section>