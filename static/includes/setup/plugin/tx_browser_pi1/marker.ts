plugin.tx_browser_pi1 {
  marker {
        // markers can use TEXT or COA
    my_title = TEXT
    my_title {
      value =
      lang {
        de =
        en =
      }
      wrap = <h1>|</h1>
    }
        // markers can use TEXT or COA
    my_url = TEXT
    my_url {
      typolink {
        parameter = {page:uid}
        parameter {
          insertData = 1
        }
        returnLast = url
      }
    }
        // markers can use TEXT or COA
    my_reset = TEXT
    my_reset {
      value = Reset
      lang {
        de = Zur&uuml;cksetzen
        en = Reset
      }
    }
        // markers can use TEXT or COA
    my_search = TEXT
    my_search {
      value = Search
      lang {
        de = Suchen
        en = Search
      }
    }
        // markers can use TEXT or COA
    my_search_legend = TEXT
    my_search_legend {
      data = LLL:EXT:browser/pi1/locallang.xml:label_search_legend
    }
        // markers can use TEXT or COA
    my_csv_export = TEXT
    my_csv_export {
      value = Export
      lang {
        de = Export
        en = Export
      }
    }
        // markers can use TEXT or COA
    my_week = TEXT
    my_week {
      value = week
      lang {
        de = Woche
        en = week
      }
    }
  }
}