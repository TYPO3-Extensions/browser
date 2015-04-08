plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        tt_news {
          datetime = TEXT
          datetime {
            field = tt_news.datetime
            strftime {
              cObject = TEXT
              cObject {
                value   = %m/%d/%y
                lang.de = %d.%m.%y
              }
            }
          }
          image < plugin.tx_browser_pi1.displayList.master_templates.tableFields.image.0
          image {
              // tt_news type: News
            0 =
            0 < plugin.tx_browser_pi1.views.list.1000.tt_news.image.default
              // tt_news type: Link internal Page
            1 =
            1 < plugin.tx_browser_pi1.views.list.1000.tt_news.image.page
              // tt_news type: Link external Url
            2 =
            2 < plugin.tx_browser_pi1.views.list.1000.tt_news.image.url
          }
          title < plugin.tx_browser_pi1.displayList.master_templates.tableFields.header.0
          title {
              // tt_news type: News
            0 =
            0 < plugin.tx_browser_pi1.views.list.1000.tt_news.title.default
              // tt_news type: Link internal Page
            1 =
            1 < plugin.tx_browser_pi1.views.list.1000.tt_news.title.page
              // tt_news type: Link external Url
            2 =
            2 < plugin.tx_browser_pi1.views.list.1000.tt_news.title.url
          }
        }
      }
    }
  }
}
