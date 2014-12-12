plugin.tx_browser_pi1 {
  displaySingle {

    seo {
      htmlHead {
          // [Boolean] Search Engine Optimization for the meta tag title. Activate the register browser_htmlTitleTag.
        title = 0
        meta {
            // [Boolean] Search Engine Optimization for the meta tag description. Activate the register browser_description.
          description = 0
            // [Boolean] Search Engine Optimization for the meta tag keywords. Activate the register browser_keywords.
          keywords    = 0
        }
      }
    }

    display {
      title         = 1
      table {
        summary     = 1
        caption     = 1
      }
      backbutton = 1
      backbutton {
        data = LLL:EXT:browser/pi1/locallang.xml:backbutton
        noTrimWrap = |<a class="backbutton" href="javascript:history.back()">&laquo; |</a>|
      }
      searchform {
          // [Boolean] 1: Wrap the Swords in the result, 0: Don't wrap it
        wrapSwordInResults = 1
        wrapSwordInResults {
          0 {
              // #ffff7f: light yellow
            wrap = <span style="background:#ffff7f">|</span>
          }
          1 {
              // #b3ffb2: light green
            wrap = <span style="background:#b3ffb2">|</span>
          }
          2 {
              // #ffb2b2: light red
            wrap = <span style="background:#ffb2b2">|</span>
          }
          3 {
              // #b2b3ff: light blue
            wrap = <span style="background:#b2b3ff">|</span>
          }
          4 {
              // #ffffb3: light light yellow
            wrap = <span style="background:#ffffb3">|</span>
          }
          5 {
              // #e6ffb2 light light yellow green
            wrap = <span style="background:#e6ffb2">|</span>
          }
          6 {
              // #ffd380: light orange
            wrap = <span style="background:#ffd380">|</span>
          }
          7 {
              // #b3ffff: light turkise
            wrap = <span style="background:#b3ffff">|</span>
          }
        }
      }
    }

    templateMarker = ###TEMPLATE_SINGLE###
    templateMarker {
      oddClass {
          // [String] HTML class for odd columns (th, td)
        columns = odd
          // [String] HTML class for odd rows (tr)
        rows    = ui-priority-secondary
      }
    }

    content_stdWrap {
      parseFunc < lib.parseFunc_RTE
    }

    caption_stdWrap {
      wrap = <p class="single-image_caption"> | </p>
      trim=1
    }

    imageCount      = {$plugin.tx_browser_pi1.singleImageCount}
    imageWrapIfAny  = <div class="single-image single-image-###IMAGE_COUNT###"> | </div>
    imageBoxWrap {
        // [String] Wrap code for every image/imagecaption block #11211
      wrap = <span class="single-imagebox single-imagebox-###IMAGE_COUNT###"> | </span>
    }
    image {
        // [String] path and file name of the image, which should displayed, if a record hasn't any image #11204
      file = EXT:browser/Resources/Public/Images/Icons/alternate_image_400x300.gif
      file {
        maxW = {$plugin.tx_browser_pi1.singleMaxW}
        maxH = {$plugin.tx_browser_pi1.singleMaxH}
      }
      wrap =
      imageLinkWrap = 1
      imageLinkWrap {
        enable = 1
        bodyTag = <body bgColor="#ffffff">
        wrap = <a href="javascript:close();"> | </a>
        width = {$plugin.tx_browser_pi1.singlePopupMaxW}
        height = {$plugin.tx_browser_pi1.singlePopupMaxH}
        JSwindow = 1
        JSwindow.newWindow = 1
        JSwindow.expand = 17,20
      }
      noImage_stdWrap {
      }
    }

      // [Boolean] If first image is for the preview, it wouldn't display in the single view
    firstImageIsPreview       = 0
      // [Boolean] If first image is for the preview, but there is no further image, preview image will displayed in the single view
    forceFirstImageIsPreview  = 0

      // Displays the no-item-message, if SQL query will return an empty result
    noItemMessage = TEXT
    noItemMessage {
      data = LLL:EXT:browser/pi1/locallang.xml:phrase_norecord
      wrap = <p class="noItemMessage">|</p>
      wrap = <p style="padding:2em 0;">|</p>
    }
    master_templates {
    }
  }
}
  // plugin.tx_browser_pi1: displaySingle
