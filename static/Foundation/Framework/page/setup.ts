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
    browserJquery     = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.jquery}
  }
  includeJSFooter {
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.foundation}
  }
  jsFooterInline {
      // browser: $( document ).foundation();
    60142 = TEXT
    60142 {
      value = $( document ).foundation();
    }
  }
}