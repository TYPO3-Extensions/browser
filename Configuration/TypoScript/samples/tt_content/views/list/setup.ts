plugin.tx_browser_pi1 {
  views {
    list {
      43746 = Browser: ready-to-use with tt_content
      43746 {
        name    = Browser: ready-to-use with tt_content
        showUid = contentUid
        select (
          tt_content.header,
          tt_content.bodytext,
          tt_content.list_type,
          tt_content.image
        )
        orderBy             = tt_content.header, tt_content.list_type
        csvLinkToSingleView = tt_content.header
        filter {
          tt_content {
            CType < plugin.tx_browser_pi1.displayList.master_templates.selectbox
            CType {
              first_item {
                cObject {
                  20 {
                    data >
                    value = Content type (CType)
                    lang {
                      de = Inhaltstyp (CType)
                      en = Content type (CType)
                    }
                  }
                }
              }
              wrap = <div class="selectbox">|</div>
            }

            tstamp < plugin.tx_browser_pi1.displayList.master_templates.category_menu
            tstamp {
              first_item {
                cObject {
                  20 {
                    data >
                    value = Year
                    lang {
                      de = Jahr
                      en = Year
                    }
                  }
                }
              }
              wrap = <span class="category_menu">|</span>
              order.field = uid
              area < plugin.tx_browser_pi1.displayList.master_templates.areas.sample_period
            }
          }
        }
        tt_content {
          list_type = COA
          list_type {
              // If it is default content
            10 = TEXT
            10 {
              if {
                isFalse = ###TT_CONTENT.LIST_TYPE###
              }
              value = default
              lang {
                de = Standard
                en = default
              }
            }
              // If it is a plugin
            20 = TEXT
            20 {
              if {
                isTrue = ###TT_CONTENT.LIST_TYPE###
              }
              value = ###TT_CONTENT.LIST_TYPE###
            }
          }
        }
          // marker and subparts will replaced in the HTML template before data handling
          // #43627, 121205, dwildt
        htmlSnippets =
        htmlSnippets {
          marker {
            filter = TEXT
            filter {
              value (
              <style type="text/css">
              <!--
                .filter ul {
                  margin: .2em 0 0;
                  padding: 0;
                }
              -->
              </style>
              <div class="filter" style="padding:.4em 0 0 0;">
                <div style="float:left;margin: 0 1em 0 0;">
                  ###TT_CONTENT.TSTAMP###
                </div>
                ###TT_CONTENT.CTYPE###
              </div>
)
            }
          }
          subparts {
            listview = COA
            listview {
              10 = TEXT
              10 {
                value (
            <div style="background:white;border:0.3em solid darkblue;color:darkred;padding:0 1em;font-size:.75em;position:relative;">
              <p>
                Configuration Briefing
              </p>
              <ul>
                <li>
                  Flexform [Behaviour]
                  <ul>
                    <li>
                      Record Storage Page: select your root page
                    </li>
                    <li>
                      Recursive: [Infinite]
                    </li>
                  </ul>
                </li>
                <li>
                  Flexform [Plugin] > [Templating]
                  <ul>
                    <li>
                      HTML: [Browser: Out of the box (browser)]
                    </li>
                    <li>
                      CSS jQuery UI (User Interface): select one out of 24 themes
                    </li>
                  </ul>
                </li>
                <li>
                  Flexform [Plugin] > [List View]
                  <ul>
                    <li>
                      Limit: records per page (from 1 to 1000): 5
                    </li>
                  </ul>
                </li>
                <li>
                  Adapt the vies to your needs
                  <ul>
                    <li>
                      Modul Web > Template
                    </li>
                    <li>
                      Page tree > current page
                    </li>
                    <li>
                      Edit area
                      <ul>
                        <li>
                          [TypoScript Object Browser]
                        </li>
                        <li>
                          Browse: [Setup]
                        </li>
                        <li>
                          Inspect:<br />
                          * plugin.tx_browser_pi1.views.list.43746<br />
                          * plugin.tx_browser_pi1.views.single.43746
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li>
                  Remove this prompt
                  <ul>
                    <li>
                      Modul Web > Template
                    </li>
                    <li>
                      Page tree > current page
                    </li>
                    <li>
                      Edit area
                      <ul>
                        <li>
                          [TypoScript Object Browser]
                        </li>
                        <li>
                          Browse: [Setup]
                        </li>
                        <li>
                          Remove: plugin.tx_browser_pi1.views.list.43746.htmlSnippets.subparts.listview.10
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
)
                lang.de (
            <div style="background:white;border:0.3em solid darkblue;color:darkred;padding:0 1em;position:relative;">
              <p>
                Konfigurationsanweisung
              </p>
              <ul>
                <li>
                  Flexform [Verhalten]
                  <ul>
                    <li>
                      Datensatzsammlung: Wähle Deine Startseite aus.
                    </li>
                    <li>
                      Rekursive: [Undendlich]
                    </li>
                  </ul>
                </li>
                <li>
                  Flexform [Plug-In] > [Templating]
                  <ul>
                    <li>
                      HTML: [Browser: Out of the Box (browser)]
                    </li>
                    <li>
                      CSS jQuery UI (User Interface): Wähle eins von 24 Themen aus.
                    </li>
                  </ul>
                </li>
                <li>
                  Flexform [Plugin] > [Listenansicht]
                  <ul>
                    <li>
                      Limit: Datensätze pro Seite (1 bis 1000): 5
                    </li>
                  </ul>
                </li>
                <li>
                  Pass' die Views an Deine Anforderungen an:
                  <ul>
                    <li>
                      Modul Web > Template
                    </li>
                    <li>
                      Seiten Baum > die aktuelle Seite
                    </li>
                    <li>
                      Bearbeitungsbereich
                      <ul>
                        <li>
                          [TypoScript-Objekt-Browser]
                        </li>
                        <li>
                          Durchsuchen: [Setup]
                        </li>
                        <li>
                          Sieh' Dir folgenden Code an:<br />
                          * plugin.tx_browser_pi1.views.list.43746. ...<br />
                          * plugin.tx_browser_pi1.views.single.43746. ...
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li>
                  Diesen Hinweis entfernen:
                  <ul>
                    <li>
                      Modul Web > Template
                    </li>
                    <li>
                      Seiten Baum > die aktuelle Seite
                    </li>
                    <li>
                      Bearbeitungsbereich
                      <ul>
                        <li>
                          [TypoScript-Objekt-Browser]
                        </li>
                        <li>
                          Durchsuchen: [Setup]
                        </li>
                        <li>
                          Entfernen: plugin.tx_browser_pi1.views.list.43746.htmlSnippets.subparts.listview.10 >
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
)
              }
              20 = TEXT
              20 {
                value (
            <div id="c###TT_CONTENT.UID###-listview-###MODE###" class="###VIEW###view ###VIEW###view-content ###VIEW###view-###MODE### ###VIEW###view-content-###MODE###">
              <!-- ###GROUPBY### begin -->
              <!-- ###GROUPBYHEAD### begin -->
              ###GROUPBY_GROUPNAME###
              <!-- ###GROUPBYHEAD### end -->
              <!-- ###GROUPBYBODY### begin -->
              <table id="c###TT_CONTENT.UID###-listview-###MODE###-table"###SUMMARY###>
                ###CAPTION###
                <!-- ###LISTHEAD### begin -->
                <thead>
                  <tr><!-- ###LISTHEADITEM### begin -->
                    <th###CLASS###>###ITEM###</th><!-- ###LISTHEADITEM### end -->
                  </tr>
                </thead><!-- ###LISTHEAD### end -->
                <tbody><!-- ###LISTBODY### begin -->
                  <tr###CLASS###><!-- ###LISTBODYITEM### begin -->
                    <td###CLASS###>###ITEM### ###SOCIALMEDIA_BOOKMARKS###</td><!-- ###LISTBODYITEM### end -->
                  </tr><!-- ###LISTBODY### end -->
                </tbody>
              </table>
              <!-- ###GROUPBYBODY### end -->
              <!-- ###GROUPBY### end -->
            </div> <!-- /listview -->
)
              }
            }
          }
        }
      }
    }
  }
}