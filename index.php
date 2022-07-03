<?php
define('__SITE_PATH', realpath(dirname(__FILE__)));
define('__SITE_URL', dirname($_SERVER['PHP_SELF']));

header('Location: ' . __SITE_URL . '/burza.php');
