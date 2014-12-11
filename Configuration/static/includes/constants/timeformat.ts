plugin.tx_browser_pi1 {

  # cat=Browser - Time Format//101;       type=string;  label= Format for strftime
  strftime = %a, %d.%b.%y %H:%M
  # cat=Browser - Time Format/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  timeFormat.links = Click me!

}
