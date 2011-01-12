<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 - 2010 Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_sql_auto bundles SQL methods for this case: The user has defined a SELECT
 * statement only. Browser should generate a full sql query automatically
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   72: class tx_browser_pi1_sql_auto
 *   93:     function __construct($parentObj)
 *
 *              SECTION: Main method
 *  116:     function get_query_array()
 *
 *              SECTION: SQL relation building with user defined SELECT only
 *  270:     function select()
 *  458:     function sql_from()
 *  612:     function orderBy()
 *  760:     function groupBy()
 *  873:     function get_joins()
 *
 *              SECTION: SQL relation building WHERE
 * 1287:     function whereSearch()
 * 1544:     function whereClause()
 * 1758:     function andWhere()
 * 1842:     function arr_andWherePid()
 * 1878:     function str_andWherePid($realTable)
 * 1947:     function arr_andWhereEnablefields()
 * 1983:     function str_enableFields($realTable)
 *
 *              SECTION: Methods for automatic SQL relation building
 * 2020:     function get_ts_autoconfig_relation()
 * 2086:     function get_arr_relations_mm_simple()
 *
 *              SECTION: Manual SQL Query Building
 * 2487:     function get_sql_query($select, $from, $where, $group, $order, $limit)
 *
 * TOTAL FUNCTIONS: 17
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_auto
{

  var $boolAutorelation = true;
  // [Boolean] If it is TRUE, browser should try to build relations automatically
  var $arr_ts_autoconf_relation;
  // [Array] Array with some configuration from the TS for an automatic relation building
  var $arr_relations_mm_simple;
  // [Array] Array with the arrays MM and/or simple
  var $b_left_join = false;
  // [Boolean] TRUE if we should use LEFT JOIN. From TypoScript global or local autoconfig.relations.left_join




  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }






  /***********************************************
   *
   * Main method
   *
   **********************************************/


  /**
 * It returns the parts for a SQL query. OrderBy and GroupBy aren't used in the SQL statement.
 * OrderBy is used in php multisort. GroupBy is used in context with consolidation.
 *
 * @return  array   array with the elements error and data. Data has the elements select, from, where, orderBy, groupBy.
 */
  function get_query_array()
  {

    $arr_return['error']['status'] = false;


    /////////////////////////////////////////////////////////////////
    //
    // Get SELECT

    $arr_return['data']['select'] = $this->select();
    if (!$arr_return['data']['select'])
    {
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_sql_select').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
    // Get SELECT

// :todo: 100429: remove methods orderBy() and groupBy() in this class

//    /////////////////////////////////////////////////////////////////
//    //
//    // Set the global groupBy
//
//    $this->groupBy();
//    // Set the global groupBy


// 100429, dwildt: auskommentiert, war vermutlich für SQL query. Sortiert wird aber mit PHP
//    /////////////////////////////////////////////////////////////////
//    //
//    // Get ORDER BY
//
//    $arr_return['data']['orderBy'] = $this->orderBy();
//    if (!$arr_return['data']['orderBy'])
//    {
//      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
//      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_sql_orderby').'</p>';
//      $arr_return['error']['status'] = true;
//      $arr_return['error']['header'] = $str_header;
//      $arr_return['error']['prompt'] = $str_prompt;
//      return $arr_return;
//    }
// 100429, dwildt: auskommentiert, da kein $arr_result vorhanden
//    if (isset($arr_result['data']['addToSelect']))
//    {
//      $arr_return['data']['select'] = $arr_return['data']['select'].', '.$arr_result['data']['addToSelect'];
//      if ($this->pObj->b_drs_sql)
//      {
//        t3lib_div::devLog('[INFO/SQL] OrderBy: Select is now:<br />'.
//          '\''.$arr_return['data']['select'].'\'', $this->pObj->extKey, 0);
//      }
//    }
//    unset($arr_result);
    // Get ORDER BY


    /////////////////////////////////////////////////////////////////
    //
    // Get Relations

    $this->arr_ts_autoconf_relation = $this->get_ts_autoconfig_relation();
    $this->arr_relations_mm_simple  = $this->get_arr_relations_mm_simple();
    // Get Relations


    /////////////////////////////////////////////////////////////////
    //
    // Get WHERE and FROM

    $arr_return['data']['where']  = $this->whereClause();
    $arr_return['data']['from']   = $this->sql_from();
    // From has to be the last, because whereClause can have new tables.
    // Get WHERE and FROM


    ////////////////////////////////////////////////////////////////////
    //
    // Enable the ordering by table_mm.sorting

    // 091128: ADDED in context with table_mm.sorting (see below)
    $str_mmSorting = false;
    $arr_mmSorting = false;
    if(is_array($this->pObj->arrConsolidate['select']['mmSortingTableFields']))
    {
      foreach($this->pObj->arrConsolidate['select']['mmSortingTableFields'] as $tableField)
      {
        list($table, $field) = explode('.', $tableField);
        $arr_mmSorting[] = $tableField.' AS \''.$tableField.'\'';
      }
    }
    if (is_array($arr_mmSorting))
    {
      $str_mmSorting = $str_mmSorting.implode(', ', $arr_mmSorting);
      $str_mmSorting = ', '.$str_mmSorting;
    }
    $arr_return['data']['select'] = $arr_return['data']['select'].$str_mmSorting;
    // 091128: ADDED in context with table_mm.sorting (see below)
    // Enable the ordering by table_mm.sorting


    /////////////////////////////////////////////////////////////////
    //
    // Replace Markers for pidlist and uid

    $str_pid_list = $this->pObj->pidList;
    $str_pid_list = str_replace(',', ', ', $str_pid_list);
    // For human readable

    foreach($arr_return['data'] as $str_query_part => $str_statement)
    {
      $str_statement                        = str_replace('###PID_LIST###', $str_pid_list,                  $str_statement);
      $str_statement                        = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_statement);
      $arr_return['data'][$str_query_part]  = $str_statement;
    }

    return $arr_return;
  }


















/***********************************************
   *
   * SQL relation building with user defined SELECT only
   *
   **********************************************/


  /**
 * It returns the select part for SQL query. It fills up $csvSelect for the HTML table head.
 * If tables hasn't any uid in the SELECT, table.uid will be added.
 *  If orderBy contains further tableFields, they will added to the select query.
 *
 * @return  string    SQL select or FALSE, if there is an error
 */
  function select()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

    $select = false;

// 3.3.7
//    ////////////////////////////////////////////////////////////////////
//    //
//    // RETURN in case of override.select
//
//    $select = $conf['views.'][$viewWiDot][$mode.'.']['override.']['select'];
//    if ($select)
//    {
//      if ($this->pObj->b_drs_sql)
//      {
//        t3lib_div::devlog('[WARN/SQL] views.'.$viewWiDot.$mode.'.override.select is: '.$select, $this->pObj->extKey, 2);
//        t3lib_div::devLog('[INFO/SQL] If there is views.'.$viewWiDot.$mode.'.select, it will be ignored!', $this->pObj->extKey, 0);
//        t3lib_div::devLog('[INFO/SQL] SELECT '.$select, $this->pObj->extKey, 0);
//      }
//      return $select;
//    }
//    // RETURN in case of override.select



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.select

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['select'])
    {
      $select = $this->pObj->conf_sql['select'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.select is true. views.'.$viewWiDot.$mode.'.select will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] SELECT '.$select, $this->pObj->extKey, 0);
      }
      return $select;
    }
    // RETURN in case of override.select
// 3.3.7



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN, if the global array conf_sql['select'] isn't set

    if (!$this->pObj->conf_sql['select'])
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] ABORTED', $this->pObj->extKey, 3);
      }
      return false;
    }
    // RETURN, if the global array conf_sql['select'] isn't set



    ////////////////////////////////////////////////////////////////////
    //
    // Get the SELECT statement from the global conf_sql['select']

    // Values from the TypoScript setup
    $select = $this->pObj->conf_sql['select'];
    // Get the SELECT statement from the global conf_sql['select']



    ////////////////////////////////////////////////////////////////////
    //
    // stdWrap

    // stdWrap



    ////////////////////////////////////////////////////////////////////
    //
    // If a used table hasn't any uid field, add it to the consolidation array

    $str_addTableUids = false;
    $arr_addTableUids = false;
    if(is_array($this->pObj->arrConsolidate['addedTableFields']))
    {
      foreach($this->pObj->arrConsolidate['addedTableFields'] as $tableField)
      {
        list($table, $field) = explode('.', $tableField);
        if ($field == 'uid')
        {
          $arr_addTableUids[] = $tableField.' AS \''.$tableField.'\'';
        }
      }
    }
    if (is_array($arr_addTableUids))
    {
      $str_addTableUids = $str_addTableUids.implode(', ', $arr_addTableUids);
      $str_addTableUids = ', '.$str_addTableUids;
    }
    $select = $select.$str_addTableUids;
    // If a used table hasn't any uid field, add it to the consolidation array


    ////////////////////////////////////////////////////////////////////
    //
    // Add localization fields

    $arr_addedTableFields = array();
    // Loop through all used tables
    foreach ($this->pObj->arr_realTables_arrFields as $table => $arrFields)
    {
      $arr_result = $this->pObj->objLocalize->localizationFields_select($table);
      // Get the and SELECT statement with aliases
      if ($arr_result['wiAlias'])
      {
        $arr_localSelect[] = $arr_result['wiAlias'];
      }
      // Get all added table.fields
      if (is_array($arr_result['addedFields']))
      {
        $arr_addedTableFields = array_merge($arr_addedTableFields, $arr_result['addedFields']);
      }
    }
    unset($arr_result);
    // Loop through all used tables

    if (is_array($arr_localSelect))
    {
      // Build the SELECT statement
      $str_localSelect = implode(', ', $arr_localSelect);
      if($str_localSelect) {
        $select = $select.', '.$str_localSelect;
      }
      // Build the SELECT statement
    }

    if (is_array($arr_addedTableFields))
    {
      // Loop through all new table.fields
      foreach ($arr_addedTableFields as $tableField)
      {
        list($table, $field) = explode('.', $tableField);
        if (!in_array($field, $this->pObj->arr_realTables_arrFields[$table]))
        {
          // Add every new table.field to the global array arr_realTables_arrFields
          $this->pObj->arr_realTables_arrFields[$table][] = $field;
          // Add every new table.field to the global array consolidate
          $this->pObj->arrConsolidate['addedTableFields'][] = $tableField;
        }
      }
      // Loop through all new table.fields
    }
    // Add localization fields


    ////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devLog('[INFO/SQL] SELECT '.$select, $this->pObj->extKey, -1);
    }
    // DRS - Development Reporting System


    return $select;
  }








  /**
 * The method returns the FROM clause for the SQL query
 *
 * @return  string    SQL from
 */
  function sql_from()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

// 3.3.7
//    $from = $conf['views.'][$viewWiDot][$mode.'.']['override.']['from'];
//    $from = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($from);
//    if ($from)
//    {
//      if ($this->pObj->b_drs_sql)
//      {
//        t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.'.override.from is: '.$from, $this->pObj->extKey, 0);
//        t3lib_div::devLog('[INFO/SQL] The system generated FROM clause will be ignored!', $this->pObj->extKey, 0);
//        t3lib_div::devLog('[INFO/SQL] FROM '.$from, $this->pObj->extKey, 0);
//      }
//      return $from;
//    }



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.from

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['from'])
    {
      $from = $this->pObj->conf_sql['from'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.from is true. views.'.$viewWiDot.$mode.'.from will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] FROM '.$from, $this->pObj->extKey, 0);
      }
      return $from;
    }
    // RETURN in case of override.from
// 3.3.7



    $from = false;

    // Add the first element of fetched tables to FROM
    if ($this->pObj->localTable)
    {
      $from = $this->pObj->localTable;
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] Value from the localTable: FROM \''.$from.'\'', $this->pObj->extKey, 0);
      }
    }


    if(!$from)
    {
      if ($this->b_left_join)
      {
        reset($this->pObj->arr_realTables_arrFields);
        // Take the key of the first element. This is the name of the first table
        $from = key($this->pObj->arr_realTables_arrFields);
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devLog('[INFO/SQL] Value from the TypoScript select: FROM \''.$from.'\'', $this->pObj->extKey, 0);
        }
      }
      // Add the first element of fetched tables to FROM
    }

    // LEFT JOIN
    if ($this->b_left_join)
    {
      $arr_result     = $this->get_joins();
      $str_left_join  = $arr_result['data']['left_join'];
      unset($arr_result);
      $from           = $from.$str_left_join;
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] autoconfig.relations.left_join is TRUE.<br />
          Fetched tables for relation building won\'t be used.<br />
          FROM \''.$from.'\'', $this->pObj->extKey, 0);
      }
    }
    // LEFT JOIN

    // FULL JOIN
    if (!$this->b_left_join)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] autoconfig.relations.left_join is FALSE.', $this->pObj->extKey, 0);
      }

      // Get fetched tables as array
      $arr_realTables = array_keys($this->pObj->arr_realTables_arrFields);
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] Fetched tables are: '.implode(', ', $arr_realTables), $this->pObj->extKey, 0);
      }
      if(is_array($arr_realTables))
      {
        // Generates the from tables unique
        if($from)
        {
          $arr_realTables[] = $from;
          $arr_realTables   = array_unique($arr_realTables);
          $from             = implode(', ', $arr_realTables);
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devLog('[INFO/SQL] FROM \''.$from.'\'', $this->pObj->extKey, 0);
          }
        }
        // Generates the from tables unique
        // Generates the from tables
        if(!$from)
        {
          $from  = implode(', ', $arr_realTables);
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devLog('[INFO/SQL] FROM \''.$from.'\'', $this->pObj->extKey, 0);
          }
        }
        // Generates the from tables
      }
      // Get fetched tables as array
    }
    // FULL JOIN

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[HELP/SQL] Change it? Use views.'.$viewWiDot.$mode.'.override.from', $this->pObj->extKey, 1);
    }
    return $from;
  }








  /**
 * It returns the order part for SQL where clause.
 * If there are piVars, the order of the piVars will preferred.
 * Otherwise it returns the TypoScript configuration.
 * If there aren't piVars and there aren't a TypoSCript configuration, it will be empty.
 * If there are aliases, the aliases will be deleted.
 *
 * @return  string    $orderBy: SQL ORDER BY clause.
 */
  function orderBy()
  {
    // 3.3.7
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.oderBy

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['orderBy'])
    {
      $orderBy = $this->pObj->conf_sql['orderBy'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.orderBy is true. views.'.$viewWiDot.$mode.'.orderBy will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] ORDER BY '.$orderBy, $this->pObj->extKey, 0);
      }
      return $orderBy;
    }
    // RETURN in case of override.oderBy
// 3.3.7



    $orderBy = false;



    ////////////////////////////////////////////////////////////////////
    //
    // Set the orderBy by piVars

    $orderBy = $this->pObj->objSqlFun->orderBy_by_piVar();
    if($orderBy)
    {
      $bool_setGroupBy = false;
    }
    if(!$orderBy)
    {
      $bool_setGroupBy = true;
    }
    // Set the orderBy by piVars


    ////////////////////////////////////////////////////////////////////
    //
    // Append the default orderBy

    if($orderBy)
    {
      $orderBy = $orderBy.', '.$this->pObj->conf_sql['orderBy'];
    }
    if(!$orderBy)
    {
      $orderBy = $this->pObj->conf_sql['orderBy'];
    }
    // Append the default orderBy


    ////////////////////////////////////////////////////////////////////
    //
    // Proper ASC and DESC

    $orderBy = str_ireplace(' desc', ' DESC', $orderBy);
    $orderBy = str_ireplace(' asc',  ' ASC',  $orderBy);
    // Proper ASC and DESC


    ////////////////////////////////////////////////////////////////////
    //
    // If there is no orderBy clause, take the select clause

    if (!$orderBy)
    {
      $orderBy = $this->pObj->conf_sql['select'];
      $bool_orderByIsSelect = true;
    }
    // If there is no orderBy clause, take the select clause


    ////////////////////////////////////////////////////////////////////
    //
    // Clean up Aliases

    $arr_orderBy = explode(',', $orderBy);
    foreach ($arr_orderBy as $key => $str_tableFieldAlias)
    {
      $arr_orderBy[$key] = $this->pObj->objSqlFun->get_sql_alias_before($str_tableFieldAlias);
    }
    if (is_array($arr_orderBy))
    {
      $orderBy = implode(',', $arr_orderBy);
    }
    // Clean up Aliases


    ////////////////////////////////////////////////////////////////////
    //
    // If there is a groupBy, prepend it to the orderBy clause

    if ($this->pObj->groupBy)
    {
      if($bool_setGroupBy)
      {
        $orderBy = $this->pObj->groupBy.', '.$orderBy;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devLog('[INFO/SQL] GROUP BY is prepended to ORDER BY.', $this->pObj->extKey, -1);
        }
      }
    }
    // If there is a groupBy, prepend it to the orderBy clause


    //////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devLog('[INFO/SQL] ORDER BY \''.$orderBy.'\' Be aware: this is for php ordering but not for the SQL order-by-clause.', $this->pObj->extKey, -1);
    }
    // DRS - Development Reporting System

    return $orderBy;
  }








  /**
 * THIS ISN'T THE GROUPBY FOR THE SQL QUERY
 * Allocates a proper group by in the global groupBy
 * It returns the group by part, which is needed for consolidation
 * If there is more than one value, all other values will be removed
 * If there are aliases, the aliases will be deleted.
 *
 * @return  string    $groupBy: The first groupBy value with ASC or DESC, if there is one
 */
  function groupBy()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

// 3.3.7
    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.groupBy

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['groupBy'])
    {
      $groupBy = $this->pObj->conf_sql['groupBy'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.groupBy is true. views.'.$viewWiDot.$mode.'.groupBy will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] GROUP BY '.$groupBy, $this->pObj->extKey, 0);
      }
      return $groupBy;
    }
    // RETURN in case of override.groupBy
// 3.3.7



    $groupBy = $conf['views.'][$viewWiDot][$mode.'.']['groupBy'];
    //$groupBy = $this->pObj->conf_sql['groupBy'];


    ////////////////////////////////////////////////////////////////////
    //
    // RETURN if there isn't any groubBy in the TypoScript

    if(!$groupBy || $groupBy == '')
    {
      return false;
    }
    // RETURN if there isn't any groubBy in the TypoScript


    ////////////////////////////////////////////////////////////////////
    //
    // Proper ASC and DESC

    $groupBy = str_ireplace(' desc', ' DESC', $groupBy);
    $groupBy = str_ireplace(' asc',  ' ASC',  $groupBy);
    // Proper ASC and DESC


    ////////////////////////////////////////////////////////////////////
    //
    // We like only the first value

    $arr_groupBy    = explode(',', $groupBy);
    if(count($arr_groupBy) > 1)
    {
      $str_1stGroupBy = $arr_groupBy[0];
      unset($arr_groupBy);
      $arr_groupBy[]  = $str_1stGroupBy;
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[WARN/SQL] GROUP BY will be cuted after the first value: '.$groupBy, $this->pObj->extKey, 2);
        t3lib_div::devLog('[HELP/SQL] Please configure \'views.'.$viewWiDot.$mode.'.groupBy\'', $this->pObj->extKey, 1);
      }
    }
    // We like only the first value


    ////////////////////////////////////////////////////////////////////
    //
    // Clean up Aliases

    foreach ($arr_groupBy as $key => $str_tableFieldAlias)
    {
      $arr_groupBy[$key] = $this->pObj->objSqlFun->get_sql_alias_before($str_tableFieldAlias);
    }
    if (is_array($arr_groupBy))
    {
      $groupBy = implode(',', $arr_groupBy);
    }
    // Clean up Aliases


    //////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devLog('[INFO/SQL] GROUP BY is: \''.$groupBy.'\'. Be aware: this is for php ordering and for consolidation but not for the SQL group-by-clause.', $this->pObj->extKey, -1);
    }
    // DRS - Development Reporting System

    $this->pObj->groupBy = $groupBy;
    return $groupBy;
  }








  /**
 * Relation method: Building the relation part for the where clause
 *
 * @return  string    TRUE || FALSE or the SQL-where-clause
 */
  function get_joins()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $str_left_join = false;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

    $arr_return['error']['status'] = false;


    ////////////////////////////////////////////////////////////////////
    //
    // Tables used?

    if (count($this->pObj->arr_realTables_arrFields) < 1)
    {
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] There isn\'t any table used.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/SQL] There has to be the local table at least!', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/SQL] ABORT (No relation building!)', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_table_no').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


    ////////////////////////////////////////////////////////////////////
    //
    // Do we have tables for a simple relation building?

    if (is_array($conf_view['relations.']['simple.']))
    {
      if (!is_array($this->arr_relations_mm_simple))
      {
        $this->arr_relations_mm_simple = array();
      }
      $this->arr_relations_mm_simple['simple'] = $conf_view['relations.']['simple.'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] relations.simple is configured.', $this->pObj->extKey, 0);
      }
    }
    // Do we have tables for a simple relation building?


    ////////////////////////////////////////////////////////////////////
    //
    // Do we have tables for a MM relation building?
    if (is_array($conf_view['relations.']['mm.']))
    {
      if (!is_array($this->arr_relations_mm_simple))
      {
        $this->arr_relations_mm_simple = array();
      }
      $this->arr_relations_mm_simple['MM'] = $conf_view['relations.']['mm.'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] relations.mm is configured.', $this->pObj->extKey, 0);
      }
    }
    // Do we have tables for a MM relation building?

    // RETURN there isn't any table
    if (empty($this->arr_relations_mm_simple))
    {
      // We don't have any table. Return.
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] Nothing to do. There is no relation.', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
    // RETURN there isn't any table
    // Do we have tables for relation building?



    //////////////////////////////////////////////////////////////////
    //
    // RETURN, if relation isn't MM or isn't simple

    if(is_array($this->arr_relations_mm_simple))
    {
      foreach ($this->arr_relations_mm_simple as $relation => $tables)
      {
        if ($relation != 'MM' && $relation != 'simple')
        {
          if ($this->pObj->b_drs_sql || $this->pObj->b_drs_error)
          {
            t3lib_div::devlog('[ERROR/SQL] There is a undefined relation type: \''.$relation.'\'', $this->pObj->extKey, 3);
            t3lib_div::devlog('[HELP/SQL] There has to be the local table at least!', $this->pObj->extKey, 1);
            t3lib_div::devlog('[ERROR/SQL] ABORT (No relation building!)', $this->pObj->extKey, 3);
          }
          $str_header  = '<h1 style="color:red">ERROR Relation Building</h1>';
          $str_prompt  = '<p style="color:red; font-weight:bold;">relations.'.$relation.' isn\'t defined.</p>';
          $arr_return['error']['status'] = true;
          $arr_return['error']['header'] = $str_header;
          $arr_return['error']['prompt'] = $str_prompt;
          return $arr_return;
        }
      }
    }
    // RETURN, if relation isn't MM or simple


    $str_full_join = false;


    //////////////////////////////////////////////////////////////////
    //
    // MM-relation-building

    $tables = $this->arr_relations_mm_simple['MM'];
    if (is_array($tables))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' has configured MM relations.', $this->pObj->extKey, 0);
      }
      //////////////////////////////////
      //
      // Convert Array
      // from: ["tt_news."]["tt_news_cat_mm"] = "tt_news_cat"
      // to:   ["tt_news"]["tt_news_cat_mm"] = "tt_news_cat"
      foreach($tables as $localTable => $localFields)
      {
        $str_dot = false;
        if(substr($localTable, -1) == '.')
        {
          $tableWoDot           = substr($localTable, 0, strlen($localTable) -1);
          $tables[$tableWoDot]  = $tables[$localTable];
          unset($tables[$localTable]);
        }
      }
      // Convert Array

//      var_dump($tables);
//      exit;
      //  array["tx_ships_main"]["tx_ships_main_g2_shipowner_mm"]   = "tx_ships_owner"
      //  array["tx_ships_main"]["tx_ships_main_g3_application_mm"] = "tx_ships_application"
      //  array["tx_ships_main"]["tx_ships_main_g3_rigortype_mm"]   = "tx_ships_rigortype"
      //  ...
      foreach ($tables as $localTable => $foreignTables)
      {
        //var_dump($foreignTables);
        //  array["tx_ships_main_g2_shipowner_mm"]    = "tx_ships_owner"
        //  array["tx_ships_main_g3_application_mm"]  = "tx_ships_application"
        //  array["tx_ships_main_g3_rigortype_mm"]    = "tx_ships_rigortype"
        //  array["tx_ships_main_g3_powersystem_mm"]  = ...

        // Load the TCA, if we don't have an table.columns array
        if (!is_array($GLOBALS['TCA'][$localTable]['columns']))
        {
          t3lib_div::loadTCA($localTable);
          if ($this->pObj->b_drs_locallang)
          {
            t3lib_div::devlog('[INFO/SQL] $GLOBALS[\'TCA\'][\''.$localTable.'\'] is loaded.', $this->pObj->extKey, 0);
          }
        }
        // Load the TCA, if we don't have an table.columns array
        foreach($foreignTables as $mmTable => $foreignTable)
        {
          if (in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
          {
            // #9697, 100912, dwildt
            $bool_opposite = false;
            if(!empty($this->arr_relations_opposite[$localTable][$mmTable]['MM_opposite_field']))
            {
              $bool_opposite = true;
            }
            // #9697, 100912, dwildt
            if ($this->b_left_join)
            {
              // #9697, 100912, dwildt
              if($bool_opposite)
              {
                $str_left_join_uidlocal = ' LEFT JOIN '.$mmTable.
                                          ' ON ( '.$localTable.'.uid = '.$mmTable.'.uid_foreign )';
              }
              if(!$bool_opposite)
              {
                $str_left_join_uidlocal = ' LEFT JOIN '.$mmTable.
                                          ' ON ( '.$localTable.'.uid = '.$mmTable.'.uid_local )';
              }
              // #9697, 100912, dwildt
              // Use current LEFT JOIN once only
              if (strpos($str_left_join, $str_left_join_uidlocal) === false)
              {
                if ($this->pObj->b_drs_sql)
                {
                  t3lib_div::devlog('[INFO/SQL] '.$localTable.' get a LEFT JOIN to '.$foreignTable.'.', $this->pObj->extKey, 0);
                }
                $str_left_join = $str_left_join.$str_left_join_uidlocal;
              }
              $str_enablefields_foreign = $this->pObj->cObj->enableFields($foreignTable);
              $str_pidStatement         = $this->str_andWherePid($foreignTable);
              $str_pidStatement         = ' AND '.$str_pidStatement.' ';
              // #9697, 100912, dwildt
              if($bool_opposite)
              {
                $str_left_join_uidforeign = ' LEFT JOIN '.$foreignTable.
                                            ' ON ( '.
                                                $mmTable.'.uid_local = '.$foreignTable.'.uid'.
                                                $str_enablefields_foreign.
                                                $str_pidStatement.
                                              ' )';
              }
              if(!$bool_opposite)
              {
                $str_left_join_uidforeign = ' LEFT JOIN '.$foreignTable.
                                            ' ON ( '.
                                                $mmTable.'.uid_foreign = '.$foreignTable.'.uid'.
                                                $str_enablefields_foreign.
                                                $str_pidStatement.
                                              ' )';
              }
              // #9697, 100912, dwildt
              // Use current LEFT JOIN once only
              if (strpos($str_left_join, $str_left_join_uidforeign) === false)
              {
                $str_left_join = $str_left_join.$str_left_join_uidforeign;
              }
            }
            if (!$this->b_left_join)
            {
              if ($this->pObj->b_drs_sql)
              {
                t3lib_div::devlog('[INFO/SQL] '.$localTable.' get a FULL JOIN to '.$foreignTable.'.', $this->pObj->extKey, 0);
              }
              // #9697, 100912, dwildt
              if($bool_opposite)
              {
                $str_full_join .= ' AND '.$localTable.'.uid = '.$mmTable.'.uid_foreign'.
                                  ' AND '.$mmTable.'.uid_local = '.$foreignTable.'.uid';
              }
              if(!$bool_opposite)
              {
                $str_full_join .= ' AND '.$localTable.'.uid = '.$mmTable.'.uid_local'.
                                  ' AND '.$mmTable.'.uid_foreign = '.$foreignTable.'.uid';
              }
              // #9697, 100912, dwildt
            }
            // Add the mm table to the fetched tables, if it is new
            if (!in_array($mmTable, array_keys($this->pObj->arr_realTables_arrFields)))
            {
              // Add every new table.field to the global array arr_realTables_arrFields
              $this->pObj->arr_realTables_arrFields[$mmTable][] = 'uid_local';
              $this->pObj->arr_realTables_arrFields[$mmTable][] = 'uid_foreign';

              // 091128: ADDED (in context with table_mm.sorting)
              $keys_mmTable = array_keys(($GLOBALS['TYPO3_DB']->admin_get_fields($mmTable)));
              if(in_array('sorting', $keys_mmTable))
              {
                $this->pObj->arr_realTables_arrFields[$mmTable][] = 'sorting';
                // Add every new table.field to the global array consolidate
                $this->pObj->arrConsolidate['addedTableFields'][] = $mmTable.'.sorting';
                $this->pObj->arrConsolidate['select']['mmSortingTableFields'][]  = $mmTable.'.sorting';
              }
              // 091128: ADDED (in context with table_mm.sorting)
            }
            // Add the foreign table to the fetched tables, if it is new
            if (!in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
            {
              $this->pObj->arr_realTables_arrFields[$foreignTable][] = 'uid';
            }
          }
        }
      }
    }
    // MM-relation-building


    //////////////////////////////////////////////////////////////////
    //
    // simple-relation-building

    $tables = $this->arr_relations_mm_simple['simple'];
    if (is_array($tables))
    {
      //////////////////////////////////
      //
      // Convert Array
      // from: ["tt_news"]["cruser_id"] = "be_users"
      // to:   ["tt_news.cruser_id"]    = "be_users"
      foreach($tables as $keyTable => $arrFields)
      {
        foreach($arrFields as $keyField => $valueField)
        {
          $str_dot = false;
          if(substr($keyTable, -1) != '.')
          {
            $str_dot = '.';
          }
          $tables[$keyTable.$str_dot.$keyField] = $valueField;
          unset($tables[$keyTable][$keyField]);
        }
        unset($tables[$keyTable]);
      }
      foreach($tables as $localTableField => $foreignTable)
      {
        list ($localTable, $localField)     = explode('.', $localTableField);
          // #11650, cweiske, 101223
        //$foreignTableField = $foreignTable.'.uid';
        if (strpos($foreignTable, '.') !== false) 
        {
          list($foreignTable, $foreignTableField) = explode('.', $foreignTable);
        }
        else
        {
          $foreignTableField = $foreignTable.'.uid';
        }
          // #11650, cweiske, 101223
        if ($this->b_left_join)
        {
          $str_enablefields_foreign = $this->pObj->cObj->enableFields($foreignTable);
          $str_pidStatement         = $this->str_andWherePid($foreignTable);
          $str_pidStatement         = " AND ".$str_pidStatement." ";
          $str_left_join_uidforeign = " LEFT JOIN ".$foreignTable.
                                      " ON ( ".
                                      "   ( ".
                                      "     ".$localTableField." = ".$foreignTableField." OR ".
                                      "     ".$localTableField." LIKE CONCAT(".$foreignTableField.", ',%') OR ".
                                      "     ".$localTableField." LIKE CONCAT('%,', ".$foreignTableField.", ',%') OR ".
                                      "     ".$localTableField." LIKE CONCAT('%,', ".$foreignTableField.") ".
                                      "   )".
                                      "   ".$str_enablefields_foreign.
                                      "   ".$str_pidStatement.
                                      " )";
          // Use current LEFT JOIN once only
          if (strpos($str_left_join, $str_left_join_uidforeign) === false)
          {
            $str_left_join .= $str_left_join_uidforeign;
          }
        }
        if (!$this->b_left_join)
        {
          // The AND clause below makes only sense, if it is a 1:1-relation!
          $str_full_join .=  " AND (".
                                  "   ( ".
                                  "     ".$localTableField." = ".$foreignTableField." OR ".
                                  "     ".$localTableField." LIKE CONCAT(".$foreignTableField.", ',%') OR ".
                                  "     ".$localTableField." LIKE CONCAT('%,', ".$foreignTableField.", ',%') OR ".
                                  "     ".$localTableField." LIKE CONCAT('%,', ".$foreignTableField.") ".
                                  "   )".
                                  "   ".$str_enablefields_foreign.
                                  "   ".$str_pidStatement.
                                  " )";
        }
        // Add the foreign table to the fetched tables, if it is new
        if (!in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
        {
          $this->pObj->arr_realTables_arrFields[$foreignTable][] = 'uid';
        }
      }
    }
    // simple-relation-building


    //////////////////////////////////////////////////////////////////
    //
    // Building $arr_return

    if ($this->b_left_join)
    {
      $arr_return['data']['left_join'] = $str_left_join;
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$str_left_join, $this->pObj->extKey, 0);
      }
    }
    if (!$this->b_left_join)
    {
      $arr_return['data']['full_join'] = $str_full_join;
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$str_full_join, $this->pObj->extKey, 0);
      }
    }
    return $arr_return;
    // Building $arr_return
  }


















/***********************************************
*
* SQL relation building WHERE
*
**********************************************/


    /**
 * It returns the part for the where clause with a search, if there are search fields in the TS and a piVar sword.
 * The where clause will have this structure:
 *   (field_1 LIKE sword_1 or field_2 LIKE sword_1 or ...) AND (field_1 LIKE sword_2 or field_2 LIKE sword_2 or ...)
 * The SQL result will be true:
 * - If every sword will be once in one field at least
 *
 * @return  string    SQL query string
 */
  function whereSearch()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot  = $view.'.';

    // Query with OR and AND
    $str_whereOr  = false;
    $arr_whereOr  = array();
    // Query with AND NOT LIKE
    $str_whereNot = false;
    $arr_whereNot = array();



    //////////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of no swords or no search fields

    if (!($this->pObj->arr_swordPhrases && $this->pObj->csvSearch))
    {
      return false;
    }
    // RETURN in case of no swords or no search fields



    //////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] Search fields:<br />'.$this->pObj->csvSearch, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SEARCH] Please configure: views.list.'.$mode.'.search', $this->pObj->extKey, 1);
    }
    // DRS - Development Reporting System



    //////////////////////////////////////////////////////////////////////////
    //
    // Char for Wildcard

    $chr_wildcard = $this->pObj->str_searchWildcardCharManual;
    // Char for Wildcard



    //////////////////////////////////////////////////////////////////////////
    //
    // andWhere AND and OR

    $arrSearchFields = explode(',', $this->pObj->csvSearch);
    $int_sword       = 0;
    foreach ($this->pObj->arr_swordPhrases['or'] as $arr_swords_and)
    {
      // Suggestion #7730
      // The user has to add a wildcard
      if($this->pObj->bool_searchWildcardsManual)
      {
        foreach($arr_swords_and as $key => $value)
        {
          // First char of search word isn't a wildcard
          $int_firstChar = 0;
          if($value[$int_firstChar] != $chr_wildcard)
          {
            $value                = '[[:<:]]'.$value;
            $arr_swords_and[$key] = $value;
          }
          // First char of search word isn't a wildcard
          // First char of search word is a wildcard
          if($value[$int_firstChar] == $chr_wildcard)
          {
            $value                = substr($value, 1, strlen($value) - 1);
            $arr_swords_and[$key] = $value;
          }
          // First char of search word is a wildcard
          // Last char of search word isn't a wildcard
          $int_lastChar = strlen($value) -1;
          if($value[$int_lastChar] != $chr_wildcard)
          {
            $value                = $value.'[[:>:]]';
            $arr_swords_and[$key] = $value;
          }
          // Last char of search word isn't a wildcard
          // Last char of search word is a wildcard
          if($value[$int_lastChar] == $chr_wildcard)
          {
            $value                = substr($value, 0, -1);
            $arr_swords_and[$key] = $value;
          }
          // Last char of search word is a wildcard
        }
      }
      // The user has to add a wildcard
      // Suggestion #7730

      foreach ($arrSearchFields as $arrSearchField)
      {
        list($str_before_as, $str_behind_as)  = explode(' AS ', $arrSearchField);
        list($table, $field)                  = explode('.', $str_before_as);
        $table                                = trim($table);
        $field                                = trim($field);

        // Suggestion #7730
        // Wildcard are used by default
        if(!$this->pObj->bool_searchWildcardsManual)
        {
          $str_wrap_sword      = '%\' AND '.$table.'.'.$field.' LIKE \'%';
          $str_whereTableField = implode($str_wrap_sword, $arr_swords_and);
          $str_whereTableField = $table.'.'.$field.' LIKE \'%'.$str_whereTableField.'%\'';
        }
        // Wildcard are used by default

        // The user has to add a wildcard
        if($this->pObj->bool_searchWildcardsManual)
        {
          $str_wrap_sword      = '\') AND ('.$table.'.'.$field.' REGEXP \'';
          $str_whereTableField = implode($str_wrap_sword, $arr_swords_and);
          $str_whereTableField = '('.$table.'.'.$field.' REGEXP \''.$str_whereTableField.'\')';
        }
        // The user has to add a wildcard
        // Suggestion #7730

        if(count($arr_swords_and) > 1)
        {
          $str_whereTableField = '('.$str_whereTableField.')';
        }
        $arr_whereSword[$int_sword][]             = $str_whereTableField;
      }
      $int_sword++;
    }
    foreach ($arr_whereSword as $key_sword => $arr_fields)
    {
      $str_or     = implode(' OR ',$arr_fields);
      $arr_or[]   = '( '.$str_or.' )';
    }
    $str_whereOr = implode(' OR ', $arr_or);
    $str_whereOr = ' AND ( '.$str_whereOr.' )';
    // andWhere AND and OR



    //////////////////////////////////////////////////////////////////////////
    //
    // andWhere NOT

    if(count($this->pObj->arr_swordPhrases['not']) > 0)
    {
      foreach ($arrSearchFields as $arrSearchField)
      {
        list($str_before_as, $str_behind_as)  = explode(' AS ', $arrSearchField);
        list($table, $field)                  = explode('.', $str_before_as);
        $table                                = trim($table);
        $field                                = trim($field);

        // Suggestion #7730
        // Wildcard are used by default
        if(!$this->pObj->bool_searchWildcardsManual)
        {
          $str_wrap_sword = '%\' AND '.$table.'.'.$field.' NOT LIKE \'%';
          $str_whereNot   = implode($str_wrap_sword, $this->pObj->arr_swordPhrases['not']);
          $str_whereNot   = $table.'.'.$field.' NOT LIKE \'%'.$str_whereNot.'%\'';
        }
        // Wildcard are used by default

        // The user has to add a wildcard
        if($this->pObj->bool_searchWildcardsManual)
        {
          $str_wrap_sword = '\') AND ('.$table.'.'.$field.' NOT REGEXP \'';
          $str_whereNot   = implode($str_wrap_sword, $this->pObj->arr_swordPhrases['not']);

          // First char of search word isn't a wildcard
          $int_firstChar = 0;
          if($str_whereNot[$int_firstChar] != $chr_wildcard)
          {
            $str_whereNot = '[[:<:]]'.$str_whereNot;
          }
          // First char of search word isn't a wildcard
          // First char of search word is a wildcard
          if($str_whereNot[$int_firstChar] == $chr_wildcard)
          {
            $str_whereNot = substr($str_whereNot, 1, strlen($str_whereNot) - 1);
          }
          // First char of search word is a wildcard
          // Last char of search word isn't a wildcard
          $int_lastChar = strlen($str_whereNot) -1;
          if($str_whereNot[$int_lastChar] != $chr_wildcard)
          {
            $str_whereNot = $str_whereNot.'[[:>:]]';
          }
          // Last char of search word isn't a wildcard
          // Last char of search word is a wildcard
          if($str_whereNot[$int_lastChar] == $chr_wildcard)
          {
            $str_whereNot = substr($str_whereNot, 0, -1);
          }
          // Last char of search word is a wildcard
          $str_whereNot = '('.$table.'.'.$field.' NOT REGEXP \''.$str_whereNot.'\')';
        }
        // The user has to add a wildcard
        // Suggestion #7730

        $arr_whereNot[] = $str_whereNot;
      }
      if(count($arr_whereNot) > 0)
      {
        $str_whereNot = implode(' AND ', $arr_whereNot);
        $str_whereNot = ' AND '.$str_whereNot;
      }
    }
    // andWhere NOT



    //////////////////////////////////////////////////////////////////////////
    //
    // RETURN andWhere

    $str_return = $str_whereOr.$str_whereNot;



    //////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] andWhere clause:<br />'.$str_return, $this->pObj->extKey, 0);
      // " AND ( ( tt_news.title LIKE '%Browser%' OR tt_news_cat.title LIKE '%Browser%' ) AND ( tt_news.title LIKE '%Erweiterung%' OR tt_news_cat.title LIKE '%Erweiterung%' ) )"
    }
    // DRS - Development Reporting System

    return $str_return;
    // RETURN andWhere

  }










  /**
 * Relation method: Building the whole where clause
 *
 * @return  string    FALSE or the SQL-where-clause
 */
  function whereClause()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot    = $view.'.';



// 3.3.7
    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.where

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['where'])
    {
      $where = $this->pObj->conf_sql['where'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.where is true. views.'.$viewWiDot.$mode.'.where will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] all andWhere configuration will be ignored too!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] WHERE '.$where, $this->pObj->extKey, 0);
      }
      return $where;
    }
    // RETURN in case of override.where
// 3.3.7



    $whereClause  = false;


    //////////////////////////////////////////////////////////////////////////
    //
    // Get enableFields like hiddden, deleted, starttime ... only for the localTable

    $str_enablefields = $this->str_enableFields($this->pObj->localTable);
    // #11429, cweiske, 101219
    //if (strpos($whereClause, $str_enablefields) === false)
    if ($str_enablefields !== '' && strpos($whereClause, $str_enablefields) === false)
    {
      $whereClause = $whereClause." AND ".$str_enablefields;
    }
    // Get enableFields like hiddden, deleted, starttime ... only for the localTable


    ////////////////////////////////////////////////////////////////////
    //
    // Add localization fields

    $str_local_where = $this->pObj->objLocalize->localizationFields_where($this->pObj->localTable);
    if ($str_local_where)
    {
      $whereClause      = $whereClause." AND ".$str_local_where;
    }
    // Add localization fields


    //////////////////////////////////////////////////////////////////////////
    //
    // Is there a andWhere statement from the filter class?
    if (is_array($this->pObj->arr_andWhereFilter))
    {
      $str_andFilter  = implode(" AND ", $this->pObj->arr_andWhereFilter);
      $whereClause    = $whereClause." AND ".$str_andFilter;
    }
    // Is there a andWhere statement from the filter class?


    //////////////////////////////////////////////////////////////////////////
    //
    // If we have a sword, allocates the global $arr_swordPhrasesTableField

    if ($this->pObj->arr_swordPhrases && $this->pObj->csvSearch)
    {
      $arrSearchFields = explode(',', $this->pObj->csvSearch);
      foreach ($arrSearchFields as $arrSearchField)
      {
        list($str_before_as, $str_behind_as) = explode(' AS ', $arrSearchField);
        list($table, $field)                 = explode('.', $str_before_as);
        $tableField                          = trim($table).'.'.trim($field);
        foreach ($this->pObj->arr_swordPhrases as $sword)
        {
          $this->pObj->arr_swordPhrasesTableField[$tableField][] = $sword;
        }
      }
    }
    // If we have a sword, allocates the global $arr_swordPhrasesTableField


    //////////////////////////////////////////////////////////////////////////
    //
    // Get SWORD, AND WHERE and JOINS

    if ($view == 'list')
    {
      $whereSword = $this->whereSearch();
    }
    $andWhere       = $this->andWhere();
    $arr_result     = $this->get_joins();
    $str_full_join  = $arr_result['data']['full_join'];
    unset($arr_result);
    // Get SWORD, AND WHERE and JOINS


    //////////////////////////////////////////////////////////////////////////
    //
    // Add a FULL JOIN

    if ($str_full_join != '')
    {
      $whereClause .= ' '.$str_full_join;
    }
    // Add a FULL JOIN


    //////////////////////////////////////////////////////////////////////////
    //
    // Add an AND WHERE from TypoScript

    if ($andWhere != '')
    {
      $whereClause .= ' AND '.$andWhere;
    }
    // Add an AND WHERE from TypoScript


    //////////////////////////////////////////////////////////////////////////
    //
    // Process depending on the view (LIST || SINGLE)

    switch($view)
    {
      case('single'):
        // Add the uid of the choosen record
        //$whereClause .= ' AND '.$this->pObj->arrLocalTable['uid'].' = '.$this->pObj->piVars['showUid'];
        $whereClause .= $this->pObj->objLocalize->localizationSingle_where($this->pObj->localTable);
        break;
      case('list'):
        // Add the search clause, if there is a search (sword)
        if ($whereSword != '')
        {
          $whereClause .= ' '.$whereSword;
        }
        break;
    }
    // Process depending on the view (LIST || SINGLE)


    //////////////////////////////////////////////////////////////////////////
    //
    // Add pid IN list

    $str_pidStatement = $this->str_andWherePid($this->pObj->localTable);
    // Do we have a showUid not for the local table but for the foreign table? 3.3.3

    if (strpos($whereClause, $str_pidStatement) === false)
    {
      $whereClause = $whereClause." AND ".$str_pidStatement;
    }
    // Add pid IN list


    //////////////////////////////////////////////////////////////////////////
    //
    // BUGFIX

    // #11430, cweise, 101219
    //if (strpos(Clause, " AND ") == 0)
    if (substr(ltrim($whereClause), 0, 4) == 'AND ')
    {
      //$whereClause = substr($whereClause, strlen(" AND"), strlen($whereClause));
      $whereClause = substr(ltrim($whereClause), 3);
    }
    // BUGFIX


    //////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

// 3.3.7
    // Human readable format
    #10111
    $hr_whereClause = str_replace(',', ', ', $whereClause);
    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devLog('[INFO/SQL] WHERE '.$hr_whereClause, $this->pObj->extKey, -1);
      t3lib_div::devlog('[HELP/SQL] Change it? Use views.'.$viewWiDot.$mode.'.override.where', $this->pObj->extKey, 1);
    }
    // DRS - Development Reporting System

    return $whereClause;

  }











  /**
 * Relation method: Building a further part for the where clause
 *
 * @return  string    TRUE || FALSE or the SQL-where-clause
 */
  function andWhere()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

// 3.3.7
    ////////////////////////////////////////////////////////////////////
    //
    // RETURN in case of override.andWhere

    if($conf['views.'][$viewWiDot][$mode.'.']['override.']['andWhere'])
    {
      $andWhere = $this->pObj->conf_sql['andWhere'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/SQL] override.andWhere is true. views.'.$viewWiDot.$mode.'.andWhere will be ignored!', $this->pObj->extKey, 0);
        t3lib_div::devLog('[INFO/SQL] WHERE ... AND '.$andWhere, $this->pObj->extKey, 0);
      }
      return $andWhere;
    }
    // RETURN in case of override.andWhere
// 3.3.7



    $lAndWhereClause    = false;


    //////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // Take the andWhere clause from the global conf_sql['andWhere']

    if ($this->pObj->conf_sql['andWhere'])
    {
      $lAndWhereClause = $this->pObj->conf_sql['andWhere'];
    }
    // Take the andWhere clause from the global conf_sql['andWhere']


    //////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System
    if (!$lAndWhereClause)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' hasn\'t any local or global andWhere clause. This is OK.', $this->pObj->extKey, 0);
      }
    }
    if ($lAndWhereClause)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] andWhere clause is: '.$lAndWhereClause, $this->pObj->extKey, 0);
      }
   }
    // DRS - Development Reporting System

    return $lAndWhereClause;
  }











  /**
 * Searches in the global arr_realTables_arrFields for tables with pids.
 * If there is one, the method generates an array with all table.pid in the syntax:
 * table.pid IN (pidlist). pidlist is a comma seperated list of uids.
 * If aliases are configured, table will become an alias.
 *
 * @return  array   $arr_andWherePid: Array with statements: table.pid IN (pidlist)
 */
  function arr_andWherePid()
  {
    // Return array
    $arr_andWherePid = false;
    foreach ($this->pObj->arr_realTables_arrFields as $realTable => $arrFields)
    {
      // Has the table a pid?
      if (in_array('pid', $arrFields))
      {
        // Get the syntax table.field
        $tableField = $realTable.'.pid';

        // Replace real name of the table with its alias, if there is an alias
        $tableField = $this->pObj->objSqlFun->set_tablealias($tableField);
        $tableField = $this->pObj->objSqlFun->get_sql_alias_before($tableField);
        // Replace real name of the table with its alias, if there is an alias

        // Push the pid statement to the return array
        $arr_andWherePid[] = $tableField." IN (".$this->pObj->pidList.")";
      }
      // Has the table a pid?
    }
    return $arr_andWherePid;
  }






  /**
 * Return the AND WHERE statement for the pid
 *
 * @param string    $realTable: Name of the current table
 * @return  string    $str_andWherePid: String with statement: table.pid IN (pidlist)
 */
  function str_andWherePid($realTable)
  {
    $conf = $this->pObj->conf;

    // Get the syntax table.field
    $tableField = $realTable.'.pid';

    // Replace real name of the table with its alias, if there is an alias
    $tableField = $this->pObj->objSqlFun->set_tablealias($tableField);
    $tableField = $this->pObj->objSqlFun->get_sql_alias_before($tableField);
    // Replace real name of the table with its alias, if there is an alias

    $str_currPidList = $this->pObj->pidList;
    // Do we have a foreignTable with a pid in the TypoScript?
    if(isset($conf['foreignTables.'][$realTable.'.']['csvPidList']))
    {
      $str_pidList = $conf['foreignTables.'][$realTable.'.']['csvPidList'];
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$realTable.' has a pidList in foreignTables.'.$realTable.'.csvPidList: '.$str_pidList,
          $this->pObj->extKey, 0);
      }
      $int_deep = 0;
      if(isset($conf['foreignTables.'][$realTable.'.']['intDeep']))
      {
        $int_deep = $conf['foreignTables.'][$realTable.'.']['intDeep'];
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] pidList should be exetended with '.$int_deep.' levels. See intDeep.', $this->pObj->extKey, 0);
        }
      }
      if(!isset($conf['foreignTables.'][$realTable.'.']['intDeep']))
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] pidList shouldn\'t be exetended.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/SQL] Change it? Configure foreignTables.'.$realTable.'.intDeep', $this->pObj->extKey, 1);
        }
      }
      $str_currPidList = $this->pObj->pi_getPidList($str_pidList, $int_deep);
    }
    if(!isset($conf['foreignTables.'][$realTable.'.']['csvPidList']))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$realTable.' hasn\'t any own pidList.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/SQL] Change it? Please configure foreignTables.'.$realTable.'.csvPidList', $this->pObj->extKey, 1);
      }
    }
    // Do we have a foreignTable with a pid in the TypoScript?

    // Push the pid statement to the return array
    $str_andWherePid= $tableField." IN (".$str_currPidList.")";
    return $str_andWherePid;
  }






  /**
 * Searches in the global arr_realTables_arrFields for each tables.
 * Each table will get an AND WHERE enablefields statement in the syntax (i.e.)
 * table.deleted = 0 AND table.hidden = 0.
 * If aliases are configured, table will become an alias.
 *
 * @return  array   $arr_andWhereEnablefields: Array with enablefields statements
 */
  function arr_andWhereEnablefields()
  {
    // Return array
    $arr_andWhereEnablefields = false;
    foreach ($this->pObj->arr_realTables_arrFields as $realTable => $arrFields)
    {
      // Get the enablefields statement
      $str_enablefields = $this->pObj->cObj->enableFields($realTable);
      // Cut of the first ' AND '
      $str_enablefields = substr($str_enablefields, 5, strlen($str_enablefields));

      // Replace real name of the table with its alias, if there is an alias
      $tableField = $realTable.'.dummy';
      $tableField = $this->pObj->objSqlFun->set_tablealias($tableField);
      $tableField = $this->pObj->objSqlFun->get_sql_alias_before($tableField);
      list($aliasTable, $field) = explode('.', $tableField);
      $str_enablefields = str_replace($realTable.'.', $aliasTable.'.', $str_enablefields);
      // Replace real name of the table with its alias, if there is an alias

      // Push the enbalefields statement to the return array
      $arr_andWhereEnablefields[] = $str_enablefields;
    }
    return $arr_andWhereEnablefields;
  }






  /**
 * Get the AND WHERE enablefields for the current table. Replace the real name with an alias, if there is an alias.
 *
 * @param string    $realTable: Name of the current table
 * @return  array   $arr_andWhereEnablefields: Array with enablefields statements
 */
  function str_enableFields($realTable)
  {
    $str_enablefields = $this->pObj->cObj->enableFields($realTable);
    // Cut of the first ' AND '
    $str_enablefields = substr($str_enablefields, 5, strlen($str_enablefields));

    // Replace real name of the table with its alias, if there is an alias
    $tableField = $realTable.'.dummy';
    $tableField = $this->pObj->objSqlFun->set_tablealias($tableField);
    $tableField = $this->pObj->objSqlFun->get_sql_alias_before($tableField);
    list($aliasTable, $field) = explode('.', $tableField);
    $str_enablefields = str_replace($realTable.'.', $aliasTable.'.', $str_enablefields);
    // Replace real name of the table with its alias, if there is an alias

    return $str_enablefields;
  }






  /***********************************************
   *
   * Methods for automatic SQL relation building
   *
   **********************************************/





  /**
 * Checks if there should be an automatic configuration process. If yes it fills up $arr_ts_autoconf_relation and $boolAutorelation
 *
 * @return  array   FALSE || $arr_ts_autoconf_relation
 */
  function get_ts_autoconfig_relation()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

    $arr_ts_autoconf_relation = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['autoconfig.']['relations.'];
    switch(is_array($arr_ts_autoconf_relation))
    {
      case(true):
        // We have a local configuration
        $boolAutoconf = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['autoconfig.']['relations'];
        if (!$boolAutoconf)
        {
          // Autoconfiguration shouldn't be used
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.': autoconfig is FALSE. '.
              'We don\'t use an autoconfigured relation building.' , $this->pObj->extKey, 0);
          }
          $this->boolAutorelation = false;
          return false;
        }
        break;
      default:
        // We have a global configuration
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' hasn\'t any local autoconfig array. We try the global one.' ,
          $this->pObj->extKey, 0);
        }
        $boolAutoconf = $this->pObj->conf['autoconfig.']['relations'];
        if (!$boolAutoconf)
        {
          // Autoconfiguration shouldn't be used
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Global autoconfig is FALSE. We don\'t use an autoconfigured relation building.' , $this->pObj->extKey, 0);
          }
          $this->boolAutorelation = false;
          return false;
        }
        $arr_ts_autoconf_relation = $this->pObj->conf['autoconfig.']['relations.'];
        break;
    }

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] Autoconfig is detected.', $this->pObj->extKey, 0);
    }
    return $arr_ts_autoconf_relation;
  }






  /**
 * Generating the $this->arr_relations_mm_simple, an array with the arrays MM and/or simple
 *
 * @return  string    TRUE or $arr_return
 */
  function get_arr_relations_mm_simple()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot  = $view.'.';
    $arr_return = array();


    // RETURN autoconfig is switched off
    if (!$this->boolAutorelation)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] Nothing to do, autoconfig is switched off.', $this->pObj->extKey, 0);
      }
      return true;
    }
    // RETURN autoconfig is switched off

    // Don't do anything but load the TCA, if there is only one table
    if (count($this->pObj->arr_realTables_arrFields) < 2)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] Nothing to do. There is no relation.', $this->pObj->extKey, 0);
      }
      $tables = $this->pObj->arr_realTables_arrFields;
      foreach($tables as $tableKey => $tableValue)
      {
        t3lib_div::loadTCA($tableKey);
        if ($this->pObj->b_drs_tca)
        {
          t3lib_div::devlog('[INFO/TCA] $GLOBALS[\'TCA\'][\''.$tableKey.'\'] is loaded.', $this->pObj->extKey, 0);
        }
      }
      return true;
    }
    // Don't do anything but load the TCA, if there is only one table

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] We try to build SQL relations automatically.', $this->pObj->extKey, 0);
    }


    //////////////////////////////////
    //
    // Get autoconfig configuration

    $boolOneWayOnly           = $this->arr_ts_autoconf_relation['oneWayOnly'];
    $boolSimpleRelations      = $this->arr_ts_autoconf_relation['simpleRelations'];
    $boolSelfReference        = $this->arr_ts_autoconf_relation['simpleRelations.']['selfReference'];
    $boolMMrelations          = $this->arr_ts_autoconf_relation['mmRelations'];
    $allowedTCAconfigTypesCSV = $this->arr_ts_autoconf_relation['TCAconfig.']['type.']['csvValue'];
    $dontUseFieldsCSV         = $this->arr_ts_autoconf_relation['csvDontUseFields'];
    // Get autoconfig configuration


    /////////////////////////////////////
    //
    // Initiate the global boolean for LEFT JOIN

    if ($this->arr_ts_autoconf_relation['left_join'] == 1 || strtoupper($this->arr_ts_autoconf_relation['left_join'] == 'true'))
    {
      $this->b_left_join = true;
    }
    // Initiate the global boolean for LEFT JOIN


    /////////////////////////////////////
    //
    // Development Logging

    if ($this->pObj->b_drs_sql)
    {
      if ($boolOneWayOnly)
      {
        t3lib_div::devlog('[INFO/SQL] Use only relations in the TCA of the local table '.$this->pObj->localTable.'.', $this->pObj->extKey, 0);
      }
      else
      {
        t3lib_div::devlog('[INFO/SQL] Use relations in the TCA of the local table '.$this->pObj->localTable.' and of foreign tables.',
        $this->pObj->extKey, 0);
      }
      if ($boolSimpleRelations)
      {
        t3lib_div::devlog('[INFO/SQL] Use simple relations.', $this->pObj->extKey, 0);
        if ($boolSelfReference)
        {
          t3lib_div::devlog('[INFO/SQL] Use self references in simple relations.', $this->pObj->extKey, 0);
        }
        else
        {
          t3lib_div::devlog('[INFO/SQL] Don\'t use self references in simple relations.', $this->pObj->extKey, 0);
        }
      }
      else
      {
        t3lib_div::devlog('[INFO/SQL] Don\'t use simple relations.', $this->pObj->extKey, 0);
      }
      if ($boolMMrelations)
      {
        t3lib_div::devlog('[INFO/SQL] Use MM relations.', $this->pObj->extKey, 0);
      }
      else
      {
        t3lib_div::devlog('[INFO/SQL] Don\'t use MM relations.', $this->pObj->extKey, 0);
      }
      if ($this->b_left_join)
      {
        t3lib_div::devlog('[INFO/SQL] Use LEFT JOIN is TRUE.', $this->pObj->extKey, 0);
      }
      else
      {
        t3lib_div::devlog('[INFO/SQL] Use LEFT JOIN is FALSE. We use FULL JOINS.', $this->pObj->extKey, 0);
      }
    }
    // Development Logging


    //////////////////////////////////
    //
    // Process csv values

    // get config.type (should be select and/or group)
    $arrTCAtypes = explode(',', $allowedTCAconfigTypesCSV);
    foreach($arrTCAtypes as $key => $value)
    {
      $arrTCAtypes[trim($key)] = trim($value);
    }
    if ($this->pObj->b_drs_sql)
    {
      $csvTmpPrompt = implode(', ', $arrTCAtypes);
      t3lib_div::devlog('[INFO/SQL] Use only TCA columns with config.type: '.$csvTmpPrompt, $this->pObj->extKey, 0);
    }
    // get field names, which shouldn't processed for relation building
    if ($dontUseFieldsCSV)
    {
      $arrNoColumns = explode(',', $dontUseFieldsCSV);
      foreach($arrNoColumns as $key => $value)
      {
        list($table, $field) = explode('.', $value);
        $arrNoColumns[][trim($table)] = trim($field);
        unset($arrNoColumns[$key]);
        if ($this->pObj->b_drs_sql)
        {
          $arrDontUseFields[] = $table.'.'.$field;
        }
      }
    }
    if (($this->pObj->b_drs_sql) && is_array($arrDontUseFields))
    {
      $csvTmpPrompt = implode(', ', $arrDontUseFields);
      t3lib_div::devlog('[INFO/SQL] If this fields have a relation, don\'t use it: '.$csvTmpPrompt, $this->pObj->extKey, 0);
    }
    // Process csv values


    //////////////////////////////////////////////////////////////////
    //
    // Loop through the TCA of the foreign tables

    $tables = $this->pObj->arr_realTables_arrFields;
    foreach($tables as $tableKey => $tableValue)
    {
      t3lib_div::loadTCA($tableKey);
      if ($this->pObj->b_drs_tca)
      {
        t3lib_div::devlog('[INFO/TCA] $GLOBALS[\'TCA\'][\''.$tableKey.'\'] is loaded.', $this->pObj->extKey, 0);
      }
      $arrColumns = $GLOBALS['TCA'][$tableKey]['columns'];
      if (is_array($arrColumns))
      {
        foreach($arrColumns as $columnsKey => $columnsValue)
        {
          $config = $columnsValue['config'];


          //////////////////////////////////////////////
          //
          // Look for a destination table

          if ($this->pObj->b_drs_tca)
          {
            t3lib_div::devlog('[INFO/TCA] \''.$tableKey.'.'.$columnsKey.'.config.type: \''.$config['type'].'\'', $this->pObj->extKey, 0);
          }
          $boolDB           = true;
          $foreignTable     = false;
          $arrForeignTables = false;
          if ($config['internal_type'] && $config['internal_type'] != 'db')
          {
            // We don't have any field with any relation
            $boolDB = false;
            if ($this->pObj->b_drs_tca && in_array($config['type'], $arrTCAtypes))
            {
              t3lib_div::devlog('[INFO/TCA] \''.$tableKey.'.'.$columnsKey.'.config.internal_type isn\'t \'db\': There isn\'t any relation.', $this->pObj->extKey, 0);
            }
          }

          // There is a different workflow for select and group
          if ($boolDB && in_array('select', $arrTCAtypes) && $config['type'] == 'select')
          {
            // Config.internal_type is 'db', user wants to process config.type 'select', the config.type is 'select'
            $foreignTable = $config['foreign_table'];
            if ($this->pObj->b_drs_sql)
            {
              t3lib_div::devlog('[INFO/SQL] TCA \''.$tableKey.'.'.$columnsKey.'.config.foreign_table: \''.$foreignTable.'\'', $this->pObj->extKey, 0);
            }
          }
          if ($boolDB && in_array('group',  $arrTCAtypes) && $config['type'] == 'group')
          {
            // Config.internal_type is 'db', user wants to process config.type 'group', the config.type is 'group'
            $arrForeignTables = $this->pObj->objZz->getCSVasArray($config['allowed']);
            if ($this->pObj->b_drs_sql)
            {
              $csvForeignTables = implode(', ', $arrForeignTables);
              t3lib_div::devlog('[INFO/SQL] TCA \''.$tableKey.'.'.$columnsKey.'.config.allowed: \''.$csvForeignTables.'\'', $this->pObj->extKey, 0);
            }
            if (count($arrForeignTables) > 1 && ($this->pObj->b_drs_sql || $this->pObj->b_drs_error))
            {
              t3lib_div::devlog('[WARN/SQL] TCA \''.$tableKey.'.'.$columnsKey.'.config.allowed has more than one table.', $this->pObj->extKey, 2);
              t3lib_div::devlog('[ERROR/SQL] But '.$this->pObj->extKey.' can\'t process more than one table.', $this->pObj->extKey, 3);
              t3lib_div::devlog('[HELP/SQL] Please configure your SQL relation manually.', $this->pObj->extKey, 1);
            }
            $foreignTable = $arrForeignTables[0];
          }


          //////////////////////////////////////////////
          //
          // Only process if there is a foreign_table and if it is used
//var_dump('sql_auto 1639', $foreignTable);
          if ($foreignTable && in_array($foreignTable, array_keys($tables)))
          {
            // There is a relation between the used table and the current table is in the TCA
            $boolRelation = true;
            if ($boolOneWayOnly)
            {
              // Don't use relations from foreign tables
              if ($tableKey != $this->pObj->localTable)
              {
                $boolRelation = false;
              }
            }
            if ($boolRelation && !in_array($config['type'], $arrTCAtypes))
            {
              // The TCA.table.column.x.config.type isn't an element in the TS allowedTCAconfigTypes
              $boolRelation = false;
            }
            if ($boolRelation && is_array($arrNoColumns))
            {
              // We should build a relation, but we have to check if it isn't one of the forbidden table.fields
              foreach($arrNoColumns as $ncKey => $ncValue)
              {
                // var_dump($tableKey, $ncValue[$tableKey], $columnsKey);
                // -> "tx_civserv_service", "sv_organisation", "sv_similar_services"
                if ($ncValue[$tableKey] == $columnsKey) {
                  // The column is an element in the TS dontUseFields. It is forbidden.
                  $boolRelation = false;
                }
              }
            }
            if ($boolRelation)
            {
              // The TCA config has a MM array
              if($config['MM'])
              {
                // Don't process MM relations automatically
                if (!$boolMMrelations)
                {
                  if ($this->pObj->b_drs_sql)
                  {
                    t3lib_div::devlog('[INFO/SQL] Result (MM): '.$tableKey.' - '.$config['MM'].' - '.$foreignTable, $this->pObj->extKey, 0);
                    t3lib_div::devlog('[INFO/SQL] But MM relations shouldn\'t processed automatically.', $this->pObj->extKey, 0);
                    t3lib_div::devlog('[HELP/SQL] If you want to process it automatically, please enable the MM relation building.', $this->pObj->extKey, 1);
                  }
                }
                // Don't process MM relations automatically
                // Process MM relation automatically
                if ($boolMMrelations)
                {
                  $arr_return['MM'][$tableKey][$config['MM']] = $foreignTable;
                  // #9697, 100912, dwildt
                  if(!empty($config['MM_opposite_field']))
                  {
                    $this->arr_relations_opposite[$tableKey][$config['MM']]['MM_opposite_field']  = $config['MM_opposite_field'];
                  }
                  // #9697, 100912, dwildt
                  if ($this->pObj->b_drs_sql)
                  {
                    t3lib_div::devlog('[INFO/SQL] Result (MM): '.$tableKey.' - '.$config['MM'].' - '.$foreignTable, $this->pObj->extKey, -1);
                    t3lib_div::devlog('[HELP/SQL] Switch off the result above? Use TS config: '.
                      'autoconfig.relations.csvDontUseFields = ..., '.$tableKey.'.'.$columnsKey.', ...', $this->pObj->extKey, 1);
                  }
                  if ($this->pObj->b_drs_sql && $config['foreign_table_where'])
                  {
                    t3lib_div::devlog('[WARN/SQL] In TCA is foreign_table_where configured. This may be a risk, because the browser won\'t '.
                      'process the clause: '.$config['foreign_table_where'], $this->pObj->extKey, 2);
                  }
                }
                // Process MM relation automatically
              }
              // The TCA config has a MM array

              // The TCA config has a simple relation - a foreign_table but no MM array
              if(!$config['MM'])
              {
                // Don't process simple relations automatically
                if (!$boolSimpleRelations)
                {
                  if ($this->pObj->b_drs_sql)
                  {
                    t3lib_div::devlog('[INFO/SQL] Result (simple): '.$tableKey.'.'.$columnsKey.' - '.$foreignTable, $this->pObj->extKey, 0);
                    t3lib_div::devlog('[INFO/SQL] But simple relations shouldn\'t processed automatically.', $this->pObj->extKey, 0);
                    t3lib_div::devlog('[HELP/SQL] If you want to process it automatically, please enable the simple relation building.', $this->pObj->extKey, 1);
                  }
                }
                // Don't process simple relations automatically
                // Process simple relations automatically
                if ($boolSimpleRelations)
                {
                  // Foreign table is the local table, but self references aren't allowed
                  $boll_process = true;
                  if ($this->pObj->localTable == $foreignTable && !$boolSelfReference)
                  {
                    if ($this->pObj->b_drs_sql)
                    {
                      t3lib_div::devlog('[INFO/SQL] Result (simple): '.$tableKey.'.'.$columnsKey.' - '.$foreignTable, $this->pObj->extKey, 0);
                      t3lib_div::devlog('[INFO/SQL] It is a self reference. But self references shouldn\'t processed automatically.', $this->pObj->extKey, 0);
                      t3lib_div::devlog('[HELP/SQL] If you want to process it automatically, please self reference relation building.', $this->pObj->extKey, 1);
                    }
                    $boll_process = false;
                  }
                  // Foreign table is the local table, but self references aren't allowed
                  // Foreign table isn't the local table or self references are allowed
                  if ($boll_process)
                  {
                    // Build the simple relation
                    $arr_return['simple'][$tableKey][$columnsKey] = $foreignTable;
                    if ($this->pObj->b_drs_sql)
                    {
                      t3lib_div::devlog('[INFO/SQL] Result (simple): '.$tableKey.'.'.$columnsKey.' - '.$foreignTable, $this->pObj->extKey, -1);
                      t3lib_div::devlog('[HELP/SQL] Switch off the result above? Use TS config: '.
                          'autoconfig.relations.csvDontUseFields = ..., '.$tableKey.'.'.$columnsKey.', ...', $this->pObj->extKey, 1);
                    }
                    if ($this->pObj->b_drs_sql && $config['foreign_table_where'])
                    {
                      t3lib_div::devlog('[WARN/SQL] In TCA is foreign_table_where configured. This may be a risk, because the browser won\'t '.
                          'process the clause: '.$config['foreign_table_where'], $this->pObj->extKey, 2);
                    }
                  }
                  // Foreign table isn't the local table or self references are allowed
                }
                // Process simple relations automatically
              }
            }
          }
        }
      }
    }
    // Loop through the TCA of the foreign tables

    return $arr_return;
  }

















  /***********************************************
   *
   * Manual SQL Query Building
   *
   **********************************************/


  /**
 * The method returns a SQL query.
 *
 * @param string    $select: SELECT clause
 * @param string    $from:   FROM clause
 * @param string    $where:  WHERE clause
 * @param string    $group:  GROUP clause
 * @param string    $order:  ORDER clause
 * @param string    $limit:  LIMIT clause
 * @return  string    SQL query
 */
  function get_sql_query($select, $from, $where, $group, $order, $limit)
  {

    $str_query = ''.
'  ###SELECT###
  ###FROM###
  ###WHERE###
  ###GROUP###
  ###ORDER###
  ###LIMIT###
';

  }


















}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_auto.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_auto.php']);
}

?>