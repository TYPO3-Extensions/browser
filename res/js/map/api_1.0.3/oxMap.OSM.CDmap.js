
'use strict';

window.oxMap.OSM.CDmap = function( data ){

	var self = this,
		custom = {},
		imageType = oxMap.Helper.getUsedImageType(),
		onLevel = 0,
		maxLevel;

    this.customMapLayer = function(){

    	var image = self.customZoomLevel[ 0 ] ? custom[ imageType ][ 0 ] : null;
    	return new OpenLayers.Layer.Image(
    		'customMapLayer',
    		image,
    		oxMap.Helper.OL.createBoundObject( custom.bounds, 'WGS84' ),
    		new OpenLayers.Size( custom.size.w, custom.size.h ),
            { 
    			isBaseLayer				: false,
                opacity					: 1,
                displayOutsideMaxExtent	: false,
                alwaysInRange			: true
            }
    	);

    };

    this.mapControl = function(){
    	oxMap.OSM.map.events.on({
    		'zoomend' 	: self.customZoomControl
    	});
    };
    
    this.customZoomControl = function(){
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

    this.zoomLevel = [];
    this.customZoomLevel = [];

	this.setup = (function(){
		var a, b, level;

		if( !oxMap.Config.custom ){
			new oxMap.Error( 'custom' );
			return false;
		}

		custom = $.extend( custom, oxMap.Config.custom );

		if( custom.imageType && custom.imageType === 'png' ){
			imageType = 'png';
		}

		oxMap.Helper.preLoadLayerImages( custom[ imageType ] );

		level = oxMap.Config.startLevel;
		for( a = 0, b = oxMap.Config.numZoomLevels; a < b; a += 1 ){
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