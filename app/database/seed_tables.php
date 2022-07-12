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
    seed_table_orderbook();
    seed_table_postavke();
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
		$st = $db->prepare('INSERT INTO burza_dionice(ime, ticker, izdano, zadnja_cijena, dividenda) VALUES (:ime, :ticker, :izdano, :zadnja_cijena, :dividenda)');
		
		/*$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://yfapi.net/v6/finance/quote?symbols=AAPL%2CMSFT%2CGOOG%2CAMZN%2CTSLA%2CJNJ%2CMETA%2CNVDA%2CXOM%2CPG",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"x-api-key: placeholder"
			],
		]);

		$response = json_decode(curl_exec($curl), true)['quoteResponse']['result'];
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err; return FALSE;}
		
		foreach($response as $i){
			$st->execute(array('ime' => $i['longName'], 'ticker' => $i['symbol'], 'izdano' => $i['sharesOutstanding'], 'zadnja_cijena' => $i['regularMarketPrice'], 'dividenda' => 100));
		} */

		$st->execute(array('ime' => 'APPLE', 'ticker' => 'AAPL', 'izdano' => '1000', 'zadnja_cijena' => '100', 'dividenda' => 100));
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
	$ds = new DioniceService();
	$us = new UserService();
	$sve_dionice = $ds->sveDionice();
	$svi_korisnici = $us->sviKorisnici();
	$broj_korisnika = count($svi_korisnici);

	if (!is_table_empty('burza_imovina')) {
		return;
	}

	$db = DB::getConnection();

	try {
		foreach ($sve_dionice as $dionica) {
			$kolicina = intval($dionica['izdano'] / $broj_korisnika);
			echo '<br/>';
			echo $kolicina;
			echo '<br/>';
			echo $dionica['izdano'];
			echo '<br/>';
			echo $broj_korisnika;
			echo '<br/>';

			foreach ($svi_korisnici as $korisnik) {
				$st = $db->prepare('INSERT INTO burza_imovina(id_user, id_dionica, kolicina) VALUES (:id_user, :id_dionica, :kolicina)');
				$st->execute(array('id_user' => $korisnik['id'], 'id_dionica' => $dionica['id'], 'kolicina' => round($kolicina/1000000)));
			}
		}
	} catch (PDOException $e) {
		exit("PDO error (seed_table_imovina): " . $e->getMessage());
	}
}

function seed_table_orderbook()
{
	if (!is_table_empty('burza_orderbook')) {
		return;
	}

	$db = DB::getConnection();

	try {

	} catch (PDOException $e) {
		exit("PDO error (seed_table_orderbook): " . $e->getMessage());
	}
}

function seed_table_postavke()
{
	if (!is_table_empty('burza_postavke')) {
		return;
	}

	$db = DB::getConnection();

	try {
		$st = $db->prepare('INSERT INTO burza_postavke(pocetni_kapital, kamata, komisija) VALUES (:pocetni_kapital, :kamata, :komisija)');
		$st->execute(array('pocetni_kapital' => 10000, 'kamata' => 1, 'komisija' => 1));
	} catch (PDOException $e) {
		exit("PDO error (seed_table_imovina): " . $e->getMessage());
	}
}
