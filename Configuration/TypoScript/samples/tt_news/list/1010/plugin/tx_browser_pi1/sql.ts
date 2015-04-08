plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        // [String] Select clause (don't confuse it with the SQL select)
        select (
          tt_news.title,
          tt_news.short,
          tt_news.bodytext,
          tt_news.uid
        )
        // [String] Order By clause (don't confuse it with the SQL Order By)
        orderBy (
          tt_news.datetime DESC
        )
      }
    }
  }
}