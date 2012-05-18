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
 * @version      3.9.13
 * @since        3.9.9
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  167: class tx_browser_pi1_filter_4x
 *  233:     function __construct($pObj)
 *
 *              SECTION: Main
 *  266:     public function get( )
 *
 *              SECTION: Init and reset
 *  355:     private function init( )
 *  397:     private function init_andWhereFilter( )
 *  427:     private function init_calendarArea( )
 *  453:     private function init_localisation( )
 *  502:     private function reset( )
 *
 *              SECTION: Filter rendering
 *  537:     private function get_filter( )
 *  603:     private function get_filterItems( )
 *  675:     private function get_filterItemsFromRows( )
 *  742:     private function get_filterItemsDefault( )
 *  793:     private function get_filterItemsTree( )
 *  893:     private function get_filterItemsWrap( $items )
 * 1001:     private function get_filterItem( $uid, $value )
 * 1067:     private function set_markerArray( )
 * 1095:     private function set_markerArrayUpdateRow( $uid )
 * 1135:     private function get_filterItemValueStdWrap( $conf_name, $conf_array, $uid, $value )
 * 1231:     private function get_filterItemCObj( $conf_name, $conf_array, $uid, $value )
 * 1323:     private function get_filterTitle( )
 * 1445:     private function get_filterWrap( $items )
 *
 *              SECTION: Areas
 * 1506:     private function areas_toRows( )
 * 1548:     private function areas_toRowsConverter( $areas )
 * 1603:     private function areas_countHits( $areas )
 * 1671:     private function areas_wiHitsOnly( $areas )
 *
 *              SECTION: Rows
 * 1728:     private function get_rows( )
 * 1767:     private function get_rowsWiHits( )
 * 1810:     private function get_rowsAllItems( $rows_wiHits )
 *
 *              SECTION: SQL ressources
 * 1888:     private function sql_resAllItems( )
 * 1965:     private function sql_resSysLanguageRows( )
 * 2047:     private function sql_resWiHits( )
 *
 *              SECTION: SQL ressources to rows
 * 2135:     private function sql_resToRows( $res )
 * 2175:     private function sql_resToRows_allItemsWiHits( $res, $rows_wiHits )
 *
 *              SECTION: SQL statements - select
 * 2249:     private function sql_select( $bool_count )
 * 2326:     private function sql_select_addLL( )
 * 2361:     private function sql_select_addLL_sysLanguage( )
 * 2436:     private function sql_select_addLL_langOl(  )
 * 2502:     private function sql_select_addTreeview( )
 *
 *              SECTION: SQL statements - from, groupBy, orderBy, limit
 * 2606:     private function sql_from( )
 * 2658:     private function sql_groupBy( )
 * 2683:     private function sql_orderBy( )
 * 2739:     private function sql_limit( )
 *
 *              SECTION: SQL statements - where
 * 2778:     private function sql_whereAllItems( )
 * 2807:     private function sql_whereWiHits( )
 * 2833:     private function sql_whereAnd_enableFields( )
 * 2853:     private function sql_whereAnd_Filter( )
 * 2887:     private function sql_whereAnd_fromTS( )
 * 2919:     private function sql_whereAnd_pidList( )
 * 2972:     private function sql_whereAnd_sysLanguage( )
 *
 *              SECTION: cObject
 * 3043:     private function cObjData_init( )
 * 3083:     private function cObjData_reset( )
 * 3106:     private function cObjData_setTreeview( )
 * 3133:     private function cObjData_unsetTreeview( )
 * 3163:     private function cObjData_updateRow( $uid )
 *
 *              SECTION: Localisation
 * 3243:     private function localise( )
 * 3295:     private function localise_langOl( )
 * 3326:     private function localise_langOlWiPrefix( )
 * 3404:     private function localise_langOlWoPrefix( )
 * 3452:     private function localise_sysLanguage( )
 *
 *              SECTION: TypoScript values
 * 3541:     private function ts_getAreas( )
 * 3612:     private function ts_getCondition( )
 * 3677:     private function ts_getDisplayHits( )
 * 3708:     private function ts_getDisplayWithoutAnyHit( )
 *
 *              SECTION: Tree view helper
 * 3776:     private function tree_setOneDim( $uid_parent )
 * 3815:     private function tree_getRendered( )
 *
 *              SECTION: Replace marker
 * 4039:     private function replace_itemClass( $conf_array, $item )
 * 4083:     private function replace_itemSelected( $conf_array, $uid, $value, $item )
 * 4166:     private function replace_itemStyle( $conf_array, $item )
 * 4204:     private function replace_itemTitle( $conf_array, $item )
 * 4248:     private function replace_itemUid( $conf_array, $uid, $item )
 * 4275:     private function replace_itemUrl( $conf_array, $uid, $item )
 * 4426:     private function replace_marker( $coa_conf )
 *
 *              SECTION: Maximum items per HTML row
 * 4469:     private function set_maxItemsPerHtmlRow( )
 * 4535:     private function set_itemCurrentNumber( )
 * 4566:     private function get_maxItemsTagEndBegin( $item )
 * 4606:     private function get_maxItemsWrapBeginEnd( $items )
 *
 *              SECTION: Hits helper
 * 4659:     private function set_hits( $uid, $value, $row )
 * 4766:     private function sum_hits( $rows )
 *
 *              SECTION: Other helper
 * 4820:     private function set_currFilterIsArea( )
 * 4857:     private function set_firstItem( )
 * 4923:     private function set_firstItemTreeView( )
 * 4956:     private function set_htmlSpaceLeft( )
 * 4989:     private function set_nicePiVar( )
 *
 * TOTAL FUNCTIONS: 82
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

    // [Boolean] If a filter is selected by the visitor, this boolean will be true.
  var $aFilterIsSelected = null;










  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj) {
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
//      foreach( ( array ) $fields as $field => $confField )
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
    $this->reset( );

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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init( )
  {
      // Set class var $arr_conf_tableFields
      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Integrate $this->pObj->oblFltr3x->arr_conf_tableFields!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Integrate $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:
    $this->pObj->oblFltr3x->get_tableFields( );
      // Set class var $arr_conf_tableFields

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
 * init_andWhereFilter( ):
 *
 * @return	void
 * @version 3.9.12
 * @since   3.9.12
 */
  private function init_andWhereFilter( )
  {

    if( ! ( $this->andWhereFilter === null ) )
    {
      return $this->andWhereFilter;
    }

    $arrAndWhere = $this->pObj->oblFltr3x->andWhere_filter( );
    $strAndWhere = implode(" AND ", ( array ) $arrAndWhere );

    if( empty( $strAndWhere ) )
    {
      $this->andWhereFilter = false;
      return;
    }

    $this->andWhereFilter = " AND ". $strAndWhere;
  }



  /**
   * init_aFilterIsSelected( ): Sets the class var $aFilterIsSelected. The boolaen
   *                            is true, if a filter is an element of the piVars.
   *
   * @return	boolean $aFilterIsSelected: true, if a filter is slected
   * @version 3.9.12
   * @since   3.9.12
   */
  public function init_aFilterIsSelected( )
  {
      // RETURN : var is initialised
    if( ! $this->aFilterIsSelected === null )
    {
      return $this->aFilterIsSelected;
    }
      // RETURN : var is initialised

      // RETURN : no piVars, set var to false
    if( empty( $this->pObj->piVars ) )
    {
      $this->aFilterIsSelected = false;
      return $this->aFilterIsSelected;
    }
      // RETURN : no piVars, set var to false

    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( array_keys ( ( array ) $fields ) as $fieldWiDot )
//      foreach( ( array ) $fields as $fieldWiDot => $elements )
      {
        if( substr( $fieldWiDot, -1 ) != '.' )
        {
          continue;
        }
        $field      = substr($fieldWiDot, 0, -1);
        $tableField = $tableWiDot . $field;
        if( isset( $this->pObj->piVars[$tableField] ) )
        {
          $this->aFilterIsSelected = true;
          return $this->aFilterIsSelected;
        }
      }
    }

    $this->aFilterIsSelected = false;
    return $this->aFilterIsSelected;
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
 * reset( ):  Reset some vars
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function reset( )
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
 * @version 3.9.9
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
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Set nice_piVar
    $this->set_nicePiVar( );

      // Set class var $htmlSpaceLeft
    $this->set_htmlSpaceLeft( );

      // Set class var $maxItemsPerHtmlRow
    $this->set_maxItemsPerHtmlRow( );

      // SWITCH current filter is a tree view
    switch( in_array( $table, $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield ) )
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
 * get_filterItemsFromRows( ):  Render the given rows of the current tableField.
 *                      It returns the rendered filter as a string.
 *
 * @return	array		$arr_return : $arr_return['data']['items']
 * @version 3.9.9
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
    switch( in_array( $table, $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield ) )
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
    $item                       = null;
    $arr_return['data']['item'] = $item;

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

    $arr_return['data']['items'] = $items;
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
    $this->cObjData_setTreeview( );
      // Set marker treeview
    $this->markerArray['###TREEVIEW###'] = 1;

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    //$conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Parent uid of the root records: 0 of course
    $uid_parent = 0;

      // Needed for tree_setOneDim( )
    $this->arr_rowsTablefield = $this->rows;


      // Get the labels for the fields uid, value and treeParentField
    $this->uidField         = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $this->valueField       = $this->sql_filterFields[$this->curr_tableField]['value'];
    $this->treeParentField  = $this->sql_filterFields[$this->curr_tableField]['treeParentField'];



      //////////////////////////////////////////////////////
      //
      // Order the values

      // Get the values for ordering
    $arr_value = array( );
    foreach ( $this->arr_rowsTablefield as $key => $row )
    {
      $arr_value[$key] = $row[$this->valueField];
    }
      // Get the values for ordering

      // Set DESC or ASC
    if ( strtolower( $conf_array['order.']['orderFlag'] ) == 'desc' )
    {
      $order = SORT_DESC;
    }
    if ( strtolower( $conf_array['order.']['orderFlag'] ) != 'desc' )
    {
      $order = SORT_ASC;
    }
      // Set DESC or ASC

      // Order the rows
    array_multisort($arr_value, $order, $this->arr_rowsTablefield);
      // Order the values


    unset( $this->tmpOneDim );
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
    $this->cObjData_unsetTreeview( );
      // Unset marker treeview
    unset( $this->markerArray['###TREEVIEW###'] );

      // RETURN
    $arr_return['data']['items'] = $items;
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
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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
          // Wrap the current valie by the cObject
        $this->updateWizard( 'filter_cObject' );
        $item = $this->get_filterItemCObj( $conf_name, $conf_array, $uid, $value );
        break;
      case( 3 ):
      default:
          // stdWrap the current value
        $item = $this->get_filterItemValueStdWrap( $conf_name, $conf_array, $uid, $value );
        break;
    }
      // DEVELOPMENT: Browser engine 4.x


    $this->set_itemCurrentNumber( );

      // Reset cObj->data
    $this->pObj->cObj->data = $cObjDataBak;

    return $item;
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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_markerArrayUpdateRow( $uid )
  {
    foreach( $this->rows[$uid] as $key => $value )
    {
      $marker                     = '###' . strtoupper( $key ) . '###';
      $this->markerArray[$marker] = $value;
    }

    $uidField                   = $this->sql_filterFields[$this->curr_tableField]['uid'];
    $marker                     = '###UID###';
    $this->markerArray[$marker] = $this->rows[$uid][$uidField];

    $valueField                 = $this->sql_filterFields[$this->curr_tableField]['value'];
    $marker                     = '###VALUE###';
    $this->markerArray[$marker] = $this->rows[$uid][$valueField];

    $hitsField                  = $this->sql_filterFields[$this->curr_tableField]['hits'];
    $marker                     = '###HITS###';
    $this->markerArray[$marker] = $this->rows[$uid][$hitsField];
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
 * @param	array		$conf_name      : TS configuration object type of the current filter / tableField
 * @param	array		$conf_array     : TS configuration array of the current filter / tableField
 * @param	integer		$uid            : uid of the current item / row
 * @param	string		$value          : value of the current item / row
 * @return	string		$value_stdWrap  : The value stdWrapped
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_filterItemCObj( $conf_name, $conf_array, $uid, $value )
  {
    static $firstLoop = true;

      // Item class
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
      // Item class

      // Item style
    $this->markerArray['###STYLE###']         = $this->replace_itemStyle( $conf_array, '###STYLE###' );
      // Item title
    $this->markerArray['###TITLE###']         = $this->replace_itemTitle( '###TITLE###' );
      // Item URL
    $this->markerArray['###URL###']           = $this->replace_itemUrl( $conf_array, $uid, '###URL###' );
      // Item selected
    $this->markerArray['###ITEM_SELECTED###'] = $this->replace_itemSelected( $conf_array, $uid, $value, '###ITEM_SELECTED###' );

      // Workaround: remove ###ONCHANGE###
    $item = str_replace( ' class=" ###ONCHANGE###"', null, $item );
    if( $firstLoop && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'class=" ###ONCHANGE###" is removed. Check the code!';
      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
    }
      // Workaround: remove ###ONCHANGE###


    $conf_array = $this->replace_marker( $conf_array );


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

    $item  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );

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

    return $item;
  }









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
 * @param	[type]		$$areas: ...
 * @return	array		$areas : $areas with counted hits
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
//    foreach( $areas as $areas_uid => $areas_row )
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
 * @param	[type]		$$areas: ...
 * @return	array		$areas : all rows or rows with one hit at least only
 * @package array   $areas : rows of the current area
 * @version 3.9.9
 * @since   3.9.9
 */
  private function areas_wiHitsOnly( $areas )
  {
      // RETURN all areas
    if( $this->ts_getDisplayWithoutAnyHit( ) )
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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rows( )
  {
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // 1. step: filter items with one hit at least
    $arr_return = $this->get_rowsWiHits( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
      // 1. step: filter items with one hit at least

      // 2. step: all filter items, hits will be taken from $rows
    $arr_return = $this->get_rowsAllItems( $rows );
      // 2. step: all filter items, hits will be taken from $rows

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
 *
 *                                      which have one hit at least
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

      // RETURN display items only, if they have one hit at least
    if( ! $this->ts_getDisplayWithoutAnyHit( ) )
    {
      $arr_return['data']['rows'] = $rows_wiHits;
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );
      return $arr_return;
    }
      // RETURN display items only, if they have one hit at least

      // SWITCH localTable versus foreignTable
    switch( true )
    {
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
        $rows = $rows_wiHits;
        break;
          // local table
    }
      // SWITCH localTable versus foreignTable

      // RETURN rows
    $arr_return['data']['rows'] = $rows;
    return $arr_return;
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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resAllItems( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // Don't count hits
    $bool_count = false;

      // Query for all filter items
    $select   = $this->sql_select( $bool_count );
      //$from     = $table;
    $from     = $this->sql_from( );
    $where    = $this->sql_whereAllItems( );
    $groupBy  = $this->curr_tableField;
    $orderBy  = $this->sql_orderBy( );
    $limit    = $this->sql_limit( );

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
 * @version 3.9.9
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

      // Add table to arr_tablesWiTreeparentfield
    $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield[] = $table;

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
//    $this->pObj->dev_var_dump( $this->pObj->arr_realTables_arrFields );

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
 * @param     string      $where : current WHERE statement
 * @return    string      $where : WHERE statement added with localisation and Where
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

      // SWITCH
    switch( true )
    {
      case( $conf_flexform == 'controlled' ) :
      case( isset( $this->pObj->piVars['sword'] ) ):        
$this->pObj->dev_var_dump( $this->andWhereFilter );
        return $this->andWhereFilter;
        break;
      case( $conf_flexform == 'independent' ) :
$this->pObj->dev_var_dump( false );
        return false;
        break;
      case( $this->pObj->localTable != $table ) :
        return $this->andWhereFilter;
        break;
      default;
        $prompt = __METHOD__ . ' (' . __LINE__ . '): undefined value: "' . $conf_flexform . '".';
        die( $prompt );
        break;
    }
      // SWITCH

    
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
 * @return	viod
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
 * cObjData_setTreeview( ): Add the treeview value to cObj->data.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_setTreeview( )
  {
      // Set key and value for treeview field
    $key    = $this->pObj->prefixId . '.treeview';
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
 * cObjData_unsetTreeview( ): Unset the treeview field in cObj->data
 *
 * @return	string		$item       : The rendered item
 * @version 3.9.9
 * @since   3.9.9
 */
  private function cObjData_unsetTreeview( )
  {
      // UNset the treeview field
    $key = $this->pObj->prefixId . '.treeview';
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

      // Add the field rowNumber with the number of the current row
    $key    = $this->pObj->prefixId . '.rowNumber';
    $value  = $this->itemsPerHtmlRow['currItemNumber'];

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
 * ts_getDisplayWithoutAnyHit( ):  Get the TS configuration for displaying items without hits.
 *                              If current filter is a tree view, return value is true.
 *
 * @return	string		$display_without_any_hit : value from TS configuration
 * @version 3.9.9
 * @since   3.9.9
 */
  private function ts_getDisplayWithoutAnyHit( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Short var
    $currFilterWrap = $this->conf_view['filter.'][$table . '.'][$field . '.']['wrap.'];

      // Get TS value
    $display_without_any_hit = $currFilterWrap['item.']['display_without_any_hit'];

      // RETURN ts value directly: filter isn't a tree view filter
    if ( ! in_array( $table, $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield ) )
    {
      return $display_without_any_hit;
    }
      // RETURN ts value directly: filter isn't a tree view filter

      // RETURN true: filter is a tree view filter
      // DRS - Development Reporting System
    if( $this->pObj->b_drs_filter )
    {
      if( $display_without_any_hit == false )
      {
        $prompt = 'wrap.item.display_without_any_hit is false. But ' . $this->curr_tableField . ' is displayed in a tree view: display_without_any_hit is set to true!';
        t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System

      // RETURN
    return true;
      // RETURN true: filter is a tree view filter
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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function tree_setOneDim( $uid_parent )
  {
    static $tsPath = null;

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
      $this->tree_setOneDim( $row[$this->uidField] );
      $tsPath   = $lastPath;
    }
      // LOOP rows
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
          // Render uid and value of the first item
        $first_item_uid   = $conf_array['first_item.']['option_value'];
        $tsValue          = $conf_array['first_item.']['value_stdWrap.']['value'];
        $tsConf           = $conf_array['first_item.']['value_stdWrap.'];
        $first_item_value = $this->pObj->local_cObj->stdWrap( $tsValue, $tsConf );
          // Render uid and value of the first item
        $tmpOneDim  = array( 'uid'   => $first_item_uid   ) +
                      array( 'value' => $first_item_value ) +
                      $this->tmpOneDim;
        break;
      case( false ):
      default:
        $tmpOneDim  = $this->tmpOneDim;
        break;
    }
      // SWITCH display first item
      // Add first item
//$this->pObj->dev_var_dump( $tmpOneDim );
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
      $item = $this->get_filterItem( $curr_uid, $value );

        // CONTINUE: item is empty
      if( empty( $item ) )
      {
          // DRS
        if( $firstCallDrsTreeview && ( $this->pObj->b_drs_filter || $this->pObj->b_drs_cObjData ) )
//        if( 1 || $this->pObj->b_drs_filter )
        {
          $prompt = 'No value: [' . $key . '] won\'t displayed! Be aware: this log won\'t displayed never again.';
          t3lib_div :: devlog( '[WARN/FILTER] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Maybe TS configuration for [' . $key . '] is: display it only with a hit at least.';
          t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'There is a workaround: please take a look in the manual for ' . $this->pObj->prefixId . '.treeview.';
          t3lib_div :: devlog( '[HELP/FILTER] ' . $prompt, $this->pObj->extKey, 1 );
          $firstCallDrsTreeview = false;
        }
          // DRS
        continue;
      }
        // CONTINUE: item is empty

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

//        // String result for printing
//      $str_result =  $str_result . $startTag . $curr_uid . ': ' . $item;

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
//    $str_result =             $str_result . $endTag . PHP_EOL .
//                              $this->htmlSpaceLeft . '</div>';
    $arr_result[$curr_uid] =  $arr_result[$curr_uid] . $endTag  . PHP_EOL .
                              $this->htmlSpaceLeft . '</div>';
      // Render the end tag of the last item

    $arr_result[$first_item_uid] = $this->htmlSpaceLeft . '<div id="' . $html_id . '">' . $arr_result[$first_item_uid];

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
    $this->itemsPerHtmlRow['currItemNumber']      = 0;

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
    $this->itemsPerHtmlRow['currItemNumber']++;
  }










/**
 * get_maxItemsTagEndBegin( ):  Get the tag for end the current row  and begin
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

    $maxItemsPerHtmlRow = $this->itemsPerHtmlRow['maxItemsPerHtmlRow'];
    $currItemNumber     = $this->itemsPerHtmlRow['currItemNumber'];
    if ( $currItemNumber >= ( $maxItemsPerHtmlRow - 1 ) )
    {
      $item         = $item . $this->itemsPerHtmlRow['rowEnd'] . PHP_EOL .
                      $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowBegin'];
      $this->itemsPerHtmlRow['currRowNumber']++;
      $str_evenOdd  = $this->itemsPerHtmlRow['currRowNumber'] % 2 ? 'odd' : 'even';
      $item         = str_replace( '###EVEN_ODD###', $str_evenOdd, $item );
    }
    $this->itemsPerHtmlRow['currItemNumber']++;
    return $item;
  }










/**
 * get_maxItemsWrapBeginEnd( ): Wrap all items with the begin tag and the end tag.
 *
 * @param	string		$items : current items
 * @return	string		$items : current items wrapped
 * @version 3.9.9
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
    $items  = $this->itemsPerHtmlRow['rowBegin'] . PHP_EOL .
                  $items .
                  $this->itemsPerHtmlRow['rowEnd'] . PHP_EOL;
      // Wrap $items

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

    return $value;
  }









/**
 * sum_hits( ): Count the hits of the current tableField.
 *              Store it in the class var $hits_sum[tableField]
 *
 * @param	string		$rows   : current rows
 * @return	void
 * @version 3.9.9
 * @since   3.0.0
 */
  private function sum_hits( $rows )
  {
      // Get the label for the hit field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // Init sum hits
    $sum_hits = 0;

      // LOOP all rows
    foreach( ( array ) $rows as $row )
    {
        // Add hits
      $sum_hits = $sum_hits + $row[ $hitsField ];
    }
      // LOOP all rows

      // Set class var $this->hits_sum
    $this->hits_sum[$this->curr_tableField] = $sum_hits;

    return;
  }









 /***********************************************
  *
  * Requirements
  *
  **********************************************/



/**
 * requiredMarker( )  : Check, whether a marker is configured in the HTML template
 *
 * @param     string    $tableField : label for the marker
 * @return    boolean   true: marker is configured; false: marker isn't configured
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
    
      // RETURN true : HTML marker is a part of the current HTML subpart
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
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_currFilterIsArea( )
  {
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
 * @version 3.9.9
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
          $firstItem[$uid][$hitsField] = $this->hits_sum[$this->curr_tableField];
          break;
        default:
          $firstItem[$uid][$field] = null;
          break;
      }
        // SWITCH field
    }
      // LOOP all fields of current filter / tableField

      // Add first item to the rows of the current filter
    $this->rows = $firstItem + $this->rows;

    return;
  }









/**
 * set_firstItemTreeView( ):  Adds the first item to the rows of the current filter.
 *                            Class var $rows.
 *                            If firstItem shouldn't displayed, nothing will happen.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_firstItemTreeView( )
  {
      // Get table and field
    list( $table ) = explode( '.', $this->curr_tableField );

      // RETURN current filter isn't a tree view
    if( ! in_array( $table, $this->pObj->oblFltr3x->arr_tablesWiTreeparentfield ) )
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
 * updateWizard( ): 
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








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']);
}
?>
