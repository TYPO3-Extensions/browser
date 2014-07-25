plugin.tx_browser_pi1 {
  navigation {
      // [BOOLEAN] 1 (default): Display the record browser. 0: Don't display it.
    record_browser = 1
    record_browser {
      display {
          // [BOOLEAN] 0: Don't display the record browser, if there isn't any result/record. 1 (default): Display it ever.
        withoutResult     = 1
          // [BOOLEAN] 0: Don't display the first and the last button. 1 (default): Display it.
        firstAndLastButton  = 1
          // [BOOLEAN] 0 (default): Display buttons with a link only.
        buttonsWithoutLink  = 1
      }
      buttons {
        chars {
            // Box: wrap for all buttons
          wrap_all = TEXT
          wrap_all {
              // [STRING] empty (default): empty value will allocated while runtime with the rendered buttons.
            value =
            wrap  = <ul id="c###TT_CONTENT.UID###-recordBrowser" class="recordBrowser ui-widget ui-helper-clearfix">|</ul><div id="update-prompt"></div>
          }
            // The devider between of two buttons
          devider = TEXT
          devider {
            value =
            noTrimWrap = | | |
          }
            // First button of the record browser
          first = TEXT
          first {
              // [STRING] empty (default): empty value will allocated while runtime. See labeling below.
            value = &lt;&lt;
            noTrimWrap  = |<li class="ui-state-default ui-corner-all"> | </li>|
            typolink {
              parameter = {page:uid} - c###TT_CONTENT.UID###-recordBrowser ###RECORD_POSITION###/###RECORD_SUM###
              parameter {
                insertData = 1
              }
              additionalParams  = &tx_browser_pi1[{$plugin.tx_browser_pi1.navigation.showUid}]=###RECORD_UID###&L={GP:L}###PIVARS_FOR_SINGLEVIEW###
              additionalParams {
                insertData = 1
              }
              useCacheHash = 1
            }
          }
            // Previous button of the record browser
          prev < .first
          prev {
            value = &lt;
          }
            // Current button of the record browser
          curr < .first
          curr {
            value = &Oslash;
            typolink {
              parameter = {page:uid}
              additionalParams = &L={GP:L}###PIVARS_FOR_LISTVIEW###
            }
            //noTrimWrap  = |<li class="ui-state-default ui-corner-all c###TT_CONTENT.UID###-recordBrowser"> | </li>|
          }
            // Next button of the record browser
          next < .first
          next {
            value = &gt;
          }
            // Last button of the record browser
          last < .first
          last {
            value = &gt;&gt;
          }
        }
        icons < .chars
        icons {
          first {
            value = <span class="ui-icon ui-icon-seek-first" style="float: left;margin: 0 4px;"></span>
          }
          prev {
            value = <span class="ui-icon ui-icon-seek-prev" style="float: left;margin: 0 4px;"></span>
          }
          curr {
            value = <span class="ui-icon ui-icon-home" style="float: left;margin: 0 4px;"></span>
          }
          next {
            value = <span class="ui-icon ui-icon-seek-next" style="float: left;margin: 0 4px;"></span>
          }
          last {
            value = <span class="ui-icon ui-icon-seek-end" style="float: left;margin: 0 4px;"></span>
          }
        }
        position < .chars
        position {
          first {
            value = ###RECORD_POSITION###
          }
          prev {
            value = ###RECORD_POSITION###
          }
          curr {
            value = ###RECORD_POSITION###
          }
          next {
            value = ###RECORD_POSITION###
          }
          last {
            value = ###RECORD_POSITION###
          }
        }
        text < .chars
        text {
          first {
            value   = first
            lang {
              de = Erster
              en = first
            }
          }
          prev {
            value   = previous
            lang {
              de = Vorheriger
              en = previous
            }
          }
          curr {
            value   = current
            lang {
              de = Aktuell
              en = current
            }
          }
          next {
            value   = next
            lang {
              de = N&auml;chster
              en = next
            }
          }
          last {
            value   = last
            lang {
              de = Letzter
              en = last
            }
          }
        }
        current < .icons
      }
      buttons_wo_link < .buttons
      buttons_wo_link {
        chars {
          wrap_all  >
          devider   >
          first {
            typolink  >
          }
          prev {
            typolink  >
          }
          curr >
          next {
            typolink  >
          }
          last {
            typolink  >
          }
        }
        icons {
          wrap_all  >
          devider   >
          first {
            value = <span style="float: left;margin:8px 12px;"></span>
            typolink  >
          }
          prev {
            value = <span style="float: left;margin:8px 12px;"></span>
            typolink  >
          }
          curr >
          next {
            value = <span style="float: left;margin:8px 12px;"></span>
            typolink  >
          }
          last {
            value = <span style="float: left;margin:8px 12px;"></span>
            typolink  >
          }
        }
        position {
          wrap_all  >
          devider   >
          first {
            typolink  >
          }
          prev {
            typolink  >
          }
          curr >
          next {
            typolink  >
          }
          last {
            typolink  >
          }
        }
        text {
          wrap_all  >
          devider   >
          first {
            typolink  >
          }
          prev {
            typolink  >
          }
          curr >
          next {
            typolink  >
          }
          last {
            typolink  >
          }
        }
        current < .icons
      }
      enable {
          // [BOOLEAN] 0 (recommended) || 1: marker PIVARS_FOR_LISTVIEW will replece with piVar params (http query). BE AWARE: Don't cache the single view in this case!
        pivars_for_listview = 0
        pivars_for_listview {
            // [STRING] Comma seperated list of piVars, which shouldn't rendered. Example: sword, pointer
          csvDontRenderPiVars =
        }
          // [BOOLEAN] 0 (recommended) || 1: marker PIVARS_FOR_SINGLEVIEW will replece with piVar params (http query). BE AWARE: Don't cache the single view in this case!
        pivars_for_singleview = 0
        pivars_for_singleview {
            // [STRING] Comma seperated list of piVars, which shouldn't rendered. Example: sword, pointer
          csvDontRenderPiVars =
        }
      }
      special {
          // [BOOLEAN] 0 (recommended) || 1: Overwrites sys_lsysanguage_overlay temporarily (freemedia case)
        listViewWithDefaultLanguage = 0
      }
    }
  }
}