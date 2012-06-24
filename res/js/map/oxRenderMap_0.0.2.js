/*
 *  oxMapRender for using in TYPO3 browser only
 * 
 *  author:    o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2012-02-20
 *  version: 0.0.2
 *  
 * */

var oxMapRender = function(){
    'use strict';
    var self = this;

    this.init = function( oConfig ){                                                                //  default initialize method
        if(!oConfig)                                                                                //  needs map configuration like oxMapConfig.js
            return false;                                                                           //  otherwise it can't render any map

        oConfig.Marker = markerData;

        var aAllowedWMS = [ 'OSM' ],                                                                //  list of allowed WMS like OpenStreetMap
            sWms = null,
            oWmsLayer = null,
            oWmsConfig = {},
            mapOptions = {                                                                          //  define map options from configuration
                controls: (function(){                                                              //  control types from oxMapConfig.MapControls
                              var controlsTypes = [ new OpenLayers.Control.Attribution() ];
                              if(oConfig.MapControls)
                                  for(var a = oConfig.MapControls.length; a--; )
                                      controlsTypes.push( new OpenLayers.Control[ oConfig.MapControls[a] ]() );
                              return controlsTypes;
                          })(),
                numZoomLevels: oConfig.MapZoomLevels || 16,
                units: 'm'
            },
            oxMap, oCenterCoor;

        oxMapRender.prototype.oxMapConfig = oConfig;                                                //  inherit configuration to oxMapRender constructor

        if(!self.oxMapConfig.MapId)                                                                 //  if there no map canvas ID render stops
            return;

        this.oxMap = oxMap = self.setMap( self.oxMapConfig.MapId, mapOptions );                     //  start map rendering with setMap-method

        for(var a = aAllowedWMS.length; a--; )                                                      //  bind allowed WMS to map object
            if(self.oxMapConfig[ aAllowedWMS[a] ]){
                oWmsConfig = self.oxMapConfig[ aAllowedWMS[a] + 'Config' ];                         //  WMS configuration for map drawing is set --> oxMapConfig.OSMConfig
                oWmsLayer = self.addDefaultMapLayer( aAllowedWMS[a] , oWmsConfig.type );            //  set map layer from given WMS
                oxMap.addLayer( oWmsLayer );                                                        //  and added to map object
                break;
            }

        oCenterCoor = self.setCoordinates( oWmsConfig.center );                                     //  center map to coordinates
        oxMap.setCenter(oCenterCoor, oWmsConfig.zoom || 4);                                         //  oxDefaultZoomLevel;
    };

    this.setMap = function( oxMapId, oxMapOptions ){                                                //  create default map object
        return new OpenLayers.Map( oxMapId, oxMapOptions );
    };

    this.addDefaultMapLayer = function( sWms, sWmsConfig ){                                         //  create default map layer, default OSM.Mapnik
        if(sWms == 'OSM')
            switch(sWmsConfig){
                case 'Osmarender':  return new OpenLayers.Layer.OSM.Osmarender(); break;
                default:            return new OpenLayers.Layer.OSM.Mapnik(); break;
            }
    };

    this.setCoordinates = function( aCoorPair ){                                                    //  return OpenLayers.LonLat object
        if(aCoorPair.length == 2)
            return new OpenLayers.LonLat( aCoorPair[0], aCoorPair[1] )
                        .transform(
                            new OpenLayers.Projection( "EPSG:4326" ),                               // transform from WGS 1984
                            self.oxMap.getProjectionObject()                                        // to Spherical Mercator Projection
                        );
        else
            return false;
    };

    this.setMarker = function( oMarker ){                                                           //  set stand alone marker with further meta data
        var marker, m, n, icon, box,
            sCategory = null,
            oCategoryData = null;

        OpenLayers.Marker.prototype.name = '';
        OpenLayers.Marker.prototype.category = '';

        for(sCategory in oMarker){                                                                  //  check category for data
            oCategoryData = oMarker[ sCategory ];

            if(!oCategoryData.data)                                                                 //  if there no data break routine
                continue;

            marker = new OpenLayers.Layer.Markers( sCategory );                                     //  set new marker layer
            self.oxMap.addLayer( marker );                                                          //  and bind it to map object

            if(oCategoryData.icon)                                                                  //  create custom icons
                icon = self.createIcon( oCategoryData.icon );                                       //  image source from oxMapConfig->category

            $.each(oCategoryData.data, function( key, kVal){
                n = self.setCoordinates( kVal.coors );                                              //  make an OpenLayers.LonLat object for drawing
                m = new OpenLayers.Marker( n, icon.clone() );                                       //  create OpenLayers.Marker object
                m.name = key;                                                                       //  give marker a name
                m.category = sCategory;                                                             //  and his category

                if(self.oxMapConfig.MapMarkerEvent == 'on'){                                        //  if only one marker exists, open info box automatically
                    self.setLayer( null, m );
                    self.oxMapConfig.MapMarkerEvent = 'click';
                }

                m.events.on( self.eventHandling( self.oxMapConfig.MapMarkerEvent ) );               //  bind browser event to marker

                marker.addMarker( m );                                                              //  add marker to special category marker layer

            });
        }
    };

    this.eventHandling = function(event){                                                           //  event handling function for controlling event methods
        switch(event){                                                                              //  returns different OL event handling objects
            case 'hover':
                return {
                    'mouseover': self.setLayer,
                    'mouseout': self.setLayer
                };
            default:
                return {
                    'click': self.setLayer
                };
        }
    };

    this.layerOn = false;                                                                           //  buffer for opened layer
    this.setLayer = function( event, marker ){                                                      //  create an info box layer as separate DIV and put data form JSON into it
        var marker = marker ? marker : this,
            name = marker.name,
            cat = marker.category,
            iconDiv = $(marker.icon.imageDiv),
            infoBox = $('<div class="oxTextLayer"></div>'),
            local = self.oxMapConfig.Marker[ cat ],
            data = local[ 'data' ];

        $.each(data, function( dataName, content ){
            if(dataName == name){
                if(self.layer){
                    self.layer.remove();
                    self.layer = false;
                }
                else if(content.desc){
                    infoBox.attr( 'id', 'oxTextLayer-' + name )                                     //  add ID for more flexibility
                           .html( content.desc )                                                    //  add content
                           .css({
                                'left': local[ 'icon' ][1] + 3,
                                'top':  local[ 'icon' ][2] / 2  });
                    iconDiv.append( infoBox )                                                       //  put layer into same DIV like icon image
                    self.layer = infoBox;
                }
                return false;
            }
        });
    };

    this.createIcon = function( param ){                                                            //  customized icon creator by using OpenLayers.Icon object 
        var icon = param[0],
            size = new OpenLayers.Size( param[1] , param[2] ),
            offset = new OpenLayers.Pixel( param[3] , param[4] );
        return new OpenLayers.Icon( icon, size, offset );
    };

    this.filterLayer = function( layerName, switchTo ){                                             //  filter layer for drawing on map --> layerName or category, filter property on | off or value between 0 and 1
        var layer = self.oxMap.getLayersByName( layerName );                                        //  find layer by name inside map object
        for(var a = layer.length;a--; )
            if(typeof switchTo === 'boolean')                                                       //  check visibility for visible | hidden -> use css display property
                layer[a].display( switchTo );
            else if(typeof switchTo === 'number')                                                   //  check transparency
                layer[a].setOpacity( switchTo > 0 && switchTo <=1
                                        ? switchTo
                                        : switchTo < 0
                                            ? 0
                                            : 1 );
    };
};

var map = new oxMapRender()                                                                         //  for render an map build a new instance of oxMapRender
map.init( oxMapConfig );                                                                            //  initialize render object by direct configuration input

if(oxMapConfig.MapMarker){                                                                          //  marker method should be started outside oxMapRender
    map.setMarker( oxMapConfig.Marker );                                                            //  start marker method marker with data from data.json
}