<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Consultation d'Inventaires Géolocalisés Air CLimat Energie">
        <meta name="author" content="Air PACA">

        <title>CIGALE - Consultation d'Inventaires Géolocalisés Air CLimat Energie</title>

        <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css"> <!-- Bootstrap core CSS  -->
        <link href="index.css" rel="stylesheet"> <!-- Custom styles for this template -->
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'> <!-- Fonts from Google Fonts -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><b>CIGALE</b></a>
                </div>
                <!-- 
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Administration</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <!-- Titre de la page et image des contributeurs-->
        <div id="headerwrap">
            <div class="container" id="head">
                <div class="row">
                    <div class="col-md-6">
                        
                        <h1>Consultation d'Inventaires Géolocalisés </br>Air CLimat Energie</h1>
                        
                        <form class="form-inline" role="form">
                            <a href="visualisation.php" type="button"  class="btn btn-warning btn-lg" role="button">C'est parti!</a>
                        </form>	

                        <div id="preambule">
                        L'application CIGALE est réalisée par <a href="http://www.airpaca.org/">Air PACA</a>, 
                        dans le cadre de ses missions au sein de <a href="http://oreca.regionpaca.fr/">l’Observatoire Régional de l’Energie, du Climat et de l’Air</a>. 
                        Elle fournit, de la région à la commune, des données annuelles de consommations et de productions d’énergie, 
                        d’émissions de polluants atmosphériques et de gaz à effet de serre.                   
                        </div>
                        
                    </div><!-- /col-md-6 -->
                    
                    <div class="col-md-6" id=col-contributors>
                        <img src="img/contributors2.png" id="img_contributors">
                    </div><!-- /col-md-6 -->
                    
                </div><!-- /row -->
            </div><!-- /container -->
        </div><!-- /headerwrap -->

        <div class="container">
            <div class="row mt centered">
            
                <div class="col-md-4">
                    <a href="visualisation.php"><img class="img-visu" src="img/cartography2.png" border="0" width="180"></a>
                    <h4>Visualisation</h4>
                    <p>Cartes et bilans par territoire</p>
                </div><!--/col-md-4 -->

                <div class="col-md-4">
                <a href="extraction.php"><img class="img-extract" src="img/csv-icon.png" border="0" width="180"></a>
                <h4>Extraction</h4>
                <p>Export de données</p>
                </div><!--/col-md-4 -->

                <div class="col-md-4">
                    <a href="methodo.php"><img class="img-methodo" src="img/document-flat.png" border="0" width="180"></a>
                    <h4>Aide et méthodologie</h4>
                    <p>Méthodologie d'inventaire et infos utilisateurs</p>
                </div><!--/col-md-4 -->
                
            </div><!-- /row -->
        </div><!-- /container -->

        <div class="container">
            <p class="centered">Air PACA © - Bootstrap theme FLATTY thanks to http://blacktie.co | <a href="methodo.php#contact">contact</a></p>
        </div><!-- /container -->


        <!-- JQuery & Bootstrap core JavaScript -->
        <script src="libs/jquery/jquery-3.2.1.min.js"></script> 
        <script src="libs/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

        <!-- Script -->
        <script type="text/javascript"> 

        // Changement de la source des images au hover
        $(".img-extract").hover(function(){
            $(this).attr("src", function(index, attr){
                return attr.replace(".png", ".hover.png");
            });
        }, function(){
            $(this).attr("src", function(index, attr){
                return attr.replace(".hover.png", ".png");
            });
        });

        $(".img-methodo").hover(function(){
            $(this).attr("src", function(index, attr){
                return attr.replace(".png", ".hover.png");
            });
        }, function(){
            $(this).attr("src", function(index, attr){
                return attr.replace(".hover.png", ".png");
            });
        });

        $(".img-visu").hover(function(){
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
