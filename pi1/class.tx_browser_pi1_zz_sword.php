<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * The class tx_browser_pi1_zz_sword bundles zz methods for the extension browser
 *
 * @author      Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package     TYPO3
 * @subpackage  browser
 * @version     5.0.0
 * @since       5.0.0
 * @internal    #60107
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  101: class tx_browser_pi1_zz_sword
 *
 * TOTAL FUNCTIONS: 29
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_zz_sword
{

  private $conf = null;
  // [Array] The current TypoScript configuration array
  private $mode = null;
  // [Integer] The current mode (from modeselector)
  private $view = null;
  // [String] 'list' or 'single': The current view
  private $conf_view = null;
  // [Array] The TypoScript configuration array of the current view
  private $conf_path = null;

  // [String] TypoScript path to the current view. I.e. views.single.1

  function __construct( $parentObj )
  {
    // Set the Parent Object
    $this->pObj = $parentObj;

    $this->conf = $this->pObj->conf;
    $this->mode = $this->pObj->mode;
    $this->view = $this->pObj->view;
    $this->conf_view = $this->pObj->conf_view;
    $this->conf_path = $this->pObj->conf_path;
    $this->template = $this->pObj->template;
  }

  /**
   * getSqlOperators()  :
   *
   * @return	array
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSqlOperators()
  {
    $operators = array(
      'and' => $this->getSqlOperatorsAnd(),
      'or' => $this->getSqlOperatorsOr(),
      'not' => $this->getSqlOperatorsNot()
    );

    return $operators;
  }

  /**
   * getSqlOperatorsAnd()  :
   *
   * @return	string
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSqlOperatorsAnd()
  {
    $lSearchform = $this->conf[ 'displayList.' ][ 'display.' ][ 'searchform.' ];

    $name = $lSearchform[ 'and' ];
    $conf = $lSearchform[ 'and.' ];
    $value = $this->pObj->pObj->cObj->cObjGetSingle( $name, $conf );
    if ( empty( $value ) )
    {
      $value = 'and';
    }
    $value = ' ' . $value . ' ';
    return $value;
  }

  /**
   * getSqlOperatorsNot()  :
   *
   * @return	string
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSqlOperatorsNot()
  {
    $lSearchform = $this->conf[ 'displayList.' ][ 'display.' ][ 'searchform.' ];

    $name = $lSearchform[ 'not' ];
    $conf = $lSearchform[ 'not.' ];
    $value = $this->pObj->pObj->cObj->cObjGetSingle( $name, $conf );
    if ( empty( $value ) )
    {
      $value = 'not';
    }
    $value = ' ' . $value . ' ';
    return $value;
  }

  /**
   * getSqlOperatorsOr()  :
   *
   * @return	string
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSqlOperatorsOr()
  {
    $lSearchform = $this->conf[ 'displayList.' ][ 'display.' ][ 'searchform.' ];

    $name = $lSearchform[ 'or' ];
    $conf = $lSearchform[ 'or.' ];
    $value = $this->pObj->pObj->cObj->cObjGetSingle( $name, $conf );
    if ( empty( $value ) )
    {
      $value = 'or';
    }
    $value = ' ' . $value . ' ';
    return $value;
  }

  /**
   * getSwords()
   *
   * @param	string
   * @param	array
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSwords()
  {
    // 140705, dwildt, 1-: with mysql_real_escape_string
    //$arr_swords_quoted = explode( '\\"', $this->pObj->pObj->piVar_sword );
    // 140705, dwildt, 1+: without mysql_real_escape_string
    $arr_swords_quoted = explode( '"', $this->pObj->pObj->piVar_sword );
var_dump( __METHOD__, __LINE__, $this->pObj->pObj->piVar_sword, $arr_swords_quoted );
    // Preparation for investigating quotes

    switch ( true )
    {
      case(count( $arr_swords_quoted ) == 1):
        $arr_swords_exploded = $this->getSwordsWoQuotes();
        break;
      case(count( $arr_swords_quoted ) > 1):
      default:
        $arr_swords_exploded = $this->getSwordsWiQuotes();
        break;
    }
    return $arr_swords_exploded;
  }

  /**
   * getSwordsExploded()
   *
   * @param	string    $sword  : current sword. Can be one word or one phrase
   *                            I.e
   *                            * word
   *                            * one phrase
   *                            but not
   *                            * word "one phrase"
   * @param	array
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSwordsExploded( $sword )
  {
    $arrSwords = explode( '"', $sword );
    $operators = $this->getSqlOperators();

    // Remove all SQL operators
    $sword = str_replace( $operators, ' ', $sword );
    // Remove unnecessary spaces
    $sword = preg_replace( '/\s\s+/', ' ', $sword );
    $arrSwords = explode( ' ', $sword );

    return $arrSwords;
  }

  /**
   * getSwordsWiQuotes()
   *
   * @param	string
   * @param	array
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSwordsWiQuotes()
  {
var_dump(__METHOD__, __LINE__ );
    $arr_swords_marker = array(); // Array with every search word or search phrase and its marker like $0, $1

    $arr_swords_quoted = explode( '"', $this->pObj->pObj->piVar_sword );
    var_dump( __METHOD__, __LINE__, $this->pObj->pObj->piVar_sword, $arr_swords_quoted );
    // Preparation for investigating quotes
    // Workflow for search words without any quotes

    $key = 0;
    $int_counter = 0;
    // Quoted phrases are stored in even elements
    $bool_odd = false;
    if ( substr( $this->pObj->pObj->piVar_sword, 0 ) == '"' )
    {
      // Quoted phrases are stored in odd elements
      $bool_odd = true;
    }

    // Loop through the aray with the quoted and non quoted swords
    $int_counter = 0;
    foreach ( ( array ) $arr_swords_quoted as $key => $value )
    {
      switch ( true )
      {
        case( $value === null ):
        case( $value == '' ):
          continue;
        default:
          // Foolow the workflow
          break;
      }
      // Switch between even und odd elements
      switch ( $key % 2 )
      {
        case(false):
          // We have an odd array like [0], [2], [4]
          // We have a quoted sword in every odd element - don't explode it.
          if ( $bool_odd )
          {
            $arr_swords_marker[ '$' . $int_counter++ ] = $value;
            continue;
          }
          $arr_exploded = $this->getSwordsExploded( $value );
          $arr_exploded = $this->removeShortWords( $arr_exploded );
          break;
        case(true):
          // We have an even array like [1], [3], [5]
          // We have a quoted sword in every even element - don't explode it.
          if ( !$bool_odd )
          {
            $arr_swords_marker[ '$' . $int_counter++ ] = $value;
            continue;
          }
          $arr_exploded = $this->getSwordsExploded( $value );
          $arr_exploded = $this->removeShortWords( $arr_exploded );
          break;
      } // Switch between even und odd elements
    } // Loop through the aray with the quoted and non quoted swords

    $arrReturn = array(
      'marker' => $arr_swords_marker,
      'exploded' => $arr_exploded
    );
var_dump(__METHOD__, __LINE__, $arrReturn);
    return $arrReturn;
  }

  /**
   * getSwordsWoQuotes()
   *
   * @param	string
   * @param	array
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  private function getSwordsWoQuotes()
  {
var_dump(__METHOD__, __LINE__ );

    // Get current search words or phrase
    $sword = $this->pObj->pObj->piVar_sword;
    $arrSwords = $this->getSwordsExploded( $sword );
    $arrSwords = $this->removeShortWords( $arrSwords );

    var_dump( __METHOD__, __LINE__, $this->pObj->pObj->piVar_sword, $arrSwords );
    return $arrSwords;
  }

  /*   * *********************************************
   *
   * Sword and Search respectively
   *
   * ******************************************** */

  /**
   * Returns an array with search values out of the given search phrase.
   * Example for a phrase: "Dirk Wildt" Pressesprecher Berlin
   * This will return the elements: Dirk Wildt, Pressesprecher, Berlin
   *
   * @param	string		$str_search_phrase: piVar value
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  public function main()
  {
    $arr_return = array();
    // Example phrase: Helmut und Schmidt und Bundeskanzler nicht Entertainer "Helmut Kohl"
    // RETURN, if there isn't any sword
    if ( !$this->pObj->pObj->piVar_sword )
    {
      // DRS - Development Reporting System
      if ( $this->pObj->pObj->b_drs_search )
      {
        t3lib_div::devlog( '[INFO/SEARCH] There is no search phrase.', $this->pObj->extKey, 0 );
      }
      return false;
    }

    // DRS - Development Reporting System
    if ( $this->pObj->pObj->b_drs_search )
    {
      t3lib_div::devlog( '[INFO/SEARCH] Searchphrase is \'' . $this->pObj->pObj->piVar_sword . '\'', $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/SEARCH] Searchphrase is \'' . rawurldecode( $this->pObj->pObj->piVar_sword ) . '\'', $this->pObj->extKey, 0 );
    }

    // Get words for the SQL operators AND, OR and NOT
//var_dump( __METHOD__, __LINE__, $arr_sql_operator );

    $arr_swords_exploded = $this->getSwords();


    // Extend marker array with search words from the array exploded
    foreach ( ( array ) $arr_swords_exploded as $arr_swords_exploded_swords )
    {
      foreach ( ( array ) $arr_swords_exploded_swords as $str_exploded )
      {
        switch ( strtolower( $str_exploded ) )
        {
          case(false):
          case(''):
          case(trim( $arr_sql_operator[ 'and' ] )):
          case(trim( $arr_sql_operator[ 'or' ] )):
          case(trim( $arr_sql_operator[ 'not' ] )):
            // do nothing;
            break;
          default:
            $arr_swords_marker[ '$' . $int_counter++ ] = $str_exploded;
        }
      }
    }

    // Remove non unique search words
    if ( is_array( $arr_swords_marker ) )
    {
      $arr_swords_marker = array_unique( $arr_swords_marker );
    }
var_dump(__METHOD__, __LINE__, $arr_swords_exploded, $arr_swords_marker);
    // Extend marker array with search words from the array exploded
    //////////////////////////////////////////////////
    //
      // Mask the search phrase

    $str_sword_mask = $this->pObj->pObj->piVar_sword;
    // var_dump('zz 1769', $this->pObj->pObj->piVar_sword );
    // Helmut und Schmidt und Bundeskanzler nicht Entertainer "Helmut Kohl"
    // Remove all quotes
    $str_sword_mask = str_replace( '\\"', false, $str_sword_mask );

    // Replace all search words with a mask
    foreach ( ( array ) $arr_swords_marker as $str_mask => $str_sword )
    {
      $str_sword_mask = str_replace( $str_sword, $str_mask, $str_sword_mask );
    }
    // $1 und $2 und $3 nicht $4 $0
    // Replace all search words with a mask
    // Remove unnecessary spaces
    $str_sword_mask = preg_replace( '/\s\s+/', ' ', $str_sword_mask );
    // $1 und $2 und $3 nicht $4 $0
    // 1. Mask AND and NOT
    $arr_search = array( $arr_sql_operator[ 'and' ], $arr_sql_operator[ 'not' ] );
    $arr_replace = array( 'and', 'not' );
    // var_dump('zz 1842', $str_sword_mask);
    // $1 und $2 und $3 nicht $4 $0
    // DRS - Development Reporting System
    if ( $this->pObj->pObj->b_drs_search )
    {
      t3lib_div::devlog( '[INFO/SEARCH] Step 1/2: Mask is \'' . $str_sword_mask . '\'', $this->pObj->extKey, 0 );
    }
    $str_sword_mask = str_replace( $arr_search, $arr_replace, $str_sword_mask );
    // Helmut und Schmidt und       Bundeskanzler nicht Entertainer "Helmut Kohl"
    // $1und$2und$3nicht$4 $0
    // 1. Mask AND and NOT
    // 2. Mask spaces and OR
    $arr_search = array( ' ', $arr_sql_operator[ 'or' ] );
    $arr_replace = array( 'or', 'or' );
    $str_sword_mask = str_replace( $arr_search, $arr_replace, $str_sword_mask );
    // Helmut und Schmidt und       Bundeskanzler nicht Entertainer "Helmut Kohl" nicht "Harald Schmidt"
    // $1und$2und$3nicht$4oder$0
    // 2. Mask spaces and OR
    // var_dump('zz 1855', $str_sword_mask);
    // $1and$2and$3not$4or$0
    // DRS - Development Reporting System
    if ( $this->pObj->pObj->b_drs_search )
    {
      t3lib_div::devlog( '[INFO/SEARCH] Step 2/2: Mask is \'' . $str_sword_mask . '\'', $this->pObj->extKey, 0 );
    }
    // For resultphrase
    $arr_search = array( 'and', 'or', 'not' );
    $arr_replace = array( $arr_sql_operator[ 'and' ], $arr_sql_operator[ 'or' ], $arr_sql_operator[ 'not' ] );
    $str_resultphrase_mask = str_replace( $arr_search, $arr_replace, $str_sword_mask );



    $arr_swords = array();

    // Get all NOT
    $str_sword_mask_wo_not = $str_sword_mask;
    foreach ( ( array ) $arr_swords_marker as $key => $str_sword )
    {
      $str_search = 'not' . $key;
      $bool_found = strpos( $str_sword_mask, $str_search );
      if ( !($bool_found === false) )
      {
        if ( $str_sword != '' )
        {
          $arr_swords[ 'not' ][] = $str_sword;
          $str_sword_mask_wo_not = str_replace( $str_search, '', $str_sword_mask_wo_not );
        }
      }
    }
    // Get all NOT
    // Get all OR
    $int_counter = 0;
    $arr_or = explode( 'or', $str_sword_mask_wo_not );
    foreach ( ( array ) $arr_or as $key_search_or => $str_search_or )
    {
      if ( $str_search_or )
      {
        // Get all AND
        $arr_and = explode( 'and', $str_search_or );
        foreach ( ( array ) $arr_and as $str_search_and )
        {
          if ( $arr_swords_marker[ $str_search_and ] != '' )
          {
            //var_dump('$arr_swords4['.$int_counter.'][] = $arr_swords_marker['.$key.']');
            $arr_swords[ 'or' ][ $int_counter ][] = $arr_swords_marker[ $str_search_and ];
          }
        }
        $int_counter++;
      }
      // Get all AND
      if ( !$str_search_or )
      {
        unset( $arr_or[ $key_search_or ] );
      }
    }
    // Get all OR
    // Get a proper search word phrase
    $arr_search_or = array();
    foreach ( ( array ) $arr_swords[ 'or' ] as $arr_search_and )
    {
      $str_search_and = implode( '"' . $arr_sql_operator[ 'and' ] . '"', $arr_search_and );
      $str_search_and = '"' . $str_search_and . '"';
      $arr_search_or[] = $str_search_and;
    }
    $str_proper_search = implode( $arr_sql_operator[ 'or' ], $arr_search_or );
    if ( is_array( $arr_swords[ 'not' ] ) )
    {
      $str_search_not = implode( '"' . $arr_sql_operator[ 'not' ] . '"', $arr_swords[ 'not' ] );
      $str_search_not = $arr_sql_operator[ 'not' ] . '"' . $str_search_not . '"';
      $str_proper_search = $str_proper_search . $str_search_not;
    }
    // var_dump('zz 1886', $str_proper_search);
    // "Helmut" und "Schmidt" und "Bundeskanzler" oder "Helmut Kohl" nicht "Entertainer"
    // Get a proper search word phrase
    //var_dump('zz 1890', $this->pObj->pObj->piVar_sword , $str_sword_mask, $arr_swords);
    //array["not"][0] => "Entertainer"
    //     ["or"] [0][0] => "Helmut"
    //               [1] => "Schmidt"
    //               [2] => "Bundeskanzler"
    //            [1][0] => "Helmut Kohl"
    //////////////////////////////////////////////////
    //
      // Consolidate wildcards in markers
    // Char for Wildcard
    $chr_wildcard = $this->pObj->str_searchWildcardCharManual;

    // The user has to add a wildcard
    if ( $this->pObj->pObj->bool_searchWildcardsManual )
    {
      foreach ( ( array ) $arr_swords_marker as $key => $value )
      {
        // First char of search word is a wildcard
        if ( $value[ 0 ] == $chr_wildcard )
        {
          $value = substr( $value, 1, strlen( $value ) - 1 );
          $arr_swords_marker[ $key ] = $value;
        }
        // First char of search word is a wildcard
        // Last char of search word is a wildcard
        if ( $value[ strlen( $value ) - 1 ] == $chr_wildcard )
        {
          $value = substr( $value, 0, -1 );
          $arr_swords_marker[ $key ] = $value;
        }
      }
    }
    // The user has to add a wildcard
    // Consolidate wildcards in markers
//var_dump('zz 2013', $arr_swords_marker);
    //////////////////////////////////////////////////
    //
      // RETURN result

    $arr_return[ 'data' ][ 'arr_sword' ] = $arr_swords;
    $arr_return[ 'data' ][ 'str_sword' ] = $str_proper_search;
    $arr_return[ 'data' ][ 'arr_resultphrase' ][ 'arr_marker' ] = $arr_swords_marker;
    $arr_return[ 'data' ][ 'arr_resultphrase' ][ 'str_mask' ] = $str_resultphrase_mask;
    return $arr_return;
    // RETURN result
  }

  /**
   * removeShortWords()
   *
   * @param	array
   * @return	array		search values
   * @version     5.0.0
   * @since       5.0.0
   */
  private function removeShortWords( $arrSwords )
  {
    $int_minLen = $this->pObj->arr_advanced_securitySword;

    // Remove words which are to short
    foreach ( ( array ) $arrSwords as $key => $value )
    {
      if ( strlen( $value ) >= $int_minLen )
      {
        continue;
      }
      unset( $arrSwords[ $key ] );
    } // Remove words which are to short

    return $arrSwords;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_zz_sword.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_zz_sword.php' ]);
}
?>
