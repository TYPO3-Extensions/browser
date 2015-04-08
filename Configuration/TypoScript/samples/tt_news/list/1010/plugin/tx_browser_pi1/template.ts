plugin.tx_browser_pi1 {
  template {
    extensions {
      tt_news {
        rss {
            // [String] Name of the template. It will displayed in the flexform of the plugin.
          name      = tt_news: RSS
            // [String] Path to your RSS/XML template
          file      = EXT:browser/Resources/Public/Sample/tt_news/rss.tmpl
            // [String] Path to a icon. It will displayed in the flexform of the plugin.
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_01.gif
            // [csv] Comma seperated list with the numbers of the correspondening views
          csvViews  = 1010
        }
      }
    }
  }
}