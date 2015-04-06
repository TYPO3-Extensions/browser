plugin.tx_browser_pi1 {
  views {
    single {
      43746 = Browser: ready-to-use with tt_content
      43746 {
        name    = Browser: ready-to-use with tt_content
        showUid = contentUid
        select (
          tt_content.header,
          tt_content.header_layout,
          tt_content.tstamp,
          tt_content.CType,
          tt_content.bodytext,
          tt_content.list_type,
          tt_content.image,
          tt_content.layout,
          tt_content.colPos
        )
        tt_content {
          tstamp = TEXT
          tstamp {
            value = ###TX_ORG_CAL.DATETIME###
            strftime = %A, %d. %B %Y, %H:%M Uhr
          }
        }
          // marker and subparts will replaced in the HTML template before data handling
          // #43627, 121205, dwildt
        htmlSnippets =
        htmlSnippets {
          subparts {
            singleview = TEXT
            singleview {
              value (
<!-- ###AREA_FOR_AJAX_LIST_01### begin -->
        <div id="c###TT_CONTENT.UID###-singleview-###MODE###" class="singleview singleview-###MODE###">
<!-- ###AREA_FOR_AJAX_LIST_01### begin -->
        <div id="c###TT_CONTENT.UID###-singleview-###MODE###" class="singleview singleview-###MODE###">
          ###RECORD_BROWSER###
          <div class="ui-widget ui-corner-all">
            <h1 class="ui-widget-header ui-corner-top">
              ###TITLE###
            </h1>
            <div class="ui-widget-content ui-corner-bottom">
              <table###SUMMARY###>
                ###CAPTION###<!-- ###SINGLEBODY### begin -->
                <tbody><!-- ###SINGLEBODYROW### begin -->
                  <tr###CLASS###>
                    <th>###FIELD###</th>
                    <td>###VALUE### ###SOCIALMEDIA_BOOKMARKS###</td>
                  </tr><!-- ###SINGLEBODYROW### end -->
                </tbody><!-- ###SINGLEBODY### end -->
              </table>
            </div>
<!-- ###AREA_FOR_AJAX_LIST_01### end -->
            <!-- ###BACKBUTTON### begin -->
            <p class="backbutton">
              ###BUTTON###
            </p>
          <!-- ###BACKBUTTON### end -->
<!-- ###AREA_FOR_AJAX_LIST_02### begin -->
          </div>
        </div>
<!-- ###AREA_FOR_AJAX_LIST_02### end -->
)
            }
          }
        }
      }
    }
  }
}