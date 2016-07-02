<section class="user-section">
	<h3>Nutzer-Info</h3>
	<div class="content">
		<div class="sidebar">
			<?php echo $avatarHtml; ?>
		</div>
		<table>
			<tr>
				<td>Powerlevel:</td>
				<td><?php echo $powerlevel; ?></td>
			</tr>
			<tr>
				<td>Rang:</td>
				<td><?php echo $rankHtml; ?></td>
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
				<td><?php echo $lastPostHtml; ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>Website:</td>
				<td><?php echo $websiteHtml; ?></td>
			</tr>
			<tr>
				<td>E-Mail:</td>
				<td><?php echo $emailHtml; ?></td>
			</tr>
		</table>
		<div class="clearfix"></div>
	</div>
</section>