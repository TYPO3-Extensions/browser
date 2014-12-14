plugin.tx_browser_pi1 {
  navigation {
    indexBrowser {
      classes =
      classes {
        // a-tag: default, active
        a =
        a {
          default = ui-tabs-anchor
        }
        // tab-wrap: default, active
        tab =
        tab {
          active  = ui-state-default ui-corner-top tab-###TAB### tab-###KEY### ui-tabs-active ui-state-active selected
          default = ui-state-default ui-corner-top tab-###TAB### tab-###KEY###
        }
      }
      display {
          // [Boolean] Should tabs displayed, if they haven't any item?
        tabWithoutItems = 1
          // [Boolean] Should tabs use the title porperty of the HTML a tag?
        tabHrefTitle    = 1
      }
        // [INTEGER] The key/id of the default tab (see the 'x' in indexBrowser.tabs.x). If the user didn't activate any tab, this is the current tab.
      defaultTab = 0
      defaultTab {
          // [BOOLEAN] Display the name of the default tab in the URL path
        display_in_url = 0
      }
        // [STRING] The default wrap for all tabs. If you need a wrap for only one tab, please use the wrap property of that tab.
      defaultTabWrap = <span>|</span>
        // [Boolean] Are initials of the tabs case sensitive?
      caseSensitive = 0
        // [STRING, syntax: table.field]: If you want to use another field than the first field of your SQL query.
      field =
      tabs {
          // Tab with the property special (all)
        0 = All
        0 {
            // [STRING] (all || others): Display all items
          special =  all
          stdWrap {
            data = LLL:EXT:browser/pi1/locallang.xml:label_indexBrowser_all
          }
        }
        1 = 0-9
        1 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  0,1,2,3,4,5,6,7,8,9
            // Don't use the default value displayWithoutItems. Display this tab only, if it has items
          displayWithoutItems = 0
        }
        2 = A
        2 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  A
        }
        3 = B
        3 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  B
        }
        4 = C
        4 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  C
        }
        5 = D
        5 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  D
        }
        6 = E
        6 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  E
        }
        7 = F
        7 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  F
        }
        8 = G
        8 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  G
        }
        9 = H
        9 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  H
        }
        10 = I
        10 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  I
        }
        11 = J
        11 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  J
        }
        12 = K
        12 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  K
        }
        13 = L
        13 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  L
        }
        14 = M
        14 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  M
        }
        15 = N
        15 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  N
        }
        16 = O
        16 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  O
        }
        17 = PQ
        17 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  P, Q
        }
        18 = R
        18 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  R
        }
        19 = S
        19 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  S
        }
        20 = T
        20 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  T
        }
        21 = U
        21 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  U
        }
        22 = V
        22 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  V
        }
        23 = W
        23 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  W
        }
        24 = XYZ
        24 {
            // [CSV-STRING] List of comma separated initials (an initial has one digit only)
          valuesCSV =  X, Y, Z
        }
          // Tab with the property special (others)
        25 = Others
        25 {
            // [STRING] (all || others): Display all items which aren't matched by the other tabs
          special = others
            // Don't use the default value displayWithoutItems. Display this tab only, if it has items
          displayWithoutItems = 0
          stdWrap {
            data = LLL:EXT:browser/pi1/locallang.xml:label_indexBrowser_others
          }
        }
      }
        // [STRING] (auto || iso || utf): Manual configuring of using multibyte methods
      charset = auto
        // Workaround for UTF8
      workaround =
      workaround {
          // [BOOLEAN] Workaround for the index browser in case of trouble with UTF8: Send the SQL query 'SET NAMES latin1' before other any query.
        latin1 = {$plugin.tx_browser_pi1.navigation.indexbrowser.workaroundLatin1}
      }
    }
  }
}