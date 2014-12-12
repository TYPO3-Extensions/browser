plugin.tx_browser_pi1 {
  flexform {
    viewList {
        // [BOOLEAN] 1 (default): Display the listview. 0: Don't display the listview.
      display_listview = 1
        // [STRING] independent (default) || controlled: Calculate total hits. Can be overriden while runtime by the flexform value.
      total_hits = independent
        // [Boolean] True: hits will counted (default). False: hits won't counted (best performance)
      count_hits = true
        // [BOOLEAN] false (default) || true. Can be overriden while runtime by the flexform value.
      csvexport = false
      csvexport {
        devider {
            // [STRING] field devider. Usually a comma: ,
          stdWrap = TEXT
          stdWrap {
            value = ,
          }
        }
        enclosure {
            // [STRING] field enclosure. Usually a double quote: "
          stdWrap = TEXT
          stdWrap {
            value = "
          }
        }
        strip_tag {
            // [BOOLEAN] true (recommended) || false. Remove HTML tags
          stdWrap = TEXT
          stdWrap {
            value = true
          }
        }
          // [STRING] Path to the HTML template file
        template {
            // default file
          file    = EXT:browser/Resources/Private/Templates/HTML/csv_export.tmpl
          marker  = ###TEMPLATE_CSV###
        }
      }
        // [BOOLEAN] false (default) || true. Can be overriden while runtime by the flexform value.
      rotateviews = false
    }
  }
}