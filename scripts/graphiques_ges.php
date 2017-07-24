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

/* Export des emissions directes (sans l'élec, avec le secteur de la prod d'énergie) */
$sql = "
select b.nom_court_secten1, b.secten1_color, sum(val) as val
from (
	select id_comm, id_secten1, (sum(val))::BIGINT as val 
	from total.bilan_comm_v4_secten1
	where 
        an = " . $an . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        and code_cat_energie <> 8 -- Emissions directes avec secteur prod energie mais pas l'élec
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

$array_result_pie_direct = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_direct[] = $row;
} 

/* Export des émissions indirectes sans le secteur de la prode d'énergie mais avec l'élec */
$sql = "
select b.nom_court_secten1, b.secten1_color, sum(val) as val
from (
	select id_comm, id_secten1, (sum(val))::BIGINT as val 
	from total.bilan_comm_v4_secten1
	where 
        an = " . $an . " 
        and id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
        and id_secten1 <> '1' -- Emissions indirectes sans secteur prod energie mais avec l'élec
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

$array_result_pie_indirect = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie_indirect[] = $row;
} 

/* Evo par secteur - émissions directes */
$sql = "
select an, id_secten1, nom_court_secten1, secten1_color, (sum(val) / 1000.)::integer as val
from total.bilan_comm_v4_secten1 as a
left join total.tpk_secten1_color as b using (id_secten1)
where 
	id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
    and code_cat_energie <> 8 -- Emissions directes donc sans l'élec
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
		from total.bilan_comm_v4_secten1
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
			and id_comm in (select distinct id_comm from commun.tpk_commune_2015_2016 where siren_epci_2017 = " . $siren_epci . ")
            and code_cat_energie <> 8 -- Emissions directes
			and an = " . $an . "
		) as epci,
		-- Emissions de la région
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4_secten1
		where 
			id_polluant in (select id_polluant from commun.tpk_polluants where nom_abrege_polluant = '" . $polluant . "')
            and code_cat_energie <> 8 -- emissions directes
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

/* Stockage des résultats */
$array_result = array(
    $array_result_pie_direct,
    $array_result_pie_indirect,
    $array_result_line_directes,
    $array_result_part,
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
