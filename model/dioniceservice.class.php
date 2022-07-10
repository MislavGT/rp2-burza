<?php

require_once __DIR__ . '/../app/database/db.class.php';

class DioniceService
{
    public function sveDionice()
    {
        $db = DB::getConnection();

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
        $db = DB::getConnection();

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
		// Provjeri prvo jel postoje taj user i ta dionica
		
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM burza_users WHERE burza_users.id=:id' );
			$st->execute( array( 'id' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'kupiDionice :: User with the given id_user does not exist.' );


		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM burza_dionice WHERE burza_dionice.id=:id' );
			$st->execute( array( 'id' => $id_dionice ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'kupiDionice :: Dionica with the given id_dionice does not exist.' );
        
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO burza_orderbook(id_user, id_dionica, kolicina, cijena, tip) VALUES (:id_user, :id_dionica, :kolicina, :cijena, :tip)' );
			
			$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip' => 'buy' ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

    public function prodajDionice( $id_user, $id_dionice, $kolicina, $cijena ){
		// Provjeri prvo jel postoje taj user i ta dionica
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM burza_users WHERE burza_users.id=:id' );
			$st->execute( array( 'id' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'prodajDionice :: User with the given id_user does not exist.' );


		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM burza_dionice WHERE burza_dionice.id=:id' );
			$st->execute( array( 'id' => $id_dionice ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $st->rowCount() !== 1 )
			throw new Exception( 'prodajDionice :: Dionica with the given id_dionice does not exist.' );

	    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO burza_orderbook(id_user, id_dionica, kolicina, cijena, tip) VALUES (:id_user, :id_dionica, :kolicina, :cijena, :tip)' );
			$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip' => "sell" ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

    public function kupiProdajOdmah($id_user, $id_dionice, $kolicina, $cijena, $tip){
		if($tip==='buy') $tip2='sell';
		//ako prodaje provjerimo ima li dovoljno imovine za prodavati, ako ima, micemo mu imovinu iz burza_imovina
		else{
			$tip2='buy';
			 //provjeri ima li taj user te dionice u toj kolicini
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT * FROM burza_imovina WHERE burza_imovina.id_user=:id_user AND burza_imovina.id_dionica=:id_dionica AND burza_imovina.kolicina>=:kolicina' );
				$st->execute( array( 'id_user' => $id_user, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina ) );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    
			if( $st->rowCount() !== 1 ) throw new Exception( 'prodajDionice :: User nema te dionice u toj količini' );

			//ako ima i sve prodaje, brisemo mu tu imovinu
			$row = $st->fetch();
            $kolicina2=$row["kolicina"]-$kolicina;
			if($kolicina2===0){
				try
				{
					$db = DB::getConnection();
					$st = $db->prepare( 'DELETE FROM burza_imovina WHERE  burza_imovina.id_user=:id_user AND burza_imovina.id_dionica=:id_dionica;' );
					$st->execute( array('id_user'=>$id_user, 'id_dionica' => $id_dionice ) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			}
			else{
				try
				{
		    		$db = DB::getConnection();
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=:kolicina2 WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user ' );
					$st->execute( array( 'kolicina2'=>$kolicina2, 'id_dionica' => $id_dionice, 'id_user'=>$id_user) );
		    	}  
		        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

			}
		

		} 
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM burza_orderbook WHERE burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.kolicina=:kolicina AND burza_orderbook.cijena=:cijena AND burza_orderbook.tip=:tip LIMIT 1' );
			$st->execute( array( 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip'=>$tip2 ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
    
		if( $st->rowCount() === 1 ){
			$row = $st->fetch();
            $id_user2= $row["id_user"];
			//imamo match, brisemo stavku iz orderbooka
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare( 'DELETE FROM burza_orderbook WHERE  burza_orderbook.id_user=:id_user AND burza_orderbook.id_dionica=:id_dionica AND burza_orderbook.kolicina=:kolicina AND burza_orderbook.cijena=:cijena AND burza_orderbook.tip=:tip LIMIT 1;' );
				$st->execute( array('id_user'=>$id_user2, 'id_dionica' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'tip' => $tip2 ) );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
            
			//dodavanje transakcije
			if($tip==='buy'){
				$tm=$id_user;
				$id_user=$id_user2;
				$id_user2=$tm;
			}

			$datum=date("Y/m/d");
			try
			{
		    	$db = DB::getConnection();
				$st = $db->prepare( 'INSERT INTO burza_transakcije(id_dionice, kolicina, cijena, prodao, kupio, datum) VALUES ( :id_dionice, :kolicina, :cijena, :prodao, :kupio, :datum)' );
				$st->execute( array( 'id_dionice' => $id_dionice, 'kolicina' => $kolicina, 'cijena'=>$cijena, 'prodao'=>$id_user, 'kupio'=>$id_user2, 'datum'=>$datum ) );
		    }  
		    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

			//dodavanje imovine onome koji je kupio
			try
			{
		    	$db = DB::getConnection();
				$st = $db->prepare( 'SELECT * FROM burza_imovina WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user' );
				$st->execute( array( 'id_dionica' => $id_dionice, 'id_user'=>$id_user2) );
		    }  
		    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			
			//imamo li vec tog usera i te dionice u popisu imovina?
			//ako imamo mijenjamo vrijednost kolicine
			if( $st->rowCount() == 1 ){
				$row = $st->fetch();
                $kolicina2=$kolicina+$row["kolicina"];
				try
				{
		    		$db = DB::getConnection();
					$st = $db->prepare( 'UPDATE burza_imovina SET kolicina=:kolicina2 WHERE burza_imovina.id_dionica=:id_dionica AND burza_imovina.id_user=:id_user ' );
					$st->execute( array( 'kolicina2'=>$kolicina2, 'id_dionica' => $id_dionice, 'id_user'=>$id_user2) );
		    	}  
		        catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			}

			//ako nemamo moramo dodati
			if( $st->rowCount() == 0 ){
				try
				{
		    		$db = DB::getConnection();
					$st = $db->prepare( 'INSERT INTO burza_imovina(id_user, id_dionica, kolicina) VALUES ( :id_user, :id_dionica, :kolicina)' );
					$st->execute( array( 'id_user' => $id_user2, 'id_dionica'=>$id_dionice, 'kolicina' => $kolicina) );
		    	}  
		    	catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

			}
			
			
			return TRUE;
			
		} 

		return FALSE;
	}
}
