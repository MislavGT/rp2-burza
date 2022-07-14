<?php

require_once __DIR__ . '/../app/database/db.class.php';

function sendJSONandExit( $message )
{
    header( 'Content-type:application/json;charset=utf-8' );
    echo json_encode( $message );
    flush();
    exit( 0 );
}

$db = DB::getConnection();

$id_user = (int)$_GET['id_korisnika'];

try {
    $st = $db->prepare('SELECT * FROM burza_orderbook WHERE id_user=:id');
    $st->execute( array('id' => $id_user));

} catch (PDOException $e) {
    exit("PDO error (ajax_orderbook): " . $e->getMessage());
}

sendJSONandExit( $st->fetchAll() );
?>