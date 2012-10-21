/*
 *  map configuration for rendering a map
 * 
 *  author:    o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2012-02-20
 *  
 * */

var oxMapConfig = {
    MapId:          'oxMapArea',                                       //    HTML map viewport id
    MapControls:    ['Navigation','PanZoom'],                       //    customized control types
    MapZoomLevels:  3,                                                //    customized zoom levels
    MapMarker:      true,                                              //    standalone marker is allowed
    MapMarkerInfo:  true,                                              //    marker with infolayer is allowed
    MapMarkerEvent: 'hover',                                           //    click | hover | on (for drawing one point only)

    OSM:            false,                                             //    WMS type OSM
    OSMConfig:      {
                    type:    'Mapnik',                                 //    Mapnik
                    name:    'OpenStreetMap (Mapnik)',                 //    OpenStreetMap (Mapnik)
                    center:  [ 9.555442525, 48.933978799 ],            //    setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                    numZoomLevels: 6,
                    zoomStartLevel: 12                              //    integer (0 - 16) set zoom level
                    },

    GMap:           true,
    GMapConfig:     {
                    type:    'Street',
                    name:    'Google Streets',
                    center:  [ 9.555442525, 48.933978799 ],
                    zoomStartLevel:    11
                    }

};