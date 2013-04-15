<?php

require_once('prepage.php');

// If the session verification code is not set, redirect to the SLC Sandbox authorization endpoint
if (isset($_GET['code'])) {
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
    <h1>Please Login</h1>
    <div id='logins' data-role='collapsible-set'>
      <div id='saml' data-role='collapsible'>
        <h3>Single Sign On for Students</h3>
        <p>Coming Soon..</p>
      </div>
      <div id='staff' data-role='collapsible'>
        <h3>School Faculty and Staff</h3>
        <center><a href='/login.php' rel='external'>
          <img src='/images/inbloom-logo.jpg' />
        </a></center> 
      </div>
    </div>
    <br />
    <center>
    Download or Fork the project on Github at: <a href='https://github.com/jbkc85/InBloomingOnion'>github.com/jbkc85/InBloomingOnion</a>
    </center>
<?php require_once('postpage.php'); ?>
