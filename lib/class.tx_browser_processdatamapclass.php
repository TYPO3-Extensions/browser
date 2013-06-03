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
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


require_once(PATH_t3lib.'class.t3lib_tceforms.php');


 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   28: class tx_browser_befilter_hooks implements t3lib_localRecordListGetTableHook
 *   41:     public function getDBlistQuery ($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
 *   88:     public function makeFormitem($item, $table, $conf)
 *  151:     public function editFormitem($confarray, $item, $labelValue)
 *  170:     public function makeWhereClause($item, $conf, $itemValue, $table)
 *  212:     public function makeQueryInputTrim($item, $itemValue, $table)
 *  225:     public function makeQuerySelect($item, $itemValue, $table)
 *  238:     public function makeQueryCheckTime($item, $itemValue, $table)
 *  253:     public function makeQueryInputFromto($item, $from, $to, $table)
 *  275:     public function getTimestampFrom($timetime)
 *  287:     public function getTimestampTo($timetime)
 *  303:     private function init_ts()
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_processcatamapclass {

/**
 * processDatamap_beforeStart( ): 
 *
 * @return  void
 * @author dwildt
 * @since     4.5.7
 * @version   4.5.7
 */
  public function processDatamap_postProcessFieldArray( $status, $table, $id, &$fieldArray, &$pObj )
  {
    $fieldArray['hidden'] = 1;
//    $prompt = 'Test processDatamap_postProcessFieldArray';
//    t3lib_div :: devLog( '[TEST/BROWSER] ' . $prompt , $this->pObj->extKey, 3 );
//    echo __METHOD__ . ':' . __LINE__;
//    die( __METHOD__ . ':' . __LINE__ );
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_processDatamapClass.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_processDatamapClass.php']);
}

?>