<!DOCTYPE html>
<html lang="en">
<head>

    <!-- 
    TODO: Tester le chargement des différents EPCI avec l'exemple suivant:https://gist.github.com/zross/f0306ca14e8202a0fe73
    FIXME: Impossible de faire marcher le filtre WFS on charge donc toute la couche et filtre avec leaflet ce qui est pas top!
    -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Air PACA ORECA V3E</title>
    
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

    <!-- Chart.js -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

    <!-- Selectize.js -->
    <script src="libs/selectize.js/selectize.js" type="text/javascript"></script>
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
    <link rel="stylesheet" href="style.css"/>

    <!-- Config -->
    <script type="text/javascript" src="config.js"></script>
 
</head>
    
<!------------------------------------------------------------------------------ 
                                    Body
------------------------------------------------------------------------------->
<body>

<!-- ......... Side bar -->
<div id="container">
    <div id="sidebar-left">
        <div class="list-group">
        
            <img class="img-titre" align="middle" src="img/logo-Air-PACA_small.png">
        
            <h1>V3E</h1>
            <h2>Visualisations et Extractions Emissions Energie</h2>
                
            <!-- Sélection de l'emprise géographique qui déclanche la fonction submitForm() -->
                <select id="geonfo" placeholder="Echelon administratif ..."></select>
                <p>
                    <a href="javascript:liste_epci_clean();" class="btn btn-default" role="button">Réinitialiser</a> 
                    <!-- <a href="javascript:liste_epci_submit();" class="btn btn-primary" role="button">Valider</a> -->
                </p>
            
            <!-- Liste des données sélectionnables qui s'affiche après sélection d'un EPCI -->
            <a href="#" class="list-group-item active" id="liste_polluants">
            Polluants atmosphériques
            </a>

                <a href="#" class="list-group-item liste_polluants_items" id="nox">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de NOx
                </a>

                <a href="#" class="list-group-item liste_polluants_items" id="pm10">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de PM10
                </a>

                <a href="#" class="list-group-item liste_polluants_items" id="pm25">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de PM2.5
                </a>            

                <a href="#" class="list-group-item liste_polluants_items" id="cov">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de COV
                </a>  

                <a href="#" class="list-group-item liste_polluants_items" id="so2">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de SO2
                </a>  

                <a href="#" class="list-group-item liste_polluants_items" id="nh3">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de NH3
                </a>              
            
            <a href="#" class="list-group-item" id="liste_energies">
            Bilans énergétiques
            </a>

                <a href="#" class="list-group-item hide liste_energies_items" id="consos">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Consommations d'énergie
                </a>

                <a href="#" class="list-group-item hide liste_energies_items" id="prod">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Productions d'énergie
                </a>                        

            <a href="#" class="list-group-item" id="liste_ges">
            Gazs à Effet de Serre
            </a>

                <a href="#" class="list-group-item hide liste_ges_items" id="ges">
                <span class="glyphicon glyphicon-chevron-right"></span>
                Emissions de GES
                </a>            
        
        </div>
        
        <!-- Bouton de dev pour les tests -->
        <a href="javascript:tests();" class="btn btn-default" role="button">Tests dev</a>
        
    </div>
    <!-- Leaflet sidebar -->
    <div id="sidebar">
        <h1>leaflet-sidebar</h1>
    </div>    
 
    <!-- Element carte -->
    <div id="map"></div>             
    
</div>

<!------------------------------------------------------------------------------ 
                                    Map script
------------------------------------------------------------------------------->
<script type="text/javascript">

/* Variables générales */
var wms_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "V3E/serv.map";
var wms_format = 'image/png';
var wms_tr = true;
var wms_attrib = "Air PACA";

var wfs_getcapabilities = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "V3E/serv.map&SERVICE=WFS&REQUEST=GetCapabilities&VERSION=2.0.0";
var wfs_address = cfg_host + "cgi-bin/mapserv?map=" + cfg_root + "V3E/serv.map&SERVICE=WFS&VERSION=2.0.0";

var my_layers = {
    epci_wfs: {
        layer: null,
        type: "wfs",
        wfs_query: "&REQUEST=GetFeature&TYPENAME=epci_wfs&outputformat=geojson",
        opacity: 0.5,
        subtitle: "EPCI PACA 2017",
        onmap: true,
        style: {color: "#000000", fillColor: "#D8D8D8", fillOpacity:0.5, weight: 2},
        legend: {},
    },  
    comm_nox: {
        layer: null,
        type: "wfs",
        wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_nox&outputformat=geojson",
        // wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_nox&outputformat=geojson&FILTER=<Filter><Equals><PropertyName>siren_epci_2017</PropertyName><Literal>200039931</Literal></Equals></Filter>",
        // wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_nox&outputformat=geojson&Filter=<Filter><PropertyIsEqualTo><PropertyName>siren_epci_2017</PropertyName><Literal>200039931</Literal></PropertyIsEqualTo></Filter>",
        // wfs_query: "&REQUEST=GetFeature&TYPENAME=comm_nox&outputformat=geojson&Filter=<Filter><PropertyIsLike wildcard='*' singleChar='.' escape='!'><PropertyName>siren_epci_2017</PropertyName><Literal>*200039931*</Literal></PropertyIsLike></Filter>",
        opacity: 0.5,
        subtitle: "Emissions de NOx par communes",
        onmap: true,
        style: {color: "#000000", fillColor: "#D8D8D8", fillOpacity:0.5, weight: 2},
    },         
};

var my_app = {
    sidebar: {displayed: false},
}

/* Déclaration des Controles Leaflet */
var legend = L.control({position: 'bottomleft'});
var hover_info = L.control({position: 'topleft'});


/* Fonctions */
$(function() { /* Gestion des listes */

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
});

function createMap(){
    /* Création de la carte */
    var map = L.map('map', {layers: [], zoomControl:false}).setView([43.9, 6.0], 8);    
    map.attributionControl.addAttribution('mes2camp &copy; <a href="http://www.airpaca.org/">Air PACA - 2017</a>');    

    /* Chargement des fonds carto */    
    var Hydda_Full = L.tileLayer('http://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png', {
        maxZoom: 18,
        opacity: 0.5,
        attribution: 'Fond de carte &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    });
    Hydda_Full.addTo(map);

    // Prise en compte du click sur la carte 
    map.on('click', function(e)  {   
        
        // Cache la couche des communes si visible
        sidebar.hide();
        if (my_layers.comm_nox.layer != null) {
            map.removeLayer(my_layers.comm_nox.layer);
        };
        
        // Affichage des EPCI et regénération de la légende
        my_layers.epci_wfs.layer.addTo(map);
        generate_legend("Emissions de NOx / an (t)", my_layers.epci_wfs.legend.bornes, my_layers.epci_wfs.legend.colors);
        map.fitBounds(my_layers.epci_wfs.layer.getBounds());
    });
    
    return map;
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
            { geoid: "reg|93", geonm: "PACA", geotyp: "Région" },
        ],
        render: {
            option: function(item, escape) {
                return "<div><span class='form_geonm'>" + escape(item.geonm) + "</span><br /><span class='form_geotyp'>" + escape(item.geotyp) + "</span></div>";
            },
            item: function(item, escape) {
                
                // Passage de l'EPCI à la commune
                epci2comm(escape(item.geoid), escape(item.geonm));
                
                return "<div><span class='form_geonm'>" + escape(item.geonm) + "</span> <span class='form_geotyp'>(" + escape(item.geotyp) + ")</span></div>";
                
            }
        }
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
        data: {
            pg_host:cfg_pg_host,
            pg_bdd:cfg_pg_bdd, 
            pg_lgn:cfg_pg_lgn, 
            pg_pwd:cfg_pg_pwd,            
        },
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
    if (my_layers.comm_nox.layer != null) {
        map.removeLayer(my_layers.comm_nox.layer);
    };  

    // Ajout de la couche des EPCI sur la carte et zoom max extent
    my_layers.epci_wfs.layer.addTo(map); 
    map.fitBounds(my_layers.epci_wfs.layer.getBounds());
    
    // Cache la sidebar
    sidebar.hide();
};

function liste_epci_submit(){
    /*
    Une fois validé, on récupère le code de l'EPCI et on zoom dessus, affichant les graphiques
    */  

    // Récupération des valeurs du formulaire
    var liste_siren_epeci = select_list[0].selectize.getValue();
    var liste_nom_epeci = select_list[0].selectize.options[liste_siren_epeci].geonm;

    // Passage de l'EPCI à la commune
    epci2comm(liste_siren_epeci, liste_nom_epeci);

};

function epci2comm(siren_epeci, nom_epeci){
    
    // Si la couche des communes est déjà affichée on la supprime
    if (my_layers.comm_nox.layer != null) {
        map.removeLayer(my_layers.comm_nox.layer);
    };    
    
    // Zoom sur l'EPCI en le retrouvant dans les objets du layer epci
    for (i in my_layers.epci_wfs.layer._layers) {
        if (my_layers.epci_wfs.layer._layers[i].feature.properties.siren_epci_2017 == siren_epeci) {
            map.fitBounds(my_layers.epci_wfs.layer._layers[i]._bounds, {paddingBottomRight: [800, 0]});
        };
    };
    
    // Affichage de la couche des communes
    create_wfs_comm_layers(my_layers.comm_nox, siren_epeci); 
    
    // Retrait de la couche EPCI
    map.removeLayer(my_layers.epci_wfs.layer);    
    // my_layers.epci_wfs.layer.setStyle({fillOpacity:0.0});
    
    // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
    create_graphiques(siren_epeci, nom_epeci);       
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
        name: 'epci',
        layers: 'epci',
        format: wms_format,
        transparent: wms_tr,
        opacity: 0.5,
        subtitle: "EPCI PACA 2017"
    });

    if (my_layers_object.onmap == true) {
        my_layers_object.layer.addTo(map);    
    };
};

function calc_jenks(data, field, njenks){
    /*
    Calcul à partir d'une réponse geojson [data] les classes et couleurs de 
    jenks sur un champ [field] à partir d'une nombre de classes [njenks].
    Renvoie bornes [min, ..., max] et couleurs de ces bornes.

    Attention, si le nombre de classes jenks demandé est trop faible par rapport au nombre
    de valeurs, alors on réduit le nombre de classes
    
    Ex:
    the_jenks = calc_jenks(data, "superficie", 3);
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
    var color_x = chroma.scale(['#f9ebea', '#cd6155', '#cb4335']).colors(njenks);
    
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
        url: wfs_address + my_layers_object.wfs_query,
        datatype: 'json',
        jsonCallback: 'getJson',
        success: function (data) {
        
            // Calcul des statistiques
            the_jenks = calc_jenks(data, "val", 6);
           
            // Création de l'objet
            my_layers_object.layer = L.geoJSON(data, {
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
                        create_wfs_comm_layers(my_layers.comm_nox, feature.properties["siren_epci_2017"]); 
                        
                        // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
                        create_graphiques(feature.properties["siren_epci_2017"], feature.properties["nom_epci_2017"]);                     
                        
                    });                    
                },                 
            });     

            if (my_layers_object.onmap == true) {
                
                // Ajout de la couche sur la carte
                my_layers_object.layer.addTo(map);
                
                // Zoom sur la couche
                map.fitBounds(my_layers_object.layer.getBounds());
                
                // Création de la légende
                generate_legend("Emissions de NOx / an (t)", the_jenks.bornes, the_jenks.colors);
                
                // Enregistrement des paramètres de la légende pour la recréer
                my_layers_object.legend = {bornes: the_jenks.bornes, colors: the_jenks.colors};
            };
        }
    });    
};

function create_wfs_comm_layers(my_layers_object, siren_epci){
    /*
    Crée un layer wfs à partir des couches disponibles 
    dans le mapfile et l'insert dans l'objet layer déclaré
    en argument.
    Ex: create_wfs_comm_layers(my_layers.comm_nox);
    */     
    $.ajax({
        url: wfs_address + my_layers_object.wfs_query,
        datatype: 'json',
        jsonCallback: 'getJson',
        success: function (data) {
        
            // Calcul des statistiques uniquement sur les valeurs répondant au filtre
            data_filtered = {features: []};
            for (ifeature in data.features) {
                if (data.features[ifeature].properties.siren_epci_2017 == siren_epci) {
                    data_filtered.features.push(data.features[ifeature]);
                };
            };
            
            the_jenks = calc_jenks(data_filtered, "val", 6);
           
            // Création de l'objet
            my_layers_object.layer = L.geoJSON(data, {
                style: function(feature) {
                    
                    // Récupération du style de l'objet et remplissage avec la bonne couleur
                    the_style = my_layers_object.style;
                    the_style.fillColor = find_jenks_color(the_jenks, feature.properties.val);
                    return the_style;
                },
                filter: function(feature, layer) {
                    if (feature.properties["siren_epci_2017"] == siren_epci) {
                        return true;
                    };
                },
                onEachFeature: function (feature, layer) {
                    
                    // Ajout d'un popup
                    // var html = "<div id='popup'>" + feature.properties["nom_comm"] +"<br>" + parseFloat(feature.properties["val"]).toFixed(1) + " t/an</div>";
                    var html = "<div id='popup'>Accéder aux données tabulaires?</div>";                    
                    layer.bindPopup(html);

                    // Prise en compte du hover
                    layer.on('mouseover', function(){
                        layer.setStyle({weight: 4});
                        // this.openPopup();
                        hover_info.update(feature.properties["nom_comm"] + ": " + parseFloat(feature.properties["val"]).toFixed(1) + " t/an</div>");
                    });
                    layer.on('mouseout', function(){
                        layer.setStyle({weight: 2});
                        // this.closePopup();
                        hover_info.hide();
                    });

                    // Prise en compte du cklic
                    layer.on('click', function(){
                        
                        return null;
                        
                        // Zoom sur la couche
                        // map.fitBounds(layer._bounds, {paddingBottomRight: [800, 0]});

                        // Récupération de l'id epci et lancement de la fonction d'affichage des graphiques                       
                        // create_graphiques(feature.properties["siren_epci_2017"], feature.properties["nom_epci_2017"]);                     
                        
                    });                    
                },                 
            });     

            if (my_layers_object.onmap == true) {
                
                // Ajout de la couche sur la carte
                my_layers_object.layer.addTo(map);
                
                // Création de la légende
                generate_legend("Emissions de NOx / an (t)", the_jenks.bornes, the_jenks.colors);
            };
        }
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
                from = grades[i].toFixed(1);
            }
            to = grades[i + 1].toFixed(1);            
            
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
        <div class="btn_export"><img class="img-btn-export" src="img/pdf_download.png" onclick="export_pdf();"></div>\
        <div class="graph1">graph1</div>\
        <div class="graph2">graph2</div>\
        <div class="graph3">graph3</div>\
        <div class="graph4">graph4</div>\
    </section>\
    ';
    sidebar.setContent(sidebarContent);      
};

function change_graph_title(the_title){
    $('.graph_title').html(the_title + '</br> Bilan des émissions de NOx');    
};

function create_piechart_emi(response, div){
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

    var graph_title = 'Répartition sectorielle';

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
                borderWidth: 1
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true,
                fontSize: 15,
                text: graph_title
            },
            legend: {
                position: 'bottom',
                display: true,
                labels: {fontSize: 10,},
                boxWidth: 1 // FIXME: Ne fonctionne pas
            },
        }
    });
    
};

function create_barchart_emi(response, div){
    /*
    Création d'un graphique bar à partir de:
    @response - Réponse json de la requête ajax
    @div - Classe de l'élement auquel rattacher le graph 
    */
    
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
        bg_colors.push('#02fcf2');
        bd_colors.push('#02fcf2');
    };  

    var ctx = document.getElementById(div + "_canvas");
    var graph = new Chart(ctx, {
        type: 'bar', // 'horizontalBar',          
        data: {
            labels: graph_labels,
            datasets: [{
                label: 'NO2',
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
                fontSize: 15,
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

function create_linechart_emi(response, div){
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
                fontSize: 15,
                text: "Evolution sectorielle pluriannuelle (t)"
            },
            legend: {
                position: 'bottom',
                display: true,
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
                label: 'LABEL A DEFINIR',
                data: [response[0].epci, response[0].reg],
                backgroundColor: '#02fcf2', // bg_colors,
                borderColor: '#02fcf2', // bd_colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive:true,
            maintainAspectRatio: false,
            title: {
                display: true,
                fontSize: 15,
                text: "EPCI = " + response[0].pct_reg + "% de la région",
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

function create_graphiques(siren_epci, nom_epci){
    /*
    Création des graphiques 
    */
    $.ajax({
        type: "GET",
        url: "scripts/graphiques.php",
        dataType: 'json',   
        data: {
            pg_host:cfg_pg_host,
            pg_bdd:cfg_pg_bdd, 
            pg_lgn:cfg_pg_lgn, 
            pg_pwd:cfg_pg_pwd,  
            siren_epci:siren_epci,
        },    
        beforeSend:function(jqXHR, settings){
            jqXHR.siren_epci = siren_epci;  
            jqXHR.nom_epci = nom_epci;               
        },        
        success: function(response,textStatus,jqXHR){
            change_graph_title(jqXHR.nom_epci);
            create_barchart_emi(response[1], "graph2");
            create_piechart_emi(response[0], "graph1");
            create_linechart_emi(response[2], "graph3");
            create_barchart_part(response[3], "graph4");
            
            sidebar.show();  
        },
        error: function (request, error) {
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
    doc.text("Métropole d'Aix-Marseille-Provence", 10, 10);
    
    // Sous titre
    doc.setFontSize(10);
    doc.text('Bilan des émissions de NOx', 10, 20);

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

/* Appel des fonctions */
var map = createMap();
var sidebar = create_sidebar();
var select_list = liste_epci_create(); 
liste_epci_populate();
create_wfs_epci_layers(my_layers.epci_wfs);
create_sidebar_template();
create_hover_info_bar();


function tests(){
    console.log("tests");
    
    console.log(my_layers.epci_wfs.layer);
    // if (a == 0){
        my_layers.epci_wfs.layer.setStyle({fillOpacity:0.0});
        // a = 1;
    // } else {
        // my_layers.epci_wfs.layer.resetStyle();
        // a = 0;
    // };
};





</script>

</body>
</html>