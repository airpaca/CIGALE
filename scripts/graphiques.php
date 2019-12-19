<?php 
/* Récupération des paramètres de connexion */
include '../config.php';

$siren_epci = $_GET['siren_epci'];
$polluant = $_GET['polluant'];
$an = $_GET['an'];

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

/* Export des données pour piechart */
$sql = "
select b.nom_secteur_pcaet, b.secteur_pcaet_color, sum(val) as val
from (
	select id_comm, id_secteur_pcaet, (sum(val) / 1000.)::integer as val 
	-- from total.bilan_comm_v" . $v_inv . "_secten1_" . str_replace(".", "", $polluant) ." 
	from total.bilan_comm_v" . $v_inv . "_diffusion --" . str_replace(".", "", $polluant) ." 
	where 
        an = " . $an . " 
        and id_epci = " . $siren_epci . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
        -- and ss is false -- Aucune donnée en Secret Stat 
        and ss_epci is false -- Aucune donnée en Secret Stat
	group by id_comm, id_secteur_pcaet
) as a
left join total.tpk_secteur_pcaet_color as b on a.id_secteur_pcaet = b.id_secteur_pcaet::integer
-- left join commun.tpk_commune_2015_2016 as c using (id_comm)
-- left join (select distinct id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018 FROM commun.tpk_commune_2015_2016) as c on a.id_comm = c.id_comm_2018
-- left join cigale.epci as d on c.siren_epci_2018 = d.siren_epci
-- where siren_epci = " . $siren_epci . " 
group by b.nom_secteur_pcaet, b.secteur_pcaet_color    
;
";
// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export des émissions piechart";
    exit;
}

$array_result_pie = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie[] = $row;
} 

/* Export des données pour barchart */
$sql = "
select an, (sum(val) / 1000.)::integer as val
-- from total.bilan_comm_v" . $v_inv . "_secten1_" . str_replace(".", "", $polluant) ." 
from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) ." 
where 
	id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
	-- and id_comm in (select distinct id_comm_2018 from commun.tpk_commune_2015_2016 where siren_epci_2018 = " . $siren_epci . ")
    and id_epci = " . $siren_epci . " 
    and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
    -- and ss is false -- Aucune donnée en Secret Stat 
    and ss_epci is false -- Aucune donnée en Secret Stat
    and an not in (2008,2009,2011)
group by an

-- Ajout des années non disponibles
union all 
select 2008::integer as an, null::integer as val
union all
select 2009::integer as an, null::integer as val
union all
select 2011::integer as an, null::integer as val

order by an
;
";

// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_bar = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_bar[] = $row;
}

/* Lignes d'évolution des secteurs*/
$sql = "
-- SANS LES VALEURS POUR LES ANNEES MANQUANTES
select an, a.id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, (sum(val) / 1000.)::integer as val
from total.bilan_comm_v" . $v_inv . "_diffusion as a -- " . str_replace(".", "", $polluant) ."  as a
left join total.tpk_secteur_pcaet_color as b on a.id_secteur_pcaet = b.id_secteur_pcaet::integer
where 
 	id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
    and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
	-- and id_comm in (select distinct id_comm_2018 from commun.tpk_commune_2015_2016 where siren_epci_2018 = " . $siren_epci . ")
    and id_epci = " . $siren_epci . " 
    -- and ss is false -- Aucune donnée en Secret Stat
    and ss_epci is false -- Aucune donnée en Secret Stat
group by an, a.id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color
order by id_secteur_pcaet, an
;
";

// echo nl2br($sql);

// $sql = "
// -- AVEC LES VALEURS POUR LES ANNEES MANQUANTES
// with emi as (
	// select an, id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, (sum(val) / 1000.)::integer as val
	// from total.bilan_comm_v4_secten1 as a
	// left join total.tpk_secteur_pcaet_color as b using (id_secteur_pcaet)
	// where 
		// id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        // and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
		// and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
	// group by an, id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color
	// order by id_secteur_pcaet, an
// ) 
// select * from emi
// union all
// select 2008::integer as an, id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, null::integer as val
// from emi 
// where an = 2007
// union all
// select 2009::integer as an, id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, null::integer as val
// from emi 
// where an = 2007
// union all
// select 2011::integer as an, id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, null::integer as val
// from emi 
// where an = 2007
// order by id_secteur_pcaet, an
// ;
// ";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export de l'évolution des émissions";
    exit;
}

$array_result_line = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_line[] = $row;
}

/* Part sur dep et reg */
$sql = "
select 
	epci::integer,  
    reg::integer, 
	round((epci / reg * 100.)::numeric, 1) as pct_reg 
from (
	select 
		-- Emissions de l'EPCI
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) ." 
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
            and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
			-- and id_comm in (select distinct id_comm_2018 from commun.tpk_commune_2015_2016 where siren_epci_2018 = " . $siren_epci . " )
            and id_epci = " . $siren_epci . " 
			and an = " . $an . "
            -- and ss is false -- Aucune donnée en Secret Stat
            and ss_epci is false -- Aucune donnée en Secret Stat
		) as epci,
		-- Emissions de la région
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) ." 
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
            and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
			and an = " . $an . "
            and ss is false -- Aucune donnée en Secret Stat
		) as reg
) as a
";

// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export de la part des émissions";
    exit;
}

$array_result_part = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_part[] = $row;
}

/* Légende des secten1 */
// $sql = "
// select 0 as val, nom_secteur_pcaet, secteur_pcaet_color
// from total.tpk_secteur_pcaet_color 
// order by id_secteur_pcaet
// ";

// $res = pg_query($conn, $sql);
// if (!$res) {
    // echo "An SQL error occured.\n";
    // exit;
// }

// $array_result_legend = array();
// while ($row = pg_fetch_assoc( $res )) {
  // $array_result_legend[] = $row;
// }

// Récupération des émissions totales pour an max 
$sql = "
select (sum(val) / 1000.)::integer as val 
from total.bilan_comm_v" . $v_inv . "_diffusion as a -- " . str_replace(".", "", $polluant) ."  as a
-- left join commun.tpk_commune_2015_2016 as c using (id_comm)
-- left join (select distinct id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018 FROM commun.tpk_commune_2015_2016) as c on a.id_comm = c.id_comm_2018
where 
    an = " . $an . " 
    -- and siren_epci_2018 = " . $siren_epci . "
    and id_epci = " . $siren_epci . "     
    and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
    and code_cat_energie not in ('8', '6') -- Approche cadasrale pas d'élec ni conso de chaleur
    and ss_epci is false -- Aucune donnée en Secret Stat 
;
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de la récupération de l'émission totale";
    exit;
}

$emi_an = array();
while ($row = pg_fetch_assoc( $res )) {
  $emi_an[] = $row;
} 


/* Stockage des résultats */
$array_result = array(
    $array_result_pie,
    $array_result_bar,
    $array_result_line,
    $array_result_part,
    $emi_an,
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
