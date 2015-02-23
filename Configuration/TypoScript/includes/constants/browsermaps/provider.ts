plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Provider*//101;       type=options[osmRoadmap,googleHybrid,googleRoadmap,googleSatellite,googleTerrain];   label= Default provider:Map will displayed with this provider by default. The selected provider must be enabled in the list below! The default provider will be the first in the controll panel. The order from below hasn't any effect.
  map.provider.default = osmRoadmap
  # cat=BrowserMaps - Provider*//201;       type=boolean;  label= OpenStreetMap Roadmap: Displays the default road map view. This is the default map type. Please take care of the licences. Copyright must be visible!
  map.provider.osm.roadmap = 1
  # cat=BrowserMaps - Provider*//301;       type=boolean;  label= GoogleMaps Roadmap: Displays the default road map view. This is the default map type. Please take care of the licences. Copyright must be visible!
  map.provider.google.roadmap = 1
  # cat=BrowserMaps - Provider*//302;       type=boolean;  label= GoogleMaps Terrain: Displays a physical map based on terrain information. Please take care of the licences. Copyright must be visible!
  map.provider.google.terrain = 1
  # cat=BrowserMaps - Provider*//303;       type=boolean;  label= GoogleMaps Satellite: Displays Google Earth satellite images. Please take care of the licences. Copyright must be visible!
  map.provider.google.satellite = 1
  # cat=BrowserMaps - Provider*//304;       type=boolean;  label= GoogleMaps Hybrid: Displays a mixture of normal and satellite views. Please take care of the licences. Copyright must be visible!
  map.provider.google.hybrid = 1
  # cat=BrowserMaps - Provider*//401;       type=string;   label= Order:Order of the providers in the controll panel. Keys are: googleHybrid, googleRoadmap, googleSatellite, googleTerrain, osmRoadmap. Be aware: The default provider from below will be the first in the controll panel.
  map.provider.order = osmRoadmap,googleRoadmap,googleSatellite,googleHybrid,googleTerrain
  # cat=BrowserMaps - Provider*/others/999; type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.provider.links = Click me!

}