plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // header, text
      tableFields =
      tableFields {
          // key, default (single view), page, url
        text = CASE
        text {
          key {
            field = {$plugin.tx_browser_pi1.templates.listview.url.0.key}
          }
          default = COA
          default {
            wrap  = <p>|</p>
            10 = TEXT
            10 {
              field     = {$plugin.tx_browser_pi1.templates.listview.text.list} // {$plugin.tx_browser_pi1.templates.listview.text.single}
              stdWrap {
                stripHtml         = 1
                htmlSpecialChars  = 1
                crop              = {$plugin.tx_browser_pi1.templates.listview.text.crop} | ... | 1
              }
            }
            20 = TEXT
            20 {
              value = details &raquo;
              lang {
                de = Details &raquo;
                en = details &raquo;
              }
              noTrimWrap = | ||
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
            }
          }
            // without any link (record is available only in list views)
          notype < .default
          notype {
            20 >
          }
          page < .default
          page {
            20 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
            }
          }
          url < .page
          url {
            20 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.url
            }
          }
          calpage   < .page
          calurl    < .url
          news      < .default
          newspage  < .page
          newsurl   < .url
        }
      }
    }
  }
}