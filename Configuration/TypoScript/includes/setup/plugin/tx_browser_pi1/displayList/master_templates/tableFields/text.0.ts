plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // header, text
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        text {
        }
          // 0
        text =
        text {
            // key, default (single view), page, url
          0 = CASE
          0 {
            key {
              field = {$plugin.tx_browser_pi1.templates.listview.url.0.key}
            }
              // single view
            default = COA
            default {
              wrap  = <{$plugin.tx_browser_pi1.templates.listview.text.0.tag} class="link-to-record">|</{$plugin.tx_browser_pi1.templates.listview.text.0.tag}>
                // Text
              10 = TEXT
              10 {
                field     = {$plugin.tx_browser_pi1.templates.listview.text.0.field}
                stdWrap {
                  stripHtml         = 1
                  htmlSpecialChars  = 0
                  crop              = {$plugin.tx_browser_pi1.templates.listview.text.0.crop}
                }
              }
                // Details link
              20 = TEXT
              20 {
                value = details
                lang {
                  de = Mehr
                  en = details
                }
                stdWrap {
                  stripHtml         = 1
                  htmlSpecialChars  = 1
                  crop              = 30|...|1
                  noTrimWrap        = ||&nbsp;&raquo;|
                }
                noTrimWrap = | ||
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
              }
            }
              // without any link (record is available only in list views)
            notype < .default
            notype {
              wrap  = <{$plugin.tx_browser_pi1.templates.listview.text.0.tag} class="no-link">|</{$plugin.tx_browser_pi1.templates.listview.text.0.tag}>
              20 >
            }
              // link to an internal page
            page < .default
            page {
              wrap  = <{$plugin.tx_browser_pi1.templates.listview.text.0.tag} class="link-to-page">|</{$plugin.tx_browser_pi1.templates.listview.text.0.tag}>
              20 {
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
              }
            }
              // link to an external website
            url < .page
            url {
              wrap  = <{$plugin.tx_browser_pi1.templates.listview.text.0.tag} class="link-to-url">|</{$plugin.tx_browser_pi1.templates.listview.text.0.tag}>
              20 {
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.url
                value = URL
                lang {
                  de = URL
                  en = URL
                }
              }
            }
              // DEPRECATED! Use page!
            calpage   < .page
              // DEPRECATED! Use url!
            calurl    < .url
              // DEPRECATED! Use default (record)!
            news      < .default
              // DEPRECATED! Use page!
            newspage  < .page
              // DEPRECATED! Use url!
            newsurl   < .url
          }
        }
      }
    }
  }
}