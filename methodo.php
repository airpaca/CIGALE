<!-- Doctype HTML5 -->
<!DOCTYPE html>
<html lang="en">
<html dir="ltr">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Méthodologie et documents de référence">
    <meta name="author" content="AtmoSud">    
    
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />		
	
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
            <img src="img/LogoAtmosud.small.png">
            
			<h1>Définitions et équivalences énergétiques</h1>
                <h2 data-toc-text="Consommation énergétique finale">Consommation énergétique finale</h2>
					<p>
                    Les données présentées dans Cigale correspondent à la <b>consommation énergétique finale</b>. 
                    Il s’agit de l’énergie livrée à des fins énergétiques, donc hors utilisation en tant que matière première, pour toutes les branches économiques 
                    à l’exception des producteurs d’électricité et de chaleur (pour éviter les double-comptes). 
                    Elle représente toute l’énergie consommée par les utilisateurs finaux sur le territoire y compris les consommations d’électricité et de chaleur 
                    (qui sont des énergies secondaires). Les émissions de gaz à effet de serre résultantes intègrent donc les émissions indirectes de CO<sub>2</sub> liées 
                    à la consommation d’électricité (SCOPE 1 et 2). 
					</p>	
					<p>	
                    L’usage de matières premières correspond à la consommation non énergétique finale et n’est pas inclus dans les données de consommation dans Cigale, 
                    bien que les émissions induites par les activités concernées soient prises en compte, sous la catégorie « aucune énergie ». 
					</p>
			    <h2 data-toc-text="Production d'énergie primaire">Production d'énergie</h2>
					<p>
					Dans CIGALE, les productions recensées sont les productions d’électricité et de chaleur en sortie de l’installation. </br>
					Ces productions peuvent correspondre à des productions primaires (ex : éolien, solaire, hydraulique, etc.) ou a des productions secondaires ( ex : thermique fossile).					
					</p>
					<p>
					Energie primaire: il s'agit des produits énergétiques « bruts » dans l’état dans lequel ils sont fournis par la nature, c’est-à-dire l’énergie potentielle contenue dans les produits après extraction mais
					avant transformation (exemple : bois). </br>
					Par convention, l’énergie électrique provenant des filières hydraulique, éolienne et photovoltaïque est considérée comme une production primaire.</br>
					</br>
					L’énergie secondaire (électricité ou chaleur) issue de la transformation des produits est généralement inférieure à la production primaire, en fonction des pertes et des rendements des unités de valorisation (UIOM, ISDND, centrale, etc.) 
					</p>
				<h2 data-toc-text="Emissions de gaz à effet de serre : PRG">Emissions de gaz à effet de serre : PRG</h2>
					<p>
                    Le <b>Potentiel de Réchauffement Global (PRG)</b> est un indicateur défini pour comparer l’impact de chaque 
					gaz à effet de serre sur le réchauffement global, sur une période choisie (généralement 100 ans). 
					Il est calculé à partir des PRG de chaque substance et est exprimé en équivalent CO<sub>2</sub> (CO<sub>2</sub>e). 
					</p>	
					<p>	
					Par définition, le PRG du CO<sub>2</sub> est toujours égal à 1. Les coefficients utilisés dans l’inventaire d'AtmoSud
					sont ceux du 5<sup>e</sup> rapport du GIEC ( (CO2=<sub>2</sub>, CH<sub>4</sub>=28, N<sub>2</sub>O=265). 
                    Les gaz fluorés ne sont actuellement pas calculés dans l’inventaire.
					</p>
				<h2 data-toc-text="Equivalences énergétiques">Tableau des équivalences énergétiques</h2>
					<p>
                    <img src="img/Tableau_eq_energetique.JPG" border="0" width="97%">
                    </p>
			
            <h1>Nomenclatures d'activités</h1>
			    <h2 data-toc-text="Nomenclature SNAP">Nomenclature SNAP</h2>
                    <p>
                    Les inventaires d’émissions sont établis selon la nomenclature <b>SNAP</b> (Selected Nomenclature for Air Pollution, EMEP/CORINAIR 1997). 
					Cette nomenclature a évolué depuis 1997, pour permettre la prise en compte de nouvelles activités. Elle est décrite dans le rapport
					OMINEA (Organisation et méthodes des inventaires nationaux des émissions atmosphériques en France). Cf. CITEPA.
                    </p>
                <h2 data-toc-text="Nomenclature PCAET">Nomenclature PCAET</h2>
                    <p>
                    Dans l’outil CIGALE, les données d’émission et de consommation sont déclinées en huit secteurs conformément aux prescriptions de <a href="https://www.legifrance.gouv.fr/jo_pdf.do?id=JORFTEXT000032974938">l’arrêté PCAET</a> :
                    </p>
                
                    <ul>
                    <li>Résidentiel</li>
                    <li>Tertiaire</li>
                    <li>Transport routier</li>
                    <li>Autres transports</li>
                    <li>Agriculture</li>
                    <li>Déchets</li>
                    <li>Industrie hors branche énergie</li>
                    <li>Branche énergie</li>
                    </ul>             
                
                    <p>
                    Une neuvième catégorie <b>Emetteurs non inclus</b> regroupe les émissions non prises en compte dans les totaux sectoriels ainsi 
                    que les sources non anthropiques, qui ne sont généralement pas rapportées dans les bilans d’émissions au format PCAET. 
                    Il s’agit notamment de la remise en suspension des particules fines, des feux de forêt et des sources naturelles :
                    (végétation, NOx et COVNM des champs et cultures, NOx des cheptels) :                    
                    </p>
                
                    <img src="img/tableau_non_inclus.png" border="0" width="40%">
                
                    <p> 
                    Les émissions de GES des cycles LTO internationaux sont également rapportées dans cette catégorie. 
                    Pour information, les émissions et consommations des phases croisières de l’aviation et du maritime ne sont pas rapportées dans Cigale. 
                    </p>

                    <p>
                    Pour la <b>Branche énergie</b>, les données de consommation d’énergie et d’émissions de gaz à effet de serre liées à la production d’électricité, 
                    de chaleur et de froid ne sont pas inclues dans ce secteur, mais elles sont comptabilisées au stade de la consommation finale par l’utilisateur. 
                    Ainsi, l’inventaire des polluants atmosphériques (hors GES) comptabilise les émissions sur le lieu de rejet. L’inventaire des émissions de gaz à 
                    effet de serre comptabilise les émissions directes liées à tous les secteurs d’activité hormis celui de la production d’électricité, de chaleur et 
                    de froid, dont seule la part d’émissions indirectes liée à la consommation à l’intérieur du territoire est comptabilisée.
                    </p>

                    <img src="img/schema_scope.png" border="0" width="30%">

                    
                    
                    </p>	
				<h2 data-toc-text="Secteur UTCATF">Secteur UTCATF</h2>
					<p> 
                    L'<b>Utilisation des Terres, Changement d’Affectation des Terres et Foresterie</b> est à la fois un puits et une source d'émission de 
					CO<sub>2</sub>, CH<sub>4</sub> et N<sub>2</sub>O. L'UTCATF couvre la récolte et l'accroissement forestier, la conversion des forêts (défrichement) 
					et des prairies ainsi que les sols dont la composition en carbone est sensible à la nature des activités 
					auxquelles ils sont dédiés (forêt, prairies, terres cultivées). Ce secteur n’est actuellement pas calculé 
					dans l’inventaire AtmoSud.                  
                    </p>	

			
			<h1>Nomenclatures de combustibles</h1>
				<h2 data-toc-text="Nomenclature NAPFUE">Nomenclature NAPFUE</h2>
                    <p>
					Les activités de combustion sont distinguées dans l’inventaire par un niveau supplémentaire en intégrant 
					la nomenclature NAPFUE (Nomenclature for Air Pollution of FUEls). Cette nomenclature est décrite dans 
					le rapport OMINEA (Cf. CITEPA).
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

            <h1>Mise à jour des données</h1>
            <p>
            AtmoSud met à jour les inventaires d'émissions, de consommations et productions énergétiques tous les ans. 
            </p>
            <p>
            Lors de cette mise à jour, une année supplémentaire est calculée. Les années antérieures sont également toutes recalculées pour 
            prendre en compte les éventuelles modifications méthodologiques et actualisation des données sources. 
            Les données de la dernière version annulent et remplacent donc les précédentes.
            </p>
            <p>
            Des mises à jour pour une même version d’inventaire peuvent également être réalisées. Les listes des versions et détails des mises à jour sont consultables ci-dessous.            
            </p>
            
            <b>Versions d'inventaire:</b>
            <?php include 'scripts/versions_inventaire_html.php';   ?>
           
            <b>Mises à jour de l'interface:</b></br> 
			- 17/12/2019: Changement du format de restitution pour les consommations et émissions de GES de la branche production d'énergie, pour n'exclure que les installations de production d'électricité et de chaleur. Les autres activités, comme les raffineries par exemple, sont maintenant bien comptabilisées dans cette branche d'activités.</br>
            - 16/10/2019: Changement de la classification SECTEN1 en classification PCAET pour différencier le secteur déchets de celui de l'industrie.</br>
            - 16/10/2019: Correction d'un bug lors de l'export des données sur les PNR.</br>
            - 16/10/2019: Ajout du champ code insee des communes dans la partie export si détail communal activé.</br>
            - 16/10/2019: Correction d'un bug d'affichage cartographique des communues de certains territoires.</br>
            - 16/10/2019: Ajout de la version d'inventaire et de la date dans les extractions.</br>
            
            <h1>Secret statistique</h1>
            <p>
            Certaines données sont soumises au secret statistique et ne peuvent être publiées. Une donnée est considérée 
			comme confidentielle lorsque moins de 3 établissements sont à l’origine de cette donnée ou qu’un seul établissement 
			contribue à 85 % ou plus de cette donnée (<a href="https://www.insee.fr/fr/information/1300624">définition INSEE du secret statistique</a>).
			</p>
			<p>
			Le secret statistique est calculé par commune, par secteur d’activité et par catégorie d’énergie pour les consommations. 
			</p>
			<p>
			Dans le souci de publier un maximum de données, plusieurs traitements sont effectués :</br>
			</p>
			<p>
			- Pas de donnée confidentielle pour les émissions (uniquement pour les consommations) </br>
			- A l’échelle de l’EPCI, lorsque les critères du secret statistique sont respectés et 
			que le secret statistique concerne plusieurs communes (pour un même secteur d’activité et 
			une même catégorie d’énergie), les données globales de l’EPCI peuvent être diffusées (impossibilité de reconstitution des données communales).
            </p>                    

            <h1 data-toc-text="Conditions d'utilisation">Conditions d'utilisation des données</h1>
                    
                    <p>
                    Les données présentées dans le cadre de l'application CIGALE sont sous licence 
                    <a href="http://opendatacommons.org/licenses/odbl/1.0/">ODbL</a>.
                    </p>
                    <p>
                    Elles peuvent librement être diffusées, 
                    publiées ou utilisées dans le cadre de travaux, d'études ou d'analyse avec les conditions suivantes:
                    </p>
                    <p>
                    - Toute utilisation des données brutes issues de la base de données CIGALE devra faire référence à 
                    l'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) et à AtmoSud en ces termes :
                    'Source: Base de donnes CIGALE - Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) 
                    Provence-Alpes-Côte d'Azur / inventaire AtmoSud'. 
                    Il est également important de préciser la version de l’inventaire et la date correspondant à l’extraction des données.
                    </p>
                    <p>
                    - Toute utilisation de données retravaillées par l'utilisateur final à partir de données 
                    brutes issues de l'application CIGALE devra faire référence à l'Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) et à AtmoSud en ces termes :
                    'Source: *Utilisateur final* d'après la base de données CIGALE - Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) Provence-Alpes-Côte d'Azur / inventaire AtmoSud'
                    </p>
                    <p>
                    - Sur demande, l'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) Provence-Alpes-Côte d'Azur et AtmoSud 
                    mettent à disposition les méthodes d'exploitation des données mises en œuvre.
                    </p>
                    <p>
                    - Les données contenues dans ce document restent la propriété de l'Observatoire Régional de l'Energie, 
                    du Climat et de l'Air (ORECA) Provence-Alpes-Côte dAzur et d'AtmoSud.
                    </p>
                    <p>
                    - L'Observatoire Régional de l'Energie, du Climat et de l'Air (ORECA) Provence-Alpes-Côte dAzur et 
                    AtmoSud peuvent rediffuser ce document à d'autres destinataires.        
                    </p>    
            
			<h1>Liens utiles</h1>
			<p>
			<a href="img/171030_Methodo_TDB_conso_prod_cigale.pdf">Note méthodologique d'élaboration de l'inventaire régional des consommations et productions d'énergies en Provence Alpes Côte d'Azur</a>
			</p>
			<p>
			<a href="img/171016_NoteMethodoInventaire.pdf">Note méthodologique d'élaboration de l'inventaire des émissions de polluants en Provence Alpes Côte d'Azur</a>
			</p>
            
			<p>
			<a href="https://www.atmosud.org/sites/paca/files/atoms/files/190724_plaquette_inventaires_territoriaux_0.pdf">En savoir plus sur les inventaires : la plaquette AtmoSud</a>
			</p>            
            
            <p>
			<a href="https://www.lcsqa.org/system/files/rapport/MTES_Guide_methodo_elaboration_inventaires_PCIT_mars2019.pdf">Guide PCIT (Pôle de Coordination des Inventaires territoriaux)</a>
			</p>
			<p>
			<a href="https://www.citepa.org/fr/activites/inventaires-des-emissions/secten">CITEPA : inventaire SECTEN</a>
			</p>

            <h1>Contact</h1>
            <p>
            Pour nous faire remonter une erreur, incohérence ou pour plus d'information sur les données 
            présentées, vous pouvez envoyer un mail à <a href="mailto:contact.air@atmosud.org">contact.air@atmosud.org</a>
            </p>
            <p>
            Responsables de la publication: AtmoSud - Benjamin Rocher, Damien Bouchard, Romain Souweine
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
