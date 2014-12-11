plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Zoom//101;        type=boolean;   label= Zoom wheel:Enable or disable the zoom wheel.
  map.zoomWheel = 1
  # cat=BrowserMaps - Zoom//102;        type=options[auto,fixed];  label= Zoom level mode:Auto: An optimal zoom level will calculated. Fixed: Zoom level will taken from the field "Fixed zoom level" below.
  map.zoomLevel.mode = auto
  # cat=BrowserMaps - Zoom//103;        type=int+;      label= Start zoom level: The start zoom level. Value from 1 to 18. Value has an effect only, if zoom level mode is "fixed". Default: 4
  map.zoomLevel.start = 4
  # cat=BrowserMaps - Zoom//104;        type=int+;      label= Maximum zoom level: The maximum zoom level. Maximum value has an effect only, if zoom level mode is "auto".  Default: 18
  map.zoomLevel.max = 18
  # cat=BrowserMaps - Zoom//105;        type=int+;      label= Number of zoom levels for CI: The number of zoom levels with an own CI. Value should not be greater than Maximum zoom level.
  map.zoomLevel.levels = 18
  # cat=BrowserMaps - Zoom/others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.zoom.links = Click me!

}
