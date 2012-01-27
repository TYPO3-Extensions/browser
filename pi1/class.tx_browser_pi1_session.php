<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_session bundles methods for the session management
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.3
* @since 3.9.3
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_session
 *  101:     function __construct($pObj)
 *
 *              SECTION: Initial
 *  137:     public function sessionIsEnabled( )
 *  196:     public function getNameOfDataSpace( )
 *
 *              SECTION: Cache
 *  239:     public function cacheOfListView( )
 *
 *              SECTION: Statistics
 *  404:     public function statisticsNewVisit( $table, $field, $uid )
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_session
{
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

    // [Boolean] True, if session management is enabled. Will set while runtime
  var $bool_session_enabled = null;
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
 * sessionIsEnabled( ): Sets the global $bool_session_enabled.
 *                      The boolean is controlled by the flexform / TypoScript.
 *                      The User can enable and disable session management.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function sessionIsEnabled( )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Boolean is set before

    if( ! ( $this->bool_session_enabled === null ) )
    {
      return;
    }
      // RETURN: Boolean is set before



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Enable session management

    $this->bool_session_enabled = true;
      // Enable session management



      ///////////////////////////////////////////////////////////////////////////////
      //
      // User disabled the session management

    if( ! $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'] )
    {
      $this->bool_session_enabled = false;
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        $value = $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'];
        t3lib_div::devlog( '[INFO/SESSION+TEMPLATING] advanced.session_manager.session.enabled is \'' . $value . '\' '.
          'Record browser won\'t get its data from session (less performance).', $this->pObj->extKey, 0 );
      }
    }
      // User disabled the session management

    return;
  }









  /**
 * getNameOfDataSpace( ): Get the name of the session data space.
 *                        The name is user, if a frontend user is logged in.
 *                        The name is ses, if any frontend user isn't logged in.
 *
 * @return	string		user || ses
 * @version 3.9.3
 * @since 3.9.3
 */
  public function getNameOfDataSpace( )
  {
    switch( $GLOBALS['TSFE']->loginUser )
    {
      case( true ):
        return 'user';
      default:
        return 'ses';
    }
  }








  /***********************************************
  *
  * Cache
  *
  **********************************************/









  /**
 * cacheOfListView(): The method caches list view data in the session cache.
 *                    The result will stored in the global $this->pObj->uids_of_all_rows too.
 *                    If session managment is disabled, the method will call the list view method.
 *                    The result will stored in the global $this->pObj->uids_of_all_rows too but
 *                    without any caching.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function cacheOfListView( )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the session management

    $this->sessionIsEnabled( );
      // Set status of the session management


      // Uid of the current plugin
    $tt_content_uid = $this->pObj->cObj->data['uid'];



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: no session, get data from the list view. Call it!

    if( ! $this->bool_session_enabled )
    {
      if ($this->pObj->b_drs_perform || $this->pObj->b_drs_session)
      {
        t3lib_div::devlog('[WARN/PERFORMANCE+SESSION] list view is called. Uids of all rows are needed. '.
          'Be aware of less performance!', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/PERFORMANCE+SESSION] Enable session for better performance!', $this->pObj->extKey, 1);
      }
        // listView will set $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']
      $this->pObj->objNav->recordbrowser_callListView();
// dwildt, 111107
      return;
    }
      // RETURN: no session, get data from the list view. Call it!



      //////////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($this->pObj->b_drs_session || $this->pObj->b_drs_templating)
    {
      t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Session: uid of all rows should delivered by the session data '.
        '(best performance).', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the name of the session data space

    $str_data_space = $this->getNameOfDataSpace( );
      // Get the name of the session data space



      //////////////////////////////////////////////////////////////////////////
      //
      // Get tx_browser-pi1 session data

    $arr_session_browser = $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId);
      // Get tx_browser-pi1 session data



      //////////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( empty( $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[INFO/WARN] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][cache][mode-' . $this->mode . '][uids_of_all_rows] is empty!',
          $this->pObj->extKey, 2);
      }
    }
    if( ! empty( $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
      if ($this->pObj->b_drs_session || $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][cache][mode-' . $this->mode . '][uids_of_all_rows] is set with ' .
          '#' . count($arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']) . ' uids.',  $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////////
      //
      // Session data is not set: set tx_browser_pi1['uids_of_all_rows'] !

    if( ! isset( $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
        // listView will set $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']
      $this->pObj->objNavi->recordbrowser_callListView( );
        // Set the session array uids_of_all_rows
      $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'];
        // Write session data tx_browser_pi1
      $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_session_browser);
        // DRS - Development Reporting System
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][cache][mode-' . $this->mode . '][uids_of_all_rows] is set with ' .
          '#' . count($this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']) . ' uids.',  $this->pObj->extKey, 0);
      }
// dwildt, 111107
//          // Get uids of all records
//        $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'];
    }
      // Session data is not set: set tx_browser_pi1['uids_of_all_rows'] !



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the global $this->pObj->uids_of_all_rows

    $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $arr_session_browser[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'];
    return;
      // Set the global $this->pObj->uids_of_all_rows
  }








  /***********************************************
  *
  * Statistics
  *
  **********************************************/









  /**
 * statisticsNewVisit():  The method checks, if the previous visit is older than current time minus
 *                        the user defined timeout. It manages the session data for the visit.
 *                        Workflow:
 *                        * Is session management disabled? Return false
 *                        * Isn't previous visit older than current time minus timeout? Return false
 *                        #31230, 31229: Statistics module
 *
 * @param	string		name of the current table
 * @param	string		name of the current field
 * @param	integer		id of the current uid
 * @return	boolean		$bool_newVisit: true in case of a new visit, otherwise false
 * @version 3.9.3
 * @since 3.9.3
 */
  public function statisticsNewVisit( $table, $field, $uid )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the session management

    $this->sessionIsEnabled( );
      // Boolean for status of visit
    $bool_newVisit = false;
      // Set status of the session management



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: no session, no counting of visits

    if( ! $this->bool_session_enabled )
    {
        // DRS - Development Reporting System
      if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
      {
        $prompt = 'Session management is disabled. Visits can\'t count.';
        t3lib_div::devlog('[WARN/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'Enable session for better performance!';
        t3lib_div::devlog('[HELP/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System

      return $bool_newVisit ;
    }
      // RETURN: no session, no counting of visits



      //////////////////////////////////////////////////////////////////////////
      //
      // Init variables

      // Uid of the current Browser plugin
    $tt_content_uid       = $this->pObj->cObj->data['uid'];
      // Get the name of the session data space
    $str_data_space       = $this->getNameOfDataSpace( );
      // Period between a current and a new download and visit in seconds
    $timeout              = $this->pObj->objStat->timeout;
      // Timestamp of now
    $time                 = time( );
      // Get tx_browser-pi1 session data
    $arr_session_browser  = $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId);
    $arr_session_visit    = $arr_session_browser[$tt_content_uid]['statistics']['visit'];
      // Get tx_browser-pi1 session data
    $int_syslanguage      = $GLOBALS['TSFE']->sys_language_content;
    if( $int_syslanguage == '')
    {
      $int_syslanguage = 0;
    }
      // Init variables



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: first visit

    if( empty( $arr_session_visit[$int_syslanguage][$table][$uid][$field] ) )
    {
        // Set the new visit
      $arr_session_browser[$tt_content_uid]['statistics']['visit'][$int_syslanguage][$table][$uid][$field] = $time;
      $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_session_browser);
        // Storing session data now (is proper in context with an PHP exit!)
      $GLOBALS["TSFE"]->storeSessionData();
        // Set the new visit

        // DRS - Development Reporting System
      if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
      {
        $prompt = 'First visit.';
        t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = $table . '.record[' . $uid . '][' . $field . '] is set to: ' . $time;
        t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System

        // RETURN
      $bool_newVisit = true;
      return $bool_newVisit;
    }
      // RETURN: first visit



      //////////////////////////////////////////////////////////////////////////
      //
      // Repeated visit

    $timeLastVisit    = $arr_session_visit[$int_syslanguage][$table][$uid][$field];
    $timeMinusTimeout = $time - $timeout;
    switch( true )
    {
      case( $timeLastVisit <= $timeMinusTimeout ):
          // new visit
          // Set the new visit
        $arr_session_browser[$tt_content_uid]['statistics']['visit'][$int_syslanguage][$table][$uid][$field] = $time;
        $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_session_browser);
          // Storing session data now (is proper in context with an PHP exit!)
        $GLOBALS["TSFE"]->storeSessionData();
          // Set the new visit

          // DRS - Development Reporting System
        if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
        {
          $prompt = 'New visit: previous visit (' . $timeLastVisit . ' ) is older than ' . $timeMinusTimeout . '.';
          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = $table . '.record[' . $uid . '][' . $field . '] is set to: ' . $time;
          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System

        $bool_newVisit = true;
        break;
          // new visit
      default:
          // DRS - Development Reporting System
        if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
        {
          $prompt = 'No new visit: previous visit (' . $timeLastVisit . ' ) isn\'t older than ' . $timeMinusTimeout . '.';
          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = $table . '.record[' . $uid . '][' . $field . '] is left to: ' . $timeLastVisit;
          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System

          // no new visit
        $bool_newVisit = false;
          // no new visit
    }
      // Repeated visit



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN

    return $bool_newVisit;
      // RETURN
  }









//  /**
// * statisticsNewDownload():  The method checks, if the previous visit is older than current time minus
// *                        the user defined timeout. It manages the session data for the visit.
// *                        Workflow:
// *                        * Is session management disabled? Return false
// *                        * Isn't previous visit older than current time minus timeout? Return false
// *                        #31230, 31229: Statistics module
// *
// * @return	boolean		$bool_newDownload: true in case of a new visit, otherwise false
// * @version 3.9.3
// * @since 3.9.3
// */
//  public function statisticsNewDownload( $table, $field, $uid )
//  {
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Set status of the session management
//
//    $this->sessionIsEnabled( );
//      // Boolean for status of visit
//    $bool_newDownload = false;
//      // Set status of the session management
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // RETURN: no session, no counting of visits
//
//    if( ! $this->bool_session_enabled )
//    {
//        // DRS - Development Reporting System
//      if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
//      {
//        $prompt = 'Session management is disabled. Visits can\'t count.';
//        t3lib_div::devlog('[WARN/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 2);
//        $prompt = 'Enable session for better performance!';
//        t3lib_div::devlog('[HELP/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 1);
//      }
//        // DRS - Development Reporting System
//
//      return $bool_newDownload ;
//    }
//      // RETURN: no session, no counting of visits
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Init variables
//
//      // Uid of the current Browser plugin
//    $tt_content_uid       = $this->pObj->cObj->data['uid'];
//      // Get the name of the session data space
//    $str_data_space       = $this->getNameOfDataSpace( );
////      // Current table
////    $table                = $this->pObj->localTable;
////      // Name of the field for statistics data
////    $field                = $this->pObj->objStat->fieldDownloadsByVisits;
////      // Uid of the current record
////    $uid                  = $this->pObj->piVars['showUid'];
//      // Period between a current and a new download and visit in seconds
//    $timeout              = $this->pObj->objStat->timeout;
//      // Timestamp of now
//    $time                 = time( );
//      // Get tx_browser-pi1 session data
//    $arr_session_browser  = $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId);
//    $arr_session_visit    = $arr_session_browser[$tt_content_uid]['statistics']['visit'];
//      // Get tx_browser-pi1 session data
//      // Init variables
//
//
//
//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ' , $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId) );
//    }
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // RETURN: first visit
//
//    if( empty( $arr_session_visit[$table][$uid][$field] ) )
//    {
//        // Set the new visit
//      $arr_session_browser[$tt_content_uid]['statistics']['visit'][$table][$uid][$field] = $time;
//      $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_session_browser);
//        // Storing session data now (is proper in context with an PHP exit!)
//      $GLOBALS["TSFE"]->storeSessionData();
//        // Set the new visit
//
//        // DRS - Development Reporting System
//      if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
//      {
//        $prompt = 'First visit.';
//        t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//        $prompt = $table . '.record[' . $uid . '][' . $field . '] is set to: ' . $time;
//        t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//      }
//        // DRS - Development Reporting System
//
//        // RETURN
//      $bool_newDownload = true;
//      return $bool_newDownload;
//    }
//      // RETURN: first visit
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Repeated visit
//
//    $timeLastVisit    = $arr_session_visit[$table][$uid][$field];
//    $timeMinusTimeout = $time - $timeout;
//    switch( true )
//    {
//      case( $timeLastVisit <= $timeMinusTimeout ):
//          // new visit
//          // Set the new visit
//        $arr_session_browser[$tt_content_uid]['statistics']['visit'][$table][$uid][$field] = $time;
//        $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_session_browser);
//          // Set the new visit
//
//          // DRS - Development Reporting System
//        if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
//        {
//          $prompt = 'New visit: previous visit (' . $timeLastVisit . ' ) is older than ' . $timeMinusTimeout . '.';
//          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//          $prompt = $table . '.record[' . $uid . '][' . $field . '] is set to: ' . $time;
//          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//        }
//          // DRS - Development Reporting System
//
//        $bool_newDownload = true;
//        break;
//          // new visit
//      default:
//          // DRS - Development Reporting System
//        if( $this->pObj->b_drs_session || $this->pObj->b_drs_statistics )
//        {
//          $prompt = 'No new visit: previous visit (' . $timeLastVisit . ' ) isn\'t older than ' . $timeMinusTimeout . '.';
//          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//          $prompt = $table . '.record[' . $uid . '][' . $field . '] is left to: ' . $timeLastVisit;
//          t3lib_div::devlog('[INFO/SESSION+STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
//        }
//          // DRS - Development Reporting System
//
//          // no new visit
//        $bool_newDownload = false;
//          // no new visit
//    }
//      // Repeated visit
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // RETURN
//
//    return $bool_newDownload;
//      // RETURN
//  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_session.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_session.php']);
}
?>
