<?php

namespace Netzmacher\Browser\Utility\UserFunc;

use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015-2016 -  Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

/**
 * Class for rendering a HTML page by TCPDF methods
 *
 * @package TYPO3
 * @subpackage org
 * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @version 7.4.0
 * @since 7.4.0
 */
class TCAPrompt
{

  /**
   * Path to locallang file (with : as postfix)
   *
   * @var string
   */
  protected $_locallangPath = 'LLL:EXT:browser/Resources/Private/Language/FlexForms/pi6/locallang.xml:';

  /**
   * Max width of the div area of the prompt
   *
   * @var string
   */
  protected $_maxWidth = '600px';

  /**
   * tx_org_downloads( ) : Returns a note, how to configure filter by constant editor
   *
   * @return string
   * @access public
   * @version 7.8.0
   * @since 7.8.0
   *
   */
  public function sheetSDEFHelpPrompt()
  {
    return $this->_divPrompt( 'sheet_sDEF.help.prompt' );
  }

  /**
   * tx_org_jobs( ) :
   *
   * @param string $llLabel
   * @return string
   * @access private
   * @version 7.8.0
   * @since 7.8.0
   *
   */
  private function _divPrompt( $llLabel )
  {
    //.message-notice
    //.message-information
    //.message-ok
    //.message-warning
    //.message-error

    $subject = LocalizationUtility::translate( $this->_locallangPath . $llLabel, '<i>' . $llLabel . '</i>' );

    $prompt = '
      <div class="typo3-message message-information" style="max-width:' . $this->_maxWidth . ';">
        <div class="message-body">
          ' . $subject . '
        </div>
      </div>';

    return $prompt;
  }

}
