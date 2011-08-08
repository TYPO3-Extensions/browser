<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 - 2011 Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * @version 3.6.5
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   90: class tx_browser_pi1 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  326:     function main($content, $conf)
 *
 *              SECTION: DRS - Development Reporting System
 *  956:     function init_drs()
 *
 *              SECTION: Classes
 * 1222:     function require_classes()
 * 1326:     function init_classVars()
 *
 *              SECTION: Template
 * 1465:     function getTemplate($cObj, $conf, $arr_data)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1 extends tslib_pibase {

  var $prefixId = 'tx_browser_pi1';
  // Same as class name
  var $scriptRelPath = 'pi1/class.tx_browser_pi1.php';
  // Path to this script relative to the extension dir.
  var $extKey = 'browser';
  // The extension key.
  var $pi_checkCHash = true;

  var $str_developer_name     = 'Dirk Wildt';
  var $str_developer_mail     = 'wildt[at]die-netzmacher.de';
  var $str_developer_phone    = '+49 361 21655226';
  var $str_developer_company  = 'Die Netzmacher';
  var $str_developer_web      = 'http://die-netzmacher.de';
  var $str_developer_typo3ext = 'http://typo3.org/extensions/repository/view/browser/current/';
  var $str_developer_lang     = 'german, english';
  // [Boolean] Set by init_drs()
  var $developer_contact      = false;
  // [String, csv] Csv list of IP-addresses of the developer / integrator
  //               Needed for reports in the frontend
  var $str_developer_csvIp    = null;

  var $arr_extConf            = array();
  // Array out of the extConf file

  var $arrModeItems           = array();
  // Array for the mode selector


  var $tsStrftime;
  // The human readable format for timestamps out of the TS
  var $view;
  // [String] The current view type: list || single
  // #9659, 101010 fsander
  var $segment;
  // [Array] contains for each segment wether it should be shown or not (needed for AJAX)
  var $lang;
  // [Object] System language Object. $lang->lang cotain the current language.
  var $boolFirstVisit;
  // [Boolean] Is it the first call of the plugin?

  var $pidList;
  // [String/CSV] List with pids of the records of the local table
  var $singlePid;
  // [Integer] Uid of the current singlePid. Is set in list view only.
  var $csvSelect;
  // [String/CSV] List of fields for the SQL select query
  var $csvSelectWoFunc;
  // [String/CSV] List of fields for the SQL select query, cleaned up from any function
  var $csvOrderBy;  // 090628, depricated. See $conf_sql
  // [String/CSV] List of fields for the SQL query orderBy
  var $conf_sql;
  // [Array] Array with the SQL query parts from the TypoScript.
  //         LF and CR are cleaned up.
  //         tableFields and functions got an alias
  //         Elements
  //         - select:   select clause
  //         - search:   list with fields from db, in which search is enabled
  //         - groupBy:  group-by-clause NOT FOR SQL but for php multisort and consolidation
  //         - orderBy:  order-by-clause NOT FOR SQL but for php multisort
  //         - andWhere: and where clause
  var $arr_andWhereFilter;
  // [Array] Array with andWhere statements generated by the Filter class
  var $arrLinkToSingle = array();
  // [Array] Array with fieldnames, which should wrapped as a link to a single view


  var $piVar_mode   = false;
  // [Integer] The current mode (view). We need $piVar_mode, if there is only one view. Then we don't want the
  // piVars[mode] because of a nice real url path
  var $piVar_azTab  = false;
  // [String] The current tab of the A-Z-Browser. We need $piVar_azTab, if the current tab is the default tab. Then we don't want the
  // piVars[azTab] because of a nice real url path
  var $piVar_sword  = false;
  // [String] The current piVar Sword in secure mode
  var $piVar_alias_showUid  = false;
  // [String] Alias of the showUid

  var $template;
  // [String] Current HTML Template
  var $str_template_raw;
  // [String] Raw HTML Template
  var $str_wrap_grouptitle;
  // [String] The wrap for the group title in listr views (i.e <h2>|</h2)

  var $uploadFolder;
  // [String] Path to an uplod folder

  var $elements;
  // [Array] The elements of the current SQL row
  var $rows;
  // [Array] The rows of the SQL result
  var $uids_of_all_rows;
  // [Array] Uids of all rows (after consolidation but before limitation)

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


  // processing views
  var $boolFirstRow = true;
  // [Boolean] true if current row is the first row, false if not; Don't change the value!
  var $lDisplayType;
  // [String] Possible values: displaySingle || displayList.
  var $lDisplay;
  // [Array] Local array with the configuration of displaySingle.display or displayList.display


  // Relation building
  var $recordTS;
  // The local or global record array from the TS
  var $arrSelectRow = array();
  // [Array] Array with the field names for the SQL select statement, but without uid and some other special cases
  var $localTable = '';
  // [String] The local table out of TS record.uid
  var $arrLocalTable = '';
  // [Array] Array with the table.uid and table.pid of the localtable. Syntax: array[uid] = table.field, array[pid] = table.field
  var $arr_realTables_arrFields;
  // [Array] Array with tables for an autmatic relation building, Syntax [table][] = field.
  var $arrConsolidate;
  // [Array] Array with consolidating information. Syntax [addedTableFields][] = table.field.
  var $arr_realTables_localized;
  // [Array] Array with localized tables
  var $arr_realTables_notLocalized;
  // [Array] Array with tables, which aren't localized
  var $arr_children_to_devide;
  // [Array] Array with the tables.fields of children records, which have to devide while stdWrap


  // Booleans for DRS - Development Reporting System
  var $b_drs_all          = false;
  var $b_drs_error        = false;
  var $b_drs_warn         = false;
  var $b_drs_info         = false;
  var $b_drs_browser      = false;
  var $b_drs_discover     = false;
  var $b_drs_filter       = false;
  var $b_drs_flexform     = false;
  var $b_drs_javascript   = false;
  var $b_drs_locallang    = false;
  var $b_drs_perform      = false;
  var $b_drs_realurl      = false;
  var $b_drs_seo          = false;
  var $b_drs_socialmedia  = false;
  var $b_drs_sql          = false;
  var $b_drs_templating   = false;
  var $b_drs_tca          = false;
  var $b_drs_tsUpdate     = false;

  // DRS properties
  var $i_drs_max_sql_result_len = 100;
  // Value will be overriden, if there is a value in $conf

  // Development
  var $boolCache = true;
  // Use cache: FALSE || TRUE; If you develope this extension, it can be helpfull to set this var on FALSE (no cache)
  var $bool_typo3_43 = false;
  // [Boolean] If true, the current version is TYPO3 4.3 at least
  var $bool_dontUseDRS = false;
  // [Boolean] If true, the current plugin won't be report any log to the DRS. It is configured by the plugin sheet [development]
  var $bool_debugJSS = false;
  // [Boolean] If true, Javascript is running in debugging mode. It is configured by the plugin sheet [development]


  // Auto Discover
  var $boolFirstElement = true;
  // TRUE, if the current element is the first in the row
  var $boolFirstTimeAutodiscover = true;
  // TRUE, if method autodiscConfig is used the first time. Don't change the value TRUE!
  var $boolArrHandleAsProcessed = false;
  // FALSE, if array arrHandleAs isn't processed completly. Don't change the value FALSE!
  var $confAutodiscover;
  // Array with the autodiscover configuration
  var $arrDontDiscoverFields;
  // Array with the names of that fields, which shouldn't wrapped automatically
  var $arrHandleAs;
  // Array with detected fields for arrHandleAs automatically
  var $TShandleAs;
  // Array with fields in the array handleAs in the TS


  // SQL configuration
  var $b_sql_manual = false;
  // FALSE: User defined a select statement only, Browser should build the full query automatically
  // TRUE: User has defined a SELECT, FROM, WHERE and maybe JOINS. Browser should use a manual configured SQL query
























  /***********************************************
   *
   * Main Process
   *
   **********************************************/




  /**
 * Main method of your PlugIn
 *
 * @param string    $content: The content of the PlugIn
 * @param array   $conf: The PlugIn Configuration
 * @return  string    The content that should be displayed on the website
 * 
 * @version 3.6.2
 
 */
  function main($content, $conf) 
  {
    $this->conf = $conf;

    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();



      ////////////////////////////////////////////////////////////////////
      //
      // TYPO3 Version

    $str_version = TYPO3_version;
    if(!$str_version)
    {
      $str_version = '4.2.9';
    }
    $int_version = t3lib_div::int_from_ver($str_version);
    if($int_version >= 4003000)
    {
      $this->bool_typo3_43 = true;
    }
    if($int_version < 4003000)
    {
      $this->bool_typo3_43 = false;
    }
      // TYPO3 Version



      ////////////////////////////////////////////////////////////////////
      //
      // Timetracking

    require_once(PATH_t3lib.'class.t3lib_timetrack.php');
    $this->TT = new t3lib_timeTrack;
    $this->TT->start();
    if($this->bool_typo3_43)
    {
      $this->startTime = $this->TT->getDifferenceToStarttime();
    }
    if(!$this->bool_typo3_43)
    {
      $this->startTime = $this->TT->mtime();
    }
      // Timetracking



      //////////////////////////////////////////////////////////////////////
      //
      // Get the values from the localconf.php file

    $this->arr_extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
      // Get the values from the localconf.php file



      //////////////////////////////////////////////////////////////////////
      //
      // Init DRS - Development Reporting System

    $this->init_drs();
    if ($this->b_drs_perform)
    {
      t3lib_div::devlog('[INFO/PERFORMANCE] START', $this->extKey, 0);
    }
      // Init DRS - Development Reporting System



      //////////////////////////////////////////////////////////////////////
      //
      // Init current IP

    $this->str_developer_csvIp = $this->arr_extConf['updateWizardAllowedIPs'];
      // Init current IP



      //////////////////////////////////////////////////////////////////////
      //
      // Init Update Check

      // dwildt, 101216, #11523
    if($this->arr_extConf['updateWizardEnable'])
    {
      $pos = strpos($this->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
      if (!($pos === false))
      {
        require_once(PATH_typo3conf.'ext/'.$this->extKey.'/pi2/class.tx_browser_pi2.php');
        // Class with methods for Update Checking
        $this->objPi2 = new tx_browser_pi2($this);
        $html_updateCheck = $this->objPi2->main($content, $conf, $this);
      }
    }
      // Init Update Check



      //////////////////////////////////////////////////////////////////////
      //
      // Get the global TCA

      /* BACKGROUND : t3lib_div::loadTCA($table) loads for the frontend
       * only 'ctrl' and 'feInterface' parts.
       */
    $GLOBALS['TSFE']->includeTCA();
      // Get the global TCA



      //////////////////////////////////////////////////////////////////////
      //
      // Require and init helper classes

    $this->require_classes();
      // Require and init helper classes



      //////////////////////////////////////////////////////////////////////
      //
      // Get pid list

    if (strstr($this->cObj->currentRecord, 'tt_content'))
    {
      $this->conf['pidList']    = $this->cObj->data['pages'];
      $this->conf['recursive']  = $this->cObj->data['recursive'];
      $this->pidList = $this->pi_getPidList($this->conf['pidList'], $this->conf['recursive']);
    }
      // Get pid list


      //////////////////////////////////////////////////////////////////////
      //
      // Make cObj instance

    $this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
      // Make cObj instance



      //////////////////////////////////////////////////////////////////////
      //
      // Clean up views (multiple plugins)

      // #11981, 110106, dwildt
    $conf       = $this->objZz->cleanup_views($conf);
    $this->conf = $conf;
      // Clean up views (multiple plugins)



      //////////////////////////////////////////////////////////////////////
      //
      // Get Configuration out of the Plugin (Flexform) but [Templating]

    $this->objFlexform->main();
    $conf = $this->conf;
      // Get Configuration out of the Plugin (Flexform) but [Templating]



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire piVars

      // Allocates values to $this->piVars, $this->pi_isOnlyFields and $this->views
    $this->objZz->prepairePiVars();
      // Prepaire piVars



      //////////////////////////////////////////////////////////////////////
      //
      // Get Configuration out of the Plugin (Flexform) devider [Templating]

    $this->objFlexform->sheet_templating();
      // Get Configuration out of the Plugin (Flexform) devider [Templating]



      //////////////////////////////////////////////////////////////////////
      //
      // Set the class variables

    $this->init_classVars();
      // Set the class variables



      //////////////////////////////////////////////////////////////////////
      //
      // Replace TSFE markers

    $this->conf = $this->objZz->substitute_t3globals_recurs($this->conf);
      // Replace TSFE markers



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->b_drs_perform)
    {
      if($this->bool_typo3_43)
      {
        $endTime = $this->TT->getDifferenceToStarttime();
      }
      if(!$this->bool_typo3_43)
      {
        $endTime = $this->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] substitute_t3globals_recurs: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////////////
      //
      // Get the HTML template

    $arr_data['view'] = $this->view;
    $arr_data['mode'] = $this->piVar_mode;
    $arr_result = $this->getTemplate($this->cObj, $this->conf, $arr_data);
    unset($arr_data);

    if ($arr_result['error']['status'])
    {
      if ($this->b_drs_error)
      {
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 3);
        if($this->bool_typo3_43)
        {
          $endTime = $this->TT->getDifferenceToStarttime();
        }
        if(!$this->bool_typo3_43)
        {
          $endTime = $this->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] END: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
      }
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      // return $this->pi_wrapInBaseClass($prompt);
      return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
    }
    $this->str_template_raw = $arr_result['data']['template'];
    unset($arr_result);
      // Get the HTML template



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire modeSelector

    $arr_result = $this->objNavi->prepaireModeSelector();
    if ($arr_result['error']['status']) {
      $prompt = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $this->objWrapper->wrapInBaseIdClass($prompt);
      //return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
    }
    $this->arrModeItems = $arr_result['data'];
    unset($arr_result);
      // Prepaire modeSelector



      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire format for time values

    $this->tsStrftime = $this->objZz->setTsStrftime();
      // Prepaire format for time values



      //////////////////////////////////////////////////////////////////////
      //
      // Get used tables from the SQL query parts out of the Typoscript

    $this->arr_realTables_arrFields = $this->objTyposcript->fetch_realTables_arrFields();
      // Get used tables from the SQL query parts out of the Typoscript



      //////////////////////////////////////////////////////////////////////
      //
      // Get the local table uid field name and pid field name

    $this->arrLocalTable = $this->objTyposcript->fetch_localTable();
    if (!is_array($this->arrLocalTable))
    {
      if ($this->b_drs_error)
      {
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 3);
        if($this->bool_typo3_43)
        {
          $endTime = $this->TT->getDifferenceToStarttime();
        }
        if(!$this->bool_typo3_43)
        {
          $endTime = $this->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] END: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
      }
      $prompt = '<h1 style="color:red;">'.$this->pi_getLL('error_readlog_h1').'</h1>
           <p style="color:red;font-weight:bold;">'.$this->pi_getLL('error_table_no').'</p>';
      return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
    }
      // Get the local table uid field name and pid name



      //////////////////////////////////////////////////////////////////////
      //
      // Get the local table

    list($this->localTable, $field) = explode('.', $this->arrLocalTable['uid']);
      // Get the local table



      //////////////////////////////////////////////////////////////////////
      //
      // Add missing uids and pids

    $arr_result = $this->objConsolidate->addUidAndPid();
    $this->arrConsolidate['addedTableFields'] = $arr_result['data']['consolidate']['addedTableFields'];
    $this->arr_realTables_arrFields           = $arr_result['data']['arrFetchedTables'];
    unset($arr_result);
      // Add missing uids



      //////////////////////////////////////////////////////////////////////
      //
      // Set the manual SQL mode or the auto SQL mode

      // Process the query building in case of a manual configuration with SELECT, FROM and WHERE and maybe JOINS
    $arr_result = $this->objSqlMan->check_typoscript_query_parts();
      // RETURN error
    if ($arr_result['error']['status'])
    {
      $template = $arr_result['error']['header'].$arr_result['error']['prompt'];
      return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($template);
    }
      // RETURN error

    // ##############################################################################################################################
    //unset($arr_result); // :todo:
    // ##############################################################################################################################

      // Auto SQL mode: The user configured only a select statement
    if(!$arr_result)
    {
      $this->b_sql_manual = false;
      if ($this->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/DRS] User configured in TypoScript a SELECT statement only:<br />
          SQL auto mode.', $this->extKey, 0);
      }
    }
      // Auto SQL mode: The user configured only a select statement

      // Manual SQL mode: The user configured more than a select statement
    if($arr_result)
    {
        // The user configured a whole SQL query
      $this->b_sql_manual = true;
      if ($this->b_drs_sql)
      {
        t3lib_div::devLog('[INFO/DRS] User configured in TypoScript a whole SQL query:<br />
          SQL manual mode.', $this->extKey, 0);
      }
    }
      // Manual SQL mode: The user configured more than a select statement
      // Set the manual SQL mode or the auto SQL mode



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->b_drs_perform) {
      if($this->bool_typo3_43)
      {
        $endTime = $this->TT->getDifferenceToStarttime();
      }
      if(!$this->bool_typo3_43)
      {
        $endTime = $this->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] Before view processing: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////////////
      //
      // Process the views

    switch($this->view)
    {
      case('list'):
        $str_template_completed = $this->objViews->listView($this->str_template_raw);
        break;
      case('single'):
        $str_template_completed = $this->objViews->singleView($this->str_template_raw);
        break;
    }
      // Process the views



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->b_drs_perform)
    {
      if($this->bool_typo3_43)
      {
        $endTime = $this->TT->getDifferenceToStarttime();
      }
      if(!$this->bool_typo3_43)
      {
        $endTime = $this->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] After view processing: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////////////
      //
      // Error, if the completed template is an array and has the element error.status

    if(is_array($str_template_completed))
    {
      if($str_template_completed['error']['status'] == true)
      {
        $prompt = $str_template_completed['error']['header'].$str_template_completed['error']['prompt'];
        // return $this->objWrapper->wrapInBaseIdClass($prompt);
        return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
      }
      else
      {
        $prompt = '<h1 style="color:red;">'.$this->pi_getLL('error_h1').'</h1>
            <p style="color:red;font-weight:bold;">'.$this->pi_getLL('error_template_array').'</p>';
        // return $this->objWrapper->wrapInBaseIdClass($prompt);
        return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
      }
    }
      // Error, if the completed template is an array and has the element error.status



      //////////////////////////////////////////////////////////////////////
      //
      // Error, if the completed template is the raw template

    if($this->str_template_raw == $str_template_completed) {
      if ($this->b_drs_error)
      {
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 3);
        if($this->bool_typo3_43)
        {
          $endTime = $this->TT->getDifferenceToStarttime();
        }
        if(!$this->bool_typo3_43)
        {
          $endTime = $this->TT->mtime();
        }
        t3lib_div::devLog('[INFO/PERFORMANCE] END: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
      }
      $prompt = '<h1 style="color:red;">'.$this->pi_getLL('error_h1').'</h1>
          <p style="color:red;font-weight:bold;">'.$this->pi_getLL('error_template_render').'</p>';
      // return $this->objWrapper->wrapInBaseIdClass($prompt);
      return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($prompt);
    }
      // Error, if the completed template is the raw template



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Performance

    if ($this->b_drs_perform) {
      if($this->bool_typo3_43)
      {
        $endTime = $this->TT->getDifferenceToStarttime();
      }
      if(!$this->bool_typo3_43)
      {
        $endTime = $this->TT->mtime();
      }
      t3lib_div::devLog('[INFO/PERFORMANCE] END: '. ($endTime - $this->startTime).' ms', $this->extKey, 0);
    }
      // DRS - Performance



      //////////////////////////////////////////////////////////////////////
      //
      // XML/RSS: return the result (XML string) without wrapInBaseClass
  
    if(substr($str_template_completed, 0, strlen('<?xml')) == '<?xml')
    {
      return trim($str_template_completed);
    }
      // XML/RSS: return the result (XML string) without wrapInBaseClass



      // 110804, dwildt
    $this->objJss->addCssFiles();



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX

      // #9659, 101010 fsander
    if (!$this->objFlexform->bool_ajax_enabled)
    {
      if ($this->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/JSS] AJAX is disabled.', $this->extKey, 0);
        t3lib_div::devlog('[HELP/JSS] Change it: configure the browser flexform [AJAX].', $this->extKey, 1);
      }
    }

    $bool_load_jQuery = false;
    if ($this->objFlexform->bool_ajax_enabled)
    {
      if ($this->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/JSS] AJAX is enabled.', $this->extKey, 0);
        t3lib_div::devlog('[INFO/JSS] jQuery will be loaded.', $this->extKey, 0);
      }
      $bool_load_jQuery = true;
    }  
    if ($this->objFlexform->bool_jquery_ui)
    {
      if ($this->b_drs_javascript)
      {
        t3lib_div::devlog('[INFO/JSS] jQuery UI should included.', $this->extKey, 0);
        t3lib_div::devlog('[INFO/JSS] jQuery will be loaded.', $this->extKey, 0);
      }
      $bool_load_jQuery = true;
    }  

    if($bool_load_jQuery)
    {
        // Adding jQuery
      $bool_success_jQuery = $this->objJss->load_jQuery();
      if($bool_success_jQuery)
      {
        // Wrap the template with a div with AJAX identifiers
        $str_template_completed = $this->objJss->wrap_ajax_div($str_template_completed);
      }
      if(!$bool_success_jQuery)
      {
        if ($this->b_drs_warn)
        {
          t3lib_div::devlog('[WARN/JSS] AJAX JSS file is not included because of missing jQuery.', $this->extKey, 2);
        }
      }
    }
      // #9659, 101010 fsander
      // AJAX



      // 110804, dwildt
    $this->objJss->addJssFiles();



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX: Remove from single view the no AJAX content

    if($this->segment['wrap_piBase'] == false)
    {
        // Do we have an AJAX marker in the template
      $pos = strpos($str_template_completed, '###AREA_FOR_AJAX_LIST');
      if (!($pos === false))
      {
        $str_template_part_01   = $this->cObj->getSubpart($str_template_completed, '###AREA_FOR_AJAX_LIST_01###');
        $str_template_part_02   = $this->cObj->getSubpart($str_template_completed, '###AREA_FOR_AJAX_LIST_02###');
        $str_template_part_03   = $this->cObj->getSubpart($str_template_completed, '###AREA_FOR_AJAX_LIST_03###');
        $str_template_completed = $str_template_part_01.$str_template_part_02.$str_template_part_03;
      }
    }
      // AJAX: Remove from single view the no AJAX content



      //////////////////////////////////////////////////////////////////////
      //
      // Replace left over markers

      // 110801, dwildt, #28657
    $str_template_completed = $this->objMarker->replace_left_over($str_template_completed);
      // Replace left over markers



      //////////////////////////////////////////////////////////////////////
      //
      // AJAX: return the result (HTML string) without wrapInBaseClass

    if($this->segment['wrap_piBase'] == false)
    {
      return trim($str_template_completed);
    }
      // AJAX: return the result (HTML string) without wrapInBaseClass



      // 12367, dwildt, 110310
    switch($this->objFlexform->bool_wrapInBaseClass)
    {
      case(false):
        return $html_updateCheck.$str_template_completed;
        break;
      case(true):
      default:
        return $html_updateCheck.$this->objWrapper->wrapInBaseIdClass($str_template_completed);
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
 * @return  void
 */
  function init_drs()
  {

      //////////////////////////////////////////////////////////////////////
      //
      // Prepaire the developer contact prompt

    $this->developer_contact =
        'company: '.  $this->str_developer_company.'<br />'.
        'name: '.     $this->str_developer_name   .'<br />'.
        'mail: <a href="mailto:'.$this->str_developer_mail.'" title="Send a mail">'.$this->str_developer_mail.'</a><br />'.
        'web: <a href="'.$this->str_developer_web.'" title="Website" target="_blank">'.$this->str_developer_web.'</a><br />'.
        'phone: '.    $this->str_developer_phone  .'<br />'.
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
      // Initiate the DRS mode

    $this->b_drs_all          = false;
    $this->b_drs_error        = false;
    $this->b_drs_warn         = false;
    $this->b_drs_info         = false;
    $this->b_drs_browser      = false;
    $this->b_drs_cal          = false;
    $this->b_drs_discover     = false;
    $this->b_drs_filter       = false;
    $this->b_drs_flexform     = false;
    $this->b_drs_hooks        = false;
    $this->b_drs_javascript   = false;
    $this->b_drs_locallang    = false;
    $this->b_drs_marker       = false;
    $this->b_drs_perform      = false;
    $this->b_drs_realurl      = false;
    $this->b_drs_search       = false;
    $this->b_drs_seo          = false;
    $this->b_drs_socialmedia  = false;
    $this->b_drs_sql          = false;
    $this->b_drs_tca          = false;
    $this->b_drs_templating   = false;
    $this->b_drs_tsUpdate     = false;
    $this->b_drs_ttc          = false;
      // Initiate the DRS mode



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
      $this->b_drs_filter       = true;
      $this->b_drs_flexform     = true;
      $this->b_drs_hooks        = true;
      $this->b_drs_javascript   = true;
      $this->b_drs_locallang    = true;
      $this->b_drs_marker       = true;
      $this->b_drs_perform      = true;
      $this->b_drs_realurl      = true;
      $this->b_drs_search       = true;
      $this->b_drs_seo          = true;
      $this->b_drs_socialmedia  = true;
      $this->b_drs_sql          = true;
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
    if ($this->arr_extConf['drs_mode'] == 'Labeling Support/Localization')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_locallang  = true;
      $this->b_drs_perform    = true;
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
    if ($this->arr_extConf['drs_mode'] == 'Social media')
    {
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
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
 * @return  void
 */
  function require_classes()
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

      // Class with realurl methods
    require_once('class.tx_browser_pi1_filter.php');
    $this->objFilter = new tx_browser_pi1_filter($this);

      // #9659, 101016, dwildt
      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_javascript.php');
    $this->objJss = new tx_browser_pi1_javascript($this);

      // Class with methods for the markers
    require_once('class.tx_browser_pi1_marker.php');
    $this->objMarker = new tx_browser_pi1_marker($this);

      // Class with methods for ordering rows
    require_once('class.tx_browser_pi1_multisort.php');
    $this->objMultisort = new tx_browser_pi1_multisort($this);

      // Class with methods for the modeSelector, the pageBrowser and the a-z-browser
    require_once('class.tx_browser_pi1_navi.php');
    $this->objNavi = new tx_browser_pi1_navi($this);

      // Class with localization methods
    require_once('class.tx_browser_pi1_localization.php');
    $this->objLocalize = new tx_browser_pi1_localization($this);

      // Class with seo methods for Search Engine Optimization
    require_once('class.tx_browser_pi1_seo.php');
    $this->objSeo = new tx_browser_pi1_seo($this);

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
 * @return  boolean   FALSE
 */
  function init_classVars()
  {

      //////////////////////////////////////////////////////////////////////
      //
      // class.tx_browser_pi1_localization.php

      // [Array] The current Typoscript configuration array
    $this->objLocalize->conf      = $this->conf;
      // [Integer] The current mode (from modeselector)
    $this->objLocalize->mode      = $this->piVar_mode;
      // [String] 'list' or 'single': The current view
    $this->objLocalize->view      = $this->view;
      // [Array] The TypoScript configuration array of the current view
    $this->objLocalize->conf_view = $this->conf['views.'][$this->view.'.'][$this->piVar_mode.'.'];
      // [String] TypoScript path to the current view. I.e. views.single.1
    $this->objLocalize->conf_path = 'views.'.$this->view.'.'.$this->piVar_mode.'.';
    $this->objLocalize->init_typoscript();



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
   * Template
   *
   **********************************************/



  /**
 * Get the value for the var $template
 *
 * @param array   Global cObj
 * @param array   TS configuration array
 * @param array   Input array with the elements view and mode
 * @param integer   The current view
 * @return  array   template
 */
  function getTemplate($cObj, $conf, $arr_data)
  {

    $view = $arr_data['view'];
    $mode = $arr_data['mode'];

    $viewWiDot = $view.'.';

    $arr_return = array();
    $arr_return['error']['status']  = false;
    $arr_return['data']['template'] = false;


      //////////////////////////////////////////////////////////////////////
      //
      // Catch the template

    if (!empty($conf['views.'][$viewWiDot][$mode.'.']['template.']['file']))
    {
        // Local HTML Template
      $template_path = $conf['views.'][$viewWiDot][$mode.'.']['template.']['file'];
    }
    if (empty($conf['views.'][$viewWiDot][$mode.'.']['template.']['file']))
    {
        // Global HTML Template
      $template_path = $conf['template.']['file'];
    }
    $template = $cObj->fileResource($template_path);
      // Catch the template



      //////////////////////////////////////////////////////////////////////
      //
      // DRS - Development Reporting System

    if (!$template)
    {
      if ($this->b_drs_error)
      {
        t3lib_div::devLog('[ERROR/DRS] There is no template file. Path: '.$template_path, $this->extKey, 3);
        t3lib_div::devLog('[ERROR/DRS] ABORTED', $this->extKey, 0);
      }
      $str_header  = '<h1 style="color:red;">'.$this->pi_getLL('error_readlog_h1').'</h1>';
      $str_prompt  = '<p style="color:red;font-weight:bold;">'.$this->pi_getLL('error_template_no').'</p>';
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