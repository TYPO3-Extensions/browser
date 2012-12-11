<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2012 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
* The class tx_browser_pi1_typoscript bundles typoscript methods for the extension browser
*
* @author       Dirk Wildt http://wildt.at.die-netzmacher.de
* @package      TYPO3
* @subpackage   browser
* @version      4.2.0
* since         2.0.0
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_typoscript
 *  111:     function __construct($parentObj)
 *
 *              SECTION: TypoScript Management
 *  151:     function oneDim_to_tree($conf_oneDim)
 *
 *              SECTION: Get used tables from the TypoScript
 *  225:     function fetch_realTables_arrFields()
 *  364:     function fetch_localTable()
 *
 *              SECTION: Helper Functions
 *  497:     function set_confSql()
 *  788:     function set_confSql_groupBy()
 *  866:     function set_confSqlDevider()
 *  922:     function fetch_realTableWiField($str_queryPart)
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_typoscript
{

  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = false;
  // [Array] The current TypoScript configuration array
  var $mode       = false;
  // [Integer] The current mode (from modeselector)
  var $view       = false;
  // [String] 'list' or 'single': The current view
  var $conf_view  = false;
  // [Array] The TypoScript configuration array of the current view
  var $conf_path  = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  // Variables set by the pObj (by class.tx_browser_pi1.php)


  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $conf_sql;
  // Array with the SQL query parts from the TypoScript
  var $arr_realTables_arrFields;
  // Array with tables and fields in this syntax: array[table][] = field

  var $str_sqlDeviderDisplay  = false;
  // [String] Devider for children records. This devider should be displayed.
  var $str_sqlDeviderWorkflow = false;
  // [String] Devider for children records. This devider is for the workflow of stdWrap.
  // Variables set by this class












/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;

  }










  /***********************************************
  *
  * TypoScript Management
  *
  **********************************************/






  /**
 * oneDim_to_tree():  Build a multidimensional TypoScript configuration array (tree)
 *                    out of a one dimensional array.
 *                    Example:
 *                    - $conf_oneDim['views.single.1.select'] = tt_news.title
 *                      will become
 *                    - $conf['views.']['single.']['1.']['select'] = tt_news.title
 *
 * @param	array		$conf_oneDim  : TypoScript configuration array (one dimension)
 * @return	array		$conf         : TypoScript configuration array
 * @since     3.4.3
 * @version   3.4.3
 */
  function oneDim_to_tree($conf_oneDim)
  {
    $conf = array();

    // Values for preg_replace and preg_split
    $str_delimiter    = '|';
    $str_split        = '/' . preg_quote($str_delimiter, '/') . '/';
    $str_dot          = '/\./';
    $str_dot_replace  = '.|';
    // Values for preg_replace and preg_split

    // Loop: Each TypoScript configuration path
    foreach ($conf_oneDim as $key_oneDim => $value_oneDim)
    {
      // Get all items from the current TypoScript path
      // views.single.1.select -> views.|single.|1.|select
      $key_oneDim   = preg_replace($str_dot, $str_dot_replace, $key_oneDim);
      // array( 'views.', 'single.', '1.', 'select')
      $ts_keys      = preg_split($str_split, $key_oneDim, -1, PREG_SPLIT_NO_EMPTY);
      // 'select'
      $last_ts_key  = array_pop($ts_keys);
      // Get all items from the current TypoScript path

      // Build parent structure
      // Might be slow for really deep and large structures
      $parentArr = &$conf;
      // Loop: Each element of the current configuration path
      foreach ($ts_keys as $ts_key)
      {
        if(!isset($parentArr[$ts_key]))
        {
          $parentArr[$ts_key] = array();
        }
        elseif (!is_array($parentArr[$ts_key]))
        {
          $parentArr[$ts_key] = array();
        }
        $parentArr = &$parentArr[$ts_key];
      }
      // Loop: Each element of the current configuration path
      // Build parent structure

      // Add the final part to the structure
      if(empty($parentArr[$last_ts_key]))
      {
        $parentArr[$last_ts_key] = $value_oneDim;
      }
      // Add the final part to the structure

    }
    // Loop: Each TypoScript configuration path

    return $conf;
  }







  /***********************************************
  *
  * Get used tables from the TypoScript
  *
  **********************************************/


/**
 * fetch_realTables_arrFields( ): Returns an array with used tables and fields
 *                                out of the TypoScript SQL query parts.
 *                                The tables will have real names
 *
 * @return	array		Array with the syntax array[table][] = field
 *
 * @version 3.9.9.
 * @since   2.0.0
 */
  function fetch_realTables_arrFields( )
  {
    static $promptDRSEngine4 = true;


      
      //////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog('[INFO/SQL] We try to fetch used tables.', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System


      //////////////////////////////////////////////////////
      //
      // Get the typoscript configuration for the SQL query

    $lConfSql = $this->set_confSql( );
      // Set the typoscript configuration for the SQL query



      /////////////////////////////////////////////////////
      //
      // Fetch used tables from the SELECT statement

      // Is there a SQL function, which should replaced with an alias?
      // Replace each SQL function which its alias
    foreach( ( array ) $this->conf_view['select.']['deal_as_table.'] as $arr_dealastable )
    {
      $str_statement  = $arr_dealastable['statement'];
      $str_aliasTable = $arr_dealastable['alias'];
        // 121211, dwildt, 1-
      //$arr_dealAlias[$str_aliasTable] = $str_statement;
      // I.e.: $conf_sql['select'] = CONCAT(tx_bzdstaffdirectory_persons.title, ' ', tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name), tx_bzdstaffdirectory_groups.group_name
      $lConfSql['select'] = str_replace( $str_statement, $str_aliasTable, $lConfSql['select'] );
      // I.e.: $conf_sql['select'] = tx_bzdstaffdirectory_persons.last_name, tx_bzdstaffdirectory_groups.group_name
    }
    
      // Set the global csvSelectWoFunc with table.fields only and without any function
    $csvSelectWoFunc = $lConfSql['select'];
    $arrSelectWoFunc = explode(',', $csvSelectWoFunc);
    $arrSelectWoFunc = $this->pObj->objSqlFun_3x->clean_up_as_and_alias($arrSelectWoFunc);
    $csvSelectWoFunc = implode(', ', $arrSelectWoFunc);
    $this->pObj->csvSelectWoFunc = $csvSelectWoFunc;
      // Set the global csvSelectWoFunc with table.fields only and without any function

    $this->fetch_realTableWiField( $lConfSql['select'], 'select' );
      // Fetch used tables from the SELECT statement

    

      /////////////////////////////////////////////////////
      //
      // Fetch used tables from the SEARCH, ORDER BY and AND WHERE statement

      // Fetch used tables from the search fields, if there is a sword
    if( $this->pObj->piVar_sword )
    {
      $this->fetch_realTableWiField( $lConfSql['search'], 'search' );
    }

      // Try to fetch used tables from the ORDER BY statement
    $csvOrderBy = $lConfSql['orderBy'];
      // Bugfix #6468, #6518,  010220, dwildt
    $csvOrderBy = str_ireplace( ' desc', '', $csvOrderBy );
    $csvOrderBy = str_ireplace( ' asc',  '', $csvOrderBy );
    $this->fetch_realTableWiField( $csvOrderBy, 'orderBy' );

      
      // Try to fetch used tables from the AND WHERE statement
    if( $lConfSql['andWhere'] )
    {
      $arr_result        = $this->pObj->objSqlFun_3x->get_propper_andWhere( $lConfSql['andWhere'] );
      $strCsvTableFields = implode( ',', $arr_result['data']['arr_used_tableFields'] );
      unset( $arr_result );
      $this->fetch_realTableWiField( $strCsvTableFields, 'andWhere' );
    }
      // Fetch used tables from the SEARCH, ORDER BY and AND WHERE statement


      /////////////////////////////////////////////////////
      //
      // Get table fields out of the filter, if filter is set

    $arr_tableField = false;
    if( is_array( $this->conf_view['filter.'] ) )
    {
        // 121211, dwildt, 1-
      //$arr_prompt = array( );
      foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $arrFields )
      {
        // Get piVar name
        $tableField           = $tableWiDot.key( $arrFields );
        list( $table, $field )  = explode( '.', $tableField );
          // 121211, dwildt, 1+
        unset( $table );
        $str_nice_piVar       = $arrFields[$field.'.']['nice_piVar'];
        if( ! $str_nice_piVar )
        {
          $str_nice_piVar = $tableField;
        }
          // Do we have a piVar
        if( $this->pObj->piVars[$str_nice_piVar] )
//        if( $this->pObj->piVars[$str_nice_piVar] || 1 )
        {
          $arr_tableField[]  = $tableField;
        }
          // DEVELOPMENT: Browser engine 4.x
          // DRS
        if( ( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_todo ) && $promptDRSEngine4 )
        {
          $prompt = 'Other workflow in 4.x and 3.x!';
          t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
          $promptDRSEngine4 = false;
        }
          // DRS
        if( $this->pObj->dev_browserEngine >= 4 )
        {
            // IF no pivar (filter isn't set)
          if( ! $this->pObj->piVars[$str_nice_piVar] )
          {
              // IF current filter isn't set in $arr_tableField
            if( ! isset( $arr_tableField[$tableField] ) )
            {
                // Add the current filter (tableField), but filter isn't used
              $arr_tableField[]  = $tableField;
            }
              // IF current filter isn't set in $arr_tableField
          }
            // IF no pivar (filter isn't set)
        }
            // DEVELOPMENT: Browser engine 4.x
      }
    }
    if( is_array( $arr_tableField ) )
    {
      $arrCsvFilter = implode( ',', $arr_tableField );
      $this->fetch_realTableWiField( $arrCsvFilter, 'filter' );
    }
      // Get table fields out of the filter, if filter is set



      /////////////////////////////////////////////////////
      //
      // Set the class var conf_sql

    $this->conf_sql = $lConfSql;
      // Set the class var conf_sql

    return $this->arr_realTables_arrFields;
  }











 /**
  * Rteurns the values for the array with the local table. The local table is the main table.
  *
  * @return	array		$arr_localTable: Array with the syntax: array[uid] = table.field, array[pid] = table.field
  */
  function fetch_localTable()
  {

    /////////////////////////////////////////////////////
    //
    // RETURN, if $this->pObj->arrLocalTable is initiated

    if(is_array($this->pObj->arrLocalTable))
    {
      return $this->pObj->arrLocalTable;
    }


    /////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] Look for the local table.', $this->pObj->extKey, 0);
    }


    /////////////////////////////////////////////////////
    //
    // Get the local table from TS, if it is configured

    $str_localTable = $this->conf_view['localTable'];
    if (!$str_localTable)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$this->conf_path.'localTable isn\'t configured. Probably it is OK.', $this->pObj->extKey, 0);
      }
      $str_localTable = $this->pObj->conf['localTable'];
    }
    if ($str_localTable)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$this->conf_path.'localTable is: \''.$str_localTable.'\'.', $this->pObj->extKey, 0);
      }
    }


    /////////////////////////////////////////////////////
    //
    // If there isn't a table in the TS, take the first of the select query

    if (!$str_localTable)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] localTable (global TypoScript value) isn\'t configured. Probably it is OK.', $this->pObj->extKey, 0);
      }
      reset($this->arr_realTables_arrFields);
      $str_localTable = key($this->arr_realTables_arrFields);
      if ($str_localTable)
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] We take the first table from the SELECT statement.<br />
            localTable (maintable) is: '.$str_localTable, $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/SQL] If you like another table, please configure '.$this->conf_path.'localTable', $this->pObj->extKey, 1);
        }
      }
    }


    /////////////////////////////////////////////////////
    //
    // Do we have a special uid and pid in the local view?

    if (is_array($this->conf_view['localTable.']))
    {
      $arr_localTable = $this->conf_view['localTable.'];
    }
    else
    {
      $arr_localTable = $this->conf['localTable.'];
    }

    /////////////////////////////////////////////////////
    //
    // We need a syntax: table.field

    $arr_localTable['uid'] = $str_localTable.'.'.$arr_localTable['uid'];
    $arr_localTable['pid'] = $str_localTable.'.'.$arr_localTable['pid'];
    $arr_localTable = $this->pObj->objSqlFun_3x->replace_tablealias($arr_localTable);

    return $arr_localTable;

  }


























  /***********************************************
  *
  * Helper Functions
  *
  **********************************************/


 /**
  * set_confSql( ): Sets the class var conf_sql with the SQL query statements from the TypoScript.
  *                 If there is a 'deal_as_table', SQL function will replaced.
  *                 All tables become an alias, functions too.
  *
  * @return	array		$conf_sql:
  *
  * @version  3.9.19
  * @since    3.0.0
  */
  function set_confSql( )
  {
      // 121211, dwildt, 1+
    $conf_sql = array( );

      // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = 'Try to process aliases in SQL query parts.';
      t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS



      //////////////////////////////////////////////////////
      //
      // LOOP select, ..., andWhere

    $arr_query_parts = array( 'select', 'from', 'search', 'orderBy', 'groupBy', 'where', 'andWhere' );
    foreach ($arr_query_parts as $str_query_part)
    {
      $coa_name = $this->conf_view['override.'][$str_query_part];
      $coa_conf = $this->conf_view['override.'][$str_query_part . '.'];

        // IF override
      if( $coa_name )
      {
          // DRS
        if( $this->pObj->b_drs_sql )
        {
          $prompt = $str_query_part . ' has an override.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
          // DRS
        $conf_sql[$str_query_part]  = $this->pObj->objSqlFun_3x->global_stdWrap
                                      (
                                        'override.' . $str_query_part,
                                        $coa_name,
                                        $coa_conf
                                      );
      }
        // IF override

        // IF no override
      if( ! $coa_name )
      {
          // DRS
        if ($this->pObj->b_drs_sql)
        {
          $prompt = $str_query_part .' hasn\'t any override.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'If you want to override, please configure \'override.' . $str_query_part . '\'.';
          t3lib_div::devlog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
        }
          // DRS
        $coa_name = $this->conf_view[$str_query_part];
        $coa_conf = $this->conf_view[$str_query_part.'.'];
        // 3.3.7
        $conf_sql[$str_query_part]  = $this->pObj->objSqlFun_3x->global_stdWrap
                                      (
                                        $str_query_part,
                                        $coa_name,
                                        $coa_conf
                                      );
      }
        // IF no override

    }


      //////////////////////////////////////////////////////
      //
      // group by, order by, where, and where
      
      // Set group by
    $conf_sql['groupBy'] = $this->set_confSql_groupBy( );

      // Set default order by
    if( empty ( $conf_sql['orderBy'] ) )
    {
      $conf_sql['orderBy'] = $this->conf_view['select'];
    }
      // Set default order by

      // Concatenate group by and order by
    if( empty ( $conf_sql['groupBy'] ) )
    {
      $conf_sql['orderBy'] = $conf_sql['groupBy'] . ', ' . $conf_sql['orderBy'];
    }
      // Concatenate group by and order by

      // Set where
    if( empty ( $conf_sql['where']) )
    {
      $conf_sql['where'] = $this->conf['where'];
    }
      // Set where

      // Set and where
    if( empty ( $conf_sql['andWhere']) )
    {
      $conf_sql['andWhere'] = $this->conf['andWhere'];
    }
      // Set and where
      // group by, order by, where, and where

//$this->pObj->dev_var_dump( $conf_sql );


      //////////////////////////////////////////////////////
      //
      // and where data query

      // plugin [template] int_templating_dataQuery has a value
    if( $this->pObj->objFlexform->int_templating_dataQuery )
    {
        // Get andWhere from TypoScript
      $str_key   = $this->pObj->objFlexform->int_templating_dataQuery . '.';
      $arr_items = $this->conf['flexform.']['templating.']['arrDataQuery.']['items.'];
      $arr_item  = $arr_items[$str_key]['arrQuery.']['andWhere'];
      $conf_sql['andWhere'] = $conf_sql['andWhere'] . $arr_item;
    }
      // plugin [template] int_templating_dataQuery has a value
      // and where data query



      //////////////////////////////////////////////////////
      //
      // Clean up LF and CR (Line Feed and Carriage Return)

    foreach ($arr_query_parts as $str_query_part)
    {
      $conf_sql[$str_query_part]  = $this->pObj->objZz->cleanUp_lfCr_doubleSpace
                                    (
                                      $conf_sql[$str_query_part]
                                    );
    }
      // Clean up LF and CR (Line Feed and Carriage Return)


    
      //////////////////////////////////////////////////////
      //
      // Replace SQL function with an alias

      // LOOP replace each SQL function which its alias
    foreach( ( array ) $this->conf_view['select.']['deal_as_table.'] as $arr_dealastable )
    {
      $str_statement  = $arr_dealastable['statement'];
      $str_aliasTable = $arr_dealastable['alias'];
      $arr_dealAlias[$str_aliasTable] = $str_statement;
      // I.e.: $conf_sql['select'] = CONCAT(tx_bzdstaffdirectory_persons.title, ' ', tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name), tx_bzdstaffdirectory_groups.group_name
      $conf_sql['select'] = str_replace( $str_statement, $str_aliasTable, $conf_sql['select'] );
      // I.e.: $conf_sql['select'] = tx_bzdstaffdirectory_persons.last_name, tx_bzdstaffdirectory_groups.group_name
    }
      // LOOP replace each SQL function which its alias
      // Replace SQL function with an alias



      ////////////////////////////////////////////////////////////////////
      //
      // Does ORDER BY contains further tables and fields?

    $arr_addToSelect      = false;
    $csvOrderByWoAscDesc  = $this->pObj->objSqlFun_3x->get_orderBy_tableFields( $conf_sql['orderBy'] );
    $arrOrderByWoAscDesc  = $this->pObj->objZz->getCSVasArray( $csvOrderByWoAscDesc );
    $arrSelect            = $this->pObj->objZz->getCSVasArray( $conf_sql['select'] );

      // #110110, cweiske, '11870
    foreach ( $arrSelect as $key => $field )
    {
      $arrSelect[$key] = $this->pObj->objSqlFun_3x->get_sql_alias_behind( $field );
    }
      // #110110, cweiske, '11870

      // Is there any difference?
    $arr_addToSelect = array_diff( $arrOrderByWoAscDesc, $arrSelect );
      // Does ORDER BY contains further tables and fields?


    
      ////////////////////////////////////////////////////////////////////
      //
      // IF order by has new tableFields

    if( count( ( array ) $arr_addToSelect ) > 1 )
    {
        // SELECT has aliases
      if( ! ( strpos( $conf_sql['select'], " AS " ) === false ) )
      {
        foreach( ( array ) $arr_addToSelect as $tableField )
        {
          $conf_sql['select'] = $conf_sql['select'] . ', ' . $tableField . ' AS \'' . $tableField . '\'';
        }
      }
        // SELECT has aliases
        // SELECT hasn't aliases
      if( strpos($conf_sql['select'], " AS " ) === false )
      {
        $csvAddToSelect     = implode( ', ', $arr_addToSelect );
        $conf_sql['select'] = $conf_sql['select'] . ', ' . $csvAddToSelect;
      }
        // SELECT hasn't aliases

        // Add the new table.fields to the consolidation array
//      if( ! is_array( $this->pObj->arrConsolidate['addedTableFields'] ) )
//      {
//        $this->pObj->arrConsolidate['addedTableFields'] = array( );
//      }
      $this->pObj->arrConsolidate['addedTableFields'] =
        array_merge
        (
          ( array ) $this->pObj->arrConsolidate['addedTableFields'],
          $arr_addToSelect
        );
        // Add the new table.fields to the consolidation array
    }
      // IF order by has new tableFields



      //////////////////////////////////////////////////////
      //
      // Add aliases to the SELECT statement

      // There is a 'bug' in exec_SELECTgetRows:
      // It cleares all tables in the selection statement. So it isn't possible to select fields
      // from different tables, if it has the same name.
      // SOLUTION: Aliasing all select values with AS
      // EXAMPLE: tx_ships_main.g2_name AS 'tx_ships_main.g2_name' ...

    $arr_aliasedSelect = null;

      // LOOP all tableFields from select
    $arr_tableFields    = explode( ',', $conf_sql['select'] );
//var_dump( __METHOD__, __LINE__, $arr_tableFields );
//$this->pObj->dev_var_dump( $arr_tableFields );
//exit;
    foreach( $arr_tableFields as $tableField )
    {
        // 3.9.19, dwildt, 1+
      $tableField = trim( $tableField );
      if( empty ( $tableField ) )
      {
        continue;
      }
        // 110110, cweiske, #11870
      if( strpos( $tableField, ' AS ' ) !== false )
      {
        $arr_aliasedSelect[] = $tableField;
        continue;
      }
        // 110110, cweiske, #11870

      list( $table, $field ) = explode( '.', $tableField );
      $table  = trim( $table );
      $field  = trim( $field );

      $tableField = $table . '.' . $field;
      $alias      = $tableField;

        // Do we have a function instead of table.field?
      if( $arr_dealAlias[$tableField] )
      {
        $tableField = $arr_dealAlias[$tableField];
        // We want the sytax: function AS 'table.field'
        // I.e.: CONCAT(tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name) AS 'tx_bzdstaffdirectory_persons.last_name'
      }
      // Do we have a function instead of table.field?

      $arr_aliasedSelect[] = $tableField.' AS \'' . $alias . '\'';
    }
      // LOOP all tableFields from select

    $str_aliasedSelect  = implode( ', ', $arr_aliasedSelect );
    $conf_sql['select'] = $str_aliasedSelect;
      // Add aliases to the SELECT statement



      //////////////////////////////////////////////////////
      //
      // Set the global array conf_sql

    $this->pObj->conf_sql = $conf_sql;
      // Set the global array conf_sql

    return $conf_sql;

  }















 /**
  * THIS ISN'T THE GROUPBY FOR THE SQL QUERY
  * Allocates a proper group by in the global groupBy
  * It returns the group by part, which is needed for consolidation
  * If there is more than one value, all other values will be removed
  * If there are aliases, the aliases will be deleted.
  *
  * @return	string		$groupBy: The first groupBy value with ASC or DESC, if there is one
  */
  function set_confSql_groupBy() {

    ////////////////////////////////////////////////////////////////////
    //
    // RETURN if there isn't any groubBy in the TypoScript

    if(!isset($this->conf_view['groupBy']))
    {
      return false;
    }
    if(!$this->conf_view['groupBy'])
    {
      return false;
    }
    // RETURN if there isn't any groubBy in the TypoScript


    $groupBy = $this->conf_view['groupBy'];


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
        t3lib_div::devLog('[HELP/SQL] Please configure \''.$this->conf_path.'.groupBy\'', $this->pObj->extKey, 1);
      }
    }
    // We like only the first value


    //////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devLog('[INFO/SQL] GROUP BY is: \''.$groupBy.'\'. Be aware: this is for php ordering and for consolidation but not for the SQL group-by-clause.', $this->pObj->extKey, -1);
    }
    // DRS - Development Reporting System

    return $groupBy;

  }











 /**
  * Sets the global vars $str_sqlDeviderDisplay and $str_sqlDeviderWorkflow
  *
  * @return	boolean		false
  */
  function set_confSqlDevider()
  {
    // Get global or local advanced array
    $arr_conf_advanced = $this->conf['advanced.'];
    #10116
    if(!empty($this->conf_view['advanced.']))
    {
      $arr_conf_advanced = $this->conf_view['advanced.'];
    }
    // Get global or local advanced array

    // Devider for display: Wrap the devider for the values of children records
    $str_deviderDisplay = $arr_conf_advanced['sql.']['devider.']['childrenRecords.']['value'];
    $conf_stdWrap       = $arr_conf_advanced['sql.']['devider.']['childrenRecords.'];
    if (is_array($conf_stdWrap))
    {
      $str_deviderDisplay = $this->pObj->objWrapper->general_stdWrap($str_deviderDisplay, $conf_stdWrap);
    }
    $this->str_sqlDeviderDisplay = $str_deviderDisplay;
    // Devider for display: Wrap the devider for the values of children records

    // Devider for workflow: Wrap the devider for the values of children records
    $str_deviderWorkflow = $arr_conf_advanced['sql.']['devider.']['workflow.']['value'];
    $conf_stdWrap        = $arr_conf_advanced['sql.']['devider.']['workflow.'];
    if (is_array($conf_stdWrap))
    {
      $str_deviderWorkflow = $this->pObj->objWrapper->general_stdWrap($str_deviderWorkflow, $conf_stdWrap);
    }
    $this->str_sqlDeviderWorkflow = $str_deviderWorkflow;
    // Devider for workflow: Wrap the devider for the values of children records

    return false;
  }

















/**
 * fetch_realTableWiField( )  : Allocates the class array arr_table_wi_arrFields with realname 
 *                              tables and there fields
 *
 * @param	string		$str_queryPart: The SQL query part out of the global conf_sql.
 * @return	void
 * 
 * @version     4.2.0
 * @since       2.0.0
 */
  private function fetch_realTableWiField( $str_queryPart, $key_queryPart ) 
  {

      // RETURN : $str_queryPart is empty
    if ( empty( $str_queryPart ) )
    {
      return false;
    }
      // RETURN : $str_queryPart is empty

    $arrCsv     = explode(',', $str_queryPart);
    $arrCsv     = $this->pObj->objSqlFun_3x->clean_up_as_and_alias($arrCsv);
    $arrTmp[0]  = $arrCsv;
    $arrTmp     = $this->pObj->objSqlFun_3x->replace_tablealias($arrTmp);
    $arrCsv     = $arrTmp[0];
    
      // LOOP each query part
    foreach( ( array ) $arrCsv as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
        // 121211, dwildt, 6+ 
        // CONTINUE : table is empty
      if( empty( $table ) )
      {
        continue; 
      }
        // CONTINUE : table is empty
        // 121211, dwildt, 6+
      
      $table = trim( $table );
      $field = trim( $field );
      
        // #43889, 121211, dwildt
      switch( $key_queryPart )
      {
        case( 'select' ):
          break;
        case( 'orderBy' ):
        case( 'search' ):
        case( 'where' ):
        case( 'andWhere' ):
        case( 'filter' ):
          if( $field == 'uid' )
          {
            continue 2;
          }
          break;
        default:
          $prompt = __METHOD__ . ' (line: ' . __LINE__ . '): <br />' .
                    'Undefined value in SWITCH: ' . $key_queryPart;
          die( $prompt );
          break;
      }
        // #43889, 121211, dwildt

      if( ! is_array( $this->arr_realTables_arrFields[$table] ) )
      {
        $this->arr_realTables_arrFields[$table][] = $field;
      }
      if( is_array( $this->arr_realTables_arrFields[$table] ) )
      {
        if( ! in_array( $field, $this->arr_realTables_arrFields[$table] ) )
        {
          $this->arr_realTables_arrFields[$table][] = $field;
        }
      }
    }
      // LOOP each query part

    return;
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_typoscript.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_typoscript.php']);
}

?>
