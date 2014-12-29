plugin.tx_browser_pi1 {

  # cat=Browser - Templates - List - Image 5*//102;         type=string;     label= File*:(Obligate!) Field with the file name. I.e: tx_org_service.image
  templates.listview.image.5.file       =
  # cat=Browser - Templates - List - Image 5*//103;         type=int+;       label= Position:Position of the image in the list of files: 0 (and empty) is 1st position (default),  1 is 2nd position, 2 is 3rd position and so on.
  templates.listview.image.5.listNum    = 0
  # cat=Browser - Templates - List - Image 5*//110;         type=string;     label= Height:Height of the image in pixel. I.e: width = 120c and height = 80c crops 120x80px from the center of the scaled image.5. Width = 100c-100 and height = 100c crops 100x100px from landscape-images at the left and portrait- images centered. Width = 100c+30 height = 100c-25 crops 100x100px from landscape-images a bit right of the center and portrait-images a bit higher than centered.
  templates.listview.image.5.height     = 70c
  # cat=Browser - Templates - List - Image 5*//111;         type=string;     label= Width:Width of the image in pixel. The samples at property height above.
  templates.listview.image.5.width      = 70c
  # cat=Browser - Templates - List - Image 5*//112;         type=string;     label= SEO field:Field for Search Engine Optimisation (SEO). The field for alttext and the titletext property. I.e. tx_org_service.imageseo
  templates.listview.image.5.seo        =
  # cat=Browser - Templates - List - Image 5*//113;         type=options[data,default,picture,srcset];     label= Render layout:data: renders an image tag containing data-keys for the different resolutions. default: renders a normal non-responsive image as a <img> tag. picture: renders a picture tag containing source tags for each resolutions and an <img> tag for the default image.5. srcset: renders an image tag pointing to a set of images for the different resolutions.
  templates.listview.image.5.layoutKey  = default
  # cat=Browser - Templates - List - Image 5*//130;         type=string;     label= Path*:(Obligate!) Path to the images (with ending backslash!). I.e: uploads/tx_org/
  templates.listview.image.5.path       =
  # cat=Browser - Templates - List - Image 5*//130;         type=string;     label= Default image:Full qualified path to the default image. I.e: EXT:browser/Resources/Public/Images/Icons/browser_default_300x200.gif
  templates.listview.image.5.default    = EXT:browser/Resources/Public/Images/Icons/browser_default_300x200.gif
  # cat=Browser - Templates - List - Image 5*//others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.listview.image.5.links      = Click me!

}