
'use strict';

window.oxMap.Error= function( errorType, param ){

	var self = this;

	this.reportError = function( error, param ){
		alert( self.errorTypes[ error ].replace( /%p/g, param ) );
	};

	this.errorTypes = {
		'default'	: 'Es ist ein unbekannter Fehler aufgetreten.',
		'config' 	: 'Konfigurationsdaten fehlen. Bitte prüfen, ob die Daten korrekt geladen werden.\n\n[oxMap.Config]',
		'module'	: 'Das Modul [%p] existiert nicht.',
		'map'		: 'Es fehlt ein Kartenmodul. Aktuelle Module sind [OSM].',
		'file'		: 'Modul kann nicht verarbeitet werden.\n\nDatei fehlt: oxMap.%p.js',
		'wms'		: 'Das Kartensystem --%p-- ist nicht definiert.',
		'data'		: 'Die darzustellenden Daten konnten nicht geladen werden. Bitte prüfen, ob diese eingebunden wurden.',
		'event'		: 'Es wurde keine Layer-Event definiert. Es wird stattdessen ONCLICK reserviert.',
		'custom'	: 'Die Konfigurationswerte für die Corporate Design Karte fehlen.'
	};

	this.setup = (function( errorType, param ){
		self.reportError( errorType || 'default', param );
	})( errorType, param );

};