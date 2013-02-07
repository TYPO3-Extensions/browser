
'use strict';

window.oxMap.OSM = {

    configuration : {
        'wms'   : 'OSM',
        'type'  : 'Mapnik',
        'mapID' : 'oxMapArea'
    },
    osmOptions : {
        controls    : [ new OpenLayers.Control.Attribution() ],
        projection  : new OpenLayers.Projection( "EPSG:4326" ),
        units       : 'degrees',
        allOverlays : true
    },
    map : null,
    wmsLayer : null,
    wms : {
        'OSM' : {
                'Osmarender' : new OpenLayers.Layer.OSM.Osmarender(),
                'Mapnik'     : new OpenLayers.Layer.OSM.Mapnik()
                },
        'GMap': {
                'ROADMAP'    : new OpenLayers.Layer.Google( 'Google Streets', { numZoomLevels : 20 } )
                }
    },
    tt : null,

    Render : function(){

        var self = this,
            options = self.configuration,
            mapLayerType, typeLength,
            mapLayer = [],
            cd;

        options = $.extend( self.configuration, oxMap.Config );

        mapLayerType = options.type;
        typeLength = mapLayerType.length;

        if( !mapLayerType || typeLength < 1 ){
            new oxMap.Error( 'wms', options.wms + ' ' + options.type );
            return false;
        }

        self.osmOptions.controls = self.osmOptions.controls.concat( new oxMap.OSM.Controls( options.mapControls ) );

        for( var a = 0; a < typeLength; a += 1 ){
            mapLayer.push( self.wms[ options.wms ][ mapLayerType[a] ] );
        }

        oxMap.OSM.map = new OpenLayers.Map( options.mapID, self.osmOptions );
        oxMap.OSM.map.addLayers( mapLayer );
        oxMap.OSM.map.setCenter(
            oxMap.OSM.LonLat( options.center ),
            options.startLevel
        );

        if( options.custom && options.custom.bounds ){
            oxMap.OSM.map.setOptions(
                $.extend( oxMap.OSM.map.options, {
                    restrictedExtent : oxMap.Helper.OL.createBoundObject( options.custom.bounds, 'WGS84')
                })
            );
        }

        oxMap.OSM.map.events.on({
        	'zoomend' : function(){
        		if( oxMap.OSM.tt ){
        			oxMap.OSM.tt.parentNode.removeChild( oxMap.OSM.tt );
        			oxMap.OSM.tt = null;
        		}
        	}
        })

        oxMap.OSM.configuration.size = oxMap.OSM.map.getSize();

        oxMap.OSM.wmsLayer = mapLayer;

        oxMap.data = rawdata || (function(){ new oxMap.Error( 'data' ) })();

    },

    Controls : function( controls ){

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

    },

    LonLat : function( aCoorPair ){

        if ( aCoorPair.length === 2 ){
            return new OpenLayers.LonLat( aCoorPair[ 0 ], aCoorPair[ 1 ] )
                        .transform( oxMap.Helper.OL.transformer.WGS84, oxMap.Helper.OL.transformer.map() );
        }
        return false;

    }

};