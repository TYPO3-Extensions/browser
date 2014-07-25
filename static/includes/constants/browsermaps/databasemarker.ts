plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Database Marker*//101;           type=string;    label= table.field with record uid*: *Obligate! Example: tx_org_headquarters.uid
  map.marker.field.linktoSingle =
  # cat=BrowserMaps - Database Marker*//102;           type=string;    label= table.field with the latitude*: *Obligate! Example: tx_org_headquarters.latitude
  map.marker.field.latitude =
  # cat=BrowserMaps - Database Marker*//103;           type=string;    label= table.field with the longitude*: *Obligate! Example: tx_org_headquarters.longitude
  map.marker.field.longitude =
  # cat=BrowserMaps - Database Marker*//104;           type=string;    label= table.field with the content*: *Obligate! table.field with the content for the popup. Example: tx_org_headquarters.short
  map.marker.field.description =
  # cat=BrowserMaps - Database Marker*//105;           type=string;    label= table.field with the number: It may be a number or text. It is optional. Example: tx_org_headquarters.uid
  map.marker.field.number =
  # cat=BrowserMaps - Database Marker*//106;           type=string;    label= table.field with the category: Example: tx_org_headquarterscat.title
  map.marker.field.category =
  # cat=BrowserMaps - Database Marker*//108;           type=string;    label= table.field with the category icons: Example: tx_org_headquarterscat.icons
  map.marker.field.categoryIcon =
  # cat=BrowserMaps - Database Marker*//110;           type=string;    label= table.field with category x-offset: Example: tx_org_headquarterscat.icon_offset_x
  map.marker.field.categoryOffsetX =
  # cat=BrowserMaps - Database Marker*//111;           type=string;    label= table.field with category y-offset: Example: tx_org_headquarterscat.icon_offset_y
  map.marker.field.categoryOffsetY =
  # cat=BrowserMaps - Database Marker*/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.marker.database.links = Click me!

}