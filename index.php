<?php require_once('prepage.php'); ?>
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
<?php 

$_SESSION['salt'] = sha1("{$config['inbloomingonionsalt']}".date("m.d.y"));
require_once('postpage.php'); 

?>
