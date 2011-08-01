<?php
 /***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 - 2011 Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_navi bundles methods for navigation like the A-Z-Browser
 * or the page broser. It is part of the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    browser
 * @version       3.7.0
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   55: class tx_browser_pi1_navi
 *  100:     function __construct($parentObj)
 *  131:     function azBrowser($arr_data)
 *  339:     function azTemplate($arr_data)
 *  616:     function azTabArray($arr_data)
 * 1056:     function azRowsInitial($arr_data)
 * 1321:     function tmplPageBrowser($arr_data)
 *
 *              SECTION: ModeSelector
 * 1532:     function prepaireModeSelector()
 * 1600:     function tmplModeSelector($arr_data)
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi
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


  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $bool_synonyms    = false;
  // [Boolean] It's true, if there are used synonyms
  var $sql_initialField = false;
  // The initial field name in the SQL result (azRows)











 /**
  * Constructor. The method initiate the parent object
  *
  * @param  object    The parent object
  * @return void
  */
  function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;

  }









    /***********************************************
    *
    * A-Z browser
    *
    **********************************************/









  /**
 * Returns an array with used tables and fields out of the TypoScript SQL query parts.
 * The tables will have real names
 *
 * @param array   Array with elements rows and template
 * @return  array   Array with the syntax array[table][] = field
 */
  function azBrowser($arr_data)
  {
    $template       = $arr_data['template'];
    $rows           = $arr_data['rows'];

    $arr_return['data']['rows']     = $rows;
    $arr_return['data']['template'] = $template;

    $lDisplay = $this->pObj->lDisplayList['display.'];



      /////////////////////////////////////
      //
      // Bool Synonyms

    $this->bool_synonyms = $this->conf_view['functions.']['synonym'];
      // Bool Synonyms



      ///////////////////////////////////////////////////
      //
      // RETURN, if the A-Z-Browser isn't activated

    if ($this->pObj->objFlexform->bool_azBrowser)
    {
        // The A-Z-Browser should be displayed
      if ($this->pObj->b_drs_browser) {
        t3lib_div::devlog('[INFO/BROWSER] display.a-z_Browser is true.', $this->pObj->extKey, 0);
      }
    }
    if (!$this->pObj->objFlexform->bool_azBrowser)
    {
        // The A-Z-Browser isn't activated, we don't need any process, return
      if ($this->pObj->b_drs_browser) {
        t3lib_div::devlog('[INFO/BROWSER] display.a-z_Browser is false.', $this->pObj->extKey, 0);
      }
      $template = $this->pObj->cObj->substituteSubpart($template, '###AZSELECTOR###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN, if the A-Z-Browser isn't activated



      ///////////////////////////////////////////////////
      //
      // RETURN, if we don't have configured tabs

    $arr_conf_tabs = $this->conf['navigation.']['a-z_Browser.']['tabs.'];
    if (!is_array($arr_conf_tabs))
    {
      // The A-Z-Browser isn't configured
      if ($this->pObj->b_drs_browser) {
        t3lib_div::devlog('[INFO/BROWSER] a-z_Browser.tabs hasn\'t any element.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/BROWSER] a-z_Browser won\'t be processed.', $this->pObj->extKey, 1);
      }
      $template = $this->pObj->cObj->substituteSubpart($template, '###AZSELECTOR###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN, if we don't have configured tabs



      ///////////////////////////////////////////////////
      //
      // Move $GLOBALS['TSFE']->id temporarily
      // #9458

    $int_tsfeId = $GLOBALS['TSFE']->id;
    if (!empty($this->pObj->objFlexform->int_viewsListPid))
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Move $GLOBALS['TSFE']->id temporarily



      ///////////////////////////////////////////////////
      //
      // Get the A-Z-Browser rows (uid, initialField)

    $arr_result = $this->azRowsInitial($arr_data);
    if ($arr_result['error']['status'])
    {
      $GLOBALS['TSFE']->id = $int_tsfeId; // #9458
      return $arr_result;
    }
    $azRows = $arr_result['data']['azRows'];
    unset($arr_result);
      // Get the A-Z-Browser rows (uid, initialField)



      ////////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After rows initial: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      ///////////////////////////////////////////////////
      //
      // Count the hits per tab, prepaire the tabArray

    $arr_data['azRows']         = $azRows;
    $arr_data['rows']           = $rows;
    $arr_result = $this->azTabArray($arr_data);
    unset($arr_data);
    $lArrTabs = $arr_result['data']['azTabArray'];
    $arr_tsId = $arr_result['data']['tabIds'];
    $rows     = $arr_result['data']['rows'];
    unset($arr_result);
      // Count the hits per tab, prepaire the tabArray



      ////////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After prepairing tab array: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      ///////////////////////////////////////////////////
      //
      // Build the A-Z-Browser template

    $arr_data['azTabArray'] = $lArrTabs;
    $arr_data['tabIds']     = $arr_tsId;
    $arr_data['template']   = $template;
    $arr_result = $this->azTemplate($arr_data);
    unset($arr_data);
    $template = $arr_result['data']['template'];
    unset($arr_result);
      // Build the A-Z-Browser template



      ////////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After building the template: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance


    $arr_return['data']['azTabArray'] = $lArrTabs;
    $arr_return['data']['tabIds']     = $arr_tsId;
    $arr_return['data']['rows']       = $rows;
    $arr_return['data']['template']   = $template;

    $GLOBALS['TSFE']->id = $int_tsfeId; // #9458
    return $arr_return;

  }


















 /**
  * Building the HTML template with the A-Z-Browser
  *
  * @param  array   Array with elements azTabArray, tabIds, template
  * @return array   Array data with the element template
  */
  function azTemplate($arr_data)
  {
    $lArrTabs = $arr_data['azTabArray'];
    $arr_tsId = $arr_data['tabIds'];
    $template = $arr_data['template'];

    $arr_return['data']['template'] = $template;

    $langKey  = $GLOBALS['TSFE']->lang;

    $int_key_defaultTab   = $this->pObj->conf['navigation.']['a-z_Browser.']['defaultTab'];
    $arr_defaultTab       = $this->pObj->conf['navigation.']['a-z_Browser.']['tabs.'][$int_key_defaultTab.'.']['stdWrap.'];
    $str_defaultTabLabel  = $this->pObj->conf['navigation.']['a-z_Browser.']['tabs.'][$int_key_defaultTab];
    $defaultAzTab         = $this->pObj->objWrapper->general_stdWrap($str_defaultTabLabel, $arr_defaultTab);
    $bool_dontLinkDefaultTab = false;
    if ($this->pObj->conf['navigation.']['a-z_Browser.']['defaultTab.']['display_in_url'] == 0)
    {
      $bool_dontLinkDefaultTab = true;
      // #7582, Bugfix, 100501
      if($this->pObj->objFlexform->bool_emptyAtStart)
      {
        $bool_dontLinkDefaultTab = false;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[WARN/TEMPLATING] Empty list by start is true and the default A-Z-tab shouldn\'t linked with a piVar. '.
            'This is not proper.',  $this->pObj->extKey, 2);
          t3lib_div::devlog('[INFO/TEMPLATING] The default A-Z-tab will be linked with a piVar by the system!',  $this->pObj->extKey, 0);
        }
      }
      // #7582, Bugfix, 100501
    }


      //////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    $boolPrompt_1 = false;
    $boolPrompt_2 = false;
      // DRS - Development Reporting System


      //////////////////////////////////////////////////////
      //
      // Get the key of last displayed tab

    end($lArrTabs);
    do
    {
      $i_curr_key = key($lArrTabs);
      prev($lArrTabs);
    }
    while ($lArrTabs[$i_curr_key]['displayWithoutItems'] + $lArrTabs[$i_curr_key]['amount'] < 1);
      // The tab with the current key won't be displayed,
      // if $lArrTabs[$i_curr_key]['displayWithoutItems'] == 0 and $lArrTabs[$i_curr_key]['amount'] == 0
      // Take so long the previous tab as it will be dislayed in this case.

    $lastTabId = $i_curr_key;
      // Get the key of last displayed tab


    $tsDisplayTitleTag = $this->conf['navigation.']['a-z_Browser.']['display.']['tabHrefTitle'];


    foreach((array) $lArrTabs as $key_tab => $arr_tab)
    {
      $str_label  = $lArrTabs[$key_tab]['label'];
        // #8333, fsander
        // #9912, dwildt
//:TODO:
//  /**
//   * Converts special chars (like ���, umlauts etc) to ascii equivalents (usually double-bytes, like �=> ae etc.)
//   *
//   * @param string    Character set of string
//   * @param string    Input string to convert
//   * @return  string    The converted string
//   */
//  function specCharsToASCII($charset,$string) {
//    if ($charset == 'utf-8')  {
      $str_piVar = t3lib_div::convUmlauts(strip_tags(html_entity_decode($str_label)));
//var_dump('navi 419', $this->pObj->objZz->char_single_multi_byte($str_label), $str_piVar);
      //$str_piVar = t3lib_cs::specCharsToASCII(strip_tags(html_entity_decode($str_label)));
      // #9708, fsander
      // $str_piVar = strtolower(ereg_replace('[^a-zA-Z0-9]','',$str_piVar));
      $str_piVar = strtolower(preg_replace('/[^a-zA-Z0-9]*/','',$str_piVar));
      $str_class  = $str_piVar;
      //$str_class  = $str_label;
      //$str_piVar  = $str_label;
      // #8333, fsander

      if ($lArrTabs[$key_tab]['wrap'] != "")
      {
        $str_label = str_replace('|', $str_label, $lArrTabs[$key_tab]['wrap']);
      }
      if ($key_tab != $lastTabId)
      {
        $tabClass = 'tab-'.$str_class;
      }
      // Bug #6363, #5945
      if ($key_tab == $lastTabId)
      {
        $tabClass = 'tab-'.$str_class.' last';
      }
      if ($lArrTabs[$key_tab]['active'])
      {
        $liClass = ' class="'.$tabClass.' selected"';
      }
//:TODO: 101004
      if (!$lArrTabs[$key_tab]['active'])
      {
        $liClass = ' class="'.$tabClass.'"';
      }

      $markerArray['###TAB###'] = '';

      if ($arr_tab['amount'] == 1)
      {
        // The tab has one item: link the tab!
        if($tsDisplayTitleTag)
        {
          // The a-tag
          $title = htmlspecialchars($this->pObj->pi_getLL('browserItem', 'Item', true));
          if(($this->pObj->b_drs_locallang || $this->pObj->b_drs_browser) && !$boolPrompt_1)
          {
            t3lib_div::devlog('[INFO/LOCALLANG+BROWSER] Label for one item is: '.$title, $this->pObj->extKey, 0);
            $prompt = 'If you want another label, please configure _LOCAL_LANG.'.$langKey.'.browserItem';
            t3lib_div::devlog('[HELP/LOCALLANG+BROWSER] '.$prompt, $this->pObj->extKey, 1);
            $boolPrompt_1 = true;
          }
          $title = $arr_tab['amount'].' '.$title;
          $typolink['parameter'] = $GLOBALS['TSFE']->id.' - - "'.$title.'"';
          // Typolink syntax for parameter: PID target class title
        }
        if(!$tsDisplayTitleTag)
        {
          $typolink['parameter'] = $GLOBALS['TSFE']->id;
        }
        $arr_addPiVars = array('azTab' => $str_piVar);
        if($bool_dontLinkDefaultTab)
        {
          $tmp_azTab = false;
          if($str_piVar == $defaultAzTab)
          {
            if(isset($this->pObj->piVars['azTab']))
            {
              $tmp_azTab = $this->pObj->piVars['azTab'];
              unset($this->pObj->piVars['azTab']);
            }
            unset($arr_addPiVars);
            $arr_addPiVars = array(); // 100429, dwildt - Bugfix #7526: tab [All] didn't have any piVar
          }
        }
        // ##9495, fsander
        // remove pointer value from PIvars:
        $arr_addPiVars['pointer'] = '';

        $markerArray['###TAB###'] = $this->pObj->objZz->linkTP_keepPIvars(
                                      $str_label, $typolink, $arr_addPiVars, $this->pObj->boolCache);
        if($bool_dontLinkDefaultTab)
        {
          if($tmp_azTab)
          {
            $this->pObj->piVars['azTab'] = $tmp_azTab;
          }
        }
      }

      if($arr_tab['amount'] > 1)
      {
        // The tab has two items at least: link the tab!
        if($tsDisplayTitleTag)
        {
          $title = htmlspecialchars($this->pObj->pi_getLL('browserItems', 'Items', true));
          if(($this->pObj->b_drs_locallang || $this->pObj->b_drs_browser) && !$boolPrompt_2)
          {
            t3lib_div::devlog('[INFO/LOCALLANG+BROWSER] Label for items is: '.$title, $this->pObj->extKey, 0);
            $prompt = 'If you want another label, please configure _LOCAL_LANG.'.$langKey.'.browserItems';
            t3lib_div::devlog('[HELP/LOCALLANG+BROWSER] '.$prompt, $this->pObj->extKey, 1);
            $boolPrompt_2 = true;
          }
          $title = $arr_tab['amount'].' '.$title;
          $typolink['parameter'] = $GLOBALS['TSFE']->id.' - - "'.$title.'"';
          // Typolink syntax for parameter: PID target class title
        }
        if(!$tsDisplayTitleTag)
        {
          $typolink['parameter'] = $GLOBALS['TSFE']->id;
        }
        $arr_addPiVars = array('azTab' => $str_piVar);
        if($bool_dontLinkDefaultTab)
        {
          $tmp_azTab = false;
          if($str_piVar == $defaultAzTab)
          {
            if(isset($this->pObj->piVars['azTab']))
            {
              $tmp_azTab = $this->pObj->piVars['azTab'];
              unset($this->pObj->piVars['azTab']);
            }
            unset($arr_addPiVars);
            $arr_addPiVars = array(); // 100429, dwildt - Bugfix #7526: tab [All] didn't have any piVar
          }
        }
        // ##9495, fsander
        // remove pointer value from PIvars:
        $arr_addPiVars['pointer'] = '';
        $markerArray['###TAB###'] = $this->pObj->objZz->linkTP_keepPIvars(
                                      $str_label, $typolink, $arr_addPiVars, $this->pObj->boolCache);
        if($bool_dontLinkDefaultTab)
        {
          if($tmp_azTab)
          {
            $this->pObj->piVars['azTab'] = $tmp_azTab;
          }
        }
      }

      if ($arr_tab['amount'] == 0 && $arr_tab['displayWithoutItems'])
      {
        // The tab hasn't any items but should displayed. Display the tab but without any link!
        $markerArray['###TAB###'] = $str_label;
      }

      $markerArray['###LI_CLASS###'] = $liClass;
      $tmplAzTabs = $this->pObj->cObj->getSubpart($template, '###AZSELECTORTABS###');
      $tabs .= $this->pObj->cObj->substituteMarkerArray($tmplAzTabs, $markerArray);
    }


    //////////////////////////////////////////////
    //
    // Process the markers, subpart and template

    unset($markerArray);
    $markerArray                  = $this->pObj->objWrapper->constant_markers();
    $markerArray['###UL_MODE###'] = $this->mode;
    $markerArray['###UL_VIEW###'] = $this->view;
    $tmplAzBrowser  = $this->pObj->cObj->getSubpart($template, '###AZSELECTOR###');
    $tmplAzBrowser  = $this->pObj->cObj->substituteMarkerArray($tmplAzBrowser, $markerArray);
    $tmplAzBrowser  = $this->pObj->cObj->substituteSubpart($tmplAzBrowser, '###AZSELECTORTABS###', $tabs, true);
    $template       = $this->pObj->cObj->substituteSubpart($template, '###AZSELECTOR###', $tmplAzBrowser, true);
    // Process the markers, subpart and template


    //////////////////////////////////////////////
    //
    // Return the template

    $arr_return['data']['template'] = $template;
    return $arr_return;
    // Return the template
  }

















 /**
  * Generates an array with informations for every tab
  *
  * @param  array   Array with elements azRows and rows
  * @return array   Array data with elements azTabArray, tabIds and rows
  * @version        3.4.3
  */
  function azTabArray($arr_data)
  {
    $azRows                           = $arr_data['azRows'];
    $rows                             = $arr_data['rows'];
    $arr_return['data']['azTabArray'] = false;
    $arr_return['data']['tabIds']     = false;
    $arr_return['data']['rows']       = $rows;


    //////////////////////////////////////////////////////
    //
    // Initial Values

    $int_azRows_all     = 0;
    $int_azRows_others  = 0;
    $int_azRows_user    = 0;
    if(is_array($azRows)) {
      $int_azRows_all     = count($azRows);
      $int_azRows_others  = count($azRows);
    }
    $arr_tsId['all']      = -1;
    $arr_tsId['others']   = -1;
    $arr_tsId['default']  = -1;
    $arr_tsId['active']   = -1;
    // Initial Values


    //////////////////////////////////////////////////////
    //
    // --- Comment
    //
    // Three types of tabs
    //
    // 1. All:    A tab, which should display all items
    // 2. Other:  A tab, which should dispaly all items, which didn't
    //            matched the user defined tabs
    // 3. User:   All user defined tabs in the TyporScript configuration
    //
    // --- Comment


    //////////////////////////////////////////////////////
    //
    // Build the $lArrTabs - Step 1 (special, label)

    $conf_tabs = $this->conf['navigation.']['a-z_Browser.']['tabs.'];
    foreach ($conf_tabs as $key_confTab => $str_confTab)
    {
      if (substr($key_confTab, -1) != '.')
      {
        if ($conf_tabs[$key_confTab.'.']['special'] == 'all')
        {
          $lArrTabs[$key_confTab]['special'] = 'all';
          $arr_tsId['all']                   = $key_confTab;
        }
        if ($conf_tabs[$key_confTab.'.']['special'] == 'others')
        {
          $lArrTabs[$key_confTab]['special'] = 'others';
          $arr_tsId['others']                = $key_confTab;
        }
        $lArrTabs[$key_confTab]['label']   = $str_confTab;
        $lArrTabs[$key_confTab]['amount']  = 0;
      }
    }
    // Build the $lArrTabs - Step 1 (special, label)


    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 1: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // --- Comment
    //
    // Structure of the array $lArrTabs
    //
    // $lArrTabs[tabId][label]
    // $lArrTabs[tabId][special]
    // $lArrTabs[tabId][active]
    // $lArrTabs[tabId][amount]
    // $lArrTabs[tabId][displayWithoutItems]
    // $lArrTabs[tabId][amount]
    //
    // --- Comment


    //////////////////////////////////////////////////////
    //
    // Build the $lArrTabs - Step 2 (stdWrap, active, displayWoItems)

    // Get the key of the default tab
    $int_key_defaultTab   = $this->conf['navigation.']['a-z_Browser.']['defaultTab'];
    $arr_tsId['default']  = $int_key_defaultTab;
    // Get the key of the default tab

    foreach ($lArrTabs as $key_lArrTab => $arr_lArrTab)
    {
      // Tab TypoScript configuration
      $conf_tab   = $conf_tabs[$key_lArrTab.'.'];

      // label
      $str_label  = $arr_lArrTab['label'];

      // stdWrap
      $conf_stdWrap = $conf_tab['stdWrap.'];
      if (is_array($conf_stdWrap))
      {
        $str_label = $this->pObj->objWrapper->general_stdWrap($str_label, $conf_stdWrap);
      }
      //$str_label  = htmlspecialchars($str_label);  // <span> wird maskiert!
      $lArrTabs[$key_lArrTab]['label'] = $str_label;
      // stdWrap

      // wrap
      $str_wrap = $conf_tabs[$key_lArrTab.'.']['wrap'];
      if ($str_wrap == '')
      {
        $str_wrap = $this->conf['navigation.']['a-z_Browser.']['defaultTabWrap'];
      }
      $lArrTabs[$key_lArrTab]['wrap'] = $str_wrap;
      // wrap

      // active status
      // #8333: fsander
      // #9912, dwildt
//:TODO:
      $str_label_cleaned = t3lib_div::convUmlauts(strip_tags(html_entity_decode($str_label)));
      // #9708 fsander
      //$str_label_cleaned = strtolower(ereg_replace('[^a-zA-Z0-9]','',$str_label));
      $str_label_cleaned = strtolower(preg_replace('[^a-zA-Z0-9]','',$str_label));
      //if($str_label == $this->pObj->piVar_azTab)
      // #8333: fsander

      $lArrTabs[$key_lArrTab]['active'] = false;
      if($str_label_cleaned == $this->pObj->piVar_azTab)
      {
        $lArrTabs[$key_lArrTab]['active'] = true;
        $arr_tsId['active']               = $key_lArrTab;
      }
      // #10054
      //var_dump('navi 771', $this->pObj->piVar_azTab, $str_label_cleaned);
      if((strtolower($this->pObj->piVar_azTab) == $str_label_cleaned) && ($int_key_defaultTab == $key_lArrTab))
      {
        $lArrTabs[$key_lArrTab]['active'] = true;
        $arr_tsId['active']               = $key_lArrTab;
      }
      // #10054
      // active status

      // displayWithoutItems
      $str_displayWithoutItems = $conf_tab['displayWithoutItems'];
      if ($str_displayWithoutItems == '')
      {
        $lArrTabs[$key_lArrTab]['displayWithoutItems'] = $this->conf['navigation.']['a-z_Browser.']['display.']['tabWithoutItems'];
      }
      if ($str_displayWithoutItems != '')
      {
        $lArrTabs[$key_lArrTab]['displayWithoutItems'] = intval($str_displayWithoutItems);
      }
      // displayWithoutItems
    }
    // Build the $lArrTabs - Step 2 (stdWrap, active, displayWoItems)


    //////////////////////////////////////////////////////
    //
    // Check active status

    if ($arr_tsId['active'] < 0)
    {
      $arr_tsId['active'] = $arr_tsId['default'];
    }
    // Check active status


    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 2: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // Build the $lArrTabs - Step 3: Count the rows

    $rows_others        = $azRows;
    $int_initialsUser   = 0;
    $bool_caseSensitive = $this->conf['navigation.']['a-z_Browser.']['caseSensitive'];

    foreach ($lArrTabs as $key_lArrTab => $arr_lArrTab)
    {
      // Tab TypoScript configuration
      $conf_tab   = $conf_tabs[$key_lArrTab.'.'];
      $csvTabInitials = $conf_tab['valuesCSV'];
      if ($csvTabInitials)
      {
        $arrTabInitials = explode(',', $csvTabInitials);
        foreach ($arrTabInitials as $key => $value)
        {
          $arrTabInitials[$key] = trim($value);
        }
        foreach ($arrTabInitials as $strTabInitial)
        {
          if (is_array($azRows))
          {
            foreach ($azRows as $row => $elements)
            {
              $str_sqlInitial = $elements[$this->sql_initialField];
              $str_sqlInitial = substr($str_sqlInitial, 0, strlen($strTabInitial));
              // :todo: UTF8-Support!
              $bool_equal = false;
              if(!$bool_caseSensitive)
              {
                if(strtolower($str_sqlInitial) == strtolower($strTabInitial))
                {
                  $bool_equal = true;
                }
              }
              if($bool_caseSensitive)
              {
                if($str_sqlInitial == $strTabInitial)
                {
                  $bool_equal = true;
                }
              }
              if($bool_equal)
              {
                $lArrTabs[$key_lArrTab]['amount']++;
                $lArrTabs[$key_lArrTab]['keyRow'][] = $row;
                $int_initialsUser++;
                unset($rows_others[$row]);
              }
            }
          }
        }
      }
    }
    if (is_array($rows_others))
    {
      foreach ($rows_others as $row => $elements)
      {
        $lArrTabs[$arr_tsId['others']]['amount']++;
        $lArrTabs[$arr_tsId['others']]['keyRow'][] = $row;
      }
    }
    // Build the $lArrTabs - Step 3: Count the rows


    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 3: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // Count the rows for ALL and OTHERS

    if(is_array($azRows)) {
      $int_initialsAll = count($azRows);
    }
    if(!is_array($azRows)) {
      $int_initialsAll = 0;
    }
    $lArrTabs[$arr_tsId['all']]['amount']    = $int_initialsAll;
    // Count the rows for ALL and OTHERS


    ///////////////////////////////////////////////
    //
    // Process the rows

    // Get the uids of the records, which should displayed
    $arr_displayUid = false;
    if ($arr_tsId['active'] != $arr_tsId['all'])
    {
      $table          = $this->pObj->localTable;
      $arr_displayRow = $lArrTabs[$arr_tsId['active']]['keyRow'];
      if (is_array($azRows) && is_array($arr_displayRow))
      {
        foreach ($azRows as $row => $elements)
        {
          if (in_array($row, $arr_displayRow))
          {
            $arr_displayUid[] = $elements[$table.'.uid'];
          }
        }
      }
    }
    // Get the uids of the records, which should displayed

    // Remove rows, which aren't in the uid array
    $drs_rows_before = count($rows);
    if ($arr_tsId['active'] != $arr_tsId['all'])
    {
      if (is_array($rows))
      {
        if (!is_array($arr_displayRow))
        {
          $arr_displayRow = array();
        }
        foreach ($rows as $row => $elements)
        {
          if (!in_array($row, $arr_displayRow))
          {
            unset($rows[$row]);
          }
        }
// Umstellung wegen Synonyms, 091006
//      if (!is_array($arr_displayUid))
//      {
//        $arr_displayUid = array();
//      }
//        $int_count = 0;
//        foreach ($rows as $row => $elements)
//        {
//          $uid = $elements[$table.'.uid'];
//          if (!in_array($uid, $arr_displayUid))
//          {
//            unset($rows[$row]);
//          }
//          if (in_array($uid, $arr_displayUid))
//          {
//            $arr_bug[$int_count]['row']       = $row;
//            $arr_bug[$int_count]['elements']  = $elements;
//            $int_count++;
//          }
//        }
      }
    }
    $drs_rows_after = count($rows);
    // Remove rows, which aren't in the uid array


    ///////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($drs_rows_after != $drs_rows_before)
    {
      $removed_rows = $drs_rows_before - $drs_rows_after;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The A-Z-Browser has #'.$removed_rows.' rows removed.',  $this->pObj->extKey, 0);
      }
    }
    // DRS - Development Reporting System


    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] Remove waste rows: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // RETURN the result

    $arr_return['data']['azTabArray'] = $lArrTabs;
    $arr_return['data']['tabIds']     = $arr_tsId;
    $arr_return['data']['rows']       = $rows;

    return $arr_return;
    // RETURN the result
  }

















 /**
  * Building the SQL query for the A-Z-Browser. Exxecute the query. Return the rows.
  *
  * @param  array   Array with the current rows
  * @return array   Array data with the element azRows
  */
  function azRowsInitial($arr_data)
  {
    $arr_return['error']['status']  = false;
    $arr_return['data']['azRows']   = false;
    $rows                           = $arr_data['rows'];


    ///////////////////////////////////////////////
    //
    // RETURN if we got an empty result

    if (!is_array($rows) || (is_array($rows) && count($rows) < 1))
    {
      $arr_return['data']['azRows'] = false;
      return $arr_return;
    }
    // RETURN if we got an empty result


    ///////////////////////////////////////////////
    //
    // Get the table.field for the a-z_Browser initials

    if (isset($this->conf_view['navigation.']['a-z_Browser.']['field']))
    {
      $str_initialField = $this->conf_view['navigation.']['a-z_Browser.']['field'];
      if ($this->pObj->b_drs_browser) {
        t3lib_div::devlog('[INFO/BROWSER] '.$this->conf_path.'a-z_Browser.field is '.$str_initialField, $this->pObj->extKey, 0);
      }
    }
    if (!$str_initialField)
    {
      $str_initialField = $this->conf['navigation.']['a-z_Browser.']['field'];
      if ($str_initialField)
      {
        // The user has defined a table.field element
        if ($this->pObj->b_drs_browser) {
          t3lib_div::devlog('[INFO/BROWSER] a-z_Browser.field is '.$str_initialField, $this->pObj->extKey, 0);
        }
      }
    }
    if (!$str_initialField)
    {
      // The user hasn't defined a table.field element, we take the first one of the field views.list.X.select
      // First table in the global arr_realTables_arrFields
      reset($this->pObj->arr_realTables_arrFields);
      $table             = key($this->pObj->arr_realTables_arrFields);
      // First field in the this table
      $field             = $this->pObj->arr_realTables_arrFields[$table][0];
      $tableField        = $table.'.'.$field;
      $str_initialField  = $tableField;
      if ($this->pObj->b_drs_browser)
      {
        $prompt = 'Default: a-z_Browser.field is the first table.field from '.$this->conf_path.'select: '.$str_initialField;
        t3lib_div::devlog('[INFO/BROWSER] '.$prompt, $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/BROWSER] If you need another table.field use '.$this->conf_path.'a-z_Browser.field please.', $this->pObj->extKey, 1);
      }
    }
    $this->sql_initialField = $str_initialField;
    // Get the table.field for the a-z_Browser initials


    ///////////////////////////////////////////////
    //
    // ERROR if table isn't the local table

    list($table, $field) = explode('.', $str_initialField);
    if ($table != $this->pObj->localTable)
    {
      $str_prompt = '[ERROR/BROWSER] a-z_Browser field isn\'t a field from the local table:<br />
        table.field: '.$str_initialField.'<br />
        localtable: '.$this->pObj->localTable.'<br />
        <br />
        Please configure:<br />
        '.$this->conf_path.'a-z_Browser.field = '.$this->pObj->localTable.'... or<br />
        a-z_Browser.field = '.$this->pObj->localTable.'...';
      if ($this->pObj->b_drs_browser)
      {
        t3lib_div::devlog($str_prompt, $this->pObj->extKey, 3);
        t3lib_div::devlog('[INFO/BROWSER] A-Z-Browser won\'t be processed.', $this->pObj->extKey, 0);
      }

      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error A-Z-Browser</h1>';
      $arr_return['error']['prompt'] = '<p style="color:red">'.$str_prompt.'</p>';
      return $arr_return;
    }


    ///////////////////////////////////////////////
    //
    // Synonyms

    if ($this->bool_synonyms || 1)
    {
      if ($this->bool_synonyms)
      {
        $syn_tableField   = $this->conf_view['functions.']['synonym.']['for.']['table_field'];  // sv.sv_name
        list($syn_table, $syn_field) = explode('.', $syn_tableField);
        $real_table       = $this->conf_view['aliases.']['tables.'][$syn_table];                // tx_civserv_service
        $real_tableField  = $real_table.'.'.$syn_field;                                         // tx_civserv_service.sv_name
        $tableFieldUid    = $real_table.'.uid';
        $tableFieldAz     = $real_tableField;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] Synonyms: A-Z-Browser takes the current rows.', $this->pObj->extKey, 0);
        }
      }
      if (!$this->bool_synonyms)
      {
        $tableFieldUid  = $table.'.uid';
        $tableFieldAz   = $str_initialField;
      }
      foreach ($rows as $int_row => $elements)
      {
        $azRows[$int_row][$tableFieldUid] = $elements[$tableFieldUid];
        $azRows[$int_row][$tableFieldAz]  = $elements[$tableFieldAz];
      }
      $arr_return['data']['azRows'] = $azRows;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] Synonyms: A-Z-Browser process the current rows.', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
    // Synonyms


    ///////////////////////////////////////////////
    //
    // Build the SQL query

    $arr_uids = false;
    // Get all uids from the current SQL result (from rows).
    foreach ($rows as $row => $elements)
    {
      $arr_uids[] = $elements[$table.'.uid'];
    }
    $csv_uids = implode(', ', $arr_uids);
    $str_select = "SELECT ".$table.".uid AS `".$table.".uid`, ".$str_initialField." AS `".$str_initialField."`\n";
    $str_from   = "FROM ".$table."\n";
    $str_where  = "WHERE ".$table.".uid IN ( ".$csv_uids." )\n";
    $query = $str_select.$str_from.$str_where;
    // Build the SQL query


    ///////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_templating)
    {
      t3lib_div::devlog('[INFO/TEMPLATING] A-Z-Browser query<br />
        '.$query,  $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System


    ///////////////////////////////////////////////
    //
    // Execute the Queries for counting/tabs

    $res   = $GLOBALS['TYPO3_DB']->sql_query($query);
    $error = $GLOBALS['TYPO3_DB']->sql_error();
    // Execute the Queries for counting/tabs


    ///////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($error != '')
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      if ($this->pObj->b_drs_error)
      {
        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
      }
      else
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">'.$this->pObj->pi_getLL('drs_sql_prompt').'</p>';
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn.$str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
    // DRS - Development Reporting System


    ///////////////////////////////////////////////
    //
    // Building the a-z_Browser rows

    $i_counter    = 0;
    $tmp_initial  = array();
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
    {
      $azRows[] = $row;
    }
    // Building the a-z_Browser rows


    ///////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_templating)
    {
      $int_rows = count($azRows);
      t3lib_div::devlog('[INFO/TEMPLATING] A-Z-Browser has #'.$int_rows.' rows.',  $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System


    ///////////////////////////////////////////////
    //
    // SQL Free Result

    $GLOBALS['TYPO3_DB']->sql_free_result($res);
    // SQL Free Result


    ///////////////////////////////////////////////
    //
    // RETURN the A-Z-Browser rows

    $arr_return['data']['azRows'] = $azRows;
    return $arr_return;
    // RETURN the A-Z-Browser rows
  }









    /***********************************************
    *
    * pagebrowser
    *
    **********************************************/









 /**
  * Building the page browser. Returns the HTML template
  *
  * @param  array   Array with elements template and display
  * @return string    template
  */
  function tmplPageBrowser($arr_data)
  {

    $int_currTab    = $arr_data['tabIds']['active'];
    $arr_currRowIds = $arr_data['azTabArray'][$int_currTab]['keyRow'];

    $template       = $arr_data['template'];
    $rows           = $arr_data['rows'];

    $arr_return['data']['template'] = $template;
    $arr_return['data']['rows']     = $rows;



      ///////////////////////////////////////////////
      //
      // RETURN if pagebrowser shouldn't displayed

    if (!$this->pObj->objFlexform->bool_pageBrowser)
    {
      $template = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN if pagebrowser shouldn't displayed



      ///////////////////////////////////////////////
      //
      // RETURN if we have any row

    if (!is_array($rows) || (is_array($rows) && count($rows) < 1))
    {
      $template = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN if we have any row



      ///////////////////////////////////////////////
      //
      // RETURN if firstVisit and emptyListByStart

    if($this->pObj->boolFirstVisit and $this->pObj->objFlexform->bool_emptyAtStart)
    {
      $template = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN if firstVisit and emptyListByStart



      ///////////////////////////////////////////////////
      //
      // Move $GLOBALS['TSFE']->id temporarily
      // #9458

    $int_tsfeId = $GLOBALS['TSFE']->id;
    if (!empty($this->pObj->objFlexform->int_viewsListPid))
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Move $GLOBALS['TSFE']->id temporarily



// 110801, dwildt -
//      ///////////////////////////////////////////////
//      //
//      // Set maximum for the pointer
//
//      // 110302, dwildt: :todo: it seems, that $int_maxPointer isn't handled anywhere
//    $int_maxPointer = 9999;
//      // #10858, dwildt, 101220
//    if(isset($this->pObj->piVars['pointer']))
//    {
//      if($this->pObj->piVars['pointer'] > 0)
//      {
//        if($this->conf_view['limit'])
//        {
//          list($start, $limit) = explode(',', $this->conf_view['limit']);
//          if($limit < 1) $limit = 20;
//        }
//        $int_maxPointer = (count($rows) / $limit);
//          // Returns the next lowest integer
//        $int_maxPointer = floor($int_maxPointer);
//        if($this->pObj->piVars['pointer'] > $int_maxPointer)
//        {
//          $this->pObj->piVars['pointer'] = $int_maxPointer;
//        }
//      }
//        // 13549, 110203, dwildt
//      $int_maxPointer = (count($rows) / $this->pObj->piVars['pointer']);
//    }
//      // Set maximum for the pointer
// 110801, dwildt -



      ///////////////////////////////////////////////
      //
      // Change pagebrowser in case of limit

    if($this->conf_view['limit'])
    {
      list($start, $limit) = explode(',', $this->conf_view['limit']);
      if($limit < 1) $limit = 20;
      $this->conf['navigation.']['pageBrowser.']['results_at_a_time'] = trim($limit);

        // DRS - Development Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] pageBrowser.result_at_a_time is overriden by limit: '.$limit.'.',  $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
    }
      // Change pagebrowser in case of limit



      ///////////////////////////////////////////////
      //
      // Init piBase for pagebrowser

    $this->pObj->internal['res_count']          = count($rows);
    $this->pObj->internal['maxPages']           = $this->conf['navigation.']['pageBrowser.']['maxPages'];
    $this->pObj->internal['results_at_a_time']  = $this->conf['navigation.']['pageBrowser.']['results_at_a_time'];
    $this->pObj->internal['showRange']          = $this->conf['navigation.']['pageBrowser.']['showRange'];
    $this->pObj->internal['dontLinkActivePage'] = $this->conf['navigation.']['pageBrowser.']['dontLinkActivePage'];
    $this->pObj->internal['showFirstLast']      = $this->conf['navigation.']['pageBrowser.']['showFirstLast'];
    $this->pObj->internal['pagefloat']          = $this->conf['navigation.']['pageBrowser.']['pagefloat'];
      // Init piBase for pagebrowser



      ///////////////////////////////////////////////
      //
      // Get the wrapped pagebrowser

    $pb = $this->conf['navigation.']['pageBrowser.'];
    $res_items  = $this->pObj->pi_list_browseresults
                  (
                    $pb['showResultCount'], $pb['tableParams'], $pb['wrap.'],$pb['pointer'],$pb['hscText']
                  );
      // Get the wrapped pagebrowser



      ///////////////////////////////////////////////
      //
      // Build the template

    $markerArray                            = $this->pObj->objWrapper->constant_markers();
    $markerArray['###RESULT_AND_ITEMS###']  = $res_items;
    $markerArray['###MODE###']              = $this->mode;
    $markerArray['###VIEW###']              = $this->view;
    $subpart      = $this->pObj->cObj->getSubpart($template, '###PAGEBROWSER###');
    $pageBrowser  = $this->pObj->cObj->substituteMarkerArray($subpart, $markerArray);
    $template     = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', $pageBrowser, true);
      // Build the template



      ///////////////////////////////////////////////
      //
      // Process the rows

    $int_start  = $this->pObj->piVars[$pb['pointer']] * $pb['results_at_a_time'];
    $int_amount = $pb['results_at_a_time'];

    $int_counter = 0;
    $int_remove_start = $int_start;
    $int_remove_end   = $int_start + $int_amount;
    $drs_rows_before  = count($rows);
    foreach ($rows as $row => $elements)
    {
      if ($int_counter < $int_remove_start || $int_counter >= $int_remove_end)
      {
        unset($rows[$row]);
      }
      $int_counter++;
    }
    $drs_rows_after = count($rows);
      // Process the rows



      ///////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($drs_rows_after != $drs_rows_before)
    {
      $removed_rows = $drs_rows_before - $drs_rows_after;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The pagebrowser has #'.$removed_rows.' rows removed.',  $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System



      ///////////////////////////////////////////////
      //
      // RETURN the result

    $arr_return['data']['template'] = $template;
    $arr_return['data']['rows']     = $rows;
    $GLOBALS['TSFE']->id            = $int_tsfeId; // #9458
    return $arr_return;
      // RETURN the result
  }









    /***********************************************
    *
    * mode selector
    *
    **********************************************/



    /**
 * Prepaire an array for the mode selector. Allocate a value to $this->piVar_mode.
 *
 * @return  array   Array with the modeSelector names
 */
  function prepaireModeSelector()
  {

    $arr_return = array();
    $arr_return['error']['status'] = false;



      ///////////////////////////////////////////////
      //
      // RETURN with an error, if there are no views

    if(!is_array($this->conf['views.']))
    {
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_views_noview').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // RETURN with an error, if there are no views



      ///////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    $langKey = $GLOBALS['TSFE']->lang;
    if($langKey == 'en')
    {
      $langKey = 'default';
    }
    if(is_array($this->conf['views.'][$this->view.'.']))
    {
      foreach((array) $this->conf['views.'][$this->view.'.'] as $keyView => $arrView)
      {
        // We don't need the typoscript array dot
        $mode                       = substr($keyView, 0, strlen($keyView) - 1);
        $llMode                     = $this->pObj->pi_getLL($this->view.'_mode_'.$mode, $mode);
        $arr_return['data'][$mode]  = $this->pObj->pi_getLL($this->view.'_mode_'.$mode, $llMode);
        if ($this->pObj->b_drs_locallang && $mode == $llMode)
        {
          t3lib_div::devlog('[WARN/LOCALLANG] '.$this->conf_path.' hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
          $prompt = 'Please configure _LOCAL_LANG.'.$langKey.'.'.$this->view.'_mode_'.$mode.'.';
          t3lib_div::devlog('[HELP/LOCALLANG] '.$prompt, $this->pObj->extKey, 1);
        }
      }
    }
      // DRS - Development Reporting System
    return $arr_return;
  }









 /**
  * Building the mode selector HTML code.
  *
  * @param  array   Array with the template and the mode selector tabs
  * @return string    template
  */
  function tmplModeSelector($arr_data)
  {

    $template   = $arr_data['template'];
    $arr_items  = $arr_data['arrModeItems'];


      /////////////////////////////////////
      //
      // Without items don't display any tabs

    if (count($arr_items) <= 1) {
        // We don't have a mode selector
      $template = $this->pObj->cObj->substituteSubpart($template, '###MODESELECTOR###', '', true);
      return $template;
    }



      /////////////////////////////////////
      //
      // Building the tabs

    reset($arr_items);
    $i_max_counter  = count($arr_items);
    $i_counter      = 0;
    $arrTabs        = array();
    while (list($str_item_key, $str_item_value) = each($arr_items))
    {
      $tabClass         = ($i_counter < ($i_max_counter - 1)) ? 'tab-'.$i_counter : 'tab-'.$i_counter.' last';
      $class            = $this->mode == $str_item_key ? ' class="'.$tabClass.' selected"' : ' class="'.$tabClass.'"';
      $str_item_value   = htmlspecialchars($str_item_value);
      if ($this->conf['navigation.']['modeSelector.']['wrap'] != '') {
        $str_item_value = str_replace('|', $str_item_value, $this->conf['navigation.']['modeSelector.']['wrap']);
      }
      $item             = $this->pObj->pi_linkTP_keepPIvars($str_item_value, array('mode' => $str_item_key), $this->pObj->boolCache);
      $markerArray['###CLASS###'] = $class;
      $markerArray['###TABS###']  = $item;
      $modeSelectorTabs           = $this->pObj->cObj->getSubpart($template, '###MODESELECTORTABS###');
      $tabs                      .= $this->pObj->cObj->substituteMarkerArray($modeSelectorTabs, $markerArray);
      $i_counter++;
    }
    unset($markerArray);
      // Building the tabs



      /////////////////////////////////////
      //
      // Building and Return the template

    $markerArray               = $this->pObj->objWrapper->constant_markers();
    $markerArray['###MODE###'] = $this->mode;
    $markerArray['###VIEW###'] = $this->view;
    $modeSelector = $this->pObj->cObj->getSubpart($template, '###MODESELECTOR###');
    $modeSelector = $this->pObj->cObj->substituteMarkerArray($modeSelector, $markerArray);
    $modeSelector = $this->pObj->cObj->substituteSubpart($modeSelector, '###MODESELECTORTABS###', $tabs,          true);
    $template     = $this->pObj->cObj->substituteSubpart($template,     '###MODESELECTOR###',     $modeSelector,  true);
    return $template;
      // Building and Return the template

  }









    /***********************************************
    *
    * record browser
    *
    **********************************************/









 /**
  * recordbrowser_set_session_data: Set session data for the record browser.
  *                                 * We need the record browser in the sngle view.
  *                                 * This method must be called, before the page browser 
  *                                   changes the rows array (before limiting).
  *                                 * Feature: #27041
  *
  * @param  array   $rows: Array with all available rows of the list view in order of the list view
 * @return  array   $arr_return: false in case of success, otherwise array with an error message
  * 
  * @version 3.7.0
  */
  function recordbrowser_set_session_data($rows)
  {
      /////////////////////////////////////
      //
      // RETURN record browser isn't enabled

    if(!$this->pObj->conf['navigation.']['record_browser'])
    {
      if ($this->pObj->b_drs_templating)
      {
        $value = $this->pObj->conf['navigation.']['record_browser'];
        t3lib_div::devlog('[INFO/TEMPLATING] navigation.record_browser is \'' . $value . '\' '.
          'Record browser won\'t be handled (best performance).', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN record browser isn't enabled



      /////////////////////////////////////
      //
      // RETURN session isn't enabled

    if(!$this->pObj->conf['session_manager.']['session.']['enabled'])
    {
      if ($this->pObj->b_drs_templating)
      {
        $value = $this->pObj->conf['session_manager.']['session.']['enabled'];
        t3lib_div::devlog('[INFO/TEMPLATING] session_manager.session.enabled is \'' . $value . '\' '.
          'Record browser won\'t get its data from session (less performance).', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN session isn't enabled



      /////////////////////////////////////
      //
      // RETURN rows are empty

    if(empty($rows))
    {
        // Get the tx_browser_pi1 session array 
      $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->pObj->prefixId);
        // Empty the array with the uids of all rows 
      $arr_browser_session['uids_all_rows'] = array();
        // Set the tx_browser_pi1 session array
      $GLOBALS['TSFE']->fe_user->setKey('ses', $this->pObj->prefixId, $arr_browser_session);
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] Rows are empty. Session array [' . $this->pObj->prefixId . '][uids_all_rows] will be empty.',  $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN rows are empty



      /////////////////////////////////////
      //
      // Get table.field for uid of the local table

    $key_for_uid = $this->pObj->arrLocalTable['uid'];
    
      // RETURN uid table.field isn't any key
    $key = key($rows);
    if(!isset($rows[$key][$key_for_uid]))
    {
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error Record Browser</h1>';
      $arr_return['error']['prompt'] = '<p style="color:red">Key is missing in $rows. Key is ' . $key_for_uid . '</p>';
      $arr_return['error']['prompt'] = $arr_return['error']['prompt'] . '<p>' . __METHOD__ . ' (' . __LINE__ . ')</p>';
      return $arr_return;
    }
      // RETURN uid table.field isn't any key
      // Get table.field for uid of the local table



      /////////////////////////////////////
      //
      // LOOP rows: set the array with uids

    $arr_uid = array();
    foreach((array) $rows as $row => $elements)
    {
      $arr_uid[] = $elements[$key_for_uid];
    }
    //echo '<pre>' . var_export($arr_uid, true) . '</pre>';
      // LOOP rows: set the array with uids



      /////////////////////////////////////
      //
      // Set the session array

      // Get the tx_browser_pi1 session array 
    $arr_browser_session  = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->pObj->prefixId);
      // Overwrite the array with the uids of all rows 
    $arr_browser_session['uids_all_rows'] = $arr_uid;
      // Set the tx_browser_pi1 session array
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->pObj->prefixId, $arr_browser_session);
    if ($this->pObj->b_drs_templating)
    {
      t3lib_div::devlog('[INFO/TEMPLATING] Session array [' . $this->pObj->prefixId . '][uids_all_rows] is set with ' .
        '#' . count($arr_uid) . ' uids.',  $this->pObj->extKey, 0);
    }
      // Set the session array

  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi.php']) 
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi.php']);
}

?>
