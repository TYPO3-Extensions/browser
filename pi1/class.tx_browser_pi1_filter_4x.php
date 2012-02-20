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
  * HTML
  *
  **********************************************/

/**
 * get_htmlFilters( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 * 
 * @version 3.9.9
 * @since   3.9.9
 */
  public function get_htmlFilters( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // LOOP each filter
    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( ( array ) $fields as $field => $confField )
      {
        if( rtrim($field, '.') != $field )
        {
          continue;
        }
        $tableField = $tableWiDot . $field;
        $arr_return = $this->get_htmlFilter( $tableField );
        if( $arr_return['error']['status'] )
        {
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
          return $arr_return;
        }
      }
    }
      // LOOP each filter

//    $str_header  = '<h1 style="color:red;">' . __METHOD__ . '</h1>';
//    $str_prompt  = '<p style="color:red;font-weight:bold;">Development</p>';
//    $arr_return['error']['status'] = true;
//    $arr_return['error']['header'] = $str_header;
//    $arr_return['error']['prompt'] = $str_prompt;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * get_htmlFilter( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlFilter( $tableField )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    $arr_return = $this->sql( $tableField );
    if( $arr_return['error']['status'] )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
    unset( $arr_return );

    // Set HTML object

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * sql( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  public function sql( $tableField )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    $arr_return = $this->sql_select( $tableField );
    if( $arr_return['error']['status'] )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_return;
    }
    $select = $arr_return['data']['select'];
    unset( $arr_return );
    // Get SQL result
      // Get SELECT statement
      // Get GROUP BY
      // Build SELECT statement
      // Exec SELECT


      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * sql_select( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  public function sql_select( $tableField )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

//    $arr_return = $this->sql_select( $tableField );
//    if( $arr_return['error']['status'] )
//    {
//      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
//      return $arr_return;
//    }
//    $select = $arr_return['data']['select'];
//    unset( $arr_return );
    // Get SQL result
      // Get SELECT statement
      // Get GROUP BY
      // Build SELECT statement
      // Exec SELECT

    var_dump( __METHOD__, $tableField );


    $str_header  = '<h1 style="color:red;">' . __METHOD__ . '</h1>';
    $str_prompt  = '<p style="color:red;font-weight:bold;">Development ' . $tableField . '</p>';
    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = $str_header;
    $arr_return['error']['prompt'] = $str_prompt;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']);
}
?>
