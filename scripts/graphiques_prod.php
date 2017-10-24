<?php 

/* Récupération des paramètres de connexion */
include '../config.php';

$siren_epci = $_GET['siren_epci'];
$polluant = $_GET['polluant'];
$an = $_GET['an'];

// echo $siren_epci;
// echo $polluant;
// echo $an;
// 200054807 prod 2015

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

// Export des productions primaire an_max par grande filière
$sql = "
select 
	a.lib_grande_filiere, 
	color_grande_filiere, 
	round(sum(val / 1000.)::numeric, 1) as val
from total.bilan_comm_v4_prod as a
left join src_prod_energie.tpk_grande_filiere as b using (id_grande_filiere)
where 
	an = " . $an . "
	and est_enr is true
    and siren_epci_2017 = '" . $siren_epci . "' 
group by a.lib_grande_filiere, color_grande_filiere
order by lib_grande_filiere  
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$prod_primaires_grandes_filieres = array();
while ($row = pg_fetch_assoc( $res )) {
  $prod_primaires_grandes_filieres[] = $row;
} 

/* quantité totale annuelle produite */
$sql = "
select 
	an, 
	round(sum(val / 1000.)::numeric, 1) as val
from total.bilan_comm_v4_prod as a
where siren_epci_2017 = '" . $siren_epci . "' 
group by an
order by an
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$quantites_totales_annuelles = array();
while ($row = pg_fetch_assoc( $res )) {
  $quantites_totales_annuelles[] = $row;
}

/* Evolution des productions primaire par grande filière */
$sql = "
select 
    an, 
	a.lib_grande_filiere, 
	color_grande_filiere, 
	round(sum(val / 1000.)::numeric, 1) as val
from total.bilan_comm_v4_prod as a
left join src_prod_energie.tpk_grande_filiere as b using (id_grande_filiere)
where 
	est_enr is true
    and siren_epci_2017 = '" . $siren_epci . "' 
group by an, a.lib_grande_filiere, color_grande_filiere
order by an, lib_grande_filiere  
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$evo_prod_primaires_grandes_filieres = array();
while ($row = pg_fetch_assoc( $res )) {
  $evo_prod_primaires_grandes_filieres[] = $row;
}

/* Histogramme production primaire/secondaire (évolution annuelle)  */
$sql = "
select 
    an, 
	case when est_enr is true then 'Primaire' else 'Secondaire' end as prod, 
	case when est_enr is true then '#33ff9c' else '#ff3390' end as prod_color, 
	round(sum(val / 1000.)::numeric, 1) as val
from total.bilan_comm_v4_prod as a
left join src_prod_energie.tpk_grande_filiere as b using (id_grande_filiere)
where 
    siren_epci_2017 = '" . $siren_epci . "' 
group by 
	an, 
	case when est_enr is true then 'Primaire' else 'Secondaire' end, 
	case when est_enr is true then '#33ff9c' else '#ff3390' end
order by an, prod;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$evo_primaire_secondaire = array();
while ($row = pg_fetch_assoc( $res )) {
  $evo_primaire_secondaire[] = $row;
}

/* Stockage des résultats */
$array_result = array(
    $prod_primaires_grandes_filieres,
    $quantites_totales_annuelles,
    $evo_prod_primaires_grandes_filieres,
    $evo_primaire_secondaire
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
