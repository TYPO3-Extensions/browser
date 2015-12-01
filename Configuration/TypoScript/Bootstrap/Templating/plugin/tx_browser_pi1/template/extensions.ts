plugin.tx_browser_pi1 {
  template {
    extensions {
        // empty line for enable commenting only
      browser {
      }
        // bootstrapMain
      browser {
        bootstrapMain01 {
          name      = Bootstrap 1: two columns, map above the list
          file      = EXT:browser/Resources/Private/Templates/HTML/Bootstrap/main_01.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Bootstrap/main_01.gif
          csvViews  = all
        }
        bootstrapMain02 {
          name      = Bootstrap 2: two columns, map above the search form
          file      = EXT:browser/Resources/Private/Templates/HTML/Bootstrap/main_02.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Bootstrap/main_02.gif
          csvViews  = all
        }
        bootstrapMain03 {
          name      = Bootstrap 3: one column
          file      = EXT:browser/Resources/Private/Templates/HTML/Bootstrap/main_03.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Bootstrap/main_03.gif
          csvViews  = all
        }
        bootstrapMain04 {
          name      = Bootstrap 4: two columns for map and searchform, one column for the list
          file      = EXT:browser/Resources/Private/Templates/HTML/Bootstrap/main_04.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Bootstrap/main_04.gif
          csvViews  = all
        }
        bootstrapMain05 {
          name      = Bootstrap 5: one column neither with map, nor search form, nor browsers
          file      = EXT:browser/Resources/Private/Templates/HTML/Bootstrap/main_05.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/Bootstrap/main_05.gif
          csvViews  = all
        }
      }
    }
  }
}