<div class="footer">

	<p>2016-<?= date('Y') ?> WhiteYoshiEgg/WYE.</p>

	<p>
		<?php if ($numOnlineUsers === 0): ?>
			<em>Momentan sind keine registrierten Nutzer online.</em>
		<?php else: ?>
			<?= $numOnlineUsers ?> Nutzer online:
			<?php foreach ($onlineUsers as $i => $user): ?>
				<a class="username" href="?p=user&id=<?= $user['id'] ?>"><?= $user['name'] ?></a><?= ($i
					!== $numOnlineUsers
					- 1) ? ', ' : '' ?>
			<?php endforeach ?>
		<?php endif ?>
	</p>

</div>