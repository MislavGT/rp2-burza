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
            exit('Greška u bazi (AdminService.isAdmin): ' . $e->getMessage());
        }

        $row = $st->fetch();

        if ($row) {
            return $row[0] === '1';
        } else { // no privilege information
            return false;
        }
    }
};
