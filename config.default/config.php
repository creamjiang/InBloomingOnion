<?php
/**
 * @author Jason Cameron
 * @file config.php
 * @description contains the necessary config information for connecting 
 * to the database and SISAPI (InBloom, Clever, etc.)
 */

/**
 * Database Credentials
 * The following database credentials are used to connect to the localhost
 * database.  Currently this application only supports MySQL.
 * TODO: Take the time to make a database class to support MySQL and Postgres
 */
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
 * TODO: Fill in documents for LearnSprout
 */
  $config['sisapi'] = 'inbloom'; # inbloom, learnsprout, clever
  $config['clientid'] = '11111';
  $config['clientsecret'] = '22222';

?>
