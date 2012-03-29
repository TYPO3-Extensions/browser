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
 * The class tx_browser_pi1_sql_auto bundles SQL methods for this case: The user has defined a SELECT
 * statement only. Browser should generate a full sql query automatically
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 *
 * @version     3.9.12
 * @since       3.9.12
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   90: class tx_browser_pi1_sql_auto
 *  134:     public function __construct($parentObj)
 *
 *              SECTION: Statements
 *  164:     public function get_statements( )
 *
 *              SECTION: Statements SELECT
 *  294:     private function get_statements_select( )
 *  323:     private function get_statements_from( )
 *  469:     private function get_statements_orderBy()
 *  491:     private function get_statements_groupBy( )
 *
 *              SECTION: Statements WHERE
 *  518:     private function get_statements_where( )
 *  729:     function get_joins( )
 *
 *              SECTION: WHERE helper
 * 1221:     function whereSearch()
 * 1479:     function andWhere()
 * 1563:     function arr_andWherePid()
 * 1596:     function str_andWherePid($realTable)
 * 1665:     function arr_andWhereEnablefields()
 * 1701:     function str_enableFields($realTable)
 *
 *              SECTION: Relation building
 * 1739:     private function init_class_relations( )
 * 1847:     private function init_class_relationsMm( $table, $config, $foreignTable )
 * 1907:     private function init_class_relationsSingle( $table, $columnsKey, $foreignTable)
 * 1978:     private function relations_confDRSprompt( )
 * 2055:     private function relations_dontUseFields( )
 * 2108:     private function relations_getForeignTable( $tables, $config, $configPath )
 * 2175:     private function relations_requirements( $table, $config, $configPath )
 *
 *              SECTION: Helper
 * 2288:     private function init_class_boolAutorelation( )
 * 2364:     private function init_class_bLeftJoin( )
 * 2402:     private function init_class_statementTables( $type, $csvStatement )
 * 2447:     private function init_class_statementTablesByFilter( )
 * 2468:     private function zz_addUid( $type, $csvStatement )
 * 2512:     private function zz_dieIfOverride( $type )
 * 2561:     private function zz_loadTCAforAllTables( )
 * 2583:     private function zz_setToRealTableNames( $csvStatement )
 * 2607:     private function zz_woForeignTables( $type, $csvStatement )
 *
 * TOTAL FUNCTIONS: 30
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_auto
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



    // [Boolean] If it is TRUE, browser should try to build relations automatically
  var $boolAutorelation = true;

  var $arr_relations_mm_simple;
  // [Array] Array with the arrays MM and/or simple
  var $arr_relations_opposite;
  // [Array] Array with ...
  var $b_left_join = false;
  // [Boolean] TRUE if we should use LEFT JOIN. From TypoScript global or local autoconfig.relations.left_join

    // [Array] array like $statementTables['select']['localtable']['tx_org_cal'] = 'tx_org_cal'
  var $statementTables = null;
    // [Array] array like $addedTableFields['select']['tx_org_cal'][] = 'tx_org_cal.uid'
  var $addedTableFields = null;




/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  public function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }









  /***********************************************
   *
   * Statements
   *
   **********************************************/



/**
 * get_statements( ): It returns the statements for a SQL query:
 *                    SELECT, FROM, WHERE, ORDER BY, LIMIT
 *                    GROUP BY isn't handled
 *
 * @return	array		$arr_return : contains statements or an error message
 * @version 3.9.12
 * @since   3.9.12
 */
  public function get_statements( )
  {
      // Add filter tables to class var $statementTables
    $this->init_class_statementTablesByFilter( );

      // Get SELECT
    $arr_return['data']['select'] = $this->get_statements_select( );
    if ( ! $arr_return['data']['select'] )
    {
      $str_header = '<h1 style="color:red">' .
                      $this->pObj->pi_getLL( 'error_sql_h1' ).
                    '</h1>';
      $str_prompt = '<p style="color:red; font-weight:bold;">' .
                      $this->pObj->pi_getLL('error_sql_select') .
                    '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Get SELECT

    $this->zz_loadTCAforAllTables( );
    // Load the TCA for all tables
    foreach( $this->statementTables['select'] as $localForeign => $tables )
    {
      foreach( $tables as $table)
      {
        $this->pObj->objZz->loadTCA($table);
      }
    }
      // Load the TCA for all tables


//    // Set the global groupBy
//    $this->get_statements_groupBy();


      // Get ORDER BY
    $arr_return['data']['orderBy'] = $this->get_statements_orderBy( );
    if (!$arr_return['data']['orderBy'])
    {
      $str_header = '<h1 style="color:red">' .
                      $this->pObj->pi_getLL( 'error_sql_h1' ) .
                    '</h1>';
      $str_prompt = '<p style="color:red; font-weight:bold;">' .
                      $this->pObj->pi_getLL( 'error_sql_orderby' ) .
                    '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Get ORDER BY


      // Get Relations
    $this->init_class_boolAutorelation( );
    $this->init_class_relations( );
var_dump( __METHOD__, __LINE__, $this->arr_relations_mm_simple );
        // Get Relations


      // Get WHERE and FROM
    $arr_return['data']['where']  = $this->get_statements_where( );
      // From has to be the last, because whereClause can have new tables.
    $arr_return['data']['from']   = $this->get_statements_from( );
      // Get WHERE and FROM


      // Enable the ordering by table_mm.sorting
    $str_mmSorting = false;
    $arr_mmSorting = false;
    if(is_array($this->pObj->arrConsolidate['select']['mmSortingTableFields']))
    {
      foreach((array) $this->pObj->arrConsolidate['select']['mmSortingTableFields'] as $tableField)
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
      // Enable the ordering by table_mm.sorting


      // Replace Markers for pidlist and uid
    $str_pid_list = $this->pObj->pidList;
      // For human readable
    $str_pid_list = str_replace(',', ', ', $str_pid_list);

    foreach((array) $arr_return['data'] as $str_query_part => $str_statement)
    {
      $str_statement                        = str_replace('###PID_LIST###', $str_pid_list,                  $str_statement);
      $str_statement                        = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_statement);
      $arr_return['data'][$str_query_part]  = $str_statement;
    }

    return $arr_return;
  }









  /***********************************************
   *
   * Statements SELECT
   *
   **********************************************/



/**
 * get_statements_select( ): It returns the select statement for a SQL query.
 *            If tables hasn't any uid in the SELECT, table.uid will be added.
 *            If required localisation fields will added too.
 *            Added fields will added to the consolidation array.
 *
 * @return	string		SQL select or FALSE, if there is an error
 * @version 3.9.12
 * @since   3.9.12
 */
  private function get_statements_select( )
  {
      // DIE in case of override.select
    $this->zz_dieIfOverride( 'select' );

      // Remove all expressions and aliases in the SELECT statement
    $csvSelect = $this->zz_setToRealTableNames( $this->conf_view['select'] );

      // Devide in local table and foreign tables
    $this->init_class_statementTables( 'select', $csvSelect );

      // Remove foreign tables
    $csvSelect = $this->zz_woForeignTables( 'select', $csvSelect );

      // Add table.uid
    $csvSelect = $this->zz_addUid( 'select', $csvSelect );

    return $csvSelect;
  }









  /***********************************************
   *
   * Statements FROM
   *
   **********************************************/



/**
 * get_statements_from( ): The method returns the FROM clause for the SQL query
 *
 * @return	string		SQL from
 * @version 3.9.12
 * @since   3.9.12
 */
  private function get_statements_from( )
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';

      // DIE in case of override.from
    $this->zz_dieIfOverride( 'from' );




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









  /***********************************************
   *
   * Statements ORDER BY, GROUP BY (disabled)
   *
   **********************************************/



/**
 * It returns the order part for SQL where clause.
 * If there are piVars, the order of the piVars will preferred.
 * Otherwise it returns the TypoScript configuration.
 * If there aren't piVars and there aren't a TypoSCript configuration, it will be empty.
 * If there are aliases, the aliases will be deleted.
 *
 * @return	string		$orderBy: SQL ORDER BY clause.
 * @version 3.9.12
 * @since   3.9.12
 */
  private function get_statements_orderBy()
  {
//      // DIE in case of override.from
//    $this->zz_dieIfOverride( 'orderBy' );
//
//    $csvOrder = $this->conf_view['orderBy'];
//
//      // Set the orderBy by piVars
//    $csvOrder = $this->pObj->objSqlFun->zz_prependPiVarSort( $csvOrder );
//
//    return $csvOrder;
  }



/**
 * get_statements_groupBy( )
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function get_statements_groupBy( )
  {
  }









  /***********************************************
   *
   * Statements WHERE
   *
   **********************************************/



/**
 * Relation method: Building the whole where clause
 *
 * @return	string		FALSE or the SQL-where-clause
 * @version 3.9.12
 * @since   3.9.12
 */
  private function get_statements_where( )
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
    // Add localisation fields

    $str_local_where = $this->pObj->objLocalise->localisationFields_where($this->pObj->localTable);
    if ($str_local_where)
    {
      $whereClause      = $whereClause." AND ".$str_local_where;
    }
    // Add localisation fields


      //////////////////////////////////////////////////////////////////////////
      //
      // Is there a andWhere statement from the filter class?
    if ( is_array( $this->pObj->arr_andWhereFilter ) )
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
        $whereClause .= $this->pObj->objLocalise->localisationSingle_where($this->pObj->localTable);
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
      t3lib_div::devLog('[INFO/SQL] WHERE '.$hr_whereClause, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SQL] Change it? Use views.'.$viewWiDot.$mode.'.override.where', $this->pObj->extKey, 1);
    }
    // DRS - Development Reporting System

    return $whereClause;

  }


















    /***********************************************
    *
    * WHERE helper
    *
    **********************************************/


/**
 * It returns the part for the where clause with a search, if there are search fields in the TS and a piVar sword.
 * The where clause will have this structure:
 *   (field_1 LIKE sword_1 or field_2 LIKE sword_1 or ...) AND (field_1 LIKE sword_2 or field_2 LIKE sword_2 or ...)
 * The SQL result will be true:
 * - If every sword will be once in one field at least
 *
 * @return	string		SQL query string
 */
  private function whereSearch()
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
        foreach((array) $arr_swords_and as $key => $value)
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
 * Relation method: Building a further part for the where clause
 *
 * @return	string		TRUE || FALSE or the SQL-where-clause
 */
  private function andWhere()
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
 * @return	array		$arr_andWherePid: Array with statements: table.pid IN (pidlist)
 */
  private function arr_andWherePid()
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
        $tableField = $this->pObj->objSqlFun_3x->set_tablealias($tableField);
        $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before($tableField);
        // Replace real name of the table with its alias, if there is an alias

        // Push the pid statement to the return array
        $arr_andWherePid[] = $tableField . " IN (" . $this->pObj->pidList . ")";
      }
      // Has the table a pid?
    }
    return $arr_andWherePid;
  }



/**
 * Return the AND WHERE statement for the pid
 *
 * @param	string		$realTable: Name of the current table
 * @return	string		$str_andWherePid: String with statement: table.pid IN (pidlist)
 */
  private function str_andWherePid($realTable)
  {
    $conf = $this->pObj->conf;

    // Get the syntax table.field
    $tableField = $realTable.'.pid';

    // Replace real name of the table with its alias, if there is an alias
    $tableField = $this->pObj->objSqlFun_3x->set_tablealias($tableField);
    $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before($tableField);
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
 * @return	array		$arr_andWhereEnablefields: Array with enablefields statements
 */
  private function arr_andWhereEnablefields()
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
      $tableField = $this->pObj->objSqlFun_3x->set_tablealias($tableField);
      $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before($tableField);
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
 * @param	string		$realTable: Name of the current table
 * @return	array		$arr_andWhereEnablefields: Array with enablefields statements
 */
  private function str_enableFields($realTable)
  {
    $str_enablefields = $this->pObj->cObj->enableFields($realTable);
    // Cut of the first ' AND '
    $str_enablefields = substr($str_enablefields, 5, strlen($str_enablefields));

    // Replace real name of the table with its alias, if there is an alias
    $tableField = $realTable.'.dummy';
    $tableField = $this->pObj->objSqlFun_3x->set_tablealias($tableField);
    $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before($tableField);
    list($aliasTable, $field) = explode('.', $tableField);
    $str_enablefields = str_replace($realTable.'.', $aliasTable.'.', $str_enablefields);
    // Replace real name of the table with its alias, if there is an alias

    return $str_enablefields;
  }






  /***********************************************
   *
   * Relation building
   *
   **********************************************/



/**
 * init_class_relations( ): Inits the class var $arr_relations_mm_simple,
 *                                an array with the arrays MM and/or simple
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_relations( )
  {
      // RETURN : autoconfig is switched off
    if ( ! $this->boolAutorelation )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Nothing to do, autoconfig is switched off.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN : autoconfig is switched off

      // RETURN IF : no foreign table.
    if( ! isset ( $this->statementTables['select']['foreigntable'] ) )
    {
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'Autorelation building isn\'t needed, there isn\'t any foreign table.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN IF : no foreign table.

      // Prompt the current TypoScript configuration to the DRS
    $this->relations_confDRSprompt( );

      // Initialises the class var $b_left_join
    $this->init_class_bLeftJoin( );

      // Get all used tables
    $tables = $this->statementTables['all']['localtable'];
    $tables = $tables + $this->statementTables['all']['foreigntable'];

      // LOOP tables
    foreach( (array ) $tables as $table )
    {
        // Get the TCA array of the current column
      $arrColumns = $GLOBALS['TCA'][$table]['columns'];

        // CONTINUE : current table hasn't any TCA columns
      if( ! is_array( $arrColumns ) )
      {
        continue;
      }
        // CONTINUE : current table hasn't any TCA columns

        // LOOP each TCA column
      foreach( ( array ) $arrColumns as $columnsKey => $columnsValue )
      {
          // Get the TCA configuration of the current column
        $config     = $columnsValue['config'];
          // Get the TCA configuration path of the current column
        $configPath = $table . '.' . $columnsKey . '.config.';

          // CONTINUE : requirements aren't met
        if( ! $this->relations_requirements( $table, $config, $configPath ) )
        {
          continue;
        }
          // CONTINUE : requirements aren't met

          // Get the foreign table
        $foreignTable = $this->relations_getForeignTable( $tables, $config, $configPath );
          // CONTINUE : there is no foreign table
        if( empty ( $foreignTable ) )
        {
          continue;
        }
          // CONTINUE : there is no foreign table
          // Get the foreign table

          // SWITCH mm or single
        switch( true )
        {
          case( $config['MM'] ):
            $this->init_class_relationsMm( $table, $config, $foreignTable );
            break;
          case( ! $config['MM'] ):
          default:
            $this->init_class_relationsSingle( $table, $columnsKey, $foreignTable);
            break;
        }
          // SWITCH mm or single
      }
        // LOOP each TCA column
    }
      // LOOP tables

    return;
  }



/**
 * init_class_relationsMm( ): Sets the class vars
 *          arr_relations_mm_simple['MM'][$table][$config['MM']]
 *          arr_relations_opposite[$table][$config['MM']]['MM_opposite_field']
 *
 * @param	string		$$table       : current table from used tables
 * @param	array		$config       : configuration of the current TCA column
 * @param	string		$foreignTable : current foreign table from TCA
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_relationsMm( $table, $config, $foreignTable )
  {
    $boolMMrelations = $this->pObj->conf['autoconfig.']['relations.']['mmRelations'];

      // RETURN IF : mmRelations should set manually
    if( ! $boolMMrelations )
    {
        // DRS
      if( $this->pObj->b_drs_tca )
      {
        $prompt = 'Result (MM): ' . $table . ' - ' . $config['MM'] . ' - ' . $foreignTable;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'But MM relations shouldn\'t processed automatically.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'If you want to process it automatically, please enable the MM relation building.';
        t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
        // DRS
      return;
    }
      // RETURN IF : mmRelations should set manually

    $this->arr_relations_mm_simple['MM'][$table][$config['MM']] = $foreignTable;

    if( ! empty($config['MM_opposite_field'] ) )
    {
      $this->arr_relations_opposite[$table][$config['MM']]['MM_opposite_field']  = $config['MM_opposite_field'];
    }

      // DRS
    if ( $this->pObj->b_drs_tca )
    {
      $prompt = 'Result (MM): ' . $table . ' - ' . $config['MM'] . ' - ' . $foreignTable;
      t3lib_div::devlog('[OK/SQL] ' . $prompt , $this->pObj->extKey, -1);
      $prompt = 'Switch off the result above? Use TS config: ' .
        'autoconfig.relations.csvDontUseFields = ..., ' . $table . '.' . $columnsKey . ', ...';
      t3lib_div::devlog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      if( $config['foreign_table_where'] )
      {
        $prompt = 'In TCA is foreign_table_where configured. This may be a risk, ' .
          'because the TYPO3 Browser won\'t process the clause: ' .
          $config['foreign_table_where'];
        t3lib_div::devlog('[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2);
      }
    }
      // DRS
  }



/**
 * init_class_relationsSingle( ): Sets the class var $arr_relations_mm_simple['simple']
 *
 * @param	string		$$table       : current table from used tables
 * @param	string		$columnsKey   : current column name from TCA
 * @param	string		$foreignTable : current foreign table from TCA
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_relationsSingle( $table, $columnsKey, $foreignTable)
  {
    $boolSimpleRelations = $this->pObj->conf['autoconfig.']['relations.']['simpleRelations'];

      // RETURN IF : Don't process simple relations automatically
    if( ! $boolSimpleRelations )
    {
        // DRS
      if( $this->pObj->b_drs_tca )
      {
        $prompt = 'Result (simple): ' . $table . '.' . $columnsKey . ' - ' . $foreignTable;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'But simple relations shouldn\'t processed automatically.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want to process it automatically, please enable the simple relation building.';
        t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return;
    }
      // RETURN IF : Don't process simple relations automatically

      // Foreign table is the local table, but self references aren't allowed
    if( $this->pObj->localTable == $foreignTable &&  ! $boolSelfReference )
    {
        // DRS
      if( $this->pObj->b_drs_tca )
      {
        $prompt = 'Result (simple): ' . $table . '.' . $columnsKey . ' - ' . $foreignTable;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'It is a self reference. But self references shouldn\'t processed automatically.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want to process it automatically, please enable self reference relation building.';
        t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return;
    }

    $this->arr_relations_mm_simple['simple'][$table][$columnsKey] = $foreignTable;

      // DRS
    if ($this->pObj->b_drs_tca )
    {
      $prompt = 'Result (simple): ' . $table . '.' . $columnsKey . ' - ' . $foreignTable;
      t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
      $prompt = 'Switch off the result above? Use TS config: '.
                'autoconfig.relations.csvDontUseFields = ..., ' .
                $table . '.' . $columnsKey . ', ...';
      t3lib_div::devlog( '[HELP/SQL]' . $prompt, $this->pObj->extKey, 1 );
      if( $config['foreign_table_where'] )
      {
        $prompt = 'In TCA is foreign_table_where configured. This may be a risk, ' .
                  'because the TYPO3 Browser won\'t process the clause: ' .
                  $config['foreign_table_where'];
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
    }
      // DRS
  }



/**
 * relations_confDRSprompt( ):  Prompts to the DRS the current TypoScript
 *                            configuration for relation building
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function relations_confDRSprompt( )
  {
      // RETURN : DRS is disabled
    if( ! $this->pObj->b_drs_sql && ! $this->pObj->b_drs_tca )
    {
      return;
    }
      // RETURN : DRS is disabled

      // Get TypoScript configuration
    $boolOneWayOnly       = $this->pObj->conf['autoconfig.']['relations.']['oneWayOnly'];
    $boolSimpleRelations  = $this->pObj->conf['autoconfig.']['relations.']['simpleRelations'];
    $boolSelfReference    = $this->pObj->conf['autoconfig.']['relations.']['simpleRelations.']['selfReference'];
    $boolMMrelations      = $this->pObj->conf['autoconfig.']['relations.']['mmRelations'];
    $csvAllowedTCAtypes   = $this->pObj->conf['autoconfig.']['relations.']['TCAconfig.']['type.']['csvValue'];
    $dontUseFieldsCSV     = $this->pObj->conf['autoconfig.']['relations.']['csvDontUseFields'];
      // Get TypoScript configuration

    if( $boolOneWayOnly )
    {
      $prompt = 'Use only relations in the TCA of the local table ' .
                $this->pObj->localTable . '.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    else
    {
      $prompt = 'Use relations in the TCA of the local table ' .
                $this->pObj->localTable . ' and of foreign tables.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    if( $boolSimpleRelations )
    {
      $prompt = 'Use simple relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      if ($boolSelfReference)
      {
        $prompt = 'Use self references in simple relations.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      else
      {
        $prompt = 'Don\'t use self references in simple relations.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    else
    {
      $prompt = 'Don\'t use simple relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    if ( $boolMMrelations )
    {
      $prompt = 'Use MM relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    else
    {
      $prompt = 'Don\'t use MM relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }

    $prompt = 'Only TCA config.types \'' . $csvAllowedTCAtypes .
              '\' will used for autorelation building.';
    t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      // DRS
  }



/**
 * relations_dontUseFields( ):  Returns an array with tablefields, which shouldn't
 *                              used for relation building.
 *
 * @return	array		$arr_return : table.fields, which shouldn't used for relation building
 * @version 3.9.12
 * @since   3.9.12
 */
  private function relations_dontUseFields( )
  {
      // Get TypoScript configuration
    $dontUseFieldsCSV = $this->pObj->conf['autoconfig.']['relations.']['csvDontUseFields'];

    if( empty ( $dontUseFieldsCSV ) )
    {
      return;
    }

    $arrDontUseTableFields = $this->pObj->objZz->getCSVasArray( $dontUseFieldsCSV );

      // LOOP $tableFields
    foreach( ( array ) $arrDontUseTableFields as $key => $tableField )
    {
      $arr_return[] = $tableField;
        // DRS
      if ( $this->pObj->b_drs_sql )
      {
        $arrDRSprompt[] = $tableField;
      }
        // DRS
    }
      // LOOP $tableFields

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      if( is_array( $arrDRSprompt ) )
      {
        $csvDRSprompt = implode( ', ', $arrDRSprompt );
        $prompt = 'If this fields have a relation, don\'t use it: ' . $csvDRSprompt;
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // DRS

    return $arr_return;
  }



/**
 * relations_getForeignTable( ): Returns the foreign table from the
 *                               configuration of the current TCA column
 *
 * @param	string		$table        : current table from used tables
 * @param	array		$config       : configuration of current TCA column
 * @param	string		$configPath   : configuration path of curren TCA columen
 * @return	string		$foreignTable : the foreign table
 * @version 3.9.12
 * @since   3.9.12
 */
  private function relations_getForeignTable( $tables, $config, $configPath )
  {
    switch( $config['type'])
    {
      case( 'select' ):
        $foreignTable = $config['foreign_table'];
          // DRS
        if( $this->pObj->b_drs_sql )
        {
          $prompt = $configPath . 'foreign_table: \'' . $foreignTable . '\'';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
        break;
      case( 'group' ):
        $arrForeignTables = $this->pObj->objZz->getCSVasArray( $config['allowed'] );
        $foreignTable = $arrForeignTables[0];
          // DRS
        if( $this->pObj->b_drs_sql )
        {
          $csvForeignTables = implode( ', ', $arrForeignTables );
          $prompt = $configPath . '.allowed: \''.$csvForeignTables.'\'';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          if( count( $arrForeignTables ) > 1 )
          {
            $prompt = $configPath . '.allowed has more than one table.';
            t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
            $prompt = 'But the TYPO3-Browser can handle one table only.';
            t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
            $prompt = 'Please configure your SQL relation manually.';
            t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
          }
        }
          // DRS
        break;
    }

      // RESET $foreignTable, if it isn't element of used tables
    if( ! in_array( $foreignTable, array_keys( $tables ) ) )
    {
        // DRS
      if ( $this->pObj->b_drs_tca )
      {
        $prompt = $foreignTable . ' isn\'t element of used tables.';
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
        // DRS
      $foreignTable = null;
    }
      // RESET $foreignTable, if it isn't element of used tables

    return $foreignTable;
  }



/**
 * relations_requirements( ): Checks requirements for relation building.
 *                            Returns true if they met, false if not.
 *
 * @param	string		$table              : current table from used tables
 * @param	array		$config             : configuration of current TCA column
 * @param	string		$configPath         : configuration path of curren TCA columen
 * @return	boolean		true: requirements are met, false: req. aren't met
 * @version 3.9.12
 * @since   3.9.12
 */
  private function relations_requirements( $table, $config, $configPath )
  {
    static $first_call = true;
    static $arrDontUseTableFields;

      // RETURN : internal_type is db
    if( $config['internal_type'] == 'db')
    {
        // DRS
      if( $this->pObj->b_drs_tca )
      {
        $prompt = $configPath . 'internal_type is \'db\'. ' .
                  'But \'db\' isn\'t supported by the TYPO3-Browser.';
        t3lib_div::devlog( '[INFO/TCA] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return false;
    }
      // RETURN : internal_type is db

      // Get TypoScript configuration
    $csvAllowedTCAtypes = $this->pObj->conf['autoconfig.']['relations.']['TCAconfig.']['type.']['csvValue'];
    $arrAllowedTCAtypes = $this->pObj->objZz->getCSVasArray( $csvAllowedTCAtypes );
      // RETURN : type isn't any element of $arrAllowedTCAtypes
    if( ! in_array( $config['type'], $arrAllowedTCAtypes ) )
    {
        // DRS
      if( $this->pObj->b_drs_tca )
      {
        $prompt = $configPath . 'type is \'' .
                  $config['type']. '\', but any element in the list of ' .
                  'allowed types.';
        t3lib_div::devlog( '[INFO/TCA] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return false;
    }
      // RETURN : type isn't any element of $arrAllowedTCAtypes

      // IF : relations from the local table only
    if( $this->pObj->conf['autoconfig.']['relations.']['oneWayOnly'] )
    {
        // But table is a foreign table
      if( $table != $this->pObj->localTable )
      {
          // DRS
        if( $this->pObj->b_drs_tca )
        {
          $prompt = 'Relation building is allowed from local table to foreign table ' .
                    'only. But current table is a foreign table: ' . $table;
          t3lib_div::devlog( '[INFO/TCA] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
        return false;
      }
        // But table is a foreign table
    }
      // IF : relations from the local table only

      // Get table.field names, which shouldn't processed for relation building
    if( $first_call )
    {
      $arrDontUseTableFields = $this->relations_dontUseFields( );
      $first_call = false;
    }
      // Get table.field names, which shouldn't processed for relation building

      // CONTINUE : current column is element of $arrDontUseTableFields
    if ( is_array( $arrDontUseTableFields ) )
    {
      $tableField = $table. '.' . $columnsKey;
      if( in_array( $tableField, $arrDontUseTableFields ) )
      {
          // DRS
        if ( $this->pObj->b_drs_tca )
        {
          $prompt = $tableField . ' is element of dontUseTableFields.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
          // DRS
        continue;
      }
    }
      // CONTINUE : current column is element of $arrDontUseTableFields

    return true;
  }









  /***********************************************
   *
   * Join
   *
   **********************************************/



/**
 * Relation method: Building the relation part for the where clause
 *
 * @return	string		TRUE || FALSE or the SQL-where-clause
 */
  private function get_joins( )
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
      foreach((array) $tables as $localTable => $localFields)
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

        // Loop: tables
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
          if ($this->pObj->b_drs_localisation)
          {
            t3lib_div::devlog('[INFO/SQL] $GLOBALS[\'TCA\'][\''.$localTable.'\'] is loaded.', $this->pObj->extKey, 0);
          }
        }
          // Load the TCA, if we don't have an table.columns array

          // Loop: foreignTables
        foreach((array) $foreignTables as $mmTable => $foreignTable)
        {
            // foreignTable is an element in the array of the real tables
          if (in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
          {
              // #9697, 100912, dwildt
            $bool_opposite = false;
            if(!empty($this->arr_relations_opposite[$localTable][$mmTable]['MM_opposite_field']))
            {
              $bool_opposite = true;
            }
              // #9697, 100912, dwildt

              // left join: true
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
                // Opposite relation: true
              if($bool_opposite)
              {
                $str_left_join_uidforeign = ' LEFT JOIN '.$foreignTable.
                                            ' ON ( '.
                                                $mmTable.'.uid_local = '.$foreignTable.'.uid'.
                                                $str_enablefields_foreign.
                                                $str_pidStatement.
                                              ' )';
              }
                // Opposite relation: true
                // Opposite relation: false
              if(!$bool_opposite)
              {
                $str_left_join_uidforeign = ' LEFT JOIN '.$foreignTable.
                                            ' ON ( '.
                                                $mmTable.'.uid_foreign = '.$foreignTable.'.uid'.
                                                $str_enablefields_foreign.
                                                $str_pidStatement.
                                              ' )';
              }
                // Opposite relation: false
                // #9697, 100912, dwildt

                // Use current LEFT JOIN once only
              if (strpos($str_left_join, $str_left_join_uidforeign) === false)
              {
                $str_left_join = $str_left_join.$str_left_join_uidforeign;
              }
                // Use current LEFT JOIN once only
            }
              // left join: true

              // left join: false
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
              // left join: false

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
                // 13803, 110313, dwildt
              if(in_array('sorting_foreign', $keys_mmTable))
              {
                $this->pObj->arr_realTables_arrFields[$mmTable][] = 'sorting_foreign';
                  // Add every new table.field to the global array consolidate
                $this->pObj->arrConsolidate['addedTableFields'][] = $mmTable.'.sorting_foreign';
                $this->pObj->arrConsolidate['select']['mmSortingTableFields'][]  = $mmTable.'.sorting_foreign';
              }
                // 13803, 110313, dwildt
            }
              // Add the mm table to the fetched tables, if it is new

              // Add the foreign table to the fetched tables, if it is new
            if (!in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
            {
              $this->pObj->arr_realTables_arrFields[$foreignTable][] = 'uid';
            }
              // Add the foreign table to the fetched tables, if it is new
          }
            // foreignTable is an element in the array of the real tables
        }
          // Loop: foreignTables
      }
        // Loop: tables
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

        // LOOP tables
      foreach( (array) $tables as $keyTable => $arrFields)
      {
        foreach((array) $arrFields as $keyField => $valueField)
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
        // LOOP tables

        // LOOP tables
      foreach( ( array ) $tables as $localTableField => $foreignTable )
      {
        list ($localTable, $localField) = explode('.', $localTableField);
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

          // #32254, 111201, dwildt+
        $str_enablefields_foreign = $this->pObj->cObj->enableFields($foreignTable);
        $str_pidStatement         = $this->str_andWherePid($foreignTable);
        $str_pidStatement         = " AND " . $str_pidStatement . " " ;
          // #32254, 111201, dwildt+

          // #11843, fconstien, 110310
        $localTableFieldMaxItems = $GLOBALS['TCA'][$localTable]['columns'][$localField]['config']['maxitems'];
        switch(true)
        {
          case($localTableFieldMaxItems == 1):
            $str_query_part = "   " . $localTableField . " = " . $foreignTableField .
                              "   " . $str_enablefields_foreign .
                              "   " . $str_pidStatement ;
            break;
          case($localTableFieldMaxItems == 2):
            $str_query_part = "   ( " .
                              "     " . $localTableField . " = " . $foreignTableField . " OR " .
                              "     " . $localTableField . " LIKE CONCAT(" . $foreignTableField . ", ',%') OR " .
                              "     " . $localTableField . " LIKE CONCAT('%,', " . $foreignTableField .
                              "   )" .
                              "   " . $str_enablefields_foreign .
                              "   " . $str_pidStatement ;
            break;
          default:
            $str_query_part = "   ( " .
                              "     " . $localTableField . " = " . $foreignTableField . " OR " .
                              "     " . $localTableField . " LIKE CONCAT(" . $foreignTableField . ", ',%') OR " .
                              "     " . $localTableField . " LIKE CONCAT('%,', " . $foreignTableField . ", ',%') OR " .
                              "     " . $localTableField . " LIKE CONCAT('%,', " . $foreignTableField . ") " .
                              "   )" .
                              "   " . $str_enablefields_foreign .
                              "   " . $str_pidStatement ;
        }
          // #11843, fconstien, 110310

          // #11650, cweiske, 101223
        if ($this->b_left_join)
        {
            // #32254, 11120, dwildt-
          //$str_enablefields_foreign = $this->pObj->cObj->enableFields($foreignTable);
          //$str_pidStatement         = $this->str_andWherePid($foreignTable);
          //$str_pidStatement         = " AND " . $str_pidStatement . " " ;
            // #32254, 111201, dwildt-
          $str_left_join_uidforeign = " LEFT JOIN " . $foreignTable .
                                      " ON ( " .
                                      $str_query_part .
                                      " )" ;
          // Use current LEFT JOIN once only
          if (strpos($str_left_join, $str_left_join_uidforeign) === false)
          {
            $str_left_join .= $str_left_join_uidforeign;
          }
        }
        if (!$this->b_left_join)
        {
          // The AND clause below makes only sense, if it is a 1:1-relation!
          $str_full_join .=  " AND (" .
                             $str_query_part .
                             " )" ;
        }
        // Add the foreign table to the fetched tables, if it is new
        if (!in_array($foreignTable, array_keys($this->pObj->arr_realTables_arrFields)))
        {
          $this->pObj->arr_realTables_arrFields[$foreignTable][] = 'uid';
        }
      }
        // LOOP tables
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
   * Helper
   *
   **********************************************/



/**
 * init_class_boolAutorelation( ):  Checks the TypoScript configuration. Checks
 *                                  the local and global array autoconfig.relations.
 *                                  Sets the class var $boolAutorelation.
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_boolAutorelation( )
  {
    $conf_path  = $this->pObj->conf_path;
    $conf_view  = $this->pObj->conf_view;

    $coa_autoconfigRelations = $conf_view['autoconfig.']['relations.'];

      // Local TypoScript configuration
    if( $coa_autoconfigRelations )
    {
      $boolAutoconf = $conf_view['autoconfig.']['relations'];
      if ( ! $boolAutoconf )
      {
          // Autoconfiguration shouldn't be used
        if( $this->pObj->b_drs_sql )
        {
          $prompt = $conf_path . 'autoconfig.relations is false. '.
            'Autoconfigured relation building is disabled.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $this->boolAutorelation = false;
        return;
      }
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Autoconfigured relation building is enabled.';
        t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
      }
    }
      // Local TypoScript configuration



      // Global TypoScript configuration
      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $prompt = $conf_path .' hasn\'t any local autoconfig array. We try the global one.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $boolAutoconf = $this->pObj->conf['autoconfig.']['relations'];

      // IF : autoconfiguration shouldn't be used
    if ( ! $boolAutoconf )
    {
      if ($this->pObj->b_drs_sql)
      {
        $prompt = 'autoconfig.relations is false. ' .
                  'Autoconfigured relation building is disabled.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
      $this->boolAutorelation = false;
    }
      // IF : autoconfiguration shouldn't be used

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $prompt = 'Autoconfigured relation building is enbled.';
      t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

  }



/**
 * init_class_bLeftJoin( ): Initialises the class var $b_left_join
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_bLeftJoin( )
  {
    switch( true )
    {
      case( $this->pObj->conf['autoconfig.']['relations.']['left_join'] == 1 ):
      case( strtolower($this->pObj->conf['autoconfig.']['relations.']['left_join'] == 'true' ) ):
        $this->b_left_join = true;
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'LEFT JOIN is true.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      default:
        $this->b_left_join = false;
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'Use LEFT JOIN is false. FULL JOINS will used.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
    }
  }



/**
 * init_class_statementTables( ): Inits the class var statementTables.
 *    Var is an array like
 *    * $statementTables['select']['localtable']['tx_org_cal']        = 'tx_org_cal'
 *    * $statementTables['select']['foreigntable']['tx_org_caltype']  = 'tx_org_caltype'
 *
 * @param	string		$type         : select, from, where, orderBy, groupBy
 * @param	string		$csvStatement : current SQL statement
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_statementTables( $type, $csvStatement )
  {
      // Move csvStatement to an array
    $arrStatement = $this->pObj->objZz->getCSVasArray( $csvStatement );

    $prevTable = null;
    foreach( $arrStatement as $tableField)
    {
      list( $table ) = explode( '.', $tableField );

        // CONTINUE : table is handled before
      if( $table == $prevTable )
      {
        continue;
      }
        // CONTINUE : table is handled before

        // Set previous table to current table
      $prevTable = $table;

        // CONTINUE : table is local table
      if( $table == $this->pObj->localTable )
      {
        $this->statementTables['all']['localtable'][$table] = $table;
        $this->statementTables[$type]['localtable'][$table] = $table;
        continue;
      }
        // CONTINUE : table is local table

        // table is foreign table
      $this->statementTables['all']['foreigntable'][$table] = $table;
      $this->statementTables[$type]['foreigntable'][$table] = $table;
    }
  }



/**
 * init_class_statementTablesByFilter( ): Add filter tables to the class var
 *                                        $statementTables
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_class_statementTablesByFilter( )
  {
    $arrFilter  = array_keys( $this->conf_view['filter.'] );
    $csvFilter  = implode( ', ', $arrFilter );
    $this->init_class_statementTables( 'filter', $csvFilter );
  }



/**
 * zz_addUid( ):  Adds table.uid to the given statement, if table.uid isn't
 *                any element of the given statement.
 *                table is the fist table of the statement.
 *                Adds table.uid to the class var $addedTableFields.
 *
 * @param	string		$type         : select, from, where, orderBy, groupBy
 * @param	string		$csvStatement : current SQL statement
 * @return	string		$csvStatement : the statement with the table.uid
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_addUid( $type, $csvStatement )
  {
      // Get first table of the current statement
    list( $table ) = explode( '.', $csvStatement );

      // Short var
    $tableUid = $table . '.uid';

      // Get position of $tableUid
    $pos = strpos( $csvStatement, $tableUid );

      // RETURN : $tableUid is an element in the given statement
    if( ! ( $pos === false ) )
    {
      return $csvStatement;
    }
      // RETURN : $tableUid is an element in the given statement

      // Add table.uid to the end of the statement
    $csvStatement = $csvStatement . ', ' . $tableUid;

      // RETURN : table.uid is an element of the class var addedTableFields
    if( in_array( $tableUid, $this->addedTableFields[$type][$table] ) )
    {
      return $csvStatement;
    }
      // RETURN : table.uid is an element of the class var addedTableFields

      // Add table.uid to the class var addedTableFields
    $this->addedTableFields[$type][$table][] = $tableUid;

    return $csvStatement;
  }



/**
 * zz_dieIfOverride( ): Dies if an override for given type is defined
 *
 * @param	string		$type : select, from, where, orderBy, groupBy
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_dieIfOverride( $type )
  {
      // RETURN : any override.select isn't defined
    if( ! isset ( $this->conf_view['override.'][$type] ) )
    {
      return;
    }
      // RETURN : any override.select isn't defined

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $prompt = $this->conf_path . 'override.' . $type . ' is true. ' .
                $this->conf_path .'' . $type . ' will be ignored!';
      t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      $prompr = 'SELECT ' . $select;
      t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Index browser should handle override.' . $type . '.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

    $prompt = '<h1>
                  override.' . $type . '
                </h1>
                <p>
                  ' .$this->conf_path . 'override.' . $type . ' is used.<br />
                  But TYPO3-Browser 4.0 doesn\'t support this TypoScript property now.<br />
                  <br />
                  Sorry.
                </p>';
    die ( $prompt );
   }



/**
 * zz_loadTCAforAllTables( ): Load the TCA for all tables
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_loadTCAforAllTables( )
  {
    foreach( ( array ) $this->statementTables['select'] as $localForeign => $tables )
    {
      foreach( $tables as $table)
      {
        $this->pObj->objZz->loadTCA($table);
      }
    }
  }



/**
 * zz_setToRealTableNames( ): Returns the given SQL statement with table.fields
 *                            (real names) only.
 *
 * @param	string		$csvStatement: current SQL statement
 * @return	string		$csvStatement: SQL statement with table.fields only
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_setToRealTableNames( $csvStatement )
  {
      // Move csvStatement to an array
    $arrStatement = $this->pObj->objZz->getCSVasArray( $csvStatement );
      // Clean up: remove all expressions and aliases
    $arrStatement = $this->pObj->objSqlFun->expressionAndAliasToTable( $arrStatement );
      // Implode array to a csv string
    $csvStatement = implode( ', ', $arrStatement );

    return $csvStatement;
  }



/**
 * zz_woForeignTables( ): Removes foreign table.fields from the given
 *                        statement.
 *
 * @param	string		$type         : select, from, where, orderBy, groupBy
 * @param	string		$csvStatement : current SQL statement
 * @return	string		$csvStatement : the statement without foreign tables
 * @version 3.9.12
 * @since   3.9.12
 */
  private function zz_woForeignTables( $type, $csvStatement )
  {
      // Move csvStatement to an array
    $arrStatement = $this->pObj->objZz->getCSVasArray( $csvStatement );

    foreach( $arrStatement as $key => $tableField)
    {
      list( $table ) = explode( '.', $tableField );
      if( in_array( $table, $this->statementTables[$type]['foreigntable'] ) )
      {
        unset( $arrStatement[$key] );
      }
    }

    $csvStatement = implode( ', ', $arrStatement );
    return $csvStatement;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_auto.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_auto.php']);
}

?>
