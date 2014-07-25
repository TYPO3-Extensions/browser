
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
      // #i0076, 140721, dwildt, 4+
      if( filterWrapper === null)
      {
        return null;
      }
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

	self.filterMarkerByCategory = function(layerName, visibility) {
		var layer = oxMap.OSM.map.getLayersByName(layerName)[0];
    	layer.setVisibility(visibility);

    	return layer;
	};

	self.filterRouteByCategory = function(pointer, routeName, visibility) {
		var routes = oxMap.cfg.route.data.features,
			originData = oxMap.data,
			a, b = routes.length,
			x, y,
			markerList, markerOnLayer, markerLayer, marker, m,
			routeLayer;

		for (a = 0; a < b; a += 1 ) {
			if (routes[a]['properties']['name'] === routeName) {
				routes = routes[a];
				routeLayer = oxMap.OSM.map.getLayersByName(routeName)[0];
				break;
			}
		}

		routeLayer.setVisibility(visibility);

		markerList = routes.properties.markerList;
		for (a = 0, b = markerList.length; a < b; a += 1) {
			m = markerList[a].split(/:/);

			markerOnLayer = oxMap.OSM.map.getLayersByName(m[0])[0].markers;
			for (x = markerOnLayer.length; x > -1; x -= 1) {
				if (markerOnLayer[x] && markerOnLayer[x]['name'] === m[1]) {
					markerOnLayer[x].display(visibility);
					break;
				}
			}
		}
	};

	self.filterOnMap = function(item) {
		var filter = item.checked ? true : false,
			route = oxMap.data[item.name]['route'];

		if (!route) {
			self.filterMarkerByCategory(item.name, filter);
		} else {
			self.filterRouteByCategory(item.name, route, filter);
		}
		return true;
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