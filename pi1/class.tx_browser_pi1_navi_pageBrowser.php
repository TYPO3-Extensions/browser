<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_navi_pageBrowser bundles methods for the page browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     4.7.0
 * @since       3.9.12
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   63: class tx_browser_pi1_navi_pageBrowser
 *  105:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  137:     public function get( $content )
 *
 *              SECTION: Counting
 *  237:     private function count( )
 *  276:     private function count_fromIndexBrowser( )
 *  308:     private function count_resSql( )
 *
 *              SECTION: SQL statements
 *  401:     private function sqlStatement_from( $table )
 *  428:     private function sqlStatement_where( $table )
 *
 *              SECTION: TypoScript
 *  483:     private function tsResultsAtATime( )
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_pageBrowser
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

    // [Integer] sum of records
  var $sum;
    // [Boolean] sum of records is taken from index browser
  var $sumIsFromIndexBrowser;

    // [Object] interface of extension radialsearch
  private $objFilterRadialsearch  = null;
    // [Boolean] True, if a radialsearch filter is configured by TS, false if not.
  private $isRadialsearchFilterByTS   = null;

  
  
 /**
  * Constructor. The method initiate the parent object
  *
  * @param	object		The parent object
  * @return	void
  * @version  3.9.9
  * @since    3.9.9
  */
  public function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;
      // 111023, uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
    $this->t3lib_cs_obj = t3lib_div::makeInstance('t3lib_cs');
  }

  
  
    /***********************************************
    *
    * Main
    *
    **********************************************/

/**
 * get( ): Get the page browser for the subpart in the current content.
 *
 * @param	string		$content    : current content
 * @return	array		$arr_return : Contains null or the page browser
 * @version 4.7.0
 * @since   3.9.12
 */
  public function get( $content )
  {
    $arr_return = array( );
    
      // Set class var
    $this->content = $content;

      // RETURN : requierments aren't met
    if( ! $this->requirements( ) )
    {
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : requierments aren't met

      // #52486, 131006, 1+
    $this->init( );
    
      // Set class var sum
    $this->count( );

      // RETURN : there isn't any record.
    if( $this->sum < 1 )
    {
        // 131225, dwildt, 4+
      if( $this->pObj->b_drs_navi )
      {
        $prompt = 'No pageBrowser: there isn\'t any record.';
        t3lib_div::devlog( '[INFO/NAVI] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : there isn't any record.

      // Backup $GLOBALS['TSFE']->id
    $globalTsfeId = $GLOBALS['TSFE']->id;
      // Setup $GLOBALS['TSFE']->id temporarily
    if( ! empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Setup $GLOBALS['TSFE']->id temporarily

      // Set TypoScript property
    $this->tsResultsAtATime( );

      // Get TypoScript configuration
    $confPageBrowser = $this->conf['navigation.']['pageBrowser.'];

      // Init piBase for pagebrowser
    $this->pObj->internal['res_count']          = $this->sum;
    $this->pObj->internal['maxPages']           = $confPageBrowser['maxPages'];
    $this->pObj->internal['showRange']          = $confPageBrowser['showRange'];
    $this->pObj->internal['pagefloat']          = $confPageBrowser['pagefloat'];
    $this->pObj->internal['showFirstLast']      = $confPageBrowser['showFirstLast'];
    $this->pObj->internal['results_at_a_time']  = $confPageBrowser['results_at_a_time'];
    $this->pObj->internal['dontLinkActivePage'] = $confPageBrowser['dontLinkActivePage'];
      // Init piBase for pagebrowser

      // Get the wrapped pagebrowser
    $pageBrowser  = $this->pObj->pi_list_browseresults
                    (
                      $confPageBrowser['showResultCount'],
                      $confPageBrowser['tableParams'],
                      $confPageBrowser['wrap.'],
                      $confPageBrowser['pointer'],
                      $confPageBrowser['hscText']
                    );
      // Get the wrapped pagebrowser

      // Reset $GLOBALS['TSFE']->id
    $GLOBALS['TSFE']->id = $globalTsfeId; // #9458

      // RETURN the content
      // 131225, dwildt, 4+
    if( $this->pObj->b_drs_navi )
    {
      $prompt = 'pageBrowser is returned with content.';
      t3lib_div::devlog( '[INFO/NAVI] ' . $prompt, $this->pObj->extKey, 0 );
    }
    $arr_return['data']['content'] = $pageBrowser;
    return $arr_return;
  }

  
  
   /***********************************************
    *
    * Init
    *
    **********************************************/
  
/**
 * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
 *
 * @return    void
 * @access private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function init( )
  {
      // #52486, 131005, dwildt, 2+
      // Init radialsearch filter and object
    $this->init_filterRadialsearch( );

  }

/**
 * init_filterRadialsearch( ): 
 *
 * @return	void
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function init_filterRadialsearch( )
  {
    $path = t3lib_extMgm::extPath( 'browser' ) . 'pi1/';
    require_once( $path . 'class.tx_browser_pi1_filterRadialsearch.php' );

    $this->objFilterRadialsearch = t3lib_div::makeInstance( 'tx_browser_pi1_filterRadialsearch' );
    $this->objFilterRadialsearch->setParentObject( $this->pObj );
    $this->objFilterRadialsearch->setConfView( $this->conf_view );
  }

  
  
   /***********************************************
    *
    * Requirements
    *
    **********************************************/
  
 /**
  * requirements( ):
  *
  * @return	boolean   true, if requirements are met; false if not
  * @version 3.9.13
  * @since   3.9.13
  */
  private function requirements( )
  {
      // RETURN : pagebrowser shouldn't displayed
    if( ! $this->pObj->objFlexform->bool_pageBrowser )
    {
     return false;
    }
      // RETURN : pagebrowser shouldn't displayed

      // RETURN : firstVisit but emptyListAtStart
    if( $this->pObj->boolFirstVisit && $this->pObj->objFlexform->bool_emptyAtStart )
    {
     return false;
    }
      // RETURN : firstVisit but emptyListAtStart

   return true;
  }



    /***********************************************
    *
    * Counting
    *
    **********************************************/

/**
 * count( ):  Counts records. If index browser is enabled, sum will taken from it.
 *            Otherwise there will a database query.
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since   3.9.12
 */
  private function count( )
  {
      // RETURN : sum is taken from the index browser
    $this->count_fromIndexBrowser( );
    if( $this->sumIsFromIndexBrowser )
    {
      return;
    }
      // RETURN : sum is taken from the index browser

      // SQL result with sum of records, depending on search word and filter
    $arr_return = $this->count_resSql( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $res = $arr_return['data']['res'];
      // SQL result with sum of records, depending on search word and filter

      // Get the row
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // Free SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );
      // Set class var
    $this->sum = $row['count'];

    return false;
  }



/**
 * count_fromIndexBrowser( ):  Take the sum from the index browser, if it
 *                             is enabled, and set the class var sum.
 *
 * @return	void
 * @version 3.9.24
 * @since   3.9.12
 */
  private function count_fromIndexBrowser( )
  {
      // RETURN : index browser isn't enabled
    // 3.9.24, 120604, dwildt, 1-
    //if( ! isset ( $this->pObj->objNaviIndexBrowser->indexBrowserTab ) )
    // 3.9.24, 120604, dwildt, 1+
    if( empty ( $this->pObj->objNaviIndexBrowser->indexBrowserTab ) )
    {
      $this->sumIsFromIndexBrowser = false;
      return;
    }
      // RETURN : index browser isn't enabled

      // Get sum of current tab
    $arrTabs    = $this->pObj->objNaviIndexBrowser->indexBrowserTab;
    $tabId      = $arrTabs['tabSpecial']['selected'];
    $sumCurrTab = $arrTabs['tabIds'][$tabId]['sum'];
      // Get sum of current tab

      // Override sum of the page browser
    $this->sum  = $sumCurrTab;
    $this->sumIsFromIndexBrowser = true;
  }



/**
 * count_resSql( ): Builds the query for counting rows, executes it and returns
 *                  the SQL ressource.
 *                  Result depends on search word and filter.
 *
 * @return	array		$arr_return : SQL ressource or an error message in case of on arror
 * @version 4.1.2
 * @since   3.9.12
 */
  private function count_resSql( )
  {
      // #38611, 120703, dwildt+
      // SWITCH $int_localisation_mode
    $curr_int_localisation_mode = null;
    switch( $this->pObj->objLocalise->get_localisationMode( ) )
    {
      case( PI1_DEFAULT_LANGUAGE ):
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
          // Follow the workflow
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        if( $this->pObj->b_drs_localise || $this->pObj->b_drs_navi )
        {
          $prompt = 'Localisation mode is PI1_SELECTED_OR_DEFAULT_LANGUAGE and will set to PI1_DEFAULT_LANGUAGE temporarily.';
          t3lib_div::devlog( '[INFO/LOCALISATION+NAVI] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // Store current localisation mode
        $curr_int_localisation_mode = $this->pObj->objLocalise->get_localisationMode( );
          // Set all to default language
        //$this->pObj->objLocalise->int_localisation_mode = PI1_DEFAULT_LANGUAGE;
        $this->pObj->objLocalise->setLocalisationMode( PI1_DEFAULT_LANGUAGE );
        break;
      default:
          // DIE
        $this->pObj->objLocalise->zz_promptLLdie( __METHOD__, __LINE__ );
        break;
    }
      // SWITCH $int_localisation_mode
      // #38611, 120703, dwildt+
    
      // Get current table.field of the index browser
    $tableField     = $this->pObj->arrLocalTable['uid'];
    list( $table )  = explode( '.', $tableField );

      // #52486, 131006, -
//      // Query for all filter items
//    $select   = "COUNT( DISTINCT " . $tableField . " ) AS 'count'";
//    $from     = $this->sqlStatement_from( $table );
//    $where    = $this->sqlStatement_where( $table );
//    $groupBy  = null;
//    $orderBy  = null;
//    $limit    = null;
//
//      // Execute the query
//    $arr_return = $this->pObj->objSqlFun->exec_SELECTquery
//                  (
//                    $select,
//                    $from,
//                    $where,
//                    $groupBy,
//                    $orderBy,
//                    $limit
//                  );
      // #52486, 131006, -
//$this->pObj->dev_var_dump( str_replace( '\'', '"', $arr_return['data']['query'] ) );

      // #52486, 131006, +
      // Query for all filter items
    $select   = "COUNT( DISTINCT " . $tableField . " ) AS 'count'";
    $from     = $this->sqlStatement_from( $table )
              . $this->sql_radialsearchFrom( )
              ;
    $where    = $this->sqlStatement_where( $table )
              . $this->sql_radialsearchWhere( true )
              ;
    $groupBy  = null;
    $orderBy  = null;
    $limit    = null;
      // #52486, 131006, +


      // #52486, 131006, +
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

//$this->pObj->dev_var_dump( str_replace( '\'', '"', $query ) );
      // Execute query
    $promptOptimise   = 'Maintain the performance? Disbale the page browser, if it isn\'t needed.';
    $debugTrailLevel  = 1;
    $arr_return = $this->pObj->objSqlFun->sql_query( $query, $promptOptimise, $debugTrailLevel );
//$this->pObj->dev_var_dump( str_replace( '\'', '"', $arr_return['data']['query'] ) );
      // Execute query


      // #38611, 120703, dwildt+
      // SWITCH $int_localisation_mode
    if( $curr_int_localisation_mode != null )
    {
      if( $this->pObj->b_drs_localise || $this->pObj->b_drs_navi )
      {
        $prompt = 'Localisation mode is reseted';
        t3lib_div::devlog( '[INFO/LOCALISATION+NAVI] ' . $prompt, $this->pObj->extKey, 0 );
      }
      //$this->pObj->objLocalise->int_localisation_mode = $curr_int_localisation_mode;
      $this->pObj->objLocalise->setLocalisationMode( $curr_int_localisation_mode );
    }
      // SWITCH $int_localisation_mode
      // #38611, 120703, dwildt+
    
    return $arr_return;
  }



    /***********************************************
    *
    * SQL statements
    *
    **********************************************/

/**
 * sqlStatement_from( ): SQL statement FROM without a FROM
 *
 * @param	string		$table  : The current from table
 * @return	string		$from   : FROM statement without a from
 * @version 3.9.12
 * @since   3.9.12
 */
  private function sqlStatement_from( $table )
  {
    switch( true )
    {
        // 3.9.25, 120506, dwildt+
      case( ! empty ( $this->pObj->conf_sql['andWhere'] ) ):
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->pObj->objFltr4x->get_selectedFilters( ) ):
        $from = $this->pObj->objSqlInit->statements['listView']['from'];
        break;
      default:
        $from = $table;
        break;
    }

    return $from;
  }

/**
 * sqlStatement_where( ): SQL statement WHERE without a WHERE
 *
 * @param	string		$table              : The current from table
 * @param	string		$andWhereFindInSet  : FIND IN SET
 * @return	string		$where            : WHERE statement without a WHERE
 * @version 4.1.2
 * @since   3.9.12
 */
  private function sqlStatement_where( $table )
  {
    switch( true )
    {
        // 3.9.25, 120506, dwildt+
      case( ! empty ( $this->pObj->conf_sql['andWhere'] ) ):
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->pObj->objFltr4x->get_selectedFilters( ) ):
        $where    = $this->pObj->objSqlInit->statements['listView']['where'];
        $andWhere = $this->pObj->objFltr4x->andWhereFilter;
          // 3.9.25, 120605: dwildt
        $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
          // 3.9.25, 120605, dwildt+
        $andWhere = $this->pObj->objLocalise->localisationFields_where( $table );
        $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
//$this->pObj->dev_var_dump( $where );
        break;
      default:
          // 3.9.25, 120605: dwildt+
        $where    = $this->pObj->cObj->enableFields( $table );
        $andWhere = $this->pObj->objSqlFun->get_andWherePid( $table );
        $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
        $andWhere = $this->pObj->objLocalise->localisationFields_where( $table );
        $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $andWhere );
//$this->pObj->dev_var_dump( $where );
        break;
    }
          // 3.9.25, 120605, dwildt+
        $where    = $this->pObj->objSqlFun->zz_concatenateWithAnd( $where, $llWhere );

    return $where;
  }
  
/**
 * sql_radialsearchFrom( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function sql_radialsearchFrom( )
  {
    return $this->objFilterRadialsearch->andFrom( );
  }

/**
 * sql_radialsearchHaving( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function sql_radialsearchHaving( )
  {
    return $this->objFilterRadialsearch->andHaving( );
  }

/**
 * sql_radialsearchOrderBy( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function sql_radialsearchOrderBy( )
  {
    return $this->objFilterRadialsearch->andOrderBy( );
  }

/**
 * sql_radialsearchSelect( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function sql_radialsearchSelect( )
  {
    return $this->objFilterRadialsearch->andSelect( );
  }

/**
 * sql_radialsearchWhere( )  :
 *
 * @param       boolean   $withDistance :
 * @return	string
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function sql_radialsearchWhere( $withDistance )
  {
    return $this->objFilterRadialsearch->andWhere( $withDistance );
  }
  



    /***********************************************
    *
    * TypoScript
    *
    **********************************************/

/**
 * tsResultsAtATime( ): Override the TypoScript property results_at_a_time, if
 *                      the current view has a limit.
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function tsResultsAtATime( )
  {
      // RETURN : current view hasn't any limit
    if( empty( $this->conf_view['limit'] ) )
    {
      return;
    }
      // RETURN : current view hasn't any limit

      // Get the limit
    list( $start, $limit ) = explode( ',', $this->conf_view['limit'] );

      // Set default limit
    if( $limit < 1 )
    {
      $limit = 20;
    }

      // Override ts property
    $this->conf['navigation.']['pageBrowser.']['results_at_a_time'] = trim( $limit );

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_navi )
    {
      $prompt = 'pageBrowser.result_at_a_time is overriden by limit property of current view: ' .
                $limit . '.';
      t3lib_div::devlog('[INFO/NAVI] ' . $prompt,  $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
  }

  

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php']);
}

?>