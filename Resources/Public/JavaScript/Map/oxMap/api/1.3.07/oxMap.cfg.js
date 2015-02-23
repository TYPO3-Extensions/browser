configuration = {
	language		: 'de'
  , wms				: 'OSM'
  , type			: 'ROADMAP'        //   Mapnik (OSM) | ROADMAP (GoogleMaps)
  , modules			: 'Error,OSM.Route,OSM.Base,OSM.Filter'

	  		// default 	: Error
	  		// OSM 		: OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route
	  		
  , imagePath	: 'img/ol2.12/'
  , cssPath	   : 'img/ol2.12/css/style.css'
  , mapControls		: 'Navigation,PanZoom,LayerSwitcher'
, mapID : 'oxMap-area'
  , zoomWheelEnable : false
  
  , filter : {
		wrapper : 'oxMap-filter-module'
	}

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