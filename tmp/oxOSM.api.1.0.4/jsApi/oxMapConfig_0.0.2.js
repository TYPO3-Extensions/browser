/*
 *  map configuration for rendering a map
 * 
 *  author:    o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2012-02-20
 *  version: 0.0.2
 *  
 * */

var oxMapConfig = {
    MapId:          'oxMapArea',                                       //    HTML map viewport id
    MapControls:    ['Navigation','PanZoomBar'],                       //    customized control types
    MapZoomLevels:  10,                                                //    customized zoom levels
    MapMarker:      true,                                              //    standalone marker is allowed
    MapMarkerInfo:  true,                                              //    marker with infolayer is allowed
    MapMarkerEvent: 'hover',                                           //    click | hover | on (for drawing one point only)

    OSM:            true,                                              //    WMS type OSM
    OSMConfig:      {
                    type:    'Mapnik',                                 //    Mapnik
                    name:    'OpenStreetMap (Mapnik)',                 //    OpenStreetMap (Mapnik)
                    center:  [ 9.555442525, 48.933978799 ],            //    setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                    zoom:    10                                        //    integer (0 - 16) set zoom level
                    }
};