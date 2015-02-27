plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Controlling//401;             type=options[disabled,Map,Map +Routes];   label= Enable the map:Map: Map with marker only. Map +Routes: Map with routes and marker (oxMap only!)
  map.controlling.enabled = disabled
  # cat=BrowserMaps - Controlling//402;             type=options[GoogleMaps,Open Street Map];  label= Map Provider: [DEPRECATED] for oxMap only! GoogleMaps or OpenStreetMap. Please take care of the licences! Copyright must be visible!
  map.controlling.provider = Open Street Map
  # cat=BrowserMaps - Controlling//501;             type=boolean;   label= Empty coordinates?: Don't handle latitudes and longitudes, which are empty or 0.
  map.controlling.dontHandle00Coordinates = 1
  # cat=BrowserMaps - Controlling/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.controlling.links = Click me!

}