<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_template bundles template methods for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 4.1.9
 * @since 1.0.0
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   69: class tx_browser_pi1_template
 *  133:     function __construct($parentObj)
 *
 *              SECTION: Rendering HTML
 *  164:     function tmplSearchBox($template, $display)
 *  407:     function resultphrase()
 *  602:     function tmplListview($template, $rows)
 * 1377:     function tmplSingleview($template, $rows)
 * 1903:     function tmplTableHead($template)
 * 2549:     function tmplRows($elements, $subpart, $template)
 * 3349:     private function tmpl_marker( )
 * 3379:     private function tmpl_rmFields( )
 *
 *              SECTION: GroupBy
 * 3419:     function groupBy_verify($template)
 * 3496:     function groupBy_remove($template)
 * 3519:     function groupBy_get_groupname($elements)
 * 3544:     function groupBy_stdWrap($elements)
 *
 *              SECTION: Handle As
 * 3635:     function render_handleAs($elements, $handleAs, $markerArray)
 * 3795:     function hook_template_elements()
 * 3813:     function hook_template_elements_transformed()
 *
 * TOTAL FUNCTIONS: 16
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_template
{


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
    // [Booelan] If true, workflow will executed in case of empty rows too
  var $ignore_empty_rows_rule = false;
    // Variables set by the pObj (by class.tx_browser_pi1.php)



    //////////////////////////////////////////////////////
    //
    // Variables set by this class

    // [Array]Array with the fields of the SQL result
  var $arr_select;
    // [Array] Array with fields from orderBy from TS
  var $arr_orderBy;
    // [Array] Array with fields from functions.clean_up.csvTableFields from TS
  var $arr_rmFields   = null;
    // [Array] Local or global TypoScript array with the displaySingle properties
  var $lDisplaySingle;
    // [Array] Local or global TypoScript array with the displayList properties
  var $lDisplayList;
    // [array] Array with default markers
  var $markerArray    = null;
    // [Integer] Amount of elements, which should dislayed
  var $max_elements   = null;
    // [string] HTML class for odd columns (th, td)
  var $oddClassColumns  = null;
    // [string] HTML class for odd rows (tr)
  var $oddClassRows   = null;
    // [Boolean] true, if rows should grouped, false, if rows shouldn't grouped
  var $bool_groupby;
    // [Array] Array [table.field] = $value.
    // It is needed by social media bookmarks in a default single view.
  var $arr_curr_value = false;
    // 3.4.0
    // Variables set by this class





/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }










    /***********************************************
    *
    * Rendering HTML
    *
    **********************************************/



/**
 * Building the searchbox as a form.
 *
 * @param	string		$template: The current template part
 * @return	string		$template: The HTML template part
 * @version 4.1.9
 * @since 1.0.0
 */
  function tmplSearchBox( $template )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];

    $display = $this->pObj->objFlexform->bool_searchForm && $this->pObj->segment['searchform'];


      //////////////////////////////////////////////////////////
      //
      // RETURN searchform shouldn't displayed

    if (!$display)
    {
      $template = $this->pObj->cObj->substituteSubpart($template, '###SEARCHFORM###', '', true);
      return $template;
    }
      // RETURN searchform shouldn't displayed



      //////////////////////////////////////////////////////////
      //
      // action without filters and sword

    $arr_currPiVars  = $this->pObj->piVars;

      // Remove pointer temporarily
    $pageBrowserPointerLabel = $this->conf['navigation.']['pageBrowser.']['pointer'];
    $arr_removePiVars = array('sword', 'sort', $pageBrowserPointerLabel);
        // #11576, dwildt, 101219
//$this->pObj->dev_var_dump( $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin, $this->pObj->piVars );
    if( ! $this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin )
    {
      $arr_removePiVars[] = 'plugin';
    }
    foreach((array) $arr_removePiVars as $str_removePiVars)
    {
      if(isset($this->pObj->piVars[$str_removePiVars]))
      {
        unset($this->pObj->piVars[$str_removePiVars]);
      }
    }
      // Remove pointer temporarily

      // Move $GLOBALS['TSFE']->id temporarily
      // #9458
    $int_tsfeId = $GLOBALS['TSFE']->id;
    if (!empty($this->pObj->objFlexform->int_viewsListPid))
    {
      $GLOBALS['TSFE']->id = $this->pObj->objFlexform->int_viewsListPid;
    }
      // Move $GLOBALS['TSFE']->id temporarily

      // Remove the filter fields temporarily
      // #9495, fsander

      // #11580, dwildt, 101219
    if(is_array($conf_view['displayList.']['display.']['searchform.']['respect_filters.']))
    {
      $conf_respect_filters = $conf_view['displayList.']['display.']['searchform.']['respect_filters.'];
    }
    if(!is_array($conf_view['displayList.']['display.']['searchform.']['respect_filters.']))
    {
      $conf_respect_filters = $conf['displayList.']['display.']['searchform.']['respect_filters.'];
    }
    if($conf_respect_filters['all'])
    {
      // Don't remove any filter ...
    }
    if(!$conf_respect_filters['all'])
    {
      // Remove all but ...
      if(is_array($conf_respect_filters['but.']))
      {
        $conf_filter = $conf_view['filter.'];
        foreach((array) $conf_respect_filters['but.'] as $tableWiDot => $arr_fields)
        {
          foreach((array) $arr_fields as $field_key => $field_value)
          {
            if($field_value)
            {
              unset($conf_filter[$tableWiDot][$field_key]);
            }
          }
        }
      }
      $this->pObj->piVars = $this->pObj->objZz->removeFiltersFromPiVars($this->pObj->piVars, $conf_filter);
    }
      // #11580, dwildt, 101219
      // Remove the filter fields temporarily

    $clearAnyway  = 0;
    $altPageId    = 0;
    $str_action   = $this->pObj->pi_linkTP_keepPIvars_url($this->pObj->piVars, $this->pObj->boolCache,$clearAnyway, $altPageId);

      // Recover piVars
      // #9495, fsander
    $this->pObj->piVars = $arr_currPiVars;
      // Recover piVars


      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray = $this->markerArray;
      // 110829, dwildt+
      // 110829, dwildt-
//    $markerArray                  = $this->pObj->objWrapper->constant_markers();
    $markerArray['###ACTION###']  = $str_action;
    $str_sword                    = stripslashes($this->pObj->piVars['sword']);
    $str_sword                    = htmlspecialchars($str_sword);
    $str_sword_default            = $this->pObj->pi_getLL('label_sword_default', 'Search Word', true);
    $str_sword_default            = htmlspecialchars($str_sword_default);
    if(!$str_sword)
    {
      $str_sword = $str_sword_default;
      if ($this->pObj->b_drs_localisation || $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/LANG+TEMPLATING] Empty Sword becomes the default value: \''.$str_sword.'\'.', $this->pObj->extKey, 0);
        $langKey = $GLOBALS['TSFE']->lang;
        if ($langKey == 'en')
        {
          $langKey = 'default';
        }
        t3lib_div::devLog('[HELP/LANG+TEMPLATING] Configure it? See _LOCAL_LANG.'.$langKey.'.label_sword_default', $this->pObj->extKey, 1);
      }
    }
    $markerArray['###SWORD###']         = $str_sword;
    $markerArray['###SWORD_DEFAULT###'] = $str_sword_default;
    $markerArray['###BUTTON###']        = $this->pObj->pi_getLL('pi_list_searchBox_search', 'Search', true);
    $markerArray['###POINTER###']       = $this->pObj->prefixId.'[pointer]';
      // 110110, cweiske, #11886
    $markerArray['###FLEXFORM###']      = $this->pObj->piVars['plugin'];
      // 120916, dwildt, 1+
    $markerArray['###PLUGIN###']        = $this->pObj->piVars['plugin'];
    $markerArray['###MODE###']          = $this->pObj->piVar_mode;
    $markerArray['###VIEW###']          = $this->pObj->view;
    $markerArray['###RESULTPHRASE###']  = $this->resultphrase();

    $str_hidden = null;
    foreach( ( array ) $this->pObj->piVars as $key => $values )
    {
      $piVar_key = $this->pObj->prefixId.'['.$key.']';
      if( is_array( $values ) )
      {
        foreach( ( array ) $values as $value )
        {
          if( $value != null )
          {
            $str_hidden = $str_hidden . PHP_EOL .
                          $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $value . '">';
          }
        }
      }
      if( ! is_array( $values ) && ! ( $values == null ) )
      {
        $str_hidden = $str_hidden . PHP_EOL .
                      $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $values . '">';
      }
    }
    $markerArray['###HIDDEN###']  = $str_hidden;
//$this->pObj->dev_var_dump( $markerArray['###HIDDEN###'] );

    $subpart    = $this->pObj->cObj->getSubpart($template, '###SEARCHFORM###');
    // 3.5.0: We need the subpartmarker for the filter again
    $searchBox  = '<!-- ###SEARCHFORM### begin -->
        '.$this->pObj->cObj->substituteMarkerArray($subpart, $markerArray).'
<!-- ###SEARCHFORM### end -->';



      //////////////////////////////////////////////////////////////////////
      //
      // csv export: remove the csv export button

      // #29370, 110831, dwildt+
    if( ! $this->pObj->objFlexform->sheet_viewList_csvexport )
    {
      $searchBox = $this->pObj->cObj->substituteSubpart($searchBox, '###BUTTON_CSV-EXPORT###', null, true);
    }
      // #29370, 110831, dwildt+
      // #00000, 120126, dwildt+
    if( $this->pObj->objFlexform->sheet_viewList_csvexport )
    {
      $templateCSV = $this->pObj->cObj->getSubpart($searchBox, '###BUTTON_CSV-EXPORT###');
      if( empty( $templateCSV ) )
      {
        $prompt = '<div style="border:1em solid orange;padding:1em;text-align:center;">
            <h1>
              TYPO3 Browser Warning
            </h1>
            <h2>
              EN: Subpart is missing
            </h2>
            <p>
              English: You enabled the CSV export in the plugin/flexform.<br />
              But the current HTML template doesn\'t contain the subpart ###BUTTON_CSV-EXPORT###.<br />
              Please take care of a proper template and add the subpart.<br />
              See example in res/html/default.templ.<br />
            </p>
            <h2>
              DE: Subpart fehlt
            </h2>
            <p>
              Deutsch: Du hast im Plugin/in der Flexform den CSV-Export aktiviert.<br />
              Aber das aktuelle HTML-Template hat keinen Subpart ###BUTTON_CSV-EXPORT###.<br />
              Bitte k&uuml;mmere Dich um ein korrektes Template und f&uuml;ge den Subpart hinzu.<br />
              Ein Beispiel findest Du in der Datei: res/html/default.tmpl<br />
            </p>
          </div>';
      }
      $template = $prompt . $template;
    }
      // #00000, 120126, dwildt+

    $template   = $this->pObj->cObj->substituteSubpart($template, '###SEARCHFORM###', $searchBox, true);
      // csv export: remove the csv export button

    $this->pObj->piVars  = $arr_currPiVars;
    $GLOBALS['TSFE']->id = $int_tsfeId; // #9458
    // action without filters and sword

    return $template;
  }









/**
 * Building the result phrase for the search form.
 *
 * @return	string		Rendered rusult phrase
 * @version   4.1.9
 * @since     2.0.0
 */
  function resultphrase()
  {
    /**
     * This method correspondends with tx_browser_pi1_zz::color_swords($tableField, $str_content)
     */

    $lSearchform = $this->pObj->lDisplay['searchform.'];



    ///////////////////////////////////////////////////////////////
    //
    // RETURN in case of any swords

    if (!is_array($this->pObj->arr_swordPhrases))
    {
      return false;
    }
    // RETURN in case of any swords



    ///////////////////////////////////////////////////////////////
    //
    // Set variables

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];

    $conf_phrase     = $lSearchform['resultPhrase.'];
    $conf_searchFor  = $lSearchform['resultPhrase.']['searchFor.'];
    $str_searchFor   = $this->pObj->objWrapper->general_stdWrap($conf_searchFor['value'], $conf_searchFor);
      // 120915, dwildt, 1-
    //$conf_and        = $lSearchform['resultPhrase.']['searchFor.']['and.'];
      // 120915, dwildt, 1-
    //$str_and         = $this->pObj->objWrapper->general_stdWrap($conf_and['value'], $conf_and);

    $conf_minLen     = $lSearchform['resultPhrase.'];
    $bool_wrapSwords = $this->pObj->objFlexform->bool_searchForm_wiColoredSwords;
    $key_according   = 0;
    $arr_confWrap    = $lSearchform['wrapSwordInResults.'];
    $max_key         = count($arr_confWrap) - 1;
    // Set variables



    ///////////////////////////////////////////////////////////////
    //
    // Get global or local array advanced

    #10116
    $arr_conf_advanced = $conf['advanced.'];
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }
    // Get global or local array advanced



    // Char for Wildcard
    $chr_wildcard     = $this->pObj->str_searchWildcardCharManual;
    $arr_colored      = array();
      // 120915, dwildt, 1+
    $arrWrappedSwords = null;
    foreach((array) $this->pObj->arr_resultphrase['arr_marker'] as $key => $value)
    {
      $value = stripslashes($value);
      if ($bool_wrapSwords)
      {
        // Wildcards are used by default
        if(!$this->pObj->bool_searchWildcardsManual)
        {
          $str_wrapped_value = $this->pObj->objWrapper->general_stdWrap($value, $arr_confWrap[$key_according.'.']);
        }
        // Wildcards are used by default

        // The user has to add a wildcard
        if($this->pObj->bool_searchWildcardsManual)
        {
          $valueWildcard = $value;
          // First char of search word is a wildcard
          if($valueWildcard[0] == $chr_wildcard)
          {
            $valueWildcard = substr($valueWildcard, 1, strlen($valueWildcard) - 1);
          }
          // First char of search word is a wildcard
          // Last char of search word is a wildcard
          if($valueWildcard[strlen($valueWildcard) - 1] == $chr_wildcard)
          {
            $valueWildcard = substr($valueWildcard, 0, -1);
          }
          // Last char of search word is a wildcard
          $str_wrapped_value = $this->pObj->objWrapper->general_stdWrap($valueWildcard, $arr_confWrap[$key_according.'.']);
        }
        // The user has to add a wildcard

        $arr_colored[$key] = $str_wrapped_value;
      }
//if(t3lib_div::_GP('dev')) var_dump('template 332', $arr_colored);
      if (!$bool_wrapSwords)
      {
        $str_wrapped_value = $value;
      }
      $arrWrappedSwords[$key] = $str_wrapped_value;
      if ($key_according <= $max_key)
      {
        $key_according++;
      }
      if ($key_according > $max_key)
      {
        $key_according = 0;
      }
    }
    $str_swords = $this->pObj->arr_resultphrase['str_mask'];
    foreach( ( array ) $arrWrappedSwords as $key => $value )
    {
      $str_swords = str_replace($key, $value, $str_swords);
    }
    $this->pObj->arr_resultphrase['arr_colored'] = $arr_colored;
// 3.3.4
//if(t3lib_div::_GP('dev')) var_dump('template 354', $this->pObj->arr_resultphrase);

    $conf_hasResult = $lSearchform['resultPhrase.']['hasResult.'];
    $str_hasResult  = $lSearchform['resultPhrase.']['hasResult.']['value'];
    $str_hasResult  = $this->pObj->objWrapper->general_stdWrap($str_hasResult, $conf_hasResult);
    $str_minLen     = false;
    $bool_minLen    = $lSearchform['resultPhrase.']['minLenPhrase'];
    if($bool_minLen)
    {
      $conf_minLen    = $lSearchform['resultPhrase.']['minLenPhrase.'];
      $str_minLen     = $lSearchform['resultPhrase.']['minLenPhrase.']['value'];
      $str_minLen     = $this->pObj->objWrapper->general_stdWrap($str_minLen, $conf_minLen);
      #10116
      $str_minLen     = str_replace('###advanced.security.sword.minLenWord###', $arr_conf_advanced['security.']['sword.']['minLenWord'], $str_minLen);
    }
    $bool_operator    = $lSearchform['resultPhrase.']['operatorPhrase'];
    $str_operator     = false;
    if($bool_operator)
    {
      $conf_operator    = $lSearchform['resultPhrase.']['operatorPhrase.'];
      $str_operator     = $lSearchform['resultPhrase.']['operatorPhrase.']['value'];
      $str_operator     = $this->pObj->objWrapper->general_stdWrap($str_operator, $conf_operator);
    }
    $str_wildcard = false;
    if($this->pObj->bool_searchWildcardsManual)
    {
      $conf_wildcard    = $lSearchform['resultPhrase.']['wildcardPhrase.'];
      $str_wildcard     = $lSearchform['resultPhrase.']['wildcardPhrase.']['value'];
      $str_wildcard     = $this->pObj->objWrapper->general_stdWrap($str_wildcard, $conf_wildcard);
      $str_wildcard     = str_replace('%wildcard%', $this->pObj->str_searchWildcardCharManual, $str_wildcard);
    }
    $str_phrase     = $str_searchFor.' '.$str_swords.' '.$str_hasResult.' '.$str_minLen.$str_operator.$str_wildcard;
    $str_phrase     = $this->pObj->objWrapper->general_stdWrap($str_phrase, $conf_phrase);

    if ($this->pObj->b_drs_search)
    {
      t3lib_div::devlog('[INFO/SEARCH] Result phrase: \''.$str_phrase.'\'', $this->pObj->extKey, 0);
    }



    ///////////////////////////////////////////////////////////////
    //
    // RETURN false, in case of TypoScript: Don't display resultphrase'

    if (!$this->pObj->objFlexform->bool_searchForm_wiPhrase)
    {
      return false;
    }
    // RETURN false, in case of TypoScript: Don't display resultphrase'



    return $str_phrase;
  }









/**
 * Building the table with the result in the list view
 *
 * @param	string		A HTML template with the TYPO3 subparts and markers
 * @param	array		Array with the records of the SQL result
 * @return	void
 * @version 4.1.9
 * @since 1.0.0
 */
  function tmplListview($template, $rows)
  {
      ///////////////////////////////////////////////////////////
      //
      // Get the local or the global displayList array

    $lDisplayList = $this->conf_view['displayList.'];
    if (!is_array($lDisplayList))
    {
      $lDisplayList = $this->pObj->conf['displayList.'];
    }
    $this->lDisplayList = $lDisplayList;
      // Get the local or the global displayList array




      ////////////////////////////////////////////////////////
      //
      // Set the groupby mode and get a proper template

    $template = $this->groupBy_verify($template);
      // Set the groupby mode and get a proper template



      // Set the global arr_rmFields
    $this->tmpl_rmFields( );



      //////////////////////////////////////////////////////////////////////
      //
      // Remove ###LIST_TITLE### in case of AJAX single view

      // #9659, 101013, dwildt
    if($this->pObj->segment['header'] == false)
    {
      if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] tx_browser_pi1[segment] has a value. AJAX call single view with list view.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] AJAX: Do not handle the list title!', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] AJAX: Subpart ###LIST_TITLE### is removed.', $this->pObj->extKey, 0);
      }
      //var_dump('templ 486', $this->pObj->segment, $template);
      $template = $this->pObj->cObj->substituteSubpart($template, '###LIST_TITLE###', null, true);
      //var_dump('templ 488', $template);
    }
      // Remove my_title in case of AJAX



      ////////////////////////////////////////////////////////
      //
      // Replace mode and view in the whole template

      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray = $this->markerArray;
      // 110829, dwildt+
      // 110829, dwildt-
//    $template = str_replace('###MODE###', $this->pObj->piVar_mode, $template);
//    $template = str_replace('###VIEW###', $this->pObj->view, $template);
      // 110829, dwildt-
      // Replace mode an view in the whole template



      ////////////////////////////////////////////////////////
      //
      // First time on the site?


    if( $this->pObj->boolFirstVisit )
    {
      $bool_emptyList = $this->pObj->objFlexform->bool_emptyAtStart;
      if($bool_emptyList)
      {                               
          // 3.9.24, 120604, dwildt-
//        $conf_emptyList = $lDisplayList['emptyListByStart.']['stdWrap.'];
          // 3.9.24, 120604, dwildt+
        $conf_emptyList = $lDisplayList['display.']['emptyListByStart.']['stdWrap.'];
        if ($this->pObj->b_drs_templating || $this->pObj->b_drs_flexform)
        {
          $langKey = $GLOBALS['TSFE']->lang;
          if ($langKey == 'en')
          {
            $langKey = 'default';
          }
          t3lib_div::devLog('[INFO/FLEXFORM + TEMPLATING] It is the first call for the plugin. The SQL result is replaced with a message.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/FLEXFORM + TEMPLATING] If you want a SQL result instead of the message, please disable
            in the Browser flexform tab [list view] field [empty list at start]<br />
            <br />
            If you want another label, please configure:<br />
            _LOCAL_LANG.'.$langKey.'.label_first_visit', $this->pObj->extKey, 1);
        }
        $str_emptyList  = $this->pObj->objWrapper->general_stdWrap('', $conf_emptyList);

        $template       = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $str_emptyList, true);
          // 110829, dwildt+
        $this->tmpl_marker( );
        // 110829, dwildt-
//        $markerArray    = $this->pObj->objWrapper->constant_markers();
        $template       = $this->pObj->cObj->substituteMarkerArray($template, $markerArray);
        return $template;
      }
      if(!$bool_emptyList)
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] It is the first call for the plugin. The SQL result is replaced with a message.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] If you want to display a list, please configure:<br />
            displayList.display.emptyListByStart = 0', $this->pObj->extKey, 1);
        }
      }
    }
      // First time on the site?



      ////////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After \'First time?\': '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
        // DRS - Performance



      /////////////////////////////////////
      //
      // RETURN: Without rows no listview table

    // 110823, dwildt
    // if (count($rows) == 0 || !is_array($rows))
    $this->updateWizard( 'displayList.noItemMessage', $lDisplayList );
    if ( empty ( $rows ) )
    {
      if( $this->ignore_empty_rows_rule )
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] Rows are empty and ignore_empty_rows_rule is true. Workflow will executed.', $this->pObj->extKey, 0);
        }
      }
      if( ! $this->ignore_empty_rows_rule )
      {
          // #37731, 120604, dwildt, +
        $cObj_name = $lDisplayList['noItemMessage'];
        if( $cObj_name == '1' )
        {
          $cObj_name = 'TEXT';
        }
        $cObj_conf      = $lDisplayList['noItemMessage.'];
        $noItemMessage  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        $template       = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $noItemMessage, true);
          // #37731, 120604, dwildt, +
        
          // 3.9.24, 120604, dwildt, -
//        if ($this->pObj->conf['displayList.']['noItemMessage'])
//        {
//          $noItemMessage = $this->pObj->objWrapper->general_stdWrap('X', $this->pObj->conf['displayList.']['noItemMessage.']);
//          $template = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $noItemMessage, true);
//          if ($this->pObj->b_drs_templating)
//          {
//            t3lib_div::devlog('[INFO/TEMPLATING] Returned Template is the noItemMessage.', $this->pObj->extKey, 0);
//            t3lib_div::devLog('[HELP/TEMPLATING] Change it? Configure '.$this->conf_path.'.displayList.noItemMessage.', $this->pObj->extKey, 1);
//          }
//        }
//        if (!$this->pObj->conf['displayList.']['noItemMessage'])
//        {
//          $template = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', '', true);
//        }
          // 3.9.24, 120604, dwildt, -
          // 110829, dwildt+
        $this->tmpl_marker( );
          // 110829, dwildt-
        //$markerArray    = $this->pObj->objWrapper->constant_markers();

          /////////////////////////////////////
          //
          // Workaround

          // In case of ###LISTHEADITEM### is part of ###SEARCHFORM###
        $markerArray['###ITEM###']  = false;
          // Workaround

        $template       = $this->pObj->cObj->substituteMarkerArray($template, $markerArray);
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/TEMPLATING] There isn\'t any row.', $this->pObj->extKey, 2);
        }
        return $template;
      }
    }
      // RETURN: Without rows no listview table



      //////////////////////////////////////////////////////////////////
      //
      // Init the global array $arrHandleAs

    $this->pObj->objTca->setArrHandleAs();
    $this->pObj->rows = $rows;
      // Init the global array $arrHandleAs


      //////////////////////////////////////////////////////////////////
      //
      // Get oddClasses

      // #28562: 110830, dwildt+
    $this->oddClassColumns  = $lDisplayList['templateMarker.']['oddClass.']['columns'];
    $this->oddClassRows     = $lDisplayList['templateMarker.']['oddClass.']['rows'];
      // Get oddClasses



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for handle the consolidated rows

      // #11785, dwildt, 101229
      // This hook is used by one foreign extension at least
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_list_consolidated']))
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_hooks)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_list_consolidated']);
        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_list_consolidated']);
        $csv_ext      = implode(',', $arr_ext);
        if ($i_extensions == 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extension '.$csv_ext.' uses the HOOK rows_list_consolidated.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1);
        }
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extensions '.$csv_ext.' use the HOOK rows_list_consolidated.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      $_params = array('pObj' => &$this);
      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_list_consolidated'] as $_funcRef)
      {
        t3lib_div::callUserFunction($_funcRef, $_params, $this);
      }
    }
      // Any foreign extension is using this hook
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_hooks)
    {
      if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['rows_list_consolidated']))
      {
        t3lib_div::devlog('[INFO/HOOK] Any third party extension doesn\'t use the HOOK rows_list_consolidated.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/HOOK] See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/', $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System
      // Any foreign extension is using this hook
    $rows = $this->pObj->rows;
      // Hook for handle the consolidated rows



      //////////////////////////////////////////////////////////////////
      //
      // Keys for special handling

      // 120915, dwildt, 1-
    //$handleAs         = $this->pObj->arrHandleAs;
      // 120915, dwildt, 1-
    //$arrKeyAsDocument = $this->pObj->objZz->getCSVtablefieldsAsArray($handleAs['document']);
      // Keys for special handling



      //////////////////////////////////////////////////////////////////
      //
      // Is there a upload folder?

    // Bugfix #9418
    // $this->pObj->uploadFolder = $this->pObj->conf['views.'][$this->viewWiDot][$this->mode.'.']['upload'];
    $this->pObj->uploadFolder = $this->pObj->conf['views.'][$this->view.'.'][$this->mode.'.']['upload'];
    if (!$this->pObj->uploadFolder)
    {
      $this->pObj->uploadFolder = $this->pObj->conf['upload'];
    }
      // Is there a upload folder?



      //////////////////////////////////////////////////////////////////////
      //
      // csv export: Set CSV field devider and field enclosure

    $this->pObj->objExport->csv_init_config( );
      // #29370, 110831, dwildt+
      // csv export: Set CSV field devider and field enclosure



      //////////////////////////////////////////////////////////////////
      //
      // HTML-Template with ###ITEM### ?

    $tmpl_element = $this->pObj->cObj->getSubpart($template, '###LISTBODYITEM###');
    $bool_table = true;
    $pos = strpos($tmpl_element, '###ITEM###');
    if ($pos === false)
    {
      $bool_table = false;
    }
      // HTML-Template with ###ITEM### ?



      //////////////////////////////////////////////////////////////////
      //
      // With ###ITEM###

    if( $bool_table )
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###.<br />'.
          'The Browser will replace the ###ITEM### with the SQL result in form of a table or list.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING] Change it? Use your own marker like ####TABLE.FIELD### in the HTML template.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System

        // Table header with titles in columns
      $template = $this->tmplTableHead($template);



        // ###LISTBODY###: Table body with elements in columns
        // Counter for classes (odd / even)
        // Bug #8343
        //$c                 = 0;
      $c                 = 2;
        // Current group name. Initial value is a timestamp, because the real groupname can be false or empty
      $str_current_group = time();
        // Array with the grouped records
      $arr_htmlGroupby       = false;
        // Counter for the groups. It is a need for the current group
      $int_groupCounter  = -1;
        // Rows of a group
      $bodyRows          = '';
        // Wrap for the groupname
      if($this->pObj->str_wrap_grouptitle)
      {
        $arr_wrap_grouptitle = explode('|', $this->pObj->str_wrap_grouptitle);
      }
      if(!$this->pObj->str_wrap_grouptitle)
      {
        $arr_wrap_grouptitle = array(false, false);
      }


        // #28562: 110830, dwildt+
      $counter_tr = 0;
      $max_tr     = count( $rows ) - 1;
      $firstKey         = key($rows);
      $row              = $rows[$firstKey];
      $max_elements     = 0;
      $addedTableFields = $this->pObj->arrConsolidate['addedTableFields'];
        // 120915, dwildt, 1
      foreach( array_keys( $row) as $key )
      {
        if ( in_array( $key, (array) $addedTableFields ) )
        {
          continue;
        }
        if( in_array( $key, (array) $this->arr_rmFields ) )
        {
          continue;
        }
        $max_elements++;
      }
      $this->max_elements = $max_elements;
        // #28562: 110830, dwildt+

        // #34963, dwildt+
      $c = 0;
        // elements
      foreach( ( array ) $rows as $elements )
      {
        if( $this->ignore_empty_rows_rule )
        {
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devLog('[WARN/TEMPLATING] CONTINUE because of ignore_empty_rows_rule!', $this->pObj->extKey, 2);
          }
          continue;
        }
          // In case of the first group and a new group
        $str_next_group = $this->groupBy_get_groupname($elements);
        if($this->bool_groupby)
        {
          if($str_next_group != $str_current_group)
          {
            $str_current_group = $str_next_group;
            $int_groupCounter++;
            $arr_htmlGroupby[$int_groupCounter] = $this->pObj->cObj->getSubpart($template, '###GROUPBY###');
            $arr_htmlGroupby[$int_groupCounter] = str_replace('###GROUPBY_GROUPNAME###',
                                                    $arr_wrap_grouptitle[0].$str_current_group.$arr_wrap_grouptitle[1],
                                                    $arr_htmlGroupby[$int_groupCounter]);
            if($int_groupCounter > 0)
            {
                // Allocates the collected rows to the passed group
              $arr_htmlGroupby[$int_groupCounter - 1] = $this->pObj->cObj->substituteSubpart(
                                                          $arr_htmlGroupby[$int_groupCounter - 1],
                                                          '###LISTBODY###', $bodyRows, true);
              $bodyRows = '';
            }
          }
        }
          // In case of the first group and a new group

          // ###LISTBODYITEM###: bodyRows
        $this->pObj->elements = $elements;
        $htmlRows     = $this->tmplRows($elements, '###LISTBODYITEM###', $template);

          // #29370, 110831, dwildt+
          // Remove last devider in case of csv export
        if( $this->pObj->objExport->str_typeNum == 'csv' )
        {
          $htmlRows = rtrim( $htmlRows, $this->pObj->objExport->csv_devider );
        }

        $listBodyRow  = $this->pObj->cObj->getSubpart($template, '###LISTBODY###');
        $listBodyRow  = $this->pObj->cObj->substituteSubpart($listBodyRow, '###LISTBODYITEM###', $htmlRows, true);

          // #28562: 110830, dwildt+
        $markerArray['###TR_COUNTER###']  = $counter_tr;
        switch(true)
        {
          case( $counter_tr == 0 ):
            $markerArray['###TR_FIRST_LAST###'] = 'first';
            break;
          case( $counter_tr >=  $max_tr ):
            $markerArray['###TR_FIRST_LAST###'] = 'last';
            break;
          default:
            $markerArray['###TR_FIRST_LAST###'] = null;
            break;
        }
        $markerArray['###TR_EVEN_OR_ODD###']  = $counter_tr%2 ? $this->oddClassRows : null;
        $counter_tr++;
          // #28562: 110830, dwildt+

          // Suggestion #8856,  dwildt, 100812
          // Bugfix     #10762, dwildt, 101201
          //$markerBodyRows['###CLASS###'] = ($c++%2 ? ' class="odd"' : '');
          // #12738, 120515, dwildt
        $str_class = 'item item-'. ( $c );
        if( $c == 0 )
        {
          $str_class = $str_class . ' item-first first';
        }
        else {
          if( ( $c ) % 2 )
          {
            $str_class = $str_class . ' item-odd odd';
          }
          if( count( $rows ) == ( $c + 1 ) )
          {
            $str_class = $str_class . ' item-last last';
          }
        }
        $markerBodyRows['###CLASS###'] = ' class="' . $str_class . '"';
          // Suggestion #8856, dwildt, 100812

        $listBodyRow  = $this->pObj->cObj->substituteMarkerArray($listBodyRow, $markerBodyRows);
        $bodyRows    .= $listBodyRow;
          // ###LISTBODYITEM###: bodyRows
        $c++;
      }
        // 120915, dwildt, 1+
      unset( $max_tr );
        // elements



      if( ! $this->ignore_empty_rows_rule )
      {
        if($this->bool_groupby)
        {
          // Allocates the collected rows to the current group
          $arr_htmlGroupby[$int_groupCounter] = $this->pObj->cObj->substituteSubpart(
                                                  $arr_htmlGroupby[$int_groupCounter],
                                                  '###LISTBODY###', $bodyRows, true);
          $str_htmlGroupby = implode("\n",$arr_htmlGroupby);
          $template = $this->pObj->cObj->substituteSubpart($template, '###GROUPBY###', $str_htmlGroupby, true);
        }
        if(!$this->bool_groupby)
        {
          // #10762 ###CLASS### won't be replaced
          // $template = $this->pObj->cObj->substituteSubpart($template, '###LISTBODYITEM###', $bodyRows, true);
          $template = $this->pObj->cObj->substituteSubpart($template, '###LISTBODY###', $bodyRows, true);
        }
        // ###LISTBODY###: Table body with elements in columns
      }
      if( $this->ignore_empty_rows_rule )
      {
        if ( $this->pObj->b_drs_templating )
        {
          t3lib_div::devLog('[INFO/TEMPLATING] ###LISTBODY###, ###GROUPBY### are ignored because of ignore_empty_rows_rule!', $this->pObj->extKey, 0);
        }
      }
    }
      // With ###ITEM###



      ////////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ( $this->pObj->b_drs_perform )
    {
      if( $this->pObj->bool_typo3_43 )
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime( );
      }
      if( ! $this->pObj->bool_typo3_43 )
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After some initials: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
      // DRS - Performance



      ////////////////////////////////////////////////////////////////////////
      //
      // Without ###ITEM### but with table.field marker

    if(!$bool_table)
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### doesn\'t contain the marker ###ITEM###.<br />'.
          'The Browser will process all ###TABLE.FIELD### markers instead.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING] Change it? Remove your markers ####TABLE.FIELD### and use ###ITEM### instead.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System

        // Select box for ordering
      $template = $this->tmplTableHead($template);

        // ###LISTBODY### Content
        // Current group name. Initial value is a timestamp, because the real groupname can be false or empty
      $str_current_group = time();
        // Array with the grouped records
      $arr_htmlGroupby   = false;
        // Counter for the groups. It is a need for the current group
      $int_groupCounter  = -1;
        // Rows of a group
      $tmpl_rows         = '';
        // Wrap for the groupname
      if($this->pObj->str_wrap_grouptitle)
      {
        $arr_wrap_grouptitle = explode('|', $this->pObj->str_wrap_grouptitle);
      }
      if(!$this->pObj->str_wrap_grouptitle)
      {
        $arr_wrap_grouptitle = array(false, false);
      }
        // Rows
      $c = 0;
      foreach((array) $rows as $row => $elements)
      {
        if( $this->ignore_empty_rows_rule )
        {
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devLog('[WARN/TEMPLATING] CONTINUE because of ignore_empty_rows_rule!', $this->pObj->extKey, 2);
          }
          continue;
        }
          // In case of the first group and a new group
        $str_next_group = $this->groupBy_get_groupname($elements);
        if($this->bool_groupby)
        {
            // A new group is starting
          if($str_next_group != $str_current_group)
          {
            $str_current_group = $str_next_group;
            $int_groupCounter++;
            $arr_htmlGroupby[$int_groupCounter] = $this->pObj->cObj->getSubpart($template, '###GROUPBY###');
            $str_current_group_stdWrap          = $this->groupBy_stdWrap($elements);
            $arr_htmlGroupby[$int_groupCounter] = str_replace('###GROUPBY_GROUPNAME###',
                                                    $arr_wrap_grouptitle[0].$str_current_group_stdWrap.$arr_wrap_grouptitle[1],
                                                    $arr_htmlGroupby[$int_groupCounter]);
            if($int_groupCounter > 0)
            {
                // Allocates the collected rows to the passed group
              $arr_htmlGroupby[$int_groupCounter - 1] = $this->pObj->cObj->substituteSubpart(
                                                          $arr_htmlGroupby[$int_groupCounter - 1],
                                                          '###LISTBODY###', $tmpl_rows, true);
              $tmpl_rows = '';
            }
          }
            // A new group is starting
        }
          // In case of the first group and a new group

        $this->pObj->elements    = $elements;
        $this->pObj->rows[$row]  = $rows[$row];
        $tmpl_row                = $this->tmplRows($elements, '###LISTBODYITEM###', $template); //:todo: Performance

          // Remove last devider in case of csv export
          // #29370, 110831, dwildt+
        if( $this->pObj->objExport->str_typeNum == 'csv' )
        {
          $tmpl_row = rtrim( $tmpl_row, $this->pObj->objExport->csv_devider );
        }

          // Suggestion #8856,  dwildt, 100812
          // Bugfix     #10762, dwildt, 101201
          //$markerBodyRows['###CLASS###'] = ($c++%2 ? ' class="odd"' : '');
          // #12738, 120515, dwildt
        $str_class = 'item item-'. ( $c );
        if( $c == 0 )
        {
          $str_class = $str_class . ' item-first first';
        }
        else {
          if( ( $c ) % 2 )
          {
            $str_class = $str_class . ' item-odd odd ';
          }
          if( count( $rows ) == ( $c + 1 ) )
          {
            $str_class = $str_class . ' item-last last';
          }
        }
        $markerArray['###CLASS###'] = ' class="' . $str_class . '"';
          // Suggestion #8856, dwildt, 100812

          // Bug #5922, 100210
        if(!is_array($markerArray))
        {
          $tmpl_rows .= $tmpl_row;
        }
        if(is_array($markerArray))
        {
          $tmpl_row   = $this->pObj->cObj->substituteMarkerArray($tmpl_row, $markerArray);
          $tmpl_rows .= $tmpl_row;
        }
        $c++;
      }
        // Rows
      unset($markerArray);

        // GROUP BY true
      if( $this->bool_groupby )
      {
        if( $this->ignore_empty_rows_rule )
        {
          if ($this->pObj->b_drs_templating)
          {
            t3lib_div::devlog('[INFO/TEMPLATING] ###LISTBODY###, ###GROUPBY### will ignored bevause of ignore_empty_rows_rule.', $this->pObj->extKey, 0);
          }
        }
        if( ! $this->ignore_empty_rows_rule )
        {
          // Allocates the collected rows to the current group
          $arr_htmlGroupby[$int_groupCounter] = $this->pObj->cObj->substituteSubpart
                                                (
                                                  $arr_htmlGroupby[$int_groupCounter],
                                                  '###LISTBODY###',
                                                  $tmpl_rows,
                                                  true
                                                );
          $str_htmlGroupby = implode("\n",$arr_htmlGroupby);
          $template = $this->pObj->cObj->substituteSubpart($template, '###GROUPBY###', $str_htmlGroupby, true);
        }
      }
        // GROUP BY true

        // GROUP BY false
      if( ! $this->bool_groupby )
      {
        if( $this->ignore_empty_rows_rule )
        {
          if ($this->pObj->b_drs_templating)
          {
            t3lib_div::devlog('[INFO/TEMPLATING] ###LISTBODY### will ignored bevause of ignore_empty_rows_rule.', $this->pObj->extKey, 0);
          }
        }
        if( ! $this->ignore_empty_rows_rule )
        {
          $template = $this->pObj->cObj->substituteSubpart($template, '###LISTBODY###', $tmpl_rows, true);
        }
      }
        // GROUP BY false

      $this->pObj->rows = $rows;
        // ###LISTBODY### Content



        ////////////////////////////////////////////////////////////////////////
        //
        // DRS - Performance

      if ($this->pObj->b_drs_perform)
      {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After rows with individual design: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
      }
        // DRS - Performance
    }
      // Without ###ITEM### but with table.field marker



      /////////////////////////////////////
      //
      // Fill up the template with content

    $markerArray['###MODE###']    = $this->mode;
    $markerArray['###VIEW###']    = $this->pObj->view;
    $markerArray['###SUMMARY###'] = $this->pObj->objWrapper->tableSummary('list');
    $markerArray['###CAPTION###'] = $this->pObj->objWrapper->tableCaption('list');
    $subpart        = $this->pObj->cObj->getSubpart($template, '###LISTVIEW###');
    $listview       = $this->pObj->cObj->substituteMarkerArray($subpart, $markerArray);
    $template       = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $listview, true);
    unset($markerArray);
      // 110829, dwildt-
    //$markerArray    = $this->pObj->objWrapper->constant_markers();
      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray    = $this->markerArray;
    $template       = $this->pObj->cObj->substituteMarkerArray($template, $markerArray);
      // Fill up the template with content



      /////////////////////////////////////
      //
      // SEO: Search Engine Optimisation

    reset($rows);
    $firstKey = key($rows);
    $this->pObj->objSeo->seo($rows[$firstKey]);
    // SEO: Search Engine Optimisation



    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After generatin template: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance

    return $template;
  }


  /**
 * Building the table with the result in the single view. There can be more than one rows in case of 1:N Relations
 *
 * @param	string		Name of the current table
 * @param	array		The SQL result as rows array
 * @return	void
 * @version 4.0.0
 * @since 1.0.0
 */
  function tmplSingleview( $template, $rows )
  {

      ///////////////////////////////////////////////////////////
      //
      // Get the local or the global displaySingle array

    $lDisplaySingle = $this->conf_view['displaySingle.'];
    if (!is_array($lDisplaySingle))
    {
      $lDisplaySingle = $this->pObj->conf['displaySingle.'];
    }
    $this->lDisplaySingle = $lDisplaySingle;
      // Get the local or the global displaySingle array



      ///////////////////////////////////////////////////////////
      //
      // Set the globals elements and rows

    if( is_array ( $rows ) )
    {
      reset( $rows );
      $firstKey = key($rows);
      $elements = $rows[$firstKey];
      $this->pObj->elements = $elements;
      $this->pObj->rows     = $rows;
    }
      // Set the globals elements and rows



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for handle the consolidated row

      // #11785, dwildt, 101229
      // This hook is used by one foreign extension at least
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['row_single_consolidated']))
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_hooks)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['row_single_consolidated']);
        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['row_single_consolidated']);
        $csv_ext      = implode(',', $arr_ext);
        if ($i_extensions == 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extension '.$csv_ext.' uses the HOOK row_single_consolidated.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1);
        }
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/HOOK] The third party extensions '.$csv_ext.' use the HOOK row_single_consolidated.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      $_params = array('pObj' => &$this);
      foreach((array) $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['row_single_consolidated'] as $_funcRef)
      {
        t3lib_div::callUserFunction($_funcRef, $_params, $this);
      }
//echo chr(10) . '<!--' . chr(10) . __LINE__ . ' ' . chr(10) . __FILE__ . ' $this->pObj->rows:' . chr(10) . print_r($this->pObj->rows, 1) . chr(10) . '-->' . chr(10);
    }
      // Any foreign extension is using this hook
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_hooks)
    {
      if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['row_single_consolidated']))
      {
        t3lib_div::devlog('[INFO/HOOK] Any third party extension doesn\'t use the HOOK row_single_consolidated.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/HOOK] See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/', $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System
      // Any foreign extension is using this hook
      // Hook for handle the consolidated row



    ///////////////////////////////////////////////////////////
    //
    // Set the globals elements and rows

    $rows = $this->pObj->rows;
    if (is_array($rows))
    {
      reset($rows);
      $firstKey             = key($rows);
      $elements             = $rows[$firstKey];
      $this->pObj->elements = $elements;
      $this->pObj->rows     = $rows;
    }
    // Set the globals elements and rows
//echo chr(10) . chr(10) . __LINE__ . ' ' . chr(10) . __FILE__ . ' $this->pObj->rows:' . chr(10) . print_r($this->pObj->rows, 1) . chr(10) . chr(10);



    ///////////////////////////////////////////////////////////
    //
    // RETURN: We don't have any row

    $this->updateWizard( 'displaySingle.noItemMessage', $lDisplaySingle );
    if (count($elements) == 0 || !is_array($elements))
    {
      // We don't have a result
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/SQL+TEMPLATING] We don\'t have any row!', $this->pObj->extKey, 0);
      }
      $template = $this->pObj->cObj->substituteSubpart($template, '###SINGLEVIEW###', '', true);

        // #37731, 120604, dwildt, +
      $cObj_name = $lDisplaySingle['noItemMessage'];
      if( $cObj_name == '1' )
      {
        $cObj_name = 'TEXT';
      }
      $cObj_conf      = $lDisplaySingle['displayList.']['noItemMessage.'];
      $noItemMessage  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      $template       = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $noItemMessage, true);
        // #37731, 120604, dwildt, +
        // #37731, 120604, dwildt, -
//      if ($lDisplaySingle['noItemMessage'])
//      {
//        $template = $this->pObj->objWrapper->general_stdWrap('', $lDisplaySingle['noItemMessage.']);
//        if ($this->pObj->b_drs_templating)
//        {
//          t3lib_div::devlog('[INFO/TEMPLATING] Returned Template is the noItemMessage.', $this->pObj->extKey, 0);
//          t3lib_div::devLog('[HELP/TEMPLATING] Change it? Configure '.$this->conf_path.'.displayList.noItemMessage.', $this->pObj->extKey, 1);
//        }
//      }
        // #37731, 120604, dwildt, -
        // 110829, dwildt+
      $this->tmpl_marker( );
      $markerArray = $this->markerArray;
        // 110829, dwildt+
        // 110829, dwildt-
//      $markerArray    = $this->pObj->objWrapper->constant_markers();
      $template       = $this->pObj->cObj->substituteMarkerArray($template, $markerArray);
      return $template;
    }
    // RETURN: We don't have any row


    /////////////////////////////////////
    //
    // We need $singleRow later for SEO

    $singleRow = $elements;
    // We need $singleRow later for SEO



    ////////////////////////////////////////////////////////
    //
    // Replace mode an view in the whole template

    $template = str_replace('###MODE###', $this->pObj->piVar_mode, $template);
    $template = str_replace('###VIEW###', $this->pObj->view, $template);
    // Replace mode an view in the whole template



    /////////////////////////////////////
    //
    // Building the back button

//    // #9659, 101010 fsander
//    if ($this->pObj->objFlexform->bool_ajax_enabled)
//    {
//      if ($this->pObj->b_drs_templating || $this->pObj->b_drs_javascript)
//      {
//        t3lib_div::devlog('[INFO/TEMPLATING+JSS] Backbutton won\'t be displayed because AJAX is activated', $this->pObj->extKey, 0);
//      }
//      $str_backbutton = false;
//      $subpart    = $this->pObj->cObj->getSubpart($template, '###BACKBUTTON###');
//      $backbutton = $this->pObj->cObj->substituteMarkerArray($subpart, array('###BUTTON###' => $str_backbutton));
//      $template   = $this->pObj->cObj->substituteSubpart($template, '###BACKBUTTON###', $backbutton, true);
//    }
//    if (!$this->pObj->objFlexform->bool_ajax_enabled)
//    {
      $bool_backbutton  = $this->pObj->lDisplay['backbutton'];
      $conf_backbutton  = $this->pObj->lDisplay['backbutton.'];
      $str_backbutton   = false;
      if ($bool_backbutton)
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] Backbutton will be displayed.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure displaySingle.display.backbutton = 0, if you don\'t want any backbutton.', $this->pObj->extKey, 1);
        }
        $str_backbutton = $this->pObj->objWrapper->general_stdWrap('', $conf_backbutton);
      }
      if (!$bool_backbutton)
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] Backbutton won\'t be displayed.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure displaySingle.display.backbutton = 1, if you want to display the backbutton.', $this->pObj->extKey, 1);
        }
      }
      $subpart    = $this->pObj->cObj->getSubpart($template, '###BACKBUTTON###');
      $backbutton = $this->pObj->cObj->substituteMarkerArray($subpart, array('###BUTTON###' => $str_backbutton));
      $template   = $this->pObj->cObj->substituteSubpart($template, '###BACKBUTTON###', $backbutton, true);
//    }
    // Building the back button


    /////////////////////////////////////
    //
    // Init the global array $arrHandleAs

    $this->pObj->objTca->setArrHandleAs();
    // Init the global array $arrHandleAs


    /////////////////////////////////
    //
    // Is there a upload folder?

    $this->pObj->uploadFolder = $this->conf_view['upload'];
    if (!$this->pObj->uploadFolder)
    {
      $this->pObj->uploadFolder = $this->pObj->conf['upload'];
    }
    // Is there a upload folder?


    /////////////////////////////////////
    //
    // Keys for special handling

    $handleAs         = $this->pObj->arrHandleAs;
      // 120915, dwildt, 1-
    //$arrKeyAsDocument = $this->pObj->objZz->getCSVtablefieldsAsArray($handleAs['document']);
    // Keys for special handling


    ////////////////////////////////////////////////////////////////////////
    //
    // Wrap all elements. If the fieldname is a marker in the HTML-Template, it will be replaced

    $markerArray = $this->render_handleAs($elements, $handleAs, $markerArray);
    $markerArray = $this->pObj->objZz->extend_marker_wi_pivars($markerArray);
    // Wrap all elements. If the fieldname is a marker in the HTML-Template, it will be replaced


    //////////////////////////////////////////////////////////////////
    //
    // Should swords get an HTML wrap in results?

    // Get the local or gloabl autoconfig array - #9879
    $lAutoconf = $this->conf_view['autoconfig.'];
    if (!is_array($lAutoconf))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.single.X. hasn\'t any autoconf array.<br />
          We take the global one.', $this->pObj->extKey, 0);
      }
      $lAutoconf = $this->pObj->conf['autoconfig.'];
    }
    // Get the local or gloabl autoconfig array- #9879
    $arr_TCAitems = $lAutoconf['autoDiscover.']['items.'];
    // Get the TCA properties from the TypoScript
    $bool_dontColorSwords = false;
    // Should swords get an HTML wrap in results?



    /////////////////////////////////////
    //
    // Building the result phrase

    // result phrase allocates values for the array coloredSwords
// :todo: 3.3.4 Bugfix: Without next line, swords in single view won't be colored
//if(t3lib_div::_GP('dev')) var_dump('template 1037: todo');
    $this->resultphrase();
    // Building the result phrase



    /////////////////////////////////////
    //
    // Building the body title

    $displayTitle = $this->pObj->lDisplay['title'];
    if ($displayTitle)
    {
        // Is the system marker ###TITLE### defined?
        // 120515, dwildt, 9+
      $pos = strpos($template, '###TITLE###');
      if ($pos === false)
      {
        if ($this->pObj->b_drs_templating)
        {
          $prompt = 'The system marker ###TITLE### isn\'t used in the HTML-template.';
          t3lib_div::devlog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
      else
      {
        $value = false;
        // 3.4.0
        unset($this->arr_curr_value);
        if ($handleAs['title'])
        {
          list($table, $field) = explode('.', $handleAs['title']);
          $value = $elements[$handleAs['title']];
          // 3.4.0
          $this->arr_curr_value[$handleAs['title']] = $value;

          // Colors the sword words and phrases
          $bool_dontColorSwords = $arr_TCAitems['title.']['dontColorSwords'];
          if (!$bool_dontColorSwords)
          {
            $value = $this->pObj->objZz->color_swords($handleAs['title'], $value);
          }
          // Colors the sword words and phrases
          if ($this->pObj->b_drs_templating)
          {
            t3lib_div::devlog('[INFO/TEMPLATING] '.$handleAs['title'].' will be handled as the title.', $this->pObj->extKey, 0);
            t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TITLE### will be replaced.', $this->pObj->extKey, 0);
          }
        }
        if(!$value)
        {
          list($table, $field) = explode('.', $this->pObj->arrLocalTable['uid']);
          $value = 'ID '.$this->pObj->piVars['showUid'].' from table '.$table;
          if ($this->pObj->b_drs_templating)
          {
            t3lib_div::devlog('[INFO/TEMPLATING] \''.$value.'\' will be handled as the title.', $this->pObj->extKey, 0);
            t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TITLE### will be replaced.', $this->pObj->extKey, 0);
          }
        }
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure displaySingle.display.title = 0, if you don\'t want any title handling.', $this->pObj->extKey, 1);
        }
        $key   = $handleAs['title'];
        $value = $this->pObj->objWrapper->wrapAndLinkValue($key, $value, 0);
        unset($elements[$handleAs['title']]);
      }
    }

    if (!$displayTitle)
    {
      $value = '';
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] No field won\'t be handled as the title.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING] Please configure displaySingle.display.title = 1, if you want an automatically title handling.', $this->pObj->extKey, 1);
      }
    }

    $markerArray['###TITLE###'] = $value;
    // Building the body title


      /////////////////////////////////////
      //
      // Building the body content

      // Is the system marker ###IMAGE### defined?
    $i_pos = strpos($template, '###IMAGE###');
    if ($i_pos === false)
    {
      $b_marker_image = false;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###IMAGE### isn\'t used in the HTML-template.', $this->pObj->extKey, 0);
      }
    }
    else
    {
      $b_marker_image = true;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###IMAGE### is used in the HTML-template.', $this->pObj->extKey, 0);
      }
    }
      // Is the system marker ###IMAGE### defined?

      // $b_marker_image
    if( $b_marker_image )
    {
        // DRS - Development Reporting System
      if( $handleAs['image'] ) 
      {
        if( $this->pObj->b_drs_templating )
        {
          t3lib_div::devlog('[INFO/TEMPLATING] The field \''.$handleAs['image'].'\' will be wrapped as an IMAGE.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###IMAGE### will be replaced.', $this->pObj->extKey, 0);
        }
      }
      if( ! $handleAs['image'] )
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] There is no field detected for handle as an image.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###IMAGE### will be deleted.', $this->pObj->extKey, 0);
        }
      }
        // DRS - Development Reporting System
        // image
      $tsImage['image']           = $elements[$handleAs['image']];
      $bool_dontColorSwords = $arr_TCAitems['image.']['dontColorSwords'];
      if (!$bool_dontColorSwords)
      {
        $value = $this->pObj->objZz->color_swords($handleAs['image'], $value);
      }
        // image
        // imageCaption
      $tsImage['imagecaption']    = $elements[$handleAs['imageCaption']];
      $bool_dontColorSwords = $arr_TCAitems['imageCaption.']['dontColorSwords'];
      if (!$bool_dontColorSwords)
      {
        $value = $this->pObj->objZz->color_swords($handleAs['imageCaption'], $value);
      }
        // imageCaption
        // imageAltText
      $tsImage['imagealttext']    = $elements[$handleAs['imageAltText']];
      $bool_dontColorSwords = $arr_TCAitems['imageAltText.']['dontColorSwords'];
      if (!$bool_dontColorSwords)
      {
        $value = $this->pObj->objZz->color_swords($handleAs['imageAltText'], $value);
      }
        // imageAltText
        // imageTitleText
      $tsImage['imagetitletext']  = $elements[$handleAs['imageTitleText']];
      $bool_dontColorSwords = $arr_TCAitems['imageTitleText.']['dontColorSwords'];
      if (!$bool_dontColorSwords)
      {
        $value = $this->pObj->objZz->color_swords($handleAs['imageTitleText'], $value);
      }
        // imageTitleText
      unset($elements[$handleAs['image']]);
      unset($elements[$handleAs['imageCaption']]);
      unset($elements[$handleAs['imageAltText']]);
      unset($elements[$handleAs['imageTitleText']]);
    }
      // $b_marker_image

      // Is the system marker ###TEXT### defined?
    $i_pos = strpos($template, '###TEXT###');
    if ($i_pos === false)
    {
      $b_marker_text = false;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TEXT### isn\'t used in the HTML-template.', $this->pObj->extKey, 0);
      }
    }
    else
    {
      $b_marker_text = true;
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TEXT### is used in the HTML-template.', $this->pObj->extKey, 0);
      }
    }
      // Is the system marker ###TEXT### defined?

    if($b_marker_text)
    {
      if($handleAs['text'])
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] The field \''.$handleAs['text'].'\' will be wrapped as TEXT.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TEXT### will be replaced.', $this->pObj->extKey, 0);
        }
      }
      if(!$handleAs['text'])
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] There is no field detected for handle as text.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###TEXT### will be deleted.', $this->pObj->extKey, 0);
        }
      }
      $markerArray['###TEXT###'] = $this->pObj->objWrapper->general_stdWrap($elements[$handleAs['text']], $lDisplaySingle['content_stdWrap.']);
      $markerArray['###TEXT###'] = $this->pObj->objZz->color_swords($handleAs['text'], $markerArray['###TEXT###']);
      unset($elements[$handleAs['text']]);
    }
    // Building the body content


    /////////////////////////////////////
    //
    // Substitute some markers

    $htmlRows   = $this->tmplRows($elements, '###SINGLEBODYROW###', $template);
    $singleBody = $this->pObj->cObj->getSubpart($template, '###SINGLEBODY###');
    $singleBody = $this->pObj->cObj->substituteSubpart($singleBody, '###SINGLEBODYROW###', $htmlRows, true);
    $template   = $this->pObj->cObj->substituteSubpart($template, '###SINGLEBODY###', $singleBody, true);
    $markerArray['###SUMMARY###'] = $this->pObj->objWrapper->tableSummary('single');
    $markerArray['###CAPTION###'] = $this->pObj->objWrapper->tableCaption('single');
    // Substitute some markers


    /////////////////////////////////////
    //
    // Replace VIEW and MODE

    $markerArray['###MODE###']            = $this->mode;
    $markerArray['###VIEW###']            = $this->pObj->view;
      // #28562, DWILDT, 110805 +
    $markerArray['###TT_CONTENT.UID###']  = $this->pObj->cObj->data['uid'];
    // Replace VIEW and MODE


    /////////////////////////////////////
    //
    // Fill up the template with content

    $subpart      = $this->pObj->cObj->getSubpart($template, '###SINGLEVIEW###');
    $singleview   = $this->pObj->cObj->substituteMarkerArray($subpart, $markerArray);
    $template     = $this->pObj->cObj->substituteSubpart($template, '###SINGLEVIEW###', $singleview, true);
    unset($markerArray);
      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray = $this->markerArray;
      // 110829, dwildt+
      // 110829, dwildt-
//    $markerArray  = $this->pObj->objWrapper->constant_markers();
    $template     = $this->pObj->cObj->substituteMarkerArray($template, $markerArray);
    // Fill up the template with content


    /////////////////////////////////////
    //
    // SEO: Search Engine Optimisation

    $this->pObj->objSeo->seo($singleRow);
    // SEO: Search Engine Optimisation


    return $template;
  }





      /**
 * Building a row for the HTML table tag <thead> out of the given record and write it to the global $template
 *
 * @param	string		Template
 * @return	string		Template
 * @version 3.9.24
 * @since 1.0.0
 */
  function tmplTableHead($template)
  {

      ///////////////////////////////////////////
      //
      // Get the field names from the SQL result

    reset($this->pObj->rows);
    $key          = key($this->pObj->rows);
    $lRows[0]     = $this->pObj->rows[$key];
    $arr_result   = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields($lRows);
    $lArrColumns  = $arr_result['data']['rows'];
    if(!is_array($lArrColumns[0]))
    {
      $lArrColumns[0] = array();
    }
    $arrColumns   = array_keys($lArrColumns[0]);
    unset($arr_result);
    $this->arr_select = $arrColumns;
      // Get the field names from the SQL result



      ///////////////////////////////////////////
      //
      // Get the default order out of the TS

    $csvOrderBy = $this->pObj->conf_sql['orderBy'];
    // Bug #6468, 100217. Thanks to Walter Sparding
    $csvOrderBy = str_ireplace(' desc', '', $csvOrderBy);
    $csvOrderBy = str_ireplace(' asc',  '', $csvOrderBy);
    $arr_tableFields = explode(',', $csvOrderBy);
      // 120915, dwildt, 1+
    $arrOrderByFields  = null;
    foreach ($arr_tableFields as $tableField)
    {
      $arrOrderByFields[] = trim($tableField);
    }
      // Get the default order out of the TS



      // csv export: Header fields shouldn't get the order property
      // #29370, 110831, dwildt+
    if( $this->pObj->objExport->str_typeNum == 'csv' )
    {
      if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
      {
        t3lib_div::devlog('[INFO/EXPORT] Don\'t link header fields. $arrOrderByFields is unset.',  $this->pObj->extKey, 0);
      }
      unset( $arrOrderByFields );
    }



      // Set the global arr_rmFields
    $this->tmpl_rmFields( );

      // Get the global $arrHandleAs array
    $handleAs = $this->pObj->arrHandleAs;

      // Amount of columns
    $maxColumns = count($arrColumns) - 1;



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Delete columns, we don't want

    foreach((array) $arrColumns as $columnKey => $columnValue)
    {
        // We don't want the imageCaption
      if (trim($columnValue) == $handleAs['imageCaption'])
      {
        unset($arrColumns[$columnKey]);
        $maxColumns--;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] Table Head: '.$columnKey.' is removed.', $this->pObj->extKey, 0);
        }
      }
        // We don't want the imageAltText
      if (trim($columnValue) == $handleAs['imageAltText'])
      {
        unset($arrColumns[$columnKey]);
        $maxColumns--;
        if ($this->pObj->b_drs_templating)
        {
         t3lib_div::devLog('[INFO/TEMPLATING] Table Head: '.$columnKey.' is removed.', $this->pObj->extKey, 0);
        }
      }
        // We don't want the imageTitleText
      if (trim($columnValue) == $handleAs['imageTitleText'])
      {
        unset($arrColumns[$columnKey]);
        $maxColumns--;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] Table Head: '.$columnKey.' is removed.', $this->pObj->extKey, 0);
        }
      }

        // We don't want fields from global arrConsolidate['addedTableFields']
      $addedTableFields = $this->pObj->arrConsolidate['addedTableFields'];
      if ( in_array( trim( $columnValue ), ( array ) $addedTableFields ) )
      {
        unset($arrColumns[$columnKey]);
        $maxColumns--;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] Table Head: '.$columnValue.' is removed.', $this->pObj->extKey, 0);
        }
      }
        // We don't want fields from global arrConsolidate['addedTableFields']

        // We don't want fields from functions.clean_up.csvTableFields
      if (in_array(trim($columnValue), $this->arr_rmFields))
      {
        unset($arrColumns[$columnKey]);
        $maxColumns--;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] Table Head: '.$columnKey.' is removed.', $this->pObj->extKey, 0);
        }
      }
        // We don't want fields from functions.clean_up.csvTableFields
    }
      // Delete columns, we don't want



      //////////////////////////////////////////////////////////////////////////
      //
      // HTML-Template with ###ITEM### ?

    $bool_table   = true;
    $tmpl_element = $this->pObj->cObj->getSubpart($template, '###LISTHEAD###');
    $pos = strpos($tmpl_element, '<tr');
    if ($pos === false)
    {
      $bool_table = false;
    }
    if(!$bool_table)
    {
      $tmpl_element = $this->pObj->cObj->getSubpart($template, '###LISTBODYITEM###');
      $pos = strpos($tmpl_element, '###ITEM###');
      if (!($pos === false))
      {
        $bool_table = false;
      }
    }
      // HTML-Template with ###ITEM### ?



      //////////////////////////////////////////////////////////////////////
      //
      // csv export: with ###ITEM### in every case

      // #29370, 110831, dwildt+
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] Template with ###ITEM### in ###LISTHEAD###.',  $this->pObj->extKey, 0);
        }
        $bool_table = true;
        break;
      default:
        // Do nothing;
    }
      // csv export: with ###ITEM### in every case



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Building the table head or the select box for ordering

    $currentColumn  = 0;
      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray = $this->markerArray;
      // 110829, dwildt+
      // 110829, dwildt-
//    $markerArray    = $this->pObj->objWrapper->constant_markers();
    $bool_first     = true;

      // #28562: 110830, dwildt+
    $counter_th = 0;
    $max_th     = count( $arrColumns ) - 1;



      // Loop: All columns / keys of first record
    foreach( ( array ) $arrColumns as $columnValue )
    {
      list( $table, $field ) = explode( '.', trim( $columnValue ) );
        // 120915, dwildt, 1+
      unset( $table );
      $field    = trim( $columnValue );
      $fieldLL  = $this->pObj->objZz->getTableFieldLL( $field );
        // Order the list
      $sort = false;
      if (in_array($field, $arrOrderByFields))
      {
        if ($this->pObj->internal['descFlag'])
        {
          $b_asc  = 0;
        }
        else
        {
          $b_asc  = 1;
        }
        $sort = array('sort' => $field.':'.$b_asc);
      }
        // Order the list

        // #28562: 110830, dwildt+
      $markerArray['###TH_COUNTER###'] = $counter_th;
      switch(true)
      {
        case( $counter_th == 0 ):
          $markerArray['###TH_FIRST_LAST###'] = 'first';
          break;
        case( $counter_th >=  $max_th ):
          $markerArray['###TH_FIRST_LAST###'] = 'last';
          break;
        default:
          $markerArray['###TH_FIRST_LAST###'] = null;
          break;
      }
      $markerArray['###TH_EVEN_OR_ODD###']  = $counter_th%2 ? $this->oddClassColumns : null;
        // #28562: 110830, dwildt+

        // Building the table head
      if($bool_table)
      {
          // DRS - Development Reporting System
        if ($bool_first && $this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###.<br />'.
            'The Browser will generate a table-head-tag with the field names and an order possibility.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] Do you want a select box for ordering? Use your own marker like ####TABLE.FIELD### in the HTML template.', $this->pObj->extKey, 1);
        }
          // DRS - Development Reporting System
        if($currentColumn < $maxColumns)
        {
          $class = 'cell-'.$currentColumn;
        }
        if($currentColumn >= $maxColumns)
        {
          $class = 'cell-'.$currentColumn.' last';
        }
        $listHeadItem = $this->pObj->cObj->getSubpart($template, '###LISTHEADITEM###');
        $markerArray['###CLASS###'] = ' class="'.$class.'"';
        $str_href                   = $fieldLL;

          // csv export: move value to a proper csv value
          // #29370, 110831, dwildt+
        $str_href = $this->pObj->objExport->csv_value( $str_href );

        if (in_array($field, $arrOrderByFields))
        {
          $str_href                 = $this->pObj->pi_linkTP_keepPIvars($fieldLL, $sort, $this->pObj->boolCache);
        }
        $markerArray['###ITEM###']  = $str_href;
        $items       .= $this->pObj->cObj->substituteMarkerArray($listHeadItem, $markerArray);
        $currentColumn++;
      }
        // Building the table head

        // Building the select box for ordering
      if (in_array($field, $arrOrderByFields))
      {
        if(!$bool_table)
        {
            // DRS - Development Reporting System
          if ($bool_first && $this->pObj->b_drs_templating)
          {
            t3lib_div::devlog('[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### doesn\'t contain the marker ###ITEM###.<br />'.
              'The Browser won\'t generate any table-head but a select box for ordering.', $this->pObj->extKey, 0);
            t3lib_div::devLog('[HELP/TEMPLATING] Do you want a table head? Remove your markers ####TABLE.FIELD### and use ###ITEM###.', $this->pObj->extKey, 1);
          }
            // DRS - Development Reporting System
          $listHeadItem               = $this->pObj->cObj->getSubpart($template, '###LISTHEADITEM###');
          $str_url                    = $this->pObj->pi_linkTP_keepPIvars_url($sort, $this->pObj->boolCache);
// #10204, dwildt, 101012
//          $markerArray['###ITEM###']  = '<option value="'.$str_url.'">'.$fieldLL.' ('.$b_asc.')</option>'."\n";
//          $arr_options[$str_url]      = $fieldLL;  // For the select box below
          $arr_orderBox_value_label[$field.':'.$b_asc] = $fieldLL;
          $items                     .= $this->pObj->cObj->substituteMarkerArray($listHeadItem, $markerArray);
          $currentColumn++;
        }
      }
        // Building the select box for ordering
      $bool_first = false;
      $counter_th++;
    }
      // 120915, dwildt, 1+
    unset( $max_th );
      // Loop: All columns / keys of first record

      // Remove last devider in case of csv export
      // #29370, 110831, dwildt+
    if( $this->pObj->objExport->str_typeNum == 'csv' )
    {
      $items = rtrim( $items, $this->pObj->objExport->csv_devider );
    }

      // Don't display selectBox_orderBy
      // #12005, 110107, dwildt
    if(!$bool_table)
    {
      $bool_display = $this->lDisplayList['selectBox_orderBy.']['display'];
      if(!$bool_display)
      {
        $items = null;
          // Workaround
        $bool_table = true;
          // DRS - Development Reporting System
        if ($bool_first && $this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] selectBox_orderBy.display is FALSE. selectbox won\'t be displayed.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] If you want to display it, please enable displayList.selectBox_orderBy.display.', $this->pObj->extKey, 1);
        }
          // DRS - Development Reporting System
      }
    }
      // #12005, 110107, dwildt
      // Don't display selectBox_orderBy



      // Building the select box for ordering records
    if(!$bool_table)
    {
       // Do we have fields for ordering, which aren't in the SQL result?
      $arrOrderByWoColumns = array_diff($arrOrderByFields, $arrColumns);
      foreach( ( array ) $arrOrderByWoColumns as $columnValue )
      {
          // 3.9.24, 120604, dwildt+
        if( empty ( $columnValue ) )
        {
          continue;
        }
          // 3.9.24, 120604, dwildt+
        if ($this->pObj->internal['descFlag'])
        {
          $b_asc  = 0;
        }
        else
        {
          $b_asc  = 1;
        }
        $field    = trim($columnValue);
        $fieldLL  = $this->pObj->objZz->getTableFieldLL($field);
        $sort     = array('sort' => $field.':'.$b_asc);
        $str_url  = $this->pObj->pi_linkTP_keepPIvars_url($sort, $this->pObj->boolCache);
        // #10204, dwildt, 101012
        $arr_orderBox_value_label[$field.':'.$b_asc] = $fieldLL;
      }
       // Do we have fields for ordering, which aren't in the SQL result?
       // #8337, 101011, dwildt
      $obj_ts     = $this->lDisplayList['selectBox_orderBy.']['selectbox'];
      $arr_ts     = $this->lDisplayList['selectBox_orderBy.']['selectbox.'];
      if(!is_array($arr_ts))
      {
         // DRS - Development Reporting System
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[ERROR/TEMPLATING] The TS array selectBox_orderBy.selectbox is empty!', $this->pObj->extKey, 3);
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure a proper TypoScript.', $this->pObj->extKey, 1);
        }
         // DRS - Development Reporting System
      }
      $conf_tableField  = 'table.field';  // Dummy
//      #10204, dwildt, 101012
//      $arr_orderBox_value_label[$field.':'.$b_asc] = $fieldLL;

      $arr_result       = $this->pObj->oblFltr3x->items_order_and_addFirst($arr_ts, $arr_orderBox_value_label, $conf_tableField);
      $arr_values       = $arr_result['data']['values'];

      unset($arr_result);



        /////////////////////////////////////////////////////////////////
        //
        // Process nice_piVar

        // #8337, 101011, dwildt
      $arr_result     = $this->pObj->oblFltr3x->get_nice_piVar($obj_ts, $arr_ts, 'orderBy');
      $key_piVar      = $arr_result['data']['key_piVar'];
      $arr_piVar      = $arr_result['data']['arr_piVar'];
      $str_nice_piVar = $arr_result['data']['nice_piVar'];
      unset($arr_result);
        // Process nice_piVar



      $str_html             = false;
      $int_counter_element  = 0;
      $conf_selected        = ' '.$arr_ts['wrap.']['item.']['selected'];
      $int_space_left       = $arr_ts['wrap.']['item.']['nice_html_spaceLeft'];
      $str_space_left       = str_repeat(' ', $int_space_left);
      if(!is_array($arr_values))
      {
        $arr_values = array();
      }
        // Loop through the rows of the SQL result
      foreach((array) $arr_values as $value => $label)
      {
          // 120915, dwildt, 1-
        //$str_counter_element  = $int_counter_element.'.';
        $conf_item            = $arr_ts['wrap.']['item'];
        // Wrap the item class
        if($b_asc)
        {
          $str_order = 'asc';
        }
        if(!$b_asc)
        {
          $str_order = 'desc';
        }
        $conf_item  = $this->pObj->oblFltr3x->get_wrappedItemClass($arr_ts, $conf_item, $str_order);
        // Wrap the item style
        $conf_item  = $this->pObj->oblFltr3x->get_wrappedItemStyle($arr_ts, $conf_item, $str_order);
        // Wrap the item uid
        $conf_item  = str_replace('###VALUE###', $value, $conf_item);
        // Get the item selected (or not selected)
        $arr_piVar  = $this->pObj->piVars;
          // dwildt, 110102
        //$conf_item  = $this->pObj->oblFltr3x->get_wrappedItemSelected($value, $arr_piVar, $conf_selected, $conf_item);
        $tmp_value = null;
        if($value !== 0)
        {
          if(isset($arr_piVar['sort']))
          {
            list($value_field, $value_order) = explode(':', $value);
            list($piVar_field, $piVar_order) = explode(':', $arr_piVar['sort']);
              // 120915, dwildt, 2+
            unset( $value_order );
            unset( $piVar_order );
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('template 1971', $value_field == $piVar_field);
            if($value_field == $piVar_field)
            {
              $tmp_value = $arr_piVar['sort'];
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('template 1976', $value, $tmp_value);
            }
          }
        }
        $conf_item  = $this->pObj->oblFltr3x->get_wrappedItemSelected(null, $tmp_value, $arr_piVar, $arr_ts, $conf_selected, $conf_item);
          // Wrap the value
        $conf_item  = str_replace('|', $label, $conf_item);
        $conf_item  = $conf_item."\n";
        $str_html   = $str_html.$str_space_left.$conf_item;
        $int_counter_element++;
      }
        // Loop through the rows of the SQL result
        // Delete the last line break
      $str_html = substr($str_html, 0, -1);

        // Wrap all items / the object
        // #8337, 101011, dwildt
      $conf_object    = $this->pObj->oblFltr3x->wrap_allItems($obj_ts, $arr_ts, $str_nice_piVar, $key_piVar, count($arr_values));
      $str_html       = str_replace('|', "\n".$str_html."\n".$str_space_left, $conf_object);
        // Wrap the object title
      $conf_wrap      = $this->pObj->oblFltr3x->wrap_objectTitle($arr_ts, $conf_tableField);
        // Wrap the object
      if ($conf_wrap)
      {
        $str_html = str_replace('|', "\n".$str_html."\n".$str_space_left, $conf_wrap);
      }
        // Wrap all items / the object


        ////////////////////////////////////////////////////////////////////////////////
        //
        // Wrap the form
        #10204, dwildt, 101012

        // Form HTML class
      $str_class = $this->lDisplayList['selectBox_orderBy.']['selectbox.']['form.']['class'];
      if(!empty($str_class))
      {
        $arr_marker_option['###CLASS###'] = ' class="'.$str_class.'"';
      }
      if(empty($str_class))
      {
        $arr_marker_option['###CLASS###'] = null;
      }
        // Form HTML class

        // Form legend stdWrap
      $arr_legend = $this->lDisplayList['selectBox_orderBy.']['selectbox.']['form.']['legend_stdWrap.'];
      $str_legend = $this->pObj->objWrapper->general_stdWrap($arr_legend['value'], $arr_legend);
      $arr_marker_option['###LEGEND###'] = $str_legend;
        // Form legend stdWrap

        // Form submit button stdWrap
      $arr_button = $this->lDisplayList['selectBox_orderBy.']['selectbox.']['form.']['button_stdWrap.'];
      $str_button = $this->pObj->objWrapper->general_stdWrap($arr_button['value'], $arr_button);
      $arr_marker_option['###BUTTON###'] = $str_button;
        // Form submit button stdWrap

        // Form action (URL without any parameter)
      $arr_tmp = $this->pObj->piVars;
      unset($this->pObj->piVars);
      $str_url_wo_piVars = $this->pObj->pi_linkTP_keepPIvars_url(null, $this->pObj->boolCache);
      $this->pObj->piVars = $arr_tmp;
      $arr_marker_option['###URL###'] = $str_url_wo_piVars;
        // Form action (URL without any parameter)

        // Form action (URL without any parameter)
      $str_hidden = null;
      $str_param  = null;
      foreach((array) $this->pObj->piVars as $key => $values)
      {
        $piVar_key = $this->pObj->prefixId.'['.$key.']';
        if(is_array($values))
        {
          foreach((array) $values as $value)
          {
              // #29186, 110823, dwildt
            //if(empty($value))
            if( $value == null )
            {
              $str_hidden = $str_hidden."\n".$str_space_left.'<input type="hidden" name="'.$piVar_key.'" value="'.$value.'">';
//              $str_param  = $str_param.'&'.$piVar_key.'='.$value;
            }
          }
        }
          // #29186, 110823, dwildt
        //if(!is_array($values) && !empty($values))
        if( ! is_array( $values ) && ! ( $values == null ) )
        {
          $str_hidden = $str_hidden."\n".$str_space_left.'<input type="hidden" name="'.$piVar_key.'" value="'.$values.'">';
//          $str_param  = $str_param.'&'.$piVar_key.'='.$value;
        }
      }
//      if(!empty($str_param))
//      {
//        $cHash_md5  = $this->pObj->objZz->get_cHash($str_param);
//        $str_hidden = $str_hidden."\n".$str_space_left.'<input type="hidden" name="cHash" value="'.$cHash_md5.'">';
//      }
        // 120915, dwildt, 1+
      unset( $str_param );

      $arr_marker_option['###HIDDEN###'] = $str_hidden;
        // Form action (URL without any parameter)

        #10204, dwildt, 101012
        // Wrap the form



        ////////////////////////////////////////////////////////////////////////////////
        //
        // Add Javascript
        #10204, dwildt, 101012

        // DRS - Development Reporting System
      if ($this->pObj->b_drs_search || $this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/SEARCH+JSS] Selectbox for ordering is enabled. It needs jQuery.', $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System

        // We need jQuery. Load
        // name has to correspondend with similar code in tx_browser_pi1.php
      $name                 = 'jQuery';
        // 120915, dwildt, 1-
      //$bool_success_jQuery  = $this->pObj->objJss->load_jQuery();
        // 120918, dwildt, 1+
      $bool_success_jQuery  = $this->pObj->objJss->load_jQuery();

      if ($this->pObj->objFlexform->bool_ajax_enabled)
      {
          // name has to correspondend with similar code in tx_browser_pi1.php
        $name         = 'ajaxLL';
        $path         = $this->pObj->conf['javascript.']['ajax.']['fileLL'];
        $path_tsConf  = 'javascript.ajax.fileLL';
        $bool_success = $this->pObj->objJss->addJssFile($path, $name, $path_tsConf);
          // name has to correspondend with similar code in tx_browser_pi1.php
        $name         = 'ajax';
        $path         = $this->pObj->conf['javascript.']['ajax.']['file'];
        $path_tsConf  = 'javascript.ajax.file';
        $bool_success = $this->pObj->objJss->addJssFile($path, $name, $path_tsConf);
      }

        // Adding Browser General JSS file
      $name         = 'general';
      $path         = $this->pObj->conf['javascript.']['general.']['file'];
      $path_tsConf  = 'javascript.general.file';
      $bool_success = $this->pObj->objJss->addJssFile($path, $name, $path_tsConf);
        // Adding Browser General JSS file

        // Add Javascript



      $templateMarker = $this->lDisplayList['selectBox_orderBy.']['templateMarker'];
      $selectBox      = $this->pObj->cObj->getSubpart($this->pObj->str_template_raw, $templateMarker);

        // DRS - Development Reporting System
      if(empty($selectBox))
      {
        if ($this->pObj->b_drs_templating || $this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/TEMPLATING] '.$templateMarker.' is empty or missing!', $this->pObj->extKey, 3);
          t3lib_div::devlog('[INFO/TEMPLATING] Please take care of your HTML code.', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      $arr_marker_option['###SELECTBOX###'] = $str_html;
      $selectBox      = $this->pObj->cObj->substituteMarkerArray($selectBox, $arr_marker_option);
      $items          = $selectBox;
        // DRS - Development Reporting System
//if(t3lib_div::_GP('dev')) var_dump('template 1893', $selectBox);
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] '.$templateMarker.' will be rendered as the select box for ordering records.', $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
    }
      // Building the select box for ordering records

    $listHead = $this->pObj->cObj->getSubpart($template, '###LISTHEAD###');
    $listHead = $this->pObj->cObj->substituteSubpart($listHead, '###LISTHEADITEM###', $items, true);
    $template = $this->pObj->cObj->substituteSubpart($template, '###LISTHEAD###', $listHead, true);
    // Building the table head or the select box for ordering



      ////////////////////////////////////////
      //
      // Return the template with the table head

    return $template;
      // Return the template with the table head
  }







  /**
 * Building a row out of the given record
 *
 * @param	array		The SQL row (elements)
 * @param	string		The subpart marker, which is the template for a row
 * @param	string		Template
 * @return	string		FALSE || HTML string
 * @version 3.9.6
 * @since 1.0.0
 */
  function tmplRows($elements, $subpart, $template)
  {
    
    static $bool_firstLoop = true;
    
      // Get the global $arrHandleAs array
    $handleAs                   = $this->pObj->arrHandleAs;
      // [Boolean] Shouldn't empty values handled?
    $bool_dontHandleEmptyValues = $this->pObj->objFlexform->bool_dontHandleEmptyValues;
    //var_dump('template 2218', $bool_dontHandleEmptyValues);



      //////////////////////////////////////////////////////////////////
      //
      // Handle empty values?

      // 110111, dwildt, #11603
    if($bool_dontHandleEmptyValues)
    {
      $str_elements = implode('', $elements);
        // RETURN: All elements are empty, the current row is empty
        // #29186, 110823, dwildt
      //if(empty($str_elements))
      if($str_elements == null )
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] Row is empty. RETURN false.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] If empty rows should handled, please configure the plugin/flexform [list] field emptyValues.dontHandle = 0.', $this->pObj->extKey, 1);
        }
        $htmlRow = false;
        return $htmlRow;
        // RETURN: All elements are empty, the current row is empty
      }
    }
      // Handle empty values?



      //////////////////////////////////////////////////////////////////
      //
      // Should swords get an HTML wrap in results?

      // Get the local or gloabl autoconfig array - #9879
    $lAutoconf = $this->conf_view['autoconfig.'];
    if (!is_array($lAutoconf))
    {
      if( $bool_firstLoop )
      {
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] views.single|list.X. hasn\'t any autoconf array.<br />
            We take the global one.', $this->pObj->extKey, 0);
        }
      }
      $lAutoconf = $this->pObj->conf['autoconfig.'];
    }
      // Get the local or gloabl autoconfig array- #9879

      // 120915, dwildt, 1-
    //$arr_TCAitems = $lAutoconf['autoDiscover.']['items.'];
      // Get the TCA properties from the TypoScript
    $bool_dontColorSwords = false;
      // Should swords get an HTML wrap in results?



      //////////////////////////////////////////////////////////////////
      //
      // Get the local or the global displaySingle or displayList array

    $lDisplayType = $this->pObj->lDisplayType;
    $lDisplayView = $this->conf_view[$lDisplayType];
    if (!is_array($lDisplayView))
    {
      $lDisplayView = $this->pObj->conf[$lDisplayType];
    }
      // Get the local or the global displaySingle or displayList array



      // We need it for the class property 'last'
    $maxColumns = count($elements) - 1;

      // Get the uid field
    $uidField = $this->pObj->arrLocalTable['uid'];

      // SQL manual mode: Display uid only, if it isn't the first element
    $extraUidField  = false;
    if ($this->pObj->b_sql_manual)
    {
      if ($uidField != '')
      {
        $extraUidField = true;
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] SQL manual mode:<br />
           If '.$uidField.' will be the first field in the row, it will deleted.', $this->pObj->extKey, 0);
        }
      }
    }
      // SQL manual mode: Display uid only, if it isn't the first element

      // We want the prompt of a missing single view only once
      // 120915, dwildt, 1-
    //$boolMissingSingleView = true;

    $this->pObj->boolFirstElement = true;

    $i_count_element  = 0;
      // Counts the elements of the row
    $i_count_cell   = 0;
      // Counts the printed cells like <td>



      //////////////////////////////////////////////////////////////////
      //
      // Default Design

    $bool_design_default   = true;
      // List view with default design
    if($this->pObj->view == 'list')
    {
      $tmpl_element = $this->pObj->cObj->getSubpart($template, '###LISTBODYITEM###');
      $pos = strpos($tmpl_element, '###ITEM###');
      if ($pos === false)
      {
        $bool_design_default = false;
          // DRS - Development Reporting System
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] ###LISTBODYITEM### without ###ITEM###<br />
           The Browser process an individual design with TABLE.FIELD markers.', $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
      }
      if (!($pos === false))
      {
          // DRS - Development Reporting System
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] ###LISTBODYITEM### contains ###ITEM###<br />
           The Browser process the default design with rows.', $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
      }
    }
      // List view with default design
      // Single view with default design
    if($this->pObj->view == 'single')
    {
      $tmpl_element = $this->pObj->cObj->getSubpart($template, '###SINGLEBODYROW###');
      $pos = strpos($tmpl_element, '###VALUE###');
      if ($pos === false)
      {
        $bool_design_default = false;
          // DRS - Development Reporting System
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] ###SINGLEBODYROW### without ###VALUE###<br />
           The Browser process an individual design with TABLE.FIELD markers.', $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
      }
      if (!($pos === false))
      {
          // DRS - Development Reporting System
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] ###SINGLEBODYROW### contains ###VALUE###<br />
           The Browser process the default design with rows.', $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
      }
    }
      // Single view with default design
      // Default Design



      //////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->boolFirstRow)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] Before elements loop (first row): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////////
      //
      // Loop through all elements

    $c                    = 0;
    $htmlRow              = false;
    $bool_drs_handleCase  = false;

      // 110829, dwildt-
//    $markerArray  = $this->pObj->objWrapper->constant_markers();
      // 110829, dwildt+
    $this->tmpl_marker( );
    $markerArray = $this->markerArray;
      // 110829, dwildt+

      // #12723, mbless, 110310
    $this->_elements                = $elements;
    $this->hook_template_elements();
    $elements                       = $this->_elements;
    unset($this->_elements);
    $this->_elementsTransformed     = array();
    $this->_elementsBoolSubstitute  = array();
      // #12723, mbless, 110310

      // LOOP elements
    foreach( ( array ) $elements as $key => $value )
    {
      $boolSubstitute       = true;
      $bool_dontColorSwords = false;
        // 120915, dwildt, 1-
      //list($table, $field)  = explode('.', $key);

        // Handle empty values?
      if( $bool_dontHandleEmptyValues )
      {
        if( $this->pObj->view == 'single' )
        {
            // #29186, 110823, dwildt
          //if(empty($value))
          if( $value == null )
          {
            if ( $this->pObj->b_drs_templating )
            {
              $prompt =  $key . ' is empty. It won\'t handled!';
              t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
              $prompt = 'If empty values should handled, please configure the plugin/flexform [list] ' .
                        'field emptyValues.dontHandle = 0.';
              t3lib_div::devLog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
            }
            continue;
          }
        }
      }
        // Handle empty values?
      
      if( $handleAs['image'] )
      {
        switch( true )
        {
          case( $key == $handleAs['imageCaption'] ):
          case( $key == $handleAs['imageAltText'] ):
          case( $key == $handleAs['imageTitleText'] ):
            continue 2;
            break;
        }
      }


        // 120129, dwildt+
//if( $key == 'tx_org_downloads.documents' )
//{
//  $pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//  if (!($pos === false))
//  {
//    var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')', $key, $value );
//  }
//}
//if( $key == 'tx_org_downloads.thumbnail' )
//{
//  $pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//  if (!($pos === false))
//  {
//    var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')', $key, $value );
//  }
//}
      $arr_result = $this->pObj->objTca->handleAs(
                      $key,
                      $value,
                      $lDisplayView,
                      $bool_drs_handleCase,
                      $bool_dontColorSwords,
                      $elements,
                      $maxColumns,
                      $boolSubstitute
                    );
      $value                = $arr_result['data']['value'];
      $bool_drs_handleCase  = $arr_result['data']['drs_handleCase'];
      $bool_dontColorSwords = $arr_result['data']['dontColorSwords'];
      $maxColumns           = $arr_result['data']['maxColumns'];
      $boolSubstitute       = $arr_result['data']['boolSubstitute'];
//if( $key == 'tt_news.image' )
//{
//  $this->pObj->dev_var_dump( $handleAs, $value );
//}
        // 120129, dwildt+
//if( $key == 'tx_org_downloads.documents' )
//{
//  $pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//  if (!($pos === false))
//  {
//    var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')', $key, $value );
//  }
//}
//if( $key == 'tx_org_downloads.thumbnail' )
//{
//  $pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//  if (!($pos === false))
//  {
//    var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')', $key, $value );
//  }
//}

        // First field is UID and we have a list view
      if ($extraUidField && $i_count_element == 0 && $this->view == 'list')
      {
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          $bool_drs_handleCase = true;
          t3lib_div::devLog('[INFO/TEMPLATING] '.$key.' is removed, because it is the first value in the row and it is the uid of the local table record.', $this->pObj->extKey, 0);
        }
        $bool_drs_handleCase = true;
        $maxColumns--;
        $boolSubstitute = false;
      }
        // First field is UID and we have a list view

        // Remove fields, which shouldn't displayed
      if( in_array( $key, ( array ) $this->arr_rmFields ) )
      {
        if( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
        {
          $bool_drs_handleCase = true;
          $prompt = $key . ' is in the list of fields, which shouldn\'t displayed.';
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
        }
        $maxColumns--;
        $boolSubstitute = false;
      }
        // Remove fields, which shouldn't displayed

        // DRS - Performance
      if( $this->pObj->boolFirstRow && ( $i_count_element == 0 ) )
      {
        if( $this->pObj->b_drs_perform )
        {
          if( $this->pObj->bool_typo3_43 )
          {
            $endTime = $this->pObj->TT->getDifferenceToStarttime( );
          }
          if( ! $this->pObj->bool_typo3_43 )
          {
            $endTime = $this->pObj->TT->mtime( );
          }
          $prompt = 'After removing fields 1: '. ($endTime - $this->pObj->tt_startTime).' ms';
          t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
        // DRS - Performance

        // Remove fields, which where added because of missing uid and pid
      $addedTableFields = $this->pObj->arrConsolidate['addedTableFields'];
      if ( in_array( $key, ( array ) $addedTableFields ) )
      {
        if( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
        {
          $bool_drs_handleCase = true;
          $prompt = $key.' is in the uid/pid list of the consolidation array. It shouldn\'t displayed.';
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
        }
        $maxColumns--;
        $boolSubstitute = false;
      }
        // Remove fields, which where added because of missing uid and pid

        // DRS - Performance
      if( $this->pObj->boolFirstRow && ( $i_count_element == 0 ) )
      {
        if( $this->pObj->b_drs_perform )
        {
          if( $this->pObj->bool_typo3_43 )
          {
            $endTime = $this->pObj->TT->getDifferenceToStarttime( );
          }
          if( ! $this->pObj->bool_typo3_43 )
          {
            $endTime = $this->pObj->TT->mtime( );
          }
          $prompt = 'After removing fields 2: '. ($endTime - $this->pObj->tt_startTime).' ms';
          t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->pObj->extKey, 0);
        }
      }
        // DRS - Performance

        // DRS- Developement Reporting System: Any Case didn't matched above
      if( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
      {
        if( ! $bool_drs_handleCase )
        {
          $prompt = 'There isn\'t any handle as case for ' . $key . '.';
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
          $prompt ='If you want a handle as case, please configure the handleAs array.';
          t3lib_div::devLog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
        }
      }
        // DRS- Developement Reporting System: Any Case didn't matched above

        // Colors the sword words and phrases
        // 3.3.4
      //if(t3lib_div::_GP('dev')) var_dump('template 2196', $key, $bool_dontColorSwords);
      if ( ! $bool_dontColorSwords)
      {
        $value = $this->pObj->objZz->color_swords($key, $value);
      }
        // Colors the sword words and phrases

      $this->pObj->boolFirstElement = false;
        //if(t3lib_div::_GP('dev')) var_dump('template 2206', $elements);
        // Bugfix, 3.3.7, 100617, dwildt
      $this->pObj->elements = $elements;

$GLOBALS['TSFE']->register[$this->pObj->extKey.'_numElement'] = $i_count_element;
      $value = $this->pObj->objWrapper->wrapAndLinkValue($key, $value, $elements[$uidField]);

      // DRS - Performance
      if ($this->pObj->boolFirstRow && $i_count_element == 0)
      {
        if ($this->pObj->b_drs_perform) {
          if($this->pObj->bool_typo3_43)
          {
            $endTime = $this->pObj->TT->getDifferenceToStarttime();
          }
          if(!$this->pObj->bool_typo3_43)
          {
            $endTime = $this->pObj->TT->mtime();
          }
          t3lib_div::devLog('[INFO/PERFORMANCE] After wrap and link value: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
        }
      }
        // DRS - Performance

        // #12723, mbless, 110310
      $this->_elementsTransformed[$key]     = $value;
      $this->_elementsBoolSubstitute[$key]  = $boolSubstitute;

        // #36704, dwildt, 120429, 1+
      $i_count_element++;
    }
      // LOOP elements

    $this->hook_template_elements_transformed();

      // #28562: 110830, dwildt+
    $counter_td       = 0;
    $max_td           = $this->max_elements - 1;
    $addedTableFields = $this->pObj->arrConsolidate['addedTableFields'];
      // #28562: 110830, dwildt+

    foreach ($this->_elementsTransformed as $key => $value)
    {
        // 4.1.13, 120920, dwildt, 1+
      $GLOBALS['TSFE']->register[$this->pObj->extKey.'_positionColumn'] = $counter_td;
//$this->pObj->dev_var_dump( $this->pObj->extKey.'_positionColumn', $GLOBALS['TSFE']->register[$this->pObj->extKey.'_positionColumn']);
      $boolSubstitute = $this->_elementsBoolSubstitute[$key];
        // #12723, mbless, 110310

        // #28562: 110830, dwildt+
      if ( in_array( $key, (array) $addedTableFields ) )
      {
        continue;
      }
      if( in_array( $key, (array) $this->arr_rmFields ) )
      {
        continue;
      }
        // #28562: 110830, dwildt+

        // csv export: move value to a proper csv value
        // #29370, 110831, dwildt+
      $value = $this->pObj->objExport->csv_value( $value );

        // Substitute the template marker
      if ( $boolSubstitute )
      {
          // #28562: 110830, dwildt+
        $markerArray['###TD_COUNTER###']  = $counter_td;
        switch(true)
        {
          case( $counter_td == 0 ):
            $markerArray['###TD_FIRST_LAST###'] = 'first';
            break;
          case( $counter_td >=  $max_td ):
            $markerArray['###TD_FIRST_LAST###'] = 'last';
            break;
          default:
            $markerArray['###TD_FIRST_LAST###'] = null;
            break;
        }
        $markerArray['###TD_EVEN_OR_ODD###']  = $counter_td%2 ? $this->oddClassColumns : null;
        $counter_td++;
          // #28562: 110830, dwildt+

        $htmlSubpart = $this->pObj->cObj->getSubpart($template, $subpart);
        if($this->view == 'list' && $bool_design_default)
        {
          $class = $i_count_cell < $maxColumns ? 'cell-'.$i_count_cell : 'cell-'.$i_count_cell.' last';
          $markerArray['###CLASS###'] = ' class="'.$class.'"';
          $markerArray['###ITEM###']  = $value;
          $bool_defaultTemplate = true;
          $markerArray['###SOCIALMEDIA_BOOKMARKS###'] = $this->pObj->objSocialmedia->get_htmlBookmarks($elements, $key, $bool_defaultTemplate);
          $htmlRow  .= $this->pObj->cObj->substituteMarkerArray($htmlSubpart, $markerArray);
          //var_dump('template 2256');
        }
        if($this->view == 'list' && !$bool_design_default)
        {
          $markerArray['###'.strtoupper($key).'###']  = $value;
          $bool_defaultTemplate = false;
          $markerArray['###SOCIALMEDIA_BOOKMARKS###'] = $this->pObj->objSocialmedia->get_htmlBookmarks($elements, $key, $bool_defaultTemplate);
          $htmlRow  = $this->pObj->cObj->substituteMarkerArray($htmlSubpart, $markerArray);
        }
        if($this->view == 'single' && $bool_design_default)
        {
          $markerArray['###CLASS###'] = $c++%2 ? ' class="odd"' : '';
          $markerArray['###FIELD###'] = $this->pObj->objZz->getTableFieldLL($key);
          $markerArray['###VALUE###'] = $value;
          $bool_defaultTemplate = true;
          $markerArray['###SOCIALMEDIA_BOOKMARKS###'] = $this->pObj->objSocialmedia->get_htmlBookmarks($elements, $key, $bool_defaultTemplate);
          $htmlRow  .= $this->pObj->cObj->substituteMarkerArray($htmlSubpart, $markerArray);
        }
        if($this->view == 'single' && !$bool_design_default)
        {
          $markerArray['###'.strtoupper($key).'###']  = $value;
          $bool_defaultTemplate = false;
          $markerArray['###SOCIALMEDIA_BOOKMARKS###'] = $this->pObj->objSocialmedia->get_htmlBookmarks($elements, $key, $bool_defaultTemplate);
          $htmlRow  = $this->pObj->cObj->substituteMarkerArray($htmlSubpart, $markerArray);
        }
        $i_count_cell++;
      }
        // Substitute the template marker

        // DRS - Performance
      if ($this->pObj->boolFirstRow && $i_count_element == 0)
      {
        if ($this->pObj->b_drs_perform) {
          if($this->pObj->bool_typo3_43)
          {
            $endTime = $this->pObj->TT->getDifferenceToStarttime();
          }
          if(!$this->pObj->bool_typo3_43)
          {
            $endTime = $this->pObj->TT->mtime();
          }
          t3lib_div::devLog('[INFO/PERFORMANCE] After substitute marker: '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
        }
      }
        // DRS - Performance

        // #36704, dwildt, 120429, 1-
      //$i_count_element++;
    }
      // dwildt, 120915, 1+
    unset( $max_td );
        // 4.1.13, 120920, dwildt, 1+
    $GLOBALS['TSFE']->register[$this->pObj->extKey.'_positionColumn'] = null;
      // Loop through all elements

      // #12723, mbless, 110310
    unset ($this->_elementsTransformed);
    unset ($this->_elementsBoolSubstitute);
      // #12723, mbless, 110310



      //////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->pObj->boolFirstRow)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After elements loop (first row): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



    $this->pObj->boolFirstRow = false;
//$pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false))
//{
//  var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')',  $htmlRow );
//}


    $bool_firstLoop = false;
    
    return $htmlRow;
  }









  /**
 * cal_marker(): Set some global marker
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function tmpl_marker( )
  {
      // Set marker
    $this->markerArray['###MODE###']  = $this->pObj->piVar_mode;
    $this->markerArray['###VIEW###']  = $this->pObj->view;
    $this->markerArray                = $this->pObj->objMarker->extend_marker_wi_cObjData( $this->markerArray );
    $markerArray                      = $this->pObj->objWrapper->constant_markers( );
    foreach( (array) $markerArray as $key => $value)
    {
      $this->markerArray[$key] = $value;
    }
      // Set marker
  }









 /**
  * tmpl_rmFields( ):  Get the field names, which should not displayed.
  *                    Set the global arr_rmFields
  *
  * @return	void
  * @version 4.0.0
  * @since 4.0.0
  */
  private function tmpl_rmFields( )
  {
      // RETURN global $arr_rmFields is set
    if( is_array( $this->arr_rmFields ) )
    {
      return;
    }
      // RETURN global $arr_rmFields is set
    $conf_rmFields      = $this->conf_view['functions.']['clean_up.']['csvTableFields'];
    $arr_rmFields       = $this->pObj->objZz->getCSVasArray($conf_rmFields);
    $lArr_RmFields[0]   = array_flip($arr_rmFields);
    $arr_result         = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields($lArr_RmFields);
    $lArr_RmFields      = $arr_result['data']['rows'];
    $this->arr_rmFields = ( array ) $arr_rmFields;
  }









  /***********************************************
  *
  * GroupBy
  *
  **********************************************/



/**
 * Verifying the GROUPBY configuration. If groupby isn't configured in TypoScript, GROUPBY marker in HTML
 * template will be removed. If groupby is configured in TypoScript, but the template hasn't any GROUPBY marker
 * there will be a log in devlog.
 *
 * @param	string		$template: The HTML template with the GROUPBY-markers
 * @return	string		$template: The HTML template with or without the GROUPBY-by-markers
 */
  function groupBy_verify($template)
  {
    // Do we have a TypoScript group by configuration?
    $this->bool_groupby = false;
    if(isset($this->pObj->conf_sql['groupBy']) && $this->pObj->conf_sql['groupBy'] != '')
    {
      $this->bool_groupby = true;
    }
    // Do we have a TypoScript group by configuration?


    // RETURN if we have an HTML without any groupby marker
    if(strpos($template, '###GROUPBY###') === false)
    {
      if($this->bool_groupby)
      {
        // DRS- Developement Reporting System
        if ($this->pObj->b_drs_templating || $this->pObj->b_drs_error)
        {
          t3lib_div::devLog('[ERROR/TEMPLATING] TypoScript is configured with groupby. '.
            'But your template doesn\'t contain any marker like ###GROUPBY###', $this->pObj->extKey, 3);
          t3lib_div::devLog('[WARN/TEMPLATING] Your data won\'t grouped.', $this->pObj->extKey, 2);
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure your HTML template.', $this->pObj->extKey, 1);
        }
        // DRS- Developement Reporting System
      }
      return $template;
    }
    // RETURN if we have an HTML without any groupby marker


    // Edit the template
    // Don't change anything
    if($this->bool_groupby)
    {
      // Do nothing
      // DRS- Developement Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[OK/TEMPLATING] TypoScript is configured with groupby. '.
          'And the template contains the marker ###GROUPBY###.', $this->pObj->extKey, -1);
      }
      // DRS- Developement Reporting System
    }
    // Don't change anything
    // Remove all GROUPBY marker
    if(!$this->bool_groupby)
    {
      // Remove all groupby markers
      $template = $this->groupBy_remove($template);
      // DRS- Developement Reporting System
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] TypoScript is configured without groupby. '.
          'All markers ###GROUPBY### will removed in the template.', $this->pObj->extKey, 0);
      }
      // DRS- Developement Reporting System
    }
    // Remove all GROUPBY marker
    // Edit the template

    return $template;
  }








/**
 * Remove all GROUPBY marker
 *
 * @param	string		$template: The HTML template with the groupby-markers
 * @return	string		$template: The HTML template without the groupby-markers
 */
  function groupBy_remove($template)
  {
    $template = $this->pObj->cObj->substituteSubpart($template, '###GROUPBYHEAD###', '', true);
    $template = str_replace('<!-- ###GROUPBY### begin -->',     '', $template);
    $template = str_replace('<!-- ###GROUPBY### end -->',       '', $template);
    $template = str_replace('<!-- ###GROUPBYBODY### begin -->', '', $template);
    $template = str_replace('<!-- ###GROUPBYBODY### end -->',   '', $template);
    return $template;
  }








/**
 * Get the name of the group in the current record, if there is one.
 *
 * @param	array		$elements: The current record
 * @return	string		$str_return: Value of the group field. FALSE, if we aren't in group mode
 */
  function groupBy_get_groupname($elements)
  {
    $str_value = false;

    if($this->bool_groupby)
    {
      $str_tableField = trim($this->pObj->objSqlFun_3x->get_orderBy_tableFields($this->pObj->conf_sql['groupBy']));
      $str_value      = $elements[$str_tableField];
    }
    return $str_value;
  }








/**
 * groupBy_stdWrap: Wrap the group name, if it has a stdWrap
 *
 * @param	array		$elements: The current record
 * @return	string		$str_return: Value of the group field wrapped by stdWrap if we have a TSconfig
 */
  function groupBy_stdWrap($elements)
  {
    // THIS is a method with a general task. todo: Generalie this method. dwildt, 100615




    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];

    $lConfCObj  = false;
    $str_value  = false;

    // Get value from SQL table
    $str_tableField      = trim($this->pObj->objSqlFun_3x->get_orderBy_tableFields($this->pObj->conf_sql['groupBy']));
    list($table, $field) = explode('.', $str_tableField);
    $str_value           = $elements[$table.'.'.$field];
    // Get value from SQL table

    // Do we have a stdWrap?
    $bool_stdWrap = true;
    if(!isset($conf_view[$table.'.'][$field]))
    {
      $bool_stdWrap = false;
    }
    if(!is_array($conf_view[$table.'.'][$field.'.']))
    {
      $bool_stdWrap = false;
    }
    // Do we have a stdWrap?

    // RETURN without stdWrap
    if(!$bool_stdWrap)
    {
      // DRS- Developement Reporting System
      if ($this->pObj->boolFirstRow && ($this->pObj->b_drs_templating || $this->pObj->b_drs_sql))
      {
        t3lib_div::devLog('[INFO/TEMPLATING+SQL] GroupBy field '.$str_tableField.' hasn\'t any stdWrap.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING+SQL] If you want a stdWrap, please configure '.$str_tableField, $this->pObj->extKey, 1);
      }
      // DRS- Developement Reporting System: Any Case didn't matched above
      return $str_value;
    }
    // RETURN without stdWrap

    // RETURN with stdWrap
    $lConfCObj['10']  = $conf_view[$table.'.'][$field];
    $lConfCObj['10.'] = $conf_view[$table.'.'][$field.'.'];
    $lConfCObj = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $elements);
    $str_value = $this->pObj->objWrapper->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($lConfCObj, $ext=''), false);

    return $str_value;
    // RETURN with stdWrap
  }
















  /***********************************************
  *
  * Handle As
  *
  **********************************************/



/**
 * Wraps field values in respect to the TypoScript configuration an the handleAs cases
 *
 * @param array   $elements: SQL row
 * @param array   $handleAs: Array with the fieldnames which have a special handling like title, images or documents
 * @param array   $markerArray: Array with the current markers
 * @return  array   $markerArray: Array with the current markers
 * 
 * @version 3.9.18
 * @since 1.0.0
 */
  function render_handleAs($elements, $handleAs, $markerArray)
  {

    /////////////////////////////////////////
    //
    // RETURN without elements

    if(!is_array($elements))
    {
      return $markerArray;
    }
    if(count($elements) < 1)
    {
      return $markerArray;
    }
    // RETURN without elements


    $displayTitle = $this->pObj->lDisplay['title'];
    $rows         = $this->pObj->rows;
    $bool_nRows   = false;
    if (count($rows) > 1)
    {
      $bool_nRows = true;
    }

    /////////////////////////////////////////
    //
    // Wrap all elements

    foreach((array) $elements as $tableField => $value)
    {

      $b_is_rendered  = false;

      /////////////////////////////////////////
      //
      // Handle the TITLE

      if ($displayTitle && $tableField == $handleAs['title'])
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] '.$handleAs['title'].' will be handled as the title.', $this->pObj->extKey, 0);
          t3lib_div::devLog('[HELP/TEMPLATING] Please configure displaySingle.display.title = 0, if you don\'t want any title handling.', $this->pObj->extKey, 1);
        }
        $value                                             = $this->pObj->objWrapper->wrapAndLinkValue($tableField, $value, 0);
        $markerArray['###TITLE###']                        = $value;
        $markerArray['###'.strtoupper($tableField).'###']  = $value;

        $b_is_rendered = true;
      }
      // Handle the TITLE


      /////////////////////////////////////////
      //
      // Handle the IMAGE

      if ($tableField == $handleAs['image'])
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] The field \''.$handleAs['image'].'\' will be wrapped as an IMAGE.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TEMPLATING] The system marker ###IMAGE### will be replaced.', $this->pObj->extKey, 0);
        }
        $tsImage['image']           = $elements[$handleAs['image']];
        $tsImage['imagecaption']    = $elements[$handleAs['imageCaption']];
        $tsImage['imagealttext']    = $elements[$handleAs['imageAltText']];
        $tsImage['imagetitletext']  = $elements[$handleAs['imageTitleText']];
        $value                      = $this->pObj->objWrapper->wrapImage($tsImage);
        $markerArray['###IMAGE###']                       = $value;
        $markerArray['###'.strtoupper($tableField).'###'] = $value;

        $b_is_rendered = true;
      }
      // Handle the IMAGE


      /////////////////////////////////////////
      //
      // Handle the DOCUMENT

      //:todo: Handle the document


      /////////////////////////////////////////
      //
      // Process all the rest of the elements

      if (!$b_is_rendered)
      {
        $value        = false;
        list($table, $field)  = explode('.', $tableField);
        // Store the id of the previous element.
        $int_last_uid     = false;

        // Loop through all rows
          // 120915, dwildt, 1-
        //foreach( ( array ) $rows as $lRow => $lElements )
          // 120915, dwildt, 1+
        foreach( ( array ) $rows as $lElements )
        {
          // Store the current id of the current element.
          $int_cur_uid = $lElements[$table.'.uid'];
          if (!$int_cur_uid)
          {
            // Store -1, if current element has no uid (has no uid field in the SELECT statement)
            $int_cur_uid = -1;
          }
          // Wrap the element and append it, if it has different id
          if($int_last_uid != $int_cur_uid)
          {
            $value = $value.$this->pObj->objWrapper->wrapAndLinkValue($tableField, $lElements[$tableField], 0);
          }
          // Store the id as id of the previous element.

          if($int_last_uid == $int_cur_uid)
          {
            $value = $this->pObj->objWrapper->wrapAndLinkValue($tableField, $lElements[$tableField], 0);
          }
          $int_last_uid = $int_cur_uid;
        }
        // Loop through all rows

        // Process the TS extensions.browser.wrapAll
        if ($value)
        {
          $conf_wrapHeader  = $this->conf_view[$table.'.'][$field.'.']['extensions.']['browser.']['wrapAll.']['header.'];
          $lHeader          = $this->pObj->objWrapper->general_stdWrap(false, $conf_wrapHeader);
          $conf_wrapAll     = $this->conf_view[$table.'.'][$field.'.']['extensions.']['browser.']['wrapAll.']['stdWrap.'];
          $value            = $this->pObj->objWrapper->general_stdWrap($value, $conf_wrapAll);
          $value            = $lHeader.$value;
        }
        // Process the TS extensions.browser.wrapAll

        $lMarker               = '###'.strtoupper($tableField).'###';
        $markerArray[$lMarker] = $value;
      }
      // Process all the rest of the elements
    }
    // Wrap all elements


    return $markerArray;
  }

  
  
//  /**
// * Wraps field values in respect to the TypoScript configuration an the handleAs cases
// *
// * @param	array		$elements: SQL row
// * @param	array		$handleAs: Array with the fieldnames which have a special handling like title, images or documents
// * @param	array		$markerArray: Array with the current markers
// * @return	array		$markerArray: Array with the current markers
// */
//  function render_handleAs($elements, $handleAs, $markerArray)
//  {
//
//    /////////////////////////////////////////
//    //
//    // RETURN without elements
//
//    if(!is_array($elements))
//    {
//      return $markerArray;
//    }
//    if(count($elements) < 1)
//    {
//      return $markerArray;
//    }
//    // RETURN without elements
//
//
//    $displayTitle = $this->pObj->lDisplay['title'];
//    $rows         = $this->pObj->rows;
//    $bool_nRows   = false;
//    if (count($rows) > 1)
//    {
//      $bool_nRows = true;
//    }
//
//$this->pObj->dev_var_dump( $handleAs );    
//    /////////////////////////////////////////
//    //
//    // Wrap all elements
//
//    foreach( ( array ) $elements as $tableField => $value )
//    {
//$this->pObj->dev_var_dump( $tableField );    
//
//      $b_is_rendered  = false;
//
//        /////////////////////////////////////////
//        //
//        // Handle the TITLE
//
//      $bool_title = false;
//      $pos = strpos( $handleAs['title'], $tableField );
//      if( ! $pos === false )
//      {
//        $bool_title = true;
//      }
//      if( $displayTitle && $bool_title )
//      {
//        if( $this->pObj->b_drs_templating )
//        {
//          $prompt = $handleAs['title'].' will be handled as the title.';
//          t3lib_div::devlog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
//          $prompt = 'Please configure displaySingle.display.title = 0, if you don\'t want any title handling.';
//          t3lib_div::devLog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
//        }
//        $value                                             = $this->pObj->objWrapper->wrapAndLinkValue($tableField, $value, 0);
//        $markerArray['###TITLE###']                        = $value;
//        $markerArray['###'.strtoupper($tableField).'###']  = $value;
//
//        $b_is_rendered = true;
//      }
//        // Handle the TITLE
//
//
//
//        /////////////////////////////////////////
//        //
//        // Handle the IMAGE
//
//      $bool_image = false;
//      $pos = strpos( $handleAs['image'], $tableField );
//      if( ! $pos === false )
//      {
//        $bool_title = true;
//      }
//      if( $bool_title )
//      {
//        if( $this->pObj->b_drs_templating )
//        {
//          $prompt = 'The field \''.$handleAs['image'].'\' will be wrapped as an IMAGE.';
//          t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
//          $prompt = 'The system marker ###IMAGE### will be replaced.';
//          t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
//        }
//        $tsImage['image']           = $elements[$tableField];
//        $tsImage['imagecaption']    = $elements[$handleAs['imageCaption']];
//        $tsImage['imagealttext']    = $elements[$handleAs['imageAltText']];
//        $tsImage['imagetitletext']  = $elements[$handleAs['imageTitleText']];
//        $value                      = $this->pObj->objWrapper->wrapImage($tsImage);
//        $markerArray['###IMAGE###']                       = $value;
//        $markerArray['###'.strtoupper($tableField).'###'] = $value;
//
//        $b_is_rendered = true;
//      }
//        // Handle the IMAGE
//
//
//        /////////////////////////////////////////
//        //
//        // Handle the DOCUMENT
//
//      //:todo: Handle the document
//
//
//        /////////////////////////////////////////
//        //
//        // Process all the rest of the elements
//
//      if( ! $b_is_rendered )
//      {
//        $value        = false;
//        list($table, $field)  = explode('.', $tableField);
//        // Store the id of the previous element.
//        $int_last_uid = false;
//
//          // Loop through all rows
//        foreach((array) $rows as $lRow => $lElements)
//        {
//          // Store the current id of the current element.
//          $int_cur_uid = $lElements[$table.'.uid'];
//          if (!$int_cur_uid)
//          {
//            // Store -1, if current element has no uid (has no uid field in the SELECT statement)
//            $int_cur_uid = -1;
//          }
//          // Wrap the element and append it, if it has different id
//          if($int_last_uid != $int_cur_uid)
//          {
//            $value = $value.$this->pObj->objWrapper->wrapAndLinkValue($tableField, $lElements[$tableField], 0);
//          }
//          // Store the id as id of the previous element.
//
//          if($int_last_uid == $int_cur_uid)
//          {
//            $value = $this->pObj->objWrapper->wrapAndLinkValue($tableField, $lElements[$tableField], 0);
//          }
//          $int_last_uid = $int_cur_uid;
//        }
//          // Loop through all rows
//
//          // Process the TS extensions.browser.wrapAll
//        if ($value)
//        {
//          $conf_wrapHeader  = $this->conf_view[$table.'.'][$field.'.']['extensions.']['browser.']['wrapAll.']['header.'];
//          $lHeader          = $this->pObj->objWrapper->general_stdWrap(false, $conf_wrapHeader);
//          $conf_wrapAll     = $this->conf_view[$table.'.'][$field.'.']['extensions.']['browser.']['wrapAll.']['stdWrap.'];
//          $value            = $this->pObj->objWrapper->general_stdWrap($value, $conf_wrapAll);
//          $value            = $lHeader.$value;
//        }
//          // Process the TS extensions.browser.wrapAll
//
//        $lMarker               = '###'.strtoupper($tableField).'###';
//        $markerArray[$lMarker] = $value;
//      }
//        // Process all the rest of the elements
//    }
//      // Wrap all elements
//
//
//    return $markerArray;
//  }










  /**
 * hook_template_elements(): hook to manipulate the elements of a row BEFORE the elements are linked or transformed by typoscript
 *
 * @return	void
 * @author 	Martin Bless
 * @internal 	#12723, mbless, 110310
 */
  function hook_template_elements() {
      // debug($this->_elements,'$this->_elements',__LINE__,__FILE__);
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['BR_TemplateElementsHook'])) {
      foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['BR_TemplateElementsHook'] as $_classRef) {
        $_procObj = & t3lib_div :: getUserObj($_classRef);
        $_procObj->BR_TemplateElementsHook($this);
      }
    }
  }


  /**
 * hook_template_elements_transformed( ): hook to manipulate the elements of a row AFTER the elements are linked or transformed by typoscript
 *
 * @return	void
 * @author 	Martin Bless
 * @internal 	#12723, mbless, 110310
 */
  function hook_template_elements_transformed( ) {
      // debug($this->_elementsTransformed,'$this->_elementsTransformed',__LINE__,__FILE__);
      // debug($this->_elementsBoolSubstitute,'$this->_elementsBoolSubstitute',__LINE__,__FILE__);
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['BR_TemplateElementsTransformedHook'])) {
      foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['BR_TemplateElementsTransformedHook'] as $_classRef) {
        $_procObj = & t3lib_div :: getUserObj($_classRef);
        $_procObj->BR_TemplateElementsTransformedHook($this);
      }
    }
  }



/**
 * updateWizard( ): Checks, if TypoScript of the current view has deprecated properties.
 *                  It is relevant only, if the update wizard is enabled.
 *
 * @param	integer		$uid        : uid of the current item / row
 * @param	string		$value      : value of the current item / row
 * @return	string		$item       : The rendered item
 * @version 3.9.24
 * @since   3.9.24
 */
  private function updateWizard( $check, $lDisplayList )
  {
    if( ! $this->pObj->arr_extConf['updateWizardEnable'] )
    {
      return;
    }
      // Current IP has access
    if( ! $this->pObj->bool_accessByIP )
    {
      return;
    }

    switch( $check )
    {
      case( 'displaySingle.noItemMessage' ):
      case( 'displayList.noItemMessage' ):
        if( $lDisplayList['noItemMessage'] == '1' )
        {
          $prompt_01 = '
            Deprecated: ' . $check . ' = 1<br />
            Please use: <br />
            ' . $check . ' = TEXT<br />
            ';
        }
        if( $prompt_01 )
        {
          echo '
            <div style="border:1em solid red;padding:2em;background:white;">
              <h1>TYPO3 Browser Update Wizard</h1>
            ';
          if( $prompt_01 )
          {
            echo '
                <p>
                  ' . $prompt_01 . '
                </p>
              ';
          }
          echo '
            </div>
            ';
        }
        break;
    }
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_template.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_template.php']);
}

?>