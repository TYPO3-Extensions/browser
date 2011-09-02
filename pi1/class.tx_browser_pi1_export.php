<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_export bundles methods for exporting datas
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 4.0.0
* @since 4.0.0
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_browser_pi1_export
 *   83:     function __construct($pObj)
 *
 *              SECTION: typeNum
 *  118:     public function set_typeNum( )
 *
 *              SECTION: CSV helper
 *  189:     public function csv_init_config( )
 *  225:     public function csv_value( $value )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_export
{
    // [string] Devider for CSV fields. Usually: ;
  var $csv_devider    = null;
    // [string] Wrapper for CSV fields. Usually "
  var $csv_enclosure  = null;
    // [boolean] HTML tags will removed
  var $csv_striptag   = null;
    // [Integer] Number of the current typeNum
  var $int_typeNum    = null;
    // [String] Name of the current typeNum
  var $str_typeNum    = null;









  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * typeNum
  *
  **********************************************/









  /**
 * set_typeNum(): Set the globals $int_typeNum and $str_typeNum.
 *                The globals are needed by other classes while runtime.
 *
 * @return  void
 * @version 4.0.0
 * @since 4.0.0
 */
  public function set_typeNum( )
  {
      // Get the typeNum form the current URL parameters
    $typeNum = (int) t3lib_div::_GP( 'type' );

      // RETURN typeNum is 0 or empty
    if( empty ( $typeNum ) )
    {
      return;
    }
      // RETURN typeNum is 0 or empty

      // Check the proper typeNum
    $conf = $this->pObj->conf;
    switch (true)
    {
      case( $typeNum == $conf['export.']['csv.']['page.']['typeNum'] ) :
          // Given typeNum is the internal typeNum for CSV export
        $this->int_typeNum = $typeNum;
        $this->str_typeNum = 'csv';
        break;
      default :
          // Given typeNum isn't the internal typeNum for CSV export
        $this->str_typeNum = 'undefined';
    }
      // Check the proper typeNum

      // DRS - Development Reporting System
    if ($this->pObj->b_drs_export)
    {
      t3lib_div::devLog('[INFO/Export] typeNum is ' . $typeNum . '. Name is ' . $this->str_typeNum . '.', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System

//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ', (int) t3lib_div::_GP( 'type' ) );
//      die( );
//    }
  }









  /***********************************************
  *
  * CSV helper
  *
  **********************************************/









  /**
 * csv_init_config( ): Init the globals $csv_devider, $csv_enclosure and $csv_striptag
 *
 * @return  void
 * @version 4.0.0
 * @since 4.0.0
 */
  public function csv_init_config( )
  {
      // RETURN typeNum isn't the csv typeNum
    if( $this->str_typeNum != 'csv' )
    {
      return;
    }
      // RETURN typeNum isn't the csv typeNum

    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['devider.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['devider.']['stdWrap.'];
    $this->csv_devider    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['enclosure.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['enclosure.']['stdWrap.'];
    $this->csv_enclosure  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['strip_tag.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['strip_tag.']['stdWrap.'];
    $this->csv_striptag   = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
  }









  /**
 * csv_value( ):  Change a value to a proper csv value: HTML tags will removed, value will enclosed,
 *                the csv devider will added.
 *
 * @param   string    $value: value for csv export
 * @return  string    $value: proper csv value
 * @version 4.0.0
 * @since 4.0.0
 */
  public function csv_value( $value )
  {
      // RETURN typeNum isn't the csv typeNum
    if( $this->str_typeNum != 'csv' )
    {
      return $value;
    }
      // RETURN typeNum isn't the csv typeNum

      // Remove HTML tags
    if( $this->csv_striptag )
    {
      $value = strip_tags( $value );
    }
      // If value contains the enclosure char, double this char. I.e: " will become ""
    $value = str_replace( $this->csv_enclosure,  $this->csv_enclosure . $this->csv_enclosure, $value);
      // Enclose the value with the enclosure char and add the devider
    $value = $this->csv_enclosure . $value . $this->csv_enclosure . $this->csv_devider;
    return $value;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_export.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_export.php']);
}
?>