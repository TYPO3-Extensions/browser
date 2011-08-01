<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2010 -  Dirk Wildt http://wildt.at.die-netzmacher.de
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
 ***************************************************************/

/**
 * The class tx_browser_pi2_tickets contains the array with the tickets
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage    tx_browser
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   48: class tx_browser_pi2_tickets
 *   62:     function __construct($parentObj)
 *
 *              SECTION: Array with all Tickets
 *  127:     function init_tickets()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_browser_pi2_tickets
{






  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 */
  function __construct($parentObj)
  {
    $this->pObj = $parentObj;

    ///////////////////////////////////////////////////////
    //
    // Define constants for the Ticket array

    define('PI2_CHECK_TYPE',          1);
    define('PI2_CHECK_PATH',          2);
    define('PI2_CHECK_VALUE',         3);

    define('PI2_STATUS_OK',           1);
    define('PI2_STATUS_INFO',         2);
    define('PI2_STATUS_HELP',         3);
    define('PI2_STATUS_WARN',         4);
    define('PI2_STATUS_ERROR',        5);

    define('PI2_TODO_NOTHING',        1);
    define('PI2_TODO_UPDATE_PLEASE',  2);
    define('PI2_TODO_UPDATE_MUST',    3);
    define('PI2_TODO_REMOVE_PLEASE',  4);
    define('PI2_TODO_CONFIG_PLUGIN',  5);

    define('PI2_TYPE_INT',            1);
    define('PI2_TYPE_STR',            2);

    define('PI2_VIEW_LIST',           1);
    define('PI2_VIEW_SINGLE',         2);
    define('PI2_VIEW_LIST_SINGLE',    3);

    define('PI2_GLOBAL',              1);
    define('PI2_LOCAL',               2);
    define('PI2_GLOBAL_LOCAL',        3);
    // Define constants for the Ticket array

  }










  /***********************************************
  *
  * Array with all Tickets
  *
  **********************************************/








/**
 * Initiate an array with the tickets
 *
 * @return	array		Array with the Tickets
 */
  function init_tickets() {

    /////////////////////////////////////////////////////
    //
    // Set the Ticket array

    // Syntax:  Release|TicketNo|Properties
    // Example: 3      |123     |The Ticket Array
    $int_v = 2;
    // Version
    $int_t = 0;
    //Ticket

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'A-Z-Browser Default Tab';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'The A-Z-Browser Default Tab couldn\'t translate to another language until %version%. We changed the type from string to integer. The value has to be an id of a tab instead of the name. Edit your code like in the example below.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'A-Z-Browser Standard-Tab';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Der A-Z-Browser Standard-Tab konnte bis zur Version %version% nicht &uuml;bersetzt werden. Wir haben den Typ von String auf Integer umgestellt. Der Wert ist jetzt die ID eines Tabs statt wie bisher eines Namens. &Auml;ndern Sie den Code wie im Beispiel unten erl&auml;utert.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'str_to_int';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.2';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'a-z_Browser.defaultTab';
    $arr_release[$int_v][$int_t]['expl']['prompt']['default']       = 'Example: If you like the first tab as the default tab and the first tab has the id 0 (see a-z_Browser.tabs):';
    $arr_release[$int_v][$int_t]['expl']['prompt']['de']            = 'Beispiel: Wenn der erste Tab der Standard-Tab sein soll und dieser die ID 0 hat (siehe auch a-z_Browser.tabs):';
    $arr_release[$int_v][$int_t]['expl']['code']                    = 'a-z_Browser.defaultTab = 0';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Recursion Guard';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'The field is moved to the array advanced in version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Recursion Guard';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Das Feld ist in der Version %version% in das Array advanced umgezogen.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_value_into_array';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.2';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'recursionGuard';
    $arr_release[$int_v][$int_t]['dest']['typoscript']['code']      = 'advanced.recursionGuard';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display Searchbox';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Suchfeld anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.searchform';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display Searchbox Phrase';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Suchfeld Ergebnissatz  anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.searchform.resultPhrase';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display Colored Swords';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Treffer farblich markieren';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.searchform.wrapSwordInResults';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display A-Z-Browser';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Anteige A-Z-Browser';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.a-z_Browser';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display PageBrowser';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Anteige Page-Browser';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.pagebrowser';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display empty List at Start';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Leere Liste beim Starten';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.emptyListByStart';

    $int_v++;
    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Pagebrowser results_at_a_time';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Pagebrowser results_at_a_time';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.1.4';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'pageBrowser.results_at_a_time';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Searchform: and';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is moved in TypoScript since version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Suchformular: and';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft ist seit Version %version% im TypoScript an anderer Stelle.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_value';
    $arr_release[$int_v][$int_t]['version']                         = '3.1.6';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['path']      = 'displayList.display.searchform.resultPhrase.searchFor.and.value';
    $arr_release[$int_v][$int_t]['dest']['typoscript']['path']      = 'displayList.display.searchform.and.value';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'A-Z-Browser: Display default tab in URL?';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is moved in TypoScript since version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'A-Z-Browser: Standard Tab in URL anzeigen?';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft ist seit Version %version% im TypoScript an anderer Stelle.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_value';
    $arr_release[$int_v][$int_t]['version']                         = '3.1.6';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['path']      = 'a-z_Browser.defaultTab.realURL';
    $arr_release[$int_v][$int_t]['dest']['typoscript']['path']      = 'a-z_Browser.defaultTab.display_in_url';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'piVars: don\'t display piVars in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is removed in TypoScript since version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'piVars: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft ist seit Version %version% im TypoScript ersatzlose weggefallen.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'remove_value';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['path']      = 'advanced.realUrl.linkToSingle.dont_display_piVars';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Sword: don\'t display sword in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Listview > Search: configured > colored swords (single view)';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Suchbegriff: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Listenansicht > Suche: konfiguriert > farbige Treffer (Detailansicht).';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.sword';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'A-Z-Index: don\'t display value in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Marker & RealUrl > RealUrl: configured > A-Z-index';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'A-Z-Index: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Marker & RealUrl > RealUrl: konfiguriert > A-Z-Index.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.azTab';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Mode: don\'t display mode in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Marker & RealUrl > RealUrl: configured > Current view';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Aktuelle Ansicht: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Marker & RealUrl > RealUrl: konfiguriert > Aktuelle Ansicht.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.mode';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Pointer: don\'t display value in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Marker & RealUrl > RealUrl: configured > Current page of page-browser';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Seite des Page-Browsers: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Marker & RealUrl > RealUrl: konfiguriert > Aktuelle Seite des Datensatz- bzw. Page-Brwosers.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.pointer';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Plugin: don\'t display uid in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Marker & RealUrl > RealUrl: configured > Current plugin';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Plugin: Uid in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Marker & RealUrl > RealUrl: konfiguriert > Das aktuelle Plugin.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.plugin';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Sort: don\'t display sorting clause in links to single view';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. Please remove the TypoScript code. Please configure the plugin: Marker & RealUrl > RealUrl: configured > Sort clause';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Sortierung: in Links zur Detailansicht nicht anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird ab Version %version% im Plugin gepflegt. Entfernen Sie bitte den TypoScript-Code. Und pflegen Sie das Plugin: Marker & RealUrl > RealUrl: konfiguriert > Aktuelle Sortierung.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.3.5';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'advanced.realUrl.linkToSingle.dont_display_piVars.sort';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Plugin: a field is moved';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'The field [Views: This plugin handles the views (ids from TypoScript '.
      'comma seperated like: 0,1,4)] in the devider [General] is deprecated. Please configure now [Views: Enabled list views, which are handled by this'.
      'plugin. The (number) is the number from TypoScript.].<br />'.
      'Former value was : %viewsIds% <br />'.
      '<br />'.
      'Please maintain the new field.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Plugin: Feld wurde geändert';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Das Feld [Views: Dieses Plugin verarbeitet die Views (IDs aus TypoScript '.
      'mit Komma getrennt - z.B.: 0,1,4)] im Reiter [Allgemein] ist veraltet. Die Werte werden jetzt im Feld gepflegt [Views: Listen, die von '.
      'diesem Plugin verarbeitet werden.].<br />'.
      'Deine bisherigen Werte waren: %viewsIds% <br />'.
      '<br />'.
      'Pflege bitte die Werte in das neue Feld ein.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_CONFIG_PLUGIN;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_plugin_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.4.2';
    $arr_release[$int_v][$int_t]['srce']                            = 'sDEF.viewsIds';
    $arr_release[$int_v][$int_t]['dest']                            = 'sDEF.viewslist';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'jQuery library: path has changed';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is moved in TypoScript since version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'jQuery Bibliothek: Pfad hat sich ge&auml;ndert';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft ist seit Version %version% im TypoScript an anderer Stelle.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_value';
    $arr_release[$int_v][$int_t]['version']                         = '3.7.0';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['path']      = 'javascript.jquery.file';
    $arr_release[$int_v][$int_t]['dest']['typoscript']['path']      = 'javascript.jquery.library';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'TypoScript property plugin';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is moved in TypoScript since version %version%.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'TypoScript Eigenschaft plugin';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft ist seit Version %version% im TypoScript an anderer Stelle.';
    $arr_release[$int_v][$int_t]['status']                          = PI2_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI2_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_value';
    $arr_release[$int_v][$int_t]['version']                         = '3.7.0';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['path']      = 'plugin';
    $arr_release[$int_v][$int_t]['dest']['typoscript']['path']      = 'flexform';

//:TODO:
    // Set the Ticket array


    /////////////////////////////////////////////////////
    //
    // Return the Ticket array

    return $arr_release;
    // Return the Ticket array
  }








  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2_tickets.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/browser/pi2/class.tx_browser_pi2_tickets.php']);
  }

?>