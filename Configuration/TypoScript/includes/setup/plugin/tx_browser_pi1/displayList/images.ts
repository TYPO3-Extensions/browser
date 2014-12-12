plugin.tx_browser_pi1 {
  displayList {
      // [Boolean] Display caption in List view?
    caption_stdWrap.if.directReturn = 0

      // [Integer] Amount of images which should be displayed
    imageCount     = {$plugin.tx_browser_pi1.listImageCount}
      // [String] Wrap code for the whole image block (if there is any image)
    imageWrapIfAny =
    imageBoxWrap {
        // [String] Wrap code for every image/imagecaption block #11211
      wrap = <span class="list-imagebox list-imagebox-###IMAGE_COUNT###"> | </span>
    }
    image {
        // [String] path and file name of the image, which should displayed, if a record hasn't any image #11204
      file = EXT:browser/Resources/Public/Images/Icons/alternate_image_400x300.gif
      file {
        maxW = {$plugin.tx_browser_pi1.listMaxW}
        maxH = {$plugin.tx_browser_pi1.listMaxH}
      }
      wrap          =
      imageLinkWrap = 1
      imageLinkWrap {
        enable = 1
        bodyTag = <body bgColor="#ffffff">
        wrap = <a href="javascript:close();"> | </a>
        width = {$plugin.tx_browser_pi1.listPopupMaxW}
        height = {$plugin.tx_browser_pi1.listPopupMaxH}
        JSwindow = 1
        JSwindow.newWindow = 1
        JSwindow.expand = 17,20
      }
      noImage_stdWrap {
      }
      firstImageIsPreview       >
      forceFirstImageIsPreview  >
    }
  }
}