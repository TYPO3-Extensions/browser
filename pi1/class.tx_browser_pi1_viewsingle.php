<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_viewsingle bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 * @version 5.0.0
 * @since 1.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   53: class tx_browser_pi1_viewsingle
 *   72:     function __construct($parentObj)
 *
 *              SECTION: Building the views
 *  113:     function singleView( )
 *
 *              SECTION: Helper
 *  754:     public function displayThePlugin( )
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_viewsingle
{

  // Variables set by the pObj (by class.tx_browser_pi1.php)
  // [Array] The current TypoScript configuration array
  public $conf = false;
  // [Integer] The current mode (from modeselector)
  public $mode = false;
  // [String] 'list' or 'single': The current view
  public $view = false;
  // [Array] The TypoScript configuration array of the current view
  public $conf_view = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  public $conf_path = false;
  // Variables set by the pObj (by class.tx_browser_pi1.php)

  private $arr_select;
  // Array with the fields of the SQL result
  private $arr_orderBy;
  // Array with fields from orderBy from TS
  private $arr_rmFields;

  // Array with fields from functions.clean_up.csvTableFields from TS

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
  }

  /**
   * confDisplayType() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function confDisplay()
  {
    $conf = $this->conf;
    $mode = $this->mode;

    $this->pObj->lDisplayType = 'displaySingle.';
    if ( is_array( $this->conf_view[ $mode . '.' ][ $this->pObj->lDisplayType ][ 'display.' ] ) )
    {
      $this->pObj->lDisplay = $this->conf_view[ $mode . '.' ][ $this->pObj->lDisplayType ][ 'display.' ];
    }
    else
    {
      $this->pObj->lDisplay = $conf[ $this->pObj->lDisplayType ][ 'display.' ];
    }
  }

  /**
   * getMode() : Display a single item from the database
   *
   * @param	string		$template: HTML template with TYPO3 subparts and markers
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getMode()
  {
    $mode = $this->mode;
    $conf_view = $this->conf_view;


    $sumOfModes = count( $conf_view );

    // RETURN false : there isn't any single view
    if ( $sumOfModes < 1 )
    {
      // RETURN : no DRS prompt needed
      if ( !$this->pObj->b_drs_error )
      {
        return false;
      }

      // RETURN : DRS prompt
      $this->getModeDRSnoView();
      return false;
    }

    // mode isn't proper. Move it to 1.
    if ( $mode > $sumOfModes )
    {
      $mode = 1;
    }

    return $mode;
  }

  /**
   * getModeDRSnoView() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getModeDRSnoView()
  {
    $view = $this->view;

    $prompt = 'There is no ' . $view . ' view.';
    t3lib_div::devlog( '[ERROR/DRS] ' . $prompt, $this->pObj->extKey, 3 );
    $prompt = 'Did you included the static template from this extensions?';
    t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
    $prompt = 'Did you configure plugin.' . $this->pObj->prefixId . '.views.' . $view . '?';
    t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
    $prompt = 'ABORTED';
    t3lib_div::devLog( '[WARN/DRS] ' . $prompt, $this->pObj->extKey, 2 );
  }

  /**
   * getRows() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRows( $statements )
  {
    $arr_result = $this->getRowsSql( $statements );
    if ( $arr_result[ 'error' ] )
    {
      return $arr_result;
    }

    $res = $arr_result[ 'res' ];
    $rows = $this->getRowsAliases( $res );
    $GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );
    $rows = $this->getRowsSynonyms( $rows );
    $rows = $this->getRowsLocalised( $rows );
    $arr_result[ 'rows' ] = $rows;
    return $arr_result;
  }

  /**
   * getRowsAliases() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsAliases( $res )
  {
    $arr_table_realnames = $this->conf_view[ 'aliases.' ][ 'tables.' ];
    switch ( true )
    {
      case(is_array( $arr_table_realnames )):
        $rows = $this->getRowsAliasesWith( $res );
        return $rows;
      case(!is_array( $arr_table_realnames )):
      default:
        $rows = $this->getRowsAliasesWithout( $res );
        return $rows;
    }
  }

  /**
   * getRowsAliasesWith() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsAliasesWith( $res )
  {
    $rows = array();
    $arr_table_realnames = $this->conf_view[ 'aliases.' ][ 'tables.' ];

    $i_row = 0;
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
//var_dump( __METHOD__, __LINE__, $row );
      //foreach ( $row as $str_tablealias_field => $value )
      foreach ( array_keys( $row ) as $tableAliasField )
      {
        $arr_tablealias_field = explode( '.', $tableAliasField );    // table_1.sv_name
        $str_tablealias = $arr_tablealias_field[ 0 ];                     // table_1
        $str_field = $arr_tablealias_field[ 1 ];                          // sv_name
        $str_table = $arr_table_realnames[ $str_tablealias ];             // tx_civserv_service
        $str_table_field = $str_table . '.' . $str_field;                 // tx_civserv_service.sv_name
        if ( $str_table_field == '.' )
        {
          $str_table_field = $tableAliasField;
        }
        $rows[ $i_row ][ $str_table_field ] = $row[ $tableAliasField ];
      }
      $i_row++;
    }

    return $rows;
  }

  /**
   * getRowsAliasesWithout() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsAliasesWithout( $res )
  {
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      $rows[] = $row;
    }
    return $rows;
  }

  /**
   * getRowsConsolidated() :
   *
   * @param	array     $rows
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsConsolidated( $rows )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'Begin' );

    switch ( true )
    {
      case($this->pObj->b_sql_manual):
        $rows = $this->getRowsConsolidatedSqlManualMode( $rows );
      case(!$this->pObj->b_sql_manual):
      default:
        $rows = $this->getRowsConsolidatedSqlAutoMode( $rows );
    }

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );

    return $rows;
  }

  /**
   * getRowsConsolidatedSqlAutoMode() :
   *
   * @param	array     $rows
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsConsolidatedSqlAutoMode( $rows )
  {
    $arr_result = $this->pObj->objConsolidate->consolidate( $rows );
    $rows = $arr_result[ 'data' ][ 'rows' ];
    return $rows;
  }

  /**
   * getRowsConsolidatedSqlManualMode() :
   *
   * @param	array     $rows
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsConsolidatedSqlManualMode( $rows )
  {
    // RETURN : no DRS prompt needed
    if ( !$this->pObj->b_drs_localisation )
    {
      return $rows;
    }

    // RETURN : DRS prompt
    t3lib_div::devlog( '[INFO/SQL] Manual SQL mode: Rows didn\'t get any general consolidation.', $this->pObj->extKey, 0 );
    return $rows;
  }

  /**
   * getRowsLcalised() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsLocalised( $rows )
  {
    $rows = $this->pObj->objLocalise->consolidate_rows( $rows, $this->pObj->localTable );
    return $rows;
  }

  /**
   * getRowsSql() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsSql( $statements )
  {
    $arr_result = array(
      'error' => null,
      'res' => null
    );

    $select = $statements[ 'data' ][ 'select' ];
    $from = $statements[ 'data' ][ 'from' ];
    $where = $statements[ 'data' ][ 'where' ];
    $where = $where . $statements[ 'data' ][ 'whereLL' ];
    $orderBy = $statements[ 'data' ][ 'orderBy' ];
    unset( $statements );

    $groupBy = '';
    $orderBy = '';
    $limit = '';
    $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery( $select, $from, $where, $groupBy, $orderBy, $limit, $uidIndexField = "" );

    $res = $GLOBALS[ 'TYPO3_DB' ]->sql_query( $query );
    $error = $GLOBALS[ 'TYPO3_DB' ]->sql_error();

    if ( $error )
    {
      $this->pObj->objSqlFun_3x->query = $query;
      $this->pObj->objSqlFun_3x->error = $error;
      $arr_result[ 'error' ] = $this->pObj->objSqlFun_3x->prompt_error();
      return $arr_result;
    }

    $arr_result[ 'res' ] = $res;
    if ( !$this->pObj->b_drs_sql )
    {
      return $arr_result;
    }

    $prompt = $query;
    t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.';
    t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
    return $arr_result;
  }

  /**
   * getRowsSynonyms() :
   *
   * @param	array
   * @return	array   $rows
   * @version 5.0.0
   * @since   5.0.0
   */
  private function getRowsSynonyms( $rows )
  {
    $arr_result = $this->pObj->objSqlFun_3x->rows_with_synonyms( $rows );
    $rows = $arr_result[ 'data' ][ 'rows' ];
    return $rows;
  }

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
    if ( !is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'browser_single' ] ) )
    {
      // RETURN without any prompt to DRS
      if ( !$this->pObj->b_drs_hooks )
      {
        return;
      }
      // RETURN with a prompt to DRS
      $prompt = 'Any third party extension doesn\'t use the HOOK browser_single.';
      t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
      t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
      return;
    }

    // Implement the hook
    $_params = array( 'pObj' => &$this );
    foreach ( ( array ) $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'browser_single' ] as $_funcRef )
    {
      t3lib_div::callUserFunction( $_funcRef, $_params, $this );
    }

    // RETURN : no DRS prompt needed
    if ( !$this->pObj->b_drs_hooks )
    {
      return;
    }

    $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'browser_single' ] );
    $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'browser_single' ] );
    $csv_ext = implode( ',', $arr_ext );

    if ( $i_extensions > 1 )
    {
      t3lib_div::devlog( '[INFO/SQL] The third party extensions ' . $csv_ext . ' use the HOOK browser_single.', $this->pObj->extKey, -1 );
      t3lib_div::devlog( '[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1 );
      return;
    }

    t3lib_div::devlog( '[INFO/SQL] The third party extension ' . $csv_ext . ' uses the HOOK browser_single.', $this->pObj->extKey, -1 );
    t3lib_div::devlog( '[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1 );
    return;
  }

  /**
   * init() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function init()
  {

    $template = $this->pObj->str_template_raw;
    $template = $this->initHtmlStaticReplace( $template );
    $this->pObj->str_template_raw = $template;

    $this->initGeneralStdWrap();
    $this->initGlobalDisplaySingle();
    $this->initGlobalDisplaySingleDisplay();
  }

  /**
   * initGeneralStdWrap() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function initGeneralStdWrap()
  {
    $conf_view = $this->conf_view;

    if ( is_array( $conf_view[ 'general_stdWrap.' ] ) )
    {
      $this->pObj->conf[ 'general_stdWrap.' ] = $conf_view[ 'general_stdWrap.' ];
    }
  }

  /**
   * initGlobalDisplaySingle() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function initGlobalDisplaySingle()
  {
    $conf = $this->conf;
    $conf_view = $this->conf_view;

    if ( is_array( $conf_view[ 'displaySingle.' ] ) )
    {
      $this->pObj->lDisplaySingle = $conf_view[ 'displaySingle.' ];
      return;
    }

    $this->pObj->lDisplaySingle = $conf[ 'displaySingle.' ];
    return;
  }

  /**
   * initGlobalDisplaySingleDisplay() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function initGlobalDisplaySingleDisplay()
  {
    $conf = $this->conf;
    $conf_view = $this->conf_view;

    if ( is_array( $conf_view[ 'displaySingle.' ][ 'display.' ] ) )
    {
      $this->pObj->lDisplay = $conf_view[ 'displaySingle.' ][ 'display.' ];
      return;
    }

    $this->pObj->lDisplay = $conf[ 'displaySingle.' ][ 'display.' ];
    return;
  }

  /**
   * init() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function initHtmlStaticReplace( $template )
  {
    $template = $this->pObj->objTemplate->htmlStaticReplace( $template, $this->conf_view );
    return $template;
  }

  /**
   * initSql() :
   *
   * @return	array
   * @version 5.0.0
   * @since   5.0.0
   */
  private function initSql()
  {
    $this->pObj->objSqlInit->init();

    if ( $this->pObj->b_sql_manual )
    {
      $arr_result = $this->pObj->objSqlMan->get_queryArray();
      return $arr_result;
    }

    $arr_result = $this->pObj->objSqlAut->get_statements();
    return $arr_result;
  }

  /**
   * main() : Display a single item from the database
   *
   * @param	string		$template: HTML template with TYPO3 subparts and markers
   * @return	void
   * @version 5.0.0
   * @since   1.x
   */
  public function main()
  {
    // Set mode
    $mode = $this->getMode();
    if ( $mode < 1 )
    {
      return false;
    }

    $this->init();

    $arr_result = $this->initSql();
    if ( $arr_result[ 'error' ][ 'status' ] )
    {
      $template = $arr_result[ 'error' ][ 'header' ] . $arr_result[ 'error' ][ 'prompt' ];
      return $template;
    }

    $arr_result = $this->getRows( $arr_result );
    if ( $arr_result[ 'error' ] )
    {
      return $arr_result[ 'error' ];
    }
    $rows = $arr_result[ 'rows' ];
    unset( $arr_result );

    $this->pObj->rows = $rows;
    // #59669, dwildt, 1+
    $this->pObj->rowsLocalised = $rows;

    $rows = $this->getRowsConsolidated( $rows );
    $this->pObj->rows = $rows;

    $this->pObj->objConsolidate->children_relation();
    $rows = $this->pObj->rows;

    $this->hook_afterConsolidatetRows();

//    var_dump( __METHOD__, __LINE__, $rows );
//    die( ' :( ' );
    // Prompt status of rows to the DRS
    $this->mainRowsDRS( $rows );

    $template = $this->template( $rows );

    $this->mainStatistics();
//    var_dump( __METHOD__, __LINE__, $template );
//die( ':(' );

    return $template;
  }

  /**
   * mainRowsDRS() :
   *
   * @param	array
   * @return	void
   * @version 5.0.0
   * @since   1.x
   */
  private function mainRowsDRS( $rows )
  {
    if ( $this->mainRowsDRSwarn( $rows ) )
    {
      return;
    }
    $this->mainRowsDRSinfo( $rows );
  }

  /**
   * mainRowsDRSinfo() :
   *
   * @param	array
   * @return	void
   * @version 5.0.0
   * @since   1.x
   */
  private function mainRowsDRSinfo( $rows )
  {
    if ( !$this->pObj->b_drs_sql )
    {
      return;
    }

    if ( empty( $rows ) )
    {
      return;
    }

    switch ( true )
    {
      case(count( $rows ) > 1):
        t3lib_div::devlog( '[INFO/SQL] Result: ' . count( $rows ) . ' records.<br />You must have 1:n relations.', $this->pObj->extKey, 0 );
        break;
      case(count( $rows ) == 1):
      default:
        t3lib_div::devlog( '[INFO/SQL] Result: ' . count( $rows ) . ' records.<br />You must have 1:n relations.', $this->pObj->extKey, 0 );
        break;
    }

    t3lib_div::devlog( '[INFO/SQL] Result of the row is:', $this->pObj->extKey, 0 );
    reset( $rows );
    $firstKey = key( $rows );
    foreach ( $rows[ $firstKey ] as $key => $value )
    {
      $value = htmlspecialchars( $value );
      if ( strlen( $value ) > $this->pObj->i_drs_max_sql_result_len )
      {
        $value = substr( $value, 0, $this->pObj->i_drs_max_sql_result_len ) . ' ...';
      }
      t3lib_div::devlog( '[INFO/SQL] [' . $key . ']: ' . $value, $this->pObj->extKey, 0 );
    }
  }

  /**
   * mainRowsDRSwarn() :
   *
   * @param	array
   * @return	void
   * @version 5.0.0
   * @since   1.x
   */
  private function mainRowsDRSwarn( $rows )
  {
    if ( !empty( $rows ) )
    {
      return false;
    }

    if ( !$this->pObj->b_drs_warn )
    {
      return true;
    }

    t3lib_div::devlog( '[WARN/SQL] Result is 0 rows! But query is OK.', $this->pObj->extKey, 2 );
    return true;
  }

  /**
   * mainStatistics() :
   *
   * @return	void
   * @version 5.0.0
   * @since   5.0.0
   */
  private function mainStatistics()
  {
    $this->pObj->objStat->countViewSingleRecord();
  }

  /**
   * template() :
   *
   * @param	 array		$rows     :
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   1.x
   */
  private function template( $rows )
  {
    $this->confDisplay();

    $template = $this->pObj->str_template_raw;
    $templateMarker = $this->pObj->lDisplaySingle[ 'templateMarker' ];
    $template = $this->pObj->cObj->getSubpart( $template, $templateMarker );
    $template = $this->templateModeSelector( $template );
    $template = $this->templateRows( $template, $rows );
    $template = $this->templateNaviRecordBrowser( $template );
    return $template;
  }

  /**
   * templateNaviRecordBrowser() :
   *
   * @param	 array		$rows     :
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   1.x
   */
  private function templateNaviRecordBrowser( $template )
  {
    $template = $this->pObj->objNaviRecordBrowser->recordbrowser_get( $template );
    return $template;
  }

  /**
   * templateRows() :
   *
   * @param	 string  $template : HTML template with TYPO3 subparts and markers
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   5.0.0
   */
  private function templateRows( $template, $rows )
  {
    switch ( $this->templateTTCmode() )
    {
      case( true ):
        $template = $this->templateRowsTTC( $template, $rows );
        break;
      case( false ):
      default:
        $template = $this->templateRowsSingleView( $template, $rows );
        break;
    }
    return $template;
  }

  /**
   * templateRowsSingleView() :
   *
   * @param	 string  $template : HTML template with TYPO3 subparts and markers
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   5.0.0
   */
  private function templateRowsSingleView( $template, $rows )
  {
    $template = $this->pObj->objTemplate->tmplSingleview( $template, $rows );
    return $template;
  }

  /**
   * templateRowsTTC() :
   *
   * @param	 string  $template : HTML template with TYPO3 subparts and markers
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   1.x
   */
  private function templateRowsTTC( $template, $rows )
  {
    $arr_result = $this->pObj->objTTContainer->main( $rows );

    if ( !$arr_result[ 'error' ][ 'status' ] )
    {
      $template = $arr_result[ 'data' ][ 'template' ];
      return $template;
    }

    $template = $arr_result[ 'error' ][ 'header' ] . $arr_result[ 'error' ][ 'prompt' ];
    return $template;
  }

  /**
   * templateTTCmode() :
   *
   * @return	boolean
   * @version 5.0.0
   * @since   5.0.0
   */
  private function templateTTCmode()
  {
    $mode = $this->mode;

    $ttcMode = false;

    foreach ( ( array ) $this->conf_view[ $mode . '.' ] as $ts_value )
    {
      if ( $ts_value == 'TT_CONTAINER' )
      {
        $ttcMode = true;
        break;
      }
    }

    if ( !$this->pObj->b_drs_ttc )
    {
      return $ttcMode;
    }

    if ( $ttcMode )
    {
      t3lib_div::devlog( '[INFO/TTC] We have one TT_CONTAINER at least.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TTC] We don\'t process the default TypoScript Template Marker.', $this->pObj->extKey, 0 );
    }
    else
    {
      t3lib_div::devlog( '[INFO/TTC] We don\'t have any TT_CONTAINER.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TTC] We don\'t process the TypoScript Template Container (TTC).', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TTC] We process the default TypoScript Template Marker.', $this->pObj->extKey, 0 );
    }

    return $ttcMode;
  }

  /**
   * templateModeSelector() :
   *
   * @param	 array		$rows     :
   * @return	string  $template : HTML template with TYPO3 subparts and markers
   * @version 5.0.0
   * @since   1.x
   */
  private function templateModeSelector( $template )
  {
    $arr_data = array();
    $arr_data[ 'template' ] = $template;
    $arr_data[ 'arrModeItems' ] = $this->pObj->arrModeItems;
    $template = $this->pObj->objNaviModeSelector->tmplModeSelector( $arr_data );

    return $template;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_viewsingle.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_viewsingle.php' ]);
}
?>