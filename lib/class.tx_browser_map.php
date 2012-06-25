<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
***************************************************************/

/**
* Class provides methods for the map module
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    org
* @version 4.1.0
* @since 4.1.0
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   48: class tx_browser_map
 *   73:     function static_country_zones($params)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

class tx_browser_map
{
  private $n = array();
  private $e = array();
  private $s = array();
  private $w = array();
  private $center = array();

  public function fillBoundList( $coordinates )
  {
    if
    ( 
          count( $this->n ) == 0 
      &&  count( $this->e ) == 0 
      &&  count( $this->s ) == 0 
      &&  count( $this->w ) == 0
    )
    {
      $this->n = $coordinates;
      $this->e = $coordinates;
      $this->s = $coordinates;
      $this->w = $coordinates;
      //var_dump( __METHOD__, __LINE__, $this->n, $this->e, $this->s, $this->w );
      return;			
    }

    if( abs( $this->n[1] ) < abs( $coordinates[1] ) )
      $this->n = $coordinates;
    if( abs( $this->e[0] ) < abs( $coordinates[0] ) )
      $this->e = $coordinates;
    if( abs( $this->s[1] ) > abs( $coordinates[1] ) )
      $this->s = $coordinates;
    if( abs( $this->w[0] ) > abs( $coordinates[0] ) )
      $this->w = $coordinates;
    //var_dump( __METHOD__, __LINE__, $this->n, $this->e, $this->s, $this->w );
  }

  public function centerCoor( )
  {
    $this->center[0] = ( $this->n[0] * 1 + $this->e[0] * 1 + $this->s[0] * 1 + $this->w[0] * 1 ) / 4;				// X coordinates
    $this->center[1] = ( $this->n[1] * 1 + $this->e[1] * 1 + $this->s[1] * 1 + $this->w[1] * 1 ) / 4;				// Y coordinates
    return $this->center;
  }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_map.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_map.php']);
}

?>