<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011-2016 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_statistics bundles methods for statistics requirement
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 3.9.3
* @since 3.9.3
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   64: class tx_browser_pi1_statistics
 *  124:     function __construct($pObj)
 *
 *              SECTION: Initial
 *  158:     private function statisticsInitVars( )
 *  236:     public function statisticsIsEnabled( )
 *
 *              SECTION: Counter
 *  319:     public function countViewSingleRecord( )
 *  398:     private function countHit( )
 *  426:     private function countVisit( )
 *
 *              SECTION: SQL
 *  490:     public function sql_update_statistics( $table, $field, $uid, $operator )
 *
 *              SECTION: Helper
 *  674:     private function helperFieldInTable( $table, $field )
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_statistics
{
    // #31230, 31229: Statistics module

    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

    // [Array] The current TypoScript configuration array
  var $conf       = false;
    // [Integer] The current mode (from modeselector)
  var $mode       = false;
    // [String] 'list' or 'single': The current view
  var $view       = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_view  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
  var $conf_path  = false;
    // Variables set by the pObj (by class.tx_browser_pi1.php)



    //////////////////////////////////////////////////////
    //
    // Variables set by this class

    // [Array] Array with checked tables: $arr_checkedTables[table][field]
  var $arr_checkedTables            = null;
    // [Boolean] True, if statistics module is enabled. Will set while runtime
  var $bool_statistics_enabled      = null;
    // [String/csv] Comma seperated list of IPs, which won't counted
  var $dontAccountIPsOfCsvList      = null;
    // [Integer] Period between a current and a new download and visit in seconds
  var $timeout                      = null;
    // [String] Name of the field for counting downloads (with respect for timeout)
  var $fieldDownloads               = null;
    // [String] Label of the field for counting downloads (with respect for timeout)
  var $fieldDownloadsByVisits       = null;
    // [String] Label of the field for counting hits (without any respect for timeout)
  var $fieldHits                    = null;
    // [String] Label of the field for counting visits (hits with respect for timeout)
  var $fieldVisits                  = null;
    // [Array] Array with the type of the fields. Labels of the element are the labels of the fields
  var $arr_fieldType                  = null;
    // Variables set by this class









  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Initial
  *
  **********************************************/









  /**
 * statisticsInitVars( ): The method inits the global class variables
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statisticsInitVars( )
  {
      // Get the adjustment configuration
    $conf_adjustment = $this->pObj->conf['flexform.']['sDEF.']['statistics.']['adjustment.'];

      // List of IPs, which should ignored
    $coa_name                     = $conf_adjustment['dontAccountIPsOfCsvList'];
    $coa_conf                     = $conf_adjustment['dontAccountIPsOfCsvList.'];
    $this->dontAccountIPsOfCsvLt  = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Report in the frontend in case of an unexpected sql result
    $coa_name                     = $conf_adjustment['debugging'];
    $coa_conf                     = $conf_adjustment['debugging.'];
    $this->debugging              = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Timeout (for downloads and visits)
    $coa_name                     = $conf_adjustment['timeout'];
    $coa_conf                     = $conf_adjustment['timeout.'];
    $this->timeout                = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Field for counting downloads
    $coa_name                     = $conf_adjustment['fields.']['downloads.']['label'];
    $coa_conf                     = $conf_adjustment['fields.']['downloads.']['label.'];
    $this->fieldDownloads         = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Field for counting downloads by visits
    $coa_name                     = $conf_adjustment['fields.']['downloadsByVisits.']['label'];
    $coa_conf                     = $conf_adjustment['fields.']['downloadsByVisits.']['label.'];
    $this->fieldDownloadsByVisits = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Field for counting hits
    $coa_name                     = $conf_adjustment['fields.']['hits.']['label'];
    $coa_conf                     = $conf_adjustment['fields.']['hits.']['label.'];
    $this->fieldHits              = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Field for counting visits
    $coa_name                     = $conf_adjustment['fields.']['visits.']['label'];
    $coa_conf                     = $conf_adjustment['fields.']['visits.']['label.'];
    $this->fieldVisits            = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Type for field for counting downloads
    $coa_name                     = $conf_adjustment['fields.']['downloads.']['type'];
    $coa_conf                     = $conf_adjustment['fields.']['downloads.']['type.'];
    $this->arr_fieldType[$this->fieldDownloads]     = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Type for field for counting downloads by visits
    $coa_name                     = $conf_adjustment['fields.']['downloadsByVisits.']['type'];
    $coa_conf                     = $conf_adjustment['fields.']['downloadsByVisits.']['type.'];
    $this->arr_fieldType[$this->fieldDownloadsByVisits] = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Type for field for counting hits
    $coa_name                     = $conf_adjustment['fields.']['hits.']['type'];
    $coa_conf                     = $conf_adjustment['fields.']['hits.']['type.'];
    $this->arr_fieldType[$this->fieldHits]          = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);

      // Type for field for counting visits
    $coa_name                     = $conf_adjustment['fields.']['visits.']['type'];
    $coa_conf                     = $conf_adjustment['fields.']['visits.']['type.'];
    $this->arr_fieldType[$this->fieldVisits]        = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
  }









  /**
 * statisticsIsEnabled( ):  The method sets the global $bool_statistics_enabled.
 *                          The boolean is controlled by the flexform / TypoScript.
 *                          The User can enable and disable the statistics module.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function statisticsIsEnabled( )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Boolean is set before

    if( ! ( $this->bool_statistics_enabled === null ) )
    {
      return;
    }
      // RETURN: Boolean is set before



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Enable statistics module

    $coa_name = $this->pObj->conf['flexform.']['sDEF.']['statistics.']['enabled'];
    $coa_conf = $this->pObj->conf['flexform.']['sDEF.']['statistics.']['enabled.'];
    $this->bool_statistics_enabled = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
      // Enable statistics module



      ///////////////////////////////////////////////////////////////////////////////
      //
      // User disabled the statistics module

    if( ! $this->bool_statistics_enabled )
    {
      if ( $this->pObj->b_drs_statistics )
      {
        $value = $this->bool_statistics_enabled;
        t3lib_div::devlog( '[INFO/STATISTICS] flexform.sDEF.statistics.enabled is \'' . $value . '\' '.
          'Statistics data won\'t collect.', $this->pObj->extKey, 0 );
      }
      return;
    }
      // User disabled the statistics module



      //////////////////////////////////////////////////////////////////////////
      //
      // init the variables of the statistics module

    $this->statisticsInitVars( );
      // init the variables of the statistics module

    return;
  }









  /***********************************************
  *
  * Counter
  *
  **********************************************/









  /**
 * countViewSingleRecord( ):  The method counts the hits and visits for a record in the single view
 *                            There isn't any counting, if the page is cached.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function countViewSingleRecord( )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the statistics module and init it

    $this->statisticsIsEnabled( );
      // Set status of the statistics module and init it



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: statistics module is disabled

    if( ! $this->bool_statistics_enabled )
    {
      if ($this->pObj->b_drs_statistics)
      {
        t3lib_div::devlog('[INFO/STATISTICS] single view won\'t counted for statistics.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/STATISTICS] Enable flexform.sDEF.statistics.enabled.', $this->pObj->extKey, 1);
      }
      return;
    }
      // RETURN: statistics module is disabled



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Don't count for a defined IP

    $pos = strpos( $this->dontAccountIPsOfCsvList, t3lib_div :: getIndpEnv('REMOTE_ADDR') );
    if ( ! ( $pos === false ) )
    {
      if ($this->pObj->b_drs_statistics)
      {
        t3lib_div::devlog('[INFO/STATISTICS] Current IP is an element of the "don\'t account IP list".', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/STATISTICS] Current IP is ' . t3lib_div :: getIndpEnv('REMOTE_ADDR') , $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/STATISTICS] List of IPs is ' . $this->dontAccountIPsOfCsvList , $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/STATISTICS] No counting for statistics!', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN: Don't count for a defined IP



      //////////////////////////////////////////////////////////////////////////
      //
      // Counting

      // Count the hit
    $this->countHit( );
      // Count the visit
    $this->countVisit( );
      // Counting



    return;
  }









  /**
 * countHit( ): The method counts the hits for a record in the singleView.
 *              There isn't any counting, if the page is cached.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function countHit( )
  {
    $table = $this->pObj->localTable;
    $field = $this->fieldHits;
    $uid   = $this->pObj->piVars['showUid'];

      // Count the hit
    $this->sql_update_statistics( $table, $field, $uid, '+' );

  }









  /**
 * countVisit( ): The method counts the visits for a record in the singleView.
 *                Visits are managed by the method $this->pObj->objSession->statisticsNewVisit.
 *                There isn't any counting, if the page is cached.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function countVisit( )
  {
    $table = $this->pObj->localTable;
    $field = $this->fieldVisits;
    $uid   = $this->pObj->piVars['showUid'];

      // RETURN: no new visit
    $bool_newVisit = $this->pObj->objSession->statisticsNewVisit( $table, $field, $uid );
    if( ! $bool_newVisit )
    {
        // DRS - Development Reporting System
      if( $this->pObj->b_drs_statistics )
      {
        $prompt = 'No new visit, no counting.';
        t3lib_div::devlog('[INFO/STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
      return;
    }
      // RETURN: no new visit

      // Count the hit
    $this->sql_update_statistics( $table, $field, $uid, '+' );
    return;
  }








  /***********************************************
  *
  * SQL
  *
  **********************************************/









  /**
 * sql_update_statistics( ):  The method increases or decreases the value of the given field
 *                            in the SQL table.
 *                            The method checks, if the field is existing.
 *                            If there is an SQL error or if there isn't any affected row,
 *                            the method logs in the DRS.
 *                            If the user has enabled the SQL debug by the flexform / TypoScript,
 *                            the method echos it in the frontend.
 *
 * @param	string		$table:     name of the current table
 * @param	string		$field:     name of the current field
 * @param	integer		$uid:       uid of the current record
 * @param	integer		$operator:  operator like + or -
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function sql_update_statistics( $table, $field, $uid, $operator )
  {
      // The current table hasn't any field for counting hits
    if( ! $this->helperFieldInTable( $table, $field ) )
    {
      return;
    }
      // The current table hasn't any field for counting hits

      // Get the uid of the localised record, if there is one
    $uid = $this->pObj->objLocalise->get_localisedUid( $table, $uid );

      // Build the query
    $query = '' .
      'UPDATE `' . $table . '` ' .
      'SET    `' . $field . '` = `' . $field . '` ' . $operator . ' 1 ' .
      'WHERE  `uid` = ' . $uid ;

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_sql || $this->pObj->b_drs_statistics )
    {
      t3lib_div::devlog( '[INFO/SQL+STATISTICS] ' . $query, $this->pObj->extKey, 0 );
    }
      // DRS - Development Reporting System

      // Execute the query
    $GLOBALS['TYPO3_DB']->sql_query( $query );

      // Evaluate the query
    $affected_rows  = $GLOBALS['TYPO3_DB']->sql_affected_rows( );
    $error          = $GLOBALS['TYPO3_DB']->sql_error( );



      ///////////////////////////////////////////////////////////////////////////////
      //
      // ERROR: debug report in the frontend

    if( $error )
    {
      $this->pObj->objSqlFun_3x->query = $query;
      $this->pObj->objSqlFun_3x->error = $error;
      $arr_result = $this->pObj->objSqlFun_3x->prompt_error( );
      $prompt     = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      return $prompt;
    }
      // ERROR: debug report in the frontend

//    if( ! empty( $error ) )
//    {
//      if( $this->debugging )
//      {
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
//        echo $str_warn.$str_header.$str_prompt;
//      }
//    }
//      // ERROR: debug report in the frontend
//
//
//
//      ///////////////////////////////////////////////////////////////////////////////
//      //
//      // DRS - Development Reporting System
//
//    if( ! empty( $error ) )
//    {
//      if( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
//      }
//    }
//      // DRS - Development Reporting System
//
//
//
//      ///////////////////////////////////////////////////////////////////////////////
//      //
//      // RETURN: error
//
//    if( ! empty( $error ) )
//    {
//      return;
//    }
//      // RETURN: error



      ///////////////////////////////////////////////////////////////////////////////
      //
      // WARNING: any row isn't effected

    if( $affected_rows < 1 )
    {
      if( $this->debugging )
      {
        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('warn_sql_h1').'</h1>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">Any row isn\'t affected!</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
        echo $str_header.$str_prompt;
      }
    }
      // WARNING: any row isn't effected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Any affected row

    if( $affected_rows < 1 )
    {
        // DRS - Development Reporting System
      if( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog('[WARN/SQL] ' . $query,  $this->pObj->extKey, 2);
        t3lib_div::devlog('[WARN/SQL] Any row isn\'t affected!',  $this->pObj->extKey, 2);
      }
        // DRS - Development Reporting System
      return;
    }
      // RETURN: Any affected row



      ///////////////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_statistics )
    {
      switch( $operator )
      {
        case( '+' ):
          $str_inOrDecreased = 'increased';
          break;
        case( '-' ):
          $str_inOrDecreased = 'decreased';
          break;
      }
      $prompt = 'Counter is ' . $str_inOrDecreased . ' (' . $operator . ') for ' . $table . ', record[' . $uid . ']: field ' . $field . '.';
      t3lib_div::devlog('[INFO/STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System

    return;
  }

















  /***********************************************
  *
  * Helper
  *
  **********************************************/









  /**
 * helperFieldInTable( ): The method checks,
 *                        * if the needed field for statistics data is an element of the local table
 *                        * if the type in the TCA is the type in the TypoScript
 *                        The result will be stored in the class var
 *                        $this->arr_checkedTables[$table][$field]
 *
 * @param	string		$table: current table
 * @param	string		$field: current field
 * @return	boolean		$this->arr_checkedTables[$table][$field]
 * @version 3.9.3
 * @since 3.9.3
 */
  private function helperFieldInTable( $table, $field )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: table.field is checked before

    if( isset( $this->arr_checkedTables[$table][$field] ) )
    {
      return $this->arr_checkedTables[$table][$field];
    }
      // RETURN: table.field is checked before



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set the global $this->arr_checkedTables[$table][$field]

      // Load the TCA for the current table
    $this->pObj->objZz->loadTCA($table);
      // Check, if the field is an element of the current table
    switch( isset($GLOBALS['TCA'][$table]['columns'][$field] ) )
    {
      case( true ):
          // Compare to type of the field in the TypoScript with the type in the TCA
        $str_TsType   = $this->arr_fieldType[$field];
        $str_TcaType  = $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'];
          // Type isn't the same
        if( $str_TsType != $str_TcaType )
        {
          $this->arr_checkedTables[$table][$field] = false;
          $prompt_01 = 'TCA type of \'' . $field . '\' is \'' . $str_TcaType . '\' in the TCA, but it is \'' . $str_TsType . '\' in the TypoScript.';
          $prompt_02 = 'Please take care of a proper TCA and TypoScript. See flexform.sDEF.statistics.adjustment.fields.' . $field . '.type.';
          if( $this->pObj->b_drs_statistics )
          {
            t3lib_div::devlog('[WARN/STATISTICS] ' . $prompt_01, $this->pObj->extKey, 2);
            t3lib_div::devlog('[HELP/STATISTICS] ' . $prompt_02, $this->pObj->extKey, 1);
          }
          if( $this->debugging )
          {
            $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $prompt_01 . '</p>';
            $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $prompt_02 . '</p>';
            echo $str_prompt;
          }
          // Type isn't the same
        }
          // Type is the same
        if( $str_TsType == $str_TcaType )
        {
          $this->arr_checkedTables[$table][$field] = true;
        }
          // Type is the same
        break;
      default:
          // Hit field isn't any element of the current table
        $this->arr_checkedTables[$table][$field] = false;
        $prompt_01 = $field . ' isn\'t any field of the table ' . $table . ' in the TCA. Hit can\'t counted!';
        $prompt_02 = 'Please extend your TCA table ' . $table . ' with the field ' . $field . '.';
        if( $this->pObj->b_drs_statistics )
        {
          t3lib_div::devlog('[WARN/STATISTICS] ' . $prompt_01, $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/STATISTICS] ' . $prompt_02, $this->pObj->extKey, 1);
        }
        if( $this->debugging )
        {
          $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $prompt_01 . '</p>';
          $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $prompt_02 . '</p>';
          echo $str_prompt;
        }
    }
      // Check, if the field is an element of the current table
      // Set the global $this->arr_checkedTables[$table][$field]



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN

    return $this->arr_checkedTables[$table][$field];
      // RETURN
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_statistics.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_statistics.php']);
}
?>