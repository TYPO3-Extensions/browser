plugin.tx_browser_pi1 {
  views {
    single {
      43746 {
        tt_content {
          image = FILES
          image {
            references {
              table = tt_content
              uid {
                field = tt_content.uid
              }
              uid >
              XXXuid = 120
              uid = ###TT_CONTENT.UID###
              fieldName = image
            }
            begin = 0
            maxItems = 99
            renderObj = IMAGE
            renderObj {
              file {
                import {
                  data = file:current:uid
                }
                treatIdAsReference = 1
                height = {$plugin.tx_browser_pi1.templates.singleview.image.0.heightDefault}
                height {
                  override {
                    field = {$plugin.tx_browser_pi1.templates.singleview.image.0.height}
                  }
                }
                width = {$plugin.tx_browser_pi1.templates.singleview.image.0.widthDefault}
                width {
                  override {
                    field = {$plugin.tx_browser_pi1.templates.singleview.image.0.width}
                  }
                }
              }
              altText {
                data = file:current:title
                stdWrap {
                  stripHtml = 1
                  htmlSpecialChars = 1
                }
              }
              titleText < .altText
              wrap = <div class="slide">|</div>
            }
            stdWrap.wrap = <div class="carousel">|</div>
          }
          list_type = COA
          list_type {
              // If it is default content
            10 = TEXT
            10 {
              if {
                isFalse {
                  field = tt_content.list_type
                }
              }
              value = No plugin
              lang {
                de = Kein Plugin
                en = No plugin
              }
            }
              // If it is a plugin
            20 = TEXT
            20 {
              if {
                isTrue {
                  field = tt_content.list_type
                }
              }
              field = tt_content.list_type
            }
          }
          tstamp = TEXT
          tstamp {
            field     = tt_content.tstamp
            strftime  = %A, %d. %B %Y, %H:%M Uhr
          }
        }
      }
    }
  }
}