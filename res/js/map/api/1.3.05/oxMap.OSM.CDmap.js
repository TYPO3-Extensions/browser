
'use strict';

window.CDmap = function( data ){

	var self = oxMap.OSM.CDmap,
		custom = {
			type : 'Image'
		},
		imageType = oxMap.Utils.getUsedImageType(),
		onLevel = 0,
		maxLevel;

    self.customMapLayer = function(){

    	var image = self.customZoomLevel[ 0 ] ? custom[ imageType ][ 0 ] : null;
    	return new OpenLayers.Layer.Image(
    		'customMapLayer',
    		image,
    		oxMap.OSM.Util.createBoundObject( custom.bounds, 'WGS84' ),
    		new OpenLayers.Size( custom.size.w, custom.size.h ),
            { 
    			isBaseLayer				: false,
                opacity					: 1,
                displayOutsideMaxExtent	: false,
                alwaysInRange			: true
            }
    	);

    };

    self.mapControl = function(){
    	oxMap.OSM.map.events.on({
    		'zoomend' 	: self.customZoomControl
    	});
    };
    
    self.customZoomControl = function(){
		var curLevel = this.getZoom();

		if( curLevel >= self.zoomLevel[ onLevel ] ){
			onLevel += 1;
		}
		else{
			onLevel -= 1;
		}

		if( onLevel >= 0 && onLevel <= maxLevel){
			if( self.customZoomLevel[ onLevel ] ){
				oxMap.OSM.wmsLayer[0].setVisibility( false );
				self.customMapLayer.setUrl( custom[ imageType ][ onLevel ] );
			}
			else{
				oxMap.OSM.wmsLayer[0].setVisibility( true );
				self.customMapLayer.setUrl( null );
			}
		}
		else if( onLevel > maxLevel ){
			oxMap.OSM.map.zoomTo( self.zoomLevel[maxLevel] );
			onLevel = maxLevel;
		}
		else{
			oxMap.OSM.map.zoomTo( self.zoomLevel[0] );
			onLevel = 0;
		}
	}

    self.zoomLevel = [];
    self.customZoomLevel = [];

	self.setup = (function(){
		var a, b, level;

		if( !oxMap.cfg.customMap ){
			new Error( 'custom', null, oxMap.cfg.error );
			return false;
		}

		custom = oxMap.Utils.merge( custom, oxMap.cfg.customMap );

		if( custom.imageType && custom.imageType === 'png' ){
			imageType = 'png';
		}

		oxMap.Utils.preLoadLayerImages( custom[ imageType ] );

		level = oxMap.cfg.startLevel;
		for( a = 0, b = oxMap.cfg.numZoomLevels; a < b; a += 1 ){
			self.zoomLevel[ a ] = level + a;
			self.customZoomLevel[ a ] = false;
		}
		maxLevel = self.zoomLevel.length
		for( a = 0, b = maxLevel; a < b; a += 1 ){
			if( self.zoomLevel[ a ] == custom.startLevel ){
				onLevel = a;
			}
		}
		for( a = onLevel, b = onLevel + custom.numZoomLevels; a < b; a += 1 ){
			self.customZoomLevel[ a ] = true;
		}
		onLevel = 0;

		self.mapControl();
		self.customMapLayer = self.customMapLayer();
		oxMap.OSM.wmsLayer[0].setVisibility( false );
		oxMap.OSM.map.addLayer( self.customMapLayer );

	})();
};