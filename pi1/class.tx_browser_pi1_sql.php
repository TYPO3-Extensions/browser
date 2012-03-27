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
 * The class tx_browser_pi1_sql bundles methods with a workflow for sql queries and rows.
 * statement with a FROM and a WHERE clause and maybe with the array JOINS.
 * It is the new SQL modul from Browser version 4.0 and it replaces the former SQL modul.
 *
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *
 * @version   3.9.9
 * @since     3.9.9
 *
 * @package     TYPO3
 * @subpackage  browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *
 */
class tx_browser_pi1_sql
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


  
    // [String] SQL error message
  var $error = null;
    // [String] SQL query
  var $query = null;

    // [Array]      Array tableFields for uid and pid of the localTable
    //              I.e: array( 'uid' => 'tx_org_cal.uid', 'pid' => 'tx_org_cal.pid' )
  var $arrLocalTable = null;
    // [String/CSV] Proper select statement for current rows.
    //              I.e: 'tx_org_cal.title,  tx_org_cal.subtitle,  tx_org_cal.teaser_short, ...'
  var $csvSelect  = null;
    // [String/CSV] Proper select statement for current rows for the search query.
    //              I.e: 'tx_org_cal.title AS \'tx_org_cal.title\', tx_org_cal.subtitle AS ...'
  var $csvSearch  = null;
    // [String/CSV] Proper order by statement (without ORDER BY).
    //              I.e: 'tx_org_cal.datetime DESC'
  var $csvOrderBy = null;
    // [Array]      Array with elements like rows, ... Each element contains SQL query statements
    //              like for SELECT, FROM, WHERE, GROUP BY, ORDER BY
  var $sql_query_statements = null;









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
    * Query building
    *
    **********************************************/



/**
 * init( ): Sets the class vars csvSelect, csvSelect, csvOrderBy, arrLocalTable.
 *          Sets the class var sql_query_statements['rows'] (sql query statements)
 *
 * @return	void
 * @version 3.9.8
 * @since   3.9.8
 */
  function init( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'begin' );

      // Set the globals csvSelect, csvSelect, csvOrderBy, arrLocalTable
    $arr_result = $this->global_all( );
    if( $arr_result['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_result;
    }
    unset( $arr_result );
      // Set the globals csvSelect, csvSelect, csvOrderBy

      // Set the SQL query statements
    $arr_result = $this->get_queryArray( );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }

    $this->sql_query_statements['rows'] = $arr_result['data'];
    unset( $arr_result );
      // SQL query array

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
  }

  
  
  
  
  
  
  
  
  /**
 * get_queryArray( ):
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_queryArray( )
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
    $arr_result = $this->get_queryArrayAuto( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlAut->get_query_array( )' );
    return $arr_result;
      // RETURN case is SQL automatically
  }








  
  /**
 * get_queryArrayAuto( ): 
 *
 * @return	array		array with the elements error and data. Data has the elements select, from, where, orderBy, groupBy.
 */
  public function get_queryArrayAuto( )
  {

    $arr_return['error']['status'] = false;

    //$this->pObj->dev_var_dump( $this->conf_sql );


    
      /////////////////////////////////////////////////////////////////
      //
      // Get the SELECT statement

      // Localise it, add uids
    $arr_return['data']['select'] = $this->pObj->objSqlAut->select( );
    if( ! $arr_return['data']['select'] )
    {
      $str_header  =  '<h1 style="color:red">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
      $str_prompt  =  '<p style="color:red; font-weight:bold;">' .
                        $this->pObj->pi_getLL('error_sql_select') .
                      '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Get the SELECT statement



      /////////////////////////////////////////////////////////////////
      //
      // Set the global groupBy

    $this->pObj->objSqlAut->groupBy( );
      // Set the global groupBy



      /////////////////////////////////////////////////////////////////
      //
      // Get the ORDER BY statement

    $arr_return['data']['orderBy'] = $this->pObj->objSqlAut->orderBy( );
    if( ! $arr_return['data']['orderBy'] )
    {
      $str_header   = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
      $str_prompt   = '<p style="color:red; font-weight:bold;">' .
                        $this->pObj->pi_getLL('error_sql_orderby') .
                      '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Get the ORDER BY statement



      /////////////////////////////////////////////////////////////////
      //
      // Get Relations

    $this->pObj->objSqlAut->arr_ts_autoconf_relation = $this->pObj->objSqlAut->get_ts_autoconfig_relation( );
    $this->pObj->objSqlAut->arr_relations_mm_simple  = $this->pObj->objSqlAut->get_arr_relations_mm_simple( );
      // Get Relations



      /////////////////////////////////////////////////////////////////
      //
      // Get WHERE and FROM

    $arr_return['data']['where']  = $this->pObj->objSqlAut->whereClause( );
    $arr_return['data']['from']   = $this->pObj->objSqlAut->sql_from( );
    // From has to be the last, because whereClause can have new tables.
    // Get WHERE and FROM



      ////////////////////////////////////////////////////////////////////
      //
      // Enable the ordering by table_mm.sorting

      // 091128: ADDED in context with table_mm.sorting (see below)
    $str_mmSorting = false;
    $arr_mmSorting = false;
    if( is_array( $this->pObj->arrConsolidate['select']['mmSortingTableFields'] ) )
    {
      foreach( ( array ) $this->pObj->arrConsolidate['select']['mmSortingTableFields'] as $tableField )
      {
        list( $table, $field ) = explode( '.', $tableField );
        $arr_mmSorting[] = $tableField . ' AS \'' . $tableField . '\'';
      }
    }
    if( is_array( $arr_mmSorting ) )
    {
      $str_mmSorting = $str_mmSorting . implode( ', ', $arr_mmSorting );
      $str_mmSorting = ', ' . $str_mmSorting;
    }
    $arr_return['data']['select'] = $arr_return['data']['select'] . $str_mmSorting;
      // 091128: ADDED in context with table_mm.sorting (see below)
      // Enable the ordering by table_mm.sorting



      /////////////////////////////////////////////////////////////////
      //
      // Replace Markers for pidlist and uid

    $str_pid_list = $this->pObj->pidList;
      // For human readable
    $str_pid_list = str_replace( ',', ', ', $str_pid_list );

    foreach( ( array ) $arr_return['data'] as $str_query_part => $str_statement )
    {
      $str_statement = str_replace('###PID_LIST###', $str_pid_list,                  $str_statement);
      $str_statement = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_statement);
      $arr_return['data'][$str_query_part]  = $str_statement;
    }
      // Replace Markers for pidlist and uid

    return $arr_return;
  }









    /***********************************************
    *
    * Globals
    *
    **********************************************/



/**
 * global_all( ): Set the globals csvSelect, csvSearch, csvOrderBy, arrLocalTable
 *
 * @return	array		$arr_return : Array in case of an error with the error message
 * @version 3.9.12
 * @since   3.9.12
 */
    private function global_all( )
    {
      $arr_return['error']['status'] = false;

        // Set the globals csvSelect and arrLocalTable
      if( ! $this->global_csvSelect( ) )
      {
        $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
        $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                          $this->pObj->pi_getLL( 'error_sql_select') .
                        '</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the globals csvSelect and arrLocalTable

        // Set the global csvSearch
      if( ! $this->global_csvSearch( ) )
      {
        $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
        $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                          $this->pObj->pi_getLL( 'error_sql_search' ) .
                        '</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the global csvSearch

        // Set the global csvOrderBy
      if( ! $this->global_csvOrderBy( ) )
      {
        $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
        $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                          $this->pObj->pi_getLL( 'error_sql_orderby' ) .
                        '</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the global csvOrderBy

        // #33892, 120219, dwildt+
      $this->csvSelect      = $this->pObj->csvSelect;
      $this->csvSearch      = $this->pObj->csvSearch;
      $this->csvOrderBy     = $this->pObj->csvOrderBy;
      $this->arrLocalTable  = $this->pObj->arrLocalTable;
        // #33892, 120219, dwildt+

      return $arr_return;
    }







 /**
  * global_csvSelect( ): Set the global csvSelect. Values are from the TypoScript select
  *
  * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
  * @version 3.9.12
  * @since   3.9.12
  */
    private function global_csvSelect( )
    {
      $conf       = $this->conf;
      $mode       = $this->piVar_mode;
      $view       = $this->view;
      $conf_path  = $this->conf_path;
      $conf_view  = $this->conf_view;

 

        ///////////////////////////////////
        //
        // Get the SELECT statement

      $this->pObj->csvSelect  = $conf_view['select'];
      $this->pObj->csvSelect  = $this->global_stdWrap
                                (
                                  'select',
                                  $this->pObj->csvSelect,
                                  $conf_view['select.']
                                );
      $this->pObj->csvSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $this->pObj->csvSelect );

      if( empty( $this->pObj->csvSelect ) )
      {
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'views.'.$viewWiDot.$mode.' hasn\'t any select fields.';
          t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Did you included the static template from this extensions?';
          t3lib_div::devLog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
          $prompt = 'Did you configure ' . $conf_path . '.select ?';
          t3lib_div::devLog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
          $prompt = 'ABORTED';
          t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
        }
        return false;
      }
        // Get the SELECT statement



        //////////////////////////////////////////////////////////////////////
        //
        // Get the parts behind an AS, replace aliases with real names

      $csv_before_process = $this->pObj->csvSelect;
      $csv_after_process  = $this->replace_statement( $csv_before_process );
      $arr_csv            = explode( ',', $csv_after_process );
      $arr_csv            = $this->clean_up_as_and_alias( $arr_csv );
      $csv_after_process  = implode( ', ', $arr_csv );
        // Get the parts behind an AS, replace aliases with real names


        //////////////////////////////////////////////////////////////////////
        //
        // RETURN in case of an error

      if( empty( $csv_after_process ) )
      {
        if ($this->pObj->b_drs_error)
        {
          $prompt = '$csv_after_process is FALSE or is empty.';
          t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
          $prompt = 'ABORTED';
          t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
        }
        return false;
      }
        // RETURN in case of an error



        //////////////////////////////////////////////////////////////////////
        //
        // Delete the first table.field, if it is the uid of the arrLocalTable

        // Init the global arrLocalTable, if it isn't inited
      if( ! is_array( $this->pObj->arrLocalTable) )
      {
        $this->global_arrLocalTable( );
      }

      $str_deleted_tablefield = false;
      $arr_tablefields        = explode( ',', $csv_after_process );
      if( trim($arr_tablefields[0] ) == $this->pObj->arrLocalTable['uid'] )
      {
        $str_deleted_tablefield = $arr_tablefields[0];
        unset( $arr_tablefields[0] );
        foreach( ( array ) $arr_tablefields as $key => $value )
        {
          $arr_tablefields[$key] = trim( $value );
        }
        $csv_after_process = implode( ', ', $arr_tablefields );
      }
        // Delete the first table.field, if it is the uid of the arrLocalTable



        ///////////////////////////////////
        //
        // DRS - Logging if user defined values were changed

      if( $csv_before_process != $csv_after_process )
      {
        $this->pObj->csvSelect = $csv_after_process;
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'Values for the global var csvSelect were changed.<br />
             Before changing:<br />
             '.$csv_before_process.'<br />
             After changing:<br />
             '.$csv_after_process;
          t3lib_div::devlog('[INFO/SQL] '.$prompt, $this->pObj->extKey, 0);
          if( $str_deleted_tablefield )
          {
            $prompt = $str_deleted_tablefield . ' is deleted, because it is the ' .
                      'first field in the statement and the value of the localTable.uid.';
            t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
          }
        }
      }
        // DRS - Logging if user defined values were changed

      return true;
    }



 /**
  * global_csvSearch( ): Set the global csvSearch. Values are from the TypoScript.
  *
  *                      If search is empty, search will get the values out of the select statement.
  *
  * @return	boolean		TRUE
  * @version 3.9.12
  * @since   3.9.12
  */
    private function global_csvSearch( )
    {
      $conf = $this->conf;
      $mode = $this->piVar_mode;
      $view = $this->view;

      $conf_view = $this->conf_view;


        ///////////////////////////////////
        //
        // Get the SEARCH values

      $csvSearch = $this->conf_sql['search'];
      $csvSearch = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );

      if ( empty( $csvSearch ) )
      {
        $csvSearch  = $this->conf_sql['select'];
        $csvSearch  = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'views.' . $viewWiDot . $mode . ' hasn\'t any extra search field. It is OK.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
      }

        // Is there a statement, which should replaced with an alias?
      foreach( ( array ) $conf_view['select.']['deal_as_table.'] as $arr_dealastable )
      {
        $csvSearch = str_replace( $arr_dealastable['statement'], $arr_dealastable['alias'], $csvSearch );
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'Used tables: Statement "' . $arr_dealastable['statement'] . '" is replaced with "' . $arr_dealastable['alias'] . '"';
          t3lib_div::devlog( '[INFO/SQL] ', $this->pObj->extKey, 0 );
        }
      }
        // Is there a statement, which should replaced with an alias?

      $this->pObj->csvSearch = $csvSearch;
      // Get the SEARCH values

      return true;
    }



 /**
  * global_csvOrderBy( ): Set the global csvOrderBy. Values are from the TypoScript orderBy or select
  *
  * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
  * @version 3.9.12
  * @since   3.9.12
  */
    private function global_csvOrderBy( )
    {
      $conf       = $this->conf;
      $mode       = $this->piVar_mode;
      $view       = $this->view;
      $conf_path  = $this->conf_path;
      $conf_view  = $this->conf_view;


        ///////////////////////////////////
        //
        // Get the override ORDER BY clause

      $orderBy = $this->conf_view['override.']['orderBy'];
      $orderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $orderBy );
      if( $orderBy )
      {
        if( $this->pObj->b_drs_sql )
        {
          $prompt = $conf_path . '.override.orderBy is: ' . $orderBy;
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'The system generated ORDER BY clause will be ignored!';
          t3lib_div::devLog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'ORDER BY ' . $orderBy;
          t3lib_div::devLog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
        // Get the override ORDER BY clause


        ///////////////////////////////////
        //
        // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error

      if( empty ( $orderBy ) )
      {
        $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $conf_view['orderBy'] );
        if( empty( $this->pObj->csvOrderBy ) )
        {
          $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $conf_view['select'] );
        }
        if( empty( $this->pObj->csvOrderBy ) )
        {
          if ($this->pObj->b_drs_error)
          {
            $prompt = 'views.' . $viewWiDot . $mode . ' hasn\'t any orderBy fields.';
            t3lib_div::devlog( '[ERROR/SQL] ', $this->pObj->extKey, 3 );
            $prompt = 'ABORTED';
            t3lib_div::devlog( '[WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
          }
          return false;
        }
      }
        // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error


        ///////////////////////////////////
        //
        // Get the parts behind an AS, replace aliases with real names

      $csv_before_process = $this->pObj->csvOrderBy;
      $csv_before_process = $this->replace_statement( $csv_before_process );
      $arr_csv            = explode( ',', $csv_before_process );
      $arr_csv            = $this->clean_up_as_and_alias( $arr_csv );
      $csv_before_process = implode( ', ', $arr_csv );
      $csv_after_process  = $csv_before_process;
        // Get the parts behind an AS, replace aliases with real names



        ///////////////////////////////////
        //
        // RETURN in case of an error

      if( empty ( $csv_after_process ) )
      {
        if( $this->pObj->b_drs_error )
        {
          $prompt = '$csv_after_process is FALSE or is empty.';
          t3lib_div::devlog('[ERROR/SQL] '. $prompt, $this->pObj->extKey, 3);
          $prompt = 'ABORTED';
          t3lib_div::devlog( '[WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
        }
        return false;
      }
        // RETURN in case of an error



        ///////////////////////////////////
        //
        // Delete the first table.field, if it is the uid of the arrLocalTable

        // Init the global arrLocalTable, if it isn't inited
      if( ! is_array( $this->pObj->arrLocalTable ) )
      {
        $this->global_arrLocalTable( );
      }

      $str_deleted_tablefield = false;
      $arr_tablefields        = explode( ',', $csv_after_process );
      if( trim($arr_tablefields[0]) == $this->pObj->arrLocalTable['uid'] )
      {
        $str_deleted_tablefield = $arr_tablefields[0];
        unset( $arr_tablefields[0] );
        foreach( ( array ) $arr_tablefields as $key => $value )
        {
          $arr_tablefields[$key] = trim($value);
        }
        $csv_after_process = implode( ', ', $arr_tablefields );
      }
        // Init the global arrLocalTable, if it isn't inited


        ////////////////////////////////////////////////////////////////////
        //
        // DRS - Logging if user defined values were changed

      if( $csv_before_process != $csv_after_process )
      {
        $this->pObj->csvOrderBy = $csv_after_process;
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'Values for the global var csvOrderBy were changed.<br />
             Before changing:<br />
             '.$csv_before_process.'<br />
             After changing:<br />
             '.$csv_after_process;
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          if ($str_deleted_tablefield)
          {
            $prompt = $str_deleted_tablefield . ' is deleted, because it is the first field in the statement and the value of the localTable.uid.';
            t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
          }
        }
      }

      return true;
    }



  /**
   * global_stdWrap: The method wraps sql query parts
   *
   * @param	string		$str_tsProperty: the name of the current array like select. or override.select.
   * @param	string		$str_tsValue:    the TypoScript value like: tt_news.title, tt_news.short
   * @param	array		$arr_tsArray:    the TypoScript array like select. or override.select.
   * @return	string		wrapped value, if there is a stdWrap configuration
   * @version 4.0.0
   */
  private function global_stdWrap( $str_tsProperty, $str_tsValue, $arr_tsArray )
  {
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;

      // RETURN value, if there is no stdWrap configuration
    if( ! is_array( $arr_tsArray ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $str_tsProperty . ' hasn\'t any stdWrap.';
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'If you like to wrap it, please configure ' . $conf_path . $str_tsProperty. '.value ...';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
      // RETURN value, if there is no stdWrap configuration

      // RETURN value, if property isn't uppercase
    if( $str_tsValue != strtoupper( $str_tsValue ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $str_tsValue. ' doesn\'t seem to be a TypoScript object like TEXT or COA.';
        t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'There will be any wrap.';
        t3lib_div::devlog('[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'If you like to wrap it, please configure i.e. '.
                  $conf_path . $str_tsProperty . ' = TEXT and ' .
                  $conf_path . $str_tsProperty . '.value = your value';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
      // RETURN value, if property isn't uppercase

      // RETURN value, if property is empty
    if( empty ( $str_tsValue ) )
    {
      if ($this->pObj->b_drs_sql)
      {
        $prompt = $str_tsValue. ' doesn\'t seem to be a TypoScript object like TEXT or COA.';
        t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'There will be any wrap.';
        t3lib_div::devlog('[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'If you like to wrap it, please configure i.e. '.
                  $conf_path . $str_tsProperty . ' = TEXT and ' .
                  $conf_path . $str_tsProperty . '.value = your value';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
    // RETURN value, if property isn't uppercase

    $lConfCObj    = false;
    $elements     = false;
    $str_tsValue  = $this->pObj->cObj->cObjGetSingle( $str_tsValue, $arr_tsArray );

    if( $this->pObj->b_drs_sql )
    {
      $prompt = $conf_path . $str_tsProperty . ' is wrapped: ' . $str_tsValue;
      t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
    }

    return $str_tsValue;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']);
}

?>
