<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_mapleaflet bundles methods for rendering and processing calender based content, filters and category menues
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 7.0.0
 * @since 7.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */
class tx_browser_pi1_mapleaflet
{

//  // [OBJECT] parent object
//  public $pObj;
//  // [OBJECT] copy of the cObject of the parent object
//  private $cObj;
//  // [ARRAY] TypoScript configuration array. Will set while runtime
//  private $confMap;
//  // [string]
  private $mapLLjssLeafletFooterInline = null;
  private $mapLLleafletIsEnabled = null;
  private $mapLLOverlayIsEmpty = true;
  private $mapLLProviderGoogleIsEnabled = null;
  private $mapLLProviderIsEnabled = null;
  private $mapLLProviderOsmIsEnabled = null;

//  protected $view;

  /*   * *********************************************
   *
   * Setter
   *
   * ******************************************** */

  /**
   * mapLLaddJsFooterInlineCode( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  public function mapLLsetObj( $pObj, $confMap )
  {
    $this->pObj = $pObj;
    $this->cObj = $pObj->cObj;
    $this->view = $pObj->view;
    $this->confMap = $confMap;
  }

  /*   * *********************************************
   *
   * Javascript
   *
   * ******************************************** */

  /**
   * mapLLaddJsFooterInlineCode( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLaddJsFooterInlineCode()
  {
    $name = 'leafletFooterInline';
    $block = $this->mapLLjssLeafletFooterInline;
    $GLOBALS[ 'TSFE' ]->getPageRenderer()->addJsFooterInlineCode( $name, $block );
  }

  /*   * *********************************************
   *
   * Div container
   *
   * ******************************************** */

  /**
   * mapLLgetDivContainer( ): Get the HTML template
   *
   * @param	string		$template   : current HTML template of the parent object
   * @return	array		$arr_return : with elements error, template
   * @access public
   * @version 7.0.0
   * @since   7.0.0
   */
  public function mapLLgetDivContainer( $template )
  {
    // Default values
    $error = '<p>ERROR at ' . __METHOD__ . ' (' . __LINE__ . ')</p>';
    $arr_return = array(
      'error' => true,
      'template' => $error
    );

    // Div container depends on the current view
    switch ( $this->view )
    {
      case( 'list' ):
        $coa_name = $this->confMap[ 'template.' ][ 'leaflet.' ][ 'container.' ][ 'list' ];
        $coa_conf = $this->confMap[ 'template.' ][ 'leaflet.' ][ 'container.' ][ 'list.' ];
        break;
      case( 'single' ):
        $coa_name = $this->confMap[ 'template.' ][ 'leaflet.' ][ 'container.' ][ 'single' ];
        $coa_conf = $this->confMap[ 'template.' ][ 'leaflet.' ][ 'container.' ][ 'single.' ];
        break;
      default:
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value. $this->view is "' . $this->view . '"';
        $this->pObj->drs_die( $header, $text );
        exit;
    } // Div container depends on the current view

    $divContainer = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );

    // Proper result: RETURN div container
    if ( !empty( $divContainer ) )
    {
      $arr_return = array(
        'error' => false,
        'template' => $divContainer
      );
      return $arr_return;
    } // Proper result: RETURN div container
    // Unproper result: RETURN error
    $arr_return = $this->mapLLgetDivContainerError( $template );
    return $arr_return;
  }

  /**
   * mapLLgetDivContainerError( ): Returns an error prompt
   *
   * @param	string		$template   : current HTML template of the parent object
   * @return	array		$arr_return : with elements error, prompt
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLgetDivContainerError( $template )
  {
    // RETURN error message
    $error = '<h1 style="color:red;">'
            . $this->pObj->pi_getLL( 'error_readlog_h1' )
            . '</h1>'
            . '<p style="color:red;font-weight:bold;">'
            . $this->pObj->pi_getLL( 'error_template_map_no' )
            . '</p>';
    // Replace the map marker in the template of the parent object
    $hashKey = '###MAP###';
    $template = str_replace( $hashKey, $error, $template );
    // RETURN the template
    $arr_return = array(
      'error' => true,
      'template' => $template
    );
    return $arr_return;
  }

  /**
   * mapLLinit( ): Add the Javascript
   *
   * @param	string		$template     : current HTML template of the parent object
   * @param	string		$mapTemplate  : current HTML template of the map
   * @return	array		$template     : handled HTML template of the parent object
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLinit( $template, $mapTemplate )
  {

    return;
    $markerHtmlDynamic = $this->renderMapMarkerSnippetsHtmlDynamic( $mapTemplate );
    $marker = $markerHtmlCategories + $markerHtmlDynamic;
    $mapTemplate = $this->cObj->substituteMarkerArray( $mapTemplate, $marker );
    //var_dump( __METHOD__, __LINE__, $marker, $mapTemplate );
  }

  /**
   * mapLLleafletIsEnabled( ): returns true, if leaflet and a provider (GoogleMaps or OSM) is enabledl
   *
   * @return	boolean Returns true or false
   * @internal  #65184
   * @access protected
   * @version 7.0.0
   * @since   7.0.0
   */
  protected function mapLLleafletIsEnabled()
  {
    if ( $this->mapLLleafletIsEnabled !== null )
    {
      return $this->mapLLleafletIsEnabled;
    }

    if ( $this->mapLLMapIsDisabled() )
    {
      return false;
    }

    if ( !$this->mapLLProviderIsEnabled() )
    {
      return false;
    }

    $mode = $this->confMap[ 'compatibility.' ][ 'mode' ];
    switch ( $mode )
    {
      case('leaflet (default)'):
        return true;
      case('oxMap (deprecated)'):
        return false;
      default:
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value. navigation.map.compatibility.mode is "' . $mode . '"';
        $this->pObj->drs_die( $header, $text );
        exit;
    }
  }

  /**
   * mapLLMapIsDisabled( ):
   *
   * @return	boolean
   * @internal  #65184
   * @access protected
   * @version 7.0.0
   * @since   7.0.0
   */
  protected function mapLLMapIsDisabled()
  {
    $enabled = $this->confMap[ 'enabled' ];
    switch ( $enabled )
    {
      case('disabled'):
      case('Map +Routes'):
        return true;
      case('Map'):
        return false;
      default:
        $header = 'FATAL ERROR!';
        $text = 'Unexpeted value. navigation.map.$enabled is "' . $mode . '"';
        $this->pObj->drs_die( $header, $text );
        exit;
    }
  }

  /**
   * mapLLProviderDefault( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLProviderDefault()
  {
    $defaultProvider = $this->confMap[ 'provider.' ][ 'default' ];

    switch ( $defaultProvider )
    {
      case('googleHybrid'):
        if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'hybrid' ] )
        {
          return $defaultProvider;
        }
        break;
      case('googleRoadmap'):
        if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'roadmap' ] )
        {
          return $defaultProvider;
        }
        break;
      case('googleSatellite'):
        if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'satellite' ] )
        {
          return $defaultProvider;
        }
        break;
      case('googleTerrain'):
        if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'terrain' ] )
        {
          return $defaultProvider;
        }
        break;
      case('osmRoadmap'):
        if ( $this->confMap[ 'provider.' ][ 'osm.' ][ 'roadmap' ] )
        {
          return $defaultProvider;
        }
        break;
    }

    $header = 'FATAL ERROR!';
    $text = 'Default map provider is "' . $defaultProvider . '".<br /> '
            . 'But this map provider is disabled in the list of the map providers.<br /> '
            . 'Please solve this conflict.<br /> '
            . 'Take the Constant Editor. See category [BROWSERMAPS - PROVIDER].';
    $this->pObj->drs_die( $header, $text );
    exit;
  }

  /**
   * mapLLProviderIsEnabled( ): Returns true, if one or both - googleMaps and OSM - are enabled
   *
   * @return	boolean Returns true or false
   * @internal  #65184
   * @access protected
   * @version 7.0.0
   * @since   7.0.0
   */
  protected function mapLLProviderIsEnabled()
  {
    if ( $this->mapLLProviderIsEnabled !== null )
    {
      return $this->mapLLProviderIsEnabled;
    }

    switch ( true )
    {
      case($this->mapLLProviderGoogleIsEnabled()):
      case($this->mapLLProviderOsmIsEnabled()):
        return true;
      default:
        return false;
    }
  }

  /**
   * mapLLProviderGoogleIsEnabled( ): Returns true, if googleMaps is enabled
   *
   * @return	boolean Returns true or false
   * @internal  #65184
   * @access protected
   * @version 7.0.0
   * @since   7.0.0
   */
  protected function mapLLProviderGoogleIsEnabled()
  {
    if ( $this->mapLLProviderGoogleIsEnabled !== null )
    {
      return $this->mapLLProviderGoogleIsEnabled;
    }

    switch ( true )
    {
      case($this->confMap[ 'provider.' ][ 'google.' ][ 'hybrid' ]):
      case($this->confMap[ 'provider.' ][ 'google.' ][ 'roadmap' ]):
      case($this->confMap[ 'provider.' ][ 'google.' ][ 'satellite' ]):
      case($this->confMap[ 'provider.' ][ 'google.' ][ 'terrain' ]):
        return true;
      default:
        return false;
    }
  }

  /**
   * mapLLProviderOrder( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLProviderOrder()
  {
    $strOrder = $this->confMap[ 'provider.' ][ 'order' ];
    $strOrder = str_replace( ' ,', null, $strOrder );
    $arrOrder = explode( ',', $strOrder );

    return $arrOrder;
  }

  /**
   * mapLLProviderOsmIsEnabled( ): Returns true, if OSM is enabled
   *
   * @return	boolean Returns true or false
   * @internal  #65184
   * @access protected
   * @version 7.0.0
   * @since   7.0.0
   */
  protected function mapLLProviderOsmIsEnabled()
  {
    if ( $this->mapLLProviderOsmIsEnabled !== null )
    {
      return $this->mapLLProviderOsmIsEnabled;
    }

    switch ( true )
    {
      case($this->confMap[ 'provider.' ][ 'osm.' ][ 'roadmap' ]):
        return true;
      default:
        return false;
    }
  }

  /*   * *********************************************
   *
   * Javascript
   *
   * ******************************************** */

  /**
   * mapLLjss( ): Add the Javascript
   *
   * @param	string		$template     : current HTML template of the parent object
   * @param	string		$mapTemplate  : current HTML template of the map
   * @return	array		$template     : handled HTML template of the parent object
   * @access public
   * @version 7.0.0
   * @since   7.0.0
   */
  public function mapLLjss( $template, $mapTemplate )
  {
    $hashKey = '###MAP###';

    $this->mapLLinit( $template, $mapTemplate );

    $this->mapLLjssComment();
    $this->mapLLjssMapObject();
    $this->mapLLjssTileLayer();
    $this->mapLLjssSetView();
    $this->mapLLjssAttributionControl();

    $this->mapLLjssIcon();
    $this->mapLLjssAddLayer();
    $this->mapLLjssAddControl();

    $this->mapLLaddJsFooterInlineCode();

    $template = str_replace( $hashKey, $mapTemplate, $template );
    return $template;
  }

  /**
   * mapLLjssAddControl( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControl()
  {
    $arrLayers = array();
    $arrOrder = $this->mapLLProviderOrder();

    foreach ( $arrOrder as $provider )
    {
      switch ( $provider )
      {
        case('googleHybrid'):
          $arrLayers[ 'googleHybrid' ] = $this->mapLLjssAddControlGoogleMapsHybrid();
          break;
        case('googleRoadmap'):
          $arrLayers[ 'googleRoadmap' ] = $this->mapLLjssAddControlGoogleMapsRoadmap();
          break;
        case('googleSatellite'):
          $arrLayers[ 'googleSatellite' ] = $this->mapLLjssAddControlGoogleMapsSatellite();
          break;
        case('googleTerrain'):
          $arrLayers[ 'googleTerrain' ] = $this->mapLLjssAddControlGoogleMapsTerrain();
          break;
        case('osmRoadmap'):
          $arrLayers[ 'osmRoadmap' ] = $this->mapLLjssAddControlOSMRoadmap();
          break;
      }
    }

    $strLayers = null;
    $arrLayers = array_filter( $arrLayers );

    if ( count( $arrLayers ) >= 2 )
    {
      $strLayers = implode( ', ', $arrLayers );
    }

    switch ( true )
    {
      case(!$this->mapLLOverlayIsEmpty ):
      case($strLayers ):
        // Follow the workflow
        break;
      default;
        return;
    }

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var baseLayers = {" . $strLayers . "};"
            . PHP_EOL
    ;

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "map.addControl( new L.Control.Layers( baseLayers, overlays ));"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssAddControlGoogleMapsHybrid( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControlGoogleMapsHybrid()
  {
    if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'hybrid' ] )
    {
      return "'" . $this->pObj->pi_getLL( 'leafletLabelGoogleHybrid' ) . "' : googleHybrid";
    }

    return null;
  }

  /**
   * mapLLjssAddControlGoogleMapsRoadmap( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControlGoogleMapsRoadmap()
  {
    if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'roadmap' ] )
    {
      return "'" . $this->pObj->pi_getLL( 'leafletLabelGoogleRoadmap' ) . "' : googleRoadmap";
    }

    return null;
  }

  /**
   * mapLLjssAddControlGoogleMapsSatellite( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControlGoogleMapsSatellite()
  {
    if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'satellite' ] )
    {
      return "'" . $this->pObj->pi_getLL( 'leafletLabelGoogleSatellite' ) . "' : googleSatellite";
    }

    return null;
  }

  /**
   * mapLLjssAddControlGoogleMapsTerrain( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControlGoogleMapsTerrain()
  {
    if ( $this->confMap[ 'provider.' ][ 'google.' ][ 'terrain' ] )
    {
      return "'" . $this->pObj->pi_getLL( 'leafletLabelGoogleTerrain' ) . "' : googleTerrain";
    }

    return null;
  }

  /**
   * mapLLjssAddControlOSMRoadmap( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddControlOSMRoadmap()
  {
    if ( $this->confMap[ 'provider.' ][ 'osm.' ][ 'roadmap' ] )
    {
      return "'" . $this->pObj->pi_getLL( 'leafletLabelOSMRoadmap' ) . "' : osmRoadmap";
    }

    return null;
  }

  /**
   * mapLLjssAddLayer( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayer()
  {
    $this->mapLLjssAddLayerBase();
    $this->mapLLjssAddLayerOverlay();
  }

  /**
   * mapLLjssAddLayerBase( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerBase()
  {
    $defaultProvider = $this->mapLLProviderDefault();

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "map.addLayer( " . $defaultProvider . ");"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssAddLayerOverlay( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlay()
  {
    $this->mapLLjssAddLayerOverlayGroupsInit();
    $this->mapLLjssAddLayerOverlayGroupsMarker();
    $this->mapLLjssAddLayerOverlayGroupsAdd();
    $this->mapLLjssAddLayerOverlayAdd();
  }

  /**
   * mapLLjssAddLayerOverlayAdd( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayAdd()
  {
    $strOverlays = null;
    $arrOverlays = array();

    //var_dump( __METHOD__, __LINE__, $this->arrCategories );
    switch ( true )
    {
      case(!$this->confMap[ 'configuration.' ][ 'overlays' ]):
        $this->mapLLjssLeafletFooterInline = ''
                . $this->mapLLjssLeafletFooterInline
                . "var overlays = { };"
                . PHP_EOL
        ;
        $this->mapLLOverlayIsEmpty = true;
        return;
      case($this->confMap[ 'configuration.' ][ 'overlays' ]):
      default:
        foreach ( $this->arrCategories[ 'labels' ] as $key => $value )
        {
          $arrOverlays[] = "'" . $value . "' : lg" . $key;
        }
        if ( count( $arrOverlays ) >= 2 )
        {
          $strOverlays = implode( ', ', $arrOverlays );
        }

        $this->mapLLjssLeafletFooterInline = ''
                . $this->mapLLjssLeafletFooterInline
                . "var overlays = { " . $strOverlays . " };"
                . PHP_EOL
        ;
        $this->mapLLOverlayIsEmpty = false;
        return;
    }
  }

  /**
   * mapLLjssAddLayerOverlayGroupsAdd( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsAdd()
  {
    //var_dump( __METHOD__, __LINE__, $this->arrCategories );
    switch ( true )
    {
      case(!$this->confMap[ 'configuration.' ][ 'overlays' ]):
        $this->mapLLjssLeafletFooterInline = ''
                . $this->mapLLjssLeafletFooterInline
                . "map.addLayer( lgDefault ); // "
                . PHP_EOL
        ;
        return;
      case($this->confMap[ 'configuration.' ][ 'overlays' ]):
      default:
        foreach ( $this->arrCategories[ 'labels' ] as $key => $value )
        {
          $this->mapLLjssLeafletFooterInline = ''
                  . $this->mapLLjssLeafletFooterInline
                  . "map.addLayer( lg" . $key . " ); // " . $value
                  . PHP_EOL
          ;
        }
        return;
    }
  }

  /**
   * mapLLjssAddLayerOverlayGroupsInit( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsInit()
  {
    switch ( true )
    {
      case(!$this->confMap[ 'configuration.' ][ 'overlays' ]):
        $this->mapLLjssLeafletFooterInline = ''
                . $this->mapLLjssLeafletFooterInline
                . "var lgDefault = new L.MarkerClusterGroup();"
                . PHP_EOL
        ;
        return;
      case($this->confMap[ 'plugins.' ][ 'mastercluster.' ][ 'enabled' ]):
        $this->mapLLjssAddLayerOverlayGroupsInitCluster();
        return;
      case(!$this->confMap[ 'plugins.' ][ 'mastercluster.' ][ 'enabled' ]):
      default:
        $this->mapLLjssAddLayerOverlayGroupsInitGroup();
        return;
    }
  }

  /**
   * mapLLjssAddLayerOverlayGroupsInitCluster( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsInitCluster()
  {
    //var_dump( __METHOD__, __LINE__, $this->arrCategories );
    foreach ( $this->arrCategories[ 'labels' ] as $key => $value )
    {
      $this->mapLLjssLeafletFooterInline = ''
              . $this->mapLLjssLeafletFooterInline
              . "var lg" . $key . " = new L.MarkerClusterGroup(); // " . $value
              . PHP_EOL
      ;
    }
    return;
  }

  /**
   * mapLLjssAddLayerOverlayGroupsInitGroup( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsInitGroup()
  {
    //var_dump( __METHOD__, __LINE__, $this->arrCategories );
    foreach ( $this->arrCategories[ 'labels' ] as $key => $value )
    {
      $this->mapLLjssLeafletFooterInline = ''
              . $this->mapLLjssLeafletFooterInline
              . "var lg" . $key . " = new L.LayerGroup(); // " . $value
              . PHP_EOL
      ;
    }
    return;
  }

  /**
   * mapLLjssAddLayerOverlayGroupsMarker( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsMarker()
  {
    $arrMarker = array();
    //var_dump( __METHOD__, __LINE__, $this->marker );

    foreach ( $this->marker as $marker )
    {
      $lat = $marker[ 'lat' ];
      $lon = $marker[ 'lon' ];
      $icon = $this->mapLLjssAddLayerOverlayGroupsMarkerIcon( $marker[ 'iconKey' ] );
      $popUp = $marker[ 'desc' ];
      $popUp = preg_replace( '/\\r/', null, $popUp );
      $popUp = preg_replace( '/\\n/', null, $popUp );
      //$popUp = nl2br( $marker[ 'desc' ] ); <- Isn't proper!
      $layerGroup = "lg" . $marker[ 'iconKey' ];
      if ( !$this->confMap[ 'configuration.' ][ 'overlays' ] )
      {
        $layerGroup = "lgDefault";
      }
      $arrMarker[] = "L.marker([" . $lat . ", " . $lon . "]" . $icon . ").bindPopup('" . $popUp . "').addTo( " . $layerGroup . ")";
    }

    if ( empty( $arrMarker ) )
    {
      return;
    }

    $strMarker = implode( ',' . PHP_EOL, $arrMarker );
    $strMarker = $strMarker . ';';

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . $strMarker
            . PHP_EOL
    ;
    return;
  }

  /**
   * mapLLjssAddLayerOverlayGroupsMarkerIcon( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAddLayerOverlayGroupsMarkerIcon( $iconKey )
  {
    if ( $this->confMap[ 'configuration.' ][ 'defaultIcons.' ][ 'enabled' ] )
    {
      return null;
    }

    $icon = "liIcon" . $iconKey;
    return ", {icon: " . $icon . "}";
  }

  /**
   * mapLLjssAttributionControl( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssAttributionControl()
  {
    $prefix = "<a href=\"http://typo3-organiser.de/\" "
            . "title=\"TYPO3 Organiser: Leaflet ready to use.\" "
            . "style=\"font-weight:normal\">Leaflet/TYPO3</a>";
    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "map.attributionControl.setPrefix( '" . $prefix . "' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssComment( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssComment()
  {
    $author = '@author      (c) 2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de/>';
    $poweredBy = '@description Powered by the Leaflet modul of the TYPO3 Browser - TYPO3 without PHP. See http://typo3-browser.de/';
    $readyToUse = '             Ready to use by the TYPO3 Organsier - TYPO3 for Lobbies and Organisers. See http://typo3-organiser.de/';
    $since = '@since       7.0.0';
    $version = '@version     7.0.0';
    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . PHP_EOL
            . PHP_EOL
            . "/* "
            . PHP_EOL
            . " *   " . $poweredBy
            . PHP_EOL
            . " *   " . $readyToUse
            . PHP_EOL
            . " *   " . $author
            . PHP_EOL
            . " *   " . $version
            . PHP_EOL
            . " *   " . $since
            . PHP_EOL
            . " */"
            . PHP_EOL
            . PHP_EOL
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssIcon( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIcon()
  {
    if ( $this->confMap[ 'configuration.' ][ 'defaultIcons.' ][ 'enabled' ] )
    {
      return;
    }

    $this->mapLLjssIconClass();
    $this->mapLLjssIconIcons();
  }

  /**
   * mapLLjssIconClass( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClass()
  {
    $shadowPath = $this->mapLLjssIconClassShadowPath();
    $shadowSize = $this->mapLLjssIconClassSizeShadow( $shadowPath );
    $iconSize = $this->mapLLjssIconClassSizeIcon();
    $iconAnchor = $this->mapLLjssIconClassAnchorIcon();
    $popupAnchor = $this->mapLLjssIconClassAnchorPopup();
    $shadowAnchor = $this->mapLLjssIconClassAnchorShadow();
//    var_dump( __METHOD__, __LINE__, $this->arrCategories, $this->catIcons );

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "
var LeafIcon = L.Icon.extend({
  options: {
    shadowUrl     : '" . $shadowPath . "',
    iconSize      : [" . $iconSize . "],
    shadowSize    : [" . $shadowSize . "],
    iconAnchor    : [" . $iconAnchor . "],
    shadowAnchor  : [" . $shadowAnchor . "],
    popupAnchor   : [" . $popupAnchor . "]
  }
});
"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssIconClassAnchorIcon( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassAnchorIcon()
  {
    $key = key( $this->marker );
    $x = $this->marker[ $key ][ 'iconOffsetX' ];
    $y = $this->marker[ $key ][ 'iconOffsetY' ];
    return $x . ", " . $y;
  }

  /**
   * mapLLjssIconClassAnchorPopup( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassAnchorPopup()
  {
    $x = $this->confMap[ 'configuration.' ][ 'popup.' ][ 'offset.' ][ 'x' ];
    $y = $this->confMap[ 'configuration.' ][ 'popup.' ][ 'offset.' ][ 'y' ];
    return $x . ", " . $y;
  }

  /**
   * mapLLjssIconClassAnchorShadow( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassAnchorShadow()
  {
    $x = $this->confMap[ 'configuration.' ][ 'defaultIcons.' ][ 'shadow.' ][ 'offsetX' ];
    $y = $this->confMap[ 'configuration.' ][ 'defaultIcons.' ][ 'shadow.' ][ 'offsetY' ];
    return $x . ", " . $y;
  }

  /**
   * mapLLjssIconClassShadowPath( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassShadowPath()
  {
    // Set the path
    $shadowPath = $this->confMap[ 'configuration.' ][ 'defaultIcons.' ][ 'shadow.' ][ 'path' ];

    if ( empty( $shadowPath ) )
    {
      $header = 'FATAL ERROR!';
      $text = 'Unexpeted value : TypoScript property "configuration.defaultIcons.shadow.path" is empty.';
      $this->pObj->drs_die( $header, $text );
    }
    // absolute path
    $pathAbsolute = t3lib_div::getFileAbsFileName( $shadowPath );
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

  /**
   * mapLLjssIconClassSize( ):
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassSize( $path )
  {
    $root = t3lib_div::getIndpEnv( 'TYPO3_DOCUMENT_ROOT' ) . '/';
    list( $width, $height ) = getimagesize( $root . $path );
    if ( !( empty( $height ) or empty( $width ) ) )
    {
      $size = $width . ", " . $height;
      return $size;
    }

    $header = 'FATAL ERROR!';
    $text = 'Can\'t calculate width or height from: ' . $root;
    $this->pObj->drs_die( $header, $text );
  }

  /**
   * mapLLjssIconClassSizeIcon( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassSizeIcon()
  {
    //var_dump( __METHOD__, __LINE__, $this->arrCategories, $this->catIcons );
    $key = key( $this->marker );
    $path = $this->marker[ $key ][ 'catIconMap' ];
    return $this->mapLLjssIconClassSize( $path );
  }

  /**
   * mapLLjssIconClassSizeShadow( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconClassSizeShadow( $path )
  {
    return $this->mapLLjssIconClassSize( $path );
  }

  /**
   * mapLLjssIconIcons( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssIconIcons()
  {
//    var_dump( __METHOD__, __LINE__, $this->arrCategories, $this->marker );
    foreach ( array_keys( $this->marker ) as $key )
    {
      $iconKey = $this->marker[ $key ][ 'iconKey' ];
//      if ( !in_array( $iconKey, array_keys( $this->arrCategories[ 'labels' ] ) ) )
//      {
//        continue;
//      }
      $path = $this->marker[ $key ][ 'catIconMap' ];
      $comment = $this->arrCategories[ 'labels' ][ $iconKey ];
      $this->mapLLjssLeafletFooterInline = ''
              . $this->mapLLjssLeafletFooterInline
              . "var liIcon" . $iconKey . " = new LeafIcon({iconUrl: '" . $path . "'}); // " . $comment
              . PHP_EOL
      ;
    }
  }

  /**
   * mapLLjssMapObject( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssMapObject()
  {
    $htmlId = $this->mapLLjssMapObjectHtmlId();
    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var map = L.map( '" . $htmlId . "' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssMapObject( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssMapObjectHtmlId()
  {
    $htmlId = $this->confMap[ 'template.' ][ 'leaflet.' ][ 'htmlId' ];
    return $htmlId;
  }

  /**
   * mapLLjssSetView( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssSetView()
  {
    $coordinates = $this->mapLLjssSetViewCoordinates();
    $zoom = $this->mapLLjssSetViewZoom();

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "map.setView( " . $coordinates . ", " . $zoom . " );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssSetViewCoordinates( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssSetViewCoordinates()
  {
    $coordinates = $this->coordinates[ 'center' ];
    return $coordinates;
    //return '[ 51.505, -0.09 ]'; // London (lat, lon)
  }

  /**
   * mapLLjssSetViewZoom( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssSetViewZoom()
  {
    return $this->zoomlevel;
  }

  /**
   * mapLLjssTileLayer( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayer()
  {
    $this->mapLLjssTileLayerGoogleMaps();
    $this->mapLLjssTileLayerOSM();
  }

  /**
   * mapLLjssTileLayerGoogleMaps( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerGoogleMaps()
  {
    $this->mapLLjssTileLayerGoogleMapsHybrid();
    $this->mapLLjssTileLayerGoogleMapsRoadmap();
    $this->mapLLjssTileLayerGoogleMapsSatellite();
    $this->mapLLjssTileLayerGoogleMapsTerrain();
  }

  /**
   * mapLLjssTileLayerGoogleMapsHybrid( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerGoogleMapsHybrid()
  {
    if ( !$this->confMap[ 'provider.' ][ 'google.' ][ 'hybrid' ] )
    {
      return null;
    }

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var googleHybrid = new L.Google( 'HYBRID' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssTileLayerGoogleMapsRoadmap( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerGoogleMapsRoadmap()
  {
    if ( !$this->confMap[ 'provider.' ][ 'google.' ][ 'roadmap' ] )
    {
      return null;
    }

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var googleRoadmap = new L.Google( 'ROADMAP' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssTileLayerGoogleMapsSatellite( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerGoogleMapsSatellite()
  {
    if ( !$this->confMap[ 'provider.' ][ 'google.' ][ 'satellite' ] )
    {
      return null;
    }

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var googleSatellite = new L.Google( 'SATELLITE' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssTileLayerGoogleMapsTerrain( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerGoogleMapsTerrain()
  {
    if ( !$this->confMap[ 'provider.' ][ 'google.' ][ 'terrain' ] )
    {
      return null;
    }

    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "var googleTerrain = new L.Google( 'TERRAIN' );"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssTileLayerOSM( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerOSM()
  {
    $this->mapLLjssTileLayerOSMRoadmap();
  }

  /**
   * mapLLjssTileLayerOSMRoadmap( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssTileLayerOSMRoadmap()
  {
    if ( !$this->confMap[ 'provider.' ][ 'osm.' ][ 'roadmap' ] )
    {
      return null;
    }

    $copyright = "&copy; <a href=\"http://openstreetmap.org\" "
            . "title=\"openstreetmap.org\" "
            . "style=\"font-weight:normal\" "
            . ">OpenStreetMap</a>";
    $maxZoom = $this->mapLLjssMaxZoom();
    $this->mapLLjssLeafletFooterInline = ''
            . $this->mapLLjssLeafletFooterInline
            . "
var osmRoadmap = L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '" . $copyright . "',
  maxZoom: " . $maxZoom . "
} );
"
            . PHP_EOL
    ;
  }

  /**
   * mapLLjssTileLayerOSM( ): Add the Javascript
   *
   * @return	void
   * @access private
   * @version 7.0.0
   * @since   7.0.0
   */
  private function mapLLjssMaxZoom()
  {
    $maxZoomLevel = $this->confMap[ 'configuration.' ][ 'zoomLevel.' ][ 'max' ];
    return $maxZoomLevel;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_mapleaflet.php' ] )
{
  include_once ($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_mapleaflet.php' ]);
}