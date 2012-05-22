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
 * The class tx_browser_pi1_viewlist bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.13
 * @since       1.0
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
  var $content = null;




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









  /***********************************************
   *
   * Building the views
   *
   **********************************************/



  /**
 * main( ): Display a search form, indexBrowser, pageBrowser and a list of records
 *
 * @return	string		$template : The processed HTML template
 * @version 3.9.8
 * @since 3.9.8
 */
  function main( )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // RETURN there isn't any list configured
    $prompt = $this->check_view( );
    if( $prompt )
    {
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $prompt;
    }
      // RETURN there isn't any list configured

      // Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
    $this->init( );

      // Get HTML content
    $this->content = $this->pObj->str_template_raw;

      // Set SQL query parts in general and statements for rows
    $arr_return = $this->pObj->objSqlInit->init( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $content;
    }
      // Set SQL query parts in general and statements for rows




      //////////////////////////////////////////////////////////////////////
      //
      // csv export versus list view

      // #29370, 110831, dwildt+
      // Get template for csv
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
          // CASE csv
          // Take the CSV template
        $this->content_setCSV( );
        break;
          // CASE csv
      default:
          // CASE no csv
          // Take the default template (the list view) and replace some subparts
        $arr_return = $this->content_setDefault( );
        if( $arr_return['error']['status'] )
        {
            // Prompt the expired time to devlog
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
          $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
          return $content;
        }
        break;
          // CASE no csv
    }
    $content = $this->content;
      // Get template for csv
      // csv export versus list view
      // #29370, 110831, dwildt+


//var_dump( __METHOD__, __LINE__,
//	$this->pObj->arrConsolidate['addedTableFields'],
//	$this->pObj->objSqlAut->statementTables,
//	$this->pObj->objSqlInit->statements,
//	$this->pObj->objFltr4x->andWhereFilter
//);
//var_dump( __METHOD__, __LINE__, $this->pObj->objSqlAut->arr_relations_mm_simple );

      // Building SQL query and get the SQL result
    $arr_return = $this->rows_sql( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $content;
    }
    $res = $arr_return['data']['res'];
      // Building SQL query and get the SQL result

      // Set rows
    $this->rows_fromSqlRes( $res );
    $rows = $this->pObj->rows;

      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Synonyms aren\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

      // Consolidate localisation
    $rows = $this->rows_consolidateLL( $rows );
      // Consolidate children
    $rows = $this->rows_consolidateChildren( $rows );
    $this->pObj->rows = $rows;

      // Implement the hook rows_filter_values
    $this->hook_afterConsolidatetRows( );
    $rows = $this->pObj->rows;

      // Order the rows
    if( ! $this->zz_orderByValueIsLocalised( ) )
    {
      $this->pObj->objMultisort->multisort_rows( );
      $rows = $this->pObj->rows;
    }

      // Ordering the children rows
    $rows = $this->pObj->objMultisort->multisort_mm_children( $rows );
    $this->pObj->rows = $rows;

      // DRS - :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Hierarchical order isn\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS - :TODO:

      // Delete fields, which were added whily runtime
    $arr_return = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields( $rows );
    $rows       = $arr_return['data']['rows'];
    unset($arr_return);
    $this->pObj->rows = $rows;

      // DRS - display first row
    $this->zz_drsFirstRow( );

      // Set the global $arrLinkToSingle
    $this->zz_setGlobalArrLinkToSingle( );



      /////////////////////////////////////////////////////////////////
      //
      // Extension pi5: +Browser Calendar

      // Will executed in case, that the Browser is extended with the Browser Calendar user Interface
    $arr_result   = $this->pObj->objCal->cal( $rows, $content );
    $bool_success = $arr_result['success'];
    if( $bool_success )
    {
      $rows         = $arr_result['rows'];
      $content     = $arr_result['template'];
      $this->pObj->objTemplate->ignore_empty_rows_rule = true;
      if ($this->pObj->b_drs_cal || $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING/CAL/UI]: +Browser Calendar User Interface is loaded.', $this->pObj->extKey, 0);
      }
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devLog('[WARN/TEMPLATING/CAL/UI]: +Browser Calendar set ignore_empty_rows_rule to true!', $this->pObj->extKey, 2);
      }
    }
    $this->pObj->rows = $rows;
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'after $this->pObj->objCal->cal( )' );
      // Extension pi5: +Browser Calendar



      /////////////////////////////////////
      //
      // record browser

    $arr_result = $this->pObj->objNavi->recordbrowser_set_session_data( $rows );
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass( $prompt );
    }
      // record browser

    $content = $this->pObj->objTemplate->tmplListview( $content, $rows );
    $this->content = $content;

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

      // RETURN content
    return $this->content;
  }




  /**
 * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
 *
 * @return	void
 * @version 3.9.8
 * @since 1.0.0
 */
  private function init( )
  {
      // Overwrite global general_stdWrap
      // #12471, 110123, dwildt+
    if( is_array( $this->conf_view['general_stdWrap.'] ) )
    {
      $this->pObj->conf['general_stdWrap.'] = $this->conf_view['general_stdWrap.'];
      $this->conf['general_stdWrap.']       = $this->pObj->conf['general_stdWrap.'];
    }
      // Overwrite global general_stdWrap

      // Get the local or global displayList
    if( is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $this->conf_view['displayList.'];
    }
    if( ! is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $this->conf['displayList.'];
    }
      // Get the local or global displayList



      // Get the local or global displayList.display
    if( is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $this->conf_view['displayList.']['display.'];
    }
    if( ! is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $this->conf['displayList.']['display.'];
    }
      // Get the local or global displayList.display
  }




  /**
 * check_view( ):
 *
 * @return	string		Error prompt in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function check_view( )
  {
    $mode = $this->mode;

      //////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any list configured

    $bool_noView = false;
    switch( true )
    {
      case( empty( $mode ) ):
        $bool_noView = true;
        break;
      case( ! is_array( $this->conf_view ) ):
        $bool_noView = true;
        break;
    }
    if( $bool_noView )
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










 /***********************************************
  *
  * Content / Template
  *
  **********************************************/



/**
 * content_setCSV( ): Sets content to CSV template
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.9
 */
  private function content_setCSV( )
  {
      // DRS
    if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
    {
      t3lib_div::devlog('[INFO/TEMPLATING+EXPORT] ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
    }
      // DRS

      // Get the label of the subpart marker for the csv content
    $str_marker     = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
      // Get the csv content file
    $template_path  = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
      // Set the csv content
    $this->content  = $this->pObj->cObj->fileResource( $template_path );

      // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker, __METHOD__, __LINE__ );
  }



/**
 * content_setDefault( ): Sets content to list view template.
 *                        Takes care of the subparts
 *                        * searchbox
 *                        * indexBrowser
 *                        *
 *
 * @return	array		$arr_return: Contains an error message in case of an error
 * @version 3.9.12
 * @since   3.9.9
 */
  private function content_setDefault( )
  {
      // HTML template subpart for the list view
    $str_marker     = $this->pObj->lDisplayList['templateMarker'];
      // Set the list view content
    $this->content  = $this->pObj->cObj->getSubpart( $this->content, $str_marker );

      // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker, __METHOD__, __LINE__ );

      // Set search box and filter
    $arr_return = $this->subpart_setSearchbox( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set search box and filter

      // Set index browser
    $arr_return = $this->subpart_setIndexBrowser( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set index browser

      // Set page browser
    $arr_return = $this->subpart_setPageBrowser( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set page browser

      // Set mode selector
    $arr_return = $this->subpart_setModeSelector( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set mode selector

    return;
  }



/**
 * content_dieIfEmpty( ): If content is empty, the methods will die the workflow
 *                        with a qualified prompt.
 *
 * @param	string		$marker:  subpart marker
 * @param	string		$method:  calling method
 * @param	string		$line:    line of calling method
 * @return	void		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function content_dieIfEmpty( $marker, $method, $line )
  {
    if( empty( $this->content ) )
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
          </p>
          <h2>
            DE: Subpart fehlt
          </h2>
          <p>
            Deutsch: Dem aktuellen HTML-Template fehlt der Subpart \'' . $marker . '\'.<br />
            Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
          </p>
          <p>
            ' . $method . ' (' . $line . ')
          </p>
        </div>';
      die( $prompt );
    }
  }









  /***********************************************
  *
  * SQL
  *
  **********************************************/



  /**
 * rows_consolidateLL( ): Consolidate localisation. Returns consolidated rows.
 *
 * @param	array		$rows  : consolidated rows
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function rows_consolidateLL( $rows )
  {
      // RETURN : SQL manual mode
    if( $this->pObj->b_sql_manual )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Manual SQL mode: Rows didn\'t get any localisation consolidation.';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
      }
      return $rows;
    }
      // RETURN : SQL manual mode

      // Consolidate Localisation
    $rows = $this->pObj->objLocalise3x->consolidate_rows( $rows, $this->pObj->localTable );

    return $rows;
  }



  /**
 * rows_consolidateChildren( ): Consolidate children, returns consolidated rows.
 *
 * @param	array		$rows  : consolidated rows
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function rows_consolidateChildren( $rows )
  {
      // RETURN : SQL manual mode
    if( $this->pObj->b_sql_manual )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Manual SQL mode: Rows didn\'t get any consolidation for children.';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
      }
      return $rows;
    }
      // RETURN : SQL manual mode

      // Consolidate children
    $arr_return       = $this->pObj->objConsolidate->consolidate( $rows );
    $rows             = $arr_return['data']['rows'];
//    $int_rows_wo_cons = $arr_return['data']['rows_wo_cons'];
//    $int_rows_wi_cons = $arr_return['data']['rows_wi_cons'];

    return $rows;

  }



  /**
 * rows_sql( ): Move SQL result to rows and set the global var $rows.
 *
 * @param	array		$res  : current SQL result
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function rows_fromSqlRes( $res )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'start' );

    $conf_view = $this->conf_view;

      // Get aliases
    $arr_table_realnames = $conf_view['aliases.']['tables.'];

      // SWITCH case aliases
    switch( true )
    {
      case( is_array( $arr_table_realnames ) ):
        $rows = $this->rows_getCaseAliases( $res );
        break;
      case( ! is_array( $arr_table_realnames ) ):
      default:
        $rows = $this->rows_getDefault( $res );
        break;
    }
      // SWITCH case aliases

      // SQL Free Result
    $GLOBALS['TYPO3_DB']->sql_free_result( $this->res );

      // Set global var
    $this->pObj->rows = $rows;

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'stop' );
      // Building $rows
  }



  /**
 * rows_getCaseAliases( ):  Move SQL result to rows, depending on
 *                          values from aliases.tables
 *
 * @param	array		$res  : current SQL result
 * @return	array		$rows : the rows
 * @version 3.9.12
 * @since   3.9.12
 */
  private function rows_getCaseAliases( $res )
  {
    $rows                 = array( );
    $conf_view            = $this->conf_view;
    $arr_table_realnames  = $conf_view['aliases.']['tables.'];

    $i_row = 0;
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      foreach( $row as $str_tablealias_field => $value )
      {
        $arr_tablealias_field = explode( '.', $str_tablealias_field ); // table_1.sv_name
        $str_tablealias       = $arr_tablealias_field[0];              // table_1
        $str_field            = $arr_tablealias_field[1];              // sv_name
        $str_table            = $arr_table_realnames[$str_tablealias]; // tx_civserv_service
        $str_table_field      = $str_table . '.' . $str_field;         // tx_civserv_service.sv_name
        if( $str_table_field == '.' )
        {
          $str_table_field = $str_tablealias_field;
        }
        $rows[$i_row][$str_table_field] = $row[$str_tablealias_field];
      }
      $i_row++;
    }

    return $rows;
  }



  /**
 * rows_getDefault( ): Move SQL result to rows
 *
 * @param	array		$res  : current SQL result
 * @return	array		$rows : the rows
 * @version 3.9.12
 * @since   3.9.12
 */
  private function rows_getDefault( $res )
  {
    $rows = array( );

    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[] = $row;
    }

    return $rows;
  }



  /**
 * rows_sql( ): Building the SQL query, returns the SQL result.
 *
 * @return	array		$arr_return: Contains the SQL res or an error message
 * @version 3.9.13
 * @since   3.9.12
 */
  private function rows_sql( )
  {
      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Update the manual: ORDER BY has unwanted effects, if ORDER BY value is localised.';
      $prompt = $prompt . ' The sequence of rows will be: [ ordered [ordered rows of foreign
                language limit 0,20] + [oderded rows of default language limit the rest]], but not 
                [ordered rows 0,20]. Sorry, but this is a need of performance.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

    switch( $this->pObj->objLocalise->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        $arr_return = $this->rows_sqlLanguageDefault( );
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        $arr_return = $this->rows_sqlLanguageFirstDefaultOrFirstTranslated( );
        break;
      default:
          // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }

    return $arr_return;
  }



 /**
  * rows_sqlIdsOfRowsWiTranslationOnly( ) : Get the ids of default or translated rows
  *
  * @return	array		$arr_return: Contains the ids
  * @version 3.9.13
  * @since   3.9.13
  */
  private function rows_sqlIdsOfRowsWiTranslationOnly( )
  {
      // Get ids of records, which match the rules and have a translation for the current language
      // Get all ids
    $withAllIds = array( );
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $withAllIds );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $idsWiCurrTranslation = $arr_return['data']['idsWiCurrTranslation'];
    $idsOfTranslationRows = $arr_return['data']['idsOfTranslationRows'];
      // Get ids of records, which match the rules and have a translation for the current language

    $idsOfDefaultLanguageRows = array( );
    if( empty ( $idsOfTranslationRows ) )
    {
        // Get ids of records of default language, which match the rules but haven't any translation
      $withAllIds = array( );
      $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withAllIds );
      if( $arr_return['error']['status'] )
      {
        return $arr_return;
      }
      $idsOfDefaultLanguageRows   = $arr_return['data']['idsOfHitsWoCurrTranslation'];
        // Get ids of records of default language, which match the rules but haven't any translation

      if( empty ( $idsOfDefaultLanguageRows ) )
      {
        return $arr_return;
      }
    }
    
      // Merge all ids
    $withIds = array_merge(
                ( array ) $idsWiCurrTranslation,
                ( array ) $idsOfTranslationRows,
                ( array ) $idsOfDefaultLanguageRows
              );

      // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );

    return $arr_return;
  }



 /**
  * rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation( ) : Get the ids of default or translated rows
  *
  * @return	array		$arr_return: Contains the ids
  * @version 3.9.13
  * @since   3.9.13
  */
  private function rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation( )
  {
      // Get ids of records, which match the rules and have a translation for the current language
      // Get all ids
    $withIds = array( );
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $withIds );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $withoutIds           = $arr_return['data']['idsWiCurrTranslation'];
    $idsOfTranslationRows = $arr_return['data']['idsOfTranslationRows'];
      // Get ids of records, which match the rules and have a translation for the current language

      // Get ids of records of default language, which match the rules but haven't any translation
    $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $idsOfDefaultLanguageRows   = $arr_return['data']['idsOfHitsWoCurrTranslation'];
      // Get ids of records of default language, which match the rules but haven't any translation

      // Merge all ids
    $withIds = array_merge(
                ( array ) $withoutIds,
                ( array ) $idsOfTranslationRows,
                ( array ) $idsOfDefaultLanguageRows
              );

      // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );

    return $arr_return;
  }



 /**
  * rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation( ) : Get the ids of default or translated rows
  *
  * @return	array		$arr_return: Contains the SQL res or an error message
  * @version 3.9.13
  * @since   3.9.13
  */
  private function rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation( )
  {

      // Get ids of records of default language, which match the rules
      // get all ids
    $withoutIds = array( );
    $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $idsOfDefaultLanguageRows   = $arr_return['data']['idsOfHitsWoCurrTranslation'];
      // Get ids of records of default language, which match the rules

      // Get ids of the translation records of the matched default records
    $arr_return = $this->rows_sqlIdsOfRowsWiTranslation( $idsOfDefaultLanguageRows );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $idsOfTranslationRows = $arr_return['data']['idsOfTranslationRows'];
      // Get ids of the translation records of the matched default records

      // Merge all ids
    $withIds = array_merge(
                ( array ) $idsOfTranslationRows,
                ( array ) $idsOfDefaultLanguageRows
              );

      // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $withIds );

    return $arr_return;
  }



  /**
 * rows_sqlIdsOfRowsWiTranslation( ) : Get ids of rows with translated records and ids of translated records
 *
 * @param	[type]		$$withIds: ...
 * @return	array		$arr_return: Array with two elements with the ids
 * @version 3.9.13
 * @since   3.9.13
 */
  private function rows_sqlIdsOfRowsWiTranslation( $withIds )
  {
    $arr_return = array( );

      // SWITCH $int_localisation_mode
    switch( $this->pObj->objLocalise->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        if( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
        {
          $prompt = 'Any id of translated row isn\'t needed.';
          t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // RETURN : nothing to do
        return $arr_return;
        break;
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
    $labelOfParentUid   = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
    $labelSysLanguageId = $GLOBALS['TCA'][$table]['ctrl']['languageField'];

      // RETURN : table is not localised
    if( ( ! $labelOfParentUid ) || ( ! $labelSysLanguageId ) )
    {
      if( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
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
    $select   = "DISTINCT " . $tableUid . " AS '" . $tableUid . "',
                          " . $tableL10nParent . " AS '" . $tableL10nParent . "'";
    $from     = $this->pObj->objSqlInit->statements['listView']['from'];
      // If FROM contains a relation from $tableUid to a foreign table, move
      //    $tableUid to $tableL10nParent
    $from     = str_replace( $tableUid . ' = ' , $tableL10nParent . ' = ' , $from );
    $where    = $this->pObj->objSqlInit->statements['listView']['where'];
    $andWhere = $table . '.' . $labelSysLanguageId . " = " . intval( $this->pObj->objLocalise->lang_id ) . " ";
    $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );

    $withIdList = implode( ',', ( array ) $withIds );
    $andWhere = null;
    if( $withIdList )
    {
      $andWhere = $tableL10nParent . " IN (" . $withIdList . ")";
    }
    $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
    if( $this->pObj->objFltr4x->init_aFilterIsSelected( ) )
    {
      $where  = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $this->pObj->objFltr4x->andWhereFilter );
    }

    if( ! empty( $this->pObj->objNaviIndexBrowser->findInSetForCurrTab ) )
    {
      $findInSetForCurrTab = $this->pObj->objNaviIndexBrowser->findInSetForCurrTab;
      $where  = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $findInSetForCurrTab );
    }
    
    $groupBy  = null;
    $orderBy  = $this->pObj->objSqlInit->statements['listView']['orderBy'];
    $limit    = $this->conf_view['limit'];
    if( $withIdList )
    {
      $limit = null;
    }
      // SQL query array

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

      // Execute
    $promptOptimise   = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
                        'Don\'t use the query in a localised context.';
    $debugTrailLevel  = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
      // Execute

      // Error management
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Error management

      // Get ids of rows with translated records and ids of translated records
    $res = $arr_return['data']['res'];
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $arr_return['data']['idsWiCurrTranslation'][] = $row[$tableL10nParent];
      $arr_return['data']['idsOfTranslationRows'][] = $row[$tableUid];
    }
      // Get ids of rows with translated records and ids of translated records

      // Free SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    return $arr_return;

  }



 /**
  * rows_sqlIdsOfRowsWiDefaultLanguage( ):  Get ids of rows of the default language. Rows
  *                                       which ids within the array $withoutIds will
  *                                       ignored
  *
  * @param	array		$withoutIds : Ids of rows, which have a translated record
  * @return	array		$arr_return : Contains the ids of rows
  * @version 3.9.13
  * @since   3.9.13
  */
  private function rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds )
  {
    $arr_return = array( );

      // SWITCH $int_localisation_mode
    switch( $this->pObj->objLocalise->int_localisation_mode )
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
    if( ! $GLOBALS['TCA'][$table]['ctrl']['languageField'] )
    {
      if( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
      {
        $prompt = $table . ' isn\'t localised.';
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
//        // RETURN : nothing to do
//      return $arr_return;
        // andWhere sys_language_uid ...
    }
    if( $GLOBALS['TCA'][$table]['ctrl']['languageField'] )
    {
      $andWhereSysLanguage = $table . '.' . $GLOBALS['TCA'][$table]['ctrl']['languageField'] . " <= 0";
    }
      // RETURN : table is not localised


      // andWhere list of ids ...
    $withoutIdList  = implode( ',', ( array ) $withoutIds );
    $andWhereIdList = null;
    if( $withoutIdList )
    {
      $andWhereIdList = $tableUid . " NOT IN (" . $withoutIdList . ")";
    }

      // SQL query array
    $select   = "DISTINCT " . $tableUid . " AS '" . $tableUid . "'";
    $from     = $this->pObj->objSqlInit->statements['listView']['from'];
    $where    = $this->pObj->objSqlInit->statements['listView']['where'];
//$this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->pObj->objSqlInit->statements['listView'] );
    $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhereSysLanguage );
    $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhereIdList );
//$this->pObj->dev_var_dump( $where );
    if( $this->pObj->objFltr4x->init_aFilterIsSelected( ) )
    {
      $where  = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $this->pObj->objFltr4x->andWhereFilter );
    }

    if( ! empty( $this->pObj->objNaviIndexBrowser->findInSetForCurrTab ) )
    {
      $findInSetForCurrTab = $this->pObj->objNaviIndexBrowser->findInSetForCurrTab;
      $where  = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $findInSetForCurrTab );
    }

    $groupBy  = null;

      // #9917: Selecting a random sample from a set of rows
    $orderBy  = $this->pObj->objSqlInit->statements['listView']['orderBy'];

      // LIMIT  : reduce amount of rows by amount of translated rows
    $limit  = $this->conf_view['limit'];
    list( $start, $results_at_a_time ) = explode( ',', $limit );
    $results_at_a_time = ( int ) $results_at_a_time - count ( $withoutIds );
    if( $results_at_a_time < 0 )
    {
      $prompt = 'Sorry, this error shouldn\'t occurred: Amount of displayed rows is \'' . $results_at_a_time . '\'.<br />
                <br />
                Method: ' . __METHOD__ . '<br />
                Line: ' . __LINE__ . '<br />
                <br />
                TYPO3 Browser';
      echo $prompt;
    }
    $limit  = ( int ) $start . "," . $results_at_a_time;
      // LIMIT  : reduce amount of rows by amount of translated rows
      // SQL query array

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
      // Get query

      // Execute query
    $promptOptimise   = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
                        'Don\'t use the query in a localised context.';
    $debugTrailLevel  = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
      // Execute query

      // Error management
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Error management

      // Get the SQL result
    $res = $arr_return['data']['res'];

      // Get the ids
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $arr_return['data']['idsOfHitsWoCurrTranslation'][] = $row[$tableUid];
    }
      // Get the ids

      // Free SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    return $arr_return;
  }



  /**
 * rows_sqlLanguageDefault( ): Building the SQL query, returns the SQL result.
 *
 * @return	array		$arr_return: Contains the SQL res or an error message
 * @version 3.9.13
 * @since   3.9.13
 */
  private function rows_sqlLanguageDefault( )
  {
    $withoutIds = array( );
    $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguage( $withoutIds );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $idsOfRowsDefaultLanguage = $arr_return['data']['idsOfHitsWoCurrTranslation'];
      // Get ids of records of default language

    if( empty ( $idsOfRowsDefaultLanguage ) )
    {
      return $arr_return;
    }

      // Get rows for the list view
    $arr_return = $this->rows_sqlRowsbyIds( $idsOfRowsDefaultLanguage );

    return $arr_return;
  }


  /**
 * rows_sqlLanguageFirstDefaultOrFirstTranslated( ): Get the ids of default or translated rows
 *
 * @return	array		$arr_return: Contains the ids
 * @version 3.9.13
 * @since   3.9.13
 */
  private function rows_sqlLanguageFirstDefaultOrFirstTranslated( )
  {
      // SWITCH : is index browser or ORDER BY ?localised
    switch( true )
    {
      case( $this->zz_indexBrowserIsLocalised( ) ):
        $arr_return = $this->rows_sqlIdsOfRowsWiTranslationOnly( );
        break;
      case( $this->zz_orderByValueIsLocalised( ) ):
          // First value of ORDER BY is localised
        $arr_return = $this->rows_sqlIdsOfRowsWiTranslationAndThanWoTranslation( );
        break;
          // First value of ORDER BY is localised
      case( ! $this->zz_orderByValueIsLocalised( ) ):
      default:
          // First value of ORDER BY isn't localised
        $arr_return = $this->rows_sqlIdsOfRowsWiDefaultLanguageAndThanWiTranslation( );
        break;
          // First value of ORDER BY isn't localised
    }
      // SWITCH : is index browser or ORDER BY ?localised

    return $arr_return;
  }



  /**
 * rows_sqlRowsbyIds( ): Get the rows for the list view. The method returns the SQL result, but an array.
 *
 * @param	string		$withIds     : Ids of the rows for the lost view
 * @return	array		$arr_return : Contains the SQL res or an error message
 * @version 3.9.13
 * @since   3.9.13
 * @todo    120506, dwildt: filterIsSelected
 */
  private function rows_sqlRowsbyIds( $withIds )
  {

      // SQL query array
    $select = $this->pObj->objSqlInit->statements['listView']['select'];

    $select = $this->sql_selectLocalised( $select );

    $from     = $this->pObj->objSqlInit->statements['listView']['from'];
    $where    = $this->pObj->objSqlInit->statements['listView']['where'];

    $thisIdList = implode( ',', ( array ) $withIds );
    if( $thisIdList )
    {
      $where  = $where . " AND " . $this->pObj->localTable . ".uid IN (" . $thisIdList . ")";
    }

    $groupBy  = null;
    $orderBy  = $this->pObj->objSqlInit->statements['listView']['orderBy'];

      // Don't limit the rows (we have a list of ids!)
    $limit = null;

      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'UNION isn\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

      // SQL query
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit,
                $uidIndexField=""
              );
      // SQL query
//var_dump( __METHOD__, __LINE__, $query );

      // Execute query
    $promptOptimise   = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
                        'Don\'t use the query in a localised context.';
    $debugTrailLevel  = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
      // Execute query

    return $arr_return;
  }



  /**
 * sql_selectLocalised( ) : If local table has language overlay fields or
 *                          is localised, fields for language controlling
 *                          are added
 *
 * @param	string		$select : Current select
 * @return	string		$select : Select with fields for localisation
 * @version 3.9.13
 * @since   3.9.12
 */
  private function sql_selectLocalised( $select )
  {
      // SWITCH $int_localisation_mode
    switch( $this->pObj->objLocalise->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        if( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
        {
          $prompt = 'SELECT doens\'t need to be localised.';
          t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // RETURN : nothing to do
        return $select;
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
          // Follow the workflow
        break;
      default:
          // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
      // SWITCH $int_localisation_mode

      // Get array with localised parts
      // Get localtable
    $table = $this->pObj->localTable;
    $arr_result = $this->pObj->objLocalise->localisationFields_select( $table );

      // Add the localised parts with aliases to the current SELECT statement
    $selectLocalisedPart = implode( ', ', ( array ) $arr_result['wiAlias'] );
    if( $selectLocalisedPart )
    {
      $select = $select . ', ' . $selectLocalisedPart;
      if( $this->pObj->b_drs_localise || $this->pObj->b_drs_sql )
      {
        $prompt = 'SELECT got the part for localising: ... ' . $selectLocalisedPart;
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
      // Add the localised part with aliases to the current SELECT statement

      // Check array for non unique elements
    $testArray = explode( ',', $select );
    $this->pObj->objZz->zz_devPromptArrayNonUnique( $testArray, __METHOD__, __LINE__ );

      // Add tables to the consolidation array
      // LOOP through all new table.fields
    foreach( ( array ) $arr_result['addedFields'] as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      if( ! in_array( $field, $this->pObj->arr_realTables_arrFields[$table] ) )
      {
          // Add every new table.field to the global array arr_realTables_arrFields
        $this->pObj->arr_realTables_arrFields[$table][] = $field;
          // Add every new table.field to the global array consolidate
        $this->pObj->arrConsolidate['addedTableFields'][] = $tableField;
      }
    }
      // LOOP through all new table.fields
      // Add tables to the consolidation array

    return $select;
  }









 /***********************************************
  *
  * Subparts
  *
  **********************************************/



/**
 * subpart_setSearchbox( ): Get the searchform. Part of content is generated
 *                          by the template class. Replace filter marker.
 *
 * @return	array		$arr_return : Error message in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function subpart_setSearchbox( )
  {
    $this->content  = $this->pObj->objTemplate->tmplSearchBox( $this->content );
    $arr_return     = $this->subpart_setSearchboxFilter( $filter );

    return $arr_return;
  }



/**
 * subpart_setSearchboxFilter( ): Get filter values and then replace filter marker with filter content.
 *
 * @return	array		$arr_return: Error message in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function subpart_setSearchboxFilter( )
  {
      // Default return value
    $arr_return = array( );

      // Get filter
    $arr_return = $this->pObj->objFltr4x->get( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $filter = $arr_return['data']['filter'];
      // Get filter

      // RETURN : there isn't any filter
    if( empty ( $filter ) )
    {
      return $arr_return;
    }
      // RETURN : there isn't any filter

      // Get the searchform content
    $searchform     = $this->pObj->cObj->getSubpart( $this->content, '###SEARCHFORM###' );
      // Replace filter marker with filter content
    $searchform     = $this->pObj->cObj->substituteMarkerArray( $searchform, $filter );
      // Add the subparts marker, because another method ( the search template ) need this subpart marker
    $searchform     = '<!-- ###SEARCHFORM### begin -->' . PHP_EOL .
                  $searchform . '<!-- ###SEARCHFORM### end -->' . PHP_EOL;
      // Update the searchform in the whole content
    $this->content  = $this->pObj->cObj->substituteSubpart( $this->content, '###SEARCHFORM###', $searchform, true );

    return $arr_return;
  }



/**
 * subpart_setIndexBrowser( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setIndexBrowser( )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'subpart_setIndexBrowser begin' );

    $arr_return = $this->pObj->objNaviIndexBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

    $content        = $arr_return['data']['content'];
    $marker         = $this->pObj->objNaviIndexBrowser->getMarkerIndexBrowser( );
    $this->content  = $this->pObj->cObj->substituteSubpart( $this->content, $marker, $content, true);

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'subpart_setIndexBrowser end' );
    return;
  }



/**
 * subpart_setModeSelector( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setModeSelector( )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Get the mode selector content
    $arr_return = $this->pObj->objNaviModeSelector->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      $this->content  = $this->pObj->cObj->substituteSubpart
                        (
                          $this->content, '###MODESELECTOR###', null, true
                        );
      return $arr_return;
    }
      // Get the mode selector content
    $content = $arr_return['data']['content'];

    if( empty ( $content ) )
    {
      $this->content  = $this->pObj->cObj->substituteSubpart
                        (
                          $this->content, '###MODESELECTOR###', null, true
                        );
      return;
    }

      // Set the marker array
    $markerArray                = $this->pObj->objWrapper->constant_markers( );
    $markerArray['###MODE###']  = $this->mode;
    $markerArray['###VIEW###']  = $this->view;
      // Set the marker array

    $modeSelector   = $this->pObj->cObj->getSubpart( $this->content, '###MODESELECTOR###' );
    $modeSelector   = $this->pObj->cObj->substituteMarkerArray( $modeSelector, $markerArray );
    $modeSelector   = $this->pObj->cObj->substituteSubpart
                      (
                        $modeSelector, '###MODESELECTORTABS###', $content, true
                      );
    $this->content  = $this->pObj->cObj->substituteSubpart
                      (
                        $this->content, '###MODESELECTOR###', $modeSelector, true
                      );

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    return;
  }



/**
 * subpart_setPageBrowser( ):  Replaces the pagebrowser subpart in the current content
 *                              with the content from ->objNaviPageBrowser->get( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setPageBrowser( )
  {
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Get the page browser content
    $arr_return = $this->pObj->objNaviPageBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the page browser content

      // Set marker the array
    $markerArray                            = $this->pObj->objWrapper->constant_markers( );
    $markerArray['###RESULT_AND_ITEMS###']  = $arr_return['data']['content'];
    $markerArray['###MODE###']              = $this->mode;
    $markerArray['###VIEW###']              = $this->view;
      // Set marker the array

      // Replace markers in the current content
    $subpart        = $this->pObj->cObj->getSubpart(  $this->content, '###PAGEBROWSER###' );
    $pageBrowser    = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $this->content  = $this->pObj->cObj->substituteSubpart
                      (
                        $this->content, '###PAGEBROWSER###', $pageBrowser, true
                      );
      // Replace markers in the current content

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    return;
  }









 /***********************************************
  *
  * Hooks
  *
  **********************************************/



  /**
 * hook_afterConsolidatetRows( ): Implement the hook rows_filter_values
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function hook_afterConsolidatetRows( )
  {
      // DRS
    if( $this->pObj->b_drs_hooks )
    {
        // Any foreign extension is using this hook
      if( ! is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
      {
        $prompt = 'Any third party extension doesn\'t use the HOOK rows_filter_values.';
        t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
        t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // Any foreign extension is using this hook

        // One foreign extension is using this hook at least
      if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
      {
        $i_extensions = count( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] );
        $arr_ext      = array_values( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] );
        $csv_ext      = implode( ',', $arr_ext );
        if( $i_extensions == 1 )
        {
          $prompt = 'The third party extension ' . $csv_ext . ' uses the HOOK rows_filter_values.';
          t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'In case of errors or strange behaviour please check this extension!';
          t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
        }
        if( $i_extensions > 1 )
        {
          $prompt = 'The third party extensions ' . $csv_ext . ' use the HOOK rows_filter_values.';
          t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'In case of errors or strange behaviour please check this extenions!';
          t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }
        // One foreign extension is using this hook at least
    }
      // DRS

      // Implement the hook
    if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
    {
      $_params = array( 'pObj' => &$this );
      foreach( ( array ) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] as $_funcRef )
      {
        t3lib_div::callUserFunction( $_funcRef, $_params, $this );
      }
    }
      // Implement the hook
  }









 /***********************************************
  *
  * ZZ
  *
  **********************************************/



  /**
 * zz_drsFirstRow( ): Prompt to devLog the first row
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_drsFirstRow( )
  {
    if( ! $this->pObj->b_drs_sql )
    {
      return;
    }

    if( count ( ( array ) $this->pObj->rows ) <= 0 )
    {
      return;
    }

    reset( $this->pObj->rows );
    $firstKey   = key( $this->pObj->rows );
    $firstRow   = $rows[$firstKey];

    $prompt = 'Result of the first row: ' . PHP_EOL;
    $prompt = $prompt . var_export( $firstRow, true );
    t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
  }



 /**
  * zz_indexBrowserIsLocalised( ) : Method returns true, if the a tab of the index
  *                                 browser is selected and the index browser tableField
  *                                 is localised.
  *
  * @return	boolean		$tableFieldIsLocalised : true or false
  * @version 3.9.13
  * @since   3.9.13
  */
  private function zz_indexBrowserIsLocalised( )
  {
      // RETURN false : there isn't any FIND IN SET for the current tab attributes
    if( ! $this->pObj->objNaviIndexBrowser->findInSetForCurrTab )
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
  * @return	boolean		$tableFieldIsLocalised : true or false
  * @version 3.9.13
  * @since   3.9.13
  */
  private function zz_orderByValueIsLocalised( )
  {
      // RETURN : ORDER BY is randomised
    if( $this->conf_view['random'] == 1 )
    {
      return false;
    }
      // RETURN : ORDER BY is randomised

      // Get ORDER BY
    $orderBy      = $this->pObj->objSqlInit->statements['listView']['orderBy'];
    $arr_orderBy  = $this->pObj->objZz->getCSVasArray( $orderBy );
      // Get the first tableField
    list( $tableField ) = explode( ' ', $arr_orderBy[0] );

      // Get localised status of the tableField
    $tableFieldIsLocalised = $this->pObj->objLocalise->zz_tablefieldIsLocalised( $tableField );
    
      // DRS
    if( $tableFieldIsLocalised )
    {
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'ORDER BY ' . $tableField . ' ... has unwanted effects!';
        t3lib_div::devlog( '[WARN/LOCALISATION+SQL] ' . $prompt,  $this->pObj->extKey, 2 );
        $prompt = $tableField . ' is translated. The sequence of rows will be: [ ordered [ordered rows of foreign
                  language limit 0,20] + [oderded rows of default language limit the rest]], but not [ordered rows limit 0,20]. Sorry, but this is a
                  need of performance.';
        t3lib_div::devlog( '[INFO/LOCALISATION+SQL] ' . $prompt,  $this->pObj->extKey, 2 );
      }
    }
      // DRS
      
      // RETURN the localised status
    return $tableFieldIsLocalised;
  }



 /**
  * zz_setGlobalArrLinkToSingle( ): Set the global $arrLinkToSingle
  *
  * @return	array
  * @version 3.9.8
  * @since 1.0.0
  */
  private function zz_setGlobalArrLinkToSingle( )
  {
    $conf_view = $this->conf_view;


      // Get linkToSingle CSV list
    $csvLinkToSingle = $conf_view['csvLinkToSingleView'];

      // IF no CSV list
    if ( ! $csvLinkToSingle )
    {
        // Set CSV list: take values from the select
      $csvLinkToSingle = $conf_view['select'];
      $csvLinkToSingle = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvLinkToSingle );
        // LOOP replace table.field with an alias
      foreach( ( array ) $conf_view['select.']['deal_as_table.'] as $arr_dealastable )
      {
        $csvLinkToSingle = str_replace( $arr_dealastable['statement'], $arr_dealastable['alias'], $csvLinkToSingle );
          // DRS
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = 'Used tables: Statement "' . $arr_dealastable['statement'] . '" ' .
                    'is replaced with "' . $arr_dealastable['alias'] . '"';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
      }
        // LOOP replace table.field with an alias
        // DRS
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = $this->conf_path . ' hasn\'t any linkToSingleView.';
        t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want a link to a single view, please configure ' .
                  $this->conf_path . '.csvLinkToSingleView.';
        t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
    }
      // IF no CSV list

      // Set the global $arrLinkToSingle
    $arrLinkToSingleFields = explode( ',', $csvLinkToSingle );
    $this->pObj->arrLinkToSingle = array( );
    foreach( ( array ) $arrLinkToSingleFields as $arrLinkToSingleField )
    {
      list( $table, $field ) = explode( '.', trim( $arrLinkToSingleField ) );
      $this->pObj->arrLinkToSingle[] = $table.'.'.$field;
    }
      // Set the global $arrLinkToSingle

      // Replace aliases in case of aliases
    if( is_array( $conf_view['aliases.']['tables.'] ) )
    {
      foreach( $this->pObj->arrLinkToSingle as $i_key => $str_tablefield )
      {
        $this->pObj->arrLinkToSingle[$i_key] = $this->pObj->objSqlFun_3x->get_sql_alias_before( $str_tablefield );
      }
      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun_3x->replace_tablealias( $this->pObj->arrLinkToSingle );
    }
      // Replace aliases in case of aliases

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $str_csvList  = implode( ', ', $this->pObj->arrLinkToSingle );
      $prompt       = 'Fields which will get a link to a single view: ' . $str_csvList . '.';
      t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt       = 'If you want to configure the field list, please use ' .
                      $this->conf_path . '.csvLinkToSingleView.';
      t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_viewlist.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_viewlist.php']);
}

?>