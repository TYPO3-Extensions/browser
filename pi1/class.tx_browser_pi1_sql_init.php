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
 * The class tx_browser_pi1_sql_init bundles all methods, which initialise the sql engine 4x
 * statement with a FROM and a WHERE clause and maybe with the array JOINS.
 * It is the new SQL modul from Browser version 4.0 and it replaces the former SQL modul.
 *
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *
 * @version   3.9.12
 * @since     3.9.9
 *
 * @package     TYPO3
 * @subpackage  browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   64: class tx_browser_pi1_sql_init
 *  119:     public function __construct($parentObj)
 *
 *              SECTION: Initialise variables
 *  150:     public function init( )
 *
 *              SECTION: Initialise class variables
 *  205:     private function init_class_statements( )
 *
 *              SECTION: Initialise global variables
 *  246:     private function init_global_csv( )
 *  315:     private function init_global_csvSelect( )
 *  454:     private function init_global_csvSearch( )
 *  508:     private function init_global_csvOrderBy( )
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_sql_init
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



    // [String] SQL error message
  var $error = null;
    // [String] SQL query
  var $query = null;

//    // [String/CSV] Proper select statement for current rows.
//    //              I.e: 'tx_org_cal.title,  tx_org_cal.subtitle,  tx_org_cal.teaser_short, ...'
//  var $csvSelect  = null;
//    // [String/CSV] Proper select statement for current rows for the search query.
//    //              I.e: 'tx_org_cal.title AS \'tx_org_cal.title\', tx_org_cal.subtitle AS ...'
//  var $csvSearch  = null;
//    // [String/CSV] Proper order by statement (without ORDER BY).
//    //              I.e: 'tx_org_cal.datetime DESC'
//  var $csvOrderBy = null;
    // [Array]      Array with elements like rows, ... Each element contains SQL query statements
    //              like for SELECT, FROM, WHERE, GROUP BY, ORDER BY
  var $statements = null;









    /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  public function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }











    /***********************************************
    *
    * Initialise variables
    *
    **********************************************/



/**
 * init( ): Sets the class vars csvSelect, csvSelect, csvOrderBy, arrLocalTable.
 *          Sets the class var sql_query_statements['listView'] (sql query statements)
 *
 * @return	void
 * @version 3.9.8
 * @since   3.9.8
 */
  public function init( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'begin' );

      // Set the globals csvSelect, csvSelect, csvOrderBy, arrLocalTable
    $arr_return = $this->init_global_csv( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_return;
    }
      // Set the globals csvSelect, csvSelect, csvOrderBy

//      // Init the class vars csvSelect, csvSelect, csvOrderBy, arrLocalTable
//    $this->init_class_csv( );

    // Set the SQL query statements
    $arr_return = $this->init_class_statements( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // SQL query array

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
  }











    /***********************************************
    *
    * Initialise class variables
    *
    **********************************************/



//  /**
//   * init_class_csv( ): Set the class vars csvSelect, csvSearch, csvOrderBy, arrLocalTable
//   *
//   * @return	array		$arr_return : Array in case of an error with the error message
//   * @version 3.9.12
//   * @since   3.9.12
//   */
//  private function init_class_csv( )
//  {
//    $this->csvSelect      = $this->pObj->csvSelect;
//    $this->csvSearch      = $this->pObj->csvSearch;
//    $this->csvOrderBy     = $this->pObj->csvOrderBy;
//    $this->arrLocalTable  = $this->pObj->arrLocalTable;
//
//    return $arr_return;
//  }



    /**
   * init_class_statements( ):  Get the SQL statements.
   *                            Result depends on SQL mode manual or auto.
   *
   * @return	array
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_class_statements( )
  {
      // RETURN : array in SQL manual mode
    if( $this->pObj->b_sql_manual )
    {
      $arr_return = $this->pObj->objSqlMan_3x->get_query_array( $this );
      $this->statements['listView'] = $arr_return['data'];
      return $arr_return;
    }
      // RETURN : array in SQL manual mode

      // RETURN : array in SQL auto mode
    $arr_return = $this->pObj->objSqlAut->get_statements( );
    $this->statements['listView'] = $arr_return['data'];
    return $arr_return;
      // RETURN : array in SQL auto mode
  }









    /***********************************************
    *
    * Initialise global variables
    *
    **********************************************/



  /**
   * init_global_csv( ): Set the globals csvSelect, csvSearch, csvOrderBy, arrLocalTable
   *
   * @return	array		$arr_return : Array in case of an error with the error message
   * @version 3.9.12
   * @since   3.9.12
   */
  private function init_global_csv( )
  {
    $arr_return = array( );
    $arr_return['error']['status'] = false;

      // Set the globals csvSelect and arrLocalTable
    if( ! $this->init_global_csvSelect( ) )
    {
      $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
      $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                        $this->pObj->pi_getLL( 'error_sql_select') .
                      '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Set the globals csvSelect and arrLocalTable

      // Set the global csvSearch
    if( ! $this->init_global_csvSearch( ) )
    {
      $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
      $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                        $this->pObj->pi_getLL( 'error_sql_search' ) .
                      '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Set the global csvSearch

      // Set the global csvOrderBy
    if( ! $this->init_global_csvOrderBy( ) )
    {
      $str_header  =  '<h1 style="color:red;">' . $this->pObj->pi_getLL( 'error_sql_h1' ) . '</h1>';
      $str_prompt  =  '<p style="color:red;font-weight:bold;">' .
                        $this->pObj->pi_getLL( 'error_sql_orderby' ) .
                      '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // Set the global csvOrderBy

    return $arr_return;
  }







 /**
  * init_global_csvSelect( ): Set the global csvSelect. Values are from the TypoScript select
  *
  * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
  * @version 3.9.12
  * @since   3.9.12
  */
  private function init_global_csvSelect( )
  {
    $mode       = $this->piVar_mode;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;



      ///////////////////////////////////
      //
      // Get the SELECT statement

    $this->pObj->csvSelect  = $conf_view['select'];
    $this->pObj->csvSelect  = $this->pObj->objSqlFun->cObjGetSingle
                              (
                                'select',
                                $this->pObj->csvSelect,
                                $conf_view['select'],
                                $conf_view['select.']
                              );
    $this->pObj->csvSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $this->pObj->csvSelect );

    if( empty( $this->pObj->csvSelect ) )
    {
      if( $this->pObj->b_drs_error )
      {
        $prompt = 'views.'.$viewWiDot.$mode.' hasn\'t any select fields.';
        t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'Did you included the static template from this extensions?';
        t3lib_div::devLog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        $prompt = 'Did you configure ' . $conf_path . '.select ?';
        t3lib_div::devLog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        $prompt = 'ABORTED';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // Get the SELECT statement



      //////////////////////////////////////////////////////////////////////
      //
      // Get the parts behind an AS, replace aliases with real names

    $csv_before_process = $this->pObj->csvSelect;
    $csv_after_process  = $this->pObj->objSqlFun->expressionToAlias( $csv_before_process );
    $arr_csv            = explode( ',', $csv_after_process );
    $arr_csv            = $this->pObj->objSqlFun->expressionAndAliasToTable( $arr_csv );
    $csv_after_process  = implode( ', ', $arr_csv );
      // Get the parts behind an AS, replace aliases with real names


      //////////////////////////////////////////////////////////////////////
      //
      // RETURN in case of an error

    if( empty( $csv_after_process ) )
    {
      if ($this->pObj->b_drs_error)
      {
        $prompt = '$csv_after_process is FALSE or is empty.';
        t3lib_div::devlog('[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'ABORTED';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // RETURN in case of an error



      //////////////////////////////////////////////////////////////////////
      //
      // Delete the first table.field, if it is the uid of the arrLocalTable

      // Init the global arrLocalTable, if it isn't inited
    if( ! is_array( $this->pObj->arrLocalTable) )
    {
      $this->setGlobal_arrLocalTable( );
    }

    $str_deleted_tablefield = false;
    $arr_tablefields        = explode( ',', $csv_after_process );
    if( trim($arr_tablefields[0] ) == $this->pObj->arrLocalTable['uid'] )
    {
      $str_deleted_tablefield = $arr_tablefields[0];
      unset( $arr_tablefields[0] );
      foreach( ( array ) $arr_tablefields as $key => $value )
      {
        $arr_tablefields[$key] = trim( $value );
      }
      $csv_after_process = implode( ', ', $arr_tablefields );
    }
      // Delete the first table.field, if it is the uid of the arrLocalTable



      ///////////////////////////////////
      //
      // DRS - Logging if user defined values were changed

    if( $csv_before_process != $csv_after_process )
    {
      $this->pObj->csvSelect = $csv_after_process;
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Values for the global var csvSelect were changed.<br />
           Before changing:<br />
           '.$csv_before_process.'<br />
           After changing:<br />
           '.$csv_after_process;
        t3lib_div::devlog('[INFO/SQL] '.$prompt, $this->pObj->extKey, 0);
        if( $str_deleted_tablefield )
        {
          $prompt = $str_deleted_tablefield . ' is deleted, because it is the ' .
                    'first field in the statement and the value of the localTable.uid.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
    }
      // DRS - Logging if user defined values were changed

    return true;
  }



 /**
  * init_global_csvSearch( ): Set the global csvSearch. Values are from the TypoScript.
  *
  *                      If search is empty, search will get the values out of the select statement.
  *
  * @return	boolean		TRUE
  * @version 3.9.12
  * @since   3.9.12
  */
  private function init_global_csvSearch( )
  {
    $mode = $this->piVar_mode;

    $conf_view = $this->conf_view;


      ///////////////////////////////////
      //
      // Get the SEARCH values

    $csvSearch = $this->pObj->conf_sql['search'];
    $csvSearch = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );

    if ( empty( $csvSearch ) )
    {
      $csvSearch  = $this->pObj->conf_sql['select'];
      $csvSearch  = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $csvSearch );
      if( $this->pObj->b_drs_sql )
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
  * init_global_csvOrderBy( ): Set the global csvOrderBy. Values are from the TypoScript orderBy or select
  *
  * @return	boolean		TRUE, if there is a orderBy value. FALSE, if there isn't any orderBy value
  * @version 3.9.12
  * @since   3.9.12
  */
  private function init_global_csvOrderBy( )
  {
    $mode       = $this->piVar_mode;
    $conf_path  = $this->conf_path;
    $conf_view  = $this->conf_view;


      ///////////////////////////////////
      //
      // Get the override ORDER BY clause

    $orderBy = $this->conf_view['override.']['orderBy'];
    $orderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $orderBy );
    if( $orderBy )
    {
      if( $this->pObj->b_drs_sql )
      {
        $prompt = $conf_path . '.override.orderBy is: ' . $orderBy;
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'The system generated ORDER BY clause will be ignored!';
        t3lib_div::devLog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'ORDER BY ' . $orderBy;
        t3lib_div::devLog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
      }
    }
      // Get the override ORDER BY clause


      ///////////////////////////////////
      //
      // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error

    if( empty ( $orderBy ) )
    {
      $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $conf_view['orderBy'] );
      if( empty( $this->pObj->csvOrderBy ) )
      {
        $this->pObj->csvOrderBy = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $conf_view['select'] );
      }
      if( empty( $this->pObj->csvOrderBy ) )
      {
        if ($this->pObj->b_drs_error)
        {
          $prompt = 'views.' . $viewWiDot . $mode . ' hasn\'t any orderBy fields.';
          t3lib_div::devlog( '[ERROR/SQL] ', $this->pObj->extKey, 3 );
          $prompt = 'ABORTED';
          t3lib_div::devlog( '[WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
        }
        return false;
      }
    }
      // If we don't have any override clause, get the ORDER BY clause. If there isn't any one: RETURN with an error


      ///////////////////////////////////
      //
      // Get the parts behind an AS, replace aliases with real names

    $csv_before_process = $this->pObj->csvOrderBy;
    $csv_before_process = $this->pObj->objSqlFun->expressionToAlias( $csv_before_process );
    $arr_csv            = explode( ',', $csv_before_process );
    $arr_csv            = $this->pObj->objSqlFun->expressionAndAliasToTable( $arr_csv );
    $csv_before_process = implode( ', ', $arr_csv );
    $csv_after_process  = $csv_before_process;
      // Get the parts behind an AS, replace aliases with real names



      ///////////////////////////////////
      //
      // RETURN in case of an error

    if( empty ( $csv_after_process ) )
    {
      if( $this->pObj->b_drs_error )
      {
        $prompt = '$csv_after_process is FALSE or is empty.';
        t3lib_div::devlog('[ERROR/SQL] '. $prompt, $this->pObj->extKey, 3);
        $prompt = 'ABORTED';
        t3lib_div::devlog( '[WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // RETURN in case of an error



      ///////////////////////////////////
      //
      // Delete the first table.field, if it is the uid of the arrLocalTable

      // Init the global arrLocalTable, if it isn't inited
    if( ! is_array( $this->pObj->arrLocalTable ) )
    {
      $this->setGlobal_arrLocalTable( );
    }

    $str_deleted_tablefield = false;
    $arr_tablefields        = explode( ',', $csv_after_process );
    if( trim($arr_tablefields[0]) == $this->pObj->arrLocalTable['uid'] )
    {
      $str_deleted_tablefield = $arr_tablefields[0];
      unset( $arr_tablefields[0] );
      foreach( ( array ) $arr_tablefields as $key => $value )
      {
        $arr_tablefields[$key] = trim($value);
      }
      $csv_after_process = implode( ', ', $arr_tablefields );
    }
      // Init the global arrLocalTable, if it isn't inited


      ////////////////////////////////////////////////////////////////////
      //
      // DRS - Logging if user defined values were changed

    if( $csv_before_process != $csv_after_process )
    {
      $this->pObj->csvOrderBy = $csv_after_process;
      if( $this->pObj->b_drs_sql )
      {
        $prompt = 'Values for the global var csvOrderBy were changed.<br />
           Before changing:<br />
           '.$csv_before_process.'<br />
           After changing:<br />
           '.$csv_after_process;
        t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        if ($str_deleted_tablefield)
        {
          $prompt = $str_deleted_tablefield . ' is deleted, because it is the first field in the statement and the value of the localTable.uid.';
          t3lib_div::devlog('[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
    }

    return true;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_init.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql_init.php']);
}

?>
