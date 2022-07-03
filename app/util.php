<?php

require_once __DIR__ . '/database/db.class.php';


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
