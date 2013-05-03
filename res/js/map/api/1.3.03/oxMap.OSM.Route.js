
 'use strict';

 window.Route = function(){

	var self = oxMap.OSM.Route,
	    config = {
			defaultStyle : {
	    		strokeWidth : 1
	    	  , strokeColor : '#000'
  	  		}
		};
	/*
	self.convertDataForRoute = function(){
		var json = new OpenLayers.Format.GeoJSON({
				'internalProjection' : new OpenLayers.Projection("EPSG:900913"),
				'externalProjection' : new OpenLayers.Projection("EPSG:4326")
        	});

		return json.read( config.data );
	};

	self.setCustomStyleOnRoute = function( route ){
		var a = route.length,
			style;

		for( ;a--; ){
			style = route[a]['data']['style'];
			if(style) {
				route[a]['style'] = {
					'strokeWidth' : style['strokeWidth'] ? style['strokeWidth'] : '1'
				  , 'strokeColor' : style['strokeColor'] ? style['strokeColor'] : '#000'
				}
			}
		}
		return route;
	};
	*/
	
	self.createRoute = function(){
		var routes = config.data.features,
			a=routes.length,
			routeLayer, 
			routeCoors, geom, style, point,
			routeLayerList = [];
		
		for(;a--;){
			routeCoors = [];
			geom = routes[a]['geometry']['coordinates'];
			style = routes[a]['properties']['style'];

			routeLayer = new OpenLayers.Layer.Vector( routes[a]['properties']['name'] );

			for( var r = 0, t = geom.length; r < t; r += 1 ){
				point = oxMap.OSM.LonLat( geom[r] );
				point = new OpenLayers.Geometry.Point( point.lon, point.lat );
				routeCoors.push( point );
			}

			routeLayer.oxType = 'route';
			routeLayer.oxMarkerList = routes[a]['properties']['markerList'];
			routeLayer.addFeatures([
			    new OpenLayers.Feature.Vector(
			    	new OpenLayers.Geometry.LineString( routeCoors ),
			    	null,
			    	{
			    		'strokeWidth' : style['strokeWidth'] ? style['strokeWidth'] : config.defaultStyle.strokeWidth
					  , 'strokeColor' : style['strokeColor'] ? style['strokeColor'] : config.defaultStyle.strokeColor
			    	}
			    )
			]);

			routeLayerList.push( routeLayer );
		}

		oxMap.OSM.map.addLayers( routeLayerList );

	};

	/*
	self.createRoute = function(){
		var routeDef = self.setCustomStyleOnRoute( self.convertDataForRoute() ),
			routeLayer = new OpenLayers.Layer.Vector('Route');

		routeLayer.addFeatures( routeDef );
		oxMap.OSM.map.addLayer( routeLayer );
	};
	*/

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