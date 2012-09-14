<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_zz bundles zz methods for the extension browser
 *
 * @author      Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.13
 * @since       1.0.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  101: class tx_browser_pi1_zz
 *  147:     function __construct($parentObj)
 *
 *              SECTION: piVars
 *  181:     function prepairePiVars()
 *  819:     function removeFiltersFromPiVars($inputPiVars, $filterConf)
 *  875:     function advanced_remove_piVars($keepFilters=0)
 *  981:     function advanced_remove_piVars_filter()
 *
 *              SECTION: $GLOBAL markers
 * 1109:     function get_t3globals_value($marker)
 * 1183:     function substitute_t3globals_recurs($arr_multi_dimensional)
 *
 *              SECTION: CSV process and format time
 * 1348:     function getCSVasArray($csvValues)
 * 1364:     function getCSVtablefieldsAsArray($csvTableFields)
 * 1385:     public function cleanUp_lfCr_doubleSpace($csvValue)
 * 1409:     function setTsStrftime()
 *
 *              SECTION: Link
 * 1459:     function linkTP($str, $typolink=array(), $urlParameters=array(), $cache=0, $altPageId=0)
 * 1494:     function linkTP_keepPIvars($str, $typolink=array(), $overrulePIvars=array(), $cache=0, $clearAnyway=0, $altPageId=0)
 * 1528:     function get_absUrl($str_relUrl)
 * 1563:     function get_singlePid_for_listview()
 * 1623:     function get_cHash($str_params)
 * 1646:     function get_pathWoEXT($str_TYPO3_EXT_path)
 *
 *              SECTION: Markers
 * 1699:     function extend_marker_wi_pivars($markerArray)
 *
 *              SECTION: TypoScript children records
 * 1744:     function children_tsconf_recurs($key, $arr_multi_dimensional, $str_devider)
 *
 *              SECTION: Languages, _LOCAL_LANG
 * 1836:     function getTableFieldLL($tableField)
 * 1928:     function initLang()
 *
 *              SECTION: Sword and Search respectively
 * 1972:     function search_values($str_sword_phrase)
 * 2416:     function color_swords($tableField, $value)
 *
 *              SECTION: Security
 * 2509:     function secure_piVar($str_value, $str_type)
 *
 *              SECTION: TCA
 * 2657:     function loadTCA($str_table)
 *
 *              SECTION: TypoScript
 * 2694:     function cleanup_views($conf)
 *
 *              SECTION: UTF-8
 * 2741:     function b_TYPO3_utf8()
 * 2819:     function char_single_multi_byte($str_char)
 *
 *              SECTION: Arrays
 * 2890:     public function zz_devPromptArrayNonUnique( $testArray, $method, $line )
 *
 * TOTAL FUNCTIONS: 29
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
  class tx_browser_pi1_zz
  {






  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)


    //////////////////////////////////////////////////////
    //
    // Variables set by this class

    // [Array] The current TypoScript configuration array
  var $conf       = false;
    // [Array] Temporarily array for storing piVars
  var $tmp_piVars   = false;
    // [Array] Array with all keys of the TYPO3 array $GLOBALS
  var $arr_t3global_keys = false;

    #10116
    // [Boolean] Don't replace $GLOBALS
  var $bool_advanced_dontReplace    = true;
    // [Integer] Maximum Number for recursive loops
  var $int_advanced_recursionGard   = 10000;
    // [Array] Array with security configuration for the search word
  var $arr_advanced_securitySword   = null;
    // #12528, dwildt, 110125
    // [Boolean] Empty marker in TypoScript will be removed
  var $bool_advanced_3_6_0_rmMarker = false;






/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
    $this->conf = $this->pObj->conf;
  }










  /***********************************************
  *
  * piVars
  *
  **********************************************/








  /**
 * Prepaire piVars. Allocates values to $this->piVars and $this->pi_isOnlyFields
 *
 * @return	void
 * @version 3.9.13
 */
  function prepairePiVars()
  {
      //////////////////////////////////////
      //
      // DRS message?

    if (isset($this->pObj->piVars['drs']))
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = $this->pObj->piVars['drs'];
        t3lib_div::devlog('[WARN/DRS] Page was reloaded because of \''.$prompt.'\'', $this->pObj->extKey, 2);
      }
      unset($this->pObj->piVars['drs']);
    }
      // DRS message?



      ////////////////////////////////////////////////////////////////////////////
      //
      // _GET - Allocate piVars from _GET, if they aren't set

      // 3.9.24, 120604, dwildt+
    switch( $this->pObj->objFlexform->handlePiVars )
    {
      case( 'forEachPlugin'):
          // #11579, dwildt, 101219
        foreach( ( array ) $GLOBALS['_GET'][$this->pObj->prefixId] as $key => $value )
        {
          if( ! isset( $this->pObj->piVars[$key] ) )
          {
            $this->pObj->piVars[$key] = stripslashes( $value );
          }
        }
        break;
      case( 'forCurrentPluginOnly' ):
          // do nothing;
        break;
      default:
        $prompt = 'Switch with undefined value in ' . __METHOD__ . ' at line ' . __LINE__ . '<br />' .
                  'Sorry, this error should not occured!.<br />' .
                  'Browser - TYPO3 without PHP.'; 
    }
      // 3.9.24, 120604, dwildt+
      // _GET - Allocate piVars from _GET, if they aren't set


    $conf = $this->pObj->conf;
      // #9599
    if(empty($this->pObj->piVars['mode']))
    {
      $this->pObj->objFlexform->prepare_mode();
      $mode = $this->pObj->objFlexform->mode;
    }
    if(!empty($this->pObj->piVars['mode']))
    {
      $mode = $this->pObj->piVars['mode'];
    }
      // $view = $this->pObj->view;  // Will be set below



      //////////////////////////////////////////////////////////////////////
      //
      // Do we have an alias for showUid?
      // #9599

    if(!isset($this->pObj->piVars['showUid']))
    {
      $str_alias_showUid = $conf['views.']['list.'][$mode.'.']['showUid'];
      if(!empty($str_alias_showUid))
      {
        $this->pObj->piVars['showUid']    = $this->pObj->piVars[$str_alias_showUid];
        $this->pObj->piVar_alias_showUid  = $str_alias_showUid;
        if ($this->pObj->b_drs_realurl)
        {
          $prompt = 'showUid has the alias: '.$str_alias_showUid;
          t3lib_div::devlog('[INFO/REALURL] '.$prompt, $this->pObj->extKey, 1);
        }
        if ($this->pObj->b_drs_warn)
        {
          if(empty($this->pObj->piVars['showUid']))
          {
            t3lib_div::devlog('[INFO/FLEXFORM] showUid is empty. If you have expect a value for the current plugin,
              please configure in the current plugin: [General] handle piVars from foreign plugins!',
              $this->pObj->extKey, 0);
          }
        }
      }
    }
      // Do we have an alias for showUid?



      //////////////////////////////////////
      //
      // Simulate showUid from current plugin

//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('zz 277', $this->pObj->objFlexform->int_singlePid);
    if($this->pObj->objFlexform->int_singlePid)
    {
      $this->pObj->piVars['showUid'] = $this->pObj->objFlexform->int_singlePid;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] Plugin has a simulated single uid: '.
          $this->pObj->piVars['showUid'], $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] This UID has precedence!', $this->pObj->extKey, 0);
      }
    }
      // Simulate showUid from current plugin



      //////////////////////////////////////
      //
      // Security showUid

      // RELOAD the HTML page with another URL, if showUid isn't proper
      // #36707, dwildt, 1-
    //if ( isset( $this->pObj->piVars['showUid'] ) )
      // #36707, dwildt, 1+
    if ( isset( $this->pObj->piVars['showUid'] ) && ! empty( $this->pObj->piVars['showUid'] ) )
    {
      $str_showUid = $this->pObj->piVars['showUid'];
      if (!is_numeric($str_showUid))
      {
        // We have a string. It happens, if we have a non propper url.
        if ($this->pObj->b_drs_navi)
        {
          t3lib_div::devlog('[WARN/NAVIGATION] piVars[showUid] isn\'t a propper id:<br />'.
            $str_showUid.'<br /><br />
            It is unset!', $this->pObj->extKey, 2);
        }
        unset($this->pObj->piVars['showUid']);
        $typolink['parameter']  = $GLOBALS['TSFE']->id;
        $typolink['returnLast'] = 'url';
        $this->pObj->piVars['drs'] = 'unproperUid';
        $prompt = false;
        $str_path   = $this->linkTP_keepPIvars($prompt, $typolink, $this->pObj->piVars, $this->pObj->boolCache);
        $str_url = $this->get_absUrl($str_path);
        header('Location: '.$str_url);
      }
      if (is_numeric($str_showUid))
      {
        $this->pObj->piVars['showUid'] = $this->secure_piVar($this->pObj->piVars['showUid'], 'integer');
      }
    }
      // RELOAD the HTML page with another URL, if showUid isn't proper
      // Security showUid



      //////////////////////////////////////////////////////////////////////
      //
      // Field display_listview
      // #31156, dwildt, 110806

    if( $this->pObj->conf['flexform.']['viewSingle.']['display_listview'] )
    {
      unset($this->pObj->piVars['showUid']);
      if ($this->pObj->b_drs_all)
      {
        t3lib_div :: devlog('[INFO/ALL] piVar showUid is unset, because flexform.viewSingle.display_listview is true.', $this->pObj->extKey, 0);
      }
    }
      // #31156, dwildt, 110806
      // Field display_listview



      //////////////////////////////////////////////////////////////////////
      //
      // Catch the view type LIST or SINGLE

    switch($this->pObj->piVars['showUid'])
    {
      case(true):
        // We have a single view
        $this->pObj->view = 'single';
        break;
      default:
        // We have a list view
        $this->pObj->view = 'list';
        break;
    }
      // Catch the view type LIST or SINGLE



      //////////////////////////////////////////////////////////////////////
      //
      // Get configuration array for LIST or SINGLE

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];
    // Get configuration array for LIST or SINGLE



      //////////////////////////////////////////////////////////////////////
      //
      // Handle the segments to output for AJAX

      // #9659, 101017, dwildt
      // switch off views, depending on piVar
    $this->pObj->objJss->set_arrSegment();
      // Remove segment from piVars immediately (Never set AJAX segment by PHP)
    if(isset($this->pObj->piVars['segment']))
    {
      unset($this->pObj->piVars['segment']);
    }
      // Remove segment from piVars immediately (Never set AJAX segment by PHP)
      // Handle the segments to output for AJAX



      //////////////////////////////////////////////////////////////////////
      //
      // Set the global boolFirstVisit

    $this->pObj->boolFirstVisit = true;
    $int_max_piVars = 0;
    if( is_array( $this->pObj->piVars ) )
    {
        // #13006, dwildt, 110310
        // Don't take care about showUid, if it is empty
        // #28878, 110810, dwildt
        // #40937, 120915, dwildt, 1-
      //if( isset( $this->pObj->piVars['showUid'] ) && empty( $this->pObj->piVars['showUid'] ) )
        // #40937, 120915, dwildt, 1+
        //$this->pObj->dev_var_dump( array_key_exists( 'showUid', $this->pObj->piVars ), empty( $this->pObj->piVars['showUid'] ) );
      if( array_key_exists( 'showUid', $this->pObj->piVars ) && empty( $this->pObj->piVars['showUid'] ) )
      {
        $int_max_piVars++;
      }
        // Don't take care about showUid, if it is empty

        // piVars['plugin'] is a system piVar and it is independent of the visiting times
      if( isset( $this->pObj->piVars['plugin'] ) )
      {
        $int_max_piVars++;
      }
        // piVars['plugin'] is a system piVar and it is independent of the visiting times
        // If there are more than max_piVars, it isn't the first visit
      if( count( $this->pObj->piVars ) > $int_max_piVars )
      {
        $this->pObj->boolFirstVisit = false;
      }
        // If there are mor than max_piVars, it isn't the first visit
    }
      // Set the global boolFirstVisit



      //////////////////////////////////////
      //
      // Pointer of the Page Browser

      // Security
    $pageBrowserPointerLabel = $this->conf['navigation.']['pageBrowser.']['pointer'];
    if (isset($this->pObj->piVars[$pageBrowserPointerLabel]))
    {
      $this->pObj->piVars[$pageBrowserPointerLabel] = $this->secure_piVar($this->pObj->piVars[$pageBrowserPointerLabel], 'integer');
    }
      // Security

      // Default Process
    if (!isset($this->pObj->piVars[$pageBrowserPointerLabel]))
    {
      $this->pObj->piVars[$pageBrowserPointerLabel] = 0;
    }
      // Default Process

      // DRS- Development Reporting System
    if(!$this->pObj->b_drs_all && $this->pObj->b_drs_navi)
    {
      $prompt = 'tx_browser_pi1[' . $pageBrowserPointerLabel . '] = '.$this->pObj->piVars[$pageBrowserPointerLabel];
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS- Development Reporting System

      // Unset pointer, if it is 0 or empty
    if($this->pObj->piVars[$pageBrowserPointerLabel] == 0)
    {
      unset($this->pObj->piVars[$pageBrowserPointerLabel]);
      if($this->pObj->b_drs_navi)
      {
        $prompt = 'tx_browser_pi1[pointer] is deleted, because its value is 0.';
        t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
    if($this->pObj->piVars[$pageBrowserPointerLabel] == '')
    {
      unset($this->pObj->piVars[$pageBrowserPointerLabel]);
    }
      // Unset pointer, if it is 0 or empty
      // Pointer of the Page Browser



      //////////////////////////////////////
      //
      // Unset mode, if we have only one view or if mode is empty
      // +++ This snippet is corresponding with the mode snippet in tx_brwoser_pi1_config::prepare_piVars() !!!

      // Security
    if ( isset( $this->pObj->piVars['mode'] ) )
    {
      $this->pObj->piVars['mode'] = $this->secure_piVar( $this->pObj->piVars['mode'], 'integer' );
    }
      // Security

      // Set the global piVar_mode
    if ( ! isset($this->pObj->piVars['mode'] ) )
    {
      if ( is_array( $this->pObj->conf['views.'][$viewWiDot] ) )
      {
        reset( $this->pObj->conf['views.'][$viewWiDot] );
        $firstKeyWiDot 		= key( $this->pObj->conf['views.'][$viewWiDot] );
        $firstKeyWoDot 		= substr( $firstKeyWiDot, 0, strlen($firstKeyWiDot ) - 1 );
        $this->pObj->piVar_mode = $firstKeyWoDot;
      }
      if ( ! is_array( $this->pObj->conf['views.'][$viewWiDot] ) )
      {
        $this->pObj->piVar_mode = $this->pObj->piVars['mode'];
      }
    }
    else
    {
      $this->pObj->piVar_mode = $this->pObj->piVars['mode'];
    }
      // Set the global piVar_mode

      // Unset mode, if we have only one view
    if ( count( $conf['views.'][$viewWiDot] ) < 2 )
    {
        // We have one view only. We don't need any piVar_mode
      unset( $this->pObj->piVars['mode'] );
      if( $this->pObj->b_drs_navi )
      {
        t3lib_div::devlog('[INFO/NAVIGATION] tx_browser_pi1[mode] is deleted, because there is one view only.', $this->pObj->extKey, 0);
      }
    }
      // Unset mode, if we have only one view
      // Unset mode, if we have only one view or if mode is empty



      //////////////////////////////////////
      //
      // Sword

      // Set the default value for the sword. JavaScript will display it in the search field, if search field is empty
    $str_sword_default = $this->pObj->pi_getLL('label_sword_default', 'Search Word', true);
    $str_sword_default = htmlspecialchars($str_sword_default);
      // Set the default value for the sword. JavaScript will display it in the search field, if search field is empty
      // Unset sword, if sword is the default value
    if ($this->pObj->piVars['sword'] == $str_sword_default)
    {
      unset($this->pObj->piVars['sword']);
      if($this->pObj->b_drs_navi)
      {
        t3lib_div::devlog('[INFO/NAVIGATION] tx_browser_pi1[sword] is the default value: \''.$str_sword_default.'\'. Sword is  deleted.', $this->pObj->extKey, 0);
      }
    }
      // Unset sword, if sword is the default value

      // Unset sword, if sword is empty
    if (isset($this->pObj->piVars['sword']))
    {
      if ($this->pObj->piVars['sword'] == '')
      {
        unset($this->pObj->piVars['sword']);
        if($this->pObj->b_drs_navi)
        {
          t3lib_div::devlog('[INFO/NAVIGATION] tx_browser_pi1[sword] is empty. Sword is  deleted.', $this->pObj->extKey, 0);
        }
      }
    }
      // Unset sword, if sword is empty

      // Unset sword, if len of sword is less than three
    if (isset($this->pObj->piVars['sword']))
    {
      #10116
      if(!empty($conf_view['advanced.']))
      {
        $int_minLenSword                    = $conf_view['advanced.']['security.']['sword.']['minLenWord'];
        $this->bool_advanced_dontReplace    = $conf_view['advanced.']['performance.']['GLOBALS.']['dont_replace'];
        $this->int_advanced_recursionGard   = (int) $conf_view['advanced.']['recursionGuard'];
        $this->arr_advanced_securitySword   = $conf_view['advanced.']['security.']['sword.'];
          // #12528, dwildt, 110125
        $this->bool_advanced_3_6_0_rmMarker = $conf_view['advanced.']['downgrade.']['3_6_0.marker.']['in_typoscript.']['remove_emptyMarker'];
      }
      if(empty($conf_view['advanced.']))
      {
        $int_minLenSword                    = $conf['advanced.']['security.']['sword.']['minLenWord'];
        $this->bool_advanced_dontReplace    = $conf['advanced.']['performance.']['GLOBALS.']['dont_replace'];
        $this->int_advanced_recursionGard   = (int) $conf['advanced.']['recursionGuard'];
        $this->arr_advanced_securitySword   = $conf['advanced.']['security.']['sword.'];
          // #12528, dwildt, 110125
        $this->bool_advanced_3_6_0_rmMarker = $conf['advanced.']['downgrade.']['3_6_0.marker.']['in_typoscript.']['remove_emptyMarker'];
      }

      if (strlen(trim($this->pObj->piVars['sword'])) < $int_minLenSword)
      {
        if($this->pObj->b_drs_navi)
        {
          t3lib_div::devlog('[INFO/NAVIGATION] len of tx_browser_pi1[sword] is less than 3: \''.$this->pObj->piVars['sword'].'\'. Sword is  deleted.', $this->pObj->extKey, 0);
        }
        unset($this->pObj->piVars['sword']);
      }
    }
      // Unset sword, if sword is the default value

      // Security
    if (isset($this->pObj->piVars['sword']))
    {
      $this->pObj->piVar_sword = $this->secure_piVar($this->pObj->piVars['sword'], 'sword');
    }
      // Security
      // Store sword words and phrases global
    if (isset($this->pObj->piVar_sword))
    {
      $arr_return = $this->search_values($this->pObj->piVar_sword);
      $this->pObj->arr_swordPhrases = $arr_return['data']['arr_sword'];
      $this->pObj->arr_swordToShort = $arr_return['data']['arr_short'];
      if (count($arr_return['data']['arr_short']) > 0)
      {
        $this->pObj->piVars['sword'] = $arr_return['data']['str_sword'];
      }
      $this->pObj->arr_resultphrase = $arr_return['data']['arr_resultphrase'];
    }
      // Store sword words and phrases global
      // Sword



// #11579, dwildt, 101219
// Don't reload the HTML page with another URL, if there are different values
// Cause: Security check moves values in some case

//      ////////////////////////////////////////////////////////////////////////////
//      //
//      // SWORD - RELOAD?
//      //
//      // Reload the HTML page with another URL, if there are different values
//      // for the sword in _GET and piVars
//
//      // Do we have a sword in the URL?
//    $str_swordInUrl = false;
//    if(isset($GLOBALS['_GET'][$this->pObj->prefixId]['sword']))
//    {
//      $str_swordInUrl = $GLOBALS['_GET'][$this->pObj->prefixId]['sword'];
//    }
//      // Do we have a sword in the URL?
//      // Is there a difference to the current sword?
//      // #9659, 101010 dwildt, AJAX
//    if (!$this->pObj->objFlexform->bool_ajax_enabled)
//    {
//      if($str_swordInUrl && ($str_swordInUrl != $this->pObj->piVars['sword']))
//      {
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false))
//{
//  var_dump('zz 542', $str_swordInUrl, $this->pObj->piVars['sword']);
//  exit;
//}
//          //$this->pObj->piVars['sword'] = rawurlencode($this->pObj->piVars['sword']);
//          // "friedrich 234 456" will become "friedrich%20234%20456"
//          // Comment out:
//          // There is no difference with and with out / 091127, dwildt
//        $typolink['parameter']  = $GLOBALS['TSFE']->id;
//        $typolink['returnLast'] = 'url';
//          //$this->pObj->piVars['sword'] = rawurlencode($this->pObj->piVars['sword']); //:todo: TO SHORT
//        unset($this->pObj->piVars['sword']);  // dwildt, 100209
//        $this->pObj->piVars['drs'] = 'unproperSword';
//        $str_swords_toShort = implode(', ', $arr_return['data']['arr_short']);
//        $this->pObj->piVars['drs_swordstoshort'] = $str_swords_toShort;
//        $str_path     = $this->linkTP_keepPIvars($prompt, $typolink, $this->pObj->piVars, $this->pObj->boolCache);
//        $str_site_url = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
//        $str_url      = $str_site_url.$str_path;
//          // Prevent simulateStatic bug
//        $tmp_site_url = substr($str_path, 0, strlen($str_site_url));
//        if ($tmp_site_url == $str_site_url)
//        {
//            // $str_path got a full qualified URL
//          $str_url = $str_path;
//        }
//        if ($tmp_site_url != $str_site_url)
//        {
//          $str_url = $str_site_url.$str_path;
//        }
//          // Prevent simulateStatic bug
//        header('Location: '.$str_url);
//      }
//    }
//      // Is there a difference to the current sword?
//      // SWORD - RELOAD?



    //////////////////////////////////////
    //
    // Default Index-Browser tab

    // Security
    if (isset($this->pObj->piVars['indexBrowserTab']))
    {
      $this->pObj->piVars['indexBrowserTab'] = $this->secure_piVar($this->pObj->piVars['indexBrowserTab'], 'string');
    }
    // Security

    // Delete piVar[indexBrowserTab], if it is empty.
    if($this->pObj->piVars['indexBrowserTab'] == '')
    {
      unset($this->pObj->piVars['indexBrowserTab']);
    }
    // Delete piVar[indexBrowserTab], if it is empty.

    // Set the default tab, if there isn't any current tab
    $int_key_defaultTab   = $this->pObj->conf['navigation.']['indexBrowser.']['defaultTab'];
    $arr_defaultTab       = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$int_key_defaultTab.'.']['stdWrap.'];
    $str_defaultTabLabel  = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$int_key_defaultTab];
    $defaultAzTab         = $this->pObj->objWrapper->general_stdWrap($str_defaultTabLabel, $arr_defaultTab);
    if ($this->pObj->piVars['indexBrowserTab'] == $defaultAzTab)
    {
      // Current tab is the default tab
      if ($this->pObj->conf['navigation.']['indexBrowser.']['defaultTab.']['display_in_url'] == 0)
      {
        // It shouldn't be displayed in the real URL path. Delete piVars[indexBrowserTab]
        unset($this->pObj->piVars['indexBrowserTab']);
        if($this->pObj->b_drs_navi)
        {
          t3lib_div::devlog('[INFO/NAVIGATION] tx_browser_pi1[indexBrowserTab] is deleted, because it has the default value \''.$defaultAzTab.'\'', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/NAVIGATION] If you need the value in the real URL path, please configure indexBrowser.defaultTab.realURL = 1.', $this->pObj->extKey, 0);
        }
      }
    }
    // Set the default tab, if there isn't any current tab

    // Set piVars[indexBrowserTab] to default value, if it is empty and indexBrowser.defaultTab.realURL is true
    if (!isset($this->pObj->piVars['indexBrowserTab']))
    {
      if ($this->pObj->conf['navigation.']['indexBrowser.']['defaultTab.']['display_in_url'] == 1)
      {
        $this->pObj->piVars['indexBrowserTab'] = $defaultAzTab;
      }
    }
    // Set piVars[indexBrowserTab] to default value, if it is empty and indexBrowser.defaultTab.realURL is true

    // Delete piVar[indexBrowserTab], if we don't have any index browser.
    if($this->pObj->objFlexform->bool_indexBrowser == 0)
    {
      unset($this->pObj->piVars['indexBrowserTab']);
    }
    // Delete piVar[indexBrowserTab], if we don't have any index browser.

    // Set the global $piVar_indexBrowserTab
    if (isset($this->pObj->piVars['indexBrowserTab']))
    {
      $this->pObj->piVar_indexBrowserTab = $this->pObj->piVars['indexBrowserTab'];
    }
    if (!isset($this->pObj->piVars['indexBrowserTab']))
    {
      $this->pObj->piVar_indexBrowserTab = $defaultAzTab;
    }
    // Set the global $piVar_indexBrowserTab
    // Default Index-Browser tab


    //////////////////////////////////////
    //
    // pi_isOnlyFields

    $this->pObj->pi_isOnlyFields  = $this->pObj->pi_isOnlyFields.','.implode(',', array_keys($this->pObj->piVars));
    $arrPiOnlyFields              = explode(',', $this->pObj->pi_isOnlyFields);
    $arrPiOnlyFields              = array_unique($arrPiOnlyFields);
    $this->pObj->pi_isOnlyFields  = implode(',', $arrPiOnlyFields);


    // Delete piVar[indexBrowserTab], if it is empty.
    if($this->pObj->piVars['indexBrowserTab'] == '')
    {
      unset($this->pObj->piVars['indexBrowserTab']);
    }
    // Delete piVar[indexBrowserTab], if it is empty.


    //////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_navi)
    {
      $str_prompt = false;
      foreach((array) $this->pObj->piVars as $keyPiVar => $valuePiVar)
      {
        if (is_array($valuePiVar))
        {
          $str_value  = implode(', ', $valuePiVar);
        }
        if (!is_array($valuePiVar))
        {
          $str_value  = $valuePiVar;
        }
        $str_prompt = $str_prompt.'piVars['.$keyPiVar.'] = '.$str_value.'<br />';
      }
      if ($str_prompt)
      {
        t3lib_div::devlog('[INFO/NAVIGATION] piVars:<br />'.$str_prompt, $this->pObj->extKey, 0);
      }
    }
    // DRS - Development Reporting System

//// 4.1.8
//$this->pObj->objNaviRecordBrowser->mode = ( int ) $this->pObj->piVar_mode; 
//$this->pObj->dev_var_dump( $this->pObj->objNaviRecordBrowser->recordbrowser_get_piVars_as_params( ) );

  }













// 100709, frank.sander (new function removeFiltersFromPiVars)
/**
 * Remove all filter entries off the PiVars array.
 *
 * @param	array		$inputPiVars: current piVars
 * @param	array		$filterConf: TypoScript filter configuration array
 * @return	array		The modified piVars Array (without filters now)
 * @author    Frank Sander
 * @version   3.4.2
 * @internal  Suggestion #9495
 */
  function removeFiltersFromPiVars($inputPiVars, $filterConf)
  {
    // Get the filter fields
    if(is_array($filterConf) && is_array($inputPiVars))
    {
      foreach((array) $filterConf as $tableWiDot => $arrFields)
      {
        foreach((array) $arrFields as $fieldWiWoDot => $dummy)
        {
         if(substr($fieldWiWoDot, -1) != '.')
         {
           $arr_tableFilter[] = $tableWiDot.$fieldWiWoDot;
         }
        }
      }
    }
    // Get the filter fields

    // Remove the filter fields temporarily
    if(is_array($arr_tableFilter))
    {
      $outputPiVars = array_diff_key($inputPiVars, array_flip($arr_tableFilter));
    }
    // Remove the filter fields temporarily

    // RETURN false in case of any piVar
    if(count($outputPiVars) == 0)
    {
      return (false);
    }
    // RETURN false in case of any piVar

    return $outputPiVars;
  }













    /**
 * advanced_remove_piVars():  Method is controlled by TypoSCript advanced.realUrl.linkToSingle.dont_display_piVars
 *                            Original piVars will stored in the global $this->piVars
 *
 * @param	boolean		$keepFilters
 * @return	void
 * @version   3.9.13
 * @internal  Suggestion #9495 by Frank Sander
 */
  function advanced_remove_piVars($keepFilters=0)
  {
    // #9495, fsander
    //function advanced_remove_piVars()
    static $bool_firsttime = true;

    $conf = $this->pObj->conf;
    // #9495, fsander
    $mode       = $this->pObj->piVar_mode;
    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    // #9495, fsander



    ///////////////////////////////////////////////////////////
    //
    // Should we process dont_display_piVars?

    $pageBrowserPointerLabel = $this->conf['navigation.']['pageBrowser.']['pointer'];

    $arr_rmPiVars = false;
    $arr_noPiVars['indexBrowserTab']        = ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_indexBrowserTab;
    $arr_noPiVars['mode']                   = ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_mode;
    $arr_noPiVars[$pageBrowserPointerLabel] = ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_pointer;
    $arr_noPiVars['plugin']                 = ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin;
    $arr_noPiVars['sort']                   = ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_sort;
    $arr_noPiVars['sword']                  = ! $this->pObj->objFlexform->bool_searchForm_wiColoredSwordsSingle;

      // Do we have an array with piVar keys?
    foreach( ( array ) $arr_noPiVars as $key => $value )
    {
      if ($value && isset($this->pObj->piVars[$key]))
      {
        $arr_rmPiVars[$key] = $value;
      }
    }
    // Do we have an array with piVar keys?

    // We have an array with piVar keys, which shouldn't displayed?
    if (is_array($arr_rmPiVars))
    {
      $this->tmp_piVars = $this->pObj->piVars;
      foreach ($arr_rmPiVars as $key => $value)
      {
        unset($this->pObj->piVars[$key]);
      }
    }
    // We have an array with piVar keys, which shouldn't displayed?



    ///////////////////////////////////////////////////////////
    //
    // Remove the filter fields temporarily, if not denied
    // #9495, fsander

    if (!$keepFilters)
    {
      $this->pObj->piVars = $this->pObj->objZz->removeFiltersFromPiVars($this->pObj->piVars, $conf_view['filter.']);
    }
    // Remove the filter fields temporarily, if not denied



    ///////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_templating && $bool_firsttime)
    {
      if (is_array($arr_rmPiVars))
      {
        $str_prompt = implode('], piVars[', array_keys($arr_rmPiVars));
        $str_prompt = 'piVars['.$str_prompt.']';
        t3lib_div::devLog('[INFO/TEMPLATING] advanced.realUrl.linkToSingle.dont_display_piVars is TRUE<br />
          The array dont_display_piVars has piVars, which shouldn\'t displayed in a single view.<br />
          <br />
          Temporarily removed: '.$str_prompt.'.', $this->pObj->extKey, 0);
      }
    }
    $bool_firsttime = false;
    // DRS - Development Reporting System

  }













  /**
 * advanced_remove_piVars_filter: Remove piVars, which are set by filter
 *                                Bugfix #8368
 *
 * @return	void
 */
    function advanced_remove_piVars_filter()
    {
      static $bool_firstCall = true;

      ///////////////////////////////////////////////////////////
      //
      // RETURN if we have a second call

      if(!$bool_firstCall)
      {
        return;
      }
      // RETURN if we have a second call



      $bool_firstCall = false;

      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;

      $view       = $this->pObj->view;
      $viewWiDot  = $view.'.';
      $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];



      ///////////////////////////////////////////////////////////
      //
      // RETURN if there isn't any filter

      if(empty($conf_view['filter.']))
      {
        return;
      }
      // RETURN if there isn't any filter



      ///////////////////////////////////////////////////////////
      //
      // Remove filter piVars

      $arr_prompt = null;
      foreach((array) $conf_view['filter.'] as $tableWiDot => $arr_fields)
      {
        foreach((array) $arr_fields as $fieldWiDot => $arr_field)
        {
          if(substr($fieldWiDot, -1) == '.')
          {
            $field      = substr($fieldWiDot, 0, -1);
            $tableField = $tableWiDot.$field;
            if(isset($this->pObj->piVars[$tableField]))
            {
              unset($this->pObj->piVars[$tableField]);
              $arr_rmPiVars[] = $tableField;
            }
          }
        }
      }
      // Remove filter piVars



      ///////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

      if ($this->pObj->b_drs_templating)
      {
        if (!empty($arr_rmPiVars))
        {
          $str_prompt = implode('], piVars[', array_keys($arr_rmPiVars));
          $str_prompt = 'piVars['.$str_prompt.']';
          t3lib_div::devLog('[INFO/FILTER] filter values are removed from links to single views<br />
            <br />
            removed: '.$str_prompt.'.', $this->pObj->extKey, 0);
        }
      }
      // DRS - Development Reporting System

    }


































    /***********************************************
    *
    * $GLOBAL markers
    *
    **********************************************/

    /**
 * Returns the value for a $GLOBALS marker
 *
 * @param	string		Marker: The TSFE marker like ###TSFE:fe_user|enablecolumns|deleted###
 * @return	string		The value from the TSFE array
 */
  function get_t3globals_value($marker)
  {

    //////////////////////////////////////////////////////////////////////
    //
    // Get $GLOBAL['xxx']

    $marker     = str_replace('###', '', $marker);            // ###TSFE:fe_user|enablecolumns|deleted###
    $arr_marker = explode(':', $marker);                      // TSFE:fe_user|enablecolumns|deleted
    $arr_t3globals   = explode('|', $arr_marker[1]);          // fe_user|user|username

    $arr_curr_level     = $GLOBALS[$arr_marker[0]];           // $GLOBAL['TSFE']
    $str_globals_prompt = '$GLOBALS['.$arr_marker[0].']';
    // Get $GLOBAL['xxx']


    //////////////////////////////////////////////////////////////////////
    //
    // Loop through the $GLOBAL['xxx'] array

    $arr_result = false;
    foreach((array) $arr_t3globals as $arr_t3globals_array)
    {
      unset($arr_result);
      $bool_error = true;
      if (is_object($arr_curr_level))
      {
        $arr_next_level     = $arr_curr_level->$arr_t3globals_array;
        $str_globals_prompt = $str_globals_prompt.'->'.$arr_t3globals_array;
        $bool_error = false;
      }
      if (is_array($arr_curr_level))
      {
        $arr_next_level = $arr_curr_level[$arr_t3globals_array];
        $str_globals_prompt = $str_globals_prompt.'['.$arr_t3globals_array.']';
        $bool_error = false;
      }
      if($bool_error)
      {
        if ($this->pObj->b_drs_ttc)
        {
          $str_globals_prompt = $str_globals_prompt.'???'.$arr_t3globals_array;
          t3lib_div::devlog('[WARN/TYPOSCRIPT] '.$str_globals_prompt.' is neither an object nor an array!', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/TYPOSCRIPT] Please edit Your TypoScript:<br />'.
            '###'.$marker.'###', $this->pObj->extKey, 1);
        }
      }
      unset($arr_curr_level);
      $arr_curr_level = $arr_next_level;
      unset($arr_next_level);
    }
    // Loop through the $GLOBAL['TSFE'] array

    return $arr_curr_level;

  }









  /**
 * Get access to the TYPO3 $GLOBALS array: Replace all markers in a multi-dimensional array like an TypoScript array
 * with the values from the $GLOBALS array.
 * Syntax for $GLOBALS markers is: ###$GLOBALS KEY:element_firstLevel|element_secondLevel|...###
 * I.e:                            ###TSFE:fe_user|enablecolumns|deleted###
 *
 * @param	array		$arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @return	array		$arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 */
  function substitute_t3globals_recurs($arr_multi_dimensional)
  {
    $conf       = $this->pObj->conf;
    $conf_view  = $this->pObj->conf['views.'][$this->pObj->view.'.'][$this->pObj->piVar_mode.'.'];


    ////////////////////////////////////////////////
    //
    // RETURN, if marker with $Global keys should not replaced

    #10116
    $arr_conf_advanced = $conf['advanced.'];
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }

    $bool_dontReplace = $arr_conf_advanced['performance.']['GLOBALS.']['dont_replace'];
    if($bool_dontReplace)
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/TYPOSCRIPT] [advanced.][performance.][GLOBALS.][dont_replace] is TRUE.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TTC] If you are using markers like ###TSFE:fe_user|...### you should set it to false.<br />'.
          'Be aware that the configuration in the plugin has priority.', $this->pObj->extKey, 1);
      }
      return $arr_multi_dimensional;
    }
    // RETURN, if marker with $Global keys should not replaced


    ////////////////////////////////////////////////
    //
    // Security: recursionGuard

    static $int_levelRecurs = 0;
    $int_levelRecursMax         = (int) $arr_conf_advanced['recursionGuard'];
    $int_levelRecurs++;
    if ($int_levelRecurs > $int_levelRecursMax)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] Recursion is bigger than '.$int_levelRecursMax, $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/TTC] If it is ok, please increase advanced.recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/TTC] EXIT', $this->pObj->extKey, 3);
      }
      exit;
    }
    // Security: recursionGuard


    ////////////////////////////////////////////////
    //
    // Get all keys from $GLOBALS

    if($int_levelRecurs < 2)
    {
      $this->arr_t3global_keys = array_keys($GLOBALS);
    }
    // Get all keys from $GLOBALS


    ////////////////////////////////////////////////
    //
    // Loop through the current level of the multi-dimensional array

    foreach((array) $arr_multi_dimensional as $key_arr_curr => $value_arr_curr)
    {
      // The current TypoScript element is an array
      if (is_array($value_arr_curr))
      {
        // Loop through the next level of the multi-dimensional array (recursive)
        $arr_multi_dimensional[$key_arr_curr] = $this->substitute_t3globals_recurs($value_arr_curr);

      }
      // The current TypoScript element is an array


      // The current TypoScript element is a value
      // Replace markers with a $GLOBALS key with the value from the $GLOBALS element
      if(!is_array($value_arr_curr))
      {
        // Loop through all keys of $GLOBALS (We had 77 keys in the November of 2009! TYPO3 4.2.9)
        foreach((array) $this->arr_t3global_keys as $key_t3global)
        {
          // Do we have a marker with a $GLOBALS key?
          $bool_t3globals  = false;
          $int_marker = substr_count($value_arr_curr, '###'.$key_t3global.':');
          if ($int_marker > 0)
          {
            $bool_t3globals = true;
          }
          // Do we have a marker with a $GLOBALS key?

          // We have a marker with a $GLOBALS key
          if ($bool_t3globals)
          {
            $str_value_after_loop = $value_arr_curr;
            $bool_marker          = false;
            $arr_t3globals_marker = explode('###'.$key_t3global.':', $value_arr_curr);
            // We have at least two elements and the first isn't in any case the second part of the marker
            unset($arr_t3globals_marker[0]);
            // Remove the first element

            // Loop through all markers with a $GLOBALS key
            foreach((array) $arr_t3globals_marker as $str_marker_and_more)
            {
              $arr_marker           = explode('###', $str_marker_and_more);
              $key_marker           = '###'.$key_t3global.':'.$arr_marker[0].'###';
              $value_marker         = $this->get_t3globals_value($key_marker);
              $str_tmp_value        = $str_value_after_loop;
              $str_value_after_loop = str_replace($key_marker, $value_marker, $str_value_after_loop);
              if ($str_tmp_value != $str_value_after_loop)
              {
                $bool_marker = true;
              }
            }
            // Loop through all markers with a $GLOBALS key

            // Value has changed
            if ($bool_marker)
            {
              if ($this->pObj->b_drs_ttc)
              {
                t3lib_div::devlog('[INFO/TYPOSCRIPT] ... ['.$key_arr_curr.']: '.$value_arr_curr.'<br />'.
                                  '... became .......... ['.$key_arr_curr.']: '.$str_value_after_loop, $this->pObj->extKey, 0);
              }
              $value_arr_curr   = $str_value_after_loop;
            }
            // Value has changed
          }
          // We have a marker with a $GLOBALS key
          $arr_multi_dimensional[$key_arr_curr] = $value_arr_curr;
        }
        // Loop through all keys of $GLOBALS
      }
      // Replace markers with a $GLOBALS key with the value from the $GLOBALS element
      // The current TypoScript element is a value
    }
    // Loop through the current level of the multi-dimensional array
    return $arr_multi_dimensional;
  }









    /***********************************************
    *
    * CSV process and format time
    *
    **********************************************/



    /**
 * Returns a comma seperated list as an array of elements.
 *
 * @param	string		Comma seperated list of values
 * @return	array		The array with the values
 */
  function getCSVasArray($csvValues)
  {
    $tmpArrCSV = explode(',', $csvValues);
    foreach((array) $tmpArrCSV as $valueCSV) {
      $arrCSV[] = $this->cleanUp_lfCr_doubleSpace($valueCSV);
    }
    return $arrCSV;
  }


    /**
 * Returns a comma seperated list of table.field as an array of elements.
 *
 * @param	string		Comma seperated list of values in this format: table.field
 * @return	array		The array with the table.field values
 */
  function getCSVtablefieldsAsArray($csvTableFields)
  {
    $tmpArrCSV = explode(',', $csvTableFields);
    foreach((array) $tmpArrCSV as $valueCSV) {
      list($table, $field) = explode('.', trim($csvTableFields));
      $tableField = $table.'.'.$field;
      $arrCSV[] = $this->cleanUp_lfCr_doubleSpace($tableField);
    }
    return $arrCSV;
  }


    /**
 * Removes linefeed, carriage returns and double spaces form a string.
 * We need it for TypoScript, if a user is using () instead of =
 *
 * @param	string		Comma seperated list of values
 * @return	array		Cleaned up comma seperated list of values
 * @version 3.9.3
 * @since 1.0.0
 */
  public function cleanUp_lfCr_doubleSpace($csvValue)
  {
    $csvValue = str_replace( chr( 10 ), '', $csvValue ); // Linefeed
    $csvValue = str_replace( chr( 13 ), '', $csvValue ); // Carriage return

    //$int_levelRecursMax = $this->int_advanced_recursionGard;
    $int_levelRecurs = 0;
    do
    {
      $csvValue = str_replace( '  ', ' ', $csvValue );
      $int_levelRecurs++;
    }
    //while (!(strpos($csvValue, '  ') === false) && ($int_levelRecurs < $int_levelRecursMax));
    while ( ! ( strpos( $csvValue, '  ' ) === false ) );
    $csvValue = trim( $csvValue );
    return $csvValue;
  }


    /**
 * Sets the human readable format for timestamps in the global var $tsStrftime.
 *
 * @return	string		The format for a local date/time
 */
  function setTsStrftime()
  {
    $conf = $this->pObj->conf;

    $view = $this->pObj->view;
    $mode = $this->pObj->piVar_mode;

    $viewWiDot      = $view.'.';
    $str_tsStrftime = '';

    if ($conf['views.'][$view.'.'][$mode.'.']['format.']['strftime'] != '')
    {
      $str_tsStrftime = $conf['views.'][$view.'.'][$mode.'.']['strftime.']['date'];
      // Local format for human readable timestamp
    }
    else
    {
      $str_tsStrftime = $conf['format.']['strftime'];
      // Global format
    }
    return $str_tsStrftime;
  }








  /***********************************************
  *
  * Link
  *
  **********************************************/



  /**
 * Link string to the current page.
 * Returns the $str wrapped in <a>-tags with a link to the CURRENT page, but with $urlParameters set as extra parameters for the page.
 *
 * @param	string		The content string to wrap in <a> tags
 * @param	array		Typolink array
 * @param	array		Array with URL parameters as key/value pairs. They will be "imploded" and added to the list of parameters defined in the plugins TypoScript property "parent.addParams" plus $this->pi_moreParams.
 * @param	boolean		If $cache is set (0/1), the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
 * @return	string		The input string wrapped in <a> tags
 * @see pi_linkTP_keepPIvars(), tslib_cObj::typoLink()
 */
  function linkTP($str, $typolink=array(), $urlParameters=array(), $cache=0, $altPageId=0)
  {
    // Based on class.tslib_pibase.php::pi_linkTP: We added params $typolink

    $typolink['useCacheHash'] = $this->pObj->pi_USER_INT_obj ? 0 : $cache;
    $typolink['no_cache']     = $this->pObj->pi_USER_INT_obj ? 0 : !$cache;

    if(!$typolink['parameter'])
    {
      $tmpPageId              = $this->pObj->pi_tmpPageId;
      $typolink['parameter']  = $altPageId ? $altPageId : ($tmpPageId ? $tmpPageId : $GLOBALS['TSFE']->id);
    }
    $typolink['additionalParams'] =
      $typolink['parent.']['addParams'].t3lib_div::implodeArrayForUrl('',$urlParameters,'',1).$this->pObj->pi_moreParams;
    $str_typolink = $this->pObj->cObj->typoLink($str, $typolink);
    return $str_typolink;
  }




     /**
 * Link a string to the current page while keeping currently set values in piVars.
 * Like pi_linkTP, but $urlParameters is by default set to $this->piVars with $overrulePIvars overlaid.
 * This means any current entries from this->piVars are passed on (except the key "DATA" which will be unset before!) and entries in $overrulePIvars will OVERRULE the current in the link.
 *
 * @param	string		The content string to wrap in <a> tags
 * @param	array		Typolink array
 * @param	array		Array of values to override in the current piVars. Contrary to pi_linkTP the keys in this array must correspond to the real piVars array and therefore NOT be prefixed with the $this->pObj->prefixId string. Further, if a value is a blank string it means the piVar key will not be a part of the link (unset)
 * @param	boolean		If $cache is set, the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
 * @param	boolean		If set, then the current values of piVars will NOT be preserved anyways... Practical if you want an easy way to set piVars without having to worry about the prefix, "tx_xxxxx[]"
 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
 * @return	string		The input string wrapped in <a> tags
 * @see linkTP()
 */
  function linkTP_keepPIvars($str, $typolink=array(), $overrulePIvars=array(), $cache=0, $clearAnyway=0, $altPageId=0)
  {
    // Based on class.tslib_pibase.php::pi_linkTP: We added params $typolink

    $piVars = $this->pObj->piVars;

    if (is_array($piVars) && is_array($overrulePIvars) && !$clearAnyway)
    {
      unset($piVars['DATA']);
      $overrulePIvars = t3lib_div::array_merge_recursive_overrule($piVars, $overrulePIvars);
      if ($this->pObj->pi_autoCacheEn)
      {
        $cache = $this->pObj->pi_autoCache($overrulePIvars);
      }
    }
    $res = $this->linkTP($str, $typolink, array($this->pObj->prefixId=>$overrulePIvars), $cache, $altPageId);
    return $res;
  }










  /**
 * get_absUrl: Get the absolute URL path
 *
 * @param	string		$str_relUrl: relative URL path
 * @return	string		$str_absUrl: Absolute URL path
 */
  function get_absUrl($str_relUrl)
  {
    $str_site_url = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
    $str_absUrl   = $str_site_url.$str_relUrl;

    // Prevent simulateStatic bug
    $tmp_site_url = substr($str_relUrl, 0, strlen($str_site_url));
    if ($tmp_site_url == $str_site_url)
    {
      // $str_relUrl got a full qualified URL
      $str_absUrl = $str_relUrl;
    }
    if ($tmp_site_url != $str_site_url)
    {
      $str_absUrl = $str_site_url.$str_relUrl;
    }
    // Prevent simulateStatic bug

    return $str_absUrl;
  }










  /**
 * get_singlePid_for_listview: The singlePid for links in list views
 *
 * @return	integer		$singlePid: uid
 */
  function get_singlePid_for_listview()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


    $singlePid = false;
    // Get the page id of the page with the single view from the local value
    if(isset($conf_view['displayList.']['singlePid']))
    {
      $singlePid = $conf_view['displayList.']['singlePid'];
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] views.'.$viewWiDot.$mode.'.displayList.singlePid: '.$singlePid, $this->pObj->extKey, 0);
      }
    }
    // Get the page id of the page with the single view from the local value
    // Get the page id of the page with the single view from the global value
    if(!$singlePid) {
      $singlePid = $conf['displayList.']['singlePid'];
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] displayList.singlePid: '.$singlePid, $this->pObj->extKey, 0);
      }
    }
    // Get the page id of the page with the single view from the global value
    // Get id of the current page
    if(!$singlePid)
    {
      $singlePid = $GLOBALS['TSFE']->id;
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] singlePid get value from $GLOBALS[TSFE]->id: '.$singlePid, $this->pObj->extKey, 0);
      }
    }
    // Get id of the current page
    $singlePid = intval($singlePid);

    return $singlePid;
  }










  /**
 * Calculate the cHash md5 value
 *
 * @param	string		$str_params: URL parameter string like &tx_browser_pi1[showUid]=12&&tx_browser_pi1[cat]=1
 * @return	string		$cHash_md5: md5 value like d218cfedf9
 */
  function get_cHash($str_params)
  {
    $cHash_array  = t3lib_div::cHashParams($str_params);
    $cHash_md5    = t3lib_div::shortMD5(serialize($cHash_array));

    return $cHash_md5;
  }










  /**
 * get_pathWoEXT: Delivers a proper relative path, if path has an EXT: prefix
 *
 * @param	string		$str_TYPO3_EXT_path: With or without EXT: prefix
 * @return	string		$str_TYPO3_EXT_path: Proper relative path
 */
  function get_pathWoEXT($str_TYPO3_EXT_path)
  {
    if ($str_TYPO3_EXT_path && substr($str_TYPO3_EXT_path, 0, 4) == 'EXT:')
    {
      $str_path_wo_EXT = substr($str_TYPO3_EXT_path, 4);
      list($str_extKey, $str_path) = explode('/', $str_path_wo_EXT, 2);
      $str_extKey = strtolower($str_extKey);
      if ($str_extKey == $this->pObj->extKey || t3lib_extMgm::isLoaded($str_extKey))
      {
        $str_TYPO3_EXT_path = t3lib_extMgm::siteRelPath($str_extKey).$str_path;
      }
    }
    return $str_TYPO3_EXT_path;
  }



















    /***********************************************
    *
    * Markers
    *
    **********************************************/







  /**
 * Replace all markers in a multi-dimensional array like an TypoScript array with the real values from the SQL result
 * The method extends the SQL result with all piVar values
 *
 * @param	array		$arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param	array		$elements: The current row of the SQL result
 * @return	array		$arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 */
  function extend_marker_wi_pivars($markerArray)
  {

    /////////////////////////////////////
    //
    // Add to the marker array the piVars

    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      $markerArray['###'.strtoupper($key_pivar).'###'] = $value_pivar;
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
      }
    }

    return $markerArray;
  }










    /***********************************************
   *
   * TypoScript children records
   *
   **********************************************/



  /**
 * children_tsconf_recurs:
 *
 * @param	integer		$key: key of the current child in the string with childrens
 * @param	array		$arr_multi_dimensional: Multi-dimensional TypoScript array
 * @param	string		$str_devider: The devider of the childrens in the current string
 * @return	array		$arr_multi_dimensional: A proper TypoScript array for the current child
 */
  function children_tsconf_recurs($key, $arr_multi_dimensional, $str_devider)
  {
    $conf = $this->pObj->conf;



    ////////////////////////////////////////////////
    //
    // Security: recursionGuard

    static $int_levelRecurs = 0;

    $int_levelRecursMax = $this->int_advanced_recursionGard;
    $int_levelRecurs++;
    if ($int_levelRecurs > $int_levelRecursMax)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] Recursion is bigger than '.$int_levelRecursMax, $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/TTC] If it is ok, please increase advanced.recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/TTC] EXIT', $this->pObj->extKey, 3);
      }
      exit;
    }
    // Security: recursionGuard



    ////////////////////////////////////////////////
    //
    // Loop through the current level of the multi-dimensional array

    foreach((array) $arr_multi_dimensional as $key_arr_curr => $value_arr_curr)
    {
      // The current TypoScript element is an array
      if (is_array($value_arr_curr))
      {
        // Loop through the next level of the multi-dimensional array (recursive)
        $arr_multi_dimensional[$key_arr_curr] = $this->children_tsconf_recurs($key, $value_arr_curr, $str_devider);
      }
      // The current TypoScript element is an array

      // The current TypoScript element is a value
      if(!is_array($value_arr_curr))
      {
        // We have a value with the workflow devider
        if (!(strpos($value_arr_curr, $str_devider) === false))
        {
          // Remove all non current children from the value
          $arr_values                           = explode($str_devider, $value_arr_curr);
          $value_arr_curr_new                   = $arr_values[$key];
          $arr_multi_dimensional[$key_arr_curr] = $value_arr_curr_new;
          // Remove all non current children from the value
        }
        // We have a value with the workflow devider
      }
      // The current TypoScript element is a value
    }
    // Loop through the current level of the multi-dimensional array



    return $arr_multi_dimensional;
  }














    /***********************************************
   *
   * Languages, _LOCAL_LANG
   *
   **********************************************/



  /**
 * Returns the label for a fieldname from local language array. First it tries to get a llValue out of the _local_lang, if it failed, take a look in the TCA.
 *
 * @param	string		Fieldname in the _LOCAL_LANG array or the locallang.xml
 * @return	string		Return the translated label in case of success. Otherwise the given table.field
 */
  function getTableFieldLL($tableField) {

    ////////////////////////////////////
    //
    // _LOCAL_LANG

    // We don't like the dots between a SQL table and field, i.e: maintable.title should be maintable_title
    $tableFieldWoDot = str_replace('.', '_', $tableField);
    $llFieldName = $this->pObj->pi_getLL($tableFieldWoDot, '['.$tableFieldWoDot.']');
    if($llFieldName != '['.$tableFieldWoDot.']') {
      return $llFieldName;
    }


    //////////////////////////////////
    //
    // Process string with AS and alias

    $tableField     = $this->pObj->objSqlFun_3x->get_sql_alias_behind($tableField);
    $arrColumns[0]  = $tableField;
    $arrColumns     = $this->pObj->objSqlFun_3x->replace_tablealias($arrColumns);
    $tableField     = $arrColumns[0];


    ////////////////////////////////////
    //
    // TCA

    if($this->pObj->b_drs_localisation)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] Label for '.$tableField.': try the TCA.', $this->pObj->extKey, 0);
    }

    list($table, $field) = explode('.', $tableField);
    if (!is_array($GLOBALS['TCA'][$table]['columns']))
    {
      t3lib_div::loadTCA($table);
      if ($this->pObj->b_drs_tca)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] $GLOBALS[\'TCA\'][\''.$table.'\'] is loaded.', $this->pObj->extKey, 0);
      }
    }
    if($GLOBALS['TCA'][$table]['columns'][$field]['label'])
    {
      if(!$this->pObj->lang)
      {
        $this->initLang();
      }
      $langKey = $GLOBALS['TSFE']->lang;
      if($langKey == 'en') $langKey = 'default';

      $llFieldName = $this->pObj->lang->sL($GLOBALS['TCA'][$table]['columns'][$field]['label']);
      if($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Label is: '.$llFieldName, $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/LOCALLANG] If you want another label, '.
          'please configure _LOCAL_LANG.'.$langKey.'.'.$tableFieldWoDot, $this->pObj->extKey, 1);
      }
      return $llFieldName;
    }
    else
    {
      if($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[WARN/LOCALLANG] We didn\'t get a value from the TCA.', $this->pObj->extKey, 2);
      }
    }

    ////////////////////////////////////
    //
    // There wasn't any success

    if(!$this->pObj->lang) $this->initLang();
    $langKey = $GLOBALS['TSFE']->lang;
    if($langKey == 'en') $langKey = 'default';

    if($this->pObj->b_drs_localisation)
    {
      t3lib_div::devlog('[HELP/LOCALLANG] Please configure _LOCAL_LANG.'.$langKey.'.'.$tableFieldWoDot, $this->pObj->extKey, 1);
    }

    return $tableField;
  }



  /**
 * Inits the class 'language'
 *
 * @param	string		Fieldname in the _LOCAL_LANG array or the locallang.xml
 * @return	void
 */
  function initLang()
  {
    require_once(PATH_typo3.'sysext/lang/lang.php');
    $this->pObj->lang = t3lib_div::makeInstance('language');
    $this->pObj->lang->init($GLOBALS['TSFE']->lang);
    if($this->pObj->b_drs_localisation)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] Init a language object.', $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/LOCALISATION] Value of $GLOBALS[TSFE]->lang :'.$GLOBALS['TSFE']->lang, $this->pObj->extKey, 0);
    }
  }

















    /***********************************************
    *
    * Sword and Search respectively
    *
    **********************************************/



    /**
 * Returns an array with search values out of the given search phrase.
 * Example for a phrase: "Dirk Wildt" Pressesprecher Berlin
 * This will return the elements: Dirk Wildt, Pressesprecher, Berlin
 *
 * @param	string		$str_search_phrase: piVar value
 * @return	array		search values
 */
  function search_values($str_sword_phrase)
  {
    $conf_sword           = $this->arr_advanced_securitySword;
    $lSearchform          = $this->conf['displayList.']['display.']['searchform.'];
    $int_minLen           = $conf_sword['minLenWord'];
    $csv_swordAddSlashes  = $conf_sword['addSlashes.']['csvChars'];
      // Example phrase: Helmut und Schmidt und Bundeskanzler nicht Entertainer "Helmut Kohl"


      /////////////////////////////////////////////////////////
      //
      // RETURN, if there isn't any sword

    if (!$str_sword_phrase)
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_search)
      {
        t3lib_div::devlog('[INFO/SEARCH] There is no search phrase.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN, if there isn't any sword



      /////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] Searchphrase is \''.$str_sword_phrase.'\'', $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/SEARCH] Searchphrase is \''.rawurldecode($str_sword_phrase).'\'', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System



      /////////////////////////////////////////////////////////
      //
      // Get words for the SQL operators AND, OR and NOT

    $conf_and = $lSearchform['and.'];
    $arr_sql_operator['and']  = $this->pObj->objWrapper->general_stdWrap($conf_and['value'], $conf_and);
    if(!$arr_sql_operator['and'])
    {
      $arr_sql_operator['and'] = 'and';
    }
    $arr_sql_operator['and'] = ' '.$arr_sql_operator['and'].' ';
    $arr_sql_operator_remove['and'] = ' ';
    $conf_or = $lSearchform['or.'];
    $arr_sql_operator['or']  = $this->pObj->objWrapper->general_stdWrap($conf_or['value'], $conf_or);
    if(!$arr_sql_operator['or'])
    {
      $arr_sql_operator['or'] = 'or';
    }
    $arr_sql_operator['or'] = ' '.$arr_sql_operator['or'].' ';
    $arr_sql_operator_remove['or'] = ' ';
    $conf_not = $lSearchform['not.'];
    $arr_sql_operator['not']  = $this->pObj->objWrapper->general_stdWrap($conf_not['value'], $conf_not);
    if(!$arr_sql_operator['not'])
    {
      $arr_sql_operator['not'] = 'not';
    }
    $arr_sql_operator['not'] = ' '.$arr_sql_operator['not'].' ';
    $arr_sql_operator_remove['not'] = ' ';
      // Get words for the SQL operators AND, OR and NOT



      //////////////////////////////////////////////////
      //
      // Preparation for investigating quotes

    $arr_swords_exploded = array(); // Array with search words
    $arr_swords_marker   = array(); // Array with every search word or search phrase and its marker like $0, $1
    $arr_swords_toShort  = array(); // Array with words which are to short
    $arr_swords_quoted   = explode('\\"', $str_sword_phrase);
      // Preparation for investigating quotes



      //////////////////////////////////////////////////
      //
      // Workflow for search words without any quotes

    $key = 0;
    if (count($arr_swords_quoted) == 1)
    {
      $arr_swords_old[] = explode(' ', $arr_swords_quoted[$key]);
        // Get current search words or phrase
      $str_search_phrase     = $arr_swords_quoted[$key];
        // Remove all SQL operators
      $str_search_phrase     = str_replace($arr_sql_operator, $arr_sql_operator_remove, $str_search_phrase);
        // Remove unnecessary spaces
      $str_search_phrase     = preg_replace('/\s\s+/', ' ', $str_search_phrase);
      $arr_exploded          = explode(' ', $str_search_phrase);

        // Remove words which are to short
      foreach((array) $arr_exploded as $key => $value)
      {
        if (strlen($value) < $int_minLen)
        {
          if($value)
          {
            $arr_swords_toShort[] = $value;
          }
          unset($arr_exploded[$key]);
        }
      }
        // Remove words which are to short

      $arr_swords_exploded[] = $arr_exploded;
    }
      // Workflow for search words without any quotes



      //////////////////////////////////////////////////
      //
      // Workflow for search words with quotes

    $int_counter = 0;
    if(count($arr_swords_quoted) > 1)
    {
        // Quoted phrases are stored in even elements
      $bool_odd = false;
      if(substr($str_sword_phrase, 0) == '"')
      {
          // Quoted phrases are stored in odd elements
        $bool_odd = true;
      }

        // Loop through the aray with the quoted and non quoted swords
      $int_counter = 0;
      foreach((array) $arr_swords_quoted as $key => $value)
      {
          // Switch between even und odd elements
        switch($key%2)
        {
          case(false):
              // We have an odd array like [0], [2], [4]
              // We have a quoted sword in every odd element - don't explode it.
            if ($bool_odd && $value)
            {
              $arr_swords_old[][]                  = $arr_swords_quoted[$key];
              $arr_swords_marker['$'.$int_counter++] = $arr_swords_quoted[$key];
            }
              // We have a quoted sword in every odd element - don't explode it.
              // We have a non quoted sword in every even element - explode it.
            if (!$bool_odd && $value)
            {
              $arr_swords_old[]              = explode(' ', $arr_swords_quoted[$key]);
                // Get current search words or phrase
              $str_search_phrase     = $arr_swords_quoted[$key];
                // Remove all SQL operators
              $str_search_phrase     = str_replace($arr_sql_operator, $arr_sql_operator_remove, $str_search_phrase);
                // Remove unnecessary spaces
              $str_search_phrase     = preg_replace('/\s\s+/', ' ', $str_search_phrase);
              $arr_exploded          = explode(' ', $str_search_phrase);
                // Remove words which are to short
              foreach((array) $arr_exploded as $key => $value)
              {
                if (strlen($value) < $int_minLen)
                {
                  if($value)
                  {
                    $arr_swords_toShort[] = $value;
                  }
                  unset($arr_exploded[$key]);
                }
              }
                // Remove words which are to short
              $arr_swords_exploded[] = $arr_exploded;
                //$arr_swords_exploded[]            = explode(' ', $arr_swords_quoted[$key]);
            }
              // We have a non quoted sword in every even element - explode it.
            break;
          case(true):
              // We have a even array like [1], [3], [5]
              // We have a quoted sword in every even element - don't explode it.
            if (!$bool_odd && $value)
            {
              $arr_swords_old[][]                  = $arr_swords_quoted[$key];
              $arr_swords_marker['$'.$int_counter++] = $arr_swords_quoted[$key];
            }
              // We have a quoted sword in every even element - don't explode it.
              // We have a non quoted sword in every odd element - explode it.
            if ($bool_odd && $value)
            {
              $arr_swords_old[]              = explode(' ', $arr_swords_quoted[$key]);
                // Get current search words or phrase
              $str_search_phrase     = $arr_swords_quoted[$key];
                // Remove all SQL operators
              $str_search_phrase     = str_replace($arr_sql_operator, $arr_sql_operator_remove, $str_search_phrase);
                // Remove unnecessary spaces
              $str_search_phrase     = preg_replace('/\s\s+/', ' ', $str_search_phrase);
              $arr_exploded          = explode(' ', $str_search_phrase);
                // Remove words which are to short
              foreach((array) $arr_exploded as $key => $value)
              {
                if (strlen($value) < $int_minLen)
                {
                  if($value)
                  {
                    $arr_swords_toShort[] = $value;
                  }
                  unset($arr_exploded[$key]);
                }
              }
                // Remove words which are to short
              $arr_swords_exploded[] = $arr_exploded;
                //$arr_swords_exploded[]            = explode(' ', $arr_swords_quoted[$key]);
            }
              // We have a non quoted sword in every odd element - explode it.
            break;
        }
          // Switch between even und odd elements
      }
        // Loop through the aray with the quoted and non quoted swords
    }
      // Workflow for search words with quotes



      //////////////////////////////////////////////////
      //
      // Extend marker array with search words from the array exploded

    foreach((array) $arr_swords_exploded as $arr_swords_exploded_swords)
    {
      foreach((array) $arr_swords_exploded_swords as $str_exploded)
      {
        switch(strtolower($str_exploded))
        {
          case(false):
          case(''):
          case(trim($arr_sql_operator['and'])):
          case(trim($arr_sql_operator['or'])):
          case(trim($arr_sql_operator['not'])):
            // do nothing;
            break;
          default:
            $arr_swords_marker['$'.$int_counter++] = $str_exploded;
        }
      }
    }

      // Remove non unique search words
    if(is_array($arr_swords_marker))
    {
      $arr_swords_marker = array_unique($arr_swords_marker);
    }
      // Extend marker array with search words from the array exploded



      //////////////////////////////////////////////////
      //
      // Mask the search phrase

    $str_sword_mask = $str_sword_phrase;
      // var_dump('zz 1769', $str_sword_phrase);
      // Helmut und Schmidt und Bundeskanzler nicht Entertainer "Helmut Kohl"
      // Remove all quotes
    $str_sword_mask = str_replace('\\"', false, $str_sword_mask);

      // Replace all search words with a mask
    foreach((array) $arr_swords_marker as $str_mask => $str_sword)
    {
      $str_sword_mask = str_replace($str_sword, $str_mask, $str_sword_mask);
    }
      // $1 und $2 und $3 nicht $4 $0
      // Replace all search words with a mask

      // Remove unnecessary spaces
    $str_sword_mask = preg_replace('/\s\s+/', ' ', $str_sword_mask);
      // $1 und $2 und $3 nicht $4 $0

      // 1. Mask AND and NOT
    $arr_search     = array($arr_sql_operator['and'], $arr_sql_operator['not']);
    $arr_replace    = array('and', 'not');
      // var_dump('zz 1842', $str_sword_mask);
      // $1 und $2 und $3 nicht $4 $0
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] Step 1/2: Mask is \''.$str_sword_mask.'\'', $this->pObj->extKey, 0);
    }
    $str_sword_mask = str_replace($arr_search, $arr_replace, $str_sword_mask);
      // Helmut und Schmidt und       Bundeskanzler nicht Entertainer "Helmut Kohl"
      // $1und$2und$3nicht$4 $0
      // 1. Mask AND and NOT
      // 2. Mask spaces and OR
    $arr_search     = array(' ', $arr_sql_operator['or']);
    $arr_replace    = array('or', 'or');
    $str_sword_mask = str_replace($arr_search, $arr_replace, $str_sword_mask);
      // Helmut und Schmidt und       Bundeskanzler nicht Entertainer "Helmut Kohl" nicht "Harald Schmidt"
      // $1und$2und$3nicht$4oder$0
      // 2. Mask spaces and OR
      // var_dump('zz 1855', $str_sword_mask);
      // $1and$2and$3not$4or$0
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] Step 2/2: Mask is \''.$str_sword_mask.'\'', $this->pObj->extKey, 0);
    }
      // For resultphrase
    $arr_search            = array('and', 'or', 'not');
    $arr_replace           = array($arr_sql_operator['and'], $arr_sql_operator['or'], $arr_sql_operator['not']);
    $str_resultphrase_mask = str_replace($arr_search, $arr_replace, $str_sword_mask);



    $arr_swords = array();

      // Get all NOT
    $str_sword_mask_wo_not = $str_sword_mask;
    foreach((array) $arr_swords_marker as $key => $str_sword)
    {
      $str_search = 'not'.$key;
      $bool_found = strpos($str_sword_mask, $str_search);
      if (!($bool_found === false)) {
        if($str_sword != '')
        {
          $arr_swords['not'][] = $str_sword;
          $str_sword_mask_wo_not = str_replace($str_search, '', $str_sword_mask_wo_not);
        }
      }
    }
      // Get all NOT

      // Get all OR
    $int_counter = 0;
    $arr_or = explode('or', $str_sword_mask_wo_not);
    foreach((array) $arr_or as $key_search_or => $str_search_or)
    {
      if($str_search_or)
      {
          // Get all AND
        $arr_and = explode('and', $str_search_or);
        foreach((array) $arr_and as $str_search_and)
        {
          if($arr_swords_marker[$str_search_and] != '')
          {
            //var_dump('$arr_swords4['.$int_counter.'][] = $arr_swords_marker['.$key.']');
            $arr_swords['or'][$int_counter][] = $arr_swords_marker[$str_search_and];
          }
        }
        $int_counter++;
      }
        // Get all AND
      if(!$str_search_or)
      {
        unset($arr_or[$key_search_or]);
      }
    }
      // Get all OR

      // Get a proper search word phrase
    $arr_search_or = array();
    foreach((array) $arr_swords['or'] as $arr_search_and)
    {
      $str_search_and  = implode('"'.$arr_sql_operator['and'].'"', $arr_search_and);
      $str_search_and  = '"'.$str_search_and.'"';
      $arr_search_or[] = $str_search_and;
    }
    $str_proper_search = implode($arr_sql_operator['or'], $arr_search_or);
    if(is_array($arr_swords['not']))
    {
      $str_search_not    = implode('"'.$arr_sql_operator['not'].'"', $arr_swords['not']);
      $str_search_not    = $arr_sql_operator['not'].'"'.$str_search_not.'"';
      $str_proper_search = $str_proper_search.$str_search_not;
    }
      // var_dump('zz 1886', $str_proper_search);
      // "Helmut" und "Schmidt" und "Bundeskanzler" oder "Helmut Kohl" nicht "Entertainer"
      // Get a proper search word phrase

      //var_dump('zz 1890', $str_sword_phrase, $str_sword_mask, $arr_swords);
      //array["not"][0] => "Entertainer"
      //     ["or"] [0][0] => "Helmut"
      //               [1] => "Schmidt"
      //               [2] => "Bundeskanzler"
      //            [1][0] => "Helmut Kohl"



      //////////////////////////////////////////////////
      //
      // Consolidate wildcards in markers

      // Char for Wildcard
    $chr_wildcard = $this->pObj->str_searchWildcardCharManual;

      // The user has to add a wildcard
    if($this->pObj->bool_searchWildcardsManual)
    {
      foreach((array) $arr_swords_marker as $key => $value)
      {
          // First char of search word is a wildcard
        if($value[0] == $chr_wildcard)
        {
          $value = substr($value, 1, strlen($value) - 1);
          $arr_swords_marker[$key] = $value;
        }
          // First char of search word is a wildcard
          // Last char of search word is a wildcard
        if($value[strlen($value) - 1] == $chr_wildcard)
        {
          $value = substr($value, 0, -1);
          $arr_swords_marker[$key] = $value;
        }
      }
    }
      // The user has to add a wildcard
      // Consolidate wildcards in markers
//var_dump('zz 2013', $arr_swords_marker);


      //////////////////////////////////////////////////
      //
      // RETURN result

    $arr_return['data']['arr_sword']                      = $arr_swords;
    $arr_return['data']['arr_short']                      = $arr_swords_toShort;
    $arr_return['data']['str_sword']                      = $str_proper_search;
    $arr_return['data']['arr_resultphrase']['arr_marker'] = $arr_swords_marker;
    $arr_return['data']['arr_resultphrase']['str_mask']   = $str_resultphrase_mask;
    return $arr_return;
      // RETURN result
  }



/**
 * Colors sword words and phrases. RETURN word and phrase with an HTML wrap. Depending on TypoScript configuration.
 * Example for a phrase: "Dirk Wildt" Pressesprecher Berlin
 * This will return the elements: Dirk Wildt, Pressesprecher, Berlin
 *
 * @param	string		$tableField: Syntax table.field
 * @param	string		$value: Content. Maybe with or maybe without a value like the sword.
 * @return	string		$value: Wrapped swords. Depending on TypoScript configuration.
 */
  function color_swords($tableField, $value)
  {
    /**
     * This method correspondends with tx_browser_pi1_template::resultphrase()
     */

    $view = $this->pObj->view;

      // There isn't any sword. RETURN.
    if (!is_array($this->pObj->arr_swordPhrasesTableField))
    {
  // 3.3.4
  //if(t3lib_div::_GP('dev')) var_dump('zz 2205: RETURN no sword phrase');
      return $value;
    }
      // There isn't any sword. RETURN.

      // Value is an email. RETURN
    if (t3lib_div::validEmail($value))
    {
// 3.3.4
//if(t3lib_div::_GP('dev')) var_dump('zz 2214: RETURN valid mail');
      return $value;
    }
    // Value is an email. RETURN

    $lSearchform = $this->pObj->lDisplay['searchform.'];

      // Don't display any wrapped swords. RETURN.
      //if (!$lSearchform['wrapSwordInResults'])
    if(!$this->pObj->objFlexform->bool_searchForm_wiColoredSwords)
    {
// 3.3.4
//if(t3lib_div::_GP('dev')) var_dump('zz 2225: RETURN bool_searchForm_wiColoredSwords');
      return $value;
    }
      // Don't display any wrapped swords. RETURN.


// 3.3.4
//if(t3lib_div::_GP('dev')) var_dump('zz 2232', $this->pObj->arr_resultphrase);
    if(is_array($this->pObj->arr_resultphrase['arr_colored']))
    {
      foreach((array) $this->pObj->arr_resultphrase['arr_colored'] as $key => $str_colored)
      {
        $str_sword = $this->pObj->arr_resultphrase['arr_marker'][$key];
        $value = str_ireplace($str_sword, $str_colored, $value);
      }
    }

    return $value;
  }


























    /***********************************************
    *
    * Security
    *
    **********************************************/



/**
 * Checks the value of a piVar for security. Get magic quotes, stripslashes, mysql_real_escape_string
 *
 * @param	string		$str_value: piVar value
 * @param	string		$str_type: Type for evaluation like string, integer or boolean
 * @return	string		piVar value
 */
  function secure_piVar($str_value, $str_type)
  {

    $str_value_in         = $str_value;
    $conf_sword           = $this->arr_advanced_securitySword;
    $csv_swordAddSlashes  = $conf_sword['addSlashes.']['csvChars'];


      ////////////////////////////////////
      //
      // Get Magic Quotes

      // PHP/MySQL-Documentation: file:///usr/share/doc/packages/php-doc/html/security.database.sql-injection.html

    if (get_magic_quotes_gpc())
    {
      if (ini_get('magic_quotes_sybase'))
      {
        $str_value = str_replace("''", "'", $str_value);
      }
      else
      {
        $str_value = stripslashes($str_value);
      }
    }
      // Get Magic Quotes



      ////////////////////////////////////
      //
      // mysql_real_escape_string

     // PHP/MySQL-Documentation: file:///usr/share/doc/packages/php-doc/html/function.mysql-real-escape-string.html

    $str_value = mysql_real_escape_string($str_value);
      // mysql_real_escape_string



      ////////////////////////////////////
      //
      // Check Type

    $bool_defined = false;
    $bool_ok      = false;

      // Check Boolean
    if (strtolower($str_type) == 'boolean')
    {
      $bool_defined = true;
      if (strtolower($str_value) == 'false')
      {
        $str_value = 0;
      }
      if (strtolower($str_value) == 'true')
      {
        $str_value = 1;
      }
      $str_value = intval($str_value);
      if ($str_value == 0)
      {
        $bool_ok = true;
      }
      if ($str_value == 1)
      {
        $bool_ok = true;
      }
    }
      // Check Boolean

      // Check Integer
    if (strtolower($str_type) == 'integer')
    {
      $bool_defined = true;
      $str_value    = intval($str_value);
      $bool_ok      = true;
    }
      // Check Integer

      // Check String
    if (strtolower($str_type) == 'string')
    {
      $bool_defined = true;
      $bool_ok      = true;
    }
      // Check String

      // Check Sword
    if (strtolower($str_type) == 'sword')
    {
      $arr_swordAddSlashes = $this->getCSVasArray($csv_swordAddSlashes);
      if (is_array($arr_swordAddSlashes))
      {
        foreach ($arr_swordAddSlashes as $str_char)
        {
          $str_value = str_replace($str_char, '\\'.$str_char, $str_value);
        }
      }
      $bool_defined = true;
      $bool_ok      = true;
    }
      // Check Sword

    if (!$bool_defined)
    {
      $str_header = '<h1>ERROR</h1>';
      $str_prompt = '<p>The Type \''.$str_type.'\' isn\'t defined.<br />
        Function: tx_browser_pi1_zz::secure_piVar()</p>';
      echo $str_header.$str_prompt;
      exit;
    }
      // Check Type

    if($str_value_in != $str_value)
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = 'piVar is moved from \'' . $str_value_in . '\' to \'' . $str_value . '\'';
        t3lib_div::devlog('[WARN/Security] ' . $prompt, $this->pObj->extKey, 2);
      }
    }

    return $str_value;
  }









    /***********************************************
    *
    * TCA
    *
    **********************************************/



/**
 * Load the TCA, if we don't have an table.columns array
 *
 * @param	string		$table: name of table
 * @return	void
 * 
 * @version 3.1.13
 * @since   2.0.0
 */
  function loadTCA( $table )
  {
      // RETURN : TCA is loaded
    if( is_array( $GLOBALS['TCA'][$table]['columns'] ) )
    {
      return;
    }
      // RETURN : TCA is loaded
    
      // Load the TCA
    t3lib_div::loadTCA($table);

      // DRS
    if ($this->pObj->b_drs_tca)
    {
      $prompt = '$GLOBALS[TCA]['.$table.'] is loaded.';
      t3lib_div::devlog('[INFO/DISCOVER] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS

  }









    /***********************************************
    *
    * TypoScript
    *
    **********************************************/



/**
 * cleanup_views(): Clean up the views. Removes the view names.
 *
 * @param	array		$conf: current TypoScript configuration
 * @return	array		$conf
 * @version 3.6.1
 */
  function cleanup_views($conf)
  {
      // #11981, 110106, dwildt
      // Remove any value, keep arrays
    foreach((array) $conf['views.']['list.'] as $key => $view)
    {
      if(substr($key, -1, 1) != '.')
      {
        unset($conf['views.']['list.'][$key]);
      }
    }
    foreach((array) $conf['views.']['single.'] as $key => $view)
    {
      if(substr($key, -1, 1) != '.')
      {
        unset($conf['views.']['single.'][$key]);
      }
    }
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('zz 3044', array_keys($conf['views.']['list.']), array_keys($conf['views.']['single.']));
    return $conf;
  }









  /***********************************************
    *
    * UTF-8
    *
    **********************************************/



 /**
  * Checks the TYPO3 utf-8 configuration.
  *
  *                      $GLOBALS[TSFE]->metaCharset, $GLOBALS[TSFE]->renderCharset
  *                      Result can be overriden by $conf['navigation.']['indexBrowser.']['charset']
  *
  * @return	boolean		TRUE, if one of the following variables has the value utf-8: $TYPO3_CONF_VARS[BE][forceCharset]
  */
  function b_TYPO3_utf8()
  {
    global $TYPO3_CONF_VARS;

    $conf = $this->pObj->conf;
    $str_charset = $conf['navigation.']['indexBrowser.']['charset'];

    if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/BROWSER+SQL] indexBrowser.charset is \''.$str_charset.'\'', $this->pObj->extKey, 0);
    }

    switch($str_charset) {
      case('auto'):
        // Process the statemants below the switch
        break;
      case('iso'):
        if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/BROWSER+SQL] If you are use \'auto\', indexBrowser tries to find the charset automatically.', $this->pObj->extKey, 0);
        }
        return false;
        break;
      case('utf'):
        if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/BROWSER+SQL] If you are use \'auto\', indexBrowser tries to find the charset automatically.', $this->pObj->extKey, 0);
        }
        return true;
        break;
      default:
        // Process the statemants below the switch
        if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[ERROR/BROWSER+SQL] The current value of indexBrowser.charset isn\'t defined: \''.$str_charset.'\'', $this->pObj->extKey, 3);
          t3lib_div::devlog('[HELP/BROWSER+SQL] Please use auto, iso or utf.', $this->pObj->extKey, 1);
          t3lib_div::devlog('[INFO/BROWSER+SQL] Process is now \'auto\'.', $this->pObj->extKey, 0);
        }
    }

    if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/BROWSER+SQL] $TYPO3_CONF_VARS[BE][forceCharset] is \''.$TYPO3_CONF_VARS['BE']['forceCharset'].'\'', $this->pObj->extKey, 0);
    }
    if (strtolower($TYPO3_CONF_VARS['BE']['forceCharset']) == 'utf-8')
    {
      return true;
    }

    if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql) {
      t3lib_div::devlog('[INFO/BROWSER+SQL] $GLOBALS[TSFE]->metaCharset is \''.$GLOBALS['TSFE']->metaCharset.'\'', $this->pObj->extKey, 0);
    }
    if (strtolower($GLOBALS['TSFE']->metaCharset) == 'utf-8')
    {
      return true;
    }
    if ($this->pObj->b_drs_navi || $this->pObj->b_drs_sql) {
      t3lib_div::devlog('[INFO/BROWSER+SQL] $GLOBALS[TSFE]->renderCharset is \''.$GLOBALS['TSFE']->renderCharset.'\'', $this->pObj->extKey, 0);
    }
    if (strtolower($GLOBALS['TSFE']->renderCharset) == 'utf-8')
    {
      return true;
    }
    return false;

  }






/**
 * Translate a char to one-byte and multi-byte notation for both cases lower and upper.
 *
 * @param	string		The char, we want back in lower and upper case and with single byte and multi-byte notation
 * @return	array		Array with for for elements. false, if it $str_char isn't a multi-byte char.
 */
  function char_single_multi_byte($str_char)
  {

    // We don't have multi-byte chars in the MySQL database only.
    // It's possible, that files in PHP stored multi-byte chars too.

    if (strlen($str_char) == strlen(mb_strtolower($str_char, 'UTF-8')))
    {
      // Initial in a multi-byte notation. We want all chars like Ã¤ or Ã„
      if (strlen(mb_strtolower($str_char, "UTF-8")) < 2)
      {
        // Len of the current char is 1. It is a single byte char like a or A.
        return false;
      }
      $arr_case['lower'] = mb_strtolower($str_char, "UTF-8");               // I.e.: $arr_case[lower] = Ã¤
      $arr_case['upper'] = mb_strtoupper($str_char, "UTF-8");               // I.e.: $arr_case[upper] = Ã„

      $arr_utf8[utf8_decode(mb_strtolower($str_char, "UTF-8"))] = $arr_case;  // I.e.: $arr_utf8[ä]
      $arr_utf8[utf8_decode(mb_strtoupper($str_char, "UTF-8"))] = $arr_case;  // I.e.: $arr_utf8[Ä]
      $arr_utf8[mb_strtolower($str_char, "UTF-8")]              = $arr_case;  // I.e.: $arr_utf8[Ã¤]
      $arr_utf8[mb_strtoupper($str_char, "UTF-8")]              = $arr_case;  // I.e.: $arr_utf8[Ã„]
    }
    else
    {
      // Single-byte initial. We want all chars like ä or Ä

      if (strlen(utf8_encode(mb_strtolower($str_char))) < 2)
      {
        // Len of the current char is 1. It is a single byte char like a or A.
        return false;
      }

      $arr_case['lower'] = utf8_encode(mb_strtolower($str_char));             // I.e.: $arr_case[lower] = Ã¤
      $arr_case['upper'] = utf8_encode(mb_strtoupper($str_char));             // I.e.: $arr_case[upper] = Ã„

      $arr_utf8[mb_strtolower($str_char)]               = $arr_case;          // I.e.: $arr_utf8[ä]
      $arr_utf8[mb_strtoupper($str_char)]               = $arr_case;          // I.e.: $arr_utf8[Ä]
      $arr_utf8[utf8_encode(mb_strtolower($str_char))]  = $arr_case;          // I.e.: $arr_utf8[Ã¤]
      $arr_utf8[utf8_encode(mb_strtoupper($str_char))]  = $arr_case;          // I.e.: $arr_utf8[Ã„]
    }

    return $arr_utf8;
  }









  /***********************************************
    *
    * Arrays
    *
    **********************************************/



 /**
  * zz_devPromptArrayNonUnique( ) : Checks whether values of an array are unique or not.
  *                                 If not, method will prompts to devLog
  *
  * @param	array		$testArray  : Array which shopuld checked
  * @param	string		$method     : Calling method
  * @param	string		$line       : Calling line
  * @return	void
  * @version  3.9.13
  * @since    3.9.13
  */
  public function zz_devPromptArrayNonUnique( $testArray, $method, $line )
  {
      // RETURN : DRS is disabled
    if( ! $this->pObj->b_drs_warn )
    {
      return;
    }
      // RETURN : DRS is disabled

      // Get non unique elements
    $testArrayDiff    = array_diff( $testArray, array_unique( $testArray ) );

      // RETURN : test array is unique
    if( empty ( $testArrayDiff ) )
    {
      return;
    }
      // RETURN : test array is unique

      // CSV list of non unique elements
    $csvElementNonUnique = implode( ',', $testArrayDiff );

      // Prompt to devlog
    $prompt = 'elements aren\'t unique: ' . $csvElementNonUnique . ' in ' . $method . ' (line ' . $line . ')';
    t3lib_div::devlog( '[WARN/DRS] ' . $prompt, $this->pObj->extKey, 2 );

    return;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_zz.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_zz.php']);
}

?>
