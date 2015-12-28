plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // typolinks
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        typolinks {
        }
          // 0
        typolinks =
        typolinks {
            // default (single view), page, url
          0 =
          0 {
              // typolink for a record (single view)
            default =
            default {
                // url, target, class, title
              parameter {
                cObject = COA
                cObject {
                    // url
                  10 = COA
                  10 {
                    10 = TEXT
                    10 {
                      if {
                        isTrue = {$plugin.tx_browser_pi1.templates.listview.url.0.singlePid}
                      }
                      value = {$plugin.tx_browser_pi1.templates.listview.url.0.singlePid}
                    }
                    20 = TEXT
                    20 {
                      if {
                        isFalse = {$plugin.tx_browser_pi1.templates.listview.url.0.singlePid}
                      }
                      data = page:uid
                    }
                  }
                    // target
                  20 = TEXT
                  20 {
                    value       = -
                    noTrimWrap  = | "|"|
                  }
                    // class
                  30 = TEXT
                  30 {
                    value       = {$plugin.tx_browser_pi1.templates.listview.url.css.record}
                    noTrimWrap  = | "|"|
                  }
                    // title
                  40 = TEXT
                  40 {
                    field = {$plugin.tx_browser_pi1.templates.listview.header.0.title}
                    stdWrap {
                      stripHtml         = 1
                      htmlSpecialChars  = 1
                      crop              = {$plugin.tx_browser_pi1.templates.listview.header.0.title.crop}
                    }
                    noTrimWrap  = | "|"|
                  }
                }
              }
              additionalParams {
                wrap  = &tx_browser_pi1[{$plugin.tx_browser_pi1.templates.listview.url.0.showUid}]=|
                field = {$plugin.tx_browser_pi1.templates.listview.url.0.record}
              }
              forceAbsoluteUrl = {$plugin.tx_browser_pi1.templates.listview.url.0.forceAbsoluteUrl}
              forceAbsoluteUrl {
                scheme = {$plugin.tx_browser_pi1.templates.listview.url.0.forceAbsoluteUrlScheme}
              }
              useCacheHash = 1
              returnLast = {$plugin.tx_browser_pi1.templates.listview.url.0.returnLast}
            }
              // link to an internal page
            page < .default
            page {
              parameter.cObject {
                10 >
                10 = TEXT
                10 {
                  field = {$plugin.tx_browser_pi1.templates.listview.url.0.page}
                  stdWrap {
                    stripHtml = 1
                    htmlSpecialChars = 1
                  }
                }
                30 {
                  value = {$plugin.tx_browser_pi1.templates.listview.url.css.page}
                }
              }
              additionalParams >
            }
              // link to an external website
            url < .page
            url {
              parameter.cObject {
                10 {
                  field = {$plugin.tx_browser_pi1.templates.listview.url.0.url}
                }
                20 {
                  value       = _blank
                  noTrimWrap  = | ||
                }
                30 {
                  value = {$plugin.tx_browser_pi1.templates.listview.url.css.url}
                }
              }
            }
              // tt_news type: Link internal Page
            1 < .page
              // tt_news type: Link external Url
            2 < .url
          }
        }
      }
    }
  }
}