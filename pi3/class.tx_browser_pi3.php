<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   47: class tx_browser_pi3 extends tslib_pibase
 *   64:     function main($content, $conf)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin '' for the 'test' extension.
 *
 * @author    Dirk Wildt <wildt@die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 */
class tx_browser_pi3 extends tslib_pibase
{

  var $prefixId      = 'tx_browser_pi3';        // Same as class name
  var $scriptRelPath = 'pi3/class.tx_browser_pi3.php';    // Path to this script relative to the extension dir.
  var $extKey        = 'test';    // The extension key.
  var $pi_checkCHash = true;



    /**
 * The main method of the PlugIn
 *
 * @param	string		$content: The PlugIn content
 * @param	array		$conf: The PlugIn configuration
 * @return	string		The content that is displayed on the website
 */
  function main( $content, $conf )
  {
    $content = $content . $this->cObj->COBJ_ARRAY( $conf['title.'], $ext='' );
    $content = $content . $this->cObj->COBJ_ARRAY( $conf['manuals.'], $ext='' );
    $content = $content . $this->cObj->COBJ_ARRAY( $conf['tutorials.'], $ext='' );
    $content = $content . $this->cObj->COBJ_ARRAY( $conf['websites.'], $ext='' );
    return $content;
  }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi3/class.tx_browser_pi3.php'])    {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi3/class.tx_browser_pi3.php']);
}