<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* The class tx_browser_pi1_map bundles methods for rendering and processing calender based content, filters and category menues
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage  browser
*
* @version 4.5.6
* @since 3.9.6
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   96: class tx_browser_pi1_map
 *  137:     function __construct($pObj)
 *
 *              SECTION: Main
 *  166:     public function get_map( $template )
 *
 *              SECTION: Init
 *  256:     private function init(  )
 *  444:     private function initCatDevider( )
 *  465:     private function initMainMarker( $template )
 *
 *              SECTION: Categories
 *  560:     private function categoriesFormInputs( )
 *  633:     private function categoriesGet( )
 *  767:     private function categoriesMoreThanOne( )
 *
 *              SECTION: cObject
 *  821:     private function cObjDataAddArray( $keyValue )
 *  858:     private function cObjDataAddMarker( )
 *  902:     private function cObjDataAddRow( $row )
 *  937:     private function cObjDataRemoveArray( $keyValue )
 *  954:     private function cObjDataRemoveMarker( )
 *  977:     private function cObjDataRemoveRow( $row )
 *
 *              SECTION: Map rendering
 * 1011:     private function renderMap( $template )
 *
 *              SECTION: Map center and zoom automatically
 * 1048:     private function renderMapAutoCenterCoor( $map_template, $coordinates )
 * 1146:     private function renderMapAutoCenterCoorVers12( $objLibMap )
 * 1165:     private function renderMapAutoCenterCoorVers13( $objLibMap )
 * 1185:     private function renderMapAutoZoomLevel( $map_template, $longitudes, $latitudes )
 *
 *              SECTION: Map HTML template
 * 1313:     private function renderMapGetTemplate( $template )
 *
 *              SECTION: Map rendering data
 * 1414:     private function renderMapMarker( $template, $mapTemplate )
 * 1453:     private function renderMapMarkerCategoryIcons( )
 * 1540:     private function renderMapMarkerPoints( )
 * 1796:     private function renderMapMarkerPointsToJSON( $mapMarkers )
 * 1895:     private function renderMapMarkerSnippetsHtml( $map_template, $tsProperty )
 * 1948:     private function renderMapMarkerSnippetsHtmlCategories( $map_template )
 * 1981:     private function renderMapMarkerSnippetsHtmlDynamic( $map_template )
 * 1999:     private function renderMapMarkerSnippetsJssDynamic( $map_template )
 * 2050:     private function renderMapMarkerVariablesDynamic( $map_template )
 * 2101:     private function renderMapMarkerVariablesSystem( $map_template )
 * 2140:     private function renderMapMarkerVariablesSystemItem( $item )
 * 2157:     public function set_typeNum( )
 *
 * TOTAL FUNCTIONS: 32
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_map
{
    // [OBJECT] parent object
  var $pObj = null;

    // [ARRAY] TypoScript configuration array of the current view
  var $conf_view  = null;
    // [INTEGER] Id of the single view
  var $singlePid  = null;


    // [BOOLEAN] Is map enabled? Will set by init( ) while runtime
  public $enabled      = null;
    // [STRING] GoogleMaps, Open Street Map
  var $provider     = null;
    // [ARRAY] TypoScript configuration array. Will set by init( ) while runtime
  var $confMap      = null;
    // [Integer] Number of the current typeNum
  var $int_typeNum  = null;
    // [String] Name of the current typeNum
  var $str_typeNum  = null;
    // [ARRAY] Contains the categories of the current records
  var $arrCategories = null;
    // [BOOLEAN] true, if there are more than one category
  var $boolMoreThanOneCategory = null;
    // [STRING] Devider of categories. Example: ', ;|;'
  var $catDevider = null;








  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Main
  *
  **********************************************/



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
      // init the map
    $this->init( );



      ///////////////////////////////////////////////////////////////
      //
      // RETURN: map isn't enabled

      // #47632, 130508, dwildt, 9-
//    if( ! $this->enabled )
//    {
//      if( $this->pObj->b_drs_map )
//      {
//        $prompt = 'RETURN. Map is disabled.';
//        t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
//      }
//      return $template;
//    }
      // #47632, 130508, dwildt, 15+
    switch( true )
    {
      case( empty( $this->enabled ) ):
      case( $this->enabled == 'disabled'):
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'RETURN. Map is disabled.';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        return $template;
        break;
      default:
          // Follow the workflow
        break;
    }
      // RETURN: map isn't enabled


      // DRS
    if( $this->pObj->b_drs_warn )
    {
      $prompt = 'The map module uses a JSON array. If you get any unexpected result, ' .
                'please remove config.xhtml_cleaning and/or page.config.xhtml_cleaning ' .
                'in your TypoScript configuration of the current page.';
      t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 2);
      $prompt = 'The map module causes some conflicts with AJAX. PLease disable AJAX in the ' .
                'plugin/flecform of the browser.';
      t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 2);
    }
      // DRS




      // set the map marker (in case template is without the marker)
    $template = $this->initMainMarker( $template );

      // render the map
    $template = $this->renderMap( $template );

//var_dump( $template );
      // RETURN the template
    return $template;
  }









  /***********************************************
  *
  * Init
  *
  **********************************************/



  /**
 * init(): The method sets the globals $enabled and $confMap
 *
 * @return	void
 * @version 4.5.6
 * @since   3.9.6
 */
  private function init(  )
  {
      ///////////////////////////////////////////////////////////////////////////////
      //
      // RETURN: $enabled isn't null

    if( ! ( $this->enabled === null ) )
    {
      if( $this->pObj->b_drs_map )
      {
          // #47632, 130508, dwildt, 1-
        //switch( $this->enabled )
          // #47632, 130508, dwildt, 1+
        switch( true )
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
        t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
      }
      return;
    }
      // RETURN: $enabled isn't null



      /////////////////////////////////////////////////////////////////
      //
      // Get TypoScript configuration for the current view

    $conf             = $this->pObj->conf;
    $mode             = $this->pObj->piVar_mode;
    $view             = $this->pObj->view;
    $viewWiDot        = $view.'.';
    $this->conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
      // Get TypoScript configuration for the current view



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $confMapLocal

    switch( true )
    {
      case( isset( $conf['views.'][$viewWiDot][$mode.'.']['navigation.']['map.'] ) ):
          // local configuration
        $this->confMap = $conf['views.'][$viewWiDot][$mode.'.']['navigation.']['map.'];
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'Local configuration in: views.' . $viewWiDot . $mode . '.navigation.map';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
          // local configuration
      default:
          // global configuration
        $this->confMap = $this->pObj->conf['navigation.']['map.'];
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'Global configuration in: navigation.map';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
          // global configuration
    }
      // Set the global $confMapLocal



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $enabled

//    $cObj_name      = $this->confMap['enabled'];
//    $cObj_conf      = $this->confMap['enabled.'];
//    $this->enabled  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $this->enabled = $this->confMap['enabled'];
        // #47632, 130508, dwildt, 13+
      switch( true )
      {
        case( empty( $this->enabled ) ):
        case( $this->enabled == 1 ):
        case( $this->enabled == 'disabled'):
        case( $this->enabled == 'Map'):
        case( $this->enabled == 'Map +Routes'):
            // Follow the workflow
          break;
        default:
          $prompt = 'Unexpeted value in ' . __METHOD__ . ' (line ' . __LINE__ . '): ' .
                    'TypoScript property map.enabled is "' . $this->enabled . '".';
          die( $prompt );
      }
        // #47632, 130508, dwildt, 13+
      // Set the global $enabled



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $provider

      // ##42788, 121224, dwildt, +
    $this->provider = $this->confMap['provider'];
    switch( true )
    {
      case( $this->provider == 'GoogleMaps' ):
        break;
      case( $this->provider == 'Open Street Map' ):
        break;
      default:
        $prompt = 'Unexpeted value in ' . __METHOD__ . ' (line ' . __LINE__ . '): ' .
                  'TypoScript property map.provider is "' . $this->provider . '".';
        die( $prompt );
    }
      // Set the global $enabled



      // Get the typeNum from the current URL parameters
    $typeNum = (int) t3lib_div::_GP( 'type' );

      // Check the proper typeNum
    $conf = $this->pObj->conf;
    switch (true)
    {
      case( $typeNum == $conf['export.']['map.']['page.']['typeNum'] ) :
          // Given typeNum is the internal typeNum for CSV export
        $this->int_typeNum = $typeNum;
        $this->str_typeNum = 'map';
        break;
      default :
          // Given typeNum isn't the internal typeNum for CSV export
        $this->str_typeNum = 'undefined';
    }
      // Check the proper typeNum

      // Init the devider for the categories
    $this->initCatDevider( );

      ///////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_map )
    {
      switch( $this->enabled )
      {
          // #47632, 130508, dwildt
        case( 1 ):
        case( 'Map' ):
          $prompt = 'Map is enabled.';
          break;
        case( 'Map +Routes' ):
          $prompt = 'Map +Routes is enabled.';
          break;
        default:
          $prompt = 'Map is disabled.';
          break;
      }
      t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System

    return;
  }



  /**
 * initCatDevider( ): Init the class var $this->catDevider - the category devider.
 *
 * @return	void
 * @version 4.1.4
 * @since   4.1.4
 */
  private function initCatDevider( )
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

    $conf             = $this->pObj->conf;
    $mode             = $this->pObj->piVar_mode;
    $view             = $this->pObj->view;
    $viewWiDot        = $view.'.';
    $this->conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];
    $this->singlePid  = $this->pObj->objZz->get_singlePid_for_listview( );
      // Get TypoScript configuration for the current view



      /////////////////////////////////////////////////////////////////
      //
      // RETURN: template contains the map marker

//$pos = strpos('87.177.75.198', t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if( ! ( $pos === false ) )
//{
//  var_dump(__METHOD__ . ' (' . __LINE__ . ')', $template );
//}
    $pos = strpos( $template, $str_mapMarker );
    if( ! ( $pos === false ) )
    {
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'The HTML template contains the marker ' . $str_mapMarker . '.';
        t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
      }
      return $template;
    }
      // RETURN: template contains the map marker



      /////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_map )
    {
      $prompt_01 = 'The HTML template doesn\'t contain any marker ' . $str_mapMarker . '.';
      $prompt_02 = 'Marker ' . $str_mapMarker . ' will added before the last div-tag automatically.';
      $prompt_03 = 'But it would be better, you add the marker ' . $str_mapMarker . ' to your HTML template manually.';
      t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt_01 , $this->pObj->extKey, 2);
      t3lib_div :: devLog('[OK/BROWSERMAPS] '   . $prompt_02 , $this->pObj->extKey, -1);
      t3lib_div :: devLog('[HELP/BROWSERMAPS] ' . $prompt_03 , $this->pObj->extKey, 1);
    }
      // DRS - Development Reporting System



      /////////////////////////////////////////////////////////////////
      //
      // Set marker before the last div-tag

    $arr_divs     = explode( '</div>', $template );
    $pos_lastDiv  = count( $arr_divs ) - 2;

    $arr_divs[$pos_lastDiv] = $arr_divs[$pos_lastDiv] . $str_mapMarker . PHP_EOL . '      ';

    $template     = implode( '</div>', $arr_divs );

    return $template;
  }






  /***********************************************
  *
  * Categories
  *
  **********************************************/



  /**
 * categoriesFormInputs( ): Returns the input fields for the category form
 *
 * @return	string
 * @version 4.1.17
 * @since   4.1.4
 */
  private function categoriesFormInputs( )
  {
      // Get the field name of the field with the category icon
    $catIconsField = $this->confMap['configuration.']['categories.']['fields.']['categoryIcon'];

      // Default space in HTML code
    $tab = '                    ';

      // FOREACH category label
    foreach( $this->arrCategories['labels'] as $labelKey => $labelValue )
    {
        // Get the draft for an input field
      $cObj_name = $this->confMap['configuration.']['categories.']['form_input'];
      $cObj_conf = $this->confMap['configuration.']['categories.']['form_input.'];
      $input     = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // replace the category marker
      $input = str_replace( '###CAT###', $labelValue, $input );
        // 4.1.17, 120927, dwildt
        // replace the category marker
      $labelValueWoSpc = str_replace( ' ', null, $labelValue );
      $input = str_replace( '###CAT_WO_SPC###', $labelValueWoSpc, $input );
        // 4.1.17, 120927, dwildt

        // IF draft for an input field contains ###IMG###, render an image
      $pos = strpos( $input, '###IMG###' );
      if( ! ( $pos === false ) )
      {
          // SWITCH : Render the image
        switch( true )
        {
          case( is_array( $this->arrCategories[ 'icons' ] ) ):
              // 4.1.7, dwildt, +
            $this->cObjDataAddArray( array( $catIconsField => $this->arrCategories[ 'icons' ][ $labelKey ] ) );
//$this->pObj->dev_var_dump( $catIconsField, $this->pObj->cObj->data[ $catIconsField ] );
            $img = $this->renderMapMarkerVariablesSystemItem( 'categoryIconLegend' );
            $this->cObjDataRemoveArray( array( $catIconsField => $this->arrCategories[ 'icons' ][ $labelKey ] ) );
              // 4.1.7, dwildt, +
            break;
          default:
              // Render the image
            $cObj_name = $this->confMap['configuration.']['categories.']['colours.']['legend.'][$labelKey];
            $cObj_conf = $this->confMap['configuration.']['categories.']['colours.']['legend.'][$labelKey . '.'];
            $img       = $this->pObj->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//var_dump( __METHOD__, __LINE__, $labelKey, $cObj_name, $cObj_conf, $img );
            break;
        }
          // SWITCH : Render the image

        $input = str_replace( '###IMG###', $img, $input );
      }
        // IF draft for an input field contains ###IMG###, render an image

      $arrInputs[ ] = $tab . $input;
    }
      // FOREACH category label

      // Move array of input fields to a string
    $inputs = implode( PHP_EOL , $arrInputs );
    $inputs = trim ( $inputs );

      // RETURN input fields
    return $inputs;
  }



  /**
 * categoriesGet( ): Get the category labels from the current rows. And set it in $this->arrCategories.
 *
 * @return	array		$this->arrCategories
 * @version 4.1.7
 * @since   4.1.4
 */
  private function categoriesGet( )
  {
      // RETURN : method is called twice at least
    if( $this->arrCategories != null )
    {
      return $this->arrCategories;
    }
      // RETURN : method is called twice at least

      // Local array for category labels
    $catLabels = null;
      // Local array for category icons
    $catIcons = null;

      // Get the field name of the field with the category label
    $fieldForLabel = $this->confMap['configuration.']['categories.']['fields.']['category'];
      // Get the field name of the field with the category icon
    $fieldForIcon = $this->confMap['configuration.']['categories.']['fields.']['categoryIcon'];

      // Get categories from the rows
    $categoryLabels = array( );
      // FOREACH row
if( $this->pObj->b_drs_todo )
{
  $prompt = 'TODO: Lokalisation of the map form labels.';
  t3lib_div :: devLog( '[TODO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
// #46062, 130306, dwildt: TODO: Lokalisierung der Labels:
// Wenn Standortdatensatz uebersetzt ist, sind die Kategorie-Labels leer
}
    foreach( $this->pObj->rows as $row )
    {
        // RETURN : field for category label is missing
      if( ! isset( $row[ $fieldForLabel ] ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'current rows doesn\'t contain the field "' . $fieldForLabel . '"';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 2 );
        }
        $this->arrCategories = array( );
        return $this->arrCategories;
      }
        // RETURN : field for category label is missing
        // 4.1.7, dwildt, 1-
      //$categoryLabels =  array_merge( $categoryLabels, explode( $this->catDevider, $row[ $fieldForLabel ] ) );
        // 4.1.7, dwildt, 10+
      $catLabelsOfCurrRow = explode( $this->catDevider, $row[ $fieldForLabel ] );
      foreach( $catLabelsOfCurrRow as $labelKey => $labelValue )
      {
        $categoryLabels[] = $labelValue;
        if( isset( $row[ $fieldForIcon ] ) )
        {
          $catIconsOfCurrRow            = explode( $this->catDevider, $row[ $fieldForIcon ] );
          $categoryIcons[ $labelValue ] = $catIconsOfCurrRow[ $labelKey ];
        }
      }
        // 4.1.7, dwildt, 10+
    }
      // FOREACH row
      // Get categories from the rows

      // Remove non unique category labels
    $categoryLabels = array_unique( $categoryLabels );

      // Order the category labels
    $orderBy = $this->confMap['configuration.']['categories.']['orderBy'];
    switch( $orderBy )
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
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'configuration.categories.orderBy has an unproper value: "' . $orderBy . '"';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
          $prompt = 'categories will ordered by SORT_REGULAR!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 2 );
        }
        sort( $categoryLabels, SORT_REGULAR );
        break;
    }
      // Order the category labels

      // Set the keys: keys should correspondend with keys of the item colours
    $maxItem = count( $categoryLabels );
    $counter = 0;
    foreach( array_keys( $this->confMap['configuration.']['categories.']['colours.']['points.'] ) as $catKey )
    {
      if( substr( $catKey, -1 ) == '.' )
      {
        continue;
      }
      $catLabels[ $catKey ] = $categoryLabels[ $counter ];
      if( isset( $row[ $fieldForIcon ] ) )
      {
        $catIcons[ $catKey ]  = $categoryIcons[ $categoryLabels[ $counter ] ];
      }
      $counter++;
      if( $counter >= $maxItem )
      {
        break;
      }
    }
      // Set the keys: keys should correspondend with keys of the item colours

    $this->arrCategories['labels']  = $catLabels;
    if( isset( $row[ $fieldForIcon ] ) )
    {
      $this->arrCategories['icons']   = $catIcons;
    }
//$this->pObj->dev_var_dump( $this->arrCategories );
    return $this->arrCategories;
  }



  /**
 * categoriesMoreThanOne( ) : Set the class var $this->boolMoreThanOneCategory. It will be true, if there
 *                          are two categories at least
 *
 * @return	boolean		$this->boolMoreThanOneCategory: true, if there are two categories at least
 * @version 4.1.7
 * @since   4.1.4
 */
  private function categoriesMoreThanOne( )
  {

      // RETURN : method is called twice at least
    if( $this->boolMoreThanOneCategory != null )
    {
      return $this->boolMoreThanOneCategory;
    }
      // RETURN : method is called twice at least

    $categories = $this->categoriesGet( );

    if( count ( $categories['labels'] ) > 1 )
    {
      $this->boolMoreThanOneCategory = true;
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'There is more than one category.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
    }
    else
    {
      $this->boolMoreThanOneCategory = false;
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'There isn\'t more than one category.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
    }
    return $this->boolMoreThanOneCategory;
  }






  /***********************************************
  *
  * cObject
  *
  **********************************************/



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
    foreach( $keyValue as $key => $value )
    {
      if( empty( $key ) )
      {
        continue;
      }

      if( $this->pObj->b_drs_map )
      {
        if( empty ( $value ) )
        {
          $prompt = $key . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
        }
        else
        {
          $prompt = 'Added to cObject[' . $key . ']: ' . $value;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
          $prompt = 'You can use the content in TypoScript with: field = ' . $key;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
        }
      }
      $this->pObj->cObj->data[ $key ] = $value;
    }
  }



/**
 * cObjDataAddMarker( ):
 *
 * @return	void
 * @version 4.1.25
 * @since   4.1.0
 */
  private function cObjDataAddMarker( )
  {
      // #42736, dwildt, 1-
//    foreach( array_keys( $this->confMap['marker.']['addToCData.']['system.'] ) as $marker )
      // #42736, dwildt, 1+ (Thanks to Thomas.Scholze@HS-Lausitz.de)
    foreach( ( array ) array_keys( $this->confMap['marker.']['addToCData.']['system.'] ) as $marker )
    {
      if( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $cObj_name  = $this->confMap['marker.']['addToCData.']['system.'][$marker];
      $cObj_conf  = $this->confMap['marker.']['addToCData.']['system.'][$marker . '.'];
      $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      if( $this->pObj->b_drs_map )
      {
        if( empty ( $content ) )
        {
          $prompt = 'marker.addToCData.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
        }
        else
        {
          $prompt = 'Added to cObject[' . $marker . ']: ' . $content;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
          $prompt = 'You can use the content in TypoScript with: field = ' . $marker;
          t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
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

    foreach( ( array ) $row as $key => $value )
    {
      $this->pObj->cObj->data[ $key ] = $value;
    }

    if( $first_loop )
    {
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'This fields are added to cObject: ' . implode( ', ', array_keys( $row ) );
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
        $prompt = 'I.e: you can use the content in TypoScript with: field = ' . key( $row );
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
      $first_loop = false;
    }

    $this->cObjDataAddMarker( );

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
    foreach( array_keys( $keyValue ) as $key )
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
  private function cObjDataRemoveMarker( )
  {
    foreach( array_keys( $this->confMap['marker.']['addToCData.']['system.'] ) as $marker )
    {
      if( substr( $marker, -1, 1 ) == '.' )
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
    foreach( array_keys( ( array ) $row ) as $key )
    {
      unset( $this->pObj->cObj->data[ $key ] );
    }

    $this->cObjDataRemoveMarker( );
  }









  /***********************************************
  *
  * Map rendering
  *
  **********************************************/



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
    $arr_result   = $this->renderMapGetTemplate( $template );
    $mapTemplate  = $arr_result['template'];
    if( $arr_result['error'] )
    {
      return $mapTemplate;
    }
      // RETURN : HTML template is not proper

    $template = $this->renderMapMarker( $template, $mapTemplate );

//var_dump( __METHOD__ . ' (' . __LINE__ . '): ', $mapTemplate, $template );
      // RETURN the template
    return $template;
  }



  /***********************************************
  *
  * Map center and zoom automatically
  *
  **********************************************/



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
      // Get the mode
    $mode = $this->confMap['configuration.']['centerCoordinates.']['mode'];

      // SWITCH mode
    switch( $mode )
    {
      case( 'auto' ):
      case( 'ts' ):
          // Follow the workflow
        break;
      default:
          // DRS
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'configuration.centerCoordinates.mode is undefined: ' . $mode . '. But is has to be auto or ts!';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
        }
          // DRS
          // RETURN: there is an error!
        return $map_template;
        break;
    }
      // SWITCH mode

      // RETURN: center coordinates should not calculated
    if( $mode == 'ts' )
    {
        // DRS
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'configuration.centerCoordinates.mode is: ' . $mode . '. Coordinates won\'t calculated.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
        // DRS
      return $map_template;
    }
      // RETURN: center coordinates should not calculated

      // Require map library
    require_once( PATH_typo3conf . 'ext/browser/lib/class.tx_browser_map.php');
      // Create object
    $objLibMap = new tx_browser_map( );

      // Get sum of coordinates
    $sumCoor = count( $coordinates );
    $curCoor = $sumCoor;
      // FOR all coordinates
    for( $sumCoor; $curCoor--; )
    {
      $objLibMap->fillBoundList( explode( ',' , $coordinates[ $curCoor ] ), $curCoor );
    }
      // FOR all coordinates

      // #47632
    $version = $this->confMap['enabled.']['version'];
    switch( true )
    {
      case( $version == '1.2' ) :
        $centerCoor = $this->renderMapAutoCenterCoorVers12( $objLibMap );
        break;
      case( $version == '1.3' ) :
      default       :
        $centerCoor = $this->renderMapAutoCenterCoorVers13( $objLibMap );
        break;
    }

      // DRS
    if( $this->pObj->b_drs_map )
    {
      $prompt = 'configuration.centerCoordinates.mode is: ' . $mode . '. Calculated coordinates are ' . $centerCoor;
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
    }
      // DRS

      // Get the marker
    $marker     = $this->confMap['configuration.']['centerCoordinates.']['dynamicMarker'];
    $marker     = "'###" . strtoupper( $marker ). "###'";
      // Get the marker

      // Set center coordinates
    $map_template = str_replace( $marker, $centerCoor, $map_template );

      // RETURN the handled template
    return $map_template;
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
    $centerCoor = implode( ',', $objLibMap->centerCoor( ) );
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
    list( $lon, $lat ) = $objLibMap->centerCoor( );
    $centerCoor = '{ lon : ' . $lon . ', lat : ' . $lat . ' }';

    return $centerCoor;
  }



  /**
 * renderMapAutoZoomLevel( ):
 *
 * @param	string		$map_template: ...
 * @param	[type]		$longitudes: ...
 * @param	[type]		$latitudes: ...
 * @return	string
 * @version 4.1.0
 * @since   4.1.0
 */
  private function renderMapAutoZoomLevel( $map_template, $longitudes, $latitudes )
  {
      // Get the mode
    $mode = $this->confMap['configuration.']['zoomLevel.']['mode'];

      // SWITCH mode
    switch( $mode )
    {
      case( 'auto' ):
      case( 'fixed' ):
          // Follow the workflow
        break;
      default:
          // DRS
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'configuration.zoomLevel.mode is undefined: ' . $mode . '. But is has to be auto or ts!';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
        }
          // DRS
          // RETURN: there is an error!
        return $map_template;
        break;
    }
      // SWITCH mode

      // RETURN: center coordinates should not calculated
    if( $mode == 'fixed' )
    {
        // DRS
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'configuration.zoomLevel.mode is: ' . $mode . '. Zoom level won\'t calculated.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
        // DRS
      return $map_template;
    }
      // RETURN: center coordinates should not calculated

      // Calculate the zoom level
      // Get max distance longitude (longitudes are from -90° to 90°). 0° is the equator
    $distances[]  = ( max( $longitudes ) - min( $longitudes ) ) * 2;
      // Get max distance latitude (latidudes are from -180° to 180°). 0° is Greenwich
    $distances[]  = max( $latitudes ) - min( $latitudes );
      // Get max distance
    $maxDistance  = max( $distances );
      // Get the maximum zoom level
    $maxZoomLevel = $this->confMap['configuration.']['zoomLevel.']['max'];
    switch( true )
    {
      case( empty ( $longitudes ) ):
      case( empty ( $latitudes ) ):
          // No map markers
        $zoomLevel = 1;
        break;
      case( $maxDistance == 0 ):
          // One map marker
        $zoomLevel = $maxZoomLevel;
        break;
      default:
          // Get the quotient. Example: 360 / 5.625 = 64
        $quotient  = 360 / $maxDistance;
          // Example: ( int ) log( 64 ) / log( 2 ) = 6
        $zoomLevel = ( int ) ( log( $quotient ) / log( 2 ) );
        break;
    }
//var_dump( __METHOD__, __LINE__, $longitudes, max( $longitudes ), min( $longitudes ),
//         $latitudes, max( $latitudes ), min( $latitudes ), $distances, $maxDistance, $quotient, $zoomLevel );
//var_dump( __METHOD__, __LINE__, $zoomLevel );
      // Calculate the zoom level

    switch( true )
    {
      case( $zoomLevel < 6 ):
        $zoomLevel = $zoomLevel + 1;
        break;
      case( $zoomLevel < 12 ):
        $zoomLevel = $zoomLevel + 2;
        break;
      case( $zoomLevel < 18 ):
        $zoomLevel = $zoomLevel + 3;
        break;
    }

    if( $zoomLevel > $maxZoomLevel )
    {
      $zoomLevel = $maxZoomLevel;
    }

      // DRS
    if( $this->pObj->b_drs_map )
    {
      $prompt = 'configuration.zoomLevel.mode is: ' . $mode . '. Calculated zoom level is ' . $zoomLevel;
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
    }
      // DRS

      // Get the marker
    $marker     = $this->confMap['configuration.']['zoomLevel.']['dynamicMarker'];
    $marker     = "'###" . strtoupper( $marker ). "###'";
      // Get the marker

      // Set center coordinates
    $map_template = str_replace( $marker, $zoomLevel, $map_template );

      // RETURN the handled template
    return $map_template;
  }



  /***********************************************
  *
  * Map HTML template
  *
  **********************************************/



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
    $arr_return           = array( );
    $arr_return['error']  = false;

      // map hash key
    $mapHashKey = '###MAP###';

      // Get the template
    $mapTemplate = $this->pObj->cObj->fileResource( $this->confMap['template.']['file'] );

      // RETURN : no template file
    if( empty( $mapTemplate ) )
    {
        // DRS - Development Reporting System
      if ($this->b_drs_error)
      {
        $prompt = 'There is no template file. Path: navigation.map.template.file.';
        t3lib_div::devLog( '[ERROR/DRS] ' . $prompt, $this->extKey, 3 );
        $prompt = 'ABORTED';
        t3lib_div::devLog( '[ERROR/DRS] ' . $prompt, $this->extKey, 0 );
      }
        // DRS - Development Reporting System
        // Error message
      $str_map  = '<h1 style="color:red;">' .
                    $this->pObj->pi_getLL( 'error_readlog_h1' ) .
                  '</h1>
                  <p style="color:red;font-weight:bold;">' .
                    $this->pObj->pi_getLL( 'error_template_map_no' ) .
                  '</p>';
        // Error message
        // Replace the map marker in the template of the parent object
      $template = str_replace( $mapHashKey, $str_map, $template );
        // RETURN the template
      $arr_return['error']    = true;
      $arr_return['template'] = $template;
      return $arr_return;
    }
      // RETURN : no template file

      // Get the subpart
    $str_marker   = '###TEMPLATE_MAP###';
    $mapTemplate  = $this->pObj->cObj->getSubpart( $mapTemplate, $str_marker);
      // Get the subpart

      // RETURN: no subpart marker
    if( empty( $mapTemplate ) )
    {
        // DRS - Development Reporting System
      if ($this->b_drs_error)
      {
        $prompt = 'Template doesn\'t contain the subpart ###TEMPLATE_MAP###.';
        t3lib_div::devLog('[ERROR/DRS] ' . $prompt, $this->extKey, 3);
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 0);
      }
        // DRS - Development Reporting System
        // Error message
      $str_map  = '<h1 style="color:red;">' .
                    $this->pObj->pi_getLL('error_readlog_h1') .
                  '</h1>
                  <p style="color:red;font-weight:bold;">' .
                    $this->pObj->pi_getLL('error_template_map_no_subpart') .
                  '</p>';
        // Error message
        // Replace the map marker in the template of the parent object
      $template = str_replace( $mapHashKey, $str_map, $template );
        // RETURN the template
      $arr_return['error']    = true;
      $arr_return['template'] = $template;
      return $arr_return;
    }
      // RETURN: no subpart marker

      // RETURN : the template
    $arr_return['template'] = $mapTemplate;
    return $arr_return;
  }









  /***********************************************
  *
  * Map rendering data
  *
  **********************************************/

/**
 * renderMap( ): Render the Map
 *
 * @param	string		$template     : current HTML template of the parent object
 * @param	string		$mapTemplate  : the map
 * @return	string		$template     : current HTML template with the rendered map
 * @version 4.5.6
 * @since   3.9.6
 */
  private function renderMapMarker( $template, $mapTemplate )
  {
      // Substitute marker HTML
    $markerArray  = $this->renderMapMarkerSnippetsHtmlCategories( $mapTemplate )
                  + $this->renderMapMarkerSnippetsHtmlDynamic( $mapTemplate );
    $mapTemplate  = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
      // Substitute marker HTML

      // Add data
    $mapTemplate  = $this->renderMapMarkerVariablesSystem( $mapTemplate );
    $markerArray  = $this->renderMapMarkerVariablesDynamic( $mapTemplate );
    $mapTemplate  = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
      // Add data

      // Substitute marker JSS
    $markerArray  = $markerArray
                  + $this->renderMapMarkerSnippetsJssDynamic( $mapTemplate );
    $mapTemplate  = $this->pObj->cObj->substituteMarkerArray( $mapTemplate, $markerArray );
      // Substitute marker JSS

      // map marker
    $mapHashKey = '###MAP###';
      // Replace the map marker in the template of the parent object
    $template   = str_replace( $mapHashKey, $mapTemplate, $template );

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
  private function renderMapMarkerCategoryIcons( )
  {
    $catIcons = null;
    $arrIcon  = array( );

    foreach( array_keys( $this->confMap['configuration.']['categories.']['colours.']['points.'] ) as $catKey )
    {
      if( substr( $catKey, -1 ) == '.' )
      {
        continue;
      }

      unset( $arrIcon );

        // Set the path
      $coa_name = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['pathToIcon'];
      $coa_conf = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['pathToIcon.'];
      $value    = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
      if( empty ( $value ) )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
        // absolute path
      $pathAbsolute = t3lib_div::getFileAbsFileName( $value );
      if( ! file_exists( $pathAbsolute ) )
      {
        die( 'File doesn\'t exist: ' . $pathAbsolute . ' at ' . __METHOD__ . ' (line ' . __LINE__ . ')' );
      }
        // relative path
      $pathRelative = preg_replace('%' . PATH_site . '%', '', $pathAbsolute );
      $arrIcon[] = $pathRelative;
        // Set the path

        // Add the icon width
      $value = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['width'];
      if( empty( $value ) )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon width

        // Add the icon height
      $value = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['height'];
      if( empty( $value ) )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon height

        // Add the icon x-offset
      $value = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['offsetX'];
      if( $value == null )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon x-offset

        // Add the icon y-offset
      $value = $this->confMap['configuration.']['categories.']['colours.']['points.'][$catKey . '.']['offsetY'];
      if( $value == null )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon y-offset

//      $catIcons[$catKey] = '[' . implode( ', ', $arrIcon ) . ']';
      $catIcons[$catKey] = $arrIcon;

    }

//var_dump( __METHOD__, __LINE__, $catIcons );
    return $catIcons;
  }



  /**
 * renderMapMarkerPoints( ): Points are map markers.
 *
 * @return	array
 * @version 4.1.13
 * @since   4.1.7
 */
  private function renderMapMarkerPoints( )
  {
    $arr_return   = array( );
    $lons         = array( );
    $lats         = array( );
    $dontHandle00 = $this->confMap['configuration.']['00Coordinates.']['dontHandle'];

      // #44849, dwildt, 1+
    $llNoCat      = $this->pObj->pi_getLL('phrase_noMapCat');


      // #44849, dwildt, 1-
//    if( $this->boolMoreThanOneCategory )
      // #44849, dwildt, 1+
    if( $this->boolMoreThanOneCategory || 1 )
    {
      $arrCategoriesFlipped = array_flip( $this->arrCategories['labels'] );
    }
    else
    {
      $keys = array_keys( $this->confMap['configuration.']['categories.']['colours.']['points.'] );
      $arrCategoriesFlipped = array( $llNoCat => $keys[ 0 ] );
    }


      // FOREACH row
    $mapMarkers = null;
    $catField         = $this->confMap['configuration.']['categories.']['fields.']['category'];
    $catIconsField    = $this->confMap['configuration.']['categories.']['fields.']['categoryIcon'];
      // #42125, 121031, dwildt, 2+
    $catOffsetXField  = $this->confMap['configuration.']['categories.']['fields.']['categoryOffsetX'];
    $catOffsetYField  = $this->confMap['configuration.']['categories.']['fields.']['categoryOffsetY'];
    foreach( $this->pObj->rows as $row )
    {
        // IF there are more than one category
        // #44849, dwildt, 1-
//      if( $this->boolMoreThanOneCategory )
        // #44849, dwildt, 1+
      if( $this->boolMoreThanOneCategory || 1 )
      {
          // Get categories
        if( isset( $row[ $catField ] ) )
        {
          $categories = explode( $this->catDevider, $row[ $catField ] );
        }
        else
        {
          $categories = array( $keys[ 0 ] => $llNoCat );
        }
          // Get categories
          // Get category icons
        if( isset( $this->arrCategories['icons'] ) )
        {
          $categoryIcons = explode( $this->catDevider, $row[ $catIconsField ] );
        }
          // Get category icons
          // Get category offsets
          // #42125, 121031, dwildt, 8+
        if( isset( $row[ $catOffsetXField ] ) )
        {
          $categoryOffsetsX = explode( $this->catDevider, $row[ $catOffsetXField ] );
        }
        if( isset( $row[ $catOffsetYField ] ) )
        {
          $categoryOffsetsY = explode( $this->catDevider, $row[ $catOffsetYField ] );
        }
          // Get category offsets
      }
        // IF there are more than one category
        // IF there is one category exactly
        // #44849, dwildt, 1-
//      if( ! $this->boolMoreThanOneCategory )
        // #44849, dwildt, 1+
      if( ! $this->boolMoreThanOneCategory && 0 )
      {
          // Set dummy category
        $categories = array( $keys[ 0 ] => $llNoCat );
          // IF there are one icon at least
        if( isset( $this->arrCategories['icons'] ) )
        {
          list( $categoryIcons[ $keys[ 0 ] ] ) = explode( $this->catDevider, $row[ $catIconsField ] );
        }
          // IF there are one icon at least
          // Get category offset
          // #42125, 121031, dwildt, 8+
        if( isset( $row[ $catOffsetXField ] ) )
        {
          list( $categoryOffsetsX[ $keys[ 0 ] ] ) = explode( $this->catDevider, $row[ $catOffsetXField ] );
        }
        if( isset( $row[ $catOffsetYField ] ) )
        {
          list( $categoryOffsetsY[ $keys[ 0 ] ] ) = explode( $this->catDevider, $row[ $catOffsetYField ] );
        }
          // Get category offset
      }
        // IF there is one category exactly

        // FOREACH category
      foreach( $categories as $key => $category )
      {
          // Add the current row to cObj->data
        $this->cObjDataAddRow( $row );

        $this->cObjDataAddMarker( );
        if( isset( $this->arrCategories['icons'] ) )
        {
          $this->cObjDataAddArray( array( $catIconsField => $categoryIcons[ $key ] ) );
        }

          // #42566, 121031, dwildt
        $this->cObjDataRemoveArray( array( $catField ) );
        $catValue = implode( $this->pObj->objTyposcript->str_sqlDeviderDisplay, $categories );
        $this->cObjDataAddArray( array( $catField => $catValue ) );


          // Add x offset and y offset to current cObject
          // #42125, 121031, dwildt, 2+
        $this->cObjDataAddArray( array( $catOffsetXField => $categoryOffsetsX[ $key ] ) );
        $this->cObjDataAddArray( array( $catOffsetYField => $categoryOffsetsY[ $key ] ) );
          // Add x offset and y offset to current cObject

          // Get the longitude
        $mapMarker['lon'] = $this->renderMapMarkerVariablesSystemItem( 'longitude' );
          // Get the latitude
        $mapMarker['lat'] = $this->renderMapMarkerVariablesSystemItem( 'latitude' );

          // SWITCH logitude and latitude
        switch( true )
        {
          case( $mapMarker['lon'] . $mapMarker['lat'] == '' ):
              // CONTINUE: longituda and latitude are empty
            continue 3;
            break;
          case( $dontHandle00 && $mapMarker['lon'] == 0 && $mapMarker['lat'] == 0 ):
              // CONTINUE: longituda and latitude are 0 and 0,0 shouldn't handled
            continue 3;
            break;
        }
          // SWITCH logitude and latitude

          // Get the desc
        $mapMarker['desc']  = $this->renderMapMarkerVariablesSystemItem( 'description' );
        if( empty ( $mapMarker['desc'] ) )
        {
          $mapMarker['desc'] = 'Please take care of a proper configuration<br />
                                of the TypoScript property marker.mapMarker.description!';
        }
          // Get the desc

          // #41057, 120919, dwildt, +
          // Get the url
        $url  = $this->renderMapMarkerVariablesSystemItem( 'url' );
        if( ! empty ( $url ) )
        {
          $mapMarker['url'] = $url;
        }
          // Get the url
          // #41057, 120919, dwildt, +

          // #41057, 120919, dwildt, +
          // Get the number
        $number  = $this->renderMapMarkerVariablesSystemItem( 'number' );
        if( ! empty ( $number ) )
        {
          $mapMarker['number'] = $number;
        }
          // Get the number
          // #41057, 120919, dwildt, +

          // 4.1.17, 120927, dwildt, 2-
//          // Get the category label
//        $mapMarker['cat'] = $category;
          // 4.1.17, 120927, dwildt, 3+
          // Get the category label
        $categoryWoSpc    = str_replace( ' ', null, $category );
        $mapMarker['cat'] = $categoryWoSpc;
          // 4.1.7, 3+
          // Get the category icon
        if( isset( $this->arrCategories['icons'] ) )
        {
          $mapMarker['catIconMap'] = $this->renderMapMarkerVariablesSystemItem( 'categoryIconMap' );
        }
          // Get the iconKey
        $mapMarker['iconKey'] = $arrCategoriesFlipped[ $category ];

          // Add offset to the mapMarker
        $mapMarker['iconOffsetX'] = $this->renderMapMarkerVariablesSystemItem( 'categoryOffsetX' );
        $mapMarker['iconOffsetY'] = $this->renderMapMarkerVariablesSystemItem( 'categoryOffsetY' );
          // Add offset to the mapMarker

          // Save each mapMarker
        $mapMarkers[] = $mapMarker;
          // Save each longitude
        $lons[] = ( double ) $mapMarker['lon'];
          // Save each latitude
        $lats[]  = ( double ) $mapMarker['lat'];

          // Remove the current row from cObj->data
        $this->cObjDataRemoveRow( $row );
        $this->cObjDataRemoveMarker( );
        $this->cObjDataRemoveArray( array( $catIconsField => $categoryIcons[$key] ) );

      }
        // FOREACH category
    }
    unset( $dontHandle00 );
      // FOREACH row

//$this->pObj->dev_var_dump( $mapMarkers );
    if( $this->pObj->b_drs_map )
    {
      $prompt = 'JSON array: ' . var_export( $mapMarkers, true);
      t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
    }
    switch( true )
    {
      case( $mapMarkers == null ):
      case( ! is_array( $mapMarkers ) ):
      case( ( is_array( $mapMarkers ) ) && ( count( $mapMarkers ) < 1 ) ):
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'JSON array is null.';
          t3lib_div :: devLog( '[ERROR/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3 );
          $prompt = 'You will get an empty map!';
          t3lib_div :: devLog( '[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 2 );
          $prompt = 'Please check the TypoScript Constant Editor > Category [BROWSER - MAP].';
          t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 1 );
        }
        break;
      default:
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'JSON array seem\'s to be proper.';
          t3lib_div :: devLog( '[OK/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, -1 );
          $prompt = 'If you have an unexpected effect in your map, please check the JSON array from above!';
          t3lib_div :: devLog( '[HELP/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 1 );
        }
        break;
    }
    $arr_return['data']['mapMarkers'] = $mapMarkers;
//$this->pObj->dev_var_dump( $mapMarkers );
    $arr_return['data']['lats']       = $lats;
    $arr_return['data']['lons']       = $lons;
    return $arr_return;
  }



  /**
 * renderMapMarkerPointsToJSON( ):
 *
 * @param	array
 * @return	string		$jsonData
 * @version 4.1.7
 * @since   4.1.0
 */
  private function renderMapMarkerPointsToJSON( $mapMarkers )
  {
    $arr_return   = array( );
    $series       = null;
    $coordinates  = array( );

      // Category icons in case of database categories without own icons
    $catIcons = $this->renderMapMarkerCategoryIcons( );
      // Path to the root
    $rootPath = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/';

      // FOREACH map marker
    foreach( ( array ) $mapMarkers as $key => $mapMarker )
    {
        // Set category icon
      if( ! isset( $series[$mapMarker['cat']]['icon'] ) )
      {
          // Database category has its own icon
        if( isset( $mapMarker['catIconMap'] ) )
        {
          list( $width, $height ) = getimagesize( $rootPath . $mapMarker['catIconMap'] );
          $series[$mapMarker['cat']]['icon'][] = $mapMarker['catIconMap'];
          $series[$mapMarker['cat']]['icon'][] = $width;
          $series[$mapMarker['cat']]['icon'][] = $height;
            // #42125, 121031, dwildt, 2-
//          $series[$mapMarker['cat']]['icon'][] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['x'];
//          $series[$mapMarker['cat']]['icon'][] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['y'];
            // IF database has a field x-offset, take calue from database
            // #42125, 121031, dwildt, 8+
          if( isset( $mapMarker['iconOffsetX'] ) )
          {
            $series[$mapMarker['cat']]['icon'][] = ( int ) $mapMarker['iconOffsetX'];
          }
          else
          {
            $series[$mapMarker['cat']]['icon'][] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['x'];
          }
            // IF database has a field x-offset, take calue from database
            // IF database has a field y-offset, take calue from database
            // #42125, 121031, dwildt, 8+
          if( isset( $mapMarker['iconOffsetY'] ) )
          {
            $series[$mapMarker['cat']]['icon'][] = ( int ) $mapMarker['iconOffsetY'];
          }
          else
          {
            $series[$mapMarker['cat']]['icon'][] = ( int ) $this->confMap['configuration.']['categories.']['offset.']['y'];
          }
            // IF database has a field y-offset, take calue from database
        }
          // Database category has its own icon
          // Database categories without own icons
        if( ! isset( $mapMarker['catIconMap'] ) )
        {
          $series[$mapMarker['cat']]['icon'] = $catIcons[$mapMarker['iconKey']];
        }
          // Database categories without own icons
      }
        // Set category icon
        // Set coordinates
      $series[$mapMarker['cat']]['data'][$key]['coors']   = array( $mapMarker['lon'], $mapMarker['lat'] );
      $coordinates[] = $mapMarker['lon'] . ',' . $mapMarker['lat'];
        // Set coordinates
        // Set description
      $series[$mapMarker['cat']]['data'][$key]['desc']    = $mapMarker['desc'];

        // #41057, 120919, dwildt, +
        // Set url
      if( ! empty ( $mapMarker['url'] ) )
      {
        $series[$mapMarker['cat']]['data'][$key]['url'] = $mapMarker['url'];
      }
        // Set number
      if( ! empty ( $mapMarker['number'] ) )
      {
        $series[$mapMarker['cat']]['data'][$key]['number'] = $mapMarker['number'];
      }
        // #41057, 120919, dwildt, +
    }
      // FOREACH map marker

    $jsonData = json_encode( $series );

    $arr_return['data']['jsonData']     = $jsonData;
    $arr_return['data']['coordinates']  = $coordinates;
    return $arr_return;
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
    $markerArray = array( );

    foreach( $this->confMap['marker.']['snippets.']['html.'][$tsProperty . '.'] as $marker => $conf )
    {
      $dummy = $conf;
      if( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if( ( $pos === false ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        continue;
      }

      $cObj_name  = $this->confMap['marker.']['snippets.']['html.'][$tsProperty . '.'][$marker];
      $cObj_conf  = $this->confMap['marker.']['snippets.']['html.'][$tsProperty . '.'][$marker . '.'];
      $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      if( empty ( $content ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'marker.html.' . $tsProperty . '.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3);
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
 * @version 4.1.4
 * @since   4.1.0
 */
  private function renderMapMarkerSnippetsHtmlCategories( $map_template )
  {
    $markerArray = array( );

    if( ! $this->categoriesMoreThanOne( ) )
    {
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'There isn\'t more than one category. Any form with categories will rendered.';
        t3lib_div :: devLog( '[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0 );
      }
      return $markerArray;
    }

    $tsProperty   = 'categories';
    $markerArray  =  $this->renderMapMarkerSnippetsHtml( $map_template, $tsProperty );

    $inputs = $this->categoriesFormInputs( );
    $markerArray[ '###FILTER_FORM###' ] = str_replace('###INPUTS###', $inputs, $markerArray[ '###FILTER_FORM###' ] );

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
    $tsProperty   = 'dynamic';
    $markerArray  =  $this->renderMapMarkerSnippetsHtml( $map_template, $tsProperty );

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
    $markerArray = array( );

    foreach( $this->confMap['marker.']['snippets.']['jss.']['dynamic.'] as $marker => $conf )
    {
      $dummy = $conf;
      if( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if( ( $pos === false ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        continue;
      }

      $cObj_name  = $this->confMap['marker.']['snippets.']['jss.']['dynamic.'][$marker];
      $cObj_conf  = $this->confMap['marker.']['snippets.']['jss.']['dynamic.'][$marker . '.'];
      $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      if( empty ( $content ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'marker.snippets.jss.dynamic.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3);
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
    $markerArray = array( );

    foreach( $this->confMap['marker.']['variables.']['dynamic.'] as $marker => $conf )
    {
      $dummy = $conf;
      if( substr( $marker, -1, 1 ) == '.' )
      {
        continue;
      }

      $hashKeyMarker = '###' . strtoupper( $marker ) . '###';

      $pos = strpos( $map_template, $hashKeyMarker );
      if( ( $pos === false ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = $hashKeyMarker . ' isn\'t part of the map HTML template. It won\'t rendered!';
          t3lib_div :: devLog('[INFO/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 0);
        }
        continue;
      }

      $cObj_name  = $this->confMap['marker.']['variables.']['dynamic.'][$marker];
      $cObj_conf  = $this->confMap['marker.']['variables.']['dynamic.'][$marker . '.'];
      $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
      if( empty ( $content ) )
      {
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'marker.variables.dynamic.' . $marker . ' is empty. Probably this is an error!';
          t3lib_div :: devLog('[WARN/BROWSERMAPS] ' . $prompt , $this->pObj->extKey, 3);
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
//var_dump( __METHOD__, __LINE__, $this->pObj->rows );
    $arr_return = array( );
    $mapMarkers = array( );

      // Get rendered points (map marker), lats and lons
    $arr_return = $this->renderMapMarkerPoints( );
    $mapMarkers = $arr_return['data']['mapMarkers'];
    $lats       = $arr_return['data']['lats'];
    $lons       = $arr_return['data']['lons'];
      // Get rendered points (map marker), lats and lons

      // Get points (map marker) as JSON array and coordinates
    $arr_return   = $this->renderMapMarkerPointsToJSON( $mapMarkers );
//var_dump( __METHOD__, __LINE__, $arr_return );
    $jsonData     = $arr_return['data']['jsonData'];
    $coordinates  = $arr_return['data']['coordinates'];
      // Get points (map marker) as JSON array and coordinates

      // Add JSON array
    $map_template = str_replace( "'###JSONDATA###'", $jsonData, $map_template );

      // Set center coordinates
    $map_template = $this->renderMapAutoCenterCoor( $map_template, $coordinates );
      // Set zoom level
    $map_template = $this->renderMapAutoZoomLevel( $map_template, $lons, $lats );

    return $map_template;
  }

  /**
 * renderMapMarkerVariablesSystemItem( ):
 *
 * @param	string		$map_template: ...
 * @return	string
 * @version 4.1.4
 * @since   4.1.0
 */
  private function renderMapMarkerVariablesSystemItem( $item )
  {
    $coa_name = $this->confMap['marker.']['variables.']['system.'][$item];
    $coa_conf = $this->confMap['marker.']['variables.']['system.'][$item . '.'];
    $value    = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
    return $value;
  }



/**
 * set_typeNum( ):
 *
 * @return	void
 * @version 3.9.8
 * @since   3.9.8
 */
  public function set_typeNum( )
  {
      // init the map
    $this->init( );
  }









  /***********************************************
  *
  * Map rendering marker
  *
  **********************************************/



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']);
}
?>
