<?php 

$pg_host = $_GET['pg_host'];
$pg_bdd = $_GET['pg_bdd'];
$pg_lgn = $_GET['pg_lgn']; 
$pg_pwd = $_GET['pg_pwd'];

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

$sql = "
select distinct siren_epci as geoid, nom_epci as geonm, 'EPCI' as geotyp
from cigale.epci
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result[] = $row;
} 

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
