<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_map bundles methods for rendering and processing calender based content, filters and category menues
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.6
* @since 3.9.6
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   83: class tx_browser_pi1_map
 *  137:     function __construct($pObj)
 * TOTAL FUNCTIONS: 21
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_map
{










  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Map
  *
  **********************************************/









  /**
 * map(): Returns a map
 *
 * @param array   $rows: Consolidated rows
 * @param array   $template: Current HTML template
 * @return  array   $arr_return: rows, template, success
 * @version 4.0.0
 * @since 4.0.0
 */
  public function map( $rows, $template )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set default values

    $this->rows             = $rows;
    $this->template         = $template;
    $arr_return['rows']     = $rows;
    $arr_return['template'] = $template;
    $arr_return['success']  = false;
      // Set default values



      /////////////////////////////////////////////////////////////////
      //
      // Get TypoScript configuration for the current view

    $conf             = $this->pObj->conf;
    $mode             = $this->pObj->piVar_mode;
    $view             = $this->pObj->view;
    $viewWiDot        = $view.'.';
    $this->conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    $this->singlePid  = $this->pObj->objZz->get_singlePid_for_listview( );
      // Get TypoScript configuration for the current view



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN current view isn't the list view

    if( $this->pObj->view != 'list' )
    {
      if ($this->pObj->b_drs_map)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: Current view isn\'t the list view.', $this->pObj->extKey, 0);
      }
    }
      // RETURN current view isn't the list view
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']);
}
?>