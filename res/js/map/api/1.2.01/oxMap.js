
'use strict';

window.oxMap.Modules = {
	'Base'			: true,
	'Config'		: true,
	'Helper'		: 'init',
	'Error'			: 0,
	'OSM'			: 'map',
	'OSM.Render'	: 'init',
	'OSM.CDmap'		: 0,
	'OSM.Marker'	: 'init',
	'OSM.Tooltip'	: 'init',
	'OSM.Filter'	: 0
};




window.oxMap.Base = function( oxMapConfig ){

	var self = this,
		map = 0;
	
	function removeModule( moduleName ){
		moduleName = moduleName.split('.');
		if( moduleName.length === 2 ){
			delete oxMap[ moduleName[ 0 ] ][ moduleName[ 1 ] ];
		}
		else{
			delete oxMap[ moduleName[ 0 ] ];
		}
	}

	this.initModules = function(){
		var main,
			item,
			value;

		for( item in oxMap.Modules ){
			value = oxMap.Modules[ item ];
			if( value === 'init' ){
				item = item.split('.');

				if( item.length === 2 ){
					oxMap[ item[0] ][ item[1] ];
				}
				else{
					oxMap[ item[0] ];
				}
			}
			if( value === 0 ){
				removeModule( item );
			}
		}
		item = undefined;
		value = undefined;

		delete oxMap.Modules;
		delete oxMap.Config.modules;

	};

	this.setup = (function( oxMapConfig ){

		if( !oxMap.Config ){
			new oxMap.Error('config');
			return false;
		}

		var modules = oxMap.Config.modules,
			modulesLength = oxMap.Config.modules.length,
			a, b, m,
			mo,
			moChecker = function( modu ){
				if( typeof modu == 'undefined' ){
					new oxMap.Error( 'file', modu );
					return false;
				}
			},
			item, value;

		for( a = 0; a < modulesLength; a += 1 ){
			m = oxMap.Modules[ modules[a] ];
			if( typeof m == 'undefined' ){
				new oxMap.Error( 'module', modules[a] );
				continue;
			}

			mo = modules[a].split('.');
			moChecker( oxMap[ mo[0] ] );
			if( mo.length === 2 ){
				moChecker( oxMap[ mo[0] ][ mo[1] ] );
			}

			oxMap.Modules[ modules[a] ] = m === 0 ? 'init' : m;
		}

		for( item in oxMap.Modules ){
			value = oxMap.Modules[ item ];
			if( value === 'init' ){
				continue;
			}
			if( value === 'map' ){
				map = 1;
				oxMap.Modules[ item ] = true;
				continue;
			}
		}
		item = undefined;
		value = undefined;

		if( !map ){
			new oxMap.Error( 'map' );
			return false;
		}

		self.initModules();

	})( oxMapConfig );

};

new oxMap.Base();