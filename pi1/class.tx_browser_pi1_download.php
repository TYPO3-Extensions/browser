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

    // [Boolean] Is dwonloading allowed?
  var $bool_downloadsAllowed  = false;

    // [String] view: list || single
  var $view   = null;
    // [Integer] mode (index) of the current view
  var $mode   = null;
    // [String] table: label of the current table
  var $table  = null;
    // [Integer] uid: uid of the current record
  var $uid    = null;
    // [String] field: label of the field with the files
  var $field  = null;
    // [Integer] key: index of the file
  var $key    = null;








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
      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN typeNum isn't the download typeNum

    if( $this->str_typeNum != 'download' )
    {
      return;
    }
      // RETURN typeNum isn't the download typeNum



      //////////////////////////////////////////////////////////////////////////
      //
      // Init global class vars

    $prompt_error = $this->download_init( );
    if( $prompt_error )
    {
      return $prompt_error;
    }
      // Init global class vars



      //////////////////////////////////////////////////////////////////////////
      //
      // Check table, field and TypoScript

    $prompt_error = $this->download_check( );
    if( $prompt_error )
    {
      return $prompt_error;
    }
      // Check table, field and TypoScript




    $this->statistics( 'plus' );
      // EXIT in case of success!
    $prompt_error = $this->delivery_andExit( );
    
    $this->statistics( 'minus' );

    return $prompt_error;
  }









  /**
 * download_init( ):
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function download_check( )
  {
      // Does the view and the mode exist?
    if( ! isset( $this->pObj->conf['views.'][$this->view . '.'][$this->mode . '.']['select'] ) )
    {
      $prompt = ''.
      'Security check: TypoScript property ' .
      'plugin.tx_browser_pi1.views.' . $this->view . '. ' . $this->mode . '.select doesn\t exist.<br />' .
      __METHOD__ . ' (' . __LINE__ . ')';
      return $prompt;
    }

      // Is table.field part of the select?
    $select = $this->pObj->conf['views.'][$this->view . '.'][$this->mode . '.']['select'];
    $pos    = strpos($select, $this->table . '.' . $this->field );
    if ( $pos === false )
    {
      $prompt = ''.
      'Security check: ' . $this->table . '.' . $this->field . ' ' .
      'isn\'t part of plugin.tx_browser_pi1.views.' . $this->view . '.' . $this->mode . '.select.<br />' .
      __METHOD__ . ' (' . __LINE__ . ')';
      return $prompt;
    }

      // Is table.field part of the TCA? Is field a file type?
      // Load the TCA for the current table
    $this->pObj->objZz->loadTCA($this->table);
      // Check, if the field is an element of the current table
    if( ! isset($GLOBALS['TCA'][$this->table]['columns'][$this->field] ) )
    {
      $prompt = ''.
      'Security check: ' . $this->table . '.' . $this->field . ' ' .
      'isn\'t part of the TCA.<br />' .
      __METHOD__ . ' (' . __LINE__ . ')';
      return $prompt;
    }

    return false;
  }









  /**
 * download_init( ):
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function download_init( )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Downloading isn\'t allowed

    $cObj_name                    = $this->pObj->conf['flexform.']['sDEF.']['downloads.']['allowed'];
    $cObj_conf                    = $this->pObj->conf['flexform.']['sDEF.']['downloads.']['allowed.'];
    $this->bool_downloadsAllowed  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    if( ! $this->bool_downloadsAllowed )
    {
      $prompt_01 = 'Downloading isn\'t allowed.';
      $prompt_02 = 'Please enable the Flexform/TypoScript property flexform.sDEF.downloads.allowed.';
      if ( $this->pObj->b_drs_download )
      {
        t3lib_div::devlog( '[INFO/DOWNLOAD] ' . $prompt_01, $this->pObj->extKey, 0 );
        t3lib_div::devlog( '[HELP/DOWNLOAD] ' . $prompt_02, $this->pObj->extKey, 1 );
      }
      return $prompt_01 . ' ' . $prompt_02;
    }
      // RETURN: Downloading isn\'t allowed



      //////////////////////////////////////////////////////////////////////////
      //
      // Set all needed informations global

      // piVars['file']: i.e. single.301.tx_org_doc.9.documents.0
    $arr_file = explode('.' , $this->pObj->piVars['file'] );
      // view: list || single
    $this->view   = $arr_file[0];
      // mode (index) of the current view
    $this->mode   = $arr_file[1];
      // table: label of the current table
    $this->table  = $arr_file[2];
      // uid: uid of the current record
    $this->uid    = (int) $arr_file[3];
      // field: label of the field with the files
    $this->field  = $arr_file[4];
      // key: index of the file
    $this->key    = (int) $arr_file[5];
      // Set all needed informations global

    return false;

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
      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Any upload folder isn't configured

    $uploadFolder = $GLOBALS['TCA'][$this->table]['columns'][$this->field]['config']['uploadfolder'];
    if( empty( $uploadFolder ) )
    {
      $prompt_01 =  'Any upload folder is configured in the TCA.';
      $prompt_02 =  'Please take care of a proper configuration: ';
                    '$TCA. ' . $this->table . 'columns.' . $this->field . 'config.uploadfolder.';
      if ( $this->pObj->b_drs_download )
      {
        t3lib_div::devlog( '[ERROR/DOWNLOAD] ' . $prompt_01, $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[HELP/DOWNLOAD] ' . $prompt_02, $this->pObj->extKey, 1 );
      }
      return $prompt_01 . ' ' . $prompt_02;
    }
      // RETURN: Any upload folder isn't configured



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the absoluite path

		$str_pathAbsolute = t3lib_div::getFileAbsFileName( $uploadFolder );
    $str_pathAbsolute = rtrim( $str_pathAbsolute, '/' ) . '/';
      // Get the absoluite path


 
      //////////////////////////////////////////////////////////////////////////
      //
      // Get the file

    $select_fields  = $this->field;
    $from_table     = $this->table;
    $where_clause   = 'uid = ' . $this->uid;
    $query = $GLOBALS['TYPO3_DB']->SELECTquery( $select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '' );
    if ( $this->pObj->b_drs_sql || $this->pObj->b_drs_download )
    {
      t3lib_div::devlog( '[INFO/SQL+DOWNLOAD] ' . $query, $this->pObj->extKey, 3 );
    }

    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
    if ( ! ( $pos === false ) )
    {
      var_dump(__METHOD__. ' (' . __LINE__ . '): ' , $str_pathAbsolute, $query );
    }

    return;

    if( ! file_exists( $file ) )
    {
      $prompt = 'The file \'' . $file . '\' does not exist.';
      if ( $this->pObj->b_drs_download )
      {
        t3lib_div::devlog( '[ERROR/DOWNLOAD] ' . $prompt, $this->pObj->extKey, 3 );
      }
      return $prompt;
    }

    require_once( PATH_t3lib . 'class.t3lib_basicfilefunc.php' );
      // Initialize new fileFunc object
		$this->fileFunc = t3lib_div::makeInstance( 't3lib_basicFileFunctions' );

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
      if( $this->pObj->b_drs_download )
      {
        t3lib_div::devLog('[INFO/DOWNLOAD] typeNum is 0 or empty.', $this->pObj->extKey, 0);
      }
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
    if( $this->pObj->b_drs_download )
    {
      t3lib_div::devLog('[INFO/DOWNLOAD] typeNum is \'' . $typeNum . '\'. Name is \'' . $this->str_typeNum . '\'.', $this->pObj->extKey, 0);
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