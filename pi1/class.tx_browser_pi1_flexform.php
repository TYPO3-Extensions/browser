<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2009-2012 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 *   69: class tx_browser_pi1_flexform
 *  195:     function __construct($parentObj)
 *  217:     function main()
 *
 *              SECTION: piVars
 *  337:     function prepare_piVars()
 *  521:     function prepare_mode()
 *
 *              SECTION: Fields with Priority
 *  573:     function sheet_sDEF_views()
 *
 *              SECTION: Sheets
 *  903:     function sheet_advanced()
 * 1058:     function sheet_evaluate( )
 * 1117:     function sheet_extend( )
 * 1198:     function sheet_javascript()
 * 1500:     function sheet_sDEF( )
 * 2141:     function sheet_socialmedia()
 * 2205:     function sheet_tca()
 * 2266:     function sheet_templating()
 * 2531:     function sheet_viewList( )
 * 3352:     function sheet_viewSingle()
 *
 * TOTAL FUNCTIONS: 15
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
 * @version 4.1.25
 * @since   2.0.0
 */
class tx_browser_pi1_flexform {
  /////////////////////////////////////////////////
  //
  // Vars set by methods in the current class

  var $mode = false;
  // [integer] The ID of the current mode/view

  //[general]
  var $int_viewsListPid = false;
  // [integer] pid of the result page

  //[list view]
  var $int_singlePid = null;
  // [integer] pid of a single record, #12006

  var $bool_indexBrowser = true;
  // [boolean] Display the Index-Browser
  var $bool_pageBrowser = true;
  // [boolean] Display the PageBrowser
  var $bool_emptyAtStart = false;
  // [boolean] Display an empty list at start
  var $bool_dontHandleEmptyValues = true;
  // [boolean] Don't handle empty records in list views and don't handle empty fields in single views
  var $bool_searchForm = true;
  // [boolean] Display the Searchbox
  var $bool_searchForm_wiPhrase = true;
  // [boolean] Display the Searchbox Phrase
  var $bool_searchForm_wiColoredSwords = true;
  // [boolean] Display Colored Swords (list view)
  var $bool_searchForm_wiColoredSwordsSingle = false;
  // [boolean] Display Colored Swords (single view)
  var $bool_searchWildcardsManual = false;
  // [boolean] Display Wildcard Phrase
  var $str_searchWildcardCharManual = '*';
  // [string] Display the Searchbox

  var $bool_linkToSingle_wi_piVar_indexBrowserTab = false;
  // [boolean] Should the URL to a single view contain the parameter indexBrowserTab?
  var $bool_linkToSingle_wi_piVar_mode = false;
  // [boolean] Should the URL to a single view contain the parameter mode?
  var $bool_linkToSingle_wi_piVar_pointer = false;
  // [boolean] Should the URL to a single view contain the parameter pointer?
  var $bool_linkToSingle_wi_piVar_plugin = true;
  // [boolean] Should the URL to a single view contain the parameter plugin?
  var $bool_linkToSingle_wi_piVar_sort = false;
  // [boolean] Should the URL to a single view contain the parameter sort?

  //[sheet/extend]
    // Uid in tt_content of the Browser Calender User Interface
  var $sheet_extend_cal_ui            = null;
    // Uid of the view in the TypoScript setup
  var $sheet_extend_cal_view          = null;
    // table.field-name of the date begin field
  var $sheet_extend_cal_field_start   = null;
    // table.field-name of the date end field
  var $sheet_extend_cal_field_end     = null;
  //[sheet/extend]

  //[sheet/javascript]
  // #9659, 101013 fsander
    // [boolean] AJAX enabled?
  var $bool_ajax_enabled = false;
    // [boolean] AJAX also used for single view?
  var $bool_ajax_single = false;
    // [string] AJAX transition for list view
  var $str_ajax_list_transition = false;
    // [string] AJAX transition for single view
  var $str_ajax_single_transition = false;
    // [string] AJAX mode for list in single view
  var $str_ajax_list_on_single = false;
  // #9659, 101013 fsander
  var $str_browser_libraries = 'typoscript';
  var $str_jquery_library = 'typoscript';
  // #28562, 110804, dwildt

  var $bool_jquery_ui = false;
  // [boolean] jQuery UI jss should included
  var $bool_jquery_plugins_t3browser = false;
  // [boolean] jQuery plugin t3browser jss should included
  var $bool_css_browser = false;
  // [boolean] Browser CSS should included
  var $bool_css_jqui = false;
  // [boolean] jQuery UI CSS should included

  //[sheet/socialmedia]
  var $str_socialmedia_bookmarks_enabled = false;
  // [boolean] Are socalmedia bookmarks enabled?
  var $str_socialmedia_bookmarks_tableFieldSite_list = false;
  // [string] tableField for the site of the bookmark links
  var $str_socialmedia_bookmarks_tableFieldTitle_list = false;
  // [string] tableField for the tile property of bookmark links
  var $str_socialmedia_bookmarks_tableFieldSite_single = false;
  // [string] tableField for the site of the bookmark links
  var $str_socialmedia_bookmarks_tableFieldTitle_single = false;
  // [string] tableField for the tile property of bookmark links
  var $strCsv_socialmedia_bookmarks_list = false;
  // [string] csvList with the keys of the bookmars in in the TypoScript, which should displayed in list views
  var $strCsv_socialmedia_bookmarks_single = false;
  // [string] csvList with the keys of the bookmars in in the TypoScript, which should displayed in single views
  //[sheet/socialmedia]

  //[sheet/templating]
  var $int_templating_dataQuery = false;
  // [int] key of the dataQuery in the TypoScript, which should added in list views
  var $bool_wrapInBaseClass = true;
  // [boolean] wrap the plugin in with pi_wrapInBaseClass
  //[sheet/templating]

  //[sheet/view]
    // [string] independent (default) || controlled: Calculate total hits.
  var $sheet_viewList_total_hits      = 'independent';
    // [boolean] Enable CSV export
  var $sheet_viewList_csvexport       = null;
    // [boolean] Enable CSV export
  var $sheet_viewList_rotateviews     = null;
  //[sheet/extend]

    // 3.9.24, 120604, dwildt, 1+
  var $handlePiVars = 'forCurrentPluginOnly';
  // [string] forCurrentPluginOnly || forEachPlugin. Has an effect, if there is more than one plugin

  // Vars set by methods in the current class

  /**
 * Constructor. The method initiate the parent object
 *
 * @param    object        The parent object
 * @return    void
 */
  function __construct($parentObj) {
    // Set the Parent Object
    $this->pObj = $parentObj;

  }









  /**
 * main():  Process the values from the pi_flexform field.
 *          Process each sheet.
 *          Allocates values to TypoScript.
 *
 * @return    void
 * @version 4.1.10
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
    if (1 == 0) {
      $treeDat = $this->pObj->cObj->data['pi_flexform'];
      $treeDat = t3lib_div :: resolveAllSheetsInDS($treeDat);
      var_dump(t3lib_div :: view_array($treeDat));
    }
      // Display values from pi_flexform as an tree
      // Development



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($this->pObj->b_drs_flexform) {
      $str_header = $this->pObj->cObj->data['header'];
      $int_uid = $this->pObj->cObj->data['uid'];
      $int_pid = $this->pObj->cObj->data['pid'];
      t3lib_div :: devlog('[INFO/FLEXFORM] \'' . $str_header . '\' (pid: ' . $int_pid . ', uid: ' . $int_uid . ')', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////
      //
      // Sheet evaluate controlls the DRS

    $this->sheet_evaluate();
      // Sheet evaluate controlls the DRS



      //////////////////////////////////////////////////////////////////////
      //
      // Init Language

    if (!$this->pObj->lang) {
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
    $this->sheet_extend();
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
 * @return    void
 *
 * @version   4.1.25
 * @since     2.x 
 */
  function prepare_piVars() {

    //////////////////////////////////////////////////////////////////////
    //
    // Get the field names for sys_language_content and for l10n_parent

    $str_langField = $GLOBALS['TCA']['tt_content']['ctrl']['languageField'];
    $str_langPid = $GLOBALS['TCA']['tt_content']['ctrl']['transOrigPointerField'];
    // Get the field names for sys_language_content and for l10n_parent

    //////////////////////////////////////////////////////////////////////
    //
    // Build and execute the SQL query

    $pid = $this->pObj->cObj->data['pid'];

    $select_fields = "uid, header, " . $str_langField . ", " . $str_langPid;
    $from_table = "tt_content";
    $where_enable = $this->pObj->cObj->enableFields($from_table);
    $where_locale = $this->pObj->objLocalise3x->localisationFields_where($from_table);
    if (!$where_locale) {
      $where_locale = 1;
    }
    $where_clause = "pid = " . $pid . " " .
    "AND CType = 'list' " .
    "AND list_type = '" . $this->pObj->extKey . "_pi1' " . $where_enable . " " .
    "AND " . $where_locale;

    // For Development
    if (1 == 0) {
        // 121025, dwildt, 1-
      //$query = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '');
        // 121025, dwildt, 1+
      $query = $GLOBALS['TYPO3_DB']->SELECTquery( $select_fields, $from_table, $where_clause );
      t3lib_div :: devlog('[INFO/SQL] ' . $query, $this->pObj->extKey, 0);
    }
    // For Development

      // 121025, dwildt, 1-
    //$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '', $uidIndexField = '');
      // 121025, dwildt, 1+
    $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows( $select_fields, $from_table, $where_clause );
    // Build and execute the SQL query

    //////////////////////////////////////////////////////////////////////
    //
    // Consolidate the Rows in case of Localisation

    $arr_rm_langParents = false;
    if (count($rows) > 1) {
      foreach ((array) $rows as $row => $elements) {
        // We have a localised record
        if ($elements[$str_langPid] > 0) {
          $arr_rm_langParents[] = $elements[$str_langPid];
        }
        // We have a localised record
      }
    }
    if (is_array($arr_rm_langParents)) {
      foreach ((array) $rows as $row => $elements) {
        // Rempve the default language record
        if (in_array($elements['uid'], $arr_rm_langParents)) {
          unset ($rows[$row]);
        }
        // Rempve the default language record
      }
    }
    //var_dump($rows);
    // Consolidate the Rows in case of Localisation

      // #40959 4.1.10, 120916, dwildt, +
      // field piVarsPlugin
    $arr_piFlexform   = $this->pObj->cObj->data['pi_flexform'];
    $str_piVarsPlugin = $this->pObj->pi_getFFvalue( $arr_piFlexform, 'piVarsPlugin', 'sDEF', 'lDEF', 'vDEF' );
    $bool_addPiVarsPlugin = false;
    switch( $str_piVarsPlugin )
    {
      case( $this->pObj->cObj->data['pid'] == ( string ) $GLOBALS['TSFE']->id ):
        $bool_addPiVarsPlugin = false;
        if( $this->pObj->b_drs_flexform )
        {
          $prompt = 'Current plugin is included by the current page and not by a foreign page. ' . 
                    'piVarsPlugin is set to false.';
          t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      case( 'add' ) :
        $bool_addPiVarsPlugin = true;
        if( $this->pObj->b_drs_flexform )
        {
          $prompt = 'Current plugin wants an added piVars[plugin], if it is called by a foreign page.';
          t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      case ('ignore') :
      case ( null ) :
      case ( false ) :
        $bool_addPiVarsPlugin = false;
        if( $this->pObj->b_drs_flexform )
        {
          $prompt = 'Current plugin doesn\'t want any added piVars[plugin], if it is called by a foreign page.';
          t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      default :
        if ($this->pObj->b_drs_warn)
        {
          $prompt = 'Current plugin has an undefined value in piVarsPlugin. ' .
                    'Definded is: add, ignore. Current value is: ' . $str_piVarsPlugin;
          t3lib_div :: devlog( '[WARN/FLEXFORM] ' . $prompt, $this->pObj->extKey, 3);
        }
    }
      // field piVarsPlugin
      // #40959 4.1.10, 120916, dwildt, +

    //////////////////////////////////////////////////////////////////////
    //
    // RETURN, if we have one plugin on the page only

    if( count( $rows ) <= 1 && ! $bool_addPiVarsPlugin )
    {
        // #40959 4.1.10, 120916, dwildt, +
        // DRS
      if( $this->pObj->b_drs_warn ) 
      {
        if( $this->pObj->cObj->data['pid'] != ( string ) $GLOBALS['TSFE']->id )
        {
          $prompt = 'The current plugin ' . $this->pObj->cObj->data['header'] . ' (uid: ' . $this->pObj->cObj->data['uid'] . ') ' .
                    'is not part of the current page (uid ' . $GLOBALS['TSFE']->id . ') but of the page with the uid ' . $this->pObj->cObj->data['pid'] . '. ' .
                    'This will cause trouble in case of multiple plugins!';
          t3lib_div :: devlog( '[WARN/FLEXFORM] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Please move the plugin to the current page, if you are working with multiple plugins.';
          t3lib_div :: devlog( '[HELP/FLEXFORM] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }
        // DRS
        // #40959 4.1.10, 120916, dwildt, +

      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] There is only one plugin on the page. There isn\'t any effect for any piVar.', $this->pObj->extKey, 0);
      }
      return;
    }
    // RETURN, if we have one plugin on the page only
    
    //////////////////////////////////////////////////////////////////////
    //
    // RETURN, if plugin want to handle piVars of foreign plugin

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $str_piVars     = $this->pObj->pi_getFFvalue($arr_piFlexform, 'piVars', 'sDEF', 'lDEF', 'vDEF');
    switch ($str_piVars) {
      case ('all') :
          // 3.9.24, 120604, dwildt, 1+
        $this->handlePiVars = 'forEachPlugin';
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] Current plugin wants to handle all piVars.', $this->pObj->extKey, 0);
        }
        return;
        break;
      case ('default') :
      case (false) :
          // 3.9.24, 120604, dwildt, 1+
        $this->handlePiVars = 'forCurrentPluginOnly';
        if ($this->pObj->b_drs_flexform) 
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] Current plugin wants to handle only own piVars.', $this->pObj->extKey, 0);
        }
        break;
      default :
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[WARN/FLEXFORM] Current plugin has an undefined value in piVars. ' .
          'Definded is: default, all. Current value is: ' . $str_piVars, $this->pObj->extKey, 2);
        }
    }
    // RETURN, if plugin want to handle piVars of foreign plugin

    //////////////////////////////////////////////////////////////////////
    //
    // We have more than one plugin on the page

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] There is more than one plugin on the page.', $this->pObj->extKey, 0);
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
    if( $uid_plugin_current ) 
    {
      if( $uid_plugin_selected != $uid_plugin_current )
      {
        $bool_unset_piVars = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The visitor hasn\'t selected the current plugin.<br />
                      Id of the current plugin: ' . $uid_plugin_current . '<br />
                      Id of the selected plugin: ' . $uid_plugin_selected . '<br />
                      All piVars for the current plugin are removed!', $this->pObj->extKey, 0);
        }
      }
      if( $uid_plugin_selected == $uid_plugin_current )
      {
        if( $this->pObj->b_drs_flexform )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] The visitor has selected the current plugin.<br />
                      Id of the current plugin: ' . $uid_plugin_current . '<br />
                      Id of the selected plugin: ' . $uid_plugin_selected . '<br />
                      No piVar for the current plugin is removed!', $this->pObj->extKey, 0);
        }
      }
    }
    if( ! $uid_plugin_current )
    {
      $bool_unset_piVars = true;
      if ($this->pObj->b_drs_flexform) {
        $csv_piVars_keys = implode(', ', array_keys($this->pObj->piVars));
        t3lib_div :: devlog('[INFO/FLEXFORM] The visitor hasn\'t selected any plugin.<br />
                  Id of the current plugin: NULL<br />
                  Id of the selected plugin: ' . $uid_plugin_selected . '<br />
                  Keys of the piVars: ' . $csv_piVars_keys . '<br />
                  All piVars for the current plugin are removed!', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[HELP/FLEXFORM] If the plugin should handle the piVars,
                  please configure in the plugin [General]: handle piVars from foreign plugins!', $this->pObj->extKey, 1);
      }
    }
    if( $bool_unset_piVars ) 
    {
      unset ( $this->pObj->piVars );
    }
    // The current plugin isn't the plugin, which is used by the visitor
    // Remove piVars

    //////////////////////////////////////////////////////////////////////
    //
    // Add piVar[plugin]

    // cweiske: we do not need the original value anymore, but need the plugin id
    // in the template marker array

    $this->pObj->piVars['plugin'] = $uid_plugin_selected;
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] piVars[plugin] = ' . $uid_plugin_selected . ' is added to the array piVars.', $this->pObj->extKey, 0);
    }
    // Add piVar[plugin]

  }

  /**
 * Set the class var mode. It is the current mode/view.
 * The code is corresponding with the mode snippet in tx_brwoser_pi1_zz::prepairePiVars() !!!
 *
 * @return    void
 */
  function prepare_mode() {

    //////////////////////////////////////
    //
    // Security

    $this->mode = false;
    if (isset ($this->pObj->piVars['mode'])) {
      $this->mode = $this->pObj->objZz->secure_piVar($this->pObj->piVars['mode'], 'integer');
    }
    // Security

    //////////////////////////////////////
    //
    // Set the global piVar_mode

    if (!$this->mode) {
      if (is_array($this->pObj->conf['views.']['list.'])) {
        reset($this->pObj->conf['views.']['list.']);
        $firstKeyWiDot = key($this->pObj->conf['views.']['list.']);
        $firstKeyWoDot = substr($firstKeyWiDot, 0, strlen($firstKeyWiDot) - 1);
        $this->mode = $firstKeyWoDot;
      }
      if (!is_array($this->pObj->conf['views.']['list.'])) {
        $this->mode = $this->pObj->piVars['mode'];
      }
    }
    // Set the global piVar_mode

    //////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] list id (mode): \'' . $this->mode . '\'.', $this->pObj->extKey, 0);
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
 * @return    void
 * 
 * @version   4.1.25
 * @since     2.x
 */
  function sheet_sDEF_views() {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];

    //////////////////////////////////////////////////////////////////////
    //
    // Field views

    $str_views_status = $this->pObj->pi_getFFvalue($arr_piFlexform, 'views', 'sDEF', 'lDEF', 'vDEF');

    // Return, if views have the default status
    if ($str_views_status == 'all') {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] sDEF/views: \'all\'. Nothing to do.', $this->pObj->extKey, 0);
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

    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] sDEF/views: \'' . $str_views_status . '\'.', $this->pObj->extKey, 0);
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

    $bool_viewsIds = true;
    $str_views_csv = $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsList', 'sDEF', 'lDEF', 'vDEF');
    // Downgrade to 3.4.1: viewsIds
    if ($this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF')) {
      $str_views_csv = $str_views_csv . ',' . $this->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF');
    }
    // Downgrade to 3.4.1: viewsIds

    //var_dump('config 734', $str_views_csv);

    // If viewsIds is empty, do nothing
    if ($str_views_csv == '') {
      $bool_viewsIds = false;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] sDEF/viewsIds is empty. Nothing to do.', $this->pObj->extKey, 0);
      }
    }
    // If viewsIds is empty, do nothing

    // Remove every id, which isn't proper
    if ($bool_viewsIds) {
      // Remove every id, which isn't proper
      $arr_viewsList = $this->pObj->objZz->getCSVasArray($str_views_csv);
      $arr_viewsList_proper = false;
      // Remove every id, which isn't proper
      foreach ((array) $arr_viewsList as $key => $value) {
        if (in_array($value . '.', array_keys($this->pObj->conf['views.']['list.']))) {
          $arr_viewsList_proper[] = $value . '.';
        }
      }
      // Remove every id, which isn't proper
      if (is_array($arr_viewsList_proper)) {
        $arr_viewsList_proper = array_unique($arr_viewsList_proper);
      }
      if (!is_array($arr_viewsList_proper)) {
          // 121025, dwildt, 1-
        //$bool_viewsList = false;
        if ($this->pObj->b_drs_flexform) {
          $str_prompt = implode(', ', $arr_viewsList);
          t3lib_div :: devlog('[WARN/FLEXFORM] sDEF/viewsList hasn\'t any proper views.list id: \'' . $str_prompt . '\'', $this->pObj->extKey, 2);
          $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
          $str_prompt = str_replace('..', ', ', $str_prompt);
          $str_prompt = str_replace('.', '', $str_prompt);
          t3lib_div :: devlog('[HELP/FLEXFORM] Proper values would be: \'' . $str_prompt . '\'', $this->pObj->extKey, 1);
        }
      }
      // Remove every id, which isn't proper

      // Remove every view, which isn't element of the id list
      $arr_keyslistViews = array_keys($this->pObj->conf['views.']['list.']);
      foreach ($arr_keyslistViews as $key => $value) {
        if (!in_array($value, $arr_viewsList_proper)) {
          // Remove list view
          // Remove array
          unset ($this->pObj->conf['views.']['list.'][$value]);
          // Remove string
          $valueWoDot = substr($value, 0, strlen($value) - 1);
          unset ($this->pObj->conf['views.']['list.'][$value]);
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] views.list.' . $valueWoDot . ' is removed from TypoScript.', $this->pObj->extKey, 0);
          }
          // Remove list view

          // Remove single view
          // Remove array
          unset ($this->pObj->conf['views.']['single.'][$value]);
          // Remove string
          $valueWoDot = substr($value, 0, strlen($value) - 1);
          unset ($this->pObj->conf['views.']['single.'][$value]);
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] views.list.' . $valueWoDot . ' is removed from TypoScript.', $this->pObj->extKey, 0);
          }
          // Remove single view
        }
      }
      //var_dump($arr_viewsList_proper);
      if ($this->pObj->b_drs_flexform) {
        $str_prompt = implode('.', array_keys($this->pObj->conf['views.']['list.']));
        $str_prompt = str_replace('..', ', ', $str_prompt);
        $str_prompt = str_replace('.', '', $str_prompt);
        t3lib_div :: devlog('[INFO/FLEXFORM] This views will displayed: \'' . $str_prompt . '\'', $this->pObj->extKey, 0);
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
    if (!$plugin_singlePid) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewsSinglePid isn\'t set.', $this->pObj->extKey, 0);
      }
    }
    if ($plugin_singlePid) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewsSinglePid: \'' . $plugin_singlePid . '\'!', $this->pObj->extKey, 0);
      }
    }
    // Get the single pid from the plugin

    // Set the single pid in the local displayList
    if (isset ($this->pObj->conf['views.']['list.'][$this->mode . '.']['displayList.']['singlePid'])) {
      // Get value from TypoScript
      $conf_singlePid = $this->pObj->conf['views.']['list.'][$this->mode . '.']['displayList.']['singlePid'];
      $str_path = 'views.list.' . $this->mode . '.displayList.singlePid';
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . $conf_singlePid . '\'', $this->pObj->extKey, 0);
      }
      // Get value from TypoScript
      // Set the plugin single pid to the current pageId, if it is empty
      if (!$plugin_singlePid) {
        $plugin_singlePid = $GLOBALS['TSFE']->id;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] Plugin SinglePid was empty. It is overriden with the current page id: \'' . $plugin_singlePid . '\'!', $this->pObj->extKey, 0);
        }
      }
      // Set the plugin single pid to the current pageId, if it is empty
      // Set value in TypoScript
      $this->pObj->conf['views.']['list.'][$this->mode . '.']['displayList.']['singlePid'] = $plugin_singlePid;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript value \'' . $conf_singlePid . '\' is overriden with \'' . $plugin_singlePid . '\'!', $this->pObj->extKey, 0);
      }
      // Set value in TypoScript
    }
    // Set the single pid in the local displayList

    // Set the single pid in the global displayList
    if (!isset ($this->pObj->conf['views.']['list.'][$this->mode . '.']['displayList.']['singlePid'])) {
      // Get value from TypoScript
      $conf_singlePid = $this->pObj->conf['displayList.']['singlePid'];
      $str_path = 'displayList.singlePid';
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . $conf_singlePid . '\'', $this->pObj->extKey, 0);
      }
      // Get value from TypoScript
      // Set the plugin single pid to the current pageId, if it is empty
      if (!$plugin_singlePid) {
        $plugin_singlePid = $GLOBALS['TSFE']->id;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] Plugin SinglePid was empty. It is overriden with the current page id: \'' . $plugin_singlePid . '\'!', $this->pObj->extKey, 0);
        }
      }
      // Set the plugin single pid to the current pageId, if it is empty
      // Set value in TypoScript
      $this->pObj->conf['displayList.']['singlePid'] = $plugin_singlePid;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript value \'' . $conf_singlePid . '\' is overriden with \'' . $plugin_singlePid . '\'!', $this->pObj->extKey, 0);
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
    if (empty ($int_viewsListPid)) {
      $this->int_viewsListPid = false;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewsListPid isn\'t set.', $this->pObj->extKey, 0);
      }
    }
    if (!empty ($int_viewsListPid)) {
      $this->int_viewsListPid = $int_viewsListPid;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewsListPid: \'' . $int_viewsListPid . '\'!', $this->pObj->extKey, 0);
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
 * @return    void
 */
  function sheet_advanced() {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];

    $conf = $this->pObj->conf;
    $modeWiDot = (int) $this->mode . '.';
    $viewWiDot = $this->pObj->view . '.';
    $conf_view = $this->pObj->conf['views.'][$viewWiDot][$modeWiDot];

    //////////////////////////////////////////////////////////////////////
    //
    // Field performance_select

    $str_performance_select = $this->pObj->pi_getFFvalue($arr_piFlexform, 'performance_select', 'advanced', 'lDEF', 'vDEF');

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] advanced/performance_select: \'' . $str_performance_select . '\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    // Field performance_select

    //////////////////////////////////////////////////////////////////////
    //
    // Get global or local array advanced

    $bool_advanced_is_local = false;
    if (!empty ($conf_view['advanced.'])) {
      $bool_advanced_is_local = true;
    }
    // Get global or local array advanced

    //////////////////////////////////////////////////////////////////////
    //
    // Field $GLOBALS

    // Default configuration
    if ($str_performance_select == 'default') {
      #10116
      if ($bool_advanced_is_local) {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
      }
      if (!$bool_advanced_is_local) {
        $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
      }
    }
    // Default configuration

    // Configured by user
    if ($str_performance_select != 'default') {
      $int_performance_costs = $this->pObj->pi_getFFvalue($arr_piFlexform, 'performance_costs', 'advanced', 'lDEF', 'vDEF');
      switch ($int_performance_costs) {
        case (0) :
          // Set value in TypoScript
          #10116
          if ($bool_advanced_is_local) {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
          }
          if (!$bool_advanced_is_local) {
            $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 1;
          }
          break;
        case (1) :
          // Set value in TypoScript
          #10116
          if ($bool_advanced_is_local) {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 0;
          }
          if (!$bool_advanced_is_local) {
            $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] = 0;
          }
          break;
        default :
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
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] advanced/performance_costs<br />
                  look_for_globals: \'' . $this->pObj->conf['advanced.']['performance.']['GLOBALS.']['dont_replace'] . '\'', $this->pObj->extKey, 0);
      }
    }
    // Configured by user

    // Field $GLOBALS

    //////////////////////////////////////////////////////////////////////
    //
    // Field realUrl_select

    $str_realUrl_select = $this->pObj->pi_getFFvalue($arr_piFlexform, 'realUrl_select', 'advanced', 'lDEF', 'vDEF');

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] advanced/realUrl_select: \'' . $str_realUrl_select . '\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    // Field realUrl_select

    //////////////////////////////////////////////////////////////////////
    //
    // Field realUrl

    // Default configuration
    if ($str_realUrl_select == 'default') {
      // Do nothing. Take default values from the top of this class..
    }
    // Default configuration

    // Configured by user
    if ( $str_realUrl_select == 'configured' )
    {
      $int_realUrl = $this->pObj->pi_getFFvalue($arr_piFlexform, 'realUrl', 'advanced', 'lDEF', 'vDEF');

      $this->bool_linkToSingle_wi_piVar_indexBrowserTab = (($int_realUrl & 1) == 1);
      $this->bool_linkToSingle_wi_piVar_mode = (($int_realUrl & 2) == 2);
      $this->bool_linkToSingle_wi_piVar_pointer = (($int_realUrl & 4) == 4);
      $this->bool_linkToSingle_wi_piVar_plugin = (($int_realUrl & 8) == 8);
      $this->bool_linkToSingle_wi_piVar_sort = (($int_realUrl & 16) == 16);

    }
      // DRS
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] advanced/realUrl<br />
                int_realUrl: \'' . $int_realUrl . '\'<br />
                bool_linkToSingle_wi_piVar_indexBrowserTab: \'' . $this->bool_linkToSingle_wi_piVar_indexBrowserTab . '\'<br />
                bool_linkToSingle_wi_piVar_mode: \'' . $this->bool_linkToSingle_wi_piVar_mode . '\'<br />
                bool_linkToSingle_wi_piVar_pointer: \'' . $this->bool_linkToSingle_wi_piVar_pointer . '\'<br />
                bool_linkToSingle_wi_piVar_plugin: \'' . $this->bool_linkToSingle_wi_piVar_plugin . '\'<br />
                bool_linkToSingle_wi_piVar_sort: \'' . $this->bool_linkToSingle_wi_piVar_sort . '\'', $this->pObj->extKey, 0);
    }
      // DRS
    // Field searchForm

    return;
  }



  /**
 * Sheet evaluate: Configuration for evaluation
 *
 * @return    void
 * @version 4.0.0
 * @since   4.0.0
 */
  function sheet_evaluate( )
  {
    $sheet          = 'evaluate';
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];



      //////////////////////////////////////////////////////////////////////
      //
      // Field dontUseDRS

    $this->pObj->bool_dontUseDRS = $this->pObj->pi_getFFvalue($arr_piFlexform, 'dontUseDRS', $sheet, 'lDEF', 'vDEF');
    if ($this->pObj->bool_dontUseDRS)
    {
      if ($this->pObj->arr_extConf['drs_mode'] != 'Don\'t log anything')
      {
        t3lib_div :: devlog('[INFO/DRS] Plugin Sheet [Development] set the boolean Don\'t use DRS.', $this->pObj->extKey, 0);
        $this->pObj->arr_extConf['drs_mode'] = 'Don\'t log anything';
        $this->pObj->init_drs();
        t3lib_div :: devlog('[WARN/DRS] DRS is disabled.', $this->pObj->extKey, 2);
        if ($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if (!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div :: devLog('[INFO/PERFORMANCE]: ' . ($endTime - $this->pObj->tt_startTime) . ' ms', $this->pObj->extKey, 0);
      }
    }
      // Field dontUseDRS



      //////////////////////////////////////////////////////////////////////
      //
      // Field debugJSS

    $this->pObj->bool_debugJSS = $this->pObj->pi_getFFvalue($arr_piFlexform, 'debugJSS', $sheet, 'lDEF', 'vDEF');
      //var_dump('conf 1024', $this->pObj->bool_debugJSS);

    return;
  }






  /**
 * Sheet extend:  Administration of extensions for the Browser.
 *                New in version 4.0
 *                Available extension only: Browser Calendar UI
 *
 * @return    void
 * @version 4.0.0
 * @since   4.0.0
 */
  function sheet_extend( )
  {

    $sheet          = 'extend';
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];



      //////////////////////////////////////////////////////////////////////
      //
      // Field cal_ui

    $field = 'cal_ui';
    $this->sheet_extend_cal_ui = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_cal )
    {
      t3lib_div :: devlog('[INFO/FLEXFORM+CAL/UI] ' .
      $sheet . '.' . $field . ': \'' . $this->sheet_extend_cal_ui . '\'', $this->pObj->extKey, 0);
    }
      // Field cal_ui



      //////////////////////////////////////////////////////////////////////
      //
      // Field cal_view

    $field = 'cal_view';
    $this->sheet_extend_cal_view = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_cal )
    {
      t3lib_div :: devlog('[INFO/FLEXFORM+CAL/UI] ' .
      $sheet . '.' . $field . ': \'' . $this->sheet_extend_cal_view . '\'', $this->pObj->extKey, 0);
    }
      // Field cal_view



      //////////////////////////////////////////////////////////////////////
      //
      // Field cal_field_start

    $field = 'cal_field_start';
    $this->sheet_extend_cal_field_start = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_cal )
    {
      t3lib_div :: devlog('[INFO/FLEXFORM+CAL/UI] ' .
      $sheet . '.' . $field . ': \'' . $this->sheet_extend_cal_field_start . '\'', $this->pObj->extKey, 0);
    }
      // Field cal_field_start



      //////////////////////////////////////////////////////////////////////
      //
      // Field cal_field_end

    $field = 'cal_field_end';
    $this->sheet_extend_cal_field_end = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_cal )
    {
      t3lib_div :: devlog('[INFO/FLEXFORM+CAL/UI] ' .
      $sheet . '.' . $field . ': \'' . $this->sheet_extend_cal_field_end . '\'', $this->pObj->extKey, 0);
    }
      // Field cal_field_end



    return;
  }



  /**
 * sheet_javascript(): If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return    void
 * @version 3.7.0
 * @since 3.5.0
 */
  function sheet_javascript() {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $sheet = 'javascript';



      //////////////////////////////////////////////////////////////////////
      //
      // Field jquery_library
      // #13429, dwildt, 110519

    $field = 'jquery_library';
    $this->str_jquery_library = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    switch ($this->str_jquery_library) {
      case (false) :
      case ('typoscript') :
        $this->str_jquery_library = 'typoscript';
        break;
      case ('http://code.jquery.com/jquery-1.6.min.js') :
        $this->pObj->conf['javascript.']['jquery.']['library'] = $this->str_jquery_library;
        break;
      case ('configured') :
        $str_jquery_library_own = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_library_own', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['jquery.']['library'] = $str_jquery_library_own;
        break;
      case ('none') :
        $this->pObj->conf['javascript.']['jquery.']['library'] = null;
        break;
      default :
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
    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
    {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'jquery_library: \'' . $this->str_jquery_library . '\'', $this->pObj->extKey, 0);
    }
      // #13429, dwildt, 110519
      // Field jquery_library



      //////////////////////////////////////////////////////////////////////
      //
      // Field jquery_ui
      // #28562, dwildt, 110804

    $field = 'jquery_ui';
    $jquery_ui = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'jquery_ui: \'' . $jquery_ui . '\'', $this->pObj->extKey, 0);
    }

    switch ($jquery_ui) {
      case ('own') :
        $path = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_ui.own.path', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['jquery.']['ui'] = $path;
        $this->bool_jquery_ui = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.ui is set to ' . $path, $this->pObj->extKey, 0);
        }
        break;
      case ('no') :
        $this->pObj->conf['javascript.']['jquery.']['ui'] = null;
        $this->bool_jquery_ui = false;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.ui is set to null.', $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing;
        $this->bool_jquery_ui = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.ui isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // #28562, dwildt, 110804
      // Field jquery_ui



      //////////////////////////////////////////////////////////////////////
      //
      // Field jquery_plugins.t3browser
      // #28562, dwildt, 110804

    $field = 'jquery_plugins.t3browser';
    $jquery_plugins_t3browser = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'jquery_plugins.t3browser: \'' . $jquery_plugins_t3browser . '\'', $this->pObj->extKey, 0);
    }

    switch ($jquery_plugins_t3browser)
    {
      case ('own') :
        $plugin       = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_plugins.t3browser.own.plugin', $sheet, 'lDEF', 'vDEF');
        $library      = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_plugins.t3browser.own.library', $sheet, 'lDEF', 'vDEF');
        $localisation = $this->pObj->pi_getFFvalue($arr_piFlexform, 'jquery_plugins.t3browser.own.localisation', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin']       = $plugin;
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library']      = $library;
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation'] = $localisation;
        $this->bool_jquery_plugins_t3browser = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.plugin is set to '       . $plugin, $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.library is set to '      . $library, $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.localisation is set to ' . $localisation, $this->pObj->extKey, 0);
        }
        break;
      case ('no') :
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin']       = null;
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library']      = null;
        $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation'] = null;
        $this->bool_jquery_plugins_t3browser = false;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.plugin is set to null.',       $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.library is set to null.',      $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.localisation is set to null.', $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing;
        $this->bool_jquery_plugins_t3browser = true;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.plugin isn\'t changed by the flexform.', $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.library isn\'t changed by the flexform.', $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] javascript.jquery.plugins.t3browser.localisation isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // #28562, dwildt, 110804
      // Field jquery_plugins.t3browser



    //////////////////////////////////////////////////////////////////////
    //
    // Field browser_libraries
    // #13429, dwildt, 110519

    $this->str_browser_libraries = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries', $sheet, 'lDEF', 'vDEF');

    switch ($this->str_browser_libraries) {
      case (false) :
      case ('typoscript') :
        $this->str_browser_libraries = 'typoscript';
        break;
      case ('configured') :
        $str_browser_libraries_general = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_general', $sheet, 'lDEF', 'vDEF');
        $str_browser_libraries_ajax = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_ajax', $sheet, 'lDEF', 'vDEF');
        $str_browser_libraries_ajaxLL = $this->pObj->pi_getFFvalue($arr_piFlexform, 'browser_libraries_ajaxLL', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['javascript.']['ajax.']['file'] = $str_browser_libraries_general;
        $this->pObj->conf['javascript.']['ajax.']['fileLL'] = $str_browser_libraries_ajax;
        $this->pObj->conf['javascript.']['general.']['file'] = $str_browser_libraries_ajaxLL;
        break;
      case ('none') :
        $this->pObj->conf['javascript.']['ajax.']['file'] = null;
        $this->pObj->conf['javascript.']['ajax.']['fileLL'] = null;
        $this->pObj->conf['javascript.']['general.']['file'] = null;
        break;
      default :
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
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'browser_libraries: \'' . $this->str_browser_libraries . '\'', $this->pObj->extKey, 0);
      if ($this->str_browser_libraries == 'configured') {
        t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
        'browser_libraries_general: \'' . $str_browser_libraries_general . '\'', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
        'browser_libraries_ajax: \'' . $str_browser_libraries_ajax . '\'', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
        'browser_libraries_ajax_ll: \'' . $str_browser_libraries_ajaxLL . '\'', $this->pObj->extKey, 0);
      }
    }
    // #13429, dwildt, 110519
    // Field browser_libraries

    //////////////////////////////////////////////////////////////////////
    //
    // Field ajaxuse

    $str_ajax_mode = $this->pObj->pi_getFFvalue($arr_piFlexform, 'mode', $sheet, 'lDEF', 'vDEF');

    switch ($str_ajax_mode) {
      case (false) :
      case ('disabled') :
        break;
      case ('list_only') :
        $this->bool_ajax_enabled = 1;
        break;
      case ('list_and_single') :
        $this->bool_ajax_enabled = 1;
        $this->bool_ajax_single = 1;
        break;
      default :
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
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'AJAX enabled: \'' . (int) $this->bool_ajax_enabled . '\'', $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      'AJAX with list and single view: \'' . (int) $this->bool_ajax_single . '\'<br />', $this->pObj->extKey, 0);
    }
    // Field ajaxuse

    //////////////////////////////////////////////////////////////////////
    //
    // Field list_transition

    if ($this->bool_ajax_enabled) {
      $this->str_ajax_list_transition = $this->pObj->pi_getFFvalue($arr_piFlexform, 'list_transition', $sheet, 'lDEF', 'vDEF');
      if (empty ($this->str_ajax_list_transition)) {
        $this->str_ajax_list_transition = 'collapse';
      }
      if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
        t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
        'Transition for list view: \'' . $this->str_ajax_list_transition . '\'', $this->pObj->extKey, 0);
      }
      if ($this->bool_ajax_single) {
        $this->str_ajax_single_transition = $this->pObj->pi_getFFvalue($arr_piFlexform, 'single_transition', $sheet, 'lDEF', 'vDEF');
        if (empty ($this->str_ajax_single_transition)) {
          $this->str_ajax_single_transition = 'collapse';
        }
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
          t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
          'Transition for single view: \'' . $this->str_ajax_single_transition . '\'', $this->pObj->extKey, 0);
        }
      }
    }
    // Field list_transition

    //////////////////////////////////////////////////////////////////////
    //
    // Field list_on_single

    if ($this->bool_ajax_enabled) {
      if ($this->bool_ajax_single) {
        $this->str_ajax_list_on_single = $this->pObj->pi_getFFvalue($arr_piFlexform, 'list_on_single', $sheet, 'lDEF', 'vDEF');
        if (empty ($this->str_ajax_list_on_single)) {
          $this->str_ajax_list_on_single = 'single';
        }
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
          t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
          'Single view: \'' . $this->str_ajax_list_on_single . '\'', $this->pObj->extKey, 0);
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
 * @return    void
 * @since 2.x.x
 * @version 3.4.4
 */
  function sheet_sDEF( )
  {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $modeWiDot = (int) $this->mode . '.';
    $viewWiDot = $this->pObj->view . '.';

    $sheet = 'sDEF';


      //////////////////////////////////////////////////////////////////////
      //
      // Field relations_select

    $field      = 'relations_select';
    $relations  = false;
    $joins      = -1;
    $root       = -1;
    $relations_select = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if ($relations_select == 'default' OR empty ($relations_select)) {
      $relations = 'all';
      $joins = 1;
      $root = 0;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] relations_select is default.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] relations is set to all.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] joins is set to 1.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] root is set to 0.', $this->pObj->extKey, 0);
      }
    }
      // Field relations_select



      //////////////////////////////////////////////////////////////////////
      //
      // Field relations

    $field = 'relations';
    if (!$relations) {
      $relations = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    }
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] relations: \'' . $relations . '\'!', $this->pObj->extKey, 0);
    }
    $bool_typoscript = false;
    $bool_error = false;
    #9879
    switch ($relations) {
      case ('all') :
        $bool_typoscript = true;
        $bool_simpleRealations = 1;
        $bool_mmRealations = 1;
        break;
      case ('mm') :
        $bool_typoscript = true;
        $bool_simpleRealations = 0;
        $bool_mmRealations = 1;
        break;
      case ('single') :
        $bool_typoscript = true;
        $bool_simpleRealations = 1;
        $bool_mmRealations = 0;
        break;
      case ('typoscript') :
        $bool_typoscript = false;
        break;
      default :
        // 3.4.0
        $bool_typoscript = true;
        $bool_simpleRealations = 1;
        $bool_mmRealations = 1;
        // 3.4.0
        //$bool_error = TRUE;
    }
    if ($bool_error) {
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
                  ' . $this->pObj->pi_getLL('config_error_h1') . '
                </h1>
                <p>
                  ' . $str_prompt . '
                </p>
                <p>
                  ' . $str_reload . '
                </p>
                </div>';
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[ERROR/FLEXFORM] ' . $str_prompt . '!', $this->pObj->extKey, 3);
      }
    }
    if (!$bool_error) {
      #9879
      if (!empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'])) {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['simpleRelations'] = $bool_simpleRealations;
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['mmRelations'] = $bool_mmRealations;
      }
      if (empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'])) {
        $this->pObj->conf['autoconfig.']['relations.']['simpleRelations'] = $bool_simpleRealations;
        $this->pObj->conf['autoconfig.']['relations.']['mmRelations'] = $bool_mmRealations;
      }
    }
    if ($this->pObj->b_drs_flexform) {
      if (!$bool_error) {
        if ($bool_typoscript) {
          $path_view = null;
          if (!empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'])) {
            $path_view = 'views.' . $viewWiDot . $modeWiDot;
          }
          $str_simple = $path_view . 'autoconfig.relations.simpleRelations';
          t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript ' . $str_simple . ' is set to: ' . $bool_simpleRealations . '.', $this->pObj->extKey, 0);
          $str_mm = $path_view . 'autoconfig.relations.mmRelations';
          t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript ' . $str_mm . ' is set to: ' . $bool_mmRealations . '.', $this->pObj->extKey, 0);
        }
        if (!$bool_typoscript) {
          t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript isn\'t changed.', $this->pObj->extKey, 0);
        }
      }
    }
    // Field relations

    //////////////////////////////////////////////////////////////////////
    //
    // Field joins

    #9879
    if ( $joins < 0 )
    {
      $joins = $this->pObj->pi_getFFvalue( $arr_piFlexform, 'joins', $sheet, 'lDEF', 'vDEF' );
        // 111201, dwildt+
        // default value
      switch( true )
      {
        case( $joins === null ):
        case( $joins === '' ):
          $joins = true;
      }
        // 111201, dwildt+

    }
    if ( ! empty ( $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'] ) )
    {
      $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['relations.']['left_join'] = $joins;
    }
    if ( empty ( $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'] ) )
    {
      $this->pObj->conf['autoconfig.']['relations.']['left_join'] = $joins;
    }
    if ($this->pObj->b_drs_flexform) {
      $path_view = null;
      if (!empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'])) {
        $path_view = 'views.' . $viewWiDot . $modeWiDot;
      }
      $str_path = $path_view . 'autoconfig.relations.left_join';
      t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript ' . $str_path . ' is set to: ' . $joins . '.', $this->pObj->extKey, 0);
    }
    // Field joins

    //////////////////////////////////////////////////////////////////////
    //
    // Field root

    if ($root < 0) {
      $root = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'root', $sheet, 'lDEF', 'vDEF');
    }
    if ($root == 1) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] Root is set.', $this->pObj->extKey, 0);
      }
      if (strstr($this->pObj->cObj->currentRecord, 'tt_content')) {
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] pidList before changing: ' . $this->pObj->pidList, $this->pObj->extKey, 0);
        }
        if ($this->pObj->pidList) {
          $this->pObj->pidList = '0,' . $this->pObj->pidList;
        }
        if (!$this->pObj->pidList) {
          $this->pObj->pidList = '0';
        }
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] pidList after changing: ' . $this->pObj->pidList, $this->pObj->extKey, 0);
        }
      }
    }
    if ($root != 1) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] Root isn\'t set.', $this->pObj->extKey, 0);
      }
    }
    // Field root



      //////////////////////////////////////////////////////////////////////
      //
      // Field controlling.enabled

      // #31230, 31229: Statistics module

    $bool_controllingEnable = false;
    $field_1                = 'controlling';
    $field_2                = 'enabled';
    $field                  = $field_1 . '.' . $field_2;
    $value                  = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    switch( $value )
    {
      case( '' ) :
      case( null ) :
      case( 'no' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 0;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'0\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'yes' ) :
        $bool_controllingEnable = true;
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 1;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'1\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'ts' ) :
        // Do nothing
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is \'' . $value . '\'. Nothing will changed.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
    }
      // Field controlling.enabled



      //////////////////////////////////////////////////////////////////////
      //
      // Field controlling.adjustment.display_if_in_list

    $field_1          = 'controlling';
    $field_2          = 'adjustment';
    $field_3          = 'display_if_in_list';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_controllingEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field controlling.adjustment.display_if_in_list



      //////////////////////////////////////////////////////////////////////
      //
      // Field controlling.adjustment.hide_if_in_list

    $field_1          = 'controlling';
    $field_2          = 'adjustment';
    $field_3          = 'hide_if_in_list';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_controllingEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field controlling.adjustment.hide_if_in_list



      //////////////////////////////////////////////////////////////////////
      //
      // Field session

    $int_sessionType = -1;
    $int_session = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'session', $sheet, 'lDEF', 'vDEF');
    if ($int_session == 0 OR empty ($int_session)) {
      $int_sessionType = 1; // Session is enabled
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] session is 0.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] session.type is set to 1.', $this->pObj->extKey, 0);
      }
    }
      // Field session


      //////////////////////////////////////////////////////////////////////
      //
      // Field session.type

      // session.type isn't set above
    if ($int_sessionType < 0) {
      $int_sessionType = (int) $this->pObj->pi_getFFvalue($arr_piFlexform, 'session.type', $sheet, 'lDEF', 'vDEF');
    }
      // session.type isn't set above

    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] session.type: \'' . $int_sessionType . '\'!', $this->pObj->extKey, 0);
    }

    switch ($int_sessionType) {
      case (0) :
        // typoscript
        // Do nothing
        $value = $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'];
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case (1) :
        // enabled
        $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'] = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case (2) :
        // disabled
        $this->pObj->conf['advanced.']['session_manager.']['session.']['enabled'] = false;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] advanced.session_manager.session.enabled is set to false.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field session.type



      //////////////////////////////////////////////////////////////////////
      //
      // Field downloads.enabled

      // #31230, 31229: downloads module

    $bool_downloadsEnable = false;
    $field_1              = 'downloads';
    $field_2              = 'enabled';
    $field                = $field_1 . '.' . $field_2;
    $value                = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    switch( $value )
    {
      case( '' ) :
      case( null ) :
      case( 'no' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 0;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'0\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'yes' ) :
        $bool_downloadsEnable = true;
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 1;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'1\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'ts' ) :
        // Do nothing
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is \'' . $value . '\'. Nothing will changed.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
    }
      // Field downloads.enabled



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.enabled

      // #31230, 31229: Statistics module

    $bool_statisticsEnable  = false;
    $field_1                = 'statistics';
    $field_2                = 'enabled';
    $field                  = $field_1 . '.' . $field_2;
    $value                  = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    switch( $value )
    {
      case( '' ) :
      case( null ) :
      case( 'no' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 0;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'0\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'yes' ) :
        $bool_statisticsEnable = true;
        $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.']['value'] = 1;
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is set to \'1\'.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
      case( 'ts' ) :
        // Do nothing
        if ( $this->pObj->b_drs_flexform )
        {
          $prompt = $sheet . '.' . $field . ' is \'' . $value . '\'. Nothing will changed.';
          t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
    }
      // Field statistics.enabled



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.timeout

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'timeout';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3;
    $value            = (int) $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.timeout



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.dontAccountIPsOfCsvList

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'dontAccountIPsOfCsvList';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.dontAccountIPsOfCsvList



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.debugging

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'debugging';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      switch( $value )
      {
        case( '' ) :
        case( null ) :
        case( 'no' ) :
          $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = 0;
          if ( $this->pObj->b_drs_flexform )
          {
            $prompt = $sheet . '.' . $field . ' is set to \'0\'.';
            t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
          }
          break;
        case( 'yes' ) :
          $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.']['value'] = 1;
          if ( $this->pObj->b_drs_flexform )
          {
            $prompt = $sheet . '.' . $field . ' is set to \'1\'.';
            t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
          }
          break;
        case( 'ts' ) :
          // Do nothing
          if ( $this->pObj->b_drs_flexform )
          {
            $prompt = $sheet . '.' . $field . ' is \'' . $value . '\'. Nothing will changed.';
            t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
          }
          break;
      }
    }
      // Field statistics.adjustment.debugging



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.fields.hits

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'fields';
    $field_4          = 'hits';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3. '.' . $field_4;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'][$field_4 . '.']['label.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.fields.hits



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.fields.visits

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'fields';
    $field_4          = 'visits';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3. '.' . $field_4;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'][$field_4 . '.']['label.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.fields.visits



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.fields.downloads

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'fields';
    $field_4          = 'downloads';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3. '.' . $field_4;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'][$field_4 . '.']['label.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.fields.downloads



      //////////////////////////////////////////////////////////////////////
      //
      // Field statistics.adjustment.fields.downloadsByVisits

    $field_1          = 'statistics';
    $field_2          = 'adjustment';
    $field_3          = 'fields';
    $field_4          = 'downloadsByVisits';
    $field            = $field_1 . '.' . $field_2. '.' . $field_3. '.' . $field_4;
    $value            = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if( $bool_statisticsEnable )
    {
      $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'][$field_4 . '.']['label.']['value'] = $value;
      if ( $this->pObj->b_drs_flexform )
      {
        $prompt = $sheet . '.' . $field . ' is set to \'' . $value . '\'.';
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Field statistics.adjustment.fields.downloadsByVisits


    return;
  }




  /**
 * The sheet socialmedia administrates bookmarks.
 *
 * @return    void
 */
  function sheet_socialmedia() {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];

    $str_enabled = $this->pObj->pi_getFFvalue($arr_piFlexform, 'enabled', 'socialmedia', 'lDEF', 'vDEF');
    switch ($str_enabled) {
      case (false) :
      case ('disabled') :
        // RETURN if bookmarks are disabled
        if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_socialmedia) {
          t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks are disabled.', $this->pObj->extKey, 0);
        }
        return;
        // RETURN if bookmarks are disabled
        break;
      case ('enabled_wi_browser_template') :
        $this->str_socialmedia_bookmarks_tableFieldSite_list = $this->pObj->pi_getFFvalue($arr_piFlexform, 'tablefieldSite_list', 'socialmedia', 'lDEF', 'vDEF');
        $this->str_socialmedia_bookmarks_tableFieldSite_single = $this->pObj->pi_getFFvalue($arr_piFlexform, 'tablefieldSite_single', 'socialmedia', 'lDEF', 'vDEF');
      case ('enabled_wi_browser_template') :
      case ('enabled_wi_individual_template') :
        $this->str_socialmedia_bookmarks_enabled = $str_enabled;
        $this->str_socialmedia_bookmarks_tableFieldTitle_list = $this->pObj->pi_getFFvalue($arr_piFlexform, 'tablefieldTitle_list', 'socialmedia', 'lDEF', 'vDEF');
        $this->str_socialmedia_bookmarks_tableFieldTitle_single = $this->pObj->pi_getFFvalue($arr_piFlexform, 'tablefieldTitle_single', 'socialmedia', 'lDEF', 'vDEF');
        $this->strCsv_socialmedia_bookmarks_list = $this->pObj->pi_getFFvalue($arr_piFlexform, 'bookmarks_list', 'socialmedia', 'lDEF', 'vDEF');
        $this->strCsv_socialmedia_bookmarks_single = $this->pObj->pi_getFFvalue($arr_piFlexform, 'bookmarks_single', 'socialmedia', 'lDEF', 'vDEF');
        break;
      default :
        echo 'ERROR: ' . __METHOD__ . ' (' . __LINE__ . ')';
        exit;
    }

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_socialmedia) {
      $str_bookmarks_list = str_replace(',', ', ', $this->strCsv_socialmedia_bookmarks_list);
      $str_bookmarks_single = str_replace(',', ', ', $this->strCsv_socialmedia_bookmarks_single);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks are enabled: ' .
      $this->str_socialmedia_bookmarks_enabled, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for site: ' .
      $this->str_socialmedia_bookmarks_tableFieldSite_list, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for title: ' .
      $this->str_socialmedia_bookmarks_tableFieldTitle_list, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views: ' .
      $str_bookmarks_list, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for site: ' .
      $this->str_socialmedia_bookmarks_tableFieldSite_list, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in list views - table.field for title: ' .
      $this->str_socialmedia_bookmarks_tableFieldTitle_list, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FLEXFORM] socialmedia/bookmarks in single views: ' .
      $str_bookmarks_single, $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    return;
  }

  /**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return    void
 * @since   3.0.1
 * @version 3.4.4
 */
  function sheet_tca( )
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $modeWiDot      = (int) $this->mode . '.';
    $viewWiDot      = $this->pObj->view . '.';

      //////////////////////////////////////////////////////////////////////
      //
      // Field configuration

    $str_configuration = $this->pObj->pi_getFFvalue( $arr_piFlexform, 'configuration', 'tca', 'lDEF', 'vDEF' );

    switch ( $str_configuration )
    {
      case ('adjusted') :
        $arr_csvValue = array (
          'title',
          'image',
          'imageCaption',
          'imageAltText',
          'imageTitleText',
          'document',
          'timestamp'
        );
        foreach ( ( array ) $arr_csvValue as $str_csvValue )
        {
          $str_csvFields = $this->pObj->pi_getFFvalue( $arr_piFlexform, $str_csvValue . '_csvFields', 'tca', 'lDEF', 'vDEF' );
            // #9879
          if ( ! empty ( $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'] ) )
          {
            $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.']['autoDiscover.']['items.'][$str_csvValue . '.']['TCAlabel.']['csvValue'] = $str_csvFields;
          }
          if ( empty ( $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'] ) )
          {
            $this->pObj->conf['autoconfig.']['autoDiscover.']['items.'][$str_csvValue . '.']['TCAlabel.']['csvValue'] = $str_csvFields;
          }
          if ($this->pObj->b_drs_flexform) {
            $path_view = null;
            if (!empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['autoconfig.'])) {
              $path_view = 'views.' . $viewWiDot . $modeWiDot;
            }
            t3lib_div :: devlog('[INFO/FLEXFORM] tca: ' . $path_view . 'autoconfig.autoDiscover.items.' . $str_csvValue . '.TCAlabel.csvValue is set to \'' . $str_csvFields . '\'', $this->pObj->extKey, 0);
          }
        }
        break;
      default :
        // Do nothing
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] tca: configuration is default. Nothing to do.', $this->pObj->extKey, 0);
        }
        break;
    }

    return;
  }

  /**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return    void
 * @version 3.6.2
 */
  function sheet_templating()
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $modeWiDot = (int) $this->mode . '.';
    $viewWiDot = $this->pObj->view . '.';
    $sheet = 'templating';
    // #9689
    $str_path2template = false;



      //////////////////////////////////////////////////////////////////////
      //
      // Field template

    $str_template = $this->pObj->pi_getFFvalue($arr_piFlexform, 'template', $sheet, 'lDEF', 'vDEF');
    $bool_doNothing = false;
    switch ($str_template) {
      case ('typoscript') :
        // Do nothing;
        // #9689
        $bool_doNothing = true;
        break;
      case ('adjusted') :
        $str_path = $this->pObj->pi_getFFvalue($arr_piFlexform, 'path', $sheet, 'lDEF', 'vDEF');
        // #9689
        if (empty ($str_path)) {
          // #11418, cweiske, 101219
          echo '<div style="background:red;color:white;font-weight:bold;padding:2em;text-align:center;">' .
          '  ERROR with the template: You have not uploaded any template!<br />' .
          '  <br />' .
          '  Browser - TYPO3 without PHP.' .
          '</div>';
        }
        $str_path2template = 'uploads/tx_browser/' . $str_path;
        break;
      default :
        #10221: RSS-Feed
        $str_path2template = $str_template;
    }
    if (!$bool_doNothing) {
      if (empty ($str_path2template)) {
        echo '<div style="background:red;color:white;font-weight:bold;padding:2em;text-align:center;">' .
        '  ERROR with the template: Path to the template is empty!' .
        '  <br />' .
        '  Browser - TYPO3 without PHP.' .
        '</div>';
      }
      if (!empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file'])) {
        $this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file'] = $str_path2template;
      }
      if (empty ($this->pObj->conf['views.'][$viewWiDot][$modeWiDot]['template.']['file'])) {
        $this->pObj->conf['template.']['file'] = $str_path2template;
        // Global HTML Template
      }
    }
    // #9689

    //////////////////////////////////////////////////////////////////////
    //
    // Field css.browser
    // #28562, dwildt, 110806

    $field = 'css.browser';
    $css_browser = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
      $field . ': \'' . $css_browser . '\'', $this->pObj->extKey, 0);
    }

    switch ($css_browser) {
      case ('own') :
        $css = $this->pObj->pi_getFFvalue($arr_piFlexform, 'css.browser.own.path', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['template.']['css.']['browser'] = $css;
        $this->bool_css_browser = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.browser is set to ' . $css, $this->pObj->extKey, 0);
        }
        break;
        // #29778, 110915, dwildt
      //case ('none') :
      case ('no') :
        $this->pObj->conf['template.']['css.']['browser'] = null;
        $this->bool_css_browser = false;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.browser is set to null', $this->pObj->extKey, 0);
        }
        break;
      case ( null ) :
      case ( '' ) :
      case ( 'ts' ) :
          // #29336, 111130, dwildt
          // Do nothing;
        $this->bool_css_browser = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.browser isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      default :
        $prompt = '
                  <div style="background:white; font-weight:bold;border:.4em solid orange;">
                    <h1>
                      WARNING
                    </h1>
                    <p>
                      Flexform field has an invalid value. The value isn\'t defined.<br />
                      sheet: ' . $sheet . '<br />
                      field: ' . $field . '<br />
                      value: ' . $css_browser . '<br />
                      ' . __METHOD__ . ' (' . __LINE__ . ')
                    </p>
                    <p>
                      Please save the plugin/flexform of the browser. The bug will be fixed probably.
                    </p>
                  </div>';
        echo $prompt;
    }
    // #28562, dwildt, 110806
    // Field css.browser

    //////////////////////////////////////////////////////////////////////
    //
    // Field css.jqui
    // #28562, dwildt, 110806

    $field = 'css.jqui';
    $css_jqui = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript) {
      t3lib_div :: devlog('[INFO/FLEXFORM+JSS] ' .
        $field . ': \'' . $css_jqui . '\'', $this->pObj->extKey, 0);
    }

    switch( $css_jqui )
    {
      case ( 'black_tie' ) :
      case ( 'blitzer' ) :
      case ( 'cupertino' ) :
      case ( 'dark_hive' ) :
      case ( 'darkness' ) :
      case ( 'dot_luv' ) :
      case ( 'eggplant' ) :
      case ( 'excite_bike' ) :
      case ( 'flick' ) :
      case ( 'hot_sneaks' ) :
      case ( 'humanity' ) :
      case ( 'le_frog' ) :
      case ( 'lightness' ) :
      case ( 'mint_choc' ) :
      case ( 'netzmacher' ) :
      case ( 'overcast' ) :
      case ( 'pepper_grinder' ) :
      case ( 'redmond' ) :
        // #43741, dwildt, 1+
      case ( 'smoothness' ) :
      case ( 'south_street' ) :
      case ( 'start' ) :
      case ( 'sunny' ) :
      case ( 'swanky_purse' ) :
      case ( 'trontastic' ) :
      case ( 'vader' ) :
        $css = $this->pObj->conf['flexform.']['templating.']['jquery_ui.'][$css_jqui . '.']['css'];
        $this->pObj->conf['template.']['css.']['jquery_ui'] = $css;
        $this->bool_css_jqui = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui is set to ' . $css, $this->pObj->extKey, 0);
        }
        break;
      case ( 'z_own' ) :
        $css = $this->pObj->pi_getFFvalue($arr_piFlexform, 'css.jqui.z_own.path', $sheet, 'lDEF', 'vDEF');
        $this->pObj->conf['template.']['css.']['jquery_ui'] = $css;
        $this->bool_css_jqui = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui is set to ' . $css, $this->pObj->extKey, 0);
        }
        break;
        // #43741, dwildt, 6-
//      case ( 'z_none' ) :
//        $this->pObj->conf['template.']['css.']['jquery_ui'] = null;
//        $this->bool_css_jqui = false;
//        if ($this->pObj->b_drs_flexform) {
//          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui is set to null', $this->pObj->extKey, 0);
//        }
//        break;
      case ( 'z_ts' ) :
        // Do nothing;
        $this->bool_css_jqui = true;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case ( null ) :
      case ( '' ) :
        // #43741, dwildt, 1-
      //case ( 'smoothness' ) :
        // #43741, dwildt, 1+
      case ( 'z_none' ) :
      default :
          // #43741, dwildt, 5+
        $this->pObj->conf['template.']['css.']['jquery_ui'] = null;
        $this->bool_css_jqui = false;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui is set to null', $this->pObj->extKey, 0);
        }
          // #43741, dwildt, 7-
//          // #29336, 111130, dwildt
//        $css = $this->pObj->conf['flexform.']['templating.']['jquery_ui.']['smoothness.']['css'];
//        $this->pObj->conf['template.']['css.']['jquery_ui'] = $css;
//        $this->bool_css_jqui = true;
//        if ($this->pObj->b_drs_flexform) {
//          t3lib_div :: devlog('[INFO/FLEXFORM] template.css.jquery_ui is set to ' . $css, $this->pObj->extKey, 0);
//        }
        break;
    }
    // #28562, dwildt, 110806
    // Field css.jqui

    //////////////////////////////////////////////////////////////////////
    //
    // Field dataQuery

    $this->int_templating_dataQuery = $this->pObj->pi_getFFvalue($arr_piFlexform, 'dataQuery', $sheet, 'lDEF', 'vDEF');
    // Field dataQuery

    //////////////////////////////////////////////////////////////////////
    //
    // Field wrapBaseClass

    // 12367, dwildt, 110310
    $int_wrapInBaseClass = $this->pObj->pi_getFFvalue($arr_piFlexform, 'wrapBaseClass', $sheet, 'lDEF', 'vDEF');

    switch ($int_wrapInBaseClass) {
      case (null) :
        // do nothing;
        break;
      case (0) :
        $this->bool_wrapInBaseClass = false;
        break;
      case (1) :
      default :
        $this->bool_wrapInBaseClass = true;
    }
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] templating/wrapBaseClass: \'' .
      $this->bool_wrapInBaseClass . '\'', $this->pObj->extKey, 0);
    }
    // Field wrapBaseClass

    //////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] templating: template.file is set to \'' . $this->pObj->conf['template.']['file'] . '\'.', $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System

    return;
  }









/**
 * If the current plugin has views selected, only the selected views are available for the plugin.
 * The method removes "unavailable" views from the TypoScript.
 *
 * @return    void
 * @version 4.1.25
 * @since   2.x
 */
  function sheet_viewList( )
  {

    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
      // 121025, dwildt, 1+
    $str_lang       = $this->pObj->lang->lang;
    $sheet          = 'viewList';



      //////////////////////////////////////////////////////////////////////
      //
      // Field display_listview
      // #42124, 121025, dwildt+

    $field = 'display_listview';
    $display_listview = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if ( $this->pObj->b_drs_flexform )
    {
      t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $field . ': \'' . $display_listview . '\'', $this->pObj->extKey, 0 );
    }

    switch ( $display_listview )
    {
      case ( null ) :
      case ( '' ) :
      case ( 'yes' ) :
        $this->pObj->conf['flexform.']['viewList.']['display_listview'] = 1;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewList.display_listview is set to ' . 1, $this->pObj->extKey, 0);
        }
        break;
      case ( 'no' ) :
        $this->pObj->conf['flexform.']['viewList.']['display_listview'] = 0;
        if ( $this->pObj->b_drs_flexform )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewList.display_listview is set to ' . 0, $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
          // Do nothing;
        if ($this->pObj->b_drs_flexform)
        {
          $value = $this->pObj->conf['flexform.']['viewList.']['display_listview'];
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewList.display_listview isn\'t changed: ' . $value, $this->pObj->extKey, 0);
        }
        break;
      default :
        $prompt = '
                  <div style="background:white; font-weight:bold;border:.4em solid orange;">
                    <h1>
                      WARNING
                    </h1>
                    <p>
                      Flexform field has an invalid value. The value isn\'t defined.<br />
                      sheet: ' . $sheet . '<br />
                      field: ' . $field . '<br />
                      value: ' . $display_listview . '<br />
                      ' . __METHOD__ . ' (' . __LINE__ . ')
                    </p>
                    <p>
                      Please save the plugin/flexform of the browser. The bug will be fixed probably.
                    </p>
                  </div>';
        echo $prompt;
    }
      // #42124, 121025, dwildt+
      // Field display_listview



      //////////////////////////////////////////////////////////////////////
      //
      // Field title

      // Get the title for the list view
    $str_title = $this->pObj->pi_getFFvalue($arr_piFlexform, 'title', $sheet, 'lDEF', 'vDEF');

      // Remove the title in case of csv export
      // #29370, 110831, dwildt+
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] title is ' . $str_title . ' but it will removed.',  $this->pObj->extKey, 0);
        }
        $str_title = null;
        break;
      default:
        // Do nothing;
    }
      // #29370, 110831, dwildt+
      // Remove the title in case of csv export

    if ( $str_title != null )
    {
      if ( $this->pObj->b_drs_flexform )
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/title: \'' . $str_title . '\'!', $this->pObj->extKey, 0);
      }
    }
    if ( $str_title == null )
    {
      if ( $this->pObj->b_drs_flexform )
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/title is empty.', $this->pObj->extKey, 0);
      }
    }
      // Get the title for the list view

      // View has a local marker array with my_title
    $conf_title = false;
    $str_path   = false;
    if ( $str_lang == 'default' )
    {
      if (isset ($this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['value']))
      {
        $conf_title = $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['value'];
        $str_path   = 'views.list.' . $this->mode . '.marker.my_title.value';
      }
    }
    if (  $str_lang != 'default'  )
    {
      if (isset ($this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['lang.'][$str_lang]))
      {
        $conf_title = $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['lang.'][$str_lang];
        $str_path   = 'views.list.' . $this->mode . '.marker.my_title.lang.' . $str_lang;
      }
    }
    if ( $str_path )
    {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . $conf_title . '\'', $this->pObj->extKey, 0);
      }
      if ($conf_title) {
        if ($str_title) {
          $conf_title = $str_title;
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] The plugin value has priority for TypoScript value: \'' . $conf_title . '\'!', $this->pObj->extKey, 0);
          }
        }
        if (!$str_title) {
          $str_title = $conf_title;
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] TypoScript value: \'' . $conf_title . '\'!', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_title) {
        $conf_title = $str_title;
        if ($str_lang == 'default') {
          $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['value'] = $conf_title;
        }
        if ($str_lang != 'default') {
          $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['lang.'][$str_lang] = $conf_title;
        }
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value was 0 or empty.', $this->pObj->extKey, 0);
        }
      }
    }
      // View has a local marker array with my_title

      // View hasn't a local marker array with my_title, we take the global one
    if ( ! $conf_title )
    {
      if ($str_lang == 'default')
      {
        $conf_title = $this->pObj->conf['marker.']['my_title.']['value'];
        $str_path = 'marker.my_title.value';
      }
      if ($str_lang != 'default')
      {
        $conf_title = $this->pObj->conf['marker.']['my_title.']['lang.'][$str_lang];
        $str_path = 'marker.my_title.lang.' . $str_lang;
      }
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . $conf_title . '\'', $this->pObj->extKey, 0);
      }
      if ($conf_title)
      {
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value has priority: \'' . $conf_title . '\'!', $this->pObj->extKey, 0);
        }
      }
      if (!$conf_title)
      {
        $conf_title = $str_title;
        if ($str_lang == 'default') {
          $this->pObj->conf['marker.']['my_title.']['value'] = $conf_title;
        }
        if ($str_lang != 'default') {
          $this->pObj->conf['marker.']['my_title.']['lang.'][$str_lang] = $conf_title;
        }
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value is overriden with: \'' . $conf_title . '\'!', $this->pObj->extKey, 0);
        }
      }
    }
      // View hasn't a local marker array with my_title, we take the global one
      // Field title



    //////////////////////////////////////////////////////////////////////
    //
    // Field titleWrap

    // Get the titleWrap for the list view
    $str_titleWrap = $this->pObj->pi_getFFvalue($arr_piFlexform, 'titleWrap', $sheet, 'lDEF', 'vDEF');
    if ($str_titleWrap) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/titleWrap: \'' . htmlspecialchars($str_titleWrap) . '\'!', $this->pObj->extKey, 0);
      }
    }
    if (!$str_titleWrap) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/titleWrap is empty.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] We try to get a title wrap from TypoScript.', $this->pObj->extKey, 0);
      }
    }
    // Get the titleWrap for the list view

    // View has a local marker array with my_titleWrap
    $conf_titleWrap = false;
    $str_path = false;
    if (isset ($this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['wrap'])) {
      $conf_titleWrap = $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['wrap'];
      $str_path = 'views.list.' . $this->mode . '.marker.my_title.wrap';
    }
    if ($str_path) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . htmlspecialchars($conf_titleWrap) . '\'', $this->pObj->extKey, 0);
      }
      if ($conf_titleWrap) {
        if (!$str_titleWrap) {
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] We take the local value.', $this->pObj->extKey, 0);
          }
        }
        if ($str_titleWrap) {
          $conf_titleWrap = $str_titleWrap;
          $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['wrap'] = $conf_titleWrap;
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] Local value will be overriden by the plugin value.', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_titleWrap) {
        $conf_titleWrap = $str_titleWrap;
        $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.']['wrap'] = $conf_titleWrap;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value was 0 or empty. It is overriden with: \'' . htmlspecialchars($conf_titleWrap) . '\'!', $this->pObj->extKey, 0);
        }
      }
    }
    // View has a local marker array with my_titleWrap

    // View hasn't any local marker array with my_titleWrap, we take the global one
    if (!$conf_titleWrap) {
      $conf_titleWrap = $this->pObj->conf['marker.']['my_title.']['wrap'];
      $str_path = 'marker.my_titleWrap.value';
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . htmlspecialchars($conf_titleWrap) . '\'', $this->pObj->extKey, 0);
      }
      if ($conf_titleWrap) {
        if (!$str_titleWrap) {
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] We take the local value.', $this->pObj->extKey, 0);
          }
        }
        if ($str_titleWrap) {
          $conf_titleWrap = $str_titleWrap;
          $this->pObj->conf['marker.']['my_title.']['wrap'] = $conf_titleWrap;
          if ($this->pObj->b_drs_flexform) {
            t3lib_div :: devlog('[INFO/FLEXFORM] Global value will be overriden by the plugin value.', $this->pObj->extKey, 0);
          }
        }
      }
      if (!$conf_titleWrap) {
        $conf_titleWrap = $str_titleWrap;
        $this->pObj->conf['marker.']['my_title.']['wrap'] = $conf_titleWrap;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value is overriden with: \'' . htmlspecialchars($conf_titleWrap) . '\'!', $this->pObj->extKey, 0);
        }
      }
    }
    // View hasn't any local marker array with my_titleWrap, we take the global one
    // Field titleWrap



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the title in case of csv export
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] title won\'t be handled.',  $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/EXPORT] views.list.' . $this->mode . '.marker.my_title is removed.',  $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/EXPORT] marker.my_title is removed.',  $this->pObj->extKey, 0);
        }
        unset( $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title'] );
        unset( $this->pObj->conf['views.']['list.'][$this->mode . '.']['marker.']['my_title.'] );
        unset( $this->pObj->conf['marker.']['my_title'] );
        unset( $this->pObj->conf['marker.']['my_title.'] );
        break;
      default:
        // Do nothing;
    }
      // Remove the title in case of csv export
      // csv export



    //////////////////////////////////////////////////////////////////////
    //
    // Field grouptitleWrap

    // Get the grouptitleWrap for the list view
    $str_grouptitleWrap = $this->pObj->pi_getFFvalue($arr_piFlexform, 'grouptitleWrap', $sheet, 'lDEF', 'vDEF');
    if ($str_grouptitleWrap) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/grouptitleWrap: \'' . htmlspecialchars($str_grouptitleWrap) . '\'!', $this->pObj->extKey, 0);
      }
      $this->pObj->str_wrap_grouptitle = $str_grouptitleWrap;
    }
    if (!$str_grouptitleWrap) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/grouptitleWrap is empty.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] We try to get a title wrap from TypoScript.', $this->pObj->extKey, 0);
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
    $str_limit_offset = $this->pObj->pi_getFFvalue($arr_piFlexform, 'limitOffset', $sheet, 'lDEF', 'vDEF');
      // downwards compatibility < 3.6.5:
      // offset is NULL if flexform was never saved with this field:
    $str_limit_offset = (int) $str_limit_offset;
      // #27354, uherrmann, 110611

    $str_limit = $this->pObj->pi_getFFvalue($arr_piFlexform, 'limit', $sheet, 'lDEF', 'vDEF');
    if ( $str_limit )
    {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/limit: \'' . htmlspecialchars($str_limit) . '\'!', $this->pObj->extKey, 0);
      }
    }
    if ( ! $str_limit )
    {
      $str_limit = 20;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/limit is empty.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/limit: We allocates it with 20.', $this->pObj->extKey, 0);
      }
    }
      // #27354, uherrmann, 110611
      ##$str_limit = '0,'.$str_limit;
    $str_limit = $str_limit_offset . ',' . $str_limit;
      // #27354, uherrmann, 110611
      // Get the limit for the list view

      // View has a local limit
      // #34212: 120223, dwildt+
    $conf_limit = $str_limit;
      // #34212: 120223, dwildt-
    //$conf_limit = false;
    $str_path   = false;
    if ( isset( $this->pObj->conf['views.']['list.'][$this->mode . '.']['limit'] ) )
    {
      $conf_limit = $this->pObj->conf['views.']['list.'][$this->mode . '.']['limit'];
      $str_path   = 'views.list.' . $this->mode . '.limit';
    }
    if ($str_path) {
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] ' . $str_path . ': \'' . htmlspecialchars($conf_limit) . '\'', $this->pObj->extKey, 0);
      }
      if ($conf_limit) {
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] The TypoScript value hasn\'t any effect: \'' . htmlspecialchars($conf_limit) . '\'!', $this->pObj->extKey, 0);
          ##t3lib_div::devlog('[HELP/FLEXFORM] Please remove \''.$str_path.'\'! ', $this->pObj->extKey, 0);
          // #27354, uherrmann, 110611
          t3lib_div :: devlog('[HELP/FLEXFORM] Please remove \'' . $str_path . '\'! Use fields \'Limit: start/offset\' and \'Limit: records per page\' (Backend/ Browser plugin) instead of!', $this->pObj->extKey, 0);
          // #27354, uherrmann, 110611
        }
      }
    }
      // #34212: 120223, dwildt-
    //$conf_limit = $str_limit;
    list( $start, $results_at_a_time ) = explode( ',', $conf_limit );
    if( $results_at_a_time == null )
    {
      if( $this->pObj->b_drs_warn ) 
      {
        $prompt = 'views.list.' . $this->mode . '.limit is ' . $conf_limit;
        t3lib_div :: devlog( '[WARN/DRS] ' . $prompt , $this->pObj->extKey, 2 );
      }
      $conf_limit = '0,' . $start; 
      if( $this->pObj->b_drs_warn ) 
      {
        $prompt = 'Please move it from ' . $start . ' to ' . $conf_limit;
        t3lib_div :: devlog( '[HELP/DRS] ' . $prompt , $this->pObj->extKey, 1 );
      }
    }
    
      // Set start
    $pageBrowserPointerLabel  = $this->pObj->conf['navigation.']['pageBrowser.']['pointer'];
    if( isset( $this->pObj->piVars[$pageBrowserPointerLabel] ) )
    {
      $multiplier = ( int ) $this->pObj->piVars[$pageBrowserPointerLabel];
      list( $start, $results_at_a_time ) = explode( ',', $conf_limit );
      $start      = $start + ( $multiplier * $results_at_a_time ) ;
      $conf_limit = $start . ',' . $results_at_a_time; 
    }
    $this->pObj->conf['views.']['list.'][$this->mode . '.']['limit'] = $conf_limit;
      // View has a local limit
      // Field limit



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the title in case of csv export
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] limit won\'t be handled. views.list.' . $this->mode . '.limit is removed.',  $this->pObj->extKey, 0);
        }
        unset( $this->pObj->conf['views.']['list.'][$this->mode . '.']['limit'] );
        break;
      default:
        // Do nothing;
    }
      // Remove the title in case of csv export
      // csv export



    //////////////////////////////////////////////////////////////////////
    //
    // Field navigation

    $int_navigation = $this->pObj->pi_getFFvalue($arr_piFlexform, 'navigation', $sheet, 'lDEF', 'vDEF');

    // Set default value
    // #27352, uherrmann, 110610
    ##if(empty($int_navigation))
    if (!isset ($int_navigation))
      // #27352, uherrmann, 110610
      {
      // default case
      $int_navigation = 3;
    }
    // Set default value

    switch ($int_navigation) {
      case (0) :
        $this->bool_indexBrowser = 0;
        $this->bool_pageBrowser = 0;
        break;
      case (1) :
        $this->bool_indexBrowser = 1;
        $this->bool_pageBrowser = 0;
        break;
      case (2) :
        $this->bool_indexBrowser = 0;
        $this->bool_pageBrowser = 1;
        break;
      case (3) :
        $this->bool_indexBrowser = 1;
        $this->bool_pageBrowser = 1;
        break;
      default :
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
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewList/navigation<br />
              indexBrowser: \'' . $this->bool_indexBrowser . '\'<br />
              pageBrowser: \'' . $this->bool_pageBrowser . '\'', $this->pObj->extKey, 0);
    }
    // Field navigation



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the index browser and the page browser in case of csv export
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] indexBrowser and pageBrowser won\'t be handled. Both are set to 0.',  $this->pObj->extKey, 0);
        }
        $this->bool_indexBrowser   = 0;
        $this->bool_pageBrowser = 0;
        break;
      default:
        // Do nothing;
    }
      // Remove the index browser and the page browser in case of csv export
      // csv export



    //////////////////////////////////////////////////////////////////////
    //
    // Field records

    $int_records = $this->pObj->pi_getFFvalue($arr_piFlexform, 'records', $sheet, 'lDEF', 'vDEF');

    $this->bool_emptyAtStart = false;
    switch ($int_records) {
      case (0) :
        $this->bool_emptyAtStart = false;
        break;
      case (1) :
        $this->bool_emptyAtStart = true;
        break;
      default :
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
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewList/records<br />
              emptyAtStart: \'' . $this->bool_emptyAtStart . '\'', $this->pObj->extKey, 0);
    }
    // Field records



    //////////////////////////////////////////////////////////////////////
    //
    // Field emptyValues

    // 110110, dwildt, 11603
    $int_emptyValues = $this->pObj->pi_getFFvalue($arr_piFlexform, 'emptyValues', $sheet, 'lDEF', 'vDEF');
    // Set default value
    if ($int_emptyValues == null) {
      $int_emptyValues = $this->bool_dontHandleEmptyValues = true;
    }
    // Set default value
    switch ($int_emptyValues) {
      case (0) :
        $this->bool_dontHandleEmptyValues = false;
        break;
      case (1) :
        $this->bool_dontHandleEmptyValues = true;
        break;
      default :
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
    if ($this->pObj->b_drs_flexform) {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewList/emptyValues<br />
              dontHandle: \'' . $this->bool_dontHandleEmptyValues . '\'', $this->pObj->extKey, 0);
    }
    //var_dump('config 2500', $this->bool_dontHandleEmptyValues);
    // Field emptyValues



    //////////////////////////////////////////////////////////////////////
    //
    // Field search

    $str_search = $this->pObj->pi_getFFvalue($arr_piFlexform, 'search', $sheet, 'lDEF', 'vDEF');
    $bool_handleSearch = true;

    // Don't handle search properties'
    if (!$str_search || $str_search == 'default') {
      $this->bool_searchForm                        = true;
      $this->bool_searchForm_wiPhrase               = true;
      $this->bool_searchForm_wiColoredSwords        = true;
      $this->bool_searchForm_wiColoredSwordsSingle  = false;
      $this->pObj->bool_searchWildcardsManual       = false;
      $this->pObj->str_searchWildcardCharManual     = '*';
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/search: \'default\'. Nothing to do.', $this->pObj->extKey, 0);
      }
      $bool_handleSearch = false;
    }
    // Don't handle search properties'

    // Handle search properties'
    if ($bool_handleSearch)
    {
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/search: \'' . $str_search . '\'.', $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
      // Field search

      //////////////////////////////////////////////////////////////////////
      //
      // Field searchForm

      $int_searchForm = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchForm', $sheet, 'lDEF', 'vDEF');

      $this->bool_searchForm = (($int_searchForm & 1) == 1);
      $this->bool_searchForm_wiPhrase = (($int_searchForm & 2) == 2);
      $this->bool_searchForm_wiColoredSwords = (($int_searchForm & 4) == 4);
      $this->bool_searchForm_wiColoredSwordsSingle = (($int_searchForm & 8) == 8);

      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/searchForm<br />
                  int_searchForm: \'' . $int_searchForm . '\'<br />
                  searchForm: \'' . $this->bool_searchForm . '\'<br />
                  searchForm_wiPhrase: \'' . $this->bool_searchForm_wiPhrase . '\'<br />
                  searchForm_wiColoredSwords: \'' . $this->bool_searchForm_wiColoredSwords . '\'<br />
                  searchForm_wiColoredSwordsSingle: \'' . $this->bool_searchForm_wiColoredSwordsSingle . '\'', $this->pObj->extKey, 0);
      }
      // Field searchForm



      //////////////////////////////////////////////////////////////////////
      //
      // Field searchWildcards

      $str_searchWildcards = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchWildcards', $sheet, 'lDEF', 'vDEF');

      if ($str_searchWildcards == 'default') {
        $this->pObj->bool_searchWildcardsManual = 0;
      }
      if ($str_searchWildcards == 'manual') {
        $this->pObj->bool_searchWildcardsManual = 1;
      }
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/searchWildcards: ' .
        $this->pObj->bool_searchWildcardsManual . '\'', $this->pObj->extKey, 0);
      }
      // Field searchWildcards

      //////////////////////////////////////////////////////////////////////
      //
      // Field searchWildcardChar

      $str_searchWildcardChar = $this->pObj->pi_getFFvalue($arr_piFlexform, 'searchWildcardChar', $sheet, 'lDEF', 'vDEF');

      $this->pObj->str_searchWildcardCharManual = $str_searchWildcardChar;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/searchWildcardChar: ' .
        $this->pObj->bool_searchWildcardCharManual . '\'', $this->pObj->extKey, 0);
      }
      // Field searchWildcardChar

    }
    // Handle search properties'



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the search form in case of csv export
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] searchform won\'t be handled. It is set to 0.',  $this->pObj->extKey, 0);
        }
        $this->bool_searchForm = false;
        break;
      default:
        // Do nothing;
    }
      // Remove the search form in case of csv export
      // csv export



      //////////////////////////////////////////////////////////////////////
      //
      // Field total_hits
      // #32654, dwildt, 120127

    $field      = 'total_hits';
    $total_hits = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_filter )
    {
      t3lib_div::devlog( '[INFO/FLEXFORM+FILTER] ' . 'total_hits: \'' . $total_hits . '\'', $this->pObj->extKey, 0 );
    }

    switch ( $total_hits )
    {
      case ( 'independent' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = 'independent';
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_filter )
        {
          t3lib_div::devlog('[INFO/FLEXFORM+FILTER] flexform.' . $sheet . '.' . $field . ' is set to independent.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'ts' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_filter )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+FILTER] flexform.' . $sheet . '.' . $field . ' isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'controlled' ) :
      default :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = 'controlled';
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_filter )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+FILTER] flexform.' . $sheet . '.' . $field . ' is set to controlled.', $this->pObj->extKey, 0);
        }
    }
    $this->sheet_viewList_total_hits = $this->pObj->conf['flexform.'][$sheet . '.'][$field];
    if ( $this->pObj->b_drs_filter )
    {
      t3lib_div :: devlog('[INFO/FILTER] global sheet_viewList_total_hits is set to ' . $this->sheet_viewList_total_hits, $this->pObj->extKey, 0);
    }
      // Field total_hits



      //////////////////////////////////////////////////////////////////////
      //
      // Field csvexport
      // #29370, dwildt, 110831

    $field      = 'csvexport';
    $csvexport  = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
    {
      t3lib_div::devlog( '[INFO/FLEXFORM+EXPORT] ' . 'csvexport: \'' . $csvexport . '\'', $this->pObj->extKey, 0 );
    }

    switch ( $csvexport )
    {
      case ( 'enabled' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = true;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/FLEXFORM+EXPORT] flexform.' . $sheet . '.' . $field . ' is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'ts' ) :
        // Do nothing;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+EXPORT] flexform.' . $sheet . '.' . $field . ' isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'disabled' ) :
      default :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = false;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+EXPORT] flexform.' . $sheet . '.' . $field . ' is set to false.', $this->pObj->extKey, 0);
        }
    }
    $this->sheet_viewList_csvexport = $this->pObj->conf['flexform.'][$sheet . '.'][$field];
    if ( $this->pObj->b_drs_export )
    {
      t3lib_div :: devlog('[INFO/EXPORT] global sheet_viewList_csvexport is set to ' . $this->sheet_viewList_csvexport, $this->pObj->extKey, 0);
    }
      // Field csvexport






      //////////////////////////////////////////////////////////////////////
      //
      // Field rotateviews
      // #29370, dwildt, 110831

    $field      = 'rotateviews';
    $rotateviews  = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
    {
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . 'rotateviews: \'' . $rotateviews . '\'', $this->pObj->extKey, 0 );
    }

    switch ( $rotateviews )
    {
      case ( 'enabled' ) :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = true;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
        {
          t3lib_div::devlog('[INFO/FLEXFORM+JSS] flexform.' . $sheet . '.' . $field . ' is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'ts' ) :
        // Do nothing;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+JSS] flexform.' . $sheet . '.' . $field . ' isn\'t changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
      case ( 'disabled' ) :
      default :
        $this->pObj->conf['flexform.'][$sheet . '.'][$field] = false;
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM+JSS] flexform.' . $sheet . '.' . $field . ' is set to false.', $this->pObj->extKey, 0);
        }
    }
    $this->sheet_viewList_rotateviews = $this->pObj->conf['flexform.'][$sheet . '.'][$field];
    if ( $this->pObj->b_drs_javascript )
    {
      t3lib_div :: devlog('[INFO/JSS] global sheet_viewList_rotateviews is set to ' . $this->sheet_viewList_rotateviews, $this->pObj->extKey, 0);
    }
      // Field rotateviews



    //////////////////////////////////////////////////////////////////////
    //
    // Field simulateSingleUid

    // Get the simulateSingleUid for the list view
    $int_simulateSingleUid = $this->pObj->pi_getFFvalue($arr_piFlexform, 'simulateSingleUid', $sheet, 'lDEF', 'vDEF');
    if (!empty ($int_simulateSingleUid)) {
      $this->int_singlePid = (int) $int_simulateSingleUid;
      if ($this->pObj->b_drs_flexform) {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewList/simulateSingleUid: \'' . $int_simulateSingleUid . '\'', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[HELP/FLEXFORM] This plugin will act like a plugin which is called with a single uid!', $this->pObj->extKey, 1);
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
 * @return    void
 * @version 4.1.25
 * @since 3.7.0
 */
  function sheet_viewSingle()
  {
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    


      //////////////////////////////////////////////////////////////////////
      //
      // Field display_listview
      // #31156, dwildt, 110806

    $sheet = 'viewSingle';
    $field = 'display_listview';
    $display_listview = $this->pObj->pi_getFFvalue( $arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF' );

    if ( $this->pObj->b_drs_flexform )
    {
      t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $field . ': \'' . $display_listview . '\'', $this->pObj->extKey, 0 );
    }

    switch ( $display_listview )
    {
      case ( null ) :
      case ( '' ) :
      case ( 'no' ) :
          // #29336, 111130, dwildt
        $this->pObj->conf['flexform.']['viewSingle.']['display_listview'] = 0;
        if ( $this->pObj->b_drs_flexform )
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewSingle.display_listview is set to ' . 0, $this->pObj->extKey, 0);
        }
        break;
      case ( 'yes' ) :
        $this->pObj->conf['flexform.']['viewSingle.']['display_listview'] = 1;
        if ($this->pObj->b_drs_flexform)
        {
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewSingle.display_listview is set to ' . 1, $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
          // Do nothing;
        if ($this->pObj->b_drs_flexform)
        {
          $value = $this->pObj->conf['flexform.']['viewSingle.']['display_listview'];
          t3lib_div :: devlog('[INFO/FLEXFORM] flexform.viewSingle.display_listview isn\'t changed: ' . $value, $this->pObj->extKey, 0);
        }
        break;
      default :
        $prompt = '
                  <div style="background:white; font-weight:bold;border:.4em solid orange;">
                    <h1>
                      WARNING
                    </h1>
                    <p>
                      Flexform field has an invalid value. The value isn\'t defined.<br />
                      sheet: ' . $sheet . '<br />
                      field: ' . $field . '<br />
                      value: ' . $display_listview . '<br />
                      ' . __METHOD__ . ' (' . __LINE__ . ')
                    </p>
                    <p>
                      Please save the plugin/flexform of the browser. The bug will be fixed probably.
                    </p>
                  </div>';
        echo $prompt;
    }
      // #31156, dwildt, 110806
      // Field display_listview



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser

    $record_browser = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser: \'' . $record_browser . '\'.', $this->pObj->extKey, 0);
    }

    switch ($record_browser)
    {
      case ('disabled') :
        $this->pObj->conf['navigation.']['record_browser'] = 0;
        break;
      case ('by_flexform') :
        $this->pObj->conf['navigation.']['record_browser'] = 1;
        break;
      case ('ts') :
        // #43530, 121202, dwildt, 1+
      default:
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser'];
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser is set to ' . $record_browser . '.', $this->pObj->extKey, 0);
    }
      // Field record_browser



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser == false

    if (!$this->pObj->conf['navigation.']['record_browser'])
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle RETURN', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN record_browser == false



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN record_browser == ts

    if ($record_browser == 'ts')
    {
      if ($this->pObj->b_drs_flexform)
      {
        t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser is set to ts. RETURN', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN record_browser == false



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.firstAndLastButton

    $firstAndLastButton = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.firstAndLastButton', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.firstAndLastButton: \'' . $firstAndLastButton . '\'.', $this->pObj->extKey, 0);
    }

    switch ($firstAndLastButton)
    {
      case ('no') :
        $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastButton'] = 0;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastButton is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case ('yes') :
        // enabled
        $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastButton'] = 1;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastButton is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['firstAndLastButton'];
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.firstAndLastButton is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.display.firstAndLastButton



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.buttonsWithoutLink

    $buttonsWithoutLink = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.buttonsWithoutLink', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.buttonsWithoutLink: \'' . $buttonsWithoutLink . '\'.', $this->pObj->extKey, 0);
    }

    switch ($buttonsWithoutLink)
    {
      case (('no')) :
        $this->pObj->conf['navigation.']['record_browser.']['display.']['buttonsWithoutLink'] = 0;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.buttonsWithoutLink is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case ('yes') :
        $this->pObj->conf['navigation.']['record_browser.']['display.']['buttonsWithoutLink'] = 1;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.buttonsWithoutLink is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['buttonsWithoutLink'];
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.buttonsWithoutLink is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.display.buttonsWithoutLink



      //////////////////////////////////////////////////////////////////////
      //
      // Field record_browser.display.withoutResult

    $withoutResult = $this->pObj->pi_getFFvalue($arr_piFlexform, 'record_browser.display.withoutResult', 'viewSingle', 'lDEF', 'vDEF');
    if ($this->pObj->b_drs_flexform)
    {
      t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser.display.withoutResult: \'' . $withoutResult . '\'.', $this->pObj->extKey, 0);
    }

    switch ($withoutResult)
    {
      case ('no') :
        $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'] = 0;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is set to false.', $this->pObj->extKey, 0);
        }
        break;
      case ('yes') :
        $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'] = 1;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is set to true.', $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing
        $value = $this->pObj->conf['navigation.']['record_browser.']['display.']['withoutResult'];
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.display.withoutResult is \'' . $value . '\' and will not changed by the flexform.', $this->pObj->extKey, 0);
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
      t3lib_div :: devlog('[INFO/FLEXFORM] viewSingle.record_browser.labeling: \'' . $withoutResult . '\'.', $this->pObj->extKey, 0);
    }

    switch ($labeling)
    {
      case ('chars') :
      case ('icons') :
      case ('position') :
      case ('text') :
          // Get configuration of the selected label
        $conf_labelling = $this->pObj->conf['navigation.']['record_browser.']['buttons.'][$labeling . '.'];
          // Set configuration of the selected label
        $this->pObj->conf['navigation.']['record_browser.']['buttons.']['current.'] = $conf_labelling;
          // Get configuration of the selected label
        $conf_labelling = $this->pObj->conf['navigation.']['record_browser.']['buttons_wo_link.'][$labeling . '.'];
          // Set configuration of the selected label
        $this->pObj->conf['navigation.']['record_browser.']['buttons_wo_link.']['current.'] = $conf_labelling;
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.buttons.current < .' . $labeling, $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.buttons_wo_link.current < .' . $labeling, $this->pObj->extKey, 0);
        }
        break;
      case ('ts') :
        // Do nothing
        if ($this->pObj->b_drs_flexform) {
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.buttons.current will not changed by the flexform.', $this->pObj->extKey, 0);
          t3lib_div :: devlog('[INFO/FLEXFORM] navigation.record_browser.buttons_wo_link.current will not changed by the flexform.', $this->pObj->extKey, 0);
        }
        break;
    }
      // Field record_browser.labeling

    return;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_flexform.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_flexform.php']);
}
?>

