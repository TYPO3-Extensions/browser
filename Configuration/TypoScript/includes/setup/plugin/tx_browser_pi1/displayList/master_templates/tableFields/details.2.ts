plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // details
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        details {
        }
          // 0
        details =
        details {
            // link to the single view (record), internal page, external URL or no link (empty value)
          2 = CASE
          2 {
            key {
              field = {$plugin.tx_browser_pi1.templates.listview.url.2.key}
            }
              // link to the single view (record)
            default = TEXT
            default {
              value = details
              lang {
                de = Mehr
                en = details
              }
              stdWrap {
                stripHtml         = 1
                htmlSpecialChars  = 0
                crop              = 30|...|1
                noTrimWrap        = ||&nbsp;&raquo;|
              }
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.default
              required = 1
            }
              // without any link (record is available only in list views)
            notype = TEXT
            notype {
              value =
            }
              // link to an internal page
            page < .default
            page {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.page
            }
              // link to an external website
            url < .page
            url {
              value = homepage
              lang {
                de = Homepage
                en = homepage
              }
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.2.url
            }
          }
        }
      }
    }
  }
}