plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        marker < plugin.tx_browser_pi1.marker
        marker {
          rss_description = TEXT
          rss_description {
            value   = Browser – TYPO3 without PHP. RSS feed with tt_news.
            lang {
              de = Browser – TYPO3 ohne PHP: RSS-Feed mit tt_news.
              en = Browser – TYPO3 without PHP. RSS feed with tt_news.
            }
          }
          rss_lang = TEXT
          rss_lang {
            value   = en
            lang {
              de = de
              en = en
            }
          }
          rss_title = TEXT
          rss_title {
            value   = News
            lang {
              de = Nachrichten
              en = News
            }
          }
          rss_url = TEXT
          rss_url {
            typolink {
              parameter         = {$plugin.tx_browser_pi1.templates.listview.url.0.singlePid}
              forceAbsoluteUrl  = 1
              returnLast        = url
            }
          }
        }
      }
    }
  }
}