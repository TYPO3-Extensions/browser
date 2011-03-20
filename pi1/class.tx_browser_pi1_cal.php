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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_filter bundles methods for rendering and processing filters and category menues
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.6.4
* @since 3.6.0
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   58: class tx_browser_pi1_cal
 *   75:     function __construct($pObj)
 *
 *              SECTION: Filter
 *  110:     function area_init()
 *  192:     function area_interval($arr_ts, $arr_values, $tableField)
 *  222:     function area_strings($arr_ts, $arr_values, $tableField)
 *
 *              SECTION: Area Helper
 *  293:     function area_set_hits($arr_ts, $arr_values, $tableField)
 *  432:     function area_set_tsPeriod($arr_ts, $tableField)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_cal {

  var $str_area_case = null;
  // [string] day, week, month or year

  var $arr_area = null;
  // [array] Array with area configuration for every filter, if there is an area configured

  var $arr_hits = null;
  // [array] Hits per tablefield (filter) and  item

  var $arr_url_tsKey = null;
  // [array] Array with area url and its real tsKey



  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($pObj) {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Filter
  *
  **********************************************/










  /**
 * area_init: Check configuration and init global arr_area
 *
 * @return  void
 * @version 3.6.0
 * @since 3.6.0
 * @link  http://forge.typo3.org/issues/11402  TYPO3-Browser: Filter for area
 */
  function area_init()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];

    foreach ($this->pObj->objFilter->arr_conf_tableFields as $tableField)
    {
      list ($table, $field) = explode('.', $tableField);
      $arr_ts = $conf_view['filter.'][$table . '.'][$field . '.'];
      if ($arr_ts['area'])
      {
        switch (true)
        {
          case ($arr_ts['area.']['interval']):
            if(!is_array($arr_ts['area.']['interval.']))
            {
              if ($this->pObj->b_drs_warn) 
              {
                t3lib_div :: devLog('[WARN/CALENDAR] \'area.interval\' doesn\'t contain any element!', $this->pObj->extKey, 2);
                t3lib_div :: devLog('[HELP/CALENDAR] Please take care to a proper area configuration.', $this->pObj->extKey, 1);
              }
              break;
            }
            $this->arr_area[$tableField]['key'] = 'interval';
            $arr_ts = $this->area_set_tsPeriod($arr_ts, $tableField);
            //var_dump(__METHOD__ . ' (' . __LINE__ . ')', $arr_ts);
            break;
          case ($arr_ts['area.']['strings']):
            if(!is_array($arr_ts['area.']['strings.']))
            {
              if ($this->pObj->b_drs_warn) 
              {
                t3lib_div :: devLog('[WARN/CALENDAR] \'area.strings\' doesn\'t contain any element!', $this->pObj->extKey, 2);
                t3lib_div :: devLog('[HELP/CALENDAR] Please take care to a proper area configuration.', $this->pObj->extKey, 1);
              }
              break;
            }
            $this->arr_area[$tableField]['key'] = 'strings';
            break;
          default :
            if ($this->pObj->b_drs_warn) 
            {
              t3lib_div :: devLog('[WARN/CALENDAR] \'area.\' contains an undefined element.', $this->pObj->extKey, 2);
              t3lib_div :: devLog('[HELP/CALENDAR] Please take care to a proper area configuration.', $this->pObj->extKey, 1);
            }
        }
      }
    }
  }









  /**
 * area_interval():  Handle the area for an interval
 *                   Add to the tsConf the array ['area.']['interval.']['options.']['fields.]
 *                   Return an array $key => $value generated by the tsConf
 *
 * @param array   $arr_ts: The TypoScript configuration of the current filter
 * @param array   $arr_values: The values for the current filter
 * @param string    $tableField: The current table.field
 * @return  array   Data array with $key => $value
 * @version 3.6.0
 * @since 3.6.0
 */
  function area_interval($arr_ts, $arr_values, $tableField)
  {
    list ($table, $field) = explode('.', $tableField);

      // Get an auto generated ts configuration array 
    $arr_ts = $this->area_set_tsPeriod($arr_ts, $tableField);

    $arr_return['data']['values'] = $this->area_set_hits($arr_ts, $arr_values, $tableField);
    return $arr_return;
  }









  /**
 * area_strings(): Handle the area for strings - manual configured array of $key => $value
 *
 * @param array   $arr_ts: The TypoScript configuration of the current filter
 * @param array   $arr_values: The values for the current filter
 * @param string    $tableField: The current table.field
 * @return  void
 * @version 3.6.0
 * @since 3.6.0
 */
  function area_strings($arr_ts, $arr_values, $tableField)
  {

    list ($table, $field) = explode('.', $tableField);



      /////////////////////////////////////////////////////////////////
      //
      // RETURN, array error

    if (!is_array($arr_ts['area.']['strings.']['options.']['fields.']))
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL] strings.options.fields isn\'t an array.', $this->pObj->extKey, 2);
        t3lib_div :: devLog('[INFO/CAL] area won\'t be handled!', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/CAL] Please take care to a proper configuration.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System
      $arr_return['data']['values'] = $arr_values;
      return $arr_return;
    }
      // RETURN, array error



      /////////////////////////////////////////////////////////////////
      //
      // RETURN updated values

    $arr_return['data']['values'] = $this->area_set_hits($arr_ts, $arr_values, $tableField);
//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if (!($pos === false)) var_dump('cal 252', $arr_return);
    return $arr_return;
      // RETURN updated values
  }









  /***********************************************
  *
  * Area Helper
  *
  **********************************************/









  /**
 * area_get_urlPeriod(): Get the get parameter from TypoScript
 *                       From tsConf the array ['area.']['interval.' || 'string.']['options.']['fields.]
 *                       Return wrapped value from 'url_stdWrap'
 *                       #13920, 110319, dwildt
 *
 * @param array   $arr_ts: The TypoScript configuration of the current filter
 * @param string    $tableField: The current table.field
 * @param string    $tsKey: Current tsKey like 10, 20, 30, ...
 * @return  array   $tsKey: I.e. 2011_Jan, 2011_Feb, 2011_Mar, ...
 * @version 3.6.4
 * @since 3.6.4
 */
  function area_get_urlPeriod($arr_ts, $tableField, $tsKey)
  {
      ///////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any tsKey

    if ($tsKey == null)
    {
      return $tsKey;
    }
      // RETURN there isn't any tsKey



      ///////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any area for $tableField

    if (empty ($this->pObj->objCal->arr_area[$tableField]['key']))
    {
      return $tsKey;
    }
      // RETURN there isn't any area for $tableField



      ///////////////////////////////////////////////////////////////
      //
      // Move key (10, 20, 30, ...) to url_stdWrap (i.e: 2011_Jan, 2011_Feb, 2011_Mar, ...)

    $str_area_key = $this->pObj->objCal->arr_area[$tableField]['key'];
    switch ($str_area_key)
    {
      //case ('from_to_fields') :
      case ('strings') :
      case ('interval') :
        if(isset($arr_ts['area.'][$str_area_key . '.']['options.']['fields.'][$tsKey . '.']['url_stdWrap.']))
        {
          $url_conf = $arr_ts['area.'][$str_area_key . '.']['options.']['fields.'][$tsKey . '.']['url_stdWrap.'];
          $tsKeyUrl = $this->pObj->local_cObj->stdWrap($url_conf['value'], $url_conf);
        }
        break;
      default:
        echo __METHOD__ . ' (' . __LINE__ . '): undefined value in switch '.$this->pObj->objCal->arr_area[$tableField]['key'];
        exit;
    }
      // Move key (10, 20, 30, ...) to url_stdWrap (i.e: 2011_Jan, 2011_Feb, 2011_Mar, ...)

    return $tsKeyUrl;
  }









  /**
 * area_get_tsKey_from_urlPeriod(): Get the real tsKey from TypoScript url_stdWrap
 *                                  I.e $str_urlPeriod: 2011M%C3%A4r, 2011Apr, 2011Mai, ...
 *                                  Returns i.e: 10, 20, 30, ...
 *                                  #13920, 110319, dwildt
 *
 * @param string    $tableField: The current table.field
 * @return  string   $str_urlPeriod: I.e. 2011M%C3%A4r, 2011Apr, 2011Mai, ...
 * @version 3.6.4
 * @since 3.6.4
 */
  function area_get_tsKey_from_urlPeriod($tableField, $str_urlPeriod)
  {
    $tsKey = $str_urlPeriod;



      ///////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any area for $tableField

    if (empty ($this->pObj->objCal->arr_area[$tableField]['key']))
    {
      return $tsKey;
    }
      // RETURN there isn't any area for $tableField



      ///////////////////////////////////////////////////////////////
      //
      // RETURN real tsKey

    if(isset($this->arr_url_tsKey[$tableField][$str_urlPeriod]))
    {
      return $this->arr_url_tsKey[$tableField][$str_urlPeriod];
    }
    
      // Try to fetch the tsKey by a raw URL encoded value
    $str_urlPeriod = rawurlencode($str_urlPeriod);
    if(isset($this->arr_url_tsKey[$tableField][$str_urlPeriod]))
    {
      return $this->arr_url_tsKey[$tableField][$str_urlPeriod];
    }
      // RETURN real tsKey

      // RETURN given tsKey
    return $tsKey;
  }









  /**
 * area_set_hits(): Recalculate the hits per item. Return updated values.
 *
 * @param array   $arr_ts: The TypoScript configuration of the current filter
 * @param array   $arr_values: The values for the current filter
 * @param string    $tableField: The current table.field
 * @return  array   Array with the updated values
 * @version 3.6.0
 * @since 3.6.0
 */
  function area_set_hits($arr_ts, $arr_values, $tableField)
  {

    list ($table, $field) = explode('.', $tableField);
    $str_case             = $this->arr_area[$tableField]['key'];



//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if (!($pos === false)) var_dump('cal 674', $str_case);



      /////////////////////////////////////////////////////////////////
      //
      // Wrap items, recalculate hits

    $arr_fields = $arr_ts['area.'][$str_case . '.']['options.']['fields.'];
    foreach ($arr_fields as $keyWiDot => $arr_string)
    {
      $key        = rtrim($keyWiDot, '.');

        // Wrap item from
      $from       = $arr_string['valueFrom_stdWrap.']['value'];
      $from_conf  = $arr_string['valueFrom_stdWrap.'];
      $from_conf  = $this->pObj->objZz->substitute_t3globals_recurs($from_conf);
      $from       = $this->pObj->local_cObj->stdWrap($from, $from_conf);

        // Wrap item to
      $to         = $arr_string['valueTo_stdWrap.']['value'];
      $to_conf    = $arr_string['valueTo_stdWrap.'];
      $to         = $this->pObj->objZz->substitute_t3globals_recurs($to);
      $to         = $this->pObj->local_cObj->stdWrap($to, $to_conf);

        // Wrap item value
      $value      = $arr_string['value_stdWrap.']['value'];
      $value_conf = $arr_string['value_stdWrap.'];
      $value_conf = $this->pObj->objZz->substitute_t3globals_recurs($value_conf);
      $value      = $this->pObj->local_cObj->stdWrap($value, $value_conf);

      $arr_values_new[$key] = $value;

        // Recalculate hits
      foreach ($arr_values as $keyValue => $valueValue)
      {
          // Default value: from
        $currFrom = $from;
          // Default value: to
        $currTo = $to;

          // Current table is the local table
        if ($table == $this->pObj->localTable)
        {
          if (empty ($currFrom))
          {
            $currFrom = $keyValue;
          }
          if (empty ($currTo))
          {
            $currTo = $keyValue;
          }
            // Default value: hits
          if (!isset ($arr_hits[$key]))
          {
            $arr_hits[$key] = 0;
          }
            // Default value: hits
          if ($keyValue >= $currFrom && $keyValue <= $currTo)
          {
            $arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFilter->arr_hits[$tableField][$keyValue];
          }
        }
          // Current table is the local table

          // Current table is a foreign table
        if ($table != $this->pObj->localTable)
        {
          if (empty ($currFrom))
          {
            $currFrom = $valueValue;
          }
          if (empty ($currTo))
          {
            if($valueValue >= $currFrom)
            {
              $currTo = $valueValue;
            }
            if($valueValue < $currFrom)
            {
              $currTo = $currFrom;
            }
          }
            // Default value: hits
          if (!isset ($arr_hits[$key]))
          {
            $arr_hits[$key] = 0;
          }
            // Default value: hits
          if ($valueValue >= $currFrom && $valueValue <= $currTo)
          {
            $arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFilter->arr_hits[$tableField][$keyValue];
          }
        }
          // Current table is a foreign table
      }
        // Recalculate hits
    }
      // Wrap items, recalculate hits



      // Set the global arr_hits
    unset($this->pObj->objFilter->arr_hits[$tableField]);
    $this->pObj->objFilter->arr_hits[$tableField] = $arr_hits;
      // Set the global arr_hits

      // RETURN the result
    return $arr_values_new;
  }









  /**
 * area_set_tsPeriod():  Set an auto-generated period in the TypoScript
 *                       Add to the tsConf the array ['area.']['interval.']['options.']['fields.]
 *                       Return an updated $arr_ts
 *
 * @param array   $arr_ts: The TypoScript configuration of the current filter
 * @param string    $tableField: The current table.field
 * @return  array   Updated $arr_ts
 * @version 3.6.4
 * @since 3.6.0
 */
  function area_set_tsPeriod($arr_ts, $tableField)
  {
    list ($table, $field) = explode('.', $tableField);
    $arr_interval = $arr_ts['area.']['interval.'];



      ///////////////////////////////////////////////////////////////
      //
      // Get case

    switch($arr_interval['case'])
    {
      case('day'):
      case('week'):
      case('month'):
      case('year'):
        $this->str_area_case = $arr_interval['case'];
        break;
      default:
        $this->str_area_case = 'year';
          // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div :: devLog('[WARN/CAL] area.interval.case is undefined: ' . $arr_interval['case'], $this->pObj->extKey, 2);
          t3lib_div :: devLog('[INFO/CAL] case is set to year', $this->pObj->extKey, 0);
          t3lib_div :: devLog('[HELP/CAL] Please take care to a proper configuration.', $this->pObj->extKey, 1);
        }
          // DRS - Development Reporting System
    }
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_cal)
    {
      t3lib_div :: devLog('[INFO/CAL] area.interval.case is ' . $this->str_area_case, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
      // Get case



      ///////////////////////////////////////////////////////////////
      //
      // Convert to timestamp

    $bool_toTimestamp = $arr_interval['compare_wiTimeStamp'];
    if(!$bool_toTimestamp)
    {
      $bool_toTimestamp = true;
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL] area.interval.compare_wiTimeStamp is false.', $this->pObj->extKey, 2);
        t3lib_div :: devLog('[INFO/CAL] compare_wiTimeStamp is set to true', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/CAL] Only true will be accepted in this Browser version.', $this->pObj->extKey, 1);
      }
        // DRS - Development Reporting System
    }
      // Convert to timestamp



      ///////////////////////////////////////////////////////////////
      //
      // Get period configuration

    $arr_period_conf    = $arr_interval[$this->str_area_case . '.'];
    $start_period       = $arr_period_conf['start_period.']['stdWrap.']['value'];
    $start_period_conf  = $arr_period_conf['start_period.']['stdWrap.'];
    $start_period_conf  = $this->pObj->objZz->substitute_t3globals_recurs($start_period_conf);
    $start_period       = $this->pObj->local_cObj->stdWrap($start_period, $start_period_conf);
    if($arr_period_conf['start_period.']['use_php_strtotime'])
    {
      $tmp_timestamp = strtotime($start_period);
      if(!$tmp_timestamp)
      {
          // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div :: devLog('[WARN/CAL] area.interval.start_period hasn\'t any strtotime format: ' . $start_period, $this->pObj->extKey, 2);
          t3lib_div :: devLog('[INFO/CAL] start_period is set to now.', $this->pObj->extKey, 0);
          t3lib_div :: devLog('[HELP/CAL] Please take car of a proper configuration.', $this->pObj->extKey, 1);
        }
          // DRS - Development Reporting System
        $start_period = strtotime('now');
      }
      if($tmp_timestamp)
      {
        $start_period = $tmp_timestamp;
      }
    }



      ///////////////////////////////////////////////////////////////
      //
      // Set point of start

      // Beginn at 00:00:00 in every case
    $hour   = 0;
    $minute = 0;
    $second = 0;
      // Beginn at 00:00:00 in every case
    switch($this->str_area_case)
    {
      case('day'):
        $month          = date('n', $start_period);
        $day            = date('j', $start_period);
        $year           = date('Y', $start_period);
        $start_period   = mktime ($hour, $minute, $second, $month, $day, $year);
        break;
      case('week'):
          // Firstday of the given week
        $firstday       = $arr_period_conf['firstday_stdWrap.']['value'];
        $firstday_conf  = $arr_period_conf['firstday_stdWrap.'];
        $firstday_conf  = $this->pObj->objZz->substitute_t3globals_recurs($firstday_conf);
        $firstday       = $this->pObj->local_cObj->stdWrap($firstday, $firstday_conf);
        $start_period   = strtotime(date('Y', $start_period) . 'W' . date('W', $start_period) . $firstday);
          // Firstday of the given week
        $month          = date('n', $start_period);
        $day            = date('j', $start_period);
        $year           = date('Y', $start_period);
        $start_period   = mktime ($hour, $minute, $second, $month, $day, $year);
        break;
      case('month'):
        $month          = date('n', $start_period);
        $day            = 1;
        $year           = date('Y', $start_period);
        $start_period   = mktime ($hour, $minute, $second, $month, $day, $year);
        break;
      case('year'):
        $month          = 1;
        $day            = 1;
        $year           = date('Y', $start_period);
        $start_period   = mktime ($hour, $minute, $second, $month, $day, $year);
        break;
    }
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_cal)
    {
      t3lib_div :: devLog('[INFO/CAL] area.interval.start_period is ' . date('c', $start_period), $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
      // Set point of start



      /////////////////////////////////////////////////////////////////
      //
      // Set fields array

    $times       = $arr_period_conf['times_stdWrap.']['value'];
    $times_conf  = $arr_period_conf['times_stdWrap.'];
    $times_conf  = $this->pObj->objZz->substitute_t3globals_recurs($times_conf);
    $times       = $this->pObj->local_cObj->stdWrap($times, $times_conf);
    switch($arr_interval['case'])
    {
      case('day'):
        $offset = '+1 day';
        break;
      case('week'):
        $offset = '+1 week';
        break;
      case('month'):
        $offset = '+1 month';
        break;
      case('year'):
        $offset = '+1 year';
        break;
    }
    $from = $start_period;
    $to   = strtotime($offset, $start_period);
    for ($int_element = 10; $int_element <= ($times * 10); $int_element = $int_element + 10)
    {
      $arr_fields[$int_element . '.']['valueFrom_stdWrap' . '.']['value'] = $from;
      $arr_fields[$int_element . '.']['valueTo_stdWrap' . '.']['value']   = $to;
      $arr_fields[$int_element . '.']['value_stdWrap' . '.']              = $arr_period_conf['value_stdWrap.'];
      $arr_fields[$int_element . '.']['value_stdWrap' . '.']['value']     = $from;

        // 13920, 110318, dwildt
      $arr_fields[$int_element . '.']['url_stdWrap' . '.']                = $arr_period_conf['url_stdWrap.'];
      $arr_fields[$int_element . '.']['url_stdWrap' . '.']['value']       = $from;
      $url_conf = $arr_fields[$int_element . '.']['url_stdWrap' . '.'];
      $tsKeyUrl = $this->pObj->local_cObj->stdWrap($url_conf['value'], $url_conf);
      $this->arr_url_tsKey[$tableField][$tsKeyUrl] = $int_element;
        // 13920, 110318, dwildt

      $from = strtotime($offset, $from);
      $to   = strtotime($offset, $to);
    }
      // Set fields array



      /////////////////////////////////////////////////////////////////
      //
      // Prepaire TypoScript

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf['views.'][$viewWiDot][$mode . '.'];
      // Prepaire TypoScript



      /////////////////////////////////////////////////////////////////
      //
      // Set TypoScript

    $this->pObj->conf['views.'][$viewWiDot][$mode . '.']['filter.'][$table . '.'][$field . '.']['area.']['interval.']['options.']['fields.'] = $arr_fields;
    $arr_ts = $this->pObj->conf['views.'][$viewWiDot][$mode . '.']['filter.'][$table . '.'][$field . '.'];
      // Set TypoScript

    return $arr_ts;
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_cal.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_cal.php']);
}
?>