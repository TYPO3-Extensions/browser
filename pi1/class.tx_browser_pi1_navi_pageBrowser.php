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
 *   51: class tx_browser_pi1_navi_pageBrowser
 *   86:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  118:     public function get( $content )
 *
 * TOTAL FUNCTIONS: 2
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
 * get( ): Get the index browser. It has to replace the subpart in the current content.
 *
 * @param	string		$content: current content
 * @return	array
 * @version 3.9.12
 * @since   3.9.9
 */
  public function get( $content )
  {
    $this->content                  = $content;
    $arr_return['data']['content']  = 'Page Browser';

      // RETURN : pagebrowser shouldn't displayed
    if( ! $this->pObj->objFlexform->bool_pageBrowser )
    {
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : pagebrowser shouldn't displayed

      // RETURN : firstVisit but emptyListByStart
    if( $this->pObj->boolFirstVisit && $this->pObj->objFlexform->bool_emptyAtStart )
    {
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : firstVisit but emptyListByStart

    $this->count( );

//:TODO: Anzahl Datensaetze
return $arr_return;

      // RETURN : the sum of records is less than the sum of records per page
      // RETURN : the sum of records is less than the sum of records per page
    if (!is_array($rows) || (is_array($rows) && count($rows) < 1))
    {
      $template = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', '', true);
      $arr_return['data']['template'] = $template;
      return $arr_return;
    }
      // RETURN if we have any row
      //
      //
      //
      // Haben wir einen Index Browser ?
    $int_currTab    = $arr_data['tabIds']['active'];
    $arr_currRowIds = $arr_data['indexBrowserTabArray'][$int_currTab]['keyRow'];

    $template       = $arr_data['template'];
    $rows           = $arr_data['rows'];

    $arr_return['data']['template'] = $template;
    $arr_return['data']['rows']     = $rows;












      // Backup $GLOBALS['TSFE']->id
    $globalTsfeId = $GLOBALS['TSFE']->id;
      // Setup $GLOBALS['TSFE']->id temporarily
    if( ! empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Setup $GLOBALS['TSFE']->id temporarily






      ///////////////////////////////////////////////
      //
      // Change pagebrowser in case of limit

    if($this->conf_view['limit'])
    {
      list($start, $limit) = explode(',', $this->conf_view['limit']);
      if($limit < 1) $limit = 20;
      $this->conf['navigation.']['pageBrowser.']['results_at_a_time'] = trim($limit);

        // DRS - Development Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] pageBrowser.result_at_a_time is overriden by limit: '.$limit.'.',  $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
    }
      // Change pagebrowser in case of limit



      ///////////////////////////////////////////////
      //
      // Init piBase for pagebrowser

    $this->pObj->internal['res_count']          = count($rows);
    $this->pObj->internal['maxPages']           = $this->conf['navigation.']['pageBrowser.']['maxPages'];
    $this->pObj->internal['results_at_a_time']  = $this->conf['navigation.']['pageBrowser.']['results_at_a_time'];
    $this->pObj->internal['showRange']          = $this->conf['navigation.']['pageBrowser.']['showRange'];
    $this->pObj->internal['dontLinkActivePage'] = $this->conf['navigation.']['pageBrowser.']['dontLinkActivePage'];
    $this->pObj->internal['showFirstLast']      = $this->conf['navigation.']['pageBrowser.']['showFirstLast'];
    $this->pObj->internal['pagefloat']          = $this->conf['navigation.']['pageBrowser.']['pagefloat'];
      // Init piBase for pagebrowser



      ///////////////////////////////////////////////
      //
      // Get the wrapped pagebrowser

    $pb = $this->conf['navigation.']['pageBrowser.'];
    $res_items  = $this->pObj->pi_list_browseresults
                  (
                    $pb['showResultCount'], $pb['tableParams'], $pb['wrap.'],$pb['pointer'],$pb['hscText']
                  );
      // Get the wrapped pagebrowser



      ///////////////////////////////////////////////
      //
      // Build the template

    $markerArray                            = $this->pObj->objWrapper->constant_markers();
    $markerArray['###RESULT_AND_ITEMS###']  = $res_items;
    $markerArray['###MODE###']              = $this->mode;
    $markerArray['###VIEW###']              = $this->view;
    $subpart      = $this->pObj->cObj->getSubpart($template, '###PAGEBROWSER###');
    $pageBrowser  = $this->pObj->cObj->substituteMarkerArray($subpart, $markerArray);
    $template     = $this->pObj->cObj->substituteSubpart($template, '###PAGEBROWSER###', $pageBrowser, true);
      // Build the template



      ///////////////////////////////////////////////
      //
      // Process the rows

    $int_start  = $this->pObj->piVars[$pb['pointer']] * $pb['results_at_a_time'];
    $int_amount = $pb['results_at_a_time'];

    $int_counter = 0;
    $int_remove_start = $int_start;
    $int_remove_end   = $int_start + $int_amount;
    $drs_rows_before  = count($rows);
    foreach ($rows as $row => $elements)
    {
      if ($int_counter < $int_remove_start || $int_counter >= $int_remove_end)
      {
        unset($rows[$row]);
      }
      $int_counter++;
    }
    $drs_rows_after = count($rows);
      // Process the rows



      ///////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if ($drs_rows_after != $drs_rows_before)
    {
      $removed_rows = $drs_rows_before - $drs_rows_after;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The pagebrowser has #'.$removed_rows.' rows removed.',  $this->pObj->extKey, 0);
      }
    }
      // DRS - Development Reporting System



      ///////////////////////////////////////////////
      //
      // RETURN the result

    $arr_return['data']['template'] = $template;
    $arr_return['data']['rows']     = $rows;
    $GLOBALS['TSFE']->id            = $globalTsfeId; // #9458
    return $arr_return;
      // RETURN the result

##############################################################################
    $this->content                  = $content;
    $arr_return['data']['content']  = $content;

    return $arr_return;
  }









    /***********************************************
    *
    * Counting
    *
    **********************************************/



/**
 * count( ):
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function count( )
  {
    $this->count_resSql( );
  }



/**
 * count_resSql( ):
 *
 * @return  array   $res  : SQL ressource
 * @version 3.9.12
 * @since   3.9.12
 */
  private function count_resSql( )
  {
      // Get current table.field of the index browser
    $tableField           = $this->pObj->localTable['uid'];
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
die( $query );
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
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
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
      case( $this->pObj->objFltr4x->var_aFilterIsSelected( ) ):
        $from = $this->pObj->objSql->sql_query_statements['rows']['from'];
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
      case( $this->pObj->objFltr4x->var_aFilterIsSelected( ) ):
        $where  = $this->pObj->objSql->sql_query_statements['rows']['where'];
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








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php']);
}

?>