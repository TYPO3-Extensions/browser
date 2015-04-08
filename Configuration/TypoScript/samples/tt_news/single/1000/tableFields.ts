plugin.tx_browser_pi1 {
  views {
    single {
      1000 {
        tt_news {
            // tt_news.datetime - tt_news.bodytext
          bodytext = COA
          bodytext {
              // tt_news.datetime
            10 = TEXT
            10 {
              field = tt_news.datetime
              strftime {
                cObject = TEXT
                cObject {
                  value   = %m/%d/%y
                  lang.de = %d.%m.%y
                }
              }
            }
              // devider
            20 = TEXT
            20 {
              value = -
              noTrimWrap = | | |
            }
              // tt_news.bodytext
            30 = TEXT
            30 {
              field = tt_news.bodytext
            }
            stdWrap {
              parseFunc < lib.parseFunc_RTE
            }
          }
          image < plugin.tx_browser_pi1.displaySingle.master_templates.tableFields.image.0
        }
      }
    }
  }
}
