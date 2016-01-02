<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2016 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_search bundles template methods for the search
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 7.1.1
 * @since 6.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   85: class tx_browser_pi1_search
 *  149:     function __construct($parentObj)
 *  168:     function searchform($template)
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
 * 2959:     private function markerExtend()
 * 2981:     private function markerRemoveFilter()
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
class tx_browser_pi1_search
{

  // [Array] The current TypoScript configuration array
  private $conf = false;
  // [Integer] The current mode (from modeselector)
  private $mode = false;
  // [String] 'list' or 'single': The current view
  private $view = false;
  // [Array] The TypoScript configuration array of the current view
  private $conf_view = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  private $conf_path = false;
  // [Array] Local or global TypoScript array with the displayList properties
  private $lDisplayList;
  private $piVarsForCHash = null;
  // [INTEGER] Current TYPO3 version as an integer like 4007007 for 4.7.7
  private $typo3Version = null;

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

    // [Integer] The current mode (from modeselector)
    $this->mode = $this->pObj->piVar_mode;
    // [String] 'list' or 'single': The current view
    $this->view = $this->pObj->view;
    $this->conf_path = $this->pObj->get_confPath();
    // [Array] The TypoScript configuration array of the current view
    $this->conf_view = $this->pObj->get_confView();
  }

  /**
   * cHash( )  : Returns the cHash value, which is needed for the TYPO3 API
   *
   * @return	string  $cHash
   * @version 6.0.0
   * @since 6.0.0
   */
  private function cHash()
  {
    $cHash = null;

    if ( $this->piVarsForCHash === null )
    {
      return $cHash;
    }

    // TypoScript typolink
    $name = 'TEXT';
    $conf = array(
      'typolink.' => array(
        'parameter' => $GLOBALS[ 'TSFE' ]->id,
        'additionalParams' => $this->piVarsForCHash,
        'useCacheHash' => '1',
        'returnLast' => 'url'
      )
    );

    // Render the TypoScript
    $url = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    $params = explode( '&', $url );
    foreach ( $params as $param )
    {
      list($key, $value) = explode( '=', $param );
      if ( $key != 'cHash' )
      {
        continue;
      }
      $cHash = $value;
      break;
    }

    return $cHash;
  }

  /**
   * htmlSpaceLeft( )  :
   *
   * @return	void
   * @version 6.0.0
   * @since 6.0.0
   */
  private function htmlSpaceLeft()
  {
    $arr_ts = $this->lDisplayList[ 'selectBox_orderBy.' ][ 'selectbox.' ];
    $int_space_left = $arr_ts[ 'wrap.' ][ 'item.' ][ 'nice_html_spaceLeft' ];
    if ( ( int ) $int_space_left < 1 )
    {
      $int_space_left = 14;
    }
    $str_space_left = str_repeat( ' ', $int_space_left );
    return $str_space_left;
  }

  /**
   * init( )  :
   *
   * @return	void
   * @version 6.0.0
   * @since 6.0.0
   */
  private function init()
  {
    $this->initDisplayList();
    // #61520, 140915, dwildt, 1+
    $this->init_typo3version();
    return;
  }

  /**
   * initDisplayList() : Set the global $this->lDisplayList
   *
   * @return	array		$lDisplayList : displayList configuration
   * @version 6.0.0
   * @since 2.0.0
   */
  private function initDisplayList()
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
   * init_typo3version( ): Get the current TYPO3 version
   *
   * @internal  #61520
   *
   * @return    integer
   * @version 6.0.0
   * @since   6.0.0
   */
  private function init_typo3version()
  {
    $this->typo3Version = $this->pObj->getTypo3Version();
  }

  /**
   * marker( )  : Set the global $this->markerArray.
   *
   * @return	array $this->markerArray
   * @version 6.0.0
   * @since 6.0.0
   */
  private function marker()
  {
    $backupPiVars = $this->pObj->piVars;
    $backupTSFEid = $GLOBALS[ 'TSFE' ]->id;

    $this->replaceTSFEid();

    // #i0122, 150205, dwildt: must run before removeUnwantedPiVars()
    $this->markerSword();

    $this->removeUnwantedPiVars();
    $this->removeFilterFromPiVars();

    $this->markerArray[ '###ACTION###' ] = $this->markerAction();

    $this->markerExtend();
    $this->markerRemoveFilter();

    $this->markerArray[ '###BUTTON###' ] = $this->pObj->pi_getLL( 'pi_list_searchBox_search', 'Search', true );
    $this->markerArray[ '###POINTER###' ] = $this->pObj->prefixId . '[pointer]';
    $this->markerArray[ '###FLEXFORM###' ] = $this->pObj->piVars[ 'plugin' ];
    $this->markerArray[ '###MODE###' ] = $this->pObj->piVar_mode;
    $this->markerArray[ '###VIEW###' ] = $this->pObj->view;
    $this->markerArray[ '###RESULTPHRASE###' ] = $this->resultphrase();
    $this->markerArray[ '###HIDDEN###' ] = $this->markerHidden();

    // Removes the marker radialsearch and radius
    $this->markerArray = $this->searchformRadialsearch( $this->markerArray );

    // Recover piVars
    $this->pObj->piVars = $backupPiVars;
    $GLOBALS[ 'TSFE' ]->id = $backupTSFEid; // #9458

    return $this->markerArray;
  }

  /**
   * markerAction( ) : Returns the value for the form property action
   *
   * @return	string		$action
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerAction()
  {
    $clearAnyway = 0;
    $altPageId = 0;
    // #61594, 140915, dwildt, 1-
    //$action = $this->pObj->pi_linkTP_keepPIvars_url( $this->pObj->piVars, $this->pObj->boolCache, $clearAnyway, $altPageId );
    // #61594, 140915, dwildt, 2+
    $cache = 0;
    $action = $this->pObj->pi_linkTP_keepPIvars_url( $this->pObj->piVars, $cache, $clearAnyway, $altPageId );
    return $action;
  }

  /**
   * markerExtend(): Set some global marker
   *
   * @return	void
   * @version 6.0.0
   * @since 4.0.0
   * @internal #59669
   */
  private function markerExtend()
  {
    $this->markerArray = $this->pObj->objMarker->extend_marker( $this->markerArray );
  }

  /**
   * markerHidden( )  : Returns the HTML hidden fields for the current form
   *
   * @return	array $hidden
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerHidden()
  {
    $hidden = null;
    $str_space_left = $this->htmlSpaceLeft();

    foreach ( ( array ) $this->pObj->piVars as $key => $values )
    {
      // #i0074, 140720, dwildt, +
      if ( $key == 'pointer' )
      {
        continue;
      }

      $piVar_key = $this->pObj->prefixId . '[' . $key . ']';
      switch ( true )
      {
        case(is_array( $values )):
          $hidden = $this->markerHiddenPiVarsArray( $values, $piVar_key, $str_space_left, $hidden );
          break;
        case(!is_array( $values )):
        default;
          $hidden = $this->markerHiddenPiVarsString( $values, $piVar_key, $str_space_left, $hidden );
          break;
      }
    }
    $hidden = $this->markerHiddenCHash( $str_space_left, $hidden );
    $hidden = $this->markerHiddenNoCache( $str_space_left, $hidden );
    return $hidden;
  }

  /**
   * markerHiddenCHash( )  : Returns the HTML hidden field cHash for the current form
   *
   * @param   integer   $str_space_left : the sum of HTML space at the left margin
   * @param   string    $hidden         : former hidden fields
   * @return	string    $hidden         : new hidden fields
   * @version 6.0.0
   * @since   6.0.0
   */
  private function markerHiddenCHash( $str_space_left, $hidden )
  {
    $cHash = $this->cHash();
    $hidden = $this->markerHiddenString( $cHash, 'cHash', $str_space_left, $hidden );
    return $hidden;
  }

  /**
   * markerHiddenNoCache( )  : Returns the HTML hidden field no_cache for the current form
   *
   * @param   integer   $str_space_left : the sum of HTML space at the left margin
   * @param   string    $hidden         : former hidden fields
   * @return	string    $hidden         : new hidden fields
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerHiddenNoCache( $str_space_left, $hidden )
  {
    $hidden = $this->markerHiddenString( 1, 'no_cache', $str_space_left, $hidden );
    $this->setPiVarsForCHash( 'no_cache', 1 );
    return $hidden;
  }

  /**
   * markerHiddenPiVarsArray( )  : Returns an HTML hidden field (array)
   *                               Adds the fields to the global $setPiVarsForCHash
   *
   * @param   array     $values         : values of the current piVar
   * @param   string    $piVar_key      : key of the current piVar
   * @param   integer   $str_space_left : the sum of HTML space at the left margin
   * @param   string    $hidden         : former hidden fields
   * @return	string    $hidden         : new hidden fields
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerHiddenPiVarsArray( $values, $piVar_key, $str_space_left, $hidden )
  {
    foreach ( array_keys( ( array ) $values ) as $value )
    {
      $hidden = $this->markerHiddenPiVarsString( $value, $piVar_key, $str_space_left, $hidden );
    }
    return $hidden;
  }

  /**
   * markerHiddenPiVarsString( )  : Returns an HTML hidden field (string).
   *                                Adds this field to the global $setPiVarsForCHash
   *
   * @param   array     $values         : values of the current piVar
   * @param   string    $piVar_key      : key of the current piVar
   * @param   integer   $str_space_left : the sum of HTML space at the left margin
   * @param   string    $hidden         : former hidden fields
   * @return	string    $hidden         : new hidden fields
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerHiddenPiVarsString( $value, $piVar_key, $str_space_left, $hidden )
  {
    if ( $value === null )
    {
      return $hidden;
    }

    $this->setPiVarsForCHash( $piVar_key, $value );

    $hidden = $this->markerHiddenString( $value, $piVar_key, $str_space_left, $hidden );
    return $hidden;
  }

  /**
   * markerHiddenString( )  : Returns an HTML hidden field (string)
   *
   * @param   array     $values         : values of the current piVar
   * @param   string    $piVar_key      : key of the current piVar
   * @param   integer   $str_space_left : the sum of HTML space at the left margin
   * @param   string    $hidden         : former hidden fields
   * @return	string    $hidden         : new hidden fields
   * @version 7.1.1
   * @since 6.0.0
   */
  private function markerHiddenString( $value, $key, $str_space_left, $hidden )
  {
    if ( $value === null )
    {
      return $hidden;
    }
    // #i0171, 150501. dwildt, 1+
    $value = htmlentities( $value );
    $hidden = $hidden . PHP_EOL
            . $str_space_left . '<input type="hidden" name="' . $key . '" value="' . $value . '">';
    return $hidden;
  }

  /**
   * markerRemoveFilter(): Remove fields, if they are a filter
   *
   * @return	void
   * @version   6.0.0
   * @since     4.2.0
   * @internal  43778
   */
  private function markerRemoveFilter()
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
   * markerSword( )  : Adds the swird and the sword default to the current global -marker array
   *
   * @return	void
   * @version 6.0.0
   * @since 6.0.0
   */
  private function markerSword()
  {
    // #i0170, 150430, dwildt, 2-/2+
    //$sword = stripslashes( $this->pObj->piVars[ 'sword' ] );
    //$sword = htmlspecialchars( $sword );
    $stripslashes = true;
    $strip_tags = true;
    $htmlspecialchars = true;
    $quoteStr = false;
    $sword = $this->pObj->objZz->secure_piVar( $this->pObj->piVars[ 'sword' ], 'sword', $stripslashes, $strip_tags, $htmlspecialchars, $quoteStr );
    $swordDefault = $this->pObj->pi_getLL( 'label_sword_default', 'Search Word', true );
    $swordDefault = htmlspecialchars( $swordDefault );

    $this->markerArray[ '###SWORD###' ] = $sword;
    $this->markerArray[ '###SWORD_DEFAULT###' ] = $swordDefault;
  }

  /**
   * removeFilterFromPiVars( ) :
   *
   * @return	void
   * @version 6.0.0
   * @since 1.0.0
   */
  private function removeFilterFromPiVars()
  {
    if ( is_array( $this->conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ] ) )
    {
      $conf_respect_filters = $this->conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ];
    }
    if ( !is_array( $this->conf_view[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ] ) )
    {
      $conf_respect_filters = $this->conf[ 'displayList.' ][ 'display.' ][ 'searchform.' ][ 'respect_filters.' ];
    }

    if ( $conf_respect_filters[ 'all' ] )
    {
      // Don't remove any filter ...
      return;
    }

    $conf_filter = $this->conf_view[ 'filter.' ];

    // Remove all but ...
    if ( is_array( $conf_respect_filters[ 'but.' ] ) )
    {
      $conf_filter = $this->conf_view[ 'filter.' ];
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

  /**
   * removeUnwantedPiVars( ) :
   *
   * @return	void
   * @internal #61594
   * @version 6.0.0
   * @since 1.0.0
   */
  private function removeUnwantedPiVars()
  {

    $arr_removePiVars = array(
//      'bugfix',
      'plugin',
      $this->pObj->conf[ 'navigation.' ][ 'pageBrowser.' ][ 'pointer' ],
      'sort',
      'sword'
    );

    foreach ( ( array ) $arr_removePiVars as $str_removePiVars )
    {
      if ( isset( $this->pObj->piVars[ $str_removePiVars ] ) )
      {
        unset( $this->pObj->piVars[ $str_removePiVars ] );
      }
    }

    return;
  }

  /**
   * removeUnwantedPiVarsWorkaroundCHashComparisonFailed( ) :
   *                  140915: If there is one param in the URL at least (like id=123), then
   *                    the TypoScriptFrontendController will process the form with the GET method even it has a POST method.
   *                    The workaround is, to add the POST params to the URL.
   *                    TYPO3 error prompts:
   *                    * Page Not Found
   *                      Reason: Request parameters could not be validated (&cHash comparison failed)
   *                    * Page Not Found
   *                      Reason: Request parameters could not be validated (&cHash empty)
   *                  See
   *                  * typo3_src-6.2.2/typo3/sysext/frontend/Classes/Controller
   *                    * TypoScriptFrontendController.php::makeCacheHash( )
   *                    * TypoScriptFrontendController.php::reqCHash( )
   *
   * @return	void
   * @version 6.0.0
   * @since 6.0.0
   */
  private function removeUnwantedPiVarsWorkaroundCHashComparisonFailed()
  {
    global $TYPO3_CONF_VARS;

    if ( $this->typo3Version < 6000000 )
    {
      return false;
    }

    //var_dump( __METHOD__, __LINE__, $TYPO3_CONF_VARS[ 'FE' ][ 'pageNotFoundOnCHashError' ] );
    if ( !$TYPO3_CONF_VARS[ 'FE' ][ 'pageNotFoundOnCHashError' ] )
    {
      if ( !$this->pObj->b_drs_TYPO3_6x )
      {
        return false;
      }
      $prompt = '$TYPO3_CONF_VARS[ FE ][ pageNotFoundOnCHashError ] = false';
      t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
      $prompt = 'You will get an URL without params. It\'s proper!';
      t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
      return false;
    }

    if ( !$this->pObj->b_drs_TYPO3_6x )
    {
      return true;
    }
    $prompt = '$TYPO3_CONF_VARS[ FE ][ pageNotFoundOnCHashError ] = true';
    t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'You will get an URL with params. It\'s a workaround for the API cHash bug. It isn\'t beautiful but proper.';
    t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * replaceTSFEid( ) :
   *
   * @return	void
   * @version 6.0.0
   * @since 3.0.0
   */
  private function replaceTSFEid()
  {
    if ( empty( $this->pObj->objFlexform->int_viewsListPid ) )
    {
      return;
    }
    $GLOBALS[ 'TSFE' ]->id = $this->pObj->objFlexform->int_viewsListPid;
    return;
  }

  /**
   * requirements( ) : Requirements for the workflow of the searchform
   *
   * @param	string		$searchform
   * @return	string		$searchform
   * @internal #61594
   * @version 6.0.2
   * @since 6.0.0
   */
  private function requirements( $searchform )
  {
    global $TYPO3_CONF_VARS;

    if ( $this->typo3Version < 6000000 )
    {
      return $searchform;
    }
//var_dump ( __METHOD__, __LINE__, $this->pObj->arr_extConf );
    //var_dump( __METHOD__, __LINE__, $TYPO3_CONF_VARS[ 'FE' ][ 'pageNotFoundOnCHashError' ] );
    if ( $TYPO3_CONF_VARS[ 'FE' ][ 'pageNotFoundOnCHashError' ] )
    {
      // #62607, 141102, dwildt, +
      $display = $this->pObj->arr_extConf[ 'drs_pageNotFoundOnCHashError' ];
      if ( $display === null )
      {
        $display = 1;
      }
      if ( $display )
      {
        $prompt = $this->pObj->pi_getLL( 'error_pageNotFoundOnCHashError' );

        return '<div style="border:solid 1em red;padding:1em;text-align:center;">'
                . $prompt
                . '</div>'
                . $searchform;
      }
    }

    if ( !$TYPO3_CONF_VARS[ 'FE' ][ 'pageNotFoundOnCHashError' ] )
    {
      $this->requirementsPageNotFoundOnCHashErrorDRS();
      return $searchform;
    }

    // #62610, 141102, dwildt, +
    $display = $this->pObj->arr_extConf[ 'drs_cHashExcludedParameters' ];
    if ( $display === null )
    {
      $display = 1;
    }
    switch ( true )
    {
      case( $this->requirementsCHashExcludedParameters() ):
      case(!$display ):
        $this->requirementsCHashExcludedParametersDRS();
        return $searchform;
      default:
        $prompt = $this->pObj->pi_getLL( 'error_cHashExcludedParameters_sword' );
        return '<div style="border:solid 1em red;padding:1em;text-align:center;">'
                . $prompt
                . '</div>'
                . $searchform;
    }
  }

  /**
   * requirementsCHashExcludedParameters( ) : Returns true, if $TYPO3_CONF_VARS[ 'FE' ][ 'cHashExcludedParameters' ]
   *                                          contains tx_browser_pi1[sword]
   *
   * @return	boolean
   * @internal #61594
   * @version 6.0.0
   * @since 6.0.0
   */
  private function requirementsCHashExcludedParameters()
  {
    global $TYPO3_CONF_VARS;

    $cHashExcludedParameters = $TYPO3_CONF_VARS[ 'FE' ][ 'cHashExcludedParameters' ];
    $cHashExcludedParameters = explode( ',', $cHashExcludedParameters );

    $swordKey = $this->pObj->prefixId . '[sword]';
    //var_dump( __METHOD__, __LINE__, $swordKey, $cHashExcludedParameters );
    if ( in_array( $swordKey, $cHashExcludedParameters ) )
    {
      return true;
    }

    return false;
  }

  /**
   * requirementsCHashExcludedParametersDRS( ) : DRS prompt only
   *
   * @return	void
   * @internal #61594
   * @version 6.0.0
   * @since 6.0.0
   */
  private function requirementsCHashExcludedParametersDRS()
  {
    if ( !$this->pObj->b_drs_TYPO3_6x )
    {
      return;
    }

    $swordKey = $this->pObj->prefixId . '[sword]';
    $prompt = '$TYPO3_CONF_VARS[ FE ][ cHashExcludedParameters ] contains ' . $swordKey
            . '. This is proper.';
    t3lib_div::devlog( '[OK/TYPO3_6x] ' . $prompt, $this->pObj->extKey, -1 );
    return;
  }

  /**
   * requirementsPageNotFoundOnCHashErrorDRS( ) : DRS prompt only
   *
   * @return	void
   * @internal #61594
   * @version 6.0.0
   * @since 6.0.0
   */
  private function requirementsPageNotFoundOnCHashErrorDRS()
  {
    if ( !$this->pObj->b_drs_TYPO3_6x )
    {
      return;
    }
    $prompt = '$TYPO3_CONF_VARS[ FE ][ pageNotFoundOnCHashError ] = false';
    t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'Search form won\'t take care about cHash issues. It\'s proper.';
    t3lib_div::devlog( '[INFO/TYPO3_6x] ' . $prompt, $this->pObj->extKey, 0 );
    return;
  }

  /**
   * resultphrase( )  : Building the result phrase for the search form.
   *
   * @return	string		Rendered rusult phrase
   * @version   6.0.0
   * @since     2.0.0
   */
  public function resultphrase()
  {
    /**
     * This method corresponds with tx_browser_pi1_zz::color_swords($tableField, $str_content)
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
    $arr_conf_advanced = $this->conf[ 'advanced.' ];
    if ( !empty( $this->conf_view[ 'advanced.' ] ) )
    {
      $arr_conf_advanced = $this->conf_view[ 'advanced.' ];
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
   * setPiVarsForCHash( ) :
   *
   * @return	void
   * @version 6.0.0
   * @since 6.0.0
   */
  private function setPiVarsForCHash( $piVar_key, $value )
  {
    $this->piVarsForCHash = $this->piVarsForCHash
            . '&' . $piVar_key . '=' . $value;
//    var_dump( __METHOD__, __LINE__, $this->piVarsForCHash );
  }

  /**
   * searchform( ) : Building the searchbox form.
   *
   * @param	string		$template: The current template part
   * @return	string		$template: The HTML template part
   * @version 6.0.0
   * @since 1.0.0
   */
  public function searchform( $template )
  {
    $this->init();

    // RETURN template without searchform
    if ( !$this->searchformDisplay() )
    {
      $template = $this->pObj->cObj->substituteSubpart( $template, '###SEARCHFORM###', '', true );
      return $template;
    }

    $markerArray = $this->marker();

    $searchform = $this->pObj->cObj->getSubpart( $template, '###SEARCHFORM###' );
    $searchform = $this->requirements( $searchform );
    $searchform = '<!-- ###SEARCHFORM### begin -->
        ' . $this->pObj->cObj->substituteMarkerArray( $searchform, $markerArray ) . '
<!-- ###SEARCHFORM### end -->';
    // Remove the CSV button, if it isn't needed
    $template = $this->searchformWoCsvButton( $template, $searchform );

    return $template;
  }

  /**
   * searchformDisplay( )  : Returns false, if searchbox shouldn't dislayed
   *
   * @return	boolean
   * @version 6.0.0
   * @since 1.0.0
   */
  private function searchformDisplay()
  {
    $display = $this->pObj->objFlexform->bool_searchForm && $this->pObj->segment[ 'searchform' ];

    switch ( $display )
    {
      case( false ):
        return false;
      case( true ):
      default:
        return true;
    }
  }

  /**
   * searchformRadialsearch()  : Removes the marker radialsearch and radius
   *                                Both will be set later in the workflow
   *
   * @param	array		$markerArray  : current marker array
   * @return	array $markerArray  : marker array without radialsearch and radius
   * @version 5.0.0
   * @since 5.0.0
   */
  private function searchformRadialsearch( $markerArray )
  {
    unset( $markerArray[ '###RADIALSEARCH###' ] );
    unset( $markerArray[ '###RADIUS###' ] );
    return $markerArray;
  }

  /**
   * searchformWoCsvButton( )  :
   *
   * @param	string		$template
   * @param	string		$searchform
   * @return	string $template
   * @version 5.0.0
   * @since 5.0.0
   */
  private function searchformWoCsvButton( $template, $searchform )
  {
    if ( !$this->pObj->objFlexform->sheet_viewList_csvexport )
    {
      $searchform = $this->pObj->cObj->substituteSubpart( $searchform, '###BUTTON_CSV-EXPORT###', null, true );
      $template = $this->pObj->cObj->substituteSubpart( $template, '###SEARCHFORM###', $searchform, true );
      return $template;
    }

    $templateCSV = $this->pObj->cObj->getSubpart( $searchform, '###BUTTON_CSV-EXPORT###' );
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
            Ein Beispiel findest Du in der Datei: Resources/Private/Templates/HTML/default.tmpl<br />
          </p>
        </div>';
      $template = $prompt . $template;
    }
    $template = $this->pObj->cObj->substituteSubpart( $template, '###SEARCHFORM###', $searchform, true );
    return $template;
  }

//  /**
//   * tmplSingleviewNoItemMessage()  : Returns an empty template with a no item message
//   *
//   * @param	string		$template : current template
//   * @return	string		$template : empty template with no item message
//   * @version 5.0.0
//   * @since 1.0.0
//   */
//  private function tmplSingleviewNoItemMessage( $template )
//  {
//    // DRS
//    if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_templating )
//    {
//      t3lib_div::devlog( '[INFO/SQL+TEMPLATING] We haven\'t any elements!', $this->pObj->extKey, 0 );
//    }
//
//    $template = $this->pObj->cObj->substituteSubpart( $template, '###SINGLEVIEW###', '', true );
//
//    $cObj_name = $this->lDisplaySingle[ 'noItemMessage' ];
//    if ( $cObj_name == '1' )
//    {
//      $cObj_name = 'TEXT';
//    }
//    $cObj_conf = $this->lDisplaySingle[ 'displayList.' ][ 'noItemMessage.' ];
//    $noItemMessage = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//    $template = $this->pObj->cObj->substituteSubpart( $template, '###LISTVIEW###', $noItemMessage, true );
//    $markerArray = $this->markerExtend();
//    $template = $this->pObj->cObj->substituteMarkerArray( $template, $markerArray );
//    return $template;
//  }
}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_search.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_search.php' ]);
}
?>
