/**
 *
 * Localization file for jquery.t3browser
 
 * Copyright (c) 2011 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 0.0.1
 *
 * jquery.t3browser-x.x.x.js is needed:
 *   http://docs.jquery.com/Plugins/t3browser
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */



$( document ).ready( function( )
{

    // WARNING: The messages array must be exactly the same like in jquery.t3browser-x.x.x.js
    //          If a property is missing, it will be removed in jquery.t3browser-x.x.x.js!
    
  $( body ).t3browser({
    messages: {
      errMissingTagPropertyLabel: "HTML-Tag fehlt:",
      errMissingTagPropertyPrmpt: "Ein HTML Tag mit dem Attribute {0} fehlt. AJAX wird nicht korrekt funktionieren!",
      hlpMissingTagPropertyLabel: "Prüfe das HTML-Template:",
      hlpMissingTagPropertyPrmpt: "Bitte ergänze das Template mit etwas wie <div id=\"{0}\">...</div>",
      hlpPageObjectLabel:         "Prüfe TYPO3:",
      hlpPageObjectPrmpt:         "Prüfe bitte, ob das Page Objekt vorhanden ist. Prüfe typeNum.",
      //hlpAjaxConflictLabel: "Maybe there is a conflict:",
      //hlpAjaxConflictPrmpt: "Don't use AJAX in the single view. See flexform/plugin sheet [jQuery] field [AJAX].",
      hlpUrlLabel:                "Prüfe die URL:",
      hlpUrlPrmpt:                "Prüfe die URL manuel: {0}",
      hlpUrlSelectorLabel:        "Prüfe den jQuery Selector:",
      hlpUrlSelectorPrmpt:        "Die Anfrage an den Server ist leer, wenn der Selector kein Ergebnis produziert: {0}",
      hlpGetRidOfLabel:           "Nerven diese Meldungen?",
      hlpGetRidOfPrmpt:           "Schalte das jQuery Plugin t3browser in der Flexform des TYPÜO3-Browsers ab. Du hast dann aber keine AJAX-Funktionalitäten.",
    },
  });

});