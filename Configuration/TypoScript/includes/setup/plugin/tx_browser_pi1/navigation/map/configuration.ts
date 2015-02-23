plugin.tx_browser_pi1 {
  navigation {
      // 140703: empty statement: for proper comments only
    map {
    }
      // configuration
    map =
    map {
        // categories, centerCoordinates, 00Coordinates, route, zoomLevel
      configuration =
      configuration {
          // colours, offset, fields, form_input, orderBy
        categories =
        categories {
            // legend, point
          colours =
          colours {
              // 10, 20, 30 ... 120
            legend =
            legend {
                // Reddark
              10 = IMAGE
              10 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}reddark.png
              }
                // bluedark
              20 = IMAGE
              20 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}bluedark.png
              }
                // greendark
              30 = IMAGE
              30 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}greendark.png
              }
                // purple
              40 = IMAGE
              40 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}purple.png
              }
                // orange
              50 = IMAGE
              50 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}orange.png
              }
                // red
              60 = IMAGE
              60 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}red.png
              }
                // blue
              70 = IMAGE
              70 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}blue.png
              }
                // green
              80 = IMAGE
              80 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}green.png
              }
                // pink
              90 = IMAGE
              90 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}pink.png
              }
                // yellow
              100 = IMAGE
              100 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}yellow.png
              }
                // black
              110 = IMAGE
              110 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}black.png
              }
                // white
              120 = IMAGE
              120 {
                file = {$plugin.tx_browser_pi1.map.pathToLegend}white.png
              }
            }
              // 10, 20, 30 ... 120
            points =
            points {
                // Label for a clear view only and without any effect
              10 = reddark
              10 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/reddark.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}reddark.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              20 = bluedark
              20 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/bluedark.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}bluedark.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              30 = greendark
              30 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/greendark.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}greendark.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              40 = purple
              40 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/purple.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}purple.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              50 = orange
              50 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/orange.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}orange.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              60 = red
              60 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/red.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}red.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              70 = blue
              70 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/blue.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}blue.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              80 = green
              80 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/green.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}green.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              90 = pink
              90 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/pink.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}pink.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              100 = yellow
              100 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/yellow.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}yellow.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              110 = black
              110 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/black.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}black.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
                // Label for a clear view only and without any effect
              120 = white
              120 {
                  // [STRING] path to the icon. Default: EXT:browser/Resources/Public/JavaScript/Map/oxMap/icons/points/white.png
                pathToIcon = TEXT
                pathToIcon {
                  value = {$plugin.tx_browser_pi1.map.pathToPoints}white.png
                }
                  // [INTEGER] x-offset of the icon in pixel
                offsetX = 0
                  // [INTEGER] y-offset of the icon in pixel
                offsetY = 0
                  // [INTEGER] height of the icon in pixel
                height = 14
                  // [INTEGER] width of the icon in pixel
                width = 14
              }
            }
          }
            // inCaseOfOneCategory
          display =
          display {
            inCaseOfOneCategory = 1
          }
            // x, y: Offset for category icons, which will delivered by the database categories
          offset =
          offset {
              // [INTEGER] x-offset of the icon in pixel
            x = 0
              // [INTEGER] y-offset of the icon in pixel
            y = 0
          }
            // marker, path
          fields =
          fields {
              // category, categoryIcon, categoryOffsetX, categoryOffsetY. #47631
            marker =
            marker {
                // [STRING] table.field with the category labels. Example: tx_org_headquarterscat.title
              categoryTitle     = {$plugin.tx_browser_pi1.map.marker.field.category}
                // [STRING] table.field with the css class for the label tag of a marker category in the map form. Example: tx_route_marker_cat.formlabelcss
              categoryCssMarker = {$plugin.tx_browser_pi1.map.html.form.label.class.marker}
                // [STRING] table.field with the css class for the label tag of a path category in the map form. Example: tx_route_path_cat.formlabelcss
              categoryCssPath   = {$plugin.tx_browser_pi1.map.html.form.label.class.path}
                // [STRING] table.field with the category icons. Example: tx_org_headquarterscat.icons
              categoryIcon      = {$plugin.tx_browser_pi1.map.marker.field.categoryIcon}
                // [STRING] table.field with the x offset of the category icons. Example: tx_org_headquarterscat.icon_offset_x
              categoryOffsetX   = {$plugin.tx_browser_pi1.map.marker.field.categoryOffsetX}
                // [STRING] table.field with the y offset of the category icons. Example: tx_org_headquarterscat.icon_offset_y
              categoryOffsetY   = {$plugin.tx_browser_pi1.map.marker.field.categoryOffsetY}
                // [STRING] table.field with the description. Example: tx_org_headquarters.title
              description       = {$plugin.tx_browser_pi1.map.marker.field.description}
                // [STRING] table.field with the latitude labels. Example: tx_org_headquarters.mail_lat
              latitude          = {$plugin.tx_browser_pi1.map.marker.field.latitude}
                // [STRING] table.field with the longitude labels. Example: tx_org_headquarters.mail_lon
              longitude         = {$plugin.tx_browser_pi1.map.marker.field.longitude}
                // [STRING] table.field with the marker uid. Example: tx_org_headquarters.uid
              uid               = {$plugin.tx_browser_pi1.map.marker.field.linktoSingle}
            }
          }
            // [STRING/HTML] Draft for an input field of the category form
          form_input = TEXT
          form_input {
            value = <label for="###CAT_WO_SPC###"###CLASS###><input id="###CAT_WO_SPC###" class="{$plugin.tx_browser_pi1.map.html.form.input.class}" type="checkbox" name="###CAT_WO_SPC###" value="1" checked="checked" /> ###IMG### ###CAT###</label>
          }
            // [STRING]  SORT_REGULAR, SORT_NUMERIC, SORT_STRING or SORT_LOCALE_STRING
          orderBy = SORT_STRING
        }
          // mode, dynamicMarker
        centerCoordinates =
        centerCoordinates {
            // [STRING] auto (recommended) || ts. auto: center will calculated. ts: center will taken from TypoScript
          mode          = auto
            // [STRING] marker for the center in the HTML code. Example: oxMapConfigCenter
          dynamicMarker = oxMapConfigCenter
        }
          // dontHandle
        00Coordinates =
        00Coordinates {
            // [BOOLEAN] true (recommended): If a coordinate is 0,0, it won't handled and won't displayed
          dontHandle = {$plugin.tx_browser_pi1.map.controlling.dontHandle00Coordinates}
        }
          // markerMapper, tables
        route =
        route {
            // fields, tables
          markerMapper =
          markerMapper {
              // local, cat
            fields =
            fields {
                // obligate, optional
              local =
              local {
                  // lat, lon, title
                obligate =
                obligate {
                    // path, marker
                  lat =
                  lat {
                    path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.lat.path}
                    marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.lat.marker}
                  }
                    // path, marker
                  lon =
                  lon {
                    path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.lon.path}
                    marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.lon.marker}
                  }
                    // path, marker
                  routeLabel =
                  routeLabel {
                    path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.title.path}
                    marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.title.marker}
                  }
                }
                  // Extendable array: 0: title, 1: uid, ...
                optional =
                optional {
                    // title: path, marker
                  0 =
                  0 {
                    path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.title.path}
                    marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.local.title.marker}
                  }
                    // uid: path, marker
                  1 =
                  1 {
                    path    = uid
                    marker  = uid
                  }
                }
              }
                // [optional] Extendable array: title, icons, iconOffsetX, iconOffsetY,  ...
              cat =
              cat {
                  // uid: path, marker
                0 =
                0 {
                  path    = uid
                  marker  = uid
                }
                  // title: path, marker
                1 =
                1 {
                  path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.title.path}
                  marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.title.marker}
                }
                  // icons: path, marker
                2 =
                2 {
                  path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.icons.path}
                  marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.icons.marker}
                }
                  // iconOffsetX: path, marker
                3 =
                3 {
                  path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.iconOffsetX.path}
                  marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.iconOffsetX.marker}
                }
                  // iconOffsetY: path, marker
                4 =
                4 {
                  path    = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.iconOffsetY.path}
                  marker  = {$plugin.tx_browser_pi1.map.path.mapper.fields.cat.iconOffsetY.marker}
                }
              }
            }
              // [obligate] local, cat
            tables =
            tables {
                // path, marker
              local =
              local {
                path    = {$plugin.tx_browser_pi1.map.path.mapper.tables.local.path}
                marker  = {$plugin.tx_browser_pi1.map.path.mapper.tables.local.marker}
              }
                // path, marker
              cat =
              cat {
                path    = {$plugin.tx_browser_pi1.map.path.mapper.tables.cat.path}
                marker  = {$plugin.tx_browser_pi1.map.path.mapper.tables.cat.marker}
              }
            }
          }
            // path, pathCategory, marker, markerCategory
          tables =
          tables {
              // title, geodata, color, lineWidth, iconposition
            path =
            path {
              title         = {$plugin.tx_browser_pi1.map.path.table.path.title}
              geodata       = {$plugin.tx_browser_pi1.map.path.table.path.geodata}
              color         = {$plugin.tx_browser_pi1.map.path.table.path.color}
              lineWidth     = {$plugin.tx_browser_pi1.map.path.table.path.lineWidth}
            }
              // title
            pathCategory =
            pathCategory {
              title = {$plugin.tx_browser_pi1.map.path.table.pathCategory.title}
            }
              // title
            marker =
            marker {
              title = {$plugin.tx_browser_pi1.map.path.table.marker.title}
            }
              // title
            markerCategory =
            markerCategory {
              title = {$plugin.tx_browser_pi1.map.path.table.markerCategory.title}
            }
          }
        }
          // mode, dynamicMarker, max
        zoomLevel =
        zoomLevel {
            // [STRING] auto (recommended) || fixed. auto: zoom level will calculated. fixed: zoom level will taken from map.marker.snippets.jss.dynamic.oxMapConfigNumZoomLevels and map.marker.snippets.jss.dynamic.oxMapConfigStartLevel
          mode          = {$plugin.tx_browser_pi1.map.zoomLevel.mode}
            // [STRING] marker for the center in the HTML code. Example: oxMapConfigStartLevel
          dynamicMarker = oxMapConfigStartLevel
            // [INTEGER] Maximum zoom level
          max           = {$plugin.tx_browser_pi1.map.zoomLevel.max}
        }
      }
    }
  }
}