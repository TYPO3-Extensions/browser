plugin.tx_browser_pi1 {

  # cat=BrowserMaps - OpenLayers//101;             type=string;    label= Map controls: Comma seperated list of quoted map controls wrapped by square brackets. Examples: Navigation,PanZoom or Navigation,PanZoom,LayerSwitcher
  map.openlayers.controls.default         = 'Navigation,PanZoom'
  # cat=BrowserMaps - OpenLayers//102;             type=string;    label= Map controls (debugging): Comma seperated list of quoted map controls wrapped by square brackets. Examples: Navigation,PanZoom or Navigation,PanZoom,LayerSwitcher
  map.openlayers.controls.debugging       = 'Navigation,PanZoom,LayerSwitcher'
  # cat=BrowserMaps - OpenLayers//103;             type=string;    label= Map modules: Comma seperated list of map modules (quoted). Values: Error, OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route. OSM.CDmap is needed, if you are using the custom array. And OSM.CDmap has to place before OSM.Base.
  map.openlayers.modules.default          = 'OSM.Base,OSM.Filter'
  # cat=BrowserMaps - OpenLayers//104;             type=string;    label= Map +Routes modules: Comma seperated list of map modules (quoted). OSM.Routes must be the first element! Values: Error, OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route. OSM.CDmap is needed, if you are using the custom array. And OSM.CDmap has to place before OSM.Base.
  map.openlayers.modules.routes.default   = 'OSM.Route,OSM.Base,OSM.Filter'
  # cat=BrowserMaps - OpenLayers//105;             type=string;    label= Map modules (debugging): Comma seperated list of map modules (quoted). Values: Error, OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route. OSM.CDmap is needed, if you are using the custom array.
  map.openlayers.modules.debugging        = 'Error,OSM.Base,OSM.Filter'
  # cat=BrowserMaps - OpenLayers//106;             type=string;    label= Map +Routes modules (debugging): Comma seperated list of map modules (quoted). OSM.Routes must be the first element! Values: Error, OSM.CDmap, OSM.Base (OSM.Render, OSM.Marker, OSM.Tooltip), OSM.Filter, OSM.Route. OSM.CDmap is needed, if you are using the custom array.
  map.openlayers.modules.routes.debugging = 'Error,OSM.Route,OSM.Base,OSM.Filter'
  # cat=BrowserMaps - OpenLayers//107;             type=options[click,hover,off,on];  label= Pop-up behaviour:click: display by a mouse click. hover: display by a mouse over. off: don't display. on: display a point without any effect. If you have touchscreens enabled (see section MOBILE), your selection will ignored: hover will used in any case.
  map.openlayers.popup.behaviour = hover
  # cat=BrowserMaps - OpenLayers/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.openlayers.controlling.links = Click me!

}
