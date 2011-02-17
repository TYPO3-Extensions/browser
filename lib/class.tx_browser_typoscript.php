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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* Class provides methods for the TCA.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    org
* @version 0.3.1
* @since 0.3.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_org_extmanager
 *   67:     function promptQuickstart()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
 
require_once(PATH_tslib . 'class.tslib_pibase.php');

class tx_browser_typoscript extends tslib_pibase
{

    // The extension key.
  public $extKey        = 'browser';
  public $prefixId      = 'tx_browser_typoscript';
    // Path to any file in pi1 for locallang
  public $scriptRelPath = 'lib/class.tx_browser_typoscript.php';








  /**
 * numberFormat(): format numbers with thousands seperator and decimal point
 *
 * @param   string    $content: current content of TypoScript workflow
 * @param   array     $conf: current TypoScript configuration array
 * @since 3.6.2
 * @version 3.6.2
 * @return  string    formatted number
 */
  public function numberFormat($content = '', $conf = array()) 
  {
    global $TSFE;
    $local_cObj = $TSFE->cObj;

    if (!$content) 
    {
      $conf     = $conf['userFunc.'];
      $content  = $local_cObj->cObjGetSingle($conf['number'], $conf['number.']);
    }

    return number_format($content, $conf['decimal'], $conf['dec_point'], $conf['thousands_sep']);
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/org_workshops/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/org_workshops/lib/class.tx_browser_typoscript.php']);
}

?>