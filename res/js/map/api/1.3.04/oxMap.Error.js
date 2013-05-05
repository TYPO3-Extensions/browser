
'use strict';

function Error( errorType, param, report ){

    var self = this,
        lang = 'en';
    
    this.errorTypes = {
        'en' : {
                'default' : 'An unknown error has occurred.'
              , 'config'  : 'Datas for configuration are missing. Please check whether your datas are correctly loaded.\n\n[oxMap.Config]'
              , 'module'  : 'Module [%p] do not exists.'
              , 'base'	  : 'Base-Module XX.Base is missing. You can not render oxMap without a base module.\nXX is your WMS, e.g. OSM.Base'
              , 'map'     : 'A map module is missed. Currently there are the following modules [OSM].'
              , 'file'    : 'Module is unverifiable.\n\nMissing: oxMap.%p.js'
              , 'wms'     : 'The map system --%p-- is not defined.'
              , 'data'    : 'Datas which want displayed are not loaded. Please check whether your datas are correctly integrated.'
              , 'event'   : 'You have not defined any layer event. By default ONCLICK was set.'
              , 'custom'  : 'There are no settings for a corporate design map.'
              , 'filter'  : 'A filter wrapper is failed.\nYou must define a HTML-form with an identifier (ID) and tell it oxMap.Config.filter'
              , 'route'   : 'Route data are not defined.\nPlease insert route data as GeoJSON.'
        }
        , 'de' : {
                'default' : 'Es ist ein unbekannter Fehler aufgetreten.'
              , 'config'  : 'Konfigurationsdaten für %p fehlen. Bitte prüfen Sie, ob die Daten korrekt geladen werden.\n\n[oxMap.Config]'
              , 'module'  : 'Das Modul [%p] existiert nicht.'
              , 'base'	  : 'Das Basis-Modul XX.Base fehlt. Ohne dieses ist eine Ausgabe nicht möglich.\nXX ist das entsprechende WMS, z.B. OSM.Base'
              , 'map'     : 'Es fehlt ein Kartenmodul. Aktuelle Module sind [OSM].'
              , 'file'    : 'Modul kann nicht verarbeitet werden.\n\nDatei fehlt: oxMap.%p.js'
              , 'wms'     : 'Das Kartensystem --%p-- ist nicht definiert.'
              , 'data'    : 'Die darzustellenden Daten konnten nicht geladen werden. Bitte prüfen, ob diese eingebunden wurden.'
              , 'event'   : 'Es wurde keine Layer-Event definiert. Es wird stattdessen ONCLICK reserviert.'
              , 'custom'  : 'Die Konfigurationswerte für die Corporate Design Karte fehlen.'
              , 'filter'  : 'Der Filter wurde nicht festgelegt\nDefinieren Sie ein HTML-Form mit einer ID und teilen es der oxMap.Config.filter mit.'
              , 'route'   : 'Es wurden keine Daten für die Routenausgabe definiert.\nBitte weisen Sie Routendaten als GeoJSON zu.'
        }
    };

    this.reportError = function( error, param ){
        alert( self.errorTypes[ lang ][ error ].replace( /%p/g, param ) );
    };

    this.setup = (function( errorType, param, report ){
    	if(!errorType){
    		errorType = '00';
    		self.errorTypes[oxMap.cfg.language]['00'] = param;
    	}
    	if( report ){
    		if(oxMap.cfg.language){
    			lang = oxMap.cfg.language === 'default' ? lang : oxMap.cfg.language;
    		}
	        self.reportError( errorType || 'default', param );
    	}
    })( errorType, param, report );

};