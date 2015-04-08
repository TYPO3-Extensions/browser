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
              htmlSpecialChars = 1
            }
          }
          title = TEXT
          title {
            field = tt_news.title
            crop  = 60 | ... | 1
            stdWrap {
              stripHtml = 1
              htmlSpecialChars = 1
            }
          }
          uid = COA
          uid {
            10 = TEXT
            10 {
              typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
              stdWrap {
                stripHtml = 1
                htmlSpecialChars = 1
              }
            }
          }
        }
      }
    }
  }
}