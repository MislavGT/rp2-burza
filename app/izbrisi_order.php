<?php

require_once __DIR__ . '/../app/database/db.class.php';

$db = DB::getConnection();

$id_order = (int)$_GET['id'];

try {
    $st = $db->prepare('DELETE FROM burza_orderbook WHERE id=:id');
    $st->execute( array('id' => $id_order));
} catch (PDOException $e) {
    exit("PDO error (ajax_orderbook): " . $e->getMessage());
}
?>