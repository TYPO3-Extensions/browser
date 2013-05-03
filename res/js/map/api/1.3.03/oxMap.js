
'use strict';

window.oxMap = {
	cfg 		: {
		'language'	: 'en'
	  , 'wms' 		: null
	  , 'modules'	: null
	  , 'error'		: false
	}

  , wms 		: ['OSM','GMap']

  , addModule 	: function(){
	  	var name,
	  		s = this;
	  	for( var a = 0, b = arguments.length; a < b; a += 1 ){
	  		name = arguments[ a ];
	  		oxMap[ s.wms ][ name ] = window[ name ];
	  		oxMap[ s.wms ][ name ]['scope'] = oxMap[ s.wms ];
			oxMap.Utils.delObject( name );
	  	}
	    return true;
	}

  , runModule 	: function( name, parameters ){
		    oxMap[ this.wms ][ name ].call( parameters );
		    return true;
	}

  , checkWms 	: function(){
	  	var wms = false;

		for( var a = 0, b = oxMap.wms.length; a < b; a += 1 ){
			wms = oxMap.wms[a] === oxMap.cfg.wms ? true : wms
		}
		if( !wms ){
			new Error('wms', oxMap.cfg.wms, oxMap.cfg.error );
			return false;
		}

		oxMap.wms = oxMap.cfg.wms;

		oxMap.Utils.delObject( 'wms', oxMap.cfg );
		return true;
	}

  , getWms		: function(){
	  	return this.wms;
  	}

  , initModules	: function(){
	  	var t,
	  		wms = oxMap.getWms();

		oxMap[ wms ] = window[ wms ];
		oxMap.Utils.delObject( 'wms' );

		oxMap.runModule('Render');

		for( var a = 0, b = oxMap.cfg.modules.length; a < b; a += 1 ){
			t = oxMap.cfg.modules[a].split('.');
			if( t[0] !== wms ){
				new Error('module', oxMap.cfg.modules[a], oxMap.cfg.error );
				continue;
			}
			if( t[1] === 'Base' ){
				oxMap.addModule.apply(this, ['Marker', 'Tooltip'] );

				oxMap.runModule('Marker');
				oxMap.runModule('Tooltip');
			}
			else{
				if( !window[ t[1] ] ){
					new Error('file', wms + '.' + t[1], oxMap.cfg.error );
					continue;
				}
				oxMap.addModule.apply( this, [t[1]] );
				oxMap.runModule( t[1] );
				oxMap.Utils.delObject( t[1] );
			}
		}

		//if( !base ){
		//	new Error('base', '', true );
		//}
	}

  , setup		: function(){
	  	oxMap.Utils = Utils;
		oxMap.Utils.delObject( 'Utils' );

		oxMap.Utils.merge( oxMap.cfg, this );
		oxMap.cfg.modules = oxMap.cfg.modules.split(',');

		for( var a = 0, b = oxMap.cfg.modules.length; a < b; a += 1 ){
			if( oxMap.cfg.modules[a] === 'Error' ){
				oxMap.cfg.error = true;
				oxMap.cfg.modules.splice(a,1);
				break;
			}
		}

		if( oxMap.checkWms() ){
			oxMap.initModules();
		}
	}
};

function oxMapRender( cfg ){
	if( !cfg ){
		new Error('config', null, true);
		return false;
	}
	oxMap.setup.call( cfg );

	return true;
}
