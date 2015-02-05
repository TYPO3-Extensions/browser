<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2015 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 * The class tx_browser_pi1_typoscriptHandleAs bundles typoscript methods for the extension browser
 *
 * @author       Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package      TYPO3
 * @subpackage   browser
 * @version      5.0.0
 * @since        5.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   57: class tx_browser_pi1_typoscriptHandleAs
 *   85:     function __construct( $parentObj )
 *  106:     private function ifHandleAsTitle()
 *  139:     private function handleAs()
 *  162:     private function handleAsTitle()
 *  177:     private function init( $tableField, $row )
 *  192:     private function initHandleAs( $row )
 *  206:     private function initRow( $row )
 *  220:     private function initTableField( $tableField )
 *  234:     public function main( $tableField, $row )
 *  256:     private function requirements()
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_typoscriptHandleAs
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

  private $handleAs = null;     // [array]
  private $row = null;          // [array] current row
  private $tableField = null;   // [String] current tableField like tx_org_job.title
  private $template = null;     // [string] current template (HTML)

  /**
   * Constructor. The method initiate the parent object
   *
   * @param	object		The parent object
   * @return	void
   */

  public function __construct( $parentObj )
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
   * getHandleAsKey() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  public function getHandleAsKey( $tableField )
  {
    $this->initHandleAs( );
    switch ( true )
    {
      case($this->ifHandleAsTitle( $tableField )):
        $key = 'title';
        break;
      default:
        $key = null;
        break;
    }
    return $key;
  }

  /**
   * handleAs() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function handleAs()
  {
    switch ( true )
    {
      case($this->ifHandleAsTitle( $this->tableField )):
        $handledTableField = $this->handleAsTitle();
        break;
      default:
        $handledTableField = $this->row[ $this->tableField ];
        break;
    }
    return $handledTableField;
  }

  /**
   * handleAsTitle() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function handleAsTitle()
  {
    if ( !empty( $this->row[ $this->tableField ] ) )
    {
      $handledTableField = 'ABC ' . $this->row[ $this->tableField ] . ' XYZ';
    }
    else
    {
      list($table) = explode( '.', $this->tableField );
      $handledTableField = 'ID ' . $this->pObj->pObj->piVars[ 'showUid' ] . ' from table ' . $table;
    }

    return $handledTableField;
  }

  /**
   * ifHandleAsTitle() :
   *
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	boolean		$arrHandled[ $tableField ]  : true, if $tableField is the title key
   * @version 5.0.0
   * @since 5.0.0
   */
  private function ifHandleAsTitle( $tableField )
  {
    static $arrHandled = array();
    // RETURN: tableField is processed before, return result of before
    if ( isset( $arrHandled[ $tableField ] ) )
    {
      return $arrHandled[ $tableField ];
    }

    // RETURN : false, title should not handled automatically
    if ( ! $this->ifHandleAsTitleDisplay() )
    {
      $arrHandled[ $tableField ] = false;
      return $arrHandled[ $tableField ];
    }
    // RETURN : false, template doesn't contain the hash marker TITLE
    if ( ! $this->ifHandleAsTitleTemplateWiMarker() )
    {
      $arrHandled[ $tableField ] = false;
      return $arrHandled[ $tableField ];
    }
    // RESULT : true, if tableField is handleAs title, false, if it isn't
    $arrHandled[ $tableField ] = ($tableField == $this->handleAs[ 'title' ] );

    // RETURN: result
    return $arrHandled[ $tableField ];
  }

  /**
   * ifHandleAsTitleDisplay() :
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function ifHandleAsTitleDisplay()
  {
    if ( $this->pObj->pObj->lDisplay[ 'title' ] )
    {
      return true;
    }
    if ( $this->pObj->pObj->b_drs_typoscript )
    {
      $prompt = 'No field won\'t be handled as the title.';
      t3lib_div::devlog( '[INFO/TYPOSCRIPT] ' . $prompt, $this->pObj->pObj->extKey, 0 );
      $prompt = 'Please configure displaySingle.display.title = 1, if you want an automatically title handling.';
      t3lib_div::devlog( '[INFO/TYPOSCRIPT] ' . $prompt, $this->pObj->pObj->extKey, 1 );
    }
    return false;
  }

  /**
   * ifHandleAsTitleTemplateWiMarker() :
   *
   * @return	boolean
   * @version 5.0.0
   * @since 5.0.0
   */
  private function ifHandleAsTitleTemplateWiMarker()
  {
    $pos = strpos( $this->template, '###TITLE###' );
    // RETURN : null, because ###TITLE### isn't used in the template
    if ( !($pos === false) )
    {
      return true;
    }

    if ( $this->pObj->pObj->b_drs_typoscript )
    {
      $prompt = 'The system marker ###TITLE### isn\'t used in the HTML-template.';
      t3lib_div::devlog( '[INFO/TYPOSCRIPT] ' . $prompt, $this->pObj->pObj->extKey, 0 );
    }
    return false;
  }

  /**
   * init() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function init( $tableField, $row )
  {
    $this->initHandleAs( );
    $this->initRow( $row );
    $this->initTableField( $tableField );
  }

  /**
   * initHandleAs() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function initHandleAs()
  {
    $this->handleAs = $this->pObj->pObj->arrHandleAs;
  }

  /**
   * initRow() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function initRow( $row )
  {
    $this->row = $row;
  }

  /**
   * initTableField() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function initTableField( $tableField )
  {
    $this->tableField = $tableField;
  }

  /**
   * main() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  public function main( $tableField, $row )
  {
    $this->init( $tableField, $row );
    if ( !$this->requirements() )
    {
      $handledTableField = $row[ $tableField ];
      return $handledTableField;
    }

    $handledTableField = $this->handleAs();
    return $handledTableField;
  }

  /**
   * requirements() :
   *
   * @param	array		$row                : current row
   * @param	string		$tableField         : name of the tableField. I.e: tx_org_job.title
   * @return	string		$handledTableField  : the tableField, handled by TypoScript (html)
   * @version 5.0.0
   * @since 5.0.0
   */
  private function requirements()
  {
    static $drsCounter = 0;

    $handleAs = $this->pObj->pObj->arrHandleAs;
    if ( is_array( $handleAs ) )
    {
      return true;
    }

    if ( $drsCounter > 0 )
    {
      return false;
    }
    $drsCounter++;

    if ( $this->pObj->pObj->b_drs_typoscript )
    {
      $prompt = 'handleAs array is empty.';
      t3lib_div::devlog( '[INFO/TYPOSCRIPT] ' . $prompt, $this->pObj->pObj->extKey, 0 );
    }
    return false;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_typoscriptHandleAs.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/browser/pi1/class.tx_browser_pi1_typoscriptHandleAs.php' ]);
}
?>