plugin.tx_browser_pi1 {
  rss {
    page = PAGE
    page {
      typeNum = 0
      config {
        disableAllHeaderCode  = 1
        disablePrefixComment  = 1
        xhtml_cleaning        = 0
        admPanel              = 0
        additionalHeaders     = Content-type:text/xml
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
}