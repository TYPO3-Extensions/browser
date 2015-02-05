<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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



// #61520, 140911, dwildt, 1-
//require_once(PATH_tslib . 'class.tslib_pibase.php');

// #61520, 140911, dwildt, +
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

if ( $version < 6002000 )
{
  require_once(PATH_tslib . 'class.tslib_pibase.php');
}
// #61520, 140911, dwildt, +



/**
* Class provides userfuncs based on tslib_pibase
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    browser
* @version 6.0.0
* @since 3.6.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_browser_typoscript extends tslib_pibase
 *   79:     public function numberFormat($content = '', $conf = array())
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
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
 * @param	string		$content: current content of TypoScript workflow
 * @param	array		$conf: current TypoScript configuration array
 * @return	string		formatted number
 * @version 4.8.6
 * @since 3.6.2
 */
  public function numberFormat($content = '', $conf = array())
  {
      // 13145, dwildt, 110217
    global $TSFE;
      // #i0044, dwildt, 1-
    $local_cObj = $TSFE->cObj;
      // #i0044, dwildt, 1+
    $local_cObj->data = $GLOBALS['TSFE']->tx_browser_pi1->cObj->data;
    if( ! $content )
    {
      $conf     = $conf['userFunc.'];
      $content  = $local_cObj->cObjGetSingle( $conf['number'], $conf['number.'] );
    }
//var_dump( __METHOD__, __LINE__, $content, $local_cObj->data );
//var_dump( __METHOD__, __LINE__, $content );

    return number_format($content, $conf['decimal'], $conf['dec_point'], $conf['thousands_sep']);
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php']);
}

?>
