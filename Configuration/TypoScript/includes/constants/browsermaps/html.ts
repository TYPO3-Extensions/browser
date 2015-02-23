plugin.tx_browser_pi1 {
  # cat=BrowserMaps - HTML//101;           type=string;    label= Template:[DEPRECATED] oxMap only! Path to the HTML template. Examples: EXT:browser/Resources/Private/Templates/HTML/Map/oxMap/default_1.3.tmpl, EXT:browser/Resources/Private/Templates/HTML/Map/oxMap/map_toggle.tmpl
  map.html.path             =
  # cat=BrowserMaps - HTML//102;           type=boolean;    label= JavaScript: Include JavaScript code for toggling the map. Needed, if you are using the HTML template map_toggle.tmpl from above.
  map.html.jss.toggle       = 0
  # cat=BrowserMaps - HTML//202;           type=string;    label= Map id: HTML id of the map div-tag. If you are change it, please change it in the toggle Javascript too.
  map.html.id               = leafletmap
  # cat=BrowserMaps - HTML//202;           type=string;    label= Filter id: HTML id of the filter form
  map.html.form.id          = oxMap-filter-module
  # cat=BrowserMaps - HTML//203;           type=string;    label= Input class: HTML class of the input fields
  map.html.form.input.class = oxMap-filter-item
  # cat=BrowserMaps - HTML//300;           type=string;    label= label= table.field with the CSS class: CSS class for the lable tag in the map form for a marker. Example: tx_route_marker_cat.formlabelcss
  map.html.form.label.class.marker =
  # cat=BrowserMaps - HTML//301;           type=string;    label= label= table.field with the CSS class: CSS class for the lable tag in the map form for a path. Example: tx_route_path_cat.formlabelcss
  map.html.form.label.class.path =
  # cat=BrowserMaps - HTML/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.html.links = Click me!
}
