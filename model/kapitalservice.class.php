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
};
