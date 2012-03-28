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
 *   72: class tx_browser_pi1_sql_functions
 *  127:     function __construct($parentObj)
 *
 *              SECTION: Query building
 *  156:     function init( )
 *  202:     private function get_queryArray( )
 *  235:     public function get_queryArrayAuto( )
 *
 *              SECTION: Set global variables
 *  383:     private function init_global_csvAll( )
 *  452:     private function init_global_csvSelect( )
 *  590:     private function init_global_csvSearch( )
 *  644:     private function init_global_csvOrderBy( )
 *  796:     private function zz_cObjGetSingle( $currConfPath, $statement )
 *
 *              SECTION: ZZ: Helper
 *  911:     private function zz_aliasToTable( $arr_aliastableField )
 *  960:     private function zz_sqlExpressionAndAliasToTable( $arr_tablefields )
 *  998:     private function zz_sqlExpressionToAlias( $sqlStatement )
 * 1038:     private function zz_getTableFieldWoAs( $tableFieldWiAlias )
 * 1055:     private function zz_getAlias( $tableFieldWiAlias )
 * 1080:     private function zz_getTableFieldOrAlias( $tableFieldWiAlias, $bool_returnTableField )
 *
 * TOTAL FUNCTIONS: 15
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
    * ZZ: Helper
    *
    **********************************************/



  /**
   * zz_aliasToTable( ) : Moves aliases to tables in $arr_localtable.
   *                      Aliases come from aliases.tables.
   *                      If there isn't any alias, than nothing will replaced.
   *
   * @param	array		$arr_aliastableField: Array with local table values
   * @return	array		$arr_aliastableField with replaced table aliases.
   * @version 3.9.12
   * @since   3.9.12
   */
  public function zz_aliasToTable( $arr_aliastableField )
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
   * zz_cObjGetSingle:  Wraps the given statement by the given TypoScript configuration.
   *                    It returns the statement unwrapped, if there isn't any TypoScript
   *                    configuratuion.
   *                    It prompts some helpful logs to the DRS for TYPO3 integrators.
   *
   * @param	string		$currConfPath : current TS configuration path like 'select.' or 'override.select.'
   * @param	string		$statement    : SQL statement like: "tt_news.title, tt_news.short, ..."
   * @param	string		$coa_name     : name of COA like TEXT or COA
   * @param	array     $coa_conf     : the COA, the configuration object array
   * @return	string	$statement    : wrapped or unwrapped statement
   * @version 3.9.12
   * @since   3.9.12
   */
  public function zz_cObjGetSingle( $currConfPath, $statement, $coa_name, $coa_conf )
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






  /**
 * zz_sqlExpressionAndAliasToTable( ) : Replaces in expressions to aliases all given
 *                  table.field-alias pairs. Replaces all aliases to table.field.
 *
 * @param	array		$arr_tablefields  : Array with table.field values maybe with an AS
 * @return	array		$arr_tablefields  : Array with table.fields (real names)
 * @version 3.9.12
 * @since   3.9.12
 */
  public function zz_sqlExpressionAndAliasToTable( $arr_tablefields )
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
      $tableFieldWiAlias      = $this->zz_sqlExpressionToAlias( $tableFieldWiAlias );
      $tableFieldOrAlias      = $this->zz_getAlias( $tableFieldWiAlias );
      $arr_tablefields[$key]  = $tableFieldOrAlias;
    }
      // LOOP array with tablefields

      // Move aliases to real table names
    $arr_tablefields = $this->zz_aliasToTable( $arr_tablefields );
    return $arr_tablefields;
  }



  /**
 * zz_sqlExpressionToAlias( ) :  Replaces an expression in a SQL statement with
 *                               the alias from the deal_as_table array
 *
 * @param	string		$sqlStatement : The current SQL statement
 * @return	string		$sqlStatement : The handled SQL statement
 * @version 3.9.12
 * @since   3.9.12
 */
  public function zz_sqlExpressionToAlias( $sqlStatement )
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





  /**
 * zz_getTableFieldWoAs( ) :  Cuts the AS ..., if table.field is with an AS ...
 *
 * @param	string		$tableFieldWiAlias  : table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		$tableField         : table.field without an AS
 * @version 3.9.12
 * @since   3.9.12
 */
  public function zz_getTableFieldWoAs( $tableFieldWiAlias )
  {
    $tableField = $this->zz_getTableFieldOrAlias( $tableFieldWiAlias, true );
    return $tableField;
  }



  /**
 * zz_getAlias( ) : Returns the part behind the AS, if table.field has an AS ...
 *                  If not, it returns the table.field
 *
 * @param	string		$tableFieldWiAlias  : table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		$alias              :	Part behind the AS. If there is no AS, it returns $str_tablefield
 * @version 3.9.12
 * @since   3.9.12
 */
  public function zz_getAlias( $tableFieldWiAlias )
  {
    $alias = $this->zz_getTableFieldOrAlias( $tableFieldWiAlias, false );
    return $alias;
  }








  /**
 * zz_getTableFieldOrAlias( ) : Returns table.field or the alias.
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
  private function zz_getTableFieldOrAlias( $tableFieldWiAlias, $bool_returnTableField )
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



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions.php']);
}

?>