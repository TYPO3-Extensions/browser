<?php
  /***************************************************************
  *  Copyright notice
  *
  *  (c) 2009 Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
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
  * The class tx_browser_pi1_sql_manual_3x bundles sql methods for this case: The user has defined a SELECT
  * statement with a FROM and a WHERE clause and maybe with the array JOINS.
  *
  * @author    Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
  * @package    TYPO3
  * @subpackage  browser
  * 
  * @version  4.5.6
  * @since    2.0.0 
  */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   52: class tx_browser_pi1_sql_manual_3x
 *   69:     function __construct($parentObj)
 *
 *              SECTION: SQL
 *  106:     function get_queryArray()
 *
 *              SECTION: Check TypoScript
 *  498:     function check_typoscript_query_parts()
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_manual_3x
{

/**
 * __construct( ) : Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 * 
 * @version  4.5.6
 * @since    2.0.0 
 */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
  }



    /***********************************************
    *
    * SQL
    *
    **********************************************/

/**
 * get_queryArray( ) : Bulding the query. ###PID_LIST### and ###UID### are system defined constants.
 *
 * @return	string		SQL query
 * @version  4.5.6
 * @since    2.0.0 
 */
  public function get_queryArray( )
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      // The SQL query template
    $str_query = "
      ###SELECT###
      ###FROM###
      ###JOINS###
      ###WHERE###
      ###GROUP_BY###
      ###ORDER_BY###
      ";

      // SELECT statement
    $str_select = $this->get_queryArraySelect( $conf_view );
    $str_query  = str_replace('###SELECT###', "SELECT " . $str_select,  $str_query );

      // FROM statement I/II
    $str_from   = $this->get_queryArrayFrom( $conf_view );
    // Handle query below joins!

      // WHERE clause
    $arr_where          = $this->get_queryArrayWhere( $conf_view );
    $str_where          = $arr_where['where']; 
    // 130502, dwildt, 1-
    //$str_enable_fields  = $arr_where['enableFields'];
    unset( $arr_where );
    $str_query = str_replace('###WHERE###', "WHERE ".$str_where, $str_query);


      // JOINS
    $str_joins  = $this->get_queryArrayJoins( $conf_view );
    $str_query  = str_replace( '###JOINS###', $str_joins, $str_query );
    $str_from   = $str_from
                . $str_joins 
                ;

      // FROM statement II/II
    $str_query  = str_replace('###FROM###', "FROM ".$str_from, $str_query);

      // GROUP BY :todo:
    //$str_query = str_replace('###GROUP_BY###', '', $str_query);


      // ORDER BY
    $str_order_by = $this->get_queryArrayOrderBy( $conf_view );
    $str_query    = str_replace( '###ORDER_BY###', $str_order_by, $str_query );




    /////////////////////////////////////////////////////////////////
    //
    // Clean Up: Delete unused markers

    $str_query = str_replace('###JOINS###',     '', $str_query);
    $str_query = str_replace('###WHERE###',     '', $str_query);
    $str_query = str_replace('###GROUP_BY###',  '', $str_query);
    $str_query = str_replace('###ORDER_BY###',  '', $str_query);

    $str_query = str_replace('AND ', "\n".'                    AND ', $str_query);
    $str_query = str_replace('OR ', "\n".'                    OR ', $str_query);
    // For human readable

    $arr_return['data']['query']    = $str_query;
    $arr_return['data']['select']   = $str_select;
    $arr_return['data']['from']     = $str_from;
    $arr_return['data']['where']    = $str_where;
    $arr_return['data']['orderBy']  = $str_order_by;
    $arr_return['data']['groupBy']  = $str_group_by;


    /////////////////////////////////////////////////////////////////
    //
    // Extend the query, if it has synonyms

    $arr_return = $this->pObj->objSqlFun_3x->query_with_synonyms($arr_return);


    /////////////////////////////////////////////////////////////////
    //
    // Replace Markers for pidlist and uid

    $str_pid_list = $this->pObj->pidList;
    $str_pid_list = str_replace(',', ', ', $str_pid_list);
    // For human readable
    foreach((array) $arr_return['data'] as $str_query_part => $str_statement)
    {
      $str_statement                        = str_replace('###PID_LIST###', $str_pid_list,                  $str_statement);
      $str_statement                        = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_statement);
      $arr_return['data'][$str_query_part]  = $str_statement;
    }


    /////////////////////////////////////////////////////////////////
    //
    // Return the result

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] '.$arr_return['data']['query'], $this->pObj->extKey, 0);
    }

      // #47678, 130429, dwildt, 3+      
    $table      = $this->pObj->localTable;
    $arr_result = $this->pObj->objLocalise->localisationFields_select( $table );
//var_dump( __METHOD__, __LINE__, $table, $this->pObj->objLocalise->int_localisation_mode );
    unset( $arr_result );


    return $arr_return;
  }

/**
 * get_queryArrayFrom( ) : 
 * 
 * @param   array               $conf_view  : Configuration of the current view
 *
 * @return	string		FROM statement
 * @version  4.5.6
 * @since    2.0.0 
 */
  private function get_queryArrayFrom( $conf_view )
  {
    $str_from_table = $conf_view['from.']['table'];
    $str_from_alias = $conf_view['from.']['alias'];
    $str_from       = $str_from_table . " AS `" . $str_from_alias . "`";

    return $str_from;
  }

/**
 * get_queryArrayJoins( ) : 
 * 
 * @param   array               $conf_view  : Configuration of the current view
 *
 * @return	string		FROM statement
 * @version  4.5.6
 * @since    2.0.0 
 */
  private function get_queryArrayJoins( $conf_view )
  {
    $str_joins = '';
    if( is_array( $conf_view['joins.'] ) )
    {
        // We have joins
      $arr_joins  = $conf_view['joins.'];
      if( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] There are ' . count( $arr_joins ) . ' joins.', $this->pObj->extKey, 0 );
      }
      $str_joins  = '';
      foreach( ( array ) $arr_joins as $arr_join )
      {
        $str_enable_fields = '';
        if( is_array( $GLOBALS['TCA'][$arr_join['table']] ) )    // I.e.: tx_civserv_organisation_or_structure_mm
        {
          // Table is in the registered in the TCA array
          $str_enable_fields  = $this->pObj->cObj->enableFields( $arr_join['table'] );
          $str_enable_fields  = str_replace($arr_join['table'], $arr_join['alias'], $str_enable_fields );
        }
        $str_joins  = $str_joins . PHP_EOL
                    . $arr_join['type'] . " " . $arr_join['table'] . " AS `" . $arr_join['alias'] . "` " . PHP_EOL
                    . "ON (" . $arr_join['on'] . " " . $str_enable_fields . ") "
                    ;
                    // LEFT JOIN tx_civserv_organisation_or_structure_mm AS ###table_mm###
                    // ON (
                    //       ###table_1###.uid = ###table_mm###.uid_local
                    // )
      }
    }

    // Is there a join from the filter class?
    $str_joins_filter  = '';
// #41754.01, 1210101, dwildt, -
// $this->pObj->arr_andWhereFilter isn't never allocated
//      if (is_array($this->pObj->arr_andWhereFilter))
//      {
//        foreach((array) $this->pObj->arr_andWhereFilter as $tableField => $str_andWhere)
//        {
//          list($table, $field) = explode('.', $tableField);
//          $arr_joins_filter = $conf_view['filter.'][$table.'.'][$field.'.']['joins.'];
////array(2) {
////  ["0."]=>
////  array(4) {
////    ["type"]=>
////    string(9) "LEFT JOIN"
////    ["table"]=>
////    string(41) "tx_bzdstaffdirectory_persons_locations_mm"
////    ["alias"]=>
////    string(17) "persons_locations"
////    ["on"]=>
////    string(41) "persons.uid = persons_locations.uid_local"
////  }
////  ["1."]=>
////  array(4) {
////    ["type"]=>
////    string(9) "LEFT JOIN"
////    ["table"]=>
////    string(30) "tx_bzdstaffdirectory_locations"
////    ["alias"]=>
////    string(9) "locations"
////    ["on"]=>
////    string(83) "locations.uid = persons_locations.uid_foreign AND locations.pid IN (###PID_LIST###)"
////  }
////}
//          if (is_array($arr_joins_filter))
//          {
//            if ($this->pObj->b_drs_sql)
//            {
//              t3lib_div::devlog('[INFO/SQL] There are '.count ($arr_joins_filter).' joins.', $this->pObj->extKey, 0);
//            }
//            foreach ($arr_joins_filter as $arr_join)
//            {
//              $str_enable_fields = '';
//              if (is_array($GLOBALS['TCA'][$arr_join['table']]))    // I.e.: tx_civserv_organisation_or_structure_mm
//              {
//                // Table is in the registered in the TCA array
//                $str_enable_fields  = $this->pObj->cObj->enableFields($arr_join['table']);
//                $str_enable_fields  = str_replace($arr_join['table'], $arr_join['alias'], $str_enable_fields);
//              }
//              $str_joins_filter   .= "
//                ".$arr_join['type']." ".$arr_join['table']." AS `".$arr_join['alias']."`
//                ON (
//                      ".$arr_join['on']." ".$str_enable_fields."
//                   ) ";
//              // LEFT JOIN tx_civserv_organisation_or_structure_mm AS ###table_mm###
//              // ON (
//              //       ###table_1###.uid = ###table_mm###.uid_local
//              // )
//            }
//          }
////var_dump($str_joins_filter);
////exit;
//        }
//      }
//      // Is there a andWhere statement from the filter class?
// #41754.01, 1210101, dwildt, -

    return $str_joins . $str_joins_filter;
  }

/**
 * get_queryArrayOrderBy( ) : 
 * 
 * @param   array               $conf_view  : Configuration of the current view
 *
 * @return	string		FROM statement
 * @version  4.5.6
 * @since    2.0.0 
 */
  private function get_queryArrayOrderBy( $conf_view )
  {
      // Process the piVar sort
    $str_order_by = $this->pObj->objSqlFun_3x->orderBy_by_piVar( );
    if ( ! $str_order_by )
    {
      $str_order_by = $conf_view['orderBy'];
    }
    
    if( $str_order_by )
    {
      $str_order_by = "ORDER BY "
                    . $str_order_by
                    ;
    }

    return $str_order_by;
  }

/**
 * get_queryArraySelect( ) : 
 * 
 * @param   array               $conf_view  : Configuration of the current view
 *
 * @return	string		SELECT statement
 * @version  4.5.6
 * @since    2.0.0 
 */
  private function get_queryArraySelect( $conf_view )
  {
      // Should the datas ordered hierarchical?
    $b_hierarchical = $conf_view['functions.']['hierarchical'];
    // Should the synonyms of the datas displayed too?
    $b_synonym      = $conf_view['functions.']['synonym'];


      // SELECT statement
    $str_select = $conf_view['select'];
    $str_select = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $str_select );

      // Extend the SELECT statement in case of hierarchical order
    if( $b_hierarchical )
    {
      $andSelect = $conf_view['functions.']['hierarchical.']['andSelect'];
      $andSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $andSelect );
      if( ! $andSelect )
      {
          // $andSelect is empty. Don't process the datas hierarchical.
        if ($this->pObj->b_drs_error)
        {
          $prompt = '...functions.hierarchical.andSelect is empty.';
          t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Datas won\'t be processed hierarchical!';
          t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'This isn\'t any matter, if you don\'t need hierarchical data.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'Please deactivate ...functions.hierarchical. Or maintain andSelect.';
          t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        }
        $b_hierarchical = false;
      }
      else
      {
        $str_select = $str_select . ", " . trim( $andSelect );
      }
    }
      // Extend the SELECT statement in case of hierarchical order

    // Extend the SELECT statement in case of synonyms
    if( $b_synonym )
    {
      $andSelect = $conf_view['functions.']['synonym.']['andSelect'];
      $andSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $andSelect );
      if( ! $andSelect )
      {
        // $andSelect is empty. Don't process the synonyms.
        if( $this->pObj->b_drs_error )
        {
          $prompt = '...functions.synonym.andSelect is empty.';
          t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Synonyms won\'t be processed!';
          t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'This isn\'t any matter, if you don\'t need synonyms.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'Please deactivate ...functions.synonym. Or maintain andSelect.';
          t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        }
        $b_synonym = false;
      }
      else
      {
        $str_select = $str_select.", ".trim($andSelect);
      }
    }
    // Extend the SELECT statement in case of synonyms

    return $str_select;
  }

/**
 * get_queryArrayWhere( ) : 
 * 
 * @param   array               $conf_view  : Configuration of the current view
 *
 * @return	array		where, enableFields
 * @version  4.5.6
 * @since    2.0.0 
 */
  private function get_queryArrayWhere( $conf_view )
  {
      // Search word
    $str_sword = $this->pObj->objSqlAut_3x->whereSearch( );

      // WHERE clause
    $str_from_table = $conf_view['from.']['table'];
    $str_from_alias = $conf_view['from.']['alias'];
    $str_where = false;

      // Extend WHERE clause in case of enable fields
    $str_enable_fields = false;
    if( is_array( $GLOBALS['TCA'][$str_from_table] ) )
    {
      // Table is registered in the TCA array
      $str_enable_fields  = $this->pObj->cObj->enableFields( $str_from_table ); // tx_civserv_organisation
      $str_enable_fields  = str_replace( $str_from_table, $str_from_alias, $str_enable_fields );
                            // replace tx_civserv_organisation with table_1
      $str_where  = $str_where
                  . $str_enable_fields
                  ;
    }
      // Extend WHERE clause in case of enable fields

      // Extend WHERE clause in case of a sword
    if ( $str_sword )
    {
      $str_where  = $str_where
                  . str_replace( $str_from_table, $str_from_alias, $str_sword )
                  ;
    }
      // Extend WHERE clause in case of a sword
// 090627, dwildt. :TODO: andWhere ist im globalen array conf_sql!

      // Extend WHERE clause in case of an andWhere value
    if( $conf_view['andWhere'] )
    {
      $str_where  = $str_where
                  . " AND " . $conf_view['andWhere']
                  ;
    }
      // Extend WHERE clause in case of an andWhere value

      // Clean Up: WHERE clause without first AND or first OR
    if( $str_where )
    {
      if (' AND ' == substr( $str_where, 0, strlen( ' AND ' ) ) )
      {
        // We don't want an AND as the first word
        $str_where = substr( $str_where, strlen( ' AND ' ) );
      }
      if (' OR ' == substr( $str_where, 0, strlen( ' OR ' ) ) )
      {
        // We don't want an OR as the first word
        $str_where = substr($str_where, strlen( ' OR ' ) );
      }
      $str_query = str_replace( '###WHERE###', "WHERE " . $str_where, $str_query );
    }
      // Clean Up: WHERE clause without first AND or first OR

      // RETURN WHERE and enableFields
    $arr_where                  = array( );
    $arr_where['where']         = $str_where;
    $arr_where['enableFields']  = $str_enable_fields;
    return $arr_where;
  }



    /***********************************************
    *
    * Check TypoScript
    *
    **********************************************/

/**
 * check_typoscript_query_parts( )  : Check, if we have a SELECT, FROM, WHERE and JOINS configuration
 *
 * Array with error message in case of errors
 *
 * @return	array		false, if we don't have a manual configuration, TRUE, if we have.
 */
  public function check_typoscript_query_parts()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


    // Do we have a from?
    if (!is_array($conf_view['from.']))
    {
      // We don't have a manual configuration, because we don't have a from.
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.'.from isn\'t an array. We don\'t have a manual SQL configuration.', $this->pObj->extKey, 0);
      }
      return false;
    }


// :TODO: dwildt, 090628
//      // We have a from. Do we have an andWhere too?
//      if (!$conf_view['andWhere'])
//      {
//        if ($this->pObj->b_drs_error)
//        {
//          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from is defined, but andWhere isn\'t defined.', $this->pObj->extKey, 3);
//          t3lib_div::devlog('[WARN/SQL] ABORT without any action.', $this->pObj->extKey, 2);
//        }
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
//        $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_typoscript_joins').'</p>';
//        $arr_return['error']['status'] = TRUE;
//        $arr_return['error']['header'] = $str_header;
//        $arr_return['error']['prompt'] = $str_prompt;
//        return $arr_return;
//      }

    // We have a from and a andWhere. Do we have an array aliases.tables too?
    if (!is_array($conf_view['aliases.']['tables.']))
    {
      if ($this->pObj->b_drs_error)
      {
// :TODO: dwildt, 090628
//          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from and andWhere is defined, but the array aliases.tables isn\'t defined.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from is defined, but the array aliases.tables isn\'t defined.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[WARN/SQL] ABORT without any action.', $this->pObj->extKey, 2);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_typoscript_joins').'</p>';
      $arr_return['error']['status'] = TRUE;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }

    // We have a from, a andWhere and a aliases.tables. Do we have an array aliases.fields too?
    if (!is_array($conf_view['aliases.']['fields.']))
    {
      if ($this->pObj->b_drs_error)
      {
// :TODO: dwildt, 090628
//          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from, andWhere and aliases.tables is defined, but the array aliases.fields isn\'t defined.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from and aliases.tables is defined, but the array aliases.fields isn\'t defined.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[WARN/SQL] ABORT without any action.', $this->pObj->extKey, 2);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_typoscript_joins').'</p>';
      $arr_return['error']['status'] = TRUE;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


      // We have a from and a andWhere. Do we have an array joins too?
//      if (!is_array($conf_view['joins.']))
//      {
//        if ($this->pObj->b_drs_error)
//        {
//          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.from and andWhere is defined, but the array joins isn\'t defined.', $this->pObj->extKey, 3);
//          t3lib_div::devlog('[WARN/SQL] ABORT without any action.', $this->pObj->extKey, 2);
//        }
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
//        $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_typoscript_joins').'</p>';
//        $arr_return['error']['status'] = TRUE;
//        $arr_return['error']['header'] = $str_header;
//        $arr_return['error']['prompt'] = $str_prompt;
//        return $arr_return;
//      }
//
//      // Has the array joins elements?
//      if (count($conf_view['joins.']) < 1)
//      {
//        if ($this->pObj->b_drs_error)
//        {
//          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.'.joins hasn\'t any element.', $this->pObj->extKey, 3);
//          t3lib_div::devlog('[WARN/SQL] ABORT without any action.', $this->pObj->extKey, 2);
//        }
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
//        $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_typoscript_joins').'</p>';
//        $arr_return['error']['status'] = TRUE;
//        $arr_return['error']['header'] = $str_header;
//        $arr_return['error']['prompt'] = $str_prompt;
//        return $arr_return;
//      }

    return TRUE;
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_manual_3x.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_manual_3x.php']);
}

?>