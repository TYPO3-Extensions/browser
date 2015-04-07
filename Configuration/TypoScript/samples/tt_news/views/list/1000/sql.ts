plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        select (
          tt_news.title,
          tt_news_cat.title,
          tt_news.image,
          tt_news.imagealttext,
          tt_news.imagetitletext,
          tt_news.datetime,
          tt_news.short
        )
        orderBy (
          tt_news.datetime DESC, tt_news_cat.title, tt_news.title
        )
        functions {
          clean_up {
            csvTableFields = distance
          }
        }
      }
    }
  }
}