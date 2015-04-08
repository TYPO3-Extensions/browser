plugin.tx_browser_pi1 {
  views {
    list {
      1000 = tt_news: Sample
      1000 {
        // [String] Name of the view. It will displayed in the plugin/flexform
        name    = tt_news: Sample
        // [String] Alias for showUid.
        showUid = {$plugin.tx_browser_pi1.navigation.showUid}
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/sql.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/filter.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/tableFields.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/htmlSnippets.ts">