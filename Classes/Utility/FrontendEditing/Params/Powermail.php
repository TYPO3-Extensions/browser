<?php

namespace Netzmacher\Browser\Utility\FrontendEditing\Params;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 - 2016 -  Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * @subpackage browser
 * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @version 7.4.0
 * @since 7.4.0
 * @internal #i0215
 */
class Powermail
{
  /**
   * @var array Powermail GET and POST params
   */
  private $_powermailGP = NULL;

  /**
   * getGP( ) :
   *
   * @return array
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getGP()
  {
    if( $this->_powermailGP != NULL)
    {
      return $this->_powermailGP;
    }

    $this->_setGP();
    return $this->_powermailGP;
  }

  /**
   * _setPowermailGP( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setGP()
  {
    $aGet = GeneralUtility::_GET();
    $aPost = GeneralUtility::_POST();
    $powermailGP = ( array ) $aGet[ 'tx_powermail_pi1' ] + ( array ) $aPost[ 'tx_powermail_pi1' ][ 'field' ];

    $this->_powermailGP = $powermailGP;
  }

}