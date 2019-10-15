<?php 

/* Récupération des paramètres de connexion */
include '../config.php';

$siren_epci = $_GET['siren_epci'];
$polluant = $_GET['polluant'];
$an = $_GET['an'];

if ($polluant == 'co2') {
    $polluant = "co2.bio', 'co2.nbio";
};

/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

/* Export des emissions indirectes par secteur */
$sql = "
select b.nom_secteur_pcaet, b.secteur_pcaet_color, sum(val) as val
from (
	select id_comm, id_secteur_pcaet, (sum(val) / 1000.)::BIGINT as val 
	from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) . " 
	where 
        an = " . $an . " 
        and id_epci = " . $siren_epci . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
        and id_secteur_pcaet <> '1' -- Finale Pas de prod énergétique mais élec et chaleur
        and ss is false -- Aucune donnée en Secret Stat
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
    echo "Erreur lors de l'export des émissions indirectes par secteur";
    exit;
}

$array_result_pie_direct = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_direct[] = $row;
} 

/* Export des emissions indirectes par cétégorie d'énergie */
$sql = "
select b.nom_court_cat_energie as nom_secteur_pcaet, b.cat_energie_color as secteur_pcaet_color, sum(val) as val
from (
	select id_comm, code_cat_energie, (sum(val) / 1000.)::BIGINT as val 
	from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) . "
	where 
        an = " . $an . " 
        and id_epci = " . $siren_epci . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
        and id_secteur_pcaet <> '1' -- Finale Pas de prod énergétique mais élec et chaleur
        and ss is false -- Aucune donnée en Secret Stat
	group by id_comm, code_cat_energie
) as a
left join total.tpk_cat_energie_color as b using (code_cat_energie)
-- left join commun.tpk_commune_2015_2016 as c using (id_comm)
-- left join (select distinct id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018 FROM commun.tpk_commune_2015_2016) as c on a.id_comm = c.id_comm_2018
-- left join cigale.epci as d on c.siren_epci_2018 = d.siren_epci
-- where siren_epci = " . $siren_epci . " 
group by b.nom_court_cat_energie, b.cat_energie_color
;
";

// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export des émissions indirectes par catégories d'énergie";
    exit;
}

$array_result_pie_indirect = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_indirect[] = $row;
} 

/* Evo par secteur - émissions directes */
$sql = "
select an, a.id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color, (sum(val) / 1000.)::integer as val
from total.bilan_comm_v" . $v_inv . "_diffusion as a -- " . str_replace(".", "", $polluant) . " as a
left join total.tpk_secteur_pcaet_color as b on a.id_secteur_pcaet = b.id_secteur_pcaet::integer
where 
	id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
    and a.id_secteur_pcaet <> '1' -- Finale Pas de prod énergétique mais élec et chaleur
	-- and id_comm in (select distinct id_comm_2018 from commun.tpk_commune_2015_2016 where siren_epci_2018 = " . $siren_epci . ")
    and id_epci = " . $siren_epci . " 
    and ss is false -- Aucune donnée en Secret Stat
group by an, a.id_secteur_pcaet, nom_secteur_pcaet, secteur_pcaet_color
order by id_secteur_pcaet, an
;
";

// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export de l'évolution des émissions";
    exit;
}

$array_result_line_directes = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_line_directes[] = $row;
}

/* Part sur dep et reg (émissions directes) */
$sql = "
select 
	epci::integer,  
    reg::integer, 
	round((epci / reg * 100.)::numeric, 1) as pct_reg 
from (
	select 
		-- Emissions de l'EPCI
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) . "
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
			-- and id_comm in (select distinct id_comm_2018 from commun.tpk_commune_2015_2016 where siren_epci_2018 = " . $siren_epci . ")
            and id_epci = " . $siren_epci . " 
            and id_secteur_pcaet <> '1' -- Finale Pas de prod énergétique mais élec et chaleur
			and an = " . $an . "
            and ss is false -- Aucune donnée en Secret Stat
		) as epci,
		-- Emissions de la région
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v" . $v_inv . "_diffusion -- " . str_replace(".", "", $polluant) . "
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
            and code_cat_energie not in (6, 8) -- emissions directes hors chaleur et froid
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


/* Emi totale an max */
$sql = "
select (sum(val) / 1000.)::BIGINT as val 
from total.bilan_comm_v" . $v_inv . "_diffusion as a -- " . str_replace(".", "", $polluant) . " as a
-- left join commun.tpk_commune_2015_2016 as c using (id_comm)
-- left join (select distinct id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018 FROM commun.tpk_commune_2015_2016) as c on a.id_comm = c.id_comm_2018
where 
    an = " . $an . " 
    -- and siren_epci_2018 = " . $siren_epci . " 
    and id_epci = " . $siren_epci . " 
    and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant in ('" . $polluant . "'))
    and id_secteur_pcaet <> '1' -- Finale Pas de prod énergétique mais élec et chaleur
    and ss_epci is false -- Aucune donnée en Secret Stat  
;
";

// echo nl2br($sql);

$res = pg_query($conn, $sql);
if (!$res) {
    echo "Erreur lors de l'export de l'émission totale";
    exit;
}

$emi_an_max = array();
while ($row = pg_fetch_assoc( $res )) {
  $emi_an_max[] = $row;
} 

/* Stockage des résultats */
$array_result = array(
    $array_result_pie_direct,
    $array_result_pie_indirect,
    $array_result_line_directes,
    $array_result_part,
    $emi_an_max,
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
