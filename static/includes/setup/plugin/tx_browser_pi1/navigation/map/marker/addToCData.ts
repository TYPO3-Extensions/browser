plugin.tx_browser_pi1 {
  navigation {
      // debugging, enabled, provider, template, configuration, marker
    map =
    map {
        // addToCData, snippets, variables
      marker =
      marker {
          // system: Each item will be a part of the current record while runtime. You can create your own item in the system section
        addToCData =
        addToCData {
            // mapLinkToSingle
          system =
          system {
              // marker, path
            mapLinkToSingle = COA
            mapLinkToSingle {
                // marker: typolink parameter
              10 = COA
              10 {
                  // #i0013: if type != route
                if =
                if {
                  value = route
                  equals {
                    field = type
                  }
                  negate = 1
                }
                  // URL: page id, page type, parameters
                10 = COA
                10 {
                    // Page uid
                  10 = TEXT
                  10 {
                    data = page:uid
                  }
                    // Page type
                  20 = TEXT
                  20 {
                    value = 0
                    wrap  = ,|
                  }
                    // Parameter
                  30 = COA
                  30 {
                      // ,&tx_browser_pi1[showuid]=
                    10 = TEXT
                    10 {
                      if {
                        isTrue {
                          field = {$plugin.tx_browser_pi1.map.marker.field.linktoSingle}
                        }
                      }
                      field = {$plugin.tx_browser_pi1.map.marker.field.linktoSingle}
                      wrap  = ,&tx_browser_pi1[{$plugin.tx_browser_pi1.map.aliases.showUid.marker}]=|
                    }
                  }
                }
                  // target
                20 = TEXT
                20 {
                    // [STRING] - (default). Examples: -, _self, _blank, _top
                  value = -
                  noTrimWrap = | | |
                }
                  // class
                30 = TEXT
                30 {
                    // [STRING] - (default)
                  value = -
                  noTrimWrap = | | |
                }
                  // title
                40 = TEXT
                40 {
                  if {
                    isTrue {
                      field =
                    }
                  }
                    // [STRING]
                  field =
                  wrap  = "|"
                }
              }
                // path: typolink parameter
              20 = COA
              20 {
                  // #i0013: if type == route
                if =
                if {
                  value = route
                  equals {
                    field = type
                  }
                }
                  // URL: page id, page type, parameters
                10 = COA
                10 {
                    // Page uid
                  10 = TEXT
                  10 {
                    data = page:uid
                  }
                    // Page type
                  20 = TEXT
                  20 {
                    value = 0
                    wrap  = ,|
                  }
                    // Parameter
                  30 = COA
                  30 {
                      // ,&tx_browser_pi1[showuid]=
                    10 = TEXT
                    10 {
                      if {
                        isTrue {
                          field = {$plugin.tx_browser_pi1.map.marker.field.linktoSingle}
                          //field = {$plugin.tx_browser_pi1.map.path.mapper.tables.local.path}.uid
                        }
                      }
                      field = {$plugin.tx_browser_pi1.map.marker.field.linktoSingle}
                      //field = {$plugin.tx_browser_pi1.map.path.mapper.tables.local.path}.uid
                      wrap  = ,&tx_browser_pi1[{$plugin.tx_browser_pi1.map.aliases.showUid.path}]=|
                    }
                  }
                }
                  // target
                20 = TEXT
                20 {
                    // [STRING] - (default). Examples: -, _self, _blank, _top
                  value = -
                  noTrimWrap = | | |
                }
                  // class
                30 = TEXT
                30 {
                    // [STRING] - (default)
                  value = -
                  noTrimWrap = | | |
                }
                  // title
                40 = TEXT
                40 {
                  if {
                    isTrue {
                      field =
                    }
                  }
                    // [STRING]
                  field =
                  wrap  = "|"
                }
              }
            }
          }
        }
      }
    }
  }
}