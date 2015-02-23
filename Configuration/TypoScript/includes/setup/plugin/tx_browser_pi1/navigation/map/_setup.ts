<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/compatibility.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/configuration.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/debugging.ts">

plugin.tx_browser_pi1 {
  navigation {
      // empty statement for proper comments only
    map {
    }
      // enabled, provider
    map =
    map {
        // [STRING] disabled,Map,Map +Routes
      enabled  = {$plugin.tx_browser_pi1.map.controlling.enabled}
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/marker/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/plugins.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/provider.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/rules.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/map/template.ts">