<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CIGALE - Consultation d’Inventaires Géolocalisés de qualité de l’Air et de L’Energie</title>

    <!-- Bootstrap core CSS  -->
    <!--  <link href="../thefirm/assets/css/bootstrap.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="index.css" rel="stylesheet">

    <!-- Fonts from Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
    
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
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Administration</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

	<div id="headerwrap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h1>Consultation d’Inventaires Géolocalisés de qualité de l’Air et de L’Energie</h1>
					<form class="form-inline" role="form">
					  <!--
                      <div class="form-group">
					    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email address">
					  </div>
                      -->
					  <!--<button type="button" class="btn btn-warning btn-lg">C'est parti!</button>-->
                      <a href="visualisation.php" type="button"  class="btn btn-warning btn-lg" role="button">C'est parti!</a>
					</form>					
				</div><!-- /col-lg-6 -->
				<div class="col-lg-6">
					<!-- <img class="img-responsive" src="assets/img/ipad-map.png" alt=""> -->
                    <img class="img-responsive" src="img/contributors.png" alt="">
				</div><!-- /col-lg-6 -->
				
			</div><!-- /row -->
		</div><!-- /container -->
	</div><!-- /headerwrap -->
	
	
	<div class="container">
		<!--<div class="row mt centered">
			<div class="col-lg-6 col-lg-offset-3">
				<h1>Your Landing Page<br/>Looks Wonderful Now.</h1>
				<h3>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</h3>
			</div>
		</div> /row -->
		
		<div class="row mt centered">
			<div class="col-lg-4">
				<!-- <img src="assets/img/ser01.png" width="180" alt=""> -->
                <a href="visualisation.php"><img class="img-visu" src="img/cartography2.png" border="0" width="180"></a>
                <!-- <a href="www.coin.com"><img src="assets/img/ser01.png" alt="canard"/></a>-->
				<h4>Visualisation</h4>
				<p>Recherche géographique et production de bilans</p>
			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
                <!-- <img src="assets/img/export_table.png" width="180" alt=""> -->
                <a href="extraction.php"><img class="img-extract" src="img/csv-icon.png" border="0" width="180"></a>
				<h4>Extractions</h4>
				<p>Extraction fine des données par critères</p>

			</div><!--/col-lg-4 -->

			<div class="col-lg-4">
				<!-- <img src="assets/img/ser03.png" width="180" alt=""> -->
                <a href="#"><img class="img-methodo" src="img/ser03.png" border="0" width="180"></a>
				<h4>Méthodologie</h4>
				<p>Méthodologie d'inventaire et notes utilisateurs</p>

			</div><!--/col-lg-4 -->
		</div><!-- /row -->
	</div><!-- /container -->
	
	<div class="container">
		<p class="centered">Air PACA © - Theme inspired by BlackTie.co3</p>
	</div><!-- /container -->
	

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
    <script src="libs/jquery/jquery-3.2.1.min.js"></script> 
    <!-- <script src="../thefirm/assets/js/bootstrap.min.js"></script> -->
    <script src="libs/bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    
    
    
    
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
