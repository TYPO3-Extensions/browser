plugin.tx_browser_pi1 {

  # cat=Browser - Templates - Single - Text 0*//102;         type=string;     label= Header*:(Obligate!) Field with the content for the header. I.e: tx_org_event.header
  templates.singleview.text.0.header       =
  # cat=Browser - Templates - Single - Text 0*//102;         type=string;     label= Header tag:HTML tag for the header. I.e: h1
  templates.singleview.text.0.headerTag    = h1
  # cat=Browser - Templates - Single - Text 0*//110;         type=string;     label= Bodytext:Field with the content for the bodytext. I.e: tx_org_event.bodytext // tx_org_event.teaser_short
  templates.singleview.text.0.bodytext     =
  # cat=Browser - Templates - Single - Text 0*//others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.singleview.text.0.links      = Click me!

  # cat=Browser - Templates - Single - Image 0*//102;         type=string;     label= File*:(Obligate!) Field with the file name. I.e: tx_org_service.image
  templates.singleview.image.0.file       =
  # cat=Browser - Templates - Single - Image 0*//110;         type=string;     label= Height field:Field with the image height. I.e: tx_org_service.imageheight
  templates.singleview.image.0.height     =
  # cat=Browser - Templates - Single - Image 0*//111;         type=string;     label= Height default:Default height of the image in pixel. I.e: width = 120c and height = 80c crops 120x80px from the center of the scaled image. Width = 100c-100 and height = 100c crops 100x100px from landscape-images at the left and portrait- images centered. Width = 100c+30 height = 100c-25 crops 100x100px from landscape-images a bit right of the center and portrait-images a bit higher than centered.
  templates.singleview.image.0.heightDefault =
  # cat=Browser - Templates - Single - Image 0*//112;         type=string;     label= Width field:Field with the image width. I.e: tx_org_service.imagewidth
  templates.singleview.image.0.width      =
  # cat=Browser - Templates - Single - Image 0*//113;         type=string;     label= Width default:Default width of the image in pixel. See the samples at property height above.
  templates.singleview.image.0.widthDefault = 600
  # cat=Browser - Templates - Single - Image 0*//114;         type=string;     label= SEO field:Field for Search Engine Optimisation (SEO). The field for alttext and the titletext property. I.e. tx_org_service.imageseo
  templates.singleview.image.0.seo        =
  # cat=Browser - Templates - Single - Image 0*//115;         type=string;     label= Caption:Field for the image caption. I.e. tx_org_service.imagecaption
  templates.singleview.image.0.caption    =
  # cat=Browser - Templates - Single - Image 0*//116;         type=string;     label= Image orient:Field for the image orient. I.e. tx_org_service.imageorient
  templates.singleview.image.0.imageorient  =
  # cat=Browser - Templates - Single - Image 0*//117;         type=string;     label= Image columns:Field for the image columns. I.e. tx_org_service.imagecols
  templates.singleview.image.0.imagecols  =
  # cat=Browser - Templates - Single - Image 0*//120;         type=options[data,default,picture,srcset];     label= Render layout:data: renders an image tag containing data-keys for the different resolutions. default: renders a normal non-responsive image as a <img> tag. picture: renders a picture tag containing source tags for each resolutions and an <img> tag for the default image. srcset: renders an image tag pointing to a set of images for the different resolutions.
  templates.singleview.image.0.layoutKey  = default
  # cat=Browser - Templates - Single - Image 0*//130;         type=string;     label= Path*:(Obligate!) Path to the images (with ending backslash!). I.e: uploads/tx_org/
  templates.singleview.image.0.path       =
  # cat=Browser - Templates - Single - Image 0*//others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.singleview.image.0.links      = Click me!

}