<?php

require_once __DIR__ . '/database/db.class.php';
require_once __SITE_PATH . '/app/database/' . 'create_tables.php';
require_once __SITE_PATH . '/app/database/' . 'seed_tables.php';


class OperationResult
{
}

class OperationFailure extends OperationResult
{
	public string $error_message;

	public function __construct($error_message)
	{
		$this->error_message = $error_message;
	}

	public function success()
	{
		return false;
	}
}

class OperationSuccess extends OperationResult
{
	public function success()
	{
		return true;
	}
};

function drop_table($table_name)
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('DROP TABLE ' . $table_name);
		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (drop_table): " . $e->getMessage());
	}
}

function reset_database()
{
	drop_table('burza_users');
	drop_table('burza_privilegije');
	drop_table('burza_dionice');
	drop_table('burza_transakcije');
	drop_table('burza_kapital');
	drop_table('burza_imovina');
	drop_table('burza_orderbook');

	create_tables();
	seed_tables();
}

function is_table_empty($table_name)
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('SELECT count(*) FROM ' . $table_name);
		$st->execute();

		$r = intval($st->fetchColumn());

		return $r === 0;
	} catch (PDOException $e) {
		exit("PDO error (is_table_empty): " . $e->getMessage());
	}
}

function row_count($table_name)
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare('SELECT count(*) FROM ' . $table_name);
		$st->execute();
		return intval($st->fetchColumn());
	} catch (PDOException $e) {
		exit("PDO error (row_count): " . $e->getMessage());
	}
}

function ifeq($first, $second, $yes, $no)
{
	if (strcmp($first, $second) === 0) {
		return $yes;
	} else {
		return $no;
	}
}

function render_username($user_id, $username) {
	$capitalized = ucfirst($username);
	$admin_string = ' (admin)';
	$as = new AdminService();
	if ($as->is_admin($user_id)) {
		return $capitalized . $admin_string;
	} else {
		return $capitalized;
	}
}

function display_error($error_message)
{
	echo "<br />";
	echo '<p class="errormessage">' . $error_message . '</p>';
	echo "<br />";
}

function debug()
{
	echo "<br />";
	echo "<br />";
	echo "<hr />";
	echo "<br />";
	echo '$_POST:<br/>';
	echo "<br />";
	print_r($_POST);
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo '$_SESSION:<br/>';
	echo "<br />";
	print_r($_SESSION);
	echo "<br />";
	echo "<br />";
	echo "<br />";
	echo '$_GET:<br/>';
	echo "<br />";
	print_r($_GET);
	echo "<br />";
}

function redirectIfNotLoggedIn()
{
	if (!isset($_SESSION['username'])) {
		header('Location: ' . __SITE_URL . '/burza.php');
	}
}

function redirectIfNotAdmin()
{
	$as = new AdminService();
	if (!$as->is_admin($_SESSION['id'])) {
		header('Location: ' . __SITE_URL . '/burza.php');
	}
}
