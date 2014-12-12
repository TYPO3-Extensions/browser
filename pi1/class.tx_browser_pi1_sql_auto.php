<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_sql_auto bundles SQL methods for this case: The user has defined a SELECT
 * statement only. Browser should generate a full sql query automatically
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 *
 * @version     6.0.1
 * @since       3.9.12
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   98: class tx_browser_pi1_sql_auto
 *  137:     public function __construct($parentObj)
 *  157:     public function get_statements()
 *  268:     private function get_statements_select()
 *  304:     private function get_statements_from()
 *  448:     private function get_statements_orderBy()
 *  552:     private function get_statements_groupBy()
 *  570:     private function get_statements_where()
 *  758:     private function get_statements_whereLL()
 *  780:     private function whereSearch()
 * 1019:     private function andWhere()
 * 1088:     private function arr_andWherePid()
 * 1120:     private function arr_andWhereEnablefields()
 * 1151:     private function str_enableFields($realTable)
 * 1182:     private function init_class_relations()
 * 1284:     private function init_class_relationsLoop($tables)
 * 1356:     private function init_class_relationsMm($table, $columnsKey, $config, $foreignTable)
 * 1421:     private function init_class_relationsSingle($table, $columnsKey, $foreignTable, $config)
 * 1497:     private function relations_confDRSprompt()
 * 1571:     private function relations_dontUseFields()
 * 1624:     private function relations_getForeignTable($tables, $config, $configPath)
 * 1690:     private function relations_requirements($table, $columnsKey, $config, $configPath)
 * 1790:     private function get_joins()
 * 1873:     private function get_joinsAddTablesForeign($foreignTable)
 * 1893:     private function get_joinsAddTablesMm($mmTable)
 * 1934:     private function get_joinsSetMm()
 * 2039:     private function get_joinsSetMmFullJoin($localTable, $mmTable, $foreignTable, $fullJoin)
 * 2076:     private function get_joinsSetMmFullJoinPartForeign($localTable, $mmTable, $foreignTable, $fullJoin)
 * 2106:     private function get_joinsSetMmFullJoinPartMm($localTable, $mmTable, $foreignTable, $fullJoin)
 * 2137:     private function get_joinsSetMmLeftJoin($localTable, $mmTable, $foreignTable, $leftJoin)
 * 2173:     private function get_joinsSetMmLeftJoinIsDone($localTable, $mmTable, $foreignTable, $leftJoin)
 * 2210:     private function get_joinsSetMmLeftJoinPartForeign($localTable, $mmTable, $foreignTable, $leftJoin)
 * 2247:     private function get_joinsSetMmLeftJoinPartMm($localTable, $mmTable, $foreignTable, $leftJoin)
 * 2282:     private function get_joinsSetMmMatchFields($localTable, $foreignTable, $mmTable)
 * 2314:     private function get_joinsSetMmUidForeign($localTable, $mmTable)
 * 2342:     private function get_joinsSetMmUidMm($localTable, $mmTable)
 * 2369:     private function get_joinsSetCsv($leftJoin, $fullJoin)
 * 2523:     private function get_joinsSetCsvTablesOneDim()
 * 2563:     private function init_class_boolAutorelation()
 * 2632:     private function init_class_arrRelationsMmSimple()
 * 2666:     private function init_class_bLeftJoin()
 * 2702:     private function init_class_statementTables($type, $csvStatement)
 * 2746:     private function init_class_statementTablesByFilter()
 * 2765:     private function zz_addAliases($statement)
 * 2812:     private function zz_addUidsToSelect($csvSelect)
 * 2840:     private function zz_checkIfOneTabelIsUsedAtLeast()
 * 2877:     private function zz_dieIfOverride($type)
 * 2922:     private function zz_loadTCAforAllTables()
 * 2942:     private function zz_setToRealTableNames($csvStatement)
 * 2964:     private function zz_woForeignTables($type, $csvStatement)
 *
 * TOTAL FUNCTIONS: 49
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_auto
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
  // [Boolean] If it is TRUE, browser should try to build relations automatically
  var $boolAutorelation = true;
  // [Array] Array with the arrays MM and/or simple
  private $arr_relations_mm_simple = array();
  // [Array] Array with ...
  private $arr_relations_opposite;
  // [Boolean] TRUE if we should use LEFT JOIN. From TypoScript global or local autoconfig.relations.left_join
  var $b_left_join = true;
  // #59563, 140614, dwildt, 2-
  //// [Boolean] TRUE if the current relation is opposite
  //private $opposite = null;
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
  public function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
  }

  /*   * *********************************************
   *
   * Statements
   *
   * ******************************************** */

  /**
   * get_statements( ): It returns the statements for a SQL query:
   *                    SELECT, FROM, WHERE, ORDER BY, LIMIT
   *                    GROUP BY isn't handled
   *
   * @return	array		$arr_return : contains statements or an error message
   * @version 5.0.0
   * @since   3.9.12
   */
  public function get_statements()
  {
    static $arr_return = null;

    if ( $arr_return !== null )
    {
      return $arr_return;
    }

    // Add filter tables to class var $statementTables
    $this->init_class_statementTablesByFilter();

    // Get SELECT
    $arr_return[ 'data' ][ 'select' ] = $this->get_statements_select();
    if ( !$arr_return[ 'data' ][ 'select' ] )
    {
      $str_header = '<h1 style="color:red">' .
              $this->pObj->pi_getLL( 'error_sql_h1' ) .
              '</h1>';
      $str_prompt = '<p style="color:red; font-weight:bold;">' .
              'SELECT-' . $this->pObj->pi_getLL( 'error_value_empty' ) .
              '</p>';
      $arr_return[ 'error' ][ 'status' ] = true;
      $arr_return[ 'error' ][ 'header' ] = $str_header;
      $arr_return[ 'error' ][ 'prompt' ] = $str_prompt;
      return $arr_return;
    }
    // Get SELECT

    $this->zz_loadTCAforAllTables();
    // Load the TCA for all tables
    foreach ( $this->statementTables[ 'all' ] as $tables )
    {
      foreach ( $tables as $table )
      {
        $this->pObj->objZz->loadTCA( $table );
      }
    }
    // Load the TCA for all tables
//    // Set the global groupBy
//    $this->get_statements_groupBy();
    // Get ORDER BY
    $arr_return[ 'data' ][ 'orderBy' ] = $this->get_statements_orderBy();
    if ( !$arr_return[ 'data' ][ 'orderBy' ] )
    {
      $str_header = '<h1 style="color:red">' .
              $this->pObj->pi_getLL( 'error_sql_h1' ) .
              '</h1>';
      $str_prompt = '<p style="color:red; font-weight:bold;">' .
              'ORDER BY-' . $this->pObj->pi_getLL( 'error_value_empty' ) .
              '</p>';
      $arr_return[ 'error' ][ 'status' ] = true;
      $arr_return[ 'error' ][ 'header' ] = $str_header;
      $arr_return[ 'error' ][ 'prompt' ] = $str_prompt;
      return $arr_return;
    }
    // Get ORDER BY
    // Get Relations
    $this->init_class_boolAutorelation();
    $this->init_class_relations();
    // Get Relations
    // Get WHERE and FROM
    $arr_return[ 'data' ][ 'where' ] = $this->get_statements_where();
    $arr_return[ 'data' ][ 'whereLL' ] = $this->get_statements_whereLL();
    // From has to be the last, because whereClause can have new tables.
    $arr_return[ 'data' ][ 'from' ] = $this->get_statements_from();
    // Get WHERE and FROM
    // Enable the ordering by table_mm.sorting
    $str_mmSorting = false;
    $arr_mmSorting = false;
    if ( is_array( $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ] ) )
    {
      foreach ( ( array ) $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ] as $tableField )
      {
        list( $table ) = explode( '.', $tableField );
        $arr_mmSorting[] = $tableField . ' AS \'' . $tableField . '\'';
      }
    }
    if ( is_array( $arr_mmSorting ) )
    {
      $str_mmSorting = $str_mmSorting . implode( ', ', $arr_mmSorting );
      $str_mmSorting = ', ' . $str_mmSorting;
    }
    $arr_return[ 'data' ][ 'select' ] = $arr_return[ 'data' ][ 'select' ] . $str_mmSorting;
    // #59563, 140701, dwildt
    $arr_return[ 'data' ][ 'select' ] = $this->get_statements_selectAddMmSorting( $arr_return[ 'data' ][ 'select' ] );
    // Enable the ordering by table_mm.sorting
    // Replace Markers for pidlist and uid
    $str_pid_list = $this->pObj->pidList;
    // For human readable
    $str_pid_list = str_replace( ',', ', ', $str_pid_list );

    foreach ( ( array ) $arr_return[ 'data' ] as $str_query_part => $str_statement )
    {
      $str_statement = str_replace( '###PID_LIST###', $str_pid_list, $str_statement );
      $str_statement = str_replace( '###UID###', $this->pObj->piVars[ 'showUid' ], $str_statement );
      $arr_return[ 'data' ][ $str_query_part ] = $str_statement;
    }

    return $arr_return;
  }

  /*   * *********************************************
   *
   * Statements SELECT
   *
   * ******************************************** */

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
  private function get_statements_select()
  {
    // DIE in case of override.select
    $this->zz_dieIfOverride( 'select' );

    // Remove all expressions and aliases in the SELECT statement
    $csvSelect = $this->zz_setToRealTableNames( $this->conf_view[ 'select' ] );
    // Add table.uid
    $csvSelect = $this->zz_addUidsToSelect( $csvSelect );
    // Add aliases
    $csvSelect = $this->zz_addAliases( $csvSelect );

    // Devide in local table and foreign tables
    $this->init_class_statementTables( 'select', $csvSelect );

    // Remove foreign tables
    // 120329, Don't remove, because of missing table.uids!
    //$csvSelect = $this->zz_woForeignTables( 'select', $csvSelect );

    return $csvSelect;
  }

  /**
   * get_statements_selectAddMmSorting( ):
   *
   * @return	string
   * @version 5.0.0
   * @since   5.0.0
   */
  private function get_statements_selectAddMmSorting( $csvSelect )
  {
    if ( !is_array( $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ] ) )
    {
      return $csvSelect;
    }

    // Enable the ordering by table_mm.sorting
    $str_mmSorting = false;
    $arr_mmSorting = false;
    foreach ( ( array ) $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ] as $tableField )
    {
      $arr_mmSorting[] = $tableField . ' AS \'' . $tableField . '\'';
    }
    $str_mmSorting = $str_mmSorting . implode( ', ', $arr_mmSorting );
    $str_mmSorting = ', ' . $str_mmSorting;
    $csvSelect = $csvSelect . $str_mmSorting;

    return $csvSelect;
  }

  /*   * *********************************************
   *
   * Statements FROM
   *
   * ******************************************** */

  /**
   * get_statements_from( ): The method returns the FROM clause for the SQL query
   *
   * @return	string		SQL from
   * @version 3.9.12
   * @since   3.9.12
   */
  private function get_statements_from()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
var_dump( __METHOD__, __LINE__, $from );
    // DIE in case of override.from
    $this->zz_dieIfOverride( 'from' );

    // RETURN in case of override.from
    if ( $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ][ 'override.' ][ 'from' ] )
    {
      $from = $this->pObj->conf_sql[ 'from' ];
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] override.from is true. views.' . $viewWiDot . $mode . '.from will be ignored!', $this->pObj->extKey, 0 );
        t3lib_div::devLog( '[INFO/SQL] FROM ' . $from, $this->pObj->extKey, 0 );
      }
      return $from;
    } // RETURN in case of override.from

    $from = false;

    // Add the local table to FROM
    if ( $this->pObj->localTable )
    {
      $from = $this->pObj->localTable;
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] Value from the localTable: FROM \'' . $from . '\'', $this->pObj->extKey, 0 );
      }
    }
var_dump( __METHOD__, __LINE__, $from );

    if ( !$from )
    {
      // Add the first element of fetched tables to FROM
      if ( $this->b_left_join )
      {
        reset( $this->pObj->arr_realTables_arrFields );
        // Take the key of the first element. This is the name of the first table
        $from = key( $this->pObj->arr_realTables_arrFields );
        if ( $this->pObj->b_drs_sql )
        {
          t3lib_div::devLog( '[INFO/SQL] Value from the TypoScript select: FROM \'' . $from . '\'', $this->pObj->extKey, 0 );
        }
      }
      // Add the first element of fetched tables to FROM
    }
var_dump( __METHOD__, __LINE__, $from );

    // LEFT JOIN
    if ( $this->b_left_join )
    {
      $arr_result = $this->get_joins();
      $leftJoin = $arr_result[ 'data' ][ 'left_join' ];
      unset( $arr_result );
      $from = $from . $leftJoin;
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] autoconfig.relations.left_join is TRUE.<br />
          Fetched tables for relation building won\'t be used.<br />
          FROM \'' . $from . '\'', $this->pObj->extKey, 0 );
      }
    }
var_dump( __METHOD__, __LINE__, $from );
    // LEFT JOIN
    // FULL JOIN
    if ( !$this->b_left_join )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] autoconfig.relations.left_join is FALSE.', $this->pObj->extKey, 0 );
      }

      // Get fetched tables as array
      $arr_realTables = array_keys( $this->pObj->arr_realTables_arrFields );
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] Fetched tables are: ' . implode( ', ', $arr_realTables ), $this->pObj->extKey, 0 );
      }
      if ( is_array( $arr_realTables ) )
      {
        // Generates the from tables unique
        if ( $from )
        {
          $arr_realTables[] = $from;
          $arr_realTables = array_unique( $arr_realTables );
          $from = implode( ', ', $arr_realTables );
          if ( $this->pObj->b_drs_sql )
          {
            t3lib_div::devLog( '[INFO/SQL] FROM \'' . $from . '\'', $this->pObj->extKey, 0 );
          }
        }
        // Generates the from tables unique
        // Generates the from tables
        if ( !$from )
        {
          $from = implode( ', ', $arr_realTables );
          if ( $this->pObj->b_drs_sql )
          {
            t3lib_div::devLog( '[INFO/SQL] FROM \'' . $from . '\'', $this->pObj->extKey, 0 );
          }
        }
        // Generates the from tables
      }
      // Get fetched tables as array
    }
    // FULL JOIN
var_dump( __METHOD__, __LINE__, $from );

    if ( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[HELP/SQL] Change it? Use views.' . $viewWiDot . $mode . '.override.from', $this->pObj->extKey, 1 );
    }
    return $from;
  }

  /*   * *********************************************
   *
   * Statements ORDER BY, GROUP BY (disabled)
   *
   * ******************************************** */

  /**
   * get_statements_orderBy() : It returns the order part for SQL where clause.
   * If there are piVars, the order of the piVars will preferred.
   * Otherwise it returns the TypoScript configuration.
   * If there aren't piVars and there aren't a TypoSCript configuration, it will be empty.
   * If there are aliases, the aliases will be deleted.
   *
   * @return	string		$csvOrder: SQL ORDER BY clause.
   * @version 5.0.0
   * @since   3.9.12
   */
  private function get_statements_orderBy()
  {
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    // RETURN : ORDER BY is random( )
    if ( $this->get_statements_orderByRandomise() )
    {
      $csvOrder = 'rand( )';
      return $csvOrder;
    }

    // RETURN : ORDER BY is random( )
    if ( $this->get_statements_orderByOverride() )
    {
      $csvOrder = $this->conf_view[ 'override.' ][ 'orderBy' ];
      $csvOrder = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvOrder );
      return $csvOrder;
    }

    $csvOrder = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $this->conf_view[ 'orderBy' ] );

    // ORDER BY is empty. Take frist value from SELECT
    if ( empty( $csvOrder ) )
    {
      $csvOrder = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $this->conf_view[ 'select' ] );
      // Take the first element only
      list( $csvOrder ) = explode( ',', $csvOrder );
      list( $csvOrder ) = explode( ' ', $csvOrder );
    }

    // ORDER BY is empty. Take frist value from SELECT
    // RETURN : ORDER BY
    if ( !empty( $csvOrder ) )
    {
      // DRS
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = $this->conf_path . '.override.orderBy is: ' . $csvOrder;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'The system generated ORDER BY clause will be ignored!';
        t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'ORDER BY ' . $csvOrder;
        t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      // Set the orderBy by piVars
      $csvOrder = $this->pObj->objSqlFun->zz_prependPiVarSort( $csvOrder );
      return $csvOrder;
    }
    // RETURN : ORDER BY
    // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error
    // ERROR  : ORDER BY is undefined
    if ( $this->pObj->b_drs_error )
    {
      $prompt = 'views.' . $viewWiDot . $mode . ' hasn\'t any orderBy fields.';
      t3lib_div::devlog( '[ERROR/SQL] ', $this->pObj->extKey, 3 );
      $prompt = 'ABORTED';
      t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
    }
    return false;
    // ERROR  : ORDER BY is undefined
  }

  /**
   * get_statements_orderByOverride()  :
   *
   * @return	boolean
   * @version 5.0.0
   * @since   5.0.0
   */
  private function get_statements_orderByOverride()
  {
    // RETURN false : no override
    $override = $this->conf_view[ 'override.' ][ 'orderBy' ];
    if ( empty( $override ) )
    {
      return false;
    }

    // RETURN true : no DRS prompt needed
    if ( !$this->pObj->b_drs_sql )
    {
      return true;
    }

    // RETURN true : DRS prompt
    $prompt = $this->conf_path . '.override.orderBy is: ' . $override;
    t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'The system generated ORDER BY clause will be ignored!';
    t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * get_statements_orderByRandomise()  :
   *
   * @return	boolean
   * @version 5.0.0
   * @since   5.0.0
   */
  private function get_statements_orderByRandomise()
  {
    // RETURN false : no randomised order
    if ( !$this->conf_view[ 'random' ] == 1 )
    {
      return false;
    }

    // RETURN true : no DRS prompt needed
    if ( !$this->pObj->b_drs_sql )
    {
      return true;
    }

    // RETURN true : DRS prompt
    $prompt = 'Order of rows should randomise. If there is a ORDER BY configuration, it will ignored!';
    t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * get_statements_groupBy( )
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function get_statements_groupBy()
  {

  }

  /*   * *********************************************
   *
   * Statements WHERE
   *
   * ******************************************** */

  /**
   * Relation method: Building the whole where clause
   *
   * @return	string		FALSE or the SQL-where-clause
   * @version 6.0.1
   * @since   3.9.12
   */
  private function get_statements_where()
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';



// 3.3.7
    ////////////////////////////////////////////////////////////////////
    //
      // RETURN in case of override.where

    if ( $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ][ 'override.' ][ 'where' ] )
    {
      $where = $this->pObj->conf_sql[ 'where' ];
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'override.where is true. views.' . $viewWiDot . $mode . '.where will be ignored!';
        t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'all andWhere configuration will be ignored too!';
        t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'WHERE  ' . $where;
        t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $where;
    }
    // RETURN in case of override.where
// 3.3.7



    $whereClause = false;


    //////////////////////////////////////////////////////////////////////////
    //
      // Get enableFields like hiddden, deleted, starttime ... only for the localTable

    $str_enablefields = $this->str_enableFields( $this->pObj->localTable );

    // #62546, 141029, dwildt, 1+
    $whereClause = $this->pObj->objSqlFun->zz_concatenateWithAnd( $whereClause, $str_enablefields );

    // #62546, 141029, dwildt, 6-
//    // #11429, cweiske, 101219
//    //if (strpos($whereClause, $str_enablefields) === false)
//    if ( $str_enablefields !== '' && strpos( $whereClause, $str_enablefields ) === false )
//    {
//      $whereClause = $whereClause . " AND " . $str_enablefields;
//    }
    // Get enableFields like hiddden, deleted, starttime ... only for the localTable
//      ////////////////////////////////////////////////////////////////////
//      //
//      // Add localisation fields
//
//    $whereClause = $this->whereLL( $whereClause );
//      // Add localisation fields
// #41754.01, 1210101, dwildt, -
// $this->pObj->arr_andWhereFilter isn't never allocated
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Is there an andWhere statement from the filter class?
////$this->pObj->dev_var_dump( 'X' );
//    if ( is_array( $this->pObj->arr_andWhereFilter ) )
//    {
//      $str_andFilter  = implode( " AND ", $this->pObj->arr_andWhereFilter );
////$this->pObj->dev_var_dump( $str_andFilter );
//      $whereClause    = $whereClause." AND ".$str_andFilter;
//    }
//      // Is there an andWhere statement from the filter class?
// #41754.01, 1210101, dwildt, -
    //////////////////////////////////////////////////////////////////////////
    //
      // If we have a sword, allocates the global $arr_swordPhrasesTableField

    if ( $this->pObj->arr_swordPhrases && $this->pObj->csvSearch )
    {
      $arrSearchFields = explode( ',', $this->pObj->csvSearch );
      foreach ( $arrSearchFields as $arrSearchField )
      {
        list( $str_before_as ) = explode( ' AS ', $arrSearchField );
        list( $table, $field ) = explode( '.', $str_before_as );
        $tableField = trim( $table ) . '.' . trim( $field );
        foreach ( $this->pObj->arr_swordPhrases as $sword )
        {
          $this->pObj->arr_swordPhrasesTableField[ $tableField ][] = $sword;
        }
      }
    }
    // If we have a sword, allocates the global $arr_swordPhrasesTableField
    //////////////////////////////////////////////////////////////////////////
    //
      // Get SWORD, AND WHERE and JOINS

    if ( $view == 'list' )
    {
      $whereSword = $this->whereSearch();
    }
    $andWhere = $this->andWhere();
    $arr_result = $this->get_joins();
//$this->pObj->dev_var_dump( $arr_result );
    $fullJoin = $arr_result[ 'data' ][ 'full_join' ];
    unset( $arr_result );
    // Get SWORD, AND WHERE and JOINS
    //////////////////////////////////////////////////////////////////////////
    //
    // Add a FULL JOIN

    if ( $fullJoin != '' )
    {
      $whereClause .= ' ' . $fullJoin;
    }
    // Add a FULL JOIN
    //////////////////////////////////////////////////////////////////////////
    //
    // Add an AND WHERE from TypoScript

    // #62546, 141029, dwildt, 4-
//    if ( $andWhere != '' )
//    {
//      $whereClause .= ' AND ' . $andWhere;
//    }
    // #62546, 141029, dwildt, 1+
    $whereClause = $this->pObj->objSqlFun->zz_concatenateWithAnd( $whereClause, $andWhere );
    // Add an AND WHERE from TypoScript
    //////////////////////////////////////////////////////////////////////////
    //
    // Process depending on the view (LIST || SINGLE)

    switch ( $view )
    {
      case('single'):
        // Add the uid of the choosen record
        // #62546, 141029, dwildt, 2-
//        //$whereClause .= ' AND '.$this->pObj->arrLocalTable['uid'].' = '.$this->pObj->piVars['showUid'];
//        $whereClause .= $this->pObj->objLocalise->localisationSingle_where( $this->pObj->localTable );
        // #62546, 141029, dwildt, 2+
        $whereLL = $this->pObj->objLocalise->localisationSingle_where( $this->pObj->localTable );
        $whereClause = $this->pObj->objSqlFun->zz_concatenateWithAnd( $whereClause, $whereLL );
        break;
      case('list'):
        // Add the search clause, if there is a search (sword)
        // #62546, 141029, dwildt, 4-
//        if ( $whereSword != '' )
//        {
//          $whereClause .= ' ' . $whereSword;
//        }
        // #62546, 141029, dwildt, 1+
        $whereClause = $this->pObj->objSqlFun->zz_concatenateWithAnd( $whereClause, $whereSword );
        break;
    }
    // Process depending on the view (LIST || SINGLE)
    //////////////////////////////////////////////////////////////////////////
    //
    // Add pid IN list

    $str_pidStatement = $this->pObj->objSqlFun->get_andWherePid( $this->pObj->localTable );
    // Do we have a showUid not for the local table but for the foreign table? 3.3.3

    // #62546, 141029, dwildt, 4-
//    if ( strpos( $whereClause, $str_pidStatement ) === false )
//    {
//      $whereClause = $whereClause . $str_pidStatement;
//    }
    // #62546, 141029, dwildt, 1+
    $whereClause = $this->pObj->objSqlFun->zz_concatenateWithAnd( $whereClause, $str_pidStatement );
    // Add pid IN list
    //////////////////////////////////////////////////////////////////////////
    //
    // BUGFIX
    // #11430, cweise, 101219
    //if (strpos(Clause, " AND ") == 0)
    if ( substr( ltrim( $whereClause ), 0, 4 ) == 'AND ' )
    {
      //$whereClause = substr($whereClause, strlen(" AND"), strlen($whereClause));
      $whereClause = substr( ltrim( $whereClause ), 3 );
    }
    // BUGFIX
    //////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System
// 3.3.7
    // Human readable format
    #10111
    $hr_whereClause = str_replace( ',', ', ', $whereClause );
    if ( $this->pObj->b_drs_sql )
    {
      t3lib_div::devLog( '[INFO/SQL] WHERE ' . $hr_whereClause, $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[HELP/SQL] Change it? Use views.' . $viewWiDot . $mode . '.override.where', $this->pObj->extKey, 1 );
    }
    // DRS - Development Reporting System

    return $whereClause;
  }

  /**
   * get_statements_whereLL( ) : ...
   *
   * @return	string		FALSE or the SQL-where-clause
   * @version 3.9.24
   * @since   3.9.12
   */
  private function get_statements_whereLL()
  {
    $whereLL = $this->pObj->objLocalise->localisationFields_where( $this->pObj->localTable );
    if ( $whereLL )
    {
      $whereLL = " AND " . $whereLL;
    }

    return $whereLL;
  }

  /**
   * whereSearch( ): It returns the part for the where clause with a search, if there are search fields in the TS and a piVar sword.
   * The where clause will have this structure:
   *   (field_1 LIKE sword_1 or field_2 LIKE sword_1 or ...) AND (field_1 LIKE sword_2 or field_2 LIKE sword_2 or ...)
   * The SQL result will be true:
   * - If every sword will be once in one field at least
   *
   * @return	string		SQL query string
   * @version   5.0.14
   * @since     2.0.0
   */
  private function whereSearch()
  {
    $mode = $this->pObj->piVar_mode;

    // Query with OR and AND
    $str_whereOr = null;
    // Query with AND NOT LIKE
    $str_whereNot = false;
    $arr_whereNot = array();

    $arr_whereSword = array();
    $arr_whereSwordOr = array();


    //////////////////////////////////////////////////////////////////////////
    //
      // RETURN in case of no swords or no search fields

    if ( !( $this->pObj->arr_swordPhrases && $this->pObj->csvSearch ) )
    {
      return false;
    }
    // RETURN in case of no swords or no search fields
    //////////////////////////////////////////////////////////////////////////
    //
      // DRS - Development Reporting System

    if ( $this->pObj->b_drs_search )
    {
      $prompt = 'Search fields:<br />' . $this->pObj->csvSearch;
      t3lib_div::devlog( '[INFO/SEARCH] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Please configure: views.list.' . $mode . '.search';
      t3lib_div::devlog( '[HELP/SEARCH] ' . $prompt, $this->pObj->extKey, 1 );
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

    $arrSearchFields = explode( ',', $this->pObj->csvSearch );
    $int_sword = 0;

//$this->pObj->dev_var_dump( $this->pObj->arr_realTables_arrFields, $this->pObj->arrConsolidate['addedTableFields'] );
//var_dump(__METHOD__, __LINE__, $this->pObj->arr_swordPhrases );

    foreach ( $this->pObj->arr_swordPhrases[ 'or' ] as $arr_swords_and )
    {
//var_dump(__METHOD__, __LINE__, $arr_swords_and );
      // Suggestion #7730
      // The user has to add a wildcard
      if ( $this->pObj->bool_searchWildcardsManual )
      {
        foreach ( ( array ) $arr_swords_and as $key => $value )
        {
          // First char of search word isn't a wildcard
          $int_firstChar = 0;
          if ( $value[ $int_firstChar ] != $chr_wildcard )
          {
            $value = '[[:<:]]' . $value;
            $arr_swords_and[ $key ] = $value;
          }
          // First char of search word isn't a wildcard
          // First char of search word is a wildcard
          if ( $value[ $int_firstChar ] == $chr_wildcard )
          {
            $value = substr( $value, 1, strlen( $value ) - 1 );
            $arr_swords_and[ $key ] = $value;
          }
          // First char of search word is a wildcard
          // Last char of search word isn't a wildcard
          $int_lastChar = strlen( $value ) - 1;
          if ( $value[ $int_lastChar ] != $chr_wildcard )
          {
            $value = $value . '[[:>:]]';
            $arr_swords_and[ $key ] = $value;
          }
          // Last char of search word isn't a wildcard
          // Last char of search word is a wildcard
          if ( $value[ $int_lastChar ] == $chr_wildcard )
          {
            $value = substr( $value, 0, -1 );
            $arr_swords_and[ $key ] = $value;
          }
          // Last char of search word is a wildcard
        }
      }
      // The user has to add a wildcard
      // Suggestion #7730

      foreach ( $arrSearchFields as $arrSearchField )
      {
        list( $tableField, $str_behind_as ) = explode( ' AS ', $arrSearchField );
        list( $table, $field ) = explode( '.', $tableField );
        $table = trim( $table );
        $field = trim( $field );
        // Suggestion #7730
        // Wildcard are used by default
        if ( !$this->pObj->bool_searchWildcardsManual )
        {
          $str_wrap_sword = '%\' AND ' . $tableField . ' LIKE \'%';
          $str_whereTableField = implode( $str_wrap_sword, $arr_swords_and );
          $str_whereTableField = $table . '.' . $field . ' LIKE \'%' . $str_whereTableField . '%\'';
        }
        // Wildcard are used by default
        // The user has to add a wildcard
        if ( $this->pObj->bool_searchWildcardsManual )
        {
          $str_wrap_sword = '\') AND (' . $tableField . ' REGEXP \'';
          $str_whereTableField = implode( $str_wrap_sword, $arr_swords_and );
          $str_whereTableField = '(' . $tableField . ' REGEXP \'' . $str_whereTableField . '\')';
        }
        // The user has to add a wildcard
        // Suggestion #7730

        if ( count( $arr_swords_and ) > 1 )
        {
          $str_whereTableField = '(' . $str_whereTableField . ')';
        }
        $str_whereTableFieldOr = str_replace(' AND ', ' OR ', $str_whereTableField);
        $arr_whereSword[ $int_sword ][] = $str_whereTableField;
        $arr_whereSwordOr[ $int_sword ][] = $str_whereTableFieldOr;
      }
      $int_sword++;
    }

    foreach ( $arr_whereSword as $arr_fields )
    {
      $str_or = implode( ' OR ', $arr_fields );
      $arr_or[] = '( ' . $str_or . ' )';
    }
      // #i0073, 140720, dwildt, 2+
    foreach ( $arr_whereSwordOr as $arr_fields )
    {
      $str_and = implode( ' AND ', $arr_fields );
      $arr_and[] = '( ' . $str_and . ' )';
    }
    // #i0067, 140716, dwildt, 5+
    if ( is_array( $arr_or ) )
    {
      $str_whereOr = implode( ' OR ', $arr_or );
      $str_whereOr = ' ( ' . $str_whereOr . ' )';
    }

    // #i0067, 140720, dwildt, 5+
    if ( is_array( $arr_and ) )
    {
      $str_whereAnd = implode( ' AND ', $arr_and );
      $str_whereAnd = ' OR ( ' . $str_whereAnd . ' )';
    }

    // #i0067, 140720, dwildt, 1+
    $str_whereOr = ' AND ( ' . $str_whereOr . $str_whereAnd . ' )';

//var_dump(__METHOD__, __LINE__, $str_whereOr );
    // andWhere AND and OR
    //////////////////////////////////////////////////////////////////////////
    //
    // andWhere NOT

    if ( count( $this->pObj->arr_swordPhrases[ 'not' ] ) > 0 )
    {
      foreach ( $arrSearchFields as $arrSearchField )
      {
        list($tableField, $str_behind_as) = explode( ' AS ', $arrSearchField );
        list($table, $field) = explode( '.', $tableField );
        $table = trim( $table );
        $field = trim( $field );
        // Suggestion #7730
        // Wildcard are used by default
        if ( !$this->pObj->bool_searchWildcardsManual )
        {
          $str_wrap_sword = '%\' AND ' . $tableField . ' NOT LIKE \'%';
          $str_whereNot = implode( $str_wrap_sword, $this->pObj->arr_swordPhrases[ 'not' ] );
          $str_whereNot = $table . '.' . $field . ' NOT LIKE \'%' . $str_whereNot . '%\'';
        }
        // Wildcard are used by default
        // The user has to add a wildcard
        if ( $this->pObj->bool_searchWildcardsManual )
        {
          $str_wrap_sword = '\') AND (' . $tableField . ' NOT REGEXP \'';
          $str_whereNot = implode( $str_wrap_sword, $this->pObj->arr_swordPhrases[ 'not' ] );

          // First char of search word isn't a wildcard
          $int_firstChar = 0;
          if ( $str_whereNot[ $int_firstChar ] != $chr_wildcard )
          {
            $str_whereNot = '[[:<:]]' . $str_whereNot;
          }
          // First char of search word isn't a wildcard
          // First char of search word is a wildcard
          if ( $str_whereNot[ $int_firstChar ] == $chr_wildcard )
          {
            $str_whereNot = substr( $str_whereNot, 1, strlen( $str_whereNot ) - 1 );
          }
          // First char of search word is a wildcard
          // Last char of search word isn't a wildcard
          $int_lastChar = strlen( $str_whereNot ) - 1;
          if ( $str_whereNot[ $int_lastChar ] != $chr_wildcard )
          {
            $str_whereNot = $str_whereNot . '[[:>:]]';
          }
          // Last char of search word isn't a wildcard
          // Last char of search word is a wildcard
          if ( $str_whereNot[ $int_lastChar ] == $chr_wildcard )
          {
            $str_whereNot = substr( $str_whereNot, 0, -1 );
          }
          // Last char of search word is a wildcard
          $str_whereNot = '(' . $tableField . ' NOT REGEXP \'' . $str_whereNot . '\')';
        }
        // The user has to add a wildcard
        // Suggestion #7730

        $arr_whereNot[] = $str_whereNot;
      }
      if ( count( $arr_whereNot ) > 0 )
      {
        $str_whereNot = implode( ' AND ', $arr_whereNot );
        $str_whereNot = ' AND ' . $str_whereNot;
      }
    }
    //var_dump( __METHOD__, __LINE__, $str_whereOr, $str_whereNot );
    // andWhere NOT
//$tablesForRelations = array_unique( $tablesForRelations );
//$this->pObj->dev_var_dump( $tablesForRelations );
//$this->init_class_relationsLoop( $tablesForRelations );
    //////////////////////////////////////////////////////////////////////////
    //
    // RETURN andWhere
//var_dump(__METHOD__, __LINE__, $str_whereOr, $str_whereNot );
    $str_return = $str_whereOr . $str_whereNot;



    //////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ( $this->pObj->b_drs_search )
    {
      t3lib_div::devlog( '[INFO/SEARCH] andWhere clause:<br />' . $str_return, $this->pObj->extKey, 0 );
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

    $viewWiDot = $view . '.';

    ////////////////////////////////////////////////////////////////////
    //
      // RETURN in case of override.andWhere

    if ( $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ][ 'override.' ][ 'andWhere' ] )
    {
      $andWhere = $this->pObj->conf_sql[ 'andWhere' ];
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[INFO/SQL] override.andWhere is true. views.' . $viewWiDot . $mode . '.andWhere will be ignored!', $this->pObj->extKey, 0 );
        t3lib_div::devLog( '[INFO/SQL] WHERE ... AND ' . $andWhere, $this->pObj->extKey, 0 );
      }
      return $andWhere;
    }
    // RETURN in case of override.andWhere



    $lAndWhereClause = false;


    //////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // Take the andWhere clause from the global conf_sql['andWhere']

    if ( $this->pObj->conf_sql[ 'andWhere' ] )
    {
      $lAndWhereClause = $this->pObj->conf_sql[ 'andWhere' ];
    }
    // Take the andWhere clause from the global conf_sql['andWhere']
    //////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System
    if ( !$lAndWhereClause )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] views.' . $viewWiDot . $mode . ' hasn\'t any local or global andWhere clause. This is OK.', $this->pObj->extKey, 0 );
      }
    }
    if ( $lAndWhereClause )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] andWhere clause is: ' . $lAndWhereClause, $this->pObj->extKey, 0 );
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
    foreach ( $this->pObj->arr_realTables_arrFields as $realTable => $arrFields )
    {
      // Has the table a pid?
      if ( in_array( 'pid', $arrFields ) )
      {
        // Get the syntax table.field
        $tableField = $realTable . '.pid';

        // Replace real name of the table with its alias, if there is an alias
        $tableField = $this->pObj->objSqlFun_3x->set_tablealias( $tableField );
        $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before( $tableField );
        // Replace real name of the table with its alias, if there is an alias
        // Push the pid statement to the return array
        $arr_andWherePid[] = $tableField . " IN (" . $this->pObj->pidList . ")";
      }
      // Has the table a pid?
    }
    return $arr_andWherePid;
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
    foreach ( array_keys( ( array ) $this->pObj->arr_realTables_arrFields ) as $realTable )
//    foreach ($this->pObj->arr_realTables_arrFields as $realTable => $arrFields)
    {
      // Get the enablefields statement
      $str_enablefields = $this->pObj->cObj->enableFields( $realTable );
      // Cut of the first ' AND '
      $str_enablefields = substr( $str_enablefields, 5, strlen( $str_enablefields ) );

      // Replace real name of the table with its alias, if there is an alias
      $tableField = $realTable . '.dummy';
      $tableField = $this->pObj->objSqlFun_3x->set_tablealias( $tableField );
      $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before( $tableField );
      list( $aliasTable ) = explode( '.', $tableField );
      $str_enablefields = str_replace( $realTable . '.', $aliasTable . '.', $str_enablefields );
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
  private function str_enableFields( $realTable )
  {
    $str_enablefields = $this->pObj->cObj->enableFields( $realTable );
    // Cut of the first ' AND '
    $str_enablefields = substr( $str_enablefields, 5, strlen( $str_enablefields ) );

    // Replace real name of the table with its alias, if there is an alias
    $tableField = $realTable . '.dummy';
    $tableField = $this->pObj->objSqlFun_3x->set_tablealias( $tableField );
    $tableField = $this->pObj->objSqlFun_3x->get_sql_alias_before( $tableField );
    list( $aliasTable ) = explode( '.', $tableField );
    $str_enablefields = str_replace( $realTable . '.', $aliasTable . '.', $str_enablefields );
    // Replace real name of the table with its alias, if there is an alias

    return $str_enablefields;
  }

  /*   * *********************************************
   *
   * Relation building
   *
   * ******************************************** */

  /**
   * init_class_relations( ): Inits the class var $arr_relations_mm_simple,
   *                                an array with the arrays MM and/or simple
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_relations()
  {
    // RETURN : autoconfig is switched off
    if ( !$this->boolAutorelation )
    {
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'Nothing to do, autoconfig is switched off.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
    // RETURN : autoconfig is switched off
    // RETURN IF : no foreign table.
    if ( !isset( $this->statementTables[ 'all' ][ 'foreigntable' ] ) )
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
    $this->relations_confDRSprompt();

    // Initialises the class var $b_left_join
    $this->init_class_bLeftJoin();

    // Get all used tables
    $tables = $this->statementTables[ 'all' ][ 'localtable' ];
    $tables = $tables + $this->statementTables[ 'all' ][ 'foreigntable' ];

    // LOOP tables
    $this->init_class_relationsLoop( $tables );

    return;
  }

  /**
   * init_class_relationsLoop( ): Inits the class var $arr_relations_mm_simple,
   *                                an array with the arrays MM and/or simple
   *
   * @param	[type]		$tables: ...
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_relationsLoop( $tables )
  {
    if ( empty( $tables ) )
    {
      return;
    }

    foreach ( ( array ) $tables as $table )
    {
      // Get the TCA array of the current column
      $arrColumns = $GLOBALS[ 'TCA' ][ $table ][ 'columns' ];

      // CONTINUE : current table hasn't any TCA columns
      if ( !is_array( $arrColumns ) )
      {
        continue;
      }
      // CONTINUE : current table hasn't any TCA columns
      // LOOP each TCA column
      foreach ( ( array ) $arrColumns as $columnsKey => $columnsValue )
      {
        // Get the TCA configuration of the current column
        $config = $columnsValue[ 'config' ];
        // Get the TCA configuration path of the current column
        $configPath = $table . '.' . $columnsKey . '.config.';

        // CONTINUE : requirements aren't met
        if ( !$this->relations_requirements( $table, $columnsKey, $config, $configPath ) )
        {
          continue;
        }
        // CONTINUE : requirements aren't met
        // Get the foreign table
        $foreignTable = $this->relations_getForeignTable( $tables, $config, $configPath );
        // CONTINUE : there is no foreign table
        if ( empty( $foreignTable ) )
        {
          continue;
        }
        // CONTINUE : there is no foreign table
        // Get the foreign table
        // SWITCH mm or single
        switch ( $config[ 'MM' ] )
        {
          case( true ):
            $this->init_class_relationsMm( $table, $columnsKey, $config, $foreignTable );
            break;
          case( false ):
          default:
            $this->init_class_relationsSingle( $table, $columnsKey, $foreignTable, $config );
            break;
        }
        // SWITCH mm or single
      }
      // LOOP each TCA column
    }
    // LOOP tables
  }

  /**
   * init_class_relationsMm( ): Sets the class vars
   *          arr_relations_mm_simple['MM'][$table][$config['MM']]
   *          arr_relations_opposite[$table][$config['MM']]['MM_opposite_field']
   *
   * @param	string		$$table       : current table from used tables
   * @param	array		$config       : configuration of the current TCA column
   * @param	string		$foreignTable : current foreign table from TCA
   * @param	[type]		$foreignTable: ...
   * @return	void
   * @version 5.0.0
   * @since   3.9.12
   */
  private function init_class_relationsMm( $table, $columnsKey, $config, $foreignTable )
  {
    $relationTable = $config[ 'MM' ];
    // #59563, 140701, dwildt, 1-
    //if ( isset( $this->arr_relations_mm_simple[ 'MM' ][ $table ][ $relationTable ] ) )
    // #59563, 140701, dwildt, 1+
    if ( isset( $this->arr_relations_mm_simple[ 'MM' ][ $table ][ $relationTable ][ $foreignTable ] ) )
    {
      return;
    }

    $boolMMrelations = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'mmRelations' ];

    // RETURN IF : mmRelations should set manually
    if ( !$boolMMrelations )
    {
      // RETURN : no DRS prompt needed
      if ( !$this->pObj->b_drs_tca )
      {
        return;
      }

      // DRS prompt
      $prompt = 'Result (MM): ' . $table . ' - ' . $relationTable . ' - ' . $foreignTable;
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'But MM relations shouldn\'t processed automatically.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'If you want to process it automatically, please enable the MM relation building.';
      t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
      return;
    }
    // RETURN IF : mmRelations should set manually
    // #59563, 140701, dwildt, 1-
    //$this->arr_relations_mm_simple[ 'MM' ][ $table ][ $relationTable ] = $foreignTable;
    // #59563, 140701, dwildt, 1+
    $this->arr_relations_mm_simple[ 'MM' ][ $table ][ $relationTable ][ $foreignTable ] = true;

    if ( !empty( $config[ 'MM_opposite_field' ] ) )
    {
      // #59563, 140701, dwildt, 1-
      $this->arr_relations_opposite[ $table ][ $relationTable ][ 'MM_opposite_field' ] = $config[ 'MM_opposite_field' ];
      // #59563, 140701, dwildt, 1+
      $this->arr_relations_opposite[ $table ][ $relationTable ][ 'MM_opposite_field' ][ $foreignTable ] = $config[ 'MM_opposite_field' ];
    }
//    var_dump( __METHOD__, __LINE__, $foreignTable, $this->arr_relations_mm_simple, $this->arr_relations_opposite );
    // DRS
    $this->init_class_relationsMmDrs( $table, $columnsKey, $config, $foreignTable );
  }

  /**
   * init_class_relationsMmDrs( ):
   *
   * @param	string		$$table       : current table from used tables
   * @param	array		$config       : configuration of the current TCA column
   * @param	string		$foreignTable : current foreign table from TCA
   * @param	[type]		$foreignTable: ...
   * @return	void
   * @version 5.0.0
   * @since   3.9.12
   */
  private function init_class_relationsMmDrs( $table, $columnsKey, $config, $foreignTable )
  {
    // RETURN : no DRS prompt needed
    if ( !$this->pObj->b_drs_tca )
    {
      return;
    }

    // DRS prompt
    $prompt = 'Result (MM): ' . $table . ' - ' . $config[ 'MM' ] . ' - ' . $foreignTable;
    t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
    $prompt = 'Switch off the result above? Use TS config: ' .
            'autoconfig.relations.csvDontUseFields = ..., ' . $table . '.' . $columnsKey . ', ...';
    t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
    if ( $config[ 'foreign_table_where' ] )
    {
      $prompt = 'In TCA is foreign_table_where configured. This may be a risk, ' .
              'because the TYPO3 Browser won\'t process the clause: ' .
              $config[ 'foreign_table_where' ];
      t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
    }
  }

  /**
   * init_class_relationsSingle( ): Sets the class var $arr_relations_mm_simple['simple']
   *
   * @param	string		$$table       : current table from used tables
   * @param	string		$columnsKey   : current column name from TCA
   * @param	string		$foreignTable : current foreign table from TCA
   * @param	[type]		$config: ...
   * @return	void
   * @version 4.1.21
   * @since   3.9.12
   * @todo    120404, dwildt: Initialise $boolSelfReference
   */
  private function init_class_relationsSingle( $table, $columnsKey, $foreignTable, $config )
  {
    if ( isset( $this->arr_relations_mm_simple[ 'simple' ][ $table ][ $columnsKey ] ) )
    {
      return;
    }

    $boolSimpleRelations = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'simpleRelations' ];

    // RETURN IF : Don't process simple relations automatically
    if ( !$boolSimpleRelations )
    {
      // DRS
      if ( $this->pObj->b_drs_tca )
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
    // 121010, dwildt, 1-
    //if( $this->pObj->localTable == $foreignTable &&  ! $boolSelfReference )
    // 121010, dwildt, 1+
    if ( $this->pObj->localTable == $foreignTable )
    {
      // DRS
      if ( $this->pObj->b_drs_tca )
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

    $this->arr_relations_mm_simple[ 'simple' ][ $table ][ $columnsKey ] = $foreignTable;

    // DRS
    if ( $this->pObj->b_drs_tca )
    {
      $prompt = 'Result (simple): ' . $table . '.' . $columnsKey . ' - ' . $foreignTable;
      t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
      $prompt = 'Switch off the result above? Use TS config: ' .
              'autoconfig.relations.csvDontUseFields = ..., ' .
              $table . '.' . $columnsKey . ', ...';
      t3lib_div::devlog( '[HELP/SQL]' . $prompt, $this->pObj->extKey, 1 );
      if ( $config[ 'foreign_table_where' ] )
      {
        $prompt = 'In TCA is foreign_table_where configured. This may be a risk, ' .
                'because the TYPO3 Browser won\'t process the clause: ' .
                $config[ 'foreign_table_where' ];
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
  private function relations_confDRSprompt()
  {
    // RETURN : DRS is disabled
    if ( !$this->pObj->b_drs_sql && !$this->pObj->b_drs_tca )
    {
      return;
    }
    // RETURN : DRS is disabled
    // Get TypoScript configuration
    $boolOneWayOnly = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'oneWayOnly' ];
    $boolSimpleRelations = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'simpleRelations' ];
    $boolSelfReference = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'simpleRelations.' ][ 'selfReference' ];
    $boolMMrelations = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'mmRelations' ];
    $csvAllowedTCAtypes = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'TCAconfig.' ][ 'type.' ][ 'csvValue' ];
    //$dontUseFieldsCSV     = $this->pObj->conf['autoconfig.']['relations.']['csvDontUseFields'];
    // Get TypoScript configuration

    if ( $boolOneWayOnly )
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
    if ( $boolSimpleRelations )
    {
      $prompt = 'Use simple relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      if ( $boolSelfReference )
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
    t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
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
  private function relations_dontUseFields()
  {
    $arr_return = array();

    // Get TypoScript configuration
    $dontUseFieldsCSV = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'csvDontUseFields' ];

    if ( empty( $dontUseFieldsCSV ) )
    {
      return;
    }

    $arrDontUseTableFields = $this->pObj->objZz->getCSVasArray( $dontUseFieldsCSV );

    // LOOP $tableFields
    $arrDRSprompt = array();
    foreach ( ( array ) $arrDontUseTableFields as $tableField )
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
    if ( $this->pObj->b_drs_sql )
    {
      if ( is_array( $arrDRSprompt ) )
      {
        $csvDRSprompt = implode( ', ', $arrDRSprompt );
        $prompt = 'If this fields have a relation, don\'t use it: ' . $csvDRSprompt;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
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
    switch ( $config[ 'type' ] )
    {
      case( 'select' ):
        $foreignTable = $config[ 'foreign_table' ];
        // DRS
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $configPath . 'foreign_table: \'' . $foreignTable . '\'';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        // DRS
        break;
      case( 'group' ):
        $arrForeignTables = $this->pObj->objZz->getCSVasArray( $config[ 'allowed' ] );
        $foreignTable = $arrForeignTables[ 0 ];
        // DRS
        if ( $this->pObj->b_drs_sql )
        {
          $csvForeignTables = implode( ', ', $arrForeignTables );
          $prompt = $configPath . '.allowed: \'' . $csvForeignTables . '\'';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          if ( count( $arrForeignTables ) > 1 )
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
    if ( !in_array( $foreignTable, array_keys( $tables ) ) )
    {
      // DRS
      if ( $this->pObj->b_drs_tca )
      {
        $prompt = $foreignTable . ' isn\'t element of used tables.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
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
   * @param	string		$table      : current table from used tables
   * @param	array		$config     : configuration of current TCA column
   * @param	string		$configPath : configuration path of curren TCA columen
   * @param	[type]		$configPath: ...
   * @return	boolean		true        : requirements are met, false: req. aren't met
   * @version 3.9.12
   * @since   3.9.12
   */
  private function relations_requirements( $table, $columnsKey, $config, $configPath )
  {
    static $first_call = true;
    static $arrDontUseTableFields = array();

    // RETURN : internal_type is db
    if ( $config[ 'internal_type' ] == 'db' )
    {
      // DRS
      if ( $this->pObj->b_drs_tca )
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
    $csvAllowedTCAtypes = $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'TCAconfig.' ][ 'type.' ][ 'csvValue' ];
    $arrAllowedTCAtypes = $this->pObj->objZz->getCSVasArray( $csvAllowedTCAtypes );
    // RETURN : type isn't any element of $arrAllowedTCAtypes
    if ( !in_array( $config[ 'type' ], $arrAllowedTCAtypes ) )
    {
      // DRS
      if ( $this->pObj->b_drs_tca )
      {
        $prompt = $configPath . 'type is \'' .
                $config[ 'type' ] . '\', but any element in the list of ' .
                'allowed types.';
        t3lib_div::devlog( '[INFO/TCA] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      return false;
    }
    // RETURN : type isn't any element of $arrAllowedTCAtypes
    // IF : relations from the local table only
    if ( $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'oneWayOnly' ] )
    {
      // But table is a foreign table
      if ( $table != $this->pObj->localTable )
      {
        // DRS
        if ( $this->pObj->b_drs_tca )
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
    if ( $first_call )
    {
      $arrDontUseTableFields = $this->relations_dontUseFields();
      $first_call = false;
    }
    // Get table.field names, which shouldn't processed for relation building
    // CONTINUE : current column is element of $arrDontUseTableFields
    if ( is_array( $arrDontUseTableFields ) )
    {
      $tableField = $table . '.' . $columnsKey;
      if ( in_array( $tableField, $arrDontUseTableFields ) )
      {
        // DRS
        if ( $this->pObj->b_drs_tca )
        {
          $prompt = $tableField . ' is element of dontUseTableFields.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        // DRS
        // 140614, dwildt, 1-
        //continue;
        // 140614, dwildt, 1+
        return false;
      }
    }
    // CONTINUE : current column is element of $arrDontUseTableFields

    return true;
  }

  /*   * *********************************************
   *
   * Joins
   *
   * ******************************************** */

  /**
   * get_joins( ) : Relation method: Building the relation part for the where clause
   *
   * @return	string		TRUE || FALSE or the SQL-where-clause
   * @version   4.2.0
   * @since     2.0.0
   */
  private function get_joins()
  {
    $arr_return = array();
    $leftJoin = null;
    $fullJoin = null;


    // RETURN ERROR : any table isn't used
    $arr_return = $this->zz_checkIfOneTabelIsUsedAtLeast();
    if ( $arr_return[ 'error' ][ 'status' ] )
    {
      return $arr_return;
    }
    // RETURN ERROR : any table isn't used
    // Init the class var $arr_relations_mm_simple
    $this->init_class_arrRelationsMmSimple();

    // RETURN there isn't any table
    if ( empty( $this->arr_relations_mm_simple ) )
    {
      // We don't have any table. Return.
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] Nothing to do. There is no relation.', $this->pObj->extKey, 0 );
      }
      return $arr_return;
    }
    // RETURN there isn't any table
    // Set the MM relations
    $arr_return = $this->get_joinsSetMm();
    $leftJoin = $arr_return[ 'data' ][ 'left_join' ];
    $fullJoin = $arr_return[ 'data' ][ 'full_join' ];
    // Set the MM relations
    // Set the CSV relations
    // #43854, 121211, dwildt, 1-
    //$arr_return = $this->get_joinsSetCsv( );
    // #43854, 121211, dwildt, 1+
    $arr_return = $this->get_joinsSetCsv( $leftJoin, $fullJoin );

    // #43854, 121211, dwildt, 2-
//    $leftJoin   = $leftJoin . $arr_return['data']['left_join'];
//    $fullJoin   = $fullJoin . $arr_return['data']['full_join'];
    // #43854, 121211, dwildt, 2+
    $leftJoin = $arr_return[ 'data' ][ 'left_join' ];
    $fullJoin = $arr_return[ 'data' ][ 'full_join' ];
    // Set the CSV relations
    // RETURN : left_join or full_join
    // SWITCH : b_left_join
    switch ( $this->b_left_join )
    {
      case( true ) :
        $arr_return[ 'data' ][ 'left_join' ] = $leftJoin;
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $leftJoin;
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
//LEFT JOIN tx_org_mm_all ON ( tx_org_job.uid = tx_org_mm_all.uid_local ) LEFT JOIN tx_org_jobcat ON ( tx_org_mm_all.uid_foreign = tx_org_jobcat.uid AND tx_org_jobcat.deleted=0 AND tx_org_jobcat.hidden=0 AND tx_org_jobcat.pid IN (108,77,79,76,81,82,110,109,78,80,75) )
        }
        break;
      case( false ) :
      default :
        $arr_return[ 'data' ][ 'full_join' ] = $fullJoin;
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $fullJoin;
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
    }
    // SWITCH : b_left_join

    return $arr_return;
    // RETURN : left_join or full_join
  }

  /**
   * get_joinsAddTablesForeign( ) :
   *
   * @param	[type]		$$foreignTable: ...
   * @return	array
   * @version   3.9.13
   * @since     2.0.0
   */
  private function get_joinsAddTablesForeign( $foreignTable )
  {
    // RETURN : $foreignTable uid is added before
    if ( in_array( $foreignTable, array_keys( $this->pObj->arr_realTables_arrFields ) ) )
    {
      return;
    }
    // RETURN : $foreignTable uid is added before
    // Add the foreign table uid
    $this->pObj->arr_realTables_arrFields[ $foreignTable ][] = 'uid';
  }

  /**
   * get_joinsAddTablesMm( ) :
   *
   * @param	[type]		$$mmTable: ...
   * @return	array
   * @version   3.9.13
   * @since     2.0.0
   */
  private function get_joinsAddTablesMm( $mmTable )
  {

    // RETURN : mmTable is added before
    if ( in_array( $mmTable, array_keys( $this->pObj->arr_realTables_arrFields ) ) )
    {
      return;
    }
    // RETURN : mmTable is added before
    // Add uid_local and uid_foreign
    $this->pObj->arr_realTables_arrFields[ $mmTable ][] = 'uid_local';
    $this->pObj->arr_realTables_arrFields[ $mmTable ][] = 'uid_foreign';

    // Add sorting
    $keys_mmTable = array_keys( ( $GLOBALS[ 'TYPO3_DB' ]->admin_get_fields( $mmTable ) ) );
    if ( in_array( 'sorting', $keys_mmTable ) )
    {
      $this->pObj->arr_realTables_arrFields[ $mmTable ][] = 'sorting';
      // Add every table.field to the global array consolidate
      $this->pObj->arrConsolidate[ 'addedTableFields' ][] = $mmTable . '.sorting';
      $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ][] = $mmTable . '.sorting';
    }
    // Add sorting
    // Add sorting_foreign
    if ( in_array( 'sorting_foreign', $keys_mmTable ) )
    {
      $this->pObj->arr_realTables_arrFields[ $mmTable ][] = 'sorting_foreign';
      // Add every new table.field to the global array consolidate
      $this->pObj->arrConsolidate[ 'addedTableFields' ][] = $mmTable . '.sorting_foreign';
      $this->pObj->arrConsolidate[ 'select' ][ 'mmSortingTableFields' ][] = $mmTable . '.sorting_foreign';
    }
    // Add sorting_foreign
  }

  /**
   * get_joinsSetMm( ) : Relation method: Building the relation part for the where clause
   *
   * @return	string		TRUE || FALSE or the SQL-where-clause
   * @version   3.9.13
   * @since     2.0.0
   */
  private function get_joinsSetMm()
  {
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;
    $viewWiDot = $view . '.';

    $arr_return = array();
    $leftJoin = null;
    $fullJoin = null;

    // Get tables with MM relation
    $tables = $this->arr_relations_mm_simple[ 'MM' ];
    // RETURN : there isn't any table
    if ( empty( $tables ) )
    {
      return $arr_return;
    }
    // RETURN : there isn't any table
    // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = 'views.' . $viewWiDot . $mode . ' has configured MM relations.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
    // 140701, dwildt, ~: not needed
//    // Convert $tables
//    // Example: from ["tt_news."]["tt_news_cat_mm"] to ["tt_news"]["tt_news_cat_mm"] = "tt_news_cat"
//    foreach ( array_keys( ( array ) $tables ) as $localTable )
//    {
//      if ( substr( $localTable, -1 ) == '.' )
//      {
//        $tableWoDot = substr( $localTable, 0, strlen( $localTable ) - 1 );
//        $tables[ $tableWoDot ] = $tables[ $localTable ];
//        unset( $tables[ $localTable ] );
//      }
//    }
//    // Convert $tables from ["tt_news."]["tt_news_cat_mm"] to ["tt_news"]["tt_news_cat_mm"] = "tt_news_cat"
    // Loop: tables
//var_dump( __METHOD__, __LINE__, $tables );
//die(':(');
    foreach ( $tables as $localTable => $relationTables )
    {
      // Load the TCA
      $this->pObj->objZz->loadTCA( $localTable );
      // LOOP relationTables
      foreach ( ( array ) $relationTables as $relationTable => $foreignTables )
      {
        // Loop: foreignTables
        foreach ( array_keys( $foreignTables ) as $foreignTable )
        {
          // CONTINUE : foreignTable isn't any element in the array of the real tables
          if ( !in_array( $foreignTable, array_keys( $this->pObj->arr_realTables_arrFields ) ) )
          {
            continue;
          }
          // CONTINUE : foreignTable isn't any element in the array of the real tables
          // Load the TCA
          $this->pObj->objZz->loadTCA( $foreignTable );

          // #59563, 140613, dwildt, 12-
//        // SET flag opposite
//        switch (empty($this->arr_relations_opposite[$localTable][$relationTable]['MM_opposite_field']))
//        {
//          case( false ):
//            $this->opposite = true;
//            break;
//          default:
//          case( true ):
//            $this->opposite = false;
//            break;
//        }
//        // SET flag opposite
          // SWITCH : left join or full join
          switch ( $this->b_left_join )
          {
            case( true ) :
              $leftJoin = $this->get_joinsSetMmLeftJoin( $localTable, $relationTable, $foreignTable, $leftJoin );
              break;
            case ( false ) :
              $fullJoin = $this->get_joinsSetMmFullJoin( $localTable, $relationTable, $foreignTable, $fullJoin );
              break;
          }
          // SWITCH : left join or full join
          // Add tables and fields
          $relationTableAlias = $localTable . '_mm_' . $foreignTable;
          $this->get_joinsAddTablesMm( $relationTableAlias );
          $this->get_joinsAddTablesForeign( $foreignTable );
        } // Loop: foreignTables
      } // LOOP relationTables
    } // Loop: tables

    $arr_return[ 'data' ][ 'left_join' ] = $leftJoin;
    $arr_return[ 'data' ][ 'full_join' ] = $fullJoin;
//    var_dump( __METHOD__, __LINE__, $arr_return );
//    die( ':(' );
    return $arr_return;
  }

  /**
   * get_joinsSetMmFullJoin :
   *
   * @param	string		$localTable    : local table like tx_org_job
   * @param	string		$mmTable       : mm table like tx_org_mm_global
   * @param	string		$foreignTable  : local table like tx_org_jobcat
   * @param	string		$fullJoin      :
   * @return	array
   * @version   5.0.0
   * @since     2.0.0
   */
  private function get_joinsSetMmFullJoin( $localTable, $mmTable, $foreignTable, $fullJoin )
  {
    // Append the new FULL JOIN statement to the current FULL JOIN statement
    $andFullJoin = ''
            . $this->get_joinsSetMmFullJoinPartMm( $localTable, $mmTable, $foreignTable, $fullJoin )
            . $this->get_joinsSetMmFullJoinPartForeign( $localTable, $mmTable, $foreignTable, $fullJoin )
    ;

    // RETURN current FULL JOIN, if AND FULL JOIN is part of the current AND JOIN statement
    if ( empty( $andFullJoin ) )
    {
      return $fullJoin;
    }

    // DRS prompt
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = $localTable . ' got a FULL JOIN to ' . $foreignTable . '.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }

    return $fullJoin . $andFullJoin;
  }

  /**
   * get_joinsSetMmFullJoinPartForeign  : Get the foreign part of the FULL JOIN statement.
   *                                      Returns NULL, if this part is part of the current FULL JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$fullJoin     : current FULL JOIN statement
   * @return	string		$partForeign  : foreign part of the AND FULL JOIN  statement
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmFullJoinPartForeign( $localTable, $mmTable, $foreignTable, $fullJoin )
  {

    $mmTableUid = $this->get_joinsSetMmUidForeign( $localTable, $mmTable );

    $partForeign = ' AND ' . $mmTableUid . ' = ' . $foreignTable . '.uid';

    // RETRUN NULL  : part foreign is part of the current FULL JOIN statemant
    if ( strpos( $fullJoin, $partForeign ) === true )
    {
      return null;
    }

    // RETURN part foreign
    return $partForeign;
  }

  /**
   * get_joinsSetMmFullJoinPartMm : Get the LEFT JOIN relation (1st part) statement.
   *                                  Returns NULL, if relation is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$fullJoin     : current FULL JOIN statement
   * @return	string		$partMm       : MM part of the AND FULL JOIN statemant
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmFullJoinPartMm( $localTable, $mmTable, $foreignTable, $fullJoin )
  {
    $mmTableUid = $this->get_joinsSetMmUidMm( $localTable, $mmTable );
    $mmMatchFields = $this->get_joinsSetMmMatchFields( $localTable, $foreignTable, $mmTable );

    $partMm = ' AND ' . $localTable . '.uid = ' . $mmTableUid . '
              ' . $mmMatchFields . ' '
    ;

    // RETURN NULL, if MM part is part of the current FULL JOIN statement
    if ( strpos( $fullJoin, $partMm ) === true )
    {
      return null;
    }

    // RETURN MM part
    return $partMm;
  }

  /**
   * get_joinsSetMmLeftJoin : Append the new LEFT statement to the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : foreign table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$leftJoin     : enhanced LEFT JOIN statement
   * @internal  #59563
   * @version   5.0.0
   * @since     2.0.0
   */
  private function get_joinsSetMmLeftJoin( $localTable, $mmTable, $foreignTable, $leftJoin )
  {
    // RETURN current LEFT JOIN, if $mmTable is part of the current LEFT JOIN statement
    if ( $this->get_joinsSetMmLeftJoinIsDone( $localTable, $foreignTable, $leftJoin ) )
    {
      return $leftJoin;
    }

    // Append the new LEFT JOIN statement to the current LEFT JOIN statement
    $leftJoin = ''
            . $leftJoin
            . $this->get_joinsSetMmLeftJoinPartMm( $localTable, $mmTable, $foreignTable, $leftJoin )
            . $this->get_joinsSetMmLeftJoinPartForeign( $localTable, $foreignTable, $leftJoin )
    ;

    // DRS prompt
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = $localTable . ' got a LEFT JOIN to ' . $foreignTable . '.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }

    return $leftJoin;
  }

  /**
   * get_joinsSetMmLeftJoinIsDone : Checks, if mmTable is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable    : local table like tx_org_job
   * @param	string		$foreignTable  : local table like tx_org_jobcat
   * @param	string		$fullJoin      : current LEFT JOIN statement
   * @return	boolean		True, if mmTable is part of the current LEFT JOIN statement. False if not.
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinIsDone( $localTable, $foreignTable, $leftJoin )
  {
    $mmTableAlias = $localTable . '_mm_' . $foreignTable;
    $leftJoinMmTable = ' LEFT JOIN ' . $mmTableAlias;

    // RETURN FALSE : mmTable isn't part of the current LEFT JOIN statement
    if ( strpos( $leftJoin, $leftJoinMmTable ) === false )
    {
      return false;
    }

    // DRS prompt
    if ( $this->pObj->b_drs_error )
    {
      $prompt = 'There is more than one LEFT JOIN for ' . $localTable . ' and ' . $foreignTable . '. ' .
              'But the Browser  supports one LEFT JOIN relation per table only.';
      t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
      $prompt = 'Current relation will ignored. Maybe you will get an unexpeted result.';
      t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 2 );
    }

    // RETURN TRUE : mmTable is part of the current LEFT JOIN statement
    return true;
  }

  /**
   * get_joinsSetMmLeftJoinPartForeign  : Get the 2nd part of the LEFT JOIN statement.
   *                                      Returns NULL, if this part is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partForeign        : LEFT JOIN where
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartForeign( $localTable, $foreignTable, $leftJoin )
  {
    $tcaMmOppositeField = $GLOBALS[ 'TCA' ][ $localTable ][ 'columns' ][ $foreignTable ][ 'config' ][ 'MM_opposite_field' ];

    switch ( $tcaMmOppositeField )
    {
      case( true ):
        $partForeign = $this->get_joinsSetMmLeftJoinPartForeignWiOpposite( $localTable, $foreignTable, $leftJoin );
        break;
      case( false ):
      default:
        $partForeign = $this->get_joinsSetMmLeftJoinPartForeignWoOpposite( $localTable, $foreignTable, $leftJoin );
        break;
    }

    return $partForeign;
    $mmTableAlias = $localTable . '_mm_' . $foreignTable;
    $mmTableAliasUid = $this->get_joinsSetMmUidForeign( $localTable, $mmTableAlias );

    $partForeign = '
                LEFT JOIN ' . $foreignTable . '
                ON
                (
                  ' . $mmTableAliasUid . ' = ' . $foreignTable . '.uid
                  ' . $this->pObj->cObj->enableFields( $foreignTable ) . '
                  ' . $this->pObj->objSqlFun->get_andWherePid( $foreignTable ) . '
                )';

    // Add where once only
    if ( strpos( $leftJoin, $partForeign ) === true )
    {
      return null;
    }
    // Add where once only
    // RETURN relation
    return $partForeign;
  }

  /**
   * get_joinsSetMmLeftJoinPartForeignWiOpposite  : Get the 2nd part of the LEFT JOIN statement.
   *                                      Returns NULL, if this part is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partForeign        : LEFT JOIN where
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartForeignWiOpposite( $localTable, $foreignTable, $leftJoin )
  {
    $mmTableAlias = $foreignTable . '_mm_' . $localTable;
    $mmTableAliasUid = $this->get_joinsSetMmUidMm( $localTable, $mmTableAlias );

    $partForeign = '
                LEFT JOIN ' . $foreignTable . '
                ON
                (
                  ' . $mmTableAliasUid . ' = ' . $foreignTable . '.uid
                  ' . $this->pObj->cObj->enableFields( $foreignTable ) . '
                  ' . $this->pObj->objSqlFun->get_andWherePid( $foreignTable ) . '
                )';
    // Add where once only
    if ( strpos( $leftJoin, $partForeign ) === true )
    {
      return null;
    }
    // Add where once only
    // RETURN relation
    return $partForeign;
  }

  /**
   * get_joinsSetMmLeftJoinPartForeignWoOpposite  : Get the 2nd part of the LEFT JOIN statement.
   *                                      Returns NULL, if this part is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partForeign        : LEFT JOIN where
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartForeignWoOpposite( $localTable, $foreignTable, $leftJoin )
  {
    $mmTableAlias = $localTable . '_mm_' . $foreignTable;
    $mmTableAliasUid = $this->get_joinsSetMmUidForeign( $localTable, $mmTableAlias );

    $partForeign = '
                LEFT JOIN ' . $foreignTable . '
                ON
                (
                  ' . $mmTableAliasUid . ' = ' . $foreignTable . '.uid
                  ' . $this->pObj->cObj->enableFields( $foreignTable ) . '
                  ' . $this->pObj->objSqlFun->get_andWherePid( $foreignTable ) . '
                )';

    // Add where once only
    if ( strpos( $leftJoin, $partForeign ) === true )
    {
      return null;
    }
    // Add where once only
    // RETURN relation
    return $partForeign;
  }

  /**
   * get_joinsSetMmLeftJoinPartMm : Get the LEFT JOIN relation (1st part) statement.
   *                                  Returns NULL, if relation is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partMm     : LEFT JOIN relation
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartMm( $localTable, $mmTable, $foreignTable, $leftJoin )
  {
    $tcaMmOppositeField = $GLOBALS[ 'TCA' ][ $localTable ][ 'columns' ][ $foreignTable ][ 'config' ][ 'MM_opposite_field' ];

    switch ( $tcaMmOppositeField )
    {
      case( true ):
        $partMm = $this->get_joinsSetMmLeftJoinPartMmWiOpposite( $localTable, $mmTable, $foreignTable, $leftJoin );
        break;
      case( false ):
      default:
        $partMm = $this->get_joinsSetMmLeftJoinPartMmWoOpposite( $localTable, $mmTable, $foreignTable, $leftJoin );
        break;
    }

    return $partMm;
  }

  /**
   * get_joinsSetMmLeftJoinPartMmWiOpposite : Get the LEFT JOIN relation (1st part) statement.
   *                                  Returns NULL, if relation is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partMm     : LEFT JOIN relation
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartMmWiOpposite( $localTable, $mmTable, $foreignTable, $leftJoin )
  {
    $mmTableAlias = $foreignTable . '_mm_' . $localTable;
    $mmTableAliasUid = $this->get_joinsSetMmUidForeign( $localTable, $mmTableAlias );
    $mmMatchFields = $this->get_joinsSetMmMatchFields( $localTable, $foreignTable, $mmTableAlias );

    $partMm = '
                LEFT JOIN ' . $mmTable . ' AS ' . $mmTableAlias . '
                ON
                (
                  ' . $localTable . '.uid = ' . $mmTableAliasUid . '
                  ' . $mmMatchFields . '
                )';

    // RETURN NULL, if relation is part of the current LEFT JOIN statement
    if ( strpos( $leftJoin, $partMm ) === true )
    {
      return null;
    }

    // RETURN relation
    return $partMm;
  }

  /**
   * get_joinsSetMmLeftJoinPartMmWoOpposite : Get the LEFT JOIN relation (1st part) statement.
   *                                  Returns NULL, if relation is part of the current LEFT JOIN statement
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$leftJoin     : current LEFT JOIN statement
   * @return	string		$partMm     : LEFT JOIN relation
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmLeftJoinPartMmWoOpposite( $localTable, $mmTable, $foreignTable, $leftJoin )
  {
    $mmTableAlias = $localTable . '_mm_' . $foreignTable;
    $mmTableAliasUid = $this->get_joinsSetMmUidMm( $localTable, $mmTableAlias );
    $mmMatchFields = $this->get_joinsSetMmMatchFields( $localTable, $foreignTable, $mmTableAlias );

    $partMm = '
                LEFT JOIN ' . $mmTable . ' AS ' . $mmTableAlias . '
                ON
                (
                  ' . $localTable . '.uid = ' . $mmTableAliasUid . '
                  ' . $mmMatchFields . '
                )';

    // RETURN NULL, if relation is part of the current LEFT JOIN statement
    if ( strpos( $leftJoin, $partMm ) === true )
    {
      return null;
    }

    // RETURN relation
    return $partMm;
  }

  /**
   * get_joinsSetMmMatchFields  : Returns the mmTagbleUid.
   *                        mmTagbleUid is either uid_foreign or uid_local.
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$foreignTable : local table like tx_org_jobcat
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @return	string		$mmTableUid   : uid_foreign or uid_local
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmMatchFields( $localTable, $foreignTable, $mmTable )
  {
    $tcaMmMatchFields = $GLOBALS[ 'TCA' ][ $localTable ][ 'columns' ][ $foreignTable ][ 'config' ][ 'MM_match_fields' ];
    $mmMatchFields = null;

    if ( empty( $tcaMmMatchFields ) )
    {
      return null;
    }

    foreach ( ( array ) $tcaMmMatchFields as $field => $table )
    {
      $mmMatchFields = ""
              . $mmMatchFields
              . "AND " . $mmTable . "." . $field . " = '" . $table . "' "
      ;
    }

    return $mmMatchFields;
  }

  /**
   * get_joinsSetMmUidForeign : Returns the mmTagbleUid.
   *                            mmTagbleUid is either uid_foreign or uid_local.
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @return	string		$mmTableUid   : uid_foreign or uid_local
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmUidForeign( $localTable, $mmTable )
  {
    $opposite = empty( $this->arr_relations_opposite[ $localTable ][ $mmTable ][ 'MM_opposite_field' ] );
    switch ( $opposite )
    {
      case( false ) :
        $mmTableUid = $mmTable . '.uid_local';
        break;
      case( true ) :
      default :
        $mmTableUid = $mmTable . '.uid_foreign';
        break;
    }

    return $mmTableUid;
  }

  /**
   * get_joinsSetMmUidMm  : Returns the mmTagbleUid.
   *                        mmTagbleUid is either uid_foreign or uid_local.
   *
   * @param	string		$localTable   : local table like tx_org_job
   * @param	string		$mmTable      : mm table like tx_org_mm_global
   * @return	string		$mmTableUid   : uid_foreign or uid_local
   * @internal  #59563
   * @version   5.0.0
   * @since     5.0.0
   */
  private function get_joinsSetMmUidMm( $localTable, $mmTable )
  {
    $opposite = empty( $this->arr_relations_opposite[ $localTable ][ $mmTable ][ 'MM_opposite_field' ] );
    switch ( $opposite )
    {
      case( false ) :
        $mmTableUid = $mmTable . '.uid_foreign';
        break;
      case( true ) :
      default :
        $mmTableUid = $mmTable . '.uid_local';
        break;
    }

    return $mmTableUid;
  }

  /**
   * get_joinsSetCsv( ) : Relation method: Building the relation part for the where clause
   *
   * @return    string        TRUE || FALSE or the SQL-where-clause
   * @version   4.3.1
   * @since     2.0.0
   */
  // #43854, 121211, dwildt, 1-
  //private function get_joinsSetCsv( )
  // #43854, 121211, dwildt, 1+
  private function get_joinsSetCsv( $leftJoin, $fullJoin )
  {
    $arr_return = array();
    // #43854, 121211, dwildt, 2-
    //$leftJoin   = false;
    //$fullJoin   = false;
    // #43854, 121211, dwildt, 3+
    $leftJoinForeignTable = null;
    $arr_return[ 'data' ][ 'left_join' ] = $leftJoin;
    $arr_return[ 'data' ][ 'full_join' ] = $fullJoin;

    // Get the tables with a CSV relation
    $tables = $this->arr_relations_mm_simple[ 'simple' ];

    // RETURN : there isn't any table
    if ( empty( $tables ) )
    {
      return $arr_return;
    }
    // RETURN : there isn't any table
    // Get tableFields as an one dimensional array like ["tt_news.cruser_id"]
    $tables = $this->get_joinsSetCsvTablesOneDim();

    // LOOP tables
    foreach ( ( array ) $tables as $localTableField => $foreignTable )
    {
      // Get tableField of the local table
      list( $localTable, $localField ) = explode( '.', $localTableField );

      // Get tableField of the foreign table
      if ( strpos( $foreignTable, '.' ) !== false )
      {
        list( $foreignTable, $foreignTableField ) = explode( '.', $foreignTable );
      }
      else
      {
        $foreignTableField = $foreignTable . '.uid';
      }
      // Get tableField of the foreign table
      // Get enabled fields for the foreign table
      $foreignTableEnableFields = $this->pObj->cObj->enableFields( $foreignTable );
      // Get the pid list for the foreign table
      $foreignTablePidList = $this->pObj->objSqlFun->get_andWherePid( $foreignTable );

      // Get max relations
      $maxRelations = 0;
      $maxRelations = $GLOBALS[ 'TCA' ][ $localTable ][ 'columns' ][ $localField ][ 'config' ][ 'maxitems' ];

      // SWITCH : max relations
      switch ( true )
      {
        case( $maxRelations == 1 ):
          $str_query_part = "   " . $localTableField . " = " . $foreignTableField .
                  "   " . $foreignTableEnableFields .
                  "   " . $foreignTablePidList;
          break;
        default:
          // #44125, 121218, dwildt, 3-
//          $str_query_part = "   FIND_IN_SET ( " . $foreignTableField . ", " . $localTableField . " )" .
//                            "   " . $foreignTableEnableFields .
//                            "   " . $foreignTablePidList ;
          // #44125, 121218, dwildt, 3+
          $str_query_part = "   FIND_IN_SET( " . $foreignTableField . ", " . $localTableField . " )" .
                  "   " . $foreignTableEnableFields .
                  "   " . $foreignTablePidList;
      }
      // SWITCH : max relations
      // SWITCH : left join or full join
      switch ( $this->b_left_join )
      {
        case( true ) :
          // #43854, 121211, dwildt, 1+
          $andLeftJoin = " LEFT JOIN " . $foreignTable .
                  " ON ( " .
                  $str_query_part .
                  " )";
          // #43854, 121211, dwildt
          // RETURN : ERROR mmTable is part of left join already
          $leftJoinForeignTable = ' LEFT JOIN ' . $foreignTable;
          if ( !( strpos( $leftJoin, $leftJoinForeignTable ) === false ) )
          {
            if ( $this->pObj->b_drs_error )
            {
              $prompt = 'There is more than one LEFT JOIN for ' . $localTableField . ' and ' . $foreignTable . '. ' .
                      'But the Browser supports one LEFT JOIN relation per table only.';
              t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
              $prompt = 'Current relation will ignored. Maybe you will get an unexpeted result.';
              t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 2 );
            }
            break;
          }
          // RETURN : ERROR mmTable is part of left join already
          // #43854, 121211, dwildt
          // Add current LEFT JOIN once only
          if ( strpos( $leftJoin, $andLeftJoin ) === false )
          {
            $leftJoin = $leftJoin . $andLeftJoin;
          }
          break;
        case( false ) :
        default :
          // The AND clause below makes only sense, if it is a 1:1-relation!
          $andFullJoin = " AND (" .
                  $str_query_part .
                  " )";
          // Add current full join once only
          if ( strpos( $fullJoin, $andFullJoin ) === false )
          {
            $fullJoin = $fullJoin . $andFullJoin;
          }
          break;
      }
      // SWITCH : left join or full join
      // Add tables and fields
      $this->get_joinsAddTablesForeign( $foreignTable );
    }
    // LOOP tables
    // DRS
    if ( $this->pObj->b_drs_warn )
    {
      if ( $leftJoin || $fullJoin )
      {
        $prompt = 'DANGEROUS: csv relation(s)! See next line(s). Csv relations
                  are slow as cold glue. If you have a problem with performance, please
                  move the database design from csv relations to relations with MM tables.';
        t3lib_div::devlog( '[WARN/PERFORMANCE+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
      if ( $fullJoin )
      {
        $prompt = $fullJoin;
        t3lib_div::devlog( '[INFO/PERFORMANCE+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
      if ( $leftJoin )
      {
        $prompt = $leftJoin;
        t3lib_div::devlog( '[INFO/PERFORMANCE+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
    }
    // DRS

    $arr_return[ 'data' ][ 'left_join' ] = $leftJoin;
    $arr_return[ 'data' ][ 'full_join' ] = $fullJoin;
    return $arr_return;
  }

  /**
   * get_joinsSetCsvTablesOneDim( ) : Convert array tables to a one dimensional array.
   *                                  Example:
   *                                    ["tt_news"]["cruser_id"] -> ["tt_news.cruser_id"]
   *
   * @return	array		$tables : one dimensional array
   * @version   3.9.13
   * @since     2.0.0
   */
  private function get_joinsSetCsvTablesOneDim()
  {
    // get tables with CSV relation
    $tables = $this->arr_relations_mm_simple[ 'simple' ];

    // LOOP tables
    foreach ( ( array ) $tables as $keyTable => $arrFields )
    {
      foreach ( ( array ) $arrFields as $keyField => $valueField )
      {
        $str_dot = false;
        if ( substr( $keyTable, -1 ) != '.' )
        {
          $str_dot = '.';
        }
        $tables[ $keyTable . $str_dot . $keyField ] = $valueField;
        unset( $tables[ $keyTable ][ $keyField ] );
      }
      unset( $tables[ $keyTable ] );
    }
    // LOOP tables

    return $tables;
  }

  /*   * *********************************************
   *
   * Helper
   *
   * ******************************************** */

  /**
   * init_class_boolAutorelation( ):  Checks the TypoScript configuration. Checks
   *                                  the local and global array autoconfig.relations.
   *                                  Sets the class var $boolAutorelation.
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_boolAutorelation()
  {
    $conf_path = $this->pObj->get_confPath();
    $conf_view = $this->pObj->get_confView();

    $coa_autoconfigRelations = $conf_view[ 'autoconfig.' ][ 'relations.' ];

    // Local TypoScript configuration
    if ( $coa_autoconfigRelations )
    {
      $boolAutoconf = $conf_view[ 'autoconfig.' ][ 'relations' ];
      if ( !$boolAutoconf )
      {
        // Autoconfiguration shouldn't be used
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $conf_path . 'autoconfig.relations is false. ' .
                  'Autoconfigured relation building is disabled.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $this->boolAutorelation = false;
        return;
      }
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'Autoconfigured relation building is enabled.';
        t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
      }
    }
    // Local TypoScript configuration
    // Global TypoScript configuration
    // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = $conf_path . ' hasn\'t any local autoconfig array. We try the global one.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    $boolAutoconf = $this->pObj->conf[ 'autoconfig.' ][ 'relations' ];

    // IF : autoconfiguration shouldn't be used
    if ( !$boolAutoconf )
    {
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'autoconfig.relations is false. ' .
                'Autoconfigured relation building is disabled.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $this->boolAutorelation = false;
    }
    // IF : autoconfiguration shouldn't be used
    // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = 'Autoconfigured relation building is enbled.';
      t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
    // DRS
  }

  /**
   * init_class_arrRelationsMmSimple( ) :
   *
   * @return	void
   * @version   3.9.13
   * @since     2.0.0
   */
  private function init_class_arrRelationsMmSimple()
  {

    // relations.simple is configured
    if ( is_array( $this->conf_view[ 'relations.' ][ 'simple.' ] ) )
    {
      $this->arr_relations_mm_simple[ 'simple' ] = $this->conf_view[ 'relations.' ][ 'simple.' ];
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'relations.simple is configured.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    // relations.simple is configured
    // relations.mm is configured
    if ( is_array( $this->conf_view[ 'relations.' ][ 'mm.' ] ) )
    {
      $this->arr_relations_mm_simple[ 'MM' ] = $this->conf_view[ 'relations.' ][ 'mm.' ];
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = 'relations.mm is configured.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    // relations.mm is configured
  }

  /**
   * init_class_bLeftJoin( ): Initialises the class var $b_left_join
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_bLeftJoin()
  {
    switch ( true )
    {
      case( $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'left_join' ] == 1 ):
      case( strtolower( $this->pObj->conf[ 'autoconfig.' ][ 'relations.' ][ 'left_join' ] == 'true' ) ):
        $this->b_left_join = true;
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = 'LEFT JOIN is true.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      default:
        $this->b_left_join = false;
        if ( $this->pObj->b_drs_sql )
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

    // LOOP each tableField
    foreach ( $arrStatement as $tableField )
    {
      list( $table ) = explode( '.', $tableField );

      // CONTINUE : table is handled before
      if ( $table == $prevTable )
      {
        continue;
      }
      // CONTINUE : table is handled before
      // Set previous table to current table
      $prevTable = $table;

      // CONTINUE : table is local table
      if ( $table == $this->pObj->localTable )
      {
        $this->statementTables[ 'all' ][ 'localtable' ][ $table ] = $table;
        $this->statementTables[ $type ][ 'localtable' ][ $table ] = $table;
        continue;
      }
      // CONTINUE : table is local table
      // table is foreign table
      $this->statementTables[ 'all' ][ 'foreigntable' ][ $table ] = $table;
      $this->statementTables[ $type ][ 'foreigntable' ][ $table ] = $table;
    }
    // LOOP each tableField
  }

  /**
   * init_class_statementTablesByFilter( ): Add filter tables to the class var
   *                                        $statementTables
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_statementTablesByFilter()
  {
    $arrFilter = array_keys( ( array ) $this->conf_view[ 'filter.' ] );
    $csvFilter = implode( ', ', ( array ) $arrFilter );
    $this->init_class_statementTables( 'filter', $csvFilter );
  }

  /**
   * zz_addUidsToSelect( ):  Adds table.uid to the given statement, if table.uid isn't
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
  private function zz_addAliases( $statement )
  {
    static $drsPrompt = true;

    // DRS
    if ( $this->pObj->b_drs_devTodo && $drsPrompt )
    {
      $prompt = 'Aliases should added depending on deal_as_table.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
      $drsPrompt = false;
    }
    // DRS

    $arr_tableFields = $this->pObj->objZz->getCSVasArray( $statement );

    // LOOP all tableFields from statement
    foreach ( $arr_tableFields as $tableField )
    {
      if ( empty( $tableField ) )
      {
        continue;
      }
      if ( strpos( $tableField, ' AS ' ) !== false )
      {
        $arr_tableFieldWiAlias[] = $tableField;
        continue;
      }

      $alias = $tableField;
      $arr_tableFieldWiAlias[] = $tableField . ' AS \'' . $alias . '\'';
    }
    // LOOP all tableFields from statement

    $statement = implode( ', ', ( array ) $arr_tableFieldWiAlias );
    return $statement;
  }

  /**
   * zz_addUidsToSelect( ): Adds table.uids to the given statement.
   *                        Values are taken from the global var
   *                        $arrConsolidate['addedTableFields'].
   *
   * @param	string		$csvSelect : current SQL statement
   * @return	string		$csvSelect : the statement with the table.uid
   * @version 4.2.0
   * @since   3.9.12
   */
  private function zz_addUidsToSelect( $csvSelect )
  {

    if ( !is_array( $this->pObj->arrConsolidate[ 'addedTableFields' ] ) )
    {
      return $csvSelect;
    }

    foreach ( ( array ) $this->pObj->arrConsolidate[ 'addedTableFields' ] as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      // 121211, dwildt, 1+
      unset( $table );
      if ( $field == 'uid' )
      {
        $csvSelect = $csvSelect . ", " . $tableField . " AS '" . $tableField . "'";
      }
    }
    return $csvSelect;
  }

  /**
   * zz_checkIfOneTabelIsUsedAtLeast( ) : Returns an error, if any table isn't used
   *
   * @return	array		$arr_return : Contains an error prompt in case of an error
   * @version   3.1.13
   * @since     2.0.0
   */
  private function zz_checkIfOneTabelIsUsedAtLeast()
  {
    $arr_return = array();

    // RETURN : OK : a table is used at least
    if ( !empty( $this->pObj->arr_realTables_arrFields ) )
    {
      return;
    }
    // RETURN : OK : a table is used at least
    // RETURN ERROR : any table isn't used
    if ( $this->pObj->b_drs_error )
    {
      $prompt = 'There isn\'t any table used.';
      t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
      $prompt = 'There has to be the local table at least!';
      t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
      $prompt = 'ABORT (No relation building!)';
      t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
    }
    $str_header = '<h1 style="color:red">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
    $str_prompt = '<p style="color:red; font-weight:bold;">' . $this->pObj->pi_getLL( 'error_table_no' ) . '</p>';
    $arr_return[ 'error' ][ 'status' ] = true;
    $arr_return[ 'error' ][ 'header' ] = $str_header;
    $arr_return[ 'error' ][ 'prompt' ] = $str_prompt;
    return $arr_return;
    // RETURN ERROR : any table isn't used
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
    if ( !isset( $this->conf_view[ 'override.' ][ $type ] ) )
    {
      return;
    }
    // RETURN : any override.select isn't defined
    // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = $this->conf_path . 'override.' . $type . ' is true. ' .
              $this->conf_path . '' . $type . ' will be ignored!';
      t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = $type . ': ' . $this->conf_view[ 'override.' ][ $type ];
      t3lib_div::devLog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
    // DRS
    if ( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Index browser should handle override.' . $type . '.';
      t3lib_div::devlog( '[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS

    $header = 'FATAL ERROR!';
    $text = $this->conf_path . 'override.' . $type . ' is used.<br />
            But TYPO3-Browser 4.0 doesn\'t support this TypoScript property now.';
    $this->pObj->drs_die( $header, $text );
  }

  /**
   * zz_loadTCAforAllTables( ): Load the TCA for all tables
   *
   * @return	void
   * @version 3.9.12
   * @since   3.9.12
   */
  private function zz_loadTCAforAllTables()
  {
    foreach ( ( array ) $this->statementTables[ 'all' ] as $tables )
    {
      foreach ( $tables as $table )
      {
        $this->pObj->objZz->loadTCA( $table );
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

    foreach ( $arrStatement as $key => $tableField )
    {
      list( $table ) = explode( '.', $tableField );
      if ( in_array( $table, $this->statementTables[ $type ][ 'foreigntable' ] ) )
      {
        unset( $arrStatement[ $key ] );
      }
    }

    $csvStatement = implode( ', ', $arrStatement );
    return $csvStatement;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_sql_auto.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_sql_auto.php' ]);
}
?>