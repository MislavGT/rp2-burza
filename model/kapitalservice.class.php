<?php

require_once __DIR__ . '/../app/database/db.class.php';

class KapitalService
{
    public function setCapitalToInitial($user_id)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('INSERT INTO burza_kapital VALUES (:id_user, 10000)');
            $st->execute(array('id_user' => $user_id));
        } catch (PDOException $e) {
            exit('Greška u bazi (KapitalService.setCapitalToInitial): ' . $e->getMessage());
        }
    }

    public function neto_vrijednosti(){
        $neto=array();
        
        try {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT burza_users.id FROM burza_users');
            $st->execute(array());
        } catch (PDOException $e) {exit('Greška u bazi (KapitalService.neto_vrijednosti): ' . $e->getMessage());}
        //dohvaćamo sve id-ove i stavljamo da je njihova vrijednost 0
        foreach($st as $row){
            $id=$row['id'];
            $neto[$id]=0;
        }

        try {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT * FROM burza_kapital');
            $st->execute(array());
        } catch (PDOException $e) {exit('Greška u bazi (KapitalService.neto_vrijednosti): ' . $e->getMessage());}
        //dodavanje kapitala

        foreach($st as $row){
            $id=$row['id_user'];
            $neto[$id]+=$row['kapital'];
        }

        try {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT burza_imovina.id_user, burza_imovina.kolicina, burza_dionice.zadnja_cijena FROM burza_imovina, burza_dionice WHERE burza_imovina.id_dionica=burza_dionice.id');
            $st->execute(array());
        } catch (PDOException $e) {exit('Greška u bazi (KapitalService.neto_vrijednosti): ' . $e->getMessage());}
        //dodavanje vrijednosti u dionicama

        foreach($st as $row){
            $id=$row['id_user'];
            $neto[$id]+=$row['kolicina']*$row['zadnja_cijena'];
        }

        return $neto;

    }

    public function imena(){
        $imena=array();

        try {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT burza_users.id, burza_users.username FROM burza_users');
            $st->execute(array()); } catch (PDOException $e) {exit('Greška u bazi (KapitalService.imena): ' . $e->getMessage());}
        
        foreach($st as $row){
            $id=$row['id'];
            $imena[$id]=$row['username'];
        }
        return $imena;

    }

};
