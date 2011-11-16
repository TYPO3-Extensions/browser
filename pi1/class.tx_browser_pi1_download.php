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
 * download( ): Main method for downloading the requested file.
 *              If there is an failure, the method retirns a failure prompt.
 *              If there is success, this class will send the header, the file
 *              and will exit the PHP script.
 *
 * @return  string    Prompt, in case of a failure
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
      $prompt_error = ''.
      '<div style="border:1em solid red;text-align:center;padding:1em;"><h1>TYPO3 Browser</h1>' . $prompt_error . '</div>';
      return $prompt_error;
    }
      // Init global class vars



      //////////////////////////////////////////////////////////////////////////
      //
      // Check table, field and TypoScript

    $prompt_error = $this->download_check( );
    if( $prompt_error )
    {
      $prompt_error = ''.
      '<div style="border:1em solid red;text-align:center;padding:1em;"><h1>TYPO3 Browser</h1>' . $prompt_error . '</div>';
      return $prompt_error;
    }
      // Check table, field and TypoScript




    $this->statistics( '+' );
      // EXIT in case of success!
    $prompt_error = $this->sendFileAndExit( );
    
    $this->statistics( '-' );

    $prompt_error = ''.
    '<div style="border:1em solid red;text-align:center;padding:1em;"><h1>TYPO3 Browser</h1>' . $prompt_error . '</div>';
    return $prompt_error;
  }









  /**
 * download_check( ): The method checks
 *                    * view and mode has to exist
 *                    * table.field has to be a part of the TypoScript select property
 *                    * table.field has to be configured in the TCA
 *
 * @return  string    Prompt, in case of a failure
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
 * download_init( ):  The method sets some class variables
 *                    The method checks, if download is allowed. User have to allow the download
 *                    in the flexform / TypoScript
 *                    The method explodes the given URL.
 *                    Example:
 *                    * URL is: single.301.tx_org_doc.9.documents.0
 *                    * $this->view   = single
 *                    * $this->mode   = 301
 *                    * $this->table  = tx_org_doc
 *                    * $this->uid    = 9
 *                    * $this->field  = documents
 *                    * $this->key    = 0
 *
 * @return  string    Prompt, in case of any downloading isn't allowed
 * @version 3.9.3
 * @since 3.9.3
 */
  private function download_init( )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: Downloading isn\'t allowed

    $cObj_name                    = $this->pObj->conf['flexform.']['sDEF.']['downloads.']['enabled'];
    $cObj_conf                    = $this->pObj->conf['flexform.']['sDEF.']['downloads.']['enabled.'];
    $this->bool_downloadsAllowed  = $this->pObj->cObj->cObjGetSingle($cObj_name, $cObj_conf);

    if( ! $this->bool_downloadsAllowed )
    {
      $prompt_01 = 'This current TYPO3 Browser plugin hasn\'t any permission to handle the download page object.';
      $prompt_02 = 'Please enable the property in the flexform tab [General] or in your TypoScript. See flexform.sDEF.downloads.enabled.';
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

    return;

  }








  /***********************************************
  *
  * typeNum
  *
  **********************************************/









  /**
 * set_typeNum(): Set the class variables $int_typeNum and $str_typeNum.
 *                The class variables are needed by other classes while runtime.
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
  * Sending
  *
  **********************************************/









  /**
 * sendFileAndExit(): The method sends the file and exit in case of success
 *                    The method checks:
 *                    * upload folder is proper:  if there is a configuration in the TCA
 *                    * sql result is proper:     if result is exactly one row
 *                    * file is proper:           if the file does exist
 *
 * @return  string    Prompt, in case of a failure
 * @version 3.9.3
 * @since 3.9.3
 */
  private function sendFileAndExit( )
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
      // Build and execute the query for getting files

      // Build the query
    $uid            = $this->pObj->objLocalise->get_localisedUid( $this->table, $this->uid );
    $select_fields  = $this->field;
    $from_table     = $this->table;
    $where_clause   = 'uid = ' . $uid;
    $enablefields   = $this->pObj->cObj->enableFields( $this->table );
    if( $enablefields )
    {
      $where_clause = $where_clause . $enablefields;
    }
    $query = $GLOBALS['TYPO3_DB']->SELECTquery( $select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '' );
    if( $this->pObj->b_drs_sql || $this->pObj->b_drs_download )
    {
      t3lib_div::devlog( '[INFO/SQL+DOWNLOAD] ' . $query, $this->pObj->extKey, 0 );
    }
      // Build the query

      // Execute the query
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery( $select_fields, $from_table, $where_clause, $groupBy = '', $orderBy = '', $limit = '' );
      // Execute the query
      // Build and execute the query for getting files



      //////////////////////////////////////////////////////////////////////////
      //
      // Evaluate the query

      // LOOP count rows of the SQL result
    $rows             = array( );
    $int_rows_counter = 0;
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $rows[$int_rows_counter] = $row;
      $int_rows_counter++;
    }
      // LOOP count rows of the SQL result

      // Free the SQL result
    $GLOBALS['TYPO3_DB']->sql_free_result($res);

      // RETURN: There are 0 ore more than one rows
    if( $int_rows_counter != 1 )
    {
      $prompt_01 =  'query: ' . $query;
      $prompt_02 =  'RETURN: Result are #' . $int_rows_counter . ' rows.';
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog( '[INFO/DOWNLOAD] '   . $prompt_01, $this->pObj->extKey, 3 );
        t3lib_div::devlog( '[ERROR/DOWNLOAD] '  . $prompt_02, $this->pObj->extKey, 0 );
      }
      return $prompt_01 . '<br />' . $prompt_02;
    }
      // RETURN: There are 0 ore more than one rows
      // Evaluate the query



      //////////////////////////////////////////////////////////////////////////
      //
      // Get the file

    $arr_files    = explode(',', $rows[0][$this->field]);
    $str_file     = $arr_files[$this->key];

      // RETURN: There is no file whith the current key
    if( empty( $str_file ) )
    {
      $prompt_01 =  'There isn\'t any file. Key is \'' . $this->key . '\'.';
      if ($this->pObj->b_drs_error)
      {
        t3lib_div::devlog( '[ERROR/DOWNLOAD] '  . $prompt_01, $this->pObj->extKey, 0 );
      }
      return $prompt_01;
    }
      // RETURN: There is no file whith the current key

      // RETURN: file doesn't exist
    $str_pathFile = $str_pathAbsolute . $str_file;
    if( ! file_exists( $str_pathFile ) )
    {
      $prompt = 'The file \'' . $str_pathFile . '\' does not exist.';
      if ( $this->pObj->b_drs_download )
      {
        t3lib_div::devlog( '[ERROR/DOWNLOAD] ' . $prompt, $this->pObj->extKey, 3 );
      }
      return $prompt;
    }
      // RETURN: file doesn't exist
      // Get the file



      //////////////////////////////////////////////////////////////////////////
      //
      // filefunc object

      // Require fileFunc class
    require_once( PATH_t3lib . 'class.t3lib_basicfilefunc.php' );
      // Initialize new fileFunc object
		$this->fileFunc = t3lib_div::makeInstance( 't3lib_basicFileFunctions' );
      // Get fileinfo
		$fileInfo       = $this->fileFunc->getTotalFileInfo( $str_pathFile );
      // filefunc object



      //////////////////////////////////////////////////////////////////////////
      //
      // Set the header

    $arr_header = null;

      // Fileextension correspondends with a defined mimetype
    $str_fileext = $fileInfo['fileext'];
    if( isset ( $this->pObj->conf['download.']['mimetypes.']['fileext.'][$str_fileext] ) )
    {
      $str_application    = $this->pObj->conf['download.']['mimetypes.']['fileext.'][$str_fileext];
  		$arr_header['type'] = 'Content-type: ' . $str_application;
    }
      // Fileextension correspondends with a defined mimetype

    $arr_header['description']  = 'Content-Description: TYPO3 Browser Download Modul';
    $arr_header['disposition']  = 'Content-Disposition: attachment; filename="' . $str_file . '"';
    $arr_header['length']       = 'Content-Length: ' . $fileInfo['size'];

    $str_header = implode( ' // ', $arr_header );

      // DRS - Development Reporting System
    if ( $this->pObj->b_drs_download )
    {
      t3lib_div::devlog( '[INFO/DOWNLOAD] file name: '      . $str_file,          $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/DOWNLOAD] file path: '      . $fileInfo['path'],  $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/DOWNLOAD] file size: '      . $fileInfo['size'],  $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/DOWNLOAD] file extension: ' . $str_fileext,       $this->pObj->extKey, 0 );
      t3lib_div::devlog( '[INFO/DOWNLOAD] header: '         . $str_header,        $this->pObj->extKey, 0 );
    }
      // DRS - Development Reporting System

      // Loop header
    foreach( $arr_header as $str_header )
    {
      header( $str_header );
    }
      // Loop header
      // Set the header



//    $pos = strpos($this->pObj->str_developer_csvIp, t3lib_div :: getIndpEnv('REMOTE_ADDR'));
//    if ( ! ( $pos === false ) )
//    {
//      var_dump(__METHOD__. ' (' . __LINE__ . '): ' , $fileInfo, $fileInfo['fileext'], $this->pObj->conf['download.']['mimetypes.']['fileext.'][$fileInfo['fileext']], $arr_header );
//      return;
//    }



      //////////////////////////////////////////////////////////////////////////
      //
      // Send the header and the file

      // Read the file and write it to the output buffer.
		@readfile( $str_pathFile ) || die ( __METHOD__ . ' (' . __LINE__ . '): ' . readfile( $str_pathFile ) );
		exit;
      // Send the header and the file
  }









  /***********************************************
  *
  * Statistics
  *
  **********************************************/









  /**
 * download_statistics():
   *
   * @param string    $operator: + or -
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics( $operator )
  {
      //////////////////////////////////////////////////////////////////////////
      //
      // Set status of the statistics module and init it

    $this->pObj->objStat->statisticsIsEnabled( );
      // Set status of the statistics module and init it



      //////////////////////////////////////////////////////////////////////////
      //
      // RETURN: statistics module is disabled

    if( ! $this->pObj->objStat->bool_statistics_enabled )
    {
      if ($this->pObj->b_drs_statistics)
      {
        t3lib_div::devlog('[INFO/STATISTICS] single view won\'t counted for statistics.', $this->pObj->extKey, 0);
        t3lib_div::devlog('[HELP/STATISTICS] Enable flexform.sDEF.statistics.enabled.', $this->pObj->extKey, 1);
      }
      return;
    }
      // RETURN: statistics module is disabled



    $this->statistics_download( $operator );
    $this->statistics_downloadByVisit( $operator );
  }









  /**
 * statistics_download():
 *
 * @param	integer		$operator:  + or -
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics_download( $operator )
  {
    $field = $this->pObj->objStat->fieldDownloads;
      // Count the hit
    $this->pObj->objStat->sql_update_statistics( $this->table, $field, $this->uid, $operator );
  }









  /**
 * statistics_downloadByVisit():
 *
 * @param	integer		$operator:  + or -
 *
 * @return  void
 * @version 3.9.3
 * @since 3.9.3
 */
  private function statistics_downloadByVisit( $operator )
  {
    $field = $this->pObj->objStat->fieldDownloadsByVisits;
       // RETURN: no new visit
    //$bool_newVisit = $this->pObj->objSession->statisticsNewDownload( $this->table, $field, $this->uid );
    $bool_newVisit = $this->pObj->objSession->statisticsNewVisit( $this->table, $field, $this->uid );
    if( ! $bool_newVisit )
    {
        // DRS - Development Reporting System
      if( $this->pObj->b_drs_statistics )
      {
        $prompt = 'No new visit, no counting.';
        t3lib_div::devlog('[INFO/STATISTICS] ' . $prompt, $this->pObj->extKey, 0);
      }
        // DRS - Development Reporting System
      return;
    }
      // RETURN: no new visit

      // Count the hit
    $field = $this->pObj->objStat->fieldDownloadsByVisits;
    $this->pObj->objStat->sql_update_statistics( $this->table, $field, $this->uid, $operator );

    return;
  }

  
  
  
  
  
  
  
  
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_download.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi1/class.tx_browser_pi1_download.php']);
}
?>
