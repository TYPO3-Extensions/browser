<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 - 2011 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
* The class tx_browser_pi1_wrapper bundles wrapper methods for the extension browser
*
* @author    Dirk Wildt http://wildt.at.die-netzmacher.de
* @package    TYPO3
* @subpackage    browser
*
* @version 4.0.0
* @since 3.0.0
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_wrapper
 *   78:     function __construct($parentObj)
 *
 *              SECTION: Methods for wrap values and formating
 *  114:     function constant_markers()
 *  215:     function wrapAndLinkValue($tableField, $value, $recordId=0)
 *  761:     function wrapAndLinkValue_Children($tableField, $xsv_values, $lConfCObj, $ext)
 *  875:     function wrapImage($tsImage)
 * 1055:     function wrapDocument($documents)
 * 1109:     function wrapYYYYMMDD($specialDate)
 * 1142:     function general_stdWrap($str, $arr_tsConf)
 * 1171:     function tableSummary($view)
 * 1215:     function tableCaption($view)
 * 1255:     function wrapTableFields($wrapThisString, $elements)
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_wrapper
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
 * @param object    The parent object
 * @return  void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }














  /***********************************************
   *
   * Methods for wrap values and formating
   *
   **********************************************/







  /**
 * constant_markers(): Generate the markerArray with self-defined markers out of the TypoScript. Return a markerArray, if there are values for replacement.
 *
 * @return  array   The markerArray. If there aren't any value, it returns FALSE.
 * @version 3.6.1
 */
  function constant_markers() 
  {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];



      //////////////////////////////////////////////////////////
      //
      // Get the TypoScript marker array. RETURN, if there isn't any array.

    $conf_marker = $conf_view['marker.'];
    if (!is_array($conf_marker))
    {
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] views.'.$viewWiDot.$mode.' hasn\'t any marker array. We take the global one.', $this->pObj->extKey, 0);
      }
      $conf_marker = $conf['marker.'];
      if (!is_array($conf_marker))
      {
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devLog('[INFO/TEMPLATING] The plugin hasn\'t any marker array. Self defined markers in HTML templates won\'t be replaced.', $this->pObj->extKey, 0);
        }
        return false;
      }
    }
      // Get the TypoScript marker array

      //////////////////////////////////////////////////////////
      //
      // Replace database marker in case of a current row

    // #12472, 110124, dwildt
//    if(isset($this->pObj->elements))
//    {
//      $conf_marker = $this->pObj->objMarker->substitute_marker_recurs($conf_marker, $this->pObj->elements);
//    }
      // Replace database marker in case of a current row

      // 110301, dwildt: 13008
    $conf_marker = $this->pObj->objMarker->substitute_tablefield_marker($conf_marker);


      // One dimensional array of the tsConf markers
    $conf_oneDim_marker = t3lib_BEfunc::implodeTSParams($conf_marker);
      // Loop through all elements (real values)
    foreach((array) $this->pObj->elements as $key_tableField => $value_tableField)
    {
        // Loop through one dimensional marker array
      foreach((array) $conf_oneDim_marker as $key => $value)
      {
          // Replace constant marker with real value
        $value = str_replace('###'.strtoupper($key_tableField).'###', $value_tableField, $value);
          // cHash marker
        $pos = strpos($value, '&###CHASH###');
        if (!($pos === false)) {
          $str_path   = str_replace('&###CHASH###', '', $value);
          $arr_url    = parse_url($str_path);
          $cHash_md5  = $this->pObj->objZz->get_cHash($arr_url['path']);
          $value      = str_replace('&###CHASH###', '&cHash='.$cHash_md5, $value);
        }
          // cHash marker
          // session marker
//        if(in_array('session.', $key))
//        {
//            // 110124, dwildt, :TODO: session
//          $elements = $this->session_marker($value_arr_curr, $elements);
//        }
          // session marker
        $conf_oneDim_marker[$key] = $value;
      }
        // Loop through one dimensional marker array
    }
      // Loop through all elements (real values)
    unset($conf_marker);
      // Rebild tsConf marker
    $conf_marker = $this->pObj->objTyposcript->oneDim_to_tree($conf_oneDim_marker);
      // #12472, 110124, dwildt



      //////////////////////////////////////////////////////////
      //
      // Building the marker array for replacement

    foreach((array) $conf_marker as $key_marker => $arr_marker) 
    {
      if(substr($key_marker, -1, 1) == '.')
      {
          // I.e. $key_marker is 'title.', but we like the marker name without any dot
        $str_marker     = substr($key_marker, 0, strlen($key_marker) -1);
          // #32119, 111127, dwildt-
//          // #12472, 110123, dwildt
//        $tskey          = $conf_marker[$str_marker]; // TEXT or COA
//        $hashKeyMarker  = '###'.strtoupper($str_marker).'###';
//        switch($tskey)
//        {
//          case(null):
//          case('TEXT'):
//            $value          = $arr_marker['value'];
//              // The key name of the marker for the markerArray in the format ###MARKER###
//            $markerArray[$hashKeyMarker] = $this->general_stdWrap($value, $arr_marker);
//            break;
//          case('COA'):
//            $markerArray[$hashKeyMarker] = $this->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($arr_marker, $ext=''), false);
//            break;
//          default:
//            var_dump('ERROR: '.$conf_marker[$str_marker]);
//            if ($this->pObj->b_drs_template)
//            {
//              t3lib_div::devlog('[ERROR/TEMPLATING] Type of marker \'' . $str_marker . '\' is ' .
//                $tskey . '. But markers could by TEXT or COA only!' .
//                $this->pObj->cObj->data['uid'], $this->pObj->extKey, 3);
//              t3lib_div::devlog('[WARN/TEMPLATING] Maybe you will get an unproper rendering result.' .
//                $this->pObj->cObj->data['uid'], $this->pObj->extKey, 2);
//              t3lib_div::devlog('[INFO/TEMPLATING] Please configure.' . $str_marker,
//                $this->pObj->cObj->data['uid'], $this->pObj->extKey, 1);
//            }
//        }
//          // #12472, 110123, dwildt
          // #32119, 111127, dwildt-
          // #32119, 111127, dwildt+
        $coa_name                     = $conf_marker[$str_marker];
        if( empty ( $coa_name) )
        {
          $coa_name = 'TEXT';
        }
        $coa_conf                     = $conf_marker[$str_marker . '.'];
        $value                        = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);
        $hashKeyMarker                = '###'.strtoupper($str_marker).'###';
        $markerArray[$hashKeyMarker]  = $value;
          // #32119, 111127, dwildt+
      }
    }
      // Building the marker array for replacement



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX

      // #9659, 101010 fsander
    if ($this->pObj->objFlexform->bool_ajax_enabled)
    {
      $markerArray['###BROWSER_ID###'] = $this->pObj->cObj->data['uid'];
      if ($this->pObj->b_drs_template || $this->pObj->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/TEMPLATING+JSS] AJAX: marker_array is extended with ###BROWSER_ID###: '.
          $this->pObj->cObj->data['uid'], $this->pObj->extKey, 0);
      }
    }
      // AJAX



    return $markerArray;
  }












  /**
 * wrapAndLinkValue(): Wraps a value and links it. Method uses the COA property and API function
 *
 * @param string    $tableField: the field name in the format table.field
 * @param string    $value: The value, which should be wrapped
 * @param integer   $recordId: Id of the record, which should be displayed in a single view
 * @return  string    The wrapped and linked value
 * @version 4.0.0
 * @since 2.0.0
 */
  function wrapAndLinkValue($tableField, $value, $recordId=0)
  {
    static $bool_firsttime = true;

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];

//// 110125, dwildt
//if(t3lib_div::getIndpEnv('REMOTE_ADDR') =='84.184.207.88')
//{
//  if(isset($conf_view['tx_org_repertoire.']['image.']['layout.']['default.']['value']))
//  {
//    var_dump('template 1165', $conf_view['tx_org_repertoire.']['image.']['layout.']['default.']['value']);
//  }
//}


      //////////////////////////////////////////////////////////////
      //
      // Flag for DRS prompting

      // Prompt only, if it is the first row
    $boolPrompt = false;
    if($this->pObj->boolFirstRow && $this->pObj->b_drs_discover)
    {
      $boolPrompt = true;
    }
      // Flag for DRS prompting



      //////////////////////////////////////////////////////////////
      //
      // Get table and field name

    list($table, $field) = explode('.', $tableField);
      // Get table and field name



      //////////////////////////////////////////////////////////////
      //
      // Flags for link process management

      // Only one is possible
    $boolDoNotLink = false;
      // Don't link
    $boolDoJssAlert = false;
      // Link to a javascript alert, if there isn't a single view (only in a list view)
    $boolDoLinkToSingle = false;
      // Link to a single view, if there is one (only in a list view)
    $boolDoTsTypolink = false;
      // Wrap the value with the values of the typolink array, which were setted by the user in TypoScript
      // This has priority over all others
      // Flags for link process management



      //////////////////////////////////////////////////////////////
      //
      // Flag for list views: Is there a single view?

    $boolSingleViewExist = false;
    if ($view == 'list') {
      if(is_array($conf['views.']['single.'][$mode.'.'])) {
          // We are in a list and it is possible to link on a single view
        $boolSingleViewExist = true;
      }
    }
      // Flag for list views: Is there a single view?



      //////////////////////////////////////////////////////////////
      //
      // Do we have a typolink configuration in the TS?

    $tsArrTypolink = $conf['views.'][$viewWiDot][$mode.'.'][$table.'.'][$field.'.']['typolink.'];
    if(is_array($tsArrTypolink)) {
      // We have a typolink configuration in the TS
      $boolDoTsTypolink = true;
      if($boolPrompt) t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' has a local typolink array.', $this->pObj->extKey, 0);
    }
      // Do we have a typolink configuration in the TS?



      //////////////////////////////////////////////////////////////
      //
      // Should we set in list views an jssAlert, if there isn't a single view?

    if ($view == 'list') {
      // We have a list view
      if(in_array($tableField, $this->pObj->arrLinkToSingle)) {
        // There should be a link to a single view
        $lDisplayList = $conf['views.'][$viewWiDot][$mode.'.']['displayList.'];
        if(!is_array($lDisplayList)) {
          $lDisplayList = $conf['displayList.'];
        }
        $boolDoJssAlert = $lDisplayList['display.']['jssAlert'];
      }
    }
      // Should we set in list views an jssAlert, if there isn't a single view?



      //////////////////////////////////////////////////////////////
      //
      // Prepaire booleans for the link process management

    $arr_prompt_drs = null;
    switch(true)
    {
        // 110831, dwildt-
//      case( empty ( $value ) ) :
        // 110831, dwildt+
      case( $value == null ) :
          // There isn't any value, don't set a link. This has priority over all below.
        $arr_prompt_drs[]   = '!$value || $value == \'\'';
        $boolDoNotLink      = false;
        $boolDoJssAlert     = false;
        $boolDoLinkToSingle = false;
        $boolDoTsTypolink   = false;
        break;
          // There isn't any value, don't set a link. This has priority over all below.
      case($view == 'list') :
          // Current view is a list view
        $arr_prompt_drs[]   = '$view == \'list\'';
        switch(true)
        {
            // Is the field an element in the array linkToSingle?
          case(!in_array($tableField, $this->pObj->arrLinkToSingle)):
              // The value shouldn't get any link to a single view
              // There isn't any link to set
            $arr_prompt_drs[]   = '!in_array($tableField, $this->pObj->arrLinkToSingle)';
            $boolDoNotLink      = true;
            $boolDoJssAlert     = false;
            $boolDoLinkToSingle = false;
            $boolDoTsTypolink   = false;
            break;
          default:
            $arr_prompt_drs[]   = 'default';
              // Link to a single view
            switch(true)
            {
              case($boolSingleViewExist):
                  // There is a single view, link to it!
                $arr_prompt_drs[]   = '$boolSingleViewExist';
                $boolDoNotLink      = false;
                $boolDoJssAlert     = false;
                $boolDoLinkToSingle = true;
                $boolDoTsTypolink   = false;
                break;
              default:
                  // There isn't any single view, link to a javascript alert?
                $arr_prompt_drs[]   = 'default';
                switch(true)
                {
                  case($boolDoJssAlert):
                      // Link to a javascript alert
                    $arr_prompt_drs[]   = '$boolDoJssAlert';
                    $boolDoNotLink      = false;
                    $boolDoJssAlert     = true;
                    $boolDoLinkToSingle = false;
                    $boolDoTsTypolink   = false;
                    break;
                  default:
                      // Don't link to a javascript alert
                    $arr_prompt_drs[]   = 'default';
                    $boolDoNotLink      = true;
                    $boolDoJssAlert     = false;
                    $boolDoLinkToSingle = false;
                    $boolDoTsTypolink   = false;
                    break;
                }
                break;
                  // There isn't any single view, link to a javascript alert?
            }
            break;
              // Link to a single view
        }
        break;
          // Current view is a list view
      case($view == 'single'):
          // Current view is a single view
        $boolDoNotLink      = true;
        $boolDoJssAlert     = false;
        $boolDoLinkToSingle = false;
        $boolDoTsTypolink   = false;
        $arr_prompt_drs[]   = '$view == \'single\'';
        break;
          // Current view is a single view
      default:
          // ERROR: undefined case!
        if($this->pObj->b_drs_error) {
          t3lib_div::devLog('[ERROR/DRS] Method wrapAndLinkValue() has an undefined case in \'Prepaire process management\'.', $this->pObj->extKey, 3);
          t3lib_div::devlog('[HELP/DRS] Please contact the developer:<br />'.$this->pObj->developer_contact, $this->pObj->extKey, 1);
          t3lib_div::devLog('[WARN/DRS] '.$tableField.' will be wrapped not proper probably.', $this->pObj->extKey, 2);
        }
        $arr_prompt_drs[]   = 'default';
        break;
          // ERROR: undefined case!
    }
    $str_prompt_drs = implode(' -> ', $arr_prompt_drs);
    //var_dump(__METHOD__ . ': ' . __LINE__, $str_prompt_drs, '$boolDoLinkToSingle: ' . $boolDoLinkToSingle);
      // Prepaire booleans for the link process management



      //////////////////////////////////////////////////////////////////////
      //
      // csv export

      // #29370, 110831, dwildt+
      // Remove the title in case of csv export
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export )
        {
          t3lib_div::devlog('[INFO/EXPORT] Don\'t link to a single view. All booleans are set to false!',  $this->pObj->extKey, 0);
        }
        $boolDoNotLink      = false;
        $boolDoJssAlert     = false;
        $boolDoLinkToSingle = false;
        $boolDoTsTypolink   = false;
        break;
      default:
        // Do nothing;
    }
      // Remove the title in case of csv export
      // csv export



      //////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($bool_firsttime)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After prepaire link process: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////
      //
      // If we need it, set the singlePid

    if($boolDoLinkToSingle) 
    {
      $singlePid             = $this->pObj->objZz->get_singlePid_for_listview();
      $this->pObj->singlePid = $singlePid;
    }
      // If we need it, set the singlePid



      //////////////////////////////////////////////////////////////
      //
      // COA Process management

    $lCObjType = 'TEXT';

      // COA default type
      // Is there a COA array in the TypoScript setup?
    if(is_array($conf['views.'][$viewWiDot][$mode.'.'][$table.'.'][$field.'.'])) 
    {
        // Get the COA type, set it to 'TEXT', if there isn't a value
      $lCObjType = $conf['views.'][$viewWiDot][$mode.'.'][$table.'.'][$field];
      if (!$lCObjType)
      {
        $lCObjType = 'TEXT';
      }
        // Get the COA array
      $lConfCObj['10.'] = $conf['views.'][$viewWiDot][$mode.'.'][$table.'.'][$field.'.'];
    }
      // Is there a COA array in the TypoScript setup?


      ///////////////////////////////////
      //
      // Get the local or global autoconfig array - #9879

    $lAutoconf = $conf_view['autoconfig.'];
    $view_path = $viewWiDot.$mode;
    if (!is_array($lAutoconf))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.'.$view_path.' hasn\'t any autoconf array.<br />
          We take the global one.', $this->pObj->extKey, 0);
      }
      $view_path  = null;
      $lAutoconf  = $conf['autoconfig.'];
    }
      // Get the local or global autoconfig array - #9879


// 110125, dwildt
//if(t3lib_div::getIndpEnv('REMOTE_ADDR') =='84.184.207.88')
//{
//  if(isset($lConfCObj['10.']['layout.']['default.']['value']))
//  {
//    var_dump('wrapper 579', $lConfCObj['10.']['layout.']['default.']['value']);
//  }
//}
    $lConfCObj['10']  = $lCObjType;
    $lConfCObj['10.']['value'] = $value;
    if ($lAutoconf['marker.']['typoScript.']['replacement'])
    {
      //if(t3lib_div::_GP('dev')) var_dump('wrapper 478', $lConfCObj, $this->pObj->elements);
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] Replacement for markers in TypoScript is activated.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING] If you don\'t want a replacement, please configure '.$view_path.'autoconfig.marker.typoScript.replacement.', $this->pObj->extKey, 1);
      }
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_warn)
      {
        if(!isset($this->pObj->elements))
        {
          t3lib_div::devLog('[WARN/TEMPLATING] $this->pObj->elements isn\'t set!', $this->pObj->extKey, 2);
        }
      }
      $lConfCObj = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $this->pObj->elements);
      //Replace all ###MARKER### in Typoscript with its values.
      //if(t3lib_div::_GP('dev')) var_dump('wrapper 485', $lConfCObj);
    }
// 110125, dwildt
//if(t3lib_div::getIndpEnv('REMOTE_ADDR') =='84.184.207.88')
//{
//  if(isset($lConfCObj['10.']['layout.']['default.']['value']))
//  {
//    var_dump('wrapper 608', $lConfCObj['10.']['layout.']['default.']['value']);
//  }
//}
    if (!$lAutoconf['marker.']['typoScript.']['replacement'])
    {
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        t3lib_div::devLog('[INFO/TEMPLATING] Replacement for markers in TypoScript is deactivated.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/TEMPLATING] If you want a replacement, please configure '.$view_path.'autoconfig.marker.typoScript.replacement.', $this->pObj->extKey, 1);
      }
    }



      //////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($bool_firsttime)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After COA process: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////
      //
      // Process management

    switch(true) {
      case($boolDoTsTypolink):
          // There is a typolink array in the TS of the user. This has priority over all others
        if($boolPrompt) t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' has a local typolink array.', $this->pObj->extKey, 0);
        break;
      case($boolDoLinkToSingle):
          // Remove piVars, if they should not used in the realUrl path
        $this->pObj->objZz->advanced_remove_piVars( );
          // #8368
        $this->pObj->objZz->advanced_remove_piVars_filter( );
          // We have to set the link to the single view
        if($boolPrompt) t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' gets a link to single view.', $this->pObj->extKey, 0);
        // Building the URI parameters
        $additionalParams = '';

          // Alias for showUid? #9599
        if( empty( $this->pObj->piVar_alias_showUid ) )
        {
          $this->pObj->piVars['showUid'] = $recordId;
        }
        if( ! empty( $this->pObj->piVar_alias_showUid ) )
        {
          unset( $this->pObj->piVars['showUid'] );
          $this->pObj->objZz->tmp_piVars['showUid'] = null;
          $this->pObj->piVars[$this->pObj->piVar_alias_showUid] = $recordId;
        }
          // Alias for showUid? #9599

        foreach( ( array ) $this->pObj->piVars as $paramKey => $paramValue )
        {
            // 110807, dwildt +
          if( ! empty( $paramValue ) )
          {
            $additionalParams .= '&' . $this->pObj->prefixId . '[' . $paramKey . ']=' . $paramValue;
          }
            // 110807, dwildt +
            // 110807, dwildt -
          //$additionalParams .= '&'.$this->pObj->prefixId.'['.$paramKey.']='.$paramValue;
        }

          // #32676, 111218, dwildt+
        if( count( $conf['views.'][$viewWiDot] ) > 1 )
        {
            // Get the key of the first view
          reset( $this->pObj->conf['views.'][$viewWiDot] );
          $firstKeyWiDot          = key( $this->pObj->conf['views.'][$viewWiDot] );
          $firstKeyWoDot          = substr( $firstKeyWiDot, 0, strlen($firstKeyWiDot ) - 1 );
            // Get the key of the first view
          //var_dump( __METHOD__ , __LINE__ , $this->pObj->piVar_mode, $firstKeyWoDot );
            // Add the parameter mode
          if( $this->pObj->piVar_mode != $firstKeyWoDot )
          {
            //$this->pObj->piVars['mode'] = $this->pObj->piVar_mode;
            $additionalParams .= '&' . $this->pObj->prefixId . '[mode]=' . $this->pObj->piVar_mode;
          }
            // Add the parameter mode
          //var_dump( __METHOD__ , __LINE__ , $additionalParams );
        }
          // #32676, 111218, dwildt+


        $cHash_calc = $this->pObj->objZz->get_cHash('&id='.$singlePid.$additionalParams);
          // Building the typolink array
        if(is_array($lConfCObj['10.']['typolink.']))
        {
          if ($this->pObj->b_drs_templating && $this->pObj->boolFirstRow)
          {
            t3lib_div::devLog('[WARN/TEMPLATING] The array 10.typolink will be overriden!', $this->pObj->extKey, 2);
          }
        }
        $lConfCObj['10.']['typolink.']['parameter']         = $singlePid;
        $lConfCObj['10.']['typolink.']['additionalParams']  = $additionalParams.'&cHash='.$cHash_calc;
          // #9659, 101010 fsander
        $lConfCObj['10.']['typolink.']['ATagParams']        = 'class="linktosingle"';

        if ($this->pObj->boolFirstRow && $this->pObj->b_drs_warn)
        {
          if(!isset($this->pObj->elements))
          {
            t3lib_div::devLog('[WARN/TEMPLATING] $this->pObj->elements isn\'t set!', $this->pObj->extKey, 2);
          }
        }
        $lConfCObj = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $this->pObj->elements);
          // Replace all ###MARKER### in Typoscript with its values.

          // Recover piVars, if they weren't used in the realUrl path
        if ($this->pObj->objZz->tmp_piVars)
        {
          $this->pObj->piVars = $this->pObj->objZz->tmp_piVars;
          if ($this->pObj->b_drs_templating && $this->pObj->boolFirstRow)
          {
            $str_prompt = implode('], piVars[', array_keys($this->pObj->piVars));
            $str_prompt = 'piVars['.$str_prompt.']';
            t3lib_div::devLog('[INFO/TEMPLATING] piVars are recovered:<br />
              '.$str_prompt.'.', $this->pObj->extKey, 0);
          }
          unset($this->pObj->objZz->tmp_piVars);
        }
          // Recover piVars, if they weren't used in the realUrl path
        break;
      case($boolDoJssAlert):
          // There is no single view. We have to set a link to a javascript alert
        if($boolPrompt) t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' gets a link with a javascript alert: No single view!', $this->pObj->extKey, 0);
        $promptJSS = $this->pObj->pi_getLL('error_views_single_noview');
        $promptJSS = t3lib_div::slashJS($promptJSS, false, "'");
        // Bugfix #8589
        //$promptJSS = '\''.$promptJSS.'\'';
        $promptJSS = rawurlencode('\''.$promptJSS.'\'');
        // Bugfix #8589
        $aHrefJSSalert = '<a href="javascript:alert('.$promptJSS.')">';
        // Has COA array a wrap property?
        switch(true)
        {
          case($lConfCObj['10.']['wrap']):
            // There is a wrap
            // WARNING: Possible Error! If user changed the default sign '|' in TS
            $arrLWrap = explode('|', $lConfCObj['10.']['wrap']);
            // Insert the javascript alert wrap
            $lConfCObj['10.']['wrap'] = trim($arrLWrap[0]).$aHrefJSSalert.'|</a>'.trim($arrLWrap[1]);
            break;
          default:
            // There isn't any wrap
            // Add the javascript alert wrap
            $lConfCObj['10.']['wrap'] = $aHrefJSSalert.'|</a>';
            break;
        }
        break;
      case($boolDoNotLink):
          // We don't have to process any link
        if($boolPrompt) t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' don\'t get any link.', $this->pObj->extKey, 0);
        break;
      default:
          // This case isn't defined
        if($this->pObj->b_drs_info && $this->pObj->boolFirstRow)
        {
//          t3lib_div::devLog('[ERROR/DRS] Method wrapAndLinkValue() has an undefined case in \'Process management\'.', $this->pObj->extKey, 3);
//          t3lib_div::devlog('[HELP/DRS] Please contact the developer:<br />'.$this->pObj->developer_contact, $this->pObj->extKey, 1);
//          t3lib_div::devLog('[WARN/DRS] '.$tableField.' will be wrapped not proper probably.', $this->pObj->extKey, 2);
          t3lib_div::devLog('[INFO/DRS] Method wrapAndLinkValue() has an undefined case in \'Process management\'.', $this->pObj->extKey, 0);
        }
    }
    // Process management
      // COA Process management



      //////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($bool_firsttime)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After process management: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////
      //
      // general_stdWrap

    $bool_wrap_children = false;
    if(is_array($this->pObj->arr_children_to_devide))
    {
      if(in_array($tableField, $this->pObj->arr_children_to_devide))
      {
        $bool_wrap_children = true;
      }
    }
    if($bool_wrap_children)
    {
      $value = $this->wrapAndLinkValue_Children($tableField, $value, $lConfCObj, $ext='');
    }
    if(!$bool_wrap_children)
    {
      // $ext: If "INT" then the cObject is a "COBJ_ARRAY_INT" (non-cached), otherwise just "COBJ_ARRAY" (cached)
      $value = $this->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($lConfCObj, $ext=''), false);
    }
      // general_stdWrap



      //////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($bool_firsttime)
    {
      if ($this->pObj->b_drs_perform) {
        if($this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->getDifferenceToStarttime();
        }
        if(!$this->pObj->bool_typo3_43)
        {
          $endTime = $this->pObj->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] After general stdWrap: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
      }
    }
      // DRS - Performance



    $bool_firsttime = false;

    return $value;

  }












  /**
 * wrapAndLinkValue_Children: Wraps the values and links of children records. Method is necessary because of the
 *                            workflow of the browser. Children records became a string. This method enables, to
 *                            wrap each child in the string seperately.
 *
 * @param string    $tableField: the field name in the format table.field
 * @param string    $xsv_value: Variable seperated values, which should be wrapped
 * @param array   $lConfCObj: TypoScript configuration array
 * @param string    $ext: If "INT" then the cObject is a "COBJ_ARRAY_INT" (non-cached), otherwise just "COBJ_ARRAY" (cached)
 * @return  string    The wrapped and linked children values
 */
  function wrapAndLinkValue_Children($tableField, $xsv_values, $lConfCObj, $ext)
  {

    //////////////////////////////////////////////////////////////
    //
    // Get the children devider configuration

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



    //////////////////////////////////////////////////////////////
    //
    // RETURN. We have the default TS configuration only

    if(count($lConfCObj['10.']) < 2)
    {
      // Remove the workflow devider
      $xsv_values                = str_replace($str_sqlDeviderWorkflow, '', $xsv_values);
      // Replace value with value with removed workflow deviders
      $lConfCObj['10.']['value'] = $xsv_values;
      $value                     = $this->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($lConfCObj, $ext), false);
      return $value;
    }
    // RETURN. We have the default TS configuration only



    //////////////////////////////////////////////////////////////
    //
    // Get the children TS configuration recursiv

    // Examples:
    //   $xsv_values: news, ;|;jobs, ;|;Juridat

    $arr_values   = explode($str_devider, $xsv_values);
    $arr_confCObj = array();
    foreach((array) $arr_values as $key => $value)
    {
      // Get for every child the TS configuration
      $arr_confCObj[$key] = $this->pObj->objZz->children_tsconf_recurs($key, $lConfCObj, $str_devider);

      // 010810, fsander, #8434
      // Remove outerWrap but safe it first for later processing
      $finalConfCObj['outerWrap'] = $lConfCObj['10.']['outerWrap'];
      unset ($arr_confCObj[$key]['10.']['outerWrap']);
      // Remove outerWrap but safe it first for later processing
      // 010810, fsander, #8434
    }
    // Get the children TS configuration recursiv



    //////////////////////////////////////////////////////////////
    //
    // general_stdWrap for each child

    $arr_values = array();
    foreach((array) $arr_confCObj as $lConfCObj)
    {
      $str_value    = $this->general_stdWrap($this->pObj->local_cObj->COBJ_ARRAY($lConfCObj, $ext), false);
      $arr_values[] = $str_value;
    }
    // general_stdWrap for each child



    //////////////////////////////////////////////////////////////
    //
    // Implode the result array

    $value = implode($str_sqlDeviderDisplay, $arr_values);
    // Implode the result array



    // 010810, fsander, #8434
    // Add outerWrap to the final result
    $value = $this->pObj->local_cObj->stdWrap($value, $finalConfCObj);
    // 010810, fsander, #8434

//var_dump('wrapper 835', $value);
    return $value;
  }











  /**
 * Wrap images with the TYPO3 stdWrap method
 *
 * @param array   $tsImage : the typoscript array of an image
 * @return  string    The wrapped image(s)
 * @version 3.6.0
 * @since 1.0
 */
  function wrapImage($tsImage)
  {

    static $bool_first = true;

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';

    // If global array $handleAs has an uploadfolder overwrite $this->pObj->uploadFolder
    if($this->pObj->arrHandleAs['uploadfolder']['image'])
    {
      // DRS - Development Reporting System
      if($bool_first)
      {
        if($this->pObj->b_drs_discover && $this->pObj->uploadFolder)
        {
          t3lib_div::devLog('[WARN/DISCOVER] The path to the upload folder '.$this->pObj->uploadFolder.' is overriden by Autodiscover.', $this->pObj->extKey, 2);
          t3lib_div::devLog('[HELP/DISCOVER] If you don\'t like it, set autoconfig.autoDiscover.items.x.setUploadFolder to false.', $this->pObj->extKey, 1);
        }
      }
      // DRS - Development Reporting System
      $this->pObj->uploadFolder = $this->pObj->arrHandleAs['uploadfolder']['image'].'/';
    }



    // DRS - Development Reporting System
    if ($this->pObj->b_drs_error && !$this->pObj->uploadFolder)
    {
      t3lib_div::devLog('[WARN/DRS] Images should be displayed as image, but there is no upload folder.', $this->pObj->extKey, 2);
      t3lib_div::devLog('[HELP/DRS] Isn\'t configured: plugin.'.$this->pObj->prefixId.'.autoconfig.autoDiscover.items.image.setUploadFolder', $this->pObj->extKey, 1);
      t3lib_div::devLog('[HELP/DRS] Did you configured plugin.'.$this->pObj->prefixId.'.upload?', $this->pObj->extKey, 1);
      $tsPath = $this->pObj->prefixId.'views.'.$viewWiDot.$mode.'.upload';
      t3lib_div::devLog('[HELP/DRS] Did you configured '.$tsPath.'?', $this->pObj->extKey, 1);
    }
    // DRS - Development Reporting System



    switch($view)
    {
      case('list'):
        $tsDisplay = 'displayList';
        break;
      case('single'):
        $tsDisplay = 'displaySingle';
        break;
    }

    if (is_array($this->pObj->conf['views.'][$view.'.'][$mode.'.'][$tsDisplay.'.']))
    {
      $lConf = $this->pObj->conf['views.'][$view.'.'][$mode.'.'][$tsDisplay.'.'];
    }
    else
    {
      $lConf = $this->pObj->conf[$tsDisplay.'.'];
    }



    // dwildt, 101201, #11204
    if (empty($tsImage['image']))
    {
      // dwildt, 101201, #11204
      // return false;
      if(empty($lConf['image.']['file']))
      {
        return false;
      }
      // dwildt, 101201, #11204
    }



    $imageNum = 1;
    if(!empty($lConf['imageCount']))
    {
      $imageNum = $lConf['imageCount'];
    }
    $imageNum       = t3lib_div::intInRange($imageNum, 0, 100);
    $theImgCode     = '';
    $imgs           = t3lib_div::trimExplode(',', $tsImage['image'], 1);
    $imgsCaptions   = explode(chr(10), $tsImage['imagecaption']);
    $imgsAltTexts   = explode(chr(10), $tsImage['imagealttext']);
    $imgsTitleTexts = explode(chr(10), $tsImage['imagetitletext']);


    reset($imgs);

    $boolRemoveFirstImage = false;
    if ((count($imgs) > 1 && $lConf['firstImageIsPreview']) && $view == 'single')
    {
      $boolRemoveFirstImage = true;
    }
    if ((count($imgs) >= 1 && $lConf['forceFirstImageIsPreview']) && $view == 'single')
    {
      $boolRemoveFirstImage = true;
    }
    if ($boolRemoveFirstImage)
    {
      array_shift($imgs);
      array_shift($imgsCaptions);
      array_shift($imgsAltTexts);
      array_shift($imgsTitleTexts);
    }
    $cc = 0;

    while (list(, $val) = each($imgs))
    {
      if ($cc == $imageNum)
      {
        break;
      }
      if (!empty($val))
      {
        $lConf['image.']['altText']   = $imgsAltTexts[$cc];
        $lConf['image.']['titleText'] = $imgsTitleTexts[$cc];
        // #9419
        //$lConf['image.']['file']      = trim($this->pObj->uploadFolder).$val;
        $lConf['image.']['file']      = rtrim(trim($this->pObj->uploadFolder), '/').'/'.$val;
      }
      $currImg     = $this->pObj->local_cObj->IMAGE($lConf['image.']) . $this->pObj->local_cObj->stdWrap($imgsCaptions[$cc], $lConf['caption_stdWrap.']);
      // dwildt, 101201, #11211
      $currBoxWrap = str_replace('###IMAGE_COUNT###', $cc + 1, $lConf['imageBoxWrap.']);
      $currImg     = $this->pObj->local_cObj->stdWrap($currImg, $currBoxWrap);
      $theImgCode .= $currImg;
      $cc++;
    }
    $wrappedImage = '';
    if ($cc)
    {
      $wrappedImage = $this->pObj->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
    }
    else
    {
      // dwildt, 101201, #11204
      if(!empty($lConf['image.']['file']))
      {
        $lConf['image.']['altText']   = $this->pObj->pi_getLL('label_noImageFile', 'There isn\'t any image available.', true);
        $lConf['image.']['titleText'] = $this->pObj->pi_getLL('label_noImageFile', 'There isn\'t any image available.', true);
        $lConf['image.']['file']      = $this->pObj->objZz->get_pathWoEXT($lConf['image.']['file']);
        $currImg      = $this->pObj->local_cObj->IMAGE($lConf['image.']) . $this->pObj->local_cObj->stdWrap($imgsCaptions[$cc], $lConf['caption_stdWrap.']);
        // dwildt, 101201, #11211
        $currBoxWrap  = str_replace('###IMAGE_COUNT###', $cc + 1, $lConf['imageBoxWrap.']);
        $currImg      = $this->pObj->local_cObj->stdWrap($currImg, $currBoxWrap);
        $theImgCode   .= $currImg;
        $wrappedImage = $this->pObj->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
      }
      if(empty($lConf['image.']['file']))
      {
        $wrappedImage = $this->pObj->local_cObj->stdWrap($wrappedImage, $lConf['image.']['noImage_stdWrap.']);
      }
      // dwildt, 101201, #11204
    }

    $bool_first = false;

    return $wrappedImage;
  }









  /**
 * wrapInBaseIdClass: Wrap the given content with a div-tag and the properties id and class.
 *                    id i.e    : id="c2794-tx-browser-pi1"
 *                    class i.e : class="c2794-tx-browser-pi1"
 *                    Method is added with #28562
 *
 * @param string    $content: the content which will be wrapped
 * @return  string    the wrapped content
 * 
 * @version 4.0.0
 * @since 3.7.0
 */
  function wrapInBaseIdClass($content)
  {
      // Rendering the id.  I.e. c1149-tx-browser-pi1-list-11083102
      //                    c1149:          uid in the tt_content table / uid of the plugin
      //                    tx-browser-pi1: prefix-id withreplaced _
      //                    view:           list
      //                    mode:           11083102
      //                    #29042
    $uidPlugin      = 'c' . $this->pObj->cObj->data['uid'];
    $local_prefixId = str_replace('_', '-', $this->pObj->prefixId);
    $id             = ' id="' .
                      $uidPlugin . '-' . $local_prefixId . '-' . $this->pObj->view . '-' . $this->pObj->piVar_mode . '"';
    $class          = ' class="' . 
                      $local_prefixId . ' ' .
                      $local_prefixId . '-' . $this->pObj->view . ' ' .
                      $uidPlugin . '-' . $local_prefixId . '-' . $this->pObj->view . '"';
    
    $wrap['start']  = '<div' . $id . $class . '>';
    $wrap['end']    = '</div>';
    
    return $wrap['start'] . $content . $wrap['end'];
  }








  /**
 * Wrap documents with the TYPO3 filelink method
 *
 * @param string    $documents : the list of documents
 * @return  string    The wrapped document(s)
 */
  function wrapDocument($documents) {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';

    if (!$documents) return false;

    // If global array $handleAs has an uploadfolder overwrite $this->pObj->uploadFolder
    // Bugfix #6776, dwildt, 100310
    if($this->pObj->arrHandleAs['uploadfolder']['document'])
    {
      if($this->pObj->b_drs_discover && $this->pObj->uploadFolder)
      {
        t3lib_div::devLog('[WARN/DISCOVER] The path to the upload folder '.$this->pObj->uploadFolder.' is overriden by Autodiscover.', $this->pObj->extKey, 2);
        t3lib_div::devLog('[HELP/DISCOVER] If you don\'t like it, set autoconfig.autoDiscover.items.x.setUploadFolder to FALSE.', $this->pObj->extKey, 1);
      }
      $this->pObj->uploadFolder = $this->pObj->arrHandleAs['uploadfolder']['document'].'/';
    }

    if ($this->pObj->b_drs_error && !$this->pObj->uploadFolder)
    {
      t3lib_div::devLog('[WARN/DRS] Documents should be displayed, but there is no upload folder.', $this->pObj->extKey, 2);
      t3lib_div::devLog('[HELP/DRS] Isn\'t configured plugin.'.$this->pObj->prefixId.'.autoconfig.autoDiscover.items.documents.setUploadFolder', $this->pObj->extKey, 1);
      t3lib_div::devLog('[HELP/DRS] Did you configured plugin.'.$this->pObj->prefixId.'.upload?', $this->pObj->extKey, 1);
      $tsPath = $this->pObj->prefixId.'views.'.$viewWiDot.$mode.'.upload';
      t3lib_div::devLog('[HELP] Did you configured '.$tsPath.'?', $this->pObj->extKey, 1);
    }


    if (is_array($this->pObj->conf['views.'][$viewWiDot][$mode.'.']['document_stdWrap.'])) {
      $lConf = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['document_stdWrap.'];
    } else {
      $lConf = $this->pObj->conf['document_stdWrap.'];
    }

    $fileArr = explode(',', $documents);
    $lConf['path'] = trim($this->pObj->uploadFolder);
    while (list(, $val) = each($fileArr)) {
      $filelinks .= $this->pObj->local_cObj->filelink($val, $lConf) ;
    }
    return $filelinks;
  }


  /**
 * Wrap string in the format YYYY-MM-DD. It is a special method for the extension ships.
 *
 * @param string    $string : the string in the format YYYY-MM-DD
 * @param string    $view : list or single
 * @return  string    The wrapped document(s)
 */
  function wrapYYYYMMDD($specialDate) {

    if (!$specialDate) return false;

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';

    if ($this->pObj->conf['views.'][$viewWiDot][$mode.'.']['format.']['date'] != '') {
      $dateFormat = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['format.']['date'];
    } else {
      $dateFormat = $this->pObj->conf['format.']['date'];
    }

    $arrDate = explode('-', $specialDate);
    if (!$arrDate[0] || $arrDate[0] == 0 || $arrDate[0] == '') return false;
    if (!$arrDate[1] || $arrDate[1] == 0 || $arrDate[1] == '') $arrDate[1] = 1;
    if (!$arrDate[2] || $arrDate[2] == 0 || $arrDate[2] == '') $arrDate[2] = 1;

    return date($dateFormat, mktime(0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0]));

  }

  /**
 * Wraps the given string with general_stdWrap from configuration. If $arr_tsConf is an array, $arr_tsConf will be
 * processed instead of general_stdWrap.
 *
 * @param string    $string to wrap
 * @param array   $arr_tsConf: Array with a TS configuration
 * @return  string    Wrapped string
 */
  function general_stdWrap($str, $arr_tsConf)
  {
    if (is_array($arr_tsConf)) {
      // $arr_tsConf is an array. RETURN $str, wrapped with this TS configuration array.
      $str = $this->pObj->local_cObj->stdWrap($str, $arr_tsConf);
      return $str;
    }

    if (is_array($this->pObj->conf['general_stdWrap.'])) {
      // general_stdWrap is an array. RETURN $str, wrapped with general_stdWrap.
      $str = $this->pObj->local_cObj->stdWrap($str, $this->pObj->conf['general_stdWrap.']);
      return $str;
    }

    // RETURN $str unchanged.
    return $str;
  }






  /**
 * Return the table summary out of the locallang_db.xml
 *
 * @param string    view: list or single
 * @return  string    summary
 */
  function tableSummary($view)
  {

    $mode = 'mode_'.$this->pObj->piVar_mode;

    $langKey = $GLOBALS['TSFE']->lang;
    if($langKey == 'en') $langKey = 'default';

    $displaySummary = $this->pObj->lDisplay['table.']['summary'];
    switch(true) {
      case($displaySummary):
        $summaryLL = $this->pObj->pi_getLL($view.'_'.$mode.'_summary', '['.$view.'_'.$mode.'_summary]');
        switch(true) {
          case($summaryLL == '['.$view.'_'.$mode.'_summary]'):
            if ($this->pObj->b_drs_localisation) {
              t3lib_div::devlog('[WARN/LOCALLANG] '.$view.'_'.$mode.'_summary hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
              t3lib_div::devlog('[INFO/LOCALISATION] If you use a table it won\'t have any summary.', $this->pObj->extKey, 0);
              t3lib_div::devlog('[INFO/LOCALISATION] This wouldn\'t according to the guidelines of the Web Accessibility Initiative (WAI)', $this->pObj->extKey, 0);
              $prompt = 'Please configure _LOCAL_LANG.'.$langKey.'.'.$view.'_'.$mode.'_summary.';
              t3lib_div::devlog('[HELP/LOCALLANG] '.$prompt, $this->pObj->extKey, 1);
            }
            $summary = '';
            break;
          default:
            $summary = ' summary="'.$summaryLL.'"';
            break;
        }
        break;
          default:
            $summary = '';
            break;
    }

    return $summary;

  }


  /**
 * Return the table caption out of the locallang_db.xml
 *
 * @param string    view: list or single
 * @return  string    summary
 */
  function tableCaption($view) {

    $mode = 'mode_'.$this->pObj->piVar_mode;

    $displayCaption = $this->pObj->lDisplay['table.']['caption'];
    switch(true) {
      case($displayCaption):
        $captionLL = $this->pObj->pi_getLL($view.'_'.$mode.'_caption', '['.$view.'_'.$mode.'_caption]');
        switch(true) {
          case($captionLL == '['.$view.'_'.$mode.'_caption]'):
            if ($this->pObj->b_drs_localisation) {
              t3lib_div::devlog('[WARN/LOCALLANG] '.$view.'_'.$mode.'_caption hasn\'t any value in _LOCAL_LANG', $this->pObj->extKey, 2);
              t3lib_div::devlog('[INFO/LOCALISATION] If you use a table it won\'t have any caption.', $this->pObj->extKey, 0);
              t3lib_div::devlog('[INFO/LOCALISATION] This wouldn\'t according to the guidelines of the Web Accessibility Initiative (WAI)', $this->pObj->extKey, 0);
            }
            $caption = '';
            break;
          default:
            $caption = '<caption>'.$captionLL.'</caption>';
            break;
        }
        break;
          default:
            $caption = '';
            break;
    }

    return $caption;
  }




  /**
 * Substitute marker ###TABLE.FIELD### with the value of table.field
 *
 * @param string    String with one or more table field markers
 * @param array   The single record
 * @return  string    String with one ore more table field values
 */
  function wrapTableFields($wrapThisString, $elements) {

    if (!is_array($elements)) {
      return $wrapThisString;
    }
    // Marker Array
    // Tecklenborg-Werft: ###TX_SHIPS_MAIN.G2_NAME### - Geschichte und Bilder
    foreach((array) $elements as $key => $value) {
      $markerArray['###'.strtoupper($key).'###'] = $value;
    }
    return $this->pObj->cObj->substituteMarkerArray($wrapThisString, $markerArray);
  }




















}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_wrapper.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_wrapper.php']);
}

?>
