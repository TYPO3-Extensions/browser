<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 - 2010 Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_ttcontainer enables the Typoscript Template Container System
 *
 * @author    Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   69: class tx_browser_pi1_ttcontainer
 *  100:     function __construct($objBrowser)
 *
 *              SECTION: Main
 *  128:     function main($rows)
 *
 *              SECTION: Processing the Container
 *  264:     function loop_container_recurs($arr_container=array())
 *  476:     function get_container($arr_ttc_values, $str_ttc_type)
 *
 *              SECTION: Processing the Markers
 *  562:     function get_marker_keys_recursive($arr_ttc_values)
 *  701:     function get_marker_uids($arr_marker_keys)
 *  732:     function get_marker_values($arr_marker_keys)
 * 1162:     function get_marker_ordered($arr_marker_values)
 * 1318:     function get_wrapped_marker($arr_ttc_values, $arr_marker_values, $str_ttc_type)
 *
 *              SECTION: Helper methods for the markers
 * 1444:     function wrap_marker($arr_ttc_values, $str_ttc_type)
 *
 *              SECTION: Check the TypoScript Configuration
 * 1513:     function check_container($arr_container)
 * 1576:     function check_container_value($arr_container_value)
 *
 *              SECTION: Realurl
 * 1634:     function update_realurl($arr_ttc_values)
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_ttcontainer
{


  ///////////////////////////////////////
  //
  // Variables which are needed for recursive processing

  var $rows             = array();
  // The current SQL result as rows array
  var $arr_ts_view      = array();
  // The typoscript view array like: plugin.tx_browser_pi1.views.single.1
  var $arr_tt_container = array();
  // An array with values from the current TT_CONTAINER
  var $arr_ttc_values   = array();
  // An array with values from all TT_CONTAINER in the rootline
  var $str_ttc_path     = false;
  // A typoscript path like: ttContainer.2.0
  var $template         = '';
  // String: HTML code





  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($objBrowser)
  {
    $this->pObj = $objBrowser;
  }










  /***********************************************
   *
   * Main
   *
   **********************************************/



  /**
 * Finished HTML code - return the wrapped template
 *
 * @param	array		$rows: Array with the records of the SQL result
 * @return	array		Array with the elements error and data. Data contains the template.
 */
  function main($rows)
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot      = $view.'.';
    $this->rows     = $rows;
    $this->template = '';

    $this->arr_ts_view = $conf['views.'][$viewWiDot][$mode.'.'];

    $arr_return['error']['status']  = false;
    $arr_return['data']['template'] = $this->template;


    ///////////////////////////////////////
    //
    // Order the Typoscript array like in the TypoScript Object Browser style

    ksort($this->arr_ts_view, SORT_STRING);


    ///////////////////////////////////////
    //
    // Loop through the current Container

    foreach ($this->arr_ts_view as $ts_key => $ts_value)
    {
      if ($ts_value == 'TT_CONTAINER')
      {
        $this->str_ttc_path = $ts_key;
        // Count "up" the typoscript path. I.e. ttContainer.2 would become ttContainer.2.0

        $this->arr_tt_container = $this->arr_ts_view[$ts_key.'.'];
        // Array with the current tt_container

        // Store the values for limiting the rows. We need it in get_marker_values();
        $i_last = count($this->arr_limits);
        $this->arr_limits[$i_last]['uidField'] = $this->arr_ts_view[$ts_key.'.']['tableFieldUid'];
        $this->arr_limits[$i_last]['limit']    = $this->arr_ts_view[$ts_key.'.']['limit'];
        $this->arr_limits[$i_last]['ttc_path'] = $this->str_ttc_path;

        // Store TT_CONTAINER values of this level. We need it in get_container();
        $this->arr_ttc_values[$ts_key]['pathSegment'] = $ts_key;
        $this->arr_ttc_values[$ts_key]['uidField']    = $this->arr_ts_view[$ts_key.'.']['tableFieldUid'];
        $this->arr_ttc_values[$ts_key]['limit']       = $this->arr_ts_view[$ts_key.'.']['limit'];
        $this->arr_ttc_values[$ts_key]['order']       = $this->arr_ts_view[$ts_key.'.']['order'];
        $this->arr_ttc_values[$ts_key]['seo']         = $this->arr_ts_view[$ts_key.'.']['seo'];


        ///////////////////////////////////////
        //
        // SEO - Search Engine Optimization

        if ($this->arr_ttc_values[$ts_key]['seo'])
        {
          if ($this->pObj->b_drs_seo) {
            t3lib_div::devlog('[INFO/SEO] '.$this->str_ttc_path.'.seo is TRUE. We take the first row of the SQL result for SEO.', $this->pObj->extKey, 0);
          }
          $this->pObj->objSeo->seo($this->rows[0]);
        }
        else
        {
          if ($this->pObj->b_drs_seo) {
            t3lib_div::devlog('[INFO/SEO] '.$this->str_ttc_path.'.seo is FALSE.', $this->pObj->extKey, 0);
            t3lib_div::devlog('[HELP/SEO] If you want use Search Engine Optimization (SEO), please allocate TRUE to '.$this->str_ttc_path.'.seo.', $this->pObj->extKey, 1);
          }
        }


        $arr_result = $this->loop_container_recurs($this->arr_ts_view[$ts_key.'.']);
        if ($arr_result['error']['status'])
        {
          if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
          {
            t3lib_div::devlog('[ERROR/TTC] TT_CONTAINER ['.$ts_key.']', $this->pObj->extKey, 3);
            t3lib_div::devlog('[WARN/TTC] No TypoScript-Template-Container will be processed!', $this->pObj->extKey, 2);
            t3lib_div::devlog('[ERROR/TTC] ABORT', $this->pObj->extKey, 3);
          }
          $arr_return = $arr_result;
          unset($arr_result);
          return $arr_return;
        }
        if ($arr_result['data']['template'])
        {
          $this->template .= $arr_result['data']['template'];
          unset($arr_result);
        }

        unset($this->arr_ttc_values[$ts_key]);
        // Remove TT_CONTAINER values of this level.

        unset($this->arr_limits[$i_last]);
        // Remove the values for limiting the rows.

        $this->str_ttc_path = substr($this->str_ttc_path, 0, strlen($this->str_ttc_path) - strlen($ts_key));
        // Count "down" the typoscript path. I.e. ttContainer.2.0 would become ttContainer.2
      }
    }
    // Loop through the current Container

    $arr_return['data']['template'] = $this->template;
    return $arr_return;
  }













  /***********************************************
   *
   * Processing the Container
   *
   **********************************************/






  /**
 * Loop through the current CONTAINER and all contained CONTAINERS (recursive). Return the template
 *
 * @param	array		Array with the currrent tt_container
 * @return	array		Array with the elements error and data. Data contains the Template.
 */
  function loop_container_recurs($arr_container=array())
  {

    $conf       = $this->pObj->conf;
    $conf_view  = $this->arr_ts_view;

    $arr_return['error']['status']  = false;


    /////////////////////////////////////
    //
    // Security: recursionGuard

    static $i_curr  = 0;

    $i_max = (int) $conf['advanced.']['recursionGuard'];
    #10116
    if(!empty($conf_view['advanced.']))
    {
      $i_max = (int) $conf_view['advanced.']['recursionGuard'];
    }
    $i_curr++;
    if ($i_curr > $i_max) {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] Recursion is bigger than \''.$i_max.'\'', $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/TTC] If it is ok, please increase the recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/TTC] EXIT', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttcv_prompt_recursion').'</p>';
      $str_prompt .= '<p style="color:red;font-weight:bold;">recursionGuard: \''.$i_max.'\'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


    ///////////////////////////////////////
    //
    // Check and order the container

    $arr_result = $this->check_container($arr_container);
    if ($arr_result['error']['status'])
    {
      return $arr_result;
    }

    ksort($arr_container, SORT_STRING);


    ///////////////////////////////////////
    //
    // Loop through the current level of the TypoScript

    foreach ($arr_container as $ts_key => $ts_value)
    {

      ///////////////////////////////////////
      //
      // Process TT_CONTAINER (recursive)

      if ($ts_value == 'TT_CONTAINER')
      {
        $this->str_ttc_path .= '.'.$ts_key;
        // Count "up" the typoscript path. I.e. ttContainer.2 would become ttContainer.2.0

        $this->arr_tt_container = $arr_container[$ts_key.'.'];
        // Array with the current tt_container

        // Store the values for limiting the rows. We need it in get_marker_values();
        $i_last = count($this->arr_limits);
        $this->arr_limits[$i_last]['uidField'] = $arr_container[$ts_key.'.']['tableFieldUid'];
        $this->arr_limits[$i_last]['limit']    = $arr_container[$ts_key.'.']['limit'];
        $this->arr_limits[$i_last]['ttc_path'] = $this->str_ttc_path;

        // Store TT_CONTAINER values of this level. We need it in get_container();
        $this->arr_ttc_values[$ts_key]['pathSegment']  = $ts_key;
        $this->arr_ttc_values[$ts_key]['uidField']     = $arr_container[$ts_key.'.']['tableFieldUid'];
        $this->arr_ttc_values[$ts_key]['limit']        = $arr_container[$ts_key.'.']['limit'];
        $this->arr_ttc_values[$ts_key]['order']        = $arr_container[$ts_key.'.']['order'];

        if ($this->pObj->b_drs_ttc)
        {
          t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.' = TT_CONTAINER', $this->pObj->extKey, 0);
        }
        $arr_result = $this->loop_container_recurs($arr_container[$ts_key.'.']);
        if ($arr_result['error']['status'])
        {
          return $arr_result;
        }
        $template  .= $arr_result['data']['template'];
        unset($arr_result);

        // Remove TT_CONTAINER values of this level.
        unset($this->arr_ttc_values[$ts_key]);

        unset($this->arr_limits[$i_last]);
        // Remove the values for limiting the rows.

        $this->str_ttc_path = substr($this->str_ttc_path, 0, strlen($this->str_ttc_path) - strlen('.'.$ts_key));
        // Count "down" the typoscript path. I.e. ttContainer.2.0 would become ttContainer.2
      }
      // Process TT_CONTAINER (recursive)


      ///////////////////////////////////////
      //
      // Process TTC_STDWRAP or TTC_COA

      if ($ts_value == 'TTC_STDWRAP' || $ts_value == 'TTC_COA')
      {
        $this->str_ttc_path .= '.'.$ts_key;
        // Count "up" the typoscript path. I.e. ttContainer.2 would become ttContainer.2.0

        if ($this->pObj->b_drs_ttc)
        {
          t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.' = '.$ts_value, $this->pObj->extKey, 0);
        }
        $arr_result   = $this->get_container($arr_container[$ts_key.'.'], $ts_value);
        if ($arr_result['error']['status'])
        {
          return $arr_result;
        }
        $template  .= $arr_result['data']['template'];
        unset($arr_result);

        $this->str_ttc_path = substr($this->str_ttc_path, 0, strlen($this->str_ttc_path) - strlen('.'.$ts_key));
        // Count "down" the typoscript path. I.e. ttContainer.2.0 would become ttContainer.2
      }
      // Process TTC_STDWRAP or TTC_COA

    }
    // Loop through the current level of the TypoScript


    ///////////////////////////////////////
    //
    // Wrap the container

    // If we have a result
    if ($template)
    {
      // If we have a container wrap
      if ($arr_container['wrap'])
      {
        $template = str_replace('|', $template, $arr_container['wrap']);
        // Wrap the template with TT_CONTAINER wrap
      }
    }
    // If we have a result

    // If we don't have any result
    if (!$template)
    {
      // Development Reporting System
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.' hasn\'t any record.', $this->pObj->extKey, 0);
      }

      // If we have a noRecord.value
      if ($arr_container['noRecord.']['value'])
      {
        // Development Reporting System
        if ($this->pObj->b_drs_ttc)
        {
          t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.' has a noRecord.value. We display it:<br /><br />'.htmlspecialchars($arr_container['noRecord.']['value']), $this->pObj->extKey, 0);
        }
        $template = $arr_container['noRecord.']['value'];
        // Display the noRecord.value
      }
      // If we don't have any noRecord.value
      else
      {
        // Development Reporting System
        if ($this->pObj->b_drs_ttc)
        {
          t3lib_div::devlog('[HELP/TTC] If you want display a norecord message please use the noRecord.value.', $this->pObj->extKey, 1);
        }
      }
    }
    // If we don't have any result


    ///////////////////////////////////////
    //
    // Return the Template

    $arr_return['data']['template'] = $template;
    return $arr_return;
  }












  /**
 * Process a TTC_STDWRAP or TTC_COA. Return it in finished HTML code.
 *
 * @param	array		$arr_ttc_values: TypoScript array of the current TTC_STDWRAP or TTC_COA
 * @param	string		$str_ttc_type: Type of the TTC. TTC_COA or TTC_STDWRAP
 * @return	array		Array with the elements error and data. Data contains the Template.
 */
  function get_container($arr_ttc_values, $str_ttc_type)
  {
    $arr_return['error']['status']  = false;


    ///////////////////////////////////////
    //
    // Check TTC_STDWRAP or TTC_COA

    $arr_result = $this->check_container_value($arr_ttc_values);
    if ($arr_result['error']['status'])
    {
      return $arr_result;
    }
    unset($arr_result);


    ///////////////////////////////////////
    //
    // Process the Markers

    $arr_marker_keys    = $this->get_marker_keys_recursive($arr_ttc_values);
    // The used markers in table.field syntax like: $arr_markers[tx_civserv_service.sv_name]
    $arr_marker_keys    = $this->get_marker_uids($arr_marker_keys);
    // Extending $arr_markers with keys from TT_CONTAINER tableFieldUid like: $arr_markers[tx_civserv_service.uid]
    $arr_marker_values  = $this->get_marker_values($arr_marker_keys);
    // Get rows with the pairs marker and value like: $arr_markers[0][tx_civserv_service.uid] = 112
    $arr_marker_values  = $this->get_marker_ordered($arr_marker_values);
    // Oder the marker array
    $arr_return         = $this->get_wrapped_marker($arr_ttc_values, $arr_marker_values, $str_ttc_type);
    // Get the template as finished HTML code in $arr_return['data']['template']


    ///////////////////////////////////////
    //
    // Unset array, we don't needed

    unset($arr_marker_keys);
    unset($arr_ttc_values);
    unset($arr_marker_values);


    ///////////////////////////////////////
    //
    // Return the template in $arr_return['data']['template']

    return $arr_return;

  }























  /***********************************************
   *
   * Processing the Markers
   *
   **********************************************/



  /**
 * Get all used markes in the current level and all children levels of the TypoScript.
 *
 * @param	array		$arr_ttc_values: The elements of the current TTC_STDWRAP or TTC_COA
 * @return	array		Array with the used markers as the array keys in table.field syntax.
 */
  function get_marker_keys_recursive($arr_ttc_values)
  {

    $conf = $this->pObj->conf;

    /////////////////////////////////////
    //
    // Security: recursionGuard

    static $i_curr = 0;

    $i_max = (int) $conf['advanced.']['recursionGuard'];
    #10116
    if(!empty($conf_view['advanced.']))
    {
      $i_max = (int) $conf_view['advanced.']['recursionGuard'];
    }
    $i_curr++;
    if ($i_curr > $i_max) {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] Recursion is bigger than \''.$i_max.'\'', $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/TTC] If it is ok, please increase the recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/TTC] EXIT', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttcv_prompt_recursion').'</p>';
//      $str_prompt .= '<p style="color:red;font-weight:bold;">recursionGuard: \''.$i_max.'\'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


    /////////////////////////////////////
    //
    // Loop through the current level of the TypoScript

    foreach((array) $arr_ttc_values as $key_ttc_values => $value_ttc_values)
    {
      if (is_array($value_ttc_values))
      {

        /////////////////////////////////////
        //
        // Loop through the next level of the TypoScript (recursive)

        $tmp_arr_markers = $this->get_marker_keys_recursive($value_ttc_values);
        if (is_array($tmp_arr_markers) && count($tmp_arr_markers) > 0)
        {
          if(is_array($arr_markers))
          {
            $arr_markers = array_merge($arr_markers, $tmp_arr_markers);
          }
          else
          {
            $arr_markers = $tmp_arr_markers;
          }
        }
      }


      /////////////////////////////////////
      //
      // Process the values

      if(!is_array($value_ttc_values))
      {
        $b_marker = true;

        if ($b_marker)
        {
          // Do we have markers?
          $i_marker = substr_count($value_ttc_values, '###');  // I.e: 4
          if ($i_marker == 0)
          {
            //if ($this->pObj->b_drs_ttc)
            //{
            //  t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.'.'.$key_ttc_values.' hasn\'t any ###MARKER###', $this->pObj->extKey, 0);
            //}
            $b_marker = false;
            // There isn't any '###'
          }
        }

        if ($b_marker)
        {
          // Do we have an even amount of markers?
          $i_marker = $i_marker % 2; // Even = 0, Odd = 1
          if ($i_marker != 0)
          {
            if ($this->pObj->b_drs_ttc)
            {
              t3lib_div::devlog('[ERROR/TTC] '.$this->str_ttc_path.'.'.$key_ttc_values.' has an odd amount of \'###\'. It isn\'t possible to process the values.', $this->pObj->extKey, 3);
              t3lib_div::devlog('[ERROR/TTC] '.$this->str_ttc_path.'.'.$key_ttc_values.' = '.$value_ttc_values, $this->pObj->extKey, 3);
              t3lib_div::devlog('[HELP/TTC] Please use a proper syntax!', $this->pObj->extKey, 1);
            }
            $b_marker = false;
            // We don't have an even amount of markers. This is an error. Don't process this element of the loop..
          }
        }

        if ($b_marker)
        {
          $tmp_arr_markers = explode('###', $value_ttc_values);

          // Only odd elements contains marker, remove even elements
          for($i_even = 0; $i_even < count($tmp_arr_markers); $i_even++)
          {
            if(($i_even % 2) === 1)
            {
              // We have an odd element (Odd = 1, Even = 0)
              $str_marker               = strtolower($tmp_arr_markers[$i_even]);
              $arr_markers[$str_marker] = false;
            }
          }
        }

      }
    }
    // Loop through the current level of the TypoScript (recursive)

    return $arr_markers;
  }








  /**
 * Extends $arr_markers with keys from TT_CONTAINER tableFieldUid.
 *
 * @param	array		$arr_markers: The marker array like: $arr_markers[tx_civserv_service.sv_name]
 * @return	array		The marker array extended with uidFields like: $arr_markers[tx_civserv_service.uid]
 */
  function get_marker_uids($arr_marker_keys)
  {

    foreach((array) $this->arr_ttc_values as $key => $arr_container_values)
    {
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$arr_container_values['uidField'].' is added to the marker array.', $this->pObj->extKey, 0);
      }
      $arr_marker_keys[$arr_container_values['uidField']] = false;
    }
    return $arr_marker_keys;
  }












/**
 * Allocate the marker array with real values from the SQL result.
 *
 * @param	array		$arr_markers: The marker array like: $arr_markers[tx_civserv_service.sv_name]
 * @return	array		The marker array allocated with the values from the SQL result
 */
  function get_marker_values($arr_marker_keys)
  {

    $rows         = $this->rows;
    // The result of the SQL query as the rows array
    $arr_limits   = $this->arr_limits;
    // An array with elements [x]['uidField'], [x]['limit'], [x]['ttc_path']
    $arr_uids     = array();
    // An array ...
    $b_first_run  = true;
    // We want messages only once


    /////////////////////////////////////
    //
    // Get for every marker the value

    // Loop through all markers
    foreach((array) $arr_marker_keys as $key_marker => $value_marker)
    {
      $i_row  = 0;
      // Loop through all rows
      foreach((array) $rows as $key_row => $elements)
      {
        // Check in the first loop, if we have the searched key in the row
        if($b_first_run)
        {
          if(!(array_key_exists($key_marker, $elements)))
          {
            // Key isn't an element in the SQL result row
            if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
            {
              $str_marker = '###'.strtoupper($key_marker).'###';
              $str_select = $key_marker.' AS `'.$key_marker.'`';
              t3lib_div::devlog('[ERROR/TTC] The marker '.$str_marker.' isn\'t a field in the SQL result.', $this->pObj->extKey, 3);
              t3lib_div::devlog('[WARN/TTC] You won\'t get the expected result in your HTML template.', $this->pObj->extKey, 2);
              t3lib_div::devlog('[HELP/TTC] Please extend your SQL SELECT statement like ... '.$str_select.' ... with respect to the alias rules.', $this->pObj->extKey, 1);
            }
          }
        }
        $arr_marker_values[$i_row][$key_marker] = $elements[$key_marker];
        $i_row++;
      }
      $b_first_run = false;
      // Loop through all rows
    }
    // Loop through all markers


    /////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_warn)
    {
      if (!is_array($arr_marker_values))
      {
        t3lib_div::devlog('[WARN/TTC] $arr_marker_values hasn\'t any element. Maybe this is a bug.', $this->pObj->extKey, 2);
      }
      else
      {
        $str_prompt = '';
        foreach ($arr_marker_values as $key1 => $elements1)
        {
          foreach ($elements1 as $key_element => $value_element)
          {
            if (!$value_element)
            {
              $elements1[$key_element] = 'NULL';
            }
          }
          $str_elements1 = implode(', ', $elements1);
          $str_elements1 = htmlspecialchars($str_elements1);
          if (strlen($str_elements1) > $this->pObj->i_drs_max_sql_result_len)
          {
            $str_elements1 = substr($str_elements1, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          $str_prompt .= '<br />row['.$key1.']: '.$str_elements1;
        }
        if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[INFO/TTC] We have the rows: '.$str_prompt, $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/TTC] We want unique rows only. Check the rows.', $this->pObj->extKey, 0);
        }
      }
    }


    /////////////////////////////////////
    //
    // We want unique markers

    if (is_array($arr_marker_values))
    {
      // Loop through all marker rows
      foreach((array) $arr_marker_values as $key1 => $elements1)
      {
        if(count($arr_marker_values) <= 1)
        {
          // If we have only one row, don't check anything
          break;
        }

        $arr_marker_compare = $arr_marker_values;
        // Build the compare array
        unset($arr_marker_compare[$key1]);
        // Remove the first element of the array, because we want compare only further elements

        // Loop through all rows of the compare array
        foreach((array) $arr_marker_compare as $key2 => $elements2)
        {
          $b_unset  = false;
          $arr_diff = array_diff_assoc($elements1, $elements2);
          // Get the difference
          if(count($arr_diff) == 0)
          {
            // There is no difference
            unset($arr_marker_values[$key1]);
            // Remove the current row
            $b_unset = true;
            // Row is removed, no further tests

            // Development Reporting System
            if ($this->pObj->b_drs_ttc)
            {
              foreach ($elements1 as $key_element1 => $value_element1)
              {
                if (!$value_element1)
                {
                  $elements1[$key_element1] = 'NULL';
                }
              }
              foreach ($elements2 as $key_element2 => $value_element2)
              {
                if (!$value_element2)
                {
                  $elements2[$key_element2] = 'NULL';
                }
              }
              $str_elements1 = implode(', ', $elements1);
              $str_elements2 = implode(', ', $elements2);
              $str_elements1 = htmlspecialchars($str_elements1);
              $str_elements2 = htmlspecialchars($str_elements2);
              if (strlen($str_elements1) > $this->pObj->i_drs_max_sql_result_len)
              {
                $str_elements1 = substr($str_elements1, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
              }
              if (strlen($str_elements2) > $this->pObj->i_drs_max_sql_result_len)
              {
                $str_elements2 = substr($str_elements2, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
              }
              t3lib_div::devlog('[INFO/TTC] Same row:<br />row['.$key1.']: '.$str_elements1.'<br />row['.$key2.']: '.$str_elements2.'<br /><br />We remove row['.$key1.']', $this->pObj->extKey, 0);
            }
            // Development Reporting System

            // We don't need a compare with a removed row: count one element further
            $key1       = key($arr_marker_values);
            $elements1  = $arr_marker_values[$key1];


          }
          if(!$b_unset)
          {
            $arr_diff = array_diff_assoc($elements2, $elements1);
            if(count($arr_diff) == 0)
            {
              // There is no difference
              unset($arr_marker_values[$key1]);
              // Remove the current row

              // Development Reporting System
              if ($this->pObj->b_drs_ttc)
              {
                foreach ($elements1 as $key_element1 => $value_element1)
                {
                  if (!$value_element1)
                  {
                    $elements1[$key_element1] = 'NULL';
                  }
                }
                foreach ($elements2 as $key_element2 => $value_element2)
                {
                  if (!$value_element2)
                  {
                    $elements2[$key_element2] = '<i>NULL</i>';
                  }
                }
                $str_elements1 = implode(', ', $elements1);
                $str_elements2 = implode(', ', $elements2);
                t3lib_div::devlog('[INFO/TTC] Same row:<br />row['.$key1.']: '.$str_elements1.'<br />row['.$key2.']: '.$str_elements2.'<br /><br />We remove row['.$key1.']', $this->pObj->extKey, 0);
              }
              // Development Reporting System

              // We don't need a compare with a removed row: count one element further
              $key1       = key($arr_marker_values);
              $elements1  = $arr_marker_values[$key1];
            }
          }
        }
        // Loop through all rows of the compare array
      }
      // Loop through all marker rows
    }


    /////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_ttc)
    {
      if (is_array($arr_marker_values))
      {
        $str_prompt = '';
        foreach ($arr_marker_values as $key1 => $elements1)
        {
          foreach ($elements1 as $key_element => $value_element)
          {
            if (!$value_element)
            {
              $elements1[$key_element] = 'NULL';
            }
          }
          $str_elements1 = implode(', ', $elements1);
          $str_elements1 = htmlspecialchars($str_elements1);
          if (strlen($str_elements1) > $this->pObj->i_drs_max_sql_result_len)
          {
            $str_elements1 = substr($str_elements1, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          $str_prompt .= '<br />row['.$key1.']: '.$str_elements1;
        }
        t3lib_div::devlog('[INFO/TTC] Unique rows: '.$str_prompt, $this->pObj->extKey, 0);
      }
    }


    /////////////////////////////////////
    //
    // Do we have a limit?

    $b_limit = false;
    foreach ($arr_limits as $key_limits => $row_limits)
    {
      if (intval($row_limits['limit']) > -1)
      {
        $b_limit = true;
        if ($this->pObj->b_drs_ttc)
        {
          t3lib_div::devlog('[INFO/TTC] We have a limit. Some TT_CONTAINER.limit is bigger than -1 (is unlimited).', $this->pObj->extKey, 0);
        }
        break;
      }
    }


    /////////////////////////////////////
    //
    // We don't have a limit. Return without changings.

    if (!$b_limit)
    {
      return $arr_marker_values;
    }


    /////////////////////////////////////
    //
    // Check the limit. Remove all marker rows, which aren't inside any limit.

    $sql_row_keys = array_keys($this->rows[0]);
    //Loop through all marker rows
    foreach((array) $arr_marker_values as $key_marker => $elements_marker)
    {
      $arr_marker_uidfields = array_keys($elements_marker);
      // Loop through all rows of the limit array
      foreach((array) $arr_limits as $key_limits => $row_limits)
      {

        $str_limit_uidfield  = $row_limits['uidField'];
        if (!in_array($str_limit_uidfield, $sql_row_keys))
        {
          if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_sql || $this->pObj->b_drs_error)
          {
            list($str_table, $str_field) = explode('.', $str_limit_uidfield);
            t3lib_div::devlog('[ERROR/TTC, SQL] '.$str_limit_uidfield.' is missing in the SQL SELECT statement.', $this->pObj->extKey, 3);
            t3lib_div::devlog('[INFO/TTC, SQL] Records from '.$str_table.' won\'t be displayed.', $this->pObj->extKey, 0);
            t3lib_div::devlog('[HELP/TTC, SQL] Add it to the SELECT statement like ... '.$str_limit_uidfield.' AS `'.$str_limit_uidfield.'`, ...', $this->pObj->extKey, 1);
          }
        }

        // Has the marker row the uid of the limit row?
        if (in_array($str_limit_uidfield, $arr_marker_uidfields))
        {
          // The marker row has the uid of the limit row

          $b_store_uid     = false;
          $b_remove_marker = false;

          // Did we stored uids before?
          if (is_array($arr_uids[$str_limit_uidfield]))
          {
            // Yes, we stored uids before.
            // Did we stored the current uid before?
            if (!in_array($elements_marker[$str_limit_uidfield], $arr_uids[$str_limit_uidfield]))
            {
              // No, we didn't stored the uid before.
              $b_store_uid = true;
            }
          }
          else {
            // Yes, the current uid is inside the limit.
            $b_store_uid = true;
          }

          // Should we store the current uid?
          if ($b_store_uid)
          {
            // Has the uid any value?
            if ($elements_marker[$str_limit_uidfield] == NULL)
            {
              // No, the value of the uid is NULL.
              $b_store_uid     = false;
              // Don't store any non existiong uids.
              $b_remove_marker = true;
              // Remove the current marker row
            }
          }
          // Should we store the current uid?

          // Should we store the current uid?
          if ($b_store_uid)
          {
            // Yes, we should store the current uid.

            $b_store_uid = false;
            // Set $b_store_uid to false for another test

            // Is the current uid inside the limit?
            if (intval($row_limits['limit']) < 0)
            {
              // There insn't any limit. Uid is inside the limit, that's understood.
              $b_store_uid = true;
            }
            elseif (count($arr_uids[$str_limit_uidfield]) < intval($row_limits['limit']))
            {
              // The uid is inside the limit.
              $b_store_uid = true;
            }
            // Is the current uid inside the limit?

            // Shouldn't we store the current uid?
            if (!$b_store_uid)
            {
              $b_remove_marker = true;
              // Remove the current marker row
            }

          }
          // Should we store the current uid?

          // Should we store the current uid?
          if ($b_store_uid)
          {
            $arr_uids[$str_limit_uidfield][] = $elements_marker[$str_limit_uidfield];
            // Store the uid.
          }
          // Should we store the current uid?

          // Should we remove the marker?
          if ($b_remove_marker)
          {
            if ($this->pObj->b_drs_ttc)
            {
              $str_prompt = '';
              $str_row    = '';
              foreach ($elements_marker as $key => $value)
              {
                if (!$value)
                {
                  $value = 'NULL';
                }
                $str_prompt .= '<br />['.$key.'] '.$value;
                $str_row    .= $value.', ';
              }
              $str_row = substr($str_row, 0, -2);
              $str_row = '<br />'.$str_row;
              t3lib_div::devlog('[INFO/TTC] The following row isn\'t inside the limit or some uid is NULL: '.$str_row.$str_prompt, $this->pObj->extKey, 0);
              t3lib_div::devlog('[INFO/TTC] The row is removed.', $this->pObj->extKey, 0);
            }
            unset($arr_marker_values[$key_marker]);
            // Remove the current marker row
          }
          // Should we remove the marker?


        }
        // Has the marker row the uid of the limit row?

      }
      // Loop through all rows of the limit array

    }
    // Loop through all marker rows


    /////////////////////////////////////
    //
    // Return the result

    return $arr_marker_values;
  }













/**
 * Allocate the marker array with real values from the SQL result.
 *
 * @param	array		$arr_marker_values: The marker array
 * @return	array		The ordered marker array
 */
  function get_marker_ordered($arr_marker_values)
  {
    if (!is_array($arr_marker_values))
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/TTC] $arr_marker_values hasn\'t  any element. There won\'t be any ordering.', $this->pObj->extKey, 2);
      }
      return $arr_marker_values;
    }

    /////////////////////////////////////
    //
    // Get the key for the element with the order field

    $arr_ttc_path = explode('.', $this->str_ttc_path);  // I.e. ttContainer.20.10
    $str_curr_key = end($arr_ttc_path);                 // I.e. 10
    $str_curr_key = $str_curr_key.'.';                  // I.e  10.


    /////////////////////////////////////
    //
    // Get the TypoScript order array

    $arr_order = $this->arr_tt_container[$str_curr_key]['order.'];
    if(!is_array($arr_order)) {
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.' hasn\'t  any order array.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TTC] If you like to order the result, maintaine order.field and order.desc.', $this->pObj->extKey, 1);
      }
      return $arr_marker_values;
      // Return the unordered array
    }


    /////////////////////////////////////
    //
    // Check and init the order field

    if (!$arr_order['field'])
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/TTC] '.$this->str_ttc_path.'.order.field is empty.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/TTC] Please maintaine the order.field.', $this->pObj->extKey, 1);
      }
      return $arr_marker_values;
      // Return the unordered array
    }
    else
    {
      $str_order_field = $arr_order['field'];
    }


    /////////////////////////////////////
    //
    // Init the ASC/DESC value

    if (!$arr_order['desc'])
    {
      $arr_order['desc'] = false;
      $str_order_sort    = SORT_ASC;
      $str_drs_order     = $str_order_field.' SORT_ASC';
    }
    else
    {
      $arr_order['desc'] = true;
      $str_order_sort    = SORT_DESC;
      $str_drs_order     = $str_order_field.' SORT_DESC';
    }


    /////////////////////////////////////
    //
    // Sort the array


    $b_first  = true;
    $arr_sort = array();
    foreach ($arr_marker_values as $key_row => $arr_row)
    {
      if ($b_first)
      {
        if (!array_key_exists($str_order_field, $arr_row)) {
          if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_warn)
          {
            $str_existing_keys = implode(', ', array_keys($arr_row));
            t3lib_div::devlog('[WARN/TTC] The field '.$str_order_field.' isn\'t available for '.$this->str_ttc_path, $this->pObj->extKey, 2);
            t3lib_div::devlog('[INFO/TTC] Available keys are: '.$str_existing_keys, $this->pObj->extKey, 0);
            t3lib_div::devlog('[INFO/TTC] The result won\'t be ordered.', $this->pObj->extKey, 0);
            t3lib_div::devlog('[HELP/TTC] Please maintaine the order.field.', $this->pObj->extKey, 1);
          }
          return $arr_marker_values;
          // Return the unordered array
        }
        $b_first = false;
      }
      $arr_sort[$str_order_field][] = $arr_row[$str_order_field];
    }

//// #45888, ttContainer.40.20.20
//if( $this->str_ttc_path = 'ttContainer.40.20.20' )
//{
//  var_dump( __METHOD__, __LINE__, $this->str_ttc_path, $arr_marker_values );
//}

    if (count($arr_sort) < 1)
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[WARN/TTC] '.$this->str_ttc_path.': There is no result array for multisort.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/TTC] The result won\'t be ordered.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TTC] The only help is to read the logs below.', $this->pObj->extKey, 1);
      }
      return $arr_marker_values;
      // Return the unordered array
    }
    else
    {
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$this->str_ttc_path.': Result will be ordered by '.$str_drs_order, $this->pObj->extKey, 0);
      }
      array_multisort($arr_sort[$str_order_field], $str_order_sort, $arr_marker_values);
    }
// #45888
if( $this->str_ttc_path = 'ttContainer.40.20.20' )
{
  var_dump( __METHOD__, __LINE__, $arr_marker_values );
}

    /////////////////////////////////////
    //
    // Return the ordered markers

    return $arr_marker_values;
  }




















  /**
 * Wrap every marker. Extra process, if there is no row and if there is a no record message
 *
 * @param	array		$arr_ttc_values: TypoScript array of the current TTC_STDWRAP or TTC_COA
 * @param	array		$arr_marker_values: Rows with the pairs marker and value like: $arr_markers[0][tx_civserv_service.uid] = 112
 * @param	string		$str_ttc_type: Type of the TTC. TTC_COA or TTC_STDWRAP
 * @return	array		Array with the elements error and data. Data contains the template.
 */
  function get_wrapped_marker($arr_ttc_values, $arr_marker_values, $str_ttc_type)
  {

    $arr_return['error']['status']  = false;

    $template                       = false;
    $arr_return['data']['template'] = $template;


    ///////////////////////////////////////
    //
    // Development Reporting System

    $arr_path_container = explode('.', $this->str_ttc_path);
    $i_last             = count($arr_path_container[$i_last]) - 1;
    unset($arr_path_container[$i_last]);
    $str_path_container = implode ('.', $arr_path_container);

    ///////////////////////////////////////
    //
    // Return if there isn't any marker

    $b_markers = true;
    if (!is_array($arr_marker_values))
    {
      $b_markers = false;
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$str_path_container.' hasn\'t any array with values for markers.', $this->pObj->extKey, 0);
      }
    }
    if (count($arr_marker_values) == 0)
    {
      $b_markers = false;
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] '.$str_path_container.' has an array with markers, but markers hasn\'t any element.', $this->pObj->extKey, 0);
      }
    }
    if (!$b_markers)
    {
      // There is no marker row
      return $arr_return;
    }


    ///////////////////////////////////////
    //
    // TypoScript with marker rows

// #45888, ttContainer.40.20.20
//if( $this->str_ttc_path = 'ttContainer.40.20.20' )
//{
//  var_dump( __METHOD__, __LINE__, $this->str_ttc_path, $arr_marker_values );
//}
    $arr_ttc_values_substituted = $arr_ttc_values;
    // Update all markers in the current TypoScript recursive
    foreach((array) $arr_marker_values as $key_key_marker => $arr_values)
    {
if( $this->str_ttc_path = 'ttContainer.40.20.20' )
{
  var_dump( __METHOD__, __LINE__, $arr_values );
}
      $arr_ttc_values_substituted = $this->pObj->objMarker->substitute_marker_recurs($arr_ttc_values, $arr_values);
      // Get the current TypoScript array with substituted markers

      $arr_curr_realurl_conf = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'];
      // Store the current realurl configuration

      $arr_result = $this->update_realurl($arr_ttc_values_substituted);
      // Update the realurl configuration, if there is a typolink with the extensions.browser.realurl_template property
      if ($arr_result['error']['status'])
      {
        return $arr_result;
      }
      unset($arr_result);

      $arr_result = $this->wrap_marker($arr_ttc_values_substituted, $str_ttc_type);
      if ($arr_result['error']['status'])
      {
        return $arr_result;
      }
      $template .= $arr_result['data']['template'];
      unset($arr_result);


      $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = $arr_curr_realurl_conf;
      // Load the current realurl configuration
    }
    // Update all markers in the current TypoScript recursive


    ///////////////////////////////////////
    //
    // Return the template

    $arr_return['data']['template'] = $template;

    return $arr_return;
  }















  /***********************************************
   *
   * Helper methods for the markers
   *
   **********************************************/








 /**
  * Process a marker with the cObj->stdWrap or cObj->COBJ_ARRAY method
  *
  * @param	array		$arr_ttc_values: TypoScript array of the current TTC_STDWRAP or TTC_COA
  * @param	string		$str_ttc_type: Type of the TTC. TTC_COA or TTC_STDWRAP
  * @return	array		Array with the elements error and data. Data contains the template.
  */
  function wrap_marker($arr_ttc_values, $str_ttc_type)
  {
    $arr_return['error']['status']  = false;

    $arr_tsConf = $arr_ttc_values;
    // Get the TypoScript configuration

    if ($str_ttc_type == 'TTC_STDWRAP')
    {
      $str_value  = $arr_ttc_values['value'];
      // Get the value, if there is one
      $template = $this->pObj->local_cObj->stdWrap($str_value, $arr_tsConf);
    }
    elseif ($str_ttc_type == 'TTC_COA')
    {
      $template = $this->pObj->local_cObj->COBJ_ARRAY($arr_tsConf, $ext='');
      // $ext='' can be INT. See documentation of the method cObj->COBJ_ARRAY
    }
    else {
      // We don't have a TTC_STDWRAP object nor a TTC_COA object
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] '.$str_ttc_type.' isn\'t defined!', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttcv_prompt_object').'</p>';
      $str_prompt .= '<p style="color:red;font-weight:bold;">Object: '.$str_ttc_type.'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }

    $arr_return['data']['template'] = $template;
    return $arr_return;

  }















  /***********************************************
   *
   * Check the TypoScript Configuration
   *
   **********************************************/






  /**
 * Check the TypoScript Configuration of a TT_CONTAINER
 *
 * @param	array		$arr_container: The TypoScript of the current TT_CONTAINER
 * @return	array		Array with the element error.
 */
  function check_container($arr_container)
  {

    $arr_return['error']['status']  = false;


    ///////////////////////////////////////
    //
    // Is the TT_CONTAINER an array?

    if (!is_array($arr_container))
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] TT_CONTAINER isn\'t an array!', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttc_prompt_array').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


    ///////////////////////////////////////
    //
    // Has the TT_CONTAINER the tableFieldUid?

    if (!$arr_container['tableFieldUid'])
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] The TT_CONTAINER field tableFieldUid hasn\'t any value!', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttc_prompt_tablefielduid').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }

    return $arr_return;
  }












  /**
 * Check the TypoScript Configuration of a TTC_STDWRAP or TTC_COA
 *
 * @param	array		$arr_container: The TypoScript of the current TTC_STDWRAP or TTC_COA
 * @return	array		Array with the element error.
 */
  function check_container_value($arr_container_value)
  {

    $arr_return['error']['status']  = false;


    ///////////////////////////////////////
    //
    // Is the TTC_STDWRAP or TTC_COA an array?

    if (!is_array($arr_container_value))
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] TTC_STDWRAP/TTC_COA isn\'t an array!', $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttcv_prompt_array').'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }

    return $arr_return;
  }















  /***********************************************
   *
   * Realurl
   *
   **********************************************/






  /**
 * Update realurl configuration, if there is a typolink to another page with a extensions.browser.realurl_template property.
 *
 * @param	array		$arr_ttc_values: TypoScript array of the current TTC_STDWRAP or TTC_COA
 * @return	array		Array with the element error.
 */
  function update_realurl($arr_ttc_values)
  {

    $arr_return['error']['status'] = false;


    //////////////////////////////////////////
    //
    // Is there a civserv realurl configuration?

    $str_realurl_template = $arr_ttc_values['typolink.']['extensions.']['browser.']['realurl_template'];
    // Get the civserv realurl configuration, which should be loaded. I.e: form
    if (!$str_realurl_template)
    {
      return $arr_return;
      // There isn't any value for realurl configuration
    }


    //////////////////////////////////////////
    //
    // Is realurl activated?

    if (t3lib_extMgm::isLoaded('realurl'))
    {
      if ($this->pObj->b_drs_realurl || $this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/REALURL, TTC] Extension realurl is loaded.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/REALURL, TTC] '.$this->pObj->extKey.' trusts in a proper realurl configuration like "config.tx_realurl_enable = 1".', $this->pObj->extKey, 0);
      }
    }
    else
    {
      if ($this->pObj->b_drs_realurl || $this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[WARN/REALURL, TTC] You are using the property stdWrap.typolink.extensions.browser.realurl_template. But the extension realurl isn\'t loaded.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/REALURL, TTC] If you want to use realurl, activate it in your extension manager.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[INFO/REALURL, TTC] Return without any action!', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }


    //////////////////////////////////////////
    //
    // Load configuration

    $str_realurl_url = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['realurl']['config']['realurl_url'];
    // I.e. _DEFAULT
    $str_realurl_var = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['realurl']['config']['realurl_var'];
    // I.e. postVarSets

    unset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'][$str_realurl_url][$str_realurl_var]['_DEFAULT']);
    // I.e. postVarSets $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT']


    //////////////////////////////////////////
    //
    // Error management

    $b_error = false;
    $arr_default = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['realurl']['templates']['default'];
    if (!is_array($arr_default))
    {
      $b_error = true;
    }

    if (!$b_error)
    {
      $arr_civserv   = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['realurl']['templates'][$str_realurl_template]['addVars'];
      if (!is_array($arr_civserv))
      {
        $b_error = true;
      }
    }

    if ($b_error)
    {
      $str_header  = '<h1 style="color:red;">'.$this->pObj->pi_getLL('error_ttc_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pObj->pi_getLL('error_ttcv_prompt_realurl_1').'</p>';
      $str_prompt .= '<p style="color:red;font-weight:bold;">Array: $GLOBALS[TYPO3_CONF_VARS][EXTCONF][browser][realurl][templates]</p>';
      $str_prompt .= '<p style="color:red;font-weight:bold;">Definded values: \''.$str_keys.'\'</p>';
      $str_prompt .= '<p style="color:red;font-weight:bold;">Your value: \''.$str_realurl_template.'\'</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }


    //////////////////////////////////////////
    //
    // Update realurl configuration

    $arr_add_to_realurl = array_merge($arr_civserv, $arr_default);
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'] = $arr_add_to_realurl;

    return $arr_return;
  }











}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_ttcontainer.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_ttcontainer.php']);
}

?>