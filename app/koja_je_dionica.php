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

$id_dionica = (int)$_GET['id_dionica'];

try {
    $st = $db->prepare('SELECT ticker, zadnja_cijena FROM burza_dionice WHERE id=:id');
    $st->execute( array('id' => $id_dionica));

} catch (PDOException $e) {
    exit("PDO error (ajax_dionice): " . $e->getMessage());
}

sendJSONandExit( $st->fetch() );
?>