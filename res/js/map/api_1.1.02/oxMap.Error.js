
'use strict';

window.oxMap.Error= function( errorType, param ){

    var self = this,
        lang = 'en';
    
    this.errorTypes = {
        'en' : {
                'default' : 'An unknown error has occurred.'
              , 'config'  : 'Datas for configuration are missing. Please check whether your datas are correctly loaded.\n\n[oxMap.Config]'
              , 'module'  : 'Module [%p] do not exists.'
              , 'map'     : 'A map module is missed. Currently there are the following modules [OSM].'
              , 'file'    : 'Module is unverifiable.\n\nMissing: oxMap.%p.js'
              , 'wms'     : 'The map system --%p-- is not defined.'
              , 'data'    : 'Datas which want displayed are not loaded. Please check whether your datas are correctly integrated.'
              , 'event'   : 'You have not defined any layer event. By default ONCLICK was set.'
              , 'custom'  : 'There are no settings for a corporate design map.'
        }
        , 'de' : {
                'default' : 'Es ist ein unbekannter Fehler aufgetreten.'
              , 'config'  : 'Konfigurationsdaten fehlen. Bitte prüfen, ob die Daten korrekt geladen werden.\n\n[oxMap.Config]'
              , 'module'  : 'Das Modul [%p] existiert nicht.'
              , 'map'     : 'Es fehlt ein Kartenmodul. Aktuelle Module sind [OSM].'
              , 'file'    : 'Modul kann nicht verarbeitet werden.\n\nDatei fehlt: oxMap.%p.js'
              , 'wms'     : 'Das Kartensystem --%p-- ist nicht definiert.'
              , 'data'    : 'Die darzustellenden Daten konnten nicht geladen werden. Bitte prüfen, ob diese eingebunden wurden.'
              , 'event'   : 'Es wurde keine Layer-Event definiert. Es wird stattdessen ONCLICK reserviert.'
              , 'custom'  : 'Die Konfigurationswerte für die Corporate Design Karte fehlen.'
        }
    };

    this.reportError = function( error, param ){
        alert( self.errorTypes[ lang ][ error ].replace( /%p/g, param ) );
    };

    this.setup = (function( errorType, param ){
        lang = oxMap.Config.language === 'default' ? lang : oxMap.Config.language;
        self.reportError( errorType || 'default', param );
    })( errorType, param );

};