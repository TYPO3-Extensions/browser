plugin.tx_browser_pi1 {
  # cat=BrowserMaps - Design Popup//100;   type=int+;       label= Image position*:Position of the image in the list of files: 0 (and empty) is 1st position (default),  1 is 2nd position, 2 is 3rd position and so on.
  map.popup.image.listNum    = 0
  # cat=BrowserMaps - Design Popup//110;   type=string;     label= Image height:Height of the image in pixel. I.e: width = 120c and height = 80c crops 120x80px from the center of the scaled image. Width = 100c-100 and height = 100c crops 100x100px from landscape-images at the left and portrait- images centered. Width = 100c+30 height = 100c-25 crops 100x100px from landscape-images a bit right of the center and portrait-images a bit higher than centered.
  map.popup.image.height     = 40c
  # cat=BrowserMaps - Design Popup//111;   type=string;     label= Image width:Width of the image in pixel. The samples at property height above.
  map.popup.image.width      = 40c
  # cat=BrowserMaps - Design Popup//200;   type=int+;       label= Text cropping:Crop the text after x chars. Leave it empty, if you don't want any cropping. I.e: 120
  map.popup.text.crop   = 60
  # cat=BrowserMaps - Design Popup/others/999;      type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  map.popup.controlling.links = Click me!
}
