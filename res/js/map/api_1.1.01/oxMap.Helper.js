
'use strict';

window.oxMap.Helper = {

	OL : {
		transformer : { 
			'null'	: null,
			'WGS84'	: new OpenLayers.Projection( "EPSG:4326" ),// transform from WGS 1984
			'OSM'	: new OpenLayers.Projection( "EPSG:4326" ),// transform from WGS 1984
			'GMap'	: new OpenLayers.Projection( "EPSG:900913" ),
			'map'	: function(){
					      return oxMap.OSM.map.getProjectionObject();  // to Spherical Mercator Projection
					  }
		},

	    createIcon : function( param ){
	        var icon = param[0],
	            size = new OpenLayers.Size( param[1] , param[2] ),
	            offset = new OpenLayers.Pixel( param[3] , param[4] );
	        return new OpenLayers.Icon( icon, size, offset );
	    },

	    createBoundObject : function( bounds, transformType ){
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
	},
	
    getEvent : function( eventName ){
        switch( eventName ){                                                                              //  returns different OL event handling objects
        case 'hover':
            return 'mouseover mouseout';
            break;
        case 'click':
            return 'click';
            break;
        default:
            return null;
        }
    },
    
    getUsedImageType : function(){
        return ( $.browser.msie && parseInt( $.browser.version ) < 9 ) ? 'png' : 'svg';
    },
    
	preLoadLayerImages : function( images ){
    	$.each( images, function( k, src ){
    		var img = new Image();
    		img.src = src;
    	});
    }

};