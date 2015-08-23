<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_befilter_ts bundles methods the allocation of page TSconfig and TypoScript configuration
 *
 * @author      Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package     TYPO3
 * @subpackage  browser
 * @version     7.2.6
 * @since       3.9.8
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   48: class tx_browser_befilter_ts
 *   58:     public function init()
 *   85:     public function regard_pageTSconfig_in_foreignTableWhere($pObj, $table, $field)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_befilter_ts
{

  /**
   * init(): Initiate this class. Include required classes.
   *
   * @return  void
   * @version     3.9.8
   * @since       3.9.8
   */
  public function init()
  {
    // #61520, 140911, dwildt, 4-
//      // Require classes
//    require_once(PATH_t3lib.'class.t3lib_page.php');
//    require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
//    require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');
    // #61520, 140911, dwildt, 7+
    $this->init_typo3version();
    if ( $this->typo3Version < 6002000 )
    {
      require_once(PATH_t3lib . 'class.t3lib_page.php');
      require_once(PATH_t3lib . 'class.t3lib_tstemplate.php');
      require_once(PATH_t3lib . 'class.t3lib_tsparser_ext.php');
    }
  }

  /**
   * init_typo3version( ): Get the current TYPO3 version, move it to an integer
   *                      and set the global $bool_typo3_43
   *                      This method is independent from
   *                        * t3lib_div::int_from_ver (upto 4.7)
   *                        * t3lib_utility_VersionNumber::convertVersionNumberToInteger (from 4.7)
   *
   * @internal  #61520
   *
   * @return    void
   * @version 6.0.0
   * @since   6.0.0
   */
  private function init_typo3version()
  {
    // RETURN : typo3Version is set
    if ( $this->typo3Version !== null )
    {
      return;
    }

    // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
    $version = ( ( int ) $main ) * 1000000;
    $version = $version + ( ( int ) $sub ) * 1000;
    $version = $version + ( ( int ) $bugfix ) * 1;
    $this->typo3Version = $version;
    // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

    if ( $this->typo3Version < 3000000 )
    {
      $prompt = '<h1>ERROR</h1>
        <h2>Unproper TYPO3 version</h2>
        <ul>
          <li>
            TYPO3 version is smaller than 3.0.0
          </li>
          <li>
            constant TYPO3_version: ' . TYPO3_version . '
          </li>
          <li>
            integer $this->typo3Version: ' . ( int ) $this->typo3Version . '
          </li>
        </ul>
          ';
      die( $prompt );
    }
  }

  /**
   * regard_pageTSconfig_in_foreignTableWhere():  Replaces markers in the andWhere statement
   *                                              with corresponding values of the page TSconfig
   *                                              Marker are
   *                                              * ###CURRENT_PID###
   *                                              * ###PAGE_TSCONFIG_IDLIST###
   *                                              * ###PAGE_TSCONFIG_STR###
   *                                              * ###PAGE_TSCONFIG_STR###
   *                                              * See
   *                                                * document "TYPO3 core APIs"
   *                                                  section ['columns'][fieldname]['config'] / TYPE: "select"
   *
   * @param array   $pObj: parent object
   * @param string    $table: name of the current record
   * @param string    $field: field of the current record
   * @return  array   $conf: rendered TCA configuration of the given table and field
   * @version     7.2.6
   * @since       3.9.8
   */
  public function regard_pageTSconfig_in_foreignTableWhere( $pObj, $table, $field )
  {
    $conf = $pObj->conf;

    // RETURN: there isn't any andWhere
    if ( !isset( $conf[ 'foreign_table_where' ] ) )
    {
      return $conf;
    } // RETURN: there isn't any andWhere

    foreach ( ( array ) $pObj->pageTSconfig[ 'TCEFORM.' ][ $table . '.' ][ $field . '.' ] as $key => $value ) // LOOP each marker value in the page TSconfig
    {
      $marker = '###' . $key . '###';
      // Replace each marker in the andWhere with the value from the page TSconfig
      $conf[ 'foreign_table_where' ] = str_replace( $marker, $value, $conf[ 'foreign_table_where' ] );
    } // LOOP each marker value in the page TSconfig

    $marker = '###CURRENT_PID###'; // #i0189, 150822, dwildt, 3+
    $value = ( int ) t3lib_div::_GP( 'id' );
    $conf[ 'foreign_table_where' ] = str_replace( $marker, $value, $conf[ 'foreign_table_where' ] );

    return $conf;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/lib/class.tx_browser_befilter_ts.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/lib/class.tx_browser_befilter_ts.php' ]);
}
?>