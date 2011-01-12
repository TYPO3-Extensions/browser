<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 - Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
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
* Class that adds the wizard icon.
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
 *   46: class tx_browser_pi5_backend_wizicon
 *   69:     function getLL()
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi5_backend_wizicon
{
	function proc($wizardItems)
  {
  	global $LANG;
   	$LL = $this->getLL();

    $wizardItems['plugins_tx_browser_pi5'] =
			array
			(
      	'icon'				=> t3lib_extMgm::extRelPath('browser').'pi5/browser_wizard.gif',
        'title'   		=> $LANG->getLLL('wizard.list_type_pi5', $LL),
        'description'	=> $LANG->getLLL('wizard.list_type_pi5.desc', $LL),
        'params'			=> '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=browser_pi5'
      );
    return $wizardItems;
  }

  /**
 * Get the locallang for class use out of an XML file
 *
 * @return	array		Array of the locallang data
 */
  function getLL()
	{
  	$path2llXml = t3lib_extMgm::extPath('browser').'locallang_db.xml';
    $llXml 			= implode('', file($path2llXml));
		$arr_ll			= t3lib_div::xml2array($llXml, $NSprefix='', $reportDocTag=false);
    $LOCAL_LANG	= $arr_ll['data'];
    return $LOCAL_LANG;
  }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi5/class.tx_browser_pi5_backend_wizicon.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi5/class.tx_browser_pi5_backend_wizicon.php']);
}

?>
