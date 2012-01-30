<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2012 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
* The class tx_browser_pi1_tca bundles methods for evaluating the TYPO3 TCA array for the extension browser
*
* @author    Dirk Wildt http://wildt.at.die-netzmacher.de
* @package    TYPO3
* @subpackage    tx_browser
* @version    3.9.6
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_browser_pi1_tca
 *   69:     function __construct($parentObj)
 *
 *              SECTION: Wrap fields automatically by autodiscover
 *   94:     function setArrHandleAs()
 *  190:     function autodiscConfig()
 *  282:     function autodiscTCA($tableField)
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_tca
{
  var $arr_select;
  // Array with the fields of the SQL result
  var $arr_orderBy;
  // Array with fields from orderBy from TS
  var $arr_rmFields;
  // Array with fields from functions.clean_up.csvTableFields from TS


  
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



   /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;
  }








  /***********************************************
   *
   * Wrap fields automatically by autodiscover
   *
   **********************************************/



  /**
 * Set the global array $arrHandleAs
 *
 * @return	void
 */
  function setArrHandleAs() {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view       = $this->pObj->view;
    $viewWiDot  = $view.'.';
    $conf_view  = $conf['views.'][$viewWiDot][$mode.'.'];


    /////////////////////////////////////////
    //
    // Is the array $arrHandleAs processed?

    if(!$this->pObj->boolArrHandleAsProcessed)
    {
      $this->pObj->boolArrHandleAsProcessed = true;
    }
    else
    {
      if ($this->pObj->b_drs_discover)
      {
        t3lib_div::devlog('[INFO/DISCOVER] The global array arrHandleAs is processed in a loop ago. Nothing todo.', $this->pObj->extKey, 0);
      }
      return true;
    }


    /////////////////////////////////////////
    //
    // Get the autodiscover configuration

    $this->autodiscConfig();


    /////////////////////////////////////////
    //
    // Process each field in the select query

    $this->pObj->boolFirstElement = true;

    $b_error = false;
    if ($this->pObj->csvSelect == '')
    {
      $this->pObj->csvSelect = $conf_view['select'];
      $this->pObj->csvSelect = $this->pObj->objZz->cleanUp_lfCr_doubleSpace($this->pObj->csvSelect);
      if ($this->pObj->csvSelect == '')
      {
        if ($this->pObj->b_drs_error)
        {
          t3lib_div::devlog('[ERROR/SQL] views.'.$viewWiDot.$mode.' hasn\'t any select fields.', $this->pObj->extKey, 3);
          t3lib_div::devLog('[HELP/SQL] Did you included the static template from this extensions?', $this->pObj->extKey, 1);
          $tsArray = 'plugin.'.$this->pObj->prefixId.'.views.'.$viewWiDot.$mode.'.select';
          t3lib_div::devLog('[HELP/SQL] Did you configure '.$tsArray.'?', $this->pObj->extKey, 1);
          t3lib_div::devLog('[ERROR/SQL] ABORTED', $this->pObj->extKey, 3);
        }
        $b_error = true;
      }
    }

    if (!$b_error)
    {
      $csvSelect = $this->pObj->csvSelect;
      if (is_array($conf_view['select.']['deal_as_table.']))
      {
        // Replace each SQL function which its alias
        foreach ($conf_view['select.']['deal_as_table.'] as $arr_dealastable)
        {
          $str_statement  = $arr_dealastable['statement'];
          $str_aliasTable = $arr_dealastable['alias'];
          // I.e.: $conf_sql['select'] = CONCAT(tx_bzdstaffdirectory_persons.title, ' ', tx_bzdstaffdirectory_persons.first_name, ' ', tx_bzdstaffdirectory_persons.last_name), tx_bzdstaffdirectory_groups.group_name
          $csvSelect = str_replace($str_statement, $str_aliasTable, $csvSelect);
          // I.e.: $conf_sql['select'] = tx_bzdstaffdirectory_persons.last_name, tx_bzdstaffdirectory_groups.group_name
        }
      }
      $arrColumns = $this->pObj->objZz->getCSVasArray($csvSelect);
      $arrColumns = $this->pObj->objSqlFun->clean_up_as_and_alias($arrColumns);
      // Get the selected fields out of the TS
      foreach ($arrColumns as $columnValue)
      {
        $this->autodiscTCA($columnValue); //:TODO:
      }
      $this->pObj->boolFirstElement = false;
    }
  }






  /**
 * Get the configuration for autodiscover from the TS, fill up the global arrays $arrAutodiscTCAitems and $arrDontDiscoverFields
 *
 * @return	void
 */
  function autodiscConfig() {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';


    /////////////////////////////////////////
    //
    // Is this the first call?

    if($this->pObj->boolFirstTimeAutodiscover) {
      $this->pObj->boolFirstTimeAutodiscover = false;
    } else {
      if ($this->pObj->b_drs_discover) {
        t3lib_div::devlog('[INFO/DISCOVER] This method autodiscConfig() was called ago. Nothing todo.', $this->pObj->extKey, 0);
      }
      return true;
    }


    /////////////////////////////////////////
    //
    // Get the local or global configuration array

    $this->pObj->confAutodiscover = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['autoconfig.']['autoDiscover.'];
    switch(is_array($autoDiscover)) {
      case(true):
        // We have a local configuration
        if ($this->pObj->b_drs_discover) {
          t3lib_div::devlog('[INFO/DISCOVER] views.'.$viewWiDot.$mode.' has a local autoDiscover array.', $this->pObj->extKey, 0);
        }
        break;
      default:
        // We have a global configuration
        if ($this->pObj->b_drs_discover) {
          t3lib_div::devlog('[INFO/DISCOVER] views.'.$viewWiDot.$mode.' hasn\'t a local autoDiscover array. It is OK.', $this->pObj->extKey, 0);
          t3lib_div::devlog('[INFO/DISCOVER] We take the global autoDiscover array.', $this->pObj->extKey, 0);
        }
        $this->pObj->confAutodiscover = $this->pObj->conf['autoconfig.']['autoDiscover.'];
        break;
    }


    /////////////////////////////////////////
    //
    // Get items with there TCA configuration

    // Array $this->pObj->confAutodiscover['items.'] has the following structure (i.e.)
    //       images = 1
    //       images.TCAlabel = 1
    //       ...

    if(!is_array($this->pObj->confAutodiscover['items.'])) {
      if ($this->pObj->b_drs_discover) {
        t3lib_div::devlog('[INFO/DISCOVER] There is no array autodiscover.items.', $this->pObj->extKey, 0);
      }
      $this->pObj->arrDontDiscoverFields = false;
      return false;
    }


    /////////////////////////////////////////
    //
    // Get field names, which shouldn't wrapped by autodiscover

    $lArrDontDiscoverFields = explode(',', $this->pObj->confAutodiscover['dontDiscoverFields.']['csvValue']);
    foreach((array) $lArrDontDiscoverFields as $tmpKey => $tmpValue) {
      $this->pObj->arrDontDiscoverFields[$tmpKey] = trim($tmpValue);
    }
    if ($this->pObj->b_drs_discover) {
      $csvFields = implode(', ', $this->pObj->arrDontDiscoverFields);
      t3lib_div::devlog('[INFO/DISCOVER] List of field names, which shouldn\'t wrapped automatically by autodiscover: '.$csvFields, $this->pObj->extKey, 0);
      t3lib_div::devlog('[HELP/DISCOVER] If you want to configure this values, please use autoDiscover.dontDiscoverFields.csvValue.', $this->pObj->extKey, 1);
    }
  }








  /**
 * Check, if comparison with all TCA properties of the field is successfull. If it is, add the table name and field name to the global array $arrHandleAs
 *
 * @param	string		table name and field name, devided by a dot. I.e.: tt_news.images
 * @return	boolean		true || false
 */
  function autodiscTCA($tableField) {

    $conf = $this->pObj->conf;
    $mode = $this->pObj->piVar_mode;

    $view = $this->pObj->view;
    $viewWiDot = $view.'.';


    $TSconfAdItems  = $this->pObj->confAutodiscover['items.'];
    $tableField     = trim($tableField);


    //////////////////////////////////////////////////////////////////////////////////
    //
    // RETURN if $tableField is empty

    if (!$tableField)
    {
      if ($this->pObj->b_drs_discover || $this->pObj->b_drs_error) {
        t3lib_div::devlog('[ERROR/DISCOVER] $tableField is empty.', $this->pObj->extKey, 3);
        t3lib_div::devlog('[HELP/DISCOVER] Please contact the developer:<br />'.$this->pObj->developer_contact, $this->pObj->extKey, 1);
        t3lib_div::devlog('[INFO/DISCOVER] TCA won\'t be investigated.', $this->pObj->extKey, 0);
      }
      $this->pObj->boolFirstElement = false;
      return false;
    }
    // RETURN if $tableField is empty



    //////////////////////////////////////////////////////////////////////////////////
    //
    // Return if table.field isn't propper

    if (!(strpos($tableField, ' ') === false))
    {
      if ($this->pObj->b_drs_discover || $this->pObj->b_drs_error) {
        t3lib_div::devlog('[WARN/DISCOVER] $tableField contents spaces!<br />
          \''.$tableField.'\'', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/DISCOVER] TCA won\'t be investigated.', $this->pObj->extKey, 0);
      }
      $this->pObj->boolFirstElement = false;
      return false;
    }
    if (!(strpos($tableField, '(') === false))
    {
      if ($this->pObj->b_drs_discover || $this->pObj->b_drs_error) {
        t3lib_div::devlog('[WARN/DISCOVER] $tableField contents \'(\'!<br />
          \''.$tableField.'\'', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/DISCOVER] TCA won\'t be investigated.', $this->pObj->extKey, 0);
      }
      $this->pObj->boolFirstElement = false;
      return false;
    }
        if (!(strpos($tableField, ')') === false))
    {
      if ($this->pObj->b_drs_discover || $this->pObj->b_drs_error) {
        t3lib_div::devlog('[WARN/DISCOVER] $tableField contents \')\'!<br />
          \''.$tableField.'\'', $this->pObj->extKey, 2);
        t3lib_div::devlog('[INFO/DISCOVER] TCA won\'t be investigated.', $this->pObj->extKey, 0);
      }
      $this->pObj->boolFirstElement = false;
      return false;
    }
    // Return if table.field isn't propper


    // Explode table name and field name from the value $tableField;
    list($table, $field) = explode('.', $tableField);
    $table = trim($table);
    $field = trim($field);


    //////////////////////////////////////////////////////////////////////////////////
    //
    // Get the names of items of the TS autodiscover.items

    foreach((array) $TSconfAdItems as $itemKey => $itemArr) {
      if(!is_array($TSconfAdItems[$itemKey])) {
        // We have the item name not the array
        if($TSconfAdItems[$itemKey]) {
          // Fill array with the item names
          $lArrAutodiscTCAitems[] = $itemKey;
        }
      }
    }
    if ($this->pObj->b_drs_discover && $this->pObj->boolFirstElement) {
      $csvItems = implode(', ', $lArrAutodiscTCAitems);
      t3lib_div::devlog('[INFO/DISCOVER] List of items, which are used for comparison with the TCA: '.$csvItems, $this->pObj->extKey, 0);
    }

    if(!is_array($lArrAutodiscTCAitems)) {
      $this->pObj->boolFirstElement = false;
      return true;
    }


    /////////////////////////////////////////
    //
    // Shouldn't the field wrapped by autodiscover?

    if(is_array($this->pObj->arrDontDiscoverFields)) {
      if(in_array($field, $this->pObj->arrDontDiscoverFields)) {
        if($this->pObj->b_drs_discover) {
          t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': field shouldn\'t wrapped automatically by autodiscover.', $this->pObj->extKey, 0);
        }
        $this->pObj->boolFirstElement = false;
        return true;
      }
    }


    /////////////////////////////////////////
    //
    // Do we have items for the comparison with TCA properties?

    // Get the array with the TCA items in the TS
    $lArrTCAitems = $lArrAutodiscTCAitems;

    if(!is_array($lArrTCAitems)) {
      if($this->pObj->b_drs_discover) {
        t3lib_div::devLog('[INFO/DISCOVER] There isn\'t any item for the comparison with TCA properties.', $this->pObj->extKey, 0);
      }
      $this->pObj->boolFirstElement = false;
      return true;
    }


    /////////////////////////////////////////
    //
    // Get the TS handleAs array

    $this->pObj->TShandleAs  = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['handleAs.'];


    /////////////////////////////////////////
    //
    // Is there a handleAs text?
    #???????????????????? unschoen Sonderfall template singletext
    $haText = $this->pObj->conf['views.'][$viewWiDot][$mode.'.']['handleAs.']['text'];
    if($haText)
    {
      $this->pObj->arrHandleAs['text'] = $haText;
    }


      /////////////////////////////////////////
      //
      // Check for each item the defined configuration vars:
      // TCAlabel, TCAconfig.type, TCAconfig.internal_type, TCAconfig.allowed

    foreach((array) $lArrTCAitems as $TCAitem) {

      // TS views.list||single.x.handleAs has priority over TS autoconf.autoDiscover.items
      if($this->pObj->TShandleAs[$TCAitem] == $tableField) {
        // The TCAitem is in TS views.list||single.x.handleAs
        $this->pObj->arrHandleAs[$TCAitem] = $this->pObj->TShandleAs[$TCAitem];
        if ($this->pObj->b_drs_discover) {
          t3lib_div::devlog('[INFO/DISCOVER] The global value handleAs['.$TCAitem.'] is overriden by TS handleAs['.$TCAitem.'].', $this->pObj->extKey, 0);
          t3lib_div::devlog('[HELP/DISCOVER] If you don\'t like it, clear the TS views.'.$viewWiDot.$mode.'.handleAs.'.$TCAitem.'.', $this->pObj->extKey, 1);
        }
      }
      $boolEveryCondition = true;

        // Load the TCA, if we don't have an table.columns array
      $this->pObj->objZz->loadTCA($table);

        // TCAlabel
      if( $boolEveryCondition && $TSconfAdItems[$TCAitem.'.']['TCAlabel'] )
      {
          // Autodiscover for this TCAitem is true
        $stringInTheTCA = $GLOBALS['TCA'][$table]['columns'][$field]['label'];
          // $stringInTheTCA in case of tt_news.image i.e.: LLL:EXT:lang/locallang_general.php:LGL.images
        if( $stringInTheTCA )
        {
            // The TCA property of the current table.field has a value
          $tmpArr = explode('.', $stringInTheTCA);
            // Explode the string and get the value behind the last dot
          $valueInTheTCAstring  = $tmpArr[count($tmpArr) - 1];
          $arrTSvalues          = $this->pObj->objZz->getCSVasArray(
                                    $TSconfAdItems[$TCAitem.'.']['TCAlabel.']['csvValue']
                                  );
            // Get the values from the TS for comparison
          if( in_array( $valueInTheTCAstring, $arrTSvalues ) )
          {
            $localTableOnly     = $this->pObj->objZz->getCSVasArray( $TSconfAdItems[$TCAitem.'.']['localTableOnly'] );
            $boolEveryCondition = true;
              // true: Check the next TCA item
            if( $localTableOnly && ( $TCAitem == 'title' ) )
            {
                // Should title wrapped by autodiscover only if table is the local table?
              if( $table != $this->pObj->localTable )
              {
                  // table isn't the local table?
                $boolEveryCondition = false;
                  // false: Don't check the next TCA item
              }
            }
            if( $this->pObj->b_drs_discover )
            {
              if( $boolEveryCondition )
              {
                $prompt = $tableField . ': Property TCA[\'label\'] matchs TS item \'' . $TCAitem . '\'.';
                t3lib_div::devLog('[INFO/DISCOVER] ' . $prompt , $this->pObj->extKey, -1);
              }
              else
              {
                $prompt = $tableField . ' isn\'t from local table \'' . $this->pObj->localTable . '\'.';
                t3lib_div::devLog('[INFO/DISCOVER] ' . $prompt, $this->pObj->extKey, 0);
              }
            }
          }
          else
          {
            $boolEveryCondition = false;
              // false: Don't check the next TCA item
            if( $this->pObj->b_drs_discover )
            {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'label\'] doesn\'t match TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
        }
        else
        {
          $boolEveryCondition = false;
            // false: Don't check the next TCA item
          if( $this->pObj->b_drs_discover )
          {
            t3lib_div::devLog('[WARN/DISCOVER] '.$table.'.'.$field.' hasn\'t any label in the TCA. Is it OK? There won\'t be any further checks!', $this->pObj->extKey, 2);
            t3lib_div::devLog('[HELP/DISCOVER] If you like, configure the wrapping with the TS handleAs property.', $this->pObj->extKey, 1);
          }
        }
      }
      else
      {
        $boolEveryCondition = false;
        // false: Don't check the next TCA item
        if( $this->pObj->b_drs_discover )
        {
          t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': There is no TS item \''.$TCAitem.'\' or it is false.', $this->pObj->extKey, 0);
        }
      }

        //TCAconfig.type
      if( $boolEveryCondition && $TSconfAdItems[$TCAitem.'.']['TCAconfig.']['type'] )
      {
          // Autodiscover for this TCAitem is true
        $valueInTheTCA = $GLOBALS['TCA'][$table]['columns'][$field]['config']['type'];
        if( $valueInTheTCA )
        {
            // The TCA property of the current table.field has a value
          $arrTSvalues = $this->pObj->objZz->getCSVasArray($TSconfAdItems[$TCAitem.'.']['TCAconfig.']['type.']['csvValue']);
            // Get the values from the TS for comparison
          if(in_array($valueInTheTCA, $arrTSvalues)) {
            $boolEveryCondition = true;
              // true: Check the next TCA item
            if($this->pObj->b_drs_discover) {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'type\'] matchs the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
          else
          {
            $boolEveryCondition = false;
            // false: Don't check the next TCA item
            if( $this->pObj->b_drs_discover )
            {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'type\'] doesn\'t match the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
        }
      }

        //TCAconfig.internal_type
      if( $boolEveryCondition && $TSconfAdItems[$TCAitem.'.']['TCAconfig.']['internal_type'] )
      {
          // Autodiscover for this TCAitem is true
        $valueInTheTCA = $GLOBALS['TCA'][$table]['columns'][$field]['config']['internal_type'];
        if($valueInTheTCA) {
          // The TCA property of the current table.field has a value
          $arrTSvalues = $this->pObj->objZz->getCSVasArray($TSconfAdItems[$TCAitem.'.']['TCAconfig.']['internal_type.']['csvValue']);
          // Get the values from the TS for comparison
          if(in_array($valueInTheTCA, $arrTSvalues)) {
            $boolEveryCondition = true;
            // true: Check the next TCA item
            if($this->pObj->b_drs_discover) {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'internal_type\'] matchs the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
          else
          {
            $boolEveryCondition = false;
            // false: Don't check the next TCA item
            if($this->pObj->b_drs_discover) {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'internal_type\'] doesn\'t match the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
        }
      }

      //TCAconfig.allowed
      if($boolEveryCondition && $TSconfAdItems[$TCAitem.'.']['TCAconfig.']['allowed']) {
        // Autodiscover for this TCAitem is true
        $valueInTheTCA = $GLOBALS['TCA'][$table]['columns'][$field]['config']['allowed'];
        if($valueInTheTCA) {
          // The TCA property of the current table.field has a value
          $gfxAllowedValue = $TSconfAdItems[$TCAitem.'.']['TCAconfig.']['allowed.']['value'];
          // Get the values from the TS for comparison
          $srcGFXAllowedValues = $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'];
          $dstGFXAllowedValues = $GLOBALS['TYPO3_CONF_VARS']['GFX'][$gfxAllowedValue];
          if($srcGFXAllowedValues == $dstGFXAllowedValues) {
            $boolEveryCondition = true;
            // true: Check the next TCA item
            if($this->pObj->b_drs_discover)
            {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'allowed\'] matchs the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          } else {
            $boolEveryCondition = false;
            // false: Don't check the next TCA item
            if($this->pObj->b_drs_discover)
            {
              t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.': Property TCA[\'config\'][\'allowed\'] doesn\'t match the TS item \''.$TCAitem.'\'.', $this->pObj->extKey, 0);
            }
          }
        }
      }

      if($boolEveryCondition) {
        if($TSconfAdItems[$TCAitem.'.']['setUploadFolder']) {
          $uploadfolder = $GLOBALS['TCA'][$table]['columns'][$field]['config']['uploadfolder'];
        }
        switch($TSconfAdItems[$TCAitem.'.']['oneValueOnly']) {
          case(true):
            // Only one table.field is allowed
            if($this->pObj->arrHandleAs[$TCAitem]) {
              // We have one table.field already. That isn't allowed.
              if($this->pObj->b_drs_error)
              {
                t3lib_div::devLog('[WARN/DISCOVER] The TS item \''.$TCAitem.'\' should have only one value but there are more.', $this->pObj->extKey, 2);
              }
            }
            $this->pObj->arrHandleAs[$TCAitem] = $tableField;
            break;
          default:
            if($this->pObj->arrHandleAs[$TCAitem]) {
              // We have one table.field at least. Add a comma for the list of comma seperated values.
              $this->pObj->arrHandleAs[$TCAitem] .= ', ';
              if($this->pObj->b_drs_discover && $uploadfolder)
              {
                t3lib_div::devLog('[WARN/DISCOVER] arrHandleAs[\''.$TCAitem.'\'] has more than one value and more than one upload folder. Be aware that browser can process only one upload folder! Maybe you will get a wrong result!', $this->pObj->extKey, 2);
              }
            }
            // Add the table.field to the list of comma seperated values.
            $this->pObj->arrHandleAs[$TCAitem] .= $tableField;
            break;
        }
        if($uploadfolder) $this->pObj->arrHandleAs['uploadfolder'][$TCAitem] = $uploadfolder;
        if($this->pObj->b_drs_discover)
          {
          t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' is added to the arrHandleAs[\''.$TCAitem.'\'].', $this->pObj->extKey, 0);
          if($uploadfolder) {
            t3lib_div::devLog('[INFO/DISCOVER] '.$uploadfolder.' is added to the arrHandleAs[\'uploadfolder\'][\''.$TCAitem.'\'].', $this->pObj->extKey, 0);
          }
        }
      } else {
        if($this->pObj->b_drs_discover) {
          t3lib_div::devLog('[INFO/DISCOVER] '.$tableField.' isn\'t added to the arrHandleAs[\''.$TCAitem.'\'].', $this->pObj->extKey, 0);
        }
      }
    }
    $this->pObj->boolFirstElement = false;

    return true;
  }








  /***********************************************
   *
   * Handle the value
   *
   **********************************************/



  /**
 * handleAs( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['text'].
 *                  value will wrapped with content_stdWrap
 * @param   $tableField           : current tableField (sytax table.field)
 * @param   $value                : value of the current table.field
 * @param   $lDisplayView         : local or global display_view configuration
 * @param   $bool_drs_handleCase  : flag for the DRS
 * @param   $bool_dontColorSwords : flag for dyeing swords
 * @param   $elements             : current row
 * @param   $maxColumns           : total columns
 * @param   $boolSubstitute       : flag for substitution
 *
 * @return	array   $arr_return with elements drs_handleCase and value
 * @version 3.9.6
 * @since   3.9.6
 */
  function handleAs( $tableField, $value, $lDisplayView, $bool_drs_handleCase, $bool_dontColorSwords, $elements, $maxColumns, $boolSubstitute )
  {
      // Set globals
    $this->tableField           = $tableField;
    $this->value                = $value;
    $this->lDisplayView         = $lDisplayView;
    $this->bool_drs_handleCase  = $bool_drs_handleCase;
    $this->bool_dontColorSwords = $bool_dontColorSwords;
    $this->elements             = $bool_dontColorSwords;
    $this->maxColumns           = $maxColumns;
    $this->boolSubstitute       = $boolSubstitute;
    $this->arrHandleAs          = $this->pObj->arrHandleAs;
      // Set globals

      // Set default return array
    $arr_return['data']['value']            = $this->value;
    $arr_return['data']['drs_handleCase']   = $this->bool_drs_handleCase;
    $arr_return['data']['dontColorSwords']  = $this->bool_dontColorSwords;
    $arr_return['data']['maxColumns']       = $this->maxColumns;
    $arr_return['data']['boolSubstitute']   = $this->boolSubstitute;
      // Set default return array

      // RETURN tableField has its own configuration
    list( $table, $field ) = explode( '.', $this->tableField );
    if( is_array( $this->conf_view[$table . '.'][$field . '.'] ) )
    {
        // DRS - Development Reporting System
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        $prompt = 'handleAs: ' . $this->tableField . ' has its own configuration';
        t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      }
      // DRS - Development Reporting System
      return $arr_return;
    }
      // RETURN tableField has its own configuration

    $this->handleAsDocument( );
    $this->handleAsImage( );
    $this->handleAsImagecaption( );
    $this->handleAsImagealttext( );
    $this->handleAsImagetitletext( );
    $this->handleAsText( );
    $this->handleAsTimestamp( );
    $this->handleAsTitle( );
    $this->handleAsYYYYMMDD( );

    $arr_return['data']['value']            = $this->value;
    $arr_return['data']['drs_handleCase']   = $this->bool_drs_handleCase;
    $arr_return['data']['dontColorSwords']  = $this->bool_dontColorSwords;
    $arr_return['data']['maxColumns']       = $this->maxColumns;
    $arr_return['data']['boolSubstitute']   = $this->boolSubstitute;

$pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
if (!($pos === false))
{
  var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')',  $tableField, $this->boolSubstitute );

}
    return $arr_return;
  }









  /**
 * handleAsImage( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsImage( )
  {
      // RETURN tableField isn't content of handleAs['image']
    $pos = strpos( $this->arrHandleAs['image'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['image']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[image]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

      // Count images per row
    if ( $this->pObj->boolFirstRow )
    {
      $this->imagesPerRow = 1;
    }
    if ( ! $this->pObj->boolFirstRow )
    {
      $this->imagesPerRow++;
    }
      // Count images per row


      // DRS - Development Reporting System
    if( $this->imagesPerRow > 1 )
    {
      if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
      {
        $prompt = 'DANGEROUS: Current row has ' . $this->imagesPerRow . ' images. ' .
                  'Images, captions, alt texts and title texts can\'t allocate exactly. ';
        t3lib_div::devLog('[WARN/TEMPLATING] ' . $prompt, $this->pObj->extKey, 2);
        $prompt = 'URGENT: Configure the TypoScript of images manually!';
        t3lib_div::devLog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System

      // Image caption
    $csv_imageCaption = $this->arrHandleAs['imageCaption'];
    $arr_imageCaption = $this->pObj->objZz->getCSVasArray( $csv_imageCaption );
    $imageCaption     = $arrValues[ ( $this->imagesPerRow - 1 ) ];

      // Image alt text
    $csv_imageAltText = $this->arrHandleAs['imageAltText'];
    $arr_imageAltText = $this->pObj->objZz->getCSVasArray( $csv_imageAltText );
    $imageAltText     = $arrValues[ ( $this->imagesPerRow - 1 ) ];

      // Image title text
    $csv_imageTitleText = $this->arrHandleAs['imageTitleText'];
    $arr_imageTitleText = $this->pObj->objZz->getCSVasArray( $csv_imageTitleText );
    $imageTitleText     = $arrValues[ ( $this->imagesPerRow - 1 ) ];

      // Wrap image
    $tsImage['image']           = $this->elements[$this->tableField];
    $tsImage['imagecaption']    = $imageCaption;
    $tsImage['imagealttext']    = $imageAltText;
    $tsImage['imagetitletext']  = $imageTitleText;
    $this->value                = $this->pObj->objWrapper->wrapImage( $tsImage );

    return;
  }









  /**
 * handleAsImagecaption( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsImagecaption( )
  {
      // RETURN tableField isn't content of handleAs['imageCaption']
    $pos = strpos( $this->arrHandleAs['imageCaption'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['imageCaption']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[imageCaption]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->maxColumns--;
    $this->boolSubstitute = false;

    return;
  }









  /**
 * handleAsDocument( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsDocument( )
  {
      // RETURN tableField isn't content of handleAs['document']
    $pos = strpos( $this->arrHandleAs['document'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['document']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[document]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->value = $this->pObj->objWrapper->wrapDocument( $this->value );

    return;
  }









  /**
 * handleAsImagealttext( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsImagealttext( )
  {
      // RETURN tableField isn't content of handleAs['ImageAltText']
    $pos = strpos( $this->arrHandleAs['ImageAltText'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['ImageAltText']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[ImageAltText]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->maxColumns--;
    $this->boolSubstitute = false;

    return;
  }









  /**
 * handleAsImagetitletext( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsImagetitletext( )
  {
      // RETURN tableField isn't content of handleAs['ImageTitleText']
    $pos = strpos( $this->arrHandleAs['ImageTitleText'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['ImageTitleText']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[ImageTitleText]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->maxColumns--;
    $this->boolSubstitute = false;

    return;
  }









  /**
 * handleAsText( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['text'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsText( )
  {
      // RETURN tableField isn't content of handleAs['text']
    $pos = strpos( $this->arrHandleAs['text'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['text']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[text]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

//      // RETURN value is null
//    if( $this->value == null )
//    {
//      return $arr_return;
//    }
//      // RETURN value is null

      // tableField has a content_stdWrap
    if( is_array ( $this->lDisplayView['content_stdWrap.'] ) )
    {
      $this->value =  $this->pObj->objWrapper->general_stdWrap(
                        $this->value,
                        $this->lDisplayView['content_stdWrap.']
                      );
    }
      // tableField has a content_stdWrap

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
        // tableField hasn't a content_stdWrap
      if( ! is_array ( $this->lDisplayView['content_stdWrap.'] ) )
      {
        $prompt = $lDisplayType . 'content_stdWrap isn\'t configured.';
        t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = $this->tableField . ' will be wrapped with general_stdWrap.';
        t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
        $prompt = 'If you like to change the wrapping, please configure ' . $lDisplayType . 'content_stdWrap.';
        t3lib_div::devLog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
      }
    }
      // DRS - Development Reporting System

    return;
  }









  /**
 * handleAsTimestamp( ):  handle the given value as TEXT, if tableField is oart of
 *                        the global $this->pObj->arrHandleAs['text'].
 *                        value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsTimestamp(  )
  {
//$pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false))
//{
//  var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')',  $this->arrHandleAs['timestamp'] , $this->tableField );
//}
      // RETURN tableField isn't content of handleAs['timestamp']
    $pos = strpos( $this->arrHandleAs['timestamp'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['timestamp']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[timestamp]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['timestamp.']['dontColorSwords'];
      // Dyeing swords?

      // strftime $this->value
    $this->value  = strftime($this->pObj->tsStrftime, $this->value);

      // $this->value is UTF8
    if( mb_detect_encoding( $this->value ) == 'UTF-8' )
    {
        // strftime should moved to ISO
      if( $this->pObj->conf['format.']['strftime.']['utf8_encode'] )
      {
          // Encode it
        $value_iso = utf8_encode( $this->value );
          // DRS - Development Reporting System
        if ($this->pObj->b_drs_templating)
        {
          $prompt = $this->value . ' is in UTF-8 format. Change it to ISO.';
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'It is encoded to: '. $value_iso;
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'If you have problems with UTF-8 chars in formated timestamps, please set format.strftime.utf8_encode to 0.';
          t3lib_div::devlog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
        }
          // DRS - Development Reporting System
          // Move it to ISO
        $this->value = $value_iso;
      }
        // strftime should moved to ISO
        // strftime shouldn't moved to ISO
      if( ! $this->pObj->conf['format.']['strftime.']['utf8_encode'] )
      {
        if( $this->pObj->b_drs_templating )
        {
          $prompt = $this->value . ' is in UTF-8 format.';
          t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
          $prompt = 'If you have problems with UTF-8 chars in formated timestamps, please set format.strftime.utf8_encode to 1.';
          t3lib_div::devlog('[HELP/TEMPLATING] ' . $prompt, $this->pObj->extKey, 1);
        }
      }
        // strftime shouldn't moved to ISO
    }
      // $this->value is UTF8
//$pos = strpos( '91.23.174.97' , t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//if (!($pos === false))
//{
//  var_dump( __METHOD__ . ' (line: ' . __LINE__ . ')',  $this->value );
//
//}

    return;
  }









  /**
 * handleAsTitle( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsTitle( )
  {
      // RETURN tableField isn't content of handleAs['title']
    $pos = strpos( $this->arrHandleAs['title'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['title']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[title]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->maxColumns--;
    $this->boolSubstitute = false;

    return;
  }









  /**
 * handleAsYYYYMMDD( ): handle the given value as TEXT, if tableField is oart of
 *                  the global $this->pObj->arrHandleAs['image'].
 *                  value will wrapped with content_stdWrap
 *
 * @return	void
 * @version 3.9.6
 * @since   3.9.6
 */
  private function handleAsYYYYMMDD( )
  {
      // RETURN tableField isn't content of handleAs['YYYY-MM-DD']
    $pos = strpos( $this->arrHandleAs['YYYY-MM-DD'] , $this->tableField );
    if( $pos === false )
    {
      return;
    }
      // RETURN tableField isn't content of handleAs['YYYY-MM-DD']

      // DRS - Development Reporting System
    if ($this->pObj->boolFirstRow && $this->pObj->b_drs_templating)
    {
      $prompt = $this->tableField . ' is content of handleAs[YYYY-MM-DD]';
      t3lib_div::devLog('[INFO/TEMPLATING] ' . $prompt, $this->pObj->extKey, 0);
      $this->bool_drs_handleCase = true;
    }
      // DRS - Development Reporting System

      // Dyeing swords?
    $arr_TCAitems                = $this->conf_view['autoconfig.']['autoDiscover.']['items.'];
    $this->bool_dontColorSwords  = $arr_TCAitems['image.']['dontColorSwords'];
      // Dyeing swords?

    $this->value = $this->pObj->objWrapper->wrapYYYYMMDD( $this->value );

    return;
  }










}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_tca.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_tca.php']);
}

?>