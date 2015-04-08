plugin.tx_browser_pi1 {
  views {
    list {
      1000 = tt_news: Sample
      1000 {
        // [String] Name of the view. It will displayed in the plugin/flexform
        name    = tt_news: Sample
        // [String] Alias for showUid. It is optional. If you don't need it, remove the whole line.
        showUid = newsUid
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1000/sql.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1000/filter.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1000/tableFields.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_news/list/1000/htmlSnippets.ts">