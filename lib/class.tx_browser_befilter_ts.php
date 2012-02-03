<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_befilter_ts bundles methods the allocation of page TSconfig and TypoScript configuration
 *
 * @author      Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.8
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
      // Require classes
    require_once(PATH_t3lib.'class.t3lib_page.php');
    require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
    require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');

  }

  /**
 * regard_pageTSconfig_in_foreignTableWhere():  Replaces markers in the andWhere statement
 *                                              with corresponding values of the page TSconfig
 *                                              Marker are
 *                                              * ###PAGE_TSCONFIG_ID###
 *                                              * ###PAGE_TSCONFIG_IDLIST###
 *                                              * ###PAGE_TSCONFIG_STR###
 *                                              * See
 *                                                * document "TYPO3 core APIs"
 *                                                  section ['columns'][fieldname]['config'] / TYPE: "select"
 *
 * @param array   $pObj: parent object
 * @param string    $table: name of the current record
 * @param string    $field: field of the current record
 * @return  array   $conf: rendered TCA configuration of the given table and field
 * @version     3.9.8
 * @since       3.9.8
 */
  public function regard_pageTSconfig_in_foreignTableWhere($pObj, $table, $field)
  {
    $conf = $pObj->conf;

      // There is an andWhere
    if(isset($conf['foreign_table_where'])) {
        // LOOP each marker value in the page TSconfig
      foreach((array) $pObj->pageTSconfig['TCEFORM.'][$table . '.'][$field . '.'] as $key => $value) {
        //var_dump(__METHOD__, __LINE__, $pObj->conf, $pObj->pageTSconfig['TCEFORM.'][$table][$field]);
        $marker = '###' . $key . '###';
          // Replace each marker in the andWhere with the value from the page TSconfig
        $conf['foreign_table_where'] = str_replace($marker, $value, $conf['foreign_table_where']);
      }
        // LOOP each marker value in the page TSconfig
    }
      // There is an andWhere

    return $conf;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_ts.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_ts.php']);
}
?>