plugin.tx_browser_pi1 {
  autoconfig {
      // [BOOLEAN] Build relations automatically
    relations = {$plugin.tx_browser_pi1.autoSQLrelConfig}
    relations {
        // [BOOLEAN] Don't use relations from foreign tables back to the local table
      oneWayOnly                = {$plugin.tx_browser_pi1.autoSQLrelOnlyOneway}
        //  [BOOLEAN] Use simple relations from a local table field to a foreign table. If the frontend plugin is configured, the plugin will overwrite this value!
      simpleRelations           = 1
      simpleRelations {
          //  [BOOLEAN] Don't use relations from a table to itself
        selfReference           = {$plugin.tx_browser_pi1.autoSQLrelSelfReference}
      }
        // [BOOLEAN] Use MM relations from the local table over an mm-table to a foreign table.  If the frontend plugin is configured, the plugin will overwrite this value!
      mmRelations               = 1
      TCAconfig {
          // [BOOLEAN] Respect the TCA type for relation building
        type = 1
          // [CSV-STRING] Comma separated list of field types in the TCA, which are allowed for looking up relations.
        type {
          csvValue = select
        }
      }
        // [CSV-STRING, syntax: table.field] Comma separated list of fields in the TCA, which will ignored for looking up relations.
      #csvDontUseFields            = tt_news.l18n_parent, tt_news.l18n_diffsource, tt_news_cat.parent_category
      csvDontUseFields          =
        // [BOOLEAN] Use LEFT JOIN statements, if left_join is TRUE. 1 is recommended.
      left_join                 = 1
    }
    autoDiscover {
      dontDiscoverFields {
          // [CSV-STRING] Comma separated list of fields, which shouldn't checked in the TCA
        csvValue = uid, pid
      }
      items {
          // [BOOLEAN] Detect titles automatically
        title = {$plugin.tx_browser_pi1.autoDiscoverTitle}
        title {
          localTableOnly  = 1
          oneValueOnly    = 1
          setUploadFolder = 0
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 0
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for the title
            csvValue = title
          }
        }
          // [BOOLEAN] Detect images automatically
        image = {$plugin.tx_browser_pi1.autoDiscoverImage}
        image {
          localTableOnly  = 0
          oneValueOnly    = 1
          setUploadFolder = 1
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 1
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for the image
            csvValue = image, images
          }
          TCAconfig {
              // [BOOLEAN] Respect the TCA type for the auto-detection
            type = 1
            type {
                // [CSV-STRING] Respect the TCA type values for the auto-detection
              csvValue = group
            }
              // [BOOLEAN] Respect the TCA internal type for the auto-detection
            internal_type = 1
            internal_type {
              csvValue = file
            }
              // [BOOLEAN] Respect the TCA allowed value for the auto-detection
            allowed = 1
            allowed {
                // [STRING] Respect the TCA allowed value for the auto-detection
              value = imagefile_ext
            }
          }
        }
          // [BOOLEAN] Detect the caption of an image automatically
        imageCaption = {$plugin.tx_browser_pi1.autoDiscoverImage}
        imageCaption {
          localTableOnly  = 0
          oneValueOnly    = 1
          setUploadFolder = 0
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 0
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for the image caption
            csvValue = caption, imagecaption
          }
          TCAconfig {
              // [BOOLEAN] Respect the TCA type for the auto-detection
            type = 1
            type {
                // [CSV-STRING] Respect the TCA type values for the auto-detection
              csvValue = text
            }
          }
        }
          // [BOOLEAN] Detect the HTML-a-tag property alt of an image automatically
        imageAltText = {$plugin.tx_browser_pi1.autoDiscoverImage}
        imageAltText {
          localTableOnly  = 0
          oneValueOnly    = 1
          setUploadFolder = 0
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 1
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for the HTML-a-tag property alt of the image
            csvValue = imagealttext, imageseo
          }
          TCAconfig {
              // [BOOLEAN] Respect the TCA type for the auto-detection
            type = 1
            type {
                // [CSV-STRING] Respect the TCA type values for the auto-detection
              csvValue = text
            }
          }
        }
          // [BOOLEAN] Detect the HTML-a-tag property title of an image automatically
        imageTitleText = {$plugin.tx_browser_pi1.autoDiscoverImage}
        imageTitleText {
          localTableOnly  = 0
          oneValueOnly    = 1
          setUploadFolder = 0
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 1
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for the HTML-a-tag property title of the image
            csvValue = imagetitletext, imageseo
          }
          TCAconfig {
              // [BOOLEAN] Respect the TCA type for the auto-detection
            type = 1
            type {
                // [CSV-STRING] Respect the TCA type values for the auto-detection
              csvValue = text
            }
          }
        }
          // [BOOLEAN] Detect documents automatically
        document = {$plugin.tx_browser_pi1.autoDiscoverDocument}
        document {
          localTableOnly  = 0
          oneValueOnly    = 0
            // [BOOLEAN] Take the TCA path to the upload folder
          setUploadFolder = 1
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 1
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel        = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for documents
            csvValue = media, document, documents
          }
          TCAconfig {
              // [BOOLEAN] Respect the TCA type for the auto-detection
            type = 1
            type {
                // [CSV-STRING] Respect the TCA type values for the auto-detection
              csvValue = group
            }
            internal_type = 1
            internal_type {
                // [CSV-STRING] Respect the TCA internal type values for the auto-detection
              csvValue = file
            }
          }
        }
          // [BOOLEAN] Detect time stamps automatically
        timestamp = {$plugin.tx_browser_pi1.autoDiscoverTimestamp}
        timestamp {
          oneValueOnly   = 0
            // [BOOLEAN] 1: Swords will not be wrapped with HTML tags in search results, 0: Swords will be wrapped
          dontColorSwords = 0
            // [BOOLEAN] Respect TCA labels for the auto-detection
          TCAlabel       = 1
          TCAlabel {
              // [CSV-STRING] Detect fields with this labels in the TCA for time stamps
            csvValue = datetime, datetimeend, datetimestart, starttime, endtime,
          }
          TCAconfig {
              // [STRING] Respect the value of TCA eval for the auto-detection
            eval = datetime
          }
        }
      }
    }
      // Marker
    marker {
      typoScript {
          // Replace a marker with its value. I. e. ###TT_NEWS.TITLE### becomes "Title of the news"
        replacement = 1
      }
    }
    consolidation {
      sql {
        rows {
            // [BOOLEAN] Try to change non unique rows to unique rows
          unique = 1
          unique {
              // [BOOLEAN] Remove foreignUid fields from the rows
            rm_foreignUid_fields = 1
              // [BOOLEAN] Remove values, if they don't have an unique uid, but they are the same
            rm_nonUnique_values = 1
          }
        }
      }
    }
  }
}