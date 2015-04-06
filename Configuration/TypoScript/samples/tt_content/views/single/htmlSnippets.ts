plugin.tx_browser_pi1 {
  views {
    single {
      43746 {
        htmlSnippets =
        htmlSnippets {
          subparts {
            singleview = TEXT
            singleview {
              value (
                <div class="columns singleview singleview-###MODE###">
                  <!-- ###SINGLEVIEW### begin -->
                  <!-- ###AREA_FOR_AJAX_LIST_01### begin -->
                  ###RECORD_BROWSER###
                  <h1>
                    ###TITLE###
                  </h1>
                  <table###SUMMARY###>
                    ###CAPTION###<!-- ###SINGLEBODY### begin -->
                    <tbody><!-- ###SINGLEBODYROW### begin -->
                      <tr###CLASS###>
                        <th>###FIELD###</th>
                        <td>###VALUE### ###SOCIALMEDIA_BOOKMARKS###</td>
                      </tr><!-- ###SINGLEBODYROW### end -->
                    </tbody><!-- ###SINGLEBODY### end -->
                  </table>
                  <!-- ###AREA_FOR_AJAX_LIST_01### end -->
                  <!-- ###BACKBUTTON### begin -->
                  <p class="backbutton">
                    ###BUTTON###
                  </p>
                  <!-- ###BACKBUTTON### end -->
                  <!-- ###AREA_FOR_AJAX_LIST_02### begin -->
                  <!-- ###AREA_FOR_AJAX_LIST_02### end -->
                  <!-- ###SINGLEVIEW### end -->
                </div> <!-- /singleview -->
)
            }
          }
        }
      }
    }
  }
}