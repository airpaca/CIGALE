<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Consultation d’Inventaires Géolocalisés de qualité de l’Air et de L’Energie - Extractions">
    <meta name="author" content="Air PACA">    
    
    <title>Air PACA - Extraction CIGALE</title> <!-- <title>CIGALE - Extraction</title> -->
    
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
    
    <!-- Leaflet.Spin (including spin.js) -->
    <script src="libs/spin.js/spin.min.js"></script>
    <script src="libs/Leaflet.Spin-1.1.0/leaflet.spin.min.js"></script>

    <!-- datatables -->
    <script type="text/javascript" src="libs/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 
    
    <!-- CSS -->
    <link rel="stylesheet" href="extraction.css"/>

    <!-- Config -->
    <script type="text/javascript" src="config.js"></script>
    
</head>
    
<body>


    <!-- Corps de la page -->
    <div class="row">
        
        <!-- Zone gauche de sélection et navigation -->
        <div class="col-md-3" id="zone-select">
            
            <!-- Titre de la page -->
            <img class="img-title" src="img/csv-icon.png" border="0" width="140">  <!-- orig: width="180" -->
            <h3 class="centered">Extraction</h3>        
        
            <!-- Formulaire de sélection -->
            <div class="hide" id="formulaire">

                Année(s) d'inventaire *
                <select class="selectpicker" id="select_ans" title="Années d'inventaire" mobile multiple data-selected-text-format="count > 3" data-actions-box="true" data-width="100%"></select>
                
                Emprise géographique *
                <select class="selectpicker" id="select_entites" title="Emprise géograpique" mobile multiple data-max-options="1" data-live-search="true" data-width="100%"></select>
                
                Détail communal *
                <select class="selectpicker" id="select_detail_comm" title="Détail par commune" mobile data-max-options="1" data-width="100%">
                    <option value="true">Oui</option>
                    <option value="false">Non</option>
                </select>
                
                Consommations, Productions et Emissions *
                <select class="selectpicker" id="select_variable" title="Consommations, Productions et Emissions" mobile data-selected-text-format="count > 2" multiple data-actions-box="true" data-width="100%"></select>   
                
                <div id="label_activites">Secteurs d'activités</div>
                <select class="selectpicker" id="select_secteurs" title="Tous secteurs d'activités confondus" mobile data-selected-text-format="count > 1" multiple data-actions-box="true" data-width="100%"></select>             

                <div id="label_energies">Energies</div>
                <select class="selectpicker" id="select_cat_ener" title="Toutes énergies confondues" mobile data-selected-text-format="count > 2" multiple data-actions-box="true" data-width="100%"></select>    

                <button type="button" class="btn btn-success" id="btn_extraction" onClick="afficher_donnees();">Exporter les données</button>                
                
            </div> 

            <!-- Navigation dans les menus -->
            <div class="row">
                <div class="col-xs-4">
                    <a href="index.php"><img class="img-menus" id="img-home" src="img/flat-blue-home-icon-4.png" border="0" width="80"></img></a>
                </div>				
                <div class="col-xs-4">
                    <a href="visualisation.php"><img class="img-menus" id="img-visu" src="img/cartography2.png" border="0" width="80"></img></a>      
                </div>
                <div class="col-xs-4">
                    <a href="methodo.php"><img class="img-menus" id="img-methodo" src="img/document-flat.png" border="0" width="80"></img></a>
                </div>			
            </div>
        
        
        </div>
        
        <!-- Zone droite de consultation des donées-->
        <div class="col-md-9" id="zone-display">

            <div class="header_extraction"></div>        

            <img class="img-aide-export" src="img/export_aide.png">
            <table id="tableau" class="display" width="100%" cellspacing="0"></table>
        
        </div>
        
    </div>    
      
        
    <!-- Modal pour avertissement sur le nombre de lignes à afficher -->
    <div id="confirm" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    
                    <div class=texte_modal>
                            <strong>Attention</strong></br>La sélection demandée comporte un grand nombre de lignes. Leur affichage pourrait faire ralentir votre navigateur.    
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default" data-dismiss="modal" onClick="create_table(response_global)">Continuer</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Revenir à la sélection</button>
                    </div>                

            </div>
        </div>
    </div>         
        
     























     
        
        
        
        
        
        
        
    

<script type="text/javascript">

/* Variables générales */
var spinner_right = new Spinner({opacity: 0.25, width: 3, color: "#6E6E6E", speed: 1.5, scale: 3, top: "250%",}); // top:"50%", left:"60%",
var spinner_right_element = document.getElementById('zone-display');

var spinner_left = new Spinner({opacity: 0.25, width: 3, color: "#6E6E6E", speed: 1.5, scale: 3, top: "150%",}); 
var spinner_left_element = document.getElementById('zone-select');

var response_global = null;

var listes = {
    activites: "",
    grandes_filieres: "",
    energies: "",
    filieres: "",    
};

var cgu = 'Conditions d\'utilisation: \n\n \
Les données fournies dans le cadre de l\'application CIGALE peuvent librement être diffusées, publiées ou utilisées dans le cadre de travaux, d\'études ou d\'analyse avec les conditions suivantes: \n\n \
- Toute utilisation des données brutes issues de la base de données CIGALE devra faire référence à l\'Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) et à Air PACA en ces termes : \'Source: Base de donnes CIGALE - Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) Provence-Alpes-Côte d\'Azur / inventaire Air PACA\' \n\n \
- Toute utilisation de données retravaillées par l\'utilisateur final à partir de données brutes issues de l\'application CIGALE devra faire référence à l\'Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) et à Air PACA en ces termes : \'Source: *Utilisateur final* d\'après la base de données CIGALE - Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) Provence-Alpes-Côte d\'Azur / inventaire Air PACA\' \n\n \
- Sur demande, l\'Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) Provence-Alpes-Côte d\'Azur et Air PACA mettent à disposition les méthodes d\'exploitation des données mises en œuvre. \n\n \
- Les données contenues dans ce document restent la propriété de l\'Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) Provence-Alpes-Côte dAzur et d\'Air PACA. \n\n \
- L\'Observatoire Régional de l\'Energie, du Climat et de l\'Air (ORECA) Provence-Alpes-Côte dAzur et Air PACA peuvent rediffuser ce document à d\'autres destinataires. \
'; 

/* Navigation entre les menus */
$("#img-methodo").hover(function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".png", ".hover.png");
    });
}, function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".hover.png", ".png");
    });
});

$("#img-visu").hover(function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".png", ".hover.png");
    });
}, function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".hover.png", ".png");
    });
});

$("#img-home").hover(function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".png", ".hover.png");
    });
}, function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".hover.png", ".png");
    });
});

/* Fonctions */
function tests(){
    
    $.ajax({
        type: "GET",
        url: "scripts/tableau.php",
        dataType: 'json',   
        // data: {
            // pg_host:cfg_pg_host,
            // pg_bdd:cfg_pg_bdd, 
            // pg_lgn:cfg_pg_lgn, 
            // pg_pwd:cfg_pg_pwd,  
        // },         
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
    
    // Déclanchement du sablier (spinner)
    spinner_left.spin(spinner_left_element);
    
    $.ajax({
        type: "GET",
        url: "scripts/fill_listes.php",
        dataType: 'json',   
        beforeSend:function(jqXHR, settings){
            jqXHR.spinner_left = spinner_left;
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

            // Enregistrement de certaines listes dans des variables globales pour les ré-utiliser
            listes["activites"] = response[2];
            listes["grandes_filieres"] = response[4]; 
            listes["energies"] = response[3]; 
            listes["filieres"] = response[5];            

            // Remplissage des variables (fixe)
            variables_ener = [
                {val: 131, text: "Consommations finales d'énergies"},   
            ];

            variables_prod = [
                {val: 999, text: "Productions d'énergie"},       
            ];            
            
            variables_emi = [
                {val: 65, text: "PM10"},
                {val: 108, text: "PM2.5"},
                {val: 38, text: "NOx"},
                {val: 16, text: "COVNM"},
                {val: 48, text: "SO2"},
                {val: 36, text: "NH3"},
                {val: 11, text: "CO"},
                {val: 15, text: "CO2 total"},  
                {val: 123, text: "CH4 eq.CO2"}, 
                {val: 124, text: "N2O eq.CO2"}, 
                {val: 128, text: "PRG 100"},                 
            ];            

            $("#select_variable").append($('<optgroup label="Energie">'));
            for (ivar in variables_ener) {                                          
                $("#select_variable").append($('<option>', {value: variables_ener[ivar].val, text: variables_ener[ivar].text}, '</option>'));                               
            };
            $("#select_variable").append($('</optgroup>'));
            
            $("#select_variable").append($('<optgroup label="Prod">'));
            for (ivar in variables_prod) {                                          
                $("#select_variable").append($('<option>', {value: variables_prod[ivar].val, text: variables_prod[ivar].text}, '</option>'));                               
            };
            $("#select_variable").append($('</optgroup>'));    
            
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
            
            // Sélection des valeurs par défaut
            $('#select_ans').selectpicker('val', '2015');
            $("#select_entites").selectpicker('val', '93');   
            $("#select_entites").selectpicker('val', '93'); 
            $("#select_detail_comm").selectpicker('val', 'false'); 
            $("#select_variable").selectpicker('val', '131');
 
            // Arrêt du sablier (spinner)
            jqXHR.spinner_left.stop();
            
            // Affichage du formulaire de sélection
            $("#formulaire").removeClass("hide"); 

            // Si on arrive sur cette page à partir de la visualisation d'une commune on remplit le formulaire
            // avec les bonnes infos et on lance l'extraction           
            if (sessionStorage.id_comm != null) {                
                $("#select_entites").selectpicker('val', sessionStorage.id_comm);
                $("#select_variable").selectpicker('val', sessionStorage.id_polluant);
                $("#select_secteurs").selectpicker('selectAll');
                $("#select_cat_ener").selectpicker('selectAll');
                
                // Si on est sur les productions, alors on change les listes activités et énergies
                if (sessionStorage.id_polluant == 999) {
                    changer_listes_prod(true);
                };
                                
                afficher_donnees(); 
                
                sessionStorage.clear(); // Suppression des éventuelles variables de session
            };
            
        },
        
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
            
            // Arrêt du sablier (spinner)
            jqXHR.spinner_left.stop();
        },        
    });
};

function afficher_donnees(){ 
   
    // Si aucune année sélectionnée alors on ne peut pas envoyer
    if ($('#select_ans').val().length == 0) {
        console.log("TODO: Highlight lists not working");
        // $('#select_ans').selectpicker('setStyle', 'btn-warning');
        // $('.btn #select_ans').css('color', 'red');
        // $('#select_ans').selectpicker('setStyle', 'color:red');
        // $('#select_ans').selectpicker.css({'color': 'red'});
        return null;
    } else {
        query_ans = $('#select_ans').val().join();
    };
    
    // Emprise géographique
    if ($('#select_entites').val().length == 0) {
        query_entite = "";
    } else {
        query_entite = $('#select_entites').val().join();
        query_entite_nom = $('#select_entites').find("option:selected").text();
    };        

    // Détail communal
    if ($('#select_detail_comm').val().length == 0) {
        $('#select_detail_comm').selectpicker('setStyle', 'btn-warning');
        return null;        
    } else {
        query_detail_comm = $('#select_detail_comm').val();
    };    
 
    // Secteurs
    if ($('#select_secteurs').val().length == 0) {
        query_sect = "";
    } else {
        query_sect = $('#select_secteurs').val().join();
    };
    
    // Cétégories d'énergie
    if ($('#select_cat_ener').val().length == 0) {
        query_ener = "";
    } else {
        query_ener = $('#select_cat_ener').val().join();
    };
    
    // Polluant
    if ($('#select_variable').val().length == 0) {
        $('#select_variable').selectpicker('setStyle', 'btn-warning');
        return null; 
    } else {
        query_var = $('#select_variable').val().join();
    };    
    
    // Si un tableau existe déjà on le détruit avant de le recréer
    if (typeof the_table !== 'undefined') {
        the_table.destroy(false);
        $('#tableau').empty();
    };
    
    // TODO: Si tout est ok, il faut remettre les styles par défauts aux listes
    // $('#select_ans').selectpicker('setStyle', 'btn');
    // $('#select_ans').selectpicker('setStyle', 'btn-danger');
    // $('#select_ans').selectpicker('setStyle', 'btn-success');
    // $('#select_ans').selectpicker('setStyle', 'btn-primary');
    // $('#select_ans').selectpicker('setStyle', 'btn-waning', 'remove');
    // $('#select_ans').selectpicker('refresh');
    // $('#select_ans').selectpicker('setStyle', 'btn-success', 'remove');
    // $('#select_ans').selectpicker('setStyle', 'btn-success')
    // $('#select_ans').selectpicker('refresh');
    
    // $('#select_ans').selectpicker();
    // $('#select_ans').selectpicker('setStyle', 'btn-primary', 'add'); // add class    
    
    
    
    // console.log("TODO: Lists styles reset");
   
   
    // Déclanchement du sablier (spinner)
    spinner_right.spin(spinner_right_element);
   
    // Suppression de l'image d'aide 
    $( ".img-aide-export" ).hide();
   
    // Création du tableau
    $.ajax({
        type: "GET",
        url: "scripts/tableau.php",
        dataType: 'json',   
        data: {
            "query_ans": query_ans,
            "query_entite": query_entite,
            "query_entite_nom": query_entite_nom,
            "query_sect": query_sect,
            "query_ener": query_ener,
            "query_var": query_var,
            "query_detail_comm": query_detail_comm, 
        },
        beforeSend:function(jqXHR, settings){
            jqXHR.spinner_right = spinner_right;
        },           
        success: function(response,textStatus,jqXHR){
               
            // Si la réponse est vide alors on affiche une table vide et on quitte
            if (response.length == 0) {
                
                the_table = $('#tableau').DataTable({
                    scrollY: '70vh',
                    scrollCollapse: true,        
                    paging: false,
                    searching: true,
                    responsive: true,
                    dom: 'lpftiBr',
                    buttons: ['copy', 'csv', 'pdf'], 
                    processing: true,
                    serverSide: false,
                    language: {
                        "lengthMenu": "Display _MENU_ records per page",
                        "zeroRecords": "Aucune donnée à afficher",
                        "info": "Showing page _PAGE_ of _PAGES_",
                        "infoEmpty": "No records available",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                    },    
                    data: response,        
                    columns:[{}],
                });                
                
                // Arrêt du sablier (spinner)
                jqXHR.spinner_right.stop();

                return null;
            };
            
            
            // Si la réponse contient trop de lignes alors on averti l'utilisateur
            if (response.length > 1000) {
                                
                // Arrêt du sablier (spinner)
                jqXHR.spinner_right.stop(); 

                // Passage de la réponse dans une variable globale et appel du modal
                response_global = response;
                $('#confirm').modal('show'); //  $('#myModal').modal('hide')
  
            } else {
                
                // Arrêt du sablier (spinner)
                jqXHR.spinner_right.stop();                  
               
                // Affichage des données
                create_table(response);
                            
            };
            
        },
        
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
            
            // Arrêt du sablier (spinner)
            jqXHR.spinner_right.stop();
        },        
    });    
   
    // Mise à jour de la date et de l'heure de l'extraction
    var extraction_time = datehour();
    $(".header_extraction").html('<img src="img/logo-Air-PACA_small.png"><br/><br/>Air PACA - Inventaire v4 - Extraction du ' + extraction_time + '</br><a target="_blank" href="methodo.php#conditions-d-utilisation-des-donn-es">Consulter les conditions d\'utilisation et de diffusion</a>');
};

function create_table(response, display){
    
    $('#confirm').modal('hide');
    
    // Déclanchement du sablier (spinner)
    spinner_right.spin(spinner_right_element);    
    
    // Création de la liste de définition des colonnes
    columns = [];
    for (i in Object.keys(response[0])) {
        field = Object.keys(response[0])[i];
        columns.push( { title: field, name: field, data: field });
    };
    
    // Création de la table
    the_table = $('#tableau').DataTable({
        scrollY: '70vh',
        scrollCollapse: true,        
        paging: false,
        searching: true,
        responsive: true,
        dom: 'lftBr', // 'lpftiBr' = Avec nb lignes en plus
        buttons: [
            // 'copy', 
            {
                extend: 'copyHtml5',
                title: 'Air PACA - Export CIGALE du ' + datehour(),
                // charset: 'iso-8859-1', // 'ANSI', // 'utf-8', 
                customize: function ( csv ) {
                    return "Air PACA\n\n" + cgu + "\n\n" + csv;
                }                
            },  
            {
                extend: 'csvHtml5',
                title: 'Air PACA - Export CIGALE du ' + datehour(),
                charset:  'utf-8', // 'windows-1252', // 'iso-8859-1', // 'ANSI', // 'utf-8', 
                bom: true,
                customize: function ( csv ) {
                    return "Air PACA\n\n" + cgu + "\n\n" + csv;
                }                
            },        
            {
                extend: 'pdfHtml5',
                title: 'Air PACA - Export CIGALE du ' + datehour(),
                message: cgu, 
                customize: function ( doc ) {
                    
                    var img = new Image();
                    img.src = "img/logo-Air-PACA.png"; 
                    var dataURI = getBase64Image(img);
                    
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        image: dataURI, 
                    } );
                }                
            }
        ], 
        processing: true,
        serverSide: false,
        language: {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Aucune donnée à afficher",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
        },    
        data: response,        
        columns:columns,
    }); 

    // Arrêt du sablier (spinner)
    spinner_right.stop();       
    
};

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        };
    };
};

function changer_listes_prod(is_prod){
    /*
    Change les listes avec les variables prod si prod is true
    avec les variables conso / emi si prod is false.
    */
    
    if (is_prod == true){
        // On ne laisse que la prod dans la liste des variables 
        $("#select_variable").selectpicker('deselectAll');
        $("#select_variable").selectpicker('val', '999');
        
        // Les secteurs d'activités deviennent les grandes filières
        $("#label_activites")[0].innerHTML = "Grandes filières";           
        $("#select_secteurs").selectpicker({title: "Toutes les grandes filières"}).selectpicker('render');            

        $("#select_secteurs option").remove();
    
        for (isect in listes["grandes_filieres"]) {             
            $("#select_secteurs").append($('<option>', {value: listes["grandes_filieres"][isect].id_grande_filiere_cigale, text: listes["grandes_filieres"][isect].grande_filiere_cigale}, '</option>'));                               
        };
        $("#select_secteurs").selectpicker('refresh');          
    
        // Les énergies deviennent les petites filières ENR ou autres regroupées
        $("#label_energies")[0].innerHTML = "Filières détaillées";
        $("#select_cat_ener").selectpicker({title: "Toutes les filières détaillées"}).selectpicker('render'); 

        $("#select_cat_ener option").remove();
    
        for (isect in listes["filieres"]) {             
            $("#select_cat_ener").append($('<option>', {value: listes["filieres"][isect].id_detail_filiere_cigale, text: listes["filieres"][isect].detail_filiere_cigale}, '</option>'));                               
        };
        $("#select_cat_ener").selectpicker('refresh');          
    } else {
        // On supprime la prod de la liste des variables 
        $("#select_variable")[0][1].selected = false;
        $("#select_variable").selectpicker('render');
        
        // Les grandes filières deviennent les secteurs d'activités
        $("#label_activites")[0].innerHTML = "Secteurs d'activités";
        $("#select_secteurs").selectpicker({title: "Tous secteurs d'activités confondus"}).selectpicker('render');  

        $("#select_secteurs option").remove();
    
        for (isect in listes["activites"]) {             
            $("#select_secteurs").append($('<option>', {value: listes["activites"][isect].id_secten1, text: listes["activites"][isect].nom_secten1}, '</option>'));                               
        };
        $("#select_secteurs").selectpicker('refresh');               
        
        // Les petites filières ENR ou autres regroupées deviennent les énergies
        $("#label_energies")[0].innerHTML = "Energies";
        $("#select_cat_ener").selectpicker({title: "Toutes énergies confondues"}).selectpicker('render'); 
        
        $("#select_cat_ener option").remove();
    
        for (isect in listes["energies"]) {             
            $("#select_cat_ener").append($('<option>', {value: listes["energies"][isect].code_cat_energie, text: listes["energies"][isect].cat_energie}, '</option>'));                               
        };
        $("#select_cat_ener").selectpicker('refresh');         
    };
};

$(function () { // Gestion de la liste des variables pour faire des productions un groupe à part   
    $("#select_variable").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
        
        var selectedD = $(this).find('option').eq(clickedIndex).text();
        
        // Si on a choisi les productions
        if ((selectedD == "Productions d'énergie" && newValue == true)) {              
            changer_listes_prod(true);
        // Si on a pas choisi les productions
        } else { 
            changer_listes_prod(false); 
        };

    });
});

function getBase64Image(img) {
    /*
    Converts an image to base 64
    */
    var canvas = document.createElement("canvas");

    canvas.width = img.width;
    canvas.height = img.height;
    var ctx = canvas.getContext("2d");

    ctx.drawImage(img, 0, 0);

    var dataURL = canvas.toDataURL("image/jpeg");

    return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");

};

/* Appel des fonctions */
fill_listes();
$('[data-toggle="tooltip"]').tooltip(); // Configuration des popups   
















</script>

</body>
</html>