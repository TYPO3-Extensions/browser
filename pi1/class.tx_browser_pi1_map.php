<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
* @version 4.1.0
* @since 3.9.6
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   69: class tx_browser_pi1_map
 *  103:     function __construct($pObj)
 *
 *              SECTION: Main
 *  132:     public function get_map( $template )
 *
 *              SECTION: Init
 *  189:     private function init(  )
 *  330:     private function initMainMarker( $template )
 *  414:     public function set_typeNum( )
 *
 *              SECTION: Map rendering
 *  444:     private function renderMap( $pObj_template )
 *  598:     private function renderMapMarkerVariablesSystem( $map_template )
 *
 *              SECTION: Map rendering marker
 *  630:     private function renderMapMarkerSnippetsHtmlDynamic( $map_template )
 *  683:     private function renderMapMarkerSnippetsHtmlCategories( $map_template )
 *  732:     private function renderMapMarkerSnippetsJssDynamic( $map_template )
 *
 *              SECTION: CSS
 *  798:     private function cssSetHtmlHeader( )
 *
 * TOTAL FUNCTIONS: 11
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
  var $enabled      = null;
    // [ARRAY] TypoScript configuration array. Will set by init( ) while runtime
  var $confMap      = null;
    // [Integer] Number of the current typeNum
  var $int_typeNum  = null;
    // [String] Name of the current typeNum
  var $str_typeNum  = null;
    // [BOOLEAN] true, if there are more than one category
  var $boolMoreThanOneCategory = null;








  /**
 * Constructor. The method initiate the parent object
 *
 * @param    object        The parent object
 * @return    void
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
 * @param    string        $template: Current HTML template
 * @return    array        $template: Template with map marker
 * @version 3.9.6
 * @since   3.9.6
 */
  public function get_map( $template )
  {
      // init the map
    $this->init( );



      ///////////////////////////////////////////////////////////////
      //
      // RETURN: map isn't enabled

    if( ! $this->enabled )
    {
      if( $this->pObj->b_drs_map )
      {
        $prompt = 'RETURN. Map is disabled.';
        t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
      }
      return $template;
    }
      // RETURN: map isn't enabled


      // DRS
    if( $this->pObj->b_drs_warn )
    {
      $prompt = 'The map module uses a JSON array. If you get any unexpected result, ' .
                'please remove config.xhtml_cleaning and/or page.config.xhtml_cleaning ' .
                'in your TypoScript configuration of the current page.';
      t3lib_div :: devLog('[WARN/MAP] ' . $prompt , $this->pObj->extKey, 2);
      $prompt = 'The map module causes some conflicts with AJAX. PLease disable AJAX in the ' .
                'plugin/flecform of the browser.';
      t3lib_div :: devLog('[WARN/MAP] ' . $prompt , $this->pObj->extKey, 2);
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
 * @return    void
 * @version 3.9.6
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
        switch( $this->enabled )
        {
          case( true ):
            $prompt = 'Map is enabled.';
            break;
          default:
            $prompt = 'Map is disabled.';
            break;
        }
        t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
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
          t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
          // local configuration
      default:
          // global configuration
        $this->confMap = $this->pObj->conf['navigation.']['map.'];
        if( $this->pObj->b_drs_map )
        {
          $prompt = 'Global configuration in: navigation.map';
          t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
        }
        break;
          // global configuration
    }
      // Set the global $confMapLocal



      ///////////////////////////////////////////////////////////////
      //
      // Set the global $enabled

    $cObj_name      = $this->confMap['enabled'];
    $cObj_conf      = $this->confMap['enabled.'];
    $this->enabled  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
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





      ///////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( $this->pObj->b_drs_map )
    {
      switch( $this->enabled )
      {
        case( true ):
          $prompt = 'Map is enabled.';
          break;
        default:
          $prompt = 'Map is disabled.';
          break;
      }
      t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System

    return;
  }








  /**
 * initMainMarker( ): Set the marker ###MAP###, if the current template hasn't any map-marker
 *
 * @param    string        $template: Current HTML template
 * @return    array        $template: Template with map marker
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
        t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
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
      t3lib_div :: devLog('[WARN/MAP] ' . $prompt_01 , $this->pObj->extKey, 2);
      t3lib_div :: devLog('[OK/MAP] '   . $prompt_02 , $this->pObj->extKey, -1);
      t3lib_div :: devLog('[HELP/MAP] ' . $prompt_03 , $this->pObj->extKey, 1);
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



  /**
 * set_typeNum( ):
 *
 * @return    void
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
  * cObject
  *
  **********************************************/



  /**
 * cObjDataAddRow( ):
 *
 * @param    array
 * @return    void
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
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
        $prompt = 'I.e: you can use the content in TypoScript with: field = ' . key( $row );
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
      }
      $first_loop = false;
    }

    $this->cObjDataAddMarker( );
    
  }



  /**
 * cObjDataAddMarker( ):
 *
 * @return    void
 * @version 4.1.0
 * @since   4.1.0
 */
  private function cObjDataAddMarker( )
  {
    foreach( array_keys( $this->confMap['marker.']['addToCData.']['system.'] ) as $marker )
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
          t3lib_div :: devLog( '[WARN/MAP] ' . $prompt , $this->pObj->extKey, 3 );
        }
        else
        {
          $prompt = 'Added to cObject: ' . $content;
          t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
          $prompt = 'You can use the content in TypoScript with: field = ' . $marker;
          t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
        }
      }
      $this->pObj->cObj->data[ $marker ] = $content;
    }
  }



  /**
 * cObjDataRemoveMarker( ):
 *
 * @return    void
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
 * @param    array
 * @return    void
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
 * renderMap( ): Set the marker ###MAP###, if the current template hasn't any map-marker
 *
 * @param    string        $pObj_template: current HTML template of the parent object
 * @return    array        $pObj_template: parent object template with map marker
 * @version 3.9.6
 * @since   3.9.6
 */
  private function renderMap( $pObj_template )
  {
      // map marker
    $str_mapMarker = '###MAP###';

      // Default content of the map marker
    $str_map =  '<div style="border:2px solid red;text-align:center;color:red;padding:1em;">' .
                  __METHOD__ . ' (' . __LINE__ . '): Error. MAP isn\'t rendered
                </div>';



      //////////////////////////////////////////////////////////////////////
      //
      // Get the template

    $map_template = $this->pObj->cObj->fileResource($this->confMap['template.']['file']);
      // Get the template



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN: no template

    if( empty( $map_template ) )
    {
        // DRS - Development Reporting System
      if ($this->b_drs_error)
      {
        $prompt = 'There is no template file. Path: navigation.map.template.file.';
        t3lib_div::devLog('[ERROR/DRS] ' . $prompt, $this->extKey, 3);
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 0);
      }
        // DRS - Development Reporting System
        // Error message
      $str_map  = '<h1 style="color:red;">' .
                    $this->pObj->pi_getLL('error_readlog_h1') .
                  '</h1>
                  <p style="color:red;font-weight:bold;">' .
                    $this->pObj->pi_getLL('error_template_map_no') .
                  '</p>';
        // Error message
        // Replace the map marker in the template of the parent object
      $pObj_template = str_replace( $str_mapMarker, $str_map, $pObj_template );
        // RETURN the template
      return $pObj_template;
    }
      // RETURN: no template



      //////////////////////////////////////////////////////////////////////
      //
      // Get the subpart

    $str_marker   = '###TEMPLATE_MAP###';
    $map_template = $this->pObj->cObj->getSubpart($map_template, $str_marker);
      // Get the subpart



      //////////////////////////////////////////////////////////////////////
      //
      // RETURN: no subpart marker

    if( empty( $map_template ) )
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
      $pObj_template = str_replace( $str_mapMarker, $str_map, $pObj_template );
        // RETURN the template
      return $pObj_template;
    }
      // RETURN: no subpart marker


      //////////////////////////////////////////////////////////////////////
      //
      // Substitute marker HTML

      // System marker
    $markerArray  = $this->renderMapMarkerSnippetsHtmlCategories( $map_template );
      // Dynamic marker
    $markerArray  = $markerArray + $this->renderMapMarkerSnippetsHtmlDynamic( $map_template );
      // Replace marker in the map HTML template
    $map_template = $this->pObj->cObj->substituteMarkerArray( $map_template, $markerArray );
      // Substitute marker HTML



      // Add data
    $map_template = $this->renderMapMarkerVariablesSystem( $map_template );
    $markerArray  = $this->renderMapMarkerVariablesDynamic( $map_template );
    $map_template = $this->pObj->cObj->substituteMarkerArray( $map_template, $markerArray );

    

      //////////////////////////////////////////////////////////////////////
      //
      // Substitute marker JSS

      // System marker
    //$markerArray  = $this->renderMapMarkerSnippetsHtmlCategories( $map_template );
      // Dynamic marker
    $markerArray  = $markerArray + $this->renderMapMarkerSnippetsJssDynamic( $map_template );
      // Replace marker in the map HTML template
    $map_template = $this->pObj->cObj->substituteMarkerArray( $map_template, $markerArray );
      // Substitute marker HTML



      // Replace the map marker in the template of the parent object
    $pObj_template = str_replace( $str_mapMarker, $map_template, $pObj_template );

//var_dump( __METHOD__ . ' (' . __LINE__ . '): ', $map_template, $pObj_template );
      // RETURN the template
    return $pObj_template;
  }









  /***********************************************
  *
  * Map rendering data
  *
  **********************************************/



  /**
 * renderMapMarkerVariablesDynamic( ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
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
          t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
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
          t3lib_div :: devLog('[WARN/MAP] ' . $prompt , $this->pObj->extKey, 3);
        }
      }
      $markerArray[ $hashKeyMarker ] = $content;
    }

    return $markerArray;
  }



  /**
 * renderMapMarkerVariablesSystem( ):
 *
 * @param    string        $map_template: ...
 * @return    string
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
 * renderMapMarkerPointsToJSON( ):
 *
 * @param    array        
 * @return    string    $jsonData
 * @version 4.1.0
 * @since   4.1.0
 */
  private function renderMapMarkerPointsToJSON( $mapMarkers )
  {
    $arr_return   = array( );
    $series       = null;
    $coordinates  = array( );

    $catIcons     = $this->renderMapMarkerCategoryIcons( );
    
//    $catIcons       = array( );
//    $catIcons['cat1'] = array( 'typo3conf/ext/browser/res/js/map/test/img/test1.png', 14, 14, 0, 0 );
//    $catIcons['cat2'] = array( 'typo3conf/ext/browser/res/js/map/test/img/test2.png', 14, 14, 0, 0 );

      // FOREACH map marker
    foreach( ( array ) $mapMarkers as $key => $mapMarker )
    {
        // Set category icon
      if( ! isset( $series[$mapMarker['cat']]['icon'] ) )
      {
        $series[$mapMarker['cat']]['icon'] = $catIcons[$mapMarker['iconKey']];
      }
        // Set category icon
        // Set coordinates
      $series[$mapMarker['cat']]['data'][$key]['coors']  = array( $mapMarker['lon'], $mapMarker['lat'] );
      $coordinates[] = $mapMarker['lon'] . ',' . $mapMarker['lat'];
        // Set coordinates
        // Set description
      $series[$mapMarker['cat']]['data'][$key]['desc']   = $mapMarker['desc'];
    }
      // FOREACH map marker
//var_dump( __METHOD__, __LINE__, $series, json_encode( $series ) ); 

    $jsonData = json_encode( $series );
    
    $arr_return['data']['jsonData']     = $jsonData;
    $arr_return['data']['coordinates']  = $coordinates;
    return $arr_return;
  }



  /**
 * renderMapMarkerCategoryIcons( ):
 *
 * @param    array        
 * @return    string    $jsonData
 * @version 4.1.0
 * @since   4.1.0
 */
  private function renderMapMarkerCategoryIcons( )
  {
    $catIcons = null;
    $arrIcon  = array( );
    
    foreach( array_keys( $this->confMap['configuration.']['categories.'] ) as $catKey )
    {
      if( substr( $catKey, -1 ) == '.' )
      {
        continue;
      }
      
      unset( $arrIcon );
      
        // Set the path
      $coa_name = $this->confMap['configuration.']['categories.'][$catKey . '.']['pathToIcon'];
      $coa_conf = $this->confMap['configuration.']['categories.'][$catKey . '.']['pathToIcon.'];
      $value    = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
//var_dump( __METHOD__, __LINE__, $catKey, $coa_name, $coa_conf, $value, $this->confMap['configuration.']['categories.'] );
//var_dump( __METHOD__, __LINE__, $catKey, $value );
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
      $value = $this->confMap['configuration.']['categories.'][$catKey . '.']['width'];
      if( empty( $value ) )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon width

        // Add the icon height
      $value = $this->confMap['configuration.']['categories.'][$catKey . '.']['height'];
      if( empty( $value ) )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon height

        // Add the icon x-offset
      $value = $this->confMap['configuration.']['categories.'][$catKey . '.']['offsetX'];
      if( $value == null )
      {
        die( 'Unexpeted error in ' . __METHOD__ . ' (line ' . __LINE__ . '): TypoScript property is empty.' );
      }
      $arrIcon[] = ( int ) $value;
        // Add the icon x-offset

        // Add the icon y-offset
      $value = $this->confMap['configuration.']['categories.'][$catKey . '.']['offsetY'];
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
 * @return    array
 * @version 4.1.0
 * @since   4.1.0
 */
  private function renderMapMarkerPoints( )
  {
    $arr_return   = array( );
    $lons         = array( );
    $lats         = array( );
    $dontHandle00 = $this->confMap['configuration.']['00Coordinates.']['dontHandle'];

      // FOREACH row
    foreach( $this->pObj->rows as $row )
    {
        // Add the current row to cObj->data
      $this->cObjDataAddRow( $row );

      $this->cObjDataAddMarker( );
      
        // Get the longitude
      $mapMarker['lon'] = $this->renderMapMarkerVariablesSystemItem( 'longitude' );
        // Get the latitude
      $mapMarker['lat'] = $this->renderMapMarkerVariablesSystemItem( 'latitude' );

        // SWITCH logitude and latitude
      switch( true )
      {
        case( $mapMarker['lon'] . $mapMarker['lat'] == '' ):
            // CONTINUE: longituda and latitude are empty
          continue 2;
          break;
        case( $dontHandle00 && $mapMarker['lon'] == 0 && $mapMarker['lat'] == 0 ):
            // CONTINUE: longituda and latitude are 0 and 0,0 shouldn't handled
          continue 2;
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

        // Get the category
      $mapMarker['cat'] = $this->renderMapMarkerVariablesSystemItem( 'category' );

        // Get the iconKey
      $mapMarker['iconKey'] = $this->renderMapMarkerVariablesSystemItem( 'iconKey' );
//var_dump( __METHOD__, __LINE__, $mapMarker['iconKey'] );

        // Save each mapMarker
      $mapMarkers[] = $mapMarker;
        // Save each longitude
      $lons[] = ( double ) $mapMarker['lon']; 
        // Save each latitude
      $lats[]  = ( double ) $mapMarker['lat']; 

        // Remove the current row from cObj->data
      $this->cObjDataRemoveRow( $row );
    }
      // FOREACH row
    
    $arr_return['data']['mapMarkers'] = $mapMarkers;
    $arr_return['data']['lats']       = $lats;
    $arr_return['data']['lons']       = $lons;
    return $arr_return;
  }



  /**
 * renderMapMarkerVariablesSystemItem( ):
 *
 * @param    string        $map_template: ...
 * @return    string
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
 * renderMapAutoCenterCoor( ):
 *
 * @param    string        $map_template: ...
 * @return    string
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
          t3lib_div :: devLog( '[ERROR/MAP] ' . $prompt , $this->pObj->extKey, 3 );
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
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
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

      // Get center coordinates
    $centerCoor = implode( ',', $objLibMap->centerCoor( ) );
    $centerCoor = '[' . $centerCoor . ']';
      // Get center coordinates

      // DRS
    if( $this->pObj->b_drs_map )
    {
      $prompt = 'configuration.centerCoordinates.mode is: ' . $mode . '. Calculated coordinates are ' . $centerCoor;
      t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
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
 * renderMapAutoZoomLevel( ):
 *
 * @param    string        $map_template: ...
 * @return    string
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
      case( 'ts' ):
          // Follow the workflow
        break;
      default:
          // DRS
        if( $this->pObj->b_drs_error )
        {
          $prompt = 'configuration.zoomLevel.mode is undefined: ' . $mode . '. But is has to be auto or ts!';
          t3lib_div :: devLog( '[ERROR/MAP] ' . $prompt , $this->pObj->extKey, 3 );
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
        $prompt = 'configuration.zoomLevel.mode is: ' . $mode . '. Zoom level won\'t calculated.';
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
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
    switch( true )
    {
      case( empty ( $longitudes ) ):
      case( empty ( $latitudes ) ):
          // No map markers
        $zoomLevel = 1;
        break;
      case( $maxDistance == 0 ):
          // One map marker
        $zoomLevel = 18;
        break;
      default:
          // Get the quotient. Example: 360 / 5.625 = 64
        $quotient  = 360 / $maxDistance;
          // Example: ( int ) log( 64 ) / log( 2 ) = 6
        $zoomLevel = ( int ) ( log( $quotient ) / log( 2 ) );
        break;
    }
//var_dump( __METHOD__, __LINE__, $longitudes, max( $longitudes ), min( $longitudes ),
//         $latitudes, max( $latitudes ), min( $latitudes ), $maxDistance, $quotient, $zoomLevel );
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

    if( $zoomLevel > 18 )
    {
      $zoomLevel = 18;
    }

      // DRS
    if( $this->pObj->b_drs_map )
    {
      $prompt = 'configuration.zoomLevel.mode is: ' . $mode . '. Calculated zoom level is ' . $zoomLevel;
      t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
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
  * Map rendering marker
  *
  **********************************************/



  /**
 * renderMapMarkerSnippetsHtml( $map_template, $tsProperty ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
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
          t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
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
          t3lib_div :: devLog('[WARN/MAP] ' . $prompt , $this->pObj->extKey, 3);
        }
      }
      $markerArray[ $hashKeyMarker ] = $content;
    }

    return $markerArray;
  }



  /**
 * renderMapMarkerSnippetsHtmlDynamic( $map_template ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
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
 * renderMapMarkerSnippetsHtmlCategories( ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
 * @version 4.1.4
 * @since   4.1.0
 */
  private function renderMapMarkerSnippetsHtmlCategories( $map_template )
  {
    $markerArray = array( );
    
    if( ! $this->zz_moreThanOneCategory( ) )
    {
      return $markerArray;
    }
    
    $tsProperty   = 'categories';
    $markerArray  =  $this->renderMapMarkerSnippetsHtml( $map_template, $tsProperty );

    return $markerArray;
  }



  /**
 * renderMapMarkerSnippetsJssDynamic( $map_template ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
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
          t3lib_div :: devLog('[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0);
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
          t3lib_div :: devLog('[WARN/MAP] ' . $prompt , $this->pObj->extKey, 3);
        }
      }
      $markerArray[ "'" . $hashKeyMarker . "'" ] = $content;
    }

    return $markerArray;
  }






  /***********************************************
  *
  * Helper
  *
  **********************************************/



  /**
 * zz_moreThanOneCategory( ):
 *
 * @param    [type]        $$map_template: ...
 * @return    array
 * @version 4.1.4
 * @since   4.1.4
 */
  private function zz_moreThanOneCategory( )
  {
    if( $this->boolMoreThanOneCategory != null )
    {
      return $this->boolMoreThanOneCategory;
    }
    
    $category = $this->renderMapMarkerVariablesSystemItem( 'category' );
    foreach( $this->pObj->rows as $row )
    {
 var_dump( __METHOD__, __LINE__, $row[ $category ] ); 
    }
    if( $this->pObj->b_drs_map )
    {
      if( empty ( $content ) )
      {
        $prompt = 'marker.addToCData.' . $marker . ' is empty. Probably this is an error!';
        t3lib_div :: devLog( '[WARN/MAP] ' . $prompt , $this->pObj->extKey, 3 );
      }
      else
      {
        $prompt = 'Added to cObject: ' . $content;
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
        $prompt = 'You can use the content in TypoScript with: field = ' . $marker;
        t3lib_div :: devLog( '[INFO/MAP] ' . $prompt , $this->pObj->extKey, 0 );
      }
    }

    $this->boolMoreThanOneCategory = false;
    return $this->boolMoreThanOneCategory;
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']);
}
?>