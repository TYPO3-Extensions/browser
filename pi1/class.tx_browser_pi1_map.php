<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

/**
 * The class tx_browser_pi1_map bundles methods for rendering and processing calender based content, filters and category menues
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 6.0.7
 * @since 3.9.6
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  112: class tx_browser_pi1_map
 *
 *              SECTION: Constructor
 *  156:     function __construct($pObj)
 *
 *              SECTION: Categories
 *  176:     private function categoriesFormInputs( )
 *  247:     private function categoriesGet( )
 *  379:     private function categoriesMoreThanOne( )
 *
 *              SECTION: cObject
 *  428:     private function cObjDataAddArray( $keyValue )
 *  463:     private function cObjDataAddMarker( )
 *  505:     private function cObjDataAddRow( $row )
 *  538:     private function cObjDataRemoveArray( $keyValue )
 *  553:     private function cObjDataRemoveMarker( )
 *  574:     private function cObjDataRemoveRow( $row )
 *
 *              SECTION: Main
 *  600:     public function get_map( $template )
 *
 *              SECTION: Init
 *  682:     private function init(  )
 *  704:     private function initCatDevider( )
 *  719:     private function initMainMarker( $template )
 *
 *              SECTION: Init global variables
 *  809:     private function initVar(  )
 *  877:     private function initVarConfMap(  )
 *  916:     private function initVarEnabled(  )
 *  977:     private function initVarProvider(  )
 * 1001:     private function initVarTypeNum(  )
 *
 *              SECTION: Map rendering
 * 1039:     private function renderMap( $template )
 *
 *              SECTION: Map center and zoom automatically
 * 1083:     private function renderMapAutoCenterCoor( $map_template, $coordinates )
 * 1182:     private function renderMapAutoCenterCoorVers12( $objLibMap )
 * 1201:     private function renderMapAutoCenterCoorVers13( $objLibMap )
 * 1221:     private function renderMapAutoZoomLevel( $map_template, $longitudes, $latitudes )
 *
 *              SECTION: Map HTML template
 * 1349:     private function renderMapGetTemplate( $template )
 *
 *              SECTION: Map rendering marker
 * 1444:     private function renderMapMarker( $template, $mapTemplate )
 * 1483:     private function renderMapMarkerCategoryIcons( )
 * 1570:     private function renderMapMarkerPoints( )
 * 1826:     private function renderMapMarkerPointsToJson( $mapMarkers )
 * 1925:     private function renderMapMarkerSnippetsHtml( $map_template, $tsProperty )
 * 1978:     private function renderMapMarkerSnippetsHtmlCategories( $map_template )
 * 2011:     private function renderMapMarkerSnippetsHtmlDynamic( $map_template )
 * 2029:     private function renderMapMarkerSnippetsJssDynamic( $map_template )
 * 2080:     private function renderMapMarkerVariablesDynamic( $map_template )
 * 2131:     private function renderMapMarkerVariablesSystem( $map_template )
 * 2170:     private function renderMapMarkerVariablesSystemItem( $item )
 *
 *              SECTION: Map rendering Route
 * 2193:     private function renderMapRoute( )
 * 2235:     private function renderMapRouteMarker( )
 * 2253:     private function renderMapRoutePaths( )
 *
 *              SECTION: Set Page Type
 * 2279:     public function set_typeNum( )
 *
 * TOTAL FUNCTIONS: 40
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_map
{

  // [OBJECT] parent object
  public $pObj = null;
  // [STRING] $viewWiDot . $mode. Example: 1.single
  private $conf_path = null;
  // [ARRAY] TypoScript configuration array of the current view
  private $conf_view = null;
  // [INTEGER] UID of the current view
  private $mode = null;
  // [INTEGER] Id of the single view
  private $singlePid = null;
  // [STRING] Type of the current view: list or single
  private $view = null;
  // [BOOLEAN] Is map enabled? Will set by init( ) while runtime
  public $enabled = null;
  // [STRING] GoogleMaps, Open Street Map
  private $provider = null;
  // [ARRAY] TypoScript configuration array. Will set by init( ) while runtime
  private $confMap = null;
  // [Integer] Number of the current typeNum
  public $int_typeNum = null;
  // [String] Name of the current typeNum
  public $str_typeNum = null;
  // [ARRAY] Contains the categories of the current records
  private $arrCategories = null;
  // #i0062, 140714, dwildt, 2+
  // [ARRAY] Contains the rows without any category and the key of the "no category" label of the category form
  private $arrWoCategories = null;
  // [BOOLEAN] true, if there are more than one category
  private $boolMoreThanOneCategory = null;
  // [STRING] Devider of categories. Example: ', ;|;'
  private $catDevider = null;
  // [array] rows
  private $rowsBackup = null;

  /*   * *********************************************
   *
   * Constructor
   *
   * ******************************************** */

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */
  function __construct( $pObj )
  {
    $this->pObj = $pObj;
  }

  /*   * *********************************************
   *
   * Categories
   *
   * ******************************************** */

  /**
   * categoriesEmpty( ): Returns true, if categories are empty
   *
   * @param array     $categoryLabels
   * @return	boolean
   * @internal #i0102
   * @version 6.0.7
   * @since   6.0.7
   */
  private function categoriesEmpty( $categoryLabels )
  {
    if ( !empty( $categoryLabels ) )
    {
      return false;
    }

    if ( !$this->pObj->b_drs_warn )
    {
      return true;
    }

    $prompt = 'There isn\'t any row with lat and lon.';
    t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
    return true;
  }

  /**
   * categoriesFormInputs( ): Returns the input fields for the category form
   *
   * @return	string
   * @version 5.0.10
   * @since   4.1.4
   */
  private function categoriesFormInputs()
  {
    // Get the field name of the field with the category icon
    // #47631, dwildt, 1-
    //$arrLabels[ 'catIcon' ] = $this->confMap['configuration.']['categories.']['fields.']['categoryIcon'];
    // #47631, #i0007, dwildt, 10+
    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        $arrLabels[ 'catIcon' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'categoryIcon' ];
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        $arrLabels[ 'catIcon' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryIcon' ];
        break;
    }
    // #47631, #i0007, dwildt, 10+
    // Default space in HTML code
    $tab = '                    ';

    // FOREACH category label
//$this->pObj->dev_var_dump( $this->arrCategories );
    // #i0118, dwildt, 1-/+
    //foreach ( $this->arrCategories[ 'labels' ] as $labelKey => $labelValue )
    foreach ( ( array ) $this->arrCategories[ 'labels' ] as $labelKey => $labelValue )
    {
      // Get the draft for an input field
      $cObj_name = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'form_input' ];
      $cObj_conf = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'form_input.' ];
      $input = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // replace the category marker
      $input = str_replace( '###CAT###', $labelValue, $input );
      // 4.1.17, 120927, dwildt
      // replace the category marker
      //$labelValueWoSpc = str_replace( ' ', null, $labelValue );
      $labelValueWoSpc = $this->zz_properFormLabel( $labelValue );
      $input = str_replace( '###CAT_WO_SPC###', $labelValueWoSpc, $input );
      // 4.1.17, 120927, dwildt
      // #54548, 131221, dwildt, 6+
      $class = $this->arrCategories[ 'cssClass' ][ $labelKey ];
      if ( !empty( $class ) )
      {
        $class = ' class="' . $class . '"';
      }
      $input = str_replace( '###CLASS###', $class, $input );

      // IF draft for an input field contains ###IMG###, render an image
      $pos = strpos( $input, '###IMG###' );
      if ( !( $pos === false ) )
      {
        // SWITCH : Render the image
        switch ( true )
        {
          // #i0062
          case( $labelKey == $this->arrWoCategories[ 'iconKey' ] ):
            $name = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'legend.' ][ $labelKey ];
            $conf = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'legend.' ][ $labelKey . '.' ];
            $img = $this->pObj->cObj->cObjGetSingle( $name, $conf );
            break;
          case( is_array( $this->arrCategories[ 'icons' ] ) ):
            // 4.1.7, dwildt, +
            $this->cObjDataAddArray( array( $arrLabels[ 'catIcon' ] => $this->arrCategories[ 'icons' ][ $labelKey ] ) );
            $img = $this->renderMapMarkerVariablesSystemItem( 'categoryIconLegend' );
            $this->cObjDataRemoveArray( array( $arrLabels[ 'catIcon' ] => $this->arrCategories[ 'icons' ][ $labelKey ] ) );
            // 4.1.7, dwildt, +
            break;
          default:
            // Render the image
            $name = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'legend.' ][ $labelKey ];
            $conf = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'legend.' ][ $labelKey . '.' ];
            $img = $this->pObj->cObj->cObjGetSingle( $name, $conf );
            break;
        }
        // SWITCH : Render the image

        $input = str_replace( '###IMG###', $img, $input );
      }
      // IF draft for an input field contains ###IMG###, render an image

      $arrInputs[] = $tab . $input;
    }
    // FOREACH category label
    // Move array of input fields to a string
    // #i0118, dwildt, 1-/+
    //$inputs = implode( PHP_EOL, $arrInputs );
    $inputs = implode( PHP_EOL, ( array ) $arrInputs );
    $inputs = trim( $inputs );

    // RETURN input fields
    return $inputs;
  }

  /**
   * categoriesGet( ): Get the category labels from the current rows. And set it in $this->arrCategories.
   *
   * @return	array		$this->arrCategories
   * @version 6.0.7
   * @since   4.1.4
   */
  private function categoriesGet()
  {
//var_dump(__METHOD__, __LINE__, array_keys($this->confMap));
//var_dump(__METHOD__, __LINE__, array_keys( $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ]));
    // RETURN : method is called twice at least
    if ( $this->arrCategories != null )
    {
      return $this->arrCategories;
    }
    // RETURN : method is called twice at least
    // Local array for category labels
    $catLabels = null;
    // Local array for category icons
    $catIcons = null;
    // #54548, 131221, dwildt, 2+
    // Local array for category label css classes
    $catCss = null;

    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        // Get the field name of the field with the category label
        $fieldForLabel = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'category' ];
        // Get the field name of the field with the category icon
        $fieldForIcon = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'categoryIcon' ];
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        // Get the field name of the field with the category label
        $fieldForLabel = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryTitle' ];
        // #54548, 131221, dwildt, 4+
        // Get the field name of the field with the category label class for a marker category
        $fieldForCssMarker = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryCssMarker' ];
        // Get the field name of the field with the category label class for a path category
        $fieldForCssPath = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryCssPath' ];
        // Get the field name of the field with the category icon
        $fieldForIcon = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryIcon' ];
        break;
    }
    // #47631, #i0007, dwildt, 10+
    // Get categories from the rows
    $categoryLabels = array();

    // FOREACH row
    foreach ( $this->pObj->rows as $row )
    {
      // RETURN : field for category label is missing
      // 130530, dwildt
      switch ( true )
      {
        case(!$fieldForLabel ):
          // DRS
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'table.field with the category is empty';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
            $prompt = 'Please use the TypoScript Constant Editor and maintain map.marker.field.category ';
            t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 1 );
          }
          // DRS
//var_dump(__METHOD__, __LINE__, $fieldForLabel);
          $this->arrCategories = array();
          return $this->arrCategories;
        // #47602, 130911, dwildt, 1+
        case(!array_key_exists( $fieldForLabel, $row ) ):
          // DRS
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'current rows doesn\'t contain the field "' . $fieldForLabel . '"';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
          }
          // DRS
//var_dump(__METHOD__, __LINE__, $fieldForLabel, array_keys($row));
          $this->arrCategories = array();
          return $this->arrCategories;
        // #i0076, 140721, dwildt, +
        case($this->categoriesGetWoLatLon( $row ) ):
          continue 2;
        default:
        // follow the workflow
      }
      // RETURN : field for category label is missing
      // 4.1.7, dwildt, 1-
      //$categoryLabels =  array_merge( $categoryLabels, explode( $this->catDevider, $row[ $fieldForLabel ] ) );
      // 4.1.7, dwildt, 10+
      $catLabelsOfCurrRow = explode( $this->catDevider, $row[ $fieldForLabel ] );
      foreach ( $catLabelsOfCurrRow as $labelKey => $labelValue )
      {
        // #47602, 130911, dwildt, 4+
        if ( empty( $labelValue ) )
        {
          $labelValue = $this->pObj->pi_getLL( 'phrase_noMapCat' );
          // #i0062, 140714, 1+
          $this->arrWoCategories[ 'rows' ][] = $row[ $this->pObj->arrLocalTable[ 'uid' ] ];
        }
        $categoryLabels[] = $labelValue;
        if ( isset( $row[ $fieldForIcon ] ) )
        {
          $catIconsOfCurrRow = explode( $this->catDevider, $row[ $fieldForIcon ] );
          $categoryIcons[ $labelValue ] = $catIconsOfCurrRow[ $labelKey ];
        }
        // #54548, 131221, dwildt, 13+
        switch ( true )
        {
          case( isset( $row[ $fieldForCssMarker ] ) ):
            $catCssOfCurrRow = explode( $this->catDevider, $row[ $fieldForCssMarker ] );
            $categoryCss[ $labelValue ] = $catCssOfCurrRow[ $labelKey ];
            break;
          case( isset( $row[ $fieldForCssPath ] ) ):
            $catCssOfCurrRow = explode( $this->catDevider, $row[ $fieldForCssPath ] );
            $categoryCss[ $labelValue ] = $catCssOfCurrRow[ $labelKey ];
            break;
          default;
            break;
        }
      }
      // 4.1.7, dwildt, 10+
    }
    // FOREACH row
    // Get categories from the rows
    // #i0120, 150101, dwildt: 4+
    if ( $this->categoriesEmpty( $categoryLabels ) )
    {
      return false;
    }
    // Remove non unique category labels
    $categoryLabels = array_unique( $categoryLabels );
//var_dump (__METHOD__, __LINE__, $categoryLabels);
//var_dump(__METHOD__, __LINE__);
    // Order the category labels
    $orderBy = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'orderBy' ];
    switch ( $orderBy )
    {
      case( 'SORT_REGULAR' ):
        sort( $categoryLabels, SORT_REGULAR );
        break;
      case( 'SORT_NUMERIC' ):
        sort( $categoryLabels, SORT_NUMERIC );
        break;
      case( 'SORT_STRING' ):
        sort( $categoryLabels, SORT_STRING );
        break;
      case( 'SORT_LOCALE_STRING' ):
        sort( $categoryLabels, SORT_LOCALE_STRING );
        break;
      default:
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'configuration.categories.orderBy has an unproper value: "' . $orderBy . '"';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'categories will ordered by SORT_REGULAR!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        }
        sort( $categoryLabels, SORT_REGULAR );
        break;
    }
    // Order the category labels
    // Set the keys: keys should correspond with keys of the item colours
    $maxItem = count( $categoryLabels );
    $counter = 0;
//var_dump(__METHOD__, __LINE__);
//var_dump(__METHOD__, __LINE__, array_keys($this->confMap));
    foreach ( array_keys( $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ] ) as $catKey )
    {
      if ( substr( $catKey, -1 ) == '.' )
      {
        continue;
      }
      $catLabels[ $catKey ] = $categoryLabels[ $counter ];
      if ( isset( $row[ $fieldForIcon ] ) )
      {
        $catIcons[ $catKey ] = $categoryIcons[ $categoryLabels[ $counter ] ];
      }

      // #i0062, 140714, 4+
      if ( $catLabels[ $catKey ] == $this->pObj->pi_getLL( 'phrase_noMapCat' ) )
      {
        $this->arrWoCategories[ 'iconKey' ] = $catKey;
      }
      // #54548, 131221, dwildt, 9+
      switch ( true )
      {
        case( isset( $row[ $fieldForCssMarker ] ) ):
        case( isset( $row[ $fieldForCssPath ] ) ):
          $catCss[ $catKey ] = $categoryCss[ $categoryLabels[ $counter ] ];
          break;
        default;
          break;
      }
      $counter++;
      if ( $counter >= $maxItem )
      {
        break;
      }
    }
    // Set the keys: keys should correspond with keys of the item colours

    $this->arrCategories[ 'labels' ] = $catLabels;
    if ( isset( $row[ $fieldForIcon ] ) )
    {
      $this->arrCategories[ 'icons' ] = $catIcons;
    }
    // #54548, 131221, dwildt, 8+
    if ( !empty( $catCss ) )
    {
      $this->arrCategories[ 'cssClass' ] = $catCss;
    }
//var_dump(__METHOD__, __LINE__);

    return $this->arrCategories;
  }

  /**
   * categoriesGetWoLatLon( ):
   *
   * @return	array		$this->arrCategories
   * @version 5.0.14
   * @since   5.0.14
   */
  private function categoriesGetWoLatLon( $row )
  {
    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        $fieldForLat = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'latitude' ];
        $fieldForLon = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'longitude' ];
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        $fieldForLat = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'latitude' ];
        $fieldForLon = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'lon' ];
        break;
    }

    $dontHandle00 = $this->confMap[ 'configuration.' ][ '00Coordinates.' ][ 'dontHandle' ];

    $lat = $row[ $fieldForLat ];
    $lon = $row[ $fieldForLon ];

    switch ( true )
    {
      case( $lon . $lat == '' ):
        // CONTINUE: longitude and latitude are empty
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'lon and lat are empty. Record won\'t handled!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        }
        return true;
      case( $dontHandle00 && $lon == 0 && $lat == 0 ):
        // CONTINUE: longitude and latitude are 0 and 0,0 shouldn't handled
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'lon and lat are 0. And 0 should not handled. Record won\'t handled!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        }
        return true;
    }

    return false;
  }

  /**
   * categoriesMoreThanOne( ) : Set the class var $this->boolMoreThanOneCategory. It will be true, if there
   *                          are two categories at least
   *
   * @return	boolean		$this->boolMoreThanOneCategory: true, if there are two categories at least
   * @version 4.1.7
   * @since   4.1.4
   */
  private function categoriesMoreThanOne()
  {

    // RETURN : method is called twice at least
    if ( $this->boolMoreThanOneCategory != null )
    {
      return $this->boolMoreThanOneCategory;
    }
    // RETURN : method is called twice at least

    $categories = $this->categoriesGet();

    if ( count( $categories[ 'labels' ] ) > 1 )
    {
      $this->boolMoreThanOneCategory = true;
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'There is more than one category.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    else
    {
      $this->boolMoreThanOneCategory = false;
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'There isn\'t more than one category.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
    }
    return $this->boolMoreThanOneCategory;
  }

  /*   * *********************************************
   *
   * cObject
   *
   * ******************************************** */

  /**
   * cObjDataAddArray( ):
   *
   * @param	array		$keyValue : array with key value pairs
   * @return	void
   * @version 4.1.7
   * @since   4.1.7
   */
  private function cObjDataAddArray( $keyValue )
  {
    foreach ( $keyValue as $key => $value )
    {
      if ( empty( $key ) )
      {
        continue;
      }

      $this->pObj->cObj->data[ $key ] = $value;

      if ( !( $this->pObj->b_drs_map || $this->pObj->b_drs_warn ) )
      {
        continue;
      }

      if ( $value === null )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = $key . ' is null. Maybe this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        }
      }
      else
      {
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'Added to cObject[' . $key . ']: ' . $value;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'You can use the content in TypoScript with: field = ' . $key;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
      }
    }
  }

  /**
   * cObjDataAddMarker( ):
   *
   * @return	void
   * @version 4.1.25
   * @since   4.1.0
   */
  private function cObjDataAddMarker()
  {
    // #42736, dwildt, 1-
//    foreach( array_keys( $this->confMap['marker.']['addToCData.']['system.'] ) as $marker )
    // #42736, dwildt, 1+ (Thanks to Thomas.Scholze@HS-Lausitz.de)
    foreach ( ( array ) array_keys( $this->confMap[ 'marker.' ][ 'addToCData.' ][ 'system.' ] ) as $marker )
    {
      if ( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $cObj_name = $this->confMap[ 'marker.' ][ 'addToCData.' ][ 'system.' ][ $marker ];
      $cObj_conf = $this->confMap[ 'marker.' ][ 'addToCData.' ][ 'system.' ][ $marker . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      if ( empty( $content ) )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'marker.addToCData.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        }
      }
      else
      {
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'Added to cObject[' . $marker . ']: ' . $content;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'You can use the content in TypoScript with: field = ' . $marker . ' (in BrowserMaps context only!)';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
      }
      $this->pObj->cObj->data[ $marker ] = $content;
    }
  }

  /**
   * cObjDataAddRow( ):
   *
   * @param	array
   * @return	void
   * @version 4.1.0
   * @since   4.1.0
   */
  private function cObjDataAddRow( $row )
  {
    static $first_loop = true;

    foreach ( ( array ) $row as $key => $value )
    {
      $this->pObj->cObj->data[ $key ] = $value;
    }

    if ( $first_loop )
    {
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'This fields are added to cObject: ' . implode( ', ', array_keys( $row ) );
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'I.e: you can use the content in TypoScript with: field = ' . key( $row );
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      $first_loop = false;
    }

    $this->cObjDataAddMarker();
  }

  /**
   * cObjDataRemoveArray( ):
   *
   * @param	array		$keyValue : array with key value pairs
   * @return	void
   * @version 4.1.7
   * @since   4.1.7
   */
  private function cObjDataRemoveArray( $keyValue )
  {
    foreach ( array_keys( $keyValue ) as $key )
    {
      unset( $this->pObj->cObj->data[ $key ] );
    }
  }

  /**
   * cObjDataRemoveMarker( ):
   *
   * @return	void
   * @version 4.1.0
   * @since   4.1.0
   */
  private function cObjDataRemoveMarker()
  {
    foreach ( array_keys( $this->confMap[ 'marker.' ][ 'addToCData.' ][ 'system.' ] ) as $marker )
    {
      if ( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      unset( $this->pObj->cObj->data[ $marker ] );
    }
  }

  /**
   * cObjDataRemoveRow( ):
   *
   * @param	array
   * @return	void
   * @version 4.1.0
   * @since   4.1.0
   */
  private function cObjDataRemoveRow( $row )
  {
    foreach ( array_keys( ( array ) $row ) as $key )
    {
      unset( $this->pObj->cObj->data[ $key ] );
    }

    $this->cObjDataRemoveMarker();
  }

  /*   * *********************************************
   *
   * Main
   *
   * ******************************************** */

  /**
   * get_map( ): Set the marker ###MAP###, if the current template hasn't any map-marker
   *
   * @param	string		$template: Current HTML template
   * @return	array		$template: Template with map marker
   * @version 4.5.6
   * @since   3.9.6
   */
  public function get_map( $template )
  {
//$this->pObj->dev_var_dump( $this->pObj->rows );
    $this->rowsBackup();

    // init the map
    $this->init();
    switch ( true )
    {
      case( empty( $this->enabled ) ):
      case( $this->enabled == 'disabled'):
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'RETURN. Map is disabled.';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        $this->rowsReset();
        return $template;
      default:
        // Follow the workflow
        break;
    }
    // RETURN: map isn't enabled
    // DRS
    if ( $this->pObj->b_drs_warn )
    {
      $prompt = 'The map module uses a JSON array. If you get any unexpected result, ' .
              'please remove config.xhtml_cleaning and/or page.config.xhtml_cleaning ' .
              'in your TypoScript configuration of the current page.';
      t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
      $prompt = 'The map module causes some conflicts with AJAX. Please disable AJAX in the ' .
              'plugin/flexform of the browser.';
      t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
    }
    // DRS
//$this->pObj->dev_var_dump( $this->pObj->rows[ 0 ] );

    if ( $this->enabled == 'Map +Routes' )
    {
      $arr_result = $this->renderMapRoute();
      //$this->pObj->dev_var_dump( $arr_result );
      switch ( true )
      {
        case( empty( $arr_result[ 'marker' ] ) ):
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'There isn\'t any marker row!';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
          }
          break;
        case(!empty( $arr_result[ 'marker' ] ) ):
        default:
// #i0020, 130718, dwildt
          $this->pObj->rows = $arr_result[ 'marker' ];
          break;
      }
      $jsonRoutes = $arr_result[ 'jsonRoutes' ];
      unset( $arr_result );
    }

//$this->pObj->dev_var_dump( $this->pObj->rows );
    // set the map marker (in case template is without the marker)
    $template = $this->initMainMarker( $template );
//    var_dump( __METHOD__, __LINE__, $template );
    // render the map

    $template = $this->renderMap( $template );
//    var_dump( __METHOD__, __LINE__, $template );
//    die( ':(' );
//$this->pObj->dev_var_dump( $this->pObj->rows );


    if ( $this->enabled == 'Map +Routes' )
    {
      switch ( true )
      {
        case( empty( $jsonRoutes ) ):
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'JSON array for the variable routes is empty!';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
          }
          break;
        case(!empty( $jsonRoutes ) ):
        default:
          $template = str_replace( "'###ROUTES###'", $jsonRoutes, $template );
          break;
      }
    }


    // RETURN the template
    $this->rowsReset();
//$this->pObj->dev_var_dump( $this->pObj->rows );
    return $template;
  }

  /**
   * pathDefaultIcon( ):
   *
   * @param integer     $catKey : The key of a default icon
   * @return	string    $pathRelative : Relative path to the default icon
   * @internal  #i0062
   * @version 5.0.10
   * @since   5.0.10
   */
  private function pathDefaultIcon( $catKey )
  {
    $point = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ];
//var_dump(__METHOD__, __LINE__, $point, $catKey);
    $name = $point[ 'pathToIcon' ];
    $conf = $point[ 'pathToIcon.' ];
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );

    $pathAbsolute = t3lib_div::getFileAbsFileName( $value );
    if ( !file_exists( $pathAbsolute ) )
    {
      $header = 'FATAL ERROR!';
      $text = 'File doesn\'t exist: ' . $pathAbsolute;
      $this->pObj->drs_die( $header, $text );
    }
    // relative path
    $pathRelative = preg_replace( '%' . PATH_site . '%', '', $pathAbsolute );

    return $pathRelative;
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init(): The method sets the globals $enabled and $confMap
   *
   * @return	void
   * @version 4.8.5
   * @since   3.9.6
   */
  public function init()
  {
    // RETURN : global vars are set before
    if ( $this->initVar() )
    {
      return;
    }
    // RETURN : global vars are set before
    // Init the devider for the categories
    $this->initCatDevider();

    return;
  }

  /**
   * initCatDevider( ): Init the class var $this->catDevider - the category devider.
   *
   * @return	void
   * @version 4.1.4
   * @since   4.1.4
   */
  private function initCatDevider()
  {
    $this->pObj->objTyposcript->set_confSqlDevider();
    $this->catDevider = $this->pObj->objTyposcript->str_sqlDeviderDisplay .
            $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
  }

  /**
   * initMainMarker( ): Set the marker ###MAP###, if the current template hasn't any map-marker
   *
   * @param	string		$template: Current HTML template
   * @return	array		$template: Template with map marker
   * @version 3.9.6
   * @since   3.9.6
   */
  private function initMainMarker( $template )
  {
    // map marker
    $str_mapMarker = '###MAP###';



    /////////////////////////////////////////////////////////////////
    //
      // Get TypoScript configuration for the current view

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $this->conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];
    $this->singlePid = $this->pObj->objZz->get_singlePid_for_listview();
    // Get TypoScript configuration for the current view
    /////////////////////////////////////////////////////////////////
    //
      // RETURN: template contains the map marker
//$pos = strpos('87.177.75.198', t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if( ! ( $pos === false ) )
//{
//  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $template );
//die( ':(' );
    $pos = strpos( $template, $str_mapMarker );
    if ( !( $pos === false ) )
    {
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'The HTML template contains the marker ' . $str_mapMarker . '.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return $template;
    }
    // RETURN: template contains the map marker
    /////////////////////////////////////////////////////////////////
    //
      // DRS - Development Reporting System

    if ( $this->pObj->b_drs_warn )
    {
      $prompt_01 = 'The HTML template doesn\'t contain any marker ' . $str_mapMarker . '.';
      $prompt_02 = 'Marker ' . $str_mapMarker . ' will added before the last div-tag automatically.';
      $prompt_03 = 'But it would be better, you add the marker ' . $str_mapMarker . ' to your HTML template manually.';
      t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt_01, $this->pObj->extKey, 2 );
      t3lib_div :: devLog( '[OK/BROWSERMAPS] ' . $prompt_02, $this->pObj->extKey, -1 );
      t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt_03, $this->pObj->extKey, 1 );
    }
    // DRS - Development Reporting System
    /////////////////////////////////////////////////////////////////
    //
      // Set marker before the last div-tag

    $arr_divs = explode( '</div>', $template );

    // 140705, dwildt, +
    switch ( true )
    {
      case(count( $arr_divs == 1 )):
        $template = $template . $str_mapMarker . PHP_EOL . '      ';
        break;
      default:
        $pos_lastDiv = count( $arr_divs ) - 2;
        $arr_divs[ $pos_lastDiv ] = $arr_divs[ $pos_lastDiv ] . $str_mapMarker . PHP_EOL . '      ';
        $template = implode( '</div>', $arr_divs );
        break;
    }
//    var_dump( __METHOD__, __LINE__, $template );
//    die( ':(' );

    return $template;
  }

  /*   * *********************************************
   *
   * Init global variables
   *
   * ******************************************** */

  /**
   * initVar( ): Set global vars
   *
   * @return	boolean		true, if global vars are set before. flase, if not
   * @version 4.5.6
   * @since   3.9.6
   */
  private function initVar()
  {
    // RETURN: $enabled isn't null
    if ( !( $this->enabled === null ) )
    {
      // DRS
      if ( $this->pObj->b_drs_map )
      {
        // #47632, 130508, dwildt, 1-
        //switch( $this->enabled )
        // #47632, 130508, dwildt, 1+
        switch ( true )
        {
          // #47632, 130508, dwildt, 1-
          //case( true ):
          // #47632, 130508, dwildt, 2+
          case( $this->enabled == 1 ):
          case( $this->enabled == 'Map'):
            $prompt = 'Map is enabled.';
            break;
          // #47632, 130508, dwildt, 3+
          case( $this->enabled == 'Map +Routes'):
            $prompt = 'Map +Routes is enabled.';
            break;
          default:
            $prompt = 'Map is disabled.';
            break;
        }
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      return true;
    }
    // RETURN: $enabled isn't null
    // Get TypoScript configuration for the current view
    $this->conf = $this->pObj->conf;
    $this->mode = $this->pObj->piVar_mode;
    $this->view = $this->pObj->view;
    $viewWiDot = $this->view . '.';
    $this->conf_path = $viewWiDot . $this->mode;
    $this->conf_view = $this->conf[ 'views.' ][ $viewWiDot ][ $this->mode . '.' ];
    // Get TypoScript configuration for the current view
    // Set the global var $confMap
    $this->initVarConfMap();

    // Set the global $enabled
    $this->initVarEnabled();

    // Set the global $provider
    $this->initVarProvider();

    // Set the globals $int_typeNum and $str_typeNum
    $this->initVarTypeNum();

    // Init the devider for the categories
    $this->initCatDevider();

    return false;
  }

  /**
   * initVarConfMap( ): The method sets the global $confMap
   *
   * @return	void
   * @version 4.5.6
   * @since   4.5.6
   */
  private function initVarConfMap()
  {

    // Set the global $confMapLocal
    switch ( true )
    {
      case( isset( $this->conf_view[ 'navigation.' ][ 'map.' ] ) ):
        // local configuration
        $this->confMap = $this->conf_view[ 'navigation.' ][ 'map.' ];
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'Local configuration in: views.' . $this->conf_path . '.navigation.map';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      // local configuration
      default:
        // global configuration
        $this->confMap = $this->pObj->conf[ 'navigation.' ][ 'map.' ];
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'Global configuration in: navigation.map';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        break;
      // global configuration
    }
    // Set the global $confMapLocal
//var_dump(__METHOD__, __LINE__, array_keys($this->confMap));

    return;
  }

  /**
   * initVarEnabled(  ) :
   *
   * @return	boolean		true, if var enabled is initiated before. false, if not.
   * @version 4.5.6
   * @since   4.5.6
   */
  private function initVarEnabled()
  {
    // Set the global var $enabled
    $this->enabled = $this->confMap[ 'enabled' ];

    // Evaluate the global var $enabled
    // #47632, 130508, dwildt, 13+
    switch ( true )
    {
      case( empty( $this->enabled ) ):
      case( $this->enabled == 1 ):
      case( $this->enabled == 'disabled'):
      case( $this->enabled == 'Map'):
      case( $this->enabled == 'Map +Routes'):
        // Follow the workflow
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value : TypoScript property map.enabled is "' . $this->enabled . '".';
        $this->pObj->drs_die( $header, $text );
    }
    // #47632, 130508, dwildt, 13+
    // Evaluate the global var $enabled
    // SWITCH : map status
    $promptAfterEnabledViews = null;
    switch ( $this->enabled )
    {
      // #47632, 130508, dwildt
      case( 1 ):
      case( 'Map' ):
        $prompt = 'Map is enabled.';
        $this->initVarEnabledViews();
        if ( !$this->enabled || ( $this->enabled == 'disabled' ) )
        {
          $promptAfterEnabledViews = 'Map is set to disabled, because view isn\'t an element of enabled views!';
        }
        break;
      case( 'Map +Routes' ):
        $prompt = 'Map +Routes is enabled.';
        $this->initVarEnabledViews();
        if ( !$this->enabled || ( $this->enabled == 'disabled' ) )
        {
          $promptAfterEnabledViews = 'Map is set to disabled, because view isn\'t an element of enabled views!';
        }
        break;
      default:
        $prompt = 'Map is disabled.';
        break;
    }
    // SWITCH : map status
    // RETURN : DRS is disabled
    if ( !( $this->pObj->b_drs_map || $this->pObj->b_drs_warn ) )
    {
      return false;
    }
    // RETURN : DRS is disabled
    // DRS - Development Reporting System
    if ( $this->pObj->b_drs_map )
    {
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    if ( $promptAfterEnabledViews )
    {
      if ( $this->pObj->b_drs_warn )
      {
        t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $promptAfterEnabledViews, $this->pObj->extKey, 2 );
      }
    }

    // RETURN false!
    return false;
  }

  /**
   * initVarEnabledViews( ) :
   *
   * @return
   * @version 4.5.8
   * @since   4.5.8
   *
   * @internal  #i0012
   */
  private function initVarEnabledViews()
  {
    // 130703, dwildt, 1+
    return;

    // RETURN : TypoScript version is smaller than 4.5.8
    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005007 ):
        return;
        break;
      case( $this->pObj->typoscriptVersion <= 4005008 ):
      default:
        // follow the workflow
        break;
    }
    // RETURN : TypoScript version is smaller than 4.5.8
    // RETURN : there is #1 browser plugins only
    if ( $this->pObj->objFlexform->get_numberOfBrowserPlugins() <= 1 )
    {
      return;
    }
    // RETURN : there is #1 browser plugins only
    // Set the global var $enabled
    $enabledCsvViews = $this->confMap[ 'enabled.' ][ 'csvViews' ];
    $arrViewUids = $this->pObj->objZz->getCSVasArray( $enabledCsvViews );

    // SWITCH : Set $this->enabled to disabled, if current view isn't part of enabled views
    switch ( true )
    {
      case( empty( $enabledCsvViews ) ):
      case(!in_array( $this->mode, $arrViewUids ) ):
        $this->enabled = 'disabled';
        break;
      default:
        // Do nothing
        break;
    }
    // SWITCH : Set $this->enabled to disabled, if current view isn't part of enabled views
    // RETURN : no DRS
    if ( !( $this->pObj->b_drs_map || $this->pObj->b_drs_warn ) )
    {
      return;
    }
    // RETURN : no DRS
    // DRS
    switch ( true )
    {
      case( $this->enabled == 'disabled' ):
        $prompt = 'Map is disabled by workflow.';
        t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Current view ' . $this->mode . ' isn\'t any element in enabled.csvViews.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        break;
      default:
        $prompt = 'Current view ' . $this->mode . ' is an element in enabled.csvViews.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        break;
    }
    // DRS

    unset( $enabledCsvViews );
    unset( $arrViewUids );
  }

  /**
   * initVarProvider( ) : The method sets the global $provider
   *
   * @return	void
   * @version 4.5.13
   * @since   4.5.6
   */
  private function initVarProvider()
  {
    $this->provider = $this->confMap[ 'provider' ];
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'Map provider is: ' . $this->provider;
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    switch ( true )
    {
      case( $this->provider == 'GoogleMaps' ):
        break;
      case( $this->provider == 'Open Street Map' ):
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value : TypoScript property map.provider is "' . $this->provider . '".';
        $this->pObj->drs_die( $header, $text );
    }
    return;
  }

  /**
   * initVarTypeNum( ): The method sets the globals $int_typeNum and $str_typeNum
   *
   * @return	void
   * @version 4.5.6
   * @since   4.5.6
   */
  private function initVarTypeNum()
  {
    // Get the typeNum from the current URL parameters
    $typeNum = ( int ) t3lib_div::_GP( 'type' );

    // Check the proper typeNum
    switch ( true )
    {
      case( $typeNum == $this->pObj->conf[ 'export.' ][ 'map.' ][ 'page.' ][ 'typeNum' ] ) :
        // Given typeNum is the internal typeNum for CSV export
        $this->int_typeNum = $typeNum;
        $this->str_typeNum = 'map';
        break;
      default :
        // Given typeNum isn't the internal typeNum for CSV export
        $this->str_typeNum = 'undefined';
    }
    // Check the proper typeNum

    return;
  }

  /*   * *********************************************
   *
   * JSON
   *
   * ******************************************** */

  /**
   * json_encode_wi_single_quotes( )  : Returns a JSON array with keys and values,
   *                                    which are wrapped by single (!) quotes.
   *                                    REQUIREMENT
   *                                    All values must handled with htmlspecialcahrs( )
   *
   * @param	array           $phpArray : php array with data for JSON.
   * @return	string		$jsonData : JSON array
   *
   * @internal  #i0018
   * @version 4.5.11
   * @since   4.5.11
   */
  private function json_encode_wi_single_quotes( $phpArray )
  {
    // Moves php array to a JSON array
    $jsonData = json_encode( $phpArray );
    // Replace all double quotes with single quotes
    $jsonData = str_replace( '"', "'", $jsonData );
    // Decode HTML special chars
    $jsonData = htmlspecialchars_decode( $jsonData );
//$this->pObj->dev_var_dump( $phpArray, $jsonData );

    return $jsonData;
  }

  /*   * *********************************************
   *
   * Map rendering
   *
   * ******************************************** */

  /**
   * renderMap( ): Render the Map
   *
   * @param	string		$template     : current HTML template of the parent object
   * @return	string		$mapTemplate  : the map
   * @version 4.5.6
   * @since   3.9.6
   */
  private function renderMap( $template )
  {
    // RETURN : HTML template is not proper
    $arr_result = $this->renderMapGetTemplate( $template );
    $mapTemplate = $arr_result[ 'template' ];
    if ( $arr_result[ 'error' ] )
    {
      return $mapTemplate;
    }
    // RETURN : HTML template is not proper

    if ( $arr_result[ 'error' ] )
    {
      $mapHashKey = '###MAP###';
      $prompt = $arr_result[ 'prompt' ];
      $template = str_replace( $mapHashKey, $prompt, $template );
      return $template;
    }

    $template = $this->renderMapMarker( $template, $mapTemplate );

//var_dump( __METHOD__ . ' (' . __LINE__ . '): ', $mapTemplate, $template );
    // RETURN the template
    return $template;
  }

  /*   * *********************************************
   *
   * Map center and zoom automatically
   *
   * ******************************************** */

  /**
   * renderMapAutoCenterCoor( ):
   *
   * @param	string		$map_template: ...
   * @param	[type]		$coordinates: ...
   * @return	string
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapAutoCenterCoor( $map_template, $coordinates )
  {
    $arr_return = array
      (
      'map_template' => $map_template,
      'coordinates' => null
    );

    // Get the mode
    $mode = $this->confMap[ 'configuration.' ][ 'centerCoordinates.' ][ 'mode' ];

    // SWITCH mode
    switch ( $mode )
    {
      case( 'auto' ):
      case( 'ts' ):
        // Follow the workflow
        break;
      default:
        // DRS
        if ( $this->pObj->b_drs_error )
        {
          $prompt = 'configuration.centerCoordinates.mode is undefined: ' . $mode . '. But is has to be auto or ts!';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        }
        // DRS
        // RETURN: there is an error!
        return $arr_return;
    }
    // SWITCH mode
    // RETURN: center coordinates should not calculated
    if ( $mode == 'ts' )
    {
      // DRS
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'configuration.centerCoordinates.mode is: ' . $mode . '. Coordinates won\'t calculated.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      return $arr_return;
    }
    // RETURN: center coordinates should not calculated
    // 130601, dwildt, +
    $coordinates = array_merge( $coordinates, $this->renderMapAutoCenterCoorRoute() );

    // Require map library
    require_once( PATH_typo3conf . 'ext/browser/lib/class.tx_browser_map.php');
    // Create object
    $objLibMap = new tx_browser_map( );

    // Get sum of coordinates
    $sumCoor = count( $coordinates );
    $curCoor = $sumCoor;
    // FOR all coordinates
    for ( $sumCoor; $curCoor--; )
    {
      $objLibMap->fillBoundList( explode( ',', $coordinates[ $curCoor ] ), $curCoor );
    }
    // FOR all coordinates
    // #47632, #i0007, dwildt, 10+
    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        $centerCoor = $this->renderMapAutoCenterCoorVers12( $objLibMap );
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        $centerCoor = $this->renderMapAutoCenterCoorVers13( $objLibMap );
        break;
    }
    // #47632, #i0007, dwildt, 10+
    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'configuration.centerCoordinates.mode is: ' . $mode . '. Calculated coordinates are ' . $centerCoor;
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
    // Get the marker
    $marker = $this->confMap[ 'configuration.' ][ 'centerCoordinates.' ][ 'dynamicMarker' ];
    $marker = "'###" . strtoupper( $marker ) . "###'";
    // Get the marker
    // Set center coordinates
    $map_template = str_replace( $marker, $centerCoor, $map_template );

    // RETURN the handled template
    $arr_return = array
      (
      'map_template' => $map_template,
      'coordinates' => $coordinates
    );
    return $arr_return;
  }

  /**
   * renderMapAutoCenterCoorRoute( ):
   *
   * @param	string		$map_template: ...
   * @param	[type]		$coordinates: ...
   * @return	string
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapAutoCenterCoorRoute()
  {
    $coordinates = array();

    if ( $this->enabled != 'Map +Routes' )
    {
      return $coordinates;
    }

    $tableFieldGeodata = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ][ 'path.' ][ 'geodata' ];

    foreach ( $this->rowsBackup as $row )
    {
      $strGeodata = $row[ $tableFieldGeodata ];
      $coordinates = array_merge
              (
              $coordinates, $this->renderMapRoutePathsJsonFeaturesCoordinates( $strGeodata, false )
      );
    }

    // 130601, dwildt, +
//    $coordinates  = $coordinates
//                  + $this->renderMapAutoCenterCoorRoute( );
//                  ;

    return $coordinates;
  }

  /**
   * renderMapAutoCenterCoorVers12( ) : Wrap center coordinates for oxMap version 1.2
   *
   * @param	object		$objLibMap  : ...
   * @return	string		$centerCoor :
   * @version 4.5.6
   * @since   4.1.0
   */
  private function renderMapAutoCenterCoorVers12( $objLibMap )
  {
    $centerCoor = implode( ',', $objLibMap->centerCoor() );
    $centerCoor = '[' . $centerCoor . ']';

    return $centerCoor;
  }

  /**
   * renderMapAutoCenterCoorVers13( ) : Wrap center coordinates for oxMap version 1.3
   *
   * @param	object		$objLibMap  : ...
   * @return	string		$centerCoor :
   * @internal  #47632
   * @version 4.5.6
   * @since   4.1.0
   */
  private function renderMapAutoCenterCoorVers13( $objLibMap )
  {
    list( $lon, $lat ) = $objLibMap->centerCoor();
    $centerCoor = '{ lon : ' . $lon . ', lat : ' . $lat . ' }';

    return $centerCoor;
  }

  /**
   * renderMapAutoZoomLevel( ):
   *
   * @return	string  $map_template
   * @version 5.0.10
   * @since   4.1.0
   */
  private function renderMapAutoZoomLevel( $map_template, $coordinates )
  {
    // #i0057, 140712, dwildt, +
    if ( !$this->renderMapAutoZoomLevelRequirements() )
    {
      return $map_template;
    }

    switch ( count( $coordinates ) )
    {
      case(0):
        // #i0061
        $zoomLevel = 1;
        break;
      default:
        $arrReturn = $this->renderMapAutoZoomLevelGetBounds( $coordinates );
        $latFraction = $this->renderMapAutoZoomLevelLatFraction( $arrReturn );
        $lngFraction = $this->renderMapAutoZoomLevelLngFraction( $arrReturn );
        $zoomLevel = $this->renderMapAutoZoomLevelZoom( $latFraction, $lngFraction );
        $zoomLevel = $zoomLevel - 1;
        break;
    }


    $marker = $this->confMap[ 'configuration.' ][ 'zoomLevel.' ][ 'dynamicMarker' ];
    $marker = "'###" . strtoupper( $marker ) . "###'";

    $map_template = str_replace( $marker, $zoomLevel, $map_template );

    // RETURN the handled template
    return $map_template;
  }

  /**
   * renderMapAutoZoomLevelGetBounds( ):
   *
   * @param	string		$map_template: ...
   * @param	[type]		$longitudes: ...
   * @param	[type]		$latitudes: ...
   * @return	string
   * @internal #i0057
   * @version 5.0.8
   * @since   5.0.8
   */
  private function renderMapAutoZoomLevelGetBounds( $coordinates )
  {
    $arrLonLat = array(
      0 => 0, // lon
      1 => 0  // lat
    );
    $arrReturn = array(
      'ne' => $arrLonLat,
      'sw' => $arrLonLat
    );

    // Require map library
    require_once( PATH_typo3conf . 'ext/browser/lib/class.tx_browser_map.php');
    // Create object
    $objLibMap = new tx_browser_map( );

    // Get sum of coordinates
    $sumCoor = count( $coordinates );
    $curCoor = $sumCoor;
    // FOR all coordinates
    for ( $sumCoor; $curCoor--; )
    {
      $arrReturn = $objLibMap->fillBoundList( explode( ',', $coordinates[ $curCoor ] ), $curCoor );
    }
    $arrLonLatNE = array(
      0 => max( $arrReturn[ 'n' ][ 0 ], $arrReturn[ 'e' ][ 0 ] ),
      1 => max( $arrReturn[ 'n' ][ 1 ], $arrReturn[ 'e' ][ 1 ] )
    );
    $arrLonLatSW = array(
      0 => min( $arrReturn[ 's' ][ 0 ], $arrReturn[ 'w' ][ 0 ] ),
      1 => min( $arrReturn[ 's' ][ 1 ], $arrReturn[ 'w' ][ 1 ] )
    );
    $arrReturn = array(
      'ne' => $arrLonLatNE,
      'sw' => $arrLonLatSW
    );
    return $arrReturn;
  }

  /**
   * renderMapAutoZoomLevelLatFraction( ):
   *
   * @return	double $latFraction
   * @internal #i0057
   * @version 5.0.8
   * @since   5.0.8
   */
  private function renderMapAutoZoomLevelLatFraction( $arrReturn )
  {
    $NEsin = sin( $arrReturn[ 'ne' ][ 1 ] * pi() / 180 );
    $NEradiusX2 = log( ( 1 + $NEsin ) / (1 - $NEsin) ) / 2;
    $NEradius = max( min( $NEradiusX2, pi() ), - pi() ) / 2;

    $SWsin = sin( $arrReturn[ 'sw' ][ 1 ] * pi() / 180 );
    $SWradiusX2 = log( ( 1 + $SWsin ) / (1 - $SWsin) ) / 2;
    $SWradius = max( min( $SWradiusX2, pi() ), - pi() ) / 2;

    $latFraction = ($NEradius - $SWradius) / pi();
    return $latFraction;
  }

  /**
   * renderMapAutoZoomLevelLngFraction( ):
   *
   * @return	double $lngFraction
   * @internal #i0057
   * @version 5.0.8
   * @since   4.1.0
   */
  private function renderMapAutoZoomLevelLngFraction( $arrReturn )
  {
    $lngDiff = $arrReturn[ 'ne' ][ 0 ] - $arrReturn[ 'sw' ][ 0 ];
    if ( $lngDiff < 0 )
    {
      $lngDiff + 360;
    }
    $lngFraction = $lngDiff / 360;

    return $lngFraction;
  }

  /**
   * renderMapAutoZoomLevelRequirements( ):
   *
   * @return	boolean
   * @internal #i0057
   * @version 5.0.8
   * @since   5.0.8
   */
  private function renderMapAutoZoomLevelRequirements()
  {
    // Get the mode
    $mode = $this->confMap[ 'configuration.' ][ 'zoomLevel.' ][ 'mode' ];

    if ( $mode == 'auto' )
    {
      return true;
    }

    if ( $mode == 'fixed' )
    {
      // DRS
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'configuration.zoomLevel.mode is: ' . $mode . '. Zoom level won\'t calculated.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      return false;
    }

    // DRS
    if ( $this->pObj->b_drs_error )
    {
      $prompt = 'configuration.zoomLevel.mode is undefined: ' . $mode . '. But is has to be auto or ts!';
      t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
    }
    // DRS
    return false;
  }

  /**
   * renderMapAutoZoomLevelZoom( ):
   *
   * @return	integer
   * @version 5.0.10
   * @since   5.0.8
   */
  private function renderMapAutoZoomLevelZoom( $latFraction, $lngFraction )
  {
    $zoomMax = 20; // GoogleMaps
    $zoomMax = 18; // With level 18 detail is more optimal
    $worldDim = array(
      'height' => 256,
      'width' => 256
    );

    $name = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ 'css_mapHeight' ];
    $conf = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ 'css_mapHeight.' ];
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    $height = ( int ) $value;

    $name = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ 'css_mapWidth' ];
    $conf = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ 'css_mapWidth.' ];
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    $width = ( int ) $value;

    $mapDim = array(
      'height' => $height,
      'width' => $width
    );

    // #i0059, 140714, dwildt
    switch ( $latFraction )
    {
      case(0):
        $zoomLat = $zoomMax;
        break;
      default:
        $zoomLat = floor( log( $mapDim[ 'height' ] / $worldDim[ 'height' ] / $latFraction ) / log( 2 ) );
        break;
    }
    switch ( $lngFraction )
    {
      case(0):
        $zoomLng = $zoomMax;
        break;
      default:
        $zoomLng = floor( log( $mapDim[ 'width' ] / $worldDim[ 'width' ] / $lngFraction ) / log( 2 ) );
        break;
    }

    $zoomLevel = min( $zoomLat, $zoomLng, $zoomMax );

    $this->renderMapAutoZoomLevelZoomDie( $zoomLevel );

    return $zoomLevel;
  }

  /**
   * renderMapAutoZoomLevelZoom( ):
   *
   * @return	integer
   * @internal #i0059
   * @version 5.0.10
   * @since   5.0.10
   */
  private function renderMapAutoZoomLevelZoomDie( $zoomLevel )
  {
    if ( $zoomLevel != -INF )
    {
      return;
    }
    $header = 'Zoom Calculation failed!';
    $text = 'Zoom level is unlimited (-INF)';
    $this->pObj->drs_die( $header, $text );
  }

  /*   * *********************************************
   *
   * Map HTML template
   *
   * ******************************************** */

  /**
   * renderMapGetTemplate( ): Get the HTML template
   *
   * @param	string		$template  : current HTML template of the parent object
   * @return	array		$arr_return     : with elements error, template
   * @version 4.5.6
   * @since   3.9.6
   */
  private function renderMapGetTemplate( $template )
  {
    $arr_return = array();
    $arr_return[ 'error' ] = false;

    // map hash key
    $mapHashKey = '###MAP###';

    // Get the template
    $mapTemplate = $this->pObj->cObj->fileResource( $this->confMap[ 'template.' ][ 'file' ] );

    // RETURN : no template file
    if ( empty( $mapTemplate ) )
    {
      // DRS - Development Reporting System
      if ( $this->b_drs_error )
      {
        $prompt = 'There is no template file. Path: navigation.map.template.file.';
        t3lib_div::devLog( '[ERROR/DRS] ' . $prompt, $this->extKey, 3 );
        $prompt = 'ABORTED';
        t3lib_div::devLog( '[ERROR/DRS] ' . $prompt, $this->extKey, 0 );
      }
      // DRS - Development Reporting System
      // Error message
      $str_map = '<h1 style="color:red;">' .
              $this->pObj->pi_getLL( 'error_readlog_h1' ) .
              '</h1>
                  <p style="color:red;font-weight:bold;">' .
              $this->pObj->pi_getLL( 'error_template_map_no' ) .
              '</p>';
      // Error message
      // Replace the map marker in the template of the parent object
      $template = str_replace( $mapHashKey, $str_map, $template );
      // RETURN the template
      $arr_return[ 'error' ] = true;
      $arr_return[ 'template' ] = $template;
      return $arr_return;
    }
    // RETURN : no template file
    // Get the subpart
    $str_marker = '###TEMPLATE_MAP###';
    $mapTemplate = $this->pObj->cObj->getSubpart( $mapTemplate, $str_marker );
    // Get the subpart
    // RETURN: no subpart marker
    if ( empty( $mapTemplate ) )
    {
      // DRS - Development Reporting System
      if ( $this->b_drs_error )
      {
        $prompt = 'Template doesn\'t contain the subpart ###TEMPLATE_MAP###.';
        t3lib_div::devLog( '[ERROR/DRS] ' . $prompt, $this->extKey, 3 );
        t3lib_div::devLog( '[ERROR/DRS] ABORTED', $this->extKey, 0 );
      }
      // DRS - Development Reporting System
      // Error message
      $str_map = '<h1 style="color:red;">' .
              $this->pObj->pi_getLL( 'error_readlog_h1' ) .
              '</h1>
                  <p style="color:red;font-weight:bold;">' .
              $this->pObj->pi_getLL( 'error_template_map_no_subpart' ) .
              '</p>';
      // Error message
      // Replace the map marker in the template of the parent object
      $template = str_replace( $mapHashKey, $str_map, $template );
      // RETURN the template
      $arr_return[ 'error' ] = true;
      $arr_return[ 'template' ] = $template;
      return $arr_return;
    }
    // RETURN: no subpart marker
    // RETURN : the template
    $arr_return[ 'template' ] = $mapTemplate;
    return $arr_return;
  }

  /*   * *********************************************
   *
   * Map rendering marker
   *
   * ******************************************** */

  /**
   * renderMap( ): Render the Map
   *
   * @param	string		$template     : current HTML template of the parent object
   * @param	string		$mapTemplate  : the map
   * @return	string		$template     : current HTML template with the rendered map
   * @version 6.0.7
   * @since   3.9.6
   */
  private function renderMapMarker( $template, $mapTemplate )
  {
    $mapHashKey = '###MAP###';
    // Substitute marker HTML
    $markerArray = $this->renderMapMarkerSnippetsHtmlCategories( $mapTemplate ) + $this->renderMapMarkerSnippetsHtmlDynamic( $mapTemplate );
    $mapTemplate = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
    // Substitute marker HTML
    // #i0120, 150101, dwildt: 5+
    $templateWoMarker = $this->renderMapMarkerWoMarker( $mapHashKey, $template );
    if ( $templateWoMarker )
    {
      return $templateWoMarker;
    }
    // Add data
    $mapTemplate = $this->renderMapMarkerVariablesSystem( $mapTemplate );
    $markerArray = $this->renderMapMarkerVariablesDynamic( $mapTemplate );
    $mapTemplate = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
    // Add data
    // Substitute marker JSS
    $markerArray = $markerArray + $this->renderMapMarkerSnippetsJssDynamic( $mapTemplate );
    $mapTemplate = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
    // Substitute marker JSS
    // map marker
    // Replace the map marker in the template of the parent object
    $template = str_replace( $mapHashKey, $mapTemplate, $template );

//var_dump( __METHOD__ . ' (' . __LINE__ . '): ', $mapTemplate, $template );
    // RETURN the template
    return $template;
  }

  /**
   * renderMapMarkerCategoryIcons( ):  Render category icons by TypoScript default icons.
   *
   * @return	array		$catIcons  : Array with category icons and icons data like offset and size
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerCategoryIcons()
  {
    $catIcons = null;
    $arrIcon = array();

    foreach ( array_keys( $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ] ) as $catKey )
    {
      if ( substr( $catKey, -1 ) == '.' )
      {
        continue;
      }

      unset( $arrIcon );

      // Set the path
      $coa_name = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'pathToIcon' ];
      $coa_conf = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'pathToIcon.' ];
      $value = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
      if ( empty( $value ) )
      {
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value : TypoScript property is empty.';
        $this->pObj->drs_die( $header, $text );
      }
      // absolute path
      $pathAbsolute = t3lib_div::getFileAbsFileName( $value );
      if ( !file_exists( $pathAbsolute ) )
      {
        $header = 'FATAL ERROR!';
        $text = 'File doesn\'t exist: ' . $pathAbsolute;
        $this->pObj->drs_die( $header, $text );
      }
      // relative path
      $pathRelative = preg_replace( '%' . PATH_site . '%', '', $pathAbsolute );
      $arrIcon[] = $pathRelative;
      // Set the path
      // Add the icon width
      $value = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'width' ];
      if ( empty( $value ) )
      {
        $header = 'FATAL ERROR!';
        $text = 'TypoScript property is empty.';
        $this->pObj->drs_die( $header, $text );
      }
      $arrIcon[] = ( int ) $value;
      // Add the icon width
      // Add the icon height
      $value = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'height' ];
      if ( empty( $value ) )
      {
        $header = 'FATAL ERROR!';
        $text = 'TypoScript property is empty.';
        $this->pObj->drs_die( $header, $text );
      }
      $arrIcon[] = ( int ) $value;
      // Add the icon height
      // Add the icon x-offset
      $value = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'offsetX' ];
      if ( $value == null )
      {
        $header = 'FATAL ERROR!';
        $text = 'TypoScript property is empty.';
        $this->pObj->drs_die( $header, $text );
      }
      $arrIcon[] = ( int ) $value;
      // Add the icon x-offset
      // Add the icon y-offset
      $value = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ][ 'offsetY' ];
      if ( $value == null )
      {
        $header = 'FATAL ERROR!';
        $text = 'TypoScript property is empty.';
        $this->pObj->drs_die( $header, $text );
      }
      $arrIcon[] = ( int ) $value;
      // Add the icon y-offset
//      $catIcons[$catKey] = '[' . implode( ', ', $arrIcon ) . ']';
      $catIcons[ $catKey ] = $arrIcon;
    }

//var_dump( __METHOD__, __LINE__, $catIcons );
    return $catIcons;
  }

  /**
   * renderMapMarkerPoints( ): Points are map marker
   *
   * @return	array
   * @version 4.5.7
   * @since   4.1.7
   */
  private function renderMapMarkerPoints()
  {
    static $arrLabels = array();

    $mapMarkers = array();
    $lons = array();
    $lats = array();

    // Get category labels
    if ( empty( $arrLabels ) )
    {
      $arrLabels = $this->renderMapMarkerPointsCatLabels();
    }
    // Get category labels
    // #i0118, dwildt, 1-/+
    //$arrCategoriesFlipped = array_flip( $this->arrCategories[ 'labels' ] );
//var_dump (__METHOD__, __LINE__, $this->arrCategories[ 'labels' ]);
    $arrCategoriesFlipped = array_flip( ( array ) $this->arrCategories[ 'labels' ] );

    // LOOP row
//$this->pObj->dev_var_dump( $this->pObj->rows );
    foreach ( $this->pObj->rows as $row )
    {
      // Get mapMarkers, lats and lons
      $arr_result = $this->renderMapMarkerPointsPoint( $row, $arrLabels, $arrCategoriesFlipped );
//$this->pObj->dev_var_dump( $arr_result );
      $mapMarkers = array_merge( $mapMarkers, $arr_result[ 'data' ][ 'mapMarkers' ] );
      $lats = array_merge( $lats, $arr_result[ 'data' ][ 'lats' ] );
      $lons = array_merge( $lons, $arr_result[ 'data' ][ 'lons' ] );
      unset( $arr_result );
      // Get mapMarkers, lats and lons
    }
    // LOOP row
    // DRS
    switch ( true )
    {
      case( $mapMarkers == null ):
      case(!is_array( $mapMarkers ) ):
      case( ( is_array( $mapMarkers ) ) && ( count( $mapMarkers ) < 1 ) ):
        if ( $this->pObj->b_drs_error )
        {
          $prompt = 'JSON array is null.';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'You will get an empty map!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Please check the TypoScript Constant Editor > Category [BROWSER - MAP].';
          t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 1 );
        }
        break;
      default:
        if ( $this->pObj->b_drs_map )
        {
          $prompt = 'JSON array seem\'s to be proper.';
          t3lib_div :: devLog( '[OK/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, -1 );
          $prompt = 'If you have an unexpected effect in your map, please check the JSON array from below!';
          t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 1 );
        }
        break;
    }
    // DRS
    // Return array
    $arr_return = array
      (
      'data' => array
        (
        'mapMarkers' => $mapMarkers,
        'lats' => $lats,
        'lons' => $lons
      )
    );
    // Return array

    return $arr_return;
  }

  /**
   * renderMapMarkerPointsPoint( ): Points are map marker
   *
   * @return	array
   * @version 4.5.7
   * @since   4.1.7
   */
  private function renderMapMarkerPointsPoint( $row, $arrLabels, $arrCategoriesFlipped )
  {
    $mapMarkers = array();
    $lons = array();
    $lats = array();

    $dontHandle00 = $this->confMap[ 'configuration.' ][ '00Coordinates.' ][ 'dontHandle' ];

    // Get category properties
    $catValues = $this->renderMapMarkerPointsPointProperties( $row );
//$this->pObj->dev_var_dump( $row, $catValues, $arrLabels );
    // FOREACH category title
    foreach ( $catValues[ 'catTitles' ] as $key => $catTitle )
    {
      // Add cObj->data
      $this->renderMapMarkerPointsPointCobjDataAdd( $row, $arrLabels, $catValues, $key );

      // Get the longitude and latitude
      $lon = $this->renderMapMarkerVariablesSystemItem( 'longitude' );
      $lat = $this->renderMapMarkerVariablesSystemItem( 'latitude' );
//$this->pObj->dev_var_dump( 'lat: ' . $lat . ', lon: ' . $lon );
      // SWITCH logitude and latitude
      switch ( true )
      {
        case( $lon . $lat == '' ):
          // CONTINUE: longitude and latitude are empty
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'lon and lat are empty. Record won\'t handled!';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
          }
          continue 2;
        case( $dontHandle00 && $lon == 0 && $lat == 0 ):
          // CONTINUE: longitude and latitude are 0 and 0,0 shouldn't handled
          if ( $this->pObj->b_drs_warn )
          {
            $prompt = 'lon and lat are 0. And 0 should not handled. Record won\'t handled!';
            t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
          }
          continue 2;
      }
      // SWITCH logitude and latitude
      // Get the longitude and latitude
      // Get the category label
      //$catTitleWoSpc  = str_replace( ' ', null, $catTitle );
      $catTitleWoSpc = $this->zz_properFormLabel( $catTitle );

      // Get the description
      $description = $this->renderMapMarkerVariablesSystemItem( 'description' );
      if ( empty( $description ) )
      {
        $description = 'Content is empty!<br />' . PHP_EOL
                . 'Please take care of a proper configuration '
                . 'of the TypoScript property marker.mapMarker.description!'
        ;
      }

      // #i0019, 130717, dwildt, 1+
      $description = str_replace( $this->catDevider, $this->pObj->objTyposcript->str_sqlDeviderDisplay, $description );
      // #i0018, 130901, dwildt, 1+
      $description = str_replace( "'", null, $description );
      // Get the description
      // Get the url
////$lastItem = count( $this->pObj->cObj->data ) - 1;
////$this->pObj->dev_var_dump( $this->pObj->cObj->data[ $lastItem ] );
//$this->pObj->dev_var_dump( $this->pObj->cObj->data );
      $url = $this->renderMapMarkerVariablesSystemItem( 'url' );
      //var_dump( __METHOD__, __LINE__, $this->pObj->cObj->data['mapLinkToSingle'], $url );
      // Get the number
      $number = $this->renderMapMarkerVariablesSystemItem( 'number' );

      $arrReturn = $this->renderMapMarkerPointsPointIcon( $arrCategoriesFlipped, $catTitle );
      $catIconMap = $arrReturn[ 'path' ];
      $iconKey = $arrReturn[ 'key' ];
      $iconOffsetX = $arrReturn[ 'offsetX' ];
      $iconOffsetY = $arrReturn[ 'offsetY' ];
      // Get the icon properties
      // Get markerTable and markerUid
      switch ( true )
      {
        case( $row[ 'markerTable' ] ):
          $markerTable = $row[ 'markerTable' ];
          $routeLabel = $row[ 'routeLabel' ];
          $type = 'route';
          break;
        case( $catValues[ 'markerTable' ] ):
        default:
          $markerTable = $catValues[ 'markerTable' ];
          $routeLabel = null;
          $type = 'category';
          break;
      }
      $markerUid = $catValues[ 'markerUid' ];
      // 130612, dwildt, 4+
      if ( empty( $markerUid ) )
      {
        $markerUid = 'uid';
      }
      // Get markerTable and markerUid
//$this->pObj->dev_var_dump( $row, $catValues );
      // Set mapMarker
      $mapMarker = array
        (
        'cat' => $catTitleWoSpc,
        'desc' => $description,
        'number' => $number,
        'lon' => ( double ) $lon,
        'lat' => ( double ) $lat,
        'url' => $url,
        'catIconMap' => $catIconMap,
        'iconKey' => $iconKey,
        'iconOffsetX' => $iconOffsetX,
        'iconOffsetY' => $iconOffsetY,
        'markerUid' => $markerUid,
        'markerTable' => $markerTable,
        'routeLabel' => $routeLabel,
        'type' => $type
      );
      // Set mapMarker
      // Unset some mapMarker elements, if they are empty
      $keysForCleanup = array( 'catIconMap', 'number', 'url' );
      foreach ( $keysForCleanup as $keyForCleanup )
      {
        if ( !empty( $mapMarker[ $keyForCleanup ] ) )
        {
          continue;
        }
        // UNSET : mapMarker element is empty
        unset( $mapMarker[ $keyForCleanup ] );
      }
      // Unset some mapMarker elements, if they are empty
      // Save each mapMarker
      $mapMarkers[] = $mapMarker;
      // Save each longitude
      $lons[] = $mapMarker[ 'lon' ];
      // Save each latitude
      $lats[] = $mapMarker[ 'lat' ];

      // Remove the current row from cObj->data
      $this->renderMapMarkerPointsPointCobjDataRemove( $row, $arrLabels );
    }
    // FOREACH category title

    unset( $dontHandle00 );
    unset( $arrLabels );

    $arr_return = array
      (
      'data' => array
        (
        'mapMarkers' => $mapMarkers,
        'lats' => $lats,
        'lons' => $lons
      )
    );

    return $arr_return;
  }

  /**
   * renderMapMarkerPointsPointCobjDataAdd( ) :
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapMarkerPointsPointCobjDataAdd( $row, $arrLabels, $catValues, $key )
  {
    // Add the current row to cObj->data
    $this->cObjDataAddRow( $row );

    // Add TypoScript marker
    $this->cObjDataAddMarker();

    // Add category icon
    if ( isset( $this->arrCategories[ 'icons' ] ) )
    {
      $this->cObjDataAddArray( array( $arrLabels[ 'catIcon' ] => $catValues[ 'catIcons' ][ $key ] ) );
    }
    // Add category icon
    // #42566, 121031, dwildt
    $this->cObjDataRemoveArray( array( $catValues[ 'catTitle' ] ) );
    $this->cObjDataAddArray( array( $catValues[ 'catTitle' ] => $catValues[ 'catTitles' ][ $key ] ) );


    // Add x offset and y offset to current cObject
    // #42125, 121031, dwildt, 2+
    $this->cObjDataAddArray( array( $arrLabels[ 'catOffsetX' ] => $catValues[ 'catOffsetsX' ][ $key ] ) );
    $this->cObjDataAddArray( array( $arrLabels[ 'catOffsetY' ] => $catValues[ 'catOffsetsY' ][ $key ] ) );
    // Add x offset and y offset to current cObject
  }

  /**
   * renderMapMarkerPointsPointCobjDataRemove( ) :
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapMarkerPointsPointCobjDataRemove( $row, $arrLabels )
  {
    $this->cObjDataRemoveRow( $row );
    $this->cObjDataRemoveMarker();
    $this->cObjDataRemoveArray( array( $arrLabels[ 'catTitle' ] ) );
    $this->cObjDataRemoveArray( array( $arrLabels[ 'catIcon' ] ) );
    $this->cObjDataRemoveArray( array( $arrLabels[ 'catOffsetX' ] ) );
    $this->cObjDataRemoveArray( array( $arrLabels[ 'catOffsetY' ] ) );
  }

  /**
   * renderMapMarkerPointsCatLabels( ):
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapMarkerPointsCatLabels()
  {
    $arrLabels = array();

    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        $arrLabels[ 'catTitle' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'category' ];
        $arrLabels[ 'catIcon' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'categoryIcon' ];
        $arrLabels[ 'catOffsetX' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'categoryOffsetX' ];
        $arrLabels[ 'catOffsetY' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'categoryOffsetY' ];
        // 130612, dwildt, 1+
        $arrLabels[ 'markerUid' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'uid' ];
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        $arrLabels[ 'catTitle' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryTitle' ];
        // #54548, 131221, dwildt, 2+
        $arrLabels[ 'catCssMarker' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryCssMarker' ];
        $arrLabels[ 'catCssPath' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryCssPath' ];
        $arrLabels[ 'catIcon' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryIcon' ];
        $arrLabels[ 'catOffsetX' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryOffsetX' ];
        $arrLabels[ 'catOffsetY' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'categoryOffsetY' ];
        $arrLabels[ 'markerUid' ] = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'fields.' ][ 'marker.' ][ 'uid' ];
        break;
    }

    return $arrLabels;
  }

  /**
   * renderMapMarkerPointsPointProperties( ): Points are map marker
   *
   * @return	array
   * @version 4.6.1
   * @since   4.5.7
   */
  private function renderMapMarkerPointsPointProperties( $row )
  {
    static $arrLabels = array();

    if ( empty( $arrLabels ) )
    {
      $arrLabels = $this->renderMapMarkerPointsCatLabels();
    }

    // Get categories
    // #47602, 130911, dwildt, 7+
    $catTitles = array
      (
      '0' => $this->pObj->pi_getLL( 'phrase_noMapCat' )
    );
    if ( isset( $row[ $arrLabels[ 'catTitle' ] ] ) )
    {
      if ( $row[ $arrLabels[ 'catTitle' ] ] )
      {
        $catTitles = explode( $this->catDevider, $row[ $arrLabels[ 'catTitle' ] ] );
      }
    }
    // Get categories
    // Get category icons
    if ( isset( $this->arrCategories[ 'icons' ] ) )
    {
      $catIcons = explode( $this->catDevider, $row[ $arrLabels[ 'catIcon' ] ] );
    }
    // Get category icons
    // Get category offsets
    // #47602, 130911, dwildt, 2+
    $catOffsetsX = array( '0' );
    $catOffsetsY = array( '0' );
    // #42125, 121031, dwildt, 8+
    if ( isset( $row[ $arrLabels[ 'catOffsetX' ] ] ) )
    {
      $catOffsetsX = explode( $this->catDevider, $row[ $arrLabels[ 'catOffsetX' ] ] );
    }
    if ( isset( $row[ $arrLabels[ 'catOffsetY' ] ] ) )
    {
      $catOffsetsY = explode( $this->catDevider, $row[ $arrLabels[ 'catOffsetY' ] ] );
    }
    // Get category offsets

    $markerUid = $row[ $arrLabels[ 'markerUid' ] ];
    list( $markerTable ) = explode( '.', $arrLabels[ 'markerUid' ] );

    // 130612, dwildt, 16+
    // DIE  : if $markerUid is empty
    if ( empty( $markerUid ) )
    {
      $pluginPid = $this->pObj->cObj->data[ 'pid' ];
      $pluginUid = $this->pObj->cObj->data[ 'uid' ];
      $pluginTitle = $this->pObj->cObj->data[ 'header' ];
      $mode = $this->mode;
      $view = $this->pObj->view;
      $viewWiDot = $view . '.';
      $tsPath = 'plugin.tx_browser_pi1.views.' . $viewWiDot . $mode;
      $prompt = '<div style="background:white;color:black;">' .
              ' <h1 style="color:red;">'
              . $this->pObj->pi_getLL( 'error_maproutes_marker_woUid_h1' )
              . '</h1>'
              . $this->pObj->pi_getLL( 'error_maproutes_marker_woUid_prompt' )
              . '<p style="color:black;">' . PHP_EOL
              . __METHOD__ . ' (line ' . __LINE__ . '): <br />' . PHP_EOL
              . '</p>'
              . '</div>';
      $prompt = str_replace( '%pluginPid%', $pluginPid, $prompt );
      $prompt = str_replace( '%pluginUid%', $pluginUid, $prompt );
      $prompt = str_replace( '%pluginTitle%', $pluginTitle, $prompt );
      $prompt = str_replace( '%tsPath%', $tsPath, $prompt );
      //die( $prompt );
      echo( $prompt );
    }
    // DIE  : if $markerUid is empty
    // DIE  : if $markerTable is empty
    if ( empty( $markerTable ) )
    {
      $header = 'FATAL ERROR!';
      $text = '$markerTable is empty.';
      $this->pObj->drs_die( $header, $text );
    }
    // DIE  : if $markerTable is empty
    // 130612, dwildt, 16+
    // RETURN result
    $arr_return = array
      (
      'catTitles' => $catTitles,
      'catIcons' => $catIcons,
      'catOffsetsX' => $catOffsetsX,
      'catOffsetsY' => $catOffsetsY,
      'markerTable' => $markerTable,
      'markerUid' => $markerUid
    );
//$this->pObj->dev_var_dump( $arr_return );
    return $arr_return;
    // RETURN result
  }

  /**
   * renderMapMarkerPointsPointIcon( ):
   *
   * @return	array
   * @internal  #i0062
   * @version 5.0.10
   * @since   5.0.10
   */
  private function renderMapMarkerPointsPointIcon( $arrCategoriesFlipped, $catTitle )
  {
    $arrReturn = array(
      'path' => null,
      'key' => 0,
      'offsetX' => 0,
      'offsetY' => 0
    );
//var_dump(__METHOD__, __LINE__, $arrCategoriesFlipped, $catTitle );
    switch ( true )
    {
      case($arrCategoriesFlipped[ $catTitle ] == $this->arrWoCategories[ 'iconKey' ]):
        $arrReturn = $this->renderMapMarkerPointsPointIconWoCat( $arrCategoriesFlipped, $catTitle );
        break;
      case($arrCategoriesFlipped[ $catTitle ] != $this->arrWoCategories[ 'iconKey' ]):
      default:
        $arrReturn = $this->renderMapMarkerPointsPointIconWiCat( $arrCategoriesFlipped, $catTitle );
        break;
    }
    return $arrReturn;
  }

  /**
   * renderMapMarkerPointsPointIcon( ):
   *
   * @return	array
   * @internal  #i0062
   * @version 5.0.10
   * @since   5.0.10
   */
  private function renderMapMarkerPointsPointIconWiCat( $arrCategoriesFlipped, $catTitle )
  {
    $path = null;
    if ( isset( $this->arrCategories[ 'icons' ] ) )
    {
      $path = $this->renderMapMarkerVariablesSystemItem( 'categoryIconMap' );
    }

    $arrReturn = array(
      'path' => $path,
      'key' => $arrCategoriesFlipped[ $catTitle ],
      'offsetX' => $this->renderMapMarkerVariablesSystemItem( 'categoryOffsetX' ),
      'offsetY' => $this->renderMapMarkerVariablesSystemItem( 'categoryOffsetY' )
    );

    return $arrReturn;
  }

  /**
   * renderMapMarkerPointsPointIconWoCat( ):
   *
   * @return	array
   * @internal  #i0062
   * @version 5.0.10
   * @since   5.0.10
   */
  private function renderMapMarkerPointsPointIconWoCat( $arrCategoriesFlipped, $catTitle )
  {
    $catKey = $arrCategoriesFlipped[ $catTitle ];
    $point = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'colours.' ][ 'points.' ][ $catKey . '.' ];

    $pathRelative = $this->pathDefaultIcon( $catKey );

    $arrReturn = array(
      'path' => $pathRelative,
      'key' => $catKey,
      'offsetX' => $point[ 'offsetX' ],
      'offsetY' => $point[ 'offsetY' ]
    );

    return $arrReturn;
  }

  /**
   * renderMapMarkerPointsToJson( ):
   *
   * @param	array
   * @return	string		$jsonData
   * @version 4.8.5
   * @since   4.1.0
   */
  private function renderMapMarkerPointsToJson( $markers )
  {
    $arr_return = array();
    $coordinates = array();
    $series = null;

    // Category icons in case of database categories without own icons
    $catIcons = $this->renderMapMarkerCategoryIcons();

    // FOREACH marker
    foreach ( ( array ) $markers as $marker )
    {
      $catTitle = $marker[ 'cat' ];
      $dataKey = $marker[ 'markerTable' ] . '_' . $marker[ 'markerUid' ];

      // icon
      $icon = $this->renderMapMarkerPointsToJsonIcon( $series, $marker, $catIcons );
      $series[ $catTitle ][ 'icon' ] = $icon;

      // data
      $data = $this->renderMapMarkerPointsToJsonData( $marker );
      $series[ $catTitle ][ 'data' ][ $dataKey ] = $data;

      // route
      if ( $marker[ 'type' ] == "route" )
      {
        $series[ $catTitle ][ 'route' ] = $marker[ 'routeLabel' ];
      }

      // Set coordinates
      $coordinates[] = $marker[ 'lon' ] . ',' . $marker[ 'lat' ];
    }
    // FOREACH marker
//$this->pObj->dev_var_dump( $series );
    // #i0018, 130717, dwildt, 1-
    //$jsonData = json_encode( $series );
    // #i0018, 130717, dwildt, 1+
    $jsonData = $this->json_encode_wi_single_quotes( $series );
    // #54595, 131225, dwildt, 4+
    if ( $jsonData == "null" )
    {
      // 131227, dwildt, 1-
      //$jsonData = "{'null':{'icon': [],'data': {'null': {'coors': [0,0],'desc': '',}}}}";
      // 131227, dwildt, 1+
      $jsonData = null;
    }

    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'JSON array for the marker: ' . var_export( $jsonData, true );
      $prompt = str_replace( ':{', ': {', $prompt );
      $prompt = str_replace( '},', '}, ', $prompt );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    $arr_return[ 'data' ][ 'jsonData' ] = $jsonData;
    $arr_return[ 'data' ][ 'coordinates' ] = $coordinates;
//$this->pObj->dev_var_dump( $jsonData );
    return $arr_return;
  }

  /**
   * renderMapMarkerPointsToJsonData( ):
   *
   * @param	array
   * @return	string		$jsonData
   * @version 4.5.11
   * @since   4.5.7
   */
  private function renderMapMarkerPointsToJsonData( $mapMarker )
  {
    $arrData = array
      (
      'coors' => array
        (
        $mapMarker[ 'lon' ],
        $mapMarker[ 'lat' ]
      ),
      // #i0018, 130718, dwildt, +htmlspecialchars( )
      'desc' => htmlspecialchars( $mapMarker[ 'desc' ] ),
      'url' => $mapMarker[ 'url' ],
      // #i0018, 130718, dwildt, +htmlspecialchars( )
      'number' => htmlspecialchars( $mapMarker[ 'number' ] ),
      'route' => $this->renderMapMarkerPointsToJsonDataRoute( $mapMarker )
    );
//$this->pObj->dev_var_dump( $arrData );
    // Remove elements, if their value is empty
    if ( empty( $arrData[ 'url' ] ) )
    {
      unset( $arrData[ 'url' ] );
    }
    if ( empty( $arrData[ 'number' ] ) )
    {
      unset( $arrData[ 'number' ] );
    }
    if ( empty( $arrData[ 'route' ] ) )
    {
      unset( $arrData[ 'route' ] );
    }
    // Remove elements, if their value is empty

    return $arrData;
  }

  /**
   * renderMapMarkerPointsToJsonDataRoute( ):
   *
   * @param	array
   * @return	string
   * @version 4.5.11
   * @since   4.5.8
   */
  private function renderMapMarkerPointsToJsonDataRoute( $mapMarker )
  {
    $route = null;

    switch ( $mapMarker[ 'type' ] )
    {
      case( 'category' ):
        $route = null;
        break;
      case( 'route' ):
        // #i0018, 130718, dwildt, +htmlspecialchars( )
        $route = htmlspecialchars( $mapMarker[ 'routeLabel' ] );
        break;
      default:
        // DIE  : $value is empty
        $header = 'FATAL ERROR!';
        $text = 'The value of the map marker type is undefined: ' . $mapMarker[ 'type' ];
        $this->pObj->drs_die( $header, $text );
      // DIE  : $value is empty
    }

    return $route;
  }

  /**
   * renderMapMarkerPointsToJsonIcon( ):
   *
   * @param	array
   * @return	string		$jsonData
   * @version 4.5.11
   * @since   4.5.7
   */
  private function renderMapMarkerPointsToJsonIcon( $series, $mapMarker, $catIcons )
  {
    $arrIcon = array();
    // #i0018, 130718, dwildt, +htmlspecialchars( )
    $catTitle = htmlspecialchars( $mapMarker[ 'cat' ] );

    // RETURN  : json icon array is set
    if ( isset( $series[ $catTitle ][ 'icon' ] ) )
    {
      $arrIcon = $series[ $catTitle ][ 'icon' ];
      return $arrIcon;
    }
    // RETURN  : json icon array is set
    // RETURN : Any own icon
    if ( !isset( $mapMarker[ 'catIconMap' ] ) )
    {
      $arrIcon = $catIcons[ $mapMarker[ 'iconKey' ] ];
      return $arrIcon;
    }
    // RETURN : Any own icon

    if ( empty( $mapMarker[ 'catIconMap' ] ) )
    {
      $header = 'FATAL ERROR!';
      $text = 'catIconMap is empty.';
      $this->pObj->drs_die( $header, $text );
    }

    // Database category has its own icon
    // Path to the root
    $rootPath = t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/';
    list( $width, $height ) = getimagesize( $rootPath . $mapMarker[ 'catIconMap' ] );
    if ( empty( $height ) or empty( $width ) )
    {
      if ( $this->pObj->b_drs_error )
      {
        $prompt = 'Icon can\'t rendered. Maybe the file isn\'t proper: ' . $rootPath . $mapMarker[ 'catIconMap' ];
        t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'A possible cause is a wrong list number for the icon. Unproper example: You have uploaded one icon only, but you like to use the second image of the icons list. ';
        t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 1 );
      }
    }
    $arrIcon[] = $mapMarker[ 'catIconMap' ];
    $arrIcon[] = $width;
    $arrIcon[] = $height;
    // #42125, 121031, dwildt, 2-
//          $arrIcon[] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['x'];
//          $arrIcon[] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['y'];
    // IF database has a field x-offset, take value from database
    // #42125, 121031, dwildt, 8+
    if ( isset( $mapMarker[ 'iconOffsetX' ] ) )
    {
      $arrIcon[] = ( int ) $mapMarker[ 'iconOffsetX' ];
    }
    else
    {
      $arrIcon[] = ( int ) $this->confMap[ 'configuration.' ][ 'categories.' ][ 'offset.' ][ 'x' ];
    }
    // IF database has a field x-offset, take value from database
    // IF database has a field y-offset, take value from database
    // #42125, 121031, dwildt, 8+
    if ( isset( $mapMarker[ 'iconOffsetY' ] ) )
    {
      $arrIcon[] = ( int ) $mapMarker[ 'iconOffsetY' ];
    }
    else
    {
      $arrIcon[] = ( int ) $this->confMap[ 'configuration.' ][ 'categories.' ][ 'offset.' ][ 'y' ];
    }
    // IF database has a field y-offset, take value from database
    // Database category has its own icon
//var_dump( __METHOD__, __LINE__, $arrIcon );
    return $arrIcon;
  }

  /**
   * renderMapMarkerSnippetsHtml( $map_template, $tsProperty ):
   *
   * @param	[type]		$$map_template: ...
   * @param	[type]		$tsProperty: ...
   * @return	array
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerSnippetsHtml( $map_template, $tsProperty )
  {
    $dummy = null;
    $markerArray = array();

    foreach ( $this->confMap[ 'marker.' ][ 'snippets.' ][ 'html.' ][ $tsProperty . '.' ] as $marker => $conf )
    {
      $dummy = $conf;
      if ( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if ( ( $pos === false ) )
      {
        if ( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        continue;
      }

      $cObj_name = $this->confMap[ 'marker.' ][ 'snippets.' ][ 'html.' ][ $tsProperty . '.' ][ $marker ];
      $cObj_conf = $this->confMap[ 'marker.' ][ 'snippets.' ][ 'html.' ][ $tsProperty . '.' ][ $marker . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      if ( empty( $content ) )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'marker.html.' . $tsProperty . '.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        }
      }
      $markerArray[ $hashKeyMarker ] = $content;
    }

    return $markerArray;
  }

  /**
   * renderMapMarkerSnippetsHtmlCategories( ):
   *
   * @param	[type]		$$map_template: ...
   * @return	array
   * @version 5.0.14
   * @since   4.1.0
   */
  private function renderMapMarkerSnippetsHtmlCategories( $map_template )
  {
    $markerArray = array();

    $inCaseOfOneCategory = $this->confMap[ 'configuration.' ][ 'categories.' ][ 'display.' ][ 'inCaseOfOneCategory' ];
//var_dump(__METHOD__, __LINE__, $this->confMap);

    switch ( true )
    {
      case(!$inCaseOfOneCategory):
        if ( !$this->categoriesMoreThanOne() )
        {
          if ( $this->pObj->b_drs_map )
          {
            $prompt = 'There isn\'t more than one category. Any form with categories will rendered.';
            t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
          }
          return $markerArray;
        }
        break;
      case($inCaseOfOneCategory):
      default:
        // #i0076, 140721, dwildt, 1+
        $this->categoriesMoreThanOne();
        break;
    }

    $tsProperty = 'categories';
    $markerArray = $this->renderMapMarkerSnippetsHtml( $map_template, $tsProperty );

    $inputs = $this->categoriesFormInputs();
    $markerArray[ '###FILTER_FORM###' ] = str_replace( '###INPUTS###', $inputs, $markerArray[ '###FILTER_FORM###' ] );

    return $markerArray;
  }

  /**
   * renderMapMarkerSnippetsHtmlDynamic( $map_template ):
   *
   * @param	[type]		$$map_template: ...
   * @return	array
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerSnippetsHtmlDynamic( $map_template )
  {
    $tsProperty = 'dynamic';
    $markerArray = $this->renderMapMarkerSnippetsHtml( $map_template, $tsProperty );

    return $markerArray;
  }

  /**
   * renderMapMarkerSnippetsJssDynamic( $map_template ):
   *
   * @param	[type]		$$map_template: ...
   * @return	array
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerSnippetsJssDynamic( $map_template )
  {
    $dummy = null;
    $markerArray = array();

    foreach ( $this->confMap[ 'marker.' ][ 'snippets.' ][ 'jss.' ][ 'dynamic.' ] as $marker => $conf )
    {
      $dummy = $conf;
      if ( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if ( ( $pos === false ) )
      {
        if ( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        continue;
      }

      $cObj_name = $this->confMap[ 'marker.' ][ 'snippets.' ][ 'jss.' ][ 'dynamic.' ][ $marker ];
      $cObj_conf = $this->confMap[ 'marker.' ][ 'snippets.' ][ 'jss.' ][ 'dynamic.' ][ $marker . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      if ( empty( $content ) )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'marker.snippets.jss.dynamic.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        }
      }
      $markerArray[ "'" . $hashKeyMarker . "'" ] = $content;
    }

    return $markerArray;
  }

  /**
   * renderMapMarkerVariablesDynamic( ):
   *
   * @param	[type]		$$map_template: ...
   * @return	array
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerVariablesDynamic( $map_template )
  {
    $dummy = null;
    $markerArray = array();

    foreach ( $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ] as $marker => $conf )
    {
      $dummy = $conf;
      if ( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if ( ( $pos === false ) )
      {
        if ( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
        }
        continue;
      }

      $cObj_name = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ $marker ];
      $cObj_conf = $this->confMap[ 'marker.' ][ 'variables.' ][ 'dynamic.' ][ $marker . '.' ];
      $content = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      if ( empty( $content ) )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'marker.variables.dynamic.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
        }
      }
      $markerArray[ $hashKeyMarker ] = $content;
    }

    return $markerArray;
  }

  /**
   * renderMapMarkerVariablesSystem( ):
   *
   * @param	string		$map_template: ...
   * @return	string
   * @version 4.1.0
   * @since   4.1.0
   */
  private function renderMapMarkerVariablesSystem( $map_template )
  {
    $arr_return = array();
    $mapMarkers = array();

    // Get rendered points (map marker), lats and lons
    $arr_return = $this->renderMapMarkerPoints();
    $mapMarkers = $arr_return[ 'data' ][ 'mapMarkers' ];
    // Get rendered points (map marker), lats and lons
    // Get points (map marker) as JSON array and coordinates
    $arr_return = $this->renderMapMarkerPointsToJson( $mapMarkers );

    $jsonData = $arr_return[ 'data' ][ 'jsonData' ];
    $jsonData = $this->renderMapMarkerVariablesSystemJsonDataNull( $jsonData );
    $coordinates = $arr_return[ 'data' ][ 'coordinates' ];
    // Get points (map marker) as JSON array and coordinates
    // Add JSON array
    // #47631, #i0007, dwildt, 10+
    switch ( true )
    {
      case( $this->pObj->typoscriptVersion <= 4005004 ):
        $jsonMarker = "'###JSONDATA###'";
        break;
      case( $this->pObj->typoscriptVersion <= 4005007 ):
      default:
        $jsonMarker = "'###RAWDATA###'";
        break;
    }

    // #47631, #i0007, dwildt, 10+
    $map_template = str_replace( $jsonMarker, $jsonData, $map_template );

    // Set center coordinates
    $arr_result = $this->renderMapAutoCenterCoor( $map_template, $coordinates );
    $map_template = $arr_result[ 'map_template' ];
    $coordinates = $arr_result[ 'coordinates' ];
    unset( $arr_result );
    // Set zoom level
    $map_template = $this->renderMapAutoZoomLevel( $map_template, $coordinates );

    return $map_template;
  }

  /**
   * renderMapMarkerVariablesSystemJsonDataNull( ):
   *
   * @param	string		$jsonData: ...
   * @return	string  $jsonData
   * @internal #i0060
   * @version 5.0.10
   * @since   5.0.10
   */
  private function renderMapMarkerVariablesSystemJsonDataNull( $jsonData )
  {
    if ( !empty( $jsonData ) )
    {
      return $jsonData;
    }
    $jsonData = '{}';
    return $jsonData;
  }

  /**
   * renderMapMarkerVariablesSystemItem( ):
   *
   * @param	string		$map_template: ...
   * @return	string
   * @version 4.5.11
   * @since   4.1.0
   */
  private function renderMapMarkerVariablesSystemItem( $item )
  {
    $name = $this->confMap[ 'marker.' ][ 'variables.' ][ 'system.' ][ $item ];
    $conf = $this->confMap[ 'marker.' ][ 'variables.' ][ 'system.' ][ $item . '.' ];
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );

    //if( $item == 'url' )
    //{
    //  var_dump( __METHOD__, __LINE__, $value, $name, $conf );
    //}
    $this->renderMapMarkerVariablesSystemItemUrl( $item, $value );

    return $value;
  }

  /**
   * renderMapMarkerVariablesSystemItemUrl( ):
   *
   * @param	string		$map_template: ...
   * @return	string
   * @internal    #i0014
   * @version 4.5.8
   * @since   4.5.8
   */
  private function renderMapMarkerVariablesSystemItemUrl( $item, $value )
  {
    // RETURN : current item isn't the url
    if ( $item != 'url' )
    {
      return;
    }
    // RETURN : current item isn't the url
    // RETURN : there is #1 browser plugins only
    $numberOfBrowserPlugins = $this->pObj->objFlexform->get_numberOfBrowserPlugins();
    if ( $numberOfBrowserPlugins <= 1 )
    {
      return;
    }
    // RETURN : there is #1 browser plugins only
    // RETURN : DRS is disabled
    if ( !( $this->pObj->b_drs_map || $this->pObj->b_drs_warn ) )
    {
      return $numberOfBrowserPlugins;
    }
    // RETURN : DRS is disabled
    // DRS
    $urldecode = urldecode( $value );
    $pos = strpos( $urldecode, 'tx_browser_pi1[plugin]' );

    switch ( true )
    {
      case( $pos === false ):
        $prompt = 'There are #' . $numberOfBrowserPlugins . ' Browser plugins on the current page.';
        t3lib_div :: devlog( '[WARN/FLEXFORM] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'Current link doesn\'t contain the parameter tx_browser_pi1[plugin]: ' . $urldecode;
        t3lib_div :: devlog( '[ERROR/FLEXFORM] ' . $prompt, $this->pObj->extKey, 3 );
        $prompt = 'In case of realUrl link can be proper. This depends on your realUrl configuration.';
        t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 2 );
        break;
      case(!( $pos === false ) ):
      default:
        $prompt = 'There are #' . $numberOfBrowserPlugins . ' Browser plugins on the current page.';
        t3lib_div :: devlog( '[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'Current link contains the parameter tx_browser_pi1[plugin]: ' . $urldecode;
        t3lib_div :: devlog( '[OK/FLEXFORM] ' . $prompt, $this->pObj->extKey, -1 );
        break;
    }
    // DRS

    unset( $pos );
    return;
  }

  /**
   * renderMapMarkerWoMarker( ): Return an empty template, if there isn' any marker
   *
   * @param	string		$mapHaskKey   : Should be ###MAP###
   * @param	string		$template     : current HTML template of the parent object
   * @return	string		$template     : current HTML template with the rendered map
   * @internal #i0120
   * @version 6.0.7
   * @since   6.0.7
   */
  private function renderMapMarkerWoMarker( $mapHashKey, $template )
  {
    if ( !empty( $this->arrCategories ) )
    {
      return null;
    }

    $template = str_replace( $mapHashKey, null, $template );

    if ( !$this->pObj->b_drs_map )
    {
      return $template;
    }

    $prompt = 'Map is returned without any content.';
    t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );

    return $template;
  }

  /*   * *********************************************
   *
   * Map +Routes
   *
   * ******************************************** */

  /**
   * renderMapRoute( ):
   *
   * @return	array
   * @version 4.5.11
   * @since   4.5.6
   */
  private function renderMapRoute()
  {
    $arrReturn = array
      (
      'error' => false
      , 'prompt' => null
    );

    // RETURN : Map +Routes is disabled
    if ( $this->enabled != 'Map +Routes' )
    {
      // DRS
      if ( $this->pObj->b_drs_map )
      {
        $prompt = 'Map +Routes is disabled.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
      }
      // DRS
      return $arrReturn;
    }
    // RETURN : Map +Routes is disabled
    // Init
    $this->renderMapRouteInit();

    // Get paths
    // #i0020, 130718, dwildt
    $arrResult = $this->renderMapRoutePaths();
    $rowsPathWiCat = $arrResult[ 'rowsPathWiCat' ];
    $jsonData = $arrResult[ 'jsonData' ];


    // Get marker
    $marker = $this->renderMapRouteMarker( $rowsPathWiCat );

    $arrReturn[ 'marker' ] = $marker;
    $arrReturn[ 'jsonRoutes' ] = $jsonData;
    // #i0020, 130718, dwildt, 1+
    $arrReturn[ 'rows' ] = $rowsPathWiCat;
//$this->pObj->dev_var_dump( $arrReturn );

    return $arrReturn;
  }

  /**
   * renderMapRouteArrCatAndMarker( ) : Returns an array with the elements cat and marker.
   *                                    * Cat
   *                                      * key   is the uid    of a category
   *                                      * value is the title  of a category
   *                                    * Marker
   *                                      * key   is the uid    of a marker
   *                                      * value is the title  of a marker
   *
   * @param       integer $pathUid  : uid of the current path
   * @return	array   $arrReturn  : Array with elements cat and marker
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRouteArrCatAndMarker( $pathUid )
  {
    // variables
    $markerTitle = null;
    $markerUid = null;
    $markerCatTitle = null;
    $markerCatUid = null;
    // #i0009, 130610, dwildt, 2+
    $pathCatTitle = null;
    $pathCatUid = null;
    $arrMarkerCat = null;
    $arrMarkerCatUid = null;
    $arrMarkerCatTitle = null;
    // #i0009, 130610, dwildt, 3+
    $arrPathCat = null;
    $arrPathCatUid = null;
    $arrPathCatTitle = null;
    $arrMarker = null;
    $arrMarkerUid = null;
    $arrMarkerTitle = null;
    $confMapRouteFields = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ];
    $tablePathTitle = $confMapRouteFields[ 'path.' ][ 'title' ];
    list( $tablePath ) = explode( '.', $tablePathTitle );
    $tablePathUid = $tablePath . '.uid';
    $tableMarkerTitle = $confMapRouteFields[ 'marker.' ][ 'title' ];
    list( $tableMarker ) = explode( '.', $tableMarkerTitle );
    $tableMarkerUid = $tableMarker . '.uid';
    $tableMarkerCatTitle = $confMapRouteFields[ 'markerCategory.' ][ 'title' ];
    list( $tableMarkerCat ) = explode( '.', $tableMarkerCatTitle );
    $tableMarkerCatUid = $tableMarkerCat . '.uid';
    // #i0009, 130610, dwildt, 3+
    $tablePathCatTitle = $confMapRouteFields[ 'pathCategory.' ][ 'title' ];
    list( $tablePathCat ) = explode( '.', $tablePathCatTitle );
    $tablePathCatUid = $tablePathCat . '.uid';
    // variables
    // Get marker and marker_cat values of the given row
    foreach ( $this->pObj->rows as $row )
    {
//$this->pObj->dev_var_dump( $row );
      if ( $row[ $tablePathUid ] != $pathUid )
      {
        continue;
      }
      $markerCatTitle = $row[ $tableMarkerCatTitle ];
      $markerCatUid = $row[ $tableMarkerCatUid ];
      $pathCatTitle = $row[ $tablePathCatTitle ];
      $pathCatUid = $row[ $tablePathCatUid ];
      $markerTitle = $row[ $tableMarkerTitle ];
      $markerUid = $row[ $tableMarkerUid ];
      break;
    }
    //$this->pObj->dev_var_dump( $markerCatTitle, $markerCatUid, $markerTitle, $markerUid );
    // Get marker and marker_cat values of the given row

    $arrMarkerCatUid = explode( $this->catDevider, $markerCatUid );
    $arrMarkerCatTitle = explode( $this->catDevider, $markerCatTitle );
    // #i0009, 130610, dwildt, 2+
    $arrPathCatUid = explode( $this->catDevider, $pathCatUid );
    $arrPathCatTitle = explode( $this->catDevider, $pathCatTitle );
    $arrMarkerUid = explode( $this->catDevider, $markerUid );
    $arrMarkerTitle = explode( $this->catDevider, $markerTitle );

    foreach ( $arrMarkerCatUid as $key => $uid )
    {
      $strMarkerCatTitle = $arrMarkerCatTitle[ $key ];
//      $strMarkerCatTitle = str_replace( ' ', null, $strMarkerCatTitle );
      $strMarkerCatTitle = $this->zz_properFormLabel( $strMarkerCatTitle );
      $arrMarkerCat[ $uid ] = $strMarkerCatTitle . ':' . $tableMarker;
    }

    // #i0009, 130610, dwildt, 4+
    foreach ( $arrPathCatUid as $key => $uid )
    {
      $strPathCatTitle = $arrPathCatTitle[ $key ];
      $strPathCatTitle = $this->zz_properFormLabel( $strPathCatTitle );
      $arrPathCat[ $pathUid ] = $strPathCatTitle . ':' . $tablePath;
    }

    foreach ( $arrMarkerUid as $key => $uid )
    {
      $arrMarker[ $uid ] = $arrMarkerTitle[ $key ];
    }

    $arrReturn = array
      (
      'cat' => $arrMarkerCat,
      // #i0009, 130610, dwildt, 1+
      'pathCat' => $arrPathCat,
      'marker' => $arrMarker
    );
//$this->pObj->dev_var_dump( $arrReturn );

    return $arrReturn;
  }

  /**
   * renderMapRouteInit( ):
   *
   * @return	void
   * @version 4.5.6
   * @since   4.5.6
   */
  private function renderMapRouteInit()
  {
    $this->renderMapRouteInitRequire();
  }

  /**
   * renderMapRouteInitRequire( ):
   *
   * @return	void
   * @version 4.5.6
   * @since   4.5.6
   */
  private function renderMapRouteInitRequire()
  {
    $this->renderMapRouteInitRequireTables();
  }

  /**
   * renderMapRouteInitRequireTables( ):
   *
   * @return	void
   * @version 4.5.6
   * @since   4.5.6
   */
  private function renderMapRouteInitRequireTables()
  {
    $tables = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ];

    // LOOP tables
    foreach ( $tables as $table => $fields )
    {
      // CONTINUE : current value isn't an array
      if ( substr( $table, -1, 1 ) != '.' )
      {
        continue;
      }
      // CONTINUE : current value isn't an array
      // LOOP fields
      foreach ( $fields as $field => $value )
      {
        // CONTINUE : $value is set
        if ( !empty( $value ) )
        {
          continue;
        }
        // CONTINUE : $value is set
        // DIE  : $value is empty
        $header = 'FATAL ERROR!';
        $text = $table . $field . ' is empty. <br />
                Please take care off a proper TypoScript configuration at<br />
                <span style="font-weight:bold;">plugin.tx_browser_pi1.navigation.map.configuration.route.tables.' . $table . $field . '</span><br />
                Please use the TypoScript Constant Editor.
                ';
        $this->pObj->drs_die( $header, $text );
        // DIE  : $value is empty
//        $this->pObj->dev_var_dump( $table, $field, $value );
      }
      // LOOP fields
    }
    // LOOP tables
  }

  /**
   * renderMapRouteMarker( ):
   *
   * @param       array     $rowsPathWiCat  :
   * @return	array     $marker : Marker array
   *
   * @version 4.5.11
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarker( $rowsPathWiCat )
  {

    // Get relations marker -> categrories
    $arrResult = $this->renderMapRouteMarkerRelations();
    $rowsRelation = $arrResult[ 'rowsRelation' ];
    $tableMarker = $arrResult[ 'tableMarker' ];
    $tableCat = $arrResult[ 'tableCat' ];
    //$tablePath    = $arrResult['tablePath'];
    unset( $arrResult );

    $marker = $this->renderMapRouteTableWiCat( $tableMarker, $tableCat, $rowsRelation );

    // Merge a marker for each path
    $marker = array_merge( $marker, $this->renderMapRouteMarkerByPath() );

    $marker = $this->renderMapRouteMarkerAddPaths( $marker, $rowsPathWiCat );

    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'Marker rows: ' . var_export( $marker, true );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    return $marker;
  }

  /**
   * renderMapRouteMarkerAddPaths( ):
   *
   * @param       array     $marker         :
   * @param       array     $rowsPathWiCat  :
   * @return	array     $marker         : Marker array
   *
   * @internal    #i0020
   *
   * @version 4.5.11
   * @since   4.5.7
   *
   */
  private function renderMapRouteMarkerAddPaths( $marker, $rowsPathWiCat )
  {
    // 130903, dwildt, 1-
    //$tablePath      = $this->confMap['configuration.']['route.']['markerMapper.']['tables.']['local.']['path'];
    $tableMarker = $this->confMap[ 'configuration.' ][ 'route.' ][ 'markerMapper.' ][ 'tables.' ][ 'local.' ][ 'marker' ];
    // 130903, dwildt, 1-
    //$tablePathUid   = $tablePath    . '.uid';
    $tableMarkerUid = $tableMarker . '.uid';

    foreach ( $marker as $key => $arrMarker )
    {
      if ( $arrMarker[ 'type' ] != 'route' )
      {
        continue;
      }
      $pathUid = $arrMarker[ $tableMarkerUid ];
//$this->pObj->dev_var_dump( $tableMarkerUid, $pathUid );
      if ( !isset( $rowsPathWiCat[ $pathUid ] ) )
      {
        continue;
      }
      $marker[ $key ] = array_merge( $marker[ $key ], $rowsPathWiCat[ $pathUid ] );
    }
//$this->pObj->dev_var_dump( $rowsPathWiCat, $marker );

    return $marker;
  }

  /**
   * renderMapRouteMarkerByPath( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerByPath()
  {
    $marker = array();

    // short variables
    $confMapper = $this->confMap[ 'configuration.' ][ 'route.' ][ 'markerMapper.' ];
    $tableMarker = $confMapper[ 'tables.' ][ 'local.' ][ 'marker' ];
    // short variables

    foreach ( $this->pObj->rows as $rowIn )
    {
      $rowOut = $this->renderMapRouteMarkerByPathRow( $rowIn );
      $key = $rowOut[ $tableMarker . '.uid' ];
      $marker[ $key ] = $rowOut;
    }
    return $marker;
  }

  /**
   * renderMapRouteMarkerByPath( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerByPathRow( $elements )
  {
    $row = array();
    $row = $row + $this->renderMapRouteMarkerByPathRowLocal( $elements ) + $this->renderMapRouteMarkerByPathRowCat( $elements )
    ;
//$this->pObj->dev_var_dump( $row );

    return $row;
  }

  /**
   * renderMapRouteMarkerByPathLocal( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerByPathRowLocal( $elements )
  {
    $row = array();

    $row = $this->renderMapRouteMarkerByPathRowLocalObligate( $elements ) + $this->renderMapRouteMarkerByPathRowLocalOptional( $elements )
    ;

    return $row;
  }

  /**
   * renderMapRouteMarkerByPathLocalObligate( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.15
   * @since   4.5.7
   *
   * @internal    #47630, #i0013
   */
  private function renderMapRouteMarkerByPathRowLocalObligate( $elements )
  {
    $row = array();

    // short variables
    $dontHandle00 = $this->confMap[ 'configuration.' ][ '00Coordinates.' ][ 'dontHandle' ];
    $confMapper = $this->confMap[ 'configuration.' ][ 'route.' ][ 'markerMapper.' ];
    $tablePath = $confMapper[ 'tables.' ][ 'local.' ][ 'path' ];
    $tableMarker = $confMapper[ 'tables.' ][ 'local.' ][ 'marker' ];
    // short variables

    switch ( true )
    {
      case( empty( $tablePath ) ):
      case( empty( $tableMarker ) ):
        $header = 'FATAL ERROR!';
        $text = '
                A label for the table with the path data is missing!<br />
                Please take care off a proper TypoScript configuration at<br />
                <span style="font-weight:bold;">plugin.tx_browser_pi1.navigation.map.configuration.route.markerMapper.tables.local.*</span>
                ';
        $this->pObj->drs_die( $header, $text );
      default:
        // follow the workflow
        break;
    }

    $fieldsObligate = $confMapper[ 'fields.' ][ 'local.' ][ 'obligate.' ];
    // FOREACH  : obligate fields
    foreach ( $fieldsObligate as $fields => $field )
    {
      // CONTINUE : field doesn't have any property
      if ( !is_array( $field ) )
      {
        continue;
      }
      // CONTINUE : field doesn't have any property

      $key = trim( $fields, '.' );
      $valuePath = $field[ 'path' ];
      $valueMarker = $field[ 'marker' ];

      $pathTableField = $tablePath . '.' . $valuePath;
      $markerTableField = $tableMarker . '.' . $valueMarker;

      // DIE  : one off the values is empty
      switch ( true )
      {
        case( empty( $valuePath ) ):
        case( empty( $valueMarker ) ):
          $header = 'FATAL ERROR!';
          $text = '
                A label for a field is missing!<br />
                Please take care off a proper TypoScript configuration at<br />
                <span style="font-weight:bold;">plugin.tx_browser_pi1.navigation.map.configuration.route.markerMapper.fields.local.obligate.' . $key . '.*</span>
                ';
          $this->pObj->drs_die( $header, $text );
        case(!isset( $elements[ $pathTableField ] ) ):
          $header = 'FATAL ERROR!';
          $text = '
                Row doesn\'t contain the element ' . $pathTableField . '!<br />
                Please take care off a proper TypoScript configuration at<br />
                Please check, if your SQL query contains ' . $pathTableField . '
                ';
          $this->pObj->drs_die( $header, $text );
        default:
          // follow the workflow
          break;
      }
      // DIE  : one off the values is empty

      $row[ $markerTableField ] = $elements[ $pathTableField ];

      switch ( true )
      {
        case( $key == 'lat' ):
        case( $key == 'lon' ):
          if ( !$dontHandle00 )
          {
            $row[ $key ] = $elements[ $pathTableField ];
            continue;
          }
          if ( empty( $elements[ $pathTableField ] ) )
          {
            $pluginPid = $this->pObj->cObj->data[ 'pid' ];
            $pluginUid = $this->pObj->cObj->data[ 'uid' ];
            $pluginTitle = $this->pObj->cObj->data[ 'header' ];
            $mode = $this->mode;
            $view = $this->pObj->view;
            $viewWiDot = $view . '.';
            $tsPath = 'plugin.tx_browser_pi1.views.' . $viewWiDot . $mode;

            $header = $this->pObj->pi_getLL( 'error_maproutes_lonLat_empty_h1' );
            $text = $this->pObj->pi_getLL( 'error_maproutes_lonLat_empty_prompt' );
            $text = str_replace( '%pluginPid%', $pluginPid, $text );
            $text = str_replace( '%pluginUid%', $pluginUid, $text );
            $text = str_replace( '%pluginTitle%', $pluginTitle, $text );
            $text = str_replace( '%tsPath%', $tsPath, $text );
            $this->pObj->drs_die( $header, $text );
          }
          break;
        default:
          $row[ $key ] = $elements[ $pathTableField ];
          break;
      }
    }
    // FOREACH  : obligate fields

    $row[ 'markerTable' ] = $tablePath;
    // #i0013, 130701, dwildt, +
    $row[ 'type' ] = 'route';

//$this->pObj->dev_var_dump( $row );
    return $row;
  }

  /**
   * renderMapRouteMarkerByPathLocalOptional( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerByPathRowLocalOptional( $elements )
  {
    $row = array();

    // short variables
    $confMapper = $this->confMap[ 'configuration.' ][ 'route.' ][ 'markerMapper.' ];
    $tablePath = $confMapper[ 'tables.' ][ 'local.' ][ 'path' ];
    $tableMarker = $confMapper[ 'tables.' ][ 'local.' ][ 'marker' ];
    // short variables

    $fieldsOptional = $confMapper[ 'fields.' ][ 'local.' ][ 'optional.' ];
    foreach ( $fieldsOptional as $field )
    {
      // CONTINUE : field doesn't have any property
      if ( !is_array( $field ) )
      {
        continue;
      }
      // CONTINUE : field doesn't have any property

      $valuePath = $field[ 'path' ];
      $valueMarker = $field[ 'marker' ];

      $pathTableField = $tablePath . '.' . $valuePath;

      if ( !isset( $elements[ $pathTableField ] ) )
      {
        continue;
      }

      $markerTableField = $tableMarker . '.' . $valueMarker;
      $row[ $markerTableField ] = $elements[ $pathTableField ];
    }

    return $row;
  }

  /**
   * renderMapRouteMarkerByPathCat( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerByPathRowCat( $elements )
  {
    $row = array();

    // short variables
    $confMapper = $this->confMap[ 'configuration.' ][ 'route.' ][ 'markerMapper.' ];
    $tablePathCat = $confMapper[ 'tables.' ][ 'cat.' ][ 'path' ];
    $tableMarkerCat = $confMapper[ 'tables.' ][ 'cat.' ][ 'marker' ];
    // short variables

    switch ( true )
    {
      case( empty( $tablePathCat ) ):
      case( empty( $tableMarkerCat ) ):
        $header = 'FATAL ERROR!';
        $text = '
                  A label for the table with the path data is missing!<br />
                  Please take care off a proper TypoScript configuration at:<br />
                  plugin.tx_browser_pi1.navigation.map.configuration.route.markerMapper.tables.cat.*
                  ';
        $this->pObj->drs_die( $header, $text );
      default:
        // follow the workflow
        break;
    }

    $fieldsCat = $confMapper[ 'fields.' ][ 'cat.' ];
    foreach ( $fieldsCat as $fields => $field )
    {
      // CONTINUE : field doesn't have any property
      if ( !is_array( $field ) )
      {
        continue;
      }
      // CONTINUE : field doesn't have any property

      $key = trim( $fields, '.' );
      $valuePath = $field[ 'path' ];
      $valueMarker = $field[ 'marker' ];

      $pathTableField = $tablePathCat . '.' . $valuePath;

      if ( !isset( $elements[ $pathTableField ] ) )
      {
        // DRS
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'navigation.map.configuration.route.markerMapper.fields.cat.' . $key . '.* is configured,'
                  . 'but the current row doesn\'t contain the element ' . $pathTableField . '.';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 2 );
        }
        // DRS
        continue;
      }

      $markerTableField = $tableMarkerCat . '.' . $valueMarker;
      $row[ $markerTableField ] = $elements[ $pathTableField ];
    }

    return $row;

//    $row = array
//          (
//            'tx_route_marker_cat.title' => 'Test',
//            'tx_route_marker_cat.icons' => 'target_018px.png,target_036px_shadow.png,target_48px.png',
//            'tx_route_marker_cat.icon_offset_x' => '-24',
//            'tx_route_marker_cat.icon_offset_y' => '-24',
//            'tx_route_marker_cat.uid' => '10',
//          );
//
//      // Get rows
//    return $row;
  }

  /**
   * renderMapRouteTableWiCat( )  : Consolidate table rows.
   *                                Categories will added to each row.
   *                                If there is more than one category, categories will
   *                                handled as children - devided by the devider from typoScript
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteTableWiCat( $tableLocal, $tableCat, $rowsRelation )
  {
    // $rowsLocal: rows without categories
    //    tx_route_marker is the local table in the sample below
    //
      //array (
    //  3 =>
    //  array (
    //    'tx_route_marker.title' => 'Reichstag',
    //    'tx_route_marker.lat'   => '13.376210',
    //    'tx_route_marker.lon'   => '52.518572',
    //    'tx_route_marker.image' => 'Reichstag_Berlin.jpg',
    //    'tx_route_marker.uid'   => '3',
    //  ),
    // Get rows (they don't have any category currently)
    $rowsLocal = $this->renderMapRouteMarkerGetRowsByTable( $tableLocal );
    // Get category rows
    $rowsCat = $this->renderMapRouteMarkerGetRowsByTable( $tableCat );

    // LOOP relations
    foreach ( $rowsRelation as $localUid => $catUids )
    {
      // LOOP categories
      foreach ( $catUids as $catUid )
      {
        // LOOP category fields
        foreach ( $rowsCat[ $catUid ] as $catTableField => $catValue )
        {
          // SWITCH: local with or without category field
          switch ( true )
          {
            // CASE: with category field
            case( isset( $rowsLocal[ $localUid ][ $catTableField ] ) ):
              $rowsLocal[ $localUid ][ $catTableField ] = $rowsLocal[ $localUid ][ $catTableField ]
                      . $this->catDevider
                      . $catValue
              ;
              break;
            // CASE: with category field
            // CASE: without category field
            case(!isset( $rowsLocal[ $localUid ][ $catTableField ] ) ):
            default:
              $rowsLocal[ $localUid ][ $catTableField ] = $catValue;
              break;
            // CASE: without category field
          }
          // SWITCH: local with or without category field
        }
        // LOOP category fields
      }
      // LOOP categories
    }
    // LOOP relations
    // $rowsLocal: rows with categories
    //    tx_route_marker     is the local    table in the sample below
    //    tx_route_marker_cat is the category table in the sample below
    //
      //array (
    //  3 =>
    //  array (
    //    'tx_route_marker.title'             => 'Reichstag',
    //    'tx_route_marker.lat'               => '13.376210',
    //    ...
    //    'tx_route_marker_cat.title'         => 'Geschichte, ;|;Politik, ;|;Politik-Pfad',
    //    'tx_route_marker_cat.icons'         => 'history.png, ;|;badge.png, ;|;badge_01.png',
    //    'tx_route_marker_cat.icon_offset_x' => '2, ;|;4, ;|;6',
    //    'tx_route_marker_cat.icon_offset_y' => '2, ;|;4, ;|;6',
    //    'tx_route_marker_cat.uid'           => '10, ;|;9, ;|;8',
    //  ),
    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'Rows of ' . $tableLocal . ': ' . var_export( $rowsLocal, true );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
//$this->pObj->dev_var_dump( $rowsLocal );
    return $rowsLocal;
  }

  /**
   * renderMapRouteMarkerRelations( ) : Get relations marker -> categrories
   *                                    rowsRelation array will look like:
   *                                    * 7 => array( 4, 10, 7 ), 5 => array( 10, 8 )
   *                                    * tableMarker.uid = array ( tableCat.uid, tableCat.uid, tableCat.uid )
   *
   * @return	array   $arrReturn : with Elements rowsRelation, tableCat, tableMarker, tablePath
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerRelations()
  {
    $arrReturn = array();
    $rowsRelation = array();

    // Example
    // $relation[0]['MARKER:tx_route_path->tx_route_marker->tx_route_marker_cat->listOf.uid'] = '2.3.10, ;|;2.3.9, ;|;2.3.8, ;|;2.4.10, ;|;2.4.8, ;|;2.4.7, ;|;2.5.10, ;|;2.5.8';
    //    '2.3.10' is a relation like
    //    tx_route_path.uid -> tx_route_marker.uid -> tx_route_marker_cat.uid
    //

      // Get the MARKER relations (each element with a prefix MARKER - see example above)
    $relations = $this->renderMapRouteRelations( 'MARKER' );
//$this->pObj->dev_var_dump( $relations );
    // Get the key of a relation
    $relationKey = key( $relations[ 0 ] );

    // Get the lables for the tables path, marker and markerCat
    list( $prefix, $tables ) = explode( ':', $relationKey );
    unset( $prefix );
    list( $tablePath, $tableMarker, $tableMarkerCat ) = explode( '->', $tables );
    // Get the lables for the tables path, marker and markerCat
    // LOOP relations
    foreach ( $relations as $relation )
    {
      // LOOP relation
      foreach ( $relation as $tablePathMarkerCat )
      {
        $arrTablePathMarkerCat = explode( $this->catDevider, $tablePathMarkerCat );
        // SWITCH : children
        switch ( true )
        {
          // CASE : children
          case( count( $arrTablePathMarkerCat ) > 1 ):
            // LOOP children
            foreach ( $arrTablePathMarkerCat as $arrTablePathMarkerCatChildren )
            {
              list( $pathUid, $markerUid, $catUid ) = explode( '.', $arrTablePathMarkerCatChildren );
              unset( $pathUid );
              $rowsRelation[ $markerUid ][] = $catUid;
              $rowsRelation[ $markerUid ] = array_unique( $rowsRelation[ $markerUid ] );
            }
            // LOOP children
            break;
          // CASE : children
          // CASE : no children
          case( count( $arrTablePathMarkerCat ) == 1 ):
          default:
            list( $pathUid, $markerUid, $catUid ) = explode( '.', $arrTablePathMarkerCatChildren );
            unset( $pathUid );
            $rowsRelation[ $markerUid ][] = $catUid;
            $rowsRelation[ $markerUid ] = array_unique( $rowsRelation[ $markerUid ] );
            break;
          // CASE : no children
        }
        // SWITCH : children
      }
      // LOOP relation
    }
    // LOOP relations
    // $rowsRelation will look like:
    // array(
    //  7 => array( 4, 10, 7 ),
    //  5 => array( 10, 8 ),
    // )
    // array(
    //  tableMarker.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    //  tableMarker.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    // )
//$this->pObj->dev_var_dump( $rowsRelation );
    $arrReturn[ 'rowsRelation' ] = $rowsRelation;
    $arrReturn[ 'tableCat' ] = $tableMarkerCat;
    $arrReturn[ 'tableMarker' ] = $tableMarker;
    $arrReturn[ 'tablePath' ] = $tablePath;

    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = var_export( $rowsRelation, true );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    return $arrReturn;
  }

  /**
   * renderMapRouteRelations( ): Returns all elements which have a key with the given prefixMarker
   *
   * @param       string    $prefixMarker : MARKER || PATH
   * @return	array     $relations    : elements with keys with prefix MARKER
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteRelations( $prefixMarker )
  {
    // Example
    // $rows[0]['MARKER:tx_route_path->tx_route_marker->tx_route_marker_cat->listOf.uid'] = '2.3.10, ;|;2.3.9, ;|;2.3.8, ;|;2.4.10, ;|;2.4.8, ;|;2.4.7, ;|;2.5.10, ;|;2.5.8';

    $relations = array();
    $rowCounter = 0;

    // LOOP rows
//$this->pObj->dev_var_dump( $this->pObj->rows );

    foreach ( $this->pObj->rows as $row )
    {
      // LOOP row
      foreach ( $row as $key => $value )
      {
        list( $prefix ) = explode( ':', $key );
        if ( !( $prefix == $prefixMarker ) )
        {
          // CONTINUE : element hasn't any prefix MARKER
          continue;
        }
        if ( $value == null )
        {
          // CONTINUE : there isn't any value
          continue;
        }
        $relations[ $rowCounter ][ $key ] = $value;
        break;
      }
      // LOOP row
      $rowCounter++;
    }
    // LOOP rows
//$this->pObj->dev_var_dump( $prefixMarker, $relations );
    // If there is an evaluation error, this method will die!
    $this->renderMapRouteRelationsEval( $prefixMarker, $relations );

    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = var_export( $relations, true );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    return $relations;
  }

  /**
   * renderMapRouteRelationsEval( ) : This method will prompt, if
   *                                  * a path hasn't any relation to a path category
   *                                  * a path hasn't any relation to a marker
   *
   * @param       string    $prefixMarker : MARKER || PATH
   * @return	void
   * @version 4.5.14
   * @since   4.5.8
   *
   * @internal    #47630
   */
  private function renderMapRouteRelationsEval( $prefixMarker, $relations )
  {
    $numberOfRoutes = count( $this->pObj->rows );
    $numberOfRecordsWithRelations = count( $relations );

    if ( $numberOfRoutes == $numberOfRecordsWithRelations )
    {
      return;
    }

    $pluginPid = $this->pObj->cObj->data[ 'pid' ];
    $pluginUid = $this->pObj->cObj->data[ 'uid' ];
    $pluginTitle = $this->pObj->cObj->data[ 'header' ];
    $mode = $this->mode;
    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $tsPath = 'plugin.tx_browser_pi1.views.' . $viewWiDot . $mode;

    switch ( $prefixMarker )
    {
      case( 'MARKER' ):
//$this->pObj->dev_var_dump( $this->pObj->rows );
        $prompt = '<div style="background:white;">' .
                ' <h1 style="color:red;">'
                . $this->pObj->pi_getLL( 'error_maproutes_marker_relation_h1' )
                . '</h1>'
                . $this->pObj->pi_getLL( 'error_maproutes_marker_relation_missing' )
                . '<p>' . PHP_EOL
                . __METHOD__ . ' (line ' . __LINE__ . '): <br />' . PHP_EOL
                . '</p>'
                . '</div>';
        break;
      case( 'PATH' ):
//$this->pObj->dev_var_dump( $this->pObj->rows );
        $prompt = '<div style="background:white;">' .
                ' <h1 style="color:red;">'
                . $this->pObj->pi_getLL( 'error_maproutes_path_relation_h1' )
                . '</h1>'
                . $this->pObj->pi_getLL( 'error_maproutes_path_relation_missing' )
                . '<p>' . PHP_EOL
                . __METHOD__ . ' (line ' . __LINE__ . '): <br />' . PHP_EOL
                . '</p>'
                . '</div>';
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'prefixMarker must be MARKER or PATH.';
        $this->pObj->drs_die( $header, $text );
    }

    $prompt = str_replace( '%pluginPid%', $pluginPid, $prompt );
    $prompt = str_replace( '%pluginUid%', $pluginUid, $prompt );
    $prompt = str_replace( '%pluginTitle%', $pluginTitle, $prompt );
    $prompt = str_replace( '%tsPath%', $tsPath, $prompt );

    // die: no relation

    $promptToFrontend = $this->confMap[ 'debugging.' ][ 'route.' ][ 'relations' ];
    if ( $promptToFrontend )
    {
      echo( $prompt );
    }

    // RETURN : no DRS
    if ( !$this->pObj->b_drs_error )
    {
      return;
    }
    // RETURN : no DRS
    // DRS
    t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 3 );
    // DRS
  }

  /**
   * renderMapRouteMarkerGeodata( )  : Adds a marker for each path
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRouteMarkerGeodata( $key, $elements )
  {

    // short variables
    $tableFieldGeodata = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ][ 'path.' ][ 'geodata' ];
    $tableFieldIconposition = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ][ 'path.' ][ 'iconposition' ];
    $intPosition = ( int ) $elements[ $tableFieldIconposition ];
    $strGeodata = $elements[ $tableFieldGeodata ];
    $arrGeodata = $this->renderMapRoutePathsJsonFeaturesCoordinates( $strGeodata );

    switch ( true )
    {
      case( $intPosition == 1 ):
        // center
        $itemNumber = ( int ) ( count( $arrGeodata ) / 2 );
        break;
      case( $intPosition == 2 ):
        // end
        $itemNumber = count( $arrGeodata ) - 1;
        break;
      case( $intPosition === 0 ):
      default:
        // beginning
        $itemNumber = 0;
        break;
    }
    $geodata = $arrGeodata[ $itemNumber ];

    if ( empty( $geodata ) )
    {
      $header = 'FATAL ERROR!';
      $text = 'Array geodata is empty!';
      $this->pObj->drs_die( $header, $text );
    }

    switch ( $key )
    {
      case( 'lat' ):
        return $geodata[ 1 ];
        break;
      case( 'lon' ):
        return $geodata[ 0 ];
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'key must be "lat" or "lon", but key is "' . $key . '".';
        $this->pObj->drs_die( $header, $text );
    }

    unset( $intPosition );
  }

  /**
   * renderMapRouteMarkerGetRowsByTable( )  : Get from rows all elements, which tableField match the given tableMarker
   *
   * @param       string      $tableMarker  : label of the table with the marker
   * @return	array       $rowsOutput   : rows with elements of the given tableMarker only
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRouteMarkerGetRowsByTable( $tableMarker )
  {
    $rowsOutput = array();
    $rowsTemp = array();

    // LOOP rows
    $rowsCounter = 0;
    foreach ( $this->pObj->rows as $row )
    {
      // LOOP row
      foreach ( $row as $tableField => $value )
      {
        list( $table ) = explode( '.', $tableField );
        if ( $table != $tableMarker )
        {
          continue;
        }
        $children = explode( $this->catDevider, $value );
        $childCounter = 0;
        // LOOP children
        foreach ( $children as $child )
        {
          $uid = $rowsCounter + $childCounter;
          $rowsTemp[ $uid ][ $tableField ] = $child;
          $childCounter++;
          // DIE  : if there are more than 999 children
          if ( $childCounter > 999 )
          {
            $header = 'FATAL ERROR!';
            $text = 'There are more than 999 children.';
            $this->pObj->drs_die( $header, $text );
          }
          // DIE  : if there are more than 999 children
        }
        // LOOP children
      }
      // LOOP row
      $rowsCounter = $rowsCounter + 100;
    }
    // LOOP rows
    // LOOP rows
//var_dump(__METHOD__, __LINE__, $rowsTemp);
    foreach ( $rowsTemp as $row )
    {
      // unique array
      $uid = $row[ $tableMarker . '.uid' ];
      $rowsOutput[ $uid ] = $row;
    }
    // LOOP rows
//$this->pObj->dev_var_dump( $rowsOutput );
//var_dump(__METHOD__, __LINE__, $rowsOutput);
    return $rowsOutput;
  }

  /**
   * renderMapRoutePaths( ):
   *
   * @return	array
   * @version 4.5.11
   * @since   4.5.7
   */
  private function renderMapRoutePaths()
  {
    $arrReturn = array();
    $jsonData = array();

    // Get relations marker -> categrories
    $arrResult = $this->renderMapRoutePathCatRelations();
    $rowsRelation = $arrResult[ 'rowsRelation' ];
    $tablePath = $arrResult[ 'tablePath' ];
    $tableCat = $arrResult[ 'tableCat' ];
    //$this->pObj->dev_var_dump( $rowsRelation, $tablePath, $tableCat );
    unset( $arrResult );

    // Get rows with categories
    $rowsPathWiCat = $this->renderMapRouteTableWiCat( $tablePath, $tableCat, $rowsRelation );
    // Get json from rows
    $jsonData = $this->renderMapRoutePathsJson( $rowsPathWiCat );

    // #i0020, 130718, dwildt, 1-
    //return $jsonData;
    // #i0020, 130718, dwildt, 3+
    $arrReturn[ 'rowsPathWiCat' ] = $rowsPathWiCat;
    $arrReturn[ 'jsonData' ] = $jsonData;

    return $arrReturn;
    // #i0020, 130718, dwildt, 3+
  }

  /**
   * renderMapRoutePathsJson( ) :
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRoutePathsJson( $rowsPathWiCat )
  {
//$this->pObj->dev_var_dump( $this->pObj->rows );
    $series = array
      (
      'type' => 'FeatureCollection',
      'features' => $this->renderMapRoutePathsJsonFeatures( $rowsPathWiCat ),
    );

    $jsonData = json_encode( $series );
//$this->pObj->dev_var_dump( $series, $jsonData );
    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = 'JSON array for the paths: ' . var_export( $jsonData, true );
      $prompt = str_replace( ':{', ': {', $prompt );
      $prompt = str_replace( '},', '}, ', $prompt );
      $prompt = str_replace( '","', '", "', $prompt );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    return $jsonData;
  }

  /**
   * renderMapRoutePathsJsonFeatures( ) :
   *
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRoutePathsJsonFeatures( $rowsPathWiCat )
  {
    $confMapRouteFields = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ];
    $tablePathCatTitle = $confMapRouteFields[ 'pathCategory.' ][ 'title' ];
    $tablePathTitle = $confMapRouteFields[ 'path.' ][ 'title' ];
    list( $tablePath ) = explode( '.', $tablePathTitle );
    $tablePathColor = $confMapRouteFields[ 'path.' ][ 'color' ];
    $tablePathGeodata = $confMapRouteFields[ 'path.' ][ 'geodata' ];
    $tablePathLinewidth = $confMapRouteFields[ 'path.' ][ 'lineWidth' ];
    $tablePathUid = $tablePath . '.uid';
//$this->pObj->dev_var_dump( $rowsPathWiCat );
    $features = array();

    // LOOP rows
    foreach ( $rowsPathWiCat as $rowPathWiCat )
    {
      // short variables
      $category = $rowPathWiCat[ $tablePathCatTitle ];
      $coordinates = $this->renderMapRoutePathsJsonFeaturesCoordinates( $rowPathWiCat[ $tablePathGeodata ] );
      $id = $rowPathWiCat[ $tablePathUid ];
      $markerList = $this->renderMapRoutePathsJsonFeaturesMarker( $rowPathWiCat[ $tablePathUid ] );
      $name = $rowPathWiCat[ $tablePathTitle ];
      $strokeColor = $rowPathWiCat[ $tablePathColor ];
      $strokeWidth = $rowPathWiCat[ $tablePathLinewidth ];
      // short variables
      // feature begin
      $feature = array
        (
        'type' => 'Feature',
        'geometry' => array
          (
          'type' => 'LineString',
          'coordinates' => $coordinates,
        ), // geometry
        'properties' => array
          (
          'name' => $name,
          'id' => $id,
          'category' => $category,
          'markerList' => $markerList,
          'style' => array
            (
            'strokeWidth' => ( int ) $strokeWidth,
            'strokeColor' => $strokeColor,
          //'strokeDashstyle' => 'dot',
          ), // style
        ), // properties
      );  // feature
      // feature end
      $features[] = $feature;
    }
    // LOOP rows

    return $features;
  }

  /**
   * renderMapRoutePathsJsonFeaturesCoordinates( ) :
   *
   * @param       string    $strLonLat    : list of points (lon, lat), seperated by line feeds
   * @param       boolean   $pointAsArray : true: return point as an array. false: return point as a CSV list
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRoutePathsJsonFeaturesCoordinates( $strLonLat, $pointAsArray = true )
  {
    // DIE  : $strLonLat is empty
    $this->renderMapRoutePathsJsonFeaturesCoordinatesEval( $strLonLat );

    $coordinates = explode( PHP_EOL, $strLonLat );
    foreach ( $coordinates as $key => $coordinate )
    {
      $coordinate = trim( $coordinate );
      list( $lon, $lat ) = explode( ',', $coordinate );
      switch ( $pointAsArray )
      {
        case( false ):
          $coordinates[ $key ] = ( double ) trim( $lon ) . ',' . ( double ) trim( $lat );
          break;
        case( true ):
        default:
          $coordinates[ $key ] = array
            (
            ( double ) trim( $lon ),
            ( double ) trim( $lat )
          );
          break;
      }
    }

    // DIE  : $coordinates are empty
    if ( empty( $coordinates ) )
    {
      $header = 'FATAL ERROR!';
      $text = '
                Coordinates are empty.<br />
                Please take care off a proper TypoScript configuration at<br />
                <span style="font-weight:bold;">plugin.tx_browser_pi1.navigation.map.configuration.route.*</span>
                Please use the TypoScript Constant Editor.
                ';
      $this->pObj->drs_die( $header, $text );
    }
    // DIE  : $coordinates are empty

    return $coordinates;
  }

  /**
   * renderMapRoutePathsJsonFeaturesCoordinatesEval( ) :
   *
   * @param       string    $strLonLat    : list of points (lon, lat), seperated by line feeds
   * @param       boolean   $pointAsArray : true: return point as an array. false: return point as a CSV list
   * @return	array
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRoutePathsJsonFeaturesCoordinatesEval( $strLonLat )
  {
    if ( !empty( $strLonLat ) )
    {
      return;
    }

    $pluginPid = $this->pObj->cObj->data[ 'pid' ];
    $pluginUid = $this->pObj->cObj->data[ 'uid' ];
    $pluginTitle = $this->pObj->cObj->data[ 'header' ];
    $mode = $this->mode;
    $view = $this->pObj->view;
    $viewWiDot = $view . '.';
    $tsPath = 'plugin.tx_browser_pi1.views.' . $viewWiDot . $mode;

    $prompt = '<h1 style="color:red;">'
            . $this->pObj->pi_getLL( 'error_maproutes_geodata_empty_h1' )
            . '</h1>'
            . $this->pObj->pi_getLL( 'error_maproutes_geodata_empty_prompt' )
            . '<p>' . PHP_EOL
            . __METHOD__ . ' (line ' . __LINE__ . '): <br />' . PHP_EOL
            . '</p>';

    $prompt = str_replace( '%pluginPid%', $pluginPid, $prompt );
    $prompt = str_replace( '%pluginUid%', $pluginUid, $prompt );
    $prompt = str_replace( '%pluginTitle%', $pluginTitle, $prompt );
    $prompt = str_replace( '%tsPath%', $tsPath, $prompt );

    // die: no relation
    echo( $prompt );
  }

  /**
   * renderMapRoutePathsJsonFeaturesMarker( ) : Returns the marker of the current path
   *                                            A marker has two parts:
   *                                            * the categorie title
   *                                            * the marker uid
   *                                            The syntax is
   *                                            * catTitle:markerUid
   *                                            Example:
   *                                            * history:3
   *
   * @param       integer $pathUid  : uid of the current path
   * @return	array   $marker   : marker of the current path
   * @version 4.5.7
   * @since   4.5.7
   */
  private function renderMapRoutePathsJsonFeaturesMarker( $pathUid )
  {
    // Return array
    $marker = array();

    // variables
    static $arrResult = array();
    static $rowsRelation = null;
    static $tableCat = null;
    static $tableMarker = null;
    static $tablePath = null;
    $catTitle = null;
    $catUid = null;
    $markerUid = null;
    $arrCat = null;
    $arrMarker = null;
    $confMapRouteFields = $this->confMap[ 'configuration.' ][ 'route.' ][ 'tables.' ];
    $tablePathTitle = $confMapRouteFields[ 'path.' ][ 'title' ];
    $tableMarkerTitle = $confMapRouteFields[ 'marker.' ][ 'title' ];
    // variables
    // Get the labels of the tables path and marker
    list( $tablePath ) = explode( '.', $tablePathTitle );
    list( $tableMarker ) = explode( '.', $tableMarkerTitle );
    // Get the labels of the tables path and marker
    // Get relations path -> marker -> marker_cat
    if ( empty( $arrResult ) )
    {
      $arrResult = $this->renderMapRoutePathMarkerCatRelations();
      $rowsRelation = $arrResult[ 'rowsRelation' ];
      $tableCat = $arrResult[ 'tableCat' ];
      $tableMarker = $arrResult[ 'tableMarker' ];
      $tablePath = $arrResult[ 'tablePath' ];
    }
    //$this->pObj->dev_var_dump( $pathUid, $rowsRelation, $tablePath, $tableMarker, $tableCat );
    // Get relations path -> marker -> marker_cat
    // Get the array with categories and marker
    $arrResult = $this->renderMapRouteArrCatAndMarker( $pathUid );
    $arrCat = $arrResult[ 'cat' ];
    // #i0009, 130610, dwildt, 1+
    $arrPathCat = $arrResult[ 'pathCat' ];
    $arrMarker = $arrResult[ 'marker' ];
//$this->pObj->dev_var_dump( $arrResult );
    unset( $arrResult );
    //$this->pObj->dev_var_dump( $arrCat, $arrMarker );
    // Get the array with categories and marker
    // LOOP relations of current path
    foreach ( $rowsRelation[ $pathUid ] as $markerUid => $catUids )
    {
      // LOOP categories
      foreach ( $catUids as $catUid )
      {
        $catTitle = $arrCat[ $catUid ] . '_' . $markerUid;
        $marker[] = $catTitle;
      }
      // LOOP categories
    }
    // LOOP relations of current path
    // LOOP path marker
    // #i0009, 130610, dwildt, 5+
    foreach ( $arrPathCat as $pathCatUid => $pathCatTitleTable )
    {
      $catTitle = $pathCatTitleTable . '_' . $pathCatUid;
      $marker[] = $catTitle;
    }
    // LOOP path marker
//$this->pObj->dev_var_dump( $marker );
    return $marker;
  }

  /**
   * renderMapRoutePathCatRelations( )  : Get relations path -> categrories
   *                                      rowsRelation array will look like:
   *                                      * 7 => array( 4, 10, 7 ), 5 => array( 10, 8 )
   *                                      * tablePath.uid = array ( tableCat.uid, tableCat.uid, tableCat.uid )
   *
   * @return	array   $arrReturn : with Elements rowsRelation, tableCat, tablePath
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRoutePathCatRelations()
  {
    $arrReturn = array();
    $rowsRelation = array();

    // Example
    // $relation[0]['PATH:tx_route_path->tx_route_path_cat->listOf.uid' => '2.8';
    //    '2.8' is a relation like
    //    tx_route_path.uid -> tx_route_path_cat.uid
    //

      // Get the PATH relations (each element with a prefix PATH - see example above)
    $relations = $this->renderMapRouteRelations( 'PATH' );
//$this->pObj->dev_var_dump( $relations );
    // Get the key of a relation
    $relationKey = key( $relations[ 0 ] );

    // Get the lables for the tables path and pathCat
    list( $prefix, $tables ) = explode( ':', $relationKey );
    unset( $prefix );
    list( $tablePath, $tableCat ) = explode( '->', $tables );
    // Get the lables for the tables path and pathCat
    // LOOP relations
    foreach ( $relations as $relation )
    {
      // LOOP relation
      foreach ( $relation as $tablePathCat )
      {
        $arrTablePathCat = explode( $this->catDevider, $tablePathCat );
        // SWITCH : children
        switch ( true )
        {
          // CASE : children
          case( count( $arrTablePathCat ) > 1 ):
            // LOOP children
            foreach ( $arrTablePathCat as $tablePathCatChildren )
            {
              list( $pathUid, $catUid ) = explode( '.', $tablePathCatChildren );
              if ( $pathUid != null )
              {
                $rowsRelation[ $pathUid ][] = $catUid;
                $rowsRelation[ $pathUid ] = array_unique( $rowsRelation[ $pathUid ] );
              }
            }
            // LOOP children
            break;
          // CASE : children
          // CASE : no children
          case( count( $arrTablePathCat ) == 1 ):
          default:
            list( $pathUid, $catUid ) = explode( '.', $tablePathCat );
            if ( $pathUid != null )
            {
              $rowsRelation[ $pathUid ][] = $catUid;
              $rowsRelation[ $pathUid ] = array_unique( $rowsRelation[ $pathUid ] );
            }
            break;
          // CASE : no children
        }
        // SWITCH : children
      }
      // LOOP relation
    }
    // LOOP relations
    // $rowsRelation will look like:
    // array(
    //  2 => array( 8 ),
    //  1 => array( 7 ),
    // )
    // array(
    //  tablePath.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    //  tablePath.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    // )
//$this->pObj->dev_var_dump( $rowsRelation );
    $arrReturn[ 'rowsRelation' ] = $rowsRelation;
    $arrReturn[ 'tableCat' ] = $tableCat;
    $arrReturn[ 'tablePath' ] = $tablePath;

    // DRS
    if ( $this->pObj->b_drs_map )
    {
      $prompt = var_export( $rowsRelation, true );
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS

    return $arrReturn;
  }

  /**
   * renderMapRoutePathMarkerCatRelations( ) : Get relations path -> marker
   *                                  rowsRelation array will look like:
   *                                  * 7 => array( 4, 10, 7 ), 5 => array( 10, 8 )
   *                                  * tablePath.uid = array ( tableCat.uid, tableCat.uid, tableCat.uid )
   *
   * @return	array   $arrReturn : with Elements rowsRelation, tableCat, tablePath
   * @version 4.5.7
   * @since   4.5.7
   *
   * @internal    #47630
   */
  private function renderMapRoutePathMarkerCatRelations()
  {
    $arrReturn = array();
    $rowsRelation = array();

    // Example
    // $relation[0]['MARKER:tx_route_path->tx_route_marker->tx_route_marker_cat->listOf.uid' => '2.3.10, ;|;2.3.9, ;|;2.4.10, ;|;2.5.10';
    //    '2.3.1' is a relation like
    //    tx_route_path.uid -> tx_route_marker.uid
    //

      // Get the MARKER relations (each element with a prefix MARKER - see example above)
    $relations = $this->renderMapRouteRelations( 'MARKER' );
    //$this->pObj->dev_var_dump( $relations );
    // Get the key of a relation
    $relationKey = key( $relations[ 0 ] );

    // Get the lables for the tables path and pathCat
    list( $prefix, $tables ) = explode( ':', $relationKey );
    unset( $prefix );
    list( $tablePath, $tableMarker, $tableCat ) = explode( '->', $tables );
    // Get the lables for the tables path and pathCat
    // LOOP relations
    foreach ( $relations as $relation )
    {
      // LOOP relation
      foreach ( $relation as $tablePathMarkerCat )
      {
        $arrTablePathMarkerCat = explode( $this->catDevider, $tablePathMarkerCat );
//        $this->pObj->dev_var_dump( $arrTablePathMarkerCat );
        // SWITCH : children
        switch ( true )
        {
          // CASE : children
          case( count( $arrTablePathMarkerCat ) > 1 ):
            // LOOP children
            foreach ( $arrTablePathMarkerCat as $tablePathMarkerCatChildren )
            {
              list( $pathUid, $markerUid, $catUid ) = explode( '.', $tablePathMarkerCatChildren );
              $rowsRelation[ $pathUid ][ $markerUid ][] = $catUid;
              //$rowsRelation[ $pathUid ][ $markerUid ]     = array_unique( $rowsRelation[ $pathUid ][ $markerUid ] );
            }
            // LOOP children
            break;
          // CASE : children
          // CASE : no children
          case( count( $arrTablePathMarkerCat ) == 1 ):
          default:
            list( $pathUid, $markerUid, $catUid ) = explode( '.', $tablePathMarkerCat );
            $rowsRelation[ $pathUid ][ $markerUid ][] = $catUid;
            //$rowsRelation[ $pathUid ][ $markerUid ]     = array_unique( $rowsRelation[ $pathUid ][ $markerUid ] );
            break;
          // CASE : no children
        }
        // SWITCH : children
      }
      // LOOP relation
    }
    // LOOP relations
    // $rowsRelation will look like:
    // array(
    //  2 => array( 8 ),
    //  1 => array( 7 ),
    // )
    // array(
    //  tablePath.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    //  tablePath.uid => array ( tableCat.uid, tableCat.uid, tableCat.uid ),
    // )
    //$this->pObj->dev_var_dump( $rowsRelation, $tablePath, $tableCat );
    $arrReturn[ 'rowsRelation' ] = $rowsRelation;
    $arrReturn[ 'tableCat' ] = $tableCat;
    $arrReturn[ 'tableMarker' ] = $tableMarker;
    $arrReturn[ 'tablePath' ] = $tablePath;
    return $arrReturn;
  }

  /*   * *********************************************
   *
   * Rows
   *
   * ******************************************** */

  /**
   * rowsBackup( ):
   *
   * @return	void
   * @version 4.5.7
   * @since   4.5.7
   */
  public function rowsBackup()
  {
    $this->rowsBackup = $this->pObj->rows;
  }

  /**
   * rowsReset( ):
   *
   * @return	void
   * @version 4.5.7
   * @since   4.5.7
   */
  public function rowsReset()
  {
    $this->pObj->rows = $this->rowsBackup;
    $this->rowsBackup = null;
  }

  /*   * *********************************************
   *
   * Set Page Type
   *
   * ******************************************** */

  /**
   * set_typeNum( ):
   *
   * @return	void
   * @version 3.9.8
   * @since   3.9.8
   */
  public function set_typeNum()
  {
    // init the map
    $this->initVarTypeNum();
  }

  /**
   * zz_properFormLabel( ) :
   *
   * @param       string    $label  :
   * @return	string    $label  :
   * @version 4.5.8
   * @since   4.5.8
   */
  private function zz_properFormLabel( $label )
  {
    // $label = str_replace( ' ', '_', $label );
    $label = urlencode( $label );
    //$label = str_replace( '%', 'prcnt', $label );
    return $label;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_map.php' ] )
{
  include_once ($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_map.php' ]);
}
?>
