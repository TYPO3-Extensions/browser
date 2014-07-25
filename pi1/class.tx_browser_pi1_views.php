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
 * The class tx_browser_pi1_views bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 * @version 5.0.0
 * @since 1.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   53: class tx_browser_pi1_views
 *   72:     function __construct($parentObj)
 *
 *              SECTION: Building the views
 *  113:     function singleView( )
 *
 *              SECTION: Helper
 *  754:     public function displayThePlugin( )
 *
 * TOTAL FUNCTIONS: 3
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_views
{

  var $arr_select;
  // Array with the fields of the SQL result
  var $arr_orderBy;
  // Array with fields from orderBy from TS
  var $arr_rmFields;

  // Array with fields from functions.clean_up.csvTableFields from TS

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
   * Building the views
   *
   * ******************************************** */


  /*   * *********************************************
   *
   * Helper
   *
   * ******************************************** */

  /**
   * displayThePlugin( ): The Method checks, if the plugin should controlled by URL parameters.
   *                      Parameters are defined in the flexform or TypoScript.
   *                      Conditions
   *                      * URL Parameter is in the list for hiding this plugin
   *                        returns false
   *                      * URL Parameter is in the list for displaying this plugin
   *                        returns true, if it is in the list
   *                        returns false, if it isn't in the list
   *                      * If a paremeter is defined like tx_browser_pi1[showUid],
   *                        the method doesn't check any value of the GP parameter
   *                      * If a paremeter is defined like tx_browser_pi1[showUid]=123
   *                        the method checks the value of the GP parameter.
   *                        It returns true only, if value is met.
   *                      * If a paremeter is defined like tx_browser_pi1[*],
   *                        the methord returns true, if the GP parameter tx_browser_pi1 contains
   *                        one element at least.
   *                      It takes account of GP parameters from first to third level only.
   *                      It takes account for any paramter, but not piVars only.
   *
   *                      * if the plugin should not controlled by URL parameter or
   *                      * if the plugin meets the conditions
   *                      False
   *                      * if the plugin doesn't meet the conditions
   *
   * @return	boolean		True,
   * @version 3.9.3
   * @since 3.9.3
   */
  public function displayThePlugin()
  {
    $sheet = 'sDEF';
    $field_1 = 'controlling';
    $field_2 = 'enabled';



    //////////////////////////////////////////////////////////////////////
    //
      // RETURN true: Plugin shouldn't controlled by URL parameters

    $coa_name = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 ];
    $coa_conf = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 . '.' ];
    $value = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
    if ( !$value )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'RETURN. Plugin shouldn\'t controlled by URL parameters';
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
    }
    // RETURN true. Plugin shouldn't controlled by URL parameters
    //////////////////////////////////////////////////////////////////////
    //
      // Build the arr_GPparams
    // Merge $_POST and $_GET ($Post has precedence)
    $GP = t3lib_div::_POST() + t3lib_div::_GET();
    $GP = array_unique( $GP );
    // Merge $_POST and $_GET ($Post has precedence)

    $arr_GPparam = null;
    $str_GPparam = null;
    // LOOP first level
    foreach ( $GP as $key_01 => $value_01 )
    {
      // Element is an array
      if ( is_array( $value_01 ) )
      {
        // LOOP second level
        foreach ( $value_01 as $key_02 => $value_02 )
        {
          // Element is an array
          if ( is_array( $value_02 ) )
          {
            // LOOP third level
            foreach ( $value_02 as $key_03 => $value_03 )
            {
              // Element is an array
              // ERROR: param array is an array. This won't handled.
              if ( is_array( $value_03 ) )
              {
                if ( $this->pObj->b_drs_error )
                {
                  $param = $key_01 . '[' . $key_02 . '][' . $key_03 . ']';
                  $prompt_01 = 'ERROR: URL parameter can\'t evaluate. Parameter is a forth dimensional array at least: \'' . $param . '\'';
                  $prompt_02 = 'HELP: The PHP code of the browser has to adapt to this requirement. Please publish it in the typo3-browser-forum.de.';
                  t3lib_div::devLog( '[ERROR/TEMPLATING] ' . $prompt_01, $this->pObj->extKey, 3 );
                  t3lib_div::devLog( '[HELP/TEMPLATING] ' . $prompt_02, $this->pObj->extKey, 1 );
                }
                $arr_GPparam[ $key_01 . '[' . $key_02 . '][' . $key_03 . '][*]' ] = null;
                $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][' . $key_03 . '][*]';
                continue;
              }
              // Element is an array
              // ERROR: param array is an array. This won't handled.
              // Set the param array
              $arr_GPparam[ $key_01 . '[' . $key_02 . '][' . $key_03 . ']' ] = $value_03;
              $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][' . $key_03 . ']=' . $value_03;
            }
            // LOOP third level
            $arr_GPparam[ $key_01 . '[' . $key_02 . '][*]' ] = null;
            $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . '][*]';
            continue;
          }
          // Set the param array
          $arr_GPparam[ $key_01 . '[' . $key_02 . ']' ] = $value_02;
          $str_GPparam = $str_GPparam . ', ' . $key_01 . '[' . $key_02 . ']=' . $value_02;
        }
        // LOOP second level
        $arr_GPparam[ $key_01 . '[*]' ] = null;
        $str_GPparam = $str_GPparam . ', ' . $key_01 . '[*]';
        continue;
      }
      // Element is an array
      // Set the param
      $arr_GPparam[ $key_01 ] = $value_01;
      $str_GPparam = $str_GPparam . ', ' . $key_01 . '=' . $value_01;
    }
    // LOOP first level
    if ( $str_GPparam )
    {
      $str_GPparam = ltrim( $str_GPparam, ', ' );
    }
    // Build the arr_GPparams
    //////////////////////////////////////////////////////////////////////
    //
      // RETURN false: Parameter is in the list for hiding this plugin
    // Get the csv list as an array out of the TypoScript
    $field_1 = 'controlling';
    $field_2 = 'adjustment';
    $field_3 = 'hide_if_in_list';
    $field = $field_1 . '.' . $field_2 . '.' . $field_3;
    $coa_name = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 . '.' ][ $field_3 ];
    $coa_conf = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 . '.' ][ $field_3 . '.' ];
    $csvValues = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
    $csvArray = $this->pObj->objZz->getCSVasArray( $csvValues );
    // Get the csv list as an array out of the TypoScript
    // LOOP each parameter from csv list
    foreach ( $csvArray as $param )
    {
      // CONTINUE $csvArray is empty
      if ( empty( $param ) )
      {
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'The list of URL parameter for hiding this plugin doesn\'t contain any parameter.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        continue;
      }
      // CONTINUE $csvArray is empty
      // Get key=value pair
      list( $paramKey, $paramValue) = explode( '=', $param );
      $paramKey = trim( $paramKey );
      $paramValue = trim( $paramValue );
      // Get key=value pair
      // Key is part of the URL
      // #50214, 130720, dwildt, 1-
      // if( in_array( $paramKey, array_keys ( $arr_GPparam ) ) )
      // #50214, 130720, dwildt, 1+
      if ( in_array( $paramKey, array_keys( ( array ) $arr_GPparam ) ) )
      {
        // SWITCH conditions
        switch ( true )
        {
          case(!( $paramValue == '' ) ):
          case(!( $paramValue == null ) ):
            // A value is defined
            // RETURN false: value meets URL value
            if ( $arr_GPparam[ $paramKey ] == $paramValue )
            {
              if ( $this->pObj->b_drs_templating )
              {
                $prompt = 'The list of URL parameter for hiding this plugin contains ' . $param . '.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
                $prompt = 'And ' . $param . ' is part of the URL. This plugin will hidden.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              }
              return false;
            }
            // RETURN false: value meets URL value
            // GO ON: value doesn't meet URL value
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of URL parameter for hiding this plugin: ' . $param . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'URL parameter: ' . $str_GPparam . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
            // GO ON: value doesn't meet URL value
            break;
          // A value is defined
          default:
            // RETURN false: any value isn't defined
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of URL parameter for hiding this plugin contains ' . $paramKey . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'And ' . $paramKey . ' is part of the URL. This plugin will hidden.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
            return false;
            break;
          // RETURN false: any value isn't defined
        }
        // SWITCH conditions
      }
      // Key is part of the URL
      // Key isn't part of the URL
      // #50214, 130720, dwildt, 1-
      //if( ! ( in_array( $paramKey, array_keys ( $arr_GPparam ) ) ) )
      // #50214, 130720, dwildt, 1+
      if ( !( in_array( $paramKey, array_keys( ( array ) $arr_GPparam ) ) ) )
      {
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'The list of URL parameter for hiding this plugin contains \'' . $paramKey . '\'.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = $paramKey . ' isn\'t any part of the URL. This plugin won\'t hidden.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
      }
      // Key isn't part of the URL
    }
    // LOOP each parameter from csv list
    // RETURN false: Parameter is in the list for hiding this plugin
    //////////////////////////////////////////////////////////////////////
    //
      // RETURN true or false: Parameter is in the list for displaying this plugin
    // Get the csv list as an array out of the TypoScript
    $field_1 = 'controlling';
    $field_2 = 'adjustment';
    $field_3 = 'display_if_in_list';
    $field = $field_1 . '.' . $field_2 . '.' . $field_3;
    $coa_name = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 . '.' ][ $field_3 ];
    $coa_conf = $this->pObj->conf[ 'flexform.' ][ $sheet . '.' ][ $field_1 . '.' ][ $field_2 . '.' ][ $field_3 . '.' ];
    $csvValues = $this->pObj->cObj->cObjGetSingle( $coa_name, $coa_conf );
    $csvArray = $this->pObj->objZz->getCSVasArray( $csvValues );
    // Get the csv list as an array out of the TypoScript
    // RETURN true: $csvArray is empty
    if ( empty( $csvArray ) )
    {
      if ( $this->pObj->b_drs_templating )
      {
        $prompt = 'The list of URL parameter for display this plugin doesn\'t contain any parameter.';
        t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return true;
      continue;
    }
    // RETURN true: $csvArray is empty
    // LOOP each parameter from csv list
    foreach ( $csvArray as $param )
    {
      // Get key=value pair
      list( $paramKey, $paramValue) = explode( '=', $param );
      $paramKey = trim( $paramKey );
      $paramValue = trim( $paramValue );
      // Get key=value pair
      // Key is part of the URL
      // #50214, 130720, dwildt, 1-
      //if( in_array( $paramKey, array_keys ( $arr_GPparam ) ) )
      // #50214, 130720, dwildt, 1+
      if ( in_array( $paramKey, array_keys( ( array ) $arr_GPparam ) ) )
      {
        // SWITCH conditions
        switch ( true )
        {
          case(!( $paramValue == '' ) ):
          case(!( $paramValue == null ) ):
            // A value is defined
            // RETURN true: value meets URL value
            if ( $arr_GPparam[ $paramKey ] == $paramValue )
            {
              if ( $this->pObj->b_drs_templating )
              {
                $prompt = 'The list of needed URL parameter for displaying this plugin contains ' . $param . '.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
                $prompt = 'And ' . $param . ' is part of the URL. This plugin will displayed.';
                t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              }
              return true;
            }
            // RETURN true: value meets URL value
            break;
          // A value is defined
          default:
            // RETURN true: any value isn't defined
            if ( $this->pObj->b_drs_templating )
            {
              $prompt = 'The list of needed URL parameter for displaying this plugin contains ' . $paramKey . '.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
              $prompt = 'And ' . $paramKey . ' isn\t part of the URL. This plugin will displayed.';
              t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
            }
            return true;
            break;
          // RETURN true: any value isn't defined
        }
        // SWITCH conditions
      }
      // Key is part of the URL
    }
    // RETURN true or false: Parameter is in the list for displaying this plugin
    //////////////////////////////////////////////////////////////////////
    //

    switch ( true )
    {
      case(!( $csvValues == '' ) ):
      case(!( $csvValues == null ) ):
        // Parameters are defined
        if ( $this->pObj->b_drs_templating )
        {
          $prompt = 'This is the list of needed URL parameter for displaying this plugin: \'' . $csvValues . '\'.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'But any parameter is part of the URL. This plugin won\'t displayed.';
          t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
        }
        return false;
        break;
      // Parameters are defined
    }
    // RETURN false: Any Parameter of the list for displaying this plugin is part of the URL
    //////////////////////////////////////////////////////////////////////
    //
      // RETURN true: This plugin doesn't need any URL parameter for displaying

    if ( $this->pObj->b_drs_templating )
    {
      $prompt = 'This plugin doesn\'t need any URL parameter for displaying.';
      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
    }
    return true;
    // RETURN true: This plugin doesn't need any URL parameter for displaying
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_views.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_views.php' ]);
}
?>