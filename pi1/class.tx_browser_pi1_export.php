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
 *   52: class tx_browser_pi1_export
 *   70:     function __construct($pObj)
 *
 *              SECTION: typeNum
 *  104:     public function set_typeNum( )
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_export
{
    // [Integer] Number of the current typeNum
  var $int_typeNum = null;
    // [String] Name of the current typeNum
  var $str_typeNum = null;









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
 * set_typeNum():
 *
 * @return  void
 * @version 4.0.0
 * @since 4.0.0
 */
  public function set_typeNum( )
  {
    $typeNum = (int) t3lib_div::_GP( 'type' );

      // RETURN typeNum is 0 or empty
    if( empty ( $typeNum ) )
    {
      return;
    }
      // RETURN typeNum is 0 or empty

    $conf = $this->pObj->conf;
    switch (true)
    {
      case( $typeNum == $conf['export.']['csv.']['page.']['typeNum'] ) :
        $this->int_typeNum = $typeNum;
        $this->str_typeNum = 'csv';
        break;
      default :
        $this->str_typeNum = 'undefined';
    }
    if ($this->pObj->b_drs_export)
    {
      t3lib_div::devLog('[INFO/Export] typeNum is ' . $typeNum . '. Name is ' . $this->str_typeNum . '.', $this->pObj->extKey, 0);
    }

    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
    if ( ! ( $pos === false ) )
    {
      var_dump(__METHOD__. ' (' . __LINE__ . '): ', (int) t3lib_div::_GP( 'type' ) );
      die( );
    }
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_export.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_export.php']);
}
?>