plugin.tx_browser_pi1 {
  navigation {
      // 140703: empty statement: for proper comments only
    map {
    }
      // template
    map =
    map {
        // empty statement: for proper comments only
      template {
      }
        // file, leaflet
      template =
      template {
          // [STRING] Path to the map HTML template
        file = {$plugin.tx_browser_pi1.map.html.path}
          // empty statement: for proper comments only
        leaflet {
        }
          // container
        leaflet =
        leaflet {
            // empty statement: for proper comments only
          container {
          }
            // list, single
          container = TEXT
          container {
              // empty statement: for proper comments only
            list {
            }
            list = COA
            list {
              10 = TEXT
              10 {
                value = <div id="{$plugin.tx_browser_pi1.map.html.id}" style="height:{$plugin.tx_browser_pi1.map.design.height}px"></div>
              }
              20 = TEXT
              20 {
                if {
                  isTrue = {$plugin.tx_browser_pi1.map.html.jss.toggle}
                }
                value (
                  <div class="maptoggle">
                    <button class="###CSSBTN######CSSBTNTINY######CSSBTNBLOCK######CSSBTNPRIMARY###" role="button">
                      Please include the needed JavaScript code. See Constant Editor > Category [BROWSERMAPS - HTML] > JavaScript
                    </button>
                  </div><!-- /maptoggle -->
)
              }
            }
              // empty statement: for proper comments only
            single {
            }
            single = TEXT
            single {
              value = <div id="{$plugin.tx_browser_pi1.map.html.id}" style="height:{$plugin.tx_browser_pi1.map.design.height.single}px"></div>
            }
          }
          htmlId = {$plugin.tx_browser_pi1.map.html.id}
        }
      }
    }
  }
}