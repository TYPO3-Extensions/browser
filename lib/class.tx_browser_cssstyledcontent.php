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
* @version 3.6.4
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
   * render_uploads():  This method extends the origin render_uploads method (version TYPO3 4.5.0).
   *                    The method interprets the TypoScript of tt_content.uploads.20 in principle.
   *                    The origin method is limited for records from tt_content only.
   *                    This method extends it for using records of every table.
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
   * @access private
   */
  function render_uploads($content,$conf) 
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
          $arr_filelinks = $this->helper_replace_url( $content, $conf, $key, $filename );

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

        $outputEntries[]  = $this->cObj->cObjGetSingle
                            (
                              $splitConf[$key]['itemRendering'], 
                              $splitConf[$key]['itemRendering.']
                            );
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
   * helper_replace_url( ):  This method replaces the url in an HTML link.
   *
   * @param       string          $content:   Content input. Not used, ignore.
   * @param       array           $conf:      TypoScript configuration
   * @param       array           $key:       Position of current document
   * @param       array           $filename:  Filename of current document
   * @return      string          Replaced URL
   * @access private
   */
  function helper_replace_url( $content, $conf, $key, $filename )
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

          // Get the tx_browser_pi1 configuration
        $conf_browser         = $this->cObj->cObjGetSingle($conf['linkProc.']['tx_browser_pi1'], $conf['linkProc.']['tx_browser_pi1.'] );
        $conf_browser         = str_replace( '###KEY###',       $key,       $conf_browser );
        $conf_browser         = str_replace( '###FILENAME###',  $filename,  $conf_browser );
        $arr_link_current[1]  = $conf_browser;
          // Get the tx_browser_pi1 configuration

          // Update the current rendered link
        $arr_filelinks[$key_filelinks]  = implode( '"', $arr_link_current);
      }
        // Loop the links (with and without icon)
    }
      // Replace the URL: there is a tx_browser_pi1 configuration

    return ( $arr_filelinks );
  }








  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php']);
}

?>
