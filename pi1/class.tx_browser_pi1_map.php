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
 *   91:     function __construct($pObj)
 *
 *              SECTION: Map
 *  126:     public function get_map( $template )
 *
 *              SECTION: Init
 *  189:     private function init(  )
 *  308:     private function init_marker( $template )
 *  393:     private function render_map( $pObj_template )
 *
 *              SECTION: Marker
 *  553:     private function marker_divMap( )
 *  576:     private function marker_formFilter( )
 *  599:     private function marker_jssFilter( )
 *  622:     private function marker_jssRenderMap( )
 *
 *              SECTION: CSS
 *  660:     private function css_setHeader( )
 *
 *              SECTION: JavaScripts
 *  702:     private function jss_setHeader( )
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_map
{

    // [BOOLEAN] Is map enabled? Will set by init( ) while runtime
  var $enabled      = null;
    // [ARRAY] TypoScript configuration array. Will set by init( ) while runtime
  var $confMap      = null;
    // [Integer] Number of the current typeNum
  var $int_typeNum  = null;
    // [String] Name of the current typeNum
  var $str_typeNum  = null;









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
  * Map
  *
  **********************************************/









  /**
 * get_map( ): Set the marker ###MAP###, if the current template hasn't any map-marker
 *
 * @param	string		$template: Current HTML template
 * @return	array		$template: Template with map marker
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



      // set the map marker (in case template is without the marker)
    $template = $this->init_marker( $template );

      // render the map
    $template = $this->render_map( $template );

      // RETURN the template
    return $template;
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
  * Init
  *
  **********************************************/









  /**
 * init(): The method sets the globals $enabled and $confMap
 *
 * @return	void
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
 * init_marker( ): Set the marker ###MAP###, if the current template hasn't any map-marker
 *
 * @param	string		$template: Current HTML template
 * @return	array		$template: Template with map marker
 * @version 3.9.6
 * @since   3.9.6
 */
  private function init_marker( $template )
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
 * render_map( ): Set the marker ###MAP###, if the current template hasn't any map-marker
 *
 * @param	string		$pObj_template: current HTML template of the parent object
 * @return	array		$pObj_template: parent object template with map marker
 * @version 3.9.6
 * @since   3.9.6
 */
  private function render_map( $pObj_template )
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
      // Add css to the HTML header

    $this->css_setHeader( );
      // Add css to the HTML header



      //////////////////////////////////////////////////////////////////////
      //
      // Add javascripts to the HTML header

    $this->jss_setHeader( );
      // Add javascripts to the HTML header



      //////////////////////////////////////////////////////////////////////
      //
      // Substitute marker

    $markerArray['###MAP###']               = $this->marker_map( );
//    $markerArray['###FORM_FILTER###']       = $this->marker_formFilter( );
//    $markerArray['###DIV_MAP###']           = $this->marker_divMap( );
//    $markerArray['###SCRIPT_RENDERMAP###']  = $this->marker_jssRenderMap( );
//    $markerArray['###SCRIPT_FILTER###']     = $this->marker_jssFilter( );
    $map_template = $this->pObj->cObj->substituteMarkerArray( $map_template, $markerArray );
      // Substitute marker



//var_dump( __METHOD__ . ' (' . __LINE__ . '): ', $markerArray, $map_template );
      // Replace the map marker in the template of the parent object
    $pObj_template = str_replace( $str_mapMarker, $map_template, $pObj_template );

      // RETURN the template
    return $pObj_template;
  }









  /***********************************************
  *
  * Marker
  *
  **********************************************/








  /**
 * marker_map( ): get the content for the current marker
 *
 * @return	string		$content: current content
 * @version 4.1.0
 * @since   4.1.0
 */
  private function marker_map( )
  {
    $cObj_name  = $this->confMap['marker.']['map'];
    $cObj_conf  = $this->confMap['marker.']['map.'];
    $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    return $content;
  }








  /**
 * marker_divMap( ): get the content for the current marker
 *
 * @return	string		$content: current content
 * @version 3.9.6
 * @since   3.9.6
 */
  private function marker_divMap( )
  {
    $cObj_name  = $this->confMap['marker.']['div_map'];
    $cObj_conf  = $this->confMap['marker.']['div_map.'];
    $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    return $content;
  }








  /**
 * marker_formFilter( ): get the content for the current marker
 *
 * @return	string		$content: current content
 * @version 3.9.6
 * @since   3.9.6
 */
  private function marker_formFilter( )
  {
    $cObj_name  = $this->confMap['marker.']['form_filter'];
    $cObj_conf  = $this->confMap['marker.']['form_filter.'];
    $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    return $content;
  }








  /**
 * marker_jssFilter( ): get the content for the current marker
 *
 * @return	string		$content: current content
 * @version 3.9.6
 * @since   3.9.6
 */
  private function marker_jssFilter( )
  {
    $cObj_name  = $this->confMap['marker.']['jss_filter'];
    $cObj_conf  = $this->confMap['marker.']['jss_filter.'];
    $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    return $content;
  }








  /**
 * marker_jssRenderMap( ): get the content for the current marker
 *
 * @return	string		$content: current content
 * @version 3.9.6
 * @since   3.9.6
 */
  private function marker_jssRenderMap( )
  {
    $cObj_name  = $this->confMap['marker.']['jss_renderMap'];
    $cObj_conf  = $this->confMap['marker.']['jss_renderMap.'];
    $content    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    return $content;
  }









  /***********************************************
  *
  * CSS
  *
  **********************************************/









  /**
 * css_setHeader( ): Include CSS for openStreetMap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function css_setHeader( )
  {
    $name_prefix = 'css_';

      // Include openStreetMap
    $name         = $name_prefix . 'openStreetMap';
    $path         = $this->confMap['template.']['css'];
    $bool_inline  = $this->confMap['template.']['css']['inline'];
    $path_tsConf  = 'template.css';
    $this->pObj->objJss->addFile($path, false, $name, $path_tsConf, 'css', $bool_inline);
      // Include openStreetMap
  }









  /***********************************************
  *
  * JavaScripts
  *
  **********************************************/









  /**
 * jss_setHeader( ): Include JSS for openStreetMap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function jss_setHeader( )
  {
    $name_prefix = 'jss_';

      // Include openLayers
    $name         = $name_prefix . 'openLayers';
    $path         = $this->confMap['javascripts.']['lib.']['openLayers'];
    $bool_inline  = $this->confMap['javascripts.']['lib.']['openLayers.']['inline'];
    $path_tsConf  = 'javascripts.lib.openLayers';
    $this->pObj->objJss->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
      // Include openLayers

      // Include openStreetMap
    $name         = $name_prefix . 'openStreetMap';
    $path         = $this->confMap['javascripts.']['lib.']['openStreetMap'];
    $bool_inline  = $this->confMap['javascripts.']['lib.']['openStreetMap.']['inline'];
    $path_tsConf  = 'javascripts.lib.openStreetMap';
    $this->pObj->objJss->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
      // Include openStreetMap

      // Include config
    $name         = $name_prefix . 'config';
    $path         = $this->confMap['javascripts.']['config'];
    $bool_inline  = $this->confMap['javascripts.']['config.']['inline'];
    $path_tsConf  = 'javascripts.lib.config';
    $this->pObj->objJss->addFile($path, false, $name, $path_tsConf, 'jss', $bool_inline);
      // Include config
  }








}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_map.php']);
}
?>
