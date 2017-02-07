<div class="chat-bar">
	<div class="chat-message-container">
		<div class="chat-message">
			<div class="chat-sidebar">
				<img class="avatar" src="<?= $message['avatar_url'] ?>" />
			</div>
			<div class="chat-topbar">
				<a href="?p=user&id=<?= $message['author_id'] ?>" class="username"><?= $message['author_name'] ?></a>
				<span><?= $message['post_time'] ?></span>
			</div>
			<div class="chat-message-content">
				<?= $message['content'] ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>