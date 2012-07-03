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
 *   58: class tx_browser_pi1_navi_modeSelector
 *   93:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  120:     public function get( $content )
 *  156:     public function get_tabs( $content )
 *
 *              SECTION: Init
 *  247:     public function prepaireModeSelector()
 *
 *              SECTION: Templating
 *  319:     public function tmplModeSelector($arr_data)
 *
 * TOTAL FUNCTIONS: 5
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
    $arr_return = null;

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
    $arr_return = null;
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






    /***********************************************
    *
    * Init
    *
    **********************************************/



    /**
 * Prepaire an array for the mode selector. Allocate a value to $this->piVar_mode.
 *
 * @return	array		Array with the modeSelector names
 */
  public function prepaireModeSelector()
  {

    $arr_return = array();
    $arr_return['error']['status'] = false;



      ///////////////////////////////////////////////
      //
      // RETURN with an error, if there are no views

    if( ! is_array( $this->conf['views.'] ) )
    {
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_typoscript_h1').'</h1>';
      $str_prompt  = '<p style="color:red; font-weight:bold;">'.$this->pObj->pi_getLL('error_views_noview').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // RETURN with an error, if there are no views



      ///////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    $langKey = $GLOBALS['TSFE']->lang;
    if( $langKey == 'en' )
    {
      $langKey = 'default';
    }

    foreach( array_keys( (array) $this->conf['views.'][$this->view . '.'] ) as $keyView )
    {
      // We don't need the typoscript array dot
      $mode                       = substr( $keyView, 0, strlen( $keyView ) - 1 );
      $llMode                     = $this->pObj->pi_getLL( $this->view.'_mode_' . $mode, $mode    );
      $arr_return['data'][$mode]  = $this->pObj->pi_getLL( $this->view.'_mode_' . $mode, $llMode  );
      if ( $this->pObj->b_drs_localisation && $mode == $llMode )
      {
        t3lib_div::devlog( '[WARN/LOCALLANG] ' . $this->conf_path . ' hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
        $prompt = 'Please configure _LOCAL_LANG.'.$langKey.'.'.$this->view.'_mode_'.$mode.'.';
        t3lib_div::devlog('[HELP/LOCALLANG] '.$prompt, $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System

    return $arr_return;
  }






    /***********************************************
    *
    * Templating
    *
    **********************************************/



 /**
  * Building the mode selector HTML code.
  *
  * @param	array		Array with the template and the mode selector tabs
  * @return	string		template
  */
  public function tmplModeSelector($arr_data)
  {

    $template     = $arr_data['template'];
    $arr_items    = $arr_data['arrModeItems'];
    $markerArray  = null;



      /////////////////////////////////////
      //
      // Without items don't display any tabs

    if (count($arr_items) <= 1) {
        // We don't have a mode selector
      $template = $this->pObj->cObj->substituteSubpart($template, '###MODESELECTOR###', '', true);
      if ($this->pObj->b_drs_navi) {
        t3lib_div::devlog('[INFO/NAVIGATION] RETURN. There isn\'t any item for the mode selector.', $this->pObj->extKey, 0);
      }
      return $template;
    }



      /////////////////////////////////////
      //
      // Building the tabs

    reset($arr_items);
    $i_max_counter  = count($arr_items);
    $i_counter      = 0;
    while (list($str_item_key, $str_item_value) = each($arr_items))
    {
      $tabClass         = ($i_counter < ($i_max_counter - 1)) ? 'tab-'.$i_counter : 'tab-'.$i_counter.' last';
        // 110825, dwildt-
      //$class            = $this->mode == $str_item_key ? ' class="'.$tabClass.' selected"' : ' class="'.$tabClass.'"';
        // 110825, dwildt+
      switch( true )
      {
        case( $this->mode == $str_item_key ) :
          $class                                  = ' class="'.$tabClass.' selected"';
          $markerArray['###UI-STATE-ACTIVE###']   = ' ui-state-active';
          $markerArray['###UI-TABS-SELECTED###']  = ' ui-tabs-selected';
          break;
        default:
          $class                                  = ' class="'.$tabClass.'"';
          $markerArray['###UI-STATE-ACTIVE###']   = null;
          $markerArray['###UI-TABS-SELECTED###']  = null;
      }
      $class            = $this->mode == $str_item_key ? ' class="'.$tabClass.' selected"' : ' class="'.$tabClass.'"';
        // 110825, dwildt+
      $str_item_value   = htmlspecialchars($str_item_value);
      if ($this->conf['navigation.']['modeSelector.']['wrap'] != '') {
        $str_item_value = str_replace('|', $str_item_value, $this->conf['navigation.']['modeSelector.']['wrap']);
      }
      $item             = $this->pObj->pi_linkTP_keepPIvars($str_item_value, array('mode' => $str_item_key), $this->pObj->boolCache);
      $markerArray['###CLASS###'] = $class;
      $markerArray['###TABS###']  = $item;
      $markerArray['###MODE###']  = $str_item_key;
      $modeSelectorTabs           = $this->pObj->cObj->getSubpart($template, '###MODESELECTORTABS###');
      $tabs                      .= $this->pObj->cObj->substituteMarkerArray($modeSelectorTabs, $markerArray);
      $i_counter++;
    }
    unset($markerArray);
      // Building the tabs



      /////////////////////////////////////
      //
      // Building and Return the template

    $markerArray               = $this->pObj->objWrapper->constant_markers();
    $markerArray['###MODE###'] = $this->mode;
    $markerArray['###VIEW###'] = $this->view;
    $modeSelector = $this->pObj->cObj->getSubpart($template, '###MODESELECTOR###');
    $modeSelector = $this->pObj->cObj->substituteMarkerArray($modeSelector, $markerArray);
    $modeSelector = $this->pObj->cObj->substituteSubpart($modeSelector, '###MODESELECTORTABS###', $tabs,          true);
    $template     = $this->pObj->cObj->substituteSubpart($template,     '###MODESELECTOR###',     $modeSelector,  true);

//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->piVars);
//      die( );
//    }
    return $template;
      // Building and Return the template

  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_modeSelector.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_modeSelector.php']);
}

?>
