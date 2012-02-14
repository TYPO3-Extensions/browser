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
 * The class tx_browser_pi1_views bundles methods for displaying the list view and the singe view for the extension browser
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
 *   54: class tx_browser_pi1_views
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
class tx_browser_pi1_views
{


  var $arr_select;
  // Array with the fields of the SQL result
  var $arr_orderBy;
  // Array with fields from orderBy from TS
  var $arr_rmFields;
  // Array with fields from functions.clean_up.csvTableFields from TS



   /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }











  /***********************************************
   *
   * Building the views
   *
   **********************************************/




//  /**
// * Display a search form, a-z-Browser, pageBrowser and a list of records
// *
// * @return	void
// * @version 3.9.8
// * @since 1.0.0
// */
//  function listView( )
//  {
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin listView( )' );
//
//    $conf = $this->pObj->conf;
//    $mode = $this->pObj->piVar_mode;
//    $cObj = $this->pObj->cObj;
//
//    $view       = $this->pObj->view;
//    $viewWiDot  = $view.'.';
//    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
//
//    $template   = $this->pObj->str_template_raw;
//
//
//
//      /////////////////////////////////////
//      //
//      // Overwrite global general_stdWrap
//
//      // #12471, 110123, dwildt
//    if( is_array( $conf_view['general_stdWrap.'] ) )
//    {
//      $this->pObj->conf['general_stdWrap.'] = $conf_view['general_stdWrap.'];
//    }
//      // Overwrite global general_stdWrap
//
//
//
//      /////////////////////////////////////
//      //
//      // Get the local or global displayList
//
//    if( is_array( $conf_view['displayList.'] ) )
//    {
//      $this->pObj->lDisplayList = $conf_view['displayList.'];
//    }
//    if( ! is_array( $conf_view['displayList.'] ) )
//    {
//      $this->pObj->lDisplayList = $conf['displayList.'];
//    }
//      // Get the local or global displayList
//
//
//
//      /////////////////////////////////////
//      //
//      // Get the local or global displayList.display
//
//    if( is_array( $conf_view['displayList.']['display.'] ) )
//    {
//      $this->pObj->lDisplay = $conf_view['displayList.']['display.'];
//    }
//    if( ! is_array( $conf_view['displayList.']['display.'] ) )
//    {
//      $this->pObj->lDisplay = $conf['displayList.']['display.'];
//    }
//      // Get the local or global displayList.display
//
//
//
//      /////////////////////////////////
//      //
//      // RETURN, there isn't any list
//
//    if( ! is_array( $conf['views.'][$viewWiDot] ) )
//    {
//      if ( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog( '[ERROR/DRS] views.' . $view . ' hasn\'t any item.', $this->pObj->extKey, 3 );
//        $prompt = 'Did you included the static template from this extensions?';
//        t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
//        $tsArray = 'plugin.' . $this->pObj->prefixId . '.views.list';
//        t3lib_div::devLog( '[HELP/DRS] Did you configure ' . $tsArray . '?', $this->pObj->extKey, 1 );
//      }
//      return false;
//    }
//      // RETURN, there isn't any list
//
//
//
//      /////////////////////////////////////
//      //
//      // Do we have an existing mode?
//
//    $maxModes = count( $conf['views.'][$viewWiDot] );
//    if( $mode > $maxModes )
//    {
//      $mode = 1;
//    }
//      // Do we have an existing mode?
//
//
//      //////////////////////////////////////////////////////////////////////
//      //
//      // Filter - part I/II: SQL andWhere statement
//
//      // 3.5.0
//      // #30912, 120127, dwildt-
////    $arr_andWhereFilter = $this->pObj->objFilter->andWhere_filter();
////    if ( ! empty( $arr_andWhereFilter ) )
////    {
////      $this->pObj->arr_andWhereFilter = $arr_andWhereFilter;
////    }
//      // #30912, 120127, dwildt-
//      // #30912, 120127, dwildt+
//    $this->pObj->objFilter->andWhere_filter( );
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->andWhere_filter( )' );
//      // Filter - part I/II: SQL andWhere statement
//
//
//
//      /////////////////////////////////////
//      //
//      // Set global SQL values
//
//    $arr_result = $this->pObj->objSqlFun->global_all( );
//    if ($arr_result['error']['status'])
//    {
//      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
//      return $template;
//    }
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->global_all( )' );
//      // Set global SQL values
//
//
//
//      /////////////////////////////////////
//      //
//      // SQL with manual configuration
//
//    if( $this->pObj->b_sql_manual )
//    {
//      $arr_result = $this->pObj->objSqlMan->get_query_array( $this );
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlMan->get_query_array( )' );
//    }
//      // SQL with manual configuration
//
//
//
//      /////////////////////////////////////
//      //
//      // SQL with autmatically configuration
//
//    if( ! $this->pObj->b_sql_manual )
//    {
//      $arr_result = $this->pObj->objSqlAut->get_query_array( );
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlAut->get_query_array( )' );
//    }
//      // SQL with autmatically configuration
//
//
//
//      /////////////////////////////////////
//      //
//      // ERROR management
//
//    if( $arr_result['error']['status'] )
//    {
//      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
//      return $template;
//    }
//      // ERROR management
//
//
//
//      ///////////////////////////////////////////////////////////////////////
//      //
//      // Store values, maybe we need it later.
//
//    $select   = $arr_result['data']['select'];
//    $from     = $arr_result['data']['from'];
//    $where    = $arr_result['data']['where'];
//    $orderBy  = $arr_result['data']['orderBy'];
//    $union    = $arr_result['data']['union'];
//      // Store values, maybe we need it later.
//
//
//
//      ///////////////////////////////////////////////////////////////////////
//      //
//      // Set ORDER BY to false - we like to order by PHP
//
//    $orderBy = false;
//      // #9917: Selecting a random sample from a set of rows
//    if( $conf_view['random'] == 1 )
//    {
//      $orderBy = 'rand( )';
//    }
//      // Set ORDER BY to false - we like to order by PHP
//
//
//
//      //////////////////////////////////////////////////////////////////////
//      //
//      // Execute the SQL query
//
//    $b_union = false;
//    if( $union )
//    {
//        // We have a UNION. Maybe because there are synonyms.
//      $query   = $union;
//      $b_union = true;
//    }
//    if( ! $union )
//    {
//      $query = $GLOBALS['TYPO3_DB']->SELECTquery
//                                      (
//                                        $select,
//                                        $from,
//                                        $where,
//                                        $groupBy="",
//                                        $orderBy,
//                                        $limit="",
//                                        $uidIndexField=""
//                                      );
//    }
//
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'before $GLOBALS[TYPO3_DB]->sql_query( )' );
//    $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
//    $error = $GLOBALS['TYPO3_DB']->sql_error( );
//
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
//    $this->pObj->timeTracking_prompt( $query );
//if( $this->pObj->bool_accessByIP )
//{
//  die( $query );
//}
//
//    if( $error )
//    {
//      if( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog('[ERROR/SQL] ' . $query,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] ' . $error,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' .
//                        $this->pObj->pi_getLL( 'drs_security' ) . '</p>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
//      }
//      if( ! $this->pObj->b_drs_error )
//      {
//        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' .
//                        $this->pObj->pi_getLL( 'drs_sql_prompt' ) . '</p>';
//      }
//      $str_header  = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
//      $arr_return['error']['status'] = true;
//      $arr_return['error']['header'] = $str_warn . $str_header;
//      $arr_return['error']['prompt'] = $str_prompt;
//      return $arr_return;
//    }
//      // Execute the SQL query
//
//
//
//      //////////////////////////////////////////////////////////////////////
//      //
//      // DRS - Development Reporting System
//
//    if( $this->pObj->b_drs_sql )
//    {
//      t3lib_div::devlog( '[OK/SQL] ' . $query,  $this->pObj->extKey, -1 );
//      t3lib_div::devlog( '[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query ' .
//                          'in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1 );
//    }
//      // DRS - Development Reporting System
//
//
//
//      //////////////////////////////////////////////////////////////////////
//      //
//      // Workaround filter and localisation - Bugfix #9024
//
//      /*
//       * Description of the bug
//       *
//       * If we have a localised website
//       * and the user has selected a non default language
//       * and the user has selected a filter
//       * than the query below will select only default language records
//       */
//
//      // User selected a non default language
//    if( $this->pObj->objLocalise->int_localisation_mode >= 3 )
//    {
//        // User selected a filter
//      if( ! empty( $this->pObj->arr_andWhereFilter ) )
//      {
//        if( ! $b_union && ! $this->pObj->b_sql_manual )
//        {
//          $arr_where = null;
//          list( $table, $field ) = explode( '.', $this->pObj->arrLocalTable['uid'] );
//            // Get the field names for sys_language_content and for l10n_parent
//          $arr_localise['id_field']   = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
//          $arr_localise['pid_field']  = $GLOBALS['TCA'][$table]['ctrl'][' '];
//            // Get the field names for sys_language_content and for l10n_parent
//
//            // 13505, 110302, dwildt
//          $where = null;
//          if( $arr_localise['id_field'] && $arr_localise['pid_field'] )
//          {
//            while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
//            {
//              $uid          = $row[$table . '.' . $field];
//              $arr_where[]  = '(' . $table . '.' . $field . ' = ' . $uid .
//                              ' OR ' . $table . '.' . $arr_localise['pid_field'] . ' = '. $uid . ')';
//                                // 13573, 110303, dwildt
//            }
//            $where    = implode( ' OR ', $arr_where );
//            $where    = '(' . $where . ')';
//            $andWhere = $this->pObj->objLocalise->localisationFields_where( $table );
//              // 13505, 110302, dwildt
//            if( ! $andWhere )
//            {
//              $andWhere = 1;
//            }
//            $where = $where . ' AND ' . $andWhere;
//            $query = $GLOBALS['TYPO3_DB']->SELECTquery
//                                            (
//                                              $select,
//                                              $from,
//                                              $where,
//                                              $groupBy="",
//                                              $orderBy,
//                                              $limit="",
//                                              $uidIndexField=""
//                                            );
//            $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
//            $error = $GLOBALS['TYPO3_DB']->sql_error( );
//
//              // DRS - Development Reporting System
//            if( $this->pObj->b_drs_sql )
//            {
//              $prompt = 'Bugfix #9024 - Next query for localisation consolidation:';
//              t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
//              t3lib_div::devlog( '[INFO/SQL] ' . $query,  $this->pObj->extKey, 0 );
//              $prompt = 'Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.';
//              t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
//            }
//              // Prompt the expired time to devlog
//            $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
//              // DRS - Development Reporting System
//          }
//
//        }
//        if( $b_union )
//        {
//          if( $this->pObj->b_drs_error )
//          {
//            $prompt = 'User has selected a non default language. User has selected a filter too. ' .
//                      'And we have a query with UNIONs. It isn\'t possible to display localised records ' .
//                      'proper! We are sorry!';
//            t3lib_div::devlog( '[ERROR/FILTER] ' . $prompt,  $this->pObj->extKey, 3 );
//          }
//        }
//        if( $this->pObj->b_sql_manual )
//        {
//          if( $this->pObj->b_drs_error )
//          {
//            $prompt = 'User has selected a non default language. User has selected a filter too. ' .
//                      'And we have a query with UNIONs. And we have a manual generated query. ' .
//                      'It isn\'t possible to display localised records proper!';
//            t3lib_div::devlog( '[ERROR/FILTER] ' . $prompt,  $this->pObj->extKey, 3 );
//          }
//        }
//      }
//        // User selected a filter
//    }
//      // User selected a non default language
//
//    if( $error )
//    {
//      if( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog( '[ERROR/SQL] ' . $query,  $this->pObj->extKey, 3 );
//        t3lib_div::devlog( '[ERROR/SQL] ' . $error,  $this->pObj->extKey, 3 );
//        t3lib_div::devlog( '[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3 );
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' .
//                        $this->pObj->pi_getLL( 'drs_security' ) . '</p>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
//      }
//      if( ! $this->pObj->b_drs_error )
//      {
//        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' .
//                      $this->pObj->pi_getLL( 'drs_sql_prompt' ) . '</p>';
//      }
//      $str_header  = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
//      $arr_return['error']['status'] = true;
//      $arr_return['error']['header'] = $str_warn . $str_header;
//      $arr_return['error']['prompt'] = $str_prompt;
//      return $arr_return;
//    }
//      // Workaround filter and localisation - Bugfix #9024
//
//
//
//      ////////////////////////////////////
//      //
//      // Building $rows
//
//    $arr_table_realnames = $conf_view['aliases.']['tables.'];
//
//      // Do we have aliases?
//    if( is_array( $arr_table_realnames ) )
//    {
//        // Yes, we have aliases.
//      $i_row = 0;
//      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
//      {
//        foreach( $row as $str_tablealias_field => $value )
//        {
//          $arr_tablealias_field = explode('.', $str_tablealias_field);   // table_1.sv_name
//          $str_tablealias       = $arr_tablealias_field[0];              // table_1
//          $str_field            = $arr_tablealias_field[1];              // sv_name
//          $str_table            = $arr_table_realnames[$str_tablealias]; // tx_civserv_service
//          $str_table_field      = $str_table . '.' . $str_field;         // tx_civserv_service.sv_name
//          if( $str_table_field == '.' )
//          {
//            $str_table_field = $str_tablealias_field;
//          }
//          $rows[$i_row][$str_table_field] = $row[$str_tablealias_field];
//        }
//        $i_row++;
//      }
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We have aliases.' );
//        // Yes, we have aliases.
//    }
//    if( ! is_array( $arr_table_realnames ) )
//    {
//        // No, we don't have any alias.
//      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
//      {
//        $rows[] = $row;
//      }
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We haven\'t aliases.' );
//    }
//    $this->pObj->rows = $rows;
//      // Do we have aliases?
//
//      // SQL Free Result
//    $GLOBALS['TYPO3_DB']->sql_free_result( $res );
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after building rows.' );
//      // Building $rows
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Process synonyms if rows have synonyms
//
//    $arr_result = $this->pObj->objSqlFun->rows_with_synonyms( $rows );
//    $rows       = $arr_result['data']['rows'];
//    unset( $arr_result );
//    $this->pObj->rows = $rows;
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->rows_with_synonyms( )' );
//      // Process synonyms if rows have synonyms
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Consolidate Localisation
//
//    if( ! $this->pObj->b_sql_manual )
//    {
//      $rows = $this->pObj->objLocalise->consolidate_rows( $rows, $this->pObj->localTable );
//      $this->pObj->rows = $rows;
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objLocalise->consolidate_rows( )' );
//    }
//    if( $this->pObj->b_sql_manual && $this->pObj->b_drs_localisation )
//    {
//      $prompt = 'Manual SQL mode: Rows didn\'t get any localisation consolidation.';
//      t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
//    }
//      // Consolidate Localisation
//
//
//
//      ///////////////////////////////////////////////////////////////
//      //
//      // Consolidate rows
//
//    if( ! $this->pObj->b_sql_manual )
//    {
//      $arr_result       = $this->pObj->objConsolidate->consolidate( $rows );
//      $rows             = $arr_result['data']['rows'];
//      $int_rows_wo_cons = $arr_result['data']['rows_wo_cons'];
//      $int_rows_wi_cons = $arr_result['data']['rows_wi_cons'];
//      unset($arr_result);
//      $this->pObj->rows = $rows;
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objConsolidate->consolidate( )' );
//    }
//    if( $this->pObj->b_sql_manual && $this->pObj->b_drs_localisation )
//    {
//      $prompt = 'Manual SQL mode: Rows didn\'t get any general consolidation.';
//      t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
//    }
//      // Consolidate rows
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Hook for handle the consolidated rows
//
//      // #12813, dwildt, 110205
//      // This hook is used by one foreign extension at least
//    if( is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
//    {
//        // DRS - Development Reporting System
//      if ( $this->pObj->b_drs_hooks )
//      {
//        $i_extensions = count( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] );
//        $arr_ext      = array_values( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] );
//        $csv_ext      = implode( ',', $arr_ext );
//        if( $i_extensions == 1 )
//        {
//          $prompt = 'The third party extension ' . $csv_ext . ' uses the HOOK rows_filter_values.';
//          t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
//          $prompt = 'In case of errors or strange behaviour please check this extension!';
//          t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
//        }
//        if( $i_extensions > 1 )
//        {
//          $prompt = 'The third party extensions ' . $csv_ext . ' use the HOOK rows_filter_values.';
//          t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
//          $prompt = 'In case of errors or strange behaviour please check this extenions!';
//          t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
//        }
//      }
//        // DRS - Development Reporting System
//
//      $_params = array( 'pObj' => &$this );
//      foreach( ( array ) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] as $_funcRef )
//      {
//        t3lib_div::callUserFunction( $_funcRef, $_params, $this );
//      }
//    }
//      // Any foreign extension is using this hook
//      // DRS - Development Reporting System
//    if( $this->pObj->b_drs_hooks )
//    {
//      if( ! is_array( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_values'] ) )
//      {
//        $prompt = 'Any third party extension doesn\'t use the HOOK rows_filter_values.';
//        t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
//        $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
//        t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
//      }
//    }
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after hook rows_filter_values' );
//      // DRS - Development Reporting System
//      // Any foreign extension is using this hook
//
//    $rows = $this->pObj->rows;
//      // Hook for handle the consolidated rows
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Ordering the rows
//
//    // #9917: Selecting a random sample from a set of rows
//    if( ! ( $conf_view['random'] == 1 ) )
//    {
//      $this->pObj->objMultisort->multisort_rows( );
//      $rows = $this->pObj->rows;
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objMultisort->multisort_rows( )' );
//    }
//      // Ordering the rows
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Ordering the children
//
//      // 13803, dwildt, 110312
//    $rows = $this->pObj->objMultisort->multisort_mm_children( $rows );
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objMultisort->multisort_mm_children( $rows )' );
//      // 120127, dwildt+
//    $this->pObj->rows = $rows;
//      // Ordering the children
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Store amount of rows for the pagebrowser
//
//    // 100429, dwildt: rowsPb isn't set. Debug the code!
//    if( isset( $rowsPb ) )
//    {
//      $rowsPb = false;
//    }
//
//    if( $rowsPb == false )
//    {
//      $rowsPb = count( $rows );
//    }
//      // Store amount of rows for the pagebrowser
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Order and edit the rows hierarchical
//
//    $b_hierarchical = $conf_view['functions.']['hierarchical'];
//    if( $b_hierarchical )
//    {
//      $rows = $this->pObj->objSqlFun->make_hierarchical( $rows );
//        // 120127, dwildt+
//      $this->pObj->rows = $rows;
//      if( $this->pObj->b_drs_sql )
//      {
//        t3lib_div::devlog( '[INFO/SQL] Result should ordered hierarchical.',  $this->pObj->extKey, 0 );
//      }
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->make_hierarchical( $rows )' );
//    }
//      // Order and edit the rows hierarchical
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // DRS - Show the first row
//
//    if( $this->pObj->b_drs_sql )
//    {
//      $str_prompt = 'Result of the first row: ' . PHP_EOL;
//      if( is_array( $rows ) && count( $rows ) > 0 )
//      {
//        reset( $rows );
//        foreach( $rows as $key => $value )
//        {
//          $str_prompt .= '[' . $key . ']: ' . htmlspecialchars( $value ) . ' ' . PHP_EOL;
//        }
//        t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
//      }
//    }
//      // DRS - Show the first row
//
//
//
//      ////////////////////////////////////////////////////////////////////////
//      //
//      // Filter - part II/II - HTML code / template
//
//      // Count hits, filter rows, update template
//
//    $this->pObj->objFilter->rows_wo_limit = $rows;
//    $arr_result = $this->pObj->objFilter->filter( $template );
//    if( $arr_result['error']['status'] )
//    {
//      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
//      return $this->pObj->pi_wrapInBaseClass( $prompt );
//    }
//    $template = $arr_result['data']['template'];
//      // 120127, dwildt+
//    $rows             = $arr_result['data']['rows'];
//    $this->pObj->rows = $rows;
//      // 120127, dwildt+
//    unset( $arr_result );
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->filter( )' );
//      // Filter - part II/II - HTML code / template
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Clean up: Delete fields, we don't want to display
//
//    $arr_result = $this->pObj->objSqlFun->rows_with_cleaned_up_fields( $rows );
//    $rows       = $arr_result['data']['rows'];
//    unset($arr_result);
//      // 110801, dwildt
//    $this->pObj->rows = $rows;
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->rows_with_cleaned_up_fields( )' );
//      // Clean up: Delete rows, we don't want to display
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // DRS - Show the first row
//
//    if( $this->pObj->b_drs_sql )
//    {
//      $str_prompt = 'Result of the first row: ' . PHP_EOL;
//      if( is_array( $rows ) && count( $rows ) > 0 )
//      {
//        reset( $rows );
//        foreach( $rows as $key => $value )
//        {
//          $str_prompt .= '[' . $key . ']: ' . htmlspecialchars( $value ) . ' ' . PHP_EOL;
//        }
//        t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
//      }
//    }
//      // DRS - Show the first row
//
//
//
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Hook for override the SQL result for for the list view
//
//      // This hook is used by one extension at least
//    if( is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] ) )
//    {
//        // DRS - Development Reporting System
//      if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_browser )
//      {
//        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
//        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
//        $csv_ext      = implode(',', $arr_ext);
//        if ($i_extensions > 1)
//        {
//          t3lib_div::devlog('[INFO/SQL] The third party extensions '.$csv_ext.' use the HOOK browser_list.', $this->pObj->extKey, -1);
//          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
//        }
//        else
//        {
//          t3lib_div::devlog('[INFO/SQL] The third party extension '.$csv_ext.' uses the HOOK browser_list.', $this->pObj->extKey, -1);
//          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1);
//        }
//      }
//        // DRS - Development Reporting System
//
//      //:todo: Proper Hook
////      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] as $_classRef)
////      {
////        $_procObj   = &t3lib_div::getUserObj($_classRef);
////        $this->pObj = $_procObj->browser_list($arr_data, $this);
////      }
//    }
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after hook browser_list' );
//      // Hook for override the SQL result for for the list view
//
//
//
//      ////////////////////////////////////////////////////////////////////////
//      //
//      // Prepaire array with links to single view
//
//    $csvLinkToSingle = $conf_view['csvLinkToSingleView'];
//    if (!$csvLinkToSingle)
//    {
//      $csvLinkToSingle = $conf_view['select'];
//      $csvLinkToSingle = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($csvLinkToSingle);
//        // Is there a statement, which should replaced with an alias?
//      if (is_array($conf_view['select.']['deal_as_table.']))
//      {
//        foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
//        {
//          $csvLinkToSingle = str_replace($arr_dealastable['statement'], $arr_dealastable['alias'], $csvLinkToSingle);
//          if ($this->pObj->b_drs_sql)
//          {
//            t3lib_div::devlog('[INFO/SQL] Used tables: Statement "'.$arr_dealastable['statement'].'" is replaced with "'.$arr_dealastable['alias'].'"', $this->pObj->extKey, 0);
//          }
//        }
//      }
//    }
//    if (!$csvLinkToSingle)
//    {
//      if ($this->pObj->b_drs_sql)
//      {
//        t3lib_div::devlog('[INFO/DRS] views.'.$viewWiDot.$mode.' hasn\'t any linkToSingleView.', $this->pObj->extKey, 0);
//        t3lib_div::devLog('[HELP/DRS] If you want a link to a single view, please configure views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
//      }
//    }
//    $arrLinkToSingleFields = explode(',', $csvLinkToSingle);
//    $this->pObj->arrLinkToSingle = array();
//    foreach((array) $arrLinkToSingleFields as $arrLinkToSingleField)
//    {
//      list($table, $field) = explode('.', trim($arrLinkToSingleField));
//      $this->pObj->arrLinkToSingle[] = $table.'.'.$field;
//    }
//
//      // Replace aliases in case of aliases
//    if (is_array($conf_view['aliases.']['tables.']))
//    {
//      foreach ($this->pObj->arrLinkToSingle as $i_key => $str_tablefield)
//      {
//        $this->pObj->arrLinkToSingle[$i_key] = $this->pObj->objSqlFun->get_sql_alias_before($str_tablefield);
//      }
//      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun->replace_tablealias($this->pObj->arrLinkToSingle);
//    }
//      // Replace aliases in case of aliases
//
//    $str_csvList = implode(', ', $this->pObj->arrLinkToSingle);
//    if ($this->pObj->b_drs_sql)
//    {
//      t3lib_div::devlog('[INFO/DRS] Fields which will get a link to a single view: '.$str_csvList.'.', $this->pObj->extKey, 0);
//      t3lib_div::devLog('[HELP/DRS] If you want to configure the field list, please use views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
//    }
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after Prepaire array with links to single view' );
//      // Prepaire array with links to single view
//
//
//
//      // #29370, 110831, dwildt-
////      /////////////////////////////////////
////      //
////      // Building the template
////
////      // HTML template
////    $str_marker = $this->pObj->lDisplayList['templateMarker'];
////    $template   = $this->pObj->cObj->getSubpart($template, $str_marker);
////      // HTML template
//      // #29370, 110831, dwildt-
//
//
//
//      // #29370, 110831, dwildt+
//      //////////////////////////////////////////////////////////////////////
//      //
//      // csv export
//
//      // #29370, 110831, dwildt+
//      // Remove the title in case of csv export
//    $str_marker = $this->pObj->lDisplayList['templateMarker'];
//    switch( $this->pObj->objExport->str_typeNum )
//    {
//      case( 'csv' ) :
//        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
//        {
//          t3lib_div::devlog('[INFO/TEMPLATING+EXPORT] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
//        }
//        $str_marker     = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
//        $template_path  = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
//        $template       = $this->pObj->cObj->fileResource($template_path);
//        break;
//      default:
//        // Do nothing;
//    }
//      // Remove the title in case of csv export
//      // csv export
//      // #29370, 110831, dwildt+
//
//
//
//      //////////////////////////////////////////////////////////////////////
//      //
//      // csv export
//
//      // #32654, 120212, dwildt+
//    $str_marker = $this->pObj->lDisplayList['templateMarker'];
//    switch( $this->pObj->objMap->str_typeNum )
//    {
//      case( 'map' ) :
//        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_map )
//        {
//          t3lib_div::devlog('[INFO/TEMPLATING+MAP] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
//        }
//        $str_marker     = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
//        $template_path  = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
//        $template       = $this->pObj->cObj->fileResource($template_path);
//        break;
//      default:
//        // Do nothing;
//    }
//      // csv export
//
//
//
//      // #29370, 110831, dwildt+
//      /////////////////////////////////////
//      //
//      // Building the template
//
//      // HTML template
//    $template   = $this->pObj->cObj->getSubpart( $template, $str_marker );
//      // HTML template
//      // #29370, 110831, dwildt+
//
//
//
//    if( $str_marker == '###TEMPLATE_CSV###' )
//    {
//      if( empty( $template ) )
//      {
//        $prompt = '<div style="border:2em solid red;color:red;padding:2em;text-align:center;">
//            <h1>
//              TYPO3 Browser Error
//            </h1>
//            <h2>
//              EN: Subpart is missing
//            </h2>
//            <p>
//              English: Current HTML template doesn\'t contain the subpart ###TEMPLATE_CSV###.<br />
//              Please take care of a proper template.<br />
//            </p>
//            <h2>
//              DE: Subpart fehlt
//            </h2>
//            <p>
//              Deutsch: Dem aktuellen HTML-Template fehlt der Subpart ###TEMPLATE_CSV###.<br />
//              Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
//            </p>
//          </div>';
//        die( $prompt );
//      }
//    }
////    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
////    if ( ! ( $pos === false ) )
////    {
////      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $template );
////      die( );
////    }
//
//
//
//      // HTML search form
//      // #9659, 101011, fsander
//    //$bool_display = $this->pObj->objFlexform->bool_searchForm;
//    $bool_display = $this->pObj->objFlexform->bool_searchForm && $this->pObj->segment['searchform'];
//    $template     = $this->pObj->objTemplate->tmplSearchBox( $template, $bool_display );
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objTemplate->tmplSearchBox( )' );
//      // HTML search form
//
//
//
//      /////////////////////////////////////////////////////////////////
//      //
//      // Extension pi5: +Browser Calendar
//
//      // Will executed in case, that the Browser is extended with the Browser Calendar user Interface
//    $arr_result   = $this->pObj->objCal->cal( $rows, $template );
//    $bool_success = $arr_result['success'];
//    if( $bool_success )
//    {
//      $rows         = $arr_result['rows'];
//      $template     = $arr_result['template'];
//      $this->pObj->objTemplate->ignore_empty_rows_rule = true;
//      if ($this->pObj->b_drs_cal || $this->pObj->b_drs_templating)
//      {
//        t3lib_div::devLog('[INFO/TEMPLATING/CAL/UI]: +Browser Calendar User Interface is loaded.', $this->pObj->extKey, 0);
//      }
//      if ($this->pObj->b_drs_warn)
//      {
//        t3lib_div::devLog('[WARN/TEMPLATING/CAL/UI]: +Browser Calendar set ignore_empty_rows_rule to true!', $this->pObj->extKey, 2);
//      }
//    }
//    $this->pObj->rows = $rows;
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objCal->cal( )' );
//      // Extension pi5: +Browser Calendar
//
//
//
//      /////////////////////////////////////
//      //
//      // HTML a-z-browser
//
//    $arr_data['template']       = $template;
//      // 110801, dwildt
//    //$arr_data['rows']           = $rows;
//    $arr_data['rows']           = $this->pObj->rows;
//    $arr_result = $this->pObj->objNavi->azBrowser( $arr_data );
//    if ($arr_result['error']['status'])
//    {
//      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
//      return $this->pObj->pi_wrapInBaseClass($prompt);
//    }
//
//    $lArrTabs         = $arr_result['data']['azTabArray'];
//    $arr_tsId         = $arr_result['data']['tabIds'];
//    $template         = $arr_result['data']['template'];
//    $rows             = $arr_result['data']['rows'];
//      // 110801, dwildt +
//    $this->pObj->rows = $rows;
//    unset($arr_result);
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->azBrowser( )' );
//      // HTML a-z-browser
//
//
//
//      /////////////////////////////////////
//      //
//      // record browser
//
//    $arr_result = $this->pObj->objNavi->recordbrowser_set_session_data( $rows );
//    if ($arr_result['error']['status'])
//    {
//      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
//      return $this->pObj->pi_wrapInBaseClass( $prompt );
//    }
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->recordbrowser_set_session_data( )' );
//      // record browser
//
//
//
//      /////////////////////////////////////
//      //
//      // HTML page browser
//
//    $arr_data['azTabArray'] = $lArrTabs;
//    $arr_data['tabIds']     = $arr_tsId;
//    $arr_data['template']   = $template;
//    $arr_data['rows']       = $rows;
//
//    $arr_result = $this->pObj->objNavi->tmplPageBrowser( $arr_data );
//    unset($arr_data);
//    $template         = $arr_result['data']['template'];
//    $rows             = $arr_result['data']['rows'];
//      // 110801, dwildt +
//    $this->pObj->rows = $rows;
//    unset($arr_result);
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->tmplPageBrowser( )' );
//      // HTML page browser
//
//      // HTML mode selector
//    $arr_data['template']     = $template;
//    $arr_data['arrModeItems'] = $this->pObj->arrModeItems;
//    $template = $this->pObj->objNavi->tmplModeSelector( $arr_data );
//    unset($arr_data);
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->tmplModeSelector( )' );
//      // HTML mode selector
//      // Building the template
//
//
//
//      ///////////////////////////////////////////////
//      //
//      // In case of limit, limit the rows
//
//    if( isset( $conf_view['limit'] ) )
//    {
//      $arr_limit        = explode(',', $conf_view['limit']);
//      $int_start        = (int) trim($arr_limit[0]);
//      $int_amount       = (int) trim($arr_limit[1]);
//      $int_counter      = 0;
//      $int_remove_start = $int_start;
//      $int_remove_end   = $int_start + $int_amount;
//      $drs_rows_before  = count($rows);
//      if (is_array($rows))
//      {
//        foreach ($rows as $row => $elements)
//        {
//          if ($int_counter < $int_remove_start || $int_counter >= $int_remove_end)
//          {
//            unset($rows[$row]);
//          }
//          $int_counter++;
//        }
//      }
//      $drs_rows_after = count($rows);
//
//        // DRS - Development Reporting System
//      if ($drs_rows_after != $drs_rows_before)
//      {
//        $removed_rows = $drs_rows_before - $drs_rows_after;
//        if ($this->pObj->b_drs_templating)
//        {
//          t3lib_div::devlog('[INFO/TEMPLATING] We have a limit: '.$conf_view['limit'].'<br />'.
//            ' #'.$removed_rows.' rows were removed.',  $this->pObj->extKey, 0);
//        }
//      }
//        // Prompt the expired time to devlog
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: in case of a limit' );
//        // DRS - Development Reporting System
//    }
//    if( ! isset( $conf_view['limit'] ) )
//    {
//      if ($this->pObj->b_drs_templating)
//      {
//        t3lib_div::devlog('[INFO/TEMPLATING] We don\'t have any limit.<br />'.
//          ' #0 rows were removed. We have #'.count($rows).' rows.',  $this->pObj->extKey, 0);
//      }
//    }
//      // In case of limit, limit the rows
//
//
//
//      ////////////////////////////////////////////////////////////////////////
//      //
//      // HTML records
//
//    $template = $this->pObj->objTemplate->tmplListview($template, $rows);
//      // Prompt the expired time to devlog
//    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objTemplate->tmplListview( )' );
//      // HTML records
//
//
//
//    return $template;
//  }









  /**
 * Display a single item from the database
 *
 * @param	string		$template: HTML template with TYPO3 subparts and markers
 * @return	void
 * @version 3.6.3
 */
  function singleView( )
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $cObj = $this->pObj->cObj;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];

    $template   = $this->pObj->str_template_raw;


      /////////////////////////////////////
      //
      // Do we have configured views?

    $maxModes = count($conf['views.'][$viewWiDot]);
    if (!$maxModes || $maxModes == 0)
    {
      if ($this->pObj->b_drs_error) {
        t3lib_div::devlog('[ERROR/DRS] There is no '.$view.' view.', $this->pObj->extKey, 3);
        t3lib_div::devLog('[HELP/DRS] Did you included the static template from this extensions?', $this->pObj->extKey, 1);
        $tsArray = 'plugin.'.$this->pObj->prefixId.'.views.'.$view;
        t3lib_div::devLog('[HELP/DRS] Did you configure '.$tsArray.'?', $this->pObj->extKey, 1);
        t3lib_div::devLog('[WARN/DRS] ABORTED', $this->pObj->extKey, 2);
      }
      return false;
    }
      // Do we have configured views?



      /////////////////////////////////////
      //
      // Do we have an existing mode?

    if (!$maxModes || $maxModes == 0)
    {
      exit;
    }
    if ($mode > $maxModes)
    {
      $mode = 1;
    }
      // Do we have an existing mode?



      /////////////////////////////////////
      //
      // Overwrite global general_stdWrap

      // #12471, 110123, dwildt
    if (is_array($conf_view['general_stdWrap.'])) {
      $this->pObj->conf['general_stdWrap.'] = $conf_view['general_stdWrap.'];
    }
      // Overwrite global general_stdWrap



      /////////////////////////////////////
      //
      // Get the local or global displaySingle

    if (is_array($conf_view['displaySingle.']))
    {
      $this->pObj->lDisplaySingle = $conf_view['displaySingle.'];
    }
    else
    {
      $this->pObj->lDisplaySingle = $this->pObj->conf['displaySingle.'];
    }
      // Get the local or global displaySingle



      /////////////////////////////////////
      //
      // Get the local or global displaySingle.display

    if (is_array($conf_view['displaySingle.']['display.']))
    {
      $this->pObj->lDisplay = $conf_view['displaySingle.']['display.'];
    }
    else
    {
      $this->pObj->lDisplay = $conf['displaySingle.']['display.'];
    }
      // Get the local or global displaySingle.display



      /////////////////////////////////////
      //
      // Set global SQL values

    $arr_result = $this->pObj->objSqlFun->global_all();
    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
      // Set global SQL values



      /////////////////////////////////////
      //
      // SQL with manual configuration or autmatically configuration

    if ($this->pObj->b_sql_manual)
    {
        // Process the query building in case of a manual configuration with SELECT, FROM and WHERE and maybe JOINS
      $arr_result = $this->pObj->objSqlMan->get_query_array($this);
    }

      // We don't have a manual configuration
    if (!$this->pObj->b_sql_manual)
    {
        // Process the query building automatically
      $arr_result = $this->pObj->objSqlAut->get_query_array();
    }

    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
      // SQL with manual configuration or autmatically configuration



    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
    $orderBy  = $arr_result['data']['orderBy'];
    unset($arr_result);



      /////////////////////////////////////
      //
      // Process SQL query: Get the record(s)

    $groupBy  = '';
    $orderBy  = '';
    $limit    = '';
    $query    = $GLOBALS['TYPO3_DB']->SELECTquery       ($select, $from, $where, $groupBy, $orderBy, $limit, $uidIndexField="");

    $res   = $GLOBALS['TYPO3_DB']->sql_query($query);
    $error = $GLOBALS['TYPO3_DB']->sql_error();

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

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] '.$query,  $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1);
    }
      // Process SQL query: Get the record(s)



      ////////////////////////////////////
      //
      // Building $rows

    $arr_table_realnames = $conf_view['aliases.']['tables.'];

      // Do we have aliases?
    if (is_array($arr_table_realnames))
    {
        // Yes, we have aliases.
      $i_row = 0;
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        foreach ($row as $str_tablealias_field => $value)
        {
          $arr_tablealias_field = explode('.', $str_tablealias_field);   // table_1.sv_name
          $str_tablealias       = $arr_tablealias_field[0];              // table_1
          $str_field            = $arr_tablealias_field[1];              // sv_name
          $str_table            = $arr_table_realnames[$str_tablealias]; // tx_civserv_service
          $str_table_field      = $str_table.'.'.$str_field;             // tx_civserv_service.sv_name
          if ($str_table_field == '.')
          {
            $str_table_field = $str_tablealias_field;
          }
          $rows[$i_row][$str_table_field] = $row[$str_tablealias_field];
        }
        $i_row++;
      }
        // Yes, we have aliases.
    }
    else
    {
        // No, we don't have any alias.
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        $rows[] = $row;
      }
    }
    // Do we have aliases?



      ////////////////////////////////////
      //
      // SQL Free Result

    $GLOBALS['TYPO3_DB']->sql_free_result($res);
      // SQL Free Result



      /////////////////////////////////////////////////////////////////
      //
      // Process synonyms if rows have synonyms

    $arr_result = $this->pObj->objSqlFun->rows_with_synonyms($rows);
    $rows       = $arr_result['data']['rows'];
    unset($arr_result);
      // Process synonyms if rows have synonyms



      /////////////////////////////////////////////////////////////////
      //
      // Consolidate Localisation

    $rows = $this->pObj->objLocalise->consolidate_rows($rows, $this->pObj->localTable);
    $this->pObj->rows = $rows;
      // Consolidate Localisation



      ///////////////////////////////////////////////////////////////
      //
      // Consolidate rows

      // 100429, dwildt - Bugfixing: Consolidate rows was missing upto 3.2.2
    if (!$this->pObj->b_sql_manual)
    {
      $arr_result       = $this->pObj->objConsolidate->consolidate($rows);
      $rows             = $arr_result['data']['rows'];
      $int_rows_wo_cons = $arr_result['data']['rows_wo_cons'];
      $int_rows_wi_cons = $arr_result['data']['rows_wi_cons'];
      unset($arr_result);
      $this->pObj->rows = $rows;
    }
    if ($this->pObj->b_sql_manual)
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/SQL] Manual SQL mode: Rows didn\'t get any general consolidation.',  $this->pObj->extKey, 0);
      }
    }
      // Consolidate rows



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After consolidate rows: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      /////////////////////////////////////////////////////////////////
      //
      // #9727: Ordering the children

      // 13803, dwildt, 110312
    $rows = $this->pObj->objMultisort->multisort_mm_children($rows);
    //$rows = $this->pObj->objMultisort->multisort_mm_children_single($rows);
    $this->pObj->rows = $rows;
      // #9727: Ordering the children



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After multisort_mm_children(): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      /////////////////////////////////////////////////////////////////
      //
      // #9838: Simplified relation building

    $this->pObj->objConsolidate->children_relation();
    $rows = $this->pObj->rows;
      // #9838: Simplified relation building



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After children_relation(): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      /////////////////////////////////////
      //
      // Hook for override the SQL result for for the single view

    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']))
    {
      // This hook is used by one extension at least
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_browser)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']);
        $arr_ext     = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']);
        $csv_ext     = implode(',', $arr_ext);
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/SQL] The third party extensions '.$csv_ext.' use the HOOK browser_single.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
        else
        {
          t3lib_div::devlog('[INFO/SQL] The third party extension '.$csv_ext.' uses the HOOK browser_single.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1);
        }
      }
// :todo: Proper Hook
//      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single'] as $_classRef)
//      {
//        $_procObj   = &t3lib_div::getUserObj($_classRef);
//        $this       = $_procObj->browser_single($arr_data, $this);
//      }
    }
      // Hook for override the SQL result for for the single view



      /////////////////////////////////////
      //
      // DRS - Development Reporting System

    $bool_displayFirstRow = false;
    if (count($rows) == 0)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[WARN/SQL] Result is 0 rows! But query is OK.', $this->pObj->extKey, 2);
      }
    }
    if (!$rows)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[WARN/SQL] Result is 0 rows (false)! But query is OK.', $this->pObj->extKey, 2);
      }
    }
    if (count($rows) == 1 && $rows)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SQL] Result: 1 record.', $this->pObj->extKey, 0);
        $bool_displayFirstRow = true;
      }
    }
    if (count($rows) > 1)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SQL] Result: '.count($rows).' records.<br />You must have 1:n relations.', $this->pObj->extKey, 0);
        $bool_displayFirstRow = true;
      }
    }
    if ($bool_displayFirstRow && is_array($rows))
    {
      t3lib_div::devlog('[INFO/SQL] Result of the row is:', $this->pObj->extKey, 0);
      reset($rows);
      $firstKey = key($rows);
      foreach ($rows[$firstKey] as $key => $value)
      {
        $value = htmlspecialchars($value);
        if (strlen($value) > $this->pObj->i_drs_max_sql_result_len)
        {
          $value = substr($value, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
        }
        t3lib_div::devlog('[INFO/SQL] ['.$key.']: '.$value, $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System



      /////////////////////////////////////
      //
      // Building the template

      // It is possible to process one subpart only!
      // If you want to process more than one subpart, the included subparts and markes have to be unique
      // in the template file - in the HTML file. And the code in this class has to be modified.
    $str_marker = $this->pObj->lDisplaySingle['templateMarker'];
    $template   = $this->pObj->cObj->getSubpart($template, $str_marker);

    $this->pObj->lDisplayType = 'displaySingle.';
    if (is_array($conf['views.'][$viewWiDot][$mode.'.'][$this->pObj->lDisplayType]['display.'])) {
      $this->pObj->lDisplay = $conf['views.'][$viewWiDot][$mode.'.'][$this->pObj->lDisplayType]['display.'];
    } else {
      $this->pObj->lDisplay = $conf[$this->pObj->lDisplayType]['display.'];
    }

    // HTML mode selector
    $arr_data['template']     = $template;
    $arr_data['arrModeItems'] = $this->pObj->arrModeItems;
    $template = $this->pObj->objNavi->tmplModeSelector($arr_data);
    unset($arr_data);
      // Building the template



      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //
      // Do we have a HTML template with markers or a Typoscript Template Container (TTC)?

    $b_ttc = false;
    // dwildt, 101012
    if(is_array($conf['views.'][$viewWiDot][$mode.'.']))
    {
      foreach ($conf['views.'][$viewWiDot][$mode.'.'] as $ts_key => $ts_value)
      {
        if ($ts_value == 'TT_CONTAINER')
        {
          $b_ttc = true;
          break;
        }
      }
    }

      // Typoscript Template Container (TTC)
    if ($b_ttc)
    {
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] We have one TT_CONTAINER at least.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We don\'t process the default TypoScript Template Marker.', $this->pObj->extKey, 0);
      }
      $arr_result = $this->pObj->objTTContainer->main($rows);
      if ($arr_result['error']['status'])
      {
        $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
        return $this->pObj->pi_wrapInBaseClass($prompt);
      }
      $template = $arr_result['data']['template'];
      unset($arr_result);
    }
      // Typoscript Template Container (TTC)

      // HTML Template with markers
    if (!$b_ttc)
    {
      // We have an old style template with HTML and markers
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] We don\'t have any TT_CONTAINER.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We don\'t process the TypoScript Template Container (TTC).', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We process the default TypoScript Template Marker.', $this->pObj->extKey, 0);
      }
      $template = $this->pObj->objTemplate->tmplSingleview($template, $rows);
    }
      // HTML Template with markers
      // Do we have a HTML template with markers or a Typoscript Template Container (TTC)?



      //////////////////////////////////////////////////////////////////////////
      //
      // Record Browser

    $template = $this->pObj->objNavi->recordbrowser_get($template);
      // Record Browser



      //////////////////////////////////////////////////////////////////////////
      //
      // Statistics: Count the visit

    $this->pObj->objStat->countViewSingleRecord( );
      // Statistics: Count the visit



    return $template;
  }









  /***********************************************
   *
   * Helper
   *
   **********************************************/




  /**
 * displayThePlugin( ): The Method checks, if the plugin should controlled by URL parameters.
 *                      Parameters are defined in the flexform or TypoScript.
 *                      Conditions
 *                      * URL Parameter is in the list for hiding this plugin
 *                        returns false
 *                      * URL Parameter is in the list for displaying this plugin
 *                        returns true, if it is in the list
 *                        returns false, if it isn't in the list
 *                      * If a paremeter is defined like tx_browser_pi1[showUid],
 *                        the method doesn't check any value of the GP parameter
 *                      * If a paremeter is defined like tx_browser_pi1[showUid]=123
 *                        the method checks the value of the GP parameter.
 *                        It returns true only, if value is met.
 *                      * If a paremeter is defined like tx_browser_pi1[*],
 *                        the methord returns true, if the GP parameter tx_browser_pi1 contains
 *                        one element at least.
 *                      It takes account of GP parameters from first to third level only.
 *                      It takes account for any paramter, but not piVars only.
 *
 *                      * if the plugin should not controlled by URL parameter or
 *                      * if the plugin meets the conditions
 *                      False
 *                      * if the plugin doesn't meet the conditions
 *
 * @return	boolean		True,
 * @version 3.9.3
 * @since 3.9.3
 */
  public function displayThePlugin( )
  {
    $sheet    = 'sDEF';
    $field_1  = 'controlling';
    $field_2  = 'enabled';



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN true: Plugin shouldn't controlled by URL parameters

    $coa_name = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2];
    $coa_conf = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'];
    $value    = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
    if( ! $value )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'RETURN. Plugin shouldn\'t controlled by URL parameters';
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN true. Plugin shouldn't controlled by URL parameters



      //////////////////////////////////////////////////////////////////////
      //
      // Build the arr_GPparams

      // Merge $_POST and $_GET ($Post has precedence)
    $GP = t3lib_div::_POST() + t3lib_div::_GET();
    $GP = array_unique( $GP );
      // Merge $_POST and $_GET ($Post has precedence)

    $arr_GPparam  = null;
    $str_GPparam  = null;
      // LOOP first level
    foreach( $GP as $key_01 => $value_01 )
    {
        // Element is an array
      if( is_array( $value_01 ) )
      {
          // LOOP second level
        foreach( $value_01 as $key_02 => $value_02 )
        {
            // Element is an array
          if( is_array( $value_02 ) )
          {
              // LOOP third level
            foreach( $value_02 as $key_03 => $value_03 )
            {
                // Element is an array
                // ERROR: param array is an array. This won't handled.
              if( is_array( $value_03 ) )
              {
                if ( $this->pObj->b_drs_error )
                {
                  $param      = $key_01 . '[' . $key_02 . '][' . $key_03 . ']';
                  $prompt_01  = 'ERROR: URL parameter can\'t evaluate. Parameter is a forth dimensional array at least: \'' . $param . '\'';
                  $prompt_02  = 'HELP: The PHP code of the browser has to adapt to this requirement. Please publish it in the typo3-browser-forum.de.';
                  t3lib_div::devLog( '[ERROR/TEMPLATING] ' . $prompt_01, $this->pObj->extKey, 3 );
                  t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt_02, $this->pObj->extKey, 1 );
                }
                $arr_GPparam[$key_01 . '[' . $key_02 . '][' . $key_03 . '][*]'] = null;
                $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][' . $key_03 . '][*]';
                continue;
              }
                // Element is an array
                // ERROR: param array is an array. This won't handled.
                // Set the param array
              $arr_GPparam[$key_01 . '[' . $key_02 . '][' . $key_03 . ']'] = $value_03;
              $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][' . $key_03 . ']=' . $value_03;
            }
              // LOOP third level
            $arr_GPparam[$key_01 . '[' . $key_02 . '][*]'] = null;
            $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][*]';
            continue;
          }
            // Set the param array
          $arr_GPparam[$key_01 . '[' . $key_02 . ']'] = $value_02;
          $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . ']=' . $value_02;
        }
          // LOOP second level
        $arr_GPparam[$key_01 . '[*]'] = null;
        $str_GPparam = $str_GPparam . ', ' . $key_01 . '[*]';
        continue;
      }
        // Element is an array
        // Set the param
      $arr_GPparam[$key_01] = $value_01;
      $str_GPparam = $str_GPparam . ', ' . $key_01 . '=' . $value_01;
    }
      // LOOP first level
    if( $str_GPparam )
    {
      $str_GPparam = ltrim( $str_GPparam, ', ' );
    }
      // Build the arr_GPparams



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN false: Parameter is in the list for hiding this plugin

      // Get the csv list as an array out of the TypoScript
    $field_1    = 'controlling';
    $field_2    = 'adjustment';
    $field_3    = 'hide_if_in_list';
    $field      = $field_1 . '.' . $field_2. '.' . $field_3;
    $coa_name   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3];
    $coa_conf   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'];
    $csvValues  = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
    $csvArray   = $this->pObj->objZz->getCSVasArray( $csvValues );
      // Get the csv list as an array out of the TypoScript

      // LOOP each parameter from csv list
    foreach( $csvArray as $param )
    {
        // CONTINUE $csvArray is empty
      if( empty ( $param ) )
      {
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'The list of URL parameter for hiding this plugin doesn\'t contain any parameter.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        continue;
      }
        // CONTINUE $csvArray is empty

        // Get key=value pair
      list( $paramKey, $paramValue) = explode( '=', $param );
      $paramKey   = trim( $paramKey );
      $paramValue = trim( $paramValue );
        // Get key=value pair

        // Key is part of the URL
      if( in_array( $paramKey, array_keys ( $arr_GPparam ) ) )
      {
          // SWITCH conditions
        switch( true )
        {
          case( ! ( $paramValue == '' ) ):
          case( ! ( $paramValue == null ) ):
              // A value is defined
              // RETURN false: value meets URL value
            if( $arr_GPparam[$paramKey] == $paramValue )
            {
              if ( $this->pObj->b_drs_templating )
              {
                $prompt = 'The list of URL parameter for hiding this plugin contains ' . $param . '.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
                $prompt = 'And ' . $param . ' is part of the URL. This plugin will hidden.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              }
              return false;
            }
              // RETURN false: value meets URL value
              // GO ON: value doesn't meet URL value
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of URL parameter for hiding this plugin: ' . $param . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'URL parameter: ' . $str_GPparam . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
              // GO ON: value doesn't meet URL value
            break;
              // A value is defined
          default:
              // RETURN false: any value isn't defined
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of URL parameter for hiding this plugin contains ' . $paramKey . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'And ' . $paramKey . ' is part of the URL. This plugin will hidden.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
            return false;
            break;
              // RETURN false: any value isn't defined
        }
          // SWITCH conditions
      }
        // Key is part of the URL
        // Key isn't part of the URL
      if( ! ( in_array( $paramKey, array_keys ( $arr_GPparam ) ) ) )
      {
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'The list of URL parameter for hiding this plugin contains \'' . $paramKey . '\'.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = $paramKey . ' isn\'t any part of the URL. This plugin won\'t hidden.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
      }
        // Key isn't part of the URL
    }
      // LOOP each parameter from csv list
      // RETURN false: Parameter is in the list for hiding this plugin



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN true or false: Parameter is in the list for displaying this plugin

      // Get the csv list as an array out of the TypoScript
    $field_1    = 'controlling';
    $field_2    = 'adjustment';
    $field_3    = 'display_if_in_list';
    $field      = $field_1 . '.' . $field_2. '.' . $field_3;
    $coa_name   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3];
    $coa_conf   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'];
    $csvValues  = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
    $csvArray   = $this->pObj->objZz->getCSVasArray( $csvValues );
      // Get the csv list as an array out of the TypoScript

      // RETURN true: $csvArray is empty
    if( empty ( $csvArray ) )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'The list of URL parameter for display this plugin doesn\'t contain any parameter.';
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
      continue;
    }
      // RETURN true: $csvArray is empty

      // LOOP each parameter from csv list
    foreach( $csvArray as $param )
    {
        // Get key=value pair
      list( $paramKey, $paramValue) = explode( '=', $param );
      $paramKey   = trim( $paramKey );
      $paramValue = trim( $paramValue );
        // Get key=value pair

        // Key is part of the URL
      if( in_array( $paramKey, array_keys ( $arr_GPparam ) ) )
      {
          // SWITCH conditions
        switch( true )
        {
          case( ! ( $paramValue == '' ) ):
          case( ! ( $paramValue == null ) ):
              // A value is defined
              // RETURN true: value meets URL value
            if( $arr_GPparam[$paramKey] == $paramValue )
            {
              if ( $this->pObj->b_drs_templating )
              {
                $prompt = 'The list of needed URL parameter for displaying this plugin contains ' . $param . '.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
                $prompt = 'And ' . $param . ' is part of the URL. This plugin will displayed.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              }
              return true;
            }
              // RETURN true: value meets URL value
            break;
              // A value is defined
          default:
              // RETURN true: any value isn't defined
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of needed URL parameter for displaying this plugin contains ' . $paramKey . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'And ' . $paramKey . ' isn\t part of the URL. This plugin will displayed.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
            return true;
            break;
              // RETURN true: any value isn't defined
        }
          // SWITCH conditions
      }
        // Key is part of the URL
    }
      // RETURN true or false: Parameter is in the list for displaying this plugin



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN false: Any Parameter of the list for displaying this plugin is part of the URL

// See $csvValues above
//      // Get the csv list as an array out of the TypoScript
//    $field_1    = 'controlling';
//    $field_2    = 'adjustment';
//    $field_3    = 'display_if_in_list';
//    $field      = $field_1 . '.' . $field_2. '.' . $field_3;
//    $coa_name   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3];
//    $coa_conf   = $this->pObj->conf['flexform.'][$sheet . '.'][$field_1 . '.'][$field_2 . '.'][$field_3 . '.'];
//    $csvValues  = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
//    $csvArray   = $this->pObj->objZz->getCSVasArray( $csvValues );
//      // Get the csv list as an array out of the TypoScript
// See $csvValues above

    switch( true )
    {
      case( ! ( $csvValues == '' ) ):
      case( ! ( $csvValues == null ) ):
          // Parameters are defined
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'This is the list of needed URL parameter for displaying this plugin: \'' . $csvValues . '\'.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'But any parameter is part of the URL. This plugin won\'t displayed.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        return false;
        break;
          // Parameters are defined
    }
      // RETURN false: Any Parameter of the list for displaying this plugin is part of the URL



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN true: This plugin doesn't need any URL parameter for displaying

    if ( $this->pObj->b_drs_templating )
    {
      $prompt = 'This plugin doesn\'t need any URL parameter for displaying.';
      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
    }
    return true;
      // RETURN true: This plugin doesn't need any URL parameter for displaying
  }















}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']);
}

?>
