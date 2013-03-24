<?php
  $config['db_host'] = 'localhost';
  $config['db_name'] = 'inbloom_local_data';
  $config['db_user'] = 'inbloom';
  $config['db_pass'] = 'inbloom';

  $mysqli = new mysqli($config['db_host'],$config['db_user'],$config['db_pass'],
                       $config['db_name']);

  if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
  }

?>
