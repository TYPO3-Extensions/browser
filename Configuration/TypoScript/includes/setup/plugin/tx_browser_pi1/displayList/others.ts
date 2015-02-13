  ///////////////////////////////////////////////////////////////
  //
  // plugin.tx_browser_pi1: displayList

plugin.tx_browser_pi1 {
  displayList {

    singlePid =
    seo {
      htmlHead {
          // [Boolean] Search Engine Optimization for the meta tag title. Activate the register browser_htmlTitleTag.
        title = 0
        meta {
            // [Boolean] Search Engine Optimization for the meta tag description. Activate the register browser_description.
          description = 0
            // [Boolean] Search Engine Optimization for the meta tag keywords. Activate the register browser_keywords.
          keywords    = 0
        }
      }
    }

    display {
        // and, or, not, resultPhrase, wrapSwordInResults, noResult, respect_filters
      searchform =
      searchform {
        and = TEXT
        and {
            // [String] One word only
          value = and
          lang {
              // [String] One word only
            de = und
          }
        }
        or = TEXT
        or {
            // [String] One word only
          value = or
          lang {
              // [String] One word only
            de = oder
          }
        }
        not = TEXT
        not {
            // [String] One word only
          value = not
          lang {
              // [String] One word only
            de = nicht
          }
        }
        resultPhrase {
          searchFor  = TEXT
          searchFor {
            value = Search for
            lang {
              de = Suche nach
            }
          }
          hasResult  = TEXT
          hasResult {
            value = has the result
            lang {
              de = hat folgendes Ergebnis
            }
          }
          minLenPhrase = 1
            // [Boolean] 1: display; 0: don't display
          minLenPhrase {
            value = (a single sword must content ###advanced.security.sword.minLenWord### at least).
            lang {
              de = (ein Suchbegriff muss mindestens ###advanced.security.sword.minLenWord### Zeichen lang sein).
            }
            wrap = <br />|
          }
          operatorPhrase = 1
            // [Boolean] 1: display; 0: don't display
          operatorPhrase {
            value (
<p>
  You can fokus your search with "and", "not" and phrases. Examples:
</p>
<ul>
  <li>
    "green apples" and taste
  </li>
  <li>
    apples and taste not "Golden Delicious"
  </li>
</ul>
)
            lang {
              de (
<p>
  Sie k&ouml;nnen die Suche mit "und", "nicht" und Phrasen eingrenzen. Beispiele:
</p>
<ul>
  <li>
    "Gr&uuml;ne &Auml;pfel" und Geschmack
  </li>
  <li>
    &Auml;pfel und Geschmack nicht "Golden Delicious"
  </li>
</ul>
)
            }
            wrap = |
          }
          wildcardPhrase  =TEXT
          wildcardPhrase {
            value (
<ul>
  <li>
    apple%wildcard% will find apple and applepie
  </li>
</ul>
)
            lang {
              de (
<ul>
  <li>
    Apfel%wildcard% findet Apfel und Apfelmus
  </li>
</ul>
)
            }
            wrap = |
          }
          wrap = <p>|</p>
        }
        wrapSwordInResults {
          0 {
              // #ffff7f: light yellow
            wrap = <span style="background:#ffff7f;font-weight:bold;padding:0 .2em;">|</span>
          }
          1 {
              // #b3ffb2: light green
            wrap = <span style="background:#b3ffb2;font-weight:bold;padding:0 .2em;">|</span>
          }
          2 {
              // #ffb2b2: light red
            wrap = <span style="background:#ffb2b2;font-weight:bold;padding:0 .2em;">|</span>
          }
          3 {
              // #b2b3ff: light blue
            wrap = <span style="background:#b2b3ff;font-weight:bold;padding:0 .2em;">|</span>
          }
          4 {
              // #ffffb3: light light yellow
            wrap = <span style="background:#ffffb3;font-weight:bold;padding:0 .2em;">|</span>
          }
          5 {
              // #e6ffb2 light light yellow green
            wrap = <span style="background:#e6ffb2;font-weight:bold;padding:0 .2em;">|</span>
          }
          6 {
              // #ffd380: light orange
            wrap = <span style="background:#ffd380;font-weight:bold;padding:0 .2em;">|</span>
          }
          7 {
              // #b3ffff: light turkise
            wrap = <span style="background:#b3ffff;font-weight:bold;padding:0 .2em;">|</span>
          }
        }
        respect_filters = Comment: The search ignore any filter, if all is false (default)
        respect_filters {
            // [Boolean] 0: Don't respect any filter (default), 1: Respect every filter
          all = 0
          but = Comment: but is an array for exceptions
          but {
              // table.field [Boolean] This is an example. This has an effect only, if all is false
            tx_mytable_pi1.datetime = 1
          }
        }
      }
        // [Boolean] 0: subpart CATEGORY_MENU will removed from the HTML template (default). 1: Don't remove it!
      category_menu = 0
      emptyListByStart {
        stdWrap {
          data = LLL:EXT:browser/pi1/locallang.xml:label_first_visit
          wrap = <p style="padding:2em 0;">|</p>
        }
      }
      table {
          // [Boolean] Display the HTML table property summary (WAI guideline)
        summary     = 1
          // [Boolean] Display the HTML table tag caption (WAI guideline)
        caption     = 1
      }
        // [Boolean] If it isn't possible to link to a single view, link to a single nevertheless and display a JSS alert on click
      jssAlert      = 1
    }

      // cssClass
    templateMarker = ###TEMPLATE_LIST###
    templateMarker {
        // odd, tr, td, wrap
      cssClass =
      cssClass {
          // [String] Class for odd rows and columns
        odd = odd
          // [String] Class for rows. Don't use 'rows' in case of bootstrap or foundation!
        tr = tr
          // [String] Class for columns. Don't use 'columns' in case of bootstrap or foundation!
        td = td
          // [Boolean] Wrap the class by a noTrimWrap: | class="|"|
        wrap = 1
      }
    }

    selectBox_orderBy {
        // [Boolean] 1: Display the select box for ordering (default), 0: don't display.
      display = 0
        // upto 3.4.5: templateMarker = ###TEMPLATE_SELECTBOX###
      templateMarker = ###TEMPLATE_SELECTBOX_VALID###
      selectbox = SELECTBOX
      selectbox {
        form {
            // [String] HTML class of the form
          class = selectboxorderby
          legend_stdWrap {
            value   = Sorting
            lang.de = Sortieren
          }
          button_stdWrap {
            value   = Send
            lang.de = Absenden
          }
        }
          // [Boolean] Should be added a default item?
        first_item = 1
        first_item {
          stdWrap {
            data = LLL:EXT:browser/pi1/locallang.xml:label_selectbox_firstitem_default
          }
            // [STRING, unique] Value attribute in the option tag (#11401)
          option_value = 0
        }
          // [Integer] How many items should the select box display in the visible area?
        size = 1
          // [String] Wrap the object
          // upto 3.4.5: wrap = <div class="selectbox">|</div>
        wrap = |
        wrap {
            // [Integer] Space characters from left margin in the HTML code
          nice_html_spaceLeft = 12
            // [String] Wrap the items
            // upto 3.4.5: object  = <select name="orderby" size="###SIZE###" onchange="javascript:Go(this)">|</select>
          object = <select name="tx_browser_pi1[sort]" size="###SIZE###">|</select>
          object {
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 14
          }
            // [String] Wrap one item
            // upto 3.4.5: item    = <option###CLASS######STYLE### value="###URL###"###ITEM_SELECTED###>|</option>
          item = <option###CLASS######STYLE### value="###VALUE###"###ITEM_SELECTED###>|</option>
          item {
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 16
              // [String] HTML code for a selected item
            selected = selected="selected"
              // [String] Default HTML class
            class {
              asc   = asc
              desc  = desc
            }
              // [String] Default HTML style
            style =
            stdWrap {
              htmlSpecialChars = 1
              crop = 60 | ... | 1
            }
          }
        }
        order {
            // [String] value || uid
          field = value
            // [String] ASC || DESC
          orderFlag = ASC
          html {
            str_asc  =
            str_desc =
          }
        }
        sql {
            // [String, SQL] And where statement. Example: tt_news_cat.author.field LIKE 'A%' AND tt_news.uid < 99
          andWhere =
        }
      }
    }
      // Displays the no-item-message, if SQL query will return an empty result
    noItemMessage = TEXT
    noItemMessage {
      data = LLL:EXT:browser/pi1/locallang.xml:phrase_norecord
      wrap = <p class="noItemMessage">|</p>
    }
  }
}
  // plugin.tx_browser_pi1: displayList