plugin.tx_browser_pi1 {
  # cat=BrowserMaps - leaflet plugins//100;           type=string;    label= Google Maps Api: External link to the Google API. It is needed for the Google Tile Layer below.
  map.leafletplugins.googleApi                = http://maps.google.com/maps/api/js?v=3&sensor=false
  # cat=BrowserMaps - leaflet plugins//101;           type=string;    label= Google Tile Layer:Script is needed for using Google Maps.
  map.leafletplugins.layertilegoogle          = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/Plugins/leaflet-plugins-master/layer/tile/Google.js
  # cat=BrowserMaps - leaflet plugins//200;           type=string;    label= Master Clustring:Script is needed for clustering marker.
  map.leafletplugins.mastercluster            = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/Plugins/Leaflet.markercluster-master/dist/leaflet.markercluster.js
  # cat=BrowserMaps - leaflet plugins//200;           type=string;    label= Master Clustring:Script is needed for clustering marker.
  map.leafletplugins.masterclusterCss         = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/Plugins/Leaflet.markercluster-master/dist/MarkerCluster.css
  # cat=BrowserMaps - leaflet plugins//200;           type=string;    label= Master Clustring:Script is needed for clustering marker.
  map.leafletplugins.masterclusterCssDefault  = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/Plugins/Leaflet.markercluster-master/dist/MarkerCluster.Default.css
  # cat=BrowserMaps - leaflet plugins/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.leafletplugins.links = Click me!
}
