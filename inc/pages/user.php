<?php

	require_once __DIR__ . '/../functions/database.php';
	require_once __DIR__ . '/../functions/forums.php';
	require_once __DIR__ . '/../functions/user.php';


	$database = getDatabase();

	do
	{
		if (!isset($_GET['id']) || !is_int($_GET['id'] * 1))
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}
		$id = (int)$_GET['id'];

		$user = getUser($id, $database);

		if ($user === null)
		{
			renderErrorMessage('Diesen Nutzer gibt es nicht.');
			break;
		}

		$name = $user['name'];
		$avatarHtml = getAvatarHtml($id);
		$powerlevel = POWERLEVEL_DESCRIPTIONS[$user['powerlevel']];
		$rankHtml = getProfileRankHtml($id, $database);
		$title = $user['title'];
		$registrationTime = date(DEFAULT_DATE_FORMAT, $user['registration_time']);
		$lastLoginTime = date(DEFAULT_DATE_FORMAT, $user['last_login_time']);
		$numPosts = getNumPostsByUser($id, $database);
		$websiteHtml = ($user['website'] !== '') ? '<a href="' . $user['website'] . '">' . $user['website'] . '</a>' : '';
		$emailHtml = str_ireplace(['@', '.'], [' <i class="fa fa-at"></i> ', ' <i class="fa fa-circle"></i> '], $user['email']);
		$bio = nl2br($user['bio']);
		$signature = nl2br($user['signature']);

		$lastPost = getLastPost($id, $database);
		if ($lastPost === null)
		{
			$lastPostHtml = '<em>keiner</em>';
		}
		else
		{
			// TODO permission to see last post
			$page = getPostPageInThread($lastPost['id'], $lastPost['thread_id'], $database);
			$lastPostHtml = date(DEFAULT_DATE_FORMAT, $lastPost['post_time']) . ' in <a href="?p=thread&id='
				. $lastPost['thread_id'] . '&page=' . $page . '#post-' . $lastPost['id'] . '">' . $lastPost['thread_name'] . '</a>';
		}

		?>

		<h2>Nutzer: <?php echo $name; ?></h2>

		<form>
			<a class="pseudo button" href="?p=posts&user=<?php echo $id; ?>"><i class="fa fa-list"></i> Beiträge ansehen</a>
			<a class="pseudo button" href="?p=new-pm&user=<?php echo $id; ?>"><i class="fa fa-envelope"></i> Nachricht
				schreiben</a>
			<!-- TODO only for admins -->
			<a class="pseudo button" href="?p=admin&action=manage-user&id=<?php echo $id; ?>"><i class="fa fa-cog"></i>
				Nutzer administrieren</a>
		</form>

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

		<section class="user-section">
			<h3>Biografie</h3>
			<div class="content">
				<?php echo $bio; ?>
				<div class="signature"><?php echo $signature; ?></div>
			</div>
		</section>

		<section class="user-section">
			<h3>Medaillen (9)</h3>
			<div class="content medals">
				<h4>Meilensteine (5)</h4>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<h4>Event-Medaillen (4)</h4>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
				<div class="medal-showcase">
					<img src="http://smwhacking.de/forum/images/medals/special18.png" alt="Medaille"/>
					<div>
						<h5>Veteran</h5>
						<p>Seit drei Jahren im Forum aktiv</p>
						<p>verliehen am 31.12.1969 23:59:59</p>
					</div>
				</div>
			</div>
		</section>

		<?php
	} while (false);

