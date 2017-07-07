<?php 

/* Récupération des paramètres de connexion */
$pg_host = $_GET['pg_host'];
$pg_bdd = $_GET['pg_bdd'];
$pg_lgn = $_GET['pg_lgn']; 
$pg_pwd = $_GET['pg_pwd'];
$query_ans = $_GET['query_ans'];
$query_sect = $_GET['query_sect'];
$query_ener = $_GET['query_ener'];
$query_var = $_GET['query_var'];



/* Connexion à PostgreSQL */
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

/*  Ecriture du code SQL de la requête */
$sql = "
select an, id_comm, id_secten1, code_cat_energie, sum(val) as conso, id_polluant, sum(val) as val, id_unite
from total.bilan_comm_v4_secten1
where 
    an in (" . $query_ans . ")
    " . $query_sect . " --  and id_secten1 in (" . $query_sect . ")
    and code_cat_energie in (" . $query_ener . ")
	and id_polluant in (" . $query_var . ")
	and id_comm = 13060
group by an, id_comm, id_secten1, code_cat_energie, id_polluant, id_unite
order by an, id_comm, id_secten1, code_cat_energie, id_polluant, id_unite
;
";

// echo $sql;

/* Execution de la requête */
$rResult = pg_query($conn, $sql);
if (!$rResult) {
    echo "An SQL error occured.\n";
    exit;
}

/* Récupération des colonnes de la requête */
$aColumns = array('an', 'id_comm', 'id_secten1', 'code_cat_energie', 'conso', 'id_polluant', 'val', 'id_unite');

/* Préparation de l'header de l'output */
$output = array(
    "sEcho" => 10,
    "iTotalRecords" => 10,
    "iTotalDisplayRecords" => 10,
    "aaData" => array()
);

/* Insertion des données dans l'output */
while ( $aRow = pg_fetch_array($rResult, null, PGSQL_ASSOC) )
{
    $row = array();
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( $aColumns[$i] == "version" )
        {
            /* Special output formatting for 'version' column */
            $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
        }
        else if ( $aColumns[$i] != ' ' )
        {
            /* General output */
            $row[] = $aRow[ $aColumns[$i] ];
        }
    }
    $output['aaData'][] = $row;
};
     
/* Export en JSON */
header('Content-Type: application/json');
echo json_encode( $output );
?>
