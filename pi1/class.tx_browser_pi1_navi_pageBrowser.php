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
 * The class tx_browser_pi1_navi_pageBrowser bundles methods for the page browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.12
 * @since       3.9.12
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  100: class tx_browser_pi1_navi_pageBrowser
 *  188:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  220:     public function get( $content )
 *
 *              SECTION: Requirements
 *  297:     private function localisation_init( )
 *  349:     private function requirements_check( )
 *  417:     private function tableField_check( )
 *  467:     private function tableField_init( )
 *
 *              SECTION: Subparts
 *  556:     private function subpart( )
 *  602:     private function subpart_setContainer( )
 *  623:     private function subpart_setTabs( )
 *
 *              SECTION: Tabs
 *  721:     private function tabs_init( )
 *  788:     private function tabs_initAttributes( $csvAttributes, $tabLabel, $tabId )
 *  840:     private function tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $displayWoItems )
 *  899:     private function tabs_initSpecialChars( $arrCsvAttributes )
 *
 *              SECTION: Count chars
 *  958:     private function count_chars( )
 *  998:     private function count_chars_addSumToTab( $res )
 * 1050:     private function count_chars_resSqlCount( $currSqlCharset )
 *
 *              SECTION: Count special chars
 * 1171:     private function count_specialChars( )
 * 1210:     private function count_specialChars_addSum( $row )
 * 1260:     private function count_specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset )
 * 1351:     private function count_specialChars_setSqlFindInSet( $row )
 * 1377:     private function count_specialChars_setSqlLength( )
 *
 *              SECTION: SQL charset
 * 1449:     private function sqlCharsetGet( )
 * 1482:     private function sqlCharsetSet( $sqlCharset )
 *
 *              SECTION: Downward compatibility
 * 1522:     private function getMarkerIndexbrowser( )
 * 1568:     private function getMarkerIndexbrowserTabs( )
 *
 *              SECTION: Helper
 * 1626:     private function zz_specCharsToASCII( $string )
 * 1646:     private function zz_tabClass( $lastTabId, $tab, $key )
 * 1679:     private function zz_tabDefaultLabel( )
 * 1697:     private function zz_tabDefaultLink( )
 * 1740:     private function zz_tabLinkLabel( $tab )
 * 1782:     private function zz_setTabPiVars( $labelAscii, $label )
 * 1812:     private function zz_setTabPiVarsDefaultTab( $label )
 * 1844:     private function zz_setTabSlected( $tabId )
 * 1894:     private function zz_tabLastId( )
 * 1926:     private function zz_tabTitle( $sum )
 *
 * TOTAL FUNCTIONS: 35
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_pageBrowser
{

    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = false;
    // [Array] The current TypoScript configuration array
  var $mode       = false;
    // [Integer] The current mode (from modeselector)
  var $view       = false;
    // [String] 'list' or 'single': The current view
  var $conf_view  = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_path  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
    // Variables set by the pObj (by class.tx_browser_pi1.php)









 /**
  * Constructor. The method initiate the parent object
  *
  * @param	object		The parent object
  * @return	void
  * @version  3.9.9
  * @since    3.9.9
  */
  public function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;
      // 111023, uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
    $this->t3lib_cs_obj = t3lib_div::makeInstance('t3lib_cs');
  }









    /***********************************************
    *
    * Main
    *
    **********************************************/



/**
 * get( ): Get the index browser. It has to replace the subpart in the current content.
 *
 * @param	string		$content: current content
 * @return	array
 * @version 3.9.12
 * @since   3.9.9
 */
  public function get( $content )
  {
    $this->content                  = $content;
    $arr_return['data']['content']  = $content;

    return $arr_return;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_pageBrowser.php']);
}

?>