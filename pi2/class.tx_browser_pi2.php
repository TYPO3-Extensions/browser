<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Dirk Wildt <wildt.at.die-netzmacher.de>
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

require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Plugin 'Browser' for the 'browser' extension - the fastest way for your data into the TYPO3 frontend.
 *
 * @author    Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_browser_pi2 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  109:     function main($content, $conf, $pObj)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi2 extends tslib_pibase {

  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $prefixId = 'tx_browser_pi2';
  // Same as class name
  var $scriptRelPath = 'pi2/class.tx_browser_pi2.php';
  // Path to this script relative to the extension dir.
  var $extKey = 'browser';
  // The extension key.
  var $arr_tickets = FALSE;
  // [array] The Array with the Tiockets. Set by tx_browser_pi2_tickets.
  // Variables set by this class


  //////////////////////////////////////////////////////
  //
  // Variables set by children classes

  // Variables set by children classes





















  /***********************************************
   *
   * Main Process
   *
   **********************************************/




  /**
 * Main method of your PlugIn
 *
 * @param	string		$content: The content of the PlugIn
 * @param	array		$conf: The PlugIn Configuration
 * @param	array		$pObj: The parent object
 * @return	string		The content that should be displayed on the website
 */
  function main($content, $conf, $pObj) {

    //var_dump(get_defined_constants());

    $this->conf = $conf;
    $this->pObj = $pObj;

    $this->pi_loadLL();



    //////////////////////////////////////////////////////////////////////
    //
    // Instantiate the pi2 classes

    require_once('class.tx_browser_pi2_checker.php');
    // Class with the functions for checking TypoScript update
    $this->objCheck = new tx_browser_pi2_checker($this);
    require_once('class.tx_browser_pi2_tickets.php');
    // Class with the Ticket array and defined constants
    $this->objTickets = new tx_browser_pi2_tickets($this);
    // Instantiate the pi2 classes



    //////////////////////////////////////////////////////////////////////
    //
    // Make cObj instance

    $this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
    // Make cObj instance



    //////////////////////////////////////////////////////////////////////
    //
    // Workflow

    // Get the tickets
    $this->arr_tickets = $this->objTickets->init_tickets();
    // Get the report
    $template          = $this->objCheck->loop_tickets();
    // Workflow


    //////////////////////////////////////////////////////////////////////
    //
    // Return the result (HTML string)

    return $this->pi_wrapInBaseClass($template);
    // Return the result (HTML string)
  }



}







if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2.php']);
}

?>