plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        tt_news {
          tstamp = TEXT
          tstamp {
            field     = tt_content.tstamp
            strftime  = %d.%m.%y
          }
        }
      }
    }
  }
}