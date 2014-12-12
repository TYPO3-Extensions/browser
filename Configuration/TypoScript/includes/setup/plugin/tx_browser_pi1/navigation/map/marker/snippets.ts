plugin.tx_browser_pi1 {
  navigation {
    map {
        // snippets
      marker =
      marker {
          // jss, html: HTML code snippets and JSS code snippets. The properties correspond with the markers of the HTML marker map
        snippets =
        snippets {
            // dynamic
          jss =
          jss {
              // OpenLayersCss, OpenLayersImgPath, oxMapConfigCenter, oxMapConfigLanguage, oxMapConfigMapControls, oxMapConfigMapMarkerEvent, oxMapConfigZoomWheelEnable, oxMapConfigModules, oxMapConfigNumZoomLevels, oxMapConfigStartLevel, oxMapConfigType, oxMapConfigWms
            dynamic =
            dynamic {
                // Path to the folder with the css
              OpenLayersCss = TEXT
              OpenLayersCss {
                  // [STRING] Relative path. Be aware of the ending slash!
                value = '{$plugin.tx_browser_pi1.map.design.css}'
              }
                // Path to the folder with the icons of the control panel among others
              OpenLayersImgPath = TEXT
              OpenLayersImgPath {
                  // [STRING] Relative path. Be aware of the ending slash!
                value = '{$plugin.tx_browser_pi1.map.design.imgPath}'
              }
                // Default center of the map
              oxMapConfigCenter = TEXT
              oxMapConfigCenter {
                  // [STRING] setCenter coordinates [ longitude , latitude ]  longitude = Y, latitude = X. Example: [9.15,50.1725] <- Center of European Union
                value = { lon : 9.15, lat : 50.1725 }
              }
                // Default language for error prompts
              oxMapConfigLanguage = TEXT
              oxMapConfigLanguage {
                  // [STRING] default, en, de
                value = default
                lang {
                  de = de
                  en = en
                }
                wrap  = '|'
              }
                // Map controlling tools
              oxMapConfigMapControls = COA
              oxMapConfigMapControls {
                  // Debug mode off
                10 = TEXT
                10 {
                  if {
                    isFalse = {$plugin.tx_browser_pi1.map.debugging.feAlert}
                  }
                    // [STRING/CSV] Comma seperated list of quoted map controls wrapped by square brackets. Example: ['Navigation', 'PanZoomBar']
                  value = {$plugin.tx_browser_pi1.map.openlayers.controls.default}
                }
                  // Debug mode on
                20 = TEXT
                20 {
                  if {
                    isTrue = {$plugin.tx_browser_pi1.map.debugging.feAlert}
                  }
                    // [STRING/CSV] Comma seperated list of quoted map controls wrapped by square brackets. Example: ['Navigation', 'PanZoomBar']
                  value = {$plugin.tx_browser_pi1.map.openlayers.controls.debugging}
                }
              }
                // Map marker event type
              oxMapConfigMapMarkerEvent = COA
              oxMapConfigMapMarkerEvent {
                  // Touchscreen mode is disabled
                10 = TEXT
                10 {
                  if {
                    isFalse = {$plugin.tx_browser_pi1.map.mobileTouchscreen}
                  }
                    // [STRING] customized marker events
                  value = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
                }
                  // Touchscreen mode is enabled
                20 = TEXT
                20 {
                  if {
                    isTrue = {$plugin.tx_browser_pi1.map.mobileTouchscreen}
                  }
                    // [STRING] customized marker events
                  value = 'hover'
                }
              }
                // Map zoom wheel
              oxMapConfigZoomWheelEnable = TEXT
              oxMapConfigZoomWheelEnable {
                  // [BOOLEAN] enable or disable the zoom wheel
                value = {$plugin.tx_browser_pi1.map.zoomWheel}
              }
                // Map modules
              oxMapConfigModules = COA
              oxMapConfigModules {
                  // Debug mode off
                10 = TEXT
                10 {
                  if {
                    isFalse = {$plugin.tx_browser_pi1.map.debugging.feAlert}
                  }
                    // [ARRAY] Possible modules: Error, OSM, OSM.CDmap, OSM.Filter
                  value = {$plugin.tx_browser_pi1.map.openlayers.modules.default}
                }
                  // Debug mode on
                20 = TEXT
                20 {
                  if {
                    isTrue = {$plugin.tx_browser_pi1.map.debugging.feAlert}
                  }
                    // [ARRAY] Possible modules: Error, OSM, OSM.CDmap, OSM.Filter
                  value = {$plugin.tx_browser_pi1.map.openlayers.modules.debugging}
                }
              }
                // Map zoom level
              oxMapConfigNumZoomLevels = TEXT
              oxMapConfigNumZoomLevels {
                  // [INTEGER] Value from 0 to 18. Example: 4
                value = {$plugin.tx_browser_pi1.map.zoomLevel.levels}
              }
                // Map start level
              oxMapConfigStartLevel = TEXT
              oxMapConfigStartLevel {
                  // [INTEGER] Value from 0 to 18. Example: 4
                value = {$plugin.tx_browser_pi1.map.zoomLevel.start}
              }
                // Map type: ROADMAP || Mapnik
              oxMapConfigType = COA
              oxMapConfigType {
                  // ROADMAP, if GoogleMaps
                10 = TEXT
                10 {
                  if {
                    value   = {$plugin.tx_browser_pi1.map.controlling.provider}
                    equals  = GoogleMaps
                  }
                  value = 'ROADMAP'
                }
                  // Mapnik, if Open Street Map
                20 = TEXT
                20 {
                  if {
                    value   = {$plugin.tx_browser_pi1.map.controlling.provider}
                    equals  = Open Street Map
                  }
                  value = 'Mapnik'
                }
              }
                // Map wms: GMap || OSM
              oxMapConfigWms = COA
              oxMapConfigWms {
                  // GMS, if GoogleMaps
                10 = TEXT
                10 {
                  if {
                    value   = {$plugin.tx_browser_pi1.map.controlling.provider}
                    equals  = GoogleMaps
                  }
                  value = 'GMap'
                }
                  // OSM, if Open Street Map
                20 = TEXT
                20 {
                  if {
                    value   = {$plugin.tx_browser_pi1.map.controlling.provider}
                    equals  = Open Street Map
                  }
                  value = 'OSM'
                }
              }
                // Map wms: OSM
              oxMapConfigWms >
              oxMapConfigWms = TEXT
              oxMapConfigWms {
                value = 'OSM'
              }
            }
          }
            // categories, dynamic
          html =
          html {
              // filter_form
            categories =
            categories {
                // Category filter
              filter_form = TEXT
              filter_form {
                value (
                  <form id="{$plugin.tx_browser_pi1.map.html.form.id}">
                    ###INPUTS###
                  </form>
  )
              }
            }
              // map_css, map_html, map_jss
            dynamic =
            dynamic {
                // CSS properties
              map_css = TEXT
              map_css {
                value (
                  <style type="text/css">
                    .tx-browser-pi1 a.oxMap-markerLink {
                      cursor:pointer;
                    }
                    .tx-browser-pi1 .mapview {
                      /* 140715, dwildt. margin-top:1em; */
                    }
                    .tx-browser-pi1 .mapview-content {
                      /* 140712, dwildt. padding: 1em 0;*/
                    }
                    .tx-browser-pi1 .mapview-content form {
                      padding-bottom:1em;
                      text-align:center;
                    }
                    .tx-browser-pi1 .mapview-content form label {
                      display:inline-flex;
                      /* #i0083, 140923, dwildt, 2+ */
                      line-height:1em;
                      padding-right:1em;
                    }
                    /* #i0083, 140923, dwildt, 3+ */
                    .tx-browser-pi1 .mapview-content form label img {
                      margin:0 0.3em;
                    }
                    /* #i0083, 140923, dwildt, + */
                    .tx-browser-pi1 .mapview-content form label input[type="file"],
                    .tx-browser-pi1 .mapview-content form label input[type="checkbox"],
                    .tx-browser-pi1 .mapview-content form label input[type="radio"],
                    .tx-browser-pi1 .mapview-content form label select {
                        margin-bottom: 0px;
                    }
                    .tx-browser-pi1 .olControlAttribution {
                      background-color:white;
                      bottom:0px;
                      right:0px;
                    }
                    .tx-browser-pi1 .olMap {
                      /* 140712, dwildt. margin: auto !important;*/
                      margin: 0 0 1em !important;
                    }
                    .tx-browser-pi1 .oxMapArea {
                      height:###CSS_MAPHEIGHT###;
                      width:###CSS_MAPWIDTH###;
                    }
                    .tx-browser-pi1 .oxTextLayer {
                      background:#fff;
                      font-size:.85em;
                      width:200px;
                      overflow:hidden;
                      position:absolute;
                      padding:3px;
                      box-shadow:3px 3px 10px #3d3d3d;
                      z-index:99;
                    }
                  </style>
)
              }
                // HTML div tag for the map
              map_html = TEXT
              map_html {
                value = <div id="{$plugin.tx_browser_pi1.map.html.id}" class="oxMapArea"></div>
              }
                // Javascript for the map. 20: Provider and API, 30: Custom, 40: oxRenderMap
              map_jss = COA
              map_jss {
                  // 10: Error prompt, if provider isn't proper. 20: GoogleMaps API || OpenStreetMap API
                20 = COA
                20 {
                    // Error prompt, if provider isn't in list of providers
                  10 = TEXT
                  10 {
                    if {
                      value     = GoogleMaps,Open Street Map
                      isInList  = {$plugin.tx_browser_pi1.map.controlling.provider}
                      negate  = 1
                    }
                    value (
                      <div style="background:white;border:1em solid red;color:red;padding:1em;text-align:center;">
                        <h1>TypoScript Error</h1>
                        <p>
                          '{$plugin.tx_browser_pi1.map.controlling.provider}' isn't any element of the list: GoogleMaps,Open Street Map
                        </p>
                        <p>
                          Browser - TYPO3 without PHP
                        </p>
                      </div>
)
                    lang.de (
                      <div style="background:white;border:1em solid red;color:red;padding:1em;text-align:center;">
                        <h1>TypoScript Fehler</h1>
                        <p>
                          '{$plugin.tx_browser_pi1.map.controlling.provider}' ist kein Element der Liste: GoogleMaps,Open Street Map
                        </p>
                        <p>
                          Browser - TYPO3 ohne PHP
                        </p>
                      </div>
)
                  }
                    // GoogleMaps Api, if provider is an element of the list of providers
                  20 = TEXT
                  20 {
                    if {
                      value   = {$plugin.tx_browser_pi1.map.controlling.provider}
                      equals  = GoogleMaps
                    }
                    value (
                      <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script>
)
                  }
                }
                  // Custom configuration
                30 = COA
                30 {
                    // Begin: Don't edit it!
                  10 = TEXT
                  10 {
                    value (
                      <script>
                      /* <![CDATA[ */
                          // Be aware of the trailing slash!
                        var rawdata = '###RAWDATA###';
                        var routes  = '###ROUTES###';
                        configuration = {

)
                  }
                    // Variables: Adapt it to your needs
                  20 = TEXT
                  20 {
                    value (
                            language        : '###OXMAPCONFIGLANGUAGE###'         //  default | en | de
                          , wms             : '###OXMAPCONFIGWMS###'              //  OSM
                          , type            : '###OXMAPCONFIGTYPE###'             //  Mapnik | ROADMAP
                          , modules         : '###OXMAPCONFIGMODULES###'          //  Error, OSM, OSM.CDmap, OSM.Filter
                          , imagePath       : '###OPENLAYERSIMGPATH###'
                          , cssPath         : '###OPENLAYERSCSS###'
                          , mapId           : '{$plugin.tx_browser_pi1.map.html.id}'
                          , mapControls     : '###OXMAPCONFIGMAPCONTROLS###'
                          , zoomWheelEnable : '###OXMAPCONFIGZOOMWHEELENABLE###'
                          , filter          : {
                                                wrapper : '{$plugin.tx_browser_pi1.map.html.form.id}'
                                              }
                          , center          : '###OXMAPCONFIGCENTER###'
                          , startLevel      : '###OXMAPCONFIGSTARTLEVEL###'
                          , numZoomLevels   : '###OXMAPCONFIGNUMZOOMLEVELS###'
                          , mapMarkerEvent  : '###OXMAPCONFIGMAPMARKEREVENT###'   // click | hover | on (for drawing one point only)
                          , route           : {
                                                  data : routes
                                                , defaultStyle : {
                                                                      strokeWidth     : 1
                                                                    , strokeColor     : '#000'
                                                                    //, strokeDashstyle : 'solid'     // Default is “solid”.  [dot | dash | dashdot | longdash | longdashdot | solid]
                                                                  }
                                              }
)
                  }
                    // End: Don't edit it!
                  30 = TEXT
                  30 {
                    value (

                        };
                      /* ]]> */
                      </script>
)
                  }
                }
                  // oxRenderMap: Touchscreen disabled || Touchscreen enabled
                40 = COA
                40 {
                    // oxRenderMap: Touchscreen enabled: Compressed || Uncompressed
                  10 = COA
                  10 {
                    if {
                      isTrue = {$plugin.tx_browser_pi1.map.mobileTouchscreen}
                    }
                      // javascript is compressed (default mode)
                    10 = TEXT
                    10 {
                      if {
                        isFalse = {$plugin.tx_browser_pi1.map.debugging.uncompressed}
                      }
                      value (

                        <!--<script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenLayers_2.12/ol-te7.js"></script>-->
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenLayers_2.13/OpenLayers.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenStreetMap/OpenStreetMap_1.3.10.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Utils.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Error.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.CDmap.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Filter.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Marker.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.mobile.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Route.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Tooltip.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.js"></script>
                        <script>new oxMapRender(configuration)</script>

)
                    }
                      // javascript is uncompressed (debugging mode)
                    20 = TEXT
                    20 {
                      if {
                        isTrue = {$plugin.tx_browser_pi1.map.debugging.uncompressed}
                      }
                      value (

                        <!--<script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenLayers_2.12/ol-te7.js"></script>-->
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenLayers_2.13/OpenLayers.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenStreetMap/OpenStreetMap_1.3.10.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Utils.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Error.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.CDmap.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Filter.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Marker.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Route.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Tooltip.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.mobile.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.js"></script>
                        <script>new oxMapRender(configuration)</script>

)
                    }
                  }
                    // oxRenderMap: Touchscreen disabled: Compressed || Uncompressed
                  20 = COA
                  20 {
                    if {
                      isFalse = {$plugin.tx_browser_pi1.map.mobileTouchscreen}
                    }
                      // javascript is compressed (default mode)
                    10 = TEXT
                    10 {
                      if {
                        isFalse = {$plugin.tx_browser_pi1.map.debugging.uncompressed}
                      }
                      value (

                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/oxMap.Render_1.3.07.js"></script>
                        <script>new oxMapRender(configuration)</script>

)
                    }
                      // javascript is uncompressed (debugging mode)
                    20 = TEXT
                    20 {
                      if {
                        isTrue = {$plugin.tx_browser_pi1.map.debugging.uncompressed}
                      }
                      value (

                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenLayers_2.13/OpenLayers.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/lib/OpenStreetMap/OpenStreetMap_1.3.10.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Utils.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.Error.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.CDmap.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Filter.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Marker.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Route.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.Tooltip.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.OSM.js"></script>
                        <script src="typo3conf/ext/browser/Resources/Public/JavaScript/Map/api/1.3.10/oxMap.js"></script>
                        <script>new oxMapRender(configuration)</script>

)
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}