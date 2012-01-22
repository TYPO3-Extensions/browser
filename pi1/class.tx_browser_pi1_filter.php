<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2009-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_filter bundles methods for rendering and processing filters and category menues
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.6
* @since 3.0.1
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   75: class tx_browser_pi1_filter
 *  101:     function __construct($pObj)
 *
 *              SECTION: Filter and
 *  128:     function filter($template)
 *  368:     function filterLoop($template)
 *
 *              SECTION: Little Helpers
 *  434:     function orderValues($arr_values, $conf_tableField)
 *  500:     function andWhere_filter()
 *  655:     function andWhere_localTable($obj_ts, $arr_ts, $arr_piVar, $tableField)
 *  738:     function andWhere_foreignTable($obj_ts, $arr_ts, $arr_piVar, $tableField)
 *  808:     function getRows($tableField)
 * 1021:     function wrapRows($arr_input)
 * 1139:     function get_nice_piVar($obj_ts, $arr_ts, $conf_tableField)
 *
 *              SECTION: Rendering TS objects
 * 1208:     function renderHtmlFilter($obj_ts, $arr_ts, $arr_values, $tableField)
 *
 *              SECTION: Rendering items
 * 1500:     function wrap_values_and_add_first_value($arr_ts, $arr_values, $tableField)
 * 1672:     function wrap_objectTitle($arr_ts, $conf_tableField)
 * 1752:     function get_wrappedItemClass($arr_ts, $conf_item, $str_order)
 * 1784:     function get_wrappedItemStyle($arr_ts, $conf_item, $str_order)
 * 1806:     function get_wrappedItemUid($uid, $conf_item)
 * 1842:     function get_wrappedItemURL($tableField, $value, $conf_item)
 * 1934:     function get_wrappedItemSelected($uid, $arr_piVar, $conf_selected, $conf_item)
 * 1956:     function wrap_allItems($obj_ts, $arr_ts, $str_nice_piVar, $key_piVar, $number_of_items)
 * 2020:     function get_tableFields()
 *
 * TOTAL FUNCTIONS: 20
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_filter {

    // [array] Array with all table.fields, which are configured in the TypoScript views.x.filter
  var $arr_conf_tableFields = null;

    // [array] Array with the current SQL after consolidation but before any limit
    //         Will be set from outside: @views
  var $rows_wo_limit = null;

    // [array] Hits per tablefield (filter) and item
  var $arr_hits = null;

    // [array] Rows of the SQL query after consolidation
  var $arr_rows = array( );
    // [array] Tables with a treeParentField field
  var $arr_tablesWiTreeparentfield  = array( );
    // [array] SQL rows of the current table.field
  var $arr_rowsTablefield           = array( );

    // [array] temporarily array for the recursive method setTreeOneDim( )
  var $tmpOneDim  = array( );










  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($pObj) {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Filter and
  *
  **********************************************/

  /**
 * filter():  Main function for handling filters and category menus.
 *            It returns the template with rendered filters and category menus.
 *            A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @param string    $template: current template
 * @return  array   The array with the template at least
 * @version 3.6.0
 */
  function filter($template)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    if(is_array($conf_view['displayList.']['display.']))
    {
      $lDisplay = $conf_view['displayList.']['display.'];
    }
    if(!is_array($conf_view['displayList.']['display.']))
    {
      $lDisplay = $conf['displayList.']['display.'];
    }



      /////////////////////////////////////////////////////////////////
      //
      // RETURN filters shouldn't processed

    $bool_display = $this->pObj->objFlexform->bool_searchForm;
    if ($bool_display == 0)
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_filter)
      {
        t3lib_div :: devlog('[INFO/FILTER] Searchform is deactivated. Filters won\'t processed.', $this->pObj->extKey, 0);
        t3lib_div :: devlog('[HELP/FILTER] If you want to use filters, please configure: ' . $viewWiDot . $mode . '.displayList.display.searchform = 1.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System
      // Clean up the template and return
      $template = $this->pObj->cObj->substituteSubpart($template, '###CATEGORY_MENU###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
        // Clean up the template and return
    }
      // RETURN filters shouldn't processed



      /////////////////////////////////////////////////////////////////
      //
      // RETURN we don't have any filter array

    if (!is_array($conf_view['filter.']))
    {
      if ($this->pObj->b_drs_filter)
      {
        t3lib_div :: devlog('[INFO/FILTER] ' . $viewWiDot . $mode . '.filters isn\'t an array. There isn\'t any filter for processing.', $this->pObj->extKey, 0);
      }
      // Clean up the template and return
      $template = $this->pObj->cObj->substituteSubpart($template, '###CATEGORY_MENU###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN we don't have any filter array



      /////////////////////////////////////////////////////////////////
      //
      // Set the global $arr_conf_tableFields

    if ( empty ($this->arr_conf_tableFields) )
    {
      $this->get_tableFields();
    }
      // Set the global $arr_conf_tableFields



      /////////////////////////////////////////////////////////////////
      //
      // Init area

      // dwildt, 101211, #11402
    $arr_dummy = $this->pObj->objCal->area_init(array());
      // Init area



      /////////////////////////////////////////////////////////////////
      //
      // Get the children devider

    $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider            = $str_sqlDeviderDisplay . $str_sqlDeviderWorkflow;
      // Get the children devider



      /////////////////////////////////////////////////////////////////
      //
      // Hits per filter item

    foreach ($this->arr_conf_tableFields as $tableField)
    {
      list ($table, $field) = explode('.', $tableField);
      if (is_array($this->rows_wo_limit))
      {
        foreach ($this->rows_wo_limit as $row_wo_limit)
        {
          $arr_uids = null;
          if ($str_devider)
          {
            $arr_uids = explode($str_devider, $row_wo_limit[$table . '.uid']);
          }
          if (is_array($arr_uids))
          {
            foreach ($arr_uids as $uid)
            {
              if(!empty($uid))
              {
                $this->arr_hits[$tableField][$uid]++;
                $this->arr_hits[$tableField]['sum']++;
              }
            }
          }
          if ($arr_uids == null)
          {
            $uid = $row_wo_limit[$table . '.uid'];
            if(!empty($uid))
            {
              $this->arr_hits[$tableField][$uid]++;
              $this->arr_hits[$tableField]['sum']++;
            }
          }
        }
      }
    }
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_warn) {
      if (empty ($this->arr_hits)) {
        t3lib_div :: devlog('[WARN/FILTER] Any filter item hasn\'t any hit!', $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System
      // Hits per filter item



      /////////////////////////////////////////////////////////////////
      //
      // Get the content for the filter marker

    $arr_result = $this->filterLoop($template);
    if ($arr_result['error']['status'])
    {
      return $arr_result;
    }
    $markerArray = $arr_result['data']['marker'];
// dwildt, 110309: Werte sind aus db! Falsch!
//var_dump(__METHOD__ . ': ' . __LINE__ , $markerArray);
    unset ($arr_result);
      // Get the content for the filter marker



      /////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($this->pObj->b_drs_filter)
    {
      $csv_markerKeys = implode(', ', array_keys($markerArray));
      t3lib_div :: devlog('[INFO/FILTER] This markers allocate HTML filter snippets:' .
      $csv_markerKeys, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System



      /////////////////////////////////////////////////////////////////
      //
      // Replace filters in the HTML template

    if (!is_array($markerArray))
    {
      $markerArray = array ();
    }

      // Filter can be a part of ###SEARCHFORM###
    $str_subpart  = $this->pObj->cObj->getSubpart($template, '###SEARCHFORM###');
    $str_subpart  = $this->pObj->cObj->substituteMarkerArray($str_subpart, $markerArray);
      // Add the subparts marker, because another method (the search template) need this subpart marker
    $str_subpart  = '<!-- ###SEARCHFORM### begin -->' . PHP_EOL . $str_subpart . '<!-- ###SEARCHFORM### end -->' . PHP_EOL;
    $template     = $this->pObj->cObj->substituteSubpart($template, '###SEARCHFORM###', $str_subpart, true);
      // Replace filters in the HTML template



      /////////////////////////////////////////////////////////////////
      //
      // Replace category menus in the HTML template

    $str_subpart  = $this->pObj->cObj->getSubpart($template, '###CATEGORY_MENU###');
    $str_subpart  = $this->pObj->cObj->substituteMarkerArray($str_subpart, $markerArray);

      // Remove ###CATEGORY_MENU### from HTML template
    if(!$lDisplay['category_menu'])
    {
      $str_subpart = null;
      if ($this->pObj->b_drs_warn)
      {
        $prompt = '###CATEGORY_MENU### is removed from HTML template because of TypoScript.';
        t3lib_div :: devlog('[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'If you like to use it, please configure local or global displayList.display.category_menu.';
        t3lib_div :: devlog('[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1);
      }
    }
      // Remove ###CATEGORY_MENU### from HTML template

    $template     = $this->pObj->cObj->substituteSubpart($template, '###CATEGORY_MENU###', $str_subpart, true);
      // Replace category menus in the HTML template



    $arr_return['data']['template'] = $template;
    return $arr_return;
  }










  /**
 * Loop through all filters, which are configured in TypoScript.
 * Configuration can be: view.list.x.filter.x.
 *
 * @param string    $template: the current template
 * @return  array   The array with the template at least
 * @version 3.5.0
 */
  function filterLoop($template)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    //3.5.0
    $arr_rows = null;



      /////////////////////////////////////////////////////////////////
      //
      // Get rows

      // LOOP get rows per table.field
    foreach ( $this->arr_conf_tableFields as $tableField )
    {
      $arr_result = $this->getRows($tableField);
      if ($arr_result['error']['status']) {
        return $arr_result;
      }
      $arr_rows[$tableField] = $arr_result['data']['rows'];
      unset ($arr_result);
    }
      // LOOP get rows per table.field
      // Get rows



      /////////////////////////////////////////////////////////////////
      //
      // Wrap rows

    $arr_input['data']['rows']      = $arr_rows;
    $arr_input['data']['template']  = $template;
    $arr_result = $this->wrapRows($arr_input);
    $marker     = $arr_result['data']['marker'];
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    unset( $arr_result );
      // Wrap rows



      // RETURN the result
    $arr_return['data']['marker'] = $marker;
    return $arr_return;
  }









  /***********************************************
  *
  * Little Helpers
  *
  **********************************************/









  /**
   * filterCondition( ):  Render the filter condition.
   *                      // #32117, 111127, dwildt+
   *
   * @param string      $tableField: table.field of the current filter
   * @param array       $arr_ts: typoScript array of the current filter
   * @return  boolen    True, if there isn't any condition or condition is meet. False, if it isn't.
   * @version 3.9.3
   * @since   3.9.3
   */
  function filterCondition( $tableField, $arr_ts )
  {
      /////////////////////////////////////////////////////////////////
      //
      // Default values

    $bool_condition = true;
      // Default values

    

      /////////////////////////////////////////////////////////////////
      //
      // RETURN true: any condition isn't defined

    if( ! ( isset ( $arr_ts['condition'] ) ) )
    {
      if ( $this->pObj->b_drs_filter )
      {
        $prompt = $tableField . ' hasn\'t any condition.';
        t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
      }
      return $bool_condition;
    }
      // RETURN true: any condition isn't defined



      /////////////////////////////////////////////////////////////////
      //
      // Get condition result

    $coa_name   = $arr_ts['condition'];
    $coa_conf   = $arr_ts['condition.'];
    $value      = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
    switch( $value )
    {
      case( false ):
        $bool_condition = false;
        if ( $this->pObj->b_drs_filter )
        {
          $prompt = 'Condition of ' . $tableField . ' is false.';
          t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
      default;
        $bool_condition = true;
        if ( $this->pObj->b_drs_filter )
        {
          $prompt = 'Condition of ' . $tableField . ' is true.';
          t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
    }
      // Get condition result



      /////////////////////////////////////////////////////////////////
      //
      // RETURN condition result

    return $bool_condition;
      // RETURN condition result
  }

  
  
  
  
  
  
  
  
  /**
 * Order the values by uid or value and ASC or DESC
 *
 * @param array   $arr_values: Array with the values for ordering
 * @param string    $conf_tableField: table and field in table.field syntax
 * @return  array   Array with ordered values
 */
  function orderValues( $arr_values, $conf_tableField )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';



      /////////////////////////////////////////////////////////////////
      //
      // RETURN there aren't values for ordering

    if ( ! is_array( $arr_values ) )
    {
      return $arr_values;
    }
    if ( count( $arr_values ) < 1 )
    {
      return $arr_values;
    }
      // RETURN there aren't values for ordering



    list ($table, $field) = explode('.', $conf_tableField);
    $arr_ts = $conf_view['filter.'][$table . '.'][$field . '.'];

    if ( $arr_ts['order.']['field'] == 'uid' )
    {
      $arr_values = array_flip( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog('[INFO/FILTER] Values are ordered by uid.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/FILTER] If you want order values by there values, please configure ' . $conf_view_path . $conf_tableField . '.order.field.', $this->pObj->extKey, 1);
      }
    }

    if ( strtolower( $arr_ts['order.']['orderFlag'] ) == 'desc' )
    {
      arsort( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog('[INFO/FILTER] Values are ordered descending.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/FILTER] If you want to order ascending, please configure ' . $conf_view_path . $conf_tableField . '.order.orderFlag = ASC.', $this->pObj->extKey, 1);
      }
    }
    else
    {
      asort( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog('[INFO/FILTER] Values are ordered ascending.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/FILTER] If you want to order descending, please configure ' . $conf_view_path . $conf_tableField . '.order.orderFlag = DESC.', $this->pObj->extKey, 1);
      }
    }
    if ($arr_ts['order.']['field'] == ('uid'))
    {
      $arr_values = array_flip($arr_values);
    }

    return $arr_values;
  }

  /**
   * Order the items, add the first item and wrap all items
   * Is used by class template only. dwildt, 120121
   *
   * @param array   $arr_ts: The TypoScript configuration of the object
   * @param array   $arr_values: The values for the object
   * @param string    $tableField: The current table.field from the ts filter array
   * @return  array   Return the processed items
   * @version 3.6.0
   */
  function items_order_and_addFirst($arr_ts, $arr_values, $tableField) {
  
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';



    /////////////////////////////////////////////////////////////////
    //
    // Order the values and save the order!

    // #11407: Ordering filter items hasn't any effect
    $arr_values = $this->orderValues($arr_values, $tableField);
    // Order the values and save the order!



    /////////////////////////////////////////////////////////////////
    //
    // Handle the first_item

    if ($arr_ts['first_item'])

    {
      $bool_handle = true;
      
      // :todo: 101019, dwildt: Next section seems to have an unproper effect
      //      $bool_display_without_any_hit = $arr_ts['first_item.']['display_without_any_hit'];
      $int_hits = $this->arr_hits[$tableField]['sum'];
      //
      //      // There is no hit
      //      if($int_hits < 1)
      //      {
      //        $bool_handle = false;
      //        $int_hits  = 0;
      //      }
      //      // There is no hit
      
      // Wrap the first item and prepaire it for adding
      //if($bool_handle || $bool_display_without_any_hit)
      if ($bool_handle) {
        // Wrap the item
        $value = $this->pObj->local_cObj->stdWrap($arr_ts['first_item.']['stdWrap.']['value'], $arr_ts['first_item.']['stdWrap.']);
        
        // Wrap the hits and add it to the item
        $bool_display_hits = $arr_ts['first_item.']['display_hits'];
        if ($bool_display_hits) {
          $conf_hits = $arr_ts['first_item.']['display_hits.']['stdWrap.'];
          $str_hits = $this->pObj->objWrapper->general_stdWrap($int_hits, $conf_hits);
          $bool_behindItem = $arr_ts['first_item.']['display_hits.']['behindItem'];
          if ($bool_behindItem)
          {
            $value = $value . $str_hits;
          }
          if (!$bool_behindItem)
          {
            $value = $str_hits . $value;
          }
        }
          // Wrap the hits and add it to the item

          // Prepaire item for adding
          // dwildt, 101211, #11401
        //$arr_new_values[0] = $value;
        $arr_new_values[$arr_ts['first_item.']['option_value']] = $value;
        if ($this->pObj->b_drs_filter)
        {
          t3lib_div :: devLog('[INFO/FILTER] \'' . $value . '\' is added as the first item.', $this->pObj->extKey, 0);
          t3lib_div :: devLog('[HELP/FILTER] If you don\'t want a default item, please configure ' . $conf_view_path . $tableField . '.first_item.', $this->pObj->extKey, 1);
        }
          // Prepaire item for adding
      }  
        // Wrap the first item and prepaire it for adding
    }    
      // Handle the first_item
//if (t3lib_div :: getIndpEnv('REMOTE_ADDR') == '84.184.226.247')
//  var_dump('filter 1399', $arr_values, $arr_new_values);


      /////////////////////////////////////////////////////////////////
      //
      // Order the values and save the order!

      // #11407: Ordering filter items hasn't any effect
//    if (is_array($arr_new_values))
//    {
//      $arr_values = $this->orderValues($arr_values, $tableField);
//      if (count($arr_values) > 0) {
//        // Order the values
//        foreach ($arr_values as $uid => $value)
//        {
//          $arr_new_values[$uid] = $value;
//        }
//        unset ($arr_values);
//        $arr_values = $arr_new_values;
//        unset ($arr_new_values);
//      }
//    }
      // #11407: Ordering filter items hasn't any effect
      // Order the values and save the order!



      /////////////////////////////////////////////////////////////////
      // 
      // Add the first_item
      // #11407: Ordering filter items hasn't any effect

    if (is_array($arr_new_values))
    {    
      if (count($arr_values) > 0)
      {  
        foreach ($arr_values as $uid => $value)
        {
          $arr_new_values[$uid] = $value;
        }
      }  
      unset ($arr_values);
      $arr_values = $arr_new_values;
      unset ($arr_new_values);
    }    
      // Add the first_item
      // #11407: Ordering filter items hasn't any effect


      /////////////////////////////////////////////////////////////////
      // 
      // stdWrap all items but the first item

    if (count($arr_values) > 0)
    {
      foreach ($arr_values as $key => $value)
      {
        if ($key != $arr_ts['first_item.']['option_value'])
        {
          if(is_array($this->pObj->objCal->arr_area[$tableField]))
          {
            // Do noting. Items were wrapped.
          }
          if(!is_array($this->pObj->objCal->arr_area[$tableField]))
          {
            $tsConf = $arr_ts['wrap.']['item.']['stdWrap.'];
            $value  = $this->pObj->local_cObj->stdWrap($value, $tsConf);
//var_dump(__METHOD__ . ' (' . __LINE__ . ')', $value);
          }
        }
        $arr_values[$key] = $value;
      }
    }
      // stdWrap all items but the first item



    $arr_return['data']['values'] = $arr_values;
    return $arr_return;
  }










  /**
 * andWhere_filter: Generate the andWhere statement, if it is needed.
 *
 * @return  array   arr_andWhereFilter: NULL if there isn' any filter
 * @version 3.6.0
 */
  function andWhere_filter()
  {
    $arr_andWhereFilter = null;

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];



      /////////////////////////////////////////////////////////////////
      //
      // Set the global $arr_conf_tableFields

    $bool_noTableFields = $this->get_tableFields();
      // Return, if we don't have any filter array
    if ($bool_noTableFields)
    {
      return false;
    }
      // Return, if we don't have any filter array
      // Set the global $arr_conf_tableFields



      /////////////////////////////////////////////////////////////////
      //
      // Init area

      // dwildt, 101211, #11402
    $this->pObj->objCal->area_init();
    $conf       = $this->pObj->conf;
    $conf_view  = $conf['views.'][$viewWiDot][$mode . '.'];
      // Init area



      /////////////////////////////////////////////////////////////////
      //
      // LOOP: filter tableFields

    $bool_manual  = $this->pObj->b_sql_manual;
    $str_andWhere = null;

    foreach ($this->arr_conf_tableFields as $tableField)
    {
        // Process nice_piVar
      list ($table, $field) = explode('.', $tableField);
      // #8337, 101011, dwildt
      $obj_ts         = $conf_view['filter.'][$table . '.'][$field];
      $arr_ts         = $conf_view['filter.'][$table . '.'][$field . '.'];
      $arr_result     = $this->get_nice_piVar($obj_ts, $arr_ts, $tableField);
      $key_piVar      = $arr_result['data']['key_piVar'];
      $arr_piVar      = $arr_result['data']['arr_piVar'];
      $str_nice_piVar = $arr_result['data']['nice_piVar'];
      unset ($arr_result);
        // Process nice_piVar

        // Current piVar isn't set
      $bool_handleCurrPiVar = true;
      if (count($arr_piVar) < 1)
      {
        $bool_handleCurrPiVar = false;
      }
        // Current piVar isn't set

        // Build the andWhere statement
      if ($bool_handleCurrPiVar)
      {
          // SQL automatic mode
        if (!$bool_manual)
        {
            // dwildt, 101211, #11401
            // andWhere for the localTable
          if ($table == $this->pObj->localTable)
          {
            $str_andWhere = $this->andWhere_localTable($obj_ts, $arr_ts, $arr_piVar, $tableField);
          }
            // andWhere for the localTable
            // andWhere for a foreignTable
          if ($table != $this->pObj->localTable)
          {
            $str_andWhere = $this->andWhere_foreignTable($obj_ts, $arr_ts, $arr_piVar, $tableField);
          }
            // andWhere for a foreignTable
        }
          // SQL automatic mode

          // SQL manual mode
        if ($bool_manual)
        {
          $str_andWhere = null;
          $str_uidList = implode(', ', $arr_piVar);
          $lArr_tableAliases = $conf_view['aliases.']['tables.'];
          $lArr_tableAliases = array_flip($lArr_tableAliases);
          $lTable = $lArr_tableAliases[$table]; // locations
          if (!$lTable)
          {
            if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql) {
              t3lib_div :: devlog('[ERROR/FILTER+SQL] There is no alias for table \'' . $table . '\'', $this->pObj->extKey, 3);
              t3lib_div :: devlog('[INFO/FILTER+SQL] Browser is in SQL manual mode.', $this->pObj->extKey, 0);
              t3lib_div :: devlog('[HELP/FILTER+SQL] Please configure aliases.tables of this view.', $this->pObj->extKey, 1);
              echo '<h1>ERROR</h1>
                              <h2>There is no table alias</h2>
                              <p>Please see the logs in the DRS - Development Reporting System.</p>';
              exit;
            }
          }
          // dwildt, 101223
          //$str_andWhere .= " AND " . $lTable . ".uid IN (" . $str_uidList . ")\n";
          $str_andWhere = $lTable . ".uid IN (" . $str_uidList . ")\n";
        }
          // SQL manual mode
      }
        // Build the andWhere statement

        // Set arr_andWhereFilter
      if ($bool_handleCurrPiVar) 
      {
        if(!empty($str_andWhere))
        {
          $arr_andWhereFilter[$table . '.' . $field] = $str_andWhere;
        }
      }
        // Set arr_andWhereFilter
    }
    // LOOP: filter tableFields


    if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql) 
    {
      if(is_array($arr_andWhereFilter))
      {
        $prompt = implode(' AND ', $arr_andWhereFilter);
        t3lib_div :: devlog('[INFO/FILTER+SQL] andWhere statement:<br /><br />' . $prompt, $this->pObj->extKey, 0);
      }
    }

    return $arr_andWhereFilter;

  }









  /**
 * andWhere_localTable: Generate the andWhere statement for a field from the localtable.
 *                      If there is an area, it will be handled
 *
 * @param string    $obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param array   $arr_ts: The TypoScript configuration of the SELECTBOX
 * @param array   $arr_piVar   Current piVars
 * @param string    $tableField   Current table.field
 * @return  array   arr_andWhereFilter: NULL if there isn' any filter
 * @version 3.6.0
 */
  function andWhere_localTable($obj_ts, $arr_ts, $arr_piVar, $tableField)
  {
    $str_andWhere = null;



      /////////////////////////////////////////////////////////////////
      //
      // Handle area filter

    if(is_array($this->pObj->objCal->arr_area[$tableField]))
    {
      foreach ($arr_piVar as $str_piVar)
      {
          // 13920, 110319, dwildt
          // Move url value to tsKey
        $str_piVar      = $this->pObj->objCal->area_get_tsKey_from_urlPeriod($tableField, $str_piVar);

        $arr_item       = null;
        $str_key        = $this->pObj->objCal->arr_area[$tableField]['key']; // I.e strings
        $arr_currField  = $arr_ts['area.'][$str_key . '.']['options.']['fields.'][$str_piVar . '.'];

        $from       = $arr_currField['valueFrom_stdWrap.']['value'];
        $from_conf  = $arr_currField['valueFrom_stdWrap.'];
        $from_conf  = $this->pObj->objZz->substitute_t3globals_recurs($from_conf);
        $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);
        if(!empty($from))
        {
          $arr_item[] = $tableField . " >= '" . mysql_real_escape_string($from) . "'";
        }

        $to         = $arr_currField['valueTo_stdWrap.']['value'];
        $to_conf    = $arr_currField['valueTo_stdWrap.'];
        $to_conf    = $this->pObj->objZz->substitute_t3globals_recurs($to_conf);
        $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);
        if(!empty($to))
        {
          $arr_item[] = $tableField . " <= '" . mysql_real_escape_string($to) . "'";
        }

        if(is_array($arr_item))
        {
          $arr_orValues[] = '(' . implode(' AND ', $arr_item) . ') ';
        }
      }
      $str_andWhere = implode(' OR ', $arr_orValues);
      if(!empty($str_andWhere))
      {
        $str_andWhere = ' (' . $str_andWhere . ')';
      }
    }
      // Handle area filter



      /////////////////////////////////////////////////////////////////
      //
      // Handle without area filter

    if(!is_array($this->pObj->objCal->arr_area[$tableField]))
    {
      foreach ($arr_piVar as $str_value)
      {
        $arr_orValues[] = $tableField . " LIKE '" . mysql_real_escape_string($str_value) . "'";
      }
      $str_andWhere = implode(' OR ', $arr_orValues);
      if(!empty($str_andWhere))
      {
        $str_andWhere = ' (' . $str_andWhere . ')';
      }
    }
      // Handle without area filter

    return $str_andWhere;
  }



















  /**
 * andWhere_foreignTable: Generate the andWhere statement for a field from a foreign table.
 *                        If there is an area, it will be handled
 *
 * @param string    $obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param array   $arr_ts: The TypoScript configuration of the SELECTBOX
 * @param array   $arr_piVar   Current piVars
 * @param string    $tableField   Current table.field
 * @return  array   arr_andWhereFilter: NULL if there isn' any filter
 * @version 3.6.0
 */
  function andWhere_foreignTable($obj_ts, $arr_ts, $arr_piVar, $tableField)
  {
    list ($table, $field) = explode('.', $tableField);
    $str_andWhere = null;



      /////////////////////////////////////////////////////////////////
      //
      // Handle area filter

    if(is_array($this->pObj->objCal->arr_area[$tableField]))
    {
      foreach ($arr_piVar as $str_piVar)
      {
          // 13920, 110319, dwildt
          // Move url value to tsKey
        $str_piVar      = $this->pObj->objCal->area_get_tsKey_from_urlPeriod($tableField, $str_piVar);

        $arr_item       = null;
        $str_key        = $this->pObj->objCal->arr_area[$tableField]['key']; // I.e strings
        $arr_currField  = $arr_ts['area.'][$str_key . '.']['options.']['fields.'][$str_piVar . '.'];

        $from       = $arr_currField['valueFrom_stdWrap.']['value'];
        $from_conf  = $arr_currField['valueFrom_stdWrap.'];
        $from_conf  = $this->pObj->objZz->substitute_t3globals_recurs($from_conf);
        $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);
        if(!empty($from))
        {
          $arr_item[] = $tableField . " >= '" . mysql_real_escape_string($from) . "'";
        }

        $to         = $arr_currField['valueTo_stdWrap.']['value'];
        $to_conf    = $arr_currField['valueTo_stdWrap.'];
        $to_conf    = $this->pObj->objZz->substitute_t3globals_recurs($to_conf);
        $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);
        if(!empty($to))
        {
          $arr_item[] = $tableField . " <= '" . mysql_real_escape_string($to) . "'";
        }

        if(is_array($arr_item))
        {
          $arr_orValues[] = '(' . implode(' AND ', $arr_item) . ') ';
        }
      }
      $str_andWhere = implode(' OR ', $arr_orValues);
      if(empty($str_andWhere))
      {
        $str_andWhere = ' (' . $str_andWhere . ')';
      }
    }
      // Handle area filter



      /////////////////////////////////////////////////////////////////
      //
      // Handle without area filter

    if(!is_array($this->pObj->objCal->arr_area[$tableField]))
    {
      $str_uidList = implode(', ', $arr_piVar);
      $str_andWhere = $table . ".uid IN (" . $str_uidList . ")\n";
    }
      // Handle without area filter

    return $str_andWhere;
  }








  /**
 * getRows(): Building the SQL query. Execute the query. Return the result as rows.
 *
 * @param string    $tableField: table.field
 * @return  array   Data array with rows
 * @version 3.9.6
 * @ since 3.0.1
 */
  function getRows($tableField) 
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    $arr_return['error']['status'] = false;



      /////////////////////////////////////////////////////////////////
      //
      // Build the SQL query.

    list ($table, $field) = explode('.', $tableField);

      // SELECT
    $str_select = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['select'];
    if ( ! empty ( $str_select ) ) 
    {
      $str_select = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_select);
      if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql) 
      {
        t3lib_div :: devlog('[INFO/FILTER+SQL] Select Override is activated. ' . $str_select, $this->pObj->extKey, 0);
      }
    }
    if (empty ( $str_select ) ) 
    {
      $str_select = $table . ".uid AS 'uid'," . PHP_EOL .
      "         " . $table . "." . $field . " AS 'value'," . PHP_EOL;
      if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql)
      {
        t3lib_div :: devlog('[INFO/FILTER+SQL] There is no select override. ' . $str_select, $this->pObj->extKey, 0);
      }
    }



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $treeviewEnabled

      // #32223, 120120, dwildt+
    $cObj_name        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled'];
    $cObj_conf        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled.'];
    $treeviewEnabled  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->pObj->b_drs_filter )
    {
      if( $treeviewEnabled )
      {
        $prompt = 'treeview is enabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
      }
      if( ! $treeviewEnabled )
      {
        $prompt = 'treeview is disabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
      }
      t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
    }
      // #32223, 120120, dwildt+
      // Set the global $treeviewEnabled



      ///////////////////////////////////////////////////////////////
      //
      // Table has a treeParentField

      // #32223, 120119, dwildt+
    if( $treeviewEnabled )
    {
        // Load the TCA for the current table
      $this->pObj->objZz->loadTCA( $table );
        // Table has a treeParentField
      if( isset( $GLOBALS['TCA'][$table]['ctrl']['treeParentField'] ) )
      {
          // Add treeParentField to the SELECT statement
        $treeParentField = $GLOBALS['TCA'][$table]['ctrl']['treeParentField'];
        $str_select .= "         " . $table . "." . $treeParentField . " AS 'treeParentField'," . PHP_EOL;
          // Add treeParentField to the SELECT statement
        $this->arr_tablesWiTreeparentfield[] = $table;
        if( $this->pObj->b_drs_filter )
        {
          $prompt = 'treeview: ' . $table . ' is configured for a tree view (treeParentField).';
          t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
        // Table has a treeParentField
    }
      // #32223, 120119, dwildt+
      // Table has a treeParentField



      ///////////////////////////////////////////////////////////////
      //
      // Build SQL query

      // SELECT
    $str_select = $str_select . PHP_EOL .
    "         '" . $tableField . "' AS 'table.field'###LOCALISATION_SELECT###";
      // SELECT

      // FROM
    $str_from = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['from'];
    if ( $str_from ) 
    {
      $str_from = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_from);
    }
    if (! $str_from ) 
    {
      $str_from = $table;
    }
      // FROM

      // ORDER BY
    $str_orderBy = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['orderBy'];
    if ($str_orderBy) 
    {
      $str_orderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_orderBy);
      $str_orderBy = "  ORDER BY " . $str_orderBy . PHP_EOL;
    }
      // ORDER BY

      // GROUP BY
    $str_groupBy = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['groupBy'];
    if ($str_groupBy)
    {
      $str_groupBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_groupBy);
      $str_groupBy = "  GROUP BY " . $str_groupBy . PHP_EOL;
    }
      // GROUP BY

      // AND WHERE
    $str_andWhere = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['andWhere'];
    $str_andWhere = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($str_andWhere);
    if ($str_andWhere) 
    {
      $str_andWhere = "    AND " . $str_andWhere . PHP_EOL;
    }
      // AND WHERE

      // BUG #8533
      // AND WHERE PID LIST
    if ($this->pObj->pidList) 
    {
      $str_andWhere = $str_andWhere . "    AND " . $table . ".pid IN (" . $this->pObj->pidList . ")\n";
    }
      // AND WHERE PID LIST

      // QUERY
    $query = "  SELECT " . $str_select . PHP_EOL .
    "  FROM " . $str_from . PHP_EOL .
    "  WHERE 1 " . $this->pObj->cObj->enableFields($table) . "###LOCALISATION_WHERE###\n" .
    $str_andWhere .
    $str_groupBy .
    $str_orderBy; // Bugfix #7264
      // QUERY

      // BUGFIX - part I: If table.field isn't in $this->pObj->arr_realTables_arrFields
      //                  we will get trouble in $this->pObj->objLocalise->localisationFields_select()
    $bool_table_is_added = false;
    $bool_field_is_added = false;
    if (!is_array($this->pObj->arr_realTables_arrFields[$table]))
    {
      $this->pObj->arr_realTables_arrFields[$table][] = $field;
      $bool_table_is_added = true;
      $bool_field_is_added = true;
      if ($this->pObj->b_drs_filter)
      {
        t3lib_div :: devlog('[INFO/FILTER] Table ' . $table . '.' . $field . ' is added to arr_realTables_arrFields temporarily. It is removed.', $this->pObj->extKey, 0);
      }
    }
    if (is_array($this->pObj->arr_realTables_arrFields[$table])) 
    {
      if (!in_array($field, $this->pObj->arr_realTables_arrFields[$table])) 
      {
        $this->pObj->arr_realTables_arrFields[$table][] = $field;
        $bool_field_is_added = true;
        if ($this->pObj->b_drs_filter) 
        {
          t3lib_div :: devlog('[INFO/FILTER] Field ' . $table . '.' . $field . ' is added to arr_realTables_arrFields temporarily. It is removed.', $this->pObj->extKey, 0);
        }
      }
    }
      // BUGFIX - part I

    $arr_local_select = $this->pObj->objLocalise->localisationFields_select($table);
      // BUGFIX - part II: Remove added table.fields
    if ($bool_table_is_added) 
    {
      unset ($this->pObj->arr_realTables_arrFields[$table]);
      if ($this->pObj->b_drs_filter) 
      {
        t3lib_div :: devlog('[INFO/FILTER] Table ' . $table . ' is removed from arr_realTables_arrFields temporarily. It is removed.', $this->pObj->extKey, 0);
      }
    }
    if (!$bool_table_is_added && $bool_field_is_added)
    {
      $arr_flip = array_flip($this->pObj->arr_realTables_arrFields[$table]);
      $rm_key = $arr_flip[$field];
      unset ($this->pObj->arr_realTables_arrFields[$table][$rm_key]);
      if ($this->pObj->b_drs_filter) 
      {
        t3lib_div :: devlog('[INFO/FILTER] Field ' . $table . '.' . $field . ' is removed from arr_realTables_arrFields temporarily. It is removed.', $this->pObj->extKey, 0);
      }
    }
      // BUGFIX - part II: Remove added table.fields

    $str_local_select = $arr_local_select['filter'];
    if ($str_local_select) 
    {
      $str_local_select = ",\n" .
      "         " . $str_local_select . PHP_EOL;
    }
    $query = str_replace('###LOCALISATION_SELECT###', $str_local_select, $query);
    $str_local_where = $this->pObj->objLocalise->localisationFields_where($table);
    if ($str_local_where) 
    {
      $str_local_where = " AND " . $str_local_where;
    }
    $query = str_replace('###LOCALISATION_WHERE###', $str_local_where, $query);
      // Build SQL query



      /////////////////////////////////////////////////////////////////
      //
      // Replace PID_LIST

    $str_pid_list = $this->pObj->pidList;
    $str_pid_list = str_replace(',', ', ', $str_pid_list);
      // For human readable
    $query = str_replace('###PID_LIST###', $str_pid_list, $query);
      // Replace PID_LIST


    
      /////////////////////////////////////////////////////////////////
      //
      // Execute the Query

    $res = $GLOBALS['TYPO3_DB']->sql_query($query);
    $error = $GLOBALS['TYPO3_DB']->sql_error();

    if ($error != '') 
    {
      if ($this->pObj->b_drs_filter) 
      {
        t3lib_div :: devlog('[ERROR/FILTER] ' . $query, $this->pObj->extKey, 3);
        t3lib_div :: devlog('[ERROR/FILTER] ' . $error, $this->pObj->extKey, 3);
        t3lib_div :: devlog('[ERROR/FILTER] ABORT.', $this->pObj->extKey, 3);
      }
      $str_header = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
      if ($this->pObj->b_drs_error) 
      {
        $str_warn = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">' . $this->pObj->pi_getLL('drs_security') . '</p>';
        $str_prompt = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $error . '</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">' . $query . '</p>';
      }
      else
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' . $this->pObj->pi_getLL('drs_sql_prompt') . '</p>';
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn . $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
    if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql) 
    {
        // 100629, dwildt
        // $query_br = str_replace(PHP_EOL, '<br />', $query);
        //t3lib_div::devlog('[INFO/FILTER+SQL] Query:<br /><br />'.$query_br, $this->pObj->extKey, 0);
      t3lib_div :: devlog('[INFO/FILTER+SQL] Query:<br /><br />' . $query, $this->pObj->extKey, 0);
    }
// dwildt, 110309
//var_dump(__METHOD__ . ': ' . __LINE__ , $query);
      // Execute the Query



      ////////////////////////////////////
      //
      // Building the rows

    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) 
    {
      $rows[] = $row;
    }
    if ($this->pObj->b_drs_filter || $this->pObj->b_drs_sql) 
    {
      t3lib_div :: devlog('[INFO/FILTER+SQL] Result: #' . count($rows) . ' row(s).', $this->pObj->extKey, 0);
    }
      // Building the rows



      /////////////////////////////////////////////////////////////////
      //
      // Consolidate Localisation

    $rows = $this->pObj->objLocalise->consolidate_filter($rows);
      // Consolidate Localisation



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for handle the consolidated rows

      // #12813, dwildt, 110309
      // This hook is used by one foreign extension at least
    $this->rows = $rows;
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_options']))
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_hooks)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_options']);
        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_options']);
        $csv_ext      = implode(',', $arr_ext);
        if ($i_extensions == 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extension '.$csv_ext.' uses the HOOK rows_filter_options.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1);
        }
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extensions '.$csv_ext.' use the HOOK rows_filter_options.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extensions!', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      $_params = array('pObj' => &$this);
      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_options'] as $_funcRef)
      {
        t3lib_div::callUserFunction($_funcRef, $_params, $this);
      }
    }
      // Any foreign extension is using this hook
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_hooks)
    {
      if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_filter_options']))
      {
        t3lib_div::devlog('[INFO/HOOK] Any third party extension doesn\'t use the HOOK rows_filter_options.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/HOOK] See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/', $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System
      // Any foreign extension is using this hook
// dwildt, 110309
//foreach ($this->pObj->rows as $rKey => $rVal) {
//  var_dump($rVal['tx_org_workshop.uid'] . ': ' . $rVal['tx_org_workshop.rating']);
//}
    $rows = $this->rows;
// dwildt, 110309
//var_dump(__METHOD__ . ': ' . __LINE__ , $rows);
//foreach ($rows as $rKey => $rVal) {
//  var_dump($rVal['tx_org_workshop.uid'] . ': ' . $rVal['tx_org_workshop.rating']);
//}
      // Hook for handle the consolidated rows



      // RETURN the result
    $arr_return['data']['rows'] = $rows;
// dwildt, 110309
//var_dump(__METHOD__ . ': ' . __LINE__ , $arr_return['data']['rows']);

    return $arr_return;
  }









  /**
 * wrapRows(): Main function for filter processing. It returns the template with rendered filters.
 *
 * @param array   $arr_input: array rows, template
 * @return  array   The array with the template at least
 * @version 3.5.0
 */
  function wrapRows( $arr_input )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';

    $arr_rows = $arr_input['data']['rows'];
      // #32223, 120119, dwildt+
    $this->arr_rows = $arr_rows;
    $template = $arr_input['data']['template'];
    unset ($arr_input);



      /////////////////////////////////////////////////////////////////
      //
      // RETURN / ERROR: All filters are empty

    if (empty ($arr_rows)) {
      if ($this->pObj->b_drs_error) {
        t3lib_div :: devlog('[ERROR/FILTER] Any Filter hasn\'t any item!', $this->pObj->extKey, 3);
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = 'Filter: Error';
      $arr_return['error']['prompt'] = 'All filters are empty';
      return $arr_return;
    }
      // RETURN / ERROR: All filters are empty



      /////////////////////////////////////////////////////////////////
      //
      // Convert the rows

    // From -------------------------------------------------------------
    // array["tx_org_newscat.title"][0]["table.field"] = "tx_org_newscat.title"
    // array["tx_org_newscat.title"][0]["uid"]         = "1"
    // array["tx_org_newscat.title"][0]["value"]       = "Berlin"
    // array["tx_org_newscat.title"][1]["table.field"] = "tx_org_newscat.title"
    // array["tx_org_newscat.title"][1]["uid"]         = "3"
    // array["tx_org_newscat.title"][1]["value"]       = "Istanbul"
    // ...
    // To ---------------------------------------------------------------
    // array["tx_org_newscat.title"][1]   = "Berlin"
    // array["tx_org_newscat.title"][2]   = "Wien"
    // array["tx_org_newscat.title"][3]   = "Istanbul"
    // ...
    // ------------------------------------------------------------------

      // LOOP table.field
    $arr_tableFields = null;
    foreach ( $arr_rows as $tableField => $rows )
    {
        // DRS - Development Reporting System
        // Rows are empty
      if ( count( $rows ) < 1 )
      {
        if ( $this->pObj->b_drs_warn )
        {
          t3lib_div :: devlog('[WARN/FILTER] SQL result for ' . $tableField . ' ' .
          'is empty. This is an error probably.', $this->pObj->extKey, 2);
        }
      }
        // Rows are empty
        // DRS - Development Reporting System

      list( $table, $field ) = explode( '.', $tableField);
      switch( true )
      {
        case( in_array( $table, $this->arr_tablesWiTreeparentfield ) ):
            // #32223, 120119, dwildt+
          $arr_tableFields[$tableField] = $this->get_treeOrdered( $arr_rows, $tableField );
//$pos = strpos('79.204.110.26', t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if( ! ( $pos === false ) )
//{
//  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $arr_tableFields[$tableField] );
//}
          break;
        default:
            // #32223, 120119, dwildt-
            // Convert the rows
//          foreach ( ( array ) $rows as $key => $row)
//          {
//            $arr_tableFields[$tableField][$row['uid']] = $row['value'];
//          }
//          unset ($rows);
            // Convert the rows
            // #32223, 120119, dwildt-

            // #32223, 120119, dwildt+
          $arr_tableFields[$tableField] = $this->get_ordered( $arr_rows, $tableField );
          break;
      }

    }
      // LOOP table.field
      // Convert the rows



      /////////////////////////////////////////////////////////////////
      //
      // Wrap table.fields

    foreach ( $arr_tableFields as $tableField => $rows )
    {
      list ( $table, $field ) = explode( '.', $tableField );
      $obj_ts = $conf_view['filter.'][$table . '.'][$field];
      $arr_ts = $conf_view['filter.'][$table . '.'][$field . '.'];
      $str_marker = '###' . strtoupper( $tableField ) . '###';
      switch ( $obj_ts )
      {
        case ('CATEGORY_MENU') :
        case ('CHECKBOX') :
        case ('RADIOBUTTONS') :
        case ('SELECTBOX') :
            // #32117, 111127, dwildt-
          //$marker[$str_marker] = $this->renderHtmlFilter($obj_ts, $arr_ts, $arr_tableFields[$tableField], $tableField);
            // #32117, 111127, dwildt+
            // Evaluate the filter condition
          switch( $this->filterCondition( $tableField, $arr_ts ) )
          {
            case( true ):
                // There isn't any condition or condition is met
                // Display the filter
              $marker[$str_marker] = $this->renderHtmlFilter($obj_ts, $arr_ts, $arr_tableFields[$tableField], $tableField);
              break;
            default:
                // Condition isn't met
                // If there are filter values, add it as hidden fields
              $str_inputHidden = null;
              foreach( $this->pObj->piVars[$tableField] as $filterValue )
              {
                $str_inputHidden = $str_inputHidden . '<input type="hidden" value="' . $filterValue .  '" name="tx_browser_pi1[' . $tableField . '][]">';
              }
              $marker[$str_marker] = $str_inputHidden;
                // If there are filter values, add it as hidden fields
              break;
          }
            // Evaluate the filter condition
            // #32117, 111127, dwildt+
          break;
        default :
          if ( $this->pObj->b_drs_filter )
          {
            t3lib_div :: devLog('[WARN/FILTER] \'' . $conf_view_path . '\' contents an undefined TS object: \'' . $obj_ts . '\'', $this->pObj->extKey, 2);
            t3lib_div :: devLog('[ERROR/FILTER] ABORTED.', $this->pObj->extKey, 3);
            t3lib_div :: devLog('[HELP/FILTER] Configure ' . $conf_view_path . $tableField . '.', $this->pObj->extKey, 1);
          }
          $str_header = '<h1 style="color:red">' . $this->pObj->pi_getLL('error_filter_h1') . '</h1>';
          $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' . $this->pObj->pi_getLL('error_filter_prompt') . '</p>';
          $arr_return['error']['status'] = true;
          $arr_return['error']['header'] = $str_header;
          $arr_return['error']['prompt'] = $str_prompt;
          return $arr_return;
      }
    }
      // Wrap table.fields

    $arr_return['data']['marker'] = $marker;
    return $arr_return;
  }

  /**
 * Returns an array with key_piVar, arr_piVar and nice_piVar
 *
 * @param string    $obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param array   $arr_ts: The TypoScript configuration of the Object
 * @param string    $conf_tableField: The current table.field from the ts filter array
 * @return  array   Data array with the selectbox at least
 */
  function get_nice_piVar($obj_ts, $arr_ts, $conf_tableField) 
  {
    $str_nice_piVar = $arr_ts['nice_piVar'];
    if ($str_nice_piVar == '') 
    {
      $str_nice_piVar = $conf_tableField;
    }
    // #8337, 101012, dwildt
    switch ($obj_ts) 
    {
      case ('CHECKBOX') :
        $conf_multiple = true;
        break;
      case ('CATEGORY_MENU') :
      case ('RADIOBUTTONS') :
        $conf_multiple = false;
        break;
      case ('SELECTBOX') :
        $conf_multiple = $arr_ts['multiple'];
        break;
      default :
        $conf_multiple = false;
        if ($this->pObj->b_drs_error) 
        {
          t3lib_div :: devlog('[ERROR/FILTER] multiple - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
          t3lib_div :: devlog('[INFO/FILTER] multiple becomes false.', $this->pObj->extKey, 3);
        }
    }

//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('filter 1185', $str_nice_piVar, $this->pObj->piVars);

    if (!$conf_multiple)
    {
      $key_piVar                  = $this->pObj->prefixId . '[' . $str_nice_piVar . ']';
      $arr_piVar[0]               = $this->pObj->piVars[$str_nice_piVar];
    }
    if ($conf_multiple) 
    {
      $key_piVar = $this->pObj->prefixId . '[' . $str_nice_piVar . '][]';
      $arr_piVar = $this->pObj->piVars[$str_nice_piVar];
      if (!is_array($arr_piVar)) 
      {
          // There is no piVar array. But we need an array in every case!
        $arr_piVar = array ();
      }
    }
      // Unset $arr_piVar, if it's empty
    if (is_array($arr_piVar)) 
    {
      foreach ($arr_piVar as $key => $value) {
        if (!$value) {
          unset ($arr_piVar[$key]);
        }
      }
    }

    $arr_return['data']['key_piVar']  = $key_piVar;
    $arr_return['data']['arr_piVar']  = $arr_piVar;
    $arr_return['data']['nice_piVar'] = $str_nice_piVar; // Bugfix #7159, 100429

    return $arr_return;
  }









  /***********************************************
  *
  * Rendering TS objects
  *
  **********************************************/

  /**
 * renderHtmlFilter(): Returns the rendered HTML object
 *
 * @param string    $obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param array   $arr_ts: The TypoScript configuration of the SELECTBOX
 * @param array   $arr_values: The values for the selectbox
 * @param string    $tableField: The current table.field from the ts filter array
 * @return  array   Data array with the selectbox at least
 * @version 3.9.3
 * @since   3.0.1
 */
  private function renderHtmlFilter($obj_ts, $arr_ts, $arr_values, $tableField)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot      = $view . '.';
    $conf_view      = $conf['views.'][$viewWiDot][$mode . '.'];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';

    list ($table, $field) = explode('.', $tableField);
    $str_objType = $conf_view['filter.'][$table . '.'][$field];

    $str_html = false;



      /////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ( $this->pObj->b_drs_filter )
    {
      t3lib_div :: devLog('[INFO/FILTER] \'' . $tableField . '\' is detected as a ' . $str_objType . '.', $this->pObj->extKey, 0);
      t3lib_div :: devLog('[HELP/FILTER] Configure the  ' . $str_objType . '? Please configure<br />' . $conf_view_path . $tableField . '.', $this->pObj->extKey, 1);
    }
      // DRS - Development Reporting System



      /////////////////////////////////////////////////////////////////
      //
      // Process nice_piVar

      // #8337, 101011, dwildt
    $arr_result     = $this->get_nice_piVar($obj_ts, $arr_ts, $tableField);
    $key_piVar      = $arr_result['data']['key_piVar'];
    $arr_piVar      = $arr_result['data']['arr_piVar'];
    $str_nice_piVar = $arr_result['data']['nice_piVar'];
    unset ($arr_result);
      // Process nice_piVar



      /////////////////////////////////////////////////////////////////
      //
      // Process nice_html

    $int_space_left = $arr_ts['wrap.']['item.']['nice_html_spaceLeft'];
    $str_space_left = str_repeat(' ', $int_space_left);
      // Process nice_html



      /////////////////////////////////////////////////////////////////
      //
      // Prepaire row and item counting

      // #8337, Checkbox
    switch ($obj_ts) {
      case ('CHECKBOX') :
      case ('RADIOBUTTONS') :
        $maxItemsPerRow = $arr_ts['wrap.']['itemsPerRow'];
        if ($maxItemsPerRow <= 0) {
          $maxItemsPerRow = false;
        }
        if ($maxItemsPerRow > 0) {
          $str_row_wrap     = $arr_ts['wrap.']['itemsPerRow.']['wrap'];
          $arr_row_wrap     = explode('|', $str_row_wrap);
          $str_html         = $str_html . PHP_EOL . $str_space_left . $arr_row_wrap[0];
          $str_html         = str_replace('###EVEN_ODD###', 'even', $str_html);
          $str_noItemValue  = $arr_ts['wrap.']['itemsPerRow.']['noItemValue'];
          $int_count_row    = 0;
          $int_count_item   = 0;
        }
        break;
      case ('CATEGORY_MENU') :
      case ('SELECTBOX') :
      default :
        $maxItemsPerRow = false;
    }
      // Prepaire row and item counting

      // dwildt, 101211, #11401
      // Current table is the local table
    if ($table == $this->pObj->localTable) 
    {
        // Convert key, values and hits
      foreach ($arr_values as $uid => $value) 
      {
          // Key of first item
        if ($uid == $arr_ts['first_item.']['option_value'])
        {
          $key = $uid;
        }
          // Key of first item
          // Key of all other items
        if ($uid != $arr_ts['first_item.']['option_value']) 
        {
          $key = $value;
        }
          // Key of all other items
        
        $arr_values_localTable[$key] = $value;
        if (!isset ($arr_hits_localTable[$tableField][$key])) 
        {
          $arr_hits_localTable[$tableField][$key] = 0;
        }
        $arr_hits_localTable[$tableField][$key] = $arr_hits_localTable[$tableField][$key] + $this->arr_hits[$tableField][$uid];
      }
        // Convert key, values and hits
        // Allocate new keys and hits
      $arr_values = $arr_values_localTable;
      $this->arr_hits[$tableField] = $arr_hits_localTable[$tableField];
        // Allocate new keys and hits
    }
      // Current table is the local table

      // Area
    if ( ! empty ($this->pObj->objCal->arr_area[$tableField]['key'] ) )
    {
      switch ( $this->pObj->objCal->arr_area[$tableField]['key'] )
      {
        case ('strings') :
          $arr_result = $this->pObj->objCal->area_strings($arr_ts, $arr_values, $tableField);
          $arr_values = $arr_result['data']['values'];
          unset ($arr_result);
          break;
        case ('interval') :
          $arr_result = $this->pObj->objCal->area_interval($arr_ts, $arr_values, $tableField);
          $arr_values = $arr_result['data']['values'];
          unset ($arr_result);
          break;
//        case ('from_to_fields') :
//          break;
        default:
          echo 'tx_browser_pi1_filter::rednerHtmlFilter: undefined value in switch '.$this->pObj->objCal->arr_area[$tableField]['key'];
          exit;
      }
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_cal || $this->pObj->b_drs_filter)
      {
        $arr_prompt = null;
        foreach((array) $arr_values as $key => $value)
        {
          $arr_prompt[] = '[' . $key . '] = ' . $value;
        }
        $str_prompt = implode(', ', (array) $arr_prompt);
        t3lib_div :: devLog('[INFO/FILTER+CAL] values are: ' . $str_prompt, $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
    }
      // Area



      /////////////////////////////////////////////////////////////////
      //
      // Wrap values
  
    $arr_result = $this->wrap_values_and_add_first_value($arr_ts, $arr_values, $tableField);
    $arr_values = $arr_result['data']['values'];
    unset ($arr_result);
    $conf_selected = ' ' . $arr_ts['wrap.']['item.']['selected'];
      // Wrap values

    

      /////////////////////////////////////////////////////////////////
      //
      // Configure the display of items without any hit

    switch( true )
    {
      case( in_array( $table, $this->arr_tablesWiTreeparentfield ) ):
          // #32223, 120119, dwildt+
        $first_item_display_without_any_hit = true;
        $records_display_without_any_hit    = true;
          // DRS - Development Reporting System
        if( $this->pObj->b_drs_filter )
        {
          if( $arr_ts['first_item.']['display_without_any_hit'] == false )
          {
            $prompt = 'first_item.display_without_any_hit is false. But ' . $table . ' is displayed in a tree view: display_without_any_hit is set to true!';
            t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
          }
          if( $arr_ts['wrap.']['item.']['display_without_any_hit'] == false )
          {
            $prompt = 'wrap.item.display_without_any_hit is false. But ' . $table . ' is displayed in a tree view: display_without_any_hit is set to true!';
            t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
          }
        }
          // DRS - Development Reporting System
          // #32223, 120119, dwildt+
        break;
      default:
        $first_item_display_without_any_hit = $arr_ts['first_item.']['display_without_any_hit'];
        $records_display_without_any_hit    = $arr_ts['wrap.']['item.']['display_without_any_hit'];
        break;
    }
      // Configure the display of items without any hit

      // Bool: display hits for the first item
    $first_item_display_hits   = $arr_ts['first_item.']['display_hits'];
      // Bool: display hits for all other items
    $records_bool_display_hits = $arr_ts['wrap.']['item.']['display_hits'];



      // Loop through the rows of the SQL result
    $int_count_displayItem = 0;
    foreach ( (array) $arr_values as $uid => $value )
    {
        // #8337, 101012, dwildt
      if ( ! ( $maxItemsPerRow === false ) )
      {
        if ( $int_count_item >= $maxItemsPerRow )
        {
          $str_html       = $str_html . $arr_row_wrap[1] . PHP_EOL . $str_space_left . $arr_row_wrap[0];
          $int_count_row  = $int_count_row +1;
          $str_evenOdd    = $int_count_row % 2 ? 'odd' : 'even';
          $str_html       = str_replace( '###EVEN_ODD###', $str_evenOdd, $str_html );
          $int_count_item = 0;
        }
      }

        // dwildt, 101211, #11401
        // Display configuration of the first item
      if ( $uid == $arr_ts['first_item.']['option_value'] )
      {
        $bool_display_without_any_hit = $first_item_display_without_any_hit;
          // Hits of first item are rendered before
        $bool_display_hits            = false;
      }
        // Display configuration of the first item
        // Display configuration of all other items
      if ( ! ( $uid == $arr_ts['first_item.']['option_value'] ) )
      {
        $bool_display_without_any_hit = $records_display_without_any_hit;
        $bool_display_hits            = $records_bool_display_hits;
        $int_hits                     = $this->arr_hits[$tableField][$uid];
      }
        // Display configuration of all other items
        // dwildt, 101211, #11401


        // 0: Default value for missing hits
      if( $bool_display_hits )
      {
        if( empty( $int_hits ) )
        {
          $int_hits = 0;
        }
      }
        // 0: Default value for missing hits

        // Wrap the item
      if ($int_hits >= 1 || $bool_display_without_any_hit)
      {
        if($obj_ts == 'CATEGORY_MENU')
        {
          $arr_ts = $this->pObj->objJss->class_onchange($obj_ts, $arr_ts, $int_count_displayItem);
        }

        if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
        {
          $conf_item = $value;
        }
        if( ! in_array( $table, $this->arr_tablesWiTreeparentfield ) )
        {
          $conf_item = $arr_ts['wrap.']['item'];
            //#32223, 120119, dwildt+
  //        $tsConf     = $arr_ts['wrap.']['item.']['stdWrap.'];
          $tsConf = $arr_ts['wrap.']['item.']['wraps.']['item.']['stdWrap.'];
          $conf_item  = $this->pObj->local_cObj->stdWrap( $conf_item, $tsConf );
            //#32223, 120119, dwildt+
        }



          // Wrap the item class
        $conf_item = $this->get_wrappedItemClass($arr_ts, $conf_item, false);
          // Wrap the item style
        $conf_item = $this->get_wrappedItemStyle($arr_ts, $conf_item, false);
          // Wrap the item uid
        $conf_item = $this->get_wrappedItemKey($arr_ts, $uid, $conf_item);
          // Wrap the item URL
        $conf_item = $this->get_wrappedItemURL($arr_ts, $tableField, $uid, $conf_item);

          // Get the item selected (or not selected)
        $conf_item = $this->get_wrappedItemSelected($uid, $value, $arr_piVar, $arr_ts, $conf_selected, $conf_item);
          // Remove empty class
        $conf_item = str_replace(' class=""', null, $conf_item);
          // Workaround: 110913, dwildt
        $conf_item = str_replace('class=" ', 'class="', $conf_item);

          // Wrap the hits
        if ( $bool_display_hits )
        {
          $conf_hits        = $arr_ts['wrap.']['item.']['display_hits.']['stdWrap.'];
          $str_hits         = $this->pObj->objWrapper->general_stdWrap($int_hits, $conf_hits);
          $bool_behindItem  = $arr_ts['wrap.']['item.']['display_hits.']['behindItem'];
          switch( $bool_behindItem )
          {
            case( true ):
              if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
              {
                $conf_item = str_replace( '###HITS_BEHIND###', $str_hits, $conf_item );
              }
              if( ! in_array( $table, $this->arr_tablesWiTreeparentfield ) )
              {
                $value = $value . $str_hits;
              }
              break;
            default:
              if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
              {
                $conf_item = str_replace( '###HITS_BEFORE###', $str_hits, $conf_item );
              }
              if( ! in_array( $table, $this->arr_tablesWiTreeparentfield ) )
              {
                $value = $str_hits . $value;
              }
              break;
          }
        }
          // Wrap the hits

          // Wrap the whole value
          // #8337, 101011, dwildt
        $conf_item = str_replace('###TABLE.FIELD###', $key_piVar, $conf_item);

          //#32223, 120119, dwildt-
        //$conf_item              = str_replace('|', $value, $conf_item);
          //#32223, 120119, dwildt+
        $pos = strpos($conf_item, '|');
        if( ! ( $pos === false ) )
        {
          if( $this->pObj->b_drs_filter )
          {
            $prompt = $conf_item . ' contains a pipe ("|"). This is deprecated.';
            t3lib_div :: devlog('[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2);
            $prompt = 'Please edit the current item wrap. Replace the pipe with ###VALUE###.';
            t3lib_div :: devlog('[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1);
          }
          $conf_item = str_replace('|', '###VALUE###', $conf_item);
        }
        $conf_item  = str_replace('###VALUE###',  $value, $conf_item);
        $conf_item  = str_replace('###UID###',    $uid,   $conf_item);
          //#32223, 120119, dwildt+

        $conf_item              = $conf_item . PHP_EOL;
        $str_html               = $str_html . $str_space_left . $conf_item;
        $int_count_displayItem  = $int_count_displayItem +1;
          // Wrap the whole value
      }
        // Wrap the item
      if ( ! ( $maxItemsPerRow === false ) )
      {
        $int_count_item = $int_count_item +1;
      }
    }
      // Loop through the rows of the SQL result

    if ( ! ($maxItemsPerRow === false) )
    {
      $str_html = $str_html . $arr_row_wrap[1] . PHP_EOL;
    }

      // Delete the last line break
    $str_html = substr($str_html, 0, -1);
    $str_html = str_replace( '###HITS_BEFORE###', null, $str_html );
    $str_html = str_replace( '###HITS_BEHIND###', null, $str_html );

      // Wrap the items

    

      /////////////////////////////////////////////////////////////////
      //
      // Wrap all items / the object
  
    if($obj_ts != 'CATEGORY_MENU')
    {
      $arr_ts = $this->pObj->objJss->class_onchange($obj_ts, $arr_ts, $int_count_displayItem);
    }
    if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      $conf_object = '|';
    }
    if( ! in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      $conf_object  = $this->wrap_allItems($obj_ts, $arr_ts, $str_nice_piVar, $key_piVar, $int_count_displayItem);
    }
    $str_html     = str_replace('|', PHP_EOL . $str_html . PHP_EOL . $str_space_left, $conf_object);
      // Wrap the object title
    $conf_wrap    = $this->wrap_objectTitle($arr_ts, $tableField);
      // Wrap the object
    $int_space_left = $arr_ts['wrap.']['nice_html_spaceLeft'];
    $str_space_left = str_repeat(' ', $int_space_left);
    if ($conf_wrap) {
      $str_html = str_replace('|', PHP_EOL . $str_html . PHP_EOL . $str_space_left, $conf_wrap);
    }
      // Wrap all items / the object

    return $str_html;
  }




  /***********************************************
  *
  * Rendering items
  *
  **********************************************/




  /**
 * wrap_values_and_add_first_value( ): Wrap values (value_stdWrap) and add the first value
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param array   $arr_values: The values for the object
 * @param string    $tableField: The current table.field from the ts filter array
 * @return  array   Return the wrapped values
 * @version 3.9.6
 * @since   3.0.1
 */
  private function wrap_values_and_add_first_value($arr_ts, $arr_values, $tableField)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';

    list( $table, $field ) = explode( '.', $tableField);



      /////////////////////////////////////////////////////////////////
      //
      // RETURN tablefield is a treeview

    if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      $arr_return['data']['values'] = $arr_values;
      return $arr_return;
    }
      // RETURN tablefield is a treeview



      /////////////////////////////////////////////////////////////////
      //
      // Wrap the value of the first item

    if ($arr_ts['first_item'])
    {
      $int_hits     = $this->arr_hits[$tableField]['sum'];

        // Wrap the value of the first item and prepaire it for adding
        // Wrap the item
      $value  = $arr_ts['first_item.']['value_stdWrap.']['value'];
      $tsConf = $arr_ts['first_item.']['value_stdWrap.'];
      $value  = $this->pObj->local_cObj->stdWrap( $value, $tsConf);

        // Wrap the hits and add it to the item
      $bool_display_hits = $arr_ts['first_item.']['display_hits'];
      if ( $bool_display_hits )
      {
        $conf_hits  = $arr_ts['first_item.']['display_hits.']['stdWrap.'];
        $str_hits   = $this->pObj->objWrapper->general_stdWrap( $int_hits, $conf_hits );
        $bool_behindItem = $arr_ts['first_item.']['display_hits.']['behindItem'];
        if ($bool_behindItem)
        {
          $value = $value . $str_hits;
        }
        if (!$bool_behindItem)
        {
          $value = $str_hits . $value;
        }
      }
        // Wrap the hits and add it to the item

        // Prepaire item for adding
        // dwildt, 101211, #11401
      //$arr_new_values[0] = $value;
      $arr_new_values[$arr_ts['first_item.']['option_value']] = $value;
      if ($this->pObj->b_drs_filter)
      {
        t3lib_div :: devLog('[INFO/FILTER] \'' . $value . '\' is added as the first item.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/FILTER] If you don\'t want a default item, please configure ' . $conf_view_path . $tableField . '.first_item.', $this->pObj->extKey, 1);
      }
        // Prepaire item for adding
    }
      // Wrap the value of the first item



      /////////////////////////////////////////////////////////////////
      //
      // Add the first_item
      // #11407: Ordering filter items hasn't any effect

    if ( is_array( $arr_new_values ) )
    {
      foreach ( (array) $arr_values as $uid => $value )
      {
        $arr_new_values[$uid] = $value;
      }
      unset ( $arr_values );
      $arr_values = $arr_new_values;
      unset ( $arr_new_values );
    }
      // Add the first_item
      // #11407: Ordering filter items hasn't any effect



      /////////////////////////////////////////////////////////////////
      //
      // Wrap values of all items but the first item

    foreach ( ( array ) $arr_values as $key => $value )
    {
      if ( $key != $arr_ts['first_item.']['option_value'] )
      {
        if( is_array( $this->pObj->objCal->arr_area[$tableField] ) )
        {
          // Do noting. Items were wrapped.
        }
        if( ! is_array( $this->pObj->objCal->arr_area[$tableField] ) )
        {
            //#32223, 120119, dwildt-
          //$tsConf = $arr_ts['wrap.']['item.']['stdWrap.'];
            //#32223, 120119, dwildt+
//          $tsConf = $arr_ts['wrap.']['item.']['value_stdWrap.'];
          $tsConf = $arr_ts['wrap.']['item.']['wraps.']['value.']['stdWrap.'];
          $value  = $this->pObj->local_cObj->stdWrap( $value, $tsConf );
        }
      }
      $arr_values[$key] = $value;
    }
      // Wrap values of all items but the first item



      /////////////////////////////////////////////////////////////////
      //
      // RETURN the result

    $arr_return['data']['values'] = $arr_values;
    return $arr_return;
      // RETURN the result
  }







  /**
 * Wraps the title of the object
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param string    $conf_tableField: The current table.field from the ts filter array
 * @return  string    Returns the wrapped title
 * @version 3.9.6
 */
  function wrap_objectTitle($arr_ts, $conf_tableField) {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';

    $conf_wrap = $arr_ts['wrap'];

      // Don't wrap the object title
    if ( ! is_array( $arr_ts['wrap.']['title_stdWrap.'] ) )
    {
        // It isn't any title configured. Delete the marker.
      $conf_wrap = str_replace( '###TITLE###', '', $conf_wrap );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog('[INFO/FILTER] There is no title_stdWrap. The object won\'t get a title.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/FILTER] If you want a title, please configure ' . $conf_view_path . $conf_tableField . '.wrap.title_stdWrap.', $this->pObj->extKey, 1);
      }
    }
      // Don't wrap the object title

      // Wrap the object title (TypoScript stdWrap)
    if ( is_array( $arr_ts['wrap.']['title_stdWrap.'] ) )
    {
      $lConfCObj = $arr_ts['wrap.']['title_stdWrap.'];

        // Get the local or gloabl autoconfig array - #9879
      $lAutoconf = $conf_view['autoconfig.'];
      $view_path = $viewWiDot . $mode;
      if ( ! is_array( $lAutoconf ) )
      {
        if ( $this->pObj->b_drs_sql )
        {
          t3lib_div :: devlog('[INFO/SQL] views.' . $view_path . ' hasn\'t any autoconf array.<br />
                      We take the global one.', $this->pObj->extKey, 0);
        }
        $lAutoconf = $conf['autoconfig.'];
        $view_path = null;
      }
        // Get the local or gloabl autoconfig array - #9879

        // Don't replace markers recursive
      if ( ! $lAutoconf['marker.']['typoScript.']['replacement'] )
      {
        if ( $this->pObj->b_drs_filter )
        {
          t3lib_div :: devLog('[INFO/FILTER] Replacement for markers in TypoScript is deactivated.', $this->pObj->extKey, 0);
          t3lib_div :: devLog('[HELP/FILTER] If you want a replacement, please configure ' . $view_path . 'autoconfig.marker.typoScript.replacement.', $this->pObj->extKey, 1);
        }
      }
        // Don't replace markers recursive

        // Replace ###TABLE.FIELD### recursive
      $value_marker = $this->pObj->objZz->getTableFieldLL($conf_tableField);
      if ( $lAutoconf['marker.']['typoScript.']['replacement'] )
      {
        $key_marker = '###' . strtoupper($conf_tableField) . '###';
        $markerArray[$key_marker] = $value_marker;
        $lConfCObj = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $markerArray);
        if ($this->pObj->b_drs_filter)
        {
          t3lib_div :: devLog('[INFO/FILTER] ###TITLE### will be replaced with the localised value of \'' . $conf_tableField . '\': \'' . $value_marker . '\'.', $this->pObj->extKey, 0);
          t3lib_div :: devLog('[HELP/FILTER] If you want another replacement, please configure ' . $conf_view_path . $conf_tableField . '.wrap.title_stdWrap', $this->pObj->extKey, 1);
        }
      }
        // Replace ###TABLE.FIELD### recursive

      $str_stdWrap  = $this->pObj->local_cObj->stdWrap( $value_marker, $lConfCObj );
      $conf_wrap    = str_replace( '###TITLE###', $str_stdWrap, $conf_wrap );
    }
      // Wrap the object title (TypoScript stdWrap)

    return $conf_wrap;
  }



  /**
 * get_ordered: ...
 *
 * @param array     $arr_rows   : Result of the SQL query
 * @param string    $tableField : Current table.field
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.6
 * @since   3.9.6
 */
  private function get_ordered( $arr_rows, $tableField )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot  = $view . '.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode . '.'];

    list($table, $field) = explode( '.', $tableField );
    $arr_ts     = $conf_view['filter.'][$table . '.'][$field . '.'];



      //////////////////////////////////////////////////////
      //
      // Order the values

      // Get the values for ordering
    $arr_rowsTablefield = $arr_rows[$tableField];
    foreach ( $arr_rowsTablefield as $key => $row )
    {
      $arr_value[$key] = $row['value'];
    }
      // Get the values for ordering

      // Set DESC or ASC
    if ( strtolower( $arr_ts['order.']['orderFlag'] ) == 'desc' )
    {
      $order = SORT_DESC;
    }
    if ( strtolower( $arr_ts['order.']['orderFlag'] ) != 'desc' )
    {
      $order = SORT_ASC;
    }
      // Set DESC or ASC

      // Order the rows
    array_multisort( $arr_value, $order, $arr_rowsTablefield );
      // Order the values



    foreach ( ( array ) $arr_rowsTablefield as $key => $row )
    {
      $arr_tableFields[$row['uid']] = $row['value'];
    }

      // RETURN the ordered rows of the current tablefield
    return $arr_tableFields;
  }



  /**
 * get_treeOrdered: Get the elements ordered to the needs of a tree.
 *
 * @param   array     $arr_rows         : Result of the SQL query
 * @param   string    $tableField       : Current table.field
 * @return  array     $arr_tableFields  : Array with the values. Values are wrapped with ul- and li-tags.
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.6
 * @since   3.9.6
 */
  private function get_treeOrdered( $arr_rows, $tableField )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot  = $view . '.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode . '.'];

    list($table, $field) = explode( '.', $tableField );
    $arr_ts     = $conf_view['filter.'][$table . '.'][$field . '.'];

      // Parent uid of the root records: 0 of course
    $uid_parent = 0;
      // Current level of the treeview: 0 of course
    $level      = 0;
      // Needed for set_treeOneDim( )
    $this->arr_rowsTablefield = $arr_rows[$tableField];



      //////////////////////////////////////////////////////
      //
      // Order the values

      // Get the values for ordering
    foreach ( $this->arr_rowsTablefield as $key => $row )
    {
      $arr_value[$key] = $row['value'];
    }
      // Get the values for ordering

      // Set DESC or ASC
    if ( strtolower( $arr_ts['order.']['orderFlag'] ) == 'desc' )
    {
      $order = SORT_DESC;
    }
    if ( strtolower( $arr_ts['order.']['orderFlag'] ) != 'desc' )
    {
      $order = SORT_ASC;
    }
      // Set DESC or ASC

      // Order the rows
    array_multisort($arr_value, $order, $this->arr_rowsTablefield);
      // Order the values


    unset( $this->tmpOneDim );
      // Set rows of the current tablefield to a one dimensional array
    $this->set_treeOneDim( $tableField, $uid_parent );
      // Get the renderd tree. Each element of the returned array contains HTML tags.
    $arr_tableFields = $this->get_treeRendered( $arr_ts );
    unset( $this->tmpOneDim );


      // RETURN the ordered and rendered rows of the current tablefield
    return $arr_tableFields;
  }






  /**
 * set_treeOneDim:  Recursive method. It generates a one dimensional array.
 *                  Each array has upto three elements:
 *                  * [obligate] uid   : uid of the record
 *                  * [obligate] value : value of the record
 *                  * [optional] array : if the record has children ...
 * @param string    $tableField : Current table.field.
 * @param integer   $uid_parent : Parent uid of the current record - for recursive calls.
 *                                It is 0 while starting.
 * @return  void    Result will be allocated to the global $tmpOneDim
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.6
 * @since   3.9.6

 */
  private function set_treeOneDim( $tableField, $uid_parent )
  {
    static $tsPath = null;

      // LOOP rows
    foreach( $this->arr_rowsTablefield as $key => $row )
    {
        // CONTINUE current row isn't row with current $uid_parent
      if( $row['treeParentField'] != $uid_parent )
      {
        continue;
      }
        // CONTINUE current row isn't row with current $uid_parent

      $lastPath = $tsPath;
      $tsPath   = $tsPath . $key . '.' ;
      $this->tmpOneDim[$tsPath . 'uid']    = $row['uid'];
      $this->tmpOneDim[$tsPath . 'value']  = $row['value'];

      $this->set_treeOneDim( $tableField, $row['uid'] );
      $tsPath   = $lastPath;
    }
      // LOOP rows
  }






  /**
 * get_treeRendered:  Method converts a one dimensional array to a multidimensional array.
 *                    It wraps every element of the array with ul and or li tags.
 *                    Wrapping depends in position and level of the element in the tree.
 * @param   array     $arr_ts     : configuration of the current table.field.
 * @return  array     $arr_result : Array with the rendered elements
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.6
 * @since   3.9.6

 */
  private function get_treeRendered( $arr_ts )
  {
      // Render uid and value of the first item
    $first_item_uid   = $arr_ts['first_item.']['option_value'];
    $tsValue          = $arr_ts['first_item.']['value_stdWrap.']['value'];
    $tsConf           = $arr_ts['first_item.']['value_stdWrap.'];
    $first_item_value = $this->pObj->local_cObj->stdWrap( $tsValue, $tsConf );
      // Render uid and value of the first item

      // Add first item
    $tmpOneDim    = array( 'uid'   => $first_item_uid   ) +
                    array( 'value' => $first_item_value ) +
                    $this->tmpOneDim;
      // Add first item

      // Move one dimensional array to an iterator
    $tmpArray     = $this->pObj->objTyposcript->oneDim_to_tree( $tmpOneDim );
    $rcrsArrIter  = new RecursiveArrayIterator( $tmpArray );
    $iterator     = new RecursiveIteratorIterator( $rcrsArrIter );
      // Move one dimensional array to an iterator

      // Code for an item (an a-tag usually)
    $conf_item    = $arr_ts['wrap.']['item'];

      // HTML id
    $cObj_name  = $arr_ts['treeview.']['html_id'];
    $cObj_conf  = $arr_ts['treeview.']['html_id.'];
    $html_id    = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );



      //////////////////////////////////////////////////////
      //
      // Loop values

      // Initial depth
    $last_depth = -1;

      // LOOP
    foreach ($iterator as $key => $value)
    {
        // CONTINUE $key is the uid. Save the uid.
      if( $key == 'uid' )
      {
        $curr_uid = $value;
        continue;
      }
        // CONTINUE $key is the uid. Save the uid.

        // ERROR/CONTINUE $key isn't value
      if( $key != 'value' )
      {
        echo 'ERROR: key != value.' . PHP_EOL . __METHOD__ . ' (Line: ' . __LINE__ . ')' . PHP_EOL;
        continue;
      }
        // ERROR/CONTINUE $key isn't value

        // Render the value
      $value      = '###HITS_BEFORE###' . $value . '###HITS_BEHIND###';
      $value      = str_replace('###VALUE###', $value, $conf_item );
        // Render the value

        // Vars
      $curr_depth = $iterator->getDepth( );
      $indent     = str_repeat( '  ', ( $iterator->getDepth( ) + 1 ) );
        // Vars

        // Render the start tag
      switch( true )
      {
        case( $curr_depth > $last_depth ):
            // Start of sublevel
          $delta_depth  = $curr_depth - $last_depth;
          $startTag     = PHP_EOL . 
                          str_repeat
                          (
                            $indent . '<ul id="' . $html_id . '_ul_' . $curr_uid . '">' . PHP_EOL .
                            $indent . '  <li id="' . $html_id . '_li_' . $curr_uid . '">', $delta_depth
                          );
          $last_depth   = $curr_depth;
          break;
            // Start of sublevel
        case( $curr_depth < $last_depth ):
            // Stop of sublevel
          $delta_depth  = $last_depth - $curr_depth;
          $startTag     = '</li>' . PHP_EOL .
                          str_repeat
                          (
                            $indent .' </ul>' . PHP_EOL .
                            $indent . '</li>', $delta_depth
                          ) .
                          '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          $last_depth   = $curr_depth;
          break;
            // Stop of sublevel
        default:
          $startTag = '</li>' . PHP_EOL . 
                      $indent . '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          break;
      }
        // Render the start tag

        // String result for printing
      $str_result =  $str_result . $startTag . $curr_uid . ': ' . $value;

        // Result array
      $arr_result[$curr_uid] = $startTag . $value;
    }
      // LOOP
      // Loop values

      // Render the end tag of the last item
    $endTag =                 '</li>' .
                              str_repeat
                              (
                                '</ul>' . PHP_EOL . $indent . '</li>',
                                $curr_depth
                              ) .
                              PHP_EOL .
                              $indent . '</ul>' . PHP_EOL;
    $str_result =             $str_result . $endTag . PHP_EOL .
                              '</div>';
    $arr_result[$curr_uid] =  $arr_result[$curr_uid] . $endTag . '</div>';
      // Render the end tag of the last item

    $arr_result[$first_item_uid] =  '<div id="' . $html_id . '">' . $arr_result[$first_item_uid];
      // Development
//    $pos = strpos('79.204.110.26', t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__ . ' (' . __LINE__ . ')', $str_result );
//    }
      // Development

      // Development
//    $pos = strpos('79.204.110.26', t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__ . ' (' . __LINE__ . ')' );
//      echo "<pre>";
//      foreach ($iterator as $key => $value)
//      {
//        $indent = str_repeat( '  ', ( $iterator->getDepth( ) + 1 ) );
//        if( $key == 'uid')
//        {
//          $curr_uid = $value;
//          continue;
//        }
//        echo $iterator->getDepth() . $indent . $curr_uid . ': ' . $value . "\n";
//      }
//      echo "</pre>";
//    }
      // Development

      // RETURN the result
    return $arr_result;
  }




  /**
 * Get the wrapped item class
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param string    $conf_item: The current item wrap
 * @param string    $str_order: asc or desc
 * @return  string    Returns the wrapped item
 * @version 3.5.0
 */
  function get_wrappedItemClass($arr_ts, $conf_item, $str_order)
  {

    $conf_item_class = null;
    if (is_array($arr_ts['wrap.']))
    {
      if (is_array($arr_ts['wrap.']['item.']))
      {
        $conf_item_class = $arr_ts['wrap.']['item.']['class'];
      }
    }

    if ($str_order)
    {
      if (!empty ($arr_ts['wrap.']['item.']['class.'][$str_order]))
      {
        $conf_item_class = $arr_ts['wrap.']['item.']['class.'][$str_order];
      }
    }

    if ($conf_item_class)
    {
      $conf_item_class = ' class="' . $conf_item_class . '"';
    }

    $conf_item = str_replace('###CLASS###', $conf_item_class, $conf_item);

    return $conf_item;
  }

  /**
 * Get the wrapped item style
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param string    $conf_item: The current item wrap
 * @param string    $str_order: asc or desc
 * @return  string    Returns the wrapped item
 */
  function get_wrappedItemStyle($arr_ts, $conf_item, $str_order) {
    if (!$str_order) {
      $conf_item_style = $arr_ts['wrap.']['item.']['style'];
    }
    if ($str_order) {
      $conf_item_style = $arr_ts['wrap.']['item.']['style.'][$str_order];
    }
    if ($conf_item_style) {
      $conf_item_style = ' style="' . $conf_item_style . '"';
    }
    $conf_item = str_replace('###STYLE###', $conf_item_style, $conf_item);

    return $conf_item;
  }

  /**
 * get_wrappedItemKey: Wrap the key of the current value
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param integer   $uid: The item uid
 * @param string    $conf_item: The current item wrap
 * @return  string    Returns the wrapped item
 * @version 3.6.1
 */
  function get_wrappedItemKey($arr_ts, $uid, $conf_item) 
  {
    $str_uid = null;
    

    // #11844, dwildt, 110102
    if ($uid != $arr_ts['first_item.']['option_value']) 
    {
      $str_uid = htmlspecialchars($uid, ENT_QUOTES);
    }
    $conf_item = str_replace('###UID###', $str_uid, $conf_item);

    return $conf_item;
  }


















  /**
 * get_wrappedItemURL(): Get the URL for the item
 *
 * @param array   $arr_ts: The TypoScript configuration of the object
 * @param string    $tableField: table.field of the current filter
 * @param string    $value: value of the current filter
 * @param string    $conf_item: The current item wrap
 * @return  string    Returns the wrapped item
 * 
 * @version 3.6.4
 * @since 3.6.1
 */
  function get_wrappedItemURL($arr_ts, $tableField, $value, $conf_item)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    $arr_currPiVars  = $this->pObj->piVars;

      // 13920, 110319, dwildt
      // Set value of the first item to null: it won't become an additional parameter below
    if ($value == $arr_ts['first_item.']['option_value']) 
    {
      $value = null;
    }

      // 13920, 110319, dwildt
      // Move value (10, 20, 30, ...) to url_stdWrap (i.e: 2011_Jan, 2011_Feb, 2011_Mar, ...)
    $value = $this->pObj->objCal->area_get_urlPeriod($arr_ts, $tableField, $value);

      // Remove piVars temporarily
    $arr_removePiVars = array('sort', 'pointer');

      // Remove piVars['plugin'], if current plugin is trhe default plugin
      // #11576, dwildt, 101219
    if(!$this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin)
    {
      $arr_removePiVars[] = 'plugin';
    }
    foreach((array) $arr_removePiVars as $str_removePiVars)
    {
      if(isset($this->pObj->piVars[$str_removePiVars]))
      {
        unset($this->pObj->piVars[$str_removePiVars]);
      }
    }
      // Remove piVars temporarily


      // Move $GLOBALS['TSFE']->id temporarily
      // #9458
    $int_tsfeId = $GLOBALS['TSFE']->id;
    if (!empty($this->pObj->objFlexform->int_viewsListPid))
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Move $GLOBALS['TSFE']->id temporarily

      // Remove the filter fields temporarily
      // #9495, fsander
    $this->pObj->piVars = $this->pObj->objZz->removeFiltersFromPiVars($this->pObj->piVars, $conf_view['filter.']);
      // Remove the filter fields temporarily

    $additionalParams = null;
    foreach((array) $this->pObj->piVars as $paramKey => $paramValue)
    {
      if(!empty($paramValue))
      {
        $additionalParams = $additionalParams . '&' . $this->pObj->prefixId . '[' . $paramKey . ']=' . $paramValue;
      }
    }
    $additionalParams = $additionalParams . '&' . $this->pObj->prefixId . '[' . $tableField . ']=' . $value;
    $cHash_calc       = $this->pObj->objZz->get_cHash('&id=' . $GLOBALS['TSFE']->id . $additionalParams);

    $arr_typolink['parameter']        = $GLOBALS['TSFE']->id;
    $arr_typolink['additionalParams'] = $additionalParams.'&cHash='.$cHash_calc;
    $arr_typolink['returnLast']       = 'URL';

    $str_url    = $this->pObj->local_cObj->typoLink_URL($arr_typolink);
    $conf_item  = str_replace('###URL###', $str_url, $conf_item);

      // Reset $this->pObj->piVars
    $this->pObj->piVars   = $arr_currPiVars;
      // Reset $GLOBALS['TSFE']->id
    $GLOBALS['TSFE']->id  = $int_tsfeId;

    return $conf_item;
  }









  /**
 * Get the item selected
 *
 * @param integer   $uid: The item uid
 * @param string   $value: The item value
 * @param array   $arr_piVar: The array with the piVar or piVars
 * @param string    $conf_slected: The selected configuration from TS
 * @param string    $conf_item: The current item wrap
 * @return  string    Returns the wrapped item selected or not selected
 * 
 * @version 4.0.0
 * @since 3.6.0
 */
  function get_wrappedItemSelected($uid, $value, $arr_piVar, $arr_ts, $conf_selected, $conf_item) 
  {
    //$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
    //if ( ! ( $pos === false ) )
    //{
    //  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $this->pObj->objCal->selected_period, $arr_ts['area.']['interval.']['options.']['fields.'][$uid . '.']['value_stdWrap.']['value'], $arr_piVar, $this->pObj->piVars);
    //} 

      // dwildt, 110102
      // Workaround: Because of new feature to filter a local table field
    $bool_inArray = false;
    if( $uid )
    {
      if( in_array( $uid, $arr_piVar ) )
      {
        $bool_inArray = true;
      }
    }
    if( $value )
    {
      if( in_array( $value, $arr_piVar ) )
      {
        $bool_inArray = true;
      }
    }
      // #29444: 110902, dwildt+
// 120121, ???, value_stdWrap :TODO:
    $value_from_ts_area = $arr_ts['area.']['interval.']['options.']['fields.'][$uid . '.']['value_stdWrap.']['value'];
    if( $value_from_ts_area )
    {
      if( in_array( $value_from_ts_area, $arr_piVar ) )
      {
        $bool_inArray = true;
      }
    }
    if( empty ( $arr_piVar ) )
    {
      if( $this->pObj->objCal->selected_period )
      {
        if( $this->pObj->objCal->selected_period == $value_from_ts_area )
        {
          $bool_inArray = true;
        }
      }
    }
      // #29444: 110902, dwildt+
    if( ! $bool_inArray )
    {
      $conf_selected = null;
    }
    $conf_item = str_replace( '###ITEM_SELECTED###', $conf_selected, $conf_item );
      #8337

    return $conf_item;
  }

  /**
 * Wrap all items (wrap the object)
 *
 * @param string    $obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param array   $arr_ts: The current TS configuration of the obkject
 * @param string    $str_nice_piVar: The nice name for the current piVar
 * @param string    $key_piVar: The real name of the piVar
 * @param integer   $number_of_items: The number of items
 * @return  string    Returns the wrapped items/object
 * @version 3.9.6
 * @sice    3.0.1
 */
  private function wrap_allItems($obj_ts, $arr_ts, $str_nice_piVar, $key_piVar, $number_of_items) {

    // #8337, 101011, dwildt
    switch ($obj_ts) {
      case ('CHECKBOX') :
        $conf_size = null;
        $conf_multiple = true;
        break;
      case ('CATEGORY_MENU') :
      case ('RADIOBUTTONS') :
        $conf_size = null;
        $conf_multiple = false;
        break;
      case ('SELECTBOX') :
        $conf_size = $arr_ts['size'];
        #3.4.904
        if ($conf_size < 2) {
          $conf_multiple = 0;
        }
        if ($conf_size >= 2) {
          if ($arr_ts['multiple'] == 1) {
            $conf_multiple = ' ' . $arr_ts['multiple.']['selected'];
          }
        }
        break;
      default :
        $conf_size = null;
        $conf_multiple = false;
        if ($this->pObj->b_drs_error) {
          t3lib_div :: devlog('[ERROR/FILTER] multiple - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
          t3lib_div :: devlog('[INFO/FILTER] multiple becomes false.', $this->pObj->extKey, 3);
        }
    }
    $conf_object = $arr_ts['wrap.']['object'];
    // Remove empty class
    $conf_object = str_replace(' class=""', null, $conf_object);

    $int_space_left = $arr_ts['wrap.']['object.']['nice_html_spaceLeft'];
    $str_space_left = str_repeat(' ', $int_space_left);
    $conf_object = $str_space_left . $conf_object;
    $str_uid = $this->pObj->prefixId . '_' . $str_nice_piVar;
    $str_uid = str_replace('.', '_', $str_uid);
    $conf_object = str_replace('###TABLE.FIELD###', $key_piVar, $conf_object);
    $conf_object = str_replace('###ID###', $str_uid, $conf_object);
    $conf_object = str_replace('###SIZE###', $conf_size, $conf_object);
    $conf_object = str_replace('###MULTIPLE###', $conf_multiple, $conf_object);

    // DRS - Development Reporting System
    if (empty ($conf_object)) {
      if ($this->pObj->b_drs_warn) {
        t3lib_div :: devlog('[WARN/TEMPLATING] wrap_allItems returns an empty value for ' . $obj_ts, $this->pObj->extKey, 2);
      }
    }
    // DRS - Development Reporting System

    return $conf_object;
  }

  /**
 * get_tableFields(): Set the global arr_conf_tableFields
 *
 * @return  boolean   FALSE: filters are set. TRUE: filters aren't set or there is a ts config error
 * @version 3.5.0
 */
  function get_tableFields() {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    /////////////////////////////////////////////////////////////////
    //
    // Return TRUE, if we don't have any filter array

    if (!is_array($conf_view['filter.'])) {
      if ($this->pObj->b_drs_filter) {
        t3lib_div :: devlog('[INFO/FILTER] ' . $viewWiDot . $mode . '.filters isn\'t an array. There isn\'t any filter for processing.', $this->pObj->extKey, 0);
      }
      return true;
    }
    // Return TRUE, if we don't have any filter array

    /////////////////////////////////////////////////////////////////
    //
    // Loop through the filter array and get all table.field

    $arr_tableFields = false;
    foreach ($conf_view['filter.'] as $tables => $str_field) {
      while ($value = current($str_field)) {
        // If $str_field hasn't any dot, it is a field and it isn't an array
        if (substr(key($str_field), -1) != '.') {
          $this->arr_conf_tableFields[] = trim($tables) . key($str_field);
        }
        next($str_field);
      }
    }
    // Loop through the filter array and get all table.field

    /////////////////////////////////////////////////////////////////
    //
    // Return TRUE, if there is a ts config error

    if (!is_array($this->arr_conf_tableFields)) {
      if ($this->pObj->b_drs_error) {
        t3lib_div :: devlog('[ERROR/FILTER] ' . $viewWiDot . $mode . '.filters hasn\'t any table.field syntax.' .
        ' This is an error.', $this->pObj->extKey, 3);
      }
      return true;
    }
    // Return TRUE, if we there is a ts config error

    /////////////////////////////////////////////////////////////////
    //
    // Add table.fields to the select statement

    // Loop each filter (table.field)
    foreach ($this->arr_conf_tableFields as $tableField) {
      list ($table, $field) = explode('.', $tableField);
      $field = 'uid';
      $tableField = $table . '.' . $field;

      // addedTableFields
      $bool_was_registered = true;
      if (!is_array($this->pObj->arrConsolidate['addedTableFields'])) {
        $this->pObj->arrConsolidate['addedTableFields'][] = $tableField;
        $bool_was_registered = false;
        if ($this->pObj->b_drs_filter) {
          t3lib_div :: devlog('[INFO/FILTER] Table ' . $table . '.' . $field . ' is added to arrConsolidate[addedTableFields].', $this->pObj->extKey, 0);
        }
      }
      if (is_array($this->pObj->arrConsolidate['addedTableFields'])) {
        if ($tableField) {
          if (!in_array($tableField, $this->pObj->arrConsolidate['addedTableFields'])) {
            $this->pObj->arrConsolidate['addedTableFields'][] = $tableField;
            $bool_was_registered = false;
            if ($this->pObj->b_drs_filter) {
              t3lib_div :: devlog('[INFO/FILTER] Table ' . $table . '.' . $field . ' is added to arrConsolidate[addedTableFields].', $this->pObj->extKey, 0);
            }
          }
        }
      }
      // addedTableFields

      // arr_realTables_arrFields
      if (!is_array($this->pObj->arr_realTables_arrFields[$table])) {
        $this->pObj->arr_realTables_arrFields[$table][] = $field;
        if ($this->pObj->b_drs_filter) {
          t3lib_div :: devlog('[INFO/FILTER] Table ' . $table . '.' . $field . ' is added to arr_realTables_arrFields.', $this->pObj->extKey, 0);
        }
      }
      if (is_array($this->pObj->arr_realTables_arrFields[$table])) {
        if (!in_array($field, $this->pObj->arr_realTables_arrFields[$table])) {
          $this->pObj->arr_realTables_arrFields[$table][] = $field;
          if ($this->pObj->b_drs_filter) {
            t3lib_div :: devlog('[INFO/FILTER] Field ' . $table . '.' . $field . ' is added to arr_realTables_arrFields.', $this->pObj->extKey, 0);
          }
        }
      }
      // arr_realTables_arrFields

      // add to the select query
      //      if(!$bool_was_registered)
      //      {
      if (strpos($this->pObj->conf_sql['select'], $tableField) === false) {
        $this->pObj->conf_sql['select'] = $this->pObj->conf_sql['select'] . ', ' .
        $tableField . ' AS \'' . $tableField . '\'';
        if ($this->pObj->b_drs_filter) {
          t3lib_div :: devlog('[INFO/FILTER] ' . $table . '.' . $field . ' is added to $this->pObj->conf_sql[select].', $this->pObj->extKey, 0);
        }
      }
      //      }
      // add to the select query
      $this->pObj->csvSelectWoFunc = $this->pObj->csvSelectWoFunc . ', ' . $tableField;
    }
    // Loop each filter (table.field)
    // Add table.fields to the select statement

    return false;

  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter.php']);
}
?>