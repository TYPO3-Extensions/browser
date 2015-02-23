plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Design Map//100;             type=int+;      label= Map height 1: Height of the map in the list view (in pixel). oxMap: height in list and single view.
  map.design.height = 400
  # cat=BrowserMaps - Design Map//101;             type=int+;      label= Map height 2: Height of the map in the single view (in pixel). Not for oxMap!
  map.design.height.single = 400
  # cat=BrowserMaps - Design Map//102;             type=int+;      label= Map width: Width of the map in the list view (in pixel). oxMap only!
  map.design.width = 600
  # cat=BrowserMaps - Design Map//300;             type=string;    label= CSS file: Path to the CSS file. Example: /fileadmin/my_openstreetmap/css/style.css
  map.design.css      = /typo3conf/ext/browser/Resources/Public/JavaScript/Map/oxMap/lib/OpenLayers_2.12/theme/default/style.css
  # cat=BrowserMaps - Design Map//2301;             type=string;    label= Control panel directory: Path to the directory with the icons of the control panel among others (with ending slash). Example: /fileadmin/my_openstreetmap/icons/
  map.design.imgPath  = /typo3conf/ext/browser/Resources/Public/JavaScript/Map/oxMap/lib/OpenLayers_2.12/img/
  # cat=BrowserMaps - Design Map//302;             type=string;    label= Icons directory: Path to the directory with the category icons (with ending slash). Example: uploads/tx_org/
  map.design.path.categoryIcon = uploads/media/
  # cat=BrowserMaps - Design Map/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.design.controlling.links = Click me!

}
