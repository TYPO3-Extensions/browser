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
        header {
        }
          // 6
        header =
        header {
              // key, default (single view), page, url
          6 = CASE
          6 {
            key {
              field = {$plugin.tx_browser_pi1.templates.listview.url.6.key}
            }
              // single view
            default = TEXT
            default {
              field = {$plugin.tx_browser_pi1.templates.listview.header.6.field}
              wrap  = <{$plugin.tx_browser_pi1.templates.listview.header.6.tag}>|</{$plugin.tx_browser_pi1.templates.listview.header.6.tag}>
              stdWrap {
                stripHtml         = 1
                htmlSpecialChars  = 0
                crop              = {$plugin.tx_browser_pi1.templates.listview.header.6.crop}
              }
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.6.default
              required = 1
            }
              // without any link (record is available only in list views)
            notype < .default
            notype {
              typolink >
            }
              // link to an internal page
            page < .default
            page {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.6.page
            }
              // link to an external website
            url < .page
            url {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.6.url
            }
              // DEPRECATED! Use page!
            calpage < .page
              // DEPRECATED! Use url!
            calurl  < .url
              // DEPRECATED! Use default (record)!
            news < .default
              // DEPRECATED! Use page!
            newspage < .page
              // DEPRECATED! Use url!
            newsurl  < .url
          }
        }
      }
    }
  }
}