plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Default Icons//100;             type=boolean;   label= Enabled: Use the leaflet default icon (recommended). If you like to use your own icons, please configure the properties below and the categories [BROWSERMAPS - DATABASEMARKER] and [BROWSERMAPS - ICONS].
  map.defaultIcons.enabled = 1
  # cat=BrowserMaps - Default Icons//110;             type=string;    label= Shadow: Path to the shadow image
  map.defaultIcons.shadow.path = EXT:browser/Resources/Public/JavaScript/Map/Leaflet/0.7.3/images/marker-shadow.png
  # cat=BrowserMaps - Default Icons//111;             type=int;      label= Shadow x-offset: X-offset of the shadow anchor (recommended: half of the width of the icon)
  map.defaultIcons.shadow.offsetX = 14
  # cat=BrowserMaps - Default Icons//112;             type=int;      label= Shadow y-offset: Y-offset of the shadow anchor (recommended: height of the shadow)
  map.defaultIcons.shadow.offsetY = 41

  map.defaultIcons.pathToShadow = EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/legend/
  # cat=BrowserMaps - Default Icons//801;             type=string;    label= Path to legend icons: [DEPRECATED] oxMap only! Path to the directory, which contains the images for the form legend (with ending slash!). Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/legend/
  map.defaultIcons.pathToLegend = EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/legend/
  # cat=BrowserMaps - Default Icons//802;             type=string;    label= Path to point icons: [DEPRECATED] oxMap only! Path to the directory, which contains the images for points in the map (with ending slash!). Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/
  map.defaultIcons.pathToPoints = EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/
  # cat=BrowserMaps - Default Icons/others/999;       type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.defaultIcons.links = Click me!

}
