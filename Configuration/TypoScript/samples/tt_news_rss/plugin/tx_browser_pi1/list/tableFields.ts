plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        tt_news {
          short = COA
          short {
            10 = TEXT
            10 {
              field     = tt_news.short
              required  = 1
            }
            20 = TEXT
            20 {
              if {
                isFalse {
                  field = tt_news.short
                }
              }
              field     = tt_news.bodytext
              crop        = 300 | ... | 1
            }
            stdWrap {
              stripHtml = 1
            }
          }
          title = TEXT
          title {
            field = tt_news.title
            crop  = 60 | ... | 1
            stdWrap {
              stripHtml = 1
            }
          }
          uid = CASE
          uid {
            key {
              field = {$plugin.tx_browser_pi1.templates.listview.url.0.key}
            }
              // tt_news type: News
            0 = TEXT
            0 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
            }
              // tt_news type: Link internal Page
            1 = TEXT
            1 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
            }
              // tt_news type: Link external URL
            2 = TEXT
            2 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.url
            }
          }
        }
      }
    }
  }
}