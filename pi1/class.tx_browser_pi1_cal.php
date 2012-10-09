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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_cal bundles methods for rendering and processing calender based content, filters and category menues
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 4.1.21
* @since 3.6.0
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   90: class tx_browser_pi1_cal
 *  178:     function __construct($pObj)
 *
 *              SECTION: Calendar
 *  217:     public function cal( $rows, $template )
 *
 *              SECTION: Calendar Data
 *  399:     private function cal_data( )
 *  467:     private function cal_data_day( )
 *  564:     private function cal_data_day_schedule( )
 *  688:     private function cal_data_day_navigator( )
 *
 *              SECTION: Calendar Templating
 *  726:     private function cal_template( )
 *  797:     private function cal_template_head( )
 *  908:     private function cal_template_body( )
 * 1076:     private function cal_template_body_calDate( $dates, $subPrt_calDate )
 *
 *              SECTION: Calendar Helper
 * 1196:     private function cal_colours( )
 * 1246:     private function cal_due_day( )
 * 1348:     private function cal_eval_flexform( )
 * 1420:     private function cal_eval_data( )
 * 1541:     private function cal_frame( )
 * 1680:     private function cal_frame_to_period( $arr_periods )
 * 1836:     private function cal_group_check( )
 * 1930:     private function cal_marker( )
 * 1959:     private function cal_typoscript( )
 *
 *              SECTION: Filter
 * 2068:     function area_init()
 * 2142:     function area_interval($arr_ts, $arr_values, $tableField)
 * 2171:     function area_strings($arr_ts, $arr_values, $tableField)
 *
 *              SECTION: Filter Area Helper
 * 2245:     function area_get_urlPeriod($arr_ts, $tableField, $tsKey)
 * 2316:     function area_get_tsKey_from_urlPeriod($tableField, $str_urlPeriod)
 * 2373:     function area_set_hits($arr_ts, $arr_values, $tableField)
 * 2512:     function area_set_tsPeriod($arr_ts, $tableField)
 *
 *              SECTION: Helper
 * 2782:     public function zz_strtotime( $bool_strtotime, $strtotime )
 * 2860:     public function zz_tableFieldStdWrap( $tableField, $value, $elements, $linkToSingle=true )
 *
 * TOTAL FUNCTIONS: 28
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_cal
{

    // [AREA]
    // [string] day, week, month or year
  var $str_area_case = null;
    // [array] Array with area configuration for every filter, if there is an area configured
  var $arr_area = null;
    // [array] Hits per tablefield (filter) and  item
  var $arr_hits = null;
    // [array] Array with area url and its real tsKey
  var $arr_url_tsKey = null;
    // [String/Integer] default period, if no period is selected by the category menu
  var $selected_period = null;
    // [AREA]

    // [SCHEDULE]
    // [boolean]  True: group is configured. CAL_DATE_GROUP subparts are part of HTML template
  var $bool_group     = false;
    // [array] uid. header, pi_flexform of the plugin pi5
  var $browser_pi5    = null;
    // [array] schedule's current TypoScript configuration
  var $conf_schedule  = null;
    // [array] TypoScript configuration of the current view
  var $conf_view      = null;
    // [array] colours
  var $date_colours   = null;
    // [string] date devider
  var $date_devider   = null;
    // [integer/timestamp] The current due day
  var $due_day        = null;
    // [boolean] True, if there was an error while calculating the due day
  var $due_day_error  = null;
    // [array] Array for the group filter: table.field, value
  var $groupFilter   = null;
    // [boolean] Is the calender plugin loaded?
  var $is_loaded      = false;
    // [array] Array with default markers
  var $markerArray    = null;
    // [string] HTML class for odd columns (th, td)
  var $oddClassColumns  = null;
    // [string] HTML class for odd rows (tr)
  var $oddClassRows   = null;
    // [array] periods: schedule's data, frame with time units containing the rows
  var $periods        = null;
    // [integer] Uid of the current record
  var $record_uid     = null;
    // [array] Current rows;
  var $rows           = null;
    // [integer] Time unit of the current schedule in seconds
  var $schedule_time_unit = null;
    // [integer/timestamp] Absolute beginn of the current schedule
  var $schedule_begin    = null;
    // [integer/timestamp] Absolute end of the current schedule
  var $schedule_end       = null;


    // [integer] Pid of the single view;
  var $singlePid           = null;
    // [string] Current template (HTML with marker)
  var $template       = null;
    // [SCHEDULE]

    //[sheet/extend]
    // Uid in tt_content of the Browser Calender User Interface
  var $sheet_extend_cal_ui            = null;
    // Uid of the view in the TypoScript setup
  var $sheet_extend_cal_view          = null;
    // table.field-name of the date begin field
  var $sheet_extend_cal_field_start   = null;
    // table.field-name of the date end field
  var $sheet_extend_cal_field_end     = null;
    //[sheet/extend]









  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Calendar
  *
  **********************************************/









  /**
 * cal(): Returns a calendar (schedule)
 *        It will executed only:
 *        * in list views
 *        * if the Browser is extended with the Browser Calendar user Interface.
 *
 * @param	array		$rows: Consolidated rows
 * @param	array		$template: Current HTML template
 * @return	array		$arr_return: rows, template, success
 * @version 4.0.0
 * @since 4.0.0
 */
  public function cal( $rows, $template )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set default values

    $this->rows             = $rows;
    $this->template         = $template;
    $arr_return['rows']     = $rows;
    $arr_return['template'] = $template;
    $arr_return['success']  = false;
      // Set default values



      /////////////////////////////////////////////////////////////////
      //
      // Get TypoScript configuration for the current view

    $conf             = $this->pObj->conf;
    $mode             = $this->pObj->piVar_mode;
    $view             = $this->pObj->view;
    $viewWiDot        = $view.'.';
    $this->conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    $this->singlePid  = $this->pObj->objZz->get_singlePid_for_listview( );
      // Get TypoScript configuration for the current view



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN current view isn't the list view

    if( $this->pObj->view != 'list' )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: Current view isn\'t the list view.', $this->pObj->extKey, 0);
      }
    }
      // RETURN current view isn't the list view



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN flexform doesn't contain any data

    if( !$this->cal_eval_flexform( ) )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: Browser isn\'t extended with the Browser Calendar User Interface.', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
      // RETURN flexform doesn't contain any data



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN flexform data aren't valid

    if( !$this->cal_eval_data( ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] RETURN: Browser isn\'t extended with the Browser Calendar User Interface.', $this->pObj->extKey, 2);
      }
      return $arr_return;
    }
      // RETURN flexform data aren't valid



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Upgrade the TypoScript with data of the tx_browser_pi5 plugin

    if( !$this->cal_typoscript( ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] RETURN: Browser isn\'t extended with the Browser Calendar User Interface.', $this->pObj->extKey, 2);
      }
      return $arr_return;
    }
      // Upgrade the TypoScript with data of the tx_browser_pi5 plugin



      /////////////////////////////////////////////////////////////////
      //
      // Upgrade vars

    $conf             = $this->pObj->conf;
    $mode             = $this->pObj->piVar_mode;
    $view             = $this->pObj->view;
    $viewWiDot        = $view.'.';
    $this->conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    $this->singlePid  = $this->pObj->objZz->get_singlePid_for_listview( );
      // Upgrade vars



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Generate the schedule data (periods contains the rows)

    if( !$this->cal_data( ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] RETURN: Browser isn\'t extended with the Browser Calendar User Interface.', $this->pObj->extKey, 2);
      }
      return $arr_return;
    }
      // Generate the schedule data (periods contains the rows)



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Render the template

    $template = $this->cal_template( );
      // Render the template



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN success

      // DRS
    if ($this->pObj->b_drs_cal)
    {
      t3lib_div :: devLog('[OK/CAL/UI] SUCCESS: Browser is extended with the Browser Calendar User Interface.', $this->pObj->extKey, -1);
    }
      // DRS

      // We need one row at least for indexBrowser, pageBrowser, record-browser
    $firstKey               = key( $this->rows );
    $arr_return['rows']     = $this->rows[$firstKey];
    $arr_return['template'] = $template;

    $arr_return['success']  = true;
    $this->is_loaded        = true;
    return $arr_return;
      // RETURN success
  }









  /***********************************************
  *
  * Calendar Data
  *
  **********************************************/









  /**
 * cal_data(): Get periods data (periods which contains the rows)
 *
 * @return	boolean		true in case of success
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_data( )
  {
      // Get TypoScript configuration for the flexform of the plugin pi5
    $conf_flexform_pi5 = $this->conf_view['flexform.']['pi5.'];

    if( empty ( $conf_flexform_pi5 ) )
    {
      $conf_flexform_pi5 = $this->pObj->conf['flexform.']['pi5.'];
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] There was no local flexform.pi5 configuration. The global is taken.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/CAL/UI] Change it? Configure the local array flexform.pi5.', $this->pObj->extKey, 1);
      }
    }

    $sheet            = 'sDEF';
    $field            = 'initialView';
    $cObj_name        = $conf_flexform_pi5[$sheet . '.'][$field . '.']['stdWrap'];
    $cObj_conf        = $conf_flexform_pi5[$sheet . '.'][$field . '.']['stdWrap.'];
    $sDEF_initialView = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    switch($sDEF_initialView)
    {
      case('day'):
          // Get the day's schedule configuration
        $sheet                = 'day';
        $part                 = 'schedule';
        $this->conf_schedule  = $conf_flexform_pi5[$sheet . '.'][$part . '.'];
        $bool_success         = $this->cal_data_day( );
        break;
      case('year'):
      case('month'):
      case('week'):
      default:
          // Do noting. Current initial view isn't supported
        if ($this->pObj->b_drs_error)
        {
          t3lib_div :: devLog('[ERROR/CAL/UI] Initial view: \'' . $sDEF_initialView . '\' won\'t be supported in the current Browser version.', $this->pObj->extKey, 3);
          t3lib_div :: devLog('[HELP/CAL/UI] Initial view: please use \'day\' instead.', $this->pObj->extKey, 1);
        }
        break;
    }

    if( ! $$bool_success )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] no success in ' . __METHOD__ , $this->pObj->extKey, 0);
      }
    }

    return $bool_success;
  }









  /**
 * cal_data_day(): Get periods data for a day (periods which contains the rows)
 *
 * @return	boolean		true in case of success
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_data_day( )
  {
    $sheet                  = 'day';
    $conf_flexform_pi5_day  = $this->pObj->conf['flexform.']['pi5.'][$sheet . '.'];



      /////////////////////////////////////////////////////////////////
      //
      // Schedule

      // Get field day.schedule.display
    $part                 = 'schedule';
    $field                = 'display';
    $cObj_path            = $sheet . '.' . $part . '.' . $field;
    $cObj_name            = $conf_flexform_pi5_day[$part . '.'][$field . '.']['stdWrap'];
    $cObj_conf            = $conf_flexform_pi5_day[$part . '.'][$field . '.']['stdWrap.'];
    $day_schedule_display = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // Get field day.schedule.display

      // Handle field day.schedule.display
    switch($day_schedule_display)
    {
      case(true):
        $bool_success = $this->cal_data_day_schedule( );
        break;
      case(false):
      default:
        if ($this->pObj->b_drs_cal)
        {
          t3lib_div :: devLog('[INFO/CAL/UI] ' . $cObj_path . ' is false', $this->pObj->extKey, 0);
        }
        break;
    }
      // Handle field day.schedule.display
      // Schedule



      /////////////////////////////////////////////////////////////////
      //
      // Navigator

      // Get field day.navigator.display
    $part                   = 'navigator';
    $field                  = 'display';
    $cObj_path              = $sheet . '.' . $part . '.' . $field;
    $cObj_name              = $conf_flexform_pi5_day[$part . '.'][$field . '.']['stdWrap'];
    $cObj_conf              = $conf_flexform_pi5_day[$part . '.'][$field . '.']['stdWrap.'];
    $day_navigator_display  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // Get field day.navigator.display

      // Handle field day.navigator.display
    switch($day_navigator_display)
    {
      case(true):
        //$arr_result   = $this->cal_data_day_navigator( );
        break;
      case(false):
      default:
        if ($this->pObj->b_drs_cal)
        {
          t3lib_div :: devLog('[INFO/CAL/UI] ' . $cObj_path . ' is false', $this->pObj->extKey, 0);
        }
        break;
    }
      // Handle field day.navigator.display
      // Navigator



    if( ! $$bool_success )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] no success in ' . __METHOD__ , $this->pObj->extKey, 0);
      }
    }

    return $bool_success;
  }









  /**
 * cal_data_day_schedule(): Get periods data for a day (periods which contains the rows)
 *
 * @return	boolean		true in case of success
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_data_day_schedule( )
  {
    $sheet          = 'day';
    $part           = 'schedule';
    $conf_schedule  = $this->conf_schedule;



      /////////////////////////////////////////////////////////////////
      //
      // RETURN schedule shouldn't displayed

      // field display
    $field      = 'display';
    $cObj_path  = $sheet . '.' . $part . '.' . $field;
    $cObj_name  = $conf_schedule[$field . '.']['stdWrap'];
    $cObj_conf  = $conf_schedule[$field . '.']['stdWrap.'];
    $display    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    if( strtolower( $display ) != 'yes' )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: schedule shouldn\'t displayed.', $this->pObj->extKey, 0);
      }
      return true;
    }
      // RETURN schedule shouldn't displayed



      // set the due day
    $this->cal_due_day( );

      /////////////////////////////////////////////////////////////////
      //
      // No due day given: set startingpoint as due day

    if( $this->due_day_error )
    {
        // field startingpoint
      $field          = 'startingpoint';
      $cObj_path      = $sheet . '.' . $part . '.' . $field;
      $cObj_name      = $conf_schedule[$field . '.']['stdWrap'];
      $cObj_conf      = $conf_schedule[$field . '.']['stdWrap.'];
      $startingpoint  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

        // field relation or absolute (depends on startingpoint)
      $cObj_path      = $sheet . '.' . $part . '.' . $field . '.' . $startingpoint;
      $cObj_name      = $conf_schedule[$field . '.'][$startingpoint . '.']['stdWrap'];
      $cObj_conf      = $conf_schedule[$field . '.'][$startingpoint . '.']['stdWrap.'];
      $timevalue      = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

      switch($startingpoint)
      {
        case('absolute'):
            // 110824, dwildt-
//            // j: number of month
//          $day        = ( date( 'j' ) + $timevalue ) - date( 'j' );
//          $month      = date( 'm' );
//          $year       = date( 'Y' );
//          $timestamp  = mktime(0, 0, 0, $month, $day, $year);
            // 110824, dwildt+
          $timestamp  = $timevalue;
          $ISO_8601   = date( 'c', $timestamp);
          if ($this->pObj->b_drs_cal)
          {
            t3lib_div :: devLog('[INFO/CAL/UI] startingpoint ' . $startingpoint, $this->pObj->extKey, 0);
            t3lib_div :: devLog('[INFO/CAL/UI] day: \'' . $timevalue . '\': ' . $timestamp . ' (' . $ISO_8601 . ')', $this->pObj->extKey, 0);
          }
          break;
        case('relative'):
          $bool_strtotime = $conf_schedule[$field . '.'][$startingpoint . '.']['use_php_strtotime'];
          $arr_result     = $this->zz_strtotime( $bool_strtotime, $timevalue );
          $timestamp      = $arr_result['result'];
          $ISO_8601       = $arr_result['ISO_8601'];
          if ($this->pObj->b_drs_cal)
          {
            t3lib_div :: devLog('[INFO/CAL/UI] startingpoint ' . $startingpoint, $this->pObj->extKey, 0);
            t3lib_div :: devLog('[INFO/CAL/UI] strtotime(\'' . $timevalue . '\'): ' . $timestamp . ' (' . $ISO_8601 . ')', $this->pObj->extKey, 0);
          }
          break;
      }
      $this->due_day = $timestamp;
    }
      // No due day given: set startingpoint as due day



      /////////////////////////////////////////////////////////////////
      //
      // Get the period with all time units and set it up with rows

    //$arr_frame    = $this->cal_frame( $min_begin, $min_end, $time_unit, $frmt_begin, $frmt_end, $devider );
    $arr_frame    = $this->cal_frame( );
    $bool_success = $this->cal_frame_to_period( $arr_frame );
      // Get the period with all time units and set it up with rows

    if( ! $$bool_success )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] no success in ' . __METHOD__ , $this->pObj->extKey, 0);
      }
    }

    return $bool_success;
  }









  /**
 * cal_data_day_navigator():  day's navigator
 *                            * ISN'T DEVELOPED *
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_data_day_navigator( )
  {
    if ($this->pObj->b_drs_warn)
    {
      t3lib_div :: devLog('[ERROR/CAL/UI] day\'s navigator isn\'t supported in this version.', $this->pObj->extKey, 2);
    }
  }










  /***********************************************
  *
  * Calendar Templating
  *
  **********************************************/









  /**
 * cal_template(): Returns the HTML template
 *
 * @return	string		$template   The template
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_template( )
  {
    $this->cal_marker( );

      /////////////////////////////////////////////////////////////////
      //
      // Get fields and set marker

      // Get fields
    $field      = 'caption';
    $cObj_name  = $this->conf_schedule['labels.'][$field . '.']['stdWrap'];
    $cObj_conf  = $this->conf_schedule['labels.'][$field . '.']['stdWrap.'];
    $caption    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    $field      = 'summary';
    $cObj_name  = $this->conf_schedule['labels.'][$field . '.']['stdWrap'];
    $cObj_conf  = $this->conf_schedule['labels.'][$field . '.']['stdWrap.'];
    $summary    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    $this->oddClassColumns  = $this->conf_schedule['time_unit.']['oddClass.']['columns'];
    $this->oddClassRows     = $this->conf_schedule['time_unit.']['oddClass.']['rows'];
      // Get fields

      // Set marker
    $this->markerArray['###CAPTION###'] = $caption;
    $this->markerArray['###SUMMARY###'] = $summary;
      // Set marker
      // Get fields and set marker



      // Initial group
    $this->cal_group_check( );
      // Handle the head section
    $this->cal_template_head( );
      // Handle the body section
    $this->cal_template_body( );

    $template         = $this->template;
    $subPrt_listView  = $this->pObj->cObj->getSubpart($template, '###LISTVIEW###');
    $subPrt_listView  = $this->pObj->cObj->substituteMarkerArray($subPrt_listView, $this->markerArray);
    $subPrt_listView  = '<!-- ###LISTVIEW### begin -->' . $subPrt_listView . '<!-- ###LISTVIEW### end -->';
    $template         = $this->pObj->cObj->substituteSubpart($template, '###LISTVIEW###', $subPrt_listView, true);

    $arr_return['template'] = $template;
    $arr_return['success']  = true;

    return $template;
      // Return the row and the template
  }








  /**
 * cal_template_head(): Set up the template. Here the subpart LISTHEAD.
 *                      This markers will replaced:
 *                      * cal_date
 *                      * cal_period
 *                      * caption
 *                      * summary
 *                      Result (HTML snippet) will written to the global $template
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_template_head( )
  {



      /////////////////////////////////////////////////////////////////
      //
      // Get fields

    $field      = 'cal_date';
    $cObj_name  = $this->conf_schedule['labels.'][$field . '.']['stdWrap'];
    $cObj_conf  = $this->conf_schedule['labels.'][$field . '.']['stdWrap.'];
    $cal_date   = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    $field      = 'cal_period';
    $cObj_name  = $this->conf_schedule['labels.'][$field . '.']['stdWrap'];
    $cObj_conf  = $this->conf_schedule['labels.'][$field . '.']['stdWrap.'];
    if( empty( $cObj_conf['value'] ) )
    {
      $cObj_conf['value'] = $this->due_day;
    }
    $cal_period = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // Get fields



      /////////////////////////////////////////////////////////////////
      //
      // Set marker

    $markerArray['###CAL_DATE###']    = $cal_date;
    $markerArray['###CAL_PERIOD###']  = $cal_period;
    $this->markerArray = (array) $this->markerArray + (array) $markerArray;
    // 110827, dwildt
    //$this->markerArray = (array) $this->markerArray + (array) $this->pObj->objWrapper->constant_markers();
      // Set marker



      /////////////////////////////////////////////////////////////////
      //
      // Substitute marker in the template

    $template = $this->template;
    $listHead = $this->pObj->cObj->getSubpart($template, '###LISTHEAD###');
    if( $this->bool_group )
    {
      $arr_conf_groups  = $this->conf_schedule['group.'];
      $caldategroup     = $this->pObj->cObj->getSubpart($listHead, '###CAL_DATE_GROUP###');

      $counter_th = 0 + 1;
      $max_th     = count( $arr_conf_groups ) - 1 + 1;

      foreach( $arr_conf_groups as $key_group => $arr_group)
      {
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
        $field      = 'label';
        $cObj_name  = $this->conf_schedule['group.'][$key_group][$field . '.']['stdWrap'];
        $cObj_conf  = $this->conf_schedule['group.'][$key_group][$field . '.']['stdWrap.'];
        $cal_date   = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
        $markerArray['###CAL_DATE###']  = $cal_date;
        $markerArray['###CAL_GROUP###'] = rtrim( $key_group, '.' );
        $arr_caldategroup[$key_group]   = trim( $caldategroup );
        $arr_caldategroup[$key_group]   = $this->pObj->cObj->substituteMarkerArray($arr_caldategroup[$key_group], $markerArray);
        $counter_th++;
      }
      $str_caldategroup = implode( null, $arr_caldategroup );
      $listHead         = $this->pObj->cObj->substituteSubpart($listHead, '###CAL_DATE_GROUP###', $str_caldategroup, true);
    }
    $counter_th = 0;
    $markerArray['###TH_FIRST_LAST###']   = 'first';
    $markerArray['###TH_EVEN_OR_ODD###']  = 0%2 ? $this->oddClassColumns : null;
    $listHead = $this->pObj->cObj->substituteMarkerArray($listHead, $markerArray);
    $template = $this->pObj->cObj->substituteSubpart($template, '###LISTHEAD###', $listHead, true);
      // Substitute marker in the template



    $this->template = $template;
  }









  /**
 * cal_template_body(): Set up the template. Here: LISTBODYITEM.
 *                      Loop with periods. Allocates finished periods including the dates to LISTBODYITEM.
 *                      Result (HTML snippet) will written to the global $template
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_template_body( )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set vars

    $template         = $this->template;
      // Set default markert array
    $markerArray      = $this->markerArray;
      // Get subparts for a period item (including dates)
    $subPrt_period    = $this->pObj->cObj->getSubpart($template,          '###LISTBODYITEM###');
    $subPrt_dateGroup = $this->pObj->cObj->getSubpart($subPrt_period,     '###CAL_DATE_GROUP###');
    $subPrt_date      = $this->pObj->cObj->getSubpart($subPrt_dateGroup,  '###CAL_DATE###');
      // Set vars



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set global date_devider

    $cObj_name    = $this->conf_schedule['labels.']['date.']['devider.']['stdWrap'];
    $cObj_conf    = $this->conf_schedule['labels.']['date.']['devider.']['stdWrap.'];
    $date_devider = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $this->date_devider = $date_devider;
      // Set global date_devider



      ///////////////////////////////////////////////////////////////////////////////
      //
      // LOOP periods

    $counter_tr = 0;
    $max_tr     = count( $this->periods ) - 1;
    foreach( $this->periods as $key_data_period => $arr_data_period )
    {
        // Period label
      $markerArray['###CAL_PERIOD###']  = $arr_data_period['label'];
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

        // No group
      if( ! $this->bool_group )
      {
        $markerArray['###TD_COUNTER###']      = 1;
        $markerArray['###TD_FIRST_LAST###']   = 'last';
        $markerArray['###TD_EVEN_OR_ODD###']  = 0%2 ? $this->oddClassColumns : null;
          // Get the dates for the current period
        $str_dates                      = $this->cal_template_body_calDate( $arr_data_period['rows'], $subPrt_date );
          // Set the template subpart
        $arr_periods[$key_data_period]  = $subPrt_period;
          // Subsitute CAL_DATE with dates
        $arr_periods[$key_data_period]  = $this->pObj->cObj->substituteSubpart( $arr_periods[$key_data_period], '###CAL_DATE###', $str_dates, true );
          // Replace markers
        $arr_periods[$key_data_period]  = $this->pObj->cObj->substituteMarkerArray( $arr_periods[$key_data_period], $markerArray );
      }
        // No group

        // Group
      if( $this->bool_group)
      {
        $arr_conf_groups  = $this->conf_schedule['group.'];

        $counter_td = 0 + 1;
        $max_td     = count( $arr_conf_groups ) - 1 + 1;

        foreach( $arr_conf_groups as $key_conf_group => $arr_conf_group)
        {
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

          $this->groupFilter['tableField']      = $this->conf_schedule['group.'][$key_conf_group]['tableField'];
          $this->groupFilter['value']           = $this->conf_schedule['group.'][$key_conf_group]['value'];
          $this->groupFilter['cal_group']       = rtrim( $key_conf_group, '.' );
          $this->markerArray['###CAL_GROUP###'] = rtrim( $key_conf_group, '.' );
          $markerArray['###CAL_GROUP###']       = rtrim( $key_conf_group, '.' );


            // Get the dates for the current period
          $str_dates              = $this->cal_template_body_calDate( $arr_data_period['rows'], $subPrt_date );
            // Set the template subpart
          $arr_group[$key_conf_group]  = $subPrt_dateGroup;
            // Subsitute CAL_DATE with dates
          $arr_group[$key_conf_group]  = $this->pObj->cObj->substituteSubpart( $arr_group[$key_conf_group], '###CAL_DATE###', $str_dates, true );
            // Replace markers
          $arr_group[$key_conf_group]  = $this->pObj->cObj->substituteMarkerArray( $arr_group[$key_conf_group], $markerArray );
          $counter_td++;
        }
        $counter_td = 0;
        $markerArray['###TD_COUNTER###']      = 0;
        $markerArray['###TD_FIRST_LAST###']   = 'first';
        $markerArray['###TD_EVEN_OR_ODD###']  = 0%2 ? $this->oddClassColumns : null;
          // Implode group items
        $str_groups                     = implode( null, $arr_group );
          // Set the template subpart
        $arr_periods[$key_data_period]  = $subPrt_period;
          // Subsitute CAL_DATE_GROUP with groups
        $arr_periods[$key_data_period]  = $this->pObj->cObj->substituteSubpart( $arr_periods[$key_data_period], '###CAL_DATE_GROUP###', $str_groups, true );
          // Replace markers
        $arr_periods[$key_data_period]  = $this->pObj->cObj->substituteMarkerArray( $arr_periods[$key_data_period], $markerArray );
      }
        // Group
      $counter_tr++;
    }
      // LOOP periods



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set up the template

      // Implode periods
    $str_periods    = implode( null, $arr_periods );
      // Subsitute BODYITEM with all periods
    $template       = $this->pObj->cObj->substituteSubpart( $template, '###LISTBODYITEM###', $str_periods, true );
      // Set the template
    $this->template = $template;
      // Set up the template
  }









  /**
 * cal_template_body_calDate(): Set up the template. Here: CAL_DATE.
 *                              Loop with dates (children of periods). Allocates finished dates to CAL_DATE.
 *
 * @param	array		$dates:           Array with the dates. This are the $rows from the sql result, consolidated for the calendar.
 * @param	string		$subPrt_calDate:  The CAL_DATE subpart
 * @return	string		$str_calDate   HTML snippet
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_template_body_calDate( $dates, $subPrt_calDate )
  {
    static $bool_prompted = false;

    $markerArray = $this->markerArray;



      ///////////////////////////////////////////////////////////////////////////////
      //
      // LOOP dates

    foreach( $dates as $row => $elements )
    {
        // Set record uid
      $tableFieldUid                  = $this->pObj->arrLocalTable['uid'];
      $this->record_uid               = $elements[$tableFieldUid];
      $markerArray['###UID###']       = $this->record_uid;
        // Set record uid

        // Filter rows by group
      if( $this->bool_group)
      {
          // CONTINUE row isn't part of the current group
        if ($elements[$this->groupFilter['tableField']] != $this->groupFilter['value'])
        {
          if( ! $bool_prompted )
          {
            if ( ! array_key_exists( $this->groupFilter['tableField'], $elements ) )
            {
              $prompt_err   = '[ERROR/CAL/UI] \'' . $this->groupFilter['tableField'] . '\' isn\'t a key in $elements.';
              $prompt_info  = '[INFO/CAL/UI] You have configured a group. But the tableField isn\'t part of the SQL query. You will never get a result!';
              $prompt_help  = '[HELP/CAL/UI] Fix it? Configure a proper group.';
              if ($this->pObj->b_drs_error)
              {
                t3lib_div :: devLog($prompt_err,  $this->pObj->extKey, 3);
                t3lib_div :: devLog($prompt_info, $this->pObj->extKey, 0);
                t3lib_div :: devLog($prompt_help, $this->pObj->extKey, 1);
              }
              $value = '<div style="background:white; border:.4em solid red; color:red; padding:1em;text-align:center;">
                          ' . $prompt_err . '<br />
                          ' . $prompt_info . '<br />
                          ' . $prompt_help . '<br />
                        </div>';
              $bool_prompted = true;
              return $value;
            }
          }
          continue;
        }
          // CONTINUE row isn't part of the current group
      }
        // Filter rows by group

      $bool_value = false;
      foreach( $elements as $tableField => $value )
      {
        $value = $this->zz_tableFieldStdWrap( $tableField, $value, $elements, $linkToSingle=true );
        $markerArray['###' . strtoupper( $tableField ) . '###'] = $value;
//if( $tableField == 'cal_colour' )
//{
//  var_dump( __METHOD__, __LINE__, '###' . strtoupper( $tableField ) . '###' . ': ' . $markerArray['###' . strtoupper( $tableField ) . '###']);
//}
        if( ! empty( $value ) || $value === 0 )
        {
          $bool_value = true;
        }
      }
      $arr_calDate[$row] = trim( $subPrt_calDate );
//var_dump( __METHOD__, __LINE__, $arr_calDate[$row], $markerArray);
      $arr_calDate[$row] = $this->pObj->cObj->substituteMarkerArray($arr_calDate[$row], $markerArray);
//var_dump( __METHOD__, __LINE__, $arr_calDate[$row]);
//var_dump( __METHOD__, __LINE__, '###' . strtoupper( 'cal_colour' ) . '###' . ': ' . $markerArray['###' . strtoupper( 'cal_colour' ) . '###']);
      if( ! $bool_value )
      {
        unset( $arr_calDate[$row] );
      }
    }
      // LOOP dates



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Set up the template subpart and return it

      // Implode the dates
    $str_calDate = implode( $this->date_devider, $arr_calDate );
    return $str_calDate;
      // Set up the template subpart and return it
  }









  /***********************************************
  *
  * Calendar Helper
  *
  **********************************************/








  /**
 * cal_colours(): Initial the corlor array
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_colours( )
  {
    $sheet        = 'sDEF';
    $field        = 'colours';

    $conf_flexform_pi5 = $this->conf_view['flexform.']['pi5.'];
    if( empty ( $conf_flexform_pi5 ) )
    {
      $conf_flexform_pi5 = $this->pObj->conf['flexform.']['pi5.'];
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] There was no local flexform.pi5 configuration. The global is taken.', $this->pObj->extKey, 0);
        t3lib_div :: devLog('[HELP/CAL/UI] Change it? Configure the local array flexform.pi5.', $this->pObj->extKey, 1);
      }
    }

    $conf_colours = $conf_flexform_pi5[$sheet . '.'][$field . '.'];

    foreach( $conf_colours as $key_colour => $value_colour)
    {
        // Take keys with a dot (i.e. 10.) only
      if( $key_colour === (int) rtrim( $key_colour, '.' ) )
      {
        $cObj_name  = $conf_colours[$key_colour];
        $cObj_conf  = $conf_colours[$key_colour . '.'];
        $this->date_colours[] = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      }
    }
  }









  /**
 * cal_due_day(): Set the due day (current day of the calendar request)
 *                Will set the global due_day as a timestamp
 *                Try to fetch due day from the URL.
 *                If there isn't any due_day, it will set it to 'today 0:00'
 *                The global due_day_error will set to true: Other methos will change
 *                the due_day to there needs.
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_due_day( )
  {
      /////////////////////////////////////////////////////////////////
      //
      // Get due day from piVars (from the current URL)

      // Try to catch it from the start field
    $cal_field_start  = $this->pObj->objFlexform->sheet_extend_cal_field_start;
    $piVar_due_day    = $this->pObj->piVars[$cal_field_start];
      // Try to catch it from the start field

      // No start field: try to catch it from the end field
    if( empty ( $piVar_due_day ) )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] piVar[' . $cal_field_start . '] isn\'t set.', $this->pObj->extKey, 0);
      }
      $cal_field_end  = $this->pObj->objFlexform->sheet_extend_cal_field_end;
      $piVar_due_day  = $this->pObj->piVars[$cal_field_end];
    }
      // No start field: try to catch it from the end field
      // Get due day from piVars (from the current URL)



      /////////////////////////////////////////////////////////////////
      //
      // No detectable due day: set due day to 'today 0:00''

    if( empty ( $piVar_due_day ) )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] piVar[' . $cal_field_end . '] isn\'t set.', $this->pObj->extKey, 0);
      }
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] piVar[' . $cal_field_start . '] and piVar[' . $cal_field_end . '] arn\'t set.', $this->pObj->extKey, 2);
        t3lib_div :: devLog('[WARN/CAL/UI] due day will become timestamp of today 0:00.', $this->pObj->extKey, 2);
      }
    }
      // No detectable due day: set due day to 'today 0:00''



      /////////////////////////////////////////////////////////////////
      //
      // Move due day to a timestamp

    $arr_result = $this->zz_strtotime( true, $piVar_due_day );
    $timestamp  = $arr_result['result'];
    $ISO_8601   = $arr_result['ISO_8601'];
    $bool_error = $arr_result['error'];
    if( $bool_error )
    {
      $this->due_day_error = true;
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] Can\'t find out the due day. It is set to \'today 0:00\'.', $this->pObj->extKey, 2);
        t3lib_div :: devLog('[HELP/CAL/UI] Reason: value ' . $piVar_due_day . ' can\'t move to a timestamp.', $this->pObj->extKey, 1);
      }
    }
      // Move due day to a timestamp



      /////////////////////////////////////////////////////////////////
      //
      // Set the global due day

      // Set to 0:00 hour
    $year       = date('Y', $timestamp);
    $month      = date('m', $timestamp);
    $day        = date('d', $timestamp);
    $timestamp  = mktime(0, 0, 0, $month , $day, $year);
    $ISO_8601   = date('c', $timestamp);

    if ($this->pObj->b_drs_cal)
    {
      t3lib_div :: devLog('[INFO/CAL/UI] due day is ' . $timestamp . ' (' . $ISO_8601 . ')', $this->pObj->extKey, 0);
    }
    $this->due_day = $timestamp;
      // Set the global due day
  }









  /**
 * cal_eval_flexform(): Checks, if the flexform sheet 'extend' contains any data.
 *                      Set some global vars. See code at the bottom.
 *
 * @return	boolean		Returns false in case of no data, true in case of data
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_eval_flexform( )
  {
      // RETURN field cal_ui is false
    if( !$this->pObj->objFlexform->sheet_extend_cal_ui )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: flexform extend.cal_ui doesn\'t contain any data.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field cal_ui is false

      // RETURN field cal_view is false
    if( !$this->pObj->objFlexform->sheet_extend_cal_view )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: flexform extend.cal_view doesn\'t contain any data.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field cal_view is false

      // RETURN field cal_field_start is false
    if( !$this->pObj->objFlexform->sheet_extend_cal_field_start )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: flexform extend.cal_field_start doesn\'t contain any data.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field cal_field_start is false

      // RETURN field cal_field_end is false
    if( !$this->pObj->objFlexform->sheet_extend_cal_field_end )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: flexform extend.cal_field_end doesn\'t contain any data.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field cal_field_end is false

      // Set fields
    $this->sheet_extend_cal_ui          = $this->pObj->objFlexform->sheet_extend_cal_ui;
    $this->sheet_extend_cal_view        = $this->pObj->objFlexform->sheet_extend_cal_view;
    $this->sheet_extend_cal_field_start = $this->pObj->objFlexform->sheet_extend_cal_field_start;
    $this->sheet_extend_cal_field_end   = $this->pObj->objFlexform->sheet_extend_cal_field_end;
      // Set fields

      // RETURN all flexform fields have a value
    return true;
  }









  /**
 * cal_eval_data():  Checks, if the data of the flexform sheet 'extend' are valid.
 *
 * @return	boolean		Returns false in case of invalid, true in case of valid
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_eval_data( )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN plugin doesn't exist, isn't part of the current page, is marked as hidden or deleted

      // SQL query: Get all browser_pi5 plugins of the current page.
    $uid            = (int) $this->sheet_extend_cal_ui;
    $pid            = (int) $this->pObj->cObj->data['pid'];
    $select_fields  = 'uid, header, pi_flexform';
    $from_table     = 'tt_content';
    $where_clause   = "pid = " . $pid . " AND uid = " . $uid . " AND hidden = 0 AND deleted = 0";
    $query          = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='');
      // SQL query: Get all browser_pi5 plugins of the current page.

      // SQL query: Execute it. Allocate items with values from the SQL result
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy='',$limit='');

      // LOOP count rows of the SQL result
    $int_rows_counter = 0;
    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
    {
      $this->browser_pi5 = $row;
      $int_rows_counter++;
    }
    //var_dump(__METHOD__, __LINE__, $row, $this->browser_pi5);
      // LOOP count rows of the SQL result

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result($res);

    if( $int_rows_counter != 1 )
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] query: ' . $query, $this->pObj->extKey, 0);
        t3lib_div :: devLog('[ERROR/CAL/UI] RETURN: Browser Calendar plugin doesn\'t exist, isn\'t part of the current page, is marked as hidden or deleted.', $this->pObj->extKey, 3);
        t3lib_div :: devLog('[INFO/CAL/UI] Reason can be: plugin contains data out of date.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN plugin doesn't exist, isn't part of the current page, is marked as hidden or deleted



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN view isn't part of the current TypoScript

    $conf     = $this->pObj->conf;
    $view     = $this->sheet_extend_cal_view;
    $arr_view = $conf['views.']['list.'][$view . '.'];
    if( !is_array ( $arr_view ) )
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div :: devLog('[ERROR/CAL/UI] RETURN: view ' . $view . ' isn\'t part of the current TypoScript.', $this->pObj->extKey, 3);
        t3lib_div :: devLog('[INFO/CAL/UI] Reason can be: plugin contains data out of date.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN view isn't part of the current TypoScript



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN field_start isn't part of the current TypoScript

    $field  = $this->sheet_extend_cal_field_start;
    $select = $conf['views.']['list.'][$view . '.']['select'];
    if( strpos($select, $field) === false )
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div :: devLog('[ERROR/CAL/UI] RETURN: ' . $field . ' isn\'t part of views.list.' . $view . '.select', $this->pObj->extKey, 3);
        t3lib_div :: devLog('[INFO/CAL/UI] Reason can be: plugin contains data out of date.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field_start isn't part of the current TypoScript



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN field_end isn't part of the current TypoScript

    $field  = $this->sheet_extend_cal_field_end;
    $select = $conf['views.']['list.'][$view . '.']['select'];
    if( strpos($select, $field) === false )
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div :: devLog('[ERROR/CAL/UI] RETURN: ' . $field . ' isn\'t part of views.list.' . $view . '.select', $this->pObj->extKey, 3);
        t3lib_div :: devLog('[INFO/CAL/UI] Reason can be: plugin contains data out of date.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // RETURN field_end isn't part of the current TypoScript



    return true;
  }









  /**
 * cal_frame(): Building the schedules data frame (the list of all proper time units)
 *
 * @return	array		$arr_period:    Period (list of time units)
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_frame( )
  {
    $sheet          = 'day';
    $part           = 'schedule';
    $conf_schedule  = $this->conf_schedule;

    $arr_frame = null;

      /////////////////////////////////////////////////////////////////
      //
      // Get begin, end, time_unit, format_begin, format_end, devider

      // field begin
    $field      = 'begin';
    $cObj_path  = $sheet . '.' . $part . '.' . $field;
    $cObj_name  = $conf_schedule[$field . '.']['stdWrap'];
    $cObj_conf  = $conf_schedule[$field . '.']['stdWrap.'];
    $begin      = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $frmt_begin = $conf_schedule[$field . '.']['strftime'];
      // field begin

      // field end
    $field      = 'end';
    $cObj_path  = $sheet . '.' . $part . '.' . $field;
    $cObj_name  = $conf_schedule[$field . '.']['stdWrap'];
    $cObj_conf  = $conf_schedule[$field . '.']['stdWrap.'];
    $end        = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $frmt_end   = $conf_schedule[$field . '.']['strftime'];
      // field end

      // field time_unit
    $field      = 'time_unit';
    $cObj_path  = $sheet . '.' . $part . '.' . $field;
    $cObj_name  = $conf_schedule[$field . '.']['stdWrap'];
    $cObj_conf  = $conf_schedule[$field . '.']['stdWrap.'];
    $time_unit  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // field time_unit

      // field devider
    $field      = 'devider';
    $cObj_path  = $sheet . '.' . $part . '.' . $field;
    $cObj_name  = $conf_schedule[$field . '.']['stdWrap'];
    $cObj_conf  = $conf_schedule[$field . '.']['stdWrap.'];
    $devider    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // field devider

      // Turn hours to minutes
    $min_begin  = $begin  * 60;
    $min_end    = $end    * 60;
      // Turn hours to min
      // Get begin, end, time_unit, format_begin, format_end, devider



      // Set default values
    if( !$frmt_begin )
    {
      $frmt_begin = 'H:i';
    }
    if( !$frmt_end )
    {
      $frmt_end = 'H:i';
    }
    if( !$devider )
    {
      $devider = ' - ';
    }
      // Set default values

      // Begin is greater than end: swap them
    if ( $min_begin > $min_end)
    {
      $tmp        = $min_begin;
      $min_begin  = $min_end;
      $min_end    = $min_begin;
    }
      // Begin is greater than end: swap them

      // Turn to seconds
    $sec_begin                = $min_begin      * 60;
    $sec_end                  = $min_end        * 60;
    $sec_time_unit            = $time_unit      * 60;
    $this->schedule_time_unit = $sec_time_unit;
      // Turn to seconds

      // Turn to absolute timestamp
    $sec_begin_abs            = $sec_begin  + $this->due_day;
    $this->schedule_begin     = $sec_begin_abs;
    $sec_end_abs              = $sec_end    + $this->due_day;
    $this->schedule_end       = $sec_end_abs;
      // Turn to absolute timestamp

//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if ( ! ( $pos === false ) )
//{
//  var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->schedule_begin);
//}

      // LOOP time_units
    //var_dump(__METHOD__. ' (' . __LINE__ . '): ' . $sec_begin_abs . ' - ' . $sec_end_abs . ' - ' . $sec_time_unit );
    for
    (
          $sec_time_unit_curr = $sec_begin_abs;
          $sec_time_unit_curr < $sec_end_abs;
          $sec_time_unit_curr = $sec_time_unit_curr + $sec_time_unit
    )
    {
      $sec_time_unit_begin  = $sec_time_unit_curr;
      $sec_time_unit_end    = $sec_time_unit_curr + $sec_time_unit;
      $str_unit_begin       = strftime( $frmt_begin,  $sec_time_unit_begin );
      $str_unit_end         = strftime( $frmt_end,    $sec_time_unit_end   );
      $str_label            = $str_unit_begin . $devider . $str_unit_end;
      $arr_frame[$sec_time_unit_begin]['timestamp']['begin']  = $sec_time_unit_begin;
      $arr_frame[$sec_time_unit_begin]['timestamp']['end']    = $sec_time_unit_end;
      $arr_frame[$sec_time_unit_begin]['label']               = $str_label;
      $arr_frame[$sec_time_unit_begin]['ISO_8601']            = date('c', $sec_time_unit_begin);
      //var_dump(__METHOD__. ' (' . __LINE__ . '): ' . $str_unit_begin . ' - ' . $str_unit_end);
    }
      // LOOP time_units

    return $arr_frame;
  }









  /**
 * cal_frame_to_period(): Set the global periods: add the rows to the frame (the list of all proper time units)
 *
 * @param	array		$arr_frame:   Frame, the list of time units
 * @return	boolean		true
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_frame_to_period( $arr_periods )
  {
    $rows   = $this->rows;

      // Names of start and end field
    $begin  = $this->pObj->objFlexform->sheet_extend_cal_field_start;
    $end    = $this->pObj->objFlexform->sheet_extend_cal_field_end;


      // RETRUN rows are empty
    if( empty ( $rows ) )
    {
      //var_dump(__METHOD__, __LINE__, $rows);
      $this->periods = $arr_periods;
      return true;
    }
      // RETRUN rows are empty




      // Default row with emty values
    $first_key = key( $rows );
    $first_row = $rows[$first_key];
    foreach ( $first_row as $key => $value)
    {
      $empty_row[$key] = null;
    }
      // Default row with emty values



      // Set the colours array
    $this->cal_colours( );
    $colour_counter = 0;
    $max_colours    = count( $this->date_colours );
//    foreach( $rows as $key_rows => $elements )
//    {
//      if( $colour_counter >= $max_colours)
//      {
//        $colour_counter = 0;
//      }
//      $rows[$key_rows]['cal_colour'] = $this->date_colours[$colour_counter];
//      $colour_counter++;
//    }
//var_dump( __METHOD__, __LINE__, $rows);
      // Extend rows with colours



      ///////////////////////////////////////////////////////////////////////////////
      //
      // LOOP period items

    foreach( $arr_periods as $key_period => $period )
    {
      $bool_period_is_empty = true;

        // LOOP rows
      foreach( $rows as $key_rows => $elements )
      {
          // WARNING element is empty!
        if( empty ( $elements[$begin] ) )
        {
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div :: devLog('[WARN/CAL/UI] row[' . $begin . '] is empty.', $this->pObj->extKey, 2);
          }
        }
        if( empty ( $elements[$end] ) )
        {
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div :: devLog('[WARN/CAL/UI] row[' . $end . '] is empty.', $this->pObj->extKey, 2);
          }
        }
          // WARNING element is empty!

          // Does the item match the period?
        $bool_match = true;
        if( ! ( $elements[$begin] < $period['timestamp']['end'] ) )
        {
          //var_dump(__METHOD__ . ' (' . __LINE__ . '): ' . 'Doesn\'t match - begin: ' . date( 'd.m.Y H:i', $elements[$begin]) . ' < period end: ' . date( 'd.m.Y H:i', $period['timestamp']['end']));
          $bool_match = false;
        }
        if( ! ( $elements[$end] > $period['timestamp']['begin'] ) )
        {
          //var_dump(__METHOD__ . ' (' . __LINE__ . '): ' . 'Doesn\'t match - end: ' . date( 'd.m.Y H:i', $elements[$end]) . ' > period end: ' . date( 'd.m.Y H:i', $period['timestamp']['begin']));
          $bool_match = false;
        }
          // Does the item match the period?

          // It doesn't
        if( ! $bool_match )
        {
          //var_dump(__METHOD__ . ' (' . __LINE__ . '): ' . 'Doesn\'t match - begin: ' . date( 'd.m.Y H:i', $elements[$begin]) . ', end: ' . date( 'd.m.Y H:i', $elements[$end]));
          continue;
        }
        //var_dump(__METHOD__ . ' (' . __LINE__ . '): ' . 'Does match - begin: ' . date( 'd.m.Y H:i', $elements[$begin]) . ', end: ' . date( 'd.m.Y H:i', $elements[$end]));
          // It doesn't

          // It does
          // Extend row with colour
        if( empty ( $rows[$key_rows]['cal_colour'] ) )
        {
          if( $colour_counter >= $max_colours)
          {
            $colour_counter = 0;
          }
          $rows[$key_rows]['cal_colour'] = $this->date_colours[$colour_counter];
          $colour_counter++;
        }
          // Extend row with colour
        $arr_periods[$key_period]['rows'][$key_rows]          = $rows[$key_rows];
          // Extend the rows ...
        list($table, $field)  = explode('.', $begin);
        $cal_date_start     = $this->zz_tableFieldStdWrap( $begin, $elements[$begin], $elements, $linkToSingle=false );
        $arr_periods[$key_period]['rows'][$key_rows]['cal_date_start'] = $cal_date_start;
        list($table, $field)  = explode('.', $end);
        $cal_date_end       = $this->zz_tableFieldStdWrap( $end, $elements[$end], $elements, $linkToSingle=false );
        $arr_periods[$key_period]['rows'][$key_rows]['cal_date_end'] = $cal_date_end;

        $bool_period_is_empty = false;
          // It does
      }
        // LOOP rows

        // Set default row
      if( $bool_period_is_empty )
      {
        $arr_periods[$key_period]['rows'][-1] = $empty_row;
      }
        // Set default row
    }
      // LOOP period items

    $this->periods = $arr_periods;
    return true;
  }









  /**
 * cal_group_check(): Check, if TypoScript and the HTML template is configured for grouping.
 *                    If yes, the global $bool_group becomes true.
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_group_check( )
  {
      /////////////////////////////////////////////////////////////////
      //
      // RETURN there is no group configured

    $arr_conf_group  = $this->conf_schedule['group.'];
    if( empty ( $arr_conf_group ) )
    {
      if ($this->pObj->b_drs_cal)
      {
        t3lib_div :: devLog('[INFO/CAL/UI] RETURN: No group isn\'t configured.', $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN there is no group configured



      /////////////////////////////////////////////////////////////////
      //
      // HTML template

    $template   = $this->template;
      // HTML template



      /////////////////////////////////////////////////////////////////
      //
      // RETURN there is no subpart CAL_DATE_GROUP in the LISTHEAD section

    $listHead   = $this->pObj->cObj->getSubpart($template, '###LISTHEAD###');
    $listGroup  = $this->pObj->cObj->getSubpart($listHead, '###CAL_DATE_GROUP###');
    if( empty( $listGroup) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] RETURN: Group is configured, but template doesn\'t contain ' .
          'a subpart CAL_DATE_GROUP inside the subpart LISTHEAD!', $this->pObj->extKey, 2);
      }
      return;
    }
      // RETURN there is no subpart CAL_DATE_GROUP in the LISTHEAD section



      /////////////////////////////////////////////////////////////////
      //
      // RETURN there is no subpart CAL_DATE_GROUP in the LISTBODY section

    $listBody   = $this->pObj->cObj->getSubpart($template, '###LISTBODY###');
    $listGroup  = $this->pObj->cObj->getSubpart($listBody, '###CAL_DATE_GROUP###');
    if( empty( $listGroup) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div :: devLog('[WARN/CAL/UI] RETURN: Group is configured, but template doesn\'t contain ' .
          'a subpart CAL_DATE_GROUP inside the subpart LISTBODY!', $this->pObj->extKey, 2);
      }
      return;
    }
      // RETURN there is no subpart CAL_DATE_GROUP in the LISTBODY section



      /////////////////////////////////////////////////////////////////
      //
      // SUCCESS group is configured, CAL_DATE_GROUP subparts are part of the HTML template

    if ($this->pObj->b_drs_cal)
    {
      t3lib_div :: devLog('[OK/CAL/UI] Group is configured. HTML template contains subparts CAL_DATE_GROUP.',
        $this->pObj->extKey, -1);
    }
    $this->bool_group = true;
      // SUCCESS group is configured, CAL_DATE_GROUP subparts are part of the HTML template
  }









  /**
 * cal_marker(): Set some global marker
 *
 * @return	void
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_marker( )
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
 * cal_typoscript(): Set the TypoScript depending on the flexform data
 *
 * @return	boolean		Returns false in case of an error
 * @version 4.0.0
 * @since 4.0.0
 */
  private function cal_typoscript( )
  {
      // Get the flexform value (XML format)
    $xml_pi_flexform = $this->browser_pi5['pi_flexform'];



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN pi_flexform of plugin pi5 is empty

    if( empty ( $xml_pi_flexform ) )
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div :: devLog('[ERROR/CAL/UI] Unexpected error: pi_flexform of pi5 is empty.', $this->pObj->extKey, 3);
        t3lib_div :: devLog('[HELP/CAL/UI] This can help: save the Calendar plugin again.', $this->pObj->extKey, 1);
      }
      return false;
    }
      // RETURN pi_flexform of plugin pi5 is empty


      // Get the TypoScript configuration as a one dimensional array
    $conf_oneDim      = t3lib_BEfunc::implodeTSParams($this->pObj->conf);
      // Only for development
    //$conf_pi5_before  = $this->pObj->conf['flexform.']['pi5.'];

      // Move flexform values from XML to an php array
    $arr_pi_flexform = t3lib_div::xml2array( $xml_pi_flexform, $NSprefix ='' , $reportDocTag = false );

    $modeWiDot  = $this->pObj->piVar_mode . '.';
    $viewWiDot  = $this->pObj->view . '.';

      // LOOP each sheet
    foreach( $arr_pi_flexform['data'] as $sheet => $arr_sheet )
    {
      $prefix_localView = null;
      if( is_array( $this->conf_view['flexform.']['pi5.'] ) )
      {
        $prefix_localView = 'views.' . $viewWiDot . $modeWiDot;
      }
        // LOOP each field
      foreach( $arr_sheet['lDEF'] as $field => $arr_field )
      {
        $value = $arr_field['vDEF'];
        switch($value)
        {
          case( 'ts' ):
          case( empty( $value ) ):
              // Don't do anything: let it do by TypoScript
            break;
          default:
              // Set value in TypoScript by the flexform
            $path_oneDim                = $prefix_localView . 'flexform.pi5.' . $sheet . '.' . $field . '.stdWrap.value';
            $conf_oneDim[$path_oneDim]  = $value;
            if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_cal)
            {
              t3lib_div :: devLog('[INFO/FLEXFORM+CAL/UI] ' . $path_oneDim . ' is set to: ' . $value, $this->pObj->extKey, 0);
            }
            break;
        }
      }
        // LOOP each field
    }
      // LOOP each sheet


      // Restore the TypoScript configuration
    $this->pObj->conf = $this->pObj->objTyposcript->oneDim_to_tree($conf_oneDim);
      // Only for development
    //$conf_pi5_after   = $this->pObj->conf['flexform.']['pi5.'];
//var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->conf);
    return true;
  }











  /***********************************************
  *
  * Filter
  *
  **********************************************/










  /**
 * area_init: Check configuration and init class var arr_area
 *
 * @return	void
 * @version 4.1.21
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

      // #41776, dwildt, 1-
//    foreach ($this->pObj->objFltr3x->arr_conf_tableFields as $tableField)
      // #41776, dwildt, 1+
    foreach ($this->pObj->objFltr4x->arr_conf_tableFields as $tableField)
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
 * @param	array		$arr_ts: The TypoScript configuration of the current filter
 * @param	array		$arr_values: The values for the current filter
 * @param	string		$tableField: The current table.field
 * @return	array		Data array with $key => $value
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
 * @param	array		$arr_ts: The TypoScript configuration of the current filter
 * @param	array		$arr_values: The values for the current filter
 * @param	string		$tableField: The current table.field
 * @return	void
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
  * Filter Area Helper
  *
  **********************************************/









  /**
 * area_get_urlPeriod(): Get the get parameter from TypoScript
 *                       From tsConf the array ['area.']['interval.' || 'string.']['options.']['fields.]
 *                       Return wrapped value from 'url_stdWrap'
 *                       #13920, 110319, dwildt
 *
 * @param	array		$arr_ts: The TypoScript configuration of the current filter
 * @param	string		$tableField: The current table.field
 * @param	string		$tsKey: Current tsKey like 10, 20, 30, ...
 * @return	array		$tsKey: I.e. 2011_Jan, 2011_Feb, 2011_Mar, ...
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

    if (empty ($this->arr_area[$tableField]['key']))
    {
      return $tsKey;
    }
      // RETURN there isn't any area for $tableField



      ///////////////////////////////////////////////////////////////
      //
      // Move key (10, 20, 30, ...) to url_stdWrap (i.e: 2011_Jan, 2011_Feb, 2011_Mar, ...)

    $str_area_key = $this->arr_area[$tableField]['key'];
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
        echo __METHOD__ . ' (' . __LINE__ . '): undefined value in switch '.$this->arr_area[$tableField]['key'];
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
 * @param	string		$tableField: The current table.field
 * @param	string		$str_urlPeriod: The url of the period
 * @return	string		$str_urlPeriod: I.e. 2011M%C3%A4r, 2011Apr, 2011Mai, ...
 * @version 3.6.4
 * @since 3.6.4
 */
  function area_get_tsKey_from_urlPeriod($tableField, $str_urlPeriod)
  {
    $tsKey = $str_urlPeriod;



      ///////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any area for $tableField

    if (empty ($this->arr_area[$tableField]['key']))
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
 * @param	array		$arr_ts: The TypoScript configuration of the current filter
 * @param	array		$arr_values: The values for the current filter
 * @param	string		$tableField: The current table.field
 * @return	array		Array with the updated values
 * @version 4.1.21
 * @since 3.6.0
 */
  private function area_set_hits($arr_ts, $arr_values, $tableField)
  {
    $arr_values_new = null;
    
    list ( $table ) = explode('.', $tableField);
    $str_case       = $this->arr_area[$tableField]['key'];



      /////////////////////////////////////////////////////////////////
      //
      // Wrap items, recalculate hits

    $arr_fields = $arr_ts['area.'][$str_case . '.']['options.']['fields.'];
    foreach( $arr_fields as $keyWiDot => $arr_string )
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
      foreach( ( array ) $arr_values as $keyValue => $valueValue )
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
//if( $tableField == 'tx_billing_amount.date' || 1 )
//{
//  $prompt_01 = $tableField . ' ' . $keyValue . ' >= ' . $currFrom . ' && ' . $keyValue . ' < ' . $currTo;
//  $prompt_02 = date( 'c', $keyValue ) . ' >= ' . date( 'c', $currFrom ) . ' && ' . date( 'c', $keyValue ) . ' < ' . date( 'c', $currTo );
//  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $prompt_01, $prompt_02 );
//}
            // Default value: hits
            // 120202, dwildt-
//          if ($keyValue >= $currFrom && $keyValue <= $currTo)
            // 120202, dwildt+
            // Line has to correspondend with similar code some lines below and code in filter::filter_fetch_rows()
          if ($keyValue >= $currFrom && $keyValue < $currTo)
          {
              // #41776: dwildt, 1-
            //$arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFltr3x->arr_hits[$tableField][$keyValue];
              // #41776: dwildt, 1+
            $arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFltr4x->hits_sum[$tableField][$keyValue];
          }
        }
          // Current table is the local table

          // Current table is a foreign table
        if( $table != $this->pObj->localTable )
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
          if( ! isset( $arr_hits[$key] ) )
          {
            $arr_hits[$key] = 0;
          }
//if( $tableField == 'tx_billing_amount.date' || 1 )
//{
//  $prompt_01 = $tableField . ' ' . $valueValue . ' >= ' . $currFrom . ' && ' . $valueValue . ' < ' . $currTo;
//  $prompt_02 = date( 'c', $valueValue ) . ' >= ' . date( 'c', $currFrom ) . ' && ' . date( 'c', $valueValue ) . ' < ' . date( 'c', $currTo );
//  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $prompt_01, $prompt_02 );
//}
            // Default value: hits
            // 120202, dwildt-
//          if ($valueValue >= $currFrom && $valueValue <= $currTo)
            // 120202, dwildt+
            // Line has to correspondend with similar code some lines above and code in filter::filter_fetch_rows()
          if( $valueValue >= $currFrom && $valueValue < $currTo )
          {
              // #41776: dwildt, 1-
            //$arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFltr3x->arr_hits[$tableField][$keyValue];
              // #41776: dwildt, 1+
            $arr_hits[$key] = $arr_hits[$key] + $this->pObj->objFltr4x->hits_sum[$tableField][$keyValue];
          }
        }
          // Current table is a foreign table
      }
        // Recalculate hits
    }
      // Wrap items, recalculate hits



      // Set the global arr_hits
      // #41776, dwildt, 2-
    //unset($this->pObj->objFltr3x->arr_hits[$tableField]);
    //$this->pObj->objFltr3x->arr_hits[$tableField] = $arr_hits;
      // #41776, dwildt, 2+
    unset($this->pObj->objFltr4x->hits_sum[$tableField]);
    $this->pObj->objFltr4x->hits_sum[$tableField] = $arr_hits;
      // Set the global arr_hits

      // RETURN the result
    return $arr_values_new;
  }









  /**
 * area_set_tsPeriod():  Set an auto-generated period in the TypoScript
 *                       Add to the tsConf the array ['area.']['interval.']['options.']['fields.]
 *                       Return an updated $arr_ts
 *
 * @param	array		$arr_ts: The TypoScript configuration of the current filter
 * @param	string		$tableField: The current table.field
 * @return	array		Updated $arr_ts
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

      // Field start_period
    $arr_period_conf    = $arr_interval[$this->str_area_case . '.'];
    $start_period       = $arr_period_conf['start_period.']['stdWrap.']['value'];
    $start_period_conf  = $arr_period_conf['start_period.']['stdWrap.'];
    $start_period_conf  = $this->pObj->objZz->substitute_t3globals_recurs($start_period_conf);
    $start_period       = $this->pObj->local_cObj->stdWrap($start_period, $start_period_conf);
      // Field start_period

      // 110820, dwildt +
    $bool_strtotime = $arr_period_conf['start_period.']['use_php_strtotime'];
    $arr_result     = $this->zz_strtotime( $bool_strtotime, $start_period );
    $start_period   = $arr_result['result'];
      // 110820, dwildt +
      // 110820, dwildt -
//    if($arr_period_conf['start_period.']['use_php_strtotime'])
//    {
//      $tmp_timestamp = strtotime($start_period);
//      if(!$tmp_timestamp)
//      {
//          // DRS - Development Reporting System
//        if ($this->pObj->b_drs_warn)
//        {
//          t3lib_div :: devLog('[WARN/CAL] area.interval.start_period hasn\'t any strtotime format: ' . $start_period, $this->pObj->extKey, 2);
//          t3lib_div :: devLog('[INFO/CAL] start_period is set to today 0:00.', $this->pObj->extKey, 0);
//          t3lib_div :: devLog('[HELP/CAL] Please take car of a proper configuration.', $this->pObj->extKey, 1);
//        }
//          // DRS - Development Reporting System
//        $start_period = strtotime('today 0:00');
//      }
//      if($tmp_timestamp)
//      {
//        $start_period = $tmp_timestamp;
//      }
//    }
      // 110820, dwildt -

      // #29444: 110901, dwildt+
      // default period, if no period is selected by the category menu
    $selected_period        = $arr_period_conf['selected_period.']['stdWrap.']['value'];
    $selected_period_conf   = $arr_period_conf['selected_period.']['stdWrap.'];
    $selected_period_conf   = $this->pObj->objZz->substitute_t3globals_recurs($selected_period_conf);
    $selected_period        = $this->pObj->local_cObj->stdWrap($selected_period, $selected_period_conf);
    $bool_strtotime         = $arr_period_conf['selected_period.']['use_php_strtotime'];
    $arr_result             = $this->zz_strtotime( $bool_strtotime, $selected_period );
    $this->selected_period  = $arr_result['result'];
      // #29444: 110901, dwildt+




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









  /***********************************************
  *
  * Helper
  *
  **********************************************/








  /**
 * zz_strtotime(): Upgrade rows for a day's schedule
 *
 * @param	boolean		$bool_strtotime   true: use strtotime; false: do noting
 * @param	string		$strtotime: Time string in english language
 * @return	array		$arr_return: result: in case of success timestamp else timestring; ISO_8601: timestamp in ISO 8601 format;
 * @version 4.0.0
 * @since 4.0.0
 */
  public function zz_strtotime( $bool_strtotime, $strtotime )
  {
    $arr_return['result']   = $strtotime;
    $arr_return['ISO_8601'] = null;
    $arr_return['error']    = true;

    if( ! $bool_strtotime )
    {
      return $arr_return;
    }

//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $bool_strtotime, $strtotime, ( int ) $strtotime, ( (string) $strtotime === (string) ( int ) $strtotime ) );
//    }
      // $strtotime is a timestamp
    if( (string) $strtotime === (string) ( int ) $strtotime )
    {
      $arr_return['result']   = $strtotime;
      $arr_return['ISO_8601'] = date('c', $strtotime);
      $arr_return['error']    = false;
      return $arr_return;
    }
      // $strtotime is a timestamp

    $timestamp = strtotime( $strtotime );

      // RETURN success
    if( (int) $timestamp > 0 )
    {
      $arr_return['result']   = $timestamp;
      $arr_return['ISO_8601'] = date('c', $timestamp);
      $arr_return['error']    = false;
      return $arr_return;
    }
      // RETURN success

      // DRS - Development Reporting System
    if ($this->pObj->b_drs_warn)
    {
      t3lib_div :: devLog('[WARN/CAL] Given value hasn\'t any allowed strtotime format: ' . $strtotime, $this->pObj->extKey, 2);
      t3lib_div :: devLog('[INFO/CAL] Method will return \'today 0:00\' as timestamp.', $this->pObj->extKey, 0);
      t3lib_div :: devLog('[HELP/CAL] Please take car of a proper configuration. String for strtotime must be in english language.', $this->pObj->extKey, 1);
    }
      // DRS - Development Reporting System

    $timestamp = strtotime('today 0:00');
    //$timestamp = mktime(date('G'), 0, 0, date('m') , date('d'), date('Y'));
    //$timestamp = mktime($hour, 0, 0, $month , $day, $year);
    $arr_return['result']   = $timestamp;
    $arr_return['ISO_8601'] = date('c', $timestamp);
    return $arr_return;
  }









  /**
 * zz_tableFieldStdWrap():  Wrap the given table.field value depending on it's TypoScript configuration.
 *                          If the given value is null, method won't be executed.
 *                          If there isn't any configuration, value will wrapped with a default configuration.
 *                          Value will get a link to the singleView (depending on some things, see code below).
 *                          Marker in the configuration will replaced recursive with values of the current row.
 *
 * @param	string		$tableField:      Name of the current table.field
 * @param	string		$value:           Value of the current table-field
 * @param	string		$elements:        Current row (from SQL, conslidated)
 * @param	boolean		$linkToSingle:    Should value get a link to the single view
 * @return	string		$value: The wrapped value
 * @version 4.0.0
 * @since 4.0.0
 */
  public function zz_tableFieldStdWrap( $tableField, $value, $elements, $linkToSingle=true )
  {
      // RETURN empty value
    if( $value == null )
    {
      return;
    }
      // RETURN empty value

    $markerArray          = $this->markerArray;
    list($table, $field)  = explode( '.', $tableField);

      // No configuration: set default cObj
    if( empty( $this->conf_view[$table . '.'][$field . '.'] ) )
    {
      $this->conf_view[$table . '.'][$field] = 'TEXT';
    }
      // No configuration: set default cObj

      // Get configuration
    $cObj_name  = $this->conf_view[$table . '.'][$field];
    $cObj_conf  = $this->conf_view[$table . '.'][$field . '.'];
      // Get configuration

      // No value: set default value
    if ( empty( $cObj_conf['value'] ) )
    {
      $cObj_conf['value'] = $value;
    }
      // No value: set default value

      // LOOP marker array with all values of the current row
    foreach( $elements as $el_tableField => $el_value )
    {
      $markerArray['###' . strtoupper( $el_tableField ) . '###'] = $el_value;
    }
      // LOOP marker array with all values of the current row

//:TODO: 110821, dwildt: If conf has a typolink array, add a cHash

      // Set link to single view
    if( ! is_array( $cObj_conf['typolink.'] ) && $linkToSingle )
    {
        // Only if current view is a list
      if( $this->pObj->view == 'list' )
      {
        if( in_array( $tableField, $this->pObj->arrLinkToSingle ) )
        {
          $singlePid = $this->singlePid;

            // Alias for showUid? #9599
          $curr_piVars = $this->pObj->piVars;
          if( empty( $this->pObj->piVar_alias_showUid ) )
          {
            $this->pObj->piVars['showUid'] = $this->record_uid;
          }
          if( ! empty( $this->pObj->piVar_alias_showUid ) )
          {
            unset( $this->pObj->piVars['showUid'] );
            $this->pObj->objZz->tmp_piVars['showUid'] = null;
            $this->pObj->piVars[$this->pObj->piVar_alias_showUid] = $this->record_uid;
          }
            // Alias for showUid? #9599

            // Attach piVars
          foreach((array) $this->pObj->piVars as $paramKey => $paramValue)
          {
            if(!empty($paramValue))
            {
              $additionalParams .= '&'.$this->pObj->prefixId.'['.$paramKey.']='.$paramValue;
            }
          }
            // Attach piVars

            // Build the typolink
          $cHash_calc = $this->pObj->objZz->get_cHash( '&id='.$singlePid.$additionalParams );
          $cObj_conf['typolink.']['parameter']         = $singlePid;
          $cObj_conf['typolink.']['additionalParams']  = $additionalParams.'&cHash='.$cHash_calc;
          $cObj_conf['typolink.']['ATagParams']        = 'class="linktosingle"';  // Needed for AJAX
            // Build the typolink

          $this->pObj->piVars = $curr_piVars;

        }
      }
        // Only if current view is a list
    }
      // Set link to single view

      // Workaround "npz.ch" because of bug: $this->conf_view[$table . '.'][$field . '.'] will be changed, but it should not!
    $serialized_conf = serialize($this->conf_view[$table . '.'][$field . '.']);
      // Substitute marker recursive
    $cObj_conf  = $this->pObj->cObj->substituteMarkerInObject( $cObj_conf, $markerArray );
      // Wrap the value
    $value      = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      // Workaround "npz.ch"
    $this->conf_view[$table . '.'][$field . '.'] = unserialize($serialized_conf);

      // RETURN the wrapped value
    return $value;
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_cal.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_cal.php']);
}
?>
