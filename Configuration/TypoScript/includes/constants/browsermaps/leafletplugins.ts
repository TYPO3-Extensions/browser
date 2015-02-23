plugin.tx_browser_pi1 {
  # cat=BrowserMaps - leaflet plugins//102;           type=string;    label= Google Maps Api: External link
  map.leafletplugins.googleApi        = http://maps.google.com/maps/api/js?v=3&sensor=false
  # cat=BrowserMaps - leaflet plugins//202;           type=string;    label= Google Tile Layer:
  map.leafletplugins.layertilegoogle  = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/Plugins/leaflet-plugins-master/layer/tile/Google.js
  # cat=BrowserMaps - leaflet plugins/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.leafletplugins.links = Click me!
}
