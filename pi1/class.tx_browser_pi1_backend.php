<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_backend bundles methods for backend support like itemsProcFunc
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage    browser
 * @version 4.0.0
 * @since 3.0.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   63: class tx_browser_pi1_backend
 *
 *              SECTION: Sheets
 *  115:     public function evaluate_externalLinks($arr_pluginConf, $obj_TCEform)
 *  155:     public function evaluate_plugin($arr_pluginConf, $obj_TCEform)
 *  404:     public function extend_calendar($arr_pluginConf, $obj_TCEform)
 *  575:     public function sDef_getArrViewsList($arr_pluginConf)
 *  739:     public function socialmedia_getArrBookmarks($arr_pluginConf)
 *  800:     public function templating_getArrDataQuery($arr_pluginConf)
 *  865:     public function templating_getExtensionTemplates($arr_pluginConf)
 *  923:     public function templating_get_jquery_ui($arr_pluginConf)
 *
 *              SECTION: Helper Methods
 *  983:     private function getLL()
 * 1018:     private function init($arr_pluginConf)
 * 1060:     private function init_pageObj($arr_pluginConf)
 * 1092:     private function init_pageUid($arr_pluginConf)
 * 1142:     private function init_tsObj($arr_rows_of_all_pages_inRootLine)
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_backend
{




    // [Integer] Pid of the current page
  var $pid  = null;
    // [Object] Current t3-page object
  var $obj_page = null;
    // [Object] TypoScript object of current page
  var $obj_TypoScript = null;
    // [Array] one dimensional array with language strings
  var $locallang = null;

  var $maxWidth = '600px';









  /***********************************************
   *
   * Sheets
   *
   **********************************************/











  /**
 * evaluate_externalLinks: HTML content with external links
 *
 * Tab [evaluate]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt
 * @version 4.0.0
 * @since 4.0.0
 */
  public function evaluate_externalLinks($arr_pluginConf, $obj_TCEform)
  {
      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error
    $str_prompt = null;

    $str_prompt = $str_prompt.'
      <div class="message-body" style="max-width:600px;">
        ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptExternalLinksBody'). '
      </div>
      ';

    return $str_prompt;
  }











  /**
 * evaluate_plugin: Evaluates the plugin, flexform, TypoScript
 *                  Returns a HTML report
 *
 * Tab [evaluate]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt
 * @version 4.0.0
 * @since 4.0.0
 */
  public function evaluate_plugin($arr_pluginConf, $obj_TCEform)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);

      // RETURN error with init()
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error
    $str_prompt = null;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // General information

      // INFO: Link to the tutorial and to the browser forum
    $str_prompt_info_tutorialAndForum = '
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.drs') . '
        </div>
      </div>
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.updateAssistent') . '
        </div>
      </div>
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.tutorialAndForum') . '
        </div>
      </div>
      ';
      // General information



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Check the plugin

      // RETURN plugin isn't never saved

    if( empty ( $arr_pluginConf['row']['pi_flexform'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.saved_never') . '
          </div>
        </div>
        <div class="typo3-message message-information">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.saved_never') . '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // RETURN plugin isn't never saved

      // RETURN TypoScript static template isn't included
    if( !is_array ( $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_ts_template') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_ts_template') . '
          </div>
        </div>
        ';
      return $str_prompt . $str_prompt_info_tutorialAndForum;
    }
      // RETURN TypoScript static template isn't included

      // RETURN There isn't any view configured
    if( !is_array ( $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_view') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_view') . '
          </div>
        </div>
        ';
      return $str_prompt . $str_prompt_info_tutorialAndForum;
    }
      // RETURN There isn't any view configured

      // RETURN There isn't any record storage page
    if( empty ( $arr_pluginConf['row']['pages'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.warn.no_record_storage_pid') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_record_storage_pid') . '
          </div>
        </div>
        ';
      return $str_prompt . $str_prompt_info_tutorialAndForum;
    }
      // RETURN There isn't any record storage page

      // RETURN There isn't any AJAX page object
      // Is AJAX enabled? AJAX page object II
    $bool_AJAXenabled = false;
    //var_dump(__METHOD__, __LINE__, $arr_pluginConf['row']['pi_flexform']);
    $arr_xml = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
    //var_dump(__METHOD__, __LINE__, '$arr_xml', $arr_xml);
    $record_browser = $arr_xml['data']['viewSingle']['lDEF']['record_browser']['vDEF'];

    //var_dump(__METHOD__, __LINE__, '$record_browser', $record_browser);
    switch ($record_browser)
    {
      case ('disabled') :
        $bool_AJAXenabled = false;
        break;
      case ('by_flexform') :
        $bool_AJAXenabled = true;
        break;
      case ('ts') :
      default :
        $bool_AJAXenabled = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['navigation.']['record_browser'];
        break;
    }
      // Is AJAX enabled? AJAX page object II

      // AJAX is enabled. AJAX page object II
    //var_dump(__METHOD__, __LINE__, '$bool_AJAXenabled', $bool_AJAXenabled);
    if( $bool_AJAXenabled )
    {
        // RETURN there isn't any default typeNum of AJAX page object
      //var_dump(__METHOD__, __LINE__, $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['javascript.']['ajax.']['jQuery.']['default.']['typeNum']);
      if( !isset ($this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['javascript.']['ajax.']['jQuery.']['default.']['typeNum']))
      {
        $str_prompt = '
          <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_AJAX_defaultTypeNum') . '
            </div>
          </div>
          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_AJAX_defaultTypeNum') . '
            </div>
          </div>
          ';
        return $str_prompt . $str_prompt_info_tutorialAndForum;
      }
        // RETURN there isn't any default typeNum of AJAX page object

        // RETURN there is no AJAX page object
      $AJAX_defaultTypeNum    = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['javascript.']['ajax.']['jQuery.']['default.']['typeNum'];
      $AJAX_nameOfPageObject  = $this->obj_TypoScript->setup['types.'][$AJAX_defaultTypeNum];
        // There is no AJAX page object
      //var_dump(__METHOD__, __LINE__, '$AJAX_nameOfPageObject', $AJAX_nameOfPageObject);
      if( empty( $AJAX_nameOfPageObject ) )
      {
        $str_prompt = '
          <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_AJAXpageObject') . '
            </div>
          </div>
          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_AJAXpageObject') . '
            </div>
          </div>
          ';
        $str_prompt = str_replace( '%typeNum%', $AJAX_defaultTypeNum, $str_prompt);
        return $str_prompt . $str_prompt_info_tutorialAndForum;
      }
        // RETURN there is no AJAX page object
    }
      // AJAX is enabled. AJAX page object II

      // RETURN There isn't any CSV page object
      // Is CSV export enabled?
    $bool_CSVenabled  = false;
    $arr_xml          = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
    $csvexport        = $arr_xml['data']['viewList']['lDEF']['csvexport']['vDEF'];

    switch ( $csvexport )
    {
      case ( 'enabled' ) :
        $bool_CSVenabled = true;
        break;
      case ('ts') :
        $bool_CSVenabled = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['viewList.']['csvexport.']['stdWrap.']['value'];
        break;
      case ( 'disabled' ) :
      default :
        $bool_CSVenabled = false;
        break;
    }
      // Is CSV export enabled?

      // CSV export is enabled.
    if( $bool_CSVenabled )
    {
        // RETURN there isn't any default typeNum of CSV export page object
      if( ! isset ($this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['export.']['csv.']['page.']['typeNum']))
      {
        $str_prompt = '
          <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_CSV_defaultTypeNum') . '
            </div>
          </div>
          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_CSV_defaultTypeNum') . '
            </div>
          </div>
          ';
        return $str_prompt . $str_prompt_info_tutorialAndForum;
      }
        // RETURN there isn't any default typeNum of CSV export page object

        // RETURN there is no CSV export page object
      $CSV_defaultTypeNum    = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['export.']['csv.']['page.']['typeNum'];
      $CSV_nameOfPageObject  = $this->obj_TypoScript->setup['types.'][$CSV_defaultTypeNum];
        // There is no CSV export page object
      if( empty( $CSV_nameOfPageObject ) )
      {
        $str_prompt = '
          <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.error.no_CSVpageObject') . '
            </div>
          </div>
          <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.info.no_CSVpageObject') . '
            </div>
          </div>
          ';
        $str_prompt = str_replace( '%typeNum%', $CSV_defaultTypeNum, $str_prompt);
        return $str_prompt . $str_prompt_info_tutorialAndForum;
      }
        // RETURN there is no CSV export page object
    }
      // CSV export is enabled.

      // Evaluation result: default message in case of success
    $str_prompt = '
      <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.ok') . '
        </div>
      </div>
      ';
      // Evaluation result: default message in case of success

      // DRS is enabled
    $arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser']);
    if ($arr_extConf['drs_mode'] != 'Don\'t log anything')
    {
      $str_prompt = $str_prompt . '
        <div class="typo3-message message-warning" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_evaluate.plugin.drs.warn') . '
          </div>
        </div>
        ';
      $str_prompt = str_replace( '%status%', $arr_extConf['drs_mode'], $str_prompt );
    }
      // DRS is enabled


      // Check the plugin
    return $str_prompt . $str_prompt_info_tutorialAndForum;
  }









  /**
 * extend_cal_ui: Renders a TCE form select box with calendar plugins.
 *                Three cases will be handled:
 *                1. There isn't any calendar plugin available:
 *                   * returns a prompt only
 *                2. Thera are calendar plugins available, but no one isn't selected:
 *                   * returns a prompt with a select box
 *                3. Thera are calendar plugins available and one is selected:
 *                   * returns a select box with a prompt
 *
 * Tab [extend]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt or HTML prompt and TCE select form with calendar plugins
 * @version 4.0.0
 * @since 4.0.0
 */
  public function extend_cal_ui($arr_pluginConf, $obj_TCEform)
  {
      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error

    $arr_items  = null;
    $str_prompt = null;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Reset session data

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    $arr_session['sheets']['extend']['cal_ui']['eval'] = false;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Reset session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // SQL query: Get all browser_pi5 plugins of the current page.

    $pid            = (int) $arr_pluginConf['row']['pid'];
    $select_fields  = 'uid, header';
    $from_table     = 'tt_content';
    $where_clause   = "pid = " . $pid . " AND CType = 'list' AND list_type = 'browser_pi5' AND hidden = 0 AND deleted = 0";
    //echo $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='');
      // SQL query: Get all browser_pi5 plugins of the current page.



      ///////////////////////////////////////////////////////////////////////////////
      //
      // SQL query: Execute it. Allocate items with values from the SQL result

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='');

      // The default first item
    $value          = 0;
    $label          = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_ui.select.firstItem');
    $arr_items[]    = '<option value="' . $value . '%selected%">' . $label . '</option>';
      // The default first item

      // LOOP rows of the SQL result
    $bool_selected  = false;
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
    {
      $selected = null;
        // Current row is selected
      if($row['uid'] == htmlspecialchars($arr_pluginConf['itemFormElValue']))
      {
        $bool_selected  = true;
        $selected       = ' selected="selected"';
      }
        // Current row is selected

        // Render the item
      $value        = $row['uid'];
      $label        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_ui.select.prependItem') . ': ' . htmlspecialchars($row['header']) . ' (' . $row['uid'] . ')';
      $arr_items[]  = '<option value="' . $value . '"'. $selected . '>' . $label . '</option>';
        // Render the item
    }
      // LOOP rows of the SQL result

      // Set default firstItem selected or not
    if($bool_selected) {
      $arr_items[0] = str_replace('%selected%', null, $arr_items[0]);
    }
    if(!$bool_selected) {
      $arr_items[0] = str_replace('%selected%', ' selected="selected"', $arr_items[0]);
    }
    $items = implode("\n" . '          ', (array) $arr_items);
      // Set default firstItem selected or not

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result($res);
      // SQL query: Execute it. Allocate items with values from the SQL result



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any plugin Browser Calendar on this page

    if( count($arr_items) < 2)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_ui.info.info') . '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // RETURN there isn't any plugin Browser Calendar on this page



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Render the select box (TCE form)

    $formField = '
      <div class="t3-form-field t3-form-field-flex">
        <input type="hidden" name="' . $arr_pluginConf['itemFormElName'] . '_selIconVal" value="1" />
        <select
          id        = "tceforms-select-tx-browser-pi1-extend-cal-ui"
          name      = "' . $arr_pluginConf['itemFormElName'] . '"
          class     = "select"
          size      = "1"
          onchange  = "if (this.options[this.selectedIndex].value==\'--div--\') {this.selectedIndex=1;} ' . htmlspecialchars(implode('', $arr_pluginConf['fieldChangeFunc'])) . 'if (confirm(TBE_EDITOR.labels.onChangeAlert) &amp;&amp; TBE_EDITOR.checkSubmit(-1)){ TBE_EDITOR.submitForm() };">
          ' . $items . '
        </select>
      </div>
      ';
      // Render the select box (TCE form)



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN no plugin is selected

    if(!$bool_selected)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_ui.select.info') . '
          </div>
        </div>
        ';
      $str_prompt = $str_prompt . $formField;
      return $str_prompt;
    }
      // RETURN no plugin is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A cal_ui plugin is selected

//    if( !$arr_session['sheets']['extend']['cal_view']['eval'])
//    {
//      $str_prompt = $str_prompt.'
//        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//          <div class="message-body" style="max-width:600px;">
//            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_ui.success.info') . '
//          </div>
//        </div>
//        ';
//    }
    $str_prompt = $formField . $str_prompt;
      // A cal_ui plugin is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set session data

    $arr_session['sheets']['extend']['cal_ui']['eval'] = true;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Set session data



        ///////////////////////////////////////////////////////////////////////////////
        //
        // RETURN the select box (TCE form)

      return $str_prompt;
        // RETURN the select box (TCE form)
  }








  /**
 * extend_cal_view: Renders a TCE form select box with available views.
 *                  Three cases will be handled:
 *                  1. There isn't any view available:
 *                     * returns a prompt only
 *                  2. Thera are views available, but no one isn't selected:
 *                     * returns a prompt with a select box
 *                  3. Thera are views available and one is selected:
 *                     * returns a select box with a prompt
 *
 * Tab [extend]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt or HTML prompt and TCE select form with calendar plugins
 * @version 4.0.0
 * @since 4.0.0
 */
  public function extend_cal_view($arr_pluginConf, $obj_TCEform)
  {
      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error



      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }



    $arr_items  = null;
    $str_prompt = null;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Reset session data

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    $arr_session['sheets']['extend']['cal_view']['eval'] = false;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Reset session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any plugin Browser Calendar selected

      // Get current browser calendar plugin
    $arr_xml    = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
      // Bugfix     #29732, uherrmann, 110913
    $int_plugin = (is_array($arr_xml)) ? $arr_xml['data']['extend']['lDEF']['cal_ui']['vDEF'] : '';
      // Get current browser calendar plugin

    if( empty( $int_plugin ) )
    {
      return null;
    }
      // RETURN there isn't any plugin Browser Calendar selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: session data cal_ui eval is false

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    if( !$arr_session['sheets']['extend']['cal_ui']['eval'] )
    {
      return null;
    }
      // RETURN: session data cal_ui eval is false



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A plugin Browser Calendar is selected

      // Get current listviews
    $arr_xml        = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
    $str_views_csv  = $arr_xml['data']['sDEF']['lDEF']['viewsList']['vDEF'];
    $arr_views_csv  = explode(',', $str_views_csv);
      // Get current listviews

      // The default first item
    $arr_items    = null;
    $value        = 0;
    $label        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.select.firstItem');
    $arr_items[]  = '<option value="' . $value . '%selected%">' . $label . '</option>';
      // The default first item

      // LOOP views
    $bool_selected  = false;
    foreach( $arr_views_csv as $key => $arr_view)
    {
      list( $value ) = explode('|', $arr_view);
      
      if( empty( $value ) )
      {
        continue;
      }

      $selected = null;

        // Current view is selected
      if($value == $arr_pluginConf['itemFormElValue'])
      {
        $bool_selected  = true;
        $selected       = ' selected="selected"';
      }
        // Current view is selected

        // Render the item
      $label = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.']['list.'][$value . '.']['name'];
      if( empty ( $label ) )
      {
        $label = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.']['list.'][$value];
      }
      if( empty ( $label ) )
      {
        $label = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.select.no_name');
      }
      $label        = $value . ' (' . $label . ')';
      $label        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.select.prependItem') . ': ' . $label;
      $arr_items[]  = '<option value="' . $value . '"'. $selected . '>' . $label . '</option>';
        // Render the item
    }
      // LOOP views

      // Set default firstItem selected or not
    if($bool_selected) {
      $arr_items[0] = str_replace('%selected%', null, $arr_items[0]);
    }
    if(!$bool_selected) {
      $arr_items[0] = str_replace('%selected%', ' selected="selected"', $arr_items[0]);
    }
    $items = implode("\n" . '          ', (array) $arr_items);
      // Set default firstItem selected or not
      // LOOP views



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any view available

    if( count($arr_items) < 2)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.error') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.info') . '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // RETURN there isn't any view available



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Render the select box (TCE form)

    $formField = '
      <div class="t3-form-field t3-form-field-flex">
        <input type="hidden" name="' . $arr_pluginConf['itemFormElName'] . '_selIconVal" value="1" />
        <select
          id        = "tceforms-select-tx-browser-pi1-extend-cal-view"
          name      = "' . $arr_pluginConf['itemFormElName'] . '"
          class     = "select"
          size      = "1"
          onchange  = "if (this.options[this.selectedIndex].value==\'--div--\') {this.selectedIndex=1;} ' . htmlspecialchars(implode('', $arr_pluginConf['fieldChangeFunc'])) . 'if (confirm(TBE_EDITOR.labels.onChangeAlert) &amp;&amp; TBE_EDITOR.checkSubmit(-1)){ TBE_EDITOR.submitForm() };">
          ' . $items . '
        </select>
      </div>
      ';
      // Render the select box (TCE form)



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN no view is selected

    if(!$bool_selected)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.select.info') . '
          </div>
        </div>
        ';
      $str_prompt = $str_prompt . $formField;
      return $str_prompt;
    }
      // RETURN no plugin is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A view is selected

//    if( !$arr_session['sheets']['extend']['cal_field_start']['eval'])
//    {
//      $str_prompt = $str_prompt.'
//        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//          <div class="message-body" style="max-width:600px;">
//            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_view.success.info') . '
//          </div>
//        </div>
//        ';
//    }
    $str_prompt = $formField . $str_prompt;
      // A view is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set session data

    $arr_session['sheets']['extend']['cal_view']['eval'] = true;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Set session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN the select box (TCE form)

      return $str_prompt;
      // RETURN the select box (TCE form)
  }








  /**
 * extend_cal_field_start:  Renders a TCE form select box with available fields.
 *                    Three cases will be handled:
 *                    1. There isn't any field available:
 *                       * returns a prompt only
 *                    2. Thera are fields available, but no one isn't selected:
 *                       * returns a prompt with a select box
 *                    3. Thera are fields available and one is selected:
 *                       * returns a select box with a prompt
 *
 * Tab [extend]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt or HTML prompt and TCE select form with calendar plugins
 * @version 4.0.0
 * @since 4.0.0
 */
  public function extend_cal_field_start($arr_pluginConf, $obj_TCEform)
  {
      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error



      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
//      var_dump( __METHOD__, __LINE__, 'RETURN', $bool_success);
      return $arr_pluginConf;
    }



    $arr_items  = null;
    $str_prompt = null;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Reset session data

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    $arr_session['sheets']['extend']['cal_field_start']['eval'] = false;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Reset session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any view selected

      // Get current view
    $arr_xml  = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
      // Bugfix     #29732, uherrmann, 110913
    $str_view = (is_array($arr_xml)) ? $arr_xml['data']['extend']['lDEF']['cal_view']['vDEF'] : '';
      // Get current view

    if( empty( $str_view ) )
    {
//      var_dump( __METHOD__, __LINE__, 'RETURN', $str_view);
      return null;
    }
      // RETURN there isn't any view selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: session data cal_view eval is false

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    if( !$arr_session['sheets']['extend']['cal_view']['eval'] )
    {
//      var_dump( __METHOD__, __LINE__, 'RETURN', $arr_session);
      return null;
    }
      // RETURN: session data cal_view eval is false



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A view is selected

      // Get fields
    $str_fields_csv = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.']['list.'][$str_view . '.']['select'];
    $str_fields_csv = str_replace(' ',  null, $str_fields_csv);
    $str_fields_csv = str_replace("\n", null, $str_fields_csv);
    $str_fields_csv = str_replace("\l", null, $str_fields_csv);
    $str_fields_csv = str_replace("\r", null, $str_fields_csv);
    $arr_fields_csv = explode(',', $str_fields_csv);
      // Get fields

      // The default first item
    $arr_items    = null;
    $value        = 0;
    $label        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_start.select.firstItem');
    $arr_items[]  = '<option value="' . $value . '%selected%">' . $label . '</option>';
      // The default first item

      // LOOP fields
    $bool_selected  = false;
    foreach( $arr_fields_csv as $tableField)
    {
      if( empty( $tableField ) )
      {
        continue;
      }

      list( $table, $field ) = explode('.', $tableField );
      
        // TCA eval value
      if (!is_array($GLOBALS['TCA'][$table]['columns']))
      {
        t3lib_div::loadTCA($table);
      }
      $eval           = $GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'];
      $bool_timestamp = false;
      switch( true )
      {
        case( !( strpos( $eval, 'date' ) === false ) ):
          $bool_timestamp = true;
          break;
        case( !( strpos( $eval, 'time' ) === false ) ):
          $bool_timestamp = true;
          break;
        case( !( strpos( $eval, 'year' ) === false ) ):
          $bool_timestamp = true;
          break;
      }
      if( !$bool_timestamp )
      {
        continue;
      }
        // TCA eval value
      
      $selected = null;
        // Current field is selected
      if($tableField == $arr_pluginConf['itemFormElValue'])
      {
        $bool_selected  = true;
        $selected       = ' selected="selected"';
      }
        // Current field is selected

        // Render the item
      $value  = $tableField;
      $label  = $tableField;
      $arr_items[]  = '<option value="' . $value . '"'. $selected . '>' . $label . '</option>';
        // Render the item
    }
      // LOOP fields

      // Set default firstItem selected or not
    if($bool_selected) {
      $arr_items[0] = str_replace('%selected%', null, $arr_items[0]);
    }
    if(!$bool_selected) {
      $arr_items[0] = str_replace('%selected%', ' selected="selected"', $arr_items[0]);
    }
    $items = implode("\n" . '          ', (array) $arr_items);
      // Set default firstItem selected or not
      // LOOP views



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any field available

    if( count($arr_items) < 2)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_start.error') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_start.info') . '
          </div>
        </div>
        ';
      $str_prompt = str_replace( '%view%', $str_view, $str_prompt );
      return $str_prompt;
    }
      // RETURN there isn't any view available



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Render the select box (TCE form)

    $formField = '
      <div class="t3-form-field t3-form-field-flex">
        <input type="hidden" name="' . $arr_pluginConf['itemFormElName'] . '_selIconVal" value="1" />
        <select
          id        = "tceforms-select-tx-browser-pi1-extend-cal-field-start"
          name      = "' . $arr_pluginConf['itemFormElName'] . '"
          class     = "select"
          size      = "1"
          onchange  = "if (this.options[this.selectedIndex].value==\'--div--\') {this.selectedIndex=1;} ' . htmlspecialchars(implode('', $arr_pluginConf['fieldChangeFunc'])) . 'if (confirm(TBE_EDITOR.labels.onChangeAlert) &amp;&amp; TBE_EDITOR.checkSubmit(-1)){ TBE_EDITOR.submitForm() };">
          ' . $items . '
        </select>
      </div>
      ';
      // Render the select box (TCE form)



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN no view is selected

    if(!$bool_selected)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_start.select.info') . '
          </div>
        </div>
        ';
      $str_prompt = $str_prompt . $formField;
      return $str_prompt;
    }
      // RETURN no plugin is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A view is selected

//    if( !$arr_session['sheets']['extend']['cal_field_end']['eval'])
//    {
//      $str_prompt = $str_prompt.'
//        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
//          <div class="message-body" style="max-width:600px;">
//            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_start.success.info') . '
//          </div>
//        </div>
//        ';
//    }
    $str_prompt = $formField . $str_prompt;
      // A view is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set session data

    $arr_session['sheets']['extend']['cal_field_start']['eval'] = true;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Set session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN the select box (TCE form)

      return $str_prompt;
      // RETURN the select box (TCE form)
  }








  /**
 * extend_cal_field_end:  Renders a TCE form select box with available fields.
 *                    Three cases will be handled:
 *                    1. There isn't any field available:
 *                       * returns a prompt only
 *                    2. Thera are fields available, but no one isn't selected:
 *                       * returns a prompt with a select box
 *                    3. Thera are fields available and one is selected:
 *                       * returns a select box with a prompt
 *
 * Tab [extend]
 *
 * @param array   $arr_pluginConf:  Current plugin/flexform configuration
 * @param array   $obj_TCEform:     Current TCE form object
 * @return  string    $str_prompt: HTML prompt or HTML prompt and TCE select form with calendar plugins
 * @version 4.0.0
 * @since 4.0.0
 */
  public function extend_cal_field_end($arr_pluginConf, $obj_TCEform)
  {
      //.message-notice
      //.message-information
      //.message-ok
      //.message-warning
      //.message-error



      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }



    $arr_items  = null;
    $str_prompt = null;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Reset session data

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    $arr_session['sheets']['extend']['cal_field_end']['eval'] = false;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Reset session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any field start selected

      // Get current view
    $arr_xml          = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
      // Bugfix     #29732, uherrmann, 110913
    $str_field_start  = (is_array($arr_xml)) ? $arr_xml['data']['extend']['lDEF']['cal_field_start']['vDEF'] : '';
      // Get current view

    if( empty( $str_field_start ) )
    {
      return null;
    }
      // RETURN there isn't any field start selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: session data cal_field_start eval is false

      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_pi5');
      // (Re)set cal_ui eval to false
    if( !$arr_session['sheets']['extend']['cal_field_start']['eval'] )
    {
      return null;
    }
      // RETURN: session data cal_field_start eval is false



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A view is selected

      // Get current view
    $arr_xml  = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
    $str_view = $arr_xml['data']['extend']['lDEF']['cal_view']['vDEF'];

      // Get fields
    $str_fields_csv = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.']['list.'][$str_view . '.']['select'];
    $str_fields_csv = str_replace(' ',  null, $str_fields_csv);
    $str_fields_csv = str_replace("\n", null, $str_fields_csv);
    $str_fields_csv = str_replace("\l", null, $str_fields_csv);
    $str_fields_csv = str_replace("\r", null, $str_fields_csv);
    $arr_fields_csv = explode(',', $str_fields_csv);
      // Get fields

      // The default first item
    $arr_items    = null;
    $value        = 0;
    $label        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_end.select.firstItem');
    $arr_items[]  = '<option value="' . $value . '%selected%">' . $label . '</option>';
      // The default first item

      // LOOP fields
    $bool_selected  = false;
    foreach( $arr_fields_csv as $tableField)
    {
      if( empty( $tableField ) )
      {
        continue;
      }

      list( $table, $field ) = explode('.', $tableField );
      
        // TCA eval value
      if (!is_array($GLOBALS['TCA'][$table]['columns']))
      {
        t3lib_div::loadTCA($table);
      }
      $eval           = $GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'];
      $bool_timestamp = false;
      switch( true )
      {
        case( !( strpos( $eval, 'date' ) === false ) ):
          $bool_timestamp = true;
          break;
        case( !( strpos( $eval, 'time' ) === false ) ):
          $bool_timestamp = true;
          break;
        case( !( strpos( $eval, 'year' ) === false ) ):
          $bool_timestamp = true;
          break;
      }
      if( !$bool_timestamp )
      {
        continue;
      }
        // TCA eval value
      
      $selected = null;
        // Current field is selected
      if($tableField == $arr_pluginConf['itemFormElValue'])
      {
        $bool_selected  = true;
        $selected       = ' selected="selected"';
      }
        // Current field is selected

        // Render the item
      $value  = $tableField;
      $label  = $tableField;
      $arr_items[]  = '<option value="' . $value . '"'. $selected . '>' . $label . '</option>';
        // Render the item
    }
      // LOOP fields

      // Set default firstItem selected or not
    if($bool_selected) {
      $arr_items[0] = str_replace('%selected%', null, $arr_items[0]);
    }
    if(!$bool_selected) {
      $arr_items[0] = str_replace('%selected%', ' selected="selected"', $arr_items[0]);
    }
    $items = implode("\n" . '          ', (array) $arr_items);
      // Set default firstItem selected or not
      // LOOP views



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any field available

    if( count($arr_items) < 2)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-error" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_end.error') . '
          </div>
        </div>
        <div class="typo3-message message-information" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_end.info') . '
          </div>
        </div>
        ';
      $str_prompt = str_replace( '%view%', $str_view, $str_prompt );
      return $str_prompt;
    }
      // RETURN there isn't any view available



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Render the select box (TCE form)

    $formField = '
      <div class="t3-form-field t3-form-field-flex">
        <input type="hidden" name="' . $arr_pluginConf['itemFormElName'] . '_selIconVal" value="1" />
        <select
          id        = "tceforms-select-tx-browser-pi1-extend-cal-field-end"
          name      = "' . $arr_pluginConf['itemFormElName'] . '"
          class     = "select"
          size      = "1"
          onchange  = "if (this.options[this.selectedIndex].value==\'--div--\') {this.selectedIndex=1;} ' . htmlspecialchars(implode('', $arr_pluginConf['fieldChangeFunc'])) . 'if (confirm(TBE_EDITOR.labels.onChangeAlert) &amp;&amp; TBE_EDITOR.checkSubmit(-1)){ TBE_EDITOR.submitForm() };">
          ' . $items . '
        </select>
      </div>
      ';
      // Render the select box (TCE form)



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN no view is selected

    if(!$bool_selected)
    {
      $str_prompt = $str_prompt.'
        <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
          <div class="message-body" style="max-width:600px;">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_end.select.info') . '
          </div>
        </div>
        ';
      $str_prompt = $str_prompt . $formField;
      return $str_prompt;
    }
      // RETURN no plugin is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // A view is selected

    $str_prompt = $str_prompt.'
      <div class="typo3-message message-ok" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body" style="max-width:600px;">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi1/flexform_locallang.php:sheet_extend.cal_field_end.success.ok') . '
        </div>
      </div>
      ';
    $str_prompt = $formField . $str_prompt;
      // A view is selected



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set session data

    $arr_session['sheets']['extend']['cal_field_end']['eval'] = true;
    $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_pi5', array( 'sheets' => $arr_session['sheets']));
      // Set session data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN the select box (TCE form)

      return $str_prompt;
      // RETURN the select box (TCE form)
  }










  /**
 * sDef_getArrViewsList: Get data query (and andWhere) for all list views of the current plugin.
 * Tab [General/sDEF]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the names of the views list
 * @version 3.6.1
 * @since 3.6.1
 */
  public function sDef_getArrViewsList($arr_pluginConf)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }


      ///////////////////////////////////////////////////////////////////////////////
      //
      // Get Flexform

    $arr_views  = array();
    $arr_xml    = t3lib_div::xml2array($arr_pluginConf['row']['pi_flexform'],$NSprefix='',$reportDocTag=false);
      // Get Flexform



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Should views displayed only, if they are linked with the current template?

    $bool_viewsHandleFromTemplateOnly = $arr_xml['data']['sDEF']['lDEF']['viewsHandleFromTemplateOnly']['vDEF'];
    if($bool_viewsHandleFromTemplateOnly == null)
    {
      $bool_viewsHandleFromTemplateOnly = true;
    }
      // Should views displayed only, if they are linked with the current template?



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Get key of the current template

    if($bool_viewsHandleFromTemplateOnly)
    {
      $str_pathToTmplFile = $arr_xml['data']['templating']['lDEF']['template']['vDEF'];
      //var_dump('backend 125', $str_pathToTmplFile);
    }



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Get list of views, which are linked with the current template

    if($bool_viewsHandleFromTemplateOnly)
    {
        // The list
      $arr_extensions = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['template.']['extensions.'];
      if (is_array($arr_extensions) && count($arr_extensions))
      {
          // Loop through all extensions and templates
        foreach((array) $arr_extensions as $extensionWiDot => $arr_templates)
        {
          $extension = substr($extensionWiDot, 0, strlen($extensionWiDot) - 1);
          foreach((array) $arr_templates as $arr_template)
          {
            if($arr_template['file'] == $str_pathToTmplFile)
            {
              $csvViews     = str_replace(' ', null, trim($csvViews));
              $csvViews     = $arr_template['csvViews'];
                // #27358, uherrmann, 110610
            ##$arr_csvViews = explode(',', $csvViews);
              $arr_csvViews = t3lib_div::trimExplode(',', $csvViews);
                // #27358, uherrmann, 110610
              $arr_views    = array_merge($arr_views, $arr_csvViews);
            }
          }
        }
          // Loop through all extensions and templates
      }
        // The list
    }
      // Get list of views, which are linked with the current template



      ///////////////////////////////////////////////////////////////////////////////
      //
      // TypoScript configuration for list views

    $arr_listviews = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['views.']['list.'];

      // #11981, 110106, dwildt
      // Remove any value, keep arrays
    foreach((array) $arr_listviews as $key => $view)
    {
      if(substr($key, -1, 1) != '.')
      {
        unset($arr_listviews[$key]);
      }
    }
      // #11981, 110106, dwildt

      // Loop through all listviews
    if (is_array($arr_listviews) && count($arr_listviews))
    {
      foreach((array) $arr_listviews as $key_listview => $arr_listview)
      {
        $key_listview = strtolower(substr($key_listview, 0, -1));
        $bool_handleCurrList = true;
        if(count($arr_views) >= 1)
        {
          if(!in_array($key_listview, $arr_views))
          {
            $bool_handleCurrList = false;
          }
        }
        if($bool_handleCurrList)
        {
          if($arr_listview['name'])
          {
            $str_dataQuery_name = $key_listview.': '.$arr_listview['name'];
          }
          if(!$arr_listview['name'])
          {
            $str_dataQuery_name = $key_listview.': no name';
          }
          $arr_pluginConf['items'][] = array($str_dataQuery_name, $key_listview);
          $arr_sort[] = $key_listview;
        }
      }
    }
      // Loop through all listviews

      // Order listviews
    if(!empty($arr_pluginConf['items']))
    {
      array_multisort($arr_sort, $arr_pluginConf['items']);
    }
      // Order listviews

      // We don't have any item
    if(empty($arr_pluginConf['items']))
    {
      $arr_pluginConf['items'][] = array('Any list view isn\'t available!', '');
      $arr_pluginConf['items'][] = array('Did you added a Static Template?', '');
      $arr_pluginConf['items'][] = array('Did you configured a view?', '');
    }
      // We don't have any item

    return $arr_pluginConf;
  }









  /**
 * socialmedia_getArrBookmarks: Get bookmarks for flexform. Tab [Socialmedia]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the bookmarks
 * @version 3.6.1
 * @since 3.6.1
 */
  public function socialmedia_getArrBookmarks($arr_pluginConf)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      // TypoScript configuration for bookmarks
    $arr_bookmarks = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['socialmedia.']['socialbookmarks.']['bookmarks.']['items.'];

      // Loop: bookmarks
    if (is_array($arr_bookmarks) && count($arr_bookmarks))
    {
      foreach((array) $arr_bookmarks as $key_bookmark => $arr_bookmark)
      {
        $key_bookmark = strtolower(substr($key_bookmark, 0, -1));
        if($arr_bookmark['name'])
        {
          $str_bookmark_name = $arr_bookmark['name'];
        }
        if(!$arr_bookmark['name'])
        {
          $str_bookmark_name = $key_bookmark;
        }
        $arr_pluginConf['items'][] = array($str_bookmark_name, $key_bookmark);
        $arr_sort[] = $key_bookmark;
      }
    }
      // Loop: bookmarks

      // Order bookmarks
    if(!empty($arr_pluginConf['items']))
    {
      array_multisort($arr_sort, $arr_pluginConf['items']);
    }
      // Order bookmarks

    return $arr_pluginConf;
  }











  /**
 * templating_getArrDataQuery: Get data query (and andWhere) for all list views of the current plugin.
 * Tab [Templating]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the bookmarks
 * @version 3.6.1
 * @since 3.6.1
 */
  public function templating_getArrDataQuery($arr_pluginConf)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      // TypoScript configuration for dataQueries
    $arr_dataQuery = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['templating.']['arrDataQuery.']['items.'];

      // Loop through all dataQuerys
    if (is_array($arr_dataQuery) && count($arr_dataQuery))
    {
      foreach((array) $arr_dataQuery as $key_dataQuery => $arr_dataQuery)
      {
        // First item should be an empty value
        // #9695, 100912
        $arr_pluginConf['items'][] = array('', '');

        $key_dataQuery = strtolower(substr($key_dataQuery, 0, -1));
        if($arr_dataQuery['name'])
        {
          $str_dataQuery_name = $arr_dataQuery['name'];
        }
        if(!$arr_dataQuery['name'])
        {
          $str_dataQuery_name = 'ERROR: plugin.templating.arrDataQuery.'.$key_dataQuery.'.name is missing!';
        }
        $arr_pluginConf['items'][] = array($str_dataQuery_name, $key_dataQuery);
      }
    }
      // Loop through all dataQuerys

      // We don't have any item
    if(empty($arr_pluginConf['items']))
    {
      $str_defaultItem           = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['templating.']['arrDataQuery.']['config.']['ifNoItem.']['value'];
      $arr_pluginConf['items'][] = array($str_defaultItem, '1');
    }
      // We don't have any item

    return $arr_pluginConf;
  }











  /**
 * templating_getExtensionTemplates: Get templates from the browser and third party extensions
 * Tab [Templating]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   $arr_pluginConf: Extended with the templates
 * @version 3.6.1
 * @since 3.6.1
 */
  public function templating_getExtensionTemplates($arr_pluginConf)
  {
      // Default value
    $arr_pluginConf['items'][] = array('From TypoScript (old fashion)', 'typoscript');
    $arr_pluginConf['items'][] = array('Upload own Template', 'adjusted');
    $arr_pluginConf['items'][] = array('-------------------------------------------', '');


      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      // TypoScript configuration for extension templates
    $arr_extensions = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['template.']['extensions.'];

    if (!(is_array($arr_extensions) && count($arr_extensions)))
    {
      return $arr_pluginConf;
    }

      // Loop through all extensions and templates
    foreach((array) $arr_extensions as $extensionWiDot => $arr_templates)
    {
      $extension = substr($extensionWiDot, 0, strlen($extensionWiDot) - 1);
      foreach((array) $arr_templates as $arr_template)
      {
//var_dump($extensionWiDot, $arr_template['name'], $arr_template['file']);
        $label = $arr_template['name'].' ('.$extension.')';
        $value = $arr_template['file'];
        $arr_pluginConf['items'][] = array($label, $value);
      }
    }
      // Loop through all extensions and templates

    return $arr_pluginConf;

  }









  /**
 * templating_get_jquery_ui: Get the list of jquery uis for the flexform. Tab [Templating]
 *                            * Feature #28562
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the uis
 * @version 3.7.0
 * @since 3.7.0
 */
  public function templating_get_jquery_ui($arr_pluginConf)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      // Init the one dimensional language array
    $this->getLL();

      // TypoScript configuration for jquery_ui
    $arr_jquery_uis = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['templating.']['jquery_ui.'];

      // Loop: jquery_ui
    foreach((array) $arr_jquery_uis as $key_jquery_ui => $arr_jquery_ui)
    {
      $jquery_ui_key    = strtolower(substr($key_jquery_ui, 0, -1));
      $jquery_ui_label  = $this->locallang[$arr_jquery_ui['label']];
      $jquery_ui_icon   = $arr_jquery_ui['icon'];

      $arr_pluginConf['items'][] = array($jquery_ui_label, $jquery_ui_key, $jquery_ui_icon);
    }
      // Loop: jquery_ui

    return $arr_pluginConf;
  }













  /***********************************************
   *
   * Helper Methods
   *
   **********************************************/









  /**
 * getLL(): Get the locallang for class use out of an XML file
 *
 * @return  array   Array of the locallang data
 */
  private function getLL()
  {
    $arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser']);
    switch($arr_extConf['LLstatic'])
    {
      case('German'):
        $lang = 'de';
        break;
      default:
        $lang = 'default';
    }
    require_once('flexform_locallang.php');
    $this->locallang = $LOCAL_LANG[$lang];
  }













  /**
 * init(): Initiate this class.
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   TRUE: success. FALSE: error.
 * @since 3.4.5
 * @version 3.4.5
 */
  private function init($arr_pluginConf)
  {
      // Require classes
    require_once(PATH_t3lib.'class.t3lib_page.php');
    require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
    require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');

      // Init page id and the page object
    $this->init_pageUid($arr_pluginConf);
    $this->init_pageObj($arr_pluginConf);

      // Init agregrated TypoScript
    $arr_rows_of_all_pages_inRootLine = $this->obj_page->getRootLine($this->pid);
    if (empty($arr_rows_of_all_pages_inRootLine))
    {
      return false;
    }
    $this->init_tsObj($arr_rows_of_all_pages_inRootLine);

    $this->init = true;
    return true;
  }












  /**
 * init_pageObj(): Initiate an page object.
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  private function init_pageObj($arr_pluginConf)
  {
    if(!empty($this->obj_page))
    {
      return false;
    }

      // Set current page object
    $this->obj_page = t3lib_div::makeInstance('t3lib_pageSelect');

    return false;
  }












  /**
 * init_pageUid(): Initiate the page uid.
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  private function init_pageUid($arr_pluginConf)
  {
    if(!empty($this->pid))
    {
      return false;
    }

      // Update: Get current page id from the plugin
    $int_pid = false;
    if($arr_pluginConf['row']['pid'] > 0)
    {
      $int_pid = $arr_pluginConf['row']['pid'];
    }
      // Update: Get current page id from the plugin

      // New: Get current page id from the current URL
    if(!$int_pid)
    {
        // Get backend URL - something like .../alt_doc.php?returnUrl=db_list.php&id%3D2926%26table%3D%26imagemode%3D1&edit[tt_content][1734]=edit
      $str_url    = $_GET['returnUrl'];
        // Get curent page id
      $int_pid = intval(substr($str_url, strpos($str_url, 'id=')+3));
    }
      // New: Get current page id from the current URL

      // Set current page id
    $this->pid      = $int_pid;

    return false;
  }












  /**
 * init_tsObj(): Initiate the TypoScript of the current page.
 *
 * @param array   $arr_rows_of_all_pages_inRootLine: Agregate the TypoScript of all pages in the rootline
 * @return  boolean   FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  private function init_tsObj($arr_rows_of_all_pages_inRootLine)
  {
    if(!empty($this->obj_TypoScript))
    {
      return false;
    }

    $this->obj_TypoScript = t3lib_div::makeInstance('t3lib_tsparser_ext');
    $this->obj_TypoScript->tt_track = 0;
    $this->obj_TypoScript->init();
    $this->obj_TypoScript->runThroughTemplates($arr_rows_of_all_pages_inRootLine);
    $this->obj_TypoScript->generateConfig();

    return false;
  }















}







if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_backend.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_backend.php']);
}
?>