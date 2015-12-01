plugin.tx_browser_pi1 {
  navigation {
    indexBrowser {
      classes {
        a {
          default =
          empty   = disabled
        }
        tab {
          active  = active tab-###TAB### tab-###KEY###
          default = tab-###TAB### tab-###KEY###
        }
      }
      defaultTabWrap = |
    }
    pageBrowser {
      wrap {
        activeLinkWrap    = <li class="active">|</li>
        browseLinksWrap   = <nav><ul class="pagination pagination-sm">|</ul></nav>
        disabledLinkWrap  = <li class="disabled"><a>|</a></li>
      }
    }
  }
}