<section class="user-section">
	<h3>Biografie</h3>
	<div class="content">
		<?php if ($bio !== ''): ?>
			<?php echo $bio; ?>
		<?php else: ?>
			<em>Dieser Nutzer hat keine Biografie geschrieben.</em>
		<?php endif; ?>
		<?php if ($signature !== ''): ?>
			<div class="signature"><?php echo $signature; ?></div>
		<?php endif; ?>
	</div>
</section>