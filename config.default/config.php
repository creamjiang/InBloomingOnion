<?php
/**
 * @author Jason Cameron
 * @file config.php
 * @description contains the necessary config information for connecting 
 * to the database and SISAPI (InBloom, Clever, etc.)
 */

/**
 * Site Information
 **/
  $config['district'] = 'InBloomOnion K12';
  // websitetitle is what shows up on the top of your website on every page
  $config['websitetitle'] = $config['district'];
  // contact information is shown in footer for support/request calls
  $config['emailcontact'] = 'info@example.com';
  $config['phonecontact'] = '(888)-888-8888';

/**
 * Database Credentials
 * The following database credentials are used to connect to the localhost
 * database.  Currently this application only supports MySQL.
 * TODO: Take the time to make a database class to support MySQL and Postgres
 */
  $config['dbtype'] = 'mysql';
  $config['dbhost'] = 'localhost';
  $config['dbname'] = 'inbloomingonion';
  $config['dbuser'] = 'inbloomingonion';
  $config['dbpass'] = 'On1on1!';

/**
 * SIS API Credentials
 * The following is used for credentials needed to connect to the SISAPI
 * interfaces.
 * clientid = Username used to connect to the SISAPI. 
 * * InBloom: clientid is the 'clientid' found on the administration interface
 * * Clever: clientid is the username used in their basic access
 * * LearnSprout: 
 * TODO: Fill in documents for LearnSprout and Clever
 */
  $config['sisapi'] = 'inbloom'; # inbloom, learnsprout, clever
  $config['clientid'] = '11111';
  $config['clientsecret'] = '22222';
  $config['redirecturi'] = 'http://example.com/login.php';
  $config['auth_endpoint'] = 'https://api.sandbox.inbloom.org/api/oauth/authorize';
  $config['token_endpoint']= 'https://api.sandbox.inbloom.org/api/oauth/token';
  $config['disable_ssl_check'] = false;

?>
