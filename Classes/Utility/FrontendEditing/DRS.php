<?php

namespace Netzmacher\Browser\Utility\FrontendEditing;

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
class DRS
{

  /**
   * @var boolean Internal Flag
   */
  private $_bStatus = NULL;

  /**
   * @var boolean Flag for the DRS - Development Reporting System
   */
  private $_bError = FALSE;
  private $_bFrontendEditing = FALSE;
  private $_bInfo = FALSE;
  private $_bSQL = FALSE;
  private $_bWarn = FALSE;

  /**
   * __construct( ) :
   *
   * @return void
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function __construct()
  {
    if ( $this->_bStatus !== NULL )
    {
      return;
    }

    $this->_initExtConf();
    $drsMode = $this->_aExtConf[ 'drs_mode' ];

    switch ( $drsMode )
    {
      case('All'):
      case('Frontend Editing'):
        $this->_bError = TRUE;
        $this->_bWarn = TRUE;
        $this->_bInfo = TRUE;
        $this->_bFrontendEditing = TRUE;
        $this->_bSQL = TRUE;
        $prompt = 'DRS - Development Reporting System: ' . $drsMode;
        GeneralUtility::devlog( '[INFO/DRS] ' . $prompt, __CLASS__ . '#' . __LINE__, 0 );
        break;
      case('SQL development'):
        $this->_bError = TRUE;
        $this->_bWarn = TRUE;
        $this->_bInfo = TRUE;
        $this->_bSQL = TRUE;
        $prompt = 'DRS - Development Reporting System: ' . $drsMode;
        GeneralUtility::devlog( '[INFO/DRS] ' . $prompt, __CLASS__ . '#' . __LINE__, 0 );
        break;
      case('Warnings and errors'):
        $this->_bError = TRUE;
        $this->_bWarn = TRUE;
        $prompt = 'DRS - Development Reporting System: ' . $drsMode;
        GeneralUtility::devlog( '[INFO/DRS] ' . $prompt, __CLASS__ . '#' . __LINE__, 0 );
        break;
      default:
        break;
    }

    $this->_bStatus = TRUE;
  }

  /**
   * _initExtConf( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initExtConf()
  {
    $this->_aExtConf = unserialize( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXT' ][ 'extConf' ][ 'browser' ] );
  }

  /**
   * getDrsSQL( ) :
   *
   * @return boolean
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getDrsSQL()
  {
    //$this->_init();
    return $this->_bSQL;
  }

  /**
   * getDrsError( ) :
   *
   * @return boolean
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getDrsError()
  {
    //$this->_init();
    return $this->_bError;
  }

  /**
   * getDrsFrontendEditing( ) :
   *
   * @return boolean
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getDrsFrontendEditing()
  {
//    $this->_init();
    return $this->_bFrontendEditing;
  }

  /**
   * getDrsInfo( ) :
   *
   * @return boolean
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getDrsInfo()
  {
//    $this->_init();
    return $this->_bInfo;
  }

  /**
   * getDrsWarn( ) :
   *
   * @return boolean
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getDrsWarn()
  {
//    $this->_init();
    return $this->_bWarn;
  }

}
