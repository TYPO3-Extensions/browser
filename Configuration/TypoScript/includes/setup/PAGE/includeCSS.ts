page.includeCSS {
    // #65184, 150220, dwildt
    // Test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
  leafletLib = {$plugin.tx_browser_pi1.map.leafletfiles.css}
  leafletLib {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletIsEnabled
  }
  leafletMastercluster = {$plugin.tx_browser_pi1.map.leafletplugins.masterclusterCss}
  leafletMastercluster {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiMastercluster
  }
  leafletMasterclusterDefault = {$plugin.tx_browser_pi1.map.leafletplugins.masterclusterCssDefault}
  leafletMasterclusterDefault {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiMastercluster
  }
}
