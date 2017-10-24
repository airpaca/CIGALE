<!DOCTYPE html>
<html lang="en">
<head>

    <!-- 
    TODO: Tester le chargement des différents EPCI avec l'exemple suivant:https://gist.github.com/zross/f0306ca14e8202a0fe73
    FIXME: Impossible de faire marcher le filtre WFS on charge donc toute la couche et filtre avec leaflet ce qui est pas top!
    -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CIGALE - Visualisation</title>
    
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
        
    <!-- Chart.js -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

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
        
        <a href="#" class="list-group-item" id="liste_energies">
        Bilans énergétiques
        </a>

            <a href="#" class="list-group-item hide liste_energies_items" id="conso">
            <!-- <span class="glyphicon glyphicon-chevron-right"></span> -->
            Consommations d'énergie
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
var wms_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map";
var wms_format = 'image/png';
var wms_tr = true;
var wms_attrib = "Air PACA";

var wfs_getcapabilities = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map&SERVICE=WFS&REQUEST=GetCapabilities&VERSION=2.0.0";
var wfs_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "CIGALE/serv.map&SERVICE=WFS&VERSION=2.0.0";

var an_max = 2015;
var polls = ['conso', 'prod', 'so2','nox','pm10','pm2.5','covnm','nh3','co2','ch4.co2e','n2o.co2e','prg100.3ges'];
var polls_names = {
    "conso": "consommations",
    "prod": "productions",
    "so2": "SO<SUB>2</SUB>",
    "nox": "NOx",
    "pm10": "PM10",
    "pm2.5": "PM2.5",
    "covnm": "COVNM",
    "nh3": "NH<SUB>3</SUB>",
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

/* Déclaration des Controles Leaflet */
var legend = L.control({position: 'bottomleft'});
var hover_info = L.control({position: 'topleft'});

/* Extension de chart.js */
Chart.defaults.global.defaultFontColor = '#333';
Chart.defaults.global.defaultFontSize = 13;
Chart.defaults.global.defaultFontFamily = "'Lato', sans-serif";
Chart.defaults.global.defaultFontStyle = "normal";

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
    map.attributionControl.addAttribution('&copy; <a href="http://www.airpaca.org/">Air PACA - 2017</a>');    

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
        url: wfs_address + my_layers_object.wfs_query + "&nom_abrege_polluant=" + my_layers_object.polluant,
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
    
    $.ajax({
        url: wfs_address + my_layers_object.wfs_query + "&nom_abrege_polluant=" + my_layers_object.polluant + "&siren_epci=" + siren_epci,
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
            if (['conso','prod'].includes(my_layers_object.polluant)  == true) {
                the_jenks = calc_jenks(data_filtered, "val", 6, color_scales.energie);
            } else if (['co2','ch4.co2e','n2o.co2e','prg100.3ges'].includes(my_layers_object.polluant)  == true) {
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
    
    if (type == 1) {
        $('.' + div).html('<img align="left" src="img/plots_legend_secteurs.png">');   
    } else {
        $('.' + div).html('<img align="left" src="img/plots_legend_secteurs.png"><img align="left" src="img/plots_legend_energie.png">');   
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

    // var graph_title = 'Répartition sectorielle ' + an_max;

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
                boxWidth: 1 // FIXME: Ne fonctionne pas
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
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');        

    var graph_labels = [];
    for (var i in response) {
        graph_labels.push(response[i].lib_grande_filiere);
    };              

    // var graph_title = 'Répartition sectorielle ' + an_max;

    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var bg_colors = [];
    var bd_colors = [];
    for (var i in response) {
        bg_colors.push(response[i].color_grande_filiere);
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
                boxWidth: 1 // FIXME: Ne fonctionne pas
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

function create_barchart_prod(response, div, poll){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */

    // On est pas arrivé à passer du HTML dans le tool tip du coup on écrit les polluants à l'arrache
    // if (poll == "SO<SUB>2</SUB>") {
        // poll = "SO2";
    // } else if (poll == "NH<SUB>3</SUB>") {
        // poll = "NH3";
    // } else if (poll == "CO<SUB>2</SUB>") {
        // poll = "CO2";
    // } else if (poll == "CH<SUB>4</SUB> eq. CO<SUB>2</SUB>") {
        // poll = "CH4 eq.CO2";
    // } else if (poll == "N<SUB>2</SUB>O eq. CO<SUB>2</SUB>") {
        // poll = "N2O eq.CO2";
    // };
    // poll = "Productions"
    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var graph_labels = [];
    for (var i in response) {
        graph_labels.push(response[i].an);
    };              

    var graph_title = 'Productions totales annuelles (GWh)';

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
    
    var graph_annees = [];
    for (var i in response) {
        if ($.inArray(response[i].an, graph_annees) == -1){
            graph_annees.push(response[i].an);
        };
    };      
    
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].nom_court_secten1, liste_secteurs) == -1){
            liste_secteurs.push(response[i].nom_court_secten1);
            liste_couleurs.push(response[i].secten1_color);
        };
    };    
    
    var datasets = [];
    for (var isect in liste_secteurs) {   
        secteur = liste_secteurs[isect];
        couleur = liste_couleurs[isect];
        
        data = [];
        for (var i in response) { 
            if (response[i].nom_court_secten1 == secteur){
                data.push(response[i].val);
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
    
    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: graph_annees,
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

function create_linechart_prod(response, div, graph_title){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var graph_annees = [];
    for (var i in response) {
        if ($.inArray(response[i].an, graph_annees) == -1){
            graph_annees.push(response[i].an);
        };
    };      
    
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].lib_grande_filiere, liste_secteurs) == -1){
            liste_secteurs.push(response[i].lib_grande_filiere);
            liste_couleurs.push(response[i].color_grande_filiere);
        };
    };    
    
    var datasets = [];
    for (var isect in liste_secteurs) {   
        secteur = liste_secteurs[isect];
        couleur = liste_couleurs[isect];
        
        data = [];
        for (var i in response) { 
            if (response[i].lib_grande_filiere == secteur){
                data.push(response[i].val);
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
    
    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: graph_annees,
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

function create_linechart_prod_tmp(response, div, graph_title){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */    
    $('.' + div).html('<canvas id="' + div + '_canvas"></canvas>');
    
    var graph_annees = [];
    for (var i in response) {
        if ($.inArray(response[i].an, graph_annees) == -1){
            graph_annees.push(response[i].an);
        };
    };      
    
    var liste_secteurs = [];
    var liste_couleurs = [];
    for (var i in response) {
        if ($.inArray(response[i].prod, liste_secteurs) == -1){
            liste_secteurs.push(response[i].prod);
            liste_couleurs.push(response[i].prod_color);
        };
    };    
    
    var datasets = [];
    for (var isect in liste_secteurs) {   
        secteur = liste_secteurs[isect];
        couleur = liste_couleurs[isect];
        
        data = [];
        for (var i in response) { 
            if (response[i].prod == secteur){
                data.push(response[i].val);
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
    
    var graph_data = [];
    for (var i in response) {
        graph_data.push(response[i].val);
    };  

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'line',     
        data: {
            labels: graph_annees,
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
                text: "EPCI = " + response[0].pct_reg + "% de la région en " + an_max,
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
            }
        }
    }); 
};

function create_graphiques(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    
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
            an: an_max,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = an_max;            
        },        
        success: function(response,textStatus,jqXHR){
            
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Bilan des émissions de ' + jqXHR.polls_names[jqXHR.polluant]); 
            
            create_barchart_emi(response[1], "graph2", jqXHR.polls_names[jqXHR.polluant]);
            create_piechart_emi(response[0], "graph1", 'Répartition sectorielle ' + an_max, "t");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle pluriannuelle (t)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 1);
            
            sidebar.show();  
            
        },
        error: function (request, error) {
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
            an: an_max,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = an_max;            
        },        
        success: function(response,textStatus,jqXHR){
                       
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Bilan des consommations');
            
            create_piechart_emi(response[0], "graph1", "Energie finale par secteur en " + an_max, "ktep");
            create_piechart_emi(response[1], "graph2", "Energie finale par énergie en " + an_max, "ktep");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle (énergie finale en ktep)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 2);
            
            sidebar.show();  
        },
        error: function (request, error) {
            console.log("ERROR: create_graphiques_conso()");
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });

};

function create_graphiques_prod(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    
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
            an: an_max,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = an_max;            
        },        
        success: function(response,textStatus,jqXHR){
                       
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Production d’énergie primaire');
            
            create_piechart_prod(response[0], "graph1", "Primaires par grande filière " + an_max, "GWh");
            create_barchart_prod(response[1], "graph2", jqXHR.polls_names[jqXHR.polluant]);
            create_linechart_prod(response[2], "graph3", "Evolution des productions primaires (grandes filières en GWh)");
            create_linechart_prod_tmp(response[3], "graph4", "Evolution des productions primaires / secondaires (GWh)");
            // FIXME: Faut changer la position des div des graphiques et faire une barchart empilée
            
            create_graph_legend("graph5", 2);
            // FIXME: Créer la nouvelle légende
            
            sidebar.show();  
        },
        error: function (request, error) {
            console.log("ERROR: create_graphiques_prod()");
            console.log(arguments);
            console.log("Ajax error: " + error);
            $("#error_tube").show();
        },        
    });

};

function create_graphiques_ges(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    
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
            an: an_max,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;
            jqXHR.polluant = polluant_actif; 
            jqXHR.polls_names = polls_names;
            jqXHR.an = an_max;            
        },        
        success: function(response,textStatus,jqXHR){
                
            // titre
            change_graph_title(jqXHR.nom_epci + '</br> Bilan des émissions de ' + jqXHR.polls_names[jqXHR.polluant]);
            
            create_piechart_emi(response[0], "graph1", "Répartition sectorielle " + an_max, "t");
            create_piechart_emi(response[1], "graph2", "Répartition par énergie " + an_max, "t");
            create_linechart_emi(response[2], "graph3", "Evolution sectorielle (émissions indirectes en t)");
            create_barchart_part(response[3], "graph4");
            
            create_graph_legend("graph5", 2);
            
            sidebar.show();  
        },
        error: function (request, error) {
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
    
    // Sous titre
    doc.setFontSize(10);
    if (polluant_actif == 'conso') {
        doc.text('Bilan des consommations', 10, 20);
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

    // Ajout bar chart inversé
    var canvasImg = document.getElementById("graph4_canvas").toDataURL();
    doc.addImage(canvasImg, 'PNG', 10, 240); 
   
    // Ajout légendes au format image
    var img = new Image();
    img.src = "img/plots_legend_secteurs.png"; 
    var dataURI = getBase64Image(img);
    doc.addImage(dataURI, 'PNG', 10, 260);
 
    if (polluant_actif == 'co2' || polluant_actif == 'ch4.co2e' || polluant_actif == 'n2o.co2e' || polluant_actif == 'prg100.3ges' || polluant_actif == 'conso') {    
        var img = new Image();
        img.src = "img/plots_legend_energie.png"; 
        var dataURI = getBase64Image(img);
        doc.addImage(dataURI, 'PNG', 10, 280);    
    };
     
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
            legend_text = "Consommations finales " + an_max + " tep/km&sup2;";
        } else if (polls[i] == 'prod'){
            legend_text = "Productions d'énergie primaire " + an_max + " MWh/km&sup2;";
        } else if (polls[i] == 'co2' || polls[i] == 'ch4.co2e' || polls[i] == 'n2o.co2e'){
            legend_text = "Emissions indirectes de " + polls_names[polls[i]] + " en " + an_max + " kg/km&sup2;";
        } else if (polls[i] == 'prg100.3ges'){
            legend_text = polls_names[polls[i]] + " en " + an_max + "  en kg/km&sup2;";
        } else {
            legend_text = "Emissions de " + polls_names[polls[i]] + " en " + an_max + " kg/km&sup2;";  
        };
        
        // Ajout de la couche dans la liste des couches avec les bons paramètres
        my_layers["epci_" + polls[i]] = {
            name: "epci_" + polls[i],
            polluant: polls[i], 
            type: "wfs",
            wfs_query: "&REQUEST=GetFeature&TYPENAME=epci_wfs&outputformat=geojson",
            layer: null,
            opacity: 0.5,
            subtitle: "Emissions " + an_max + " de " + polls[i] + " à l'EPCI",
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
            legend_text = "Consommations finales " + an_max + " tep/km&sup2;";
        } else if (polls[i] == 'prod'){
            legend_text = "Productions d'énergie primaire " + an_max + " MWh/km&sup2;";            
        } else if (polls[i] == 'co2' || polls[i] == 'ch4.co2e' || polls[i] == 'n2o.co2e'){
            legend_text = "Emissions indirectes de " + polls_names[polls[i]] + " en " + an_max + " kg/km&sup2;"; 
        } else if (polls[i] == 'prg100.3ges'){
            legend_text = polls_names[polls[i]] + " en " + an_max + "  kg/km&sup2;";            
        } else {
            legend_text = "Emissions de " + polls_names[polls[i]] + " en " + an_max + " kg/km&sup2;";  
        };        
        
        // Ajout de la couche dans la liste des couches avec les bons paramètres
        my_layers["comm_" + polls[i]] = {
            name: "comm_" + polls[i],
            polluant: polls[i], 
            type: "wfs",
            // wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_wfs_" + polls[i] + "&outputformat=geojson",
            wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_wfs&outputformat=geojson",
            layer: null,
            opacity: 0.5,
            subtitle: "Emissions " + an_max + " de " + polls[i] + " à la commune",
            onmap: onmap,
            style: {color: "#000000", fillColor: "#D8D8D8", fillOpacity:0.5, weight: 2},
            legend: {},
            legend_text: legend_text,        
        };
    };
};

/* Appel des fonctions */
var map = createMap();
var sidebar = create_sidebar();
var select_list = liste_epci_create(); 
liste_epci_populate();
// create_wms_layer(my_layers.epci_wms); // Fond de carte des EPCI WMS
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