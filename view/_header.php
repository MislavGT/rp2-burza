<!DOCTYPE html>
<html>

<head>
	<meta charset="utf8">
	<title>PMFSE</title>
	<link rel="stylesheet" href="<?php echo __SITE_URL; ?>/css/style.css">
</head>

<body>
	<div class="header">
		<h1 class="title">PMFSE</h1>

		<?php
		if (isset($_SESSION['username'])) {
			require('logout_menu.php');
		}
		?>
	</div>

	<div class="maincontainer">

		<?php
		if (isset($_SESSION['username'])) {
			require('main_menu.php');
		}
		?>