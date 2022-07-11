<?php

require_once __DIR__ . '/../app/database/db.class.php';

class AdminService
{
    public function is_admin($user_id)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT admin FROM burza_privilegije WHERE id_user=:id_user');
            $st->execute(array('id_user' => $user_id));
        } catch (PDOException $e) {
            exit('Greška u bazi (AdminService.is_admin): ' . $e->getMessage());
        }

        $row = $st->fetch();

        if ($row) {
            return $row[0] === '1';
        } else { // no privilege information
            return false;
        }
    }

    public function set_initial_capital($pocetni_kapital) {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('UPDATE burza_postavke SET pocetni_kapital=:pocetni_kapital WHERE 1=1');
            $st->execute(array('pocetni_kapital' => $pocetni_kapital));
        } catch (PDOException $e) {
            exit('Greška u bazi (AdminService.set_initial_capital): ' . $e->getMessage());
        }
    }

    public function get_initial_capital() {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT pocetni_kapital FROM burza_postavke');
            $st->execute(array());
        } catch (PDOException $e) {
            exit('Greška u bazi (AdminService.get_initial_capital): ' . $e->getMessage());
        }

        $row = $st->fetch();

        if ($row) {
            return intval($row[0]);
        } else { // no privilege information
            exit('burza_postavke nema postavljen iznos pocetnog kapitala');
        }
    }
};
