<?php

require_once __DIR__ . '/db.class.php';
require_once __DIR__ . '/../util.php';

function seed_tables()
{
	seed_table_users();
	seed_table_privilegije();
	seed_table_dionice();
	seed_table_transakcije();
	seed_table_kapital();
	seed_table_imovina();
}

function seed_table_users()
{
	if (!is_table_empty('burza_users')) {
		return;
	}

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO burza_users(username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :password, \'a@b.com\', \'abc\', \'1\')');

		$st->execute(array('username' => 'mirko', 'password' => password_hash('mirkovasifra', PASSWORD_DEFAULT)));
		$st->execute(array('username' => 'ana', 'password' => password_hash('aninasifra', PASSWORD_DEFAULT)));
		$st->execute(array('username' => 'maja', 'password' => password_hash('majinasifra', PASSWORD_DEFAULT)));
		$st->execute(array('username' => 'slavko', 'password' => password_hash('slavkovasifra', PASSWORD_DEFAULT)));
		$st->execute(array('username' => 'pero', 'password' => password_hash('perinasifra', PASSWORD_DEFAULT)));
	} catch (PDOException $e) {
		exit("PDO error (seed_table_users): " . $e->getMessage());
	}
}

function seed_table_privilegije()
{
	if (!is_table_empty('burza_privilegije')) {
		return;
	}

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO burza_privilegije(id_user, admin) 
			(SELECT id, 1 FROM burza_users WHERE burza_users.username=:username)');
		$st->execute(array('username' => 'mirko'));
	} catch (PDOException $e) {
		exit("PDO error (seed_table_privilegije): " . $e->getMessage());
	}
}

function seed_table_dionice()
{
	if (!is_table_empty('burza_dionice')) {
		return;
	}

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO burza_dionice(ime, ticker, izdano, zadnja_cijena) VALUES (:ime, :ticker, :izdano, :zadnja_cijena)');

		$st->execute(array('ime' => 'Meta Platforms', 'ticker' => 'META', 'izdano' => 100000, 'zadnja_cijena' => 500));
		$st->execute(array('ime' => 'Alphabet', 'ticker' => 'GOOG', 'izdano' => 200000, 'zadnja_cijena' => 500));
		$st->execute(array('ime' => 'Tesla', 'ticker' => 'TSLA', 'izdano' => 300000, 'zadnja_cijena' => 500));
		$st->execute(array('ime' => 'Palantir Technologies', 'ticker' => 'PLTR', 'izdano' => 400000, 'zadnja_cijena' => 500));
		$st->execute(array('ime' => 'Apple', 'ticker' => 'AAPL', 'izdano' => 500000, 'zadnja_cijena' => 500));
	} catch (PDOException $e) {
		exit("PDO error (seed_table_dionice): " . $e->getMessage());
	}
}

function seed_table_transakcije()
{
	if (!is_table_empty('burza_transakcije')) {
		return;
	}

	$db = DB::getConnection();

	try {
		// $st = $db->prepare('INSERT INTO burza_transakcije(id_dionice, kolicina, cijena, prodao, kupio) VALUES (:id_dionice, :kolicina, :cijena, :prodao, :kupio)');

		// $st->execute(array());
	} catch (PDOException $e) {
		exit("PDO error (seed_table_transakcije): " . $e->getMessage());
	}
}

function seed_table_kapital()
{
	if (!is_table_empty('burza_kapital')) {
		return;
	}

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO burza_kapital(id_user, kapital) SELECT id, 10000 FROM burza_users');
		$st->execute(array());
	} catch (PDOException $e) {
		exit("PDO error (seed_table_kapital): " . $e->getMessage());
	}
}

function seed_table_imovina()
{
	if (!is_table_empty('burza_imovina')) {
		return;
	}

	$db = DB::getConnection();

	try {
		// $st = $db->prepare('INSERT INTO burza_imovina(username, password_hash, email, registration_sequence, has_registered) VALUES (:username, :password, \'a@b.com\', \'abc\', \'1\')');

		// $st->execute(array('username' => 'mirko', 'password' => password_hash('mirkovasifra', PASSWORD_DEFAULT)));
		// $st->execute(array('username' => 'ana', 'password' => password_hash('aninasifra', PASSWORD_DEFAULT)));
		// $st->execute(array('username' => 'maja', 'password' => password_hash('majinasifra', PASSWORD_DEFAULT)));
		// $st->execute(array('username' => 'slavko', 'password' => password_hash('slavkovasifra', PASSWORD_DEFAULT)));
		// $st->execute(array('username' => 'pero', 'password' => password_hash('perinasifra', PASSWORD_DEFAULT)));
	} catch (PDOException $e) {
		exit("PDO error (seed_table_imovina): " . $e->getMessage());
	}
}
