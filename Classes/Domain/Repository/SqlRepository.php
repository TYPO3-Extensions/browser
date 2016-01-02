<?php

namespace Netzmacher\Browser\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015 - 2016 -  Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * SqlRepository
 *
 * @package browser
 * @author Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @version 7.4.0
 * @since 7.4.0
 * @internal #i0215
 */
class SqlRepository extends Repository
{

  /**
   * execute( ) : Get rows by a SQL query
   *
   * @param string $sQuery
   * @return array $aRows
   */
  public function SELECT( $sQuery )
  {
    $oQuery = $this->createQuery();
    $aRows = $oQuery->statement( $sQuery )->execute( true );
    return $aRows;
  }

  /**
   * execute( ) : Get rows by a SQL query
   *
   * @param string $sQuery
   * @return array $aRows
   */
  public function UPDATE( $sQuery )
  {
    $oQuery = $this->createQuery();
    $oQuery->statement( $sQuery )->execute( true );
  }

}