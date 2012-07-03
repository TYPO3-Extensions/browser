<?php
 /***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_navi_recordbrowser bundles methods for navigation like the Index-Browser
 * or the page broser. It is part of the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    browser
 * @version       4.1.2
 * @since 4.1.2
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   71: class tx_browser_pi1_navi_recordbrowser
 *  122:     public function __construct($parentObj)
 *
 *              SECTION: Index browser
 *  161:     public function indexBrowser( $arr_data )
 *  396:     public function indexBrowserTemplate($arr_data)
 *  667:     public function indexBrowserTabArray($arr_data)
 * 1107:     public function indexBrowserRowsInitial($arr_data)
 *
 *              SECTION: pagebrowser
 * 1381:     public function tmplPageBrowser($arr_data)
 *
 *              SECTION: mode selector
 * 1620:     public function prepaireModeSelector()
 * 1687:     public function tmplModeSelector($arr_data)
 *
 *              SECTION: record browser
 * 1809:     public function recordbrowser_get($str_content)
 * 1896:     public function recordbrowser_callListView()
 * 1968:     private function recordbrowser_rendering()
 * 2298:     public function recordbrowser_set_session_data($rows)
 *
 *              SECTION: downward compatibility
 * 2476:     public function getMarkerIndexbrowser( )
 * 2522:     public function getMarkerIndexbrowserTabs( )
 *
 * TOTAL FUNCTIONS: 14
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_recordbrowser
{

    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = false;
    // [Array] The current TypoScript configuration array
  var $mode       = false;
    // [Integer] The current mode (from modeselector)
  var $view       = false;
    // [String] 'list' or 'single': The current view
  var $conf_view  = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_path  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
    // Variables set by the pObj (by class.tx_browser_pi1.php)













 /**
  * Constructor. The method initiate the parent object
  *
  * @param	object		The parent object
  * @return	void
  * @version  4.1.2
  * @since    2.0.0
  */
  public function __construct($parentObj)
  {
      // Set the Parent Object
    $this->pObj = $parentObj;
  }

  
  
  
  
  
 /***********************************************
  *
  * record browser
  *
  **********************************************/



/**
  * recordbrowser_get:  Rplace the marker ###RECORD_BROWSER### with the rendered record browser
  *                     * Feature: #27041
  *
  * @param	string		$str_content: current content
  * @return	string		$str_content: content with rendered marker ###RECORD_BROWSER###
  * @version 3.7.0
  * @since 3.7.0
  */
  public function recordbrowser_get($str_content)
  {
    $markerArray['###RECORD_BROWSER###']  = null;


      /////////////////////////////////////
      //
      // RETURN record browser isn't enabled

    if(!($this->pObj->conf['navigation.']['record_browser'] == 1))
    {
      if ($this->pObj->b_drs_templating)
      {
        $value = $this->pObj->conf['navigation.']['record_browser'];
        t3lib_div::devlog('[INFO/TEMPLATING] navigation.record_browser is \'' . $value . '\' '.
          'Record browser won\'t be handled (best performance).', $this->pObj->extKey, 0);
      }
      $str_content = $this->pObj->cObj->substituteMarkerArray($str_content, $markerArray);
      return $str_content;
    }
      // RETURN record browser isn't enabled



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the global $this->pObj->uids_of_all_rows

    $this->pObj->objSession->cacheOfListView( );
      // Set the global $this->pObj->uids_of_all_rows



      //////////////////////////////////////////////////////////////////////////
      //
      // Render the record browser

    $markerArray['###RECORD_BROWSER###']  = $this->recordbrowser_rendering();
    $str_content                          = $this->pObj->cObj->substituteMarkerArray($str_content, $markerArray);
      // Render the record browser



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->b_drs_perform)
    {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After ' . __METHOD__ . ': ' . ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



    return $str_content;
  }









 /**
  * recordbrowser_callListView: Call the listView. It is needed for the record browser in the single view,
  *                             if there isn't any information about all available records.
  *                             The method allocates the global array $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] and
  *                             returns it.
  *                             The method will be called in two cases:
  *                             * Session management is disabled
  *                             * Single view is called without calling the list view before
  *
  * @return	void
  * @version  3.7.0
  * @since    3.7.0
  */
  public function recordbrowser_callListView()
  {
      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->b_drs_perform)
    {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] Before ' . __METHOD__ . ': '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      // Store current values
    $curr_rows        = $this->pObj->rows;
    $curr_view        = $this->pObj->view;
      // Set view to list
    $this->pObj->view = 'list';
      // listView will set $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']
      // #33892, 120214, dwildt-
    //$dummy = $this->pObj->objViews->listView($this->pObj->str_template_raw);
      // #33892, 120214, dwildt+
    $dummy = $this->pObj->objViewlist_3x->main($this->pObj->str_template_raw);
      // Restore current values
    $this->pObj->rows = $curr_rows;
    $this->pObj->view = $curr_view;



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->b_drs_perform)
    {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After ' . __METHOD__ . ': '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance
  }









 /**
  * recordbrowser_rendering: Render the record browser (HTML code)
  *
  * @return	string		$record_browser: HTML code
  * @version  3.7.0
  * @since    3.7.0
  */
  private function recordbrowser_rendering()
  {
    $record_browser = null;
    $arr_buttons      = array();

      // Uid of the current record
    $singlePid      = (int) $this->pObj->piVars['showUid'];
      // Uid of the current plugin
    $tt_content_uid = $this->pObj->cObj->data['uid'];



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser should not be displayed

    $bool_record_browser = $this->conf['navigation.']['record_browser'];
    if(!$bool_record_browser)
    {
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] record browser should not displayed: RETURN',  $this->pObj->extKey, 0);
      }
      return $record_browser;
    }
      // RETURN record_browser should not be displayed



      //////////////////////////////////////////////////////////////////////
      //
      // Get record_browser configuration

    $conf_record_browser = $this->conf['navigation.']['record_browser.'];
      // Get record_browser configuration



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser should not be displayed in case of no result

    $bool_display_without_result = $conf_record_browser['display.']['withoutResult'];
    if(!$bool_display_without_result)
    {
      if(empty($this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows']))
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] uids_of_all_rows is empty. ' .
            'record browser should not displayed in case of an empty result: RETURN',  $this->pObj->extKey, 0);
        }
        return $record_browser;
      }
    }
      // RETURN record_browser should not be displayed in case of no result



      //////////////////////////////////////////////////////////////////////
      //
      // Get first, current and last positions and the position array

    $uids_of_all_rows = $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'];
      // Position array: the position (0, 1, ... , n) will be the value, the uid of the record will be the key
    $pos_of_all_rows  = array_flip($uids_of_all_rows);

    $pos_of_first_row               = 0;
    $pos_of_curr_row                = $pos_of_all_rows[$singlePid];
    $pos_of_last_row                = $pos_of_all_rows[end($uids_of_all_rows)];
    $marker['###RECORD_SUM###']     = $pos_of_last_row + 1;
    $marker['###TT_CONTENT.UID###'] = $this->pObj->cObj->data['uid'];
      // Get first, current and last positions and the position array



      //////////////////////////////////////////////////////////////////////
      //
      // Set the button first

    $button = null;
    if($conf_record_browser['display.']['firstAndLastButton'])
    {
      if($pos_of_curr_row >= ($pos_of_first_row + 2))
      {
          // Get uid of the record
        $marker['###RECORD_UID###']       = $uids_of_all_rows[0];
          // Get position of the record
        $marker['###RECORD_POSITION###']  = $pos_of_all_rows[$marker['###RECORD_UID###']] + 1;

          // Get button configuration
        $button_name = $conf_record_browser['buttons.']['current.']['first'];
        $button_conf = $conf_record_browser['buttons.']['current.']['first.'];

          // Set and replace markers
        $button_conf = $this->pObj->objMarker->substitute_marker($button_conf, $marker);

          // Set button
        $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
      }
      if($pos_of_curr_row < ($pos_of_first_row + 2))
      {
        if($conf_record_browser['display.']['buttonsWithoutLink'])
        {
            // Get button configuration
          $button_name = $conf_record_browser['buttons_wo_link.']['current.']['first'];
          $button_conf = $conf_record_browser['buttons_wo_link.']['current.']['first.'];

            // Set button
          $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
        }
      }
    }
    if(!empty($button))
    {
      $arr_buttons[] = $button;
    }
      // Set the button first



      //////////////////////////////////////////////////////////////////////
      //
      // Set the button prev

    $button = null;
    if($pos_of_curr_row >= ($pos_of_first_row + 1))
    {
        // Get uid of the record
      $marker['###RECORD_UID###']       = $uids_of_all_rows[$pos_of_curr_row - 1];
        // Get position of the record
      $marker['###RECORD_POSITION###']  = $pos_of_all_rows[$marker['###RECORD_UID###']] + 1;

        // Get button configuration
      $button_name = $conf_record_browser['buttons.']['current.']['prev'];
      $button_conf = $conf_record_browser['buttons.']['current.']['prev.'];

        // Set and replace markers
      $button_conf = $this->pObj->objMarker->substitute_marker($button_conf, $marker);

        // Set button
      $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
    }
    if($pos_of_curr_row < ($pos_of_first_row + 1))
    {
      if($conf_record_browser['display.']['buttonsWithoutLink'])
      {
          // Get button configuration
        $button_name = $conf_record_browser['buttons_wo_link.']['current.']['prev'];
        $button_conf = $conf_record_browser['buttons_wo_link.']['current.']['prev.'];

          // Set button
        $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
      }
    }
    if(!empty($button))
    {
      $arr_buttons[] = $button;
    }
      // Set the button prev



      //////////////////////////////////////////////////////////////////////
      //
      // Set the button curr

    $button = null;
      // Get uid of the record
    $marker['###RECORD_UID###']       = $singlePid;
      // Get position of the record
    $marker['###RECORD_POSITION###']  = $pos_of_all_rows[$marker['###RECORD_UID###']] + 1;

      // Get button configuration
    $button_name = $conf_record_browser['buttons.']['current.']['curr'];
    $button_conf = $conf_record_browser['buttons.']['current.']['curr.'];

      // Set and replace markers
    $button_conf = $this->pObj->objMarker->substitute_marker($button_conf, $marker);

      // Set button
    $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);

    if(!empty($button))
    {
      $arr_buttons[] = $button;
    }
      // Set the button curr



      //////////////////////////////////////////////////////////////////////
      //
      // Set the button next

    $button = null;
    if($pos_of_curr_row <= ($pos_of_last_row - 1))
    {
        // Get uid of the record
      $marker['###RECORD_UID###']       = $uids_of_all_rows[$pos_of_curr_row + 1];
        // Get position of the record
      $marker['###RECORD_POSITION###']  = $pos_of_all_rows[$marker['###RECORD_UID###']] + 1;

        // Get button configuration
      $button_name = $conf_record_browser['buttons.']['current.']['next'];
      $button_conf = $conf_record_browser['buttons.']['current.']['next.'];

        // Set and replace markers
      $button_conf = $this->pObj->objMarker->substitute_marker($button_conf, $marker);

        // Set button
      $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
    }
    if($pos_of_curr_row > ($pos_of_last_row - 1))
    {
      if($conf_record_browser['display.']['buttonsWithoutLink'])
      {
          // Get button configuration
        $button_name = $conf_record_browser['buttons_wo_link.']['current.']['next'];
        $button_conf = $conf_record_browser['buttons_wo_link.']['current.']['next.'];

          // Set button
        $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
      }
    }
    if(!empty($button))
    {
      $arr_buttons[] = $button;
    }
      // Set the button next



      //////////////////////////////////////////////////////////////////////
      //
      // Set the button last

    $button = null;
    if($conf_record_browser['display.']['firstAndLastButton'])
    {
      if($pos_of_curr_row <= ($pos_of_last_row - 2))
      {
          // Get uid of the record
        $marker['###RECORD_UID###']       = $uids_of_all_rows[count($uids_of_all_rows) - 1];
          // Get position of the record
        $marker['###RECORD_POSITION###']  = $pos_of_all_rows[$marker['###RECORD_UID###']] + 1;

          // Get button configuration
        $button_name = $conf_record_browser['buttons.']['current.']['last'];
        $button_conf = $conf_record_browser['buttons.']['current.']['last.'];

          // Set and replace markers
        $button_conf = $this->pObj->objMarker->substitute_marker($button_conf, $marker);

          // Set button
        $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
      }
      if($pos_of_curr_row > ($pos_of_last_row - 2))
      {
        if($conf_record_browser['display.']['buttonsWithoutLink'])
        {
            // Get button configuration
          $button_name = $conf_record_browser['buttons_wo_link.']['current.']['last'];
          $button_conf = $conf_record_browser['buttons_wo_link.']['current.']['last.'];

            // Set button
          $button = $this->pObj->cObj->cObjGetSingle($button_name, $button_conf);
        }
      }
    }
    if(!empty($button))
    {
      $arr_buttons[] = $button;
    }
      // Set the button last



      //////////////////////////////////////////////////////////////////////
      //
      // Set the record browser

      // Devide configuration
    $devider_name = $conf_record_browser['buttons.']['current.']['devider'];
    $devider_conf = $conf_record_browser['buttons.']['current.']['devider.'];

      // Set devider
    $devider = $this->pObj->cObj->cObjGetSingle($devider_name, $devider_conf);

      // Devide buttons
    $record_browser = implode($devider, $arr_buttons);

      // Wrapper configuration
    $wrap_all_name = $conf_record_browser['buttons.']['current.']['wrap_all'];
    $wrap_all_conf = $conf_record_browser['buttons.']['current.']['wrap_all.'];
    if(empty($wrap_all_conf['value']))
    {
      $wrap_all_conf['value'] = $record_browser;
    }

      // Wrap record browser
    $record_browser = $this->pObj->cObj->cObjGetSingle($wrap_all_name, $wrap_all_conf);
      // Set the record browser



      // RETURN the record browser
    return $record_browser;
  }









 /**
  * recordbrowser_set_session_data: Set session data for the record browser.
  *                                 * We need the record browser in the single view.
  *                                 * This method must be called, before the page browser
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param	array		$rows: $idsForRecordBrowser
  * @return	void
  * @version 4.1.2
  * @since 4.1.2
  */
  public function recordbrowser_set_session_data( $rows, $idsForRecordBrowser )
  {
      // Uid of the current plugin
    $tt_content_uid = $this->pObj->cObj->data['uid'];



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN record browser isn't enabled

    if( ! ( $this->pObj->conf['navigation.']['record_browser'] == 1 ) )
    {
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        $value = $this->pObj->conf['navigation.']['record_browser'];
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] navigation.record_browser is \'' . $value . '\' '.
          'Record browser won\'t be handled (best performance).', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN record browser isn't enabled



      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the session management

    $this->pObj->objSession->sessionIsEnabled( );
      // Get the name of the session data space
    $str_data_space = $this->pObj->objSession->getNameOfDataSpace( );
      // Set status of the session management


    
    switch( true )
    {
      case( ! empty( $idsForRecordBrowser ) ):
var_dump( __METHOD__, __LINE__, $idsForRecordBrowser );
        $this->recordbrowser_set_session_dataByIds( $idsForRecordBrowser );
        break;
      case( ! empty( $rows ) ):
var_dump( __METHOD__, __LINE__, $rows );
        $this->recordbrowser_set_session_dataRows( $rows );
        break;
      default:
var_dump( __METHOD__, __LINE__, $rows, $idsForRecordBrowser );
          // Get the tx_browser_pi1 session array
        $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey( $str_data_space, $this->pObj->prefixId );
          // Empty the array with the uids of all rows
        $arr_browser_session[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = array( );
          // Set the tx_browser_pi1 session array
        $GLOBALS['TSFE']->fe_user->setKey( $str_data_space, $this->pObj->prefixId, $arr_browser_session );
        if( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
        {
          $prompt = 'Rows are empty. Session array [' . $this->pObj->prefixId . '][mode-' . $this->mode . ']' . 
                    '[uids_of_all_rows] will be empty.';
          t3lib_div::devlog( '[INFO/SESSION+TEMPLATING] ' . $prompt,  $this->pObj->extKey, 0 );
        }
        break;
    }
    
    return;
    
  }



/**
  * recordbrowser_set_session_dataByIds: Set session data for the record browser.
  *                                 * We need the record browser in the single view.
  *                                 * This method must be called, before the page browser
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param	array		$rows: $idsForRecordBrowser
  * @return	void
  * @version 4.1.2
  * @since 4.1.2
  */
  private function recordbrowser_set_session_dataByIds( $idsForRecordBrowser )
  {
    $this->recordbrowser_set_session_execute( $idsForRecordBrowser );

    return;
  }



/**
  * recordbrowser_set_session_dataByIds: Set session data for the record browser.
  *                                 * We need the record browser in the single view.
  *                                 * This method must be called, before the page browser
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param	array		$rows: $idsForRecordBrowser
  * @return	void
  * @version 4.1.2
  * @since 4.1.2
  */
  private function recordbrowser_set_session_execute( $ids )
  {
      // No session: set global array
    $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = array( );
    if( ! $this->pObj->objSession->bool_session_enabled )
    {
      $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $ids;
      if( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        $prompt = 'No session (less performance): global array uids_of_all_rows is set with ' . 
                  '#' . count( $ids ) . ' uids.';
        t3lib_div::devlog( '[INFO/SESSION+TEMPLATING] ' . $prompt,  $this->pObj->extKey, 0 );
      }
      return;
    }
      // No session: set global array

      // Get the name of the session data space
    $str_data_space = $this->pObj->objSession->getNameOfDataSpace( );

      // Set the session array
      // Get the tx_browser_pi1 session array
    $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey( $str_data_space, $this->pObj->prefixId );
      // Overwrite the array with the uids of all rows
    $arr_browser_session[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $ids;
      // Set the tx_browser_pi1 session array
    $GLOBALS['TSFE']->fe_user->setKey( $str_data_space, $this->pObj->prefixId, $arr_browser_session );
    if( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
    {
      $prompt = 'Session array [' . $str_data_space . '][' . $this->pObj->prefixId . ']' .
                '[mode-' . $this->mode . '][uids_of_all_rows] is set with #' . count($ids) . ' uids.';
      t3lib_div::devlog( '[INFO/SESSION+TEMPLATING] ' . $prompt,  $this->pObj->extKey, 0 );
    }
      // Set the session array

    return;
  }









 /**
  * recordbrowser_set_session_dataRows: Set session data for the record browser.
  *                                 * We need the record browser in the single view.
  *                                 * This method must be called, before the page browser
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param	array		$rows: $idsForRecordBrowser
  * @return	array		$arr_return: false in case of success, otherwise array with an error message
  * @version 4.1.2
  * @since 4.1.2
  */
  private function recordbrowser_set_session_dataRows( $rows )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Get table.field for uid of the local table

    $key_for_uid = $this->pObj->arrLocalTable['uid'];

      // RETURN uid table.field isn't any key
    $key = key( $rows );
    if( ! isset( $rows[$key][$key_for_uid] ) )
    {
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error Record Browser</h1>';
      $arr_return['error']['prompt'] = '<p style="color:red">Key is missing in $rows. Key is ' . $key_for_uid . '</p>';
      $arr_return['error']['prompt'] = $arr_return['error']['prompt'] . '<p>' . __METHOD__ . ' (' . __LINE__ . ')</p>';
      if ( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog('[INFO/ERROR] table.field for uid of the local table is not a key.' . $this->mode . '][uids_of_all_rows] will be empty.',  $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
      // RETURN uid table.field isn't any key
      // Get table.field for uid of the local table



      //////////////////////////////////////////////////////////////////////////
      //
      // LOOP rows: set the array with uids

    $arr_uid = array( );
    foreach( (array) $rows as $row => $elements )
    {
      $arr_uid[] = $elements[$key_for_uid];
    }
    //echo '<pre>' . var_export($arr_uid, true) . '</pre>';
      // LOOP rows: set the array with uids

    $this->recordbrowser_set_session_execute( $arr_uid );

    return;
  }









 /**
  * recordbrowser_set_session_data_3x: Set session data for the record browser.
  *                                 * We need the record browser in the single view.
  *                                 * This method must be called, before the page browser
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param	array		$rows: Array with all available rows of the list view in order of the list view
  * @return	array		$arr_return: false in case of success, otherwise array with an error message
  * @version 3.7.0
  * @since 3.7.0
  */
  public function recordbrowser_set_session_data_3x( $rows )
  {
      // Uid of the current plugin
    $tt_content_uid = $this->pObj->cObj->data['uid'];



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN record browser isn't enabled

    if( ! ( $this->pObj->conf['navigation.']['record_browser'] == 1 ) )
    {
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        $value = $this->pObj->conf['navigation.']['record_browser'];
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] navigation.record_browser is \'' . $value . '\' '.
          'Record browser won\'t be handled (best performance).', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN record browser isn't enabled



      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the session management

    $this->pObj->objSession->sessionIsEnabled( );
      // Get the name of the session data space
    $str_data_space = $this->pObj->objSession->getNameOfDataSpace( );
      // Set status of the session management



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN rows are empty

    if( empty( $rows) )
    {
        // Get the tx_browser_pi1 session array
      $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey($str_data_space, $this->pObj->prefixId);
        // Empty the array with the uids of all rows
      $arr_browser_session[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = array();
        // Set the tx_browser_pi1 session array
      $GLOBALS['TSFE']->fe_user->setKey($str_data_space, $this->pObj->prefixId, $arr_browser_session);
      if ($this->pObj->b_drs_session || $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/SESSION+TEMPLATING] Rows are empty. Session array [' . $this->pObj->prefixId . '][mode-' . $this->mode . '][uids_of_all_rows] will be empty.',  $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN rows are empty



      //////////////////////////////////////////////////////////////////////////
      //
      // Get table.field for uid of the local table

    $key_for_uid = $this->pObj->arrLocalTable['uid'];

      // RETURN uid table.field isn't any key
    $key = key( $rows );
    if( ! isset( $rows[$key][$key_for_uid] ) )
    {
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error Record Browser</h1>';
      $arr_return['error']['prompt'] = '<p style="color:red">Key is missing in $rows. Key is ' . $key_for_uid . '</p>';
      $arr_return['error']['prompt'] = $arr_return['error']['prompt'] . '<p>' . __METHOD__ . ' (' . __LINE__ . ')</p>';
      if ( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog('[INFO/ERROR] table.field for uid of the local table is not a key.' . $this->mode . '][uids_of_all_rows] will be empty.',  $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
      // RETURN uid table.field isn't any key
      // Get table.field for uid of the local table



      //////////////////////////////////////////////////////////////////////////
      //
      // LOOP rows: set the array with uids

    $arr_uid = array( );
    foreach( (array) $rows as $row => $elements )
    {
      $arr_uid[] = $elements[$key_for_uid];
    }
    //echo '<pre>' . var_export($arr_uid, true) . '</pre>';
      // LOOP rows: set the array with uids



      //////////////////////////////////////////////////////////////////////////
      //
      // No session: set global array

    $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = array( );
    if( ! $this->pObj->objSession->bool_session_enabled )
    {
      $this->pObj->uids_of_all_rows[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $arr_uid;
      if( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/SESSION+TEMPLATING] No session (less performance): ' .
          'global array uids_of_all_rows is set with ' .
          '#' . count( $arr_uid ) . ' uids.',  $this->pObj->extKey, 0 );
      }
      return false;
    }
      // No session: set global array



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the name of the session data space

    $str_data_space = $this->pObj->objSession->getNameOfDataSpace( );
      // Get the name of the session data space



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the session array

      // Get the tx_browser_pi1 session array
    $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey( $str_data_space, $this->pObj->prefixId );
      // Overwrite the array with the uids of all rows
    $arr_browser_session[$tt_content_uid]['cache']['mode-' . $this->mode]['uids_of_all_rows'] = $arr_uid;
      // Set the tx_browser_pi1 session array
    $GLOBALS['TSFE']->fe_user->setKey( $str_data_space, $this->pObj->prefixId, $arr_browser_session );
    if( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog('[INFO/TEMPLATING] Session array [' . $str_data_space . '][' . $this->pObj->prefixId . '][mode-' . $this->mode . '][uids_of_all_rows] is set with ' .
        '#' . count($arr_uid) . ' uids.',  $this->pObj->extKey, 0);
    }
      // Set the session array

    return false;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_recordbrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_recordbrowser.php']);
}

?>
