<?php
  require_once('../config/config.php');
  require_once('classes/inbloom.class.php');
  session_start();

  $mysqli = new mysqli($config['dbhost'],$config['dbuser'],$config['dbpass'],
                       $config['dbname']);

  if (mysqli_connect_errno()) {
    printf("MySQL Error: %s\n", mysqli_connect_error());
    exit();
  }
  $dontincludebackbtn = array('/start.php','/index.php','/');

?>

<html>
  <!-- Start Head -->
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/jquery.mobile-1.1.0.min.css" />
    <script src="scripts/jquery-1.7.2.min.js"></script>
    <script src="scripts/jquery.mobile-1.1.0.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script>
      google.load('visualization','1', { packages:['corechart'] });
    </script>
  </head>
  <body>
    <div data-role="header" data-position="fixed"> 
        <h1><?=$config['websitetitle']?></h1>
        <?if(!in_array($_SERVER['REQUEST_URI'],$dontincludebackbtn)){ ?>
        <a href="start.php" data-rel="back" data-icon="back" class="ui-btn-right">Back</a>
        <? } ?>
    </div>
