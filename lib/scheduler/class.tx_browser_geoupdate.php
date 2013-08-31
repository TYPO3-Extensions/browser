<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Dirk Wildt (http://wildt.at.die-netzmacher.de/)
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  105: class tx_browser_Geoupdate extends tx_scheduler_Task
 *
 *              SECTION: Main
 *  300:     public function execute( )
 *
 *              SECTION: Additional information for scheduler
 *  380:     public function getAdditionalInformation( )
 *
 *              SECTION: Converting
 *  406:     private function convertContent( $xml )
 *  423:     private function convertContentDrsMail( $success )
 *  447:     private function convertContentInstance( )
 *
 *              SECTION: DRS - Development Reporting System
 *  476:     private function drsDebugTrail( $level = 1 )
 *  522:     public function drsMailToAdmin( $subject='Information', $body=null )
 *
 *              SECTION: Get private
 *  635:     private function getContent( )
 *  665:     private function getContentInstance( )
 *
 *              SECTION: Get public
 *  691:     public function getAdminmail( )
 *  704:     public function getTestMode( )
 *  717:     public function getTable( )
 *  730:     public function getReportMode( )
 *
 *              SECTION: Initials
 *  764:     private function init( )
 *  793:     private function initDRS( )
 *  841:     private function initRequirements( )
 *  870:     private function initRequirementsAdminmail( )
 *  898:     private function initRequirementsAllowUrlFopen( )
 *  937:     private function initRequirementsOs( )
 * 1002:     private function initTimetracking( )
 *
 *              SECTION: Set public
 * 1130:     public function setAdminmail( $value )
 * 1144:     public function setTestMode( $value )
 * 1158:     public function setTable( $value )
 * 1172:     public function setReportMode( $value )
 *
 *              SECTION: Time tracking
 * 1208:     private function timeTracking_init( )
 * 1230:     private function timeTracking_log( $debugTrailLevel, $prompt )
 * 1282:     private function timeTracking_prompt( $debugTrailLevel, $prompt )
 *
 *              SECTION: Update
 * 1320:     private function updateDatabase( $content )
 * 1339:     private function updateDatabaseDrsMail( $success )
 * 1363:     private function updateDatabaseInstance( )
 *
 * TOTAL FUNCTIONS: 36
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Class "tx_browser_Geoupdate" provides procedures for check import and control browser mailboxes
 *
 * @author        Dirk Wildt (http://wildt.at.die-netzmacher.de/)
 * @package        TYPO3
 * @subpackage    browser
 * @version       4.5.13
 * @since         4.5.13
 */
class tx_browser_Geoupdate extends tx_scheduler_Task {

  /**
    * Extension key
    *
    * @var string $extKey
    */
    public $extKey = 'browser';

  /**
    * Extension configuration by the extension manager
    *
    * @var array $extConf
    */
    private $extConf;

  /**
    * The convert object
    *
    * @var object
    */
    private $convert;

  /**
    * DRS mode: display prompt in every case
    *
    * @var boolean $drsModeAll
    */
    public $drsModeAll;

  /**
    * DRS mode: display prompt in error case only
    *
    * @var boolean $drsModeError
    */
    public $drsModeError;

  /**
    * DRS mode: display prompt in warning case only
    *
    * @var boolean $drsModeWarn
    */
    public $drsModeWarn;

  /**
    * DRS mode: display prompt in info case only
    *
    * @var boolean $drsModeInfo
    */
    public $drsModeInfo;

  /**
    * DRS mode: display prompt in warning case only
    *
    * @var boolean $drsModeConvert
    */
    public $drsModeConvert;

  /**
    * DRS mode: display prompt in performance case
    *
    * @var boolean $drsModePerformance
    */
    public $drsModePerformance;

  /**
    * DRS mode: display prompt in geoupdate case
    *
    * @var boolean $drsModeGeoupdate
    */
    public $drsModeGeoupdate;

  /**
    * DRS mode: display prompt in sql case
    *
    * @var boolean $drsModeSql
    */
    public $drsModeSql;

  /**
    * DRS mode: display prompt in warning case only
    *
    * @var boolean $drsModeXml
    */
    public $drsModeUpdate;

  /**
    * DRS mode: display prompt in warning case only
    *
    * @var boolean $drsModeXml
    */
    public $drsModeXml;

  /**
    * An email address to be used during the process
    *
    * @var string $browser_browserAdminEmail
    */
    private $browser_browserAdminEmail;

  /**
    * Report mode: ever, never, update
    *
    * @var string
    */
    private $browser_testMode;

  /**
    * Import URL
    *
    * @var string
    */
    private $browser_table;

  /**
    * Report mode: ever, never, update, warn
    *
    * @var string
    */
    private $browser_reportMode;

   /**
    * Geo API URL
    *
    * @var string
    */
    private $googleApiUrl  = 'http://maps.googleapis.com/maps/api/geocode/json?address=%address%&sensor=false';
  
   /**
    * Geoupdate lables from ext_tables.php
    *
    * @var array
    */
    private $geoupdatelabels = null;

   /**
    * Rows of the current table with geodata
    *
    * @var array
    */
    private $geoupdaterows  = null;
    
   /**
    * Update values for the current row
    *
    * @var array
    */
    private $geoupdateUpdateValues = null;
    
   /**
    * Statistic data
    *
    * @var array
    */
    private $geoupdateStatistic = null;
    
   /**
    * t3lib_timeTrack object
    *
    * @var object
    */
    private $TT;

   /**
    * Endtime of previous process
    *
    * @var integer
    */
    private $tt_prevEndTime;

  /**
    * Level of warning
    *
    * @var integer
    */
    private $tt_prevPrompt;

  /**
    * Startime of the script
    *
    * @var integer
    */
    private $tt_startTime;

  /**
    * The update object
    *
    * @var object
    */
    private $update;

  /**
    * The get object
    *
    * @var object
    */
    private $get;



  /***********************************************
   *
   * Main
   *
   **********************************************/

/**
 * execute( )  : Function executed from the Scheduler.
 *               * Sends an email
 *
 * @return	boolean
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function execute( )
  {
      // Init var for debug trail
    $debugTrailLevel = 1;

      // RETURN false : init is unproper
    if( ! $this->init( ) )
    {
      $this->timeTracking_log( $debugTrailLevel, 'END' );
      return false;
    }
      // RETURN false : init is unproper

      // RETURN false : geoupdate is unproper
    if( ! $this->geoupdate( ) )
    {
      $this->timeTracking_log( $debugTrailLevel, 'END' );
      return false;
    }
      // RETURN false : content is unproper

      // DRS
    if( $this->drsModeInfo )
    {
      $prompt = 'Success!';
      t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, -1 );
    }
      // DRS

      // E-mail to admin
    $subject  = 'Success!';
    $body     = 'Task is done with success.' . PHP_EOL
              . PHP_EOL
              . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')';
    $this->drsMailToAdmin( $subject, $body, 'update' );
      // E-mail to admin

    $this->timeTracking_log( $debugTrailLevel, 'END' );

    return true;
  }



  /***********************************************
   *
   * Geo Update
   *
   **********************************************/

/**
 * geoupdate( ) : 
 *
 * @return	boolean   Information to display
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdate( )
  {
      // RETURN : requirements aren't matched
    if( ! $this->geoupdateRequired( ) )
    {
      return false;
    }
      // RETURN : requirements aren't matched

    if( ! $this->geoupdateInit( ) )
    {
      return false;
    }

    if( ! $this->geoupdateUpdate( ) )
    {
      $this->geoupdateStatistic( );
      return false;
    }

    $this->geoupdateStatistic( );
    return true;
  }



  /***********************************************
   *
   * Geo Update - Init
   *
   **********************************************/

/**
 * geoupdateInit( ) : 
 *
 * @param       array   $rows
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateInit( )
  {
    if( ! $this->geoupdateInitLabels( ) )
    {
      return false;
    }

    if( ! $this->geoupdateInitRows( ) )
    {
      return false;
    }

    return true;
  }

/**
 * geoupdateInitLabels( )  : Set lables. Get lables from ext_tables.php.
 *
 * @return	boolean     true in case of success
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateInitLabels( ) 
  {
    if( $this->geoupdatelabels !== null )
    {
      return true;
    }
    
    $tcaCtrlAddress = $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];
    
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
      'api'     => $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['api'],
      'geodata' => $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata']

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
    
      // RETURN : no record field for prompting configured
    if( ! isset( $this->geoupdatelabels[ 'api' ][ 'prompt' ] ) )
    {
      $prompt = 'WARN: Geoupdate can\'t prompt to the records, because there is no prompt field configured.';
      $this->log( $prompt, 1 );
      return;
    }
      // RETURN : no record field for prompting configured
       
    return true;
  }
  
 /**
  * geoupdateInitRows( ):  
  *
  * @return	boolean		true in case of success
  * @access   private
  * @version  4.5.17
  * @since    4.5.17
  */
  private function geoupdateInitRows( )
  {
      // RETURN : row is set before
    if( $this->geoupdaterows != null )
    {
      return true;
    }
      // RETURN : row is set before

    $labels = array( 'uid' )
            + $this->geoupdatelabels[ 'address' ]
            + $this->geoupdatelabels[ 'api' ] 
            + $this->geoupdatelabels[ 'geodata' ] 
            ;

    $select_fields  = implode( ', ', $labels );

      // RETURN : select fields are empty
    if( empty( $select_fields ) )
    {
      $prompt = 'ERROR: SELECT fields are empty!';
      $this->log( $prompt, 2 );
      return false;
    }
      // RETURN : select fields are empty

      // Set the query
    $from_table     = $this->browser_table;
    //$where_clause   = 'uid = ' . $this->processId;
    $where_clause   = null;
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
      $this->log( $prompt, 2 );
      $prompt = 'query: ' . $query;
      $this->log( $prompt, 1 );
      $prompt = 'prompt: ' . $error;
      $this->log( $prompt, 1 );
      
      return false;
    }
      // RETURN : ERROR

    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $this->geoupdaterows[ $row[ 'uid' ] ] = $row;
    }

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

//    $prompt = '[tx_browser_Geoupdate]: ' . var_export( $this->geoupdaterows, true );
//    $this->log( $prompt );
    
    return true;
  }



  /***********************************************
   *
   * Geo Update - Requirements
   *
   **********************************************/

/**
 * geoupdateRequired( )
 *
 * @return	boolean         true if requierements matched, false if not.
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateRequired( ) 
  {
    if( ! $this->geoupdateRequiredTable( ) )
    {
      return false;
    }
    
    $address  = $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['address'];
    $geodata  = $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['geodata'];
    $update   = $GLOBALS[ 'TCA' ][ $this->browser_table ][ 'ctrl' ][ 'tx_browser' ][ 'geoupdate' ]['update'];
    
    switch( true )
    {
      case( ! $update ):
        $prompt = 'WARN: $GLOBALS[TCA][' . $this->browser_table . '][ctrl][tx_browser][geoupdate][update] is set to false. '
                . 'Geodata won\'t updated.'
                ;
          // DRS
        if( $this->drsModeInfo )
        {
          t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 2 );
        }
          // E-mail to admin
        $subject  = 'TYPO3 Browser: Geoupdate is disabled';
        $body     = $prompt . PHP_EOL
                  . PHP_EOL
                  . PHP_EOL
                  . __METHOD__ . ' (' . __LINE__ . ')';
        $this->drsMailToAdmin( $subject, $body, 'warn' );
          // E-mail to admin
        return false;
        break;
      case( empty( $address ) ):
      case( empty( $geodata ) ):
        $prompt = 'ERROR: $GLOBALS[TCA][' . $this->browser_table . '][ctrl][tx_browser][geoupdate] is set, '
                . 'but the element [address] and/or [geodata] isn\'t configured! '
                . 'Please take care off a proper TCA configuration!'
                ;
          // DRS
        if( $this->drsModeInfo )
        {
          t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
        }
          // E-mail to admin
        $subject  = 'TYPO3 Browser: Geoupdate is disabled';
        $body     = $prompt . PHP_EOL
                  . PHP_EOL
                  . PHP_EOL
                  . __METHOD__ . ' (' . __LINE__ . ')';
        $this->drsMailToAdmin( $subject, $body, 'error' );
          // E-mail to admin
        return false;
        break;
    }
    
    unset( $address );
    unset( $geodata );
    unset( $update  );
    
    return true;
  }

/**
 * geoupdateRequiredTable( )
 *
 * @return	boolean         true if requierements matched, false if not.
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateRequiredTable( ) 
  {
    if( isset( $GLOBALS[ 'TCA' ][ $this->browser_table ] ) )
    {
      return true;
    }
    
      // Prompt
    $prompt = 'ERROR: $GLOBALS[TCA][' . $this->browser_table . '] isn\'t set.';

      // DRS
    if( $this->drsModeInfo )
    {
      t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
    }
      // DRS

      // E-mail to admin
    $subject  = 'ERROR!';
    $body     = $prompt . PHP_EOL
              . PHP_EOL
              . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')';
    $this->drsMailToAdmin( $subject, $body );
      // E-mail to admin

    return false;
  }



  /***********************************************
   *
   * Geo Update - Statistic
   *
   **********************************************/

/**
 * geoupdateStatistic( ) : 
 *
 * @return	void
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateStatistic( )
  {
    $this->geoupdateStatistic[ 'rows' ] = $this->geoupdateStatistic[ 'addressEmpty' ]
                                        + $this->geoupdateStatistic[ 'forbidden' ]
                                        + $this->geoupdateStatistic[ 'geodataNotEmpty' ]
                                        + $this->geoupdateStatistic[ 'errors' ]
                                        + $this->geoupdateStatistic[ 'updatedTest' ]
                                        + $this->geoupdateStatistic[ 'updated' ]
                                        ;
    
    $prompt = 'Statistic: handled rows #' . $this->geoupdateStatistic[ 'rows' ];
    $this->log( $prompt );
    $prompt = 'Statistic: rows with an empty address #' . $this->geoupdateStatistic[ 'addressEmpty' ];
    $this->log( $prompt );
    $prompt = 'Statistic: rows with no permission for update #' . $this->geoupdateStatistic[ 'forbidden' ];
    $this->log( $prompt );
    $prompt = 'Statistic: rows with geo data #' . $this->geoupdateStatistic[ 'geodataNotEmpty' ];
    $this->log( $prompt );
    $prompt = 'Statistic: errors #' . $this->geoupdateStatistic[ 'errors' ];
    $this->log( $prompt );
    switch( $this->browser_testMode )
    {
      case( 'enabled' ):
        $prompt = 'Statistic: not updates rows because of test mode #' . $this->geoupdateStatistic[ 'updatedTest' ];
        break;
      case( 'disabled' ):
        $prompt = 'Statistic: updates rows #' . $this->geoupdateStatistic[ 'updated' ];
        break;
      default:
        $prompt = 'ERROR: browser_testMode is undefined: "' . $this->browser_testMode . '"';
        $this->log( $prompt, 2 );
        die( $prompt );
        break;
    }
    $this->log( $prompt );

  }



  /***********************************************
   *
   * Geo Update - Update
   *
   **********************************************/

/**
 * geoupdateUpdate( ) : 
 *
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdate( )
  {
    $this->geoupdateStatistic = array( 
      'addressEmpty'    => 0,
      'errors'          => 0,
      'forbidden'       => 0,
      'geodataNotEmpty' => 0,
      'rows'            => 0,
      'updated'         => 0,
      'updatedTest'     => 0,
    );
    foreach( $this->geoupdaterows as $row )
    {
      if( ! $this->geoupdateUpdateRowRequired( $row ) )
      {
        continue;
      }
      $this->geoupdateUpdateRowUpdate( $row );
      break;
    }
    
    return true;
  }

/**
 * geoupdateUpdateGetAddress( )
 *
 * @return	string          $address    : Address
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddress( $row ) 
  {
    $address    = null;
    $arrAddress = array( );

      // Set street
    $street = $this->geoupdateUpdateGetAddressStreet( $row );
    if( $street )
    {
      $arrAddress[ 'street' ] = $street;
    }
      // Set street
    
      // Set location
    $location = $this->geoupdateUpdateGetAddressLocation( $row );
    if( $location )
    {
      $arrAddress[ 'location' ] = $location;
    }
      // Set location

      // Set areaLevel2
    $areaLevel2 = $this->geoupdateUpdateGetAddressAreaLevel2( $row );
    if( $areaLevel2 )
    {
      $arrAddress[ 'areaLevel2' ] = $areaLevel2;
    }
      // Set areaLevel2

      // Set areaLevel1
    $areaLevel1 = $this->geoupdateUpdateGetAddressAreaLevel1( $row );
    if( $areaLevel1 )
    {
      $arrAddress[ 'areaLevel1' ] = $areaLevel1;
    }
      // Set areaLevel1

      // Set country
    $country = $this->geoupdateUpdateGetAddressCountry( $row );
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
 * geoupdateUpdateGetAddressAreaLevel1( )
 *
 * @param	array		$row    : Array of former field values (from database)
 * @return	string          $country       : AreaLevel1
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddressAreaLevel1( $row )
  {
    $areaLevel1 = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ) )
    {
      $areaLevel1 = $row[ $this->geoupdatelabels[ 'address' ][ 'areaLevel1' ] ];
    }
    
    return $areaLevel1;
  }

/**
 * geoupdateUpdateGetAddressAreaLevel2( )
 *
 * @param	array		$row    : Array of former field values (from database)
 * @return	string          $country       : AreaLevel2
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddressAreaLevel2( $row )
  {
    $areaLevel2 = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ) )
    {
      $areaLevel2 = $row[ $this->geoupdatelabels[ 'address' ][ 'areaLevel2' ] ];
    }
    
    return $areaLevel2;
  }

/**
 * geoupdateUpdateGetAddressCountry( )
 *
 * @param	array		$row    : Array of former field values (from database)
 * @return	string          $country       : Country
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddressCountry( $row )
  {
    $country = null;

    if( isset( $this->geoupdatelabels[ 'address' ][ 'country' ] ) )
    {
      $country = $row[ $this->geoupdatelabels[ 'address' ][ 'country' ] ];
    }
    
    return $country;
  }

/**
 * geoupdateUpdateGetAddressLocation( )
 *
 * @param	array		$row    : Array of former field values (from database)
 * @return	string          $location       : Location
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddressLocation( $row )
  {
      // Get location
    $arrLocation  = array( );
    if( isset( $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ) )
    {
      $arrLocation[ 'zip' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'locationZip' ] ];
      if( empty( $arrLocation[ 'zip' ] ) )
      {
        unset( $arrLocation[ 'zip' ] );
      }
    }

    if( isset( $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ) )
    {
      $arrLocation[ 'city' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'locationCity' ] ];
      if( empty( $arrLocation[ 'city' ] ) )
      {
        unset( $arrLocation[ 'city' ] );
      }
    }
    
    $location = implode( ' ', $arrLocation );

    return $location;
  }

/**
 * geoupdateUpdateGetAddressStreet( )
 *
 * @param	array		$row    : Array of former field values (from database)
 * @return	string          $street       : Street
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateGetAddressStreet( $row )
  {
      // Get street
    $arrStreet  = array( );
    if( isset( $this->geoupdatelabels[ 'address' ][ 'streetName' ] ) )
    {
      $arrStreet[ 'name' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'streetName' ] ];
      if( empty( $arrStreet[ 'name' ] ) )
      {
        unset( $arrStreet[ 'name' ] );
      }
    }
    if( isset( $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ) )
    {
      $arrStreet[ 'number' ] = $row[ $this->geoupdatelabels[ 'address' ][ 'streetNumber' ] ];
      if( empty( $arrStreet[ 'number' ] ) )
      {
        unset( $arrStreet[ 'number' ] );
      }
    }
    
    $street = implode( ' ', $arrStreet );

    return $street;
  }

/**
 * geoupdateUpdateRowRequired( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowRequired( $row )
  {
    // Durchlaufe alle Datensätze der angegebenen Tabelle
    //    Wenn Breiten- oder Längengrad leer, prüfe ob Adresse vorhanden.
    //      Wenn Adresse vorhanden, aktualisiere Breiten- und Längengrad

    if( ! $this->geoupdateUpdateRowRequiredPermission( $row ) )
    {
      return false;
    }

    if( ! $this->geoupdateUpdateRowRequiredGeodata( $row ) )
    {
      return false;
    }

    if( ! $this->geoupdateUpdateRowRequiredAddress( $row ) )
    {
      return false;
    }

    return true;
  }

/**
 * geoupdateUpdateRowRequiredAddress( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowRequiredAddress( $row )
  {
      // RETURN : true, one address field contains content at least
    foreach( $this->geoupdatelabels[ 'address' ] as $label )
    {
      if( isset ( $row[ $label ] ) )
      {
        return true;
      }
    }
      // RETURN : true, one address field contains content at least

      // prompt to syslog
    $prompt = 'NO UPDATE: Adress fields don\'t contain any data.';
    $this->log( $prompt, 0, $row[ 'uid' ] );

    $this->geoupdateStatistic[ 'addressEmpty' ] = $this->geoupdateStatistic[ 'addressEmpty' ]
                                                + 1
                                                ;

      // RETURN : false, no address field doesn't contain any data
    return false;
  }

/**
 * geoupdateUpdateRowRequiredGeodata( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowRequiredGeodata( $row )
  {
    $prompt = var_export( $row, true );
    $this->log( $prompt, 0, $row[ 'uid' ] );
    $prompt = var_export( $this->geoupdatelabels, true );
    $this->log( $prompt, 0, $row[ 'uid' ] );

      // RETURN : false, latitude or longitude contain content at least
    foreach( $this->geoupdatelabels[ 'geodata' ] as $label )
    {
      if( ! isset ( $row[ $label ] ) )
      {
        continue;
      }
        
      if( ! $row[ $label ] )
      {
        continue;
      }
      
        // prompt to syslog
      $prompt = 'NO UPDATE: latitude and/or longitude contain content';
      $this->log( $prompt, 0, $row[ 'uid' ] );
        // Statistic
      $this->geoupdateStatistic[ 'geodataNotEmpty' ]  = $this->geoupdateStatistic[ 'geodataNotEmpty' ]
                                                      + 1
                                                      ;
      return false;
    }
      // RETURN : false, latitude or longitude contain content at least

      // RETURN : true, latitude and longitude don't contain any content
    return true;
  }

/**
 * geoupdateUpdateRowRequiredPermission( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowRequiredPermission( $row )
  {
    if( ! isset( $this->geoupdatelabels[ 'api' ][ 'forbidden' ] ) )
    {
      return true;
    }
    
    if( $row[ $this->geoupdatelabels[ 'api' ][ 'forbidden' ] ] )
    {
        // Prompt to the current record
      $prompt = $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptGeodataIsForbiddenByRecord');
      $this->geoupdateUpdateSetPrompts( $prompt );
        // prompt to syslog
      $prompt = 'NO UPDATE: Record forbids an update';
      $this->log( $prompt, 0, $row[ 'uid' ] );

      $this->geoupdateStatistic[ 'forbidden' ]  = $this->geoupdateStatistic[ 'forbidden' ]
                                                + 1
                                                ;
      return false;
    }

    return true;
  }

/**
 * geoupdateUpdateRowUpdate( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowUpdate( $row )
  {
      // Reset update values
    $this->geoupdateUpdateRowUpdateDataReset( );

      // Init geodata and prompt
    $this->geoupdateUpdateRowUpdateDataSet( $row );

    if( ! $this->geoupdateUpdateRowUpdateDataUpdate( $row ) )
    {
      return false;
    }

    // Aktualisiere Breiten- und Längengrad
    
    return true;
  }

/**
 * geoupdateUpdateRowUpdateDataReset( ) : 
 *
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowUpdateDataReset( )
  {
    unset( $this->geoupdateUpdateValues );
    
    $this->geoupdateUpdateValues = array( );

    return true;
  }

/**
 * geoupdateUpdateRowUpdateDataSet( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowUpdateDataSet( $row )
  {
      // Require map library
    require_once( PATH_typo3conf . 'ext/browser/lib/mapAPI/class.tx_browser_googleApi.php' );
      // Create object
    $objGoogleApi = new tx_browser_googleApi( );
    
    $address = $this->geoupdateUpdateGetAddress( $row );
    
      // Get data from API
    $result = $objGoogleApi->main( $address, $this );
    
      // Prompt to current record
    if( isset( $result[ 'status'] ) )
    {
      $prompt = 'ERROR: ' . $result[ 'status' ];
        // Prompt to the current record
      $this->geoupdateUpdateSetPrompts( $prompt );
        // prompt to syslog
      $this->log( $prompt, 1, $row[ 'uid' ] );
      
        // Statistic
      $this->geoupdateStatistic[ 'errors' ] = $this->geoupdateStatistic[ 'errors' ]
                                            + 1
                                            ;

      return false;
    }
      // Prompt to current record

    $this->geoupdateUpdateValues[ 'geodata' ][ 'lat' ] = $result[ 'geodata' ][ 'lat' ];
    $this->geoupdateUpdateValues[ 'geodata' ][ 'lon' ] = $result[ 'geodata' ][ 'lon' ];
    
    return true;
  }

/**
 * geoupdateUpdateRowUpdateDataUpdate( ) : 
 *
 * @param       array   $row
 * @return	boolean   true in case of success
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function geoupdateUpdateRowUpdateDataUpdate( $row )
  {
    $prompt = implode( PHP_EOL, ( array ) $this->geoupdateUpdateValues[ 'prompts' ] );
    if( $prompt )
    {
      $prompt = $prompt . PHP_EOL;
    }

    $date           = date('Y-m-d H:i:s');
    $browser        = ' - ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/scheduler/locallang.xml:promptBrowserPhrase'). ':';
    $updatePrompt   = '* ' . $date . $browser . PHP_EOL 
                    . '  [SCHEDULER] OK: ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/scheduler/locallang.xml:promptGeodataUpdate')
                    . $prompt . PHP_EOL
                    . $row[ $this->geoupdatelabels[ 'api' ][ 'prompt' ] ]
                    ;
    
    $updatePrompt = $GLOBALS['TYPO3_DB']->quoteStr( $updatePrompt, $this->browser_table );
    
    $updateFields = array( 
      $this->geoupdatelabels[ 'geodata' ][ 'lat' ]  . ' = "' . $this->geoupdateUpdateValues[ 'geodata' ][ 'lat' ] . '"',
      $this->geoupdatelabels[ 'geodata' ][ 'lon' ]  . ' = "' . $this->geoupdateUpdateValues[ 'geodata' ][ 'lon' ] . '"',
      $this->geoupdatelabels[ 'api' ][ 'prompt' ]   . ' = "' . $updatePrompt . '"'
    );
    
    $set  = implode( ', ', $updateFields );
    $uid  = $row[ 'uid' ];

      // Build the query
    $query = '' .
      'UPDATE ' . $this->browser_table . ' ' .
      'SET    ' . $set . ' ' .
      'WHERE  uid = ' . $uid ;


    switch( $this->browser_testMode )
    {
      case( 'enabled' ):
        $this->geoupdateStatistic[ 'updatedTest' ]  = $this->geoupdateStatistic[ 'updatedTest' ]
                                                    + 1
                                                    ;
        $prompt = 'TESTMODE query: ' . $query;
        $this->log( $prompt, 0, $uid );
        $prompt = 'TESTMODE: [tx_browser (' . $table . ':' . $uid . ')] will updated, if test mode would be disabled.';
        $this->log( $prompt, 0, $uid );
        break;
      case( 'disabled' ):
          // Execute the query
        $GLOBALS['TYPO3_DB']->sql_query( $query );
          // Evaluate the query
        $error          = $GLOBALS['TYPO3_DB']->sql_error( );
        break;
      default:
        $prompt = 'ERROR: browser_testMode is undefined: "' . $this->browser_testMode . '"';
        $this->log( $prompt, 0, $uid );
        die( $prompt );
        break;
    }
    
    if( ! empty( $error ) )
    {
      $prompt = 'ERROR: Unproper SQL query';
      $this->log( $prompt, 2 );
      $prompt = 'query: ' . $query;
      $this->log( $prompt, 1 );
      $prompt = 'prompt: ' . $error;
      $this->log( $prompt, 1 );
      
      $this->geoupdateStatistic[ 'errors' ] = $this->geoupdateStatistic[ 'errors' ]
                                            + 1
                                            ;
      return false;
    }

    $prompt = 'OK: [tx_browser (' . $table . ':' . $uid . ')] is updated.' . PHP_EOL;
    $this->log( $prompt, 0, $uid );

      // Statistic
    $this->geoupdateStatistic[ 'updated' ]  = $this->geoupdateStatistic[ 'updated' ]
                                            + 1
                                            ;
    return true;
  }

/**
 * geoupdateUpdateSetPrompts( )  : Set lables. Get lables from ext_tables.php.
 *
 * @param	string		$prompt     : 
 * @return	void
 * 
 * @version   4.5.13
 * @since     4.5.13
 */

  private function geoupdateUpdateSetPrompts( $prompt ) 
  {
    $this->geoupdateInitLabels( );

      // RETURN : no record field for prompting configured
    if( ! isset( $this->geoupdatelabels[ 'api' ][ 'prompt' ] ) )
    {
      $prompt = 'WARN: Geoupdate can\'t prompt to the record, because there is no prompt field configured.';
      $this->log( $prompt, 1 );
      return;
    }
      // RETURN : no record field for prompting configured
       
    $date     = date('Y-m-d H:i:s');
    $browser  = ' - ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptBrowserPhrase'). ':';
    $prompt   = '* ' . $date . $browser . PHP_EOL 
              . '  ' . $prompt . PHP_EOL
              . $promptFromRow
              ;

    $this->geoupdateUpdateValues[ 'prompts' ][ ] = $prompt;
  }



  /***********************************************
   *
   * Additional information for scheduler
   *
   **********************************************/

  /**
 * getAdditionalInformation( ) : This method returns the destination mail address as additional information
 *
 * @return	string		Information to display
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function getAdditionalInformation( )
  {
    $browserAdminEmail  = 'Admin'
                        . ': '
                        . $this->browser_browserAdminEmail
                        . ', table: '
                        . $this->browser_table
                        . ', test mode: '
                        . $this->browser_testMode
                        ;
    return $browserAdminEmail;
  }



  /***********************************************
   *
   * Converting
   *
   **********************************************/

/**
 * convertContent( )  :
 *
 * @param	[type]		$$xml: ...
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function convertContent( $xml )
  {
    $this->convertContentInstance( );
    $content = $this->convert->main( $xml );

    return $content;
  }

/**
 * convertContentDrsMail( )  :
 *
 * @param	[type]		$$success: ...
 * @return	void
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function convertContentDrsMail( $success )
  {
    switch( $success )
    {
      case( false ):
        $subject  = 'Failed';
        break;
      case( true ):
      default:
        $subject  = 'Success';
        break;
    }
    $body     = __CLASS__ . '::' .  __METHOD__ . ' (' . __LINE__ . ')';
    $this->drsMailToAdmin( $subject, $body );
  }

/**
 * convertContentInstance( )  :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function convertContentInstance( )
  {
    $path2lib = t3lib_extMgm::extPath( $this->extKey ) . 'lib/';

    require_once( $path2lib . 'class.tx_browser_convert.php' );

    $this->convert = t3lib_div::makeInstance( 'tx_browser_convert' );
    $this->convert->setPobj( $this );
  }



  /***********************************************
   *
   * DRS - Development Reporting System
   *
   **********************************************/

/**
 * drsDebugTrail( ): Returns class, method and line of the call of this method.
 *                    The calling method is a debug method - if it is called by another
 *                    method, please set the level in the calling method to 2.
 *
 * @param	integer		$level      : integer
 * @return	array		$arr_return : with elements class, method, line and prompt
 * @access private
 * @version 4.5.13
 * @since   4.5.13
 */
  private function drsDebugTrail( $level = 1 )
  {
    $arr_return = null;

      // Get the debug trail
    $debugTrail_str = t3lib_utility_Debug::debugTrail( );

      // Get debug trail elements
    $debugTrail_arr = explode( '//', $debugTrail_str );

      // Get class, method
    $classMethodLine = $debugTrail_arr[ count( $debugTrail_arr) - ( $level + 2 )];
    list( $classMethod ) = explode ( '#', $classMethodLine );
    list($class, $method ) = explode( '->', $classMethod );
      // Get class, method

      // Get line
    $classMethodLine = $debugTrail_arr[ count( $debugTrail_arr) - ( $level + 1 )];
    list( $dummy, $line ) = explode ( '#', $classMethodLine );
    unset( $dummy );
      // Get line

      // RETURN content
    $arr_return['class']  = trim( $class );
    $arr_return['method'] = trim( $method );
    $arr_return['line']   = trim( $line );
    $arr_return['prompt'] = $arr_return['class'] . '::' . $arr_return['method'] . ' (' . $arr_return['line'] . ')';

    return $arr_return;
      // RETURN content
  }



/**
 * drsMailToAdmin( ): Returns class, method and line of the call of this method.
 *                    The calling method is a debug method - if it is called by another
 *                    method, please set the level in the calling method to 2.
 *
 * @param	string		$subject     : ...
 * @param	string		$body        : ...
 * @param	string		$status      : error, warn, info, ok, update
 * @return	array		$arr_return  : with elements class, method, line and prompt
 * @access public
 * @version 4.5.13
 * @since   4.5.13
 */
  public function drsMailToAdmin( $subject='Information', $body=null, $status='error' )
  {
    switch( true )
    {
      case( $this->browser_reportMode == 'never' ):
          // DRS
        if( $this->drsModeInfo )
        {
          $prompt = 'Report mode is "never": DRS mail isn\'t sent.';
          t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 2 );
        }
          // DRS
        return;
        break;
    }
    
    switch( $status )
    {
      case( 'error' ):
        // Follow the workflow
        break;
      case( 'warn' ):
        switch( $this->browser_reportMode )
        {
          case( 'ever' ):
          case( 'warn' ):
            // Follow the workflow
            break;
          case( 'never' ):
          case( 'update' ):
          default:
            return;
            break;
        }
        break;
      case( 'info' ):
        switch( $this->browser_reportMode )
        {
          case( 'ever' ):
            // Follow the workflow
            break;
          case( 'warn' ):
          case( 'never' ):
          case( 'update' ):
          default:
            return;
            break;
        }
        break;
      case( 'ok' ):
        switch( $this->browser_reportMode )
        {
          case( 'ever' ):
            // Follow the workflow
            break;
          case( 'warn' ):
          case( 'never' ):
          case( 'update' ):
          default:
            return;
            break;
        }
        break;
      case( 'update' ):
        switch( $this->browser_reportMode )
        {
          case( 'ever' ):
          case( 'warn' ):
          case( 'update' ):
            // Follow the workflow
            break;
          case( 'never' ):
          default:
            return;
            break;
        }
        break;
    }
    
    
      // Get call method
    if( basename( PATH_thisScript ) == 'cli_dispatch.phpsh' )
    {
      $calledBy = 'CLI module dispatcher';
      $site     = '-';
    }
    else
    {
      $calledBy = 'TYPO3 backend';
      $site     = t3lib_div::getIndpEnv( 'TYPO3_SITE_URL' );
    }
      // Get call method

    $subject  = 'TYPO3-Browser Geoupdate: '
              . $subject
              ;

      // Get execution information
    $exec = $this->getExecution( );

    $start    = $exec->getStart( );
    $end      = $exec->getEnd( );
    $interval = $exec->getInterval( );
    $multiple = $exec->getMultiple( );
    $cronCmd  = $exec->getCronCmd( );
    $body     = $body
              . '


Browser
- - - - - - - - - - - - - - - -
Task-Id   : ' . $this->taskUid . '
Sitename  : ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . '
Site      : ' . $site . '
Called by : ' . $calledBy . '
tstamp    : ' . date( 'Y-m-d H:i:s' ) . ' [' . time( ) . ']
start     : ' . date( 'Y-m-d H:i:s', $start ) . ' [' . $start . ']
end       : ' . ( ( empty( $end ) ) ? '-' : ( date( 'Y-m-d H:i:s', $end ) . ' [' . $end . ']') ) . '
interval  : ' . $interval . '
multiple  : ' . ( $multiple ? 'yes' : 'no' ) . '
cronCmd   : ' . ( $cronCmd ? $cronCmd : 'not used' ) . '
table     : ' . $this->browser_table;

      // Prepare mailer and send the mail
    try
    {
      /** @var $mailer t3lib_mail_message */
      $mailer = t3lib_div::makeInstance( 't3lib_mail_message' );
      $mailer->setFrom( array( $this->browser_browserAdminEmail => 'Browser' ) );
      $mailer->setReplyTo( array( $this->browser_browserAdminEmail => 'Browser' ) );
      $mailer->setSubject( $subject );
      $mailer->setBody( $body );
      $mailer->setTo( $this->browser_browserAdminEmail );

      $mailsSend  = $mailer->send( );
      $success    = ( $mailsSend > 0 );
    }
    catch( Exception $e )
    {
      throw new t3lib_exception( $e->getMessage( ) );
    }

      // DRS
    if( $this->drsModeGeoupdate || $this->drsModeImportError )
    {
      switch( $success )
      {
        case( false ):
          $prompt = 'Undefined error. E-mail couldn\'t sent to "' . $this->browser_browserAdminEmail . '"';
          t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
          break;
        case( true ):
        default:
          $prompt = 'E-mail is sent to "' . $this->browser_browserAdminEmail . '"';
          t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, -1 );
          break;
      }
    }
      // DRS
  }



  /***********************************************
   *
   * Get public
   *
   **********************************************/

/**
 * getAdminmail( ):
 *
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function getAdminmail( )
  {
    return $this->browser_browserAdminEmail;
  }

/**
 * getTestMode( ):
 *
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function getTestMode( )
  {
    return $this->browser_testMode;
  }

/**
 * getTable( ):
 *
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function getTable( )
  {
    return $this->browser_table;
  }

/**
 * getReportMode( ):
 *
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function getReportMode( )
  {
    return $this->browser_reportMode;
  }



  /***********************************************
   *
   * Initials
   *
   **********************************************/

/**
 * init( )  :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function init( )
  {
    $success = true;

      // Get the extension configuration by the extension manager
    $this->extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser'] );

    $this->initDRS( );

    if( ! $this->initRequirements( ) )
    {
      $success = false;
      return $success;
    }

    $this->initTimetracking( );

    return $success;
  }

  /**
 * initDRS( )  : Init the DRS - Development Reporting System
 *
 * @return	void
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initDRS( )
  {

    if( $this->extConf['drs_mode'] != 'Scheduler: Geoupdate' )
    {
      return;
    }

    $prompt = 'DRS - Development Reporting System: ' . $this->extConf['drs_mode'];
    t3lib_div::devlog( '[tx_browser_Geoupdate] ' . $prompt, $this->extKey, 0 );

    $this->drsModeAll         = true;
    $this->drsModeError       = true;
    $this->drsModeWarn        = true;
    $this->drsModeInfo        = true;

    $this->drsModeConvert     = true;
    $this->drsModeGeoupdate   = true;
    $this->drsModePerformance = true;
    $this->drsModeSql         = true;
    $this->drsModeUpdate      = true;
    $this->drsModeXml         = true;
  }

/**
 * initRequirements( ) :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initRequirements( )
  {
    if( ! $this->initRequirementsAdminmail( ) )
    {
      return false;
    }

    if( ! $this->initRequirementsOs( ) )
    {
      return false;
    }

    if( ! $this->initRequirementsAllowUrlFopen( ) )
    {
      return false;
    }

    return true;

  }

/**
 * initRequirementsAdminmail( ) :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initRequirementsAdminmail( )
  {
      // RETURN : email address is given
    if ( ! empty( $this->browser_browserAdminEmail ) )
    {
      return true;
    }
      // RETURN : email address is given

      // DRS
    if( $this->drsModeError )
    {
      $prompt = 'email address is missing for the Browser admin.';
      t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
    }
      // DRS

    return false;
  }

/**
 * initRequirementsAllowUrlFopen( ) :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initRequirementsAllowUrlFopen( )
  {
    $allow_url_fopen = ini_get( 'allow_url_fopen');

      // RETURN : true. allow_url_fopen is enabled
    if( $allow_url_fopen )
    {
      return true;
    }
      // RETURN : true. allow_url_fopen is enabled

      // DRS
    if( $this->drsModeError )
    {
      $prompt = 'PHP ini property allow_url_fopen is disabled.';
      t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
    }
      // DRS

      // Send e-mail to admin
    $subject  = 'Failed';
    $body     = 'Sorry, but PHP ini property allow_url_fopen is disabled.' . PHP_EOL
              . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')'
              ;
    $this->drsMailToAdmin( $subject, $body );
      // Send e-mail to admin

    return false;
  }

/**
 * initRequirementsOs( ) :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initRequirementsOs( )
  {
      
      // #i0005, 130413, dwildt, 2+
    $os = true;
    return $os;
      // #i0005, 130413, dwildt, 2+
    
//    $os = false;
//
//      // SWITCH : server OS
//    switch( strtolower( PHP_OS ) )
//    {
//      case( 'linux' ):
//          // Linux is proper: Follow the workflow
//        $os = true;
//        break;
//      default:
//          // OS isn't supported
//        $os = false;
//    }
//      // SWITCH : server OS
//
//      // RETURN : os is supported
//    if( $os )
//    {
//      return true;
//    }
//      // RETURN : os is supported
//
//      // DRS
//    if( $this->drsModeError )
//    {
//      $prompt = 'Sorry, but the operating system "' . PHP_OS . '" isn\'t supported by TYPO3 Browser.';
//      t3lib_div::devLog( '[tx_browser_Geoupdate]: ' . $prompt, $this->extKey, 3 );
//    }
//      // DRS
//
//      // e-mail to admin
//    $subject  = 'Failed';
//    $body     = 'Sorry, but ' . PHP_OS . ' isn\'t supported.' . PHP_EOL
//              . PHP_EOL
//              . __METHOD__ . ' (' . __LINE__ . ')'
//              ;
//    $this->drsMailToAdmin( $subject, $body );
//      // e-mail to admin
//
//    return $os;
  }

  /**
 * initTimetracking( ) :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function initTimetracking( )
  {
    $this->timeTracking_init( );
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel, 'START' );
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
 * @param	integer		$uid    : uid of the current record
 * @param	integer		$pid    : pid of the current record
 * @param	string		$action : 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
 * @return	void
 * 
 * @access    public
 * 
 * @version   4.5.7
 * @since     4.5.7
 */

  public function log( $prompt, $error=0, $uid=0, $action=2 ) 
  {
    $table  = $this->browser_table;
    if( $uid )
    {
      $table = $table . ':' . $uid;
    }

    $prompt = '[tx_browser (' . $table . ')] ' . $prompt . PHP_EOL;

    $type       = 4;        // denotes which module that has submitted the entry. This is the current list:  1=tce_db; 2=tce_file; 3=system (eg. sys_history save); 4=modules; 254=Personal settings changed; 255=login / out action: 1=login, 2=logout, 3=failed login (+ errorcode 3), 4=failure_warning_email sent
    //$action     = 0;        // Action number: 0=No category, 1=new record, 2=update record, 3= delete record, 4= move record, 5= Check/evaluate
    //$error      = 0;        // flag. 0 = message, 1 = error (user problem), 2 = System Error (which should not happen), 3 = security notice (admin)
    $details_nr = -1;       // The message number. Specific for each $type and $action. in the future this will make it possible to translate errormessages to other languages
    $details    = $prompt;  // Default text that follows the message
    $data       = array( ); // Data that follows the log. Might be used to carry special information. If an array the first 5 entries (0-4) will be sprintf'ed the details-text...
    //$table      = null;     // Special field used by tce_main.php. These ($tablename, $recuid, $recpid) holds the reference to the record which the log-entry is about. (Was used in attic status.php to update the interface.)
    $recuid     = $uid;     // Secial field used by tce_main.php. These ($tablename, $recuid, $recpid) holds the reference to the record which the log-entry is about. (Was used in attic status.php to update the interface.)
    $recpid     = 0;        // Normally 0 (zero). If set, it indicates that this log-entry is used to notify the backend of a record which is moved to another location
    $event_pid  = -1;
    $NEWid      = null;

    $GLOBALS[ 'BE_USER' ]->writelog( $type, $action, $error, $details_nr, $details, $data, $table, $recuid, $recpid, $event_pid, $NEWid );
    
  }



  /***********************************************
   *
   * Set public
   *
   **********************************************/

/**
 * setAdminmail( ):
 *
 * @param	[type]		$$value: ...
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function setAdminmail( $value )
  {
    $this->browser_browserAdminEmail = $value;
  }

/**
 * setTestMode( ):
 *
 * @param	[type]		$$value: ...
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function setTestMode( $value )
  {
    $this->browser_testMode = $value;
  }

/**
 * setTable( ):
 *
 * @param	[type]		$$value: ...
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function setTable( $value )
  {
    $this->browser_table = $value;
  }

/**
 * setReportMode( ):
 *
 * @param	[type]		$$value: ...
 * @return	void
 * @access public
 * @version       4.5.13
 * @since         4.5.13
 */
  public function setReportMode( $value )
  {
    $this->browser_reportMode = $value;
  }




  /***********************************************
   *
   * Time tracking
   *
   **********************************************/

  /**
 * timeTracking_init( ):  Init the timetracking object. Set the global $startTime.
 *
 * @return	void
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function timeTracking_init( )
  {
      // Init the timetracking object
    require_once( PATH_t3lib . 'class.t3lib_timetrack.php' );
    $this->TT = new t3lib_timeTrack;
    $this->TT->start( );
      // Init the timetracking object

      // Set the global $startTime.
    $this->tt_startTime = $this->TT->getDifferenceToStarttime();
  }

  /**
 * timeTracking_log( ): Prompts a message in devLog with current run time in miliseconds
 *
 * @param	integer		$debugTrailLevel  : level for the debug trail
 * @param	string		$line             : current line in calling method
 * @param	string		$prompt           : The prompt for devlog.
 * @return	void
 * @version       4.5.13
 * @since         4.5.13
 */
  private function timeTracking_log( $debugTrailLevel, $prompt )
  {
      // RETURN: DRS shouldn't report performance prompts
    if( ! $this->drsModePerformance )
    {
      return;
    }
      // RETURN: DRS shouldn't report performance prompts

      // Get the current time
    $endTime = $this->TT->getDifferenceToStarttime( );

    $debugTrail = $this->drsDebugTrail( $debugTrailLevel );

    // Prompt the current time
    $mSec   = sprintf("%05d", ( $endTime - $this->tt_startTime ) );
    $prompt = $mSec . ' ms ### ' .
              $debugTrail['prompt'] . ': ' . $prompt;
    t3lib_div::devLog( $prompt, $this->extKey, 0 );

    $timeOfPrevProcess = $endTime - $this->tt_prevEndTime;

    switch( true )
    {
      case( $timeOfPrevProcess >= 10000 ):
        $this->tt_prevPrompt = 3;
        $prompt = 'Previous process needs more than 10 sec (' . $timeOfPrevProcess / 1000 . ' sec)';
        t3lib_div::devLog('[WARN/PERFORMANCE] ' . $prompt, $this->extKey, 3 );
        break;
      case( $timeOfPrevProcess >= 250 ):
        $this->tt_prevPrompt = 2;
        $prompt = 'Previous process needs more than 0.25 sec (' . $timeOfPrevProcess / 1000 . ' sec)';
        t3lib_div::devLog('[WARN/PERFORMANCE] ' . $prompt, $this->extKey, 2 );
        break;
      default:
        $this->tt_prevPrompt = 0;
        // Do nothing
    }
    $this->tt_prevEndTime = $endTime;
  }

  /**
 * timeTracking_prompt( ):  Method checks, wether previous prompt was a
 *                          warning or an error. If yes the given prompt will loged by devLog
 *
 * @param	integer		$debugTrailLevel  : level for the debug trail
 * @param	string		$prompt: The prompt for devlog.
 * @return	void
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function timeTracking_prompt( $debugTrailLevel, $prompt )
  {
    $debugTrail = $this->drsDebugTrail( $debugTrailLevel );

    switch( true )
    {
      case( $this->tt_prevPrompt == 3 ):
        $prompt_02 = 'ERROR';
        break;
      case( $this->tt_prevPrompt == 2 ):
        $prompt_02 = 'WARN';
        break;
      default:
          // Do nothing
        return;
    }

    $prompt = 'Details about previous process: ' . $prompt . ' (' . $debugTrail['prompt'] . ')';
    t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->extKey, $this->tt_prevPrompt );
  }



  /***********************************************
   *
   * Update
   *
   **********************************************/

/**
 * updateDatabase( )  :
 *
 * @param	array		$content  :
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function updateDatabase( $content )
  {
    $success = false;

    $this->updateDatabaseInstance( );
    $success = $this->update->main( $content );

    return $success;
  }

/**
 * updateDatabaseDrsMail( )  :
 *
 * @param	[type]		$$success: ...
 * @return	void
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function updateDatabaseDrsMail( $success )
  {
    switch( $success )
    {
      case( false ):
        $subject  = 'Failed';
        break;
      case( true ):
      default:
        $subject  = 'Success';
        break;
    }
    $body     = __CLASS__ . '::' .  __METHOD__ . ' (' . __LINE__ . ')';
    $this->drsMailToAdmin( $subject, $body );
  }

/**
 * updateDatabaseInstance( )  :
 *
 * @return	boolean
 * @access private
 * @version       4.5.13
 * @since         4.5.13
 */
  private function updateDatabaseInstance( )
  {
    $path2lib = t3lib_extMgm::extPath( $this->extKey ) . 'lib/';

    require_once( $path2lib . 'class.tx_browser_update.php' );

    $this->update = t3lib_div::makeInstance( 'tx_browser_update' );
    $this->update->setPobj( $this );
  }

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/browser/lib/scheduler/class.tx_browser_geoupdate.php'])) {
  include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/browser/lib/scheduler/class.tx_browser_geoupdate.php']);
}

?>