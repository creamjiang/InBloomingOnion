<?php

/**
  * @author Jason Cameron
  * @date August 26th, 2012
  * @desc PHP Class to initialize a OpenBadges
 **/

class openBadgeBase{
  public function __construct($backpack){
    $this->backpack = $backpack;
  }

  public function curl_request( $action, $args ){
    $curl= curl_init();
    switch( $action ){
      case 'user':
        $url = $this->backpack."/displayer/convert/email";
        $vars = array('email'=>$args);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$vars);
        break;
      case 'groups':
        $url = $this->backpack."/displayer/".$args->userId."/groups.json";
        break;
    }
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $out = curl_exec($curl);
    curl_close($curl);
    return json_decode($out);
  }
    
}

class openBadgeDisplay extends openBadgeBase{
  private function convertEmail($email){
    return $this->curl_request( 'user',$email );
  }

  private function checkStatus( $status ){
    switch( $status ){
      case "missing":
        $response = array(
            'status' => $status,
            'message'=> "User does not have an account with current backpack",
        );
        return json_encode($response);
        break;
    }
  }

  public function findGroups( $email ){
    $json = $this->convertEmail($email);
    if( $json->status != 'okay' ){ 
      return $this->checkStatus( $json->status ); 
    }
    return $this->curl_request( 'groups',$json );
  }

  private function displayBadges( $email,$groupid ){
    $json = $this->convertEmail($email);
    if( $json->status != 'okay' ){ 
      return $this->checkStatus( $json->status ); 
    }
    $url = $this->backpack."/displayer/".$json->userId."/group/$groupid.json";
    $curl= curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $out = curl_exec($curl);
    curl_close($curl);
    return ($out);
  }

  public function display( $email,$groupid ){
    return $this->displayBadges( $email,$groupid );
  }
}

class openBadgeIssuer extends openBadgeBase{
}

?>
