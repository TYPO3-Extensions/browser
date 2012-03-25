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
 * The class tx_browser_pi1_sql bundles methods with a workflow for sql queries and rows.
 * statement with a FROM and a WHERE clause and maybe with the array JOINS.
 * It is the new SQL modul from Browser version 4.0 and it replaces the former SQL modul.
 *
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *
 * @version   3.9.12
 * @since     3.9.12
 *
 * @package     TYPO3
 * @subpackage  browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *
 */
class tx_browser_pi1_sql
{
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


  var $csvSelect      = null;
  var $csvSearch      = null;
  var $csvOrderBy     = null;
  var $arrLocalTable  = null;


    // [Array]  $sqlStatements[category][statement] :
    //          category  : filter, indexBrowser, pageBrowser, listview, singleview
    //          statement : select, from, where, andWhere[search], andWhere[filter], groupBy, orderBy, limit
  var $sqlStatements = null;








/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  protected function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }










    /***********************************************
    *
    * Init
    *
    **********************************************/



/**
 * init( ): Sets the class vars csvSelect, csvSelect, csvOrderBy, arrLocalTable.
 *          Sets the class var sql_query_statements['rows'] (sql query statements)
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  public function init( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'begin' );

      // Set the globals csvSelect, csvSelect, csvOrderBy, arrLocalTable
    $arr_return = $this->pObj->objSqlFun->global_all( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_return;
    }
    unset( $arr_return );
      // Set the globals csvSelect, csvSelect, csvOrderBy

    $arr_return = $this->statements( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_return;
    }
    unset( $arr_return );

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
  }










    /***********************************************
    *
    * Statements
    *
    **********************************************/



/**
 * statements( ): 
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statements( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'brgin' );

    $this->statementsSelect( );
    $this->statementsFrom( );
    $this->statementsWhere( );
    $this->statementsGroupBy( );
    $this->statementsOrderBy( );

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
  }










    /***********************************************
    *
    * Statements SELECT
    *
    **********************************************/



/**
 * statementsSelect( ): 
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsSelect( )
  {
  }










    /***********************************************
    *
    * Statements Where
    *
    **********************************************/



/**
 * statementsWhere( ):
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsWhere( )
  {
    $this->statementsWhereAndFilter( );
    $this->statementsWhereAndSearch( );
  }



/**
 * statementsWhereAndFilter( ):
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsWhereAndFilter( )
  {
      // Get filter from piVars
      // Build statement
      // Set class var
    $this->sqlStatements['filter']['andWhere']['filter'] = 'andWhere.filter';
  }



/**
 * statementsWhereAndSearch( ):
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsWhereAndSearch( )
  {
      // Get filter from piVars
      // Build statement
      // Set class var
    $this->sqlStatements['filter']['andWhere']['search'] = 'andWhere.search';
  }










    /***********************************************
    *
    * Statements ORDER BY
    *
    **********************************************/



/**
 * statementsOrderBy( ): 
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsOrderBy( )
  {
  }










    /***********************************************
    *
    * Statements GROUP BY
    *
    **********************************************/



/**
 * statementsGroupBy( ): 
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsGroupBy( )
  {
  }










    /***********************************************
    *
    * Statements LIMIT
    *
    **********************************************/



/**
 * statementsLimit( ): 
 *
 * @version 3.9.12
 * @since   3.9.12
 */
  private function statementsLimit( )
  {
  }



}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']);
}

?>