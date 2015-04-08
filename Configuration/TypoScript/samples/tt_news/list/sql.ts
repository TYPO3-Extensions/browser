plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        // [String] Select clause (don't confuse it with the SQL select)
        select (
          tt_news.title,
          tt_news_cat.title,
          tt_news.datetime,
          tt_news.ext_url,
          tt_news.image,
          tt_news.imagealttext,
          tt_news.imagetitletext,
          tt_news.page,
          tt_news.short,
          tt_news.type
)
        // Order of the records
        orderBy (
          tt_news.datetime DESC, tt_news_cat.title, tt_news.title
)
        // Don't link any field automatically with the link to the single view
        csvLinkToSingleView = dummy
        functions {
          clean_up {
            // Don't display fields as columns in the table
            csvTableFields (
              distance,
              tt_news.ext_url,
              tt_news.page,
              tt_news.type,
              tt_news_cat.title
)
          }
        }
      }
    }
  }
}