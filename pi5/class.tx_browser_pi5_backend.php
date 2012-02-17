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
 * The class tx_browser_pi1_backend bundles methods for backend support like itemsProcFunc
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage  browser
 * @since 4.0.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   62: class tx_browser_pi5_backend
 *   92:     public function sDef_getArrViewsList($arr_pluginConf)
 *  243:     public function sDEF_getExtensionTemplates($arr_pluginConf)
 *  302:     public function evaluate_externalLinks($arr_pluginConf, $obj_TCEform)
 *  340:     public function evaluate_plugin($arr_pluginConf, $obj_TCEform)
 *  492:     public function day_selectRelative($arr_pluginConf)
 *  654:     public function month_selectRelative($arr_pluginConf)
 *  720:     public function week_selectRelative($arr_pluginConf)
 *  787:     public function year_selectRelative($arr_pluginConf)
 *  847:     public function socialmedia_getArrBookmarks($arr_pluginConf)
 *  906:     public function templating_getArrDataQuery($arr_pluginConf)
 *
 *              SECTION: Helper Methods
 *  990:     function init($arr_pluginConf)
 * 1031:     function init_pageObj($arr_pluginConf)
 * 1063:     function init_pageUid($arr_pluginConf)
 * 1113:     function init_tsObj($arr_rows_of_all_pages_inRootLine)
 * 1145:     public function zz_hours( $arr_pluginConf )
 *
 * TOTAL FUNCTIONS: 15
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi5_backend
{




  var $pid  = null;
  // [Integer] Pid of the current page
  var $obj_page = null;
  // [Object] Current t3-page object
  var $obj_TypoScript = null;
  // [Object] TypoScript object of current page











  /**
 * sDef_getArrViewsList: Get data query (and andWhere) for all list views of the current plugin.
 * Tab [General/sDEF]
 *
 * @param	array		$arr_pluginConf: Configuration of the plugin
 * @return	array		with the names of the views list
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
              $arr_csvViews = explode(',', $csvViews);
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
 * sDEF_getExtensionTemplates: Get templates from the browser and third party extensions
 * Tab [sDEF]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the templates
 */
  public function sDEF_getExtensionTemplates($arr_pluginConf)
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
        $label = $arr_template['name'].' ('.$extension.')';
        $value = $arr_template['file'];
        $arr_pluginConf['items'][] = array($label, $value);
      }
    }
      // Loop through all extensions and templates

    return $arr_pluginConf;

  }











  /**
 * evaluate_externalLinks: HTML content with external links
 *
 * Tab [evaluate]
 *
 * @param	array		$arr_pluginConf:  Current plugin/flexform configuration
 * @param	array		$obj_TCEform:     Current TCE form object
 * @return	string		$str_prompt: HTML prompt
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
      <div class="message-body">
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
 * @param	array		$arr_pluginConf:  Current plugin/flexform configuration
 * @param	array		$obj_TCEform:     Current TCE form object
 * @return	string		$str_prompt: HTML prompt
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
      // Set some default prompts

      // WARNING: Completly support initial in 4.2
    $str_prompt_warning_version_420 = '
      <div class="typo3-message message-warning">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.warn.version.420') . '
        </div>
      </div>
      ';

      // INFO: DRS
    $str_prompt_info_drs = '
      <div class="typo3-message message-notice" style="max-width:' . $this->maxWidth . ';">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.info.drs') . '
        </div>
      </div>
      ';

      // INFO: Include this plugin into the Browser sheet.extend
    $str_prompt_info_includePi5 = '
      <div class="typo3-message message-information">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.info.includePi5') . '
        </div>
      </div>
      ';

      // INFO: Link to the tutorial and to the browser forum
    $str_prompt_info_tutorialAndForum = '
      <div class="typo3-message message-notice">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.info.tutorialAndForum') . '
        </div>
      </div>
      ';
      // Set some default prompts



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN plugin isn't never saved

    if( empty ( $arr_pluginConf['row']['pi_flexform'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.error.saved_never') . '
          </div>
        </div>
        <div class="typo3-message message-information">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.info.saved_never') . '
          </div>
        </div>
        ';
      return $str_prompt;
    }
      // RETURN plugin isn't never saved



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN no TypoScript template

    if( !is_array ( $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['pi5.'] ) )
    {
      $str_prompt = '
        <div class="typo3-message message-error">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.error.no_ts_template') . '
          </div>
        </div>
        <div class="typo3-message message-information">
          <div class="message-body">
            ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.info.no_ts_template') . '
          </div>
        </div>
        ';

      $str_prompt = $str_prompt . $str_prompt_info_tutorialAndForum;
      return $str_prompt;
    }
      // RETURN no TypoScript template



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN success

    $str_prompt = '
      <div class="typo3-message message-ok">
        <div class="message-body">
          ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:sheet_evaluate.plugin.ok') . '
        </div>
      </div>
      ';
    $str_prompt = $str_prompt . $str_prompt_info_includePi5 . $str_prompt_info_tutorialAndForum .
                  $str_prompt_info_drs . $str_prompt_warning_version_420;
      // RETURN the prompt



    return $str_prompt;
  }











  /**
 * day_selectRelative:  Get items for a select box.
 *                      Returns a list with items like this:
 *                      10, ... , -1, current week, +1, ..., +10
 * Tab [day]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the items
 */
  public function day_selectRelative($arr_pluginConf)
  {
      // Localise lables
    $ll_default       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.default');
    $ll_dayNom        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.nom');
    $ll_currDayDat    = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.current.dat');
    $ll_takeItFromTs  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.takeItFromTs');
    $ll_wouldBe       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBe');
    $ll_wouldBeToday  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBeTodayDay');

      // Configure current day
    $int_currDay    = (int) date ( 'j' );                 // Integer value of current day.
    $str_currMonth  = date ( 'M' );                       // Month represented by three characters
    $str_currDay    = $ll_wouldBeToday . ' ' . $int_currDay . '. ' . $str_currMonth;   // Something like 22. day


      // Start and end position
    $int_startDay  = $int_currDay - 10;
    $int_endDay    = $int_currDay + 10;

      // Default items for select box
    $arr_pluginConf['items'][] = array($ll_currDayDat . ' ('. $str_currDay . ') - ' . $ll_default,  'today 0:00' );
    $arr_pluginConf['items'][] = array($ll_takeItFromTs,                                            'ts'  );
    $arr_pluginConf['items'][] = array('-------------------------------------------',               null  );

      // Last days
    $ll_last        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.last');
    $lastDay        = 'last Monday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.monday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Tuesday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.tuesday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Wednesday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.wednesday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Thursday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.thursday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Friday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.friday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Saturday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.saturday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $value          = $lastDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $lastDay        = 'last Sunday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.sunday');
    $label_lastDay  = $ll_last . ' ' . $ll_day;
    $label          = $label_lastDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($lastDay)) . ')';
    $arr_pluginConf['items'][] = array($label, $value);
    $arr_pluginConf['items'][] = array('-------------------------------------------',       null          );
      // Last days


      // Next days
    $ll_next        = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.next');
    $nextDay        = 'next Monday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.monday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Tuesday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.tuesday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Wednesday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.wednesday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Thursday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.thursday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Friday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.friday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Saturday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.saturday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $value          = $nextDay;
    $arr_pluginConf['items'][] = array($label, $value);
    $nextDay        = 'next Sunday';
    $ll_day         = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.day.sunday');
    $label_nextDay  = $ll_next . ' ' . $ll_day;
    $label          = $label_nextDay . ' (' . $ll_wouldBe . ' ' . date ( 'D. d. M', strtotime($nextDay)) . ')';
    $arr_pluginConf['items'][] = array($label, $value);
    $arr_pluginConf['items'][] = array('-------------------------------------------',       null          );
      // Next days

      // LOOP items from ( current day ./. 10 ) to (current day + 10)
    $strPlus = null;
    for($int_day = $int_startDay; $int_day <= $int_endDay; $int_day++)
    {
      $strPosition = $strPlus . ( $int_day - $int_currDay ); // i.e.: -7 or +5
      $str_day     = $ll_wouldBe . ' ' . $int_day . '. ' . $str_currMonth;

      if($int_day == $int_currDay)
      {
        $label = '-------------------------------------------';
        $value = null;
        $arr_pluginConf['items'][] = array($label, $value);
        $strPlus = '+';
        continue;
      }

      $label = $strPosition . ' ('. $str_day . ')';
      $value = $strPosition . ' day';
      $arr_pluginConf['items'][] = array($label, $value);
    }
      // LOOP items from ( current day ./. 10 ) to (current day + 10)

    return $arr_pluginConf;
  }











  /**
 * month_selectRelative:  Get items for a select box.
 *                        Returns a list with items like this:
 *                      - 10, ... , -1, current month, +1, ..., +10
 * Tab [year]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the items
 */
  public function month_selectRelative($arr_pluginConf)
  {
      // Localise lables
    $ll_default       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.default');
    $ll_currMonthDat  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.month.current.dat');
    $ll_takeItFromTs  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.takeItFromTs');
    $ll_wouldBe       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBe');
    $ll_wouldBeToday  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBeTodayMonth');

      // Configure current month
    $str_currMonth   = $ll_wouldBeToday . ' ' . date ( 'M' );        // Month represented by three characters
    $int_currMonth   = (int) date ( 'n' );  // Integer value of current month

      // Start and end position
    $int_startMonth  = $int_currMonth - 11;
    $int_endMonth    = $int_currMonth + 11;

      // Default items for select box
    $arr_pluginConf['items'][] = array($ll_currMonthDat . ' ('. $str_currMonth . ') - ' . $ll_default,  'today 0:00' );
    $arr_pluginConf['items'][] = array($ll_takeItFromTs,                                                'ts'  );
    $arr_pluginConf['items'][] = array('-------------------------------------------',                   null  );

      // LOOP items from ( current month ./. 11 ) to (current month + 11)
    $strPlus = null;
    for($int_month = $int_startMonth; $int_month <= $int_endMonth; $int_month++)
    {
      $strPosition  = $strPlus . ( $int_month - $int_currMonth ); // i.e.: -7 or +5
      $str_month    = $ll_wouldBe . ' ' . date ( 'M', strtotime($strPosition . ' month'));

      if($int_month == $int_currMonth)
      {
        $label = '-------------------------------------------';
        $value = null;
        $arr_pluginConf['items'][] = array($label, $value);
        $strPlus = '+';
        continue;
      }

      $label = $strPosition . ' ('. $str_month . ')';
      $value = $strPosition . ' month';
      $arr_pluginConf['items'][] = array($label, $value);
    }
      // LOOP items from ( current month ./. 11 ) to (current month + 11)

    return $arr_pluginConf;
  }











  /**
 * week_selectRelative:  Get items for a select box.
 *                        Returns a list with items like this:
 *                      - 10, ... , -1, current week, +1, ..., +10
 * Tab [year]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the items
 */
  public function week_selectRelative($arr_pluginConf)
  {
      // Localise lables
    $ll_default       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.default');
    $ll_weekNom       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.week.nom');
    $ll_currWeekDat   = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.week.current.dat');
    $ll_takeItFromTs  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.takeItFromTs');
    $ll_wouldBe       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBe');
    $ll_wouldBeToday  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBeTodayWeek');

      // Configure current week
    $int_currWeek   = (int) date ( 'W' );                 // Integer value of current week. The week is beginning on monday.
    $str_currWeek   = $ll_wouldBeToday . ' ' . $int_currWeek . '. ' . $ll_weekNom;   // Something like 22. week

      // Start and end position
    $int_startWeek  = $int_currWeek - 10;
    $int_endWeek    = $int_currWeek + 10;

      // Default items for select box
    $arr_pluginConf['items'][] = array($ll_currWeekDat . ' ('. $str_currWeek . ') - ' . $ll_default,  'today 0:00' );
    $arr_pluginConf['items'][] = array($ll_takeItFromTs,                                              'ts'  );
    $arr_pluginConf['items'][] = array('-------------------------------------------',                 null  );

      // LOOP items from ( current week ./. 10 ) to (current week + 10)
    $strPlus = null;
    for($int_week = $int_startWeek; $int_week <= $int_endWeek; $int_week++)
    {
      $strPosition  = $strPlus . ( $int_week - $int_currWeek ); // i.e.: -7 or +5
      $str_week     = $ll_wouldBe . ' ' . $int_week . '. ' . $ll_weekNom;

      if($int_week == $int_currWeek)
      {
        $label = '-------------------------------------------';
        $value = null;
        $arr_pluginConf['items'][] = array($label, $value);
        $strPlus = '+';
        continue;
      }

      $label = $strPosition . ' ('. $str_week . ')';
      $value = $strPosition . ' week';
      $arr_pluginConf['items'][] = array($label, $value);
    }
      // LOOP items from ( current week ./. 10 ) to (current week + 10)

    return $arr_pluginConf;
  }











  /**
 * year_selectRelative: Get items for a select box.
 *                      Returns a list with items like this:
 *                      -10, ... , -1, current year, +1, ..., +10
 * Tab [year]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the items
 */
  public function year_selectRelative($arr_pluginConf)
  {
      // Localise lables
    $ll_default       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.default');
    $ll_currYearDat   = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.year.current.dat');
    $ll_takeItFromTs  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.takeItFromTs');
    $ll_wouldBe       = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBe');
    $ll_wouldBeToday  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.wouldBeTodayYear');

      // Configure current year
    $int_currYear = (int) date ( 'Y' );
    $str_currYear = $ll_wouldBeToday . ' ' . $int_currYear;

      // Start and end position
    $int_startYear  = $int_currYear - 10;
    $int_endYear    = $int_currYear + 10;

      // Default items for select box
    $arr_pluginConf['items'][] = array($ll_currYearDat . ' ('. $str_currYear . ') - ' . $ll_default,  'today 0:00' );
    $arr_pluginConf['items'][] = array($ll_takeItFromTs,                                              'ts'  );
    $arr_pluginConf['items'][] = array('-------------------------------------------',                 null  );

      // LOOP items from ( current year ./. 10 ) to (current year + 10)
    $strPlus = null;
    for($int_year = $int_startYear; $int_year <= $int_endYear; $int_year++)
    {
      $strPosition  = $strPlus . ( $int_year - $int_currYear ); // i.e.: -7 or +5

      if($int_year == $int_currYear)
      {
        $label = '-------------------------------------------';
        $value = null;
        $arr_pluginConf['items'][] = array($label, $value);
        $strPlus = '+';
        continue;
      }

      $label = $strPosition . ' ('. $ll_wouldBe . ' ' . $int_year . ')';
      $value = $strPosition . ' year';
      $arr_pluginConf['items'][] = array($label, $value);
    }
      // LOOP items from ( current year ./. 10 ) to (current year + 10)

    return $arr_pluginConf;

  }








  /**
 * socialmedia_getArrBookmarks: Get bookmarks for flexform. Tab [Socialmedia]
 *
 * @param	array		$arr_pluginConf: Configuration of the plugin
 * @return	array		with the bookmarks
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
 * @param	array		$arr_pluginConf: Configuration of the plugin
 * @return	array		with the bookmarks
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













  /***********************************************
   *
   * Helper Methods
   *
   **********************************************/













  /**
 * init(): Initiate this class.
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	boolean		TRUE: success. FALSE: error.
 * @since 3.4.5
 * @version 3.4.5
 */
  function init($arr_pluginConf)
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

    return true;
  }












  /**
 * init_pageObj(): Initiate an page object.
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	boolean		FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  function init_pageObj($arr_pluginConf)
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
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	boolean		FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  function init_pageUid($arr_pluginConf)
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
 * @param	array		$arr_rows_of_all_pages_inRootLine: Agregate the TypoScript of all pages in the rootline
 * @return	boolean		FALSE
 * @since 3.4.5
 * @version 3.4.5
 */
  function init_tsObj($arr_rows_of_all_pages_inRootLine)
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








  /**
 * zz_hours:  Get the hours of one day
 *            Returns a list with items like this:
 *            00:00, 01:00, ..., 23:00, 24:00
 * Tab [year]
 *
 * @param	array		$arr_pluginConf: Current plugin/flexform configuration
 * @return	array		$arr_pluginConf: Extended with the items
 */
  public function zz_hours( $arr_pluginConf )
  {
      // Localise lables
    $ll_takeItFromTs  = $GLOBALS['LANG']->sL('LLL:EXT:browser/pi5/flexform_locallang.xml:labels.takeItFromTs');

      // Default items for select box
    $arr_pluginConf['items'][] = array($ll_takeItFromTs,                              'ts'  );
    $arr_pluginConf['items'][] = array('-------------------------------------------', null  );

      // Start and end position
    $int_firstHourOfDay =  0;
    $int_lastHourOfDay  = 24;

      // LOOP items from first to last hour of a day
    for( $int_hour = $int_firstHourOfDay; $int_hour <= $int_lastHourOfDay; $int_hour++ )
    {
      $label = sprintf ( '%02d:00', $int_hour );
      $value = $int_hour;
      $arr_pluginConf['items'][] = array($label, $value);
    }
      // LOOP items from first to last hour of a day

    return $arr_pluginConf;
  }









}







if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi5/class.tx_browser_pi5_backend.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi5/class.tx_browser_pi5_backend.php']);
}
?>
