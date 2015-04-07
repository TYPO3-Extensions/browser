temp.configurationbriefing = TEXT
temp.configurationbriefing {
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
          Adapt these view to your needs
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
          Pass' diese View an Deine Anforderungen an:
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