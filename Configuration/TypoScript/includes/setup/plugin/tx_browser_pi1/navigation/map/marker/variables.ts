plugin.tx_browser_pi1 {
  navigation {
    map {
        // variables
      marker =
      marker {
          // dynamic, system: Values, which will set while runtime.
        variables =
        variables {
            // css_mapHeight, css_mapWidth
          dynamic =
          dynamic {
            css_mapHeight = TEXT
            css_mapHeight {
                // [PIXEL] Default: 400px
              value = {$plugin.tx_browser_pi1.map.design.height}px;
            }
            css_mapWidth = TEXT
            css_mapWidth {
                // [PIXEL] Default: 600px
              value = {$plugin.tx_browser_pi1.map.design.width}px;
            }
          }
            // category, categoryIconLegend, categoryIconMap, categoryOffsetX, categoryOffsetY, description, latitude, longitude, number, url
          system =
          system {
              // [STRING] category label in the filter
            category = TEXT
            category {
                // [STRING] table.field. Example: tx_org_headquarterscat.title
              field = {$plugin.tx_browser_pi1.map.marker.field.category}
            }
              // Returns the icon as an HTML image tag
            categoryIconLegend = IMAGE
            categoryIconLegend {
              file {
                  // [STRING] Path to the directory with the category icons (with ending slash). Example: uploads/tx_org/ Default: uploads/media/
                import = {$plugin.tx_browser_pi1.map.design.path.categoryIcon}
                import {
                    // [STRING] table.field. Example: tx_org_headquarterscat.icons
                  field = {$plugin.tx_browser_pi1.map.marker.field.categoryIcon}
                    // [INTEGER] Position of the icon in the list of icons in the database. 1st position: 0. 2nd position: 1. 3rd position: 2. ...
                  listNum = {$plugin.tx_browser_pi1.map.icon.listNum.categoryIconLegend}
                }
              }
            }
              // [STRING] path to the category icon for the map
            categoryIconMap = COA
            categoryIconMap {
                // Directory
              10 = TEXT
              10 {
                  // [STRING] Path to the directory with the category icons (with ending slash). Example: uploads/tx_org/ Default: uploads/media/
                value = {$plugin.tx_browser_pi1.map.design.path.categoryIcon}
              }
                // File
              20 = COA
              20 {
                  // list view
                10 = TEXT
                10 {
                    // if.isFalse markerUid or routeUid
                  if =
                  if {
                    isTrue {
                      cObject = COA
                      cObject {
                          // markerUid
                        10 = TEXT
                        10 {
                          // #i0170, 150430, dwildt, 1-
                          //data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.marker}
                          // #i0170, 150430, dwildt, 4+
                          stdWrap {
                            data    = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.marker}
                            intval  = 1
                          }
                        }
                          // routeUid
                        20 = TEXT
                        20 {
                          // #i0170, 150430, dwildt, 1-
                          //data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.path}
                          // #i0170, 150430, dwildt, 4+
                          stdWrap {
                            data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.path}
                            intval  = 1
                          }
                        }
                      }
                    }
                    negate = 1
                  }
                    // [STRING] table.field. Example: tx_org_headquarterscat.icons
                  field = {$plugin.tx_browser_pi1.map.marker.field.categoryIcon}
                    // [INTEGER] Position of the icon in the list of icons in the database. 1st position: 0. 2nd position: 1. 3rd position: 2. ...
                  stdWrap.listNum = {$plugin.tx_browser_pi1.map.icon.listNum.categoryIconMap}
                }
                  // single view
                20 = TEXT
                20 {
                    // if.isTrue markerUid or routeUid
                  if =
                  if {
                    isTrue {
                      cObject = COA
                      cObject {
                          // markerUid
                        10 = TEXT
                        10 {
                          // #i0170, 150430, dwildt, 1-
                          //data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.marker}
                          // #i0170, 150430, dwildt, 4+
                          stdWrap {
                            data    = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.marker}
                            intval  = 1
                          }
                        }
                          // routeUid
                        20 = TEXT
                        20 {
                          // #i0170, 150430, dwildt, 1-
                          //data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.path}
                          // #i0170, 150430, dwildt, 4+
                          stdWrap {
                            data = GP:tx_browser_pi1|{$plugin.tx_browser_pi1.map.aliases.showUid.path}
                            intval  = 1
                          }
                        }
                      }
                    }
                  }
                    // [STRING] table.field. Example: tx_org_headquarterscat.icons
                  field = {$plugin.tx_browser_pi1.map.marker.field.categoryIcon}
                    // [INTEGER] Position of the icon in the list of icons in the database. 1st position: 0. 2nd position: 1. 3rd position: 2. ...
                  stdWrap.listNum = {$plugin.tx_browser_pi1.map.icon.listNum.categoryIconMapSingle}
                }
              }
            }
              // [INTEGER] x-offset of the current icon in pixel
            categoryOffsetX = TEXT
            categoryOffsetX {
                // [STRING] table.field. Example: tx_org_headquarterscat.icon_offset_x
              field = {$plugin.tx_browser_pi1.map.marker.field.categoryOffsetX}
            }
              // [INTEGER] y-offset of the current icon in pixel
            categoryOffsetY = TEXT
            categoryOffsetY {
                // [STRING] table.field. Example: tx_org_headquarterscat.icon_offset_y
              field = {$plugin.tx_browser_pi1.map.marker.field.categoryOffsetY}
            }
              // popup: close icon, image, header, text
            description = COA
            description {
                // close icon
              10 = TEXT
              10 {
                value = <div class="olPopupCloseBox" style="width: 17px; height: 17px; position: absolute; right: 13px; top: 45px; z-index: 1;" id="featurePopup_close"></div>
              }
              10 >
              // image linked to record (default), notype, page, url
              20 < plugin.tx_browser_pi1.displayList.master_templates.tableFields.image.0
              20 {
                default {
                  file {
                    import {
                      listNum = {$plugin.tx_browser_pi1.map.popup.image.listNum}
                    }
                    height = {$plugin.tx_browser_pi1.map.popup.image.height}
                    width = {$plugin.tx_browser_pi1.map.popup.image.width}
                  }
                  wrap = <div style="float:left;padding:0 1em 1em 0;">|</div>
                }
                  // without any link (record is available only in list views)
                notype < .default
                notype {
                  imageLinkWrap >
                }
                page < .default
                page {
                  imageLinkWrap {
                    typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
                  }
                }
                  // link to an external website
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
                // header
              30 < plugin.tx_browser_pi1.displayList.master_templates.tableFields.header.0
              30 {
                default {
                  wrap = <div class="mapPopupHeader mapPopupHeaderDefault">|</div>
                }
                page {
                  wrap = <div class="mapPopupHeader mapPopupHeaderPage">|</div>
                }
                url {
                  wrap = <div class="mapPopupHeader mapPopupHeaderUrl">|</div>
                }
              }
                // text
              40 < plugin.tx_browser_pi1.displayList.master_templates.tableFields.text.0
              40 {
                default {
                  10 {
                    stdWrap {
                      crop = {$plugin.tx_browser_pi1.map.popup.text.crop}
                    }
                  }
                  20 {
                    if {
                      value   = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
                      equals  = 'click'
                    }
                  }
                  wrap = <div class="mapPopupText mapPopupTextDefault">|</div>
                }
                page {
                  10 {
                    stdWrap {
                      crop = {$plugin.tx_browser_pi1.map.popup.text.crop}
                    }
                  }
                  20 {
                    if {
                      value   = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
                      equals  = 'click'
                    }
                  }
                  wrap = <div class="mapPopupText mapPopupTextDefault">|</div>
                }
                url {
                  10 {
                    stdWrap {
                      crop = {$plugin.tx_browser_pi1.map.popup.text.crop}
                    }
                  }
                  20 {
                    if {
                      value   = '{$plugin.tx_browser_pi1.map.openlayers.popup.behaviour}'
                      equals  = 'click'
                    }
                  }
                  wrap = <div class="mapPopupText mapPopupTextDefault">|</div>
                }
              }
            }
              // [NUMBER] latitude
            latitude = TEXT
            latitude {
                // [STRING] table.field. Example: tx_org_headquarters.mail_lat
              field = {$plugin.tx_browser_pi1.map.marker.field.latitude}
            }
              // [NUMBER] longitude
            longitude = TEXT
            longitude {
                // [STRING] table.field. Example: tx_org_headquarters.mail_lon
              field = {$plugin.tx_browser_pi1.map.marker.field.longitude}
            }
              // [STRING] number of the point (has an effect ...
            number = TEXT
            number {
                // [STRING] table.field. Example: tx_org_headquarters.title
              field = {$plugin.tx_browser_pi1.map.marker.field.number}
            }
              // [STRING] URL of the point (has an effect in hoover mode only!)
              // key, default (single view), page, url
            url = CASE
            url {
              key {
                field = {$plugin.tx_browser_pi1.templates.listview.url.0.key}
              }
                // single view
              default = TEXT
              default {
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.default
                typolink {
                  returnLast = url
                }
              }
              page = TEXT
              page {
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.page
                typolink {
                  returnLast = url
                }
              }
              url = TEXT
              url {
                typolink < plugin.tx_browser_pi1.displayList.master_templates.tableFields.typolinks.0.url
                typolink {
                  returnLast = url
                }
              }
            }
          }
        }
      }
    }
  }
}