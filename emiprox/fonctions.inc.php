<?php

// Fonction de convertion d'une quantité en kg en une autre.
function kg2o($q, $modelunite) {
    $unite = "kg";
    $unite_p10 = array("kt" => 9, "t" => 6, "kg" => 3, "g" => 0, "mg" => -3, "&micro;g" => -6, "ng" => -9, "pg" => -12, "fg" => -15);
    $p10_unite = array_flip($unite_p10);
    $facteur10 = 0;
	
    if ($q < 1) {
	while (($q < 1) && ($unite != "fg")) {
            $q = $q * 1000;
            $unite = $p10_unite[$unite_p10[$unite]-3];
            $facteur10 -= 3;
	}
    }
    elseif ($q > 2000) {
        while (($q > 2000) && ($unite != "kt")) {
            $q = $q / 1000;
            $unite = $p10_unite[$unite_p10[$unite]+3];
            $facteur10 += 3;
	}
    }

    $unite = str_replace("*", $unite, $modelunite);
    return array($q, $unite);
}


// Fonction pour convertir les balises <br> en saut de ligne
function br2nl($string) {
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

