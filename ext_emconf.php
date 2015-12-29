<?php

/* * *************************************************************
 * Extension Manager/Repository config file for ext "browser".
 *
 * Auto generated 03-05-2014 01:27
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 * ************************************************************* */

$EM_CONF[ $_EXTKEY ] = array(
  'title' => 'Browser - TYPO3 without PHP',
  'description' => ''
  . 'Publish the data of your extension with the Browser – TYPO3 without PHP! '
  . 'You don\'t need neither any own PHP code nor any own plugin. '
  . 'You can save up to 85 percent of your operating expense. '
  . 'The Browser – TYPO3 without PHP – provides responsive templates, '
  . 'an index browser (a-z), a page browser, a record browser, a search form, filters, '
  . 'category menus, AJAX, GoogleMaps and OpenStreetMap by default. '
  . 'SEO – Search Engine Optimization – is optional.'
  . 'Manual: http://typo3-browser.de/typo3conf/ext/browser_tut_manual_en/doc/manual.pdf'
  ,
  'category' => 'plugin',
  'shy' => 0,
  'version' => '7.4.0',
  'priority' => '',
  'loadOrder' => '',
  'module' => '',
  'state' => 'beta',
  'uploadfolder' => 1,
  'createDirs' => '',
  'modify_tables' => '',
  'clearcacheonload' => 0,
  'lockType' => '',
  'author' => 'Dirk Wildt (Die Netzmacher)',
  'author_email' => 'http://wildt.at.die-netzmacher.de',
  'author_company' => '',
  'CGLcompliance' => '',
  'CGLcompliance_note' => '',
  'constraints' => array(
    'depends' => array(
      'browser_manual_en' => '',
      'typo3' => '4.5.0-6.2.99',
    ),
    'conflicts' => array(
      'be_tablefilter' => '',
      'dbal' => '',
    ),
    'suggests' => array(
      't3jquery' => '',
    ),
  ),
);