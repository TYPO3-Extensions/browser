configuration = {
	language		: 'de'
  , wms				: 'OSM'
  , type			: 'Mapnik'        //   Mapnik (OSM) | ROADMAP (GoogleMaps)
  , modules			: 'Error,OSM.Route,OSM.Base,OSM.Filter'

	  		// default 	: Error
	  		// OSM 		: OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route
	  		

  , mapControls		: 'Navigation,PanZoom,LayerSwitcher'
  , zoomWheelEnable : false

  , center	        : {
						lon : 13.18222
					  , lat : 54.50434
					  }
  , startLevel	    : 10
  , numZoomLevels   : 1

  , mapMarkerEvent  : 'hover'

  , customMap       : {
      type: 'Image'
    , size: {
		w:474
	   ,h:270
      }
  /*
    , bounds      : {                                                                //  define bounds for pan inside a smaller area
    	w : 12.40665
       ,s : 54.37216
       ,e : 13.74733
       ,n : 54.7028
      }
  */
    , imageType   : 'png'                                                            //  SVG is in progress
    , png         :{
          0: 'img/customMap_1.png'
      }
    , numZoomLevels: 1
    , startLevel  : 10
  }
  
  , route : {
	  data : routes
	, defaultStyle : {
	    strokeWidth : 1
	  , strokeColor : '#000'
  	  }
  }
};