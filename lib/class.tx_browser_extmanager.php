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
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    browser
* @version 3.6.1
* @since 3.6.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_browser_extmanager
 *   68:     function promptCheckUpdate()
 *  103:     function promptCurrIP()
 *  138:     function promptExternalLinks()
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_extmanager
{









  /**
 * promptCheckUpdate(): Displays the update message. The message contains the current IP of the backend user
 *
 * @return	string		message wrapped in HTML
 * @since 3.6.0
 * @version 3.6.0
 */
  function promptCheckUpdate()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $str_prompt = null;

      $str_prompt = $str_prompt.'
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptCheckUpdateBody'). '
  </div>
</div>';

    return $str_prompt;
  }









  /**
 * promptCurrIP(): Displays the IP of the current backend user
 *
 * @return	string		message wrapped in HTML
 * @since 3.6.0
 * @version 3.6.0
 */
  function promptCurrIP()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $str_prompt = null;

      $str_prompt = $str_prompt.'
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptCurrIPBody') . ': ' . t3lib_div :: getIndpEnv('REMOTE_ADDR') . '
  </div>
</div>';

    return $str_prompt;
  }









  /**
 * promptExternalLinks(): Displays the quick start message.
 *
 * @return	string		message wrapped in HTML
 * @since 3.6.1
 * @version 3.6.1
 */
  function promptExternalLinks()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

    $str_prompt = null;

    $str_prompt = $str_prompt . '
<div class="message-body">
  ' . $GLOBALS['LANG']->sL('LLL:EXT:browser/lib/locallang.xml:promptExternalLinksBody'). '
</div>';
      
//      // #48613, dwildt, 3+
//    $arrDefinedConstants  = get_defined_constants( );
//    $path_typo3conf       = $arrDefinedConstants['PATH_typo3conf'];
//    $str_prompt           = str_replace( '%PATH_typo3conf%', $path_typo3conf, $str_prompt );
//      // #48613, dwildt, 3+

    return $str_prompt;
  }









}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_extmanager.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_extmanager.php']);
}

?>