<?php

require_once __DIR__ . '/db.class.php';


function create_tables()
{
	create_table_users();
	create_table_privilegije();
	create_table_dionice();
	create_table_transakcije();
	create_table_kapital();
	create_table_imovina();
}

function create_table_users()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_users (' .
				'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'username varchar(50) NOT NULL,' .
				'password_hash varchar(255) NOT NULL,' .
				'email varchar(50) NOT NULL,' .
				'registration_sequence varchar(20) NOT NULL,' .
				'has_registered int)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_users): " . $e->getMessage());
	}
}

function create_table_privilegije()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_privilegije (' .
				'id_user int NOT NULL PRIMARY KEY,' .
				'admin bool)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_privilegije): " . $e->getMessage());
	}
}

function create_table_dionice()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_dionice (' .
				'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'ime varchar(50) NOT NULL,' .
				'ticker varchar(4) NOT NULL,' .
				'izdano int)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_dionice): " . $e->getMessage());
	}
}

function create_table_transakcije()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_transakcije (' .
				'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
				'id_dionice int NOT NULL,' .
				'kolicina int NOT NULL,' .
				'cijena int NOT NULL,' .
				'prodao int NOT NULL,' .
				'kupio int NOT NULL)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_transakcije): " . $e->getMessage());
	}
}

function create_table_kapital()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_kapital (' .
				'id_user int NOT NULL PRIMARY KEY,' .
				'kapital int NOT NULL)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_kapital): " . $e->getMessage());
	}
}

function create_table_imovina()
{
	$db = DB::getConnection();

	try {
		$st = $db->prepare(
			'CREATE TABLE IF NOT EXISTS burza_imovina (' .
				'id_user int NOT NULL,' .
				'id_dionica int NOT NULL,' .
				'kolicina int NOT NULL)'
		);

		$st->execute();
	} catch (PDOException $e) {
		exit("PDO error (create_table_imovina): " . $e->getMessage());
	}
}
