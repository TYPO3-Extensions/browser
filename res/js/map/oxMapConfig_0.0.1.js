//	map configuration for rendering a map
//	only one WMS or map type allowed currently --> OSM OpenStreetMap

var oxMapConfig = {
	MapId:          'oxMapArea',                            //	HTML map viewport id
	MapControls:    ['Navigation', 'PanZoomBar'],						//	customized control types
	MapZoomLevels:	10,                                     //	customized zoom levels
	MapMarker:      true,                                   //	standalone marker is allowed
	MapMarkerInfo:	true,                                   //	marker with infolayer is allowed
	MapMarkerEvent:	'click',
	
	OSM:            true,                                   //	WMS type OSM
	OSMConfig:      {
                    type:	'Osmarender',                   //	Mapnik | Osmarender
                    name:	'OpenStreetMap Osmarender',			//	OpenStreetMap (Mapnik) | OpenStreetMap Osmarender
                    center:	[9.6075669,48.9459301],				//	setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                    zoom:	9
                  },
	Marker:         {
                    'cat1': {                             //	combined type with standalone marker and marker with infolayer
                      icon:	['typo3conf/ext/browser/res/js/map/test/img/test2.png', 14, 14, 0, 0],		//	icon -> url, width, height, offsetX, offsetY
                      data:	[9.5382032,48.9899851],  			//	coordinates for standalone marker
                      //,9.6075669,48.9459301,
                      url:	'typo3conf/ext/browser/res/js/map/test/data/text_0.0.1.txt'			//	url for infolayer data
                    },
                    'cat2': {                             //	marker with infolayer only
                      url:	'typo3conf/ext/browser/res/js/map/test/data/text2_0.0.1.txt'
                    }
                  }
};