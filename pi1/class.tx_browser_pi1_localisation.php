<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 - 2011 Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_localisation bundles methods for localisation for the extension browser
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.3
* @since 2.0.0
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   61: class tx_browser_pi1_localisation
 *  111:     function __construct($parentObj)
 *
 *              SECTION: SQL query parts
 *  165:     function localisationFields_select($table)
 *  403:     function localisationFields_where($table)
 *  506:     function localisationSingle_where($table)
 *
 *              SECTION: Configuring Localisation
 *  649:     function localisationConfig()
 *
 *              SECTION: Consolidation
 *  729:     function consolidate_filter($rows)
 *  901:     function consolidate_rows($rows, $table)
 *
 *              SECTION: Little Helpers
 * 1537:     function init_typoscript()
 * 1591:     function propper_locArray($arr_langFields, $table)
 *
 * TOTAL FUNCTIONS: 9
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_localisation
{


  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = FALSE;
  // [Array] The current TypoScript configuration array
  var $mode       = FALSE;
  // [Integer] The current mode (from modeselector)
  var $view       = FALSE;
  // [String] 'list' or 'single': The current view
  var $conf_view  = FALSE;
  // [Array] The TypoScript configuration array of the current view
  var $conf_path  = FALSE;
  // [String] TypoScript path to the current view. I.e. views.single.1
  // Variables set by the pObj (by class.tx_browser_pi1.php)


  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $int_localisation_mode;
  // [Integer] See defines in the contructor. Set by localisationConfig().
  var $lang_id;
  // [Integer] $GLOBALS['TSFE']->sys_language_content. Set by localisationConfig().
  var $overlay_mode;
  // [String] $GLOBALS['TSFE']->sys_language_contentOL. Set by localisationConfig().
  var $conf_localisation      = FALSE;
  // [Array] The The current TypoScript configuration array local or global: advanced.localisation
  var $conf_localisation_path = FALSE;
  // [String] The The current TypoScript configuration path local or global: advanced.localisation









/**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;

    define('PI1_ANY_LANGUAGE',                  0);
    define('PI1_DEFAULT_LANGUAGE',              1);
    define('PI1_DEFAULT_LANGUAGE_ONLY',         2);
    define('PI1_SELECTED_OR_DEFAULT_LANGUAGE',  3);
    define('PI1_SELECTED_LANGUAGE_ONLY',        4);
    // See method localisationConfig()
    // See class.tx_browser_pi1_views.php: Workaround filter and localisation - Bugfix #9024

  }















  /***********************************************
  *
  * SQL query parts
  *
  **********************************************/




  /**
 * Returns different SELECT statements with the localisation or overlay fields from the current table.
 * Localisation field i.e.: tt_news.sys_language_content
 * Overlay field i.e.:      tt_news_cat.title_lang_ol
 * Result depends on localisation mode and on the TCA.
 * woAlias:     and SELECT statement with syntax table.field, table,field
 * filter:      and SELECT statement with syntax table.field AS `table.field`, table,field  AS `table.field`
 * wiAlias:     same as filter
 * addedFields: array with added fields in the syntax table.field
 *
 * Example for woAlias: tx_bzdstaffdirectory_groups.sys_language_uid, tx_bzdstaffdirectory_groups.l18n_parent
 *
 * The mothod supports languageField and transOrigPointerField only.
 *
 * @param	string		$table: Name of the table in the TYPO3 database / in TCA
 * @return	array		$arr_andSelect with elements woAlias, filter, wiAlias and addedFields
 */
  function localisationFields_select($table)
  {

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Load the TCA, if we don't have an table.columns array

    if (!is_array($GLOBALS['TCA'][$table]['columns']))
    {
      t3lib_div::loadTCA($table);
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] $GLOBALS[\'TCA\'][\''.$table.'\'] is loaded.', $this->pObj->extKey, 0);
      }
    }
    // Load the TCA, if we don't have an table.columns array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Do we need translated/localised records?

    $bool_dontLocalise = FALSE;
    if(!isset($this->int_localisation_mode))
    {
      $this->int_localisation_mode = $this->localisationConfig();
    }
    if($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      $bool_dontLocalise = TRUE;
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Localisation mode is PI1_DEFAULT_LANGUAGE. There isn\' any need to localise!', $this->pObj->extKey, 0);
      }
    }
    if($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE_ONLY)
    {
      $bool_dontLocalise = TRUE;
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Localisation mode is PI1_DEFAULT_LANGUAGE_ONLY. There isn\' any need to localise!', $this->pObj->extKey, 0);
      }
    }
    // Do we need translated/localised records?


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get the field names for sys_language_content and for l10n_parent

    $arr_localise['id_field']   = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
    $arr_localise['pid_field']  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
    // Get the field names for sys_language_content and for l10n_parent


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Do we have a localised table?

    $bool_tableIsLocalised = FALSE;
    if ($arr_localise['id_field'] && $arr_localise['pid_field'])
    {
      $bool_tableIsLocalised = TRUE;
    }
    if($bool_tableIsLocalised and $bool_dontLocalise)
    {
      $bool_tableIsLocalised = FALSE;
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$table.' is localised. But we ignore it!', $this->pObj->extKey, 0);
      }
    }
    if ($bool_tableIsLocalised)
    {
      $this->pObj->arr_realTables_localised[] = $table;
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'\' is localised.', $this->pObj->extKey, 0);
      }
    }
    if (!$bool_tableIsLocalised)
    {
      $this->pObj->arr_realTables_notLocalised[] = $table;
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'\' isn\'t localised.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/LOCALISATION] Localisation isn\'t needed.', $this->pObj->extKey, 0);
      }
    }
    // Do we have a localised table?


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Do we have translated fields in case of a not localised table?

    if (!$bool_tableIsLocalised and !$bool_dontLocalise)
    {
      $bool_fieldIsLocalised = FALSE;
      $conf_tca = $this->conf_localisation['TCA.'];
//var_dump('localisation 229', $conf_tca, $table, $this->pObj->arr_realTables_arrFields[$table]);
      // Loop through the array with all used tableFields
      if(is_array($this->pObj->arr_realTables_arrFields[$table]))
      {
        foreach ($this->pObj->arr_realTables_arrFields[$table] as $str_field)
        {
          $str_field_lang_ol = $str_field.$conf_tca['field.']['appendix'];
          // Has the table a field for tranlation (syntax i.e.: field_lang_ol)?
          if (is_array($GLOBALS['TCA'][$table]['columns'][$str_field_lang_ol]))
          {
            if ($this->pObj->b_drs_locallang)
            {
              t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'.'.$str_field.'\' is translated in '.$str_field_lang_ol.'.', $this->pObj->extKey, 0);
            }
            $arr_lang_ol[] = $table.'.'.$str_field_lang_ol;
            $this->pObj->arr_realTables_arrFields[$table][]   = $str_field_lang_ol;
            $this->pObj->arrConsolidate['addedTableFields'][] = $table.'.'.$str_field_lang_ol;
            $arr_tables['woAlias'][]                          = $table.'.'.$str_field_lang_ol;
            $arr_tables['filter'][]                           = $table.'.'.$str_field_lang_ol." AS `table.".$str_field_lang_ol."`";
            $arr_tables['filter'][]                           = "'".intval($this->lang_id)."' AS `table." . $arr_localise['id_field'] . "`, ".
            $arr_tables['wiAlias'][]                          = $table.'.'.$str_field_lang_ol." AS `".$table.'.'.$str_field_lang_ol."`";
              // 13573, 110303, dwildt
            $bool_fieldIsLocalised = TRUE;
          }
          // Has the table a field for tranlation (syntax i.e.: field_lang_ol)?
        }
      }
      // Loop through the array with all used tableFields

      if (!$bool_fieldIsLocalised)
      {
        if ($this->pObj->b_drs_locallang)
        {
          t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'\' hasn\'t any field with appendix '.$conf_tca['field.']['appendix'].'.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/LOCALISATION] Overlay isn\'t needed.', $this->pObj->extKey, 0);
        }
        return FALSE;
      }
    }
    // Do we have translated fields in case of a not localised table?


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Clean up the array

    $arr_localise = $this->propper_locArray($arr_localise, $table);
    // Clean up the array

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Building AND SELECT

    $str_dummyFilter = "'".intval($this->lang_id)."' AS `table." . $arr_localise['id_field'] . "`, ".
      "'' AS `table." . $arr_localise['pid_field'] . "` ";  // 13573, 110303, dwildt
    // The user can use more than one filter. If he uses more than one filter, it will be built a UNION SELECT
    // query. So every SELECT statement should have the same amount of fields. We need the dummy filter,
    // because it is possible that one filter is a field from a localised table and another filter isn't a
    // field from a localised table.

    $arr_andSelect['woAlias'] = FALSE;  // Default
    // Without Alias. I.e.: tx_bzdstaffdirectory_groups.sys_language_uid, tx_bzdstaffdirectory_groups.l18n_parent
//BUGFIX 091112
//    $arr_andSelect['filter']  = $str_dummyFilter;
    $arr_andSelect['filter']  = "'0' AS `table.title_lang_ol`, ".$str_dummyFilter;
    // Filter. I.e.:        tx_bzdstaffdirectory_groups.sys_language_uid AS `table.sys_language_uid`, tx_bzdsta...
    $arr_andSelect['wiAlias'] = FALSE;  // Default
    // With Alias. I.e.:    tx_bzdstaffdirectory_groups.sys_language_uid AS `tx_bzdstaffdirectory_groups.sys_la...
    $arr_andSelect['addedFields'] = FALSE;          // Default
//    $arr_andSelect['addedFields'][] = 'table.l10n_parent';          // Default
//    $arr_andSelect['addedFields'][] = 'table.sys_language_content'; // Default

    // Case is PI1_DEFAULT_LANGUAGE
    if ($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      // Nothing to do. Take the default values.
    }
    // Case is PI1_DEFAULT_LANGUAGE

    // Case is PI1_SELECTED_OR_DEFAULT_LANGUAGE
    if ($this->int_localisation_mode == PI1_SELECTED_OR_DEFAULT_LANGUAGE)
    {
      if (is_array($arr_localise))
      {
        foreach ($arr_localise as $tableField)
        {
          list($table, $field) = explode('.', $tableField);
          $arr_tables['woAlias'][] = $tableField;
          $arr_tables['filter'][]  = $tableField." AS `table.".$field."`";
          $arr_tables['wiAlias'][] = $tableField." AS `".$tableField."`";
        }
//BUGFIX 091112
        $arr_tables['filter'][]  = "'0' AS `table.title_lang_ol`";
      }
    }
    // Case is PI1_SELECTED_OR_DEFAULT_LANGUAGE

    // Case is PI1_SELECTED_LANGUAGE_ONLY
    if ($this->int_localisation_mode == PI1_SELECTED_LANGUAGE_ONLY)
    {
      // Nothing to do. Take the default values.
    }
    // Case is PI1_SELECTED_LANGUAGE_ONLY

    // Extend the SELECT query if we have fields for localisation or overlay
    if (is_array($arr_tables))
    {
      $arr_andSelect['woAlias']     = implode(', ', $arr_tables['woAlias']);
      $arr_andSelect['filter']      = implode(', ', $arr_tables['filter']);
      $arr_andSelect['wiAlias']     = implode(', ', $arr_tables['wiAlias']);
      $arr_andSelect['addedFields'] = $arr_tables['woAlias'];
      // These andWhere needs a consolidation
    }
    // Extend the SELECT query if we have fields for localisation or overlay
    // Building AND SELECT

    return $arr_andSelect;
  }












 /**
  * Returns an AND WHERE statement with the localisation fields from the current table,
  * Result depends on the localisation mode and on TCA.
  *
  * The mothod supports languageField and transOrigPointerField only.
  *
  * @param	string		$table: Name of the table in the TYPO3 database / in TCA
  * @return	string		$str_addSelect: An add select string
  */
  function localisationFields_where($table)
  {

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Load the TCA, if we don't have an table.columns array

    if (!is_array($GLOBALS['TCA'][$table]['columns']))
    {
      t3lib_div::loadTCA($table);
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] $GLOBALS[\'TCA\'][\''.$table.'\'] is loaded.', $this->pObj->extKey, 0);
      }
    }
    // Load the TCA, if we don't have an table.columns array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get the field names for sys_language_content and for l10n_parent

    $arr_localise['id_field']   = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
    $arr_localise['pid_field']  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
    // Get the field names for sys_language_content and for l10n_parent


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Clean up the array

    $arr_localise = $this->propper_locArray($arr_localise, $table);
    // Clean up the array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get the localisation configuration

    $this->int_localisation_mode = $this->localisationConfig();
    // Get the localisation configuration


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return, if we don't have localisation fields

    if (!$arr_localise)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] There isn\'t any localised field.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/LOCALISATION] A localised AND WHERE isn\'t needed.', $this->pObj->extKey, 0);
      }
      return FALSE;
    }
    // Return, if we don't have localisation fields


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Building AND WHERE

    if ($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      $str_andWhere = $arr_localise['id_field']." <= 0 ";
    }
    if ($this->int_localisation_mode == PI1_SELECTED_OR_DEFAULT_LANGUAGE)
    {
      $str_andWhere = "( ".$arr_localise['id_field']." <= 0 OR ".$arr_localise['id_field']." = ".intval($this->lang_id)." ) ";
      // These andWhere needs a consolidation
    }
    if ($this->int_localisation_mode == PI1_SELECTED_LANGUAGE_ONLY)
    {
      $str_andWhere = $arr_localise['id_field']." = ".intval($this->lang_id)." ";
    }
    // Building AND WHERE


    return $str_andWhere;
  }















 /**
  * Returns an AND WHERE statement either 'AND table.uid = showuid' or 'AND (table.uid = showuid OR table.l18n_parent = showuid)'
  * Result depends on the localisation mode and on TCA.
  *
  * @param	string		$table: Name of the table in the TYPO3 database / in TCA
  * @return	string		$str_andWhere: An andWhere statement
  */
  function localisationSingle_where($table)
  {

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Load the TCA, if we don't have an table.columns array

    if (!is_array($GLOBALS['TCA'][$table]['columns']))
    {
      t3lib_div::loadTCA($table);
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] $GLOBALS[\'TCA\'][\''.$table.'\'] is loaded.', $this->pObj->extKey, 0);
      }
    }
    // Load the TCA, if we don't have an table.columns array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get the field names for for l10n_parent

    $arr_localise['pid_field']  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
    // Get the field names for for l10n_parent


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Clean up the array

    $arr_localise = $this->propper_locArray($arr_localise, $table);
    // Clean up the array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get the localisation configuration

    if ($this->int_localisation_mode === FALSE)
    {
      $this->int_localisation_mode = $this->localisationConfig();
    }
    // Get the localisation configuration


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Default AND WHERE uid ...

    // Do we have a showUid not for the local table but for the foreign table? 3.3.3
    if($this->pObj->arrLocalTable['showUid4TableField'])
    {
      $str_andWhere = ' AND '.$this->pObj->arrLocalTable['showUid4TableField'].' = '.$this->pObj->piVars['showUid'];
    }
    if(!$this->pObj->arrLocalTable['showUid4TableField'])
    {
      $str_andWhere = ' AND '.$this->pObj->arrLocalTable['uid'].' = '.$this->pObj->piVars['showUid'];
    }
    // Do we have a showUid not for the local table but for the foreign table? 3.3.3
    // Default AND WHERE uid ...


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return with default AND WHERE uid ..., if we don't have localisation fields

    if (!$arr_localise)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$table.' hasn\'t any localised field.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/LOCALISATION] A localised AND WHERE isn\'t needed.', $this->pObj->extKey, 0);
      }
      return $str_andWhere;
    }
    // Return with default AND WHERE uid ..., if we don't have localisation fields


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Building and Return AND WHERE

    if ($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      // Nothing to do: Return with default AND WHERE uid ...
    }
    if ($this->int_localisation_mode == PI1_SELECTED_OR_DEFAULT_LANGUAGE)
    {
      $str_andWhere =
        ' AND '.
        '( '.
          $this->pObj->arrLocalTable['uid'].' = '.$this->pObj->piVars['showUid'].' '.
          'OR '.
          $arr_localise['pid_field'].' = '.$this->pObj->piVars['showUid'].' '.
        ')';
    }
    if ($this->int_localisation_mode == PI1_SELECTED_LANGUAGE_ONLY)
    {
      $str_andWhere =
        ' AND '.
        '( '.
          $this->pObj->arrLocalTable['uid'].' = '.$this->pObj->piVars['showUid'].' '.
          'OR '.
          $arr_localise['pid_field'].' = '.$this->pObj->piVars['showUid'].' '.
        ')';
    }
    // Building AND WHERE

    return $str_andWhere;
  }















  /***********************************************
  *
  * Configuring Localisation
  *
  **********************************************/




   /**
 * Get the localisation configuration out of TypoScript config. Set the class vars $lang_id and
 * $overlay_mode. Returns one of the constants:
 * PI1_ANY_LANGUAGE, PI1_DEFAULT_LANGUAGE_ONLY, PI1_DEFAULT_LANGUAGE, PI1_SELECTED_LANGUAGE_ONLY, PI1_SELECTED_OR_DEFAULT_LANGUAGE
 *
 * Constants were defined in the constructor.
 *
 * @return	integer		See description above
 * @version 3.9.3
 * @since 2.0.0
 */
  private function localisationConfig()
  {

    $this->lang_id      = $GLOBALS['TSFE']->sys_language_content;
    $this->overlay_mode = $GLOBALS['TSFE']->sys_language_contentOL;

    if ($this->pObj->b_drs_locallang)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] config.sys_language_uid = '.$this->lang_id, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/LOCALISATION] config.sys_language_overlay = '.$this->overlay_mode, $this->pObj->extKey, 0);
    }

//    if ($this->lang_id == 0 && $this->overlay_mode === 0)
//    {
//      // Display records with sys_language_uid = 0 or -1
//      return PI1_ANY_LANGUAGE;
//    }
//    if ($this->lang_id == 0 && $this->overlay_mode === 1)
//    {
//      // Display only records with sys_language_uid = 0
//      return PI1_DEFAULT_LANGUAGE_ONLY;
//    }
    if ($this->lang_id == 0)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_DEFAULT_LANGUAGE', $this->pObj->extKey, 0);
      }
      // Display only records with sys_language_uid = 0 or -1
      $this->int_localisation_mode = PI1_DEFAULT_LANGUAGE;
      return PI1_DEFAULT_LANGUAGE;
    }
    if ($this->lang_id > 0 && $this->overlay_mode === 'hideNonTranslated')
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_SELECTED_LANGUAGE_ONLY', $this->pObj->extKey, 0);
      }
      $this->int_localisation_mode = PI1_SELECTED_LANGUAGE_ONLY;
      return PI1_SELECTED_LANGUAGE_ONLY;
    }
    if ($this->lang_id > 0)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_SELECTED_OR_DEFAULT_LANGUAGE', $this->pObj->extKey, 0);
      }
      $this->int_localisation_mode = PI1_SELECTED_OR_DEFAULT_LANGUAGE;
      return PI1_SELECTED_OR_DEFAULT_LANGUAGE;
    }

  }















  /***********************************************
  *
  * Consolidation
  *
  **********************************************/




  /**
 * Removes all default language records, which have a translation.
 * Process SQL result rows in case of PI1_SELECTED_OR_DEFAULT_LANGUAGE only.
 *
 * @param	array		$rows: SQL result rows
 * @return	array		$rows: Consolidated rows
 */
  function consolidate_filter($rows)
  {
    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return, if we don't have an array or we have an empty array

    if (!is_array($rows))
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[WARN/LOCALISATION] Rows aren\'t an array. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/LOCALISATION] Without rows we don\'t need any consolidation.', $this->pObj->extKey, 0);
      }
      return FALSE;
    }
    if (count($rows) < 1)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[WARN/LOCALISATION] Rows are #0. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/LOCALISATION] Without rows we don\'t need any consolidation.', $this->pObj->extKey, 0);
      }
      return $rows;
    }
    // Return, if we don't have an array or we have an empty array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Consolidation Steps
    // 1. If language should not replaced RETURN
    // 2. Fetch all language default records
    // 3. Remove the default records from $rows, if they have a translation.
    // 4. Language Overlay
    // 5. Return $rows

    // 1. If language should not replaced RETURN
    $this->int_localisation_mode = $this->localisationConfig();
    if ($this->int_localisation_mode != PI1_SELECTED_OR_DEFAULT_LANGUAGE)
    {
      return $rows;
    }
    // 1. If language should not replaced RETURN

    // 2. Fetch all language default records
    foreach ($rows as $row => $elements)
    {
      $tableField           = $elements['table.field'];
      list($table, $field)  = explode('.', $tableField);
      $langField            = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
      $int_sys_language     = $elements['table.'.$langField];
      if ($int_sys_language <= 0)
      {
        $arr_default[$table][$elements['uid']] = $row;
      }
    }
    // 2. Fetch all language default records

    // 3. Remove the default records from $rows, if they have a translation.
    foreach ($rows as $row => $elements)
    {
      $tableField           = $elements['table.field'];
      list($table, $field)  = explode('.', $tableField);
      $langPidField         = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
      $int_languagePid      = $elements['table.'.$langPidField];
      if (in_array($int_languagePid, array_keys($arr_default[$table])))
      {
        $row_default = $arr_default[$table][$int_languagePid];
        unset($rows[$row_default]);
      }
    }
    // 3. Remove the default records from $rows, if they have a translation.


    // 4. Language Overlay
    // Do we have lang_ol fields?
    $arr_lang_ol        = FALSE;
    $conf_tca           = $this->conf_localisation['TCA.'];
    $str_field_lang_ol  = $str_field.$conf_tca['field.']['appendix'];
    $str_devider        = $str_field.$conf_tca['value.']['devider'];
    $bool_langPrefix    = $str_field.$conf_tca['value.']['langPrefix'];

    reset($rows);
    $firstKey = key($rows);
    $int_count = 0;

    // Check first row for lang_ol fields
    foreach ($rows[$firstKey] as $tableField_ol => $value)
    {
      list($table, $field_ol) = explode('.', $tableField_ol);
      $int_field_len  = strlen($field_ol) - strlen($str_field_lang_ol);
      $field_appendix = substr($field_ol, $int_field_len);
      $field          = substr($field_ol, 0, $int_field_len);
      if ($field_appendix == $str_field_lang_ol)
      {
        $arr_lang_ol[$int_count]['default'] = $table.'.'.$field;
        $arr_lang_ol[$int_count]['overlay'] = $tableField_ol;
        $int_count ++;
      }
    }
    // Check first row for lang_ol fields

    // Process language overlay, if there are lang_ol fields
    if (is_array($arr_lang_ol))
    {
      $lang_prefix = $GLOBALS['TSFE']->lang; // Value i.e.: de
      // Loop through all SQL result rows
      foreach ($rows as $row => $elements)
      {
        // Loop through all lang_ol fields
        foreach ($arr_lang_ol as $key => $row_lang_ol)
        {
          $str_overlay = $elements[$row_lang_ol['overlay']]; // Get the value. I.e: en:Lead Story|fr:Accroche
          // lang_ol has a value
          if ($str_overlay != '')
          {
            $str_phrase_ol = FALSE;
            $arr_overlay = explode($str_devider, trim($str_overlay));

            // TypoScript configuration: lang_ol values have a lang prefix like de, en or fr
            if ($bool_langPrefix)
            {
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
              foreach ($arr_overlay as $str_phrase)
              {
                if ($lang_prefix.':' == substr($str_phrase, 0, strlen($lang_prefix.':')))
                {
                  $str_phrase_ol = substr($str_phrase, strlen($lang_prefix.':'));
                }
              }
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
            }
            // TypoScript configuration: lang_ol values have a lang prefix like de, en or fr

            // TypoScript configuration: lang_ol values haven't any lang prefix like de, en or fr
            if (!$bool_langPrefix)
            {
              // Take the phrase out of the $arr_overlay with the key language-id minus one
              $str_phrase_ol = $arr_overlay[$this->lang_id - 1];
            }
            // TypoScript configuration: lang_ol values haven't any lang prefix like de, en or fr

            if ($str_phrase_ol)
            {
              $rows[$row]['value'] = $str_phrase_ol;
            }
          }
          // lang_ol has a value
        }
        // Loop through all lang_ol fields
      }
      // Loop through all SQL result rows
    }
    // Process language overlay, if there are lang_ol fields
    // 4. Language Overlay


    // 5. Return $rows
    return $rows;
  }




  /**
 * Consolidate the SQL-Result: The non current language records will be deleted.
 * Process SQL result rows in case of PI1_SELECTED_OR_DEFAULT_LANGUAGE only.
 *
 * @param	array		$rows: SQL result rows
 * @param	string		$table: The current table name
 * @return	array		$rows: Consolidated rows
 */
  function consolidate_rows($rows, $table)
  {


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return, if we don't have an array or we have an empty array

    if (!is_array($rows))
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[WARN/LOCALISATION] Rows aren\'t an array. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/LOCALISATION] Without rows we don\'t need any consolidation.', $this->pObj->extKey, 0);
      }
      return FALSE;
    }
    if (count($rows) < 1)
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[WARN/LOCALISATION] Rows are #0. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/LOCALISATION] Without rows we don\'t need any consolidation.', $this->pObj->extKey, 0);
      }
      return $rows;
    }
    // Return, if we don't have an array or we have an empty array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Consolidation Steps
    // 1. If language should not replaced RETURN
    // 2. Fetch all language default records
    // 3. Process l10n_mode in case of exclude or mergeIfNotBlank
    // 4. In case of a non localised table: Copy values from default to current language record
    // 5. Remove the default records from $rows, if they have a translation.
    // 6. Set the default language record uid
    // 7. Language Overlay
    // 8. Return $rows

    // 1. If language should not replaced RETURN
//var_dump($this->int_localisation_mode);  //:todo:
//    if ($this->int_localisation_mode === FALSE)
//    {
      $this->int_localisation_mode = $this->localisationConfig();
      if ($this->int_localisation_mode != PI1_SELECTED_OR_DEFAULT_LANGUAGE)
      {
        if ($this->pObj->b_drs_locallang)
        {
          t3lib_div::devlog('[INFO/LOCALISATION] Records in default language should ignored in every case.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/LOCALISATION] We don\'t need any consolidation.', $this->pObj->extKey, 0);
        }
        return $rows;
      }
//    }
    // 1. If language should not replaced RETURN

    // Just for development
    if (FALSE)
    {
      $int_count    = 0;
      $int_max_rows = 10;
      $rows_prompt  = $rows;
      foreach ($rows as $row => $elements)
      {
        if($int_count >= $int_max_rows)
        {
          unset($rows_prompt[$row]);
        }
        $int_count++;
      }
      var_dump($rows_prompt);
    }
    // Just for development

    // 2. Fetch all language default records
    $int_count = 0;
//var_dump('localisation 934', $rows);
    foreach ($rows as $row => $elements)
    {
      $langPidField         = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField']; // I.e: l18n_parent
      $int_languagePid      = $elements[$table.'.'.$langPidField];
      $langField            = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
      $int_sys_language     = $elements[$table.'.'.$langField];
      if ($int_sys_language <= 0)
      {
//        $arr_default[$table][$elements[$table.'.uid']][] = $row;
        $arr_default[$table.'.uid'][$elements[$table.'.uid']]['keys_in_rows'][] = $row;
      }
      if ($int_sys_language > 0)
      {
        $arr_localise[$table.'.uid'][$elements[$table.'.uid']][$langPidField]     = $int_languagePid;
        $arr_localise[$table.'.uid'][$elements[$table.'.uid']]['keys_in_rows'][]  = $row;
      }
      $int_count++;
    }
    // 2. Fetch all language default records


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
      t3lib_div::devLog('[INFO/PERFORMANCE] All records in default language are fetched: '. ($endTime - $startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    // 3. Process l10n_mode in case of exclude and mergeIfNotBlank
    $bool_l10n_mode = FALSE;
    // Do we have localised records?
    if(is_array($arr_localise))
    {
      $bool_l10n_mode = TRUE;
    }

    // We have localised records
    if ($bool_l10n_mode)
    {
      reset($rows);
//var_dump('localisation 966', $rows);
      $firstKey = key($rows);
      $int_count = 0;
      // Loop through the first row for getting the tableFields
      foreach ($rows[$firstKey] as $tableField => $value)
      {
        $bool_count = FALSE;
        list($tableL10n, $field) = explode('.', $tableField);
        // Get exclude mode
        if ($GLOBALS['TCA'][$tableL10n]['columns'][$field]['l10n_mode'] == 'exclude')
        {
          $arr_l10n_mode[$int_count][$tableField] = 'exclude';
          $bool_count = TRUE;
        }
        // Get exclude mode
        // Get mergeIfNotBlank mode
        if ($GLOBALS['TCA'][$tableL10n]['columns'][$field]['l10n_mode'] == 'mergeIfNotBlank')
        {
          $arr_l10n_mode[$int_count][$tableField] = 'mergeIfNotBlank';
          $bool_count = TRUE;
        }
        // Get mergeIfNotBlank mode
        if ($bool_count)
        {
          $int_count++;
        }
      }
      // Loop through the first row for getting the tableFields

      // We have l10n_mode fields with the mode exclude or mergeIfNotBlank
      if (is_array($arr_l10n_mode))
      {
        // Loop through the array with localisation information
//var_dump('localisation 998', $arr_localise);
        foreach ($arr_localise as $tableFieldLUid => $arr_uid)
        {
          list($tableLoc, $fieldLoc) = explode('.', $tableFieldLUid);                   // tx_wine_main.uid
          $langPidField = $GLOBALS['TCA'][$tableLoc]['ctrl']['transOrigPointerField'];  // I.e: l18n_parent

          // Loop through the records with localisation information
          foreach((array) $arr_uid as $uid_localise => $rec_localise)
          {
//var_dump('localisation 1008', $uid_localise, $rec_localise);
            $uid_default        = $rec_localise[$langPidField];
            $arr_keysInRowsLoc  = $rec_localise['keys_in_rows'];
            $key_in_rowsDef     = $arr_default[$tableFieldLUid][$uid_default]['keys_in_rows'][0];

            // Loop through all rows with localised records
            foreach ($arr_keysInRowsLoc as $key_in_rowsLoc)
            {
              // Loop through the array with the l10n_mode fields
              foreach ($arr_l10n_mode as $key => $arr_tableFieldMode)
              {
                $tableField     = key($arr_tableFieldMode);
                $str_l10n_mode  = $arr_tableFieldMode[$tableField];
//var_dump('localisation 1021', $str_l10n_mode.': $rows['.$key_in_rowsLoc.']['.$tableField.'] = $rows['.$key_in_rowsDef.']['.$tableField.']', $rows[$key_in_rowsDef][$tableField]);
                // Allocates to the field of the localised row the value from the field out of the default row
                if ($str_l10n_mode == 'exclude')
                {
                  $rows[$key_in_rowsLoc][$tableField] = $rows[$key_in_rowsDef][$tableField];
//                  if ($this->pObj->b_drs_locallang)
//                  {
//                    t3lib_div::devlog('[INFO/LOCALISATION] Exclude', $this->pObj->extKey, 0);
//                  }
                }
                // Allocates to the field of the localised row the value from the field out of the default row, if localised field is empty
                if ($str_l10n_mode == 'mergeIfNotBlank')
                {
                  if ($rows[$key_in_rowsLoc][$tableField] == FALSE)
                  {
                    $rows[$key_in_rowsLoc][$tableField] = $rows[$key_in_rowsDef][$tableField];
                  }
                }
              }
              // Loop through the array with the l10n_mode fields
            }
            // Loop through all rows with localised records
          }
          // Loop through the records with localisation information
        }
        // Loop through the array with localisation information
      }
      // We have l10n_mode fields with the mode exclude or mergeIfNotBlank
    }
    // We have localised records
    // 3. Process l10n_mode in case of exclude and mergeIfNotBlank


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After l10n_mode: '. ($endTime - $startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    // 4. In case of a non localised table: Copy values from default to current language record
//var_dump('localisation 1059', $this->pObj->arr_realTables_notLocalised);
    if(is_array($this->pObj->arr_realTables_notLocalised))
    {
      $arr_lang_ol        = FALSE;
      $conf_tca           = $this->conf_localisation['TCA.'];
      $str_field_lang_ol  = $str_field.$conf_tca['field.']['appendix'];

      reset($rows);
      $firstKey = key($rows);

      // Check first row for lang_ol fields
//var_dump('localisation 1065', $rows[$firstKey]);
      foreach ($rows[$firstKey] as $tableField_ol => $dummy)
      {
        list($table_ol, $field_ol) = explode('.', $tableField_ol);
        if(in_array($table_ol, $this->pObj->arr_realTables_notLocalised))
        {
          $arr_lang_ol[] = $tableField_ol;
        }
//        $int_field_len  = strlen($field_ol) - strlen($str_field_lang_ol);
//        $field_appendix = substr($field_ol, $int_field_len);
//        $field          = substr($field_ol, 0, $int_field_len);
//        if ($field_appendix == $str_field_lang_ol)
//        {
//          $arr_lang_ol[] = $table2.'.'.$field;
//          $arr_lang_ol[] = $tableField_ol;
//        }
      }
    }
    // Check first row for lang_ol fields
//var_dump('localisation 1075', $arr_lang_ol);
//string(17) "localisation 1075"
//array(1) {
//  [0]=>
//  string(34) "tx_wine_drinkability.title_lang_ol"
//  }
//}

    // Get default lang overlay values
    $arr_default_lang_ol  = FALSE;
    $int_count            = 0;
    if(is_array($arr_lang_ol))
    {
      $localTable       = $this->pObj->localTable;
      $uid_localTable   = $localTable.'.uid';
      $sys_language_uid = $GLOBALS['TCA'][$localTable]['ctrl']['languageField'];  // I.e. tx_wine_main.sys_language_uid

      $arr_default_lang_ol = FALSE;
      foreach((array) $rows as $row => $elements)
      {
        // Default language record
        if($elements[$localTable.'.'.$sys_language_uid] <= 0)
        {
          foreach((array) $arr_lang_ol as $key => $field_lang_ol)
          {
            $arr_default_lang_ol[$elements[$uid_localTable]][$int_count]['field_lang_ol'] = $field_lang_ol;
            $arr_default_lang_ol[$elements[$uid_localTable]][$int_count]['value']         = $elements[$field_lang_ol];
            $int_count++;
          }
        }
        // Default language record
      }
    }
    // Get default lang overlay values

//var_dump('localisation 1108', $arr_default_lang_ol);
//string(17) "localisation 1108"
//array(3) {
//  [1]=>
//  array(5) {
//    [0]=>
//    array(2) {
//      ["field_lang_ol"]=>
//      string(28) "tx_wine_region.title_lang_ol"
//      ["value"]=>
//      string(0) ""
//    }
//    [1]=>
//    array(2) {
//      ["field_lang_ol"]=>
//      string(28) "tx_wine_winery.title_lang_ol"
//      ["value"]=>
//      string(0) ""
//    }
//    [2]=>
//    array(2) {
//      ["field_lang_ol"]=>
//      string(27) "tx_wine_style.title_lang_ol"
//      ["value"]=>
//      string(32) "de:Rotwein (jung)|es:Tinto Joven"
//    }
//    [3]=>
//    array(2) {
//      ["field_lang_ol"]=>
//      string(30) "tx_wine_varietal.title_lang_ol"
//      ["value"]=>
//      string(33) "de:Garnacha 100%|es:Garnacha 100%"
//    }
//    [4]=>
//    array(2) {
//      ["field_lang_ol"]=>
//      string(34) "tx_wine_drinkability.title_lang_ol"
//      ["value"]=>
//      string(72) "de:Sofort und die nächsten 3-4 Jahre|es:Ahora y los próximos 3-4 años"
//    }
//  [7]=>
//  ...
//  [2]=>
//  ...
//}

    // Set lang overlay values in current language record
    if(is_array($arr_default_lang_ol))
    {
      $langPidField = $GLOBALS['TCA'][$localTable]['ctrl']['transOrigPointerField']; // I.e: l18n_parent
      $int_count    = 0;
  //var_dump('localisation 1135', $rows);
      foreach((array) $rows as $row => $elements)
      {
  //var_dump('localisation 1137', $elements);
        // Current language record
        if($elements[$localTable.'.'.$sys_language_uid] > 0)
        {
          // Get parent language uid
          $uid_l10n_parent = $elements[$localTable.'.'.$langPidField];
  //var_dump('localisation 1142', $arr_default_lang_ol[$uid_l10n_parent]);
          foreach((array) $arr_default_lang_ol[$uid_l10n_parent] as $key => $arr_field_value)
          {
            $field_lang_ol              = $arr_field_value['field_lang_ol'];
            $value_lang_ol              = $arr_field_value['value'];
            $rows[$row][$field_lang_ol] = $value_lang_ol;
          }
        }
        // Current language record
      }
    }
    // Set lang overlay values in current language record
//var_dump('localisation 1153', $rows);

    unset($arr_default_lang_ol);
    unset($arr_lang_ol);
    // 4. In case of a non localised table: Copy values from default to current language record


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After non localised tables: '. ($endTime - $startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    // 5. Remove the default records from $rows, if they have a translation.
    if(is_array($arr_default))
    {
      $langPidField = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField']; // I.e: l18n_parent
      foreach ($rows as $row => $elements)
      {
        $int_languagePid = $elements[$table.'.'.$langPidField];
        // If the record has an element with the key l18n_parent i.e.
        if (in_array($int_languagePid, array_keys($arr_default[$table.'.uid'])))
        {
          // Delete in the array with the default language records the record with the uid which is the value out of the $langPidField
          foreach((array) $arr_default[$table.'.uid'][$int_languagePid]['keys_in_rows'] as $row_default)
          {
            //var_dump($table.'.uid: '.$int_languagePid.': '.$row_default);
            unset($rows[$row_default]);
          }
          // Delete in the array with the default language records the record with the uid which is the value out of the $langPidField
        }
        // If the record has an element with the key l18n_parent i.e.
      }
    }
    // 5. Remove the default records from $rows, if they have a translation.


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After removing waste records: '. ($endTime - $startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    // 6. Set the default language record uid
    // Should we set it?
    $bool_defaultLanguageLink = $this->conf_localisation['realURL.']['defaultLanguageLink'];
    if ($bool_defaultLanguageLink)
    {
      if (is_array($arr_localise))
      {
        $langPidField = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField']; // I.e: l18n_parent
        foreach((array) $arr_localise[$table.'.uid'] as $uid_localiseRecord => $row_localise)
        {
          foreach((array) $row_localise['keys_in_rows'] as $key_in_rows)
          {
            //var_dump('$rows['.$key_in_rows.']['.$table.'.uid] = '.$row_localise[$langPidField]);
            $rows[$key_in_rows][$table.'.uid'] = $row_localise[$langPidField];
          }
        }
      }
    }
    // Should we set it?
    // 6. Set the default language record uid

    // 7. Language Overlay
    // Do we have lang_ol fields?
    $arr_lang_ol        = FALSE;
    $conf_tca           = $this->conf_localisation['TCA.'];
    $str_field_lang_ol  = $str_field.$conf_tca['field.']['appendix'];
    if ($this->pObj->b_drs_locallang)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] Fields with the appendix '.$str_field_lang_ol.' will be used for language overlaying.', $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/LOCALISATION] If you want to use another appendix please configure:<br />'.
        $this->conf_localisation_path.'.TCA.field.appendix.', $this->pObj->extKey, 1);
    }
    $str_devider        = $str_field.$conf_tca['value.']['devider'];
    $bool_langPrefix    = $str_field.$conf_tca['value.']['langPrefix'];
    if ($this->pObj->b_drs_locallang)
    {
      if ($bool_langPrefix)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Overlay values need the language prefix. I.e. en, de, fr.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/LOCALISATION] If you want to use overlay values without this prefixes please configure:<br />'.
          $this->conf_localisation_path.'.TCA.value.langPrefix.', $this->pObj->extKey, 1);
      }
      if (!$bool_langPrefix)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Overlay values don\'t need any language prefix like en, de, fr.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/LOCALISATION] If you want to use overlay values with this prefixes please configure:<br />'.
          $this->conf_localisation_path.'.TCA.value.langPrefix.', $this->pObj->extKey, 1);
      }
    }

    reset($rows);
    $firstKey = key($rows);
    $int_count = 0;

    // Check first row for lang_ol fields
    foreach ($rows[$firstKey] as $tableField_ol => $value)
    {
      list($table, $field_ol) = explode('.', $tableField_ol);
      $int_field_len  = strlen($field_ol) - strlen($str_field_lang_ol);
      $field_appendix = substr($field_ol, $int_field_len);
      $field          = substr($field_ol, 0, $int_field_len);
      if ($field_appendix == $str_field_lang_ol)
      {
        $arr_lang_ol[$int_count]['default'] = $table.'.'.$field;
        $arr_lang_ol[$int_count]['overlay'] = $tableField_ol;
        $int_count ++;
      }
    }
    // Check first row for lang_ol fields

    // Process language overlay, if there are lang_ol fields
    if (is_array($arr_lang_ol))
    {
      $lang_prefix = $GLOBALS['TSFE']->lang; // Value i.e.: de
//var_dump('localisation 1153', $lang_prefix);
      // Loop through all SQL result rows
      foreach ($rows as $row => $elements)
      {
//var_dump('localisation 1157', $elements);
        // Loop through all lang_ol fields
        foreach ($arr_lang_ol as $key => $row_lang_ol)
        {
          $str_overlay = $elements[$row_lang_ol['overlay']]; // Get the value. I.e: en:Lead Story|fr:Accroche
          // lang_ol has a value
          if ($str_overlay != '')
          {
            $str_phrase_ol = FALSE;
            $arr_overlay = explode($str_devider, trim($str_overlay));
//var_dump('localisation 1166', $arr_overlay);

            // TypoScript configuration: lang_ol values have a lang prefix like de, en or fr
            if ($bool_langPrefix)
            {
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
              foreach ($arr_overlay as $str_phrase)
              {
                if ($lang_prefix.':' == substr($str_phrase, 0, strlen($lang_prefix.':')))
                {
                  $str_phrase_ol = substr($str_phrase, strlen($lang_prefix.':'));
                }
              }
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
            }
            // TypoScript configuration: lang_ol values have a lang prefix like de, en or fr

            // TypoScript configuration: lang_ol values haven't any lang prefix like de, en or fr
            if (!$bool_langPrefix)
            {
              // Take the phrase out of the $arr_overlay with the key language-id minus one
              $str_phrase_ol = $arr_overlay[$this->lang_id - 1];
            }
            // TypoScript configuration: lang_ol values haven't any lang prefix like de, en or fr

            if ($str_phrase_ol)
            {
              $rows[$row][$row_lang_ol['default']] = $str_phrase_ol;
            }
          }
          // lang_ol has a value
        }
        // Loop through all lang_ol fields
      }
      // Loop through all SQL result rows
    }
    // Process language overlay, if there are lang_ol fields
    // 7. Language Overlay


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
      t3lib_div::devLog('[INFO/PERFORMANCE] After language overlay: '. ($endTime - $startTime).' ms', $this->pObj->extKey, 0);
    }
    // DRS - Performance


    // Just for development
    if (FALSE)
    {
      $int_count    = 0;
      $int_max_rows = 10;
      $rows_prompt  = $rows;
      foreach ($rows as $row => $elements)
      {
        if($int_count >= $int_max_rows)
        {
          unset($rows_prompt[$row]);
        }
        $int_count++;
      }
      var_dump($rows_prompt);
    }
    // Just for development

    // 8. Return $rows
    return $rows;
  }














  /***********************************************
  *
  * Little Helpers
  *
  **********************************************/




  /**
 * get_localisedUid( ): 
 *
 * @return	void
 * @version 3.9.3
 * @since 2.0.0
 */
  public function get_localisedUid( $table, $uid )
  {
    $this->localisationConfig( );

      // RETURN: Current language is the default language
    if ( $this->int_localisation_mode == PI1_DEFAULT_LANGUAGE )
    {
      return $uid;
    }

    return $uid;
  }









  /**
 * Load the local or global TypoScript configuration array from advanced.localisation
 *
 * @return	void
 */
  function init_typoscript()
  {

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Load the local TypoScript configuration

    $viewWiDot                    = $this->view.'.';
    $this->conf_localisation      = $this->conf['views.'][$viewWiDot][$this->mode.'.']['advanced.']['localisation.'];
    $this->conf_localisation_path = 'views.'.$viewWiDot.$this->mode.'.advanced.localisation';
    // Load the local TypoScript configuration


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Load the global TypoScript configuration if there isn't any local configuration

    if (is_array($this->conf_localisation))
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$this->conf_localisation_path.' is configured.', $this->pObj->extKey, 0);
      }
    }
    if (!is_array($this->conf_localisation))
    {
      if ($this->pObj->b_drs_locallang)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$this->conf_localisation_path.' isn\'t configured. We take the global array.', $this->pObj->extKey, 0);
      }
      $this->conf_localisation      = $this->conf['advanced.']['localisation.'];
      $this->conf_localisation_path = 'advanced.localisation';
    }
    // Load the global TypoScript configuration if there isn't any local configuration

  }










  /**
 * Make the array propper for localisation fields.
 * Empty elements will removed. Field names become a table prefix.
 *
 * @param	array		$arr_langFields: Array with the field names of localisation fields
 * @param	string		$table: Name of the table in the TYPO3 database / in TCA
 * @return	array		$arr_langFields: Cleaned up array
 */
  function propper_locArray($arr_langFields, $table)
  {

    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return, if we don't have an array

    if (!is_array($arr_langFields))
    {
      return FALSE;
    }
    // Return, if we don't have an array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Remove empty elements

    foreach((array) $arr_langFields as $key => $field)
    {
      if(!$field)
      {
        unset($arr_langFields[$key]);
      }
    }
    // Remove empty elements


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Return, if we don't have an array

    if (!is_array($arr_langFields))
    {
      return FALSE;
    }
    // Return, if we don't have an array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Add the table. We like the table.field syntax.

    foreach((array) $arr_langFields as $key => $field)
    {
      $arr_langFields[$key] = $table.'.'.$field;
    }
    // Add the table. We like the table.field syntax.

    if(is_array($arr_langFields))
    {
      if(count($arr_langFields) == 0)
      {
        unset($arr_langFields);
        $arr_langFields = FALSE;
      }
    }
    return $arr_langFields;
  }


























}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_localisation.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_localisation.php']);
}

?>