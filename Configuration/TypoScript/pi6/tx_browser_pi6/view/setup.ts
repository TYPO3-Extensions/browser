plugin.tx_browser_pi6 {
    // empty statement for proper comments only
  view {
  }
    // layoutRootPaths, partialRootPaths, templateRootPaths
  view =
  view {
    layoutRootPaths {
      10 = EXT:browser/Resources/Private/View/Default/Layouts/
      20 = {$plugin.tx_browser_pi6.view.layoutRootPath}
    }
    partialRootPaths {
      10 = EXT:browser/Resources/Private/View/Default/Partials/
      20 = {$plugin.tx_browser_pi6.view.partialRootPath}
    }
    templateRootPaths {
      10 = EXT:browser/Resources/Private/View/Default/Templates/
      20 = {$plugin.tx_browser_pi6.view.templateRootPath}
    }
  }
}