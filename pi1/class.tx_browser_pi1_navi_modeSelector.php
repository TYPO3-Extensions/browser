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
 * The class tx_browser_pi1_navi_modeSelector bundles methods for navigation like the Index-Browser
 * or the page broser. It is part of the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    browser
 * @version       3.9.12
 * @since 2.0.0
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   52: class tx_browser_pi1_navi_modeSelector
 *   87:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  117:     public function get( $content )
 *  153:     public function get_tabs( $content )
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_modeSelector
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
  * @version  3.7.3
  * @since    2.0.0
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
  * get( ): Building the mode selector HTML code.
  *
  * @param	[type]		$$content: ...
  * @return	string		template
  */
  public function get( $content )
  {

      // RETURN : there is one mode only
    if( count ( $this->pObj->arrModeItems ) <= 1 )
    {
      if ($this->pObj->b_drs_navi)
      {
        $prompt = 'RETURN. There isn\'t any item for the mode selector (one mode only).';
        t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
      }
      $arr_return['data']['content'] = null;
      return $arr_return;
    }
      // RETURN : there is one mode only


      // Get the tabs
    $arr_return = $this->get_tabs( $content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the tabs

    return $arr_return;
  }



 /**
  * get_tabs( ): Building the mode selector HTML code.
  *
  * @param	[type]		$$content: ...
  * @return	string		template
  */
  public function get_tabs( $content )
  {
    $arrModes   = $this->pObj->arrModeItems;

      // Will return the content
    $tabs = null;

      // Init counting of modes (tabs)
    $iModeMax   = count( $arrModes ) - 1;
    $iModeCurr  = 0;

      // LOOP modes
    foreach( (array ) $arrModes as $modeKey => $modeLabel )
    {
        // Set class
      $tabClass = 'tab-' . $iModeCurr;

        // Add 'last' to class
      if( $iModeCurr >= $iModeMax )
      {
        $tabClass = $tabClass . ' last';
      }
        // Add 'last' to class

        // SWITCH : set class and markers depending on selected
      switch( true )
      {
        case( $this->mode == $modeKey ) :
          $class                                  = ' class="' . $tabClass . ' selected"';
          $markerArray['###UI-STATE-ACTIVE###']   = ' ui-state-active';
          $markerArray['###UI-TABS-SELECTED###']  = ' ui-tabs-selected';
          break;
        default:
          $class                                  = ' class="' . $tabClass . '"';
          $markerArray['###UI-STATE-ACTIVE###']   = null;
          $markerArray['###UI-TABS-SELECTED###']  = null;
      }
        // SWITCH : set class depending on selected

        // Make label HTML proper
      $modeLabel = htmlspecialchars( $modeLabel );

        // Wrap the label
      if( ! empty( $this->conf['navigation.']['modeSelector.']['wrap'] ) )
      {
        $wrap       = $this->conf['navigation.']['modeSelector.']['wrap'];
        $modeLabel  = str_replace('|', $modeLabel, $wrap);
      }
        // Wrap the label

        // Link the label
      $item = $this->pObj->pi_linkTP_keepPIvars( $modeLabel, array('mode' => $modeKey), $this->pObj->boolCache );

        // Set the marker array
      $markerArray['###CLASS###'] = $class;
      $markerArray['###TABS###']  = $item;
      $markerArray['###MODE###']  = $modeKey;

        // Substitute the subpart
      $subpart = $this->pObj->cObj->getSubpart( $content, '###MODESELECTORTABS###');
      $tabs    = $tabs . $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );

        // Update the mode counter
      $iModeCurr++;
    }
      // LOOP modes


      // RETURN the content
    $arr_return['data']['content'] = $tabs;
    return $arr_return;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_modeSelector.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_modeSelector.php']);
}

?>
