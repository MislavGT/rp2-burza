<?php

require_once __DIR__ . '/../app/database/db.class.php';

class DioniceService
{
    public function sveDionice()
    {
		try
		{
		$db = DB::getConnection();
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        try {
            $st = $db->prepare('SELECT * FROM burza_dionice');
            $st->execute();
        } catch (PDOException $e) {
            exit('DB error (DioniceService.sveDionice): ' . $e->getMessage());
        }

        $dionice = array();
        while ($row = $st->fetch()) {
            array_push($dionice, $row);
        }
        return $dionice;
    }

    public function jednaDionica($id_dionice) {
		try
		{
		$db = DB::getConnection();
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
        try {
            $st = $db->prepare('SELECT *
                FROM burza_dionice
                WHERE burza_dionice.id=:id_dionice');
            $st->execute(array('id_dionice' => $id_dionice));
        } catch (PDOException $e) {
            exit('DB error (DioniceService.jednaDionica): ' . $e->getMessage());
        }

        return $st->fetch();
    }

    public function kupiDionice( $id_user, $id_dionice, $kolicina, $cijena ){
		// Provjeri prvo postoje li taj user i ta dionica
		try
		{
		$db = DB::getConnection();
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		try
		{
			$st = $db->prepare( 'SELECT * FROM burza_users WHERE burza_users.id=:id' );
			$st->execute( array( 'id' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiDionice):'  . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'kupiDionice :: User with the given id_user does not exist.' );


		try
		{

			$st = $db->prepare( 'SELECT * FROM burza_dionice WHERE burza_dionice.id=:id' );
			$st->execute( array( 'id' => $id_dionice ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiDionice):' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'kupiDionice :: Dionica with the given id_dionice does not exist.' );
		$datum=date('Y-m-d H:i:s');
		try
		{

			$st = $db->prepare( 'INSERT INTO burza_orderbook(id_user, id_dionica, kolicina, cijena, tip, datum) VALUES (:id_user, :id_dionica, :kolicina, :cijena, :tip, :datum)' );
			
			$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip'=>'buy', 'datum'=>$datum ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiDionice):' . $e->getMessage() ); }
	}

    public function prodajDionice( $id_user, $id_dionice, $kolicina, $cijena ){
		try
		{
		$db = DB::getConnection();
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		// Provjeri prvo postoje li taj user i ta dionica
		try
		{

			$st = $db->prepare( 'SELECT * FROM burza_users WHERE burza_users.id=:id' );
			$st->execute( array( 'id' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.prodajDionice):' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'prodajDionice :: User with the given id_user does not exist.' );


		try
		{

			$st = $db->prepare( 'SELECT * FROM burza_dionice WHERE burza_dionice.id=:id' );
			$st->execute( array( 'id' => $id_dionice ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.prodajDionice):' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'prodajDionice :: Dionica with the given id_dionice does not exist.' );
		$datum = date('Y-m-d H:i:s');
	    try
		{

			$st = $db->prepare( 'INSERT INTO burza_orderbook(id_user, id_dionica, kolicina, cijena, tip, datum) VALUES (:id_user, :id_dionica, :kolicina, :cijena, :tip, :datum)' );
			$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip' => "sell", 'datum'=>$datum ) );
		}
		catch( PDOException $e ) { exit( 'DB error (DioniceService.prodajDionice):' . $e->getMessage() ); }
	}

    public function kupiProdajOdmah($id_user, $id_dionice, $kolicina, $cijena, $tip){
		try
		{
		$db = DB::getConnection();
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		if($tip==='buy') {
			$tip2='sell';
			$potrebno = $cijena * $kolicina;
			try
			{
				$st = $db->prepare( 'SELECT * FROM burza_kapital WHERE burza_kapital.id_user=:id_user AND burza_kapital.kapital>=:potrebno' );
				$st->execute( array( 'id_user' => $id_user, 'potrebno' => $potrebno ) );
			}
			catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }

			if( $st->rowCount() !== 1 ) throw new Exception( 'prodajDionice :: User nema dovoljno kapitala' );

			try
			{
				$st = $db->prepare( 'SELECT * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user != :id_user AND burza_orderbook.cijena<=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena ASC, datum ASC LIMIT 1' );
				$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $id_user, 'cijena'=>$cijena, 'tip'=>$tip2 ) );
			}
			catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }

			if($st->rowCount() !== 1) return $this->kupiDionice($id_user, $id_dionice, $kolicina, $cijena);

			$row = $st->fetch();

			if($kolicina > $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$za_kupiti = $kolicina - $row['kolicina']; // koliko je jos ostalo za kasnije
				$za_platiti = $row['kolicina'] * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'DELETE * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.cijena<=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena ASC, datum ASC LIMIT 1' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], 'cijena'=>$cijena, 'tip'=>$tip2 ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $id_user, 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'DELETE * FROM burza_imovina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $row['kolicina'], 'cijena'=>$row['cijena'], 'prodao'=>$row['id_user'], 'kupio'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return $this->kupiProdajOdmah($id_user, $id_dionice, $za_kupiti, $cijena, $tip);
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
			elseif($kolicina < $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$visak = $row['kolicina'] - $kolicina; // koliko je jos ostalo za kasnije
				$za_platiti = $kolicina * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'UPDATE burza_orderbook SET kolicina=:visak WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.datum =:datum' );
					$st->execute( array( 'visak' => $visak, 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], 'datum' => $row['datum'] ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $id_user, 'kolicina' => $kolicina ) );
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=kolicina-:kolicina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], "kolicina" => $kolicina ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$row['cijena'], 'prodao'=>$row['id_user'], 'kupio'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return TRUE;
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
			elseif($kolicina === $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$za_platiti = $row['kolicina'] * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'DELETE * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.cijena<=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena ASC, datum ASC LIMIT 1' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], 'cijena'=>$cijena, 'tip'=>$tip2 ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $id_user, 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'DELETE * FROM burza_imovina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $row['kolicina'], 'cijena'=>$row['cijena'], 'prodao'=>$row['id_user'], 'kupio'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return TRUE;
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
		}
		else{
			$tip2='buy';
			 //provjeri ima li taj user te dionice u toj kolicini
			try
			{
				$st = $db->prepare( 'SELECT * FROM burza_imovina WHERE burza_imovina.id_user=:id_user AND burza_imovina.id_dionica=:id_dionica AND burza_imovina.kolicina>=:kolicina' );
				$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina ) );
			}
			catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
    
			if( $st->rowCount() !== 1 ) throw new Exception( 'prodajDionice :: User nema te dionice u toj količini' );
			try
			{
				$st = $db->prepare( 'SELECT * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user != :id_user AND burza_orderbook.cijena>=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena DESC, datum ASC LIMIT 1' );
				$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $id_user, 'cijena'=>$cijena, 'tip'=>$tip2 ) );
			}
			catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }

			if($st->rowCount() !== 1) return $this->prodajDionice($id_user, $id_dionice, $kolicina, $cijena);

			$row = $st->fetch();

			if($kolicina > $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$za_prodati = $kolicina - $row['kolicina']; // koliko je jos ostalo za kasnije
				$za_platiti = $row['kolicina'] * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'DELETE * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.cijena>=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena DESC, datum ASC LIMIT 1' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], 'cijena'=>$cijena, 'tip'=>$tip2 ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $row['id_user'], 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=kolicina-:kolicina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $id_user, 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $row['kolicina'], 'cijena'=>$row['cijena'], 'kupio'=>$row['id_user'], 'prodao'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return $this->kupiProdajOdmah($id_user, $id_dionice, $za_prodati, $cijena, $tip);
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
			elseif($kolicina < $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$visak = $row['kolicina'] - $kolicina; // koliko je jos ostalo za kasnije
				$za_platiti = $kolicina * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'UPDATE burza_orderbook SET kolicina=:visak WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.datum =:datum' );
					$st->execute( array( 'visak' => $visak, 'id_dionica' => $id_dionice, 'id_user' =>$row['id_user'], 'datum' => $row['datum'] ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $row['id_user'], 'kolicina' => $kolicina ) );
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=kolicina-:kolicina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $id_user, "kolicina" => $kolicina ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$row['cijena'], 'kupio'=>$row['id_user'], 'prodao'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return TRUE;
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
			elseif($kolicina === $row['kolicina']){
				$datum = date('Y-m-d H:i:s');
				$za_platiti = $row['kolicina'] * $row['cijena']; // koliko sada treba platiti
				try
				{
					$st = $db->prepare( 'DELETE * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.id_user=:id_user AND burza_orderbook.cijena>=:cijena AND burza_orderbook.tip=:tip ORDER BY burza_orderbook.cijena DESC, datum ASC LIMIT 1' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $row['id_user'], 'cijena'=>$cijena, 'tip'=>$tip2 ) );
					$st = $db->prepare( 'INSERT INTO burza_imovina (id_user, id_dionica, kolicina) VALUES(:id_user, :id_dionica, :kolicina) ON DUPLICATE KEY UPDATE burza_imovina.kolicina=burza_imovina.kolicina+:kolicina');
					$st->execute( array( 'id_dionica' => $id_dionice, id_user => $row['id_user'], 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=kolicina-:kolicina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'id_user' => $id_user, 'kolicina' => $row['kolicina'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital-:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $row['id_user'] ) );
					$st = $db->prepare( 'UPDATE burza_kapital SET kapital=kapital+:za_platiti WHERE id_user = :id_user' );
					$st->execute( array( 'za_platiti' => $za_platiti, 'id_user' => $id_user ) );
					$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionica, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
					$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $row['kolicina'], 'cijena'=>$row['cijena'], 'kupio'=>$row['id_user'], 'prodao'=>$id_user, 'datum'=>$datum ) );
					$st = $db->prepare( 'UPDATE burza_dionice SET zadnja_cijena=:cijena where id=:id_dionica' );
					$st->execute( array( 'id_dionica' => $id_dionice, 'cijena' >= $row['cijena'] ) );
					return TRUE;
				}
				catch( PDOException $e ) { exit( 'DB error (DioniceService.kupiProdajOdmah):' . $e->getMessage() ); }
			}
		}
			
	}
}
