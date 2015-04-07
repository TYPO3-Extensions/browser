<?php

if ( !defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

// INDEX
// * de
// * default
switch ( true )
{
  case($llStatic == 'de'):
    // German
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/', 'Browser [0] + Foundation Framework' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/css/normalize/', 'Browser [0] + Foundation Framework + CSS normalize' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/jQuery/', 'Browser [0] + Foundation Framework + JSS jQuery' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/modernizr/', 'Browser [0] + Foundation Framework + JSS modernizr' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Browser [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/downgrade/4.9.1/navigation/pageBrowser/wrap/', 'Browser [1] + pageBrowser Wrap v4.9' );
    // Plugin 5
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'pi5/Configuration/TypoScript/', 'Browser [2] + Kalender' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_content/', 'Browser [3] + Beispiel ready-to-use' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news/', 'Browser [3] + Beispiel fuer tt_news' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/dam/', 'Browser [3] + Beispiel fuer DAM (veraltet!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Templating/', 'Browser [5] + Foundation Templates' );
    // Plugin 4
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi4/', 'Browser [10] + kein Cache' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Map/oxMap/', 'Browser [20] + Map Downgrade (oxMap)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/reset/', 'Browser [99] Reset' );
    // Plugin 3
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi3/', 'Browser Handbuecher [1]' );
        break;
  default:
    // English
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/', 'Browser [0] + Foundation Framework' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/css/normalize/', 'Browser [0] + Foundation Framework + CSS normalize' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/jQuery/', 'Browser [0] + Foundation Framework + JSS jQuery' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/modernizr/', 'Browser [0] + Foundation Framework + JSS modernizr' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Browser [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/downgrade/4.9.1/navigation/pageBrowser/wrap/', 'Browser [1] + pageBrowser Wrap v4.9' );
    // Plugin 5
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'pi5/Configuration/TypoScript/', 'Browser [2] + Calendar' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_content/', 'Browser [3] + Sample ready-to-use' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news/', 'Browser [3] + Sample for tt_news' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/dam/', 'Browser [3] + Sample for DAM (deprecated!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Templating/', 'Browser [5] + Foundation Templates' );
    // Plugin 4
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi4/', 'Browser [10] + no cache' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Map/oxMap/', 'Browser [20] + Map Downgrade (oxMap)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/reset/', 'Browser [99] Reset' );
    // Plugin 3
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi3/', 'Browser Manuals [1]' );
    break;
}