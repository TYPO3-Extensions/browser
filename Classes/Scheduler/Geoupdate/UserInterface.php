<?php

namespace Netzmacher\Browser\Scheduler\Geoupdate;

use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use \TYPO3\CMS\Scheduler\Task\AbstractTask;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2016 - Dirk Wildt (http://wildt.at.die-netzmacher.de/)
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   70: class tx_browser_Geoupdate_AdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider
 *
 *              SECTION: Bulding the form
 *  103:     public function getAdditionalFields( array &$taskInfo, $task, SchedulerModuleController $parentObject )
 *  133:     private function getFieldTestMode( array &$taskInfo, $task, $parentObject )
 *  203:     private function getFieldTable( array &$taskInfo, $task, $parentObject )
 *  261:     private function getFieldBrowserAdminEmail( array &$taskInfo, $task, $parentObject )
 *  319:     private function getFieldReportMode( array &$taskInfo, $task, $parentObject )
 *
 *              SECTION: Saving
 *  395:     public function saveAdditionalFields( array $submittedData, AbstractTask $task )
 *  413:     private function saveFieldTestMode( array $submittedData, AbstractTask $task )
 *  429:     private function saveFieldTable( array $submittedData, AbstractTask $task )
 *  445:     private function saveFieldBrowserAdminEmail( array $submittedData, AbstractTask $task )
 *  460:     private function saveFieldReportMode( array $submittedData, AbstractTask $task )
 *
 *              SECTION: Validating
 *  484:     public function validateAdditionalFields( array &$submittedData, SchedulerModuleController $parentObject )
 *  540:     private function validateFieldFrequency( array &$submittedData, SchedulerModuleController $parentObject )
 *  565:     private function validateFieldTestMode( array &$submittedData, SchedulerModuleController $parentObject )
 *  601:     private function validateFieldTable( array &$submittedData, SchedulerModuleController $parentObject )
 *  631:     private function validateFieldBrowserAdminEmail( array &$submittedData, SchedulerModuleController $parentObject )
 *  657:     public function validateOS( SchedulerModuleController $parentObject )
 *  692:     private function validateFieldReportMode( array &$submittedData, SchedulerModuleController $parentObject )
 *  736:     private function validateFieldStart( array &$submittedData, SchedulerModuleController $parentObject )
 *
 * TOTAL FUNCTIONS: 18
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Aditional fields provider class for usage with the browser import task
 *
 * @author        Dirk Wildt (http://wildt.at.die-netzmacher.de/)
 * @package        TYPO3
 * @subpackage    browser
 * @version       7.4.5
 * @since         4.5.0
 */
class UserInterface implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface
{

  public $msgPrefix = 'Browser Import';
  private $defaultTable = '';

  /*   * *********************************************
   *
   * Bulding the form
   *
   * ******************************************** */

  /**
   * getAdditionalFields( )  : This method is used to define new fields for adding or editing a task
   *                           In this case, it adds an email field
   *
   *                    The array is multidimensional, keyed to the task class name and each field's id
   *                    For each field it provides an associative sub-array with the following:
   *                        ['code']        => The HTML code for the field
   *                        ['label']        => The label of the field (possibly localized)
   *                        ['cshKey']        => The CSH key for the field
   *                        ['cshLabel']    => The code of the CSH label
   *
   * @param	array		$taskInfo Reference to the array containing the info used in the add/edit form
   * @param	object		$task When editing, reference to the current task object. Null when adding.
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	array		Array containing all the information pertaining to the additional fields
   * @version       0.0.1
   * @since         0.0.1
   */
  public function getAdditionalFields( array &$taskInfo, $task, SchedulerModuleController $parentObject )
  {
    $additionalFields = array() + $this->getFieldBrowserAdminEmail( $taskInfo, $task, $parentObject ) + $this->getFieldReportMode( $taskInfo, $task, $parentObject ) + $this->getFieldTable( $taskInfo, $task, $parentObject ) + $this->getFieldTestMode( $taskInfo, $task, $parentObject )
    ;

    return $additionalFields;
  }

  /**
   * getFieldTestMode( )  : This method is used to define new fields for adding or editing a task
   *                                           In this case, it adds an email field
   *
   *                    The array is multidimensional, keyed to the task class name and each field's id
   *                    For each field it provides an associative sub-array with the following:
   *                        ['code']        => The HTML code for the field
   *                        ['label']        => The label of the field (possibly localized)
   *                        ['cshKey']        => The CSH key for the field
   *                        ['cshLabel']    => The code of the CSH label
   *
   * @param	array		$taskInfo Reference to the array containing the info used in the add/edit form
   * @param	object		$task When editing, reference to the current task object. Null when adding.
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	array		Array containing all the information pertaining to the additional fields
   * @version       0.0.1
   * @since         0.0.1
   */
  private function getFieldTestMode( array &$taskInfo, $task, $parentObject )
  {
    // IF : field is empty, initialize extra field value
    if ( empty( $taskInfo[ 'browser_testMode' ] ) )
    {
      if ( $parentObject->CMD == 'add' )
      {
        // In case of new task and if field is empty, set to
        $taskInfo[ 'browser_testMode' ] = 'disabled';
      }
      elseif ( $parentObject->CMD == 'edit' )
      {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo[ 'browser_testMode' ] = $task->getTestMode();
      }
      else
      {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo[ 'browser_testMode' ] = '';
      }
    }
    // IF : field is empty, initialize extra field value
    // Write the code for the field
    $fieldID = 'browser_testMode';
    $fieldValue = $taskInfo[ 'browser_testMode' ];
    $labelDisabled = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.testMode.disabled' );
    $labelEnabled = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.testMode.enabled' );
    $selected = array();
    $selected[ 'enabled' ] = null;
    $selected[ 'disabled' ] = null;
    $selected[ $fieldValue ] = ' selected="selected"';

    $fieldCode = '
                      <select name="tx_scheduler[browser_testMode]" id="' . $fieldID . '" size="1" style="width:33em;">
                        <option value="disabled"' . $selected[ 'disabled' ] . '>' . $labelDisabled . '</option>
                        <option value="enabled"' . $selected[ 'enabled' ] . '>' . $labelEnabled . '</option>
                      </select>
                    ';
    $additionalFields = array();
    $additionalFields[ $fieldID ] = array
      (
      'code' => $fieldCode,
      'label' => 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.testMode',
      'cshKey' => '_MOD_tools_txschedulerM1',
      'cshLabel' => $fieldID
    );
    // Write the code for the field

    return $additionalFields;
  }

  /**
   * getFieldTable( )  : This method is used to define new fields for adding or editing a task
   *                                           In this case, it adds an email field
   *
   *                    The array is multidimensional, keyed to the task class name and each field's id
   *                    For each field it provides an associative sub-array with the following:
   *                        ['code']        => The HTML code for the field
   *                        ['label']        => The label of the field (possibly localized)
   *                        ['cshKey']        => The CSH key for the field
   *                        ['cshLabel']    => The code of the CSH label
   *
   * @param	array		$taskInfo Reference to the array containing the info used in the add/edit form
   * @param	object		$task When editing, reference to the current task object. Null when adding.
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	array		Array containing all the information pertaining to the additional fields
   * @version       0.0.1
   * @since         0.0.1
   */
  private function getFieldTable( array &$taskInfo, $task, $parentObject )
  {
    // Initialize extra field value
    if ( empty( $taskInfo[ 'browser_table' ] ) )
    {
      if ( $parentObject->CMD == 'add' )
      {
        // In case of new task and if field is empty, set default table address
        $taskInfo[ 'browser_table' ] = $this->defaultTable;
      }
      elseif ( $parentObject->CMD == 'edit' )
      {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo[ 'browser_table' ] = $task->getTable();
      }
      else
      {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo[ 'browser_table' ] = '';
      }
    }

    // Write the code for the field
    $mustCtrlFields = array(
      'geoupdate'
    );
    $fieldID = 'browser_table';
    $fieldCode = '
                      <select name="tx_scheduler[browser_table]" id="' . $fieldID . '" size="1" style="width:33em;">
                        ' . $this->zzOptionsForTable( $taskInfo[ 'browser_table' ], $mustCtrlFields ) . '
                      </select>
                    ';
    // Write the code for the field

    $additionalFields = array();
    $additionalFields[ $fieldID ] = array(
      'code' => $fieldCode,
      'label' => 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.table',
      'cshKey' => '_MOD_system_txschedulerM1',
      'cshLabel' => $fieldID
    );
    return $additionalFields;
  }

  /**
   * getFieldBrowserAdminEmail( )  : This method is used to define new fields for adding or editing a task
   *                                           In this case, it adds an email field
   *
   *                    The array is multidimensional, keyed to the task class name and each field's id
   *                    For each field it provides an associative sub-array with the following:
   *                        ['code']        => The HTML code for the field
   *                        ['label']        => The label of the field (possibly localized)
   *                        ['cshKey']        => The CSH key for the field
   *                        ['cshLabel']    => The code of the CSH label
   *
   * @param	array		$taskInfo Reference to the array containing the info used in the add/edit form
   * @param	object		$task When editing, reference to the current task object. Null when adding.
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	array		Array containing all the information pertaining to the additional fields
   * @version       0.0.1
   * @since         0.0.1
   */
  private function getFieldBrowserAdminEmail( array &$taskInfo, $task, $parentObject )
  {
    // IF : field is empty, initialize extra field value
    if ( empty( $taskInfo[ 'browser_browserAdminEmail' ] ) )
    {
      if ( $parentObject->CMD == 'add' )
      {
        // In case of new task and if field is empty, set default email address
        $taskInfo[ 'browser_browserAdminEmail' ] = $GLOBALS[ 'BE_USER' ]->user[ 'email' ];
      }
      elseif ( $parentObject->CMD == 'edit' )
      {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo[ 'browser_browserAdminEmail' ] = $task->getAdminmail();
      }
      else
      {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo[ 'browser_browserAdminEmail' ] = '';
      }
    }
    // IF : field is empty, initialize extra field value
    // Write the code for the field
    $fieldID = 'browser_browserAdminEmail';
    $fieldValue = htmlspecialchars( $taskInfo[ 'browser_browserAdminEmail' ] );
    $fieldCode = '<input type="text" name="tx_scheduler[browser_browserAdminEmail]" id="' . $fieldID . '" value="' . $fieldValue . '" size="50" />';
    $additionalFields = array();
    $additionalFields[ $fieldID ] = array
      (
      'code' => $fieldCode,
      'label' => 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.browserAdminEmail',
      'cshKey' => '_MOD_tools_txschedulerM1',
      'cshLabel' => $fieldID
    );
    // Write the code for the field

    return $additionalFields;
  }

  /**
   * getFieldReportMode( )  : This method is used to define new fields for adding or editing a task
   *                                           In this case, it adds an email field
   *
   *                    The array is multidimensional, keyed to the task class name and each field's id
   *                    For each field it provides an associative sub-array with the following:
   *                        ['code']        => The HTML code for the field
   *                        ['label']        => The label of the field (possibly localized)
   *                        ['cshKey']        => The CSH key for the field
   *                        ['cshLabel']    => The code of the CSH label
   *
   * @param	array		$taskInfo Reference to the array containing the info used in the add/edit form
   * @param	object		$task When editing, reference to the current task object. Null when adding.
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	array		Array containing all the information pertaining to the additional fields
   * @version       0.0.1
   * @since         0.0.1
   */
  private function getFieldReportMode( array &$taskInfo, $task, $parentObject )
  {
    // IF : field is empty, initialize extra field value
    if ( empty( $taskInfo[ 'browser_reportMode' ] ) )
    {
      if ( $parentObject->CMD == 'add' )
      {
        // In case of new task and if field is empty, set default email address
        $taskInfo[ 'browser_reportMode' ] = 'update';
      }
      elseif ( $parentObject->CMD == 'edit' )
      {
        // In case of edit, and editing a test task, set to internal value if not data was submitted already
        $taskInfo[ 'browser_reportMode' ] = $task->getReportMode();
      }
      else
      {
        // Otherwise set an empty value, as it will not be used anyway
        $taskInfo[ 'browser_reportMode' ] = '';
      }
    }
    // IF : field is empty, initialize extra field value
    // Write the code for the field
    $fieldID = 'browser_reportMode';
    $fieldValue = $taskInfo[ 'browser_reportMode' ];
    $labelEver = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.reportMode.ever' );
    $labelNever = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.reportMode.never' );
    $labelUpdate = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.reportMode.update' );
    $labelWarn = $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.reportMode.warn' );
    $selected = array();
    $selected[ 'ever' ] = null;
    $selected[ 'never' ] = null;
    $selected[ 'update' ] = null;
    $selected[ 'warn' ] = null;
    $selected[ $fieldValue ] = ' selected="selected"';

    $fieldCode = '
                      <select name="tx_scheduler[browser_reportMode]" id="' . $fieldID . '" size="1" style="width:33em;">
                        <option value="update"' . $selected[ 'update' ] . '>' . $labelUpdate . '</option>
                        <option value="ever"' . $selected[ 'ever' ] . '>' . $labelEver . '</option>
                        <option value="never"' . $selected[ 'never' ] . '>' . $labelNever . '</option>
                        <option value="warn"' . $selected[ 'warn' ] . '>' . $labelWarn . '</option>
                      </select>
                    ';
    $additionalFields = array();
    $additionalFields[ $fieldID ] = array
      (
      'code' => $fieldCode,
      'label' => 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.reportMode',
      'cshKey' => '_MOD_tools_txschedulerM1',
      'cshLabel' => $fieldID
    );
    // Write the code for the field

    return $additionalFields;
  }

  /*   * *********************************************
   *
   * Saving
   *
   * ******************************************** */

  /**
   * saveAdditionalFields( ) : This method is used to save any additional input into the current task object
   *                           if the task class matches
   *
   * @param	array		$submittedData Array containing the data submitted by the user
   * @param	AbstractTask		$task Reference to the current task object
   * @return	void
   * @version       0.0.1
   * @since         0.0.1
   */
  public function saveAdditionalFields( array $submittedData, AbstractTask $task )
  {
    $this->saveFieldTestMode( $submittedData, $task );
    $this->saveFieldTable( $submittedData, $task );
    $this->saveFieldBrowserAdminEmail( $submittedData, $task );
    $this->saveFieldReportMode( $submittedData, $task );
  }

  /**
   * saveFieldTestMode( ) : This method is used to save any additional input into the current task object
   *                           if the task class matches
   *
   * @param	array		$submittedData Array containing the data submitted by the user
   * @param	AbstractTask		$task Reference to the current task object
   * @return	void
   * @version       0.0.1
   * @since         0.0.1
   */
  private function saveFieldTestMode( array $submittedData, AbstractTask $task )
  {
//    $task->browser_reportMode = $submittedData['browser_testMode'];
    $task->setTestMode( $submittedData[ 'browser_testMode' ] );
  }

  /**
   * saveFieldTable( ) : This method is used to save any additional input into the current task object
   *                           if the task class matches
   *
   * @param	array		$submittedData Array containing the data submitted by the user
   * @param	AbstractTask		$task Reference to the current task object
   * @return	void
   * @version       0.0.1
   * @since         0.0.1
   */
  private function saveFieldTable( array $submittedData, AbstractTask $task )
  {
    //$task->browser_table = $submittedData['browser_table'];
    $task->setTable( $submittedData[ 'browser_table' ] );
  }

  /**
   * saveFieldBrowserAdminEmail( ) : This method is used to save any additional input into the current task object
   *                           if the task class matches
   *
   * @param	array		$submittedData Array containing the data submitted by the user
   * @param	AbstractTask		$task Reference to the current task object
   * @return	void
   * @version       0.0.1
   * @since         0.0.1
   */
  private function saveFieldBrowserAdminEmail( array $submittedData, AbstractTask $task )
  {
    $task->setAdminmail( $submittedData[ 'browser_browserAdminEmail' ] );
  }

  /**
   * saveFieldReportMode( ) : This method is used to save any additional input into the current task object
   *                           if the task class matches
   *
   * @param	array		$submittedData Array containing the data submitted by the user
   * @param	AbstractTask		$task Reference to the current task object
   * @return	void
   * @version       0.0.1
   * @since         0.0.1
   */
  private function saveFieldReportMode( array $submittedData, AbstractTask $task )
  {
//    $task->browser_reportMode = $submittedData['browser_reportMode'];
    $task->setReportMode( $submittedData[ 'browser_reportMode' ] );
  }

  /*   * *********************************************
   *
   * Validating
   *
   * ******************************************** */

  /**
   * validateAdditionalFields( ) : This method checks any additional data that is relevant to the specific task
   *                               If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  public function validateAdditionalFields( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

//    $prompt = var_export( $submittedData, true );
//    $parentObject->addMessage( $prompt, FlashMessage::INFO );


    if ( !$this->validateOS( $parentObject ) )
    {
      return false;
    }

    if ( !$this->validateFieldFrequency( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    if ( !$this->validateFieldTestMode( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    if ( !$this->validateFieldTable( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    if ( !$this->validateFieldBrowserAdminEmail( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    if ( !$this->validateFieldReportMode( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    if ( !$this->validateFieldStart( $submittedData, $parentObject ) )
    {
      $bool_isValidatingSuccessful = false;
    }

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateFieldFrequency( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldFrequency( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

    $submittedData[ 'frequency' ] = ( int ) $submittedData[ 'frequency' ];

    if ( $submittedData[ 'frequency' ] > ( 60 * 60 * 24 ) )
    {
      $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.enterFrequency' );
      $parentObject->addMessage( $prompt, FlashMessage::WARNING );
    }

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateFieldTestMode( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldTestMode( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

    // Messages depending on mode
    switch ( $submittedData[ 'browser_testMode' ] )
    {
      case( 'enabled' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.testMode.enabled' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::INFO );
        break;
      case( 'disabled' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.testMode.disabled' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::INFO );
        break;
      default:
        $bool_isValidatingSuccessful = false;
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.testMode.undefined' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::ERROR );
        break;
    }
    // Messages depending on mode

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateFieldTable( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldTable( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

    switch ( true )
    {
      case( empty( $submittedData[ 'browser_table' ] ) ):
      case( $submittedData[ 'browser_table' ] == $this->defaultTable ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.enterTable' );
        $parentObject->addMessage( $prompt, FlashMessage::ERROR );
        $bool_isValidatingSuccessful = false;
        break;
      default:
        $bool_isValidatingSuccessful = true;
        break;
    }

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateFieldBrowserAdminEmail( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldBrowserAdminEmail( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

    $submittedData[ 'browser_browserAdminEmail' ] = trim( $submittedData[ 'browser_browserAdminEmail' ] );

    if ( empty( $submittedData[ 'browser_browserAdminEmail' ] ) )
    {
      $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.enterBrowserAdminEmail' );
      $parentObject->addMessage( $prompt, FlashMessage::ERROR );
      $bool_isValidatingSuccessful = false;
    }

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateOS( ) : This method checks any additional data that is relevant to the specific task
   *                               If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  public function validateOS( SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;
    // #i0006, 130413, dwildt, 1+
    return $bool_isValidatingSuccessful;

    // #i0006, 130413, dwildt, -
//      // SWITCH : OS of the server
//    switch( strtolower( PHP_OS ) )
//    {
//      case( 'linux' ):
//          // Linux is proper: Follow the workflow
//        break;
//      default:
//        $bool_isValidatingSuccessful = false;
//        $prompt = $this->msgPrefix . ': ' . $GLOBALS['LANG']->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.osIsNotSupported' );
//        $prompt = str_replace( '###PHP_OS###', PHP_OS, $prompt );
//        $parentObject->addMessage( $prompt, FlashMessage::ERROR );
//    }
//      // SWITCH : OS of the server
//
//    return $bool_isValidatingSuccessful;
    // #i0006, 130413, dwildt, -
  }

  /**
   * validateFieldReportMode( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldReportMode( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;

    // Messages depending on mode
    switch ( $submittedData[ 'browser_reportMode' ] )
    {
      case( 'ever' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.reportMode.ever' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::INFO );
        break;
      case( 'never' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.reportMode.never' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::WARNING );
        break;
      case( 'update' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.reportMode.update' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::INFO );
        break;
      case( 'warn' ):
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.reportMode.warn' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::INFO );
        break;
      default:
        $bool_isValidatingSuccessful = false;
        $prompt = $this->msgPrefix . ': ' . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.reportMode.undefined' );
        ;
        $parentObject->addMessage( $prompt, FlashMessage::ERROR );
        break;
    }
    // Messages depending on mode

    return $bool_isValidatingSuccessful;
  }

  /**
   * validateFieldStart( )  : This method checks any additional data that is relevant to the specific task
   *                                     If the task class is not relevant, the method is expected to return TRUE
   *
   * @param	array		$submittedData Reference to the array containing the data submitted by the user
   * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController		$parentObject Reference to the calling object (Scheduler's BE module)
   * @return	boolean		TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @version       0.0.1
   * @since         0.0.1
   */
  private function validateFieldStart( array &$submittedData, SchedulerModuleController $parentObject )
  {
    $bool_isValidatingSuccessful = true;
    return $bool_isValidatingSuccessful;

    $submittedData[ 'start' ] = ( int ) $submittedData[ 'start' ];

    $inAnHour = time() + ( 60 * 60 );

    if ( $submittedData[ 'start' ] < $inAnHour )
    {
      $prompt = $this->msgPrefix
              . ': '
              . $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:msg.enterStart' )
      ;
      $parentObject->addMessage( $prompt, FlashMessage::ERROR );
      $bool_isValidatingSuccessful = false;
    }

    return $bool_isValidatingSuccessful;
  }

  /**
   * This method returns the HTML select options with table values
   * In this case, it adds an email field
   *
   * @param string $currentTable
   * @return string $options
   * @version 7.4.5
   * @since   7.4.5
   */
  private function zzOptionsForTable( $currentTable, $mustCtrlFields )
  {

    $selected = array();
    //$currentTable = 'tx_org_job';
    $selected[ $currentTable ] = ' selected="selected"';

    $res = $GLOBALS[ 'TYPO3_DB' ]->sql_query( 'SHOW tables;' );
    $rows = array();
    $rows[] = '<option value=""></option>';
    while ( ($row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_row( $res ) ) )
    {
      $dbTable = $row[ 0 ];
      switch ( TRUE )
      {
        case(!$this->zzOptionsForTableCtrl( $dbTable, $mustCtrlFields )):
          continue 2;
        default:
          // follow the workflow
          break;
      }
      if ( !isset( $selected[ $dbTable ] ) )
      {
        $selected[ $dbTable ] = NULL;
      }
      $rows[] = '<option value="' . $dbTable . '"' . $selected[ $dbTable ] . '>' . $dbTable . '</option>';
    }
    $GLOBALS[ 'TYPO3_DB' ]->sql_free_result( $res );
    $options = implode( PHP_EOL, $rows );

    return $options;
  }

  /**
   * This method checks any additional data that is relevant to the specific task
   * If the task class is not relevant, the method is expected to return TRUE
   *
   * @param string $table
   * @param string $field
   * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject Reference to the calling object (Scheduler's BE module)
   * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
   * @access private
   * @version 1.1.0
   * @since   1.1.0
   */
  private function zzOptionsForTableCtrl( $table, $mustCtrlFields )
  {
    //var_dump( __METHOD__, __LINE__, $table, $mustCtrlFields );
    foreach ( ( array ) $mustCtrlFields as $field )
    {
      if ( !isset( $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'tx_browser' ][ $field ] ) )
      {
        return FALSE;
      }
    }

    return TRUE;
  }

}
