plugin.tx_browser_pi1 {
  template {
    extensions {
        // empty line for enable commenting only
      browser {
      }
        // foundationMain
      browser {
        foundationMain01 {
          name      = Foundation 1: two columns, map above the list
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_01.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_01.gif
          csvViews  = all
        }
        foundationMain02 {
          name      = Foundation 2: two columns, map above the search form
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_02.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_02.gif
          csvViews  = all
        }
        foundationMain03 {
          name      = Foundation 3: one column
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_03.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_03.gif
          csvViews  = all
        }
        foundationMain04 {
          name      = Foundation 4: two columns for map and searchform, one column for the list
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_04.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_04.gif
          csvViews  = all
        }
        foundationMain05 {
          name      = Foundation 5: one column neither with map, nor search form, nor browsers
          file      = EXT:browser/Resources/Private/Templates/HTML/Foundation/main_05.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Foundation/main_05.gif
          csvViews  = all
        }
      }
    }
  }
}