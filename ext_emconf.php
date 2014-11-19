<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "browser".
 *
 * Auto generated 03-05-2014 01:27
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
  'title' => 'Browser - TYPO3 without PHP',
  'description' => ''
  . 'Publish your data in list and single views and in maps! '
  . 'You don\'t need neither any own plugin nor any line PHP code. '
  . 'The Browser - TYPO3 without PHP - provides an index browser (a-z), '
  . 'a page browser, a record browser, a search form, filters, category menus, AJAX, '
  . 'GoogleMaps and OpenStreetMap by default. Your data will be detected as objects, '
  . 'i.e. images will be wrapped automatically. SEO - Search Engine Optimization - is supported. '
  . 'The Browser enables the development of TYPO3-Extensions without any line PHP. '
  . 'TYPO3 developers and TYPO3 integrators  will be eight times faster! '
  . 'The browser is very suitable for database development. '
  . 'Manual: http://typo3-browser.de/typo3conf/ext/browser/doc/manual.pdf'
  ,
  'category' => 'plugin',
  'shy' => 0,
  'version' => '6.0.4',
  'dependencies' => 'browser_manual_en',
  'conflicts' => 'be_tablefilter',
  'priority' => '',
  'loadOrder' => '',
  'module' => '',
  'state' => 'alpha',
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
    ),
    'suggests' => array(
      't3jquery' => '',
    ),
  ),
  'suggests' => array(
  ),
);

?>