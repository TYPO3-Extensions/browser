plugin.tx_browser_pi1 {
  displaySingle {
    master_templates {
    }
      // tableFields
    master_templates =
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // image
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        imageText {
        }
          // 1
        imageText =
        imageText {
            // key, default (single view), page, url
          1 = COA
          1 {
              // image in case of: above ... and beside ...
            10 = CASE
            10 {
              key {
                field = {$plugin.tx_browser_pi1.templates.singleview.image.1.imageorient}
              }
              // don't handle
              default = TEXT
              default {
                //value = Please configure plugin.tx_browser_pi1.templates.singleview.image.1.imageorient
                value =
              }
              // above-center: div.column ul.block-grid image /ul /div
              0 = COA
              0 {
                10 = TEXT
                10 {
                  value = <div class="columns large-12 above-center">
                }
                  // image
                20 < plugin.tx_browser_pi1.displaySingle.master_templates.tableFields.image.1
                // above-right: /ul /div
                30 = TEXT
                30 {
                  value = </div>
                }
              }
              // above-right: div.column ul.block-grid image /ul /div
              1 < .0
              1 {
                10 {
                  value = <div class="columns large-6 large-offset-6 above-right">
                }
              }
              // above-left: div.column ul.block-grid
              2 < .0
              2 {
                10 {
                  value = <div class="columns large-6 above-left">
                }
              }
              // intext-right: div.columns ul.block-grid
              17 < .0
              17 {
                10 {
                  value = <div class="columns large-4 large-push-8 intext-right">
                }
              }
              // intext-left: div.columns ul.block-grid
              18 < .0
              18 {
                10 {
                  value = <div class="columns large-4 intext-left">
                }
              }
              // intext-right-nowrap: div.columns ul.block-grid
              25 < .0
              25 {
                10 {
                  value = <div class="columns large-4 large-push-8 intext-right-nowrap">
                }
              }
              // intext-left-nowrap: div.columns ul.block-grid
              26 < .0
              26 {
                10 {
                  value = <div class="columns large-4 intext-left-nowrap">
                }
              }
            }
              // text
            20 = CASE
            20 {
              key {
                field = {$plugin.tx_browser_pi1.templates.singleview.image.1.imageorient}
              }
              // don't handle
              default = TEXT
              default {
                value =
              }
              // above-center: div.columns
              0 = COA
              0 {
                  // socialmedia_bookmarks
                10 = TEXT
                10 {
                  value = ###SOCIALMEDIA_BOOKMARKS###
                  wrap = <div class="show-for-large-up socialbookmarks">|</div>
                }
                  // header
                20 = TEXT
                20 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.text.1.header}
                  wrap  = <{$plugin.tx_browser_pi1.templates.singleview.text.1.headertag}>|</{$plugin.tx_browser_pi1.templates.singleview.text.1.headertag}>
                }
                  // bodytext
                30 = TEXT
                30 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.text.1.bodytext}
                  required = 1
                  stdWrap {
                    parseFunc < lib.parseFunc_RTE
                  }
                }
                wrap = <div class="columns large-12">|</div>
              }
              // above-right: div.columns
              1 < .0
              1 {
                wrap = <div class="columns large-12">|</div>
              }
              // above-left: div.columns
              2 < .0
              2 {
                wrap = <div class="columns large-12">|</div>
              }
              // below-center: div.columns
              8 < .0
              8 {
                wrap = <div class="columns large-12">|</div>
              }
              // below-right: div.columns
              9 < .0
              9 {
                wrap = <div class="columns large-12">|</div>
              }
              // below-left: div.columns
              10 < .0
              10 {
                wrap = <div class="columns large-12">|</div>
              }
              // intext-right: div.columns
              17 < .0
              17 {
                wrap = <div class="columns large-8 large-pull-4">|</div>
              }
              // intext-left: div.columns
              18 < .0
              18 {
                wrap = <div class="columns large-8">|</div>
              }
              // intext-right-nowrap: div.columns
              25 < .0
              25 {
                wrap = <div class="columns large-8 large-pull-4">|</div>
              }
              // intext-left-nowrap: div.columns
              26 < .0
              26 {
                wrap = <div class="columns large-8">|</div>
              }
            }
              // image in case of: below ...
            30 = CASE
            30 {
              key {
                field = {$plugin.tx_browser_pi1.templates.singleview.image.1.imageorient}
              }
              // don't handle
              default = TEXT
              default {
                value =
              }
              // below-center: div.column ul.block-grid
              8 = COA
              8 {
                10 = TEXT
                10 {
                  value = <div class="columns large-12 below-center">
                }
                  // image
                20 < plugin.tx_browser_pi1.displaySingle.master_templates.tableFields.image.1
                // above-right: /ul /div
                30 = TEXT
                30 {
                  value = </div>
                }
              }
              // below-right: div.column ul.block-grid
              9 < .8
              9 {
                10 {
                  value = <div class="columns large-6 large-offset-6 below-right">
                }
              }
              // below-left: div.column ul.block-grid
              10 < .8
              10 {
                10 {
                  value = <div class="columns large-6 below-left">
                }
              }
            }
          }
        }
      }
    }
  }
}