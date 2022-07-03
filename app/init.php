<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __SITE_PATH . '/app/' . 'controller_base.class.php';
require_once __SITE_PATH . '/app/' . 'registry.class.php';
require_once __SITE_PATH . '/app/' . 'router.class.php';
require_once __SITE_PATH . '/app/' . 'template.class.php';
require_once __SITE_PATH . '/app/database/' . 'db.class.php';
require_once __SITE_PATH . '/app/database/' . 'create_tables.php';
require_once __SITE_PATH . '/app/database/' . 'seed_tables.php';

spl_autoload_register(function ($class_name) {
	$filename = strtolower($class_name) . '.class.php';
	$file = __SITE_PATH . '/model/' . $filename;

	if (file_exists($file) === false) {
		return false;
	}

	require_once($file);
});

create_tables();
seed_tables();
