<?php

namespace Netzmacher\Browser\Utility;

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
class FrontendEditingController extends ActionController
{

  /**
   * @var array Extension Configuration from ext_conf_template.txt
   */
  private $_aExtConf;

  /**
   * @var array Powermail GET and POST params
   */
  private $_aPowermailGP;

  /**
   * @var array record for an INSERT or an UPDATE
   */
  private $_feUserRecordKeyValues = array();

  /**
   * @var object
   */
  private $_oParamsPowermail;

  /**
   * @var TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
   */
  private $_oCObj;

  /**
   * @var object
   */
  private $_oTCA;

  /**
   * @var object
   */
  private $_oTables;

  /**
   * @var string The current table
   */
  private $_sCurrentTable;

  /**
   * @var string The current table properties
   */
  private $_sCurrentTableProperties;

  /**
   * @var int The uid of the current table record
   */
  private $_sCurrentTableRecordUid;

  /**
   * @var boolean Flag for the DRS - Development Reporting System
   */
  protected $b_drs_all = FALSE;
  protected $b_drs_error = FALSE;
  protected $b_drs_warn = FALSE;
  protected $b_drs_info = FALSE;
  protected $b_drs_frontendediting = FALSE;

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
    if ( !$this->_isPowermailActionCreate() )
    {
      return;
    }
    $this->_tables();
    var_dump( __METHOD__, __LINE__ );
    die();
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
    $this->_initExtConf();
    $this->_initDrs();
    $this->_initClasses();
    $this->_setPowermailGP();
    $this->_initTables();
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
    $this->_initClassesObjectManager();
    $this->_initClassesCObj();
    $this->_initClassesPowermailParams();
    $this->_initClassesTCA();
    $this->_initClassesTables();
  }

  /**
   * _initClassesCObj( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesCObj()
  {
    $this->_oCObj = $this->_oObjectManager->get(
            'TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'
    );
  }

  /**
   * _initClassesObjectManager( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesObjectManager()
  {
    $this->_oObjectManager = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\Object\\ObjectManager' );
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
   * _initClassesTCA( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesTCA()
  {
    $this->_oTCA = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\TCA' );
  }

  /**
   * _initClassesTables( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initClassesTables()
  {
    $this->_oTables = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\Tables' );
  }

  /**
   * _initDrs( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initDrs()
  {
    $drsMode = $this->_aExtConf[ 'drs_mode' ];

    switch ( $drsMode )
    {
      case('All'):
      case('Frontend Editing'):
        $this->b_drs_all = TRUE;
        $this->b_drs_error = TRUE;
        $this->b_drs_warn = TRUE;
        $this->b_drs_info = TRUE;
        $this->b_drs_frontendediting = TRUE;
        $prompt = 'DRS - Development Reporting System: ' . $drsMode;
        GeneralUtility::devlog( '[INFO/DRS] ' . $prompt, __CLASS__, 0 );
        break;
      case('Warnings and errors'):
        $this->b_drs_error = TRUE;
        $this->b_drs_warn = TRUE;
        $prompt = 'DRS - Development Reporting System: ' . $drsMode;
        GeneralUtility::devlog( '[INFO/DRS] ' . $prompt, __CLASS__, 0 );
        break;
      default:
        break;
    }
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
   * _initTables( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _initTables()
  {
    $this->_aTables = array();

    // if fe_users exits, it should be the firt element in $_aTables
    if ( isset( $this->settings[ 'mapping' ][ 'fe_users' ] ) )
    {
      $this->_aTables[ 'fe_users' ] = array(
        'uid' => 'new',
        'query' => array(
          'fields' => array(
          ),
        ),
        'postProcess' => array(
        ),
      );
    }

    foreach ( array_keys( ( array ) $this->settings[ 'mapping' ] ) AS $table )
    {
      switch ( TRUE )
      {
        case( $table == 'fe_users'):
        case( $table == '_typoScriptNodeValue'):
          continue;
        default:
          $this->_aTables[ $table ] = array(
            'uid' => 'new',
            'query' => array(
              'fields' => array(
              ),
            ),
            'postProcess' => array(
            ),
          );
          break;
      }
    }

//    var_dump( __METHOD__, __LINE__, array_keys( $this->settings[ 'mapping' ] ), $this->_aTables );
  }

  /**
   * _tables( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _tables()
  {
    $this->_setCObjStart( 'fe_users' );
    $this->_oTables->setCObj( $this->_oCObj );
    foreach ( $this->_aTables AS $this->_sCurrentTable => $this->_sCurrentTableProperties )
    {
      $this->_oTables->main( $this->_sCurrentTable, $this->settings );
      $this->_aTables[ $this->_sCurrentTable ][ 'uid' ] = $this->_oTables->getUid();
//      $this->_aTables[ $table ][ 'query' ] = $this->_oTables->getQuery();
//      $this->_aTables[ $table ][ 'postProcess' ] = $this->_oTables->getPostProcess();
//      //$this->_feUser();
//      $this->_feUserRecordKeyValues = $this->_oTables->getRecordKeyValues();
//
      if ( empty( $this->_oTables->getKeyValues() ) )
      {
        $this->_dataActionError( 'No data transferred! Table: ' . $this->_sCurrentTable );
        continue;
      }

      $this->_tablesExec();
    }
  }

  /**
   * _tablesExec( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _tablesExec()
  {
    switch ( $this->_oTables->getIsLoggedIn() )
    {
      case(TRUE):
        $this->_tablesExecUpdate();
        break;
      case(FALSE):
      default:
        $this->_tablesExecInsert();
        break;
    }

    $this->_tablesExecPostProcess();
  }

  /**
   * _tablesExecInsert( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _tablesExecInsert()
  {
    //$insertData = $this->_feUserRecordKeyValues;
    $insertData = $this->_oTables->getKeyValues();

    $query = $GLOBALS[ 'TYPO3_DB' ]->INSERTquery(
            $this->_sCurrentTable
            , $insertData
    );
    var_dump( __METHOD__, __LINE__, $this->_feUserRecordKeyValues, $query );
//    return;

    $GLOBALS[ 'TYPO3_DB' ]->exec_INSERTquery(
            $this->_sCurrentTable
            , $insertData
    );
    $this->_setCurrentTableRecordUid();

    //$this->_dataActionError( 'Update isn\'t possible.' );
    $this->_dataActionSuccess( 'fe_users data is inserted successfully with uid #' . $uid );
  }

  /**
   * _tablesExecPostProcess( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _tablesExecPostProcess()
  {

  }

  /**
   * _tablesExecUpdate( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _tablesExecUpdate()
  {
    //$updateData = $this->_feUserRecordKeyValues;
    $updateData = $this->_oTables->getKeyValues();

    if ( isset( $this->_feUserRecordKeyValues[ 'uid' ] ) )
    {
      unset( $updateData[ 'uid' ] );
      $uid = $this->_feUserRecordKeyValues[ 'uid' ];
    }

//    var_dump( __METHOD__, __LINE__, $this->_feUserRecordKeyValues );
//    return;
    $query = $GLOBALS[ 'TYPO3_DB' ]->UPDATEquery(
            $this->_sCurrentTable
            , 'uid = ' . $uid
            , $updateData
    );
    var_dump( __METHOD__, __LINE__, $this->_feUserRecordKeyValues, $query );
//    return;
    $GLOBALS[ 'TYPO3_DB' ]->exec_UPDATEquery(
            $this->_sCurrentTable
            , 'uid = ' . $uid
            , $updateData
    );
    $this->_setCurrentTableRecordUid();
    //$this->_dataActionError( 'Update isn\'t possible.' );
    $this->_dataActionSuccess( 'fe_users data is updated successfully' );
  }

  /**
   * _isPowermailActionCreate( ) :
   *
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _isPowermailActionCreate()
  {
    if ( $this->_aPowermailGP[ 'action' ] != 'create' )
    {
      return FALSE;
    }
    return TRUE;
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
//    return $this->_aPowermailGP;
//  }

  /**
   * _setCObjStart( ) :
   *
   * @param string $table
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setCObjStart( $table )
  {
    $data = $this->_setCObjStartData();
    $this->_oCObj->start( $data, $table );
  }

  /**
   * _setCObjStartData( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setCObjStartData()
  {
    $data = $this->_setCObjStartDataPowermail();
    return $data;
  }

  /**
   * _setCObjStartDataPowermail( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setCObjStartDataPowermail()
  {
    foreach ( ( array ) $this->_aPowermailGP AS $key => $value )
    {
      if ( is_array( $value ) )
      {
        $value = implode( ',', $value );
      }
      $data[ $key ] = $value;
      list($table, $field) = explode( '__', $key );
      if ( $field )
      {
        $tableField = $table . '.' . $field;
        $data[ $tableField ] = $value;
      }
    }
    return $data;
  }

  /**
   * _setCurrentTableRecordUid( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setCurrentTableRecordUid()
  {
    $uid = $GLOBALS[ 'TYPO3_DB' ]->sql_insert_id();
    $table = $this->_sCurrentTable;
    $tableField = $table . '.uid';

    $this->_sCurrentTableRecordUid[ $table ] = $uid;
    $this->_oCObj->data[ $tableField ] = $uid;
    var_dump( __METHOD__, __LINE__, $this->_oCObj->data );
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
    $this->_aPowermailGP = $this->_oParamsPowermail->getGP();
  }

}
