plugin.tx_browser_pi1 {
  displayList {
    master_templates {
      subparts {
        listview {
            // 140703: empty statement: for proper comments only
          searchform {
          }
            // simple
          searchform =
          searchform {
              // 140703: empty statement: for proper comments only
            simple {
            }
                // Input for full text. Filter. Buttons for send, csv export and reset.
            simple = TEXT
            simple {
              value (
                <div class="columns show-for-medium-up small-###FDTMPLMAINLISTCOLLEFTSMALL### medium-###FDTMPLMAINLISTCOLLEFTMEDIUM### large-###FDTMPLMAINLISTCOLLEFTLARGE### searchbox searchbox-list-###MODE###">
                  <form action="###ACTION###" method="post" >
                    <fieldset>
                      <legend>
                        ###MY_SEARCH_LEGEND###
                      </legend>
                      ###HIDDEN###
                      <input type="hidden" name="no_cache" value="1" />
                      <input type="hidden" name="tx_browser_pi1[plugin]" value="###PLUGIN###" />
                      <input type="text" name="tx_browser_pi1[sword]" placeholder="###SWORD_DEFAULT###" value="###SWORD###" />
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                      <!-- FILTER marker will replaced by plugin.tx_browser_pi1.views.list.###MODE###.htmlSnippets.marker.filter while runtime ... -->
                      ###FILTER###
                      <button class="tiny expand secondary reset" role="button" type="reset" onclick="location = '###MY_URL###'">
                        ###MY_RESET###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### begin -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_CSV_EXPORT###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### end -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                    </fieldset>
                  </form>
                </div>
)
            }
            simpleMapFilter = TEXT
            simpleMapFilter {
              value (
                <div class="columns small-###FDTMPLMAINLISTCOLLEFTSMALL### medium-###FDTMPLMAINLISTCOLLEFTMEDIUM### large-###FDTMPLMAINLISTCOLLEFTLARGE### searchbox searchbox-list-###MODE###">
                  ###MAP###
                  <form action="###ACTION###" method="post" >
                    <fieldset>
                      <legend>
                        ###MY_SEARCH_LEGEND###
                      </legend>
                      ###HIDDEN###
                      <input type="hidden" name="no_cache" value="1" />
                      <input type="hidden" name="tx_browser_pi1[plugin]" value="###PLUGIN###" />
                      <input type="text" name="tx_browser_pi1[sword]" placeholder="###SWORD_DEFAULT###" value="###SWORD###" />
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                      <!-- FILTER marker will replaced by plugin.tx_browser_pi1.views.list.###MODE###.htmlSnippets.marker.filter while runtime ... -->
                      ###FILTER###
                      <button class="tiny expand secondary reset" role="button" type="reset" onclick="location = '###MY_URL###'">
                        ###MY_RESET###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### begin -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_CSV_EXPORT###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### end -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                    </fieldset>
                  </form>
                </div>
)
            }
            simpleMapOrderFilter = TEXT
            simpleMapOrderFilter {
              value (
                <div class="columns small-###FDTMPLMAINLISTCOLLEFTSMALL### medium-###FDTMPLMAINLISTCOLLEFTMEDIUM### large-###FDTMPLMAINLISTCOLLEFTLARGE### searchbox searchbox-list-###MODE###">
                  ###MAP###
                  <!-- ###LISTHEAD### begin -->
                    <!-- ###LISTHEADITEM### begin -->
                      ###ITEM###
                    <!-- ###LISTHEADITEM### end -->
                  <!-- ###LISTHEAD### end -->
                  <form action="###ACTION###" method="post" >
                    <fieldset>
                      <legend>
                        ###MY_SEARCH_LEGEND###
                      </legend>
                      ###HIDDEN###
                      <input type="hidden" name="no_cache" value="1" />
                      <input type="hidden" name="tx_browser_pi1[plugin]" value="###PLUGIN###" />
                      <input type="text" name="tx_browser_pi1[sword]" placeholder="###SWORD_DEFAULT###" value="###SWORD###" />
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                      <!-- FILTER marker will replaced by plugin.tx_browser_pi1.views.list.###MODE###.htmlSnippets.marker.filter while runtime ... -->
                      ###FILTER###
                      <button class="tiny expand secondary reset" role="button" type="reset" onclick="location = '###MY_URL###'">
                        ###MY_RESET###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### begin -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_CSV_EXPORT###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### end -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                    </fieldset>
                  </form>
                </div>
)
            }
            simpleFilterMap = TEXT
            simpleFilterMap {
              value (
                <div class="columns small-###FDTMPLMAINLISTCOLLEFTSMALL### medium-###FDTMPLMAINLISTCOLLEFTMEDIUM### large-###FDTMPLMAINLISTCOLLEFTLARGE### searchbox searchbox-list-###MODE###">
                  <form action="###ACTION###" method="post" >
                    <fieldset>
                      <legend>
                        ###MY_SEARCH_LEGEND###
                      </legend>
                      ###HIDDEN###
                      <input type="hidden" name="no_cache" value="1" />
                      <input type="hidden" name="tx_browser_pi1[plugin]" value="###PLUGIN###" />
                      <input type="text" name="tx_browser_pi1[sword]" placeholder="###SWORD_DEFAULT###" value="###SWORD###" />
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                      <!-- FILTER marker will replaced by plugin.tx_browser_pi1.views.list.###MODE###.htmlSnippets.marker.filter while runtime ... -->
                      ###FILTER###
                      <button class="tiny expand secondary reset" role="button" type="reset" onclick="location = '###MY_URL###'">
                        ###MY_RESET###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### begin -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_CSV_EXPORT###
                      </button>
                      <!-- ###BUTTON_CSV-EXPORT### end -->
                      <button class="tiny expand hidesubmit" role="button">
                        ###MY_SEARCH###
                      </button>
                    </fieldset>
                  </form>
                  ###MAP###
                </div>
)
            }
          }
        }
      }
    }
  }
}