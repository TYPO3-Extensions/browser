page {

  meta {
      // Foundation
    viewport = width=device-width, initial-scale=1.0
  }

  includeCSS {
    browserNormalize  = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.normalize}
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.foundation}
    browserFoundCss   = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.browser}
  }
  includeJS {
    browserModernizr  = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJS.modernizr}
  }
  includeJSFooter {
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.foundation}
    60142 = EXT:browser/Resources/Public/JavaScript/foundationInit.js
  }
  includeJSFooterlibs {
    browserJquery     = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.jquery}
    browserJquery.forceOnTop = 1
  }
}