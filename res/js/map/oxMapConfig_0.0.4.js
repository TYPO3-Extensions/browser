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
    MapControls:    ['Navigation','PanZoomBar'],                       //    customized control types
    MapZoomLevels:  10,                                                //    customized zoom levels
    MapMarker:      true,                                              //    standalone marker is allowed
    MapMarkerInfo:  true,                                              //    marker with infolayer is allowed
    MapMarkerEvent: 'hover',                                           //    click | hover | off | on (for drawing one point only)

    Custom:         true,                                              //    WMS type Custom Image Layer, base WGS 84
    CustomConfig:   {
                    type:    'Custom',                                 //    Custom Map
                    name:    'Lageplan Universität Weimar',            //    
                    //center:  [ 11.32787,50.976952 ],                 //    setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                    inBounds:{                                         //    bounds of OSM area -- partially it have to manipulate by hand
                        w: 11.3115,
                        s: 50.9619,
                        e: 11.3385,
                        n: 50.9926
                    },
                    layer: {
                            bounds: {                                  //    bounds of the custom image getting from OSM
                                w: 11.3115,
                                s: 50.9619,
                                e: 11.3413,
                                n: 50.9926
                                },
                            size: {                                    //    image size in pixel units
                                w: 628,
                                h: 715    
                                },
                            png:{                                      //    by default PNG image or for browser, that don't support SVG
                                0: 'img/ABmap.png'
                            },
                            /*
                            svg: {                                     //    modern browser support SVG, better image quality in maps !!
                                0:    '/UniWeimar/img/map_weimar.png'
                            },
                            */
                            numZoomLevels: 3
                            }
                    }
};