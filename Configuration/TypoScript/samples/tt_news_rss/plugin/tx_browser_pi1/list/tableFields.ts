plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        tt_news {
          short = COA
          short {
            10 = TEXT
            10 {
                // #28856, 110809, dwildt
              value = ###TT_NEWS.SHORT###
            }
            20 = TEXT
            20 {
              if.isFalse  = ###TT_NEWS.SHORT###
              value       = ###TT_NEWS.BODYTEXT###
              crop        = 300 | ... | 1
              stripHtml   = 1
            }
          }
          title = COA
          title {
            10 = TEXT
            10 {
              value = ###TT_NEWS.TITLE###
              crop  = 60 | ... | 1
            }
          }
          uid = COA
          uid {
            10 = TEXT
            10 {
              value = {$plugin.tx_browser_pi1.extensions.tt_news.host}/
            }
            20 = TEXT
            20 {
              typolink {
                parameter = {$plugin.tx_browser_pi1.extensions.tt_news.pages.single_view}
                parameter {
                  insertData = 1
                }
                //additionalParams  = &tx_browser_pi1[newsUid]=###TT_NEWS.UID###&###CHASH###
                additionalParams  = &tx_ttnews[tt_news]=###TT_NEWS.UID###&###CHASH###
                returnLast = url
              }
            }
          }
        }
      }
    }
  }
}