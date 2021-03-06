<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2016 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

/**
 * The class tx_browser_pi1_viewlist bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     7.3.0
 * @since       1.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   88: class tx_browser_pi1_viewlist
 *  131:     function __construct( $parentObj )
 *
 *              SECTION: Building the views
 *  159:     function main( )
 *  370:     private function init( )
 *  416:     private function check_view( )
 *
 *              SECTION: Content / Template
 *  478:     private function content_setCSV( )
 *  511:     private function content_setDefault( )
 *  569:     private function content_dieIfEmpty( $marker, $method, $line )
 *
 *              SECTION: SQL
 *  623:     private function rows_consolidateLL( $rows )
 *  653:     private function rows_consolidateChildren( $rows )
 *  687:     private function rows_fromSqlRes( $res )
 *  733:     private function rows_getCaseAliases( $res )
 *  771:     private function rows_getDefault( $res )
 *  792:     private function rows_sql( )
 *  821:     private function rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation( )
 *  866:     private function rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation( )
 *  911:     private function rows_sqlIdsOfRowsWiTranslation( $withIds )
 * 1061:     private function rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds )
 * 1212:     private function rows_sqlLanguageDefault( )
 * 1237:     private function rows_sqlLanguageFirstDefaultOrFirstTranslated( )
 * 1283:     private function rows_sqlRowsbyIds( $withIds )
 * 1370:     private function sql_selectLocalised( $select )
 *
 *              SECTION: Subparts
 * 1460:     private function subpart_setSearchbox( )
 * 1477:     private function subpart_setSearchboxFilter( )
 * 1521:     private function subpart_setIndexBrowser( )
 * 1551:     private function subpart_setModeSelector( )
 * 1610:     private function subpart_setPageBrowser( )
 *
 *              SECTION: Hooks
 * 1667:     private function hook_afterConsolidatetRows( )
 *
 *              SECTION: ZZ
 * 1742:     private function zz_drsFirstRow( )
 * 1773:     private function zz_orderByValueIsLocalised( )
 * 1802:     private function zz_setGlobalArrLinkToSingle( )
 *
 * TOTAL FUNCTIONS: 30
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_viewlist
{

  //////////////////////////////////////////////////////
  //
    // Variables set by the pObj (by class.tx_browser_pi1.php)
  // [Array] The current TypoScript configuration array
  var $conf = false;
  // [Integer] The current mode (from modeselector)
  var $mode = false;
  // [String] 'list' or 'single': The current view
  var $view = false;
  // [Array] The TypoScript configuration array of the current view
  var $conf_view = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  var $conf_path = false;
  // Variables set by the pObj (by class.tx_browser_pi1.php)
  // Array with the fields of the SQL result
  var $arr_select;
  // Array with fields from orderBy from TS
  var $arr_orderBy;
  // Array with fields from functions.clean_up.csvTableFields from TS
  var $arr_rmFields;
  // [Array] result of the current SQL query
  var $res = null;
  // [Boolean] True: Query is a union, false: query isn't a union
  var $bool_union = null;
  // [String] Current content
  private $content = null;
  // [object] object of the search class
  private $objSearch = null;
  // [Object] interface of extension radialsearch
  private $objRadialsearch = null;
  // [String] radialsearch "table"/filter. Example: radialsearch
  public $radialsearchTable = null;
  // [Boolean] Radialsearch Sword is set oer isn't set
  private $radialsearchIsSword = null;

  /**
   * Constructor. The method initiate the parent object
   *
   * @param    object        The parent object
   * @return    void
   */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
  }

  /*   * *********************************************
   *
   * Building the views
   *
   * ******************************************** */

  /**
   * main( ): Display a search form, indexBrowser, pageBrowser and a list of records
   *
   * @return    string        $template : The processed HTML template
   * @access  public
   * @version 4.7.0
   * @since 3.9.8
   */
  public function main()
  {
    // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'begin' );

    // RETURN there isn't any list configured
    $prompt = $this->check_view();
    if ( $prompt )
    {
      // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
      return $prompt;
    }
    // RETURN there isn't any list configured
    // Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
    $this->init();

    // Get HTML content
    $this->content = $this->pObj->str_template_raw;
//var_dump( __METHOD__, __LINE__, $this->content );
//die( ':(' );
    // Replace static html marker and subparts by typoscript marker and subparts
    // #43627, 1212105, dwildt, 5+
    $arr_return = $this->content_replaceStaticHtml();
//var_dump( __METHOD__, __LINE__, $arr_return );
//die( ':(' );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }

    // Set SQL query parts in general and statements for rows
    $arr_return = $this->pObj->objSqlInit->init();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
      $content = $arr_return[ 'error' ][ 'header' ] . $arr_return[ 'error' ][ 'prompt' ];
      return $content;
    }
    // Set SQL query parts in general and statements for rows
    //////////////////////////////////////////////////////////////////////
    //
      // csv export versus list view
    // #29370, 110831, dwildt+
    // Get template for csv
    // #i0150, 150408, dwildt, 1-/+
    //switch ( $this->pObj->objExport->str_typeNum )
    switch ( TRUE )
    {
      // #i0150, 150408, dwildt, 1-/+
      //case( 'csv' ) :
      case( $this->pObj->objExport->str_typeNum == 'csv' ) :
        // CASE csv
        // Take the CSV template
        $this->content_setCSV();
        break;
      // #i0150, 150408, dwildt, 3+
      case( strpos( $this->content, '<?xml' ) !== false ):
        $this->content_setXML();
        break;
      default:
        // CASE no csv
        // Take the default template (the list view) and replace some subparts
        $arr_return = $this->content_setDefault();
        if ( $arr_return[ 'error' ][ 'status' ] )
        {
          // Prompt the expired time to devlog
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
          $content = $arr_return[ 'error' ][ 'header' ] . $arr_return[ 'error' ][ 'prompt' ];
          return $content;
        }
        break;
    }
    $content = $this->content;
//var_dump( __METHOD__, __LINE__, $content );
//die( ':(' );
    // Get template for csv
    // csv export versus list view
    // #29370, 110831, dwildt+
    // Building SQL query and get the SQL result
    $arr_return = $this->rows_sql();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
      $content = $arr_return[ 'error' ][ 'header' ] . $arr_return[ 'error' ][ 'prompt' ];
      return $content;
    }

    if ( isset( $arr_return[ 'data' ][ 'res' ] ) )
    {
      $res = $arr_return[ 'data' ][ 'res' ];
      $idsForRecordBrowser = null;
    }
    else
    {
      $res = $arr_return[ 'limited' ][ 'data' ][ 'res' ];
      $idsForRecordBrowser = $arr_return[ 'unlimited' ][ 'data' ][ 'idsOfHitsWoCurrTranslation' ];
    }
    //  #38612, 120703, dwildt+
    // Building SQL query and get the SQL result
    // Set rows
//var_dump( __METHOD__, __LINE__, $res, $arr_return );
    $this->rows_fromSqlRes( $res );
    $rows = $this->pObj->rows;
//var_dump( __METHOD__, __LINE__, $rows);
    // DRS
    if ( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Synonyms aren\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS
    // Consolidate localisation

    $rows = $this->rows_consolidateLL( $rows );
    // #59669, dwildt, 1+
    $this->pObj->rowsLocalised = $rows;
    // Consolidate children
    // #i0047, 140624, dwildt, :TODO:
//    var_dump( __METHOD__, __LINE__, $rows[ 0 ] );
    $rows = $this->rows_consolidateChildren( $rows );
    $this->pObj->rows = $rows;
    // #i0047, 140624, dwildt, :TODO:
//    var_dump( __METHOD__, __LINE__, $rows[ 0 ] );
//    die();
    // Implement the hook rows_filter_values
    $this->hook_afterConsolidatetRows();
    $rows = $this->pObj->rows;
    // Order the rows
    // #i0048, 140627, dwildt, 5-
//    if ( !$this->zz_orderByValueIsLocalised() )
//    {
//      $this->pObj->objMultisort->main();
//      $rows = $this->pObj->rows;
//    }
    // #i0048, 140627, dwildt, 4+: Order in every case
    // Prompt to DRS
    $this->zz_orderByValueIsLocalised();
//$this->pObj->objLocalise->consolidate_rowsDebug( $rows );
    $this->pObj->objMultisort->main();
    $rows = $this->pObj->rows;
//$this->pObj->objLocalise->consolidate_rowsDebug( $rows );
    // Ordering the children rows
    $rows = $this->pObj->objMultisort->multisort_mm_children( $rows );
    $this->pObj->rows = $rows;

    // DRS - :TODO:
    if ( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Hierarchical order isn\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS - :TODO:
    // Delete fields, which were added whily runtime
    $arr_return = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields( $rows );
    $rows = $arr_return[ 'data' ][ 'rows' ];
    unset( $arr_return );

    // #52486, 131005, dwildt, 1+
    $rows = $this->rows_consolidateRadialsearch( $rows );

    $this->pObj->rows = $rows;

//$this->pObj->dev_var_dump( $rows );
    // DRS - display first row
    $this->zz_drsFirstRow();

    // Set the global $arrLinkToSingle
    $this->zz_setGlobalArrLinkToSingle();



    /////////////////////////////////////////////////////////////////
    //
      // Extension pi5: +Browser Calendar
    // Will executed in case, that the Browser is extended with the Browser Calendar user Interface
    $arr_result = $this->pObj->objCal->cal( $rows, $content );
    $bool_success = $arr_result[ 'success' ];
    if ( $bool_success )
    {
      $rows = $arr_result[ 'rows' ];
      $content = $arr_result[ 'template' ];
      $this->pObj->objTemplate->ignore_empty_rows_rule = true;
      if ( $this->pObj->b_drs_cal || $this->pObj->b_drs_templating )
      {
        t3lib_div::devLog( '[INFO/TEMPLATING/CAL/UI]: +Browser Calendar User Interface is loaded.', $this->pObj->extKey, 0 );
      }
      if ( $this->pObj->b_drs_warn )
      {
        t3lib_div::devLog( '[WARN/TEMPLATING/CAL/UI]: +Browser Calendar set ignore_empty_rows_rule to true!', $this->pObj->extKey, 2 );
      }
      // #38612, 120703, dwildt+
      if ( $this->pObj->conf[ 'navigation.' ][ 'record_browser' ] == 1 )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'Record browser isn\'t supported from version 4.1.2';
          t3lib_div::devlog( '[WARN/CAL+RECORDBROWSER] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Rows must converted. PHP snippet must coded by the TYPO3-Browser-Team!';
          t3lib_div::devlog( '[WARN/CAL+RECORDBROWSER] ' . $prompt, $this->pObj->extKey, 2 );
        }
      }
      // #38612, 120703, dwildt+
    }
    $this->pObj->rows = $rows;
    // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'after $this->pObj->objCal->cal( )' );
    // Extension pi5: +Browser Calendar
    /////////////////////////////////////
    //
      // record browser
    //  #38612, 120703, dwildt, 1-
//    $arr_result = $this->pObj->objNaviRecordBrowser->recordbrowser_set_session_data_3x( $rows );
    //  #38612, 120703, dwildt, 1+
    $arr_result = $this->pObj->objNaviRecordBrowser->recordbrowser_set_session_data( $rows, $idsForRecordBrowser );
    if ( $arr_result[ 'error' ][ 'status' ] )
    {
      $prompt = $arr_result[ 'error' ][ 'header' ] . $arr_result[ 'error' ][ 'prompt' ];
      return $this->pObj->pi_wrapInBaseClass( $prompt );
    }
    // record browser
    // #42124, dwildt, 2-
    //$content = $this->pObj->objTemplate->tmplListview( $content, $rows );
    //$this->content = $content;
    // #42124, dwildt, 10+
//$this->pObj->dev_var_dump( $this->pObj->conf['flexform.']['viewList.']['display_listview'] );
//die( __METHOD__ . ' - ' . __LINE__ );
//var_dump( __METHOD__, __LINE__, $content );
//$strDebugTrail = t3lib_utility_Debug::debugTrail();
//$arrDebugTrail = explode( '//', $strDebugTrail );
//var_dump( __METHOD__, __LINE__, $arrDebugTrail );
    switch ( true )
    {
      case( strpos( $content, '###LISTBODYITEM###' ) === false ):
        // Don't render the rows of the listview
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'Template doesn\'t contain the marker ###LISTBODYITEM###. List data won\'t be processed!';
          t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      case( empty( $this->pObj->conf[ 'flexform.' ][ 'viewList.' ][ 'display_listview' ] ) ):
        // Don't render the rows of the listview
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'flexform.viewList.display_listview is 0. List data won\'t be processed.';
          t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      default:
//var_dump( __METHOD__, __LINE__, $rows);
//die( ':(' );
        $content = $this->pObj->objTemplate->tmplListview( $content, $rows );
        break;
    }
    // #42124, dwildt, +
    $this->content = $content;
//var_dump( __METHOD__, __LINE__, $this->content );
    // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );

    // RETURN content
    return $this->content;
  }

  /**
   * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
   *
   * @return    void
   * @access private
   * @version 6.0.0
   * @since 1.0.0
   */
  private function init()
  {
    $this->init_generalStdWrap();
    $this->init_lDisplayList();
    $this->init_lDisplay();
    $this->init_localisation();

    // #61594, 140915, 1+
    $this->initSearch();

    // #52486, 131005, dwildt, 2+
    // Init radialsearch filter and object
    $this->init_filterRadialsearch();
  }

  /**
   * init_generalStdWrap( ) :
   *
   * @return    void
   * @version 4.5.7
   * @since 1.0.0
   */
  private function init_generalStdWrap()
  {
    if ( !is_array( $this->conf_view[ 'general_stdWrap.' ] ) )
    {
      return;
    }

    // Overwrite global general_stdWrap
    // #12471, 110123, dwildt+
    $this->pObj->conf[ 'general_stdWrap.' ] = $this->conf_view[ 'general_stdWrap.' ];
    $this->conf[ 'general_stdWrap.' ] = $this->pObj->conf[ 'general_stdWrap.' ];
    // Overwrite global general_stdWrap
  }

  /**
   * init_lDisplay( ):
   *
   * @return    void
   * @version 4.5.7
   * @since 1.0.0
   */
  private function init_lDisplay()
  {
    // Get the local or global displayList.display
    if ( is_array( $this->conf_view[ 'displayList.' ][ 'display.' ] ) )
    {
      $this->pObj->lDisplay = $this->conf_view[ 'displayList.' ][ 'display.' ];
    }
    if ( !is_array( $this->conf_view[ 'displayList.' ][ 'display.' ] ) )
    {
      $this->pObj->lDisplay = $this->conf[ 'displayList.' ][ 'display.' ];
    }
    // Get the local or global displayList.display
  }

  /**
   * init_lDisplayList( ):
   *
   * @return    void
   * @version 4.5.7
   * @since 1.0.0
   */
  private function init_lDisplayList()
  {
    // Get the local or global displayList
    if ( is_array( $this->conf_view[ 'displayList.' ] ) )
    {
      $this->pObj->lDisplayList = $this->conf_view[ 'displayList.' ];
    }
    if ( !is_array( $this->conf_view[ 'displayList.' ] ) )
    {
      $this->pObj->lDisplayList = $this->conf[ 'displayList.' ];
    }
    // Get the local or global displayList
  }

  /**
   * init_localisation( ) :
   *
   * @return    void
   * @internal  #46062
   *
   * @version   4.5.7
   * @since     4.5.7
   */
  private function init_localisation()
  {
    // SWITCH $int_localisation_mode
    switch ( $this->pObj->objLocalise->get_localisationMode() )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        // RETURN : nothing to do
        return;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        // Follow the workflow
        break;
      default:
        // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
    // SWITCH $int_localisation_mode
    // Loop through all used tables
    foreach ( array_keys( $this->pObj->arr_realTables_arrFields ) as $table )
    {
      // Get the field names for sys_language_content and for l10n_parent
      $languageField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ];
      $transOrigPointerField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField' ];

      switch ( true )
      {
        case( empty( $languageField ) ):
        case( empty( $transOrigPointerField ) ):
          $this->pObj->arr_realTables_notLocalised[] = $table;
          if ( $this->pObj->b_drs_localisation )
          {
            t3lib_div::devlog( '[INFO/LOCALISATION] \'' . $table . '\' isn\'t localised.', $this->pObj->extKey, 0 );
            t3lib_div::devlog( '[INFO/LOCALISATION] Localisation isn\'t needed.', $this->pObj->extKey, 0 );
          }
          break;
        default:
          $this->pObj->arr_realTables_localised[] = $table;
          if ( $this->pObj->b_drs_localisation )
          {
            t3lib_div::devlog( '[INFO/LOCALISATION] \'' . $table . '\' is localised.', $this->pObj->extKey, 0 );
          }
          break;
      }

      unset( $languageField );
      unset( $transOrigPointerField );
    }
    // Loop through all used tables

    $this->pObj->arr_realTables_localised = array_unique( ( array ) $this->pObj->arr_realTables_localised );
    $this->pObj->arr_realTables_notLocalised = array_unique( ( array ) $this->pObj->arr_realTables_notLocalised );
  }

  /**
   * init_filterRadialsearch( ):
   *
   * @return	void
   * @internal    #52486
   * @access  private
   * @version 4.7.0
   * @since   4.7.0
   */
  private function init_filterRadialsearch()
  {
    $path = t3lib_extMgm::extPath( 'browser' ) . 'pi1/';
    require_once( $path . 'class.tx_browser_pi1_filterRadialsearch.php' );

    $this->objFilterRadialsearch = t3lib_div::makeInstance( 'tx_browser_pi1_filterRadialsearch' );
    $this->objFilterRadialsearch->setParentObject( $this->pObj );
    $this->objFilterRadialsearch->setConfView( $this->conf_view );
  }

  /**
   * initSearch( )  :
   *
   * @return	void
   * @internal  #61594
   * @version 6.0.0
   * @since   6.0.0
   */
  private function initSearch()
  {
    if ( is_object( $this->objSearch ) )
    {
      return;
    }
    require_once( PATH_typo3conf . 'ext/browser/pi1/class.tx_browser_pi1_search.php');
    $this->objSearch = new tx_browser_pi1_search( $this->pObj );
  }

  /**
   * check_view( ):
   *
   * @return    string        Error prompt in case of an error
   * @version 3.9.8
   * @since 1.0.0
   */
  private function check_view()
  {
    $mode = $this->mode;

    //////////////////////////////////////////////////////////////////
    //
      // RETURN there isn't any list configured

    $bool_noView = false;
    switch ( true )
    {
      case( empty( $mode ) ):
        $bool_noView = true;
        break;
      case(!is_array( $this->conf_view ) ):
        $bool_noView = true;
        break;
    }
    if ( $bool_noView )
    {
      if ( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog( '[ERROR/DRS] views.list.' . $mode . ' hasn\'t any item.', $this->pObj->extKey, 3 );
        $prompt = 'Did you included the static template from this extensions?';
        t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
        $tsArray = 'plugin.' . $this->pObj->prefixId . '.views.list.' . $mode;
        t3lib_div::devLog( '[HELP/DRS] Did you configure ' . $tsArray . '?', $this->pObj->extKey, 1 );
      }
      $str_header = '<h1 style="color:red">' . $this->pObj->pi_getLL( 'error_typoscript_h1' ) . '</h1>';
      $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' .
              $this->pObj->pi_getLL( 'error_typoscript_no_listview' ) . '</p>';
      return $str_header . $str_prompt;
    }
    // RETURN there isn't any list configured

    return false;
  }

  /*   * *********************************************
   *
   * Content / Template
   *
   * ******************************************** */

  /**
   * content_setCSV( ): Sets content to CSV template
   *
   * @return    void
   * @version 4.1.25
   * @since   3.9.9
   */
  private function content_setCSV()
  {
    // #33336, 130529, dwildt, 3+
    $arr_return = $this->subpart_setSearchboxFilter();
    //$this->pObj->dev_var_dump( $arr_return );
    unset( $arr_return );
    // #33336, 130529, dwildt, 3+
    // Get the label of the subpart marker for the csv content
    $str_marker = $this->conf[ 'flexform.' ][ 'viewList.' ][ 'csvexport.' ][ 'template.' ][ 'marker' ];

    // DRS
    if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
    {
      $prompt = $str_marker . ' is used as template marker.';
      t3lib_div::devlog( '[INFO/TEMPLATING+EXPORT] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
    // Get the csv content file
    $template_path = $this->conf[ 'flexform.' ][ 'viewList.' ][ 'csvexport.' ][ 'template.' ][ 'file' ];
    // Set the csv content
    // #42738, 121106, dwildt, 1-
//    $this->content  = $this->pObj->cObj->fileResource( $template_path );
    // #42738, 121106, dwildt, 2+
    $template = $this->pObj->cObj->fileResource( $template_path );
    $this->content = $this->pObj->cObj->getSubpart( $template, $str_marker );


    // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker );
  }

  /**
   * content_setDefault( ): Sets content to list view template.
   *                        Takes care of the subparts
   *                        * searchbox
   *                        * indexBrowser
   *                        *
   *
   * @return    array        $arr_return: Contains an error message in case of an error
   * @version 7.3.0
   * @since   3.9.9
   */
  private function content_setDefault()
  {
    // HTML template subpart for the list view
    $str_marker = $this->pObj->lDisplayList[ 'templateMarker' ];
    // Set the list view content
    $this->content = $this->pObj->cObj->getSubpart( $this->content, $str_marker );

    // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker );

    // #43627, 1212105, dwildt, 5+
    // Replace static html marker and subparts by typoscript marker and subparts
    // Set search box and filter
    $arr_return = $this->subpart_setSearchbox();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Set search box and filter
    // Set index browser
    $arr_return = $this->subpart_setIndexBrowser();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Set index browser
    // Set page browser
    $arr_return = $this->subpart_setPageBrowser();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Set page browser
    // Set mode selector
    $arr_return = $this->subpart_setModeSelector();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }

    // #i0208, 151202, dwildt, 2+
    $markerArray = $this->pObj->objWrapper4x->constant_markers();
    // #i0212, 151217, dwildt, 1-/+
    //$this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, $markerArray );
    $this->content = $this->pObj->cObj->substituteMarkerArray( $this->content, ( array ) $markerArray );

    // Set mode selector
//$this->pObj->dev_var_dump( $marker, $hashMarker, $content, $this->content );
    return;
  }

  /**
   * content_setXML( ):
   *
   * @return    void
   * @version 7.0.6
   * @since   7.0.6
   * @internal #i0150
   */
  private function content_setXML()
  {
    // HTML template subpart for the list view
    $str_marker = $this->pObj->lDisplayList[ 'templateMarker' ];
    // Set the list view content
    $this->content = $this->pObj->cObj->getSubpart( $this->content, $str_marker );

    // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker );
  }

  /**
   * content_replaceStaticHtml( ):
   *
   * @return    array        $arr_return: Contains an error message in case of an error
   * @version 5.0.0
   * @since   4.1.26
   * @internal  #43627
   */
  private function content_replaceStaticHtml()
  {
    $this->content = $this->pObj->objTemplate->htmlStaticReplace( $this->content, $this->conf_view );
    return;
  }

  /**
   * content_dieIfEmpty( ): If content is empty, the methods will die the workflow
   *                        with a qualified prompt.
   *
   * @param    string        $marker:  subpart marker
   * @return    void        ...
   * @version 5.0.0
   * @since   3.9.12
   */
  private function content_dieIfEmpty( $marker )
  {
    if ( empty( $this->content ) )
    {
      $prompt = '<div style="border:2em solid red;color:red;padding:2em;text-align:center;">
          <h1>
            TYPO3 Browser Error
          </h1>
          <h2>
            EN: Subpart is missing
          </h2>
          <p>
            English: Current HTML template doesn\'t contain the subpart \'' . $marker . '\' .<br />
            Please take care of a proper template.<br />
            Possible reason for this error: The static template of the Browser is missing.
          </p>
          <h2>
            DE: Subpart fehlt
          </h2>
          <p>
            Deutsch: Dem aktuellen HTML-Template fehlt der Subpart \'' . $marker . '\'.<br />
            Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
            M&ouml;gliche Ursache f&uuml;r den Fehler: Das Static Template des Browsers ist nicht eingebunden.
          </p>
        </div>';
      $header = null;
      $text = null;
      $this->pObj->drs_die( $header, $text, $prompt );
    }
  }

  /*   * *********************************************
   *
   * SQL
   *
   * ******************************************** */

  /**
   * queryWiAndFilter( ) : If there si an AND-filter, move the query from OR-filter mode to AND-filter mode
   *
   * @param    string        $query             : default query with filter in OR-mode
   * @param    string        $limit             : SQL limit statement without LIMIT
   * @param    string        $subQueryTemplate  : Optional: query template for the subquery
   * @return   string        $query             : default query with filter in AND-mode, if needed
   * @access public
   * @internal #56329
   * @version 4.8.6
   * @since   4.8.6
   */
  public function queryWiAndFilter( $query, $limit, $subQueryTemplate = null )
  {
    // RETURN : there isn't any filter
    if ( !$this->pObj->objFltr4x->get_selectedFilters() )
    {
      return $query;
    }

    foreach ( ( array ) $this->pObj->objFltr4x->get_selectedFilters() as $tableField )
    {
      $tsFilter = $this->conf_view[ 'filter.' ];
      list( $table, $field ) = explode( '.', $tableField );
      $modeAndOr = $tsFilter[ $table . '.' ][ $field . '.' ][ 'modeAndOr' ];

      switch ( $modeAndOr )
      {
        case( 'AND' ):
          if ( $this->pObj->b_drs_filter )
          {
            $prompt = $tableField . 'modeAndOr is "AND"';
            t3lib_div::devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
          }
          $query = $this->queryWiAndFilterRender( $query, $limit, $tableField, $subQueryTemplate );
          break;
        case( 'OR' ):
        default:
          if ( $this->pObj->b_drs_filter )
          {
            $prompt = $tableField . 'modeAndOr is "OR" or isn\'t set.';
            t3lib_div::devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
          }
          continue;
      }
    }

    return $query;
  }

  /**
   * queryWiAndFilterRender( ) : If there is an AND-filter, move the query from OR-filter mode to AND-filter mode
   *
   * @param    string        $query             : default query with filter in OR-mode
   * @param    string        $limit             : SQL limit statement without LIMIT
   * @param    string        $subQueryTemplate  : Optional: query template for the subquery
   * @param    string        $tableField        : current filter (table.field)
   * @return   string        $query             : default query with filter in AND-mode, if needed
   * @access private
   * @internal #56329
   * @version 4.8.6
   * @since   4.8.6
   */
  private function queryWiAndFilterRender( $query, $limit, $tableField, $subQueryTemplate = null )
  {
    $uids = $this->queryWiAndFilterRenderUids( $tableField ); // Uids of enabled filter items

    $loop = 0;
    $maxLoops = count( $uids ) - 1;

    // RETURN : There is one enabled filter item at maximum. No subquery isn't needed.
    if ( $maxLoops <= 0 )
    {
      return $query;
    }

    list( $table ) = explode( '.', $tableField );
    $andWhere = trim( $this->pObj->objFltr4x->arr_andWhereFilter[ $tableField ] );
    $limit = ' LIMIT ' . $limit;      // Limit statement. Will removed from SQL statement.

    if ( $subQueryTemplate === null )
    {
      $subQueryTemplate = $query;
    }

    foreach ( $uids AS $uid )
    {
      $subQuery = 'AND ' . $table . '.uid = ' . $uid;
      switch ( true )
      {
        case( $loop >= $maxLoops ):
          // do nothing
          break;
        case( $loop < $maxLoops ):
        default:
          $subQuery = $subQuery . ' AND ' . $this->pObj->localTable . '.uid IN ( ' . $subQueryTemplate . ' )';
          $subQuery = str_replace( $limit, null, $subQuery );
          break;
      }
      $query = str_replace( $andWhere, $subQuery, $query );
      //$this->pObj->dev_var_dump($andWhere, $subQuery, str_replace( '\'', '"', $query ));
      $loop = $loop + 1;
    }

//    $this->pObj->dev_var_dump(str_replace( '\'', '"', $query ));
    return $query;
  }

  /**
   * queryWiAndFilterRenderUids( ) : Returns the uids of the enabled filter items
   *
   * @param    string         $tableField : current filter (table.field)
   * @return   array          $uids       : uids of the enabled filter items
   * @access private
   * @internal #56329
   * @version 4.8.6
   * @since   4.8.6
   */
  private function queryWiAndFilterRenderUids( $tableField )
  {
    $match = array();                             // Result variable for the  preg_match method
    $haystack = trim( $this->pObj->objFltr4x->arr_andWhereFilter[ $tableField ] );
    // Get brackets with content like "(3,7,4)"
    preg_match_all( '|\(.*\)|', $haystack, $match, PREG_PATTERN_ORDER );
    $csvUids = trim( $match[ 0 ][ 0 ], '()' );          // Trim brackets
    $csvUids = str_replace( ' ', null, $csvUids );  // Remove spaces
    $uids = explode( ',', $csvUids );               // Move csv list to an array

    return $uids;
  }

  /**
   * rows_consolidateLL( ): Consolidate localisation. Returns consolidated rows.
   *
   * @param    array        $rows  : consolidated rows
   * @return    void
   * @access  private
   * @version 3.9.12
   * @since   3.9.12
   */
  private function rows_consolidateLL( $rows )
  {
    // RETURN : SQL manual mode
    if ( $this->pObj->b_sql_manual )
    {
      if ( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Manual SQL mode: Rows didn\'t get any localisation consolidation.';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return $rows;
    }
    // RETURN : SQL manual mode
    // Consolidate Localisation
    $rows = $this->pObj->objLocalise->consolidate_rows( $rows, $this->pObj->localTable );

    return $rows;
  }

  /**
   * rows_consolidateChildren( ): Consolidate children, returns consolidated rows.
   *
   * @param    array        $rows  : consolidated rows
   * @return    void
   * @version 4.5.6
   * @since   3.9.12
   */
  private function rows_consolidateChildren( $rows )
  {
    // #47680, 130502, dwildt, -
//      // RETURN : SQL manual mode
//    if( $this->pObj->b_sql_manual )
//    {
//      if( $this->pObj->b_drs_localisation )
//      {
//        $prompt = 'Manual SQL mode: Rows didn\'t get any consolidation for children.';
//        t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
//      }
////$this->pObj->dev_var_dump( $rows );
////$arr_return       = $this->pObj->objConsolidate->consolidate( $rows );
////$rows             = $arr_return['data']['rows'];
////$this->pObj->dev_var_dump( $rows );
//      return $rows;
//    }
//      // RETURN : SQL manual mode
    // #47680, 130502, dwildt, -
    // Consolidate children
    $arr_return = $this->pObj->objConsolidate->consolidate( $rows );
    $rows = $arr_return[ 'data' ][ 'rows' ];
//    $int_rows_wo_cons = $arr_return['data']['rows_wo_cons'];
//    $int_rows_wi_cons = $arr_return['data']['rows_wi_cons'];

    return $rows;
  }

  /**
   * rows_consolidateRadialsearch( )  : A record has one lat and lon only - independing of children records.
   *                                    This method removes distances but the first.
   *
   * @param    array        $rows  : rows
   * @return    array       $rows  : consolidated rows
   * @access  private
   * @internal #52486
   * @version 4.7.0
   * @since   4.7.0
   */
  private function rows_consolidateRadialsearch( $rows )
  {
    // RETURN : There isn't any radialsearch sword
    if ( !$this->objFilterRadialsearch->getSword() )
    {
      return $rows;
    }

    $this->pObj->objTyposcript->set_confSqlDevider();
    $str_devider = $this->pObj->objTyposcript->str_sqlDeviderDisplay
            . $this->pObj->objTyposcript->str_sqlDeviderWorkflow
    ;

    $labelDistance = $this->objFilterRadialsearch->getLabelDistance();

    foreach ( $rows as $key => $row )
    {
      $distance = explode( $str_devider, $row[ $labelDistance ] );
      $rows[ $key ][ $labelDistance ] = $distance[ 0 ];
    }

    return $rows;
  }

  /**
   * rows_sql( ): Move SQL result to rows and set the global var $rows.
   *
   * @param    array        $res  : current SQL result
   * @return    void
   * @version 4.1.25
   * @since   3.9.12
   */
  private function rows_fromSqlRes( $res )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'start' );

    $conf_view = $this->conf_view;

    // Get aliases
    $arr_table_realnames = $conf_view[ 'aliases.' ][ 'tables.' ];

    // SWITCH case aliases
    switch ( true )
    {
      // #47680, 130430,dwildt, 12+
      case( $this->pObj->b_sql_manual ):
        $rows = $this->rows_getDefault( $res );
        // DRS
        if ( $this->pObj->b_drs_devTodo )
        {
          $prompt = 'Aliases won\'t handled in ' . __METHOD__;
          t3lib_div::devlog( '[TODO/SQL-MANUAL-MODE 4.x] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Maybe a new fetaure aliases yes/no is the solution. ';
          t3lib_div::devlog( '[TODO/SQL-MANUAL-MODE 4.x] ' . $prompt, $this->pObj->extKey, 3 );
        }
        // DRS
        break;
      case( is_array( $arr_table_realnames ) ):
        $rows = $this->rows_getCaseAliases( $res );
        break;
      case(!is_array( $arr_table_realnames ) ):
      default:
        $rows = $this->rows_getDefault( $res );
        break;
    }
    // SWITCH case aliases

    unset( $arr_table_realnames );

    // SQL Free Result
    // #42302, dwildt, 1-
    //$GLOBALS['TYPO3_DB']->sql_free_result( $this->res );
    // #42302, dwildt, 1+
    // #i0113, dwildt, 1-
    //$GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );
    // Set global var
    $this->pObj->rows = $rows;

    // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'stop' );
    // Building $rows
  }

  /**
   * rows_getCaseAliases( ):  Move SQL result to rows, depending on
   *                          values from aliases.tables
   *
   * @param    array        $res  : current SQL result
   * @return    array        $rows : the rows
   * @version 4.1.2
   * @since   3.9.12
   */
  private function rows_getCaseAliases( $res )
  {
    $rows = array();
    $conf_view = $this->conf_view;
    $arr_table_realnames = $conf_view[ 'aliases.' ][ 'tables.' ];

    $i_row = 0;
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      foreach ( array_keys( $row ) as $str_tablealias_field )
      {
        $arr_tablealias_field = explode( '.', $str_tablealias_field ); // table_1.sv_name
        $str_tablealias = $arr_tablealias_field[ 0 ];              // table_1
        $str_field = $arr_tablealias_field[ 1 ];              // sv_name
        $str_table = $arr_table_realnames[ $str_tablealias ]; // tx_civserv_service
        $str_table_field = $str_table . '.' . $str_field;         // tx_civserv_service.sv_name
        if ( $str_table_field == '.' )
        {
          $str_table_field = $str_tablealias_field;
        }
        $rows[ $i_row ][ $str_table_field ] = $row[ $str_tablealias_field ];
      }
      $i_row++;
    }

    return $rows;
  }

  /**
   * rows_getDefault( ): Move SQL result to rows
   *
   * @param    array        $res  : current SQL result
   * @return    array        $rows : the rows
   * @version 7.0.3
   * @since   3.9.12
   */
  private function rows_getDefault( $res )
  {
    $rows = array();

    // RETURN rows
    if ( !empty( $res ) )
    {
      while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
      {
        $rows[] = $row;
      }

      return $rows;
    }

    // #i0139, 150310, dwildt, 1+
    return null;
  }

  /**
   * rows_sql( ): Building the SQL query, returns the SQL result.
   *
   * @return    array        $arr_return: Contains the SQL res or an error message
   * @version 3.9.13
   * @since   3.9.12
   */
  private function rows_sql()
  {
    // DRS
    if ( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Update the manual: ORDER BY has unwanted effects, if ORDER BY value is localised.';
      $prompt = $prompt . ' The sequence of rows will be: [ ordered [ordered rows of foreign
                language limit 0,20] + [oderded rows of default language limit the rest]], but not
                [ordered rows 0,20]. Sorry, but this is a need of performance.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS
    // 120704, freemedia case, dwildt
    $curr_int_localisation_mode = null;
    if ( $this->pObj->conf[ 'navigation.' ][ 'record_browser.' ][ 'special.' ][ 'listViewWithDefaultLanguage' ] )
    {
      if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_navi )
      {
        $prompt = 'navigation.record_browser.special.listViewWithDefaultLanguage is true and will set PI1_DEFAULT_LANGUAGE temporarily.';
        t3lib_div::devlog( '[INFO/LOCALISATION+NAVI] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // Store current localisation mode
      $curr_int_localisation_mode = $this->pObj->objLocalise->get_localisationMode();
      // Set all to default language
      //$this->pObj->objLocalise->int_localisation_mode = PI1_DEFAULT_LANGUAGE;
      $this->pObj->objLocalise->setLocalisationMode( PI1_DEFAULT_LANGUAGE );
    }
    // 120704, freemedia case, dwildt

    switch ( $this->pObj->objLocalise->get_localisationMode() )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        $arr_return = $this->rows_sqlLanguageDefault();
//        $arr_return = $arr_return['limited'];
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        $arr_return = $this->rows_sqlLanguageFirstDefaultOrFirstTranslated();
        break;
      default:
        // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }

    // 120704, freemedia dwildt
    if ( $curr_int_localisation_mode != null )
    {
      if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_navi )
      {
        $prompt = 'Localisation mode is reseted';
        t3lib_div::devlog( '[INFO/LOCALISATION+NAVI] ' . $prompt, $this->pObj->extKey, 0 );
      }
      //$this->pObj->objLocalise->int_localisation_mode = $curr_int_localisation_mode;
      $this->pObj->objLocalise->setLocalisationMode( $curr_int_localisation_mode );
    }
    // 120704, freemedia dwildt
//$this->pObj->dev_var_dump( $arr_return );

    return $arr_return;
  }

  /**
   * rows_sqlIdsOfRowsWiTranslationOnly( ) : Get the ids of default or translated rows
   *
   * @return    array        $arr_return: Contains the ids
   * @version 4.1.2
   * @since   3.9.13
   */
  private function rows_sqlIdsOfRowsWiTranslationOnly()
  {
    // Get ids of records, which match the rules and have a translation for the current language
    // Get all ids
    $withAllIds = array();
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $withAllIds );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $idsWiCurrTranslationLimited = $arr_return[ 'data' ][ 'idsWiCurrTranslation' ];
    $idsOfTranslationRowsLimited = $arr_return[ 'data' ][ 'idsOfTranslationRows' ];
    // Get ids of records, which match the rules and have a translation for the current language

    $idsOfDefaultLanguageRowsLimited = array();
    if ( empty( $idsOfTranslationRowsLimited ) )
    {
      // Get ids of records of default language, which match the rules but haven't any translation
      $withAllIds = array();
      $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withAllIds );
      if ( $arr_return[ 'error' ][ 'status' ] )
      {
        return $arr_return;
      }
      $idsOfDefaultLanguageRowsLimited = $arr_return[ 'data' ][ 'idsOfHitsWoCurrTranslation' ];
      // Get ids of records of default language, which match the rules but haven't any translation

      if ( empty( $idsOfDefaultLanguageRowsLimited ) )
      {
        return $arr_return;
      }
    }

    // List view
    // Merge all ids
    $withIds = array_merge(
            ( array ) $idsWiCurrTranslationLimited, ( array ) $idsOfTranslationRowsLimited, ( array ) $idsOfDefaultLanguageRowsLimited
    );

    // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );
    // List view

    return $arr_return;
  }

  /**
   * rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation( ) : Get the ids of default or translated rows
   *
   * @return    array        $arr_return: Contains the ids
   * @version 3.9.13
   * @since   3.9.13
   */
  private function rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation()
  {
    // Get ids of records, which match the rules and have a translation for the current language
    // Get all ids
    $withIds = array();
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $withIds );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $withoutIds = $arr_return[ 'data' ][ 'idsWiCurrTranslation' ];
    $idsOfTranslationRows = $arr_return[ 'data' ][ 'idsOfTranslationRows' ];
    // Get ids of records, which match the rules and have a translation for the current language
    // Get ids of records of default language, which match the rules but haven't any translation
    $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds );
//if ( $GLOBALS[ 'TSFE' ]->id == 200 )
//{
//  var_dump( __METHOD__, __LINE__, $arr_return );
//}
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $idsOfDefaultLanguageRows = $arr_return[ 'data' ][ 'idsOfHitsWoCurrTranslation' ];
    // Get ids of records of default language, which match the rules but haven't any translation
    // Merge all ids
    $withIds = array_merge(
            ( array ) $withoutIds, ( array ) $idsOfTranslationRows, ( array ) $idsOfDefaultLanguageRows
    );

    // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );
//if ( $GLOBALS[ 'TSFE' ]->id == 200 )
//{
//  var_dump( __METHOD__, __LINE__, $arr_return );
//}

    return $arr_return;
  }

  /**
   * rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation( ) : Get the ids of default or translated rows
   *
   * @return    array        $arr_return: Contains the SQL res or an error message
   * @version 3.9.13
   * @since   3.9.13
   */
  private function rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation()
  {

    // Get ids of records of default language, which match the rules
    // get all ids
    $withoutIds = array();
    $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $idsOfDefaultLanguageRows = $arr_return[ 'data' ][ 'idsOfHitsWoCurrTranslation' ];
    // Get ids of records of default language, which match the rules
    // Get ids of the translation records of the matched default records
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $idsOfDefaultLanguageRows );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $idsOfTranslationRows = $arr_return[ 'data' ][ 'idsOfTranslationRows' ];
    // Get ids of the translation records of the matched default records
    // Merge all ids
    $withIds = array_merge(
            ( array ) $idsOfTranslationRows, ( array ) $idsOfDefaultLanguageRows
    );

    // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );

    return $arr_return;
  }

  /**
   * rows_sqlIdsOfRowsWiTranslation( ) : Get ids of rows with translated records and ids of translated records
   *
   * @param    [type]        $$withIds: ...
   * @return    array        $arr_return: Array with two elements with the ids
   * @version 4.7.0
   * @since   3.9.13
   */
  private function rows_sqlIdsOfRowsWiTranslation( $withIds )
  {
    $arr_return = array();

    // SWITCH $int_localisation_mode
    switch ( $this->pObj->objLocalise->get_localisationMode() )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
        {
          $prompt = 'Any id of translated row isn\'t needed.';
          t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        // RETURN : nothing to do
        return $arr_return;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        // Follow the workflow
        break;
      default:
        // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
    // SWITCH $int_localisation_mode
    // Get localtable
    $table = $this->pObj->localTable;

    // Label of field with the uid of the record with the default language
    $labelOfParentUid = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField' ];
    $labelSysLanguageId = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ];

    // RETURN : table is not localised
    if ( (!$labelOfParentUid ) || (!$labelSysLanguageId ) )
    {
      if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
      {
        $prompt = $table . ' isn\'t localised.';
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $arr_return;
    }
    // RETURN : table is not localised
    // Fields for the SELECT statement
    $tableUid = $table . ".uid";
    $tableL10nParent = $table . "." . $labelOfParentUid;


    // SQL query array
    // #52486, 131005, dwildt, ~
    $select = "DISTINCT " . $tableUid . " AS '" . $tableUid . "',
                          " . $tableL10nParent . " AS '" . $tableL10nParent . "'"
            . $this->sql_radialsearchSelect()
    ;
    $from = $this->pObj->objSqlInit->statements[ 'listView' ][ 'from' ];
    // If FROM contains a relation from $tableUid to a foreign table, move
    //    $tableUid to $tableL10nParent
    $from = str_replace( $tableUid . ' = ', $tableL10nParent . ' = ', $from );
    // #52486, 131005, dwildt, 3+
    $from = $from
            . $this->sql_radialsearchFrom()
    ;
    $where = $this->pObj->objSqlInit->statements[ 'listView' ][ 'where' ];
    $andWhere = $table . '.' . $labelSysLanguageId . " = " . intval( $this->pObj->objLocalise->lang_id ) . " ";
    $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );

    $withIdList = implode( ',', ( array ) $withIds );
    $andWhere = null;
    if ( $withIdList )
    {
      $andWhere = $tableL10nParent . " IN (" . $withIdList . ")";
    }
    $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
    if ( $this->pObj->objFltr4x->get_selectedFilters() )
    {
      $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $this->pObj->objFltr4x->andWhereFilter );
    }

    if ( !empty( $this->pObj->objNaviIndexBrowser->findInSetForCurrTab ) )
    {
      $findInSetForCurrTab = $this->pObj->objNaviIndexBrowser->findInSetForCurrTab;
      $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $findInSetForCurrTab );
    }

    // #52486, 131005, dwildt, 4+
    $where = $where
            . $this->sql_radialsearchWhere()
            . $this->sql_radialsearchHaving()
    ;

    $groupBy = null;

    // #52486, 131005, dwildt, 5+
    $orderBy = $this->sql_radialsearchOrderBy();
    if ( !empty( $orderBy ) )
    {
      $orderBy = $orderBy . ',';
    }
    // #52486, 131005, dwildt, ~
    $orderBy = $orderBy
            . $this->pObj->objSqlInit->statements[ 'listView' ][ 'orderBy' ]
    ;

    $limit = $this->conf_view[ 'limit' ];

    if ( $withIdList )
    {
      $limit = null;
    }
    // SQL query array
    // Get query
    $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery
            (
            $select, $from, $where, $groupBy, $orderBy, $limit
    );
    // #56329, 140226, dwildt, 1+
    $query = $this->queryWiAndFilter( $query, $limit );

    // Execute
    $promptOptimise = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
            'Don\'t use the query in a localised context.';
    $debugTrailLevel = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
    // Execute
    // Error management
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Error management
    // Get ids of rows with translated records and ids of translated records
    $res = $arr_return[ 'data' ][ 'res' ];
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      $arr_return[ 'data' ][ 'idsWiCurrTranslation' ][] = $row[ $tableL10nParent ];
      $arr_return[ 'data' ][ 'idsOfTranslationRows' ][] = $row[ $tableUid ];
    }
    // Get ids of rows with translated records and ids of translated records
    // Free SQL result
    // #i0113, dwildt, 1-
    //$GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );
    //////////////////////////////////////////////////
    //
      // RETURN record browser isn't enabled
    // #38612, 120703, dwildt+
    if ( !( $this->pObj->conf[ 'navigation.' ][ 'record_browser' ] == 1 ) )
    {
      if ( $this->pObj->b_drs_session || $this->pObj->b_drs_templating )
      {
        $value = $this->pObj->conf[ 'navigation.' ][ 'record_browser' ];
        t3lib_div::devlog( '[INFO/SQL+RECORDBROWSER] navigation.record_browser is \'' . $value . '\' ' .
                'Record browser doesn\'t cause any SQL query (best performance).', $this->pObj->extKey, 0 );
      }
      return $arr_return;
    }
    // RETURN record browser isn't enabled
    //////////////////////////////////////////////////
    //
      // Workflow for recordbrowser
    // #38612, 120703, dwildt+
    // Get query without any limit
    $limit = null;
    $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery
            (
            $select, $from, $where, $groupBy, $orderBy, $limit
    );
    // Get query
    // Execute query
    $promptOptimise = 'Maintain the performance? Disable the record browser of the single view.';
    $debugTrailLevel = 1;
    $arr_return[ 'unlimited' ] = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
    // Execute query
    // Error management
    if ( $arr_return[ 'unlimited' ][ 'error' ][ 'status' ] )
    {
      $arr_return[ 'error' ] = $arr_return[ 'unlimited' ][ 'error' ];
      return $arr_return;
    }
    // Error management
    // Get the SQL result
    $res = $arr_return[ 'unlimited' ][ 'data' ][ 'res' ];

    // Get the ids
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      $arr_return[ 'unlimited' ][ 'data' ][ 'idsWiCurrTranslation' ][] = $row[ $tableL10nParent ];
      $arr_return[ 'unlimited' ][ 'data' ][ 'idsOfTranslationRows' ][] = $row[ $tableUid ];
    }
    // Get the ids
    // Free SQL result
    // #i0113, dwildt, 1-
    //$GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );
//if ( $GLOBALS[ 'TSFE' ]->id == 200 )
//{
//  var_dump( __METHOD__, __LINE__, $arr_return );
//}

    return $arr_return;
    // Workflow for recordbrowser
  }

  /**
   * rows_sqlIdsOfRowsWiDefaultLanguage( ):  Get ids of rows of the default language. Rows
   *                                       which ids within the array $withoutIds will
   *                                       ignored
   *
   * @param    array        $withoutIds : Ids of rows, which have a translated record
   * @param    boolean        $limited    : true: query gets a limit
   * @return    array        $arr_return : Contains the ids of rows
   * @version 4.8.6
   * @since   3.9.13
   */
  private function rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds, $limited = true )
  {
    $arr_return = null;

    // #38612, 120703, dwildt+
    if ( $limited == false )
    {
      // RETURN record browser isn't enabled
      if ( !( $this->pObj->conf[ 'navigation.' ][ 'record_browser' ] == 1 ) )
      {
        // DRS
        if ( $this->pObj->b_drs_session || $this->pObj->b_drs_browser )
        {
          $value = $this->pObj->conf[ 'navigation.' ][ 'record_browser' ];
          t3lib_div::devlog( '[INFO/SQL+RECORDBROWSER] navigation.record_browser is \'' . $value . '\' ' .
                  'Record browser doesn\'t cause any SQL query (best performance).', $this->pObj->extKey, 0 );
        }
        return $arr_return;
        // DRS
      }
      // RETURN record browser isn't enabled
    }
    // #38612, 120703, dwildt+
    // SWITCH $int_localisation_mode
    switch ( $this->pObj->objLocalise->get_localisationMode() )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        // Follow the workflow
        break;
      default:
        // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
    // SWITCH $int_localisation_mode
    // Get localtable
    $table = $this->pObj->localTable;
    // fields for the SELECT statement
    $tableUid = $table . ".uid";

    // RETURN : table is not localised
    $andWhereSysLanguage = null;
    if ( !$GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ] )
    {
      if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
      {
        $prompt = $table . ' isn\'t localised.';
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
//        // RETURN : nothing to do
//      return $arr_return;
      // andWhere sys_language_uid ...
    }
    if ( $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ] )
    {
      $andWhereSysLanguage = $table . '.' . $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ] . " <= 0";
    }
    // RETURN : table is not localised
    // andWhere list of ids ...
    $withoutIdList = implode( ',', ( array ) $withoutIds );
    $andWhereIdList = null;
    if ( $withoutIdList )
    {
      $andWhereIdList = $tableUid . " NOT IN (" . $withoutIdList . ")";
    }

    // SQL query array
    // #52486, 131005, dwildt, ~
    $select = "DISTINCT " . $tableUid . " AS '" . $tableUid . "'"
            . $this->sql_radialsearchSelect()
    ;
    // #52486, 131005, dwildt, ~
    $from = $this->pObj->objSqlInit->statements[ 'listView' ][ 'from' ]
            . $this->sql_radialsearchFrom()
    ;
    $where = $this->pObj->objSqlInit->statements[ 'listView' ][ 'where' ];
//// #62546
//var_dump(__METHOD__, __LINE__, $where );
    $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhereSysLanguage );
    $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhereIdList );
    if ( $this->pObj->objFltr4x->get_selectedFilters() )
    {
//$this->pObj->dev_var_dump( str_replace( '\'', '"', $this->pObj->objFltr4x->get_selectedFilters( ) ) );
//$this->pObj->dev_var_dump( str_replace( '\'', '"', $this->pObj->objFltr4x->andWhereFilter ) );
      $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $this->pObj->objFltr4x->andWhereFilter );
    }

    if ( !empty( $this->pObj->objNaviIndexBrowser->findInSetForCurrTab ) )
    {
      $findInSetForCurrTab = $this->pObj->objNaviIndexBrowser->findInSetForCurrTab;
      $where = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $findInSetForCurrTab );
    }
    // #52486, 131005, dwildt, 4+
    $where = $where
            . $this->sql_radialsearchWhere()
            . $this->sql_radialsearchHaving()
    ;

    $groupBy = null;

    // #52486, 131005, dwildt, 5+
    $orderBy = $this->sql_radialsearchOrderBy();
    if ( !empty( $orderBy ) )
    {
      $orderBy = $orderBy . ',';
    }
    // #52486, 131005, dwildt, ~
    $orderBy = $orderBy
            . $this->pObj->objSqlInit->statements[ 'listView' ][ 'orderBy' ]
    ;

    switch ( $limited )
    {
      // #42738, 121106, dwildt, 1+
      case( $this->pObj->objExport->str_typeNum == 'csv' ):
      case( false ):
        $limit = null;
        break;
      case( true ):
      default:
        // LIMIT  : reduce amount of rows by amount of translated rows
        $limit = $this->conf_view[ 'limit' ];
        list( $start, $results_at_a_time ) = explode( ',', $limit );
        $results_at_a_time = ( int ) $results_at_a_time - count( $withoutIds );
        if ( $results_at_a_time < 0 )
        {
          $prompt = 'Sorry, this error shouldn\'t occurred: Amount of displayed rows is \'' . $results_at_a_time . '\'.<br />
                    <br />
                    Method: ' . __METHOD__ . '<br />
                    Line: ' . __LINE__ . '<br />
                    <br />
                    TYPO3 Browser';
          echo $prompt;
        }
        $limit = ( int ) $start . "," . $results_at_a_time;
        // LIMIT  : reduce amount of rows by amount of translated rows
        break;
    }
    // SQL query array
    // Get query
    $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery
            (
            $select, $from, $where, $groupBy, $orderBy, $limit
    );
    // Get query
    // #56329, 140226, dwildt, 1+
    $query = $this->queryWiAndFilter( $query, $limit );
//var_dump( __METHOD__, __LINE__, str_replace( '\'', '"', $query ) );
    //$this->pObj->dev_var_dump( str_replace( '\'', '"', $query ) );
    // Execute query
    $promptOptimise = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
            'Don\'t use the query in a localised context.';
    $debugTrailLevel = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
    // Execute query
    // Error management
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Error management
    // Get the SQL result
    $res = $arr_return[ 'data' ][ 'res' ];

    // Get the ids
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      $arr_return[ 'data' ][ 'idsOfHitsWoCurrTranslation' ][] = $row[ $tableUid ];
    }
    // Get the ids
    // Free SQL result
    // #i0113, dwildt, 1-
    //$GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );

    return $arr_return;
  }

  /**
   * rows_sqlLanguageDefault( ): Building the SQL query, returns the SQL result.
   *
   * @return    array        $arr_return: Contains the SQL res or an error message
   * @version 4.1.2
   * @since   3.9.13
   */
  private function rows_sqlLanguageDefault()
  {
    $arr_return = array();
    $withoutIds = array();

    $limited = false;
    $arr_return[ 'unlimited' ] = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds, $limited );

    $limited = true;
    $arr_return[ 'limited' ] = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds, $limited );
    if ( $arr_return[ 'limited' ][ 'error' ][ 'status' ] )
    {
      $arr_return[ 'error' ] = $arr_return[ 'limited' ][ 'error' ];
      return $arr_return;
    }
    $idsOfRowsDefaultLanguageLimited = $arr_return[ 'limited' ][ 'data' ][ 'idsOfHitsWoCurrTranslation' ];
    // Get ids of records of default language

    if ( empty( $idsOfRowsDefaultLanguageLimited ) )
    {
      return $arr_return;
    }

    // Get rows for the list view
    // #38612, 120703, dwildt-/+
    $arr_return[ 'limited' ] = $this->rows_sqlRowsbyIds( $idsOfRowsDefaultLanguageLimited );

    return $arr_return;
  }

  /**
   * rows_sqlLanguageFirstDefaultOrFirstTranslated( ): Get the ids of default or translated rows
   *
   * @return    array        $arr_return: Contains the ids
   * @version 3.9.13
   * @since   3.9.13
   */
  private function rows_sqlLanguageFirstDefaultOrFirstTranslated()
  {
    // SWITCH : is index browser or ORDER BY ?localised
    switch ( true )
    {
      case( $this->zz_indexBrowserIsLocalised() ):
        $arr_return = $this->rows_sqlIdsOfRowsWiTranslationOnly();
//$this->pObj->dev_var_dump( $arr_return );
        break;
      case( $this->zz_orderByValueIsLocalised() ):
        // First value of ORDER BY is localised
        $arr_return = $this->rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation();
//$this->pObj->dev_var_dump( $arr_return );
        break;
      // First value of ORDER BY is localised
      case(!$this->zz_orderByValueIsLocalised() ):
      default:
        // First value of ORDER BY isn't localised
        $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation();
//$this->pObj->dev_var_dump( $arr_return );
        break;
      // First value of ORDER BY isn't localised
    }
    // SWITCH : is index browser or ORDER BY ?localised

    return $arr_return;
  }

  /**
   * rows_sqlRowsbyIds( ): Get the rows for the list view. The method returns the SQL result, but an array.
   *
   * @param    string        $withIds     : Ids of the rows for the lost view
   * @return    array        $arr_return : Contains the SQL res or an error message
   * @version 4.7.0
   * @since   3.9.13
   * @todo    120506, dwildt: filterIsSelected
   */
  private function rows_sqlRowsbyIds( $withIds )
  {
    // 120927, dwildt, +
    if ( empty( $withIds ) )
    {
      return false;
    }
    // 120927, dwildt, +
    // SQL query array
    $select = $this->pObj->objSqlInit->statements[ 'listView' ][ 'select' ];
    $select = $this->sql_selectLocalised( $select );
    // #52486, 131005, dwildt, 3+
    $select = $select
            . $this->sql_radialsearchSelect()
    ;

    // #52486, 131005, dwildt, ~
    $from = $this->pObj->objSqlInit->statements[ 'listView' ][ 'from' ]
            . $this->sql_radialsearchFrom()
    ;
    $where = $this->pObj->objSqlInit->statements[ 'listView' ][ 'where' ];

    $thisIdList = implode( ',', ( array ) $withIds );
    if ( $thisIdList )
    {
      $where = $where . " AND " . $this->pObj->localTable . ".uid IN (" . $thisIdList . ")";
    }

    // #52486, 131005, dwildt, 4+
    $where = $where
            . $this->sql_radialsearchWhere()
            . $this->sql_radialsearchHaving()
    ;

    $groupBy = null;

    // #52486, 131005, dwildt, 5+
    $orderBy = $this->sql_radialsearchOrderBy();
    if ( !empty( $orderBy ) )
    {
      $orderBy = $orderBy . ',';
    }
    // #52486, 131005, dwildt, ~
    $orderBy = $orderBy
            . $this->pObj->objSqlInit->statements[ 'listView' ][ 'orderBy' ]
    ;


    // Don't limit the rows (we have a list of ids!)
    $limit = null;

    // DRS
    if ( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'UNION isn\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS
    // SQL query
    $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery
            (
            $select, $from, $where, $groupBy, $orderBy, $limit, $uidIndexField = ""
    );
    // SQL query
    // #56329, 140226, dwildt, 1+
    $query = $this->queryWiAndFilter( $query, $limit );

    // Execute query
    $promptOptimise = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
            'Don\'t use the query in a localised context.';
    $debugTrailLevel = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
    // Execute query
//$this->pObj->dev_var_dump( str_replace( '\'', '"', $query) , $arr_return );
    return $arr_return;
  }

  /**
   * sql_selectLocalised( ) : If local table has language overlay fields or
   *                          is localised, fields for language controlling
   *                          are added
   *
   * @param    string        $select : Current select
   * @return    string        $select : Select with fields for localisation
   * @version 3.9.13
   * @since   3.9.12
   */
  private function sql_selectLocalised( $select )
  {
    // SWITCH $int_localisation_mode
    switch ( $this->pObj->objLocalise->get_localisationMode() )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        if ( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
        {
          $prompt = 'SELECT doens\'t need to be localised.';
          t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        // RETURN : nothing to do
        return $select;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        // Follow the workflow
        break;
      default:
        // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
    // SWITCH $int_localisation_mode
    ////////////////////////////////////////////////////////////////////
    //
      // Add localisation fields
// #46062
    //$arr_addedTableFields = array( );
    // Loop through all used tables
    $arr_addedTableFields = array();
    foreach ( array_keys( $this->pObj->arr_realTables_arrFields ) as $table )
    {
      $arr_result = $this->pObj->objLocalise->localisationFields_select( $table );
      // Get the and SELECT statement with aliases
      if ( $arr_result[ 'wiAlias' ] )
      {
        $arr_localSelect[] = $arr_result[ 'wiAlias' ];
      }
      // Get all added table.fields
      if ( is_array( $arr_result[ 'addedFields' ] ) )
      {
        $arr_addedTableFields = array_merge
                (
                ( array ) $arr_addedTableFields, $arr_result[ 'addedFields' ]
        );
      }
      unset( $arr_result );
    }
    // Loop through all used tables
    // Build the SELECT statement
//$this->pObj->dev_var_dump( $select );
    $str_localSelect = implode( ', ', ( array ) $arr_localSelect );
    if ( $str_localSelect )
    {
      $select = $select . ', ' . $str_localSelect;
    }
//$this->pObj->dev_var_dump( $select );
    // Build the SELECT statement
    // Add localisation fields
    ////////////////////////////////////////////////////////////////////
    //
      // Add tables to the consolidation array
    // LOOP through all new table.fields
    foreach ( ( array ) $arr_addedTableFields as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      if ( !in_array( $field, $this->pObj->arr_realTables_arrFields[ $table ] ) )
      {
        // Add every new table.field to the global array arr_realTables_arrFields
        $this->pObj->arr_realTables_arrFields[ $table ][] = $field;
        // Add every new table.field to the global array consolidate
        $this->pObj->arrConsolidate[ 'addedTableFields' ][] = $tableField;
      }
    }
    // LOOP through all new table.fields
    // Add tables to the consolidation array
    // Check array for non unique elements
    $testArray = explode( ',', $select );
    $this->pObj->objZz->zz_devPromptArrayNonUnique( $testArray, __METHOD__, __LINE__ );

    return $select;
  }

  /**
   * sql_radialsearchFrom( )  :
   *
   * @return	string
   * @internal    #52486
   * @access  private
   * @version 4.7.0
   * @since   4.7.0
   */
  private function sql_radialsearchFrom()
  {
    return $this->objFilterRadialsearch->andFrom();
  }

  /**
   * sql_radialsearchHaving( )  :
   *
   * @return	string
   * @internal    #52486
   * @access  private
   * @version 4.7.0
   * @since   4.7.0
   */
  private function sql_radialsearchHaving()
  {
    return $this->objFilterRadialsearch->andHaving();
  }

  /**
   * sql_radialsearchOrderBy( )  :
   *
   * @return	string
   * @internal    #52486
   * @access  private
   * @version 4.7.0
   * @since   4.7.0
   */
  private function sql_radialsearchOrderBy()
  {
    return $this->objFilterRadialsearch->andOrderBy();
  }

  /**
   * sql_radialsearchSelect( )  :
   *
   * @return	string
   * @internal    #52486
   * @access  private
   * @version 7.0.6
   * @since   4.7.0
   */
  private function sql_radialsearchSelect()
  {
    //  #i0154, 150409, dwildt, 5+
    // RETURN : There isn't any radialsearch sword
    if ( !$this->objFilterRadialsearch->getSword() )
    {
      return NULL;
    }

    $this->pObj->csvSelectWoFunc = $this->pObj->csvSelectWoFunc
            . ', distance'
    ;
    return $this->objFilterRadialsearch->andSelect();
  }

  /**
   * sql_radialsearchWhere( )  :
   *
   * @return	string
   * @internal    #52486
   * @access  private
   * @version 4.7.0
   * @since   4.7.0
   */
  private function sql_radialsearchWhere()
  {
    return $this->objFilterRadialsearch->andWhere();
  }

  /*   * *********************************************
   *
   * Subparts
   *
   * ******************************************** */

  /**
   * subpart_setSearchbox( ): Get the searchform. Part of content is generated
   *                          by the template class. Replace filter marker.
   *
   * @return    array        $arr_return : Error message in case of an error
   * @version 6.0.0
   * @since 1.0.0
   */
  private function subpart_setSearchbox()
  {
    // #61594, 140915, 1-
    //$this->content = $this->pObj->objTemplate->tmplSearchBox( $this->content );
    // #61594, 140915, 1+
    $this->content = $this->objSearch->searchform( $this->content );
    $arr_return = $this->subpart_setSearchboxFilter();

    return $arr_return;
  }

  /**
   * subpart_setSearchboxFilter( ): Get filter values and then replace filter marker with filter content.
   *
   * @return    array        $arr_return: Error message in case of an error
   * @version 3.9.8
   * @since 1.0.0
   */
  private function subpart_setSearchboxFilter()
  {
    // Default return value
    $arr_return = array();

    // Get filter
    // #43627, 121205, dwildt, 1+
    $this->pObj->str_template_raw = $this->content;
    $arr_return = $this->pObj->objFltr4x->get();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    $filter = $arr_return[ 'data' ][ 'filter' ];
    // Get filter
    // RETURN : there isn't any filter
    if ( empty( $filter ) )
    {
      return $arr_return;
    }
    // RETURN : there isn't any filter
    // Get the searchform content
    $searchform = $this->pObj->cObj->getSubpart( $this->content, '###SEARCHFORM###' );
    // Replace filter marker with filter content
//var_dump(__METHOD__, __LINE__, $this->content, $searchform, $filter );
    $searchform = $this->pObj->cObj->substituteMarkerArray( $searchform, $filter );
    // Add the subparts marker, because another method ( the search template ) need this subpart marker
    $searchform = '<!-- ###SEARCHFORM### begin -->' . PHP_EOL .
            $searchform . '<!-- ###SEARCHFORM### end -->' . PHP_EOL;
    // Update the searchform in the whole content
    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, '###SEARCHFORM###', $searchform, true );

    return $arr_return;
  }

  /**
   * subpart_setIndexBrowser( ):  Replaces the indexbrowser subpart in the current content
   *                              with the content from ->get_indexBrowser( )
   *
   * @return    array        $arr_return : Contains an error message in case of an error
   * @version 3.9.12
   * @since 1.0.0
   */
  private function subpart_setIndexBrowser()
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'subpart_setIndexBrowser begin' );

    $arr_return = $this->pObj->objNaviIndexBrowser->get( $this->content );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }

    $content = $arr_return[ 'data' ][ 'content' ];
    $marker = $this->pObj->objNaviIndexBrowser->getMarkerIndexBrowser();
    $this->content = $this->pObj->cObj->substituteSubpart( $this->content, $marker, $content, true );

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'subpart_setIndexBrowser end' );
    return;
  }

  /**
   * subpart_setModeSelector( ):  Replaces the indexbrowser subpart in the current content
   *                              with the content from ->get_indexBrowser( )
   *
   * @return    array        $arr_return : Contains an error message in case of an error
   * @version 3.9.12
   * @since 1.0.0
   */
  private function subpart_setModeSelector()
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'begin' );

    // Get the mode selector content
    $arr_return = $this->pObj->objNaviModeSelector->get( $this->content );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      $this->content = $this->pObj->cObj->substituteSubpart
              (
              $this->content, '###MODESELECTOR###', null, true
      );
      return $arr_return;
    }
    // Get the mode selector content
    $content = $arr_return[ 'data' ][ 'content' ];

    if ( empty( $content ) )
    {
      $this->content = $this->pObj->cObj->substituteSubpart
              (
              $this->content, '###MODESELECTOR###', null, true
      );
      return;
    }

    // Set the marker array
    $markerArray = $this->pObj->objWrapper4x->constant_markers();
    $markerArray[ '###MODE###' ] = $this->mode;
    $markerArray[ '###VIEW###' ] = $this->view;
    // Set the marker array

    $modeSelector = $this->pObj->cObj->getSubpart( $this->content, '###MODESELECTOR###' );
    $modeSelector = $this->pObj->cObj->substituteMarkerArray( $modeSelector, $markerArray );
    $modeSelector = $this->pObj->cObj->substituteSubpart
            (
            $modeSelector, '###MODESELECTORTABS###', $content, true
    );
    $this->content = $this->pObj->cObj->substituteSubpart
            (
            $this->content, '###MODESELECTOR###', $modeSelector, true
    );

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
    return;
  }

  /**
   * subpart_setPageBrowser( ):  Replaces the pagebrowser subpart in the current content
   *                              with the content from ->objNaviPageBrowser->get( )
   *
   * @return    array        $arr_return : Contains an error message in case of an error
   * @version 3.9.12
   * @since 1.0.0
   */
  private function subpart_setPageBrowser()
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'begin' );

    // Get the page browser content
    $arr_return = $this->pObj->objNaviPageBrowser->get( $this->content );
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // Get the page browser content
    // #i0066, 140715, dwildt 5+
    if ( empty( $arr_return[ 'data' ][ 'content' ] ) )
    {
      $this->content = $this->pObj->cObj->substituteSubpart
              (
              $this->content, '###PAGEBROWSERTOP###', null, true
      );
//var_dump(__METHOD__, __LINE__, $arr_return, $markerArray, $subpart, $pageBrowser);
      return;
    }

    // Set marker the array
    $markerArray = $this->pObj->objWrapper4x->constant_markers();
    $markerArray[ '###RESULT_AND_ITEMS###' ] = $arr_return[ 'data' ][ 'content' ];
    $markerArray[ '###MODE###' ] = $this->mode;
    $markerArray[ '###VIEW###' ] = $this->view;
    // Set marker the array
    // Replace markers in the current content
    // #i0083, 141006, dwildt, 6~
    $subpart = $this->pObj->cObj->getSubpart( $this->content, '###PAGEBROWSERTOP###' );
    $pageBrowser = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $this->content = $this->pObj->cObj->substituteSubpart
            (
            $this->content, '###PAGEBROWSERTOP###', $pageBrowser, true
    );
    // #i0083, 141006, dwildt, 6+
    $subpart = $this->pObj->cObj->getSubpart( $this->content, '###PAGEBROWSERBOTTOM###' );
    $pageBrowser = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $this->content = $this->pObj->cObj->substituteSubpart
            (
            $this->content, '###PAGEBROWSERBOTTOM###', $pageBrowser, true
    );
    // Replace markers in the current content
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'end' );
    return;
  }

  /*   * *********************************************
   *
   * Hooks
   *
   * ******************************************** */

  /**
   * hook_afterConsolidatetRows( ): Implement the hook rows_filter_values
   *
   * @return    void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function hook_afterConsolidatetRows()
  {
    // Hook isn't used
    if ( !is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_filter_values' ] ) )
    {
      // RETURN without any prompt to DRS
      if ( !$this->pObj->b_drs_hooks )
      {
        return;
      }
      // RETURN with a prompt to DRS
      $prompt = 'Any third party extension doesn\'t use the HOOK rows_filter_values.';
      t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
      t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
      return;
    }

    // Implement the hook
    $_params = array( 'pObj' => &$this );
    foreach ( ( array ) $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_filter_values' ] as $_funcRef )
    {
      t3lib_div::callUserFunction( $_funcRef, $_params, $this );
    }

    // RETURN without any prompt to DRS
    if ( !$this->pObj->b_drs_hooks )
    {
      return;
    }

    // RETURN with a prompt to DRS
    $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_filter_values' ] );
    $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_filter_values' ] );
    $csv_ext = implode( ',', $arr_ext );
    if ( $i_extensions == 1 )
    {
      $prompt = 'The third party extension ' . $csv_ext . ' uses the HOOK rows_filter_values.';
      t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'In case of errors or strange behaviour please check this extension!';
      t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
    }
    if ( $i_extensions > 1 )
    {
      $prompt = 'The third party extensions ' . $csv_ext . ' use the HOOK rows_filter_values.';
      t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'In case of errors or strange behaviour please check this extenions!';
      t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
    }
    return;
  }

  /*   * *********************************************
   *
   * ZZ
   *
   * ******************************************** */

  /**
   * zz_drsFirstRow( ): Prompt to devLog the first row
   *
   * @return    void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function zz_drsFirstRow()
  {
    if ( !$this->pObj->b_drs_sql )
    {
      return;
    }

    if ( count( ( array ) $this->pObj->rows ) <= 0 )
    {
      return;
    }

    reset( $this->pObj->rows );
    $firstKey = key( $this->pObj->rows );
    $firstRow = $this->pObj->rows[ $firstKey ];

    $prompt = 'Result of the first row: ' . PHP_EOL;
    $prompt = $prompt . var_export( $firstRow, true );
    t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
  }

  /**
   * zz_indexBrowserIsLocalised( ) : Method returns true, if the a tab of the index
   *                                 browser is selected and the index browser tableField
   *                                 is localised.
   *
   * @return    boolean        $tableFieldIsLocalised : true or false
   * @version 3.9.13
   * @since   3.9.13
   */
  private function zz_indexBrowserIsLocalised()
  {
    // RETURN false : there isn't any FIND IN SET for the current tab attributes
    if ( !$this->pObj->objNaviIndexBrowser->findInSetForCurrTab )
    {
      return false;
    }
    // RETURN false : there isn't any FIND IN SET for the current tab attributes
    // Get the tableField of the index browser
    $tableField = $this->pObj->objNaviIndexBrowser->indexBrowserTableField;

    // Get localised status of the tableField
    $tableFieldIsLocalised = $this->pObj->objLocalise->zz_tablefieldIsLocalised( $tableField );

    // RETURN the localised status
    return $tableFieldIsLocalised;
  }

  /**
   * zz_orderByValueIsLocalised( )  : Method returns true, if the first value in the ORDER BY
   *                               clause is localised.
   *
   * @return    boolean        $tableFieldIsLocalised : true or false
   * @version 3.9.13
   * @since   3.9.13
   */
  private function zz_orderByValueIsLocalised()
  {
    // RETURN : ORDER BY is randomised
    if ( $this->conf_view[ 'random' ] == 1 )
    {
      return false;
    }
    // RETURN : ORDER BY is randomised
    // Get ORDER BY
    $orderBy = $this->pObj->objSqlInit->statements[ 'listView' ][ 'orderBy' ];
    $arr_orderBy = $this->pObj->objZz->getCSVasArray( $orderBy );
    // Get the first tableField
    list( $tableField ) = explode( ' ', $arr_orderBy[ 0 ] );

    // Get localised status of the tableField
    $tableFieldIsLocalised = $this->pObj->objLocalise->zz_tablefieldIsLocalised( $tableField );

    // DRS
    if ( $tableFieldIsLocalised )
    {
      if ( $this->pObj->b_drs_warn )
      {
        $prompt = 'ORDER BY ' . $tableField . ' ... has unwanted effects!';
        t3lib_div::devlog( '[WARN/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = $tableField . ' is translated. The sequence of rows will be: [ ordered [ordered rows of foreign
                  language limit 0,20] + [oderded rows of default language limit the rest]], but not [ordered rows limit 0,20]. Sorry, but this is a
                  need of performance.';
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
    }
    // DRS
    // RETURN the localised status
    return $tableFieldIsLocalised;
  }

  /**
   * zz_setGlobalArrLinkToSingle( ): Set the global var $arrLinkToSingle
   *
   * @return    void
   * @version 5.0.0
   * @since 1.0.0
   * @internal #59669
   */
  private function zz_setGlobalArrLinkToSingle()
  {
    $tableFields = $this->zz_setGlobalArrLinkToSingleTableFields();

    $this->pObj->arrLinkToSingle = array();
    foreach ( ( array ) $tableFields as $tableField )
    {
      $this->pObj->arrLinkToSingle[] = $tableField;
    }

    // #59669, 140627, dwildt, -: should done by $this->zz_setGlobalArrLinkToSingleTableFields(). But isn't tested
//    // Set the global $arrLinkToSingle
//    // Replace aliases in case of aliases
//    if ( is_array( $conf_view[ 'aliases.' ][ 'tables.' ] ) )
//    {
//      foreach ( $this->pObj->arrLinkToSingle as $i_key => $str_tablefield )
//      {
//        $this->pObj->arrLinkToSingle[ $i_key ] = $this->pObj->objSqlFun_3x->get_sql_alias_before( $str_tablefield );
//      }
//      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun_3x->replace_tablealias( $this->pObj->arrLinkToSingle );
//    }
    // RETURN : no DRS prompt needed
    if ( !$this->pObj->b_drs_sql )
    {
      return;
    }

    // DRS prompt
    $csvList = implode( ', ', $this->pObj->arrLinkToSingle );
    $prompt = 'Fields which will get a link to a single view: ' . $csvList . '.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'If you want to configure the field list, please use ' .
            $this->conf_path . 'csvLinkToSingleView.';
    t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
  }

  /**
   * zz_setGlobalArrLinkToSingleTableFields( ): Returns the table.fields, which should linked automatically
   *
   * @return    array   $tableFields  :
   * @version 5.0.0
   * @since 5.0.0
   * @internal #59669
   */
  private function zz_setGlobalArrLinkToSingleTableFields()
  {
    // Get linkToSingle CSV list from TypoScript
    $csvLinkToSingle = $this->conf_view[ 'csvLinkToSingleView' ];

    // RETURN : list of table.fields
    if ( $csvLinkToSingle )
    {
      $csvLinkToSingle = str_replace( ' ', null, $csvLinkToSingle );
      $tableFields = explode( ',', $csvLinkToSingle );
      return $tableFields;
    }

    // Get table.fields from SELECT statement
    $statements = $this->pObj->objSqlAut->get_statements();
    $csvSelect = $statements[ 'data' ][ 'select' ];
    $csvSelect = $this->pObj->objSqlFun->getStatementWoAscDesc( $csvSelect );
    $tableFields = $this->pObj->objZz->getCSVasArray( $csvSelect );

    // RETURN : list of table.fields, no DRS prompt needed
    if ( !$this->pObj->b_drs_sql )
    {
      return $tableFields;
    }

    // DRS prompt
    $prompt = $this->conf_path . ' hasn\'t any linkToSingleView.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'If you want a link to a single view, please configure ' .
            $this->conf_path . 'csvLinkToSingleView.';
    t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );

    // RETURN : list of table.fields
    return $tableFields;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_viewlist.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_viewlist.php' ]);
}
?>
