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
 * @subpackage    browser
 * @version 3.7.0
 * @since 3.0.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   55: class tx_browser_pi1_backend
 *   85:     public function sDef_getArrViewsList($arr_pluginConf)
 *  232:     public function socialmedia_getArrBookmarks($arr_pluginConf)
 *  291:     public function templating_getArrDataQuery($arr_pluginConf)
 *  354:     public function templating_getExtensionTemplates($arr_pluginConf)
 *
 *              SECTION: Helper Methods
 *  426:     function getLL()
 *  455:     function init($arr_pluginConf)
 *  496:     function init_pageObj($arr_pluginConf)
 *  528:     function init_pageUid($arr_pluginConf)
 *  578:     function init_tsObj($arr_rows_of_all_pages_inRootLine)
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_backend
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
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the names of the views list
 * @version 3.6.1
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
                // #27358, uherrmann, 110610
            ##$arr_csvViews = explode(',', $csvViews);
              $arr_csvViews = t3lib_div::trimExplode(',', $csvViews);
                // #27358, uherrmann, 110610
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

      // #11981, 110106, dwildt
      // Remove any value, keep arrays
    foreach((array) $arr_listviews as $key => $view)
    {
      if(substr($key, -1, 1) != '.')
      {
        unset($arr_listviews[$key]);
      }
    }
      // #11981, 110106, dwildt

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
 * socialmedia_getArrBookmarks: Get bookmarks for flexform. Tab [Socialmedia]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the bookmarks
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
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the bookmarks
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











  /**
 * templating_getExtensionTemplates: Get templates from the browser and third party extensions
 * Tab [Templating]
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   $arr_pluginConf: Extended with the templates
 */
  public function templating_getExtensionTemplates($arr_pluginConf)
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
//var_dump($extensionWiDot, $arr_template['name'], $arr_template['file']);
        $label = $arr_template['name'].' ('.$extension.')';
        $value = $arr_template['file'];
        $arr_pluginConf['items'][] = array($label, $value);
      }
    }
      // Loop through all extensions and templates

    return $arr_pluginConf;

  }









  /**
 * templating_get_jquery_ui: Get the list of jquery uis for the flexform. Tab [Templating]
 *                            * Feature #28562
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  array   with the uis
 * 
 * @version 3.7.0
 * @since 3.7.0

 */
  public function templating_get_jquery_ui($arr_pluginConf)
  {
      // Require classes, init page id, page object and TypoScript object
    $bool_success = $this->init($arr_pluginConf);
    if(!$bool_success)
    {
      return $arr_pluginConf;
    }

      // TypoScript configuration for jquery_ui
    $arr_jquery_uis = $this->obj_TypoScript->setup['plugin.']['tx_browser_pi1.']['flexform.']['templating.']['jquery_ui.'];

      // Loop: jquery_ui
    foreach((array) $arr_jquery_uis as $key_jquery_ui => $arr_jquery_ui)
    {
      $jquery_ui_key    = strtolower(substr($key_jquery_ui, 0, -1));
      $jquery_ui_label  = $arr_jquery_ui['label'];
      $jquery_ui_icon   = $arr_jquery_ui['icon'];

      $arr_pluginConf['items'][] = array($jquery_ui_label, $jquery_ui_key, $jquery_ui_icon);
    }
      // Loop: jquery_ui

    return $arr_pluginConf;
  }













  /***********************************************
   *
   * Helper Methods
   *
   **********************************************/









  /**
 * getLL(): Get the locallang for class use out of an XML file
 *
 * @return  array   Array of the locallang data
 */
  function getLL()
  {
    $arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser']);
    switch($arr_extConf['LLstatic'])
    {
      case('German'):
        $lang = 'de';
        break;
      default:
        $lang = 'default';
    }
    require_once('flexform_locallang.php');
    var_dump($LOCAL_LANG[$lang]);
    
//    $path2llXml = t3lib_extMgm::extPath('browser').'pi1/locallang.xml';
//    $llXml      = implode('', file($path2llXml));
//    $arr_ll     = t3lib_div::xml2array($llXml, $NSprefix='', $reportDocTag=false);
//    $LOCAL_LANG = $arr_ll['data'];
//    return $LOCAL_LANG;
  }













  /**
 * init(): Initiate this class.
 *
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   TRUE: success. FALSE: error.
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
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   FALSE
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
 * @param array   $arr_pluginConf: Current plugin/flexform configuration
 * @return  boolean   FALSE
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
 * @param array   $arr_rows_of_all_pages_inRootLine: Agregate the TypoScript of all pages in the rootline
 * @return  boolean   FALSE
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















}







if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_backend.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_backend.php']);
}
?>