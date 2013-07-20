<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* @version  4.5.11
* @since    3.5.0
*
* @package    TYPO3
* @subpackage  browser
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
 *  615:     function addJssFileToHead( $path, $name, $keyPathTs )
 *
 *              SECTION: Helper
 *  693:     function set_arrSegment()
 *  759:     public function addCssFiles()
 *  809:     public function addJssFiles()
 * 1063:     public function addCssFile($path, $ie_condition, $name, $keyPathTs, $str_type, $inline )
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
  * True, if jquery is loaded
  *
  * @var boolean
  */
  public $jqueryIsLoaded = null;

  /**
  * True, if t3jquery is included
  *
  * @var boolean
  */
  public $t3jqueryIsUsed = null;




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
 * @since 4.1.21
 * @version 3.5.0
 */
  public function class_onchange( $obj_ts, $arr_ts, $number_of_items )
  {
      // #9659, 101016, dwildt

      //////////////////////////////////////////////////////////////////////
      //
      // Get HTML part with AJAX onchange class

    switch($obj_ts)
    {
      case ('CATEGORY_MENU') :
        // #41753.01, 121010, dwildt, 1+
      case ('TREEVIEW') :   
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
        if ( $this->pObj->b_drs_error )
        {
          $prompt = 'class_onchange - undefined value in switch: \'';
          t3lib_div :: devlog( '[ERROR/JSS] ' . $prompt . $obj_ts . '\'', $this->pObj->extKey, 3 );
          $prompt = 'class_onchange won\'t be handled.';
          t3lib_div :: devlog( '[INFO/JSS] ' . $prompt , $this->pObj->extKey, 3 );
        }
        echo '<h1>Undefined value</h1>
          <h2>' . $obj_ts . ' is not defined</h2>
          <p>Method ' . __METHOD__ . ' (line: ' . __LINE__ . ')</p>  
          <p>Sorry, this error shouldn\'t occured!</p>  
          <p>Browser - TYPO3 without PHP</p>  
          ';
        exit;
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
          // #41753.01, 121010, dwildt, 1+
        case ('TREEVIEW') :   
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
        // #41753.01, 121010, dwildt, 1+
      case ('TREEVIEW') :   
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
        // #41753.01, 121010, dwildt, 1+
      case ('TREEVIEW') :   
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
 * @version 4.5.10
 * @since   3.6.5
 */
  public function load_jQuery( )
  {
      // RETURN : method is called before
    if( ! ( $this->jqueryIsLoaded === null ) )
    {
      if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        $prompt = 'RETURN: load_jQuery( ) is called before';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $this->jqueryIsLoaded;
    }
      // RETURN : method is called before
    
      // Set default
    $this->jqueryIsLoaded = false;

      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name = 'jQuery';

      // #50069, 130716, dwildt, -
//    if(isset ($GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name]))
//    {
//        // #44306, 130104, dwildt, 6+
//      $this->jqueryIsLoaded = true;
//      if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
//      {
//        $prompt = 'RETURN true: additionalHeaderData contains ' . $this->pObj->extKey . '_' . $name;
//        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
//      }
//      return true;
//    }
      // #50069, 130716, dwildt, -

      // #50069, 130716, dwildt, +
    switch( true )
    {
      case( isset( $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $this->pObj->extKey . '_' . $name] ) ):
        $this->jqueryIsLoaded = true;
        break;
      case( isset( $GLOBALS[ 'TSFE' ]->additionalFooterData[ $this->pObj->extKey . '_' . $name] ) ):
        $this->jqueryIsLoaded = true;
        break;
    }
      // RETURN : true, jQuery is loaded
    if( $this->jqueryIsLoaded )
    {
        // DRS
      if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        $prompt = 'RETURN true: additionalHeaderData contains ' . $this->pObj->extKey . '_' . $name;
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return true;
    }
      // RETURN : true, jQuery is loaded
      // #50069, 130716, dwildt, +
    
      // RETURN true  : t3jquery is loaded
    if( $this->load_t3jquery( ) )
    {
        // #44306, 130104, dwildt, 6+
      $this->jqueryIsLoaded = true;
      if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        $prompt = 'RETURN true: t3juery is loaded.';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
      // RETURN true  : t3jquery is loaded
    
    
    $path = $this->pObj->conf['javascript.']['jquery.']['library'];

      // #13429, dwildt, 110519
      // RETURN, there isn't any jQuery for embedding
    if( empty( $path ) )
    {
        // Do nothing
      if( $this->pObj->b_drs_error )
      {
        if( empty( $this->pObj->objFlexform->str_jquery_library ) )
        {
          $prompt = 'Flexform Javascript|jquery_library is empty.';
          t3lib_div::devlog( '[WARN/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 2 ) ;
        }
        $prompt = 'jQuery path is empty: jQuery isn\'t embedded.';
        t3lib_div::devlog( '[ERROR/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 3 );
      }
        // #44306, 130104, dwildt, 1+
      $this->jqueryIsLoaded = false;
        // #44306, 130104, dwildt, 1-
//      return true;
        // #44306, 130104, dwildt, 1+
      return false;
    }
      // RETURN, there isn't any jQuery for embedding
      // #13429, dwildt, 110519



      // if none of the previous is true, we need to include jQuery from external source
      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name         = 'jQuery';
    $path_tsConf  = 'javascript.jquery.file';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['library.']['marker.'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['library.']['inline'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
      // #50069, 130716, dwildt, 1-
//    $bool_success = $this->addJssFileToHead( $path, $name, $path_tsConf );
    
      // DRS
    switch( true )
    {
      case( $this->pObj->b_drs_error ):
        if( ! $bool_success )
        {
          $prompt = $path . ' couldn\'t embedded.';
          t3lib_div::devlog( '[ERROR/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 3 );
        }
        break;
      case( $this->pObj->b_drs_flexform ):
      case( $this->pObj->b_drs_javascript ):
        if( $bool_success )
        {
          $prompt = $path . ' is embedded.';
          t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
    }
      // DRS

      // #44306, 130104, dwildt, 12+
      // SWITCH $bool_success : Set $this->jqueryIsLoaded
    switch( $bool_success )
    {
      case( true ):
        $this->jqueryIsLoaded = true;
        break;
      case( false ):
      default:
        $this->jqueryIsLoaded = false;
        break;
    }
      // SWITCH $bool_success : Set $this->jqueryIsLoaded
      // #44306, 130104, dwildt, 12+

    return $bool_success;
  }



/**
 * load_t3jquery( ):
 *
 * @return  boolean		True: success. False: error.
 * @version 4.4.0
 * @since   4.4.0
 * @internal  #44299
 */
  private function load_t3jquery( )
  {
      // RETURN : method is called before
    if( ! ( $this->t3jqueryIsUsed === null ) )
    {
      return $this->t3jqueryIsUsed;
    }
      // RETURN : method is called before
    
      // Set default
    $this->t3jqueryIsUsed = false;

      // RETURN false : t3jquery isn't loaded
    if( ! t3lib_extMgm::isLoaded('t3jquery' ) )
    {
      if ( $this->pObj->b_drs_javascript )
      {
        $prompt = 'Extension t3jquery isn\'t loaded.';
        t3lib_div::devlog('[INFO/JSS] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'We try to get another jQuery source.';
        t3lib_div::devlog('[INFO/JSS] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'Change it? Load \'t3jquery\'';
        t3lib_div::devlog('[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1);
      }
      return false;
    }
      // RETURN false : t3jquery isn't loaded

      // RETURN false : current page is element of dontIntegrateOnUID
    $t3jqueryExtConf  = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3jquery'] );
    $arrPages         = $this->pObj->objZz->getCSVasArray( $t3jqueryExtConf['dontIntegrateOnUID'] );
    if( in_array( $GLOBALS['TSFE']->id, $arrPages ) )
    {
      if ( $this->pObj->b_drs_javascript )
      {
        $prompt = 'Current page (id ' . $GLOBALS['TSFE']->id . ') is an element of t3jquery.dontIntegrateOnUID.';
        t3lib_div::devlog('[INFO/JSS] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 't3jquery will not included. We try to get another jQuery source.';
        t3lib_div::devlog('[INFO/JSS] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'Change it? Change the values of dontIntegrateOnUID in the t3jquery extension manager. ';
        t3lib_div::devlog('[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1);
      }
      return false;
    }
      // RETURN false : current page is element of dontIntegrateOnUID


      // RETURN true : t3jquery is loaded and the custom library had been created
    require_once(t3lib_extMgm::extPath('t3jquery').'class.tx_t3jquery.php');
    if (T3JQUERY === true)
    {
      tx_t3jquery::addJqJS();
      if ( $this->pObj->b_drs_javascript )
      {
        $prompt = 'Success: tx_t3jquery::addJqJS()';
        t3lib_div::devlog( '[INFO/JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $this->t3jqueryIsUsed = true;
      return true;
    }
      // RETURN true : t3jquery is loaded and the custom library had been created
    
      // RETURN false : t3jquery isn't included
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
  public function set_arrSegment( )
  {
      // #9659, 101010 fsander
      // initialize all views
    $this->pObj->segment =  array
                            (
                              'header'        => true,
                              'searchform'    => true,
                              'indexBrowser'     => true,
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
        $this->pObj->segment['indexBrowser']   = false;
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
 * addCssFile(): 
 *
 * @param	string		$path:          Path to the Javascript or CSS
 * @param	string		$ie_condition:  Optional condition for Internet Explorer
 * @param	string		$name:          For the key of additionalHeaderData
 * @param	string		$keyPathTs:     The TypoScript element path to $path for the DRS
 * @param	string		$str_type:      css or jss
 * @param	boolean		$inline:   true: include css/jss inline. false: include it as a file
 * @return	boolean		True: success. False: error.
 * @since   4.5.11
 * @version 3.7.0
 */
  private function addCssFile($path, $ie_condition, $name, $keyPathTs, $str_type, $inline )
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

      // #50069, 130716, dwildt, -
//      // RETURN path is empty
//    if( empty( $path ) )
//    {
//      if ($this->pObj->b_drs_warn)
//      {
//        t3lib_div::devlog('[WARN/JSS] file can not be included. Path is empty. Maybe it is ok.', $this->pObj->extKey, 2);
//        t3lib_div::devlog('[HELP/JSS] Change it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
//      }
//      return false;
//    }
//      // RETURN path is empty
//
//
//    $arr_parsed_url = parse_url( $path );
//
//      // URL or EXT:...
//    if(isset($arr_parsed_url['scheme']))
//    {
//      if($arr_parsed_url['scheme'] == 'EXT')
//      {
//        unset($arr_parsed_url['scheme']);
//      }
//    }
//      // URL or EXT:...
//
//      // link to a file
//    $bool_file_exists = true;
//    if( ! isset( $arr_parsed_url['scheme'] ) )
//    {
//        // absolute path
//        // 130104, dwildt, 1-
////      $absPath  = t3lib_div::getFileAbsFileName($path,$onlyRelative=1,$relToTYPO3_mainDir=0);
//        // 130104, dwildt, 3+
//      $onlyRelative       = 1;
//      $relToTYPO3_mainDir = 0;
//      $absPath  = t3lib_div::getFileAbsFileName($path, $onlyRelative, $relToTYPO3_mainDir );
//      if ( ! file_exists( $absPath ) )
//      {
//        $bool_file_exists = false;
//      }
//        // #32220, uherrmann, 111202, 4-
////        // absolute path ./. root path
////      $rootPath = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT');
////        // relative path
////      $path     = substr($absPath, strlen($rootPath.'/'));
//        // #32220, uherrmann, 111202, 2+
//        // relative path
//      $path = preg_replace('%' . PATH_site . '%', '', $absPath);
//    }
//      // link to a file
//
//
//
//    if( ! $bool_file_exists )
//    {
//      if ( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog('[ERROR/JSS] script can not be included. File doesn\'t exist: '.$path, $this->pObj->extKey, 3);
//        t3lib_div::devlog('[HELP/JSS] Solve it? Configure: \''.$keyPathTs.'\'', $this->pObj->extKey, 1);
//      }
//      return false;
//    }
      // #50069, 130716, dwildt, -

      // #50069, 130716, dwildt, 5+
    $absPath = $this->getPathAbsolute( $path );
    if( $absPath == false )
    {
      return false;
    }
      // #50069, 130716, dwildt, 5+

      // #50222, 130720, dwildt, 5+
    $path = $this->getPathRelative( $path );
    if( $path == false )
    {
      return false;
    }
      // #50222, 130720, dwildt, 5+

      // marker array
    $markerArray = array();
    $markerArray = $this->pObj->objMarker->extend_marker_wi_cObjData( $markerArray );
    $markerArray = $this->pObj->objMarker->extend_marker_wi_pivars( $markerArray );

      // switch: css || jss
    switch($str_type)
    {
      case('css'):
        if($inline)
        {
          $inline_css =
'  <style type="text/css">
' . implode ('', file($absPath)) . '
  </style>';
          $inline_css = $this->pObj->cObj->substituteMarkerArray($inline_css, $markerArray);
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_css;
        }
        if(!$inline)
        {
          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] =
            '  <link rel="stylesheet" type="text/css" href="' . $path . '" media="all" />';
        }
        break;
          // #50069, 130716, dwildt, -
//      case( 'jss' ):
//          // #44306, 130104, dwildt, 1+
//        $this->load_jQuery( );
//        
//        $script = $this->getTagScript( $inline, $absPath, $path );
//        if( $inline )
//        {
//          $inline_jss =
//'  <script type="text/javascript">
//  <!--
//' . implode ( '', file( $absPath ) ) . '
//  //-->
//  </script>';
//          $inline_jss = $this->pObj->cObj->substituteMarkerArray($inline_jss, $markerArray);
//          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
//        }
//        if( ! $inline )
//        {
//          $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] =
//            '  <script src="'.$path.'" type="text/javascript"></script>';
//        }
//        break;
          // #50069, 130716, dwildt, -
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

/**
 * addCssFiles(): Add all needed CSS files to the HTML head
 *
 * @return	void
 * @version 4.5.10
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
        // #50069, dwildt, 1+
      $bool_footer  = false;
      $inline  = $this->pObj->conf['template.']['css.']['browser.']['inline'];
      $path_tsConf  = 'template.css.browser';

      $this->addCssFile( $path, false, $name, $path_tsConf, 'css', $inline );
    }
      // css_browser



      //////////////////////////////////////////////////////////////////////
      //
      // css_jquery_ui

    if ($this->pObj->objFlexform->bool_css_jqui)
    {
      $name         = 'css_jquery_ui';
      $path         = $this->pObj->conf['template.']['css.']['jquery_ui'];
        // #50069, dwildt, 1+
      $bool_footer  = false;
      $inline       = $this->pObj->conf['template.']['css.']['jquery_ui.']['inline'];
      $path_tsConf  = 'template.css.jquery_ui';

      $this->addCssFile( $path, false, $name, $path_tsConf, 'css', $inline );
    }
      // css_jquery_ui
  }









/**
 * addJssFiles(): Add all needed JavaScript files to the HTML head
 *
 * @return	void
 * @version 4.5.10
 * @since 3.7.0
 */
  public function addJssFiles()
  {
    $this->addJssFilesJqueryUiLibrary( );
    $this->addJssFilesJqueryPluginsT3Browser( );
    $this->addJssFilesJssAjaxModuleI( );
    $this->addJssFilesJqueryPluginsT3BrowserCalendar( );
    $this->addJssFilesJqueryPluginsJsTree( );
    $this->addJssFilesJqueryCleanUp( );
  }

/**
 * addJssFilesJqueryCleanUp( )
 *
 * @return	void
 * @version     4.5.10
 * @since       4.5.10
 */
  public function addJssFilesJqueryCleanUp( )
  {
    $name         = 'jquery_cleanup';
    $path         = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['inline'];
    $path_tsConf  = 'javascript.jquery.cleanup.library';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

      // #50069, 130716, dwildt, -
//    $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
//    $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['cleanup.']['library.']['marker.'];
//    foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
//    {
//      if(substr($key_conf_marker, -1, 1) == '.')
//      {
//          // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
//        $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
//        $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
//        $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
//                                  (
//                                    $conf_marker[$str_marker],
//                                    $conf_marker[$str_marker . '.']
//                                  );
//        $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
//      }
//    }
//    $load_all_modes = $this->dyn_method_load_all_modes( );
//    $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
//    $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
//    $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
      // #50069, 130716, dwildt, -
  }

/**
 * addJssFilesJqueryPluginsJsTree( )
 *
 * @return	void
 * @version   4.5.10
 * @since     4.5.10
 */
  public function addJssFilesJqueryPluginsJsTree( )
  {
      // There isn't any table with a treeparentfield
    if ( empty( $this->pObj->objFltr4x->arr_tablesWiTreeparentfield ) )
    {
      return;
    }
      // There isn't any table with a treeparentfield
    
      // There are tables with a treeparentfield

    $name         = 'jquery_plugins_jstree_plugin';
    $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.jstree.plugin';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugin.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

    $name         = 'jquery_plugins_jstree_plugins_cookie';
    $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.jstree.plugins.cookie';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['plugins.']['cookie.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

    $name         = 'jquery_plugins_jstree_library';
    $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.jstree.library';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

      // #50069, 130716, dwildt, -
//    $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
//    $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['jstree.']['library.']['marker.'];
//    foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
//    {
//      if(substr($key_conf_marker, -1, 1) == '.')
//      {
//          // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
//        $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
//        $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
//        $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
//                                  (
//                                    $conf_marker[$str_marker],
//                                    $conf_marker[$str_marker . '.']
//                                  );
//        $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
//      }
//    }
//    $load_all_modes = $this->dyn_method_load_all_modes( );
//    $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
//    $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
//    $inline_jss     = str_replace( '###LOAD_ALL_MODES###', $load_all_modes,           $inline_jss );
//    $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
      // #50069, 130716, dwildt, -

      // There are tables with a treeparentfield
      // jquery_plugins_jstree
  }
  
/**
 * addJssFilesJqueryPluginsT3Browser( )
 *
 * @return	void
 * @version     4.5.10
 * @since       4.5.10
 */
  public function addJssFilesJqueryPluginsT3Browser( )
  {
    if( ! $this->pObj->objFlexform->bool_jquery_plugins_t3browser )
    {
      return;
    }
    
    $name         = 'jquery_plugins_t3browser_plugin';
    $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.t3browser.plugin';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['plugin.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

    $name         = 'jquery_plugins_t3browser_library';
    $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['inline'];
    $path_tsConf  = 'javascript.jquery.plugins.t3browser.library';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

      // #50069, 130716, dwildt, -
//    $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
//    $conf_marker  = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['library.']['marker.'];
//    foreach((array) $conf_marker as $key_conf_marker => $arr_conf_marker)
//    {
//      if(substr($key_conf_marker, -1, 1) == '.')
//      {
//          // I.e. $key_conf_marker is 'title.', but we like the marker name without any dot
//        $str_marker             = substr($key_conf_marker, 0, strlen($key_conf_marker) -1);
//        $hashKeyMarker          = '###'.strtoupper($str_marker).'###';
//        $marker[$hashKeyMarker] = $this->pObj->cObj->cObjGetSingle
//                                  (
//                                    $conf_marker[$str_marker],
//                                    $conf_marker[$str_marker . '.']
//                                  );
//        $inline_jss = str_replace($hashKeyMarker, $marker[$hashKeyMarker], $inline_jss);
//      }
//    }
//    $load_all_modes = $this->dyn_method_load_all_modes( );
//    $inline_jss     = str_replace( '###MODE###',           $this->pObj->piVar_mode,   $inline_jss );
//    $inline_jss     = str_replace( '###VIEW###',           $this->pObj->view,         $inline_jss );
//    $inline_jss     = str_replace( '###LOAD_ALL_MODES###', $load_all_modes,           $inline_jss );
//    $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
      // #50069, 130716, dwildt, -

      // Include localised file, if current language isn't English
    if( $GLOBALS['TSFE']->lang != 'en' )
    {
      $name         = 'jquery_plugins_t3browser_localisation';
      $path         = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation'];
      $path         = str_replace('###LANG###', $GLOBALS['TSFE']->lang, $path);
      $inline       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation.']['inline'];
      $path_tsConf  = 'javascript.jquery.plugins.t3browser.localisation';
        // #50069, 130716, dwildt, 4+
      $marker       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation.']['marker.'];
      $footer       = $this->pObj->conf['javascript.']['jquery.']['plugins.']['t3browser.']['localisation.']['footer'];
      $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
      unset( $bool_success );
        // #50069, 130716, dwildt, 1-
      //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);
    }
      // Include localised file, if current language isn't English

  }

/**
 * addJssFilesJqueryPluginsT3BrowserCalendar( )
 *
 * @return	void
 * @version   4.5.10
 * @since     4.5.10
 */
  public function addJssFilesJqueryPluginsT3BrowserCalendar( )
  {
      // +Browser Calendar isn't loaded
    if( ! $this->pObj->objCal->is_loaded )
    {
      return;
    }
      // +Browser Calendar isn't loaded
    

      // +Browser Calendar is loaded
    
    $arr_conf_pi5_jss = $this->pObj->conf['javascript.']['jquery.']['pi5.'];

    foreach( (array) $arr_conf_pi5_jss as $key_extension => $arr_properties)
    {
        // Take keys with a dot (i.e. 10.) only
      if( strpos ( $key_extension , '.' ) === false )
      {
        continue;
      }
        // 130104, dwildt, 1-
      // foreach( $arr_properties as $key_property => $value_property )
        // 130104, dwildt, 1-
      foreach( array_keys( $arr_properties ) as $key_property )
      {
          // Take keys with a dot (i.e. 10.) only
        if( strpos ( $key_property , '.' ) === false )
        {
          continue;
        }
        $name         = 'jquery_' . rtrim( $key_extension, '.' ) . '_' . rtrim( $key_property, '.' );
        $path         = $arr_properties[rtrim( $key_property, '.' )];
        $inline       = $arr_properties[$key_property]['inline'];
        $path_tsConf  = 'javascript.jquery.pi5.' . $key_extension . rtrim( $key_property, '.' );
          // #50069, 130716, dwildt, 4+
        $marker       = $this->pObj->objCal->markerArray;
        $footer       = $arr_properties[$key_property]['footer'];
        $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
        unset( $bool_success );
          // #50069, 130716, dwildt, 1-
        //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

        // #50069, 130716, dwildt, -
//        $inline_jss   = $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name];
////        $inline_jss = str_replace('###MODE###', $this->pObj->piVar_mode,  $inline_jss);
////        $inline_jss = str_replace('###VIEW###', $this->pObj->view,        $inline_jss);
//          // :TODO: move markerArray to class.tx_browser_pi1.php
//        $markerArray = $this->pObj->objCal->markerArray;
//        $inline_jss  = $this->pObj->cObj->substituteMarkerArray($inline_jss, $markerArray);
//        $GLOBALS['TSFE']->additionalHeaderData[$this->pObj->extKey.'_'.$name] = $inline_jss;
        // #50069, 130716, dwildt, -
      }
    }
    // +Browser Calendar is loaded
  }
  
/**
 * addJssFilesJqueryUiLibrary( )
 *
 * @return	void
 * @version   4.5.10
 * @since     4.5.10
 */
  private function addJssFilesJqueryUiLibrary( )
  {
    if( ! $this->pObj->objFlexform->bool_jquery_ui )
    {
      return;
    }

    $name         = 'jquery_ui_library';
    $path         = $this->pObj->conf['javascript.']['jquery.']['ui'];
    $inline       = $this->pObj->conf['javascript.']['jquery.']['ui.']['inline'];
    $path_tsConf  = 'javascript.jquery.ui.typoscript.library';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['jquery.']['library.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['jquery.']['library.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);
  }
  
/**
 * addJssFilesJssAjaxModuleI( )
 *
 * @return	void
 * @version     4.5.10
 * @since       4.5.10
 */
  public function addJssFilesJssAjaxModuleI( )
  {
      // AJAX (modul I) is disabled
    if( ! $this->pObj->objFlexform->bool_ajax_enabled )
    {
      return;
    }
      // AJAX (modul I) is disabled
    
      // AJAX (modul I) is enabled
      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name         = 'ajaxLL';
    $path         = $this->pObj->conf['javascript.']['ajax.']['fileLL'];
    $inline       = $this->pObj->conf['javascript.']['ajax.']['fileLL.']['inline'];
    $path_tsConf  = 'javascript.ajax.fileLL';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['ajax.']['fileLL.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['ajax.']['fileLL.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);

      // name has to correspondend with similar code in tx_browser_pi1_template.php
    $name         = 'ajax';
    $path         = $this->pObj->conf['javascript.']['ajax.']['file'];
    $inline       = $this->pObj->conf['javascript.']['ajax.']['file.']['inline'];
    $path_tsConf  = 'javascript.ajax.file';
      // #50069, 130716, dwildt, 4+
    $marker       = $this->pObj->conf['javascript.']['ajax.']['file.']['marker.'];
    $footer       = $this->pObj->conf['javascript.']['ajax.']['file.']['footer'];
    $bool_success = $this->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    unset( $bool_success );
      // #50069, 130716, dwildt, 1-
    //$this->addCssFile($path, false, $name, $path_tsConf, 'jss', $inline);
    // AJAX (modul I) is enabled
  }


/**
 * addJssFilePromptError(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path: Path to the Javascript
 * @param	string		$name: For the key of additionalHeaderData
 * @param	string		$keyPathTs: The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @return	boolean		True: success. False: error.
 * @version   4.5.10
 * @since     4.5.10
 */
  private function addJssFilePromptError( $path, $keyPathTs )
  {
    if( empty( $this->pObj->objFlexform->str_browser_libraries ) )
    {
        // DRS
      if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
      {
        $prompt = 'Flexform Javascript|browser_libraries is empty.';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'Script isn\'t included.';
        t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
        // #44306, 130104, dwildt, 1+
      $this->load_jQuery( );
      return true;
    }
      // RETURN, there isn't any file for embedding
      // #13429, dwildt, 110519

      // DRS
    if ( $this->pObj->b_drs_error )
    {
      $prompt = 'Script can not be included: ' . $path;
      t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
      $prompt = 'Solve it? Configure: \''.$keyPathTs.'\'';
      t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

    return false;
  }

/**
 * addJssFileTo(): Add a JavaScript file to header or footer section
 *
 * @param	string		$path         : Path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$footer       : Add JSS script to the footer section
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * 
 * @internal    #50069
 * @version     4.5.10
 * @since       4.5.10
 */
  public function addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker )
  {
    $bool_success = false; 
    
      // #50069, 130716, dwildt, + 
      // Get absolute path
    $absPath = $this->getPathAbsolute( $path );
      // RETURN : there is an error with the absolute path
    if( empty( $absPath ) )
    {
      return false;
    }
      // RETURN : there is an error with the absolute path
      // #50069, 130716, dwildt, + 

      // #50069, 130716, dwildt, 5+ 
      // Get relative path without 'EXT:'
    $path = $this->getPathRelative( $path );
      // RETURN : there is an error with the relative path
    if( empty( $path ) )
    {
      return $this->addJssFilePromptError( );
    }
      // RETURN : there is an error with the relative path

    switch( true )
    {
      case( $footer === false ):
        $bool_success = $this->addJssFileToHead( $path, $absPath, $name, $path_tsConf, $inline, $marker );
        break;
      case( $footer == true ):
      default:
        $bool_success = $this->addJssFileToFooter( $path, $absPath, $name, $path_tsConf, $inline, $marker );
        break;
    }
    
    unset( $footer );

    return $bool_success;
  }

/**
 * addJssFileToHead(): Add a JavaScript file to the HTML head
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * @version 4.5.10
 * @since 3.5.0
 */
  private function addJssFileToHead( $path, $absPath, $name, $keyPathTs, $inline, $marker )
  {
      // RETURN : script is included
    if( isset( $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $this->pObj->extKey . '_' . $name ] ) )
    {
        // #44306, 130104, dwildt, 1+
      $this->load_jQuery( );
      return true;
    }
      // RETURN : script is included

      // #50069, 130716, dwildt, 3+ 
    $script = $this->getTagScript( $inline, $absPath, $path, $marker );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalHeaderData[ $key ] = $script;

      // DRS
    if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
    {
      $prompt = 'file is placed in the header section: ' . $path;
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change the path? Configure: \'' . $keyPathTs . '\'';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      $prompt = 'Change the section for all JSS files? Take the Constant Editor: [Browser - JAVASCRIPT]';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

      // #44306, 130104, dwildt, 1+
    $this->load_jQuery( );
    return true;
  }

  
/**
 * addJssFileToFooter(): Add a JavaScript file at the bottom of the page (the footer section)
 *
 * @param	string		$path         : relative path to the Javascript
 * @param	string		$absPath      : absolute path to the Javascript
 * @param	string		$name         : For the key of additionalHeaderData
 * @param	string		$keyPathTs    : The TypoScript element path to $path for the DRS
 * @param	boolean		$inline       : Add JSS script inline
 * @param	array		$marker       : marker array
 * @return	boolean		True: success. False: error.
 * 
 * @internal    #50069
 * @version     4.5.10
 * @since       4.5.10
 */
  private function addJssFileToFooter( $path, $absPath, $name, $keyPathTs, $inline, $marker )
  {
    if( isset( $GLOBALS[ 'TSFE' ]->additionalFooterData[ $this->pObj->extKey . '_' . $name ] ) )
    {
        // #44306, 130104, dwildt, 1+
      $this->load_jQuery( );
      return true;
    }


      // #50069, 130716, dwildt, 3+ 
    $script = $this->getTagScript( $inline, $absPath, $path, $marker );
    $key    = $this->pObj->extKey . '_' . $name;
    $GLOBALS[ 'TSFE' ]->additionalFooterData[ $key ] = $script;

      // DRS
    if( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
    {
      $prompt = 'file is placed in the footer section: ' . $path;
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change the path? Configure: \'' . $keyPathTs . '\'';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
      $prompt = 'Change the section for all JSS files? Take the Constant Editor: [Browser - JAVASCRIPT]';
      t3lib_div::devlog( '[HELP/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 1 );
    }
      // DRS

      // #44306, 130104, dwildt, 1+
    $this->load_jQuery( );
    return true;
  }


/**
 * getPathAbsolute( ): Returns the absolute path of the given path
 *
 * @param	string		$path : relative or absolute path to Javascript or CSS
 * @return	string		$path : absolute path or false in case of an error
 * 
 * @internal    #50069
 * @since       4.5.10
 * @version     4.5.10
 */
  private function getPathAbsolute( $path )
  {
      // RETURN path is empty
    if( empty( $path ) )
    {
        // DRS
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'file can not be included. Path is empty. Maybe it is ok.';
        t3lib_div::devlog( '[WARN/JSS] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Change it? Configure: \'' . $keyPathTs . '\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN path is empty

      // URL or EXT:...
    $arr_parsed_url = parse_url( $path );
    if( isset( $arr_parsed_url[ 'scheme' ] ) )
    {
      if( $arr_parsed_url[ 'scheme' ] == 'EXT' )
      {
        unset( $arr_parsed_url[ 'scheme' ] );
      }
    }
      // URL or EXT:...

      // link to a file
    $bool_file_exists = true;
    if( ! isset( $arr_parsed_url['scheme'] ) )
    {
      $onlyRelative       = 1;
      $relToTYPO3_mainDir = 0;
      $absPath  = t3lib_div::getFileAbsFileName( $path, $onlyRelative, $relToTYPO3_mainDir );
      if ( ! file_exists( $absPath ) )
      {
        $bool_file_exists = false;
      }
        // relative path
      $path = preg_replace('%' . PATH_site . '%', null, $absPath);
    }
      // link to a file


      // RETURN : false, file does not exist
    if( ! $bool_file_exists )
    {
        // DRS
      if ( $this->pObj->b_drs_error )
      {
        $prompt = 'Script can not be included. File doesn\'t exist: ' . $path;
        t3lib_div::devlog( '[ERROR/JSS] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'Solve it? Configure: \''.$keyPathTs.'\'';
        t3lib_div::devlog( '[HELP/JSS] ' . $prompt, $this->pObj->extKey, 1 );
      }
        // DRS
      return false;
    }
      // RETURN : false, file does not exist
    
    return $path;
  }

/**
 * getPathRelative( ): Returns the relative path. Prefix 'EXT:' will handled
 *
 * @param	string		$path : relative path with or without prefix 'EXT:'
 * @return	string		$path : relative path without prefix 'EXT:'
 * 
 * @internal    #50069
 * @since       4.5.10
 * @version     4.5.10
 */
  private function getPathRelative( $path )
  { 
      // RETURN : path hasn't any prefix EXT:
    if( substr( $path, 0, 4 ) != 'EXT:' )
    {
      return $path;
    }
      // RETURN : path hasn't any prefix EXT:
    
      // relative path to the JssFile as measured from the PATH_site (frontend)
      // #32220, uherrmann, 111202
    $matches  = array( );
    preg_match( '%^EXT:([a-z0-9_]*)/(.*)$%', $path, $matches );
    $path     = t3lib_extMgm::siteRelPath( $matches[ 1 ] ) . $matches[ 2 ];
      // /#32220

    return $path;
  }

/**
 * getTagScript( ): Returns a script tag
 *
 * @param	boolean		$inline       : include the javascript inline
 * @param	string		$absPath      : absPath to the Javascript
 * @param	string		$path         : path to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScript( $inline, $absPath, $path, $marker )
  {
    $script = null;
    
    switch( $inline )
    {
      case( true ):
        $script = $this->getTagScriptInline( $absPath, $marker );
        break;
      case( false ):
      default:
        $script = $this->getTagScriptSrc( $path );
        break;
    }
    
    return $script;
  }

/**
 * getTagScriptInline( ): Returns a script tag
 *
 * @param	string		$absPath      : absPath to the Javascript
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptInline( $absPath, $marker )
  {
    $script = 
'  <script type="text/javascript">
  <!--
' . implode ( null , file( $absPath ) ) . '
  //-->
  </script>';

    $script = $this->getTagScriptInlineMarker( $script, $marker );
    
    return $script;
  }

/**
 * getTagScriptInlineMarker( ): 
 *
 * @param	array		$marker       : marker array
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptInlineMarker( $script, $marker )
  {
    if( ! is_array( $marker ) )
    {
      unset( $marker );
      $marker = array( );
    }
    
    foreach( array_keys( ( array ) $marker ) as $key )
    {
      if( substr( $key, -1, 1 ) != '.' )
      {
        continue;
      }
        // I.e. $key is 'title.', but we like the marker name without any dot
      $keyWoDot         = substr( $key, 0, strlen( $key ) -1 );
      $hashKey          = '###' . strtoupper( $keyWoDot ) . '###';
      $coa              = $marker[ $keyWoDot ];
      $conf             = $marker[ $key ];
      $marker[$hashKey] = $this->pObj->cObj->cObjGetSingle( $coa, $conf );
    }
    
    $marker[ '###MODE###' ]           = $this->pObj->piVar_mode;
    $marker[ '###VIEW###' ]           = $this->pObj->view;
    $load_all_modes                   = $this->dyn_method_load_all_modes( );
    $marker[ '###LOAD_ALL_MODES###' ] = $load_all_modes;

    $marker = $this->pObj->objMarker->extend_marker_wi_cObjData( $marker );
    $marker = $this->pObj->objMarker->extend_marker_wi_pivars( $marker );
    
    $script = $this->pObj->cObj->substituteMarkerArray( $script, $marker );

    return $script;
  }

/**
 * getTagScriptSrc( ): Returns a script tag
 *
 * @param	string		$path         : path to the Javascript
 * @return	string		$script       : The script tag
 * 
 * @internal  #50069
 * @since     4.5.10
 * @version   4.5.10
 */
  private function getTagScriptSrc( $path )
  {
    $script = '  <script src="' . $path . '" type="text/javascript"></script>';

    return $script;
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
  private function dyn_method_load_all_modes( )
  {
    $conf       = $this->pObj->conf;
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

      // 130104, dwildt, 1-
//    foreach( $views as $key_viewWiDot => $arr_view)
      // 130104, dwildt, 1+
    foreach( array_keys( $views ) as $key_viewWiDot )
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

  
  
/**
 * wrap_ajax_div(): Wrap the template in a div AJAX tag, if segement[cObj] is set
 *
 * @param	string		$template: The current content of the template
 * @return	string		$template unchanged or wrapped in div ajax tag
 * @since 3.5.0
 * @version 3.5.0
 */
  public function wrap_ajax_div( $template )
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
    $arr_wrap = array( );
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








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_javascript.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_javascript.php']);
}

?>