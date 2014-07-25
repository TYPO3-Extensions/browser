<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

/**
 * The class tx_browser_pi1_template bundles template methods for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 5.0.0
 * @since 1.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   85: class tx_browser_pi1_template
 *  149:     function __construct($parentObj)
 *  168:     function tmplSearchBox($template)
 *  400:     function resultphrase()
 *  578:     function tmplListview($template, $rows)
 * 1267:     function setDisplaySingle()
 * 1286:     private function setGlobalElementsOfFirstRow($rows)
 * 1309:     private function setGlobalRows($rows)
 * 1327:     private function setUploadFolder()
 * 1346:     public function tmplSingleview($template, $rows)
 * 1464:     private function tmplSingleviewBackbutton($template)
 * 1504:     private function tmplSingleviewImage($template, $handleAs, $elements, $arr_TCAitems)
 * 1585:     private function tmplSingleviewNoItemMessage($template)
 * 1620:     private function tmplSingleviewText($template, $handleAs, $elements)
 * 1671:     private function tmplSingleviewTitle($template, $handleAs, $elements, $arr_TCAitems)
 * 1746:     function tmplHead($template)
 * 2388:     private function tmplRow($elements, $subpart, $template)
 * 2735:     private function tmplRowFieldOfSingleViewIsEmpty($bool_dontHandleEmptyValues, $value)
 * 2777:     private function tmplRowIsExtraUidField($uidField)
 * 2820:     private function tmplRowIsDefaultDesign($template)
 * 2905:     private function tmplRowIsEmpty($elements)
 * 2936:     private function tmplRegisterCountingColumns($i_count_element, $maxColumns)
 * 2959:     private function tmpl_marker()
 * 2981:     private function tmpl_marker_rmFilter()
 * 3012:     private function tmpl_rmFields()
 * 3075:     private function cObjDataAdd($elements)
 * 3111:     private function cObjDataReset()
 * 3141:     private function groupBy_get_groupname($elements)
 * 3176:     private function groupBy_remove($template)
 * 3194:     private function groupBy_stdWrap($elements)
 * 3268:     private function groupBy_verify($template)
 * 3344:     function render_handleAs($elements, $handleAs, $markerArray)
 * 3490:     private function hook_template_elements()
 * 3531:     private function hook_template_elements_transformed()
 * 3571:     private function hook_row_single_consolidated()
 * 3625:     private function updateWizard($check, $lDisplayList)
 * 3682:     private function tmplHeadWiSelectBoxOrderBySorting($arr_ts, $arr_values, $tableField)
 * 3847:     private function orderValues($arr_values, $conf_tableField)
 *
 * TOTAL FUNCTIONS: 37
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_template
{

  //////////////////////////////////////////////////////
  //
    // Variables set by the pObj (by class.tx_browser_pi1.php)
  // [Array] The current TypoScript configuration array
  var $conf = false;
  // [Integer] The current mode (from modeselector)
  var $mode = false;
  // [String] 'list' or 'single': The current view
  var $view = false;
  // [Array] The TypoScript configuration array of the current view
  var $conf_view = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  var $conf_path = false;
  // [Booelan] If true, workflow will executed in case of empty rows too
  var $ignore_empty_rows_rule = false;
  // Variables set by the pObj (by class.tx_browser_pi1.php)
  //////////////////////////////////////////////////////
  //
    // Variables set by this class
  // [Array] Array with fields from orderBy from TS
  var $arr_orderBy;
  // [Array] Array with fields from functions.clean_up.csvTableFields from TS
  public $arr_rmFields = null;
  // #47823, 130502, dwildt, 2+
//    // [Array] Current cObj->data
//  private $dataCobj   = null;
  // #47823, 130502, dwildt, 2+
  // [Array] Current cObj->data
  private $dataLocalCobj = null;
  // #47823, 130502, dwildt, 2+
//    // [Array] Current TSFE->cObj->data
//  private $dataTsfe   = null;
  // [Array] Local or global TypoScript array with the displaySingle properties
  var $lDisplaySingle;
  // [Array] Local or global TypoScript array with the displayList properties
  var $lDisplayList;
  // [array] Array with default markers
  var $markerArray = null;
  // [Integer] Amount of elements, which should dislayed
  var $max_elements = null;
  // [Boolean] true, if rows should grouped, false, if rows shouldn't grouped
  var $bool_groupby;
  // [Array] Array [table.field] = $value.
  // It is needed by social media bookmarks in a default single view.
  var $arr_curr_value = false;

  // 3.4.0
  // Variables set by this class

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
  }

  /*   * *********************************************
   *
   * Rendering HTML
   *
   * ******************************************** */

  /**
   * cObjDataAdd( ):
   *
   * @param	array		$elements: The current record
   * @return	void
   * @internal  #44858
   * @version   4.5.6
   * @since     4.4.4
   */
  private function cObjDataAdd( $elements )
  {
    $this->dataLocalCobj = $this->pObj->local_cObj->data;
    $this->pObj->objCObjData->add( $elements );
    $this->pObj->local_cObj->data = $GLOBALS[ 'TSFE' ]->tx_browser_pi1->cObj->data;
  }

  /**
   * cObjDataReset( ):
   *
   * @return	void
   * @internal  #44858
   * @version   4.5.6
   * @since     4.4.4
   */
  private function cObjDataReset()
  {
    $this->pObj->objCObjData->reset();
    $this->pObj->local_cObj->data = $this->dataLocalCobj;
    unset( $this->dataLocalCobj );
  }

  /*   * *********************************************
   *
   * GroupBy
   *
   * ******************************************** */

  /**
   * Get the name of the group in the current record, if there is one.
   *
   * @param	array		$elements: The current record
   * @return	string		$str_return: Value of the group field. FALSE, if we aren't in group mode
   * @version   4.2.0
   * @since     3.3.0
   */
  private function groupBy_get_groupname( $elements )
  {
    if ( !$this->bool_groupby )
    {
      return false;
    }

    $key = trim( $this->pObj->objSqlFun_3x->get_orderBy_tableFields( $this->pObj->conf_sql[ 'groupBy' ] ) );

    // #43808, 121209, dwildt, +
    if ( !isset( $elements[ $key ] ) )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'GroupBy field is missing! Records won\'t be groupped.';
        t3lib_div::devLog( '[ERROR/TEMPLATING] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'GroupBy field must be part of the select statement from version 4.0.';
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Current GroupBy field is: ' . $key;
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 2 );
      }
    }
    // #43808, 121209, dwildt, +

    return $elements[ $key ];
  }

  /**
   * Remove all GROUPBY marker
   *
   * @param	string		$template: The HTML template with the groupby-markers
   * @return	string		$template: The HTML template without the groupby-markers
   * @version   3.3.7
   * @since     3.3.0
   */
  private function groupBy_remove( $template )
  {
    $template = $this->pObj->cObj->substituteSubpart( $template, '###GROUPBYHEAD###', '', true );
    $template = str_replace( '<!-- ###GROUPBY### begin -->', '', $template );
    $template = str_replace( '<!-- ###GROUPBY### end -->', '', $template );
    $template = str_replace( '<!-- ###GROUPBYBODY### begin -->', '', $template );
    $template = str_replace( '<!-- ###GROUPBYBODY### end -->', '', $template );
    return $template;
  }

  /**
   * groupBy_stdWrap: Wrap the group name, if it has a stdWrap
   *
   * @param	array		$elements: The current record
   * @return	string		$str_return: Value of the group field wrapped by stdWrap if we have a TSconfig
   * @version   4.4.0
   * @since     3.3.0
   */
  private function groupBy_stdWrap( $elements )
  {
    // THIS is a method with a general task. todo: Generalie this method. dwildt, 100615




    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];

    $lConfCObj = false;
    $str_value = false;

    // Get value from SQL table
    $str_tableField = trim( $this->pObj->objSqlFun_3x->get_orderBy_tableFields( $this->pObj->conf_sql[ 'groupBy' ] ) );
    list($table, $field) = explode( '.', $str_tableField );
    $str_value = $elements[ $table . '.' . $field ];
    // Get value from SQL table
    // Do we have a stdWrap?
    $bool_stdWrap = true;
    if ( !isset( $conf_view[ $table . '.' ][ $field ] ) )
    {
      $bool_stdWrap = false;
    }
    if ( !is_array( $conf_view[ $table . '.' ][ $field . '.' ] ) )
    {
      $bool_stdWrap = false;
    }
    // Do we have a stdWrap?
    // RETURN without stdWrap
    if ( !$bool_stdWrap )
    {
      // DRS- Developement Reporting System
      if ( $this->pObj->boolFirstRow && ($this->pObj->b_drs_templating || $this->pObj->b_drs_sql) )
      {
        t3lib_div::devLog( '[INFO/TEMPLATING+SQL] GroupBy field ' . $str_tableField . ' hasn\'t any stdWrap.', $this->pObj->extKey, 0 );
        t3lib_div::devLog( '[HELP/TEMPLATING+SQL] If you want a stdWrap, please configure ' . $str_tableField, $this->pObj->extKey, 1 );
      }
      // DRS- Developement Reporting System: Any Case didn't matched above
      return $str_value;
    }
    // RETURN without stdWrap
    // RETURN with stdWrap
    $lConfCObj[ '10' ] = $conf_view[ $table . '.' ][ $field ];
    $lConfCObj[ '10.' ] = $conf_view[ $table . '.' ][ $field . '.' ];
    // #44316, 130104, dwildt, 1-
//    $lConfCObj = $this->pObj->objMarker->substitute_marker_recurs($lConfCObj, $elements);
    // #44316, 130104, dwildt, 4+
    $currElements = $this->pObj->elements;
//    $this->pObj->elements = $this->pObj->elements + $elements;
    $this->pObj->elements = $elements;
    $lConfCObj = $this->pObj->objMarker->substitute_tablefield_marker( $lConfCObj );
    $this->pObj->elements = $currElements;

    $str_value = $this->pObj->objWrapper4x->general_stdWrap( $this->pObj->local_cObj->COBJ_ARRAY( $lConfCObj, $ext = '' ), false );

    return $str_value;
    // RETURN with stdWrap
  }

  /**
   * Verifying the GROUPBY configuration. If groupby isn't configured in TypoScript, GROUPBY marker in HTML
   * template will be removed. If groupby is configured in TypoScript, but the template hasn't any GROUPBY marker
   * there will be a log in devlog.
   *
   * @param	string		$template: The HTML template with the GROUPBY-markers
   * @return	string		$template: The HTML template with or without the GROUPBY-by-markers
   * @version   3.3.7
   * @since     3.3.0
   */
  private function groupBy_verify( $template )
  {
    // Do we have a TypoScript group by configuration?
    $this->bool_groupby = false;
    if ( isset( $this->pObj->conf_sql[ 'groupBy' ] ) && $this->pObj->conf_sql[ 'groupBy' ] != '' )
    {
      $this->bool_groupby = true;
    }
    // Do we have a TypoScript group by configuration?
    // RETURN if we have an HTML without any groupby marker
    if ( strpos( $template, '###GROUPBY###' ) === false )
    {
      if ( $this->bool_groupby )
      {
        // DRS- Developement Reporting System
        if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_error )
        {
          t3lib_div::devLog( '[ERROR/TEMPLATING] TypoScript is configured with groupby. ' .
                  'But your template doesn\'t contain any marker like ###GROUPBY###', $this->pObj->extKey, 3 );
          t3lib_div::devLog( '[WARN/TEMPLATING] Your data won\'t grouped.', $this->pObj->extKey, 2 );
          t3lib_div::devLog( '[HELP/TEMPLATING] Please configure your HTML template.', $this->pObj->extKey, 1 );
        }
        // DRS- Developement Reporting System
      }
      return $template;
    }
    // RETURN if we have an HTML without any groupby marker
    // Edit the template
    // Don't change anything
    if ( $this->bool_groupby )
    {
      // Do nothing
      // DRS- Developement Reporting System
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devLog( '[OK/TEMPLATING] TypoScript is configured with groupby. ' .
                'And the template contains the marker ###GROUPBY###.', $this->pObj->extKey, -1 );
      }
      // DRS- Developement Reporting System
    }
    // Don't change anything
    // Remove all GROUPBY marker
    if ( !$this->bool_groupby )
    {
      // Remove all groupby markers
      $template = $this->groupBy_remove( $template );
      // DRS- Developement Reporting System
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devLog( '[INFO/TEMPLATING] TypoScript is configured without groupby. ' .
                'All markers ###GROUPBY### will removed in the template.', $this->pObj->extKey, 0 );
      }
      // DRS- Developement Reporting System
    }
    // Remove all GROUPBY marker
    // Edit the template

    return $template;
  }

  /*   * *********************************************
   *
   * Handle As
   *
   * ******************************************** */

  /**
   * hook_row_list_consolidated() :
   *
   * @return	void
   * @version 5.0.0
   * @since 3.0.0
   */
  private function hook_row_list_consolidated()
  {
    // RETURN : Hook isn't used
    if ( !is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_list_consolidated' ] ) )
    {
      // RETURN : no DRS
      if ( !$this->pObj->b_drs_hooks )
      {
        return;
      }
      // RETURN : DRS prompt
      $prompt = 'Any third party extension doesn\'t use the HOOK rows_list_consolidated.';
      t3lib_div::devlog( '[INFO/HOOK] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/';
      t3lib_div::devlog( '[HELP/HOOK] ' . $prompt, $this->pObj->extKey, 1 );
      return;
    }

    $_params = array( 'pObj' => &$this );
    foreach ( ( array ) $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_list_consolidated' ] as $_funcRef )
    {
      t3lib_div::callUserFunction( $_funcRef, $_params, $this );
    }

    if ( $this->pObj->b_drs_hooks )
    {
      return;
    }

    // DRS - Development Reporting System
    $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_list_consolidated' ] );
    $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'rows_list_consolidated' ] );
    $csv_ext = implode( ',', $arr_ext );
    if ( $i_extensions == 1 )
    {
      t3lib_div::devlog( '[INFO/HOOK] The third party extension ' . $csv_ext . ' uses the HOOK rows_list_consolidated.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1 );
    }
    if ( $i_extensions > 1 )
    {
      t3lib_div::devlog( '[INFO/HOOK] The third party extensions ' . $csv_ext . ' use the HOOK rows_list_consolidated.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1 );
    }
    return;
  }

  /**
   * hook_row_single_consolidated() :
   *
   * @return	void
   * @version 5.0.0
   * @since 3.0.0
   */
  private function hook_row_single_consolidated()
  {
    // RETURN : Hook isn't used
    if ( !is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'row_single_consolidated' ] ) )
    {
      // DRS prompt
      if ( !$this->pObj->b_drs_hooks )
      {
        return;
      }
      if ( !is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'row_single_consolidated' ] ) )
      {
        t3lib_div::devlog( '[INFO/HOOK] Any third party extension doesn\'t use the HOOK row_single_consolidated.', $this->pObj->extKey, 0 );
        t3lib_div::devlog( '[HELP/HOOK] See Tutorial Hooks: http://typo3.org/extensions/repository/view/browser_tut_hooks_en/current/', $this->pObj->extKey, 1 );
      }
      return;
    }

    // Call user function
    $_params = array( 'pObj' => &$this );
    foreach ( ( array ) $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'row_single_consolidated' ] as $_funcRef )
    {
      t3lib_div::callUserFunction( $_funcRef, $_params, $this );
    }

    // Hook is used
    // DRS prompt
    if ( !$this->pObj->b_drs_hooks )
    {
      return;
    }

    $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'row_single_consolidated' ] );
    $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'row_single_consolidated' ] );
    $csv_ext = implode( ',', $arr_ext );
    if ( $i_extensions == 1 )
    {
      t3lib_div::devlog( '[INFO/HOOK] The third party extension ' . $csv_ext . ' uses the HOOK row_single_consolidated.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1 );
    }
    if ( $i_extensions > 1 )
    {
      t3lib_div::devlog( '[INFO/HOOK] The third party extensions ' . $csv_ext . ' use the HOOK row_single_consolidated.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1 );
    }
    return;
  }

  /**
   * hook_template_elements(): hook to manipulate the elements of a row BEFORE the elements are linked or transformed by typoscript
   *
   * @return	void
   * @author 	Martin Bless
   * @internal 	#12723, mbless, 110310
   */
  private function hook_template_elements()
  {
    // debug($this->_elements,'$this->_elements',__LINE__,__FILE__);
    if ( is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsHook' ] ) )
    {
      // #i0037, 131121, dwildt, +
      // DRS - Development Reporting System
      if ( $this->pObj->b_drs_hooks )
      {
        $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsHook' ] );
        $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsHook' ] );
        $csv_ext = implode( ',', $arr_ext );
        if ( $i_extensions == 1 )
        {
          t3lib_div::devlog( '[INFO/HOOK] The third party extension ' . $csv_ext . ' uses the HOOK BR_TemplateElementsHook.', $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1 );
        }
        if ( $i_extensions > 1 )
        {
          t3lib_div::devlog( '[INFO/HOOK] The third party extensions ' . $csv_ext . ' use the HOOK BR_TemplateElementsHook.', $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1 );
        }
      }
      // DRS - Development Reporting System
      // #i0037, 131121, dwildt, +

      foreach ( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsHook' ] as $_classRef )
      {
        $_procObj = & t3lib_div :: getUserObj( $_classRef );
        $_procObj->BR_TemplateElementsHook( $this );
      }
    }
  }

  /**
   * hook_template_elements_transformed( ): hook to manipulate the elements of a row AFTER the elements are linked or transformed by typoscript
   *
   * @return	void
   * @author 	Martin Bless
   * @internal 	#12723, mbless, 110310
   */
  private function hook_template_elements_transformed()
  {
    // debug($this->_elementsTransformed,'$this->_elementsTransformed',__LINE__,__FILE__);
    // debug($this->_elementsBoolSubstitute,'$this->_elementsBoolSubstitute',__LINE__,__FILE__);
    if ( is_array( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsTransformedHook' ] ) )
    {
      // #i0037, 131121, dwildt, +
      // DRS - Development Reporting System
      if ( $this->pObj->b_drs_hooks )
      {
        $i_extensions = count( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsTransformedHook' ] );
        $arr_ext = array_values( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsTransformedHook' ] );
        $csv_ext = implode( ',', $arr_ext );
        if ( $i_extensions == 1 )
        {
          t3lib_div::devlog( '[INFO/HOOK] The third party extension ' . $csv_ext . ' uses the HOOK BR_TemplateElementsTransformedHook.', $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extension!', $this->pObj->extKey, 1 );
        }
        if ( $i_extensions > 1 )
        {
          t3lib_div::devlog( '[INFO/HOOK] The third party extensions ' . $csv_ext . ' use the HOOK BR_TemplateElementsTransformedHook.', $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[HELP/HOOK] In case of errors or strange behaviour please check this extenions!', $this->pObj->extKey, 1 );
        }
      }
      // DRS - Development Reporting System
      // #i0037, 131121, dwildt, +

      foreach ( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXTCONF' ][ 'browser' ][ 'BR_TemplateElementsTransformedHook' ] as $_classRef )
      {
        $_procObj = & t3lib_div :: getUserObj( $_classRef );
        $_procObj->BR_TemplateElementsTransformedHook( $this );
      }
    }
  }

  /**
   * htmlFields5x() : Process rows, moves html template to html code
   *
   * @param	string    $template     : html template
   * @param	string    $hashMarker   : hash marker for the field subpart
   * @param	string		$uid          : uid of the current record of the local table
   * @param	string    $markerArray  : array with some value like rendered handleAsValues
   * @return	string	$html         : html code
   * @version 5.0.10
   * @since 5.0.0
   */
  private function htmlFields5x( $template, $hashMarker, $uid, $markerArray )
  {
    $html = null;
    $tmplWiDefaultLayout = $this->tmplWiDefaultLayout( $template, $hashMarker );
    switch ( $tmplWiDefaultLayout )
    {
      case(true):
        $html = $this->htmlFieldsWiDefaultTemplate5x( $template, $hashMarker, $uid, $markerArray );
        break;
      case(false):
      default:
        $html = $this->htmlFieldsWoDefaultTemplate5x( $template, $hashMarker, $uid );
        break;
    }
    return $html;
  }

  /**
   * htmlFieldsWiDefaultTemplate5x() : Process rows, moves html template to html code
   *
   * @param	string    $template     : html template
   * @param	string    $hashMarker   : hash marker for the field subpart
   * @param	string		$uid          : uid of the current record of the local table
   * @param	string    $markerArray  : array with some value like rendered handleAsValues
   * @return	string	$html         : html code
   * @version 5.0.10
   * @since 5.0.0
   */
  private function htmlFieldsWiDefaultTemplate5x( $template, $hashMarker, $uid, $handleAsMarkerArray )
  {
    $this->pObj->arrHandleAs;
    //var_dump( __METHOD__, __LINE__, $this->pObj->arrHandleAs, $handleAsMarkerArray );
    $html = null;
    $wiDefaultTemplate = true;
    $markerArray = $this->pObj->objTyposcript->wrapRow( $template, $wiDefaultTemplate, $uid );

    $currField = 0;
    $sumFields = count( $markerArray );

    foreach ( $markerArray as $field => $value )
    {
      if ( $this->htmlFields5xDontHandle( $field ) )
      {
        continue;
      }
      $markerField[ '###CLASS###' ] = $this->tmplTableTdClass( $currField, $sumFields );
      $markerField[ '###FIELD###' ] = $this->htmlFields5xLabel( $field );  // single view
      // Wenn handleAs, dann handleAsValue
      $value = $this->htmlFields5xValue( $field, $value, $handleAsMarkerArray );  // single view
      $markerField[ '###VALUE###' ] = $value;  // single view
      $markerField[ '###ITEM###' ] = $value;  // list view
      $markerField[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->htmlFields5xBookmark( $uid, $field );
      $htmlField = $this->htmlRow5xHtml( $template, $hashMarker, $markerField );
      $html = $html . $htmlField;
      $currField++;
    }

    return $html;
  }

  /**
   * htmlFields5xValue() :
   *
   * @param	string    $hashMarker     :
   * @return	boolean
   * @version 5.0.10
   * @since 5.0.10
   */
  private function htmlFields5xValue( $hashMarker, $value, $handleAsMarkerArray )
  {
    $field = $this->htmlFields5xTableMarker( $hashMarker );

    //var_dump( __METHOD__, __LINE__, $this->pObj->arrHandleAs, $handleAsMarkerArray );

    switch ( true )
    {
      case( $field == $this->pObj->arrHandleAs[ 'image' ]):
    //var_dump( __METHOD__, __LINE__, $field, $handleAsMarkerArray );
        $value = $handleAsMarkerArray[ $hashMarker ];
        break;
      default:
        // Do nothing
        break;
    }

    //$value = $this->htmlFields5xValueEmpty( $value );

    return $value;
  }

//  /**
//   * htmlFields5xValueEmpty() :
//   *
//   * @param	string    $hashMarker     :
//   * @return	boolean
//   * @version 5.0.10
//   * @since 5.0.10
//   */
//  private function htmlFields5xValueEmpty( $value )
//  {
//    return $value;
//  }

  /**
   * htmlFields5xDontHandle() :
   *
   * @param	string    $hashMarker     :
   * @return	boolean
   * @version 5.0.10
   * @since 5.0.10
   */
  private function htmlFields5xDontHandle( $hashMarker )
  {
    switch ( $this->view )
    {
      case( 'single' ):
        return $this->htmlFields5xDontHandleSingle( $hashMarker );
      case( 'list' ):
      default:
        return $this->htmlFields5xDontHandleList( $hashMarker );
    }
  }

  /**
   * htmlFields5xDontHandleList() :
   *
   * @param	string    $hashMarker     :
   * @return	boolean
   * @version 5.0.10
   * @since 5.0.10
   */
  private function htmlFields5xDontHandleList( $hashMarker )
  {
    $field = $this->htmlFields5xTableMarker( $hashMarker );
    $dontHandle = false;
//    $this->pObj->arrHandleAs;
//var_dump( __METHOD__, __LINE__, $this->view );

    switch ( true )
    {
      case( $field == $this->pObj->arrHandleAs[ 'imageAltText' ]):
      case( $field == $this->pObj->arrHandleAs[ 'imageTitleText' ]):
        $dontHandle = true;
        break;
      default:
        $dontHandle = false;
        break;
    }

    return $dontHandle;
  }

  /**
   * htmlFields5xDontHandle() :
   *
   * @param	string    $hashMarker     :
   * @return	boolean
   * @version 5.0.10
   * @since 5.0.10
   */
  private function htmlFields5xDontHandleSingle( $hashMarker )
  {
    $field = $this->htmlFields5xTableMarker( $hashMarker );
    $dontHandle = false;
//    $this->pObj->arrHandleAs;
//var_dump( __METHOD__, __LINE__, $this->view );

    switch ( true )
    {
      case( $field == $this->pObj->arrHandleAs[ 'title' ]):
      case( $field == $this->pObj->arrHandleAs[ 'imageAltText' ]):
      case( $field == $this->pObj->arrHandleAs[ 'imageTitleText' ]):
        $dontHandle = true;
        break;
      default:
        $dontHandle = false;
        break;
    }

    return $dontHandle;
  }

  /**
   * htmlFieldsWoDefaultTemplate5x() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFieldsWoDefaultTemplate5x( $template, $hashMarker, $uid )
  {
    $html = null;
    $wiDefaultTemplate = false;
    $markerArray = $this->pObj->objTyposcript->wrapRow( $template, $wiDefaultTemplate, $uid );
    $markerArray[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->htmlFields5xBookmark( $uid );
    $html = $this->htmlRow5xHtml( $template, $hashMarker, $markerArray );
    return $html;
  }

  /**
   * htmlFields5xBookmark() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFields5xBookmark( $uid, $field = null )
  {
    $field = $this->htmlFields5xBookmarkField( $field );

    $elements = $this->htmlFields5xRow( $uid );
    $key = $this->htmlFields5xTableMarker( $field );
    $bool_defaultTemplate = true;
    $bookmark = $this->pObj->objSocialmedia->get_htmlBookmarks( $elements, $key, $bool_defaultTemplate );

    return $bookmark;
  }

  /**
   * htmlFields5xBookmarkField() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFields5xBookmarkField( $field )
  {
    if ( $field )
    {
      return $field;
    }
    if ( $this->view == 'list' )
    {
      $field = $this->pObj->objFlexform->str_socialmedia_bookmarks_tableFieldSite_list;
      return $field;
    }

    $field = $this->pObj->objFlexform->str_socialmedia_bookmarks_tableFieldSite_single;
    return $field;
  }

  /**
   * htmlFields5xLabel() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFields5xLabel( $hashMarker )
  {
    $field = $this->htmlFields5xTableMarker( $hashMarker );
    $label = $this->pObj->objZz->getTableFieldLL( $field );

    return $label;
  }

  /**
   * htmlFields5xRow() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFields5xRow( $uidLocalTable )
  {
    // Get the label of the local table
    $tableLocal = $this->pObj->localTable;
    // LOOP rows
    foreach ( ( array ) $this->pObj->rowsLocalised as $row )
    {
      $uid = $row[ $tableLocal . '.uid' ];
      if ( $uidLocalTable !== $uid )
      {
        continue;
      }
      break;
    }
    return $row;
  }

  /**
   * htmlFields5xTableMarker() : Process rows, moves html template to html code
   *
   * @param	string    $template   : html template
   * @param	string    $hashMarker : hash marker for the field subpart
   * @param	string		$uid        : uid of the current record of the local table
   * @return	string	$html       : html code
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlFields5xTableMarker( $hashMarker )
  {
    $tableMarker = str_replace( '###', null, $hashMarker );
    $tableMarker = strtolower( $tableMarker );

    return $tableMarker;
  }

  /**
   * htmlRow5xHtml()  :
   *
   * @param	string		$template
   * @param	string		$hashMarker
   * @param	array     $markerArray
   * @return	string	$html
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlRow5xHtml( $template, $hashMarker, $markerArray )
  {
    $subpart = $this->pObj->cObj->getSubpart( $template, $hashMarker );
    $html = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    return $html;
  }

  /**
   * htmlRows5x() : Process rows, moves html template to html code
   *
   * @param	string    $template         : html template
   * @param	string    $hashMarkerRow    : hash marker for the row subpart
   * @param	string    $hashMarkerField  : hash marker for the field subpart
   * @param	string    $markerArray      : array with some value like rendered handleAsValues
   * @return	string	$html             : html code
   * @version 5.0.10
   * @since 5.0.0
   */
  private function htmlRows5x( $template, $hashMarkerRow, $hashMarkerField, $markerArray = array() )
  {
//var_dump( __METHOD__, __LINE__, $this->pObj->rowsLocalised);
//die();

    $html = null;
    $uids = $this->htmlRows5xLocalUids();
//var_dump( __METHOD__, __LINE__, $this->htmlRows5xLocalUids());
//die( ':(' );

    $currRow = 0;
    $sumRows = count( $uids );

    foreach ( $uids as $uid )
    {
      $htmlFields = $this->htmlFields5x( $template, $hashMarkerField, $uid, $markerArray );
      $htmlRow = $this->pObj->cObj->getSubpart( $template, $hashMarkerRow );
      $htmlRow = $this->pObj->cObj->substituteSubpart( $htmlRow, $hashMarkerField, $htmlFields, true );
      $markerArray[ '###CLASS###' ] = $this->tmplTableTrClass( $currRow, $sumRows );
      $htmlRow = $this->pObj->cObj->substituteMarkerArray( $htmlRow, $markerArray );
      $html = $html . $htmlRow;
      $currRow++;
    }

    return $html;
  }

  /**
   * Building a row out of the given record
   *
   * @param	array		The SQL row (elements)
   * @param	string		The subpart marker, which is the template for a row
   * @param	string		Template
   * @return	string		FALSE || HTML string
   * @version 5.0.0
   * @since 5.0.0
   */
  private function htmlRows5xLocalUids()
  {
    $uids = array();
    // Get the label of the local table
    $tableLocal = $this->pObj->localTable;

    // LOOP rows
    foreach ( ( array ) $this->pObj->rowsLocalised as $rows )
    {
      $uid = $rows[ $tableLocal . '.uid' ];
      if ( $uid === null )
      {
        continue;
      }
      $uids[] = $uid;
    }

    // Make uids uinique
    $uids = array_unique( $uids );

    return $uids;
  }

  /**
   * htmlStaticReplace( ):
   *
   * @return    array        $arr_return: Contains an error message in case of an error
   * @version 5.0.0
   * @since   5.0.0
   * @internal  #43627
   */
  public function htmlStaticReplace( $template, $conf_view )
  {
    // RETURN : htmlSnippets isn't set
    if ( !is_array( $conf_view[ 'htmlSnippets.' ] ) )
    {
      // RETURN : no DRS prompt needed
      if ( !$this->pObj->b_drs_templating )
      {
        return $template;
      }
      // RETURN : DRS prompt
      $prompt = 'views.' . $this->view . '.' . $this->mode . '.htmlSnippets isn\'t set. Nothing to do.';
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      return $template;
    }

    // Replace static html marker with typoscript property
    $template = $this->htmlStaticReplaceSubparts( $template, $conf_view );
    $template = $this->htmlStaticReplaceMarker( $template, $conf_view );

    return $template;
  }

  /**
   * htmlStaticReplaceMarker( ):
   *
   * @return    array        $arr_return: Contains an error message in case of an error
   * @version 4.1.26
   * @since   4.1.26
   *
   * @internal  #43627
   */
  private function htmlStaticReplaceMarker( $template, $conf_view )
  {
    // RETURN htmlSnippets.marker isn't set
    if ( !is_array( $conf_view[ 'htmlSnippets.' ][ 'marker.' ] ) )
    {
      // RETURN : no DRS prompt needed
      if ( !$this->pObj->b_drs_templating )
      {
        return $template;
      }
      // RETURN : DRS prompt
      $prompt = 'views.' . $this->view . '.' . $this->mode . '.htmlSnippets.marker isn\'t set. Nothing to do.';
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      return $template;
    }

    // FOREACH marker
    $confMarker = $conf_view[ 'htmlSnippets.' ][ 'marker.' ];
    foreach ( array_keys( ( array ) $confMarker ) as $marker )
    {
      // CONTINUE : current marker has a dot
      if ( $marker !== rtrim( $marker, '.' ) )
      {
        continue;
      }
      // Get the marker content
      $name = $confMarker[ $marker ];
      $conf = $confMarker[ $marker . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $name, $conf );
      // Replace the marker with the content
      $hashMarker = '###' . strtoupper( $marker ) . '###';
      $template = $this->pObj->cObj->substituteMarker( $template, $hashMarker, $content );
    } // FOREACH marker

    return $template;
  }

  /**
   * htmlStaticReplaceSubparts( ):
   *
   * @return    array        $arr_return: Contains an error message in case of an error
   * @version 4.1.26
   * @since   4.1.26
   *
   * @internal  #43627
   */
  private function htmlStaticReplaceSubparts( $template, $conf_view )
  {
    // RETURN htmlSnippets.subparts isn't set
    if ( !is_array( $conf_view[ 'htmlSnippets.' ][ 'subparts.' ] ) )
    {
      // RETURN : no DRS prompt needed
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'views.' . $this->view . '.' . $this->mode . '.htmlSnippets.subparts isn\'t set. Nothing to do.';
        t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // RETURN : DRS prompt
      return $template;
    }

    // FOREACH subparts
    $confSubpart = $conf_view[ 'htmlSnippets.' ][ 'subparts.' ];
    foreach ( array_keys( ( array ) $confSubpart ) as $subpart )
    {
      // CONTINUE : current subpart has a dot
      if ( $subpart !== rtrim( $subpart, '.' ) )
      {
        continue;
      }
      // Get the subpart content
      $name = $confSubpart[ $subpart ];
      $conf = $confSubpart[ $subpart . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $name, $conf );
      // Replace the subpart with the content
      $hashSubpart = '###' . strtoupper( $subpart ) . '###';
      $content = '<!-- ' . $hashSubpart . ' begin -->' . $content . '<!-- ' . $hashSubpart . ' end -->';
      $template = $this->pObj->cObj->substituteSubpart( $template, $hashSubpart, $content );
    } // FOREACH subpart

    return $template;
  }

  /**
   * Order the values by uid or value and ASC or DESC
   *
   * @param	array		$arr_values: Array with the values for ordering
   * @param	string		$conf_tableField: table and field in table.field syntax
   * @return	array		Array with ordered values
   * @internal  Method is moved from class.tx_browser_pi1_filter_3x to here
   * @version 4.1.21
   * @since   2.x
   */
  private function orderValues( $arr_values, $conf_tableField )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];
    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';



    /////////////////////////////////////////////////////////////////
    //
      // RETURN there aren't values for ordering

    if ( !is_array( $arr_values ) )
    {
      return $arr_values;
    }
    if ( count( $arr_values ) < 1 )
    {
      return $arr_values;
    }
    // RETURN there aren't values for ordering



    list ($table, $field) = explode( '.', $conf_tableField );
    $arr_ts = $conf_view[ 'filter.' ][ $table . '.' ][ $field . '.' ];

    if ( $arr_ts[ 'order.' ][ 'field' ] == 'uid' )
    {
      $arr_values = array_flip( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog( '[INFO/FILTER] Values are ordered by uid.', $this->pObj->extKey, 0 );
        t3lib_div :: devLog( '[HELP/FILTER] If you want order values by there values, please configure ' . $conf_view_path . $conf_tableField . '.order.field.', $this->pObj->extKey, 1 );
      }
    }

    if ( strtolower( $arr_ts[ 'order.' ][ 'orderFlag' ] ) == 'desc' )
    {
      arsort( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog( '[INFO/FILTER] Values are ordered descending.', $this->pObj->extKey, 0 );
        t3lib_div :: devLog( '[HELP/FILTER] If you want to order ascending, please configure ' . $conf_view_path . $conf_tableField . '.order.orderFlag = ASC.', $this->pObj->extKey, 1 );
      }
    }
    else
    {
      asort( $arr_values );
      if ( $this->pObj->b_drs_filter )
      {
        t3lib_div :: devLog( '[INFO/FILTER] Values are ordered ascending.', $this->pObj->extKey, 0 );
        t3lib_div :: devLog( '[HELP/FILTER] If you want to order descending, please configure ' . $conf_view_path . $conf_tableField . '.order.orderFlag = DESC.', $this->pObj->extKey, 1 );
      }
    }
    if ( $arr_ts[ 'order.' ][ 'field' ] == ('uid') )
    {
      $arr_values = array_flip( $arr_values );
    }

    return $arr_values;
  }

  /**
   * Wraps field values in respect to the TypoScript configuration an the handleAs cases
   *
   * @param	array		$elements: SQL row
   * @param	array		$handleAs: Array with the fieldnames which have a special handling like title, images or documents
   * @param	array		$markerArray: Array with the current markers
   * @return	array		$markerArray: Array with the current markers
   * @version 3.9.18
   * @since 1.0.0
   */
  function render_handleAs( $elements, $handleAs, $markerArray )
  {

    /////////////////////////////////////////
    //
    // RETURN without elements

    if ( !is_array( $elements ) )
    {
      return $markerArray;
    }
    if ( count( $elements ) < 1 )
    {
      return $markerArray;
    }
    // RETURN without elements


    $displayTitle = $this->pObj->lDisplay[ 'title' ];
    $rows = $this->pObj->rows;
    $bool_nRows = false;
    if ( count( $rows ) > 1 )
    {
      $bool_nRows = true;
    }

    /////////////////////////////////////////
    //
    // Wrap all elements

    foreach ( ( array ) $elements as $tableField => $value )
    {

      $b_is_rendered = false;

      /////////////////////////////////////////
      //
      // Handle the TITLE

      if ( $displayTitle && $tableField == $handleAs[ 'title' ] )
      {
        if ( $this->pObj->b_drs_templating )
        {
          t3lib_div::devlog( '[INFO/TEMPLATING] ' . $handleAs[ 'title' ] . ' will be handled as the title.', $this->pObj->extKey, 0 );
          t3lib_div::devLog( '[HELP/TEMPLATING] Please configure displaySingle.display.title = 0, if you don\'t want any title handling.', $this->pObj->extKey, 1 );
        }
        $value = $this->pObj->objWrapper4x->wrapAndLinkValue( $tableField, $value, 0 );
        $markerArray[ '###TITLE###' ] = $value;
        $markerArray[ '###' . strtoupper( $tableField ) . '###' ] = $value;

        $b_is_rendered = true;
      }
      // Handle the TITLE
      /////////////////////////////////////////
      //
      // Handle the IMAGE

      if ( $tableField == $handleAs[ 'image' ] )
      {
        if ( $this->pObj->b_drs_templating )
        {
          t3lib_div::devlog( '[INFO/TEMPLATING] The field \'' . $handleAs[ 'image' ] . '\' will be wrapped as an IMAGE.', $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###IMAGE### will be replaced.', $this->pObj->extKey, 0 );
        }
        $tsImage[ 'image' ] = $elements[ $handleAs[ 'image' ] ];
        $tsImage[ 'imagecaption' ] = $elements[ $handleAs[ 'imageCaption' ] ];
        $tsImage[ 'imagealttext' ] = $elements[ $handleAs[ 'imageAltText' ] ];
        $tsImage[ 'imagetitletext' ] = $elements[ $handleAs[ 'imageTitleText' ] ];
        $value = $this->pObj->objWrapper4x->wrapImage( $tsImage );
        $markerArray[ '###IMAGE###' ] = $value;
        $markerArray[ '###' . strtoupper( $tableField ) . '###' ] = $value;

        $b_is_rendered = true;
      }
      // Handle the IMAGE
      /////////////////////////////////////////
      //
      // Handle the DOCUMENT
      //:todo: Handle the document
      /////////////////////////////////////////
      //
      // Process all the rest of the elements

      if ( !$b_is_rendered )
      {
        $value = false;
        list($table, $field) = explode( '.', $tableField );
        // Store the id of the previous element.
        $int_last_uid = false;

        // Loop through all rows
        // 120915, dwildt, 1-
        //foreach( ( array ) $rows as $lRow => $lElements )
        // 120915, dwildt, 1+
        foreach ( ( array ) $rows as $lElements )
        {
          // Store the current id of the current element.
          $int_cur_uid = $lElements[ $table . '.uid' ];
          if ( !$int_cur_uid )
          {
            // Store -1, if current element has no uid (has no uid field in the SELECT statement)
            $int_cur_uid = -1;
          }
          // Wrap the element and append it, if it has different id
          if ( $int_last_uid != $int_cur_uid )
          {
            $value = $value . $this->pObj->objWrapper4x->wrapAndLinkValue( $tableField, $lElements[ $tableField ], 0 );
          }
          // Store the id as id of the previous element.

          if ( $int_last_uid == $int_cur_uid )
          {
            $value = $this->pObj->objWrapper4x->wrapAndLinkValue( $tableField, $lElements[ $tableField ], 0 );
          }
          $int_last_uid = $int_cur_uid;
        }
        // Loop through all rows
        // Process the TS extensions.browser.wrapAll
        if ( $value )
        {
          $conf_wrapHeader = $this->conf_view[ $table . '.' ][ $field . '.' ][ 'extensions.' ][ 'browser.' ][ 'wrapAll.' ][ 'header.' ];
          $lHeader = $this->pObj->objWrapper4x->general_stdWrap( false, $conf_wrapHeader );
          $conf_wrapAll = $this->conf_view[ $table . '.' ][ $field . '.' ][ 'extensions.' ][ 'browser.' ][ 'wrapAll.' ][ 'stdWrap.' ];
          $value = $this->pObj->objWrapper4x->general_stdWrap( $value, $conf_wrapAll );
          $value = $lHeader . $value;
        }
        // Process the TS extensions.browser.wrapAll

        $lMarker = '###' . strtoupper( $tableField ) . '###';
        $markerArray[ $lMarker ] = $value;
      }
      // Process all the rest of the elements
    }
    // Wrap all elements


    return $markerArray;
  }

  /**
   * Building the result phrase for the search form.
   *
   * @return	string		Rendered rusult phrase
   * @version   4.1.9
   * @since     2.0.0
   */
  function resultphrase()
  {
    /**
     * This method correspondends with tx_browser_pi1_zz::color_swords($tableField, $str_content)
     */
    $lSearchform = $this->pObj->lDisplay[ 'searchform.' ];



    ///////////////////////////////////////////////////////////////
    //
    // RETURN in case of any swords

    if ( !is_array( $this->pObj->arr_swordPhrases ) )
    {
      return false;
    }
    // RETURN in case of any swords
    ///////////////////////////////////////////////////////////////
    //
    // Set variables

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];

    $conf_phrase = $lSearchform[ 'resultPhrase.' ];
    $conf_searchFor = $lSearchform[ 'resultPhrase.' ][ 'searchFor.' ];
    $str_searchFor = $this->pObj->objWrapper4x->general_stdWrap( $conf_searchFor[ 'value' ], $conf_searchFor );
    // 120915, dwildt, 1-
    //$conf_and        = $lSearchform['resultPhrase.']['searchFor.']['and.'];
    // 120915, dwildt, 1-
    //$str_and         = $this->pObj->objWrapper4x->general_stdWrap($conf_and['value'], $conf_and);

    $conf_minLen = $lSearchform[ 'resultPhrase.' ];
    $bool_wrapSwords = $this->pObj->objFlexform->bool_searchForm_wiColoredSwords;
    $key_according = 0;
    $arr_confWrap = $lSearchform[ 'wrapSwordInResults.' ];
    $max_key = count( $arr_confWrap ) - 1;
    // Set variables
    ///////////////////////////////////////////////////////////////
    //
    // Get global or local array advanced
    #10116
    $arr_conf_advanced = $conf[ 'advanced.' ];
    if ( !empty( $conf_view[ 'advanced.' ] ) )
    {
      $arr_conf_advanced = $conf_view[ 'advanced.' ];
    }
    // Get global or local array advanced
    // Char for Wildcard
    $chr_wildcard = $this->pObj->str_searchWildcardCharManual;
    $arr_colored = array();
    // 120915, dwildt, 1+
    $arrWrappedSwords = null;
    foreach ( ( array ) $this->pObj->arr_resultphrase[ 'arr_marker' ] as $key => $value )
    {
      $value = stripslashes( $value );
      if ( $bool_wrapSwords )
      {
        // Wildcards are used by default
        if ( !$this->pObj->bool_searchWildcardsManual )
        {
          $str_wrapped_value = $this->pObj->objWrapper4x->general_stdWrap( $value, $arr_confWrap[ $key_according . '.' ] );
        }
        // Wildcards are used by default
        // The user has to add a wildcard
        if ( $this->pObj->bool_searchWildcardsManual )
        {
          $valueWildcard = $value;
          // First char of search word is a wildcard
          if ( $valueWildcard[ 0 ] == $chr_wildcard )
          {
            $valueWildcard = substr( $valueWildcard, 1, strlen( $valueWildcard ) - 1 );
          }
          // First char of search word is a wildcard
          // Last char of search word is a wildcard
          if ( $valueWildcard[ strlen( $valueWildcard ) - 1 ] == $chr_wildcard )
          {
            $valueWildcard = substr( $valueWildcard, 0, -1 );
          }
          // Last char of search word is a wildcard
          $str_wrapped_value = $this->pObj->objWrapper4x->general_stdWrap( $valueWildcard, $arr_confWrap[ $key_according . '.' ] );
        }
        // The user has to add a wildcard

        $arr_colored[ $key ] = $str_wrapped_value;
      }
//if(t3lib_div::_GP('dev')) var_dump('template 332', $arr_colored);
      if ( !$bool_wrapSwords )
      {
        $str_wrapped_value = $value;
      }
      $arrWrappedSwords[ $key ] = $str_wrapped_value;
      if ( $key_according <= $max_key )
      {
        $key_according++;
      }
      if ( $key_according > $max_key )
      {
        $key_according = 0;
      }
    }
    $str_swords = $this->pObj->arr_resultphrase[ 'str_mask' ];
    foreach ( ( array ) $arrWrappedSwords as $key => $value )
    {
      $str_swords = str_replace( $key, $value, $str_swords );
    }
    $this->pObj->arr_resultphrase[ 'arr_colored' ] = $arr_colored;
// 3.3.4
//if(t3lib_div::_GP('dev')) var_dump('template 354', $this->pObj->arr_resultphrase);

    $conf_hasResult = $lSearchform[ 'resultPhrase.' ][ 'hasResult.' ];
    $str_hasResult = $lSearchform[ 'resultPhrase.' ][ 'hasResult.' ][ 'value' ];
    $str_hasResult = $this->pObj->objWrapper4x->general_stdWrap( $str_hasResult, $conf_hasResult );
    $str_minLen = false;
    $bool_minLen = $lSearchform[ 'resultPhrase.' ][ 'minLenPhrase' ];
    if ( $bool_minLen )
    {
      $conf_minLen = $lSearchform[ 'resultPhrase.' ][ 'minLenPhrase.' ];
      $str_minLen = $lSearchform[ 'resultPhrase.' ][ 'minLenPhrase.' ][ 'value' ];
      $str_minLen = $this->pObj->objWrapper4x->general_stdWrap( $str_minLen, $conf_minLen );
      #10116
      $str_minLen = str_replace( '###advanced.security.sword.minLenWord###', $arr_conf_advanced[ 'security.' ][ 'sword.' ][ 'minLenWord' ], $str_minLen );
    }
    $bool_operator = $lSearchform[ 'resultPhrase.' ][ 'operatorPhrase' ];
    $str_operator = false;
    if ( $bool_operator )
    {
      $conf_operator = $lSearchform[ 'resultPhrase.' ][ 'operatorPhrase.' ];
      $str_operator = $lSearchform[ 'resultPhrase.' ][ 'operatorPhrase.' ][ 'value' ];
      $str_operator = $this->pObj->objWrapper4x->general_stdWrap( $str_operator, $conf_operator );
    }
    $str_wildcard = false;
    if ( $this->pObj->bool_searchWildcardsManual )
    {
      $conf_wildcard = $lSearchform[ 'resultPhrase.' ][ 'wildcardPhrase.' ];
      $str_wildcard = $lSearchform[ 'resultPhrase.' ][ 'wildcardPhrase.' ][ 'value' ];
      $str_wildcard = $this->pObj->objWrapper4x->general_stdWrap( $str_wildcard, $conf_wildcard );
      $str_wildcard = str_replace( '%wildcard%', $this->pObj->str_searchWildcardCharManual, $str_wildcard );
    }
    $str_phrase = $str_searchFor . ' ' . $str_swords . ' ' . $str_hasResult . ' ' . $str_minLen . $str_operator . $str_wildcard;
    $str_phrase = $this->pObj->objWrapper4x->general_stdWrap( $str_phrase, $conf_phrase );

    if ( $this->pObj->b_drs_search )
    {
      t3lib_div::devlog( '[INFO/SEARCH] Result phrase: \'' . $str_phrase . '\'', $this->pObj->extKey, 0 );
    }



    ///////////////////////////////////////////////////////////////
    //
    // RETURN false, in case of TypoScript: Don't display resultphrase'

    if ( !$this->pObj->objFlexform->bool_searchForm_wiPhrase )
    {
      return false;
    }
    // RETURN false, in case of TypoScript: Don't display resultphrase'



    return $str_phrase;
  }

  /**
   * setDisplayList() : Set the global $this->lDisplayList
   *
   * @return	array		$lDisplayList : displayList configuration
   * @version 5.0.0
   * @since 2.0.0
   */
  private function setDisplayList()
  {
    $lDisplayList = $this->conf_view[ 'displayList.' ];
    if ( !is_array( $lDisplayList ) )
    {
      $lDisplayList = $this->pObj->conf[ 'displayList.' ];
    }
    $this->lDisplayList = $lDisplayList;
    return $lDisplayList;
  }

  /**
   * setDisplaySingle() : Set the global $this->lDisplaySingle
   *
   * @return	array		$lDisplaySingle : displaySingle configuration
   * @version 5.0.0
   * @since 2.0.0
   */
  private function setDisplaySingle()
  {
    $lDisplaySingle = $this->conf_view[ 'displaySingle.' ];
    if ( !is_array( $lDisplaySingle ) )
    {
      $lDisplaySingle = $this->pObj->conf[ 'displaySingle.' ];
    }
    $this->lDisplaySingle = $lDisplaySingle;
    return $lDisplaySingle;
  }

  /**
   * setGlobalElementsOfFirstRow() :
   *
   * @param	array		$rows           : current rows
   * @return	array		$elements       : elements of the first row
   * @version 5.0.0
   * @since 1.0.0
   */
  private function setGlobalElementsOfFirstRow( $rows )
  {
    if ( !is_array( $rows ) )
    {
      return;
    }

    reset( $rows );
    $firstKey = key( $rows );
    $elements = $rows[ $firstKey ];
    $this->pObj->elements = $elements;

    return $elements;
  }

  /**
   * setGlobalRows() : Set the global $this->lDisplaySingle
   *
   * @param	[type]		$rows: ...
   * @return	array		$lDisplaySingle : displaySingle configuration
   * @version 5.0.0
   * @since 1.0.0
   */
  private function setGlobalRows( $rows )
  {
    if ( !is_array( $rows ) )
    {
      return;
    }

    reset( $rows );
    $this->pObj->rows = $rows;
  }

  /**
   * setUploadFolder() : Set the global $this->lDisplaySingle
   *
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function setUploadFolder()
  {
    // Is there a upload folder?
    $this->pObj->uploadFolder = $this->conf_view[ 'upload' ];
    if ( !$this->pObj->uploadFolder )
    {
      $this->pObj->uploadFolder = $this->pObj->conf[ 'upload' ];
    }
  }

  /**
   * Building a row for the HTML table tag <thead> out of the given record and write it to the global $template
   *
   * @param	string		Template
   * @return	string		Template
   * @version 3.9.24
   * @since 1.0.0
   */
  private function tmplHead( $template )
  {
    $arrOrderByFields = $this->tmplHeadOrderbyFields();
    $arrColumns = $this->tmplHeadRemoveFields( $arrColumns );

    switch ( true )
    {
      case( $this->tmplHeadWiItemMarker( $template ) ):
      case( $this->typeNumIsCsv() ):
        $items = $this->tmplHeadWiTableHead( $template, $arrColumns, $arrOrderByFields );
        break;
      default:
        $items = $this->tmplHeadWiSelectBoxOrderBy( $arrColumns, $arrOrderByFields );
        break;
    }

    $listHead = $this->pObj->cObj->getSubpart( $template, '###LISTHEAD###' );
    $listHead = $this->pObj->cObj->substituteSubpart( $listHead, '###LISTHEADITEM###', $items, true );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTHEAD###', $listHead, true );

    return $template;
  }

  /**
   * tmplHeadFieldKeys()  :
   *
   * @return	array
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplHeadFieldKeys()
  {
    reset( $this->pObj->rows );
    $key = key( $this->pObj->rows );
    $rows = array(
      0 => $this->pObj->rows[ $key ]
    );
    $arr_result = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields( $rows );
    $rows = $arr_result[ 'data' ][ 'rows' ];
    if ( !is_array( $rows[ 0 ] ) )
    {
      $rows[ 0 ] = array();
    }
    $keysOfFirstRow = array_keys( $rows[ 0 ] );
    return $keysOfFirstRow;
  }

  /**
   * tmplHeadTypolink()
   *
   * @param	string		Template
   * @return	string
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplHeadTypolink( $tableField, $arrOrderByFields, $tableFieldWiAscOrDesc )
  {
    $typolink = null;
    $tableFieldLL = $this->pObj->objZz->getTableFieldLL( $tableField );
    $typolink = $tableFieldLL;

    $typolink = $this->pObj->objExport->csv_value( $typolink );

    if ( in_array( $tableField, $arrOrderByFields ) )
    {
      $sort = array(
        'sort' => $tableFieldWiAscOrDesc[ $tableField ][ 'param' ]
      );
      $typolink = $this->pObj->pi_linkTP_keepPIvars( $tableFieldLL, $sort, $this->pObj->boolCache );
    }
    return $typolink;
  }

  /**
   * tmplHeadWiItemMarker()  :
   *
   * @param	string
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplHeadWiItemMarker( $template )
  {
    $listHead = $this->pObj->cObj->getSubpart( $template, '###LISTHEAD###' );
    $pos = strpos( $listHead, '<tr' );
    if ( $pos !== false )
    {
      return true;
    }

    $listBodyItem = $this->pObj->cObj->getSubpart( $template, '###LISTBODYITEM###' );
    $pos = strpos( $listBodyItem, '###ITEM###' );
    if ( $pos !== false )
    {
      return true;
    }

    return false;
  }

  /**
   * tmplHeadOrderbyFields() :
   *
   * @return	array
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplHeadOrderbyFields()
  {
    $arrOrderByFields = null;

    $csvOrderBy = $this->pObj->conf_sql[ 'orderBy' ];
    $csvOrderBy = str_ireplace( ' desc', '', $csvOrderBy );
    $csvOrderBy = str_ireplace( ' asc', '', $csvOrderBy );

    $tableFields = explode( ',', $csvOrderBy );
    foreach ( $tableFields as $tableField )
    {
      if ( empty( $tableField ) )
      {
        continue;
      }
      $arrOrderByFields[] = trim( $tableField );
    }
    // Get the default order out of the TS
    // csv export: Header fields shouldn't get the order property
    // #29370, 110831, dwildt+
    if ( $this->pObj->objExport->str_typeNum == 'csv' )
    {
      if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
      {
        t3lib_div::devlog( '[INFO/EXPORT] Don\'t link header fields. $arrOrderByFields is unset.', $this->pObj->extKey, 0 );
      }
      unset( $arrOrderByFields );
    }
    return $arrOrderByFields;
  }

  /**
   * tmplHeadRemoveFields()  :
   *
   * @return	string
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplHeadRemoveFields()
  {
    // Set the global arr_rmFields
    $this->tmpl_rmFields();

    $addedTableFields = $this->pObj->arrConsolidate[ 'addedTableFields' ];

    // Get the global $arrHandleAs array
    $handleAs = $this->pObj->arrHandleAs;

    $keysOfFirstRow = $this->tmplHeadFieldKeys();

    // Delete columns, we don't want
    foreach ( ( array ) $keysOfFirstRow as $columnKey => $columnValue )
    {
      switch ( true )
      {
        case(trim( $columnValue ) == $handleAs[ 'imageCaption' ]):
        case(trim( $columnValue ) == $handleAs[ 'imageAltText' ]):
        case(trim( $columnValue ) == $handleAs[ 'imageTitleText' ]):
        case(in_array( trim( $columnValue ), ( array ) $addedTableFields )):
        case(in_array( trim( $columnValue ), $this->arr_rmFields ) ):
          unset( $keysOfFirstRow[ $columnKey ] );
          if ( $this->pObj->b_drs_templating )
          {
            t3lib_div::devLog( '[INFO/TEMPLATING] Table Head: ' . $columnKey . ' is removed.', $this->pObj->extKey, 0 );
          }
          break;
        default:
          // Follow the workflow
          break;
      }
    }
    return $keysOfFirstRow;
  }

  /**
   * tmplHeadSort()
   *
   * @param	string		Template
   * @return	string		Template
   * @version 3.9.24
   * @since 1.0.0
   */
  private function tmplHeadSort( $tableFields, $arrOrderByFields )
  {
    $tableFieldWiAscOrDesc = array();
    foreach ( ( array ) $tableFields as $tableField )
    {
      if ( !in_array( $tableField, $arrOrderByFields ) )
      {
        continue;
      }

      if ( $this->pObj->internal[ 'orderBy' ] != $tableField )
      {
        $tableFieldWiAscOrDesc[ $tableField ][ 'param' ] = $tableField . ':1'; // ASC
        $tableFieldWiAscOrDesc[ $tableField ][ 'order' ] = 'ASC';
        continue;
      }

      if ( !$this->pObj->internal[ 'descFlag' ] )
      {
        $tableFieldWiAscOrDesc[ $tableField ][ 'param' ] = $tableField . ':1'; // ASC
        $tableFieldWiAscOrDesc[ $tableField ][ 'order' ] = 'ASC';
        continue;
      }

      $tableFieldWiAscOrDesc[ $tableField ][ 'param' ] = $tableField . ':0'; // DESC
      $tableFieldWiAscOrDesc[ $tableField ][ 'order' ] = 'DESC';
    }
    return $tableFieldWiAscOrDesc;
  }

  /**
   * tmplHeadWiTableHead()
   *
   * @param	string		Template
   * @return	string		Template
   * @version 3.9.24
   * @since 1.0.0
   */
  private function tmplHeadWiTableHead( $template, $tableFields, $arrOrderByFields )
  {
    $items = null;
    $currentColumn = 0;
    $maxColumns = count( $tableFields ) - 1;

    $tableFieldWiAscOrDesc = $this->tmplHeadSort( $tableFields, $arrOrderByFields );

    $markerArrayStatic = $this->tmpl_marker();
    // LOOP : tablefields
    foreach ( ( array ) $tableFields as $tableField )
    {
      $typolink = $this->tmplHeadTypolink( $tableField, $arrOrderByFields, $tableFieldWiAscOrDesc );
      $order = $tableFieldWiAscOrDesc[ $tableField ][ 'order' ];
      $markerArray = $this->tmplHeadWiTableHeadMarkerArray( $currentColumn, $maxColumns, $typolink, $order );
      $markerArray = $markerArrayStatic + $markerArray;

      $listHeadItem = $this->pObj->cObj->getSubpart( $template, '###LISTHEADITEM###' );
      $item = $this->pObj->cObj->substituteMarkerArray( $listHeadItem, $markerArray );
      $items = $items . $item;
      $currentColumn++;
    } // LOOP : tablefields

    if ( $this->pObj->objExport->str_typeNum == 'csv' )
    {
      $items = rtrim( $items, $this->pObj->objExport->csv_devider );
    }

    if ( !$this->pObj->b_drs_templating )
    {
      return $items;
    }

    t3lib_div::devlog( '[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###.<br />' .
            'The Browser will generate a table-head-tag with the field names and an order possibility.', $this->pObj->extKey, 0 );
    t3lib_div::devLog( '[HELP/TEMPLATING] Do you want a select box for ordering? Use your own marker like ####TABLE.FIELD### in the HTML template.', $this->pObj->extKey, 1 );
    return $items;
  }

  /**
   * tmplHeadWiTableHeadMarkerArray()
   *
   * @param	string		Template
   * @return	string		Template
   * @version 3.9.24
   * @since 1.0.0
   */
  private function tmplHeadWiTableHeadMarkerArray( $currentColumn, $maxColumns, $typolink, $order )
  {
    $class = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'td' ];
    $odd = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'odd' ];

    if ( !empty( $order ) )
    {
      $order = strtolower( $order );
      $order = ' ' . $class . '-' . $order . ' ' . $order;
    }

    $markerArray[ '###TH_COUNTER###' ] = $currentColumn;
    switch ( true )
    {
      case( $currentColumn == 0 ):
        $markerArray[ '###TH_FIRST_LAST###' ] = 'first';
        $markerArray[ '###CLASS###' ] = ' class="' . $class . ' ' . $class . '-' . $currentColumn . $order . ' first"';
        break;
      case( $currentColumn >= $maxColumns ):
        $markerArray[ '###TH_FIRST_LAST###' ] = 'last';
        $markerArray[ '###CLASS###' ] = ' class="' . $class . ' ' . $class . '-' . $currentColumn . $order . ' last"';
        break;
      default:
        $markerArray[ '###TH_FIRST_LAST###' ] = null;
        $markerArray[ '###CLASS###' ] = ' class="' . $class . ' ' . $class . '-' . $currentColumn . $order . '"';
        break;
    }
    $markerArray[ '###TH_EVEN_OR_ODD###' ] = $currentColumn % 2 ? $odd : null;
    $markerArray[ '###ITEM###' ] = $typolink;

    return $markerArray;
  }

  /**
   * tmplHeadWiSelectBoxOrderBy()
   *
   * @return	string		Template
   * @version 3.9.24
   * @since 1.0.0
   */
  private function tmplHeadWiSelectBoxOrderBy( $tableFields, $arrOrderByFields )
  {

    static $bool_first = true;
    $items = null;

    // RETURN : select box orderby should not displayed
    $display = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'display' ];
    if ( !$display )
    {
      if ( $bool_first && $this->pObj->b_drs_templating )
      {
        $prompt = 'selectBox_orderBy.display is FALSE. selectbox won\'t be displayed.';
        t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'If you want to display it, please enable displayList.selectBox_orderBy.display.';
        t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
      }
      $bool_first = false;
      return $items;
    }

    $tableFieldWiAscOrDesc = $this->tmplHeadSort( $tableFields, $arrOrderByFields );
    unset( $arrOrderByFields );
    foreach ( $tableFieldWiAscOrDesc as $tableField => $property )
    {
      $arrOrderByFields[ $property[ 'param' ] ] = $this->pObj->objZz->getTableFieldLL( $tableField );
    }

    // Do we have fields for ordering, which aren't in the SQL result?
    // #8337, 101011, dwildt
    $obj_ts = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox' ];
    $arr_ts = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ];
    if ( !is_array( $arr_ts ) )
    {
      // DRS - Development Reporting System
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[ERROR/TEMPLATING] The TS array selectBox_orderBy.selectbox is empty!', $this->pObj->extKey, 3 );
        t3lib_div::devLog( '[HELP/TEMPLATING] Please configure a proper TypoScript.', $this->pObj->extKey, 1 );
      }
      // DRS - Development Reporting System
    }
    $conf_tableField = 'table.field';  // Dummy
    $arr_result = $this->tmplHeadWiSelectBoxOrderBySorting( $arr_ts, $arrOrderByFields, $conf_tableField );
    $arr_values = $arr_result[ 'data' ][ 'values' ];
    unset( $arr_result );

    // Process nice_piVar
    $arr_result = $this->pObj->objFltr4x->zz_getNicePiVar( 'orderBy' );
    $key_piVar = $arr_result[ 'data' ][ 'key_piVar' ];
    $arr_piVar = $arr_result[ 'data' ][ 'arr_piVar' ];
    $str_nice_piVar = $arr_result[ 'data' ][ 'nice_piVar' ];
    unset( $arr_result );
    // Process nice_piVar

    $str_html = false;
    $int_counter_element = 0;
    $conf_selected = ' ' . $arr_ts[ 'wrap.' ][ 'item.' ][ 'selected' ];
    $int_space_left = $arr_ts[ 'wrap.' ][ 'item.' ][ 'nice_html_spaceLeft' ];
    $str_space_left = str_repeat( ' ', $int_space_left );

    // Loop through the rows of the SQL result
    foreach ( ( array ) $arr_values as $value => $label )
    {
      list($tableField) = explode( ':', $value );
      $order = $tableFieldWiAscOrDesc[ $tableField ][ 'order' ];
      $order = strtolower( $order );

      $conf_item = $arr_ts[ 'wrap.' ][ 'item' ];
      $conf_item = $this->pObj->objFltr3x->get_wrappedItemClass( $arr_ts, $conf_item, $order );
      // Wrap the item style
      $conf_item = $this->pObj->objFltr3x->get_wrappedItemStyle( $arr_ts, $conf_item, $order );
      // Wrap the item uid
      $conf_item = str_replace( '###VALUE###', $value, $conf_item );
      // Get the item selected (or not selected)
      $arr_piVar = $this->pObj->piVars;
      // dwildt, 110102
      //$conf_item  = $this->pObj->objFltr3x->get_wrappedItemSelected($value, $arr_piVar, $conf_selected, $conf_item);
      $tmp_value = null;
      if ( $value !== 0 )
      {
        if ( isset( $arr_piVar[ 'sort' ] ) )
        {
          list($value_field, $value_order) = explode( ':', $value );
          list($piVar_field, $piVar_order) = explode( ':', $arr_piVar[ 'sort' ] );
          // 120915, dwildt, 2+
          unset( $value_order );
          unset( $piVar_order );
          if ( $value_field == $piVar_field )
          {
            $tmp_value = $arr_piVar[ 'sort' ];
          }
        }
      }
      // 140705, dwildt, 1-: User can't select a selected item again.
      //$conf_item = $this->pObj->objFltr3x->get_wrappedItemSelected( null, $tmp_value, $arr_piVar, $arr_ts, $conf_selected, $conf_item );
      // Wrap the value
      $conf_item = str_replace( '|', $label, $conf_item );
      $conf_item = $conf_item . "\n";
      $str_html = $str_html . $str_space_left . $conf_item;
      $int_counter_element++;
    }
//var_dump( __METHOD__, __LINE__, $str_html);
//die(':(');
    // Loop through the rows of the SQL result
    // Delete the last line break
    $str_html = substr( $str_html, 0, -1 );

    // Wrap all items / the object
    // #8337, 101011, dwildt
    $conf_object = $this->pObj->objFltr3x->wrap_allItems( $obj_ts, $arr_ts, $str_nice_piVar, $key_piVar, count( $arr_values ) );
    $str_html = str_replace( '|', "\n" . $str_html . "\n" . $str_space_left, $conf_object );
    // Wrap the object title
    $conf_wrap = $this->pObj->objFltr3x->wrap_objectTitle( $arr_ts, $conf_tableField );
    // Wrap the object
    if ( $conf_wrap )
    {
      $str_html = str_replace( '|', "\n" . $str_html . "\n" . $str_space_left, $conf_wrap );
    }
    // Wrap all items / the object
    ////////////////////////////////////////////////////////////////////////////////
    //
        // Wrap the form
    #10204, dwildt, 101012
    // Form HTML class
    $str_class = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ][ 'form.' ][ 'class' ];
    if ( !empty( $str_class ) )
    {
      $arr_marker_option[ '###CLASS###' ] = ' class="' . $str_class . '"';
    }
    if ( empty( $str_class ) )
    {
      $arr_marker_option[ '###CLASS###' ] = null;
    }
    // Form HTML class
    // Form legend stdWrap
    $arr_legend = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ][ 'form.' ][ 'legend_stdWrap.' ];
    $str_legend = $this->pObj->objWrapper4x->general_stdWrap( $arr_legend[ 'value' ], $arr_legend );
    $arr_marker_option[ '###LEGEND###' ] = $str_legend;
    // Form legend stdWrap
    // Form submit button stdWrap
    $arr_button = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ][ 'form.' ][ 'button_stdWrap.' ];
    $str_button = $this->pObj->objWrapper4x->general_stdWrap( $arr_button[ 'value' ], $arr_button );
    $arr_marker_option[ '###BUTTON###' ] = $str_button;
    // Form submit button stdWrap
    // Form action (URL without any parameter)
    $arr_tmp = $this->pObj->piVars;
    unset( $this->pObj->piVars );
    $str_url_wo_piVars = $this->pObj->pi_linkTP_keepPIvars_url( null, $this->pObj->boolCache );
    $this->pObj->piVars = $arr_tmp;
    $arr_marker_option[ '###URL###' ] = $str_url_wo_piVars;

    $str_hidden = null;
    $str_param = null;
    foreach ( ( array ) $this->pObj->piVars as $key => $values )
    {
      $piVar_key = $this->pObj->prefixId . '[' . $key . ']';
      if ( is_array( $values ) )
      {
        foreach ( ( array ) $values as $value )
        {
          if ( $value == null )
          {
            $str_hidden = $str_hidden . "\n" . $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $value . '">';
          }
        }
      }
      if ( !is_array( $values ) && !( $values == null ) )
      {
        $str_hidden = $str_hidden . "\n" . $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $values . '">';
      }
    }
    unset( $str_param );

    $arr_marker_option[ '###HIDDEN###' ] = $str_hidden;
    // Form action (URL without any parameter)
    #10204, dwildt, 101012
    // Wrap the form
    ////////////////////////////////////////////////////////////////////////////////
    //
        // Add Javascript
    #10204, dwildt, 101012
    // DRS - Development Reporting System
    if ( $this->pObj->b_drs_search || $this->pObj->b_drs_javascript )
    {
      t3lib_div::devlog( '[INFO/SEARCH+JSS] Selectbox for ordering is enabled. It needs jQuery.', $this->pObj->extKey, 0 );
    }
    // DRS - Development Reporting System
    // We need jQuery. Load
    // name has to correspondend with similar code in tx_browser_pi1.php
    $name = 'jQuery';
    // 120915, dwildt, 1-
    //$bool_success_jQuery  = $this->pObj->objJss->load_jQuery();
    // 120918, dwildt, 1+
    $this->pObj->objJss->load_jQuery();

    if ( $this->pObj->objFlexform->bool_ajax_enabled )
    {
      // name has to correspondend with similar code in tx_browser_pi1.php
      $name = 'ajaxLL';
      $path = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'fileLL' ];
      $path_tsConf = 'javascript.ajax.fileLL';
      // #50069, 130716, dwildt, 4+
      $marker = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'fileLL.' ][ 'marker.' ];
      $inline = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'fileLL.' ][ 'inline' ];
      $footer = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'fileLL.' ][ 'footer' ];
      $bool_success = $this->pObj->objJss->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
      // #50069, 130716, dwildt, 1-
      //$bool_success = $this->pObj->objJss->addJssFileToHead($path, $name, $path_tsConf);
      // name has to correspondend with similar code in tx_browser_pi1.php
      $name = 'ajax';
      $path = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'file' ];
      $path_tsConf = 'javascript.ajax.file';
      // #50069, 130716, dwildt, 4+
      $marker = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'file.' ][ 'marker.' ];
      $inline = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'file.' ][ 'inline' ];
      $footer = $this->pObj->conf[ 'javascript.' ][ 'ajax.' ][ 'file.' ][ 'footer' ];
      $bool_success = $this->pObj->objJss->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
      // #50069, 130716, dwildt, 1-
      //$bool_success = $this->pObj->objJss->addJssFileToHead($path, $name, $path_tsConf);
    }

    // Adding Browser General JSS file
    $name = 'general';
    $path = $this->pObj->conf[ 'javascript.' ][ 'general.' ][ 'file' ];
    $path_tsConf = 'javascript.general.file';
    // #50069, 130716, dwildt, 4+
    $marker = $this->pObj->conf[ 'javascript.' ][ 'general.' ][ 'file.' ][ 'marker.' ];
    $inline = $this->pObj->conf[ 'javascript.' ][ 'general.' ][ 'file.' ][ 'inline' ];
    $footer = $this->pObj->conf[ 'javascript.' ][ 'general.' ][ 'file.' ][ 'footer' ];
    $bool_success = $this->pObj->objJss->addJssFileTo( $path, $name, $path_tsConf, $footer, $inline, $marker );
    // #50069, 130716, dwildt, 1-
    //$bool_success = $this->pObj->objJss->addJssFileToHead($path, $name, $path_tsConf);
    // Adding Browser General JSS file
    // Add Javascript
    // #52297, 130926, dwildt, 2-
//      $templateMarker = $this->lDisplayList['selectBox_orderBy.']['templateMarker'];
//      $selectBox      = $this->pObj->cObj->getSubpart( $this->pObj->str_template_raw, $templateMarker );
    // #52297, 130926, dwildt, 4+
    $templateMarker = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'templateMarker' ];
    $arr_result = $this->pObj->getTemplate();
    $str_template_raw = $arr_result[ 'data' ][ 'template' ];
    $selectBox = $this->pObj->cObj->getSubpart( $str_template_raw, $templateMarker );
//var_dump(__METHOD__, __LINE__, $selectBox );
    // DRS - Development Reporting System
    if ( empty( $selectBox ) )
    {
      if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_error )
      {
        t3lib_div::devlog( '[WARN/TEMPLATING] ' . $templateMarker . ' is empty or missing!', $this->pObj->extKey, 2 );
        t3lib_div::devlog( '[INFO/TEMPLATING] Please take care of your HTML code.', $this->pObj->extKey, 1 );
      }
    }
    // DRS - Development Reporting System

    $arr_marker_option[ '###SELECTBOX###' ] = $str_html;
    $selectBox = $this->pObj->cObj->substituteMarkerArray( $selectBox, $arr_marker_option );
    $items = $selectBox;
    // DRS - Development Reporting System
//var_dump(__METHOD__, __LINE__, $selectBox );
    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $templateMarker . ' will be rendered as the select box for ordering records.', $this->pObj->extKey, 0 );
    }
    // DRS - Development Reporting System
//    var_dump( __METHOD__, __LINE__, $items );
//    die( ':(' );
    return $items;
  }

  /**
   * Order the items, add the first item and wrap all items
   *
   * @param	array		$arr_ts: The TypoScript configuration of the object
   * @param	array		$arr_values: The values for the object
   * @param	string		$tableField: The current table.field from the ts filter array
   * @return	array		Return the processed items
   * @internal  Method is moved from class.tx_browser_pi1_filter_3x to here
   * @version 4.1.21
   * @since   2.x
   */
  private function tmplHeadWiSelectBoxOrderBySorting( $arr_ts, $arr_values, $tableField )
  {

    $arr_new_values = null;
    $arr_return = null;

    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';

    $conf_view_path = 'views.' . $viewWiDot . $mode . '.filter.';



    /////////////////////////////////////////////////////////////////
    //
    // Order the values and save the order!
    // #11407: Ordering filter items hasn't any effect
    $arr_values = $this->orderValues( $arr_values, $tableField );
    // Order the values and save the order!
    /////////////////////////////////////////////////////////////////
    //
    // Handle the first_item

    if ( $arr_ts[ 'first_item' ] )
    {
      $bool_handle = true;

      $int_hits = $this->pObj->objFltr4x->sum_hits[ $tableField ];
      if ( $bool_handle )
      {
        // Wrap the item
        $value = $this->pObj->local_cObj->stdWrap( $arr_ts[ 'first_item.' ][ 'stdWrap.' ][ 'value' ], $arr_ts[ 'first_item.' ][ 'stdWrap.' ] );

        // Wrap the hits and add it to the item
        $bool_display_hits = $arr_ts[ 'first_item.' ][ 'display_hits' ];
        if ( $bool_display_hits )
        {
          $conf_hits = $arr_ts[ 'first_item.' ][ 'display_hits.' ][ 'stdWrap.' ];
          $str_hits = $this->pObj->objWrapper4x->general_stdWrap( $int_hits, $conf_hits );
          $bool_behindItem = $arr_ts[ 'first_item.' ][ 'display_hits.' ][ 'behindItem' ];
          if ( $bool_behindItem )
          {
            $value = $value . $str_hits;
          }
          if ( !$bool_behindItem )
          {
            $value = $str_hits . $value;
          }
        }
        // Wrap the hits and add it to the item
        // Prepaire item for adding
        // dwildt, 101211, #11401
        //$arr_new_values[0] = $value;
        $arr_new_values[ $arr_ts[ 'first_item.' ][ 'option_value' ] ] = $value;
        if ( $this->pObj->b_drs_filter )
        {
          t3lib_div :: devLog( '[INFO/FILTER] \'' . $value . '\' is added as the first item.', $this->pObj->extKey, 0 );
          t3lib_div :: devLog( '[HELP/FILTER] If you don\'t want a default item, please configure ' . $conf_view_path . $tableField . '.first_item.', $this->pObj->extKey, 1 );
        }
        // Prepaire item for adding
      }
      // Wrap the first item and prepaire it for adding
    }
    // Handle the first_item
    // Order the values and save the order!
    /////////////////////////////////////////////////////////////////
    //
      // Add the first_item
    // #11407: Ordering filter items hasn't any effect

    if ( is_array( $arr_new_values ) )
    {
      if ( count( $arr_values ) > 0 )
      {
        foreach ( $arr_values as $uid => $value )
        {
          $arr_new_values[ $uid ] = $value;
        }
      }
      unset( $arr_values );
      $arr_values = $arr_new_values;
      unset( $arr_new_values );
    }

    // Add the first_item
    // #11407: Ordering filter items hasn't any effect
    /////////////////////////////////////////////////////////////////
    //
      // stdWrap all items but the first item

    if ( count( $arr_values ) > 0 )
    {
      foreach ( $arr_values as $key => $value )
      {
        if ( $key != $arr_ts[ 'first_item.' ][ 'option_value' ] )
        {
          if ( is_array( $this->pObj->objCal->arr_area[ $tableField ] ) )
          {
            // Do noting. Items were wrapped.
          }
          if ( !is_array( $this->pObj->objCal->arr_area[ $tableField ] ) )
          {
            $tsConf = $arr_ts[ 'wrap.' ][ 'item.' ][ 'stdWrap.' ];
            $value = $this->pObj->local_cObj->stdWrap( $value, $tsConf );
          }
        }
        $arr_values[ $key ] = $value;
      }
    }
    // stdWrap all items but the first item

    $arr_return[ 'data' ][ 'values' ] = $arr_values;
    return $arr_return;
  }

  /**
   * tmplListview() : Building the table with the result in the list view
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  public function tmplListview( $template, $rows )
  {

    // Get the local or the global displayList array
    $lDisplayList = $this->setDisplayList();

    // Remove ###LIST_TITLE### in case of AJAX single view
    $template = $this->tmplListviewAjaxTitle( $template );

    $markerArray = $this->tmpl_marker();

    // dwildt, 140624, 1-: no effect
    //$this->updateWizard( 'displayList.noItemMessage', $lDisplayList );
    // First time on the site?
    $arrFirstVisit = $this->tmplListviewFirstVisit( $template );
    if ( $arrFirstVisit[ 'return' ] )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrFirstVisit[ 'template' ];
    }
    $template = $arrFirstVisit[ 'template' ];
    unset( $arrFirstVisit );

    // Empty rows?
    $arrEmptyRows = $this->tmplListviewEmptyRows( $template, $rows );
    if ( $arrEmptyRows[ 'return' ] )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrEmptyRows[ 'template' ];
    }
    $template = $arrEmptyRows[ 'template' ];
    unset( $arrEmptyRows );

    // Set the groupby mode and get a proper template
    $template = $this->groupBy_verify( $template );

    // Set the global arr_rmFields
    $this->tmpl_rmFields();

    // Init the global array $arrHandleAs
    $this->pObj->objTca->setArrHandleAs();

    $this->pObj->rows = $rows;

    // #i0064, 140714, dwildt, 3-
//    // Get oddClasses
//    $this->oddClassColumns = $lDisplayList[ 'templateMarker.' ][ 'oddClass.' ][ 'columns' ];
//    $this->oddClassRows = $lDisplayList[ 'templateMarker.' ][ 'oddClass.' ][ 'rows' ];
    // Hook for handle the consolidated rows
    $this->hook_row_list_consolidated();

    $rows = $this->pObj->rows;

    $this->setUploadFolder();

    // csv export: Set CSV field devider and field enclosure
    $this->pObj->objExport->csv_init_config();

    // HTML-Template with ###ITEM### ?
    $boolWithItemMarker = $this->tmplWiDefaultLayout( $template, '###LISTBODYITEM###' );

    switch ( $boolWithItemMarker )
    {
      case(true):
        $template = $this->tmplListviewWiItemMarker( $template, $rows );
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After tmplListviewWiItemMarker()' );
        break;
      case(false):
      default:
        $template = $this->tmplListviewWoItemMarker( $template, $rows );
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After tmplListviewWoItemMarker()' );
        break;
    }

    // Fill up the template with content

    $markerArray = $this->tmpl_marker( $markerArray );
    $markerArray[ '###SUMMARY###' ] = $this->pObj->objWrapper4x->tableSummary( 'list' );
    $markerArray[ '###CAPTION###' ] = $this->pObj->objWrapper4x->tableCaption( 'list' );
    $subpart = $this->pObj->cObj->getSubpart( $template, '###LISTVIEW###' );
    $listview = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTVIEW###', $listview, true );
    $template = $this->pObj->cObj->substituteMarkerArray( $template, $markerArray );

    // SEO: Search Engine Optimisation
    $this->tmplListviewSeo( $rows );

    // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );

    return $template;
  }

  /**
   * tmplListviewAjaxTitle() : Remove ###LIST_TITLE### in case of AJAX single view
   *
   * @param	string		$template : the current HTML template with the TYPO3 subparts and markers
   * @param	string    $template :
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewAjaxTitle( $template )
  {

    if ( $this->pObj->segment[ 'header' ] == true )
    {
      return $template;
    }

    if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_javascript )
    {
      $prompt = 'tx_browser_pi1[segment] has a value. AJAX call single view with list view.';
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'AJAX: Do not handle the list title!';
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'AJAX: Subpart ###LIST_TITLE### is removed.';
      t3lib_div::devlog( '[INFO/FLEXFORM+JSS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LIST_TITLE###', null, true );
  }

  /**
   * tmplListview() : Building the table with the result in the list view
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  public function tmplListviewEmptyRows( $template, $rows )
  {
    $arrEmptyRows = array(
      'return' => false,
      'template' => $template
    );

    // RETURN : rows aren't empty
    if ( !empty( $rows ) )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrEmptyRows;
    }

    // RETURN : rows are empty and empty rows should ignored
    if ( $this->ignore_empty_rows_rule )
    {
      // RETURN : no DRS
      if ( !$this->pObj->b_drs_templating )
      {
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
        return $arrEmptyRows;
      }
      $prompt = 'Rows are empty and ignore_empty_rows_rule is true. Workflow will executed.';
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrEmptyRows;
    }

    // #37731, 120604, dwildt, +
    $name = $this->lDisplayList[ 'noItemMessage' ];
    if ( $name == '1' )
    {
      $name = 'TEXT';
    }
    $conf = $this->lDisplayList[ 'noItemMessage.' ];
    $noItemMessage = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTVIEW###', $noItemMessage, true );

    $markerArray = $this->tmpl_marker();
    $markerArray[ '###ITEM###' ] = false;

    $template = $this->pObj->cObj->substituteMarkerArray( $template, $markerArray );

    $arrEmptyRows[ 'return' ] = true;
    $arrEmptyRows[ 'template' ] = $template;

    if ( !$this->pObj->b_drs_warn )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrEmptyRows;
    }

    $prompt = 'There isn\'t any row.';
    t3lib_div::devlog( '[WARN/TEMPLATING] ' . $prompt, $this->pObj->extKey, 2 );
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
    return $arrEmptyRows;
  }

  /**
   * tmplListview() : Building the table with the result in the list view
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 4.4.0
   * @since 1.0.0
   */
  private function tmplListviewFirstVisit( $template )
  {
    $arrFirstVisit = array(
      'return' => false,
      'template' => $template
    );

    if ( !$this->pObj->boolFirstVisit )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrFirstVisit;
    }

    $bool_emptyAtStart = $this->pObj->objFlexform->bool_emptyAtStart;
    if ( !$bool_emptyAtStart )
    {
      if ( !$this->pObj->b_drs_templating )
      {
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
        return $arrFirstVisit;
      }

      $prompt = 'It is the first call for the plugin. The SQL result is replaced with a message.';
      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'If you want to display a list, please configure: displayList.display.emptyListByStart = 0';
      t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrFirstVisit;
    }

    $conf = $this->lDisplayList[ 'display.' ][ 'emptyListByStart.' ][ 'stdWrap.' ];
    $value = $this->pObj->objWrapper4x->general_stdWrap( '', $conf );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTVIEW###', $value, true );

    $markerArray = $this->tmpl_marker();

    $template = $this->pObj->cObj->substituteMarkerArray( $template, $markerArray );

    $arrFirstVisit[ 'return' ] = true;
    $arrFirstVisit[ 'template' ] = $template;

    if ( !($this->pObj->b_drs_templating || $this->pObj->b_drs_flexform) )
    {
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
      return $arrFirstVisit;
    }
    $langKey = $GLOBALS[ 'TSFE' ]->lang;
    if ( $langKey == 'en' )
    {
      $langKey = 'default';
    }
    $prompt = 'It is the first call for the plugin. The SQL result is replaced with a message.';
    t3lib_div::devLog( '[INFO/FLEXFORM + TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'If you want a SQL result instead of the message, please disable '
            . 'in the Browser flexform tab [list view] field [empty list at start] '
            . 'If you want another label, please configure: _LOCAL_LANG.' . $langKey . '.label_first_visit'
    ;
    t3lib_div::devLog( '[HELP/FLEXFORM + TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
    $debugTrailLevel = 1;
    $this->pObj->timeTracking_log( $debugTrailLevel, 'End' );
    return $arrFirstVisit;
  }

  /**
   * tmplListviewSeo() :
   *
   * @return	void
   * @version 5.0.0
   * @since 3.0.0
   */
  private function tmplListviewSeo( $rows )
  {
    reset( $rows );
    $firstKey = key( $rows );
    $this->pObj->objSeo->seo( $rows[ $firstKey ] );
  }

  /**
   * tmplListviewWiItemMarker() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWiItemMarker( $template, $rows )
  {
    switch ( $this->pObj->objZz->get_advanced_5_0_0_useTyposcriptEngine4x() )
    {
      case(true):
        $template = $this->tmplListviewWiItemMarker4x( $template, $rows );
        break;
      case(false):
      default:
        $template = $this->tmplListviewWiItemMarker5x( $template );
        break;
    }
    return $template;
  }

  /**
   * tmplListviewWiItemMarker4x() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWiItemMarker4x( $template, $rows )
  {
    // DRS - Development Reporting System
    if ( $this->pObj->b_drs_templating )
    {
      $prompt = 'HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###. '
              . 'The Browser will replace the ###ITEM### with the SQL result in form of a table or list.'
      ;
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'Change it? Use your own marker like ####TABLE.FIELD### in the HTML template.';
      t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
    }

    // Current group name. Initial value is a timestamp, because the real groupname can be false or empty
    $str_current_group = time();
    // Array with the grouped records
    $arr_htmlGroupby = false;
    // Counter for the groups. It is a need for the current group
    $int_groupCounter = -1;
    // Rows of a group
    $bodyRows = '';
    // Wrap for the groupname
    if ( $this->pObj->str_wrap_grouptitle )
    {
      $arr_wrap_grouptitle = explode( '|', $this->pObj->str_wrap_grouptitle );
    }
    if ( !$this->pObj->str_wrap_grouptitle )
    {
      $arr_wrap_grouptitle = array( false, false );
    }

    // #28562: 110830, dwildt+
    $counterEvenOdd = 0;
    $counter_tr = 0;
    $max_tr = count( $rows ) - 1;
    $firstKey = key( $rows );
    $row = $rows[ $firstKey ];
    $max_elements = 0;

    $addedTableFields = $this->pObj->arrConsolidate[ 'addedTableFields' ];

    // Table header with titles in columns
    $template = $this->tmplHead( $template );

    // 120915, dwildt, 1
    foreach ( array_keys( $row ) as $key )
    {
      if ( in_array( $key, ( array ) $addedTableFields ) )
      {
        continue;
      }
      if ( in_array( $key, ( array ) $this->arr_rmFields ) )
      {
        continue;
      }
      $max_elements++;
    }
    $this->max_elements = $max_elements;
    // elements

    foreach ( ( array ) $rows as $row )
    {
      if ( $this->ignore_empty_rows_rule )
      {
        if ( $this->pObj->b_drs_warn )
        {
          t3lib_div::devLog( '[WARN/TEMPLATING] CONTINUE because of ignore_empty_rows_rule!', $this->pObj->extKey, 2 );
        }
        continue;
      }
      // In case of the first group and a new group
      $str_next_group = $this->groupBy_get_groupname( $row );
      if ( $this->bool_groupby )
      {
        if ( $str_next_group != $str_current_group )
        {
          $str_current_group = $str_next_group;
          $int_groupCounter++;
          $arr_htmlGroupby[ $int_groupCounter ] = $this->pObj->cObj->getSubpart( $template, '###GROUPBY###' );
          $arr_htmlGroupby[ $int_groupCounter ] = str_replace( '###GROUPBY_GROUPNAME###', $arr_wrap_grouptitle[ 0 ] . $str_current_group . $arr_wrap_grouptitle[ 1 ], $arr_htmlGroupby[ $int_groupCounter ] );
          if ( $int_groupCounter > 0 )
          {
            // Allocates the collected rows to the passed group
            $arr_htmlGroupby[ $int_groupCounter - 1 ] = $this->pObj->cObj->substituteSubpart(
                    $arr_htmlGroupby[ $int_groupCounter - 1 ], '###LISTBODY###', $bodyRows, true );
            $bodyRows = '';
          }
        }
      }
      // In case of the first group and a new group

      $markerArray = $this->tmplMarkerCountingRows( $counter_tr, $max_tr, $markerArray );
      $this->tmplRegisterCountingRows( $counter_tr, $max_tr );

      $counter_tr++;
      // #28562: 110830, dwildt+
      // ###LISTBODYITEM###: bodyRows
      $this->pObj->elements = $row;
      // Get the rendered the HTML row
      $htmlRows = $this->tmplRow( $row, '###LISTBODYITEM###', $template );
      // #29370, 110831, dwildt+
      // Remove last devider in case of csv export
      if ( $this->pObj->objExport->str_typeNum == 'csv' )
      {
        $htmlRows = rtrim( $htmlRows, $this->pObj->objExport->csv_devider );
      }

      $listBodyRow = $this->pObj->cObj->getSubpart( $template, '###LISTBODY###' );
      $listBodyRow = $this->pObj->cObj->substituteSubpart( $listBodyRow, '###LISTBODYITEM###', $htmlRows, true );

      $markerBodyRows[ '###CLASS###' ] = $this->tmplTableTrClass( $counterEvenOdd, count( $rows ) );
      // Suggestion #8856, dwildt, 100812

      $listBodyRow = $this->pObj->cObj->substituteMarkerArray( $listBodyRow, $markerBodyRows );
      $bodyRows .= $listBodyRow;
      // ###LISTBODYITEM###: bodyRows
      $counterEvenOdd++;
    }
    // 120915, dwildt, 1+
    unset( $max_tr );
    // elements
//    var_dump( __METHOD__, __LINE__, $bodyRows );
//    $prompt = 'Die at ' . __METHOD__ . '::' . __LINE__;
//    die( $prompt );
    if ( $this->ignore_empty_rows_rule )
    {
      if ( !$this->pObj->b_drs_templating )
      {
        return $template;
      }
      $prompt = '###LISTBODY###, ###GROUPBY### are ignored because of ignore_empty_rows_rule!';
      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      return $template;
    }

    if ( !$this->bool_groupby )
    {
      // $template = $this->pObj->cObj->substituteSubpart($template, '###LISTBODYITEM###', $bodyRows, true);
      $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTBODY###', $bodyRows, true );
      return $template;
    }

    // Allocates the collected rows to the current group
    $arr_htmlGroupby[ $int_groupCounter ] = $this->pObj->cObj->substituteSubpart(
            $arr_htmlGroupby[ $int_groupCounter ], '###LISTBODY###', $bodyRows, true
    );
    $str_htmlGroupby = implode( "\n", $arr_htmlGroupby );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###GROUPBY###', $str_htmlGroupby, true );
    return $template;
  }

  /**
   * tmplListviewWiItemMarker5x() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWiItemMarker5x( $template )
  {
    // Table header with titles in columns
    $template = $this->tmplHead( $template );

    $htmlRows = $this->htmlRows5x( $template, '###LISTBODY###', '###LISTBODYITEM###' );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTBODY###', $htmlRows, true );
    //var_dump( __METHOD__, __LINE__, $htmlRows );
    // RETURN : any DRS prompt isn't needed
    if ( !$this->pObj->b_drs_templating )
    {
      return $template;
    }

    // RETURN : a DRS prompt is needed
    $prompt = 'HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###. '
            . 'The Browser will replace the ###ITEM### with the SQL result in form of a table or list.'
    ;
    t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Change it? Use your own marker like ####TABLE.FIELD### in the HTML template.';
    t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
    return $template;
  }

  /**
   * tmplListviewWoItemMarker() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWoItemMarker( $template, $rows )
  {
    switch ( $this->pObj->objZz->get_advanced_5_0_0_useTyposcriptEngine4x() )
    {
      case(true):
        $template = $this->tmplListviewWoItemMarker4x( $template, $rows );
        break;
      case(false):
      default:
        $template = $this->tmplListviewWoItemMarker5x( $template );
        break;
    }
    return $template;
  }

  /**
   * tmplListviewWoItemMarker4x() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWoItemMarker4x( $template, $rows )
  {
    // DRS - Development Reporting System
    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] HTML-Template subpart ###LISTBODYITEM### doesn\'t contain the marker ###ITEM###.<br />' .
              'The Browser will process all ###TABLE.FIELD### markers instead.', $this->pObj->extKey, 0 );
      t3lib_div::devLog( '[HELP/TEMPLATING] Change it? Remove your markers ####TABLE.FIELD### and use ###ITEM### instead.', $this->pObj->extKey, 1 );
    }
    // DRS - Development Reporting System
    // Select box for ordering
    $template = $this->tmplHead( $template );

    // ###LISTBODY### Content
    // Current group name. Initial value is a timestamp, because the real groupname can be false or empty
    $str_current_group = time();
    // Array with the grouped records
    $arr_htmlGroupby = false;
    // Counter for the groups. It is a need for the current group
    $int_groupCounter = -1;
    // Rows of a group
    $tmpl_rows = '';
    // Wrap for the groupname
    if ( $this->pObj->str_wrap_grouptitle )
    {
      $arr_wrap_grouptitle = explode( '|', $this->pObj->str_wrap_grouptitle );
    }
    if ( !$this->pObj->str_wrap_grouptitle )
    {
      $arr_wrap_grouptitle = array( false, false );
    }
    // Rows
    $counterEvenOdd = 0;
    foreach ( ( array ) $rows as $row => $elements )
    {
      if ( $this->ignore_empty_rows_rule )
      {
        if ( $this->pObj->b_drs_warn )
        {
          t3lib_div::devLog( '[WARN/TEMPLATING] CONTINUE because of ignore_empty_rows_rule!', $this->pObj->extKey, 2 );
        }
        continue;
      }
      // In case of the first group and a new group
      $str_next_group = $this->groupBy_get_groupname( $elements );
      if ( $this->bool_groupby )
      {
        // A new group is starting
        if ( $str_next_group != $str_current_group )
        {
          $str_current_group = $str_next_group;
          $int_groupCounter++;
          $arr_htmlGroupby[ $int_groupCounter ] = $this->pObj->cObj->getSubpart( $template, '###GROUPBY###' );
          $str_current_group_stdWrap = $this->groupBy_stdWrap( $elements );
          $arr_htmlGroupby[ $int_groupCounter ] = str_replace( '###GROUPBY_GROUPNAME###', $arr_wrap_grouptitle[ 0 ] . $str_current_group_stdWrap . $arr_wrap_grouptitle[ 1 ], $arr_htmlGroupby[ $int_groupCounter ] );
          if ( $int_groupCounter > 0 )
          {
            // Allocates the collected rows to the passed group
            $arr_htmlGroupby[ $int_groupCounter - 1 ] = $this->pObj->cObj->substituteSubpart(
                    $arr_htmlGroupby[ $int_groupCounter - 1 ], '###LISTBODY###', $tmpl_rows, true );
            $tmpl_rows = '';
          }
        }
        // A new group is starting
      }
      // In case of the first group and a new group

      $this->pObj->elements = $elements;
      $this->pObj->rows[ $row ] = $rows[ $row ];
      $tmpl_row = $this->tmplRow( $elements, '###LISTBODYITEM###', $template ); //:todo: Performance
      // Remove last devider in case of csv export
      // #29370, 110831, dwildt+
      if ( $this->pObj->objExport->str_typeNum == 'csv' )
      {
        $tmpl_row = rtrim( $tmpl_row, $this->pObj->objExport->csv_devider );
      }

      // Suggestion #8856,  dwildt, 100812
      // Bugfix     #10762, dwildt, 101201
      //$markerBodyRows['###CLASS###'] = ($counterEvenOdd++%2 ? ' class="odd"' : '');
      // #12738, 120515, dwildt
      $str_class = 'item item-' . ( $counterEvenOdd );
      if ( $counterEvenOdd == 0 )
      {
        $str_class = $str_class . ' item-first first';
      }
      else
      {
        if ( ( $counterEvenOdd ) % 2 )
        {
          $str_class = $str_class . ' item-odd odd ';
        }
        if ( count( $rows ) == ( $counterEvenOdd + 1 ) )
        {
          $str_class = $str_class . ' item-last last';
        }
      }
      $markerArray[ '###CLASS###' ] = ' class="' . $str_class . '"';
      // Suggestion #8856, dwildt, 100812
      // Bug #5922, 100210
      if ( !is_array( $markerArray ) )
      {
        $tmpl_rows .= $tmpl_row;
      }
      if ( is_array( $markerArray ) )
      {
        $tmpl_row = $this->pObj->cObj->substituteMarkerArray( $tmpl_row, $markerArray );
        $tmpl_rows .= $tmpl_row;
      }
      $counterEvenOdd++;
    }
    // Rows
    unset( $markerArray );

    // GROUP BY true
    if ( $this->bool_groupby )
    {
      if ( $this->ignore_empty_rows_rule )
      {
        if ( $this->pObj->b_drs_templating )
        {
          t3lib_div::devlog( '[INFO/TEMPLATING] ###LISTBODY###, ###GROUPBY### will ignored because of ignore_empty_rows_rule.', $this->pObj->extKey, 0 );
        }
      }
      if ( !$this->ignore_empty_rows_rule )
      {
        // Allocates the collected rows to the current group
        $arr_htmlGroupby[ $int_groupCounter ] = $this->pObj->cObj->substituteSubpart
                (
                $arr_htmlGroupby[ $int_groupCounter ], '###LISTBODY###', $tmpl_rows, true
        );
        $str_htmlGroupby = implode( "\n", $arr_htmlGroupby );
        $template = $this->pObj->cObj->substituteSubpart( $template, '###GROUPBY###', $str_htmlGroupby, true );
      }
    }
    // GROUP BY true
    // GROUP BY false
    if ( !$this->bool_groupby )
    {
      if ( $this->ignore_empty_rows_rule )
      {
        if ( $this->pObj->b_drs_templating )
        {
          t3lib_div::devlog( '[INFO/TEMPLATING] ###LISTBODY### will ignored because of ignore_empty_rows_rule.', $this->pObj->extKey, 0 );
        }
      }
      if ( !$this->ignore_empty_rows_rule )
      {
        $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTBODY###', $tmpl_rows, true );
      }
    }
    // GROUP BY false

    $this->pObj->rows = $rows;
    // ###LISTBODY### Content

    return $template;
  }

  /**
   * tmplListviewWoItemMarker5x() :
   *
   * @param	string		A HTML template with the TYPO3 subparts and markers
   * @param	array		Array with the records of the SQL result
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplListviewWoItemMarker5x( $template )
  {
    // Table header with titles in columns
    $template = $this->tmplHead( $template );

    $htmlRows = $this->htmlRows5x( $template, '###LISTBODY###', '###LISTBODYITEM###' );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTBODY###', $htmlRows, true );
    //var_dump( __METHOD__, __LINE__, $htmlRows );
    // RETURN : any DRS prompt isn't needed
    if ( !$this->pObj->b_drs_templating )
    {
      return $template;
    }

    // RETURN : a DRS prompt is needed
    $prompt = 'HTML-Template subpart ###LISTBODYITEM### contains the marker ###ITEM###. '
            . 'The Browser will replace the ###ITEM### with the SQL result in form of a table or list.'
    ;
    t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Change it? Use your own marker like ####TABLE.FIELD### in the HTML template.';
    t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
    return $template;
  }

  /**
   * tmplMarkerCountingColumns()  :
   *
   * @param	integer		$currColumn      :  position of the current row
   * @param	integer		$maxColumns      :  max rows
   * @return	array   $markerArray  :
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplMarkerCountingColumns( $currColumn, $maxColumns, $markerArray )
  {
    $odd = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'odd' ];

    $markerArray[ '###TD_COUNTER###' ] = $currColumn;
    $markerArray[ '###TD_EVEN_OR_ODD###' ] = $currColumn % 2 ? $odd : null;

    switch ( true )
    {
      case( $currColumn == 0 ):
        $markerArray[ '###TD_FIRST_LAST###' ] = 'first';
        break;
      case( $currColumn >= $maxColumns ):
        $markerArray[ '###TD_FIRST_LAST###' ] = 'last';
        break;
      default:
        $markerArray[ '###TD_FIRST_LAST###' ] = null;
        break;
    }

    return $markerArray;
  }

  /**
   * tmplMarkerCountingRows()  :
   *
   * @param	integer		$currRow      :  position of the current row
   * @param	integer		$maxRows      :  max rows
   * @return	array   $markerArray  :
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplMarkerCountingRows( $currRow, $maxRows, $markerArray )
  {
    $odd = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'odd' ];

    $markerArray[ '###TR_COUNTER###' ] = $currRow;
    $markerArray[ '###TR_EVEN_OR_ODD###' ] = $currRow % 2 ? $odd : null;

    switch ( true )
    {
      case( $currRow == 0 ):
        $markerArray[ '###TR_FIRST_LAST###' ] = 'first';
        break;
      case( $currRow >= $maxRows ):
        $markerArray[ '###TR_FIRST_LAST###' ] = 'last';
        break;
      default:
        $markerArray[ '###TR_FIRST_LAST###' ] = null;
        break;
    }

    return $markerArray;
  }

  /**
   * tmplRegisterCountingColumns()  :
   *
   * @param	integer		$currColumn : position of the current column
   * @param	integer		$maxColumns : max columns
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplRegisterCountingColumns( $currColumn, $maxColumns )
  {
    $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumn' ] = $currColumn;
    $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnOdd' ] = $currColumn % 2 ? true : false;

    switch ( true )
    {
      case( $currColumn == 0 ):
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnFirst' ] = true;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnLast' ] = false;
        break;
      case( $currColumn == $maxColumns ):
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnFirst' ] = false;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnLast' ] = true;
        break;
      default:
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnFirst' ] = false;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumnLast' ] = false;
        break;
    }
  }

  /**
   * tmplRegisterCountingRows()  :
   *
   * @param	integer		$currRow      :  position of the current row
   * @param	integer		$maxRows      :  max rows
   * @return	void
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplRegisterCountingRows( $currRow, $maxRows )
  {
    $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRow' ] = $currRow;
    $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowOdd' ] = $currRow % 2 ? true : false;

    switch ( true )
    {
      case( $currRow == 0 ):
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowFirst' ] = true;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowLast' ] = false;
        break;
      case( $currRow >= $maxRows ):
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowFirst' ] = false;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowLast' ] = true;
        break;
      default:
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowFirst' ] = false;
        $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numRowLast' ] = false;
        break;
    }
  }

  /**
   * Building a row out of the given record
   *
   * @param	array		The SQL row (elements)
   * @param	string		The subpart marker, which is the template for a row
   * @param	string		Template
   * @return	string		FALSE || HTML string
   * @version 4.1.13
   * @since 1.0.0
   */
  private function tmplRow( $elements, $subpart, $template )
  {
    static $bool_firstLoop = true;

    // DRS - Performance
    if ( $this->pObj->boolFirstRow )
    {
      // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'begin (for the first row only)' );
    }

    // Get the global $arrHandleAs array
    $handleAs = $this->pObj->arrHandleAs;
    // [Boolean] Shouldn't empty values handled?
    $bool_dontHandleEmptyValues = $this->pObj->objFlexform->bool_dontHandleEmptyValues;

    // RETURN : row is empty
    if ( $bool_dontHandleEmptyValues )
    {
      if ( $this->tmplRowIsEmpty( $elements ) )
      {
        return false;
      }
    }

    // displayList || displaySingle
    $lDisplayType = $this->pObj->lDisplayType;
    $lDisplayView = $this->conf_view[ $lDisplayType ];
    if ( !is_array( $lDisplayView ) )
    {
      $lDisplayView = $this->pObj->conf[ $lDisplayType ];
    }

    // Get the uid field
    $uidField = $this->pObj->arrLocalTable[ 'uid' ];

    // Is needed an extra uid field ?
    $bool_extraUidField = $this->tmplRowIsExtraUidField( $uidField );

    $this->pObj->boolFirstElement = true;

    // Default Design
    $bool_design_default = $this->tmplRowIsDefaultDesign( $template );

    // Loop through all elements
    // Is needed for the class property 'last'
    $maxColumns = count( $elements ) - 1;
    // Counts the elements of the row
    $i_count_element = 0;
    // Counts the printed cells like <td>
    $i_count_cell = 0;
    // Counter. 120920, dwildt: seem's to be without any effect
    $counterEvenOdd = 0;
    // Content of the current HTML row
    $htmlRow = false;
    // DRS flag
    $bool_drs_handleCase = false;

    $markerArray = $this->tmpl_marker();

    // #12723, mbless, 110310
    $this->_elements = $elements;
    $this->hook_template_elements();
    $elements = $this->_elements;
    unset( $this->_elements );
    $this->_elementsTransformed = array();
    $this->_elementsBoolSubstitute = array();

    $this->cObjDataAdd( $elements );

    // LOOP elements
    foreach ( ( array ) $elements as $key => $value )
    {
      $boolSubstitute = true;
      $bool_dontColorSwords = false;

      // CONTINUE: field for single view is empty
      if ( $this->tmplRowFieldOfSingleViewIsEmpty( $bool_dontHandleEmptyValues, $value ) )
      {
        continue;
      }

      // CONTINUE: field is part of an image object
      if ( $handleAs[ 'image' ] )
      {
        switch ( true )
        {
          case( $key == $handleAs[ 'imageCaption' ] ):
          case( $key == $handleAs[ 'imageAltText' ] ):
          case( $key == $handleAs[ 'imageTitleText' ] ):
            continue 2;
        }
      }

      $arr_result = $this->pObj->objTca->handleAs(
              $key, $value, $lDisplayView, $bool_drs_handleCase, $bool_dontColorSwords, $elements, $maxColumns, $boolSubstitute
      );
      $value = $arr_result[ 'data' ][ 'value' ];
      $bool_drs_handleCase = $arr_result[ 'data' ][ 'drs_handleCase' ];
      $bool_dontColorSwords = $arr_result[ 'data' ][ 'dontColorSwords' ];
      $maxColumns = $arr_result[ 'data' ][ 'maxColumns' ];
      $boolSubstitute = $arr_result[ 'data' ][ 'boolSubstitute' ];

      // First field is UID and we have a list view
      if ( $bool_extraUidField && $i_count_element == 0 && $this->view == 'list' )
      {
        if ( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
        {
          $bool_drs_handleCase = true;
          $prompt = $key . ' is removed, because it is the first value in the row and ' .
                  'it is the uid of the local table record.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $maxColumns = $maxColumns - 1;
        $boolSubstitute = false;
      }

      // Remove fields, which shouldn't displayed
      if ( in_array( $key, ( array ) $this->arr_rmFields ) )
      {
        if ( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
        {
          $bool_drs_handleCase = true;
          $prompt = $key . ' is in the list of fields, which shouldn\'t displayed.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $maxColumns = $maxColumns - 1;
        $boolSubstitute = false;
      }

      // DRS - Performance
      if ( $this->pObj->boolFirstRow && ( $i_count_element == 0 ) )
      {
        // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After removing fields 1' );
      }

      // Remove fields, which where added because of missing uid and pid
      $addedTableFields = $this->pObj->arrConsolidate[ 'addedTableFields' ];
      if ( in_array( $key, ( array ) $addedTableFields ) )
      {
        if ( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
        {
          $bool_drs_handleCase = true;
          $prompt = $key . ' is in the uid/pid list of the consolidation array. It shouldn\'t displayed.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $maxColumns = $maxColumns - 1;
        $boolSubstitute = false;
      }

      // DRS - Performance
      if ( $this->pObj->boolFirstRow && ( $i_count_element == 0 ) )
      {
        // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After removing fields 2' );
      }

      // DRS- Developement Reporting System: Any Case didn't matched above
      if ( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
      {
        if ( !$bool_drs_handleCase )
        {
          $prompt = 'There isn\'t any handle as case for ' . $key . '.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'If you want a handle as case, please configure the handleAs array.';
          t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }

      // Colors the sword words and phrases
      if ( !$bool_dontColorSwords )
      {
        $value = $this->pObj->objZz->color_swords( $value );
      }

      $this->pObj->boolFirstElement = false;
      $this->pObj->elements = $elements;


      $value = $this->pObj->objWrapper4x->wrapAndLinkValue( $key, $value, $elements[ $uidField ] );

      // DRS - Performance
      if ( $this->pObj->boolFirstRow && $i_count_element == 0 )
      {
        // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After wrap and link value' );
      }
      // DRS - Performance
      // #12723, mbless, 110310
      $this->_elementsTransformed[ $key ] = $value;
      $this->_elementsBoolSubstitute[ $key ] = $boolSubstitute;

      // #36704, dwildt, 120429, 1+
      $i_count_element++;
    }
    // LOOP elements
    // #41129, 120920, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->register[ $this->pObj->extKey . '_numColumn' ] = null;

    $this->hook_template_elements_transformed();

    // #28562: 110830, dwildt+
    $counter_td = 0;
    $max_td = $this->max_elements - 1;
    $addedTableFields = $this->pObj->arrConsolidate[ 'addedTableFields' ];
    // #28562: 110830, dwildt+

    foreach ( $this->_elementsTransformed as $key => $value )
    {
      $boolSubstitute = $this->_elementsBoolSubstitute[ $key ];
      // #12723, mbless, 110310
      // #28562: 110830, dwildt+
      if ( in_array( $key, ( array ) $addedTableFields ) )
      {
        continue;
      }
      if ( in_array( $key, ( array ) $this->arr_rmFields ) )
      {
        continue;
      }
      // #28562: 110830, dwildt+
      // csv export: move value to a proper csv value
      // #29370, 110831, dwildt+
      $value = $this->pObj->objExport->csv_value( $value );

      // Substitute the template marker
      if ( $boolSubstitute )
      {
        $markerArray = $this->tmplMarkerCountingColumns( $counter_td, $max_td, $markerArray );
        $this->tmplRegisterCountingColumns( $counter_td, $max_td );
        $counter_td++;
        // #28562: 110830, dwildt+

        $htmlSubpart = $this->pObj->cObj->getSubpart( $template, $subpart );
        if ( $this->view == 'list' && $bool_design_default )
        {
          // #59669, 140624, dwildt, 2-
//          $class = $i_count_cell < $maxColumns ? 'cell-' . $i_count_cell : 'cell-' . $i_count_cell . ' last';
//          $markerArray[ '###CLASS###' ] = ' class="' . $class . '"';
          // #59669, 140624, dwildt, 1+
          $markerArray[ '###CLASS###' ] = $this->tmplTableTdClass( $i_count_cell, $maxColumns );

          $markerArray[ '###ITEM###' ] = $value;
          $bool_defaultTemplate = true;
          $markerArray[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->pObj->objSocialmedia->get_htmlBookmarks( $elements, $key, $bool_defaultTemplate );
          $htmlRow .= $this->pObj->cObj->substituteMarkerArray( $htmlSubpart, $markerArray );
          //var_dump('template 2256');
        }
        if ( $this->view == 'list' && !$bool_design_default )
        {
          $markerArray[ '###' . strtoupper( $key ) . '###' ] = $value;
          $bool_defaultTemplate = false;
          $markerArray[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->pObj->objSocialmedia->get_htmlBookmarks( $elements, $key, $bool_defaultTemplate );
          $htmlRow = $this->pObj->cObj->substituteMarkerArray( $htmlSubpart, $markerArray );
        }
        if ( $this->view == 'single' && $bool_design_default )
        {
          $markerArray[ '###CLASS###' ] = $counterEvenOdd++ % 2 ? ' class="odd"' : '';
          $markerArray[ '###FIELD###' ] = $this->pObj->objZz->getTableFieldLL( $key );
          $markerArray[ '###VALUE###' ] = $value;
          $bool_defaultTemplate = true;
          $markerArray[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->pObj->objSocialmedia->get_htmlBookmarks( $elements, $key, $bool_defaultTemplate );
          $htmlRow .= $this->pObj->cObj->substituteMarkerArray( $htmlSubpart, $markerArray );
        }
        if ( $this->view == 'single' && !$bool_design_default )
        {
          $markerArray[ '###' . strtoupper( $key ) . '###' ] = $value;
          $bool_defaultTemplate = false;
          $markerArray[ '###SOCIALMEDIA_BOOKMARKS###' ] = $this->pObj->objSocialmedia->get_htmlBookmarks( $elements, $key, $bool_defaultTemplate );
          $htmlRow = $this->pObj->cObj->substituteMarkerArray( $htmlSubpart, $markerArray );
        }
        $i_count_cell++;
      }
      // Substitute the template marker
      // DRS - Performance
      if ( $this->pObj->boolFirstRow && $i_count_element == 0 )
      {
        // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->pObj->timeTracking_log( $debugTrailLevel, 'After substitute marker' );
      }
      // DRS - Performance
      // #36704, dwildt, 120429, 1-
      //$i_count_element++;
    }
    // dwildt, 120915, 1+
    unset( $max_td );
    // Loop through all elements
    // #44858
    $this->cObjDataReset();

    // #12723, mbless, 110310
    unset( $this->_elementsTransformed );
    unset( $this->_elementsBoolSubstitute );
    // #12723, mbless, 110310
    // DRS - Performance
    if ( $this->pObj->boolFirstRow )
    {
      // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->pObj->timeTracking_log( $debugTrailLevel, 'end (for the first row only)' );
    }
    // DRS - Performance

    $this->pObj->boolFirstRow = false;

    $bool_firstLoop = false;

    return $htmlRow;
  }

  /**
   * tmplRowFieldOfSingleViewIsEmpty( )  :  Returns true, if current view is the single view
   *                                        and current field is empty
   *
   * @param	boolean		$bool_dontHandleEmptyValues : Don't handle empty values
   * @param	string		$value                      : current value
   * @return	boolean		true, if current view is the single view and current field is empty
   * @version 4.1.13
   * @since 1.0.0
   */
  private function tmplRowFieldOfSingleViewIsEmpty( $bool_dontHandleEmptyValues, $value )
  {
    // RETURN false : empty values should handled
    if ( !$bool_dontHandleEmptyValues )
    {
      return false;
    }
    // RETURN false : empty values should handled
    // RETURN false : current view isn't the single view
    if ( $this->pObj->view != 'single' )
    {
      return false;
    }
    // RETURN false : current view isn't the single view
    // RETURN false : current value isn't empty
    if ( $value != null )
    {
      return false;
    }
    // RETURN false : current value isn't empty
    // DRS
    if ( $this->pObj->b_drs_templating )
    {
      $prompt = 'Current field is empty. It won\'t handled!';
      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'If empty values should handled, please configure the plugin/flexform [list] ' .
              'field emptyValues.dontHandle = 0.';
      t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
    }
    // DRS
    // RETURN true: current view is the single view and current field is empty
    return true;
  }

  /**
   * tmplRowIsExtraUidField( )  : Returns true, if an extra uid field is needed
   *
   * @param	string		$uidField : label of the uid field
   * @return	boolean		true, if an extra uid field is needed
   * @version 4.1.13
   * @since 1.0.0
   */
  private function tmplRowIsExtraUidField( $uidField )
  {
    // #47679, 130429, dwildt, 1+
    unset( $uidField );
    return false;

    // #47679, 130429, dwildt
//      // RETURN false, if sql manual mode is off
//    if( ! $this->pObj->b_sql_manual )
//    {
//      return false;
//    }
//      // RETURN false, if sql manual mode is off
//
//      // RETURN false, if current uidField is empty
//    if( empty ( $uidField ) )
//    {
//      return false;
//    }
//      // RETURN false, if current uidField is empty
//
//      // DRS
//    if( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating )
//    {
//      $prompt = 'SQL manual mode: If ' . $uidField . ' will be the first field in the row, it will deleted.';
//      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
//    }
//      // DRS
//
//      // RETURN true
////var_dump( __METHOD__, __LINE__, $uidField );
//    return true;
    // #47679, 130429, dwildt
  }

  /**
   * tmplRowIsDefaultDesign( )  : Checks, if ...
   *
   * @param	string		$template : The SQL row (elements)
   * @return	boolean		true, if row is empty, false, if not.
   * @version 4.1.13
   * @since 1.0.0
   */
  private function tmplRowIsDefaultDesign( $template )
  {
    // Default return value
    $isDefaultDesign = true;

    // SWITCH view  : get defined marker
    switch ( $this->pObj->view )
    {
      case( 'list' ):
        $tmpl_element = $this->pObj->cObj->getSubpart( $template, '###LISTBODYITEM###' );
        $pos = strpos( $tmpl_element, '###ITEM###' );
        break;
      case( 'single' ):
        $tmpl_element = $this->pObj->cObj->getSubpart( $template, '###SINGLEBODYROW###' );
        $pos = strpos( $tmpl_element, '###VALUE###' );
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'view is undefined: ' . $this->pObj->view;
        $this->pObj->drs_die( $header, $text );
        break;
    }
    // SWITCH view  : get defined marker
    // IF there isn't any defined marker ...
    if ( $pos === false )
    {
      $isDefaultDesign = false;
    }
    // IF there isn't any defined marker ...
    // RETURN result, if DRS is off
    if ( !( $this->pObj->boolFirstRow && $this->pObj->b_drs_templating ) )
    {
      return $isDefaultDesign;
    }
    // RETURN result, if DRS is off
    // SWITCH view  : DRS
    switch ( $this->pObj->view )
    {
      case( 'list' ):
        switch ( $isDefaultDesign )
        {
          case( false ):
            $prompt = '###LISTBODYITEM### without ###ITEM###. ' .
                    'The Browser process an individual design with TABLE.FIELD markers.';
            t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            break;
          case( true ):
          default:
            $prompt = '###LISTBODYITEM### contains ###ITEM###. ' .
                    'The Browser process the default design with rows.';
            t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            break;
        }
        break;
      case( 'single' ):
        switch ( $isDefaultDesign )
        {
          case( false ):
            $prompt = '###SINGLEBODYROW### without ###VALUE###. ' .
                    'The Browser process an individual design with TABLE.FIELD markers.';
            t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            break;
          case( true ):
          default:
            $prompt = '###SINGLEBODYROW### contains ###VALUE###. ' .
                    'The Browser process the default design with rows.';
            t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            break;
        }
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'view is undefined: ' . $this->pObj->view;
        $this->pObj->drs_die( $header, $text );
        break;
    }
    // SWITCH view  : DRS
    // RETURN result
    return $isDefaultDesign;
  }

  /**
   * tmplRowIsEmpty( )  : Checks, if row is empty. If it is, it returns true.
   *
   * @param	array		The SQL row (elements)
   * @return	boolean		true, if row is empty, false, if not.
   * @version 4.1.13
   * @since 1.0.0
   */
  private function tmplRowIsEmpty( $elements )
  {
    $str_elements = implode( '', $elements );

    // SWITCH : $str_elements
    switch ( $str_elements )
    {
      case( null ):
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'Row is empty. RETURN false.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'If empty rows should handled, please configure the plugin/flexform [list] field emptyValues.dontHandle = 0.';
          t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1 );
        }
        return true;
      default:
        break;
    }
    return false;
  }

  /**
   * Building the searchbox as a form.
   *
   * @param	string		$template: The current template part
   * @return	string		$template: The HTML template part
   * @version 5.0.0
   * @since 1.0.0
   */
  public function tmplSearchBox( $template )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];

    $display = $this->pObj->objFlexform->bool_searchForm && $this->pObj->segment[ 'searchform' ];


    //////////////////////////////////////////////////////////
    //
      // RETURN searchform shouldn't displayed

    if ( !$display )
    {
      $template = $this->pObj->cObj->substituteSubpart( $template, '###SEARCHFORM###', '', true );
      return $template;
    }
    // RETURN searchform shouldn't displayed
    //////////////////////////////////////////////////////////
    //
      // action without filters and sword

    $arr_currPiVars = $this->pObj->piVars;

    // Remove pointer temporarily
    // #i0074, 140720, dwildt, 1-
    //$pageBrowserPointerLabel = $this->conf[ 'navigation.' ][ 'pageBrowser.' ][ 'pointer' ];
    // #i0074, 140720, dwildt, 1+
    $pageBrowserPointerLabel = $this->pObj->conf[ 'navigation.' ][ 'pageBrowser.' ][ 'pointer' ];
    $arr_removePiVars = array( 'sword', 'sort', $pageBrowserPointerLabel );
    // #11576, dwildt, 101219
    if ( !$this->pObj->objFlexform->bool_linkToSingle_wi_piVar_plugin )
    {
      $arr_removePiVars[] = 'plugin';
    }
    foreach ( ( array ) $arr_removePiVars as $str_removePiVars )
    {
      if ( isset( $this->pObj->piVars[ $str_removePiVars ] ) )
      {
        unset( $this->pObj->piVars[ $str_removePiVars ] );
      }
    }
    // Remove pointer temporarily
    // Move $GLOBALS['TSFE']->id temporarily
    // #9458
    $int_tsfeId = $GLOBALS[ 'TSFE' ]->id;
    if ( !empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      $GLOBALS[ 'TSFE' ]->id = $this->pObj->objFlexform->int_viewsListPid;
    }
    // Move $GLOBALS['TSFE']->id temporarily
    // Remove the filter fields temporarily
    // #9495, fsander
    // #11580, dwildt, 101219
    if ( is_array( $conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ] ) )
    {
      $conf_respect_filters = $conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ];
    }
    if ( !is_array( $conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ] ) )
    {
      $conf_respect_filters = $conf[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ];
    }
    if ( $conf_respect_filters[ 'all' ] )
    {
      // Don't remove any filter ...
    }
    if ( !$conf_respect_filters[ 'all' ] )
    {
      // Remove all but ...
      if ( is_array( $conf_respect_filters[ 'but.' ] ) )
      {
        $conf_filter = $conf_view[ 'filter.' ];
        foreach ( ( array ) $conf_respect_filters[ 'but.' ] as $tableWiDot => $arr_fields )
        {
          foreach ( ( array ) $arr_fields as $field_key => $field_value )
          {
            if ( $field_value )
            {
              unset( $conf_filter[ $tableWiDot ][ $field_key ] );
            }
          }
        }
      }
      $this->pObj->piVars = $this->pObj->objZz->removeFiltersFromPiVars( $this->pObj->piVars, $conf_filter );
    }
    // #11580, dwildt, 101219
    // Remove the filter fields temporarily

    $clearAnyway = 0;
    $altPageId = 0;
    $str_action = $this->pObj->pi_linkTP_keepPIvars_url( $this->pObj->piVars, $this->pObj->boolCache, $clearAnyway, $altPageId );

    // Recover piVars
    // #9495, fsander
    $this->pObj->piVars = $arr_currPiVars;
    // Recover piVars
    // 110829, dwildt+
    $markerArray = $this->tmpl_marker();
    // #43778, 121208, dwildt, 1+
    $this->tmpl_marker_rmFilter();
    $markerArray = $this->markerArray;
    // 110829, dwildt+
    // 110829, dwildt-
//    $markerArray                  = $this->pObj->objWrapper4x->constant_markers();
    $markerArray[ '###ACTION###' ] = $str_action;
    $str_sword = stripslashes( $this->pObj->piVars[ 'sword' ] );
    $str_sword = htmlspecialchars( $str_sword );
    $str_sword_default = $this->pObj->pi_getLL( 'label_sword_default', 'Search Word', true );
    $str_sword_default = htmlspecialchars( $str_sword_default );
    // 140712, dwildt, -: Foundation
//    if ( !$str_sword )
//    {
//      $str_sword = $str_sword_default;
//      if ( $this->pObj->b_drs_localisation || $this->pObj->b_drs_templating )
//      {
//        t3lib_div::devLog( '[INFO/LANG+TEMPLATING] Empty Sword becomes the default value: \'' . $str_sword . '\'.', $this->pObj->extKey, 0 );
//        $langKey = $GLOBALS[ 'TSFE' ]->lang;
//        if ( $langKey == 'en' )
//        {
//          $langKey = 'default';
//        }
//        t3lib_div::devLog( '[HELP/LANG+TEMPLATING] Configure it? See _LOCAL_LANG.' . $langKey . '.label_sword_default', $this->pObj->extKey, 1 );
//      }
//    }
    $markerArray[ '###SWORD###' ] = $str_sword;
    $markerArray[ '###SWORD_DEFAULT###' ] = $str_sword_default;
    $markerArray[ '###BUTTON###' ] = $this->pObj->pi_getLL( 'pi_list_searchBox_search', 'Search', true );
    $markerArray[ '###POINTER###' ] = $this->pObj->prefixId . '[pointer]';
    // 110110, cweiske, #11886
    $markerArray[ '###FLEXFORM###' ] = $this->pObj->piVars[ 'plugin' ];
    // 120916, dwildt, 1+
    $markerArray[ '###PLUGIN###' ] = $this->pObj->piVars[ 'plugin' ];
    $markerArray[ '###MODE###' ] = $this->pObj->piVar_mode;
    $markerArray[ '###VIEW###' ] = $this->pObj->view;
    $markerArray[ '###RESULTPHRASE###' ] = $this->resultphrase();

    $str_hidden = null;
    $arr_ts = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ];
    $int_space_left = $arr_ts[ 'wrap.' ][ 'item.' ][ 'nice_html_spaceLeft' ];
    $str_space_left = str_repeat( ' ', $int_space_left );
    foreach ( ( array ) $this->pObj->piVars as $key => $values )
    {
      // #i0074, 140720, dwildt, +
      if( $key == 'pointer' )
      {
        continue;
      }
      $piVar_key = $this->pObj->prefixId . '[' . $key . ']';

      if ( is_array( $values ) )
      {
        foreach ( ( array ) $values as $value )
        {
          if ( $value != null )
          {
            $str_hidden = $str_hidden . PHP_EOL .
                    $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $value . '">';
          }
        }
      }
      if ( !is_array( $values ) && !( $values == null ) )
      {
        $str_hidden = $str_hidden . PHP_EOL .
                $str_space_left . '<input type="hidden" name="' . $piVar_key . '" value="' . $values . '">';
      }
    }
    $markerArray[ '###HIDDEN###' ] = $str_hidden;

    // Removes the marker radialsearch and radius
    $markerArray = $this->tmplSearchBoxRadialsearch( $markerArray );

    $subpart = $this->pObj->cObj->getSubpart( $template, '###SEARCHFORM###' );
    // 3.5.0: We need the subpartmarker for the filter again
    $searchBox = '<!-- ###SEARCHFORM### begin -->
        ' . $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray ) . '
<!-- ###SEARCHFORM### end -->';



    //////////////////////////////////////////////////////////////////////
    //
      // csv export: remove the csv export button
    // #29370, 110831, dwildt+
    if ( !$this->pObj->objFlexform->sheet_viewList_csvexport )
    {
      $searchBox = $this->pObj->cObj->substituteSubpart( $searchBox, '###BUTTON_CSV-EXPORT###', null, true );
    }
    // #29370, 110831, dwildt+
    // #00000, 120126, dwildt+
    if ( $this->pObj->objFlexform->sheet_viewList_csvexport )
    {
      $templateCSV = $this->pObj->cObj->getSubpart( $searchBox, '###BUTTON_CSV-EXPORT###' );
      if ( empty( $templateCSV ) )
      {
        $prompt = '<div style="border:1em solid orange;padding:1em;text-align:center;">
            <h1>
              TYPO3 Browser Warning
            </h1>
            <h2>
              EN: Subpart is missing
            </h2>
            <p>
              English: You enabled the CSV export in the plugin/flexform.<br />
              But the current HTML template doesn\'t contain the subpart ###BUTTON_CSV-EXPORT###.<br />
              Please take care of a proper template and add the subpart.<br />
              See example in res/html/default.templ.<br />
            </p>
            <h2>
              DE: Subpart fehlt
            </h2>
            <p>
              Deutsch: Du hast im Plugin/in der Flexform den CSV-Export aktiviert.<br />
              Aber das aktuelle HTML-Template hat keinen Subpart ###BUTTON_CSV-EXPORT###.<br />
              Bitte k&uuml;mmere Dich um ein korrektes Template und f&uuml;ge den Subpart hinzu.<br />
              Ein Beispiel findest Du in der Datei: res/html/default.tmpl<br />
            </p>
          </div>';
      }
      $template = $prompt . $template;
    }
    // #00000, 120126, dwildt+

    $template = $this->pObj->cObj->substituteSubpart( $template, '###SEARCHFORM###', $searchBox, true );
    // csv export: remove the csv export button

    $this->pObj->piVars = $arr_currPiVars;
    $GLOBALS[ 'TSFE' ]->id = $int_tsfeId; // #9458
    // action without filters and sword

    return $template;
  }

  /**
   * tmplSearchBoxRadialsearch()  : Removes the marker radialsearch and radius
   *                                Both will be set later in the workflow
   *
   * @param	array		$markerArray  : current marker array
   * @return	array $markerArray  : marker array without radialsearch and radius
   * @version 5.0.0
   * @since 5.0.0
   */
  private function tmplSearchBoxRadialsearch( $markerArray )
  {
    unset( $markerArray[ '###RADIALSEARCH###' ] );
    unset( $markerArray[ '###RADIUS###' ] );
    return $markerArray;
  }

  /**
   * tmplSingleview() : Returns the single view
   *
   * @param	string		$template : current template
   * @param	array		$rows     : current rows
   * @return	string
   * @version 5.0.0
   * @since 1.0.0
   */
  public function tmplSingleview( $template, $rows )
  {
    // Get displaySingle configuration
    $lDisplaySingle = $this->setDisplaySingle();

    // Set the globals elements and rows
    $elements = $this->setGlobalElementsOfFirstRow( $rows );
    $this->setGlobalRows( $rows );

    $this->hook_row_single_consolidated();

    // Set the globals elements and rows
    $rows = $this->pObj->rows;
    $elements = $this->setGlobalElementsOfFirstRow( $rows );
    $this->setGlobalRows( $rows );

    $this->updateWizard( 'displaySingle.noItemMessage', $lDisplaySingle );

    // RETURN: There aren't any elements
    if ( empty( $elements ) )
    {
      return $this->tmplSingleviewNoItemMessage( $template );
    }

    // We need $singleRow later for SEO
    $singleRow = $elements;

    // 140630, dwildt, 1+
    $markerArray = $this->tmpl_marker();

    // Replace mode and view in the whole template
    $template = str_replace( '###MODE###', $this->pObj->piVar_mode, $template );
    $template = str_replace( '###VIEW###', $this->pObj->view, $template );

    // Building the back button
    $template = $this->tmplSingleviewBackbutton( $template );

    $this->setUploadFolder();

    // Init the global array $arrHandleAs
    $this->pObj->objTca->setArrHandleAs();

    // Set the global arr_rmFields
    $this->tmpl_rmFields();

    $handleAs = $this->pObj->arrHandleAs;
//var_dump(__METHOD__, __LINE__, $handleAs);
    // Wrap all elements. If the fieldname is a marker in the HTML-Template, it will be replaced
    $markerArray = $this->render_handleAs( $elements, $handleAs, $markerArray );
    $markerArray = $this->pObj->objZz->extend_marker_wi_pivars( $markerArray );

    $lAutoconf = $this->conf_view[ 'autoconfig.' ];
    if ( !is_array( $lAutoconf ) )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] views.single.X. hasn\'t any autoconf array.<br />
          We take the global one.', $this->pObj->extKey, 0 );
      }
      $lAutoconf = $this->pObj->conf[ 'autoconfig.' ];
    }
    // Get the TCA properties from the TypoScript
    $arr_TCAitems = $lAutoconf[ 'autoDiscover.' ][ 'items.' ];

    // Building the result phrase
    $this->resultphrase();

    // Building the body title
    $markerArray[ '###TITLE###' ] = $this->tmplSingleviewTitle( $template, $handleAs, $elements, $arr_TCAitems );
    $markerArray[ '###IMAGE###' ] = $this->tmplSingleviewImage( $template, $handleAs, $elements, $arr_TCAitems );
    $markerArray[ '###TEXT###' ] = $this->tmplSingleviewText( $template, $handleAs, $elements );
    $elements = $this->pObj->elements;

    // Substitute some markers
    $useTyposcriptEngine4x = $this->pObj->objZz->get_advanced_5_0_0_useTyposcriptEngine4x();
    switch ( $useTyposcriptEngine4x )
    {
      case( true ):
        $htmlRow = $this->tmplRow( $elements, '###SINGLEBODYROW###', $template );
        //var_dump( __METHOD__, __LINE__, $template, $htmlRow );
        break;
      case( false ):
      default:
        // #59669, 140624, dwildt, 1+
        $htmlRow = $this->htmlRows5x( $template, '###SINGLEBODY###', '###SINGLEBODYROW###', $markerArray );
//        var_dump( __METHOD__, __LINE__, $htmlRow, $template );
//        var_dump( __METHOD__, __LINE__, $htmlRow );
//        die( 'Auskommentierten Code oben beachten :(' );
//        $template = $this->pObj->cObj->substituteSubpart( $template, '###SINGLEBODY###', $htmlRow, true );
//      var_dump( __METHOD__, __LINE__, 'Titel in den Head!' );
//      var_dump( __METHOD__, __LINE__, $htmlRow, $template );
//      die( ':(' );
//        return $template;
    }

    $singleBody = $this->pObj->cObj->getSubpart( $template, '###SINGLEBODY###' );
    $singleBody = $this->pObj->cObj->substituteSubpart( $singleBody, '###SINGLEBODYROW###', $htmlRow, true );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###SINGLEBODY###', $singleBody, true );

    $markerArray = $this->tmpl_marker( $markerArray );
    $markerArray[ '###SUMMARY###' ] = $this->pObj->objWrapper4x->tableSummary( 'single' );
    $markerArray[ '###CAPTION###' ] = $this->pObj->objWrapper4x->tableCaption( 'single' );

//var_dump(__METHOD__, __LINE__, $markerArray);
    $subpart = $this->pObj->cObj->getSubpart( $template, '###SINGLEVIEW###' );
    $singleview = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###SINGLEVIEW###', $singleview, true );

    // SEO: Search Engine Optimisation
    $this->pObj->objSeo->seo( $singleRow );
    // SEO: Search Engine Optimisation

    return $template;
  }

  /**
   * tmplSingleviewBackbutton() : Returns the template with the back button
   *
   * @param	string		$template : current template
   * @return	string
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplSingleviewBackbutton( $template )
  {

    $bool_backbutton = $this->pObj->lDisplay[ 'backbutton' ];
    if ( !$bool_backbutton )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] Backbutton won\'t be displayed.', $this->pObj->extKey, 0 );
        t3lib_div::devLog( '[HELP/TEMPLATING] Please configure displaySingle.display.backbutton = 1, if you want to display the backbutton.', $this->pObj->extKey, 1 );
      }
      $template = $this->pObj->cObj->substituteSubpart( $template, '###BACKBUTTON###', null, true );
      return $template;
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] Backbutton will be displayed.', $this->pObj->extKey, 0 );
      t3lib_div::devLog( '[HELP/TEMPLATING] Please configure displaySingle.display.backbutton = 0, if you don\'t want any backbutton.', $this->pObj->extKey, 1 );
    }

    $conf_backbutton = $this->pObj->lDisplay[ 'backbutton.' ];
    $str_backbutton = $this->pObj->objWrapper4x->general_stdWrap( '', $conf_backbutton );
    $subpart = $this->pObj->cObj->getSubpart( $template, '###BACKBUTTON###' );
    $backbutton = $this->pObj->cObj->substituteMarkerArray( $subpart, array( '###BUTTON###' => $str_backbutton ) );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###BACKBUTTON###', $backbutton, true );
    return $template;
  }

  /**
   * tmplSingleviewImage() : Returns a value for the marker ###IMAGE###, if the marker is set in the template.
   *
   * @param	string		$template     : current template
   * @param	array		$handleAs     :
   * @param	array		$elements     :
   * @param	array		$arr_TCAitems :
   * @return	string		$title        :
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplSingleviewImage( $template, $handleAs, $elements, $arr_TCAitems )
  {
    $i_pos = strpos( $template, '###IMAGE###' );
    if ( $i_pos === false )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###IMAGE### isn\'t used in the HTML-template.', $this->pObj->extKey, 0 );
      }
      return null;
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###IMAGE### is used in the HTML-template.', $this->pObj->extKey, 0 );
    }

    if ( !$handleAs[ 'image' ] )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] There is no field detected for handle as an image.', $this->pObj->extKey, 0 );
        t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###IMAGE### will be deleted.', $this->pObj->extKey, 0 );
      }
      return null;
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] The field \'' . $handleAs[ 'image' ] . '\' will be wrapped as an IMAGE.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###IMAGE### will be replaced.', $this->pObj->extKey, 0 );
    }

    // image
    $tsImage[ 'image' ] = $elements[ $handleAs[ 'image' ] ];
    $bool_dontColorSwords = $arr_TCAitems[ 'image.' ][ 'dontColorSwords' ];
    if ( !$bool_dontColorSwords )
    {
      $value = $this->pObj->objZz->color_swords( $value );
    }
    // image
    // imageCaption
    $tsImage[ 'imagecaption' ] = $elements[ $handleAs[ 'imageCaption' ] ];
    $bool_dontColorSwords = $arr_TCAitems[ 'imageCaption.' ][ 'dontColorSwords' ];
    if ( !$bool_dontColorSwords )
    {
      $value = $this->pObj->objZz->color_swords( $value );
    }
    // imageCaption
    // imageAltText
    $tsImage[ 'imagealttext' ] = $elements[ $handleAs[ 'imageAltText' ] ];
    $bool_dontColorSwords = $arr_TCAitems[ 'imageAltText.' ][ 'dontColorSwords' ];
    if ( !$bool_dontColorSwords )
    {
      $value = $this->pObj->objZz->color_swords( $value );
    }
    // imageAltText
    // imageTitleText
    $tsImage[ 'imagetitletext' ] = $elements[ $handleAs[ 'imageTitleText' ] ];
    $bool_dontColorSwords = $arr_TCAitems[ 'imageTitleText.' ][ 'dontColorSwords' ];
    if ( !$bool_dontColorSwords )
    {
      $value = $this->pObj->objZz->color_swords( $value );
    }
    // imageTitleText
    unset( $this->pObj->elements[ $handleAs[ 'image' ] ] );
    unset( $this->pObj->elements[ $handleAs[ 'imageCaption' ] ] );
    unset( $this->pObj->elements[ $handleAs[ 'imageAltText' ] ] );
    unset( $this->pObj->elements[ $handleAs[ 'imageTitleText' ] ] );

    return $value;
  }

  /**
   * tmplSingleviewNoItemMessage()  : Returns an empty template with a no item message
   *
   * @param	string		$template : current template
   * @return	string		$template : empty template with no item message
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplSingleviewNoItemMessage( $template )
  {
    // DRS
    if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/SQL+TEMPLATING] We haven\'t any elements!', $this->pObj->extKey, 0 );
    }

    $template = $this->pObj->cObj->substituteSubpart( $template, '###SINGLEVIEW###', '', true );

    $cObj_name = $this->lDisplaySingle[ 'noItemMessage' ];
    if ( $cObj_name == '1' )
    {
      $cObj_name = 'TEXT';
    }
    $cObj_conf = $this->lDisplaySingle[ 'displayList.' ][ 'noItemMessage.' ];
    $noItemMessage = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTVIEW###', $noItemMessage, true );
    $markerArray = $this->tmpl_marker();
    $template = $this->pObj->cObj->substituteMarkerArray( $template, $markerArray );
    return $template;
  }

  /**
   * tmplSingleviewText() : Returns a value for the marker ###TEXT###, if the marker is set in the template.
   *
   * @param	string		$template     : current template
   * @param	array		$handleAs     :
   * @param	array		$elements     :
   * @param	array		$arr_TCAitems :
   * @return	string		$title        :
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplSingleviewText( $template, $handleAs, $elements )
  {
    // Is the system marker ###TEXT### defined?
    $i_pos = strpos( $template, '###TEXT###' );
    if ( $i_pos === false )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TEXT### isn\'t used in the HTML-template.', $this->pObj->extKey, 0 );
      }
      return null;
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TEXT### is used in the HTML-template.', $this->pObj->extKey, 0 );
    }
    // Is the system marker ###TEXT### defined?

    if ( !$handleAs[ 'text' ] )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] There is no field detected for handle as text.', $this->pObj->extKey, 0 );
        t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TEXT### will be deleted.', $this->pObj->extKey, 0 );
      }
      return null;
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] The field \'' . $handleAs[ 'text' ] . '\' will be wrapped as TEXT.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TEXT### will be replaced.', $this->pObj->extKey, 0 );
    }
    $text = $this->pObj->objWrapper4x->general_stdWrap( $elements[ $handleAs[ 'text' ] ], $this->lDisplaySingle[ 'content_stdWrap.' ] );
    $text = $this->pObj->objZz->color_swords( $text );
    unset( $this->pObj->elements[ $handleAs[ 'text' ] ] );
    return $text;
  }

  /**
   * tmplSingleviewTitle() : Returns a value for the marker ###TITLE###, if the marker is set in the template.
   *
   * @param	string		$template     : current template
   * @param	array		$handleAs     :
   * @param	array		$elements     :
   * @param	array		$arr_TCAitems :
   * @return	string		$title        :
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplSingleviewTitle( $template, $handleAs, $elements, $arr_TCAitems )
  {
    $title = null;
    $displayTitle = $this->pObj->lDisplay[ 'title' ];

    // RETURN : null, because title should not used
    if ( !$displayTitle )
    {
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] No field won\'t be handled as the title.', $this->pObj->extKey, 0 );
        t3lib_div::devLog( '[HELP/TEMPLATING] Please configure displaySingle.display.title = 1, if you want an automatically title handling.', $this->pObj->extKey, 1 );
      }

      return null;
    }

    $pos = strpos( $template, '###TITLE###' );
    // RETURN : null, because ###TITLE### isn't used in the template
    if ( $pos === false )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'The system marker ###TITLE### isn\'t used in the HTML-template.';
        t3lib_div::devlog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return null;
    }

    // RETURN : $handleAs['title'] is empty
    if ( empty( $handleAs[ 'title' ] ) )
    {
      list($table, $field) = explode( '.', $this->pObj->arrLocalTable[ 'uid' ] );
      $title = 'ID ' . $this->pObj->piVars[ 'showUid' ] . ' from table ' . $table;
      if ( $this->pObj->b_drs_templating )
      {
        t3lib_div::devlog( '[INFO/TEMPLATING] \'' . $title . '\' will be handled as the title.', $this->pObj->extKey, 0 );
        t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TITLE### will be replaced.', $this->pObj->extKey, 0 );
      }
      return value;
    }

    list($table, $field) = explode( '.', $handleAs[ 'title' ] );
    $title = $elements[ $handleAs[ 'title' ] ];
    $this->arr_curr_value[ $handleAs[ 'title' ] ] = $title;

    // Colors the sword words and phrases
    $bool_dontColorSwords = $arr_TCAitems[ 'title.' ][ 'dontColorSwords' ];
    if ( !$bool_dontColorSwords )
    {
      $title = $this->pObj->objZz->color_swords( $title );
    }

    if ( $this->pObj->b_drs_templating )
    {
      t3lib_div::devlog( '[INFO/TEMPLATING] ' . $handleAs[ 'title' ] . ' will be handled as the title.', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/TEMPLATING] The system marker ###TITLE### will be replaced.', $this->pObj->extKey, 0 );
      t3lib_div::devLog( '[HELP/TEMPLATING] Please configure displaySingle.display.title = 0, if you don\'t want any title handling.', $this->pObj->extKey, 1 );
    }

    $key = $handleAs[ 'title' ];
    $title = $this->pObj->objWrapper4x->wrapAndLinkValue( $key, $title, 0 );
    unset( $this->pObj->elements[ $handleAs[ 'title' ] ] );

    return $title;
  }

  /**
   * tmplTableTdClass() :
   *
   * @param	integer      $currPosition
   * @param	integer      $max
   * @return	string   $class
   * @version 5.0.0
   * @since 2.0.0
   */
  private function tmplTableTdClass( $currPosition, $max )
  {
    $class = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'td' ];
    return $this->tmplTableTrTdClass( $class, $currPosition, $max );
  }

  /**
   * tmplTableTrClass() :
   *
   * @param	integer      $currPosition
   * @param	integer      $max
   * @return	string   $class
   * @version 5.0.0
   * @since 2.0.0
   */
  private function tmplTableTrClass( $currPosition, $max )
  {
    $class = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'tr' ];
    return $this->tmplTableTrTdClass( $class, $currPosition, $max );
  }

  /**
   * tmplTableTrTdClass() :
   *
   * @param	string      $name
   * @param	integer      $currPosition
   * @param	integer      $max
   * @return	string   $class
   * @version 5.0.0
   * @since 2.0.0
   */
  private function tmplTableTrTdClass( $name, $currPosition, $max )
  {
    $odd = $this->lDisplayList[ 'templateMarker.' ][ 'cssClass.' ][ 'odd' ];

    $max = $max - 1;
    $class = $name . ' ' . $name . '-' . $currPosition;
    if ( $currPosition == 0 )
    {
      $class = $class . ' ' . $name . '-first first';
    }
    else
    {
      if ( $currPosition % 2 )
      {
        $class = $class . ' ' . $name . '-' . $odd . ' ' . $odd;
      }
      if ( $max <= $currPosition )
      {
        $class = $class . ' ' . $name . '-last last';
      }
    }
    $class = ' class="' . $class . '"';
//var_dump(__METHOD__, __LINE__, $class);
    return $class;
  }

  /**
   * tmplWiDefaultLayout() : Returns true, if the template contains the marker ###ITEM### or ###VALUE###
   *
   * @param	string      $template       : the current HTML template
   * @param	string      $subpartMarker  : subpartmarker
   * @return	boolean   Returns true, if the template contains the marker ###ITEM### or ###VALUE###
   * @version 5.0.0
   * @since 1.0.0
   */
  private function tmplWiDefaultLayout( $template, $subpartMarker )
  {
    $subpart = $this->pObj->cObj->getSubpart( $template, $subpartMarker );
    $posItem = strpos( $subpart, '###ITEM###' );
    $posValue = strpos( $subpart, '###VALUE###' );
    switch ( true )
    {
      case( $posItem !== false ):
      case( $posValue !== false ):
        return true;
      default:
        return false;
    }
  }

  /**
   * tmpl_marker(): Set some global marker
   *
   * @return	array   $markerArray
   * @version 5.0.0
   * @since 4.0.0
   * @internal #59669
   */
  private function tmpl_marker( $markerArray = array() )
  {
    $markerArray = $this->pObj->objMarker->extend_marker( $markerArray );
    $this->markerArray = $markerArray;
    return $markerArray;

//    // Set marker
//    $markerArray[ '###MODE###' ] = $this->pObj->piVar_mode;
//    $markerArray[ '###VIEW###' ] = $this->pObj->view;
//    $markerArray = $this->pObj->objMarker->extend_marker_wi_cObjData( $markerArray );
//    $constantMarkers = $this->pObj->objWrapper4x->constant_markers();
//    foreach ( ( array ) $constantMarkers as $key => $value )
//    {
//      $markerArray[ $key ] = $value;
//    }
//
//    $this->markerArray = $markerArray;
//    return $markerArray;
  }

  /**
   * tmpl_marker_rmMarker(): Remove fields, if they are filter
   *
   * @return	void
   * @version   4.2.0
   * @since     4.2.0
   * @internal  43778
   */
  private function tmpl_marker_rmFilter()
  {
    // LOOP each filter
    foreach ( ( array ) $this->conf_view[ 'filter.' ] as $tableWiDot => $fields )
    {
      foreach ( array_keys( ( array ) $fields ) as $field )
      {
        // CONTINUE : field has an dot
        if ( rtrim( $field, '.' ) != $field )
        {
          continue;
        }
        // CONTINUE : field has an dot

        $currFilter = $tableWiDot . $field;
        $hashFilter = '###' . strtoupper( $currFilter ) . '###';

        unset( $this->markerArray[ $hashFilter ] );
      }
    }
    // LOOP each filter
  }

  /**
   * tmpl_rmFields( ):  Get the field names, which should not displayed.
   *                    Set the global arr_rmFields
   *
   * @return	void
   * @version 4.0.0
   * @since 4.0.0
   */
  private function tmpl_rmFields()
  {
    // RETURN global $arr_rmFields is set
    if ( is_array( $this->arr_rmFields ) )
    {
      return;
    }
    // RETURN global $arr_rmFields is set
    $conf_rmFields = $this->conf_view[ 'functions.' ][ 'clean_up.' ][ 'csvTableFields' ];
    $arr_rmFields = $this->pObj->objZz->getCSVasArray( $conf_rmFields );
    $lArr_RmFields[ 0 ] = array_flip( $arr_rmFields );
    $arr_result = $this->pObj->objSqlFun_3x->rows_with_cleaned_up_fields( $lArr_RmFields );
    $lArr_RmFields = $arr_result[ 'data' ][ 'rows' ];
    $this->arr_rmFields = ( array ) $arr_rmFields;
  }

  /*   * *********************************************
   *
   * cObjData
   *
   * ******************************************** */

///**
// * cObjDataAddFieldsWoLocaltable( ):
// *
// * @param	array		$elements: The current record
// * @return	void
// * @internal  #44858
// * @version   4.4.4
// * @since     4.4.4
// */
//  private function cObjDataAddFieldsWoLocaltable( $elements )
//  {
//    $fieldsWoLocaltable = null;
//
//      // FOREACH  : elements
//    foreach( ( array ) $elements as $tableField => $value )
//    {
//      list( $table, $field ) = explode( '.', $tableField );
//      if( $table == $this->pObj->localTable )
//      {
//        $fieldsWoLocaltable[ $field ] = $value;
//      }
//    }
//      // FOREACH  : elements
//
//    if( is_array( $fieldsWoLocaltable ) )
//    {
//      $this->pObj->objCObjData->add( $fieldsWoLocaltable );
//    }
//
//    unset( $fieldsWoLocaltable );
//  }
  /**
   * typeNumIsCsv() :
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function typeNumIsCsv()
  {
    if ( $this->pObj->objExport->str_typeNum != 'csv' )
    {
      return false;
    }

    if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
    {
      t3lib_div::devlog( '[INFO/EXPORT] TypeNum is CSV. The default ###LISTHEAD### is taken.', $this->pObj->extKey, 0 );
    }
    return true;
  }

  /**
   * updateWizard( ): Checks, if TypoScript of the current view has deprecated properties.
   *                  It is relevant only, if the update wizard is enabled.
   *
   * @param	integer		$uid        : uid of the current item / row
   * @param	string		$value      : value of the current item / row
   * @return	string		$item       : The rendered item
   * @version 5.0.0
   * @since 5.0.0
   */
  private function updateWizard( $check, $lDisplayList )
  {
    if ( !$this->pObj->arr_extConf[ 'updateWizardEnable' ] )
    {
      return;
    }
    // Current IP has access
    if ( !$this->pObj->bool_accessByIP )
    {
      return;
    }

    switch ( $check )
    {
      case( 'displaySingle.noItemMessage' ):
      case( 'displayList.noItemMessage' ):
        if ( $lDisplayList[ 'noItemMessage' ] == '1' )
        {
          $prompt_01 = '
            Deprecated: ' . $check . ' = 1<br />
            Please use: <br />
            ' . $check . ' = TEXT<br />
            ';
        }
        if ( $prompt_01 )
        {
          echo '
            <div style="border:1em solid red;padding:2em;background:white;">
              <h1>TYPO3 Browser Update Wizard</h1>
            ';
          if ( $prompt_01 )
          {
            echo '
                <p>
                  ' . $prompt_01 . '
                </p>
              ';
          }
          echo '
            </div>
            ';
        }
        break;
    }
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_template.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_template.php' ]);
}
?>
