<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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

//////////////////////////////////////////////////////////////////////
//
// TYPO3 Downwards Compatibility

if (!defined('PATH_typo3'))
{
  //var_dump(get_defined_constants());
  //echo 'Not defined: PATH_typo3.<br />tx_browser_pi1 defines it now.<br />';
  if (!defined('PATH_site'))
  {
    echo '<div style="border:1em solid red;padding:1em;color:red;font-weight:bold;font-size:2em;background:white;line-height:2.4em;text-align:center;">Error<br />
      The constant PATH_typo3 isn\'t defined.<br />
      tx_browser_pi1 tries to get now PATH_site, but it isn\'t defined neither!<br />
      <br />
      Please check your TYPO3 installation.</div>';
  }
  if (!defined('TYPO3_mainDir'))
  {
    echo '<div style="border:1em solid red;padding:1em;color:red;font-weight:bold;font-size:2em;background:white;line-height:2.4em;text-align:center;">Error<br />
      The constant PATH_typo3 isn\'t defined.<br />
      tx_browser_pi1 tries to get now TYPO3_mainDir, but it isn\'t defined neither!<br />
      <br />
      Please check your TYPO3 installation.</div>';
  }
  define('Path_typo3', PATH_site.TYPO3_mainDir);
}
// TYPO3 Downwards Compatibility


require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Plugin 'Browser' for the 'browser' extension - the fastest way for your data into the TYPO3 frontend.
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_browser
 *
 * @version 3.9.8
 * @since 0.0.1
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   91: class tx_browser_pi1 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  331:     function main($content, $conf)
 *
 *              SECTION: DRS - Development Reporting System
 * 1087:     function init_drs()
 *
 *              SECTION: Classes
 * 1405:     function require_classes()
 * 1531:     function init_classVars()
 *
 *              SECTION: Template
 * 1683:     function getTemplate($cObj, $conf, $arr_data)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1 extends tslib_pibase {

    ////////////////////////////////////////////////////////////////////
    //
    // TYPO3 extension

    // Same as class name
  var $prefixId       = 'tx_browser_pi1';
    // Path to this script relative to the extension dir.
  var $scriptRelPath  = 'pi1/class.tx_browser_pi1.php';
    // The extension key.
  var $extKey         = 'browser';
  var $pi_checkCHash  = true;
    // [Array] values out of the extConf file
  var $arr_extConf    = null;
    // TYPO3 extension



    ////////////////////////////////////////////////////////////////////
    //
    // Template

    // [String/HTML] Content of the current template
  var $template;
    // [String/HTML] Raw template - for comparing with current template
  var $str_template_raw;
    // [String] The wrap for the group title in listr views (i.e <h2>|</h2)
  var $str_wrap_grouptitle;
    // Template



    ////////////////////////////////////////////////////////////////////
    //
    // Misc

    // [Object] System language Object. $lang->lang cotain the current language.
  var $lang;
    // [Boolean] Is it the first call of the plugin?
  var $boolFirstVisit;
    // The human readable format for timestamps out of the TS
  var $tsStrftime;
    // Misc



    ////////////////////////////////////////////////////////////////////
    //
    // views

    // [Array] Items for the mode selector
  var $arrModeItems = array();
   // [String] The current view type: list || single
  var $view;
    // [String/CSV] List with pids of the records of the local table
  var $pidList;
    // [Integer] Uid of the current singlePid. Is set in list view only.
  var $singlePid;
    // [Integer]  The current mode (view). We need $piVar_mode, if there is only one view.
    //            We like a nice real url path, so we don't want the piVars[mode] in this case.
  var $piVar_mode   = false;
    // [String]   The current tab of the A-Z-Browser. We need $piVar_azTab, if the current tab is the default tab.
    //            We like a nice real url path, so we don't want the piVars[azTab] in this case.
  var $piVar_azTab  = false;
    // [String] The current piVar Sword in secure mode
  var $piVar_sword  = false;
    // [String] Alias of the showUid
  var $piVar_alias_showUid  = false;
    // [Array] Array with fieldnames, which should wrapped as a link to a single view
  var $arrLinkToSingle = array( );
  var $lDisplayType;
  // [String] Possible values: displaySingle || displayList.
  var $lDisplay;
  // [Array] Local array with the configuration of displaySingle.display or displayList.display
   // views



    ////////////////////////////////////////////////////////////////////
    //
    // AJAX (object I)

    // #9659, 101010 fsander
    // [Array] contains for each segment wether it should be shown or not (needed for AJAX object I)
  var $segment;
    // AJAX (object I)



    ////////////////////////////////////////////////////////////////////
    //
    // "SQL"
    
    // [String/CSV] List of fields for the SQL select query
  var $csvSelect;
    // [String/CSV] List of fields for the SQL select query, cleaned up from any function
  var $csvSelectWoFunc;
    // [String/CSV] List of fields for the SQL query orderBy
  var $csvOrderBy;  // 090628, depricated. See $conf_sql below
    // [Array] Array with the SQL query parts from the TypoScript.
    //         LF and CR are cleaned up.
    //         tableFields and functions got an alias
    //         Elements
    //         - select:   select clause
    //         - search:   list with fields from db, in which search is enabled
    //         - groupBy:  group-by-clause NOT FOR SQL but for php multisort and consolidation
    //         - orderBy:  order-by-clause NOT FOR SQL but for php multisort
    //         - andWhere: and where clause
  var $conf_sql;
    // [Array] Array with andWhere statements generated by the filter class
  var $arr_andWhereFilter;
    // "SQL"



    ////////////////////////////////////////////////////////////////////
    //
    // Sword

  var $arr_swordPhrases;
  // [Array] Array with sword phrases. Example: My Word "My Phrase" will be [0] My, [1] Word, [2] My Phrase
  var $arr_swordToShort;
  // [Array] Array with swords, which are shorter then len in
  var $arr_resultphrase;
  // [Array] Array with elements for the result phrase
  var $arr_swordPhrasesTableField;
  // [Array] Array with tableField elements with sword phrases.
  // Example:
  // ['tx_juridat_pi1.reg_num'][0]  = 'Einkünfte'
  // ['tx_juridat_pi1.reg_num'][1]  = 'Berufsverband'
  // ['tx_juridat_pi1.issue'][0]    = 'Einkünfte'
  // ['tx_juridat_pi1.issue'][1]    = 'Berufsverband'
    // Sword



    ////////////////////////////////////////////////////////////////////
    //
    // rows

    // [String] Path to the uplod folder of the current element
  var $uploadFolder;
    // [Array] The elements of the current row
  var $elements;
    // [Array] The rows of the SQL result: $uids_of_all_rows[uid_of_the_plugin][rows]
  var $rows;
    // [Array] Uids of all rows (after consolidation but before limitation)
  var $uids_of_all_rows;
    // [Boolean] true if current row is the first row, false if not; Don't change the value!
  var $boolFirstRow = true;
    // TRUE, if the current element is the first in the row
  var $boolFirstElement = true;
    // rows



    ////////////////////////////////////////////////////////////////////
    //
    // Relation building

    // The local or global record array from the TS
  var $recordTS;
    // [Array] Array with the field names for the SQL select statement, but without uid and some other special cases
  var $arrSelectRow = array();
    // [String] The local table out of TS record.uid
  var $localTable = '';
    // [Array] Array with the table.uid and table.pid of the localtable. Syntax: array[uid] = table.field, array[pid] = table.field
  var $arrLocalTable = '';
    // [Array] Array with tables for an autmatic relation building, Syntax [table][] = field.
  var $arr_realTables_arrFields;
    // [Array] Array with consolidating information. Syntax [addedTableFields][] = table.field.
  var $arrConsolidate;
    // [Array] Array with localised tables
  var $arr_realTables_localised;
    // [Array] Array with tables, which aren't localised
  var $arr_realTables_notLocalised;
    // [Array] Array with the tables.fields of children records, which have to devide while stdWrap
  var $arr_children_to_devide;
    // SQL configuration
    // FALSE: User defined a select statement only, Browser should build the full query automatically
    // TRUE: User has defined a SELECT, FROM, WHERE and maybe JOINS. Browser should use a manual configured SQL query
  var $b_sql_manual = false;
    // Relation building



    ////////////////////////////////////////////////////////////////////
    //
    // Auto Discover

    // TRUE, if method autodiscConfig is used the first time. Don't change the value TRUE!
  var $boolFirstTimeAutodiscover = true;
    // FALSE, if array arrHandleAs isn't processed completly. Don't change the value FALSE!
  var $boolArrHandleAsProcessed = false;
  // Array with the autodiscover configuration
  var $confAutodiscover;
    // Array with the names of that fields, which shouldn't wrapped automatically
  var $arrDontDiscoverFields;
    // Array with detected fields for arrHandleAs automatically
  var $arrHandleAs;
    // Array with fields in the array handleAs in the TS
  var $TShandleAs;
    // Auto Discover



    ////////////////////////////////////////////////////////////////////
    //
    // DRS - Development Reporting System

  var $str_developer_name     = 'Dirk Wildt';
  var $str_developer_company  = 'Die Netzmacher';
  var $str_developer_web      = 'http://wildt.die-netzmacher.de';
  var $str_developer_typo3ext = 'http://typo3.org/extensions/repository/view/browser/current/';
  var $str_developer_lang     = 'german, english';
    // [Boolean] Set by init_drs( )
  var $developer_contact      = false;
    // [String, csv] Csv list of IP-addresses of the developer / integrator
    //               Needed for reports in the frontend
  var $str_developer_csvIp    = null;
    // Booleans for DRS - Development Reporting System
  var $b_drs_all          = false;
  var $b_drs_error        = false;
  var $b_drs_warn         = false;
  var $b_drs_info         = false;
  var $b_drs_browser      = false;
  var $b_drs_cal          = false;
  var $b_drs_discover     = false;
  var $b_drs_download     = false;
  var $b_drs_export       = false;
  var $b_drs_filter       = false;
  var $b_drs_flexform     = false;
  var $b_drs_hooks        = false;
  var $b_drs_javascript   = false;
  var $b_drs_localisation = false;
  var $b_drs_map          = false;
  var $b_drs_marker       = false;
  var $b_drs_perform      = false;
  var $b_drs_realurl      = false;
  var $b_drs_search       = false;
  var $b_drs_seo          = false;
  var $b_drs_session      = false;
  var $b_drs_socialmedia  = false;
  var $b_drs_statistics   = false;
  var $b_drs_sql          = false;
  var $b_drs_tca          = false;
  var $b_drs_templating   = false;
  var $b_drs_tsUpdate     = false;
  var $b_drs_ttc          = false;
    // Booleans for DRS - Development Reporting System
    // Value will be overriden, if there is a value in $conf
  var $i_drs_max_sql_result_len = 100;
    // DRS - Development Reporting System



    ////////////////////////////////////////////////////////////////////
    //
    // Development

    // [Boolean] True: current IP is element of list with allowed IPs; false: it isn't
  var $bool_accessByIP = null;
    // Use cache: FALSE || TRUE; If you develope this extension, it can be helpfull to set this var on FALSE (no cache)
  var $boolCache = true;
    // [Boolean] If true, the current version is TYPO3 4.3 at least
  var $bool_typo3_43 = false;
    // [Boolean] If true, the current plugin won't be report any log to the DRS. It is configured by the plugin sheet [development]
  var $bool_dontUseDRS = false;
    // [Boolean] If true, Javascript is running in debugging mode. It is configured by the plugin sheet [development]
  var $bool_debugJSS = false;
    // [Integer] timetracking: start time
  var $tt_startTime   = 0;
    // [Integer] timetracking: previous end time
  var $tt_prevEndTime = 0;
    // [Integer] 0 (OK), 2 (WARN), 3 (ERROR): kind of last prompt
  var $tt_prevPrompt  = null;
    // Development









  /***********************************************
   *
   * Main Process
   *
   **********************************************/




  /**
 * main( ): Main method of your PlugIn
 *
 * @param	string		$content: The content of the PlugIn
 * @param	array		$conf: The PlugIn Configuration
 * @return	string		The content that should be displayed on the website
 * @version 3.9.8
 * @since   0.0.1
 */
  public function main( $content, $conf )
  {
      // Globalise TypoScript configuration
    $this->conf = $conf;
      // Set default values for piVars[]
    $this->pi_setPiVarDefaults();
      // Init localisation
    $this->pi_loadLL();
      // Set the global $bool_typo3_43
    $this->get_typo3version( );
      // Init timetracking, set the starttime
    $this->timeTracking_init( );
      // Get the values from the localconf.php file
    $this->arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
      // Init DRS - Development Reporting System
    $this->init_drs();
      // Init current IP
    $this->init_accessByIP( );

      // Prompt the expired time to devlog
    $this->timeTracking_log( 'START' );



      //////////////////////////////////////////////////////////////////////
      //
      // Init Update Check

      // Update check is enabled
    if( $this->arr_extConf['updateWizardEnable'] )
    {
        // Current IP has access
      if( $bool_accessByIP )
      {
        require_once(PATH_typo3conf . 'ext/' . $this->extKey . '/pi2/class.tx_browser_pi2.php' );
          // Class with methods for Update Checking
        $this->objPi2     = new tx_browser_pi2( $this );
        $html_updateCheck = $this->objPi2->main( $content, $conf, $this );
      }
        // Current IP has access
    }
      // Update check is enabled
      // Init Update Check



      //////////////////////////////////////////////////////////////////////
      //
      // Get the global TCA

      /* BACKGROUND : t3lib_div::loadTCA($table) loads for the frontend
       * only 'ctrl' and 'feInterface' parts.
       */
    $GLOBALS['TSFE']->includeTCA( );
      // Get the global TCA



      //////////////////////////////////////////////////////////////////////
      //
      // Require and init helper classes

    $this->require_classes( );
      // Require and init helper classes



      //////////////////////////////////////////////////////////////////////
      //
      // Get pid list

    if ( strstr( $this->cObj->currentRecord, 'tt_content' ) )
    {
      $this->conf['pidList']    = $this->cObj->data['pages'];
      $this->conf['recursive']  = $this->cObj->data['recursive'];
      $this->pidList = $this->pi_getPidList( $this->conf['pidList'], $this->conf['recursive'] );
    }
      // Get pid list


      //////////////////////////////////////////////////////////////////////
      //
      // Make cObj instance

    $this->local_cObj = t3lib_div::makeInstance( 'tslib_cObj' );
      // Make cObj instance



      //////////////////////////////////////////////////////////////////////
      //
      // Clean up views ( multiple plugins )

      // #11981, 110106, dwildt
    $conf       = $this->objZz->cleanup_views( $conf );
    $this->conf = $conf;
      // Clean up views ( multiple plugins )



      //////////////////////////////////////////////////////////////////////
      //
      // Get the typeNum I/II

      // #31230, 111108, dwildt
    $this->objDownload->set_typeNum( );
      // #29370, 110831, dwildt
    $this->objExport->set_typeNum( );
      // Get the typeNum I/II



      //////////////////////////////////////////////////////////////////////
      //
      // Get Configuration out of the Plugin (Flexform) but [Templating]

    $this->objFlexform->main( );
    $conf = $this->conf;
      // Get Configuration out of the Plugin (Flexform) but [Templating]



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire piVars

      // Allocates values to $this->piVars, $this->pi_isOnlyFields and $this->views
    $this->objZz->prepairePiVars( );
      // Prepaire piVars



      //////////////////////////////////////////////////////////////////////
      //
      // Get Configuration out of the Plugin (Flexform) devider [Templating]

    $this->objFlexform->sheet_templating( );
      // Get Configuration out of the Plugin (Flexform) devider [Templating]



      //////////////////////////////////////////////////////////////////////
      //
      // Set the class variables

    $this->init_classVars( );
      // Set the class variables



      //////////////////////////////////////////////////////////////////////
      //
      // Get the typeNum II/II

      // #32654, 120212, dwildt+
    $this->objMap->set_typeNum( );
      // Get the typeNum II/II



      //////////////////////////////////////////////////////////////////////
      //
      // Control the plugin by URL parameter

      // #32099, 111126, dwildt+
    if( ! $this->objViews->displayThePlugin( ) )
    {
      if ($this->b_drs_templating)
      {
        $prompt = 'RETURN. The current plugin should not handled';
        t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->extKey, 0);
      }
      return;
    }
      // Control the plugin by URL parameter



      //////////////////////////////////////////////////////////////////////
      //
      // Replace TSFE markers

//:TODO: 120213. Performance: Methode evt. nicht mehr unterstuetzen. Stattdessen stdWrap.data.
    $this->conf = $this->objZz->substitute_t3globals_recurs( $this->conf );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after substitute_t3globals_recurs( )' );
      // Replace TSFE markers



      //////////////////////////////////////////////////////////////////////
      //
      // Download: send the file ...

      // #31230, 111110, dwildt+
    if( $this->objDownload->str_typeNum == 'download' )
    {
        // EXIT:  $this->objDownload->download will exit in case of success
        //        There is a prompt only in case of an error
      $prompt_error = $this->objDownload->download( );

        // RETURN in case of an error
      return $this->objWrapper->wrapInBaseIdClass( $prompt_error );
    }
      // Download: send the file ...



      //////////////////////////////////////////////////////////////////////
      //
      // Get the HTML template

    $arr_result = $this->getTemplate( );

      // RETURN error
    if( $arr_result['error']['status'] )
    {
      if( $this->b_drs_error )
      {
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 3);
          // Prompt the expired time to devlog
        $this->timeTracking_log( 'END' );
      }
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $prompt );
    }
      // RETURN error

      // Init global $str_template_raw
    $this->str_template_raw = $arr_result['data']['template'];
    unset( $arr_result );
      // Get the HTML template



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire modeSelector

    $arr_result = $this->objNavi->prepaireModeSelector( );
    if( $arr_result['error']['status'] )
    {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->objWrapper->wrapInBaseIdClass( $prompt );
    }
    $this->arrModeItems = $arr_result['data'];
    unset( $arr_result );
      // Prepaire modeSelector



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire format for time values

    $this->tsStrftime = $this->objZz->setTsStrftime( );
      // Prepaire format for time values



      //////////////////////////////////////////////////////////////////////
      //
      // Get used tables from the SQL query parts out of the Typoscript

    $this->arr_realTables_arrFields = $this->objTyposcript->fetch_realTables_arrFields( );
      // Get used tables from the SQL query parts out of the Typoscript



      //////////////////////////////////////////////////////////////////////
      //
      // Get the local table uid field name and pid field name

    $this->arrLocalTable = $this->objTyposcript->fetch_localTable( );
    if( ! is_array( $this->arrLocalTable ) )
    {
      if( $this->b_drs_error )
      {
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 3);
          // Prompt the expired time to devlog
        $this->timeTracking_log( 'END' );
      }
      $prompt = '<h1 style="color:red;">' . $this->pi_getLL( 'error_readlog_h1' ) . '</h1>' . PHP_EOL .
                '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_table_no' ) . '</p>';
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $prompt );
    }
      // Get the local table uid field name and pid name



      //////////////////////////////////////////////////////////////////////
      //
      // Set the global $localTable

    list( $this->localTable, $field ) = explode( '.', $this->arrLocalTable['uid'] );
      // Set the global $localTable



      //////////////////////////////////////////////////////////////////////
      //
      // Add missing uids and pids

    $arr_result = $this->objConsolidate->addUidAndPid( );
    $this->arrConsolidate['addedTableFields'] = $arr_result['data']['consolidate']['addedTableFields'];
    $this->arr_realTables_arrFields           = $arr_result['data']['arrFetchedTables'];
    unset( $arr_result );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objConsolidate->addUidAndPid( )' );
      // Add missing uids



      //////////////////////////////////////////////////////////////////////
      //
      // Set the manual SQL mode or the auto SQL mode

      // Process of the query building in case of a manual configuration with SELECT, FROM and WHERE and maybe JOINS
    $arr_result = $this->objSqlMan->check_typoscript_query_parts( );

      // RETURN error
    if( $arr_result['error']['status'] )
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $template );
    }
      // RETURN error

      // Auto SQL mode: The user configured only a select statement
    if( empty( $arr_result ) )
    {
      $this->b_sql_manual = false;
      if( $this->b_drs_sql )
      {
        $prompt = 'User configured a SELECT statement only: SQL auto mode.';
        t3lib_div::devLog('[INFO/DRS] ' . $prompt, $this->extKey, 0);
      }
    }
      // Auto SQL mode: The user configured only a select statement

      // Manual SQL mode: The user configured more than a select statement
    if( $arr_result )
    {
        // The user configured a whole SQL query
      $this->b_sql_manual = true;
      if( $this->b_drs_sql )
      {
        $prompt = 'User configured a whole SQL query: SQL manual mode.';
        t3lib_div::devLog('[INFO/DRS] ' . $prompt, $this->extKey, 0);
      }
    }
      // Manual SQL mode: The user configured more than a select statement

      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objSqlMan->check_typoscript_query_parts( )' );
      // Set the manual SQL mode or the auto SQL mode



      //////////////////////////////////////////////////////////////////////
      //
      // Process the views

      // Prompt the expired time to devlog
    $this->timeTracking_log( 'before processing the view' );
      // SWITCH view
    switch( $this->view )
    {
      case( 'list' ):
        if( $this->pObj->bool_accessByIP )
        {
          $str_template_completed = $this->objListview->listView( );
          break;
        }
        $str_template_completed = $this->objViews->listView( );
        break;
      case( 'single' ):
        $str_template_completed = $this->objViews->singleView( );
        break;
    }
      // SWITCH view
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after processing the view' );
      // Process the views



      //////////////////////////////////////////////////////////////////////
      //
      // Error, if the completed template is an array and has the element error.status

    if( is_array( $str_template_completed ) )
    {
        // Prompt the expired time to devlog
      $this->timeTracking_log( 'END' );

        // RETURN defined error
      if( $str_template_completed['error']['status'] == true )
      {
        $prompt = $str_template_completed['error']['header'] . $str_template_completed['error']['prompt'];
        return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass($prompt);
      }
        // RETURN defined error

        // RETURN undefined error
      $prompt = '<h1 style="color:red;">' . $this->pi_getLL('error_h1') . '</h1>' . PHP_EOL .
                '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_template_array' ) . '</p>';
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $prompt );
        // RETURN undefined error
    }
      // Error, if the completed template is an array and has the element error.status



      //////////////////////////////////////////////////////////////////////
      //
      // Error, if the completed template is the raw template

    if( $this->str_template_raw == $str_template_completed )
    {
        // Prompt the expired time to devlog
      $this->timeTracking_log( 'END' );
      $prompt = '<h1 style="color:red;">' . $this->pi_getLL('error_h1') . '</h1>' . PHP_EOL .
                '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_template_render' ) . '</p>';
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $prompt );
    }
      // Error, if the completed template is the raw template



      //////////////////////////////////////////////////////////////////////
      //
      // XML/RSS: return the result (XML string) without wrapInBaseClass

      // #28855, 110809, dwildt
    if( substr( $str_template_completed, 0, strlen( '<?xml' ) ) == '<?xml' )
    {
        // Prompt the expired time to devlog
      $this->timeTracking_log( 'END (XML is returned)' );
      return trim( $str_template_completed );
    }
      // XML/RSS: return the result (XML string) without wrapInBaseClass



      //////////////////////////////////////////////////////////////////////
      //
      // csv export: return the result (HTML string) without wrapInBaseClass

      // #29370, 110831, dwildt+
    switch( $this->objExport->str_typeNum )
    {
        // typeNum name is csv
      case( 'csv' ) :
        switch ( $this->objFlexform->sheet_viewList_csvexport )
        {
            // CSV export is enabled
          case( true ) :
              // Prompt the expired time to devlog
            $this->timeTracking_log( 'END (CSV file is returned)' );
            return trim( $str_template_completed );
            break;
            // CSV export isn't enabled
          case( false ) :
          default :
              // Prompt the expired time to devlog
            $this->timeTracking_log( 'END (no CSV file is returned)' );
            return 'CSV export isn\'t enabled. Please enable it in the plugin/flexform of your TYPO3-Browser.';
            break;
        }
        break;
        // typeNum name is csv
      default:
        // typeNum name isn't csv: Follow the workflow
    }
      // csv export: Set CSV field devider and field wrapper



      //////////////////////////////////////////////////////////////////////
      //
      // map marker category: return the result (HTML string) without wrapInBaseClass

      // #32654, 120212, dwildt+
    switch( $this->objMap->str_typeNum )
    {
        // typeNum name is map
      case( 'map' ) :
        switch ( $this->objMap->enabled )
        {
            // CSV export is enabled
          case( true ) :
              // Prompt the expired time to devlog
            $this->timeTracking_log( 'END (file with map markers is returned)' );
            return trim( $str_template_completed );
            break;
            // CSV export isn't enabled
          case( false ) :
          default :
            $prompt = 'Map marker export isn\'t enabled. Please enable it in your TYPO3-Browser. ' . PHP_EOL .
                      PHP_EOL .
                      'TypoScript: ' . PHP_EOL .
                      'views.list.' . $this->piVar_mode . '.navigation.map < plugin.tx_browser_pi1.navigation.map '  . PHP_EOL .
                      'views.list.' . $this->piVar_mode . '.navigation.map.enabled.value = 1 ';
              // DRS - Development Reporting System
            if( $this->b_drs_map )
            {
              t3lib_div :: devLog( '[ERROR/MAP] ' . $prompt , $this->extKey, 3 );
            }
              // Prompt the expired time to devlog
            $this->timeTracking_log( 'END (no file with map markers is returned)' );
            return $prompt;
            break;
        }
        break;
        // typeNum name is map
      default:
        // typeNum name isn't map: Follow the workflow
    }
      // #32654, 120212, dwildt+



      // 110804, dwildt
    $this->objJss->addCssFiles( );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objJss->addCssFiles( )' );



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX

      // #9659, 101010 fsander
    if( ! $this->objFlexform->bool_ajax_enabled )
    {
      if( $this->b_drs_javascript )
      {
        t3lib_div::devlog( '[INFO/JSS] AJAX is disabled.', $this->extKey, 0 );
        t3lib_div::devlog( '[HELP/JSS] Change it: configure the browser flexform [AJAX].', $this->extKey, 1 );
      }
    }

    $bool_load_jQuery = false;
    if( $this->objFlexform->bool_ajax_enabled )
    {
      if( $this->b_drs_javascript )
      {
        t3lib_div::devlog( '[INFO/JSS] AJAX is enabled.', $this->extKey, 0 );
        t3lib_div::devlog( '[INFO/JSS] jQuery will be loaded.', $this->extKey, 0 );
      }
      $bool_load_jQuery = true;
    }
    if( $this->objFlexform->bool_jquery_ui )
    {
      if( $this->b_drs_javascript )
      {
        t3lib_div::devlog( '[INFO/JSS] jQuery UI should included.', $this->extKey, 0 );
        t3lib_div::devlog( '[INFO/JSS] jQuery will be loaded.', $this->extKey, 0 );
      }
      $bool_load_jQuery = true;
    }

    if( $bool_load_jQuery )
    {
        // Adding jQuery
      $bool_success_jQuery = $this->objJss->load_jQuery( );
      if( $bool_success_jQuery )
      {
        // Wrap the template with a div with AJAX identifiers
        $str_template_completed = $this->objJss->wrap_ajax_div( $str_template_completed );
      }
      if( ! $bool_success_jQuery )
      {
        if( $this->b_drs_warn )
        {
          $prompt = 'AJAX JSS file is not included because of missing jQuery.';
          t3lib_div::devlog( '[WARN/JSS] ' . $prompt, $this->extKey, 2 );
        }
      }
    }
      // #9659, 101010 fsander
      // AJAX



      // 110804, dwildt
    $this->objJss->addJssFiles( );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objJss->addJssFiles( )' );



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX: Remove from single view the no AJAX content

    if( $this->segment['wrap_piBase'] == false )
    {
        // Do we have an AJAX marker in the template
      $pos = strpos( $str_template_completed, '###AREA_FOR_AJAX_LIST' );
      if( ! ( $pos === false ) )
      {
        $str_template_part_01   = $this->cObj->getSubpart( $str_template_completed, '###AREA_FOR_AJAX_LIST_01###' );
        $str_template_part_02   = $this->cObj->getSubpart( $str_template_completed, '###AREA_FOR_AJAX_LIST_02###' );
        $str_template_part_03   = $this->cObj->getSubpart( $str_template_completed, '###AREA_FOR_AJAX_LIST_03###' );
        $str_template_completed = $str_template_part_01 . $str_template_part_02 . $str_template_part_03;
      }
    }
      // AJAX: Remove from single view the no AJAX content



      //////////////////////////////////////////////////////////////////////
      //
      // Get the map

      // #32654, 111219, dwildt
    $str_template_completed = $this->objMap->get_map( $str_template_completed );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objMap->get_map( )' );
      // Get the map



      //////////////////////////////////////////////////////////////////////
      //
      // Replace left over markers

      // 110801, dwildt, #28657
    $str_template_completed = $this->objMarker->replace_left_over( $str_template_completed );
      // Prompt the expired time to devlog
    $this->timeTracking_log( 'after $this->objMarker->replace_left_over( )' );
      // Replace left over markers



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX: return the result (HTML string) without wrapInBaseClass

    if( ! $this->segment['wrap_piBase'] )
    {
        // Prompt the expired time to devlog
      $this->timeTracking_log( 'END' );
      return trim( $str_template_completed );
    }
      // AJAX: return the result (HTML string) without wrapInBaseClass



      // 12367, dwildt, 110310
    switch( $this->objFlexform->bool_wrapInBaseClass )
    {
      case( false ):
          // Prompt the expired time to devlog
        $this->timeTracking_log( 'END' );
        return $html_updateCheck . $str_template_completed;
        break;
      case( true ):
      default:
          // Prompt the expired time to devlog
        $this->timeTracking_log( 'END' );
        return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $str_template_completed );
    }
  }









  /***********************************************
   *
   * DRS - Development Reporting System
   *
   **********************************************/



  /**
 * Set the booleans for Warnings, Errors and DRS - Development Reporting System
 *
 * @return	void
 */
  private function init_drs()
  {

      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire the developer contact prompt

    $this->developer_contact =
        'company: '.  $this->str_developer_company.'<br />'.
        'name: '.     $this->str_developer_name   .'<br />'.
        'web: <a href="'.$this->str_developer_web.'" title="Website" target="_blank">'.$this->str_developer_web.'</a><br />'.
        'languages: '.$this->str_developer_lang.'<br /><br />'.
        'TYPO3 Repository:<br /><a href="'.$this->str_developer_typo3ext.'" title="'.$this->extKey.' online" target="_blank">'.
    $this->str_developer_typo3ext.'</a>';
    $i_len = intval($this->conf['drs.']['sql.']['result.']['max_len']);
    if ($i_len > 0)
    {
      $this->i_drs_max_sql_result_len = $i_len;
    }
      // Prepaire the developer contact prompt



      //////////////////////////////////////////////////////////////////////
      //
      // Set the DRS mode

    if ($this->arr_extConf['drs_mode'] == 'All')
    {
      $this->b_drs_all          = true;
      $this->b_drs_error        = true;
      $this->b_drs_warn         = true;
      $this->b_drs_info         = true;
      $this->b_drs_browser      = true;
      $this->b_drs_cal          = true;
      $this->b_drs_discover     = true;
      $this->b_drs_download     = true;
      $this->b_drs_export       = true;
      $this->b_drs_filter       = true;
      $this->b_drs_flexform     = true;
      $this->b_drs_hooks        = true;
      $this->b_drs_javascript   = true;
      $this->b_drs_localisation = true;
      $this->b_drs_map          = true;
      $this->b_drs_marker       = true;
      $this->b_drs_perform      = true;
      $this->b_drs_realurl      = true;
      $this->b_drs_search       = true;
      $this->b_drs_seo          = true;
      $this->b_drs_session      = true;
      $this->b_drs_socialmedia  = true;
      $this->b_drs_sql          = true;
      $this->b_drs_session      = true;
      $this->b_drs_statistics   = true;
      $this->b_drs_tca          = true;
      $this->b_drs_templating   = true;
      $this->b_drs_tsUpdate     = true;
      $this->b_drs_ttc          = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Auto Discover development')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_discover   = true;
      $this->b_drs_perform    = true;
      $this->b_drs_tca        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Calendar')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_cal        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Download')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_download   = true;
      $this->b_drs_localisation  = true;
      $this->b_drs_statistics = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Export')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_export     = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Filter and Category Menu')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_filter     = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Flexform')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_flexform   = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Hooks')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_hooks      = true;
      $this->b_drs_perform    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Javascript')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_javascript = true;
      $this->b_drs_perform    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Localisation')
    {
      $this->b_drs_error        = true;
      $this->b_drs_warn         = true;
      $this->b_drs_info         = true;
      $this->b_drs_localisation = true;
      $this->b_drs_perform      = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Map')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_map        = true;
//      $this->b_drs_perform    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'PageBrowser and A-Z-Browser')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_browser    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Performance')
    {
      $this->b_drs_perform    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Realurl')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_realurl    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Search')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_search     = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'SEO (Search Engine Optimization)')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_seo        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Session Management')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_session    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Social media')
    {
      $this->b_drs_error        = true;
      $this->b_drs_warn         = true;
      $this->b_drs_info         = true;
      $this->b_drs_socialmedia  = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System: Social media.', $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'SQL development')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_perform    = true;
      $this->b_drs_sql        = true;
      $this->b_drs_tca        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Statistics')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_statistics = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Templating')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_marker     = true;
      $this->b_drs_perform    = true;
      $this->b_drs_templating = true;
      $this->b_drs_ttc        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Typoscript Template Container')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_marker     = true;
      $this->b_drs_perform    = true;
      $this->b_drs_templating = true;
      $this->b_drs_ttc        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Typoscript Update Checker')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_tsUpdate   = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Warnings and errors')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
      // Set the DRS mode

  }









  /***********************************************
   *
   * Classes
   *
   **********************************************/



  /**
 * Init the helper classes
 *
 * @return	void
 */
  private function require_classes()
  {
      //////////////////////////////////////////////////////////////////////
      //
      // Require and init helper classes

      // Class with methods for get calendar values
    require_once('class.tx_browser_pi1_cal.php');
    $this->objCal = new tx_browser_pi1_cal($this);

      // Class with methods for get flexform values
    require_once('class.tx_browser_pi1_flexform.php');
    $this->objFlexform = new tx_browser_pi1_flexform($this);

      // Class with methods for consolidating rows
    require_once('class.tx_browser_pi1_consolidate.php');
    $this->objConsolidate = new tx_browser_pi1_consolidate($this);

      // Class with methods for manage downloads
    require_once('class.tx_browser_pi1_download.php');
    $this->objDownload = new tx_browser_pi1_download($this);

      // Class with methods for exporting rows
    require_once('class.tx_browser_pi1_export.php');
    $this->objExport = new tx_browser_pi1_export($this);

      // Class with realurl methods
    require_once('class.tx_browser_pi1_filter.php');
    $this->objFilter = new tx_browser_pi1_filter($this);

      // #9659, 101016, dwildt
      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_javascript.php');
    $this->objJss = new tx_browser_pi1_javascript($this);

      // Class with methods for the map
    require_once('class.tx_browser_pi1_map.php');
    $this->objMap = new tx_browser_pi1_map($this);

      // Class with methods for the markers
    require_once('class.tx_browser_pi1_marker.php');
    $this->objMarker = new tx_browser_pi1_marker($this);

      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_multisort.php');
    $this->objMultisort = new tx_browser_pi1_multisort($this);

      // Class with methods for the modeSelector, the pageBrowser and the a-z-browser
    require_once('class.tx_browser_pi1_navi.php');
    $this->objNavi = new tx_browser_pi1_navi($this);

      // Class with localisation methods
    require_once('class.tx_browser_pi1_localisation.php');
    $this->objLocalise = new tx_browser_pi1_localisation($this);

      // Class with seo methods for Search Engine Optimization
    require_once('class.tx_browser_pi1_seo.php');
    $this->objSeo = new tx_browser_pi1_seo($this);

      // Class with session methods for the session management
    require_once('class.tx_browser_pi1_session.php');
    $this->objSession = new tx_browser_pi1_session($this);

      // Class with methods for social media
    require_once('class.tx_browser_pi1_socialmedia.php');
    $this->objSocialmedia = new tx_browser_pi1_socialmedia($this);

      // Class with sql methods, if user defined only a SELECT
    require_once('class.tx_browser_pi1_sql_auto.php');
    $this->objSqlAut = new tx_browser_pi1_sql_auto($this);

      // Class with sql methods for manual mode and auto mode
    require_once('class.tx_browser_pi1_sql_functions.php');
    $this->objSqlFun = new tx_browser_pi1_sql_functions($this);

      // Class with sql methods, if user defined a SELECT, FROM, WHERE and an array JOINS
    require_once('class.tx_browser_pi1_sql_manual.php');
    $this->objSqlMan = new tx_browser_pi1_sql_manual($this);

      // Class with methods for statistics requirement
    require_once('class.tx_browser_pi1_statistics.php');
    $this->objStat = new tx_browser_pi1_statistics($this);

      // Class with TCA methods, which evaluate the TYPO3 TCA array
    require_once('class.tx_browser_pi1_tca.php');
    $this->objTca = new tx_browser_pi1_tca($this);

      // Class with template methods, which return HTML
    require_once('class.tx_browser_pi1_template.php');
    $this->objTemplate = new tx_browser_pi1_template($this);

      // Class with ttcontainer methods, which return HTML
    require_once('class.tx_browser_pi1_ttcontainer.php');
    $this->objTTContainer = new tx_browser_pi1_ttcontainer($this);

      // Class with typoscript methods, which process typoscript
    require_once('class.tx_browser_pi1_typoscript.php');
    $this->objTyposcript = new tx_browser_pi1_typoscript($this);

      // Class with views methods, which process the list view and the single view
    require_once('class.tx_browser_pi1_views.php');
    $this->objViews = new tx_browser_pi1_views($this);

      // Class with methods for the list view
    require_once( 'class.tx_browser_pi1_viewlist.php' );
    $this->objListview = new tx_browser_pi1_viewlist( $this );

      // Class with wrapper methods for wrapping fields and link values
    require_once('class.tx_browser_pi1_wrapper.php');
    $this->objWrapper = new tx_browser_pi1_wrapper($this);

      // Class with zz methods
    require_once('class.tx_browser_pi1_zz.php');
    $this->objZz = new tx_browser_pi1_zz($this);

  }









  /**
 * Set variables in the helper classes
 *
 * @return	boolean		FALSE
 */
  private function init_classVars()
  {

      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_localisation.php

      // [Array] The current Typoscript configuration array
    $this->objLocalise->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objLocalise->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objLocalise->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objLocalise->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objLocalise->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';
    $this->objLocalise->init_typoscript();



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi.php

      // [Array] The current TypoScript configuration array
    $this->objNavi->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNavi->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNavi->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNavi->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNavi->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_session.php

      // [Array] The current TypoScript configuration array
    $this->objSession->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objSession->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objSession->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objSession->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSession->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_socialmedia.php

      // [Array] The current TypoScript configuration array
    $this->objSocialmedia->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objSocialmedia->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objSocialmedia->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objSocialmedia->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSocialmedia->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_statistics.php

      // [Array] The current TypoScript configuration array
    $this->objStat->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objStat->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objStat->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objStat->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objStat->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_tca.php

      // [Array] The current TypoScript configuration array
    $this->objTca->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objTca->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objTca->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objTca->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTca->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_template.php

      // [Array] The current TypoScript configuration array
//    $this->objTemplate->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objTemplate->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objTemplate->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objTemplate->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTemplate->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_typoscript.php

      // [Array] The current TypoScript configuration array
    $this->objTyposcript->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objTyposcript->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objTyposcript->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objTyposcript->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTyposcript->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';



    return false;
  }









  /***********************************************
   *
   * Helper
   *
   **********************************************/



  /**
 * get_typo3version( ): Get the current TYPO3 version, move it to an integer
 *                      and set the global $bool_typo3_43
 *
 * @return	void
 * @version 3.9.8
 * @since   2.0.0
 */
  private function get_typo3version( )
  {
      // Get the current TYPO3 version
    $str_version = TYPO3_version;

      // Set default value
    if( ! $str_version )
    {
      $str_version = '4.2.9';
    }
      // Set default value

      // Move version to an integer
    $int_version = t3lib_div::int_from_ver( $str_version );

      // Set the global $bool_typo3_43 
    if( $int_version >= 4003000 )
    {
      $this->bool_typo3_43 = true;
    }
    if( $int_version < 4003000 )
    {
      $this->bool_typo3_43 = false;
    }
      // Set the global $bool_typo3_43
  }









  /**
 * init_accessByIP( ):  Set the global $bool_accessByIP.
 *
 * @return	void
 * @version 3.9.8
 * @since   2.0.0
 */
  private function init_accessByIP( )
  {
      // No access by default
    $this->bool_accessByIP = false;

      // Get list with allowed IPs (< version 4.0)
    $this->str_developer_csvIp = $this->arr_extConf['updateWizardAllowedIPs'];
      // Get list with allowed IPs (>= version 4.0)
    $csvIP      = $this->arr_extConf['updateWizardAllowedIPs'];
    $currentIP  = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

      // Current IP is an element in the list
    $pos = strpos($csvIP, $currentIP );
    if( ! ( $pos === false ) )
    {
      $this->bool_accessByIP = true;
    }
      // Current IP is an element in the list

      // RETURN no DRS prompt
    if( ! $this->b_drs_all )
    {
      return;
    }
      // RETURN no DRS prompt

      // DRS prompt
    $prompt = $currentIP . ' is an element of ' . $csvIP;
    t3lib_div::devLog('[INFO/ALL] ' . $prompt, $this->extKey, 0 );
      // DRS prompt
  }









  /***********************************************
   *
   * Time tracking
   *
   **********************************************/









  /**
 * timeTracking_init( ):  Init the timetracking object.
 *                        Set the global $startTime.
 *
 * @return	void
 * @version 3.9.8
 * @since   0.0.1
 */
  private function timeTracking_init( )
  {
      // Init the timetracking object
    require_once( PATH_t3lib . 'class.t3lib_timetrack.php' );
    $this->TT = new t3lib_timeTrack;
    $this->TT->start( );
      // Init the timetracking object

      // Set the global $startTime.
    if( $this->bool_typo3_43 )
    {
      $this->tt_startTime = $this->TT->getDifferenceToStarttime();
    }
    if( ! $this->bool_typo3_43 )
    {
      $this->tt_startTime = $this->TT->mtime();
    }
      // Set the global $startTime.
  }









  /**
 * timeTracking_log( ): Prompts a message in devLog with current run time in miliseconds
 *
 *
 * @param   string  $prompt: The prompt for devlog.
 * @return	void
 * @version 3.9.8
 * @since   0.0.1
 */
  public function timeTracking_log( $prompt )
  {
      // RETURN: DRS shouldn't report performance prompts
    if( ! $this->b_drs_perform )
    {
      return;
    }
      // RETURN: DRS shouldn't report performance prompts

      // Get the current time
    if( $this->bool_typo3_43 )
    {
      $endTime = $this->TT->getDifferenceToStarttime();
    }
    if( ! $this->bool_typo3_43 )
    {
      $endTime = $this->TT->mtime( );
    }
      // Get the current time

      // Prompt the current time
    t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt . ': ' . ( $endTime - $this->tt_startTime ) . ' ms', $this->extKey, 0 );


    switch( true )
    {
      case( ( $endTime - $this->tt_prevEndTime ) >= 10000 ):
        $this->tt_prevPrompt = 3;
        $prompt_02 = 'Previous process needs more than 10 sec';
        t3lib_div::devLog('[ERROR/PERFORMANCE] ' . $prompt_02, $this->extKey, 3 );
        break;
      case( ( $endTime - $this->tt_prevEndTime ) >= 1000 ):
        $this->tt_prevPrompt = 2;
        $prompt_02 = 'Previous process needs more than 1 sec';
        t3lib_div::devLog('[WARN/PERFORMANCE] ' . $prompt_02, $this->extKey, 2 );
        break;
      default:
        $this->tt_prevPrompt = 0;
        // Do nothing
    }
    $this->tt_prevEndTime = $endTime;
  }









  /**
 * timeTracking_prompt( ):  Method checks, wether previous prompt was a
 *                          warning or an error. If yes the given prompt will loged by devLog
 *
 *
 * @param   string  $prompt: The prompt for devlog.
 * @return	void
 * @version 3.9.8
 * @since   3.9.8
 */
  public function timeTracking_prompt( $prompt )
  {
    switch( true )
    {
      case( $this->tt_prevPrompt == 3 ):
        $prompt_02 = 'ERROR';
        break;
      case( $this->tt_prevPrompt == 2 ):
        $prompt_02 = 'WARN';
        break;
      default:
          // Do nothing
        return;
    }

//    t3lib_div::devLog('[' . $prompt_02. '/PERFORMANCE] ' . $prompt, $this->extKey, $this->tt_prevPrompt );
    t3lib_div::devLog('[INFO/PERFORMANCE] Details about previous process: ' . $prompt, $this->extKey, $this->tt_prevPrompt );
  }








  
  /***********************************************
   *
   * Template
   *
   **********************************************/



  /**
 * getTemplate( ):  Get (HTML) the content of the $template.
 *                  Handles error.
 *
 * @return	array		With element template
 * @version 3.9.8
 * @since   1.0.0
 *
 */
  private function getTemplate( )
  {
    $cObj = $this->cObj;
    $conf = $this->conf;

    $view       = $this->view;
    $mode       = $this->piVar_mode;
    $viewWiDot  = $view.'.';

    $arr_return['error']['status']  = false;
    $arr_return['data']['template'] = false;


      //////////////////////////////////////////////////////////////////////
      //
      // Catch the template

    if( ! empty( $conf['views.'][$viewWiDot][$mode.'.']['template.']['file'] ) )
    {
        // Local HTML Template
      $template_path = $conf['views.'][$viewWiDot][$mode.'.']['template.']['file'];
    }
    if ( empty( $conf['views.'][$viewWiDot][$mode.'.']['template.']['file'] ) )
    {
        // Global HTML Template
      $template_path = $conf['template.']['file'];
    }
    $template = $cObj->fileResource( $template_path );
      // Catch the template



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if( ! $template )
    {
      if( $this->b_drs_error )
      {
        t3lib_div::devLog( '[ERROR/TEMPLATING] There is no template file. Path: ' . $template_path, $this->extKey, 3 );
        t3lib_div::devLog( '[ERROR/TEMPLATING] ABORTED', $this->extKey, 0 );
      }
      $str_header  = '<h1 style="color:red;">' . $this->pi_getLL( 'error_readlog_h1' ) . '</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_template_no' ) . '</p>';
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] = $str_header;
      $arr_return['error']['prompt'] = $str_prompt;
      return $arr_return;
    }
      // DRS - Development Reporting System



    $arr_return['data']['template'] = $template;
    return $arr_return;
  }



}










if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1.php']);
}

?>