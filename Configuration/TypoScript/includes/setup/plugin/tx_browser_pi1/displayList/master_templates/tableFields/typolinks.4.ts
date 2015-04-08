plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // typolinks_1
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        typolinks {
        }
          // 4
        typolinks =
        typolinks {
            // default (single view), page, url
          4 =
          4 {
              // typolink for a record (single view)
            default =
            default {
                // url, target, class, title
              parameter{
                cObject = COA
                cObject {
                    // url
                  10 = COA
                  10 {
                    10 = TEXT
                    10 {
                      if {
                        isTrue = {$plugin.tx_browser_pi1.templates.listview.url.4.singlePid}
                      }
                      value = {$plugin.tx_browser_pi1.templates.listview.url.4.singlePid}
                    }
                    20 = TEXT
                    20 {
                      if {
                        isFalse = {$plugin.tx_browser_pi1.templates.listview.url.4.singlePid}
                      }
                      data = page:uid
                    }
                  }
                    // target
                  20 = TEXT
                  20 {
                    value       = -
                    noTrimWrap  = | ||
                  }
                    // class
                  30 = TEXT
                  30 {
                    value       = linktosingle
                    noTrimWrap  = | "|"|
                  }
                    // title
                  40 = TEXT
                  40 {
                    field = {$plugin.tx_browser_pi1.templates.listview.header.4.field} // {$plugin.tx_browser_pi1.templates.listview.header.4.single}
                    stdWrap {
                      stripHtml = 1
                      htmlSpecialChars = 1
                    }
                    noTrimWrap  = | "|"|
                  }
                }
              }
              additionalParams {
                wrap  = &tx_browser_pi1[{$plugin.tx_browser_pi1.templates.listview.url.4.showUid}]=|
                field = {$plugin.tx_browser_pi1.templates.listview.url.4.record}
              }
              forceAbsoluteUrl = {$plugin.tx_browser_pi1.templates.listview.url.4.forceAbsoluteUrl}
              forceAbsoluteUrl {
                scheme = {$plugin.tx_browser_pi1.templates.listview.url.4.forceAbsoluteUrlScheme}
              }
              useCacheHash = 1
              returnLast = {$plugin.tx_browser_pi1.templates.listview.url.4.returnLast}
            }
              // link to an internal page
            page < .default
            page {
              parameter.cObject {
                10 >
                10 = TEXT
                10 {
                  field = {$plugin.tx_browser_pi1.templates.listview.url.4.page}
                }
                30 {
                  value = internal
                }
              }
              additionalParams >
            }
              // link to an external website
            url < .page
            url {
              parameter.cObject {
                10 {
                  field = {$plugin.tx_browser_pi1.templates.listview.url.4.url}
                }
                20 {
                  value       = _blank
                  noTrimWrap  = | ||
                }
                30 {
                  value = external
                }
              }
            }
          }
        }
      }
    }
  }
}