<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* The class tx_browser_pi1_download bundles methods for downloading datas
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    tx_browser
*
* @version 3.9.3
* @since 3.9.3
*/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_browser_pi1_download
 *   83:     function __construct($pObj)
 *
 *              SECTION: typeNum
 *  118:     public function set_typeNum( )
 *
 *              SECTION: CSV helper
 *  189:     public function csv_init_config( )
 *  225:     public function csv_value( $value )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi1_download
{
    // [Integer] Number of the current typeNum
  var $int_typeNum    = null;
    // [String] Name of the current typeNum
  var $str_typeNum    = null;









  /**
 * Constructor. The method initiate the parent object
 *
 * @param object    The parent object
 * @return  void
 */
  function __construct($pObj)
  {
    $this->pObj = $pObj;
  }









  /***********************************************
  *
  * Main
  *
  **********************************************/









  /**
 * download( ): 
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function download( )
  {
      // RETURN typeNum isn't the csv typeNum
    if( $this->str_typeNum != 'download' )
    {
      return;
    }
      // RETURN typeNum isn't the csv typeNum

    require_once( PATH_t3lib . 'class.t3lib_basicfilefunc.php' );
      // Initialize new fileFunc object
		$this->fileFunc = t3lib_div::makeInstance( 't3lib_basicFileFunctions' );

    if( ! $this->download_init( ) )
    {
      return 'ERROR $this->download_init( )';
    }

    $this->statistics( 'plus' );
      // EXIT in case of success!
    $prompt_error = $this->delivery_andExit( );
    
    $this->statistics( 'minus' );

    return $prompt_error;
  }









  /**
 * download_init( ): Init the globals $csv_devider, $csv_enclosure and $csv_striptag
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function download_init( )
  {
    // Parameter auswerten
    // Dateinamen und Pfad holen
    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
    if ( ! ( $pos === false ) )
    {
      var_dump(__METHOD__. ' (' . __LINE__ . '): ' . $this->pObj->piVars );
    }

    return;
    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['devider.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['devider.']['stdWrap.'];
    $this->csv_devider    = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['enclosure.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['enclosure.']['stdWrap.'];
    $this->csv_enclosure  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
    $cObj_name            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['strip_tag.']['stdWrap'];
    $cObj_conf            = $this->pObj->conf['flexform.']['viewList.']['csvexport.']['strip_tag.']['stdWrap.'];
    $this->csv_striptag   = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);
  }









  /***********************************************
  *
  * Delivery
  *
  **********************************************/









  /**
 * delivery():
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function delivery_andExit( )
  {
    return $this->delivery_sendFile( );
  }









  /**
 * delivery_sendFile():
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function delivery_sendFile( )
  {
		// Send file to browser
		$file = t3lib_div::getFileAbsFileName( $this->filePath . $download[0]['file'] );

    if( ! file_exists($file) )
    {
      return 'The file ' . $file . ' does not exist.';
    }

		$fileInformation = $this->fileFunc->getTotalFileInfo( $file );

      //header('Content-type: text/csv');
      //header('Content-type: application/msexcel');
      //header('Content-Disposition: attachment; filename="downloaded.csv"');
		header( 'Content-Description: Modern Downloads File Transfer' );
		header( 'Content-type: application/force-download' );
		header( 'Content-Disposition: attachment; filename="' . $download[0]['file'] . '"' );
		header( 'Content-Length: ' . $fileInformation['size'] );
    // Read the file and write it to the output buffer.
		@readfile( $file ) || die ( __METHOD__ . ' (' . __LINE__ . '): ' . readfile( $file ) );
		exit;
  }








  /***********************************************
  *
  * typeNum
  *
  **********************************************/









  /**
 * set_typeNum(): Set the globals $int_typeNum and $str_typeNum.
 *                The globals are needed by other classes while runtime.
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  public function set_typeNum( )
  {
      // Get the typeNum from the current URL parameters
    $typeNum = (int) t3lib_div::_GP( 'type' );

      // RETURN typeNum is 0 or empty
    if( empty ( $typeNum ) )
    {
      return;
    }
      // RETURN typeNum is 0 or empty

      // Check the proper typeNum
    $conf = $this->pObj->conf;
    switch (true)
    {
      case( $typeNum == $conf['download.']['page.']['typeNum'] ) :
          // Given typeNum is the internal typeNum for download
        $this->int_typeNum = $typeNum;
        $this->str_typeNum = 'download';
        break;
      default :
          // Given typeNum isn't the internal typeNum for download
        $this->str_typeNum = 'undefined';
    }
      // Check the proper typeNum

      // DRS - Development Reporting System
    if ($this->pObj->b_drs_download)
    {
      t3lib_div::devLog('[INFO/download] typeNum is ' . $typeNum . '. Name is ' . $this->str_typeNum . '.', $this->pObj->extKey, 0);
    }
      // DRS - Development Reporting System

  }









  /***********************************************
  *
  * Statistics
  *
  **********************************************/









  /**
 * download_statistics():
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics( )
  {
    //$this->statistics_download( )
    //$this->statistics_downloadByVisit( )
  }









  /**
 * statistics_download():
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics_download( )
  {
  }









  /**
 * statistics_downloadByVisit():
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics_downloadByVisit( )
  {
  }

  
  
  
  
  
  
  
  
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_download.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_download.php']);
}
?>