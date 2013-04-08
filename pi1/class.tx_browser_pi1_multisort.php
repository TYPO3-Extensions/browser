<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_multisort bundles methods for ordering rows.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @since    3.4.4
* @version  4.1.13
*
* @package    TYPO3
* @subpackage  browser
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   57: class tx_browser_pi1_multisort
 *   73:     function __construct($parentObj)
 *  103:     function multisort_rows()
 *  330:     function multisort_mm_children_list($rows)
 *  361:     function multisort_mm_children( $rows )
 *  490:     function multisort_mm_children_single($rows)
 *
 *              SECTION: Helper
 *  856:     function multisort_upto_6_level($arr_multisort)
 *  949:     function multisort_rows_upto_6_level($arr_multisort, $rows)
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_multisort
{








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



















  /**
   * multisort_rows( ): Order the rows depending on csvOrderBy and piVars[sort]
   *
   * @param	array		&$array: Reference to the array with the rows
   * @return	void
   * @version 4.1.13
   */
  function multisort_rows( )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;
    $conf_view = $this->pObj->conf_view;

      // 120329, dwildt+
      // RETURN : rows should ordered randomly
    if( $conf_view['random'] == 1 )
    {
      return;
    }
      // RETURN : rows should ordered randomly
      // 120329, dwildt+

    $viewWiDot = $view.'.';

    $conf_view    = $conf['views.'][$viewWiDot][$mode.'.'];
    $b_synonym    = $conf_view['functions.']['synonym'];

    $args                 = false;
    $arr_usedTableFields  = array();  //:todo: Wird nicht gefuellt
    $csvOrderBy           = $this->pObj->objSqlAut_3x->orderBy();
    $arrOrderByWiAscDesc  = $this->pObj->objZz->getCSVasArray($csvOrderBy);
    $csvOrderByWoAscDesc  = $this->pObj->objSqlFun_3x->get_orderBy_tableFields($csvOrderBy);
    $arrOrderByWoAscDesc  = $this->pObj->objZz->getCSVasArray($csvOrderByWoAscDesc);
    $rows                 = $this->pObj->rows;
//var_dump(__METHOD__ . ': ' . __LINE__, $rows);



      /////////////////////////////////////////////////////////////////
      //
      // RETURN if there isn't any row

    if( ! is_array( $rows ) )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Abort multisort_rows(). There isn\'t any row.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN if there isn't any row



      /////////////////////////////////////////////////////////////////
      //
      // RETURN if there isn't any orderBy array

    if( ! is_array( $arrOrderByWoAscDesc ) )
    {
      if ($this->pObj->b_drs_sql)
      {
        $prompt = 'Abort multisort_rows(). There is no orderBy clause.';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN if there isn't any orderBy array
//$this->pObj->dev_var_dump( $rows, $arrOrderByWoAscDesc );


      /////////////////////////////////////////////////////////////////
      //
      // RETURN if we have synonyms

    if( $b_synonym )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Abort multisort_rows(). Abort multisort_rows(). No ORDER BY here - we have synonyms!';
        t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN if we have synonyms



      /////////////////////////////////////////////////////////////////
      //
      // Remove keys, which aren't existing

    reset( $rows );
    $firstKey             = key( $rows );
      // #i0006, dwildt, 1-
//    $arr_rmKeys           = array_diff( $arrOrderByWoAscDesc, array_keys($rows[$firstKey] ) );
      // #i0006, dwildt, 1+
    $arr_rmKeys           = array_diff( ( array ) $arrOrderByWoAscDesc, ( array ) array_keys( $rows[$firstKey] ) );
    $arrOrderByWoAscDesc  = array_flip( ( array ) $arrOrderByWoAscDesc );
    foreach( ( array ) $arr_rmKeys as $key )
    {
      unset($arrOrderByWoAscDesc[$key]);
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] \''.$key.'\' isn\'t any element in the current row!', $this->pObj->extKey, 3);
        t3lib_div::devlog('[WARN/SQL] Maybe the order of the rows won\'t be proper.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/SQL] Please take care for a proper orderBy statement.', $this->pObj->extKey, 1);
      }
    }
    $arrOrderByWoAscDesc = array_flip($arrOrderByWoAscDesc);
      // Remove keys, which aren't existing



    /////////////////////////////////////////////////////////////////
    //
    // Building arguments for array_multisort - Part I

    $int_count = 0;
    foreach((array) $arrOrderByWiAscDesc as $key => $strOrderByField)
    {
      if($int_count > 6)
      {
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/SQL] The order clause has more than seven items!<br />'.
            'value is: \''.$csvOrderBy.'\'.', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/SQL] Please reduce the amount of items in the order clause.', $this->pObj->extKey, 1);
        }
        break; // dwildt, 100915
      }
      // dwildt, 100915
      // Get SORT_DESC or SORT_ASC
      list($tableField, $order) = explode(' ', $strOrderByField);

      if(!in_array($tableField, $arr_usedTableFields))
      {
        $args[$int_count]['table.field']    = $tableField;
        $args[$int_count]['int_orderFlag']  = $this->pObj->objSqlFun_3x->get_descOrAsc($strOrderByField);

        list($table, $field)  = explode('.', $tableField);

        // dwildt, 100915
        $arr_sortTypeAndCase = $this->pObj->objSqlFun_3x->get_sortTypeAndCase($table, $field);
        $args[$int_count]['int_typeFlag']   = $arr_sortTypeAndCase['int_typeFlag'];
        $args[$int_count]['caseSensitive']  = $arr_sortTypeAndCase['bool_caseSensitive'];
        // Get the typeFlag

        $int_count++;
      }
    }
    // Building arguments for array_multisort - Part I



    /////////////////////////////////////////////////////////////////
    //
    // Building arguments for array_multisort - Part II

    $i_count_args     = 0;
    $bool_drsWarnUtf8 = false; // 101009
    foreach((array) $args as $key => $arr_tableField_order)
    {
      $i_count_rows   = 0;
      foreach ($rows as $row => $elements)
      {
        if(!$arr_tableField_order['caseSensitive'])
        {
          $str_value = strtolower($rows[$row][$arr_tableField_order['table.field']]);
          if (!$bool_drsWarnUtf8 && $this->pObj->b_drs_warn)
          {
            t3lib_div::devlog('[WARN/UTF-8] multisort_rows() uses strtolower(). This is '.
              'UTF-8 insecure and multibyte insecure!', $this->pObj->extKey, 2);
            $bool_drsWarnUtf8 = true;
          }
        }
        if($arr_tableField_order['caseSensitive'])
        {
          $str_value = $rows[$row][$arr_tableField_order['table.field']];
        }
        $arr_multisort[$i_count_args]['table.field'][$i_count_rows] = $str_value;
        $i_count_rows++;
      }
      $arr_multisort[$i_count_args]['int_orderFlag'] = $arr_tableField_order['int_orderFlag'];
      $arr_multisort[$i_count_args]['int_typeFlag']  = $arr_tableField_order['int_typeFlag'];
      $i_count_args++;
    }
    // Building arguments for array_multisort - Part II



    /////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      $arr_constant[1] = 'SORT_NUMERIC';
      $arr_constant[2] = 'SORT_STRING';
      $arr_constant[3] = 'SORT_DESC';
      $arr_constant[4] = 'SORT_ASC';
      $str_prompt = 'array_multisort(<br />';
      foreach((array) $args as $arr_items)
      {
        $str_prompt = $str_prompt.'&nbsp;&nbsp;\''.$arr_items['table.field'].'\', '.$arr_constant[$arr_items['int_orderFlag']].', '.
                      $arr_constant[$arr_items['int_typeFlag']].', <br >';
      }
      $str_prompt = $str_prompt.');';
      t3lib_div::devlog('[INFO/SQL] Rows are ordered by PHP:<br />'.
        '<br />'.
        $str_prompt.'<br />'.
        '<br />'.
        'Level: '.(count($arr_multisort) - 1),
        $this->pObj->extKey, 0);
    }



    /////////////////////////////////////////////////////////////////
    //
    // Write the result to the global rows array

    // dwildt, 100915
    $arr_return = $this->multisort_rows_upto_6_level($arr_multisort, $rows);
    $rows       = $arr_return['rows'];

//var_dump(__METHOD__ . ': ' . __LINE__, $rows);
    $this->pObj->rows = $rows;
    // Write the result to the global rows array
  }









    /**
 * multisort_mm_children_list(): Order children elements depending on csvOrderBy.
 *
 * @param	array		$rows : current rows
 * @return	void
 * @internal  http://forge.typo3.org/issues/13803
 * @since     3.6.3
 * @version   3.6.3
 */
  function multisort_mm_children_list($rows)
  {
    return $rows;
  }

















    /**
 * multisort_mm_children(): Order children elements
 *                          Result is one row with ordered children elements.
 *                          It will be handled the field sorting only to date.
 *
 * @param	array		$row  : Current row
 * @return	array		$row  : Row with ordered childrens
 * @since     3.6.3
 * @version   3.6.3
 */
  function multisort_mm_children( $rows )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot          = $view.'.';
    $conf_view          = $conf['views.'][$viewWiDot][$mode.'.'];
    $conf_path          = 'views.' . $viewWiDot . $mode. '.';
    $conf_orderChildren = $conf_view['orderBy.'];



      /////////////////////////////////////////////////////////////////
      //
      // RETURN orderBy hasn't any elements

    if(empty($conf_orderChildren))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] ' . $conf_path . 'orderBy hasn\'t any element.' .
          'If you have children, they will be ordered randomly.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/SQL] See tutorial ... ', $this->pObj->extKey, 1);
      }
      return $rows;
    }
      // RETURN orderBy hasn't any elements



      /////////////////////////////////////////////////////////////////
      //
      // Get the children devider

    $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider            = $str_sqlDeviderDisplay.$str_sqlDeviderWorkflow;
      // Get the children devider


      /////////////////////////////////////////////////////////////////
      //
      // Loop: rows

    foreach($rows as $key_rows => $row)
    {
      $str_localTableUid = $rows[$key_rows][$this->pObj->arrLocalTable['uid']];
        // Loop: tsConf queries
      foreach($conf_orderChildren as $foreignTable => $foreignQuery)
      {
        $arr_queries[$foreignTable] = str_replace('###UID_LOCAL###', $str_localTableUid, $foreignQuery);
      }
        // Loop: tsConf queries

        // Loop: queries
      foreach($arr_queries as $foreignTable => $query)
      {
        $res    = $GLOBALS['TYPO3_DB']->sql_query($query);

          // EXIT: error!
        $error  = $GLOBALS['TYPO3_DB']->sql_error();
        if( $error )
        {
          $this->pObj->objSqlFun_3x->query = $query;
          $this->pObj->objSqlFun_3x->error = $error;
          $arr_result = $this->pObj->objSqlFun_3x->prompt_error( );
          $prompt     = $arr_result['error']['header'] . $arr_result['error']['prompt'];
          die( $prompt );
        }
//        if (!empty($error))
//        {
//          if ($this->pObj->b_drs_error)
//          {
//            $str_warn     = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' . $this->pObj->pi_getLL('drs_security') . '</p>';
//            $str_prompt   = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
//            $str_prompt  .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
//            echo $str_warn . $str_prompt;
//          }
//          else
//          {
//            echo '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' . $this->pObj->pi_getLL('drs_sql_prompt') . '</p>';
//          }
//          exit;
//        }
          // EXIT: error!

          // Loop: children
        $arr_ordered = null;
        while ($row_foreignTable = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
        {
          foreach($row_foreignTable as $key => $value)
          {
              // Get the ordered values as a string
            $arr_ordered[$foreignTable . '.' . $key][] = $value;
          }
        }
          // Loop: children

          // Loop: ordered values
        foreach((array) $arr_ordered as $foreign_tableField => $arr_values)
        {
          if(isset($rows[$key_rows][$foreign_tableField]))
          {
              // Set the ordered values
            $rows[$key_rows][$foreign_tableField] = implode($str_devider, $arr_values);
          }
        }
          // Loop: ordered values
      }
        // Loop: queries
    }
      // Loop: rows



    return $rows;
  }








    /**
 * multisort_mm_children_single(): Order children elements depending on csvOrderBy.
 *                                 Result is one row with ordered children elements.
 *                                 It will be handled the field sorting only to date.
 *
 * @param	array		current rows
 * @return	void
 * @internal  http://forge.typo3.org/issues/9727
 * @since     3.4.3
 * @version   3.6.3
 */
  function multisort_mm_children_single($rows)
  {
    $csvOrderBy           = $this->pObj->objSqlAut_3x->orderBy();
    $arrOrderByWiAscDesc  = $this->pObj->objZz->getCSVasArray($csvOrderBy);
    $rows                 = $this->pObj->rows;
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $rows);
//}



      /////////////////////////////////////////////////////////////////
      //
      // RETURN rows contain more than one row

    if(count($rows) > 1)
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/SQL] ABORTED multisort_mm_children() can handle one row only. '.
          'But rows contain more than one row!', $this->pObj->extKey, 2);
      }
      return;
    }
      // RETURN rows contain more than one row



      /////////////////////////////////////////////////////////////////
      //
      // Get the first key of the row

    reset($rows);
    $row_firstKey = key($rows);
      // Get the first key of the row



      /////////////////////////////////////////////////////////////////
      //
      // Get the children devider

    $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider            = $str_sqlDeviderDisplay.$str_sqlDeviderWorkflow;
      // Get the children devider



      /////////////////////////////////////////////////////////////////
      //
      // Get all mm relation tables and all foreign tables -  #9727
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $this->pObj->objSqlAut_3x->arr_relations_opposite);
//  var_dump(__METHOD__ . ': ' . __LINE__, $this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM']);
//}

    $arr_mm_tables = array();
      // fsander, 101023    -- initialize $arr_foreign_tables
    $arr_foreign_tables = array();
      // fsander, 101023    -- check if we have an array first
      // There is an relations_mm_simple_array
    if (is_array($this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM']))
    {
      if(is_array($this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM']))
      {
        foreach((array) $this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM'] as $arr_relation_tables)
        {
          foreach((array) $arr_relation_tables as $str_relation_table => $str_foreign_table)
          {
            $arr_mm_tables[$str_relation_table][]     = $str_foreign_table;
            $arr_foreign_tables[$str_foreign_table][] = $str_relation_table;
              // 13803, dwildt, 110313: non opposite
            $arrOrderByWiAscDesc[]                    = $str_relation_table . '.sorting';
//              // 13803, dwildt, 110313: opposite
//            $arrOrderByWiAscDesc[]                    = $str_relation_table . '.sorting_foreign';
            if ($this->pObj->b_drs_sql)
            {
              t3lib_div::devlog('[INFO/SQL] ' . $str_relation_table . '.sorting' .
                'is added to csvOrderBy temporarily.', $this->pObj->extKey, 0);
            }
              // 13803, dwildt, 110313
          }
        }
      }
    }
      // There is an relations_mm_simple_array
      // Get all mm relation tables and all foreign tables -  #9727

      // 13803, dwildt, 110313
      // Remove non unique values
    $arrOrderByWiAscDesc = array_unique($arrOrderByWiAscDesc);


// Wenn ...sorting existiert, und noch nicht Teil der SQL-Anweisung ist, dann anhängen
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $arr_mm_tables, $arr_foreign_tables);
//}
//$arrOrderByWiAscDesc = array_unique($arrOrderByWiAscDesc);
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//$arrOrderByWiAscDesc[] = 1;
//
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $arrOrderByWiAscDesc);
//}



      /////////////////////////////////////////////////////////////////
      //
      // Cut $arrOrderByWiAscDesc after the sixth element

      // Our multisort handles six array at maximum
    $max_elements = 6;
    if(count($arrOrderByWiAscDesc) > $max_elements)
    {
      $strOrderByWiAscDesc = implode(', ', $arrOrderByWiAscDesc);
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/SQL] The order clause has more than six items!<br />'.
          'value is: \''.$strOrderByWiAscDesc.'\'.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/SQL] Order clause will reduced to six items.', $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
      $count_elements = 0;
      foreach($arrOrderByWiAscDesc as $key => $value)
      {
        if($count_elements >= $max_elements)
        {
          unset($arrOrderByWiAscDesc[$key]);
        }
        $count_elements++;
      }
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_warn)
      {
        $strOrderByWiAscDesc = implode(', ', $arrOrderByWiAscDesc);
        t3lib_div::devlog('[INFO/SQL] Order clause after reducing: '.
          '\''.$strOrderByWiAscDesc.'\'.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/SQL] Please reduce the amount of items in the order clause manually.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System
    }
      // Cut $arrOrderByWiAscDesc after the sixth element
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $arrOrderByWiAscDesc);
//}



      /////////////////////////////////////////////////////////////////
      //
      // Get arrays for ordering with multisort

    $arr_check_elements = null;
    foreach((array) $arrOrderByWiAscDesc as $tableFieldOrder)
    {
        // Get table and field
      list($table, $fieldOrder) = explode('.', $tableFieldOrder);
      list($field, $order)      = explode(' ', $fieldOrder);
        // Get table and field

        // Set dest_table depending on mm table
      $bool_children_table  = false;
      $dest_table           = $table;
      if(in_array($table, array_keys($arr_foreign_tables)))
      {
        $bool_children_table  = true;
      }
      if(in_array($table, array_keys($arr_mm_tables)))
      {
        $bool_children_table  = true;
        $dest_table           = $arr_mm_tables[$table][0];
      }
        // Set dest_table depending on mm table

        // Get sort type and case sensitive
      $arr_sortTypeAndCase    = $this->pObj->objSqlFun_3x->get_sortTypeAndCase($table, $field);
        // Get sort type and case sensitive

        // Get array order for children table
      if($bool_children_table)
      {
          // Set table counter
        if(empty($arr_order[$table]))
        {
          $i_counter_table = 0;
        }
        if(!empty($arr_order[$table]))
        {
          $i_counter_table = count($arr_order[$table]);
        }
          // Set table counter

          // Get values
          // 101012, dwildt
        $arr_tmp = null;
        if($str_devider)
        {
          $arr_tmp = explode($str_devider, $rows[$row_firstKey][$table.'.'.$field]);
        }
          #9872
        if(!empty($arr_tmp))
        {
          $arr_order[$dest_table][$i_counter_table][$table.'.'.$field] = $arr_tmp;
        }
          // Get values

          // Generate one multisort array
          #9872
        if(!empty($arr_order))
        {
          //var_dump('sqlFun 1160', $dest_table, $i_counter_table, $table.'.'.$field, $arr_order, count($arr_order[$dest_table][$i_counter_table][$table.'.'.$field]));
          $arr_check_elements[$dest_table][$i_counter_table]         = count($arr_order[$dest_table][$i_counter_table][$table.'.'.$field]);
          $arr_order[$dest_table][$i_counter_table]['int_orderFlag'] = $this->pObj->objSqlFun_3x->get_descOrAsc($tableFieldOrder);
          $arr_order[$dest_table][$i_counter_table]['int_typeFlag']  = $arr_sortTypeAndCase['int_typeFlag'];
            // Generate one multisort array

            // Workaround: Warn, because of case sensitive only
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devlog('[WARN/SQL] multisort_mm_children() sort case sensitive only!',
              $this->pObj->extKey, 2);
          }
            // Workaround: Warn, because of case sensitive only
        }
      }
        // Get array order for children table
    }
      // Get arrays for ordering with multisort



      // RETURN there isn't any children record for ordering
    if(empty($arr_order))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] There isn\'t any children record for ordering.', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN there isn't any children record for ordering



//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $arr_check_elements, $arr_order);
//}



      /////////////////////////////////////////////////////////////////
      //
      // Warn, if multisort arrays have different numbers of elements

      // 101012, dwildt
    if(is_array($arr_check_elements))
    {
      foreach((array) $arr_check_elements as $dest_table => $arr_curr_elements)
      {
        $i_first_elements = $arr_curr_elements[0];
        foreach((array) $arr_curr_elements as $i_curr_elements)
        {
          if($i_first_elements != $i_curr_elements)
          {
            if ($this->pObj->b_drs_error)
            {
              t3lib_div::devlog('[WARN/SQL] multisort_mm_children(): multisort arrays for table '.$table .' '.
                'have different number of elements. Multisort won\'t work probably!',
                $this->pObj->extKey, 2);
              t3lib_div::devlog('[INFO/SQL] multisort_mm_children(): Maybe your MM table for '.$table .' '.
                'cointains waste records. Please delete waste records from your MM table!',
                $this->pObj->extKey, 0);
            }
          }
        }
      }
    }
      // Warn, if multisort arrays have different numbers of elements



      /////////////////////////////////////////////////////////////////
      //
      // Multisort

      // 101012, dwildt
    if(is_array($arr_order))
    {
      foreach((array) $arr_order as $table => $arr_field_order)
      {
        $arr_field_order = $this->multisort_upto_6_level($arr_field_order);
        foreach((array) $arr_field_order as $key => $arr_multisort)
        {
          reset($arr_multisort);
          $tableField = key($arr_multisort);
          $rows[$row_firstKey][$tableField] = implode($str_devider, $arr_multisort[$tableField]);
        }
      }
    }

    $this->pObj->rows = $rows;
//if($this->pObj->cObj->data['uid'] == 23)
//{
//  var_dump(__METHOD__ . ': ' . __LINE__, $rows);
//}
    return;
      // Multisort
//var_dump(__METHOD__ . ': ' . __LINE__, $rows);
//
//    return $rows;
  }





















    /***********************************************
    *
    * Helper
    *
    **********************************************/







/**
 * multisort_upto_6_level: multisort upto 6 arrays
 *
 * @param	array		$arr_multisort  : array with elements (arrays) for multisort
 * @return	array		$arr_multisort  : ordered
 * @since   3.4.3
 * @version 3.4.3
 */
  function multisort_upto_6_level($arr_multisort)
  {
    /////////////////////////////////////////////////////////////////
    //
    // Process array_multisort

    if((count($arr_multisort) -1 ) == 0)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) == 1)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) == 2)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2][key($arr_multisort[2])], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) == 3)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2][key($arr_multisort[2])], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3][key($arr_multisort[3])], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) == 4)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2][key($arr_multisort[2])], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3][key($arr_multisort[3])], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4][key($arr_multisort[4])], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) == 5)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2][key($arr_multisort[2])], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3][key($arr_multisort[3])], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4][key($arr_multisort[4])], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag'],
        $arr_multisort[5][key($arr_multisort[5])], $arr_multisort[5]['int_orderFlag'], $arr_multisort[5]['int_typeFlag']
      );
    }
    if((count($arr_multisort) -1 ) > 5)
    {
      array_multisort(
        $arr_multisort[0][key($arr_multisort[0])], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1][key($arr_multisort[1])], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2][key($arr_multisort[2])], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3][key($arr_multisort[3])], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4][key($arr_multisort[4])], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag'],
        $arr_multisort[5][key($arr_multisort[5])], $arr_multisort[5]['int_orderFlag'], $arr_multisort[5]['int_typeFlag'],
        $arr_multisort[6][key($arr_multisort[6])], $arr_multisort[6]['int_orderFlag'], $arr_multisort[6]['int_typeFlag']
      );
    }
    // Process array_multisort

    return $arr_multisort;
  }








/**
 * multisort_rows_upto_6_level: multisort rows with upto 6 arrays
 *
 *
 *                                  : [rows]          ordered
 *
 * @param	array		$arr_multisort  : array with elements (arrays) for multisort
 * @param	array		$rows           : Result of a database query
 * @return	array		$arr_return     : [arr_multisort] ordered
 * @since   3.4.3
 * @version 3.4.3
 */
  function multisort_rows_upto_6_level($arr_multisort, $rows)
  {
      // #i0006, dwildt, 3+
    $arr_return                   = array( );
    $arr_return['arr_multisort']  = $arr_multisort;
    $arr_return['rows']           = $rows;
    
      // #i0006, dwildt, 6+
      // RETURN : $arr_multisort isn't an array
    if( ! is_array( $arr_multisort ) )
    {
      return $arr_return;
    }
      // RETURN : $arr_multisort isn't an array

    
    /////////////////////////////////////////////////////////////////
    //
    // Process array_multisort

    if((count($arr_multisort) -1 ) == 0)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) == 1)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) == 2)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2]['table.field'], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) == 3)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2]['table.field'], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3]['table.field'], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) == 4)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2]['table.field'], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3]['table.field'], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4]['table.field'], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) == 5)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2]['table.field'], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3]['table.field'], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4]['table.field'], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag'],
        $arr_multisort[5]['table.field'], $arr_multisort[5]['int_orderFlag'], $arr_multisort[5]['int_typeFlag'],
        $rows
      );
    }
    if((count($arr_multisort) -1 ) > 5)
    {
      array_multisort(
        $arr_multisort[0]['table.field'], $arr_multisort[0]['int_orderFlag'], $arr_multisort[0]['int_typeFlag'],
        $arr_multisort[1]['table.field'], $arr_multisort[1]['int_orderFlag'], $arr_multisort[1]['int_typeFlag'],
        $arr_multisort[2]['table.field'], $arr_multisort[2]['int_orderFlag'], $arr_multisort[2]['int_typeFlag'],
        $arr_multisort[3]['table.field'], $arr_multisort[3]['int_orderFlag'], $arr_multisort[3]['int_typeFlag'],
        $arr_multisort[4]['table.field'], $arr_multisort[4]['int_orderFlag'], $arr_multisort[4]['int_typeFlag'],
        $arr_multisort[5]['table.field'], $arr_multisort[5]['int_orderFlag'], $arr_multisort[5]['int_typeFlag'],
        $arr_multisort[6]['table.field'], $arr_multisort[6]['int_orderFlag'], $arr_multisort[6]['int_typeFlag'],
        $rows
      );
    }
    // Process array_multisort

    $arr_return['arr_multisort']  = $arr_multisort;
    $arr_return['rows']           = $rows;

    return $arr_return;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_multisort.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_multisort.php']);
}

?>