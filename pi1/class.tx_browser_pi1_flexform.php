<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009-2011 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 *   68: class tx_browser_pi1_flexform
 *  165:     function __construct($parentObj)
 *  199:     function main()
 *
 *              SECTION: piVars
 *  317:     function prepare_piVars()
 *  550:     function prepare_mode()
 *
 *              SECTION: Fields with Priority
 *  629:     function sheet_sDEF_views()
 *
 *              SECTION: Sheets
 * 1038:     function sheet_advanced()
 * 1238:     function sheet_development()
 * 1319:     function sheet_javascript()
 * 1480:     function sheet_sDEF()
 * 1730:     function sheet_socialmedia()
 * 1851:     function sheet_tca()
 * 1928:     function sheet_templating()
 * 2040:     function sheet_viewList()
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


 /**
  * The class tx_browser_pi1_flexform bundles all methods for the flexform but any wizard.
  * See Wizards in the wizard class.
  *
  * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
  * @package    TYPO3
  * @subpackage    browser
  * @version 3.7.0
  * @since  2.0.0
  */
class tx_browser_pi1_flexform
{
  /////////////////////////////////////////////////
  //
  // Vars set by methods in the current class

  var $mode = false;
  // [integer] The ID of the current mode/view

  //[general]
  var $int_viewsListPid                   = false;
  // [integer] pid of the result page

  //[list view]
  var $int_singlePid                      = null;
  // [integer] pid of a single record, #12006

  var $bool_azBrowser                     = true;
  // [boolean] Display the A-Z-Browser
  var $bool_pageBrowser                   = true;
  // [boolean] Display the PageBrowser
  var $bool_emptyAtStartbool_emptyAtStart                  = false;
  // [boolean] Display an empty list at start
  var $bool_dontHandleEmptyValues         = true;
  // [boolean] Don't handle empty records in list views and don't handle empty fields in single views
  var $bool_searchForm                    = true;
  // [boolean] Display the Searchbox
  var $bool_searchForm_wiPhrase           = true;
  // [boolean] Display the Searchbox Phrase
  var $bool_searchForm_wiColoredSwords    = true;
  // [boolean] Display Colored Swords (list view)
  var $bool_searchForm_wiColoredSwordsSingle   = false;
  // [boolean] Display Colored Swords (single view)
  var $bool_searchWildcardsManual         = false;
  // [boolean] Display Wildcard Phrase
  var $str_searchWildcardCharManual       = '*';
  // [string] Display the Searchbox

  var $bool_linkToSingle_wi_piVar_azTab   = false;
  // [boolean] Should the URL to a single view contain the parameter azTab?
  var $bool_linkToSingle_wi_piVar_mode    = false;
  // [boolean] Should the URL to a single view contain the parameter mode?
  var $bool_linkToSingle_wi_piVar_pointer = false;
  // [boolean] Should the URL to a single view contain the parameter pointer?
  var $bool_linkToSingle_wi_piVar_plugin  = true;
  // [boolean] Should the URL to a single view contain the parameter plugin?
  var $bool_linkToSingle_wi_piVar_sort    = false;
  // [boolean] Should the URL to a single view contain the parameter sort?

    //[javascript]
    // #9659, 101013 fsander
  var $bool_ajax_enabled                  = false;
    // [boolean] AJAX enabled?
  var $bool_ajax_single                   = false;
    // [boolean] AJAX also used for single view?
  var $str_ajax_list_transition           = false;
    // [string] AJAX transition for list view
  var $str_ajax_single_transition         = false;
    // [string] AJAX transition for single view
  var $str_ajax_list_on_single            = false;
    // [string] AJAX mode for list in single view
    // #9659, 101013 fsander
  var $str_browser_libraries              = 'typoscript';
  var $str_jquery_library                 = 'typoscript';
    // #28562, 110804, dwildt
  var $bool_jquery_ui                     = false;
    // [boolean] jQuery UI enabled

  //[socialmedia]
  var $str_socialmedia_bookmarks_enabled                = false;
  // [boolean] Are socalmedia bookmarks enabled?
  var $str_socialmedia_bookmarks_tableFieldSite_list    = false;
  // [string] tableField for the site of the bookmark links
  var $str_socialmedia_bookmarks_tableFieldTitle_list   = false;
  // [string] tableField for the tile property of bookmark links
  var $str_socialmedia_bookmarks_tableFieldSite_single  = false;
  // [string] tableField for the site of the bookmark links
  var $str_socialmedia_bookmarks_tableFieldTitle_single = false;
  // [string] tableField for the tile property of bookmark links
  var $strCsv_socialmedia_bookmarks_list                = false;
  // [string] csvList with the keys of the bookmars in in the TypoScript, which should displayed in list views
  var $strCsv_socialmedia_bookmarks_single              = false;
  // [string] csvList with the keys of the bookmars in in the TypoScript, which should displayed in single views
  //[socialmedia]

  //[templating]
  var $int_templating_dataQuery   = false;
  // [int] key of the dataQuery in the TypoScript, which should added in list views
  var $bool_wrapInBaseClass       = true;
  // [boolean] wrap the plugin in with pi_wrapInBaseClass
  //[templating]

  // Vars set by methods in the current class











/**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;

  }























/**
 * Process the values from the pi_flexform field. Process each sheet. Allocates values to TypoScript.
 *
 * @return  void
 * @version 3.7.0
 */
  function main()
  {

      //////////////////////////////////////////////////////////////////////
      //
      // Init methods for pi_flexform

    $this->pObj->pi_initPIflexForm();
      // Init methods for pi_flexform


      //////////////////////////////////////////////////////////////////////
      //
      // Development

      // Display values from pi_flexform as an tree
    if (1 == 0)
    {
      $treeDat = $this->pObj->cObj->data['pi_flexform'];
      $treeDat = t3lib_div::resolveAllSheetsInDS($treeDat);
      var_dump(t3lib_div::view_array($treeDat));
    }
      // Display values from pi_flexform as an tree
      // Development



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System
  
    if ($this->pObj->b_drs_flexform)
    {
      $str_header     = $this->pObj->cObj->data['header'];
      $int_uid        = $this->pObj->cObj->data['uid'];
      $int_pid        = $this->pObj->cObj->data['pid'];
      t3lib_div::devlog('[INFO/FLEXFORM] \''.$str_header.'\' (pid: '.$int_pid.', uid: '.$int_uid.')', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////
      //
      // Sheet development controlls the DRS

    $this->sheet_development();
      // Sheet development controlls the DRS



      //////////////////////////////////////////////////////////////////////
      //
      // Init Language

    if(!$this->pObj->lang)
    {
      $this->pObj->objZz->initLang();
    }
      // Init Language



      //////////////////////////////////////////////////////////////////////
      //
      // Prepare piVars

    $this->prepare_piVars();
      // Prepare piVars



      //////////////////////////////////////////////////////////////////////
      //
      // Process Fields with Priority

    $this->sheet_sDEF_views();
      // Process Fields with Priority



      //////////////////////////////////////////////////////////////////////
      //
      // Process the Sheets

    $this->sheet_javascript();
    $this->sheet_sDEF();
    $this->sheet_viewList();
    $this->sheet_viewSingle();
    $this->sheet_socialmedia();
      // #9689
    //$this->sheet_templating();
    $this->sheet_tca();
    $this->sheet_advanced();
      // Process the Sheets

  }









  /***********************************************
   *
   * piVars
   *
   **********************************************/




/**
 * Changes the piVars array, if there is more than one plugin on the current page.
 * If there is, the piVars[plugin] with the uid of the current plugin is added to the piVars.
 * If the visitor of the page hasn't selected the current plugin, all piVars will be removed.
 *
 * @return  void
 */
  function prepare_piVars()
  {

    //////////////////////////////////////////////////////////////////////
    //
    // Get the field names for sys_language_content and for l10n_parent

    $str_langField  = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
    $str_langPid    = $GLOBALS['TCA']['tt_content']['ctrl']['transOrigPointerField'];
    // Get the field names for sys_language_content and for l10n_parent


    //////////////////////////////////////////////////////////////////////
    //
    // Build and execute the SQL query

    $pid            = $this->pObj->cObj->data['pid'];

    $select_fields  = "uid, header, ".$str_langField.", ".$str_langPid;
    $from_table     = "tt_content";
    $where_enable   = $this->pObj->cObj->enableFields($from_table);
    $where_locale   = $this->pObj->objLocalize->localizationFields_where($from_table);
    if(!$where_locale)
    {
      $where_locale = 1;
    }
    $where_clause   = "pid = ".$pid." ".
                      "AND CType = 'list' ".
                      "AND list_type = '".$this->pObj->extKey."_pi1' ".$where_enable." ".
                      "AND ".$where_locale;

    // For Development
    if (1==0)
    {
      $query = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='');
      t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
    }
    // For Development

    $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='',$uidIndexField='');
    // Build and execute the SQL query


    //////////////////////////////////////////////////////////////////////
    //
    // Consolidate the Rows in case of Localization

    $arr_rm_langParents = false;
    if(count($rows) > 1)
    {
      foreach((array) $rows as $row => $elements)
      {
        // We have a localized record
        if($elements[$str_langPid] > 0)
        {
          $arr_rm_langParents[] = $elements[$str_langPid];
        }
        // We have a localized record
      }
    }
    if(is_array($arr_rm_langParents))
    {
      foreach((array) $rows as $row => $elements)
      {
        // Rempve the default language record
        if(in_array($elements['uid'], $arr_rm_langParents))
        {
          unset($rows[$row]);
        }
        // Rempve the default language record
      }
    }
    //var_dump($rows);
    // Consolidate the Rows in case of Localization


    //////////////////////////////////////////////////////////////////////
    //
    // RETURN, if we have one plugin on the page only

    if(count($rows) <= 1)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] There is only one plugin on the page. There isn\'t any effect for any piVar.', $this->pObj->extKey, 0);
      }
      return;
    }
    // RETURN, if we have one plugin on the page only



    //////////////////////////////////////////////////////////////////////
    //
    // RETURN, if plugin want to handle piVars of foreign plugin

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_piVars     = $this->pObj->pi_getFFvalue($arr_piFlexform, 'piVars', 'sDEF', 'lDEF', 'vDEF');
    switch($str_piVars)
    {
      case('all'):
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] Current plugin wants to handle all piVars.', $this->pObj->extKey, 0);
        }
        return;
        break;
      case('default'):
      case(false):
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] Current plugin wants to handle only own piVars.', $this->pObj->extKey, 0);
        }
        break;
      default:
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[WARN/FLEXFORM] Current plugin has an undefined value in piVars. '.
            'Definded is: default, all. Current value is: '.$str_piVars, $this->pObj->extKey, 2);
        }
    }
    // RETURN, if plugin want to handle piVars of foreign plugin



    //////////////////////////////////////////////////////////////////////
    //
    // We have more than one plugin on the page

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] There is more than one plugin on the page.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    // Allocates uids
    $uid_plugin_selected = $this->pObj->cObj->data['uid'];
    $uid_plugin_current  = $this->pObj->piVars['plugin'];
    // Allocates uids
    // We have more than one plugin on the page


    //////////////////////////////////////////////////////////////////////
    //
    // Remove piVars

    // The current plugin isn't the plugin, which is used by the visitor
    $bool_unset_piVars = false;
    if($uid_plugin_current)
    {
      if($uid_plugin_selected != $uid_plugin_current)
      {
        $bool_unset_piVars = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The visitor hasn\'t selected the current plugin.<br />
            Id of the current plugin: '.$uid_plugin_current.'<br />
            Id of the selected plugin: '.$uid_plugin_selected.'<br />
            All piVars for the current plugin are removed!', $this->pObj->extKey, 0);
        }
      }
      if($uid_plugin_selected == $uid_plugin_current)
      {
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The visitor has selected the current plugin.<br />
            Id of the current plugin: '.$uid_plugin_current.'<br />
            Id of the selected plugin: '.$uid_plugin_selected.'<br />
            No piVar for the current plugin is removed!', $this->pObj->extKey, 0);
        }
      }
    }
    if(!$uid_plugin_current)
    {
      $bool_unset_piVars = true;
      if ($this->pObj->b_drs_flexform)
      {
        $csv_piVars_keys = implode(', ', array_keys($this->pObj->piVars));
        t3lib_div::devlog('[INFO/FLEXFORM] The visitor hasn\'t selected any plugin.<br />
          Id of the current plugin: NULL<br />
          Id of the selected plugin: '.$uid_plugin_selected.'<br />
          Keys of the piVars: '.$csv_piVars_keys.'<br />
          All piVars for the current plugin are removed!', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/FLEXFORM] If the plugin should handle the piVars,
          please configure in the plugin [General]: handle piVars from foreign plugins!',
          $this->pObj->extKey, 1);
      }
    }
    if($bool_unset_piVars)
    {
      unset($this->pObj->piVars);
    }
    // The current plugin isn't the plugin, which is used by the visitor
    // Remove piVars


    //////////////////////////////////////////////////////////////////////
    //
    // Add piVar[plugin]

    // cweiske: we do not need the original value anymore, but need the plugin id
    // in the template marker array
    
    $this->pObj->piVars['plugin'] = $uid_plugin_selected;
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] piVars[plugin] = '.$uid_plugin_selected.' is added to the array piVars.', $this->pObj->extKey, 0);
    }
    // Add piVar[plugin]

  }


















/**
 * Set the class var mode. It is the current mode/view.
 * The code is corresponding with the mode snippet in tx_brwoser_pi1_zz::prepairePiVars() !!!
 *
 * @return  void
 */
  function prepare_mode()
  {

    //////////////////////////////////////
    //
    // Security

    $this->mode = false;
    if (isset($this->pObj->piVars['mode']))
    {
      $this->mode = $this->pObj->objZz->secure_piVar($this->pObj->piVars['mode'], 'integer');
    }
    // Security


    //////////////////////////////////////
    //
    // Set the global piVar_mode

    if (!$this->mode)
    {
      if (is_array($this->pObj->conf['views.']['list.']))
      {
        reset($this->pObj->conf['views.']['list.']);
        $firstKeyWiDot  = key($this->pObj->conf['views.']['list.']);
        $firstKeyWoDot  = substr($firstKeyWiDot, 0, strlen($firstKeyWiDot) - 1);
        $this->mode           = $firstKeyWoDot;
      }
      if (!is_array($this->pObj->conf['views.']['list.']))
      {
        $this->mode = $this->pObj->piVars['mode'];
      }
    }
    // Set the global piVar_mode


    //////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] list id (mode): \''.$this->mode.'\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System


  }
















 /***********************************************
  *
  * Fields with Priority
  *
  **********************************************/




/**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 */
  function sheet_sDEF_views()
  {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];

    //////////////////////////////////////////////////////////////////////
    //
    // Field views

    $str_views_status = $this->pObj->pi_getFFvalue($arr_piFlexform, 'views', 'sDEF', 'lDEF', 'vDEF');

    // Return, if views have the default status
    if($str_views_status == 'all')
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] sDEF/views: \'all\'. Nothing to do.', $this->pObj->extKey, 0);
      }
      $this->prepare_mode();
      // Prepare Mode
      return;
    }
    // Return, if views have the default status
    // Field views


    //////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] sDEF/views: \''.$str_views_status.'\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System



// #9683
//    //////////////////////////////////////////////////////////////////////
//    //
//    // Field viewsIds
//
//    $bool_viewsIds = true;
//    $str_views_csv  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF');
//
//    // If viewsIds is empty, do nothing
//    if ($str_views_csv == '')
//    {
//      $bool_viewsIds = false;
//      if ($this->pObj->b_drs_flexform)
//      {
//        t3lib_div::devlog('[INFO/FLEXFORM] sDEF/viewsIds is empty. Nothing to do.', $this->pObj->extKey, 0);
//      }
//    }
//    // If viewsIds is empty, do nothing
//
//    // Remove every id, which isn't proper
//    if($bool_viewsIds)
//    {
//      $arr_viewsIds         =  $this->pObj->objZz->getCSVasArray($str_views_csv);
//      $arr_viewsIds_proper  = false;
//      // Remove every id, which isn't proper
//      foreach((array) $arr_viewsIds as $key => $value)
//      {
//        if (in_array($value.'.', array_keys($this->pObj->conf['views.']['list.'])))
//        {
//          $arr_viewsIds_proper[] = $value.'.';
//        }
//      }
//      // Remove every id, which isn't proper
//      if (is_array($arr_viewsIds_proper))
//      {
//        $arr_viewsIds_proper = array_unique($arr_viewsIds_proper);
//      }
//      if (!is_array($arr_viewsIds_proper))
//      {
//        $bool_viewsIds = false;
//        if ($this->pObj->b_drs_flexform)
//        {
//          $str_prompt = implode(', ', $arr_viewsIds);
//          t3lib_div::devlog('[WARN/FLEXFORM] sDEF/viewsIds hasn\'t any proper views.list id: \''.$str_prompt.'\'', $this->pObj->extKey, 2);
//          $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
//          $str_prompt = str_replace('..', ', ', $str_prompt);
//          $str_prompt = str_replace('.', '', $str_prompt);
//          t3lib_div::devlog('[HELP/FLEXFORM] Proper values would be: \''.$str_prompt.'\'', $this->pObj->extKey, 1);
//        }
//      }
//    }
//    // Remove every id, which isn't proper
//
//    // Remove every view, which isn't element of the id list
//    if($bool_viewsIds)
//    {
//      $arr_keyslistViews  = array_keys($this->pObj->conf['views.']['list.']);
//      foreach ($arr_keyslistViews as $key => $value)
//      {
//        if(!in_array($value, $arr_viewsIds_proper))
//        {
//          // Remove list view
//          // Remove array
//          unset($this->pObj->conf['views.']['list.'][$value]);
//          // Remove string
//          $valueWoDot = substr($value, 0, strlen($value) - 1);
//          unset($this->pObj->conf['views.']['list.'][$value]);
//          if ($this->pObj->b_drs_flexform)
//          {
//            t3lib_div::devlog('[INFO/FLEXFORM] views.list.'.$valueWoDot.' is removed from TypoScript.', $this->pObj->extKey, 0);
//          }
//          // Remove list view
//
//          // Remove single view
//          // Remove array
//          unset($this->pObj->conf['views.']['single.'][$value]);
//          // Remove string
//          $valueWoDot = substr($value, 0, strlen($value) - 1);
//          unset($this->pObj->conf['views.']['single.'][$value]);
//          if ($this->pObj->b_drs_flexform)
//          {
//            t3lib_div::devlog('[INFO/FLEXFORM] views.list.'.$valueWoDot.' is removed from TypoScript.', $this->pObj->extKey, 0);
//          }
//          // Remove single view
//        }
//      }
//      //var_dump($arr_viewsIds_proper);
//    }
//    if ($this->pObj->b_drs_flexform)
//    {
//      $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
//      $str_prompt = str_replace('..', ', ', $str_prompt);
//      $str_prompt = str_replace('.', '', $str_prompt);
//      t3lib_div::devlog('[INFO/FLEXFORM] This views will displayed: \''.$str_prompt.'\'', $this->pObj->extKey, 0);
//    }
//    // Remove every view, which isn't element of the id list
//    // Field viewsIds



    //////////////////////////////////////////////////////////////////////
    //
    // Field viewsList, #9683

    $bool_viewsIds  = true;
    $str_views_csv  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsList', 'sDEF', 'lDEF', 'vDEF');
    // Downgrade to 3.4.1: viewsIds
    if($this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF'))
    {
      $str_views_csv  = $str_views_csv.','.$this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF');
    }
    // Downgrade to 3.4.1: viewsIds

//var_dump('config 734', $str_views_csv);

    // If viewsIds is empty, do nothing
    if ($str_views_csv == '')
    {
      $bool_viewsIds = false;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] sDEF/viewsIds is empty. Nothing to do.', $this->pObj->extKey, 0);
      }
    }
    // If viewsIds is empty, do nothing

    // Remove every id, which isn't proper
    if($bool_viewsIds)
    {
      // Remove every id, which isn't proper
      $arr_viewsList         =  $this->pObj->objZz->getCSVasArray($str_views_csv);
      $arr_viewsList_proper  = false;
      // Remove every id, which isn't proper
      foreach((array) $arr_viewsList as $key => $value)
      {
        if (in_array($value.'.', array_keys($this->pObj->conf['views.']['list.'])))
        {
          $arr_viewsList_proper[] = $value.'.';
        }
      }
      // Remove every id, which isn't proper
      if (is_array($arr_viewsList_proper))
      {
        $arr_viewsList_proper = array_unique($arr_viewsList_proper);
      }
      if (!is_array($arr_viewsList_proper))
      {
        $bool_viewsList = false;
        if ($this->pObj->b_drs_flexform)
        {
          $str_prompt = implode(', ', $arr_viewsList);
          t3lib_div::devlog('[WARN/FLEXFORM] sDEF/viewsList hasn\'t any proper views.list id: \''.$str_prompt.'\'', $this->pObj->extKey, 2);
          $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
          $str_prompt = str_replace('..', ', ', $str_prompt);
          $str_prompt = str_replace('.', '', $str_prompt);
          t3lib_div::devlog('[HELP/FLEXFORM] Proper values would be: \''.$str_prompt.'\'', $this->pObj->extKey, 1);
        }
      }
      // Remove every id, which isn't proper

      // Remove every view, which isn't element of the id list
      $arr_keyslistViews  = array_keys($this->pObj->conf['views.']['list.']);
      foreach ($arr_keyslistViews as $key => $value)
      {
        if(!in_array($value, $arr_viewsList_proper))
        {
          // Remove list view
          // Remove array
          unset($this->pObj->conf['views.']['list.'][$value]);
          // Remove string
          $valueWoDot = substr($value, 0, strlen($value) - 1);
          unset($this->pObj->conf['views.']['list.'][$value]);
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] views.list.'.$valueWoDot.' is removed from TypoScript.', $this->pObj->extKey, 0);
          }
          // Remove list view

          // Remove single view
          // Remove array
          unset($this->pObj->conf['views.']['single.'][$value]);
          // Remove string
          $valueWoDot = substr($value, 0, strlen($value) - 1);
          unset($this->pObj->conf['views.']['single.'][$value]);
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] views.list.'.$valueWoDot.' is removed from TypoScript.', $this->pObj->extKey, 0);
          }
          // Remove single view
        }
      }
      //var_dump($arr_viewsList_proper);
      if ($this->pObj->b_drs_flexform)
      {
        $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
        $str_prompt = str_replace('..', ', ', $str_prompt);
        $str_prompt = str_replace('.', '', $str_prompt);
        t3lib_div::devlog('[INFO/FLEXFORM] This views will displayed: \''.$str_prompt.'\'', $this->pObj->extKey, 0);
      }
      // Remove every view, which isn't element of the id list
    }
    // Field viewsList



    //////////////////////////////////////////////////////////////////////
    //
    // Prepare Mode

    $this->prepare_mode();
    // Prepare Mode



    //////////////////////////////////////////////////////////////////////
    //
    // Field viewsSinglePid

    // Get the single pid from the plugin
    $plugin_singlePid = $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsSinglePid', 'sDEF', 'lDEF', 'vDEF');
    if(!$plugin_singlePid)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewsSinglePid isn\'t set.', $this->pObj->extKey, 0);
      }
    }
    if($plugin_singlePid)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewsSinglePid: \''.$plugin_singlePid.'\'!', $this->pObj->extKey, 0);
      }
    }
    // Get the single pid from the plugin

    // Set the single pid in the local displayList
    if (isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['displayList.']['singlePid']))
    {
      // Get value from TypoScript
      $conf_singlePid = $this->pObj->conf['views.']['list.'][$this->mode.'.']['displayList.']['singlePid'];
      $str_path       = 'views.list.'.$this->mode.'.displayList.singlePid';
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.$conf_singlePid.'\'', $this->pObj->extKey, 0);
      }
      // Get value from TypoScript
      // Set the plugin single pid to the current pageId, if it is empty
      if(!$plugin_singlePid)
      {
        $plugin_singlePid = $GLOBALS['TSFE']->id;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] Plugin SinglePid was empty. It is overriden with the current page id: \''.$plugin_singlePid.'\'!', $this->pObj->extKey, 0);
        }
      }
      // Set the plugin single pid to the current pageId, if it is empty
      // Set value in TypoScript
      $this->pObj->conf['views.']['list.'][$this->mode.'.']['displayList.']['singlePid'] = $plugin_singlePid;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] TypoScript value \''.$conf_singlePid.'\' is overriden with \''.$plugin_singlePid.'\'!', $this->pObj->extKey, 0);
      }
      // Set value in TypoScript
    }
    // Set the single pid in the local displayList

    // Set the single pid in the global displayList
    if (!isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['displayList.']['singlePid']))
    {
      // Get value from TypoScript
      $conf_singlePid = $this->pObj->conf['displayList.']['singlePid'];
      $str_path       = 'displayList.singlePid';
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.$conf_singlePid.'\'', $this->pObj->extKey, 0);
      }
      // Get value from TypoScript
      // Set the plugin single pid to the current pageId, if it is empty
      if(!$plugin_singlePid)
      {
        $plugin_singlePid = $GLOBALS['TSFE']->id;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] Plugin SinglePid was empty. It is overriden with the current page id: \''.$plugin_singlePid.'\'!', $this->pObj->extKey, 0);
        }
      }
      // Set the plugin single pid to the current pageId, if it is empty
      // Set value in TypoScript
      $this->pObj->conf['displayList.']['singlePid'] = $plugin_singlePid;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] TypoScript value \''.$conf_singlePid.'\' is overriden with \''.$plugin_singlePid.'\'!', $this->pObj->extKey, 0);
      }
      // Set value in TypoScript
    }
    // Set the single pid in the global displayList
    // Field viewsSinglePid



    //////////////////////////////////////////////////////////////////////
    //
    // Field searchDestinationId
    // #9458

    // Get the pid for the result page from the plugin
    $int_viewsListPid = $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsListPid', 'sDEF', 'lDEF', 'vDEF');
    if(empty($int_viewsListPid))
    {
      $this->int_viewsListPid = false;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewsListPid isn\'t set.', $this->pObj->extKey, 0);
      }
    }
    if(!empty($int_viewsListPid))
    {
      $this->int_viewsListPid = $int_viewsListPid;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewsListPid: \''.$int_viewsListPid.'\'!', $this->pObj->extKey, 0);
      }
    }
    // Get the pid for the result page from the plugin



    return;
  }
















 /***********************************************
  *
  * Sheets
  *
  **********************************************/
















/**
 * The Sheet advanced has properties for the performance
 *
 * @return  void
 */
  function sheet_advanced()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;

    $conf       = $this->pObj->conf;
    $modeWiDot  = (int) $this->mode.'.';
    $viewWiDot  = $this->pObj->view.'.';
    $conf_view  = $this->pObj->conf['views.'][$viewWiDot][$modeWiDot];


    //////////////////////////////////////////////////////////////////////
    //
    // Field performance_select

    $str_performance_select = $this->pObj->pi_getFFvalue($arr_piFlexform, 'performance_select', 'advanced', 'lDEF', 'vDEF');

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] advanced/performance_select: \''.$str_performance_select.'\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    // Field performance_select



    //////////////////////////////////////////////////////////////////////
    //
    // Get global or local array advanced

    $bool_advanced_is_local = false;
    if(!empty($conf_view['advanced.']))
    {
      $bool_advanced_is_local = true;
    }
    // Get global or local array advanced



    //////////////////////////////////////////////////////////////////////
    //
    // Field $GLOBALS

    // Default configuration
    if($str_performance_select == 'default')
    {
      #10116
      if($bool_advanced_is_local)
      {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
      }
      if(!$bool_advanced_is_local)
      {
        $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
      }
    }
    // Default configuration

    // Configured by user
    if($str_performance_select != 'default')
    {
      $int_performance_costs  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'performance_costs', 'advanced', 'lDEF', 'vDEF');
      switch ($int_performance_costs) {
        case(0):
          // Set value in TypoScript
          #10116
          if($bool_advanced_is_local)
          {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
          }
          if(!$bool_advanced_is_local)
          {
            $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
          }
          break;
        case(1):
          // Set value in TypoScript
          #10116
          if($bool_advanced_is_local)
          {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 0;
          }
          if(!$bool_advanced_is_local)
          {
            $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 0;
          }
          break;
        default:
          $prompt = '
            <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
              <h1>
                ERROR
              </h1>
              <p>
                Flexform field Searchbox has a value bigger than 7. The value isn\'t defined.<br />
                ' . __METHOD__ . ' (' . __LINE__ . ')
              </p>
            </div>';
          echo $prompt;
          exit;
      }
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] advanced/performance_costs<br />
          look_for_globals: \''.$this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'].'\'', $this->pObj->extKey, 0);
      }
    }
    // Configured by user

    // Field $GLOBALS



    //////////////////////////////////////////////////////////////////////
    //
    // Field realUrl_select

    $str_realUrl_select = $this->pObj->pi_getFFvalue($arr_piFlexform, 'realUrl_select', 'advanced', 'lDEF', 'vDEF');

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] advanced/realUrl_select: \''.$str_realUrl_select.'\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    // Field realUrl_select



    //////////////////////////////////////////////////////////////////////
    //
    // Field realUrl

    // Default configuration
    if($str_realUrl_select == 'default')
    {
      // Do nothing. Take default values from the top of this class..
    }
    // Default configuration

    // Configured by user
    if($str_realUrl_select == 'configured')
    {
      $int_realUrl  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'realUrl', 'advanced', 'lDEF', 'vDEF');

      $this->bool_linkToSingle_wi_piVar_azTab   = (($int_realUrl &  1)  ==  1);
      $this->bool_linkToSingle_wi_piVar_mode    = (($int_realUrl &  2)  ==  2);
      $this->bool_linkToSingle_wi_piVar_pointer = (($int_realUrl &  4)  ==  4);
      $this->bool_linkToSingle_wi_piVar_plugin  = (($int_realUrl &  8)  ==  8);
      $this->bool_linkToSingle_wi_piVar_sort    = (($int_realUrl & 16)  == 16);

      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] advanced/realUrl<br />
          int_realUrl: \''.$int_realUrl.'\'<br />
          linkToSingle_wi_piVar_azTab: \''.$this->bool_linkToSingle_wi_piVar_azTab.'\'<br />
          linkToSingle_wi_piVar_mode: \''.$this->bool_linkToSingle_wi_piVar_mode.'\'<br />
          linkToSingle_wi_piVar_pointer: \''.$this->bool_linkToSingle_wi_piVar_pointer.'\'<br />
          linkToSingle_wi_piVar_plugin: \''.$this->bool_linkToSingle_wi_piVar_plugin.'\'<br />
          bool_linkToSingle_wi_piVar_sort: \''.$this->bool_linkToSingle_wi_piVar_sort.'\'', $this->pObj->extKey, 0);
      }
    }
    // Field searchForm



    return;
  }





















/**
 * Development configuration for the current plugin
 *
 * @return  void
 * @since   3.4.5
 * @version 3.4.5
 */
  function sheet_development()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;
    $modeWiDot      = (int) $this->mode.'.';
    $viewWiDot      = $this->pObj->view.'.';


      //////////////////////////////////////////////////////////////////////
      //
      // Field dontUseDRS

    $this->pObj->bool_dontUseDRS = $this->pObj->pi_getFFvalue($arr_piFlexform, 'dontUseDRS', 'development', 'lDEF', 'vDEF');
      //var_dump('conf 1024', $this->pObj->bool_dontUseDRS);



      //////////////////////////////////////////////////////////////////////
      //
      // Plugin sheet [development]: Don't use the DRS

    if($this->pObj->bool_dontUseDRS)
    {
      if($this->pObj->arr_extConf['drs_mode'] != 'Don\'t log anything')
      {
        t3lib_div::devlog('[INFO/DRS] Plugin Sheet [Development] set the boolean Don\'t use DRS.', $this->pObj->extKey, 0);
        $this->pObj->arr_extConf['drs_mode'] = 'Don\'t log anything';
        $this->pObj->init_drs();
        t3lib_div::devlog('[WARN/DRS] DRS is disabled.', $this->pObj->extKey, 2);
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE]: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // Plugin sheet [development]: Don't use the DRS



      //////////////////////////////////////////////////////////////////////
      //
      // Field debugJSS

    $this->pObj->bool_debugJSS = $this->pObj->pi_getFFvalue($arr_piFlexform, 'debugJSS', 'development', 'lDEF', 'vDEF');
      //var_dump('conf 1024', $this->pObj->bool_debugJSS);



    return;
  }

















/**
 * sheet_javascript(): If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @version 3.7.0
 * @since 3.5.0
 */
  function sheet_javascript()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;



      //////////////////////////////////////////////////////////////////////
      //
      // Field jquery_library
      // #13429, dwildt, 110519

    $this->str_jquery_library = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_library', 'javascript', 'lDEF', 'vDEF');

    switch ($this->str_jquery_library)
    {
      case(false):
      case('typoscript'):
        $this->str_jquery_library = 'typoscript';
        break;
      case('http://code.jquery.com/jquery-1.6.min.js'):
        $this->pObj->conf['javascript.']['jquery.']['library'] = $this->str_jquery_library;
        break;
      case('configured'):
        $str_jquery_library_own = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_library_own', 'javascript', 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['jquery.']['library'] = $str_jquery_library_own;
        break;
      case('none'):
        $this->pObj->conf['javascript.']['jquery.']['library'] = null;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR</h1>
            <p>
              Flexform field jquery_library has an invalid value. The value isn\'t defined.<br />
              value: ' . $this->str_jquery_library . '<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
        'jquery_library: \'' . $this->str_jquery_library . '\'',
        $this->pObj->extKey, 0);
    }
      // #13429, dwildt, 110519
      // Field jquery_library



      //////////////////////////////////////////////////////////////////////
      //
      // Field jquery_ui
      // #28562, dwildt, 110804

    $jquery_ui = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_ui', 'javascript', 'lDEF', 'vDEF');

    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
        'jquery_ui: \'' . $jquery_ui . '\'',
        $this->pObj->extKey, 0);
    }

    switch ($jquery_ui)
    {
      case('black_tie'): 
      case('blitzer'): 
      case('cupertino'): 
      case('dark_hive'): 
      case('darkness'): 
      case('dot_luv'): 
      case('eggplant'): 
      case('excite_bike'): 
      case('flick'): 
      case('hot_sneaks'): 
      case('humanity'): 
      case('le_frog'): 
      case('lightness'): 
      case('mint_choc'): 
      case('netzmacher'): 
      case('overcast'): 
      case('pepper_grinder'): 
      case('redmond'): 
      case('smoothness'): 
      case('south_street'): 
      case('start'): 
      case('sunny'): 
      case('swanky_purse'): 
      case('trontastic'): 
      case('vader'): 
        $ui = $this->pObj->conf['javascript.']['jquery.']['ui.'][$jquery_ui . '.'];
        $this->pObj->conf['javascript.']['jquery.']['ui.']['typoscript.'] = $ui;
        $this->bool_jquery_ui = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript < .' . $jquery_ui, $this->pObj->extKey, 0);
        }
        break;
      case('z_configured'):
        $library  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_ui.z_configured.library', 'javascript', 'lDEF', 'vDEF');
        $css      = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_ui.z_configured.css',     'javascript', 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['jquery.']['ui.']['typoscript.']['library']  = $library;
        $this->pObj->conf['javascript.']['jquery.']['ui.']['typoscript.']['css']      = $css;
        $this->bool_jquery_ui = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript.library is set to '  . $library, $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript.css is set to '      . $css,     $this->pObj->extKey, 0);
        }
        break;
      case('z_none'):
        $this->pObj->conf['javascript.']['jquery.']['ui.']['typoscript.']['library']  = null;
        $this->pObj->conf['javascript.']['jquery.']['ui.']['typoscript.']['css']      = null;
        $this->bool_jquery_ui = false;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript.library is set to null.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript.css is set to null.',     $this->pObj->extKey, 0);
        }
        break;
      case('z_ts'):
        // Do nothing;
        $this->bool_jquery_ui = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] javascript.jquery.ui.typoscript isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR</h1>
            <p>
              Flexform field jquery_ui has an invalid value. The value isn\'t defined.<br />
              value: ' . $jquery_ui . '<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
      // #28562, dwildt, 110804
      // Field jquery_ui



      //////////////////////////////////////////////////////////////////////
      //
      // Field browser_libraries
      // #13429, dwildt, 110519

    $this->str_browser_libraries = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries', 'javascript', 'lDEF', 'vDEF');

    switch ($this->str_browser_libraries)
    {
      case(false):
      case('typoscript'):
        $this->str_browser_libraries = 'typoscript';
        break;
      case('configured'):
        $str_browser_libraries_general  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_general',  'javascript', 'lDEF', 'vDEF');
        $str_browser_libraries_ajax     = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_ajax',     'javascript', 'lDEF', 'vDEF');
        $str_browser_libraries_ajaxLL   = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_ajaxLL',   'javascript', 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['ajax.']['file']     = $str_browser_libraries_general;
        $this->pObj->conf['javascript.']['ajax.']['fileLL']   = $str_browser_libraries_ajax;
        $this->pObj->conf['javascript.']['general.']['file']  = $str_browser_libraries_ajaxLL;
        break;
      case('none'):
        $this->pObj->conf['javascript.']['ajax.']['file']     = null;
        $this->pObj->conf['javascript.']['ajax.']['fileLL']   = null;
        $this->pObj->conf['javascript.']['general.']['file']  = null;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR</h1>
            <p>
              Flexform field browser_libraries has an invalid value. The value isn\'t defined.<br />
              value: ' . $this->str_browser_libraries . '<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
        'browser_libraries: \'' . $this->str_browser_libraries . '\'',
        $this->pObj->extKey, 0);
      if ($this->str_browser_libraries == 'configured')
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
          'browser_libraries_general: \'' . $str_browser_libraries_general . '\'',
          $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
          'browser_libraries_ajax: \'' . $str_browser_libraries_ajax . '\'',
          $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
          'browser_libraries_ajax_ll: \'' . $str_browser_libraries_ajaxLL . '\'',
          $this->pObj->extKey, 0);
      }
    }
      // #13429, dwildt, 110519
      // Field browser_libraries



      //////////////////////////////////////////////////////////////////////
      //
      // Field ajaxuse

    $str_ajax_mode  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'mode', 'javascript', 'lDEF', 'vDEF');

    switch ($str_ajax_mode)
    {
      case(false):
      case('disabled'):
        break;
      case('list_only'):
        $this->bool_ajax_enabled  = 1;
        break;
      case('list_and_single'):
        $this->bool_ajax_enabled  = 1;
        $this->bool_ajax_single   = 1;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR</h1>
            <p>
              Flexform field ajax::mode has an invalid value. The value isn\'t defined.<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
        'AJAX enabled: \''.(int) $this->bool_ajax_enabled.'\'',
        $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
        'AJAX with list and single view: \''.(int) $this->bool_ajax_single.'\'<br />',
        $this->pObj->extKey, 0);
    }
      // Field ajaxuse



      //////////////////////////////////////////////////////////////////////
      //
      // Field list_transition

    if ($this->bool_ajax_enabled)
    {
      $this->str_ajax_list_transition =
        $this->pObj->pi_getFFvalue(
                                    $arr_piFlexform,
                                    'list_transition',
                                    'javascript', 'lDEF', 'vDEF'
                                  );
      if(empty($this->str_ajax_list_transition))
      {
        $this->str_ajax_list_transition = 'collapse';
      }
      if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
          'Transition for list view: \''.$this->str_ajax_list_transition.'\'',
          $this->pObj->extKey, 0);
      }
      if ($this->bool_ajax_single)
      {
        $this->str_ajax_single_transition =
          $this->pObj->pi_getFFvalue(
                                      $arr_piFlexform,
                                      'single_transition',
                                      'javascript', 'lDEF', 'vDEF'
                                    );
        if(empty($this->str_ajax_single_transition))
        {
          $this->str_ajax_single_transition = 'collapse';
        }
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
        {
          t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
            'Transition for single view: \''.$this->str_ajax_single_transition.'\'',
            $this->pObj->extKey, 0);
        }
      }
    }
      // Field list_transition



      //////////////////////////////////////////////////////////////////////
      //
      // Field list_on_single

    if ($this->bool_ajax_enabled)
    {
      if ($this->bool_ajax_single)
      {
        $this->str_ajax_list_on_single =
          $this->pObj->pi_getFFvalue(
                                      $arr_piFlexform,
                                      'list_on_single',
                                      'javascript', 'lDEF', 'vDEF'
                                    );
        if(empty($this->str_ajax_list_on_single))
        {
          $this->str_ajax_list_on_single = 'single';
        }
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
        {
          t3lib_div::devlog('[INFO/FLEXFORM+JSS] '.
            'Single view: \''.$this->str_ajax_list_on_single.'\'',
            $this->pObj->extKey, 0);
        }
      }
    }
      // Field list_on_single



    return;
  }









  /**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @since 2.x.x
 * @version 3.4.4
 */
  function sheet_sDEF()
  {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $modeWiDot  = (int) $this->mode.'.';
    $viewWiDot  = $this->pObj->view.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // Field relations_select

    $relations  = false;
    $joins      = -1;
    $root       = -1;
    $relations_select = $this->pObj->pi_getFFvalue($arr_piFlexform, 'relations_select', 'sDEF', 'lDEF', 'vDEF');
    if ($relations_select == 'default' OR empty($relations_select))
    {
      $relations  = 'all';
      $joins      = 1;
      $root       = 0;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] relations_select is default.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] relations is set to all.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] joins is set to 1.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] root is set to 0.', $this->pObj->extKey, 0);
      }
    }
      // Field relations_select



      //////////////////////////////////////////////////////////////////////
      //
      // Field relations

    if(!$relations)
    {
      $relations = $this->pObj->pi_getFFvalue($arr_piFlexform, 'relations', 'sDEF', 'lDEF', 'vDEF');
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] relations: \''.$relations.'\'!', $this->pObj->extKey, 0);
    }
    $bool_typoscript = false;
    $bool_error      = false;
    #9879
    switch($relations)
    {
      case('all'):
        $bool_typoscript        = true;
        $bool_simpleRealations  = 1;
        $bool_mmRealations      = 1;
        break;
      case('mm'):
        $bool_typoscript        = true;
        $bool_simpleRealations  = 0;
        $bool_mmRealations      = 1;
        break;
      case('single'):
        $bool_typoscript        = true;
        $bool_simpleRealations  = 1;
        $bool_mmRealations      = 0;
        break;
      case('typoscript'):
        $bool_typoscript        = false;
        break;
      default:
        // 3.4.0
        $bool_typoscript        = true;
        $bool_simpleRealations  = 1;
        $bool_mmRealations      = 1;
        // 3.4.0
        //$bool_error = TRUE;
    }
    if($bool_error)
    {
      $str_prompt = $this->pObj->pi_getLL('config_error_prompt');
      $str_prompt = str_replace('%class%', __METHOD__ . ' (' . __LINE__ . ')', $str_prompt);
      $str_prompt = str_replace('%sheet%', 'sheet_sDEF()', $str_prompt);
      $str_prompt = str_replace('%field%', 'relations', $str_prompt);
      $str_prompt = str_replace('%value%', $relations, $str_prompt);
      $str_reload = $this->pObj->pi_getLL('config_reload');
      $str_reload = str_replace('%pid%', $this->pObj->cObj->data['pid'], $str_reload);
      $str_reload = str_replace('%uid%', $this->pObj->cObj->data['uid'], $str_reload);
      echo '
        <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
          <h1>
            '.$this->pObj->pi_getLL('config_error_h1').'
          </h1>
          <p>
            '.$str_prompt.'
          </p>
          <p>
            '.$str_reload.'
          </p>
          </div>';
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[ERROR/FLEXFORM] '.$str_prompt.'!', $this->pObj->extKey, 3);
      }
    }
    if (!$bool_error)
    {
      #9879
      if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
      {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['simpleRelations'] =
          $bool_simpleRealations;
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['mmRelations']     =
           $bool_mmRealations;
      }
      if (empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
      {
        $this->pObj->conf['autoconfig.']['relations.']['simpleRelations'] = $bool_simpleRealations;
        $this->pObj->conf['autoconfig.']['relations.']['mmRelations']     = $bool_mmRealations;
      }
    }
    if ($this->pObj->b_drs_flexform)
    {
      if(!$bool_error)
      {
        if($bool_typoscript)
        {
          $path_view = null;
          if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
          {
            $path_view = 'views.'.$viewWiDot.$modeWiDot;
          }
          $str_simple = $path_view.'autoconfig.relations.simpleRelations';
          t3lib_div::devlog('[INFO/FLEXFORM] TypoScript '.$str_simple.' is set to: '.$bool_simpleRealations.'.', $this->pObj->extKey, 0);
          $str_mm = $path_view.'autoconfig.relations.mmRelations';
          t3lib_div::devlog('[INFO/FLEXFORM] TypoScript '.$str_mm.' is set to: '.$bool_mmRealations.'.', $this->pObj->extKey, 0);
        }
        if(!$bool_typoscript)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] TypoScript isn\'t changed.', $this->pObj->extKey, 0);
        }
      }
    }
      // Field relations



      //////////////////////////////////////////////////////////////////////
      //
      // Field joins

      #9879
    if($joins < 0)
    {
      $joins = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'joins', 'sDEF', 'lDEF', 'vDEF');
    }
    if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
    {
      $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['left_join'] = $joins;
    }
    if (empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
    {
      $this->pObj->conf['autoconfig.']['relations.']['left_join'] = $joins;
    }
    if ($this->pObj->b_drs_flexform)
    {
      $path_view = null;
      if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
      {
        $path_view = 'views.'.$viewWiDot.$modeWiDot;
      }
      $str_path = $path_view.'autoconfig.relations.left_join';
      t3lib_div::devlog('[INFO/FLEXFORM] TypoScript '.$str_path.' is set to: '.$joins.'.', $this->pObj->extKey, 0);
    }
      // Field joins



      //////////////////////////////////////////////////////////////////////
      //
      // Field root

    if($root < 0)
    {
      $root = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'root', 'sDEF', 'lDEF', 'vDEF');
    }
    if ($root == 1)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] Root is set.', $this->pObj->extKey, 0);
      }
      if (strstr($this->pObj->cObj->currentRecord, 'tt_content'))
      {
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] pidList before changing: '.$this->pObj->pidList, $this->pObj->extKey, 0);
        }
        if ($this->pObj->pidList)
        {
          $this->pObj->pidList = '0,'.$this->pObj->pidList;
        }
        if (!$this->pObj->pidList)
        {
          $this->pObj->pidList = '0';
        }
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] pidList after changing: '.$this->pObj->pidList, $this->pObj->extKey, 0);
        }
      }
    }
    if ($root != 1)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] Root isn\'t set.', $this->pObj->extKey, 0);
      }
    }
      // Field root



      //////////////////////////////////////////////////////////////////////
      //
      // Field session

    $int_sessionType  = -1;
    $int_session      = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'session', 'sDEF', 'lDEF', 'vDEF');
    if ($int_session == 0 OR empty($int_session))
    {
      $int_sessionType = 1; // Session is enabled
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] session is 0.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] session.type is set to 1.', $this->pObj->extKey, 0);
      }
    }
      // Field session



      //////////////////////////////////////////////////////////////////////
      //
      // Field session.type

      // session.type isn't set above 
    if($int_sessionType < 0)
    {
      $int_sessionType = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'session.type', 'sDEF', 'lDEF', 'vDEF');
    }
      // session.type isn't set above 

    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] session.type: \''.$int_sessionType.'\'!', $this->pObj->extKey, 0);
    }

    switch($int_sessionType)
    {
      case(0):
          // typoscript
        // Do nothing
        $value = $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'];
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case(1):
          // enabled
        $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'] = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case(2):
          // disabled
        $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'] = false;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is set to false.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field session.type


    return;
  }









/**
 * The sheet socialmedia administrates bookmarks.
 *
 * @return  void
 */
  function sheet_socialmedia()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;


    $str_enabled = $this->pObj->pi_getFFvalue($arr_piFlexform, 'enabled', 'socialmedia', 'lDEF', 'vDEF');
    switch($str_enabled)
    {
      case(false):
      case('disabled'):
        // RETURN if bookmarks are disabled
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_socialmedia)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks are disabled.', $this->pObj->extKey, 0);
        }
        return;
        // RETURN if bookmarks are disabled
        break;
      case('enabled_wi_browser_template'):
        $this->str_socialmedia_bookmarks_tableFieldSite_list    = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'tablefieldSite_list',
                                                                    'socialmedia', 'lDEF', 'vDEF');
        $this->str_socialmedia_bookmarks_tableFieldSite_single  = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'tablefieldSite_single',
                                                                    'socialmedia', 'lDEF', 'vDEF');
      case('enabled_wi_browser_template'):
      case('enabled_wi_individual_template'):
        $this->str_socialmedia_bookmarks_enabled                = $str_enabled;
        $this->str_socialmedia_bookmarks_tableFieldTitle_list   = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'tablefieldTitle_list',
                                                                    'socialmedia', 'lDEF', 'vDEF');
        $this->str_socialmedia_bookmarks_tableFieldTitle_single = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'tablefieldTitle_single',
                                                                    'socialmedia', 'lDEF', 'vDEF');
        $this->strCsv_socialmedia_bookmarks_list                = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'bookmarks_list',
                                                                    'socialmedia', 'lDEF', 'vDEF');
        $this->strCsv_socialmedia_bookmarks_single              = $this->pObj->pi_getFFvalue(
                                                                    $arr_piFlexform,
                                                                    'bookmarks_single',
                                                                    'socialmedia', 'lDEF', 'vDEF');
        break;
      default:
        echo 'ERROR: ' . __METHOD__ . ' (' . __LINE__ . ')';
        exit;
    }




    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_socialmedia)
    {
      $str_bookmarks_list   = str_replace(',', ', ', $this->strCsv_socialmedia_bookmarks_list);
      $str_bookmarks_single = str_replace(',', ', ', $this->strCsv_socialmedia_bookmarks_single);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks are enabled: '.
        $this->str_socialmedia_bookmarks_enabled, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for site: '.
        $this->str_socialmedia_bookmarks_tableFieldSite_list, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for title: '.
        $this->str_socialmedia_bookmarks_tableFieldTitle_list, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views: '.
        $str_bookmarks_list, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for site: '.
        $this->str_socialmedia_bookmarks_tableFieldSite_list, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for title: '.
        $this->str_socialmedia_bookmarks_tableFieldTitle_list, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/FLEXFORM] socialmedia/bookmarks in single views: '.
        $str_bookmarks_single, $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    return;
  }
































/**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @since   3.0.1
 * @version 3.4.4
 */
  function sheet_tca()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;
    $modeWiDot      = (int) $this->mode.'.';
    $viewWiDot      = $this->pObj->view.'.';


    //////////////////////////////////////////////////////////////////////
    //
    // Field configuration

    $str_configuration = $this->pObj->pi_getFFvalue($arr_piFlexform, 'configuration', 'tca', 'lDEF', 'vDEF');

    switch($str_configuration)
    {
      case('adjusted'):
        $arr_csvValue = array('title', 'image', 'imageCaption', 'imageAltText', 'imageTitleText', 'document', 'timestamp');
        foreach((array) $arr_csvValue as $str_csvValue)
        {
          $str_csvFields = $this->pObj->pi_getFFvalue($arr_piFlexform, $str_csvValue.'_csvFields', 'tca', 'lDEF', 'vDEF');
          // #9879
          if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
          {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['autoDiscover.']['items.'][$str_csvValue.'.']['TCAlabel.']['csvValue'] = $str_csvFields;
          }
          if (empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
          {
            $this->pObj->conf['autoconfig.']['autoDiscover.']['items.'][$str_csvValue.'.']['TCAlabel.']['csvValue'] = $str_csvFields;
          }
          if ($this->pObj->b_drs_flexform)
          {
            $path_view = null;
            if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']))
            {
              $path_view = 'views.'.$viewWiDot.$modeWiDot;
            }
            t3lib_div::devlog('[INFO/FLEXFORM] tca: '.$path_view.'autoconfig.autoDiscover.items.'.$str_csvValue.'.TCAlabel.csvValue is set to \''.$str_csvFields.'\'', $this->pObj->extKey, 0);
          }
        }
        break;
      default:
        // Do nothing
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] tca: configuration is default. Nothing to do.', $this->pObj->extKey, 0);
        }
        break;
    }


    return;
  }
















/**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @version 3.6.2
 */
  function sheet_templating()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;
    $modeWiDot      = (int) $this->mode.'.';
    $viewWiDot      = $this->pObj->view.'.';
      // #9689
    $str_path2emplate = false;


      //////////////////////////////////////////////////////////////////////
      //
      // Field template
  
    $str_template = $this->pObj->pi_getFFvalue($arr_piFlexform, 'template', 'templating', 'lDEF', 'vDEF');
    $bool_doNothing = false;
    switch($str_template)
    {
      case('typoscript'):
          // Do nothing;
          // #9689
        $bool_doNothing = true;
        break;
      case('adjusted'):
        $str_path = $this->pObj->pi_getFFvalue($arr_piFlexform, 'path', 'templating', 'lDEF', 'vDEF');
          // #9689
        if(empty($str_path))
        {
            // #11418, cweiske, 101219
          echo  '<div style="background:red;color:white;font-weight:bold;padding:2em;text-align:center;">'.
                '  ERROR with the template: You have not uploaded any template!<br />'.
                '  <br />'.
                '  Browser - the TYPO3-Frontend-Engine.'.
                '</div>';
        }
        $str_path2emplate = 'uploads/tx_browser/'.$str_path;
        break;
      default:
          #10221: RSS-Feed
        $str_path2emplate = $str_template;
    }
    if (!$bool_doNothing)
    {
      if(empty($str_path2emplate))
      {
        echo  '<div style="background:red;color:white;font-weight:bold;padding:2em;text-align:center;">'.
              '  ERROR with the template: Path to the template is empty!'.
              '  <br />'.
              '  Browser - the TYPO3-Frontend-Engine.'.
              '</div>';
      }
      if (!empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file']))
      {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file'] = $str_path2emplate;
      }
      if (empty($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file']))
      {
        $this->pObj->conf['template.']['file'] = $str_path2emplate;
          // Global HTML Template
      }
    }
      // #9689



      //////////////////////////////////////////////////////////////////////
      //
      // Field dataQuery
  
    $this->int_templating_dataQuery = $this->pObj->pi_getFFvalue($arr_piFlexform, 'dataQuery', 'templating', 'lDEF', 'vDEF');
      // Field dataQuery



      //////////////////////////////////////////////////////////////////////
      //
      // Field wrapBaseClass

      // 12367, dwildt, 110310
    $int_wrapInBaseClass = $this->pObj->pi_getFFvalue($arr_piFlexform, 'wrapBaseClass', 'templating', 'lDEF', 'vDEF');

    switch ($int_wrapInBaseClass) 
    {
      case(null):
        // do nothing;
        break;
      case(0):
        $this->bool_wrapInBaseClass = false;
        break;
      case(1):
      default:
        $this->bool_wrapInBaseClass = true;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] templating/wrapBaseClass: \'' . 
        $this->bool_wrapInBaseClass . '\'', $this->pObj->extKey, 0);
    }
      // Field wrapBaseClass



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System
  
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] templating: template.file is set to \''.$this->pObj->conf['template.']['file'].'\'.', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System


    return;
  }
















/**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @version 3.5.0
 */
  function sheet_viewList()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_lang       = $this->pObj->lang->lang;


      //////////////////////////////////////////////////////////////////////
      //
      // Field title

      // Get the title for the list view
    $str_title = $this->pObj->pi_getFFvalue($arr_piFlexform, 'title', 'viewList', 'lDEF', 'vDEF');
    if($str_title)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/title: \''.$str_title.'\'!', $this->pObj->extKey, 0);
      }
    }
    if(!$str_title)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/title is empty.', $this->pObj->extKey, 0);
      }
    }
      // Get the title for the list view

      // View has a local marker array with my_title
    $conf_title = false;
    $str_path   = false;
    if ($str_lang == 'default')
    {
      if (isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['value']))
      {
        $conf_title = $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['value'];
        $str_path   = 'views.list.'.$this->mode.'.marker.my_title.value';
      }
    }
    if ($str_lang != 'default')
    {
      if (isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['lang.'][$str_lang]))
      {
        $conf_title = $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['lang.'][$str_lang];
        $str_path   = 'views.list.'.$this->mode.'.marker.my_title.lang.'.$str_lang;
      }
    }
    if ($str_path)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.$conf_title.'\'', $this->pObj->extKey, 0);
      }
      if ($conf_title)
      {
        if ($str_title)
        {
          $conf_title = $str_title;
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] The plugin value has priority for TypoScript value: \''.$conf_title.'\'!', $this->pObj->extKey, 0);
          }
        }
        if (!$str_title)
        {
          $str_title = $conf_title;
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] TypoScript value: \''.$conf_title.'\'!', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_title)
      {
        $conf_title = $str_title;
        if ($str_lang == 'default')
        {
          $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['value'] = $conf_title;
        }
        if ($str_lang != 'default')
        {
          $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['lang.'][$str_lang] = $conf_title;
        }
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value was 0 or empty.', $this->pObj->extKey, 0);
        }
      }
    }
      // View has a local display array with a single pid

      // View hasn't any local single pid, we take the global one
    if (!$conf_title)
    {
      if ($str_lang == 'default')
      {
        $conf_title = $this->pObj->conf['marker.']['my_title.']['value'];
        $str_path   = 'marker.my_title.value';
      }
      if ($str_lang != 'default')
      {
        $conf_title = $this->pObj->conf['marker.']['my_title.']['lang.'][$str_lang];
        $str_path   = 'marker.my_title.lang.'.$str_lang;
      }
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.$conf_title.'\'', $this->pObj->extKey, 0);
      }
      if ($conf_title)
      {
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value has priority: \''.$conf_title.'\'!', $this->pObj->extKey, 0);
        }
      }
      if (!$conf_title)
      {
        $conf_title = $str_title;
        if ($str_lang == 'default')
        {
          $this->pObj->conf['marker.']['my_title.']['value'] = $conf_title;
        }
        if ($str_lang != 'default')
        {
          $this->pObj->conf['marker.']['my_title.']['lang.'][$str_lang] = $conf_title;
        }
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value is overriden with: \''.$conf_title.'\'!', $this->pObj->extKey, 0);
        }
      }
    }
      // View hasn't any local single pid, we take the global one
      // Field title


      //////////////////////////////////////////////////////////////////////
      //
      // Field titleWrap

      // Get the titleWrap for the list view
    $str_titleWrap = $this->pObj->pi_getFFvalue($arr_piFlexform, 'titleWrap', 'viewList', 'lDEF', 'vDEF');
    if($str_titleWrap)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/titleWrap: \''.htmlspecialchars($str_titleWrap).'\'!', $this->pObj->extKey, 0);
      }
    }
    if(!$str_titleWrap)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/titleWrap is empty.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] We try to get a title wrap from TypoScript.', $this->pObj->extKey, 0);
      }
    }
      // Get the titleWrap for the list view

      // View has a local marker array with my_titleWrap
    $conf_titleWrap = false;
    $str_path       = false;
    if (isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['wrap']))
    {
      $conf_titleWrap = $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['wrap'];
      $str_path       = 'views.list.'.$this->mode.'.marker.my_title.wrap';
    }
    if ($str_path)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.htmlspecialchars($conf_titleWrap).'\'', $this->pObj->extKey, 0);
      }
      if ($conf_titleWrap)
      {
        if(!$str_titleWrap)
        {
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] We take the local value.', $this->pObj->extKey, 0);
          }
        }
        if($str_titleWrap)
        {
          $conf_titleWrap = $str_titleWrap;
          $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['wrap'] = $conf_titleWrap;
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] Local value will be overriden by the plugin value.', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_titleWrap)
      {
        $conf_titleWrap = $str_titleWrap;
        $this->pObj->conf['views.']['list.'][$this->mode.'.']['marker.']['my_title.']['wrap'] = $conf_titleWrap;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value was 0 or empty. It is overriden with: \''.htmlspecialchars($conf_titleWrap).'\'!', $this->pObj->extKey, 0);
        }
      }
    }
      // View has a local marker array with my_titleWrap

      // View hasn't any local marker array with my_titleWrap, we take the global one
    if (!$conf_titleWrap)
    {
      $conf_titleWrap = $this->pObj->conf['marker.']['my_title.']['wrap'];
      $str_path   = 'marker.my_titleWrap.value';
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.htmlspecialchars($conf_titleWrap).'\'', $this->pObj->extKey, 0);
      }
      if ($conf_titleWrap)
      {
        if(!$str_titleWrap)
        {
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] We take the local value.', $this->pObj->extKey, 0);
          }
        }
        if($str_titleWrap)
        {
          $conf_titleWrap = $str_titleWrap;
          $this->pObj->conf['marker.']['my_title.']['wrap'] = $conf_titleWrap;
          if ($this->pObj->b_drs_flexform)
          {
            t3lib_div::devlog('[INFO/FLEXFORM] Global value will be overriden by the plugin value.', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_titleWrap)
      {
        $conf_titleWrap = $str_titleWrap;
        $this->pObj->conf['marker.']['my_title.']['wrap'] = $conf_titleWrap;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value is overriden with: \''.htmlspecialchars($conf_titleWrap).'\'!', $this->pObj->extKey, 0);
        }
      }
    }
      // View hasn't any local marker array with my_titleWrap, we take the global one
      // Field titleWrap



      //////////////////////////////////////////////////////////////////////
      //
      // Field grouptitleWrap

      // Get the grouptitleWrap for the list view
    $str_grouptitleWrap = $this->pObj->pi_getFFvalue($arr_piFlexform, 'grouptitleWrap', 'viewList', 'lDEF', 'vDEF');
    if($str_grouptitleWrap)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/grouptitleWrap: \''.htmlspecialchars($str_grouptitleWrap).'\'!', $this->pObj->extKey, 0);
      }
      $this->pObj->str_wrap_grouptitle = $str_grouptitleWrap;
    }
    if(!$str_grouptitleWrap)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/grouptitleWrap is empty.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] We try to get a title wrap from TypoScript.', $this->pObj->extKey, 0);
      }
      $this->pObj->str_wrap_grouptitle = false;
    }
      // Get the grouptitleWrap for the list view
      // Field grouptitleWrap



      //////////////////////////////////////////////////////////////////////
      //
      // Field limit

      // Get the limit for the list view
          // #27354, uherrmann, 110611
        // Get the limit (offset) for the list view
    $str_limit_offset = $this->pObj->pi_getFFvalue($arr_piFlexform, 'limitOffset', 'viewList', 'lDEF', 'vDEF');
      // downwards compatibility < 3.6.5:
      // offset is NULL if flexform was never saved with this field:
      $str_limit_offset = (int)$str_limit_offset;
          // #27354, uherrmann, 110611

    $str_limit = $this->pObj->pi_getFFvalue($arr_piFlexform, 'limit', 'viewList', 'lDEF', 'vDEF');
    if($str_limit)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/limit: \''.htmlspecialchars($str_limit).'\'!', $this->pObj->extKey, 0);
      }
    }
    if(!$str_limit)
    {
      $str_limit = 20;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/limit is empty.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/limit: We allocates it with 20.', $this->pObj->extKey, 0);
      }
    }
        // #27354, uherrmann, 110611
  ##$str_limit = '0,'.$str_limit;
    $str_limit = $str_limit_offset.','.$str_limit;
        // #27354, uherrmann, 110611
      // Get the limit for the list view

      // View has a local limit
    $conf_limit = false;
    $str_path   = false;
    if (isset($this->pObj->conf['views.']['list.'][$this->mode.'.']['limit']))
    {
      $conf_limit = $this->pObj->conf['views.']['list.'][$this->mode.'.']['limit'];
      $str_path   = 'views.list.'.$this->mode.'.limit';
    }
    if ($str_path)
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] '.$str_path.': \''.htmlspecialchars($conf_limit).'\'', $this->pObj->extKey, 0);
      }
      if ($conf_limit)
      {
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] The TypoScript value hasn\'t any effect: \''.htmlspecialchars($conf_limit).'\'!', $this->pObj->extKey, 0);
        ##t3lib_div::devlog('[HELP/FLEXFORM] Please remove \''.$str_path.'\'! ', $this->pObj->extKey, 0);
            // #27354, uherrmann, 110611
          t3lib_div::devlog('[HELP/FLEXFORM] Please remove \''.$str_path.'\'! Use fields \'Limit: start/offset\' and \'Limit: records per page\' (Backend/ Browser plugin) instead of!', $this->pObj->extKey, 0);
            // #27354, uherrmann, 110611
        }
      }
    }
    $conf_limit = $str_limit;
    $this->pObj->conf['views.']['list.'][$this->mode.'.']['limit'] = $conf_limit;
      // View has a local limit
      // Field limit



      //////////////////////////////////////////////////////////////////////
      //
      // Field navigation

    $int_navigation  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'navigation', 'viewList', 'lDEF', 'vDEF');

      // Set default value
        // #27352, uherrmann, 110610
  ##if(empty($int_navigation))
    if(!isset($int_navigation))
        // #27352, uherrmann, 110610
    {
        // default case
      $int_navigation = 3;
    }
      // Set default value

    switch ($int_navigation) 
    {
      case(0):
        $this->bool_azBrowser   = 0;
        $this->bool_pageBrowser = 0;
        break;
      case(1):
        $this->bool_azBrowser   = 1;
        $this->bool_pageBrowser = 0;
        break;
      case(2):
        $this->bool_azBrowser   = 0;
        $this->bool_pageBrowser = 1;
        break;
      case(3):
        $this->bool_azBrowser   = 1;
        $this->bool_pageBrowser = 1;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR</h1>
            <p>
              Flexform field navigation has a value bigger than 3. The value isn\'t defined.<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewList/navigation<br />
        azBrowser: \''.$this->bool_azBrowser.'\'<br />
        pageBrowser: \''.$this->bool_pageBrowser.'\'', $this->pObj->extKey, 0);
    }
      // Field navigation



      //////////////////////////////////////////////////////////////////////
      //
      // Field records

    $int_records  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'records', 'viewList', 'lDEF', 'vDEF');

    $this->bool_emptyAtStart = false;
    switch ($int_records) {
      case(0):
        $this->bool_emptyAtStart  = false;
        break;
      case(1):
        $this->bool_emptyAtStart  = true;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR
            </h1>
            <p>
              Flexform field records has a value bigger than 1. The value isn\'t defined.<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewList/records<br />
        emptyAtStart: \''.$this->bool_emptyAtStart.'\'', $this->pObj->extKey, 0);
    }
      // Field records



      //////////////////////////////////////////////////////////////////////
      //
      // Field emptyValues

      // 110110, dwildt, 11603
    $int_emptyValues  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'emptyValues', 'viewList', 'lDEF', 'vDEF');
      // Set default value
    if($int_emptyValues == null)
    {
      $int_emptyValues = $this->bool_dontHandleEmptyValues = true;
    }
      // Set default value
    switch ($int_emptyValues)
    {
      case(0):
        $this->bool_dontHandleEmptyValues  = false;
        break;
      case(1):
        $this->bool_dontHandleEmptyValues  = true;
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR
            </h1>
            <p>
              Flexform field emptyValues has a value bigger than 1. The value isn\'t defined.<br />
              ' . __METHOD__ . ' (' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewList/emptyValues<br />
        dontHandle: \''.$this->bool_dontHandleEmptyValues.'\'', $this->pObj->extKey, 0);
    }
//var_dump('config 2500', $this->bool_dontHandleEmptyValues);
      // Field emptyValues



      //////////////////////////////////////////////////////////////////////
      //
      // Field search

    $str_search         = $this->pObj->pi_getFFvalue($arr_piFlexform, 'search', 'viewList', 'lDEF', 'vDEF');
    $bool_handleSearch  = true;

      // Don't handle search properties'
    if(!$str_search || $str_search == 'default')
    {
      $this->bool_searchForm                       = true;
      $this->bool_searchForm_wiPhrase              = true;
      $this->bool_searchForm_wiColoredSwords       = true;
      $this->bool_searchForm_wiColoredSwordsSingle = false;
      $this->pObj->bool_searchWildcardsManual      = false;
      $this->pObj->str_searchWildcardCharManual    = '*';
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/search: \'default\'. Nothing to do.', $this->pObj->extKey, 0);
      }
      $bool_handleSearch = false;
    }
      // Don't handle search properties'

      // Handle search properties'
    if($bool_handleSearch)
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/search: \''.$str_search.'\'.', $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
        // Field search
  
  
  
        //////////////////////////////////////////////////////////////////////
        //
        // Field searchForm
  
      $int_searchForm  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchForm', 'viewList', 'lDEF', 'vDEF');
  
      $this->bool_searchForm                       = (($int_searchForm & 1) == 1);
      $this->bool_searchForm_wiPhrase              = (($int_searchForm & 2) == 2);
      $this->bool_searchForm_wiColoredSwords       = (($int_searchForm & 4) == 4);
      $this->bool_searchForm_wiColoredSwordsSingle = (($int_searchForm & 8) == 8);
  
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/searchForm<br />
          int_searchForm: \''.$int_searchForm.'\'<br />
          searchForm: \''.$this->bool_searchForm.'\'<br />
          searchForm_wiPhrase: \''.$this->bool_searchForm_wiPhrase.'\'<br />
          searchForm_wiColoredSwords: \''.$this->bool_searchForm_wiColoredSwords.'\'<br />
          searchForm_wiColoredSwordsSingle: \''.$this->bool_searchForm_wiColoredSwordsSingle.'\'', $this->pObj->extKey, 0);
      }
        // Field searchForm
  
  
  
        //////////////////////////////////////////////////////////////////////
        //
        // Field searchWildcards
  
      $str_searchWildcards  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchWildcards', 'viewList', 'lDEF', 'vDEF');
  
      if ($str_searchWildcards == 'default')
      {
        $this->pObj->bool_searchWildcardsManual = 0;
      }
      if ($str_searchWildcards == 'manual')
      {
        $this->pObj->bool_searchWildcardsManual = 1;
      }
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/searchWildcards: '.
          $this->pObj->bool_searchWildcardsManual.'\'', $this->pObj->extKey, 0);
      }
        // Field searchWildcards
  
  
  
        //////////////////////////////////////////////////////////////////////
        //
        // Field searchWildcardChar
  
      $str_searchWildcardChar  = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchWildcardChar', 'viewList', 'lDEF', 'vDEF');
  
      $this->pObj->str_searchWildcardCharManual = $str_searchWildcardChar;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/searchWildcardChar: '.
          $this->pObj->bool_searchWildcardCharManual.'\'', $this->pObj->extKey, 0);
      }
        // Field searchWildcardChar

    }
      // Handle search properties'



      //////////////////////////////////////////////////////////////////////
      //
      // Field simulateSingleUid

      // Get the simulateSingleUid for the list view
    $int_simulateSingleUid = $this->pObj->pi_getFFvalue($arr_piFlexform, 'simulateSingleUid', 'viewList', 'lDEF', 'vDEF');
    if(!empty($int_simulateSingleUid))
    {
      $this->int_singlePid = (int) $int_simulateSingleUid;
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewList/simulateSingleUid: \''.$int_simulateSingleUid.'\'', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/FLEXFORM] This plugin will act like a plugin which is called with a single uid!', $this->pObj->extKey, 1);
      }
    }
      // Get the simulateSingleUid for the list view
      // Field simulateSingleUid



    return;
  }









  /**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return  void
 * @since 3.7.0
 * @version 3.7.0
 */
  function sheet_viewSingle()
  {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $modeWiDot      = (int) $this->mode.'.';
    $viewWiDot      = $this->pObj->view.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser

    $record_browser = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser: \'' . $record_browser . '\'.', $this->pObj->extKey, 0);
    }

    switch($record_browser)
    {
      case('disabled'):
        $this->pObj->conf['navigation.']['record_browser'] = 0;
        break;
      case('by_flexform'):
        $this->pObj->conf['navigation.']['record_browser'] = 1;
        break;
      case('ts'):
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser'];
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser is set to ' . $record_browser . '.', $this->pObj->extKey, 0);
    }
      // Field record_browser



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser == false

    if(!$this->pObj->conf['navigation.']['record_browser'])
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewSingle RETURN', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN record_browser == false



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser == ts

    if($record_browser == 'ts')
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser is set to ts. RETURN', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN record_browser == false



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.firstAndLastItem

    $firstAndLastItem = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.firstAndLastItem', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.firstAndLastItem: \'' . $firstAndLastItem . '\'.', $this->pObj->extKey, 0);
    }

    switch($firstAndLastItem)
    {
      case('no'):
        $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastItem'] = 0;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastItem is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case('yes'):
          // enabled
        $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastItem'] = 1;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastItem is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case('ts'):
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastItem'];
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastItem is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.display.firstAndLastItem



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.itemsWithoutLink

    $itemsWithoutLink = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.itemsWithoutLink', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.itemsWithoutLink: \'' . $itemsWithoutLink . '\'.', $this->pObj->extKey, 0);
    }

    switch($itemsWithoutLink)
    {
      case(('no')):
        $this->pObj->conf['navigation.']['record_browser.']['display.']['itemsWithoutLink'] = 0;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.itemsWithoutLink is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case('yes'):
        $this->pObj->conf['navigation.']['record_browser.']['display.']['itemsWithoutLink'] = 1;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.itemsWithoutLink is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case('ts'):
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['itemsWithoutLink'];
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.itemsWithoutLink is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.display.itemsWithoutLink



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.withoutResult

    $withoutResult = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.withoutResult', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.withoutResult: \'' . $withoutResult . '\'.', $this->pObj->extKey, 0);
    }

    switch($withoutResult)
    {
      case('no'):
        $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'] = 0;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case('yes'):
        $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'] = 1;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case('ts'):
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'];
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.display.withoutResult



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.labels

    $labeling = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.labeling', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div::devlog('[INFO/FLEXFORM] viewSingle.record_browser.labeling: \'' . $withoutResult . '\'.', $this->pObj->extKey, 0);
    }

    switch($labeling)
    {
      case('chars'):
      case('icons'):
      case('position'):
      case('text'):
          // Get configuration of the selected label
        $conf_labelling = $this->pObj->conf['navigation.']['record_browser.']['labeling.'][$labeling . '.'];
          // Set configuration of the selected label
        $this->pObj->conf['navigation.']['record_browser.']['labeling.']['typoscript.'] = $conf_labelling;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.labelling.typoscript < .' . $labeling, $this->pObj->extKey, 0);
        }
        break;
      case('ts'):
        // Do nothing
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div::devlog('[INFO/FLEXFORM] navigation.record_browser.labeling.typoscript will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.labeling



    return;
  }









}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_flexform.php'])  {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_flexform.php']);
}

?>