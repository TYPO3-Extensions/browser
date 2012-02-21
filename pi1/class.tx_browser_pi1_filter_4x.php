<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_filter_4x bundles methods for rendering and processing filters and category menues.
 * 4x means: with SQL engine 4.x
 *
 * @author       Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package      TYPO3
 * @subpackage   browser
 *
 * @version      3.9.9
 * @since        3.9.9
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *
 */
class tx_browser_pi1_filter_4x {

  
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


  var $bool_dontLocalise      = null;
  var $int_localisation_mode  = null;












  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj) {
    $this->pObj = $pObj;
  }









/**
 * init( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function init( )
  {

    if( ! isset( $this->int_localisation_mode ) )
    {
      $this->int_localisation_mode = $this->pObj->objLocalise->localisationConfig( );
    }

    switch( $this->int_localisation_mode )
    {
      case( PI1_DEFAULT_LANGUAGE ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE. There isn\' any need to localise!';
        break;
      case( PI1_DEFAULT_LANGUAGE_ONLY ):
        $this->bool_dontLocalise = true;
        $prompt = 'Localisation mode is PI1_DEFAULT_LANGUAGE_ONLY. There isn\' any need to localise!';
        break;
      default:
        $this->bool_dontLocalise = false;
        $prompt = 'Localisation mode is enabled';
        break;
    }
    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // Do we need translated/localised records?
  }


    
 /***********************************************
  *
  * HTML
  *
  **********************************************/

/**
 * get_htmlFilters( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 * 
 * @version 3.9.9
 * @since   3.9.9
 */
  public function get_htmlFilters( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Init localisation
    $this->init( );

      // LOOP each filter
    foreach( ( array ) $this->conf_view['filter.'] as $tableWiDot => $fields )
    {
      foreach( ( array ) $fields as $field => $confField )
      {
        if( rtrim($field, '.') != $field )
        {
          continue;
        }
        $tableField = $tableWiDot . $field;
        $arr_return = $this->get_htmlFilter( $tableField );
        if( $arr_return['error']['status'] )
        {
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
          return $arr_return;
        }
      }
    }
      // LOOP each filter

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * get_htmlFilter( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function get_htmlFilter( $tableField )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    $arr_return = $this->sql( $tableField );
    if( $arr_return['error']['status'] )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
    unset( $arr_return );

  // Set HTML object

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * sql( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql( $tableField )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    $select = $this->sql_select( $tableField );
    // Get SQL result
      // Get SELECT statement
      // Get GROUP BY
      // Build SELECT statement
      // Exec SELECT

    var_dump( __METHOD__, $tableField, $select );


    $str_header  = '<h1 style="color:red;">' . __METHOD__ . '</h1>';
    $str_prompt  = '<p style="color:red;font-weight:bold;">Development ' . $tableField . '</p>';
    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = $str_header;
    $arr_return['error']['prompt'] = $str_prompt;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $arr_return;
  }









/**
 * sql_select( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select( $tableField )
  {
    $conf_view = $this->conf_view;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );




      /////////////////////////////////////////////////////////////////
      //
      // Build the SQL query.

    list ($table, $field) = explode('.', $tableField);

      // SELECT
    $select = $conf_view['filter.'][$table . '.'][$field . '.']['sql.']['select'];
    if( ! empty ( $select ) )
    {
      $select = $this->pObj->objZz->cleanUp_lfCr_doubleSpace( $select );
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
      {
        $prompt = 'filter.' . $tableField . '.sql.select: ' . $select;
        t3lib_div :: devlog( '[INFO/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    if( empty ( $select ) )
    {
      $select = $table . ".uid AS 'uid'," . PHP_EOL .
      "         " . $table . "." . $field . " AS 'value'," . PHP_EOL;
      if ( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql )
      {
        $prompt = 'filter.' . $tableField . '.sql.select isn\'t set. It\'s ok. Generated SELECT is: ' . $select;
        t3lib_div :: devlog( '[INFO/FILTER+SQL] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
      // SELECT

      // SELECT + treeparentField
    $select = $this->sql_select_treeview( $tableField, $select );

      // SELECT + localisation
    $select = $this->sql_select_ll( $tableField, $select );


      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $select;
  }









/**
 * sql_select_ll( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_ll( $tableField, $select )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // RETURN no localisation
    if( $this->bool_dontLocalise )
    {
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }
      // RETURN no localisation


    list ($table, $field) = explode('.', $tableField);

    $select = $this->sql_select_ll_localTable( $tableField, $select );
    $select = $this->sql_select_ll_foreignTable( $tableField, $select );

    $this->pObj->objZz->loadTCA( $table );

    var_dump( __METHOD__, $arr_local_select );


    return $select;
  }









/**
 * sql_select_ll_localTable( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_ll_localTable( $tableField, $select )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    list ($table, $field) = explode('.', $tableField);

    $this->pObj->objZz->loadTCA( $table );

      // RETURN no languageField
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['languageField'] ) )
    {
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised localTable: TCA.' . $table . 'ctrl.languageField is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }
      // RETURN no languageField

      // RETURN no transOrigPointerField
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'] ) )
    {
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised localTable: TCA.' . $table . 'ctrl.transOrigPointerField is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }
      // RETURN no transOrigPointerField

    $languageField          = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
    $transOrigPointerField  = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];

    $languageField          = $table . '.' . $languageField;
    $transOrigPointerField  = $table . '.' . $transOrigPointerField;

    $select = $select . ' ' .
              $languageField . ' AS \'sys_language_uid\' ' .
              $transOrigPointerField . ' AS \'l10n_parent\'';

    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      $prompt = $table . ' is a localised localTable. SELECT is localised.';
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }

    return $select;
  }









/**
 * sql_select_ll_foreignTable( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_ll_foreignTable( $tableField, $select )
  {
    $conf_view = $this->conf_view;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

    list ($table, $field) = explode('.', $tableField);

    $this->pObj->objZz->loadTCA( $table );

    $lang_ol        = $this->pObj->objLocalise->conf_localisation['TCA.']['field.']['appendix'];
    $field_lang_ol  = $field . $lang_ol;

      // RETURN no languageField
    if( ! isset ($GLOBALS['TCA'][$table]['columns'][$field_lang_ol] ) )
    {
      if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
      {
        $prompt = $table . ' isn\'t a localised foreignTable: ' .
                  'TCA.' . $table . 'columns.' . $field_lang_ol . ' is missing.';
        t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }
      // RETURN no languageField

    $tableField = $table . '.' . $field_lang_ol;
    $select = $select . ' ' .
              $tableField . ' AS \'lang_ol\'';

    if( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_localisation )
    {
      $prompt = $table . ' is a localised foreignTable. SELECT is localised.';
      t3lib_div::devlog( '[INFO/FILTER+SQL+LOCALISATION] ' . $prompt, $this->pObj->extKey, 0 );
    }

    return $select;
  }









/**
 * sql_select_treeview( ):  It renders filters and category menus in HTML.
 *                    A rendered filter can be a category menu, a checkbox, radiobuttons and a selectbox
 *
 * @return	array
 *
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sql_select_treeview( $tableField, $select )
  {
    $conf_view = $this->conf_view;

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );


    list ($table, $field) = explode('.', $tableField);



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $treeviewEnabled

      // #32223, 120120, dwildt+
    $cObj_name        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled'];
    $cObj_conf        = $conf_view['filter.'][$table . '.'][$field . '.']['treeview.']['enabled.'];
    $treeviewEnabled  = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );

    if( ! $treeviewEnabled )
    {
      if( $this->pObj->b_drs_filter )
      {
        $prompt = 'treeview is disabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
        t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }

    if( $this->pObj->b_drs_filter )
    {
      $prompt = 'treeview is enabled. Has an effect only in case of cps_tcatree and a proper TCA configuration.';
      t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }

      // #32223, 120119, dwildt+

      // Load the TCA for the current table
    $this->pObj->objZz->loadTCA( $table );
      // Table has a treeParentField
    if( ! isset( $GLOBALS['TCA'][$table]['ctrl']['treeParentField'] ) )
    {
      if( $this->pObj->b_drs_filter )
      {
        $prompt = 'TCA.' . $table . '.ctrl.treeParentField isn\'t set.';
        t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $select;
    }

      // Add treeParentField to the SELECT statement
    $treeParentField = $GLOBALS['TCA'][$table]['ctrl']['treeParentField'];
    $select = $select . "         " . $table . "." . $treeParentField . " AS 'treeParentField'," . PHP_EOL;
      // Add treeParentField to the SELECT statement
    $this->pObj->objFilter->arr_tablesWiTreeparentfield[] = $table;

    if( $this->pObj->b_drs_filter )
    {
      $prompt = 'TCA.' . $table . '.ctrl.treeParentField is set. ' . $table . ' is configured for a tree view.';
      t3lib_div :: devlog( '[INFO/FILTER] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // Table has a treeParentField

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return $select;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_filter_4x.php']);
}
?>
