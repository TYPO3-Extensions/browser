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
                      custom:    {
                        type: 'customBBOX',                            //    custom layer type
                        content: {
                          sort : 'image',
                          size: {                                    //    image size in pixel units
                            w: 620,
                            h: 715    
                          },
                          bounds: {                                  //    bounding box coordinates for the restricted extent
                            w: 11.29976,
                            s: 50.95955,
                            e: 11.35304,
                            n: 50.99803
                          },
                          png:{                                      //    by default PNG image or for browser, that don't support SVG
                            0: 'img/ci_map_v2.level0.png',
                            1: 'img/ci_map_v2.level1.png',
                            2: 'img/ci_map_v2.level2.png'
                          },
                          svg: {                                     //    modern browser support SVG, better image quality in maps !!
                            0: 'img/ci_map_v2.level0.svg',
                            1: 'img/ci_map_v2.level1.svg',
                            2: 'img/ci_map_v2.level1.svg'
                          }
                        },
                        numZoomLevels: 3
                      },
                      zoomStartLevel: 14                                 //    integer (0 - 19) set zoom level
                    }
};