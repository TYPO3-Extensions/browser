plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Compatibility//101;           type=options[leaflet (default),oxMap (deprecated)];   label= mode:Use oxMap, if you liie the map modul of the Browser upto version 6.x. Leaflet: You must configure the provider constants. oxMap: You must configure the controlling constants.
  map.compatibility.mode = leaflet (default)
  # cat=BrowserMaps - Compatibility/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.compatibility.links = Click me!

}
