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
    // [String] status of the current process: update, edit, delete, moved
  private $prefixLog = 'tx_browser ';

    // [String] status of the current process: update, edit, delete, moved
  private $processStatus  = null;
    // [String] label of the table of the current process
  private $processTable   = null;
    // [String] record uid of the current process
  private $processId      = null;
  
    // [Object] parent object
  private $pObj       = null;



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
 * @param	array		$fieldArray : modified fields - reference!
 * @param	object		$reference  : parent object - reference!
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$reference ) 
  {
      // RETURN : current table is without any tx_browser configuration
    if( ! is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser'] ) )
    {
      return;
    }
      // RETURN : current table is without any tx_browser configuration
    
      // Initial global variables
    $this->processStatus  = $status;
    $this->processTable   = $table;
    $this->processId      = $id;
    $this->pObj           = $reference;

      
    switch( true )
    {
      case( is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser']['route'] ) ):
        $this->route( &$fieldArray, &$reference );
          // follow cases below
      default:
          // follow the workflow
        break;
    }
    
    return;
  }



  /***********************************************
  *
  * Log
  *
  **********************************************/

/**
 * log( )
 *
 * @param	string		$prompt : prompt
 * @param	integer		$error  : 0 = message, 1 = error, 2 = System Error, 3 = security notice 
 * @param	string		$action : 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function log( $prompt, $error=0, $action=2 ) 
  {
    $table  = $this->processTable;
    $uid    = $this->processId;
    $pid    = null; 
    
    $prompt = '[' . $this->prefixLog . ' (' . $table . ':' . $uid . ')] ' . $prompt;
    //    $details_nr = -1;
    //    $data       = array( );
    //    $event_pid  = null; // page id
    //    $NEWid      = null;
    $this->pObj->log( $table, $uid, $action, $pid, $error, $prompt );
  }



  /***********************************************
  *
  * Route
  *
  **********************************************/

/**
 * route( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @param	object		$reference  : reference to parent object
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function route( &$fieldArray, &$reference ) 
  {
    $this->routeGpx( &$fieldArray, &$reference );

    return;
  }

/**
 * routeGpx( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @param	object		$reference  : reference to parent object
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function routeGpx( &$fieldArray, &$reference ) 
  {
      // RETURN : requirements aren't matched
    if( ! $this->routeGpxRequired( $fieldArray ) )
    {
      return;
    }
      // RETURN : requirements aren't matched

    $arrResult = $this->routeGpxHandleData( &$fieldArray, &$reference );
    if( $arrResult['error'] )
    {
      return;
    }

    return;
    $error  = 1;
    $prompt = $this->processStatus . ': ' . $this->processTable . ': ' . $this->processId  . ': ' . var_export( $fieldArray, true );
    $this->log( $prompt, $error );
  }

/**
 * routeGpxHandleData( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @param	object		$reference  : reference to parent object
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function routeGpxHandleData( &$fieldArray, &$reference ) 
  {
      // Get field labels
    $fieldGpxfile = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ][ 'gpxfile' ];
    $fieldGeodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ][ 'geodata' ];
    
      // Update TCA array of the current table
    if (!is_array($GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ]))
    {
      t3lib_div::loadTCA( $this->processTable );
    }
      // Update TCA array of the current table
    
      // Get field configuration
    $confGpxfile = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ][ $fieldGpxfile ][ 'config' ];
    $confGeodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ][ $fieldGeodata ][ 'config' ];
    
      // Get the absoulte path of the uploaded file
    $uploadfolder = $confGpxfile[ 'uploadfolder' ];
    $absPath      = t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/' . $uploadfolder . '/' . $fieldArray[ $fieldGpxfile ];
    
      // RETURN : file is missing
    if( ! file_exists( $absPath ) )
    {
      $error  = 1;
      $prompt = 'ERROR: file is missing: ' . $absPath;
      $this->log( $prompt, $error );    
      return;
    }
      // RETURN : file is missing

    $xml = simplexml_load_file( $absPath );

    foreach( $xml->trk->trkseg->trkpt as $point )
    {
      foreach( $point->attributes( ) as $key => $value )
      {
        echo $key . ' = "' . $value . '"; ';
      }
    }
//    $error  = 1;
//    $prompt = $absPath . ': ' . $fileExist;
//    $this->log( $prompt, $error );
//
//    $error  = 1;
//    $prompt = $this->processStatus . ': ' . $this->processTable . ': ' . $this->processId  . ': ' . var_export( $fieldArray, true );
//    $this->log( $prompt, $error );
  }

/**
 * routeGpx( )
 *
 * @return	boolean         $requirementsMatched  : true if requierements matched, false if not.
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function routeGpxRequired( $fieldArray ) 
  {
    $requirementsMatched = true; 
    
    $fieldGpxfile = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ]['gpxfile'];
    $fieldGeodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ]['geodata'];
    
    switch( true )
    {
      case( ! isset( $fieldArray[ $fieldGpxfile ] ) ):
        $prompt = 'OK: No GPX file is uploaded. Nothing to do.';
        $this->log( $prompt );    
        $requirementsMatched = false; 
        return $requirementsMatched;
        break;
      case( empty( $fieldArray[ $fieldGpxfile ] ) ):
        $prompt = 'OK: GPX file is removed. Nothing to do.';
        $this->log( $prompt );    
        $requirementsMatched = false; 
        return $requirementsMatched;
        break;
      case( empty( $fieldGpxfile ) ):
      case( empty( $fieldGeodata ) ):
        $error  = 1;
        $prompt = 'ERROR: $GLOBALS[TCA][' . $this->processTable . '][ctrl][tx_browser][route] is set, '
                . 'but the element [gpxfile] and/or [geodata] isn\'t configured! '
                . 'Please take care off a proper TCA configuration!'
                ;
        $this->log( $prompt, $error );
        $requirementsMatched = false; 
        return $requirementsMatched;
        break;
    }
    
    unset( $fieldArray );
    unset( $fieldGpxfile );
    unset( $fieldGeodata );
    
    return $requirementsMatched;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']);
}

?>