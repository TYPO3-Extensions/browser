
 'use strict';

 window.Filter = function(){

	var self = oxMap.OSM.Filter,
	    config = {
		    wrapper			: 'oxMap-filter-module'
		  , items			: 'oxMap-filter-item'
		  , getDataByAjax	: false
		},
		
		filterCheckElements = (function(){
			var filterWrapper = document.getElementById( config.wrapper );
			return filterWrapper.getElementsByTagName('input');
		})();

	function checkedFilter( name ){
		var elem,
			a = filterCheckElements.length,
			e = 0;

		for( ; a--; ){
			if(filterCheckElements[a].name === name && !filterCheckElements[a].checked){
				return false;
			}
		}
		return true;
	}
	
	function setVisibilityMarkerOnList( list, compare, visibility ){
		var l = list.length,
			c;

		for( ; l--; ){
			for( c = compare.marker.length; c--; ){
				if( list[ l ][ 'name' ] === compare.marker[c] ){
					list[l]['icon']['imageDiv'].style.display = visibility ? 'block' : 'none';
				}
			}
		}
	}

	self.filterStack = [];
	
	self.sortMarkerByCategory = function( rml ){
		var cat = [],
			rm = rml.length,
			t;
		for( ; rm--; ){
			t = rml[rm].split(/:/);
			if( cat.length === 0 ){
				cat.push({ 'cat' : t[0], 'marker' : [ t[1] ] });
				continue;
			}

			for(var a = 0; a < cat.length; a += 1 ){
				if( cat.length < 0 || cat[ a ][ 'cat' ] !== t[0] ){
					cat.push({ 'cat' : t[0], 'marker' : [] });
				}
				cat[ a ][ 'marker' ].push( t[1] );
			}
		}
		return cat;
	};

	self.filterOnMap = function( item ){
    	var filterOn = item.checked ? true : false,
    		layer = oxMap.OSM.map.getLayersByName( item.name )[ 0 ],
    		routeMarker, rm, r, rmMar;

    	layer.setVisibility( filterOn );

    	if( layer.oxType && layer.oxType === 'route' ){
    		routeMarker = self.sortMarkerByCategory( layer.oxMarkerList );
    		rm = routeMarker.length;

    		for( ; rm--; ){
    			layer = oxMap.OSM.map.getLayersByName( routeMarker[rm]['cat'] );
    			setVisibilityMarkerOnList( layer[  0 ][ 'markers' ], routeMarker[ rm ], filterOn );
    		}
    	}
    };

    self.getDataByAjax = function(){};

    self.setup = (function(){
    	config = oxMap.Utils.merge( config, oxMap.OSM.configuration.filter );

    	var wrapper = document.getElementById( config.wrapper ),
    		itemsClass = config.items,
    		items;
    	
    	if( !wrapper ){
   			new Error( 'filter', null, true );
  			return false;
    	}

    	items = wrapper.getElementsByTagName('input');
    	for( var a = 0, b = items.length; a < b; a += 1 ){
    		var c = items[ a ].getAttribute('class');
    		if( c.match( new RegExp(itemsClass) ) ){
    			self.filterOnMap( items[ a ] );
    			self.filterStack.push( items[ a ] );
    		}
    	}

    	wrapper.onchange = function( event ){
    		event = event || window.event;

    		var target = event.target || event.srcElement;
    		self.filterOnMap( target );
    	}

    })();

};