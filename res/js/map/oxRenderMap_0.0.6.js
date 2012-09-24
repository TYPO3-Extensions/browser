/*
 *  oxMapRender for using in TYPO3 browser only
 * 
 *  author:    o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2012-02-20
 *  
 * */

var oxMapRender = function(){
    'use strict';
    var self = this,
        mapGlobalOptions = null;

    this.init = function( oConfig ){                                                                //  default initialize method
        if(!oConfig)                                                                                //  needs map configuration like oxMapConfig.js
            return false;                                                                           //  otherwise it can't render any map

        oConfig.Marker = rawdata;

        var aAllowedWMS = [ 'OSM','Custom' ],                                                       //  list of allowed WMS like OpenStreetMap
            sWms = null,
            bConfigExists = false,
            oWmsLayer = null,
            oWmsConfig = {},
            mapOptions = {                                                                          //  define map options from configuration
                controls: (function(){                                                              //  control types from oxMapConfig.MapControls
                              var controlsTypes = [ new OpenLayers.Control.Attribution() ];
                              if ( oConfig.MapControls ) {
                                  for ( var a = 0, b = oConfig.MapControls.length; a < b; a += 1 ) {
                                      controlsTypes.push( new OpenLayers.Control[ oConfig.MapControls[a] ]() );
                                  }
                              }
                              return controlsTypes;
                          })(),
                numZoomLevels: oConfig.MapZoomLevels || 16,
                units: 'm'
            },
            oxMap, oCenterCoor;

        oxMapRender.prototype.oxMapConfig = oConfig;                                                //  inherit configuration to oxMapRender constructor

        if( !self.oxMapConfig.MapId ){                                                              //  if there no map canvas ID render stops
            return;
        }

        self.oxMap = oxMap = self.setMap( self.oxMapConfig.MapId, mapOptions );                     //  start map rendering with setMap-method
        mapGlobalOptions = mapOptions;

        for ( var a = 0, b = aAllowedWMS.length; a < b; a += 1 ) {                                  //  bind allowed WMS to map object
            bConfigExists = self.oxMapConfig[ aAllowedWMS[ a ] + 'Config' ];

            if( bConfigExists ){
                oWmsConfig = bConfigExists;                                                         //  WMS configuration for map drawing is set --> oxMapConfig.OSMConfig
                oWmsLayer = self.addDefaultMapLayer( aAllowedWMS[ a ] , oWmsConfig );               //  set map layer from given WMS
                oxMap.addLayer( oWmsLayer );                                                        //  and added to map object
            }

            if( oWmsConfig.center ){
                oCenterCoor = self.setCoordinates( oWmsConfig.center );                             //  center map to coordinates
                oxMap.setCenter(oCenterCoor, oWmsConfig.zoom || 4);                                 //  oxDefaultZoomLevel;
            }
            else if( oWmsConfig.inBounds ){
                self.oxMap.zoomToMaxExtent();
            }
        }
    };

    this.setMap = function( oxMapId, oxMapOptions ){                                                //  create default map object
        return new OpenLayers.Map( oxMapId, oxMapOptions );
    };

    this.addDefaultMapLayer = function( sWms, oWmsConfig ){                                         //  create default map layer, default OSM.Mapnik
        if( sWms === 'OSM' ){
            switch( oWmsConfig.type ){
                case 'Osmarender': return new OpenLayers.Layer.OSM.Osmarender(); break;
                default          : return new OpenLayers.Layer.OSM.Mapnik(); break;
            }
        }
        else if( sWms === 'Custom' ){
            return self.customMapLayer( oWmsConfig );
        }
    };

    this.setCoordinates = function( aCoorPair ){                                                    //  return OpenLayers.LonLat object
        if ( aCoorPair.length === 2 ){
            return new OpenLayers.LonLat( aCoorPair[0], aCoorPair[1] )
                        .transform(
                            new OpenLayers.Projection( "EPSG:4326" ),                               // transform from WGS 1984
                            //new OpenLayers.Projection( "EPSG:900913" ),
                            self.oxMap.getProjectionObject()                                        // to Spherical Mercator Projection
                        );
        }
        else {
            return false;
        }
    };

    this.setMarker = function( oMarker ){                                                           //  set stand alone marker with further meta data
        var marker, m, n, icon, box,
            sCategory = null,
            oCategoryData = null;

        OpenLayers.Marker.prototype.name = '';
        OpenLayers.Marker.prototype.category = '';

        for ( sCategory in oMarker ) {                                                              //  check category for data
            oCategoryData = oMarker[ sCategory ];

            if ( !oCategoryData.data ){                                                             //  if there no data break routine
                continue;
            }

            marker = new OpenLayers.Layer.Markers( sCategory );                                     //  set new marker layer
            self.oxMap.addLayer( marker );                                                          //  and bind it to map object

            if ( oCategoryData.icon ) {                                                             //  create custom icons
                icon = self.createIcon( oCategoryData.icon );                                       //  image source from oxMapConfig->category
            }

            $.each(oCategoryData.data, function( key, kVal){
                var mHTML;

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
                
                mHTML = $( m.icon.imageDiv );                                                       //    HTML wrapper around marker icon

                if ( kVal.url && self.oxMapConfig.MapMarkerEvent != 'click' ) {                     //    if marker has an url
                    var link = $('<a></a>').attr( 'href', kVal.url )                                //    wrap icon with A element, set url
                                           .addClass( 'markerLink' );                               //    and css class for styling

                    mHTML.find('img').wrap( link );                            
                }

                if ( kVal.number ) {                                                                //    plus a number if set
                    mHTML.append( '<span class="markerNumber">' + kVal.number + '</span>' );
                }
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
            case 'click':
                return {
                    'click': self.setLayer
                };
            default:
                return null;
        }
    };

    this.layerOn = false;                                                                           //  buffer for opened layer
    this.setLayer = function( event, marker ){                                                      //  create an info box layer as separate DIV and put data form JSON into it
        var marker = marker ? marker : this,
            name = marker.name,
            cat = marker.category,
            iconDiv = $( marker.icon.imageDiv ),
            infoBox = $( '<div class="oxTextLayer"></div>' ),
            local = self.oxMapConfig.Marker[ cat ],
            data = local[ 'data' ],
            htmlMarker, zIndex;

        if( event ){
            htmlMarker = iconDiv.parent('.olLayerDiv')[ 0 ];
            zIndex = htmlMarker.getAttribute( 'data-zIndex' );
                                                                                                    //     Fixes zIndex problem with marker and custom text layer
            if( !zIndex ){                                                                            //    --mouseover
                htmlMarker.setAttribute( 'data-zIndex', htmlMarker.style.zIndex );                    //    save origin z-index inside data an attribute
                htmlMarker.style.zIndex = 100000;                                                    //    set a large z-index
            }
            else{                                                                                    //    --mouseout
                htmlMarker.style.zIndex = zIndex;                                                    //    set saved z-index from data attribute
                htmlMarker.removeAttribute( 'data-zIndex' );                                        //    and remove useless data attribute
            }
        }

        $.each(data, function( dataName, content ){
            if(dataName == name){
                if( self.layer ){
                    self.layer.remove();
                    self.layer = false;
                }
                else if( content.desc ){
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

    this.createBoundObject = function( aBoundData ){
        var W = aBoundData.w,
            S = aBoundData.s,
            E = aBoundData.e,
            N = aBoundData.n,
            
            bounds = new OpenLayers.Bounds( W,S, E,N );
        /*
        bounds.transform(
                //new OpenLayers.Projection( "EPSG:4326" ),                               // transform from WGS 1984
                new OpenLayers.Projection( "EPSG:900913" ),
                self.oxMap.getProjectionObject() 
                );
        */
        return bounds;
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

    this.customMapLayer = function( param ){                                                        //  method for setup and binding custom layer map onto OSM
        var size = new OpenLayers.Size( 
                param.layer.size.w,
                param.layer.size.h
            ),
            bounds = self.createBoundObject( param.inBounds );

        self.oxMap.setOptions(
            $.extend( mapGlobalOptions, {                                                           //  specific map options for a custom image layer
                restrictedExtent: bounds,                                                           //  bounds of map area for pan and zoom
                maxExtent:        bounds                                                            //  maximum bounds of map area
            })
        );
        
        function controlCustomZoom( layerImages, type, name ){                                      //  custom layer zoom control
            if(type === 'svg'){                                                                     //  currently not for SVG support
                return false;
            }

            self.oxMap.events.on({                                                                  //  bind zoom event on map
                'zoomend' : function(){
                    var url = layerImages[ this.getZoom() ],                                        //  get image url by reading zoom level after zooming
                        layer = this.getLayersByName( name )[ 0 ];                                  //  get custom layer inside map
                    layer.setUrl( url );                                                            //  and set new image by url depends on zoom level and position from image list (list defined in config)
                }
            });
        }

        function setImageInsideBrowser(){                                                           //  check supported image type PNG or SVG
            var imageSupport = ( $.browser.msie && parseInt( $.browser.version ) < 9 ) ? 'png' : 'svg';
            imageSupport = param.layer[ imageSupport ] ? imageSupport : 'png';                      //  PNG by default

            controlCustomZoom( param.layer[ imageSupport ], imageSupport, param.name );             //  initialize zoom handling for this custom layer

            return param.layer[ imageSupport ][ 0 ];                                                //  return start image
        }
        return new OpenLayers.Layer.Image(
            param.name,                                                                             //  image layer name
            setImageInsideBrowser(),                                                                //  image uri
            self.createBoundObject( param.layer.bounds ),                                           //  image bounds inside OSM coordinates
            size,                                                                                   //  image layer size in pixel
            {                                                                                       //  special image layer options
            numZoomLevels: param.layer.numZoomLevels,
            projection: new OpenLayers.Projection("EPSG:4326")
            }
        ); 
    };
};

/*
Klasse Filter
@parameter  map                map object from an oxRenderMap class
            filterItem        list of filter input ('checkboxes')
*/
/*
name    STRING      Layer-Name -->    es wird zwischen Markern ohne und mit Informationlayer unterschieden
                                        - einfache Markername bestehen aus dem Kategorienamen   --> 'cat1'
                                        - Infolayernamen betehen aus Kategoriename und Suffix 'Info'  --> 'cat1Info'
                                       um alle Layer zu schließen, muss die Methode so oft wiederholt werden, bis alle Kategorienamen erfasst wurden
        switchTo    BOOLEAN | FLOAT    Sichtbarkeitswert -->    true | false entspricht sichtbar bzw. unsichtbar
                                                            Zahl zwischen 0 uns 1 definiert Transparenzgrad
*/
function Filter( parameter ){
    'use strict';

    var self = this,
        filter,
        status,
        mapObject;

    this.filterByChange = function( type ){                                                         //  read filter type
        this.filterByChange.change = function( filterItem ){
            filterItem.onchange = function(){                                                       //  if type 'change', add event listener on a filter item
                self.getFilterValues( this );                                                       //  get status of filter
            }
        };
        this.filterByChange.zoom = function( filterItem ){
            self.getFilterValues( filterItem );                                                     //  get status of filter
        };

        $.each( filter, function(){
            self.filterByChange[ type ]( this );                                                    //  starts type reading and filtering
        });
    };

    this.getFilterValues = function( filter){                                                       //  get filter data for filter marker and layer on a map
        var name = filter.name,
            value = filter.value,
            switchTo = filter.checked;

        filter.value = !switchTo ? 0 : 1;
        self.filterOnMap( name, switchTo );                                                         //  real filter on map starts
    };

    this.filterOnMap = function( name, switchTo ){                                                  //  separate filter on map method for using by others
        var nameType = ['', 'Info'],                                                                //  list of group of elements for one filter  [marker, layer]
            a, b = nameType.length;

        for( a = 0; a < b; a += 1 ){
            mapObject.filterLayer( name + nameType[ a ], switchTo );                                //  filter every group element
        }
    };

    this.setup = (function(){                                                                       //  setup filter class
        mapObject = parameter.mapObject;
        filter = parameter.filterItems;

        filter.attr('checked', 'checked');                                                          //  reset all filter and switch to ON

        self.filterByChange( 'change' );                                                            //  base filter with change event on filter items

        mapObject.oxMap.events.on({                                                                 //  filter after zooming -> bug from OSM ?? show all marker in new zoom level
            'zoomend' : function(){                                                                 //  if zoom ended
                self.filterByChange( 'zoom' );
            },
            'moveend' : function(){                                                                 //  and after paning map
                self.filterByChange( 'zoom' );
            }
        });
    })();
}

var map = new oxMapRender()                                                                         //  for render an map build a new instance of oxMapRender
map.init( oxMapConfig );                                                                            //  initialize render object by direct configuration input

if(oxMapConfig.MapMarker){                                                                          //  marker method should be started outside oxMapRender
    map.setMarker( oxMapConfig.Marker );                                                            //  start marker method marker with data from data.json
}

var filter = new Filter({
    mapObject : map,                                                                                //  map object from a new oxMapRender class
    filterItems : $('.oxMapFilter')                                                                 //  filter elements get from DOM
});