plugin.tx_browser_pi1 {
  template {
    extensions {
        // empty line for enable commenting only
      browser {
      }
        // foundationMain
      browser {
        foundationMain01 {
          name      = Foundation with own HTML snippet - 1: 1st row: Title. 2nd row:  Searchform | Map, Browser, Order, Content
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_01.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_01.gif
          csvViews  = all
        }
        foundationMain02 {
          name      = Foundation with own HTML snippet - 2: Searchform | Title, Browser, Content
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_02.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_02.gif
          csvViews  = all
        }
        foundationMain03 {
          name      = Foundation with own HTML snippet - 3: Title, Searchform, Map, Browser, Content
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_03.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_03.gif
          csvViews  = all
        }
        foundationTable01 {
          name      = Foundation without HTML snippet - 1: Title // Searchform / Map, Browser, Table
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/table_01.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/table_01.gif
          csvViews  = all
        }
        foundationTable02 {
          name      = Foundation without HTML snippet - 2: Searchform / Title, Browser, Table
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/table_02.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/table_02.gif
          csvViews  = all
        }
      }
    }
  }
}