plugin.tx_browser_pi1 {

  # cat=Browser - Templates - Single - Image 1*//102;         type=string;     label= File*:(Obligate!) Field with the file name. I.e: tx_org_service.image
  templates.singleview.image.1.file       =
  # cat=Browser - Templates - Single - Image 1*//110;         type=string;     label= Height:Field with the image height. I.e: tx_org_service.imageheight
  templates.singleview.image.1.height     =
  # cat=Browser - Templates - Single - Image 1*//111;         type=string;     label= Width:Field with the image width. I.e: tx_org_service.imagewidth
  templates.singleview.image.1.width      =
  # cat=Browser - Templates - Single - Image 1*//112;         type=string;     label= SEO field:Field for Search Engine Optimisation (SEO). The field for alttext and the titletext property. I.e. tx_org_service.imageseo
  templates.singleview.image.1.seo        =
  # cat=Browser - Templates - Single - Image 1*//113;         type=string;     label= Caption:Field for the image caption. I.e. tx_org_service.imagecaption
  templates.singleview.image.1.caption    =
  # cat=Browser - Templates - Single - Image 1*//114;         type=string;     label= Image orient:Field for the image orient. I.e. tx_org_service.imageorient
  templates.singleview.image.1.imageorient  =
  # cat=Browser - Templates - Single - Image 1*//115;         type=string;     label= Image columns:Field for the image columns. I.e. tx_org_service.imagecols
  templates.singleview.image.1.imagecols  =
  # cat=Browser - Templates - Single - Image 1*//120;         type=options[data,default,picture,srcset];     label= Render layout:data: renders an image tag containing data-keys for the different resolutions. default: renders a normal non-responsive image as a <img> tag. picture: renders a picture tag containing source tags for each resolutions and an <img> tag for the default image. srcset: renders an image tag pointing to a set of images for the different resolutions.
  templates.singleview.image.1.layoutKey  = default
  # cat=Browser - Templates - Single - Image 1*//130;         type=string;     label= Path*:(Obligate!) Path to the images (with ending backslash!). I.e: uploads/tx_org/
  templates.singleview.image.1.path       =
  # cat=Browser - Templates - Single - Image 1*//others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.singleview.image.1.links      = Click me!

}