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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* Class provides methods for the TCA.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    org
* @version 0.3.1
* @since 0.3.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_org_extmanager
 *   67:     function promptQuickstart()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
 
 
require_once(PATH_tslib . 'class.tslib_pibase.php');

class tx_browser_typoscript extends tslib_pibase
{

    // The extension key.
  public $extKey        = 'browser';
  public $prefixId      = 'tx_browser_typoscript';
    // Path to any file in pi1 for locallang
  public $scriptRelPath = 'lib/class.tx_browser_typoscript.php';








  /**
 * static_country_zones():  Function to use in own TCA definitions
 *                          Adds additional select items
 *
 * @param string    $content: The content of the PlugIn
 * @param array   $conf: The PlugIn Configuration
 * @return  string    The content that should be displayed on the website
 *
 */
  public function static_country_zones($content = '', $conf = array())
  {
    global $TSFE;
    $local_cObj = $TSFE->cObj; // cObject

//var_dump(__FILE__ . ': ' . __LINE__ . " :\n\n" . $content, $conf);
//die('test dwildt '. '###');
       // TS configuration
    $lConf    = $conf['userFunc.'];
       // get the wrapped zones
    $content  = $local_cObj->cObjGetSingle($lConf['zones'], $lConf['zones.']);
    
//    return 'test';
    return $content;

//      // If the user stores the country id in antother field than 'static_countries'
//    $str_tcaFieldForStaticCountries = $params['config']['itemsProcFunc_conf']['countries_are_in'];
//    if(empty($str_tcaFieldForStaticCountries))
//    {
//      $str_tcaFieldForStaticCountries = 'static_countries';
//    }
//
//      // get the uid of the current country
//    $uid_StaticCountries  = $params['row'][$str_tcaFieldForStaticCountries];
//    
//      // Build the SELECT statement    
//    $select   = 'static_country_zones.uid as itemKey, static_country_zones.zn_name_local as itemValue';
//    $from     = 'static_country_zones, static_countries';
//    $where    = 'static_countries.cn_iso_nr  = static_country_zones.zn_country_iso_nr AND static_countries.uid = '.$uid_StaticCountries;
//    $groupBy  = null;
//    $orderBy  = 'zn_name_local';
//    $limit    = null;
//    
//      // Exexcute the SELECT statemant
//    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
//
//      // Allocate key and value to the params item array
//    while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
//    {
//      //var_dump($row);
//      $params['items'][] = array($row['itemValue'], $row['itemKey']);
//    }
//
//      // Free the SQL result
//    $GLOBALS['TYPO3_DB']->sql_free_result($res);
//    
//      // If there isn't any state/zone, deliver an empty value
//    if(empty($params['items']))
//    {
//      $params['items'][] = array('', 0);
//    }
//
//    return $params;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/org_workshops/lib/class.tx_browser_typoscript.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/org_workshops/lib/class.tx_browser_typoscript.php']);
}

?>