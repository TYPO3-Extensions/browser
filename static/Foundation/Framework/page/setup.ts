page {

  includeCSS {
    browserNormalize  = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.normalize}
    browserFoundation = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.foundation}
    browserFoundCss   = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeCSS.browser}
  }
  includeJS {
    browserModernizr  = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJS.modernizr}
  }
  includeJSFooter {
    browserJquery     = {$plugin.tx_browser_pi1.frameworks.foundation.framework.page.includeJSFooter.jquery}
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