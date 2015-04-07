plugin.tx_browser_pi1 {
  template {
    extensions {
      tt_news {
        sample {
          // [String] Name of the template. It will displayed in the plugin/flexform
          name      = tt_news: Sample
          file      = EXT:browser/Resources/Public/Sample/tt_news/default.tmpl
          // [csv] Comma seperated list with the number of the correspondening views
          csvViews  = 1000
        }
        rss {
          // [String] Name of the template. It will displayed in the plugin/flexform
          name      = tt_news: RSS
          file      = EXT:browser/Resources/Public/Sample/tt_news/rss.tmpl
          // [csv] Comma seperated list with the number of the correspondening views
          csvViews  = 1010
        }
      }
    }
  }
}