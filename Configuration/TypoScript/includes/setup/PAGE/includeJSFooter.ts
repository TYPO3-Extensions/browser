page.includeJSFooter {
  browserMapToggle = EXT:browser/Resources/Public/JavaScript/Map/toggle/map_toggle.js
  browserMapToggle {
    if {
      isTrue = {$plugin.tx_browser_pi1.map.html.jss.toggle}
    }
  }
}