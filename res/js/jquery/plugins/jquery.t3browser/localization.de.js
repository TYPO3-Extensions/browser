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
    
  $( 'body' ).t3browser({
    messages: {
      errMissingTagPropertyLabel: "HTML-Tag fehlt:",
      errMissingTagPropertyPrmpt: "Ein HTML Tag mit dem Attribute {0} fehlt. AJAX wird nicht korrekt funktionieren!",
      errServerErrorPrmpt:        "Das ist eine Nachricht vom Server. Vielleicht hat der Server ein grundsätzliches Problem. Falls er mehr als die Fehlermeldung sendet, siehst Du die Server-Antwort unter diesen Meldungen.",
      hlpForumLabel:              "Interaktive Hilfe:",
      hlpForumPrmpt:              "Wenn Du den Fehler nicht beheben kannst, kannst Du Hilfe im TYPO3-Browser-Forum auf <a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a> bekommen.",
      hlpMissingTagPropertyLabel: "Prüfe das HTML-Template:",
      hlpMissingTagPropertyPrmpt: "Bitte ergänze das Template mit etwas wie <div id=\"{0}\">...</div>",
      hlpPageObjectLabel:         "Du kannst TYPO3 prüfen:",
      hlpPageObjectPrmpt:         "Ist das Page Objekt vorhanden? Ist typeNum richtig konfiguriert?",
      //hlpAjaxConflictLabel: "Maybe there is a conflict:",
      //hlpAjaxConflictPrmpt: "Don't use AJAX in the single view. See flexform/plugin sheet [jQuery] field [AJAX].",
      hlpUrlLabel:                "Du kannst die URL prüfen:",
      hlpUrlPrmpt:                "Klicke auf {0}",
      hlpUrlSelectorLabel:        "Du kannst den jQuery Selector prüfen:",
      hlpUrlSelectorPrmpt:        "Die Anfrage an den Server ist leer, wenn der Selector kein Ergebnis produziert: {0}",
      hlpGetRidOfLabel:           "Nerven diese Meldungen?",
      hlpGetRidOfPrmpt:           "Schalte das jQuery Plugin t3browser in der Flexform des TYPO3-Browsers ab. AJAX-Funktionalitäten sind dann aber abgeschaltet.",
    },
  });

});
