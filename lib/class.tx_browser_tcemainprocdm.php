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


/**
* The class tx_browser_tcemainprocdm bundles methods for evaluating data in backend forms
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 4.5.7
* @since 4.5.7
*/

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



  /***********************************************
  *
  * Hook: processDatamap_postProcessFieldArray
  *
  **********************************************/

/**
 * processDatamap_postProcessFieldArray( )
 *
 * @param	string		$status     : update, edit, delete, moved
 * @param	string		$table      : label of the current table
 * @param	integer		$id         : uid of the current record
 * @param	array		$fieldArray : Array of modified fields
 * @param	object		$reference  : reference to parent object
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$reference ) 
  {
//    if( ! is_array( $GLOBALS[ 'TCA' ][ $table ][ 'columns' ] ) )
//    {
//      t3lib_div::loadTCA( $table );
//    }

    switch( true )
    {
      case( ! is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser'] ) ):
          // follow the workflow: RETURN
        break;
      case( is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser']['route'] ) ):
        $this->route( $status, $table, $id, &$fieldArray, &$reference );
          // follow other cases in this switch
      default:
          // follow the workflow: RETURN
        break;
    }
    
    return;
  }



  /***********************************************
  *
  * Route
  *
  **********************************************/

/**
 * route( )
 *
 * @param	string		$status     : update, edit, delete, moved
 * @param	string		$table      : label of the current table
 * @param	integer		$id         : uid of the current record
 * @param	array		$fieldArray : Array of modified fields
 * @param	object		$reference  : reference to parent object
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function route( $status, $table, $id, &$fieldArray, &$reference ) 
  {
    $prompt = var_export( $GLOBALS['TCA'][$table]['ctrl']['tx_browser'], true );

    $table      = $table;
    $recuid     = $id;
    $action     = 5; // Action number: 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
    $recpid     = $id; 
    $error      = 3;  // 0 = message, 1 = error, 2 = System Error, 3 = security notice 
    $details    = $status . ': ' . $table . ': ' . $id  . ': ' . $prompt . '|' . var_export( $fieldArray, true );    
    $details_nr = -1;
    $data       = array( );
    $event_pid  = $id; 
    $NEWid      = null;
    $reference->log( $table, $recuid, $action, $recpid, $error, $details, $details_nr, $data, $event_pid );
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']);
}

?>