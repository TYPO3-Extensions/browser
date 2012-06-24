<?php

  $lines = file( 'data.json' );
  var_dump( $lines[0] );
  
  var_dump( json_decode( $lines[0] ) );

?>
