<?php

namespace Netzmacher\Browser\Scheduler\Test;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2008 Markus Friedrich (markus.friedrich@dkd.de)
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
 * Class "tx_browser_TestTask" provides testing procedures
 *
 * @author        Markus Friedrich <markus.friedrich@dkd.de>
 * @package        TYPO3
 * @subpackage    tx_browser
 */
class Task extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{

  /**
   * An email address to be used during the process
   *
   * @var string $email
   */
  var $browser_browserAdminEmail;

  /**
   * Function executed from the Scheduler.
   * Sends an email
   *
   * @return	boolean
   */
  public function execute()
  {
    global $TYPO3_CONF_VARS;

    $success = FALSE;

    if ( !empty( $this->browser_browserAdminEmail ) )
    {
      // If an email address is defined, send a message to it
      // NOTE: the TYPO3_DLOG constant is not used in this case, as this is a test task
      // and debugging is its main purpose anyway
      GeneralUtility::devLog( '[tx_browser_TestTask]: Test email sent to "' . $this->browser_browserAdminEmail . '"', 'browser', 0 );

      // Get execution information
      $exec = $this->getExecution();

      // Get call method
      if ( basename( PATH_thisScript ) == 'cli_dispatch.phpsh' )
      {
        $calledBy = 'CLI module dispatcher';
        $site = '-';
      }
      else
      {
        $calledBy = 'TYPO3 backend';
        $site = GeneralUtility::getIndpEnv( 'TYPO3_SITE_URL' );
      }

      $start = $exec->getStart();
      $end = $exec->getEnd();
      $interval = $exec->getInterval();
      $multiple = $exec->getMultiple();
      $cronCmd = $exec->getCronCmd();
      $mailBody = 'BROWSER TEST-TASK' . LF
              . '- - - - - - - - - - - - - - - -' . LF
              . 'UID: ' . $this->taskUid . LF
              . 'Sitename: ' . $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'SYS' ][ 'sitename' ] . LF
              . 'Site: ' . $site . LF
              . 'Called by: ' . $calledBy . LF
              . 'tstamp: ' . date( 'Y-m-d H:i:s' ) . ' [' . time() . ']' . LF
              . 'start: ' . date( 'Y-m-d H:i:s', $start ) . ' [' . $start . ']' . LF
              . 'end: ' . ((empty( $end )) ? '-' : (date( 'Y-m-d H:i:s', $end ) . ' [' . $end . ']')) . LF
              . 'interval: ' . $interval . LF
              . 'multiple: ' . ($multiple ? 'yes' : 'no') . LF
              . 'cronCmd: ' . ($cronCmd ? $cronCmd : 'not used');

      // Prepare mailer and send the mail
      try
      {
        /** @var $mailer TYPO3\\CMS\\Core\\Mail\\MailMessage */
        $mailer = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Mail\\MailMessage' );
        $mailer->setFrom( array( $this->browser_browserAdminEmail => 'BROWSER TEST-TASK' ) );
        $mailer->setReplyTo( array( $this->browser_browserAdminEmail => 'BROWSER TEST-TASK' ) );
        $mailer->setSubject( 'BROWSER TEST-TASK' );
        $mailer->setBody( $mailBody );
        $mailer->setTo( $this->browser_browserAdminEmail );
        $mailsSend = $mailer->send();
        $success = ($mailsSend > 0);
      }
      catch ( Exception $e )
      {
        throw new t3lib_exception( $e->getMessage() );
      }

      if ( $TYPO3_CONF_VARS[ 'MAIL' ][ 'transport' ] != 'mbox' )
      {
        return;
      }

      $prompt = 'The e-mail hasn\'t left your local server. It is stored in your mbox at ' . $TYPO3_CONF_VARS[ 'MAIL' ][ 'transport_mbox_file' ];
      $message = GeneralUtility::makeInstance(
                      'TYPO3\\CMS\\Core\\Messaging\\FlashMessage', $prompt, null, // the header is optional
                      \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE, // the severity is optional as well and defaults to \TYPO3\CMS\Core\Messaging\FlashMessage::OK
                      FALSE // optional, whether the message should be stored in the session or only in the \TYPO3\CMS\Core\Messaging\FlashMessageQueue object (default is FALSE)
      );
      \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage( $message );
      $GLOBALS[ 'BE_USER' ]->simplelog( $prompt, $this->_taskLabel, 0 );
    }
    else
    {
      // No email defined, just log the task
      GeneralUtility::devLog( '[tx_browser_TestTask]: No email address given', 'browser', 2 );
    }

    return $success;
  }

  /**
   * This method returns the destination mail address as additional information
   *
   * @return	string		Information to display
   */
  public function getAdditionalInformation()
  {
    return $GLOBALS[ 'LANG' ]->sL( 'LLL:EXT:browser/Resources/Private/Language/Scheduler/locallang.xml:label.browserAdminEmail' ) . ': ' . $this->browser_browserAdminEmail;
  }

}
