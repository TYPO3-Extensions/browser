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
* The class tx_browser_pi1_statistics bundles methods for statistics requirement
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.3
* @since 3.9.3
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_browser_pi1_statistics
 *   98:     function __construct($pObj)
 *
 *              SECTION: Initial
 *  134:     public function countViewSingleRecord( )
 *  170:     public function getNameOfDataSpace( )
 *
 *              SECTION: Cache
 *  213:     public function cacheOfListView( )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_statistics
{
    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

    // [Array] The current TypoScript configuration array
  var $conf       = false;
    // [Integer] The current mode (from modeselector)
  var $mode       = false;
    // [String] 'list' or 'single': The current view
  var $view       = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_view  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
  var $conf_path  = false;
    // Variables set by the pObj (by class.tx_browser_pi1.php)



    //////////////////////////////////////////////////////
    //
    // Variables set by this class

    // [Boolean] True, if statistics module is enabled. Will set while runtime
  var $bool_statistics_enabled = null;
    // Variables set by this class









  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Initial
  *
  **********************************************/









  /**
 * statisticsIsEnabled( ):  Sets the global $bool_statistics_enabled.
 *                          The boolean is controlled by the flexform / TypoScript.
 *                          The User can enable and disable the statistics module.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function statisticsIsEnabled( )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Boolean is set before

    if( ! ( $this->bool_statistics_enabled === null ) )
    {
      return;
    }
      // RETURN: Boolean is set before



      ///////////////////////////////////////////////////////////////////////////////
      //
      // Enable statistics module

    $coa_name = $this->pObj->conf['flexform.']['sDEF.']['statistics.']['enabled'];
    $coa_conf = $this->pObj->conf['flexform.']['sDEF.']['statistics.']['enabled.'];
    $this->bool_statistics_enabled = $this->pObj->cObj->cObjGetSingle($coa_name, $coa_conf);;
      // Enable statistics module



      ///////////////////////////////////////////////////////////////////////////////
      //
      // User disabled the statistics module

    if( ! $this->bool_statistics_enabled )
    {
      if ( $this->pObj->b_drs_statistics )
      {
        $value = $this->bool_statistics_enabled;
        t3lib_div::devlog( '[INFO/STATISTICS] flexform.sDEF.statistics.enabled is \'' . $value . '\' '.
          'Statistics data won\'t collect.', $this->pObj->extKey, 0 );
      }
    }
      // User disabled the statistics module

    return;
  }









  /**
 * countViewSingleRecord( ):  Sets the global $bool_session_enabled.
 *                            The boolean is controlled by the flexform / TypoScript.
 *                            The User can enable and disable session management.
 *
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function countViewSingleRecord( )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the statistics module

    $this->statisticsIsEnabled( );
      // Set status of the statistics module


      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: statistics module is disabled

    if( ! $this->bool_statistics_enabled )
    {
      if ($this->pObj->b_drs_statistics)
      {
        t3lib_div::devlog('[INFO/STATISTICS] single view won\'t counted for statistics.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/STATISTICS] Enable flexform.sDEF.statistics.enabled.', $this->pObj->extKey, 1);
      }
      return;
    }
      // RETURN: statistics module is disabled



      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Boolean is set before

    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
    if ( ! ( $pos === false ) )
    {
      //var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->piVars);
      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->piVars, $this->pObj->localTable, $this->pObj->arrLocalTable );
      var_dump(__METHOD__. ' (' . __LINE__ . '): ', $this->pObj->conf['flexform.']['sDEF.'] );
      die( );
    }
      // RETURN: Boolean is set before



    return;
  }








  /***********************************************
  *
  * Hits
  *
  **********************************************/








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_statistics.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_statistics.php']);
}
?>
