<div class="home-page-grid">

	<div class="welcome">
		<h2>Willkommen!</h2>
		<p><em>smwhacking.de</em> ist eine der wenigen deutschsprachigen Seiten übers SMW-Hacken. Es geht darum, das
			SNES-Spiel <em>Super&nbsp;Mario&nbsp;World</em> zu bearbeiten und eigene Level &mdash; oder gleich sein ganz
			eigenes Spiel &mdash; zu bauen.<br />
			Auf dieser Seite findest du Hacks, die andere Leute erstellt haben, Infos und Tutorials rund ums Hacken,
			und Ressourcen, die du in deinen eigenen Hacks benutzen kannst (Grafiken, Blöcke, Musik und einiges
			mehr). Wir haben auch ein <a href="?p=forum">Forum</a>, in dem du mitdiskutieren und Fragen zum Hacken
			stellen kannst!</p>
		<p>Ich bin <em>WYE</em> (oder auch <em>WhiteYoshiEgg</em>, oder auch <em>vvye</em>, oder auch
			<em>mebamme</em>). Im Jahr 2008 habe ich das Forum gegründet, etwas später kam dann diese Seite hier
			dazu. Danke auch an <em>Suyo</em>, der das damalige Backend programmiert hat, und an <em>RPG Hacker</em>,
			der sich um das Hosting kümmert!</p>
		<p>Wir sollten nicht verschweigen, dass die deutsche SMW-Hacking-Gemeinde ihre besten Zeiten hinter sich
			hat &mdash; das Forum ist nicht mehr besonders aktiv, und auf dieser Seite lädt fast niemand mehr neue
			Hacking-Materialien mehr hoch. Die zentrale Anlaufstelle für das SMW-Hacken ist nach wie vor die
			englischsprachige Seite <a href="http://www.smwcentral.net">SMW Central</a>, auf der seit 2006
			SMW-Hacker aus aller Welt aktiv sind. Dort werden fast alle Hacks, Ressourcen und Tutorials
			veröffentlicht, und davon wirst du sehr viel mehr finden als hier. smwhacking.de ist nur ein kleines
			Licht &mdash; wenn du deinen eigenen guten Hack bauen und bekannt machen willst, kommst du an SMW
			Central nicht vorbei.</p>
		<p>Trotzdem hoffen wir, dass du dich auf dieser Seite umschaust, ein paar nützliche Sachen findest &mdash;
			und auch dem Forum einen Besuch abstattest. :)<br />
			Viel Spaß beim Hacken!</p>
	</div>

	<div class="news">
		<h2>Neueste Ankündigungen</h2>

		<?php if ($numNews === 0): ?>
			<em>Es gibt noch keine Ankündigungen.</em>
		<?php endif; ?>

		<?php foreach ($news as $newsArticle): ?>
			<div class="news-article">
				<h3><?= $newsArticle['name'] ?></h3>
				<p class="news-subtitle">von <a
						href="?p=user&id=<?= $newsArticle['author'] ?>"><?= $newsArticle['author_name'] ?></a> am
					<?= date(DEFAULT_DATE_FORMAT, $newsArticle['creation_time']) ?> &bull;
					<strong><?= $newsArticle['replies'] ?></strong> <?= $newsArticle['replies'] == 1 ? 'Antwort' : 'Antworten' ?>
				</p>
				<p>
					<?php if ($newsArticle['author_has_avatar']): ?>
						<img class="avatar" src="img/avatars/<?= $newsArticle['author'] ?>.png" />
					<?php else: ?>
						<img class="avatar" src="img/avatars/default.png" />
					<?php endif; ?>
					<?= $newsArticle['content'] ?>
				</p>
				<p><a href="?p=thread&id=<?= $newsArticle['id'] ?>">&rarr; gesamten Beitrag lesen</a></p>
			</div>
		<?php endforeach; ?>

	</div>
</div>

