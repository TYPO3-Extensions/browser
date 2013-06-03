<?php

class tx_myextension_tcemainprocdm {

  function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference) {
    if ($status == 'update' && $table == 'pages') {
      $fieldArray['hidden'] = 1;
    }
  }

}

?>