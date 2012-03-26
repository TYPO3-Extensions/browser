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
 * The class tx_browser_pi1_navi_indexBrowser bundles methods for the index browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.12
 * @since       3.9.9
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  100: class tx_browser_pi1_navi_indexBrowser
 *  188:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  220:     public function get( $content )
 *
 *              SECTION: Requirements
 *  297:     private function localisation_init( )
 *  349:     private function requirements_check( )
 *  417:     private function tableField_check( )
 *  467:     private function tableField_init( )
 *
 *              SECTION: Subparts
 *  556:     private function subpart( )
 *  602:     private function subpart_setContainer( )
 *  623:     private function subpart_setTabs( )
 *
 *              SECTION: Tabs
 *  721:     private function tabs_init( )
 *  788:     private function tabs_initAttributes( $csvAttributes, $tabLabel, $tabId )
 *  840:     private function tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $displayWoItems )
 *  899:     private function tabs_initSpecialChars( $arrCsvAttributes )
 *
 *              SECTION: Count chars
 *  958:     private function count_chars( )
 *  998:     private function count_chars_addSumToTab( $res )
 * 1050:     private function count_chars_resSqlCount( $currSqlCharset )
 *
 *              SECTION: Count special chars
 * 1171:     private function count_specialChars( )
 * 1210:     private function count_specialChars_addSum( $row )
 * 1260:     private function count_specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset )
 * 1351:     private function count_specialChars_setSqlFindInSet( $row )
 * 1377:     private function count_specialChars_setSqlLength( )
 *
 *              SECTION: SQL charset
 * 1449:     private function sqlCharsetGet( )
 * 1482:     private function sqlCharsetSet( $sqlCharset )
 *
 *              SECTION: Downward compatibility
 * 1522:     private function getMarkerIndexbrowser( )
 * 1568:     private function getMarkerIndexbrowserTabs( )
 *
 *              SECTION: Helper
 * 1626:     private function zz_specCharsToASCII( $string )
 * 1646:     private function zz_tabClass( $lastTabId, $tab, $key )
 * 1679:     private function zz_tabDefaultLabel( )
 * 1697:     private function zz_tabDefaultLink( )
 * 1740:     private function zz_tabLinkLabel( $tab )
 * 1782:     private function zz_setTabPiVars( $labelAscii, $label )
 * 1812:     private function zz_setTabPiVarsDefaultTab( $label )
 * 1844:     private function zz_setTabSlected( $tabId )
 * 1894:     private function zz_tabLastId( )
 * 1926:     private function zz_tabTitle( $sum )
 *
 * TOTAL FUNCTIONS: 35
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_indexBrowser
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


    // [Array] piVars backup
  var $piVarsBak = null;
    // [Array] Array with tabIds and tabLabels
  var $indexBrowserTab = array( );
//  'tabSpecial' =>
//    'default' => '0',
//    'all' => 0,
//    'others' => 25,
//  'tabIds' =>
//    0 =>
//      'label' => 'Alle',
//      'displayWoItems' => '1',
//      'sum' => 0,
//      'special' => 'all',
//    1 =>
//    ...
//  'tabLabels' =>
//    'Alle' => 0,
//    '0-9' => 1,
//    'A' => 2,
//    ...
//  'attributes' =>
//    0 =>
//      'tabLabel' => '0-9',
//      'tabId' => 1,
//    ...
//    'Z' =>
//      'tabLabel' => 'XYZ',
//      'tabId' => 24,

    // [String] table.field of the index browser
  var $indexBrowserTableField = null;
    // [Array] Array with the find in set statements for special chars
  var $findInSet = array( );

    // [Boolean] true: don't localise the current SQL query, false: localise it
  var $bool_dontLocalise      = null;
    // [Integer] number of the localisation mode
  var $int_localisation_mode  = null;

    // [Boolean] If a filter is selected by the visitor, this boolean will be true.
  var $aFilterIsSelected = null;

    // [String] Subpart for the index browser
  var $subpart    = null;
    // [String] Subpart for a tab within the index browser
  var $subpartTab = null;
    // [Boolean] Should the deafult tab get a link?
  var $linkDefaultTab = null;
    // [String] Label of the default tab
  var $tabDefaultLabel = null;












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
    * Main
    *
    **********************************************/



/**
 * get( ): Get the index browser. It has to replace the subpart in the current content.
 *
 * @param	string		$content: current content
 * @return	array
 * @version 3.9.12
 * @since   3.9.9
 */
  public function get( $content )
  {
    $this->content                  = $content;
    $arr_return['data']['content']  = $content;

      // RETURN: requirements aren't met
    $arr_return = $this->requirements_check( );
    if( ! empty( $arr_return ) )
    {
      return $arr_return;
    }
      // RETURN: requirements aren't met

      // RETURN : table is not the local table
    $arr_return = $this->tableField_check( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : table is not the local table

      // Backup $GLOBALS['TSFE']->id
    $globalTsfeId = $GLOBALS['TSFE']->id;
      // Setup $GLOBALS['TSFE']->id temporarily
    if( ! empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Setup $GLOBALS['TSFE']->id temporarily
      
      // Init the tabs
    $arr_return = $this->tabs_init( );
    if( $arr_return['error']['status'] )
    {
        // Reset $GLOBALS['TSFE']->id
      $GLOBALS['TSFE']->id = $globalTsfeId;
      return $arr_return;
    }
      // Init the tabs

      // Render the tabs
    $arr_return = $this->subpart( );
    if( $arr_return['error']['status'] )
    {
        // Reset $GLOBALS['TSFE']->id
      $GLOBALS['TSFE']->id = $globalTsfeId;
      return $arr_return;
    }
    //$arr_result['data']['content']
      // Render the tabs

      // Reset $GLOBALS['TSFE']->id
    $GLOBALS['TSFE']->id = $globalTsfeId;
    return $arr_return;
  }









    /***********************************************
    *
    * Requirements
    *
    **********************************************/



  /**
 * localisation_init( ):  Inits the localisation mode and localisation TS
 *                            Sets the class vars
 *                            * $int_localisation_mode
 *                            * bool_dontLocalise
 *
 * @return	void
 * @version 3.9.11
 * @since   3.9.11
 */
  private function localisation_init( )
  {

      // Set class var $int_localisation_mode; init TS of pObj->objLocalise;
    if( ! isset( $this->int_localisation_mode ) )
    {
      $this->int_localisation_mode = $this->pObj->objLocalise->localisationConfig( );
      $this->pObj->objLocalise->init_typoscript( );
    }

      // Set class var $bool_dontLocalise
      // SWTCH $int_localisation_mode
    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE. There isn\' any need to localise!';
        break;
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE_ONLY. There isn\' any need to localise!';
        break;
      default:
        $this->bool_dontLocalise = false;
        $prompt = 'Localisation mode is enabled';
        break;
    }
      // SWTCH $int_localisation_mode
      // Set class var $bool_dontLocalise

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      t3lib_div::devlog( '[INFO/NAVI+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return;
  }



/**
 * requirements_check( ): Checks
 *                        * configuration of the flexform
 *                        * configuration of TS tabs
 *                        It returns true, if a requirement isn't met
 *
 * @return	mixed         true or array, if a requirement isn't met
 * @version 3.9.11
 * @since   3.9.9
 */
  private function requirements_check( )
  {
      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Index browser should not handled in a localised context!';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

      // RETURN true : index browser is disabled
    if( ! $this->pObj->objFlexform->bool_indexBrowser )
    {
      if( $this->pObj->b_drs_navi )
      {
        $prompt = 'display.indexBrowser is false.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN true : index browser is disabled

    $this->localisation_init( );
    if( ! $this->bool_dontLocalise )
    {
        // DRS
      if( $this->pObj->b_drs_navi || $this->pObj->b_drs_localisation )
      {
        $prompt = 'Sorry: index browser isn\'t localised in browser version 4.0.x';
        t3lib_div::devlog( '[ERROR/NAVI+LOCALISATION] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'Please disable the index browser in a localised context';
        t3lib_div::devlog( '[HELP/NAVI+LOCALISATION] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      $prompt   = $this->pObj->pi_getLL( 'warn_indexbrowser_ll' );
      $content  = '<div class="indexBrowser" style="font-size:.85em">' . $prompt . '</div>';
      $arr_return['data']['content'] = $content;
      return $arr_return;
    }

      // RETURN true : index browser hasn't any configured tab
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
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = '<h1 style="color:red">Error Index Browser</h1>';
      $prompt = 'Index browser is enabled by the flexform or by TypoScript. ' .
                'But the TypoScript navigation.indexBrowser.tabs hasn\'t any element. ' .
                'Please take care of a proper TypoScript or disable the index browser.';
      $arr_return['error']['prompt'] = '<p style="color:red">' . $prompt . '</p>';
      return $arr_return;
    }
      // RETURN true : index browser hasn't any configured tab

      // RETURN false : requirements are OK
    return false;
  }



/**
 * tableField_check( ): Checks, if the table.field of the index browser
 *                      correspondends with the local table.
 *                      Sets the class var $indexBrowserTableField.
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.9
 */
  private function tableField_check( )
  {
      // Init the table.field
    $this->tableField_init( );

    list( $table, $field ) = explode( '.', $this->indexBrowserTableField );

      // RETURN : table is the local table
    if( $table == $this->pObj->localTable )
    {
      return;
    }
      // RETURN : table is the local table

      // Error management
    $prompt_01  = 'Sorry, the index browser can\'t handle an index for foreign tables!';
    $prompt_02  = 'Current table.field is: ' . $this->indexBrowserTableField;
    $prompt_03  = 'Local table is: ' . $this->pObj->localTable;
    $prompt_04  = 'Please configure: ' . $this->conf_path . 'indexBrowser.field = ' . $this->pObj->localTable .'... ';
    if ($this->pObj->b_drs_navi)
    {
      t3lib_div::devlog('[ERROR/NAVIGATION] ' . $prompt_01, $this->pObj->extKey, 3);
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt_02, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt_03, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/NAVIGATION] ' . $prompt_04, $this->pObj->extKey, 1);
    }

    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = '<h1 style="color:red">Error Index-Browser</h1>';
    $prompt = $prompt_01 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_02 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_03 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_04 . '<br />' . PHP_EOL;
    $arr_return['error']['prompt'] = '<p style="color:red">' . $prompt . '</p>';
      // Error management

      // RETURN error message
    return $arr_return;
  }



/**
 * tableField_init( ):  Set the class var $this->indexBrowserTableField
 *                      Value is the table.field for SQL queries
 *
 * @return	void
 * @version 3.9.11
 * @since   3.9.9
 */
  private function tableField_init( )
  {

      // RETURN : table.field for the index browser form is set in the current view
    if( isset( $this->conf_view['navigation.']['indexBrowser.']['field'] ) )
    {
      $this->indexBrowserTableField = $this->conf_view['navigation.']['indexBrowser.']['field'];
      if( ! empty ( $this->indexBrowserTableField ) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = $this->conf_path . 'indexBrowser.field is ' . $field;
          t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return;
      }
    }
      // RETURN : table.field for the index browser form is set in the current view

      // RETURN : table.field for the index browser form is set in global configuration
    if( isset( $this->conf['navigation.']['indexBrowser.']['field'] ) )
    {
      $this->indexBrowserTableField = $this->conf['navigation.']['indexBrowser.']['field'];
      if( ! empty ( $this->indexBrowserTableField ) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = 'indexBrowser.field is ' . $field;
          t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return;
      }
    }
      // RETURN : table.field  for the index browser form is set in global configuration

      // The user hasn't defined a table.field element.
      // We take the first one of the field views.list.X.select

      // Get the first table of the global arr_realTables_arrFields
    reset( $this->pObj->arr_realTables_arrFields );
    $table = key( $this->pObj->arr_realTables_arrFields );
      // First field of the current table
    $field = $this->pObj->arr_realTables_arrFields[$table][0];
    $this->indexBrowserTableField = $table . '.' . $field;
      // Get the first table of the global arr_realTables_arrFields

      // DIE : undefined error
    if( empty ( $this->indexBrowserTableField ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): undefined error!');
    }
      // DIE : undefined error

      // DRS
    if( $this->pObj->b_drs_navi )
    {
      $prompt = 'indexBrowser.field is the first table.field from ' .
                $this->conf_path . 'select: ' . $this->indexBrowserTableField;
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
      $prompt = 'If you need another table.field use ' . $this->conf_path . 'indexBrowser.field';
      t3lib_div::devlog('[HELP/NAVIGATION] ' . $prompt, $this->pObj->extKey, 1);
    }
      // DRS

  }









    /***********************************************
    *
    * Subparts
    *
    **********************************************/



/**
 * subpart( ): Returns the content for the index browser subpart
 *
 * @return	array		$arr_return : Content or an erreor message in case of an error
 * @version 3.9.12
 * @since   3.9.9
 */
  private function subpart( )
  {
      // Set class vars subpart and $arr_return
    $marker           = $this->pObj->objNavi->getMarkerIndexBrowser( );
    $this->subpart    = $this->pObj->cObj->getSubpart( $this->content, $marker );
    $markerTabs       = $this->pObj->objNavi->getMarkerIndexbrowserTabs( );
    $this->subpartTab = $this->pObj->cObj->getSubpart( $this->subpart, $markerTabs );
      // Set class vars subpart and $arr_return

      // Set class var $tabDefaultLabel
    $this->zz_tabDefaultLabel( );

      // Set the subpart for the tabs
    $arr_return = $this->subpart_setTabs( );
    if( ! ( empty ( $arr_return ) ) )
    {
      return $arr_return;
    }
      // Set the subpart for the tabs

      // Set the whole subpart
    $arr_return = $this->subpart_setContainer( );
    if( ! ( empty ( $arr_return ) ) )
    {
      return $arr_return;
    }
      // Set the whole subpart

      // Replace the subpart tabs in the whole subpart
    $content = $this->pObj->cObj->substituteSubpart( $this->subpart, $markerTabs, $this->subpartTab, true);

      // Retirn the content
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }



/**
 * subpart_setContainer( ): Replace markers in the subpart for the index browser
 *                          but not the subpart ###TABS###
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.9
 */
  private function subpart_setContainer( )
  {
    $markerArray['###MODE###']    = $this->mode;
    $markerArray['###VIEW###']    = $this->view;
    $markerArray['###MODE###']    = $this->mode;
    $markerArray['###UL_MODE###'] = $this->mode;
    $markerArray['###VIEW###']    = $this->view;
    $markerArray['###UL_VIEW###'] = $this->view;

    $this->subpart = $this->pObj->cObj->substituteMarkerArray($this->subpart, $markerArray);
  }



/**
 * subpart_setTabs( ): Set the content in the subpart for the tabs
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function subpart_setTabs( )
  {
    $content = null;

    $this->linkDefaultTab = $this->zz_tabDefaultLink( );
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
    }
      // Get the tab array
    $arrTabs = $this->indexBrowserTab['tabIds'];
      // get id of the last visible tab
    $lastTabId = $this->zz_tabLastId( );

      // LOOP : tabs
    foreach( ( array ) $this->indexBrowserTab['tabIds'] as $key => $tab )
    {
      if( $tab['sum'] < 1 &&  ! $tab['displayWoItems'] )
      {
        continue;
      }

        // Wrap the label
      $label  = $tab['label'];
      if( isset ( $tab['wrap'] ) )
      {
        $label = str_replace('|', $label, $tab['wrap']);
      }

        // Init the marker array
      unset( $markerArray );

        // Get class
      $class                          = $this->zz_tabClass( $lastTabId, $tab, $key );
      $markerArray['###CLASS###']     = $class;
      $markerArray['###LI_CLASS###']  = $class;

        // SWITCH : sum of hits of tab, display without items
      switch( true )
      {
        case( ! empty( $tab['sum'] ) ):
            // Tab with hits
          $markerArray['###TAB###'] = $this->zz_tabLinkLabel( $tab );
          break;
        case( $tab['displayWoItems'] ):
            // Tab without hits
          $markerArray['###TAB###'] = $label;
          break;
        default:
          continue;
      }
        // SWITCH : sum of hits of tab, display without items

        // Set the content
      $content = $content . $this->pObj->cObj->substituteMarkerArray( $this->subpartTab, $markerArray );
    }
      // LOOP : tabs

      // Set the class var subpartTab
    $this->subpartTab = $content;
  }









    /***********************************************
    *
    * Tabs
    *
    **********************************************/



/**
 * tabs_init( ):    Sets the class var $indexBrowserTab
 *
 * @return	array		$arr_return: Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_init( )
  {
      // Get tabSpecial property default
    $this->indexBrowserTab['tabSpecial']['default'] = null;
    if( isset( $this->conf['navigation.']['indexBrowser.']['defaultTab'] ) )
    {
      $this->indexBrowserTab['tabSpecial']['default'] = $this->conf['navigation.']['indexBrowser.']['defaultTab'];
    }
      // Get tabSpecial property default

      // Get default property display tabs without any item
    $defaultDisplayWoItems  = $this->conf['navigation.']['indexBrowser.']['display.']['tabWithoutItems'];

      // LOOP tabs TS configuratione array
    $conf_tabs = $this->conf['navigation.']['indexBrowser.']['tabs.'];
    foreach( ( array ) $conf_tabs as $tabId => $tabLabel )
    {
        // CONTINUE : key is an array
      if( substr( $tabId, -1 ) == '.' )
      {
        continue;
      }
        // CONTINUE : key is an array

        // Get attributes
      $csvAttributes      = $conf_tabs[$tabId . '.']['valuesCSV'];
      $csvAttributes      = str_replace(' ', null, $csvAttributes);
      $arrCsvAttributes[] = $csvAttributes;

        // Init tab attributes
      $this->tabs_initAttributes( $csvAttributes, $tabLabel, $tabId );
        // Init tab properties
      $this->tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $defaultDisplayWoItems );
    }
      // LOOP tabs TS configuratione array

      // Init special chars
    $this->tabs_initSpecialChars( $arrCsvAttributes );

      // Count special chars
    $arr_return = $this->count_specialChars( );
    if( ! ( empty ( $arr_return ) ) )
    {
      return $arr_return;
    }

      // Count chars
    $arr_return = $this->count_chars( );
    if( ! ( empty ( $arr_return ) ) )
    {
      return $arr_return;
    }

  }



/**
 * tabs_initAttributes( ):  Sets the array attributes of the class var $indexBrowserTab
 *
 * @param	string		$csvAttributes  : attributes
 * @param	string		$tabLabel       : label of the current tab
 * @param	integer		$tabId          : Id of the current tab
 * @return	array		$arr_return     : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initAttributes( $csvAttributes, $tabLabel, $tabId )
  {
      // RETURN : no attributes
    if( empty ( $csvAttributes ) )
    {
      return;
    }
      // RETURN : no attributes

      // LOOP : attributes
    $attributes = explode( ',', $csvAttributes );
    foreach( $attributes as $attribute )
    {
        // DRS : ERROR : attribute is part of two tabs at least
      if( isset ( $this->indexBrowserTab['attributes'][ $attribute ]) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = 'The tab attribute ' . $attribute . ' is part of two tabs at least!';
          t3lib_div::devlog( '[ERROR/NAVI] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'You will get an unproper result for the index browser';
          t3lib_div::devlog( '[WARN/NAVI] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = $attribute . ' is part of tab[' . $this->indexBrowserTab['attributes'][ $attribute ][ 'tabLabel' ] . '] '.
                    ' and of tab[' . $tabLabel . '] at least!';
          t3lib_div::devlog( '[WARN/NAVI] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Please take care of a proper TypoScript configuration!';
          t3lib_div::devlog( '[HELP/NAVI] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }
        // DRS : ERROR : attribute is part of two tabs at least

        // Set class var
      $this->indexBrowserTab['attributes'][ $attribute ][ 'tabLabel' ] = $tabLabel;
      $this->indexBrowserTab['attributes'][ $attribute ][ 'tabId' ]    = $tabId;
    }
      // LOOP : attributes
  }



/**
 * tabs_initProperties( ):  Sets the elements tabIds and tabLabels of the class var $indexBrowserTab
 *                          Updates the element tabSpecial.
 *
 * @param	array		$conf_tabs      : TS configuration array
 * @param	integer		$tabId          : Current tab ID for TS configuration array
 * @param	string		$tabLabel       : Label of the current tab
 * @param	boolean		$displayWoItems : Default value for displaying tabs without any hit
 * @return	array		$arr_return     : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $displayWoItems )
  {
      // Overwrite tab label in case of stdWrap
    if( $conf_tabs[$tabId . '.']['stdWrap.'] )
    {
      $stdWrap  = $conf_tabs[$tabId . '.']['stdWrap.'];
      $tabLabel = $this->pObj->objWrapper->general_stdWrap( $tabLabel, $stdWrap );
    }
      // Overwrite tab label in case of stdWrap

      // Overwrite property display without items
    if( isset ( $conf_tabs[$tabId . '.']['displayWithoutItems'] ) )
    {
      $displayWoItems = $conf_tabs[$tabId . '.']['displayWithoutItems'];
    }
      // Overwrite property display without items

      // Set labelAscii. Label for using in the URL
    $labelAscii = $this->zz_specCharsToASCII( $tabLabel );

      // Set tab array
    $this->indexBrowserTab['tabIds'][$tabId]['label']           = $tabLabel;
    $this->indexBrowserTab['tabIds'][$tabId]['labelAscii']      = $labelAscii;
    $this->indexBrowserTab['tabIds'][$tabId]['displayWoItems']  = $displayWoItems;
    $this->indexBrowserTab['tabIds'][$tabId]['sum']             = 0;
    $this->indexBrowserTab['tabLabels'][$tabLabel]              = $tabId;
      // Set tab selected
    $this->zz_setTabSlected( $tabId );
      // Set tab array

      // RETURN : tab with special value 'all'
    if( $conf_tabs[$tabId . '.']['special'] == 'all' )
    {
      $this->indexBrowserTab['tabIds'][$tabId]['special'] = 'all';
      $this->indexBrowserTab['tabSpecial']['all']         = $tabId;
      return;
    }
      // RETURN : tab with special value 'all'

      // RETURN : tab with special value 'others'
    if( $conf_tabs[$tabId . '.']['special'] == 'others' )
    {
      $this->indexBrowserTab['tabIds'][$tabId]['special'] = 'others';
      $this->indexBrowserTab['tabSpecial']['others']      = $tabId;
      return;
    }
      // RETURN : tab with special value 'others'
  }



/**
 * tabs_initSpecialChars( ): Inits the array initials of the class var $indexBrowserTab
 *
 * @param	array		$arrCsvAttributes : initials from the tab TS configuration
 * @return	void
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initSpecialChars( $arrCsvAttributes )
  {
      // Get initials unique
    $arrCsvAttributes  = array_unique( $arrCsvAttributes );
    $csvInitials  = implode( ',', ( array ) $arrCsvAttributes );

      // Init vars with all initials
    $this->indexBrowserTab['initials']['all']           = $csvInitials;
    $this->indexBrowserTab['initials']['specialChars']  = null;
    $this->indexBrowserTab['initials']['alphaNum']      = null;

      // UTF-8 decode
    $subject = utf8_decode( $csvInitials  );

      // Init var with special chars
    $pattern = '/[^0-9a-zA-Z,]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexBrowserTab['initials']['specialChars'] = utf8_encode( $specialChars );
    }
      // Init var with special chars

      // Init var with alpha numeric chars
    $pattern = '/[0-9a-zA-Z]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexBrowserTab['initials']['alphaNum'] = utf8_encode( $specialChars );
    }
      // Init var with alpha numeric chars
  }









    /***********************************************
    *
    * Count chars
    *
    **********************************************/






/**
 * count_chars( ): Updates sum / number of hits of chars (one byte)
 *
 * @return	array		$arr_return : Contains an erreor message in case of an error
 * @version 3.9.12
 * @since   3.9.11
 */
  private function count_chars( )
  {
      // Get current table.field of the index browser
    list( $table, $field) = explode( '.', $this->indexBrowserTableField);

      // Get current SQL char set
    $currSqlCharset = $this->sqlCharsetGet( );
      // Set SQL char set to latin1
    $this->sqlCharsetSet( 'latin1' );

      // SQL result with sum for records with a sepecial char as first character
    $arr_return = $this->count_chars_resSqlCount( $currSqlCharset );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $res = $arr_return['data']['res'];
      // SQL result with sum for records with a sepecial char as first character

      // Add the sum to the tab with the special char attribute
    $this->count_chars_addSumToTab( $res );

      // Free SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

      // Reset SQL char set
    $this->sqlCharsetSet( $currSqlCharset );
  }



/**
 * count_chars_addSumToTab( ): Updates the sum in the arrays tabIds and attributes
 *                       of the class var $indexBrowserTab
 *
 * @param	array		$res  : SQL result
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.11
 */
  private function count_chars_addSumToTab( $res )
  {
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
        // Get values from the SQL row
      $attribute  = $row[ 'initial' ];
      $rowSum     = $row[ 'count' ];

        // Set attributes sum
      $this->indexBrowserTab['attributes'][ $attribute ][ 'sum' ] = $rowSum;

        // Get id of the tab for all attributes
      $tabId    = $this->indexBrowserTab[ 'tabSpecial' ][ 'all' ];
        // Get sum of the current tab
      $currSum  = $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ];
        // Add row sum to current sum
      $sum      = $currSum + $rowSum;
        // Allocates result to the current tab
      $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ] = $sum;

        // Get id of the tab others or of the tab with the current attribute
      if( isset ( $this->indexBrowserTab[ 'attributes' ][ $attribute ][ 'tabId' ] ) )
      {
        $tabId  = $this->indexBrowserTab[ 'attributes' ][ $attribute ][ 'tabId' ];
      }
      else
      {
        $tabId  = $this->indexBrowserTab[ 'tabSpecial' ][ 'others' ];
      }
        // Get id of the tab others or of the tab with the current attribute

        // Get sum of the current tab
      $currSum  = $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ];
        // Add row sum to current sum
      $sum      = $currSum + $rowSum;
        // Allocates result to the current tab
      $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ] = $sum;
    }
  }



/**
 * count_chars_resSqlCount( ): SQL query and execution for counting initials
 *
 * @param	integer		$length         : SQL length of special chars group
 * @param	array		$arrfindInSet   : FIND IN SET statement with proper length
 * @param	string		$currSqlCharset : Current SQL charset for reset in error case
 * @return	array		$arr_return     : SQL ressource or an error message in case of an error
 * @version 3.9.12
 * @since   3.9.11
 */
  private function count_chars_resSqlCount( $currSqlCharset )
  {
    static $drsPrompt = true;

      // DRS
    if( $drsPrompt && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Query needs an and where in case of filter';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
      $drsPrompt = false;
    }
      // DRS

      // Get current table.field of the index browser
    $tableField           = $this->indexBrowserTableField;
    list( $table, $field) = explode( '.', $tableField );

    $strFindInSet = null;
    foreach( $this->findInSet as $length => $arrfindInSet )
    {
      $strFindInSet = $strFindInSet . implode ( " OR ", $arrfindInSet );
    }
    if( ! empty ( $strFindInSet ) )
    {
      $strFindInSet = "NOT (" . $strFindInSet . ")";
    }
      // Query for all filter items
    $select   = "COUNT( * ) AS 'count', LEFT ( " . $tableField . ", 1 ) AS 'initial'";
    $from   = $this->sqlStatement_from( $table );
    $where  = $this->sqlStatement_where( $table, $strFindInSet );

    $groupBy  = "LEFT ( " . $tableField . ", 1 )";
    $orderBy  = "LEFT ( " . $tableField . ", 1 )";
    $limit    = null;

      // Get query
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );
      // Execute query
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
        // Reset SQL charset
      $this->sqlCharsetSet( $currSqlCharset );
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Return SQL result
    $arr_return['data']['res'] = $res;
    return $arr_return;
  }












    /***********************************************
    *
    * Count special chars
    *
    **********************************************/






/**
 * count_specialChars( ): Updates sum / number of hits of sepcial chars (multy byte)
 *
 * @return	array		$arr_return : Contains an erreor message in case of an error
 * @version 3.9.12
 * @since   3.9.10
 */
  private function count_specialChars( )
  {
      // RETURN : no special chars
    if( empty ( $this->indexBrowserTab['initials']['specialChars'] ) )
    {
      return;
    }
      // RETURN : no special chars

      // Get a row with the SQL length for each special char
    $arr_return = $this->count_specialChars_setSqlLength( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $row = $arr_return['data']['row'];
    unset( $arr_return );
      // Get a row with the SQL length for each special char

      // Get the sum for each special char initial
    $arr_return = $this->count_specialChars_addSum( $row );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the sum for each special char initial

  }



/**
 * count_specialChars_addSum( ): Updates sum / number of hits of sepcial chars
 *
 * @param	array		$row        : Row with special chars and their SQL length
 * @return	array		$arr_return : Contains an erreor message in case of an error
 * @version 3.9.12
 * @since   3.9.10
 */
  private function count_specialChars_addSum( $row )
  {
      // Get current table.field of the index browser
    list( $table, $field) = explode( '.', $this->indexBrowserTableField);

      // Get current SQL char set
    $currSqlCharset = $this->sqlCharsetGet( );
      // Set SQL char set to latin1
    $this->sqlCharsetSet( 'latin1' );

      // Set class var findInSet
    $this->count_specialChars_setSqlFindInSet( $row );

      // LOOP : find in set for each special char length group
    foreach( $this->findInSet as $length => $arrfindInSet )
    {
        // SQL result with sum for records with a sepecial char as first character
      $arr_return = $this->count_specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset );
      if( $arr_return['error']['status'] )
      {
        return $arr_return;
      }
      $res = $arr_return['data']['res'];
        // SQL result with sum for records with a sepecial char as first character

        // Add the sum to the tab with the special char attribute
      $this->count_chars_addSumToTab( $res );

        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
    }
      // LOOP : find in set for each special char length group

      // Reset SQL char set
    $this->sqlCharsetSet( $currSqlCharset );
  }



/**
 * count_specialChars_resSqlCount( ): SQL query and execution for counting
 *                                    special char initials
 *
 * @param	integer		$length         : SQL length of special chars group
 * @param	array		$arrfindInSet   : FIND IN SET statement with proper length
 * @param	string		$currSqlCharset : Current SQL charset for reset in error case
 * @return	array		$arr_return     : SQL ressource or an error message in case of an error
 * @version 3.9.12
 * @since   3.9.10
 */
  private function count_specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset )
  {
    static $drsPrompt = true;

      // DRS
    if( $drsPrompt && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Query needs an and where in case of filter';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
      $drsPrompt = false;
    }
      // DRS

      // Get current table.field of the index browser
    $tableField           = $this->indexBrowserTableField;
    list( $table, $field) = explode( '.', $tableField );

      // Query for all filter items
    $select   = "COUNT( * ) AS 'count', LEFT ( " . $tableField . ", " . $length . " ) AS 'initial'";
    $from   = $this->sqlStatement_from( $table );
    $strFindInSet = "(" . implode ( " OR ", $arrfindInSet ) . ")";
    $where        = $this->sqlStatement_where( $table, $strFindInSet );
    $groupBy  = "LEFT ( " . $tableField . ", " . $length . " )";
    $orderBy  = "LEFT ( " . $tableField . ", " . $length . " )";
    $limit    = null;

      // Get query
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );
      // Execute query
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
        // Reset SQL charset
      $this->sqlCharsetSet( $currSqlCharset );
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Return SQL result
    $arr_return['data']['res'] = $res;
    return $arr_return;
  }



/**
 * count_specialChars_setSqlFindInSet( ): Set the FIND IN SET statement for each special char group.
 *                                        A special char group is grouped by the length of a special
 *                                        char.
 *
 * @param	array		$row  : Row with special chars and their SQL length
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.10
 */
  private function count_specialChars_setSqlFindInSet( $row )
  {
      // Get current table.field of the index browser
    $tableField           = $this->indexBrowserTableField;
    list( $table, $field) = explode( '.', $tableField );

      // LOOP : generate a find in set statement for each special char
    foreach( $row as $char => $length )
    {
      if( $length < 2 )
      {
        continue;
      }
      $this->findInSet[$length][] = "FIND_IN_SET( LEFT ( " . $tableField . ", " . $length . " ), '" . $char . "' )";
    }
      // LOOP : generate a find in set statement for each special char
  }



/**
 * count_specialChars_setSqlLength( ): Return a row with all special chars and their SQL length
 *
 * @return	array		$arr_return : row with all special chars and their SQL length
 * @version 3.9.12
 * @since   3.9.10
 */
  private function count_specialChars_setSqlLength( )
  {
      // Build the select statement parts for the length of each special char
    $arrStatement     = array( );
    $arrSpecialChars  = explode( ',', $this->indexBrowserTab['initials']['specialChars'] );
    foreach( ( array ) $arrSpecialChars as $specialChar )
    {
      $arrStatement[] = "LENGTH ( '" . $specialChar . "' ) AS '" . $specialChar . "'";
    }
      // Build the select statement parts for the length of each special char

      // DIE : undefined error
    if( empty ( $arrStatement ) )
    {
      die ( __METHOD__ . '(' . __LINE__ . '): undefined error.');
    }
      // DIE : undefined error

      // Execute query for the length of each special char
    $query  = "SELECT " . implode( ', ', $arrStatement );
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/NAVI+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Get the row
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // SQL free result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    $arr_return['data']['row'] = $row;
    return $arr_return;
  }








    /***********************************************
    *
    * SQL charset
    *
    **********************************************/



/**
 * sqlCharsetGet( ):  Get the current SQL charset like latin1 or utf8.
 *
 * @return	string		$charset  : current charset
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sqlCharsetGet( )
  {
      // Query
    $query  = "SHOW VARIABLES LIKE 'character_set_client';";
      // Execute
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // RETURN
    $row    = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

    if( empty ( $row ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): row is empty. Query: ' . $query );
    }

    $charset = $row['Value'];
    if( empty ( $charset ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): row[Value] is empty. Query: ' . $query );
    }
    return $charset;
  }



/**
 * sqlCharsetSet( ):  Execute SET NAMES with given charset
 *
 * @param	string		$sqlCharset : SQL charset like latin1 or utf8
 * @return	[type]		...
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sqlCharsetSet( $sqlCharset )
  {
    $query  = "SET NAMES " . $sqlCharset . ";";
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS
  }








    /***********************************************
    *
    * SQL statements
    *
    **********************************************/



/**
 * sqlStatement_from( ): SQL statement FROM without a FROM
 *
 * @param   string	$table  : The current from table
 * @return	string  $from   : FROM statement without a from
 * @version 3.9.12
 * @since   3.9.12
 */
  private function sqlStatement_from( $table )
  {
    switch( true )
    {
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->var_aFilterIsSelected( ) ):
        $from = $this->pObj->objSql->sql_query_statements['rows']['from'];
        break;
      default:
        $from = $table;
        break;
    }
    
    return $from;
  }



/**
 * sqlStatement_where( ): SQL statement WHERE without a WHERE
 *
 * @param   string	$table  : The current from table
 * @param   string	$andWhereFindInSet  : FIND IN SET
 * @return	string  $where : WHERE statement without a WHERE
 * @version 3.9.12
 * @since   3.9.12
 */

  private function sqlStatement_where( $table, $andWhereFindInSet )
  {
    switch( true )
    {
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->var_aFilterIsSelected( ) ):
        $where  = $this->pObj->objSql->sql_query_statements['rows']['where'];
        $where  = $this->sqlStatement_whereAndFindInSet( $where, $andWhereFindInSet );
        $where  = $where . $this->pObj->objFltr4x->andWhereFilter;
        break;
      default:
        $andEnableFields = $this->pObj->cObj->enableFields( $table );
        if( $andEnableFields )
        {
          $where = "1";
        }
        $where  = $where . $andEnableFields;
        $where  = $this->sqlStatement_whereAndFindInSet( $where, $andWhereFindInSet );
        if( empty ( $where ) )
        {
          $where = "1";
        }
        $llWhere  = $this->pObj->objLocalise->localisationFields_where( $table );
        if( $llWhere )
        {
          $where  = $where . " AND " . $llWhere;
        }
        break;
    }

    return $where;
  }



/**
 * sqlStatement_whereAndFindInSet( ): SQL statement WHERE without a WHERE
 *
 * @param   string	$where              : The current WHERE statement
 * @param   string	$andWhereFindInSet  : FIND IN SET
 * @return	string  $where              : WHERE statement without a WHERE
 * @version 3.9.12
 * @since   3.9.12
 */

  private function sqlStatement_whereAndFindInSet( $where, $andWhereFindInSet )
  {
      // RETURN : there isn't any FIND IN SET
    if( ! $andWhereFindInSet )
    {
      return $where;
    }
      // RETURN : there isn't any FIND IN SET

    switch( true )
    {
      case( $where ):
        $where = $where . " AND " . $andWhereFindInSet;
        break;
      case( empty( $where ) ):
      default:
        $where = $andWhereFindInSet;
        break;
    }

    return $where;
  }








    /***********************************************
    *
    * Downward compatibility
    *
    **********************************************/



 /**
  * getMarkerIndexbrowser( ): Downward compatibility for ###INDEXBROWSER###
  *                           If ###AZSELECTOR### is used in an HTML template
  *                           ###AZSELECTOR### will return
  *                           * Feature: #35032
  *
  * @return	string		###INDEXBROWSER### || ###AZSELECTOR###
  * @version  3.9.10
  * @since    3.9.10
  */
  public function getMarkerIndexbrowser( )
  {
      // DRS
    if ($this->pObj->b_drs_devTodo)
    {
      $prompt = 'Task #35037: Don\'t support the marker ###AZSELECTOR### from version 5.x';
      t3lib_div::devlog('[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS

      // get th current content
    $template = $this->currContent;

      // RETURN ###AZSELECTOR###, if ###AZSELECTOR### is part of the current content
    $pos = strpos( $template, '###AZSELECTOR###');
    if ( ! ( $pos === false ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = 'The current template contains the marker ###AZSELECTOR###';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = '###AZSELECTOR### won\'t supported from version 5.x';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'Please move it from ###AZSELECTOR### to ###INDEXBROWSER###';
        t3lib_div::devlog('[TODO/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
      }
      return '###AZSELECTOR###';
    }
      // RETURN ###AZSELECTOR###, if ###AZSELECTOR### is part of the current content

      // RETURN ###INDEXBROWSER###
    return '###INDEXBROWSER###';
  }



 /**
  * getMarkerIndexbrowserTabs( ): Downward compatibility for ###INDEXBROWSERTABS###
  *                               If ###AZSELECTORTABS### is used in an HTML template
  *                               ###AZSELECTORTABS### will return
  *                               * Feature: #35032
  *
  * @return	string		###INDEXBROWSERTABS### || ###AZSELECTORTABS###
  * @version  3.9.10
  * @since    3.9.10
  */
  private function getMarkerIndexbrowserTabs( )
  {
      // DRS
    if ($this->pObj->b_drs_devTodo)
    {
      $prompt = 'Task #35037: Don\'t support the marker ###AZSELECTORTABS### from version 5.x';
      t3lib_div::devlog('[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS

      // get th current content
    $template = $this->currContent;

      // RETURN ###AZSELECTORTABS###, if ###AZSELECTORTABS### is part of the current content
    $pos = strpos( $template, '###AZSELECTORTABS###');
    if ( ! ( $pos === false ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = 'The current template contains the marker ###AZSELECTORTABS###';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = '###AZSELECTORTABS### won\'t supported from version 5.x';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'Please move it from ###AZSELECTORTABS### to ###INDEXBROWSERTABS###';
        t3lib_div::devlog('[TODO/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
      }
      return '###AZSELECTORTABS###';
    }
      // RETURN ###AZSELECTORTABS###, if ###AZSELECTORTABS### is part of the current content

      // RETURN ###INDEXBROWSERTABS###
    return '###INDEXBROWSERTABS###';
  }









    /***********************************************
    *
    * Variables: init and get
    *
    **********************************************/



/**
 * var_aFilterIsSelected( ):
 *
 * @return	string  $from   : FROM statement without a from
 * @version 3.9.12
 * @since   3.9.12
 */
  private function var_aFilterIsSelected( )
  {
      // RETURN : var is initialised
    if( ! $this->aFilterIsSelected === null )
    {
      return $this->aFilterIsSelected;
    }
      // RETURN : var is initialised

      // RETURN : no piVars, set var to false
    if( empty( $this->pObj->piVars ) )
    {
      $this->aFilterIsSelected = false;
      return $this->aFilterIsSelected;
    }
      // RETURN : no piVars, set var to false

    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( ( array ) $fields as $fieldWiDot => $elements )
      {
        if( substr( $fieldWiDot, -1 ) != '.' )
        {
          continue;
        }
        $field      = substr($fieldWiDot, 0, -1);
        $tableField = $tableWiDot . $field;
        if( isset( $this->pObj->piVars[$tableField] ) )
        {
          $this->aFilterIsSelected = true;
          return $this->aFilterIsSelected;
        }
      }
    }

    $this->aFilterIsSelected = false;
    return $this->aFilterIsSelected;
  }









    /***********************************************
    *
    * Helper
    *
    **********************************************/



/**
 * zz_specCharsToASCII( ): Convert labels to ascii labels
 *
 * @param	string		$string:  the string for conversion
 * @return	string		$ascii:   the converted string
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_specCharsToASCII( $string )
  {
    $ascii = strip_tags( html_entity_decode( $string ) );
    $ascii = $this->t3lib_cs_obj->specCharsToASCII( $this->bool_utf8, $ascii );
    $ascii = strtolower( preg_replace( '/[^a-zA-Z0-9-_]*/', null, $ascii ) );

    return $ascii;
  }


/**
 * zz_tabClass( ): Returns the tab class like ' class="tab-u tab-29 selected last"'
 *
 * @param	integer		$lastTabId  : id of the last visible tab
 * @param	array		$tab        : array with elements of the current tab
 * @param	integer		$key        : id of the tab from the TS configuration
 * @return	string		$class      : complete class tag
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabClass( $lastTabId, $tab, $key )
  {
      // Default class
    $class = 'tab-' . $tab['labelAscii'] . ' tab-' . $key;

      // Selected tab
    if( ! empty ( $tab['selected'] ) )
    {
      $class = $class . ' selected';
    }
      // Selected tab

      // Last visible tab
    if( $key == $lastTabId )
    {
      $class = $class . ' last';
    }
      // Last visible tab

    $class = ' class="' . $class . '"';

    return $class;
  }



/**
 * zz_tabDefaultLabel( ): Set class var $tabDefaultLabel
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabDefaultLabel( )
  {
    $defaultTab_key     = $this->pObj->conf['navigation.']['indexBrowser.']['defaultTab'];
    $defaultTab_label   = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$defaultTab_key];
    $defaultTab_stdWrap = $this->pObj->conf['navigation.']['indexBrowser.']['tabs.'][$defaultTab_key . '.']['stdWrap.'];
    $this->tabDefaultLabel = $this->pObj->objWrapper->general_stdWrap( $defaultTab_label, $defaultTab_stdWrap );
  }



/**
 * zz_tabDefaultLink( ):  Set the boolean class var linkDefaultTab: Should
 *                        the default tab get a link?
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabDefaultLink( )
  {
    $boolLink = true;

      // IF don't display in URL
    if( empty ( $this->pObj->conf['navigation.']['indexBrowser.']['defaultTab.']['display_in_url'] ) )
    {
      $boolLink = false;

        // IF empty list at start
        // #7582, Bugfix, 100501
      if( $this->pObj->objFlexform->bool_emptyAtStart )
      {
        $boolLink = true;
          // DRS - Development Reporting System
        if( $this->pObj->b_drs_templating || $this->pObj->b_drs_navi )
        {
          $prompt = 'Empty list by start is true and the default tab of the index browser ' .
                    'shouldn\'t linked with a piVar. This is not proper.';
          t3lib_div::devlog('[WARN/TEMPLATING+NAVI] ' . $prompt, $this->pObj->extKey, 2);
          $prompt = 'The default tab of the index browser will be linked with a piVar by the system!';
          t3lib_div::devlog('[INFO/TEMPLATING+NAVI] ' . $prompt,  $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
      }
        // IF empty list at start
    }
      // IF don't display in URL

      // Set the class var
    $this->linkDefaultTab = $boolLink;
  }



/**
 * zz_tabLinkLabel( ): Links the label of the current tab
 *
 * @param	array		$tab            : array with elements of the current tab
 * @return	string		$tabLinkedLabel : the linked label
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabLinkLabel( $tab )
  {
      // Init typolink
    $typolink['parameter'] = $GLOBALS['TSFE']->id;

      // Get the property title
    $title  = $this->zz_tabTitle( $tab['sum'] );
    if( $title )
    {
      $typolink['parameter'] = $typolink['parameter'] . ' - - "' . $title . '"';
    }
      // Get the property title

      // Set piVars
    $this->zz_setTabPiVars( $tab['labelAscii'], $tab['label'] );
      // Init array with piVars
    $tabLinkedLabel = $this->pObj->objZz->linkTP_keepPIvars
                      (
                        $tab['label'],
                        $typolink,
                        $this->pObj->piVars,
                        $this->pObj->boolCache
                      );

      // RESET piVars
    $this->pObj->piVars = $this->piVarsBak;

    return $tabLinkedLabel;
  }



/**
 * zz_setTabPiVars( ):  Makes a backup of the current piVars. Than it removes some
 *                      elements, which shouldn't be part of a index browser link.
 *
 * @param	string		$labelAscii : label of the current tab in ascii format
 * @param	string		$label      : label of the current tab
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_setTabPiVars( $labelAscii, $label )
  {
      // Backup piVars
    $this->piVarsBak = $this->pObj->piVars;

      // Unset the pointer
    if( isset ( $this->pObj->piVars['pointer'] ) )
    {
      unset( $this->pObj->piVars['pointer'] );
    }

      // Set indexBrowserTab
    $this->pObj->piVars['indexBrowserTab'] = $labelAscii;

      // Handle default tab
    $this->zz_setTabPiVarsDefaultTab( $label );
  }



/**
 * zz_setTabPiVarsDefaultTab( ):  Removes the piVar indexBrowserTab in case of
 *                                the default tab, if default tab should get a
 *                                link
 *
 * @param	string		$label: label of the current tab
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_setTabPiVarsDefaultTab( $label )
  {
      // RETURN : default tab should get a link
    if( $this->linkDefaultTab )
    {
      return;
    }

      // RETURN : current tab isn't the default tab
    if( $label != $this->tabDefaultLabel )
    {
      return;
    }

      // Unset piVars['indexBrowserTab']
    if( isset( $this->pObj->piVars['indexBrowserTab'] ) )
    {
      unset( $this->pObj->piVars['indexBrowserTab'] );
    }
  }



/**
 * zz_setTabSlected( ): Sets the class property selected, if the current tab
 *                      is selected. Sets the class var indexBrowserTab.
 *
 * @param	integer		$tabId    : Current tab ID for TS configuration array
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_setTabSlected( $tabId )
  {
//$this->pObj->dev_var_dump( $tabLabel, $this->pObj->piVar_indexBrowserTab );
//$this->pObj->dev_var_dump( $tabId, $this->indexBrowserTab['tabSpecial']['default'] );
      // IF : piVar
    if( $this->pObj->piVar_indexBrowserTab )
    {
      $label      = $this->indexBrowserTab['tabIds'][$tabId]['label'];
      $labelAscii = $this->indexBrowserTab['tabIds'][$tabId]['labelAscii'];

        // IF : current tab is selected
      if($label == $this->pObj->piVar_indexBrowserTab)
      {
        $this->indexBrowserTab['tabIds'][$tabId]['selected'] = true;
        $this->indexBrowserTab['tabSpecial']['selected']     = $tabId;
        return;
      }
        // IF : current tab is selected
        // IF : current tab is selected
      if($labelAscii == $this->pObj->piVar_indexBrowserTab)
      {
        $this->indexBrowserTab['tabIds'][$tabId]['selected'] = true;
        $this->indexBrowserTab['tabSpecial']['selected']     = $tabId;
        return;
      }
        // IF : current tab is selected

        // Don't follow workflow
      return;
    }
      // IF : piVar

      // no piVar
    if( $tabId == $this->indexBrowserTab['tabSpecial']['default'] )
    {
      $this->indexBrowserTab['tabIds'][$tabId]['selected'] = true;
      $this->indexBrowserTab['tabSpecial']['selected']     = $tabId;
    }
  }


/**
 * zz_tabLastId( ): Returns the id of the last visible tab. A tab is visible,
 *                  if the property displayWoItems is true or of the tab has
 *                  one hit at least
 *
 * @return	integer		$id: Id of the last visible tab
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabLastId( )
  {
      // Get tab array
    $arrTabs = $this->indexBrowserTab['tabIds'];

      // Get last tab
    end( $arrTabs );

      // DO WHILE : a tab should displayed items or a tab has a hit at least
    do
    {
      $id = key( $arrTabs );
      prev( $arrTabs );
    }
    while( $arrTabs[$id]['displayWoItems'] + $arrTabs[$id]['count'] < 1 );
      // DO WHILE : a tab should displayed items or a tab has a hit at least

      // RETURN : id of last visible tab
    return $id;
  }



/**
 * zz_tabTitle( ): Returns the value for the title property. Something like
 *                 "1 item" or "12 items"
 *
 * @param	integer		$sum    : Amount of hits of the current tab
 * @return	string		$title  :	The title value
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_tabTitle( $sum )
  {
      // DRS
    static $drsPrompt_01 = true;
    static $drsPrompt_02 = true;

      // RETURN : title shouldn't displayed
    $displayTitle = $this->conf['navigation.']['indexBrowser.']['display.']['tabHrefTitle'];
    if( ! $displayTitle )
    {
      return;
    }
      // RETURN : title shouldn't displayed

      // SWITCH : sum of hits
    switch( true )
    {
      case( $sum > 1 ):
          // Get localised title for more than one item
        $title = htmlspecialchars($this->pObj->pi_getLL( 'browserItems', 'Items', true ) );
          // DRS
        if( $drsPrompt_02 )
        {
          if( $this->pObj->b_drs_localisation || $this->pObj->b_drs_navi )
          {
              // Get the current language key
            $langKey = $GLOBALS['TSFE']->lang;
            $prompt = 'Label for a tab with items is: ' . $title;
            t3lib_div::devlog('[INFO/LOCALLANG+NAVI] ' . $prompt, $this->pObj->extKey, 0);
            $prompt = 'If you want another label, please configure ' .
                      '_LOCAL_LANG.' . $langKey . '.browserItems';
            t3lib_div::devlog('[HELP/LOCALLANG+NAVI] ' . $prompt, $this->pObj->extKey, 1);
            $drsPrompt_02 = false;
          }
        }
          // DRS
        break;
      default:
          // Get localised title for one item exactly
        $title = htmlspecialchars($this->pObj->pi_getLL( 'browserItem', 'Item', true ) );
          // DRS
        if( $drsPrompt_01 )
        {
          if( $this->pObj->b_drs_localisation || $this->pObj->b_drs_navi )
          {
              // Get the current language key
            $langKey = $GLOBALS['TSFE']->lang;
            $prompt = 'Label for a tab with one item is: ' . $title;
            t3lib_div::devlog('[INFO/LOCALLANG+NAVI] ' . $prompt, $this->pObj->extKey, 0);
            $prompt = 'If you want another label, please configure ' .
                      '_LOCAL_LANG.' . $langKey . '.browserItem';
            t3lib_div::devlog('[HELP/LOCALLANG+NAVI] ' . $prompt, $this->pObj->extKey, 1);
            $drsPrompt_01 = false;
          }
        }
          // DRS
        break;
    }
      // SWITCH : sum of hits

      // RETURN the title value
    $title = $sum . ' ' . $title;
    return $title;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_indexBrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_indexBrowser.php']);
}

?>
