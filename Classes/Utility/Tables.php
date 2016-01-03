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
class Tables
{

  /**
   * @var array
   */
  private $_aKeyValues;

  /**
   * @var array Powermail GET and POST params
   */
  private $_aPowermailGP;

  /**
   * @var array record for an INSERT or an UPDATE
   */
  private $_aTableRecordKeyValues = array();

  /**
   * @var array TypoScript settings array of the parent object
   */
  private $_aSettings;

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
   * @var TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
   */
  private $_oCObj;

  /**
   * @var Netzmacher\Browser\Utility\DRS
   */
  private $_oDRS;

  /**
   * @var Netzmacher\Browser\Utility\TCA
   */
  private $_oTCA;

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
   * getKeyValues( ) :
   *
   * @return array
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getKeyValues()
  {
    return $this->_aKeyValues;
  }

  /**
   * getTableRecordKeyValues( ) :
   *
   * @param string $table
   * @return array
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function getTableRecordKeyValues( $table )
  {
    return $this->_aTableRecordKeyValues[ $table ];
  }

//  /**
//   * getUid( $table ) :
//   *
//   * @param string $table
//   * @return array
//   * @access public
//   * @version 7.4.0
//   * @since 7.4.0
//   */
//  public function getUid()
//  {
//    return $this->_aTableRecordKeyValues;
//  }

  /**
   * main( ) :
   *
   * @param string $table
   * @param array $settings
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  public function main( $table, $settings )
  {
    $this->_init( $table, $settings );
    $this->_record();
    $this->_aKeyValues = $this->_oTCA->getTableKeyValues( $table );
  }

  /**
   * setCObj( ) :
   *
   * @param TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
   * @return void
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function setCObj( \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj )
  {
    $this->_oCObj = $cObj;
  }

  /**
   * setDRS( )
   *
   * @param Netzmacher\Browser\Utility\DRS $oDRS
   * @return array
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function setDRS( \Netzmacher\Browser\Utility\DRS $oDRS )
  {
    $this->_oDRS = $oDRS;
  }

  /**
   * setTCA( ) :
   *
   * @param Netzmacher\Browser\Utility\TCA $oTCA
   * @return void
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   */
  public function setTCA( \Netzmacher\Browser\Utility\TCA $oTCA )
  {
    $this->_oTCA = $oTCA;
  }

  /**
   * init( ) :
   *
   * @param string $table
   * @param array $settings
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _init( $table, $settings )
  {
    $this->_setSettings( $settings );
    $this->_setTable( $table );
    $this->_classes();
    $this->_setFeUserIsLoggedIn();
    $this->_setPowermailGP();
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
    //var_dump( __METHOD__, __LINE__, $this->_sTable );
    $aTSFields = $this->_recordFieldsForHandleFromTypoScript();
    $aPMFields = $this->_recordFieldsForHandleFromPowermail();

    // intersect of all field groups
    $aIntersect = array_intersect( $aTCAFields, $aTSFields, $aPMFields );
    $sIntersect = implode( ',', $aIntersect );

    $prompt = implode( ', ', ( array ) $aIntersect );
    $prompt = 'Intersect of all fields: ' . $prompt;
    $this->_DRSprompt( $prompt, __LINE__ );

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
    $prompt = implode( ', ', array_keys( $this->_aPowermailGP ) );
    $prompt = 'Values delivered by Powermail: ' . $prompt;
    $this->_DRSprompt( $prompt, __LINE__ );
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
    //var_dump( __METHOD__, __LINE__, $this->_sTable, $strDebugTrail = \TYPO3\CMS\Core\Utility\DebugUtility::debugTrail() );
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

    $prompt = implode( ', ', ( array ) $aAllowedTCAFields );
    $prompt = 'TCA fields with a matching configuration: ' . $prompt;
    $this->_DRSprompt( $prompt, __LINE__ );

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

    $prompt = implode( ', ', ( array ) $aTSFields );
    $prompt = 'Fields which are allowed by TypoScript: ' . $prompt;
    $this->_DRSprompt( $prompt, __LINE__ );

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
    $this->_recordSetTCA();
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
      $this->_aTableRecordKeyValues[ $this->_sTable ] = $keyValues;
      return;
    }
    $keyValues = $this->_recordSetKeyValuesUid( $keyValues );
//    $keyValues = $this->_recordSetKeyValuesUsername( $keyValues );
    $keyValues = $this->_recordSetKeyValuesDefaults( $keyValues );

    $this->_aTableRecordKeyValues[ $this->_sTable ] = $keyValues;
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
      $prompt = 'Nothing to do: There isn\'t any default value.';
      $this->_DRSprompt( $prompt, __LINE__ );

      return $keyValues;
    }

    foreach ( $aFieldsForHandle as $field )
    {
      $typoscriptPlain = $this->_aSettings[ 'mapping' ][ $this->_sTable ][ 'defaults' ][ $field ];
      $name = $typoscriptPlain[ '_typoScriptNodeValue' ];
      $conf = $this->_oTypoScriptService->convertPlainArrayToTypoScriptArray( $typoscriptPlain );

      $value = $this->_oCObj->cObjGetSingle( $name, $conf );

      switch ( TRUE )
      {
        case(!empty( $value )):
        case($value === 0 ):
        case($value === "0" ):
          $keyValues[ $field ] = $value;
          $prompt = 'Default is added. ' . $field . ': "' . $value . '"';
          $this->_DRSprompt( $prompt, __LINE__ );
          continue;
        default:
          $prompt = 'Default isn\'t added, becuase content is NULL. Field: ' . $field;
          $this->_DRSprompt( $prompt, __LINE__ );
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
   * _recordSetTCA( ) :
   *
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   */
  private function _recordSetTCA()
  {
    $this->_oTCA->main( $this->_sTable, $this->_aTableRecordKeyValues[ $this->_sTable ] );
  }

  /**
   * _DRSprompt( ) :
   *
   * @param string $prompt
   * @param int $line
   * @param int $type
   * @return void
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _DRSprompt( $prompt, $line = __LINE__, $type = 0 )
  {
    if ( !$this->_oDRS->getDrsFrontendEditing() )
    {
      return;
    }
    GeneralUtility::devlog( '[INFO/FE] ' . $prompt, __CLASS__ . '#' . $line, $type );
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
    $this->_classesPowermailParams();
    $this->_classesTypoScriptService();
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
