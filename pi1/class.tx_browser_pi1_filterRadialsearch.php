<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_filterRadialsearch bundles methods for rendering and processing radial search filter
 *
 * @author       Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package      TYPO3
 * @subpackage   browser
 *
 * @version      4.7.0
 * @since        4.7.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */
class tx_browser_pi1_filterRadialsearch {
  
  public $prefixId = 'tx_browser_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_browser_pi1_filterRadialsearch.php';

  // path to this script relative to the extension dir.
  public $extKey = 'browser';

    // [Object] Parent object
  private $pObj   = null;
    // [Object] Filter object
  private $filter = null;

  


 /***********************************************
  *
  * Main
  *
  **********************************************/

/**
 * andWhere( ): 
 *
 * @return	array		$arr_return : $arr_return['data']['marker']['###TABLE.FIELD###']
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andWhere( )
  {
    $this->init( );

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

    
    $andWhere = '            AND tx_radialsearch_postalcodes.pid = 0 
            AND tx_radialsearch_postalcodes.country_code LIKE "DE" 
            AND tx_radialsearch_postalcodes.admin_code1 LIKE "TH"
            AND
            (
                  tx_radialsearch_postalcodes.postal_code LIKE "99084 Erfurt%" 
              OR  tx_radialsearch_postalcodes.place_name LIKE "99084 Erfurt%" 
              OR  CONCAT(tx_radialsearch_postalcodes.postal_code, " ", tx_radialsearch_postalcodes.place_name) LIKE "99084 Erfurt%"
            ) 
            AND tx_radialsearch_postalcodes.deleted = 0 
';
      // Prompt the expired time to devlog

    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

    return $andWhere;
  }
  



  /***********************************************
  *
  * Init
  *
  **********************************************/
/**
 * init( ): 
 *
 * @return	boolen        true
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function init( )
  {
    if( ! is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }

    $this->filter = $this->pObj->objFltr4x;

    if( ! is_object( $this->filter ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }

    return true;
  }



  /***********************************************
  *
  * Set
  *
  **********************************************/

 /**
  * setParentObject( )  : Set the parent object
  *
  * @param	object		$pObj: Parent Object
  * @return	void
  * @access public
  * @version    0.0.1
  * @since      0.0.1
  */
  public function setParentObject( $pObj )
  {
    if( ! is_object( $pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->pObj = $pObj;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filterRadialsearch.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filterRadialsearch.php']);
}
?>