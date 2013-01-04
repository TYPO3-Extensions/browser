<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2013: Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_marker bundles marker methods for the extension browser
*
* @author    Dirk Wildt http://wildt.at.die-netzmacher.de
*
* @package    TYPO3
* @subpackage    browser
* @version   4.4.0
* @since     3.4.4
*/

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_marker
 *   90:     function __construct($parentObj)
 *
 *              SECTION: Session markers
 *  117:     function session_marker($arr_tsConf, $elements)
 *
 *              SECTION: Markers
 *  191:     function substitute_tablefield_marker($arr_multi_dimensional)
 *  419:     function substitute_marker($arr_multi_dimensional, $marker)
 *  575:     function substitute_marker_recurs($arr_multi_dimensional, $elements)
 *  895:     function extend_marker_wi_cObjData($markerArray)
 *  935:     function extend_marker_wi_pivars($markerArray)
 *  974:     function replace_left_over($str_content)
 *
 * TOTAL FUNCTIONS: 8
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

    // [Array] The current TypoScript configuration array
  var $conf               = false;
    // [Array] Temporarily array for storing piVars
  var $tmp_piVars         = false;
    // [Array] Array with all keys of the TYPO3 array $GLOBALS
  var $arr_t3global_keys  = false;







/**
 * Constructor. The method initiate the parent object
 *
 * @param    object        The parent object
 * @return    void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
    $this->conf = $this->pObj->conf;
  }









    /***********************************************
    *
    * Session markers
    *
    **********************************************/

/**
 * Returns the value for a $GLOBALS marker
 *
 * @param    string        $arr_tsConf: The current TypoScript configuration
 * @param    array        $elements: Array with the element session
 * @return    string        The value from the TSFE array
 * 
 * @version   4.4.0
 */
  function session_marker($arr_tsConf, $elements)
  {

    $str_sess_key  = $arr_tsConf['session.']['key'];  // i.e: ses
    $str_sess_name = $arr_tsConf['session.']['name']; // i.e: wt_cart_cart
    $arr_session   = $GLOBALS['TSFE']->fe_user->getKey($str_sess_key, $str_sess_name); // get already exting products from session

      // RETURN default value, if we don't have any session
    if(!is_array($arr_session) || count($arr_session) < 1)
    {
      $elements['session'] = $arr_tsConf['session.']['getDefault'];  // i.e: 1
      return $elements;
    }
      // RETURN default value, if we don't have any session


      // #44316, 130104, dwildt, 1-
//    $arr_tsConf = $this->substitute_marker_recurs($arr_tsConf, $elements);
      // #44316, 130104, dwildt, 4+
    $currElements         = $this->pObj->elements;
    $this->pObj->elements = $this->pObj->elements + $elements;
    $arr_tsConf           = $this->pObj->objMarker->substitute_tablefield_marker( $arr_tsConf );
    $this->pObj->elements = $currElements;

    $str_keyElement   = $arr_tsConf['session.']['whereElement.']['key'];   // i.e: uid
    $str_valueElement = $arr_tsConf['session.']['whereElement.']['value']; // i.e: ###SHOWUID###

      // One loop for every item
    foreach ($arr_session as $key => $value)
    {
      if ($arr_session[$key][$str_keyElement] == $str_valueElement)
      {
        $elements['session'] = $arr_session[$key][$arr_tsConf['session.']['getFrom']];
        break;
      }
    }

    if(!isset($elements['session']))
    {
      $elements['session'] = $arr_tsConf['session.']['getDefault'];
    }

    return $elements;
  }









    /***********************************************
    *
    * Markers
    *
    **********************************************/









  /**
 * substitute_tablefield_marker():  Replace database markers:
 *                                  Replace all markers in the given multidimensional array like the TypoScript
 *                                  configuration with the real values from the SQL result (with table.field values)
 *                                  * The method extends the SQL result with all piVar values. ###CHASH### has a process.
 *                                  * This method should supersede the deprecated method substitute_marker_recursive ()
 *
 * @param    array        $arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param    array        $elements: The current row of the SQL result
 * @return    array        $arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 * @version 3.7.0
 * @since   3.6.0
 */
  function substitute_tablefield_marker($arr_multi_dimensional)
  {
    $elements = $this->pObj->elements;



      /////////////////////////////////////
      //
      // RETURN there isn't any element

    if(empty($elements))
    {
      return $arr_multi_dimensional;
    }
      // RETURN there isn't any element



      /////////////////////////////////////
      //
      // Get the children devider configuration

      // Get arr_children_to_devide as array
    $arr_children_to_devide = (array) $this->pObj->arr_children_to_devide;

    if($this->pObj->objTyposcript->str_sqlDeviderDisplay == false)
    {
      $this->pObj->objTyposcript->set_confSqlDevider();
    }
    if($this->pObj->objTyposcript->str_sqlDeviderWorkflow == false)
    {
      $this->pObj->objTyposcript->set_confSqlDevider();
    }
    $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider            = $str_sqlDeviderDisplay.$str_sqlDeviderWorkflow;
      // Get the children devider configuration



      /////////////////////////////////////
      //
      // Add to the $elements piVars and singlePid

      // Add to the $elements piVars
    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      // dwildt, 090620: If we have multiple selects, piVars can contain arrays
      // This array should be array of uids. We don't need any process for uids here.
      if (!is_array($value_pivar))
      {
          // dwildt, 110320: Prevent to override database values in $elements by piVars
        if(!isset($elements[strtolower($key_pivar)]))
        {
          $elements[strtolower($key_pivar)] = $value_pivar;
        }
      }
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
      }
    }
      // Add to the $elements piVars

      // Add to the $elements the singlePid
    if (isset($this->pObj->singlePid))
    {
      $elements[strtolower('singlePid')] = $this->pObj->singlePid;
    }
      // Add to the $elements the singlePid
      // Add to the $elements piVars and singlePid



      /////////////////////////////////////
      //
      // Loop through all elements (real values)

      // One dimensional array of the tsConf markers
    $arr_one_dimensional = t3lib_BEfunc::implodeTSParams($arr_multi_dimensional);

      // Loop through one dimensional tsConf array
    foreach((array) $arr_one_dimensional as $key_tsConf => $value_tsConf)
    {
      $value_tsConf_after_loop  = $value_tsConf;

        // CONTINUE: there isn't any marker - go to the next tsConf element
      $int_countMarker = substr_count($value_tsConf, '###');  // I.e: 4
      if ($int_countMarker == 0)
      {
        if ($this->pObj->b_drs_marker)
        {
          $value_tsConf_html = htmlspecialchars($value_tsConf);
          if (strlen($value_tsConf_html) > $this->pObj->i_drs_max_sql_result_len)
          {
            $value_tsConf_html = substr($value_tsConf_html, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          t3lib_div::devlog('[INFO/MARKER] ... '.$value_tsConf.' hasn\'t any marker.', $this->pObj->extKey, 0);
        }
        continue;
      }
        // CONTINUE: there isn't any marker - go to the next tsConf element

        // Loop through all elements (real values)
      foreach((array) $elements as $key_tableField => $value_tableField)
      {
          // Replace constant marker with real value
        $key_marker         = '###'.strtoupper($key_tableField).'###';

          // session marker
//        if(in_array('session.', $key_tsConf))
//        {
//            // 110124, dwildt, :todo: session
//          $elements = $this->session_marker($value_tsConf_after_loop, $elements);
//        }
          // session marker


          // Value contains the current marker: handle children records
        if (!(strpos($value_tsConf_after_loop, $key_marker) === false))
        {
            // Marker has children values
          if(in_array($key_tableField, (array) $arr_children_to_devide))
          {
              // Get children values
            $arr_valuesChildren = explode($str_devider, $value_tableField);

              // Multiple the values and replace the marker for every child
              // EXAMPLE for value
              //   Before marker replacement: &tx_trevent_pi1[uid]=###FE_USERS.UID###&###CHASH###
              //   After  marker replacement: &tx_trevent_pi1[uid]=158&###CHASH###, ;|;&tx_trevent_pi1[uid]=155&###CHASH###
            $arr_value_after_loop = null;
            foreach((array) $arr_valuesChildren as $keyChild => $valueChild)
            {
              $arr_value_after_loop[] = str_replace($key_marker, $valueChild, $value_tsConf_after_loop);
            }
              // 13008, 110302, dwildt
              // 13807, 110313, dwildt
            $value_tsConf_after_loop = implode($str_sqlDeviderDisplay, (array) $arr_value_after_loop);
              // Multiple the values and replace the marker for every child
          }
            // Marker has children values

            // Marker hasn't any child value
          if(!in_array($key_tsConf, (array) $arr_children_to_devide))
          {
              // Color swords
            $value_tableField        = $this->pObj->objZz->color_swords($key_tableField, $value_tableField);
            $value_tsConf_after_loop = str_replace($key_marker, $value_tableField, $value_tsConf_after_loop);
          }
            // Marker hasn't any child value
        }
          // Value contains the current marker: handle children records

      }
        // Loop through all elements (real values)

        // Replace cHash marker
      $pos = strpos($value_tsConf_after_loop, '&###CHASH###');
      if (!($pos === false)) {
        $str_path                 = str_replace('&###CHASH###', '', $value_tsConf_after_loop);
        $arr_url                  = parse_url($str_path);
        $cHash_md5                = $this->pObj->objZz->get_cHash($arr_url['path']);
        $value_tsConf_after_loop  = str_replace('&###CHASH###', '&cHash='.$cHash_md5, $value_tsConf_after_loop);
      }
        // Replace cHash marker

        // Clear markers, which aren't replaced
      if($this->pObj->objZz->bool_advanced_3_6_0_rmMarker)
      {
        $value_tsConf_after_loop = preg_replace('|###.*?###|i', '', $value_tsConf_after_loop);
      }

        // DRS - Development Reporting System
      if ($value_tsConf_after_loop != $value_tsConf)
      {
        if ($this->pObj->b_drs_marker)
        {
          $value_tsConf_html = htmlspecialchars($value_tsConf_after_loop);
          if (strlen($value_tsConf_html) > $this->pObj->i_drs_max_sql_result_len)
          {
            $value_tsConf_html = substr($value_tsConf_html, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          if(empty($value_tsConf_html))
          {
            t3lib_div::devlog('[INFO/MARKER] ... ['.$key_marker.']: '.$value_tsConf.' is EMPTY.', $this->pObj->extKey, 0);
          }
          if(!empty($value_tsConf_html))
          {
            t3lib_div::devlog('[INFO/MARKER] ... ['.$key_marker.']: '.$value_tsConf.' become:<br /><br />'.$value_tsConf_html, $this->pObj->extKey, 0);
          }
        }
      }
        // DRS - Development Reporting System

      $arr_one_dimensional[$key_tsConf] = $value_tsConf_after_loop;
    }
      // Loop through one dimensional tsConf array

      // Rebuild $arr_multi_dimensional
    unset($arr_multi_dimensional);
    $arr_multi_dimensional = $this->pObj->objTyposcript->oneDim_to_tree($arr_one_dimensional);
      // #12472, 110124, dwildt
    return $arr_multi_dimensional;
  }









  /**
 * substitute_marker():  Replace markers:
 *                       Replace all markers in the given multidimensional array like the TypoScript configuration
 *                       with the values from the given marker array
 *                                  * The method extends the SQL result with all piVar values. ###CHASH### has a process.
 *                                  * This method should supersede the deprecated method substitute_marker_recursive ()
 *
 * @param    array        $arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param    array        $elements: The current row of the SQL result
 * @return    array        $arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 * @version 3.7.0
 * @since   3.6.0
 */
  function substitute_marker($arr_multi_dimensional, $marker)
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any element

    if(empty($marker))
    {
      return $arr_multi_dimensional;
    }
      // RETURN there isn't any element



      //////////////////////////////////////////////////////////////////////////
      //
      // Add to the $marker piVars and singlePid

      // Add to the $marker piVars
    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      // dwildt, 090620: If we have multiple selects, piVars can contain arrays
      // This array should be array of uids. We don't need any process for uids here.
      if (!is_array($value_pivar))
      {
          // dwildt, 110320: Prevent to override database values in $marker by piVars
        if(!isset($marker['###' . strtoupper($key_pivar) . '###']))
        {
          $marker['###' . strtoupper($key_pivar) . '###'] = $value_pivar;
        }
      }
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
      }
    }
      // Add to the $marker piVars

      // Add to the $marker the singlePid
    if (isset($this->pObj->singlePid))
    {
      $marker['###' . strtoupper('singlePid') . '###'] = $this->pObj->singlePid;
    }
      // Add to the $marker the singlePid
      // Add to the $marker piVars and singlePid



      //////////////////////////////////////////////////////////////////////////
      //
      // Loop through one dimensional array

    $arr_one_dimensional = t3lib_BEfunc::implodeTSParams($arr_multi_dimensional);
    foreach((array) $arr_one_dimensional as $key_tsConf => $value_tsConf)
    {
      $value_tsConf_after_loop  = $value_tsConf;

        // CONTINUE: array element dosn't contain any marker
      $int_countMarker = substr_count($value_tsConf, '###');  // I.e: 4
      if ($int_countMarker == 0)
      {
        if ($this->pObj->b_drs_marker)
        {
          $value_tsConf_html = htmlspecialchars($value_tsConf);
          if (strlen($value_tsConf_html) > $this->pObj->i_drs_max_sql_result_len)
          {
            $value_tsConf_html = substr($value_tsConf_html, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          t3lib_div::devlog('[INFO/MARKER] ... '.$value_tsConf.' hasn\'t any marker.', $this->pObj->extKey, 0);
        }
        continue;
      }
        // CONTINUE: array element dosn't contain any marker

        // Replace markers
      foreach((array) $marker as $key_marker => $value_marker)
      {
        $value_tsConf_after_loop = str_replace($key_marker, $value_marker, $value_tsConf_after_loop);
      }
        // Replace markers

        // Replace cHash marker
      $pos = strpos($value_tsConf_after_loop, '&###CHASH###');
      if (!($pos === false)) {
        $str_path                 = str_replace('&###CHASH###', '', $value_tsConf_after_loop);
        $arr_url                  = parse_url($str_path);
        $cHash_md5                = $this->pObj->objZz->get_cHash($arr_url['path']);
        $value_tsConf_after_loop  = str_replace('&###CHASH###', '&cHash='.$cHash_md5, $value_tsConf_after_loop);
      }
        // Replace cHash marker

        // Clear markers, which aren't replaced
      $value_tsConf_after_loop = preg_replace('|###.*?###|i', '', $value_tsConf_after_loop);

        // DRS - Development Reporting System
      if ($value_tsConf_after_loop != $value_tsConf)
      {
        if ($this->pObj->b_drs_marker)
        {
          $value_tsConf_html = htmlspecialchars($value_tsConf_after_loop);
          if (strlen($value_tsConf_html) > $this->pObj->i_drs_max_sql_result_len)
          {
            $value_tsConf_html = substr($value_tsConf_html, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
          }
          if(empty($value_tsConf_html))
          {
            t3lib_div::devlog('[INFO/MARKER] ... ['.$key_marker.']: '.$value_tsConf.' is EMPTY.', $this->pObj->extKey, 0);
          }
          if(!empty($value_tsConf_html))
          {
            t3lib_div::devlog('[INFO/MARKER] ... ['.$key_marker.']: '.$value_tsConf.' become:<br /><br />'.$value_tsConf_html, $this->pObj->extKey, 0);
          }
        }
      }
        // DRS - Development Reporting System

      $arr_one_dimensional[$key_tsConf] = $value_tsConf_after_loop;
    }
      // Loop through one dimensional array



      //////////////////////////////////////////////////////////////////////////
      //
      // Rebuild multidimensional array

    unset($arr_multi_dimensional);
    $arr_multi_dimensional = $this->pObj->objTyposcript->oneDim_to_tree($arr_one_dimensional);
      // Rebuild multidimensional array



      // RETURN multidimensional array
    return $arr_multi_dimensional;
  }









  /**
 * [DEPRECATED] Use substitute_tablefield_marker( )
 *
 * substitute_marker_recurs(): Replace all markers in a multi-dimensional array like an TypoScript array with the real values from the SQL result
 * The method extends the SQL result with all piVar values. ###CHASH### has a process.
 *
 * @param    array        $arr_multi_dimensional: Multi-dimensional array like an TypoScript array
 * @param    array        $elements: The current row of the SQL result
 * @return    array        $arr_multi_dimensional: The current Multi-dimensional array with substituted markers
 * @version 4.4.0
 */
  function substitute_marker_recurs($arr_multi_dimensional, $elements)
  {

      // 110312, dwildt
    //return $this->substitute_tablefield_marker($arr_multi_dimensional);

    $conf       = $this->pObj->conf;
    $conf_view  = $this->pObj->conf['views.'][$this->pObj->view.'.'][$this->pObj->piVar_mode.'.'];



      /////////////////////////////////////
      //
      // Get arr_children_to_devide as array

//var_dump(__METHOD__ . ': ' . __LINE__, $this->pObj->arr_children_to_devide);
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
        // 4.1.25, 121025, dwildt, 2+
      $debugTrailLevel = 1;
      $debugTrail_01 = $this->pObj->drs_debugTrail( $debugTrailLevel );
      $debugTrailLevel = 2;
      $debugTrail_02 = $this->pObj->drs_debugTrail( $debugTrailLevel );
      $debugTrailLevel = 3;
      $debugTrail_03 = $this->pObj->drs_debugTrail( $debugTrailLevel );
      
      if ($this->pObj->b_drs_error)
      {
        $prompt = 'Recursion is bigger than ' . $int_levelRecursMax;
        t3lib_div::devlog('[ERROR] ' . $prompt, $this->pObj->extKey, 3);
        $prompt = 'Called from ' . $debugTrail_01['prompt'] . ' -> ' . $debugTrail_02['prompt'] . ' -> ' . $debugTrail_03['prompt'];
        t3lib_div::devlog('[INFO] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'If it is ok, please increase advanced.recursionGuard.';
        t3lib_div::devlog('[HELP] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'EXIT';
        t3lib_div::devlog('[ERROR] ' . $prompt, $this->pObj->extKey, 3);
      }
        // 12310, dwildt, 110310
      $prompt = '<h1>Recursion Guard</h1>
        <p>
          Recursion is bigger than '.$int_levelRecursMax.'<br />
          If it is ok, please increase advanced.recursionGuard.<br />
          Did you miss to include the browser template in the page template?<br />
          Method: ' . __METHOD__ . ' (line: ' . __LINE__ . ')
        </p>
        <p>
          Called from ' . $debugTrail_01['prompt'] . ' -> ' . $debugTrail_02['prompt'] . ' -> ' . $debugTrail_03['prompt'] . '
        </p>
        <p style="border:1em solid red;color:red;font-weight:bold;padding: 2em;text-align:center;">
          The method: ' . __METHOD__ . ' is deprecated.<br />
          Please use substitute_tablefield_marker( )
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
      // Get the workflow devider for children values
    $str_sqlDeviderDisplay  = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider            = $str_sqlDeviderDisplay.$str_sqlDeviderWorkflow;
//var_dump(__METHOD__ . ': ' . __LINE__, $str_devider);
      // Get the workflow devider for children values
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

    foreach((array) $arr_multi_dimensional as $key_tsConf => $value_tsConf)
    {
        // 100709, fsander
        // if(is_array(array_keys($value_tsConf)))
      if(is_array($value_tsConf) && is_array(array_keys($value_tsConf)))
      {
        if(in_array('session.', array_keys($value_tsConf)))
        {
          $elements = $this->session_marker($value_tsConf, $elements);
        }
      }

      if (is_array($value_tsConf))
      {
          // Loop through the next level of the multi-dimensional array (recursive)
        $arr_multi_dimensional[$key_tsConf] = $this->substitute_marker_recurs($value_tsConf, $elements);
      }


        /////////////////////////////////////
        //
        // Replace markers with the values

      if(!is_array($value_tsConf))
      {
          // Do we have markers?
        $b_marker = true;
        $i_marker = substr_count($value_tsConf, '###');  // I.e: 4
        if ($i_marker == 0)
        {
            // There isn't any '###'
          $b_marker = false;
        }
          // Do we have markers?

        if ($b_marker)
        {
          $value_tsConf_after_loop  = $value_tsConf;
          $b_marker_changed         = false;

            // Loop: Replace all used markers, if they have a real value
          foreach((array) $elements as $key_tableField => $value_tableField)
          {
            $bool_marker        = false;
            $value_tsConf_curr  = $value_tsConf_after_loop;
            $key_marker         = '###'.strtoupper($key_tableField).'###';

              // Value has the current marker
            if (!(strpos($value_tsConf_curr, $key_marker) === false))
            {
                // Marker has children values
              if(in_array($key_tableField, $arr_children_to_devide))
              {
//var_dump(__METHOD__ . ': ' . __LINE__);
                  // Get children values
                $arr_valuesChildren   = explode($str_devider, $value_tableField);

                  // Multiple the values and replace the marker for every child
                  // EXAMPLE for value
                  //   Before marker replacement: &tx_trevent_pi1[uid]=###FE_USERS.UID###&###CHASH###
                  //   After  marker replacement: &tx_trevent_pi1[uid]=158&###CHASH###, ;|;&tx_trevent_pi1[uid]=155&###CHASH###
                $arr_lConfCObj = array();
                foreach((array) $arr_valuesChildren as $keyChild => $valueChild)
                {
                  $arr_value_after_loop[] = str_replace($key_marker, $valueChild, $value_tsConf_curr);
                }
                  // 13008, 110302, dwildt
                  // 13807, 110313, dwildt
                $value_tsConf_after_loop = implode($str_devider, $arr_value_after_loop);
                  // Multiple the values and replace the marker for every child
              }
                // Marker has children values

                // Marker hasn't any child value
              if(!in_array($key_tableField, $arr_children_to_devide))
              {
                $value_tableField        = $this->pObj->objZz->color_swords($key_tableField, $value_tableField);
                  // 3.3.4
                  //$value_tsConf_after_loop = str_replace($key_marker, $value_tableField, $value_tsConf_after_loop);
                $value_tsConf_after_loop = str_replace($key_marker, $value_tableField, $value_tsConf_curr);
              }
                // Marker hasn't any child value
            }
              // Value has the current marker

              // Set boolean for workflow
            if ($value_tsConf_curr != $value_tsConf_after_loop)
            {
                //if(t3lib_div::_GP('dev')) var_dump('zz 1375', $key_tableField, $value_tsConf_curr, $value_tsConf_after_loop);
              $bool_marker = true;
            }
              // Set boolean for workflow

            $str_elements1        = htmlspecialchars($value_tableField);
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
                  t3lib_div::devlog('[INFO/TT_CONTAINER] ... ['.$key_tsConf.']: '.$key_marker.' is NULL.', $this->pObj->extKey, 0);
                }
                else
                {
                  t3lib_div::devlog('[INFO/TT_CONTAINER] ... ['.$key_tsConf.']: '.$key_marker.' -> '.$str_elements1, $this->pObj->extKey, 0);
                }
              }
            }
          }
            // Loop: Replace all used markers, if they have a real value

            // Do we have a cHash marker?
          $pos = strpos($value_tsConf_after_loop, '&###CHASH###');
          if (!($pos === false)) {
            $str_path                 = str_replace('&###CHASH###', '', $value_tsConf_after_loop);
            $arr_url                  = parse_url($str_path);
            $cHash_md5                = $this->pObj->objZz->get_cHash($arr_url['path']);
            $value_tsConf_after_loop  = str_replace('&###CHASH###', '&cHash='.$cHash_md5, $value_tsConf_after_loop);
          }
            // Do we have a cHash marker?

          if ($value_tsConf_after_loop != $value_tsConf)
          {
              // Value has changed
            $b_marker_changed = true;
            $value_tsConf = $value_tsConf_after_loop;
          }
          else
          {
            if ($this->pObj->b_drs_ttc)
            {
              t3lib_div::devlog('[INFO/TT_CONTAINER] ... ['.$key_tsConf.']: hasn\'t any marker.', $this->pObj->extKey, 0);
            }
          }



            /////////////////////////////////////
            //
            // Delete the markers, which weren't replaced in the multi-dimensional array

          if($this->pObj->objZz->bool_advanced_3_6_0_rmMarker)
          {
            $arr_value            = array($value_tsConf);
            $arr_markers_in_value = $this->pObj->objTTContainer->get_marker_keys_recursive($arr_value);
            if (is_array($arr_markers_in_value))
            {
              if (count($arr_markers_in_value) >= 1)
              {
                  // There is one non replaced marker at least
                foreach ($arr_markers_in_value as $key_m_i_value => $value_m_i_value)
                {
                  $value_tsConf = str_replace('###'.strtoupper($key_m_i_value).'###', '', $value_tsConf);
                }
              }
            }
          }
            // Delete the markers, which weren't replaced in the multi-dimensional array
        }
        $arr_multi_dimensional[$key_tsConf] = $value_tsConf;
      }
        // Replace markers with the values

    }
      // Loop through the current level of the multi-dimensional array

    return $arr_multi_dimensional;
  }








  /**
 * extend_marker_wi_cObjData: Extend the given marker array with key/values from cObj->data,
 *                            the data of the tt_content record of the browser plugin
 *
 * @param    array        $markerArray: Array with markers
 * @return    array        $markerArray: Array with markers extended
 * @version 3.7.0
 * @since 3.7.0
 */
  function extend_marker_wi_cObjData($markerArray)
  {

    /////////////////////////////////////
    //
    // Add to the marker array the piVars

    foreach ($this->pObj->cObj->data as $key_cObjData => $value_cObjData)
    {
      if(!empty($value_cObjData))
      {
        $markerArray['###TT_CONTENT.'.strtoupper($key_cObjData).'###'] = $value_cObjData;
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
            // Make serial array line-break-able
          $value_cObjData = str_replace(';', '; ', $value_cObjData);
          t3lib_div::devlog('[INFO/TEMPLATING] The cObjData ['.$key_cObjData.'] is available.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###TT_CONTENT.'.strtoupper($key_cObjData).'###, it will become '.$value_cObjData, $this->pObj->extKey, 1);
        }
      }
    }

    return $markerArray;
  }








  /**
 * extend_marker_wi_pivars: Extend the given marker array with key/values from the piVars
 *
 * @param    array        $markerArray: Array with markers
 * @return    array        $markerArray: Array with markers extended
 * @version 2.0.0
 * @since 2.0.0
 */
  function extend_marker_wi_pivars($markerArray)
  {

    /////////////////////////////////////
    //
    // Add to the marker array the piVars

    foreach ($this->pObj->piVars as $key_pivar => $value_pivar)
    {
      if(!empty($value_pivar))
      {
        $markerArray['###'.strtoupper($key_pivar).'###'] = $value_pivar;
        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] The piVar ['.$key_pivar.'] is available.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/TEMPLATING] If you use the marker ###'.strtoupper($key_pivar).'###, it will become '.$value_pivar, $this->pObj->extKey, 1);
        }
      }
    }

    return $markerArray;
  }








  /**
 * replace_left_over(): Replace all markers, which are left over
 *                      Feature: #28657
 *
 * @param    array        $str_content: current content
 * @return    string        $str_content: rendered content
 * @version 3.7.0
 * @since 3.7.0
 */
  function replace_left_over($str_content)
  {

      // Get configuration from the flexform
      // Bugfix     #29738, uherrmann, 110913
    $sheet = 'evaluate';
    $field = 'handle_marker';
    $arr_piFlexform = $this->pObj->cObj->data['pi_flexform'];
    $handle_marker  = $this->pObj->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');

      // Switch configuration
    switch($handle_marker)
    {
      case('remove_empty_markers'):
      case(''):
      case(null):
          // DRS - Development Reporting System
        if ($this->pObj->b_drs_templating)
        {
          preg_match_all
          (
            '|###.*?###|i',
            $str_content,
            $arr_markers,
            PREG_PATTERN_ORDER
          );
          $str_markers = implode(', ', $arr_markers[0]);
          t3lib_div::devLog('[INFO/TEMPLATE] Markers will be removed: ' . $str_markers, $this->pObj->extKey, 0);
        }
          // DRS - Development Reporting System
          // Replace the left over markers
        $str_content = preg_replace('|###.*?###|i', '', $str_content);
        break;
      case('none'):
          // Do nothing;
        break;
      default:
        $prompt = '
                  <div style="background:white; font-weight:bold;border:.4em solid orange;">
                    <h1>
                      WARNING
                    </h1>
                    <p>
                      Flexform field has an invalid value. The value isn\'t defined.<br />
                      sheet: ' . $sheet . '<br />
                      field: ' . $field . '<br />
                      value: ' . $handle_marker . '<br />
                      ' . __METHOD__ . ' (' . __LINE__ . ')
                    </p>
                    <p>
                      Please save the plugin/flexform of the browser. The bug will be fixed probably.
                    </p>
                  </div>';
        echo $prompt;
    }
      // Switch configuration

    return $str_content;
  }









}









if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_marker.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_marker.php']);
}

?>