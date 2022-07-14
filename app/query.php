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

$id_dionica = (int)$_GET['id_dionice'];

try {
    $st = $db->prepare('SELECT DISTINCT datum FROM burza_transakcije WHERE id_dionica=:id');
    $st->execute( array('id' => $id_dionica));

} catch (PDOException $e) {
    exit("PDO error (ajax_transakcije): " . $e->getMessage());
}
$message = [];
foreach($st as $row) {
    $x = $row['datum'];
    try {
        $zt = $db->prepare('SELECT max(cijena) AS mx FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum');
        $zt->execute( array('id' => $id_dionica, 'datum' => $x));
        $row2 = $zt->fetch();
        $high = $row2['mx'];
        $zt = $db->prepare('SELECT min(cijena) AS mn FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum');
        $zt->execute( array('id' => $id_dionica, 'datum' => $x));
        $row2 = $zt->fetch();
        $low = $row2['mn'];
        $zt = $db->prepare('SELECT cijena AS o FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum ORDER BY id ASC LIMIT 1');
        $zt->execute( array('id' => $id_dionica, 'datum' => $x));
        $row2 = $zt->fetch();
        $open = $row2['o'];
        $zt = $db->prepare('SELECT cijena AS c FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum ORDER BY id DESC LIMIT 1');
        $zt->execute( array('id' => $id_dionica, 'datum' => $x));
        $row2 = $zt->fetch();
        $close = $row2['c'];
    } catch (PDOException $e) {
        exit("PDO error (ajax): " . $e->getMessage());
    }
    $message[] = array('open' => $open, 'close' => $close, 'low' => $low, 'high' => $high, 'date' => $x);
}
sendJSONandExit( $message );
?>