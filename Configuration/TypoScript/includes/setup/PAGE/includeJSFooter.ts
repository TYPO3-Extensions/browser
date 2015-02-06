page.includeJSFooter {
  browserMapToggle = EXT:browser/Resources/Private/Templates/HTML/Map/map_toggle.js
  browserMapToggle {
    if {
      isTrue = {$plugin.tx_browser_pi1.map.html.jss.toggle}
    }
  }
}