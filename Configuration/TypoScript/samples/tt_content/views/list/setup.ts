plugin.tx_browser_pi1 {
  views {
    list {
      43746 = Browser: ready-to-use with tt_content
      43746 {
        name    = Browser: ready-to-use with tt_content
        showUid = {$plugin.tx_browser_pi1.navigation.showUid}
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/views/list/sql.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/views/list/tableFields.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/views/list/filter.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/samples/tt_content/views/list/htmlSnippets/setup.ts">