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
  
    // [String] Geo API URL
  private $googleApiUrl  = 'http://maps.googleapis.com/maps/api/geocode/json?address=%address%&sensor=false';




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
 * @version   4.5.13
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

      // #51478, 130829, dwildt
    if( is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser']['geoupdate'] ) )
    {
      $this->geoupdate( $fieldArray, $reference );
    }
      
    if( is_array( $GLOBALS['TCA'][$table]['ctrl']['tx_browser']['route'] ) )
    {
      $this->route( &$fieldArray, &$reference );
    }
    
    return;
  }



  /***********************************************
  *
  * Geo Update
  *
  **********************************************/

/**
 * geoupdate( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	void
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdate( &$fieldArray ) 
  {
      // RETURN : requirements aren't matched
    if( ! $this->geoupdateRequired( ) )
    {
      $prompt = $this->processStatus . ': ' . $this->processTable . ': ' . $this->processId  . ': ' . var_export( $fieldArray, true );
      $this->log( $prompt );
      return;
    }
      // RETURN : requirements aren't matched

    $arrResult = $this->geoupdateHandleData( $fieldArray );
    if( $arrResult['error'] )
    {
      return;
    }

    return;
  }

/**
 * geoupdateGoogleAPI( )
 *
 * @param	string		$address    : Address
 * @return	array           $geodata    : lon, lat
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateGoogleAPI( $address ) 
  {
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
        $prompt = 'Google API status is: OK';
        $this->log( $prompt );
        break;
      case( $status == 'ZERO_RESULTS' ):
        $prompt = 'Google API status is: ZERO_RESULTS';
        $this->log( $prompt );
        $prompt = 'This means: Query was proper, but Google API doesn\'t know the given address.';
        $this->log( $prompt );
        $prompt = 'Address: ' . $address;
        $this->log( $prompt );
        break;
      case( $status == 'OVER_QUERY_LIMIT' ):
        $prompt = 'Google API status is: OVER_QUERY_LIMIT';
        $this->log( $prompt );
        $prompt = 'This means: Query was proper, but your website overrun the limit of allowed or contracted Google requests.';
        $this->log( $prompt );
        break;
      case( $status == 'REQUEST_DENIED' ):
        $error  = 1;
        $prompt = 'ERROR: Google API status is: REQUEST_DENIED';
        $this->log( $prompt, $error );
        $prompt = 'This means: Query was unproper. Probably because of a wrong sensor parameter';
        $this->log( $prompt );
        $prompt = $googleApiUrl;
        $this->log( $prompt );
        break;
      case( $status == 'INVALID_REQUEST' ):
        $error  = 1;
        $prompt = 'ERROR: Google API status is: INVALID_REQUEST';
        $this->log( $prompt, $error );
        $prompt = 'This means: Query was unproper. Probably because of a missing address';
        $this->log( $prompt );
        $prompt = $googleApiUrl;
        $this->log( $prompt );
        break;
      default:
        $error  = 1;
        $prompt = 'ERROR: Google API status is undefined: ' . $status;
        $this->log( $prompt, $error );
        break;
    }
      // Log the status message

      //  RETURN  : geodata 
    $geodata  = array
                (
                  'lat' => $lat,
                  'lon' => $lon,
                );
    return $geodata;
  }

/**
 * geoupdateHandleData( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	void
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleData( &$fieldArray ) 
  {
      // Get address
    $address = $this->geoupdateHandleDataGetAddress( $fieldArray );
    if( empty( $address ) )
    {
        // update geodata
      $fieldArray[ $geodata[ 'lat' ] ] = null;
      $fieldArray[ $geodata[ 'lon' ] ] = null;

        // logging
      $prompt = 'OK: Address is empty ';
      $this->log( $prompt );
      $prompt = 'OK: latitude and longitude are removed!';
      $this->log( $prompt );
        // logging

      return;
    }
    $prompt = 'Address is "' . $address . '"';
    $this->log( $prompt, 1 );
    
      // Get geodata
    $geodata = $this->geoupdateGoogleAPI( $address ); 
    $lat = $geodata[ 'lat' ];
    $lon = $geodata[ 'lon' ];
    unset( $geodata );
//    list( $lat, $lon ) = $this->geoupdateGoogleAPI( $address ); 

      // RETURN : lan or lot is null
    switch( true )
    {
      case( $lat == null ):
      case( $lon == null ):
        $error  = 1;
        $prompt = 'ERROR: Returned latitude and/or longitude is null!';
        $this->log( $prompt, $error );
        $error  = 0;
        $prompt = 'address: ' . $address;
        $this->log( $prompt, $error );
        return;
        break;
      default:
          // follow the workflow
        break;
    }
      // RETURN : lan or lot is null

      // get lables for geodata
    $geodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata'];

      // update geodata
    $fieldArray[ $geodata[ 'lat' ] ] = $lat;
    $fieldArray[ $geodata[ 'lon' ] ] = $lon;

      // logging
    $prompt = 'OK: latitude and longitude are updated!';
    $this->log( $prompt );
    $prompt = 'Address: ' . $address;
    $this->log( $prompt );
    $prompt = 'latitude: ' . $lat . '; longigute: ' . $lon;
    $this->log( $prompt );
      // logging

    return;
  }

/**
 * geoupdateAddressIsUntouched( )
 *
 * @param	array		$labels     : Address field labels
 * @param	array		$fieldArray : Array of modified fields
 * @return	boolean         $untouched  : true, if address data are untouched
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateAddressIsUntouched( $labels, $fieldArray ) 
  {
      // RETURN : false, an address field is touched at least
    foreach( $labels as $label )
    {
      if( isset ( $fieldArray[ $label ] ) )
      {
        return false;
      }
    }
      // RETURN : false, an address field is touched at least
    
    $prompt = 'OK: Address data are untouched.';
    $this->log( $prompt );
    return true;
  }

/**
 * geoupdateHandleDataGetAddress( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	string          $address    : Address
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddress( $fieldArray ) 
  {
    $address    = null;
    $arrAddress = array( );

      // Labels of the address fields
    $labels = $this->geoupdateHandleDataGetAddressLabels( );
    
      // RETURN : no address field is touched
    if( $this->geoupdateAddressIsUntouched( $labels, $fieldArray ) )
    {
      return;
    }
      // RETURN : no address field is touched
      
      // Get former address data
    $select_fields = implode( ', ', $labels );
    $sqlResult = $this->sqlSelect( $select_fields );
      
      // Set street
    $street = $this->geoupdateHandleDataGetAddressStreet( $fieldArray, $labels, $sqlResult );
    if( $street )
    {
      $arrAddress[ 'street' ] = $street;
    }
      // Set street
    
      // Set location
    $location = $this->geoupdateHandleDataGetAddressLocation( $fieldArray, $labels, $sqlResult );
    if( $location )
    {
      $arrAddress[ 'location' ] = $location;
    }
      // Set location

      // Set areaLevel2
    $areaLevel2 = $this->geoupdateHandleDataGetAddressAreaLevel2( $fieldArray, $labels, $sqlResult );
    if( $areaLevel2 )
    {
      $arrAddress[ 'areaLevel2' ] = $areaLevel2;
    }
      // Set areaLevel2

      // Set areaLevel1
    $areaLevel1 = $this->geoupdateHandleDataGetAddressAreaLevel1( $fieldArray, $labels, $sqlResult );
    if( $areaLevel1 )
    {
      $arrAddress[ 'areaLevel1' ] = $areaLevel1;
    }
      // Set areaLevel1

      // Set country
    $country = $this->geoupdateHandleDataGetAddressCountry( $fieldArray, $labels, $sqlResult );
    if( $country )
    {
      $arrAddress[ 'country' ] = $country;
    }
      // Set country

     // 'Amphitheatre Parkway 1600, Mountain View, CA';
    $address = implode( ', ', $arrAddress );
    
    $prompt = 'OK: address is "' . $address . '"';
    $this->log( $prompt );

    return $address;
  }

/**
 * geoupdateHandleDataGetAddressAreaLevel1( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$labels       : Labels of the fields
 * @param	array		$sqlResult    : Array of former field values (from database)
 * @return	string          $country       : AreaLevel1
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressAreaLevel1( $fieldArray, $labels, $sqlResult ) 
  {
    $areaLevel1 = null;

    if( isset( $labels[ 'areaLevel1' ] ) )
    {
      $areaLevel1 = $sqlResult[ $labels[ 'areaLevel1' ] ];
      if( isset( $fieldArray[ $labels[ 'areaLevel1' ] ] ) )
      {
        $areaLevel1 = $fieldArray[ $labels[ 'areaLevel1' ] ];
      }
    }
    
    return $areaLevel1;
  }

/**
 * geoupdateHandleDataGetAddressAreaLevel2( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$labels       : Labels of the fields
 * @param	array		$sqlResult    : Array of former field values (from database)
 * @return	string          $country       : AreaLevel2
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressAreaLevel2( $fieldArray, $labels, $sqlResult ) 
  {
    $areaLevel2 = null;

    if( isset( $labels[ 'areaLevel2' ] ) )
    {
      $areaLevel2 = $sqlResult[ $labels[ 'areaLevel2' ] ];
      if( isset( $fieldArray[ $labels[ 'areaLevel2' ] ] ) )
      {
        $areaLevel2 = $fieldArray[ $labels[ 'areaLevel2' ] ];
      }
    }
    
    return $areaLevel2;
  }

/**
 * geoupdateHandleDataGetAddressCountry( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$labels       : Labels of the fields
 * @param	array		$sqlResult    : Array of former field values (from database)
 * @return	string          $country       : Country
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressCountry( $fieldArray, $labels, $sqlResult ) 
  {
    $country = null;

    if( isset( $labels[ 'country' ] ) )
    {
      $country = $sqlResult[ $labels[ 'country' ] ];
      if( isset( $fieldArray[ $labels[ 'country' ] ] ) )
      {
        $country = $fieldArray[ $labels[ 'country' ] ];
      }
    }
    
    return $country;
  }

/**
 * geoupdateHandleDataGetAddressLabels( )
 *
 * @return	array          $labels    : Labels
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressLabels( ) 
  {
      // Get field labels
    $tcaCtrlAddress = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];
    
      // Labels of the address fields
    $labels = array( );
    $labels[ 'areaLevel1' ]   = $tcaCtrlAddress[ 'areaLevel1' ]; 
    $labels[ 'areaLevel2' ]   = $tcaCtrlAddress[ 'areaLevel2' ]; 
    $labels[ 'country' ]      = $tcaCtrlAddress[ 'country' ]; 
    $labels[ 'locationZip' ]  = $tcaCtrlAddress[ 'location' ][ 'zip' ]; 
    $labels[ 'locationCity' ] = $tcaCtrlAddress[ 'location' ][ 'city' ]; 
    $labels[ 'streetName' ]   = $tcaCtrlAddress[ 'street' ][ 'name' ]; 
    $labels[ 'streetNumber' ] = $tcaCtrlAddress[ 'street' ][ 'number' ]; 
    
      // Remove empty labels
    foreach( $labels as $key => $label )
    {
      if( empty ( $label ) )
      {
        unset( $labels[ $key ] );
      }
    }
      // Remove empty labels

    return $labels;
  }

/**
 * geoupdateHandleDataGetAddressLocation( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$labels       : Labels of the fields
 * @param	array		$sqlResult    : Array of former field values (from database)
 * @return	string          $location       : Location
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressLocation( $fieldArray, $labels, $sqlResult ) 
  {
      // Get location
    $arrLocation  = array( );
    if( isset( $labels[ 'locationZip' ] ) )
    {
      $arrLocation[ 'zip' ] = $sqlResult[ $labels[ 'locationZip' ] ];
      if( isset( $fieldArray[ $labels[ 'locationZip' ] ] ) )
      {
        $arrLocation[ 'zip' ] = $fieldArray[ $labels[ 'locationZip' ] ];
      }
    }
    if( isset( $labels[ 'locationCity' ] ) )
    {
      $arrLocation[ 'city' ] = $sqlResult[ $labels[ 'locationCity' ] ];
      if( isset( $fieldArray[ $labels[ 'locationCity' ] ] ) )
      {
        $arrLocation[ 'city' ] = $fieldArray[ $labels[ 'locationCity' ] ];
      }
    }
    
    $location = implode( ' ', $arrLocation );

    return $location;
  }

/**
 * geoupdateHandleDataGetAddressStreet( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$labels  : Labels of the fields
 * @param	array		$sqlResult    : Array of former field values (from database)
 * @return	string          $street       : Street
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateHandleDataGetAddressStreet( $fieldArray, $labels, $sqlResult ) 
  {
      // Get street
    $arrStreet  = array( );
    if( isset( $labels[ 'streetName' ] ) )
    {
      $arrStreet[ 'name' ] = $sqlResult[ $labels[ 'streetName' ] ];
      if( isset( $fieldArray[ $labels[ 'streetName' ] ] ) )
      {
        $arrStreet[ 'name' ] = $fieldArray[ $labels[ 'streetName' ] ];
      }
    }
    if( isset( $labels[ 'streetNumber' ] ) )
    {
      $arrStreet[ 'number' ] = $sqlResult[ $labels[ 'streetNumber' ] ];
      if( isset( $fieldArray[ $labels[ 'streetNumber' ] ] ) )
      {
        $arrStreet[ 'number' ] = $fieldArray[ $labels[ 'streetNumber' ] ];
      }
    }
    
    $street = implode( ' ', $arrStreet );

    return $street;
  }

/**
 * geoupdateRequired( )
 *
 * @return	boolean         $requirementsMatched  : true if requierements matched, false if not.
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateRequired( ) 
  {
    $requirementsMatched = true; 
    
    $address  = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];
    $geodata  = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata'];
    $update   = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['update'];
    
    switch( true )
    {
      case( ! $update ):
        $prompt = 'OK: $GLOBALS[TCA][' . $this->processTable . '][ctrl][tx_browser][geoupdate][update] is set to false. '
                . 'Geodata won\'t updated.'
                ;
        $this->log( $prompt );    
        $requirementsMatched = false; 
        return $requirementsMatched;
        break;
      case( empty( $address ) ):
      case( empty( $geodata ) ):
        $error  = 1;
        $prompt = 'ERROR: $GLOBALS[TCA][' . $this->processTable . '][ctrl][tx_browser][geoupdate] is set, '
                . 'but the element [address] and/or [geodata] isn\'t configured! '
                . 'Please take care off a proper TCA configuration!'
                ;
        $this->log( $prompt, $error );
        $requirementsMatched = false; 
        return $requirementsMatched;
        break;
    }
    
    unset( $address );
    unset( $geodata );
    unset( $update  );
    
    return $requirementsMatched;
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
    
    $prompt = '[' . $this->prefixLog . ' (' . $table . ':' . $uid . ')] ' . $prompt . PHP_EOL;
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
 * @return	void
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  private function routeGpxHandleData( &$fieldArray ) 
  {
      // Get field labels
    $fieldGpxfile = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ][ 'gpxfile' ];
    $fieldGeodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'route' ][ 'geodata' ];
    
      // Update TCA array of the current table
    if( ! is_array( $GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ] ) )
    {
      t3lib_div::loadTCA( $this->processTable );
    }
      // Update TCA array of the current table
    
      // Get field configuration
    $confGpxfile = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ][ $fieldGpxfile ][ 'config' ];
    // 130829, dwildt, 1-
    //$confGeodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'columns' ][ $fieldGeodata ][ 'config' ];
    
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

    $gpxXml         = simplexml_load_file( $absPath );
    $arrTrackpoint  = array( );
    $arrTrackpoints = array( );
    $strGeodata     = null;

    foreach( $gpxXml->trk->trkseg->trkpt as $trackPoint )
    {
      foreach( $trackPoint->attributes( ) as $key => $value )
      {
        $arrTrackPoint[ $key ] = $value;
      }
      $arrTrackpoints[] = $arrTrackPoint[ 'lon' ] . ',' . $arrTrackPoint[ 'lat' ];
    }
    
    $strGeodata = implode( PHP_EOL, ( array ) $arrTrackpoints );
    
    unset( $arrTrackpoint );
    unset( $arrTrackpoints );
    
    if( empty( $strGeodata ) )
    {
      $error  = 1;
      $prompt = 'ERROR: GPX file seems to be empty or XML structure is unproper. Data can\ imported.';
      $this->log( $prompt, $error );
      $error  = 1;
      $prompt = 'INFO: Please take care off a proper XML structure: XML->trk->trkseg->trkpt->attributes[ lat || lon ]';
      $this->log( $prompt, $error );
      return;
    }

    $fieldArray[ $fieldGeodata ] = $strGeodata;

    $error  = 1;
    $prompt = 'OK: GPX data are updated!';
    $this->log( $prompt, $error );
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

  
  
  /***********************************************
   *
   * SQL
   *
   **********************************************/

 /**
  * sqlSelect( ):  The method select the values of the given table and select and
  *                 returns the values as a marker array
  *
  * @param	string		$select_fields:  fields for the SQL select
  * @return	array		$result       :  Array with field-value pairs
  * @access public
  * @version  4.5.17
  * @since    4.5.17
  */
  public function sqlSelect( $select_fields )
  {
    $result = null;

      // Set the query
    $from_table     = $this->processTable;
    $where_clause   = 'uid = ' . $this->processId;
    $groupBy        = null;
    $orderBy        = null;
    $limit          = null;

    $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Set the query

      // Execute the query
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute the query

      // RETURN : ERROR
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    if( ! empty( $error ) )
    {
      t3lib_div::devlog('[ERROR/SQL] '. $query,  $this->extKey, 3);
      t3lib_div::devlog('[ERROR/SQL] '. $error,  $this->extKey, 3);
      $prompt = 'ERROR: Unproper SQL query';
      $this->log( $prompt, 1 );
      $prompt = 'query: ' . $query;
      $this->log( $prompt );
      $prompt = 'prompt: ' . $error;
      $this->log( $prompt );
      
      return;
    }
      // RETURN : ERROR

      // Fetch first row only
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

      // Set the result array
    foreach( $row as $key => $value )
    {
      $result[ $key ] = $value;
    }
      // Set the result array

      // RETURN the result array
    return $result;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_tcemainprocdm.php']);
}

?>