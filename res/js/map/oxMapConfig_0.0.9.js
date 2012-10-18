/*
 *  map configuration for rendering a map
 * 
 *  author:    o'xkape, Mike Kunert <warum@oxkape.de>
 *  copyright: o'xkape <http://www.webkartografie.de> by <http://www.oxkape.de>
 *  date: 2012-10-18
 *  
 * */

var oxMapConfig = {
    MapId:          'oxMapArea',                                       //    HTML map viewport id
    MapControls:    ['Navigation','PanZoom'],                          //    customized control types
    MapZoomWheel:   false,
    MapZoomLevels:  3,                                                 //    customized zoom levels
    MapMarker:      true,                                              //    stand alone marker is allowed
    MapMarkerInfo:  true,                                              //    marker with information layer is allowed
    MapMarkerEvent: 'hover',                                           //    click | hover | off | on (for drawing one point only)

    OSM:            true,                                              //    WMS type OSM
    OSMConfig:      {
                      type:    'Mapnik',                                 //    Mapnik
                      name:    'OpenStreetMap (Mapnik)',                 //    OpenStreetMap (Mapnik)
                      center:  [ 11.322578,50.98098 ],                   //    setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                      zoomStartLevel: 14                                 //    integer (0 - 19) set zoom level
                    }
};