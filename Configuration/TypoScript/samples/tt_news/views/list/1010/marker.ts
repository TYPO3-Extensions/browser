plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        marker < plugin.tx_browser_pi1.marker
        marker {
          my_http = TEXT
          my_http {
            value = http://
          }
          rss_title {
            value   = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.default.title}
            lang.de = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.de.title}
          }
          rss_url {
            typolink {
              parameter = {$plugin.tx_browser_pi1.extensions.tt_news.pages.rss_feed}
            }
          }
          rss_description {
            value   = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.default.description}
            lang.de = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.de.description}
          }
          rss_image_title {
            value   = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.default.description}
            lang.de = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.de.description}
          }
          rss_image_url {
            value = http://{$plugin.tx_browser_pi1.extensions.tt_news.host}{$plugin.tx_browser_pi1.extensions.tt_news.pathToIcon}
          }
          rss_image_link {
            value = http://{$plugin.tx_browser_pi1.extensions.tt_news.host}/
          }
          rss_image_height {
            value = http://{$plugin.tx_browser_pi1.extensions.tt_news.heightOfIcon}/
          }
          rss_image_width {
            value = http://{$plugin.tx_browser_pi1.extensions.tt_news.widthOfIcon}/
          }
          rss_image_description {
            value   = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.default.description}
            lang.de = {$plugin.tx_browser_pi1.extensions.tt_news._LOCAL_LANG.de.description}
          }
        }
      }
    }
  }
}