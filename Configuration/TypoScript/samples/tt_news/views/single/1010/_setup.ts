plugin.tx_browser_pi1 {
  views {
    single {
      1010 = tt_news: RSS
      1010 {
        // [Mixed] Internal comment
        comment = This view should not be used. select value is a dummy!
        // [String] Select clause (don't confuse it with the SQL select)
        select  = tt_news.title
      }
    }
  }
}