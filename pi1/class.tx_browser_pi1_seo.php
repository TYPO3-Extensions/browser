<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008, 2009 Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_seo bundles methods for Search Engine Optimazation for the extension browser
*
* @author    Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   48: class tx_browser_pi1_seo
 *   67:     function __construct($parentObj)
 *
 *              SECTION: SEO - Search Engine Optimation
 *   93:     function seo($elements)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_seo
{


  var $arr_select;
  // Array with the fields of the SQL result
  var $arr_orderBy;
  // Array with fields from orderBy from TS
  var $arr_rmFields;
  // Array with fields from functions.clean_up.csvTableFields from TS



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
   * SEO - Search Engine Optimation
   *
   **********************************************/



  /**
 * Set the tags title and the meta tags description and keywords in the HTML head tag
 *
 * @param	array		the single record
 * @return	void
 */
  function seo($elements) {

    switch($this->pObj->view) {
      case('single'):
        $arrView = 'single.';
        $arrDisplay = 'displaySingle.';
        $boolSubstituteMarkers = TRUE;
        break;
      default:
        $arrView = 'list.';
        $arrDisplay = 'displayList.';
        $boolSubstituteMarkers = FALSE;
        break;
    }
    $mode = $this->pObj->piVar_mode;

    $tmpSeo = $this->pObj->conf['views.'][$arrView][$mode.'.'][$arrDisplay]['seo.'];
    $path = 'views.'.$arrView.$mode.'.'.$arrDisplay.'seo.htmlHead.';

    if (!is_array($tmpSeo))
    {
      $tmpSeo = $this->pObj->conf[$arrDisplay]['seo.'];
      $path = $arrDisplay.'seo.htmlHead.';
      if ($this->pObj->b_drs_seo)
      {
        t3lib_div::devlog('[INFO/SEO] views.'.$this->pObj->view.'.'.$mode.' has no local seo array. It\'s is OK. We take the global seo array.' , $this->pObj->extKey, 0);
      }
    }

    $boolSeoTitle = $tmpSeo['htmlHead.']['title'];
    switch($boolSeoTitle) {
      case(TRUE):
        $htmlTitleTag = $this->pObj->pi_getLL($this->pObj->view.'_mode_'.$mode.'_titleTag');
        if ($htmlTitleTag != '') {
          $htmlTitleTag = $boolSubstituteMarkers ? $this->pObj->objWrapper->wrapTableFields($htmlTitleTag, $elements) : $htmlTitleTag;
          if (is_array($tmpSeo['htmlHead.']['title.']))
          {
            $htmlTitleTag = $this->pObj->local_cObj->stdWrap($htmlTitleTag, $tmpSeo['htmlHead.']['title.']);
          }
          $GLOBALS['TSFE']->register[$this->pObj->extKey.'_htmlTitleTag'] = $htmlTitleTag;
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[INFO/SEO] '.$this->pObj->view.'_mode_'.$mode.' title-tag: '.$htmlTitleTag, $this->pObj->extKey, 0);
          }
        } else {
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[WARN/SEO] '.$this->pObj->view.'_mode_'.$mode.'_titleTag hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
            t3lib_div::devlog('[INFO/SEO] No SEO: We don\'t wrap the HTML-head-title-tag.', $this->pObj->extKey, 0);
          }
        }
        break;
      default:
        if ($this->pObj->b_drs_seo)
        {
          t3lib_div::devlog('[INFO/SEO] No SEO: '.$path.'title is FALSE. If you don\'t like Search Engine Optimisation, it\'s OK.', $this->pObj->extKey, 0);
        }
        break;
    }

    $boolSeoDescr = $tmpSeo['htmlHead.']['meta.']['description'];
    switch($boolSeoDescr) {
      case(TRUE):
        $metaDescr = $this->pObj->pi_getLL($this->pObj->view.'_mode_'.$mode.'_summary');
        if ($metaDescr != '') {
          if($boolSubstituteMarkers)
          {
            $metaDescr = $this->pObj->objWrapper->wrapTableFields($metaDescr, $elements);
          }
          if (is_array($tmpSeo['htmlHead.']['meta.']['description.']))
          {
            $metaDescr = $this->pObj->local_cObj->stdWrap($metaDescr, $tmpSeo['htmlHead.']['meta.']['description.']);
          }
          $metaDescr = str_replace(', ,', ',', $metaDescr);
          $metaDescr = str_replace(',,', ',', $metaDescr);
          $GLOBALS['TSFE']->register[$this->pObj->extKey.'_description'] = $metaDescr;
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[INFO/SEO] '.$this->pObj->view.'_mode_'.$mode.' description: '.$metaDescr.'<br />'.
              'Stored in the register: '.$this->pObj->extKey.'_description', $this->pObj->extKey, 0);
          }
        } else {
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[WARN/SEO] '.$this->pObj->view.'_mode_'.$mode.'_summary hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
            t3lib_div::devlog('[INFO/SEO] No SEO: We don\'t wrap the HTML-head-title-tag.', $this->pObj->extKey, 0);
          }
        }
        break;
      default:
        if ($this->pObj->b_drs_seo)
        {
          t3lib_div::devlog('[INFO/SEO] No SEO: '.$path.'meta.description is FALSE. If you don\'t like Search Engine Optimisation, it\'s OK.', $this->pObj->extKey, 0);
        }
        break;
    }

    $boolSeoKeywd = $tmpSeo['htmlHead.']['meta.']['keywords'];
    switch($boolSeoKeywd) {
      case(TRUE):
        $metaKeywd = $this->pObj->pi_getLL($this->pObj->view.'_mode_'.$mode.'_keywords');
        if ($metaKeywd != '') {
          if ($boolSubstituteMarkers)
          {
            $metaKeywd = $this->pObj->objWrapper->wrapTableFields($metaKeywd, $elements);
          }
          $arrKeywd = explode(',', $metaKeywd);
          foreach((array) $arrKeywd as $key => $value) {
            if (trim($value))
            {
              $cleanedArrKeywd[$key] = trim($value);
            }
          }
            // 110914, dwildt
          //$cleanedArrKeywd = array_unique($cleanedArrKeywd);
          $cleanedArrKeywd = array_unique( (array) $cleanedArrKeywd );
          $metaKeywd       = implode(',', $cleanedArrKeywd);
          if (is_array($tmpSeo['htmlHead.']['meta.']['keywords.']))
          {
            $metaKeywd = $this->pObj->local_cObj->stdWrap($metaKeywd, $tmpSeo['htmlHead.']['meta.']['keywords.']);
          }
          $GLOBALS['TSFE']->register[$this->pObj->extKey.'_keywords'] = $metaKeywd;
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[INFO/SEO] '.$this->pObj->view.'_mode_'.$mode.' keywords: '.$metaKeywd.'<br />'.
              'Stored in the register: '.$this->pObj->extKey.'_keywords', $this->pObj->extKey, 0);
          }
        } else {
          if ($this->pObj->b_drs_seo)
          {
            t3lib_div::devlog('[WARN/SEO] '.$this->pObj->view.'_mode_'.$mode.'_keywords hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
            t3lib_div::devlog('[INFO/SEO] No SEO: We don\'t wrap the HTML-head-title-tag.', $this->pObj->extKey, 0);
          }
        }
        break;
      default:
        if ($this->pObj->b_drs_seo)
        {
          t3lib_div::devlog('[INFO/SEO] No SEO: '.$path.'meta.keywords is FALSE. If you don\'t like Search Engine Optimisation, it\'s OK.', $this->pObj->extKey, 0);
        }
        break;
    }

  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_seo.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_seo.php']);
}

?>