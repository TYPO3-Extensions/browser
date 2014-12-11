plugin.tx_browser_pi1 {
  template {
    extensions {
        // empty line for enable commenting only
      browser {
      }
        // foundationMain
      browser {
        foundationMain01 {
          name      = Foundation - Main 1: Title // Searchform / Map, Browser, Order, Content
          file      = EXT:browser/res/html/foundation/main_01.html
          csvViews  = all
        }
        foundationMain02 {
          name      = Foundation - Main 2: Searchform / Title, Browser, Content
          file      = EXT:browser/res/html/foundation/main_02.html
          csvViews  = all
        }
        foundationTable01 {
          name      = Foundation - Table 1: Title // Searchform / Map, Browser, Table
          file      = EXT:browser/res/html/foundation/table_01.html
          csvViews  = all
        }
        foundationTable02 {
          name      = Foundation - Table 2: Searchform / Title, Browser, Table
          file      = EXT:browser/res/html/foundation/table_02.html
          csvViews  = all
        }
      }
    }
  }
}