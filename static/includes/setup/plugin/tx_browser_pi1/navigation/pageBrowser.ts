plugin.tx_browser_pi1 {
  navigation {
    pageBrowser {
    }
      // dontLinkActivePage, forceOutput, maxPages, pagefloat, pointer, showFirstLast, showRange, showResultCount, tableParams, wrap
    pageBrowser =
    pageBrowser {
        // [BOOLEAN] Don't link the active page
      dontLinkActivePage  =  1
        // [BOOLEAN] Display the pageBrowser even if there isn't any page or one page only
      forceOutput         =  0
        // [BOOLEAN] Enable htmlspecialchars(). Set this to FALSE if you want f.e use images instead of text for links like 'previous' and 'next'
      hscText             =  0
        // [INTEGER] The maximum pages, which will displayed in the page browser
      maxPages            = 10
      pagefloat           = center
        // [String] Name for the pagebrowser piVar
      pointer             = pointer
        // [BOOLEAN] Display the text line "Displaying results x to y out of z"
      showFirstLast       =  1
        // [BOOLEAN] Display the text line "<< First < Previous 1-20 21-40 41-60 Next > Last >>"
      showRange           =  0
      showResultCount     =  1
      tableParams         = cellpadding="2" align="center"
        // activeLinkWrap, browseBoxWrap, browseLinksWrap, disabledLinkWrap, inactiveLinkWrap, showResultsNumbersWrap, showResultsWrap
      wrap =
      wrap {
        activeLinkWrap         = <span class="SCell">|</span>
        browseBoxWrap          = <div class="browseBoxWrap">|</div>
        browseBoxWrap          = |
        browseLinksWrap        = <div class="browseLinksWrap">|</div>
        disabledLinkWrap       = <span class="SCell">|</span>
        inactiveLinkWrap       = |
        showResultsNumbersWrap = <span class="showResultsNumbersWrap">|</span>
        showResultsWrap        = <div class="showResultsWrap">|</div>
      }
    }
  }
}