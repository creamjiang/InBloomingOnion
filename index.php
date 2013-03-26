<?php

require_once('prepage.php');
session_start();

// If the session verification code is not set, redirect to the SLC Sandbox authorization endpoint
if (!isset($_GET['code'])) {
  $url = "{$config['auth_endpoint']}".
         "?client_id={$config['clientid']}".
         "&redirect_uri={$config['redirecturi']}";
  header('Location: ' . $url);
  die('Redirect'); 
} else {
  #session_start();
  $url = "{$config['token_endpoint']}".
         "?client_id={$config['clientid']}".
         "&client_secret={$config['clientsecret']}".
         "&grant_type=authorization_code".
         "&redirect_uri={$config['redirecturi']}".
         "&code={$_GET['code']}";
    
  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if ( $config['disable_ssl_check'] == TRUE) {
    // WARNING: this would prevent curl from detecting a 'man in the middle' attack
    // See note in settings.php 
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  }

  curl_setopt($ch, CURLOPT_HEADER, 'Content-Type: application/vnd.slc+json');
  curl_setopt($ch, CURLOPT_HEADER, 'Accept: application/vnd.slc+json');

  //execute post
  $result = curl_exec($ch);

  //close connection
  curl_close($ch);

  // de-serialize the result into an object
  $result = json_decode($result);

  // set the session with the access_token and verification code
  $_SESSION['access_token'] = $result->access_token;
  $_SESSION['code'] = $_GET['code'];

  // redirect to the start page of the application
  header('Location: ' . 'start.php');
}
?>
