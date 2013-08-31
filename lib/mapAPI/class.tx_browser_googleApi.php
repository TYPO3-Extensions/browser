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
* The class tx_browser_googleApi bundles methods for evaluating data in backend forms
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 4.5.13
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

class tx_browser_googleApi 
{
    // [String] Geo API URL
  private $googleApiUrl  = 'http://maps.googleapis.com/maps/api/geocode/json?address=%address%&sensor=false';
  



/**
 * main( )
 *
 * @param	string		$address    : address in the syntax like '1600 Amphitheatre Parkway, Mountain View, CA'
 * @param	object		$fieldArray : Array of modified fields * @param	string		$address    : Address
 * @return	array           $returnData     : geodata( lon, lat), status
 * 
 * @access    public
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  public function main( $address, $pObj ) 
  {
    $returnData   = null;
    $returnStatus = null;
    
      // Set URL
    $urlAddress = urlencode( $address );
    $googleApiUrl  = str_replace( '%address%', $urlAddress, $this->googleApiUrl );
    
      // Get geodata from Google API
    $json   = file_get_contents( $googleApiUrl );
    $data   = json_decode( $json );
    $lat    = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $lon    = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    $status = $data->{'status'};
      // Get geodata from Google API

      // Log the status message
    switch( true )
    {
      case( $status == 'OK' ):
//          // Prompt to the current record
        $returnStatus = null;
//        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiOK');
//          // Prompt to the current record
        $prompt = 'Google API status is: OK';
        $pObj->log( $prompt );
        break;
      case( $status == 'ZERO_RESULTS' ):
          // Prompt to the current record
        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiZERO_RESULTS');
        $prompt = 'Google API status is: ZERO_RESULTS';
        $pObj->log( $prompt, 1 );
        $prompt = 'This means: Query was proper, but Google API doesn\'t know the given address.';
        $pObj->log( $prompt );
        $prompt = 'Address: ' . $address;
        $pObj->log( $prompt );
        break;
      case( $status == 'OVER_QUERY_LIMIT' ):
          // Prompt to the current record
        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiOVER_QUERY_LIMIT');
        $prompt = 'Google API status is: OVER_QUERY_LIMIT';
        $pObj->log( $prompt, 1 );
        $prompt = 'This means: Query was proper, but your website overrun the limit of allowed or contracted Google requests.';
        $pObj->log( $prompt );
        break;
      case( $status == 'REQUEST_DENIED' ):
          // Prompt to the current record
        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiREQUEST_DENIED');
        $prompt = 'ERROR: Google API status is: REQUEST_DENIED';
        $pObj->log( $prompt, 1 );
        $prompt = 'This means: Query was unproper. Probably because of a wrong sensor parameter';
        $pObj->log( $prompt );
        $prompt = $googleApiUrl;
        $pObj->log( $prompt );
        break;
      case( $status == 'INVALID_REQUEST' ):
          // Prompt to the current record
        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiINVALID_REQUEST');
        $prompt = 'ERROR: Google API status is: INVALID_REQUEST';
        $pObj->log( $prompt, 2 );
        $prompt = 'This means: Query was unproper. Probably because of a missing address';
        $pObj->log( $prompt );
        $prompt = $googleApiUrl;
        $pObj->log( $prompt );
        break;
      default:
          // Prompt to the current record
        $returnStatus = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/mapAPI/locallang.xml:statusGoogleApiUNDEFINED');
        $prompt = 'ERROR: Google API status is undefined: ' . $status;
        $pObj->log( $prompt, 2 );
        break;
    }
      // Log the status message

      //  RETURN  : geodata 
    $returnData  = array
                (
                  'geodata' => array(
                    'lat' => $lat,
                    'lon' => $lon
                  ),
                  'status' => $returnStatus,
                );
    return $returnData;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/mapAPI/class.tx_browser_googleApi.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/mapAPI/class.tx_browser_googleApi.php']);
}

?>