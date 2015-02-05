<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_multisort bundles methods for ordering rows.
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version  5.0.0
 * @since    3.4.4
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   57: class tx_browser_pi1_multisort
 *   73:     function __construct($parentObj)
 *  103:     function main()
 *  330:     function multisort_mm_children_list($rows)
 *  361:     function multisort_mm_children( $rows )
 *  490:     function multisort_mm_children_single($rows)
 *
 *              SECTION: Helper
 *  856:     function multisort_upto_6_level($arguments)
 *  949:     function multisortRows($arguments, $rows)
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_multisort
{

  // [object] The parent object
  public $pObj;
  private $conf_view = null;
  private $conf_path = null;
  // [Array] current rows
  private $rows;
  // [Boolean]
  private $resetGlobalRows;

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;
    // #i0084, 140922, dwildt, 2+
    $this->conf_path = $this->pObj->get_confPath();
    $this->conf_view = $this->pObj->get_confView();
  }

  /**
   * main( ): Order the rows depending on csvOrderBy and piVars[sort]
   *
   * @return	void
   * @version  5.0.0
   * @since    3.4.4
   */
  private function init( $rows )
  {
    $this->rowsInit( $rows );
    return $this->initRequirements();
  }

  /**
   * initRequirements( ):
   *
   * @return	boolean           True, if requirements are matched. False, if not.
   * @version  5.0.0
   * @since    3.4.4
   */
  private function initRequirements()
  {
    switch ( true )
    {
      case($this->initRequirementsNoRows()):
      case($this->initRequirementsRandom()):
      case($this->initRequirementsSynonyms()):
      case($this->initRequirementsWoOrderBy()):
        return false;
      default:
        return true;
    }
  }

  /**
   * initRequirementsNoRows( ):
   *
   * @return	boolean           True, if requirements are matched. False, if not.
   * @version  5.0.0
   * @since    3.4.4
   */
  private function initRequirementsNoRows()
  {
    // RETURN false : no no rows
    if ( !is_array( $this->rows ) )
    {
      return false;
    }

    // RETURN true : no rows without DRS prompt
    if ( !($this->pObj->b_drs_orderby || $this->pObj->b_drs_sql) )
    {
      return true;
    }

    // RETURN true : no rows with DRS prompt
    $prompt = 'Abort main(). There isn\'t any row.';
    t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * initRequirementsRandom( ):
   *
   * @return	boolean           True, if requirements are matched. False, if not.
   * @version  5.0.0
   * @since    3.4.4
   */
  private function initRequirementsRandom()
  {
    // RETURN false : no random mode
    if ( !$this->conf_view[ 'random' ] )
    {
      return false;
    }

    // RETURN true : random mode without DRS prompt
    if ( !($this->pObj->b_drs_orderby || $this->pObj->b_drs_sql) )
    {
      return true;
    }

    // RETURN true : random mode with DRS prompt
    $prompt = 'Abort main(). No ORDER BY here - random mode is enabled!';
    t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * initRequirementsSynonyms( ):
   *
   * @return	boolean           True, if requirements are matched. False, if not.
   * @version  5.0.0
   * @since    3.4.4
   */
  private function initRequirementsSynonyms()
  {
    // RETURN false : no synonyms mode
    if ( !$this->conf_view[ 'functions.' ][ 'synonym' ] )
    {
      return false;
    }

    // RETURN true : synonyms mode without DRS prompt
    if ( !($this->pObj->b_drs_orderby || $this->pObj->b_drs_sql) )
    {
      return true;
    }

    // RETURN true : synonyms mode with DRS prompt
    $prompt = 'Abort main(). No ORDER BY here - synonyms mode is enabled!';
    t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * initRequirementsWoOrderBy( ):
   *
   * @return	boolean           True, if requirements are matched. False, if not.
   * @version  5.0.0
   * @since    3.4.4
   */
  private function initRequirementsWoOrderBy()
  {
    $statements = $this->pObj->objSqlAut->get_statements();
    $csvOrderBy = $statements[ 'data' ][ 'orderBy' ];
    $csvOrderBy = $this->pObj->objSqlFun->getStatementWoAscDesc( $csvOrderBy );
    $arrOrderBy = $this->pObj->objZz->getCSVasArray( $csvOrderBy );

    switch ( true )
    {
      case(!is_array( $arrOrderBy )):
      case(empty( $arrOrderBy )):
        // follow the workflow (returns true)
        break;
      default:
        // RETURN false : ORDER BY statement
        return false;
    }

    // RETURN true : no ORDER BY statement without DRS prompt
    if ( !($this->pObj->b_drs_orderby || $this->pObj->b_drs_sql) )
    {
      return true;
    }

    // RETURN true : no ORDER BY statement with DRS prompt
    $prompt = 'Abort main(). There is no orderBy clause.';
    t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
    return true;
  }

  /**
   * main( ): Order the rows depending on csvOrderBy and piVars[sort]
   *
   * @return	void
   * @version  5.0.0
   * @since    3.4.4
   */
  public function main( $rows = null )
  {

    if ( $this->init( $rows ) )
    {
      return;
    }

    // 140627, dwildt, 2-
//    // Remove keys, which aren't existing
//    $arrOrderByWoAscDesc = $this->multisort_rowsRemoveNonExistingKeys();

    $arguments = $this->multisortGetArguments();
    $this->rows = $this->multisortRows( $arguments );

    $this->rowsReset();
    // Write the result to the global rows array
  }

  /**
   * multisortGetArguments( ): Get the arguments for PHP method array_multisort
   *
   * @return	array
   * @version  5.0.0
   * @since    3.4.4
   */
  private function multisortGetArguments()
  {
    $tablefieldProperties = $this->multisortGetArgumentsTablefieldProperties();
    $arguments = $this->multisortGetArgumentsForPhpMethod( $tablefieldProperties );

    return $arguments;
  }

  /**
   * multisortGetArgumentsForPhpMethod( ):
   *
   * @return	array
   * @version  5.0.0
   * @since    5.0.0
   */
  private function multisortGetArgumentsForPhpMethod( $tablefieldProperties )
  {
    $arguments = array();
    $rows = $this->rows;
    reset( $rows );

    // LOOP tablefield properties
    $counterArgument = 0;
    foreach ( ( array ) $tablefieldProperties as $tablefieldProperty )
    {
      $arguments = $this->multisortGetArgumentsForPhpMethodPerRow( $arguments, $tablefieldProperty, $counterArgument );
      $counterArgument++;
    } // LOOP tablefield properties
    // RETURN : no DRS prompt needed
    if ( !($this->pObj->b_drs_orderby || $this->pObj->b_drs_sql ) )
    {
      return $arguments;
    }

    // DRS prompt
    $this->multisortGetArgumentsForPhpMethodDRS( $tablefieldProperties, $arguments );
    return $arguments;
  }

  /**
   * multisortGetArgumentsForPhpMethod( ):
   *
   * @return	array
   * @version  5.0.0
   * @since    5.0.0
   */
  private function multisortGetArgumentsForPhpMethodDRS( $tablefieldProperties, $arguments )
  {
    // DRS prompt
    $arr_constant = array(
      1 => 'SORT_NUMERIC',
      2 => 'SORT_STRING',
      3 => 'SORT_DESC',
      4 => 'SORT_ASC'
    );

    $prompt = 'array_multisort( ';
    foreach ( ( array ) $tablefieldProperties as $tablefieldProperty )
    {
      $prompt = $prompt . ' \''
              . $tablefieldProperty[ 'table.field' ] . '\', '
              . $arr_constant[ $tablefieldProperty[ 'int_orderFlag' ] ] . ', '
              . $arr_constant[ $tablefieldProperty[ 'int_typeFlag' ] ] . '; '
      ;
    }
    $prompt = $prompt . ' ); '
            . 'Rows are ordered by PHP: ' . $prompt . ' '
            . 'Level: ' . ( count( $arguments ) - 1 )
    ;
    t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
  }

  /**
   * multisortGetArgumentsForPhpMethod( ):
   *
   * @return	array
   * @version  5.0.0
   * @since    5.0.0
   */
  private function multisortGetArgumentsForPhpMethodPerRow( $arguments, $tablefieldProperty, $counterArgument )
  {
    $rows = $this->rows;
    reset( $rows );

    // LOOP rows
    $counterRow = 0;
    foreach ( ( array ) array_keys( $rows ) as $keyOfRow )
    {
      // CONTINUE : tablefieldProperty caseSensitive is true
      if ( $tablefieldProperty[ 'caseSensitive' ] )
      {
        $value = $rows[ $keyOfRow ][ $tablefieldProperty[ 'table.field' ] ];
        $arguments[ $counterArgument ][ 'table.field' ][ $counterRow ] = $value;
        $counterRow++;
        continue;
      }

      // tablefieldProperty caseSensitive isn't true
      $value = strtolower( $rows[ $keyOfRow ][ $tablefieldProperty[ 'table.field' ] ] );
      $arguments[ $counterArgument ][ 'table.field' ][ $counterRow ] = $value;
      $counterRow++;

      // CONTINUE : no DRS prompt needed
      $this->multisortGetArgumentsForPhpMethodPerRowDRS();
    } // LOOP rows

    $arguments[ $counterArgument ][ 'int_orderFlag' ] = $tablefieldProperty[ 'int_orderFlag' ];
    $arguments[ $counterArgument ][ 'int_typeFlag' ] = $tablefieldProperty[ 'int_typeFlag' ];

    return $arguments;
  }

  /**
   * multisortGetArgumentsForPhpMethodDRS( ):
   *
   * @return	array
   * @version  5.0.0
   * @since    5.0.0
   */
  private function multisortGetArgumentsForPhpMethodPerRowDRS( )
  {
    static $drsPromptIsDone = false;

    // CONTINUE : no DRS prompt needed
    if ( ! $this->pObj->b_drs_warn )
    {
      return;
    }

    // CONTINUE : DRS prompt is done before
    if ( $drsPromptIsDone )
    {
      return;
    }

    // DRS prompt
    $prompt = 'main() uses strtolower(). This is UTF-8 insecure and multibyte insecure!';
    t3lib_div::devlog( '[WARN/UTF-8] ' . $prompt, $this->pObj->extKey, 2 );
    $drsPromptIsDone = true;
  }

  /**
   * multisortGetArguments( ): Get the arguments for PHP method array_multisort
   *
   * @return	array
   * @version  5.0.0
   * @since    5.0.0
   */
  private function multisortGetArgumentsTablefieldProperties()
  {
    $tablefieldProperties = array();
    $arr_usedTableFields = array();  //:todo: Wird nicht gefuellt
    $csvOrderBy = $this->pObj->objSqlAut_3x->orderBy();
    $arrOrderByWiAscDesc = $this->pObj->objZz->getCSVasArray( $csvOrderBy );

    // Building arguments for array_multisort - Part I
    $int_count = 0;
    foreach ( ( array ) $arrOrderByWiAscDesc as $strOrderByField )
    {
      if ( $int_count > 6 )
      {
        if ( $this->pObj->b_drs_warn )
        {
          $prompt = 'The order clause has more than seven items! value is: \'' . $csvOrderBy . '\'.';
          t3lib_div::devlog( '[WARN/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 2 );
          $prompt = 'Please reduce the amount of items in the order clause.';
          t3lib_div::devlog( '[HELP/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 1 );
        }
        break; // dwildt, 100915
      }
      // dwildt, 100915
      // Get SORT_DESC or SORT_ASC
      // 130502, dwildt, 1-
      //list($tableField, $order) = explode(' ', $strOrderByField);
      // 130502, dwildt, 1+
      list( $tableField ) = explode( " ", $strOrderByField );

      if ( !in_array( $tableField, $arr_usedTableFields ) )
      {
        // 140627, dwildt, 4+
        if ( empty( $tableField ) )
        {
          continue;
        }
        $tablefieldProperties[ $int_count ][ 'table.field' ] = $tableField;
        $tablefieldProperties[ $int_count ][ 'int_orderFlag' ] = $this->pObj->objSqlFun_3x->get_descOrAsc( $strOrderByField );

        list($table, $field) = explode( '.', $tableField );

        // dwildt, 100915
        $arr_sortTypeAndCase = $this->pObj->objSqlFun_3x->get_sortTypeAndCase( $table, $field );
        $tablefieldProperties[ $int_count ][ 'int_typeFlag' ] = $arr_sortTypeAndCase[ 'int_typeFlag' ];
        $tablefieldProperties[ $int_count ][ 'caseSensitive' ] = $arr_sortTypeAndCase[ 'bool_caseSensitive' ];
        // Get the typeFlag

        $int_count++;
      }
    }
    // Building arguments for array_multisort - Part I

    return $tablefieldProperties;
  }

  /**
   * multisortRows: multisort rows with upto 6 arrays
   *
   *
   *                                  : [rows]          ordered
   *
   * @param	array		$arguments  : array with elements (arrays) for multisort
   * @param	array		$rows           : Result of a database query
   * @return	array		$arr_return     : [arr_multisort] ordered
   * @since   3.4.3
   * @version 4.6.4
   */
  private function multisortRows( $arguments )
  {
    $rows = $this->rows;

    // RETURN : $arguments isn't an array
    if ( !is_array( $arguments ) )
    {
      return $rows;
    }

    // Process array_multisort
    if ( (count( $arguments ) - 1 ) == 0 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) == 1 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) == 2 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], ( array ) $arguments[ 2 ][ 'table.field' ], $arguments[ 2 ][ 'int_orderFlag' ], $arguments[ 2 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) == 3 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], ( array ) $arguments[ 2 ][ 'table.field' ], $arguments[ 2 ][ 'int_orderFlag' ], $arguments[ 2 ][ 'int_typeFlag' ], ( array ) $arguments[ 3 ][ 'table.field' ], $arguments[ 3 ][ 'int_orderFlag' ], $arguments[ 3 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) == 4 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], ( array ) $arguments[ 2 ][ 'table.field' ], $arguments[ 2 ][ 'int_orderFlag' ], $arguments[ 2 ][ 'int_typeFlag' ], ( array ) $arguments[ 3 ][ 'table.field' ], $arguments[ 3 ][ 'int_orderFlag' ], $arguments[ 3 ][ 'int_typeFlag' ], ( array ) $arguments[ 4 ][ 'table.field' ], $arguments[ 4 ][ 'int_orderFlag' ], $arguments[ 4 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) == 5 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], ( array ) $arguments[ 2 ][ 'table.field' ], $arguments[ 2 ][ 'int_orderFlag' ], $arguments[ 2 ][ 'int_typeFlag' ], ( array ) $arguments[ 3 ][ 'table.field' ], $arguments[ 3 ][ 'int_orderFlag' ], $arguments[ 3 ][ 'int_typeFlag' ], ( array ) $arguments[ 4 ][ 'table.field' ], $arguments[ 4 ][ 'int_orderFlag' ], $arguments[ 4 ][ 'int_typeFlag' ], ( array ) $arguments[ 5 ][ 'table.field' ], $arguments[ 5 ][ 'int_orderFlag' ], $arguments[ 5 ][ 'int_typeFlag' ], $rows
      );
    }
    if ( (count( $arguments ) - 1 ) > 5 )
    {
      array_multisort(
              // #00031, 130921, dwildt, ~
              ( array ) $arguments[ 0 ][ 'table.field' ], $arguments[ 0 ][ 'int_orderFlag' ], $arguments[ 0 ][ 'int_typeFlag' ], ( array ) $arguments[ 1 ][ 'table.field' ], $arguments[ 1 ][ 'int_orderFlag' ], $arguments[ 1 ][ 'int_typeFlag' ], ( array ) $arguments[ 2 ][ 'table.field' ], $arguments[ 2 ][ 'int_orderFlag' ], $arguments[ 2 ][ 'int_typeFlag' ], ( array ) $arguments[ 3 ][ 'table.field' ], $arguments[ 3 ][ 'int_orderFlag' ], $arguments[ 3 ][ 'int_typeFlag' ], ( array ) $arguments[ 4 ][ 'table.field' ], $arguments[ 4 ][ 'int_orderFlag' ], $arguments[ 4 ][ 'int_typeFlag' ], ( array ) $arguments[ 5 ][ 'table.field' ], $arguments[ 5 ][ 'int_orderFlag' ], $arguments[ 5 ][ 'int_typeFlag' ], ( array ) $arguments[ 6 ][ 'table.field' ], $arguments[ 6 ][ 'int_orderFlag' ], $arguments[ 6 ][ 'int_typeFlag' ], $rows
      );
    }
    // Process array_multisort

    return $rows;
  }

  /**
   * multisort_mm_children(): Order children elements
   *                          Result is one row with ordered children elements.
   *                          It will be handled the field sorting only to date.
   *
   * @param	array		$row  : Current row
   * @return	array		$row  : Row with ordered childrens
   * @since     3.6.3
   * @version   3.6.3
   */
  public function multisort_mm_children( $rows )
  {
    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;
    $view = $this->pObj->view;

    $viewWiDot = $view . '.';
    $conf_view = $conf[ 'views.' ][ $viewWiDot ][ $mode . '.' ];
    $conf_path = 'views.' . $viewWiDot . $mode . '.';
    $conf_orderChildren = $conf_view[ 'orderBy.' ];

    // RETURN orderBy hasn't any elements
    if ( empty( $conf_orderChildren ) )
    {
      if ( $this->pObj->b_drs_orderby || $this->pObj->b_drs_sql )
      {
        $prompt = $conf_path . 'orderBy hasn\'t any element. '
                . 'If you have children, they will be ordered randomly.';
        t3lib_div::devlog( '[INFO/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 0 );
        $prompt = 'See tutorial ... ';
        t3lib_div::devlog( '[HELP/ORDER BY+SQL] ' . $prompt, $this->pObj->extKey, 1 );
      }
      return $rows;
    }
    // RETURN orderBy hasn't any elements
    // Get the children devider
    $str_sqlDeviderDisplay = $this->pObj->objTyposcript->str_sqlDeviderDisplay;
    $str_sqlDeviderWorkflow = $this->pObj->objTyposcript->str_sqlDeviderWorkflow;
    $str_devider = $str_sqlDeviderDisplay . $str_sqlDeviderWorkflow;
    // Get the children devider
    /////////////////////////////////////////////////////////////////
    //
      // Loop: rows
    // 130502, dwildt, 1-
    //foreach( $rows as $key_rows => $row )
    // 130502, dwildt, 1+
    foreach ( ( array ) array_keys( $rows ) as $key_rows )
    {
      $str_localTableUid = $rows[ $key_rows ][ $this->pObj->arrLocalTable[ 'uid' ] ];
      // Loop: tsConf queries
      foreach ( $conf_orderChildren as $foreignTable => $foreignQuery )
      {
        $arr_queries[ $foreignTable ] = str_replace( '###UID_LOCAL###', $str_localTableUid, $foreignQuery );
      }
      // Loop: tsConf queries
      // Loop: queries
      foreach ( $arr_queries as $foreignTable => $query )
      {
        $res = $GLOBALS[ 'TYPO3_DB' ]->sql_query( $query );

        // EXIT: error!
        $error = $GLOBALS[ 'TYPO3_DB' ]->sql_error();
        if ( $error )
        {
          $this->pObj->objSqlFun_3x->query = $query;
          $this->pObj->objSqlFun_3x->error = $error;
          $arr_result = $this->pObj->objSqlFun_3x->prompt_error();
          $prompt = $arr_result[ 'error' ][ 'header' ] . $arr_result[ 'error' ][ 'prompt' ];
          $header = null;
          $text = null;
          $this->pObj->drs_die( $header, $text, $prompt );
        }
        // EXIT: error!
        // Loop: children
        $arr_ordered = null;
        while ( $row_foreignTable = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
        {
          foreach ( $row_foreignTable as $key => $value )
          {
            // Get the ordered values as a string
            $arr_ordered[ $foreignTable . '.' . $key ][] = $value;
          }
        }
        // Loop: children
        // Loop: ordered values
        foreach ( ( array ) $arr_ordered as $foreign_tableField => $arr_values )
        {
          if ( isset( $rows[ $key_rows ][ $foreign_tableField ] ) )
          {
            // Set the ordered values
            $rows[ $key_rows ][ $foreign_tableField ] = implode( $str_devider, $arr_values );
          }
        }
        // Loop: ordered values
      }
      // Loop: queries
    }
    // Loop: rows

    return $rows;
  }

//  /**
//   * multisort_rowsRemoveNonExistingKeys( ):
//   *
//   * @return	array       $arrOrderByWoAscDesc
//   * @version  5.0.0
//   * @since    3.4.4
//   */
//  private function multisort_rowsRemoveNonExistingKeys()
//  {
//    $csvOrderBy = $this->pObj->objSqlAut_3x->orderBy();
//    $csvOrderByWoAscDesc = $this->pObj->objSqlFun_3x->get_orderBy_tableFields( $csvOrderBy );
//    $arrOrderByWoAscDesc = $this->pObj->objZz->getCSVasArray( $csvOrderByWoAscDesc );
//
//    $rows = $this->rows;
//    reset( $rows );
//    $firstKey = key( $rows );
//
//    $arr_rmKeys = array_diff( ( array ) $arrOrderByWoAscDesc, ( array ) array_keys( $rows[ $firstKey ] ) );
//    $arrOrderByWoAscDesc = array_flip( ( array ) $arrOrderByWoAscDesc );
//    foreach ( ( array ) $arr_rmKeys as $key )
//    {
//      unset( $arrOrderByWoAscDesc[ $key ] );
//      if ( $this->pObj->b_drs_error )
//      {
//        t3lib_div::devlog( '[ERRORORDER BY+SQL] \'' . $key . '\' isn\'t any element in the current row!', $this->pObj->extKey, 3 );
//        t3lib_div::devlog( '[WARN/ORDER BY+SQL] Maybe the order of the rows won\'t be proper.', $this->pObj->extKey, 2 );
//        t3lib_div::devlog( '[HELP/ORDER BY+SQL] Please take care for a proper orderBy statement.', $this->pObj->extKey, 1 );
//      }
//    }
//    $arrOrderByWoAscDesc = array_flip( $arrOrderByWoAscDesc );
//
//    return $arrOrderByWoAscDesc;
//  }

  /*   * *********************************************
   *
   * Helper
   *
   * ******************************************** */

  /**
   * rowsInit( ): Inits the class var rows and resetGlobalRows
   *
   * @param array $rows :
   * @return	void
   * @version  5.0.0
   * @since    5.0.0
   */
  private function rowsInit( $rows )
  {
    if ( $rows === null )
    {
      $this->resetGlobalRows = true;
      $this->rows = $this->pObj->rows;
      return;
    }

    $this->resetGlobalRows = false;
    $this->rows = $rows;
    return;
  }

  /**
   * rowsReset( ): Reset the global var rows
   *
   * @return	void
   * @version  5.0.0
   * @since    5.0.0
   */
  private function rowsReset()
  {
    $this->pObj->rows->$this->rows;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_multisort.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_multisort.php' ]);
}
?>