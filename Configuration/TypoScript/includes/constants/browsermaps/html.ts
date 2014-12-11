plugin.tx_browser_pi1 {
  # cat=BrowserMaps - HTML//102;             type=string;    label= Map id: HTML id of the map div-tag
  map.html.id               = oxMap-area
  # cat=BrowserMaps - HTML//102;             type=string;    label= Filter id: HTML id of the filter form
  map.html.form.id          = oxMap-filter-module
  # cat=BrowserMaps - HTML//103;             type=string;    label= Input class: HTML class of the input fields
  map.html.form.input.class = oxMap-filter-item
  # cat=BrowserMaps - HTML//200;             type=string;    label= label= table.field with the CSS class: CSS class for the lable tag in the map form for a marker. Example: tx_route_marker_cat.formlabelcss
  map.html.form.label.class.marker =
  # cat=BrowserMaps - HTML//201;             type=string;    label= label= table.field with the CSS class: CSS class for the lable tag in the map form for a path. Example: tx_route_path_cat.formlabelcss
  map.html.form.label.class.path =
  # cat=BrowserMaps - HTML/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.html.links = Click me!
}
