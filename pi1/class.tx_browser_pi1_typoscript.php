<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2015 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 * The class tx_browser_pi1_typoscript bundles typoscript methods for the extension browser
 *
 * @author       Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package      TYPO3
 * @subpackage   browser
 * @version      5.0.16
 * @since         2.0.0
 * @internal #59669
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   79: class tx_browser_pi1_typoscript
 *  128:     function __construct( $parentObj )
 *  148:     private function cObjDataAdd( $row )
 *  161:     private function cObjDataReset()
 *  175:     private function cObjGetSingleTableField( $tableField, $row )
 *  206:     private function cObjGetSingleTableFieldHandleAs( $tableField, $row )
 *  227:     private function cObjGetSingleTableFieldHandleAsRequirements( $tableField, $row )
 *  245:     public function fetch_localTable()
 *  347:     public function fetch_realTables_arrFields()
 *  507:     private function fetch_realTableWiField( $str_queryPart, $key_queryPart )
 *  591:     private function getFirstRow()
 *  619:     public function oneDim_to_tree( $conf_oneDim )
 *  685:     private function set_confSql()
 *  960:     public function set_confSqlDevider()
 * 1021:     private function set_confSql_groupBy()
 * 1093:     public function wrapRow()
 * 1111:     private function wrapRowInit()
 * 1127:     private function wrapRowInitRequireClasses()
 * 1139:     private function wrapRowInitRequireClassesHandleAs()
 * 1154:     private function wrapRowInitSetRows()
 * 1178:     private function wrapRowInitSetTableFields()
 * 1206:     private function wrapRowInitSetTablesForeign()
 * 1236:     private function wrapRowInitSetUids()
 * 1250:     private function wrapRowInitSetUidsForeign()
 * 1266:     private function wrapRowInitSetUidsForeignPerTable( $tableForeign )
 * 1296:     private function wrapRowInitSetUidsLocal()
 * 1340:     private function wrapRowTableForeign( $markerArray )
 * 1358:     private function wrapRowTableForeignRows( $markerArray, $tableForeign )
 * 1408:     private function wrapRowTableForeignField( $tableField, $row )
 * 1422:     private function wrapRowTableForeignGetFields( $tableForeign )
 * 1447:     private function wrapRowTableLocal( $markerArray )
 * 1470:     private function wrapRowTableLocalField( $markerArray, $tableField, $row )
 * 1484:     private function wrapRowTableLocalGetFields()
 *
 * TOTAL FUNCTIONS: 32
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_typoscript
{

  //////////////////////////////////////////////////////
  //
  // Variables set by the pObj (by class.tx_browser_pi1.php)

  public $conf = false;
  // [Array] The current TypoScript configuration array
  public $mode = false;
  // [Integer] The current mode (from modeselector)
  public $view = false;
  // [String] 'list' or 'single': The current view
  public $conf_view = false;
  // [Array] The TypoScript configuration array of the current view
  public $conf_path = false;
  // [String] TypoScript path to the current view. I.e. views.single.1
  // Variables set by the pObj (by class.tx_browser_pi1.php)
  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  private $conf_sql;
  // Array with the SQL query parts from the TypoScript
  private $arr_realTables_arrFields;
  // Array with tables and fields in this syntax: array[table][] = field
  static private $autoDiscoverItems = null; // [array] typoscript configuration array
  public $str_sqlDeviderDisplay = null;
  // [String] Devider for children records. This devider should be displayed.
  public $str_sqlDeviderWorkflow = null;
  // [String] Devider for children records. This devider is for the workflow of stdWrap.

  private $objHandleAs = null;
  // [Object] Object of tx_browser_pi1_typoscriptHandleAs

  public $template = array();
  // [String] template of the ucrrent template
  private $tableFields = array();
  // [Array] fields of the current row (first row)
  private $tablesForeign = array();
  // [Array] list of foreign table names
  private $rows = array();
  // [Integer] uid of the current record (local table)
  private $uidOfCurrentRecord = null;
  // [Array] current row in case of no child, current rows in case of children
  private $uids = array();

  // [Array] uid of the local table in case of no child, uid of local table and uids of foreign tables in case og children
  // Variables set by this class

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */
  function __construct( $parentObj )
  {
    // Set the Parent Object
    $this->pObj = $parentObj;
  }

  /*   * *********************************************
   *
   * cObjData methods
   *
   * ******************************************** */

  /**
   * cObjDataAdd( ):
   *
   * @param	array		$row  : current row
   * @return	void
   * @version   5.0.0
   * @since     5.0.0
   */
  private function cObjDataAdd( $row )
  {
    $this->pObj->objCObjData->add( $row );
  }

  /**
   * cObjDataReset( ):
   *
   * @return	void
   * @internal  #44858
   * @version   5.0.0
   * @since     5.0.0
   */
  private function cObjDataReset()
  {
    $this->pObj->objCObjData->reset();
  }

  /**
   * cObjGetSingle() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function cObjGetSingle( $name, $conf )
  {
    if ( empty( $name ) )
    {
      $name = 'TEXT';
    }

    if ( is_array( $conf ) )
    {
      $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );
      return $value;
    }

    $header = 'FATAL ERROR!';
    $text = 'cObjGetSingle: the param conf isn\'t an array.';
    $this->pObj->drs_die( $header, $text );
  }

  /**
   * cObjGetSingleTableField() :
   *
   * @param	array     $row        : current row
   * @param	string		$tableField : name of the tableField. I.e: tx_org_job.title
   * @return	string	$value      : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function cObjGetSingleTableField( $tableField, $row )
  {
    list( $table, $field ) = explode( '.', $tableField );
    $name = $this->conf_view[ $table . '.' ][ $field ];
    $conf = $this->conf_view[ $table . '.' ][ $field . '.' ];
    $conf = $this->cObjGetSingleTableFieldReplaceMarker( $name, $conf, $row );

    switch ( true )
    {
      case(is_array( $conf )):
      case(!empty( $name )):
        $value = $this->cObjGetSingle( $name, $conf );
        break;
      default:
        $value = $this->objHandleAs->main( $tableField, $row );
        $value = $this->colorSwords( $tableField, $value );
        $value = $this->link( $tableField, $value );
        break;
    }
    return $value;
  }

  /**
   * cObjGetSingleTableFieldReplaceMarker() : Replace tableField marker in the current TypoSCript configuration
   *                                by reference
   *
   * @param	string	$name : TypoScript configuration name
   * @param	array   $conf : TypoScript configuration array
   * @param	array		$row  : current row
   * @return	array   $conf : TypoScript configuration array
   * @version 5.0.0
   * @since 5.0.0
   */
  private function cObjGetSingleTableFieldReplaceMarker( $name, $conf, $row )
  {
    // substitute_tablefield_marker needs the following array
    $arrConf = array(
      '10' => $name,
      '10.' => $conf
    );

    // Substitute the marker
    $arrConf = $this->pObj->objMarker->substitute_tablefield_marker( $arrConf, $row );
    // Update the TypoScript configuration by reference
    $conf = $arrConf[ '10.' ];
    return $conf;
  }

  /**
   * colorSwords() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function colorSwords( $tableField, $value )
  {
    // Do we have a handleAs field like title, text or image?
    $key = $this->objHandleAs->getHandleAsKey( $tableField );

    // Process color_swords: We don't have a handle As case:
    if ( empty( $key ) )
    {
      $value = $this->pObj->objZz->color_swords( $value );
      return $value;
    }

    // RETURN : uncolored swords, handle as case shouldn't processed
    $dontColorSwords = $this->autoDiscoverItems[ $key . '.' ][ 'dontColorSwords' ];
    if ( $dontColorSwords )
    {
      return $value;
    }

    // RETURN : colored swords, handle as case should processed
    $value = $this->pObj->objZz->color_swords( $value );
    return $value;
  }

  /*   * *********************************************
   *
   * Get used tables from the TypoScript
   *
   * ******************************************** */

  /**
   * fetch_localTable()  : Returns the values for the array with the local table. The local table is the main table.
   *
   * @return	array		$arr_localTable: Array with the syntax: array[uid] = table.field, array[pid] = table.field
   * @version 4.5.14
   * @since 2.0.0
   */
  public function fetch_localTable()
  {

    /////////////////////////////////////////////////////
    //
    // RETURN, if $this->pObj->arrLocalTable is initiated

    if ( is_array( $this->pObj->arrLocalTable ) )
    {
      return $this->pObj->arrLocalTable;
    }


    /////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[INFO/SQL] Look for the local table.', $this->pObj->extKey, 0 );
    }


    /////////////////////////////////////////////////////
    //
    // Get the local table from TS, if it is configured

    $str_localTable = $this->conf_view[ 'localTable' ];
    if ( !$str_localTable )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] ' . $this->conf_path . 'localTable isn\'t configured. Probably it is OK.', $this->pObj->extKey, 0 );
      }
      $str_localTable = $this->pObj->conf[ 'localTable' ];
    }
    if ( $str_localTable )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] ' . $this->conf_path . 'localTable is: \'' . $str_localTable . '\'.', $this->pObj->extKey, 0 );
      }
    }


    /////////////////////////////////////////////////////
    //
    // If there isn't a table in the TS, take the first of the select query

    if ( !$str_localTable )
    {
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devlog( '[INFO/SQL] localTable (global TypoScript value) isn\'t configured. Probably it is OK.', $this->pObj->extKey, 0 );
      }
      // #i0115, 141220, dwildt -/+
      //reset( $this->arr_realTables_arrFields );
      if ( is_array( $this->arr_realTables_arrFields ) )
      {
        reset( $this->arr_realTables_arrFields );
      }
      $str_localTable = key( $this->arr_realTables_arrFields );
      if ( $str_localTable )
      {
        if ( $this->pObj->b_drs_sql )
        {
          t3lib_div::devlog( '[INFO/SQL] We take the first table from the SELECT statement.<br />
            localTable (maintable) is: ' . $str_localTable, $this->pObj->extKey, 0 );
          t3lib_div::devlog( '[HELP/SQL] If you like another table, please configure ' . $this->conf_path . 'localTable', $this->pObj->extKey, 1 );
        }
      }
    }


    /////////////////////////////////////////////////////
    //
    // Do we have a special uid and pid in the local view?

    if ( is_array( $this->conf_view[ 'localTable.' ] ) )
    {
      $arr_localTable = $this->conf_view[ 'localTable.' ];
    }
    else
    {
      $arr_localTable = $this->conf[ 'localTable.' ];
    }

    /////////////////////////////////////////////////////
    //
    // We need a syntax: table.field

    $arr_localTable[ 'uid' ] = $str_localTable . '.' . $arr_localTable[ 'uid' ];
    $arr_localTable[ 'pid' ] = $str_localTable . '.' . $arr_localTable[ 'pid' ];
    $arr_localTable = $this->pObj->objSqlFun_3x->replace_tablealias( $arr_localTable );

    return $arr_localTable;
  }

  /**
   * fetch_realTables_arrFields( ): Returns an array with used tables and fields
   *                                out of the TypoScript SQL query parts.
   *                                The tables will have real names
   *
   * @return	array		Array with the syntax array[table][] = field
   * @version 4.7.0
   * @since   2.0.0
   */
  public function fetch_realTables_arrFields()
  {
    static $promptDRSEngine4 = true;



    //////////////////////////////////////////////////////
    //
      // DRS - Development Reporting System

    if ( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[INFO/SQL] We try to fetch used tables.', $this->pObj->extKey, 0 );
    }
    // DRS - Development Reporting System
    //////////////////////////////////////////////////////
    //
      // Get the typoscript configuration for the SQL query

    $lConfSql = $this->set_confSql();
    // Set the typoscript configuration for the SQL query
    /////////////////////////////////////////////////////
    //
      // Fetch used tables from the SELECT statement
    // Is there a SQL function, which should replaced with an alias?
    // Replace each SQL function which its alias
    foreach ( ( array ) $this->conf_view[ 'select.' ][ 'deal_as_table.' ] as $arr_dealastable )
    {
      $str_statement = $arr_dealastable[ 'statement' ];
      $str_aliasTable = $arr_dealastable[ 'alias' ];
      // 121211, dwildt, 1-
      //$arr_dealAlias[$str_aliasTable] = $str_statement;
      // I.e.: $conf_sql['select'] = CONCAT(tx_bzdstaffdirectory_persons.title, ' ', tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name), tx_bzdstaffdirectory_groups.group_name
      $lConfSql[ 'select' ] = str_replace( $str_statement, $str_aliasTable, $lConfSql[ 'select' ] );
      // I.e.: $conf_sql['select'] = tx_bzdstaffdirectory_persons.last_name, tx_bzdstaffdirectory_groups.group_name
    }

    // Set the global csvSelectWoFunc with table.fields only and without any function
    $csvSelectWoFunc = $lConfSql[ 'select' ];
    $arrSelectWoFunc = explode( ',', $csvSelectWoFunc );
    $arrSelectWoFunc = $this->pObj->objSqlFun_3x->clean_up_as_and_alias( $arrSelectWoFunc );
    $csvSelectWoFunc = implode( ', ', $arrSelectWoFunc );
    $this->pObj->csvSelectWoFunc = $csvSelectWoFunc;
    // Set the global csvSelectWoFunc with table.fields only and without any function

    $this->fetch_realTableWiField( $lConfSql[ 'select' ], 'select' );
    // Fetch used tables from the SELECT statement
    /////////////////////////////////////////////////////
    //
      // Fetch used tables from the SEARCH, ORDER BY and AND WHERE statement
    // Fetch used tables from the search fields, if there is a sword
    if ( $this->pObj->piVar_sword )
    {
      $this->fetch_realTableWiField( $lConfSql[ 'search' ], 'search' );
    }

    // Try to fetch used tables from the ORDER BY statement
    $csvOrderBy = $lConfSql[ 'orderBy' ];
    // Bugfix #6468, #6518,  010220, dwildt
    $csvOrderBy = str_ireplace( ' desc', '', $csvOrderBy );
    $csvOrderBy = str_ireplace( ' asc', '', $csvOrderBy );
    $this->fetch_realTableWiField( $csvOrderBy, 'orderBy' );


    // Try to fetch used tables from the AND WHERE statement
    if ( $lConfSql[ 'andWhere' ] )
    {
      $arr_result = $this->pObj->objSqlFun_3x->get_propper_andWhere( $lConfSql[ 'andWhere' ] );
      $strCsvTableFields = implode( ',', $arr_result[ 'data' ][ 'arr_used_tableFields' ] );
      unset( $arr_result );
      $this->fetch_realTableWiField( $strCsvTableFields, 'andWhere' );
    }
    // Fetch used tables from the SEARCH, ORDER BY and AND WHERE statement
    /////////////////////////////////////////////////////
    //
      // Get table fields out of the filter, if filter is set

    $arr_tableField = false;
    if ( is_array( $this->conf_view[ 'filter.' ] ) )
    {
      // 121211, dwildt, 1-
      //$arr_prompt = array( );
      foreach ( ( array ) $this->conf_view[ 'filter.' ] as $tableWiDot => $arrFields )
      {
//var_dump( __METHOD__, __LINE__, $tableWiDot, $arrFields );
        // #52486, 131002, dwildt, 4+
        if ( $arrFields == 'RADIALSEARCH' )
        {
          continue;
        }
        // #52486, 131002, dwildt, 4+
        // Get piVar name
        $tableField = $tableWiDot . key( $arrFields );
        list( $table, $field ) = explode( '.', $tableField );
        // 121211, dwildt, 1+
        unset( $table );
        $str_nice_piVar = $arrFields[ $field . '.' ][ 'nice_piVar' ];
        if ( !$str_nice_piVar )
        {
          $str_nice_piVar = $tableField;
        }
        // Do we have a piVar
        if ( $this->pObj->piVars[ $str_nice_piVar ] )
//        if( $this->pObj->piVars[$str_nice_piVar] || 1 )
        {
          $arr_tableField[] = $tableField;
        }
        // DEVELOPMENT: Browser engine 4.x
        // DRS
        if ( ( $this->pObj->b_drs_filter || $this->pObj->b_drs_sql || $this->pObj->b_drs_todo ) && $promptDRSEngine4 )
        {
          $prompt = 'Other workflow in 4.x and 3.x!';
          t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->pObj->extKey, 2 );
          $promptDRSEngine4 = false;
        }
        // DRS
        if ( $this->pObj->dev_browserEngine >= 4 )
        {
          // IF no pivar (filter isn't set)
          if ( !$this->pObj->piVars[ $str_nice_piVar ] )
          {
            // IF current filter isn't set in $arr_tableField
            if ( !isset( $arr_tableField[ $tableField ] ) )
            {
              // Add the current filter (tableField), but filter isn't used
              $arr_tableField[] = $tableField;
            }
            // IF current filter isn't set in $arr_tableField
          }
          // IF no pivar (filter isn't set)
        }
        // DEVELOPMENT: Browser engine 4.x
      }
    }
    if ( is_array( $arr_tableField ) )
    {
      $arrCsvFilter = implode( ',', $arr_tableField );
      $this->fetch_realTableWiField( $arrCsvFilter, 'filter' );
    }
    // Get table fields out of the filter, if filter is set
    /////////////////////////////////////////////////////
    //
      // Set the class var conf_sql

    $this->conf_sql = $lConfSql;
    // Set the class var conf_sql

    return $this->arr_realTables_arrFields;
  }

  /**
   * fetch_realTableWiField( )  : Allocates the class array arr_table_wi_arrFields with realname
   *                              tables and there fields
   *
   * @param	string		$str_queryPart: The SQL query part out of the global conf_sql.
   * @param	[type]		$key_queryPart: ...
   * @return	void
   * @version     4.2.0
   * @since       2.0.0
   */
  private function fetch_realTableWiField( $str_queryPart, $key_queryPart )
  {

    // RETURN : $str_queryPart is empty
    if ( empty( $str_queryPart ) )
    {
      return false;
    }
    // RETURN : $str_queryPart is empty

    $arrCsv = explode( ',', $str_queryPart );
    $arrCsv = $this->pObj->objSqlFun_3x->clean_up_as_and_alias( $arrCsv );

    // #50214, 130720, dwildt, 3-
//    $arrTmp[0]  = $arrCsv;
//    $arrTmp     = $this->pObj->objSqlFun_3x->replace_tablealias( $arrTmp );
//    $arrCsv     = $arrTmp[0];
    // #50214, 130720, dwildt, 1+
    $arrCsv = $this->pObj->objSqlFun_3x->replace_tablealias( $arrCsv );

    // LOOP each query part
    foreach ( ( array ) $arrCsv as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      // 121211, dwildt, 6+
      // CONTINUE : table is empty
      if ( empty( $table ) )
      {
        continue;
      }
      // CONTINUE : table is empty
      // 121211, dwildt, 6+

      $table = trim( $table );
      $field = trim( $field );

      // #43889, 121211, dwildt
      switch ( $key_queryPart )
      {
        case( 'select' ):
          break;
        case( 'orderBy' ):
        case( 'search' ):
        case( 'where' ):
        case( 'andWhere' ):
        case( 'filter' ):
          if ( $field == 'uid' )
          {
            continue 2;
          }
          break;
        default:
          $header = 'FATAL ERROR!';
          $text = 'Undefined value in SWITCH: ' . $key_queryPart;
          $this->pObj->drs_die( $header, $text );
      }
      // #43889, 121211, dwildt

      if ( !is_array( $this->arr_realTables_arrFields[ $table ] ) )
      {
        $this->arr_realTables_arrFields[ $table ][] = $field;
      }
      if ( is_array( $this->arr_realTables_arrFields[ $table ] ) )
      {
        if ( !in_array( $field, $this->arr_realTables_arrFields[ $table ] ) )
        {
          $this->arr_realTables_arrFields[ $table ][] = $field;
        }
      }
    }
    // LOOP each query part
//$this->pObj->dev_var_dump( $this->arr_realTables_arrFields );

    return;
  }

  /**
   * getConfAdvanced() : Returns $confAdvanced
   *
   * @return	array  $confAdvanced :
   * @version 5.0.0
   * @since 5.0.0
   */
  private function getConfAdvanced()
  {
    static $confAdvanced = null;

    if ( $confAdvanced !== null )
    {
      return $confAdvanced;
    }

    $confAdvanced = $this->conf_view[ 'advanced.' ];
    if ( empty( $confAdvanced ) )
    {
      $confAdvanced = $this->conf[ 'advanced.' ];
    }

    return $confAdvanced;
  }

  /**
   * getDeviderPerTableField() :
   *
   * @return	array
   * @version 5.0.0
   * @since 5.0.0
   */
  private function getDeviderPerTableField( $tableField )
  {
    static $deviderPerTableField = array();

    if ( isset( $deviderPerTableField[ $tableField ] ) )
    {
      return $deviderPerTableField[ $tableField ];
    }

    // Get global or local configuration advanced array
    $confAdvanced = $this->getConfAdvanced();
    list($table, $field) = explode( '.', $tableField );

    $name = $confAdvanced[ 'sql.' ][ 'devider.' ][ $table . '.' ][ $field . '.' ][ 'display' ];
    $conf = $confAdvanced[ 'sql.' ][ 'devider.' ][ $table . '.' ][ $field . '.' ][ 'display.' ];

    switch ( true )
    {
      case( empty( $name )):
        $deviderPerTableField[ $tableField ][ 'isset' ] = false;
        $deviderPerTableField[ $tableField ][ 'devider' ] = null;
        break;
      case(!empty( $name )):
      default:
        $deviderPerTableField[ $tableField ][ 'isset' ] = true;
        $deviderPerTableField[ $tableField ][ 'devider' ] = $this->cObjGetSingle( $name, $conf );
        break;
    }
    //var_dump(__METHOD__, __LINE__, $deviderPerTableField);
    return $deviderPerTableField[ $tableField ];
  }

  /**
   * GetFirstRow( ):
   *
   * @return	array		$row  : first row
   * @version   5.0.0
   * @since     5.0.0
   */
  private function getFirstRow()
  {
    switch ( true )
    {
      case($this->uidOfCurrentRecord === null):
        $row = $this->getFirstRowViewSingle();
        break;
      case($this->uidOfCurrentRecord !== null):
      default:
        $row = $this->getFirstRowViewList();
        break;
    }

    return $row;
  }

  /**
   * getFirstRowViewList( ):
   *
   * @return	array		$row  : first row
   * @version   5.0.0
   * @since     5.0.0
   */
  private function getFirstRowViewList()
  {
    // Get the label of the local table
    $tableLocal = $this->pObj->localTable;
//var_dump( __METHOD__, __LINE__, $this->pObj->localTable, $this->rows );
    // LOOP rows
    foreach ( ( array ) $this->rows as $row )
    {
      $uid = $row[ $tableLocal . '.uid' ];
      if ( $this->uidOfCurrentRecord !== $uid )
      {
        continue;
      }
      break;
    }

    return $row;
  }

  /**
   * getFirstRowViewSingle( ):
   *
   * @return	array		$row  : first row
   * @version   5.0.0
   * @since     5.0.0
   */
  private function getFirstRowViewSingle()
  {
    reset( $this->rows );
    $firstKey = key( $this->rows );
    $row = $this->rows[ $firstKey ];
    return $row;
  }

  /*   * *********************************************
   *
   * TypoScript Management
   *
   * ******************************************** */

  /**
   * link() : Wrap a typolink automatically, if requirements matched
   *
   * @param	string      $tableField : name of the tableField. I.e: tx_org_job.title
   * @param	string      $value      : current value. I.e: Manager
   * @return	string		$value      : the linked value
   * @version 5.0.0
   * @since 5.0.0
   */
  private function link( $tableField, $value )
  {
    if ( !$this->linkRequirements( $tableField ) )
    {
      return $value;
    }

    // Does the single view exist?
    $singleView = $this->conf[ 'views.' ][ 'single.' ][ $this->mode . '.' ];
    switch ( true )
    {
      case(empty( $singleView )):
        $value = $this->linkWoSingleView( $value );
        break;
      case( is_array( $singleView )):
      default:
        $value = $this->linkWiSingleView( $value );
        break;
    }
    return $value;
  }

  /**
   * linkRequirements() : Returns true, if requirements matched. False, if not.
   *
   * @param	string      $tableField : name of the tableField. I.e: tx_org_job.title
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkRequirements( $tableField )
  {
    switch ( true )
    {
      case(!$this->linkRequirementsView()):
      case(!$this->linkRequirementsDoLink( $tableField )):
      case(!$this->linkRequirementsTypeNum()):
        return false;
      default:
        return true;
    }
  }

  /**
   * linkRequirementsDoLink() : Returns true, if current tableField should linked.
   *
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkRequirementsDoLink( $tableField )
  {
    if ( in_array( $tableField, ( array ) $this->pObj->arrLinkToSingle ) )
    {
      return true;
    }

    return false;
  }

  /**
   * linkRequirementsTypeNum() : Returns false, if links shouldn't processed automatically in the current page type
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkRequirementsTypeNum()
  {
    switch ( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
        if ( $this->pObj->b_drs_flexform || $this->pObj->b_drs_export || $this->pObj->b_drs_typoscript )
        {
          $prompt = 'Don\'t link to a single view. All booleans are set to false!';
          t3lib_div::devlog( '[INFO/EXPORT+FLEXFORM+TYPOSCRIPT] ' . $prompt, $this->pObj->extKey, 0 );
        }
        return false;
      default:
        return true;
    }
  }

  /**
   * linkRequirementsView() : Returns true, if current view is a list view
   *
   * @param	string		$tableField : name of the tableField. I.e: tx_org_job.title
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkRequirementsView()
  {
    // RETURN : Link only in a list view
    if ( $this->view == 'list' )
    {
      return true;
    }

    return false;
  }

  /**
   * linkWiSingleView() : Wrap a typolink automatically
   *
   * @param	string      $value  : current value. I.e: Manager
   * @return	string		$value  : the linked value
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleView( $value )
  {
    switch ( true )
    {
      case( $value == '' ):
      case( $value === null ):
        return '&nbsp;';
      default:
        //follow the workflow
        break;
    }

    // Set piVars
    $this->linkWiSingleViewPiVars();
    // Get additional parameter
    $additionalParams = $this->linkWiSingleViewAdditionalParams();
    // Get the page id of the single view
    $singlePid = $this->pObj->objZz->get_singlePid_for_listview();

    // TypoScript typolink
    $name = 'TEXT';
    $conf = array(
      'value' => $value,
      'typolink.' => array(
        'parameter' => $singlePid,
        'additionalParams' => $additionalParams,
        'ATagParams' => 'class="linktosingle"',
        'useCacheHash' => '1',
      )
    );

    // Render the TypoScript
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );

    return $value;
  }

  /**
   * linkWiSingleViewAdditionalParams() : Return the additional parameters for the typolink
   *
   * @return	string  $additionalParams :
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewAdditionalParams()
  {
    $additionalParams = $this->linkWiSingleViewAdditionalParamsPiVars();
    $additionalParams = $this->linkWiSingleViewAdditionalParamsMode( $additionalParams );
    return $additionalParams;
  }

  /**
   * linkWiSingleViewAdditionalParamsMode() : Returns the mode parameter, if there is more than one single view
   *
   * @return	string  $additionalParams :
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewAdditionalParamsMode( $additionalParams )
  {

    if ( count( $this->conf[ 'views.' ][ 'single.' ] ) <= 1 )
    {
      return $additionalParams;
    }

    // Get the key of the first view
    reset( $this->pObj->conf[ 'views.' ][ 'single.' ] );
    $firstKeyWiDot = key( $this->pObj->conf[ 'views.' ][ 'single.' ] );
    $firstKeyWoDot = substr( $firstKeyWiDot, 0, strlen( $firstKeyWiDot ) - 1 );

    if ( $this->pObj->piVar_mode == $firstKeyWoDot )
    {
      return;
    }

    // Add the parameter mode
    $additionalParams .= '&' . $this->pObj->prefixId . '[mode]=' . $this->pObj->piVar_mode;
    return $additionalParams;
  }

  /**
   * linkWiSingleViewAdditionalParamsPiVars() : Returns the piVars
   *
   * @return	string  $additionalParams :
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewAdditionalParamsPiVars()
  {
    $additionalParams = null;
    foreach ( ( array ) $this->pObj->piVars as $paramKey => $paramValue )
    {
      if ( empty( $paramValue ) )
      {
        continue;
      }
      $additionalParams = $additionalParams
              . '&' . $this->pObj->prefixId . '[' . $paramKey . ']=' . $paramValue;
    }
    return $additionalParams;
  }

  /**
   * linkWiSingleViewPiVars() : Manage piVars
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewPiVars()
  {
    $this->linkWiSingleViewPiVarsRemove();
    $this->linkWiSingleViewPiVarsShowUid();
  }

  /**
   * linkWiSingleViewPiVarsRemove() : Remove piVars
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewPiVarsRemove()
  {
    // Remove piVars, if they should not used in the realUrl path
    $this->pObj->objZz->advanced_remove_piVars();
    // #8368
    $this->pObj->objZz->advanced_remove_piVars_filter();
  }

  /**
   * linkWiSingleViewPiVarsShowUid() : Set piVar showUid
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWiSingleViewPiVarsShowUid()
  {
    $aliasShowUid = $this->pObj->piVar_alias_showUid;

    if ( empty( $aliasShowUid ) )
    {
      $this->pObj->piVars[ 'showUid' ] = $this->uidOfCurrentRecord;
      return;
    }

    unset( $this->pObj->piVars[ 'showUid' ] );
    $this->pObj->objZz->tmp_piVars[ 'showUid' ] = null;
    $this->pObj->piVars[ $aliasShowUid ] = $this->uidOfCurrentRecord;
  }

  /**
   * linkWoSingleView() : Wrappes the value with a javascript alert
   *
   * @param	string      $value  : current value. I.e: Manager
   * @return	string		$value  : the linked value
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWoSingleView( $value )
  {
    if ( !$this->linkWoSingleViewYesJss() )
    {
      return $value;
    }

    $value = $this->linkWoSingleViewJss( $value );
    return $value;
  }

  /**
   * linkWoSingleViewJss() : Wrappes the value with a javascript alert
   *
   * @param	string      $value  : current value. I.e: Manager
   * @return	string		$value  : the linked value
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWoSingleViewJss( $value )
  {
    $promptJSS = $this->pObj->pi_getLL( 'error_views_single_noview' );
    $promptJSS = t3lib_div::slashJS( $promptJSS, false, "'" );
    $promptJSS = rawurlencode( '\'' . $promptJSS . '\'' );

    // TypoScript typolink
    $name = 'TEXT';
    $conf = array(
      'value' => $value,
      'wrap' => '<a href="javascript:alert(' . $promptJSS . ')">|</a>',
    );

    // Render the TypoScript
    $value = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    return $value;
  }

  /**
   * linkWoSingleViewYesJss() : Returns true, if JSS alert should set
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function linkWoSingleViewYesJss()
  {
    $lDisplayList = $this->conf[ 'views.' ][ $this->view . '.' ][ $this->mode . '.' ][ 'displayList.' ];
    if ( !is_array( $lDisplayList ) )
    {
      $lDisplayList = $this->conf[ 'displayList.' ];
    }
    return $lDisplayList[ 'display.' ][ 'jssAlert' ];
  }

  /**
   * oneDim_to_tree():  Build a multidimensional TypoScript configuration array (tree)
   *                    out of a one dimensional array.
   *                    Example:
   *                    - $conf_oneDim['views.single.1.select'] = tt_news.title
   *                      will become
   *                    - $conf['views.']['single.']['1.']['select'] = tt_news.title
   *
   * @param	array		$conf_oneDim  : TypoScript configuration array (one dimension)
   * @return	array		$conf         : TypoScript configuration array
   * @since     3.4.3
   * @version   3.4.3
   */
  public function oneDim_to_tree( $conf_oneDim )
  {
    $conf = array();

    // Values for preg_replace and preg_split
    $str_delimiter = '|';
    $str_split = '/' . preg_quote( $str_delimiter, '/' ) . '/';
    $str_dot = '/\./';
    $str_dot_replace = '.|';
    // Values for preg_replace and preg_split
    // Loop: Each TypoScript configuration path
    foreach ( $conf_oneDim as $key_oneDim => $value_oneDim )
    {
      // Get all items from the current TypoScript path
      // views.single.1.select -> views.|single.|1.|select
      $key_oneDim = preg_replace( $str_dot, $str_dot_replace, $key_oneDim );
      // array( 'views.', 'single.', '1.', 'select')
      $ts_keys = preg_split( $str_split, $key_oneDim, -1, PREG_SPLIT_NO_EMPTY );
      // 'select'
      $last_ts_key = array_pop( $ts_keys );
      // Get all items from the current TypoScript path
      // Build parent structure
      // Might be slow for really deep and large structures
      $parentArr = &$conf;
      // Loop: Each element of the current configuration path
      foreach ( $ts_keys as $ts_key )
      {
        if ( !isset( $parentArr[ $ts_key ] ) )
        {
          $parentArr[ $ts_key ] = array();
        }
        elseif ( !is_array( $parentArr[ $ts_key ] ) )
        {
          $parentArr[ $ts_key ] = array();
        }
        $parentArr = &$parentArr[ $ts_key ];
      }
      // Loop: Each element of the current configuration path
      // Build parent structure
      // Add the final part to the structure
      if ( empty( $parentArr[ $last_ts_key ] ) )
      {
        $parentArr[ $last_ts_key ] = $value_oneDim;
      }
      // Add the final part to the structure
    }
    // Loop: Each TypoScript configuration path

    return $conf;
  }

  /*   * *********************************************
   *
   * Helper Functions
   *
   * ******************************************** */

  /**
   * set_confSql( ): Sets the class var conf_sql with the SQL query statements from the TypoScript.
   *                 If there is a 'deal_as_table', SQL function will replaced.
   *                 All tables become an alias, functions too.
   *
   * @return	array		$conf_sql:
   * @version  5.0.16
   * @since    3.0.0
   */
  private function set_confSql()
  {
    // 121211, dwildt, 1+
    $conf_sql = array();

    // DRS
    if ( $this->pObj->b_drs_sql )
    {
      $prompt = 'Try to process aliases in SQL query parts.';
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    }
    // DRS
    //////////////////////////////////////////////////////
    //
      // LOOP select, ..., andWhere

    $arr_query_parts = array( 'select', 'from', 'search', 'orderBy', 'groupBy', 'where', 'andWhere' );
    foreach ( $arr_query_parts as $str_query_part )
    {
      $coa_name = $this->conf_view[ 'override.' ][ $str_query_part ];
      $coa_conf = $this->conf_view[ 'override.' ][ $str_query_part . '.' ];

      // IF override
      if ( $coa_name )
      {
        // DRS
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $str_query_part . ' has an override.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
        }
        // DRS
        // #i0026, 130903, dwildt
//        $conf_sql[$str_query_part]  = $this->pObj->objSqlFun_3x->global_stdWrap
//                                      (
//                                        'override.' . $str_query_part,
//                                        $coa_name,
//                                        $coa_conf
//                                      );
        // #i0026, 130903, dwildt
        $conf_sql[ $str_query_part ] = $this->pObj->objSqlFun->cObjGetSingle
                (
                'override.' . $str_query_part, $coa_name, $coa_name, $coa_conf
        );
      }
      // IF override
      // IF no override
      if ( !$coa_name )
      {
        // DRS
        if ( $this->pObj->b_drs_sql )
        {
          $prompt = $str_query_part . ' hasn\'t any override.';
          t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
          $prompt = 'If you want to override, please configure \'override.' . $str_query_part . '\'.';
          t3lib_div::devlog( '[HELP/SQL] ' . $prompt, $this->pObj->extKey, 1 );
        }
        // DRS
        $coa_name = $this->conf_view[ $str_query_part ];
        $coa_conf = $this->conf_view[ $str_query_part . '.' ];
        // 3.3.7
        // #i0026, 130903, dwildt
//        $conf_sql[$str_query_part]  = $this->pObj->objSqlFun_3x->global_stdWrap
//                                      (
//                                        $str_query_part,
//                                        $coa_name,
//                                        $coa_conf
//                                      );
        // #i0026, 130903, dwildt
        $conf_sql[ $str_query_part ] = $this->pObj->objSqlFun->cObjGetSingle
                (
                $str_query_part, $coa_name, $coa_name, $coa_conf
        );
      }
      // IF no override
    }


    //////////////////////////////////////////////////////
    //
      // group by, order by, where, and where
    // Set group by
    $conf_sql[ 'groupBy' ] = $this->set_confSql_groupBy();

    // Set default order by
    if ( empty( $conf_sql[ 'orderBy' ] ) )
    {
      $conf_sql[ 'orderBy' ] = $this->conf_view[ 'select' ];
    }
    // Set default order by
    // Concatenate group by and order by
    if ( empty( $conf_sql[ 'groupBy' ] ) )
    {
      $conf_sql[ 'orderBy' ] = $conf_sql[ 'groupBy' ] . ', ' . $conf_sql[ 'orderBy' ];
    }
    // Concatenate group by and order by
    // Set where
    if ( empty( $conf_sql[ 'where' ] ) )
    {
      $conf_sql[ 'where' ] = $this->conf[ 'where' ];
    }
    // Set where
    // Set and where
    if ( empty( $conf_sql[ 'andWhere' ] ) )
    {
      $conf_sql[ 'andWhere' ] = $this->conf[ 'andWhere' ];
    }
    // Set and where
    // group by, order by, where, and where
//$this->pObj->dev_var_dump( $conf_sql );
    //////////////////////////////////////////////////////
    //
      // and where data query
    // plugin [template] int_templating_dataQuery has a value
    if ( $this->pObj->objFlexform->int_templating_dataQuery )
    {
      // Get andWhere from TypoScript
      $str_key = $this->pObj->objFlexform->int_templating_dataQuery . '.';
      $arr_items = $this->conf[ 'flexform.' ][ 'templating.' ][ 'arrDataQuery.' ][ 'items.' ];
      $arr_item = $arr_items[ $str_key ][ 'arrQuery.' ][ 'andWhere' ];
      // #i0079, 140724, dwildt, 1-
      //$conf_sql[ 'andWhere' ] = $conf_sql[ 'andWhere' ] . $arr_item;
      // #i0079, 140724, dwildt, 8+
      if ( empty( $conf_sql[ 'andWhere' ] ) )
      {
        $conf_sql[ 'andWhere' ] = $arr_item;
      }
      else
      {
        $conf_sql[ 'andWhere' ] = $conf_sql[ 'andWhere' ] . ' AND ' . $arr_item;
      }
    }
    // plugin [template] int_templating_dataQuery has a value
    // and where data query
    //////////////////////////////////////////////////////
    //
      // Clean up LF and CR (Line Feed and Carriage Return)

    foreach ( $arr_query_parts as $str_query_part )
    {
      $conf_sql[ $str_query_part ] = $this->pObj->objZz->cleanUp_lfCr_doubleSpace
              (
              $conf_sql[ $str_query_part ]
      );
    }
    // Clean up LF and CR (Line Feed and Carriage Return)
    //////////////////////////////////////////////////////
    //
      // Replace SQL function with an alias
    // LOOP replace each SQL function which its alias
    foreach ( ( array ) $this->conf_view[ 'select.' ][ 'deal_as_table.' ] as $arr_dealastable )
    {
      $str_statement = $arr_dealastable[ 'statement' ];
      $str_aliasTable = $arr_dealastable[ 'alias' ];
      $arr_dealAlias[ $str_aliasTable ] = $str_statement;
      // I.e.: $conf_sql['select'] = CONCAT(tx_bzdstaffdirectory_persons.title, ' ', tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name), tx_bzdstaffdirectory_groups.group_name
      $conf_sql[ 'select' ] = str_replace( $str_statement, $str_aliasTable, $conf_sql[ 'select' ] );
      // I.e.: $conf_sql['select'] = tx_bzdstaffdirectory_persons.last_name, tx_bzdstaffdirectory_groups.group_name
    }
    // LOOP replace each SQL function which its alias
    // Replace SQL function with an alias
    ////////////////////////////////////////////////////////////////////
    //
      // Does ORDER BY contains further tables and fields?

    $arr_addToSelect = false;
    $csvOrderByWoAscDesc = $this->pObj->objSqlFun_3x->get_orderBy_tableFields( $conf_sql[ 'orderBy' ] );
    $arrOrderByWoAscDesc = $this->pObj->objZz->getCSVasArray( $csvOrderByWoAscDesc );
    $arrSelect = $this->pObj->objZz->getCSVasArray( $conf_sql[ 'select' ] );

    // #110110, cweiske, '11870
    foreach ( $arrSelect as $key => $field )
    {
      $arrSelect[ $key ] = $this->pObj->objSqlFun_3x->get_sql_alias_behind( $field );
    }
    // #110110, cweiske, '11870
    // Is there any difference?
    $arr_addToSelect = array_diff( $arrOrderByWoAscDesc, $arrSelect );
    // Does ORDER BY contains further tables and fields?
    ////////////////////////////////////////////////////////////////////
    //
      // IF order by has new tableFields

    if ( count( ( array ) $arr_addToSelect ) > 1 )
    {
      // SELECT has aliases
      if ( !( strpos( $conf_sql[ 'select' ], " AS " ) === false ) )
      {
        foreach ( ( array ) $arr_addToSelect as $tableField )
        {
          $conf_sql[ 'select' ] = $conf_sql[ 'select' ] . ', ' . $tableField . ' AS \'' . $tableField . '\'';
        }
      }
      // SELECT has aliases
      // SELECT hasn't aliases
      if ( strpos( $conf_sql[ 'select' ], " AS " ) === false )
      {
        $csvAddToSelect = implode( ', ', $arr_addToSelect );
        $conf_sql[ 'select' ] = $conf_sql[ 'select' ] . ', ' . $csvAddToSelect;
      }
      // SELECT hasn't aliases
      // Add the new table.fields to the consolidation array
//      if( ! is_array( $this->pObj->arrConsolidate['addedTableFields'] ) )
//      {
//        $this->pObj->arrConsolidate['addedTableFields'] = array( );
//      }
      $this->pObj->arrConsolidate[ 'addedTableFields' ] = array_merge
              (
              ( array ) $this->pObj->arrConsolidate[ 'addedTableFields' ], $arr_addToSelect
      );
      // Add the new table.fields to the consolidation array
    }
    // IF order by has new tableFields
    //////////////////////////////////////////////////////
    //
      // Add aliases to the SELECT statement
    // There is a 'bug' in exec_SELECTgetRows:
    // It cleares all tables in the selection statement. So it isn't possible to select fields
    // from different tables, if it has the same name.
    // SOLUTION: Aliasing all select values with AS
    // EXAMPLE: tx_ships_main.g2_name AS 'tx_ships_main.g2_name' ...

    $arr_aliasedSelect = null;

    // LOOP all tableFields from select
    $arr_tableFields = explode( ',', $conf_sql[ 'select' ] );
//var_dump( __METHOD__, __LINE__, $arr_tableFields );
//$this->pObj->dev_var_dump( $arr_tableFields );
//exit;
    foreach ( $arr_tableFields as $tableField )
    {
      // 3.9.19, dwildt, 1+
      $tableField = trim( $tableField );
      if ( empty( $tableField ) )
      {
        continue;
      }
      // 110110, cweiske, #11870
      if ( strpos( $tableField, ' AS ' ) !== false )
      {
        $arr_aliasedSelect[] = $tableField;
        continue;
      }
      // 110110, cweiske, #11870

      list( $table, $field ) = explode( '.', $tableField );
      $table = trim( $table );
      $field = trim( $field );

      $tableField = $table . '.' . $field;
      $alias = $tableField;

      // Do we have a function instead of table.field?
      if ( $arr_dealAlias[ $tableField ] )
      {
        $tableField = $arr_dealAlias[ $tableField ];
        // We want the sytax: function AS 'table.field'
        // I.e.: CONCAT(tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name) AS 'tx_bzdstaffdirectory_persons.last_name'
      }
      // Do we have a function instead of table.field?

      $arr_aliasedSelect[] = $tableField . ' AS \'' . $alias . '\'';
    }
    // LOOP all tableFields from select
    // #i0115, 141220, dwildt, -/+
    //$str_aliasedSelect = implode( ', ', $arr_aliasedSelect );
    $str_aliasedSelect = implode( ', ', ( array ) $arr_aliasedSelect );
    $conf_sql[ 'select' ] = $str_aliasedSelect;
    // Add aliases to the SELECT statement
    //////////////////////////////////////////////////////
    //
      // Set the global array conf_sql

    $this->pObj->conf_sql = $conf_sql;
    // Set the global array conf_sql

    return $conf_sql;
  }

  /**
   * set_confSqlDevider()  : Sets the global vars $str_sqlDeviderDisplay and $str_sqlDeviderWorkflow
   *
   * @return	string		sqlDevider
   * @version 5.0.0
   * @since 2.0.0
   */
  public function set_confSqlDevider()
  {
    static $str_devider = null;

    if ( $str_devider !== null )
    {
      return $str_devider;
    }
    $this->set_confSqlDeviderRecords();
    $this->set_confSqlDeviderWorkflow();

    $str_devider = $this->str_sqlDeviderDisplay . $this->str_sqlDeviderWorkflow;

    return $str_devider;
  }

  /**
   * set_confSqlDeviderRecords()  : Sets the global vars $str_sqlDeviderDisplay
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function set_confSqlDeviderRecords()
  {
    // Get global or local configuration advanced array
    $confAdvanced = $this->getConfAdvanced();

    $name = $confAdvanced[ 'sql.' ][ 'devider.' ][ 'childrenRecords' ];
    $conf = $confAdvanced[ 'sql.' ][ 'devider.' ][ 'childrenRecords.' ];
    switch ( true )
    {
      case(!is_array( $conf )):
        $value = $name;
        break;
      case( is_array( $conf )):
      default:
        $value = $this->str_sqlDeviderDisplay = $this->cObjGetSingle( $name, $conf );
        break;
    }
    return $value;
  }

  /**
   * set_confSqlDeviderWorkflow()  : Sets the global var $str_sqlDeviderWorkflow
   *
   * @return	void
   * @version 5.0.0
   * @since 2.0.0
   */
  private function set_confSqlDeviderWorkflow()
  {
    // Get global or local configuration advanced array
    $confAdvanced = $this->getConfAdvanced();

    $name = $confAdvanced[ 'sql.' ][ 'devider.' ][ 'workflow' ];
    $conf = $confAdvanced[ 'sql.' ][ 'devider.' ][ 'workflow.' ];
    switch ( true )
    {
      case(!is_array( $conf )):
        $value = $name;
        break;
      case( is_array( $conf )):
      default:
        $value = $this->str_sqlDeviderWorkflow = $this->cObjGetSingle( $name, $conf );
        break;
    }
    return $value;
  }

  /**
   * set_confSql_groupBy() : THIS ISN'T THE GROUPBY FOR THE SQL QUERY
   *                         Allocates a proper group by in the global groupBy
   *                         It returns the group by part, which is needed for consolidation
   *                         If there is more than one value, all other values will be removed
   *                         If there are aliases, the aliases will be deleted.
   *
   * @return	string		$groupBy: The first groupBy value with ASC or DESC, if there is one
   * @version  4.5.14
   * @since    3.0.0
   */
  private function set_confSql_groupBy()
  {

    ////////////////////////////////////////////////////////////////////
    //
    // RETURN if there isn't any groubBy in the TypoScript

    if ( !isset( $this->conf_view[ 'groupBy' ] ) )
    {
      return false;
    }
    if ( !$this->conf_view[ 'groupBy' ] )
    {
      return false;
    }
    // RETURN if there isn't any groubBy in the TypoScript


    $groupBy = $this->conf_view[ 'groupBy' ];


    ////////////////////////////////////////////////////////////////////
    //
    // Proper ASC and DESC

    $groupBy = str_ireplace( ' desc', ' DESC', $groupBy );
    $groupBy = str_ireplace( ' asc', ' ASC', $groupBy );
    // Proper ASC and DESC
    ////////////////////////////////////////////////////////////////////
    //
    // We like only the first value

    $arr_groupBy = explode( ',', $groupBy );
    if ( count( $arr_groupBy ) > 1 )
    {
      $str_1stGroupBy = $arr_groupBy[ 0 ];
      unset( $arr_groupBy );
      $arr_groupBy[] = $str_1stGroupBy;
      // DRS - Development Reporting System
      if ( $this->pObj->b_drs_sql )
      {
        t3lib_div::devLog( '[WARN/SQL] GROUP BY will be cuted after the first value: ' . $groupBy, $this->pObj->extKey, 2 );
        t3lib_div::devLog( '[HELP/SQL] Please configure \'' . $this->conf_path . '.groupBy\'', $this->pObj->extKey, 1 );
      }
    }
    // We like only the first value
    //////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

    if ( $this->pObj->b_drs_sql )
    {
      t3lib_div::devLog( '[INFO/SQL] GROUP BY is: \'' . $groupBy . '\'. Be aware: this is for php ordering and for consolidation but not for the SQL group-by-clause.', $this->pObj->extKey, -1 );
    }
    // DRS - Development Reporting System

    return $groupBy;
  }

  /*   * *********************************************
   *
   * Wrapper methods
   *
   * ******************************************** */

  /**
   * wrapRow()  : Handle the current row by TypoScript.
   *
   * @param   string  $template      : current template template
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.0
   * @since 5.0.0
   */
  public function wrapRow( $template, $wiDefaultTemplate, $uid = null )
  {
    $this->wrapRowInit( $template, $uid );

    if ( !$this->wrapRowRequirements() )
    {
      return false;
    }

    $markerArray = array();
    $markerArray = ( array ) $this->wrapRowTableForeign( $markerArray );
    $markerArray = ( array ) $this->wrapRowTableLocal( $markerArray );
    $markerArray = ( array ) $this->wrapRowFieldOrder( $markerArray, $wiDefaultTemplate );
    return $markerArray;
  }

  /**
   * wrapRowFieldOrder()  : Returns the markerArray in the order of the table.fields of the SELECT statement
   *
   * @param   string  $markerArraySrcs  : marker array
   * @return	array		$markerArrayDest  : ordered marker array
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowFieldOrder( $markerArraySrce, $wiDefaultTemplate )
  {
    $markerArrayDest = array();
    $tableFields = $this->wrapRowFieldOrderGetTablefields();

    // LOOP each table.field
    foreach ( $tableFields as $tableField )
    {
      $hashMarker = '###' . strtoupper( $tableField ) . '###';
      if ( !isset( $markerArraySrce[ $hashMarker ] ) )
      {
        continue;
      }
      $markerArrayDest[ $hashMarker ] = $markerArraySrce[ $hashMarker ];
      // #i0050, 140630, dwildt, 1+
      unset( $markerArraySrce[ $hashMarker ] );
    } // LOOP each table.field
    // RETURN markerArray in the order of the table.fields in the SELECT statement
    // #i0050, 140630, dwildt, 4+
    if ( $wiDefaultTemplate )
    {
      return $markerArrayDest;
    }

//var_dump(__METHOD__, __LINE__, $markerArrayDest, $markerArraySrce);
    // #i0050, 140630, dwildt, 1+
    $markerArrayDest = $markerArrayDest + $markerArraySrce;
//var_dump(__METHOD__, __LINE__, $markerArrayDest);
//die(':(');
    return $markerArrayDest;
  }

  /**
   * wrapRowFieldOrderGetTablefields()  : Gets the table.fields from the SELECT statement in the given order
   *
   * @return	array		$tableFields
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowFieldOrderGetTablefields()
  {
    $statements = $this->pObj->objSqlAut->get_statements();
    $csvSelect = $statements[ 'data' ][ 'select' ];
    $csvSelect = $this->pObj->objSqlFun->getStatementWoAscDesc( $csvSelect );
    $tableFields = $this->pObj->objZz->getCSVasArray( $csvSelect );

    return $tableFields;
  }

  /**
   * wrapRowInit() : Initial methods
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInit( $template, $uid )
  {
    $this->wrapRowInitAutoDiscoverItems();
    $this->wrapRowInitSetRows();
    $this->wrapRowInitSetTableFields();
    $this->wrapRowInitSetTablesForeign();
    $this->wrapRowInitSetTemplate( $template );
    $this->wrapRowInitSetUidOfCurrentRecord( $uid );
    $this->wrapRowInitSetUids();
    $this->wrapRowInitRequireClasses();
  }

  /**
   * wrapRowInitRequireClasses() :
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitRequireClasses()
  {
    $this->wrapRowInitRequireClassesHandleAs();
  }

  /**
   * wrapRowInitRequireClassesHandleAs() :
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitRequireClassesHandleAs()
  {
    require_once('class.tx_browser_pi1_typoscriptHandleAs.php');
    $this->objHandleAs = new tx_browser_pi1_typoscriptHandleAs( $this );
  }

  /**
   * wrapRowInitAutoDiscoverItems() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitAutoDiscoverItems()
  {
    if ( $this->autoDiscoverItems !== null )
    {
      return;
    }
    $autoconfig = $this->conf_view[ 'autoconfig.' ];
    if ( is_array( $autoconfig ) )
    {
      $this->autoDiscoverItems = $autoconfig[ 'autoDiscover.' ][ 'items.' ];
      return;
    }

    $autoconfig = $this->pObj->conf[ 'autoconfig.' ];
    $this->autoDiscoverItems = $autoconfig[ 'autoDiscover.' ][ 'items.' ];
    if ( !$this->pObj->b_drs_typoscript )
    {
      return;
    }

    $prompt = 'views.single.X. hasn\'t any autoconf array.<br />
          We take the global one.';
    t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return;
  }

  /**
   * wrapRowInitSetRows() : Set the class var rows to the content of the global rowsLocalised
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetRows()
  {
    $this->rows = $this->pObj->rowsLocalised;
    if ( empty( $this->rows ) )
    {
      $header = 'FATAL ERROR!';
      $text = 'Rows are empty.';
      $this->pObj->drs_die( $header, $text );
    }
  }

  /**
   * wrapRowInitSetTableFields() : Get the keys (tableFields) of the current row
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetTableFields()
  {
    $this->tableFields = array_keys( $this->getFirstRow() );

    switch ( true )
    {
      case(!is_array( $this->tableFields )):
      case(empty( $this->tableFields )):
        $header = 'FATAL ERROR!';
        $text = 'tableFields are empty.';
        $this->pObj->drs_die( $header, $text );
    }
  }

  /**
   * wrapRowInitSetTablesForeign() : List the involved foreign tables in the class var $tablesForeign
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetTablesForeign()
  {
    $tablesForeign = array();
    $this->tablesForeign = null;
    foreach ( ( array ) $this->tableFields as $tableField )
    {
      list( $table, $field ) = explode( '.', $tableField );
      if ( $table == $this->pObj->localTable )
      {
        continue;
      }
      if ( $field != 'uid' )
      {
        continue;
      }
      $tablesForeign[] = $table;
      continue;
    }
    $tablesForeign = array_unique( $tablesForeign );

    $this->tablesForeign = $tablesForeign;
  }

  /**
   * wrapRowInitSetTemplate() : Set the class var rows to the content of the global rowsLocalised
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetTemplate( $template )
  {
    $this->template = $template;
  }

  /**
   * wrapRowInitSetUidOfCurrentRecord() : Set the class var rows to the content of the global rowsLocalised
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidOfCurrentRecord( $uid )
  {
    $this->uidOfCurrentRecord = $uid;
  }

  /**
   * wrapRowInitSetUids() : List the uids of the records of all involved tables
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUids()
  {
    $this->uids = null;
    $this->wrapRowInitSetUidsLocal();
    $this->wrapRowInitSetUidsForeign();
  }

  /**
   * wrapRowInitSetUidsForeign() : List the uids of the records of all involved foreign tables
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidsForeign()
  {
    foreach ( ( array ) $this->tablesForeign as $tableForeign )
    {
      $this->wrapRowInitSetUidsForeignPerTable( $tableForeign );
    }
  }

  /**
   * wrapRowInitSetUidsForeignPerTable() : List the uids of the records of all involved foreign tables
   *
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidsForeignPerTable( $tableForeign )
  {
    switch ( true )
    {
      case($this->uidOfCurrentRecord === null):
        $this->wrapRowInitSetUidsForeignPerTableViewSingle( $tableForeign );
        break;
      case($this->uidOfCurrentRecord !== null):
      default:
        $this->wrapRowInitSetUidsForeignPerTableViewList( $tableForeign );
        break;
    }
  }

  /**
   * wrapRowInitSetUidsForeignPerTableViewList() : List the uids of the records of all involved foreign tables
   *
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidsForeignPerTableViewList( $tableForeign )
  {
    // Get the label of the local table
    $tableLocal = $this->pObj->localTable;

    // LOOP rows
    foreach ( ( array ) $this->rows as $row )
    {
      $localUid = $row[ $tableLocal . '.uid' ];
      if ( $this->uidOfCurrentRecord != $localUid )
      {
        continue;
      }
      $foreignUid = $row[ $tableForeign . '.uid' ];
      if ( $foreignUid === null )
      {
        continue;
      }
      $this->uids[ $tableForeign ][] = $foreignUid;
    }

    if ( !is_array( $this->uids[ $tableForeign ] ) )
    {
      return;
    }

    // Make uids uinique
    $this->uids[ $tableForeign ] = array_unique( $this->uids[ $tableForeign ] );
  }

  /**
   * wrapRowInitSetUidsForeignPerTableViewSingle() : List the uids of the records of all involved foreign tables
   *
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidsForeignPerTableViewSingle( $tableForeign )
  {
    // LOOP rows
    foreach ( ( array ) $this->rows as $rows )
    {
      $uid = $rows[ $tableForeign . '.uid' ];
      if ( $uid === null )
      {
        continue;
      }
      $this->uids[ $tableForeign ][] = $uid;
    }
    if ( !is_array( $this->uids[ $tableForeign ] ) )
    {
      return;
    }

    // Make uids uinique
    $this->uids[ $tableForeign ] = array_unique( $this->uids[ $tableForeign ] );
  }

  /**
   * wrapRowInitSetUidsLocal()  : List the uid of the records of the local tables
   *                              There must be exactly one uid.
   *
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowInitSetUidsLocal()
  {
    // Get the label of the local table
    $tableLocal = $this->pObj->localTable;

    // LOOP rows
    foreach ( ( array ) $this->rows as $rows )
    {
      $uid = $rows[ $tableLocal . '.uid' ];
      if ( $uid === null )
      {
        continue;
      }
      if ( $this->uidOfCurrentRecord !== null && $this->uidOfCurrentRecord !== $uid )
      {
        continue;
      }
      $this->uids[ $tableLocal ][] = $uid;
    }

    // Make uids uinique
    $this->uids[ $tableLocal ] = array_unique( $this->uids[ $tableLocal ] );

    if ( count( $this->uids[ $tableLocal ] ) == 1 )
    {
      return;
    }

    // ERROR  : There isn't any or more than one uid for the localtable
    $header = 'FATAL ERROR!';
    $text = 'There isn\'t any or more than one uid for the localtable.';
    $this->pObj->drs_die( $header, $text );
  }

  /**
   * wrapRowRequirements()  :
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowRequirements()
  {
    if ( $this->wrapRowRequirementsRowIsNull() )
    {
      return false;
    }

    return true;
  }

  /**
   * wrapRowRequirementsEmptyRow()  :
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowRequirementsRowIsNull()
  {
    $bool_dontHandleEmptyValues = $this->pObj->objFlexform->bool_dontHandleEmptyValues;

    // RETURN : row is empty
    if ( !$bool_dontHandleEmptyValues )
    {
      return false;
    }

    $strRow = implode( '', $this->getFirstRow() );
    if ( $strRow !== null )
    {
      return false;
    }

    return true;
  }

  /**
   * wrapRowTableForeign() : Handle the fields of the foreign tables by TypoScript
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeign( $markerArray )
  {
    foreach ( ( array ) $this->tablesForeign as $tableForeign )
    {
      $markerArray = $this->wrapRowTableForeignRows( $markerArray, $tableForeign );
    }
//    var_dump( __METHOD__, __LINE__, $this->uids, $markerArray );
    return $markerArray;
  }

  /**
   * wrapRowTableForeignField() : Handle the current fields of the foreign table by TypoScript of the given tableField
   *
   * @param	string		$tableField         : name of the foreign tableField. I.e: tx_org_jobcat.title
   * @param	array		$row                : current row
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeignField( $tableField, $row )
  {
    $this->cObjDataAdd( $row );
    $handledTableField = $this->cObjGetSingleTableField( $tableField, $row );
    $this->cObjDataReset();
    return $handledTableField;
  }

  /**
   * wrapRowTableForeignGetFields() : List of tableFields of all records of all foreign tables
   *
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	array		$fields       : list of tableFields
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeignGetFields( $tableForeign )
  {
    $fields = array();

    foreach ( ( array ) $this->tableFields as $tableField )
    {
      list( $table ) = explode( '.', $tableField );
      if ( $table != $tableForeign )
      {
        continue;
      }
      $fields[] = $tableField;
      continue;
    }
    $fields = array_unique( ( array ) $fields );

    return $fields;
  }

  /**
   * wrapRowTableForeignRequirements() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeignRequirements( $tableForeign, $tableField )
  {
    switch ( true )
    {
      case(!$this->wrapTableFieldRequirementsFieldIsFromForeignTable( $tableForeign, $tableField )):
      case(!$this->wrapTableFieldRequirements( $tableField )):
        return false;
      default:
        return true;
    }
  }

  /**
   * wrapRowTableForeignRows()  : Handle the fields of the current foreign table for all children by TypoScript
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.14
   * @since 5.0.0
   */
  private function wrapRowTableForeignRows( $markerArray, $tableForeign )
  {
    $uidsForeign = $this->uids[ $tableForeign ];
    switch ( true )
    {
      case(!is_array( $uidsForeign )):
        $markerArray = $this->wrapRowTableForeignRowsWoChildren( $markerArray, $tableForeign );
        break;
      case( is_array( $uidsForeign )):
      default:
        $markerArray = $this->wrapRowTableForeignRowsWiChildren( $markerArray, $tableForeign );
        break;
    }

    return $markerArray;
  }

  /**
   * wrapRowTableForeignRowsWiChildren()  :
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.14
   * @since 5.0.0
   */
  private function wrapRowTableForeignRowsWiChildren( $markerArray, $tableForeign )
  {
    $values = array();
    $uidsForeign = $this->uids[ $tableForeign ];

    foreach ( ( array ) $uidsForeign as $uidForeign )
    {
      foreach ( $this->rows as $row )
      {
        if ( $row[ $tableForeign . '.uid' ] == $uidForeign )
        {
          break;
        }
        unset( $row );
      }
      $this->wrapRowTableForeignRowsDie( $row );
      foreach ( array_keys( ( array ) $row ) as $tableField )
      {
        if ( !$this->wrapRowTableForeignRequirements( $tableForeign, $tableField ) )
        {
          continue;
        }
        $hashMarker = '###' . strtoupper( $tableField ) . '###';
        $values[ $hashMarker ][] = $this->wrapRowTableForeignField( $tableField, $row );
      }
    }

    foreach ( $values as $hashMarker => $value )
    {
      $markerArray[ $hashMarker ] = $this->wrapRowTableForeignRowsDevideFields( $value, $hashMarker );
    }
    return $markerArray;
  }

  /**
   * wrapRowTableForeignRowsWoChildren()  :
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @param	string		$tableForeign : name of the foreign table. I.e: tx_org_jobcat
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.14
   * @since 5.0.14
   * @internal #i0075
   */
  private function wrapRowTableForeignRowsWoChildren( $markerArray, $tableForeign )
  {
    $values = array();

    $firstKey = key( $this->rows );
    $row = $this->rows[ $firstKey ];

    if ( empty( $row ) )
    {
      return $markerArray;
    }

    foreach ( array_keys( ( array ) $row ) as $tableField )
    {
      if ( !$this->wrapRowTableForeignRequirements( $tableForeign, $tableField ) )
      {
        continue;
      }
      $hashMarker = '###' . strtoupper( $tableField ) . '###';
      $values[ $hashMarker ][] = null;
    }

    foreach ( $values as $hashMarker => $value )
    {
      $markerArray[ $hashMarker ] = $this->wrapRowTableForeignRowsDevideFields( $value, $hashMarker );
    }
    return $markerArray;
  }

  /**
   * wrapRowTableForeignRowsDevideFields()  : Devide fields with the devider
   *
   * @param	array     $values : current values
   * @return	string	$value  : devided values
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeignRowsDevideFields( $values, $hashMarker )
  {
    static $devider = array();

    $tableField = $this->zz_hashMarkerToTableField( $hashMarker );

    if ( isset( $devider[ $tableField ] ) )
    {
      $value = implode( $devider[ $tableField ], ( array ) $values );
      return $value;
    }

    $arrDevider = $this->getDeviderPerTableField( $tableField );
    if ( $arrDevider[ 'isset' ] )
    {
      $devider[ $tableField ] = $arrDevider[ 'devider' ];
      $value = implode( $devider[ $tableField ], ( array ) $values );
//var_dump(__METHOD__, __LINE__, $devider, $value);
      return $value;
    }

    $this->set_confSqlDevider();
    $devider[ $tableField ] = $this->str_sqlDeviderDisplay;

    $value = implode( $devider[ $tableField ], ( array ) $values );
//var_dump(__METHOD__, __LINE__, $devider, $value);
    return $value;
  }

  /**
   * wrapRowTableForeignRowsDie()  : Dies if row is empty
   *
   * @param	array		$row  : current row
   * @return	void
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableForeignRowsDie( $row )
  {
    // RETURN : row is proper
    if ( !empty( $row ) )
    {
      return;
    }

    // DIE : row is empty, prompt the error
    $header = 'FATAL ERROR!';
    $text = 'There isn\'t any row.';
    $this->pObj->drs_die( $header, $text );
  }

  /**
   * wrapRowTableLocal() :  Handle the fields of the local table by TypoScript
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableLocal( $markerArray )
  {
    $row = $this->getFirstRow();
    $row = $this->wrapRowTableLocalForeignMarker( $markerArray, $row );

    $this->cObjDataAdd( $row );
    foreach ( array_keys( ( array ) $row ) as $tableField )
    {
      if ( !$this->wrapRowTableLocalRequirements( $this->pObj->localTable, $tableField ) )
      {
        continue;
      }
      $markerArray = $this->wrapRowTableLocalField( $markerArray, $tableField, $row );
    }
    $markerArray = $markerArray + $this->pObj->objWrapper4x->constant_markers( $row );
    $this->cObjDataReset();
    return $markerArray;
  }

  /**
   * wrapRowTableLocalField() : Handle the fields of the local table by the TypoScript of the given tableField
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @param	string		$tableField   : name of the local tableField. I.e: tx_org_job.title
   * @param	array		$row          : current row
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableLocalField( $markerArray, $tableField, $row )
  {
    $hashMarker = '###' . strtoupper( $tableField ) . '###';
    $markerArray[ $hashMarker ] = $this->cObjGetSingleTableField( $tableField, $row );
    $markerArray = $this->wrapRowTableLocalFieldTitle( $markerArray, $tableField, $hashMarker );

    return $markerArray;
  }

  /**
   * wrapRowTableLocalFieldTitle() : Handle the fields of the local table by the TypoScript of the given tableField
   *
   * @param	array		$markerArray  : array with tableField hash marker
   * @param	string		$tableField   : name of the local tableField. I.e: tx_org_job.title
   * @param	array		$row          : current row
   * @return	array		$markerArray  : array with tableField hash marker
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableLocalFieldTitle( $markerArray, $tableField, $hashMarker )
  {
    // :TODO: ###TITLE### gehoert nicht in demn Body sondern in den Head
    return $markerArray;
//    $key = $this->objHandleAs->getHandleAsKey( $tableField );
//    if ( $key != 'title' )
//    {
//      return $markerArray;
//    }
//
//    $markerArray[ '###TITLE###' ] = $markerArray[ $hashMarker ];
//    return $markerArray;
  }

  /**
   * wrapRowTableLocalForeignMarker() :   Replace tableFields with the wrapped vales in the current row.
   *                                      Because the local table is wrapped after foreigen tables are wrapped,
   *                                      the current marker array contains tableFields of foreign tables only.
   *                                      The current marker array MUST NOT contain any wrapped tableField of the local table!
   *
   * @param	array		$markerArray  : array with tableField hash marker of foreign tables
   * @return	array		$markerArray  : array with tableField hash marker of foreign tables and local table
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableLocalForeignMarker( $markerArray, $row )
  {
    foreach ( ( array ) $markerArray as $hashTableField => $value )
    {
      $tableField = strtolower( str_replace( '###', null, $hashTableField ) );
      $row[ $tableField ] = $value;
    }
    return $row;
  }

  /**
   * wrapRowTableLocalGetFields() : List of tableFields of the local table
   *
   * @return	array		$fields : List of tableFields
   * @version 6.0.7
   * @since 5.0.0
   * @internal #i0107
   */
  private function wrapRowTableLocalGetFields()
  {
    static $staticArray = array();
    $fields = array();

    $pluginId = $this->pObj->cObj->data[ 'uid' ];
    if ( !isset( $staticArray[ $pluginId ][ 'firstLoop' ] ) )
    {
      $staticArray[ $pluginId ][ 'firstLoop' ] = true;
    }

    if ( !$staticArray[ $pluginId ][ 'firstLoop' ] )
    {
      return $staticArray[ $pluginId ][ 'fields' ];
    }

    foreach ( ( array ) $this->tableFields as $tableField )
    {
      list( $table ) = explode( '.', $tableField );
      if ( $table != $this->pObj->localTable )
      {
        continue;
      }
      $fields[] = $tableField;
      continue;
    }

    $staticArray[ $pluginId ][ 'fields' ] = array_unique( ( array ) $fields );
    $staticArray[ $pluginId ][ 'firstLoop' ] = true;
    return $staticArray[ $pluginId ][ 'fields' ];
  }

  /**
   * wrapRowTableLocalRequirements() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapRowTableLocalRequirements( $tableLocal, $tableField )
  {
    switch ( true )
    {
      case(!$this->wrapTableFieldRequirementsFieldIsFromLocalTable( $tableLocal, $tableField )):
      case(!$this->wrapTableFieldRequirements( $tableField )):
        return false;
      default:
        return true;
    }
  }

  /**
   * wrapTableFieldRequirements() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapTableFieldRequirements( $tableField )
  {
    switch ( true )
    {
      case($this->wrapTableFieldRequirementsFieldIsInConsolidationArray( $tableField )):
      case($this->wrapTableFieldRequirementsFieldIsInRemoveArray( $tableField )):
// :TODO: Folgende drei Zeilen: Pruefung Feldwert ist an dieser Stelle nicht moeglich.
//      case($this->wrapTableFieldRequirementsHandleAsImage( $tableField )):
//      case($this->wrapTableFieldRequirementsListViewFirstFieldUid( $tableField )):
//      case($this->wrapTableFieldRequirementsSingleViewNullValue( $tableField )):
        return false;
      default:
        return true;
    }
  }

  /**
   * wrapTableFieldRequirementsFieldIsFromForeignTable() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapTableFieldRequirementsFieldIsFromForeignTable( $tableForeign, $tableField )
  {
    $tableForeignFields = $this->wrapRowTableForeignGetFields( $tableForeign );

    if ( !in_array( $tableField, $tableForeignFields ) )
    {
      return false;
    }

    return true;
  }

  /**
   * wrapTableFieldRequirementsFieldIsFromLocalTable() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapTableFieldRequirementsFieldIsFromLocalTable( $tableLocal, $tableField )
  {
    $tableFields = $this->wrapRowTableLocalGetFields( $tableLocal );
    if ( !in_array( $tableField, $tableFields ) )
    {
      return false;
    }

    return true;
  }

  /**
   * wrapTableFieldRequirementsFieldIsInConsolidationArray() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapTableFieldRequirementsFieldIsInConsolidationArray( $tableField )
  {
    $addedTableFields = $this->pObj->arrConsolidate[ 'addedTableFields' ];
    if ( in_array( $tableField, ( array ) $addedTableFields ) )
    {
      return true;
    }

//    if ( $this->pObj->boolFirstRow && ($this->pObj->b_drs_templating || $this->pObj->b_drs_typoscript) )
//    {
//      $prompt = $key . ' is in the uid/pid list of the consolidation array. It shouldn\'t displayed.';
//      t3lib_div::devLog( '[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0 );
//    }
    return false;
  }

  /**
   * wrapTableFieldRequirementsFieldIsInRemoveArray() : Returns true, if all requirements match
   *
   * @param   string    $tableField :
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function wrapTableFieldRequirementsFieldIsInRemoveArray( $tableField )
  {
    $arr_rmFields = $this->pObj->objTemplate->arr_rmFields;
    if ( in_array( $tableField, ( array ) $arr_rmFields ) )
    {
      return true;
    }
    return false;
  }

  /**
   * zz_hashMarkerToTableField()  : Moves a hash marker to a table.field
   *
   * @param   string  $hashMarker : hashMarker like ###TX_ORG_JOB.TITLE###
   * @return	string	$tableField : table.field like tx_org_job.title
   * @version 5.0.0
   * @since 5.0.0
   */
  private function zz_hashMarkerToTableField( $hashMarker )
  {
    $tableField = str_replace( '###', null, strtolower( $hashMarker ) );

    return $tableField;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_typoscript.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_typoscript.php' ]);
}
?>