plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      map {
      }
        // descriptionWiLinkToSingle
      map =
      map {
          // Content for the pop up with description. It contains a description and a link to the single view.
        descriptionWiLinkToSingle = COA
        descriptionWiLinkToSingle {
            // Description
          10 = TEXT
          10 {
            required    = 1
            field       = {$plugin.tx_browser_pi1.map.marker.field.description}
            noTrimWrap  = || <br />|
          }
            // Categories
          20 = TEXT
          20 {
            required    = 1
            field       = {$plugin.tx_browser_pi1.map.marker.field.category}
            noTrimWrap  = |(|) <br />|
          }
            // Link to the single view (in 'click'-mode only)
          30 = TEXT
          30 {
            if {
              value   = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
              equals  = 'click'
            }
            value = In detail &raquo;
            lang {
              de = Details &raquo;
              en = In detail &raquo;
            }
            typolink {
              parameter.data = field:mapLinkToSingle
                // 130117
              useCacheHash = 1
            }
          }
            // Link to the single view (in 'click'-mode only)
          30 >
          30 = COA
          30 {
            if {
              isFalse = {$plugin.tx_browser_pi1.map.mobileTouchscreen}
            }
            10 = TEXT
            10 {
              if {
                value   = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
                equals  = 'click'
              }
              value = In detail &raquo;
              lang {
                de = Details &raquo;
                en = In detail &raquo;
              }
              typolink {
                parameter.data = field:mapLinkToSingle
                  // 130117
                useCacheHash = 1
              }
            }
          }
        }
      }
    }
  }
}