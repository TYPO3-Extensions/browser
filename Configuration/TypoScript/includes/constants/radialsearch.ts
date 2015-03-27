plugin.tx_browser_pi1 {

  # cat=Browser - Radial Search/others/100;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptRadialsearch]; label=Help!
  radialsearch.help = Click me!
  # cat=Browser - Radial Search//200;       type=string;    label= Latitude: table.field label for the latitude. Example: tx_myext_address.lat, tx_org_headquarters.mail_lat
  radialsearch.lat =
  # cat=Browser - Radial Search//201;       type=string;    label= Longitude: table.field label for the longitude. Example: tx_myext_address.lon, tx_org_headquarters.mail_lon
  radialsearch.lon =
  # cat=Browser - Radial Search//202;       type=string;    label= Uid: table.field label for the uid. Example: tx_myext_address.uid,
  radialsearch.uid =
  # cat=Browser - Radial Search//300;       type=string;    label= Sword: HTML name of the input field for the search word (sword).
  radialsearch.sword = tx_radialsearch_pi1[sword]
  # cat=Browser - Radial Search//301;       type=string;    label= Radius: HTML name of the select box with the radius options.
  radialsearch.radius = tx_radialsearch_pi1[radius]
  # cat=Browser - Radial Search//400;       type=options[Within the radius only,Within and without the radius];    label= Search mode: Display hits ...
  radialsearch.searchmode = Within and without the radius
  # cat=Browser - Radial Search/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  radialsearch.links = Click me!

}
