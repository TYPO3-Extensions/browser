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

  var $csvSelect  = null;
  var $csvSearch  = null;
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
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Set the globals csvSelect, csvOrderBy and arrLocalTable
    $arr_result       = $this->pObj->objSqlFun->global_all( );
    if( $arr_result['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_result;
    }
    $this->csvSelect  = $arr_result['data']['csvSelect'];
    $this->csvSearch  = $arr_result['data']['csvSearch'];
    $this->csvOrderBy = $arr_result['data']['csvOrderBy'];
    $this->pObj->dev_var_dump( __METHOD__, __LINE__, $arr_result );
    unset( $arr_result );
      // Set the globals csvSelect, csvOrderBy and arrLocalTable


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









  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_sql.php']);
  }

?>