<h2>Das SMW Hacking-Sündenhaus</h2>
<h3>Porno-Tauschbörse</h3>

<div id="secrets-list">
	<ul>
		<?php foreach ($secrets as $secret): ?>
			<?php if (!$secret['is_link']): ?>
				<li><a href="?p=secret&id=<?= $secret['id']; ?>"><?= $secret['name']; ?></a></li>
			<?php else: ?>
				<li><a href="<?= $secret['content']; ?>"><?= $secret['name']; ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
