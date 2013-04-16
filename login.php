<?php

require_once('prepage.php');

if($_SESSION['salt']!=sha1("{$config['inbloomingonionsalt']}".date("m.d.y"))){
  header("Location: index.php");
}

// First we verify if the method chosen for logging in is SAML or OAuth
if ( isset( $_POST['method'] ) ){
  if( $_POST['method'] == 'saml' ){
  } else if ( $_POST['method'] == 'oauth' ){
    // check_code_parameter
    if( !isset($_GET['code']) ){
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
      header('Location: dashboard.php');
    }
  }
}
// If no method is chosen, resume original behavior of checking code
// If the session verification code is not set, redirect to the SLC Sandbox authorization endpoint
else if (!isset($_GET['code'])) {
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
  header('Location: dashboard.php');
}
?>

<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet"  href="jq-mobile/jquery.mobile-1.1.0.min.css" />
    <script src="jq-mobile/jquery-1.7.2.min.js"></script>
    <script src="jq-mobile/jquery.mobile-1.1.0.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script>
      google.load('visualization','1', { packages:['corechart'] });
      function directToLogin( param ){
        $.mobile.changePage( "login.php", {
          transition: "pop",
          type: "post",
          data: {"method":param}
        });
      }
    </script>
  </head>
  <body>
    <div data-role="header" data-position="fixed"> 
	<h1>InBloomingOnion Login</h1> 
    </div>
  </body>
</html>
