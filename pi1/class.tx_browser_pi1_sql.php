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
 * @version   3.9.9
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


  
    // [String] SQL error message
  var $error = null;
    // [String] SQL query
  var $query = null;

    // [Array]  Array tableFields for uid and pid of the localTable
    //          I.e: array( 'uid' => 'tx_org_cal.uid', 'pid' => 'tx_org_cal.pid' )
  var $arrLocalTable = null;
    // [String/CSV] Proper select statement for current rows.
    //              I.e: 'tx_org_cal.title,  tx_org_cal.subtitle,  tx_org_cal.teaser_short, ...'
  var $csvSelect  = null;
    // [String/CSV]  Proper select statement for current rows for the search query.
    //               I.e: 'tx_org_cal.title AS \'tx_org_cal.title\', tx_org_cal.subtitle AS ...'
  var $csvSearch  = null;
    // [String/CSV]  Proper order by statement (without ORDER BY).
    //               I.e: 'tx_org_cal.datetime DESC'
  var $csvOrderBy = null;









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
    * Query building
    *
    **********************************************/



  /**
 * main( ): Display a search form, a-z-Browser, pageBrowser and a list of records
 *
 * @return	string  $template : The processed HTML template
 * @version 3.9.8
 * @since 1.0.0
 */
  function init( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'begin' );

      // Set the globals csvSelect, csvSelect, csvOrderBy, arrLocalTable
    $arr_result       = $this->pObj->objSqlFun->global_all( );
    if( $arr_result['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__, 'end' );
      return $arr_result;
    }
      // Development prompting
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvSelect );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvSearch );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->csvOrderBy );
//    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $this->arrLocalTable );
      // Development prompting
    unset( $arr_result );
      // Set the globals csvSelect, csvSelect, csvOrderBy

      // SQL query array
    $arr_result = $this->get_queryArray( );
    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $arr_result );
    if( $arr_result['error']['status'] )
    {
      return $arr_result;
    }
    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
      // #33892, 120214, dwildt+
    $groupBy  = null;
    $orderBy  = $arr_result['data']['orderBy'];
      // #33892, 120214, dwildt+
    $limit    = null;
    $union    = $arr_result['data']['union'];
    unset( $arr_result );
      // SQL query array

      // Short vars
    $conf       = $this->conf;
    $mode       = $this->piVar_mode;
    $view       = $this->view;
    $conf_view  = $this->conf_view;
      // Short vars

        $str_header  = '<h1 style="color:red;">' . $this->pObj->pi_getLL('error_sql_h1') . '</h1>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">' . $this->pObj->pi_getLL('error_sql_select') . '</p>';
        $str_prompt  = '<p style="color:red;font-weight:bold;">' . 'SQLengine 4.x' . '</p>';
        $arr_return['error']['status'] = true;
        $arr_return['error']['header'] = $str_header;
        $arr_return['error']['prompt'] = $str_prompt;
        return $arr_return;
    $arr_return   = $arr_data;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

    return $arr_return;
  }

  
  
  
  
  
  
  
  
  /**
 * get_queryArray( ):
 *
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_queryArray( )
  {
      // RETURN case is SQL manual
    if( $this->pObj->b_sql_manual )
    {
      $arr_result = $this->pObj->objSqlMan->get_query_array( $this );
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlMan->get_query_array( )' );
      return $arr_result;
    }
      // RETURN case is SQL manual

      // RETURN case is SQL automatically
    $arr_result = $this->pObj->objSqlAut->get_query_array( );
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'after $this->pObj->objSqlAut->get_query_array( )' );
    return $arr_result;
      // RETURN case is SQL automatically
  }









  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']);
  }

?>