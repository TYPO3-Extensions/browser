plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Debugging//101;             type=boolean;   label= Frontend Alert:Error module is added to the modules, LayerSwitcher to the controllers. There will be an alert in the frontend in case of a bug.
  map.debugging.feAlert = 0
  # cat=BrowserMaps - Debugging//102;             type=boolean;   label= Use uncompressed JavaScript:The oxMap API files will included uncompressed. Default is one include compressed file.
  map.debugging.uncompressed = 0
  # cat=BrowserMaps - Debugging//103;             type=boolean;   label= Route Relations:(Recommended) Debug unproper relations for routes. You will get a prompt in the frontend, if categories or marker (POI) aren't proper
  map.debugging.route.relations = 1
  # cat=BrowserMaps - Debugging/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.debugging.links = Click me!

}
