<?php 

/* Récupération des paramètres de connexion */
include '../config.php';

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

$sql = "
-- select distinct siren_epci as geoid, nom_epci as geonm, 'EPCI' as geotyp from cigale.epci
select valeur as geoid, texte as geonm, 'EPCI' as geotyp from cigale.liste_entites_admin where order_field = 3 order by geonm;
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
