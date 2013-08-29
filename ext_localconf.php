<?php

if (!defined ('TYPO3_MODE'))  die ('Access denied.');



  // Get the extensions's configuration
$extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser'] );

  ////////////////////////////////////////////////////
  //
  // Extending TypoScript from static template uid=43 to set up userdefined tag
  
t3lib_extMgm::addPItoST43( $_EXTKEY,'pi1/class.tx_browser_pi1.php','_pi1','list_type',1 );
t3lib_extMgm::addPItoST43( $_EXTKEY,'pi3/class.tx_browser_pi3.php','_pi3','list_type',1 );
t3lib_extMgm::addPItoST43( $_EXTKEY,'pi4/class.tx_browser_pi4.php','_pi4','list_type',1 );
  // Extending TypoScript from static template uid=43 to set up userdefined tag



  ////////////////////////////////////////////////////
  //
  // SC_OPTIONS

  // #33673, 120203, dwildt
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][] = 'EXT:browser/lib/class.tx_browser_befilter_hooks.php:tx_browser_befilter_hooks';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list.inc']['makeQueryArray'][] = 'EXT:browser/lib/class.tx_browser_befilter_sql.php:tx_browser_befilter_sql';

//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:browser/lib/tx_browser_processdatamapclass.php:tx_browser_processdatamapclass';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:browser/lib/class.tx_browser_tcemainprocdm.php:tx_browser_tcemainprocdm';


  // #51478, 130829, dwildt, +
  // If sample tasks should be shown, register information for the test tasks
if( ! empty ( $extConf['showSampleTasks'] ) )
{
  $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_browser_TestTask'] = array
  (
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:' . $_EXTKEY . '/lib/scheduler/locallang.xml:label.testTask.name',
    'description'      => 'LLL:EXT:' . $_EXTKEY . '/lib/scheduler/locallang.xml:label.testTask.description',
    'additionalFields' => 'tx_browser_TestTask_AdditionalFieldProvider'
  );
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_browser_ImportTask'] = array
(
  'extension'        => $_EXTKEY,
  'title'            => 'LLL:EXT:' . $_EXTKEY . '/lib/scheduler/locallang.xml:label.importTask.name',
  'description'      => 'LLL:EXT:' . $_EXTKEY . '/lib/scheduler/locallang.xml:label.importTask.description',
  'additionalFields' => 'tx_browser_ImportTask_AdditionalFieldProvider'
);
// SC_OPTIONS

?>