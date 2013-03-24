<?php
require_once('prepage.php');

session_start();

//open connection
$ch = curl_init();

$url = 'https://api.sandbox.inbloom.org/api/rest/v1/students';
//$url = 'https://api.sandbox.slcedu.org/api/rest/system/session/check';

$token = $_SESSION['access_token'];
$code = $_SESSION['code'];

$auth = sprintf('Authorization: bearer %s', $token);
//echo $auth;

$headers = array(
  'Content-Type: application/vnd.slc+json',
  'Accept: application/vnd.slc+json',
  $auth);

curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

if (DISABLE_SSL_CHECKS == TRUE) {
// WARNING: this would prevent curl from detecting a 'man in the middle' attack
// See note in settings.php 
  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
}

//execute post
$result = curl_exec($ch);
// echo $result;
$json = json_decode($result);

// If response is '401 Unauthorized', redirect back to home page for authentication
if ($json->code == '401') {
  header('Location: index.php');
  die();
}

//https://localhost/api/rest/v1/teachers/<UUID>/teacherSectionAssociations/sections

//foreach($json as $students) {
//  $found = false;
//  foreach($students->links as $links) {
//    //echo $links->rel;
//    if ($links->rel == "getStudentSectionAssociations" && !$found) {
//      $url = $links->href;
//      //echo $url . "<br/>";
//      curl_setopt($ch, CURLOPT_URL, $url);
//      $sections_result = curl_exec($ch);
//      print_r($sections_result);
//      $found = true;  
//    }
//  }
//}

//close connection
curl_close($ch);


?>


<html>
  <head>
    <link rel="stylesheet"  href="jq-mobile/jquery.mobile-1.1.0.min.css" />
    <script src="jq-mobile/jquery-1.7.2.min.js"></script>
    <script src="jq-mobile/jquery.mobile-1.1.0.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script>
      google.load('visualization','1', { packages:['corechart'] });
    </script>
  </head>
  <body>
    <div data-role="header" data-position="fixed"> 
	<h1>InBloomingOnion</h1> 
</div>
    <ul data-role="listview" data-theme="g">


<?php
foreach ($json as $student) {

  #print_r($student); exit;
  $username = $student->electronicMail[0]->emailAddress;
  if ($username == ''){ $username = $firstname.'.'.$lastname; }
  $firstname= $student->name->firstName;
  $lastname = $student->name->lastSurname;
  $select = "SELECT * FROM users where username = '{$username}'";
  $count = $mysqli->query( $select );
  if( $count->num_rows == 0 ){
    /** Populate database with Students **/
    $inbloomid= $student->id;
    $sql = "INSERT INTO users(inbloomid,username,firstname,lastname,cellnumber, created_at) VALUES('{$inbloomid}','{$username}','{$firstname}','{$lastname}','{$student->telephone[0]->telephoneNumber}', NOW())";
    $mysqli->query( $sql );
  }


  echo sprintf('<li><a href="student.php?UUID=%s">', $student->id);
  echo $firstname . ' ' . $lastname;
  echo '</a></li>';
}
?>
    </ul>
    <div data-role="footer" data-position="fixed"> 
	<h1></h1> 
</div>
  </body>

</html>

