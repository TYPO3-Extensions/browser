plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Default Icons//101;             type=string;    label= Path to legend icons: Path to the directory, which contains the images for the form legend (with ending slash!). Default: EXT:browser/Resources/Public/JavaScript/Map/icons/legend/
  map.pathToLegend = EXT:browser/Resources/Public/JavaScript/Map/icons/legend/
  # cat=BrowserMaps - Default Icons//102;             type=string;    label= Path to point icons: Path to the directory, which contains the images for points in the map (with ending slash!). Default: EXT:browser/Resources/Public/JavaScript/Map/icons/points/
  map.pathToPoints = EXT:browser/Resources/Public/JavaScript/Map/icons/points/
  # cat=BrowserMaps - Default Icons/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.defaultIcons.links = Click me!

}
