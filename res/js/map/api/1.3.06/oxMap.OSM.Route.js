
 'use strict';

 window.Route = function(){

	var self = oxMap.OSM.Route,
	    config = {
			defaultStyle : {
	    		strokeWidth : 1
	    	  , strokeColor : '#000'
  	  		}
		};

	self.createRoute = function(){
		var routes = config.data.features,
			categories = {},
			cat, c, r,
			a=routes.length,
			route,
			routeLayer, 
			routeCoors, geom, style, point,
			routeLayerList = [],
			featureList,
			markerList;
		
		for(;a--;){
			cat = routes[ a ][ 'properties' ][ 'name' ];
			if( !categories[ cat ] ){
				categories[ cat ] = {};
				categories[ cat ][ 'routes' ] = {};
			}
			categories[ cat ][ 'routes' ][ a ] = routes[ a ][ 'properties' ][ 'name' ];
		}

		for( c in categories ){
			routeLayer = new OpenLayers.Layer.Vector( c );
			routeLayer.oxType = 'route';
			featureList = [];
			markerList = [];

			for( r in categories[ c ][ 'routes' ] ){
				route = routes[r];

				routeCoors = [];
				geom = route.geometry.coordinates;
				style = route.properties.style;

				for( var s = 0, t = geom.length; s < t; s += 1 ){
					point = oxMap.OSM.LonLat( geom[s] );
					point = new OpenLayers.Geometry.Point( point.lon, point.lat );
					routeCoors.push( point );
				}

				featureList.push(
					new OpenLayers.Feature.Vector(
				    	new OpenLayers.Geometry.LineString( routeCoors ),
				    	null,
				    	{
				    		'strokeWidth' : style['strokeWidth'] ? style['strokeWidth'] : config.defaultStyle.strokeWidth
						  , 'strokeColor' : style['strokeColor'] ? style['strokeColor'] : config.defaultStyle.strokeColor
				    	}
					)
				);

				for( var z = 0, y = route.properties.markerList.length; z < y; z += 1 ){
					markerList.push( route.properties.markerList[ z ] );
				}
			}

			routeLayer.addFeatures( featureList );
			routeLayerList.push( routeLayer );
			routeLayer.oxMarkerList = markerList;
		}

		oxMap.OSM.map.addLayers( routeLayerList );

	};

    self.setup = (function(){
    	if( !oxMap.OSM.configuration.route ){
    		new Error( 'config', 'OSM.Route', true );
    		return false;
    	}
    	if( !oxMap.OSM.configuration.route.data ){
    		new Error( 'route', null, true );
    		return false;
    	}

    	OpenLayers.Layer.prototype.oxType;
    	OpenLayers.Layer.prototype.oxMarkerList;

    	config = oxMap.Utils.merge( config, oxMap.OSM.configuration.route );

    	self.createRoute();
    })();

};