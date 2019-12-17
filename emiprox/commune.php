<?php 




/*
Reproduit le comportement d'Emiprox, à la commune uniquement
https://cigale.atmosud.org/emiprox/commune.php?id_comm=84007

TODO: On pourrait utiliser l'API CIGALE si on y accédait !
        $url = "http://cigale.atmosud.org/scripts/tableau.php?query_ans=2016&query_entite=84007&query_entite_nom=AVIGNON%20(84)&query_sect=%271%27%2C%272%27%2C%273%27%2C%274%27%2C%275%27%2C%276%27%2C%277%27%2C%278%27&query_ener=&query_var=65%2C38%2C16&query_detail_comm=false";
        $result = file_get_contents($url);
        echo $result;

*/

// Récupération des paramètres
include("cfg/parametres.cfg.php");
include("cfg/parametres.postgresql.cfg.php");
include("fonctions.inc.php");

// Overwrite some
$sectens1 = array(
    "Energie", // "#DF7401"
    "Industrie/déchets", // "#ffbe33"
    "Résidentiel", // "#33afff"
    "Tertiaire", // "#2E2EFE"
    "Agriculture", // "#33ff9c"
    "Transports routiers", // "#ff3390"
    "Autres transports", // "#c733ff"
    "Non inclus" // "#848484"
);
// $couleurs_secten1 = array('b4ff5a', 'FF6600', 'FFFC98', 'FFCB04', '96D0FF', '1077B2', 'FF6600', 'FFFC98');

// Récupération du nom de la commune
$id_comm = $_GET['id_comm'];

// Si on est sur les arrondissements de Marseille, on reste à la commune
if ($id_comm >= 13201 and $id_comm <= 13206) {
    $id_comm = '13055';
};



// Dictionnaire des polluants à traiter
$polls = array(
    // "131" => array(
        // 'nom_abrege_polluant' => "conso",
        // 'html' => "Consommations",
        // 'nom' => "Consommations d'énergie finale",
        // 'unite' => "ktep"
    // ),
    "38" => array(
        'nom_abrege_polluant' => "nox",
        'html' => "NO<sub>x</sub>",
        'nom' => "Oxydes d'azote",
        'unite' => "t"
    ),
    "65" => array(
        'nom_abrege_polluant' => "pm10",
        'html' => "PM10",
        'nom' => "Particules inf&eacute;rieures &agrave; 10 &micro;m",
        'unite' => "t"
    ),
    
    "108" => array(
        'nom_abrege_polluant' => "pm2.5",
        'html' => "PM2.5",
        'nom' => "Particules inf&eacute;rieures &agrave; 2.5 &micro;m",
        'unite' => "t"
    ),
    "15" => array(
        'nom_abrege_polluant' => "co2",
        'html' => "CO<sub>2</sub>",
        'nom' => "Dioxyde de carbone",
        'unite' => "kt"
    ),
    "128" => array(
        'nom_abrege_polluant' => "prg100.3ges",
        'html' => "GES",
        'nom' => "Gaz &agrave; Effet de Serre",
        'unite' => "kt eq.CO2 "
    ),
    "11" => array(
        'nom_abrege_polluant' => "co",
        'html' => "CO",
        'nom' => "Monoxyde de carbone",
        'unite' => "t"
    ),
    "48" => array(
        'nom_abrege_polluant' => "so2",
        'html' => "SO<sub>2</sub>",
        'nom' => "Dioxyde de soufre",
        'unite' => "t"
    ),    
    "36" => array(
        'nom_abrege_polluant' => "nh3",
        'html' => "NH3",
        'nom' => "Amoniac",
        'unite' => "t"
    ),   
    "16" => array(
        'nom_abrege_polluant' => "covnm",
        'html' => "COVNM",
        'nom' => "Compos&eacute;s Organiques Volatils Non M&eacute;thaniques",
        'unite' => "t"
    )
);
$liste_polls = array_keys($polls);

// Connexion à PostgreSQL
$conn = pg_connect("dbname='" . $pg_bdd . "' user='" . $pg_lgn . "' password='" . $pg_pwd . "' host='" . $pg_host . "'");
if (!$conn) {
    echo "Not connected";
    exit;
}

// Get CIGALE SECTEN colors
$sql = "select distinct id_secten1, secten1_color from total.tpk_secten1_color order by id_secten1;";

$res = pg_query($conn, $sql);
if (!$res) {
    echo $sql;
    echo "\nErreur d'extraction des couleurs";
    exit;
}

$colors = array();
while ($row = pg_fetch_assoc( $res )) {
  $colors[] = $row;
} 

$couleurs_secten1 = array();
foreach($colors as $row) {
    array_push($couleurs_secten1, $row["secten1_color"]);
    // array_push($couleurs_secten1, '"'.$row["secten1_color"].'"');
};
// echo $couleurs_secten1[0];
// exit;

// echo "and id_polluant in (" . implode(",", $liste_polls) . ");";

// Extraction des émissions communales
$sql = "
SELECT 
    id_polluant, 
    nom_abrege_polluant,
    id_secten1,
    nom_secten1,
    id_unite,
    lib_unite,
    case 
        when id_polluant in (15,128) then val / 1000000.
        else val / 1000.
     end as val     
FROM (
    SELECT 
        case when id_polluant in (121,122) then 15 else id_polluant end as id_polluant, 
        id_secten1::text,
        id_unite,
        sum(val) as val
    FROM total.bilan_comm_v7_diffusion
    WHERE 
        id_comm = " . $id_comm . "
        -- and id_polluant in (" . implode(",", $liste_polls) . ")
        and ( id_polluant in (" . implode(",", $liste_polls) . ") or id_polluant in (121,122) )
        and an = " . $IE["annee"] . " 
        -- and (id_secten1::text, id_polluant) not in (('1', 131),('1', 15),('1', 128),('1', 123),('1', 124))  
		and ext_pcaet is true
        and hors_bilan is false 
        and id_comm not in (99138)        
    group by 
        case when id_polluant in (121,122) then 15 else id_polluant end , 
        id_secten1, 
        id_unite
) as a
left join transversal.tpk_secten1 as b using (id_secten1)
left join commun.tpk_polluants as c using (id_polluant)
left join commun.tpk_unite as d using (id_unite)
order by 
    id_polluant, 
    id_secten1, 
    id_unite
";

$res = pg_query($conn, $sql);
if (!$res) {
    // echo $sql;
    echo "\nErreur d'extraction des émissions communales";
    exit;
}

$emi_comm = array();
while ($row = pg_fetch_assoc( $res )) {
  $emi_comm[] = $row;
} 

// Extraction des émissions départementales
$sql = "
SELECT 
    id_polluant,  
    nom_abrege_polluant,
    id_secten1,
    nom_secten1,
    id_unite,
    lib_unite,
    case 
        when id_polluant in (15,128) then val / 1000000.
        else val / 1000.
     end as val    
FROM (
    SELECT 
        case when id_polluant in (121,122) then 15 else id_polluant end as id_polluant, 
        id_secten1::text,
        id_unite,
        sum(val) as val
    FROM total.bilan_comm_v7_diffusion
    WHERE 
        id_comm / 1000 = " . $id_comm . " / 1000
        -- and id_polluant in (" . implode(",", $liste_polls) . ")
        and ( id_polluant in (" . implode(",", $liste_polls) . ") or id_polluant in (121,122) )
        and an = " . $IE["annee"] . " 
		and ext_pcaet is true
        and hors_bilan is false 
        and id_comm not in (99138)            
    group by 
        case when id_polluant in (121,122) then 15 else id_polluant end,  
        id_secten1, 
        id_unite
) as a
left join transversal.tpk_secten1 as b using (id_secten1)
left join commun.tpk_polluants as c using (id_polluant)
left join commun.tpk_unite as d using (id_unite)
order by 
    id_polluant, 
    id_secten1, 
    id_unite
";

$res = pg_query($conn, $sql);
if (!$res) {
    // echo $sql;
    echo "\nErreur d'extraction des émissions départementales";
    exit;
}

$emi_dep = array();
while ($row = pg_fetch_assoc( $res )) {
  $emi_dep[] = $row;
} 

// Extraction des émissions régionales
$sql = "
SELECT 
    id_polluant,  
    nom_abrege_polluant,
    id_secten1,
    nom_secten1,
    id_unite,
    lib_unite,
    case 
        when id_polluant in (15,128) then val / 1000000.
        else val / 1000.
     end as val     
FROM (
    SELECT 
        case when id_polluant in (121,122) then 15 else id_polluant end as id_polluant, 
        id_secten1::text,
        id_unite,
        sum(val) as val
    FROM total.bilan_comm_v7_diffusion
    WHERE 
        -- and id_polluant in (" . implode(",", $liste_polls) . ")
        ( id_polluant in (" . implode(",", $liste_polls) . ") or id_polluant in (121,122) )
        and an = " . $IE["annee"] . "
		and ext_pcaet is true
        and hors_bilan is false 
        and id_comm not in (99138)            
    group by 
        case when id_polluant in (121,122) then 15 else id_polluant end, 
        id_secten1, 
        id_unite
) as a
left join transversal.tpk_secten1 as b using (id_secten1)
left join commun.tpk_polluants as c using (id_polluant)
left join commun.tpk_unite as d using (id_unite)
order by 
    id_polluant, 
    id_secten1, 
    id_unite
";

$res = pg_query($conn, $sql);
if (!$res) {
    // echo $sql;
    echo "\nErreur d'extraction des émissions régionales";
    exit;
}

$emi_reg = array();
while ($row = pg_fetch_assoc( $res )) {
  $emi_reg[] = $row;
} 

// Nom de la commune
$sql = "SELECT joli_nom_comm FROM commun.tpk_communes where lpad(id_comm::text, 5, '0') = '" . $id_comm . "'";

$res = pg_query($conn, $sql);
if (!$res) {
    echo $sql;
    echo "\nErreur d'extraction du nom de la commune";
    exit;
}

$nom_comm = array();
while ($row = pg_fetch_assoc( $res )) {
  $nom_comm[] = $row;
}
$nom_commune = $nom_comm[0]["joli_nom_comm"];

// echo $emi_reg[0]["id_polluant"];

// Ouverture de la page
include("debut.inc.php");

// Insertion nom comm
echo "        <!-- Title -->\n";
echo "        <div class=\"row\">\n";
echo "            <div class=\"col-lg-12\">\n";
echo "                <h3 class='emi_title'>".$nom_commune."</br>Emissions de polluants et Gaz à Effet de Serre ".$IE['annee']."</h3>\n";
echo "            </div>\n";
echo "        </div>\n";
echo "        <!-- /.row -->\n";


// Composition de la page
echo "        <!-- Page Features -->\n";
echo "        <div class=\"row text-center\">\n";

// Pour chaque polluant
foreach($liste_polls as $ipol => $pol) {
    
    $tpol = $polls[$pol];
    // echo $pol."</br>";
    // echo $polls[$pol]["nom_abrege_polluant"]."</br>";


    // Calcul de l'émission comm totale
    $emi_comm_tot = 0.0;
    foreach($emi_comm as $row) {
        // Si polluant identique
        if ($row["nom_abrege_polluant"] == $polls[$pol]["nom_abrege_polluant"]) {            
            $emi_comm_tot += $row["val"];
        };
    };
    if ($emi_comm_tot < 2) {
        $emi_comm_tot = round($emi_comm_tot, 1);
    } else {
        $emi_comm_tot = intval($emi_comm_tot);
    };
    
    
    // HTML
    $html_annee = $IE['annee'];
    echo "            <div class=\"col-md-6 col-sm-6 hero-feature\">\n";
    echo "                <div class=\"thumbnail\" style=\"text-align: center;\">\n";
    echo "                    <div class=\"caption\">\n";
    echo "                        <h3><span class=\"label label-default\">".$tpol['html']."</span></h3>\n";
    echo "                        <p><b>".$tpol['nom']."</b></p>\n";
    echo "                        <div class='emi_src'>Inventaire des &eacute;missions ".$html_annee.", ".$AASQA['nom']."</div>\n";
    echo "                    </div>\n";
    echo "                    <div id=\"chart_".$ipol."\" style=\"height: 300px; width: 90%;\"></div>\n";
    // echo "                    <h3 class='emi_quantite'>".number_format($sumkg2o[0], 0, ',', ' ')." ".$sumkg2o[1]."</h3>\n";
    // echo "                    <h3 class='emi_quantite'>".intval($emi_comm_tot)." ".$polls[$pol]["unite"]."</h3>\n";
    echo "                    <h3 class='emi_quantite'>".$emi_comm_tot." ".$polls[$pol]["unite"]."</h3>\n";

    
    // if ($with_pcdep || $with_pcreg) {
        // echo "                    <p>";
        // if ($with_pcdep) {
            // echo number_format($pcDep[$pol][$code_dep], $precisionDep, ',', ' ')." % du d&eacute;partement<br />";
        // }
        // if ($with_pcreg) {
            // echo number_format($pcReg[$pol], $precisionReg, ',', ' ')." % de la r&eacute;gion";
        // }
        // echo "</p>";
    // }
    
    echo "                </div>\n";
    echo "            </div>\n";
    
    
    

    $gvaleurs = array();
    // $glegend = array("1","toto","3","4","5","6","7","8");
    $gpourcent = array();    

    


    
    // Pour chaque secten
    for ($i=0; $i<8; $i++) {
        
        $not_exists = "false";
        
        // Pour chaque ligne
        foreach($emi_comm as $row) {
            // Si polluant identique
            if ($row["nom_abrege_polluant"] == $polls[$pol]["nom_abrege_polluant"]) {            
                // Si secten identique
                if ($row["id_secten1"] == $i+1) {
                    // On ajoute la valeur à l'array
                    // echo $i."</br>";
                    // echo $row["val"]."</br>";
                    // echo "??????</br>";
                    array_push($gvaleurs, $row["val"]);
                    array_push($gpourcent, intval($row["val"] / $emi_comm_tot * 100.));
                    $not_exists = "true";
                };
            };
        };
        
        // Si pas d'émission pour ce secten, alors 0
        if ($not_exists == "false") {
            array_push($gvaleurs, 0.0);
            array_push($gpourcent, 0.0);            
        };
    };

    // for ($i=0; $i<8; $i++) {
       // echo $gvaleurs[$i]."</br>";
       // echo $gpourcent[$i]."</br>";
       // echo $sectens1[$i]."</br>";
       // echo "...</br>";
    // };
    
// foreach($couleurs_secten1 as $color) {
    // array_push($couleurs_secten1, '"'.$row["secten1_color"].'"');
// };
// echo $couleurs_secten1[0];  
// for ($i=0; $i<8; $i++) {
    // echo $i . " - " . $couleurs_secten1[$i] . "</br>";
// } 
    
    ?>

            <script>

            var chart_<?php echo $ipol; ?> = new CanvasJS.Chart("chart_<?php echo $ipol; ?>",
                {
                    exportEnabled: false,
                    backgroundColor: "",
                    data: [
                        {
                            type: "pie",
                            fillOpacity: .7, 
                            startAngle: -90,
                            showInLegend: false,
                            toolTipContent: "{label}: <b>{y}%</b>",
                            indexLabel: "{shortname}",
                            indexLabelFontSize: 12,
                            dataPoints: [
                                <?php
                                for ($i=0; $i<8; $i++) {
                                    // echo "{ y: ".$gpourcent[$i].", color: '#".$couleurs_secten1[$i]."', label: \"".$glegend[$i]."\", shortname: \"".$sectens1[$i]."\" },\n";
                                    echo "{ y: ".$gpourcent[$i].", color: '".$couleurs_secten1[$i]."', label: \"".$sectens1[$i]."\", shortname: \"".$sectens1[$i]."\" },\n";
                                }
                                ?>
                            ]
                        }
                    ]
                });
            chart_<?php echo $ipol; ?>.render();

            </script>

    <?php

 
/*     // Information sur le polluant
    $tpol = $POLLUANTS[$pol];

    // Calcul des %dep et %reg
    if ($with_pcreg) {
        $pcReg[$pol] = $sum[$pol] / $reg[$pol] * 100;
        if ($pcReg[$pol] < 1) {
            $precisionReg = 2;
        }
        else {
            $precisionReg = 0;
        }
    }

    if ($with_pcdep) {
        $pcDep[$pol][$code_dep] = $sum[$pol] / $dep[$code_dep][$pol] * 100;
        if ($pcDep[$pol][$code_dep] < 1) {
            $precisionDep = 2;
        }
        else {
            $precisionDep = 0;
        }
    } */
};

// Fermeture de la page
echo "        </div>\n";
echo "        <!-- /.row -->";

// Déconnexion
// mysql_close();

// Fin de la page HTML
include("fin.inc.php");
?>




