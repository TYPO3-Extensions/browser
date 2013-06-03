<?php

class tx_browser_tcemainprocdm 
{

  public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$reference ) 
  {
    if( $status == 'update' && $table == 'pages' )
    {
      $fieldArray[ 'hidden' ] = 1;
      $reference->log( $table, $id , 3, 0, 1, '#########################' );
    }
  }

}

?>