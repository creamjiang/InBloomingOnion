<?php

require_once('prepage.php');

$msg_type =$_POST['msg_type'];
$message  =$_POST['msg_content'];
$sender   =$_POST['sender'];
$recipient=$_POST['recipient'];

$sql = "INSERT INTO notifications
        (message_type, message, sender, recipient, created_at)
        values ('{$msg_type}','{$message}',{$sender},{$recipient}, NOW())";
$mysqli->query( $sql );

if( $msg_type == 'alert' ){
  error_log("MAILING!!");
  $to = '8168031981@txt.att.net';
  $subject = "[{$msg_type}] from InBloomingOnion";
  $headers = 'From: webmaster@inbloomingonion.local';
  mail( $to, $subject, $message, $headers );
  # mail('18168031981@txt.att.net',"[$msg_type] from InbloomingOnion!", $message );
}

header("Location:http://dev.inbloomingonion.local/student.php?UUID=".$_POST['uuid']); 

?>
