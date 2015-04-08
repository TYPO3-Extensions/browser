plugin.tx_browser_pi1 {
  views {
    list {
      1010 = tt_news: RSS
      1010 {
        // [String] Name of the view. It will displayed in the plugin/flexform
        name    = tt_news: RSS
        // [String] Alias for showUid. It is optional. If you don't need it, remove the whole line.
        showUid = newsUid
        // [String] Select clause (don't confuse it with the SQL select)
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1010/plugin/tx_browser_pi1/marker.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1010/plugin/tx_browser_pi1/sql.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1010/plugin/tx_browser_pi1/tableFields.ts">