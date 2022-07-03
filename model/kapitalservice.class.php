<?php

require_once __DIR__ . '/../app/database/db.class.php';

class KapitalService
{
    public function setCapitalToInitial($user_id)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO burza_kapital VALUES (:id_user, 10000)');
        $st->execute(array('id_user' => $user_id));
    }
};
