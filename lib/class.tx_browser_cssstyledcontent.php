<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* Class extends tx_cssstyledcontent_pi1
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    browser
* @version 3.9.3
* @since 3.6.4
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_org_extmanager
 *   67:     function promptQuickstart()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



  // RETURN css_styled_content isn't installed
if(!file_exists(PATH_site . 'typo3/sysext/css_styled_content/pi1/class.tx_cssstyledcontent_pi1.php'))
{
  var_dump(__METHOD__ . '(' . __LINE__ .'): Browser - the TYPO3 frontend engine: [EN] You have to install CSS styled content! [DE] Bitte installiere CSS styled content!');
  return;
}

require_once(PATH_site . 'typo3/sysext/css_styled_content/pi1/class.tx_cssstyledcontent_pi1.php');

class tx_browser_cssstyledcontent extends tx_cssstyledcontent_pi1
{

    // The extension key.
  public $extKey        = 'browser';
  public $prefixId      = 'tx_browser_cssstyledcontent';
    // Path to any file in pi1 for locallang
  public $scriptRelPath = 'lib/class.tx_browser_cssstyledcontent.php';









 /**
  * render_uploads(): The method enables to link to files of each language at the same time.
  *                   The method is based on $this->render_uploads_per_language( ). See below.
  *                   Conditions
  *                   * userFunc.renderCurrentLanguageOnly has to be true
  *                   * the table sys_language has to contain one record at least
  *
  * @param       string          Content input. Not used, ignore.
  * @param       array           TypoScript configuration
  * @return      string          HTML output.
  * @version 3.9.3
  * @since 3.9.3
  * @access public
  */
  public function render_uploads( $content, $conf )
  {
//    $this->str_developer_csvIp = '79.237.182.65';
//    $pos = strpos($this->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ' , $this->str_developer_csvIp );
//    }

    $out = null;

      //////////////////////////////////////////////////////////////////////////
      //
      // Enable the DRS by TypoScript

    $bool_drs = false;
    if( isset( $conf['userFunc.']['drs'] ) )
    {
      $coa_name = $conf['userFunc.']['drs'];
      $coa_conf = $conf['userFunc.']['drs.'];
      $bool_drs = intval( $this->cObj->cObjGetSingle( $coa_name, $coa_conf, $TSkey='__' ) );
    }
    if( $bool_drs )
    {
      $this->helper_init_drs( );
    }
      // Enable the DRS by TypoScript



      //////////////////////////////////////////////////////////////////////////
      //
      // Init the browser localisation object

    require_once( PATH_typo3conf . 'ext/browser/pi1/class.tx_browser_pi1_localisation.php' );
    $this->objLocalise = new tx_browser_pi1_localisation ($this );
    require_once( PATH_typo3conf . 'ext/browser/pi1/class.tx_browser_pi1_zz.php' );
    $this->objZz = new tx_browser_pi1_zz ($this );
      // Init the browser localisation object



      //////////////////////////////////////////////////////////////////////////
      //
      // Link the file for the current language only (default)?

    $bool_currLangOnly = true;
    if( isset( $conf['userFunc.']['renderCurrentLanguageOnly'] ) )
    {
      $coa_name           = $conf['userFunc.']['renderCurrentLanguageOnly'];
      $coa_conf           = $conf['userFunc.']['renderCurrentLanguageOnly.'];
      $bool_currLangOnly  = intval( $this->cObj->cObjGetSingle( $coa_name, $coa_conf, $TSkey='__' ) );
    }
      // Link the file for the current language only (default)?



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the filelink for the current language only

    if( $bool_currLangOnly )
    {
      $out = $out . $this->render_uploads_per_language( $content, $conf );
      return $out;
    }
      // RETURN the filelink for the current language only



      //////////////////////////////////////////////////////////////////////////
      //
      // Get configured languages

    $llRows = $this->objLocalise->sql_getLanguages( );
      // Get configured languages



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the current table

    $table = 'no_table_is_defined';
    if( isset( $conf['userFunc.']['table'] ) )
    {
      $coa_name = $conf['userFunc.']['table'];
      $coa_conf = $conf['userFunc.']['table.'];
      $table    = $this->cObj->cObjGetSingle( $coa_name, $coa_conf, $TSkey='__' );
    }
      // Get the current table



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the current uid (of the default language record)

    $uid = 'no_record_is_defined';
    if( isset( $conf['userFunc.']['record'] ) )
    {
      $coa_name = $conf['userFunc.']['record'];
      $coa_conf = $conf['userFunc.']['record.'];
      $uid      = intval( $this->cObj->cObjGetSingle( $coa_name, $coa_conf, $TSkey='__' ) );
    }
      // Get the current uid (of the default language record)



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the select

    $select = 'no_seelct_is_defined';
    if( isset( $conf['userFunc.']['select'] ) )
    {
      $coa_name = $conf['userFunc.']['select'];
      $coa_conf = $conf['userFunc.']['select.'];
      $select   = $this->cObj->cObjGetSingle( $coa_name, $coa_conf, $TSkey='__' );
      $select   = $this->objZz->cleanUp_lfCr_doubleSpace( $select );
    }
      // Get the select


    
      //////////////////////////////////////////////////////////////////////////
      //
      // Get the configuration

    $userFunc_conf = $conf['userFunc.']['conf.'];
      // Get the configuration



      //////////////////////////////////////////////////////////////////////////
      //
      // Set and get localisation configuration

      // Remove 'L' from linkVars
    $str_linkVarsWoL                          = $this->helper_linkVarsWoL( );
      // Save the language id for the reset below
    $lang_id                                  = $this->objLocalise->lang_id;
      // Set and get localisation configuration



      //////////////////////////////////////////////////////////////////////////
      //
      // LOOP all languages

    foreach( $llRows as $flag => $arr_lang )
    {
        // Get the localised uid
        // Don't substitute non localised records with default language
      $this->objLocalise->int_localisation_mode = PI1_SELECTED_LANGUAGE_ONLY;
        // Set current language
      $this->objLocalise->lang_id               = intval( $llRows[$flag]['uid'] );
      $llUid = $this->objLocalise->get_localisedUid( $table, $uid );
        // Get the localised uid

        // CONTINUE there isn't any localised record
      if( empty( $llUid ) )
      {
        continue;
      }
        // CONTINUE there isn't any localised record

        // Set data of the localised record as a marker array
      $marker                             = null;
      $marker                             = $this->sql_marker( $select, $table, $llUid );
      $marker['###SYS_LANGUAGE.FLAG###']  = $llRows[$flag]['flag'];
      $marker['###SYS_LANGUAGE.TITLE###'] = $llRows[$flag]['title'];
        // Set data of the localised record as a marker array

        // Replace the marker in the TypoScript recursively
        // Workaround because of bug: $userFunc_conf will be changed, but it should not!
      $serialized_conf  = serialize( $userFunc_conf );
      $conf             = $this->cObj->substituteMarkerInObject( $userFunc_conf, $marker );
      $userFunc_conf    = unserialize( $serialized_conf );
        // Replace the marker in the TypoScript recursively

        // Update the linkVars
      $GLOBALS['TSFE']->linkVars = '&L=' . $llRows[$flag]['uid'] . $str_linkVarsWoL;

        // Render the $conf
      $llOut = $this->render_uploads_per_language( $content, $conf );
      
        // Concatenate the localized output
      $out = $out . $llOut;
    }
      // LOOP all languages



      //////////////////////////////////////////////////////////////////////////
      //
      // Reset some variables, which are changed above

    $this->objLocalise->int_localisation_mode = null;
    $this->objLocalise->lang_id               = $lang_id;
    $GLOBALS['TSFE']->linkVars                = $str_linkVars;
      // Reset some variables, which are changed above



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the content

    return $out;
      // RETURN the content
  }









 /**
  * render_uploads_per_language(): This method extends the origin render_uploads method (version TYPO3 4.5.0).
  *                                The method interprets the TypoScript of tt_content.uploads.20 in principle.
  *                                The origin method is limited for records from tt_content only.
  *                                This method extends it for using records of every table.
  *
  *                    If you like to use the method, you hav to configure this TypoScript snippet:
  *
  *                    tt_content.uploads.20 {
  *                      fields {
  *                        layout  (stdWrap) ->  0: link only, 1: with application icon, 2: with based icon
  *                                              i.e: ###TX_ORG_REPERTOIRE.DOCUMENTSLAYOUT###
  *                        files   (stdWrap) ->  name of the files
  *                                              i.e: ###TX_ORG_REPERTOIRE.DOCUMENTS###
  *                        caption (stdWrap) ->  caption of the files, devided by LF
  *                                              i.e: ###TX_ORG_REPERTOIRE.DOCUMENTSCAPTION###
  *                      }
  *                      tableField  (stdWrap) ->  current table.field.
  *                                                i.e. tx_org_repertoire.documents
  *                    }
  *
  * @param       string          Content input. Not used, ignore.
  * @param       array           TypoScript configuration
  * @return      string          HTML output.
  * @version 3.9.3
  * @since 3.6.4
  * @access public
  */
  private function render_uploads_per_language( $content, $conf )
  {

      // the result
    $out = '';

      // get layout type
      // 0: link only, 1: with application icon, 2: with based icon
    $type = intval($this->cObj->stdWrap($conf['fields.']['layout'], $conf['fields.']['layout.']));

      // set default path
    $path = 'uploads/media/';

      // get tableField
    $tableField = $this->cObj->stdWrap($conf['tableField'], $conf['tableField.']);
    list($table, $field) = explode('.', $tableField);
  
      // file path variable is set, this takes precedence
    $filePathConf = $this->cObj->stdWrap($conf['fields.']['from_path'], $conf['fields.']['from_path.']);
    if (!empty($filePathConf))
    {
      $fileList   = $this->cObj->filelist($filePathConf);
      list($path) = explode('|', $filePathConf);
    }
    
      // file path variable isn't set
    if (empty($filePathConf))
    {
        // Get the list of files from the field
      $fileList = trim($this->cObj->stdWrap($conf['fields.']['files'], $conf['fields.']['files.']));
        // Get the path
      if (is_array($GLOBALS['TCA'][$table]['columns'][$field]))
      {
        if(!empty($GLOBALS['TCA'][$table]['columns'][$field]['config']['uploadfolder'])) 
        {
            // in TCA-array folders are saved without trailing slash
          $path = $GLOBALS['TCA'][$table]['columns'][$field]['config']['uploadfolder'] . '/';
        }
      }
    }

      // explode into an array
    $fileArray = t3lib_div::trimExplode(',',$fileList,1);

      // there are files to list ...
    if (count($fileArray))
    {
        // the captions of the files
      $captions = $this->cObj->stdWrap($conf['fields.']['caption'], $conf['fields.']['caption.']);
      $captions = t3lib_div::trimExplode(LF, $captions);

        // Adding hardcoded TS to linkProc configuration
      $conf['linkProc.']['path.']['current']    = 1;
      $conf['linkProc.']['icon']                = 1;            // Always render icon - is inserted by PHP if needed.
      $conf['linkProc.']['icon.']['wrap']       = ' | //**//';  // Temporary, internal split-token!
      $conf['linkProc.']['icon_link']           = 1;            // Always link the icon
      $conf['linkProc.']['icon_image_ext_list'] = null;
        // Render a based icon if possible
      if($type == 2)
      {
        $conf['linkProc.']['icon_image_ext_list'] = $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'];
      }
        // stdWrap for the label
      if ($conf['labelStdWrap.']) 
      {
        $conf['linkProc.']['labelStdWrap.'] = $conf['labelStdWrap.'];
      }
      if ($conf['useSpacesInLinkText'] || $conf['stripFileExtensionFromLinkText']) 
      {
        $conf['linkProc.']['removePrependedNumbers'] = 0;
      }

        // dwildt, 111110, +
        // Get table.uid
      list( $cR_table, $cR_uid) = explode( ':', $GLOBALS['TSFE']->currentRecord );

        // Set marker array
      $flag = $GLOBALS['TSFE']->lang;
      if( empty( $flag ) )
      {
        $flag = 'gb';
      }
      $marker                             = null;
      $marker['###SYS_LANGUAGE.FLAG###']  = 'gb';
      $marker['###SYS_LANGUAGE.TITLE###'] = 'Any language is configured';
      if( isset ( $llRows[$flag] ) )
      {
        $marker['###SYS_LANGUAGE.FLAG###']  = $llRows[$flag]['flag'];
        $marker['###SYS_LANGUAGE.TITLE###'] = $llRows[$flag]['title'];
      }
      $marker['###TT_CONTENT.UID###']     = $cR_uid;
        // Set marker array

        // Replace the marker in the TypoScript recursively
        // Workaround because of bug: $splitConf[$key]['itemRendering.']
        // will be changed, but it should not!
      //$serialized_conf  = serialize( $splitConf[$key]['itemRendering.'] );
      $conf         = $this->cObj->substituteMarkerInObject
                          (
                            $conf,
                            $marker
                          );
      //$splitConf[$key]['itemRendering.'] = unserialize( $serialized_conf );
        // Replace the marker in the TypoScript recursively

        // Get configured languages
      $llRows = $this->objLocalise->sql_getLanguages( );
        // dwildt, 111110, +

        // LOOP: files
      $filesData = array();
      foreach($fileArray as $key => $fileName)
      {
        $absPath = t3lib_div::getFileAbsFileName($path.$fileName);

          // file is a file
        if (@is_file($absPath)) 
        {
          $path_info = pathinfo($fileName);
          $filesData[$key] = array();

          $filesData[$key]['filename']      = $fileName;
          $filesData[$key]['path']          = $path;
          $filesData[$key]['filesize']      = filesize($absPath);
          $filesData[$key]['fileextension'] = strtolower($path_info['extension']);
          $filesData[$key]['description']   = trim($captions[$key]);

          $this->cObj->setCurrentVal($path);
          $GLOBALS['TSFE']->register['ICON_REL_PATH'] = $path.$fileName;
          $GLOBALS['TSFE']->register['filename']      = $filesData[$key]['filename'];
          $GLOBALS['TSFE']->register['path']          = $filesData[$key]['path'];
          $GLOBALS['TSFE']->register['fileSize']      = $filesData[$key]['filesize'];
          $GLOBALS['TSFE']->register['fileExtension'] = $filesData[$key]['fileextension'];
          $GLOBALS['TSFE']->register['description']   = $filesData[$key]['description'];
// dwildt, 111106, -
//          $filesData[$key]['linkedFilenameParts']     = $this->beautifyFileLink
//                                                        (
//                                                          explode
//                                                          (
//                                                            '//**//',
//                                                            $this->cObj->filelink
//                                                            (
//                                                              $fileName,
//                                                              $conf['linkProc.']
//                                                            )
//                                                          ),
//                                                          $fileName,
//                                                          $conf['useSpacesInLinkText'],
//                                                          $conf['stripFileExtensionFromLinkText']
//                                                        );
// dwildt, 111106, -
// dwildt, 111106, +

            // Replace the URL, if there is a tx_browser_pi1 configuration
          $arr_filelinks = $this->helper_replace_url( $content, $conf, $key, $fileName );

            // Beautify the links
          $filesData[$key]['linkedFilenameParts'] = $this->beautifyFileLink
                                                    (
                                                      $arr_filelinks,
                                                      $fileName,
                                                      $conf['useSpacesInLinkText'],
                                                      $conf['stripFileExtensionFromLinkText']
                                                    );
// dwildt, 111106, +
        }
          // file is a file
      }
        // LOOP: files

        // optionSplit applied to conf to allow differnt settings per file
      $splitConf = $GLOBALS['TSFE']->tmpl->splitConfArray($conf, count($filesData));

        // render the list
      $outputEntries = array();

        // LOOP: files
      foreach($filesData as $key => $fileData)
      {
        $GLOBALS['TSFE']->register['linkedIcon']    = $fileData['linkedFilenameParts'][0];
        $GLOBALS['TSFE']->register['linkedLabel']   = $fileData['linkedFilenameParts'][1];
        $GLOBALS['TSFE']->register['filename']      = $fileData['filename'];
        $GLOBALS['TSFE']->register['path']          = $fileData['path'];
        $GLOBALS['TSFE']->register['description']   = $fileData['description'];
        $GLOBALS['TSFE']->register['fileSize']      = $fileData['filesize'];
        $GLOBALS['TSFE']->register['fileExtension'] = $fileData['fileextension'];

// dwildt, 111106, -
//        $outputEntries[]  = $this->cObj->cObjGetSingle
//                            (
//                              $splitConf[$key]['itemRendering'],
//                              $splitConf[$key]['itemRendering.']
//                            );
// dwildt, 111106, -
// dwildt, 111106, +

          // Set marker array
        $marker['###KEY###']                = $key;
        $marker['###FILENAME###']           = $fileName;
          // Set marker array

          // Replace the marker in the TypoScript recursively
          // Workaround because of bug: $splitConf[$key]['itemRendering.']
          // will be changed, but it should not!
        $serialized_conf  = serialize( $splitConf[$key]['itemRendering.'] );
        $coa_conf         = $this->cObj->substituteMarkerInObject
                            (
                              $splitConf[$key]['itemRendering.'],
                              $marker
                            );
        $splitConf[$key]['itemRendering.'] = unserialize( $serialized_conf );
          // Replace the marker in the TypoScript recursively

        $coa_name         = $splitConf[$key]['itemRendering'];
        $str_outputEntry  = $this->cObj->cObjGetSingle
                            (
                              $coa_name,
                              $coa_conf
                            );
        $outputEntries[]  = $str_outputEntry;
// dwildt, 111106, +
      }
        // LOOP: files
        // render the list

        // Wrap around the whole content
      if (isset($conf['outerWrap']))
      {
          // user defined outerWrap
        $outerWrap = $conf['outerWrap'];
      }
      if (!isset($conf['outerWrap']))
      {
          // default outer wrap: table tag params
        $tableTagParams           = $this->getTableAttributes($conf,$type);
        $tableTagParams['class']  = 'csc-uploads csc-uploads-'.$type;
        $outerWrap                = '<table ' . t3lib_div::implodeAttributes($tableTagParams) . '>|</table>';
      }
        // Wrap around the whole content

      $out = $this->cObj->wrap(implode('', $outputEntries), $outerWrap);
    }

      // stdWrap for the whole result
    if ($conf['stdWrap.']) 
    {
      $out = $this->cObj->stdWrap($out, $conf['stdWrap.']);
    }

        // Return the result
    return $out;
  }










  /***********************************************
   *
   * Helper
   *
   **********************************************/



  /**
   * helper_linkVarsWoL( ): Remove parameter 'L' from linkVars
   *
   * @return string         $str_linkVarsWoL: linkVars without 'L'
   * @access private
   */
  private function helper_linkVarsWoL( )
  {
      // Get linkVars
    $str_linkVars = $GLOBALS['TSFE']->linkVars;

      // LOOP linkVars: remove 'L'
    $arr_linkVars = explode( '&', $str_linkVars );
    foreach( $arr_linkVars as $str_linkVar )
    {
      list( $key_linkVar, $value_linkVar ) = explode( '=', $str_linkVar );
        // remove 'L'
      if( $key_linkVar != 'L' && ! empty( $key_linkVar ) )
      {
        $arr_linkVarsWoL[] = $key_linkVar . '=' . $value_linkVar;
      }
        // remove 'L'
    }
      // LOOP linkVars: remove 'L'

      // Set linkVars without 'L'
    $str_linkVarsWoL = implode( '&', $arr_linkVarsWoL );
    if( ! empty( $str_linkVarsWoL ) )
    {
      $str_linkVarsWoL = '&' . $str_linkVarsWoL;
    }
      // Set linkVars without 'L'

      // DRS - Development Reporting System
    if ( $this->b_drs_localisation )
    {
      if ( $str_linkVars != $str_linkVarsWoL )
      {
        $prompt = '\'L=' . $GLOBALS['TSFE']->sys_language_content . '\' is removed temporarily from linkVars.';
        t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->extKey, 0);
      }
    }
      // DRS - Development Reporting System

      // RETURN linkVars without 'L'
    return $str_linkVarsWoL;
  }








  /**
   * helper_init_drs( ): Init the DRS - Development Reportinmg System
   *
   * @return void
   * @access private
   */
  private function helper_init_drs( )
  {
    $this->b_drs_error        = true;
    $this->b_drs_warn         = true;
    $this->b_drs_info         = true;
    $this->b_drs_download     = true;
    $this->b_drs_localisation = true;
    $this->b_drs_statistics   = true;
    $prompt_01 = 'The DRS - Development Reporting System is enabled by TypoScript.';
    $prompt_02 = 'Change it: Please look for userFunc = tx_browser_cssstyledcontent->render_uploads and for userFunc.drs.';
    t3lib_div::devlog('[INFO/DRS] ' . $prompt_01, $this->extKey, 0);
    t3lib_div::devlog('[HELP/DRS] ' . $prompt_02, $this->extKey, 1);
  }








  /**
   * helper_replace_url( ):  This method replaces the url in an HTML link.
   *
   * @param       string          $content:   Content input. Not used, ignore.
   * @param       array           $conf:      TypoScript configuration
   * @param       array           $key:       Position of current document
   * @param       array           $fileName:  Filename of current document
   * @return      string          Replaced URL
   * @access private
   */
  private function helper_replace_url( $content, $conf, $key, $fileName )
  {
      // Link the current file with and without an icon (two links)
    $str_filelinks = $this->cObj->filelink( $fileName, $conf['linkProc.'] );
      // Devide the two rendered links from a string to two elements
    list( $arr_filelinks[0], $arr_filelinks[1] ) = explode( '//**//', $str_filelinks );

      // Replace the URL: there is a tx_browser_pi1 configuration
    if( isset( $conf['linkProc.']['tx_browser_pi1'] ) )
    {
        // Loop the links (with and without icon)
      foreach( $arr_filelinks as $key_filelinks => $value_filelinks)
      {
          // Current link
        $arr_link_current = explode( '"', $arr_filelinks[$key_filelinks]);

          // ERROR: prompt. Don't change anything
        if( $arr_link_current[0] != '<a href=' )
        {
          echo 'TYPO3-Browser ERROR:<br />' .
            'First element of the current array has to be "<a href=" but it is "'. $arr_link_current[0] . '"<br />' .
            'TypoScript configuration will be ignored.<br />' .
            __METHOD__ . ' (' . __LINE__ . ')';
          continue;
        }
          // ERROR: prompt. Don't change anything

            // Set marker array
          $marker['###KEY###']                = $key;
          $marker['###FILENAME###']           = $fileName;
            // Set marker array

            // Replace the marker in the TypoScript recursively
            // Workaround because of bug: $splitConf[$key]['itemRendering.']
            // will be changed, but it should not!
          $serialized_conf  = serialize( $conf['linkProc.']['tx_browser_pi1.'] );
          $coa_conf         = $this->cObj->substituteMarkerInObject
                              (
                                $conf['linkProc.']['tx_browser_pi1.'],
                                $marker
                              );
          $conf['linkProc.']['tx_browser_pi1.'] = unserialize( $serialized_conf );
            // Replace the marker in the TypoScript recursively

        $coa_name = $conf['linkProc.']['tx_browser_pi1'];

          // Get the tx_browser_pi1 configuration
        $str_url              = $this->cObj->cObjGetSingle($coa_name, $coa_conf );
        $arr_link_current[1]  = $str_url;
          // Get the tx_browser_pi1 configuration

          // Update the current rendered link
        $arr_filelinks[$key_filelinks]  = implode( '"', $arr_link_current);
      }
        // Loop the links (with and without icon)
    }
      // Replace the URL: there is a tx_browser_pi1 configuration

    return ( $arr_filelinks );
  }








  /***********************************************
   *
   * SQL
   *
   **********************************************/



 /**
  * sql_marker( ):  The method select the values of the given table and select and
  *                 returns the values as a marker array
  *
  * @param  string       $select_fields:  fields for the SQL select
  * @param  string       $from_table:     table for the SQL from
  * @param  integer      $llUid:          uid of the localised record
  * @return array        $marker:         Array with the elements '###FIELD###' => 'value'
  * @version 3.9.3
  * @since 3.9.3
  * @access public
  */
  public function sql_marker( $select_fields, $from_table, $llUid )
  {
    $marker = null;
    
      ////////////////////////////////////////////////////////////////////////////////
      //
      // Set the query

      // Values
    $where_clause   = 'uid = ' . intval( $llUid ) . '';
    $groupBy        = null;
    $orderBy        = null;
    $limit          = null;
      // Values

      // Query for evaluation
    $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Query for evaluation

      // DRS - Development Reporting System
    if ( $this->pObj->b_drs_localisation || $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog('[INFO/SQL+LOCALISATION] ' . $query, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
      // Set the query



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Execute the query

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute the query



      ///////////////////////////////////////////////////////////////////////////////
      //
      // ERROR

      // ERROR: debug report in the frontend
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    if( ! empty( $error ) )
    {
//      if( $this->debugging )
//      {
        //$str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
        //$str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
        //echo $str_warn.$str_header.$str_prompt;
        echo $str_prompt;
//      }
    }
      // ERROR: debug report in the frontend

      // DRS - Development Reporting System
    if( ! empty( $error ) )
    {
      if( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog('[ERROR/SQL] '. $query,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '. $error,  $this->pObj->extKey, 3);
      }
    }
      // DRS - Development Reporting System
      // ERROR



      //////////////////////////////////////////////////////////////////////////
      //
      // Handle the SQL result

      // Fetch one row only
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

      // Set the marker array
    foreach( $row as $key => $value )
    {
      $marker['###TABLE.' . strtoupper( $key ) . '###'] = $value;
    }
      // Set the marker array
      // Handle the SQL result



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the marker array

    return $marker;
      // RETURN the marker array
  }







  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php']);
}

?>
