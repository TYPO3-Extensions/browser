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
 * @subpackage  browser
 * @version 3.9.8
 * @since 1.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   67: class tx_browser_pi1_viewlist
 *  110:     function __construct( $parentObj )
 *
 *              SECTION: Building the views
 *  141:     function main( )
 * 1043:     function main_4x( )
 * 1183:     private function init( )
 * 1234:     private function check_view( )
 *
 *              SECTION: SQL
 * 1301:     private function sql( )
 * 1471:     private function sql_getQueryArray( )
 * 1506:     private function rows( )
 *
 *              SECTION: Content / Template
 * 1583:     private function content_setCSV( )
 * 1636:     private function content_setDefault( )
 *
 *              SECTION: Subparts
 * 1695:     private function subpart_setSearchbox( $filter )
 * 1713:     private function subpart_setSearchboxFilter( $filter )
 * 1756:     private function set_arrLinkToSingle( )
 *
 * TOTAL FUNCTIONS: 13
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
 * @since 1.0.0
 */
  function main( )
  {
      // DEVELOPMENT: Browser engine 4.x
    switch( $this->pObj->dev_browserEngine )
    {
      case( 4 ):
        return $this->main_4x( );
        break;
      case( 3 ):
      default:
          // Follow the workflow
        break;
    }
      // DEVELOPMENT: Browser engine 4.x



      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin listView( )' );

      // Short vars
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_view  = $this->conf_view;
      // Short vars



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

      // #30912, 120127, dwildt-
//    $arr_andWhereFilter = $this->pObj->objFilter->andWhere_filter();
//    if ( ! empty( $arr_andWhereFilter ) )
//    {
//      $this->pObj->arr_andWhereFilter = $arr_andWhereFilter;
//    }
      // #30912, 120127, dwildt-
      // #30912, 120127, dwildt+
    $this->pObj->objFilter->andWhere_filter( );
//    $arr_andWhereFilter = $this->pObj->objFilter->andWhere_filter( );
//    $this->pObj->dev_var_dump( $arr_andWhereFilter );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->andWhere_filter( )' );
      // Filter - part I/II: SQL andWhere statement




//    $arr_result = $this->sql( );
//    if( $arr_result['error']['status'] )
//    {
//      $template = $arr_result['error']['header'] . $arr_result['error']['prompt'];
//      return $template;
//    }



    $this->sql( );
    $res = $this->res;



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
        if( ( ! $this->bool_union ) && ( ! $this->pObj->b_sql_manual ) )
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
              // Prompt the expired time to devlog
            $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'before $GLOBALS[TYPO3_DB]->sql_query( )' );
            $tt_start = $this->pObj->tt_prevEndTime;
              // Prompt the expired time to devlog

              // Execute
            $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
            $error = $GLOBALS['TYPO3_DB']->sql_error( );
              // Execute

              // Prompt the expired time to devlog
            $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
            $this->pObj->timeTracking_prompt( $query );
            $tt_end = $this->pObj->tt_prevEndTime;
              // Prompt the expired time to devlog

              // Error management
            if( $error ) {
              $this->pObj->objSqlFun->query = $query;
              $this->pObj->objSqlFun->error = $error;
              $arr_result = $this->pObj->objSqlFun->prompt_error( );
              if( $arr_result['error']['status'] )
              {
                $template = $arr_result['error']['header'] . $arr_result['error']['prompt'];
                return $template;
              }
            }
              // Error management

              // DRS - Development Reporting System
            if( $this->pObj->b_drs_sql )
            {
              $prompt = 'Bugfix #9024 - Next query for localisation consolidation:';
              t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
              t3lib_div::devlog( '[OK/SQL] ' . $query,  $this->pObj->extKey, -1 );
              $prompt = 'Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.';
              t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
            }
              // DRS - Development Reporting System
          }

        }
        if( $this->bool_union )
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
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_result = $this->pObj->objSqlFun->prompt_error( );
      if( $arr_result['error']['status'] )
      {
        $template = $arr_result['error']['header'] . $arr_result['error']['prompt'];
        return $template;
      }
    }
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
      // Workaround filter and localisation - Bugfix #9024



      // Building $rows
    $this->rows( );
    $rows = $this->pObj->rows;
      // SQL Free Result
    $GLOBALS['TYPO3_DB']->sql_free_result( $this->res );
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

      // SQL mode automatically
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
      // SQL mode automatically

      // SQL mode manual
    if( $this->pObj->b_sql_manual && $this->pObj->b_drs_localisation )
    {
      $prompt = 'Manual SQL mode: Rows didn\'t get any general consolidation.';
      t3lib_div::devlog( '[WARN/SQL] ' . $prompt,  $this->pObj->extKey, 2 );
    }
      // SQL mode manual
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
      if( count ( ( array ) $rows ) > 0 )
      {
        reset( $rows );
        $firstKey = key( $rows );
        $str_prompt .= var_export( $rows[$firstKey], true );
      }
      t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
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
//    $this->pObj->dev_var_dump( $arr_result );
    $template = $arr_result['data']['template'];
      // 120127, dwildt+
    $rows             = $arr_result['data']['rows'];
    $this->pObj->rows = $rows;
      // 120127, dwildt+
    unset( $arr_result );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objFilter->filter( )' );
      // Count hits, filter rows, update template
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
      if( count ( ( array ) $rows ) > 0 )
      {
        reset( $rows );
        $firstKey = key( $rows );
        $str_prompt .= var_export( $rows[$firstKey], true );
      }
      t3lib_div::devlog('[INFO/SQL] ' . $str_prompt, $this->pObj->extKey, 0);
    }
      // DRS - Show the first row



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for override the SQL result for the list view

      // This hook is used by one extension at least
    if( is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] ) )
    {
        // DRS - Development Reporting System
      if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_navi )
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
      // Hook for override the SQL result for the list view



      // Set the global $arrLinkToSingle
    $this->set_arrLinkToSingle( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after Prepaire array with links to single view' );



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



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Get template for csv
    $str_marker = $this->pObj->lDisplayList['templateMarker'];
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/TEMPLATING+EXPORT] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
        }
        $str_marker     = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
        $template_path  = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
        $template       = $this->pObj->cObj->fileResource( $template_path );
        break;
      default:
        // Do nothing;
    }
      // Get template for csv
      // csv export
      // #29370, 110831, dwildt+



      //////////////////////////////////////////////////////////////////////
      //
      // csv map marker

      // #32654, 120212, dwildt+
      // Get template for csv
    // 120321, dwildt, 1-
    //$str_marker = $this->pObj->lDisplayList['templateMarker'];
    switch( $this->pObj->objMap->str_typeNum )
    {
      case( 'map' ) :
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_map )
        {
          t3lib_div::devlog('[INFO/TEMPLATING+MAP] ' . $str_marker . ' is ignored. ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
        }
        $str_marker     = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
        $template_path  = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
        $template       = $this->pObj->cObj->fileResource( $template_path );
        break;
      default:
        // Do nothing;
    }
      // Get template for csv
      // csv map marker



      /////////////////////////////////////
      //
      // Building the template

      // HTML template subpart
    $template = $this->pObj->cObj->getSubpart( $template, $str_marker );



    if( empty( $str_marker ) )
    {
      $prompt = '<div style="border:2em solid red;color:red;padding:2em;text-align:center;">
          <h1>
            TYPO3 Browser Error
          </h1>
          <h2>
            EN: Subpart of "' . $str_marker . '" is empty
          </h2>
          <p>
            English: Maybe the HTML template doesn\'t have the subpart?<br />
            Please take care of a proper TypoScript.<br />
          </p>
          <h2>
            DE: Subpart von "' . $str_marker . '" ist leer
          </h2>
          <p>
            Deutsch: Fehlt im HTML-Teplate der Subpart?<br />
            Bitte k&uuml;mmere Dich um ein korrektes TypoScript.<br />
          </p>
          <p>
            ' . __METHOD__ . ' (' . __LINE__ . ')
          </p>
        </div>';
      die( $prompt );
    }
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
            English: Current HTML template doesn\'t contain the subpart \'' . $str_marker . '\' .<br />
            Please take care of a proper template.<br />
          </p>
          <h2>
            DE: Subpart fehlt
          </h2>
          <p>
            Deutsch: Dem aktuellen HTML-Template fehlt der Subpart \'' . $str_marker . '\'.<br />
            Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
          </p>
          <p>
            ' . __METHOD__ . ' (' . __LINE__ . ')
          </p>
        </div>';
      die( $prompt );
    }



      // HTML search form
      // #9659, 101011, fsander
    //$bool_display = $this->pObj->objFlexform->bool_searchForm;
    $template     = $this->pObj->objTemplate->tmplSearchBox( $template );
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
      // HTML index browser

    $arr_data['template']       = $template;
      // 110801, dwildt
    //$arr_data['rows']           = $rows;
    $arr_data['rows']           = $this->pObj->rows;
    $arr_result = $this->pObj->objNavi->indexBrowser( $arr_data );
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass($prompt);
    }

    $lArrTabs         = $arr_result['data']['indexBrowserTabArray'];
    $arr_tsId         = $arr_result['data']['tabIds'];
    $template         = $arr_result['data']['template'];
    $rows             = $arr_result['data']['rows'];
      // 110801, dwildt +
    $this->pObj->rows = $rows;
    unset($arr_result);
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objNavi->indexBrowser( )' );
      // HTML index browser



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

    $arr_data['indexBrowserTabArray'] = $lArrTabs;
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

    $template = $this->pObj->objTemplate->tmplListview( $template, $rows );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objTemplate->tmplListview( )' );
      // HTML records



    return $template;
  }










  /**
 * main_4x( ): Display a search form, indexBrowser, pageBrowser and a list of records
 *
 * @return	string		$template : The processed HTML template
 * @version 3.9.8
 * @since 3.9.8
 */
  function main_4x( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );



      //////////////////////////////////////////////////////////////////
      //
      // Short vars

    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_view  = $this->conf_view;
      // Short vars



      //////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any list configured

    $prompt = $this->check_view( );
    if( $prompt )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $prompt;
    }
      // RETURN there isn't any list configured



      // Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
    $this->init( );

      // HTML content of the current template
    $this->content = $this->pObj->str_template_raw;



      //////////////////////////////////////////////////////////////////////
      //
      // Set SQL query parts in general and statements for rows

    $arr_result = $this->pObj->objSql->init( );
    if( $arr_result['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      $content = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      return $content;
    }
      // Set SQL query parts in general and statements for rows



    $this->sql_4x( );


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
          // Take the default template (the list view)
        $arr_result = $this->content_setDefault( );
        if( $arr_result['error']['status'] )
        {
            // Prompt the expired time to devlog
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
          $content = $arr_result['error']['header'] . $arr_result['error']['prompt'];
          return $content;
        }
        break;
          // CASE no csv
    }
      // Get template for csv
      // csv export versus list view
      // #29370, 110831, dwildt+



      // Get rows
      // Set rows



      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

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
  * SQL
  *
  **********************************************/









  /**
 * sql( ): Building the SQL query
 *
 * @return	array
 * @version 3.9.8
 * @since 1.0.0
 */
  private function sql( )
  {
    $conf_view = $this->conf_view;


      // Set the globals csvSelect, csvOrderBy and arrLocalTable
    $arr_result = $this->pObj->objSqlFun->global_all( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlFun->global_all( )' );
      // RETURN error
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    unset( $arr_result );
      // RETURN error
      // Set global SQL values



      // SQL query array
    $arr_result = $this->sql_getQueryArray( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
      // #33892, 120214, dwildt+
    $groupBy  = null;
    $orderBy  = $arr_result['data']['orderBy'];
      // #33892, 120214, dwildt+
    $limit    = null;
    $union    = $arr_result['data']['union'];
    unset( $arr_result );
      // SQL query array



      // Set ORDER BY to false - we like to order by PHP
//:TODO: 120214, performance: order by aktivieren
    $orderBy = false;
      // #9917: Selecting a random sample from a set of rows
    if( $conf_view['random'] == 1 )
    {
      $orderBy = 'rand( )';
    }
      // Set ORDER BY to false - we like to order by PHP



//  // #33892, 120214, dwildt+
//if( $this->pObj->bool_accessByIP )
//{
//  $orderBy  = $arr_result['data']['orderBy'];
//  $limit    = $conf_view['limit'];
//    // DRS
//  if( $this->pObj->b_drs_sql )
//  {
//    $prompt = 'orderBy: ' . $orderBy . '; limit: ' . $limit;
//    t3lib_div::devlog( '[INFO/DEVELOPMENT] ' . $prompt, $this->pObj->extKey, 0 );
//  }
//    // DRS
//}
//  // #33892, 120214, dwildt+



      // SQL query
    $this->bool_union = false;
      // Query: union case
    if( $union )
    {
        // We have a UNION. Maybe because there are synonyms.
      $query   = $union;
      $this->bool_union = true;
    }
      // Query: union case

      // Query: default case
    if( ! $union )
    {
      $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                      (
                                        $select,
                                        $from,
                                        $where,
                                        $groupBy,
                                        $orderBy,
                                        $limit,
                                        $uidIndexField=""
                                      );
    }
      // Query: default case
      // SQL query

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'before $GLOBALS[TYPO3_DB]->sql_query( )' );
    $tt_start = $this->pObj->tt_prevEndTime;
      // Prompt the expired time to devlog

      // Execute
    $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
      // Execute

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $GLOBALS[TYPO3_DB]->sql_query( )' );
    $this->pObj->timeTracking_prompt( $query );
    $tt_end = $this->pObj->tt_prevEndTime;
      // Prompt the expired time to devlog

      // Error management
    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      return $this->pObj->objSqlFun->prompt_error( );
    }
      // Error management

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[OK/SQL] ' . $query,  $this->pObj->extKey, -1 );
      t3lib_div::devlog( '[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query ' .
                          'in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1 );
    }
      // DRS - Development Reporting System
      // Execute the SQL query

if( $this->pObj->bool_accessByIP )
{
  if( ( $tt_end - $tt_start ) > 1000 )
  {
    if( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[ERROR/PERFROMANCE] Abort by development script!!!!!!!!',  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/PERFROMANCE] Abort by development script!!!!!!!!',  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/PERFROMANCE] Abort by development script!!!!!!!!',  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/PERFROMANCE] Abort by development script!!!!!!!!',  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/PERFROMANCE] Abort by development script!!!!!!!!',  $this->pObj->extKey, 3 );
    }
    $prompt = '<h1>Mehr als eine Sekunde</h1>' .
              '<p>' . __METHOD__ . ' (' . __LINE__ . '): </p>' .
              '<p>' . $query . '</p>';
    die( $prompt );
  }
}

    $this->res = $res;
    return false;

  }









  /**
 * sql( ): Building the SQL query
 *
 * @return	array
 * @version 3.9.12
 * @since   3.9.12
 */
  private function sql_4x( )
  {
    $conf_view = $this->conf_view;


      // Set the globals csvSelect, csvOrderBy and arrLocalTable
    $arr_result = $this->pObj->objSqlFun->global_all( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    unset( $arr_result );
      // Set global SQL values

      // SQL query array
    $arr_result = $this->sql_getQueryArray( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }

    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
      // #33892, 120214, dwildt+
    $groupBy  = null;
    $orderBy  = $arr_result['data']['orderBy'];
      // #33892, 120214, dwildt+
    $limit    = null;
    $union    = $arr_result['data']['union'];
    unset( $arr_result );
      // SQL query array



      // Set ORDER BY to false - we like to order by PHP
//:TODO: 120214, performance: order by aktivieren
    $orderBy = false;
      // #9917: Selecting a random sample from a set of rows
    if( $conf_view['random'] == 1 )
    {
      $orderBy = 'rand( )';
    }
      // Set ORDER BY to false - we like to order by PHP



      // SQL query
    $this->bool_union = false;
      // Query: union case
    if( $union )
    {
        // We have a UNION. Maybe because there are synonyms.
      $query   = $union;
      $this->bool_union = true;
    }
      // Query: union case

      // Query: default case
    if( ! $union )
    {
      $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                      (
                                        $select,
                                        $from,
                                        $where,
                                        $groupBy,
                                        $orderBy,
                                        $limit,
                                        $uidIndexField=""
                                      );
    }
      // Query: default case
      // SQL query


    return;

  }








  /**
 * sql_getQueryArray( ):
 *
 * @return	array
 * @version 3.9.8
 * @since 1.0.0
 */
  private function sql_getQueryArray( )
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









  /**
 * rows( ):
 *
 * @return	array
 * @version 3.9.8
 * @since 1.0.0
 */
  private function rows( )
  {
    $conf_view = $this->conf_view;
    $res       = $this->res;


      // Get aliases
    $arr_table_realnames = $conf_view['aliases.']['tables.'];

      // IF aliases
    if( is_array( $arr_table_realnames ) )
    {
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
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We have aliases.' );
    }
      // IF aliases

      // IF no aliases
    if( ! is_array( $arr_table_realnames ) )
    {
      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
      {
        $rows[] = $row;
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after: We haven\'t aliases.' );
    }
      // IF no aliases

    $this->pObj->rows = $rows;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after building rows.' );
      // Building $rows
  }









 /***********************************************
  *
  * Content / Template
  *
  **********************************************/



/**
 * content_setCSV( ): Sets content to CSV template
 *
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
 * @return	array         $arr_return: Contains an error message in case of an error
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
      
      // Set mode selector

      // Set page browser
    $arr_return = $this->subpart_setPageBrowser( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set page browser

    return;
  }



/**
 * content_dieIfEmpty( ): If content is empty, the methods will die the workflow
 *                      with a qualified prompt.
 *
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
  * Subparts
  *
  **********************************************/



/**
 * subpart_setSearchbox( ): Get the searchform. Part of content is generated
 *                          by the template class. Replace filter marker.
 *
 * @return	array           $arr_return : Error message in case of an error
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
 * @return	array                 $arr_return: Error message in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function subpart_setSearchboxFilter( )
  {
      // Default return value
    $arr_return = array( );

      // Get filter
    $arr_result = $this->pObj->objFltr4x->get( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    $filter = $arr_result['data']['filter'];
    unset( $arr_result );
      // Get filter

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
 * @return	array               $arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setIndexBrowser( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setIndexBrowser begin' );

    $arr_return = $this->pObj->objNaviIndexBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

    $content        = $arr_return['data']['content'];
    $marker         = $this->pObj->objNaviIndexBrowser->getMarkerIndexBrowser( );
    $this->content  = $this->pObj->cObj->substituteSubpart( $this->content, $marker, $content, true);

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setIndexBrowser end' );
    return;
  }



/**
 * subpart_setPageBrowser( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array               $arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setPageBrowser( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setPageBrowser begin' );

    $arr_return = $this->pObj->objNaviPageBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

    $content        = $arr_return['data']['content'];
    $this->content  = $this->pObj->cObj->substituteSubpart
                      (
                        $this->content,
                        '###PAGEBROWSER###',
                        $content,
                        true
                      );

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setPageBrowser end' );
    return;
  }









/**
 * set_arrLinkToSingle( ): Set the global $arrLinkToSingle
 *
 * @return	array
 * @version 3.9.8
 * @since 1.0.0
 */
  private function set_arrLinkToSingle( )
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
        $this->pObj->arrLinkToSingle[$i_key] = $this->pObj->objSqlFun->get_sql_alias_before( $str_tablefield );
      }
      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun->replace_tablealias( $this->pObj->arrLinkToSingle );
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']);
}

?>
