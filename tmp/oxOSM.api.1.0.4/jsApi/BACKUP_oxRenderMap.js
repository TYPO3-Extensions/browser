/*
 * oxMapRender for using in TYPO3 browser only
 * This JS-code is not allowed to manipulate by others then the author or by compress
 * 
 *  author:	o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2011-12-10
 *  
 * */

var oxMapRender = function(){
	var self = this;
	
	this.init = function(oConfig){																	//	default initialize method
		if(!oConfig)																				//	needs map configuration like oxMapConfig.js
			return false;																			//	otherwise it can't render any map
		
		var aAllowedWMS = ['OSM'],																	//	list of allowed WMS like OpenStreetMap
			sWms = null,
			oWmsLayer = null,
			oWmsConfig = {},
			impr = (function(){																		//	define imprint and license with WMS -> have to set
					var i = document.createElement('div');
					i.setAttribute('style','text-align:right');
					i.innerHTML = "<a href=\"http://www.webkartografie.de\">WEBkartografie mit o'kape</a>";
					return i;
					})(),
			mapOptions = {																			//	define map options from configuration
			controls: (function(){																	//	control types from oxMapConfig.MapControls
						controlsTypes = [new OpenLayers.Control.Attribution()];
						if(oConfig.MapControls)
							for(var a=oConfig.MapControls.length;a--;)
								controlsTypes.push(new OpenLayers.Control[oConfig.MapControls[a]]());
						return controlsTypes;
					  })(),
			numZoomLevels: oConfig.MapZoomLevels || 16,
			units: 'm'
		};

		oxMapRender.prototype.oxMapConfig = oConfig;												//	inherit configuration to oxMapRender constructor
		
		if(!self.oxMapConfig.MapId)																	//	if there no map canvas ID render stops
			return;
		var oxMap = this.oxMap = self.setMap(self.oxMapConfig.MapId, mapOptions);					//	start map rendering with setMap-method
		for(var a = aAllowedWMS.length; a--; )														//	bind allowed WMS to map object
			if(self.oxMapConfig[aAllowedWMS[a]]){
				oWmsConfig = self.oxMapConfig[aAllowedWMS[a] + 'Config'];							//	WMS configuration for map drawing is set --> oxMapConfig.OSMConfig
				oWmsLayer = self.addDefaultMapLayer( aAllowedWMS[a] , oWmsConfig.type );			//	set map layer from given WMS
				oxMap.addLayer(oWmsLayer);															//	and added to map object
				break;
			}

		var oCenterCoor = self.setCoordinates(oWmsConfig.center);									//	center map to coordinates
		oxMap.setCenter(oCenterCoor, oWmsConfig.zoom || 4);											//	oxDefaultZoomLevel;
		
		var cc = document.getElementById(mapOptions.controls[0].id);
		setTimeout(function(){
			cc.appendChild(impr);	
		},25);
	};
	
	this.setMap = function(oxMapId,oxMapOptions){													//	create default map object
		return new OpenLayers.Map(oxMapId,oxMapOptions);
	};
	
	this.addDefaultMapLayer = function(sWms,sWmsConfig){											//	create default map layer, default OSM.Mapnik
		if(sWms == 'OSM'){
			switch (sWmsConfig){
				case 'Mapnik': 		return new OpenLayers.Layer.OSM.Mapnik(); break;
				case 'Osmarender': 	return new OpenLayers.Layer.OSM.Osmarender(); break;
				default:			return new OpenLayers.Layer.OSM.Mapnik(); break;
			}
		}
	};
	
	this.setCoordinates = function(aCoorPair){														//	return OpenLayers.LonLat object
		if(aCoorPair.length == 2)
			return new OpenLayers.LonLat(aCoorPair[0],aCoorPair[1])
						.transform(
							new OpenLayers.Projection("EPSG:4326"), 								// transform from WGS 1984
							self.oxMap.getProjectionObject() 										// to Spherical Mercator Projection
						);
		else
			return false;
	};
	
	this.setMarkerWithLayer = function(oMarker){													//	set marker with has an infobox
		var box;
		var sCategory = null;
		var oCategoryData = null;

		for(sCategory in oMarker){																	//	check category for data 
			oCategoryData = oMarker[sCategory];
			
			if(!oCategoryData.url)																	//	OpenLayers.Layer.Text needs a .txt with marker data --> text.txt
				continue;
			
			box = self.infoBox(self.oxMap,sCategory,oCategoryData);									//	add marker and infobox to map object
			self.oxMap.addLayer( box );
		}
	};
	
	this.setMarker = function(oMarker){																//	set standalone marker with further meta data
		var marker,m,n,icon,box;
		var sCategory = null;
		var oCategoryData = null;

		for(sCategory in oMarker){																	//	check category for data
			oCategoryData = oMarker[sCategory];

			if(!oCategoryData.data)																	//	if there no data break routine
				continue;

			marker = new OpenLayers.Layer.Markers(sCategory);										//	set new marker layer
			self.oxMap.addLayer( marker );															//	and bind it to map object
			
			if(oCategoryData.icon)																	//	create custom icons
				icon = self.iconGroup[sCategory] = self.createIcon(oCategoryData.icon);				//	from image url from oxMapConfig->category
		
			for(var a = 0, b = oCategoryData.data.length; a < b; a++ ){								//	check coordinates
				n = self.setCoordinates([ oCategoryData.data[a] , oCategoryData.data[++a] ]);		//	make an OpenLayers.LonLat object for drawing
				m = new OpenLayers.Marker(n,icon.clone());											//	create OpenLayers.Marker oject
					
				marker.addMarker( m );																//	and add them to marker layer
			}
		}
	};
	
	this.createIcon = function(param){																//	customized icon creater by using OpenLayers.Icon object 
		var icon = param[0];
		var size = new OpenLayers.Size( param[1] , param[2]);
		var offset = new OpenLayers.Pixel( param[3] , param[4] );
		return new OpenLayers.Icon( icon, size, offset );
	};
	
	this.filterLayer = function(layerName,switchTo){												//	filter layer for drawing on map --> layerName or category, filter property on | off or value between 0 and 1
		var layer = self.oxMap.getLayersByName(layerName);											//	find layer by name inside map object
		for(var a = layer.length;a--;)
			if(typeof switchTo === 'boolean')														//	check visibility for visible | hidden -> use css display property
				layer[a].display(switchTo);
			else if(typeof switchTo === 'number')													//	check transparency
				layer[a].setOpacity( (switchTo > 0 && switchTo <=1) ? switchTo : (switchTo < 0) ? 0 : 1);
	};
	
	this.infoBox = function(oMap,sCategory,oCategoryData){											//	build an infobox object form given data src .txt-file --> text.txt 
		return new OpenLayers.Layer.Text(sCategory+'Info',{
			location: oCategoryData.url,
			projection: oMap.displayProjection
			});
	};

};


var map = new oxMapRender()																			//	for render an map build a new instance of oxMapRender
map.init(oxMapConfig);																				//	initialize render object by direct config input

if(oxMapConfig.MapMarker){																			//	marker method should be started outside oxMapRender
	oxMapRender.prototype.iconGroup = [];															//	for better availability
	map.setMarker(oxMapConfig.Marker);																//	start marker method for standalone marker with direct marker configuration input
	map.setMarkerWithLayer(oxMapConfig.Marker);														//	start marker with layer method with direct marker configuration input
}