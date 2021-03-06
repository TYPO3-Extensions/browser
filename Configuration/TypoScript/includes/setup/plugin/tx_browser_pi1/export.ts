plugin.tx_browser_pi1 {
  export {
    csv {
      page = PAGE
      page {
        typeNum = {$plugin.tx_browser_pi1.typeNum.csvPageObj}
        config {
            // Get rid of the parsetime comment
          debug                 = 0
          disableAllHeaderCode  = 1
          disablePrefixComment  = 1
            // CSV has a one byte charset!
          metaCharset           = iso-8859-15
          xhtml_cleaning        = 0
          admPanel              = 0
          additionalHeaders     = {$plugin.tx_browser_pi1.typeNum.csvPageObj.additionalHeaders}
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
            // Get rid of the parsetime comment
          debug                 = 0
          disableAllHeaderCode  = 1
          disablePrefixComment  = 1
            // CSV has a one byte charset!
          metaCharset           = iso-8859-15
          xhtml_cleaning        = 0
          admPanel              = 0
          additionalHeaders     = {$plugin.tx_browser_pi1.typeNum.mapPageObj.additionalHeaders}
        }
      }
    }
    vCard < .csv
    vCard {
      page {
        typeNum = {$plugin.tx_browser_pi1.typeNum.vCardPageObj}
        config {
            // Get rid of the parsetime comment
          metaCharset           = UTF-8
          additionalHeaders     = {$plugin.tx_browser_pi1.typeNum.vCardPageObj.additionalHeaders}
        }
      }
    }
  }
}