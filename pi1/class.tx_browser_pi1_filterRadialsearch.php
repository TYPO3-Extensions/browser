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
 * The class tx_browser_pi1_filterRadialsearch bundles methods for rendering and processing a radial search filter
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
  private $pObj       = null;
    // [Array] TypoSCript of the current view
  public  $conf_view  = null;

    // [Boolean] False, init process was unproper, true in case of success
  private $init                 = null;
    // [Object] interface of extension radialsearch
  private $objRadialsearch      = null;
    // [String] radialsearch "table"/filter. Example: radialsearch
  public  $radialsearchTable    = null;
    // [Boolean] Radialsearch Sword is set oer isn't set
  private $radialsearchIsSword  = null;
  





  /***********************************************
  *
  * SQL
  *
  **********************************************/
  
/**
 * andFrom( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andFrom( )
  {
    $this->init( );
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->radialsearchIsSword )
    {
      return null;
    }
    
    return $this->objRadialsearch->andFrom( );
  }

/**
 * andHaving( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andHaving( )
  {
    $this->init( );
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->radialsearchIsSword )
    {
      return null;
    }
    
    return $this->objRadialsearch->andHaving( );
  }

/**
 * andOrderBy( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andOrderBy( )
  {
    $this->init( );
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->radialsearchIsSword )
    {
      return null;
    }
    
    return $this->objRadialsearch->andOrderBy( );
  }

/**
 * andSelect( )  :
 *
 * @return	string
 * @internal    #52486
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andSelect( )
  {
    $this->init( );
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->radialsearchIsSword )
    {
      return null;
    }
    
    return $this->objRadialsearch->andSelect( );
  }

/**
 * andWhere( )  :
 *
 * @param       boolean   $withDistance :
 * @return	string
 * @internal    #52486
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function andWhere( $withDistance )
  {
    $this->init( );
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->radialsearchIsSword )
    {
      return null;
    }
    
    return $this->objRadialsearch->andWhere( $withDistance );
  }



   /***********************************************
    *
    * Get
    *
    **********************************************/
  
/**
 * getRadialsearchTSname( )  : 
 *
 * @return	string
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function getRadialsearchTSname( )
  {
    $radialsearchTable = $this->radialsearchTable;

    if( ! $radialsearchTable )
    {
      $prompt = 'ERROR: radialsearchTable isn\'t initiated!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search (Umkreissuche)<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    
    return $radialsearchTable;
  }  
  
/**
 * getSword( )  : 
 *
 * @return	string
 * @access  public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function getSword( )
  {
    return $this->radialsearchIsSword;
  }  



   /***********************************************
    *
    * Init
    *
    **********************************************/
  
/**
 * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
 *
 * @return    void
 * @access public
 * @version 4.7.0
 * @since   4.7.0
 */
  public function init( )
  {
    if( $this->init !== null )
    {
      return $this->init;
    }
    
    if( ! is_object( $this->pObj ) )
    {
      $this->init = false;
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    
    if( ! is_array( $this->conf_view ) )
    {
      $prompt = 'ERROR: no array!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }

      // RETURN : There isn't any radialsearch filter
    if( ! $this->initFilterTable( ) )
    {
      return;
    }
    
      // RETURN : There isn't any radialsearch sword
    if( ! $this->initSword( ) )
    {
      $this->init = true;
      return $this->init;
    }

      // Check if EXT radialserach is installed
    $this->initExtension( );

      // Init radialsserach filter class
    $this->initObject( );

    $this->init = true;
    return $this->init;
  }
  
/**
 * initExtension( )  : Check if EXT radialserach is installed
 *
 * @return	void
 * @access  private
 * @internal    #52486
 * @version 4.7.0
 * @since   4.7.0
 */
  private function initExtension( )
  {
    $key = 'radialsearch';
    
      // RETURN : extension is installed
    if( t3lib_extMgm::isLoaded( $key ) )
    {
      return true;
    }
      // RETURN : extension is installed

        $prompt = '
<h1>
  ERROR: radial search (Umkreissuche)
</h1>
<p>
  You are using a radial search filter in the current view.<br />
  But the extension Radial Search (Umkreissuche) (extension key: radialsearch) isn\'t loaded.<br />
  Please remove the radialsearch filter or install and enable the extension radialsearch.
</p>
<p>
  Error occured at ' . __METHOD__ . ' (line #' . __LINE__ . ')
</p>
<p>
  Sorry for the trouble. Browser - TYPO3 without PHP.
</p>
';
    die( $prompt );
  }
  
/**
 * initFilterTable( ) : Checks weather a radialserach filter is set or not.
 *                              If radialsearch filter 
 *                              * is set
 *                                * it sets the class var $radialsearchTable
 *                                * returns true
 *                              * isn't set
 *                                * returns false
 *
 * @return	boolean         TRue, if radialsearch filter is set
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function initFilterTable( )
  {
    if( $this->radialsearchTable !== null )
    {
      return $this->radialsearchTable;
    }
      // LOOP each table
    foreach( array_keys( ( array ) $this->conf_view['filter.'] ) as $table )
    {
      if( substr( $table, -1 ) == '.' )
      {
        continue;
      }
      
        // Name (COA object) of the current filter table
      $name = $this->conf_view[ 'filter.' ][ $table ];
      
        // CONTINUE : Name (COA object) isn't RADIALSEARCH
      if( $name != 'RADIALSEARCH' )
      {
        continue;
      }
      
        // RETURN true : Name (COA object) is RADIALSEARCH
      if( $name == 'RADIALSEARCH' )
      {
          // Set the radialsearch "table". Example: radialsearch
        $this->radialsearchTable = $table;
          // DRS
        if( $this->pObj->b_drs_filter )
        {
          $prompt = 'filter RADIALSEARCH is set and has the name ' . $table;
          t3lib_div::devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
        }
          // DRS
        return true;
      }      
        // RETURN true : Name (COA object) is RADIALSEARCH
    }
      // LOOP each table

      // DRS
    if( $this->pObj->b_drs_filter )
    {
      $prompt = 'There isn\'t any filter with the name RADIALSEARCH.';
      t3lib_div::devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

      // RETURN false : any radialsearch filter isn't used
    return false;
  }
  
/**
 * initObject( ): 
 *
 * @return	void
 * @internal    #52486
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function initObject( )
  {
    $path = t3lib_extMgm::extPath( 'radialsearch' ) . 'interface/';
    require_once( $path . 'class.tx_radialsearch_interface.php' );

    $this->objRadialsearch = t3lib_div::makeInstance( 'tx_radialsearch_interface' );
    $this->objRadialsearch->setParentObject( $this->pObj );
    $this->objRadialsearch->setCurrentObject( $this );
  }

/**
 * initSword( ): Set the class var $isSword
 *
 * @return	boolean        true, if sword is set. False, if not.
 * @access  private
 * @version 4.7.0
 * @since   4.7.0
 */
  private function initSword( )
  {
      // RETURN : sword is set before
    if( $this->radialsearchIsSword !== null )
    {
      return $this->radialsearchIsSword;
    }
      // RETURN : sword is set before

      // Get the current sword
    $tx_radialsearch_pi1  = ( array ) t3lib_div::_GP( 'tx_radialsearch_pi1' );
    $sword = $tx_radialsearch_pi1[ 'sword' ];
    
      // Set class var $isSword
    switch( true )
    {
      case( $sword === null ):
      case( $sword == '' ):
      case( $sword == '*' ):
        $this->radialsearchIsSword = false;
        break;
      default:
        $this->radialsearchIsSword = true;
        break;
    }
    unset( $sword );
      // Set class var $isSword

    return $this->radialsearchIsSword;
  }



  /***********************************************
  *
  * Set
  *
  **********************************************/

 /**
  * setConfiguration( )  : Set fields and filter
  *
  * @param	array		$fields: array with elements lat and lon
  * @return	void
  * @access public
  * @version    4.7.0
  * @since      4.7.0
  */
  public function setConfiguration( $fields, $filter )
  { 
    $this->objRadialsearch->setConfiguration( $fields, $filter );
  }

 /**
  * setConfView( )  : Set the parent object
  *
  * @param	object		$pObj: Parent Object
  * @return	void
  * @access public
  * @version    4.7.0
  * @since      4.7.0
  */
  public function setConfView( $confView )
  {
    if( ! is_array( $confView ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Radial Search<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->conf_view = $confView;
  }

 /**
  * setParentObject( )  : Set the parent object
  *
  * @param	object		$pObj: Parent Object
  * @return	void
  * @access public
  * @version    4.7.0
  * @since      4.7.0
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