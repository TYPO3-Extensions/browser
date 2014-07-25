plugin.tx_browser_pi1 {
  export {
    csv {
      page = PAGE
      page {
        typeNum = {$plugin.tx_browser_pi1.typeNum.csvPageObj}
        config {
          disableAllHeaderCode  = 1
          disablePrefixComment  = 1
            // CSV has a one byte charset!
          metaCharset           = iso-8859-15
          xhtml_cleaning        = 0
          admPanel              = 0
          additionalHeaders     = Content-Type: text/csv | Content-Disposition: attachment; filename="export.csv"
        }
        10 = CONTENT
        10 {
          table=tt_content
          select{
              // use current language
            languageField = sys_language_uid
            andWhere {
              cObject = COA
              cObject {
                  // choose all Browser plugins...
                10 = TEXT
                10 {
                  value = list_type = 'browser_pi1'
                }
              }
            }
          }
        }
      }
    }
    map < .csv
    map {
      page {
        typeNum = {$plugin.tx_browser_pi1.typeNum.mapPageObj}
        config {
          disableAllHeaderCode  = 1
          disablePrefixComment  = 1
            // CSV has a one byte charset!
          metaCharset           = iso-8859-15
          xhtml_cleaning        = 0
          admPanel              = 0
          additionalHeaders     = Content-Type: text/plain
        }
      }
    }
  }
}