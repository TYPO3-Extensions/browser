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
 * The class tx_browser_pi1_viewlist bundles methods for displaying the list view and the singe view for the extension browser
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 * @version 3.9.8
 * @since 1.0
 */

 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   64: class tx_browser_pi1_viewlist
 *  107:     function __construct( $parentObj )
 *
 *              SECTION: Building the views
 *  144:     function main( )
 *  242:     private function init( )
 *  293:     private function check_view( )
 *
 *              SECTION: Content / Template
 *  354:     private function content_setCSV( )
 *  387:     private function content_setDefault( )
 *  445:     private function content_dieIfEmpty( $marker, $method, $line )
 *
 *              SECTION: Subparts
 *  499:     private function subpart_setSearchbox( )
 *  516:     private function subpart_setSearchboxFilter( )
 *  560:     private function subpart_setIndexBrowser( )
 *  588:     private function subpart_setModeSelector( )
 *  632:     private function subpart_setPageBrowser( )
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_viewlist
{
    //////////////////////////////////////////////////////
    //
    // Variables set by the pObj (by class.tx_browser_pi1.php)

    // [Array] The current TypoScript configuration array
  var $conf       = false;
    // [Integer] The current mode (from modeselector)
  var $mode       = false;
    // [String] 'list' or 'single': The current view
  var $view       = false;
    // [Array] The TypoScript configuration array of the current view
  var $conf_view  = false;
    // [String] TypoScript path to the current view. I.e. views.single.1
  var $conf_path  = false;
    // Variables set by the pObj (by class.tx_browser_pi1.php)



    // Array with the fields of the SQL result
  var $arr_select;
    // Array with fields from orderBy from TS
  var $arr_orderBy;
    // Array with fields from functions.clean_up.csvTableFields from TS
  var $arr_rmFields;
    // [Array] result of the current SQL query
  var $res = null;
    // [Boolean] True: Query is a union, false: query isn't a union
  var $bool_union = null;

    // [String] Current content
  var $content = null;




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











  /***********************************************
   *
   * Building the views
   *
   **********************************************/










  /**
 * main( ): Display a search form, indexBrowser, pageBrowser and a list of records
 *
 * @return	string		$template : The processed HTML template
 * @version 3.9.8
 * @since 3.9.8
 */
  function main( )
  {
      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // RETURN there isn't any list configured
    $prompt = $this->check_view( );
    if( $prompt )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      return $prompt;
    }
      // RETURN there isn't any list configured

      // Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
    $this->init( );

      // Get HTML content
    $this->content = $this->pObj->str_template_raw;

      // Set SQL query parts in general and statements for rows
    $arr_return = $this->pObj->objSqlInit->init( );
    if( $arr_return['error']['status'] )
    {
        // Prompt the expired time to devlog
      $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $content;
    }
      // Set SQL query parts in general and statements for rows




      //////////////////////////////////////////////////////////////////////
      //
      // csv export versus list view

      // #29370, 110831, dwildt+
      // Get template for csv
    switch( $this->pObj->objExport->str_typeNum )
    {
      case( 'csv' ) :
          // CASE csv
          // Take the CSV template
        $this->content_setCSV( );
        break;
          // CASE csv
      default:
          // CASE no csv
          // Take the default template (the list view) and replace some subparts
        $arr_return = $this->content_setDefault( );
        if( $arr_return['error']['status'] )
        {
            // Prompt the expired time to devlog
          $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
          $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
          return $content;
        }
        break;
          // CASE no csv
    }
      // Get template for csv
      // csv export versus list view
      // #29370, 110831, dwildt+


var_dump( __METHOD__, __LINE__, $this->pObj->objSqlInit->statements );
var_dump( __METHOD__, __LINE__, $this->pObj->objSqlAut->arr_relations_mm_simple );

      // Get rows
    $this->rows_listViewSqlRes( );
      // Set rows



      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );

      // RETURN content
    return $this->content;
  }











  /**
 * init( ): Overwrite general_stdWrap, set globals $lDisplayList and $lDisplay
 *
 * @return	void
 * @version 3.9.8
 * @since 1.0.0
 */
  private function init( )
  {
      // Overwrite global general_stdWrap
      // #12471, 110123, dwildt+
    if( is_array( $this->conf_view['general_stdWrap.'] ) )
    {
      $this->pObj->conf['general_stdWrap.'] = $this->conf_view['general_stdWrap.'];
      $this->conf['general_stdWrap.']       = $this->pObj->conf['general_stdWrap.'];
    }
      // Overwrite global general_stdWrap

      // Get the local or global displayList
    if( is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $this->conf_view['displayList.'];
    }
    if( ! is_array( $this->conf_view['displayList.'] ) )
    {
      $this->pObj->lDisplayList = $this->conf['displayList.'];
    }
      // Get the local or global displayList



      // Get the local or global displayList.display
    if( is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $this->conf_view['displayList.']['display.'];
    }
    if( ! is_array( $this->conf_view['displayList.']['display.'] ) )
    {
      $this->pObj->lDisplay = $this->conf['displayList.']['display.'];
    }
      // Get the local or global displayList.display
  }









  /**
 * check_view( ):
 *
 * @return	string		Error prompt in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function check_view( )
  {
    $mode = $this->mode;

      //////////////////////////////////////////////////////////////////
      //
      // RETURN there isn't any list configured

    $bool_noView = false;
    switch( true )
    {
      case( empty( $mode ) ):
        $bool_noView = true;
        break;
      case( ! is_array( $this->conf_view ) ):
        $bool_noView = true;
        break;
    }
    if( $bool_noView )
    {
      if ( $this->pObj->b_drs_error )
      {
        t3lib_div::devlog( '[ERROR/DRS] views.list.' . $mode . ' hasn\'t any item.', $this->pObj->extKey, 3 );
        $prompt = 'Did you included the static template from this extensions?';
        t3lib_div::devLog( '[HELP/DRS] ' . $prompt, $this->pObj->extKey, 1 );
        $tsArray = 'plugin.' . $this->pObj->prefixId . '.views.list.' . $mode;
        t3lib_div::devLog( '[HELP/DRS] Did you configure ' . $tsArray . '?', $this->pObj->extKey, 1 );
      }
      $str_header = '<h1 style="color:red">' . $this->pObj->pi_getLL( 'error_typoscript_h1' ) . '</h1>';
      $str_prompt = '<p style="border: 2px dotted red; font-weight:bold;text-align:center; padding:1em;">' .
                        $this->pObj->pi_getLL( 'error_typoscript_no_listview' ) . '</p>';
      return $str_header . $str_prompt;
    }
      // RETURN there isn't any list configured

    return false;
  }









 /***********************************************
  *
  * Content / Template
  *
  **********************************************/



/**
 * content_setCSV( ): Sets content to CSV template
 *
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.9
 */
  private function content_setCSV( )
  {
      // DRS
    if ( $this->pObj->b_drs_templating || $this->pObj->b_drs_export )
    {
      t3lib_div::devlog('[INFO/TEMPLATING+EXPORT] ###TEMPLATE_CSV### is used as template marker.',  $this->pObj->extKey, 0);
    }
      // DRS

      // Get the label of the subpart marker for the csv content
    $str_marker     = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['marker'];
      // Get the csv content file
    $template_path  = $this->conf['flexform.']['viewList.']['csvexport.']['template.']['file'];
      // Set the csv content
    $this->content  = $this->pObj->cObj->fileResource( $template_path );

      // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker, __METHOD__, __LINE__ );
  }



/**
 * content_setDefault( ): Sets content to list view template.
 *                        Takes care of the subparts
 *                        * searchbox
 *                        * indexBrowser
 *                        *
 *
 * @return	array		$arr_return: Contains an error message in case of an error
 * @version 3.9.12
 * @since   3.9.9
 */
  private function content_setDefault( )
  {
      // HTML template subpart for the list view
    $str_marker     = $this->pObj->lDisplayList['templateMarker'];
      // Set the list view content
    $this->content  = $this->pObj->cObj->getSubpart( $this->content, $str_marker );

      // Die, if content is empty
    $this->content_dieIfEmpty( $str_marker, __METHOD__, __LINE__ );

      // Set search box and filter
    $arr_return = $this->subpart_setSearchbox( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set search box and filter

      // Set index browser
    $arr_return = $this->subpart_setIndexBrowser( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set index browser

      // Set page browser
    $arr_return = $this->subpart_setPageBrowser( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set page browser

      // Set mode selector
    $arr_return = $this->subpart_setModeSelector( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Set mode selector

    return;
  }



/**
 * content_dieIfEmpty( ): If content is empty, the methods will die the workflow
 *                      with a qualified prompt.
 *
 * @param	[type]		$$marker: ...
 * @param	[type]		$method: ...
 * @param	[type]		$line: ...
 * @return	[type]		...
 * @version 3.9.12
 * @since   3.9.12
 */
  private function content_dieIfEmpty( $marker, $method, $line )
  {
    if( empty( $this->content ) )
    {
      $prompt = '<div style="border:2em solid red;color:red;padding:2em;text-align:center;">
          <h1>
            TYPO3 Browser Error
          </h1>
          <h2>
            EN: Subpart is missing
          </h2>
          <p>
            English: Current HTML template doesn\'t contain the subpart \'' . $marker . '\' .<br />
            Please take care of a proper template.<br />
          </p>
          <h2>
            DE: Subpart fehlt
          </h2>
          <p>
            Deutsch: Dem aktuellen HTML-Template fehlt der Subpart \'' . $marker . '\'.<br />
            Bitte k&uuml;mmere Dich um ein korrektes Template.<br />
          </p>
          <p>
            ' . $method . ' (' . $line . ')
          </p>
        </div>';
      die( $prompt );
    }
  }









  /***********************************************
  *
  * SQL
  *
  **********************************************/



  /**
   * rows_listViewSqlRes( ): Building the SQL query
   *
   * @return	array
   * @version 3.9.8
   * @since 1.0.0
   */
  private function rows_listViewSqlRes( )
  {
    $conf_view = $this->conf_view;

      // SQL query array
    $select   = $this->pObj->objSqlInit->statements['listView']['select'];
    $from     = $this->pObj->objSqlInit->statements['listView']['from'];
    $where    = $this->pObj->objSqlInit->statements['listView']['where'];
    $groupBy  = null;
    $orderBy = false;
    $limit    = $this->pObj->objSqlInit->statements['listView']['limit'];
      // SQL query array

      // #9917: Selecting a random sample from a set of rows
    if( $conf_view['random'] == 1 )
    {
      $orderBy = 'rand( )';
    }
      // Set ORDER BY to false - we like to order by PHP
    
      // DRS
    if( $this->pObj->b_drs_devTodo )
    {
      $prompt = 'UNION isn\'t supported any longer! Refer it to the release notes.';
      t3lib_div::devlog('[ERROR/TODO] ' . $prompt, $this->pObj->extKey, 3);
    }
      // DRS

      // SQL query
    $query = $GLOBALS['TYPO3_DB']->SELECTquery
                                    (
                                      $select,
                                      $from,
                                      $where,
                                      $groupBy,
                                      $orderBy,
                                      $limit,
                                      $uidIndexField=""
                                    );
      // SQL query

      // Prompt the expired time to devlog
    $b_drs_performBak = $this->b_drs_perform;
    $this->b_drs_perform = true;
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'SQL query list view - START' );
    $tt_start = $this->pObj->tt_prevEndTime;
      // Prompt the expired time to devlog

      // Execute
    $res   = $GLOBALS['TYPO3_DB']->sql_query( $query );
    $error = $GLOBALS['TYPO3_DB']->sql_error( );
      // Execute

      // Prompt the expired time to devlog
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'SQL query list view - STOP' );
    $this->pObj->timeTracking_prompt( $query );
    $this->b_drs_perform = $b_drs_performBak;
    $tt_end = $this->pObj->tt_prevEndTime;
      // Prompt the expired time to devlog

      // DRS - Performance
    if( $this->pObj->b_drs_warn )
    {
      $iMilliseconds = $tt_end - $tt_start;
      $sMilliseconds = '(' . $iMilliseconds . ' ms)';
      $promptHelp = 'Maintain the performance? Reduce the relations: reduce the filter. ' .
                    'Don\'t use the query in a localised context.';
      switch( true )
      {
        case( $iMilliseconds < 500 ):
          $prompt = 'Query for the list view needs less than a half second ' . $sMilliseconds . '.';
          t3lib_div::devlog( '[OK/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, -1 );
          break;
        case( $iMilliseconds >= 500 && $iMilliseconds < 5000 ):
          $prompt = 'Query for the list view needs more than a half second ' . $sMilliseconds . '.';
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
          break;
        case( $iMilliseconds >= 5000 && $iMilliseconds < 10000 ):
          $prompt = 'Query needs more than 5 seconds ' . $sMilliseconds . '.';
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 2 );
          t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
          break;
        case( $iMilliseconds >= 10000 ):
          $prompt = 'Query for the list view needs more than 10 seconds ' . $sMilliseconds . '.';
          t3lib_div::devlog( '[WARN/PERFROMANCE] ' . $prompt ,  $this->pObj->extKey, 3 );
          t3lib_div::devlog( '[HELP/PERFROMANCE] ' . $promptHelp ,  $this->pObj->extKey, 1 );
          break;
      }
    }
      // DRS - Performance

var_dump( __METHOD__, __LINE__, $query, $sMilliseconds, $tt_end, $tt_start );

      // Error management
    if( $error )
    {
      $this->pObj->objSqlFun_3x->query = $query;
      $this->pObj->objSqlFun_3x->error = $error;
      return $this->pObj->objSqlFun_3x->prompt_error( );
    }
      // Error management

      // DRS - Development Reporting System
    if( $this->pObj->b_drs_sql )
    {
      t3lib_div::devlog( '[OK/SQL] ' . $query,  $this->pObj->extKey, -1 );
      t3lib_div::devlog( '[HELP/SQL] Be aware of the multi-byte notation, if you want to use the query ' .
                          'in your SQL shell or in phpMyAdmin.', $this->pObj->extKey, 1 );
    }
      // DRS - Development Reporting System
      // Execute the SQL query


    $this->res = $res;
    return false;

  }









 /***********************************************
  *
  * Subparts
  *
  **********************************************/



/**
 * subpart_setSearchbox( ): Get the searchform. Part of content is generated
 *                          by the template class. Replace filter marker.
 *
 * @return	array		$arr_return : Error message in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function subpart_setSearchbox( )
  {
    $this->content  = $this->pObj->objTemplate->tmplSearchBox( $this->content );
    $arr_return     = $this->subpart_setSearchboxFilter( $filter );

    return $arr_return;
  }



/**
 * subpart_setSearchboxFilter( ): Get filter values and then replace filter marker with filter content.
 *
 * @return	array		$arr_return: Error message in case of an error
 * @version 3.9.8
 * @since 1.0.0
 */
  private function subpart_setSearchboxFilter( )
  {
      // Default return value
    $arr_return = array( );

      // Get filter
    $arr_return = $this->pObj->objFltr4x->get( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
    $filter = $arr_return['data']['filter'];
      // Get filter

      // RETURN : there isn't any filter
    if( empty ( $filter ) )
    {
      return $arr_return;
    }
      // RETURN : there isn't any filter

      // Get the searchform content
    $searchform     = $this->pObj->cObj->getSubpart( $this->content, '###SEARCHFORM###' );
      // Replace filter marker with filter content
    $searchform     = $this->pObj->cObj->substituteMarkerArray( $searchform, $filter );
      // Add the subparts marker, because another method ( the search template ) need this subpart marker
    $searchform     = '<!-- ###SEARCHFORM### begin -->' . PHP_EOL .
                  $searchform . '<!-- ###SEARCHFORM### end -->' . PHP_EOL;
      // Update the searchform in the whole content
    $this->content  = $this->pObj->cObj->substituteSubpart( $this->content, '###SEARCHFORM###', $searchform, true );

    return $arr_return;
  }



/**
 * subpart_setIndexBrowser( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setIndexBrowser( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setIndexBrowser begin' );

    $arr_return = $this->pObj->objNaviIndexBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }

    $content        = $arr_return['data']['content'];
    $marker         = $this->pObj->objNaviIndexBrowser->getMarkerIndexBrowser( );
    $this->content  = $this->pObj->cObj->substituteSubpart( $this->content, $marker, $content, true);

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'subpart_setIndexBrowser end' );
    return;
  }



/**
 * subpart_setModeSelector( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setModeSelector( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Get the mode selector content
    $arr_return = $this->pObj->objNaviModeSelector->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the mode selector content
    $content = $arr_return['data']['content'];

      // Set the marker array
    $markerArray                = $this->pObj->objWrapper->constant_markers( );
    $markerArray['###MODE###']  = $this->mode;
    $markerArray['###VIEW###']  = $this->view;
      // Set the marker array

    $modeSelector   = $this->pObj->cObj->getSubpart( $this->content, '###MODESELECTOR###' );
    $modeSelector   = $this->pObj->cObj->substituteMarkerArray( $modeSelector, $markerArray );
    $modeSelector   = $this->pObj->cObj->substituteSubpart
                      (
                        $modeSelector, '###MODESELECTORTABS###', $content, true
                      );
    $this->content  = $this->pObj->cObj->substituteSubpart
                      (
                        $this->content, '###MODESELECTOR###', $modeSelector, true
                      );

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return;
  }



/**
 * subpart_setPageBrowser( ):  Replaces the indexbrowser subpart in the current content
 *                              with the content from ->get_indexBrowser( )
 *
 * @return	array		$arr_return : Contains an error message in case of an error
 * @version 3.9.12
 * @since 1.0.0
 */
  private function subpart_setPageBrowser( )
  {
    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'begin' );

      // Get the page browser content
    $arr_return = $this->pObj->objNaviPageBrowser->get( $this->content );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // Get the page browser content

      // Set marker the array
    $markerArray                            = $this->pObj->objWrapper->constant_markers( );
    $markerArray['###RESULT_AND_ITEMS###']  = $arr_return['data']['content'];
    $markerArray['###MODE###']              = $this->mode;
    $markerArray['###VIEW###']              = $this->view;
      // Set marker the array

      // Replace markers in the current content
    $subpart        = $this->pObj->cObj->getSubpart(  $this->content, '###PAGEBROWSER###' );
    $pageBrowser    = $this->pObj->cObj->substituteMarkerArray( $subpart, $markerArray );
    $this->content  = $this->pObj->cObj->substituteSubpart
                      (
                        $this->content, '###PAGEBROWSER###', $pageBrowser, true
                      );
      // Replace markers in the current content

    $this->pObj->timeTracking_log( __METHOD__, __LINE__,  'end' );
    return;
  }









}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_viewlist.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_viewlist.php']);
}

?>
