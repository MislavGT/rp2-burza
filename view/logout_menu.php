<div class="logout">
	<div class="username">
		<?php echo render_username($_SESSION['id'], $_SESSION['username']); ?>
	</div>
	<a class="linkbutton" href="<?php echo __SITE_URL; ?>/burza.php?rt=login/logout">logout</a>
</div>
