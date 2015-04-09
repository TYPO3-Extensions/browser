plugin.tx_browser_pi1 {
  views {
    list {
      43746 {
        htmlSnippets =
        htmlSnippets {
          marker {
            filter = TEXT
            filter {
              value (
              <div class="filter">
                ###TT_CONTENT.TSTAMP###
                ###TT_CONTENT.CTYPE###
              </div>
)
            }
          }
          subparts {
            listview = TEXT
            listview {
              value (
                <div class="columns">
                  <div class="row">
                    <div class="columns listview listview-content listview-###MODE### listview-content-###MODE###">
                      <table###SUMMARY###>
                        ###CAPTION###
                        <!-- ###LISTHEAD### begin -->
                        <thead>
                          <tr><!-- ###LISTHEADITEM### begin -->
                            <th###CLASS###>###ITEM###</th><!-- ###LISTHEADITEM### end -->
                          </tr>
                        </thead><!-- ###LISTHEAD### end -->
                        <tbody><!-- ###LISTBODY### begin -->
                          <tr###CLASS###"><!-- ###LISTBODYITEM### begin -->
                            <td###CLASS###>###ITEM### ###SOCIALMEDIA_BOOKMARKS###</td><!-- ###LISTBODYITEM### end -->
                          </tr><!-- ###LISTBODY### end -->
                        </tbody>
                      </table>
                    </div><!-- /columns --><!-- /listview -->
                  </div><!-- /row -->
                </div><!-- /columns -->
)
            }
          }
        }
      }
    }
  }
}