// page.9000
// page.9001
// page.9002

  // Test if leavelet is enabled
page.9000 = COA
page.9000 {
    // true, if leavelet is the current modul and map isn't disabled
  10 = TEXT
  10 {
    value (
      <h1>Leaflet is enabled</h1>
      <p>
        True: modul leaflet<br />
        True: one of the OpenStreetMap or GoogleMaps layers hybrid, roadmap, stellite or terrain is enabled at least
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletIsEnabled
  }
    // true, if leavelet isn't the current modul and/or map is disabled
  20 = TEXT
  20 {
    value (
      <h1>Leaflet isn't enabled</h1>
      <p>
        One or both aren't true:<br />
        Current modul must be leaflet<br />
        One of the OpenStreetMap or GoogleMaps layers hybrid, roadmap, stellite or terrain must be enabled at least
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletIsEnabled
    if {
      negate = 1
    }
  }
}
page.9000 >

  // Test if leavelet and google is enabled
page.9001 = COA
page.9001 {
    // true, if leavelet is the current modul and map isn't disabled
  10 = TEXT
  10 {
    value (
      <h1>Leaflet and GoogleMaps are enabled</h1>
      <p>
        True: modul leaflet<br />
        True: if one of the GoogleMaps layers hybrid, roadmap, stellite or terrain is enabled at least
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiGoogle
  }
    // true, if leavelet isn't the current modul and/or map is disabled
  20 = TEXT
  20 {
    value (
      <h1>Leaflet and GoogleMaps aren't enabled</h1>
      <p>
        One or all aren't true:<br />
        Current modul must be leaflet<br />
        One of the GoogleMaps layers hybrid, roadmap, stellite or terrain must enabled at least
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiGoogle
    if {
      negate = 1
    }
  }
}
page.9001 >

  // Test if leavelet is enabled
page.9002 = COA
page.9002 {
    // true, if leavelet is the current modul and map isn't disabled
  10 = TEXT
  10 {
    value (
      <h1>Leaflet master clustering is enabled</h1>
      <p>
        True: modul leaflet<br />
        True: one of the OpenStreetMap or GoogleMaps layers hybrid, roadmap, stellite or terrain is enabled at least<br />
        True: master clustering is enabled
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiMastercluster
  }
    // true, if leavelet isn't the current modul and/or map is disabled
  20 = TEXT
  20 {
    value (
      <h1>Leaflet master clustering isn't enabled</h1>
      <p>
        One, two or all aren't true:<br />
        Current modul must be leaflet<br />
        One of the OpenStreetMap or GoogleMaps layers hybrid, roadmap, stellite or terrain must be enabled at least<br />
        Master clustering isn't enabled
      </p>
      <p>test @ browser/Configuration/TypoScript/includes/setup/PAGE/testleaflet.ts</p>
)
    wrap = |
    if < plugin.tx_browser_pi1.navigation.map.rules.leafletWiMastercluster
    if {
      negate = 1
    }
  }
}
page.9002 >

