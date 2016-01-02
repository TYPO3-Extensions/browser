<?php

namespace Netzmacher\Browser\Utility\Tables;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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
class FeUsers
{

  /**
   * @var array Powermail GET and POST params
   */
  private $_aPowermailGP;

  /**
   * @var array record for an INSERT or an UPDATE
   */
  private $_aRecordKeyValues = array();

  /**
   * @var array TypoScript settings array of the parent object
   */
  private $_aSettings;

  /**
   * @var array
   */
  private $_aSupportedTCAConfigInternalTypes = array(
    'file'
          //, 'file_reference'
          //, 'folder'
          //, 'db'
  );

  /**
   * @var array
   */
  private $_aSupportedTCAConfigTypes = array(
    'check'
    //, 'flex'
    , 'group'
    //, 'inline'
    , 'input'
    //, 'none'
    //, 'passthrough'
    , 'radio'
    , 'select'
    , 'text'
          //, 'user'
  );

  /**
   * @var boolean
   */
  private $_bIsLoggedIn = FALSE;

  /**
   * @var ContentObjectRenderer
   */
  private $_cObj;

  /**
   * @var object
   */
  private $_oTCATables;

  /**
   * @var object
   */
  private $_oTypoScriptService;

  /**
   * @var string Comma separated list of fields for handle
   */
  private $_sFieldsForHandle;

  /**
   * @var string Table
   */
  private $_sTable;

  /**
   * getIsLoggedIn( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getIsLoggedIn()
  {
    return $this->_bIsLoggedIn;
  }

  /**
   * getRecordKeyValues( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getRecordKeyValues()
  {
    return $this->_aRecordKeyValues;
  }

  /**
   * init( ) :
   *
   * @param array $settings
   * @param string $table
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  public function init( $settings, $table )
  {
    $this->_setSettings( $settings );
    $this->_setTable( $table );
    $this->_classes();
    $this->_setFeUserIsLoggedIn();
    $this->_setPowermailGP();
    $this->_record();
  }

  /**
   * _record( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _record()
  {
    $this->_recordFieldsForHandle();
    $this->_recordSet();
  }

  /**
   * _recordFieldsForHandle( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordFieldsForHandle()
  {
    $aTCAFields = $this->_recordFieldsForHandleFromTCA();
    $aTSFields = $this->_recordFieldsForHandleFromTypoScript();
    $aPMFields = $this->_recordFieldsForHandleFromPowermail();

// intersect of all field groups
    $aIntersect = array_intersect( $aTCAFields, $aTSFields, $aPMFields );
    $sIntersect = implode( ',', $aIntersect );

    $this->_sFieldsForHandle = $sIntersect;
  }

  /**
   * _recordFieldsForHandleFromPowermail( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordFieldsForHandleFromPowermail()
  {
    $aPMFields = array();
    foreach ( array_keys( $this->_aPowermailGP ) as $key )
    {
      list($table, $field) = explode( '__', $key );
      if ( $table != $this->_sTable )
      {
        continue;
      }
      $aPMFields[] = $field;
    }
    return $aPMFields;
  }

  /**
   * _recordFieldsForHandleFromTCA( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordFieldsForHandleFromTCA()
  {
    $aAllowedTCAFields = array();
    // fields from TCA
    $aTCAFields = array_keys( $GLOBALS[ 'TCA' ][ $this->_sTable ][ 'columns' ] );
    foreach ( $aTCAFields as $field )
    {
      $sConfigType = $GLOBALS[ 'TCA' ][ $this->_sTable ][ 'columns' ][ $field ][ 'config' ][ 'type' ];
      if ( !in_array( $sConfigType, $this->_aSupportedTCAConfigTypes ) )
      {
        continue;
      }
      $aAllowedTCAFields[] = $field;
    }
    return $aAllowedTCAFields;
  }

  /**
   * _recordFieldsForHandleFromTypoScript( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordFieldsForHandleFromTypoScript()
  {
    $sTSFields = $this->_aSettings[ 'mapping' ][ $this->_sTable ][ 'allowedFields' ];
    $sTSFields = str_replace( ' ', NULL, $sTSFields );
    $aTSFields = explode( ',', $sTSFields );
    return $aTSFields;
  }

  /**
   * _recordSet( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSet()
  {
    $this->_recordSetKeyValues();
    $this->_recordSetLocal();
  }

  /**
   * _recordSetKeyValues( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValues()
  {
    $keyValues = $this->_recordSetKeyValuesPowermail();
    if ( empty( $keyValues ) )
    {
      $this->_aRecordKeyValues = $keyValues;
      return;
    }
    $keyValues = $this->_recordSetKeyValuesUid( $keyValues );
    $keyValues = $this->_recordSetKeyValuesUsername( $keyValues );
    $keyValues = $this->_recordSetKeyValuesDefaults( $keyValues );

    $this->_aRecordKeyValues = $keyValues;
    return;
  }

  /**
   * _recordSetKeyValuesDefaults( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesDefaults( $keyValues )
  {
    $aFieldsForHandle = $this->_recordSetKeyValuesDefaultsForHandle( $keyValues );
    $keyValues = $this->_recordSetKeyValuesDefaultsHandle( $keyValues, $aFieldsForHandle );

    return $keyValues;
  }

  /**
   * _recordSetKeyValuesDefaultsForHandle( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesDefaultsForHandle( $keyValues )
  {
    $aHandledKeys = array_keys( $keyValues );
    $aDefaultKeys = array_keys( $this->_aSettings[ 'mapping' ][ $this->_sTable ][ 'defaults' ] );
    $aFieldsForHandle = array_diff( $aDefaultKeys, $aHandledKeys, array( '_typoScriptNodeValue' ) );

    return $aFieldsForHandle;
  }

  /**
   * _recordSetKeyValuesDefaultsHandle( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesDefaultsHandle( $keyValues, $aFieldsForHandle )
  {
    if ( empty( $aFieldsForHandle ) )
    {
      return $keyValues;
    }

    foreach ( $aFieldsForHandle as $field )
    {
      $typoscriptPlain = $this->_aSettings[ 'mapping' ][ $this->_sTable ][ 'defaults' ][ $field ];
      $name = $typoscriptPlain[ '_typoScriptNodeValue' ];
      $conf = $this->_oTypoScriptService->convertPlainArrayToTypoScriptArray( $typoscriptPlain );

      $value = $this->_cObj->cObjGetSingle( $name, $conf );

      switch ( TRUE )
      {
        case(!empty( $value )):
        case($value === 0 ):
        case($value === "0" ):
          $keyValues[ $field ] = $value;
          continue;
        default:
          continue;
      }
    }

    return $keyValues;
  }

  /**
   * _recordSetKeyValuesPowermail( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesPowermail()
  {
    $aFieldsForHandle = explode( ',', $this->_sFieldsForHandle );
    foreach ( $aFieldsForHandle as $field )
    {
      if ( empty( $field ) )
      {
        continue;
      }
      $pmKey = $this->_sTable . '__' . $field;
      $keyValues[ $field ] = $this->_aPowermailGP[ $pmKey ];
    }
    return $keyValues;
  }

  /**
   * _recordSetKeyValuesUid( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesUid( $keyValues )
  {
    if ( !$this->_bIsLoggedIn )
    {
      return $keyValues;
    }
    $keyValues[ 'uid' ] = $GLOBALS[ 'TSFE' ]->fe_user->user[ 'uid' ];
    return $keyValues;
  }

  /**
   * _recordSetKeyValuesUsername( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetKeyValuesUsername( $keyValues )
  {
    if ( !$this->_bIsLoggedIn )
    {
      return $keyValues;
    }
    if ( !empty( $keyValues[ 'email' ] ) )
    {
      $keyValues[ 'username' ] = $keyValues[ 'email' ];
      return $keyValues;
    }
    if ( !empty( $keyValues[ 'name' ] ) )
    {
      list($first) = explode( ' ', $keyValues[ 'name' ] );
      $keyValues[ 'username' ] = strtolower( $first );
      return $keyValues;
    }
    return $keyValues;
  }

  /**
   * _recordSetLocal( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetLocal()
  {
    $this->_recordSetLocalUid();
    $this->_recordSetLocalFields();
    $this->_recordSetLocalPostProcess();
  }

  /**
   * _recordSetLocalFields( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetLocalFields()
  {
    $this->_oTCATables->local( $this->_sTable, $this->_aRecordKeyValues );
  }

  /**
   * _recordSetLocalPostProcess( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetLocalPostProcess()
  {

  }

  /**
   * _recordSetLocalUid( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetLocalUid()
  {

  }

  /**
   * _classes( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classes()
  {
    $this->_classesObjectManager();
    $this->_classesCObj();
    $this->_classesPowermailParams();
    $this->_classesTCATables();
    $this->_classesTypoScriptService();
  }

  /**
   * _classesCObj( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classesCObj()
  {
    $this->_cObj = $this->_oObjectManager->get(
            'TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'
    );
    $data = array(
      'dummy' => 'dummy',
    );
    $this->_cObj->start( $data, $this->_sTable );
  }

  /**
   * _classesObjectManager( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classesObjectManager()
  {
    $this->_oObjectManager = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\Object\\ObjectManager' );
  }

  /**
   * _classesPowermailParams( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classesPowermailParams()
  {
    $this->_oParamsPowermail = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\Params\\Powermail' );
  }

  /**
   * _classesTCATables( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classesTCATables()
  {
    $this->_oTCATables = GeneralUtility::makeInstance( 'Netzmacher\\Browser\\Utility\\TCA\\Tables' );
  }

  /**
   * _classesTypoScriptService( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _classesTypoScriptService()
  {
    $this->_oTypoScriptService = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Extbase\\Service\\TypoScriptService' );
  }

  /**
   * _setFeUserIsLoggedIn( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setFeUserIsLoggedIn()
  {
    if ( !$GLOBALS[ 'TSFE' ]->fe_user->user )
    {
      $this->_bIsLoggedIn = FALSE;
      return;
    }

    $this->_bIsLoggedIn = TRUE;
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

  /**
   * _setSettings( ) :
   *
   * @param array $settings
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setSettings( $settings )
  {
    $this->_aSettings = $settings;
  }

  /**
   * _setTable( ) :
   *
   * @param strinmg $table
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _setTable( $table )
  {
    $this->_sTable = $table;
  }

}
