<?php
  require_once('../config/config.php');

  $mysqli = new mysqli($config['dbhost'],$config['dbuser'],$config['dbpass'],
                       $config['dbname']);

  if (mysqli_connect_errno()) {
    printf("MySQL Error: %s\n", mysqli_connect_error());
    exit();
  }

?>
