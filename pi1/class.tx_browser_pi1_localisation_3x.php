<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2016 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_localisation_3x bundles methods for localisation for the extension browser
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 3.9.3
* @since 2.0.0
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   70: class tx_browser_pi1_localisation_3x
 *  124:     function __construct($parentObj)
 *
 *              SECTION: SQL query parts
 *  180:     function localisationFields_select($table)
 *  414:     function localisationFields_where($table)
 *  512:     function localisationSingle_where($table)
 *
 *              SECTION: Configuring Localisation
 *  650:     private function localisationConfig()
 *
 *              SECTION: Consolidation
 *  774:     function consolidate_filter($rows)
 *  946:     function consolidate_rows($rows, $table)
 *
 *              SECTION: Little Helpers
 * 1592:     public function get_localisedUid( $table, $uid )
 * 1670:     function init_typoscript()
 * 1735:     private function is_tableLocalised( $table )
 * 1846:     function propper_locArray($arr_langFields, $table)
 *
 *              SECTION: SQL
 * 1935:     public function sql_getLanguages( )
 * 2157:     private function sql_localisedUid( $table, $uid )
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_localisation_3x
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

    // [Array] arr_localisedTables[$table]
  var $arr_localisedTables        = null;
    // [Array] $arr_localisedTableFields[$table]['id_field']
  var $arr_localisedTableFields   = null;
    // [Integer] See defines in the contructor. Set by localisationConfig().
  var $int_localisation_mode      = null;
    // [Integer] $GLOBALS['TSFE']->sys_language_content. Set by localisationConfig().
  var $lang_id                    = null;
    // [String] $GLOBALS['TSFE']->sys_language_contentOL. Set by localisationConfig().
  var $overlay_mode               = null;
    // [Array] The The current TypoScript configuration array local or global: advanced.localisation
  var $conf_localisation          = false;
    // [String] The The current TypoScript configuration path local or global: advanced.localisation
  var $conf_localisation_path     = false;
    // Variables set by this class









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
 * @version 3.9.3
 * @since 2.0.0
 */
  function localisationFields_select($table)
  {

      ////////////////////////////////////////////////////////////////////////////////
      //
      // Load the TCA, if we don't have an table.columns array

    $this->pObj->objZz->loadTCA($table);
      // Load the TCA, if we don't have an table.columns array


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Do we need translated/localised records?

    $bool_dontLocalise = false;
    if(!isset($this->int_localisation_mode))
    {
      $this->int_localisation_mode = $this->localisationConfig();
    }
    if($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      $bool_dontLocalise = true;
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Localisation mode is PI1_DEFAULT_LANGUAGE. There isn\' any need to localise!', $this->pObj->extKey, 0);
      }
    }
    if($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE_ONLY)
    {
      $bool_dontLocalise = true;
      if ($this->pObj->b_drs_localisation)
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

    $bool_tableIsLocalised = false;
    if ($arr_localise['id_field'] && $arr_localise['pid_field'])
    {
      $bool_tableIsLocalised = true;
    }
    if($bool_tableIsLocalised and $bool_dontLocalise)
    {
      $bool_tableIsLocalised = false;
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$table.' is localised. But we ignore it!', $this->pObj->extKey, 0);
      }
    }
    if ($bool_tableIsLocalised)
    {
      $this->pObj->arr_realTables_localised[] = $table;
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'\' is localised.', $this->pObj->extKey, 0);
      }
    }
    if (!$bool_tableIsLocalised)
    {
      $this->pObj->arr_realTables_notLocalised[] = $table;
      if ($this->pObj->b_drs_localisation)
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
      $bool_fieldIsLocalised = false;
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
            if ($this->pObj->b_drs_localisation)
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
            $bool_fieldIsLocalised = true;
          }
          // Has the table a field for tranlation (syntax i.e.: field_lang_ol)?
        }
      }
      // Loop through the array with all used tableFields

      if (!$bool_fieldIsLocalised)
      {
        if ($this->pObj->b_drs_localisation)
        {
          t3lib_div::devlog('[INFO/LOCALISATION] \''.$table.'\' hasn\'t any field with appendix '.$conf_tca['field.']['appendix'].'.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/LOCALISATION] Overlay isn\'t needed.', $this->pObj->extKey, 0);
        }
        return false;
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

    $arr_andSelect['woAlias'] = false;  // Default
    // Without Alias. I.e.: tx_bzdstaffdirectory_groups.sys_language_uid, tx_bzdstaffdirectory_groups.l18n_parent
//BUGFIX 091112
//    $arr_andSelect['filter']  = $str_dummyFilter;
    $arr_andSelect['filter']  = "'0' AS `table.title_lang_ol`, ".$str_dummyFilter;
    // Filter. I.e.:        tx_bzdstaffdirectory_groups.sys_language_uid AS `table.sys_language_uid`, tx_bzdsta...
    $arr_andSelect['wiAlias'] = false;  // Default
    // With Alias. I.e.:    tx_bzdstaffdirectory_groups.sys_language_uid AS `tx_bzdstaffdirectory_groups.sys_la...
    $arr_andSelect['addedFields'] = false;          // Default
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
  * @version 3.9.3
  * @since 2.0.0
  */
  function localisationFields_where($table)
  {
      ////////////////////////////////////////////////////////////////////////////////
      //
      // Load the TCA, if we don't have an table.columns array

    $this->pObj->objZz->loadTCA($table);
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
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] There isn\'t any localised field.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[INFO/LOCALISATION] A localised AND WHERE isn\'t needed.', $this->pObj->extKey, 0);
      }
      return false;
    }
    // Return, if we don't have localisation fields


      ////////////////////////////////////////////////////////////////////////////////
      //
      // Building AND WHERE

      // DRS :TODO:
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = '$this->int_localisation_mode == PI1_SELECTED_OR_DEFAULT_LANGUAGE';
      t3lib_div::devlog( '[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS :TODO:
    if ($this->int_localisation_mode == PI1_DEFAULT_LANGUAGE)
    {
      $str_andWhere = $arr_localise['id_field']." <= 0 ";
    }
    if ($this->int_localisation_mode == PI1_SELECTED_OR_DEFAULT_LANGUAGE)
    {
      $str_andWhere = "( ".$arr_localise['id_field']." <= 0 OR ".$arr_localise['id_field']." = ".intval($this->lang_id)." ) ";
      // These andWhere needs a consolidation
//        // DEVELOPMENT: Browser engine 4.x
//      if( $this->pObj->dev_browserEngine == 4 )
//      {
//          // DRS
//        if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
//        {
//          $prompt = '+++ Browser engine 4.x ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++';
//          t3lib_div::devlog( $prompt, $this->pObj->extKey, 2 );
//          $prompt = 'Browser engine 4.x: andWhere for localised fields is modified. ' .
//                    'Only records of the default language will selected.';
//          t3lib_div::devlog( '[WARN/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 2 );
//          $prompt = 'Browser engine 4.x: If you are using this with the Browser engine 3.x, you will get trouble.';
//          t3lib_div::devlog( '[WARN/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 2 );
//        }
//          // DRS
//        $str_andWhere = $arr_localise['id_field']." <= 0 ";
//      }
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
  * @version 3.9.3
  * @since 2.0.0
  */
  function localisationSingle_where($table)
  {
      ////////////////////////////////////////////////////////////////////////////////
      //
      // Load the TCA, if we don't have an table.columns array

    $this->pObj->objZz->loadTCA($table);
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

    if ($this->int_localisation_mode === false)
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
      if ($this->pObj->b_drs_localisation)
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
  public function localisationConfig()
  {
      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN $this->int_localisation_mode is set before

    if( ! ( $this->int_localisation_mode === null ) )
    {
      return $this->int_localisation_mode;
    }
      // RETURN $this->int_localisation_mode is set before



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Get localisation configuration

    $this->lang_id      = $GLOBALS['TSFE']->sys_language_content;
    $this->overlay_mode = $GLOBALS['TSFE']->sys_language_contentOL;

      // DRS - Development Reporting System
    if ($this->pObj->b_drs_localisation)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] config.sys_language_uid = '.$this->lang_id, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/LOCALISATION] config.sys_language_overlay = '.$this->overlay_mode, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
      // Get localisation configuration

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



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN current language is default language

    if ($this->lang_id == 0)
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_DEFAULT_LANGUAGE', $this->pObj->extKey, 0);
      }
      // Display only records with sys_language_uid = 0 or -1
      $this->int_localisation_mode = PI1_DEFAULT_LANGUAGE;
      return PI1_DEFAULT_LANGUAGE;
    }
      // RETURN current language is default language



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN display selected language only

    if ($this->lang_id > 0 && $this->overlay_mode === 'hideNonTranslated')
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_SELECTED_LANGUAGE_ONLY', $this->pObj->extKey, 0);
      }
      $this->int_localisation_mode = PI1_SELECTED_LANGUAGE_ONLY;
      return PI1_SELECTED_LANGUAGE_ONLY;
    }
      // RETURN display selected language only



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN display selected or default language

    if ($this->lang_id > 0)
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] Mode is PI1_SELECTED_OR_DEFAULT_LANGUAGE', $this->pObj->extKey, 0);
      }
      $this->int_localisation_mode = PI1_SELECTED_OR_DEFAULT_LANGUAGE;
      return PI1_SELECTED_OR_DEFAULT_LANGUAGE;
    }
      // RETURN display selected or default language
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
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[WARN/LOCALISATION] Rows aren\'t an array. Is it ok?', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/LOCALISATION] Without rows we don\'t need any consolidation.', $this->pObj->extKey, 0);
      }
      return false;
    }
    if (count($rows) < 1)
    {
      if ($this->pObj->b_drs_localisation)
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
      if ( in_array( ( array ) $int_languagePid, array_keys( ( array ) $arr_default[$table] ) ) )
      {
        $row_default = $arr_default[$table][$int_languagePid];
        unset($rows[$row_default]);
      }
    }
    // 3. Remove the default records from $rows, if they have a translation.


    // 4. Language Overlay
    // Do we have lang_ol fields?
    $arr_lang_ol        = false;
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
            $str_phrase_ol = false;
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
 * consolidate_rows( )  : Consolidate the SQL-Result: The non current language records will be deleted.
 *                        Process SQL result rows in case of PI1_SELECTED_OR_DEFAULT_LANGUAGE only.
 *
 * @param	array	$rows   : SQL result rows
 * @param	string	$table  : The current table name
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     2.0.0
 */
  public function consolidate_rows( $rows, $table )
  {
      // For development only, IP must allowed in the extension manager!
    $promptForDev = false; 

      // RETURN : there is no row
    if( $this->consolidate_rowsNoRow( $rows ) )
    {
      return $rows;
    }
      // RETURN : there is no row

      ////////////////////////////////////////////////////////////////////////////////
      //
      // Consolidation Steps
      // 1. RETURN : current language is the default
      // 2. Get uids of records with default language and localised records
      // 3. Process l10n_mode in case of exclude or mergeIfNotBlank
      // 4. In case of a non localised table: Copy values from default to current language record
      // 5. Remove the default records from $rows, if they have a translation.
      // 6. Set the default language record uid
      // 7. Language Overlay
      // 8. Return $rows

      // 1. RETURN : current language is the default
    if( $this->consolidate_rows01noLocalisation( ) )
    {
      return $rows;
    }
      // 1. RETURN : current language is the default


      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel,  'begin' );

      // Just for development
    $this->zzDevPromptRows( $promptForDev, $rows );

      // 2. Get uids of records with default language and localised records
    $arrResult                = $this->consolidate_rows02getUids( $rows, $table );
    $arrUidsKeyDefault        = $arrResult[ 'default'   ];
    $arrUidsLocalisedDefault  = $arrResult[ 'localised' ];
    unset( $arrResult );
      // 2. Get uids of records with default language and localised records

      // 3. Process l10n_mode in case of exclude and mergeIfNotBlank
    $rows = $this->consolidate_rows03handleTableLocalised( $arrUidsLocalisedDefault, $arrUidsKeyDefault, $rows );

      // 4. In case of a non localised table: Copy values from default to current language record
    $rows = $this->consolidate_rows04handleTableTranslated( $rows );

      // 5. Remove the default records from $rows, if they have a translation.
    $rows = $this->consolidate_rows05removeDefault( $arrUidsKeyDefault, $rows, $table );

      // 6. Set the default language record uid
    $rows = $this->consolidate_rows06setDefaultUid( $arrUidsLocalisedDefault, $rows, $table );

      // 7. Language Overlay
    $rows = $this->consolidate_rows07languageOverlay( $rows, $table );

      // Just for development
    $this->zzDevPromptRows( $promptForDev, $rows );

    $this->pObj->timeTracking_log( $debugTrailLevel,  'end' );

      // 8. Return $rows
    return $rows;
  }

/**
 * consolidate_rows01noLocalisation( )  : Returns true, if current language is the default language
 *
 * @return	boolean
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function consolidate_rows01noLocalisation( )
  {
    $this->int_localisation_mode = $this->localisationConfig( );
    
    if( $this->int_localisation_mode != PI1_SELECTED_OR_DEFAULT_LANGUAGE )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Records in default language should ignored in every case.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'We don\'t need any localisation consolidation.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
    
    return false;
  }

/**
 * consolidate_rows02getUids( )  : 
 *
 * @param	array	$rows   : SQL result rows
 * @param	string	$table  : The current table name
 * @return	array	$arr_localise
 * 
 * @version   4.5.7
 * @since     2.0.0
 */
  private function consolidate_rows02getUids( $rows, $table )
  {
    $arrReturn = array( );
    
    $tableUid     = $table . '.uid';
      // I.e: l18n_parent
    $langPidField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField'  ];
    $langField    = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField'          ];

    foreach( $rows as $key => $row )
    {
      $int_languagePid  = $row[ $table . '.' . $langPidField ];
      $int_sys_language = $row[ $table . '.' . $langField    ];
      $recordUid        = $row[ $tableUid ];
      
      switch( true )
      {
        case( $int_sys_language > 0 ):
        default:
          $arrReturn[ 'localised' ][ $tableUid ][ $recordUid ][ $langPidField ]     = $int_languagePid;
          $arrReturn[ 'localised' ][ $tableUid ][ $recordUid ][ 'keys_in_rows' ][ ] = $key;
          break;
        case( $int_sys_language <= 0 ):
        default:
          $arrReturn[ 'default' ][ $tableUid ][ $recordUid ][ 'keys_in_rows' ][ ] = $key;
          break;          
      }
      
      unset( $int_sys_language );
    }
//$this->pObj->dev_var_dump( $arrReturn );

    return $arrReturn;
  }

/**
 * consolidate_rows03handleTableLocalised( )  : 
 *
 * @param       array   $arrUidsLocalisedDefault : 
 * @param	array	$rows   : SQL result rows
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function consolidate_rows03handleTableLocalised( $arrUidsLocalisedDefault, $arrUidsKeyDefault, $rows )
  {
      // RETURN : no localised records
    if( ! is_array( $arrUidsLocalisedDefault ) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'There isn\'t any localised record.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'l10n_mode exlude and mergeIfNotBlank won\'t handled.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $rows;
    }
      // RETURN : no localised records
    
      // Set variables
    $arr_l10n_mode  = array( );
    reset( $rows );
    $firstKey       = key( $rows );
    $int_count      = 0;
      // Set variables

      // Loop through the first row for getting the l10n_mode of each field
    foreach( array_keys( $rows[ $firstKey ] ) as $tableField )
    {
      list( $tableL10n, $field ) = explode( '.', $tableField );
      $l10n_mode  = $GLOBALS[ 'TCA' ][ $tableL10n ][ 'columns' ][ $field ][ 'l10n_mode' ];
        
      switch( true )
      {
        case( $l10n_mode == 'exclude' ):
          $arr_l10n_mode[ $int_count ][ $tableField ] = 'exclude';
          $int_count++;
          break;
        case( $l10n_mode == 'mergeIfNotBlank' ):
          $arr_l10n_mode[ $int_count ][ $tableField ] = 'mergeIfNotBlank';
          $int_count++;
          break;
        default:
            // Do nothing;
          break;
      }
      
      unset( $l10n_mode );
    }
      // Loop through the first row for getting the l10n_mode for each field

      // RETURN : any record hasn't any exclude or mergeIfNotBlank property
    if( empty( $arr_l10n_mode ) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Any field hasn\'t the l10n_mode exlude or mergeIfNotBlank.';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'l10n_mode exlude and mergeIfNotBlank won\'t handled.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $rows;
    }
      // RETURN : any record hasn't any exclude or mergeIfNotBlank property

      // Loop through the array with localisation information
    foreach( $arrUidsLocalisedDefault as $tableFieldLUid => $arr_uid )
    {
        // I.e: $tableFieldLUid = 'tx_wine_main.uid'
      list( $tableLoc ) = explode( '.', $tableFieldLUid );
        // I.e: $langPidField = 'l18n_parent'
      $langPidField     = $GLOBALS[ 'TCA' ][ $tableLoc ][ 'ctrl' ][ 'transOrigPointerField' ];

        // Loop through the records with localisation information
      foreach( ( array ) $arr_uid as $rec_localise )
      {
        $uid_default        = $rec_localise[ $langPidField ];
        $arr_keysInRowsLoc  = $rec_localise[ 'keys_in_rows' ];
        $key_in_rowsDef     = $arrUidsKeyDefault[ $tableFieldLUid ][ $uid_default ][ 'keys_in_rows' ][ 0 ];

          // Loop through all rows with localised records
        foreach ($arr_keysInRowsLoc as $key_in_rowsLoc)
        {
            // Loop through the array with the l10n_mode fields
          foreach( $arr_l10n_mode as $arr_tableFieldMode )
          {
            $tableField     = key( $arr_tableFieldMode );
            $str_l10n_mode  = $arr_tableFieldMode[ $tableField ];

            switch( true )
            {
              case( $str_l10n_mode == 'exclude' ):
                $rows[ $key_in_rowsLoc ][ $tableField ] = $rows[ $key_in_rowsDef ][ $tableField ];
                break;
              case( $str_l10n_mode == 'mergeIfNotBlank' ):
                if( $rows[ $key_in_rowsLoc ][ $tableField ] == false )
                {
                  $rows[ $key_in_rowsLoc ][ $tableField ] = $rows[ $key_in_rowsDef ][ $tableField ];
                }
                break;
              default:
                  // Do nothing;
                break;
              
            }
            
            unset( $str_l10n_mode );
          }
            // Loop through the array with the l10n_mode fields
        }
          // Loop through all rows with localised records
      }
        // Loop through the records with localisation information
    }
      // Loop through the array with localisation information
    
    return $rows;
  }

/**
 * consolidate_rows04handleTableTranslated( )  : In case of a non localised table: Copy values from default to current language record
 *
 * @param	array	$rows   : SQL result rows
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function consolidate_rows04handleTableTranslated( $rows )
  {
      // RETURN : All tables are localised
    if( ! is_array( $this->pObj->arr_realTables_notLocalised ) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'All tables are localised.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'This is strange, if you are using foreign / category tables!';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return $rows;
    }
      // RETURN : All tables are localised

      // Check first row for lang_ol fields
    reset( $rows );
    $firstKey     = key( $rows );
    $arr_lang_ol  = array( );
    foreach( array_keys ( $rows[ $firstKey ] ) as $tableField_ol )
    {
      list( $table_ol ) = explode('.', $tableField_ol);
      if( in_array( $table_ol, $this->pObj->arr_realTables_notLocalised ) )
      {
        $arr_lang_ol[ ] = $tableField_ol;
      }
    }
      // Check first row for lang_ol fields

      // RETURN : there isn't any not localised table
    if( empty ($arr_lang_ol) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Any tables isn\'t localised.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'This is strange, if you are using foreign / category tables!';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return $rows;
    }
      // RETURN : there isn't any not localised table

      // Get default lang overlay values
    $arr_default_lang_ol  = array( );
    $int_count            = 0;
    $localTable           = $this->pObj->localTable;
    $uid_localTable       = $localTable . '.uid';
      // I.e: $sys_language_uid = tx_wine_main.sys_language_uid
    $sys_language_uid     = $GLOBALS[ 'TCA' ][ $localTable ][ 'ctrl' ][ 'languageField' ];

    foreach( ( array ) $rows as $row )
    {
        // Default language record
      if( $row[ $localTable . '.' . $sys_language_uid ] <= 0 )
      {
        foreach( ( array ) $arr_lang_ol as $field_lang_ol )
        {
          $uidLocal = $row[ $uid_localTable ];
          $arr_default_lang_ol[ $uidLocal ][ $int_count ][ 'field_lang_ol' ] = $field_lang_ol;
          $arr_default_lang_ol[ $uidLocal ][ $int_count ][ 'value' ]         = $row[ $field_lang_ol ];
          $int_count++;
        }
      }
      // Default language record
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

      // RETURN : ...
    if( empty ( $arr_default_lang_ol ) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = '$arr_default_lang_ol is empty.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'This is strange.!';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 2 );
      }
      return $rows;
    }
      // RETURN : ...

      // Set lang overlay values in current language record
      // I.e: l18n_parent
    $langPidField = $GLOBALS[ 'TCA' ][ $localTable ][ 'ctrl' ][ 'transOrigPointerField' ]; 
    $int_count    = 0;
    foreach( ( array ) $rows as $key => $row )
    {
        // CONTINUE : current row is the default language
      if( $row[ $localTable . '.' . $sys_language_uid ] <= 0 )
      {
        continue;
      }
        // CONTINUE : current row is the default language

        // Get parent language uid
      $uid_l10n_parent = $row[ $localTable . '.' . $langPidField ];
        // Current language record
      foreach( ( array ) $arr_default_lang_ol[ $uid_l10n_parent ] as $arr_field_value )
      {
        $field_lang_ol                  = $arr_field_value[ 'field_lang_ol' ];
        $value_lang_ol                  = $arr_field_value[ 'value' ];
        $rows[ $key ][ $field_lang_ol ] = $value_lang_ol;
      }
        // Current language record
    }
      // Set lang overlay values in current language record

    unset( $arr_default_lang_ol );
    unset( $arr_lang_ol );

  
    return $rows;
  }

/**
 * consolidate_rows05removeDefault( )  : Remove the default records from $rows, if they have a translation.
 *
 * @param	array	$rows   : SQL result rows
 * @param	string	$table  : The current table name
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     2.0.0
 */
  private function consolidate_rows05removeDefault( $arrUidsKeyDefault, $rows, $table )
  {
    if( empty ( $arrUidsKeyDefault ) )
    {
      return $rows;
    }

      // I.e: $langPidField = 'l18n_parent'
    $tableUid     = $table . '.uid';
    $langPidField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField' ];
    
    foreach( ( array ) $rows as $row )
    {
      $int_languagePid = $row[ $table . '.' . $langPidField ];

        // CONTINUE : record is without l18n_parent
      if( ! in_array( $int_languagePid, array_keys( $arrUidsKeyDefault[ $tableUid ] ) ) )
      {
        continue;
      }
        // CONTINUE : record is without l18n_parent

      foreach( ( array ) $arrUidsKeyDefault[ $tableUid ][ $int_languagePid ][ 'keys_in_rows' ] as $row_default )
      {
        unset( $rows[ $row_default ] );
      }
    }
    
    return $rows;
  }

/**
 * consolidate_rows06setDefaultUid( )  : Set the default language record uid
 *
 * @param	array	$rows   : SQL result rows
 * @param	string	$table  : The current table name
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function consolidate_rows06setDefaultUid( $arrUidsLocalisedDefault, $rows, $table )
  {
      // RETURN : Don't set the default uid
    if( ! $this->conf_localisation['realURL.']['defaultLanguageLink'] )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'realURL.defaultLanguageLink is false';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $rows;
    }
      // RETURN : Don't set the default uid

      // RETURN ; There isn't any localised record
    if( empty( $arrUidsLocalisedDefault ) )
    {
      return $rows;
    }
      // RETURN ; There isn't any localised record
    
    $langPidField = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField']; // I.e: l18n_parent

    foreach( ( array ) $arrUidsLocalisedDefault[ $table . '.uid' ] as $row_localise )
    {
      foreach( ( array ) $row_localise[ 'keys_in_rows' ] as $key_in_rows )
      {
        $rows[ $key_in_rows ][ $table . '.uid' ] = $row_localise[ $langPidField ];
      }
    }
    return $rows;
  }

/**
 * consolidate_rows07languageOverlay( )  : Language Overlay
 *
 * @param	array	$rows   : SQL result rows
 * @param	string	$table  : The current table name
 * @return	array	$rows   : Consolidated rows
 * 
 * @version   4.5.7
 * @since     2.0.0
 */
  public function consolidate_rows07languageOverlay( $rows, $table )
  {
    // Do we have lang_ol fields?
    $arr_lang_ol        = false;
    $conf_tca           = $this->conf_localisation[ 'TCA.' ];
    $str_field_lang_ol  = $conf_tca[ 'field.' ][ 'appendix' ];
    $str_devider        = $conf_tca['value.']['devider'];
    $bool_langPrefix    = $conf_tca['value.']['langPrefix'];

      // DRS
    if( $this->pObj->b_drs_localisation )
    {
      $prompt = 'Fields with the appendix ' . $str_field_lang_ol . ' will be used for language overlaying.';
      t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'If you want to use another appendix please configure: ' 
              . $this->conf_localisation_path . '.TCA.field.appendix.';
      t3lib_div::devlog( '[HELP/LOCALISATION] ' . $prompt, $this->pObj->extKey, 1 );
      if( $bool_langPrefix )
      {
        $prompt = 'Overlay values need the language prefix. I.e. en, de, fr.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want to use overlay values without this prefixes please configure: ' 
                . $this->conf_localisation_path . '.TCA.value.langPrefix.';
        t3lib_div::devlog( '[HELP/LOCALISATION] ' . $prompt, $this->pObj->extKey, 1 );
      }
      if( ! $bool_langPrefix )
      {
        $prompt = 'Overlay values don\'t need any language prefix like en, de, fr.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want to use overlay values with this prefixes please configure: '
                . $this->conf_localisation_path . '.TCA.value.langPrefix.';
        t3lib_div::devlog( '[HELP/LOCALISATION] ' . $prompt, $this->pObj->extKey, 1 );
      }
    }
      // DRS

    $arr_lang_ol  = array( );
    reset($rows);
    $firstKey     = key($rows);
    $int_count    = 0;

      // Check first row for lang_ol fields
    foreach( $rows[ $firstKey ] as $tableField_ol => $value )
    {
      list( $table, $field_ol ) = explode( '.' , $tableField_ol );
      $int_field_len  = strlen( $field_ol ) - strlen( $str_field_lang_ol );
      $field_appendix = substr( $field_ol, $int_field_len );
      $field          = substr( $field_ol, 0, $int_field_len );
      if( $field_appendix == $str_field_lang_ol )
      {
        $arr_lang_ol[ $int_count ][ 'default' ] = $table . '.' . $field;
        $arr_lang_ol[ $int_count ][ 'overlay' ] = $tableField_ol;
        $int_count ++;
      }
    }
      // Check first row for lang_ol fields

    // Process language overlay, if there are lang_ol fields
    if( empty( $arr_lang_ol ) )
    {
      return $rows;
    }
    
      // I.e.: $lang_prefix = 'de'
    $lang_prefix = $GLOBALS['TSFE']->lang; 
      // FOREACH  : rows
    foreach( $rows as $row => $elements )
    {
        // Loop through all lang_ol fields
      foreach( $arr_lang_ol as $row_lang_ol )
      {
          // I.e: $str_overlay = 'en:Lead Story|fr:Accroche'
        $str_overlay = $elements[ $row_lang_ol[ 'overlay' ] ];
          // CONTINUE : lang_ol hasn't any value
        if( empty( $str_overlay ) )
        {
          continue;
        }
          // CONTINUE : lang_ol hasn't any value

        $str_phrase_ol  = false;
        $arr_overlay    = explode( $str_devider, trim( $str_overlay ) );

        switch( $bool_langPrefix )
        {
          case( true ):
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
            foreach( $arr_overlay as $str_phrase )
            {
              if( $lang_prefix . ':' == substr( $str_phrase, 0, strlen( $lang_prefix . ':' ) ) )
              {
                $str_phrase_ol = substr( $str_phrase, strlen( $lang_prefix . ':' ) );
              }
            }
              // Loop through all lang_ol phrases and search for the phrase with a lang_prefix
            break;
          case( false ):
          default:
            $str_phrase_ol = $arr_overlay[ $this->lang_id - 1 ];
            break;
        }

        if( empty( $str_phrase_ol ) )
        {
          continue;
        }

        $rows[ $row ][ $row_lang_ol[ 'default' ] ] = $str_phrase_ol;
      }
        // Loop through all lang_ol fields
    }
      // FOREACH  : rows
    
    return $rows;
  }
  
/**
 * consolidate_rowsNoRow( )  : Returns true, if there isn't any row
 *
 * @param	array	$rows   : SQL result rows
 * @return	boolean         : Returns true, if there isn't any row
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function consolidate_rowsNoRow( $rows )
  {

    if( ! is_array( $rows ) )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Rows aren\'t an array. Is it ok?';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Without any row we don\'t need any consolidation.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
    
    if( count( $rows ) < 1 )
    {
      if( $this->pObj->b_drs_localisation )
      {
        $prompt = 'Rows are #0. Is it ok?';
        t3lib_div::devlog( '[WARN/LOCALISATION] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Without any row we don\'t need any consolidation.';
        t3lib_div::devlog( '[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
    
    return false;
  }












  /***********************************************
  *
  * Little Helpers
  *
  **********************************************/




  /**
 * get_localisedUid( ): Method returns the uid of the localised record.
 *                      The method checks some conditions:
 *                      * It returns the given uid, if current language is the default language
 *                      * It returns the given uid, if the current table isn't localised
 *                      The method returns a localised uid in case of $this->int_localisation_mode is
 *                      * PI1_SELECTED_LANGUAGE_ONLY or
 *                      * PI1_SELECTED_OR_DEFAULT_LANGUAGE
 *
 * @param	string		$table : name of the cirrent table
 * @param	integer		$uid   : current uid
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function get_localisedUid( $table, $uid )
  {
      ////////////////////////////////////////////////////////////////////////////////
      //
      // Init localisation

    $this->localisationConfig( );
      // Init localisation



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN conditions

      // RETURN: Current language is the default language
    if( $this->int_localisation_mode == PI1_DEFAULT_LANGUAGE )
    {
      return $uid;
    }
      // RETURN: Current language is the default language

      // RETURN: Current table isn't localised
    if( $this->is_tableLocalised( $table ) == false )
    {
      return $uid;
    }
      // RETURN: Current table isn't localised
      // RETURN conditions



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Get the localised uid

    switch( $this->int_localisation_mode )
    {
      case( PI1_SELECTED_LANGUAGE_ONLY ):
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        $uid = $this->sql_localisedUid( $table, $uid );
        break;
      default:
          // Do nothing
        if( $this->pObj->b_drs_warn )
        {
          $prompt_01 = ' $this->int_localisation_mode has an undefined value: \'' . $this->int_localisation_mode . '\'.';
          $prompt_02 = 'Current table.uid (' . $table . '.' . $uid . ') won\'t be localised!';
          t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01, $this->pObj->extKey, 2);
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt_02, $this->pObj->extKey, 0);
        }
        break;
    }
      // Get the localised uid



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN the localised uid

    return $uid;
      // RETURN the localised uid
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
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$this->conf_localisation_path.' is configured.', $this->pObj->extKey, 0);
      }
    }
    if (!is_array($this->conf_localisation))
    {
      if ($this->pObj->b_drs_localisation)
      {
        t3lib_div::devlog('[INFO/LOCALISATION] '.$this->conf_localisation_path.' isn\'t configured. We take the global array.', $this->pObj->extKey, 0);
      }
      $this->conf_localisation      = $this->conf['advanced.']['localisation.'];
      $this->conf_localisation_path = 'advanced.localisation';
    }
    // Load the global TypoScript configuration if there isn't any local configuration

  }









  /**
 * is_tableLocalised( ):  Method checks the configuration of the given table in ext_tables.php.
 *                        It returns true, if the table is localised.
 *                        Table must have the fields
 *                        * languageField
 *                        * transOrigPointerField
 *                        There is a warning in the DRS, if this field is missing:
 *                        * transOrigDiffSourceField
 *                        Method allocates values to the class variables
 *                        * $arr_localisedTables;
 *                        * $arr_localisedTableFields
 *                        and the global variables
 *                        * $this->pObj->arr_realTables_localised
 *                        * $this->pObj->arr_realTables_notLocalised
 *
 * @param	string		name of the current table
 * @return	boolean		True, if tbale is localised, false if not.
 * @version 3.9.3
 * @since 3.9.3
 */
  private function is_tableLocalised( $table )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: table.field is checked before

    if( isset( $this->arr_localisedTables[$table] ) )
    {
      return $this->arr_localisedTables[$table];
    }
      // RETURN: table.field is checked before



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Load the TCA, if we don't have an table.columns array

    $this->pObj->objZz->loadTCA($table);
      // Load the TCA, if we don't have an table.columns array



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Is table localised?

    switch( true )
    {
      case( ! isset( $GLOBALS['TCA'][$table]['ctrl']['languageField'] ) ):
          // RETURN table isn't localised
        $this->arr_localisedTables[$table]          = false;
        $this->pObj->arr_realTables_notLocalised[]  = $table;
        if ($this->pObj->b_drs_localisation)
        {
          $prompt = 'Field languageField is missing. ' . $table . ' isn\'t localised.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return $this->arr_localisedTables[$table];
        break;
          // RETURN table isn't localised
      case( ! isset( $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'] ) ):
          // RETURN table isn't localised
        $this->arr_localisedTables[$table]          = false;
        $this->pObj->arr_realTables_notLocalised[]  = $table;
        if ($this->pObj->b_drs_localisation)
        {
          $prompt = 'Field transOrigPointerField is missing. ' . $table . ' isn\'t localised.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return $this->arr_localisedTables[$table];
        break;
          // RETURN table isn't localised
      case( ! isset( $GLOBALS['TCA'][$table]['ctrl']['transOrigDiffSourceField'] ) ):
          // Table will handled like a localised table
        $this->arr_localisedTables[$table]      = true;
        $this->pObj->arr_realTables_localised[] = $table;
        if ($this->pObj->b_drs_localisation)
        {
          $prompt_01 = 'Field transOrigDiffSourceField is missing. Maybe ' . $table . ' isn\'t proper localised.';
          $prompt_02 = 'But ' . $table . ' will handled like a localised table.';
          t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01, $this->pObj->extKey, 2);
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt_02, $this->pObj->extKey, 0);
        }
        break;
          // Table will handled like a localised table
      default:
          // Table is localised
        $this->arr_localisedTables[$table]      = true;
        $this->pObj->arr_realTables_localised[] = $table;
          // Table is localised
    }
      // Is table localised?



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Set the field names for sys_language_content and for l10n_parent

    $this->arr_localisedTableFields[$table]['id_field']   = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
    $this->arr_localisedTableFields[$table]['pid_field']  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];
      // Set the field names for sys_language_content and for l10n_parent



      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN is table localised?

    return $this->arr_localisedTables[$table];
      // RETURN is table localised?

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
      return false;
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
      return false;
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
        $arr_langFields = false;
      }
    }
    return $arr_langFields;
  }









  /***********************************************
  *
  * SQL
  *
  **********************************************/




  /**
 * sql_getLanguages( ): Get the rows of languages out of the table sys_language.
 *                      The method returns null, if there isn't any row in the table.
 *                      If there is a row, the default language will be the first row.
 *                      If there is a page TSconfig for the default language,
 *                      the row of the default language get the label and flag from
 *                      the page TSconfig
 *
 * @return	array		$rows: rows of lanuages. Null, if table sys_language is empty.
 * @version 3.9.3
 * @since 3.9.3
 */
  public function sql_getLanguages( )
  {
    $rows = null;



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Get the query

      // Values
    $select_fields  = 'uid, title, flag';
    $from_table     = 'sys_language';
    $where_clause   = 'pid = 0 AND hidden = 0';
    $groupBy        = null;
    $orderBy        = null;
    $limit          = null;
      // Values

      // Query for evaluation
    $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Query for evaluation

      // DRS - Development Reporting System
    if ( $this->pObj->b_drs_localisation || $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog('[INFO/SQL+LOCALISATION] ' . $query, $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System
      // Get the query



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Execute the query

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute the query



      ///////////////////////////////////////////////////////////////////////////////
      //
      // ERROR

      // ERROR: debug report in the frontend
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun_3x->query = $query;
      $this->pObj->objSqlFun_3x->error = $error;
      $arr_result = $this->pObj->objSqlFun_3x->prompt_error( );
      $prompt     = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      echo $prompt;
    }
//    if( ! empty( $error ) )
//    {
//      if( $this->debugging )
//      {
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
//        echo $str_warn.$str_header.$str_prompt;
//      }
//    }
//      // ERROR: debug report in the frontend
//
//      // DRS - Development Reporting System
//    if( ! empty( $error ) )
//    {
//      if( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
//      }
//    }
//      // DRS - Development Reporting System
//      // ERROR



      //////////////////////////////////////////////////////////////////////////
      //
      // Handle the SQL result

      // LOOP: SQL result to rows
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[$row['flag']] = $row;
    }
      // LOOP: SQL result to rows

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result($res);
      // Handle the SQL result



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN rows are empty

    if( empty( $rows ) )
    {
      if ( $this->pObj->b_drs_warn )
      {
        $prompt_01 =  'Any language isn\'t configured.';
        $prompt_02 =  'Please configure your backend languages, ' .
                      'if you will have any unexpected result in context with localisation.';
        t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01,  $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/LOCALISATION] ' . $prompt_02,  $this->pObj->extKey, 1);
      }
      return $rows;
    }
      // RETURN rows are empty



      //////////////////////////////////////////////////////////////////////////
      //
      // Get label and flag of the default language out of the page TSconfig

    $pid            = $GLOBALS['TSFE']->id;
    $page_TSconfig  = t3lib_BEfunc :: getPagesTSconfig( $pid, $rootLine='', $returnPartArray=0 );
    $title          = $page_TSconfig['mod.']['SHARED.']['defaultLanguageLabel'];
      // Take the name without extension. I.e de.gif will become de
    list($flag)     = explode( '.', $page_TSconfig['mod.']['SHARED.']['defaultLanguageFlag'] );
    if( empty( $title ) )
    {
      $title = 'default';
      if( $this->pObj->b_drs_warn )
      {
        $prompt_01 = 'Any label insn\'t configured for the default language!';
        $prompt_02 = 'Please configure in your page TSconfig mod.SHARED.defaultLanguageLabel.';
        t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01,  $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/LOCALISATION] ' . $prompt_02,  $this->pObj->extKey, 1);
      }
    }
    if( empty( $flag ) )
    {
      $flag = null;
      if( $this->pObj->b_drs_warn )
      {
        $prompt_01 = 'Any flag insn\'t configured for the default language!';
        $prompt_02 = 'Please configure in your page TSconfig mod.SHARED.defaultLanguageFlag.';
        t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01,  $this->pObj->extKey, 2);
        t3lib_div::devlog('[HELP/LOCALISATION] ' . $prompt_02,  $this->pObj->extKey, 1);
      }
    }
      // Get label and flag of the default language out of the page TSconfig



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the default language at the first position

    $rows = array($flag => array( 'uid' => '0', 'title' => $title, 'flag' => $flag ) ) + $rows;
      // Set the default language at the first position


      //////////////////////////////////////////////////////////////////////////
      //
      // Set current language as first row

//    $int_currLangUid = $GLOBALS['TSFE']->sys_language_content;
//    if( isset( $rows[$int_currLangUid] ) )
//    {
//      $arr_currLangUid = $rows[$int_currLangUid];
//      unset( $rows[$int_currLangUid] );
//      $rows = array( $int_currLangUid => $arr_currLangUid ) + $rows;
//    }
    $flag = $GLOBALS['TSFE']->lang;
    if( empty( $flag ) || ( $flag == 'en' ))
    {
      $flag = 'gb';
    }
    if( isset( $rows[$flag] ) )
    {
      $arr_currLangUid = $rows[$flag];
      unset( $rows[$flag] );
      $rows = array( $flag => $arr_currLangUid ) + $rows;
    }
      // Set current language as first row



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN the rows

    return $rows;
      // RETURN the rows
  }









  /**
 * sql_localisedUid( ): Get the uid of the localised record
 *
 * @param	string		name of the current table
 * @param	integer		uid of the cirrent row
 * @return	void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function sql_localisedUid( $table, $uid )
  {
      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN conditions

    $bool_return = true;
    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
          // RETURN: Current language is the default language
        $bool_return = true;
        if ($this->pObj->b_drs_localisation)
        {
          $prompt = '$this->int_localisation_mode is PI1_DEFAULT_LANGUAGE. SQL query won\'t be executed.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        break;
          // RETURN: Current language is the default language
      case( PI1_SELECTED_LANGUAGE_ONLY ):
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
          // DON'T RETURN: localisation mode
        $bool_return = false;
        break;
          // DON'T RETURN: localisation mode
      default:
          // RETURN: localisation mode is undefined
        $bool_return = true;
        if( $this->pObj->b_drs_warn )
        {
          $prompt_01 = '$this->int_localisation_mode has an undefined value: \'' . $this->int_localisation_mode . '\'.';
          $prompt_02 = 'SQL query won\'t be executed.';
          t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01, $this->pObj->extKey, 2);
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt_02, $this->pObj->extKey, 0);
        }
        break;
          // RETURN: localisation mode is undefined
    }
      // RETURN
    if( $bool_return )
    {
      return $uid;
    }
      // RETURN
      // RETURN conditions



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Get the query

      // Default values
    $l10n_parent      = $this->arr_localisedTableFields[$table]['pid_field'];
    $sys_language_uid = $this->arr_localisedTableFields[$table]['id_field'];
    $select_fields    = 'uid, ' . $l10n_parent . ', ' . $sys_language_uid;
    $from_table       = $table;
    $groupBy          = null;
    $orderBy          = null;
    $limit            = null;
      // Default values

      // Get the where clause
    switch( $this->int_localisation_mode )
    {
      case( PI1_SELECTED_LANGUAGE_ONLY ):
        if( intval( $this->lang_id ) > 0 )
        {
//        $where_clause = '( ( uid = \'' . $uid . '\' OR ' . $l10n_parent . ' = \'' . $uid . '\' ) AND '.
//                        $sys_language_uid . ' = ' . $this->lang_id . ' )';
          $where_clause = '( ' . $l10n_parent . ' = \'' . $uid . '\' AND '.
                          $sys_language_uid . ' = ' . $this->lang_id . ' )';
        }
        if( intval( $this->lang_id ) <= 0 )
        {
          $where_clause = 'uid = \'' . $uid . '\'';
        }
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        $where_clause = '( uid = \'' . $uid . '\' OR ' . $l10n_parent . ' = \'' . $uid . '\' )';
        break;
    }
      // Get the where clause

      // Query for evaluation
    $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Query for evaluation

      // DRS - Development Reporting System
    if ($this->pObj->b_drs_localisation || $this->pObj->b_drs_sql)
    {
      t3lib_div::devlog('[INFO/LOCALISATION] ' . $query, $this->pObj->extKey, 0);
    }
//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . ')', $query );
//    }
      // DRS - Development Reporting System
      // Get the query



      ////////////////////////////////////////////////////////////////////////////////
      //
      // Execute the query

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
                                    (
                                      $select_fields,
                                      $from_table,
                                      $where_clause,
                                      $groupBy,
                                      $orderBy,
                                      $limit
                                    );
      // Execute the query



      ///////////////////////////////////////////////////////////////////////////////
      //
      // ERROR

      // ERROR: debug report in the frontend
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun_3x->query = $query;
      $this->pObj->objSqlFun_3x->error = $error;
      $arr_result = $this->pObj->objSqlFun_3x->prompt_error( );
      $prompt     = $arr_result['error']['header'] . $arr_result['error']['prompt'];
      echo $prompt;
    }
//    if( ! empty( $error ) )
//    {
//      if( $this->debugging )
//      {
//        $str_warn    = '<p style="border: 1em solid red; background:white; color:red; font-weight:bold; text-align:center; padding:2em;">'.$this->pObj->pi_getLL('drs_security').'</p>';
//        $str_header  = '<h1 style="color:red">'.$this->pObj->pi_getLL('error_sql_h1').'</h1>';
//        $str_prompt  = '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$error.'</p>';
//        $str_prompt .= '<p style="font-family:monospace;font-size:smaller;padding-top:2em;">'.$query.'</p>';
//        echo $str_warn.$str_header.$str_prompt;
//      }
//    }
//      // ERROR: debug report in the frontend
//
//      // DRS - Development Reporting System
//    if( ! empty( $error ) )
//    {
//      if( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog('[ERROR/SQL] '.$query,  $this->pObj->extKey, 3);
//        t3lib_div::devlog('[ERROR/SQL] '.$error,  $this->pObj->extKey, 3);
//      }
//    }
//      // DRS - Development Reporting System
//      // ERROR



      //////////////////////////////////////////////////////////////////////////
      //
      // Handle the SQL result

      // LOOP: SQL result to rows
    $rows             = array( );
    $int_rows_counter = 0;
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      if( $row[$l10n_parent] == 0 )
      {
        $arr_uid['def_language'] = $row['uid'];
      }
      if( $row[$sys_language_uid] == $this->lang_id )
      {
        $arr_uid['sys_language'] = $row['uid'];
      }
      $rows[$int_rows_counter] = $row;
      $int_rows_counter++;
    }
      // LOOP: SQL result to rows

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result($res);
      // Handle the SQL result



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the uid

    $uid_origin = $uid;
    $uid        = $arr_uid['sys_language'];
      // Try to set localised uid
    switch( $this->int_localisation_mode )
    {
      case( PI1_SELECTED_LANGUAGE_ONLY ):
        if( empty( $uid ) )
        {
          if( $this->pObj->b_drs_warn )
          {
            $prompt_01 = 'Record ' . $table . '.' . $uid_origin .  ' doesn\'t have any localised record!';
            $prompt_02 = $query;
            t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_01,  $this->pObj->extKey, 2);
            t3lib_div::devlog('[WARN/LOCALISATION] ' . $prompt_02,  $this->pObj->extKey, 2);
          }
//          $uid = $arr_uid['def_language'];
//          $uid = null;
        }
        break;
      case( PI1_SELECTED_OR_DEFAULT_LANGUAGE ):
        if( empty( $uid ) )
        {
          $uid = $arr_uid['def_language'];
        }
        break;
    }
      // Try to set localised uid
      // DRS - Development Reporting System
    if( $this->pObj->b_drs_localisation )
    {
      switch( true )
      {
        case( $this->lang_id == 0 ):
          $prompt = 'sys_language_uid is \'' . $this->lang_id . '\'. The default language record is ' . $table . '.' . $uid_origin . '.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
          break;
        case( $uid === null ):
        case( $uid === 0 ):
          $prompt = 'The default language record ' . $table . '.' . $uid_origin . ' hasn\'t any localised record.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
          break;
        case( $uid_origin == $uid ):
          $prompt = 'The default language record ' . $table . '.' . $uid_origin . ' hasn\'t any localised record with the sys_language_uid ' . $this->lang_id . '.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
          break;
        case( $uid_origin != $uid ):
          $prompt = 'Uid of default language record is ' . $table . '.' . $uid_origin . ', uid of the localised record is ' . $table . '.' . $uid . '.';
          t3lib_div::devlog('[INFO/LOCALISATION] ' . $prompt, $this->pObj->extKey, 0);
          break;
      }
    }
      // DRS - Development Reporting System
      // Get the uid


      ////////////////////////////////////////////////////////////////////////////////
      //
      // RETURN the localised uid

    return intval( $uid );
      // RETURN the localised uid
  }

/**
 * zzDevPromptRows( )  : Just for development
 *
 * @param	array	$rows     : SQL result rows
 * @param	string	$maxRows  : number of rows for prompting
 * @return	void
 * @internal    #46062
 * 
 * @version   4.5.7
 * @since     4.5.7
 */
  private function zzDevPromptRows( $promptForDev, $rows, $maxRows=10 )
  {
    if( ! $promptForDev )
    {
      return;
    }

    $rows_prompt  = array( );
    $int_count    = 0;
    foreach( $rows as $key => $row )
    {
      if( $int_count >= $maxRows )
      {
        break;
      }
      $rows_prompt[ $key ] = $row;
      $int_count++;
    }

    $this->pObj->dev_var_dump( $rows_prompt );
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_localisation_3x.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_localisation_3x.php']);
}

?>