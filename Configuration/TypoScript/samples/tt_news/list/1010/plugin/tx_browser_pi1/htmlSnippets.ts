plugin.tx_browser_pi1 {
  views {
    list {
      1010 {
        htmlSnippets =
        htmlSnippets {
          subparts {
            listview = TEXT
            listview {
              value (<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>###RSS_TITLE###</title>
    <link>###RSS_URL###</link>
    <description>###RSS_DESCRIPTION###</description>
    <language>###RSS_LANG###</language>
    <image>
      <title>###RSS_IMAGE_TITLE###</title>
      <url>###RSS_IMAGE_URL###</url>
      <link>###RSS_IMAGE_LINK###</link>
      <width>###RSS_IMAGE_WIDTH###</width>
      <height>###RSS_IMAGE_HEIGHT###</height>
      <description>###RSS_IMAGE_DESCRIPTION###</description>
    </image>
    <!-- ###LISTVIEW### begin --><!-- ###LISTBODY### begin --><!-- ###LISTBODYITEM### begin --><item>
      <title>
        <![CDATA[###TT_NEWS.TITLE###]]>
      </title>
      <link>
        ###MY_HTTP######TT_NEWS.UID###
      </link>
      <content:encoded><![CDATA[###TT_NEWS.SHORT###]]></content:encoded>
    </item><!-- ###LISTBODYITEM### end --><!-- ###LISTBODY### end --><!-- ###LISTVIEW### end -->
  </channel>
</rss>
)
            }
          }
        }
      }
    }
  }
}