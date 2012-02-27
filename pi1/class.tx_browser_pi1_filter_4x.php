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
 * The class tx_browser_pi1_filter_4x bundles methods for rendering and processing filters and category menues.
 * 4x means: with SQL engine 4.x
 *
 * @author       Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package      TYPO3
 * @subpackage   browser
 *
 * @version      3.9.9
 * @since        3.9.9
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   95: class tx_browser_pi1_filter_4x
 *  141:     function __construct($pObj)
 *
 *              SECTION: Main
 *  175:     public function get_htmlFilters( )
 *
 *              SECTION: Init
 *  261:     private function init( )
 *
 *              SECTION: Marker
 *  321:     private function get_marker( )
 *
 *              SECTION: Rows
 *  399:     private function get_rows( )
 *
 *              SECTION: SQL ressources
 *  501:     private function sql_resAllItems( )
 *  578:     private function sql_resWiHits( )
 *
 *              SECTION: SQL ressources to rows
 *  666:     private function sql_resToRows( $res )
 *  706:     private function sql_resToRows_allItemsWiHits( $res, $rows_wiHits )
 *
 *              SECTION: SQL select
 *  780:     private function sql_select( $bool_count )
 *  857:     private function sql_select_addLL( )
 *  892:     private function sql_select_addLL_sysLanguage( )
 *  967:     private function sql_select_addLL_lang_ol(  )
 * 1033:     private function sql_select_addTreeview( )
 *
 *              SECTION: SQL from, groupBy, orderBy, limit
 * 1136:     private function sql_from( )
 * 1174:     private function sql_groupBy( )
 * 1199:     private function sql_orderBy( )
 * 1255:     private function sql_limit( )
 *
 *              SECTION: SQL where
 * 1294:     private function sql_whereAllItems( )
 * 1336:     private function sql_whereWiHits( )
 * 1369:     private function sql_andWhere_enableFields( )
 * 1396:     private function sql_andWhere_fromTS( )
 * 1428:     private function sql_andWhere_pidList( )
 * 1481:     private function sql_andWhere_sysLanguage( )
 *
 *              SECTION: TypoScript
 * 1554:     private function ts_condition( )
 * 1620:     private function ts_displayWithoutAnyHit( )
 *
 * TOTAL FUNCTIONS: 26
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
    // [Integer] number of the localisation mode
  var $int_localisation_mode  = null;
    // [String] Current table
  var $curr_tableField        = null;
    // [Array] tables with the fields, which are used in the SQL query
  var $sql_filterFields       = null;

    // [Array] Rows of the current filter
  var $rows = null;
    // [Array] Sum of hits per tableField. tableField is the element
  var $hits_sum = array( );

    // [Array] nice piVar array for the current filter / tableField
  var $nicePiVar = null;

    // [String] Space of the left HTML margin
  var $htmlSpaceLeft = null;
    // [Array] Array with elements maxItemsPerHtmlRow, rowBegin, rowEnd, noItemValue
  var $itemsPerHtmlRow = null;











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
 * get_htmlFilters( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  public function get_htmlFilters( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Init localisation
    $this->init( );

    $arr_return['data']['marker'] = array( );

      // LOOP each filter
    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( ( array ) $fields as $field => $confField )
      {
        if( rtrim($field, '.') != $field )
        {
          continue;
        }
        $this->curr_tableField = $tableWiDot . $field;

          // Get table
        list( $table ) = explode( '.', $this->curr_tableField );
          // Load TCA
        $this->pObj->objZz->loadTCA( $table );

        $arr_result = $this->get_marker( );
        if( $arr_result['error']['status'] )
        {
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
          return $arr_result;
        }
        $arr_return['data']['marker'] = $arr_return['data']['marker'] + $arr_result['data']['marker'];
        unset( $arr_result );
      }
    }
      // LOOP each filter

$this->pObj->dev_var_dump( __METHOD__, __LINE__, $arr_return['data']['marker'] );

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Area?';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Check the effect of TypoScript sql.andWhere!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->sql_filterFields );

    $str_header  = '<h1 style="color:red;">' . __METHOD__ . '</h1>';
    $str_prompt  = '<p style="color:red;font-weight:bold;">Development ' . $this->curr_tableField . '</p>';
    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = $str_header;
    $arr_return['error']['prompt'] = $str_prompt;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









 /***********************************************
  *
  * Init
  *
  **********************************************/









/**
 * init( ):  Inits the localisation mode. Set the class var $this->int_localisation_mode.
 *
 * @return	void
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init( )
  {

    if( ! isset( $this->int_localisation_mode ) )
    {
      $this->int_localisation_mode = $this->pObj->objLocalise->localisationConfig( );
    }

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
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // Do we need translated/localised records?
  }









 /***********************************************
  *
  * Marker
  *
  **********************************************/









/**
 * get_marker( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_marker( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );
      // Set marker label
    $markerLabel = '###' . strtoupper( $this->curr_tableField ) . '###';

      // RETURN condition isn't met
    if( ! $this->ts_condition( ) )
    {
      $arr_return['data']['marker'][$markerLabel] = null;
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_return;
    }
      // RETURN condition isn't met

      // Get filter rows
    $arr_return = $this->get_rows( );
    if( $arr_return['error']['status'] )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
    unset( $arr_return );
      // Get filter rows

      // Render the filter rows
    $this->rows = $rows;
    $arr_return = $this->get_html( );
    $items = $arr_return['data']['items'];
    unset( $arr_return );
    $arr_return['data']['marker'][$markerLabel] = $items;
      // Render the filter rows

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









 /***********************************************
  *
  * HTML
  *
  **********************************************/









/**
 * get_html( ): Render the given rows. Returns a HTML filter.
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_html( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Default return value
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
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Set nice_piVar
    $this->set_nicePiVar( );
    
      // Set class var $htmlSpaceLeft
    $this->set_htmlSpaceLeft( );

      // Set class var $maxItemsPerHtmlRow
    $this->set_maxItemsPerHtmlRow( );

    // Process nice_html
    // Prepaire row and item counting
    // Area
    // Wrap values
      // Wrap the item

    $arr_return = $this->get_htmlItems( );
    //$content    = $arr_return['data']['items'];


      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * get_htmlItems( ): Render the given rows. Returns a HTML filter.
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlItems( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    // :TODO: Area?

      // SWITCH current filter is a tree view
    switch( in_array( $table, $this->pObj->objFilter->arr_tablesWiTreeparentfield ) )
    {
      case( true ):
        $arr_return = $this->get_htmlItemsTree( );
        //$items      = $arr_return['data']['items'];
        break;
      case( false ):
      default:
        $arr_return = $this->get_htmlItemsList( );
        $items      = $arr_return['data']['items'];
        $arr_return = $this->get_htmlItemsWrapped( $items );
        // :TODO:
        //$this->wrap_objectTitle( );
        break;
    }
      // SWITCH current filter is a tree view

      // RETURN html items
    //$arr_return['data']['items'] = $items;
    return $arr_return;
  }









/**
 * get_htmlItemsList( ): Render the given rows. Returns a HTML filter.
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlItemsList( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Default return value
    $item                       = null;
    $arr_return['data']['item'] = $item;

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS configuration of the current filter / tableField
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

    // :TODO: Area?

      // Add the first item to the rows
    $this->set_firstItem( );

      // LOOP rows
    foreach( ( array ) $this->rows as $uid => $row )
    {
      $key    = $this->sql_filterFields[$this->curr_tableField]['value'];
      $value  = $row[$key];

      $item   = $this->get_htmlItem( $conf_array, $uid, $value );
      $items  = $items . $this->htmlSpaceLeft . ' ' . $item . PHP_EOL ;
    }
      // LOOP rows

    $items = $this->maxItemsPerHtmlRowWrap( $items );

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

    $arr_return['data']['items'] = $items;
    return $arr_return;
  }









/**
 * get_htmlItem( ): Render the given rows. Returns a HTML filter.
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlItem( $conf_array, $uid, $value )
  {
    static $firstLoop = true;

      // stdWrap the current value
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
    $item = $this->pObj->local_cObj->stdWrap( $value, $stdWrap );
      // stdWrap the current value

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
    $item = $this->maxitemsPerHtmlRowBegin( $item );

      // Item class
    if($conf_name == 'CATEGORY_MENU')
    {
      $conf_array = $this->pObj->objJss->class_onchange($conf_name, $conf_array, $row_number);
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
    $item = $this->replace_itemTitle( $conf_array, $item );
      // Item uid
    $item = $this->replace_itemUid( $conf_array, $uid, $item );
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

    $this->maxItemsPerHtmlRowIncreaseItemNumber( );

    $firstLoop = false;
    return $item;
  }









 /***********************************************
  *
  * HTML - replace marker
  *
  **********************************************/









/**
 * replace_itemClass( ): Replaces the marker ###CLASS### with the value from TS
 *
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
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
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	integer		$uid        : The uid of the current item
 * @param	string		$value      : The value of the current item
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
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
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
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
 * replace_itemTitle( ): Replaces the marker ###TITLE### with the value from TS
 *
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemTitle( $conf_array, $item )
  {
    static $firstLoop = true;

      // Get TS value
    $title = null;
//    if( empty( $conf_array['wrap.']['item.']['style'] ) )
//    {
//      $style = null;
//    }
//    else
//    {
//      $style = ' style="' . $conf_array['wrap.']['item.']['style'] . '"';
//    }
      // Get TS value

      // Replace the marker
    $item = str_replace( '###TITLE###', $title, $item );

    if( $firstLoop )
    {
      if( $this->pObj->b_drs_devTodo )
      {
        $prompt = '###TITLE### is removed. Check the code!';
        t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
      }
    }
    $firstLoop = false;

      // RETURN content
    return $item;
  }









/**
 * replace_itemUid( ): Replaces the marker ###UID### with the given uid
 *
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	string		$uid        : The uid of the current item
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
 * @version 3.9.9
 * @since   3.0.0
 */
  private function replace_itemUid( $conf_array, $uid, $item )
  {
      // Replace the marker
    $item = str_replace( '###UID###', $uid, $item );

      // RETURN content
    return $item;
  }









/**
 * replace_itemUrl( ): Replaces the marker ###URL###
 *
 * @param	array     $conf_array : The TS configuration of the current filter
 * @param	string		$uid        : The uid of the current item
 * @param	string		$item   : The current item
 * @return	string	$item   :	Returns the wrapped item
 *
 * @version 3.9.9
 * @since   3.6.1
 */
  private function replace_itemUrl( $conf_array, $uid, $item )
  {
      // Short vars
    $conf_view      = $this->conf_view;
    $arr_currPiVars = $this->pObj->piVars;
    $tableField     = $this->curr_tableField;

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
    $uid = $this->pObj->objCal->area_get_urlPeriod( $conf_array, $tableField, $uid );



      /////////////////////////////////////////////////////////
      //
      // Remove piVars temporarily
    
      // Remove sort and pointer
    $arr_removePiVars = array( 'sort', 'pointer' );

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
                                                $conf_view['filter.']
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
                          $this->pObj->prefixId . '[' . $tableField . ']=' . $uid;
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









 /***********************************************
  *
  * Rows
  *
  **********************************************/









/**
 * get_rows( ):  Get the rows of the current filter
 *
 * @return	array		$arr_return : Array with the rows or an error message
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rows( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Order rows, if it is a tree view.';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:
//    if ( ! in_array( $table, $this->pObj->objFilter->arr_tablesWiTreeparentfield ) )
//    {
//      return $display_without_any_hit;
//    }


    return $arr_return;
  }









/**
 * get_rowsWiHits( ): Get the rows with the items with a hit at least of the current filter
 *
 * @return	array		$arr_return : Array with the rows or an error message
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rowsWiHits( )
  {
      // Get SQL ressource for filter items with one hit at least
    $arr_return = $this->sql_resWiHits( );
    if( $arr_return['error']['status'] )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
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
 *                            If param $rows_wiHits contains rows, the counted
 *                            hits will taken over in rows with all items.
 *
 * @param   array   $rows_wiHits  : Rows with items of the current filter, which have one hit at least
 * @return	array		$arr_return   : Array with the rows or an error message
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_rowsAllItems( $rows_wiHits )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // RETURN display items only, if they have one hit at least
    if( ! $this->ts_displayWithoutAnyHit( ) )
    {
      $arr_return['data']['rows'] = $rows_wiHits;
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
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
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
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
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Don't count hits
    $bool_count = false;

      // Query for all filter items
    $select   = $this->sql_select( $bool_count );
    $from     = $table;
    $where    = $this->sql_whereAllItems( );
    $groupBy  = $this->curr_tableField;
    $orderBy  = $this->sql_orderBy( );
    $limit    = $this->sql_limit( );

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
      // Get query
      // Execute query
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select,
                                      $from,
                                      $where,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute query

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

    $arr_return['data']['res'] = $res;
    return $arr_return;
  }








/**
 * sql_resWiHits( ):  Get the SQL ressource for a filter with items with hits only.
 *                        Hits will counted.
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
      // Get query
      // Execute query
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select,
                                      $from,
                                      $where,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute query

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

    $arr_return['data']['res'] = $res;
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
 * @param	ressource		$res  : current SQL ressource
 * @return	array		$rows : rows
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_resToRows( $res )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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
 * @param	ressource		$res              : current SQL ressource
 * @param	array       $rows_wiHits      : rows with hits
 * @return	array     $rows_wiAllItems  : rows with all filter items
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

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get label of the hits field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // LOOP all items
    foreach( ( array ) $rows_wiAllItems as $uid => $row )
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
  * SQL select
  *
  **********************************************/









/**
 * sql_select( ): Get the SELECT statement for the current filter (the current tableField).
 *                Statement will contain fields for localisation and treeview, if there is
 *                any need.
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
        $count = "count(*)";
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
    $addSelect = $addSelect . $this->sql_select_addLL_lang_ol( );
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
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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
 * sql_select_addLL_lang_ol( ): Returns an addSelect with the localisation fields,
 *                              if there are localisation needs.
 *                              Method handles the foreign table (language overlay) only.
 *
 * @return	string		$addSelect  : the addSelect with the localisation fields
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_addLL_lang_ol(  )
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
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
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
    $this->pObj->objFilter->arr_tablesWiTreeparentfield[] = $table;

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
  * SQL from, groupBy, orderBy, limit
  *
  **********************************************/









/**
 * sql_from( ): Get the FROM statement ...
 *
 * @return	string		$from : FROM statement
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_from( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    switch( true )
    {
      case( $this->pObj->localTable != $table ):
          // foreign table
        $from = $this->pObj->objSql->sql_query_statements['rows']['from'];
        break;
      case( $this->pObj->localTable == $table ):
      default:
          // local table
        $from = $table;
        break;
    }
      // Get FROM statement

      // RETURN FROM statement
    return $from;
  }









/**
 * sql_groupBy( ): Get the GROUP BY statement ...
 *
 * @return	string		$from : GROUP BY statement
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
 * sql_limit( ): Get the LIMIT statement ...
 *
 * @return	string		$limit : LIMIT statement
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
  * SQL where
  *
  **********************************************/









/**
 * sql_whereAllItems( ):  Get the WHERE statement for all items.
 *                        All items means: idenependent of any hit.
 *
 * @return	string		$where : WHERE statement without WHERE
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereAllItems( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    //$this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->pObj->arr_realTables_arrFields );

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Add andWhere from TS.';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

    $where  = '1 ' .
              $this->sql_andWhere_pidList( ) .
              $this->sql_andWhere_enableFields( ) .
              $this->sql_andWhere_fromTS( ) .
              $this->sql_andWhere_sysLanguage( );
      // Get WHERE statement

      // RETURN WHERE statement without a WHERE
    return $where;
  }









/**
 * sql_whereWiHits( ):  Get the WHERE statement for a filter, which should diplay
 *                          flter items with a hit only.
 *
 * @return	string		$where : WHERE statement without WHERE
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_whereWiHits( )
  {
      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Add andWhere from TS.';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

      // Get WHERE statement
    $where = $this->pObj->objSql->sql_query_statements['rows']['where'] .
             $this->sql_andWhere_fromTS( );

      // RETURN WHERE statement without a WHERE
    return $where;
  }









/**
 * sql_andWhere_enableFields( ): Get the AND WHERE statement with the enabled fields.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_andWhere_enableFields( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

    $andWhere = $this->pObj->cObj->enableFields( $table );

      // RETURN AND WHERE statement
    return $andWhere;
  }









/**
 * sql_andWhere_fromTS( ):  Get the AND WHERE statement from the TS configuration.
 *                          See sql.andWhere.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_andWhere_fromTS( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS value
    $andWhere = $this->conf_view['filter.'][$table . '.'][$field . '.']['sql.andWhere.']['andWhere'];

    if( ! empty ( $andWhere ) )
    {
      $andWhere = " AND " . $andWhere;
    }

      // RETURN AND WHERE statement
    return $andWhere;
  }









/**
 * sql_andWhere_pidList( ): Get the AND WHERE statement with the pid list.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_andWhere_pidList( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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
 * sql_andWhere_sysLanguage( ): Get the AND WHERE statement with gthe sys_language_uid.
 *                              It is an AND WHERE for tables which have a record for each
 *                              language.
 *
 * @return	string		$andWhere : AND WHERE statement with an AND
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_andWhere_sysLanguage( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

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
  * TypoScript
  *
  **********************************************/









  /**
 * ts_condition( ):  Render the filter condition
 *
 * @return	boolean		True, if filter should displayed, false if filter shouldn't diplayed
 * @version 3.9.3
 * @since   3.9.3
 */
  private function ts_condition( )
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
 * ts_displayHits( ):  Get the TS configuration for displaying items without hits.
 *                              If current filter is a tree view, return value is true.
 *
 * @return	string		$display_without_any_hit : value from TS configuration
 * @version 3.9.9
 * @since   3.9.9
 */
  private function ts_displayHits( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Short var
    $currFilterWrap = $this->conf_view['filter.'][$table . '.'][$field . '.']['wrap.'];

      // RETURN TS value
    return $currFilterWrap['item.']['display_hits'];
  }









/**
 * ts_displayWithoutAnyHit( ):  Get the TS configuration for displaying items without hits.
 *                              If current filter is a tree view, return value is true.
 *
 * @return	string		$display_without_any_hit : value from TS configuration
 * @version 3.9.9
 * @since   3.9.9
 */
  private function ts_displayWithoutAnyHit( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Short var
    $currFilterWrap = $this->conf_view['filter.'][$table . '.'][$field . '.']['wrap.'];

      // Get TS value
    $display_without_any_hit = $currFilterWrap['item.']['display_without_any_hit'];

      // RETURN ts value directly: filter isn't a tree view filter
    if ( ! in_array( $table, $this->pObj->objFilter->arr_tablesWiTreeparentfield ) )
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
  * Tree view
  *
  **********************************************/









/**
 * get_htmlItemsTree( ): Get the elements ordered to the needs of a tree.
 *
 * @return	array		$arr_tableFields  : Array with the values. Values are wrapped with ul- and li-tags.
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlItemsTree( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Parent uid of the root records: 0 of course
    $uid_parent = 0;
      // Current level of the treeview: 0 of course
    $level      = 0;
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
    $arr_tableFields = $this->tree_getRendered( );
    unset( $this->tmpOneDim );


      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

      // RETURN the ordered and rendered rows of the current tablefield
    $arr_return['data']['items'] = implode( null, $arr_tableFields );
    return $arr_return;
  }









/**
 * get_htmlItemsWrapped( ) Wrap all items (wrap the object)
 *
 * @param	string		$conf_name: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param	array		$conf_array: The current TS configuration of the obkject
 * @param	string		$str_nice_piVar: The nice name for the current piVar
 * @param	string		$key_piVar: The real name of the piVar
 * @param	integer		$number_of_items: The number of items
 * @return	string		Returns the wrapped items/object
 * @version 3.9.6
 * @since    3.0.1
 */
  private function get_htmlItemsWrapped( $items )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );
    
      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Check multiple!';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:

      // #8337, 101011, dwildt
      // SWITCH COA
    switch( $conf_name )
    {
      case ( 'CHECKBOX' ) :
        $size     = null;
        $multiple = ' ' . $conf_array['multiple.']['selected'];
        break;
      case ( 'CATEGORY_MENU' ) :
      case ( 'RADIOBUTTONS' ) :
        $size      = null;
        $multiple  = null;
        break;
      case ( 'SELECTBOX' ) :
        $size = $conf_array['size'];
        #3.4.904
        if( $size < 2 )
        {
          $multiple = null;
        }
        if( $size >= 2 )
        {
          if( $conf_array['multiple'] == 1 )
          {
            $multiple = ' ' . $conf_array['multiple.']['selected'];
          }
        }
        break;
      default :
        $size      = null;
        $multiple  = null;
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'multiple - undefined value in switch: \'' . $conf_name . '\'';
          t3lib_div :: devlog( '[ERROR/FILTER] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'multiple becomes false.';
          t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 3);
        }
    }
      // SWITCH COA

    $itemsWrap = $conf_array['wrap.']['object'];
      // Remove empty class
    $itemsWrap = str_replace( ' class=""', null, $itemsWrap );

    $int_space_left = $conf_array['wrap.']['object.']['nice_html_spaceLeft'];
    $str_space_left = str_repeat( ' ', $int_space_left );
    $itemsWrap      = $str_space_left . $itemsWrap;

    $key_piVar      = $this->nicePiVar['key_piVar'];
    $arr_piVar      = $this->nicePiVar['arr_piVar'];
    $str_nicePiVar  = $this->nicePiVar['nice_piVar'];

    $str_uid        = $this->pObj->prefixId . '_' . $str_nicePiVar;
    $str_uid        = str_replace('.', '_', $str_uid);
    
    $itemsWrap    = str_replace('###TABLE.FIELD###',  $key_piVar, $itemsWrap );
    $itemsWrap    = str_replace('###ID###',           $str_uid,   $itemsWrap );
    $itemsWrap    = str_replace('###SIZE###',         $size,      $itemsWrap );
    $itemsWrap    = str_replace('###MULTIPLE###',     $multiple,  $itemsWrap );

      // DRS - Development Reporting System
    if( empty( $itemsWrap ) )
    {
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'wrap_allItems returns an empty value for ' . $conf_name;
        t3lib_div :: devlog('[WARN/TEMPLATING] ' . $prompt, $this->pObj->extKey, 2);
      }
    }
      // DRS - Development Reporting System

    $items = PHP_EOL . $items . PHP_EOL . $this->htmlSpaceLeft;
    $items = str_replace('|', $items, $itemsWrap);

    $arr_return['data']['items'] = $items;
    return $arr_return;
  }






  /**
 * set_treeOneDim:  Recursive method. It generates a one dimensional array.
 *                  Each array has upto three elements:
 *                  * [obligate] uid   : uid of the record
 *                  * [obligate] value : value of the record
 *                  * [optional] array : if the record has children ...
 *                                It is 0 while starting.
 *
 * @param	integer		$uid_parent : Parent uid of the current record - for recursive calls.
 * @return	void		Result will be allocated to the global $tmpOneDim
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
 * get_treeRendered:  Method converts a one dimensional array to a multidimensional array.
 *                    It wraps every element of the array with ul and or li tags.
 *                    Wrapping depends in position and level of the element in the tree.
 *
 * @return	array		$arr_result : Array with the rendered elements
 * @internal        #32223, 120119, dwildt+
 * @version 3.9.9
 * @since   3.9.9
 */
  private function tree_getRendered( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];


      // Render uid and value of the first item
    $first_item_uid   = $conf_array['first_item.']['option_value'];
    $tsValue          = $conf_array['first_item.']['value_stdWrap.']['value'];
    $tsConf           = $conf_array['first_item.']['value_stdWrap.'];
    $first_item_value = $this->pObj->local_cObj->stdWrap( $tsValue, $tsConf );
      // Render uid and value of the first item

      // Add first item
      // SWITCH display first item
    switch( $conf_array['first_item'] )
    {
      case( true ):
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

      // Move one dimensional array to an iterator
    $tmpArray     = $this->pObj->objTyposcript->oneDim_to_tree( $tmpOneDim );
    $rcrsArrIter  = new RecursiveArrayIterator( $tmpArray );
    $iterator     = new RecursiveIteratorIterator( $rcrsArrIter );
      // Move one dimensional array to an iterator

      // Code for an item (an a-tag usually)
//    $conf_item    = $conf_array['wrap.']['item'];

      // HTML id
    $cObj_name  = $conf_array['treeview.']['html_id'];
    $cObj_conf  = $conf_array['treeview.']['html_id.'];
    $html_id    = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );



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
    foreach( $iterator as $key => $value )
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
//      $value      = '###HITS_BEFORE###' . $value . '###HITS_BEHIND###';
//      $value      = str_replace('###VALUE###', $value, $conf_item );
      $item = $this->get_htmlItem( $conf_array, $curr_uid, $value );
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
                          '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          $last_depth   = $curr_depth;
          break;
            // Stop of sublevel
        default:
          $startTag = '</li>' . PHP_EOL .
                      $this->htmlSpaceLeft . $indent . '<li id="' . $html_id . '_li_' . $curr_uid . '">';
          break;
      }
        // Render the start tag

        // String result for printing
      $str_result =  $str_result . $startTag . $curr_uid . ': ' . $item;

        // Result array
      $arr_result[$curr_uid] = $startTag . $item;
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
    $str_result =             $str_result . $endTag . PHP_EOL .
                              $this->htmlSpaceLeft . '</div>';
    $arr_result[$curr_uid] =  $arr_result[$curr_uid] . $endTag  . PHP_EOL .
                              $this->htmlSpaceLeft . '</div>';
      // Render the end tag of the last item

    $arr_result[$first_item_uid] = $this->htmlSpaceLeft . '<div id="' . $html_id . '">' . $arr_result[$first_item_uid];

      // RETURN the result
    return $arr_result;
  }









 /***********************************************
  *
  * HTML - max items per row
  *
  **********************************************/









/**
 * set_maxItemsPerHtmlRow( ): Set class var $itemsPerHtmlRow.
 *
 * @return	void
 *
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
 * maxitemsPerHtmlRowBegin( ): ...
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function maxitemsPerHtmlRowBegin( $item )
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
      $item       = $item . $this->itemsPerHtmlRow['rowEnd'] . PHP_EOL .
                        $this->htmlSpaceLeft . $this->itemsPerHtmlRow['rowBegin'];
      $this->itemsPerHtmlRow['currRowNumber']++;
      $str_evenOdd    = $this->itemsPerHtmlRow['currRowNumber'] % 2 ? 'odd' : 'even';
      $item       = str_replace( '###EVEN_ODD###', $str_evenOdd, $item );
    }
    $this->itemsPerHtmlRow['currItemNumber']++;
    return $item;
  }










/**
 * maxItemsPerHtmlRowIncreaseItemNumber( ): ...
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function maxItemsPerHtmlRowIncreaseItemNumber( )
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
 * maxItemsPerHtmlRowWrap( ): ...
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function maxItemsPerHtmlRowWrap( $items )
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
  * Helper
  *
  **********************************************/









/**
 * set_firstItem( ):  Adds the first item to the rows of the current filter.
 *                    If firstItem shouldn't displayed, nothing will happen.
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function set_firstItem( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // RETURN first item shouldn't displayed
    if( ! $conf_array['first_item'] )
    {
      return $value;
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
 * set_htmlSpaceLeft( ): Set the left margin for HTML code. Class var $htmlSpaceLeft.
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.0.0
 */
  private function set_htmlSpaceLeft( )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Get TS value
    $int_space_left = $conf_array['wrap.']['item.']['nice_html_spaceLeft'];

      // Set class var $htmlSpaceLeft
    $this->htmlSpaceLeft = str_repeat(' ', $int_space_left);

    return;
  }









/**
 * set_hits( ): Prepend or append the hits to the curren item.
 *              Hits will handled by stdWrap.
 *              If hits shouldn't displayed, method returns the given value.
 *
 * @param integer   $uid    : uid of the current filter item
 * @param string    $value  : value of the current filter item
 * @param array     $row    : current row
 * @return	string  $value  : Value with hits or without hits
 *
 * @version 3.9.9
 * @since   3.0.0
 */
  private function set_hits( $uid, $value, $row )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get TS filter configuration
    $conf_name  = $this->conf_view['filter.'][$table . '.'][$field];
    $conf_array = $this->conf_view['filter.'][$table . '.'][$field . '.'];

      // Set display hits flag
      // SWITCH first item
    switch( true )
    {
      case( $uid == $conf_array['first_item.']['option_value'] ):
        $bool_displayHits   = $conf_array['first_item.']['display_hits'];
        $bool_displayWoHit  = $conf_array['first_item.']['display_without_any_hit'];
        break;
      default:
        $bool_displayHits   = $conf_array['wrap.']['item.']['display_hits'];
        $bool_displayWoHit  = $conf_array['wrap.']['item.']['display_without_any_hit'];
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


    if( $hits < 1 )
    {
      if( ! $bool_displayWoHit )
      {
        return $value;
      }
    }

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
 *              Store it in the class var $this->hits_sum[tableField]
 *
 * @param string    $rows   : current rows
 *
 * @return	void
 *
 * @version 3.9.9
 * @since   3.0.0
 */
  private function sum_hits( $rows )
  {
      // Get table and field
    list( $table, $field ) = explode( '.', $this->curr_tableField );

      // Get the label for the hit field
    $hitsField = $this->sql_filterFields[$this->curr_tableField]['hits'];

      // Init sum hits
    $sum_hits = 0;

      // LOOP all rows
    foreach( ( array ) $rows as $uid => $row )
    {
        // Add hits
      $sum_hits = $sum_hits + $row[ $hitsField ];
    }
      // LOOP all rows

      // Set class var $this->hits_sum
    $this->hits_sum[$this->curr_tableField] = $sum_hits;

    return;
  }









/**
 * set_nicePiVar( ): Set class var nicePiVar. Result depends on HTML multiple property.
 *
 * @return	void
 *
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
    switch ($conf_name)
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
          t3lib_div :: devlog('[ERROR/FILTER] ' . $prompt, $this->pObj->extKey, 3);
          $prompt = 'multiple becomes false.';
          t3lib_div :: devlog('[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0);
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









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']);
}
?>