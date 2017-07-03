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

/* Export des données pour piechart */
$sql = "
select grand_secteur, grand_secteur_color, (sum(val) / 1000.)::integer as val
from (
	select id_secteur, sum(val) as val
	from total.bilan_comm_v4
	where id_polluant = 38 and an = 2015
	group by id_secteur
) as a
left join total.tpk_secteur as b using (id_secteur)
group by grand_secteur, grand_secteur_color
order by grand_secteur
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_pie = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_pie[] = $row;
} 

/* Export des données pour barchart */
$sql = "
select an, (sum(val) / 1000.)::integer as val
from total.bilan_comm_v4
where id_polluant = 38
group by an
order by an
";

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
select grand_secteur, grand_secteur_color, an, sum(val) as val
from (
	select id_secteur, an, (sum(val) / 1000.)::integer as val
	from total.bilan_comm_v4
	where id_polluant = 38
	group by id_secteur, an
) as a
left join total.tpk_secteur as b using (id_secteur)
group by grand_secteur, grand_secteur_color, an
order by grand_secteur, an
";

$res = pg_query($conn, $sql);
if (!$res) {
    echo "An SQL error occured.\n";
    exit;
}

$array_result_line = array();
while ($row = pg_fetch_assoc( $res )) {
  $array_result_line[] = $row;
}

/* Part sur dep et reg */
$sql = "
select 
	epci::integer, dep::integer, reg::integer, 
	round((epci / dep * 100.)::numeric, 1) as pct_dep, 
	round((epci / reg * 100.)::numeric, 1) as pct_reg 
from (
	select 
		-- Emissions de l'EPCI
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4
		where id_polluant = 38 and id_comm = 13055 and an = 2015) as epci,
		-- Emissions du dep
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4
		where id_polluant = 38 and id_comm / 1000 = 13 and an = 2015) as dep,
		-- Emissions de la région
		(select (sum(val) / 1000.) as val
		from total.bilan_comm_v4
		where id_polluant = 38 and an = 2015) as reg
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
    $array_result_pie,
    $array_result_bar,
    $array_result_line,
    $array_result_part,
);

/* Export en JSON */
header('Content-Type: application/json');
echo json_encode($array_result);

?>
