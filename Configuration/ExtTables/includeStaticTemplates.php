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
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/', 'Browser [0] Foundation Framework' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/css/normalize/', 'Browser [0] + Foundation Framework + CSS normalize' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/jQuery/', 'Browser [0] + Foundation Framework + JSS jQuery' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/modernizr/', 'Browser [0] + Foundation Framework + JSS modernizr' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/fastclick/', 'Browser [0] + Foundation Framework + JSS fastclick' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/FoundationIcons/', 'Browser [0] + Foundation Framework + Icons' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Browser [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/downgrade/4.9.1/navigation/pageBrowser/wrap/', 'Browser [1] + pageBrowser Wrap v4.9' );
    // Plugin 5
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'pi5/Configuration/TypoScript/', 'Browser [2] + Kalender' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_content/', 'Browser [3] + Beispiel mit tt_content' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news/', 'Browser [3] + Beispiel mit tt_news' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news_rss/', 'Browser [3] + Beispiel mit tt_news (RSS-FEED)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Bootstrap/Templating/', 'Browser [5] + Bootstrap Templates' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Templating/', 'Browser [5] + Foundation Templates' );
    // Plugin 4
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi4/', 'Browser [10] + kein Cache' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Map/oxMap/', 'Browser [20] + Map Downgrade (oxMap)' );
    // Plugin 6
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi6/', 'Browser [30] Frontend Editing' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi6/Bootstrap/', 'Browser [30] +Bootstrap Templating' );
    // Plugin 3
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi3/', 'Browser [40] Handbuecher' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/reset/', 'Browser [99] Reset' );
        break;
  default:
    // English
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/', 'Browser [0] Foundation Framework' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/css/normalize/', 'Browser [0] + Foundation Framework + CSS normalize' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/jQuery/', 'Browser [0] + Foundation Framework + JSS jQuery' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/modernizr/', 'Browser [0] + Foundation Framework + JSS modernizr' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Framework/page/jss/fastclick/', 'Browser [0] + Foundation Framework + JSS fastclick' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/FoundationIcons/', 'Browser [0] + Foundation Framework + Icons' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/', 'Browser [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/downgrade/4.9.1/navigation/pageBrowser/wrap/', 'Browser [1] + pageBrowser Wrap v4.9' );
    // Plugin 5
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'pi5/Configuration/TypoScript/', 'Browser [2] + Calendar' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_content/', 'Browser [3] + Sample with tt_content' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news/', 'Browser [3] + Sample with tt_news' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/samples/tt_news_rss/', 'Browser [3] + Sample with tt_news (RSS feed)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Bootstrap/Templating/', 'Browser [5] + Bootstrap Templates' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Foundation/Templating/', 'Browser [5] + Foundation Templates' );
    // Plugin 4
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi4/', 'Browser [10] + no cache' );
    // Plugin 1
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Map/oxMap/', 'Browser [20] + Map Downgrade (oxMap)' );
    // Plugin 6
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi6/', 'Browser [30] Frontend Editing' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi6/Bootstrap/', 'Browser [30] +Bootstrap Templating' );
    // Plugin 3
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/pi3/', 'Browser [40] Manuals' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/reset/', 'Browser [99] Reset' );
    break;
}