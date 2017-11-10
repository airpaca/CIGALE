<!-- Doctype HTML5 -->
<!DOCTYPE html>
<html lang="en">
<html dir="ltr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Méthodologie et documents de référence">
    <meta name="author" content="Air PACA">    
    
    <title>CIGALE - Méthodo</title>
    
    <link rel="icon" type="image/png" href="img/cicada.png">
    
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
            
            <br/>
            <img src="img/logo-Air-PACA_small.png">
            
			<h1>Définitions et équivalences énergétiques</h1>
                <h2 data-toc-text="Consommation d'énergie finale/primaire">Consommation d'énergie finale/primaire</h2>
					<p>
                    La <b>consommation d’énergie primaire</b> correspond à l’énergie non transformée consommée sur un territoire. 
					Elle intègre les consommations du secteur de la Production/transformation d’énergie mais pas les consommations 
					d’énergies secondaires telles que l’électricité ou la chaleur. 
					Les émissions de polluants associées sont uniquement des émissions directes (SCOPE 1).
					</p>	
					<p>	
					La <b>consommation d’énergie finale</b> représente toute l’énergie consommée par les utilisateurs finaux. 
					Elle intègre les consommations d’électricité et de chaleur (qui sont des énergies secondaires) 
					mais pas les consommations énergétiques du secteur de la Production/transformation d’énergie 
					(considérées comme de l’énergie primaire). Les émissions de gaz à effet de serre résultantes 
					intègrent donc les émissions indirectes de CO<sub>2</sub> liées à la consommation d’électricité (SCOPE 1 et 2).
					</p>
			    <h2 data-toc-text="Production d'énergie primaire">Production d'énergie primaire</h2>
					<p>
					L’énergie primaire est l’ensemble des produits énergétiques avant transformation. 
					La production d’énergie primaire consiste en l’extraction de combustibles fossiles (pétrole brut, gaz naturel, 
					combustibles minéraux solides, etc.), la production d’énergie nucléaire (chaleur générée par la fission des atomes) 
					ainsi que la production d’énergie renouvelable (énergie solaire thermique, photovoltaïque, hydraulique, éolienne, 
					géothermique, biomasse, etc.). 
					</p>
				<h2 data-toc-text="Emissions de gaz à effet de serre : PRG">Emissions de gaz à effet de serre : PRG</h2>
					<p>
                    Le <b>Pouvoir de Réchauffement Global (PRG)</b> est un indicateur défini pour comparer l’impact de chaque 
					gaz à effet de serre sur le réchauffement global, sur une période choisie (généralement 100 ans). 
					Il est calculé à partir des PRG de chaque substance et est exprimé en équivalent CO<sub>2</sub> (CO<sub>2</sub>e). 
					</p>	
					<p>	
					Par définition, le PRG du CO<sub>2</sub> est toujours égal à 1. Les coefficients utilisés dans l’inventaire 
					sont ceux établis lors de la Conférence des Parties de 1995 et appliqués dans le cadre du protocole 
					de Kyoto (CO<sub>2</sub>=1, CH<sub>4</sub>=21, N<sub>2</sub>O=310). Les gaz fluorés ne sont actuellement pas calculés dans l’inventaire.
					</p>
				<h2 data-toc-text="Equivalences énergétiques">Tableau des équivalences énergétiques</h2>
					<p>
                    <img src="img/Tableau_eq_energetique.JPG" border="0" width="97%">
                    </p>
			
            <h1>Nomenclatures d'activités</h1>
			    <h2 data-toc-text="Nomenclature SNAP">Nomenclature SNAP</h2>
                    <p>
                    Les inventaires d’émissions sont établis selon la nomenclature <b>SNAP</b> (Selected Nomenclature for Air Pollution, EMEP/CORINAIR 1997). 
					Cette nomenclature a évolué depuis 1997, pour permettre la prise en compte de nouvelles activités. Elle est décrite dans l’
					<a href="http://www.citepa.org/images/III-1_Rapports_Inventaires/OMINEA_2017.pdf">OMINEA 
					(Organisation et méthodes des inventaires nationaux des émissions atmosphériques en France)</a>.
                    </p>
                <h2 data-toc-text="Nomenclature SECTEN">Nomenclature SECTEN</h2>
                    <p>
                    La restitution des inventaires de consommations d’énergie et d’émissions de polluants est 
                    réalisée selon le premier niveau de la nomenclature <b>SECTEN</b> (SECTeurs économiques et ENergie), afin d’être en 
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
                    <a href="https://www.citepa.org/images/III-1_Rapports_Inventaires/SECTEN/CITEPA-liste-sources-2017-d.pdf">
					Correspondance SNAP / SECTEN</a>
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
                    <img src="img/170724_schema_primaire_finale.jpg" border="0" width="97%">
                    </p>	
				<h2 data-toc-text="Secteur UTCF">Secteur UTCF</h2>
					<p> 
                    L'<b>utilisation des terres, leur changement et la forêt</b> est à la fois un puits et une source d'émission de 
					CO<sub>2</sub>, CH<sub>4</sub> et N<sub>2</sub>O. L'UTCF couvre la récolte et l'accroissement forestier, la conversion des forêts (défrichement) 
					et des prairies ainsi que les sols dont la composition en carbone est sensible à la nature des activités 
					auxquelles ils sont dédiés (forêt, prairies, terres cultivées). Ce secteur n’est actuellement pas calculé 
					dans l’inventaire Air PACA.                  
                    </p>	
				<h2 data-toc-text="Emetteurs non inclus">Emetteurs non inclus</h2>
					<p> 
                    La catégorie <b>Emetteurs non inclus</b> regroupe les émissions non prises en compte dans les totaux nationaux. 
					Concernant les émissions de gaz à effet de serre direct, il s’agit des émissions du trafic maritime aérien 
					et international ainsi que des sources non anthropiques. Pour les autres substances, il s’agit des mêmes 
					émissions auxquelles sont ajoutées les émissions de la phase croisière du trafic aérien domestique, les 
					émissions des sources biotiques agricoles et les émissions de particules issues de la remise en suspension 
					(afin d’éviter les doubles comptes).                  
                    </p>
			
			<h1>Nomenclatures de combustibles</h1>
				<h2 data-toc-text="Nomenclature NAPFUE">Nomenclature NAPFUE</h2>
                    <p>
					Les activités de combustion sont distinguées dans l’inventaire par un niveau supplémentaire en intégrant 
					la nomenclature NAPFUE (Nomenclature for Air Pollution of FUEls). Cette nomenclature est décrite dans 
					l'<a href="http://www.citepa.org/images/III-1_Rapports_Inventaires/OMINEA_2017.pdf">OMINEA</a>.
					</p>
				<h2 data-toc-text="Format de restitution">Format de restitution</h2>
                    <p>
					Pour un rendu plus synthétique des données, l’ensemble de ces énergies est rassemblé selon une nomenclature simplifiée, selon 9 catégories :</br>
                    <ul>
                    <li>Aucune énergie : cette catégorie permet de distinguer toute émission de polluants atmosphériques ou de GES non énergétique ;</li>
                    <li>Gaz naturel ;</li>
                    <li>Produits pétroliers ;</li>
                    <li>Combustibles minéraux solides ;</li>
                    <li>Bois énergie ;</li>
                    <li>Chaleur et froid : les émissions associées sont des émissions indirectes de CO<sub>2</sub> ;</li>
                    <li>Electricité : les émissions associées sont des émissions indirectes de CO<sub>2</sub> ;</li>
                    <li>Autres énergies renouvelables ;</li>
					<li>Autres non renouvelables.</li>
                    </ul>
					</p>
				<h2 data-toc-text="Détails des autres combustibles">Détails des autres combustibles</h2>
                    <p>
					Les catégories <b>Autres énergies renouvelables</b> et <b>autres énergies non renouvelables</b> sont constituées des énergies suivantes :</br>
                    </p>
					<p>
					- Autre énergie renouvelable : Ordures ménagères (organiques), déchets agricoles, farines animales, boues d’épuration, biocarburant, 
					liqueur noire, bio-alcool, biogaz, gaz de décharge, chaleur issue du solaire thermique et de la géothermie.
                    </p>
					<p>
					- Autre énergie non renouvelable : Ordures ménagères (non organiques), déchets industriels solides, pneumatiques, plastiques, 
					solvants usagés, gaz de cokerie, gaz de haut fourneau, mélange de gaz sidérurgiques, gaz industriel, gaz d’usine à gaz, gaz d’aciérie, hydrogène.
					</p>
					
            <h1>Secret statistique</h1>
            <p>
            Certaines données sont soumises au secret statistique et ne peuvent être publiées. Une donnée est considérée 
			comme confidentielle lorsque moins de 3 établissements sont à l’origine de cette donnée ou qu’un seul établissement 
			contribue à 85 % ou plus de cette donnée (<a href="https://www.insee.fr/fr/information/1300624">définition INSEE du secret statistique</a>).
			</p>
			<p>
			Le secret statistique est calculé par commune, par secteur d’activité et par catégorie d’énergie pour les consommations. 
			Si une consommation est confidentielle, toutes les émissions concernées ne sont également pas publiées.
			</p>
			<p>
			Dans le souci de publier un maximum de données, plusieurs traitements sont effectués :</br>
			</p>
			<p>
			- Pas de donnée confidentielle à l’échelle de la région et des départements ;</br>
			- A l’échelle de l’EPCI, lorsque les critères du secret statistique sont respectés et 
			que le secret statistique concerne plusieurs communes (pour un même secteur d’activité et 
			une même catégorie d’énergie), les données globales de l’EPCI peuvent être diffusées (impossibilité de reconstitution des données communales).
            </p>                    

            <h1 data-toc-text="Conditions d'utilisation">Conditions d'utilisation des données</h1>
                    <p>
                    Les données fournies dans le cadre de l'application CIGALE peuvent librement être diffusées, 
                    publiées ou utilisées dans le cadre de travaux, d'études ou d'analyse avec les conditions suivantes:
                    </p>
                    <p>
                    - Toute utilisation des données brutes issues de la base de données CIGALE devra faire référence à 
                    l'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) et à Air PACA en ces termes :
                    'Source: Base de donnes CIGALE - Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) 
                    Provence-Alpes-Côte d'Azur / inventaire Air PACA'
                    </p>
                    <p>
                    - Toute utilisation de données retravaillées par l'utilisateur final à partir de données 
                    brutes issues de l'application CIGALE devra faire référence à l'Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) et à Air PACA en ces termes :
                    'Source: *Utilisateur final* d'après la base de données CIGALE - Observatoire Régional de l'Energie, 
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
            
			<h1>Liens utiles</h1>
			<p>
			<a href="img/171030_Methodo_TDB_conso_prod_cigale.pdf">Note méthodologique d'élaboration de l'inventaire régional des consommations et productions d'énergies en Provence Alpes Côte d'Azur</a>
			</p>
			<p>
			<a href="img/171016_NoteMethodoInventaire.pdf">Note méthodologique d'élaboration de l'inventaire des émissions de polluants en Provence Alpes Côte d'Azur</a>
			</p>
            <p>
			<a href="http://www.lcsqa.org/system/files/ressources/medde-dgec-guide_methodo-elaboration_inventaires-pcit-2012_vf.pdf">Guide PCIT (Pôle de Coordination des Inventaires territoriaux)</a>
			</p>
			<p>
			<a href="https://www.citepa.org/fr/activites/inventaires-des-emissions/secten">CITEPA : inventaire SECTEN</a>
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