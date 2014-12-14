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
        image {
        }
          // 1
        image =
        image {
            // key, default (single view), page, url
          1 = COA
          1 {
            wrap = <ul class="small-block-grid-3 columns small-12">|</ul>
              // image, caption
            10 = TEXT
            10 {
                // if is true: file
              if =
              if {
                isTrue {
                  field = {$plugin.tx_browser_pi1.templates.singleview.image.1.file}
                }
              }
              field = {$plugin.tx_browser_pi1.templates.singleview.image.1.file}
                // load register; image, caption
              split =
              split {
                token = ,
                cObjNum = 1
                  // load register; image, caption
                1 =
                1 {
                    // load register; image, caption
                  10 = COA
                  10 {
                    10 = LOAD_REGISTER
                    10 {
                      Counter {
                        cObject = TEXT
                        cObject {
                          data = register:Counter
                          wrap = |+1
                        }
                      }
                      prioriCalc = intval
                    }
                      // image, caption
                    20 = COA
                    20 {
                      wrap = <li class="tx-browser-pi1"><figure class="tx-browser-pi1">|</figure></li>
                        // image
                      10 = IMAGE
                      10 {
                        file {
                            // current image || default image
                          import =
                          import {
                            wrap = {$plugin.tx_browser_pi1.templates.singleview.image.1.path}|
                            current = 1
                          }
                          height = 400
                          height {
                            override {
                              field = {$plugin.tx_browser_pi1.templates.singleview.image.0.height}
                            }
                          }
                          width = {$styles.content.imgtext.maxW}
                          width {
                            override {
                              field = {$plugin.tx_browser_pi1.templates.singleview.image.0.width}
                            }
                          }
                        }
                        imageLinkWrap = 1
                        imageLinkWrap {
                           enable = 1
                           directImageLink = 1
                        }
                        altText = TEXT
                        altText {
                          field = {$plugin.tx_browser_pi1.templates.singleview.image.1.seo}
                          split {
                            token {
                              char = 10
                            }
                            returnKey {
                              stdWrap {
                                cObject = TEXT
                                cObject {
                                  current = 1
                                  setCurrent {
                                    data = register:Counter
                                    wrap = |-1
                                  }
                                  prioriCalc = intval
                                }
                              }
                            }
                          }
                          stdWrap {
                            stripHtml = 1
                            htmlSpecialChars = 1
                          }
                        }
                        titleText < .altText
                          // data-caption=
                        params =
                        params {
                          stdWrap {
                            cObject = COA
                            cObject {
                                // if is true: caption
                              if =
                              if {
                                isTrue {
                                  field = {$plugin.tx_browser_pi1.templates.singleview.image.1.caption}
                                }
                              }
                              wrap = data-caption="|"
                              10 = TEXT
                              10 {
                                field = {$plugin.tx_browser_pi1.templates.singleview.image.1.caption}
                                //field = tx_route_path.imagecaption
                                split {
                                  token {
                                    char = 10
                                  }
                                  returnKey {
                                    stdWrap {
                                      cObject = TEXT
                                      cObject {
                                        current = 1
                                        setCurrent {
                                          data = register:Counter
                                          wrap = |-1
                                        }
                                        prioriCalc = intval
                                      }
                                    }
                                  }
                                }
                                stdWrap {
                                  stripHtml = 1
                                  htmlSpecialChars = 1
                                }
                              }
                            }
                          }
                        }
                        layoutKey = {$plugin.tx_browser_pi1.templates.singleview.image.1.layoutKey}
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
                        // caption
                      20 = COA
                      20 {
                        wrap = <figcaption>|</figcaption>
                        10 = TEXT
                        10 {
                            // if is true: caption
                          if =
                          if {
                            isTrue {
                              field = {$plugin.tx_browser_pi1.templates.singleview.image.1.caption}
                            }
                          }
                          field = {$plugin.tx_browser_pi1.templates.singleview.image.1.caption}
                          split {
                            token {
                              char = 10
                            }
                            returnKey {
                              stdWrap {
                                cObject = TEXT
                                cObject {
                                  current = 1
                                  setCurrent {
                                    data = register:Counter
                                    wrap = |-1
                                  }
                                  prioriCalc = intval
                                }
                              }
                            }
                          }
                          stdWrap {
                            stripHtml = 1
                            htmlSpecialChars = 1
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}