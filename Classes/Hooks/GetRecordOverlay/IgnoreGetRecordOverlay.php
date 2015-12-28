<?php

namespace Netzmacher\Browser\Hooks\GetRecordOverlay;

/*
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2015 Dirk Wildt, http://wildt.at.die-netzmacher.de
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

use TYPO3\CMS\Frontend\Page\PageRepositoryGetRecordOverlayHookInterface;

/**
 * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @version 7.3.0
 * @since 7.3.0
 */
class IgnoreGetRecordOverlay implements PageRepositoryGetRecordOverlayHookInterface
{

  /**
   * Enables to handle a row before a record overlay. If row contains an element called 'IgnoreGetRecordOverlay',
   * row will stored in $GLOBALS[ 'tx_browser' ][ 'row_preProcess' ]. It can called by method
   * getRecordOverlay_postProcess() below. This enables to ignore the API localisation effects.
   *
   * @param string $table
   * @param array $row
   * @param integer $sys_language_content
   * @param string $OLmode
   * @param \TYPO3\CMS\Frontend\Page\PageRepository $parent
   * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
   * @version 7.3.0
   * @since 7.3.0
   */
  public function getRecordOverlay_preProcess( $table, &$row, &$sys_language_content, $OLmode, \TYPO3\CMS\Frontend\Page\PageRepository $parent )
  {
    // RETURN: row doesn't contain the element 'IgnoreGetRecordOverlay'
    if ( !isset( $row[ 'IgnoreGetRecordOverlay' ] ) )
    {
      return;
    }

    // Save row for post processing
    $GLOBALS[ 'tx_browser' ][ 'row_preProcess' ] = $row;
  }

  /**
   * Enables to handle a row auf a record overlay. If row from pre process contains an element called 'IgnoreGetRecordOverlay',
   * current row will reset to row from preProcess. This enables to reset a record overlay. By default a row will
   * removed, if it doesn't match the localisation requirements.
   *
   * @param string $table
   * @param array $row
   * @param integer $sys_language_content
   * @param string $OLmode
   * @param \TYPO3\CMS\Frontend\Page\PageRepository $parent
   * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
   * @version 7.3.0
   * @since 7.3.0
   */
  public function getRecordOverlay_postProcess( $table, &$row, &$sys_language_content, $OLmode, \TYPO3\CMS\Frontend\Page\PageRepository $parent )
  {
    // RETURN: row from pre process doesn't contain the element 'IgnoreGetRecordOverlay'
    if ( !isset( $GLOBALS[ 'tx_browser' ][ 'row_preProcess' ] ) )
    {
      return;
    }

    // Reset row to row of the pre process
    $row = $GLOBALS[ 'tx_browser' ][ 'row_preProcess' ];
    unset( $row[ 'IgnoreGetRecordOverlay' ] );
    unset( $GLOBALS[ 'tx_browser' ][ 'row_preProcess' ] );
  }

}
