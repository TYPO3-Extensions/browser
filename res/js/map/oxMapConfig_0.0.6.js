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
                    name:    'planUniversityWeimar',                   //    layer name
                    //center:  [ 11.32787,50.976952 ],                 //    setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X
                    inBounds:{                                         //    bounds of OSM area -- partially it have to manipulate by hand
                        w: 11.3120,
                        s: 50.9614,
                        e: 11.3390,
                        n: 50.9921
                    },
                    layer: {
                        bounds: {                                      //    bounds of the custom image getting from OSM
                            w: 11.3120,
                            s: 50.9614,
                            e: 11.3418,
                            n: 50.9921
                        },
                        size: {                                        //    image size in pixel units
                            w: 628,
                            h: 715    
                        },
                        png:{                                          //    by default PNG image or for browser, that don't support SVG
                            0: 'img/ci_map_export_1.png',
                            1: 'img/ci_map_export_2.png',
                            2: 'img/ci_map_export_3.png'
                        },
                        svg: {                                         //    modern browser support SVG, better image quality in maps !!
                            0: 'img/ci_map_export.svg'
                        },
                        numZoomLevels: 3
                        }
                    }
};