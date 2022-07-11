<?php

require_once __DIR__ . '/../app/database/db.class.php';

class UserService
{
    public function sviKorisnici()
    {
        try {
            $db = DB::getConnection();
        } catch (PDOException $e) {
            exit('PDO error ' . $e->getMessage());
        }
        try {
            $st = $db->prepare('SELECT * FROM burza_users');
            $st->execute();
        } catch (PDOException $e) {
            exit('DB error (UserService.sviKorisnici): ' . $e->getMessage());
        }

        $users = array();
        while ($row = $st->fetch()) {
            array_push($users, $row);
        }
        return $users;
    }
}
