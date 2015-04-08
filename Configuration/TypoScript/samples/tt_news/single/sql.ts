plugin.tx_browser_pi1 {
  views {
    single {
      1000 = tt_news: Sample
      1000 {
        // [String] Select clause (don't confuse it with the SQL select)
        select (
          tt_news.title,
          tt_news.short,
          tt_news.datetime,
          tt_news.bodytext,
          tt_news.image,
          tt_news.imagecaption,
          tt_news.imagealttext,
          tt_news.imagetitletext
)
        functions {
          clean_up {
            csvTableFields (
              tt_news.datetime,
              tt_news.imagecaption,
              tt_news.imagealttext,
              tt_news.imagetitletext
)
          }
        }
      }
    }
  }
}