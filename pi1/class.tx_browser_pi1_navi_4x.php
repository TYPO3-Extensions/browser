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
 * The class tx_browser_pi1_navi_4x bundles methods for navigation like the index browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.9
 * @since       3.9.9
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   67: class tx_browser_pi1_navi_4x
 *  116:     public function __construct($parentObj)
 *
 *              SECTION: Index browser
 *  155:     public function indexBrowser($arr_data)
 *  383:     public function indexBrowserTemplate($arr_data)
 *  649:     public function indexBrowserTabArray($arr_data)
 * 1089:     public function indexBrowserRowsInitial($arr_data)
 *
 *              SECTION: pagebrowser
 * 1357:     public function tmplPageBrowser($arr_data)
 *
 *              SECTION: mode selector
 * 1596:     public function prepaireModeSelector()
 * 1663:     public function tmplModeSelector($arr_data)
 *
 *              SECTION: record browser
 * 1785:     public function recordbrowser_get($str_content)
 * 1872:     public function recordbrowser_callListView()
 * 1941:     private function recordbrowser_rendering()
 * 2271:     public function recordbrowser_set_session_data($rows)
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_4x
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
  * @version  3.9.9
  * @since    3.9.9
  */
  public function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;
      // 111023, uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
    $this->t3lib_cs_obj = t3lib_div::makeInstance('t3lib_cs');
  }









    /***********************************************
    *
    * Index browser
    *
    **********************************************/



/**
 * indexBrowser_set( ):
 *                          No support for synonyms!
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  public function indexBrowser_set( $content )
  {
    $arr_return['data']['content'] = $content;
    
    $lDisplay = $this->pObj->lDisplayList['display.'];

      // RETURN: requirements aren't met
    if( ! $this->indexBrowser_checkRequirements( ) )
    {
      $content = $this->pObj->cObj->substituteSubpart( $content, '###INDEXBROWSER###', null, true );
      $arr_return['data']['content'] = $content;
      return $arr_return;
    }
      // RETURN: requirements aren't met

    $arr_return = $this->indexBrowser_rows( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    $rows = $arr_return['data']['rows'];


      // :TODO:
      // Move $GLOBALS['TSFE']->id temporarily
      // Get the index browser rows (uid, initialField)
      // Count the hits per tab, prepaire the tabArray
      // Build the index browser template

    return $arr_return;
  }



/**
 * indexBrowser_checkRequirements( ): Checks
 *                                    * configuration of the flexform
 *                                    * configuration of TS tabs
 *                                    and returns false, if a requirement isn't met
 *
 * @return	boolean   true / false
 * @version 3.9.9
 * @since   3.9.9
 */
  private function indexBrowser_checkRequirements( )
  {
      // RETURN: index browser is disabled
    if( ! $this->pObj->objFlexform->bool_indexBrowser )
    {
      if( $this->pObj->b_drs_navi )
      {
        $prompt = 'display.indexBrowser is false.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN: index browser is disabled

      // RETURN: index browser hasn't any configured tab
    $arr_conf_tabs = $this->conf['navigation.']['indexBrowser.']['tabs.'];
    if( ! is_array( $arr_conf_tabs ) )
    {
      // The index browser isn't configured
      if ( $this->pObj->b_drs_navi )
      {
        $prompt = 'navigation.indexBrowser.tabs hasn\'t any element.';
        t3lib_div::devlog( '[WARN/NAVIGATION] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'navigation.indexBrowser won\'t be processed.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN: index browser hasn't any configured tab

    return true;
  }



/**
 * indexBrowser_rows( ): Checks
 *                                    * configuration of the flexform
 *                                    * configuration of TS tabs
 *                                    and returns false, if a requirement isn't met
 *
 * @return	boolean   true / false
 * @version 3.9.9
 * @since   3.9.9
 */
  private function indexBrowser_rows( )
  {
    $arr_return['data']['rows'] = null;

    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = '<h1 style="color:red">Error index browser</h1>';
    $arr_return['error']['prompt'] = '<p style="color:red">No rows.</p>';

    return $arr_return;
  }

















 /**
  * Building the HTML template with the index browser
  *
  * @param	array		Array with elements indexBrowserTabArray, tabIds, template
  * @return	array		Array data with the element template
  */
  public function indexBrowserTemplate($arr_data)
  {
    $lArrTabs = $arr_data['indexBrowserTabArray'];
    $arr_tsId = $arr_data['tabIds'];
    $template = $arr_data['template'];

    $arr_return['data']['template'] = $template;

    $langKey  = $GLOBALS['TSFE']->lang;

    $int_key_defaultTab   = $this->pObj->conf['navigation.']['indexBrowser.']['defaultTab'];
    $arr_defaultTab       = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$int_key_defaultTab.'.']['stdWrap.'];
    $str_defaultTabLabel  = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$int_key_defaultTab];
    $defaultAzTab         = $this->pObj->objWrapper->general_stdWrap($str_defaultTabLabel, $arr_defaultTab);
    $bool_dontLinkDefaultTab = false;
    if ($this->pObj->conf['navigation.']['indexBrowser.']['defaultTab.']['display_in_url'] == 0)
    {
      $bool_dontLinkDefaultTab = true;
      // #7582, Bugfix, 100501
      if($this->pObj->objFlexform->bool_emptyAtStart)
      {
        $bool_dontLinkDefaultTab = false;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[WARN/TEMPLATING] Empty list by start is true and the default tab of the index browser shouldn\'t linked with a piVar. '.
            'This is not proper.',  $this->pObj->extKey, 2);
          t3lib_div::devlog('[INFO/TEMPLATING] The default tab of the index browser will be linked with a piVar by the system!',  $this->pObj->extKey, 0);
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


    $tsDisplayTitleTag = $this->conf['navigation.']['indexBrowser.']['display.']['tabHrefTitle'];


    foreach((array) $lArrTabs as $key_tab => $arr_tab)
    {
      $str_label  = $lArrTabs[$key_tab]['label'];

        // - 111023, dwildt/uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
        // $str_piVar = t3lib_div::convUmlauts(strip_tags(html_entity_decode($str_label)));
        // + 111023, dwildt/uherrmann, #9912
      $str_piVar = $this->t3lib_cs_obj->specCharsToASCII( $this->bool_utf8, strip_tags( html_entity_decode( $str_label ) ) );
        // #9708, fsander
        // $str_piVar = strtolower(ereg_replace('[^a-zA-Z0-9]','',$str_piVar));
        // - 111023, dwildt, #31200
        //$str_piVar  = strtolower(preg_replace('/[^a-zA-Z0-9]*/','',$str_piVar));
        // + 111023, dwildt, #31200
      $str_piVar  = strtolower(preg_replace('/[^a-zA-Z0-9-_]*/','',$str_piVar));
      $str_class  = $str_piVar;

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
          if(($this->pObj->b_drs_localisation || $this->pObj->b_drs_navi) && !$boolPrompt_1)
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
        $arr_addPiVars = array('indexBrowserTab' => $str_piVar);
        if($bool_dontLinkDefaultTab)
        {
          $tmp_indexBrowserTab = false;
          if($str_piVar == $defaultAzTab)
          {
            if(isset($this->pObj->piVars['indexBrowserTab']))
            {
              $tmp_indexBrowserTab = $this->pObj->piVars['indexBrowserTab'];
              unset($this->pObj->piVars['indexBrowserTab']);
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
          if($tmp_indexBrowserTab)
          {
            $this->pObj->piVars['indexBrowserTab'] = $tmp_indexBrowserTab;
          }
        }
      }

      if($arr_tab['amount'] > 1)
      {
        // The tab has two items at least: link the tab!
        if($tsDisplayTitleTag)
        {
          $title = htmlspecialchars($this->pObj->pi_getLL('browserItems', 'Items', true));
          if(($this->pObj->b_drs_localisation || $this->pObj->b_drs_navi) && !$boolPrompt_2)
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
        $arr_addPiVars = array('indexBrowserTab' => $str_piVar);
        if($bool_dontLinkDefaultTab)
        {
          $tmp_indexBrowserTab = false;
          if($str_piVar == $defaultAzTab)
          {
            if(isset($this->pObj->piVars['indexBrowserTab']))
            {
              $tmp_indexBrowserTab = $this->pObj->piVars['indexBrowserTab'];
              unset($this->pObj->piVars['indexBrowserTab']);
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
          if($tmp_indexBrowserTab)
          {
            $this->pObj->piVars['indexBrowserTab'] = $tmp_indexBrowserTab;
          }
        }
      }

      if ($arr_tab['amount'] == 0 && $arr_tab['displayWithoutItems'])
      {
        // The tab hasn't any items but should displayed. Display the tab but without any link!
        $markerArray['###TAB###'] = $str_label;
      }

      $markerArray['###LI_CLASS###'] = $liClass;
      $tmplIndexBrowserTabs = $this->pObj->cObj->getSubpart($template, '###INDEXBROWSERTABS###');
      $tabs .= $this->pObj->cObj->substituteMarkerArray($tmplIndexBrowserTabs, $markerArray);
    }


    //////////////////////////////////////////////
    //
    // Process the markers, subpart and template

    unset($markerArray);
    $markerArray                  = $this->pObj->objWrapper->constant_markers();
    $markerArray['###UL_MODE###'] = $this->mode;
    $markerArray['###UL_VIEW###'] = $this->view;
    $tmplIndexBrowser  = $this->pObj->cObj->getSubpart($template, '###INDEXBROWSER###');
    $tmplIndexBrowser  = $this->pObj->cObj->substituteMarkerArray($tmplIndexBrowser, $markerArray);
    $tmplIndexBrowser  = $this->pObj->cObj->substituteSubpart($tmplIndexBrowser, '###INDEXBROWSERTABS###', $tabs, true);
    $template       = $this->pObj->cObj->substituteSubpart($template, '###INDEXBROWSER###', $tmplIndexBrowser, true);
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
  * @param	array		Array with elements indexBrowserRows and rows
  * @return	array		Array data with elements indexBrowserTabArray, tabIds and rows
  * @version        3.4.3
  */
  public function indexBrowserTabArray($arr_data)
  {
    $indexBrowserRows                           = $arr_data['indexBrowserRows'];
    $rows                             = $arr_data['rows'];
    $arr_return['data']['indexBrowserTabArray'] = false;
    $arr_return['data']['tabIds']     = false;
    $arr_return['data']['rows']       = $rows;


    //////////////////////////////////////////////////////
    //
    // Initial Values

    $int_indexBrowserRows_all     = 0;
    $int_indexBrowserRows_others  = 0;
    $int_indexBrowserRows_user    = 0;
    if(is_array($indexBrowserRows)) {
      $int_indexBrowserRows_all     = count($indexBrowserRows);
      $int_indexBrowserRows_others  = count($indexBrowserRows);
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

    $conf_tabs = $this->conf['navigation.']['indexBrowser.']['tabs.'];
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 1: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
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
    $int_key_defaultTab   = $this->conf['navigation.']['indexBrowser.']['defaultTab'];
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
        $str_wrap = $this->conf['navigation.']['indexBrowser.']['defaultTabWrap'];
      }
      $lArrTabs[$key_lArrTab]['wrap'] = $str_wrap;
      // wrap

        // active status
        // - 111023, dwildt/uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
        //$str_label_cleaned = t3lib_div::convUmlauts(strip_tags(html_entity_decode($str_label)));
        // + 111023, dwildt/uherrmann, #9912
      $str_label_cleaned = $this->t3lib_cs_obj->specCharsToASCII( $this->bool_utf8, strip_tags( html_entity_decode( $str_label ) ) );

        // #9708 fsander
        // - 111023, dwildt, #31200
        //$str_label_cleaned = strtolower(preg_replace('[^a-zA-Z0-9]','',$str_label_cleaned));
        // + 111023, dwildt, #31200
      $str_label_cleaned = strtolower(preg_replace('[^a-zA-Z0-9-_]','',$str_label_cleaned));

      $lArrTabs[$key_lArrTab]['active'] = false;
      if($str_label_cleaned == $this->pObj->piVar_indexBrowserTab)
      {
        $lArrTabs[$key_lArrTab]['active'] = true;
        $arr_tsId['active']               = $key_lArrTab;
      }
        // #10054
      if((strtolower($this->pObj->piVar_indexBrowserTab) == $str_label_cleaned) && ($int_key_defaultTab == $key_lArrTab))
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
        $lArrTabs[$key_lArrTab]['displayWithoutItems'] = $this->conf['navigation.']['indexBrowser.']['display.']['tabWithoutItems'];
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 2: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // Build the $lArrTabs - Step 3: Count the rows

    $rows_others        = $indexBrowserRows;
    $int_initialsUser   = 0;
    $bool_caseSensitive = $this->conf['navigation.']['indexBrowser.']['caseSensitive'];

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
          if (is_array($indexBrowserRows))
          {
            foreach ($indexBrowserRows as $row => $elements)
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After tab array - step 3: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // Count the rows for ALL and OTHERS

    if(is_array($indexBrowserRows)) {
      $int_initialsAll = count($indexBrowserRows);
    }
    if(!is_array($indexBrowserRows)) {
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
      if (is_array($indexBrowserRows) && is_array($arr_displayRow))
      {
        foreach ($indexBrowserRows as $row => $elements)
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
        t3lib_div::devlog('[INFO/TEMPLATING] The index browser has #'.$removed_rows.' rows removed.',  $this->pObj->extKey, 0);
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
      t3lib_div::devLog('[INFO/PERFORMANCE] Remove waste rows: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    //////////////////////////////////////////////////////
    //
    // RETURN the result

    $arr_return['data']['indexBrowserTabArray'] = $lArrTabs;
    $arr_return['data']['tabIds']     = $arr_tsId;
    $arr_return['data']['rows']       = $rows;

    return $arr_return;
    // RETURN the result
  }

















 /**
  * Building the SQL query for the index browser. Exxecute the query. Return the rows.
  *
  * @param	array		Array with the current rows
  * @return	array		Array data with the element indexBrowserRows
  */
  public function indexBrowserRowsInitial($arr_data)
  {
    $arr_return['error']['status']  = false;
    $arr_return['data']['indexBrowserRows']   = false;
    $rows                           = $arr_data['rows'];


    ///////////////////////////////////////////////
    //
    // RETURN if we got an empty result

    if (!is_array($rows) || (is_array($rows) && count($rows) < 1))
    {
      $arr_return['data']['indexBrowserRows'] = false;
      return $arr_return;
    }
    // RETURN if we got an empty result


    ///////////////////////////////////////////////
    //
    // Get the table.field for the indexBrowser initials

    if (isset($this->conf_view['navigation.']['indexBrowser.']['field']))
    {
      $str_initialField = $this->conf_view['navigation.']['indexBrowser.']['field'];
      if ($this->pObj->b_drs_navi) {
        t3lib_div::devlog('[INFO/NAVIGATION] '.$this->conf_path.'indexBrowser.field is '.$str_initialField, $this->pObj->extKey, 0);
      }
    }
    if (!$str_initialField)
    {
      $str_initialField = $this->conf['navigation.']['indexBrowser.']['field'];
      if ($str_initialField)
      {
        // The user has defined a table.field element
        if ($this->pObj->b_drs_navi) {
          t3lib_div::devlog('[INFO/NAVIGATION] indexBrowser.field is '.$str_initialField, $this->pObj->extKey, 0);
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
      if ($this->pObj->b_drs_navi)
      {
        $prompt = 'Default: indexBrowser.field is the first table.field from '.$this->conf_path.'select: '.$str_initialField;
        t3lib_div::devlog('[INFO/NAVIGATION] '.$prompt, $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/NAVIGATION] If you need another table.field use '.$this->conf_path.'indexBrowser.field please.', $this->pObj->extKey, 1);
      }
    }
    $this->sql_initialField = $str_initialField;
    // Get the table.field for the indexBrowser initials


    ///////////////////////////////////////////////
    //
    // ERROR if table isn't the local table

    list($table, $field) = explode('.', $str_initialField);
    if ($table != $this->pObj->localTable)
    {
      $str_prompt = '[ERROR/NAVIGATION] indexBrowser field isn\'t a field from the local table:<br />
        table.field: '.$str_initialField.'<br />
        localtable: '.$this->pObj->localTable.'<br />
        <br />
        Please configure:<br />
        '.$this->conf_path.'indexBrowser.field = '.$this->pObj->localTable.'... or<br />
        indexBrowser.field = '.$this->pObj->localTable.'...';
      if ($this->pObj->b_drs_navi)
      {
        t3lib_div::devlog($str_prompt, $this->pObj->extKey, 3);
        t3lib_div::devlog('[INFO/NAVIGATION] index browser won\'t be processed.', $this->pObj->extKey, 0);
      }

      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error index browser</h1>';
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
        $tableFieldIndexBrowser     = $real_tableField;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] Synonyms: index browser takes the current rows.', $this->pObj->extKey, 0);
        }
      }
      if (!$this->bool_synonyms)
      {
        $tableFieldUid  = $table.'.uid';
        $tableFieldIndexBrowser   = $str_initialField;
      }
      foreach ($rows as $int_row => $elements)
      {
        $indexBrowserRows[$int_row][$tableFieldUid] = $elements[$tableFieldUid];
        $indexBrowserRows[$int_row][$tableFieldIndexBrowser]  = $elements[$tableFieldIndexBrowser];
      }
      $arr_return['data']['indexBrowserRows'] = $indexBrowserRows;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] Synonyms: index browser process the current rows.', $this->pObj->extKey, 0);
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
      t3lib_div::devlog('[INFO/TEMPLATING] index browser query<br />
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

    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      return $this->pObj->objSqlFun->prompt_error( );
    }
//    if ($error != '')
//    {
//      if ($this->pObj->b_drs_error)
//      {
//        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
//      }
//      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
//      if ($this->pObj->b_drs_error)
//      {
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
//      }
//      else
//      {
//        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">'.$this->pObj->pi_getLL('drs_sql_prompt').'</p>';
//      }
//      $arr_return['error']['status'] = true;
//      $arr_return['error']['header'] = $str_warn.$str_header;
//      $arr_return['error']['prompt'] = $str_prompt;
//      return $arr_return;
//    }
//    // DRS - Development Reporting System


    ///////////////////////////////////////////////
    //
    // Building the indexBrowser rows

    $i_counter    = 0;
    $tmp_initial  = array();
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
    {
      $indexBrowserRows[] = $row;
    }
    // Building the indexBrowser rows


    ///////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_templating)
    {
      $int_rows = count($indexBrowserRows);
      t3lib_div::devlog('[INFO/TEMPLATING] index browser has #'.$int_rows.' rows.',  $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System


    ///////////////////////////////////////////////
    //
    // SQL Free Result

    $GLOBALS['TYPO3_DB']->sql_free_result($res);
    // SQL Free Result


    ///////////////////////////////////////////////
    //
    // RETURN the index browser rows

    $arr_return['data']['indexBrowserRows'] = $indexBrowserRows;
    return $arr_return;
    // RETURN the index browser rows
  }









    /***********************************************
    *
    * pagebrowser
    *
    **********************************************/









 /**
  * Building the page browser. Returns the HTML template
  *
  * @param	array		Array with elements template and display
  * @return	string		template
  */
  public function tmplPageBrowser($arr_data)
  {

    $int_currTab    = $arr_data['tabIds']['active'];
    $arr_currRowIds = $arr_data['indexBrowserTabArray'][$int_currTab]['keyRow'];

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
 * @return	array		Array with the modeSelector names
 */
  public function prepaireModeSelector()
  {

    $arr_return = array();
    $arr_return['error']['status'] = false;



      ///////////////////////////////////////////////
      //
      // RETURN with an error, if there are no views

    if( ! is_array( $this->conf['views.'] ) )
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
    if( $langKey == 'en' )
    {
      $langKey = 'default';
    }

    foreach( (array) $this->conf['views.'][$this->view . '.'] as $keyView => $arrView )
    {
      // We don't need the typoscript array dot
      $mode                       = substr( $keyView, 0, strlen( $keyView ) - 1 );
      $llMode                     = $this->pObj->pi_getLL( $this->view.'_mode_' . $mode, $mode    );
      $arr_return['data'][$mode]  = $this->pObj->pi_getLL( $this->view.'_mode_' . $mode, $llMode  );
      if ( $this->pObj->b_drs_localisation && $mode == $llMode )
      {
        t3lib_div::devlog( '[WARN/LOCALLANG] ' . $this->conf_path . ' hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
        $prompt = 'Please configure _LOCAL_LANG.'.$langKey.'.'.$this->view.'_mode_'.$mode.'.';
        t3lib_div::devlog('[HELP/LOCALLANG] '.$prompt, $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System

    return $arr_return;
  }









 /**
  * Building the mode selector HTML code.
  *
  * @param	array		Array with the template and the mode selector tabs
  * @return	string		template
  */
  public function tmplModeSelector($arr_data)
  {

    $template   = $arr_data['template'];
    $arr_items  = $arr_data['arrModeItems'];



      /////////////////////////////////////
      //
      // Without items don't display any tabs

    if (count($arr_items) <= 1) {
        // We don't have a mode selector
      $template = $this->pObj->cObj->substituteSubpart($template, '###MODESELECTOR###', '', true);
      if ($this->pObj->b_drs_navi) {
        t3lib_div::devlog('[INFO/NAVIGATION] RETURN. There isn\'t any item for the mode selector.', $this->pObj->extKey, 0);
      }
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
        // 110825, dwildt-
      //$class            = $this->mode == $str_item_key ? ' class="'.$tabClass.' selected"' : ' class="'.$tabClass.'"';
        // 110825, dwildt+
      switch( true )
      {
        case( $this->mode == $str_item_key ) :
          $class                                  = ' class="'.$tabClass.' selected"';
          $markerArray['###UI-STATE-ACTIVE###']   = ' ui-state-active';
          $markerArray['###UI-TABS-SELECTED###']  = ' ui-tabs-selected';
          break;
        default:
          $class                                  = ' class="'.$tabClass.'"';
          $markerArray['###UI-STATE-ACTIVE###']   = null;
          $markerArray['###UI-TABS-SELECTED###']  = null;
      }
      $class            = $this->mode == $str_item_key ? ' class="'.$tabClass.' selected"' : ' class="'.$tabClass.'"';
        // 110825, dwildt+
      $str_item_value   = htmlspecialchars($str_item_value);
      if ($this->conf['navigation.']['modeSelector.']['wrap'] != '') {
        $str_item_value = str_replace('|', $str_item_value, $this->conf['navigation.']['modeSelector.']['wrap']);
      }
      $item             = $this->pObj->pi_linkTP_keepPIvars($str_item_value, array('mode' => $str_item_key), $this->pObj->boolCache);
      $markerArray['###CLASS###'] = $class;
      $markerArray['###TABS###']  = $item;
      $markerArray['###MODE###']  = $str_item_key;
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

//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->piVars);
//      die( );
//    }
    return $template;
      // Building and Return the template

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
    $dummy = $this->pObj->objViewlist->main($this->pObj->str_template_raw);
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
  * @param	array		$rows: Array with all available rows of the list view in order of the list view
  * @return	array		$arr_return: false in case of success, otherwise array with an error message
  * @version 3.7.0
  * @since 3.7.0
  */
  public function recordbrowser_set_session_data($rows)
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

    if(empty($rows))
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_4x.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_4x.php']);
}

?>
