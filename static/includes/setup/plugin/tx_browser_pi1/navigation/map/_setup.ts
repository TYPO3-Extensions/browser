<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/static/includes/setup/plugin/tx_browser_pi1/navigation/map/configuration.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/static/includes/setup/plugin/tx_browser_pi1/navigation/map/debugging.ts">

plugin.tx_browser_pi1 {
  navigation {
      // debugging, enabled, provider, template, configuration, marker
    map =
    map {
        // [STRING] disabled,Map,Map +Routes
      enabled  = {$plugin.tx_browser_pi1.map.controlling.enabled}
        // [STRING] GoogleMaps or Open Street Map
      provider  = {$plugin.tx_browser_pi1.map.controlling.provider}
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/static/includes/setup/plugin/tx_browser_pi1/navigation/map/marker/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/static/includes/setup/plugin/tx_browser_pi1/navigation/map/template.ts">
