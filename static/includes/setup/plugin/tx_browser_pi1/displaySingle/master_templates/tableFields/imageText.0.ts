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
          // 0
        imageText =
        imageText {
            // key, default (single view), page, url
          0 = COA
          0 {
              // image in case of: above ... and beside ...
            10 = CASE
            10 {
              key {
                field = {$plugin.tx_browser_pi1.templates.singleview.image.0.imageorient}
              }
              // don't handle
              default = TEXT
              default {
                value = Please configure plugin.tx_browser_pi1.templates.singleview.image.0.imageorient
              }
              // above-center: div.column ul.block-grid image /ul /div
              0 = COA
              0 {
                10 = TEXT
                10 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.image.0.imagecols}
                  wrap = <div class="columns large-12 above-center"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
                  // image
                20 < plugin.tx_browser_pi1.displaySingle.master_templates.tableFields.image.0
                20 {
                  wrap >
                }
                // above-right: /ul /div
                30 = TEXT
                30 {
                  value = </ul></div>
                }
              }
              // above-right: div.column ul.block-grid image /ul /div
              1 < .0
              1 {
                10 {
                  wrap = <div class="columns large-6 large-offset-6 above-right"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // above-left: div.column ul.block-grid
              2 < .0
              2 {
                10 {
                  wrap = <div class="columns large-6 above-left"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // intext-right: div.columns ul.block-grid
              17 < .0
              17 {
                10 {
                  wrap = <div class="columns large-4 large-push-8 intext-right"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // intext-left: div.columns ul.block-grid
              18 < .0
              18 {
                10 {
                  wrap = <div class="columns large-4 intext-left"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // intext-right-nowrap: div.columns ul.block-grid
              25 < .0
              25 {
                10 {
                  wrap = <div class="columns large-4 large-push-8 intext-right-nowrap"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // intext-left-nowrap: div.columns ul.block-grid
              26 < .0
              26 {
                10 {
                  wrap = <div class="columns large-4 intext-left-nowrap"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
            }
              // text
            20 = CASE
            20 {
              key {
                field = {$plugin.tx_browser_pi1.templates.singleview.image.0.imageorient}
              }
              // don't handle
              default = TEXT
              default {
                value =
              }
              // above-center: div.columns
              0 = COA
              0 {
                wrap = <div class="columns large-12">|</div>
                  // Buy now
                10 = TEXT
                10 {
                  value = plugin.tx_browser_pi1.templates.singleview.image.0.imageorient
                }
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
                field = {$plugin.tx_browser_pi1.templates.singleview.image.0.imageorient}
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
                  field = {$plugin.tx_browser_pi1.templates.singleview.image.0.imagecols}
                  wrap = <div class="columns large-12 below-center"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
                  // image
                20 < plugin.tx_browser_pi1.displaySingle.master_templates.tableFields.image.0
                20 {
                  wrap >
                }
                // above-right: /ul /div
                30 = TEXT
                30 {
                  value = </ul></div>
                }
              }
              // below-right: div.column ul.block-grid
              9 < .8
              9 {
                10 {
                  wrap = <div class="columns large-6 large-offset-6 below-right"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
              // below-left: div.column ul.block-grid
              10 < .8
              10 {
                10 {
                  wrap = <div class="columns large-6 below-left"><ul class="clearing-thumbs small-block-grid-|" data-clearing>
                }
              }
            }
          }
        }
      }
    }
  }
}