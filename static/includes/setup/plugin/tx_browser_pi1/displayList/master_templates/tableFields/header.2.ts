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
          // 2
        header =
        header {
              // key, default (single view), page, url
          2 = CASE
          2 {
            key {
              field = {$plugin.tx_browser_pi1.templates.listview.url.2.key}
            }
              // single view. 10: teaser_title, 20: title
            default = TEXT
            default {
              field = {$plugin.tx_browser_pi1.templates.listview.header.2.list} // {$plugin.tx_browser_pi1.templates.listview.header.2.single}
              wrap  = <h2>|</h2>
              stdWrap {
                stripHtml         = 1
                htmlSpecialChars  = 1
                crop              = {$plugin.tx_browser_pi1.templates.listview.header.2.crop} | ... | 1
              }
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.default
            }
              // without any link (record is available only in list views)
            notype < .default
            notype {
              typolink >
            }
              // link to an internal page. 10: teaser_title, 20: title
            page < .default
            page {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.page
            }
              // link to an external website. 10: teaser_title, 20: title
            url < .page
            url {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.url
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