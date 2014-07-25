plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Database Path Mapper*//201;      type=string;    label= table with the path data*: *Obligate! Example: tx_route_path
  map.path.mapper.tables.local.path =
  # cat=BrowserMaps - Database Path Mapper*//202;      type=string;    label= table with the marker data*: *Obligate! Example: tx_route_marker
  map.path.mapper.tables.local.marker =
  # cat=BrowserMaps - Database Path Mapper*//203;      type=string;    label= table with the path categories*: *Obligate! Example: tx_route_path_cat
  map.path.mapper.tables.cat.path =
  # cat=BrowserMaps - Database Path Mapper*//204;      type=string;    label= table with the marker data*: *Obligate! Example: tx_route_marker_cat
  map.path.mapper.tables.cat.marker =
  # cat=BrowserMaps - Database Path Mapper*//301;      type=string;    label= title field of the path table: Example: title
  map.path.mapper.fields.local.title.path =
  # cat=BrowserMaps - Database Path Mapper*//302;      type=string;    label= title field of the marker table: Example: title
  map.path.mapper.fields.local.title.marker =
  # cat=BrowserMaps - Database Path Mapper*//303;      type=string;    label= latitude field of the path table*: *Obligate! Example: icon_lat
  map.path.mapper.fields.local.lat.path =
  # cat=BrowserMaps - Database Path Mapper*//304;      type=string;    label= latitude field of the marker table*: *Obligate! Example: lat
  map.path.mapper.fields.local.lat.marker =
  # cat=BrowserMaps - Database Path Mapper*//305;      type=string;    label= longitude field of the path table*: *Obligate! Example: icon_lon
  map.path.mapper.fields.local.lon.path =
  # cat=BrowserMaps - Database Path Mapper*//306;      type=string;    label= longitude field of the marker table*: *Obligate! Example: lon
  map.path.mapper.fields.local.lon.marker =
  # cat=BrowserMaps - Database Path Mapper*//401;      type=string;    label= title field of the path cat table: Example: title
  map.path.mapper.fields.cat.title.path =
  # cat=BrowserMaps - Database Path Mapper*//402;      type=string;    label= title field of the marker cat table: Example: title
  map.path.mapper.fields.cat.title.marker =
  # cat=BrowserMaps - Database Path Mapper*//403;      type=string;    label= icons field of the path cat table: Example: icons
  map.path.mapper.fields.cat.icons.path =
  # cat=BrowserMaps - Database Path Mapper*//404;      type=string;    label= icons field of the marker cat table: Example: icons
  map.path.mapper.fields.cat.icons.marker =
  # cat=BrowserMaps - Database Path Mapper*//405;      type=string;    label= icons-offset-x of path cat table: Example: icons_offset_x
  map.path.mapper.fields.cat.iconOffsetX.path =
  # cat=BrowserMaps - Database Path Mapper*//406;      type=string;    label= icons-offset-x of marker cat table: Example: icons_offset_x
  map.path.mapper.fields.cat.iconOffsetX.marker =
  # cat=BrowserMaps - Database Path Mapper*//407;      type=string;    label= icons-offset-y of path cat table: Example: icons_offset_y
  map.path.mapper.fields.cat.iconOffsetY.path =
  # cat=BrowserMaps - Database Path Mapper*//408;      type=string;    label= icons-offset-y of marker cat table: Example: icons_offset_y
  map.path.mapper.fields.cat.iconOffsetY.marker =
  # cat=BrowserMaps - Database Path Mapper*/others/999;       type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.path.mapper.database.links = Click me!

}
