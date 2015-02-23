plugin.tx_browser_pi1 {
  navigation {
      // 140703: empty statement: for proper comments only
    map {
    }
      // template
    map =
    map {
        // empty statement: for proper comments only
      rules {
      }
        // leafletIsEnabled
      rules =
      rules {
          // Empty statement needed for proper comments
        leafletIsEnabled {
        }
          // true, if leavelet and a map provider is enabled. Test it at Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
        leafletIsEnabled =
        leafletIsEnabled  {
          value = 11
          equals {
            stdWrap {
              cObject = COA
              cObject {
                  // true, if leavelet is the current modul
                10 = TEXT
                10 {
                  value = 1
                    // true, if leavelet is the current modul
                  if =
                  if {
                    value   = leaflet (default)
                    equals  = {$plugin.tx_browser_pi1.map.compatibility.mode}
                  }
                }
                  // true, if one of the OpenStreetMap or GoogleMaps layers is enabled at least
                20 = TEXT
                20 {
                  value = 1
                    // true, if one of the GoogleMaps layers hybrid, roadmap, stellite or terrain is enabled at least
                  if =
                  if {
                    isTrue {
                      stdWrap {
                        cObject = COA
                        cObject {
                            // true, if map OpenStreetMap roadmap is enabled
                          10 = TEXT
                          10 {
                            value = 1
                              // true, if map OpenStreetMap roadmap is enabled
                            if =
                            if {
                              isTrue  = {$plugin.tx_browser_pi1.map.provider.osm.roadmap}
                            }
                          }
                            // true, if map GoogleMap hybrid is enabled
                          20 = TEXT
                          20 {
                            value = 1
                              // true, if map GoogleMap hybrid is enabled
                            if =
                            if {
                              isTrue  = {$plugin.tx_browser_pi1.map.provider.google.hybrid}
                            }
                          }
                            // true, if map GoogleMap roadmap is enabled
                          30 = TEXT
                          30 {
                            value = 1
                              // true, if map GoogleMap roadmap is enabled
                            if =
                            if {
                              isTrue  = {$plugin.tx_browser_pi1.map.provider.google.roadmap}
                            }
                          }
                            // true, if map GoogleMap satellite is enabled
                          40 = TEXT
                          40 {
                            value = 1
                              // true, if map GoogleMap satellite is enabled
                            if =
                            if {
                              isTrue  = {$plugin.tx_browser_pi1.map.provider.google.satellite}
                            }
                          }
                            // true, if map GoogleMap terrain is enabled
                          50 = TEXT
                          50 {
                            value = 1
                              // true, if map GoogleMap terrain is enabled
                            if =
                            if {
                              isTrue  = {$plugin.tx_browser_pi1.map.provider.google.terrain}
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
          // Empty statement needed for proper comments
        leafletWiGoogle {
        }
          // true, if leavelet and provider GoogleMaps is enabled. Test it at Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
        leafletWiGoogle < plugin.tx_browser_pi1.navigation.map.rules.leafletIsEnabled
        leafletWiGoogle {
          equals {
            stdWrap {
              cObject {
                20 {
                  if {
                    isTrue {
                      stdWrap {
                        cObject = COA
                        cObject {
                          10 >
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
  }
}