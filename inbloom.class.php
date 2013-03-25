<?php

class InBloomingOnion{
  protected $access_token,$debug;
  protected $apiurl = "https://apittendancesattendances.sandbox.inbloom.org/api/rest/v1/";
  public function __construct( $access_token, $debug=false ){
    $this->access_token = $access_token;
    $this->debug = $debug;
  }

  public function inBloomApiCall( $url, $verify_ssl = false ){
    $ch = curl_init();
    $token = $_SESSION['access_token'];
    $code = $_SESSION['code'];
    $auth = sprintf( 'Authorization: bearer %s', $token );
    $headers = array(
      'Content-Type: application/vnd.slc+json',
      'Accept: application/vnd.slc+json',
      $auth
    );

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    if( $verify_ssl ){
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    }

    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode( $result );

  }

  /**
   * function inBloomCommonCall takes a simple key-value array and creates an 
   * initial call to the API based off of the credentials passed.  This skips 
   * the curl call needing to know the entire URL.
   * @param $commonArray
   * * id   - unique ID for the API call
   * * call - call to be used for the API
   */
  public function inBloomCommonCall( $commonArray ){
    switch( $commonArray['call'] ){
      case "student":
        if( $this->checkArrayID( $commonArray ) ){
          $uri = "students/{$commonArray['id']}";
        } else {
          $uri = "students";
        }
        break;
      case "attendance":
        if( $this->checkArrayID( $commonArray ) ){
          $uri = "students/{$commonArray['id']}/attendances";
        } else {
          $uri = "attendances";
        }
        break;
      default:
    }
    echo "$uri";
    //return $this->inBloomApiCall( "{$this->apiurl}{$uri}" );
  }

  private function checkArrayID( $array ){
    if( isset($array['id']) ){ return true; }
    return false;
  }
}
