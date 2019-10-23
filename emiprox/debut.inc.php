<!DOCTYPE html>
<!--

<?php

echo html_entity_decode(br2nl($INTRO))."\n\n";
echo html_entity_decode($AASQA['nom'])." | ".html_entity_decode($AASQA['web'])."\n\n"; 
?>
-->

<html lang="fr">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate" />
    <meta name="description" content="<?php echo $DESC; ?>" />
    <meta name="author" content="<?php echo $AASQA['nom']; ?>" />
    <meta name="copyright" content="<?php echo $AASQA['nom']; ?>" />
    <meta name="publisher" content="<?php echo $AASQA['nom']; ?>" />
	
    <title>Emiprox | <?php echo $IE['nom']; ?></title>

    <!-- Google font -->
    <link href='https://fonts.googleapis.com/css?family=Gudea:400,700' rel='stylesheet' type='text/css'>
    
    <!-- JQuery -->
    <script src="js/jquery-1.11.0.js" type="text/javascript"></script>
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/heroic-features.css" rel="stylesheet">

    <!-- Selectize.js -->
    <script src="js/selectize.js" type="text/javascript"></script>
    <link href="css/selectize.css" rel="stylesheet" type="text/css"/>
    <link href="css/selectize.bootstrap3.css" rel="stylesheet" type="text/css"/>
    
    <!-- CanvasJS -->
    <script type="text/javascript" src="js/canvasjs.min.js"></script>

    <!-- Emiprox -->
    <link rel="stylesheet" type="text/css" href="css/emiprox.css" media="screen" title="emiprox" />

</head>

<body>
    
    <!-- Page Content -->
    <div class="container">
        
        <!-- Header -->
        <a href='<?php echo $AASQA['web']; ?>'><img id="logo" src='img/logo.png'></a>
        <!-- 
        <h1>Emiprox<br /><\?php echo $IE['nom']; ?></h1>
         -->
        <div class='row'>
            <div class='col-lg-12'>
                <!-- <p class='introduction'><\?php echo $INTRO; ?></p> -->
            </div>
        </div>
    
    
    
