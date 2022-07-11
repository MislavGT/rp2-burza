<?php

function sendJSONandExit( $message )
{
    header( 'Content-type:application/json;charset=utf-8' );
    echo json_encode( $message );
    flush();
    exit( 0 );
}

$db = DB::getConnection();

$id_dionica = $_GET['id'];

try {
    $st = $db->prepare('SELECT DISTINCT datum FROM burza_transakcije WHERE id_dionica=:id');
    $st->execute( 'id' => $id_dionica);

} catch (PDOException $e) {
    exit("PDO error (ajax): " . $e->getMessage());
}
$message = [];
$counter = 0;
foreach($st as $row) {
    $x = $row['datum'];
    try {
        $zt = $db->prepare('SELECT max(cijena) AS mx FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum');
        $zt->execute( 'id' => $id_dionica, 'datum' => $x);
        $row2 = $zt->fetch();
        $high = $row2['mx'];
        $zt = $db->prepare('SELECT min(cijena) AS mn FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum');
        $zt->execute( 'id' => $id_dionica, 'datum' => $x);
        $row2 = $zt->fetch();
        $low = $row2['mn'];
        $zt = $db->prepare('SELECT cijena AS o FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum ORDER BY datum ASC LIMIT 1');
        $zt->execute( 'id' => $id_dionica, 'datum' => $x);
        $row2 = $zt->fetch();
        $open = $row2['o'];
        $zt = $db->prepare('SELECT cijena AS c FROM burza_transakcije WHERE id_dionica=:id AND datum = :datum ORDER BY datum DESC LIMIT 1');
        $zt->execute( 'id' => $id_dionica, 'datum' => $x);
        $row2 = $zt->fetch();
        $open = $row2['c'];
    } catch (PDOException $e) {
        exit("PDO error (ajax): " . $e->getMessage());
    }
    $message[$counter] = ['open' => $open, 'close' => $close, 'low' => $low, 'high' => $high, 'date' = $x];
    $counter = $counter + 1;
}
sendJSONandExit( $message );
?>