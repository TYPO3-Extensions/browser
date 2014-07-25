plugin.tx_browser_pi1 {

  # cat=BrowserMaps - Mobile//101;        type=boolean;   label= Optimize for Touchscreens:You can navigate in your map in touchscreens, if this option is enabled. This option has the disadvantage, that you can't use the click mode for your pop ups. In enabled touchscreen mode pop ups will enabled by a hover automatically, click mode will ignored.
  map.mobileTouchscreen = 0
  # cat=BrowserMaps - Mobile/others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.mobile.links = Click me!

}