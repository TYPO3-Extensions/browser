<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011-2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  This script is based on the TYPO3 extension browser
 *  (c) 2010 - Bert Wendler and Marcel Walczak
 *
 *  Thanks to Bert Wendler and Marcel Walczak for their wonderful
 *  work.
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


require_once(PATH_t3lib.'class.t3lib_tceforms.php');


 /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   28: class tx_browser_befilter_hooks implements t3lib_localRecordListGetTableHook
 *   41:     public function getDBlistQuery ($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
 *   88:     public function makeFormitem($item, $table, $conf)
 *  151:     public function editFormitem($confarray, $item, $labelValue)
 *  170:     public function makeWhereClause($item, $conf, $itemValue, $table)
 *  212:     public function makeQueryInputTrim($item, $itemValue, $table)
 *  225:     public function makeQuerySelect($item, $itemValue, $table)
 *  238:     public function makeQueryCheckTime($item, $itemValue, $table)
 *  253:     public function makeQueryInputFromto($item, $from, $to, $table)
 *  275:     public function getTimestampFrom($timetime)
 *  287:     public function getTimestampTo($timetime)
 *  303:     private function init_ts()
 *
 * TOTAL FUNCTIONS: 11
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_befilter_hooks implements t3lib_localRecordListGetTableHook {

  var $extension = 'tx_browser_befilter';
  var $session = array();
  var $tceforms;
  var $filterCriteria = array();
    // 0.2.0, 110815, dwildt +
  var $conf           = null;
  var $objLibSql      = null;
  var $objLibTs       = null;
  var $oppositeField  = null;
  var $pageId         = null;
  var $pageTSconfig   = null;
  var $relationMM     = false;
    // 0.2.0, 110815, dwildt +

  public function getDBlistQuery ($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject) {

      // 0.2.0, 110815, dwildt +
    $this->pageId = $pageId;
    $this->initLibrary();
      // 0.2.0, 110815, dwildt +

      // 120710, dwildt, 1-
    //if( array_key_exists( 'filter', $GLOBALS['TCA'][$table]['ctrl'] ) && ( $parentObject->table == $table ) )
      // 120710, dwildt, 1+
    if( array_key_exists( 'filter', ( array ) $GLOBALS['TCA'][$table]['ctrl'] ) && ( $parentObject->table == $table ) )
    {
      $posts = t3lib_div::_POST($this->extension);
      if(isset($posts)) {
        $GLOBALS["BE_USER"]->setAndSaveSessionData($table."_filterciteria", $posts);
        $this->filterCriteria = $posts;
      } else{
        $this->filterCriteria = $GLOBALS["BE_USER"]->getSessionData($table."_filterciteria");
      }

      $this->tceforms = t3lib_div::makeInstance("t3lib_TCEforms");
      $this->tceforms->initDefaultBEMode();
      $this->tceforms->backPath = $GLOBALS['BACK_PATH'];
      $parentObject->HTMLcode .= $this->tceforms->printNeededJSFunctions_top();
      $parentObject->HTMLcode .= $this->tceforms->printNeededJSFunctions();
        // 0.2.0, 110815, dwildt -
      //$itemList = explode(',', $selectedFieldsList);
        // 0.2.0, 110815, dwildt +
      switch($GLOBALS['TCA'][$table]['ctrl']['filter']) {
        case('filter_for_all_fields'):
            // Will display all configured filters
          $itemList = array_keys($GLOBALS['TCA'][$table]['columns']);
          break;
        case('filter_for_displayed_fields_only'):
        case(true):
        default:
            // Will display configured filters for displayed fields only
          $itemList = explode(',', $selectedFieldsList);
      }
        // 0.2.0, 110815, dwildt +

      $parentObject->HTMLcode .= '<fieldset>';
      $parentObject->HTMLcode .= '<legend>Suchoptionen</legend>';
      foreach ($itemList as $item) {
        if ($conf = $GLOBALS['TCA'][$table]['columns'][$item]['config_filter']) {
            // 0.2.0, 110815, dwildt +
          $this->conf = $conf;
            // Replace markers like ###PAGE_TSCONFIG_ID### and ###PAGE_TSCONFIG_IDLIST###
          $conf = $this->objLibTs->regard_pageTSconfig_in_foreignTableWhere($this, $table, $item);
            // 0.2.0, 110815, dwildt +
          $parentObject->HTMLcode .= $this->makeFormitem($item, $table, $conf);
          $additionalWhereClause .= $this->makeWhereClause($item, $conf, $this->filterCriteria[$item], $table);
        }
      }
      $parentObject->HTMLcode .= '<input type="submit" value="Suche starten" style="margin-top: 15px;" />';
      $parentObject->HTMLcode .= '</fieldset>';
    }
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $table: ...
 * @param [type]    $conf: ...
 * @return  [type]    ...
 */
  public function makeFormitem($item, $table, $conf) {
    if(array_key_exists('fromto', $conf)) {
      // from
      $element = array();
      $labelDef = $GLOBALS['TCA'][$table]['columns'][$item]['label'];
      $itemfrom = $item.'_from';
      $labelValuefrom = $this->tceforms->sL($labelDef).$this->tceforms->sL('LLL:EXT:browser/lib/locallang.xml:befilter_from');
        // 0.2.0, 110815, dwildt +
      if(isset($conf['fromto_labels']['from']))
      {
        $labelValuefrom = $this->tceforms->sL($conf['fromto_labels']['from']);
      }
        // 0.2.0, 110815, dwildt +
      $confarray = array(
        'itemFormElName' => $this->extension.'['.$itemfrom.']',
        'itemFormElValue' => '',
        'fieldConf' => array(
          'config' => $conf,
        ),
      );
      $element = $this->editFormitem($confarray, $itemfrom, $labelValuefrom);

      // to
      $itemto = $item.'_to';
      $labelValueto = $this->tceforms->sL($labelDef).$this->tceforms->sL('LLL:EXT:browser/lib/locallang.xml:befilter_to');
        // 0.2.0, 110815, dwildt +
      if(isset($conf['fromto_labels']['to']))
      {
        $labelValueto = $this->tceforms->sL($conf['fromto_labels']['to']);
      }
        // 0.2.0, 110815, dwildt +
      $confarray = array(
        'itemFormElName' => $this->extension.'['.$itemto.']',
        'itemFormElValue' => '',
        'fieldConf' => array(
          'config' => $conf,
        ),
      );
      $element .= $this->editFormitem($confarray, $itemto, $labelValueto);
    } else {
      $element = array();
      $labelDef = $GLOBALS['TCA'][$table]['columns'][$item]['label'];
      $labelValue = $this->tceforms->sL($labelDef);
      $confarray = array(
        'itemFormElName' => $this->extension.'['.$item.']',
        'itemFormElValue' => '',
        'fieldConf' => array(
          'config' => $conf,
        ),
      );
      $element = $this->editFormitem($confarray, $item, $labelValue);
    }
    return $element;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $confarray: ...
 * @param [type]    $item: ...
 * @param [type]    $labelValue: ...
 * @return  [type]    ...
 */
  public function editFormitem($confarray, $item, $labelValue) {
    $formElement = $this->tceforms->getSingleField_SW('','',array(),$confarray);
    $formElement = str_replace($this->extension.'['.$item.']'.'_hr', $this->extension.'['.$item.']', $formElement);
    $formElement = preg_replace('/<input\ type=\"hidden.*?>/s','',$formElement);
    $formElement = str_replace($this->extension.'['.$item.']" value=""', $this->extension.'['.$item.']" value="'.$this->filterCriteria[$item].'"', $formElement);
    $formElement = str_replace('<option value="'.$this->filterCriteria[$item].'">', '<option value="'.$this->filterCriteria[$item].'" selected="selected">', $formElement);
    $formElement = '<div style="float:left; margin: 5px;"><label>'.$labelValue.'</label><br />'.$formElement.'</div>';
    return $formElement;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $conf: ...
 * @param [type]    $itemValue: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeWhereClause($item, $conf, $itemValue, $table) {
    if
    (
      (isset($this->filterCriteria[$item])  && ($this->filterCriteria[$item]!='-1') && ($this->filterCriteria[$item]!='')) ||
      (isset($this->filterCriteria[$item.'_from'])  && ($this->filterCriteria[$item.'_from']!='-1') && ($this->filterCriteria[$item.'_from']!='')) ||
      (isset($this->filterCriteria[$item.'_to'])  && ($this->filterCriteria[$item.'_to']!='-1') && ($this->filterCriteria[$item.'_to']!=''))
    )
    {
      switch($conf['type']) {
        case 'select':
          $whereClause = $this->makeQuerySelect($item, $itemValue, $table);
          break;
        case 'input':
            // 0.2.0, 110815, dwildt -
          //switch($conf['eval']) {
            // 0.2.0, 110815, dwildt +
          switch(true) {
              // 0.2.0, 110815, dwildt -
            //case 'trim':
              // 0.2.0, 110815, dwildt +
            case(!(strpos($conf['eval'], 'trim')      === false)):
              $whereClause = $this->makeQueryInputTrim($item, $itemValue, $table);
              break;
              // 0.2.0, 110815, dwildt -
            //case 'date':
              // 0.2.0, 110815, dwildt +
            case(!(strpos($conf['eval'], 'datetime')  === false)):
              if (array_key_exists('fromto', $conf)) {
                $whereClause = $this->makeQueryInputDatetimeFromto($item, $this->filterCriteria[$item.'_from'], $this->filterCriteria[$item.'_to'], $table);
              } else {
                $whereClause = $this->makeQueryInputDatetime($item, $itemValue, $table);
              }
              break;
            case(!(strpos($conf['eval'], 'date')      === false)):
              if (array_key_exists('fromto', $conf)) {
                $whereClause = $this->makeQueryInputFromto($item, $this->filterCriteria[$item.'_from'], $this->filterCriteria[$item.'_to'], $table);
              } else {
                $whereClause = $this->makeQueryCheckTime($item, $itemValue, $table);
              }
              break;
            default:
              // dosomething
              break;
          }
          break;
        default:
          $whereClause = $this->makeQueryInputTrim($item, $itemValue, $table);
          break;
      }
    }
    return $whereClause;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $itemValue: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQueryInputTrim($item, $itemValue, $table) {
    $query = ' AND '.$GLOBALS['TYPO3_DB']->searchQuery($searchwords=array(searchword=>$itemValue), $fields=array('field'=>$item), $table);
    return $query;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $itemValue: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQuerySelect($item, $itemValue, $table) {
      // 0.2.0, 110815, dwildt -
    //$query = ' AND ('.$table.'.'.$item.' = \''.$itemValue.'\')';
      // 0.2.0, 110815, dwildt +
    $operator = ' = ';
    $query = $query . $this->objLibSql->get_andWhere($this, $table, $item, $operator, $itemValue);
      // 0.2.0, 110815, dwildt +
    return $query;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $itemValue: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQueryCheckTime($item, $itemValue, $table) {
      // dwildt: method getTimestamp doesn't exist!
      // 0.2.0, 110815, dwildt -
    //$timestamp = $this->getTimestamp($itemValue);
      // 0.2.0, 110815, dwildt -
    //$query = ' AND ('.$table.'.'.$item.' = \''.$itemValue.'\')';
      // 0.2.0, 110815, dwildt +
    $operator = ' = ';
    $query = $query . $this->objLibSql->get_andWhere($this, $table, $item, $operator, $itemValue);
      // 0.2.0, 110815, dwildt +
    return $query;
  }

    // 0.2.0, 110815, dwildt +
  /**
 * makeQueryInputDatetime
 *
 * @param [type]    $item: ...
 * @param [type]    $from: ...
 * @param [type]    $to: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQueryInputDatetime($item, $itemValue, $table) {
    $query = null;
    if ($itemValue != '') {
      $timestamp  =  $this->getTimestampFromDatetime($itemValue);
      $query      = ' AND (' . $table . '.' . $item . ' = ' . $timestamp . ')';
    }
    return $query;
  }
    // 0.2.0, 110815, dwildt +

    // 0.2.0, 110815, dwildt +
  /**
 * makeQueryInputDatetimeFromto
 *
 * @param [type]    $item: ...
 * @param [type]    $from: ...
 * @param [type]    $to: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQueryInputDatetimeFromto($field, $value_from, $value_to, $table) {
    $query = null;
    if ($value_from != '') {
      $value_from =  $this->getTimestampFromDatetime($value_from);
      $operator   = ' >= ';
      $query      = $query . $this->objLibSql->get_andWhere($this, $table, $field, $operator, $value_from);
    }
    if ($value_to != '') {
      $value_to =  $this->getTimestampFromDatetime($value_to);
      $query    = $query . ' AND ('.$table.'.'.$field.' <= '.$value_to.')';
    }
    return $query;
  }
    // 0.2.0, 110815, dwildt +

  /**
 * [Describe function...]
 *
 * @param [type]    $item: ...
 * @param [type]    $from: ...
 * @param [type]    $to: ...
 * @param [type]    $table: ...
 * @return  [type]    ...
 */
  public function makeQueryInputFromto($item, $from, $to, $table) {
      // 0.2.0, 110815, dwildt +
    $query = null;
    if ($from!='') {
        // 0.2.0, 110815, dwildt -
//      $from =  $this->getTimestampFrom($from);
//      $from = ' AND ('.$table.'.'.$item.' >= '.$from.')';
        // 0.2.0, 110815, dwildt -
        // 0.2.0, 110815, dwildt +
      $itemValue  =  $this->getTimestampFrom($from);
      $operator   = ' >= ';
      $query      = $query . $this->objLibSql->get_andWhere($this, $table, $item, $operator, $itemValue);
        // 0.2.0, 110815, dwildt +
    }
    if ($to!='') {
        // 0.2.0, 110815, dwildt -
//      $to =  $this->getTimestampTo($to);
//      $to = ' AND ('.$table.'.'.$item.' <= '.$to.')';
        // 0.2.0, 110815, dwildt -
        // 0.2.0, 110815, dwildt +
      $itemValue  =  $this->getTimestampFrom($to);
      $operator   = ' <= ';
      $query      = $query . $this->objLibSql->get_andWhere($this, $table, $item, $operator, $itemValue);
        // 0.2.0, 110815, dwildt +
    }
      // 0.2.0, 110815, dwildt -
//    $query = $from.$to;
    return $query;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $timetime: ...
 * @return  [type]    ...
 */
  public function getTimestampFrom($timetime) {
    $dmY = explode('-', $timetime);
    $timestamp = mktime(0, 0, 0, $dmY[1], $dmY[0], $dmY[2]);
    return $timestamp;
  }

  /**
 * [Describe function...]
 *
 * @param [type]    $timetime: ...
 * @return  [type]    ...
 */
  public function getTimestampTo($timetime) {
    $dmY = explode('-', $timetime);
    $timestamp = mktime(23, 59, 59, $dmY[1], $dmY[0], $dmY[2]);
    return $timestamp;
  }


    // 0.2.0, 110815, dwildt +
  /**
 * [Describe function...]
 *
 * @param [type]    $timetime: ...
 * @return  [type]    ...
 */
  public function getTimestampFromDatetime($datetime) {
    list($time, $date)        = explode(' ', $datetime);
    list($hour, $minutes)     = explode(':', $time);
    list($day, $month, $year) = explode('-', $date);
    $timestamp = mktime($hour, $minutes, 0, $month, $day, $year);
    return $timestamp;
  }
    // 0.2.0, 110815, dwildt +

  /**
 * [Describe function...]
 *
 * @param [type]    $datetime: ...
 * @return  [type]    ...
 */
  public function getTimestampToDatetime($datetime) {
    $dmY = explode('-', $datetime);
    $timestamp = mktime(23, 59, 59, $dmY[1], $dmY[0], $dmY[2]);
    return $timestamp;
  }

    // 0.2.0, 110815, dwildt +
  /**
 * initLibrary(): Initiate the library
 *            * Initiate class tx_browser_befilter_sql
 *            * Initiate class tx_browser_befilter_ts
 *            * Allocates the page TS config
 *
 * @return  void
 * @author dwildt
 * @since 0.2.0
 * @version 0.2.0
 */
  private function initLibrary() {

      // Init class tx_browser_befilter_sql
    require_once('class.tx_browser_befilter_sql.php');
    $this->objLibSql = new tx_browser_befilter_sql($this);
      // Init class tx_browser_befilter_sql

      // Init class tx_browser_befilter_ts
    require_once('class.tx_browser_befilter_ts.php');
    $this->objLibTs = new tx_browser_befilter_ts($this);
      // Init class tx_browser_befilter_ts

      // Allocates the page TS config
    if(!$this->pageTSconfig) {
      $this->pageTSconfig = t3lib_BEfunc::getPagesTSconfig($this->pageId,$rootLine='',$returnPartArray=0);
    }
      // Allocates the page TS config
  }
    // 0.2.0, 110815, dwildt +
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_hooks.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/lib/class.tx_browser_befilter_hooks.php']);
}

?>