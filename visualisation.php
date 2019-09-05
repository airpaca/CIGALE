<!-- Doctype HTML5 -->
<!DOCTYPE html>
<html lang="en">
<html dir="ltr">
<head>

    <!-- 
    TODO: Tester le chargement des différents EPCI avec l'exemple suivant:https://gist.github.com/zross/f0306ca14e8202a0fe73
    FIXME: Impossible de faire marcher le filtre WFS on charge donc toute la couche et filtre avec leaflet ce qui est pas top!
    -->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Visualisation climat Air Energie">
    <meta name="author" content="AtmoSud">
    
    <title>CIGALE - Visualisation</title>
    
    <link rel="icon" type="image/png" href="img/cicada.png">
    
    <!-- JQuery 3.2.1 -->
    <script src="libs/jquery/jquery-3.2.1.min.js"></script>    
    
    <!-- Leaflet 3.2.1 -->
    <script src="libs/leaflet/leaflet_v1.0.3/leaflet.js"></script> 
    <link rel="stylesheet" href="libs/leaflet/leaflet_v1.0.3/leaflet.css"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
    <script src="libs/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

    <!-- Leaflet Sidebar -->
    <script src="libs/leaflet-sidebar-master/src/L.Control.Sidebar.js"></script>
    <link rel="stylesheet" href="libs/leaflet-sidebar-master/src/L.Control.Sidebar.css"/>    

    <!-- Leaflet.Spin (including spin.js) -->
    <script src="libs/spin.js/spin.min.js"></script>
    <script src="libs/Leaflet.Spin-1.1.0/leaflet.spin.min.js"></script>

    <!-- leaflet.wms -->
    <script src="libs/leaflet.wms-gh-pages/dist/leaflet.wms.js"></script>  

    <!-- proj4js -->
    <script src="libs/proj4/proj4.js"></script>  
        
    <!-- Chart.js
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    
    <!-- Selectize.js (ATTENTION: Version standalone nécessaire!! js/standalone) -->
    <script src="libs/selectize.js/selectize.min.js" type="text/javascript"></script>
    <link href="libs/selectize.js/selectize.css" rel="stylesheet" type="text/css"/>
    <link href="libs/selectize.js/selectize.bootstrap3.css" rel="stylesheet" type="text/css"/>    

    <!-- Geostats (simogeo/geostats) -->
    <script type="text/javascript" src="libs/geostats/geostats-master/lib/geostats.min.js"></script>

    <!-- Chroma.js -->
    <script type="text/javascript" src="libs/chroma.js/chroma.js-master/chroma.min.js"></script>
  
    <!-- Monstserrat font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">    
    
    <!-- jsPDF -->
    <script src="libs/jspdf/jsPDF-master/dist/jspdf.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="visualisation.css"/>

    <!-- Config -->
    <script type="text/javascript" src="config.js"></script>
 
</head>
    
<!------------------------------------------------------------------------------ 
                                    Body
------------------------------------------------------------------------------->
<body>

<div id="container">
<!-- Corps de la page -->
<div class="row">
    
    <!-- Zone gauche de sélection et navigation -->
    <div class="col-md-3" id="zone-select">
        
        <!-- Titre de la page -->
        <img class="img-title" src="img/cartography2.png" border="0" width="140">  <!-- orig: width="180" -->
        <h3 class="centered">Visualisation</h3>        
    
        <!-- Sélection de l'emprise géographique qui déclanche la fonction submitForm() -->
        <div class="liste_select">
            <select id="geonfo" placeholder="Rechercher un EPCI ..."></select>              
            <a href="javascript:liste_epci_clean();" class="btn btn-default reinit" role="button">Réinitialiser</a> 
        </div>
    
        <!-- Liste des données sélectionnables qui s'affiche après sélection d'un EPCI -->
        <a href="#" class="list-group-item active" id="liste_polluants">
        Polluants atmosphériques
        </a>

            <a href="#" class="list-group-item liste_polluants_items active" id="nox">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de NOx
            </a>

            <a href="#" class="list-group-item liste_polluants_items" id="pm10">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de PM10
            </a>

            <a href="#" class="list-group-item liste_polluants_items" id="pm2.5">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de PM2.5
            </a>            

            <a href="#" class="list-group-item liste_polluants_items" id="covnm">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de COVNM
            </a>  

            <a href="#" class="list-group-item liste_polluants_items" id="so2">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de SO<SUB>2</SUB>
            </a>  

            <a href="#" class="list-group-item liste_polluants_items" id="nh3">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de NH<SUB>3</SUB>
            </a>              

            <a href="#" class="list-group-item liste_polluants_items" id="co">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de CO
            </a>      
            
        <a href="#" class="list-group-item" id="liste_energies">
        Bilans énergétiques
        </a>

            <a href="#" class="list-group-item hide liste_energies_items" id="conso">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Consommations d'énergie finale 
            <!-- <a href="methodo.php" class="lien_image_menu"><span class="glyphicon glyphicon-info-sign"></span></a> -->
            <!-- <span class="glyphicon glyphicon-info-sign"><a href="methodo.php"></a></span> -->
            </a>   

            <a href="#" class="list-group-item hide liste_energies_items" id="prod">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Productions d'énergie
            </a>               
            
        <a href="#" class="list-group-item" id="liste_ges">
        Gaz à Effet de Serre
        </a>

            <a href="#" class="list-group-item hide liste_ges_items" id="co2">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de CO<SUB>2</SUB>
            </a>  
            
            <a href="#" class="list-group-item hide liste_ges_items" id="ch4.co2e">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de CH<SUB>4</SUB> eq.CO<SUB>2</SUB>
            </a>  

            <a href="#" class="list-group-item hide liste_ges_items" id="n2o.co2e">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Emissions de N<SUB>2</SUB>O eq.CO<SUB>2</SUB>
            </a>  

            <a href="#" class="list-group-item hide liste_ges_items" id="prg100.3ges">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            PRG 100 
            </a>  
        
        <!-- Navigation dans les menus -->
        <div class="row">
            <div class="col-xs-4">
                <a href="index.php"><img class="img-menus" id="img-home" src="img/flat-blue-home-icon-4.png" border="0" width="80"></img></a>
            </div>			
            <div class="col-xs-4">
                <a href="extraction.php"><img class="img-menus" id="img-extract" src="img/csv-icon.png" border="0" width="80"></img></a>      
            </div>
            <div class="col-xs-4">
                <a href="methodo.php"><img class="img-menus" id="img-methodo" src="img/document-flat.png" border="0" width="80"></img></a>
            </div>
        </div>
    
    
    </div>
    
    <!-- Zone droite de consultation des donées-->
    <div class="col-md-9" id="zone-display">
        
        <!-- Element carte -->
        <div id="map"></div>
        
    </div>
    
</div>    
</div> 
<!-- Leaflet sidebar --> 
<div id="sidebar">
    <h1>leaflet-sidebar</h1>
</div>     


<!------------------------------------------------------------------------------ 
                                    Map script
------------------------------------------------------------------------------->
<script type="text/javascript">



/* Navigation dans les menus */
$("#img-methodo").hover(function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".png", ".hover.png");
    });
}, function(){
    $(this).attr("src", function(index, attr){
        return attr.replace(".hover.png", ".png");
    });
});

$("#img-extract").hover(function(){
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


/* Variables générales */

// Le logo AtmoSud prend trop de temps à transformer en base 64, on stock directement le résultat dans une variable.
// ANCIEN LOGO AIRPACA: var dataURI_logo_airpaca = "data:image/jpeg;base64,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";
var dataURI_logo_airpaca = "data:image/jpeg;base64,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";

// var wms_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map";
// var wms_format = 'image/png';
// var wms_tr = true;
// var wms_attrib = "AtmoSud";

// var wfs_getcapabilities = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map&SERVICE=WFS&REQUEST=GetCapabilities&VERSION=2.0.0";
// var wfs_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map&SERVICE=WFS&VERSION=2.0.0";  
var wfs_address = cfg_host + "/cigale/ows?service=wfs&version=2.0.0";

var polls = ['conso', 'prod', 'so2','nox','pm10','pm2.5','covnm','nh3','co','co2','ch4.co2e','n2o.co2e','prg100.3ges'];
var polls_names = {
    "conso": "consommations",
    "prod": "productions",
    "so2": "SO<SUB>2</SUB>",
    "nox": "NOx",
    "pm10": "PM10",
    "pm2.5": "PM2.5",
    "covnm": "COVNM",
    "nh3": "NH<SUB>3</SUB>",
    "co": "CO",
    "co2": "CO<SUB>2</SUB>",
    "ch4.co2e": "CH<SUB>4</SUB> eq. CO<SUB>2</SUB>",
    "n2o.co2e": "N<SUB>2</SUB>O eq. CO<SUB>2</SUB>",
    "prg100.3ges": "PRG 100",
};
var polls_id = {
    "conso": "131",
    "prod": "999",
    "so2": "48",
    "nox": "38",
    "pm10": "65",
    "pm2.5": "108",
    "covnm": "16",
    "nh3": "36",
    "co": "11",
    "co2": "15",
    "ch4.co2e": "123",
    "n2o.co2e": "124",
    "prg100.3ges": "128",
};

var polluant_actif = "nox";

var color_scales = {
    polluants: ['#ffeda0', '#feb24c', '#f03b20'],
    energie: ['#fde0dd', '#fa9fb5', '#c51b8a'],
    ges: ['#f9ebea', '#cd6155', '#cb4335'],
};

var my_layers = {
    epci_wms: {
        name: "epci",
        layer: null,
        type: "wms",
        wms_layer_name: "epci",
        opacity: 0.5,
        subtitle: "EPCI PACA 2017",
        onmap: true,
    },       
};

var my_app = {
    sidebar: {displayed: false},
    siren_epci: "",
    nom_epci: "",
    niveau: "epci",
};

var cgu = 'Conditions Générales d\'utilisation: \n\n \
Diffusion libre pour une réutilisation ultérieure des données dans les conditions ci-dessous : \n \
– Toute utilisation partielle ou totale de ces données doit faire référence à AtmoSud en terme de "AtmoSud - Inventaire énergétique et d\'émissions de polluants et gaz à effet de serre". \n \
– Données non rediffusées en cas de modification ultérieure des données. \n \
\n \
Les données contenues dans ce document restent la propriété d\'AtmoSud.\n \
AtmoSud peut rediffuser ce document à d\'autres destinataires. \
';

/* Déclaration des Controles Leaflet */
var legend = L.control({position: 'bottomleft'});
var logo = L.control({position: 'topleft'});
var hover_info = L.control({position: 'topleft'});

/* Extension de chart.js */
Chart.defaults.global.defaultFontColor = '#333';
Chart.defaults.global.defaultFontSize = 13;
Chart.defaults.global.defaultFontFamily = "'Lato', sans-serif";
Chart.defaults.global.defaultFontStyle = "normal";
Chart.defaults.global.legend.labels.boxWidth = 20;

// Spinner
var spinner_bilans = new Spinner({opacity: 0.25, width: 3, color: "#6E6E6E", speed: 1.5, scale: 3, }); // top:"50%", left:"60%",
var spinner_bilans_element = document.getElementById('sidebar');

// Permets de dessiner des lignes sur un linechart pour les nodata
// Source: https://stackoverflow.com/questions/36329630/chart-js-2-0-vertical-lines
var originalLineDraw = Chart.controllers.line.prototype.draw;
Chart.helpers.extend(Chart.controllers.line.prototype, {
  draw: function() {
    originalLineDraw.apply(this, arguments);

    var chart = this.chart;
    var ctx = chart.chart.ctx;

    var xaxis = chart.scales['x-axis-0'];
    var yaxis = chart.scales['y-axis-0'];

    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 0.5), yaxis.top);
    ctx.strokeStyle = '#ffffff';
    ctx.lineWidth = 50;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 0.5), yaxis.bottom);
    ctx.stroke();
    
    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 0.3), yaxis.top);
    ctx.strokeStyle = '#bfbfbf';
    ctx.lineWidth = 1;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 0.3), yaxis.bottom);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 0.71), yaxis.top);
    ctx.strokeStyle = '#bfbfbf';
    ctx.lineWidth = 1;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 0.71), yaxis.bottom);
    ctx.stroke();
    
    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 1.5), yaxis.top);
    ctx.strokeStyle = '#ffffff';
    ctx.lineWidth = 25;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 1.5), yaxis.bottom);
    ctx.stroke();
    
    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 1.4), yaxis.top);
    ctx.strokeStyle = '#bfbfbf';
    ctx.lineWidth = 1;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 1.4), yaxis.bottom);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(xaxis.getPixelForValue(undefined, 1.6), yaxis.top);
    ctx.strokeStyle = '#bfbfbf';
    ctx.lineWidth = 1;
    ctx.lineTo(xaxis.getPixelForValue(undefined, 1.6), yaxis.bottom);
    ctx.stroke();
    
  }
});

/* Fonctions */
$(function() { /* Gestion des listes et couches EPCI poll */

    /* Click sur la liste des polluants */
    $('#liste_polluants').click( function() {
        
        $('#liste_polluants').addClass("active"); 
        $('#liste_energies').removeClass("active"); 
        $('#liste_ges').removeClass("active");          
        
        $('.liste_polluants_items').removeClass("hide"); 
        $('.liste_energies_items').addClass("hide"); 
        $('.liste_ges_items').addClass("hide");      
    });
   
    /* Click sur la liste des énergies */
    $('#liste_energies').click( function() {
        
        $('#liste_polluants').removeClass("active"); 
        $('#liste_energies').addClass("active"); 
        $('#liste_ges').removeClass("active");         
        
        $('.liste_polluants_items').addClass("hide"); 
        $('.liste_energies_items').removeClass("hide"); 
        $('.liste_ges_items').addClass("hide");         
    });
    
    /* Click sur la liste des ges */
    $('#liste_ges').click( function() {
        
        $('#liste_polluants').removeClass("active"); 
        $('#liste_energies').removeClass("active"); 
        $('#liste_ges').addClass("active");         
        
        $('.liste_polluants_items').addClass("hide"); 
        $('.liste_energies_items').addClass("hide"); 
        $('.liste_ges_items').removeClass("hide"); 
    });  

    /* Click sur un polluant */
    $('.liste_polluants_items').click( function() {
        $('.liste_polluants_items').removeClass('active');
        $(this).addClass('active');
        // console.log($(this).attr("id"));
    });     
 
    /* Click sur une energie */
    $('.liste_energies_items').click( function() {
        $('.liste_energies_items').removeClass('active');
        $(this).addClass('active');
    });   

    /* Click sur un GES */
    $('.liste_ges_items').click( function() {
        $('.liste_ges_items').removeClass('active');
        $(this).addClass('active');
    }); 

    /* Click sur une couche quelle qu'elle soit */
    $('.liste_polluants_items, .liste_energies_items, .liste_ges_items').click( function() {
        
        // Change le polluant actif
        polluant_actif = $(this).attr("id");
        
        // Si les émissions à la commune sont affichées alors on change le poll des graphiques et communes
        // if (map.hasLayer(my_layers.comm_nox.layer) == true){
        // if (map.hasLayer(my_layers["comm_" + polluant_actif].layer) == true){
        if (my_app.niveau == "comm"){
            
            // Suppression des émissions commuales et création des nouvelles
            for (i in my_layers){
                if ( my_layers[i].name.match(/comm_.*/) && map.hasLayer(my_layers[i].layer) == true ){
                    map.removeLayer(my_layers[i].layer);
                };
            };
            create_wfs_comm_layers(my_layers["comm_" + polluant_actif], my_app.siren_epci);
            
            // Création des graphiques
            if (polluant_actif == 'conso') {
                create_graphiques_conso(my_app.siren_epci, my_app.nom_epci);
            } else if (polluant_actif == 'prod') {
                create_graphiques_prod(my_app.siren_epci, my_app.nom_epci);                
            } else if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges') { 
                create_graphiques_ges(my_app.siren_epci, my_app.nom_epci);                
            } else {
                create_graphiques(my_app.siren_epci, my_app.nom_epci);             
            };
            
            return null;
        } else {
        // Sinon on gère les couches des EPCI

            // Affiche la couche des ECPI pour le polluant actif, retire les autres
            for (i in my_layers) {

                // Si une des couches EPCI polluant est déjà affichée on la supprime
                if ( my_layers[i].name.match(/epci_.*/) && map.hasLayer(my_layers[i].layer) == true ){
                    map.removeLayer(my_layers[i].layer);
                    // console.log("Removed " + my_layers[i].name);
                };    

                // On affiche la couche des EPCI correspondant au polluant actif
                if (my_layers[i].name == "epci_" + polluant_actif) {
                    my_layers[i].layer.addTo(map);
                    
                    // Création de la légende
                    generate_legend(my_layers[i].legend_text, my_layers[i].legend.bornes, my_layers[i].legend.colors);             
                    
                    // Zoom sur la couche
                    map.fitBounds(my_layers[i].layer.getBounds());
                    
                    // console.log("Added " + my_layers[i].name);
                };
                
            };
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

function createMap(){
    /* Création de la carte */
    var map = L.map('map', {layers: [], zoomControl:false}).setView([43.9, 6.0], 8);    
    map.attributionControl.addAttribution('&copy; <a href="http://www.airpaca.org/">AtmoSud - 2017</a>');    

    /* Chargement des fonds carto */    
    // var Hydda_Full = L.tileLayer('http://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png', {
        // maxZoom: 18,
        // opacity: 0.5,
        // attribution: 'Fond de carte &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    // });
    // Hydda_Full.addTo(map);

    var urlbasemap = 'https://{s}.tiles.mapbox.com/v3/aj.map-zfwsdp9f/{z}/{x}/{y}.png'; // AJ Ashton's Terrain Grey
    var attrib = '&copy; Mapbox';
    L.tileLayer(urlbasemap, {
            name: "fond",
            attribution: attrib,
            opacity: 0.75
    }).addTo(map);

    // Prise en compte du click sur la carte 
    map.on('click', function(e){   
   
        // Si besoin de boucler sur les couches présentes sur la carte:
        // var clickBounds = L.latLngBounds(e.latlng, e.latlng);       
        // for (var l in map._layers) {            
            // if (map._layers[l].options.name == 'epci') {
                // console.log(map._layers[l]);
            // };
        // };
            
        // Supprime tous les éventuels layers des communes 
        remove_all_comm_layers();
             
        // Cache la couche des communes si visible
        sidebar.hide();
        // Vide la liste des EPCI
        select_list[0].selectize.clear();
                
        // if (map.hasLayer(my_layers["comm_" + polluant_actif].layer) == true) {           
            // map.removeLayer(my_layers["comm_" + polluant_actif].layer);
        // };
               
        // Affichage des EPCI et regénération de la légende
        my_layers["epci_" + polluant_actif].layer.setStyle({fillOpacity:0.5});
        my_layers["epci_" + polluant_actif].layer.addTo(map);
        generate_legend(my_layers["epci_" + polluant_actif].legend_text, my_layers["epci_" + polluant_actif].legend.bornes, my_layers["epci_" + polluant_actif].legend.colors);
        map.fitBounds(my_layers["epci_" + polluant_actif].layer.getBounds());
        
        // On informe l'application que l'on est au niveau epci
        my_app.niveau = 'epci';  
        
    });
    
    return map;
};

function remove_all_comm_layers(){
    // Supprimer tous les layers des communes    
    for (var l in map._layers) {   
        if (map._layers[l].options.name != null) {
            if (map._layers[l].options.name.match(/comm_.*/)) {
                // console.log("Removeing layer - " + map._layers[l].options.name);
                map.removeLayer(map._layers[l]);
            };
        };
    };    
};

function create_sidebar(){
    /*
    Initialisation de la slidebar popup
    Ex: var sidebar = create_sidebar();
    */
    var sidebar = L.control.sidebar('sidebar', {
        closeButton: true,
        position: 'right',
        autoPan: false,
    });

    // Modification de le fonction show de la sidebar.
    // NOTE: Remplacé par paddingBottomRight dans l'appel de la méthode map.fitBounds.
    /*
    sidebar.show = function () {
        // RS ADD - Always Pan on show()
        this._map.panBy([-this.getOffset() / 2, 0], {
            duration: 0.5
        });      
        ---

        if (!this.isVisible()) {
            L.DomUtil.addClass(this._container, 'visible');
            if (this.options.autoPan) {
            }
            this.fire('show');
        }
    };
    */


    map.addControl(sidebar);
    sidebar.hide();
    
    return sidebar;
};

function liste_epci_create(){
    /*
    Création de la liste de sélection des entités géographiques qui sera
    ensuite remplie par une requête Ajax.
    Code en partie tiré d'Emiprox, Jonathan Virga.
    */
    var select_list = $('#geonfo').selectize({
        valueField: 'geoid',
        labelField: 'geonm',
        searchField: ['geonm'],
        options: [
            // { geoid: "reg|93", geonm: "PACA", geotyp: "Région" },
        ],
        highlight: true,
        render: {
            option: function(item, escape) {
                return "<div><span class='form_geonm'>" + escape(item.geonm) + "</span><br /><span class='form_geotyp'>" + escape(item.geotyp) + "</span></div>";
            },
        },
        onChange: function(value){
            if (select_list[0].selectize.getItem(value)[0] != null) {
                epci2comm(select_list[0].selectize.getValue(), select_list[0].selectize.getItem(value)[0].textContent);            
            };
        },
    });
    
    return select_list;
};     

function liste_epci_populate() {
    /*
    Remplissage de la liste des échelon administratifs.
    Pour pouvoir remplir la liste directement sans boucler 
    sur les résultats et leur champs il est nécessaire que 
    les champs de la requête (dont leur nom) correspondent 
    exactement aux champs du formulaire.
    */
    $.ajax({
        type: "GET",
        url: "scripts/liste_epci_populate.php",
        dataType: 'json',   
        success: function(response,textStatus,jqXHR){
            
            var selectize_element = select_list[0].selectize;
            selectize_element.addOption(response);
            // selectize_element.refreshOptions(); // Provoque l'ouverture de la liste onload
        },
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });	  
};

function liste_epci_clean() {
    // Efface la liste
    select_list[0].selectize.clear();

    // Si la couche des communes est déjà affichée on la supprime
    if (map.hasLayer(my_layers["comm_" + polluant_actif].layer) == true){
        map.removeLayer(my_layers["comm_" + polluant_actif].layer);
    };  
   
    // Changement de style des EPCI                      
    my_layers["epci_" + polluant_actif].layer.setStyle({fillOpacity:0.5});
   
    // Ajout de la couche des EPCI sur la carte et zoom max extent
    my_layers["epci_" + polluant_actif].layer.addTo(map);
    generate_legend(my_layers["epci_" + polluant_actif].legend_text, my_layers["epci_" + polluant_actif].legend.bornes, my_layers["epci_" + polluant_actif].legend.colors);
    map.fitBounds(my_layers["epci_" + polluant_actif].layer.getBounds());
    
    // On informe l'application que l'on est au niveau epci
    my_app.niveau = 'epci';    
    
    // Cache la sidebar
    sidebar.hide();
};

function liste_epci_submit(){
    /*
    Une fois validé, on récupère le code de l'EPCI et on zoom dessus, affichant les graphiques
    */  

    console.log("SUBMIT");
    
    console.log(liste_epci_submit());
    console.log(select_list[0].selectize.getValue());
    console.log(select_list[0].selectize.getValue());
    
    // Récupération des valeurs du formulaire
    // var liste_siren_epeci = select_list[0].selectize.getValue();
    // var liste_nom_epeci = select_list[0].selectize.options[liste_siren_epeci].geonm;   
    my_app.siren_epci = select_list[0].selectize.getValue();
    my_app.nom_epci = select_list[0].selectize.options[liste_siren_epeci].geonm;
        
    // Passage de l'EPCI à la commune
    epci2comm(liste_siren_epeci, liste_nom_epeci);
    
};

function epci2comm(siren_epeci, nom_epeci){
    
    // Si une couche des communes est déjà affichée on la supprime
    // if (map.hasLayer(my_layers["comm_" + polluant_actif].layer) == true){
    if (my_app.niveau == "comm"){
        map.removeLayer(my_layers["comm_" + polluant_actif].layer);  // FIXME: C'est pas le polluant actif qu'il faut supprimer!!
    };      

    // Zoom sur l'EPCI en le retrouvant dans les objets du layer epci
    for (i in my_layers["epci_" + polluant_actif].layer._layers) {
        if (my_layers["epci_" + polluant_actif].layer._layers[i].feature.properties.siren_epci == siren_epeci) {
            map.fitBounds(my_layers["epci_" + polluant_actif].layer._layers[i]._bounds, {paddingBottomRight: [800, 0]});
        };
    };
    
    // Affichage de la couche des communes
    create_wfs_comm_layers(my_layers["comm_" + polluant_actif], siren_epeci); 
    
    // Retrait de la couche EPCI
    // map.removeLayer(my_layers["epci_" + polluant_actif].layer);    
    
    // Changement de style des EPCI                      
    my_layers["epci_" + polluant_actif].layer.setStyle({fillOpacity:0.0});    
    
    
    // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
    if (polluant_actif == 'conso') {
        create_graphiques_conso(siren_epeci, nom_epeci);
    } else if (polluant_actif == 'prod') {
        create_graphiques_prod(siren_epeci, nom_epeci);
    } else if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges') { 
        create_graphiques_ges(siren_epeci, nom_epeci);           
    } else {
        create_graphiques(siren_epeci, nom_epeci);       
    };    

    // On informe l'application que l'on est au niveau communal
    my_app.niveau = "comm";
};

function layer_epci_chargement(){
    /*
    NOTE: OLD PLUS UTILISE. ON PASSE MAINTENANT PAR GEOSERVER
    Création d'une couche des EPCI à partir d'une requête 
    ajax dans la base PostGIS.
    Pour traiter du json en PostgreSQL il faut avoir une version 
    supérieure à 9.1. On peut retourner la géométrie en geojson mais 
    on est obligés de créer l'objet json manuellement en javascript.
    */  
    $.ajax({       
        type: "GET",
        url: "scripts/layer_epci_chargement.php",
        dataType: 'json',      
        success: function(response,textStatus,jqXHR){
            
            console.log(response);
            
            // Création d'une liste d'objets GeoJSON à partir de la réponse
            var objets_epci = [];
            for (var i in response) {            
                objets_epci.push(
                    {
                    "type": "Feature",
                    "id": response[i].geoid,
                    "properties": {"geoid": response[i].geoid, "geonm": response[i].geonm},
                    "geometry": JSON.parse(response[i].geom) 
                    }
                );                     
            };  
            
            // Création de la couche
            my_layers["epci"] = new L.geoJSON(objets_epci, {
                name: "epci",
                style: {    
                    "color": "#737373", "weight": 2, "opacity": 1, 
                    "fillColor": '#ffffff', "fillOpacity": 0.4
                },
                filter: function(feature, layer) {
                    return true;
                },
                onEachFeature: function (feature, layer) {
                    
                    // Ajout d'un popup
                    var html = '<div id="popup">';
                    // for (prop in feature.properties) {
                        // html += "<b>" + prop + ':</b> ' + feature.properties[prop]+"<br>";
                    // };
                    
                    html += "" + feature.properties["geonm"]+"<br>"; 
                    
                    html += "</div>";
                    layer.bindPopup(html);

                    // Prise en compte du hover
                    layer.on('mouseover', function(){
                        layer.setStyle({color: '#737373', weight: 4});
                        // this.openPopup();
                    });
                    layer.on('mouseout', function(){
                        layer.setStyle({color: "#737373",weight: 2});
                        // this.closePopup();
                    });

                    // Prise en compte du cklic
                    layer.on('click', function(){
                        // map.fitBounds(layer._bounds);
                        sidebar.toggle(); 
                    });                    

                },  
            });
            
            my_layers["epci"].addTo(map);
            
        },
        error: function (request, error) {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });     
};

function create_wms_layer(my_layers_object){
    /*
    Crée un layer wms à partir des couches disponibles 
    dans le mapfile et l'insert dans l'objet layer déclaré
    en argument.
    Ex: create_wms_layer(my_layers.epci);
    */  
    my_layers_object.layer = L.tileLayer.wms(wms_address, {
        name: my_layers_object.name, // wms_layer_name
        layers: 'epci', // (required) Comma-separated list of WMS layers to show.
        format: wms_format,
        transparent: wms_tr,
        opacity: my_layers_object.opacity,
        subtitle: my_layers_object.subtitle,
    });

    if (my_layers_object.onmap == true) {
        my_layers_object.layer.addTo(map);    
    }; 
    
};

function calc_jenks(data, field, njenks, colorscale){
    /*
    Calcul à partir d'une réponse geojson [data] les classes et couleurs de 
    jenks sur un champ [field] à partir d'une nombre de classes [njenks].
    Renvoie bornes [min, ..., max] et couleurs de ces bornes.

    Attention, si le nombre de classes jenks demandé est trop faible par rapport au nombre
    de valeurs, alors on réduit le nombre de classes
    
    @colorscale: Ex - ['#f9ebea', '#cd6155', '#cb4335']
    
    Ex:
    the_jenks = calc_jenks(data, "superficie", 3, ['#f9ebea', '#cd6155', '#cb4335']);
    console.log(the_jenks);
    */
    items = [];
    $.each(data.features, function (key, val) {
        $.each(val.properties, function(i,j){
            if (i == field) {
                items.push(j);
            };
        }); 
    });    

    if (items.length < njenks + 1){
        console.log("Nombre d'objets insuffisant pour " + njenks + " classes passage à " + (parseInt(items.length) + -1) + " classes.");
        njenks = (parseInt(items.length) + -1);
    };   
    
    classifier = new geostats(items);
    var jenksResult = classifier.getJenks(njenks);
    // var color_x = chroma.scale(['#f9ebea', '#cd6155', '#cb4335']).colors(njenks);
    var color_x = chroma.scale(colorscale).colors(njenks);
    
    return {bornes: jenksResult, colors: color_x}; 
};

function find_jenks_color(jenks_obj, the_val){
    /*
    Retrouve la couleur correspondant à une bornes de classes
    en fonction d'une valeur.
    jenks_obj = object return by calc_jenks Object { bornes: Array[4], colors: Array[3] }
    the_val = value to test
    Returns html color
    */
    
    // Si la valeur est dans une des bornes de classes <=
    for (ibornemin in jenks_obj.bornes.slice(0, -1)) {
        vbornemin = jenks_obj.bornes[ibornemin];
        vbornemax = jenks_obj.bornes[+ibornemin + 1];
        
        // console.log(ibornemin, vbornemin, vbornemax);
        // console.log(the_jenks.bornes[the_jenks.bornes.length - 1]);
        
        if (the_val >= vbornemin && the_val < vbornemax) {
            // console.log("->" + ibornemin, vbornemin, vbornemax, jenks_obj.colors[ibornemin]); 
            return jenks_obj.colors[ibornemin]; 
        };
    };
    
    // Si la valeur est = à la borne encadrante max
    if (the_val == jenks_obj.bornes[jenks_obj.bornes.length - 1]) {
            // console.log("->" + ibornemin, vbornemin, vbornemax, jenks_obj.colors[ibornemin]);
            return jenks_obj.colors[ibornemin];                 
    // Si non retourne une erreur
    } else {
        console.log("ERROR: Value " + the_val + " out of classes");
        return null;
    };    
    
};

function create_wfs_epci_layers(my_layers_object){
    /*
    Crée un layer wfs à partir des couches disponibles 
    dans le mapfile et l'insert dans l'objet layer déclaré
    en argument.
    Ex: create_wfs_epci_layers(my_layers.epci);
    */
    
    $.ajax({
        url: wfs_address + my_layers_object.wfs_query + "&CQL_FILTER=nom_abrege_polluant='" + my_layers_object.polluant + "'",
        datatype: 'json',
        jsonCallback: 'getJson',
        success: function (data) {
        
            // Calcul des statistiques (echelle de couleur en fonction du polluant)
            if (my_layers_object.polluant == 'conso' || my_layers_object.polluant == 'prod'){
                the_jenks = calc_jenks(data, "val", 6, color_scales.energie);
            } else if (my_layers_object.polluant == 'co2' || my_layers_object.polluant == 'ch4.co2e' || my_layers_object.polluant == 'n2o.co2e' || my_layers_object.polluant == 'prg100.3ges'){
                the_jenks = calc_jenks(data, "val", 6, color_scales.ges);
            } else {
                the_jenks = calc_jenks(data, "val", 6, color_scales.polluants);
            };
            // NOTE: La formulation suivante ne fonctionnait pas sous Safari
            // if (['conso','prod'].includes(my_layers_object.polluant)  == true) {
                // the_jenks = calc_jenks(data, "val", 6, color_scales.energie);
            // } else if (['co2','ch4.co2e','n2o.co2e','prg100.3ges'].includes(my_layers_object.polluant)  == true) { 
                // the_jenks = calc_jenks(data, "val", 6, color_scales.ges);
            // } else {
                // the_jenks = calc_jenks(data, "val", 6, color_scales.polluants);
            // };

            // Création de l'objet
            my_layers_object.layer = L.geoJSON(data, {
                name:my_layers_object.name,
                style: function(feature) {
                    
                    // Récupération du style de l'objet et remplissage avec la bonne couleur
                    the_style = my_layers_object.style;
                    the_style.fillColor = find_jenks_color(the_jenks, feature.properties.val);
                    return the_style;
                },
                filter: function(feature, layer) {
                    return true;
                },
                onEachFeature: function (feature, layer) {
                    
                    // Ajout d'un popup
                    // var html = "<div id='popup'>" + feature.properties["nom_epci"]+"<br></div>";                   
                    var html = "";                   
                    layer.bindPopup(html);

                    // Prise en compte du hover
                    layer.on('mouseover', function(){
                        layer.setStyle({weight: 4, color: "#000000"});
                        // this.openPopup();
                        hover_info.update(feature.properties["nom_epci"]);
                    });
                    layer.on('mouseout', function(){
                        layer.setStyle({weight: 2, color: "#000000"});
                        // this.closePopup();
                        hover_info.hide();
                    });

                    // Prise en compte du cklic
                    layer.on('click', function(){
                        
                        this.closePopup(); // Debug - Si on mets pas de popup, les EPCI ne changent pas de style
                        
                        // Zoom sur la couche
                        map.fitBounds(layer._bounds, {paddingBottomRight: [800, 0]});

                        // Retrait de la couche EPCI
                        // map.removeLayer(my_layers_object.layer);
                        
                        // Changement de style des EPCI                      
                        for (i in my_layers_object.layer._layers){
                             my_layers_object.layer._layers[i].setStyle({fillOpacity:0.0});
                        };

                        // Affichage de la couche des communes
                        // create_wfs_comm_layers(my_layers["comm_" + my_layers_object.polluant], feature.properties["siren_epci"]); 
                        create_wfs_comm_layers(my_layers["comm_" + polluant_actif], feature.properties["siren_epci"]); 
                        
                        // On informe l'application que l'on est au niveau communal
                        my_app.niveau = 'comm';
                        
                        // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                         
                        if (polluant_actif  == 'conso'){
                            create_graphiques_conso(feature.properties["siren_epci"], feature.properties["nom_epci"]); 
                        } else if (polluant_actif  == 'prod'){
                            create_graphiques_prod(feature.properties["siren_epci"], feature.properties["nom_epci"]);                             
                        } else if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges') { 
                            create_graphiques_ges(feature.properties["siren_epci"], feature.properties["nom_epci"]);                                
                        } else {
                            create_graphiques(feature.properties["siren_epci"], feature.properties["nom_epci"]);  
                        };  

                        // On réapplique le style mouseout pour éviter les artefacts 
                        layer.setStyle({weight: 2, color: "#000000"});

                        // Mise à jour de la liste des EPCI avec l'EPCI sélectionné
                        // NOTE: Si bugs / lenteurs, commencer par commenter cette ligne!
                        select_list[0].selectize.setValue(feature.properties["siren_epci"], true);                       
                    });                    
                },                 
            });     

            // Enregistrement des paramètres de la légende pour la recréer
            my_layers_object.legend = {bornes: the_jenks.bornes, colors: the_jenks.colors};            
            
            if (my_layers_object.onmap == true) {
                
                // Ajout de la couche sur la carte
                my_layers_object.layer.addTo(map);
                
                // Zoom sur la couche
                map.fitBounds(my_layers_object.layer.getBounds());
                
                // Création de la légende
                generate_legend(my_layers_object.legend_text, the_jenks.bornes, the_jenks.colors);
                
            };
        }
    });    
};

function create_wfs_epci_layers_filter_specifique(my_layers_object){
    /*
    Crée un layer wfs à partir des couches disponibles 
    dans le mapfile et l'insert dans l'objet layer déclaré
    en argument.
    
    Filtre les données en fonction d'un champ et d'une valeur 
    Ex: create_wfs_epci_layers_filter_specifique(my_layers.epci);
    
    FIXME: Abandonné pour l'instant, car il faudrait refaire tous les
    jenks. Cf. FIXME dans la fonction filter.
    */
    $.ajax({
        url: wfs_address + my_layers_object.wfs_query,
        datatype: 'json',
        jsonCallback: 'getJson',
        success: function (data) {
        
            // Calcul des statistiques
            the_jenks = calc_jenks(data, "val", 6, ['#f9ebea', '#cd6155', '#cb4335']);
           
            // Création de l'objet
            my_layers_object.layer = L.geoJSON(data, {
                name:my_layers_object.name, 
                filtre_polluant: function(value){
                    /*
                    Fonction spécifique permettant de filtrer 
                    le layer en fonction du polluant demandé
                    
                    FIXME: Il faudrait recalculer les classes de couleur et 
                    les appliquer! Trop long, on laisse de côté et on essaie de créer plusieurs
                    layers à partir d'une liste de polluants
                    */
                    if (value == null) {
                        value = "no2";
                    };
                   
                    for (iobjet in my_layers.epci_wfs_poll.layer._layers){
                        if (my_layers.epci_wfs_poll.layer._layers[iobjet].feature.properties.nom_abrege_polluant != value){
                            map.removeLayer(my_layers.epci_wfs_poll.layer._layers[iobjet]);
                        } else {
                            map.addLayer(my_layers.epci_wfs_poll.layer._layers[iobjet]);
                            console.log(my_layers.epci_wfs_poll.layer._layers[iobjet].options.color)
                        };
                    };
                                        
                },
                style: function(feature) {
                    
                    // Récupération du style de l'objet et remplissage avec la bonne couleur
                    the_style = my_layers_object.style;
                    the_style.fillColor = find_jenks_color(the_jenks, feature.properties.val);
                    return the_style;
                },
                onEachFeature: function (feature, layer) {
                    
                    // Ajout d'un popup
                    var html = "<div id='popup'>" + feature.properties["nom_epci_2017"]+"<br></div>";                   
                    layer.bindPopup(html);

                    // Prise en compte du hover
                    layer.on('mouseover', function(){
                        layer.setStyle({weight: 4, color: "#000000"});
                        // this.openPopup();
                        hover_info.update(feature.properties["nom_epci_2017"]);
                    });
                    layer.on('mouseout', function(){
                        layer.setStyle({weight: 2, color: "#000000"});
                        // this.closePopup();
                        hover_info.hide();
                    });

                    // Prise en compte du cklic
                    layer.on('click', function(){
                        
                        // Zoom sur la couche
                        map.fitBounds(layer._bounds, {paddingBottomRight: [800, 0]});

                        // Retrait de la couche EPCI
                        map.removeLayer(my_layers_object.layer);
                        
                        // Affichage de la couche des communes
                        create_wfs_comm_layers(my_layers["comm_" + polluant_actif], feature.properties["siren_epci"]); 
                        
                        // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
                        create_graphiques(feature.properties["siren_epci"], feature.properties["nom_epci"]); 
                    if (polluant_actif == 'conso') {
                        create_graphiques_conso(siren_epeci, nom_epeci);
                    } else if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e') {
                        create_graphiques_ges(siren_epeci, nom_epeci);                         
                    } else {
                        create_graphiques(siren_epeci, nom_epeci);       
                    }; 
                        
                        
                        // On réapplique le style mouseout pour éviter les artefacts 
                        layer.setStyle({weight: 2, color: "#000000"});

                        // Mise à jour de la liste des EPCI avec l'EPCI sélectionné
                        // FIXME: Ne fonctionne pas pour l'instant, revoir tout le système d'affichage
                        // select_list[0].selectize.addOption({value:feature.properties["siren_epci_2017"],text:feature.properties["nom_epci_2017"]});
                        // select_list[0].selectize.addItem(feature.properties["siren_epci_2017"], false); 
                        
                    });                    
                },                 
            });     

            if (my_layers_object.onmap == true) {
                
                // Ajout de la couche sur la carte
                my_layers_object.layer.addTo(map);
                
                // Zoom sur la couche
                map.fitBounds(my_layers_object.layer.getBounds());
                
                // Création de la légende
                generate_legend(my_layers_object.legend_text, the_jenks.bornes, the_jenks.colors);
                
                // Enregistrement des paramètres de la légende pour la recréer
                my_layers_object.legend = {bornes: the_jenks.bornes, colors: the_jenks.colors};
            };
        },
        error: function () {
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
            console.log("ERROR - create_wfs_epci_layers_filter(my_layers_object)");
        },        
    });    
};

function create_wfs_comm_layers(my_layers_object, siren_epci){
    /*
    Crée un layer wfs à partir des couches disponibles 
    dans le mapfile et l'insert dans l'objet layer déclaré
    en argument.
    Ex: create_wfs_comm_layers(my_layers.comm_nox);
    */     
   
    // Start loading bar
    // map.spin(true, {opacity: 0.25, width: 3, color: "#6E6E6E", speed: 1.5});

    // Stop loading bar
    // map.spin(false);    
   
    // Supprime tous les éventuels layers des communes 
    remove_all_comm_layers();     
    
    console.log(wfs_address + my_layers_object.wfs_query + "&CQL_FILTER=nom_abrege_polluant='" + my_layers_object.polluant + "'+AND+siren_epci='" + siren_epci + "'");
    
    $.ajax({
        // url: wfs_address + my_layers_object.wfs_query + "&nom_abrege_polluant=" + my_layers_object.polluant + "&siren_epci=" + siren_epci,
        url: wfs_address + my_layers_object.wfs_query + "&CQL_FILTER=nom_abrege_polluant='" + my_layers_object.polluant + "'+AND+siren_epci='" + siren_epci + "'",
        datatype: 'json',
        jsonCallback: 'getJson',
        success: function (data) {
        
            // Calcul des statistiques uniquement sur les valeurs répondant au filtre
            data_filtered = {features: []};
            for (ifeature in data.features) {
                if (data.features[ifeature].properties.siren_epci == siren_epci) {
                    data_filtered.features.push(data.features[ifeature]);
                };
            };
            
            // ... colorramp en fonction du polluant
            // the_jenks = calc_jenks(data_filtered, "val", 6, ['#f9ebea', '#cd6155', '#cb4335']);
            // if (['conso','prod'].includes(my_layers_object.polluant)  == true) {
            if (my_layers_object.polluant == "conso" || my_layers_object.polluant == "prod") {
                the_jenks = calc_jenks(data_filtered, "val", 6, color_scales.energie);
            // } else if (['co2','ch4.co2e','n2o.co2e','prg100.3ges'].includes(my_layers_object.polluant)  == true) {
            } else if (my_layers_object.polluant == "co2" || my_layers_object.polluant == "ch4.co2e" || my_layers_object.polluant == "n2o.co2e" || my_layers_object.polluant == "prg100.3ges") {
                the_jenks = calc_jenks(data_filtered, "val", 6, color_scales.ges);
            } else {
                the_jenks = calc_jenks(data_filtered, "val", 6, color_scales.polluants);
            };           
           
            // Création de l'objet
            my_layers_object.layer = L.geoJSON(data, {
                style: function(feature) {
                    
                    // Récupération du style de l'objet et remplissage avec la bonne couleur
                    the_style = my_layers_object.style;
                    the_style.fillColor = find_jenks_color(the_jenks, feature.properties.val);
                    return the_style;
                },
                name: my_layers_object.name, 
                filter: function(feature, layer) {
                    if (feature.properties["siren_epci"] == siren_epci) {
                        return true;
                    };
                },
                onEachFeature: function (feature, layer) {
                    
                    // Ajout d'un popup et passage des arguments id_comm, id_polluant
                    // var html = "<div id='popup'>" + feature.properties["nom_comm"] +"<br>" + parseFloat(feature.properties["val"]).toFixed(1) + " t/an</div>";
                    // var html = "<div id='popup'>Accéder aux données tabulaires?</div>";  
                                        
                    var html = "<div id='popup'><a href='extraction.php'>Extraction des données sur cette commune</a></div>";                    
                    layer.bindPopup(html);

                    // Prise en compte du hover
                    layer.on('mouseover', function(){
                        layer.setStyle({weight: 4});
                        // this.openPopup();
                        
                        if (my_layers_object.polluant == ("conso")){
                            the_unit = "tep";
                        } else if (my_layers_object.polluant == ("prod")){
                            the_unit = "MWh";
                        } else {
                            the_unit = "kg";
                        };
                        hover_info.update(feature.properties["nom_comm"] + ": " + parseFloat(feature.properties["val"]).toFixed(1) + " " + the_unit + "/km&sup2;</div>");
                    });
                    layer.on('mouseout', function(){
                        layer.setStyle({weight: 2});
                        // this.closePopup();
                        hover_info.hide();
                    });

                    // Prise en compte du cklic
                    layer.on('click', function(){
 
                        // Stockage des informations de la commune pour l'extraction éventuelle
                        sessionStorage.id_comm = feature.properties["id_comm"]; 
                        sessionStorage.id_polluant = polls_id[my_layers_object.polluant]; 

                        return null;
                        
                        // Zoom sur la couche
                        // map.fitBounds(layer._bounds, {paddingBottomRight: [800, 0]});

                        // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
                        // create_graphiques(feature.properties["siren_epci_2017"], feature.properties["nom_epci_2017"]);                     
                        
                    });                    
                },                 
            });     
                
            // Ajout de la couche sur la carte
            my_layers_object.layer.addTo(map);
            
            // Création de la légende
            generate_legend(my_layers_object.legend_text, the_jenks.bornes, the_jenks.colors);
        },
    });    
};

function generate_legend(title, grades, colors){
    /*
    Génération d'un légende
    @grades = bornes de classes. On doit avoir une borne de classe de plus que le nb de couleurs
    @colors = liste des couleurs.
    Fait pour fonctionner avec le retour de la fonction calc_jenks()
    Ex: generate_legend("Emissions de NOx / an (t)", the_jenks.bornes, the_jenks.colors);
    */  
    // console.log(title, grades, colors);
    
    legend.onAdd = function (map) {
        
        var div = L.DomUtil.create('div', 'info legend'),
        from, to;
        var labels = [];
        
        div.innerHTML += title + "</br>";
        
        for (var i = 0; i < grades.slice(0,-1).length; i++) {
            
            if (i == 0) {
                from = 0;
            } else {
                from = grades[i].toFixed(0);
            }
            to = grades[i + 1].toFixed(0);            
            
            labels.push('<i style="background:' + colors[i] + '"></i> ' + from + (to ? ' à ' + to : '+'));    
        };
        
        div.innerHTML += labels.join('<br>');

        return div;
    };
    legend.addTo(map);    
};

function create_sidebar_template(){ 
    var sidebarContent = '\
    <section class="graph_container" id="graph_container_block">\
        <div class="graph_title">Titre du graph</div>\
        <div class="btn_export"><img class="img-btn-export" src="img/pdfs-512.png" onclick="export_pdf();"></div>\
        <div class="graph1">graph1</div>\
        <div class="graph2">graph2</div>\
        <div class="graph3">graph3</div>\
        <div class="graph4">graph4</div>\
        <div class="graph5" id="graph5">graph5</div>\
    </section>\
    ';
    sidebar.setContent(sidebarContent);      
};

function change_graph_title(the_title){
    $('.graph_title').html(the_title);    
};

function create_graph_legend(div, type){
    
    if (polluant_actif == 'prod') {
        $('.' + div).html('<img align="left" src="img/plots_legend_grandes_filieres.png">');
    } else {
        if (type == 1) {
            $('.' + div).html('<img align="left" src="img/plots_legend_secteurs.png">');   
        } else {
            $('.' + div).html('<img align="left" src="img/plots_legend_secteurs.png"><img align="left" src="img/plots_legend_energie.png">');   
        };
    };
};

function create_piechart_emi(response, div, graph_title, tooltip_unit){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */           
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');        

    var graph_labels = [];
    for (var i in response) {
        graph_labels.push(response[i].nom_court_secten1);
    };              

    // var graph_title = 'Répartition sectorielle ' + cfg_anmax;

    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var bg_colors = [];
    var bd_colors = [];
    for (var i in response) {
        bg_colors.push(response[i].secten1_color);
        bd_colors.push('#ffffff');
    };  
    
    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'doughnut',      
        data: {
            labels: graph_labels,
            datasets: [{
                label: 'Emissions (t)',
                data: graph_data,
                backgroundColor: bg_colors,
                borderColor: bd_colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true, 
                // fontSize: 13,
                // fontFamily: "'Lato', sans-serif",
                // fontWeight:300,
                // fontColor: "#333",                 
                fontStyle: "normal", 
                text: graph_title
            },
            legend: {
                position: 'bottom',
                display: false, // On désactive la légende
                labels: {fontSize: 10,},                
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        /*
                        Pourcentage de chaque classe et moif tooltip
                        */
                        
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                                                
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];

                        var total = 0;
                        for (var i in allData) {
                            total += parseInt(allData[i]);
                        };
                        
                        var tooltipPercentage = Math.round((tooltipData / total) * 100.);
                        return tooltipLabel + ': ' + tooltipPercentage + '% (' + tooltipData + ' ' + tooltip_unit + ')';
                    }
                }
            },

        }
    });
    
};

function create_piechart_prod(response, div, graph_title, tooltip_unit){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */           
    $('.' + div).html('<canvas id="' + div + '_canvas""></canvas>'); // style="background-color: red;  

    var graph_labels = [];
    for (var i in response) {
        graph_labels.push(response[i].detail_filiere_cigale);
    };              

    // var graph_title = 'Répartition sectorielle ' + cfg_anmax;

    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var bg_colors = [];
    var bd_colors = [];
    for (var i in response) {
        bg_colors.push(response[i].color_detail_filiere_cigale);
        bd_colors.push('#ffffff');
    };  
    
    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'doughnut',      
        data: {
            labels: graph_labels,
            datasets: [{
                label: 'Emissions (t)',
                data: graph_data,
                backgroundColor: bg_colors,
                borderColor: bd_colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true, 
                // fontSize: 13,
                // fontFamily: "'Lato', sans-serif",
                // fontWeight:300,
                // fontColor: "#333",                 
                fontStyle: "normal", 
                text: graph_title
            },
            legend: {
                position: 'bottom',
                display: false, // On désactive la légende
                labels: {fontSize: 10,},
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        /*
                        Pourcentage de chaque classe et moif tooltip
                        */
                        
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                                                
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];

                        var total = 0;
                        for (var i in allData) {
                            total += parseInt(allData[i]);
                        };
                        
                        var tooltipPercentage = Math.round((tooltipData / total) * 100.);
                        return tooltipLabel + ': ' + tooltipPercentage + '% (' + tooltipData + ' ' + tooltip_unit + ')';
                    }
                }
            },

        }
    });   
};

function create_barchart_emi(response, div, poll){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */

    // On est pas arrivé à passer du HTML dans le tool tip du coup on écrit les polluants à l'arrache
    if (poll == "SO<SUB>2</SUB>") {
        poll = "SO2";
    } else if (poll == "NH<SUB>3</SUB>") {
        poll = "NH3";
    } else if (poll == "CO<SUB>2</SUB>") {
        poll = "CO2";
    } else if (poll == "CH<SUB>4</SUB> eq. CO<SUB>2</SUB>") {
        poll = "CH4 eq.CO2";
    } else if (poll == "N<SUB>2</SUB>O eq. CO<SUB>2</SUB>") {
        poll = "N2O eq.CO2";
    };
    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var graph_labels = [];
    for (var i in response) {
        graph_labels.push(response[i].an);
    };              

    var graph_title = 'Evolution pluriannuelle (t)';

    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var bg_colors = [];
    var bd_colors = [];
    for (var i in response) {
        bg_colors.push('#8a8a8a');
        bd_colors.push('#8a8a8a');
    };  

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'bar', // 'horizontalBar',          
        data: {
            labels: graph_labels,
            datasets: [{
                label: poll,
                data: graph_data,
                backgroundColor: bg_colors,
                borderColor: bd_colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: graph_title
            },
            legend: {
                position: 'bottom',
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,
                        // max: 150,
                    }
                }]
            }
        }
    }); 
};

function create_linechart_emi(response, div, graph_title){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
       
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].nom_court_secten1, liste_secteurs) == -1){
            liste_secteurs.push(response[i].nom_court_secten1);
            liste_couleurs.push(response[i].secten1_color);
        };
    };    

    // Il faut ajouter les valeurs en face de la bonne année et mettre une val nulle si besoin.
    var datasets = [];
    
    for (var isect in liste_secteurs) {
        
        data = [];
        
        for (ian in cfg_ans_film) {
            
            secteur = liste_secteurs[isect];
            couleur = liste_couleurs[isect];
            an = cfg_ans_film[ian];
            
            found_val = 0;
            for (var i in response) {
                if (response[i].nom_court_secten1 == secteur && response[i].an == an){
                    found_val = 1;
                    data.push(response[i].val);
                    break;
                };
            };
            if (found_val == 0){
                data.push(null);
            };
        };
        
        datasets.push({
            label: secteur, // response[i].grand_secteur, 
            data: data, 
            backgroundColor: couleur, 
            borderColor: couleur, 
            fill: false,
            borderWidth: 3,
            pointHitRadius: 8,
        });        
        
    };   
    
    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: cfg_ans_film,
            datasets: datasets,
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            // tooltips: {
                // mode: 'index',
                // intersect: false,
            // },
            // hover: {
                // mode: 'nearest',
                // intersect: true
            // },            
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: graph_title,
            },
            legend: {
                position: 'bottom',
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,
                        // max: 150,
                    }
                }]
            }
        },        
    }); 
};

function create_linechart_prod_primaire(response, div, graph_title){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].detail_filiere_cigale, liste_secteurs) == -1){
            liste_secteurs.push(response[i].detail_filiere_cigale);
            liste_couleurs.push(response[i].color_detail_filiere_cigale);
        };
    };    
    
    // Il faut ajouter les valeurs en face de la bonne année et mettre une val nulle si besoin.
    var datasets = [];
    
    for (var isect in liste_secteurs) {
        
        data = [];
        
        for (ian in cfg_ans_film) {
            
            secteur = liste_secteurs[isect];
            couleur = liste_couleurs[isect];
            an = cfg_ans_film[ian];
            
            found_val = 0;
            for (var i in response) {
                if (response[i].detail_filiere_cigale == secteur && response[i].an == an){
                    found_val = 1;
                    data.push(response[i].val);
                    break;
                };
            };
            if (found_val == 0){
                data.push(null);
            };
        };
        
        datasets.push({
            label: secteur, // response[i].grand_secteur, 
            data: data, 
            backgroundColor: couleur, 
            borderColor: couleur, 
            fill: false,
            borderWidth: 3,
            pointHitRadius: 8,
        });        
        
    };             

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: cfg_ans_film,
            datasets: datasets,
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            // tooltips: {
                // mode: 'index',
                // intersect: false,
            // },
            // hover: {
                // mode: 'nearest',
                // intersect: true
            // },            
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: graph_title,
            },
            legend: {
                position: 'bottom',
                display: true,
                labels: {fontSize: 10,},              
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,
                        // max: 150,
                    }
                }]
            }
        },        
    }); 
};

function create_linechart_prod_secondaire(response, div, graph_title){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].grande_filiere_cigale, liste_secteurs) == -1){
            liste_secteurs.push(response[i].grande_filiere_cigale);
            liste_couleurs.push(response[i].color_grande_filiere_cigale);
        };
    };    

    // Il faut ajouter les valeurs en face de la bonne année et mettre une val nulle si besoin.
    var datasets = [];
    
    for (var isect in liste_secteurs) {
        
        data = [];
        
        for (ian in cfg_ans_film) {
            
            secteur = liste_secteurs[isect];
            couleur = liste_couleurs[isect];
            an = cfg_ans_film[ian];
            
            found_val = 0;
            for (var i in response) {
                if (response[i].grande_filiere_cigale == secteur && response[i].an == an){
                    found_val = 1;
                    data.push(response[i].val);
                    break;
                };
            };
            if (found_val == 0){
                data.push(null);
            };
        };
        
        datasets.push({
            label: secteur, // response[i].grand_secteur, 
            data: data, 
            backgroundColor: couleur, 
            borderColor: couleur, 
            fill: false,
            borderWidth: 3,
            pointHitRadius: 8,
        });        
        
    };

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: cfg_ans_film,
            datasets: datasets,
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            // tooltips: {
                // mode: 'index',
                // intersect: false,
            // },
            // hover: {
                // mode: 'nearest',
                // intersect: true
            // },            
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: graph_title,
            },
            legend: {
                position: 'bottom',
                display: true,
                labels: {fontSize: 10,},               
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,
                        // max: 150,
                    }
                }]
            }
        },        
    }); 
};

function create_stacked_barchart_prod(response, div){
    /*
    */
    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var graph_labels = [];
    for (var i in response) {
        if ($.inArray(response[i].an, graph_labels) == -1){
            graph_labels.push(response[i].an);
        };
    };              
    
    var graph_title = 'Productions primaires / secondaires (GWh)';

    var graph_datasets = [
        {label: 'Primaire', backgroundColor: "#6E6E6E", data: []}, // borderColor: "black", borderWidth: 0, 
        {label: 'Secondaire', backgroundColor: "#8a8a8a", data: []} // borderColor: "black", borderWidth: 0, 
    ];
    for (var i in response) {
        if (response[i].prod == "Primaire") {
            graph_datasets[0].data.push(Number(response[i].val));
        } else {
            graph_datasets[1].data.push(Number(response[i].val));
        };
    };  

    var barChartData = {
        labels: graph_labels,
        datasets: graph_datasets
    };
      
    var ctx = document.getElementById(div + "_canvas"); 
    var graph = new Chart(ctx, {
        type: 'bar', // 'horizontalBar',  
        data: barChartData,
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: graph_title
            },
            legend: {
                position: 'bottom',
                display: true,
                labels: {fontSize: 10,},
            },
            scales: {
                yAxes: [{
                    stacked: true,
                    ticks: {
                        min:0,
                        fontSize: 10
                    }
                }],
                xAxes: [{
                    stacked: true,
                    ticks: {
                        min:0,
                        fontSize: 10
                    }                    
                    // categoryPercentage: 0.40,
                }]
            }   
        }  
    }); 
  
};

function create_barchart_part(response, div){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */
    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'horizontalBar', // 'horizontalBar',          
        data: {
            labels: ["EPCI", "Région"],
            datasets: [{
                label: '2015',
                data: [response[0].epci, response[0].reg],
                backgroundColor: '#8a8a8a', // bg_colors,
                borderColor: '#8a8a8a', // bd_colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true,
                // fontSize: 15,
                fontStyle: "normal", 
                text: "EPCI = " + response[0].pct_reg + "% de la région en " + cfg_anmax,
            },
            legend: {
                position: 'bottom',
                display: false,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,
                        // autoSkip: true,
                        // maxTicksLimit: 4,
                        // max: 150,
                    }
                }],
                xAxes: [{
                    ticks: {
                        // beginAtZero:true,
                        min:0,   
                        autoSkip: true,
                        maxTicksLimit: 4,                        
                        // max: 150,
                    }
                }],                
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        /*
                        Ajout de l'unité dans les tooltips
                        */
                        if (polluant_actif == 'conso') {
                            tooltip_unit = ' ktep';
                        } else if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges') { 
                            tooltip_unit = ' t';             
                        } else {
                            tooltip_unit = ' t';            
                        };  

                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];

                        return tooltipLabel + ': ' + tooltipData + tooltip_unit;
                    }
                }
            },            
        }
    }); 
};

function create_graphiques(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    
    // On cache les graphiques précédents si existants et on lance le sablier
    $(".graph_container").hide();
    sidebar.show(); 
    spinner_bilans.spin(spinner_bilans_element);
    
    // Enregistrement de l'EPCI pour recréation éventuelle des graphiques avec un autre polluant
    my_app.siren_epci = siren_epci;
    my_app.nom_epci = nom_epci;
    
    $.ajax({
        type: "GET",
        url: "scripts/graphiques.php",
        dataType: 'json',   
        data: {
            siren_epci: siren_epci,
            polluant: polluant_actif,
            an: cfg_anmax,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = cfg_anmax; 
            jqXHR.spinner_bilans = spinner_bilans;    
        },        
        success: function(response,textStatus,jqXHR){
            
            jqXHR.spinner_bilans.stop();
            
            // Mise en forme des blocs de graphiques pour les émissions
            $(".graph_container").show();
            $(".graph4").css({"height": "10%", "width": "70%"});    
            $(".graph4").show();
            $(".graph5").show();             
                    
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Emissions annuelles de ' + jqXHR.polls_names[jqXHR.polluant] + "</br>(" + response[4][0].val + " t en " + cfg_anmax + ")"); 
            
            create_barchart_emi(response[1], "graph2", jqXHR.polls_names[jqXHR.polluant]);
            create_piechart_emi(response[0], "graph1", 'Répartition sectorielle ' + cfg_anmax, "t");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle pluriannuelle (t)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 1);
            
        },
        error: function (request, error) {
            // jqXHR.spinner_bilans.stop();
            console.log("ERROR: create_graphiques()");
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });

};

function create_graphiques_conso(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    
    // On cache les graphiques précédents si existants et on lance le sablier
    $(".graph_container").hide();
    sidebar.show(); 
    spinner_bilans.spin(spinner_bilans_element);    
    
    // Enregistrement de l'EPCI pour recréation éventuelle des graphiques avec un autre polluant
    my_app.siren_epci = siren_epci;
    my_app.nom_epci = nom_epci;
    
    $.ajax({
        type: "GET",
        url: "scripts/graphiques_consos.php",
        dataType: 'json',   
        data: { 
            siren_epci: siren_epci,
            polluant: polluant_actif,
            an: cfg_anmax,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = cfg_anmax;   
            jqXHR.spinner_bilans = spinner_bilans;                
        },        
        success: function(response,textStatus,jqXHR){
             
            jqXHR.spinner_bilans.stop(); 
             
            // Mise en forme des blocs de graphiques pour les émissions
            $(".graph_container").show();
            $(".graph4").css({"height": "10%", "width": "70%"});  
            $(".graph4").show();
            $(".graph5").show();             
             
            // titre
            change_graph_title(jqXHR.nom_epci + "</br> Consommation d’énergie finale non corrigée du climat </br>(" + response[4][0].val + " ktep en " + cfg_anmax + ")");
            
            create_piechart_emi(response[0], "graph1", "Consommations finales par secteur en " + cfg_anmax, "ktep");
            create_piechart_emi(response[1], "graph2", "Consommations finales par énergie en " + cfg_anmax, "ktep");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle (énergie finale en ktep)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 2);
             
        },
        error: function (request, error) {
            jqXHR.spinner_bilans.stop(); 
            console.log("ERROR: create_graphiques_conso()");
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });

};

function create_graphiques_prod(siren_epci, nom_epci){
    /*
    Création des graphiques de productions
    */

    // On cache les graphiques précédents si existants et on lance le sablier
    $(".graph_container").hide();
    sidebar.show(); 
    spinner_bilans.spin(spinner_bilans_element);
    
    // Enregistrement de l'EPCI pour recréation éventuelle des graphiques avec un autre polluant
    my_app.siren_epci = siren_epci;
    my_app.nom_epci = nom_epci;
    
    $.ajax({
        type: "GET",
        url: "scripts/graphiques_prod.php",
        dataType: 'json',   
        data: { 
            siren_epci: siren_epci,
            polluant: polluant_actif,
            an: cfg_anmax_prod,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = cfg_anmax_prod;
            jqXHR.spinner_bilans = spinner_bilans;             
        },        
        success: function(response,textStatus,jqXHR){
              
            jqXHR.spinner_bilans.stop();
              
            // titre
            change_graph_title(jqXHR.nom_epci + "</br> Production d’énergie </br>(" + response[4][0].val + " GWh en " + cfg_anmax_prod + ")");
            
            create_piechart_prod(response[0], "graph1", "Primaires par filières " + cfg_anmax_prod, "GWh");
            create_stacked_barchart_prod(response[3], "graph2");
            create_linechart_prod_primaire(response[2], "graph3", "Evolution des productions primaires (par filières en GWh)");
            
            // Pour les production, on utilise les légendes dynamiques
            // $("#graph1_canvas").css({"height": "231", "width": "212"});
            // $("#graph1_canvas").show();
            $(".graph_container").show();
            $(".graph5").hide();
            
            // Si pas de productions secondaires, alors on supprime le graphique
            if (response[1].length == 0){
                $(".graph4").css({"height": "0%", "width": "100%"});
                // $(".graph4").replaceWith("");
                $(".graph4").hide();
            } else {
                $(".graph4").css({"height": "35%", "width": "100%"});                
                $(".graph4").show();
                create_linechart_prod_secondaire(response[1], "graph4", "Evolution des productions secondaires (par filières en GWh)");
            };     
               
            // create_graph_legend("graph5", 1);
        },
        error: function (request, error) {
            // jqXHR.spinner_bilans.stop();
            console.log("ERROR: create_graphiques_prod()");
            console.log(arguments);
            console.log("Ajax error: " + error);
        },        
    });

};

function create_graphiques_ges(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */

    // On cache les graphiques précédents si existants et on lance le sablier
    $(".graph_container").hide();
    sidebar.show(); 
    spinner_bilans.spin(spinner_bilans_element);    
    
    // Enregistrement de l'EPCI pour recréation éventuelle des graphiques avec un autre polluant
    my_app.siren_epci = siren_epci;
    my_app.nom_epci = nom_epci;
    
    $.ajax({
        type: "GET",
        url: "scripts/graphiques_ges.php",
        dataType: 'json',   
        data: { 
            siren_epci: siren_epci,
            polluant: polluant_actif,
            an: cfg_anmax,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = cfg_anmax;  
            jqXHR.spinner_bilans = spinner_bilans;            
        },        
        success: function(response,textStatus,jqXHR){
             
            jqXHR.spinner_bilans.stop(); 
             
            // Mise en forme des blocs de graphiques pour les émissions
            $(".graph_container").show();
            $(".graph4").css({"height": "10%", "width": "70%"});  
            $(".graph4").show();  
            $(".graph5").show(); 
             
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Emissions annuelles de ' + jqXHR.polls_names[jqXHR.polluant] + "</br>(" + response[4][0].val + " t en " + cfg_anmax + ")");
            
            create_piechart_emi(response[0], "graph1", "Emissions par secteur " + cfg_anmax, "t");
            create_piechart_emi(response[1], "graph2", "Emissions par énergie " + cfg_anmax, "t");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle (émissions indirectes en t)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 2);
 
        },
        error: function (request, error) {
            jqXHR.spinner_bilans.stop();
            console.log("ERROR: create_graphiques_ges()");
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });

};

function export_pdf(){
    /*
    Utilisation de jsPDF et de la fonction toDataURL de charts.js
    NOTE: Valeurs pour canva 1 qui rendaient la meilleure res:
          doc.addImage(canvasImg, 'PNG', 10, 30, 100, 90);  
    NOTE: On peut aussi ne pas indiquer la résolution 
          doc.addImage((canvasImg, 'PNG', x, y, width, height)
    */

    /*
    Démo téléchragement d'une seule image dans navigateur
    
    // Création de l'image + paramètres
    var download = document.createElement('a');     
    download.href = document.getElementById("graph1_canvas").toDataURL();
    download.download = 'test.png';
    download.click();     
  
    // Fonction permettant de créer un évenement de téléchargement
    function fireEvent(obj,evt){
        var fireOnThis = obj;
        if(document.createEvent ) {
            var evObj = document.createEvent('MouseEvents');
            evObj.initEvent( evt, true, false );
            fireOnThis.dispatchEvent( evObj );
        } else if( document.createEventObject ) {
            var evObj = document.createEventObject();
            fireOnThis.fireEvent( 'on' + evt, evObj );
        }
    };
    
    // Déclanchement du téléchargement
    fireEvent(download, 'click');    
    */
    
    // Création du doc
    var doc = new jsPDF;

    // Titre principal
    doc.text(my_app.nom_epci, 10, 10);

    // Logo AtmoSud    
    // Le logo AtmoSud prend trop de temps à transformer en base 64, on stock directement le résultat dans une variable.
    doc.addImage(dataURI_logo_airpaca, 'PNG', 150, 3);  
    
    // Sous titre
    doc.setFontSize(10);
    if (polluant_actif == 'conso') {
        doc.text('Bilan des consommations', 10, 20);
    } else if (polluant_actif == 'prod') {
        doc.text("Bilan des productions d'énergie", 10, 20);        
    } else {
        doc.text('Bilan des émissions de ' + polls_names[polluant_actif], 10, 20);  
    };

    // Ajout pie chart
    var canvasImg = document.getElementById("graph1_canvas").toDataURL();
    doc.addImage(canvasImg, 'PNG', 10, 30);    

    // Ajout bar chart
    var canvasImg = document.getElementById("graph2_canvas").toDataURL();
    doc.addImage(canvasImg, 'PNG', 100, 30);    

    // Ajout line chart
    var canvasImg = document.getElementById("graph3_canvas").toDataURL();
    doc.addImage(canvasImg, 'PNG', 10, 120);    

    // Si on est sur les pructions alors on ajoute une nouvelle page et on crée une variable Y
    if (polluant_actif == 'prod') {
        
        // On ajoute la légende ici
        // var img = new Image();
        // img.src = "img/plots_legend_grandes_filieres.png"; 
        // var dataURI = getBase64Image(img);
        // doc.addImage(dataURI, 'PNG', 10, 240);  
        
        doc.addPage();
        pdf_y = 30;
    } else {
        pdf_y = 240;
    };
    
    // Ajout bar chart inversé (sauf si prod et pas de secondaire)
    console.log($(".graph4").is(":visible"));
    var canvasImg = document.getElementById("graph4_canvas").toDataURL();
    doc.addImage(canvasImg, 'PNG', 10, pdf_y); 
   
    // Ajout légendes au format image   
    if (polluant_actif != 'prod') {
        var img = new Image();
        img.src = "img/plots_legend_secteurs.png"; 
        var dataURI = getBase64Image(img);
        doc.addImage(dataURI, 'PNG', 10, pdf_y + 20);
     
        if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges' || polluant_actif == 'conso') {    
            var img = new Image();
            img.src = "img/plots_legend_energie.png"; 
            var dataURI = getBase64Image(img);
            doc.addImage(dataURI, 'PNG', 10, pdf_y + 40);    
        };
    };
     
    // Conditions d'utilisation à la fin du doc
    doc.addPage();
    var splitTitle = doc.splitTextToSize(cgu, 180);
    doc.text(splitTitle, 10, 10);
     
    // Export
    doc.save('bilan_emissions.pdf');
};

function create_hover_info_bar(){

    hover_info.onAdd = function(map) {
            this._div = L.DomUtil.create('div', 'hover_info'); 
            this._div.innerHTML = "";  // this.update();
            $(this._div).hide();
            return this._div;
    };
    hover_info.update = function(text) {
            $(this._div).show();
            this._div.innerHTML = '<span id="hover_info">' + text + '</span>';
    };
    hover_info.hide = function() {
            $(this._div).hide();
    };    
    hover_info.addTo(map);
};

function creation_couches_epci_polluant(){
    /*
    Boucle sur la liste des polluants,
    crée le layer dans la liste des layers,
    et charge chaque couche mapserver 
    pour le polluant demandé.
    */
    for (i in polls) {
        
        // Si on traite les nox on ajoutera la couche à la carte
        if (polls[i] == "nox") {
            onmap = true;
        } else {
            onmap = false;
        };
        
        // Texte de légende en fonction du polluant
        if (polls[i] == 'conso'){
            legend_text = "Consommations finales " + cfg_anmax + " tep/km&sup2;";
        } else if (polls[i] == 'prod'){
            legend_text = "Productions d'énergie primaire " + cfg_anmax + " MWh/km&sup2;";
        } else if (polls[i] == 'co2' || polls[i] == 'ch4.co2e' || polls[i] == 'n2o.co2e'){
            legend_text = "Emissions indirectes de " + polls_names[polls[i]] + " en " + cfg_anmax + " kg/km&sup2;";
        } else if (polls[i] == 'prg100.3ges'){
            legend_text = polls_names[polls[i]] + " en " + cfg_anmax + "  en kg/km&sup2;";
        } else {
            legend_text = "Emissions de " + polls_names[polls[i]] + " en " + cfg_anmax + " kg/km&sup2;";  
        };
        
        // Ajout de la couche dans la liste des couches avec les bons paramètres
        my_layers["epci_" + polls[i]] = {
            name: "epci_" + polls[i],
            polluant: polls[i], 
            type: "wfs",
            wfs_query: "&REQUEST=GetFeature&TYPENAME=cigale:epci_poll&outputformat=application/json",
            layer: null,
            opacity: 0.5,
            subtitle: "Emissions " + cfg_anmax + " de " + polls[i] + " à l'EPCI",
            onmap: onmap,
            style: {color: "#000000", fillColor: "#D8D8D8", fillOpacity:0.5, weight: 2},
            legend: {},
            legend_text: legend_text,        
        };
        
        // Création de la couche avec le bon filtre
        create_wfs_epci_layers(my_layers["epci_" + polls[i]]);
    };
};

function creation_couches_comm_polluant(){
    /*
    Boucle sur la liste des polluants,
    crée le layer dans la liste des layers,
    et charge chaque couche mapserver 
    pour le polluant demandé.
    */
    for (i in polls) {
        
        // On ne charge jamais les communes dès le départ
        onmap = false;
        
        // Texte de légende en fonction du polluant
        if (polls[i] == 'conso'){
            legend_text = "Consommations finales " + cfg_anmax + " tep/km&sup2;";
        } else if (polls[i] == 'prod'){
            legend_text = "Productions d'énergie primaire " + cfg_anmax + " MWh/km&sup2;";            
        } else if (polls[i] == 'co2' || polls[i] == 'ch4.co2e' || polls[i] == 'n2o.co2e'){
            legend_text = "Emissions indirectes de " + polls_names[polls[i]] + " en " + cfg_anmax + " kg/km&sup2;"; 
        } else if (polls[i] == 'prg100.3ges'){
            legend_text = polls_names[polls[i]] + " en " + cfg_anmax + "  kg/km&sup2;";            
        } else {
            legend_text = "Emissions de " + polls_names[polls[i]] + " en " + cfg_anmax + " kg/km&sup2;";  
        };        
        
        // Ajout de la couche dans la liste des couches avec les bons paramètres
        my_layers["comm_" + polls[i]] = {
            name: "comm_" + polls[i],
            polluant: polls[i], 
            type: "wfs",
            // wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_wfs_" + polls[i] + "&outputformat=geojson",
            wfs_query: "&REQUEST=GetFeature&TYPENAME=cigale:comm_poll&outputformat=application/json",
            layer: null,
            opacity: 0.5,
            subtitle: "Emissions " + cfg_anmax + " de " + polls[i] + " à la commune",
            onmap: onmap,
            style: {color: "#000000", fillColor: "#D8D8D8", fillOpacity:0.5, weight: 2},
            legend: {},
            legend_text: legend_text,        
        };
    };
};

function ajouter_logo(){
    logo.onAdd = function (map) {
        var div = L.DomUtil.create('div', 'info logo');  
        div.innerHTML = '<img src="img/logo-Air-PACA_small.png">'; //  id="img_contributors"
        return div;
    };
    logo.addTo(map);  
};

/* Appel des fonctions */
var map = createMap();
ajouter_logo();
var sidebar = create_sidebar();
var select_list = liste_epci_create(); 
liste_epci_populate();
creation_couches_epci_polluant(); // Couches des ECPI par polluant
creation_couches_comm_polluant(); // Couches des communes par polluant
create_sidebar_template();
create_hover_info_bar();

function tests(){
    console.log("tests()");
};




</script>

</body>
</html>