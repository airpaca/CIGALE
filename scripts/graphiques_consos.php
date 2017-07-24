<?php 

$pg_host = $_GET['pg_host'];
$pg_bdd = $_GET['pg_bdd'];
$pg_lgn = $_GET['pg_lgn']; 
$pg_pwd = $_GET['pg_pwd'];
$siren_epci = $_GET['siren_epci'];
$polluant = $_GET['polluant'];
$an = $_GET['an'];

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

// Export des consommations primaires
$sql = "
select b.nom_court_secten1, b.secten1_color, sum(val) as val
from (
	select id_comm, id_secten1, (sum(val))::integer as val 
	from total.bilan_comm_v4_secten1
	where 
        an = " . $an . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        and not (id_secten1 = '1' and code_cat_energie <> 8) -- On ne veut pas prendre le secteur de la production d'énergie sauf elec
	group by id_comm, id_secten1
) as a
left join total.tpk_secten1_color as b using (id_secten1)
left join commun.tpk_commune_2015_2016 as c using (id_comm)
left join cigale.epci as d on c.siren_epci_2017 = d.siren_epci
where 
	siren_epci = " . $siren_epci . " 
group by b.nom_court_secten1, b.secten1_color    
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_pie_primaire = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_primaire[] = $row;
} 

// Export des consommations finales
$sql = "
select b.nom_court_secten1, b.secten1_color, sum(val) as val
from (
	select id_comm, id_secten1, (sum(val))::integer as val 
	from total.bilan_comm_v4_secten1
	where 
        an = " . $an . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        -- and not (id_secten1 = '1' and code_cat_energie <> 8) -- On ne veut pas prendre le secteur de la production d'énergie sauf elec
	group by id_comm, id_secten1
) as a
left join total.tpk_secten1_color as b using (id_secten1)
left join commun.tpk_commune_2015_2016 as c using (id_comm)
left join cigale.epci as d on c.siren_epci_2017 = d.siren_epci
where 
	siren_epci = " . $siren_epci . " 
group by b.nom_court_secten1, b.secten1_color    
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_pie_finale = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_finale[] = $row;
} 

/* Lignes d'évolution par secteur - energie primaire */
$sql = "
select an, id_secten1, nom_court_secten1, secten1_color, (sum(val) / 1000.)::integer as val
from total.bilan_comm_v4_secten1 as a
left join total.tpk_secten1_color as b using (id_secten1)
where 
	id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
    and not (id_secten1 = '1' and code_cat_energie <> 8) -- On ne veut pas prendre le secteur de la production d'énergie sauf elec
	and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
group by an, id_secten1, nom_court_secten1, secten1_color
order by id_secten1, an
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_line_primaire = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_line_primaire[] = $row;
}

/* Part sur dep et reg (énergie primaire) */
$sql = "
select 
	epci::integer,  
    reg::integer, 
	round((epci / reg * 100.)::numeric, 1) as pct_reg 
from (
	select 
		-- Emissions de l'EPCI
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4_secten1
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
			and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
            and not (id_secten1 = '1' and code_cat_energie <> 8) -- On ne veut pas prendre le secteur de la production d'énergie sauf elec
			and an = " . $an . "
		) as epci,
		-- Emissions de la région
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4_secten1
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
            and not (id_secten1 = '1' and code_cat_energie <> 8) -- On ne veut pas prendre le secteur de la production d'énergie sauf elec
			and an = " . $an . "
		) as reg
) as a
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_part = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_part[] = $row;
}















// /* Export des données pour barchart */
// $sql = "
// select an, (sum(val) / 1000.)::integer as val
// from total.bilan_comm_v4_secten1
// where 
	// id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
	// and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
// group by an
// order by an
// ;
// ";

// $res = pg_query($conn, $sql);
// if (!$res) {
    // echo "An SQL error occured.\n";
    // exit;
// }

// $array_result_bar = array();
// while ($row = pg_fetch_assoc( $res )) {
  // $array_result_bar[] = $row;
// }

// /* Lignes d'évolution des secteurs*/
// $sql = "
// select an, id_secten1, nom_court_secten1, secten1_color, (sum(val) / 1000.)::integer as val
// from total.bilan_comm_v4_secten1 as a
// left join total.tpk_secten1_color as b using (id_secten1)
// where 
	// id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
	// and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
// group by an, id_secten1, nom_court_secten1, secten1_color
// order by id_secten1, an
// ;
// ";

// $res = pg_query($conn, $sql);
// if (!$res) {
    // echo "An SQL error occured.\n";
    // exit;
// }

// $array_result_line = array();
// while ($row = pg_fetch_assoc( $res )) {
  // $array_result_line[] = $row;
// }

// /* Part sur dep et reg */
// $sql = "
// select 
	// epci::integer,  
    // reg::integer, 
	// round((epci / reg * 100.)::numeric, 1) as pct_reg 
// from (
	// select 
		// -- Emissions de l'EPCI
		// (select (sum(val) / 1000.) as val
		// from total.bilan_comm_v4_secten1
		// where 
			// id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
			// and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
			// and an = " . $an . "
		// ) as epci,
		// -- Emissions de la région
		// (select (sum(val) / 1000.) as val
		// from total.bilan_comm_v4_secten1
		// where 
			// id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
			// and an = " . $an . "
		// ) as reg
// ) as a
// ";

// $res = pg_query($conn, $sql);
// if (!$res) {
    // echo "An SQL error occured.\n";
    // exit;
// }

// $array_result_part = array();
// while ($row = pg_fetch_assoc( $res )) {
  // $array_result_part[] = $row;
// }

/* Stockage des résultats */
$array_result = array(
    $array_result_pie_primaire,
    $array_result_pie_finale,
    $array_result_line_primaire,
    $array_result_part,
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
