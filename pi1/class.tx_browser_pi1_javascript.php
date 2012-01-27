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
* The class tx_browser_pi1_javascript bundles methods for javascript and AJAX.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @version  3.9.3
* @since    3.5.0
*
* @package    TYPO3
* @subpackage    tx_browser
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   66: class tx_browser_pi1_javascript
 *   82:     function __construct($parentObj)
 *
 *              SECTION: CSS
 *  135:     function class_onchange($obj_ts, $arr_ts, $number_of_items)
 *  381:     function wrap_ajax_div($template)
 *
 *              SECTION: Files
 *  505:     function load_jQuery()
 *  615:     function addJssFile( $path, $name, $keyPathTs )
 *
 *              SECTION: Helper
 *  693:     function set_arrSegment()
 *  759:     public function addCssFiles()
 *  809:     public function addJssFiles()
 * 1063:     public function addFile($path, $ie_condition, $name, $keyPathTs, $str_type, $bool_inline )
 *
 *              SECTION: Dynamic methods
 * 1245:     function dyn_method_load_all_modes( )
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_javascript
{








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
    * CSS
    *
    **********************************************/




    /**
 * class_wi_ajax_onchange(): Set an HTML class for AJAX onload depending on
 *                           some circumstances
 *
 * @param	string		$obj_ts: The content object CHECKBOX, RADIOBUTTONS or SELECTBOX
 * @param	array		$arr_ts: The TypoScript configuration of the object
 * @param	string		$conf_item: The current item wrap
 * @param	integer		$number_of_items: The number of items
 * @param	string		$str_order: asc or desc
 * @return	string		Returns the wrapped item
 * @since 3.5.0
 * @version 3.5.0
 */
  function class_onchange($obj_ts, $arr_ts, $number_of_items)
  {
      // #9659, 101016, dwildt

      //////////////////////////////////////////////////////////////////////
      //
      // Get HTML part with AJAX onchange class

    switch($obj_ts)
    {
      case ('CATEGORY_MENU') :
        $conf_object = $arr_ts['wrap.']['item.']['class'];
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('jss 138', $conf_object);
        break;
      case ('CHECKBOX') :
      case ('RADIOBUTTONS') :
        $conf_object = $arr_ts['wrap'];
        break;
      case ('SELECTBOX') :
        $conf_object = $arr_ts['wrap.']['object'];
        break;
      default :
        if ($this->pObj->b_drs_error)
        {
          t3lib_div :: devlog('[ERROR/JSS] class_onchange - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
          t3lib_div :: devlog('[INFO/JSS] class_onchange won\'t be handled.', $this->pObj->extKey, 3);
        }
    }
      // Get HTML part with AJAX onchange class


      //////////////////////////////////////////////////////////////////////
      //
      // Get the class name and marker

      // Get the class name. Result should be something like onchange
    $class_onchange  = strtolower($this->pObj->conf['javascript.']['ajax.']['html.']['marker.']['ajax_onchange']);
      // Set the marker. Result should be something like ###ONCHANGE###
    $marker_onchange = '###'.strtoupper($class_onchange).'###';
      // DRS - Develoment Reporting System
    if ($this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/JSS] AJAX onchange marker is ###'.strtoupper($class_onchange).'###',
        $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/JSS] AJAX onchange class is '.$class_onchange,
        $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/JSS] Change it? Configure javascript.ajax.html.marker.ajax_onchange',
        $this->pObj->extKey, 1);
    }
      // DRS - Develoment Reporting System
      // Get the class name and marker



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN null, in case of disabled AJAX

    $bool_return = false;
    if (!$this->pObj->objFlexform->bool_ajax_enabled)
    {
      $bool_return = true;
    }
      // RETURN null, in case of disabled AJAX
      // RETURN null: use never AJAX auto reload
    if(!$arr_ts['ajax_onchange'])
    {
        // DRS - Develoment Reporting System
      if ($this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/JSS] '.$obj_ts.' shoudn\'t get an AJAX onload class.',
          $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/JSS] Change it? Configure ajax_onchange for '.$obj_ts.'.',
          $this->pObj->extKey, 1);
      }
      $bool_return = true;
    }
      // RETURN null: use never AJAX onchange
      // Allocates value to $arr_ts
    if($bool_return)
    {
      $conf_object = str_replace($marker_onchange, null, $conf_object);
        // Remove empty class
      $conf_object = str_replace(' class=""', null, $conf_object);
      switch($obj_ts)
      {
        case ('CATEGORY_MENU') :
          $arr_ts['wrap.']['item.']['class'] = $conf_object;
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('jss 215', $arr_ts['wrap.']['item.']['class']);
          break;
        case('CHECKBOX'):
        case('RADIOBUTTONS'):
          $arr_ts['wrap'] = $conf_object;
          break;
        case('SELECTBOX'):
          $arr_ts['wrap.']['object'] = $conf_object;
          break;
        default :
          if ($this->pObj->b_drs_error)
          {
            t3lib_div :: devlog('[ERROR/JSS] class_onchange - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
            t3lib_div :: devlog('[INFO/JSS] class_onchange won\'t be handled.', $this->pObj->extKey, 3);
          }
      }
      return $arr_ts;
    }
      // Allocates value to $arr_ts
      // RETURN null, in case of disabled AJAX



      // Handle class depending of Content Object
    //t3lib_div::devlog('[DEV] number_of_items \''.$number_of_items.'\'', $this->pObj->extKey, 0);
    switch($obj_ts)
    {
      case('CATEGORY_MENU'):
        $conf_object = str_replace($marker_onchange, $class_onchange, $conf_object);
        break;
      case('CHECKBOX'):
          // Reload by AJAX, if there is only one checkbox
        if($number_of_items < 2)
        {
          $conf_object = str_replace($marker_onchange, $class_onchange, $conf_object);
        }
          // Reload by AJAX, if there is only one checkbox
          // Don't reload by AJAX, if there is more than one checkbox
        if($number_of_items >= 2)
        {
          $conf_object = str_replace($marker_onchange, null, $conf_object);
            // DRS - Develoment Reporting System
          if ($this->pObj->b_drs_javascript)
          {
            t3lib_div::devlog('[INFO/JSS] '.$obj_ts.': Number of items are more than one: onchange class becomes null.',
              $this->pObj->extKey, 0);
          }
            // DRS - Develoment Reporting System
        }
          // Don't reload by AJAX, if there is more than one checkbox
        break;
      case('RADIOBUTTONS'):
          // Reload by AJAX
        $conf_object = str_replace($marker_onchange, $class_onchange, $conf_object);
        break;
      case('SELECTBOX'):
          // Reload by AJAX, if there is only one item in the selectbox
        if($number_of_items < 2)
        {
          $conf_object = str_replace($marker_onchange, $class_onchange, $conf_object);
        }
          // Reload by AJAX, if there is only one item in the selectbox
          // More than one iem in the selectbox
        if($number_of_items >= 2)
        {
            // Reload by AJAX, if there isn't any multiple select
          if($arr_ts['multiple'] == 0 || $arr_ts['size'] < 2)
          {
            $conf_object = str_replace($marker_onchange, $class_onchange, $conf_object);
          }
            // Reload by AJAX, if there isn't any multiple select
            // Don't reload by AJAX, because of the multiple select
          if($arr_ts['multiple'] == 1 && $arr_ts['size'] >= 2)
          {
              // DRS - Develoment Reporting System
            if ($this->pObj->b_drs_javascript)
            {
              t3lib_div::devlog('[INFO/JSS] Number of items are more than one, multiple is true and size is bigger than one: onchange class becomes null.',
                $this->pObj->extKey, 0);
            }
              // DRS - Develoment Reporting System
            $conf_object = str_replace($marker_onchange, null, $conf_object);
          }
            // Don't reload by AJAX, because of the multiple select
        }
          // More than one iem in the selectbox
        break;
      default :
        if ($this->pObj->b_drs_error)
        {
          t3lib_div :: devlog('[ERROR/JSS] class_onchange - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
          t3lib_div :: devlog('[INFO/JSS] class_onchange won\'t be handled.', $this->pObj->extKey, 3);
        }
    }
      // Handle class depending of Content Object

      // DRS - Develoment Reporting System
    if ($this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/JSS] AJAX onload class is: '.$class_onchange,
        $this->pObj->extKey, 0);
    }
      // DRS - Develoment Reporting System

      // Remove empty class
    $conf_object = str_replace(' class=""', null, $conf_object);
    switch($obj_ts)
    {
      case ('CATEGORY_MENU') :
        $arr_ts['wrap.']['item.']['class'] = $conf_object;
//$pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false)) var_dump('jss 326', $arr_ts['wrap.']['item.']['class']);
        break;
      case ('CHECKBOX'):
      case ('RADIOBUTTONS'):
        $arr_ts['wrap'] = $conf_object;
        break;
      case ('SELECTBOX'):
        $arr_ts['wrap.']['object'] = $conf_object;
        break;
      default :
        if ($this->pObj->b_drs_error)
        {
          t3lib_div :: devlog('[ERROR/JSS] class_onchange - undefined value in switch: \'' . $obj_ts . '\'', $this->pObj->extKey, 3);
          t3lib_div :: devlog('[INFO/JSS] class_onchange won\'t be handled.', $this->pObj->extKey, 3);
        }
    }
    return $arr_ts;
  }



















    /**
 * wrap_ajax_div(): Wrap the template in a div AJAX tag, if segement[cObj] is set
 *
 * @param	string		$template: The current content of the template
 * @return	string		$template unchanged or wrapped in div ajax tag
 * @since 3.5.0
 * @version 3.5.0
 */
  function wrap_ajax_div($template)
  {
    // #9659, 101016, dwildt



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN template: segment[cObj] is empty

    if (!$this->pObj->segment['cObj'])
    {
      return $template;
    }
      // RETURN template: segment[cObj] is empty



      //////////////////////////////////////////////////////////////////////
      //
      // Generate the AJAX class

    $str_ajax_class = 'ajax';
    if ($this->pObj->objFlexform->bool_ajax_single)
    {
       $str_ajax_class .= ' ajax_single';
    }
    if ($this->pObj->objFlexform->str_ajax_list_transition != 'none')
    {
       $str_ajax_class .= ' ajaxlt'.$this->pObj->objFlexform->str_ajax_list_transition;
    }
    if ($this->pObj->objFlexform->str_ajax_single_transition != 'none')
    {
       $str_ajax_class .= ' ajaxst'.$this->pObj->objFlexform->str_ajax_single_transition;
    }
    // #9659, 101013 fsander
    if ($this->pObj->objFlexform->str_ajax_list_on_single != 'listAndSingle')
    {
       $str_ajax_class .= ' ajaxlos'.$this->pObj->objFlexform->str_ajax_list_on_single;
    }
    // #9659, 101016, dwildt
    // #9659, 101017 fsander
    if ($this->pObj->bool_debugJSS)
    {
       $str_ajax_class .= ' debugjss';
    }
    // #9659, 101017 fsander
    $str_ajax_class .= ' dynamicFilters';
      // Generate the AJAX class
//:TODO:


      //////////////////////////////////////////////////////////////////////
      //
      // Wrap the template

    // #9659, 101013, dwildt
    $lang = $this->pObj->lang->lang;
    if($lang == 'default')
    {
      $lang = 'en';
    }
    $arr_wrap[0] = '<div class="'.$str_ajax_class.'" lang="'.$lang.'">';
    $arr_wrap[1] = '</div> <!-- /ajax -->';

    $template = '
      '.$arr_wrap[0].'
        '.$template.'
      '.$arr_wrap[1]."\n";
      // Wrap the template

    // #9659, 101013 fsander
      // DRS - Develoment Reporting System
    if ($this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/JSS] AJAX: template is wrapped with: '.$arr_wrap[0].'|'.$arr_wrap[1], $this->pObj->extKey, 0);
    }
      // DRS - Develoment Reporting System

    return $template;
  }



























    /***********************************************
    *
    * Files
    *
    **********************************************/



/**
 * load_jQuery(): Load the TYPO3 jQuery class and JSS file. If it is missed,
 *                load file from the browser ressources
 *
 * @return	boolean		True: success. False: error.
 * @since 3.5.0
 * @version 3.6.5
 */
  function load_jQuery()
  {
      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name = 'jQuery';
    if(isset ($GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name]))
    {
      return true;
    }

      // checks if t3jquery is loaded
    if (t3lib_extMgm::isLoaded('t3jquery'))
    {
      require_once(t3lib_extMgm::extPath('t3jquery').'class.tx_t3jquery.php');
    }
    if (!t3lib_extMgm::isLoaded('t3jquery'))
    {
      if ( $this->pObj->b_drs_javascript )
      {
        t3lib_div::devlog('[INFO/JSS] Extension t3jquery isn\'t loaded.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/JSS] We try to get another jQuery source.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/JSS] Change it? Load \'t3jquery\'', $this->pObj->extKey, 1);
      }
    }
      // if t3jquery is loaded and the custom library had been created
    if (T3JQUERY === true)
    {
      tx_t3jquery::addJqJS();
      if ( $this->pObj->b_drs_javascript )
      {
        t3lib_div::devlog('[INFO/JSS] Success: tx_t3jquery::addJqJS()', $this->pObj->extKey, 0);
      }
      return true;
    }



    $path         = $this->pObj->conf['javascript.']['jquery.']['library'];

      // #13429, dwildt, 110519
      // RETURN, there isn't any jQuery for embedding
    if(empty($path))
    {
        // Do nothing
      if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
      {
        if(empty($this->pObj->objFlexform->str_jquery_library))
        {
          t3lib_div::devlog('[INFO/FLEXFORM+JSS] Flexform Javascript|jquery_library is empty.', $this->pObj->extKey, 0);
        }
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] jQuery path is empty: jQuery isn\'t embedded.', $this->pObj->extKey, 0);
      }
      return true;
    }
      // RETURN, there isn't any jQuery for embedding
      // #13429, dwildt, 110519



      // if none of the previous is true, we need to include jQuery from external source
      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name         = 'jQuery';
    $path_tsConf  = 'javascript.jquery.file';
    $bool_success = $this->addJssFile($path, $name, $path_tsConf);
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      if($bool_success)
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] ' . $path . ' is embedded.', $this->pObj->extKey, 0);
      }
      if(!$bool_success)
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] ' . $path . ' is embedded.', $this->pObj->extKey, 0);
      }
    }
    if(!$bool_success)
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/FLEXFORM+JSS] ' . $path . ' couldn\'t embedded.', $this->pObj->extKey, 3);
      }
    }

    return $bool_success;
  }
















/**
 * addJssFile(): Add a JavaScript file the the HTML head
 *
 * @param	string		$path: Path to the Javascript
 * @param	string		$name: For the key of additionalHeaderData
 * @param	string		$keyPathTs: The TypoScript element path to $path for the DRS
 * @return	boolean		True: success. False: error.
 * @version 3.6.5
 * @since 3.5.0
 */
  function addJssFile( $path, $name, $keyPathTs )
  {
    if(isset ($GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name]))
    {
      return true;
    }

    if ( ! empty( $path ) )
    {
      if( substr( $path, 0, 4 ) == 'EXT:' )
      {
          // relative path to the JssFile as measured from the PATH_site (frontend)
          // #32220, uherrmann, 111202
        preg_match( '%^EXT:([a-z0-9_]*)/(.*)$%', $path, $matches );
        $path = t3lib_extMgm::siteRelPath($matches[1]) . $matches[2];
          // /#32220
      }
      $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] =
        '  <script src="'.$path.'" type="text/javascript"></script>';
      if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] file is included: '.$path, $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/FLEXFORM+JSS] Change it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
      }
      return true;
    }

      // #13429, dwildt, 110519
      // RETURN, there isn't any file for embedding
    if( empty( $this->pObj->objFlexform->str_browser_libraries ) )
    {
      if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] Flexform Javascript|browser_libraries is empty.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] Script isn\'t included. ', $this->pObj->extKey, 0);
      }
      return true;
    }
      // RETURN, there isn't any file for embedding
      // #13429, dwildt, 110519

    if ( $this->pObj->b_drs_error )
    {
      t3lib_div::devlog('[ERROR/JSS] script can not be included: '.$path, $this->pObj->extKey, 3);
      t3lib_div::devlog('[HELP/JSS] Solve it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
    }
    return false;
  }









    /***********************************************
    *
    * Helper
    *
    **********************************************/









/**
 * set_arrSegment(): Catch the segments to output for AJAX
 *
 * @return	void
 * @since 3.5.0
 * @version 3.5.0
 */
  function set_arrSegment()
  {
      // #9659, 101010 fsander
      // initialize all views
    $this->pObj->segment =  array
                            (
                              'header'        => true,
                              'searchform'    => true,
                              'azBrowser'     => true,
                              'list'          => true,
                              'pageBrowser'   => true,
                              'single'        => true,
                              'cObj'          => true,
                              'wrap_piBase'   => true,
                            );
      // initialize all views

      // Catch the segments to output for AJAX
      // switch off views, depending on piVar
    switch($this->pObj->piVars['segment'])
    {
      case('searchform'):
          // update the results with each change of the searchform, too
        $this->pObj->segment['header']      = false;
        $this->pObj->segment['cObj']        = false;
        $this->pObj->segment['wrap_piBase'] = false;
        break;
      case('list'):
        $this->pObj->segment['header']      = false;
        $this->pObj->segment['searchform']  = false;
        $this->pObj->segment['cObj']        = false;
        $this->pObj->segment['wrap_piBase'] = false;
        break;
      case('innerList'):
        $this->pObj->segment['header']      = false;
        $this->pObj->segment['searchform']  = false;
        $this->pObj->segment['azBrowser']   = false;
        $this->pObj->segment['pageBrowser'] = false;
        $this->pObj->segment['cObj']        = false;
        $this->pObj->segment['wrap_piBase'] = false;
        break;
      case('single'):
        $this->pObj->segment['header']      = false;
        $this->pObj->segment['cObj']        = false;
        $this->pObj->segment['wrap_piBase'] = false;
        break;
    }
      // switch off views, depending on piVar

  }









/**
 * addCssFiles(): Add all needed CSS files to the HTML head
 *
 * @return	void
 * @version 3.7.0
 * @since 3.7.0
 */
  public function addCssFiles()
  {
      //////////////////////////////////////////////////////////////////////
      //
      // css_browser

    if ($this->pObj->objFlexform->bool_css_browser)
    {
      $name         = 'css_browser';
      $path         = $this->pObj->conf['template.']['css.']['browser'];
      $bool_inline  = $this->pObj->conf['template.']['css.']['browser.']['inline'];
      $path_tsConf  = 'template.css.browser';

      $this->addFile($path, false, $name, $path_tsConf, 'css', $bool_inline);
    }
      // css_browser



      //////////////////////////////////////////////////////////////////////
      //
      // css_jquery_ui

    if ($this->pObj->objFlexform->bool_css_jqui)
    {
      $name         = 'css_jquery_ui';
      $path         = $this->pObj->conf['template.']['css.']['jquery_ui'];
      $bool_inline  = $this->pObj->conf['template.']['css.']['jquery_ui.']['inline'];
      $path_tsConf  = 'template.css.jquery_ui';

      $this->addFile($path, false, $name, $path_tsConf, 'css', $bool_inline);
    }
      // css_jquery_ui
  }









/**
 * addJssFiles(): Add all needed JavaScript files to the HTML head
 *
 * @return	void
 * @version 3.9.6
 * @since 3.7.0
 */
  public function addJssFiles()
  {
      //////////////////////////////////////////////////////////////////////
      //
      // jquery_ui

    if ($this->pObj->objFlexform->bool_jquery_ui)
    {
      $name         = 'jquery_ui_library';
      $path         = $this->pObj->conf['javascript.']['jquery.']['ui'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['ui.']['inline'];
      $path_tsConf  = 'javascript.jquery.ui.typoscript.library';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
    }
      // jquery_ui



      //////////////////////////////////////////////////////////////////////
      //
      // jquery_plugins_t3browser

    if ($this->pObj->objFlexform->bool_jquery_plugins_t3browser)
    {
      $name         = 'jquery_plugins_t3browser_plugin';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.t3browser.plugin';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

      $name         = 'jquery_plugins_t3browser_library';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.t3browser.library';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

      $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
      $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['marker.'];
      foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
      {
        if(substr($key_conf_marker, -1, 1) == '.')
        {
            // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
          $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
          $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
          $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
                                    (
                                      $conf_marker[$str_marker],
                                      $conf_marker[$str_marker . '.']
                                    );
          $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
        }
      }
      $load_all_modes = $this->dyn_method_load_all_modes( );
      $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
      $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
      $inline_jss     = str_replace( '###LOAD_ALL_MODES###', $load_all_modes,           $inline_jss );
      $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;

        // Include localised file, if current language isn't English
      if( $GLOBALS['TSFE']->lang != 'en' )
      {
        $name         = 'jquery_plugins_t3browser_localisation';
        $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation'];
        $path         = str_replace('###LANG###', $GLOBALS['TSFE']->lang, $path);
        $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation.']['inline'];
        $path_tsConf  = 'javascript.jquery.plugins.t3browser.localisation';
        $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
      }
        // Include localised file, if current language isn't English

    }
      // jquery_plugins_t3browser



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX (modul I) is enabled

      // dwildt, 111018: 31077
    //if ($this->objFlexform->bool_ajax_enabled)
    if ($this->pObj->objFlexform->bool_ajax_enabled)
    {
        // name has to correspondend with similar code in tx_browser_pi1_template.php
      $name         = 'ajaxLL';
      $path         = $this->pObj->conf['javascript.']['ajax.']['fileLL'];
      $bool_inline  = $this->pObj->conf['javascript.']['ajax.']['fileLL.']['inline'];
      $path_tsConf  = 'javascript.ajax.fileLL';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

        // name has to correspondend with similar code in tx_browser_pi1_template.php
      $name         = 'ajax';
      $path         = $this->pObj->conf['javascript.']['ajax.']['file'];
      $bool_inline  = $this->pObj->conf['javascript.']['ajax.']['file.']['inline'];
      $path_tsConf  = 'javascript.ajax.file';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
    }
      // AJAX (modul I) is enabled



      //////////////////////////////////////////////////////////////////////
      //
      // +Browser Calendar is loaded

    if ($this->pObj->objCal->is_loaded)
    {
      $arr_conf_pi5_jss = $this->pObj->conf['javascript.']['jquery.']['pi5.'];

      foreach( (array) $arr_conf_pi5_jss as $key_extension => $arr_properties)
      {
          // Take keys with a dot (i.e. 10.) only
        if( strpos ( $key_extension , '.' ) === false )
        {
          continue;
        }
        foreach( $arr_properties as $key_property => $value_property)
        {
            // Take keys with a dot (i.e. 10.) only
          if( strpos ( $key_property , '.' ) === false )
          {
            continue;
          }
          $name         = 'jquery_' . rtrim( $key_extension, '.' ) . '_' . rtrim( $key_property, '.' );
          $path         = $arr_properties[rtrim( $key_property, '.' )];
          $bool_inline  = $arr_properties[$key_property]['inline'];
          $path_tsConf  = 'javascript.jquery.pi5.' . $key_extension . rtrim( $key_property, '.' );
          $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

          $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
  //        $inline_jss = str_replace('###MODE###', $this->pObj->piVar_mode,  $inline_jss);
  //        $inline_jss = str_replace('###VIEW###', $this->pObj->view,        $inline_jss);
            // :TODO: move markerArray to class.tx_browser_pi1.php
          $markerArray = $this->pObj->objCal->markerArray;
          $inline_jss  = $this->pObj->cObj->substituteMarkerArray($inline_jss, $markerArray);
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
        }
      }
    }
      // +Browser Calendar is loaded



      //////////////////////////////////////////////////////////////////////
      //
      // jquery_plugins_jstree

      // There are tables with a treeparentfield
    if ( count( $this->pObj->objFilter->arr_tablesWiTreeparentfield ) >= 1 )
    {
      $name         = 'jquery_plugins_jstree_plugin';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.jstree.plugin';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

      $name         = 'jquery_plugins_jstree_plugins_cookie';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.jstree.plugins.cookie';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

      $name         = 'jquery_plugins_jstree_library';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library'];
      $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.jstree.library';
      $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

      $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
      $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['marker.'];
      foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
      {
        if(substr($key_conf_marker, -1, 1) == '.')
        {
            // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
          $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
          $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
          $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
                                    (
                                      $conf_marker[$str_marker],
                                      $conf_marker[$str_marker . '.']
                                    );
          $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
        }
      }
      $load_all_modes = $this->dyn_method_load_all_modes( );
      $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
      $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
      $inline_jss     = str_replace( '###LOAD_ALL_MODES###', $load_all_modes,           $inline_jss );
      $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;

    }
      // There are tables with a treeparentfield
      // jquery_plugins_jstree



      //////////////////////////////////////////////////////////////////////
      //
      // jquery_cleanup

    $name         = 'jquery_cleanup';
    $path         = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library'];
    $bool_inline  = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['inline'];
    $path_tsConf  = 'javascript.jquery.cleanup.library';
    $this->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);

    $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
    $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['marker.'];
    foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
    {
      if(substr($key_conf_marker, -1, 1) == '.')
      {
          // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
        $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
        $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
        $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
                                  (
                                    $conf_marker[$str_marker],
                                    $conf_marker[$str_marker . '.']
                                  );
        $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
      }
    }
    $load_all_modes = $this->dyn_method_load_all_modes( );
    $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
    $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
    $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
      // jquery_cleanup
  }










/**
 * addJssFile(): Add a JavaScript file the the HTML head
 *
 * @param	string		$path:          Path to the Javascript or CSS
 * @param	string		$ie_condition:  Optional condition for Internet Explorer
 * @param	string		$name:          For the key of additionalHeaderData
 * @param	string		$keyPathTs:     The TypoScript element path to $path for the DRS
 * @param	string		$str_type:      css or jss
 * @param	boolean		$bool_inline:   true: include css/jss inline. false: include it as a file
 * @return	boolean		True: success. False: error.
 * @since 3.7.0
 * @version 3.7.0
 */
  public function addFile($path, $ie_condition, $name, $keyPathTs, $str_type, $bool_inline )
  {
      // RETURN file is loaded
    if(isset ($GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name]))
    {
      if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/FLEXFORM+JSS] file isn\'t added again: '.$path, $this->pObj->extKey, 0);
      }
      return true;
    }
      // RETURN file is loaded



      // RETURN path is empty
    if( empty( $path ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/JSS] file can not be included. Path is empty. Maybe it is ok.', $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/JSS] Change it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
      }
      return false;
    }
      // RETURN path is empty



    $arr_parsed_url = parse_url($path);

      // URL or EXT:...
    if(isset($arr_parsed_url['scheme']))
    {
      if($arr_parsed_url['scheme'] == 'EXT')
      {
        unset($arr_parsed_url['scheme']);
      }
    }
      // URL or EXT:...

      // link to a file
    $bool_file_exists = true;
    if( ! isset( $arr_parsed_url['scheme'] ) )
    {
        // absolute path
      $absPath  = t3lib_div::getFileAbsFileName($path,$onlyRelative=1,$relToTYPO3_mainDir=0);
      if ( ! file_exists( $absPath ) )
      {
        $bool_file_exists = false;
      }
        // relative path
      $path = preg_replace('%' . PATH_site . '%', '', $absPath);
    }
      // link to a file



    if( ! $bool_file_exists )
    {
      if ( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog('[ERROR/JSS] script can not be included. File doesn\'t exist: '.$path, $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/JSS] Solve it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
      }
      return false;
    }



      // marker array
    $markerArray = array();
    $markerArray = $this->pObj->objMarker->extend_marker_wi_cObjData($markerArray);
    $markerArray = $this->pObj->objMarker->extend_marker_wi_pivars($markerArray);

      // switch: css || jss
    switch($str_type)
    {
      case('css'):
        if($bool_inline)
        {
          $inline_css =
'  <style type="text/css">
' . implode ('', file($absPath)) . '
  </style>';
          $inline_css = $this->pObj->cObj->substituteMarkerArray($inline_css, $markerArray);
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_css;
        }
        if(!$bool_inline)
        {
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] =
            '  <link rel="stylesheet" type="text/css" href="' . $path . '" media="all" />';
        }
        break;
      case('jss'):
        if($bool_inline)
        {
          $inline_jss =
'  <script type="text/javascript">
  <!--
' . implode ('', file($absPath)) . '
  //-->
  </script>';
          $inline_jss = $this->pObj->cObj->substituteMarkerArray($inline_jss, $markerArray);
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
        }
        if(!$bool_inline)
        {
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] =
            '  <script src="'.$path.'" type="text/javascript"></script>';
        }
        break;
      default:
        $prompt = '
          <div style="background:white; color:red; font-weight:bold;border:.4em solid red;">
            <h1>
              ERROR
            </h1>
            <p>
              Undefined value: ' . $str_type . '. Allowed are css and jss.
            </p>
            <p>
              ' . $this->pObj->extKey . ': '. __METHOD__ . ' (line ' . __LINE__ . ')
            </p>
          </div>';
        echo $prompt;
        exit;
        break;
    }
      // switch: css || jss

      // IE condition
    if($ie_condition)
    {
      $str_addHeaderData = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
      $str_addHeaderData = '  <![' . $ie_condition . ']>' . trim($str_addHeaderData) . '<![endif]>';
      $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $str_addHeaderData;
    }
      // IE condition

      // DRS
    if ($this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript)
    {
      t3lib_div::devlog('[INFO/FLEXFORM+JSS] file is included: '.$path, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/FLEXFORM+JSS] Change it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
    }
      // DRS

    return true;
      // path isn't empty

  }









    /***********************************************
    *
    * Dynamic methods
    *
    **********************************************/









/**
 * dyn_method_load_all_modes(): Return a script for background loading of each view
 *
 * @return	string		$js_complete: JSS skript
 * @since 3.9.3
 * @version 3.9.6
 */
  function dyn_method_load_all_modes( )
  {
    $conf       = $this->pObj->conf;
    $mode       = $this->pObj->piVar_mode;
    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $views      = $conf['views.'][$viewWiDot];

      // RETURN script is disabled by the plugin/flexform
    if( ! $this->pObj->objFlexform->sheet_viewList_rotateviews )
    {
      $js_complete = '  // Browser method dyn_method_load_all_modes( ): There isn\'t any loader set, ' .
                     'because script is disabled by the user in the plugin/flexform.';
      return $js_complete;
    }
      // RETURN script is disabled by the plugin/flexform

      // RETURN single view doesn't need the JSS script
    if( $view == 'single' )
    {
      $js_complete = '  // Browser method dyn_method_load_all_modes( ): There isn\'t any loader set, ' .
                     'because there current view is a single view.';
      return $js_complete;
    }
      // RETURN single view doesn't need the JSS script

      // RETURN: AJAX object I is enabled in list views
    if( $this->pObj->objFlexform->bool_ajax_enabled )
    {
      $js_complete = '  // Browser method dyn_method_load_all_modes( ): There isn\'t any loader set, ' .
                     'because AJAX object I is enabled for list views.';
      return $js_complete;
    }
      // RETURN: AJAX object I is enabled in list views

    $js_complete  = null;
    $tab          = null;
    $bool_first   = true;
    $js_snippet   = '' .
'###TAB###  setTimeout(function() {
  ###TAB###  load_mode( ###CURR_VIEW### );
  ###NEXT_VIEW###
  ###TAB###}, int_seconds );';

    foreach( $views as $key_viewWiDot => $arr_view)
    {
      if( strpos ( $key_viewWiDot , '.' ) === false )
      {
        continue;
      }
      if( $bool_first )
      {
        $bool_first = false;
        continue;
      }
      $key_viewWoDot = rtrim( $key_viewWiDot, '.' );
      switch( true )
      {
        case( empty( $js_complete ) ) :
          $js_complete = $js_snippet;
          break;
        default :
          $js_complete = str_replace( '###NEXT_VIEW###', $js_snippet, $js_complete );
      }
      $js_complete = str_replace( '###CURR_VIEW###',  $key_viewWoDot, $js_complete );
      $js_complete = str_replace( '###TAB###',        $tab,           $js_complete );
      $tab = $tab . '  ';
    }
    $js_complete = str_replace( '###NEXT_VIEW###', null, $js_complete );

    switch( true )
    {
      case( empty( $js_complete ) ):
        $js_complete = '  // Browser method dyn_method_load_all_modes( ): There isn\'t any loader set, because there is one view only.';
        break;
      default:
       $prompt_01   = '  // Code is set by the Browser method dyn_method_load_all_modes( ) - BEGIN';
       $prompt_02   = '    // Code is set by the Browser method dyn_method_load_all_modes( ) - END';
       $js_complete = $prompt_01 . '
' . $js_complete . '
' . $prompt_02 . '
';
       break;
    }

    return $js_complete;
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_javascript.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_javascript.php']);
}

?>
