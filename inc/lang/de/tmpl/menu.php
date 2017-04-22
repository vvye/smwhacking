<div id="main-menu">
	<input type="checkbox" class="menu-toggle" id="main-menu-toggle">
	<label for="main-menu-toggle" class="menu-toggle-label"><h2><span>☰</span> Menü</h2></label>
	<nav>
		<ul>
			<?php foreach ($menuItems as $item): ?>
				<?php if ($item['active']): ?>
					<li class="active">
				<?php elseif ($item['secret']): ?>
					<li class="secret-locked">
				<?php else: ?>
					<li>
				<?php endif; ?>
				<a href="<?= $item['link'] ?>"><?= $item['caption'] ?></a>
				</li>
			<?php endforeach ?>
		</ul>
	</nav>
</div>