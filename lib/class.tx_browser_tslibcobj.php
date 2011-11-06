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
 * Contains methods based on 
 *
 * class tslib_cObj			:		All main TypoScript features, rendering of content objects (cObjects). This class is the backbone of TypoScript Template rendering.
 *
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * 3895:     function filelink($theValue, $conf)
 *
 */



/**
 * This class contains all main TypoScript features.
 * This includes the rendering of TypoScript content objects (cObjects).
 * Is the backbone of TypoScript Template rendering.
 *
 * There are lots of functions you can use from your include-scripts.
 * The class "tslib_cObj" is normally instantiated and referred to as "cObj".
 * When you call your own PHP-code typically through a USER or USER_INT cObject then it is this class that instantiates the object and calls the main method. Before it does so it will set (if you are using classes) a reference to itself in the internal variable "cObj" of the object. Thus you can access all functions and data from this class by $this->cObj->... from within you classes written to be USER or USER_INT content objects.
 *
 * @author	Kasper Skårhøj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage tslib
 */
class tx_browser_tslibcobj extends tslib_cObj {


	/**
	 * Creates a list of links to files.
	 * Implements the stdWrap property "filelink"
	 *
	 * @param	string		The filename to link to, possibly prefixed with $conf[path]
	 * @param	array		TypoScript parameters for the TypoScript function ->filelink
	 * @return	string		The link to the file possibly with icons, thumbnails, size in bytes shown etc.
	 * @access private
	 * @see stdWrap()
	 */
	function filelink($theValue, $conf) {
		$conf['path'] = isset($conf['path.'])
			? $this->stdWrap($conf['path'], $conf['path.'])
			: $conf['path'];
		$theFile = trim($conf['path']) . $theValue;
		if (@is_file($theFile)) {
			$theFileEnc = str_replace('%2F', '/', rawurlencode($theFile));

				// the jumpURL feature will be taken care of by typoLink, only "jumpurl.secure = 1" is applyable needed for special link creation
			if ($conf['jumpurl.']['secure']) {
				$alternativeJumpUrlParameter = isset($conf['jumpurl.']['parameter.'])
					? $this->stdWrap($conf['jumpurl.']['parameter'], $conf['jumpurl.']['parameter.'])
					: $conf['jumpurl.']['parameter'];
				$typoLinkConf = array(
					'parameter' => ($alternativeJumpUrlParameter ? $alternativeJumpUrlParameter : ($GLOBALS['TSFE']->id . ',' . $GLOBALS['TSFE']->type)),
					'fileTarget' => $conf['target'],
					'ATagParams' => $this->getATagParams($conf),
					'additionalParams' => '&jumpurl=' . rawurlencode($theFileEnc) . $this->locDataJU($theFileEnc, $conf['jumpurl.']['secure.']) . $GLOBALS['TSFE']->getMethodUrlIdToken
				);
			} else {
				$typoLinkConf = array(
					'parameter' => $theFileEnc,
					'fileTarget' => $conf['target'],
					'ATagParams' => $this->getATagParams($conf)
				);
			}
// dwildt, 111104
//var_dump($typoLinkConf);
				// if the global jumpURL feature is activated, but is disabled for this
				// filelink, the global parameter needs to be disabled as well for this link creation
			$globalJumpUrlEnabled = $GLOBALS['TSFE']->config['config']['jumpurl_enable'];
			if ($globalJumpUrlEnabled && isset($conf['jumpurl']) && $conf['jumpurl'] == 0) {
				$GLOBALS['TSFE']->config['config']['jumpurl_enable'] = 0;
					// if the global jumpURL feature is deactivated, but is wanted for this link, then activate it for now
			} else if (!$globalJumpUrlEnabled && $conf['jumpurl']) {
				$GLOBALS['TSFE']->config['config']['jumpurl_enable'] = 1;
			}
			$theLinkWrap = $this->typoLink('|', $typoLinkConf);

				// now the original value is set again
			$GLOBALS['TSFE']->config['config']['jumpurl_enable'] = $globalJumpUrlEnabled;

			$theSize = filesize($theFile);
			$fI = t3lib_div::split_fileref($theFile);
			if ($conf['icon']) {
				$iconP = t3lib_extMgm::siteRelPath('cms') . 'tslib/media/fileicons/';
				$icon = @is_file($iconP . $fI['fileext'] . '.gif') ? $iconP . $fI['fileext'] . '.gif' : $iconP . 'default.gif';
					// Checking for images: If image, then return link to thumbnail.
				$IEList = isset($conf['icon_image_ext_list.'])
					? $this->stdWrap($conf['icon_image_ext_list'], $conf['icon_image_ext_list.'])
					: $conf['icon_image_ext_list'];
				$image_ext_list = str_replace(' ', '', strtolower($IEList));
				if ($fI['fileext'] && t3lib_div::inList($image_ext_list, $fI['fileext'])) {
					if ($conf['iconCObject']) {
						$icon = $this->cObjGetSingle($conf['iconCObject'], $conf['iconCObject.'], 'iconCObject');
					} else {
						if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['thumbnails']) {
							$thumbSize = '';
							if ($conf['icon_thumbSize'] || $conf['icon_thumbSize.']) {
								$thumbSize = '&size=' . (isset($conf['icon_thumbSize.'])
									? $this->stdWrap($conf['icon_thumbSize'], $conf['icon_thumbSize.'])
									: $conf['icon_thumbSize']);
							}
							$check = basename($theFile) . ':' . filemtime($theFile) . ':' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
							$md5sum = '&md5sum=' . t3lib_div::shortMD5($check);
							$icon = 't3lib/thumbs.php?dummy=' . $GLOBALS['EXEC_TIME'] . '&file=' .
								rawurlencode('../' . $theFile) . $thumbSize . $md5sum;
						} else {
							$icon = t3lib_extMgm::siteRelPath('cms') . 'tslib/media/miscicons/notfound_thumb.gif';
						}
						$icon = '<img src="' . htmlspecialchars($GLOBALS['TSFE']->absRefPrefix . $icon) . '"' .
							$this->getBorderAttr(' border="0"') . '' . $this->getAltParam($conf) . ' />';
					}
				} else {
					$icon = '<img src="' . htmlspecialchars($GLOBALS['TSFE']->absRefPrefix . $icon) .
						'" width="18" height="16"' . $this->getBorderAttr(' border="0"') .
						$this->getAltParam($conf) . ' />';
				}
				if ($conf['icon_link']) {
					$icon = $this->wrap($icon, $theLinkWrap);
				}
				$icon = isset($conf['icon.'])
					? $this->stdWrap($icon, $conf['icon.'])
					: $icon;
			}
			if ($conf['size']) {
				$size = isset($conf['size.'])
					? $this->stdWrap($theSize, $conf['size.'])
					: $theSize;
			}

				// Wrapping file label
			if ($conf['removePrependedNumbers']) {
				$theValue = preg_replace('/_[0-9][0-9](\.[[:alnum:]]*)$/', '\1', $theValue);
			}
			if(isset($conf['labelStdWrap.'])) {
				$theValue = $this->stdWrap($theValue, $conf['labelStdWrap.']);
			}

				// Wrapping file
			$wrap = isset($conf['wrap.'])
				? $this->stdWrap($conf['wrap'], $conf['wrap.'])
				: $conf['wrap'];
			if ($conf['ATagBeforeWrap']) {
				$theValue = $this->wrap($this->wrap($theValue, $wrap), $theLinkWrap);
			} else {
				$theValue = $this->wrap($this->wrap($theValue, $theLinkWrap), $wrap);
			}
			$file = isset($conf['file.'])
				? $this->stdWrap($theValue, $conf['file.'])
				: $theValue;
				// output
			$output = $icon . $file . $size;
			if(isset($conf['stdWrap.'])) {
				$output = $this->stdWrap($output, $conf['stdWrap.']);
			}

			return $output;
		}
	}




}

?>