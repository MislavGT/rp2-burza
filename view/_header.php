<!DOCTYPE html>
<html>

<head>
	<meta charset="utf8">
	<title>TeamUp</title>
	<link rel="stylesheet" href="<?php echo __SITE_URL; ?>/css/style.css">
</head>

<body>
	<div class="header">
		<h1 class="title">TeamUp</h1>

		<?php
		if (isset($username)) {
			require('logout_menu.php');
		}
		?>
	</div>

	<div class="maincontainer">

	<?php
	if (isset($username)) {
		require('main_menu.php');
	}
	?>