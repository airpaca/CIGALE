<?php 

/* Récupération des paramètres de connexion */
$pg_host = $_GET['pg_host'];
$pg_bdd = $_GET['pg_bdd'];
$pg_lgn = $_GET['pg_lgn']; 
$pg_pwd = $_GET['pg_pwd'];
$query_ans = $_GET['query_ans'];
$query_entite = $_GET['query_entite'];
$query_sect = $_GET['query_sect'];
$query_ener = $_GET['query_ener'];
$query_var = $_GET['query_var'];
$query_detail_comm = $_GET['query_detail_comm'];

/*  Ecriture du code SQL de la requête */

// Group by 
$group_by = " GROUP BY an, lib_unite, nom_abrege_polluant";
if ($query_detail_comm == "true") {
    $group_by =  $group_by . ", nom_entite";
    $nom_entite = " nom_comm";
} else {
    $nom_entite = " 'TODO: Nom entite'";
};
if ($query_sect != "") {
    $group_by =  $group_by . ", nom_secten1";
    $nom_secten1 = "nom_secten1";
} else {
    $nom_secten1 = "'Tous secteurs' as nom_secten1";
};
if ($query_ener != "") {
    $group_by =  $group_by . ", cat_energie";
    $cat_energie = "cat_energie";
} else {
    $cat_energie = "'Toutes énergies' as cat_energie";
};
// echo $group_by;

// Where
$where = "WHERE ";
$where =  $where . " an in (" . $query_ans . ")";
$where =  $where . " and id_polluant in (" . $query_var . ")";
if ($query_sect != "") {
    $where =  $where . " and id_secten1 in (" . $query_sect . ")";
};
if ($query_ener != "") {
    $where =  $where . " and code_cat_energie in (" . $query_ener . ")";
};
if ($query_entite == "93") {
    $where =  $where;
} elseif (
    $query_entite == "4" || 
    $query_entite == "5" || 
    $query_entite == "6" || 
    $query_entite == "13" || 
    $query_entite == "83" || 
    $query_entite == "84" 
) {
    $where =  $where . " and id_comm / 1000 in (" . $query_entite . ")";
} else {
    $where =  $where . " and id_comm in (" . $query_entite . ")";
};
// echo $where;


// SQL
$sql = "
select 
    an, 
    " . $nom_entite . "  as nom_entite,  
    " . $nom_secten1 . " ,  
    " . $cat_energie . " , 
    round(sum(val)::numeric, 1) as conso, 
    -- case when id_polluant = 131 then -999 else round(sum(val)::numeric, 1) end as conso, 
    nom_abrege_polluant, 
    round(sum(val)::numeric, 1) as val, 
    lib_unite
from total.bilan_comm_v4_secten1 as a
left join commun.tpk_communes as b using (id_comm)
left join transversal.tpk_secten1 as c using (id_secten1)
left join transversal.tpk_energie as d using (code_cat_energie)
left join commun.tpk_polluants as e using (id_polluant)
left join commun.tpk_unite as f using (id_unite)
" . $where . " 
" . $group_by . "
order by an, nom_entite, nom_secten1, cat_energie, nom_abrege_polluant, lib_unite
;
";

// echo $sql;

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

/* Execution de la requête */
$rResult = pg_query($conn, $sql);
if (!$rResult) {
    echo "An SQL error occured.\n";
    exit;
}

/* Récupération des résultats */
$output = array();
while ($row = pg_fetch_assoc( $rResult )) {
  $output[] = $row;
} 
     
/* Export en JSON */
header('Content-Type: application/json');
echo json_encode( $output );
?>