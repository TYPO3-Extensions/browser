plugin.tx_browser_pi1 {
  template {
      // default file
    file = EXT:browser/Resources/Private/Templates/HTML/default.tmpl
    css {
      browser = EXT:browser/Resources/Public/Css/default.css
      browser {
          // [Boolean] 0 (default): CSS as external file, 1: CSS inline
        inline = 0
      }
      jquery_ui = EXT:browser/Resources/Public/JavaScript/jQuery/ui/smoothness/jquery-ui-1.8.14.custom.css
      jquery_ui {
          // [Boolean] 0 (default): CSS as external file, 1: CSS inline
        inline = 0
      }
    }
    extensions {
        // table, list, main, margin, table_until_3-9-6
      browser =
      browser {
        table {
          name  = Browser: Table
          file  = EXT:browser/Resources/Private/Templates/HTML/default.tmpl
          image = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_01_table.gif
        }
        list {
          name  = Browser: List
          file  = EXT:browser/Resources/Private/Templates/HTML/default_ul.tmpl
          image = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_02_list.gif
        }
          // #44131
        main {
          name      = Browser: Main
          file      = EXT:browser/Resources/Private/Templates/HTML/main.tmpl
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_03_main.gif
          csvViews  = all
        }
          // #44131
        margin {
          name      = Browser: Margin
          file      = EXT:browser/Resources/Private/Templates/HTML/margin.tmpl
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_04_margin.gif
          csvViews  = all
        }
          // #60530
        newsletter {
          name      = Browser: Newsletter
          file      = EXT:browser/Resources/Private/Templates/HTML/newsletter.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_05_newsletter.gif
          csvViews  = all
        }
          // #i0168
        slick {
          name      = Browser: Slick Carousel
          file      = EXT:browser/Resources/Private/Templates/HTML/slick.html
          image     = EXT:browser/Resources/Public/Images/BackendLayouts/browser-default_06_slick.gif
          csvViews  = all
        }
      }
    }
    add_parameter {
      comment   = Array for adding parameter to navigation elements.
      tutorial  = http://typo3.org/extensions/repository/view/browser_tut_navigation_en/current/
        // Default parameter
      browser = COA
      browser {
          // showUid
        10 = TEXT
        10 {
          // #i0170, 150430, dwildt, 1-
          //dataWrap        = &tx_browser_pi1[showUid]={GP:tx_browser_pi1|showUid}
          // #i0170, 150430, dwildt, 4+
          stdWrap {
            dataWrap  = &tx_browser_pi1[showUid]={GP:tx_browser_pi1|showUid}
            intval    = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|showUid
        }
          // azTab
        20 = TEXT
        20  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap        = &tx_browser_pi1[azTab]={GP:tx_browser_pi1|azTab}
          // #i0170, 150430, dwildt, 5+
          stdWrap {
            dataWrap          = &tx_browser_pi1[azTab]={GP:tx_browser_pi1|azTab}
            htmlSpecialChars  = 1
            stripHtml         = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|azTab
        }
          // indexBrowserTab
        21 = TEXT
        21  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap = &tx_browser_pi1[indexBrowserTab]={GP:tx_browser_pi1|indexBrowserTab}
          // #i0170, 150430, dwildt, 5+
          stdWrap {
           dataWrap           = &tx_browser_pi1[indexBrowserTab]={GP:tx_browser_pi1|indexBrowserTab}
            htmlSpecialChars  = 1
            stripHtml         = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|indexBrowserTab
        }
          // mode
        30 = TEXT
        30  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap        = &tx_browser_pi1[mode]={GP:tx_browser_pi1|mode}
          // #i0170, 150430, dwildt, 5+
          stdWrap {
            dataWrap          = &tx_browser_pi1[mode]={GP:tx_browser_pi1|mode}
            htmlSpecialChars  = 1
            stripHtml         = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|mode
        }
          // pointer
        40 = TEXT
        40  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap        = &tx_browser_pi1[pointer]={GP:tx_browser_pi1|pointer}
          // #i0170, 150430, dwildt, 4+
          stdWrap {
            dataWrap  = &tx_browser_pi1[pointer]={GP:tx_browser_pi1|pointer}
            intval    = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|pointer
        }
          // sort
        50 = TEXT
        50  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap = &tx_browser_pi1[sort:tx_browser_pi1|sort]={GP:tx_browser_pi1|sort}
          // #i0170, 150430, dwildt, 5+
          stdWrap {
            dataWrap          = &tx_browser_pi1[sort:tx_browser_pi1|sort]={GP:tx_browser_pi1|sort}
            htmlSpecialChars  = 1
            stripHtml         = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|sort
        }
          // sword
        60 = TEXT
        60  {
          // #i0170, 150430, dwildt, 1-
          //dataWrap        = &tx_browser_pi1[sword]={GP:tx_browser_pi1|sword}
          // #i0170, 150430, dwildt, 5+
          stdWrap {
            dataWrap        = &tx_browser_pi1[sword]={GP:tx_browser_pi1|sword}
            htmlSpecialChars = 1
            stripHtml = 1
          }
          if.isTrue.data  = GP:tx_browser_pi1|sword
        }
      }
    }
  }
}