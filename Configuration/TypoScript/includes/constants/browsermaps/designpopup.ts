plugin.tx_browser_pi1 {
  map {
    popup {
      # cat=BrowserMaps - Design Popup//200;    type=int;      label= X-offset: X-offset of the popup from the marker anchor (recommended: 0)
      offsetX = 0
      # cat=BrowserMaps - Design Popup//201;    type=int;      label= Y-offset: Y-offset of the popup from the marker anchor (recommended: minus height of the icon)
      offsetY = -14
      image {
        # cat=BrowserMaps - Design Popup//300;    type=int+;      label= Image position*:Position of the image in the list of files: 0 (and empty) is 1st position (default),  1 is 2nd position, 2 is 3rd position and so on.
        listNum    = 0
        # cat=BrowserMaps - Design Popup//310;    type=string;    label= Image height:Height of the image in pixel. I.e: width = 120c and height = 80c crops 120x80px from the center of the scaled image. Width = 100c-100 and height = 100c crops 100x100px from landscape-images at the left and portrait- images centered. Width = 100c+30 height = 100c-25 crops 100x100px from landscape-images a bit right of the center and portrait-images a bit higher than centered.
        height     = 40c
        # cat=BrowserMaps - Design Popup//311;    type=string;    label= Image width:Width of the image in pixel. The samples at property height above.
        width      = 40c
      }
      # cat=BrowserMaps - Design Popup//400;    type=string;    label= Text cropping:Crop the text after x chars. Leave it empty, if you don't want any cropping. I.e: 120|...|1
      text.crop   = 60|...|1
      # cat=BrowserMaps - Design Popup/others/999;      type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
      controlling.links = Click me!
    }
  }
}

plugin.tx_browser_pi1 {
  map {
    popup {
      leafletProperties {
        # cat=BrowserMaps - Design Popup - Properties//100;    type=string;                 label= className: A custom class name to assign to the popup. YOU MUST WRAP THE CLASS NAME IN APOSTROPHE! Example: 'myclass'. Leaflet default: ''
        className     =
        # cat=BrowserMaps - Design Popup - Properties//101;    type=options[,true,false];   label= closeButton: Controls the presense of a close button in the popup. Leaflet default: true
        closeButton   =
        # cat=BrowserMaps - Design Popup - Properties//102;    type=int+;                   label= maxHeight: If set, creates a scrollable container of the given height inside a popup if its content exceeds it. Leaflet default: null
        maxHeight     =
        # cat=BrowserMaps - Design Popup - Properties//103;    type=int+;                   label= maxWidth: Max width of the popup. Leaflet default: 300
        maxWidth      = 300
        # cat=BrowserMaps - Design Popup - Properties//104;    type=int+;                   label= minWidth: Min width of the popup. Leaflet default: 50
        minWidth      = 50
        # cat=BrowserMaps - Design Popup - Properties//105;    type=string;                 label= offset: The offset of the popup position. Useful to control the anchor of the popup when opening it on some overlays. YOU MUST WRAP THE CLASS NAME IN BRACKETS! Example: (0, 6).  Leaflet default: (0, 6)
        offset        =
        # cat=BrowserMaps - Design Popup - Properties//106;    type=options[,true,false];   label= zoomAnimation: Whether to animate the popup on zoom. Disable it if you have problems with Flash content inside popups.  Leaflet default: true
        zoomAnimation	=
      }
    }
  }
}