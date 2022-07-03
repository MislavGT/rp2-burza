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
}
