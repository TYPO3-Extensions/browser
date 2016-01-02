<?php

namespace Netzmacher\Browser\Controller;

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
   * @var array record for an INSERT or an UPDATE
   */
  private $_feUserRecordKeyValues = array();

  /**
   * @var int
   */
  private $_feUserUid;

  /**
   * @var object
   */
  private $_oParamsPowermail;

  /**
   * @var object
   */
  private $_oTCATables;

  /**
   * @var object
   */
  private $_oTablesFeusers;

  /**
   * @var object
   */
  private $_oTypoScriptService;

  /**
   * @var array Powermail GET and POST params
   */
  private $_powermailGP;

  /**
   * sqlRepository
   *
   * @var \Netzmacher\Browser\Domain\Repository\SqlRepository
   * @inject
   */
  protected $sqlRepository;

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
    $this->_init();
    if ( !$this->_powermailActionIsCreate() )
    {
      return;
    }
    $this->_insert();
  }

  /**
   * _dataActionError( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _dataActionError( $prompt = 'Sorry, an unknown error occurs.' )
  {
    $this->_dataActionErrorUnsetPowermailAction();
// pi6PromptError
    $this->view->assignMultiple(
            array(
              'condition' => 'error',
              'prompterror' => 'TYPO3 Browser Frontend Editing - ERROR: ' . $prompt
            )
    );
  }

  /**
   * _dataActionErrorUnsetPowermailAction( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _dataActionErrorUnsetPowermailAction()
  {
    unset( $_GET[ 'tx_powermail_pi1' ][ 'action' ] );
    unset( $_POST[ 'tx_powermail_pi1' ][ 'action' ] );
  }

  /**
   * _dataActionSuccess( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _dataActionSuccess( $prompt = 'Your posting was successful.' )
  {
// pi6PromptSuccess
    $this->view->assignMultiple(
            array(
              'condition' => 'success',
              'promptsuccess' => 'TYPO3 Browser Frontend Editing: ' . $prompt
            )
    );
    unset( $_GET[ 'tx_powermail_pi1' ][ 'action' ] );
    unset( $_POST[ 'tx_powermail_pi1' ][ 'action' ] );
  }

  /**
   * _init( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _init()
  {
    $this->_initClasses();
    $this->_setPowermailGP();
  }

  /**
   * _initClasses( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClasses()
  {
    $this->_initClassesPowermailParams();
    $this->_initClassesTCATables();
    $this->_initClassesTablesFeusers();
    $this->_initClassesTypoScriptService();
  }

  /**
   * _initClassesPowermailParams( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesPowermailParams()
  {
    $this->_oParamsPowermail = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\Params\\Powermail' );
  }

  /**
   * _initClassesTCATables( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesTCATables()
  {
    $this->_oTCATables = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\TCA\\Tables' );
  }

  /**
   * _initClassesTablesFeusers( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesTablesFeusers()
  {
    $this->_oTablesFeusers = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\Tables\\FeUsers' );
  }

  /**
   * _initClassesTypoScriptService( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesTypoScriptService()
  {
    $this->_oTypoScriptService = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\Service\\TypoScriptService' );
  }

  /**
   * _insert( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insert()
  {
    //$this->_feUser();
    $this->_oTablesFeusers->init( $this->settings, 'fe_users' );
    //$this->_oTablesFeusers->init( $this->settings, 'tx_org_job' );
    $this->_feUserRecordKeyValues = $this->_oTablesFeusers->getRecordKeyValues();

    if ( empty( $this->_feUserRecordKeyValues ) )
    {
      $this->_dataActionError( 'No data transferred!' );
      return;
    }
    $this->_insertExec();
  }

  /**
   * _insertExec( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insertExec()
  {
    $this->_insertExecLocal();
  }

  /**
   * _insertExecLocal( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insertExecLocal()
  {
    switch ( $this->_oTablesFeusers->getIsLoggedIn() )
    {
      case(TRUE):
        $this->_insertExecLocalUpdate();
        break;
      case(FALSE):
      default:
        $this->_insertExecLocalInsert();
        break;
    }

    $this->_insertExecLocalPostProcess();
  }

  /**
   * _insertExecLocalInsert( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insertExecLocalInsert()
  {
    $insertData = $this->_feUserRecordKeyValues;

    $GLOBALS[ 'TYPO3_DB' ]->exec_INSERTquery(
            'fe_users'
            , $insertData
    );
    $this->_setFeUserUid();

    //$this->_dataActionError( 'Update isn\'t possible.' );
    $this->_dataActionSuccess( 'fe_users data is inserted successfully with uid #' . $uid );
  }

  /**
   * _insertExecLocalPostProcess( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insertExecLocalPostProcess()
  {

  }

  /**
   * _insertExecLocalUpdate( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _insertExecLocalUpdate()
  {
    $updateData = $this->_feUserRecordKeyValues;
    if ( isset( $this->_feUserRecordKeyValues[ 'uid' ] ) )
    {
      unset( $updateData[ 'uid' ] );
      $uid = $this->_feUserRecordKeyValues[ 'uid' ];
    }

    var_dump( __METHOD__, __LINE__, $this->_feUserRecordKeyValues );
    die();
    $query = $GLOBALS[ 'TYPO3_DB' ]->UPDATEquery(
            'fe_users'
            , 'uid = ' . $uid
            , $updateData
    );
    var_dump( __METHOD__, __LINE__, $this->_feUserRecordKeyValues, $query );
    die();
    $GLOBALS[ 'TYPO3_DB' ]->exec_UPDATEquery(
            'fe_users'
            , 'uid = ' . $uid
            , $updateData
    );
    $this->_setFeUserUid();
    //$this->_dataActionError( 'Update isn\'t possible.' );
    $this->_dataActionSuccess( 'fe_users data is updated successfully' );
  }

  /**
   * _powermailActionIsCreate( ) :
   *
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _powermailActionIsCreate()
  {
    switch ( TRUE )
    {
      case($this->_powermailGP[ 'action' ] != 'create'):
        return FALSE;
      default:
        return TRUE;
    }
  }

//  /**
//   * _getPowermailGP( ) :
//   *
//   * @return array
//   * @access private
//   * @version 7.4.0
//   * @since 7.4.0
//   */
//  private function _getPowermailGP()
//  {
//    return $this->_powermailGP;
//  }

  /**
   * _setFeUserUid( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setFeUserUid()
  {
    $this->_feUserUid = $GLOBALS[ 'TYPO3_DB' ]->sql_insert_id();
  }

  /**
   * _setPowermailGP( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setPowermailGP()
  {
    $this->_powermailGP = $this->_oParamsPowermail->getGP();
  }

}
