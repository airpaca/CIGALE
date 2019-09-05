<?php 

/* Récupération des paramètres de connexion */
include '../config.php';

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

/* Export des années disponibles */
$sql = "select * from commun.tpk_ans order by an;";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$annees = array();
while ($row = pg_fetch_assoc( $res )) {
  $annees[] = $row;
} 

/* Export des entités géographiques disponibles */
// $sql = "
// -- Région PACA
// select 1 as order_field, 93 as valeur, 'Région PACA' as texte
// union all
// -- Départements
// select 2 as order_field, id_dep as valeur, joli_nom_dep as texte 
// from commun.tpk_depts
// where id_reg = 93
// union all
// -- EPCI
// select distinct 3 as order_field, siren_epci_2017, nom_epci_2017
// from commun.tpk_commune_2015_2016
// where siren_epci_2017 is not null
// union all
// -- Communes
// select order_field, id_comm, b.nom_comm || ' (' || lpad((id_comm / 1000)::text, 2, '0') || ')' as nom_comm
// from (
	// select distinct 4 as order_field, a.id_comm
	// from total.bilan_comm_v4_secten1 as a
// ) as a
// left join commun.tpk_communes as b using (id_comm)
// order by order_field, valeur
// ";
$sql = "
SELECT * FROM cigale.liste_entites_admin;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$entites_geo = array();
while ($row = pg_fetch_assoc( $res )) {
  $entites_geo[] = $row;
}

/* Extraction des secteurs d'activités */
$sql = "
select '''' || id_secten1 || '''' as id_secten1, nom_secten1
from transversal.tpk_secten1 
order by id_secten1
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$secteurs_act = array();
while ($row = pg_fetch_assoc( $res )) {
  $secteurs_act[] = $row;
}

/* Extraction des catégories d'énergie */
$sql = "
select distinct code_cat_energie, cat_energie
from transversal.tpk_energie 
where code_cat_energie is not null
order by code_cat_energie
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$cat_energie = array();
while ($row = pg_fetch_assoc( $res )) {
  $cat_energie[] = $row;
}

/* Extraction des grandes filières pour prod ener */
$sql = "
select id_grande_filiere_cigale, grande_filiere_cigale
from src_prod_energie.tpk_grande_filiere_cigale
order by id_grande_filiere_cigale
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$grandes_filieres = array();
while ($row = pg_fetch_assoc( $res )) {
  $grandes_filieres[] = $row;
}

/* Extraction des filières enr ou autres prod ener */
$sql = "
select id_detail_filiere_cigale, detail_filiere_cigale
from src_prod_energie.tpk_detail_filiere_cigale
order by id_detail_filiere_cigale
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$filieres = array();
while ($row = pg_fetch_assoc( $res )) {
  $filieres[] = $row;
}

/* Stockage des résultats */
$array_result = array(
    $annees,
    $entites_geo,
    $secteurs_act,
    $cat_energie,
    $grandes_filieres,
    $filieres
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
