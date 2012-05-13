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
 * The class tx_browser_pi1_sql_functions bundles methods, which supports the SQL handling
 * statement with a FROM and a WHERE clause and maybe with the array JOINS.
 * It is the new SQL modul from Browser version 4.0 and it replaces the former SQL modul.
 *
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *
 * @version   3.9.12
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
 *   74: class tx_browser_pi1_sql_functions
 *  132:     public function __construct($parentObj)
 *
 *              SECTION: Handle SQL aliases
 *  163:     private function aliasToTable( $arr_aliastableField )
 *  210:     private function getTableFieldWoAs( $tableFieldWiAlias )
 *  227:     private function getAlias( $tableFieldWiAlias )
 *  252:     private function getTableFieldOrAlias( $tableFieldWiAlias, $bool_returnTableField )
 *
 *              SECTION: Handle SQL expressions
 *  305:     public function expressionAndAliasToTable( $arr_tablefields )
 *  343:     public function expressionToAlias( $sqlStatement )
 *
 *              SECTION: Handle SQL error
 *  397:     public function prompt_error( $query, $error )
 *
 *              SECTION: TypoScript for SQL statements
 *  455:     public function cObjGetSingle( $currConfPath, $statement, $coa_name, $coa_conf )
 *
 *              SECTION: Helpers
 *  547:     public function get_andWherePid( $table )
 *  621:     public function exec_SELECTquery( $select, $from, $where, $groupBy, $orderBy, $limit )
 *  667:     public function sql_query( $query )
 *  688:     public function zz_prependPiVarSort( $orderBy )
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_functions
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
    // [Boolean]  For development only: If it is true, SQL queries will prompted
    //            in the frontend but will not executed.
  var $dev_sqlPromptsOnly = false;

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
  public function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }









    /***********************************************
    *
    * Handle SQL aliases
    *
    **********************************************/



  /**
 * aliasToTable( ) : Moves aliases to tables in $arr_localtable.
 *                      Aliases come from aliases.tables.
 *                      If there isn't any alias, than nothing will replaced.
 *
 * @param	array		$arr_aliastableField: Array with local table values
 * @return	array		$arr_aliastableField with replaced table aliases.
 * @version 3.9.12
 * @since   3.9.12
 */
  private function aliasToTable( $arr_aliastableField )
  {
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;


      // RETURN, if we don't have any alias array
    if( ! is_array( $conf_view['aliases.']['tables.'] ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $conf_path . ' hasn\'t any array ' .
                  'aliases.tables. We don\'t process aliases.';
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
      return $arr_aliastableField;
    }
      // RETURN, if we don't have any alias array

    foreach( $arr_aliastableField as $key_field => $str_tablefield )
    {
      $arr_tablefield                     = explode( '.', trim( $str_tablefield ) );
      list( $str_tablealias, $str_field ) = $arr_tablefield;
      $str_tablereal                      = $conf_view['aliases.']['tables.'][$str_tablealias];
      if( $str_tablereal )
      {
        $arr_aliastableField[$key_field] = $str_tablereal . '.' . $str_field;
      }
    }
    return $arr_aliastableField;
  }





  /**
 * getTableFieldWoAs( ) :  Cuts the AS ..., if table.field is with an AS ...
 *
 * @param	string		$tableFieldWiAlias  : table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		$tableField         : table.field without an AS
 * @version 3.9.12
 * @since   3.9.12
 */
  private function getTableFieldWoAs( $tableFieldWiAlias )
  {
    $tableField = $this->getTableFieldOrAlias( $tableFieldWiAlias, true );
    return $tableField;
  }



  /**
 * getAlias( ) : Returns the part behind the AS, if table.field has an AS ...
 *                  If not, it returns the table.field
 *
 * @param	string		$tableFieldWiAlias  : table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		$alias              :	Part behind the AS. If there is no AS, it returns $str_tablefield
 * @version 3.9.12
 * @since   3.9.12
 */
  private function getAlias( $tableFieldWiAlias )
  {
    $alias = $this->getTableFieldOrAlias( $tableFieldWiAlias, false );
    return $alias;
  }








  /**
 * getTableFieldOrAlias( ) : Returns table.field or the alias.
 *                              If $bool_returnTableField is true, it returns the table.field.
 *                              If $bool_returnTableField is false, it returns the alias.
 *                              If there isn't any AS ... it returns the table.field.
 *
 * @param	string		$tableFieldWiAlias      : table.field with an AS like "news.uid AS 'news.uid'"
 * @param	boolean		$bool_returnTableField  : true: returns the table.field, false: returns the alias
 * @return	string		Returns the string before or behind the AS. If there is no AS, it returns $str_tablefield
 * @version 3.9.12
 * @since   3.9.12
 */
  private function getTableFieldOrAlias( $tableFieldWiAlias, $bool_returnTableField )
  {
    list( $tableField, $alias ) = explode ( ' AS ', $tableFieldWiAlias );

      // RETURN : table.field, there isn't any alias
    if( empty ( $alias ) )
    {
      return $tableField;
    }
      // RETURN : table.field, there isn't any alias

      // RETURN : table.field
    if( $bool_returnTableField )
    {
      return $tableField;
    }
      // RETURN : table.field

      // Delete apostrophs (') and spaces
    $alias = trim( $alias );
    $alias = str_replace( '\'', null, $alias );
    $alias = str_replace( '`',  null, $alias );
      // Delete apostrophs (') and spaces

      // RETURN : alias
    return $alias;
  }









    /***********************************************
    *
    * Handle SQL expressions
    *
    **********************************************/



  /**
 * expressionAndAliasToTable( ) : Replaces expressions to aliases in all given
 *                                table.field-alias pairs. Replaces all aliases to table.field.
 *
 * @param	array		$arr_tablefields  : Array with table.field values maybe with an AS
 * @return	array		$arr_tablefields  : Array with table.fields (real names)
 * @version 3.9.12
 * @since   3.9.12
 */
  public function expressionAndAliasToTable( $arr_tablefields )
  {
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;

    if( ! is_array( $arr_tablefields ) )
    {
      return $arr_tablefields;
    }

      // LOOP array with tablefields
    foreach( ( array ) $arr_tablefields as $key => $tableFieldWiAlias )
    {
      $tableFieldWiAlias      = $this->expressionToAlias( $tableFieldWiAlias );
      $tableFieldOrAlias      = $this->getAlias( $tableFieldWiAlias );
      $arr_tablefields[$key]  = $tableFieldOrAlias;
    }
      // LOOP array with tablefields

      // Move aliases to real table names
    $arr_tablefields = $this->aliasToTable( $arr_tablefields );
    return $arr_tablefields;
  }



  /**
 * expressionToAlias( ) : Replaces an expression in a SQL statement with
 *                        the alias from the deal_as_table array
 *
 * @param	string		$sqlStatement : The current SQL statement
 * @return	string		$sqlStatement : The handled SQL statement
 * @version 3.9.12
 * @since   3.9.12
 */
  public function expressionToAlias( $sqlStatement )
  {
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;

    if( ! is_array( $conf_view['select.']['deal_as_table.'] ) )
    {
      return $sqlStatement;
    }

    foreach( $conf_view['select.']['deal_as_table.'] as $arr_dealastable )
    {
      $expression    = $arr_dealastable['statement'];
      $alias         = $arr_dealastable['alias'];
      $sqlStatement  = str_replace( $expression, $alias, $sqlStatement );
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'SQL expression is replaced with alias. Expression: \"'.$expression.'\"; ' .
                  'Alias:  \"'.$alias.'\"';
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
    return $sqlStatement;
  }









    /***********************************************
    *
    * SQL prompts
    *
    **********************************************/



 /**
  * prompt_error( ): Prompts a SQL error.
  *                  It is with the query in case of an enabled DRS.
  *
  * @param	string		$query: the current query
  * @param	string		$error: the error message delivered by SQL
  * @return	array		$arr_return with elements for prompting
  * @version 3.9.12
  * @since   3.9.12
  */
  public function prompt_error( $query, $error, $debugTrailLevel )
  {
    $arr_return = array( );

    if( $this->pObj->b_drs_error )
    {
      //$debugTrailLevel      = 1; // 1 level up
      $debugTrail = $this->pObj->drs_debugTrail( $debugTrailLevel );
      t3lib_div::devlog( '[ERROR/SQL] ' . $query,  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/SQL] ' . $error,  $this->pObj->extKey, 3 );
      t3lib_div::devlog( '[ERROR/SQL] ABORT at ' . $debugTrail['prompt'], $this->pObj->extKey, 3 );
      $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' .
                      $this->pObj->pi_getLL( 'drs_security' ) . '</p>';
      $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
      $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
      $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $debugTrail['prompt'] . '</p>';
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
  
  
  
  /**
   * prompt_performance( ): Building the SQL query, returns the SQL result.
   *
   * @param     string  $iMilliseconds  : ...
   * @param     string  $promptHelp     : ...
   * @return	void
   * @version 3.9.13
   * @since   3.9.13
   */
  private function prompt_performance( $iMilliseconds, $promptHelp )
  {

      // RETURN : DRS is off
    if( ! $this->pObj->b_drs_warn )
    {
      return;
    }
      // RETURN : DRS is off
    

      // String for milliseconds
    $sMilliseconds = '(' . $iMilliseconds . ' ms)';

      // SWITCH : limit for milliseconds
    switch( true )
    {
      case( $iMilliseconds < 500 ):
        $prompt = 'Query needs less than a half second ' . $sMilliseconds . '.';
        t3lib_div::devlog( '[OK/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, -1 );
        break;
      case( $iMilliseconds >= 500 && $iMilliseconds < 5000 ):
        $prompt = 'Query needs more than a half second ' . $sMilliseconds . '.';
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
        break;
      case( $iMilliseconds >= 5000 && $iMilliseconds < 10000 ):
        $prompt = 'Query needs more than 5 seconds ' . $sMilliseconds . '.';
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
        break;
      case( $iMilliseconds >= 10000 ):
        $prompt = 'Query needs more than 10 seconds ' . $sMilliseconds . '.';
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
        break;
    }
      // SWITCH : limit for milliseconds
  }









    /***********************************************
    *
    * TypoScript for SQL statements
    *
    **********************************************/



  /**
 * cObjGetSingle( ):  Wraps the given statement by the given TypoScript configuration.
 *                    It returns the statement unwrapped, if there isn't any TypoScript
 *                    configuratuion.
 *                    It prompts some helpful logs to the DRS for TYPO3 integrators.
 *
 * @param	string		$currConfPath : current TS configuration path like 'select.' or 'override.select.'
 * @param	string		$statement    : SQL statement like: "tt_news.title, tt_news.short, ..."
 * @param	string		$coa_name     : name of COA like TEXT or COA
 * @param	array		$coa_conf     : the COA, the configuration object array
 * @return	string		$statement    : wrapped or unwrapped statement
 * @version 3.9.12
 * @since   3.9.12
 */
  public function cObjGetSingle( $currConfPath, $statement, $coa_name, $coa_conf )
  {
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;

      // RETURN : coa_conf isn't an array
    if( ! is_array( $coa_conf ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $conf_path . $currConfPath . ' hasn\'t any elements.';
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'If you like to wrap it, please configure i.e. '.
                  $conf_path . $currConfPath . ' = TEXT and ' .
                  $conf_path . $currConfPath . '.value = your value';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
      return $statement;
    }
      // RETURN : coa_conf isn't an array

      // RETURN value, if property isn't uppercase
    if( $coa_name != strtoupper( $coa_name ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $coa_name. ' doesn\'t seem to be a name for a TypoScript object like TEXT or COA.';
        t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'There won\'t be any wrap.';
        t3lib_div::devlog('[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'If you like to wrap it, please configure i.e. '.
                  $conf_path . $currConfPath . ' = TEXT and ' .
                  $conf_path . $currConfPath . '.value = your value';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
      }
      return $coa_name;
    }
      // RETURN value, if property isn't uppercase

      // RETURN : statement is empty
    if( empty ( $statement ) )
    {
      if ($this->pObj->b_drs_sql)
      {
        $prompt = 'Statement is empty.';
        t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'This is an undefined error. Please post this bug at http://typo3-browser-forum.de/';
        t3lib_div::devLog('[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'Statement won\'t be wrapped';
        t3lib_div::devlog('[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2);
      }
      return $statement;
    }
      // RETURN : statement is empty

    $statement  = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );

    if( $this->pObj->b_drs_sql )
    {
      $prompt = $conf_path . $currConfPath . ' is wrapped: ' . $statement;
      t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
    }

    return $statement;
  }









    /***********************************************
    *
    * Helpers
    *
    **********************************************/



  /**
 * get_andWherePid( ):  Get the table.pid IN (1,2,3,4) for an
 *                      and WHERE statement. Statement is with an AND
 *
 * @param	string		$table: Name of the current table
 * @return	string		$andWherePid: statement: table.pid IN (pidlist)
 */
  public function get_andWherePid( $table )
  {
    $conf       = $this->pObj->conf;
    $tableField = $table.'.pid';
    $pidList    = $this->pObj->pidList;

      // IF foreignTable has a pidList in the TypoScript
    if( isset( $conf['foreignTables.'][$table . '.']['csvPidList'] ) )
    {
      $confPidList = $conf['foreignTables.'][$table.'.']['csvPidList'];
      if ( $this->pObj->b_drs_sql )
      {
        $prompt = $table . ' has a pidList in foreignTables.' . $table . '.csvPidList: ' . $confPidList;
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $int_deep = 0;
      if( isset( $conf['foreignTables.'][$table.'.']['intDeep'] ) )
      {
        $int_deep = $conf['foreignTables.'][$table.'.']['intDeep'];
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'pidList should be exetended with ' . $int_deep.' levels. See intDeep.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
      }
      if( ! isset($conf['foreignTables.'][$table.'.']['intDeep'] ) )
      {
        if( $this->pObj->b_drs_sql )
        {
          $prompt = 'pidList shouldn\'t be extended.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'Change it? Configure foreignTables.' . $table . '.intDeep';
          t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }
      $pidList = $this->pObj->pi_getPidList( $confPidList, $int_deep );
    }
      // IF foreignTable has a pidList in the TypoScript

      // IF foreignTable hasn't any pidList in the TypoScript
    if( ! isset( $conf['foreignTables.'][$table.'.']['csvPidList'] ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $table . ' hasn\'t any own pidList.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'Change it? Please configure foreignTables.' . $table . '.csvPidList';
        t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
      }
    }
      // IF foreignTable hasn't any pidList in the TypoScript

      // RETURN the result
    $andWherePid = " AND " . $tableField ." IN (" . $pidList .")";
    return $andWherePid;
  }



  /**
 * exec_SELECTquery( ) :  Same as $GLOBALS['TYPO3_DB']->SELECTquery. But if
 *                        class var $dev_sqlPromptsOnly is true, SQL query
 *                        won't executed but prompted in the frontend.
 *
 * @param	string		$select   : SELECT statement
 * @param	string		$from     : FROM statement
 * @param	string		$where    : WHERE statement
 * @param	string		$groupBy  : GROUP BY statement
 * @param	string		$orderBy  : ORDER BY statement
 * @param	string		$limit    : LIMIT statement
 * @return	array		$res      : SQL result
 * @version 3.9.12
 * @since   3.9.12
 */
  public function exec_SELECTquery( $select, $from, $where, $groupBy, $orderBy, $limit )
  {
    $promptOptimise = 'Sorry, no help!';
    
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

      // RETURN : query should prompt only
    if( $this->dev_sqlPromptsOnly )
    {

        // Get query
      var_dump( $query );
      return;
    }
      // RETURN : query should prompt only

      // Enable DRS performance
    if( $this->pObj->b_drs_warn )
    {
      $b_drs_performBak           = $this->pObj->b_drs_perform;
      $this->pObj->b_drs_perform  = true;
    }
      // Enable DRS performance

      // Log the time
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel + 1, 'START' );
    $tt_start = $this->pObj->tt_prevEndTime;

      // Execute query
    $res   = $GLOBALS['TYPO3_DB']->exec_SELECTquery
            (
              $select,
              $from,
              $where,
              $groupBy,
              $orderBy,
              $limit
            );
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
      // Execute query

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_sql )
    {
      $debugTrail = $this->pObj->drs_debugTrail( $debugTrailLevel );
      $prompt     = $debugTrail['prompt'] . ': ' . $query;
      t3lib_div::devlog( '[OK/SQL] ' . $prompt,  $this->pObj->extKey, -1 );
      $prompt     = 'Be aware of the multi-byte notation, if you want to use the query ' .
                    'in your SQL shell or in phpMyAdmin.';
      t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS - Development Reporting System
      
      // Log the time
    $this->pObj->timeTracking_log( $debugTrailLevel + 1,  'STOP' );
    $this->pObj->timeTracking_prompt( $debugTrailLevel + 1, $query );

      // RESET DRS performance
    if( $this->pObj->b_drs_warn )
    {
      $this->pObj->b_drs_perform = $b_drs_performBak;
    }
    $tt_end = $this->pObj->tt_prevEndTime;

      // DRS - Performance
    $iMilliseconds  = $tt_end - $tt_start;
    $this->prompt_performance( $iMilliseconds, $promptOptimise );
      // DRS - Performance

      // Error management
    if( $error )
    {
        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
      $arr_return = $this->prompt_error( $query, $error, $debugTrailLevel + 1 );
    }
      // Error management

    $arr_return['data']['query']  = $query;
    $arr_return['data']['res']    = $res;
    return $arr_return;
  }



 /**
  * sql_query( ) :  Same as $GLOBALS['TYPO3_DB']->sql_query. But if
  *                 class var $dev_sqlPromptsOnly is true, SQL query
  *                 won't executed but prompted in the frontend.
  *
  * @param    string	$query            : SQL query
  * @param    string	$promptOptimise   : Prompt in case of a performance problem
  * @param    string    $debugTrailLevel  : level for debug trail
  * @return   array	$res              : SQL result
  * @version 3.9.12
  * @since   3.9.12
  */
  public function sql_query( $query, $promptOptimise, $debugTrailLevel )
  {
    if( $this->dev_sqlPromptsOnly )
    {
      var_dump( $query );
      return;
    }

    $debugTrail           = $this->pObj->drs_debugTrail( $debugTrailLevel );
    $localDebugTrailLevel = $debugTrailLevel + 1;
    
      // Enable DRS performance
    if( $this->pObj->b_drs_warn )
    {
      $b_drs_performBak           = $this->pObj->b_drs_perform;
      $this->pObj->b_drs_perform  = true;
    }
      // Enable DRS performance

      // Log the time
    $this->pObj->timeTracking_log( $localDebugTrailLevel,  'START' );
    $tt_start = $this->pObj->tt_prevEndTime;

      // Execute the query
    $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
    $error = $GLOBALS['TYPO3_DB']->sql_error( );

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_sql )
    {
$this->pObj->dev_var_dump( $res );
      $prompt = $debugTrail['prompt'] . ': ' . $query;
      t3lib_div::devlog( '[OK/SQL] ' . $prompt,  $this->pObj->extKey, -1 );
      $prompt = 'Be aware of the multi-byte notation, if you want to use the query ' .
                'in your SQL shell or in phpMyAdmin.';
      t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS - Development Reporting System
      
      // Log the time
    $this->pObj->timeTracking_log( $localDebugTrailLevel, 'STOP' );
    $this->pObj->timeTracking_prompt( $localDebugTrailLevel, $query );

      // RESET DRS performance
    if( $this->pObj->b_drs_warn )
    {
      $this->pObj->b_drs_perform = $b_drs_performBak;
    }
    $tt_end = $this->pObj->tt_prevEndTime;

      // DRS - Performance
    $iMilliseconds  = $tt_end - $tt_start;
    $this->prompt_performance( $iMilliseconds, $promptOptimise );
      // DRS - Performance

      // Error management
    if( $error )
    {
      $arr_return = $this->prompt_error( $query, $error, $localDebugTrailLevel );
    }
      // Error management

    $arr_return['data']['query'] = $query;
    $arr_return['data']['res']   = $res;
    return $arr_return;
  }



  /**
 * zz_prependPiVarSort( ):  Prepends the value from the piVars['sort'] to the
 *                          the given ORCER BY statement, if there is a piVar.
 *
 * @param	[type]		$$orderBy: ...
 * @return	string		The ORDER BY statement
 */
  public function zz_prependPiVarSort( $orderBy )
  {
    $b_desc       = false;
    $str_order_by = false;


      // RETURN without any piVar[sort]
    if( ! isset( $this->pObj->piVars['sort'] ) )
    {
      return $orderBy;
    }
      // RETURN without any piVar[sort]


      // I.e: tt_news.title:1
    $arr_sort = explode( ':', $this->pObj->piVars['sort'] );
    list( $tablefield, $b_desc ) = $arr_sort;

    // We need $tablefield and $b_desc local
    list( $this->pObj->internal['orderBy'], $this->pObj->internal['descFlag'] ) = $arr_sort;

      // Get DESC or ASC
    if( $b_desc )
    {
      $str_order = ' DESC';
    }
    if( ! $b_desc )
    {
      $str_order = ' ASC';
    }
      // Get DESC or ASC

      // piVarSort expression
    if( $tablefield )
    {
      $piVarSort = $tablefield . $str_order;
    }

    if( $orderBy )
    {
      $orderBy = $piVarSort . ', ' . $orderBy;
    }
    else
    {
      $orderBy = $piVarSort;
    }

    return $orderBy;
  }



 /**
  * zz_concatenateWithAnd( )  : Concatenate first param AND second param. Method
  *                             handles empty params.
  *
  * @param    string    $param_1  : first param
  * @param    string    $param_2  : second param
  * @return   string	Pramams cocatenated with AND
  * @version  3.1.13
  * @since    3.1.13
  */
  public function zz_concatenateWithAnd( $param_1, $param_2 )
  {
      // RETURN $param_2
    if( empty ( $param_1 ) )
    {
      return $param_2;
    }
      // RETURN $param_2

      // RETURN $param_1
    if( empty ( $param_2 ) )
    {
      return $param_1;
    }
      // RETURN $param_1

      // Cut the ' AND' of the end of param_1
    $param_1 = trim( $param_1 );
    if( substr( $param_1, -4) == ' AND' )
    {
      $param_1 = substr( $param_1, 0, strlen( $param_1 ) - 4 );
    }
      // Cut the ' AND' of the end of param_1
      
      // Cut the 'AND ' of the beginning of param_2
    $param_2 = trim( $param_2 );
    if( substr( $param_2, 0, 4) == 'AND ' )
    {
      $param_2 = substr( $param_2, 4 );
    }
      // Cut the 'AND ' of the beginning of param_2

      // RETURN $param_1 AND $param_2
    $param_1 = $param_1 . " AND " . $param_2;
    return $param_1;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions.php']);
}

?>
