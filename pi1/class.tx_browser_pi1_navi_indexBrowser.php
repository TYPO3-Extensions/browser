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
 * The class tx_browser_pi1_navi_indexBrowser bundles methods for navigation like the index browser
 * or the page broser. It is part of the extension browser
 *
 * @author      Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package     TYPO3
 * @subpackage  browser
 * @version     3.9.9
 * @since       3.9.9
 */

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_browser_pi1_navi_indexBrowser
 *  115:     public function __construct($parentObj)
 *
 *              SECTION: Index browser
 *  147:     public function get( $content )
 *  207:     private function checkRequirements( )
 *  250:     private function initTableField( )
 *  325:     private function checkTableField( )
 *  372:     private function initTabs( )
 *  461:     private function initTabsSpecialChars( $arrInitials )
 *  503:     private function rows( )
 *  533:     private function rowsInitSpecialChars( )
 *  570:     private function rowsInitSpecialCharsLength( )
 *  629:     private function rowsSumSpecialChars( $row )
 *
 *              SECTION: SQL
 *  764:     private function sqlCharsetGet( )
 *  797:     private function sqlCharsetSet( $sqlCharset )
 *
 *              SECTION: downward compatibility
 *  837:     private function getMarkerIndexbrowser( )
 *  883:     private function getMarkerIndexbrowserTabs( )
 *
 * TOTAL FUNCTIONS: 15
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
  var $indexbrowserTab = array( );
    // [String] table.field of the index browser
  var $indexBrowserTableField = null;
    // [Array] Array with the find in set statements for special chars
  var $findInSet = array( );
    // [Array] Array with special chars initials and their sum
  var $rowsSumSpecialChars = array( );











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
    * Index browser
    *
    **********************************************/



/**
 * get( ): Get the content of the index browser
 *
 * @param	[type]		$$content: ...
 * @return	array
 * @version 3.9.9
 * @since   3.9.9
 */
  public function get( $content )
  {
    $arr_return['data']['content'] = $content;

    $lDisplay = $this->pObj->lDisplayList['display.'];

      // RETURN: requirements aren't met
    if( ! $this->checkRequirements( ) )
    {
       // #35032, 120320
      $markerIndexbrowser = $this->getMarkerIndexbrowser( );
      $content = $this->pObj->cObj->substituteSubpart( $content, $markerIndexbrowser, null, true );
      $arr_return['data']['content'] = $content;
      return $arr_return;
    }
      // RETURN: requirements aren't met

      // Init the table.field
    $this->initTableField( );

      // Check, if table is the local table
    $arr_return = $this->checkTableField( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

    $this->initTabs( );



    $arr_return = $this->rows( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $rows = $arr_return['data']['rows'];


      // :TODO:
      // Move $GLOBALS['TSFE']->id temporarily
      // Get the index browser rows (uid, initialField)
      // Count the hits per tab, prepaire the tabArray
      // Build the index browser template

    return $arr_return;
  }









    /***********************************************
    *
    * init
    *
    **********************************************/



/**
 * checkRequirements( ): Checks
 *                                    * configuration of the flexform
 *                                    * configuration of TS tabs
 *                                    and returns false, if a requirement isn't met
 *
 * @return	boolean		true / false
 * @version 3.9.9
 * @since   3.9.9
 */
  private function checkRequirements( )
  {
      // RETURN: index browser is disabled
    if( ! $this->pObj->objFlexform->bool_indexBrowser )
    {
      if( $this->pObj->b_drs_navi )
      {
        $prompt = 'display.indexBrowser is false.';
        t3lib_div::devlog( '[INFO/NAVIGATION] ' . $prompt, $this->pObj->extKey, 0 );
      }
      return false;
    }
      // RETURN: index browser is disabled

      // RETURN: index browser hasn't any configured tab
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
      // RETURN: index browser hasn't any configured tab

    return true;
  }



/**
 * initTableField( ):  Set the class var $this->indexBrowserTableField
 *                                  Value is the table.field for SQL queries
 *
 * @return	void
 * @version 3.9.10
 * @since   3.9.9
 */
  private function initTableField( )
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



/**
 * checkTableField( ):
 *
 * @return	array		$arr_return
 * @version 3.9.10
 * @since   3.9.9
 */
  private function checkTableField( )
  {
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
 * initTabs( ):  Loops through the tab TS configuration array
 *                            and inits the class var $this->indexbrowserTab
 *
 * @return	void
 * @version 3.9.10
 * @since   3.9.10
 */
  private function initTabs( )
  {
      // Default properties
    $defaultWrap            = $this->conf['navigation.']['indexBrowser.']['defaultTabWrap'];
    $defaultDisplayWoItems  = $this->conf['navigation.']['indexBrowser.']['display.']['tabWithoutItems'];
      // Default properties

      // Tab with special value 'default'
    $this->indexbrowserTab['tabSpecial']['default'] = null;
    if( isset( $this->conf['navigation.']['indexBrowser.']['defaultTab'] ) )
    {
      $this->indexbrowserTab['tabSpecial']['default'] = $this->conf['navigation.']['indexBrowser.']['defaultTab'];
    }
      // Tab with special value 'default'

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

      if( $conf_tabs[$tabId . '.']['valuesCSV'] )
      {
        $valuesCSV      = $conf_tabs[$tabId . '.']['valuesCSV'];
        $arrInitials[]  = str_replace(' ', null, $valuesCSV);
      }

        // Tab label stdWrap
      if( $conf_tabs[$tabId . '.']['stdWrap.'] )
      {
        $stdWrap  = $conf_tabs[$tabId . '.']['stdWrap.'];
        $tabLabel = $this->pObj->objWrapper->general_stdWrap( $tabLabel, $stdWrap );
      }
        // Tab label stdWrap

        // Tab display withou items
      $displayWoItems = $defaultDisplayWoItems;
      if( isset ( $conf_tabs[$tabId . '.']['displayWithoutItems'] ) )
      {
        $displayWoItems = $conf_tabs[$tabId . '.']['displayWithoutItems'];
      }
        // Tab display withou items

        // Init tab array
      $this->indexbrowserTab['tabIds'][$tabId]['label']           = $tabLabel;
      $this->indexbrowserTab['tabIds'][$tabId]['displayWoItems']  = $displayWoItems;
      $this->indexbrowserTab['tabIds'][$tabId]['sum']             = 0;
      $this->indexbrowserTab['tabLabels'][$tabLabel] = $tabId;
        // Init tab array

        // CONTINUE : tab with special value 'all'
      if( $conf_tabs[$tabId . '.']['special'] == 'all' )
      {
        $this->indexbrowserTab['tabIds'][$tabId]['special'] = 'all';
        $this->indexbrowserTab['tabSpecial']['all']         = $tabId;
        continue;
      }
        // CONTINUE : tab with special value 'all'

        // CONTINUE : tab with special value 'others'
      if( $conf_tabs[$tabId . '.']['special'] == 'others' )
      {
        $this->indexbrowserTab['tabIds'][$tabId]['special'] = 'others';
        $this->indexbrowserTab['tabSpecial']['others']      = $tabId;
        continue;
      }
        // CONTINUE : tab with special value 'others'
    }
      // LOOP tabs TS configuratione array

      // Init special chars
    $this->initTabsSpecialChars( $arrInitials );
  }



/**
 * initTabsSpecialChars( ): Inits the class var $this->indexbrowserTab['initials']
 *
 * @param	array		$arrInitials : initials from the tab TS configuration
 * @return	void
 * @version 3.9.10
 * @since   3.9.10
 */
  private function initTabsSpecialChars( $arrInitials )
  {
      // Get initials unique
    $arrInitials  = array_unique( $arrInitials );
    $csvInitials  = implode( ',', ( array ) $arrInitials );

      // Init vars with all initials
    $this->indexbrowserTab['initials']['all']           = $csvInitials;
    $this->indexbrowserTab['initials']['specialChars']  = null;
    $this->indexbrowserTab['initials']['alphaNum']      = null;

      // UTF-8 decode
    $subject = utf8_decode( $csvInitials  );

      // Init var with special chars
    $pattern = '/[^0-9a-zA-Z,]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexbrowserTab['initials']['specialChars'] = utf8_encode( $specialChars );
    }
      // Init var with special chars

      // Init var with alpha numeric chars
    $pattern = '/[0-9a-zA-Z]/';
    if( preg_match_all( $pattern, $subject, $matches ) )
    {
      $specialChars = implode(',', $matches[0] );
      $this->indexbrowserTab['initials']['alphaNum'] = utf8_encode( $specialChars );
    }
      // Init var with alpha numeric chars
  }









    /***********************************************
    *
    * rows
    *
    **********************************************/



/**
 * rows( ):
 *
 * @return	boolean		true / false
 * @version 3.9.9
 * @since   3.9.9
 */
  private function rows( )
  {
      // Take care of special chars
    $arr_return = $this->rowsInitSpecialChars( );
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



/**
 * rowsInitSpecialChars( ):
 *
 * @return	[type]		...
 * @version 3.9.10
 * @since   3.9.10
 */
  private function rowsInitSpecialChars( )
  {
      // RETURN : no special chars
    if( empty ( $this->indexbrowserTab['initials']['specialChars'] ) )
    {
      return;
    }
      // RETURN : no special chars

      // Get a row with the SQL length for each special char
    $arr_return = $this->rowsInitSpecialCharsLength( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $row = $arr_return['data']['row'];
    unset( $arr_return );
      // Get a row with the SQL length for each special char

      // Get the sum for each special char initial
    $arr_return = $this->rowsSumSpecialChars( $row );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

  }



/**
 * rowsInitSpecialCharsLength( ):
 *
 * @return	[type]		...
 * @version 3.9.10
 * @since   3.9.10
 */
  private function rowsInitSpecialCharsLength( )
  {
      // Build the select statement parts for the length of each special char
    $arrStatement     = array( );
    $arrSpecialChars  = explode( ',', $this->indexbrowserTab['initials']['specialChars'] );
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

    $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

    $GLOBALS['TYPO3_DB']->sql_free_result( $res );

    $arr_return['data']['row'] = $row;
    return $arr_return;
  }



/**
 * rowsSumSpecialChars( ):
 *
 * @param	[type]		$$row: ...
 * @return	[type]		...
 * @version 3.9.10
 * @since   3.9.10
 */
  private function rowsSumSpecialChars( $row )
  {
      // Get current SQL char set
    $currSqlCharset = $this->sqlCharsetGet( );

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

      // Set SQL char set to latin1
    $this->sqlCharsetSet( 'latin1' );

      // DRS
    if ($this->pObj->b_drs_devTodo)
    {
      $prompt = 'Query needs an and where in case of filter';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS


      // LOOP : execute a query for each special char length group
    $sum_initialWiSpecialChars = 0;
    foreach( $this->findInSet as $length => $arrfindInSet )
    {
        // Query for all filter items
      $select   = "COUNT( * ) AS 'count', LEFT ( " . $field . ", " . $length . " ) AS 'initial'";
      $from     = $table;
      $where    = "(" . implode ( " OR ", $arrfindInSet ) . ")";
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
        // Get query

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
        // Execute query

        // Error management
      $error = $GLOBALS['TYPO3_DB']->sql_error( );
      if( $error )
      {
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

        // Reset SQL char set
      $this->sqlCharsetSet( $currSqlCharset );

        // LOOP build the rows
      while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
      {
        $sum_initialWiSpecialChars++;
        $rows[ $row[ 'initial' ] ] = $row[ 'count' ];
      }
        // LOOP build the rows

        // Free SQL result
      $GLOBALS['TYPO3_DB']->sql_free_result( $res );

      if( ! empty ( $rows ) )
      {
        $this->rowsSumSpecialChars = $rows;
      }


    }
      // LOOP : execute a query for each special char length group

  }









    /***********************************************
    *
    * SQL
    *
    **********************************************/



/**
 * sqlCharsetGet( ): Get the current SQL charset like latin1 or utf8.
 *
 * @return	string		current charset
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
