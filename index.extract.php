<!DOCTYPE html>
<html lang="en">
<head>

    <!-- 
    TODO: Uiliser le plugin datatables qui fait des supers exports pdf
    -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Extraction</title>
    
    <!-- JQuery 3.2.1 -->
    <script src="libs/jquery/jquery-3.2.1.min.js"></script>    
    
    <!-- Leaflet 3.2.1 -->
    <script src="libs/leaflet/leaflet_v1.0.3/leaflet.js"></script> 
    <link rel="stylesheet" href="libs/leaflet/leaflet_v1.0.3/leaflet.css"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
    <script src="libs/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap-select -->
    <link rel="stylesheet" href="libs/bootstrap-select-1.12.2/dist/css/bootstrap-select.min.css">
    <script src="libs/bootstrap-select-1.12.2/dist/js/bootstrap-select.min.js"></script>
    
    <!-- Leaflet Sidebar -->
    <script src="libs/leaflet-sidebar-master/src/L.Control.Sidebar.js"></script>
    <link rel="stylesheet" href="libs/leaflet-sidebar-master/src/L.Control.Sidebar.css"/>    

    <!-- Leaflet.Spin (including spin.js) -->
    <script src="libs/spin.js/spin.min.js"></script>
    <script src="libs/Leaflet.Spin-1.1.0/leaflet.spin.min.js"></script>

    <!-- Chart.js -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

    <!-- Selectize.js -->
    <script src="libs/selectize.js/selectize.js" type="text/javascript"></script>
    <link href="libs/selectize.js/selectize.css" rel="stylesheet" type="text/css"/>
    <link href="libs/selectize.js/selectize.bootstrap3.css" rel="stylesheet" type="text/css"/> 

    <!-- datatables -->
    <script type="text/javascript" src="libs/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 
    
    <!-- CSS -->
    <link rel="stylesheet" href="style.extract.css"/>

    <!-- Config -->
    <script type="text/javascript" src="config.js"></script>
    
</head>
    
<body>


<div id="container">

    <!-- Partie gauche - Sélection des données -->
    <div id="sidebar-left">
        <div class="list-group">
        
            <img class="img-titre" align="middle" src="img/logo-Air-PACA_small.png">
        
            <h5>Extraction</h5>
             
             
            <!-- Formulaire de sélection avec bootstrap-select --> 
             
            <p>Année(s) d'inventaire</p>
            <select class="selectpicker" id="select_ans" title="Années d'inventaire" multiple data-selected-text-format="count > 3" data-actions-box="true" data-width="100%"></select>

            <p>Emprise géographique</p>
            <select class="selectpicker" id="select_entites" title="Emprise géograpique" multiple data-max-options="1" data-live-search="true" data-width="100%"></select>
            
            <p>Détail communal</p>
            <select class="selectpicker" id="select_detail_comm" title="Détail par commune" data-max-options="1" data-width="100%">
                <option value="true">Oui</option>
                <option value="false">Non</option>
            </select>
       
            <p>Secteurs d'activités</p>
            <select class="selectpicker" id="select_secteurs" title="Secteurs d'activité" data-selected-text-format="count > 1" multiple data-actions-box="true" data-width="100%"></select>             

            <p>Energies</p>
            <select class="selectpicker" id="select_cat_ener" title="Combustibles" data-selected-text-format="count > 2" multiple data-actions-box="true" data-width="100%"></select>    

            <p>Consommations, Productions et Emissions</p>
            <select class="selectpicker" id="select_variable" title="Polluants et GES" data-selected-text-format="count > 2" multiple data-actions-box="true" data-width="100%"></select>   

        </div>

        <div class="Boutons_extractions">
        
        
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-success" onClick="afficher_donnees();">Exporter les données</button>
                <!--
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                 
                <ul class="dropdown-menu">
                    <li><a href="#">En CSV</a></li>
                    <li><a href="#">En PDF</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Au format base de données</a></li>
                </ul>
                 -->
            </div>
            
            
        </div>
        
    </div>
    
    <!-- Partie droite - Affichage des données -->    
    <div id="sidebar-right">

        
        <div class="header_extraction">
            Air PACA - Inventaire v4
        </div>



        <table id="tableau" class="display" width="100%" cellspacing="0">
            <thead>
                <tr>
                  <th>Année</th>
                  <th>Entité administrative</th>
                  <th><a target="_blank" href="https://www.citepa.org/fr/" data-toggle="tooltip" title="Nomenclature SECTEN niveau 1 du CITEPA (SECTteurs Economiques et éNergie)">Secten 1</a></th>
                  <th>Catégorie d'énergie</th>
                  <th><a data-toggle="tooltip" title="Consommations d'énergie finale">Consommation (tep)</a></th>
                  <th>Polluant</th>
                  <th>Valeur</th>
                  <th>Unité</th>
                </tr>
            </thead>
        </table>
    </div>
    
</div>

<script type="text/javascript">

/* Variables générales */

/* Fonctions */
function tests(){
    
    $.ajax({
        type: "GET",
        url: "scripts/tableau.php",
        dataType: 'json',   
        data: {
            pg_host:cfg_pg_host,
            pg_bdd:cfg_pg_bdd, 
            pg_lgn:cfg_pg_lgn, 
            pg_pwd:cfg_pg_pwd,  
        },         
        success: function(response,textStatus,jqXHR){
            
            console.log(response);
            console.log(JSON.stringify(response));
        },
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });
    
};

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    };
    return i;
};

function datehour() {
    var d = new Date();
    
    var year = d.getFullYear() ;
    var month = addZero(d.getMonth() + 1);
    var day =addZero(d.getDate());
    var hour = addZero(d.getHours());
    var minutes = addZero(d.getMinutes());
    var seconds = addZero(d.getSeconds());
    return year + "-" + month + "-" + day + " " + hour + ":" + minutes + ":" + seconds;
};

function fill_listes(){
    /*
    Une seule requête Ajax qui retourne dans une array toutes les valeurs 
    nécessaires pour remplir les listes de sélection.
    
    Maj des listes avec les réponses.
    */
    $.ajax({
        type: "GET",
        url: "scripts/fill_listes.php",
        dataType: 'json',   
        data: {
            pg_host:cfg_pg_host,
            pg_bdd:cfg_pg_bdd, 
            pg_lgn:cfg_pg_lgn, 
            pg_pwd:cfg_pg_pwd,  
        },         
        success: function(response,textStatus,jqXHR){
            
            // Remplissage de la liste des années
            for (ian in response[0]) {             
                $("#select_ans").append($('<option>', {value: response[0][ian].an, text: response[0][ian].an}, '</option>'));                               
            };
            $("#select_ans").selectpicker('refresh');

            // Remplissage de la liste des entités géographiques
            for (ient in response[1]) {             
                $("#select_entites").append($('<option>', {value: response[1][ient].valeur, text: response[1][ient].texte}, '</option>'));                               
            };
            $("#select_entites").selectpicker('refresh');            

            // Remplissage des secteurs d'activités
            for (isect in response[2]) {             
                $("#select_secteurs").append($('<option>', {value: response[2][isect].id_secten1, text: response[2][isect].nom_secten1}, '</option>'));                               
            };
            $("#select_secteurs").selectpicker('refresh');  
            
            // Remplissage des catégories d'énergie
            for (isect in response[3]) {             
                $("#select_cat_ener").append($('<option>', {value: response[3][isect].code_cat_energie, text: response[3][isect].cat_energie}, '</option>'));                               
            };
            $("#select_cat_ener").selectpicker('refresh');            

            // Remplissage des variables (fixe)
            variables_ener = [
                {val: 131, text: "Consommations d'énergie"},
                // {val: 999, text: "Productions d'énergie"},       
            ];

            variables_emi = [
                {val: 65, text: "PM10"},
                {val: 108, text: "PM2.5"},
                {val: 38, text: "NOx"},
                {val: 129, text: "COV"},
                {val: 48, text: "SO2"},
                {val: 36, text: "NH3"},
                {val: 999, text: "GES eq.CO2"},        
            ];
            
            // $("#select_variable").append($('<optgroup label="Energie">'));
            for (ivar in variables_ener) {                                          
                $("#select_variable").append($('<option>', {value: variables_ener[ivar].val, text: variables_ener[ivar].text}, '</option>'));                               
            };
            // $("#select_variable").append($('</optgroup>'));
            
            $("#select_variable").append($('<optgroup label="Emissions">'));
            for (ivar in variables_emi) {                                           
                $("#select_variable").append($('<option>', {value: variables_emi[ivar].val, text: variables_emi[ivar].text}, '</option>'));                               
            };
            $("#select_variable").append($('</optgroup>'));            
            
            $("#select_variable").selectpicker('refresh');  
            
            // FIXME: Les labels ne s'affichent pas! Tester d'ajouter d'abord les groupes 
            // Puis de mettre à jour ces groups?
            $("#selectpicker").selectpicker();
            $("#select_variable").selectpicker();    // FIXME: Les labels ne s'affichent pas!
            
            
        },
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });
};

function afficher_donnees(){
   
    // Vérification des variables du formulaire
    if ($('#select_ans').val().length == 0) {
        console.log("TODO: Erreure pas d'années définies");
    } else {
        query_ans = $('#select_ans').val().join();
    };
 
    if ($('#select_secteurs').val().length == 0) {
        query_sect = " ";
        console.log("TODO: Faut faire un group by!")
    } else {
        query_sect = " and id_secten1 in (" + $('#select_secteurs').val().join() + ")";
    };

    if ($('#select_cat_ener').val().length == 0) {
        console.log("TODO: Tous combustibles confondus");
    } else {
        query_ener = $('#select_cat_ener').val().join();
    };
    
    if ($('#select_variable').val().length == 0) {
        console.log("TODO: Erreure pas de polluant défini");
    } else {
        query_var = $('#select_variable').val().join();
    };    
    
    // Si un tableau existe déjà on le détruit avant de le recréer
    if (typeof the_table !== 'undefined') {
        the_table.destroy();
    };
    
    // Création du tableau  
    the_table = $('#tableau').DataTable({
        scrollY: '60vh',
        scrollCollapse: true,        
        paging: false,
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf'], 
        processing: true,
        serverSide: false,      
        ajax: {
            "url" : "scripts/tableau.php",
            "type": "GET",
            "data" : {
                "pg_host": cfg_pg_host,
                "pg_bdd": cfg_pg_bdd, 
                "pg_lgn": cfg_pg_lgn, 
                "pg_pwd": cfg_pg_pwd,
                "query_ans": query_ans,
                "query_sect": query_sect,
                "query_ener": query_ener,
                "query_var": query_var,
            },
        },
    });
  
    // Mise à jour de la date et de l'heure de l'extraction
    var extraction_time = datehour();
    $(".header_extraction").html('Air PACA - Inventaire v4 - Extraction du ' + extraction_time + '</br><a target="_blank" href="#">Conditions d\'utilisation et de diffusion</a>');
};




/* Au chargement */
$(document).ready(function(){
    /* Configuration des popups */
    $('[data-toggle="tooltip"]').tooltip();   
    
    /* Appel des fonctions */
    fill_listes();
    // tests();   

});





        
        






</script>

</body>
</html>