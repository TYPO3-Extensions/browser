<?php
if (!defined ('TYPO3_MODE'))  die ('Access denied.');



    ///////////////////////////////////////////////////////////
    //
    // INDEX
    
    // Configuration by the extension manager
    // Methods for backend workflows
    // TypoScript: Include Static Templates
    // Plugin general configuration
    // Wizard Icons
    // Plugin 1 configuration
    // Plugin 4 configuration
    // Plugin 3 configuration
    // Add default page and user TSconfig
    // Add pagetree icon



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    // Configuration by the extension manager

  $confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['browser']);

    // Language for labels of static templates and page tsConfig
  $llStatic = $confArr['LLstatic'];
  switch($llStatic) {
    case($llStatic == 'German'):
      $llStatic = 'de';
      break;
    default:
      $llStatic = 'default';
  }
    // Language for labels of static templates and page tsConfig



    ///////////////////////////////////////////////////////////
    //
    // Methods for backend workflows
  
  require_once(t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_browser_pi1_backend.php');
  require_once(t3lib_extMgm::extPath($_EXTKEY).'pi5/class.tx_browser_pi5_backend.php');



    ///////////////////////////////////////////////////////////
    //
    // TypoScript: Include Static Templates
    
    // Case $llStatic
  switch(true) {
    case($llStatic == 'de'):
      // German
      // Plugin 1
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/',                     'Browser');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/downgrade/3.9.0/',     'Browser <= 3.9.0');
        // Plugin 5
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi5/static/',                 '+Browser Kalender');
        // Plugin 4
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi4/static/',                 '+Browser (kein Cache)');
        // Plugin 1
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/tt_content/',  '+Browser - Out of the Box');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/tt_news/',     '+Browser Beispiel fuer tt_news');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/dam/',         '+Browser Beispiel fuer DAM');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/downgrade/4.5.4/map/', '+Browser oxMap <= 1.2');
        // Plugin 3
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi3/static/',                 'Browser Handbuecher');
        // TypoScript: Include Static Templates
      break;
    default:
      // English
      // Plugin 1
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/',                     'Browser');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/downgrade/3.9.0/',     'Browser <= 3.9.0');
        // Plugin 5
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi5/static/',                 '+Browser Calendar');
        // Plugin 4
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi4/static/',                 '+Browser (no cache)');
        // Plugin 1
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/tt_content/',  '+Browser - out of the box');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/tt_news/',     '+Browser Sample for tt_news');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/samples/dam/',         '+Browser Sample for DAM');
      t3lib_extMgm::addStaticFile($_EXTKEY,'static/downgrade/4.5.4/map/', '+Browser oxMap <= 1.2');
        // Plugin 3
      t3lib_extMgm::addStaticFile($_EXTKEY,'pi3/static/',                 'Browser Manuals');
        // TypoScript: Include Static Templates
      break;
  }
    // Case $llStatic
    // TypoScript: Include Static Templates



    ///////////////////////////////////////////////////////////
    //
    // Plugin general configuration
  
  t3lib_div::loadTCA('tt_content');
    // Plugin general configuration



    ///////////////////////////////////////////////////////////
    //
    // Wizard Icons
  
  if (TYPO3_MODE=='BE') 
  {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_browser_pi1_backend_wizicon'] = 
      t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_browser_pi1_backend_wizicon.php'; 
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_browser_pi5_backend_wizicon'] = 
      t3lib_extMgm::extPath($_EXTKEY).'pi5/class.tx_browser_pi5_backend_wizicon.php'; 
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_browser_pi4_backend_wizicon'] = 
      t3lib_extMgm::extPath($_EXTKEY).'pi4/class.tx_browser_pi4_backend_wizicon.php'; 
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_browser_pi3_backend_wizicon'] = 
      t3lib_extMgm::extPath($_EXTKEY).'pi3/class.tx_browser_pi3_backend_wizicon.php'; 
  }
    // Wizard Icons



    ///////////////////////////////////////////////////////////
    //
    // Plugin 1 configuration
  
  $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';
    // Remove the default tt_content fields layout, select_key, pages and recursive.
  $TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
    // Display the field pi_flexform
  t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform.xml');
    // Register our file with the flexform structure
  t3lib_extMgm::addPlugin(array('LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1', 'EXT:browser/ext_icon.gif'),'list_type');
    // Add the Flexform to the Plugin List
    // Plugin 1 configuration



    ///////////////////////////////////////////////////////////
    //
    // Plugin 5 configuration
  
  $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi5']='layout,select_key';
    // Remove the default tt_content fields layout, select_key, pages and recursive.
  $TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi5']='pi_flexform';
    // Display the field pi_flexform
  t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi5', 'FILE:EXT:'.$_EXTKEY.'/pi5/flexform.xml');
    // Register our file with the flexform structure
  t3lib_extMgm::addPlugin(array('LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi5', $_EXTKEY.'_pi5', 'EXT:browser/ext_icon.gif'),'list_type');
    // Add the Flexform to the Plugin List
    // Plugin 1 configuration



    ///////////////////////////////////////////////////////////
    //
    // Plugin 4 configuration
  
  $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi4']='layout,select_key';
    // Remove the default tt_content fields layout, select_key, pages and recursive.
  $TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi4']='pi_flexform';
    // Display the field pi_flexform
  t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi4', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform.xml');
    // Register our file with the flexform structure
  t3lib_extMgm::addPlugin(array('LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi4', $_EXTKEY.'_pi4', 'EXT:browser/ext_icon.gif'),'list_type');
    // Add the Flexform to the Plugin List
    // Plugin 4 configuration



    ///////////////////////////////////////////////////////////
    //
    // Plugin 3 configuration
  
  $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi3']='layout,select_key,pages,recursive';
    // Remove the default tt_content fields layout, select_key, pages and recursive.
  $TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi3']='pi_flexform';
    // Display the field pi_flexform
  t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi3', 'FILE:EXT:'.$_EXTKEY.'/pi3/flexform.xml');
    // Register our file with the flexform structure
  t3lib_extMgm::addPlugin(array('LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi3', $_EXTKEY.'_pi3', 'EXT:browser/ext_icon.gif'),'list_type');
    // Add the Flexform to the Plugin List
    // Plugin 3 configuration




  /////////////////////////////////////////////////
  //
  // Add default page and user TSconfig

//t3lib_extMgm::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/tsConfig/' . $llStatic . '/page.txt">');
  // Add default page and user TSconfig




    ////////////////////////////////////////////////////////////////////////////
    //
    // Add pagetree icon

  $TCA['pages']['columns']['module']['config']['items'][] =
     array('Browser', 'browser', t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif');
  t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-browser', '../typo3conf/ext/browser/ext_icon.gif');
    // Add pagetree icon



?>