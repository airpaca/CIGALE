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
    
    <!-- CSS -->
    <link rel="stylesheet" href="style.extract.css"/>
   
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
            <select class="selectpicker" id="select_ans" title="Années d'inventaire" multiple data-selected-text-format="count > 3" data-width="100%">
                <option>2007</option>
                <option>2010</option>
                <option>2012</option>
                <option>2013</option>
                <option>2014</option>
                <option>2015</option>                
            </select>

            <p>Emprise géographique</p>
            <select class="selectpicker" title="Emprise géograpique" multiple data-max-options="1" data-live-search="true" data-width="100%">
                <option>Région PACA</option>
                <option>Commune A</option>
                <option>Commune B</option>
            </select>
            
            <p>Secteurs d'activités</p>
            <select class="selectpicker" title="Secteurs d'activité" data-selected-text-format="count > 1" multiple data-actions-box="true" data-width="100%">
                <option>Extraction, transformation et distribution d'énergie</option>
                <option>Industrie manufacturière, traitement des déchets, construction</option>
                <option>Résidentiel</option>
                <option>Tertiaire, commercial et institutionnel</option>
                <option>Agriculture, sylviculture et aquaculture hors UTCF</option>
                <option>Transport routier'</option>
                <option>Modes de transports autres que routier'</option>
                <option>Emetteurs non inclus'</option>
            </select>             

            <p>Combustibles</p>
            <select class="selectpicker" title="Combustibles" data-selected-text-format="count > 2" multiple data-actions-box="true" data-width="100%">
                <option>Combustibles Minéraux Solides (CMS)</option>
                <option>Produits pétroliers</option>
                <option>Bois-énergie (EnR)</option>
                <option>Autres énergies renouvelables (EnR)</option>
                <option>Autres non renouvelables</option>
                <option>Gaz Naturel</option>
                <option>Electricité (émissions indirectes)</option>
                <option>Chaleur et froid issus de réseau (émissions indirectes)</option>
            </select>    

            <p>Polluants et GES</p>
            <select class="selectpicker" title="Polluants et GES" data-selected-text-format="count > 5" multiple data-actions-box="true" data-width="100%">
                <option>PM10</option>
                <option>PM2.5</option>
                <option>NOx</option>
                <option>COV</option>
                <option>SO2</option>
                <option>NH3</option>
                <option>GES eq.CO2</option>
            </select>   

            
         
            
        </div>

        <div class="Boutons_extractions">
        
        
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-success">Exporter les données</button>
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
            </div>
            
            
        </div>
        
    </div>
    
    <!-- Partie droite - Affichage des données -->    
    <div id="sidebar-right">

        
        <p>
        Air PACA - Inventaire v4</br>
        Extraction datée du 2017-06-21 à 15:32
        </p>
        
        <h3>
        Conditions d'utilisation et de diffusion :
        </br>
        Diffusion libre pour une réutilisation ultérieure des données dans les conditions ci-dessous :</br>
        – Toute utilisation partielle ou totale de ce document doit faire référence à Air PACA en terme de "Inventaire Air PACA".</br>
        – Données non rediffusées en cas de modification ultérieure des données.</br>
        </br>
        Sur demande, Air PACA met à disposition les méthodes d'exploitation des données mises en oeuvre.</br>
        Les données contenues dans ce document restent la propriété d'Air PACA.</br>
        Air PACA peut rediffuser ce document à d'autres destinataires.</br>
        </h3>
        
        
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Année</th>
              <th>Entité administrative</th>
              <th>Secteur</th>
              <th>Combustible</th>
              <th>Consommation en tep</th>
              <th>Polluant</th>
              <th>Valeur</th>
              <th>Unité</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>
            
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>1417</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2007</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>5</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Bois-énergie (EnR)</td>
              <td>200</td>
              <td>PM10</td>
              <td>2051</td>
              <td>t/an</td>
            </tr>
            <tr>
              <th scope="row">1</th>
              <td>2015</td>
              <td>Région</td>
              <td>Tertiaire</td>
              <td>Autres énergies renouvelables (EnR)</td>
              <td>7</td>
              <td>PM10</td>
              <td>24</td>
              <td>t/an</td>
            </tr>          
          </tbody>
        </table>


    </div>

</div>

<script type="text/javascript">

/* Variables générales */

/* Fonctions */

/* Appel des fonctions */


console.log($("#select_ans"));

$("#select_ans").append($('<option>', {
    value: "toto",
    text: '2020'
}));


</script>

</body>
</html>