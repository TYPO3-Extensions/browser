<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


require_once(PATH_t3lib.'class.t3lib_tceforms.php');


 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   28: class tx_browser_befilter_hooks implements t3lib_localRecordListGetTableHook
 *   41:     public function getDBlistQuery ($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
 *   88:     public function makeFormitem($item, $table, $conf)
 *  151:     public function editFormitem($confarray, $item, $labelValue)
 *  170:     public function makeWhereClause($item, $conf, $itemValue, $table)
 *  212:     public function makeQueryInputTrim($item, $itemValue, $table)
 *  225:     public function makeQuerySelect($item, $itemValue, $table)
 *  238:     public function makeQueryCheckTime($item, $itemValue, $table)
 *  253:     public function makeQueryInputFromto($item, $from, $to, $table)
 *  275:     public function getTimestampFrom($timetime)
 *  287:     public function getTimestampTo($timetime)
 *  303:     private function init_ts()
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

class tx_browser_tcemainprocdm 
{

  public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$reference ) 
  {
//    if( $status == 'update' && $table == 'pages' )
//    {
//      if( $fieldArray[ 'hidden' ] == 1 )
//      {
//        $fieldArray[ 'hidden' ] = 0;
//      }
//      else
//      {
//        $fieldArray[ 'hidden' ] = 1;
//      }
        // TCA eval value
      if( ! is_array( $GLOBALS[ 'TCA' ][ $table ][ 'columns' ] ) )
      {
        t3lib_div::loadTCA( $table );
      }
      $prompt = var_export( $GLOBALS['TCA'][$table]['ctrl'], true );
      
      $table      = $table;
      $recuid     = $id;
      $action     = 5; // Action number: 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
      $recpid     = $id; 
      $error      = 3;  // 0 = message, 1 = error, 2 = System Error, 3 = security notice 
      $details    = $table . ': ' . $prompt . '|' . var_export( $fieldArray, true );    
      $details_nr = -1;
      $data       = array( );
      $event_pid  = $id; 
      $NEWid      = null;
      $reference->log( $table, $recuid, $action, $recpid, $error, $details, $details_nr, $data, $event_pid );

//      $table      = $table;
//      $recuid     = $id;
//      $action     = 5; // Action number: 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
//      $recpid     = $id; 
//      $error      = 1;  // 0 = message, 1 = error, 2 = System Error, 3 = security notice 
//      $details    = 'prompt';    
//      $details_nr = -1;
//      $data       = array( );
//      $event_pid  = $id; 
//      $NEWid      = null;
//      $reference->log( $table, $recuid, $action, $recpid, $error, $details, $details_nr, $data, $event_pid );

//      $type       = 4;    // 4: Modules like an extension
//      $action     = 0;
//      $error      = 1;    // 0 = message, 1 = error, 2 = System Error, 3 = Security notice
//      $details_nr = 0;
//      $details    = 'message';
//      $data       = array( );
//      $table      = $table;
//      $recuid     = $id;
//      $recpid     = null; // obsolete
//      $event_pid  = $id;  // page id
//      $NEWid      = null;
//      $reference->BE_USER->writelog
//                          (
//                            $type, 
//                            $action, 
//                            $error, 
//                            $details_nr, 
//                            $details, 
//                            $data, 
//                            $table, 
//                            $recuid, 
//                            $recpid,
//                            $event_pid, 
//                            $NEWid
//                          );
//      $message  = 'simplelog';
//      $extKey   = 'tx_browser';
//      $error    = 1;
//      $reference->BE_USER->simplelog( $message, $extKey, $error );

//    }
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']);
}

?>