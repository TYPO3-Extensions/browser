plugin.tx_browser_pi1 {
  views {
    list {
        // [String] Name of the view. It will displayed in the plugin/flexform
      43746 = Browser: ready-to-use with tt_content
      43746 {
          // [String] Name of the view.
        name    = Browser: ready-to-use with tt_content
          // [String] Alias for showUid.
        showUid = {$plugin.tx_browser_pi1.navigation.showUid}
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/list/sql.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/list/tableFields.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/list/filter.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/list/htmlSnippets.ts">
