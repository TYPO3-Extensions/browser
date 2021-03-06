<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012-2016 - Dirk Wildt, Die Netzmacher <http://wildt.at.die-netzmacher.de>
*  (c) 1999-2011 - Kasper SkÃ¥rhÃžj (kasperYYYY@typo3.com)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Content rendering' for the 'css_styled_content' extension.
 *
 * $Id$
 *
 * @author	Kasper SkÃ¥rhÃžj <kasperYYYY@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_browser_cssstyledcontent extends tslib_pibase
 *
 *              SECTION: Rendering of Content Elements:
 *   96:     function render_bullets($content,$conf)
 *  141:     function render_table($content,$conf)
 *  283:     function render_uploads($content,$conf)
 *  395:     function render_textpic($content, $conf)
 *
 *              SECTION: Helper functions
 *  832:     function getTableAttributes($conf,$type)
 *  861:     function &hookRequest($functionName)
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
/**
 * Plugin class - instantiated from TypoScript.
 * Rendering some content elements from tt_content table.
 *
 * @author	Kasper SkÃ¥rhÃžj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage tx_cssstyledcontent
 * @version 6.0.0
 */
class tx_browser_cssstyledcontent extends tslib_pibase {

    // The extension key.
  public $extKey        = 'browser';
  public $prefixId      = 'tx_browser_cssstyledcontent';
    // Path to any file in pi1 for locallang
  public $scriptRelPath = 'lib/class.tx_browser_cssstyledcontent.php';

 /**
  * Backup of cObj->data
  *
  * @var array
  */
  private $bakCObjData = null;
	var $conf = array();







	/***********************************
	 *
	 * Rendering of Content Elements:
	 *
	 ***********************************/

	/**
	 * Rendering the "Bulletlist" type content element, called from TypoScript (tt_content.bullets.20)
	 *
	 * @param	string		Content input. Not used, ignore.
	 * @param	array		TypoScript configuration
	 * @return	string		HTML output.
	 * @access private
	 */
	function render_bullets($content,$conf)	{

			// Look for hook before running default code for function
		if ($hookObj = $this->hookRequest('render_bullets')) {
			return $hookObj->render_bullets($content,$conf);
		} else {

				// Get bodytext field content, returning blank if empty:
			$field = (isset($conf['field']) && trim($conf['field']) ? trim($conf['field']) : 'bodytext');
			$content = trim($this->cObj->data[$field]);
			if (!strcmp($content,''))	return '';

				// Split into single lines:
			$lines = t3lib_div::trimExplode(LF,$content);
			foreach($lines as &$val)	{
				$val = '<li>'.$this->cObj->stdWrap($val,$conf['innerStdWrap.']).'</li>';
			}

				// Set header type:
			$type = intval($this->cObj->data['layout']);

				// Compile list:
			$out = '
				<ul class="csc-bulletlist csc-bulletlist-'.$type.'">'.
					implode('',$lines).'
				</ul>';

				// Calling stdWrap:
			if ($conf['stdWrap.']) {
				$out = $this->cObj->stdWrap($out, $conf['stdWrap.']);
			}

				// Return value
			return $out;
		}
	}

	/**
	 * returns an array containing width relations for $colCount columns.
	 *
	 * tries to use "colRelations" setting given by TS.
	 * uses "1:1" column relations by default.
	 *
	 * @param array $conf TS configuration for img
	 * @param int $colCount number of columns
	 * @return array
	 */
	protected function getImgColumnRelations($conf, $colCount) {
		$relations = array();
		$equalRelations= array_fill(0, $colCount, 1);
		$colRelationsTypoScript = trim($this->cObj->stdWrap($conf['colRelations'], $conf['colRelations.']));

		if ($colRelationsTypoScript) {
				// try to use column width relations given by TS
			$relationParts = explode(':', $colRelationsTypoScript);
				// enough columns defined?
			if (count($relationParts) >= $colCount) {
				$out = array();
				for ($a = 0; $a < $colCount; $a++) {
					$currentRelationValue = intval($relationParts[$a]);
					if ($currentRelationValue >= 1) {
						$out[$a] = $currentRelationValue;
					} else {
						t3lib_div::devLog('colRelations used with a value smaller than 1 therefore colRelations setting is ignored.', $this->extKey, 2);
						unset($out);
						break;
					}
				}
				if (max($out) / min($out) <= 10) {
					$relations = $out;
				} else {
					t3lib_div::devLog('The difference in size between the largest and smallest colRelation was not within a factor of ten therefore colRelations setting is ignored..', $this->extKey, 2);
				}
			}
		}
		return $relations ? $relations : $equalRelations;
	}

	/**
	 * returns an array containing the image widths for an image row with $colCount columns.
	 *
	 * @param array $conf TS configuration of img
	 * @param int $colCount number of columns
	 * @param int $netW max usable width for images (without spaces and borders)
	 * @return array
	 */
	protected function getImgColumnWidths($conf, $colCount, $netW) {
		$columnWidths = array();
		$colRelations = $this->getImgColumnRelations($conf, $colCount);

		$accumWidth = 0;
		$accumDesiredWidth = 0;
		$relUnitCount = array_sum($colRelations);

		for ($a = 0; $a < $colCount; $a++)	{
			$availableWidth = $netW - $accumWidth; // this much width is available for the remaining images in this row (int)
			$desiredWidth = $netW / $relUnitCount * $colRelations[$a]; // theoretical width of resized image. (float)
			$accumDesiredWidth += $desiredWidth; // add this width. $accumDesiredWidth becomes the desired horizontal position
				// calculate width by comparing actual and desired horizontal position.
				// this evenly distributes rounding errors across all images in this row.
			$suggestedWidth = round($accumDesiredWidth - $accumWidth);
			$finalImgWidth = (int) min($availableWidth, $suggestedWidth); // finalImgWidth may not exceed $availableWidth
			$accumWidth += $finalImgWidth;
			$columnWidths[$a] = $finalImgWidth;
		}
		return $columnWidths;
	}

	/**
	 * Rendering the IMGTEXT content element, called from TypoScript (tt_content.textpic.20)
	 *
	 * @param	string		Content input. Not used, ignore.
	 * @param	array		TypoScript configuration. See TSRef "IMGTEXT". This function aims to be compatible.
	 * @return	string		HTML output.
	 * @access private
	 * @coauthor	Ernesto Baschny <ernst@cron-it.de>
	 */
	 function render_textpic($content, $conf)	{
			// Look for hook before running default code for function
		if (method_exists($this, 'hookRequest') && $hookObj = $this->hookRequest('render_textpic')) {
			return $hookObj->render_textpic($content,$conf);
		}

		$renderMethod = $this->cObj->stdWrap($conf['renderMethod'], $conf['renderMethod.']);

			// Render using the default IMGTEXT code (table-based)
		if (!$renderMethod || $renderMethod == 'table')	{
			return $this->cObj->IMGTEXT($conf);
		}

			// Specific configuration for the chosen rendering method
		if (is_array($conf['rendering.'][$renderMethod . '.']))	{
			$conf = $this->cObj->joinTSarrays($conf, $conf['rendering.'][$renderMethod . '.']);
		}

			// Image or Text with Image?
		if (is_array($conf['text.']))	{
			$content = $this->cObj->stdWrap($this->cObj->cObjGet($conf['text.'], 'text.'), $conf['text.']);
		}

		$imgList = trim($this->cObj->stdWrap($conf['imgList'], $conf['imgList.']));

		if (!$imgList)	{
				// No images, that's easy
			if (is_array($conf['stdWrap.']))	{
				return $this->cObj->stdWrap($content, $conf['stdWrap.']);
			}
			return $content;
		}

		$imgs = t3lib_div::trimExplode(',', $imgList);
		$imgStart = intval($this->cObj->stdWrap($conf['imgStart'], $conf['imgStart.']));
		$imgCount = count($imgs) - $imgStart;
		$imgMax = intval($this->cObj->stdWrap($conf['imgMax'], $conf['imgMax.']));
		if ($imgMax)	{
      // #61644, dwildt, 1-
			//$imgCount = t3lib_div::intInRange($imgCount, 0, $imgMax);	// reduce the number of images.
      // #61644, dwildt, 1+
			$imgCount = t3lib_utility_Math::forceIntegerInRange($imgCount, 0, $imgMax);	// reduce the number of images.
		}

		$imgPath = $this->cObj->stdWrap($conf['imgPath'], $conf['imgPath.']);

			// Does we need to render a "global caption" (below the whole image block)?
		$renderGlobalCaption = !$conf['captionSplit'] && !$conf['imageTextSplit'] && is_array($conf['caption.']);
		if ($imgCount == 1) {
				// If we just have one image, the caption relates to the image, so it is not "global"
			$renderGlobalCaption = false;
		}

			// Use the calculated information (amount of images, if global caption is wanted) to choose a different rendering method for the images-block
		$GLOBALS['TSFE']->register['imageCount'] = $imgCount;
		$GLOBALS['TSFE']->register['renderGlobalCaption'] = $renderGlobalCaption;
		if ($conf['fallbackRendering']) {
			$fallbackRenderMethod = $this->cObj->cObjGetSingle($conf['fallbackRendering'], $conf['fallbackRendering.']);
		}
		if ($fallbackRenderMethod && is_array($conf['rendering.'][$fallbackRenderMethod . '.']))	{
			$conf = $this->cObj->joinTSarrays($conf, $conf['rendering.'][$fallbackRenderMethod . '.']);
		}

			// Global caption
		$globalCaption = '';
		if ($renderGlobalCaption)	{
			$globalCaption = $this->cObj->stdWrap($this->cObj->cObjGet($conf['caption.'], 'caption.'), $conf['caption.']);
		}

			// Positioning
		$position = $this->cObj->stdWrap($conf['textPos'], $conf['textPos.']);

		$imagePosition = $position&7;	// 0,1,2 = center,right,left
		$contentPosition = $position&24;	// 0,8,16,24 (above,below,intext,intext-wrap)
		$align = $this->cObj->align[$imagePosition];
		$textMargin = intval($this->cObj->stdWrap($conf['textMargin'],$conf['textMargin.']));
		if (!$conf['textMargin_outOfText'] && $contentPosition < 16)	{
			$textMargin = 0;
		}

		$colspacing = intval($this->cObj->stdWrap($conf['colSpace'], $conf['colSpace.']));
		$rowspacing = intval($this->cObj->stdWrap($conf['rowSpace'], $conf['rowSpace.']));

		$border = intval($this->cObj->stdWrap($conf['border'], $conf['border.'])) ? 1:0;
		$borderColor = $this->cObj->stdWrap($conf['borderCol'], $conf['borderCol.']);
		$borderThickness = intval($this->cObj->stdWrap($conf['borderThick'], $conf['borderThick.']));

		$borderColor = $borderColor?$borderColor:'black';
		$borderThickness = $borderThickness?$borderThickness:1;
		$borderSpace = (($conf['borderSpace']&&$border) ? intval($conf['borderSpace']) : 0);

			// Generate cols
		$cols = intval($this->cObj->stdWrap($conf['cols'],$conf['cols.']));
		$colCount = ($cols > 1) ? $cols : 1;
		if ($colCount > $imgCount)	{$colCount = $imgCount;}
		$rowCount = ceil($imgCount / $colCount);

			// Generate rows
		$rows = intval($this->cObj->stdWrap($conf['rows'],$conf['rows.']));
		if ($rows>1)	{
			$rowCount = $rows;
			if ($rowCount > $imgCount)	{$rowCount = $imgCount;}
			$colCount = ($rowCount>1) ? ceil($imgCount / $rowCount) : $imgCount;
		}

			// Max Width
		$maxW = intval($this->cObj->stdWrap($conf['maxW'], $conf['maxW.']));

		if ($contentPosition>=16)	{	// in Text
			$maxWInText = intval($this->cObj->stdWrap($conf['maxWInText'],$conf['maxWInText.']));
			if (!$maxWInText)	{
					// If maxWInText is not set, it's calculated to the 50% of the max
				$maxW = round($maxW/100*50);
			} else {
				$maxW = $maxWInText;
			}
		}

			// max usuable width for images (without spacers and borders)
		$netW = $maxW - $colspacing * ($colCount - 1) - $colCount * $border * ($borderThickness + $borderSpace) * 2;

			// Specify the maximum width for each column
		$columnWidths = $this->getImgColumnWidths($conf, $colCount, $netW);

		$image_compression = intval($this->cObj->stdWrap($conf['image_compression'],$conf['image_compression.']));
		$image_effects = intval($this->cObj->stdWrap($conf['image_effects'],$conf['image_effects.']));
		$image_frames = intval($this->cObj->stdWrap($conf['image_frames.']['key'],$conf['image_frames.']['key.']));

			// EqualHeight
		$equalHeight = intval($this->cObj->stdWrap($conf['equalH'],$conf['equalH.']));
		if ($equalHeight)	{
				// Initiate gifbuilder object in order to get dimensions AND calculate the imageWidth's
			$gifCreator = t3lib_div::makeInstance('tslib_gifbuilder');
			$gifCreator->init();
			$relations_cols = Array();
			$imgWidths = array(); // contains the individual width of all images after scaling to $equalHeight
			for ($a=0; $a<$imgCount; $a++)	{
				$imgKey = $a+$imgStart;
				$imgInfo = $gifCreator->getImageDimensions($imgPath.$imgs[$imgKey]);
				$rel = $imgInfo[1] / $equalHeight;	// relationship between the original height and the wished height
				if ($rel)	{	// if relations is zero, then the addition of this value is omitted as the image is not expected to display because of some error.
					$imgWidths[$a] = $imgInfo[0] / $rel;
					$relations_cols[floor($a/$colCount)] += $imgWidths[$a];	// counts the total width of the row with the new height taken into consideration.
				}
			}
		}

			// Fetches pictures
		$splitArr = array();
		$splitArr['imgObjNum'] = $conf['imgObjNum'];
		$splitArr = $GLOBALS['TSFE']->tmpl->splitConfArray($splitArr, $imgCount);

		$imageRowsFinalWidths = Array();	// contains the width of every image row
		$imgsTag = array();		// array index of $imgsTag will be the same as in $imgs, but $imgsTag only contains the images that are actually shown
		$origImages = array();
		$rowIdx = 0;
		for ($a=0; $a<$imgCount; $a++)	{
			$imgKey = $a+$imgStart;
			$totalImagePath = $imgPath.$imgs[$imgKey];

			$GLOBALS['TSFE']->register['IMAGE_NUM'] = $imgKey;	// register IMG_NUM is kept for backwards compatibility
			$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $imgKey;
			$GLOBALS['TSFE']->register['ORIG_FILENAME'] = $totalImagePath;

			$this->cObj->data[$this->cObj->currentValKey] = $totalImagePath;
			$imgObjNum = intval($splitArr[$a]['imgObjNum']);
			$imgConf = $conf[$imgObjNum.'.'];

			if ($equalHeight)	{

				if ($a % $colCount == 0) {
						// a new row startsS
					$accumWidth = 0; // reset accumulated net width
					$accumDesiredWidth = 0; // reset accumulated desired width
					$rowTotalMaxW = $relations_cols[$rowIdx];
					if ($rowTotalMaxW > $netW && $netW > 0) {
						$scale = $rowTotalMaxW / $netW;
					} else {
						$scale = 1;
					}
					$desiredHeight = $equalHeight / $scale;
					$rowIdx++;
				}

				$availableWidth= $netW - $accumWidth; // this much width is available for the remaining images in this row (int)
				$desiredWidth= $imgWidths[$a] / $scale; // theoretical width of resized image. (float)
				$accumDesiredWidth += $desiredWidth; // add this width. $accumDesiredWidth becomes the desired horizontal position
					// calculate width by comparing actual and desired horizontal position.
					// this evenly distributes rounding errors across all images in this row.
				$suggestedWidth = round($accumDesiredWidth - $accumWidth);
				$finalImgWidth = (int) min($availableWidth, $suggestedWidth); // finalImgWidth may not exceed $availableWidth
				$accumWidth += $finalImgWidth;
				$imgConf['file.']['width'] = $finalImgWidth;
				$imgConf['file.']['height'] = round($desiredHeight);

					// other stuff will be calculated accordingly:
				unset($imgConf['file.']['maxW']);
				unset($imgConf['file.']['maxH']);
				unset($imgConf['file.']['minW']);
				unset($imgConf['file.']['minH']);
				unset($imgConf['file.']['width.']);
				unset($imgConf['file.']['maxW.']);
				unset($imgConf['file.']['maxH.']);
				unset($imgConf['file.']['minW.']);
				unset($imgConf['file.']['minH.']);
			} else {
				$imgConf['file.']['maxW'] = $columnWidths[($a%$colCount)];
			}

			$titleInLink = $this->cObj->stdWrap($imgConf['titleInLink'], $imgConf['titleInLink.']);
			$titleInLinkAndImg = $this->cObj->stdWrap($imgConf['titleInLinkAndImg'], $imgConf['titleInLinkAndImg.']);
			$oldATagParms = $GLOBALS['TSFE']->ATagParams;
			if ($titleInLink)	{
					// Title in A-tag instead of IMG-tag
				$titleText = trim($this->cObj->stdWrap($imgConf['titleText'], $imgConf['titleText.']));
				if ($titleText)	{
						// This will be used by the IMAGE call later:
					$GLOBALS['TSFE']->ATagParams .= ' title="'. htmlspecialchars($titleText) .'"';
				}
			}

			if ($imgConf || $imgConf['file'])	{
				if ($this->cObj->image_effects[$image_effects])	{
					$imgConf['file.']['params'] .= ' '.$this->cObj->image_effects[$image_effects];
				}
				if ($image_frames)	{
					if (is_array($conf['image_frames.'][$image_frames.'.']))	{
						$imgConf['file.']['m.'] = $conf['image_frames.'][$image_frames.'.'];
					}
				}
				if ($image_compression && $imgConf['file'] != 'GIFBUILDER')	{
					if ($image_compression == 1)	{
						$tempImport = $imgConf['file.']['import'];
						$tempImport_dot = $imgConf['file.']['import.'];
						unset($imgConf['file.']);
						$imgConf['file.']['import'] = $tempImport;
						$imgConf['file.']['import.'] = $tempImport_dot;
					} elseif (isset($this->cObj->image_compression[$image_compression])) {
						$imgConf['file.']['params'] .= ' '.$this->cObj->image_compression[$image_compression]['params'];
						$imgConf['file.']['ext'] = $this->cObj->image_compression[$image_compression]['ext'];
						unset($imgConf['file.']['ext.']);
					}
				}
				if ($titleInLink && ! $titleInLinkAndImg)	{
						// Check if the image will be linked
					$link = $this->cObj->imageLinkWrap('', $totalImagePath, $imgConf['imageLinkWrap.']);
					if ($link)	{
							// Title in A-tag only (set above: ATagParams), not in IMG-tag
						unset($imgConf['titleText']);
						unset($imgConf['titleText.']);
						$imgConf['emptyTitleHandling'] = 'removeAttr';
					}
				}
				$imgsTag[$imgKey] = $this->cObj->IMAGE($imgConf);
			} else {
				$imgsTag[$imgKey] = $this->cObj->IMAGE(Array('file' => $totalImagePath)); 	// currentValKey !!!
			}
				// Restore our ATagParams
			$GLOBALS['TSFE']->ATagParams = $oldATagParms;
				// Store the original filepath
			$origImages[$imgKey] = $GLOBALS['TSFE']->lastImageInfo;

			if ($GLOBALS['TSFE']->lastImageInfo[0]==0) {
				$imageRowsFinalWidths[floor($a/$colCount)] += $this->cObj->data['imagewidth'];
			} else {
				$imageRowsFinalWidths[floor($a/$colCount)] += $GLOBALS['TSFE']->lastImageInfo[0];
 			}

		}
			// How much space will the image-block occupy?
		$imageBlockWidth = max($imageRowsFinalWidths)+ $colspacing*($colCount-1) + $colCount*$border*($borderSpace+$borderThickness)*2;
		$GLOBALS['TSFE']->register['rowwidth'] = $imageBlockWidth;
		$GLOBALS['TSFE']->register['rowWidthPlusTextMargin'] = $imageBlockWidth + $textMargin;

			// noRows is in fact just one ROW, with the amount of columns specified, where the images are placed in.
			// noCols is just one COLUMN, each images placed side by side on each row
		$noRows = $this->cObj->stdWrap($conf['noRows'],$conf['noRows.']);
		$noCols = $this->cObj->stdWrap($conf['noCols'],$conf['noCols.']);
		if ($noRows) {$noCols=0;}	// noRows overrides noCols. They cannot exist at the same time.

		$rowCount_temp = 1;
		$colCount_temp = $colCount;
		if ($noRows)	{
			$rowCount_temp = $rowCount;
			$rowCount = 1;
		}
		if ($noCols)	{
			$colCount = 1;
			$columnWidths = array();
		}

			// Edit icons:
		if (!is_array($conf['editIcons.'])) {
			$conf['editIcons.'] = array();
		}
		$editIconsHTML = $conf['editIcons']&&$GLOBALS['TSFE']->beUserLogin ? $this->cObj->editIcons('',$conf['editIcons'],$conf['editIcons.']) : '';

			// If noRows, we need multiple imagecolumn wraps
		$imageWrapCols = 1;
		if ($noRows)	{ $imageWrapCols = $colCount; }

			// User wants to separate the rows, but only do that if we do have rows
		$separateRows = $this->cObj->stdWrap($conf['separateRows'], $conf['separateRows.']);
		if ($noRows)	{ $separateRows = 0; }
		if ($rowCount == 1)	{ $separateRows = 0; }

			// Apply optionSplit to the list of classes that we want to add to each image
		$addClassesImage = $conf['addClassesImage'];
		if ($conf['addClassesImage.'])	{
			$addClassesImage = $this->cObj->stdWrap($addClassesImage, $conf['addClassesImage.']);
		}
		$addClassesImageConf = $GLOBALS['TSFE']->tmpl->splitConfArray(array('addClassesImage' => $addClassesImage), $colCount);

			// Render the images
		$images = '';
		for ($c = 0; $c < $imageWrapCols; $c++)	{
			$tmpColspacing = $colspacing;
			if (($c==$imageWrapCols-1 && $imagePosition==2) || ($c==0 && ($imagePosition==1||$imagePosition==0))) {
					// Do not add spacing after column if we are first column (left) or last column (center/right)
				$tmpColspacing = 0;
			}

			$thisImages = '';
			$allRows = '';
			$maxImageSpace = 0;
			for ($i = $c; $i<count($imgsTag); $i=$i+$imageWrapCols)	{
				$imgKey = $i+$imgStart;
				$colPos = $i%$colCount;
				if ($separateRows && $colPos == 0) {
					$thisRow = '';
				}

					// Render one image
				if($origImages[$imgKey][0]==0) {
					$imageSpace=$this->cObj->data['imagewidth'] + $border*($borderSpace+$borderThickness)*2;
				} else {
					$imageSpace = $origImages[$imgKey][0] + $border*($borderSpace+$borderThickness)*2;
				}

				$GLOBALS['TSFE']->register['IMAGE_NUM'] = $imgKey;
				$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $imgKey;
				$GLOBALS['TSFE']->register['ORIG_FILENAME'] = $origImages[$imgKey]['origFile'];
				$GLOBALS['TSFE']->register['imagewidth'] = $origImages[$imgKey][0];
				$GLOBALS['TSFE']->register['imagespace'] = $imageSpace;
				$GLOBALS['TSFE']->register['imageheight'] = $origImages[$imgKey][1];
				if ($imageSpace > $maxImageSpace)	{
					$maxImageSpace = $imageSpace;
				}
				$thisImage = '';
				$thisImage .= $this->cObj->stdWrap($imgsTag[$imgKey], $conf['imgTagStdWrap.']);

				if (!$renderGlobalCaption)	{
					$thisImage .= $this->cObj->stdWrap($this->cObj->cObjGet($conf['caption.'], 'caption.'), $conf['caption.']);
				}
				if ($editIconsHTML)	{
					$thisImage .= $this->cObj->stdWrap($editIconsHTML, $conf['editIconsStdWrap.']);
				}
				$thisImage = $this->cObj->stdWrap($thisImage, $conf['oneImageStdWrap.']);
				$classes = '';
				if ($addClassesImageConf[$colPos]['addClassesImage'])	{
					$classes = ' ' . $addClassesImageConf[$colPos]['addClassesImage'];
				}
				$thisImage = str_replace('###CLASSES###', $classes, $thisImage);

				if ($separateRows)	{
					$thisRow .= $thisImage;
				} else {
					$allRows .= $thisImage;
				}
				$GLOBALS['TSFE']->register['columnwidth'] = $maxImageSpace + $tmpColspacing;


					// Close this row at the end (colCount), or the last row at the final end
				if ($separateRows && ($i+1 == count($imgsTag)))	{
						// Close the very last row with either normal configuration or lastRow stdWrap
					$allRows .= $this->cObj->stdWrap($thisRow, (is_array($conf['imageLastRowStdWrap.']) ? $conf['imageLastRowStdWrap.'] : $conf['imageRowStdWrap.']));
				} elseif ($separateRows && $colPos == $colCount-1)	{
					$allRows .= $this->cObj->stdWrap($thisRow, $conf['imageRowStdWrap.']);
				}
			}
			if ($separateRows)	{
				$thisImages .= $allRows;
			} else {
				$thisImages .= $this->cObj->stdWrap($allRows, $conf['noRowsStdWrap.']);
			}
			if ($noRows)	{
					// Only needed to make columns, rather than rows:
				$images .= $this->cObj->stdWrap($thisImages, $conf['imageColumnStdWrap.']);
			} else {
				$images .= $thisImages;
			}
		}

			// Add the global caption, if not split
		if ($globalCaption)	{
			$images .= $globalCaption;
		}

			// CSS-classes
		$captionClass = '';
		$classCaptionAlign = array(
			'center' => 'csc-textpic-caption-c',
			'right' => 'csc-textpic-caption-r',
			'left' => 'csc-textpic-caption-l',
		);
		$captionAlign = $this->cObj->stdWrap($conf['captionAlign'], $conf['captionAlign.']);
		if ($captionAlign)	{
			$captionClass = $classCaptionAlign[$captionAlign];
		}
		$borderClass = '';
		if ($border)	{
			$borderClass = $conf['borderClass'] ? $conf['borderClass'] : 'csc-textpic-border';
		}

			// Multiple classes with all properties, to be styled in CSS
		$class = '';
		$class .= ($borderClass? ' '.$borderClass:'');
		$class .= ($captionClass? ' '.$captionClass:'');
		$class .= ($equalHeight? ' csc-textpic-equalheight':'');
		$addClasses = $this->cObj->stdWrap($conf['addClasses'], $conf['addClasses.']);
		$class .= ($addClasses ? ' '.$addClasses:'');

			// Do we need a width in our wrap around images?
		$imgWrapWidth = '';
		if ($position == 0 || $position == 8)	{
				// For 'center' we always need a width: without one, the margin:auto trick won't work
			$imgWrapWidth = $imageBlockWidth;
		}
		if ($rowCount > 1)	{
				// For multiple rows we also need a width, so that the images will wrap
			$imgWrapWidth = $imageBlockWidth;
		}
		if ($caption)	{
				// If we have a global caption, we need the width so that the caption will wrap
			$imgWrapWidth = $imageBlockWidth;
		}

			// Wrap around the whole image block
		$GLOBALS['TSFE']->register['totalwidth'] = $imgWrapWidth;
		if ($imgWrapWidth)	{
			$images = $this->cObj->stdWrap($images, $conf['imageStdWrap.']);
		} else {
			$images = $this->cObj->stdWrap($images, $conf['imageStdWrapNoWidth.']);
		}

		$output = $this->cObj->cObjGetSingle($conf['layout'], $conf['layout.']);
		$output = str_replace('###TEXT###', $content, $output);
		$output = str_replace('###IMAGES###', $images, $output);
		$output = str_replace('###CLASSES###', $class, $output);

		if ($conf['stdWrap.'])	{
			$output = $this->cObj->stdWrap($output, $conf['stdWrap.']);
		}

		return $output;
	}












	/************************************
	 *
	 * Helper functions
	 *
	 ************************************/

	/**
	 * Returns a link text string which replaces underscores in filename with
	 * blanks.
	 *
	 * Has the possibility to cut off FileType.

	 * @param array $links
	 *        array with [0] linked file icon, [1] text link
	 * @param string $fileName
	 *        the name of the file to be linked (without path)
	 * @param boolean $useSpaces
	 *        whether underscores in the file name should be replaced with spaces
	 * @param boolean $cutFileExtension
	 *        whether the file extension should be removed
	 *
	 * @return array modified array with new link text
	 */
	protected function beautifyFileLink(
		array $links, $fileName, $useSpaces = FALSE, $cutFileExtension = FALSE
	) {
		$linkText = $fileName;
		if ($useSpaces) {
			$linkText = str_replace('_', ' ', $linkText);
		}
		if ($cutFileExtension) {
			$pos = strrpos($linkText, '.');
			$linkText = substr($linkText, 0, $pos);
		}
		$links[1] = str_replace(
			'>' . $fileName . '<', '>' . htmlspecialchars($linkText) . '<', $links[1]
		);
		return $links;
	}

	/**
	 * Returns table attributes for uploads / tables.
	 *
	 * @param	array		TypoScript configuration array
	 * @param	integer		The "layout" type
	 * @return	array		Array with attributes inside.
	 */
	function getTableAttributes($conf,$type)	{

			// Initializing:
		$tableTagParams_conf = $conf['tableParams_'.$type.'.'];

		$conf['color.'][200] = '';
		$conf['color.'][240] = 'black';
		$conf['color.'][241] = 'white';
		$conf['color.'][242] = '#333333';
		$conf['color.'][243] = 'gray';
		$conf['color.'][244] = 'silver';

			// Create table attributes array:
		$tableTagParams = array();
		$tableTagParams['border'] =  $this->cObj->data['table_border'] ? intval($this->cObj->data['table_border']) : $tableTagParams_conf['border'];
		$tableTagParams['cellspacing'] =  $this->cObj->data['table_cellspacing'] ? intval($this->cObj->data['table_cellspacing']) : $tableTagParams_conf['cellspacing'];
		$tableTagParams['cellpadding'] =  $this->cObj->data['table_cellpadding'] ? intval($this->cObj->data['table_cellpadding']) : $tableTagParams_conf['cellpadding'];
		$tableTagParams['bgcolor'] =  isset($conf['color.'][$this->cObj->data['table_bgColor']]) ? $conf['color.'][$this->cObj->data['table_bgColor']] : $conf['color.']['default'];

			// Return result:
		return $tableTagParams;
	}

	/**
	 * Returns an object reference to the hook object if any
	 *
	 * @param	string		Name of the function you want to call / hook key
	 * @return	object		Hook object, if any. Otherwise null.
	 */
	function hookRequest($functionName) {
		global $TYPO3_CONF_VARS;

			// Hook: menuConfig_preProcessModMenu
		if ($TYPO3_CONF_VARS['EXTCONF']['css_styled_content']['pi1_hooks'][$functionName]) {
			$hookObj = t3lib_div::getUserObj($TYPO3_CONF_VARS['EXTCONF']['css_styled_content']['pi1_hooks'][$functionName]);
			if (method_exists ($hookObj, $functionName)) {
				$hookObj->pObj = $this;
				return $hookObj;
			}
		}
	}


	/**
	 * Rendering the "Table" type content element, called from TypoScript (tt_content.table.20)
	 *
	 * @param	string		Content input. Not used, ignore.
	 * @param	array		TypoScript configuration
	 * @return	string		HTML output.
         * @version   4.8.0
         * @since 4.8.0
         * @internal #53397
	 * @access public
	 */
	public function render_table($content,$conf)	{

			// Look for hook before running default code for function
		if ($hookObj = $this->hookRequest('render_table')) {
			return $hookObj->render_table($content,$conf);
		} else {
				// Init FlexForm configuration
			$this->pi_initPIflexForm();

				// Get bodytext field content
			$field = (isset($conf['field']) && trim($conf['field']) ? trim($conf['field']) : 'bodytext');
//echo $field . PHP_EOL;
                        $content = trim($this->cObj->data[$field]);
//echo $content . PHP_EOL;
			if (!strcmp($content,''))	return '';

                          // #53397, 131107, dwildt
//var_dump( __METHOD__, __LINE__, var_export( $this->cObj->data['pi_flexform'], true ) );
                        $this->conf = $conf;
                        $this->helper_init_drs( );
                        $this->cObjDataSet( );
			$this->pi_initPIflexForm();
//var_dump( __METHOD__, __LINE__, var_export( $this->cObj->data['pi_flexform'], true ) );

                          // get flexform values
			$caption = trim(htmlspecialchars($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'acctables_caption')));
			$useTfoot = trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'acctables_tfoot'));
			$headerPos = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'acctables_headerpos');
			$noStyles = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'acctables_nostyles');
			$tableClass = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'acctables_tableclass');

			$delimiter = trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tableparsing_delimiter','s_parsing'));
			if ($delimiter)	{
				$delimiter = chr(intval($delimiter));
			} else {
				$delimiter = '|';
			}
			$quotedInput = trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tableparsing_quote','s_parsing'));
			if ($quotedInput)	{
				$quotedInput = chr(intval($quotedInput));
			} else {
				$quotedInput = '';
			}

				// generate id prefix for accessible header
			$headerScope = ($headerPos=='top'?'col':'row');
			$headerIdPrefix = $headerScope.$this->cObj->data['uid'].'-';

				// Split into single lines (will become table-rows):
			$rows = t3lib_div::trimExplode(LF,$content);
			reset($rows);
//var_dump( __METHOD__, __LINE__, var_export( $rows, true ) );

				// Find number of columns to render:
			$cols = t3lib_utility_Math::forceIntegerInRange($this->cObj->data['cols']?$this->cObj->data['cols']:count(explode($delimiter,current($rows))),0,100);

				// Traverse rows (rendering the table here)
			$rCount = count($rows);
			foreach($rows as $k => $v)	{
				$cells = explode($delimiter,$v);
				$newCells=array();
				for($a=0;$a<$cols;$a++)	{
						// remove quotes if needed
					if ($quotedInput && substr($cells[$a],0,1) == $quotedInput && substr($cells[$a],-1,1) == $quotedInput)	{
						$cells[$a] = substr($cells[$a],1,-1);
					}

					if (!strcmp(trim($cells[$a]),''))	$cells[$a]='&nbsp;';
					$cellAttribs = ($noStyles ? '' : (($a > 0 && ($cols - 1) == $a) ? ' class="td-last td-' . $a . '"' : ' class="td-' . $a . '"'));
					if (($headerPos == 'top' && !$k) || ($headerPos == 'left' && !$a))	{
						$scope = ' scope="'.$headerScope.'"';
						$scope .= ' id="'.$headerIdPrefix.(($headerScope=='col')?$a:$k).'"';

						$newCells[$a] = '
							<th'.$cellAttribs.$scope.'>'.$this->cObj->stdWrap($cells[$a],$conf['innerStdWrap.']).'</th>';
					} else {
						if (empty($headerPos))	{
							$accessibleHeader = '';
						} else {
							$accessibleHeader = ' headers="'.$headerIdPrefix.(($headerScope=='col')?$a:$k).'"';
						}
						$newCells[$a] = '
							<td'.$cellAttribs.$accessibleHeader.'>'.$this->cObj->stdWrap($cells[$a],$conf['innerStdWrap.']).'</td>';
					}
				}
				if (!$noStyles)	{
					$oddEven = $k%2 ? 'tr-odd' : 'tr-even';
					$rowAttribs =  ($k>0 && ($rCount-1)==$k) ? ' class="'.$oddEven.' tr-last"' : ' class="'.$oddEven.' tr-'.$k.'"';
				}
				$rows[$k]='
					<tr'.$rowAttribs.'>'.implode('',$newCells).'
					</tr>';
			}

			$addTbody = 0;
			$tableContents = '';
			if ($caption)	{
				$tableContents .= '
					<caption>'.$caption.'</caption>';
			}
			if ($headerPos == 'top' && $rows[0])	{
				$tableContents .= '<thead>'. $rows[0] .'
					</thead>';
				unset($rows[0]);
				$addTbody = 1;
			}
			if ($useTfoot)	{
				$tableContents .= '
					<tfoot>'.$rows[$rCount-1].'</tfoot>';
				unset($rows[$rCount-1]);
				$addTbody = 1;
			}
			$tmpTable = implode('',$rows);
			if ($addTbody)	{
				$tmpTable = '<tbody>'.$tmpTable.'</tbody>';
			}
			$tableContents .= $tmpTable;
//var_dump( __METHOD__, __LINE__, var_export( $tableContents, true ) );

				// Set header type:
			$type = intval($this->cObj->data['layout']);

				// Table tag params.
			$tableTagParams = $this->getTableAttributes($conf,$type);
			if (!$noStyles)	{
				$tableTagParams['class'] = 'contenttable contenttable-' . $type .
					($tableClass ? ' ' . $tableClass : '') . $tableTagParams['class'];
			} elseif ($tableClass) {
				$tableTagParams['class'] = $tableClass;
			}


				// Compile table output:
			$out = '
				<table ' . t3lib_div::implodeAttributes($tableTagParams) . '>' .
				$tableContents . '
				</table>';
//var_dump( __METHOD__, __LINE__, var_export( $out, true ) );

				// Calling stdWrap:
			if ($conf['stdWrap.']) {
				$out = $this->cObj->stdWrap($out, $conf['stdWrap.']);
			}

				// Return value
//$out = trim( $out );
//$out = '<h1>HALLO</h1><table><tr><td>TABELLE</td></tr></table>' . $out;
//var_dump( __METHOD__, __LINE__, var_export( $out, true ) );
			return $out;
		}
	}



 /**
  * render_uploads(): The method enables to link to files of each language at the same time.
  *                   The method is based on $this->render_uploads_per_language( ). See below.
  *                   Conditions
  *                   * userFunc.renderCurrentLanguageOnly has to be true
  *                   * the table sys_language has to contain one record at least
  *
  * @param	string		Content input. Not used, ignore.
  * @param	array		TypoScript configuration
  * @return	string		HTML output.
  * @access public
  * @version 4.4.4
  * @since 3.9.3
  */
  public function render_uploads( $content, $conf )
  {
    $this->conf = $conf;

    $out = null;

      //////////////////////////////////////////////////////////////////////////
      //
      // Enable the DRS by TypoScript

    $this->helper_init_drs( );
      // Enable the DRS by TypoScript

//    // #44858, 130130, dwildt, 1+
//  $this->cObjDataAddFieldsWoTablePrefix( );
    $this->cObjDataSet( );
//s$pos = strpos( '87.177.72.26 ', t3lib_div :: getIndpEnv( 'REMOTE_ADDR' ) );
//if ( ! ( $pos === false ) )
//{
//  echo '<pre>';
////  var_dump( __METHOD__, __LINE__, $GLOBALS['TSFE']->cObj->data );
////  var_dump( __METHOD__, __LINE__, $GLOBALS['TSFE']->currentRecord );
////  var_dump( __METHOD__, __LINE__, $this->cObj->data );
//  var_dump( __METHOD__, __LINE__, $GLOBALS['TSFE']->tx_browser_pi1 );
//  echo '</pre>' . PHP_EOL;
//}
// #i0008
//var_dump( __METHOD__, __LINE__, $GLOBALS['TSFE']->register );
// #i0002
//var_dump( __METHOD__, __LINE__, $this->cObj->data );

      //////////////////////////////////////////////////////////////////////////
      //
      // Init the browser localisation object

    require_once( PATH_typo3conf . 'ext/browser/pi1/class.tx_browser_pi1_localisation_3x.php' );
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
      $coa_name                                     = $conf['userFunc.']['renderCurrentLanguageOnly'];
      $coa_conf_userFunc_renderCurrentLanguageOnly  = $conf['userFunc.']['renderCurrentLanguageOnly.'];
      $bool_currLangOnly                            = intval
                                                      (
                                                        $this->helper_cObjGetSingle
                                                        (
                                                          $coa_name,
                                                          $coa_conf_userFunc_renderCurrentLanguageOnly
                                                        )
                                                      );
    }
//      // DRS
//    if ( $this->b_drs_download )
//    {
//      $prompt = '$bool_currLangOnly: \'' . $bool_currLangOnly . '\'';
//      t3lib_div::devlog( '[INFO] ' . $prompt, $this->extKey, 0 );
//    }
//      // DRS
      // Link the file for the current language only (default)?



      //////////////////////////////////////////////////////////////////////////
      //
      // Set tt_content.uid

    $marker                         = null;
    list( $cR_table, $cR_uid)       = explode( ':', $GLOBALS['TSFE']->currentRecord );
    unset( $cR_table );
    $marker['###TT_CONTENT.UID###'] = $cR_uid;
      // 130207, dwildt, 1+
    $this->cObj->data['tt_content.uid'] = $cR_uid;

//var_dump( __METHOD__, __LINE__, $marker );
      // 111215, dwildt-
    //$conf                           = $this->cObj->substituteMarkerInObject( $conf, $marker );
      // 111215, dwildt+
//var_dump( __METHOD__, __LINE__, $conf['userFunc.']['record.'] );
    $serialized_conf  = serialize( $conf );
    $coa_conf         = $this->cObj->substituteMarkerInObject( $conf, $marker );
    $conf             = unserialize( $serialized_conf );
//var_dump( __METHOD__, __LINE__, $coa_conf['userFunc.']['record.'] );
      // 111215, dwildt+
      // Set tt_content.uid



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the filelink for the current language only

    if( $bool_currLangOnly )
    {
      $out = $out . $this->render_uploads_per_language( $content, $coa_conf );
        // #44858, 130130, dwildt, 1+
      $this->cObjDataReset( );
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
    if( isset( $coa_conf['userFunc.']['table'] ) )
    {
      $coa_name                 = $coa_conf['userFunc.']['table'];
      $coa_conf_userFunc_table  = $coa_conf['userFunc.']['table.'];
      $table                    = $this->helper_cObjGetSingle( $coa_name, $coa_conf_userFunc_table );
    }
      // Get the current table



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the current uid (of the default language record)

    $uid = 'no_record_is_defined';
    if( isset( $coa_conf['userFunc.']['record'] ) )
    {
      $coa_name                 = $coa_conf['userFunc.']['record'];
      $coa_conf_userFunc_record = $coa_conf['userFunc.']['record.'];
      $uid                      = intval( $this->helper_cObjGetSingle( $coa_name, $coa_conf_userFunc_record ) );
    }
      // Get the current uid (of the default language record)



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the select

    $select = 'no_select_is_defined';
    if( isset( $coa_conf['userFunc.']['select'] ) )
    {
      $coa_name                 = $coa_conf['userFunc.']['select'];
      $coa_conf_userFunc_select = $coa_conf['userFunc.']['select.'];
      $select                   = $this->helper_cObjGetSingle( $coa_name, $coa_conf_userFunc_select );
      $select                   = $this->objZz->cleanUp_lfCr_doubleSpace( $select );
    }
      // Get the select



      // 130207, dwildt, 6-
//      //////////////////////////////////////////////////////////////////////////
//      //
//      // Get the configuration
//
//    $userFunc_conf = $coa_conf['userFunc.']['conf.'];
//      // Get the configuration



      //////////////////////////////////////////////////////////////////////////
      //
      // Set and get localisation configuration

      // 130207, dwildt, 2-
//      // Remove 'L' from linkVars
//    $str_linkVarsWoL                          = $this->helper_linkVarsWoL( );
      // Save the language id for the reset below
    $lang_id                                  = $this->objLocalise->lang_id;
      // Set and get localisation configuration



      //////////////////////////////////////////////////////////////////////////
      //
      // LOOP all languages

      // 130207, dwildt, 1-
//    foreach( $llRows as $flag => $arr_lang )
      // 130207, dwildt, 1+
    foreach( array_keys( ( array ) $llRows ) as $flag )
    {
        // Get the localised uid
        // Don't substitute non localised records with default language
      //$this->objLocalise->int_localisation_mode = PI1_SELECTED_LANGUAGE_ONLY;
      $this->objLocalise->setLocalisationMode( PI1_SELECTED_LANGUAGE_ONLY );
        // Set current language
      $this->objLocalise->lang_id               = intval( $llRows[$flag]['uid'] );
      $llUid = $this->objLocalise->get_localisedUid( $table, $uid );
        // Get the localised uid

        // CONTINUE there isn't any localised record
      if( empty( $llUid ) )
      {
          // DRS - Development Reporting System
        if ( $this->b_drs_localisation )
        {
          $prompt = 'CONTINUE: ' . $table . '['. $uid . '] hasn\'t any localised record.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->extKey, 0);
        }
          // DRS - Development Reporting System
        continue;
      }
        // CONTINUE there isn't any localised record

        // Set data of the localised record as a marker array
      $marker                             = null;
      $marker                             = $this->sql_marker( $select, $table, $llUid );

        // CONTINUE there isn't any localised record
        // #35014, 120319, dwildt
      if( empty( $marker ) )
      {
          // DRS - Development Reporting System
        if ( $this->b_drs_localisation )
        {
          $prompt = 'CONTINUE: ' . $table . '['. $llUid . '] is an empty row.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->extKey, 0);
        }
          // DRS - Development Reporting System
        continue;
      }
        // CONTINUE there isn't any localised record

        // Set data of the localised record as a marker array
      $marker['###SYS_LANGUAGE.FLAG###']  = $llRows[$flag]['flag'];
      $marker['###SYS_LANGUAGE.TITLE###'] = $llRows[$flag]['title'];

      // 111215, dwildt+
      $marker['###TABLE.UID###'] = $llUid;

        // Replace the marker in the TypoScript recursively
        // Workaround because of bug: $userFunc_conf will be changed, but it should not!
      $serialized_conf            = serialize( $coa_conf['userFunc.']['conf.'] );
      $coa_conf_userFunc_conf     = $this->cObj->substituteMarkerInObject( $coa_conf['userFunc.']['conf.'], $marker );
      $coa_conf['userFunc.']['conf.'] = unserialize( $serialized_conf );
        // Replace the marker in the TypoScript recursively

        // Update the linkVars
      // 111215, dwildt-
      //$GLOBALS['TSFE']->linkVars = '&L=' . $llRows[$flag]['uid'] . $str_linkVarsWoL;

        // Render the $conf
      $llOut = $this->render_uploads_per_language( $content, $coa_conf_userFunc_conf );

        // Concatenate the localized output
      $out = $out . $llOut;
    }
      // LOOP all languages



      //////////////////////////////////////////////////////////////////////////
      //
      // Reset some variables, which are changed above

    //$this->objLocalise->int_localisation_mode = null;
    $this->objLocalise->setLocalisationMode( null );
    $this->objLocalise->lang_id               = $lang_id;
    $GLOBALS['TSFE']->linkVars                = $str_linkVars;
      // Reset some variables, which are changed above



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the content

      // #44858, 130130, dwildt, 1+
    $this->cObjDataReset( );
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
  * @param	string		Content input. Not used, ignore.
  * @param	array		TypoScript configuration
  * @return	string		HTML output.
  * @access public
  * @version 3.9.3
  * @since 3.6.4
  */
  private function render_uploads_per_language( $content, $conf )
  {
//      // DRS
//    if ( $this->b_drs_download )
//    {
//      $prompt = 'render_uploads_per_language( ) start';
//      t3lib_div::devlog( '[INFO] ' . $prompt, $this->extKey, 0 );
//    }
      // DRS

      // 130207, dwildt, 1+
    unset( $content );

      // the result
    $out = '';

      // get layout type
      // 0: link only, 1: with application icon, 2: with based icon
// #44858, 130207, dwildt, 1-
//    $type = intval( $this->cObj->stdWrap( $conf['fields.']['layout'], $conf['fields.']['layout.'] ) );
// #44858, 130207, dwildt, 1+
    $type = intval( $this->cObj->data['layout'] );
//var_dump( __METHOD__, __LINE__, '$type: ' . $type );

      // set default path
    $path = 'uploads/media/';

      // #44858, 130207, dwildt, 3-
//      // get tableField
//    $tableField = $this->cObj->stdWrap($conf['tableField'], $conf['tableField.']);
//    list($table, $field) = explode('.', $tableField);

      // #44858, 130207, dwildt, 11+
      // get table and field
    if( ! empty( $conf['tableField'] ) )
    {
      list( $table )  = explode('.', $conf['tableField'] );
      $field          = $conf['tableField'];
    }
    else
    {
      $table = $this->table;
        #48871, 130605, dwildt, 6-
        $field = ( trim( $conf['field'] ) ? trim( $conf['field'] ) : 'media' );
        list( $tableInTca, $fieldInTca ) = explode( '.', $field );
        if( empty ( $fieldInTca ) )
        {
          $fieldInTca = $tableInTca;
        }
        #48871, 130605, dwildt, 6-
//        // #48871, 130605, dwildt, 17+
//      $cObj_name  = $conf['field'];
//      $cObj_conf  = $conf['field.'];
//      $field      = $this->helper_cObjGetSingle( $cObj_name, $cObj_conf );
//      switch( true )
//      {
//        case( empty( $field ) ):
//          $field      = 'media';
//          $fieldInTca = 'media';
//          break;
//        case( ! empty( $field ) ):
//        default:
//          list( $tableInTca, $fieldInTca ) = explode( '.', $field );
//          if( empty ( $fieldInTca ) )
//          {
//            $fieldInTca = $tableInTca;
//          }
//          break;
//      }
//var_dump( __METHOD__, __LINE__, $field, $tableInTca, $fieldInTca, $this->cObj->data[ $field ] );
//        // #48871, 130605, dwildt, 17+
    }

      // 130207, +
    $thumbTableField  = null;
    $thumbTable       = null;
    $thumbField       = null;
    $thumbList        = null;
    $thumbArray       = null;
    $thumbPath        = null;

    $thumbTableField = $conf['thumbnail'];
    if( ! empty ( $thumbTableField ) )
    {
      $thumbList = $this->cObj->data[$thumbTableField];
      if( ! empty ( $thumbList ) )
      {
        $thumbArray = t3lib_div::trimExplode( ',', $thumbList, 1 );
        list( $thumbTable, $thumbField ) = explode( '.', $thumbTableField );
        if( is_array( $GLOBALS['TCA'][$thumbTable]['columns'][$thumbField] ) )
        {
          if( ! empty( $GLOBALS['TCA'][$thumbTable]['columns'][$thumbField]['config']['uploadfolder'] ) )
          {
              // in TCA-array folders are saved without trailing slash
            $thumbPath = $GLOBALS['TCA'][$thumbTable]['columns'][$thumbField]['config']['uploadfolder'] . '/';
          }
        }
      }
    }
      // 130207, +
//var_dump( __METHOD__, __LINE__, $field, $thumbTableField, $thumbPath, $thumbList );

      // get table and field
      // #44858, 130207, dwildt, 11+


      // file path variable is set, this takes precedence
      // 130207, dwildt, 1-
//    $filePathConf = $this->cObj->stdWrap($conf['fields.']['from_path'], $conf['fields.']['from_path.']);
      // 130207, dwildt, 1+
    $filePathConf = $this->cObj->stdWrap( $conf['filePath'], $conf['filePath.'] );
//var_dump( __METHOD__, __LINE__, '$filePathConf: ' . $filePathConf );

      // 130207, dwildt, +
      // DRS
    if ( $this->b_drs_devTodo )
    {
      $prompt = 'tx_dam / file path configuration: fields.from_path is moved to filePath';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->extKey, 2 );
    }
      // DRS
      // 130207, dwildt, +

    if ( ! empty( $filePathConf ) )
    {
        // #37165, 120517, dwildt
      if( $table != 'tx_dam' )
      {
        $fileList   = $this->cObj->filelist( $filePathConf );
      }
      if( $table == 'tx_dam' )
      {
          // Get the list of files from the field
        $fileList = trim($this->cObj->stdWrap($conf['fields.']['files'], $conf['fields.']['files.']));
          // 130207, dwildt, +
          // DRS
        if ( $this->b_drs_devTodo )
        {
          $prompt = 'tx_dam / file list configuration: fields.files';
          t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->extKey, 2 );
        }
          // DRS
          // 130207, dwildt, +
      }
        // #37165, 120517, dwildt
      list( $path ) = explode( '|', $filePathConf );
    }

      // file path variable isn't set
    if ( empty( $filePathConf ) )
    {
        // Get the list of files from the field
        // #44858, 130207, dwildt, 1-
//      $fileList = trim($this->cObj->stdWrap($conf['fields.']['files'], $conf['fields.']['files.']));
        // #44858, 130207, dwildt, 1+
      $fileList = $this->cObj->data[$field];

//var_dump( __METHOD__, __LINE__, '$this->cObj->data: ', $this->cObj->data );
//var_dump( __METHOD__, __LINE__, '$field: ' . $field );
//var_dump( __METHOD__, __LINE__, '$fileList: ' . $fileList );
//var_dump( __METHOD__, __LINE__, '$table: ' . $table );
//var_dump( __METHOD__, __LINE__, '$field: ' . $field );
//var_dump( __METHOD__, __LINE__, 'GLOBALS->TCA',  $GLOBALS['TCA'][$table] );
//var_dump( __METHOD__, __LINE__, 'GLOBALS->TCA',  $GLOBALS['TCA'][$table]['columns'][$field] );

// 130207, dwildt, -
//        // Get the path
//      if( is_array( $GLOBALS['TCA'][$table]['columns'][$field] ) )
//      {
//        if( ! empty( $GLOBALS['TCA'][$table]['columns'][$field]['config']['uploadfolder'] ) )
//        {
//            // in TCA-array folders are saved without trailing slash
//          $path = $GLOBALS['TCA'][$table]['columns'][$field]['config']['uploadfolder'] . '/';
//        }
//      }

// 130207, dwildt, +
        // Get the path
      if( is_array( $GLOBALS['TCA'][$table]['columns'][$fieldInTca] ) )
      {
        if( ! empty( $GLOBALS['TCA'][$table]['columns'][$fieldInTca]['config']['uploadfolder'] ) )
        {
            // in TCA-array folders are saved without trailing slash
          $path = $GLOBALS['TCA'][$table]['columns'][$fieldInTca]['config']['uploadfolder'] . '/';
        }
      }
    }

      // 130207, dwildt, +
    if( empty( $fileList ) )
    {
      if( ! empty( $thumbList ) )
      {
        $fileList = $thumbList;
        $path     = $thumbPath;
      }
    }
      // 130207, dwildt, +
//var_dump( __METHOD__, __LINE__, $fileList, $path );

      // explode into an array
    $fileArray = t3lib_div::trimExplode( ',', $fileList, 1 );

      // there are files to list ...
    if( count( $fileArray ) )
    {
        // 130207, dwildt, 3-
//        // the captions of the files
//      $captions = $this->cObj->stdWrap($conf['fields.']['caption'], $conf['fields.']['caption.']);
//      $captions = t3lib_div::trimExplode(LF, $captions);

        // 130207, dwildt, 6+
        // Get the descriptions for the files (if any):
      $descriptions = t3lib_div::trimExplode(LF,$this->cObj->data['imagecaption']);
        // Get the titles for the files (if any)
      $titles = t3lib_div::trimExplode(LF, $this->cObj->data['titleText']);
        // Get the alternative text for icons/thumbnails
      $altTexts = t3lib_div::trimExplode(LF, $this->cObj->data['altText']);
        // 130207, dwildt, 6+
//var_dump( __METHOD__, __LINE__, '$descriptions: ',  $descriptions );
//var_dump( __METHOD__, __LINE__, '$titles: ',  $titles );
//var_dump( __METHOD__, __LINE__, '$altTexts: ', $altTexts );

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

        // #44858, 130207, dwildt, 4-
//        // dwildt, 111110, +
//        // Get configured languages
//      $llRows = $this->objLocalise->sql_getLanguages( );
//        // dwildt, 111110, +

        // LOOP: files
      $filesData = array( );
      foreach($fileArray as $key => $fileName)
      {
        $absPath = t3lib_div::getFileAbsFileName( $path . $fileName );
//var_dump( __METHOD__, __LINE__, $absPath );

          // DRS
        if ( $this->b_drs_error )
        {
          $prompt = $absPath;
          t3lib_div::devlog( '[INFO] ' . $prompt, $this->extKey, 0 );
          if ( ! @is_file($absPath))
          {
            $prompt = 'Is no file';
            t3lib_div::devlog( '[ERROR] ' . $prompt, $this->extKey, 3 );
          }
        }
          // DRS

          // file is a file
        if (@is_file($absPath))
        {
            // DRS
          if ( $this->b_drs_download )
          {
            $prompt = 'File does exist.';
            t3lib_div::devlog( '[OK] ' . $prompt, $this->extKey, -1 );
          }
            // DRS

          $path_info = pathinfo($fileName);
          $filesData[$key] = array();

          $filesData[$key]['filename']      = $fileName;
          $filesData[$key]['path']          = $path;
          $filesData[$key]['filesize']      = filesize($absPath);
          $filesData[$key]['fileextension'] = strtolower($path_info['extension']);
            // 130207, dwildt, 1-
//          $filesData[$key]['description']   = trim($captions[$key]);
            // 130207, dwildt, 1+
          $filesData[$key]['description']   = trim($descriptions[$key]);
            // 130207, dwildt, 1+
          $conf['linkProc.']['title'] = trim($titles[$key]);

            // 130207, dwildt, 6+
          if (isset($altTexts[$key]) && !empty($altTexts[$key])) {
            $altText = trim($altTexts[$key]);
          } else {
            $altText = sprintf($this->pi_getLL('uploads.icon'), $fileName);
          }
          $conf['linkProc.']['altText'] = $conf['linkProc.']['iconCObject.']['altText'] = $altText;

          $this->cObj->setCurrentVal($path);

            // 130207, dwildt, 1-
          //$GLOBALS['TSFE']->register['ICON_REL_PATH'] = $path . $fileName;
            // 130207, dwildt, +
          $icon_rel_path = $path . $fileName;
          if( ! empty ( $thumbPath ) )
          {
            $icon_rel_path = $thumbPath . $thumbArray[$key];
          }
          $GLOBALS['TSFE']->register['ICON_REL_PATH'] = $icon_rel_path;
            // 130207, dwildt, +

          $GLOBALS['TSFE']->register['filename']      = $filesData[$key]['filename'];
          $GLOBALS['TSFE']->register['path']          = $filesData[$key]['path'];
          $GLOBALS['TSFE']->register['fileSize']      = $filesData[$key]['filesize'];
          $GLOBALS['TSFE']->register['fileExtension'] = $filesData[$key]['fileextension'];
          $GLOBALS['TSFE']->register['description']   = $filesData[$key]['description'];
//var_dump( __METHOD__, __LINE__, '$filesData: ' . var_export($filesData, true ) );
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
          $arr_filelinks = $this->helper_browser_linkProc( $conf, $key, $fileName );

            // Beautify the links
          $filesData[$key]['linkedFilenameParts'] = $this->beautifyFileLink
                                                    (
                                                      $arr_filelinks,
                                                      $fileName,
                                                      $conf['useSpacesInLinkText'],
                                                      $conf['stripFileExtensionFromLinkText']
                                                    );
// dwildt, 111106, +
//var_dump( __METHOD__, __LINE__, '$filesData: ' . $filesData );
        }
          // file is a file
      }
        // LOOP: files

        // optionSplit applied to conf to allow differnt settings per file
      $splitConf = $GLOBALS['TSFE']->tmpl->splitConfArray($conf, count($filesData));

        // render the list
      $outputEntries = array();

        // LOOP: filesData
      foreach($filesData as $key => $fileData)
      {
        $GLOBALS['TSFE']->register['linkedIcon']    = $fileData['linkedFilenameParts'][0];
        $GLOBALS['TSFE']->register['linkedLabel']   = $fileData['linkedFilenameParts'][1];
        $GLOBALS['TSFE']->register['filename']      = $fileData['filename'];
        $GLOBALS['TSFE']->register['path']          = $fileData['path'];
        $GLOBALS['TSFE']->register['description']   = $fileData['description'];
        $GLOBALS['TSFE']->register['fileSize']      = $fileData['filesize'];
        $GLOBALS['TSFE']->register['fileExtension'] = $fileData['fileextension'];
//var_dump( __METHOD__, __LINE__, var_export($GLOBALS['TSFE']->register, true ) );

// dwildt, 111106, -
//        $outputEntries[]  = $this->helper_cObjGetSingle
//                            (
//                              $splitConf[$key]['itemRendering'],
//                              $splitConf[$key]['itemRendering.']
//                            );
// dwildt, 111106, -
// dwildt, 111106, +

          // Set marker array
        $marker['###KEY###']                = $key;
        $marker['###FILENAME###']           = $fileName;
          // 130207, dwildt, 2+
        $this->cObj->data['key']            = $key;
        $this->cObj->data['filename']       = $fileName;

          // Set marker array

          // Replace the marker in the TypoScript recursively
          // Workaround because of bug: $splitConf[$key]['itemRendering.']
          // will be changed, but it should not!
        $serialized_conf                    = serialize( $splitConf[$key]['itemRendering.'] );
        $coa_conf_itemRendering             = $this->cObj->substituteMarkerInObject
                                            (
                                              $splitConf[$key]['itemRendering.'],
                                              $marker
                                            );
        $splitConf[$key]['itemRendering.']  = unserialize( $serialized_conf );
          // Replace the marker in the TypoScript recursively

        $coa_name         = $splitConf[$key]['itemRendering'];

        $str_outputEntry  = $this->helper_cObjGetSingle
                            (
                              $coa_name,
                              $coa_conf_itemRendering
                            );
  // 130605, dwildt
//var_dump( __METHOD__, __LINE__, $key );
//var_dump( __METHOD__, __LINE__, var_export( $splitConf[$key]['itemRendering.'], true ) );
//var_dump( __METHOD__, __LINE__, var_export( $coa_conf_itemRendering, true ) );
//var_dump( __METHOD__, __LINE__, var_export( $str_outputEntry, true ) );

          // Error management
          // 120215, dwildt+
        if( empty( $str_outputEntry ) )
        {
            // DRS
          if ( $this->b_drs_error )
          {
            $prompt = 'Result is empty.';
            t3lib_div::devlog( '[ERROR] ' . $prompt, $this->extKey, 3 );
            switch( true )
            {
              case( empty ( $coa_name ) ):
                $prompt = 'Unproper TypoScript property: itemRendering =';
                t3lib_div::devlog( '[ERROR] ' . $prompt, $this->extKey, 3 );
                $prompt = 'A proper TypoScript property would be: itemRendering = COA';
                t3lib_div::devlog( '[INFO] ' . $prompt, $this->extKey, 0 );
                $prompt = 'Please check the property itemRendering. Maybe it is overwritten by another extension.';
                t3lib_div::devlog( '[HELP] ' . $prompt, $this->extKey, 1 );
                break;
              case( ! ( $coa_name == 'COA' ) ):
                $prompt = 'Maybe this TypoScript property is unproper: itemRendering = ' . $coa_name;
                t3lib_div::devlog( '[ERROR] ' . $prompt, $this->extKey, 2 );
                $prompt = 'A proper TypoScript property would be: itemRendering = COA';
                t3lib_div::devlog( '[INFO] ' . $prompt, $this->extKey, 0 );
                $prompt = 'Please check the property itemRendering. Maybe it is overwritten by another extension.';
                t3lib_div::devlog( '[HELP] ' . $prompt, $this->extKey, 1 );
                break;
            }
          }
          $prompt = '<div style="background:red;color:white;padding:.2em;font-weight:bold;font-size:8pt;">
                      Item rendering failed. Please enable the DRS by TypoScript and investigate the logs!
                      See userFunc.drs.
                      Maybe the TypoScript property itemRendering is overriden by another extension.<br />
                      Browser - TYPO3 without PHP<br />
                      ' . __METHOD__ . ' (' . __LINE__ . ')
                     </div>';
          $str_outputEntry = $prompt;
            // DRS
        }
          // 120215, dwildt+
          // Error management
          // DRS
// COA; array ( 'wrap' => '<div class="csc-uploads-thumbnail csc-uploads-thumbnail-last">|</div>', 10 => 'TEXT', '10.' => array ( 'data' => 'register:linkedIcon', ), )
          // DRS

          // dwildt, 111106, +
        $outputEntries[] = $str_outputEntry;
      }
        // LOOP: filesData
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
      // there are files to list ...

      // stdWrap for the whole result
    if ($conf['stdWrap.'])
    {
      $out = $this->cObj->stdWrap($out, $conf['stdWrap.']);
    }

      // Error management
      // 120215, dwildt+
    if( empty( $out ) )
    {
        // DRS
      if ( $this->b_drs_download )
      {
        $prompt = 'Result is empty. This is an error probably.';
        t3lib_div::devlog( '[ERROR] ' . $prompt, $this->extKey, 3 );
      }
        // DRS
    }
      // 120215, dwildt+
      // Error management

      // Return the result
    return $out;
  }



  /***********************************************
  *
  * cObjData
  *
  **********************************************/

///**
// * cObjDataAddFieldsWoTablePrefix( ):
// *
// * @return    void
// * @internal  #44896
// * @version 4.4.4
// * @since   4.4.4
// */
//  private function cObjDataAddFieldsWoTablePrefix(  )
//  {
//    $this->cObjDataBackup( );
//    $this->cObj->data = $GLOBALS['TSFE']->cObj->data;
//
//    list( $currTable ) = explode( ':', $GLOBALS['TSFE']->currentRecord );
//
//      // FOREACH  : cObj->data in TSFE
//    foreach( array_keys( $GLOBALS['TSFE']->cObj->data ) as $tableField )
//    {
//      list( $table, $field ) = explode( '.', $tableField );
//      if( $table != $currTable )
//      {
//        continue;
//      }
//      $this->cObj->data[$field] = $GLOBALS['TSFE']->cObj->data[$tableField];
//    }
//      // FOREACH  : cObj->data in TSFE
//  }

/**
 * cObjDataBackup( ):
 *
 * @return    void
 * @internal  #44896
 * @version 4.4.4
 * @since   4.4.4
 */
  private function cObjDataBackup(  )
  {
    if( ! ( $this->bakCObjData === null ) )
    {
      return;
    }

    $this->bakCObjData    = $this->cObj->data;
//    $this->bakTsfeData    = $GLOBALS['TSFE']->cObj->data;
//    $this->bakCurrRecord  = $GLOBALS['TSFE']->currentRecord;

      // DRS
    if( $this->b_drs_cObjData )
    {
      $prompt = 'cObj->data are set (overriden).';
      t3lib_div::devlog( '[INFO/COBJDATA] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

/**
 * cObjDataReset( ):
 *
 * @return    void
 * @internal  #44896
 * @version 4.4.4
 * @since   4.4.4
 */
  private function cObjDataReset(  )
  {
    if( $this->bakCObjData === null )
    {
      return;
    }
    $this->cObj->data               = $this->bakCObjData;
//    $GLOBALS['TSFE']->cObj->data    = $this->bakTsfeData;
//    $GLOBALS['TSFE']->currentRecord = $this->bakCurrRecord;

      // DRS
    if( $this->b_drs_cObjData )
    {
      $prompt = 'cObj->data are reset.';
      t3lib_div::devlog( '[INFO/INIT] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

/**
 * cObjDataSet( ):
 *
 * @return    void
 * @internal  #44896, #i0001
 * @version 4.4.5
 * @since   4.4.4
 */
  private function cObjDataSet(  )
  {
    $this->cObjDataBackup( );
    $this->cObj->data = $GLOBALS['TSFE']->tx_browser_pi1->cObj->data;
      // #44858, 130207, dwildt, 1+
    list( $this->table ) = explode( ':', $GLOBALS['TSFE']->tx_browser_pi1->currentRecord );

    $this->cObjDataSetFieldWrapper( );
  }

/**
 * cObjDataSetFieldWrapper( ):
 *
 * @return    void
 * @internal  #44896, #i0001
 * @version 4.4.5
 * @since   4.4.5
 */
  private function cObjDataSetFieldWrapper(  )
  {
//var_dump( __METHOD__, __LINE__, var_export( $this->conf['userFunc.']['cObjDataFieldWrapper.'], true ) );
      // RETURN : if fields shouldn't  added with another key ...
    if( ! is_array( $this->conf['userFunc.']['cObjDataFieldWrapper.'] ) )
    {
      return;
    }
      // RETURN : if fields shouldn't  added with another key ...

      // FOREACH  : userFunc.cObjDataFieldWrapper. ...
    foreach( array_keys( $this->conf['userFunc.']['cObjDataFieldWrapper.'] )  as $key )
    {
        // CONTINUE : current value is an array
      if( substr( $key, -1, 1 ) == '.' )
      {
        continue;
      }
        // CONTINUE : current value is an array

        // Get the original field name. Example: tx_org_downloads.tx_flipit_layout
      $value = $this->conf['userFunc.']['cObjDataFieldWrapper.'][$key];

      if ( $this->b_drs_warn )
      {
        switch( true )
        {
          case( isset( $this->cObj->data[$key] ) ):
            $prompt = 'cObj->data[' . $key . '] will be overriden by cObj->data[' . $value . ']: ' .
                      $this->cObj->data[$value] ;
            t3lib_div::devlog( '[INFO/COBJDATA] ' . $prompt, $this->extKey, 2 );
            break;
          default:
            $prompt = 'cObj->data[' . $key . '] will become cObj->data[' . $value . ']: '  .
                      $this->cObj->data[$value] ;
            t3lib_div::devlog( '[INFO/COBJDATA] ' . $prompt, $this->extKey, 0 );
            break;
        }
      }
//var_dump( __METHOD__, __LINE__, 'cObj->data[' . $key . '] will become cObj->data[' . $value . ']: ' . $this->cObj->data[$value] );


        // Set value of original field to field with the new key. Example tx_flipit_layout = 'layout_01'
      $this->cObj->data[$key] = $this->cObj->data[$value];
    }
      // FOREACH  : userFunc.cObjDataFieldWrapper. ...
  }













  /***********************************************
   *
   * Helper
   *
   **********************************************/








  /**
 * helper_browser_linkProc( ):  This method handles the linkProc configuration
 *                              If linkProc has an element tx_browser_pi1, this element
 *                              will rendered instead of the default linkProc configuration.
 *                              It will be allocated the path to the current icon (preview or
 *                              application icon) out of the linkProc result to the
 *                              * register ICON_REL_PATH_FROM_LINCPROC
 *                              The tx_browser_pi1 configuration wll have access to the register
 *
 * @param	array		$conf:      TypoScript configuration
 * @param	array		$key:       Position of current document
 * @param	array		$fileName:  Filename of current document
 * @return	string		Replaced URL
 * @access private
 */
  private function helper_browser_linkProc( $conf, $key, $fileName )
  {
      // 130207, dwildt, 2+
    $arr_default_filelinks  = null;
    $arr_filelinks          = null;

      //////////////////////////////////////////////////////////////////////////
      //
      // Replace markers

      // Set marker array
    $marker['###KEY###']                = $key;
    $marker['###FILENAME###']           = $fileName;
      // 130207, dwildt, 2+
    $this->cObj->data['key']            = $key;
    $this->cObj->data['filename']       = $fileName;
      // Set marker array

      // Replace the marker in the TypoScript recursively
      // Workaround because of bug: $splitConf[$key]['itemRendering.']
      // will be changed, but it should not!
    $serialized_conf    = serialize( $conf['linkProc.'] );
    $coa_confLinkProc   = $this->cObj->substituteMarkerInObject
                          (
                            $conf['linkProc.'],
                            $marker
                          );
    $conf['linkProc.']  = unserialize( $serialized_conf );
      // Replace the marker in the TypoScript recursively
      // Replace markers



      // Link the current file with and without an icon (two links)
    $str_default_filelinks = $this->cObj->filelink( $fileName, $coa_confLinkProc );
      // Devide the two rendered links from a string into two elements
    list( $arr_default_filelinks[0], $arr_default_filelinks[1] ) = explode( '//**//', $str_default_filelinks );




      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN by handling the default linkProc configuration array

    if( ! isset( $coa_confLinkProc['tx_browser_pi1'] ) )
    {
        // RETURN the result
      return ( $arr_default_filelinks );
    }
      // RETURN by handling the default linkProc configuration array



      //////////////////////////////////////////////////////////////////////////
      //
      // Set register ICON_REL_PATH_FROM_LINCPROC

    $str_currIconRelPath      = $arr_default_filelinks[0];
      // I.e. <a href="uploads/tx_org/flyer_typo3_organiser_01.pdf" target="_blank" ><img src="typo3temp/pics/abfb01d4d2.jpg" width="200" height="408" alt="" /></a>
    list( $dummy, $str_srce ) = explode( 'src="', $str_currIconRelPath );
      // I.e. typo3temp/pics/abfb01d4d2.jpg" width="200" height="408" alt="" /></a>
    list( $str_srce )         = explode( '"',     $str_srce );
      // I.e. typo3temp/pics/abfb01d4d2.jpg
    $GLOBALS['TSFE']->register['ICON_REL_PATH_FROM_LINCPROC'] = $str_srce;

      // 130207, dwildt, 1+
    unset( $dummy );
      // Set register ICON_REL_PATH_FROM_LINCPROC



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN by handling the tx_browser_pi1 linkProc configuration array

    $str_filelinks =  $this->helper_cObjGetSingle
                      (
                        $coa_confLinkProc['tx_browser_pi1'],
                        $coa_confLinkProc['tx_browser_pi1.']
                      );
      // Devide the two rendered links from a string to two elements
    list( $arr_filelinks[0], $arr_filelinks[1] ) = explode( '//**//', $str_filelinks );


      // RETURN the result
    return ( $arr_filelinks );
      // RETURN by handling the tx_browser_pi1 linkProc configuration array
  }









  // 130207, dwildt, -
//  /**
// * helper_linkVarsWoL( ): Remove parameter 'L' from linkVars
// *
// * @return	string		$str_linkVarsWoL: linkVars without 'L'
// * @access private
// */
//  private function helper_linkVarsWoL( )
//  {
//      // Get linkVars
//    $str_linkVars = $GLOBALS['TSFE']->linkVars;
//
//      // LOOP linkVars: remove 'L'
//    $arr_linkVars = explode( '&', $str_linkVars );
//    foreach( $arr_linkVars as $str_linkVar )
//    {
//      list( $key_linkVar, $value_linkVar ) = explode( '=', $str_linkVar );
//        // remove 'L'
//      if( $key_linkVar != 'L' && ! empty( $key_linkVar ) )
//      {
//        $arr_linkVarsWoL[] = $key_linkVar . '=' . $value_linkVar;
//      }
//        // remove 'L'
//    }
//      // LOOP linkVars: remove 'L'
//
//      // Set linkVars without 'L'
//    $str_linkVarsWoL = implode( '&', ( array ) $arr_linkVarsWoL );
//    if( ! empty( $str_linkVarsWoL ) )
//    {
//      $str_linkVarsWoL = '&' . $str_linkVarsWoL;
//    }
//      // Set linkVars without 'L'
//
//      // DRS - Development Reporting System
//    if ( $this->b_drs_localisation )
//    {
//      if ( $str_linkVars != $str_linkVarsWoL )
//      {
//        $prompt = '\'L=' . $GLOBALS['TSFE']->sys_language_content . '\' is removed temporarily from linkVars.';
//        t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->extKey, 0);
//      }
//    }
//      // DRS - Development Reporting System
//
//      // RETURN linkVars without 'L'
//    return $str_linkVarsWoL;
//  }
  // 130207, dwildt, -








/**
 * helper_init_drs( ): Init the DRS - Development Reportinmg System
 *
 * @return	void
 * @access private
 * @version 4.4.5
 * @since 3.6.4
 */
  private function helper_init_drs( )
  {
      // #00002, 13-02-06, dwildt, +
    $this->arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);

    switch( true )
    {
      case( $this->arr_extConf['drs_mode'] == 'All' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_cObjData       = true;
        $this->b_drs_download       = true;
        $this->b_drs_localisation   = true;
        $this->b_drs_sql            = true;
        $this->b_drs_statistics     = true;
        $this->b_drs_devTodo        = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'cObj->data' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_cObjData       = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'Download' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_cObjData       = true;
        $this->b_drs_download       = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'Localisation' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_localisation   = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'SQL development' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_localisation   = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'Statistics' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_sql            = true;
        break;
      case( $this->arr_extConf['drs_mode'] == ':TODO: for Development' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        $this->b_drs_info           = true;
        $this->b_drs_devTodo        = true;
        break;
      case( $this->arr_extConf['drs_mode'] == 'Warnings and errors' ):
        $this->b_drs_error          = true;
        $this->b_drs_warn           = true;
        break;
      default:
        return;
        break;
    }
      // #00002, 13-02-06, dwildt, +

//    $conf = $this->conf;
//
//
//    if( ! isset( $conf['userFunc.']['drs'] ) )
//    {
//      return;
//    }
//
//    $coa_name               = $conf['userFunc.']['drs'];
//    $coa_conf_userFunc_drs  = $conf['userFunc.']['drs.'];
//
//    if( ! intval( $this->helper_cObjGetSingle( $coa_name, $coa_conf_userFunc_drs ) ) )
//    {
//      return;
//    }
//
//    $this->b_drs_error          = true;
//    $this->b_drs_warn           = true;
//    $this->b_drs_info           = true;
//    $this->b_drs_cObjData       = true;
//    $this->b_drs_download       = true;
//    $this->b_drs_localisation   = true;
//    $this->b_drs_download  = true;
//    $this->b_drs_sql            = true;
//    $this->b_drs_statistics     = true;
//    $prompt_01 = 'The DRS - Development Reporting System is enabled by TypoScript.';
//    $prompt_02 = 'Change it: Please look for userFunc = tx_browser_cssstyledcontent->render_uploads and for userFunc.drs.';
//    t3lib_div::devlog('[INFO/DRS] ' . $prompt_01, $this->extKey, 0);
//    t3lib_div::devlog('[HELP/DRS] ' . $prompt_02, $this->extKey, 1);
  }



 /**
  * helper_cObjGetSingle( ):
  *
  * @return    string        $value  : ....
  * @internal #i0001
  * @access   private
  * @version  4.4.5
  * @since    4.4.5
  */
  private function helper_cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
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
  * @param	string		$select_fields:  fields for the SQL select
  * @param	string		$from_table:     table for the SQL from
  * @param	integer		$llUid:          uid of the localised record
  * @return	array		$marker:         Array with the elements '###FIELD###' => 'value'
  * @access public
  * @version 3.9.3
  * @since 3.9.3
  */
  public function sql_marker( $select_fields, $from_table, $llUid )
  {
    $marker = null;

      ////////////////////////////////////////////////////////////////////////////////
      //
      // Set the query

      // Values
    $enablefields   = $this->cObj->enableFields( $from_table );
    $where_clause   = 'uid = ' . intval( $llUid ) . ' ' . $enablefields;
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
    if ( $this->b_drs_localisation || $this->b_drs_sql )
    {
      t3lib_div::devlog('[INFO/SQL+LOCALISATION] ' . $query, $this->extKey, 0);
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
      if( $this->b_drs_error )
      {
        t3lib_div::devlog('[ERROR/SQL] '. $query,  $this->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '. $error,  $this->extKey, 3);
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_cssstyledcontent.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_cssstyledcontent.php']);
}


?>