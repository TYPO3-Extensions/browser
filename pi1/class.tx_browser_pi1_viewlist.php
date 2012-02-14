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
 * The class tx_browser_pi1_viewlist bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_browser
 * @version 3.9.8
 * @since 1.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_browser_pi1_viewlist
 *   73:     function __construct($parentObj)
 *
 *              SECTION: Building the views
 *  105:     function listView( $template )
 * 1372:     function singleView($template)
 *
 *              SECTION: Helper
 * 1976:     public function displayThePlugin( )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_viewlist
{


    // Array with the fields of the SQL result
  var $arr_select;
    // Array with fields from orderBy from TS
  var $arr_orderBy;
    // Array with fields from functions.clean_up.csvTableFields from TS
  var $arr_rmFields;

    // [Integer] Id of the current mode.
  var $mode       = null;
    // [Array] TypoScript of the current view
  var $conf_view  = null;



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
 * main( ): Display a search form, a-z-Browser, pageBrowser and a list of records
 *
 * @return	void
 * @version 3.9.8
 * @since 1.0.0
 */
  function main( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin listView( )' );

      // Short vars
    $conf       = $this->pObj->conf;
    $mode       = $this->pObj->piVar_mode;
    $cObj       = $this->pObj->cObj;

    $view       = $this->pObj->view;
    $viewWiDot  = $view . '.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode . '.'];
      // Short vars

      // Global vars
    $this->conf_view  = $conf_view;


    
      /////////////////////////////////////
      //
      // Default mode

    $maxModes = count( $conf['views.'][$viewWiDot] );
    if( $mode > $maxModes )
    {
      $mode       = 1;
      $this->mode = 1;
    }
      // Default mode


      //////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any list configured

    $prompt = $this->check_view( );
    if( $prompt )
    {
      return $prompt;
    }
      // RETURN there isn't any list configured



      // HTML content of the current template
    $template = $this->pObj->str_template_raw;

      //Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
    $this->init( );



      //////////////////////////////////////////////////////////////////////
      //
      // Filter - part I/II: SQL andWhere statement

      // 3.5.0
      // #30912, 120127, dwildt-
//    $arr_andWhereFilter = $this->pObj->objFilter->andWhere_filter();
//    if ( ! empty( $arr_andWhereFilter ) )
//    {
//      $this->pObj->arr_andWhereFilter = $arr_andWhereFilter;
//    }
      // #30912, 120127, dwildt-
      // #30912, 120127, dwildt+
    $this->pObj->objFilter->andWhere_filter( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->andWhere_filter( )' );
      // Filter - part I/II: SQL andWhere statement



      /////////////////////////////////////
      //
      // Set global SQL values

    $arr_result = $this->pObj->objSqlFun->global_all( );
    if( $arr_result['error']['status'] )
    {
      $template = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      return $template;
    }
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->global_all( )' );
      // Set global SQL values



      /////////////////////////////////////
      //
      // SQL query array

    $arr_result = $this->sql_getQueryArray( );
    if( $arr_result['error']['status'] )
    {
      $template = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      return $template;
    }
    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
    $orderBy  = $arr_result['data']['orderBy'];
    $union    = $arr_result['data']['union'];
      // SQL query array



      ///////////////////////////////////////////////////////////////////////
      //
      // Set ORDER BY to false - we like to order by PHP

//:TODO: 120214, performance: order by aktivieren
    $orderBy = false;
      // #9917: Selecting a random sample from a set of rows
    if( $conf_view['random'] == 1 )
    {
      $orderBy = 'rand( )';
    }
      // Set ORDER BY to false - we like to order by PHP



      //////////////////////////////////////////////////////////////////////
      //
      // Execute the SQL query

    $b_union = false;
    if( $union )
    {
        // We have a UNION. Maybe because there are synonyms.
      $query   = $union;
      $b_union = true;
    }
    if( ! $union )
    {
      $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                      (
                                        $select,
                                        $from,
                                        $where,
                                        $groupBy="",
                                        $orderBy,
                                        $limit="",
                                        $uidIndexField=""
                                      );
    }

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'before $GLOBALS[TYPO3_DB]->sql_query( )' );
    $tt_start = $this->pObj->tt_prevEndTime;
    $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
    $error = $GLOBALS['TYPO3_DB']->sql_error( );

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
    $this->pObj->timeTracking_prompt( $query );
    $tt_end = $this->pObj->tt_prevEndTime;

    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      return $this->pObj->objSqlFun->prompt_error( );
    }
      // Execute the SQL query

if( $this->pObj->bool_accessByIP )
{
  if( ( $tt_end - $tt_start ) > 1000 )
  {
    $prompt = '<h1>Mehr als eine Sekunde</h1>' .
              '<p>' . __METHOD__ . ' (' . __LINE__ . '): </p>' .
              '<p>' . $query . '</p>';
    die( $prompt );
  }
}


      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[OK/SQL] ' . $query,  $this->pObj->extKey, -1 );
      t3lib_div::devlog( '[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query ' .
                          'in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1 );
    }
      // DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////
      //
      // Workaround filter and localisation - Bugfix #9024

      /*
       * Description of the bug
       *
       * If we have a localised website
       * and the user has selected a non default language
       * and the user has selected a filter
       * than the query below will select only default language records
       */

      // User selected a non default language
    if( $this->pObj->objLocalise->int_localisation_mode >= 3 )
    {
        // User selected a filter
      if( ! empty( $this->pObj->arr_andWhereFilter ) )
      {
        if( ! $b_union && ! $this->pObj->b_sql_manual )
        {
          $arr_where = null;
          list( $table, $field ) = explode( '.', $this->pObj->arrLocalTable['uid'] );
            // Get the field names for sys_language_content and for l10n_parent
          $arr_localise['id_field']   = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
          $arr_localise['pid_field']  = $GLOBALS['TCA'][$table]['ctrl'][' '];
            // Get the field names for sys_language_content and for l10n_parent

            // 13505, 110302, dwildt
          $where = null;
          if( $arr_localise['id_field'] && $arr_localise['pid_field'] )
          {
            while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
            {
              $uid          = $row[$table . '.' . $field];
              $arr_where[]  = '(' . $table . '.' . $field . ' = ' . $uid .
                              ' OR ' . $table . '.' . $arr_localise['pid_field'] . ' = '. $uid . ')';
                                // 13573, 110303, dwildt
            }
            $where    = implode( ' OR ', $arr_where );
            $where    = '(' . $where . ')';
            $andWhere = $this->pObj->objLocalise->localisationFields_where( $table );
              // 13505, 110302, dwildt
            if( ! $andWhere )
            {
              $andWhere = 1;
            }
            $where = $where . ' AND ' . $andWhere;
            $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                            (
                                              $select,
                                              $from,
                                              $where,
                                              $groupBy="",
                                              $orderBy,
                                              $limit="",
                                              $uidIndexField=""
                                            );
            $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
            $error = $GLOBALS['TYPO3_DB']->sql_error( );

              // DRS - Development Reporting System
            if( $this->pObj->b_drs_sql )
            {
              $prompt = 'Bugfix #9024 - Next query for localisation consolidation:';
              t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
              t3lib_div::devlog( '[INFO/SQL] ' . $query,  $this->pObj->extKey, 0 );
              $prompt = 'Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.';
              t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
            }
              // Prompt the expired time to devlog
            $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
              // DRS - Development Reporting System
          }

        }
        if( $b_union )
        {
          if( $this->pObj->b_drs_error )
          {
            $prompt = 'User has selected a non default language. User has selected a filter too. ' .
                      'And we have a query with UNIONs. It isn\'t possible to display localised records ' .
                      'proper! We are sorry!';
            t3lib_div::devlog( '[ERROR/FILTER] ' . $prompt,  $this->pObj->extKey, 3 );
          }
        }
        if( $this->pObj->b_sql_manual )
        {
          if( $this->pObj->b_drs_error )
          {
            $prompt = 'User has selected a non default language. User has selected a filter too. ' .
                      'And we have a query with UNIONs. And we have a manual generated query. ' .
                      'It isn\'t possible to display localised records proper!';
            t3lib_div::devlog( '[ERROR/FILTER] ' . $prompt,  $this->pObj->extKey, 3 );
          }
        }
      }
        // User selected a filter
    }
      // User selected a non default language

    if( $error )
    {
      if( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog( '[ERROR/SQL] ' . $query,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[ERROR/SQL] ' . $error,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3 );
        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' .
                        $this->pObj->pi_getLL( 'drs_security' ) . '</p>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
      }
      if( ! $this->pObj->b_drs_error )
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' .
                      $this->pObj->pi_getLL( 'drs_sql_prompt' ) . '</p>';
      }
      $str_header  = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn . $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Workaround filter and localisation - Bugfix #9024



      ////////////////////////////////////
      //
      // Building $rows

    $arr_table_realnames = $conf_view['aliases.']['tables.'];

      // Do we have aliases?
    if( is_array( $arr_table_realnames ) )
    {
        // Yes, we have aliases.
      $i_row = 0;
      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
      {
        foreach( $row as $str_tablealias_field => $value )
        {
          $arr_tablealias_field = explode('.', $str_tablealias_field);   // table_1.sv_name
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
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We have aliases.' );
        // Yes, we have aliases.
    }
    if( ! is_array( $arr_table_realnames ) )
    {
        // No, we don't have any alias.
      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
      {
        $rows[] = $row;
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We haven\'t aliases.' );
    }
    $this->pObj->rows = $rows;
      // Do we have aliases?

      // SQL Free Result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after building rows.' );
      // Building $rows



      /////////////////////////////////////////////////////////////////
      //
      // Process synonyms if rows have synonyms

    $arr_result = $this->pObj->objSqlFun->rows_with_synonyms( $rows );
    $rows       = $arr_result['data']['rows'];
    unset( $arr_result );
    $this->pObj->rows = $rows;
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->rows_with_synonyms( )' );
      // Process synonyms if rows have synonyms



      /////////////////////////////////////////////////////////////////
      //
      // Consolidate Localisation

    if( ! $this->pObj->b_sql_manual )
    {
      $rows = $this->pObj->objLocalise->consolidate_rows( $rows, $this->pObj->localTable );
      $this->pObj->rows = $rows;
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objLocalise->consolidate_rows( )' );
    }
    if( $this->pObj->b_sql_manual && $this->pObj->b_drs_localisation )
    {
      $prompt = 'Manual SQL mode: Rows didn\'t get any localisation consolidation.';
      t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
    }
      // Consolidate Localisation



      ///////////////////////////////////////////////////////////////
      //
      // Consolidate rows

    if( ! $this->pObj->b_sql_manual )
    {
      $arr_result       = $this->pObj->objConsolidate->consolidate( $rows );
      $rows             = $arr_result['data']['rows'];
      $int_rows_wo_cons = $arr_result['data']['rows_wo_cons'];
      $int_rows_wi_cons = $arr_result['data']['rows_wi_cons'];
      unset($arr_result);
      $this->pObj->rows = $rows;
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objConsolidate->consolidate( )' );
    }
    if( $this->pObj->b_sql_manual && $this->pObj->b_drs_localisation )
    {
      $prompt = 'Manual SQL mode: Rows didn\'t get any general consolidation.';
      t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
    }
      // Consolidate rows



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for handle the consolidated rows

      // #12813, dwildt, 110205
      // This hook is used by one foreign extension at least
    if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
    {
        // DRS - Development Reporting System
      if ( $this->pObj->b_drs_hooks )
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
        // DRS - Development Reporting System

      $_params = array( 'pObj' => &$this );
      foreach( ( array ) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] as $_funcRef )
      {
        t3lib_div::callUserFunction( $_funcRef, $_params, $this );
      }
    }
      // Any foreign extension is using this hook
      // DRS - Development Reporting System
    if( $this->pObj->b_drs_hooks )
    {
      if( ! is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
      {
        $prompt = 'Any third party extension doesn\'t use the HOOK rows_filter_values.';
        t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
        t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
      }
    }
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after hook rows_filter_values' );
      // DRS - Development Reporting System
      // Any foreign extension is using this hook

    $rows = $this->pObj->rows;
      // Hook for handle the consolidated rows



      /////////////////////////////////////////////////////////////////
      //
      // Ordering the rows

    // #9917: Selecting a random sample from a set of rows
    if( ! ( $conf_view['random'] == 1 ) )
    {
      $this->pObj->objMultisort->multisort_rows( );
      $rows = $this->pObj->rows;
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objMultisort->multisort_rows( )' );
    }
      // Ordering the rows



      /////////////////////////////////////////////////////////////////
      //
      // Ordering the children

      // 13803, dwildt, 110312
    $rows = $this->pObj->objMultisort->multisort_mm_children( $rows );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objMultisort->multisort_mm_children( $rows )' );
      // 120127, dwildt+
    $this->pObj->rows = $rows;
      // Ordering the children

    

      /////////////////////////////////////////////////////////////////
      //
      // Store amount of rows for the pagebrowser

    // 100429, dwildt: rowsPb isn't set. Debug the code!
    if( isset( $rowsPb ) )
    {
      $rowsPb = false;
    }

    if( $rowsPb == false )
    {
      $rowsPb = count( $rows );
    }
      // Store amount of rows for the pagebrowser



      /////////////////////////////////////////////////////////////////
      //
      // Order and edit the rows hierarchical

    $b_hierarchical = $conf_view['functions.']['hierarchical'];
    if( $b_hierarchical )
    {
      $rows = $this->pObj->objSqlFun->make_hierarchical( $rows );
        // 120127, dwildt+
      $this->pObj->rows = $rows;
      if( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] Result should ordered hierarchical.',  $this->pObj->extKey, 0 );
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->make_hierarchical( $rows )' );
    }
      // Order and edit the rows hierarchical



      /////////////////////////////////////////////////////////////////
      //
      // DRS - Show the first row

    if( $this->pObj->b_drs_sql )
    {
      $str_prompt = 'Result of the first row: ' . PHP_EOL;
      if( is_array( $rows ) && count( $rows ) > 0 )
      {
        reset( $rows );
        foreach( $rows as $key => $value )
        {
          $str_prompt .= '[' . $key . ']: ' . htmlspecialchars( $value ) . ' ' . PHP_EOL;
        }
        t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
      }
    }
      // DRS - Show the first row



      ////////////////////////////////////////////////////////////////////////
      //
      // Filter - part II/II - HTML code / template

      // Count hits, filter rows, update template

    $this->pObj->objFilter->rows_wo_limit = $rows;
    $arr_result = $this->pObj->objFilter->filter( $template );
    if( $arr_result['error']['status'] )
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass( $prompt );
    }
    $template = $arr_result['data']['template'];
      // 120127, dwildt+
    $rows             = $arr_result['data']['rows'];
    $this->pObj->rows = $rows;
      // 120127, dwildt+
    unset( $arr_result );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->filter( )' );
      // Filter - part II/II - HTML code / template



      /////////////////////////////////////////////////////////////////
      //
      // Clean up: Delete fields, we don't want to display

    $arr_result = $this->pObj->objSqlFun->rows_with_cleaned_up_fields( $rows );
    $rows       = $arr_result['data']['rows'];
    unset($arr_result);
      // 110801, dwildt
    $this->pObj->rows = $rows;
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->rows_with_cleaned_up_fields( )' );
      // Clean up: Delete rows, we don't want to display



      /////////////////////////////////////////////////////////////////
      //
      // DRS - Show the first row

    if( $this->pObj->b_drs_sql )
    {
      $str_prompt = 'Result of the first row: ' . PHP_EOL;
      if( is_array( $rows ) && count( $rows ) > 0 )
      {
        reset( $rows );
        foreach( $rows as $key => $value )
        {
          $str_prompt .= '[' . $key . ']: ' . htmlspecialchars( $value ) . ' ' . PHP_EOL;
        }
        t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
      }
    }
      // DRS - Show the first row



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for override the SQL result for for the list view

      // This hook is used by one extension at least
    if( is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] ) )
    {
        // DRS - Development Reporting System
      if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_browser )
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
        $csv_ext      = implode(',', $arr_ext);
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/SQL] The third party extensions '.$csv_ext.' use the HOOK browser_list.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
        else
        {
          t3lib_div::devlog('[INFO/SQL] The third party extension '.$csv_ext.' uses the HOOK browser_list.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      //:todo: Proper Hook
//      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] as $_classRef)
//      {
//        $_procObj   = &t3lib_div::getUserObj($_classRef);
//        $this->pObj = $_procObj->browser_list($arr_data, $this);
//      }
    }
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after hook browser_list' );
      // Hook for override the SQL result for for the list view



      ////////////////////////////////////////////////////////////////////////
      //
      // Prepaire array with links to single view

    $csvLinkToSingle = $conf_view['csvLinkToSingleView'];
    if (!$csvLinkToSingle)
    {
      $csvLinkToSingle = $conf_view['select'];
      $csvLinkToSingle = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($csvLinkToSingle);
        // Is there a statement, which should replaced with an alias?
      if (is_array($conf_view['select.']['deal_as_table.']))
      {
        foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
        {
          $csvLinkToSingle = str_replace($arr_dealastable['statement'], $arr_dealastable['alias'], $csvLinkToSingle);
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Used tables: Statement "'.$arr_dealastable['statement'].'" is replaced with "'.$arr_dealastable['alias'].'"', $this->pObj->extKey, 0);
          }
        }
      }
    }
    if (!$csvLinkToSingle)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/DRS] views.'.$viewWiDot.$mode.' hasn\'t any linkToSingleView.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/DRS] If you want a link to a single view, please configure views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
      }
    }
    $arrLinkToSingleFields = explode(',', $csvLinkToSingle);
    $this->pObj->arrLinkToSingle = array();
    foreach((array) $arrLinkToSingleFields as $arrLinkToSingleField)
    {
      list($table, $field) = explode('.', trim($arrLinkToSingleField));
      $this->pObj->arrLinkToSingle[] = $table.'.'.$field;
    }

      // Replace aliases in case of aliases
    if (is_array($conf_view['aliases.']['tables.']))
    {
      foreach ($this->pObj->arrLinkToSingle as $i_key => $str_tablefield)
      {
        $this->pObj->arrLinkToSingle[$i_key] = $this->pObj->objSqlFun->get_sql_alias_before($str_tablefield);
      }
      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun->replace_tablealias($this->pObj->arrLinkToSingle);
    }
      // Replace aliases in case of aliases

    $str_csvList = implode(', ', $this->pObj->arrLinkToSingle);
    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/DRS] Fields which will get a link to a single view: '.$str_csvList.'.', $this->pObj->extKey, 0);
      t3lib_div::devLog('[HELP/DRS] If you want to configure the field list, please use views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
    }
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after Prepaire array with links to single view' );
      // Prepaire array with links to single view



      // #29370, 110831, dwildt-
//      /////////////////////////////////////
//      //
//      // Building the template
//
//      // HTML template
//    $str_marker = $this->pObj->lDisplayList['templateMarker'];
//    $template   = $this->pObj->cObj->getSubpart($template, $str_marker);
//      // HTML template
      // #29370, 110831, dwildt-



      // #29370, 110831, dwildt+
      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the title in case of csv export
    $str_marker = $this->pObj->lDisplayList['templateMarker'];
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/TEMPLATING+EXPORT] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
        }
        $str_marker     = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
        $template_path  = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
        $template       = $this->pObj->cObj->fileResource($template_path);
        break;
      default:
        // Do nothing;
    }
      // Remove the title in case of csv export
      // csv export
      // #29370, 110831, dwildt+



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #32654, 120212, dwildt+
    $str_marker = $this->pObj->lDisplayList['templateMarker'];
    switch( $this->pObj->objMap->str_typeNum )
    {
      case( 'map' ) :
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_map )
        {
          t3lib_div::devlog('[INFO/TEMPLATING+MAP] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
        }
        $str_marker     = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
        $template_path  = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
        $template       = $this->pObj->cObj->fileResource($template_path);
        break;
      default:
        // Do nothing;
    }
      // csv export



      // #29370, 110831, dwildt+
      /////////////////////////////////////
      //
      // Building the template

      // HTML template
    $template   = $this->pObj->cObj->getSubpart( $template, $str_marker );
      // HTML template
      // #29370, 110831, dwildt+



    if( $str_marker == '###TEMPLATE_CSV###' )
    {
      if( empty( $template ) )
      {
        $prompt = '<div style="border:2em solid red;color:red;padding:2em;text-align:center;">
            <h1>
              TYPO3 Browser Error
            </h1>
            <h2>
              EN: Subpart is missing
            </h2>
            <p>
              English: Current HTML template doesn\'t contain the subpart ###TEMPLATE_CSV###.<br />
              Please take care of a proper template.<br />
            </p>
            <h2>
              DE: Subpart fehlt
            </h2>
            <p>
              Deutsch: Dem aktuellen HTML-Template fehlt der Subpart ###TEMPLATE_CSV###.<br />
              Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
            </p>
          </div>';
        die( $prompt );
      }
    }
//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $template );
//      die( );
//    }



      // HTML search form
      // #9659, 101011, fsander
    //$bool_display = $this->pObj->objFlexform->bool_searchForm;
    $bool_display = $this->pObj->objFlexform->bool_searchForm && $this->pObj->segment['searchform'];
    $template     = $this->pObj->objTemplate->tmplSearchBox( $template, $bool_display );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objTemplate->tmplSearchBox( )' );
      // HTML search form



      /////////////////////////////////////////////////////////////////
      //
      // Extension pi5: +Browser Calendar

      // Will executed in case, that the Browser is extended with the Browser Calendar user Interface
    $arr_result   = $this->pObj->objCal->cal( $rows, $template );
    $bool_success = $arr_result['success'];
    if( $bool_success )
    {
      $rows         = $arr_result['rows'];
      $template     = $arr_result['template'];
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
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objCal->cal( )' );
      // Extension pi5: +Browser Calendar



      /////////////////////////////////////
      //
      // HTML a-z-browser

    $arr_data['template']       = $template;
      // 110801, dwildt
    //$arr_data['rows']           = $rows;
    $arr_data['rows']           = $this->pObj->rows;
    $arr_result = $this->pObj->objNavi->azBrowser( $arr_data );
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass($prompt);
    }

    $lArrTabs         = $arr_result['data']['azTabArray'];
    $arr_tsId         = $arr_result['data']['tabIds'];
    $template         = $arr_result['data']['template'];
    $rows             = $arr_result['data']['rows'];
      // 110801, dwildt +
    $this->pObj->rows = $rows;
    unset($arr_result);
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->azBrowser( )' );
      // HTML a-z-browser



      /////////////////////////////////////
      //
      // record browser

    $arr_result = $this->pObj->objNavi->recordbrowser_set_session_data( $rows );
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass( $prompt );
    }
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->recordbrowser_set_session_data( )' );
      // record browser



      /////////////////////////////////////
      //
      // HTML page browser

    $arr_data['azTabArray'] = $lArrTabs;
    $arr_data['tabIds']     = $arr_tsId;
    $arr_data['template']   = $template;
    $arr_data['rows']       = $rows;

    $arr_result = $this->pObj->objNavi->tmplPageBrowser( $arr_data );
    unset($arr_data);
    $template         = $arr_result['data']['template'];
    $rows             = $arr_result['data']['rows'];
      // 110801, dwildt +
    $this->pObj->rows = $rows;
    unset($arr_result);
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->tmplPageBrowser( )' );
      // HTML page browser

      // HTML mode selector
    $arr_data['template']     = $template;
    $arr_data['arrModeItems'] = $this->pObj->arrModeItems;
    $template = $this->pObj->objNavi->tmplModeSelector( $arr_data );
    unset($arr_data);
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->tmplModeSelector( )' );
      // HTML mode selector
      // Building the template



      ///////////////////////////////////////////////
      //
      // In case of limit, limit the rows

    if( isset( $conf_view['limit'] ) )
    {
      $arr_limit        = explode(',', $conf_view['limit']);
      $int_start        = (int) trim($arr_limit[0]);
      $int_amount       = (int) trim($arr_limit[1]);
      $int_counter      = 0;
      $int_remove_start = $int_start;
      $int_remove_end   = $int_start + $int_amount;
      $drs_rows_before  = count($rows);
      if (is_array($rows))
      {
        foreach ($rows as $row => $elements)
        {
          if ($int_counter < $int_remove_start || $int_counter >= $int_remove_end)
          {
            unset($rows[$row]);
          }
          $int_counter++;
        }
      }
      $drs_rows_after = count($rows);

        // DRS - Development Reporting System
      if ($drs_rows_after != $drs_rows_before)
      {
        $removed_rows = $drs_rows_before - $drs_rows_after;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] We have a limit: '.$conf_view['limit'].'<br />'.
            ' #'.$removed_rows.' rows were removed.',  $this->pObj->extKey, 0);
        }
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: in case of a limit' );
        // DRS - Development Reporting System
    }
    if( ! isset( $conf_view['limit'] ) )
    {
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] We don\'t have any limit.<br />'.
          ' #0 rows were removed. We have #'.count($rows).' rows.',  $this->pObj->extKey, 0);
      }
    }
      // In case of limit, limit the rows



      ////////////////////////////////////////////////////////////////////////
      //
      // HTML records

    $template = $this->pObj->objTemplate->tmplListview($template, $rows);
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objTemplate->tmplListview( )' );
      // HTML records



    return $template;
  }









  /**
 * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
 *
 * @return	void
 * @version 3.9.8
 * @since 1.0.0
 */
  function init( )
  {
      // Overwrite global general_stdWrap
      // #12471, 110123, dwildt+
    if( is_array( $this->conf_view['general_stdWrap.'] ) )
    {
      $this->pObj->conf['general_stdWrap.'] = $this->conf_view['general_stdWrap.'];
    }
      // Overwrite global general_stdWrap

      // Get the local or global displayList
    if( is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $this->conf_view['displayList.'];
    }
    if( ! is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $conf['displayList.'];
    }
      // Get the local or global displayList



      // Get the local or global displayList.display
    if( is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $this->conf_view['displayList.']['display.'];
    }
    if( ! is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $conf['displayList.']['display.'];
    }
      // Get the local or global displayList.display
  }









  /**
 * check_view( ):
 *
 * @return	void
 * @version 3.9.8
 * @since 1.0.0
 */
  function check_view( )
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
  * SQL
  *
  **********************************************/









  /**
 * sql_getQueryArray( ):
 *
 * @return	array
 * @version 3.9.8
 * @since 1.0.0
 */
  function sql_getQueryArray( )
  {
      // RETURN case is SQL manual
    if( $this->pObj->b_sql_manual )
    {
      $arr_result = $this->pObj->objSqlMan->get_query_array( $this );
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlMan->get_query_array( )' );
      return $arr_result;
    }
      // RETURN case is SQL manual

      // RETURN case is SQL automatically
    $arr_result = $this->pObj->objSqlAut->get_query_array( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlAut->get_query_array( )' );
    return $arr_result;
      // RETURN case is SQL automatically
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']);
}

?>
