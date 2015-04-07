plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        tt_news {
          datetime = TEXT
          datetime {
            field     = tt_news.datetime
            strftime  = %d.%m.%y
          }
        }
      }
    }
  }
}