<?php

namespace Netzmacher\Browser\Controller;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 -  Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
class FrontendEditingController extends ActionController
{

  /**
   * dataAction( ) : Create PDF from HTML (using TCPDF through t3_tcpdf extension)
   *          * Provides a PDF file for download
   *          * It prints the HTML content in debug mode
   *
   * @return void
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   * @internal #i0215
   *
   */
  public function dataAction()
  {
    FrontendEditingController::_powermail();
  }

  /**
   * _powermail( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _powermail()
  {
    // action: create (workflow: confirmation)
    $aGet = GeneralUtility::_GET();
    $aPost = GeneralUtility::_POST();
    $aPowermail = $aGet[ 'tx_powermail_pi1' ] + $aPost[ 'tx_powermail_pi1' ][ 'field' ];

    switch ( TRUE )
    {
      case($aPowermail[ 'action' ] != 'create'):
        //$this->view->assign('condition', FALSE);
        return;
      default:
      // follow the workflow;
    }

    $sParams = var_export( $aPowermail, TRUE );

    $this->view->assignMultiple(
            array(
              'condition' => TRUE,
              'result' => 'Result: ' . $sParams . '<br />',
              'action' => 'Action: ' . $aPowermail[ 'action' ] . '<br />',
              'phpclassmethod' => __METHOD__ . ' (#' . __LINE__ . ')'
            )
    );

    return;
  }

}
