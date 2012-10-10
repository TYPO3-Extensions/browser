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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * The class tx_browser_pi1_filter bundles methods for rendering and processing filters and category menues.
 * 4x means: with Browser engine 4.x
 *
 * @author       Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package      TYPO3
 * @subpackage   browser
 *
 * @version      4.1.21
 * @since        3.9.9
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  188: class tx_browser_pi1_filter_4x
 *  267:     function __construct( $pObj )
 *
 *              SECTION: Main
 *  301:     public function get( )
 *
 *              SECTION: Init and reset
 *  406:     private function init( )
 *  458:     private function init_andWhereFilter( )
 *  580:     private function init_andWhereFilter_localTable($arr_piVar, $tableField)
 *  682:     private function init_andWhereFilter_manualMode( $arr_piVar, $tableField, $conf_view )
 *  736:     private function init_andWhereFilter_foreignTable( $arr_piVar, $tableField )
 *  824:     public function get_selectedFilters( )
 *  872:     private function init_boolIsFilter( )
 *  910:     private function init_calendarArea( )
 *  934:     private function init_consolidationAndSelect( )
 *  961:     private function init_consolidationAndSelect_setArrayConsolidation( )
 * 1001:     private function init_consolidationAndSelect_setTsSelect( )
 * 1050:     private function init_consolidationAndSelect_isFilterArray( )
 * 1081:     private function init_consolidationAndSelect_isTableFields( )
 * 1134:     private function init_localisation( )
 * 1183:     private function init_reset( )
 *
 *              SECTION: Filter rendering
 * 1212:     private function get_filter( )
 * 1276:     private function get_filterItems( )
 * 1353:     private function get_filterItemsFromRows( )
 * 1419:     private function get_filterItemsDefault( )
 * 1477:     private function get_filterItemsTree( )
 * 1585:     private function get_filterItemsWrap( $items )
 * 1689:     private function get_filterItem( $uid, $value )
 * 1794:     private function get_filterItemValueStdWrap( $conf_name, $conf_array, $uid, $value )
 * 1883:     private function get_filterItemCObj( $uid, $value )
 * 2129:     private function get_filterTitle( )
 * 2245:     private function get_filterWrap( $items )
 *
 *              SECTION: Areas
 * 2300:     private function areas_toRows( )
 * 2336:     private function areas_toRowsConverter( $areas )
 * 2386:     private function areas_countHits( $areas )
 * 2448:     private function areas_wiHitsOnly( $areas )
 *
 *              SECTION: Rows
 * 2500:     private function get_rows( )
 * 2541:     private function get_rowsWiHits( )
 * 2579:     private function get_rowsAllItems( $rows_wiHits )
 *
 *              SECTION: SQL ressources
 * 2658:     private function sql_resAllItems( )
 * 2696:     private function sql_resSysLanguageRows( )
 * 2752:     private function sql_resWiHits( )
 *
 *              SECTION: SQL ressources to rows
 * 2804:     private function sql_resToRows( $res )
 * 2838:     private function sql_resToRows_allItemsWiHits( $res, $rows_wiHits )
 *
 *              SECTION: SQL statements - select
 * 2904:     private function sql_select( $bool_count )
 * 2976:     private function sql_select_addLL( )
 * 3005:     private function sql_select_addLL_sysLanguage( )
 * 3074:     private function sql_select_addLL_langOl(  )
 * 3134:     private function sql_select_addTreeview( )
 *
 *              SECTION: SQL statements - from, groupBy, orderBy, limit
 * 3237:     private function sql_from( )
 * 3276:     private function sql_groupBy( )
 * 3295:     private function sql_orderBy( )
 * 3346:     private function sql_limit( )
 *
 *              SECTION: SQL statements - where
 * 3379:     private function sql_whereAllItems( )
 * 3405:     private function sql_whereWiHits( )
 * 3432:     private function sql_whereWiHitsLL( $where )
 * 3466:     private function sql_whereAnd_enableFields( )
 * 3486:     private function sql_whereAnd_Filter( )
 * 3528:     private function sql_whereAnd_fromTS( )
 * 3554:     private function sql_whereAnd_pidList( )
 * 3601:     private function sql_whereAnd_sysLanguage( )
 *
 *              SECTION: cObject
 * 3669:     private function cObjData_init( )
 * 3709:     private function cObjData_reset( )
 * 3732:     private function cObjData_setFlagDisplayInCaseOfNoCounting( )
 * 3767:     private function cObjData_unsetFlagDisplayInCaseOfNoCounting( )
 * 3802:     private function cObjData_setFlagTreeview( )
 * 3829:     private function cObjData_unsetFlagTreeview( )
 * 3859:     private function cObjData_updateRow( $uid )
 *
 *              SECTION: Localisation
 * 3934:     private function localise( )
 * 3978:     private function localise_langOl( )
 * 4003:     private function localise_langOlWiPrefix( )
 * 4075:     private function localise_langOlWoPrefix( )
 * 4117:     private function localise_sysLanguage( )
 *
 *              SECTION: TypoScript values
 * 4203:     private function ts_getAreas( )
 * 4274:     private function ts_getCondition( )
 * 4333:     private function ts_getDisplayHits( )
 * 4357:     private function ts_getDisplayInCaseOfNoCounting( )
 * 4381:     private function ts_countHits( )
 *
 *              SECTION: Tree view helper
 * 4438:     private function tree_setOneDim( $uid_parent )
 * 4470:     private function tree_setOneDimOneRow( $uid_parent )
 * 4503:     private function tree_setOneDimDefault( $uid_parent )
 * 4557:     private function tree_getRendered( )
 *
 *              SECTION: Replace marker
 * 4796:     private function replace_itemClass( $conf_array, $item )
 * 4834:     private function replace_itemSelected( $conf_array, $uid, $value, $item )
 * 4911:     private function replace_itemStyle( $conf_array, $item )
 * 4943:     private function replace_itemTitle( $item )
 * 4980:     private function replace_itemUid( $uid, $item )
 * 5001:     private function replace_itemUrl( $conf_array, $uid, $item )
 * 5147:     private function replace_marker( $coa_conf )
 *
 *              SECTION: Maximum items per HTML row
 * 5184:     private function set_maxItemsPerHtmlRow( )
 * 5245:     private function set_itemCurrentNumber( )
 * 5271:     private function get_maxItemsTagEndBegin( $item )
 * 5327:     private function get_maxItemsWrapBeginEnd( $items )
 *
 *              SECTION: Hits helper
 * 5377:     private function set_hits( $uid, $value, $row )
 * 5480:     private function sum_hits( $rows )
 * 5557:     private function set_markerArray( )
 * 5579:     private function set_markerArrayUpdateRow( $uid )
 *
 *              SECTION: Requirements
 * 5646:     private function requiredMarker( $tableField )
 *
 *              SECTION: Other helper
 * 5698:     private function set_currFilterIsArea( )
 * 5736:     private function set_firstItem( )
 * 5806:     private function set_firstItemTreeView( )
 * 5837:     private function set_htmlSpaceLeft( )
 * 5864:     private function set_nicePiVar( )
 * 5953:     private function updateWizard( $check )
 * 6064:     function zz_getNicePiVar( $tableField )
 *
 * TOTAL FUNCTIONS: 101
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_filter_4x {


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


    // [Boolean] true: don't localise the current SQL query, false: localise it
  var $bool_dontLocalise      = null;
    // [Boolean] true: there is a ts filter array with tableFields
  var $bool_isFilter          = null;
    // [Array] Back up of cObject data
  var $cObjDataBak            = null;
    // [Integer] number of the localisation mode
  var $int_localisation_mode  = null;
    // [String] Current table
  var $curr_tableField        = null;
    // [Array] current marker array
  var $markerArray            = null;
    // [Array] tables with the fields, which are used in the SQL query
  var $sql_filterFields       = null;
    // [String] andWhere statement, if a filter is set
  var $andWhereFilter         = null;

    // [Array] Rows of the current filter
  var $rows = null;
    // [Array] Sum of hits per tableField. tableField is the element
  var $hits_sum = array( );
    // [Boolean] true: current filter has areas, false: current filter hasn't areas
  var $bool_currFilterIsArea = null;

    // [Array] nice piVar array for the current filter / tableField
  var $nicePiVar = null;

    // [String] Space of the left HTML margin
  var $htmlSpaceLeft = null;
    // [Array] Array with elements maxItemsPerHtmlRow, rowBegin, rowEnd, noItemValue
  var $itemsPerHtmlRow = null;

    // [Array] Array with selected filters
  var $arr_selectedFilters = null;

    // [Array] all filter tableFields
  var $arr_tsFilterTableFields = null;
    // [Array]
  var $arr_filter_condition = null;
    // #41776, dwildt, 2+
    // [Array] Tables with a treeParentField field
  var $arr_tablesWiTreeparentfield  = array( );











  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct( $pObj )
  {
    $this->pObj = $pObj;
  }









 /***********************************************
  *
  * Main
  *
  **********************************************/









/**
 * get( ):  Get filters. Returns a marker array or an error message.
 *
 * @return	array		$arr_return : $arr_return['data']['marker']['###TABLE.FIELD###']
 * @version 3.9.9
 * @since   3.9.9
 */
  public function get( )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Default return value
    $arr_return = array( );
    $arr_return['data']['marker'] = array( );

      // RETURN there isn't any filter
    if( ! is_array ( $this->conf_view['filter.'] ) )
    {
        // DRS
      if ($this->pObj->b_drs_filter)
      {
        $prompt = $this->conf_path . 'filters isn\'t an array. There isn\'t any filter for processing.';
        t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
      }
        // DRS
      return $arr_return;
    }
      // RETURN there isn't any filter

      // Init localisation
    $this->init( );

      // LOOP each filter
    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( array_keys ( ( array ) $fields ) as $field )
      {
          // CONTINUE : field has an dot
        if( rtrim($field, '.') != $field )
        {
          continue;
        }
          // CONTINUE : field has an dot

          // Class var table.field
        $this->curr_tableField = $tableWiDot . $field;

          // Get table
        list( $table ) = explode( '.', $this->curr_tableField );

          // CONTINUE : marker is missing in the HTML template
        if( ! $this->requiredMarker( $this->curr_tableField ) )
        {
          continue;
        }
          // CONTINUE : marker is missing in the HTML template

          // Load TCA
        $this->pObj->objZz->loadTCA( $table );

        $arr_result = $this->get_filter( );
        if( $arr_result['error']['status'] )
        {
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
          return $arr_result;
        }
        $arr_return['data']['filter'] = ( array ) $arr_return['data']['filter'] + $arr_result['data']['marker'];
        unset( $arr_result );
      }
    }
      // LOOP each filter

      // Reset some vars
    $this->init_reset( );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    return $arr_return;
  }









 /***********************************************
  *
  * Init and reset
  *
  **********************************************/



/**
 * init( ): Inits class vars like
 *          * localisation mode
 *          * calendar area
 *          * marker Array
 *          and other vars like
 *          * cObj->data
 *
 * @return	void
 * @version 4.1.21
 * @since   3.9.9
 */
  private function init( )
  {
      // #41776, dwildt, 1-
//    $this->pObj->objFltr3x->get_tableFields( );

    $this->init_boolIsFilter( );

      // RETURN: if there isn't any filter array
    if( ! $this->init_consolidationAndSelect_isFilterArray( ) )
    {
      return;
    }
      // RETURN: if there isn't any filter array

      // RETURN: if there isn't any table.field configured
    if( ! $this->init_consolidationAndSelect_isTableFields( ) )
    {
      return;
    }
      // RETURN: if there isn't any table.field configured

      // #41776, dwildt, 2+
      // Set the array consolidation and the ts property SELECT
    $this->init_consolidationAndSelect( );

      // Init localisation
    $this->init_localisation( );

      // Init calendar area
    $this->init_calendarArea( );

      // Init class var $andWhereFilter
    $this->init_andWhereFilter( );

      // Set class var markerArray
    $this->set_markerArray( );

      // Init the data of the cObj
    $this->cObjData_init( );

    return;
  }



/**
 * init_andWhereFilter( ): Set and returns the SQL andWhere statement
 *
 * @return	string		$this->andWhereFilter : the SQL andWhere statement
 * @version 4.1.21
 * @since   3.9.12
 */
  private function init_andWhereFilter( )
  {
      // RETURN : $this->andWhereFilter was set before
    if( ! ( $this->andWhereFilter === null ) )
    {
      return $this->andWhereFilter;
    }
      // RETURN : $this->andWhereFilter was set before

      // RETURN : there isn't any filter
    if( ! $this->bool_isFilter )
    {
      $this->andWhereFilter = false;
      return $this->andWhereFilter;
    }
      // RETURN : there isn't any filter

    $arr_andWhereFilter = null;

      // Init area
    $this->pObj->objCal->area_init( );
    $conf       = $this->pObj->conf;
    $conf_view  = $conf['views.'][$viewWiDot][$mode . '.'];
      // Init area

      // LOOP: filter tableFields
    foreach( $this->arr_tsFilterTableFields as $tableField )
    {
      list ($table, $field) = explode('.', $tableField);
      $str_andWhere         = null;

        // Get nice_piVar
      $arr_result   = $this->zz_getNicePiVar( $tableField );
      $arr_piVar    = $arr_result['data']['arr_piVar'];
      unset ($arr_result);
        // Get nice_piVar

        // CONTINUE : There isn't any piVar
      if ( empty( $arr_piVar ) )
      {
        continue;
      }
        // CONTINUE : There isn't any piVar

        // SWITCH : manual mode versus auto mode
      switch( true )
      {
        case( $this->pObj->b_sql_manual ):
            // SQL manual mode
          $str_andWhere = $this->init_andWhereFilter_manualMode( $arr_piVar, $tableField, $conf_view );
          break;
            // SQL manual mode
        case( ! $this->pObj->b_sql_manual ):
        default:
            // SQL auto mode
            // SWITCH : local table versus foreign table
          switch( true )
          {
            case( $table == $this->pObj->localTable ):
              $str_andWhere = $this->init_andWhereFilter_localTable( $arr_piVar, $tableField);
              break;
            case( $table != $this->pObj->localTable ):
            default:
              $str_andWhere = $this->init_andWhereFilter_foreignTable( $arr_piVar, $tableField);
              break;
          }
            // SWITCH : local table versus foreign table
          break;
            // SQL auto mode
      }
        // SWITCH : manual mode versus auto mode

      if( ! empty( $str_andWhere ) )
      {
        $arr_andWhereFilter[$table . '.' . $field] = $str_andWhere;
      }
        // Build the andWhere statement
    }
      // LOOP: filter tableFields

      // andWhere statement
    $strAndWhere = implode(" AND ", ( array ) $arr_andWhereFilter );

      // RETURN : there isn't any andWhere statement
    if( empty( $strAndWhere ) )
    {
      $this->andWhereFilter = false;
      return $this->andWhereFilter;
    }
      // RETURN : there isn't any andWhere statement

    $this->andWhereFilter = " AND ". $strAndWhere;

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
    {
      if( is_array( $arr_andWhereFilter ) )
      {
        $prompt = 'andWhere statement: ' . $this->andWhereFilter;
        t3lib_div :: devlog( '[INFO/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
      // DRS

    return $this->andWhereFilter;
  }



  /**
 * init_andWhereFilter_foreignTable: Generate the andWhere statement for a field from a foreign table.
 *                        If there is an area, it will be handled
 *
 *                        Method is enhanced with a php array for allocate conditions
 *
 * @param	array		$arr_piVar   Current piVars
 * @param	string		$tableField   Current table.field
 * @return	array		arr_andWhereFilter: NULL if there isn' any filter
 * @internal              #30912: Filter: count items with no relation to category:
 * @version 4.1.21
 * @since   3.6.0
 */
  private function init_andWhereFilter_foreignTable( $arr_piVar, $tableField )
  {
    $str_andWhere = null;

    list( $table ) = explode( '.', $tableField );

      // SWITCH  : area filter versus default filter
    switch( true )
    {
      case( is_array( $this->pObj->objCal->arr_area[$tableField] ) ):
          // Handle area filter
        $str_andWhere = $this->init_andWhereFilter_foreignTableArea( $arr_piVar, $tableField );
        break;
      default:
          // Handle default filter (without area)
        $str_uidList  = implode(', ', $arr_piVar);
        $str_andWhere = $table . '.uid IN (' . $str_uidList . ')' . PHP_EOL;
          // #30912, 120127, dwildt+
        $this->arr_filter_condition[$table . '.uid']['uid_in_list'] = $arr_piVar;
        break;
    }
      // SWITCH  : area filter versus default filter

    return $str_andWhere;
  }



  /**
 * init_andWhereFilter_foreignTableArea: 
 *
 * @param	array		$arr_piVar   Current piVars
 * @param	string		$tableField   Current table.field
 * @return	array		arr_andWhereFilter: NULL if there isn' any filter
 * @internal              #30912: Filter: count items with no relation to category:
 * @version 4.1.21
 * @since   3.6.0
 */
  private function init_andWhereFilter_foreignTableArea( $arr_piVar, $tableField )
  {
    $str_andWhere = null;

    list ($table, $field) = explode('.', $tableField);
    $conf_array           = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // LOOP : each piVar
    foreach( ( array ) $arr_piVar as $str_piVar )
    {
        // 13920, 110319, dwildt
        // Move url value to tsKey
      $str_piVar      = $this->pObj->objCal->area_get_tsKey_from_urlPeriod($tableField, $str_piVar);

      $arr_item       = null;
      $str_key        = $this->pObj->objCal->arr_area[$tableField]['key']; // I.e strings
      $arr_currField  = $conf_array['area.'][$str_key . '.']['options.']['fields.'][$str_piVar . '.'];

      $from       = $arr_currField['valueFrom_stdWrap.']['value'];
      $from_conf  = $arr_currField['valueFrom_stdWrap.'];
      $from_conf  = $this->pObj->objZz->substitute_t3globals_recurs($from_conf);
      $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);
      if( ! empty( $from ) )
      {
        $arr_item[] = $tableField . " >= '" . mysql_real_escape_string($from) . "'";
          // #30912, 120127, dwildt+
        $this->arr_filter_condition[$tableField]['from'] = mysql_real_escape_string( $from );
      }

      $to         = $arr_currField['valueTo_stdWrap.']['value'];
      $to_conf    = $arr_currField['valueTo_stdWrap.'];
      $to_conf    = $this->pObj->objZz->substitute_t3globals_recurs($to_conf);
      $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);
      if( ! empty( $to ) )
      {
        $arr_item[] = $tableField . " <= '" . mysql_real_escape_string($to) . "'";
          // #30912, 120127, dwildt+
        $this->arr_filter_condition[$tableField]['to'] = mysql_real_escape_string( $to );
      }

      if( is_array( $arr_item ) )
      {
        $arr_orValues[] = '(' . implode(' AND ', $arr_item) . ') ';
      }
    }
      // LOOP : each piVar
    
    $str_andWhere = implode(' OR ', $arr_orValues);
    if( ! empty( $str_andWhere ) )
    {
      $str_andWhere = ' (' . $str_andWhere . ')';
    }

    return $str_andWhere;
  }



/**
 * init_andWhereFilter_localTable: Generate the andWhere statement for a field from the localtable.
 *                      If there is an area, it will be handled
 *
 *                        Method is enhanced with a php array for allocate conditions
 *
 * @param	array		$arr_piVar   Current piVars
 * @param	string		$tableField   Current table.field
 * @return	array		arr_andWhereFilter: NULL if there isn' any filter
 * @internal              #30912: Filter: count items with no relation to category:
 * @version 4.1.21
 * @since   2.x
 */
  private function init_andWhereFilter_localTable($arr_piVar, $tableField)
  {
    $str_andWhere = null;

      // SWITCH  : area filter versus default filter
$this->pObj->dev_var_dump( $tableField, is_array( $this->pObj->objCal->arr_area[$tableField] ) );
    switch( true )
    {
      case( is_array( $this->pObj->objCal->arr_area[$tableField] ) ):
          // Handle area filter
        $str_andWhere = $this->init_andWhereFilter_localTableArea( $arr_piVar, $tableField );
        break;
      default:
          // Handle default filter (without area)
        foreach( $arr_piVar as $str_value )
        {
          $arr_orValues[] = $tableField . " LIKE '" . mysql_real_escape_string( $str_value ) . "'";
            // #30912, 120127, dwildt+
            // #30912, 120202, dwildt+
          $strtolower_value = "'" . mb_strtolower( mysql_real_escape_string( $str_value ) ) . "'";
          $this->arr_filter_condition[$tableField]['like'][] = $strtolower_value;
        }
        $str_andWhere = implode( ' OR ', $arr_orValues );
        if( ! empty( $str_andWhere ) )
        {
          $str_andWhere = ' (' . $str_andWhere . ')';
        }
        break;
    }
      // SWITCH  : area filter versus default filter

    return $str_andWhere;


  }



/**
 * init_andWhereFilter_localTableArea( )
 *
 * @param	array		$arr_piVar   Current piVars
 * @param	string		$tableField   Current table.field
 * @return	array		arr_andWhereFilter: NULL if there isn' any filter
 * @internal              #30912: Filter: count items with no relation to category:
 * @version 4.1.21
 * @since   2.x
 */
  private function init_andWhereFilter_localTableArea( $arr_piVar, $tableField )
  {
    $str_andWhere = null;

    list ($table, $field) = explode('.', $tableField);
    $conf_array           = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // LOOP : each piVar
    foreach ( $arr_piVar as $str_piVar )
    {
        // 13920, 110319, dwildt
        // Move url value to tsKey
      $str_piVar      = $this->pObj->objCal->area_get_tsKey_from_urlPeriod($tableField, $str_piVar);

      $arr_item       = null;
      $str_key        = $this->pObj->objCal->arr_area[$tableField]['key']; // I.e strings
      $arr_currField  = $conf_array['area.'][$str_key . '.']['options.']['fields.'][$str_piVar . '.'];

      $from       = $arr_currField['valueFrom_stdWrap.']['value'];
      $from_conf  = $arr_currField['valueFrom_stdWrap.'];
      $from_conf  = $this->pObj->objZz->substitute_t3globals_recurs($from_conf);
      $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);
      if( ! empty( $from ) )
      {
        $arr_item[] = $tableField . " >= '" . mysql_real_escape_string($from) . "'";
          // #30912, 120127, dwildt+
        $this->arr_filter_condition[$tableField]['from'] = mysql_real_escape_string( $from );
      }

      $to         = $arr_currField['valueTo_stdWrap.']['value'];
      $to_conf    = $arr_currField['valueTo_stdWrap.'];
      $to_conf    = $this->pObj->objZz->substitute_t3globals_recurs($to_conf);
      $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);
      if( ! empty( $to ) )
      {
        $arr_item[] = $tableField . " <= '" . mysql_real_escape_string($to) . "'";
          // #30912, 120127, dwildt+
        $this->arr_filter_condition[$tableField]['to'] = mysql_real_escape_string( $to );
      }

      if( is_array( $arr_item ) )
      {
        $arr_orValues[] = '(' . implode(' AND ', $arr_item) . ') ';
      }
    }
      // LOOP : each piVar
    
    $str_andWhere = implode(' OR ', ( array) $arr_orValues );
    if( ! empty( $str_andWhere ) )
    {
      $str_andWhere = ' (' . $str_andWhere . ')';
    }

    return $str_andWhere;
  }




/**
 * init_andWhereFilter_manualMode:
 *
 * @param	[type]		$$arr_piVar: ...
 * @param	[type]		$tableField: ...
 * @param	[type]		$conf_view: ...
 * @return	array		arr_andWhereFilter: NULL if there isn' any filter
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_andWhereFilter_manualMode( $arr_piVar, $tableField, $conf_view )
  {
    list( $table ) = explode( '.', $tableField );

      // List of record uids
    $csvUids = implode( ', ', $arr_piVar );

      // Get table alias
    $arrTableAliases  = $conf_view['aliases.']['tables.'];
    $arrTableAliases  = array_flip( $arrTableAliases );
    $strTableAlias    = $arrTableAliases[ $table ];
      // Get table alias

    if( $strTableAlias )
    {
      $strAndWhere = $strTableAlias . '.uid IN (' . $csvUids . ')' . PHP_EOL;
      return $strAndWhere;
    }

      // DRS
    $prompt = 'There is no alias for table \'' . $table . '\'';
    t3lib_div :: devlog( '[ERROR/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 3 );
    $prompt = 'Browser is in SQL manual mode.';
    t3lib_div :: devlog( '[INFO/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Please configure aliases.tables of this view.';
    t3lib_div :: devlog( '[HELP/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 1 );
      // DRS

    echo '<h1>ERROR</h1>
      <h2>There is no table alias</h2>
      <p>Please see the logs in the DRS - Development Reporting System.</p>
      <p>Method ' . __METHOD__ . '</p>
      <p>Line ' . __LINE__ . ' </p>
      <p><br /></p>
      <p>This is a message of the Browser - TYPO3 without PHP.</p>
      ';
    exit;
  }



/**
 * init_boolIsFilter( ):
 *
 * @return	void
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_boolIsFilter( )
  {
      // RETURN : $this->bool_isFilter was set before
    if( ! ( $this->bool_isFilter === null ) )
    {
      return $this->bool_isFilter;
    }
      // RETURN : $this->bool_isFilter was set before

    $this->bool_isFilter = true;

      // FALSE: if there isn't any filter array
    if( ! $this->init_consolidationAndSelect_isFilterArray( ) )
    {
      $this->bool_isFilter = false;
    }
      // FALSE: if there isn't any filter array

      // FALSE: if there isn't any table.field configured
    if( ! $this->init_consolidationAndSelect_isTableFields( ) )
    {
      $this->bool_isFilter = false;
    }
      // FALSE: if there isn't any table.field configured

    return $this->bool_isFilter;
  }



/**
 * init_calendarArea( ):  If a filter has a area, this method inits the array with
 *                        the area items in the TS configuration of each filter.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init_calendarArea( )
  {

      // Init area
    $this->pObj->objCal->area_init( );

      // Reinit class vars $conf and $conf_view
    $this->conf       = $this->pObj->conf;
    $this->conf_view  = $this->conf['views.'][$this->view . '.'][$this->mode . '.'];
      // Reinit class vars $conf and $conf_view

    return;
  }



/**
 * init_consolidationAndSelect(): Set the array consolidation and the ts property SELECT
 *
 * @return	void
 * @internal  It was get_tableFields( ) in version 3.x
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_consolidationAndSelect( )
  {
      // RETURN : there isn't any filter
    if( ! $this->bool_isFilter )
    {
      return;
    }
      // RETURN : there isn't any filter

      // Add tableUids to the consolidation array
    $this->init_consolidationAndSelect_setArrayConsolidation( );

      // Add tableFields to the ts property SELECT
    $this->init_consolidationAndSelect_setTsSelect( );

  }



/**
 * init_consolidationAndSelect_setArrayConsolidation( ): Adds tableUid to the consolidation array
 *
 * @return	void
 * @internal  #41776
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_consolidationAndSelect_setArrayConsolidation( )
  {

      // LOOP : each filter (table.field)
    foreach( ( array ) $this->arr_tsFilterTableFields as $tableField )
    {
      list( $table ) = explode( '.', $tableField );
      $tableUid = $table . '.uid';

        // CONTINUE : $arrConsolidation contains the current tableUid
      if( in_array( $tableUid, ( array ) $this->pObj->arrConsolidate['addedTableFields'] ) )
      {
        continue;
      }
        // CONTINUE : $arrConsolidation contains the current tableUid

        // Add current tableUid
      $this->pObj->arrConsolidate['addedTableFields'][] = $tableUid;

        // DRS
      if( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devlog('[INFO/FILTER] Table ' . $tableUid . ' is added to arrConsolidate[addedTableFields].', $this->pObj->extKey, 0);
      }
        // DRS
    }
      // LOOP : each filter (table.field)

  }



/**
 * init_consolidationAndSelect_setTsSelect( ): Add tableFields to the ts property SELECT
 *
 * @return	void
 * @internal  #41776
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_consolidationAndSelect_setTsSelect( )
  {
      // LOOP : each filter (table.field)
    foreach( ( array ) $this->arr_tsFilterTableFields as $tableField )
    {
        // IF : $conf_sql['select'] doesn't contain the current tableField
      if( strpos( $this->pObj->conf_sql['select'], $tableField ) === false )
      {
          // Add tableField to ts property SELECT
        $csvStatement = ', ' . $tableField . ' AS \'' . $tableField . '\'';
        $this->pObj->conf_sql['select'] = $this->pObj->conf_sql['select'] . ', ' . $csvStatement;
          // Add tableField to ts property SELECT
          // DRS
        if( $this->pObj->b_drs_filter )
        {
          $prompt = $table . '.' . $field . ' is added to $this->pObj->conf_sql[select].';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
      }
        // IF : $conf_sql['select'] doesn't contain the current tableField
        // IF : $csvSelectWoFunc doesn't contain the current tableField
      if( strpos( $this->pObj->csvSelectWoFunc, $tableField ) === false )
      {
        $this->pObj->csvSelectWoFunc = $this->pObj->csvSelectWoFunc . ', ' . $tableField;
          // DRS
        if( $this->pObj->b_drs_filter )
        {
          $prompt = $tableField . ' is added to $this->pObj->csvSelectWoFunc.';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
      }
        // IF : $csvSelectWoFunc doesn't contain the current tableField
    }
      // LOOP : each filter (table.field)

  }



/**
 * init_consolidationAndSelect_isFilterArray(): Returns false, if there isn't any filter array
 *
 * @return	boolean		true: there is a filter array. false: there isn't
 * @internal  #41776
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_consolidationAndSelect_isFilterArray( )
  {
      // RETURN: true, there is a filter array
    if( is_array( $this->conf_view['filter.'] ) )
    {
      return true;
    }
      // RETURN: true, there is a filter array

      // DRS
    if( $this->pObj->b_drs_filter )
    {
      $prompt = $viewWiDot . $mode . ' . filters isn\'t an array. There isn\'t any filter for processing.';
      t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // RETURN: true, there is a filter array
    return false;
  }



/**
 * init_consolidationAndSelect_isTableFields(): Returns false, if there isn't any configured table.field
 *
 * @return	boolean		true: there is a table.field configured. false: there isn't
 * @internal  #41776
 * @version 4.1.21
 * @since   4.1.21
 */
  private function init_consolidationAndSelect_isTableFields( )
  {
      // LOOP : all table.field
    foreach( ( array ) $this->conf_view['filter.'] as $tables => $arrFields )
    {
        // #41776, dwildt, 1-
//      while( $value = current( $arrFields ) )
        // #41776, dwildt, 1+
      while( current( $arrFields ) )
      {
        $field = key( $arrFields );
          // IF : add field without a dot to $arr_tsFilterTableFields
        if( substr( $field, -1 ) != '.' )
        {
          $this->arr_tsFilterTableFields[] = trim( $tables ) . $field;
        }
          // IF : add field without a dot to $arr_tsFilterTableFields
        next( $arrFields );
      }
    }
      // LOOP : all table.field

      // RETURN : true, there is one table.field at least
    if( is_array( $this->arr_tsFilterTableFields ) )
    {
      return true;
    }
      // RETURN : true, there is one table.field at least

      // DRS
    if( $this->pObj->b_drs_error )
    {
      $prompt = $viewWiDot . $mode . '.filters hasn\'t any table.field syntax.';
      t3lib_div :: devlog( '[ERROR/FILTER] ' . $prompt, $this->pObj->extKey, 3 );
    }
      // DRS

      // RETURN : false, there is any table.field
    return false;
  }



  /**
 * init_localisation( ):  Inits the localisation mode and localisation TS
 *                            Sets the class vars
 *                            * $int_localisation_mode
 *                            * bool_dontLocalise
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init_localisation( )
  {

      // Set class var $int_localisation_mode; init TS of pObj->objLocalise;
    if( ! isset( $this->int_localisation_mode ) )
    {
      $this->int_localisation_mode = $this->pObj->objLocalise->localisationConfig( );
      $this->pObj->objLocalise->init_typoscript( );
    }

      // Set class var $bool_dontLocalise
      // SWTCH $int_localisation_mode
    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE. There isn\' any need to localise!';
        break;
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE_ONLY. There isn\' any need to localise!';
        break;
      default:
        $this->bool_dontLocalise = false;
        $prompt = 'Localisation mode is enabled';
        break;
    }
      // SWTCH $int_localisation_mode
      // Set class var $bool_dontLocalise

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return;
  }



/**
 * init_reset( ):  Reset some vars
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init_reset( )
  {
      // Reset cObj->data
    $this->cObjData_reset( );
  }









 /***********************************************
  *
  * Filter rendering
  *
  **********************************************/



/**
 * get_filter( ):  Get the filter of the current tableField.
 *
 * @return	array		$arr_return : $arr_return['data']['marker']['###TABLE.FIELD###']
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filter( )
  {
    $arr_return = array( );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Set marker label
    $markerLabel = '###' . strtoupper( $this->curr_tableField ) . '###';

      // RETURN condition isn't met
    if( ! $this->ts_getCondition( ) )
    {
      $arr_return['data']['marker'][$markerLabel] = null;
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $arr_return;
    }
      // RETURN condition isn't met

    $this->set_currFilterIsArea( );

      // Get filter rows
    $arr_return = $this->get_rows( );
    if( $arr_return['error']['status'] )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
    unset( $arr_return );
      // Get filter rows

      // Set class var $rows
    $this->rows = $rows;

      // Localise the rows
    $this->localise( );

      // Render the filter rows
    $arr_return = $this->get_filterItems( );
    $items      = $arr_return['data']['items'];
    unset( $arr_return );
    $arr_return['data']['marker'][$markerLabel] = $items;
      // Render the filter rows

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    return $arr_return;
  }



/**
 * get_filterItems( ):  Render the given rows of the current tableField.
 *                      It returns the rendered filter as a string.
 *
 * @return	array		$arr_return : $arr_return['data']['items']
 * @version 4.1.21
 * @since   3.9.9
 */
  private function get_filterItems( )
  {
    $arr_return = array( );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Default return value
    $arr_return['data']['items'] = null;

      // Set rows, if current filter is with areas
    $this->areas_toRows( );

// 4.1.16, 120927, dwildt, -
//      // RETURN rows are empty
//    if( empty ( $this->rows) )
//    {
//        // DRS
//      if( $this->pObj->b_drs_warn )
//      {
//        $prompt = 'Rows are empty. Filter: ' . $this->curr_tableField . '.';
//        t3lib_div::devlog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
//      }
//        // DRS
//      return $arr_return;
//    }
//      // RETURN rows are empty
// 4.1.16, 120927, dwildt, -


      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Set nice_piVar
    $this->set_nicePiVar( );

      // Set class var $htmlSpaceLeft
    $this->set_htmlSpaceLeft( );

      // Set class var $maxItemsPerHtmlRow
    $this->set_maxItemsPerHtmlRow( );

      // SWITCH current filter is a tree view
      // #41776, dwildt, 2-
//      // @todo: 120518, objFltr4x instead of 3x
//    switch( in_array( $table, $this->pObj->objFltr3x->arr_tablesWiTreeparentfield ) )
      // #41776, dwildt, 1+
    switch( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      case( true ):
        $arr_return = $this->get_filterItemsTree( );
        break;
      case( false ):
      default:
        $arr_return = $this->get_filterItemsDefault( );
        if( ! empty ( $arr_return ) )
        {
          $items      = $arr_return['data']['items'];
          $arr_return = $this->get_filterItemsWrap( $items );
        }
        break;
    }
      // SWITCH current filter is a tree view

    return $arr_return;
  }



/**
 * get_filterItemsFromRows( ):  Render the given rows of the current tableField.
 *                      It returns the rendered filter as a string.
 *
 * @return	array		$arr_return : $arr_return['data']['items']
 * @version 4.1.21
 * @since   3.9.9
 */
  private function get_filterItemsFromRows( )
  {
      // Default return value
    $arr_return = array( );
    $arr_return['data']['items'] = null;

      // RETURN rows are empty
    if( empty ( $this->rows) )
    {
        // DRS
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'Rows are empty. Filter: ' . $this->curr_tableField . '.';
        t3lib_div::devlog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
      }
        // DRS
      return $arr_return;
    }
      // RETURN rows are empty


      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // Set nice_piVar
    $this->set_nicePiVar( );

      // Set class var $htmlSpaceLeft
    $this->set_htmlSpaceLeft( );

      // Set class var $maxItemsPerHtmlRow
    $this->set_maxItemsPerHtmlRow( );

      // SWITCH current filter is a tree view
      // #41776, dwildt, 2-
//      // @todo: 121019, dwildt: 3x -> 4x
//    switch( in_array( $table, $this->pObj->objFltr3x->arr_tablesWiTreeparentfield ) )
      // #41776, dwildt, 1+
    switch( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      case( true ):
        $arr_return = $this->get_filterItemsTree( );
        break;
      case( false ):
      default:
        $arr_return = $this->get_filterItemsDefault( );
        $items      = $arr_return['data']['items'];
        $arr_return = $this->get_filterItemsWrap( $items );
        break;
    }
      // SWITCH current filter is a tree view

    return $arr_return;
  }



/**
 * get_filterItemsDefault( ): Render the items, if the filter view is the default view.
 *                            Default means: it isn't a tree view.
 *                            Items will returned as a string.
 *
 * @return	array		$arr_return : $arr_return['data']['items']
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterItemsDefault( )
  {
    $arr_return = array( );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Default return value
    $items                        = null;
    $arr_return['data']['items']  = $items;

      // Add the first item to the rows
    $this->set_firstItem( );

      // LOOP rows
    $this->row_number = 0;

    foreach( ( array ) $this->rows as $uid => $row )
    {
      $key    = $this->sql_filterFields[$this->curr_tableField]['value'];
      $value  = $row[$key];

      $item   = $this->get_filterItem( $uid, $value );
      $items  = $items . $this->htmlSpaceLeft . ' ' . $item . PHP_EOL ;
      $this->row_number++;
    }
      // LOOP rows

    $items = $this->get_maxItemsWrapBeginEnd( $items );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

    $trimItems = trim ( $items );
    if( ! empty ( $trimItems ) )
    {
      $arr_return['data']['items'] = $items;
    }
    else
    {
      unset( $arr_return );
    }
    return $arr_return;
  }



/**
 * get_filterItemsTree( ):  Render the items, if the filter view is a tree view.
 *                          Items will returned as a string.
 *
 * @return	array		$arr_return : $arr_return['data']['items']
 * @internal                #32223, 120119, dwildt+
 * @version   3.9.9
 * @since     3.9.9
 */
  private function get_filterItemsTree( )
  {
    $arr_return = array( );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Set cObj->data treeview
    $this->cObjData_setFlagTreeview( );
      // Set marker treeview
    $this->markerArray['###TREEVIEW###'] = 1;

      // Get table and field
    //list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    //$conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Needed for tree_setOneDim( )
    $this->arr_rowsTablefield = $this->rows;

      // Removes all rows with a null key
      // @todo: 120521, dwildt  : rows with key null should removed before counting hits!
      //                          sum of hits can be wrong
    unset( $this->arr_rowsTablefield[ null ] );

      // Get the labels for the fields uid, value and treeParentField
    $this->uidField         = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $this->valueField       = $this->sql_filterFields[$this->curr_tableField]['value'];
    $this->treeParentField  = $this->sql_filterFields[$this->curr_tableField]['treeParentField'];



//      //////////////////////////////////////////////////////
//      //
//      // Order the values
//
//      // Get the values for ordering
//      // @todo: 121018, dwildt, is multisort needed?
//    $arr_value = array( );
//    foreach ( $this->arr_rowsTablefield as $key => $row )
//    {
//      $arr_value[$key] = $row[$this->valueField];
//    }
//      // Get the values for ordering
//
//      // Set DESC or ASC
//    if ( strtolower( $conf_array['order.']['orderFlag'] ) == 'desc' )
//    {
//      $order = SORT_DESC;
//    }
//    if ( strtolower( $conf_array['order.']['orderFlag'] ) != 'desc' )
//    {
//      $order = SORT_ASC;
//    }
//      // Set DESC or ASC
//
//      // Order the rows
//    array_multisort( $arr_value, $order, $this->arr_rowsTablefield );
//      // Order the values

    unset( $this->tmpOneDim );
      // Parent uid of the root records: 0 of course
    $uid_parent = 0;
      // Set rows of the current tablefield to a one dimensional array
    $this->tree_setOneDim( $uid_parent );
     // Get the renderd tree. Each element of the returned array contains HTML tags.
    $arr_tableFields  = $this->tree_getRendered( );
    $items            = implode( null, $arr_tableFields );
    unset( $this->tmpOneDim );


      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

    $items = $this->get_filterWrap( $items );

      // Unset cObj->data treeview
    $this->cObjData_unsetFlagTreeview( );
      // Unset marker treeview
    unset( $this->markerArray['###TREEVIEW###'] );

      // RETURN
    $trimItems = trim ( $items );
    if( ! empty ( $trimItems ) )
    {
      $arr_return['data']['items'] = $items;
    }
    else
    {
      unset( $arr_return );
    }
    return $arr_return;
  }



/**
 * get_filterItemsWrap( ):  Wrap all items (wrap the object)
 *
 * @param	string		$items      : The items of the current tableField
 * @return	array		$arr_return : $arr_return['data']['items']
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterItemsWrap( $items )
  {
    $arr_return = array( );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // IF NOT CATEGORY_MENU ajax class onchange
    if($conf_name != 'CATEGORY_MENU')
    {
      $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $this->row_number);
    }
      // IF NOT CATEGORY_MENU ajax class onchange

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check multiple!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

      // Set multiple property
      // SWITCH type of filter
    switch( $conf_name )
    {
      case ( 'SELECTBOX' ) :
        $size = $conf_array['size'];
        $multiple = null;
        if( $size >= 2 )
        {
          if( $conf_array['multiple'] == 1 )
          {
            $multiple = ' ' . $conf_array['multiple.']['selected'];
          }
        }
        break;
      case ( 'CHECKBOX' ) :
      case ( 'CATEGORY_MENU' ) :
      case ( 'RADIOBUTTONS' ) :
      default :
        $size      = null;
        $multiple  = null;
        break;
    }
      // SWITCH type of filter
      // Set multiple property

      // Get the all items wrap
    $itemsWrap = $this->htmlSpaceLeft . $conf_array['wrap.']['object'];
      // Remove empty class
    $itemsWrap = str_replace( ' class=""', null, $itemsWrap );

      // Get nice piVar
    $key_piVar      = $this->nicePiVar['key_piVar'];
    //$arr_piVar      = $this->nicePiVar['arr_piVar'];
    $str_nicePiVar  = $this->nicePiVar['nice_piVar'];

      // Get ID
    $id = $this->pObj->prefixId . '_' . $str_nicePiVar;
    $id = str_replace('.', '_', $id);

      // Replace marker
    $itemsWrap = str_replace('###TABLE.FIELD###',  $key_piVar,  $itemsWrap );
    $itemsWrap = str_replace('###ID###',           $id,         $itemsWrap );
    $itemsWrap = str_replace('###SIZE###',         $size,       $itemsWrap );
    $itemsWrap = str_replace('###MULTIPLE###',     $multiple,   $itemsWrap );
      // Replace marker

      // Wrap all items
    $items = PHP_EOL . $items . $this->htmlSpaceLeft;
    $items = str_replace('|', $items, $itemsWrap);
      // Wrap all items

      // IF CATEGORY_MENU ajax class onchange
    if($conf_name == 'CATEGORY_MENU')
    {
      $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $this->row_number);
    }
      // IF CATEGORY_MENU ajax class onchange

      // Wrap the filter
    $items = $this->get_filterWrap( $items );

      // RETURN content
    $arr_return['data']['items'] = $items;
    return $arr_return;
  }



/**
 * get_filterItem( ): Render the current filter item.
 *
 * @param	integer		$uid        : uid of the current item / row
 * @param	string		$value      : value of the current item / row
 * @return	string		$item       : The rendered item
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterItem( $uid, $value )
  {
    static $loop = array( );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    if( ! isset ( $loop[ $this->curr_tableField ] ) )
    {
      $loop[ $this->curr_tableField ] = 0;
    }
    else
    {
      $loop[ $this->curr_tableField ]++;
    }

    if( $loop[ $this->curr_tableField ] < 2 )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );
    }

      // Get TS configuration of the current filter / tableField
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Make a backup
    $cObjDataBak = $this->pObj->cObj->data;
      // Add elements of current row to cObj->data
    $this->cObjData_updateRow( $uid );

    $this->set_markerArrayUpdateRow( $uid );

      // IF first_item, set the first item tree view
    if( $uid == $conf_array['first_item.']['option_value'] )
    {
      $this->set_firstItemTreeView( );
    }
      // IF first_item, set the first item tree view

      // DEVELOPMENT: Browser engine 4.x
    switch( $this->pObj->dev_browserEngine )
    {
      case( 4 ):
          // Wrap the current value by the cObject
        $this->updateWizard( 'filter_cObject' );
        if( $loop[ $this->curr_tableField ] < 2 )
        {
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel,  '### 1' );
        }
        $item = $this->get_filterItemCObj( $uid, $value );
        if( $loop[ $this->curr_tableField ] < 2 )
        {
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel,  '### 2' );
        }
        break;
      case( 3 ):
          // stdWrap the current value
        $item = $this->get_filterItemValueStdWrap( $conf_name, $conf_array, $uid, $value );
        break;
      default:
        $prompt = 'Sorry, this filter shouldn\'t occure: case is undefined.<br />
                  <br />
                  Method: ' . __METHOD__ . '<br />
                  Line: ' . __LINE__ . '<br />
                  <br />
                  Browser - TYPO3 without PHP';
        die( $prompt );
        break;
    }
      // DEVELOPMENT: Browser engine 4.x


    $this->set_itemCurrentNumber( );
    if( $loop[ $this->curr_tableField ] < 2 )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  '### 3' );
    }

      // Reset cObj->data
    $this->pObj->cObj->data = $cObjDataBak;

    if( $loop[ $this->curr_tableField ] < 2 )
    {
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    }
    return $item;
  }



/**
 * get_filterItemValueStdWrap( ): Render the current filter item.
 *
 * @param	array		$conf_name      : TS configuration object type of the current filter / tableField
 * @param	array		$conf_array     : TS configuration array of the current filter / tableField
 * @param	integer		$uid            : uid of the current item / row
 * @param	string		$value          : value of the current item / row
 * @return	string		$value_stdWrap  : The value stdWrapped
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterItemValueStdWrap( $conf_name, $conf_array, $uid, $value )
  {
    static $firstLoop = true;

      // Get the stdWrap for the value
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $stdWrap  = $conf_array['first_item.']['value_stdWrap.'];
        break;
      default:
        $stdWrap  = $conf_array['wrap.']['item.']['wraps.']['value.']['stdWrap.'];
        break;
    }
      // SWITCH first item
      // Get the stdWrap for the value

      // stdWrap the current value
    $item = $this->pObj->local_cObj->stdWrap( $value, $stdWrap );

      // Prepend or append hits
    $item = $this->set_hits( $uid, $item, $this->rows[$uid] );

      // stdWrap the current item
    $stdWrap  = $conf_array['wrap.']['item.']['wraps.']['item.']['stdWrap.'];
    $item     = $this->pObj->local_cObj->stdWrap( $item, $stdWrap );
      // stdWrap the current item

      // DRS :TODO:
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check maxItemsPerRow!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

    $item = $this->get_maxItemsTagEndBegin( $item );

      // Item class
    if($conf_name == 'CATEGORY_MENU')
    {
      $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $this->row_number);
    }
      // DRS :TODO:
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check AJAX ###ONCHANGE###';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:
    $item = $this->replace_itemClass( $conf_array, $item );
      // Item class
      // Item style
    $item = $this->replace_itemStyle( $conf_array, $item );
      // Item title
    $item = $this->replace_itemTitle( $item );
      // Item uid
    $item = $this->replace_itemUid( $uid, $item );
      // Item URL
    $item = $this->replace_itemUrl( $conf_array, $uid, $item );
      // Item selected
    $item = $this->replace_itemSelected( $conf_array, $uid, $value, $item );

      // Workaround: remove ###ONCHANGE###
    $item = str_replace( ' class=" ###ONCHANGE###"', null, $item );
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'class=" ###ONCHANGE###" is removed. Check the code!';
      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
    }
      // Workaround: remove ###ONCHANGE###

    $firstLoop = false;

    return $item;
  }



/**
 * get_filterItemCObj( ): Render the current filter item.
 *
 * @param	integer		$uid            : uid of the current item / row
 * @param	string		$value          : value of the current item / row
 * @return	string		$value_stdWrap  : The value stdWrapped
 * @version 3.9.20
 * @since   3.9.9
 */
  private function get_filterItemCObj( $uid, $value )
  {
    static $firstLoop   = true;
    static $loop        = array( );
    static $conf_array  = null;

//$this->pObj->dev_var_dump( $uid, $this->markerArray['###UID###'] );
//$this->pObj->dev_var_dump( $uid, $value );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    if( ! isset ( $loop[ $this->curr_tableField ] ) )
    {
      $loop[ $this->curr_tableField ] = 0;
    }
    else
    {
      $loop[ $this->curr_tableField ]++;
    }
    if( $loop[ $this->curr_tableField ] < 2 )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );
    }

      // Item class
      // Get TS configuration of the current filter / tableField
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];
    if($conf_name == 'CATEGORY_MENU')
    {
      $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $this->row_number);
    }
//var_dump( __METHOD__, __LINE__, $value, $conf_array );
      // DRS :TODO:
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check AJAX ###ONCHANGE###';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

    $this->markerArray['###CLASS###']         = $this->replace_itemClass( $conf_array, '###CLASS###' );
    $this->markerArray['###STYLE###']         = $this->replace_itemStyle( $conf_array, '###STYLE###' );
    $this->markerArray['###TITLE###']         = $this->replace_itemTitle( '###TITLE###' );
    $this->markerArray['###URL###']           = $this->replace_itemUrl( $conf_array, $uid, '###URL###' );
    $this->markerArray['###ITEM_SELECTED###'] = $this->replace_itemSelected( $conf_array, $uid, $value, '###ITEM_SELECTED###' );
      // #40354, #40354, 4.1.7, 1+
    $this->markerArray['###TABLE.FIELD###']   = $this->nicePiVar['key_piVar'];

      // 3.9.20:  Be careful: Method need 10 milliseconds. Can be a
      //          performance problem in case of a lot records!
    //$conf_array = $this->replace_marker( $conf_array );

      // Get the COA configuration for the value
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $cObj_name = $conf_array['first_item.']['cObject'];
        $cObj_conf = $conf_array['first_item.']['cObject.'];
        break;
      default:
        $cObj_name = $conf_array['wrap.']['item.']['cObject'];
        $cObj_conf = $conf_array['wrap.']['item.']['cObject.'];
        break;
    }
      // SWITCH first item
      // Get the COA configuration for the value

    $this->cObjData_setFlagDisplayInCaseOfNoCounting( );

    $item  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );

      // 3.9.20
      // 3.9.20:  Be careful: Method need 10 milliseconds. Can be a
      //          performance problem in case of a lot records!
    $item = $this->pObj->cObj->substituteMarkerArray( $item, $this->markerArray );

      // 3.9.20: Coded is moved from above
      // Workaround: remove ###ONCHANGE###
    $item = str_replace( ' class=" ###ONCHANGE###"', null, $item );
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'class=" ###ONCHANGE###" is removed. Check the code!';
      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
    }
      // Workaround: remove ###ONCHANGE###

    $this->cObjData_unsetFlagDisplayInCaseOfNoCounting( );

      // maxItemsTagEndBegin
      // DRS :TODO:
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check maxItemsPerRow!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:
    $item = $this->get_maxItemsTagEndBegin( $item );
      // maxItemsTagEndBegin


    $firstLoop = false;

    if( $loop[ $this->curr_tableField ] < 2 )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
    }
    return $item;
  }



///**
// * get_filterItemCObj( ): Render the current filter item.
// *
// * @param	integer		$uid            : uid of the current item / row
// * @param	string		$value          : value of the current item / row
// * @return	string		$value_stdWrap  : The value stdWrapped
// * @version 3.9.20
// * @since   3.9.9
// */
//  private function get_filterItemCObj( $uid, $value )
//  {
//    static $firstLoop   = true;
//    static $loop        = array( );
//    static $conf_array  = null;
//
//      // Get table and field
//    list( $table, $field ) = explode( '.', $this->curr_tableField );
//
//    if( ! isset ( $loop[ $this->curr_tableField ] ) )
//    {
//      $loop[ $this->curr_tableField ] = 0;
//    }
//    else
//    {
//      $loop[ $this->curr_tableField ]++;
//    }
//    if( $loop[ $this->curr_tableField ] < 2 )
//    {
//      $debugTrailLevel = 1;
//      $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );
//    }
//
////    if( $loop[ $this->curr_tableField ] == 0 )
////    {
//        // Item class
//        // Get TS configuration of the current filter / tableField
//      $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
//      $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];
//      if($conf_name == 'CATEGORY_MENU')
//      {
//        $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $this->row_number);
//      }
//  //var_dump( __METHOD__, __LINE__, $value, $conf_array );
//        // DRS :TODO:
//      if( $firstLoop && $this->pObj->b_drs_devTodo )
//      {
//        $prompt = 'Check AJAX ###ONCHANGE###';
//        t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
//      }
//        // DRS :TODO:
//      $this->markerArray['###CLASS###']         = $this->replace_itemClass( $conf_array, '###CLASS###' );
//        // Item class
//
//        // Item style
//      $this->markerArray['###STYLE###']         = $this->replace_itemStyle( $conf_array, '###STYLE###' );
//        // Item title
//      $this->markerArray['###TITLE###']         = $this->replace_itemTitle( '###TITLE###' );
//        // Item URL
//      $this->markerArray['###URL###']           = $this->replace_itemUrl( $conf_array, $uid, '###URL###' );
//        // Item selected
//      $this->markerArray['###ITEM_SELECTED###'] = $this->replace_itemSelected( $conf_array, $uid, $value, '###ITEM_SELECTED###' );
//
//      $conf_array = $this->replace_marker( $conf_array );
////    }
//
//
//
//      // Get the COA configuration for the value
//      // SWITCH first item
//    switch( true )
//    {
//      case( $uid == $conf_array['first_item.']['option_value'] ):
//        $cObj_name = $conf_array['first_item.']['cObject'];
//        $cObj_conf = $conf_array['first_item.']['cObject.'];
//        break;
//      default:
//        $cObj_name = $conf_array['wrap.']['item.']['cObject'];
//        $cObj_conf = $conf_array['wrap.']['item.']['cObject.'];
//        break;
//    }
//      // SWITCH first item
//      // Get the COA configuration for the value
//
//    $this->cObjData_setFlagDisplayInCaseOfNoCounting( );
//
//    $item  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//
//      // 3.9.20: Coded is moved from above
//      // Workaround: remove ###ONCHANGE###
//    $item = str_replace( ' class=" ###ONCHANGE###"', null, $item );
//    if( $firstLoop && $this->pObj->b_drs_devTodo )
//    {
//      $prompt = 'class=" ###ONCHANGE###" is removed. Check the code!';
//      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
//    }
//      // Workaround: remove ###ONCHANGE###
//
//    $this->cObjData_unsetFlagDisplayInCaseOfNoCounting( );
//
//      // maxItemsTagEndBegin
//      // DRS :TODO:
//    if( $firstLoop && $this->pObj->b_drs_devTodo )
//    {
//      $prompt = 'Check maxItemsPerRow!';
//      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
//    }
//      // DRS :TODO:
//    $item = $this->get_maxItemsTagEndBegin( $item );
//      // maxItemsTagEndBegin
//
//
//    $firstLoop = false;
//
//    if( $loop[ $this->curr_tableField ] < 2 )
//    {
//      $debugTrailLevel = 1;
//      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
//    }
//    return $item;
//  }



/**
 * get_filterTitle( ):  Get the wrapped title for the current filter.
 *
 * @return	string		$title  : The wrapped title
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterTitle( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];



      /////////////////////////////////////////////////////
      //
      // RETURN no title_stdWrap

    if ( ! is_array( $conf_array['wrap.']['title_stdWrap.'] ) )
    {
      if ( $this->pObj->b_drs_filter )
      {
        $prompt = 'There is no title_stdWrap. The object won\'t get a title.';
        t3lib_div :: devLog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'If you want a title, please configure ' .
                  $this->conf_path . $this->curr_tableField . '.wrap.title_stdWrap.';
        t3lib_div :: devLog('[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1);
      }
      return null;
    }
      // RETURN no title_stdWrap



      /////////////////////////////////////////////////////
      //
      // Get the local or global autoconfig array

      // Get the local autoconfig array
    $lAutoconf      = $this->conf_view['autoconfig.'];
    $lAutoconfPath  = $this->conf_path;
    if ( ! is_array( $lAutoconf ) )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div :: devlog('[INFO/SQL] ' . $this->conf_path . ' hasn\'t any autoconf array.<br />
                    We take the global one.', $this->pObj->extKey, 0);
      }
        // Get the global autoconfig array
      $lAutoconf      = $this->conf['autoconfig.'];
      $lAutoconfPath  = null;
    }
      // Get the local or global autoconfig array

      // Don't replace markers recursive
    if ( ! $lAutoconf['marker.']['typoScript.']['replacement'] )
    {
        // DRS
      if ( $this->pObj->b_drs_filter )
      {
        $prompt = 'Replacement for markers in TypoScript is deactivated.';
        t3lib_div :: devLog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want a replacement, please configure ' .
                  $lAutoconfPath . 'autoconfig.marker.typoScript.replacement.';
        t3lib_div :: devLog( '[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
    }
      // Don't replace markers recursive



      /////////////////////////////////////////////////////
      //
      // Wrap the title

      // Get the title
    $title_stdWrap = $conf_array['wrap.']['title_stdWrap.'];

      // Replace ###TABLE.FIELD### recursive
    $value_marker = $this->pObj->objZz->getTableFieldLL($this->curr_tableField);
    if ( $lAutoconf['marker.']['typoScript.']['replacement'] )
    {
      $key_marker               = '###TABLE.FIELD###';
      $markerArray[$key_marker] = $value_marker;
      $key_marker               = '###' . strtoupper($this->curr_tableField) . '###';
      $markerArray[$key_marker] = $value_marker;
      $title_stdWrap = $this->pObj->objMarker->substitute_marker_recurs( $title_stdWrap, $markerArray );
        // DRS
      if ($this->pObj->b_drs_filter)
      {
        $prompt = $key_marker . ' will replaced with the localised value of ' .
                  '\'' . $this->curr_tableField . '\': \'' . $value_marker . '\'.';
        t3lib_div :: devLog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want another replacement, please configure ' .
                  $this->conf_view_path . $this->curr_tableField . '.wrap.title_stdWrap';
        t3lib_div :: devLog( '[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
    }
      // Replace ###TABLE.FIELD### recursive

      // stdWrap the title
    $title = $this->pObj->local_cObj->stdWrap( $value_marker, $title_stdWrap );

      // RETURN the title
    return $title;
  }



/**
 * get_filterWrap( ): Wraps the items with table.field.wrap
 *
 * @param	string		$items  : items of the current tableField / filter
 * @return	string		$items  : items wrapped
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterWrap( $items )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Get the items title
    $itemsTitle = $this->get_filterTitle( );

      // Get the items wrap
    $itemsWrap  = $conf_array['wrap'];
    $itemsWrap  = str_replace( '###TITLE###', $itemsTitle, $itemsWrap );

      // Nice Html
    $arr_itemsWrap = explode( '|', $itemsWrap );
    $itemsWrap  = $this->htmlSpaceLeft . $arr_itemsWrap[0] . PHP_EOL .
                  $this->htmlSpaceLeft . '  |' . PHP_EOL .
                  $this->htmlSpaceLeft . $arr_itemsWrap[1] . PHP_EOL;

      // Wrap the items
    if( $itemsWrap )
    {
      $items = str_replace('|', $items , $itemsWrap);
    }

    return $items;
  }









 /***********************************************
  *
  * Areas
  *
  **********************************************/



/**
 * areas_toRows( ): If current filter is with areas, generate the rows.
 *                  Class var $rows will overriden.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function areas_toRows( )
  {
      // RETURN filter hasn't areas
    if( ! $this->bool_currFilterIsArea )
    {
      return;
    }
      // RETURN filter hasn't areas

      // Get areas from TS
    $areas  = $this->ts_getAreas( );
      // Convert areas to rows
    $rows   = $this->areas_toRowsConverter( $areas );
    $this->rowsFromAreaWoHits = $rows;

      // Count the hits for each area row
    $rows = $this->areas_countHits( $rows );
      // Remove area rows without hits, if it's needed
    $rows = $this->areas_wiHitsOnly( $rows );

      // Override class var rows
    $this->rows = $rows;

    return;
  }



/**
 * areas_toRowsConverter( ):  Converts areas array to rows array. Returns the rows.
 *
 * @param	array		$areas  : areas from TS
 * @return	array		$rows   : rows
 * @version 3.9.9
 * @since   3.9.9
 */
  private function areas_toRowsConverter( $areas )
  {
    $rows = array( );

      // Get the labels for the fields uid and hits
    $uidField   = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $valueField = $this->sql_filterFields[$this->curr_tableField]['value'];
    $hitsField  = $this->sql_filterFields[$this->curr_tableField]['hits'];

    foreach( $areas as $uid => $value )
    {
        // LOOP all fields of current filter / tableField
      foreach( $this->sql_filterFields[$this->curr_tableField] as $field )
      {
          // SWITCH field
        switch( true )
        {
          case( $field == $uidField ):
            $rows[$uid][$uidField] = $uid;
            break;
          case( $field == $valueField ):
            $rows[$uid][$valueField] = $value;
            break;
          case( $field == $hitsField ):
            $rows[$uid][$hitsField] = 0;
            break;
          default:
            $rows[$uid][$field] = null;
            break;
        }
          // SWITCH field
      }
        // LOOP all fields of current filter / tableField
    }

      // RETURN rows
    return $rows;
  }



/**
 * areas_countHits( ): Count the hits for each area.
 *
 * @param	array		$areas  : Area TS configuration
 * @return	array		$areas  : $areas with counted hits
 * @package array   $areas : rows of the current area
 * @version 3.9.9
 * @since   3.9.9
 */
  private function areas_countHits( $areas )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS configuration of the current filter / tableField
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Get labels for the fields hits and value
    $hitsField  = $this->sql_filterFields[$this->curr_tableField]['hits'];
    $valueField = $this->sql_filterFields[$this->curr_tableField]['value'];

      // Get the key of the area of the current filter: 'strings' or 'interval'
    $area_key = $this->pObj->objCal->arr_area[$this->curr_tableField]['key'];

      // LOOP each area
    foreach( array_keys ( ( array ) $areas ) as $areas_uid )
    {
        // Short var
      $conf_area  = $conf_array['area.'][$area_key . '.']['options.']['fields.'][$areas_uid . '.'];

        // Get from
      $from       = $conf_area['valueFrom_stdWrap.']['value'];
      $from_conf  = $conf_area['valueFrom_stdWrap.'];
      $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);

        // Get to
      $to         = $conf_area['valueTo_stdWrap.']['value'];
      $to_conf    = $conf_area['valueTo_stdWrap.'];
      $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);

        // LOOP rows
      foreach( $this->rows as $rows_uid => $rows_row )
      {
        $value = $rows_row[$valueField];
          // Count the hits, if row value match from to condition
        if( $value >= $from && $value <= $to )
        {
          $areas[$areas_uid][$hitsField] = $areas[$areas_uid][$hitsField] + $this->rows[$rows_uid][$hitsField];
        }
      }
        // LOOP rows
    }
      // LOOP each area

      // RETURN areas with hits
    return $areas;
  }



/**
 * areas_wiHitsOnly( ):  The method removes areas without any hit,
 *                          if should displayed items only, which have one hit at least.
 *
 * @param	array		$areas  : Area TS configuration
 * @return	array		$areas  : all rows or rows with one hit at least only
 * @package array   $areas : rows of the current area
 * @version 3.9.9
 * @since   3.9.9
 */
  private function areas_wiHitsOnly( $areas )
  {
      // RETURN all areas
    if( $this->ts_countHits( ) )
    {
      return $areas;
    }
      // RETURN all areas

      // Get label for the field hits
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // LOOP each area
      // Remove areas without any hit
    foreach( array_keys ( ( array ) $areas ) as $areas_uid )
//    foreach( $areas as $areas_uid => $areas_row )
    {
      if( $areas[$areas_uid][$hitsField] < 1 )
      {
        unset( $areas[$areas_uid] );
      }
    }
      // Remove areas without any hit
      // LOOP each area

      // RETURN areas with hits only
    return $areas;
  }









 /***********************************************
  *
  * Rows
  *
  **********************************************/



/**
 * get_rows( ):     Get the rows of the current filter
 *
 * @return	array		$arr_return : Array with the rows or an error message
 * @version 4.1.21
 * @since   3.9.9
 */
  private function get_rows( )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // IF : hits should counted
    if( $this->ts_countHits( ) )
    {
        // 1. step: filter items with one hit at least
      $arr_return = $this->get_rowsWiHits( );
      if( $arr_return['error']['status'] )
      {
        return $arr_return;
      }
      $rows = $arr_return['data']['rows'];
        // 1. step: filter items with one hit at least
    }
      // IF : hits should counted

      // 2. step: all filter items, hits will be taken from $rows
    $arr_return = $this->get_rowsAllItems( $rows );

    return $arr_return;
  }



/**
 * get_rowsWiHits( ): Get the rows with the items with a hit at least of the current filter.
 *
 * @return	array		$arr_return : Array with the rows or an error message
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rowsWiHits( )
  {
      // Get SQL ressource for filter items with one hit at least
    $arr_return = $this->sql_resWiHits( );
    if( $arr_return['error']['status'] )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $arr_return;
    }
    $res = $arr_return['data']['res'];
    unset( $arr_return );
      // Get SQL ressource for filter items with one hit at least

      // Get rows from SQL ressource
    $arr_return['data']['rows'] = $this->sql_resToRows( $res );

      // Count all hits
    $this->sum_hits( $arr_return['data']['rows'] );

      // RETURN rows
    return $arr_return;
  }



/**
 * get_rowsAllItems( ): Get the rows with all items of the current filter.
 *                      If param $rows_wiHits contains rows, the counted
 *                      hits will taken over in rows with all items.
 *                      BE AWARE  : method is handled only, if matches of the
 *                                  items of current filter shouldn't counted.
 *
 * @param	array		$rows_wiHits  : Rows with items of the current filter,
 * @return	array		$arr_return   : Array with the rows or an error message
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rowsAllItems( $rows_wiHits )
  {
    $arr_return = array( );

      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // RETURN IF : hits should counted
    if( $this->ts_countHits( ) )
    {
      $arr_return['data']['rows'] = $rows_wiHits;
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $arr_return;
    }
      // RETURN IF : hits should counted

      // SWITCH : localTable versus foreignTable
    switch( true )
    {
      case( $table == $this->pObj->localTable ):
      case( $table != $this->pObj->localTable ):
          // foreign table
          // Get SQL ressource for all filter items
        $arr_return = $this->sql_resAllItems( );
        if( $arr_return['error']['status'] )
        {
          $debugTrailLevel = 1;
          $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
          return $arr_return;
        }
        $res = $arr_return['data']['res'];
        unset( $arr_return );
          // Get SQL ressource for all filter items
          // Get rows
        $rows = $this->sql_resToRows_allItemsWiHits( $res, $rows_wiHits );
        break;
          // foreign table
      case( $table == $this->pObj->localTable ):
      default:
          // local table
  // DRS :TODO:
if( $this->pObj->b_drs_warn )
{
  // :TODO: 121010, dwildt: im Fall von nicht zählen keine Treffer!        
  // Bug #41814 Filter: local table isn't proper, if hits aren't displayed
  $prompt = 'Bug #41814 Filter: local table isn\'t proper, if hits aren\'t displayed';
  t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 3 );
}
  // DRS :TODO:
        $arr_return = $this->sql_resAllItems( );
$this->pObj->dev_var_dump( $arr_return );
        $rows = $rows_wiHits;
        break;
          // local table
    }
    unset( $table );
      // SWITCH : localTable versus foreignTable

      // RETURN rows
    $arr_return['data']['rows'] = $rows;
    return $arr_return;
  }



/**
 * get_selectedFilters( ) : Sets the class var $arr_selectedFilters. The $tableField
 *                          of afilter is added to $arr_selectedFilters, if the filter
 *                          is an element of the piVars.
 *
 * @return	array       $arr_selectedFilters: contains $tableFields of selected filters
 * @version 4.1.21
 * @since   3.9.12
 */
  public function get_selectedFilters( )
  {
      // RETURN : var is initialised
    if( ! $this->arr_selectedFilters === null )
    {
      return $this->arr_selectedFilters;
    }
      // RETURN : var is initialised

      // RETURN : no piVars, set var to false
    if( empty( $this->pObj->piVars ) )
    {
      $this->arr_selectedFilters = false;
      return $this->arr_selectedFilters;
    }
      // RETURN : no piVars, set var to false

      // Set default
    $this->arr_selectedFilters = false;

      // LOOP : each filter table
    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
        // LOOP : each filter field
      foreach( array_keys ( ( array ) $fields ) as $fieldWiDot )
      {
        if( substr( $fieldWiDot, -1 ) != '.' )
        {
          continue;
        }
        $field      = substr($fieldWiDot, 0, -1);
        $tableField = $tableWiDot . $field;
        if( isset( $this->pObj->piVars[$tableField] ) )
        {
            // #41754, 121010, dwildt, 2-
//          $this->arr_selectedFilters = true;
//          return $this->arr_selectedFilters;
            // #41754, 121010, dwildt, 1+
          $this->arr_selectedFilters[] = $tableField;
        }
      }
        // LOOP : each filter field
    }
      // LOOP : each filter table

    return $this->arr_selectedFilters;
  }









 /***********************************************
  *
  * SQL ressources
  *
  **********************************************/



/**
 * sql_resAllItems( ):  Get the SQL ressource for a filter with all items.
 *                      Hits won't counted.
 *
 * @return	array		$arr_return : Array with the SQL ressource or an error message
 * @version 4.1.21
 * @since   3.9.9
 */
  private function sql_resAllItems( )
  {
    $arr_return = null;
    
      // SWITCH : filter without any relation versus filter with relation
    switch( true )
    {
      case(  $this->ts_countHits( ) ):
      case(  in_array( $this->curr_tableField, $this->get_selectedFilters( ) ) ):
//$this->pObj->dev_var_dump( 
//                      $this->curr_tableField . ': with relations.', 
//                      $this->count_hits[$this->curr_tableField], 
//                      in_array( $this->curr_tableField, $this->get_selectedFilters( ) )
//                    );
        $arr_return = $this->sql_resAllItemsFilterWiRelation( );
        break;
      default:
//$this->pObj->dev_var_dump( $this->curr_tableField . ': without relations.' );
        $arr_return = $this->sql_resAllItemsFilterWoRelation( );
        break;
    }

    return $arr_return;
  }



/**
 * sql_resAllItemsFilterWiRelation( ):  Get the SQL ressource for a filter with all items.
 *                      Hits won't counted.
 *
 * @return	array		$arr_return : Array with the SQL ressource or an error message
 * @version 4.1.21
 * @since   3.9.9
 */
  private function sql_resAllItemsFilterWiRelation( )
  {
      // Don't count hits
    $bool_count = false;

      // Query for all filter items
    $select   = $this->sql_select( $bool_count );
    $from     = $this->sql_from( );
    $where    = $this->sql_whereAllItems( );
    $groupBy  = $this->curr_tableField;
    $orderBy  = $this->sql_orderBy( );
    $limit    = $this->sql_limit( );

//    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
//              (
//                $select,
//                $from,
//                $where,
//                $groupBy,
//                $orderBy,
//                $limit
//              );
//$this->pObj->dev_var_dump( $query );

      // Execute query
    $arr_return = $this->pObj->objSqlFun->exec_SELECTquery
                  (
                    $select,
                    $from,
                    $where,
                    $groupBy,
                    $orderBy,
                    $limit
                  );
      // Execute query

    return $arr_return;
  }



/**
 * sql_resAllItemsFilterWoRelation( ):
 *
 * @return	array		$arr_return : Array with the SQL ressource or an error message
 * @version 4.1.21
 * @since   4.1.21
 */
  private function sql_resAllItemsFilterWoRelation( )
  {
    list( $table ) = explode( '.', $this->curr_tableField );
    $tableField = $this->curr_tableField;
    $tableUid   = $table . '.uid';

    $this->sql_filterFields[$this->curr_tableField]['hits']   = 'hits';
    $this->sql_filterFields[$this->curr_tableField]['uid']    = $table . '.uid';
    $this->sql_filterFields[$this->curr_tableField]['value']  = $this->curr_tableField;

      // Query for all filter items
    $select   = "0 AS 'hits', " . 
                $tableField . " AS '" . $tableField . "', " .
                $tableUid . " AS '" . $tableUid . "' ";
    $select   = $select . $this->sql_select_addTreeview( );
    $from     = $table;
    $where    = '';
//    $groupBy  = $tableField . ", " . $tableUid;
//    $orderBy  = $tableField . ", " . $tableUid;
    $groupBy  = $tableField;
    $orderBy  = $tableField;
    $limit    = null;

//    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
//              (
//                $select,
//                $from,
//                $where,
//                $groupBy,
//                $orderBy,
//                $limit
//              );
//echo $query;
//$this->pObj->dev_var_dump( $query );

      // Execute query
    $arr_return = $this->pObj->objSqlFun->exec_SELECTquery
                  (
                    $select,
                    $from,
                    $where,
                    $groupBy,
                    $orderBy,
                    $limit
                  );
      // Execute query

    return $arr_return;
  }



/**
 * sql_resSysLanguageRows: Get the SQL ressource for localised rows
 *
 * @return	array		$arr_return : Array with the SQL ressource or an error message
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resSysLanguageRows( )
  {
    $arr_return = array( );

      // RETURN : there isn't any row
    if( empty( $this->rows ) )
    {
      return $arr_return;
    }
      // RETURN : there isn't any row

      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // Get ids
    $uids_arr = array_keys( $this->rows );
    $uids_csv = implode( ',', $uids_arr );

      // transOrigPointerField
    $transOrigPointerField  = $this->sql_filterFields[$this->curr_tableField]['transOrigPointerField'];

      // Query for all filter items
    $select   = $table . ".uid AS '" . $table . ".uid', " .
                $this->curr_tableField . " AS '" . $this->curr_tableField . "', " .
                $transOrigPointerField . " AS '" . $transOrigPointerField . "'";
    $from     = $table;
    $where    = $transOrigPointerField . " IN (" . $uids_csv . ")";
    $groupBy  = null;
    $orderBy  = null;
    $limit    = null;

      // Execute query
    $arr_return = $this->pObj->objSqlFun->exec_SELECTquery
                  (
                    $select,
                    $from,
                    $where,
                    $groupBy,
                    $orderBy,
                    $limit
                  );
      // Execute query

    return $arr_return;
  }



/**
 * sql_resWiHits( ):  Get the SQL ressource for a filter with items with hits only.
 *                    Hits will counted.
 *
 * @return	array		$arr_return : Array with the SQL ressource or an error message
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resWiHits( )
  {
      // Count hits
    $bool_count = true;

      // Get query parts
    $select   = $this->sql_select( $bool_count );
    $from     = $this->sql_from( );
    $where    = $this->sql_whereWiHits( );
    $groupBy  = $this->sql_groupBy( );
    $orderBy  = $this->sql_orderBy( );
    $limit    = $this->sql_limit( );

//    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
//              (
//                $select,
//                $from,
//                $where,
//                $groupBy,
//                $orderBy,
//                $limit
//              );
//$this->pObj->dev_var_dump( $query );
    
      // Execute query
    $arr_return = $this->pObj->objSqlFun->exec_SELECTquery
                  (
                    $select,
                    $from,
                    $where,
                    $groupBy,
                    $orderBy,
                    $limit
                  );
      // Execute query

    return $arr_return;
  }









 /***********************************************
  *
  * SQL ressources to rows
  *
  **********************************************/



/**
 * sql_resToRows( ):  Handle the SQL result, free it. Return rows.
 *
 * @param	object		$res  : current SQL ressource
 * @return	array		$rows : rows
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resToRows( $res )
  {
    $rows = array( );

      // Get the field label of the uid
    $uidField = $this->sql_filterFields[$this->curr_tableField]['uid'];

      // LOOP build the rows
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[ ( string ) $row[ $uidField ] ] = $row;
    }
      // LOOP build the rows

      // Free SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $this->res );

      // RETURN rows
    return $rows;
  }



/**
 * sql_resToRows_allItemsWiHits( ): Handle the SQL result, free it. If param $rows_wiHits contains
 *                                  rows, the hit of each row will override the hit in the current row.
 *                                  Hit in the current row is 0 by default.
 *
 * @param	object		$res              : current SQL ressource
 * @param	array		$rows_wiHits      : rows with hits
 * @return	array		$rows_wiAllItems  : rows with all filter items
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resToRows_allItemsWiHits( $res, $rows_wiHits )
  {
      // Get all rows - get all filter items
    $rows_wiAllItems = $this->sql_resToRows( $res );
//$this->pObj->dev_var_dump( $rows_wiAllItems );

      // RETURN all rows are empty
    if( empty ( $rows_wiAllItems ) )
    {
      return null;
    }
      // RETURN all rows are empty

      // RETURN all rows, there isn't any row with a hit
    if( empty ( $rows_wiHits ) )
    {
      return $rows_wiAllItems;
    }
      // RETURN all rows, there isn't any row with a hit

      // Get label of the hits field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // LOOP all items
    foreach( array_keys ( ( array ) $rows_wiAllItems ) as $uid )
//    foreach( ( array ) $rows_wiAllItems as $uid => $row )
    {
        // If there is an hit, take it over
      if( isset ( $rows_wiHits[ $uid ] ) )
      {
        $hits = $rows_wiHits[ $uid ][ $hitsField ];
        $rows_wiAllItems[ $uid ][ $hitsField ] = $hits;
      }
        // If there is an hit, take it over
    }
      // LOOP all items

      // RETURN rows
    return $rows_wiAllItems;
  }









 /***********************************************
  *
  * SQL statements - select
  *
  **********************************************/



/**
 * sql_select( ):   Get the SELECT statement for the current filter (the current tableField).
 *                  Statement will contain fields for localisation and treeview, if there is
 *                  any need.
 *
 * @param	boolean		$bool_count : true: hits are counted, false: any hit isn't counted
 * @return	string		$select     : SELECT statement
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select( $bool_count )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // EXIT wrong TS configuration
    $conf_view = $this->conf_view;
    if( ! empty ( $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['select'] ) )
    {
      $prompt  = '
                  <h1 style="color:red;">
                    ERROR: filter
                  </h1>
                  <p style="color:red;font-weight:bold;">
                    Sorry: filter.' . $this->curr_tableField . '.sql.select isn\'t supported from TYPO3-Browser version 4.x<br />
                    <br />
                    Please remove the TypoScript code filter.' . $this->curr_tableField . '.sql.select.<br />
                    <br />
                    Method: ' . __METHOD__ . '<br />
                    Line: ' . __LINE__ . '
                  </p>';
      echo $prompt;
      exit;
    }
      // EXIT wrong TS configuration

      // select
    switch( $bool_count )
    {
      case( true ):
        $localTableUid = $this->pObj->arrLocalTable['uid'];
        $count = "COUNT( DISTINCT " . $localTableUid . " )";
        break;
      case( false ):
      default:
        $count = "0";
        break;
    }
    $select = $count . " AS 'hits', " .
              $table . ".uid AS '" . $table . ".uid', " .
              $this->curr_tableField . " AS '" . $this->curr_tableField . "'";
      // select

      // Set class var sql_filterFields
    $this->sql_filterFields[$this->curr_tableField]['hits']   = 'hits';
    $this->sql_filterFields[$this->curr_tableField]['uid']    = $table . '.uid';
    $this->sql_filterFields[$this->curr_tableField]['value']  = $this->curr_tableField;
      // Set class var sql_filterFields

      // Add treeview field to select
    $select = $select . $this->sql_select_addTreeview( );

      // Add localisation fields to select
    $select = $select . $this->sql_select_addLL( );

      // RETURN select
    return $select;
  }



/**
 * sql_select_addLL( ): Returns an addSelect with the localisation fields,
 *                      if there are localisation needs.
 *                      Localisation fields depends on case
 *                      * local table   (sys_language record)
 *                      * foreign table (language overlay)
 *
 * @return	string		$addSelect  : the addSelect with the localisation fields
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_addLL( )
  {
      // RETURN no localisation
    if( $this->bool_dontLocalise )
    {
      return;
    }
      // RETURN no localisation

      // Get addSelect
    $addSelect = $this->sql_select_addLL_sysLanguage( );
    $addSelect = $addSelect . $this->sql_select_addLL_langOl( );
      // Get addSelect

      // RETURN addSelect
    return $addSelect;
  }



/**
 * sql_select_addLL_sysLanguage( ): Returns an addSelect with the localisation fields,
 *                                  if there are localisation needs.
 *                                  Method handles the local table (sys_language record) only.
 *
 * @return	string		$addSelect  : the addSelect with the localisation fields
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_addLL_sysLanguage( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // RETURN no languageField
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['languageField'] ) )
    {
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised localTable: TCA.' . $table . 'ctrl.languageField is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN no languageField

      // RETURN no transOrigPointerField
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'] ) )
    {
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised localTable: TCA.' . $table . 'ctrl.transOrigPointerField is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN no transOrigPointerField

      // Get field labels
    $languageField          = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
    $languageField          = $table . '.' . $languageField;
    $transOrigPointerField  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
    $transOrigPointerField  = $table . '.' . $transOrigPointerField;
      // Get field labels

      // addSelect
    $addSelect  = ", " .
                  $languageField . " AS '" . $languageField . "', " .
                  $transOrigPointerField . " AS '" . $transOrigPointerField . "'";
      // addSelect

      // Add $languageField and $transOrigPointerField to the class var sql_filterFields
    $this->sql_filterFields[$this->curr_tableField]['languageField']          = $languageField;
    $this->sql_filterFields[$this->curr_tableField]['transOrigPointerField']  = $transOrigPointerField;

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      $prompt = $table . ' is a localised localTable. SELECT is localised.';
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // RETURN addSelect
    return $addSelect;
  }



/**
 * sql_select_addLL_langOl( ): Returns an addSelect with the localisation fields,
 *                              if there are localisation needs.
 *                              Method handles the foreign table (language overlay) only.
 *
 * @return	string		$addSelect  : the addSelect with the localisation fields
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_addLL_langOl(  )
  {
      // get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Load TCA
    $this->pObj->objZz->loadTCA( $table );

      // Get language overlay appendix
    $lang_ol        = $this->pObj->objLocalise->conf_localisation['TCA.']['field.']['appendix'];

      // Label of the  field for language overlay
    $field_lang_ol  = $field . $lang_ol;

      // RETURN no field for language overlay
    if( ! isset ($GLOBALS['TCA'][$table]['columns'][$field_lang_ol] ) )
    {
        // DRS
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised foreignTable: ' .
                  'TCA.' . $table . 'columns.' . $field_lang_ol . ' is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return;
    }
      // RETURN no field for language overlay

      // addSelect
    $tableField_ol  = $table . '.' . $field_lang_ol;
    $addSelect      = ", " . $tableField_ol . " AS '" . $tableField_ol . "'";
      // addSelect

      // Add field to the class var sql_filterFields
    $this->sql_filterFields[$this->curr_tableField]['lang_ol'] = $tableField_ol;

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      $prompt = $table . ' is a localised foreignTable. SELECT is localised.';
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // RETURN addSelect
    return $addSelect;
  }



/**
 * sql_select_addTreeview( ): Returns an addSelect with the treeParentField,
 *                            if there is a treeParentField
 *
 * @return	string		$addSelect  : the addSelect with the treeParentField
 * @internal #32223, 120119
 * @version 4.1.21
 * @since   3.9.9
 */
  private function sql_select_addTreeview( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // #32223, 120120, dwildt+
      // Get $treeviewEnabled
    $conf_view        = $this->conf_view;
    $cObj_name        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled'];
    $cObj_conf        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled.'];
    $treeviewEnabled  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // Get $treeviewEnabled

      // RETURN no treeview
    if( ! $treeviewEnabled )
    {
      if( $this->pObj->b_drs_filter )
      {
        $prompt = 'treeview is disabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
        t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return;
    }
      // RETURN no treeview

      // DRS
    if( $this->pObj->b_drs_filter )
    {
      $prompt = 'treeview is enabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
      t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // Load the TCA for the current table
    $this->pObj->objZz->loadTCA( $table );

      // RETURN table hasn't any treeParentField in the TCA
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['treeParentField'] ) )
    {
      if( $this->pObj->b_drs_filter )
      {
        $prompt = 'TCA.' . $table . '.ctrl.treeParentField isn\'t set.';
        t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return;
    }
      // RETURN table hasn't any treeParentField in the TCA

      // Get $tableTreeParentField
    $treeParentField      = $GLOBALS['TCA'][$table]['ctrl']['treeParentField'];
    $tableTreeParentField = $table . "." . $treeParentField;

      // Add $tableTreeParentField to the SELECT statement
    $addSelect = ", " . $tableTreeParentField . " AS '" . $tableTreeParentField . "'";

      // Add $tableTreeParentField to the class var array
    $this->sql_filterFields[$this->curr_tableField]['treeParentField']  = $tableTreeParentField;

      // #41776, dwildt, 2-
//      // Add table to arr_tablesWiTreeparentfield
//    $this->pObj->objFltr3x->arr_tablesWiTreeparentfield[] = $table;
      // #41776, dwildt, 2+
      // Add table to arr_tablesWiTreeparentfield
    $this->arr_tablesWiTreeparentfield[] = $table;

      // DRS
    if( $this->pObj->b_drs_filter )
    {
      $prompt = 'TCA.' . $table . '.ctrl.treeParentField is set. ' . $table . ' is configured for a tree view.';
      t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return $addSelect;
  }









 /***********************************************
  *
  * SQL statements - from, groupBy, orderBy, limit
  *
  **********************************************/



/**
 * sql_from( ): Get the FROM statement. Statement depends on current table is
 *              a local table or a foreign table.
 *
 * @return	string		$from : FROM statement without a FROM
 * @version 3.9.12
 * @since   3.9.9
 */
  private function sql_from( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );
      // Flexform configuration
    $conf_flexform = $this->pObj->objFlexform->sheet_viewList_total_hits;

      // SWITCH
    switch( true )
    {
        // 3.9.25, 120506, dwildt+
      case( ! empty ( $this->pObj->conf_sql['andWhere'] ) ):
      case( $this->pObj->localTable != $table ) :
      case( $conf_flexform == 'controlled' ) :
      case( isset( $this->pObj->piVars['sword'] ) ):
        $from = $this->pObj->objSqlInit->statements['listView']['from'];
        break;
      case( $conf_flexform == 'independent' ) :
        $from = $table;
        break;
      default;
        $prompt = __METHOD__ . ' (' . __LINE__ . '): undefined value: "' . $conf_flexform . '".';
        die( $prompt );
        break;
    }
      // SWITCH

    return $from;
  }



/**
 * sql_groupBy( ): Get the GROUP BY statement. It returns the current tableField by default.
 *
 * @return	string		$groupBy : GROUP BY statement without a GROUP BY
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_groupBy( )
  {
      // Get WHERE statement
    $groupBy = $this->curr_tableField;

      // RETURN GROUP BY statement without GROUP BY
    return $groupBy;
  }



/**
 * sql_orderBy( ):  Get the ORDER BY statement. It depends on the TS configuration
 *                  filter.table.field.order
 *
 * @return	string		$orderBy : ORDER BY statement without ORDER BY
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_orderBy( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Short var
    $arr_order  = null;
    $arr_order  = $this->conf_view['filter.'][$table . '.'][$field . '.']['order.'];

      // Order field
    switch( true )
    {
      case( $arr_order['field'] == 'uid' ):
        $orderField = $this->sql_filterFields[$this->curr_tableField]['uid'];
        break;
      case( $arr_order['field'] == 'value' ):
      default:
        $orderField = $this->sql_filterFields[$this->curr_tableField]['value'];
        break;
    }
      // Order field

      // Order flag
    switch( true )
    {
      case( $arr_order['orderFlag'] == 'DESC' ):
        $orderFlag = 'DESC';
        break;
      case( $arr_order['orderFlag'] == 'ASC' ):
      default:
        $orderFlag = 'ASC';
        break;
    }
      // Order flag

      // Get ORDER BY statement
    $orderBy = $orderField . ' ' . $orderFlag;

      // RETURN ORDER BY statement
    return $orderBy;
  }



/**
 * sql_limit( ): Get the LIMIT statement. It is null by default.
 *
 * @return	string		$limit : LIMIT statement without a LIMIT
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_limit( )
  {
      // Get LIMIT statement
    $limit = null;

      // RETURN LIMIT statement
    return $limit;
  }









 /***********************************************
  *
  * SQL statements - where
  *
  **********************************************/



/**
 * sql_whereAllItems( ):  Get the WHERE statement for all items.
 *                        All items means: idependent of any hit.
 *
 * @return	string		$where : WHERE statement without WHERE
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAllItems( )
  {
    $where  = '1 ' .
              $this->sql_whereAnd_pidList( ) .
              $this->sql_whereAnd_enableFields( ) .
              $this->sql_whereAnd_Filter( ) .
              $this->sql_whereAnd_fromTS( ) .
              $this->sql_whereAnd_sysLanguage( );
      // Get WHERE statement

      // RETURN WHERE statement without a WHERE
    return $where;
  }



/**
 * sql_whereWiHits( ):  Get the WHERE statement for a filter, which should diplay
 *                      filter items with a hit only.
 *
 * @return	string		$where : WHERE statement without WHERE
 * @version 3.9.13
 * @since   3.9.9
 */
  private function sql_whereWiHits( )
  {
      // Get WHERE statement
    $where =  $this->pObj->objSqlInit->statements['listView']['where'] .
              $this->sql_whereAnd_Filter( ) .
              $this->sql_whereAnd_fromTS( );
      // Localise the WHERE statement
    $where =  $this->sql_whereWiHitsLL( $where );
//$this->pObj->dev_var_dump( $where );

      // RETURN WHERE statement without a WHERE
    return $where;
  }



/**
 * sql_whereWiHitsLL( ):  Add an andWhere for the localisation field of the local table.
 *                        Current language will be the default language in every case,
 *                        because local table records of the default language only
 *                        are connected with a category.
 *
 * @param	string		$where : current WHERE statement
 * @return	string		$where : WHERE statement added with localisation and Where
 * @version   3.9.13
 * @since     3.9.13
 */
  private function sql_whereWiHitsLL( $where )
  {
      // Short var
    $table = $this->pObj->localTable;

      // Store current localisation mode
    $curr_int_localisation_mode = $this->pObj->objLocalise->int_localisation_mode;
      // Set localisation mode to default language
    $this->pObj->objLocalise->int_localisation_mode = PI1_DEFAULT_LANGUAGE;

      // Get where localisation
    $llWhere  = $this->pObj->objLocalise->localisationFields_where( $table );
    if( $llWhere )
    {
      $where  = $where . " AND " . $llWhere;
    }
      // Get where localisation

      // Reset localisation mode
    $this->pObj->objLocalise->int_localisation_mode = $curr_int_localisation_mode;

      // RETURN
    return $where;
  }



/**
 * sql_whereAnd_enableFields( ): Get the AND WHERE statement with the enabled fields.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAnd_enableFields( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

    $andWhere = $this->pObj->cObj->enableFields( $table );

      // RETURN AND WHERE statement
    return $andWhere;
  }



/**
 * sql_whereAnd_Filter( ):
 *
 * @return	string
 * @version 3.9.12
 * @since   3.9.12
 */
  private function sql_whereAnd_Filter( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );
      // Flexform configuration
    $conf_flexform = $this->pObj->objFlexform->sheet_viewList_total_hits;

      // SWITCH : idependent versus controlled among others
    switch( true )
    {
      case( $conf_flexform == 'independent' ) :
        return false;
        break;
      case( $this->pObj->localTable != $table ) :
      case( $conf_flexform == 'controlled' ) :
      case( isset( $this->pObj->piVars['sword'] ) ):
        return $this->andWhereFilter;
        break;
      default;
        $prompt = __METHOD__ . ' (' . __LINE__ . '): undefined value: "' . $conf_flexform . '".';
        die( $prompt );
        break;
    }
    unset( $table );
      // SWITCH : idependent versus controlled among others


      // DIE : undefined value
    $prompt = __METHOD__ . ' (' . __LINE__ . '): undefined value: "' . $conf_flexform . '".';
    die( $prompt );
  }



/**
 * sql_whereAnd_fromTS( ):  Get the AND WHERE statement from the TS configuration.
 *                          See sql.andWhere.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAnd_fromTS( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS value
    $andWhere = $this->conf_view['filter.'][$table . '.'][$field . '.']['sql.']['andWhere'];

    if( ! empty ( $andWhere ) )
    {
      $andWhere = " AND " . $andWhere;
    }

      // RETURN AND WHERE statement
    return $andWhere;
  }



/**
 * sql_whereAnd_pidList( ): Get the AND WHERE statement with the pid list.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAnd_pidList( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

    if( empty ( $this->pObj->pidList ) )
    {
        // DRS
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'There isn\'t any pid list for the records of ' . $table . '. Maybe this is an error.';
        t3lib_div::devlog( '[WARN/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
        // DRS
      return;
    }

    $fieldsOfTable = $this->pObj->arr_realTables_arrFields[$table];
    if( ! in_array( 'pid', $fieldsOfTable ) )
    {
        // DRS
      if( $this->pObj->b_drs_warn )
      {
        $prompt = $table . ' shouldn\'t have any pid field. Maybe this is an error.';
        t3lib_div::devlog( '[WARN/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
        // DRS
      return;
    }

    $andWhere = " AND " . $table . ".pid IN (" . $this->pObj->pidList . ")";

      // RETURN AND WHERE statement
    return $andWhere;
  }



/**
 * sql_whereAnd_sysLanguage( ): Get the AND WHERE statement with the sys_language_uid.
 *                              It is an AND WHERE for tables which have a record for each
 *                              language.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAnd_sysLanguage( )
  {
    if( ! isset( $this->sql_filterFields[$this->curr_tableField]['languageField'] ) )
    {
      return;
    }

    $languageField  = $this->sql_filterFields[$this->curr_tableField]['languageField'];
    $languageId     = $GLOBALS['TSFE']->sys_language_content;

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = '$this->int_localisation_mode PI1_SELECTED_OR_DEFAULT_LANGUAGE: for each language a query!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
        $andWhere = " AND " . $languageField . " <= 0 ";
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        $andWhere = " AND ( " .
                      $languageField . " <= 0 OR " .
                      $languageField . " = " . intval( $languageId ) .
                    " ) ";
        break;
      case( PI1_SELECTED_LANGUAGE_ONLY ):
        $andWhere = " AND " . $languageField . " = " . intval( $languageId ) . " ";
        break;
      default:
        $andWhere = null;
        break;
    }

      // RETURN AND WHERE statement
    return $andWhere;
  }









 /***********************************************
  *
  * cObject
  *
  **********************************************/



/**
 * cObjData_init( ):  Init the cObj->data. Method
 *                    * saves the current data in the class var cObjDataBak.
 *                    * removes all data
 *                    * add basic data like mode and view
 *
 * @return	void
 * @internal  #33826, 120303, dwildt
 * @version   3.9.9
 * @since     3.9.9
 */
  private function cObjData_init( )
  {
      // Make a backup
    $this->cObjDataBak = $this->pObj->cObj->data;

      // DRS
    if( $this->pObj->b_drs_cObjData )
    {
      $prompt = implode( ', ', array_keys( $this->pObj->cObj->data ) );
      $prompt = 'cObj-data had this elements: ' . $prompt;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // Remove all data
    $this->pObj->cObj->data = null;

      // Add mode and view
    $this->pObj->cObj->data[ $this->pObj->prefixId . '.mode' ] = $this->pObj->piVar_mode;
    $this->pObj->cObj->data[ $this->pObj->prefixId . '.view' ] = $this->pObj->view;

      // DRS
    if( $this->pObj->b_drs_cObjData )
    {
      $prompt = 'Init - cObj->data has now this elements: ' . implode( ', ', array_keys( $this->pObj->cObj->data ) );
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }



/**
 * cObjData_reset( ): Reset the cObj->data array.
 *                    cObj->data becomes the values from before init.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_reset( )
  {
    $this->pObj->cObj->data = $this->cObjDataBak;

      // DRS
    if( $this->pObj->b_drs_cObjData )
    {
      $prompt = implode( ', ', array_keys( $this->pObj->cObj->data ) );
      $prompt = 'Reset - cObj-data became this elements: ' . $prompt;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }



/**
 * cObjData_setFlagDisplayInCaseOfNoCounting( ): Add the flag_displayInCaseOfNoCounting value to cObj->data.
 *
 * @return	void
 * @version 3.9.16
 * @since   3.9.16
 */
  private function cObjData_setFlagDisplayInCaseOfNoCounting( )
  {
    static $bool_DRSprompt = true;

    if( ! $this->ts_getDisplayInCaseOfNoCounting( ) )
    {
      return;
    }

      // Set key and value for treeview field
    $key    = $this->pObj->prefixId . '.flag_displayInCaseOfNoCounting';
    $value  = 1;

      // Set treeview field
    $this->pObj->cObj->data[ $key ] = $value;

      // DRS
    if( $this->pObj->b_drs_cObjData && $bool_DRSprompt )
    {
      $prompt = 'cObj->data[ ' . $key . '] = ' . $value;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
      $bool_DRSprompt = false;
    }
      // DRS
  }



/**
 * cObjData_unsetFlagDisplayInCaseOfNoCounting( ): Unset the flag_displayInCaseOfNoCounting field in cObj->data
 *
 * @return	void
 * @version 3.9.16
 * @since   3.9.16
 */
  private function cObjData_unsetFlagDisplayInCaseOfNoCounting( )
  {
    static $bool_DRSprompt = true;

    if( ! $this->ts_getDisplayInCaseOfNoCounting( ) )
    {
      return;
    }

      // Unset the treeview field
    $key = $this->pObj->prefixId . '.flag_displayInCaseOfNoCounting';
    unset( $this->pObj->cObj->data[ $key ] );

      // DRS
    if( $this->pObj->b_drs_cObjData && $bool_DRSprompt )
    {
      $prompt = 'cObj->data[ ' . $key . '] is unset.';
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
      $bool_DRSprompt = false;
    }
      // DRS
  }






/**
 * cObjData_setFlagTreeview( ): Add the flag_treeview value to cObj->data.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_setFlagTreeview( )
  {
      // Set key and value for treeview field
    $key    = $this->pObj->prefixId . '.flag_treeview';
    $value  = 1;

      // Set treeview field
    $this->pObj->cObj->data[ $key ] = $value;

      // DRS
    if( $this->pObj->b_drs_cObjData )
    {
      $prompt = 'cObj->data[ ' . $key . '] = ' . $value;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }



/**
 * cObjData_unsetFlagTreeview( ): Unset the flag_treeview field in cObj->data
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_unsetFlagTreeview( )
  {
      // UNset the treeview field
    $key = $this->pObj->prefixId . '.flag_treeview';
    unset( $this->pObj->cObj->data[ $key ] );

      // DRS
    if( $this->pObj->b_drs_cObjData )
    {
      $prompt = 'cObj->data[ ' . $key . '] is unset.';
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }



/**
 * cObjData_updateRow( ): Add the current row to cObj->data.
 *                        Method adds some values with this default keys:
 *                        * uid                 : current uid
 *                        * value               : value of the current filter item
 *                        * hits                : hits of the current filter item
 *                        * prefixed rowNumber  : number of current row
 *
 * @param	integer		$uid  : uid of the current item / row
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_updateRow( $uid )
  {
    static $firstVisit = true;

      // RETURN: empty row
    if( empty( $this->rows[$uid] ) )
    {
      return;
    }
      // RETURN: empty row

      // Add each element of the row to cObj->data
    foreach( ( array ) $this->rows[$uid] as $key => $value )
    {
      $this->pObj->cObj->data[ $key ] = $value;
    }

      // Add the field uid with the uid of the current row
    $key    = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $value  = $this->rows[$uid][$key];
    $this->pObj->cObj->data['uid'] = $value;

      // Add the field value with the value of the current row
    $key    = $this->sql_filterFields[$this->curr_tableField]['value'];
    $value  = $this->rows[$uid][$key];
    $this->pObj->cObj->data['value'] = $value;

      // Add the field hits with the hits of the filter item
    $key    = $this->sql_filterFields[$this->curr_tableField]['hits'];
    $value  = $this->rows[$uid][$key];
    $this->pObj->cObj->data['hits'] = $value;
//$this->pObj->dev_var_dump( $this->pObj->cObj->data['hits'] );

      // Add the field rowNumber with the number of the current row
    $key    = $this->pObj->prefixId . '.rowNumber';
    $value  = $this->itemsPerHtmlRow['currItemNumberInRow'];

      // DRS
    if( $firstVisit && $this->pObj->b_drs_cObjData )
    {
      foreach( ( array ) $this->pObj->cObj->data as $key => $value )
      {
        $arr_prompt[ ] = '\'' . $key . '\' => \'' . $value . '\'';
      }
      $prompt = 'cObj->data of the first row: ' . implode( '; ', ( array ) $arr_prompt );
      t3lib_div::devlog( '[OK/COBJ] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

    $firstVisit = false;
  }









 /***********************************************
  *
  * Localisation
  *
  **********************************************/



/**
 * localise( ):  Get the localised value
 *
 * @return	void
 * @version 3.9.13
 * @since   3.9.9
 */
  private function localise( )
  {
      // SWTCH localisation mode
      // RETURN value
    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
        return;
        break;
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        return;
        break;
      default:
        // Localisation mode is enabled
        // Follow the workflow
        break;
    }
      // RETURN value
      // SWTCH localisation mode

      // SWITCH language overlay or sys language
    switch( true )
    {
      case( $this->sql_filterFields[$this->curr_tableField]['lang_ol'] ):
        $this->localise_langOl( );
        break;
      case( $this->sql_filterFields[$this->curr_tableField]['transOrigPointerField'] ):
        $this->localise_sysLanguage( );
        break;
      default:
    }
      // SWITCH language overlay or sys language

  }



/**
 * localise_langOl( ):  Get the localised value
 *
 * @return	array		$value: value
 * @version 3.9.9
 * @since   3.9.9
 */
  private function localise_langOl( )
  {
    $boolOlPrefix = $this->pObj->objLocalise->conf_localisation['TCA.']['value.']['langPrefix'];

    switch( $boolOlPrefix )
    {
      case( true ):
        $this->localise_langOlWiPrefix( );
        break;
      case( false ):
      default:
        $this->localise_langOlWoPrefix( );
        break;
    }
  }



/**
 * localise_langOlWiPrefix( ):  Get the localised value
 *
 * @return	array		$value: value
 * @version 3.9.9
 * @since   3.9.9
 */
  private function localise_langOlWiPrefix( )
  {
      // Get the labels for the value field and the lang_ol field
    $valueField   = $this->sql_filterFields[$this->curr_tableField]['value'];
    $langOlField  = $this->sql_filterFields[$this->curr_tableField]['lang_ol'];

      // Get the language devider
    $devider      = $this->pObj->objLocalise->conf_localisation['TCA.']['value.']['devider'];

    // Get the language prefix
    $prefix       = $GLOBALS['TSFE']->lang . ':' ; // Value i.e.: 'de:'

      // LOOP rows
    foreach( $this->rows as $uid => $row )
    {
        // Get the language overlay value
      $langOlValue  = $row[$langOlField];

        ///////////////////////////////////////////////////////
        //
        // Get language overlay value for the current language

        // Build the pattern
        // i.e:
        //    preg_match( ~(\Aen:|\|en:)(.*)\|~U, en:Policy|fr:Politique|it:Politica, $matches )
        //    * (\Aen:|\|en:):
        //      Search for 'en:' at beginning or '|en:' everywhere
        //    * (.*)
        //      Get the whole string ...
        //    * \|~U
        //      ... until the first pipe
      $pattern      = '~(\A' . $prefix . '|\\' .  $devider . $prefix. ')(.*)(\\' .  $devider . '|\\z)~U';
        // Build the pattern

        // IF: Override default language with language overlay value
      if( preg_match( $pattern, $langOlValue, $matches ) )
      {
        $this->rows[$uid][$valueField] = $matches[2];
      }
        // IF: Override default language with language overlay value
        // Get language overlay value for the current language

        // DRS
      if( $this->pObj->b_drs_filter )
      {
        if( isset ( $matches[2] ) )
        {
          $prompt = 'preg_match( ' . $pattern . ', \'' . $langOlValue . '\', $matches )';
          t3lib_div :: devLog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'result of $matches[2] : ' . $matches[2];
          t3lib_div :: devLog( '[OK/FILTER] ' . $prompt, $this->pObj->extKey, -1 );
        }
        if( ! isset ( $matches[2] ) )
        {
          $prompt = 'preg_match( ' . $pattern . ', \'' . $langOlValue . '\', $matches ) hasn\'t any result!';
          t3lib_div :: devLog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
        }
      }
        // DRS
    }
      // LOOP rows
  }



/**
 * localise_langOlWoPrefix( ):  Get the localised value
 *
 * @return	array		$value: value
 * @version 3.9.9
 * @since   3.9.9
 */
  private function localise_langOlWoPrefix( )
  {
      // Get the labels for the value field and the lang_ol field
    $valueField   = $this->sql_filterFields[$this->curr_tableField]['value'];
    $langOlField  = $this->sql_filterFields[$this->curr_tableField]['lang_ol'];

      // Get the language devider
    $devider      = $this->pObj->objLocalise->conf_localisation['TCA.']['value.']['devider'];
      // Get position (language id)
    $lang_pos     = $GLOBALS['TSFE']->sys_language_content - 1;

      // LOOP rows
    foreach( $this->rows as $uid => $row )
    {
        // Get the language overlay value
      $langOlValue  = $row[$langOlField];
        // Devide language overlays to an array
      $langOlValues = explode( $devider, $langOlValue );
        // Get element with the language position
      $langValue    = $langOlValues[$lang_pos];

        // IF there is a language value
        // Override current value
      if( ! empty( $langValue ) )
      {
        $this->rows[$uid][$valueField] = $langValue;
      }
        // Override current value
        // IF there is a language value
    }
      // LOOP rows
  }



/**
 * localise_sysLanguage( ):  Get the localised value
 *
 * @return	array		$value: value
 * @version 3.9.9
 * @since   3.9.9
 */
  private function localise_sysLanguage( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get localisation configuration
    $l10n_mode        = null;
    $l10n_mode        = $GLOBALS['TCA'][$table]['columns'][$field]['l10n_mode'];
    $l10n_displayCsv  = $GLOBALS['TCA'][$table]['columns'][$field]['l10n_display'];
    $l10n_displayArr  = null;
    $l10n_displayArr  = $this->pObj->objZz->getCSVasArray( $l10n_displayCsv );
      // Get localisation configuration

      // RETURN current field isn't localised
    switch( true )
    {
      case( in_array( 'defaultAsReadonly', $l10n_displayArr ) ):
        return;
        break;
      case( $l10n_mode == 'exclude' ):
        return;
        break;
    }
      // RETURN current field isn't localised

      // Get SQL ressource for localised records
    $arr_return = $this->sql_resSysLanguageRows( );
    $res        = $arr_return['data']['res'];

    if( $arr_return['error']['status'] )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      die( $arr_return['error']['header'] . $arr_return['error']['prompt'] );
    }
    $res = $arr_return['data']['res'];
    unset( $arr_return );
      // Get SQL ressource for all filter items

      // Get rows
    $rows_sysLanguage = $this->sql_resToRows( $res );

      // Get label for gthe field with the language record pid
    $transOrigPointerField  = $this->sql_filterFields[$this->curr_tableField]['transOrigPointerField'];

      // Override class var $rows
    foreach( $rows_sysLanguage as $row_sysLanguage )
    {
      if( ! empty( $row_sysLanguage[$this->curr_tableField] ) )
      {
        $pidLl = $row_sysLanguage[$transOrigPointerField];
        $this->rows[$pidLl][$this->curr_tableField] = $row_sysLanguage[$this->curr_tableField];
      }
    }
      // Override class var $rows

    //:TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'sys_language: order rows!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
  }








 /***********************************************
  *
  * TypoScript values
  *
  **********************************************/



/**
 * ts_getAreas( ):  Get areas for the current filter from TS configuration
 *
 * @return	array		$areas: areas
 * @version 3.9.9
 * @since   3.9.9
 */
  private function ts_getAreas( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // #41811, dwildt, 1+
    $currHitsSum = $this->hits_sum[$this->curr_tableField];

      // Get TS configuration of the current filter / tableField
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Get areas from TS
      // SWITCH area key
    switch ( $this->pObj->objCal->arr_area[$this->curr_tableField]['key'] )
    {
      case ('strings') :
        $arr_result = $this->pObj->objCal->area_strings($conf_array, null, $this->curr_tableField);
        break;
      case ('interval') :
        $arr_result = $this->pObj->objCal->area_interval($conf_array, null, $this->curr_tableField);
        break;
//        case ('from_to_fields') :
//          break;
      default:
          // DRS - Development Reporting System
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'undefined value in switch: ' .
                    '\'' . $this->pObj->objCal->arr_area[$this->curr_tableField]['key'] . '\'.';
          t3lib_div :: devLog( '[ERROR/FILTER+CAL] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'Areas won\'t handled!';
          t3lib_div :: devLog( '[WARN/FILTER+CAL] ' . $prompt, $this->pObj->extKey, 2 );
        }
          // DRS - Development Reporting System
        return;
    }
      // SWITCH area key
    $areas = $arr_result['data']['values'];
    unset ($arr_result);
      // Get areas from TS

      // #41811, dwildt, 1+
    $this->hits_sum[$this->curr_tableField] = $currHitsSum;

      // DRS
    if( $this->pObj->b_drs_cal || $this->pObj->b_drs_filter )
    {
      $arr_prompt = null;
      foreach( ( array ) $areas as $key => $value )
      {
        $arr_prompt[] = '[' . $key . '] = ' . $value;
      }
      $prompt = 'values are: ' . implode( ', ', ( array ) $arr_prompt );
      t3lib_div :: devLog( '[INFO/FILTER+CAL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // RETURN areas
    return $areas;
  }



  /**
 * ts_getCondition( ):  Render the filter condition.
 *
 * @return	boolean		True, if filter should displayed, false if filter shouldn't diplayed
 * @version 3.9.3
 * @since   3.9.3
 */
  private function ts_getCondition( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );
    $tableField = $this->curr_tableField;

      // Get TS configuration array
    $conf_name = $this->conf_view['filter.'][$table . '.'][$field . '.']['condition'];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.']['condition.'];

      // RETURN true: any condition isn't defined
    if( empty ( $conf_name ) )
    {
      if ( $this->pObj->b_drs_filter )
      {
        $prompt = $tableField . ' hasn\'t any condition. Filter will displayed.';
        t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
      }
      return true;
    }
      // RETURN true: any condition isn't defined

      // Get condition result
    $value = $this->pObj->cObj->cObjGetSingle($conf_name, $conf_array);
    switch( $value )
    {
      case( false ):
        $bool_condition = false;
        if ( $this->pObj->b_drs_filter )
        {
          $prompt = 'Condition of ' . $tableField . ' is false. Filter won\'t displayed.';
          t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
      case( true ):
      default;
        $bool_condition = true;
        if ( $this->pObj->b_drs_filter )
        {
          $prompt = 'Condition of ' . $tableField . ' is true. Filter will displayed.';
          t3lib_div :: devLog('[INFO/FILTER] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
    }
      // Get condition result

      // RETURN condition result
    return $bool_condition;
  }



/**
 * ts_getDisplayHits( ):  Get the TS configuration for displaying hits.
 *
 * @return	string		$display_hits : value from TS configuration
 * @version 3.9.9
 * @since   3.9.9
 */
  private function ts_getDisplayHits( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Short var
    $currFilterWrap = $this->conf_view['filter.'][$table . '.'][$field . '.']['wrap.'];

      // Get TS value
    $display_hits = $currFilterWrap['item.']['display_hits'];

      // RETURN TS value
    return $display_hits;
  }



/**
 * ts_getDisplayInCaseOfNoCounting( ):  Get the TS configuration for displayInCaseOfNoCounting
 *
 * @return	string		$display_hits : value from TS configuration
 * @version 3.9.16
 * @since   3.9.16
 */
  private function ts_getDisplayInCaseOfNoCounting( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

    $displayInCaseOfNoCounting = $conf_array['wrap.']['item.']['displayInCaseOfNoCounting'];

      // RETURN TS value
    return $displayInCaseOfNoCounting;
  }



/**
 * ts_countHits( ):  Get the TS configuration for counting hits. Set the class var $count_hits
 *
 * @return	boolean		$count_hits : value from TS configuration
 * @version 4.2.21
 * @since   3.9.16
 */
  private function ts_countHits( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    if( $this->count_hits[$this->curr_tableField] != null )
    {
      return $this->count_hits[$this->curr_tableField];
    }


      // Short var
    $count_hits = $this->conf_view['filter.'][$table . '.'][$field . '.']['count_hits'];
    switch( $count_hits )
    {
      case( true ):
        $this->count_hits[$this->curr_tableField] = true;
        break;
      default:
        $this->count_hits[$this->curr_tableField] = false;
        break;
    }

      // RETURN
    return $this->count_hits[$this->curr_tableField];
  }









 /***********************************************
  *
  * Tree view helper
  *
  **********************************************/



/**
 * set_treeOneDim( ): Recursive method. It generates a one dimensional array.
 *                    Each array has upto three elements:
 *                    * [obligate] uid    : uid of the record
 *                    * [obligate] value  : value of the record
 *                    * [optional] array  : if the record has children ...
 *                                          It is 0 while starting.
 *
 * @param	integer		$uid_parent : Parent uid of the current record - for recursive calls.
 * @return	void		Result will be allocated to the class var $tmpOneDim
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.16
 * @since   3.9.9
 */
  private function tree_setOneDim( $uid_parent )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

    switch ( true )
    {
      case( count ( $this->arr_rowsTablefield ) == 1 ):
        $this->tree_setOneDimOneRow( $uid_parent );
        break;
      default:
        $this->tree_setOneDimDefault( $uid_parent );
        break;
    }

    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
  }



/**
 * set_treeOneDimOneRow( ):
 *                    * [obligate] uid    : uid of the record
 *                    * [obligate] value  : value of the record
 *
 * @param	integer		$uid_parent : Parent uid of the current record - for recursive calls.
 * @return	void		Result will be allocated to the class var $tmpOneDim
 * @internal        #32223, 120119, dwildt+
 * @version 4.1.7
 * @since   3.9.9
 */
  private function tree_setOneDimOneRow( $uid_parent )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

    foreach( $this->arr_rowsTablefield as $row )
    {
      $tsPath   = $uid_parent . '.' ;
      $this->tmpOneDim[$tsPath . 'uid']   = $row[$this->uidField];
      $this->tmpOneDim[$tsPath . 'value'] = $row[$this->valueField];
      break;
    }

    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
  }



/**
 * set_treeOneDimDefault( ): Recursive method. It generates a one dimensional array.
 *                    Each array has upto three elements:
 *                    * [obligate] uid    : uid of the record
 *                    * [obligate] value  : value of the record
 *                    * [optional] array  : if the record has children ...
 *                                          It is 0 while starting.
 *
 * @param	integer		$uid_parent : Parent uid of the current record - for recursive calls.
 * @return	void		Result will be allocated to the class var $tmpOneDim
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.16
 * @since   3.9.9
 */
  private function tree_setOneDimDefault( $uid_parent )
  {
    static $tsPath  = null;
    static $level   = 0;
    static $loops   = 0;

      // Prompt the expired time to devlog
    if( $level == 0 )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );
    }

      // LOOP rows
    foreach( $this->arr_rowsTablefield as $key => $row )
    {
        // CONTINUE current row isn't row with current $uid_parent
      if( $row[$this->treeParentField] != $uid_parent )
      {
        continue;
      }
        // CONTINUE current row isn't row with current $uid_parent

      $lastPath = $tsPath;
      $tsPath   = $tsPath . $key . '.' ;
      $this->tmpOneDim[$tsPath . 'uid']   = $row[$this->uidField];
      $this->tmpOneDim[$tsPath . 'value'] = $row[$this->valueField];
      $level++;
      $loops++;
      $this->tree_setOneDimDefault( $row[$this->uidField] );
      $level--;
      $tsPath   = $lastPath;
    }
      // LOOP rows
      //
      // Prompt the expired time to devlog
    if( $level == 0 )
    {
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end ( loops: ' . $loops . ')' );
    }
  }



/**
 * get_treeRendered( ): Method converts a one dimensional array to a multidimensional array.
 *                      It wraps every element of the array with ul and or li tags.
 *                      Wrapping depends in position and level of the element in the tree.
 *
 * @return	array		$arr_result : Array with the rendered elements
 * @internal            #32223, 120119, dwildt+
 * @version   3.9.9
 * @since     3.9.9
 */
  private function tree_getRendered( )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

    $arr_result = array( );

    static $firstCallDrsTreeview = true;

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];



      // Add first item
      // SWITCH display first item
    switch( $conf_array['first_item'] )
    {
      case( true ):
          // Set hits
        $hitsField  = $this->sql_filterFields[$this->curr_tableField]['hits'];
        $sum_hits   = ( int ) $this->hits_sum[$this->curr_tableField];
        $this->pObj->cObj->data[$hitsField] = $sum_hits;
          // Set hits
          // Render uid and value of the first item
        $first_item_uid   = $conf_array['first_item.']['option_value'];
        $tsValue          = $conf_array['first_item.']['cObject'];
        $tsConf           = $conf_array['first_item.']['cObject.'];
        $first_item_value = $this->pObj->cObj->cObjGetSingle( $tsValue, $tsConf );
          // Render uid and value of the first item
        $tmpOneDim  = array( 'uid'   => $first_item_uid   ) +
                      array( 'value' => $first_item_value );
//                      array( 'value' => $first_item_value ) +
//                      $this->tmpOneDim;
        if( ! empty ( $this->tmpOneDim ) )
        {
          $tmpOneDim  = $tmpOneDim +
                        $this->tmpOneDim;
        }
          // Render uid and value of the first item
        break;
      case( false ):
      default:
        $tmpOneDim  = $this->tmpOneDim;
        break;
    }
      // SWITCH display first item
      // Add first item
      // Move one dimensional array to an iterator
    $tmpArray     = $this->pObj->objTyposcript->oneDim_to_tree( $tmpOneDim );
    $rcrsArrIter  = new RecursiveArrayIterator( $tmpArray );
    $iterator     = new RecursiveIteratorIterator( $rcrsArrIter );
      // Move one dimensional array to an iterator

      // HTML id
    $cObj_name  = $conf_array['treeview.']['html_id'];
    $cObj_conf  = $conf_array['treeview.']['html_id.'];
    $html_id    = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // HTML id



      //////////////////////////////////////////////////////
      //
      // Loop values

      // Initial depth
      // SWITCH display first item
    switch( $conf_array['first_item'] )
    {
      case( true ):
        $last_depth = -1;
        break;
      case( false ):
      default:
        $last_depth = 0;
        break;
    }
      // SWITCH display first item
      // Initial depth

      // LOOP
    $bool_firstLoop = true;
    $loops          = 0;
    foreach( $iterator as $key => $value )
    {
        // CONTINUE $key is the uid. Save the uid.
      if( $key == 'uid' )
      {
        $curr_uid = $value;
        continue;
      }
        // CONTINUE $key is the uid. Save the uid.

      if( $bool_firstLoop )
      {
        $first_item_uid = $curr_uid;
      }


        // CONTINUE ERROR $key isn't value
      if( $key != 'value' )
      {
        echo 'ERROR: key != value.' . PHP_EOL . __METHOD__ . ' (Line: ' . __LINE__ . ')' . PHP_EOL;
        continue;
      }
        // CONTINUE ERROR $key isn't value

        // Render the value
//$this->pObj->dev_var_dump( $value )
      $item = $this->get_filterItem( $curr_uid, $value );
      //$item = '<a href="leglis-bid/?tx_browser_pi1%5Btx_leglisbasis_sector.brc_text%5D=1657&cHash=579a339049d1ca24815eadf0cd53371d">
      //          Baugewerbe (132)
      //        </a>';
//$this->pObj->dev_var_dump( $item );

        // CONTINUE: item is empty
      if( empty( $item ) )
      {
          // DRS
        if( $firstCallDrsTreeview && ( $this->pObj->b_drs_filter || $this->pObj->b_drs_cObjData ) )
        {
          $prompt = 'No value: [' . $key . '] won\'t displayed! Be aware: this log won\'t displayed never again.';
          t3lib_div :: devlog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Maybe TS configuration for [' . $key . '] is: display it only with a hit at least.';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'There is a workaround: please take a look in the manual for ' . $this->pObj->prefixId . '.flag_treeview.';
          t3lib_div :: devlog( '[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1 );
          $firstCallDrsTreeview = false;
        }
          // DRS
        continue;
      }
        // CONTINUE: item is empty

      $loops++;

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
                            $this->htmlSpaceLeft . $indent . '<ul id="' . $html_id . '_ul_' . $curr_uid . '">' . PHP_EOL .
                            $this->htmlSpaceLeft . $indent . '  <li id="' . $html_id . '_li_' . $curr_uid . '">',
                            $delta_depth
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
                            $this->htmlSpaceLeft . $indent .' </ul>' . PHP_EOL .
                            $this->htmlSpaceLeft . $indent . '</li>', $delta_depth
                          ) .
                          PHP_EOL .
                          $this->htmlSpaceLeft . $indent . '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          $last_depth   = $curr_depth;
          break;
            // Stop of sublevel
        default:
          $startTag = '</li>' . PHP_EOL .
                      $this->htmlSpaceLeft . $indent . '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          break;
      }
        // Render the start tag

        // Result array
      $arr_result[$curr_uid] = $startTag . $item;

      $bool_firstLoop = false;
    }
      // LOOP
      // Loop values

      // Render the end tag of the last item
    $endTag =                 '</li>' .
                              str_repeat
                              (
                                '</ul>' . PHP_EOL .
                                $this->htmlSpaceLeft . $indent . '</li>',
                                $curr_depth
                              ) .
                              PHP_EOL .
                              $this->htmlSpaceLeft . $indent . '</ul>';
    $arr_result[$curr_uid] =  $arr_result[$curr_uid] . $endTag  . PHP_EOL .
                              $this->htmlSpaceLeft . '</div>';
      // Render the end tag of the last item

    $arr_result[$first_item_uid] = $this->htmlSpaceLeft . '<div id="' . $html_id . '">' . $arr_result[$first_item_uid];

    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

      // RETURN the result
    return $arr_result;
  }









 /***********************************************
  *
  * Replace marker
  *
  **********************************************/



/**
 * replace_itemClass( ): Replaces the marker ###CLASS### with the value from TS
 *
 * @param	array		$conf_array : The TS configuration of the current filter
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemClass( $conf_array, $item )
  {

      // Get TS value
    if( empty( $conf_array['wrap.']['item.']['class'] ) )
    {
      $class = null;
    }
    else
    {
      $class = ' class="' . $conf_array['wrap.']['item.']['class'] . '"';
    }
      // Get TS value

      // Replace the marker
    $item = str_replace( '###CLASS###', $class, $item );

      // Workaround: Remove space
    $item = str_replace('class=" ', 'class="', $item);


      // RETURN content
    return $item;
  }



  /**
 * replace_itemSelected( ): Replaces the marker ###ITEM_SELECTED### with the value from TS
 *
 * @param	array		$conf_array : The TS configuration of the current filter
 * @param	integer		$uid        : The uid of the current item
 * @param	string		$value      : The value of the current item
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemSelected( $conf_array, $uid, $value, $item )
  {
      //////////////////////////////////////////////////////////
      //
      // Set bool_piVar

      // dwildt, 110102
      // Workaround: Because of new feature to filter a local table field
    $bool_piVar = false;
    if( $uid )
    {
      if( in_array( $uid, $this->nicePiVar['arr_piVar'] ) )
      {
        $bool_piVar = true;
      }
    }

    if( $value )
    {
      if( in_array( $value, $this->nicePiVar['arr_piVar'] ) )
      {
        $bool_piVar = true;
      }
    }
      // #29444: 110902, dwildt+
    $value_from_ts_area = $conf_array['area.']['interval.']['options.']['fields.'][$uid . '.']['value_stdWrap.']['value'];
    if( $value_from_ts_area )
    {
      if( in_array( $value_from_ts_area, $this->nicePiVar['arr_piVar'] ) )
      {
        $bool_piVar = true;
      }
    }
    if( empty ( $this->nicePiVar['arr_piVar'] ) )
    {
      if( $this->pObj->objCal->selected_period )
      {
        if( $this->pObj->objCal->selected_period == $value_from_ts_area )
        {
          $bool_piVar = true;
        }
      }
    }
      // Set bool_piVar
      // #29444: 110902, dwildt+

      // SWITCH bool_piVar
    switch( $bool_piVar )
    {
      case( false ):
        $conf_selected = null;
        break;
      case( true ):
      default:
        $conf_selected = ' ' . $conf_array['wrap.']['item.']['selected'];
        break;
    }
      // SWITCH bool_piVar

      // Replave marker
    $item = str_replace( '###ITEM_SELECTED###', $conf_selected, $item );

      // RETURN content
    return $item;
  }



/**
 * replace_itemStyle( ): Replaces the marker ###STYLE### with the value from TS
 *
 * @param	array		$conf_array : The TS configuration of the current filter
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemStyle( $conf_array, $item )
  {
      // Get TS value
    if( empty( $conf_array['wrap.']['item.']['style'] ) )
    {
      $style = null;
    }
    else
    {
      $style = ' style="' . $conf_array['wrap.']['item.']['style'] . '"';
    }
      // Get TS value

      // Replace the marker
    $item = str_replace( '###STYLE###', $style, $item );

      // RETURN content
    return $item;
  }



/**
 * replace_itemTitle( ):  Replaces the marker ###TITLE### with the value from TS.
 *                        Be aware: This method return null in every case!
 *
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.0.0
 * @todo    dwildt, 120504: $conf_array isn't used in the method. Has the method sense?
 */
  private function replace_itemTitle( $item )
  {
    static $firstLoop = true;

      // Get TS value
    $title = null;
      // Get TS value

      // Replace the marker
    $item = str_replace( ' ###TITLE###',  $title, $item );
    $item = str_replace( '###TITLE###',   $title, $item );

    if( $firstLoop )
    {
      if( $this->pObj->b_drs_devTodo )
      {
        $prompt = '###TITLE### is removed. It is the marker for a href title. Develope the code!';
        t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    $firstLoop = false;

      // RETURN content
    return $item;
  }



/**
 * replace_itemUid( ): Replaces the marker ###UID### with the given uid
 *
 * @param	string		$uid        : The uid of the current item
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemUid( $uid, $item )
  {
      // Replace the marker
    $item = str_replace( '###UID###', $uid, $item );

      // RETURN content
    return $item;
  }



/**
 * replace_itemUrl( ): Replaces the marker ###URL###
 *
 * @param	array		$conf_array : The TS configuration of the current filter
 * @param	string		$uid        : The uid of the current item
 * @param	string		$item   : The current item
 * @return	string		$item   :	Returns the wrapped item
 * @version 3.9.9
 * @since   3.6.1
 */
  private function replace_itemUrl( $conf_array, $uid, $item )
  {

      // RETURN no marker
    $pos = strpos( $item, '###URL###' );
    if( $pos === false )
    {
      return $item;
    }
      // RETURN no marker

      // Set value of the first item to null: it won't become an additional parameter below
    if( $uid == $conf_array['first_item.']['option_value'] )
    {
      $uid = null;
    }
      // Set value of the first item to null: it won't become an additional parameter below

      // Move value (10, 20, 30, ...) to url_stdWrap (i.e: 2011_Jan, 2011_Feb, 2011_Mar, ...)
    $uid = $this->pObj->objCal->area_get_urlPeriod( $conf_array, $this->curr_tableField, $uid );



      /////////////////////////////////////////////////////////
      //
      // Remove piVars temporarily

      // Store status
    $arr_currPiVars = $this->pObj->piVars;

      // Remove sort and pointer
    $pageBrowserPointerLabel = $this->conf['navigation.']['pageBrowser.']['pointer'];
    $arr_removePiVars = array( 'sort', $pageBrowserPointerLabel );

      // Remove 'plugin', if current plugin is the default plugin
    if( ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin )
    {
      $arr_removePiVars[] = 'plugin';
    }
      // Remove 'plugin', if current plugin is the default plugin
      // LOOP piVars for removing
    foreach( ( array ) $arr_removePiVars as $piVar )
    {
      if( isset( $this->pObj->piVars[$piVar] ) )
      {
        unset( $this->pObj->piVars[$piVar] );
      }
    }
      // LOOP piVars for removing
      // Remove piVars temporarily



      /////////////////////////////////////////////////////////
      //
      // Change $GLOBALS['TSFE']->id temporarily

    $int_tsfeId = $GLOBALS['TSFE']->id;
    if( ! empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Change $GLOBALS['TSFE']->id temporarily



      /////////////////////////////////////////////////////////
      //
      // Remove the filter fields temporarily

      // #9495, fsander
    $this->pObj->piVars = $this->pObj->objZz->removeFiltersFromPiVars
                                              (
                                                $this->pObj->piVars,
                                                $this->conf_view['filter.']
                                              );
      // Remove the filter fields temporarily



      /////////////////////////////////////////////////////////
      //
      // Calculate additional params for the typolink

    $additionalParams = null;
    foreach( ( array ) $this->pObj->piVars as $paramKey => $paramValue )
    {
      if( ! empty( $paramValue ) )
      {
        $additionalParams = $additionalParams . '&' .
                            $this->pObj->prefixId . '[' . $paramKey . ']=' . $paramValue;
      }
    }
    if( $uid )
    {
      $additionalParams = $additionalParams . '&' .
                          $this->pObj->prefixId . '[' . $this->curr_tableField . ']=' . $uid;
    }
      // Calculate additional params for the typolink



      /////////////////////////////////////////////////////////
      //
      // Build and render the typolink

    $arr_typolink['parameter']        = $GLOBALS['TSFE']->id;
    $arr_typolink['additionalParams'] = $additionalParams;
    $arr_typolink['useCacheHash']     = 1;
    $arr_typolink['returnLast']       = 'URL';

    $typolink  = $this->pObj->local_cObj->typoLink_URL($arr_typolink);
      // Build and render the typolink



      /////////////////////////////////////////////////////////
      //
      // Cleanup piVars and id

      // Reset $this->pObj->piVars
    $this->pObj->piVars   = $arr_currPiVars;
      // Reset $GLOBALS['TSFE']->id
    $GLOBALS['TSFE']->id  = $int_tsfeId;
      // Cleanup piVars and id

      // Replace the marker
    $item  = str_replace('###URL###', $typolink, $item);

      // Return the item
    return $item;
  }



/**
 * replace_marker( ): Render the current filter item.
 *
 * @param	array		$conf_name      : TS configuration object type of the current filter / tableField
 * @param	array		$conf_array     : TS configuration array of the current filter / tableField
 * @param	integer		$uid            : uid of the current item / row
 * @param	string		$value          : value of the current item / row
 * @return	string		$value_stdWrap  : The value stdWrapped
 * @version 3.9.9
 * @since   3.9.9
 */
  private function replace_marker( $coa_conf )
  {
      // Keep $coa_conf!
    $serialized_conf = serialize( $coa_conf );

      // Substitute marker recursive
    $return_conf  = $this->pObj->cObj->substituteMarkerInObject( $coa_conf, $this->markerArray );

      // Reinit $return_conf
    $coa_conf = unserialize($serialized_conf);

    return $return_conf;
  }









 /***********************************************
  *
  * Maximum items per HTML row
  *
  **********************************************/



/**
 * set_maxItemsPerHtmlRow( ): Set class var $itemsPerHtmlRow.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_maxItemsPerHtmlRow( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Set default values
    $maxItemsPerHtmlRow = false;
    $rowBegin       = null;
    $rowEnd         = null;
    $noItemValue    = null;
      // Set default values

      // SWITCH $conf_name
      // Set values
    switch( $conf_name )
    {
      case( 'CHECKBOX' ) :
      case( 'RADIOBUTTONS' ) :
        $maxItemsPerHtmlRow = $conf_array['wrap.']['itemsPerRow'];
        if( $maxItemsPerHtmlRow > 0 )
        {
          $str_row_wrap               = $conf_array['wrap.']['itemsPerRow.']['wrap'];
          list( $rowBegin, $rowEnd )  = explode( '|', $str_row_wrap );
          $noItemValue                = $conf_array['wrap.']['itemsPerRow.']['noItemValue'];
        }
        break;
      case( 'CATEGORY_MENU' ) :
      case( 'SELECTBOX' ) :
      default :
        // Do nothing
        break;
    }
      // SWITCH $conf_name

      // Set class var $htmlSpaceLeft
    $this->itemsPerHtmlRow['maxItemsPerHtmlRow']  = $maxItemsPerHtmlRow;
    $this->itemsPerHtmlRow['rowBegin']            = $rowBegin;
    $this->itemsPerHtmlRow['rowEnd']              = $rowEnd;
    $this->itemsPerHtmlRow['noItemValue']         = $noItemValue;
    $this->itemsPerHtmlRow['currRowNumber']       = 0;
      // #40354, #40354, 4.1.7, 1+
    $this->itemsPerHtmlRow['currItemNumberAbsolute'] = 0;
    $this->itemsPerHtmlRow['currItemNumberInRow'] = 0;

    return;
  }



/**
 * set_itemCurrentNumber( ):  Method increases the nummber of handled items.
 *                            Result is stored in the class var $itemsPerHtmlRow.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_itemCurrentNumber( )
  {
      // RETURN maxItemsPerHtmlRow is false
    if ( $this->itemsPerHtmlRow['maxItemsPerHtmlRow'] === false )
    {
      return;
    }
      // RETURN maxItemsPerHtmlRow is false

      // Increase item number
      // #40354, #40354, 4.1.7, 1+
    $this->itemsPerHtmlRow['currItemNumberAbsolute']++;
    $this->itemsPerHtmlRow['currItemNumberInRow']++;
  }



/**
 * get_maxItemsTagEndBegin( ):  Get the tag for end the current row and begin
 *                              a new row.
 *
 * @param	string		$item : current item
 * @return	string		$item : current item plus tag
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_maxItemsTagEndBegin( $item )
  {
      // RETURN maxItemsPerHtmlRow is false
    if ( $this->itemsPerHtmlRow['maxItemsPerHtmlRow'] === false )
    {
      return $item;
    }
      // RETURN maxItemsPerHtmlRow is false

//if( $this->curr_tableField == 'tx_greencars_engine.title' )
//{
//  $this->pObj->dev_var_dump( $this->itemsPerHtmlRow, $this->rows );
//}
    $maxItemsPerHtmlRow     = $this->itemsPerHtmlRow['maxItemsPerHtmlRow'];
    $currItemNumber         = $this->itemsPerHtmlRow['currItemNumberInRow'];
      // #40354, 4.1.7, 1+
    $currItemNumberAbsolute = $this->itemsPerHtmlRow['currItemNumberAbsolute'];

      // #40354, 4.1.7, 4+
    if ( $currItemNumberAbsolute >= ( count( $this->rows ) -1 ) )
    {
      return $item;
    }
      // #40354, 4.1.7, 4+

    if ( $currItemNumber >= ( $maxItemsPerHtmlRow - 1 ) )
    {
      $item         = $item . PHP_EOL .
                      $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowEnd'] . PHP_EOL .
                      $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowBegin'];
      $this->itemsPerHtmlRow['currRowNumber']++;
      $str_evenOdd  = $this->itemsPerHtmlRow['currRowNumber'] % 2 ? 'odd' : 'even';
      $item         = str_replace( '###EVEN_ODD###', $str_evenOdd, $item );
        // #40354, 4.1.7, 1+
      $this->itemsPerHtmlRow['currItemNumberInRow'] = -1;
    }
      // #40354, 4.1.7, 1-
    //$this->itemsPerHtmlRow['currItemNumberInRow']++;

//if( $this->curr_tableField == 'tx_greencars_engine.title' )
//{
//  $this->pObj->dev_var_dump( $this->itemsPerHtmlRow, $item );
//}
    return $item;
  }



/**
 * get_maxItemsWrapBeginEnd( ): Wrap all items with the begin tag and the end tag.
 *
 * @param	string		$items : current items
 * @return	string		$items : current items wrapped
 * @version 4.1.7
 * @since   3.9.9
 */
  private function get_maxItemsWrapBeginEnd( $items )
  {
      // RETURN maxItemsPerHtmlRow is false
    if ( $this->itemsPerHtmlRow['maxItemsPerHtmlRow'] === false )
    {
      return $items;
    }
      // RETURN maxItemsPerHtmlRow is false

      // Wrap $items
    $items  = $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowBegin'] . PHP_EOL .
              $items . PHP_EOL .
              $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowEnd'] . PHP_EOL;
      // Wrap $items

      // #40354, 4.1.7, 1+
    $items  = str_replace( '###EVEN_ODD###', 'even', $items );

      // RETURN content
    return $items;
  }









 /***********************************************
  *
  * Hits helper
  *
  **********************************************/



/**
 * set_hits( ): Prepend or append the hits to the current item.
 *              Hits will handled by stdWrap.
 *              If hits shouldn't displayed, method returns the given value.
 *
 * @param	integer		$uid    : uid of the current filter item
 * @param	string		$value  : value of the current filter item
 * @param	array		$row    : current row
 * @return	string		$value  : Value with hits or without hits
 * @version 3.9.9
 * @since   3.0.0
 */
  private function set_hits( $uid, $value, $row )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Set display hits flag
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $bool_displayHits       = $conf_array['first_item.']['display_hits'];
        $bool_displayEmptyHits  = $conf_array['first_item.']['display_hits.']['display_empty_hits'];
        break;
      default:
        $bool_displayHits       = $conf_array['wrap.']['item.']['display_hits'];
        $bool_displayEmptyHits  = $conf_array['wrap.']['item.']['display_hits.']['display_empty_hits'];
        break;
    }
      // SWITCH first item
      // Set display hits flag

      // RETURN hit shouldn't displayed
    if( ! $bool_displayHits )
    {
      return $value;
    }
      // RETURN hit shouldn't displayed

      // Get the label for the hit field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];
      // Get the hit
    $hits = $row[$hitsField];

      // IF there is no hit and empty hits shouldn't displayed
      // RETURN item without any hit stdWrap
    if( $hits < 1 && ( ! $bool_displayEmptyHits ) )
    {
      return $value;
    }
      // RETURN item without any hit stdWrap
      // IF there is no hit and empty hits shouldn't displayed

      // stdWrap the hit
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $stdWrap  = $conf_array['first_item.']['display_hits.']['stdWrap.'];
        break;
      default:
        $stdWrap  = $conf_array['wrap.']['item.']['display_hits.']['stdWrap.'];
        break;
    }
      // SWITCH first item
    $hits = $this->pObj->objWrapper->general_stdWrap( $hits, $stdWrap );
      // stdWrap the hit

      // Get behind flag
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $bool_behindItem = $conf_array['first_item.']['display_hits.']['behindItem'];
        break;
      default:
        $bool_behindItem = $conf_array['wrap.']['item.']['display_hits.']['behindItem'];
        break;
    }
      // SWITCH first item
      // Get behind flag

      // SWITCH behind flag
    switch( $bool_behindItem )
    {
      case( true ):
        $value = $value . $hits;
        break;
      default:
        $value = $hits . $value;
        break;
    }
      // SWITCH behind flag

    unset( $uid );
    return $value;
  }



/**
 * sum_hits( ): Count the hits of the current tableField.
 *              Store it in the class var $hits_sum[tableField]
 *              Workflow depends on default case or treeview case
 *
 * @param	string		$rows   : current rows
 * @return	void
 * @version 4.1.21
 * @since   3.0.0
 */
  private function sum_hits( $rows )
  {
      // Get the label for the hit field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // Init sum hits
    $sum_hits = 0;

      // Tree view flag
    $bTreeView = false;
    list( $table ) = explode( '.', $this->curr_tableField );
      // #41776, dwildt, 1-
//    if( in_array( $table, $this->pObj->objFltr3x->arr_tablesWiTreeparentfield ) )
      // #41776, dwildt, 1+
    if( in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      $bTreeView = true;
    }
      // Tree view flag

      // Tree view  : get lowest uid_parent
    if( $bTreeView )
    {
        // Get the field label
      $treeParentField = $this->sql_filterFields[$this->curr_tableField]['treeParentField'];
        // Set lowest uid_parent 'unlimited'
      $lowestPid = 9999999;
        // LOOP all rows : set lowest pid
      foreach( ( array ) $rows as $row )
      {
        if( ( $row[ $treeParentField ] < $lowestPid ) && ( $row[ $treeParentField ] !== null ) )
        {
          $lowestPid = $row[ $treeParentField ];
        }
      }
        // LOOP all rows : set lowest pid
    }
      // Tree view  : get lowest uid_parent

      // LOOP all rows  : count hits
    foreach( ( array ) $rows as $row )
    {
        // Default case : count each row
      if( ! $bTreeView )
      {
        $sum_hits = $sum_hits + $row[ $hitsField ];
      }
        // Default case : count each row
        // Tree view  case  : count top level rows only
      if( $bTreeView )
      {
        if( $row[ $treeParentField ] == $lowestPid )
        {
          $sum_hits = $sum_hits + $row[ $hitsField ];
        }
      }
        // Tree view  case  : count top level rows only
    }
      // LOOP all rows  : count hits

      // Set class var $this->hits_sum
    $this->hits_sum[$this->curr_tableField] = ( int ) $sum_hits;

    return;
  }



/**
 * set_markerArray( ): Render the current filter item.
 *
 * @param	integer		$uid        : uid of the current item / row
 * @param	string		$value      : value of the current item / row
 * @return	string		$item       : The rendered item
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_markerArray( )
  {
      // Add mode and view
    $this->markerArray['###MODE###']  = $this->pObj->piVar_mode;
    $this->markerArray['###VIEW###']  = $this->pObj->view;

      // Add cObj->data and piVars
    $this->markerArray = $this->pObj->objMarker->extend_marker_wi_cObjData( $this->markerArray );
    $this->markerArray = $this->pObj->objMarker->extend_marker_wi_pivars( $this->markerArray );
  }



/**
 * set_markerArrayUpdateRow( ): Render the current filter item.
 *
 * @param	integer		$uid        : uid of the current item / row
 * @param	string		$value      : value of the current item / row
 * @return	string		$item       : The rendered item
 * @version 4.1.12
 * @since   3.9.9
 */
  private function set_markerArrayUpdateRow( $uid )
  {
    foreach( ( array ) $this->rows[$uid] as $key => $value )
    {
      $marker                     = '###' . strtoupper( $key ) . '###';
      $this->markerArray[$marker] = $value;
    }

    $marker                     = '###VALUE###';
    $valueField                 = $this->sql_filterFields[$this->curr_tableField]['value'];
    $this->markerArray[$marker] = $this->rows[$uid][$valueField];

    $marker                     = '###UID###';
    $uidField                   = $this->sql_filterFields[$this->curr_tableField]['uid'];
      // #11401, 120918, dwildt, +
      // SWITCH : Value of uid depends on localtable or foreigntable
    list( $table ) = explode( '.', $uidField );
    switch( $table )
    {
      case( $this->pObj->localTable ):
          // Localtable: ###UID### will replaced by the value
        $this->markerArray[$marker] = $this->rows[$uid][$valueField];
          // #41372, 4.1.15, 120925, dwildt
          // Overwrite in case of the curretn filter is an area and type is 'strings'
        if( $this->pObj->objCal->arr_area[$this->curr_tableField]['key'] == 'strings' )
        {
          $this->markerArray[$marker] = $this->rows[$uid][$uidField];
        }
          // #41372, 4.1.15, 120925, dwildt
        break;
      default:
          // Foreigntable: ###UID### will replaced by the uid
        $this->markerArray[$marker] = $this->rows[$uid][$uidField];
        break;
    }
      // SWITCH : Value of uid depends on localtable or foreigntable
      // #11401, 120918, dwildt, +

    $marker                     = '###HITS###';
    $hitsField                  = $this->sql_filterFields[$this->curr_tableField]['hits'];
    $this->markerArray[$marker] = $this->rows[$uid][$hitsField];
  }









 /***********************************************
  *
  * Requirements
  *
  **********************************************/



/**
 * requiredMarker( )  : Check, whether a marker is configured in the HTML template
 *
 * @param	string		$tableField : label for the marker
 * @return	boolean		true: marker is configured; false: marker isn't configured
 * @version   3.9.9
 * @since     3.9.9
 */
  private function requiredMarker( $tableField )
  {
    if( $this->subpart === null )
    {
      $this->subpart = $this->pObj->cObj->getSubpart( $this->pObj->str_template_raw, '###SEARCHFORM###' );
    }

      // Convert table.field to HTML marker
    $htmlMarker = '###' . strtoupper( $tableField ) . '###';

      // RETURN false : HTML marker isn't a part of the current HTML subpart
    $pos = strpos( $this->subpart, $htmlMarker );
    if( $pos === false )
    {
      if( $this->pObj->b_drs_warn )
      {
        $prompt = $tableField . ' hasn\'t the correspondending HTML marker ' . $htmlMarker . '.';
        t3lib_div :: devlog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Please add ' . $htmlMarker . ' to the subpart ###SEARCHFORM###.';
        t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN false : HTML marker isn't a part of the current HTML subpart

      // RETURN: true : HTML marker is a part of the current HTML subpart
    return true;
  }









 /***********************************************
  *
  * Other helper
  *
  **********************************************/



/**
 * set_currFilterIsArea( ): Set the class var $bool_currFilterIsArea
 *
 * @return	void
 * @version 3.9.24
 * @since   3.9.9
 */
  private function set_currFilterIsArea( )
  {
      // 3.9.24, 120604, dwildt+
    if( empty ( $this->pObj->objCal->arr_area ) )
    {
      $this->bool_currFilterIsArea = false;
      return;
    }
      // 3.9.24, 120604, dwildt+
      // SWITCH current tableField is a filter with areas
      // Set class var $bool_currFilterIsArea
    switch( in_array( $this->curr_tableField, array_keys( $this->pObj->objCal->arr_area ) ) )
    {
      case( true ):
        $this->bool_currFilterIsArea = true;
        break;
      case( false ):
      default:
        $this->bool_currFilterIsArea = false;
        break;
    }
      // Set class var $bool_currFilterIsArea
      // SWITCH current tableField is a filter with areas

    return;
  }



/**
 * set_firstItem( ):  Adds the first item to the rows of the current filter.
 *                    Class var $rows.
 *                    If firstItem shouldn't displayed, nothing will happen.
 *
 * @return	void
 * @version 4.1.21
 * @since   3.9.9
 */
  private function set_firstItem( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // RETURN first item shouldn't displayed
    if( ! $conf_array['first_item'] )
    {
      return;
    }
      // RETURN first item shouldn't displayed

      // RETURN first item shouldn't displayed
    if( ! $conf_array['first_item.']['display_wo_items'] )
    {
      if( ( int ) $this->hits_sum[$this->curr_tableField] < 1 )
      {
        return;
      }
    }
      // RETURN first item shouldn't displayed

      // Get the labels for the fields uid and hits
    $uidField   = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $hitsField  = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // Get the uid of the first item
    $uid = $conf_array['first_item.']['option_value'];

      // LOOP all fields of current filter / tableField
    foreach( $this->sql_filterFields[$this->curr_tableField] as $field )
    {
        // SWITCH field
      switch( true )
      {
        case( $field == $uidField ):
          $firstItem[$uid][$uidField] = $uid;
          break;
        case( $field == $hitsField ):
          $firstItem[$uid][$hitsField] = ( int ) $this->hits_sum[$this->curr_tableField];
          break;
        default:
          $firstItem[$uid][$field] = null;
          break;
      }
        // SWITCH field
    }
      // LOOP all fields of current filter / tableField

      // Add first item to the rows of the current filter
    $this->rows = ( array) $firstItem + ( array ) $this->rows;

    return;
  }



/**
 * set_firstItemTreeView( ):  Adds the first item to the rows of the current filter.
 *                            Class var $rows.
 *                            If firstItem shouldn't displayed, nothing will happen.
 *
 * @return	void
 * @version 4.1.21
 * @since   3.9.9
 */
  private function set_firstItemTreeView( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // RETURN current filter isn't a tree view
      // #41776, dwildt, 2-
//      // @todo: 3x -> 4x
//    if( ! in_array( $table, $this->pObj->objFltr3x->arr_tablesWiTreeparentfield ) )
      // #41776, dwildt, 1+
    if( ! in_array( $table, $this->arr_tablesWiTreeparentfield ) )
    {
      return;
    }
      // RETURN current filter isn't a tree view

      // Prepend the first item to class var $rows
    $this->set_firstItem( );

    return;
  }



/**
 * set_htmlSpaceLeft( ): Set the left margin for HTML code. Class var $htmlSpaceLeft.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.0.0
 */
  private function set_htmlSpaceLeft( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Get TS value
    $int_space_left = $conf_array['wrap.']['item.']['nice_html_spaceLeft'];

      // Set class var $htmlSpaceLeft
    $this->htmlSpaceLeft = str_repeat(' ', $int_space_left);

    return;
  }



/**
 * set_nicePiVar( ): Set class var nicePiVar. Result depends on HTML multiple property.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.0.0
 */
  private function set_nicePiVar( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];


      // Get nice_piVar from TS
    $str_nicePiVar = $conf_array['nice_piVar'];
    if( empty ( $str_nicePiVar ) )
    {
      $str_nicePiVar = $this->curr_tableField;
    }
      // Get nice_piVar from TS

      // Set multiple flag
    switch( $conf_name )
    {
      case( 'CHECKBOX' ) :
        $bool_multiple = true;
        break;
      case( 'CATEGORY_MENU' ) :
      case( 'RADIOBUTTONS' ) :
        $bool_multiple = false;
        break;
      case( 'SELECTBOX' ) :
        $bool_multiple = $conf_array['multiple'];
        break;
      default :
        $bool_multiple = false;
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'multiple - undefined value in switch: \'' . $conf_name . '\'';
          t3lib_div :: devlog( '[ERROR/FILTER] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'multiple becomes false.';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        }
    }
      // Set multiple flag

      // SWITCH multiple flag
    switch( $bool_multiple )
    {
      case( false ):
        $key_piVar    = $this->pObj->prefixId . '[' . $str_nicePiVar . ']';
        $arr_piVar[0] = $this->pObj->piVars[$str_nicePiVar];
        break;
      case( true ):
      default:
        $key_piVar = $this->pObj->prefixId . '[' . $str_nicePiVar . '][]';
        $arr_piVar = ( array ) $this->pObj->piVars[$str_nicePiVar];
        break;
    }
      // SWITCH multiple flag

      // Remove empty piVars in $arr_piVar
    foreach( ( array ) $arr_piVar as $key => $value )
    {
      if( ! $value )
      {
        unset( $arr_piVar[$key] );
      }
    }
      // Remove empty piVars in $arr_piVar

      // Set class var nicePiVar
    $this->nicePiVar['key_piVar']  = $key_piVar;
    $this->nicePiVar['arr_piVar']  = $arr_piVar;
    $this->nicePiVar['nice_piVar'] = $str_nicePiVar;
      // Set class var nicePiVar

    return;
  }



/**
 * updateWizard( ): Checks, if TypoScript of the current view has deprecated properties.
 *                  It is relevant only, if the update wizard is enabled.
 *
 * @param	integer		$uid        : uid of the current item / row
 * @param	string		$value      : value of the current item / row
 * @return	string		$item       : The rendered item
 * @version 3.9.13
 * @since   3.9.13
 */
  private function updateWizard( $check )
  {
    static $loop_filterObject = false;

    if( ! $this->pObj->arr_extConf['updateWizardEnable'] )
    {
      return;
    }
      // Current IP has access
    if( ! $this->pObj->bool_accessByIP )
    {
      return;
    }

    list( $table, $field ) = explode( '.', $this->curr_tableField );

    switch( $check )
    {
      case( 'filter_cObject' ):
        if( $loop_filterObject )
        {
          return;
        }
        $loop_filterObject = true;

        if ( $this->conf_view['filter.'][$table . '.'][$field . '.']['first_item.']['display_without_any_hit'] )
        {
          $prompt_01 = '
            filter.' . $table . '.' . $field . '.first_item.display_without_any_hit is deprecated. <br />
            Please use: <br />
            filter.' . $table . '.' . $field . '.first_item.cObject
            ';
        }
        if ( $this->conf_view['filter.'][$table . '.'][$field . '.']['first_item.']['display_hits.'] )
        {
          $prompt_02 = '
            filter.' . $table . '.' . $field . '.first_item.display_hits is deprecated. <br />
            Please use: <br />
            filter.' . $table . '.' . $field . '.first_item.cObject
            ';
        }
        if ( $this->conf_view['filter.'][$table . '.'][$field . '.']['first_item.']['stdWrap.'] )
        {
          $prompt_03 = '
            filter.' . $table . '.' . $field . '.first_item.stdWrap is deprecated. <br />
            Please use: <br />
            filter.' . $table . '.' . $field . '.first_item.cObject
            ';
        }
        if ( $this->conf_view['filter.'][$table . '.'][$field . '.']['first_item.']['value_stdWrap.'] )
        {
          $prompt_04 = '
            filter.' . $table . '.' . $field . '.first_item.value_stdWrap is deprecated. <br />
            Please use: <br />
            filter.' . $table . '.' . $field . '.first_item.cObject
            ';
        }
        if( $prompt_01 . $prompt_02 . $prompt_03 . $prompt_04 )
        {
          echo '
            <div style="border:1em solid red;padding:2em;background:white;">
              <h1>TYPO3 Browser Update Wizard</h1>
            ';
          if( $prompt_01 )
          {
            echo '
                <p>
                  ' . $prompt_01 . '
                </p>
              ';
          }
          if( $prompt_02 )
          {
            echo '
                <p>
                  ' . $prompt_02 . '
                </p>
              ';
          }
          if( $prompt_03 )
          {
            echo '
                <p>
                  ' . $prompt_03 . '
                </p>
              ';
          }
          if( $prompt_04 )
          {
            echo '
                <p>
                  ' . $prompt_04 . '
                </p>
              ';
          }
          echo '
            </div>
            ';
        }
        break;
    }
  }

  /**
 * zz_getNicePiVar( ): Returns an array with key_piVar, arr_piVar and nice_piVar
 *
 * @param	string		$tableField: The current table.field from the ts filter array
 * @return	array		Data array with the selectbox at least
 * @version 4.1.21
 * @since   2.x
 */
  function zz_getNicePiVar( $tableField )
  {
    $arr_piVar  = null;
    $arr_return = null;

    list ($table, $field) = explode( '.', $tableField );

      // SWITCH : default $tableField versus 'oderBy'
    switch( $tableField )
    {
      case( 'orderBy' ):
        $conf_name  = $this->pObj->objTemplate->lDisplayList['selectBox_orderBy.']['selectbox'];
        $conf_array = $this->pObj->objTemplate->lDisplayList['selectBox_orderBy.']['selectbox.'];
        break;
      default:
        $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
        $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];
        break;
    }
      // SWITCH : default $tableField versus 'oderBy'

      // SWITCH : set default $strNicePiVar
    switch( $conf_array['nice_piVar'] )
    {
      case( true ):
        $strNicePiVar = $conf_array['nice_piVar'];
        break;
      case( false ):
      default:
        $strNicePiVar = $tableField;
        break;
    }
      // SWITCH : set default $strNicePiVar

      // SWITCH : set multiple
    switch( $conf_name )
    {
      case ('CHECKBOX') :
        $bool_multiple = true;
        break;
      case ('CATEGORY_MENU') :
      case ('RADIOBUTTONS') :
        $bool_multiple = false;
        break;
      case ('SELECTBOX') :
        $bool_multiple = $conf_array['multiple'];
        break;
      default :
        $bool_multiple = false;
        if ($this->pObj->b_drs_error)
        {
          $prompt = 'multiple - undefined value in switch: \'' . $conf_name . '\'';
          t3lib_div :: devlog( '[ERROR/FILTER] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'multiple becomes false.';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 3 );
        }
    }
      // SWITCH : set multiple

      // SWITCH : set piVar depending on multiple
    switch( $bool_multiple )
    {
      case( false ):
        $key_piVar    = $this->pObj->prefixId . '[' . $strNicePiVar . ']';
        $arr_piVar[0] = $this->pObj->piVars[$strNicePiVar];
        break;
      case( true ):
      default:
        $key_piVar = $this->pObj->prefixId . '[' . $strNicePiVar . '][]';
        $arr_piVar = $this->pObj->piVars[$strNicePiVar];
    }
      // SWITCH : set piVar depending on multiple

      // LOOP : each piVar
    foreach( ( array ) $arr_piVar as $key => $value )
    {
      if( ! $value )
      {
        unset( $arr_piVar[$key] );
      }
    }
      // LOOP : each piVar

    $arr_return['data']['key_piVar']  = $key_piVar;
    $arr_return['data']['arr_piVar']  = $arr_piVar;
    $arr_return['data']['nice_piVar'] = $strNicePiVar; // Bugfix #7159, 100429

    return $arr_return;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']);
}
?>