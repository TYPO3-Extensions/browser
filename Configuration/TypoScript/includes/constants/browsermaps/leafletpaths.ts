plugin.tx_browser_pi1 {
  # cat=BrowserMaps - leaflet files//101;           type=string;    label= CSS: Path to the cascading style sheet
  map.leafletfiles.css    = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/0.7.3/leaflet.css
  # cat=BrowserMaps - leaflet files//102;           type=string;    label= JavaScript: Path to the javascript library
  map.leafletfiles.js     = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/0.7.3/leaflet.js
  # cat=BrowserMaps - leaflet files/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.leafletfiles.links  = Click me!
}