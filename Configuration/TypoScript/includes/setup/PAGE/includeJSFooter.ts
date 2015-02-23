page.includeJSFooter {
  browserMapToggle = EXT:browser/Resources/Public/JavaScript/Map/oxMap/toggle/map_toggle.js
  browserMapToggle {
    if {
      isTrue = {$plugin.tx_browser_pi1.map.html.jss.toggle}
    }
  }
    // #65184, 150220, dwildt
    // Test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
  goggleMapsApiV3 = {$plugin.tx_browser_pi1.map.leafletplugins.googleApi}
  goggleMapsApiV3 {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiGoogle
    external = 1
  }
    // #65184, 150220, dwildt
    // Test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
  leafletLib = {$plugin.tx_browser_pi1.map.leafletfiles.js}
  leafletLib {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletIsEnabled
  }
    // #65184, 150220, dwildt
    // Test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
  googletilelayers = {$plugin.tx_browser_pi1.map.leafletplugins.layertilegoogle}
  googletilelayers {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiGoogle
  }
    // #65184, 150220, dwildt
    // Test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts
  leafletmastercluster = {$plugin.tx_browser_pi1.map.leafletplugins.mastercluster}
  leafletmastercluster {
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiMastercluster
  }
}