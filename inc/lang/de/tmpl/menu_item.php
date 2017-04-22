<?php if ($active): ?>
<li class="active">
	<?php elseif ($secret): ?>
<li class="secret-locked">
	<?php else: ?>
<li>
	<?php endif; ?>
	<a href="<?= $link ?>"><?= $caption ?></a>
</li>