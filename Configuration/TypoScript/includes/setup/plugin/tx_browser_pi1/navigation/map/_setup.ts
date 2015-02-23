<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/compatibility.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/configuration.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/debugging.ts">

plugin.tx_browser_pi1 {
  navigation {
      // enabled, provider
    map =
    map {
        // [STRING] disabled,Map,Map +Routes
      enabled  = {$plugin.tx_browser_pi1.map.controlling.enabled}
        // default, google, order, osm
      provider  = {$plugin.tx_browser_pi1.map.controlling.provider}
      provider {
          // default provider
        default     = {$plugin.tx_browser_pi1.map.provider.default}
          // hybrid, roadmap, satellite, terrain
        google =
        google {
          hybrid    = {$plugin.tx_browser_pi1.map.provider.google.hybrid}
          roadmap   = {$plugin.tx_browser_pi1.map.provider.google.roadmap}
          satellite = {$plugin.tx_browser_pi1.map.provider.google.satellite}
          terrain   = {$plugin.tx_browser_pi1.map.provider.google.terrain}
        }
          // order of the providers
        order       = {$plugin.tx_browser_pi1.map.provider.order}
          // roadmap
        osm =
        osm {
          roadmap = {$plugin.tx_browser_pi1.map.provider.osm.roadmap}
        }
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/marker/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/rules.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/template.ts">