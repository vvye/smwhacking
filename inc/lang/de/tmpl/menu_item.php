<?php if ($active): ?>
<li class="active">
<?php else: ?>
	<?php if ($secret): ?>
<li class="secret-locked">
	<?php else: ?>
<li>
	<?php endif; ?>
<?php endif; ?>
	<a href="<?= $link ?>"><?= $caption ?></a>
</li>