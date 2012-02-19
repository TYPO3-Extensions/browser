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

    // [Array]  Array tableFields for uid and pid of the localTable
    //          I.e: array( 'uid' => 'tx_org_cal.uid', 'pid' => 'tx_org_cal.pid' )
  var $arrLocalTable = null;
    // [String/CSV] Proper select statement for current rows.
    //              I.e: 'tx_org_cal.title,  tx_org_cal.subtitle,  tx_org_cal.teaser_short, ...'
  var $csvSelect  = null;
    // [String/CSV]  Proper select statement for current rows for the search query.
    //               I.e: 'tx_org_cal.title AS \'tx_org_cal.title\', tx_org_cal.subtitle AS ...'
  var $csvSearch  = null;
    // [String/CSV]  Proper order by statement (without ORDER BY).
    //               I.e: 'tx_org_cal.datetime DESC'
  var $csvOrderBy = null;









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
 * main( ): Display a search form, a-z-Browser, pageBrowser and a list of records
 *
 * @return	string  $template : The processed HTML template
 * @version 3.9.8
 * @since 1.0.0
 */
  function init( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'begin' );

      // Set the globals csvSelect, csvSelect, csvOrderBy, arrLocalTable
    $arr_result       = $this->pObj->objSqlFun->global_all( );
    if( $arr_result['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_result;
    }
      // Development prompting
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvSelect );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvSearch );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvOrderBy );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->arrLocalTable );
      // Development prompting
    unset( $arr_result );
      // Set the globals csvSelect, csvSelect, csvOrderBy

      // SQL query array
    $arr_result = $this->get_queryArray( );
    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $arr_result );
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

      // Short vars
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_view  = $this->conf_view;
      // Short vars

        $str_header  = '<h1 style="color:red;">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">' . $this->pObj->pi_getLL('error_sql_select') . '</p>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">' . 'SQLengine 4.x' . '</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
    $arr_return   = $arr_data;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

    return $arr_return;
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

    //$this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->pObj->conf_sql );


    
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

    $this->arr_ts_autoconf_relation = $this->pObj->objSqlAut->get_ts_autoconfig_relation( );
    $this->arr_relations_mm_simple  = $this->pObj->objSqlAut->get_arr_relations_mm_simple( );
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









  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']);
  }

?>