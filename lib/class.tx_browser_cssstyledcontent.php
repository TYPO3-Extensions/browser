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
* Class provides methods for the TCA.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    org
* @version 0.3.1
* @since 0.3.1
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
 
 
//require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(PATH_site . 'typo3/sysext/css_styled_content/pi1/class.tx_cssstyledcontent_pi1.php');

class tx_browser_cssstyledcontent extends tx_cssstyledcontent_pi1
{

    // The extension key.
  public $extKey        = 'browser';
  public $prefixId      = 'tx_browser_cssstyledcontent';
    // Path to any file in pi1 for locallang
  public $scriptRelPath = 'lib/class.tx_browser_cssstyledcontent.php';


        /**
         * Rendering the "Filelinks" type content element, called from TypoScript (tt_content.uploads.20)
         *
         * @param       string          Content input. Not used, ignore.
         * @param       array           TypoScript configuration
         * @return      string          HTML output.
         * @access private
         */
        function render_uploads($content,$conf) {

                        // Look for hook before running default code for function
                if ($hookObj = $this->hookRequest('render_uploads')) {
                        return $hookObj->render_uploads($content,$conf);
                } else {

                        $out = '';

                                // Set layout type:
                        $type = intval($this->cObj->data['layout']);

                                // see if the file path variable is set, this takes precedence
                        $filePathConf = $this->cObj->stdWrap($conf['filePath'], $conf['filePath.']);
                        if ($filePathConf) {
                                $fileList = $this->cObj->filelist($filePathConf);
                                list($path) = explode('|', $filePathConf);
                        } else {
                                        // Get the list of files from the field
//                              $field = (trim($conf['field']) ? trim($conf['field']) : 'media');
//                              $fileList = $this->cObj->data[$field];
// dwildt, 110322
if(!empty($conf['value']))
{
  $fileList = trim($conf['value']);
  $type = 2;
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $fileList);
}
if(empty($conf['value']))
{
  $field = (trim($conf['field']) ? trim($conf['field']) : 'media');
  $fileList = $this->cObj->data[$field];
}
                                t3lib_div::loadTCA('tt_content');
                                $path = 'uploads/media/';
                                if (is_array($GLOBALS['TCA']['tt_content']['columns'][$field]) && 
!empty($GLOBALS['TCA']['tt_content']['columns'][$field]['config']['uploadfolder'])) {
                                        // in TCA-Array folders are saved without trailing slash, so $path.$fileName won't work
                                    $path = $GLOBALS['TCA']['tt_content']['columns'][$field]['config']['uploadfolder'] .'/';
                                }
                        }
if(!empty($conf['value']))
{
  $path = 'uploads/tx_org/';
}
                        $path = trim($path);

                                // Explode into an array:
                        $fileArray = t3lib_div::trimExplode(',',$fileList,1);
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $fileArray);
}

                                // If there were files to list...:
                        if (count($fileArray))  {

                                        // Get the descriptions for the files (if any):
                                $descriptions = t3lib_div::trimExplode(LF,$this->cObj->data['imagecaption']);

                                        // Adding hardcoded TS to linkProc configuration:
                                $conf['linkProc.']['path.']['current'] = 1;
                                $conf['linkProc.']['icon'] = 1; // Always render icon - is inserted by PHP if needed.
                                $conf['linkProc.']['icon.']['wrap'] = ' | //**//';      // Temporary, internal split-token!
                                $conf['linkProc.']['icon_link'] = 1;    // ALways link the icon
                                $conf['linkProc.']['icon_image_ext_list'] = ($type==2 || $type==3) ? 
$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] : '';       // If the layout is type 2 or 3 we will render an image based icon if possible.
                                if ($conf['labelStdWrap.']) {
                                        $conf['linkProc.']['labelStdWrap.'] = $conf['labelStdWrap.'];
                                }
                                if ($conf['useSpacesInLinkText'] || $conf['stripFileExtensionFromLinkText']) {
                                        $conf['linkProc.']['removePrependedNumbers'] = 0;
                                }

                                        // Traverse the files found:
                                $filesData = array();
                                foreach($fileArray as $key => $fileName)        {
                                        $absPath = t3lib_div::getFileAbsFileName($path.$fileName);
// dwildt, 110322
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $path.$fileName);
}
                                        if (@is_file($absPath)) {
                                                $fI = pathinfo($fileName);
                                                $filesData[$key] = array();

                                                $filesData[$key]['filename'] = $fileName;
                                                $filesData[$key]['path'] = $path;
                                                $filesData[$key]['filesize'] = filesize($absPath);
                                                $filesData[$key]['fileextension'] = strtolower($fI['extension']);
                                                $filesData[$key]['description'] = trim($descriptions[$key]);

                                                $this->cObj->setCurrentVal($path);
                                                $GLOBALS['TSFE']->register['ICON_REL_PATH'] = $path.$fileName;
                                                $GLOBALS['TSFE']->register['filename'] = $filesData[$key]['filename'];
                                                $GLOBALS['TSFE']->register['path'] = $filesData[$key]['path'];
                                                $GLOBALS['TSFE']->register['fileSize'] = $filesData[$key]['filesize'];
                                                $GLOBALS['TSFE']->register['fileExtension'] = $filesData[$key]['fileextension'];
                                                $GLOBALS['TSFE']->register['description'] = $filesData[$key]['description'];
                                                $filesData[$key]['linkedFilenameParts']
                                                        = $this->beautifyFileLink(
                                                                explode(
                                                                        '//**//',
                                                                        $this->cObj->filelink(
                                                                                $fileName, $conf['linkProc.']
                                                                        )
                                                                ),
                                                                $fileName,
                                                                $conf['useSpacesInLinkText'],
                                                                $conf['stripFileExtensionFromLinkText']
                                                        );
                                        }
                                }

                                        // optionSplit applied to conf to allow differnt settings per file
                                $splitConf = $GLOBALS['TSFE']->tmpl->splitConfArray($conf, count($filesData));
// dwildt, 110322
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $filesData);
}

                                        // Now, lets render the list!
                                $outputEntries = array();
                                foreach($filesData as $key => $fileData)        {
                                        $GLOBALS['TSFE']->register['linkedIcon'] = $fileData['linkedFilenameParts'][0];
                                        $GLOBALS['TSFE']->register['linkedLabel'] = $fileData['linkedFilenameParts'][1];
                                        $GLOBALS['TSFE']->register['filename'] = $fileData['filename'];
                                        $GLOBALS['TSFE']->register['path'] = $fileData['path'];
                                        $GLOBALS['TSFE']->register['description'] = $fileData['description'];
                                        $GLOBALS['TSFE']->register['fileSize'] = $fileData['filesize'];
                                        $GLOBALS['TSFE']->register['fileExtension'] = $fileData['fileextension'];
// dwildt, 110322
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $splitConf[$key]);
}
                                        $outputEntries[] = $this->cObj->cObjGetSingle($splitConf[$key]['itemRendering'], 
$splitConf[$key]['itemRendering.']);
                                }
// dwildt, 110322
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $outputEntries);
}

                                if (isset($conf['outerWrap']))  {
                                                // Wrap around the whole content
                                        $outerWrap = $conf['outerWrap'];
                                } else  {
                                                // Table tag params
                                        $tableTagParams = $this->getTableAttributes($conf,$type);
                                        $tableTagParams['class'] = 'csc-uploads csc-uploads-'.$type;
                                        $outerWrap = '<table ' . t3lib_div::implodeAttributes($tableTagParams) . '>|</table>';
                                }

                                        // Compile it all into table tags:
                                $out = $this->cObj->wrap(implode('', $outputEntries), $outerWrap);
// dwildt, 110322
if(!empty($conf['value']))
{
  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $out);
}
                        }

                                // Calling stdWrap:
                        if ($conf['stdWrap.']) {
                                $out = $this->cObj->stdWrap($out, $conf['stdWrap.']);
                        }

                                // Return value
                        return $out;
                }
        }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_typoscript.php']);
}

?>
