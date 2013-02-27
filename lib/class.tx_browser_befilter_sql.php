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
 * The class tx_browser_befilter_sql bundles methods for sql relation building
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
 *   48: class tx_browser_befilter_sql
 *   66:     public function makeQueryArray_post(&$queryParts, $parentObject, $table, $id, $addWhere, $fieldList, $_params)
 *  109:     public function andWhere($pObj, $table, $field, $operator, $value)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_befilter_sql
{
  var $addFromTables = null;

  /**
 * makeQueryArray_post(): Will be called by a hook from typo3/class.db_list.inc. The hook enables to edit the rendered SQL query.
 *                        The method will add from tables, if the are needed by a MM relation.
 *                        It takes care of a proper sql query.
 *                        At the bottom is code for developers for analizing the rendered SQL query.
 *
 * @param array   $queryParts: the parts for the SQL query
 * @param object    $parentObject: the parent object
 * @param string    $table: name of the current table (in the TCA)
 * @param integer    $id: Id of the current page.
 * @param string    $addWhere: andWhere statement, rendered by the parentObject or a hook class before this hook class
 * @param string    $fieldList: csv separated list of the displayed field in the TCE form
 * @param array    $_params: some parameters
 * @return  void
 * @version     3.9.8
 * @since       3.9.8
 */
  public function makeQueryArray_post(&$queryParts, $parentObject, $table, $id, $addWhere, $fieldList, $_params)
  {
      // Get the extra from fields from the session
    $arr_session = $GLOBALS['BE_USER']->getSessionData('tx_browser_befilter_sql');

      // Add the extra from fields to the FROM statement. Take care of a proper query 
    if(isset($arr_session['addFromTables'][$table]))
    {
        // Add to the fields in the SELECT statement the local table
      $arr_select = explode(',', $queryParts['SELECT']);
      foreach($arr_select as $key_select =>$value_select)
      {
        $arr_select[$key_select] = $table . '.' . $value_select;
      }
      $queryParts['SELECT'] = implode(',', $arr_select);
        // Add to the fields in the SELECT statement the local table

        // Prepend the local table to the WHERE statement: 'pid = ...' becomes 'table.pid = ...'
        //  DANGEROUS: It is needed in case of a MM relation, but it isn't hardly tested
      $queryParts['WHERE']  = $table . '.' . $queryParts['WHERE'];

        // Add the extra tables to the FROM statement
      $addFrom = implode(', ', $arr_session['addFromTables'][$table]);
      $queryParts['FROM']   = $queryParts['FROM'] . ', ' . $addFrom;
        // Add the extra tables to the FROM statement

        // Remove extra from tables from the session
      unset($arr_session['addFromTables'][$table]);
      $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_befilter_sql', array( 'addFromTables' => $arr_session['addFromTables']));

          // Development
          // If there will be any strange behaviour or a broken result, discomment the next lines.
          // You will get the SQL query and you can analize it!
//      $query = $GLOBALS['TYPO3_DB']->SELECTquery(
//                                      $queryParts['SELECT'],
//                                      $queryParts['FROM'],
//                                      $queryParts['WHERE'],
//                                      $queryParts['GROUPBY'],
//                                      $queryParts['ORDERBY'],
//                                      $queryParts['LIMIT']
//                                    );
//      var_dump(__METHOD__, __LINE__, $queryParts, $query);
          // Development
    }
      // Add the extra from fields to the FROM statement. Take care of a proper query 
  }


  /**
 * get_andWhere():  Delivers the andWhere statement. Method will analize the relation configured by the TCA
 *                  and build the andWhere statement in dependence on 
 *                  * a simple relation (without an MM table)
 *                  * a MM relation or 
 *                  * opposite MM relation
 *
 * @param array   $pObj: parent object
 * @param string    $table: name of the current table (in the TCA)
 * @param string    $field: name of the current field in the current table
 * @param string    $operator: SQL operator for andWhere statement like =, <= or =>
 * @param string    $value: current value of the table.field
 * @return  string   $andWhere: andWhere statement for simple relation, MM relation or opposite MM relation
 * @version     3.9.8
 * @since       3.9.8
 */
  public function get_andWhere($pObj, $table, $field, $operator, $value)
  {
      // TCA configuration of the current table.field
    $conf = $pObj->conf;

      // Default andWhere statement (simple relation)
    $andWhere = ' AND (' . $table . '.' . $field . ' ' . $operator . ' \''. $value . '\')';
    switch(true)
    {
      case(isset($conf['MM_opposite_field'])):
          // Opposite MM relation
          // andWhere statement
        $localTableUid      = $conf['foreign_table']  . '.uid';
        $foreignTableUid    = $table                  . '.uid';
        $foreignTableField  = $table                  . '.' . $field;
        $mmUidLocal         = $conf['MM']             . '.uid_local';
        $mmUidForeign       = $conf['MM']             . '.uid_foreign';
        $andWhere = ' AND (' .
                        $localTableUid . ' = ' . $mmUidLocal . ' ' .
                        'AND ' . $mmUidForeign . ' = ' . $foreignTableUid . ' ' .
                        'AND ' . $localTableUid . ' ' . $operator . ' \''. $value . '\'' .
                    ')';
          // andWhere statement
          // Add extra from tables to the session
        $this->addFromTables[$table][] = $conf['MM'];
        $this->addFromTables[$table][] = $conf['foreign_table'];
        $this->addFromTables[$table] = array_unique($this->addFromTables[$table]);
        $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_befilter_sql', array( 'addFromTables' => $this->addFromTables));
          // Add extra from tables to the session
          // Opposite MM relation
        break;
      case(isset($conf['MM'])):
          // MM relation
          // andWhere statement
        $localTableUid      = $table                  . '.uid';
        $foreignTableUid    = $conf['foreign_table']  . '.uid';
        $foreignTableField  = $conf['foreign_table']  . '.' . $field;
        $mmUidLocal         = $conf['MM']             . '.uid_local';
        $mmUidForeign       = $conf['MM']             . '.uid_foreign';
        $andWhere = ' AND (' .
                        $localTableUid . ' = ' . $mmUidLocal . ' ' .
                        'AND ' . $mmUidForeign . ' = ' . $foreignTableUid . ' ' .
                        'AND ' . $foreignTableUid . ' ' . $operator . ' \''. $value . '\'' .
                    ')';
          // andWhere statement
          // Add extra from tables to the session
        $this->addFromTables[$table][] = $conf['MM'];
        $this->addFromTables[$table][] = $conf['foreign_table'];
        $this->addFromTables[$table] = array_unique($this->addFromTables[$table]);
        $GLOBALS['BE_USER']->setAndSaveSessionData('tx_browser_befilter_sql', array( 'addFromTables' => $this->addFromTables));
          // Add extra from tables to the session
          // MM relation
        break;
      default:
          // Simple relation
          // Take the default andWhere statement from above
          // Any table hasn't add to the FROM statement, no needs for the session
        break;
    }

    return $andWhere;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_sql.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_sql.php']);
}
?>