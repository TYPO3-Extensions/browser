<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
  //define('Path_typo3', PATH_site.TYPO3_mainDir);
  // dwildt, 120625
  define('PATH_typo3', PATH_site.TYPO3_mainDir);
}
// TYPO3 Downwards Compatibility


require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Plugin 'Browser' for the 'browser' extension - the fastest way for your data into the TYPO3 frontend.
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  browser
 *
 * @version 4.5.0
 * @since 0.0.1
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  102: class tx_browser_pi1 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  410:     public function main( $content, $conf )
 *
 *              SECTION: DRS - Development Reporting System
 * 1145:     public function drs_debugTrail( $level = 1 )
 * 1181:     private function init_drs()
 * 1477:     public function dev_var_dump( $content )
 *
 *              SECTION: Classes
 * 1548:     private function require_classes()
 * 1693:     private function init_classVars( )
 *
 *              SECTION: Helper
 * 1934:     private function init_typo3version( )
 * 1976:     private function init_accessByIP( )
 *
 *              SECTION: Time tracking
 * 2038:     private function timeTracking_init( )
 * 2076:     public function timeTracking_log( $method, $line, $prompt )
 * 2137:     public function timeTracking_prompt( $prompt )
 *
 *              SECTION: Template
 * 2180:     private function getTemplate( )
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1 extends tslib_pibase {

    ////////////////////////////////////////////////////////////////////
    //
    // TYPO3

    // [INTEGER] TYPO3 version. Sample: 4.7.7 -> 4007007
  var $typo3Version = null;
  var $typoscriptVersion = null;
    // TYPO3
    
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
    // [String]   The current tab of the Index-Browser. We need $piVar_indexBrowserTab, if the current tab is the default tab.
    //            We like a nice real url path, so we don't want the piVars[indexBrowserTab] in this case.
  var $piVar_indexBrowserTab  = false;
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
  var $b_drs_cObjData     = false;
  var $b_drs_devTodo      = false;
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
  var $b_drs_navi         = false;
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

    // [Integer] Version of the Browser engine: 3 or 4
  var $dev_browserEngine  = null;
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
 * @param    string        $content: The content of the PlugIn
 * @param    array        $conf: The PlugIn Configuration
 * @return    string        The content that should be displayed on the website
 * @version 4.5.8
 * @since   0.0.1
 */
  public function main( $content, $conf )
  {
      // 130530, dwildt, -
//      // Globalise TypoScript configuration
//    $this->conf = $conf;
//      // Set default values for piVars[]
//    $this->pi_setPiVarDefaults();
//      // Init localisation
//    $this->pi_loadLL();
//      // Set the global $bool_typo3_43
//    $this->init_typo3version( );
//      // Init timetracking, set the starttime
//    $this->timeTracking_init( );
//      // Get the values from the localconf.php file
//    $this->arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
//      // Init DRS - Development Reporting System
//    $this->init_drs();
//      // Init current IP
//    $this->init_accessByIP( );
      // 130530, dwildt, -

    if( ! $this->init( $conf ) )
    {
        // #i0011, 130530, dwildt, +
      $prompt = '<h1 style="color:red;">' . $this->pi_getLL( 'error_typoscript_h1' ) . '</h1>' . PHP_EOL .
                '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_typoscript_is_missing' ) . '</p>';
      return $this->pi_wrapInBaseClass( $prompt );
        // #i0011, 130530, dwildt, +
    }

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'START' );



      //////////////////////////////////////////////////////////////////////
      //
      // Init Update Check

      // Update check is enabled
    if( $this->arr_extConf['updateWizardEnable'] )
    {
        // Current IP has access
      if( $this->bool_accessByIP )
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
      // Init browser engine

    $str_browserEngineIndicator = null;  
    switch( $this->arr_extConf['browserEngine'] )
    {
      case( 'Engine 3.x (deprecated)' ):
        $this->dev_browserEngine = 3;
        $str_browserEngineIndicator = '
          <div>
            <span style="background:#114400;color:white;font-size:.85em;">
              <a style="color:white;cursor:pointer;" title="You can disable this flag in the extension manager. See section Basic.">&nbsp;TYPO3 Browser Engine 3&nbsp;</a>
            </span>
          </div>';  
        if ($this->b_drs_warn)
        {
          $prompt = 'Browser engine 3.x is enabled';
          t3lib_div::devLog('[OK/SQL] ' . $prompt, $this->extKey, -1);
        }
        break;
      case( 'Engine 4.x (recommended)' ):
      default:
//        if( ! $this->bool_accessByIP )
//        {
//          $this->dev_browserEngine = 3;
//          if ($this->b_drs_sql)
//          {
//            $prompt = 'Browser engine 4.x is enabled. But current IP doesn\'t match list of allowed IPs!';
//            t3lib_div::devLog('[WARN/SQL] ' . $prompt, $this->extKey, 2);
//            $prompt = 'Browser engine 3.x is used';
//            t3lib_div::devLog('[OK/SQL] ' . $prompt, $this->extKey, -1);
//          }
//          break;
//        }
        $this->dev_browserEngine = 4;
        $str_browserEngineIndicator = '
          <div>
            <span style="background:#00003A;color:white;font-size:.85em;">
              <a style="color:white;cursor:pointer;" title="You can disable this flag in the extension manager. See section Basic.">&nbsp;TYPO3 Browser Engine 4.x - alpha - inofficial&nbsp;</a>
            </span>
          </div>';  
        if ($this->b_drs_warn)
        {
          $prompt = 'Browser engine 4.x is enabled';
          t3lib_div::devLog('[OK/SQL] ' . $prompt, $this->extKey, -1);
        }
        break;
    }
      // Init Browser engine



      //////////////////////////////////////////////////////////////////////
      //
      // Init checkedUpdate

    $str_checkedUpdate = null;  
    if( $this->arr_extConf['checked_3.']['9.']['14'] != 'I checked it' )
    {
      $str_checkedUpdate = '
        <div>
          <span style="background:#ad0000;color:white;font-size:.85em;">
            <a style="color:white;cursor:pointer;" title="TYPO3-Browser: Please confirm that you have checked the update. Go to the extension manager. See: Update Wizard.">&nbsp;Update isn\'t confirmed!&nbsp;</a>
          </span>
        </div>';  
    }
      // Init Browser engine
//$this->dev_var_dump( $this->arr_extConf );


      //////////////////////////////////////////////////////////////////////
      //
      // Init browserEngineIndicator

    if( $this->arr_extConf['browserEngineIndicator'] != 'On' )
    {
      $str_browserEngineIndicator = null;
    }
      // Init Browser engine



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
$this->dev_var_dump( $this->view );
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

    $this->timeTracking_log( $debugTrailLevel,  'before substitute_t3globals_recurs( )' );
    $this->conf = $this->objZz->substitute_t3globals_recurs( $this->conf );
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after substitute_t3globals_recurs( )' );
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
        $debugTrailLevel = 1;
        $this->timeTracking_log( $debugTrailLevel,  'END' );
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

    $arr_result = $this->objNaviModeSelector->prepaireModeSelector( );
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
        $debugTrailLevel = 1;
        $this->timeTracking_log( $debugTrailLevel,  'END' );
      }
      $prompt = '<h1 style="color:red;">' . $this->pi_getLL( 'error_readlog_h1' ) . '</h1>' . PHP_EOL .
                '<p style="color:red;font-weight:bold;">' . $this->pi_getLL( 'error_table_no' ) . '</p>';
      return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $prompt );
    }
      // Get the local table uid field name and pid name



      //////////////////////////////////////////////////////////////////////
      //
      // Set the global $localTable

    list( $this->localTable ) = explode( '.', $this->arrLocalTable['uid'] );
      // Set the global $localTable



      //////////////////////////////////////////////////////////////////////
      //
      // Add missing uids and pids

    $arr_result = $this->objConsolidate->addUidAndPid( );
    $this->arrConsolidate['addedTableFields'] = $arr_result['data']['consolidate']['addedTableFields'];
    $this->arr_realTables_arrFields           = $arr_result['data']['arrFetchedTables'];
    unset( $arr_result );
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objConsolidate->addUidAndPid( )' );
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
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objSqlMan->check_typoscript_query_parts( )' );
      // Set the manual SQL mode or the auto SQL mode



      //////////////////////////////////////////////////////////////////////
      //
      // Process the views

      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'before processing the view' );

$this->dev_var_dump( $this->view );

      // SWITCH view
    switch( $this->view )
    {
      case( 'list' ):
          // DEVELOPMENT: Browser engine 4.x
        switch( $this->dev_browserEngine )
        {
          case( 4 ):
            $str_template_completed = $this->objViewlist->main( );
            break;
          case( 3 ):
          default:
            $str_template_completed = $this->objViewlist_3x->main( );
            break;
        }
          // DEVELOPMENT: Browser engine 4.x
        break;
      case( 'single' ):
        $str_template_completed = $this->objViews->singleView( );
        break;
    }
      // SWITCH view
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after processing the view' );
      // Process the views



      //////////////////////////////////////////////////////////////////////
      //
      // Error, if the completed template is an array and has the element error.status

    if( is_array( $str_template_completed ) )
    {
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->timeTracking_log( $debugTrailLevel,  'END' );

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
      $debugTrailLevel = 1;
      $this->timeTracking_log( $debugTrailLevel,  'END' );
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
      $debugTrailLevel = 1;
      $this->timeTracking_log( $debugTrailLevel,  'END (XML is returned)' );
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
            $debugTrailLevel = 1;
            $this->timeTracking_log( $debugTrailLevel,  'END (CSV file is returned)' );
              // #33336, 130529, dwildt, 1+
            $str_template_completed = strip_tags( $str_template_completed );
              // #33336, 130529, dwildt, sesamnet-bug, 1+
            $str_template_completed = str_replace( 'begin -->"Status"', 'Status', $str_template_completed );
            return trim( $str_template_completed );
            break;
            // CSV export isn't enabled
          case( false ) :
          default :
              // Prompt the expired time to devlog
            $debugTrailLevel = 1;
            $this->timeTracking_log( $debugTrailLevel,  'END (no CSV file is returned)' );
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
            // #47632, 130508, 1-
          //case( true ) :
            // #47632, 130508, 3+
          case( 1 ) :
          case( 'Map' ) :
          case( 'Map +Route' ) :
              // Prompt the expired time to devlog
            $debugTrailLevel = 1;
            $this->timeTracking_log( $debugTrailLevel,  'END (file with map markers is returned)' );
            return trim( $str_template_completed );
            break;
            // CSV export isn't enabled
          case( false ) :
            // #47632, 130508, 1+
          case( 'disabled' ) :
            // #47632, 130508, 1-
          //default :
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
            $debugTrailLevel = 1;
            $this->timeTracking_log( $debugTrailLevel,  'END (no file with map markers is returned)' );
            return $prompt;
            break;
          // #47632, 130508, 5+
        default :
          $prompt = 'Unexpeted value in ' . __METHOD__ . ' (line ' . __LINE__ . '): ' .
                    'TypoScript property map.enabled is "' . $this->enabled . '".';
          die( $prompt );
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
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objJss->addCssFiles( )' );



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
      // #44296, 130104, dwildt, -
//    if( $this->objFlexform->bool_ajax_enabled )
//    {
//      if( $this->b_drs_javascript )
//      {
//        t3lib_div::devlog( '[INFO/JSS] AJAX is enabled.', $this->extKey, 0 );
//        t3lib_div::devlog( '[INFO/JSS] jQuery will be loaded.', $this->extKey, 0 );
//      }
//      $bool_load_jQuery = true;
//    }
//    if( $this->objFlexform->bool_jquery_ui )
//    {
//      if( $this->b_drs_javascript )
//      {
//        t3lib_div::devlog( '[INFO/JSS] jQuery UI should included.', $this->extKey, 0 );
//        t3lib_div::devlog( '[INFO/JSS] jQuery will be loaded.', $this->extKey, 0 );
//      }
//      $bool_load_jQuery = true;
//    }
      // #44296, 130104, dwildt, -

      // #44296, 130104, dwildt, +
    switch( true )
    {
      case( $this->objFlexform->bool_ajax_enabled ):
        $promptFf = 'AJAX is enabled.';
        $bool_load_jQuery = true;
        break;
      case( $this->objFlexform->bool_jquery_ui ):
        $promptFf = 'jQuery UI should included.';
        $bool_load_jQuery = true;
        break;
      case( $this->objFlexform->sheet_viewList_rotateviews ):
        $promptFf = '[rotate views] is enabled.';
        $bool_load_jQuery = true;
        break;
    }
      // #44296, 130104, dwildt, +

    if( $bool_load_jQuery )
    {
        // #44296, 130104, dwildt, 6+
      if( $this->b_drs_javascript )
      {
        t3lib_div::devlog( '[INFO/JSS] ' . $promptFf, $this->extKey, 0 );
        $prompt = 'jQuery will be loaded.';
        t3lib_div::devlog( '[INFO/JSS] ' . $prompt, $this->extKey, 0 );
      }
        // #44296, 130104, dwildt, 6+
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

      // #44296, 130104, dwildt, +
    if( $this->objFlexform->sheet_viewList_rotateviews )
    {
      switch( $this->objJss->t3jqueryIsUsed )
      {
        case( true ):
            // Follow the workflow
          break;
        case( false ):
        default:
          $this->objFlexform->bool_jquery_ui = true;
          if( $this->b_drs_javascript )
          {
            $prompt = '[rotate views] is enabled. objFlexform->bool_jquery_ui is set to true.';
            t3lib_div::devlog( '[INFO/JSS] ' . $prompt, $this->extKey, 0 );
          }
          break;
      }
    }
      // #44296, 130104, dwildt, +


      // 110804, dwildt
    $this->objJss->addJssFiles( );
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objJss->addJssFiles( )' );



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
$this->dev_var_dump( $this->view );
    $str_template_completed = $this->objMap->get_map( $str_template_completed );
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objMap->get_map( )' );
      // Get the map
//$this->dev_var_dump( $str_template_completed );



      //////////////////////////////////////////////////////////////////////
      //
      // Replace left over markers

      // 110801, dwildt, #28657
    $str_template_completed = $this->objMarker->replace_left_over( $str_template_completed );
      // Prompt the expired time to devlog
    $debugTrailLevel = 1;
    $this->timeTracking_log( $debugTrailLevel,  'after $this->objMarker->replace_left_over( )' );
      // Replace left over markers



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX: return the result (HTML string) without wrapInBaseClass

    if( ! $this->segment['wrap_piBase'] )
    {
        // Prompt the expired time to devlog
      $debugTrailLevel = 1;
      $this->timeTracking_log( $debugTrailLevel,  'END' );
      return trim( $str_template_completed );
    }
      // AJAX: return the result (HTML string) without wrapInBaseClass



      // 12367, dwildt, 110310
    switch( $this->objFlexform->bool_wrapInBaseClass )
    {
      case( false ):
          // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->timeTracking_log( $debugTrailLevel,  'END' );
        return $html_updateCheck . $str_checkedUpdate .$str_browserEngineIndicator . $str_template_completed;
        break;
      case( true ):
      default:
          // Prompt the expired time to devlog
        $debugTrailLevel = 1;
        $this->timeTracking_log( $debugTrailLevel,  'END' );
        return $html_updateCheck . $this->objWrapper->wrapInBaseIdClass( $str_checkedUpdate. $str_browserEngineIndicator. $str_template_completed );
    }
  }









  /***********************************************
   *
   * DRS - Development Reporting System
   *
   **********************************************/



/**
 * drs_debugTrail( ): Returns class, method and line of the call of this method.
 *                    The calling method is a debug method - if it is called by another
 *                    method, please set the level in the calling method to 2.
 *
 * @param    integer        $level: ...
 * @return    array        $arr_return : with elements class, method, line and prompt
 * @version 3.9.9
 * @since   3.9.9
 */
  public function drs_debugTrail( $level = 1 )
  {
    $arr_return = null; 
    
      // Get the debug trail
    $debugTrail_str = t3lib_utility_Debug::debugTrail( );

      // Get debug trail elements
    $debugTrail_arr = explode( '//', $debugTrail_str );

      // Get class, method
    $classMethodLine = $debugTrail_arr[ count( $debugTrail_arr) - ( $level + 2 )];
    list( $classMethod ) = explode ( '#', $classMethodLine );
    list($class, $method ) = explode( '->', $classMethod );
      // Get class, method

      // Get line
    $classMethodLine = $debugTrail_arr[ count( $debugTrail_arr) - ( $level + 1 )];
    list( $dummy, $line ) = explode ( '#', $classMethodLine );
    unset( $dummy );
      // Get line

      // RETURN content
    $arr_return['class']  = trim( $class );
    $arr_return['method'] = trim( $method );
    $arr_return['line']   = trim( $line );
    $arr_return['prompt'] = $arr_return['class'] . '::' . $arr_return['method'] . ' (' . $arr_return['line'] . ')';

    return $arr_return;
      // RETURN content
  }









  /**
 * dev_var_dump( ): var_dump the given content in the frontend
 *                  condition: current IP must be an element in the list of allowed IPs
 *
 * @param    mixed        $content : String or array for prompting in the frontend
 * @return    void
 * @version 3.9.11
 * @since   3.9.9
 */
  public function dev_var_dump( $content )
  //public function dev_var_dump( )
  {
    $level      = 1; // 1 level up
    $debugTrail = $this->drs_debugTrail( $level );

      // Log a security warning
    if ($this->b_drs_warn )
    {
      $prompt = 'Security risk: please disable -> dev_var_dump( ) in ' . $debugTrail['prompt'] . '.';
      t3lib_div::devlog('[WARN/SECURITY] ' . $prompt, $this->extKey, 2 );
    }
      // Log a security warning

      // RETURN current IP isn't any element of the list of the allowed IPs
    $pos = strpos( $this->str_developer_csvIp, t3lib_div :: getIndpEnv( 'REMOTE_ADDR' ) );
    if ( $pos === false )
    {
      return;
    }
      // RETURN current IP isn't any element of the list of the allowed IPs

      // Number of arguments;
    $numargs  = func_num_args( );
      // List of arguments;
    $arg_list = func_get_args( );

    $prompt = '<pre>' . $debugTrail['prompt'] . PHP_EOL .
              '</pre>' . PHP_EOL;
    echo $prompt;

    for( $i = 0; $i < $numargs; $i++ )
    {
        // Generate the prompt
        // Get the type of the content
      $type         = gettype( $arg_list[$i] );
        // Move content to a string
      $arg_list[$i] = var_export( $arg_list[$i], true );
        // Concatenate method, line, type and content. Wrap it with <pre>
      $prompt       = '<pre>type: ' . $type . PHP_EOL .
                      $arg_list[$i] . PHP_EOL .
                      '</pre>' . PHP_EOL;
        // Concatenate method, line, type and content. Wrap it with <pre>
        // Generate the prompt

        // Prompt the content
      echo $prompt;
    }
    
    unset( $content );

  }








  /***********************************************
   *
   * Classes
   *
   **********************************************/



  /**
 * Init the helper classes
 *
 * @return    void
 */
  private function require_classes()
  {
      //////////////////////////////////////////////////////////////////////
      //
      // Require and init helper classes

      // Class with methods for get calendar values
    require_once('class.tx_browser_pi1_cal.php');
    $this->objCal = new tx_browser_pi1_cal( $this );

      // Class with methods for get flexform values
    require_once('class.tx_browser_pi1_flexform.php');
    $this->objFlexform = new tx_browser_pi1_flexform( $this );

      // #44858, 130128, dwildt, 3+
      // Class with methods for handle the cObj->data
    require_once('class.tx_browser_pi1_cObjData.php');
    $this->objCObjData = new tx_browser_pi1_cObjData( $this );

      // Class with methods for consolidating rows
    require_once('class.tx_browser_pi1_consolidate.php');
    $this->objConsolidate = new tx_browser_pi1_consolidate( $this );

      // Class with methods for manage downloads
    require_once('class.tx_browser_pi1_download.php');
    $this->objDownload = new tx_browser_pi1_download( $this );

      // Class with methods for exporting rows
    require_once('class.tx_browser_pi1_export.php');
    $this->objExport = new tx_browser_pi1_export( $this );

      // Class with realurl methods
    require_once('class.tx_browser_pi1_filter_3x.php');
    $this->objFltr3x = new tx_browser_pi1_filter_3x( $this );

      // Class with filter methods
    require_once('class.tx_browser_pi1_filter_4x.php');
    $this->objFltr4x = new tx_browser_pi1_filter_4x( $this );

      // #9659, 101016, dwildt
      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_javascript.php');
    $this->objJss = new tx_browser_pi1_javascript( $this );

      // Class with methods for the map
    require_once('class.tx_browser_pi1_map.php');
    $this->objMap = new tx_browser_pi1_map( $this );

      // Class with methods for the markers
    require_once('class.tx_browser_pi1_marker.php');
    $this->objMarker = new tx_browser_pi1_marker( $this );

      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_multisort.php');
    $this->objMultisort = new tx_browser_pi1_multisort( $this );

      // Class 3.x with methods for the modeSelector, the pageBrowser and the index browser
    require_once('class.tx_browser_pi1_navi_3x.php');
    $this->objNavi_3x = new tx_browser_pi1_navi_3x( $this );

      // Class with methods for the index browser
    require_once('class.tx_browser_pi1_navi_indexBrowser.php');
    $this->objNaviIndexBrowser = new tx_browser_pi1_navi_indexBrowser( $this );

      // Class with methods for the mode selector
    require_once('class.tx_browser_pi1_navi_modeSelector.php');
    $this->objNaviModeSelector = new tx_browser_pi1_navi_modeSelector( $this );

      // Class with methods for the page browser
    require_once('class.tx_browser_pi1_navi_pageBrowser.php');
    $this->objNaviPageBrowser = new tx_browser_pi1_navi_pageBrowser( $this );

      // Class methods for the recordbrowser
    require_once('class.tx_browser_pi1_navi_recordbrowser.php');
    $this->objNaviRecordBrowser = new tx_browser_pi1_navi_recordbrowser( $this );

      // Class with localisation methods version 3.x
    require_once('class.tx_browser_pi1_localisation_3x.php');
    $this->objLocalise3x = new tx_browser_pi1_localisation_3x( $this );

      // Class with localisation methods version 4.x
    require_once('class.tx_browser_pi1_localisation.php');
    $this->objLocalise = new tx_browser_pi1_localisation( $this );

      // Class with seo methods for Search Engine Optimization
    require_once('class.tx_browser_pi1_seo.php');
    $this->objSeo = new tx_browser_pi1_seo( $this );

      // Class with session methods for the session management
    require_once('class.tx_browser_pi1_session.php');
    $this->objSession = new tx_browser_pi1_session( $this );

      // Class with methods for social media
    require_once('class.tx_browser_pi1_socialmedia.php');
    $this->objSocialmedia = new tx_browser_pi1_socialmedia( $this );

      // Class with sql methods, if user defined only a SELECT
    require_once('class.tx_browser_pi1_sql_auto.php');
    $this->objSqlAut = new tx_browser_pi1_sql_auto( $this );

      // Class with sql methods, if user defined only a SELECT
    require_once('class.tx_browser_pi1_sql_auto_3x.php');
    $this->objSqlAut_3x = new tx_browser_pi1_sql_auto_3x( $this );

      // Class with sql methods (engine 4.x)
    require_once('class.tx_browser_pi1_sql_functions.php');
    $this->objSqlFun = new tx_browser_pi1_sql_functions( $this );

      // Class with sql methods for manual mode and auto mode
    require_once('class.tx_browser_pi1_sql_functions_3x.php');
    $this->objSqlFun_3x = new tx_browser_pi1_sql_functions_3x( $this );

      // Class with sql methods (engine 4.x)
    require_once('class.tx_browser_pi1_sql_init.php');
    $this->objSqlInit = new tx_browser_pi1_sql_init( $this );

      // Class with sql methods, if user defined a SELECT, FROM, WHERE and an array JOINS
    require_once('class.tx_browser_pi1_sql_manual.php');
    $this->objSqlMan = new tx_browser_pi1_sql_manual( $this );

      // Class with methods for statistics requirement
    require_once('class.tx_browser_pi1_statistics.php');
    $this->objStat = new tx_browser_pi1_statistics( $this );

      // Class with TCA methods, which evaluate the TYPO3 TCA array
    require_once('class.tx_browser_pi1_tca.php');
    $this->objTca = new tx_browser_pi1_tca( $this );

      // Class with template methods, which return HTML
    require_once('class.tx_browser_pi1_template.php');
    $this->objTemplate = new tx_browser_pi1_template( $this );

      // Class with ttcontainer methods, which return HTML
    require_once('class.tx_browser_pi1_ttcontainer.php');
    $this->objTTContainer = new tx_browser_pi1_ttcontainer( $this );

      // Class with typoscript methods, which process typoscript
    require_once('class.tx_browser_pi1_typoscript.php');
    $this->objTyposcript = new tx_browser_pi1_typoscript( $this );

      // Class with views methods, which process the list view and the single view
    require_once('class.tx_browser_pi1_views.php');
    $this->objViews = new tx_browser_pi1_views( $this );

      // Class with methods for the list view
    require_once( 'class.tx_browser_pi1_viewlist_3x.php' );
    $this->objViewlist_3x = new tx_browser_pi1_viewlist_3x( $this );

      // Class with methods for the list view
    require_once( 'class.tx_browser_pi1_viewlist.php' );
    $this->objViewlist = new tx_browser_pi1_viewlist( $this );

      // Class with wrapper methods for wrapping fields and link values
    require_once('class.tx_browser_pi1_wrapper.php');
    $this->objWrapper = new tx_browser_pi1_wrapper( $this );

      // Class with zz methods
    require_once('class.tx_browser_pi1_zz.php');
    $this->objZz = new tx_browser_pi1_zz( $this );

  }



  /***********************************************
   *
   * Init
   *
   **********************************************/

  /**
 * init( )
 *
 * @param    array        $conf: The PlugIn Configuration
 * @return    void
 * @version 4.5.7
 * @since   4.5.7
 */
  private function init( $conf )
  {
      // Globalise TypoScript configuration
    $this->conf = $conf;
   
      // Set default values for piVars[]
    $this->pi_setPiVarDefaults( );
    
      // Init localisation
    $this->pi_loadLL( );
    
      // RETURN false: There isn't any TypoScript template
      // #i0011, 130530, dwildt, +
    if( ! isset( $conf[ 'version' ] ) )
    {
      return false;
    }
      // RETURN false: There isn't any TypoScript template
      
      // Set the global $bool_typo3_43
    $this->init_typo3version( );
    
      // Init timetracking, set the starttime
    $this->timeTracking_init( );
    
      // Get the values from the localconf.php file
    $this->arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
    
      // Init DRS - Development Reporting System
    $this->init_drs( );
    
      // Init TypoScript version
    $this->init_typoscriptVersion( );
    
      // Init current IP
    $this->init_accessByIP( );
    
    return true;
  }

  /**
 * init_accessByIP( ):  Set the global $bool_accessByIP.
 *
 * @return    void
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
  
/**
 * init_classVars( ): Set variables in the helper classes.
 *                    Start a script on some helper classes.
 *
 * @return    void
 * @version 3.9.9
 * @since   1.0.0
 */
  private function init_classVars( )
  {
      //////////////////////////////////////////////////////////////////////
      //
      // Short vars

    $conf_view = $this->conf['views.'][$this->view . '.'][$this->piVar_mode . '.'];
    $conf_path = 'views.' . $this->view . '.' . $this->piVar_mode . '.';
      // Short vars



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_filter_4x.php

      // [Array] The current TypoScript configuration array
    $this->objFltr4x->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objFltr4x->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objFltr4x->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objFltr4x->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objFltr4x->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_localisation.php

      // [Array] The current Typoscript configuration array
    $this->objLocalise3x->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objLocalise3x->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objLocalise3x->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objLocalise3x->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objLocalise3x->conf_path = $conf_path;
    $this->objLocalise3x->init_typoscript();






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
    $this->objLocalise->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objLocalise->conf_path = $conf_path;
    $this->objLocalise->init_typoscript();

    

      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi.php

      // [Array] The current TypoScript configuration array
    $this->objNavi_3x->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNavi_3x->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNavi_3x->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNavi_3x->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNavi_3x->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi_indexBrowser.php

      // [Array] The current TypoScript configuration array
    $this->objNaviIndexBrowser->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNaviIndexBrowser->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNaviIndexBrowser->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNaviIndexBrowser->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNaviIndexBrowser->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi_modeSelector.php

      // [Array] The current TypoScript configuration array
    $this->objNaviModeSelector->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNaviModeSelector->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNaviModeSelector->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNaviModeSelector->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNaviModeSelector->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi_pageBrowser.php

      // [Array] The current TypoScript configuration array
    $this->objNaviPageBrowser->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNaviPageBrowser->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNaviPageBrowser->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNaviPageBrowser->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNaviPageBrowser->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_navi_recordbrowser.php

      // [Array] The current TypoScript configuration array
    $this->objNaviRecordBrowser->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objNaviRecordBrowser->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objNaviRecordBrowser->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objNaviRecordBrowser->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objNaviRecordBrowser->conf_path = $conf_path;



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
    $this->objSession->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSession->conf_path = $conf_path;



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
    $this->objSocialmedia->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSocialmedia->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_sql_auto.php

      // [Array] The current TypoScript configuration array
    $this->objSqlAut->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objSqlAut->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objSqlAut->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objSqlAut->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSqlAut->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_sql_functions.php

      // [Array] The current TypoScript configuration array
    $this->objSqlFun->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objSqlFun->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objSqlFun->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objSqlFun->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSqlFun->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_sql_init.php

      // [Array] The current TypoScript configuration array
    $this->objSqlInit->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objSqlInit->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objSqlInit->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objSqlInit->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objSqlInit->conf_path = $conf_path;



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
    $this->objStat->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objStat->conf_path = $conf_path;



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
    $this->objTca->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTca->conf_path = $conf_path;



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
    $this->objTemplate->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTemplate->conf_path = $conf_path;



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
    $this->objTyposcript->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objTyposcript->conf_path = $conf_path;



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_viewlist.php

      // [Array] The current TypoScript configuration array
    $this->objViewlist_3x->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objViewlist_3x->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objViewlist_3x->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objViewlist_3x->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objViewlist_3x->conf_path = $conf_path;
      // class.tx_browser_pi1_viewlist.php



      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_viewlist_4x.php

      // [Array] The current TypoScript configuration array
    $this->objViewlist->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objViewlist->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objViewlist->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objViewlist->conf_view = $conf_view;
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objViewlist->conf_path = $conf_path;
      // class.tx_browser_pi1_viewlist.php
  }

 /**
  * Set the booleans for Warnings, Errors and DRS - Development Reporting System
  *
  * @return    void
  * @version  3.9.14
  * @since    2.0.0
  */
  public function init_drs()
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
      // If a plugin disabled the DRS ...

    $this->b_drs_all          = false;
    $this->b_drs_error        = false;
    $this->b_drs_warn         = false;
    $this->b_drs_info         = false;
    $this->b_drs_cal          = false;
    $this->b_drs_cObjData     = false;
    $this->b_drs_devTodo      = false;
    $this->b_drs_discover     = false;
    $this->b_drs_download     = false;
    $this->b_drs_export       = false;
    $this->b_drs_filter       = false;
    $this->b_drs_flexform     = false;
    $this->b_drs_hooks        = false;
    $this->b_drs_javascript   = false;
    $this->b_drs_localisation = false;
    $this->b_drs_map          = false;
    $this->b_drs_marker       = false;
    $this->b_drs_navi         = false;
    $this->b_drs_perform      = false;
    $this->b_drs_realurl      = false;
    $this->b_drs_search       = false;
    $this->b_drs_seo          = false;
    $this->b_drs_session      = false;
    $this->b_drs_socialmedia  = false;
    $this->b_drs_sql          = false;
    $this->b_drs_session      = false;
    $this->b_drs_statistics   = false;
    $this->b_drs_tca          = false;
    $this->b_drs_templating   = false;
    $this->b_drs_tsUpdate     = false;
    $this->b_drs_ttc          = false;
      // If a plugin disabled the DRS ...

      

      //////////////////////////////////////////////////////////////////////
      //
      // Set the DRS mode

    if( $this->arr_extConf['drs_mode'] == 'All' )
    {
      $this->b_drs_all          = true;
      $this->b_drs_error        = true;
      $this->b_drs_warn         = true;
      $this->b_drs_info         = true;
      $this->b_drs_cal          = true;
      $this->b_drs_cObjData     = true;
      $this->b_drs_devTodo      = true;
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
      $this->b_drs_navi         = true;
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
      $this->b_drs_sql        = true;
      $this->b_drs_tca        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'BrowserMaps')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_map        = true;
//      $this->b_drs_perform    = true;
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
    if( $this->arr_extConf['drs_mode'] == 'cObj->data' )
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_cObjData   = true;
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
        // #33336, 130529, dwildt, 1+
      $this->b_drs_filter     = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Filter and Category Menu')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_cObjData   = true;
      $this->b_drs_devTodo    = true;
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
    if ($this->arr_extConf['drs_mode'] == 'Marker')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_marker     = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Navigation')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_navi       = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if ($this->arr_extConf['drs_mode'] == 'Performance')
    {
      $this->b_drs_perform    = true;
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
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
    if( $this->arr_extConf['drs_mode'] == 'SQL development' )
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      //$this->b_drs_perform    = true;
      $this->b_drs_sql        = true;
      //$this->b_drs_tca        = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if( $this->arr_extConf['drs_mode'] == 'Statistics' )
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
    if( $this->arr_extConf['drs_mode'] == ':TODO: for Development' )
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_devTodo    = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
    if( $this->arr_extConf['drs_mode'] == 'Warnings and errors' )
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      t3lib_div::devlog('[INFO/DRS] DRS - Development Reporting System:<br />'.$this->arr_extConf['drs_mode'], $this->extKey, 0);
    }
      // Set the DRS mode

  }

/**
 * init_typoscriptVersion( ):
 *
 * @return    void
 * @version 4.5.7
 * @since   4.5.7
 */
  private function init_typoscriptVersion( )
  {
      // RETURN : typoscriptVersion is set
    if( $this->typoscriptVersion !== null )
    {
      return;
    }
      // RETURN : typoscriptVersion is set
    
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', $this->conf['version'] );
    $version = ( ( int ) $main ) * 1000000;
    $version = $version + ( ( int ) $sub ) * 1000;
    $version = $version + ( ( int ) $bugfix ) * 1;
    $this->typoscriptVersion = $version;
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

    if( $this->b_drs_info ) 
    {
      $prompt = 'TypoScript version is ' . $this->conf['version'] . ' (internal ' . $version . ')';
      t3lib_div::devLog('[INFO/TYPOSCRIPT] ' . $prompt, $this->extKey, 0 );
    }

  }

/**
 * init_typo3version( ): Get the current TYPO3 version, move it to an integer
 *                      and set the global $bool_typo3_43
 *                      This method is independent from
 *                        * t3lib_div::int_from_ver (upto 4.7)
 *                        * t3lib_utility_VersionNumber::convertVersionNumberToInteger (from 4.7)
 *
 * @return    void
 * @version 4.5.7
 * @since   2.0.0
 */
  private function init_typo3version( )
  {
      // #43108, 121212, dwildt, +
      // RETURN : typo3Version is set
    if( $this->typo3Version !== null )
    {
      return;
    }
      // RETURN : typo3Version is set
    
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
    $version = ( ( int ) $main ) * 1000000;
    $version = $version + ( ( int ) $sub ) * 1000;
    $version = $version + ( ( int ) $bugfix ) * 1;
    $this->typo3Version = $version;
      // Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

    if( $this->typo3Version < 3000000 ) 
    {
      $prompt = '<h1>ERROR</h1>
        <h2>Unproper TYPO3 version</h2>
        <ul>
          <li>
            TYPO3 version is smaller than 3.0.0
          </li>
          <li>
            constant TYPO3_version: ' . TYPO3_version . '
          </li>
          <li>
            integer $this->typo3Version: ' . ( int ) $this->typo3Version . '
          </li>
        </ul>
          ';
      die ( $prompt );
    }

      // Set the global $bool_typo3_43
    if( $this->typo3Version >= 4003000 )
    {
      $this->bool_typo3_43 = true;
    }
    if( $this->typo3Version < 4003000 )
    {
      $this->bool_typo3_43 = false;
    }
      // Set the global $bool_typo3_43
      // #43108, 121212, dwildt, +    
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
 * @return    void
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
  * @param    integer        $debugTrailLevel  : level for the debug trail
  * @param    string        $line             : current line in calling method
  * @param    string        $prompt           : The prompt for devlog.
  * @return    void
  * @version 4.1.13
  * @since   0.0.1
  */
  public function timeTracking_log( $debugTrailLevel, $prompt )
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

    $debugTrail = $this->drs_debugTrail( $debugTrailLevel );

    // Prompt the current time
//    $prompt = '[' . ( $endTime - $this->tt_startTime ) . ' ms] ' . $method . '(' . $line . '): ' . $prompt;
//    t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->extKey, 0 );
//    t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->extKey, 0 );
    $mSec   = sprintf("%05d", ( $endTime - $this->tt_startTime ) );
    $prompt = $mSec . ' ms ### ' . 
              $debugTrail['prompt'] . ': ' . $prompt;
    t3lib_div::devLog( $prompt, $this->extKey, 0 );

    $timeOfPrevProcess = $endTime - $this->tt_prevEndTime;
    
    switch( true )
    {
      case( $timeOfPrevProcess >= 10000 ):
        $this->tt_prevPrompt = 3;
        $prompt = 'Previous process needs more than 10 sec (' . $timeOfPrevProcess / 1000 . ' sec)';
        t3lib_div::devLog('[WARN/PERFORMANCE] ' . $prompt, $this->extKey, 3 );
        break;
      case( $timeOfPrevProcess >= 250 ):
        $this->tt_prevPrompt = 2;
        $prompt = 'Previous process needs more than 0.25 sec (' . $timeOfPrevProcess / 1000 . ' sec)';
        t3lib_div::devLog('[WARN/PERFORMANCE] ' . $prompt, $this->extKey, 2 );
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
  * @param    integer        $debugTrailLevel  : level for the debug trail
  * @param    string        $prompt: The prompt for devlog.
  * @return    void
  * @version 3.9.8
  * @since   3.9.8
  */
  public function timeTracking_prompt( $debugTrailLevel, $prompt )
  {
    $debugTrail = $this->drs_debugTrail( $debugTrailLevel );

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

    $prompt = 'Details about previous process: ' . $prompt . ' (' . $debugTrail['prompt'] . ')';
    t3lib_div::devLog('[INFO/PERFORMANCE] ' . $prompt, $this->extKey, $this->tt_prevPrompt );
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
 * @return    array        With element template
 * @version 3.9.8
 * @since   1.0.0
 */
  private function getTemplate( )
  {
    $cObj = $this->cObj;
    $conf = $this->conf;

    $view       = $this->view;
    $mode       = $this->piVar_mode;
    $viewWiDot  = $view.'.';

    $arr_return = array( );
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