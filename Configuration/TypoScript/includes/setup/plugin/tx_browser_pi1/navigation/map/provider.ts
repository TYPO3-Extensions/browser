plugin.tx_browser_pi1 {
  navigation {
      // enabled, provider
    map =
    map {
        // empty statement for proper comments only
      provider {
      }
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