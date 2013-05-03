
'use strict';

window.OSM = {

    configuration 	: {
        'wms'   			: 'OSM'
      , 'type'  			: 'Mapnik'
      , 'mapID' 			: 'oxMap-area'
      , 'zoomWheelEnable'	: true
      , 'mapMarkerEvent'	: 'click'
        	
    }

  , osmOptions 		: {
        controls    : [ new OpenLayers.Control.Attribution() ],
        projection  : new OpenLayers.Projection( "EPSG:4326" ),
        units       : 'degrees',
        allOverlays : true
    }
  , map 			: null
  , wmsLayer 		: null
  , wms 			: {
        'OSM' : {
                  'Osmarender' : new OpenLayers.Layer.OSM.Osmarender
                , 'Mapnik'     : new OpenLayers.Layer.OSM.Mapnik
    			, 'ROADMAP'    : new OpenLayers.Layer.Google( 'Google Streets', { numZoomLevels : 20 } )
                }
    }
  , tt 				: null

  , Render 			: function(){

    	var self = oxMap.OSM,
            options = self.configuration,
            mapLayerType, typeLength,
            mapLayer = [],
            cd;

        options = oxMap.Utils.merge( self.configuration, oxMap.cfg );

        mapLayerType = options.type.split(',');
        typeLength = mapLayerType.length;

        if( !mapLayerType || typeLength < 1 ){
            new Error( 'wms', options.wms + ' ' + options.type, true );
            return false;
        }

        self.osmOptions.controls = self.osmOptions.controls.concat( new oxMap.OSM.Controls( options.mapControls.split(',') ) );

        for( var a = 0; a < typeLength; a += 1 ){
            mapLayer.push( self.wms[ options.wms ][ mapLayerType[a] ] );
        }

        oxMap.OSM.map = new OpenLayers.Map( options.mapID, self.osmOptions );
        oxMap.OSM.map.addLayers( mapLayer );
        oxMap.OSM.map.setCenter(
            oxMap.OSM.LonLat( [options.center.lon, options.center.lat] ),
            options.startLevel
        );

        if( options.customMap && options.customMap.bounds ){
            oxMap.OSM.map.setOptions(
                oxMap.Utils.merge( oxMap.OSM.map.options, {
                    restrictedExtent : oxMap.OSM.Util.createBoundObject( options.customMap.bounds, 'WGS84')
                })
            );
        }

        oxMap.OSM.map.events.on({
        	'zoomend' 	: function(){
        		if( oxMap.OSM.tt ){
        			oxMap.OSM.tt.parentNode.removeChild( oxMap.OSM.tt );
        			oxMap.OSM.tt = null;
        		}
        		if( options.sector ){
        			if( oxMap.OSM.map.getZoom() <= options.sector.onZoomLevel ){
        				oxMap.OSM.Sector.setup();
        			}
        			else{
        				oxMap.OSM.Sector.clearSector();
        			}
        		}
        	}
        //,
//        	'click' : function( event){
//        		console.log(event)
//        		console.log(this.getZoom())
//        		
//        		//tiles vs. wms  wenn tiles dann wms entfernen
//        		//oxMap.OSM.wmsLayer[0].setVisibility( false );
////        		this.addLayer(
////        					
////        		);
//        	}
        })

        oxMap.OSM.configuration.size = oxMap.OSM.map.getSize();

        oxMap.OSM.wmsLayer = mapLayer;

        oxMap.data = rawdata || (function(){ new Error( 'data', null, true ) })();

    }

  , Controls 		: function( controls ){

        var a, b = controls.length,
            control = [],
            controlLayer,
            navOptions;

        for( a = 0; a < b; a += 1 ){
            if( controls[a] === 'Navigation' ){
                navOptions = oxMap.OSM.configuration.zoomWheelEnable 
                           ?
                           {
                              type              : OpenLayers.Control.TYPE_TOGGLE
                            , defaultDblClick   : function( event ) { return; }
                            , zoomWheelEnabled  : true
                            , mouseWheelOptions : {
                                    cumulative : false
                                  , interval   : 210
                                                  }
                           }
                           :
                           {
                              defaultDblClick  : function( event ) { return; }
                            , zoomWheelEnabled : false
                           };
                controlLayer = new OpenLayers.Control.Navigation( navOptions );
            }
            else{
                controlLayer = new OpenLayers.Control[ controls[ a ] ]();
            }
            control.push( controlLayer );
        }

        return control;

    }

  , LonLat 			: function( aCoorPair ){

        if ( aCoorPair.length === 2 ){
            return new OpenLayers.LonLat( aCoorPair[ 0 ], aCoorPair[ 1 ] )
                        .transform( oxMap.OSM.Util.transformer.WGS84, oxMap.OSM.Util.transformer.map() );
        }
        return false;

    }

  , Util 			: {
		transformer : { 
			'null'	: null,
			'WGS84'	: new OpenLayers.Projection( "EPSG:4326" ),// transform from WGS 1984
			'OSM'	: new OpenLayers.Projection( "EPSG:4326" ),// transform from WGS 1984
			'GMap'	: new OpenLayers.Projection( "EPSG:900913" ),
			'map'	: function(){
					      return oxMap.OSM.map.getProjectionObject();  // to Spherical Mercator Projection
					  }
		}
	
	  , createIcon : function( param ){
	      var icon = param[0],
	          size = new OpenLayers.Size( param[1] , param[2] ),
	          offset = new OpenLayers.Pixel( param[3] , param[4] );
	      return new OpenLayers.Icon( icon, size, offset );
	  	}
	
	  , createBoundObject : function( bounds, transformType ){
	      var W = bounds.w,
	          S = bounds.s,
	          E = bounds.e,
	          N = bounds.n,
	
	          self = this,
	
	          bounds = new OpenLayers.Bounds();
	      bounds.extend(new OpenLayers.LonLat( W,S ));
	      bounds.extend(new OpenLayers.LonLat( E,N ));
	
	      if( transformType ){
		        bounds.transform(
		        		self.transformer[ transformType ],
		        		self.transformer.map()
		                );
	      }
	
	      return bounds;
		}

	  , getCenter		: function(){						// coors W,S,E,N
		  	if(arguments.length !== 4){
		  		return false;
		  	}

		  	for(var a = 0, b = arguments.length; a < b; a += 1 ){
		  		arguments[a] = arguments[a] * 1;
		  	}

		  	var cLon = arguments[0] + ( (arguments[2] - arguments[0]) / 2 ),
		  		cLat = arguments[1] + ( (arguments[3] - arguments[1]) / 2 );

		  	return oxMap.OSM.LonLat( [cLon,cLat] );
	  	}
	}

};