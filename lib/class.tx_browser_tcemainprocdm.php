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
 *   77: class tx_browser_tcemainprocdm
 *
 *              SECTION: Hook: processDatamap_postProcessFieldArray
 *  122:     public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$reference )
 *
 *              SECTION: Geo Update
 *  167:     private function geoupdate( &$fieldArray )
 *  218:     private function geoupdateGoogleAPI( &$fieldArray, $address )
 *  248:     private function geoupdateHandleData( &$fieldArray )
 *  334:     private function geoupdateHandleDataGetAddress( $fieldArray, $row )
 *  408:     private function geoupdateHandleDataGetAddressAreaLevel1( $fieldArray, $row )
 *  433:     private function geoupdateHandleDataGetAddressAreaLevel2( $fieldArray, $row )
 *  458:     private function geoupdateHandleDataGetAddressCountry( $fieldArray, $row )
 *  483:     private function geoupdateHandleDataGetAddressLocation( $fieldArray, $row )
 *  527:     private function geoupdateHandleDataGetAddressStreet( $fieldArray, $row )
 *  569:     private function geoupdateIsAddressUntouched( &$fieldArray )
 *  598:     private function geoupdateIsForbiddenByRecord( &$fieldArray )
 *  631:     private function geoupdateRequired( &$fieldArray )
 *  681:     private function geoupdateSetLabels( )
 *  728:     private function geoupdateSetPrompt( $prompt, &$fieldArray )
 *  770:     private function geoupdateSetRow( )
 *
 *              SECTION: Log
 *  864:     public function log( $prompt, $error=0, $action=2 )
 *
 *              SECTION: Route
 *  895:     private function route( &$fieldArray, &$reference )
 *  911:     private function routeGpx( &$fieldArray, &$reference )
 *  940:     private function routeGpxHandleData( &$fieldArray )
 * 1017:     private function routeGpxRequired( $fieldArray )
 *
 * TOTAL FUNCTIONS: 21
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

    // [Array] Geoupdate lables from ext_tables.php
  private $geoupdatelabels = null;

    // [Array] Row of the current record with former data
  private $geoupdaterow  = null;




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
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdate( &$fieldArray )
  {
      // RETURN : requirements aren't matched
    if( ! $this->geoupdateRequired( $fieldArray ) )
    {
      $prompt = $this->processStatus . ': ' . $this->processTable . ': ' . $this->processId  . ': ' . var_export( $fieldArray, true );
      $this->log( $prompt );
      return;
    }
      // RETURN : requirements aren't matched

      // Get lables from ext_tables.php.
    $this->geoupdateSetLabels( );

      // RETURN : no address field is touched
    if( $this->geoupdateIsAddressUntouched( $fieldArray ) )
    {
      return;
    }
      // RETURN : no address field is touched

      // RETURN : no address field is touched
    if( $this->geoupdateIsForbiddenByRecord( $fieldArray ) )
    {
      return;
    }
      // RETURN : no address field is touched



    $arrResult = $this->geoupdateHandleData( $fieldArray );
    if( $arrResult['error'] )
    {
      return;
    }

//    $prompt = $this->processStatus . ': ' . $this->processTable . ': ' . $this->processId  . ': ' . var_export( $fieldArray, true );
//    $this->log( $prompt, 1 );

    return;
  }

/**
 * geoupdateGoogleAPI( )
 *
 * @param	array		$fieldArray : Array of modified fields * @param	string		$address    : Address
 * @param	[type]		$address: ...
 * @return	array		$geodata    : lon, lat
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateGoogleAPI( &$fieldArray, $address )
  {
      // Require map library
    require_once( PATH_typo3conf . 'ext/browser/lib/mapAPI/class.tx_browser_googleApi.php' );
      // Create object
    $objGoogleApi = new tx_browser_googleApi( );

      // Get data from API
    $result = $objGoogleApi->main( $address, $this );

      // Prompt to current record
    if( isset( $result[ 'status'] ) )
    {
      $prompt = $result[ 'status'];
      $this->geoupdateSetPrompt( $prompt, $fieldArray );
    }
      // Prompt to current record

      // RETURN geodata
    return $result[ 'geodata' ];
  }

/**
 * geoupdateHandleData( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	void
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleData( &$fieldArray )
  {
      // get lables for geodata
    $geodata = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata'];

      // Get former address data
    $row = $this->geoupdateSetRow( );

      // Get address
    $address = $this->geoupdateHandleDataGetAddress( $fieldArray, $row );
    if( empty( $address ) )
    {
        // update geodata
      $fieldArray[ $geodata[ 'lat' ] ] = null;
      $fieldArray[ $geodata[ 'lon' ] ] = null;

        // Prompt to the current record
      $prompt = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataRemoved');
      $this->geoupdateSetPrompt( $prompt, $fieldArray );
        // Prompt to the current record

        // logging
      $prompt = 'OK: Address is empty ';
      $this->log( $prompt );
      $prompt = 'OK: ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataUpdate');
      $this->log( $prompt, 1 );
        // logging

      return;
    }

      // Get geodata
    $latLon = $this->geoupdateGoogleAPI( $fieldArray, $address );
    $lat = $latLon[ 'lat' ];
    $lon = $latLon[ 'lon' ];

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

      // update geodata
    $fieldArray[ $geodata[ 'lat' ] ] = $lat;
    $fieldArray[ $geodata[ 'lon' ] ] = $lon;

      // Prompt to the current record
    $prompt = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataUpdate');
    $this->geoupdateSetPrompt( $prompt, $fieldArray );
      // Prompt to the current record

      // logging
    $this->log( $prompt );
    $prompt = 'OK: ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataUpdate');
    $this->log( $prompt, 1 );
    $prompt = 'Address: ' . $address;
    $this->log( $prompt );
    $prompt = 'latitude: ' . $lat . '; longigute: ' . $lon;

      // logging

    return;
  }

/**
 * geoupdateHandleDataGetAddress( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @param	[type]		$row: ...
 * @return	string		$address    : Address
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddress( $fieldArray, $row )
  {
    $address    = null;
    $arrAddress = array( );

      // Set street
    $street = $this->geoupdateHandleDataGetAddressStreet( $fieldArray, $row );
    if( $street )
    {
      $arrAddress[ 'street' ] = $street;
    }
      // Set street

      // Set location
    $location = $this->geoupdateHandleDataGetAddressLocation( $fieldArray, $row );
    if( $location )
    {
      $arrAddress[ 'location' ] = $location;
    }
      // Set location

      // Set areaLevel2
    $areaLevel2 = $this->geoupdateHandleDataGetAddressAreaLevel2( $fieldArray, $row );
    if( $areaLevel2 )
    {
      $arrAddress[ 'areaLevel2' ] = $areaLevel2;
    }
      // Set areaLevel2

      // Set areaLevel1
    $areaLevel1 = $this->geoupdateHandleDataGetAddressAreaLevel1( $fieldArray, $row );
    if( $areaLevel1 )
    {
      $arrAddress[ 'areaLevel1' ] = $areaLevel1;
    }
      // Set areaLevel1

      // Set country
    $country = $this->geoupdateHandleDataGetAddressCountry( $fieldArray, $row );
    if( $country )
    {
      $arrAddress[ 'country' ] = $country;
    }
      // Set country

     // 'Amphitheatre Parkway 1600, Mountain View, CA';
    $address = implode( ', ', $arrAddress );

      // Logging
    switch( $address )
    {
      case( false ):
        $prompt = 'OK: address is empty.';
        break;
      case( true ):
      default:
        $prompt = 'OK: address is "' . $address . '"';
        break;
    }
    $this->log( $prompt );
      // Logging

    return $address;
  }

/**
 * geoupdateHandleDataGetAddressAreaLevel1( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$row    : Array of former field values (from database)
 * @return	string		$country       : AreaLevel1
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddressAreaLevel1( $fieldArray, $row )
  {
    $areaLevel1 = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ) )
    {
      $areaLevel1 = $row[ $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ] ) )
      {
        $areaLevel1 = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ];
      }
    }

    return $areaLevel1;
  }

/**
 * geoupdateHandleDataGetAddressAreaLevel2( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$row    : Array of former field values (from database)
 * @return	string		$country       : AreaLevel2
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddressAreaLevel2( $fieldArray, $row )
  {
    $areaLevel2 = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ) )
    {
      $areaLevel2 = $row[ $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ] ) )
      {
        $areaLevel2 = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ];
      }
    }

    return $areaLevel2;
  }

/**
 * geoupdateHandleDataGetAddressCountry( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$row    : Array of former field values (from database)
 * @return	string		$country       : Country
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddressCountry( $fieldArray, $row )
  {
    $country = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'country' ] ) )
    {
      $country = $row[ $this->geoupdatelabels[ 'address' ][ 'country' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'country' ] ] ) )
      {
        $country = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'country' ] ];
      }
    }

    return $country;
  }

/**
 * geoupdateHandleDataGetAddressLocation( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$row    : Array of former field values (from database)
 * @return	string		$location       : Location
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddressLocation( $fieldArray, $row )
  {
      // Get location
    $arrLocation  = array( );
    if( isset( $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ) )
    {
      $arrLocation[ 'zip' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ] ) )
      {
        $arrLocation[ 'zip' ] = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ];
      }
      if( empty( $arrLocation[ 'zip' ] ) )
      {
        unset( $arrLocation[ 'zip' ] );
      }
    }

    if( isset( $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ) )
    {
      $arrLocation[ 'city' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ] ) )
      {
        $arrLocation[ 'city' ] = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ];
      }
      if( empty( $arrLocation[ 'city' ] ) )
      {
        unset( $arrLocation[ 'city' ] );
      }
    }

    $location = implode( ' ', $arrLocation );

    return $location;
  }

/**
 * geoupdateHandleDataGetAddressStreet( )
 *
 * @param	array		$fieldArray   : Array of modified fields
 * @param	array		$row    : Array of former field values (from database)
 * @return	string		$street       : Street
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateHandleDataGetAddressStreet( $fieldArray, $row )
  {
      // Get street
    $arrStreet  = array( );
    if( isset( $this->geoupdatelabels[ 'address' ][ 'streetName' ] ) )
    {
      $arrStreet[ 'name' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'streetName' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'streetName' ] ] ) )
      {
        $arrStreet[ 'name' ] = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'streetName' ] ];
      }
      if( empty( $arrStreet[ 'name' ] ) )
      {
        unset( $arrStreet[ 'name' ] );
      }
    }
    if( isset( $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ) )
    {
      $arrStreet[ 'number' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ];
      if( isset( $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ] ) )
      {
        $arrStreet[ 'number' ] = $fieldArray[ $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ];
      }
      if( empty( $arrStreet[ 'number' ] ) )
      {
        unset( $arrStreet[ 'number' ] );
      }
    }

    $street = implode( ' ', $arrStreet );

    return $street;
  }

/**
 * geoupdateIsAddressUntouched( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	boolean		$untouched  : true, if address data are untouched
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateIsAddressUntouched( &$fieldArray )
  {
      // RETURN : false, an address field is touched at least
    foreach( $this->geoupdatelabels[ 'address' ] as $label )
    {
      if( isset ( $fieldArray[ $label ] ) )
      {
        return false;
      }
    }
      // RETURN : false, an address field is touched at least

//    $prompt = 'OK: Address data are untouched.';
//    $this->geoupdateSetPrompt( $prompt, $fieldArray );

    $prompt = 'OK: Address data are untouched.';
    $this->log( $prompt );

    return true;
  }

/**
 * geoupdateIsForbiddenByRecord( )
 *
 * @param	array		$fieldArray : Array of modified fields
 * @return	boolean		$untouched  : true, if address data are untouched
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateIsForbiddenByRecord( &$fieldArray )
  {
      // Get former address data
    $row = $this->geoupdateSetRow( );

    if( ! isset( $this->geoupdatelabels[ 'api' ][ 'forbidden' ] ) )
    {
      return false;
    }

    if( $row[ $this->geoupdatelabels[ 'api' ][ 'forbidden' ] ] )
    {
        // Prompt to the current record
//      $prompt = '"' . $this->geoupdatelabels[ 'api' ][ 'forbidden' ] . '"' . PHP_EOL
//              . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataIsForbiddenByRecord')
//              ;
      $prompt = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataIsForbiddenByRecord');
      $this->geoupdateSetPrompt( $prompt, $fieldArray );
        // Prompt to the current record
      return true;
    }

    return false;
  }

/**
 * geoupdateRequired( )
 *
 * @param	[type]		$&$fieldArray: ...
 * @return	boolean		$requirementsMatched  : true if requierements matched, false if not.
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateRequired( &$fieldArray )
  {
    $requirementsMatched = true;

    $address  = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];
    $geodata  = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata'];
    $update   = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['update'];

    switch( true )
    {
      case( ! $update ):
          // Prompt to the current record
        $prompt = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataDisabledByExttablesphp');
        $this->geoupdateSetPrompt( $prompt, $fieldArray );
          // Prompt to the current record

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

/**
 * geoupdateSetLabels( )  : Set lables. Get lables from ext_tables.php.
 *
 * @return	void
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateSetLabels( )
  {
    if( $this->geoupdatelabels !== null )
    {
      return;
    }

    $tcaCtrlAddress = $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];

    $labels = array(
      'address' => array(
        'areaLevel1'   => $tcaCtrlAddress[ 'areaLevel1' ],
        'areaLevel2'   => $tcaCtrlAddress[ 'areaLevel2' ],
        'country'      => $tcaCtrlAddress[ 'country' ],
        'locationZip'  => $tcaCtrlAddress[ 'location' ][ 'zip' ],
        'locationCity' => $tcaCtrlAddress[ 'location' ][ 'city' ],
        'streetName'   => $tcaCtrlAddress[ 'street' ][ 'name' ],
        'streetNumber' => $tcaCtrlAddress[ 'street' ][ 'number' ]
      ),
      'api' => $GLOBALS[ 'TCA' ][ $this->processTable ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['api']
    );

      // Remove empty labels
    foreach( $labels as $groupKey => $group )
    {
      foreach( $group as $labelKey => $label )
      {
        if( empty ( $label ) )
        {
          unset( $labels[$groupKey][ $labelKey ] );
        }
      }
    }
      // Remove empty labels

    $this->geoupdatelabels = $labels;
  }

/**
 * geoupdateSetPrompt( )  : Set lables. Get lables from ext_tables.php.
 *
 * @param	string		$prompt     :
 * @param	array		$fieldArray : Array of modified fields
 * @return	void
 * @version   4.5.13
 * @since     4.5.13
 */
  private function geoupdateSetPrompt( $prompt, &$fieldArray )
  {
    $this->geoupdateSetLabels( );

      // RETURN : no record field for prompting configured
    if( ! isset( $this->geoupdatelabels[ 'api' ][ 'prompt' ] ) )
    {
      $prompt = 'WARN: Geoupdate can\'t prompt to the record, because there is no prompt field configured.';
      $this->log( $prompt, 1 );
      return;
    }
      // RETURN : no record field for prompting configured

      // Get former address data
    $row = $this->geoupdateSetRow( );

    $promptFromRow = $row[ $this->geoupdatelabels[ 'api' ][ 'prompt' ] ];
    if( isset ( $fieldArray[ $this->geoupdatelabels[ 'api' ][ 'prompt' ] ] ) )
    {
      $promptFromRow = $fieldArray[ $this->geoupdatelabels[ 'api' ][ 'prompt' ] ];
    }

    $date     = date('Y-m-d H:i:s');
    $browser  = ' - ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptBrowserPhrase'). ':';
    $prompt   = '* ' . $date . $browser . PHP_EOL
              . '  ' . $prompt . PHP_EOL
              . $promptFromRow
              ;
    // 130902, dwildt, 1-
    //$prompt = $GLOBALS['TYPO3_DB']->quoteStr( $prompt, $this->processTable );

    $fieldArray[ $this->geoupdatelabels[ 'api' ][ 'prompt' ] ] = $prompt;
  }

 /**
  * geoupdateSetRow( ):  The method select the values of the given table and select and
  *                 returns the values as a marker array
  *
  * @return	array		$row :  Array with field-value pairs
  * @access private
  * @version  4.5.17
  * @since    4.5.17
  */
  private function geoupdateSetRow( )
  {
      // RETURN : row is set before
    if( $this->geoupdaterow != null )
    {
      return $this->geoupdaterow;
    }
      // RETURN : row is set before

    $labels = $this->geoupdatelabels[ 'address' ]
            + $this->geoupdatelabels[ 'api' ]
            ;

    $select_fields  = implode( ', ', $labels );

      // RETURN : select fields are empty
    if( empty( $select_fields ) )
    {
      return null;
    }
      // RETURN : select fields are empty

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
    $this->geoupdaterow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    return $this->geoupdaterow;
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
 * @param	integer		$error  : 0 = notice, 1 = warn, 2 = error
 * @param	string		$action : 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
 * @return	void
 * @access public
 * @version   4.5.7
 * @since     4.5.7
 */
  public function log( $prompt, $error=0, $action=2 )
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
 * @param	[type]		$$fieldArray: ...
 * @return	boolean		$requirementsMatched  : true if requierements matched, false if not.
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