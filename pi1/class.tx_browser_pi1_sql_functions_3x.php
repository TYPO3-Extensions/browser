<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_sql_functions_3x bundles methods with a workflow for sql queries and rows.
* statement with a FROM and a WHERE clause and maybe with the array JOINS.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @version   3.9.9
* @since  2.0.0
*
* @package    TYPO3
* @subpackage  browser
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   86: class tx_browser_pi1_sql_functions_3x
 *  106:     function __construct($parentObj)
 *
 *              SECTION: Synonyms
 *  144:     function query_with_synonyms($arr_data)
 *  345:     function rows_with_synonyms($rows)
 *
 *              SECTION: Order rows
 *  534:     function orderBy_by_piVar()
 *  599:     function make_hierarchical($rows)
 *  624:     function init_hierarchical()
 *  682:     function order_and_addLevel_recurs($rows, $pid = NULL)
 *  758:     function wrap_and_rmLevel($rows)
 *
 *              SECTION: Clean up
 *  829:     function rows_with_cleaned_up_fields($rows)
 *  947:     function replace_statement($str_queryPart)
 *  986:     function clean_up_as_and_alias($arr_tablefields)
 * 1047:     function replace_tablealias($arr_aliastableField)
 * 1101:     function set_tablealias($tableField)
 * 1161:     function get_sql_alias_before($str_tablefield)
 * 1179:     function get_sql_alias_behind($str_tablefield)
 * 1198:     function get_sql_alias_behind_or_before($str_tablefield, $b_before_the_as)
 * 1261:     function get_propper_andWhere($str_andWhere)
 * 1469:     function get_orderBy_tableFields($csvOrderBy)
 * 1519:     function get_descOrAsc($strOrderByField)
 * 1591:     function get_sortTypeAndCase($table, $field)
 * 1773:     function human_readable($str_query)
 *
 *              SECTION: Globals
 * 1827:     function global_all( )
 * 1891:     function global_csvSelect()
 * 2015:     function global_csvSearch( )
 * 2076:     function global_csvOrderBy()
 * 2231:     function global_stdWrap($str_tsProperty, $str_tsValue, $arr_tsArray)
 *
 *              SECTION: Helpers
 * 2362:     public function prompt_error( )
 *
 * TOTAL FUNCTIONS: 27
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_functions_3x
{
    // [String] SQL error message
  var $error = null;
    // [String] SQL query
  var $query = null;








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
    * Synonyms
    *
    **********************************************/



    /**
 * Replace synonyms with realnames in a query. Build a UNION query.
 *
 * @param	array		$arr_data: With the current SQL query parts
 * @return	array		Returns the delivered data array. The array is extended with a union query and an synonyms array in case of synonyms.
 */
    function query_with_synonyms($arr_data)
    {

      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';

      $conf_view    = $conf['views.'][$viewWiDot][$mode.'.'];
      $b_synonym    = $conf_view['functions.']['synonym'];
      $conf_synonym = $conf_view['functions.']['synonym.'];

      $arr_return   = $arr_data;


      /////////////////////////////////////////////////////////////////
      //
      // Should synonyms processed?

      if (!$b_synonym)
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] ...functions.synonym is false.', $this->pObj->extKey, 0);
        }
        return $arr_return;
      }


      /////////////////////////////////////////////////////////////////
      //
      // Synonyms should processed

      $str_synonym_for    = $conf_synonym['for.']['table_field'];
      $str_synonym_value  = $conf_synonym['synonym_value'];
      $str_synonym_alias  = $conf_synonym['synonym_alias'];
      $csv_synonyms       = $conf_synonym['csvTableFields'];
      $str_synonym_where  = $conf_synonym['andWhere'];
      $str_synonym_where  = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_synonym_where);

      $arr_synonyms       = $this->pObj->objZz->getCSVasArray($csv_synonyms);
      if (count($arr_synonyms) > 0)
      {
        // We have an array with synonyms, we have one synonym at least
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] ... functions.synonym.csvTableFields has items.', $this->pObj->extKey, 0);
        }

        // The first element has the default query parts
        foreach ($arr_data['data'] as $str_data_key => $str_data_value)
        {
          switch($str_data_key)
          {
            case('query'):
              // Do nothing
              break;
            default:
              if ($str_data_value != '')
              {
                $str_data_value = str_replace($str_synonym_value, $str_synonym_for, $str_data_value);


                /////////////////////////////////////////////////////////////////
                //
                // Replace Markers for pidlist and uid

                $str_pid_list = $this->pObj->pidList;
                $str_pid_list = str_replace(',', ', ', $str_pid_list);
                // For human readable
                $str_data_value = str_replace('###PID_LIST###', $str_pid_list,                  $str_data_value);
                $str_data_value = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_data_value);
              }
              $arr_data_synonyms[0][$str_data_key] = $str_data_value;
          }
          if ($str_data_key == 'select')
          {
            $arr_data_synonyms[0][$str_data_key] = $str_data_value.", ".$str_synonym_for." AS '".$str_synonym_value."'";
          }
        }
        // The first element has the default query parts

        // Query parts for synonyms
        foreach ($arr_synonyms as $i_key => $str_synonym)
        {
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] '.$str_synonym.' will get a UNION.', $this->pObj->extKey, 0);
          }
          foreach ($arr_data['data'] as $str_data_key => $str_data_value)
          {
            /////////////////////////////////////////////////////////////////
            //
            // Replace Markers for pidlist and uid

            $str_pid_list = $this->pObj->pidList;
            $str_pid_list = str_replace(',', ', ', $str_pid_list);
            // For human readable
            $str_data_value = str_replace('###PID_LIST###', $str_pid_list,                  $str_data_value);
            $str_data_value = str_replace('###UID###',      $this->pObj->piVars['showUid'], $str_data_value);

            switch ($str_data_key)
            {
              case('query'):
                // Do nothing
                break;
              case('where'):
                $str_data_value = $str_data_value." ".$str_synonym_where;
                $str_data_value = str_replace($str_synonym_alias, $str_synonym, $str_data_value);
                // break;
                // No break, process the default case too!
              default:
                $str_data_value = str_replace($str_synonym_for,   $str_synonym,     $str_data_value);
                $str_data_value = str_replace($str_synonym_value, $str_synonym,     $str_data_value);
                $str_data_value = str_replace($str_synonym_alias, 'synonym_alias',  $str_data_value);
                $arr_data_synonyms[$i_key + 1][$str_data_key] = $str_data_value;
            }
            if ($str_data_key == 'select')
            {
              $arr_data_synonyms[$i_key + 1][$str_data_key] = $str_data_value.", ".$str_synonym_for." AS '".$str_synonym_value."'";
            }
          }
        }
        // Query parts for synonyms
      }
      $arr_return['data']['synonyms'] = $arr_data_synonyms;
      unset($arr_data_synonyms);


      /////////////////////////////////////////////////////////////////
      //
      // UNION query

      foreach ($arr_return['data']['synonyms'] as $i_key => $arr_values)
      {
        $str_select   = $arr_return['data']['synonyms'][$i_key]['select'];
        $str_from     = $arr_return['data']['synonyms'][$i_key]['from'];
        $str_where    = $arr_return['data']['synonyms'][$i_key]['where'];
        $str_order_by = $arr_return['data']['synonyms'][$i_key]['orderBy'];
        $str_group_by = $arr_return['data']['synonyms'][$i_key]['groupBy'];
        $str_select   = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_select);
        $str_from     = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_from);
        $str_where    = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_where);
        $str_order_by = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_order_by);
        $str_group_by = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_group_by);
        if ($str_select)
        {
          $str_select = "\nSELECT ".$str_select;
        }
        if ($str_from)
        {
          $str_from = "\nFROM ".$str_from;
        }
        if ($str_where)
        {
          $str_where = "\nWHERE ".$str_where;
        }
        if ($str_order_by)
        {
          $str_order_by = "\nORDER BY ".$str_order_by;
        }
        if ($str_group_by)
        {
          $str_group_by = "\nGROUP BY ".$str_group_by;
        }
        $query       = $str_select.$str_from.$str_where.$str_order_by.$str_group_by;
        $arr_query[] = $query;
        $arr_return['data']['synonyms'][$i_key]['query'] = $query;
      }
      $str_union = implode(")\n UNION ALL \n(", $arr_query);
      unset($arr_query);

      if ($str_union)
      {
        $str_union = "( ".$str_union. " )";
      }
      $arr_return['data']['union'] = $str_union;
      // UNION query

      if ($this->pObj->b_drs_sql)
      {
        $i_elements = count($arr_return['data']['synonyms']);
        t3lib_div::devlog('[INFO/SQL] Array Synonyms has '.($i_elements - 1).' elements.', $this->pObj->extKey, 0);
      }

      return $arr_return;
    }







    /**
 * Extends synonyms with the real values and order it.
 *
 * @param	array		$rows: The current rows
 * @return	array		The return array with the elements error and data. Data contains the rows
 */
    function rows_with_synonyms($rows)
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      $str_ts_plugin_name   = 'plugin.'.$this->pObj->prefixId;
      // Typoscript plugin path (root) like: plugin.tx_browser_pi1

      $arr_wrap_realname    = explode('|', $conf_view['functions.']['synonym.']['for.']['noTrimWrap']);


      //////////////////////////////////////////
      //
      // RETURN, if we don't have any row

      $arr_return['data']['rows'] = $rows;
      if(!is_array($rows))
      {
        return $arr_return;
      }
      if(count($rows) < 1)
      {
        return $arr_return;
      }


      //////////////////////////////////////////
      //
      // Are synonyms activated?

      $b_synonym = $conf_view['functions.']['synonym'];
      if (!$b_synonym)
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] ...functions.synonym is deactivated.', $this->pObj->extKey, 0);
          $arr_return['data']['rows'] = $rows;
        }
        return $arr_return;
      }

      // Synonyms should processed
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] ...functions.synonym is activated.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SQL] Current $rows will be processed.', $this->pObj->extKey, 0);
      }


      //////////////////////////////////////////
      //
      // Get variables

      $str_alias_uid      = $conf_view['aliases.']['fields.']['uid'];                     // table_1.uid
      $str_alias_name     = $conf_view['functions.']['synonym.']['for.']['table_field'];  // table_1.or_name
      $csv_synonyms       = $conf_view['functions.']['synonym.']['csvTableFields'];       // table_1.or_synonym1, ...
      $str_synonym_value  = $conf_view['functions.']['synonym.']['synonym_value'];        // synonym_value
      $arr_synonyms       = $this->pObj->objZz->getCSVasArray($csv_synonyms);


      //////////////////////////////////////////
      //
      // Get the field name for the alias uid and the alias name

      $arr_alias_uid        = explode('.', $str_alias_uid);           // table_1.uid
      $str_field_uid        = trim($arr_alias_uid[1]);                // uid
      $str_alias_uid_table  = trim($arr_alias_uid[0]);                // table_1
      $str_table_uid        = $conf_view['aliases.']['tables.'][$str_alias_uid_table];
                                                                      // tx_civserv_organisation
      $arr_alias_name       = explode('.', $str_alias_name);          // table_1.or_name
      $str_field_name       = trim($arr_alias_name[1]);               // or_name
      $str_alias_name_table = trim($arr_alias_name[0]);               // table_1
      $str_table_name       = $conf_view['aliases.']['tables.'][$str_alias_name_table];
                                                                      // tx_civserv_organisation
      $str_row_synonym      = $str_table_name.'.synonym_alias';       // tx_civserv_organisation.synonym_alias
      $str_row_uid          = $str_table_uid.'.'.$str_field_uid;      // tx_civserv_organisation.uid
      $str_row_name         = $str_table_name.'.'.$str_field_name;    // tx_civserv_organisation.or_name


      //////////////////////////////////////////
      //
      // Extend synonyms with real names

      //$rows_synonyms = $rows;
      foreach ($rows as $key_row => $arr_row)
      {
        if ($arr_row[$str_row_synonym] != $str_alias_name)
        {
          // We have a synonym. I.e: value of $str_alias_name isn't the same as table_1.or_name
          $str_realname = $rows[$key_row][$str_synonym_value];  // I.e.: Adoption ohne Kind
          // Get the realname.
          $str_realname_wrapped = $arr_wrap_realname[1].$str_realname.$arr_wrap_realname[2];
                                                                  // I.e.: ( = Adoption ohne Kind)
          // Wrap it
          $rows[$key_row][$str_row_name] .= $str_realname_wrapped;
          // Add to the synonym the wrapped realname
          //$rows_synonyms[$key_row][$str_row_name] = $str_realname;
          //var_dump('sql_man 442', $key_row.': '.$rows[$key_row][$str_row_name].' /// '.$str_realname);
        }
      }


      //////////////////////////////////////////
      //
      // Process the piVar sort

      $b_desc = false;
      if ($this->pObj->piVars['sort'])
      {
        // I.e: tt_news.title:1
        $arr_sort = explode(':', $this->pObj->piVars['sort']);
        list($str_row_name, $b_desc) = $arr_sort;
        // We need $str_row_name and $b_desc local
        list($this->pObj->internal['orderBy'], $this->pObj->internal['descFlag']) = $arr_sort;
        // We need $this->... global

      }
      if ($b_desc)
      {
        $str_order = SORT_DESC;
      }
      else
      {
        $str_order = SORT_ASC;
      }
      // Process the piVar sort


      //////////////////////////////////////////
      //
      // Ordering the rows

      $i_counter = 0;
      foreach ($rows as $key_row => $arr_row)
      {
        $arr_multisort_uid[$i_counter]  = $rows[$key_row][$str_row_uid];
        $arr_multisort_name[$i_counter] = $rows[$key_row][$str_row_name];
        $i_counter++;
      }

      if ($str_row_uid == '.')
      {
        if ($this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/SQL] The field name of the row for ordering is a dot (\'.\')', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/DRS] Please contact the developer:<br />'.$this->pObj->developer_contact, $this->pObj->extKey, 1);
          t3lib_div::devlog('[INFO/SQL] Rows won\'t be ordered.', $this->pObj->extKey, 0);
        }
     }

      array_multisort($arr_multisort_name, $str_order, $arr_multisort_uid, SORT_ASC, $rows);
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] Rows will be ordered with the PHP method array_multisort('.$str_row_name.', SORT_ASC, '.$str_row_uid.', SORT_ASC, $rows)', $this->pObj->extKey, 0);
      }
      // Ordering rows

      $arr_return['data']['rows'] = $rows;

      return $arr_return;

    }










    /***********************************************
    *
    * Order rows
    *
    **********************************************/



  /**
   * Returns a SQL ORDER BY statement in case of the piVars[sort]
   *
   * @return	string		The ORDER BY statement
   */
  function orderBy_by_piVar()
  {
    $b_desc       = false;
    $str_order_by = false;


    //////////////////////////////////////////
    //
    // RETURN without any piVar[sort]

    if (!$this->pObj->piVars['sort'])
    {
      return false;
    }
    // RETURN without any piVar[sort]


    //////////////////////////////////////////
    //
    // Building the ORDER BY statement

    // I.e: tt_news.title:1
    $arr_sort = explode(':', $this->pObj->piVars['sort']);
    list($tablefield, $b_desc) = $arr_sort;
    // We need $tablefield and $b_desc local
    list($this->pObj->internal['orderBy'], $this->pObj->internal['descFlag']) = $arr_sort;
    // Set the alias-AS-alias syntax
    $tablefield = $this->set_tablealias($tablefield);
    // We need only the part infront of the AS
    $tablefield = $this->get_sql_alias_before($tablefield);

    if ($b_desc)
    {
      $str_order = ' DESC';
    }
    else
    {
      $str_order = ' ASC';
    }

    if ($tablefield)
    {
      $str_order_by = $tablefield.$str_order;
    }
    // Building the ORDER BY statement

    return $str_order_by;
  }











    /**
 * Order the records hierarchical
 *
 * @param	array		The current rows
 * @return	array		Hierarchical sorted rows extended with the field level
 */
    function make_hierarchical($rows)
    {
      $this->init_hierarchical();
      // Allocate TypoScript values to $this->arr_hierarch
      $rows = $this->order_and_addLevel_recurs($rows, $pid = NULL);
      $rows = $this->wrap_and_rmLevel($rows);

      return $rows;
    }











    /**
 * Allocates the hierarch array $this->arr_hierarch with some values from TypoScript
 *
 * @return	void
 */
    function init_hierarchical()
    {

      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      $arr_aliases_fields   = $conf_view['aliases.']['fields.'];
      $arr_civserv_hierarch = $conf_view['functions.']['hierarchical.'];


      //////////////////////////////////////////
      //
      // Get aliases without markers and the real names

      $str_alias_pid        = $arr_aliases_fields['pid'];           // table_2.pid
      $arr_alias_pid        = explode('.', $str_alias_pid);         // table_2.pid
      $str_field_pid        = trim($arr_alias_pid[1]);              // pid
      $str_alias_pid_table  = trim($arr_alias_pid[0]);              // table_2
      $str_table_pid        = $conf_view['aliases.']['tables.'][$str_alias_pid_table];
                                                                    // tx_civserv_organisation
      $str_alias_uid        = $arr_aliases_fields['uid'];           // table_1.uid
      $arr_alias_uid        = explode('.', $str_alias_uid);         // table_1.uid
      $str_field_uid        = trim($arr_alias_uid[1]);              // uid
      $str_alias_uid_table  = trim($arr_alias_uid[0]);              // table_1
      $str_table_uid        = $conf_view['aliases.']['tables.'][$str_alias_uid_table];
                                                                    // tx_civserv_organisation

      //////////////////////////////////////////
      //
      // Result $this->arr_hierarch

      $arr_hierarch['pid']    = $str_table_pid.'.'.$str_field_pid;  // tx_civserv_organisation.pid
      $arr_hierarch['uid']    = $str_table_uid.'.'.$str_field_uid;  // tx_civserv_organisation.uid
      $arr_hierarch['level']  = 'hierarch.level';                   // hierarch.level

      $arr_hierarch['wrap_tableField']  = $arr_civserv_hierarch['wrap_tableField'];
                                                                    // <span class="level_###LEVEL###">|</span>
      $arr_hierarch['order_tableField'] = $arr_civserv_hierarch['order_tableField'];
                                                                    // tx_civserv_organisation.or_name
      $arr_hierarch['display_root']     = $arr_civserv_hierarch['display_root'];        // 1 || 0

      $this->arr_hierarch = $arr_hierarch;

    }



    /**
 * Order the records hierarchical. This is a recursive method.
 *
 * @param	array		The current rows
 * @param	integer		The id of the organisation root record
 * @return	array		Hierarchical sorted rows extended with the field level
 */
    function order_and_addLevel_recurs($rows, $pid = NULL)
    {

      $str_row_pid    = $this->arr_hierarch['pid'];
      $str_row_uid    = $this->arr_hierarch['uid'];
      $str_row_level  = $this->arr_hierarch['level'];

      $key_ord_field  = $this->arr_hierarch['order_tableField'];


      //////////////////////////////////////////
      //
      // Static variables

      static $arr_rows_hierarch = array();
      // Result array with the hierarchical sorted organsiation records
      static $i_row             = 0;
      // Counter for the hierarchical array
      static $i_level           = 0;
      // Counter for the level of the current organisation record


      $i_child = 0;
      // Counter for the children in the next loop
      // Loop through all rows. Get any children if there are some
      foreach ($rows as $key_row => $row)
      {
        if ($row[$str_row_pid] == $pid)
        {
          // The record with the given pid has children
          $arr_children[$i_child] = $row;

          // Array for enable multisort
          foreach ($row as $key_element => $value_element)
          {
            if ($key_ord_field == $key_element)
            {
              $arr_sort[$key_ord_field][] = $value_element;
            }
          }
        }
        $i_child++;
      }
      // Loop through all rows. Get any children if there are some

      // Loop through all children rows. Get the children of the children if there are some (RECURSIVE !!!)
      if (is_array($arr_children)) {
        array_multisort($arr_sort[$key_ord_field], SORT_ASC, $arr_children);
        // 090316, dwildt: :todo: Sortierung (SORT_ASC) dynamisch
        foreach ($arr_children as $row_children)
        {
          $arr_rows_hierarch[$i_row] = $row_children;
          $arr_rows_hierarch[$i_row][$str_row_level] = $i_level;
          $i_level++;
          $i_row++;
          $this->order_and_addLevel_recurs($rows, $row_children[$str_row_uid]);
          $i_level --;
        }
        unset($arr_children);
        unset($arr_sort);
        $rows = $arr_rows_hierarch;
      }
      // Loop through all children rows. Get the children of the children if there are some (RECURSIVE !!!)

      return $rows;

    }



    /**
 * Wraps the order field and deletes the level row
 *
 * @param	array		The current rows
 * @return	array		Hierarchical sorted rows extended with the field level
 */
    function wrap_and_rmLevel($rows)
    {

      $key_ord_field    = $this->arr_hierarch['order_tableField'];  // tx_civserv_organisation.or_name
      $b_display_root   = $this->arr_hierarch['display_root'];      // 1 || 0
      $str_level_field  = $this->arr_hierarch['level'];             // hierarch.level

      $arr_wrap_item    = explode('|', $this->arr_hierarch['wrap_tableField']);
                                                                    // <span class="level_###LEVEL###">|</span>

      $i_last_level = -1;
      $i_curr_level = -1;
      if (!$b_display_root)
      {
        $i_last_level++;
        $i_curr_level++;
      }

      foreach ($rows as $key_row => $elements)
      {
        if (!$b_display_root && $elements[$str_level_field] === 0)
        {
          unset($rows[$key_row]);
        }
        else
        {
          $i_level        = $rows[$key_row][$str_level_field];  // 0, 1, 2, ...
          // Get the level
          $str_curr_value = $rows[$key_row][$key_ord_field];    // This is an item
          // Get the value
          $str_curr_value = $arr_wrap_item[0].$str_curr_value.$arr_wrap_item[1];
                                                                // <span class="level_###LEVEL###">This ...</span>
          $str_curr_value = str_replace('###LEVEL###', $i_level, $str_curr_value);
                                                                // <span class="level_1">This ...</span>
          $rows[$key_row][$key_ord_field] = $str_curr_value;
          // Store values in the row
          unset($rows[$key_row][$str_level_field]);
          // Delete the level element
        }
      }

      return $rows;

    }










    /***********************************************
    *
    * Clean up
    *
    **********************************************/






    /**
 * Delete the rows from the rows array, we don't want to display
 *
 * @param	array		The current rows
 * @return	array		The return array with the elements error and data. Data contains the rows
 */
    function rows_with_cleaned_up_fields($rows)
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      $csv_cleanup_fields   = $conf_view['functions.']['clean_up.']['csvTableFields'];
      // The table.fields as aliases, we want to delete from the rows
      $arr_table_aliases    = $conf_view['aliases.']['tables.'];

      $arr_return['data']['rows'] = $rows;


      //////////////////////////////////////////
      //
      // RETURN, if we don't have any row

      $arr_return['data']['rows'] = $rows;
      if(!is_array($rows))
      {
        return $arr_return;
      }
      if(count($rows) < 1)
      {
        return $arr_return;
      }


      /////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

      if (!$csv_cleanup_fields)
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] There isn\'t any manual configured field for clean up.',  $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/SQL] If you want clean up fields by your choice, please configure views.'.$viewWiDot.$mode.'.functions.clean_up.csvTableFields.',  $this->pObj->extKey, 1);
        }
      }


      /////////////////////////////////////////////////////////////////
      //
      // Clean up: Delete rows, we don't want to display

      $b_synonym = $conf_view['functions.']['synonym'];
      if ($b_synonym) {
        $str_synonym_value    = $conf_view['functions.']['synonym.']['synonym_value'];        // synonym_value
        if ($str_synonym_value) {
          $csv_cleanup_fields  .= ', '.$str_synonym_value;
        }
      }
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] List of fields, which should cleaned up (deleted from the SQL result): '.$csv_cleanup_fields,  $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/SQL] Change the list? Please configure: views.'.$viewWiDot.$mode.'.functions.clean_up.csvTableFields.',  $this->pObj->extKey, 1);
        if ($str_synonym_value) {
          t3lib_div::devlog('[HELP/SQL] And please configure synonyms: views.'.$viewWiDot.$mode.'.functions.synonym.synonym_value.',  $this->pObj->extKey, 1);
        }
      }
      if ($csv_cleanup_fields != '')
      {
        // There are clean up fields. Like: ###table_1###.###synonym_alias###, ...
        $arr_cleanup_fields = $this->pObj->objZz->getCSVasArray($csv_cleanup_fields);
        foreach ($arr_cleanup_fields as $str_table_field)
        {
          $str_table_field = trim($str_table_field);
          $str_table_field = str_replace('#', '', $str_table_field);  // table_1.synonym_alias
          // Get the table name without any markers
          $arr_table_field = explode ('.', $str_table_field);
          list($str_table_alias, $str_field) = $arr_table_field;
          $str_table = $arr_table_aliases[$str_table_alias];          // tx_civserv_service
          // Get the table real name
          $str_cleanup_row = $str_table.'.'.$str_field;               // tx_civserv_service.synonym_alias
          if ($str_cleanup_row == '.')
          {
            $str_cleanup_row = $str_table_field;
          }
          // Get the row name in the table.field syntax
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Clean up: '.$str_cleanup_row.'.',  $this->pObj->extKey, 0);
          }
          foreach ($rows as $key_row => $arr_row)
          {
            unset($rows[$key_row][$str_cleanup_row]);
          }
        }
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[HELP/SQL] If you want clean up other fields, please configure views.'.$viewWiDot.$mode.'.functions.clean_up.csvTableFields.',  $this->pObj->extKey, 1);
        }
      }

      $arr_return['data']['rows'] = $rows;

      return $arr_return;
    }










    /**
 * Replace a statement with an alias in a query part
 *
 * @param	string		$str_queryPart: The query part, in which should replaced a statement with an alias
 * @return	string		Returns the processed query part
 */
    function replace_statement($str_queryPart)
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      if (is_array($conf_view['select.']['deal_as_table.']))
      {
        foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
        {
          $statement      = $arr_dealastable['statement'];
          $alias          = $arr_dealastable['alias'];
          $str_queryPart  = str_replace($statement, $alias, $str_queryPart);
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Statement is replaced with alias.<br />
              <br />
              Statement: \"'.$statement.'\"<br />
              Alias:  \"'.$alias.'\"', $this->pObj->extKey, 0);
          }
        }
      }
      return $str_queryPart;
    }






    /**
 * Get the SQL part behind the AS. If this is an alias, replace the alias with the real name.
 *
 * @param	array		$arr_tablefields: Array with table.field values maybe with an AS
 * @return	array		$arr_tablefields with real names of table.fields
 */
    function clean_up_as_and_alias($arr_tablefields)
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      if( ! is_array( $arr_tablefields ) )
      {
        return $arr_tablefields;
      }

      foreach( ( array ) $arr_tablefields as $key => $value )
      {
          // #47700, 130430, dwildt
        if( $value === null )
        {
          continue;
        }
        
        //$value                 = $this->pObj->objZz->cleanCSV_from_lr_and_doubleSpace($value);
        if (is_array($conf_view['select.']['deal_as_table.']))
        {
          foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
          {
            $statement  = $arr_dealastable['statement'];
            $alias      = $arr_dealastable['alias'];
            $newvalue   = str_replace($statement, $alias, $value);
            if ($newvalue != $value)
            {
              if ($this->pObj->b_drs_sql)
              {
                t3lib_div::devlog('[INFO/SQL] Statement is replaced with alias.<br />
                  <br />
                  Statement: "'.$statement.'"<br />
                  Alias:  "'.$alias.'"', $this->pObj->extKey, 0);
              }
              $value = $newvalue;
            }
          }
        }
        $value                 = $this->get_sql_alias_behind($value);
        $arr_tablefields[$key] = $value;
      }
      $arr_tablefields = $this->replace_tablealias($arr_tablefields);
      return $arr_tablefields;
    }












/**
 * Replace table aliases in $arr_localtable. If there isn't any alias, than nothing will replaced.
 *
 * @param	array		$arr_aliastableField: Array with local table values
 * @return	array		$arr_aliastableField with replaced table aliases.
 */
    function replace_tablealias($arr_aliastableField)
    {
        // 120507, dwildt
      static $firstLoop = true;
      
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      /////////////////////////////////////////////////////////////////
      //
      // RETURN, if we don't have any alias array

      if (!is_array($conf_view['aliases.']['tables.']))
      {
        if ($this->pObj->b_drs_sql && $firstLoop )
        {
          t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' hasn\'t any array aliases.tables. We don\'t process aliases.', $this->pObj->extKey, 0);
        }
        $firstLoop = false;
        return $arr_aliastableField;
      }


      foreach ($arr_aliastableField as $key_field => $str_tablefield)
      {
        $arr_tablefield                   = explode ('.', trim( $str_tablefield ) );
        list($str_tablealias, $str_field) = $arr_tablefield;
        $str_tablereal                    = $conf_view['aliases.']['tables.'][$str_tablealias];
        if ($str_tablereal)
        {
          $arr_aliastableField[$key_field] = $str_tablereal.'.'.$str_field;
        }
      }

      $firstLoop = false;
      return $arr_aliastableField;
    }












        /**
 * Replace a table.field syntax with the alias-as-alias syntax
 *
 * @param	string		$tableField: table and filed in table.field syntax
 * @return	string		aliasTable.field as `aliastable.field`
 */
    function set_tablealias($tableField)
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      /////////////////////////////////////////////////////////////////
      //
      // RETURN, if we don't have any alias array

      $arr_aliases = $conf_view['aliases.']['tables.'];
      if (!is_array($arr_aliases))
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' hasn\'t any alias array: aliases.tables. We don\'t process aliases.', $this->pObj->extKey, 0);
        }
        return $tableField;
      }
      // RETURN, if we don't have any alias array


      /////////////////////////////////////////////////////////////////
      //
      // Catch the alias, if there is one. Build alias-AS-alias.

      list($table, $field) = explode('.', $tableField);
      $arr_aliases = array_flip($arr_aliases);

      $alias = $arr_aliases[$table];
      if($alias)
      {
        $tableField = $alias.'.'.$field.' AS `'.$alias.'.'.$field.'`';
      }
      // Catch the alias, if there is one. Build alias-AS-alias.

      return $tableField;
    }












    /**
 * If there is a SQL table.field with an AS, returns the string bebefore the AS
 *
 * @param	string		$str_tablefield: table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		Part before the AS. If there is no AS, it returns $str_tablefield
 */
    function get_sql_alias_before($str_tablefield)
    {
      return $this->get_sql_alias_behind_or_before($str_tablefield, true);
    }








    /**
 * If there is a SQL table.field with an AS, returns the string behind the AS
 *
 * @param	string		$str_tablefield: table.field with an AS like "news.uid AS 'news.uid'"
 * @return	string		Part behind the AS. If there is no AS, it returns $str_tablefield
 */
    function get_sql_alias_behind($str_tablefield)
    {
      return $this->get_sql_alias_behind_or_before($str_tablefield, false);
    }








    /**
 * If there is a SQL table.field with an AS, returns the string before or behind the AS
 *
 * @param	string		$str_tablefield: table.field with an AS like "news.uid AS 'news.uid'"
 * @param	boolean		$b_before_the_as: TRUE: return the string before the AS, FALSE: return the striong behind the AS
 * @return	string		Returns the string before or behind the AS. If there is no AS, it returns $str_tablefield
 */
    function get_sql_alias_behind_or_before($str_tablefield, $b_before_the_as)
    {


      /////////////////////////////////////////////////////////////////
      //
      // RETURN, if there isn't any AS

      $arr_tablefield = explode (' AS ', $str_tablefield);
      if(count($arr_tablefield) < 2)
      {
        return ($str_tablefield);
      }


      /////////////////////////////////////////////////////////////////
      //
      // Get the parts before and behind the AS, delete '

      list($str_before_as, $str_behind_as) = $arr_tablefield;


      /////////////////////////////////////////////////////////////////
      //
      // Return part before the AS, if $b_before_the_as is TRUE

      if ($b_before_the_as) {
        // We should return the part before the AS
        return $str_before_as;
      }


      /////////////////////////////////////////////////////////////////
      //
      // Delete the apostroph ('), return the part after the AS

      $str_behind_as = trim($str_behind_as);
      $str_behind_as = str_replace('\'', '', $str_behind_as);
      $str_behind_as = str_replace('`', '', $str_behind_as);

      return $str_behind_as;
    }















/**
 * Store the used tables and fields in the global $arr_realTables_arrFields. Add an AND at the top of the statement.
 *
 * @param	string		The andWhere statement
 * @return	array		The andWhere with an AND at the top, the used table.fields in the where clause
 */
    function get_propper_andWhere( $str_andWhere )
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

      // Array with the used tables and fields from the andWher clause in table.field syntax
      $arr_used_tableFields = false;

      $arr_return = false;

      /////////////////////////////////////////////////
      //
      // Understanding
      //
      // Important for understanding the risk of a non proper clause
      // 1. Proper clause:     `tt_news`.`image` != '' AND `tt_news_cat`.`title` LIKE '%A%'
      // 2. Proper clause:     1 == 1 AND `tt_news`.`image` != '' AND `tt_news_cat`.`title` LIKE '%A%'
      // 3. Proper clause:     '1' == 1 AND tt_news.uid == `tt_news_cat`.`uid`
      // 4. Proper clause:     1 == 1 AND `tt_news`.`uid` == `tt_news_cat`.`uid` AND 2 == 2 AND `tt_news`.`uid` = 1234
      // 5. Non proper clause: `tt_news`.`image` != '' AND tt.products price > 100.00
      // 6. Non proper clause: tx_ships_special.deleted=0 AND tx_ships_special.hidden=0 AND tx_ships_special.uid IN (2)



      /////////////////////////////////////////////////
      //
      // 1st: Mask all strings with dummy_string

      // Example $str_andWhere: `tt_news`.`image` != '..`A`k' AND tt_news_cat.uid = 10.00

      $arr_andWhere_withMaskedStrings = explode('\'', $str_andWhere);
      $firstString = substr($str_andWhere, 0, 1);
      if ($firstString == '\'') {
        $boolMaskOdd = true;
      }
      else
      {
        $boolMaskOdd = false;
      }
      foreach((array) $arr_andWhere_withMaskedStrings as $key => $value)
      {
        switch($key%2)
        {
          case(true):
            // We have an even array like [0], [2], [4]
            if (!$boolMaskOdd) $arr_andWhere_withMaskedStrings[$key] = 'dummy';
            break;
          case(false):
            // We have an odd array like [1], [3], [5]
            if ($boolMaskOdd) $arr_andWhere_withMaskedStrings[$key] = 'dummy';
            break;
        }
      }
      $tmpAndWhereClause = implode('\'', $arr_andWhere_withMaskedStrings);
      unset($arr_andWhere_withMaskedStrings);


      /////////////////////////////////////////////////
      //
      // 2nd: Get rid of `

      $arr_andWhere_withoutApostroph = explode('`', $tmpAndWhereClause);
      $tmpAndWhereClause = implode('', $arr_andWhere_withoutApostroph);
      unset($arr_andWhere_withoutApostroph);
      // Example $tmpAndWhereClause: tt_news.image != 'dummy' AND tt_news_cat.uid = 10.00


      /////////////////////////////////////////////////
      //
      // 3nd: EnableFields Patch. Replace '.deleted=0' and '.hidden=0' with '.deleted = 0' and '.hidden = 0'

      $tmpAndWhereClause = $this->human_readable($tmpAndWhereClause);


      /////////////////////////////////////////////////
      //
      // 4th: Look for any value.value syntax, maybe its a table.field
      //      In case it is, add it to the array of fetched tables

      $arr_andWhere_items = explode(' ', $tmpAndWhereClause);
      //var_dump($arr_andWhere_items);
      // array[0] = "fo.uid"
      // array[1] = "="
      // array[2] = "###UID###"
      // array[3] = "AND"
      // array[4] = "fo.pid"
      // array[5] = "IN"
      // array[6] = "(###PID_LIST###)"
      foreach((array) $arr_andWhere_items as $arr_items => $item)
      {
        // Do we have an item with one dot exactly?
        $arr_item_wi_dot = explode('.', $item);
        $boolTableField = false;
        if (count($arr_item_wi_dot) == 2)
        {
          #var_dump((string) $arr_item_wi_dot[0]);
          #var_dump((string) (int) $arr_item_wi_dot[0]);
          #var_dump((string) $arr_item_wi_dot[0] == (string) (int) $arr_item_wi_dot[0]);
          #var_dump($arr_item_wi_dot[0] == '0');
          #var_dump((string) $arr_item_wi_dot[0] == (string) (int) $arr_item_wi_dot[0] || $arr_item_wi_dot[0] == '0');
          /*
            string(7) "tt_news"
            string(1) "0"
            bool(false)
            bool(false)
            bool(false)
          */
          // We have one dot exactly
          switch(true)
          {
            case((string) $arr_item_wi_dot[0] == (string) (int) $arr_item_wi_dot[0] || $arr_item_wi_dot[0] == '0'):
              // We have a float value like 10.00, nothing to do
              break;
            default:
              // We have a format string.string and it seems, that it should to be an table.field definition
              $boolTableField = true;
              break;
          }
        }
        if( $boolTableField )
        {
          // We should have a table.field value
          $table = trim($arr_item_wi_dot[0]);
          $field = trim($arr_item_wi_dot[1]);
          if( $this->pObj->b_drs_sql )
          {
            t3lib_div::devlog
            (
                '[INFO/SQL] views.' . $viewWiDot . $mode 
              . ' has this table in the andWhere clause: '
              . $table
              , $this->pObj->extKey
              , 0
            );
          }


          //////////////////////////////////
          //
          // Replace aliases

          $arr_item_wi_dot = $this->replace_tablealias($arr_item_wi_dot);
          // var_dump($arr_item_wi_dot);
          // array[0] = "tx_civserv_form."
          // array[1] = "uid"
          if ($table != $arr_item_wi_dot[0])
          {
            $prompt_table = $table;
            // Table was an alias. The new name is returned with a dot
            $table = $arr_item_wi_dot[0];
            if (substr($table, -1) == '.')
            {
              // tx_civserv_services. becomes tx_civserv_services
              $table = substr($table, 0, -1);
            }
            $arr_item_wi_dot[0]             = $table;
            $arr_andWhere_items[$arr_items] = $arr_item_wi_dot[0].'.'.$arr_item_wi_dot[1];
            if ($this->pObj->b_drs_sql)
            {
              t3lib_div::devlog('[INFO/SQL] Alias of \''.$prompt_table.'\' is replaced with \''.$table.'\'', $this->pObj->extKey, 0);
            }
          }

          $arr_used_tableFields[] = $table.'.'.$field;

        }
      }

      $str_andWhere = implode(' ', $arr_andWhere_items);

      /////////////////////////////////////////////////
      //
      // Building the clause

      $str_andWhere = ' AND '.$str_andWhere;

      if ($this->pObj->b_drs_sql && $str_andWhere) t3lib_div::devlog('[INFO/SQL] andWhere clause is: '.$str_andWhere, $this->pObj->extKey, 0);


      $arr_used_tableFields = array_unique($arr_used_tableFields);
      $arr_return['data']['arr_used_tableFields'] = $arr_used_tableFields;
      $arr_return['data']['andWhere']             = $str_andWhere;

//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $arr_return );
//      die( );
//    }
      return $arr_return;
    }














    /**
 * get_orderBy_tableFields(): Get the table.fields from the order by property
 *
 * @param	string		$csv_orderBy: Get from an orderBy clause the table.fields only - without ASC or DESC
 * @return	string		$csv_orderByWoAscDesc: table.fields in CSV syntax - comma seperated without any space
 */
  function get_orderBy_tableFields( $csvOrderBy )
  {
      // #47700, 130430, dwildt
    $arrCsv     = explode( ',', $csvOrderBy );
    $arrCsv     = $this->clean_up_as_and_alias( $arrCsv );
    $csvOrderBy = implode( ',', $arrCsv );
//    $csvOrderBy = trim( $csvOrderBy, ',');

    ///////////////////////////////////////////////////////
    //
    // Clean up line feeds, carriage returns, double spaces

    $csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvOrderBy );
    // Clean up line feeds, carriage returns, double spaces


    ///////////////////////////////////////////////////////
    //
    // Remove ASC, DESC and spaces

    $csvOrderBy = str_ireplace( ' desc',  null, $csvOrderBy );
    $csvOrderBy = str_ireplace( ' asc',   null, $csvOrderBy );
    $csvOrderBy = str_replace(  ' ',      null, $csvOrderBy );
    // Remove ASC, DESC and spaces

    return $csvOrderBy;
  }














/**
 * get_descOrAsc: Should a field ordered by DESC or by ASC?
 *
 *
 *                                        Examples:
 *                                        - tt_news.title
 *                                        - tt_news.author DESC
 *                                        - tt_news_cat.title ASC
 *
 * @param	string		$strOrderByField  : Field with format table.field
 * @return	string		$str_order        : SORT_ASC or SORT_DESC
 * @since 3.4.3
 * @version 3.4.3
 */
  function get_descOrAsc($strOrderByField)
  {
    //////////////////////////////////////////////////////////////////////
    //
    // Get SORT_DESC or SORT_ASC

    // dwildt, 100915
    //$pos = strpos($strOrderByField, 'DESC');
    $pos = strpos($strOrderByField, ' DESC');
    if ($pos >= 0)
    {
      $str_order = SORT_DESC;
    }
    if ($pos === false)
    {
      $str_order = SORT_ASC;
    }
    // Get SORT_DESC or SORT_ASC



    //////////////////////////////////////////////////////////////////////
    //
    // Check syntax of orderBy field

    $arrOrderByField  = explode(' ', $strOrderByField);
    if(count($arrOrderByField) > 2)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] $strOrderByField has another syntax than \'table.field DESC\'!<br />'.
          'value is: \''.$strOrderByField.'\'<br />'.
          'array is<br />'.
          ' - '.implode('<br /> - ', $arrOrderByField).'<br />'.
          'Maybe there is more than one space between table.field and DESC.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/SQL] Please take care for a proper syntax.', $this->pObj->extKey, 1);
      }
      $str_order = SORT_ASC;
    }
    // Check syntax of orderBy field



    //////////////////////////////////////////////////////////////////////
    //
    // Return SORT_DESC or SORT_ASC

    return $str_order;
    // Return SORT_DESC or SORT_ASC
  }










    /**
 * get_sortTypeAndCase      : Type for ordering and boolean for case sensitive
 *
 *
 *                                [bool_caseSensitive]  true || false
 *
 * @param	string		$table      : the current table
 * @param	string		$field      : the current field
 * @return	array		$arr_return : [int_typeFlag]        SORT_STRING || SORT_NUMERIC
 * @since     3.4.3
 * @version   3.4.3
 */
  function get_sortTypeAndCase($table, $field)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];



    /////////////////////////////////////////////////////////////////
    //
    // Get global or local array advanced

    #10116
    $arr_conf_advanced = $conf['advanced.'];
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }
    // Get global or local array advanced



    /////////////////////////////////////////////////////////////////
    //
    // orderBy caseSensitive

    $bool_caseSensitive = $arr_conf_advanced['sql.']['orderBy.']['caseSensitive'];
    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] Alpha-numeric values will be ordered in lowercase.', $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SQL] Change it? Please configure advanced.sql.orderBy.caseSensitive.', $this->pObj->extKey, 0);
    }
    // orderBy caseSensitive



    /////////////////////////////////////////////////////////////////
    //
    // Default typeFlag and case

    $int_typeFlag      = SORT_STRING;
    $int_caseSensitive = $bool_caseSensitive;
    // Default typeFlag and case



    /////////////////////////////////////////////////////////////////
    //
    // Do we have a TCA configuration?

    if(is_array($GLOBALS['TCA'][$table]['columns'][$field]['config']))
    {
      $bool_tcaConfig = true;
    }
    if(!is_array($GLOBALS['TCA'][$table]['columns'][$field]['config']))
    {
      $bool_tcaConfig = false;
    }
    // Do we have a TCA configuration?



      /////////////////////////////////////////////////////////////////
      //
      // Set bool to false, if TCA type is none

      // #34966, dwildt+
    if( $bool_tcaConfig )
    {
      if( $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] == 'none' )
      {
        $bool_tcaConfig = false;
      }
    }
      // Set bool to false, if TCA type is none



    /////////////////////////////////////////////////////////////////
    //
    // Get the typeFlag out of the TCA

    if($bool_tcaConfig)
    {
      // Get TCA eval keys for SORT_NUMERIC
      $csv_evalFieldsSortnumeric = $arr_conf_advanced['php.']['multisort.']['eval.']['sort_numeric.']['tca.']['csv_sortNumeric'];
      $arr_evalFieldsSortnumeric = $this->pObj->objZz->getCSVasArray($csv_evalFieldsSortnumeric);
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_tca)
      {
        t3lib_div::devlog('[INFO/SQL+TCA] This TCA eval values will get the order type flag SORT_NUMERIC:<br />'.
          implode(',', $arr_evalFieldsSortnumeric).'.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/SQL+TCA] Change it? Please configure advanced.php.multisort.eval.sort_numeric.tca.csv_sortNumeric.', $this->pObj->extKey, 0);
      }
      // Get TCA eval keys for SORT_NUMERIC

      // Is eval field SORT_NUMERIC?
      $csv_tcaEvalField = $GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'];
      $arr_tcaEvalField = $this->pObj->objZz->getCSVasArray($csv_tcaEvalField);
      foreach((array) $arr_tcaEvalField as $str_eval)
      {
        if(in_array($str_eval, $arr_evalFieldsSortnumeric))
        {
          $int_typeFlag       = SORT_NUMERIC;
          $int_caseSensitive  = false;
          break;
        }
      }
      // Is eval field SORT_NUMERIC?
    }
    // Get the typeFlag out of the TCA



    /////////////////////////////////////////////////////////////////
    //
    // Get the typeFlag out of the database

    // 100429, dwildt: Evaluation with database. New from 3.2.2
    if(!$bool_tcaConfig)
    {
      // Get database eval keys for SORT_NUMERIC
      $csv_evalFieldsSortnumeric = $arr_conf_advanced['php.']['multisort.']['eval.']['sort_numeric.']['db.']['csv_sortNumeric'];
      $arr_evalFieldsSortnumeric = $this->pObj->objZz->getCSVasArray($csv_evalFieldsSortnumeric);
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] This database fields will get the order type flag SORT_NUMERIC:<br />'.
          implode(',', $arr_evalFieldsSortnumeric).'.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/SQL] Change it? Please configure advanced.php.multisort.eval.sort_numeric.db.csv_sortNumeric.', $this->pObj->extKey, 0);
      }
      // Get database eval keys for SORT_NUMERIC

      // Get field type from the database
      $select_fields = $field;
      $from_table    = $table;
      $where_clause  = false;
      $query         = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='0');
      $res           = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='0');
        // #13186, 120317, fconstien-
      //$str_eval      = $GLOBALS['TYPO3_DB']->sql_field_type($res, $field);
        // #13186, 120317, fconstien+
      $str_eval      = $GLOBALS['TYPO3_DB']->sql_field_type($res, 0);
      // Get field type from the database

      // Is eval field SORT_NUMERIC?
      if(in_array($str_eval, $arr_evalFieldsSortnumeric))
      {
        $int_typeFlag       = SORT_NUMERIC;
        $int_caseSensitive  = false;
      }
      // Is eval field SORT_NUMERIC?

    }
    // Get the typeFlag out of the database

    $arr_return['int_typeFlag']       = $int_typeFlag;
    $arr_return['bool_caseSensitive'] = $int_caseSensitive;

    return $arr_return;
  }














  /**
 * Insert space characters in SQL queries to enable line breaks for simplify the reading
 *
 * @param	string		$str_query: The SQL query
 * @return	string		$str_query: The SQL query with inserted space characters
 */
  function human_readable($str_query)
  {
      /////////////////////////////////////////////////
      //
      // 3nd: EnableFields Patch. Replace '.deleted=0' and '.hidden=0' with '.deleted = 0' and '.hidden = 0'

      $arr_query = explode('.deleted=0',   $str_query);
      $str_query = implode('.deleted = 0', $arr_query);
      $arr_query = explode('.hidden=0',    $str_query);
      $str_query = implode('.hidden = 0',  $arr_query);

      return $str_query;
      // :todo: dwildt, 090621: Replace further fields?
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
 * @version 3.9.9
 * @since   2.0.0
 */
    function global_all( )
    {
      $arr_return['error']['status'] = false;

        // Set the globals csvSelect and arrLocalTable
      $b_ok = $this->global_csvSelect( );
      if( ! $b_ok )
      {
        $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_sql_select').'</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the globals csvSelect and arrLocalTable

        // Set the global csvSearch
      $b_ok = $this->global_csvSearch( );
      if( ! $b_ok )
      {
        $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_sql_search').'</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the global csvSearch

        // Set the global csvOrderBy
      $b_ok = $this->global_csvOrderBy( );
      if( ! $b_ok )
      {
        $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_sql_orderby').'</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
      }
        // Set the global csvOrderBy

      return $arr_return;
    }







/**
 * Set the global csvSelect. Values are from the TypoScript select
 *
 * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
 */
    function global_csvSelect()
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      ///////////////////////////////////
      //
      // Get the SELECT statement

      // 3.3.7
      $this->pObj->csvSelect = $conf_view['select'];
      $this->pObj->csvSelect = $this->global_stdWrap('select', $this->pObj->csvSelect, $conf_view['select.']);
      $this->pObj->csvSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($this->pObj->csvSelect);

      if (!$this->pObj->csvSelect || $this->pObj->csvSelect == '')
      {
        if ($this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.' hasn\'t any select fields.', $this->pObj->extKey, 3);
          t3lib_div::devLog('[HELP/SQL] Did you included the static template from this extensions?', $this->pObj->extKey, 1);
          $tsArray = 'plugin.'.$this->pObj->prefixId.'.views.'.$viewWiDot.$mode.'.select';
          t3lib_div::devLog('[HELP/SQL] Did you configure '.$tsArray.'?', $this->pObj->extKey, 1);
          t3lib_div::devlog('[WARN/SQL] ABORTED', $this->pObj->extKey, 2);
        }
        return false;
      }
      // Get the SELECT statement


      //////////////////////////////////////////////////////////////////////
      //
      // Get the parts behind an AS, replace aliases with real names

      $csv_before_process = $this->pObj->csvSelect;
      $csv_after_process  = $this->replace_statement($csv_before_process);
      $arr_csv            = explode(',', $csv_after_process);
      $arr_csv            = $this->clean_up_as_and_alias($arr_csv);
      $csv_after_process = implode(', ', $arr_csv);
      // Get the parts behind an AS, replace aliases with real names


      //////////////////////////////////////////////////////////////////////
      //
      // RETURN in case of an error

      if (!$csv_after_process || $csv_after_process == '')
      {
        if ($this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/SQL] $csv_after_process is FALSE or is empty.', $this->pObj->extKey, 3);
          t3lib_div::devlog('[WARN/SQL] ABORTED', $this->pObj->extKey, 2);
        }
        return false;
      }


      //////////////////////////////////////////////////////////////////////
      //
      // Delete the first table.field, if it is the uid of the arrLocalTable

      // Init the global arrLocalTable, if it isn't inited
      if (!is_array($this->pObj->arrLocalTable))
      {
        $this->global_arrLocalTable();
      }

      $str_deleted_tablefield = false;
      $arr_tablefields = explode(',', $csv_after_process);
      if (trim($arr_tablefields[0]) == $this->pObj->arrLocalTable['uid'])
      {
        $str_deleted_tablefield = $arr_tablefields[0];
        unset($arr_tablefields[0]);
        foreach((array) $arr_tablefields as $key => $value)
        {
          $arr_tablefields[$key] = trim($value);
        }
        $csv_after_process = implode(', ', $arr_tablefields);
      }
      // Delete the first table.field, if it is the uid of the arrLocalTable


      ///////////////////////////////////
      //
      // DRS - Logging if user defined values were changed

      if ($csv_before_process != $csv_after_process)
      {
        $this->pObj->csvSelect = $csv_after_process;
        if ($this->pObj->b_drs_sql)
        {
          $prompt = 'Values for the global var csvSelect were changed.<br />
             Before changing:<br />
             '.$csv_before_process.'<br />
             After changing:<br />
             '.$csv_after_process;
          t3lib_div::devlog('[INFO/SQL] '.$prompt, $this->pObj->extKey, 0);
          if ($str_deleted_tablefield)
          {
            t3lib_div::devlog('[INFO/SQL] '.$str_deleted_tablefield.' is deleted, because it is the first field in the statement and the value of the localTable.uid.', $this->pObj->extKey, 0);
          }
        }
      }
      // DRS - Logging if user defined values were changed

      return true;
    }






/**
 * global_csvSearch( ): Set the global csvSearch. Values are from the TypoScript. If search is empty, search will get the values out of the select statement.
 *
 * @return	boolean		TRUE
 * @version 3.9.9
 * @since   2.0.0
 */
    function global_csvSearch( )
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      ///////////////////////////////////
      //
      // Get the SEARCH values

      // 3.3.7
      //$csvSearch  = $conf_view['search'];
      $csvSearch = $this->pObj->conf_sql['search'];
      $csvSearch = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );
//if(t3lib_div::_GP('dev')) var_dump('sqlFun 2716', $csvSearch);

      if ( ! $csvSearch )
      {
        // 3.3.7
        //$csvSearch = $conf_view['select'];
        $csvSearch = $this->pObj->conf_sql['select'];
        $csvSearch  = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );
        if ($this->pObj->b_drs_sql)
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
 * Set the global csvOrderBy. Values are from the TypoScript orderBy or select
 *
 * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
 */
    function global_csvOrderBy()
    {
      $conf = $this->pObj->conf;
      $mode = $this->pObj->piVar_mode;
      $view = $this->pObj->view;

      $viewWiDot = $view.'.';
      $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


      ///////////////////////////////////
      //
      // Get the override ORDER BY clause

      // 3.3.7
      $orderBy = $conf['views.'][$viewWiDot][$mode.'.']['override.']['orderBy'];
      $orderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($orderBy);
      if ($orderBy)
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.'.override.orderBy is: '.$orderBy, $this->pObj->extKey, 0);
          t3lib_div::devLog('[INFO/SQL] The system generated ORDER BY clause will be ignored!', $this->pObj->extKey, 0);
          t3lib_div::devLog('[INFO/SQL] ORDER BY '.$orderBy, $this->pObj->extKey, 0);
        }
      }
      // Get the override ORDER BY clause


      ///////////////////////////////////
      //
      // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error

      if (!$orderBy)
      {
        $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($conf_view['orderBy']);
        if (!$this->pObj->csvOrderBy || $this->pObj->csvOrderBy == '')
        {
          $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($conf_view['select']);
        }
        if (!$this->pObj->csvOrderBy || $this->pObj->csvOrderBy == '')
        {
          if ($this->pObj->b_drs_error)
          {
            t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.' hasn\'t any orderBy fields.', $this->pObj->extKey, 3);
            t3lib_div::devlog('[WARN/SQL] ABORTED', $this->pObj->extKey, 2);
          }
          return false;
        }
      }
      // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error


      ///////////////////////////////////
      //
      // Get the parts behind an AS, replace aliases with real names

      $csv_before_process = $this->pObj->csvOrderBy;
      $csv_before_process = $this->replace_statement($csv_before_process);
      $arr_csv            = explode(',', $csv_before_process);
      $arr_csv            = $this->clean_up_as_and_alias($arr_csv);
      $csv_before_process = implode(', ', $arr_csv);
//      $csv_after_process  = $this->replace_statement($csv_before_process);
//      $arr_csv            = explode(',', $csv_after_process);
//      $arr_csv            = $this->clean_up_as_and_alias($arr_csv);
//      $csv_after_process  = implode(', ', $arr_csv);
      $csv_after_process  = $csv_before_process;


      ///////////////////////////////////
      //
      // RETURN in case of an error

      if (!$csv_after_process || $csv_after_process == '')
      {
        if ($this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/SQL] $csv_after_process is FALSE or is empty.', $this->pObj->extKey, 3);
          t3lib_div::devlog('[WARN/SQL] ABORTED', $this->pObj->extKey, 2);
        }
        return false;
      }


      ///////////////////////////////////
      //
      // Delete the first table.field, if it is the uid of the arrLocalTable

      // Init the global arrLocalTable, if it isn't inited
      if (!is_array($this->pObj->arrLocalTable))
      {
        $this->global_arrLocalTable();
      }

      $str_deleted_tablefield = false;
      $arr_tablefields = explode(',', $csv_after_process);
      if (trim($arr_tablefields[0]) == $this->pObj->arrLocalTable['uid'])
      {
        $str_deleted_tablefield = $arr_tablefields[0];
        unset($arr_tablefields[0]);
        foreach((array) $arr_tablefields as $key => $value)
        {
          $arr_tablefields[$key] = trim($value);
        }
        $csv_after_process = implode(', ', $arr_tablefields);
      }


      ////////////////////////////////////////////////////////////////////
      //
      // DRS - Logging if user defined values were changed

      if ($csv_before_process != $csv_after_process)
      {
        $this->pObj->csvOrderBy = $csv_after_process;
        if ($this->pObj->b_drs_sql)
        {
          $prompt = 'Values for the global var csvOrderBy were changed.<br />
             Before changing:<br />
             '.$csv_before_process.'<br />
             After changing:<br />
             '.$csv_after_process;
          t3lib_div::devlog('[INFO/SQL] '.$prompt, $this->pObj->extKey, 0);
          if ($str_deleted_tablefield)
          {
            t3lib_div::devlog('[INFO/SQL] '.$str_deleted_tablefield.' is deleted, because it is the first field in the statement and the value of the localTable.uid.', $this->pObj->extKey, 0);
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
  function global_stdWrap($str_tsProperty, $str_tsValue, $arr_tsArray)
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $cObj = $this->pObj->cObj;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    $conf_path  = 'views.'.$viewWiDot.$mode.'.';



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN value, if there is no stdWrap configuration

//if(t3lib_div::_GP('dev')) var_dump('sqlFun 2320', $str_tsProperty.'.', $conf_view);

    if (!is_array($arr_tsArray))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$str_tsProperty.' hasn\'t any stdWrap.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/SQL] If you like to wrap it, please configure '.$conf_path.$str_tsProperty.'.value ...', $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
    // RETURN value, if there is no stdWrap configuration



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN value, if property isn't uppercase

    // Downwards  compatible:
    // upto 3.3.6 syntax was:
    //   select = tt_news.title, tt_news_cat.title
    // from 3.3.7 syntax should be:
    //   select = TEXT
    //   select {
    //     value = tt_news.title, tt_news_cat.title
    //     ...

    if ($str_tsValue != strtoupper($str_tsValue))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$str_tsValue.' doesn\'t seem to be a TypoScript object like TEXT or COA.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[WARN/SQL] There will be any wrap.', $this->pObj->extKey, 2);
        t3lib_div::devLog('[HELP/SQL] If you like to wrap it, please configure i.e. '.$conf_path.$str_tsProperty.' = TEXT and '.$conf_path.$str_tsProperty.'.value = your value', $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
    // RETURN value, if property isn't uppercase



    ////////////////////////////////////////////////////////////////////
    //
    // RETURN value, if property is empty

    if ($str_tsValue == false || $str_tsValue == '')
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$str_tsValue.' isn\'t any TypoScript object like TEXT or COA.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[WARN/SQL] There will be any wrap.', $this->pObj->extKey, 2);
        t3lib_div::devLog('[HELP/SQL] If you like to wrap it, please configure i.e. '.$conf_path.$str_tsProperty.' = TEXT and '.$conf_path.$str_tsProperty.'.value = your value', $this->pObj->extKey, 1);
      }
      return $str_tsValue;
    }
    // RETURN value, if property isn't uppercase



    $lConfCObj = false;
    $elements  = false;

      // #29198, 110824, dwildt-
//    $lConfCObj['10']  = $str_tsValue;
//    $lConfCObj['10.'] = $arr_tsArray;
//    $lConfCObj        = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $elements);
//    $lConfCObj        = $this->pObj->objZz->substitute_t3globals_recurs($lConfCObj);
      // #29198, 110824, dwildt-

      // #29198, 110824, dwildt-
//    $str_tsValue      = $this->pObj->objWrapper->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($lConfCObj, $ext=''), false);
      // #29198, 110824, dwildt+
    $str_tsValue = $this->pObj->cObj->cObjGetSingle($str_tsValue, $arr_tsArray);

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] '.$conf_path.$str_tsProperty.' is wrapped: '.$str_tsValue, $this->pObj->extKey, 0);
    }

    return $str_tsValue;
  }









  /***********************************************
  *
  * Helpers
  *
  **********************************************/









/**
 * prompt_error( ): Prompts a SQL error.
 *                  It is with the query in case of an enabled DRS.
 *
 * @return	array		$arr_return with elements for prompting
 * @version 3.9.8
 * @since   3.9.8
 */
  public function prompt_error( )
  {
    $query = $this->query;
    $error = $this->error;

    if( $this->pObj->b_drs_error )
    {
      $level      = 1; // 1 level up
      $debugTrail = $this->pObj->drs_debugTrail( $level );
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



























  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions_3x.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_functions_3x.php']);
  }

?>