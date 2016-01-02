<?php

namespace Netzmacher\Browser\Utility\TCA;

use TYPO3\CMS\Backend\Utility\BackendUtility;

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
 */
class Tables
{

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
   * foreign( ) :
   *
   * @param string $table
   * @param string $field
   * @return string
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function foreign( $table, $field )
  {
    return;
  }

  /**
   * local( ) :
   *
   * @param string $table
   * @param array $fields
   * @return string
   * @access public
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  public function local( $table, $fields )
  {
    foreach ( array_keys( ( array ) $fields ) AS $field )
    {
      $this->_localFieldConfig( $table, $field );
    }
    var_dump( __METHOD__, __LINE__, $table, $fields, $this->_localTableField );
    die();

    return;
  }

  /**
   * _localFieldConfig( ) :
   *
   * @param string $table
   * @param array $field
   * @return string
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _localFieldConfig( $table, $field )
  {
    if ( !$this->_localFieldConfigForeignTable( $table, $field ) )
    {
      return;
    }
    if ( !$this->_localFieldConfigInternalType( $table, $field ) )
    {
      return;
    }
    if ( !$this->_localFieldConfigType( $table, $field ) )
    {
      return;
    }
  }

  /**
   * _localFieldConfig( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _localFieldConfigForeignTable( $table, $field )
  {
    $config = BackendUtility::getTcaFieldConfiguration( $table, $field );
    //var_dump( __METHOD__, __LINE__, $field, $config );

    switch ( TRUE )
    {
      case(isset( $config[ 'foreign_table' ] )):
        //var_dump( __METHOD__, __LINE__, $field, 'FOREIGN TABLE' );
        $handle = FALSE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcessCause' ] = ''
                . 'foreign_table is set: "' . $config[ 'foreign_table' ] . '". '
                . 'Field should post processed.'
        ;
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'key' ] = 'foreign_table';
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'value' ] = $config[ 'foreign_table' ];
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'prompt' ] = ''
                . 'foreign_table is set: "' . $config[ 'foreign_table' ] . '" '
        ;
        break;
      default:
        $handle = TRUE;
        break;
    }
    return $handle;
  }

  /**
   * _localFieldConfigInternalType( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _localFieldConfigInternalType( $table, $field )
  {
    $config = BackendUtility::getTcaFieldConfiguration( $table, $field );
    //var_dump( __METHOD__, __LINE__, $field, $config );

    if ( !isset( $config[ 'internal_type' ] ) )
    {
      return TRUE;
    }

    switch ( $config[ 'internal_type' ] )
    {
      case('file'):
        $this->_localTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcessCause' ] = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
                . 'Field should post processed.'
        ;
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'key' ] = 'internal_type';
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'value' ] = $config[ 'internal_type' ];
        $this->_localTableField[ $table ][ $field ][ 'postProcess' ][ 'prompt' ] = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
        ;
        $handle = FALSE;
        //var_dump( __METHOD__, __LINE__, $field, 'HANDLE' );
        break;
      case('db'):
      case('file_reference'):
      case('folder'):
        $handle = FALSE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcessCause' ] = ''
                . 'internal_type is "' . $config[ 'internal_type' ] . '". '
                . 'and can\'t handled. '
                . 'See ' . __METHOD__ . ' #' . __LINE__
        ;
        //var_dump( __METHOD__, __LINE__, $field, 'DON\'T HANDLE' );
        break;
      default:
        break;
    }

    return $handle;
  }

  /**
   * _localFieldConfigType( ) :
   *
   * @param string $table
   * @param array $field
   * @return boolean
   * @access private
   * @version 7.4.0
   * @since 7.4.0
   *
   */
  private function _localFieldConfigType( $table, $field )
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
        //var_dump( __METHOD__, __LINE__, $field, 'HANDLE' );
        $handle = TRUE;
        break;
      case('flex'):
      case('inline'):
      case('none'):
      case('passthrough'):
      case('user'):
        $handle = FALSE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcess' ] = TRUE;
        $this->_localTableField[ $table ][ $field ][ 'doNotProcessCause' ] = 'type can not handled:' . $config[ 'type' ];
        //var_dump( __METHOD__, __LINE__, $field, 'DON\'T HANDLE' );
        break;
      default:
        break;
    }
    return $handle;
  }

}
