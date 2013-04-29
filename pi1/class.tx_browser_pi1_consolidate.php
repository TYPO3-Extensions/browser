<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_consolidate bundles methods which are consolidating data
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
*
* @version  4.2.0
* @since    3.4.4
*
* @package    TYPO3
* @subpackage  browser
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   59: class tx_browser_pi1_consolidate
 *   85:     function __construct($parentObj)
 *  106:     function consolidate($rows)
 *  616:     function init_arrConsolidation()
 *  708:     function addUidAndPid()
 *
 *              SECTION: Consolidate Children (Single View and Development only)
 *  808:     function children_relation()
 * 1012:     function fields_wi_relation()
 * 1080:     function fields_wi_marker($arr_fields_wi_relation)
 * 1131:     function tsConf_TEXT_path_wi_marker($arr_fields_wi_relation)
 * 1245:     function manipulate_tsConf($arr_tsConf_TEXT_path_wi_marker, $arr_fields_wi_relation)
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_consolidate
{

  var $arr_conf_consolidation = false;
  // [Array] Array with Consolidation Information
  var $bool_conf_unique_rows = false;
  // [Boolean] Should the SQL result consolidated? Only unique rows?

  var $arr_row_current    = null;
  // [Array] The current row
  var $arr_fields_current = null;
  // [Array] The keys of the current row. Key syntax is table.field








    /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }






    /**
 * consolidate:   Consolidate Rows: If the localtable has more than one record per uid, we have
 *                a relation. This method tries to consolidate the foreign records. If we have
 *                more than one foreign record per localtable, the values would concatenated.
 *                The mothod requires a TypoScript permission:
 *                  autoconfig.consolidation.sql.rows.unique = true
 *
 * @param	array		$rows: The rows form the SQL result
 * @return	array		$rows_new: Consolidated rows.
 * @version   4.2.0
 * @since    3.4.4
 */
  function consolidate( $rows )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

    $arr_return = array( );
    $arr_return['data']['uids']                 = false;
    $arr_return['data']['rows']                 = $rows;
    $arr_return['data']['rows_wo_cons']         = count($rows);
    $arr_return['data']['rows_wi_cons']         = count($rows);
    $this->pObj->arrConsolidate['rows_wo_cons'] = count($rows);
    $this->pObj->arrConsolidate['rows_wi_cons'] = count($rows);


      //////////////////////////////////////////////////////////////////////
      //
      // RETURN if there ins't any row

    if (!is_array($rows))
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[WARN/SQL] Rows aren\'t an array. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/SQL] Without rows we don\'t need any consolidtaion.', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
    if (count($rows) < 1)
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[WARN/SQL] Rows aren\'t an array. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/SQL] Without rows we don\'t need any consolidtaion.', $this->pObj->extKey, 0);
      }
      return $arr_return;
    }
      // RETURN if there ins't any row



      ////////////////////////////////////////////////////////////////////
      //
      // Init consolidation array

    $this->init_arrConsolidation( );
    if( ! $this->bool_conf_unique_rows )
    {
      return $arr_return;
    }
      // Init consolidation array


      // 121211, dwildt, 2-
    //$conf_sqlRowsUnique     = $this->arr_conf_consolidation['sql.']['rows.']['unique.'];
    //$bool_rmNonUniqueValue  = $conf_sqlRowsUnique['rm_nonUnique_values'];
      // Should rows consolidated?



      ////////////////////////////////////////////////////////////////////
      //
      // Do we have non unique rows

      // Do we have a showUid not for the local table but for the foreign table? 3.3.3
    if( $this->pObj->arrLocalTable['showUid4TableField'] )
    {
        // 121211, dwildt, 1-
      // list( $localTable, $dummyField ) = explode( '.', $this->pObj->arrLocalTable['showUid4TableField'] );
        // 121211, dwildt, 1+
      list( $localTable ) = explode( '.', $this->pObj->arrLocalTable['showUid4TableField'] );
    }
    if( ! $this->pObj->arrLocalTable['showUid4TableField'] )
    {
      $localTable = $this->pObj->localTable;
    }
      // Do we have a showUid not for the local table but for the foreign table? 3.3.3

    foreach( ( array ) $rows as $elements )
    {
      $arr_localTable_uid[] = $elements[$localTable.'.uid'];
    }
    $int_rows_nonUnique = count($arr_localTable_uid);
    $arr_localTable_uid = array_unique($arr_localTable_uid);
    $int_rows_unique    = count($arr_localTable_uid);
    $arr_return['data']['rows_wi_cons'] = $int_rows_unique;

      // RETURN: all rows are unique
    if( $int_rows_nonUnique == $int_rows_unique )
    {
      if( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] Rows are unique: #' . $int_rows_nonUnique . ' rows. Nothing to consolidate.', $this->pObj->extKey, 0);
      }
      $this->pObj->arrConsolidate['rows_wi_cons'] = count( $rows );
      return $arr_return;
    }
      // RETURN: all rows are unique

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] Rows aren\'t unique.<br />
        Non unique: #'.$int_rows_nonUnique.' rows<br />
        Unique: #'.$int_rows_unique.' rows.', $this->pObj->extKey, 0);
    }
      // Do we have non unique rows



      ////////////////////////////////////////////////////////////////////
      //
      // Get all foreign tables, which have an uid

    reset($rows);
    $int_keyFirstRow = key($rows);
    $arr_tableFields = array_keys( $rows[$int_keyFirstRow] );
    foreach( ( array ) $arr_tableFields as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      if( $table != $localTable )
      {
        if( $field == 'uid' )
        {
          $arr_foreignTables[] = $table;
        }
      }
    }
    $arr_foreignTables = array_unique( $arr_foreignTables );
    if ($this->pObj->b_drs_sql)
    {
      $prompt_foreignTables = implode('<br />', $arr_foreignTables);
      $prompt = 'We found this foreign tables with an uid field: ' . $prompt_foreignTables;
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // Get all foreign tables, which have an uid



      /////////////////////////////////////////////////////////////////
      //
      // WORKAROUND

      // BUG (101029): If there are only rows from the local table,
      //               rows will be empty after consolidation

    if( empty( $arr_foreignTables ) )
    {
      if( $this->pObj->b_drs_warn )
      {
        $prompt = 'WORKAROUND: There isn\'t any foreign table. This case is buggy!';
        t3lib_div::devlog( '[WARN/SQL] ' . $prompt, $this->pObj->extKey, 3 );
      }
        // RETURN first row
      if( $int_rows_unique == 1 )
      {
        if( $this->pObj->b_drs_warn )
        {
          $prompt = 'WORKAROUND: First row is returned.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        reset( $rows );
        $firstKey = key( $rows );
        $rows_cons[$firstKey] = $rows[$firstKey];
        $arr_return['data']['rows']                 = $rows_cons;
        $this->pObj->arrConsolidate['rows_wi_cons'] = count($rows_cons);
        return $arr_return;
      }
        // RETURN first row

      if( $int_rows_unique > 1 )
      {
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'WORKAROUND: There is more than 1 unique row. ' .
                    'And without any foreign table. Sorry, but this can\'t be true!';
          t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->pObj->extKey, 3 );
        }
      }
    }
      // WORKAROUND



      /////////////////////////////////////////////////////////////////
      //
      // Get all mm relation tables and all foreign tables, #9727

    $arr_mm_tables = array();
      // fsander, 101023    -- check if we have an array first
    if (is_array($this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM'])) {
      foreach((array) $this->pObj->objSqlAut_3x->arr_relations_mm_simple['MM'] as $arr_relation_tables)
      {
        foreach((array) $arr_relation_tables as $str_relation_table => $str_foreign_table)
        {
          $arr_mm_tables[$str_relation_table][]     = $str_foreign_table;
            // 121211, dwildt, 1-
          //$arr_foreign_tables[$str_foreign_table][] = $str_relation_table;
        }
      }
    }
      // Get all mm relation tables and all foreign tables, #9727



    ////////////////////////////////////////////////////////////////////
    //
    // Loop through the localTable array with all unique ids and loop through all rows

    $arr_localTable_foreignTables = false;
    $str_localTableUid            = $localTable.'.uid';
    foreach( ( array ) $arr_localTable_uid as $localUid )
    {
      foreach( ( array ) $arr_foreignTables as $foreignTable )
      {
        foreach((array) $rows as $row => $elements)
        {
          if( $elements[$str_localTableUid] == $localUid )
          {
            $bool_newId = false;
            $int_foreignUid = $elements[$foreignTable.'.uid'];

            // 2nd Loop
            if (is_array($arr_localTable_foreignTables[$localUid][$foreignTable]))
            {
              $arr_foreignUid = array_keys($arr_localTable_foreignTables[$localUid][$foreignTable]);
              if (!in_array($int_foreignUid, $arr_foreignUid))
              {
                $arr_localTable_foreignTables[$localUid][$foreignTable][$int_foreignUid] = array();
                $bool_newId = true;
              }
            }
            // 2nd Loop

            // 1st Loop
            if (!is_array($arr_localTable_foreignTables[$localUid][$foreignTable]))
            {
              if($int_foreignUid)
              {
                $arr_localTable_foreignTables[$localUid][$foreignTable][$int_foreignUid] = array();
                $bool_newId = true;
              }
            }
            // 1st Loop
            foreach((array) $elements as $tableField => $element)
            {
              list($table, $field) = explode('.', $tableField);
              if ($bool_newId)
              {
                if ($table == $foreignTable)
                {
                  $arr_localTable_foreignTables[$localUid][$table][$int_foreignUid][$field] = $element;
                }
                if ( ! in_array( $table, $arr_foreignTables ) )
                {
                  if( $table != $localTable )
                  {
                    // #9727
                    $bool_trueForeignTable = false;
                    // $table is a MM table
                    if(in_array($table, array_keys($arr_mm_tables)))
                    {
                      if(in_array($foreignTable, $arr_mm_tables[$table]))
                      {
                        $bool_trueForeignTable = true;
                      }
                    }
                    // $table is a MM table
                    // $table is a foreign table
                    if(!in_array($table, array_keys($arr_mm_tables)))
                    {
                      $bool_trueForeignTable = true;
                    }
                    // $table is a foreign table
                    if($bool_trueForeignTable)
                    {
                      $arr_localTable_foreignTables[$localUid][$table][$row][$field] = $element;
                    }
                  }
                }
              }
              if ($table == $localTable)
              {
                $int_localUid = $elements[$table.'.uid'];
                if($int_localUid)
                {
                  $arr_localTable_foreignTables[$int_localUid][$table][$int_localUid][$field] = $element;
                }
              }
            }
          }
        }
      }
    }
    // Loop through the localTable array with all unique ids and loop through all rows

    $arr_rows_consolidated_fields = explode(',', $this->pObj->csvSelectWoFunc);
    foreach ($arr_rows_consolidated_fields as $key => $tableField)
    {
      $arr_rows_consolidated_fields[$key] = trim($tableField);
    }


    $str_localTableUid = $this->pObj->localTable.'.uid';
    if (!in_array($str_localTableUid, $arr_rows_consolidated_fields))
    {
      $arr_rows_consolidated_fields[] = $str_localTableUid;
    }



    //////////////////////////////////////////////////////////////////////
    //
    // Children devider configuration, #9727


    $this->pObj->objTyposcript->set_confSqlDevider();
    $str_devider = $this->pObj->objTyposcript->str_sqlDeviderDisplay.$this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    // Children devider configuration, #9727



    //////////////////////////////////////////////////////////////////////
    //
    // TypoScript: consolidate groupBy? Bugfix #9025, #8523

    $bool_dontConsolidate = false;
    if(!empty($conf_view['groupBy.']['dontConsolidate']))
    {
      if($conf_view['groupBy.']['dontConsolidate'] == 1)
      {
        $bool_dontConsolidate = true;
      }
    }
    // TypoScript: consolidate groupBy? Bugfix #9025, #8523



    //////////////////////////////////////////////////////////////////////
    //
    // Don't consolidate groupBy. Bugfix #9025, #8523

    if($bool_dontConsolidate)
    {
      $rows_cons = $rows;
    }
    // Don't consolidate groupBy. Bugfix #9025, #8523



    //////////////////////////////////////////////////////////////////////
    //
    // Consolidate groupBy. Bugfix #9025, #8523

    if(!$bool_dontConsolidate)
    {
      $int_count              = 0;
      $rows_cons              = false;
      $arr_children_to_devide = array();

      list($groupBy_table, $groupBy_field) = explode('.', $this->pObj->conf_sql['groupBy']);

        // Loop through all tables (local and foreign)
        // 121211, dwildt, 1-
      //foreach ($arr_localTable_foreignTables as $localTableUid => $arrTables)
        // 121211, dwildt, 1+
      foreach( $arr_localTable_foreignTables as $arrTables )
      {
          // Loop through all tables (key is the uid of the local record)
        foreach ($arrTables as $table => $arrRecordUids)
        {
            // Loop through all elements (key is the uid of the current record)
            // 121211, dwildt, 1-
          //foreach ($arrRecordUids as $redordUid => $arrFields)
            // 121211, dwildt, 1+
          foreach ($arrRecordUids as $arrFields)
          {
            $bool_new = false;

            // 2nd loop at least
              // #42565, 121031, dwildt, 1-
//            if( $rows_cons[$int_count][$table.'.uid'] )
              // #42565, 121031, dwildt, 1+
            if( isset( $rows_cons[$int_count][$table.'.uid'] ) )
            {
              $arrUids = explode(', ', $rows_cons[$int_count][$table.'.uid']);
              if (!in_array($arrFields['uid'], $arrUids))
              {
//$this->pObj->dev_var_dump( $table.'.uid' );    
                $rows_cons[$int_count][$table.'.uid'] .= $str_devider.$arrFields['uid'];
                $bool_new = true;
                $arr_children_to_devide[] = $table.'.uid';  // 3.3.3
              }
            }
            // 2nd loop at least

            // 1st loop
              // #42565, 121031, dwildt, 1-
//            if( ! $rows_cons[ $int_count ][ $table . '.uid' ] )
              // #42565, 121031, dwildt, 1+
            if( ! isset( $rows_cons[ $int_count ][ $table . '.uid' ] ) )
            {
              if( ! empty( $arrFields['uid'] ) )
              {
                $rows_cons[$int_count][$table.'.uid'] = $arrFields['uid'];
              }
              $bool_new = true;
            }
            // 1st loop

            // We have a new record
            if( $bool_new )
            {
              // Loop through all elements
              foreach( $arrFields as $field => $value )
              {
                  // CONTINUE : current field is the uid
                if( $field == 'uid' )
                {
                  continue;
                }
                  // CONTINUE : current field is the uid
                
                // 2nd loop at least
                  // #42565, 121031, dwildt, 1-
//                if( $rows_cons[ $int_count ][ $table . '.' . $field ] )
                  // #42565, 121031, dwildt, 1+
                if( isset( $rows_cons[ $int_count ][ $table . '.' . $field ] ) )
                {
                  if( $table . '.' . $field == $groupBy_table . '.' . $groupBy_field )
                  {
                    $rows_cons[$int_count][$table.'.'.$field] = $value;
                  }
                  if( $table . '.' . $field != $groupBy_table . '.' . $groupBy_field )
                  {
                    $rows_cons[$int_count][$table.'.'.$field] .= $str_devider . $value;
                    $arr_children_to_devide[] = $table.'.'.$field;  // 3.3.3
                  }
                }
                // 2nd loop at least
                // 1st loop
                  // #42565, 121031, dwildt, 1-
//                if( ! $rows_cons[ $int_count ][ $table . '.' . $field ] )
                  // #42565, 121031, dwildt, 1+
                if( ! isset( $rows_cons[ $int_count ][ $table . '.' . $field ] ) )
                {
                  $rows_cons[$int_count][$table.'.'.$field] = $value;
                }
                // 1st loop
              }
              // Loop through all elements
            }
            // We have a new record
          }
          // Loop through all elements (key is the uid of the current record)
        }
        // Loop through all tables (key is the uid of the local record)
        $int_count++;
      }
      // Loop through all tables (local and foreign)
    }
    // Consolidate groupBy. Bugfix #9025, #8523
//$this->pObj->dev_var_dump( $rows_cons );    



    //////////////////////////////////////////////////////////////////////
    //
    // Prepaire global array for children and link workflow

    $this->pObj->arr_children_to_devide = array_unique($arr_children_to_devide);  // 3.3.3
    //if(t3lib_div::_GP('dev')) var_dump('sql_func 2525', $this->pObj->arr_children_to_devide);
    // Prepaire global array for children and link workflow



    $arrSelectWoFunc = explode(',', $this->pObj->csvSelectWoFunc);
//if(t3lib_div::_GP('dev')) var_dump('cons 505', $this->pObj->csvSelectWoFunc);
    // dwildt, 100428: ADDED in context with table_mm.sorting
    $arrMMSorting    = $this->pObj->arrConsolidate['select']['mmSortingTableFields'];
    $int_count = 0;
    // Store uids. We need it for link to single view and for the the Index-Browser.
    $arr_uids = false;
    foreach ($rows_cons as $row_cons)
    {
      //$arr_uids[] = $row_cons[$localTable.'.uid'];
      $rows_new[$int_count][$localTable.'.uid'] = $row_cons[$localTable.'.uid'];
      foreach ($arrSelectWoFunc as $tableField)
      {
        $tableField = trim($tableField);
        $rows_new[$int_count][$tableField] = $row_cons[$tableField];
      }
      // dwildt, 100428: ADDED in context with table_mm.sorting
      if(is_array($arrMMSorting))
      {
        foreach ($arrMMSorting as $tableField)
        {
          $tableField = trim($tableField);
          $rows_new[$int_count][$tableField] = $row_cons[$tableField];
        }
      }
      // dwildt, 100428: ADDED in context with table_mm.sorting
      $int_count++;
    }
    $arr_return['data']['uids'] = $arr_uids; // :todo: 100429, dwildt: array will never be filled (see above)
    $arr_return['data']['rows'] = $rows_new;
    $this->pObj->arrConsolidate['rows_wi_cons'] = count($rows_new);
//if(t3lib_div::_GP('dev')) var_dump('cons 534', array_keys(current($rows_new)));


    return $arr_return;
  }















  /**
 * init_arrConsolidation:   Inits the consolidation boolean and array.
 *                          Values are out of the TypoScript.
 *
 * @return	boolean		False
 */
  function init_arrConsolidation()
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];


    ///////////////////////////////////
    //
    // Get the local or gloabl autoconfig array

    $lAutoconf = $conf_view['autoconfig.'];
    if (!is_array($lAutoconf))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] views.'.$viewWiDot.$mode.' hasn\'t any autoconf array.<br />
          We take the global one.', $this->pObj->extKey, 0);
      }
      $lAutoconf = $conf['autoconfig.'];
    }
    // Get the local or gloabl autoconfig array


    ///////////////////////////////////
    //
    // Set the boolean sqlRowsUnique

    $bool_sqlRowsUnique = $lAutoconf['consolidation.']['sql.']['rows.']['unique'];
    // Set the boolean sqlRowsUnique


    ///////////////////////////////////
    //
    // DRS - Development Reporting System

    if (!$bool_sqlRowsUnique)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] autoconfig.consolidation.sql.rows.unique is FALSE<br />
          Rows won\'t be consolidated.', $this->pObj->extKey, 0);
      }
    }
    // 110110, cweiske, #11973
    if ($bool_sqlRowsUnique)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] autoconfig.consolidation.sql.rows.unique is TRUE<br />
          Rows will be consolidated.', $this->pObj->extKey, 0);
      }
    }
    // DRS - Development Reporting System


    ///////////////////////////////////
    //
    // Set the globals

    $this->bool_conf_unique_rows  = $bool_sqlRowsUnique;
    $this->arr_conf_consolidation = $lAutoconf['consolidation.'];
    // Set the globals


    return false;

  }















/**
 * addUidAndPid:    Returns an array with table.uid, which are missing in the select statement
 *                  It depends on the consolidation parameters in TypoScript
 *
 * @return	array		Array with completed arr_realTables_arrFields and with missing table.uids
 * @version   4.2.0 
 */
  function addUidAndPid()
  {
    ///////////////////////////////////
    //
    // Init RETURN array

    $arr_return = array( );
    $arr_return['data']['arrFetchedTables'] = $this->pObj->arr_realTables_arrFields;
    if( isset( $this->pObj->arrConsolidate['addedTableFields'] ) )
    {
      $arr_return['data']['consolidate']['addedTableFields'] = $this->pObj->arrConsolidate['addedTableFields'];
    }
    if( ! isset($this->pObj->arrConsolidate['addedTableFields'] ) )
    {
      $arr_return['data']['consolidate']['addedTableFields'] = false;
    }
    // Init RETURN array


    ///////////////////////////////////
    //
    // Init consolidation array

    $this->init_arrConsolidation();
    if (!$this->bool_conf_unique_rows)
    {
      return $arr_return;
    }
    // Init consolidation array


    ///////////////////////////////////
    //
    // Add table.uids to the global arr_realTables_arrFields

      // 121211, dwildt, 1-
$this->pObj->dev_var_dump( $this->pObj->arr_realTables_arrFields );
    //$arr_TCAcolumns = false;
    foreach ( $this->pObj->arr_realTables_arrFields as $table => $arrFields )
    {
//      $this->pObj->objZz->loadTCA($table);
      // dwildt, 100428: ADDED in context with table_mm.sorting
      // Don't store uid or pid, if table isn't in the TCA
      if( in_array( $table, array_keys( $GLOBALS['TCA'] ) ) )
      {
        $bool_storeId = true;
      }
      if( ! in_array( $table, array_keys( $GLOBALS['TCA'] ) ) )
      {
        $bool_storeId = false;
      }
      // Don't store uid or pid, if table isn't in the TCA
      if( ( ! in_array( 'uid', $arrFields ) ) && $bool_storeId )
      {
        $arr_return['data']['arrFetchedTables'][$table][] = 'uid';
        $arr_return['data']['consolidate']['addedTableFields'][] = $table.'.uid';
      }
      if( ( ! in_array( 'pid', $arrFields ) ) && $bool_storeId )
      {
        $arr_return['data']['arrFetchedTables'][$table][] = 'pid';
        $arr_return['data']['consolidate']['addedTableFields'][] = $table.'.pid';
      }
    }
//var_dump('sql_func 2238', $arr_return);
    // Add table.uids to the global arr_realTables_arrFields


    return $arr_return;
  }











  /***********************************************
   *
   * Consolidate Children (Single View and Development only)
   *
   **********************************************/









    /**
 * children_relation():     Consolidate children
 *
 * @return	void
 * @internal  http://forge.typo3.org/issues/9838
 * @since     3.4.4
 * @version   3.4.4
 */
  function children_relation()
  {
    static $bool_this_firstLoop     = true;
    static $arr_fields_wi_relation  = null;

      // 121211, dwildt, 5-
    //$conf = $this->pObj->conf;
    //$mode = $this->pObj->piVar_mode;
    //$view = $this->pObj->view;
    //$viewWiDot = $view.'.';
    //$conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

    $rows = $this->pObj->rows;
    //var_dump('cons 748', $rows);



    /////////////////////////////////////////////////////////////////
    //
    // RETURN rows contain more than one row

    if(count($rows) > 1)
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[WARN/SQL] ABORTED children_relation() can handle one row only. '.
          'But rows contain more than one row!', $this->pObj->extKey, 2);
      }
      return;
    }
    // RETURN rows contain more than one row



      ///////////////////////////////////////////////////////////////
      //
      // Set global arr_row_current and arr_fields_current

      // 110811, pochart: Core: Error handler (FE): PHP Warning: key() [<a href='function.key'>function.key</a>]: Passed variable is not an array or object in typo3conf/ext/browser/pi1/class.tx_browser_pi1_consolidate.php line 847
    if(is_array($rows))
    {
      reset($rows);
      $firstKey   = key($rows);
      $this->arr_row_current    = $rows[$firstKey];
      $this->arr_fields_current = array_keys($rows[$firstKey]);
    }
      // 110811, dwildt +
    if(!is_array($rows))
    {
      if ($this->pObj->b_drs_warn)
      {
        t3lib_div::devlog('[INFO/WARN] $rows is empty. Does it have any logic? The developer is asking. This is a :TODO: ', $this->pObj->extKey, 2);
      }
      $this->arr_row_current    = null;
      $this->arr_fields_current = null;
    }
      // 110811, dwildt +
      // Set global arr_row_current and arr_fields_current



    /////////////////////////////////////////////////////////////////
    //
    // Get fields with a relation

    if($bool_this_firstLoop)
    {
      $arr_fields_wi_relation = $this->fields_wi_relation();
      //var_dump('cons 782', $arr_fields_wi_relation);
    }
    // RETURN there isn't any child or any child with a relation
    if(empty($arr_fields_wi_relation))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] RETURN children_relation() doesn\'t found any '.
          'child or any child with any relation.', $this->pObj->extKey, 0);
      }
      return;
    }
    // RETURN there isn't any child or any child with a relation
    // Get fields with a relation



    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After fields_wi_relation(): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////////////////////////////////
    //
    // Get marker array

    if($bool_this_firstLoop)
    {
      $arr_fields_wi_relation = $this->fields_wi_marker($arr_fields_wi_relation);
      //var_dump('cons 806', $arr_fields_wi_relation);
    }
    // RETURN there isn't any child or any child with a relation
    if(empty($arr_fields_wi_relation))
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] RETURN children_relation() doesn\'t found any '.
          'marker in the children configuration of TypoScript.', $this->pObj->extKey, 0);
      }
      return;
    }
    // RETURN there isn't any child or any child with a relation
    // Get marker array



    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After fields_wi_marker(): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////////////////////////////////
    //
    // Substitute marker

    $arr_tsConf_TEXT_path_wi_marker = $this->tsConf_TEXT_path_wi_marker($arr_fields_wi_relation);
    //var_dump('cons 846', $arr_tsConf_TEXT_path_wi_marker);
    $this->manipulate_tsConf($arr_tsConf_TEXT_path_wi_marker, $arr_fields_wi_relation);
    // Substitute marker



    ////////////////////////////////////////////////////////////////////////
    //
    // DRS - Performance

    if ($this->pObj->b_drs_perform) {
      if($this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->getDifferenceToStarttime();
      }
      if(!$this->pObj->bool_typo3_43)
      {
        $endTime = $this->pObj->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After manipulate_tsConf(): '. ($endTime - $this->pObj->tt_startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    // Select * from foreign table

    // Replace all marker with values from foreign table

    $bool_this_firstLoop = false;

    return;
  }









    /**
 * fields_wi_relation():  Find every field with a relation to a foreign table.
 *                        Return an array with relaion information like foreign_table,
 *                        MM and MM_opposite_field
 *
 * @return	array		$arr_fields_wi_relation : array with all table.fields with a relation
 * @internal  http://forge.typo3.org/issues/9838
 * @since     3.4.4
 * @version   3.4.4
 */
  function fields_wi_relation()
  {
    $arr_fields_wi_relation = null;


    ///////////////////////////////////////////////////////////////
    //
    // Store every field with a relation in the $arr_fields_wi_relation

    foreach((array) $this->arr_fields_current as $tableField)
    {
      list($table, $field) = explode('.', $tableField);

      // Load the TCA, if we don't have an table.columns array
      if (!is_array($GLOBALS['TCA'][$table]['columns']))
      {
        t3lib_div::loadTCA($table);
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] $GLOBALS[\'TCA\'][\''.$table.'\'] is loaded.', $this->pObj->extKey, 0);
        }
      }
      // Load the TCA, if we don't have an table.columns array

      // table.field is a relation field
      if(!empty($GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table']))
      {
        // store infos about relation
        $arr_fields_wi_relation[$tableField]['foreign_table'] =
          $GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'];
        $arr_fields_wi_relation[$tableField]['MM'] =
          $GLOBALS['TCA'][$table]['columns'][$field]['config']['MM'];
        if(empty($arr_fields_wi_relation[$tableField]['MM']))
        {
          unset($arr_fields_wi_relation[$tableField]['MM']);
        }
        $arr_fields_wi_relation[$tableField]['MM_opposite_field'] =
          $GLOBALS['TCA'][$table]['columns'][$field]['config']['MM_opposite_field'];
        if(empty($arr_fields_wi_relation[$tableField]['MM_opposite_field']))
        {
          unset($arr_fields_wi_relation[$tableField]['MM_opposite_field']);
        }
        // store infos about relation
      }
      // table.field is a relation field
    }
    // Store every field with a relation in the $arr_fields_wi_relation

    return $arr_fields_wi_relation;
  }









    /**
 * fields_wi_marker(): Find marker with foreign tables in the TypoScript
 *
 * @param	array		$arr_fields_wi_relation : Current rows
 * @return	array		$arr_fields_wi_relation : array with fields with relation and marker
 * @internal  http://forge.typo3.org/issues/9838
 * @since     3.4.4
 * @version   3.4.4
 */
  function fields_wi_marker($arr_fields_wi_relation)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

    foreach((array) $arr_fields_wi_relation as $tableField => $arr_relation_info)
    {
      // Get TypoScript configuration of current table.field
      list($table, $field)  = explode('.', $tableField);
      // 100921, dwildt, Bugfix in t3lib_BEfunc::implodeTSParams
      // See http://bugs.typo3.org/view.php?id=15757 implodeTSParams(): numeric keys will be renumbered
      $arr_ts_one_dimension = t3lib_BEfunc::implodeTSParams($conf_view[$table.'.'][$field.'.']);
      // Get TypoScript configuration of current table.field

      // Check if TypoScript contains marker with foreign table
      $str_searchFor        = '/###'.strtoupper($arr_relation_info['foreign_table']).'.+###/';
      $arr_result = preg_grep($str_searchFor, $arr_ts_one_dimension);
      // Check if TypoScript contains marker with foreign table

      // Remove table.field from array with relation fields
      if(empty($arr_result))
      {
        unset($arr_fields_wi_relation[$tableField]);
      }
      // Remove table.field from array with relation fields
    }
    return $arr_fields_wi_relation;
  }









    /**
 * tsConf_TEXT_path_wi_marker():  Return array with TypoScript paths of all TEXT arrays
 *                                 with markers for foreign tables in the element value
 *
 * @param	array		$arr_fields_wi_relation         : Array with table.fields with relations
 * @return	array		$arr_tsConf_TEXT_path_wi_marker : Array with TypoScript paths
 * @internal  http://forge.typo3.org/issues/9838
 * @since     3.4.4
 * @version   3.4.4
 */
  function tsConf_TEXT_path_wi_marker($arr_fields_wi_relation)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view.'.';
    $conf_view = $conf['views.'][$viewWiDot][$mode.'.'];

    $arr_tsConf_TEXT_path_wi_marker = null;

    // Loop: Each table.field with relation
    foreach((array) $arr_fields_wi_relation as $tableField => $arr_relation_info)
    {
      $bool_handle = true;

      // Relation hasn't any element
      $value = $this->arr_row_current[$tableField];
      if(empty($value))
      {
        $bool_handle = false;
      }
      // Relation hasn't any element

      // Get all tsConf parameters with markers for foreign tables
      if($bool_handle)
      {
        // Get tsConf for table.field as one dimensional array
        list($table, $field)  = explode('.', $tableField);
        // 100921, dwildt, Bugfix in t3lib_BEfunc::implodeTSParams
        // See http://bugs.typo3.org/view.php?id=15757 implodeTSParams(): numeric keys will be renumbered
        $arr_ts_one_dimension = t3lib_BEfunc::implodeTSParams($conf_view[$table.'.'][$field.'.']);
        // Get tsConf for table.field as one dimensional array

        // Search for marker with foreign table
        $str_searchFor  = '/###'.strtoupper($arr_relation_info['foreign_table']).'.+###/';
        $arr_result     = preg_grep($str_searchFor, $arr_ts_one_dimension);
        //var_dump('cons 1019', $conf_view[$table.'.'][$field.'.'], $arr_result);
        // Search for marker with foreign table

        // There isn't any marker for the foreign table in the TypoScript
        if(empty($arr_result))
        {
          $bool_handle = false;
        }
        // There isn't any marker for the foreign table in the TypoScript
      }
      // Get all tsConf parameters with markers for foreign tables

      // Get all tsConf values with markers for foreign tables
      if($bool_handle)
      {
        $arr_tsConf_value = null;
          // 121211, dwildt, 1-
        //foreach((array) $arr_result as $tsConfkey_path => $tsConf_value)
          // 121211, dwildt, 1+
        foreach( array_keys( ( array ) $arr_result ) as $tsConfkey_path )
        {
          $str_searchFor    = '/.value$/';
          preg_match($str_searchFor, $tsConfkey_path, $arr_result);
          //var_dump('cons 1048', $str_searchFor, $tsConfkey_path, $arr_result);
          if(!empty($arr_result))
          {
            $arr_tsConf_value[$tsConfkey_path] = $arr_result;
          }
        }
        if(empty($arr_tsConf_value))
        {
          $bool_handle = false;
        }
      }
      // Get all tsConf values with markers for foreign tables

      // Get all tsConf TEXT arrays with values with markers for foreign tables
      if($bool_handle)
      {
          // 121211, dwildt, 1-
        //foreach((array) $arr_tsConf_value as $key_value => $value_value)
          // 121211, dwildt, 1+
        foreach( array_keys( ( array ) $arr_tsConf_value ) as $key_value )
        {
          $key_TEXT = substr($key_value, 0, strlen($key_value) - strlen('.value'));
          if($arr_ts_one_dimension[$key_TEXT] == 'TEXT')
          {
            $arr_tsConf_TEXT_path_wi_marker[$tableField.'.'.$key_TEXT] = $arr_ts_one_dimension[$key_TEXT];
          }
        }
        if(empty($arr_tsConf_TEXT_path_wi_marker))
        {
          $bool_handle = false;
        }
        //var_dump('cons 1079', $tableField, $arr_tsConf_value, $arr_ts_one_dimension);
      }
      // Get all tsConf TEXT arrays with values with markers for foreign tables

    }
    // Loop: Each table.field with relation

    //var_dump('cons 1086', $arr_ts_one_dimension, $arr_tsConf_TEXT_path_wi_marker);
    return $arr_tsConf_TEXT_path_wi_marker;
  }









    /**
 * manipulate_tsConf(): Simplifing relation building. Enabling several relations to one foreign table.
 *
 * @param	array		$arr_tsConf_TEXT_path_wi_marker : Array with TypoScript paths
 * @param	array		$arr_fields_wi_relation         : Array with table.fields with relations
 * @return	void
 * @internal  http://forge.typo3.org/issues/9838
 * @since     3.4.4
 * @version   3.4.4
 */
  function manipulate_tsConf($arr_tsConf_TEXT_path_wi_marker, $arr_fields_wi_relation)
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot      = $view.'.';
    $conf_view      = $conf['views.'][$viewWiDot][$mode.'.'];
    $conf_view_path = 'views.'.$viewWiDot.$mode.'.';



    ////////////////////////////////////////////////////////////////////////
    //
    // Get typoscript plugin.tx_browser_pi1 as one dim array

    // 100921, dwildt, Bugfix in t3lib_BEfunc::implodeTSParams
    // See http://bugs.typo3.org/view.php?id=15757 implodeTSParams(): numeric keys will be renumbered
    $conf_oneDim      = t3lib_BEfunc::implodeTSParams($conf);
    $conf_oneDim_view = t3lib_BEfunc::implodeTSParams($conf_view);
    // Get typoscript plugin.tx_browser_pi1 as one dim array



    ////////////////////////////////////////////////////////////////////////
    //
    // Get local/global advanced array

    #10116
//var_dump('cons 1185', $view, $conf_view['advanced.']);
    if(!empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf_view['advanced.'];
    }
    if(empty($conf_view['advanced.']))
    {
      $arr_conf_advanced = $conf['advanced.'];
    }
    // Get local/global advanced array



      // Loop: Each TEXT ts conf with marker for foreign tables
      // 121211, dwildt, 1+
    //foreach((array) $arr_tsConf_TEXT_path_wi_marker as $key_TEXT => $value_TEXT)
      // 121211, dwildt, 1+
    foreach( array_keys( ( array ) $arr_tsConf_TEXT_path_wi_marker ) as $key_TEXT )
    {

      $bool_handle = true;

      // browser.handleRelation = true ?
      if(empty($conf_oneDim_view[$key_TEXT.'.browser.handleRelation']))
      {
        $bool_handle = false;
        if ($this->pObj->b_drs_sql)
        {
          t3lib_div::devlog('[INFO/SQL] manipulate_tsConf(): There isn\'t any '.$key_TEXT.
            '.browser.handleRelation. Field won\'t be handled.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/SQL] Change it? Set '.$key_TEXT.
            '.browser.handleRelation = 1', $this->pObj->extKey, 0);
        }
      }
      // browser.handleRelation = true ?

      // Get array with manipulated markers
      if($bool_handle)
      {
        $arr_ts_manipulate = null;
        // Loop: Each ts element of the current view
        $bool_rm_text = false;
        foreach((array) $conf_oneDim_view as $key_oneDim => $value_oneDim)
        {
          // Array with all matched ts elements
          $pos = strpos($key_oneDim, $key_TEXT.'.');
          if(($pos === 0))
          {
            $bool_rm_text = true;
            $arr_ts_manipulate[$key_oneDim] = $value_oneDim;
            unset($conf_oneDim[$conf_view_path.$key_oneDim]);
          }
          // Array with all matched ts elements
        }
        if($bool_rm_text)
        {
          unset($conf_oneDim[$conf_view_path.$key_TEXT]);
        }
        // Loop: Each ts element of the current view
      }
      // Get array with manipulated markers

      // Set some local variables
      if($bool_handle)
      {
        list($table, $field) = explode('.', $key_TEXT);
        $tableField     = $table.'.'.$field;
        $foreign_table  = $arr_fields_wi_relation[$tableField]['foreign_table'];
        $MM             = $arr_fields_wi_relation[$tableField]['MM'];
      }
      // Set some local variables

      // WARN can't handle MM relation
      if($bool_handle)
      {
        if(!empty($MM))
        {
          $bool_handle = false;
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devlog('[WARN/SQL] manipulate_tsConf(): Can\'t handle MM relation now!',
              $this->pObj->extKey, 2);
          }
        }
      }
      // WARN can't handle MM relation

      // WARN current field is empty
      if($bool_handle)
      {
        if(empty($this->arr_row_current[$tableField]))
        {
          $bool_handle = false;
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devlog('[WARN/SQL] manipulate_tsConf(): Current field '.$tableField.' '.
              'is empty!', $this->pObj->extKey, 2);
          }
        }
      }
      // WARN current field is empty

      // Get rows from the foreign table
      if($bool_handle)
      {
          // 121211, dwildt, 1-
        //$arr_uid_foreign = explode(',', $this->arr_row_current[$tableField]);

        $select_fields  = '*';
        $from_table     = $foreign_table;
        $where_clause   = 'uid IN ('.$this->arr_row_current[$tableField].')';
        $orderBy        = null;
        if(!empty($arr_ts_manipulate[$key_TEXT.'.browser.orderBy']))
        {
          $orderBy = $arr_ts_manipulate[$key_TEXT.'.browser.orderBy'];
        }
        if ($this->pObj->b_drs_sql)
        {
          $query = $GLOBALS['TYPO3_DB']->SELECTquery(
                                            $select_fields,
                                            $from_table,
                                            $where_clause,
                                            $groupBy='',
                                            $orderBy,
                                            $limit=''
                                          );
          t3lib_div::devlog('[INFO/SQL] manipulate_tsConf(): '.$query, $this->pObj->extKey, 0);
        }
        $rows_foreignTable = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                                                      $select_fields,
                                                      $from_table,
                                                      $where_clause,
                                                      $groupBy='',
                                                      $orderBy,
                                                        // 121211, dwildt, 1+
                                                      $limit=''
                                                        // 121211, dwildt, 2-
                                                      //$limit='',
                                                      //$uidIndexField=''
                                                    );
        if(empty($rows_foreignTable))
        {
          if ($this->pObj->b_drs_warn)
          {
            t3lib_div::devlog('[WARN/SQL] manipulate_tsConf(): Empty result!', $this->pObj->extKey, 2);
            t3lib_div::devlog('[INFO/SQL] manipulate_tsConf(): '.$query, $this->pObj->extKey, 0);
          }
        }
      }
      // Get rows from the foreign table

      // Set marker array
      $arr_markers = null;
      if($bool_handle)
      {
        $int_counter = 10;
        foreach((array) $rows_foreignTable as $row_foreignTable)
        {
          foreach((array) $row_foreignTable as $field_ft => $value_ft)
          {
            $arr_markers[$int_counter]['###'.strtoupper($foreign_table).'.'.strtoupper($field_ft).'###'] = $value_ft;
          }
          $int_counter = $int_counter + 10;
        }
      }
      // Set marker array

      // Substitute marker
      $arr_new_ts_code = null;
      if($bool_handle)
      {
        end($arr_markers);
        $last_element = key($arr_markers);
        // COA array
        $arr_new_ts_code[$conf_view_path.$key_TEXT] = 'COA';
        // Loop: Each TEXT array
        foreach((array) $arr_markers as $int_element => $arr_marker)
        {
          // TEXT array
          $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.$int_element] = 'TEXT';
          // Loop: Each TEXT element
          foreach((array) $arr_ts_manipulate as $key_manipulate => $value_manipulate)
          {
            $key_short = substr($key_manipulate, strlen($key_TEXT.'.'));
            $value_manipulate = $this->pObj->cObj->substituteMarkerArray($value_manipulate, $arr_marker);
            $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.$int_element.'.'.$key_short] = $value_manipulate;
          }
          // Loop: Each TEXT element

          // Add the devider
          if($int_element != $last_element)
          {
            if(!empty($conf_oneDim_view[$key_TEXT.'.browser.devider']))
            {
              $str_tsType = $conf_oneDim_view[$key_TEXT.'.browser.devider'];
              $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.($int_element + 1)] = $str_tsType;
              foreach((array) $conf_oneDim_view as $key_oneDim => $value_oneDim)
              {
                // Array with all matched ts elements
                $pos = strpos($key_oneDim, $key_TEXT.'.browser.devider.');
                if(($pos === 0))
                {
                  $curr_key = substr($key_oneDim, strlen($key_TEXT.'.browser.devider.'));
                  $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.($int_element + 1).'.'.$curr_key] = $value_oneDim;
                }
                // Array with all matched ts elements
              }
            }
            if(empty($conf_oneDim_view[$key_TEXT.'.browser.devider']))
            {
              #10116
              if(!empty($arr_conf_advanced['sql.']['devider.']['childrenRecords']))
              {
                $str_tsType = $arr_conf_advanced['sql.']['devider.']['childrenRecords'];
                $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.($int_element + 1)] = $str_tsType;
                // 100921, dwildt, Bugfix in t3lib_BEfunc::implodeTSParams
                // See http://bugs.typo3.org/view.php?id=15757 implodeTSParams(): numeric keys will be renumbered
                $arr_tsConf = t3lib_BEfunc::implodeTSParams($arr_conf_advanced['sql.']['devider.']['childrenRecords.']);
                foreach((array) $arr_tsConf as $key_ts => $value_ts)
                {
                  $arr_new_ts_code[$conf_view_path.$key_TEXT.'.'.($int_element + 1).'.'.$key_ts] = $value_ts;
                  // Array with all matched ts elements
                }
              }
            }
          }
          // Add the devider
          // TEXT array
        }
        // Loop: Each TEXT array
        // COA array
      }
      // Substitute marker

      // Add renewed code to the ts of current view
      if($bool_handle)
      {
        if(!empty($arr_new_ts_code))
        {
          $conf_oneDim  = $conf_oneDim + $arr_new_ts_code;
          $conf         = $this->pObj->objTyposcript->oneDim_to_tree($conf_oneDim);
        }
        if(empty($arr_new_ts_code))
        {
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] manipulate_tsConf(): There isn\'t any code to handle!', $this->pObj->extKey, 0);
          }
        }
      }
      // Add renewed code to the ts of current view
    }
    // Loop: Each TEXT ts conf with marker for foreign tables

    $this->pObj->conf = $conf;

    return;
  }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_consolidate.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_consolidate.php']);
}

?>