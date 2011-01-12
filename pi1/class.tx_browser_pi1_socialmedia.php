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
* The class tx_browser_pi1_socialmedia bundles methods for social media for the extension browser
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   52: class tx_browser_pi1_socialmedia
 *  105:     function __construct($parentObj)
 *
 *              SECTION: Bookmarks
 *  156:     function get_htmlBookmarks($elements, $key, $bool_defaultTemplate)
 *
 *              SECTION: Initial methods
 *  382:     function init_htmlBookmarks()
 *  670:     function init_default_stdWraps()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_socialmedia
{


  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = false;
  // [Array] The current TypoScript configuration array
  var $mode       = false;
  // [Integer] The current mode (from modeselector)
  var $view       = false;
  // [String] 'list' or 'single': The current view
  var $conf_view  = false;
  // [Array] The TypoScript configuration array of the current view
  var $conf_path  = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  // Variables set by the pObj (by class.tx_browser_pi1.php)



  /////////////////////////////////////////////////
  //
  // Vars set by methods in the current class

  var $str_htmlBookmarks_list    = false;
  // [string] html bookmark code for list views. It contains the marker ###URL### and ###TITLE###
  var $str_htmlBookmarks_single  = false;
  // [string] html bookmark code for single views. It contains the marker ###URL### and ###TITLE###
//  var $arr_default_stdWrapItem   = false;
//  // [array] TypoScript configuration array for a stdWrap of a bookmark (one item). Elements: ['10'] and ['10.']
//  var $arr_default_stdWrapItems  = false;
//  // [array] TypoScript configuration array for a stdWrap of bookmarks (items list). Elements: ['10'] and ['10.']
  var $str_default_htmlItem = false;
  // [string] Default HTML code for a bookmark (one item).
  //          This marker will be replaced: ###BOOKMARK_NAME###, ###BOOKMARK_URL###, ###BOOKMARK_IMAGE###, ###BOOKMARK_IMAGESIZE###
  //          This marker won't be replaced: ###URL### and ###TITLE###
  var $str_default_htmlItems = false;
  // [string] Default HTML code for bookmarks (items list).
  // Vars set by methods in the current class






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
   * Bookmarks
   *
   **********************************************/














  /**
 * get_htmlBookmarks: Set the HTML code for bookmarks in a list or single view.
 *                    Result will be written to the globals $str_htmlbookmarks_list or $str_htmlbookmarks_single.
 *                    Method is available once only.
 *
 * @param	array		$elements: The SQL result of the current row
 * @param	string		$key: The name current table.field
 * @param	boolean		$bool_defaultTemplate: TRUE if we have a default HTML template (with ###ITEM### marker)
 * @return	string		$str_items or FALSE. $str_items are the rendered bookmarks
 */
  function get_htmlBookmarks($elements, $key, $bool_defaultTemplate)
  {
    static $int_countThisMethod = 1;
    static $int_currPlugin_uid  = 0; // #9596

    // #9596: New plugin: Method is called the first time
    if ($int_currPlugin_uid != $this->pObj->cObj->data['uid'])
    {
      $int_countThisMethod  = 1;
      $int_currPlugin_uid   = $this->pObj->cObj->data['uid'];
    }
    // #9596: New plugin: Method is called the first time



    // RETURN if social bookmarks are disabled
    $str_bookmarks_enabled = $this->pObj->objConfig->str_socialmedia_bookmarks_enabled;
    if($str_bookmarks_enabled == 'disabled' || !$str_bookmarks_enabled)
    {
      // DRS - Development Reporting System
      if($int_countThisMethod == 1)
      {
        if ($this->pObj->b_drs_socialmedia)
        {
          t3lib_div::devlog('[INFO/SOCIALMEDIA] RETURN. Social Bookmarks are disabled.', $this->pObj->extKey, 0);
        }
      }
      // DRS - Development Reporting System
      $int_countThisMethod = $int_countThisMethod + 1;
      return false;
    }
    // RETURN if social bookmarks are disabled



    // Default HTML template with an ###ITEM### marker or a ###VALUE### marker
    if($bool_defaultTemplate)
    {
      // RETURN false if current key is the table.field for site
      if($this->view == 'list')
      {
        $str_currKey_forSite = $this->pObj->objConfig->str_socialmedia_bookmarks_tableFieldSite_list;
      }
      if($this->view == 'single')
      {
        $str_currKey_forSite = $this->pObj->objConfig->str_socialmedia_bookmarks_tableFieldSite_single;
      }
      if($str_currKey_forSite != $key)
      {
        // DRS - Development Reporting System
        if($int_countThisMethod <= 2)
        {
          if ($this->pObj->b_drs_socialmedia)
          {
            t3lib_div::devlog('[INFO/SOCIALMEDIA] RETURN. HTML template with ###ITEM### marker or ###VALUE### marker '.
              'Current key ('.$key.') isn\'t table.field for site ('.$str_currKey_forSite.').', $this->pObj->extKey, 0);
          }
        }
        // DRS - Development Reporting System
        $int_countThisMethod = $int_countThisMethod + 1;
        return false;
      }
      // RETURN false if current key is the table.field for site
    }
    // Default HTML template with an ###ITEM### marker or a ###VALUE### marker



    // DRS - Development Reporting System
    // Default template (with ###ITEM### or ###VALUE###)
    if($bool_defaultTemplate)
    {
      if($int_countThisMethod <= 2)
      {
        if ($this->pObj->b_drs_socialmedia)
        {
          t3lib_div::devlog('[INFO/SOCIALMEDIA] HTML template with ###ITEM### marker or ###VALUE### marker. '.
            'Current key is table.field for site ('.$str_currKey_forSite.').', $this->pObj->extKey, 0);
        }
      }
    }
    // Default template (with ###ITEM### or ###VALUE###)
    // Individual template (without ###ITEM### or ###VALUE###)
    if(!$bool_defaultTemplate)
    {
      if($int_countThisMethod <= 2)
      {
        if ($this->pObj->b_drs_socialmedia)
        {
          t3lib_div::devlog('[INFO/SOCIALMEDIA] HTML template without ###ITEM### marker or ###VALUE### marker. ', $this->pObj->extKey, 0);
        }
      }
    }
    // Individual template (without ###ITEM### or ###VALUE###)
    // DRS - Development Reporting System



    // HTML bookmark template
    //        This marker will be replaced: ###BOOKMARK_NAME###, ###BOOKMARK_URL###, ###BOOKMARK_IMAGE###, ###BOOKMARK_IMAGESIZE###
    //          This marker won't be replaced: ###URL### and ###TITLE###
    $str_items = $this->init_htmlBookmarks();
    // HTML bookmark template



    // Set the link to the single view
    // Get the uid of the page with the single view
    if($this->view == 'list')
    {
      $str_tableFieldTitle = $this->pObj->objConfig->str_socialmedia_bookmarks_tableFieldTitle_list;
      $singlePid           = $this->pObj->objZz->get_singlePid_for_listview();
    }
    if($this->view == 'single')
    {
      $str_tableFieldTitle = $this->pObj->objConfig->str_socialmedia_bookmarks_tableFieldTitle_single;
      $singlePid = $GLOBALS['TSFE']->id;
    }
    // Get the uid of the page with the single view

    // Set the piVar for the record uid
    $bool_rmShowuid = false;
    if(!isset($this->pObj->piVars['showUid']))
    {
      $uidField   = $this->pObj->arrLocalTable['uid'];
      $int_recUid = $elements[$uidField];
      $this->pObj->piVars['showUid'] = $int_recUid;
      $bool_rmShowuid = true;
    }
    // Set the piVar for the record uid

    // Set additional Parameter and the cHash
    foreach($this->pObj->piVars as $paramKey => $paramValue) {
      $additionalParams .= '&'.$this->pObj->prefixId.'['.$paramKey.']='.$paramValue;
    }
    $cHash_calc = $this->pObj->objZz->get_cHash('&id='.$singlePid.$additionalParams);
    // Set additional Parameter and the cHash

    // Remove the piVar for the record uid
    if($bool_rmShowuid)
    {
      unset($this->pObj->piVars['showUid']);
    }
    // Remove the piVar for the record uid

    // Set the TypoScript configuration array
    $lConfCObj['typolink.']['parameter']         = $singlePid;
    $lConfCObj['typolink.']['additionalParams']  = $additionalParams.'&cHash='.$cHash_calc;
    $lConfCObj['typolink.']['returnLast']        = 'url';
    // Set the TypoScript configuration array

    // Set the URL (wrap the Link)
    $str_relUrl               = $this->pObj->local_cObj->stdWrap('#', $lConfCObj);
    $str_absUrl               = $this->pObj->objZz->get_absUrl($str_relUrl);
    $markerArray              = false;
    $markerArray['###URL###'] = $str_absUrl;
    // Set the URL (wrap the Link)

    // Set the title property
    if(isset($elements[$str_tableFieldTitle]))
    {
      $str_title = $elements[$str_tableFieldTitle];
    }
    if(!isset($elements[$str_tableFieldTitle]))
    {
      $str_title = $this->pObj->objTemplate->arr_curr_value[$str_tableFieldTitle];
    }
    $str_title = str_replace('"', "'", $str_title);
    $markerArray['###TITLE###'] = $str_title;
    // Set the title property

    // Replace ###URL### and ###TITLE###
    $str_items = $this->pObj->cObj->substituteMarkerArray($str_items, $markerArray);
    // Replace ###URL### and ###TITLE###
    // Set the link to the single view



    // Return the result
// 100912
    $int_countThisMethod = $int_countThisMethod + 1;
//var_dump('socialmedia 327', $str_items);
    return $str_items;
  }














  /***********************************************
   *
   * Initial methods
   *
   **********************************************/














  /**
 * init_htmlBookmarks: Set the HTML code (template) for bookmarks in a list or single view.
 *                     Result will be written to the globals $str_htmlbookmarks_list or $str_htmlbookmarks_single.
 *                     Method is available once only.
 *                     This marker will be replaced: ###BOOKMARK_NAME###, ###BOOKMARK_URL###, ###BOOKMARK_IMAGE###, ###BOOKMARK_IMAGESIZE###
 *                     This marker won't be replaced: ###URL### and ###TITLE###
 *
 * @return	string		$str_items or false. $str_items is an html template code with ###URL### and ###TITLE###
 */
  function init_htmlBookmarks()
  {
    static $int_countThisMethod = 1;
    static $int_currPlugin_uid  = 0; // #9596

    // #9596: New plugin: Method is called the first time
    if ($int_currPlugin_uid != $this->pObj->cObj->data['uid'])
    {
      $int_countThisMethod  = 1;
      $int_currPlugin_uid   = $this->pObj->cObj->data['uid'];
    }
    // #9596: New plugin: Method is called the first time



    // RETURN bookmarks if HTML code is set
    if($int_countThisMethod > 1)
    {
      // DRS - Development Reporting System
      if($int_countThisMethod <= 2)
      {
        if ($this->pObj->b_drs_socialmedia)
        {
          t3lib_div::devlog('[INFO/SOCIALMEDIA] RETURN in init_htmlBookmarks() because method is called the '.$int_countThisMethod.'. time.', $this->pObj->extKey, 0);
        }
      }
      // DRS - Development Reporting System
      $int_countThisMethod = $int_countThisMethod + 1;
      if($this->view == 'list')
      {
        $str_items = $this->str_htmlbookmarks_list;
      }
      if($this->view == 'single')
      {
        $str_items = $this->str_htmlbookmarks_single;
      }
      return $str_items;
    }
    // RETURN bookmarks if HTML code is set



    // Get current bookmars for the list view
    if($this->view == 'list')
    {
      $csv_bookmarks = $this->pObj->objConfig->strCsv_socialmedia_bookmarks_list;
    }
    // Get current bookmars for the list view

    // Get current bookmars for the single view
    if($this->view == 'single')
    {
      $csv_bookmarks = $this->pObj->objConfig->strCsv_socialmedia_bookmarks_single;
    }
    // Get current bookmars for the single view



    // RETURN false if there isn't any selected bookmark
    if(!$csv_bookmarks)
    {
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_socialmedia)
      {
        t3lib_div::devlog('[INFO/SOCIALMEDIA] There isn\'t any bookmark item selected in the plugin.', $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
      $int_countThisMethod = $int_countThisMethod + 1;
      return false;
    }
    // RETURN false if there isn't any selected bookmark



    // Get the TypoScript local bookmark array
    $arr_tsBookmarks = $this->conf_view['plugin.']['socialmedia.']['socialbookmarks.']['bookmarks.']['items.'];
    if(!is_array($arr_tsBookmarks) || count($arr_tsBookmarks) < 1)
    {
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_socialmedia)
      {
        t3lib_div::devlog('[INFO/SOCIALMEDIA] There is no local bookmark array in TypoScript.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SOCIALMEDIA] Try to get the global one ...', $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
    }
    // Get the TypoScript local bookmark array

    // Get the TypoScript global bookmark array
    if(!is_array($arr_tsBookmarks) || count($arr_tsBookmarks) < 1)
    {
      $arr_tsBookmarks = $this->conf['plugin.']['socialmedia.']['socialbookmarks.']['bookmarks.']['items.'];
      // RETURN false if there isn't any global bookmark array in TypoScript
      if(!is_array($arr_tsBookmarks) || count($arr_tsBookmarks) < 1)
      {
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/SOCIALMEDIA] There is no global bookmark array in TypoScript!', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/SOCIALMEDIA] Please configure socialmedia.bookmarks.items.', $this->pObj->extKey, 1);
        }
        // DRS - Development Reporting System
        $int_countThisMethod = $int_countThisMethod + 1;
        return false;
      }
      // RETURN false if there isn't any global bookmark array in TypoScript
    }
    // Get the TypoScript global bookmark array



    // Selected bookmarks keys as array
    $arr_currBookmarks = explode(',', $csv_bookmarks);

    // DRS - Development Reporting System
    if ($this->pObj->b_drs_socialmedia)
    {
      $str_promptBookmarks = implode(', ', $arr_currBookmarks);
      t3lib_div::devlog('[INFO/SOCIALMEDIA] Bookmarks for a the current view: '.$str_promptBookmarks, $this->pObj->extKey, 0);
    }
    // DRS - Development Reporting System



    // Init the default stdWraps for an item (one bookmark) and the item list (bookmarks)
    $this->init_default_stdWraps();



    // Loop through the bookmarks, which are selected in the flexform plugin
    $arr_item = false;
    foreach($arr_currBookmarks as $str_currBookmark)
    {
      // Bookmark isn't a proper TypoScript array
      if(!is_array($arr_tsBookmarks[$str_currBookmark.'.']) || count($arr_tsBookmarks[$str_currBookmark.'.']) < 1)
      {
        $bool_validBookmark = false;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          $str_prompt = 'socialmedia.bookmarks.items.'.$str_currBookmark;
          t3lib_div::devlog('[WARN/SOCIALMEDIA] Current Bookmark isn\'t a valid TypoScript array: '.$str_prompt, $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/SOCIALMEDIA] Please maintain your TypoScript or your plugin.', $this->pObj->extKey, 1);
        }
        // DRS - Development Reporting System
      }
      // Bookmark isn't a proper TypoScript array

      // Bookmark is a proper TypoScript array
      if(is_array($arr_tsBookmarks[$str_currBookmark.'.']) && count($arr_tsBookmarks[$str_currBookmark.'.']) > 0)
      {
        $bool_validBookmark = true;
      }
      // Bookmark is a proper TypoScript array

      // Get the marker array
      if($bool_validBookmark)
      {
        // Get the name
        $markerArray['###BOOKMARK_NAME###'] = $arr_tsBookmarks[$str_currBookmark.'.']['name'];
        if(!$markerArray['###BOOKMARK_NAME###'])
        {
          $markerArray['###BOOKMARK_NAME###'] = $str_currBookmark;
        }
        // Get the name
        // Get the url
        $markerArray['###BOOKMARK_URL###'] = $arr_tsBookmarks[$str_currBookmark.'.']['url'];
        // Get the image path
        $str_imagePath = $arr_tsBookmarks[$str_currBookmark.'.']['image'];
        // Change EXT:browser ... to typo3/typo3conf/ext/browser ...
        $str_imagePathRel = $this->pObj->objZz->get_pathWoEXT($str_imagePath);
        $str_imagePathAbs = t3lib_div::getFileAbsFileName($str_imagePath);
        // Get the image size
        $arr_imageSize = getimagesize ($str_imagePathAbs);
        // Image size isn't proper
        if(!is_array($arr_imageSize))
        {
          $markerArray['###BOOKMARK_IMAGESIZE###']  = 'witdh="16" height="16"';
          // DRS - Development Reporting System
          if ($this->pObj->b_drs_warn)
          {
            $str_prompt = 'socialmedia.bookmarks.items.'.$str_currBookmark;
            t3lib_div::devlog('[WARN/SOCIALMEDIA] Image size isn\'t proper: '.$str_prompt.' = \''.$str_imagePathAbs.'\'', $this->pObj->extKey, 2);
          }
          // DRS - Development Reporting System
        }
        // Image size isn't proper
        // Image size is proper
        if(is_array($arr_imageSize))
        {
          $markerArray['###BOOKMARK_IMAGESIZE###'] = $arr_imageSize[3];
        }
        // Image size is proper
        $markerArray['###BOOKMARK_IMAGE###'] = $str_imagePathRel;
      }
      // Get the marker array

      // stdWrap for the bookmark (one item)
      if($bool_validBookmark)
      {
        // Default HTML code for the item
        $str_item = $this->str_default_htmlItem;
        // Item has its own stdWrap
        $arr_lConf = $arr_tsBookmarks[$str_currBookmark.'.']['stdWrap.'];
        if(is_array($arr_lConf) && count($arr_lConf) > 0)
        {
          $str_item = $this->pObj->local_cObj->stdWrap($arr_lConf['value'], $arr_lConf);
        }
        // Item has its own stdWrap
      }
      // stdWrap for the bookmark (one item)

      // Substitute marker array
      if($bool_validBookmark)
      {
        $str_item = $this->pObj->cObj->substituteMarkerArray($str_item, $markerArray);
      }
      // Substitute marker array

      // Item array
      if($bool_validBookmark)
      {
        $arr_item[] = $str_item;
      }
      // Item array
    }
    // Loop through the bookmarks, which are selected in the flexform plugin



    // Wrap all bookmarks
    unset($markerArray);
    $markerArray['###BOOKMARK_ITEMS###'] = implode('', $arr_item);
    // Default HTML code for the item
    $str_items = $this->str_default_htmlItems;
    $str_items = $this->pObj->cObj->substituteMarkerArray($str_items, $markerArray);
    // Wrap all bookmarks
    //if(t3lib_div::_GP('dev')) var_dump('socialmedia 379', $markerArray, $str_items);



    // Set current bookmars for the list view
    if($this->view == 'list')
    {
      $this->str_htmlbookmarks_list = $str_items;
    }
    // Set current bookmars for the list view

    // Set current bookmars for the single view
    if($this->view == 'single')
    {
      $this->str_htmlbookmarks_single = $str_items;
    }
    // Set current bookmars for the single view



    // Next time isn't the first time
// 100912
    $int_countThisMethod = $int_countThisMethod + 1;

    // Return the html template code with ###URL### and ###TITLE###
    return $str_items;
  }



















  /**
 * init_default_stdWraps: Init the default stdWraps for items (all bookmarks) and item (one bookmark)
 *
 * @return	boolean		false
 */
  function init_default_stdWraps()
  {
    static $bool_firsttime = true;
    static $int_currPlugin_uid  = 0; // #9596

    // #9596: New plugin: Method is called the first time
    if ($int_currPlugin_uid != $this->pObj->cObj->data['uid'])
    {
      $bool_firsttime     = true;
      $int_currPlugin_uid = $this->pObj->cObj->data['uid'];
    }
    // #9596: New plugin: Method is called the first time

    // RETURN if HTML code is set for bookmars in a list view
    if(!$bool_firsttime)
    {
      return false;
    }
    // RETURN if HTML code is set for bookmars in a list view



    // Get the local stdWrap for an item
    $arr_default_stdWrapItem = $this->conf_view['plugin.']['socialmedia.']['socialbookmarks.']['wraps.']['stdWrap_item.'];
    if(!is_array($arr_default_stdWrapItem) || count($arr_default_stdWrapItem) < 1)
    {
      // DRS - Development Reporting System
    if ($this->pObj->b_drs_socialmedia)
      {
        t3lib_div::devlog('[INFO/SOCIALMEDIA] There is no local default stdWrap for a bookmark item!', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SOCIALMEDIA] Try the global one ...', $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
    }
    // Get the local stdWrap for an item

    // Get the global stdWrap for an item
    if(!is_array($arr_default_stdWrapItem) || count($arr_default_stdWrapItem) < 1)
    {
      $arr_default_stdWrapItem = $this->conf['plugin.']['socialmedia.']['socialbookmarks.']['wraps.']['stdWrap_item.'];
      // WARN if there is no global array for a stdWrap
      if(!is_array($arr_default_stdWrapItem) || count($arr_default_stdWrapItem) < 1)
      {
        $this->str_default_htmlItem = false;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/SOCIALMEDIA] There is no default stdWrap for a bookmark item!', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/SOCIALMEDIA] Please configure socialmedia.wraps.stdWrap_item', $this->pObj->extKey, 1);
        }
        // DRS - Development Reporting System
      }
      // WARN if there is no global array for a stdWrap
    }
    // Get the global stdWrap for an item



    // TS config array for the item is proper
    if(is_array($arr_default_stdWrapItem) || count($arr_default_stdWrapItem) > 0)
    {
      // Default HTML code of a bookmark (one item)
      $this->str_default_htmlItem = $this->pObj->local_cObj->stdWrap($arr_default_stdWrapItem['value'], $arr_default_stdWrapItem);
    }
    // TS config array for the item is proper



    // TS config array result for the item isn't proper
    if(!$this->str_default_htmlItem)
    {
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SOCIALMEDIA] There is no result for socialmedia.wraps.stdWrap_item!', $this->pObj->extKey, 3);
        t3lib_div::devlog('[INFO/SOCIALMEDIA] tsConfig: '.$arr_default_stdWrapItem, $this->pObj->extKey, 1);
      }
      // DRS - Development Reporting System
    }
    // TS config array result for the item isn't proper



    // Get the local array for the stdWrap for items (bookmark list)
    $arr_default_stdWrapItems = $this->conf_view['plugin.']['socialmedia.']['socialbookmarks.']['wraps.']['stdWrap_items.'];
    if(!is_array($arr_default_stdWrapItems) || count($arr_default_stdWrapItems) < 1)
    {
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_socialmedia)
      {
        t3lib_div::devlog('[INFO/SOCIALMEDIA] There is no local default stdWrap for the bookmarks (items list)!', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SOCIALMEDIA] Try the global one ...', $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
    }
    // Get the local array for the stdWrap for items (bookmark list)



    // Get the global array for the stdWrap for items (bookmark list)
    if(!is_array($arr_default_stdWrapItems) || count($arr_default_stdWrapItems) < 1)
    {
      $arr_default_stdWrapItems = $this->conf['plugin.']['socialmedia.']['socialbookmarks.']['wraps.']['stdWrap_items.'];
      if(!is_array($arr_default_stdWrapItems) || count($arr_default_stdWrapItems) < 1)
      {
        $this->str_default_htmlItems = false;
        // DRS - Development Reporting System
        if ($this->pObj->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/SOCIALMEDIA] There is no global default stdWrap for the bookmarks (items list)!', $this->pObj->extKey, 2);
          t3lib_div::devlog('[HELP/SOCIALMEDIA] Please configure socialmedia.wraps.stdWrap_items', $this->pObj->extKey, 1);
        }
        // DRS - Development Reporting System
      }
    }
    // Get the global array for the stdWrap for items (bookmark list)



    // TS config array for the items is proper
    if(is_array($arr_default_stdWrapItems) || count($arr_default_stdWrapItems) > 0)
    {
      // Default HTML code of a bookmark (one item)
      $this->str_default_htmlItems = $this->pObj->local_cObj->stdWrap($arr_default_stdWrapItems['value'], $arr_default_stdWrapItems);
    }
    // TS config array for the item is proper



    // TS config array result for the item isn't proper
    if(!$this->str_default_htmlItems)
    {
      // DRS - Development Reporting System
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SOCIALMEDIA] There is no result for socialmedia.wraps.stdWrap_items!', $this->pObj->extKey, 3);
        t3lib_div::devlog('[INFO/SOCIALMEDIA] tsConfig: '.$arr_default_stdWrapItems, $this->pObj->extKey, 1);
      }
      // DRS - Development Reporting System
    }
    // TS config array result for the item isn't proper



    // Next time isn't the first time!
// 100912
    $bool_firsttime = false;
  }











}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_socialmedia.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_socialmedia.php']);
}

?>