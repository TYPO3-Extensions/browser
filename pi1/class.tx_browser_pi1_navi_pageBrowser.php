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
 * The class tx_browser_pi1_navi_pageBrowser bundles methods for the page browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.12
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
 *  394:     private function sqlStatement_from( $table )
 *  421:     private function sqlStatement_where( $table )
 *
 *              SECTION: TypoScript
 *  475:     private function tsResultsAtATime( )
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
 * @version 3.9.12
 * @since   3.9.12
 */
  public function get( $content )
  {
      // Set class var
    $this->content = $content;

      // RETURN : pagebrowser shouldn't displayed
    if( ! $this->pObj->objFlexform->bool_pageBrowser )
    {
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : pagebrowser shouldn't displayed

      // RETURN : firstVisit but emptyListAtStart
    if( $this->pObj->boolFirstVisit && $this->pObj->objFlexform->bool_emptyAtStart )
    {
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : firstVisit but emptyListAtStart

      // Set class var sum
    $this->count( );

      // RETURN : there isn't any record.
    if( $this->sum < 1 )
    {
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
    $res_items  = $this->pObj->pi_list_browseresults
                  (
                    $confPageBrowser['showResultCount'],
                    $confPageBrowser['tableParams'],
                    $confPageBrowser['wrap.'],
                    $confPageBrowser['pointer'],
                    $confPageBrowser['hscText']
                  );
      // Get the wrapped pagebrowser

      // Reset $GLOBALS['TSFE']->id
    $GLOBALS['TSFE']->id            = $globalTsfeId; // #9458

      // RETURN the content
    $arr_return['data']['content']  = $res_items;
    return $arr_return;
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
 * @version 3.9.12
 * @since   3.9.12
 */
  private function count_fromIndexBrowser( )
  {
      // RETURN : index browser isn't enabled
    if( ! isset ( $this->pObj->objNaviIndexBrowser->indexBrowserTab ) )
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
 * @version 3.9.12
 * @since   3.9.12
 */
  private function count_resSql( )
  {
      // Get current table.field of the index browser
    $tableField           = $this->pObj->arrLocalTable['uid'];
    list( $table, $field) = explode( '.', $tableField );

      // Query for all filter items
    $select   = "COUNT( DISTINCT " . $tableField . " ) AS 'count'";
    $from     = $this->sqlStatement_from( $table );
    $where    = $this->sqlStatement_where( $table );
    $groupBy  = null;
    $orderBy  = null;
    $limit    = null;

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

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
        // Reset SQL charset
      $this->sqlCharsetSet( $currSqlCharset );
      $arr_return = $this->pObj->objSqlFun->prompt_error( $query, $error );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Return SQL result
    $arr_return['data']['res'] = $res;
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
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->pObj->objNaviIndexBrowser->var_aFilterIsSelected( ) ):
        $from = $this->pObj->objSqlInit->statements['rows']['from'];
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
 * @version 3.9.12
 * @since   3.9.12
 */
  private function sqlStatement_where( $table )
  {
    switch( true )
    {
      case( isset( $this->pObj->piVars['sword'] ) ):
      case( $this->pObj->objNaviIndexBrowser->var_aFilterIsSelected( ) ):
        $where  = $this->pObj->objSqlInit->statements['rows']['where'];
        $where  = $where . $this->pObj->objFltr4x->andWhereFilter;
        break;
      default:
        $andEnableFields = $this->pObj->cObj->enableFields( $table );
        if( $andEnableFields )
        {
          $where = "1";
        }
        $where  = $where . $andEnableFields;
        if( empty ( $where ) )
        {
          $where = "1";
        }
        $llWhere  = $this->pObj->objLocalise->localisationFields_where( $table );
        if( $llWhere )
        {
          $where  = $where . " AND " . $llWhere;
        }
        break;
    }

    return $where;
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
