<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Consultation d’Inventaires Géolocalisés de qualité de l’Air et de L’Energie - Extractions">
    <meta name="author" content="Air PACA">    
    
    <title>CIGALE - Extraction</title>
    
    <!-- JQuery 3.2.1 -->
    <script src="libs/jquery/jquery-3.2.1.min.js"></script>    

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

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 

    <!-- bootstrap-toc -->
    <link rel="stylesheet" href="libs/bootstrap-toc-gh-pages/dist/bootstrap-toc.min.css">
    <script src="libs/bootstrap-toc-gh-pages/dist/bootstrap-toc.min.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="methodo.css"/>

    <!-- Config -->
    <script type="text/javascript" src="config.js"></script>
    
</head>
    
<body data-spy="scroll" data-target="#toc">


    <!-- Corps de la page -->
    <div class="row">
        
        <!-- Zone gauche de sélection des documents et navigation -->
        <div class="col-md-3" id="zone-select">
            
            <!-- Titre de la page -->
            <img class="img-title" src="img/document-flat.hover.png" border="0" width="140">  <!-- orig: width="180" -->
            <h3 class="centered" data-toc-skip>Méthodologie</h3>        
            
            <!-- TOC -->
            <div class="row">
                <nav id="toc" data-toggle="toc"></nav>            
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
                    <a href="extraction.php"><img class="img-menus" id="img-extract" src="img/csv-icon.png" border="0" width="80"></img></a>      
                </div>			
            </div>
        
        
        </div>
        
        <!-- Zone droite affichage des documents -->
        <div class="col-md-9" id="zone-display">
                       
            <h1>Nomenclatures</h1>
                <h2 data-toc-text="SECTEN">SECTEN</h2>
                    <p>
                    La restitution des inventaires de consommations d’énergie et d’émissions de polluants est 
                    réalisée selon la nomenclature SECTEN (SECTeurs économiques et ENergie), afin d’être en 
                    cohérence avec l’inventaire national publié chaque année par le CITEPA.
                    </p>
                    <p>
                    Le format SECTEN regroupe 7 secteurs principaux et 1 secteur intégrant les émetteurs non inclus:</br>
                    <ul>
                    <li>Extraction, transformation et distribution d’énergie</li>
                    <li>Industrie manufacturière, traitement des déchets, construction</li>
                    <li>Résidentiel, tertiaire, commercial, institutionnel</li>
                    <li>Agriculture, sylviculture et aquaculture</li>
                    <li>Transport routier</li>
                    <li>Modes de transports autres que routier</li>
                    <li>UTCF (utilisation des terres, leurs changements et la forêt)</li>
                    <li>Emetteurs non inclus dans le total France</li>
                    </ul>
                    </p>
                    <p> 
                    Le secteur UTCF n’est actuellement pas calculé dans l’inventaire Air PACA.</br>                    
                    La catégorie « hors total » regroupe les émissions non prises en compte dans les totaux nationaux. 
                    Concernant les émissions de gaz à effet de serre direct, il s’agit des émissions du trafic maritime aérien et 
                    international ainsi que des sources non anthropiques). Pour les autres substances, il s’agit des mêmes 
                    émissions auxquelles sont ajoutées les émissions de la phase croisière du trafic aérien domestique et les émissions 
                    des sources biotiques agricoles.
                    </p>
                    <p>
                    Ce format de rapportage SECTEN est adaptée à des inventaires orientés « sources », 
                    c’est-à-dire lorsque les émissions sont comptabilisées sur leur lieu de rejet. 
                    L’inventaire énergétique étant réalisé en même temps que l’inventaire des émissions, 
                    le système de rapportage a dû être adapté, afin d’inclure les consommations d’énergie 
                    secondaires (électricité et chaleur) pour lesquelles les émissions atmosphériques sont 
                    rejetées sur le lieu de production et non sur leur lieu de consommation. 
                    Son principe est synthétisé dans le schéma suivant :
                    <p>
                    <img src="img/diagramme_inv.png" border="0">
                    </p>

            <h1>Secret statistique</h1>
            <p>
            Les données de consommations et d’émissions déclarées peuvent entrer dans le cadre du secret statistique. 
            Les critères du secret statistique sont calculés au niveau communal par catégorie d’énergie et activité SECTEN1. 
            Les données qui, après vérification sont soumises au secret statistique ne sont pas utilisées dans la création des 
            cartes quel que soit le niveau d’agrégation et ne peuvent pas non plus être exportées.
            </p>                    

            <h1 data-toc-text="Conditions d'utilisation">Conditions d'utilisation des données</h1>
                <h2 data-toc-text="Diffusion">Conditions de diffusion</h2>
                    <p>
                    Les données fournies dans le cadre de l'application CIGALE peuvent librement être diffusées, 
                    publiées ou utilisées dans le cadre de travaux, d'études ou d'analyse avec les conditions suivantes:
                    </p>
                    <p>
                    - Toute utilisation des données brutes issues de la base de données Energ'air devra faire référence à 
                    l'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) et à Air PACA en ces termes :
                    'Source: Base de donnes CIGALE - Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) 
                    Provence-Alpes-Côte d'Azur / inventaire Air PACA'
                    </p>
                    <p>
                    - Toute utilisation de données retravaillées par l'utilisateur final à partir de données 
                    brutes issues de l'application CIGALE devra faire référence à l'Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) et à Air PACA en ces termes :
                    'Source: *Utilisateur final* d'après Base de donnes Energ'air - Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) Provence-Alpes-Côte d'Azur / inventaire Air PACA'
                    </p>
                    <p>
                    - Sur demande, l'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) Provence-Alpes-Côte d'Azur et Air PACA 
                    mettent à disposition les méthodes d'exploitation des données mises en œuvre.
                    </p>
                    <p>
                    - Les données contenues dans ce document restent la propriété de l'Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) Provence-Alpes-Côte dAzur et d'Air PACA.
                    </p>
                    <p>
                    - L'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) Provence-Alpes-Côte dAzur et 
                    Air PACA peuvent rediffuser ce document à d'autres destinataires.        
                    </p>    
            
            <h1>Contact</h1>
            <p>
            Pour nous faire remonter une erreur, incohérence ou pour plus d'information sur les données 
            présentées, vous pouvez envoyer un mail à <a href="mailto:romain.souweine@airpaca.org">romain.souweine@airpaca.org</a>
            </p>
            <p>
            Responsables de la publication: Air PACA - Gaëlle Luneau, Matthieu Moynet, Romain Souweine
            </p>            
        </div>
        
    </div>    
      
    

<script type="text/javascript">


/* Navigation entre les menus */
$("#img-extract").hover(function(){
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

</script>

</body>
</html>