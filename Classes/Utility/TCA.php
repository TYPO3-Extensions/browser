<?php

namespace Netzmacher\Browser\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
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
 */
class TCA
{

  /**
   * @var array
   */
  private $_aTableField;

  /**
   * @var array
   */
  private $_aTableFieldProcess;

  /**
   * @var Netzmacher\Browser\Utility\DRS
   */
  private $_oDRS;

  /**
   * getTableField( ) :
   *
   * @return array
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function getTableField()
  {
//    var_dump( __METHOD__, __LINE__, $table, $fields, $this->_aTableField );
    //die();

    return $this->_aTableField;
  }

  /**
   * getTableFieldProcess( ) :
   *
   * @param string $table
   * @return array
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function getTableKeyValues( $table )
  {
//    var_dump( __METHOD__, __LINE__, $table, $fields, $this->_aTableField );
    //die();

    return $this->_aTableFieldProcess[ $table ];
  }

  /**
   * main( ) :
   *
   * @param string $table
   * @param array $fields
   * @return string
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function main( $table, $fields )
  {
    //var_dump( __METHOD__, __LINE__, $table );
    foreach ( array_keys( ( array ) $fields ) AS $field )
    {
      $this->_fieldConfig( $table, $field );
    }

    $this->_setProcess( $table, $fields );
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
   * _setProcess( ) :
   *
   * @param string $table
   * @param array $fields
   * @return string
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _setProcess( $table, $fields )
  {
//    var_dump( __METHOD__, __LINE__, $table, $fields, $this->_aTableField );
    foreach ( $fields AS $field => $value )
    {
      if ( $this->_aTableField[ $table ][ $field ][ 'doNotProcess' ] )
      {
        continue;
      }
      $this->_aTableFieldProcess[ $table ][ $field ] = $value;
    }
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
   * _fieldConfig( ) :
   *
   * @param string $table
   * @param array $field
   * @return string
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _fieldConfig( $table, $field )
  {
    if ( !$this->_fieldConfigForeignTable( $table, $field ) )
    {
      return;
    }
    if ( !$this->_fieldConfigInternalType( $table, $field ) )
    {
      return;
    }
    if ( !$this->_fieldConfigType( $table, $field ) )
    {
      return;
    }
  }

  /**
   * _fieldConfig( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _fieldConfigForeignTable( $table, $field )
  {
    $config = BackendUtility::getTcaFieldConfiguration( $table, $field );
    //var_dump( __METHOD__, __LINE__, $field, $config );

    switch ( TRUE )
    {
      case(isset( $config[ 'foreign_table' ] )):
//        var_dump( __METHOD__, __LINE__, $field );
        $handle = FALSE;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $prompt = ''
                . 'foreign_table is set: "' . $config[ 'foreign_table' ] . '". '
                . 'Field should post processed.'
        ;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcessCause' ] = $prompt;
        $prompt = $table . '.' . $field . ': ' . $prompt;
        $this->_DRSprompt( $prompt, __LINE__ );
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'key' ] = 'foreign_table';
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'value' ] = $config[ 'foreign_table' ];
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'prompt' ] = ''
                . 'foreign_table is set: "' . $config[ 'foreign_table' ] . '" '
        ;
        break;
      default:
//        var_dump( __METHOD__, __LINE__, $field );
        $handle = TRUE;
        break;
    }
    return $handle;
  }

  /**
   * _fieldConfigInternalType( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _fieldConfigInternalType( $table, $field )
  {
    $config = BackendUtility::getTcaFieldConfiguration( $table, $field );
    //var_dump( __METHOD__, __LINE__, $field, $config );

    if ( !isset( $config[ 'internal_type' ] ) )
    {
//      var_dump( __METHOD__, __LINE__, $field );
      return TRUE;
    }

    switch ( $config[ 'internal_type' ] )
    {
      case('db'):
      case('file'):
//        var_dump( __METHOD__, __LINE__, $field );
        $this->_aTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $prompt = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
                . 'Field should post processed.'
        ;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcessCause' ] = $prompt;
        $prompt = $table . '.' . $field . ': ' . $prompt;
        $this->_DRSprompt( $prompt, __LINE__ );
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'key' ] = 'internal_type';
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'value' ] = $config[ 'internal_type' ];
        $this->_aTableField[ $table ][ $field ][ 'postProcess' ][ 'prompt' ] = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
        ;
        $handle = FALSE;
        //var_dump( __METHOD__, __LINE__, $field, 'HANDLE' );
        break;
      case('file_reference'):
      case('folder'):
//        var_dump( __METHOD__, __LINE__, $field );
        $handle = FALSE;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $prompt = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
                . 'and can\'t handled. '
                . 'See ' . __METHOD__ . ' #' . __LINE__
        ;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcessCause' ] = $prompt;
        $prompt = $table . '.' . $field . ': ' . $prompt;
        $this->_DRSprompt( $prompt, __LINE__, 3 );
        break;
      default:
        break;
    }
//    var_dump( __METHOD__, __LINE__, $table, $this->_aTableField );

    return $handle;
  }

  /**
   * _fieldConfigType( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _fieldConfigType( $table, $field )
  {
    $config = BackendUtility::getTcaFieldConfiguration( $table, $field );
    //var_dump( __METHOD__, __LINE__, $field, $config );

    switch ( $config[ 'type' ] )
    {
      case('check'):
      case('group'):
      case('input'):
      case('radio'):
      case('select'):
      case('text'):
        $prompt = ''
                . 'type is "' . $config[ 'type' ] . '". '
                . 'Field will processed.'
        ;
        $prompt = $table . '.' . $field . ': ' . $prompt;
        $this->_DRSprompt( $prompt, __LINE__ );
        $handle = TRUE;
        break;
      case('flex'):
      case('inline'):
      case('none'):
      case('passthrough'):
      case('user'):
//        var_dump( __METHOD__, __LINE__, $field );
        $handle = FALSE;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $prompt = ''
                . 'type can not handled:' . $config[ 'type' ]
                ;
        $this->_aTableField[ $table ][ $field ][ 'doNotProcessCause' ] = $prompt;
        $prompt = $table . '.' . $field . ': ' . $prompt;
        $this->_DRSprompt( $prompt, __LINE__, 3 );
        break;
      default:
        break;
    }
    return $handle;
  }

}