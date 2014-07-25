plugin.tx_browser_pi1 {
  displayList {
      // 140703: empty statement: for proper comments only
    master_templates {
    }
      // subparts
    master_templates {
        // 140703: empty statement: for proper comments only
      subparts {
      }
        // listview
      subparts =
      subparts {
          // 140703: empty statement: for proper comments only
        listview {
        }
          // searchform
        listview =
        listview {
            // 140703: empty statement: for proper comments only
          searchform {
          }
            // complex
          searchform =
          searchform {
              // 140703: empty statement: for proper comments only
            complex {
            }
                // Input for full text. Buttons for send, csv export and reset. Filter. Resultphrase
            complex = TEXT
            complex {
              value (
            <div id="c###TT_CONTENT.UID###-list-searchbox-###MODE###" class="columns small-4 large-4 searchbox searchbox-list-###MODE###">
              <form id="c###TT_CONTENT.UID###-list-searchbox-form-###MODE###" action="###ACTION###" method="post" >
                <fieldset>
                  <legend>
                    ###MY_SEARCH_LEGEND###
                  </legend>
                  ###HIDDEN###
                  <input type="hidden" name="no_cache" value="1" />
                  <input type="hidden" name="tx_browser_pi1[plugin]" value="###PLUGIN###" />
                  <input class="sword" type="text" name="tx_browser_pi1[sword]" onfocus="if( this.value == '###SWORD_DEFAULT###' )
                        this.value = ''" value="###SWORD###" />
                  <button id="c###TT_CONTENT.UID###-list-submit-sword-###MODE###" class="tiny hidesubmit" role="button">
                    ###MY_SEARCH###
                  </button>
                  <!-- ###BUTTON_CSV-EXPORT### begin -->
                  <button id="c###TT_CONTENT.UID###-list-submit-csv-export-###MODE###" class="tiny hidesubmit" role="button">
                    ###MY_CSV_EXPORT###
                  </button>
                  <!-- ###BUTTON_CSV-EXPORT### end -->
                  <button id="c###TT_CONTENT.UID###-list-submit-sword-###MODE###" type="reset" onclick="location = '###MY_URL###'" class="tiny reset" role="button">
                    ###MY_RESET###
                  </button>
                  <! -- FILTER marker will replaced by plugin.tx_browser_pi1.views.list.###MODE###.htmlSnippets.marker.filter while runtime ... -->
                  ###FILTER###
                </fieldset>
              </form>
              ###RESULTPHRASE###
            </div>
  )
            }
          }
        }
      }
    }
  }
}