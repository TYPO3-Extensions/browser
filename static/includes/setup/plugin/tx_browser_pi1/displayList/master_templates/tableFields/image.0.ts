plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // image
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        image {
        }
          // 0
        image =
        image {
            // 10: with image of the record. 20: with default image.
          0 = COA
          0 {
              // key, default (single view), page, url
            10 = CASE
            10 {
              if {
                isTrue {
                  field = {$plugin.tx_browser_pi1.templates.listview.image.0.file}
                  listNum = {$plugin.tx_browser_pi1.templates.listview.image.0.listNum}
                }
              }
              key {
                field = {$plugin.tx_browser_pi1.templates.listview.url.0.key}
              }
                // single view. 10: teaser_title, 20: title
              default = IMAGE
              default {
                file {
                  import = {$plugin.tx_browser_pi1.templates.listview.image.0.path}
                  import {
                    field   = {$plugin.tx_browser_pi1.templates.listview.image.0.file}
                    listNum = {$plugin.tx_browser_pi1.templates.listview.image.0.listNum}
                  }
                  height = {$plugin.tx_browser_pi1.templates.listview.image.0.height}
                  width = {$plugin.tx_browser_pi1.templates.listview.image.0.width}
                }
                altText = TEXT
                altText {
                  field = {$plugin.tx_browser_pi1.templates.listview.image.0.seo} // {$plugin.tx_browser_pi1.templates.listview.header.0.list} // {$plugin.tx_browser_pi1.templates.listview.header.0.single}
                  stdWrap {
                    stripHtml = 1
                    htmlSpecialChars = 1
                  }
                  listNum = {$plugin.tx_browser_pi1.templates.listview.image.0.listNum}
                  listNum {
                    splitChar = 10
                  }
                }
                titleText < .altText
                imageLinkWrap = 1
                imageLinkWrap {
                  enable = 1
                  typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
                }
                layoutKey = {$plugin.tx_browser_pi1.templates.listview.image.0.layoutkey}
                layout {
                  data {
                    element = <img src="###SRC###" ###SOURCECOLLECTION### ###PARAMS### ###ALTPARAMS### ###SELFCLOSINGTAGSLASH###>
                    source.noTrimWrap = | data-###DATAKEY###="###SRC###"|
                  }
                  default {
                   element = <img src="###SRC###" width="###WIDTH###" height="###HEIGHT###" ###PARAMS### ###ALTPARAMS### ###BORDER### ###SELFCLOSINGTAGSLASH###>
                   source =
                  }
                  picture {
                    element = <picture>###SOURCECOLLECTION###<img src="###SRC###" ###PARAMS### ###ALTPARAMS### ###SELFCLOSINGTAGSLASH###></picture>
                    source = <source src="###SRC###" media="###MEDIAQUERY###" ###SELFCLOSINGTAGSLASH###>
                  }
                  srcset {
                    element = <img src="###SRC###" srcset="###SOURCECOLLECTION###" ###PARAMS### ###ALTPARAMS### ###SELFCLOSINGTAGSLASH###>
                    source = |*|###SRC### ###SRCSETCANDIDATE###,|*|###SRC### ###SRCSETCANDIDATE###
                  }
                }
                sourceCollection {
                  small {
                    width = 200
                    srcsetCandidate = 800w
                    mediaQuery = (min-device-width: 800px)
                    dataKey = small
                  }
                  smallHires {
                    if.directReturn = 1
                    width = 300
                    pixelDensity = 2
                    srcsetCandidate = 800w 2x
                    mediaQuery = (min-device-width: 800px) AND (foobar)
                    dataKey = smallHires
                    pictureFoo = bar
                  }
                }
              }
                // without any link (record is available only in list views)
              notype < .default
              notype {
                imageLinkWrap >
              }
                // link to an internal page. 10: teaser_title, 20: title
              page < .default
              page {
                imageLinkWrap {
                  typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
                }
              }
                // link to an external website. 10: teaser_title, 20: title
              url < .page
              url {
                imageLinkWrap {
                  typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.url
                }
              }
                // DEPRECATED! Use page!
              calpage < .page
                // DEPRECATED! Use url!
              calurl  < .url
                // DEPRECATED! Use default (record)!
              news < .default
                // DEPRECATED! Use page!
              newspage < .page
                // DEPRECATED! Use url!
              newsurl  < .url
            }
              // key, default (single view), page, url
            20 < .10
            20 {
              if {
                isTrue >
                isFalse {
                  field = {$plugin.tx_browser_pi1.templates.listview.image.0.file}
                }
              }
              default {
                file = {$plugin.tx_browser_pi1.templates.listview.image.0.default}
              }
              notype {
                file = {$plugin.tx_browser_pi1.templates.listview.image.0.default}
              }
              page {
                file = {$plugin.tx_browser_pi1.templates.listview.image.0.default}
              }
              url {
                file = {$plugin.tx_browser_pi1.templates.listview.image.0.default}
              }
                // DEPRECATED! Use page!
              calpage < .page
                // DEPRECATED! Use url!
              calurl  < .url
                // DEPRECATED! Use default (record)!
              news < .default
                // DEPRECATED! Use page!
              newspage < .page
                // DEPRECATED! Use url!
              newsurl  < .url
            }
          }
        }
      }
    }
  }
}