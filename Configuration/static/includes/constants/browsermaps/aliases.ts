plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Aliases//101;                   type=string;    label= Label of a marker uid: Parameter of the marker uid in the URL. It is used in links to the single view
  map.aliases.showUid.marker  = showUid
  # cat=BrowserMaps - Aliases//102;                   type=string;    label= Label of a path uid: Parameter of the path uid in the URL. It is used in links to the single view
  map.aliases.showUid.path    = routeUid
  # cat=BrowserMaps - Aliases/others/999;             type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.aliases.links = Click me!

}
