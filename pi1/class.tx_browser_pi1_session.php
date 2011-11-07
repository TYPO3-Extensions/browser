<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * @param object    The parent object
 * @return  void
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
 * @return  void
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
 * @return  string    user || ses
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
 * @return  void
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
        // listView will set $this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows']
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

    $arr_browser_session = $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId);
      // Get tx_browser-pi1 session data



      //////////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( empty( $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[INFO/WARN] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][mode-' . $this->mode . '][uids_of_all_rows] is empty!',
          $this->pObj->extKey, 2);
      }
    }
    if( ! empty( $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
      if ($this->pObj->b_drs_session || $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][mode-' . $this->mode . '][uids_of_all_rows] is set with ' .
          '#' . count($arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows']) . ' uids.',  $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////////
      //
      // Session data is not set: set tx_browser_pi1['uids_of_all_rows'] !

    if( ! isset( $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] ) )
    {
        // listView will set $this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows']
      $this->recordbrowser_callListView( );
        // Set the session array uids_of_all_rows
      $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] = $this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'];
        // Write session data tx_browser_pi1
      $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_browser_session);
        // DRS - Development Reporting System
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Session array [' . $str_data_space . ']' .
          '[' . $this->pObj->prefixId . '][' . $tt_content_uid . '][mode-' . $this->mode . '][uids_of_all_rows] is set with ' .
          '#' . count($this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows']) . ' uids.',  $this->pObj->extKey, 0);
      }
// dwildt, 111107
//          // Get uids of all records
//        $this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] = $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'];
    }
      // Session data is not set: set tx_browser_pi1['uids_of_all_rows'] !



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the global $this->pObj->uids_of_all_rows

    $this->pObj->uids_of_all_rows[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'] = $arr_browser_session[$tt_content_uid]['mode-' . $this->mode]['uids_of_all_rows'];
    return;
      // Set the global $this->pObj->uids_of_all_rows
  }








  /***********************************************
  *
  * Statistics
  *
  **********************************************/









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_session.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_session.php']);
}
?>