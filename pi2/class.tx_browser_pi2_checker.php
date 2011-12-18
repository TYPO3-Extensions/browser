<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2011 - Dirk Wildt <wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi2_checker bundles classes for checking TypoScript update
 *
 * @author    Dirk Wildt <wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    browser
 * @version 3.7.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   58: class tx_browser_pi2_checker
 *   73:     function __construct($parentObj)
 *
 *              SECTION: Main
 *  109:     function loop_tickets()
 *  211:     function get_image_path($int_status)
 *
 *              SECTION: Checker Methods
 *  289:     function str_to_int($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  419:     function moved_value_into_array($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  544:     function moved_value($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  658:     function remove_value($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  766:     function moved_from_ts_to_plugin($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  872:     function moved_from_plugin_to_plugin($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
 *  992:     function prompt_otherBrowserPlugins()
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi2_checker
{



  var $str_style_ts;
  // HTML style property for TypoScript Code


/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
    $this->conf = $this->pObj->conf;

    $this->str_style_ts = ' style="font-size:small;color:blue;padding:0 0 0 1em;background:#EEE;"';

  }










  /***********************************************
  *
  * Main
  *
  **********************************************/








 /**
  * loop_tickets: Check the current TypoScript configuration. Returns HTML report.
  *
  * @return	string		HTML report
  */
  function loop_tickets( )
  {
    //////////////////////////////////////////////////////////
    //
    // Get the Ticket array and the current Typoscript configuration

    $arr_release_tickets    = $this->pObj->arr_tickets;
    $arr_conf_oneDimension  = t3lib_BEfunc::implodeTSParams($this->conf, $prefixId = '');
    // Get the Ticket array and the current Typoscript configuration


    //////////////////////////////////////////////////////////
    //
    // Loop through all Releases, allocate the report array

    foreach ($arr_release_tickets as $int_release => $arr_tickets)
    {
      // Loop through all Tickets of a Release
      foreach ($arr_tickets as $int_ticketNo => $arr_ticket)
      {
        // Call the user_func
        $str_html_report = call_user_func(array('tx_browser_pi2_checker', $arr_ticket['function']), $int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension);
        if ($str_html_report)
        {
          $arr_html_report[] = $str_html_report;
        }
      }
      // Loop through all Tickets of a Release
    }
    // Loop through all Releases, allocate the report array



    //////////////////////////////////////////////////////////
    //
    // In case of any error and warning

    if (is_array($arr_html_report))
    {
      $template = implode('<hr />', $arr_html_report);
    }
    // In case of any error and warning



    //////////////////////////////////////////////////////////
    //
    // In case of a proper result

    if (!is_array($arr_html_report))
    {
      $str_img_info = '<img align="top" '.
                      'alt="OK" title="OK" '.
                      'src="'.$this->get_image_path(PI2_STATUS_OK).'"/>';
      $template = '
        <p>
          '.$str_img_info.' '.$this->pObj->pi_getLL('ok').'
        </p>
        ';
      $template = $template.$this->prompt_otherBrowserPlugins();
    }
    // In case of a proper result



    //////////////////////////////////////////////////////////
    //
    // The whole result

    $template = '
      <div style="border:1px solid red;padding:.3em;font-size:.8em;">
        <p style="text-align:right;font-weight:bold;color:red;">
          '.$this->pObj->pi_getLL('header').'
        </p>
      '.$template.'
      </div>
      <p></p>
      ';
    // The whole result



    return $template;
  }












 /**
  * Get teh path to a gif image
  *
  * @param	integer		$int_status: The constant of the status message
  * @return	string		Path to the GIF image
  */
  function get_image_path($int_status)
  {
    switch($int_status)
    {
      case(PI2_STATUS_OK):
        $str_gif  = 'icon_ok.gif';
        $int_w    = 18;
        $int_h    = 16;
        break;
      case(PI2_STATUS_INFO):
        $str_gif  = 'info.gif';
        $int_w    = 17;
        $int_h    = 12;
        break;
      case(PI2_STATUS_HELP):
        $str_gif  = 'icon_note.gif';
        $int_w    = 18;
        $int_h    = 16;
        break;
      case(PI2_STATUS_WARN):
        $str_gif  = 'icon_warning.gif';
        $int_w    = 16;
        $int_h    = 16;
        break;
      case(PI2_STATUS_ERROR):
        $str_gif  = 'icon_fatalerror.gif';
        $int_w    = 14;
        $int_h    = 14;
        break;
    }
    return   TYPO3_mainDir.'sysext/t3skin/icons/gfx/'.$str_gif;
  }
























/***********************************************
  *
  * Checker Methods
  *
  **********************************************/








  /**
 * Check if the current TypoScript code value is a string and not an integer. Returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function str_to_int($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_code = $arr_ticket['srce']['typoscript']['code'];

    // If there is no code to update RETURN
    if (!array_key_exists($str_srce_code, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_srce_code.' isn\'t a part of TypoSript.', $this->pObj->extKey, 0);
      }
      return false;
    }
    // If there is no code to update RETURN

    // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_code];

    // If we have an integer RETURN
    if (is_numeric($str_srce_value))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_srce_code.' is an integer.', $this->pObj->extKey, 0);
      }
      return false;
    }
    // If we have an integer RETURN

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_code.' is a string. Update it to an integer!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the localised example prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['expl']['prompt']))
    {
      $str_expl_prompt = $arr_ticket['expl']['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['expl']['prompt']))
    {
      $str_expl_prompt = $arr_ticket['expl']['prompt']['default'];
    }
    // Get the localised example prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
          <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p'.$this->str_style_ts.'>'.$str_srce_code.' = '.$str_srce_value.'</p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_dest').'</h3>
      <p>'.$str_expl_prompt.'</p>
      <p'.$this->str_style_ts.'>'.$arr_ticket['expl']['code'].'</p>

      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }








  /**
 * Check if the given value exist. If yes, the value is out of date
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function value_is_out_of_date($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_code          = $arr_ticket['srce']['typoscript']['code'];
    $str_value_out_of_date  = $arr_ticket['srce']['value_out_of_date'];
    $str_new_value          = $arr_ticket['srce']['new_value'];

      // If there is no code to update RETURN
    if (!array_key_exists($str_srce_code, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_srce_code.' isn\'t a part of TypoSript.', $this->pObj->extKey, 0);
      }
      return false;
    }
      // If there is no code to update RETURN

      // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_code];

      // If TypoScript property doesn't contain the soruce code RETURN
    if ( $str_srce_value == $str_value_out_of_date )
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] ' . $str_srce_code . ' != ' . $str_value_out_of_date, $this->pObj->extKey, 0);
      }
      return false;
    }
      // If TypoScript property doesn't contain the soruce code RETURN

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_code.' is out of date. Update it to ' . $str_new_value, $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the localised example prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['expl']['prompt']))
    {
      $str_expl_prompt = $arr_ticket['expl']['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['expl']['prompt']))
    {
      $str_expl_prompt = $arr_ticket['expl']['prompt']['default'];
    }
    // Get the localised example prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p' . $this->str_style_ts. '>' . $str_srce_code . ' = ' . $str_value_out_of_date . '</p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_dest').'</h3>
      <p' . $this->str_style_ts. '>' . $str_srce_code . ' = ' . $str_new_value . '</p>
      <p>'.$str_expl_prompt.'</p>
      <p'.$this->str_style_ts.'>'.$arr_ticket['expl']['code'].'</p>

      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }









    /**
 * Check if the current TypoScript value isn't moved to the destination array. Returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function moved_value_into_array($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_code = $arr_ticket['srce']['typoscript']['code'];
    $str_dest_code = $arr_ticket['dest']['typoscript']['code'];

    // If we have the destination code RETURN
    if (array_key_exists($str_dest_code, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_dest_code.' is a part of the TypoSript.', $this->pObj->extKey, 0);
      }
      // Is there the source code?
      if (array_key_exists($str_srce_code, $arr_conf_oneDimension))
      {
        if ($this->pObj->pObj->b_drs_tsUpdate)
        {
          t3lib_div::devlog('[WARN/UPDATE] Please delete depricated TypoScript:<br />'.$str_srce_code.' >', $this->pObj->extKey, 2);
        }
      }
      // Is there the source code?
      return false;
    }
    // If we have the destination code RETURN

    // If there is no code to update RETURN
    if (!array_key_exists($str_srce_code, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_srce_code.' isn\'t a part of TypoSript.', $this->pObj->extKey, 0);
      }
      return false;
    }
    // If there is no code to update RETURN

    // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_code];

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_code.' is at a depricated position. Update it and move it to the destination array!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p'.$this->str_style_ts.'>'.$str_srce_code.' = '.$str_srce_value.'</p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_dest').'</h3>
      <p'.$this->str_style_ts.'>'.$str_dest_code.' = '.$str_srce_value.'</p>
      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }









    /**
 * Check if the current TypoScript array isn't moved to the destination array. Returns an HTML report.
 *
 * @param integer   $int_release: The number of the release like 0, 1, 2, 3, ...
 * @param integer   $int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param array   $arr_ticket: The array with the tiket properties
 * @param array   $arr_conf_oneDimension: The current TypoScript configuration
 * @return  string    $str_html_return: HTML report
 * @version 3.7.0
 */
  function moved_array($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_path = $arr_ticket['srce']['typoscript']['path'];
    $str_dest_path = $arr_ticket['dest']['typoscript']['path'];

//      // If we have the destination code RETURN
//    foreach((array) $arr_conf_oneDimension as $key => $value)
//    {
//      $pos = strpos($key, $str_dest_path);
////      if (!($pos === false)) 
//      if ($pos === 0) 
//      {
//        if ($this->pObj->pObj->b_drs_tsUpdate)
//        {
//          t3lib_div::devlog('[INFO/UPDATE] ' . $str_dest_path . ' is a part of the TypoSript.', $this->pObj->extKey, 0);
//        }
//          // Is there the source code?
//        foreach((array) $arr_conf_oneDimension as $key2 => $value2)
//        {
//          $pos = strpos($key2, $str_srce_path);
//          if (!($pos === false)) 
//          {
//            if ($this->pObj->pObj->b_drs_tsUpdate)
//            {
//              t3lib_div::devlog('[WARN/UPDATE] Please delete depricated TypoScript:<br />' . $str_srce_path . ' >', $this->pObj->extKey, 2);
//            }
//            return false;
//          }
//        }
//          // Is there the source code?
//        return false;
//      }
//    }
//      // If we have the destination code RETURN

      // Get the source value
    $bool_srce = false;
    foreach((array) $arr_conf_oneDimension as $key2 => $value2)
    {
      $pos = strpos($key2, $str_srce_path);
//      if (!($pos === false)) 
      if ($pos === 0)
      {
        $bool_srce = true;
        break;
      }
    }

      // RETURN there isn't any source code
    if(!$bool_srce)
    {
      return;
    }

      // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] ' . $str_srce_path . ' is depricated. Update it to ' . $str_dest_path . '!', $this->pObj->extKey, 2);
    }
      // DRS - Development Reporting system

      // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
      // Get the localised header

      // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
      // Get the localised prompt

      // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_' . $arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_' . $arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="' . $str_status . '" title="' . $str_status . '" '.
                          'src="' . $this->get_image_path($arr_ticket['status']) . '"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="' . $this->pObj->pi_getLL('ticket_phrase_todo') . '" '.
                          'title="' . $this->pObj->pi_getLL('ticket_phrase_todo') . '" '.
                          'src="' . $this->get_image_path(PI2_STATUS_HELP) . '"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>' . $this->pObj->pi_getLL('ticket_phrase_ticket') . ': </strong>' . $str_ticketNo . '
      </p>
      <p>
          ' . $str_img_info . ' ' . $str_todo . '
      </p>
      <h3>' . $this->pObj->pi_getLL('ticket_phrase_srce') . '</h3>
      <p' . $this->str_style_ts.'>'.$str_srce_path . ' { ... </p>
      <h3>' . $this->pObj->pi_getLL('ticket_phrase_dest') . '</h3>
      <p' . $this->str_style_ts . '>' . $str_dest_path . ' { ... </p>
      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', ' { ... ', $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }









    /**
 * Check if the current TypoScript array isn't moved to the destination array. Returns an HTML report.
 *
 * @param integer   $int_release: The number of the release like 0, 1, 2, 3, ...
 * @param integer   $int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param array   $arr_ticket: The array with the tiket properties
 * @param array   $arr_conf_oneDimension: The current TypoScript configuration
 * @return  string    $str_html_return: HTML report
 */
  function moved_value($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_path = $arr_ticket['srce']['typoscript']['path'];
    $str_dest_path = $arr_ticket['dest']['typoscript']['path'];

    // If we have the destination code RETURN
    if (array_key_exists($str_dest_path, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_dest_path.' is a part of the TypoSript.', $this->pObj->extKey, 0);
      }
      // Is there the source code?
      if (array_key_exists($str_srce_path, $arr_conf_oneDimension))
      {
        if ($this->pObj->pObj->b_drs_tsUpdate)
        {
          t3lib_div::devlog('[WARN/UPDATE] Please delete depricated TypoScript:<br />'.$str_srce_path.' >', $this->pObj->extKey, 2);
        }
      }
      // Is there the source code?
      return false;
    }
    // If we have the destination code RETURN

    // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_path];

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_path.' is at a depricated position. Update it and move it to the destination array!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p'.$this->str_style_ts.'>'.$str_srce_path.' = '.$str_srce_value.'</p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_dest').'</h3>
      <p'.$this->str_style_ts.'>'.$str_dest_path.' = '.$str_srce_value.'</p>
      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }














    /**
 * remove_value: Check if the current TypoScript value or array is existing. Than it returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function remove_value($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    // Get the source value
    $str_srce_path  = $arr_ticket['srce']['typoscript']['path'];

    // If we don't have any source code RETURN
    if (!array_key_exists($str_srce_path, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] TypoScript code is removed:<br />'.$str_srce_path.' >', $this->pObj->extKey, 2);
      }
      // Is there the source code?
      return false;
    }
    // If we don't have any source code RETURN

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_path.' is depricated. Remove it!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_path];

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p'.$this->str_style_ts.'>'.$str_srce_path.' = '.$str_srce_value.'</p>
      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }


















    /**
 * Check if the current TypoScript value isn't moved to the destination array. Returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function moved_from_ts_to_plugin($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    $str_srce_code = $arr_ticket['srce']['typoscript']['code'];

    // If there is no code to update RETURN
    if (!array_key_exists($str_srce_code, $arr_conf_oneDimension))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$str_srce_code.' isn\'t a part of TypoSript.', $this->pObj->extKey, 0);
      }
      return false;
    }
    // If there is no code to update RETURN

    // Get the source value
    $str_srce_value = $arr_conf_oneDimension[$str_srce_code];

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_code.' is at a depricated position. Update it and move it to the destination array!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_todo           = $this->pObj->pi_getLL('ticket_todo_'.$arr_ticket['todo']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';
    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$str_todo.'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_srce').'</h3>
      <p'.$this->str_style_ts.'>'.$str_srce_code.' = '.$str_srce_value.'</p>
      '."\n";

    $str_srce_value   = $arr_conf_oneDimension[$arr_ticket['srce']['typoscript']['code']];
    $str_html_return  = str_replace('%value%', $str_srce_value, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

    return $str_html_return;

  }


















    /**
 * moved_from_plugin_to_plugin: Check if the current TypoScript value isn't moved to the destination array.
 *                          Returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function moved_from_plugin_to_plugin($int_release, $int_ticketNo, $arr_ticket, $arr_conf_oneDimension)
  {
    // Flexform
    $this->pObj->pObj->pi_initPIflexForm();
    $arr_piFlexform = $this->pObj->pObj->cObj->data['pi_flexform'];
    // Flexform

    // Source
    $srce_divField = $arr_ticket['srce'];
    list($str_srce_div, $str_srce_field) = explode('.', $srce_divField);
    $csv_srce_values  = $this->pObj->pObj->pi_getFFvalue($arr_piFlexform, $str_srce_field, $str_srce_div, 'lDEF', 'vDEF');
    // Source

//var_dump('checker 879', $arr_piFlexform, $str_srce_field, $str_srce_div, $csv_srce_values, $this->pObj->pObj->pi_getFFvalue($arr_piFlexform, 'viewsIds', 'sDEF', 'lDEF', 'vDEF'));
//return;

    // RETURN if there aren't any values
    if (empty($csv_srce_values))
    {
      if ($this->pObj->pObj->b_drs_tsUpdate)
      {
        t3lib_div::devlog('[INFO/UPDATE] '.$srce_divField.' is empty. Nothing to do!', $this->pObj->extKey, 0);
      }
      return false;
    }
    // RETURN if there aren't any values


    // Destination
    $dest_divField = $arr_ticket['dest'];
    list($str_dest_div, $str_dest_field) = explode('.', $dest_divField);
    $csv_dest_values  = $this->pObj->pi_getFFvalue($arr_piFlexform, $str_dest_field, $str_dest_div, 'lDEF', 'vDEF');
    // Destination

    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      t3lib_div::devlog('[WARN/UPDATE] '.$srce_divField.' is depricated position. Move values '.$csv_srce_values.' to '.$srce_divField, $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    // Get the localised header
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['header']))
    {
      $str_h1 = $arr_ticket['header']['default'];
    }
    // Get the localised header

    // Get the localised prompt
    if (array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt'][$GLOBALS['TSFE']->lang];
    }
    if (!array_key_exists($GLOBALS['TSFE']->lang, $arr_ticket['prompt']))
    {
      $str_prompt = $arr_ticket['prompt']['default'];
    }
    // Get the localised prompt

    // Get the formated ticket number
    $str_ticketNo       = sprintf("%04d", $int_ticketNo);
    $str_status         = $this->pObj->pi_getLL('ticket_status_'.$arr_ticket['status']);
    $str_img_status     = '<img align="top" '.
                          'alt="'.$str_status.'" title="'.$str_status.'" '.
                          'src="'.$this->get_image_path($arr_ticket['status']).'"/>';
    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';
    $str_html_return = '
      <h2>'.$str_img_status.' '.$str_h1.'</h2>
      <p>
        '.$str_prompt.'<br />
        <strong>'.$this->pObj->pi_getLL('ticket_phrase_ticket').': </strong>'.$str_ticketNo.'
      </p>
      <p>
          '.$str_img_info.' '.$this->pObj->pi_getLL('ticket_phrase_plugin_new').'
      </p>
      <h3>'.$this->pObj->pi_getLL('ticket_phrase_plugin').'</h3>
      <p'.$this->str_style_ts.'>['.$str_dest_div.']['.$str_dest_field.'] = '.$csv_srce_values.'</p>
      '."\n";

    $str_html_return  = str_replace('%'.$str_srce_field.'%', $csv_srce_values, $str_html_return);
    $str_html_return  = str_replace('%version%', $arr_ticket['version'], $str_html_return);

//var_dump('checker 957', $str_html_return);
    return $str_html_return;

  }


















    /**
 * Check if the current TypoScript value isn't moved to the destination array. Returns an HTML report.
 *
 * @param	integer		$int_release: The number of the release like 0, 1, 2, 3, ...
 * @param	integer		$int_ticketNo: The number of the ticket like 0, 123, 4567
 * @param	array		$arr_ticket: The array with the tiket properties
 * @param	array		$arr_conf_oneDimension: The current TypoScript configuration
 * @return	string		$str_html_return: HTML report
 */
  function prompt_otherBrowserPlugins()
  {
    $str_html_return = false;
    $int_currentPage = $GLOBALS['TSFE']->id;


    //////////////////////////////////////////////////////////
    //
    // Get all browser plugins with uid, pid and hidden

    $select_fields = '`uid`, `pid`, `header`, `hidden`';
    $from_table    = 'tt_content';
    //$where_clause  = '`list_type` = \'browser_pi1\' AND `deleted` = 0 AND `pid` != '.$int_currentPage.' ';
    $where_clause  = '`list_type` = \'browser_pi1\' AND `deleted` = 0 ';
    $orderBy       = '`pid`';
//    $query =        $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy='',$orderBy,$limit='');
//    var_dump($query);
    $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields,$from_table,$where_clause,$groupBy='',$orderBy,$limit='',$uidIndexField='');
    // Get all browser plugins with uid, pid and hidden



    //////////////////////////////////////////////////////////
    //
    // RETURN, if there isn't any row

    if(count($rows) < 1)
    {
      return false;
    }
    // RETURN, if there isn't any row



    //////////////////////////////////////////////////////////
    //
    // Get the <li>-list with links to every page

    $str_list        = false;
    $bool_foreignPid = false;
    foreach((array) $rows as $row)
    {
      // Hidden value
      $str_hidden = false;
      if($row['hidden'])
      {
        $str_hidden = ' ('.$this->pObj->pi_getLL('checker_word_hidden').')';
      }
      // Hidden value

      // Value for the value in the link
      $value = $this->pObj->pi_getLL('checker_word_page').' '.$row['pid'].': '.$row['header'].$str_hidden;

      // Only link on foreign pages
      if($int_currentPage != $row['pid'])
      {
        $bool_foreignPid = true;
        $arr_tsConf['10'] = 'TEXT';
        $arr_tsConf['10.']['value'] = $value;
        $arr_tsConf['10.']['typolink.']['parameter'] = $row['pid'].'#'.$row['uid'].' - - "'.htmlSpecialchars($value).'"';
        $value = $this->pObj->local_cObj->stdWrap($value, $arr_tsConf['10.']);
      }
      // Only link on foreign pages

      // Link on foreign pages
      $str_list = $str_list.'<li>'.$value.'</li>';
      // Link on foreign pages
    }
    $str_list = '<ul>'.$str_list.'</ul>';
    // Get the <li>-list with links to every page



    //////////////////////////////////////////////////////////
    //
    // RETURN, if there isn't any link to a foreign page

    if(!$bool_foreignPid)
    {
      return false;
    }
    // RETURN, if there isn't any link to a foreign page



    // DRS - Development Reporting system
    if ($this->pObj->pObj->b_drs_tsUpdate)
    {
      //t3lib_div::devlog('[WARN/UPDATE] '.$str_srce_code.' is at a depricated position. Update it and move it to the destination array!', $this->pObj->extKey, 2);
    }
    // DRS - Development Reporting system

    $str_img_info       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_HELP).'"/>';

    $str_img_warn       = '<img align="top" '.
                          'alt="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'title="'.$this->pObj->pi_getLL('ticket_phrase_todo').'" '.
                          'src="'.$this->get_image_path(PI2_STATUS_WARN).'"/>';

    $str_html_return = '
      <p>
        '.$str_img_info.' '.$this->pObj->pi_getLL('checker_phrase_morePluginsInThisInstall').'
      </p>
      <p>
        '.$str_img_warn.' '.$this->pObj->pi_getLL('checker_phrase_warnLinkDomain').'
      </p>
      '.$str_list.'
      ';

    return $str_html_return;

  }
















}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2_checker.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2_checker.php']);
}

?>