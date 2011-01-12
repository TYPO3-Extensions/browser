<?php
 /***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 - 2010 Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_views bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_browser
 * @version 3.6.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_browser_pi1_views
 *   69:     function __construct($parentObj)
 *
 *              SECTION: Building the views
 *   99:     function listView($template)
 * 1065:     function singleView($template)
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_views
{


  var $arr_select;
  // Array with the fields of the SQL result
  var $arr_orderBy;
  // Array with fields from orderBy from TS
  var $arr_rmFields;
  // Array with fields from functions.clean_up.csvTableFields from TS



   /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }











  /***********************************************
   *
   * Building the views
   *
   **********************************************/




  /**
 * Display a search form, a-z-Browser, pageBrowser and a list of records
 *
 * @param string    $template: Template
 * @return  void
 */
  function listView($template) {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $cObj = $this->pObj->cObj;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];


    /////////////////////////////////////
    //
    // Get the local or global displayList

    if (is_array($conf_view['displayList.'])) {
      $this->pObj->lDisplayList = $conf_view['displayList.'];
    } else {
      $this->pObj->lDisplayList = $conf['displayList.'];
    }
    // Get the local or global displayList


    /////////////////////////////////////
    //
    // Get the local or global displayList.display

    if (is_array($conf_view['displayList.']['display.'])) {
      $this->pObj->lDisplay = $conf_view['displayList.']['display.'];
    } else {
      $this->pObj->lDisplay = $conf['displayList.']['display.'];
    }
    // Get the local or global displayList.display


    /////////////////////////////////
    //
    // Is there a list?

    if (!is_array($conf['views.'][$viewWiDot])) {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/DRS] views.'.$view.' hasn\'t any item.', $this->pObj->extKey, 3);
        t3lib_div::devLog('[HELP/DRS] Did you included the static template from this extensions?', $this->pObj->extKey, 1);
        $tsArray = 'plugin.'.$this->pObj->prefixId.'.views.list';
        t3lib_div::devLog('[HELP/DRS] Did you configure '.$tsArray.'?', $this->pObj->extKey, 1);
      }
      return false;

    }


    /////////////////////////////////////
    //
    // Do we have an existing mode?

    $maxModes = count($conf['views.'][$viewWiDot]);
    if ($mode > $maxModes) $mode = 1;
    // Do we have an existing mode?


    //////////////////////////////////////////////////////////////////////
    //
    // Filter - part I/II: SQL andWhere statement

    // 3.5.0
    $arr_andWhereFilter = $this->pObj->objFilter->andWhere_filter();
    if (!empty($arr_andWhereFilter))
    {
      $this->pObj->arr_andWhereFilter = $arr_andWhereFilter;
    }
    // Filter - part I/II: SQL andWhere statement



    //////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After filter: '.($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////
    //
    // Set global SQL values

    $arr_result = $this->pObj->objSqlFun->global_all();
    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
    // Set global SQL values


    /////////////////////////////////////
    //
    // SQL with manual configuration

    if ($this->pObj->b_sql_manual)
    {
      $arr_result = $this->pObj->objSqlMan->get_query_array($this);
    }
    // SQL with manual configuration


    /////////////////////////////////////
    //
    // SQL with autmatically configuration

    if (!$this->pObj->b_sql_manual)
    {
      $arr_result = $this->pObj->objSqlAut->get_query_array();
    }
    // SQL with autmatically configuration


    /////////////////////////////////////
    //
    // ERROR management

    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
    // ERROR management



    ///////////////////////////////////////////////////////////////////////
    //
    // Store values, maybe we need it later.

    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
    $orderBy  = $arr_result['data']['orderBy'];
    $union    = $arr_result['data']['union'];
    // Store values, maybe we need it later.



    ///////////////////////////////////////////////////////////////////////
    //
    // Set ORDER BY to false - we like to order by PHP

    $orderBy = false;
    #9917: Selecting a random sample from a set of rows
    if($conf_view['random'] == 1)
    {
      $orderBy = 'rand()';
    }
    // Set ORDER BY to false - we like to order by PHP



    //////////////////////////////////////////////////////////////////////
    //
    // Execute the SQL query

    $b_union = false;
    if ($union)
    {
      // We have a UNION. Maybe because there are synonyms.
      $query   = $union;
      $b_union = true;
    }
    if (!$union)
    {
      $query   = $GLOBALS['TYPO3_DB']->SELECTquery($select, $from, $where, $groupBy="", $orderBy, $limit="", $uidIndexField="");
    }

    $res   = $GLOBALS['TYPO3_DB']->sql_query($query);
    $error = $GLOBALS['TYPO3_DB']->sql_error();

    if ($error != '')
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      if ($this->pObj->b_drs_error)
      {
        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
      }
      else
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">'.$this->pObj->pi_getLL('drs_sql_prompt').'</p>';
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn.$str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
    // Execute the SQL query


    ////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] '.$query,  $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1);
    }
    // DRS - Development Reporting System


    //////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After SQL exec: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    //////////////////////////////////////////////////////////////////////
    //
    // Workaround filter and localisation - Bugfix #9024

    /*
     * Description of the bug
     *
     * If we have a localized website
     * and the user has selected a non default language
     * and the user has selected a filter
     * than the query above will select only default language records
     */

    // User selected a non default language
    if($this->pObj->objLocalize->int_localization_mode >= 3)
    {
      // User selected a filter
      if(!empty($this->pObj->arr_andWhereFilter))
      {
        if(!$b_union && !$this->pObj->b_sql_manual)
        {
          $arr_where = null;
          list($table, $field) = explode('.', $this->pObj->arrLocalTable['uid']);
          while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
          {
            $uid          = $row[$table.'.'.$field];
            $arr_where[]  = '('.$table.'.'.$field.' = '.$uid.
                            ' OR '.$table.'.l10n_parent = '.$uid.')';
          }
          $where    = implode(' OR ', $arr_where);
          $where    = '('.$where.')';
          $andWhere = $this->pObj->objLocalize->localizationFields_where($table);
          $where    = $where.' AND '.$andWhere;

          $query = $GLOBALS['TYPO3_DB']->SELECTquery($select, $from, $where, $groupBy="", $orderBy, $limit="", $uidIndexField="");
          $res   = $GLOBALS['TYPO3_DB']->sql_query($query);
          $error = $GLOBALS['TYPO3_DB']->sql_error();

          // DRS - Development Reporting System
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Bugfix #9024 - Next query for localisation consolidation',  $this->pObj->extKey, 0);
            t3lib_div::devlog('[INFO/SQL] '.$query,  $this->pObj->extKey, 0);
            t3lib_div::devlog('[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1);
          }
          // DRS - Development Reporting System
        }
        if($b_union)
        {
          if ($this->pObj->b_drs_error)
          {
            t3lib_div::devlog('[ERROR/FILTER] User has selected a non default
              language. User has selected a filter too. And we have a query
              with UNIONs. It isn\'t possible to display localised records
              proper! We are sorry!',  $this->pObj->extKey, 3);
          }
        }
        if($this->pObj->b_sql_manual)
        {
          if ($this->pObj->b_drs_error)
          {
            t3lib_div::devlog('[ERROR/FILTER] User has selected a non default
              language. User has selected a filter too. And we have a manual
              generated query. It isn\'t possible to display localised records
              proper!',  $this->pObj->extKey, 3);
          }
        }
      }
      // User selected a filter
    }
    // User selected a non default language

    if ($error != '')
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      if ($this->pObj->b_drs_error)
      {
        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
      }
      else
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">'.$this->pObj->pi_getLL('drs_sql_prompt').'</p>';
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn.$str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
    // Workaround filter and localisation - Bugfix #9024



    ////////////////////////////////////
    //
    // Building $rows

    $arr_table_realnames = $conf_view['aliases.']['tables.'];

    // Do we have aliases?
    if (is_array($arr_table_realnames))
    {
      // Yes, we have aliases.
      $i_row = 0;
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        foreach ($row as $str_tablealias_field => $value)
        {
          $arr_tablealias_field = explode('.', $str_tablealias_field);   // table_1.sv_name
          $str_tablealias       = $arr_tablealias_field[0];              // table_1
          $str_field            = $arr_tablealias_field[1];              // sv_name
          $str_table            = $arr_table_realnames[$str_tablealias]; // tx_civserv_service
          $str_table_field      = $str_table.'.'.$str_field;             // tx_civserv_service.sv_name
          if ($str_table_field == '.')
          {
            $str_table_field = $str_tablealias_field;
          }
          $rows[$i_row][$str_table_field] = $row[$str_tablealias_field];
        }
        $i_row++;
      }
      // Yes, we have aliases.
    }
    if (!is_array($arr_table_realnames))
    {
      // No, we don't have any alias.
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        $rows[] = $row;
      }
    }
    $this->pObj->rows = $rows;
    // Do we have aliases?


    ////////////////////////////////////
    //
    // SQL Free Result

    $GLOBALS['TYPO3_DB']->sql_free_result($res);


    //////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After building rows: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    /////////////////////////////////////////////////////////////////
    //
    // Process synonyms if rows have synonyms

    $arr_result = $this->pObj->objSqlFun->rows_with_synonyms($rows);
    $rows       = $arr_result['data']['rows'];
    unset($arr_result);
    $this->pObj->rows = $rows;
    // Process synonyms if rows have synonyms


    //////////////////////////////////////////////////////////////////////
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
      t3lib_div::devLog('[INFO/PERFORMANCE] After synonyms: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    /////////////////////////////////////////////////////////////////
    //
    // Consolidate Localization

    if (!$this->pObj->b_sql_manual)
    {
      $rows = $this->pObj->objLocalize->consolidate_rows($rows, $this->pObj->localTable);
      $this->pObj->rows = $rows;
    }
    if ($this->pObj->b_sql_manual)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/SQL] Manual SQL mode: Rows didn\'t get any localization consolidation.',  $this->pObj->extKey, 0);
      }
    }
    // Consolidate Localization


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After consolidate localization: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance
//var_dump('views 570');


//if(t3lib_div::_GP('dev')) var_dump('views 579', array_keys(current($rows)));
    ///////////////////////////////////////////////////////////////
    //
    // Consolidate rows

    if (!$this->pObj->b_sql_manual)
    {
      $arr_result       = $this->pObj->objConsolidate->consolidate($rows);
      $rows             = $arr_result['data']['rows'];
      $int_rows_wo_cons = $arr_result['data']['rows_wo_cons'];
      $int_rows_wi_cons = $arr_result['data']['rows_wi_cons'];
      unset($arr_result);
      $this->pObj->rows = $rows;
    }
    if ($this->pObj->b_sql_manual)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/SQL] Manual SQL mode: Rows didn\'t get any general consolidation.',  $this->pObj->extKey, 0);
      }
    }
    // Consolidate rows
//if(t3lib_div::_GP('dev')) var_dump('views 601', array_keys(current($rows)));



    /////////////////////////////////////////////////////////////////
    //
    // Ordering the rows

    #9917: Selecting a random sample from a set of rows
    if(!($conf_view['random'] == 1))
    {
      $this->pObj->objMultisort->multisort_rows();
      $rows = $this->pObj->rows;
    }
    // Ordering the rows



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After consolidate rows: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    /////////////////////////////////////////////////////////////////
    //
    // Store amount of rows for the pagebrowser

    // 100429, dwildt: rowsPb isn't set. Debug the code!
    if(isset($rowsPb))
    {
      $rowsPb = false;
    }

    if($rowsPb == false)
    {
      $rowsPb = count($rows);
    }
    // Store amount of rows for the pagebrowser


    /////////////////////////////////////////////////////////////////
    //
    // Order and edit the rows hierarchical

    $b_hierarchical = $conf_view['functions.']['hierarchical'];
    if ($b_hierarchical)
    {
      $rows = $this->pObj->objSqlFun->make_hierarchical($rows);
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] Result should ordered hierarchical.',  $this->pObj->extKey, 0);
      }
    }
    // Order and edit the rows hierarchical


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After ordered hierarchical: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    /////////////////////////////////////////////////////////////////
    //
    // Development Log: Show the first row

    if ($this->pObj->b_drs_sql)
    {
      $str_prompt = 'Result of the first row:<br /><br />';
      if (is_array($rows) && count($rows) > 0)
      {
        reset($rows);
        foreach (current($rows) as $key => $value)
        {
          $str_prompt .= '['.$key.']: '.htmlspecialchars($value).'<br />';
        }
        t3lib_div::devlog('[INFO/SQL] '.$str_prompt, $this->pObj->extKey, 0);
      }
    }
    // Development Log: Show the first row



    ////////////////////////////////////////////////////////////////////////
    //
    // Filter - part II/II - HTML code / template

    $this->pObj->objFilter->rows_wo_limit = $rows;
//if(t3lib_div::_GP('dev')) var_dump('views 712', $rows);
    $arr_result = $this->pObj->objFilter->filter($template);
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass($prompt);
    }
    $template = $arr_result['data']['template'];
    unset($arr_result);
    // Filter - part II/II - HTML code / template



    /////////////////////////////////////////////////////////////////
    //
    // Clean up: Delete fields, we don't want to display

    $arr_result = $this->pObj->objSqlFun->rows_with_cleaned_up_fields($rows);
    $rows       = $arr_result['data']['rows'];
    unset($arr_result);
    // Clean up: Delete rows, we don't want to display



    /////////////////////////////////////////////////////////////////
    //
    // Development Log: Show the first row

    if ($this->pObj->b_drs_sql)
    {
      $str_prompt = 'Result of the first row:<br /><br />';
      if (is_array($rows) && count($rows) > 0)
      {
        reset($rows);
        foreach (current($rows) as $key => $value)
        {
          $str_prompt .= '['.$key.']: '.htmlspecialchars($value).'<br />';
        }
        t3lib_div::devlog('[INFO/SQL] '.$str_prompt, $this->pObj->extKey, 0);
      }
    }
    // Development Log: Show the first row



      //////////////////////////////////////////////////////////////////////////
      //
      // Hook for override the SQL result for for the list view

      // This hook is used by one extension at least
    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']))
    {
        // DRS - Development Reporting System
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_browser)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
        $arr_ext      = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list']);
        $csv_ext      = implode(',', $arr_ext);
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/SQL] The third party extensions '.$csv_ext.' use the HOOK browser_list.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
        else
        {
          t3lib_div::devlog('[INFO/SQL] The third party extension '.$csv_ext.' uses the HOOK browser_list.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1);
        }
      }
        // DRS - Development Reporting System

      //:todo: Proper Hook
//      foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_list'] as $_classRef)
//      {
//        $_procObj   = &t3lib_div::getUserObj($_classRef);
//        $this->pObj = $_procObj->browser_list($arr_data, $this);
//      }
    }
      // Hook for override the SQL result for for the list view


    ////////////////////////////////////////////////////////////////////////
    //
    // Prepaire array with links to single view

    $csvLinkToSingle = $conf_view['csvLinkToSingleView'];
    if (!$csvLinkToSingle)
    {
      $csvLinkToSingle = $conf_view['select'];
      $csvLinkToSingle = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($csvLinkToSingle);
      // Is there a statement, which should replaced with an alias?
      if (is_array($conf_view['select.']['deal_as_table.']))
      {
        foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
        {
          $csvLinkToSingle = str_replace($arr_dealastable['statement'], $arr_dealastable['alias'], $csvLinkToSingle);
          if ($this->pObj->b_drs_sql)
          {
            t3lib_div::devlog('[INFO/SQL] Used tables: Statement "'.$arr_dealastable['statement'].'" is replaced with "'.$arr_dealastable['alias'].'"', $this->pObj->extKey, 0);
          }
        }
      }
    }
    if (!$csvLinkToSingle)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/DRS] views.'.$viewWiDot.$mode.' hasn\'t any linkToSingleView.', $this->pObj->extKey, 0);
        t3lib_div::devLog('[HELP/DRS] If you want a link to a single view, please configure views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
      }
    }
    $arrLinkToSingleFields = explode(',', $csvLinkToSingle);
    $this->pObj->arrLinkToSingle = array();
    foreach($arrLinkToSingleFields as $arrLinkToSingleField)
    {
      list($table, $field) = explode('.', trim($arrLinkToSingleField));
      $this->pObj->arrLinkToSingle[] = $table.'.'.$field;
    }

    // Replace aliases in case of aliases
    if (is_array($conf_view['aliases.']['tables.']))
    {
      foreach ($this->pObj->arrLinkToSingle as $i_key => $str_tablefield)
      {
        $this->pObj->arrLinkToSingle[$i_key] = $this->pObj->objSqlFun->get_sql_alias_before($str_tablefield);
      }
      $this->pObj->arrLinkToSingle = $this->pObj->objSqlFun->replace_tablealias($this->pObj->arrLinkToSingle);
    }
    // Replace aliases in case of aliases

    $str_csvList = implode(', ', $this->pObj->arrLinkToSingle);
    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/DRS] Fields which will get a link to a single view: '.$str_csvList.'.', $this->pObj->extKey, 0);
      t3lib_div::devLog('[HELP/DRS] If you want to configure the field list, please use views.'.$viewWiDot.$mode.'.csvLinkToSingleView.', $this->pObj->extKey, 1);
    }
    // Prepaire array with links to single view


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After link to single view: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////
    //
    // Building the template

    // HTML template
    $str_marker = $this->pObj->lDisplayList['templateMarker'];
    $template   = $this->pObj->cObj->getSubpart($template, $str_marker);
    // HTML template

    // HTML search form
    // #9659, 101011, fsander
    //$bool_display = $this->pObj->objConfig->bool_searchForm;
    $bool_display = $this->pObj->objConfig->bool_searchForm && $this->pObj->segment['searchform'];
    $template     = $this->pObj->objTemplate->tmplSearchBox($template, $bool_display);
    // HTML search form

    // HTML a-z-browser
    $arr_data['template']       = $template;
    $arr_data['rows']           = $rows;
    $arr_result = $this->pObj->objNavi->azBrowser($arr_data);
    if ($arr_result['error']['status'])
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->pObj->pi_wrapInBaseClass($prompt);
    }

    $lArrTabs = $arr_result['data']['azTabArray'];
    $arr_tsId = $arr_result['data']['tabIds'];
    $template = $arr_result['data']['template'];
    $rows     = $arr_result['data']['rows'];
    unset($arr_result);
    // HTML a-z-browser



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After a-z browser: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance

    // HTML page browser
    $arr_data['azTabArray'] = $lArrTabs;
    $arr_data['tabIds']     = $arr_tsId;
    $arr_data['template']   = $template;
    $arr_data['rows']       = $rows;

    $arr_result = $this->pObj->objNavi->tmplPageBrowser($arr_data);
    unset($arr_data);
    $template             = $arr_result['data']['template'];
    $rows                 = $arr_result['data']['rows'];
    unset($arr_result);
    // HTML page browser

    // HTML mode selector
    $arr_data['template']     = $template;
    $arr_data['arrModeItems'] = $this->pObj->arrModeItems;
    $template = $this->pObj->objNavi->tmplModeSelector($arr_data);
    unset($arr_data);
    // HTML mode selector
    // Building the template


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After pagebrowser and mode selector: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    ///////////////////////////////////////////////
    //
    // In case of limit, limit the rows

    if(isset($conf_view['limit']))
    {
      $arr_limit  = explode(',', $conf_view['limit']);
      $int_start  = (int) trim($arr_limit[0]);
      $int_amount = (int) trim($arr_limit[1]);
      $int_counter = 0;
      $int_remove_start = $int_start;
      $int_remove_end   = $int_start + $int_amount;
      $drs_rows_before  = count($rows);
      if (is_array($rows))
      {
        foreach ($rows as $row => $elements)
        {
          if ($int_counter < $int_remove_start || $int_counter >= $int_remove_end)
          {
            unset($rows[$row]);
          }
          $int_counter++;
        }
      }
      $drs_rows_after = count($rows);

      // DRS - Development Reporting System
      if ($drs_rows_after != $drs_rows_before)
      {
        $removed_rows = $drs_rows_before - $drs_rows_after;
        if ($this->pObj->b_drs_templating)
        {
          t3lib_div::devlog('[INFO/TEMPLATING] We have a limit: '.$conf_view['limit'].'<br />'.
            ' #'.$removed_rows.' rows were removed.',  $this->pObj->extKey, 0);
        }
      }
      // DRS - Development Reporting System
    }
    if(!isset($conf_view['limit']))
    {
      if ($this->pObj->b_drs_templating)
      {
        t3lib_div::devlog('[INFO/TEMPLATING] We don\'t have any limit.<br />'.
          ' #0 rows were removed. We have #'.count($rows).' rows.',  $this->pObj->extKey, 0);
      }
    }
    // In case of limit, limit the rows


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
      t3lib_div::devLog('[INFO/PERFORMANCE] Before generating template: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    // HTML records
    $template = $this->pObj->objTemplate->tmplListview($template, $rows);



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After generating template: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    return $template;

  }




  /**
 * Display a single item from the database
 *
 * @param string    $template: HTML template with TYPO3 subparts and markers
 * @return  void
 */
  function singleView($template) {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $cObj = $this->pObj->cObj;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];


    /////////////////////////////////////
    //
    // Do we have configured views?

    $maxModes = count($conf['views.'][$viewWiDot]);
    if (!$maxModes || $maxModes == 0)
    {
      if ($this->pObj->b_drs_error) {
        t3lib_div::devlog('[ERROR/DRS] There is no '.$view.' view.', $this->pObj->extKey, 3);
        t3lib_div::devLog('[HELP/DRS] Did you included the static template from this extensions?', $this->pObj->extKey, 1);
        $tsArray = 'plugin.'.$this->pObj->prefixId.'.views.'.$view;
        t3lib_div::devLog('[HELP/DRS] Did you configure '.$tsArray.'?', $this->pObj->extKey, 1);
        t3lib_div::devLog('[WARN/DRS] ABORTED', $this->pObj->extKey, 2);
      }
      return false;
    }
    // Do we have configured views?


    /////////////////////////////////////
    //
    // Do we have an existing mode?

    if (!$maxModes || $maxModes == 0)
    {
      exit;
    }
    if ($mode > $maxModes)
    {
      $mode = 1;
    }
    // Do we have an existing mode?


    /////////////////////////////////////
    //
    // Get the local or global displaySingle

    if (is_array($conf_view['displaySingle.']))
    {
      $this->pObj->lDisplaySingle = $conf_view['displaySingle.'];
    }
    else
    {
      $this->pObj->lDisplaySingle = $this->pObj->conf['displaySingle.'];
    }
    // Get the local or global displaySingle


    /////////////////////////////////////
    //
    // Get the local or global displaySingle.display

    if (is_array($conf_view['displaySingle.']['display.']))
    {
      $this->pObj->lDisplay = $conf_view['displaySingle.']['display.'];
    }
    else
    {
      $this->pObj->lDisplay = $conf['displaySingle.']['display.'];
    }
    // Get the local or global displaySingle.display


    /////////////////////////////////////
    //
    // Set global SQL values

    $arr_result = $this->pObj->objSqlFun->global_all();
    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
    // Set global SQL values


    /////////////////////////////////////
    //
    // SQL with manual configuration or autmatically configuration

    if ($this->pObj->b_sql_manual)
    {
      $arr_result = $this->pObj->objSqlMan->get_query_array($this);
      // Process the query building in case of a manual configuration with SELECT, FROM and WHERE and maybe JOINS
    }

    if (!$this->pObj->b_sql_manual)
    {
      // We don't have a manual configuration
      $arr_result = $this->pObj->objSqlAut->get_query_array();
      // Process the query building automatically
    }
    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $template;
    }
    // SQL with manual configuration or autmatically configuration


    $select   = $arr_result['data']['select'];
    $from     = $arr_result['data']['from'];
    $where    = $arr_result['data']['where'];
    $orderBy  = $arr_result['data']['orderBy'];
    unset($arr_result);


    /////////////////////////////////////
    //
    // Process SQL query: Get the record(s)

    $groupBy  = '';
    $orderBy  = '';
    $limit    = '';
    $query    = $GLOBALS['TYPO3_DB']->SELECTquery       ($select, $from, $where, $groupBy, $orderBy, $limit, $uidIndexField="");

    $res   = $GLOBALS['TYPO3_DB']->sql_query($query);
    $error = $GLOBALS['TYPO3_DB']->sql_error();

    if ($error != '')
    {
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
        t3lib_div::devlog('[ERROR/SQL] ABORT.',   $this->pObj->extKey, 3);
      }
      $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
      if ($this->pObj->b_drs_error)
      {
        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
      }
      else
      {
        $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">'.$this->pObj->pi_getLL('drs_sql_prompt').'</p>';
      }
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_warn.$str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }

    if ($this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/SQL] '.$query,  $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1);
    }
    // Process SQL query: Get the record(s)


    ////////////////////////////////////
    //
    // Building $rows

    $arr_table_realnames = $conf_view['aliases.']['tables.'];

    // Do we have aliases?
    if (is_array($arr_table_realnames))
    {
      // Yes, we have aliases.
      $i_row = 0;
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        foreach ($row as $str_tablealias_field => $value)
        {
          $arr_tablealias_field = explode('.', $str_tablealias_field);   // table_1.sv_name
          $str_tablealias       = $arr_tablealias_field[0];              // table_1
          $str_field            = $arr_tablealias_field[1];              // sv_name
          $str_table            = $arr_table_realnames[$str_tablealias]; // tx_civserv_service
          $str_table_field      = $str_table.'.'.$str_field;             // tx_civserv_service.sv_name
          if ($str_table_field == '.')
          {
            $str_table_field = $str_tablealias_field;
          }
          $rows[$i_row][$str_table_field] = $row[$str_tablealias_field];
        }
        $i_row++;
      }
      // Yes, we have aliases.
    }
    else
    {
      // No, we don't have any alias.
      while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))
      {
        $rows[] = $row;
      }
    }
    // Do we have aliases?


    ////////////////////////////////////
    //
    // SQL Free Result

    $GLOBALS['TYPO3_DB']->sql_free_result($res);
    // SQL Free Result


    /////////////////////////////////////////////////////////////////
    //
    // Process synonyms if rows have synonyms

    $arr_result = $this->pObj->objSqlFun->rows_with_synonyms($rows);
    $rows       = $arr_result['data']['rows'];
    unset($arr_result);
    // Process synonyms if rows have synonyms


    /////////////////////////////////////////////////////////////////
    //
    // Consolidate Localization

    $rows = $this->pObj->objLocalize->consolidate_rows($rows, $this->pObj->localTable);
    $this->pObj->rows = $rows;
    // Consolidate Localization


    ///////////////////////////////////////////////////////////////
    //
    // Consolidate rows

    // 100429, dwildt - Bugfixing: Consolidate rows was missing upto 3.2.2
    if (!$this->pObj->b_sql_manual)
    {
      $arr_result       = $this->pObj->objConsolidate->consolidate($rows);
      $rows             = $arr_result['data']['rows'];
      $int_rows_wo_cons = $arr_result['data']['rows_wo_cons'];
      $int_rows_wi_cons = $arr_result['data']['rows_wi_cons'];
      unset($arr_result);
      $this->pObj->rows = $rows;
    }
    if ($this->pObj->b_sql_manual)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/SQL] Manual SQL mode: Rows didn\'t get any general consolidation.',  $this->pObj->extKey, 0);
      }
    }
    // Consolidate rows



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After consolidate rows: '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////////////////////////////////
    //
    // #9727: Ordering the children

    $this->pObj->objMultisort->multisort_mm_children();
    $rows = $this->pObj->rows;
    // #9727: Ordering the children



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After multisort_mm_children(): '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance



    /////////////////////////////////////////////////////////////////
    //
    // #9838: Simplified relation building

    $this->pObj->objConsolidate->children_relation();
    $rows = $this->pObj->rows;
    // #9838: Simplified relation building



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
      t3lib_div::devLog('[INFO/PERFORMANCE] After children_relation(): '. ($endTime - $this->pObj->startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    /////////////////////////////////////
    //
    // Hook for override the SQL result for for the single view

    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']))
    {
      // This hook is used by one extension at least
      if ($this->pObj->b_drs_sql || $this->pObj->b_drs_browser)
      {
        $i_extensions = count($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']);
        $arr_ext     = array_values($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single']);
        $csv_ext     = implode(',', $arr_ext);
        if ($i_extensions > 1)
        {
          t3lib_div::devlog('[INFO/SQL] The third party extensions '.$csv_ext.' use the HOOK browser_single.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1);
        }
        else
        {
          t3lib_div::devlog('[INFO/SQL] The third party extension '.$csv_ext.' uses the HOOK browser_single.', $this->pObj->extKey, -1);
          t3lib_div::devlog('[HELP/SQL] In case of errors or strange behaviour please check this extenion!', $this->pObj->extKey, 1);
        }
      }
// :todo: Proper Hook
//      foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['browser']['browser_single'] as $_classRef)
//      {
//        $_procObj   = &t3lib_div::getUserObj($_classRef);
//        $this       = $_procObj->browser_single($arr_data, $this);
//      }
    }
    // End of Hook


    /////////////////////////////////////
    //
    // DRS - Development Reporting System

    $bool_displayFirstRow = false;
    if (count($rows) == 0)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[WARN/SQL] Result is 0 rows! But query is OK.', $this->pObj->extKey, 2);
      }
    }
    if (!$rows)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[WARN/SQL] Result is 0 rows (false)! But query is OK.', $this->pObj->extKey, 2);
      }
    }
    if (count($rows) == 1 && $rows)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SQL] Result: 1 record.', $this->pObj->extKey, 0);
        $bool_displayFirstRow = true;
      }
    }
    if (count($rows) > 1)
    {
      if ($this->pObj->b_drs_sql)
      {
        t3lib_div::devlog('[INFO/SQL] '.$query, $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/SQL] Result: '.count($rows).' records.<br />You must have 1:n relations.', $this->pObj->extKey, 0);
        $bool_displayFirstRow = true;
      }
    }
    if ($bool_displayFirstRow && is_array($rows))
    {
      t3lib_div::devlog('[INFO/SQL] Result of the row is:', $this->pObj->extKey, 0);
      reset($rows);
      $firstKey = key($rows);
      foreach ($rows[$firstKey] as $key => $value)
      {
        $value = htmlspecialchars($value);
        if (strlen($value) > $this->pObj->i_drs_max_sql_result_len)
        {
          $value = substr($value, 0, $this->pObj->i_drs_max_sql_result_len).' ...';
        }
        t3lib_div::devlog('[INFO/SQL] ['.$key.']: '.$value, $this->pObj->extKey, 0);
      }
    }
    // DRS - Development Reporting System


    /////////////////////////////////////
    //
    // Building the template

    // It is possible to process one subpart only!
    // If you want to process more than one subpart, the included subparts and markes have to be unique
    // in the template file - in the HTML file. And the code in this class has to be modified.
    $str_marker = $this->pObj->lDisplaySingle['templateMarker'];
    $template   = $this->pObj->cObj->getSubpart($template, $str_marker);

    $this->pObj->lDisplayType = 'displaySingle.';
    if (is_array($conf['views.'][$viewWiDot][$mode.'.'][$this->pObj->lDisplayType]['display.'])) {
      $this->pObj->lDisplay = $conf['views.'][$viewWiDot][$mode.'.'][$this->pObj->lDisplayType]['display.'];
    } else {
      $this->pObj->lDisplay = $conf[$this->pObj->lDisplayType]['display.'];
    }

    // HTML mode selector
    $arr_data['template']     = $template;
    $arr_data['arrModeItems'] = $this->pObj->arrModeItems;
    $template = $this->pObj->objNavi->tmplModeSelector($arr_data);
    unset($arr_data);
    // Building the template


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // Do we have a HTML template with markers or a Typoscript Template Container (TTC)?

    $b_ttc = false;
    // dwildt, 101012
    if(is_array($conf['views.'][$viewWiDot][$mode.'.']))
    {
      foreach ($conf['views.'][$viewWiDot][$mode.'.'] as $ts_key => $ts_value)
      {
        if ($ts_value == 'TT_CONTAINER')
        {
          $b_ttc = true;
          break;
        }
      }
    }

    // Typoscript Template Container (TTC)
    if ($b_ttc)
    {
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] We have one TT_CONTAINER at least.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We don\'t process the default TypoScript Template Marker.', $this->pObj->extKey, 0);
      }
      $arr_result = $this->pObj->objTTContainer->main($rows);
      if ($arr_result['error']['status'])
      {
        $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
        return $this->pObj->pi_wrapInBaseClass($prompt);
      }
      $template = $arr_result['data']['template'];
      unset($arr_result);
    }
    // Typoscript Template Container (TTC)

    // HTML Template with markers
    if (!$b_ttc)
    {
      // We have an old style template with HTML and markers
      if ($this->pObj->b_drs_ttc)
      {
        t3lib_div::devlog('[INFO/TTC] We don\'t have any TT_CONTAINER.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We don\'t process the TypoScript Template Container (TTC).', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/TTC] We process the default TypoScript Template Marker.', $this->pObj->extKey, 0);
      }
      $template = $this->pObj->objTemplate->tmplSingleview($template, $rows);
    }
    // HTML Template with markers
    // Do we have a HTML template with markers or a Typoscript Template Container (TTC)?


    return $template;

  }












}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_views.php']);
}

?>