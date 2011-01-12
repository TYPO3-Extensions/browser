<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_marker bundles zz methods for the extension browser
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 *
 * @since     3.4.4
 * @version   3.4.4
 * @package    TYPO3
 * @subpackage    tx_browser
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_marker
 *   95:     function __construct($parentObj)
 *
 *              SECTION: $GLOBAL markers
 *  123:     function get_t3globals_value($marker)
 *  198:     function substitute_t3globals_recurs($arr_multi_dimensional)
 *
 *              SECTION: Session markers
 *  391:     function session_marker($arr_tsConf, $elements)
 *
 *              SECTION: Markers
 *  478:     function substitute_marker_recurs($arr_multi_dimensional, $elements)
 *  781:     function extend_marker_wi_pivars($markerArray)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
  class tx_browser_pi1_marker
  {






  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)


  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $conf               = false;
  // [Array] The current TypoScript configuration array
  var $tmp_piVars         = false;
  // [Array] Temporarily array for storing piVars
  var $arr_t3global_keys  = false;
  // [Array] Array with all keys of the TYPO3 array $GLOBALS







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
  }











    /***********************************************
    *
    * $GLOBAL markers
    *
    **********************************************/

    /**
 * Returns the value for a $GLOBALS marker
 *
 * @param	string		Marker: The TSFE marker like ###TSFE:fe_user|enablecolumns|deleted###
 * @return	string		The value from the TSFE array
 */
  function get_t3globals_value($marker)
  {

    //////////////////////////////////////////////////////////////////////
    //
    // Get $GLOBAL['xxx']

    $marker     = str_replace('###', '', $marker);            // ###TSFE:fe_user|enablecolumns|deleted###
    $arr_marker = explode(':', $marker);                      // TSFE:fe_user|enablecolumns|deleted
    $arr_t3globals   = explode('|', $arr_marker[1]);          // fe_user|user|username

    $arr_curr_level     = $GLOBALS[$arr_marker[0]];           // $GLOBAL['TSFE']
    $str_globals_prompt = '$GLOBALS['.$arr_marker[0].']';
    // Get $GLOBAL['xxx']


    //////////////////////////////////////////////////////////////////////
    //
    // Loop through the $GLOBAL['xxx'] array

    $arr_result = false;
    foreach($arr_t3globals as $arr_t3globals_array)
    {
      unset($arr_result);
      $bool_error = true;
      if (is_object($arr_curr_level))
      {
        $arr_next_level     = $arr_curr_level->$arr_t3globals_array;
        $str_globals_prompt = $str_globals_prompt.'->'.$arr_t3globals_array;
        $bool_error = false;
      }
      if (is_array($arr_curr_level))
      {
        $arr_next_level = $arr_curr_level[$arr_t3globals_array];
        $str_globals_prompt = $str_globals_prompt.'['.$arr_t3globals_array.']';
        $bool_error = false;
      }
      if($bool_error)
      {
        if ($this->pObj->b_drs_ttc)
        {
          $str_globals_prompt = $str_globals_prompt.'???'.$arr_t3globals_array;
          t3lib_div::devlog('[WARN/TYPOSCRIPT] '.$str_globals_prompt.' is neither an object nor an array!', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/TYPOSCRIPT] Please edit Your TypoScript:<br />'.
            '###'.$marker.'###', $this->pObj->extKey, 1);
        }
      }
      unset($arr_curr_level);
      $arr_curr_level = $arr_next_level;
      unset($arr_next_level);
    }
    // Loop through the $GLOBAL['TSFE'] array

    return $arr_curr_level;

  }










  /**
 * Get access to the TYPO3 $GLOBALS array: Replace all markers in a multi-dimensional array like an TypoScript array
 * with the values from the $GLOBALS array.
 * Syntax for $GLOBALS markers is: ###$GLOBALS KEY:element_firstLevel|element_secondLevel|...###
 * I.e:                            ###TSFE:fe_user|enablecolumns|deleted###
 *
 * @param	array		$arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @return	array		$arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 */
  function substitute_t3globals_recurs($arr_multi_dimensional)
  {
    $conf       = $this->pObj->conf;
    $conf_view  = $this->pObj->conf['views.'][$this->pObj->view.'.'][$this->pObj->piVar_mode.'.'];

//var_dump('zz 710', $GLOBALS['HTTP_ACCEPT_LANGUAGE']);
// string(35) "de-de,de;q=0.8,en;q=0.5,en-us;q=0.3"
//var_dump('zz 710', $GLOBALS['TSFE']);
//exit;
// string(35) "de-de,de;q=0.8,en;q=0.5,en-us;q=0.3"

    ////////////////////////////////////////////////
    //
    // RETURN, if marker with $Global keys should not replaced

    #10116
    $arr_conf_advanced = $conf['advanced.'];
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }

    $bool_dontReplace = $arr_conf_advanced['performance.']['GLOBALS.']['dont_replace'];
    if($bool_dontReplace)
    {
      if ($this->pObj->b_drs_ttc || $this->pObj->b_drs_plugin)
      {
        t3lib_div::devlog('[INFO/TYPOSCRIPT] [advanced.][performance.][GLOBALS.][dont_replace] is TRUE.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TTC] If you are using markers like ###TSFE:fe_user|...### you should set it to false.<br />'.
          'Be aware that the configuration in the plugin has priority.', $this->pObj->extKey, 1);
      }
      return $arr_multi_dimensional;
    }
    // RETURN, if marker with $Global keys should not replaced


    ////////////////////////////////////////////////
    //
    // Security: recursionGuard

    static $int_levelRecurs = 0;
    $int_levelRecursMax         = (int) $arr_conf_advanced['recursionGuard'];
    $int_levelRecurs++;
    if ($int_levelRecurs > $int_levelRecursMax)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/TTC] Recursion is bigger than '.$int_levelRecursMax, $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/TTC] If it is ok, please increase advanced.recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR/TTC] EXIT', $this->pObj->extKey, 3);
      }
      exit;
    }
    // Security: recursionGuard


    ////////////////////////////////////////////////
    //
    // Get all keys from $GLOBALS

    if($int_levelRecurs < 2)
    {
      $this->arr_t3global_keys = array_keys($GLOBALS);
    }
    // Get all keys from $GLOBALS


    ////////////////////////////////////////////////
    //
    // Loop through the current level of the multi-dimensional array

    foreach($arr_multi_dimensional as $key_arr_curr => $value_arr_curr)
    {
      // The current TypoScript element is an array
      if (is_array($value_arr_curr))
      {
        // Loop through the next level of the multi-dimensional array (recursive)
        $arr_multi_dimensional[$key_arr_curr] = $this->substitute_t3globals_recurs($value_arr_curr);

      }
      // The current TypoScript element is an array


      // The current TypoScript element is a value
      // Replace markers with a $GLOBALS key with the value from the $GLOBALS element
      if(!is_array($value_arr_curr))
      {
        // Loop through all keys of $GLOBALS (We had 77 keys in the November of 2009! TYPO3 4.2.9)
        foreach($this->arr_t3global_keys as $key_t3global)
        {
          // Do we have a marker with a $GLOBALS key?
          $bool_t3globals  = false;
          $int_marker = substr_count($value_arr_curr, '###'.$key_t3global.':');
          if ($int_marker > 0)
          {
            $bool_t3globals = true;
          }
          // Do we have a marker with a $GLOBALS key?

          // We have a marker with a $GLOBALS key
          if ($bool_t3globals)
          {
            $str_value_after_loop = $value_arr_curr;
            $bool_marker          = false;
            $arr_t3globals_marker = explode('###'.$key_t3global.':', $value_arr_curr);
            // We have at least two elements and the first isn't in any case the second part of the marker
            unset($arr_t3globals_marker[0]);
            // Remove the first element

            // Loop through all markers with a $GLOBALS key
            foreach($arr_t3globals_marker as $str_marker_and_more)
            {
              $arr_marker           = explode('###', $str_marker_and_more);
              $key_marker           = '###'.$key_t3global.':'.$arr_marker[0].'###';
              $value_marker         = $this->get_t3globals_value($key_marker);
              $str_tmp_value        = $str_value_after_loop;
              $str_value_after_loop = str_replace($key_marker, $value_marker, $str_value_after_loop);
              if ($str_tmp_value != $str_value_after_loop)
              {
                $bool_marker = true;
              }
            }
            // Loop through all markers with a $GLOBALS key

            // Value has changed
            if ($bool_marker)
            {
              if ($this->pObj->b_drs_ttc)
              {
                t3lib_div::devlog('[INFO/TYPOSCRIPT] ... ['.$key_arr_curr.']: '.$value_arr_curr.'<br />'.
                                  '... became .......... ['.$key_arr_curr.']: '.$str_value_after_loop, $this->pObj->extKey, 0);
              }
              $value_arr_curr   = $str_value_after_loop;
            }
            // Value has changed
          }
          // We have a marker with a $GLOBALS key
          $arr_multi_dimensional[$key_arr_curr] = $value_arr_curr;
        }
        // Loop through all keys of $GLOBALS
      }
      // Replace markers with a $GLOBALS key with the value from the $GLOBALS element
      // The current TypoScript element is a value
    }
    // Loop through the current level of the multi-dimensional array
    return $arr_multi_dimensional;
  }

































    /***********************************************
    *
    * Session markers
    *
    **********************************************/

    /**
 * Returns the value for a $GLOBALS marker
 *
 * @param	string		$arr_tsConf: The current TypoScript configuration
 * @param	array		$elements: Array with the element session
 * @return	string		The value from the TSFE array
 */
  function session_marker($arr_tsConf, $elements)
  {

    $str_sess_key  = $arr_tsConf['session.']['key'];  // i.e: ses
    $str_sess_name = $arr_tsConf['session.']['name']; // i.e: wt_cart_cart
    $arr_session   = $GLOBALS['TSFE']->fe_user->getKey($str_sess_key, $str_sess_name); // get already exting products from session
//if(t3lib_div::_GP('dev')) var_dump('zz 1008', $str_sess_key, $str_sess_name, $arr_session);

    // RETURN default value, if we don't have any session
    if(!is_array($arr_session) || count($arr_session) < 1)
    {
      $elements['session'] = $arr_tsConf['session.']['getDefault'];  // i.e: 1
      return $elements;
    }
    // RETURN default value, if we don't have any session


    $arr_tsConf = $this->substitute_marker_recurs($arr_tsConf, $elements);

    $str_keyElement   = $arr_tsConf['session.']['whereElement.']['key'];   // i.e: uid
    $str_valueElement = $arr_tsConf['session.']['whereElement.']['value']; // i.e: ###SHOWUID###
//if(t3lib_div::_GP('dev')) var_dump('zz 1020', $arr_session, $str_keyElement, $str_valueElement);
    // One loop for every item
if(t3lib_div::_GP('dev')) var_dump('zz 1022', $arr_session);
    foreach ($arr_session as $key => $value)
    {
//if(t3lib_div::_GP('dev')) var_dump('zz 1025', $key, $str_keyElement, $arr_session[$key][$str_keyElement]);
//if(t3lib_div::_GP('dev')) var_dump('zz 1026', $arr_session[$key][$str_keyElement].' = '.$str_valueElement);
      if ($arr_session[$key][$str_keyElement] == $str_valueElement)
      {
        $elements['session'] = $arr_session[$key][$arr_tsConf['session.']['getFrom']];
if(t3lib_div::_GP('dev')) var_dump('zz 1033', $arr_session[$key][$arr_tsConf['session.']['getFrom']], $key, $arr_tsConf['session.']['getFrom']);
        break;
      }
    }

    if(!isset($elements['session']))
    {
      $elements['session'] = $arr_tsConf['session.']['getDefault'];
    }


//$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', 'wt_cart_cart'); // get already exting products from session
//if(t3lib_div::_GP('dev')) var_dump('zz 1080', $sesArray);
//if(t3lib_div::_GP('dev')) var_dump('zz 1005', $arr_tsConf);
//if(t3lib_div::_GP('dev')) var_dump('zz 1082', $arr_tsConf['session.']);
if(t3lib_div::_GP('dev')) var_dump('zz 1048', $elements);
    return $elements;
  }



















    /***********************************************
    *
    * Markers
    *
    **********************************************/






  /**
 * Replace all markers in a multi-dimensional array like an TypoScript array with the real values from the SQL result
 * The method extends the SQL result with all piVar values. ###CHASH### has a process.
 *
 * @param	array		$arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param	array		$elements: The current row of the SQL result
 * @return	array		$arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 */
  function substitute_marker_recurs($arr_multi_dimensional, $elements)
  {
    $conf = $this->pObj->conf;
    $conf_view  = $this->pObj->conf['views.'][$this->pObj->view.'.'][$this->pObj->piVar_mode.'.'];

    /////////////////////////////////////
    //
    // Get arr_children_to_devide as array

    $arr_children_to_devide = $this->pObj->arr_children_to_devide;
    if(!is_array($arr_children_to_devide))
    {
      $arr_children_to_devide = array();
    }
    // Get arr_children_to_devide as array



    /////////////////////////////////////
    //
    // Security: recursionGuard

    static $int_levelRecurs = 0;

    #10116
    $arr_conf_advanced = $conf['advanced.'];
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }

    $int_levelRecursMax = (int) $arr_conf_advanced['recursionGuard'];
    $int_levelRecurs++;
    if ($int_levelRecurs > $int_levelRecursMax)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR] Recursion is bigger than '.$int_levelRecursMax, $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP] If it is ok, please increase advanced.recursionGuard.', $this->pObj->extKey, 1);
        t3lib_div::devlog('[ERROR] EXIT', $this->pObj->extKey, 3);
      }
      $prompt = '<h1>Recursion Guard</h1>
        <p>
          Recursion is bigger than '.$int_levelRecursMax.'<br />
          If it is ok, please increase advanced.recursionGuard.<br />
          Method: class.tx_browser_pi1_marker.php::substitute_marker_recurs()
        </p>';
      echo $prompt;
      exit;
    }
    // Security: recursionGuard



    //////////////////////////////////////////////////////////////
    //
    // Get the children devider configuration

    if($int_levelRecurs == 0)
    {
      //if(t3lib_div::_GP('dev')) var_dump('zz 1223: 0', $int_levelRecurs);
      if($this->pObj->objTyposcript->str_sqlDeviderDisplay == false)
      {
        $this->pObj->objTyposcript->set_confSqlDevider();
      }
      if($this->pObj->objTyposcript->str_sqlDeviderWorkflow == false)
      {
        $this->pObj->objTyposcript->set_confSqlDevider();
      }
    }
    // Get the children devider configuration



    /////////////////////////////////////
    //
    // Add to the $elements the piVars

    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      // dwildt, 090620: If we have multiple selects, piVars can contain arrays
      // This array should be array of uids. We don't need any process for uids here.
      if (!is_array($value_pivar))
      {
        $elements[strtolower($key_pivar)] = $value_pivar;
      }
      if ($int_levelRecurs < 2)
      {
        // It is the first loop
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
        }
      }
    }
    // Add to the $elements the piVars



    /////////////////////////////////////
    //
    // Add to the $elements the singlePid

    if (isset($this->pObj->singlePid))
    {
      $elements[strtolower('singlePid')] = $this->pObj->singlePid;
    }
    // Add to the $elements the singlePid



    /////////////////////////////////////
    //
    // Loop through the current level of the multi-dimensional array

    foreach($arr_multi_dimensional as $key_arr_curr => $value_arr_curr)
    {
      // 100709, fsander
      // if(is_array(array_keys($value_arr_curr)))
      if(is_array($value_arr_curr) && is_array(array_keys($value_arr_curr)))
      {
        if(in_array('session.', array_keys($value_arr_curr)))
        {
          $elements = $this->session_marker($value_arr_curr, $elements);

        }
      }

      if (is_array($value_arr_curr))
      {
        // Loop through the next level of the multi-dimensional array (recursive)
        $arr_multi_dimensional[$key_arr_curr] = $this->substitute_marker_recurs($value_arr_curr, $elements);
      }


      /////////////////////////////////////
      //
      // Replace markers with the values

      if(!is_array($value_arr_curr))
      {
        // Do we have markers?
        $b_marker = true;
        $i_marker = substr_count($value_arr_curr, '###');  // I.e: 4
        if ($i_marker == 0)
        {
          $b_marker = false;
          // There isn't any '###'
        }
        // Do we have markers?

        if ($b_marker)
        {
          $str_value_after_loop = $value_arr_curr;
          $b_marker_changed     = false;

          // Loop: Replace all used markers, if they have a real value
          foreach($elements as $key_marker => $value_marker)
          {
            $bool_marker   = false;
            $str_tmp_value = $str_value_after_loop;
            $str_marker    = '###'.strtoupper($key_marker).'###';

            // Value has the current marker
            if (!(strpos($str_tmp_value, $str_marker) === false))
            {
              // Marker has children values
              if(in_array($key_marker, $arr_children_to_devide))
              {
                // Get the workflow devider for children values
                $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
                $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
                $str_devider            = $str_sqlDeviderDisplay.$str_sqlDeviderWorkflow;
                // Get the workflow devider for children values

                // Get children values
                $arr_valuesChildren   = explode($str_devider, $value_marker);

                // Multiple the values and replace the marker for every child
                // EXAMPLE for value
                //   Before marker replacement: &tx_trevent_pi1[uid]=###FE_USERS.UID###&###CHASH###
                //   After  marker replacement: &tx_trevent_pi1[uid]=158&###CHASH###, ;|;&tx_trevent_pi1[uid]=155&###CHASH###
                $arr_lConfCObj = array();
                foreach($arr_valuesChildren as $keyChild => $valueChild)
                {
                  $arr_value_after_loop[] = str_replace($str_marker, $valueChild, $str_tmp_value);
                }
                $str_value_after_loop = implode($str_devider, $arr_value_after_loop);
                // Multiple the values and replace the marker for every child
              }
              // Marker has children values

              // Marker hasn't any child value
              if(!in_array($key_marker, $arr_children_to_devide))
              {
                $value_marker         = $this->pObj->objZz->color_swords($key_marker, $value_marker);
                // 3.3.4
                //$str_value_after_loop = str_replace($str_marker, $value_marker, $str_value_after_loop);
                $str_value_after_loop = str_replace($str_marker, $value_marker, $str_tmp_value);
              }
              // Marker hasn't any child value
            }
            // Value has the current marker

            // Set boolean for workflow
            if ($str_tmp_value != $str_value_after_loop)
            {
              //if(t3lib_div::_GP('dev')) var_dump('zz 1375', $key_marker, $str_tmp_value, $str_value_after_loop);
              $bool_marker = true;
            }
            // Set boolean for workflow

            $str_elements1        = htmlspecialchars($value_marker);
            if (strlen($str_elements1) > $this->pObj->i_drs_max_sql_result_len)
            {
              $str_elements1 = substr($str_elements1, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
            }
            if ($bool_marker)
            {
              if ($this->pObj->b_drs_ttc)
              {
                if(!$str_elements1)
                {
                  t3lib_div::devlog('[INFO/TTC] ... ['.$key_arr_curr.']: '.$str_marker.' is NULL.', $this->pObj->extKey, 0);
                }
                else
                {
                  t3lib_div::devlog('[INFO/TTC] ... ['.$key_arr_curr.']: '.$str_marker.' become:<br /><br />'.$str_elements1, $this->pObj->extKey, 0);
                }
              }
            }
          }
          // Loop: Replace all used markers, if they have a real value

          // Do we have a cHash marker?
          $pos = strpos($str_value_after_loop, '&###CHASH###');
          if (!($pos === false)) {
            $str_path             = str_replace('&###CHASH###', '', $str_value_after_loop);
            $arr_url              = parse_url($str_path);
            $cHash_md5            = $this->pObj->objZz->get_cHash($arr_url['path']);
            $str_value_after_loop = str_replace('&###CHASH###', '&cHash='.$cHash_md5, $str_value_after_loop);
          }
          // Do we have a cHash marker?

          if ($str_value_after_loop != $value_arr_curr)
          {
            // Value has changed
            $b_marker_changed = true;
            $value_arr_curr = $str_value_after_loop;
          }
          else
          {
            if ($this->pObj->b_drs_ttc)
            {
              t3lib_div::devlog('[INFO/TTC] ... ['.$key_arr_curr.']: hasn\'t any marker.', $this->pObj->extKey, 0);
            }
          }


          /////////////////////////////////////
          //
          // Delete the markers, which weren't replaced in the multi-dimensional array

          $arr_value            = array($value_arr_curr);
          $arr_markers_in_value = $this->pObj->objTTContainer->get_marker_keys_recursive($arr_value);
          if (is_array($arr_markers_in_value))
          {
            if (count($arr_markers_in_value) >= 1)
            {
              // There is one non replaced marker at least
              foreach ($arr_markers_in_value as $key_m_i_value => $value_m_i_value)
              {
                $value_arr_curr = str_replace('###'.strtoupper($key_m_i_value).'###', '', $value_arr_curr);
              }
            }
          }
          // Delete the markers, which weren't replaced in the multi-dimensional array
        }
        $arr_multi_dimensional[$key_arr_curr] = $value_arr_curr;
      }
      // Replace markers with the values

    }
    // Loop through the current level of the multi-dimensional array

    return $arr_multi_dimensional;
  }







  /**
 * Replace all markers in a multi-dimensional array like an TypoScript array with the real values from the SQL result
 * The method extends the SQL result with all piVar values
 *
 * @param	array		$arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param	array		$elements: The current row of the SQL result
 * @return	array		$arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 */
  function extend_marker_wi_pivars($markerArray)
  {

    /////////////////////////////////////
    //
    // Add to the marker array the piVars

    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      $markerArray['###'.strtoupper($key_pivar).'###'] = $value_pivar;
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
      }
    }

    return $markerArray;
  }




}









if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_marker.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_marker.php']);
}

?>