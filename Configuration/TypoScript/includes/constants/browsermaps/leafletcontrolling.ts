plugin.tx_browser_pi1 {
  # cat=BrowserMaps - leaflet controlling//100;           type=boolean;    label= Overlays: Disable it, if you don't like that each category is one overlay. Without any effect for oxMap!
  map.leafletcontrolling.overlays = 1
  # cat=BrowserMaps - leaflet controlling//200;           type=boolean;    label= Master Clustering: Disable it, if you don't like to cluster marker.
  map.leafletcontrolling.masterclustering = 1
  # cat=BrowserMaps - leaflet controlling/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.leafletcontrolling.links = Click me!
}
