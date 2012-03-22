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
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The class tx_browser_pi1_navi_indexBrowser bundles methods for the index browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.11
 * @since       3.9.9
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   79: class tx_browser_pi1_navi_indexBrowser
 *  150:     public function __construct($parentObj)
 *
 *              SECTION: Main
 *  182:     public function get( $content )
 *  241:     private function get_tabs( )
 *
 *              SECTION: requirements
 *  288:     private function requirements_check( )
 *  333:     private function tableField_check( )
 *  383:     private function tableField_init( )
 *
 *              SECTION: tabs
 *  472:     private function tabs_init( )
 *  521:     private function tabs_initAttributes( $csvAttributes )
 *  573:     private function tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $displayWoItems )
 *  626:     private function tabs_initSpecialChars( $arrCsvAttributes )
 *
 *              SECTION: special chars
 *  685:     private function specialChars( )
 *  724:     private function specialChars_addSum( $row )
 *  773:     private function specialChars_addSumToTab( $res )
 *  802:     private function specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset )
 *  888:     private function specialChars_setSqlFindInSet( $row )
 *  914:     private function specialChars_setSqlLength( )
 *
 *              SECTION: SQL charset
 *  986:     private function sqlCharsetGet( )
 * 1019:     private function sqlCharsetSet( $sqlCharset )
 *
 *              SECTION: downward compatibility
 * 1059:     private function getMarkerIndexbrowser( )
 * 1105:     private function getMarkerIndexbrowserTabs( )
 *
 * TOTAL FUNCTIONS: 20
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_navi_indexBrowser
{

    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

  var $conf       = false;
    // [Array] The current TypoScript configuration array
  var $mode       = false;
    // [Integer] The current mode (from modeselector)
  var $view       = false;
    // [String] 'list' or 'single': The current view
  var $conf_view  = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_path  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
    // Variables set by the pObj (by class.tx_browser_pi1.php)


    // [Array] Array with tabIds and tabLabels
  var $indexBrowserTab = array( );
//  'tabSpecial' =>
//    'default' => '0',
//    'all' => 0,
//    'others' => 25,
//  'tabIds' =>
//    0 =>
//      'label' => 'Alle',
//      'displayWoItems' => '1',
//      'sum' => 0,
//      'special' => 'all',
//    1 =>
//    ...
//  'tabLabels' =>
//    'Alle' => 0,
//    '0-9' => 1,
//    'A' => 2,
//    ...
//  'attributes' =>
//    0 =>
//      'tabLabel' => '0-9',
//      'tabId' => 1,
//    ...
//    'Z' =>
//      'tabLabel' => 'XYZ',
//      'tabId' => 24,

    // [String] table.field of the index browser
  var $indexBrowserTableField = null;
    // [Array] Array with the find in set statements for special chars
  var $findInSet = array( );











 /**
  * Constructor. The method initiate the parent object
  *
  * @param	object		The parent object
  * @return	void
  * @version  3.9.9
  * @since    3.9.9
  */
  public function __construct($parentObj)
  {
    // Set the Parent Object
    $this->pObj = $parentObj;
      // 111023, uherrmann, #9912: t3lib_div::convUmlauts() is deprecated
    $this->t3lib_cs_obj = t3lib_div::makeInstance('t3lib_cs');
  }









    /***********************************************
    *
    * Main
    *
    **********************************************/



/**
 * get( ): Get the index browser. It has to replace the subpart in the current content.
 *
 * @param	string		$content: current content
 * @return	array
 * @version 3.9.11
 * @since   3.9.9
 */
  public function get( $content )
  {
    $arr_return['data']['content'] = $content;

      // RETURN: requirements aren't met
    if( ! $this->requirements_check( ) )
    {
       // #35032, 120320
      $markerIndexbrowser = $this->getMarkerIndexbrowser( );
      $content = $this->pObj->cObj->substituteSubpart( $content, $markerIndexbrowser, null, true );
      $arr_return['data']['content'] = $content;
      return $arr_return;
    }
      // RETURN: requirements aren't met

      // RETURN : table is not the local table
    $arr_return = $this->tableField_check( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : table is not the local table

      // Init the tabs
    $arr_return = $this->tabs_init( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Init the tabs

      // Render the tabs
    $arr_return = $this->get_tabs( );
$this->pObj->dev_var_dump( $this->indexBrowserTab );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];
      // Render the tabs


      // :TODO:
      // Move $GLOBALS['TSFE']->id temporarily
      // Get the index browser rows (uid, initialField)
      // Count the hits per tab, prepaire the tabArray
      // Build the index browser template

    return $arr_return;
  }



/**
 * get_tabs( ):
 *
 * @return	boolean		true / false
 * @version 3.9.11
 * @since   3.9.9
 */
  private function get_tabs( )
  {
      // Take care of special chars
    $arr_return = $this->specialChars( );
    if( ! ( empty ( $arr_return ) ) )
    {
      return $arr_return;
    }


      // Take care of filters

    $arr_return['data']['rows'] = null;

    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = '<h1 style="color:red">Error index browser</h1>';
    $arr_return['error']['prompt'] = '<p style="color:red">No rows.</p>';

    return $arr_return;
  }









    /***********************************************
    *
    * requirements
    *
    **********************************************/



/**
 * requirements_check( ): Checks
 *                        * configuration of the flexform
 *                        * configuration of TS tabs
 *                        It returns false, if a requirement isn't met
 *
 * @return	boolean		true / false
 * @version 3.9.11
 * @since   3.9.9
 */
  private function requirements_check( )
  {
      // RETURN false : index browser is disabled
    if( ! $this->pObj->objFlexform->bool_indexBrowser )
    {
      if( $this->pObj->b_drs_navi )
      {
        $prompt = 'display.indexBrowser is false.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN false : index browser is disabled

      // RETURN false : index browser hasn't any configured tab
    $arr_conf_tabs = $this->conf['navigation.']['indexBrowser.']['tabs.'];
    if( ! is_array( $arr_conf_tabs ) )
    {
      // The index browser isn't configured
      if ( $this->pObj->b_drs_navi )
      {
        $prompt = 'navigation.indexBrowser.tabs hasn\'t any element.';
        t3lib_div::devlog( '[WARN/NAVIGATION] ' . $prompt, $this->pObj->extKey, 2 );
        $prompt = 'navigation.indexBrowser won\'t be processed.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN false : index browser hasn't any configured tab

      // RETURN true : requirements are OK
    return true;
  }



/**
 * tableField_check( ): Checks, if the table.field of the index browser
 *                      correspondends with the local table.
 *                      Sets the class var $indexBrowserTableField.
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.9
 */
  private function tableField_check( )
  {
      // Init the table.field
    $this->tableField_init( );

    list( $table, $field ) = explode( '.', $this->indexBrowserTableField );

      // RETURN : table is the local table
    if( $table == $this->pObj->localTable )
    {
      return;
    }
      // RETURN : table is the local table

      // Error management
    $prompt_01  = 'Sorry, the index browser can\'t handle an index for foreign tables!';
    $prompt_02  = 'Current table.field is: ' . $this->indexBrowserTableField;
    $prompt_03  = 'Local table is: ' . $this->pObj->localTable;
    $prompt_04  = 'Please configure: ' . $this->conf_path . 'indexBrowser.field = ' . $this->pObj->localTable .'... ';
    if ($this->pObj->b_drs_navi)
    {
      t3lib_div::devlog('[ERROR/NAVIGATION] ' . $prompt_01, $this->pObj->extKey, 3);
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt_02, $this->pObj->extKey, 0);
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt_03, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/NAVIGATION] ' . $prompt_04, $this->pObj->extKey, 1);
    }

    $arr_return['error']['status'] = true;
    $arr_return['error']['header'] = '<h1 style="color:red">Error Index-Browser</h1>';
    $prompt = $prompt_01 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_02 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_03 . '<br />' . PHP_EOL;
    $prompt = $prompt . $prompt_04 . '<br />' . PHP_EOL;
    $arr_return['error']['prompt'] = '<p style="color:red">' . $prompt . '</p>';
      // Error management

      // RETURN error message
    return $arr_return;
  }



/**
 * tableField_init( ):  Set the class var $this->indexBrowserTableField
 *                      Value is the table.field for SQL queries
 *
 * @return	void
 * @version 3.9.11
 * @since   3.9.9
 */
  private function tableField_init( )
  {

      // RETURN : table.field for the index browser form is set in the current view
    if( isset( $this->conf_view['navigation.']['indexBrowser.']['field'] ) )
    {
      $this->indexBrowserTableField = $this->conf_view['navigation.']['indexBrowser.']['field'];
      if( ! empty ( $this->indexBrowserTableField ) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = $this->conf_path . 'indexBrowser.field is ' . $field;
          t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return;
      }
    }
      // RETURN : table.field for the index browser form is set in the current view

      // RETURN : table.field for the index browser form is set in global configuration
    if( isset( $this->conf['navigation.']['indexBrowser.']['field'] ) )
    {
      $this->indexBrowserTableField = $this->conf['navigation.']['indexBrowser.']['field'];
      if( ! empty ( $this->indexBrowserTableField ) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = 'indexBrowser.field is ' . $field;
          t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
        }
        return;
      }
    }
      // RETURN : table.field  for the index browser form is set in global configuration

      // The user hasn't defined a table.field element.
      // We take the first one of the field views.list.X.select

      // Get the first table of the global arr_realTables_arrFields
    reset( $this->pObj->arr_realTables_arrFields );
    $table = key( $this->pObj->arr_realTables_arrFields );
      // First field of the current table
    $field = $this->pObj->arr_realTables_arrFields[$table][0];
    $this->indexBrowserTableField = $table . '.' . $field;
      // Get the first table of the global arr_realTables_arrFields

      // DIE : undefined error
    if( empty ( $this->indexBrowserTableField ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): undefined error!');
    }
      // DIE : undefined error

      // DRS
    if( $this->pObj->b_drs_navi )
    {
      $prompt = 'indexBrowser.field is the first table.field from ' .
                $this->conf_path . 'select: ' . $this->indexBrowserTableField;
      t3lib_div::devlog('[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0);
      $prompt = 'If you need another table.field use ' . $this->conf_path . 'indexBrowser.field';
      t3lib_div::devlog('[HELP/NAVIGATION] ' . $prompt, $this->pObj->extKey, 1);
    }
      // DRS

  }









    /***********************************************
    *
    * tabs
    *
    **********************************************/



/**
 * tabs_init( ):    Sets the class var $indexBrowserTab
 *
 * @return	array		$arr_return: Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_init( )
  {
      // Get tabSpecial property default
    $this->indexBrowserTab['tabSpecial']['default'] = null;
    if( isset( $this->conf['navigation.']['indexBrowser.']['defaultTab'] ) )
    {
      $this->indexBrowserTab['tabSpecial']['default'] = $this->conf['navigation.']['indexBrowser.']['defaultTab'];
    }
      // Get tabSpecial property default

      // Get default property display tabs without any item
    $defaultDisplayWoItems  = $this->conf['navigation.']['indexBrowser.']['display.']['tabWithoutItems'];

      // LOOP tabs TS configuratione array
    $conf_tabs = $this->conf['navigation.']['indexBrowser.']['tabs.'];
    foreach( ( array ) $conf_tabs as $tabId => $tabLabel )
    {
        // CONTINUE : key is an array
      if( substr( $tabId, -1 ) == '.' )
      {
        continue;
      }
        // CONTINUE : key is an array

        // Get attributes
      $csvAttributes      = str_replace(' ', null, $csvAttributes);
      $arrCsvAttributes[] = $csvAttributes;

        // Init tab attributes
      $this->tabs_initAttributes( $csvAttributes  );
        // Init tab properties
      $this->tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $defaultDisplayWoItems );
    }
      // LOOP tabs TS configuratione array

      // Init special chars
    $this->tabs_initSpecialChars( $arrCsvAttributes );
  }



/**
 * tabs_initAttributes( ):  Sets the array attributes of the class var $indexBrowserTab
 *
 * @param	string		$csvAttributes  : attributes
 * @return	array		$arr_return     : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initAttributes( $csvAttributes )
  {
      // RETURN : no attributes
    if( empty ( $csvAttributes ) )
    {
      return;
    }
      // RETURN : no attributes

      // LOOP : attributes
    $attributes = explode( ',', $csvAttributes );
    foreach( $attributes as $attribute )
    {
        // DRS : ERROR : attribute is part of two tabs at least
      if( isset ( $this->indexBrowserTab['attributes'][ $attribute ]) )
      {
        if( $this->pObj->b_drs_navi )
        {
          $prompt = 'The tab attribute ' . $attribute . ' is part of two tabs at least!';
          t3lib_div::devlog( '[ERROR/NAVI] ' . $prompt, $this->pObj->extKey, 3 );
          $prompt = 'You will get an unproper result for the index browser';
          t3lib_div::devlog( '[WARN/NAVI] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = $attribute . ' is part of tab[' . $this->indexBrowserTab['attributes'][ $attribute ][ 'tabLabel' ] . '] '.
                    ' and of tab[' . $tabLabel . '] at least!';
          t3lib_div::devlog( '[WARN/NAVI] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Please take care of a proper TypoScript configuration!';
          t3lib_div::devlog( '[HELP/NAVI] ' . $prompt, $this->pObj->extKey, 1 );
        }
      }
        // DRS : ERROR : attribute is part of two tabs at least

        // Set class var
      $this->indexBrowserTab['attributes'][ $attribute ][ 'tabLabel' ] = $tabLabel;
      $this->indexBrowserTab['attributes'][ $attribute ][ 'tabId' ]    = $tabId;
    }
      // LOOP : attributes
  }



/**
 * tabs_initProperties( ):  Sets the elements tabIds and tabLabels of the class var $indexBrowserTab
 *                          Updates the element tabSpecial.
 *
 * @param	array		$conf_tabs      : TS configuration array
 * @param	integer		$tabId          : Current tab ID for TS configuration array
 * @param	string		$tabLabel       : Label of the current tab
 * @param	boolean		$displayWoItems : Default value for displaying tabs without any hit
 * @return	array		$arr_return     : Contains an error message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initProperties( $conf_tabs, $tabId, $tabLabel, $displayWoItems )
  {
      // Overwrite tab label in case of stdWrap
    if( $conf_tabs[$tabId . '.']['stdWrap.'] )
    {
      $stdWrap  = $conf_tabs[$tabId . '.']['stdWrap.'];
      $tabLabel = $this->pObj->objWrapper->general_stdWrap( $tabLabel, $stdWrap );
    }
      // Overwrite tab label in case of stdWrap

      // Overwrite property display without items
    if( isset ( $conf_tabs[$tabId . '.']['displayWithoutItems'] ) )
    {
      $displayWoItems = $conf_tabs[$tabId . '.']['displayWithoutItems'];
    }
      // Overwrite property display without items

      // Set tab array
    $this->indexBrowserTab['tabIds'][$tabId]['label']           = $tabLabel;
    $this->indexBrowserTab['tabIds'][$tabId]['displayWoItems']  = $displayWoItems;
    $this->indexBrowserTab['tabIds'][$tabId]['sum']             = 0;
    $this->indexBrowserTab['tabLabels'][$tabLabel]              = $tabId;
      // Set tab array

      // RETURN : tab with special value 'all'
    if( $conf_tabs[$tabId . '.']['special'] == 'all' )
    {
      $this->indexBrowserTab['tabIds'][$tabId]['special'] = 'all';
      $this->indexBrowserTab['tabSpecial']['all']         = $tabId;
      return;
    }
      // RETURN : tab with special value 'all'

      // RETURN : tab with special value 'others'
    if( $conf_tabs[$tabId . '.']['special'] == 'others' )
    {
      $this->indexBrowserTab['tabIds'][$tabId]['special'] = 'others';
      $this->indexBrowserTab['tabSpecial']['others']      = $tabId;
      return;
    }
      // RETURN : tab with special value 'others'
  }



/**
 * tabs_initSpecialChars( ): Inits the array initials of the class var $indexBrowserTab
 *
 * @param	array		$arrCsvAttributes : initials from the tab TS configuration
 * @return	void
 * @version 3.9.11
 * @since   3.9.10
 */
  private function tabs_initSpecialChars( $arrCsvAttributes )
  {
      // Get initials unique
    $arrCsvAttributes  = array_unique( $arrCsvAttributes );
    $csvInitials  = implode( ',', ( array ) $arrCsvAttributes );

      // Init vars with all initials
    $this->indexBrowserTab['initials']['all']           = $csvInitials;
    $this->indexBrowserTab['initials']['specialChars']  = null;
    $this->indexBrowserTab['initials']['alphaNum']      = null;

      // UTF-8 decode
    $subject = utf8_decode( $csvInitials  );

      // Init var with special chars
    $pattern = '/[^0-9a-zA-Z,]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexBrowserTab['initials']['specialChars'] = utf8_encode( $specialChars );
    }
      // Init var with special chars

      // Init var with alpha numeric chars
    $pattern = '/[0-9a-zA-Z]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexBrowserTab['initials']['alphaNum'] = utf8_encode( $specialChars );
    }
      // Init var with alpha numeric chars
  }









    /***********************************************
    *
    * special chars
    *
    **********************************************/






/**
 * specialChars( ): Updates sum / number of hits of sepcial chars
 *
 * @return	array		$arr_return : Contains an erreor message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars( )
  {
      // RETURN : no special chars
    if( empty ( $this->indexBrowserTab['initials']['specialChars'] ) )
    {
      return;
    }
      // RETURN : no special chars

      // Get a row with the SQL length for each special char
    $arr_return = $this->specialChars_setSqlLength( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $row = $arr_return['data']['row'];
    unset( $arr_return );
      // Get a row with the SQL length for each special char

      // Get the sum for each special char initial
    $arr_return = $this->specialChars_addSum( $row );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the sum for each special char initial

  }



/**
 * specialChars_addSum( ): Updates sum / number of hits of sepcial chars
 *
 * @param	array		$row        : Row with special chars and their SQL length
 * @return	array		$arr_return : Contains an erreor message in case of an error
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars_addSum( $row )
  {
      // Get current table.field of the index browser
    list( $table, $field) = explode( '.', $this->indexBrowserTableField);

      // Get current SQL char set
    $currSqlCharset = $this->sqlCharsetGet( );
      // Set SQL char set to latin1
    $this->sqlCharsetSet( 'latin1' );

      // Set class var findInSet
    $this->specialChars_setSqlFindInSet( $row );

      // LOOP : find in set for each special char length group
    foreach( $this->findInSet as $length => $arrfindInSet )
    {
        // SQL result with sum for records with a sepecial char as first character
      $arr_return = $this->specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset );
      if( $arr_return['error']['status'] )
      {
        return $arr_return;
      }
      $res = $arr_return['data']['res'];
        // SQL result with sum for records with a sepecial char as first character

        // Add the sum to the tab with the special char attribute
      $this->specialChars_addSumToTab( $res );

        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
    }
      // LOOP : find in set for each special char length group

      // Reset SQL char set
    $this->sqlCharsetSet( $currSqlCharset );
  }



/**
 * specialChars_addSumToTab( ): Updates the sum in the arrays tabIds and attributes
 *                              of the class var $indexBrowserTab
 *
 * @param	array		$res  : SQL result
 * @return	[type]		...
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars_addSumToTab( $res )
  {
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $attribute  = $row[ 'initial' ];
      $count      = $row[ 'count' ];
      $tabId      = $this->indexBrowserTab[ 'attributes' ][ $attribute ][ 'tabId' ];
      $currSum    = $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ];
      $sum        = $currSum + $count;
      $this->indexBrowserTab[ 'tabIds' ][ $tabId ][ 'sum' ]       = $sum;
      $this->indexBrowserTab['attributes'][ $attribute ][ 'sum' ] = $sum;
    }
  }



/**
 * specialChars_resSqlCount( ): SQL query and execution for counting
 *                              special char initials
 *
 *                                                an error message in case of an error
 *
 * @param	integer		$length         : SQL length of special chars group
 * @param	array		$arrfindInSet   : FIND IN SET statement with proper length
 * @param	string		$currSqlCharset : Current SQL charset for reset in error case
 * @return	array		$arr_return     : SQL ressource or
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars_resSqlCount( $length, $arrfindInSet, $currSqlCharset )
  {
    static $drsPrompt = true;

    // DRS
    if( $drsPrompt && $this->pObj->b_drs_devTodo )
    {
      $prompt = 'Query needs an and where in case of filter';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
      $drsPrompt = false;
    }
      // DRS

      // Get current table.field of the index browser
    list( $table, $field) = explode( '.', $this->indexBrowserTableField);

      // Query for all filter items
    $select   = "COUNT( * ) AS 'count', LEFT ( " . $field . ", " . $length . " ) AS 'initial'";
    $from     = $table;
    $where    = "(" . implode ( " OR ", $arrfindInSet ) . ")";
    $where    = $where . $this->pObj->cObj->enableFields( $table );
    $groupBy  = "LEFT ( " . $field . ", " . $length . " )";
    $orderBy  = "LEFT ( " . $field . ", " . $length . " )";
    $limit    = null;

      // Get query
    $query  = $GLOBALS['TYPO3_DB']->SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );
      // Execute query
    $res    = $GLOBALS['TYPO3_DB']->exec_SELECTquery
              (
                $select,
                $from,
                $where,
                $groupBy,
                $orderBy,
                $limit
              );

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );
        // Reset SQL charset
      $this->sqlCharsetSet( $currSqlCharset );
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Return SQL result
    $arr_return['data']['res'] = $res;
    return $arr_return;
  }



/**
 * specialChars_setSqlFindInSet( ): Set the FIND IN SET statement for each special char group.
 *                                  A special char group is grouped by the length of a special
 *                                  char.
 *
 * @param	array		$row  : Row with special chars and their SQL length
 * @return	[type]		...
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars_setSqlFindInSet( $row )
  {
      // Get current table.field of the index browser
    list( $table, $field) = explode( '.', $this->indexBrowserTableField);

      // LOOP : generate a find in set statement for each special char
    foreach( $row as $char => $length )
    {
      if( $length < 2 )
      {
        continue;
      }
      $this->findInSet[$length][] = "FIND_IN_SET( LEFT ( " . $field . ", " . $length . " ), '" . $char . "' )";
    }
      // LOOP : generate a find in set statement for each special char
  }



/**
 * specialChars_setSqlLength( ): Return a row with all special chars and their SQL length
 *
 * @return	array		$arr_return : row with all special chars and their SQL length
 * @version 3.9.11
 * @since   3.9.10
 */
  private function specialChars_setSqlLength( )
  {
      // Build the select statement parts for the length of each special char
    $arrStatement     = array( );
    $arrSpecialChars  = explode( ',', $this->indexBrowserTab['initials']['specialChars'] );
    foreach( ( array ) $arrSpecialChars as $specialChar )
    {
      $arrStatement[] = "LENGTH ( '" . $specialChar . "' ) AS '" . $specialChar . "'";
    }
      // Build the select statement parts for the length of each special char

      // DIE : undefined error
    if( empty ( $arrStatement ) )
    {
      die ( __METHOD__ . '(' . __LINE__ . '): undefined error.');
    }
      // DIE : undefined error

      // Execute query for the length of each special char
    $query  = "SELECT " . implode( ', ', $arrStatement );
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // Error management
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
    if( $error )
    {
      $this->pObj->objSqlFun->query = $query;
      $this->pObj->objSqlFun->error = $error;
      $arr_return = $this->pObj->objSqlFun->prompt_error( );
      return $arr_return;
    }
      // Error management

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/NAVI+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS

      // Get the row
    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );
      // SQL free result
    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    $arr_return['data']['row'] = $row;
    return $arr_return;
  }








    /***********************************************
    *
    * SQL charset
    *
    **********************************************/



/**
 * sqlCharsetGet( ):  Get the current SQL charset like latin1 or utf8.
 *
 * @return	string		$charset  : current charset
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sqlCharsetGet( )
  {
      // Query
    $query  = "SHOW VARIABLES LIKE 'character_set_client';";
      // Execute
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // RETURN
    $row    = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

    if( empty ( $row ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): row is empty. Query: ' . $query );
    }

    $charset = $row['Value'];
    if( empty ( $charset ) )
    {
      die( __METHOD__ . '(' . __LINE__ . '): row[Value] is empty. Query: ' . $query );
    }
    return $charset;
  }



/**
 * sqlCharsetSet( ):  Execute SET NAMES with given charset
 *
 * @param	string		$sqlCharset : SQL charset like latin1 or utf8
 * @return	[type]		...
 * @version 3.9.9
 * @since   3.9.9
 */
  private function sqlCharsetSet( $sqlCharset )
  {
    $query  = "SET NAMES " . $sqlCharset . ";";
    $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );

      // DRS
    if( $this->pObj->b_drs_navi || $this->pObj->b_drs_sql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/FILTER+SQL] ' . $prompt, $this->pObj->extKey, -1 );
    }
      // DRS
  }









    /***********************************************
    *
    * downward compatibility
    *
    **********************************************/



 /**
  * getMarkerIndexbrowser( ): Downward compatibility for ###INDEXBROWSER###
  *                           If ###AZSELECTOR### is used in an HTML template
  *                           ###AZSELECTOR### will return
  *                           * Feature: #35032
  *
  * @return	string		###INDEXBROWSER### || ###AZSELECTOR###
  * @version  3.9.10
  * @since    3.9.10
  */
  private function getMarkerIndexbrowser( )
  {
      // DRS
    if ($this->pObj->b_drs_devTodo)
    {
      $prompt = 'Task #35037: Don\'t support the marker ###AZSELECTOR### from version 5.x';
      t3lib_div::devlog('[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS

      // get th current content
    $template = $this->currContent;

      // RETURN ###AZSELECTOR###, if ###AZSELECTOR### is part of the current content
    $pos = strpos( $template, '###AZSELECTOR###');
    if ( ! ( $pos === false ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = 'The current template contains the marker ###AZSELECTOR###';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = '###AZSELECTOR### won\'t supported from version 5.x';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'Please move it from ###AZSELECTOR### to ###INDEXBROWSER###';
        t3lib_div::devlog('[TODO/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
      }
      return '###AZSELECTOR###';
    }
      // RETURN ###AZSELECTOR###, if ###AZSELECTOR### is part of the current content

      // RETURN ###INDEXBROWSER###
    return '###INDEXBROWSER###';
  }



 /**
  * getMarkerIndexbrowserTabs( ): Downward compatibility for ###INDEXBROWSERTABS###
  *                               If ###AZSELECTORTABS### is used in an HTML template
  *                               ###AZSELECTORTABS### will return
  *                               * Feature: #35032
  *
  * @return	string		###INDEXBROWSERTABS### || ###AZSELECTORTABS###
  * @version  3.9.10
  * @since    3.9.10
  */
  private function getMarkerIndexbrowserTabs( )
  {
      // DRS
    if ($this->pObj->b_drs_devTodo)
    {
      $prompt = 'Task #35037: Don\'t support the marker ###AZSELECTORTABS### from version 5.x';
      t3lib_div::devlog('[INFO/TODO] ' . $prompt, $this->pObj->extKey, 0);
    }
      // DRS

      // get th current content
    $template = $this->currContent;

      // RETURN ###AZSELECTORTABS###, if ###AZSELECTORTABS### is part of the current content
    $pos = strpos( $template, '###AZSELECTORTABS###');
    if ( ! ( $pos === false ) )
    {
      if ($this->pObj->b_drs_warn)
      {
        $prompt = 'The current template contains the marker ###AZSELECTORTABS###';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = '###AZSELECTORTABS### won\'t supported from version 5.x';
        t3lib_div::devlog('[WARN/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
        $prompt = 'Please move it from ###AZSELECTORTABS### to ###INDEXBROWSERTABS###';
        t3lib_div::devlog('[TODO/DEPRECATED] ' . $prompt, $this->pObj->extKey, 1);
      }
      return '###AZSELECTORTABS###';
    }
      // RETURN ###AZSELECTORTABS###, if ###AZSELECTORTABS### is part of the current content

      // RETURN ###INDEXBROWSERTABS###
    return '###INDEXBROWSERTABS###';
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_indexBrowser.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_navi_indexBrowser.php']);
}

?>
