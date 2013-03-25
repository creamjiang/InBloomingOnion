<?php

  require_once('inbloom.class.php');

  $inbloom = new InBloomingOnion( "1111" );
  $array['call'] = 'student';
  $array['id'] = '1111';
  echo $inbloom->inBloomCommonCall( $array );
