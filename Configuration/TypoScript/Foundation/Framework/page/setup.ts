page {

  meta {
      // Foundation
    viewport = width=device-width, initial-scale=1.0
  }

  includeCSS {
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.foundation}
    browserFoundCss   = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.browser}
  }
  includeJSFooter {
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.foundation}
    60142 = EXT:browser/Resources/Public/JavaScript/foundationInit.js
  }
}