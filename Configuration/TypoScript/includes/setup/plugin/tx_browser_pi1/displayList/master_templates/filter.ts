plugin.tx_browser_pi1 {
  displayList {
      // 140703: empty statement: for proper comments only
    master_templates {
    }
      // category_menu, checkbox, radiobuttons, selectbox, treeview, areas, map, modules, tableFields
    master_templates =
    master_templates {
        // Master template for a category menu
      category_menu = CATEGORY_MENU
      category_menu {
          // If condition is met, filter will display. If not, filter will hide.
        condition = TEXT
        condition {
            // True (default)
          value = 1
        }
        treeview {
            // [Boolean] Disable the treeview. Enable is recommended. Has an effect only in case of cps_tcatree and a proper TCA configuration.
          enabled = TEXT
          enabled {
              // true, false (default)
            value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.enable}
          }
            // [String] HTML id of the treeview.
          html_id = TEXT
          html_id {
              // treeview (default)
            value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_01}
          }
            // [String] type: category_menu or checkbox. Property is for evaluation only.
          type = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.plugins}
        }
          // [String/optional] Name for the piVar. Name must be unique.
        nice_piVar =
          // [Boolean] 1 (default): Hits are counted. 0: Any hit is counted (best performance!)
        count_hits = 1
          // [String] OR || AND: OR (default)
        modeAndOr = OR
          // [Boolean] Should be added a default item?
        first_item = 1
        first_item {
            // [Boolean] 1 (default): First item will displayed ever. 0: First item will displayed in case of items only.
          display_wo_items = 1
            // Wrap the first filter item
          cObject = COA
          cObject {
              // Value from locallang.xml
            20 = TEXT
            20 {
              data = LLL:EXT:browser/pi1/locallang.xml:label_filter_firstitem_default
                // Append hits
              append = TEXT
              append {
                if {
                    // Display hits only in case of one hit at least
                  isTrue {
                    field = hits
                  }
                }
                field = hits
                noTrimWrap  = | (|)|
              }
            }
            wrap = <li><a href="###URL###"###CLASS######STYLE######TITLE###>|</a></li>
          }
            // [STRING, unique] Value attribute in the option tag (#11401)
          option_value = 0
        }
          // [Boolean]: 1: If value is changing, AJAX will reload the form. 0: No auto reload.
        ajax_onchange = 1
          // [String] Wrap the object
        wrap = <div class="category_menu">###TITLE###|</div>
        wrap {
            // [Integer] Space characters from left margin in the HTML code
          nice_html_spaceLeft = 12
          title_stdWrap {
              // [String] Title of the object. If it shouldn't displayed, please delete the title_stdWrap array
            value = ###TABLE.FIELD###
            wrap  = <div class="category_menu_title">|</div>
          }
            // [String] Wrap the items
          object = <ul>|</ul>
          object {
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 14
          }
            // [String] Wrap one item
          item = ###VALUE###
          item {
              // [Boolean] 0: Item without any hit will not be displayed. 1: Any item will be displayed.
            display_without_any_hit = 0
              // [Boolean] 1 (default): If hits aren't counted, the item will displayed (flag_displayInCaseOfNoCounting is set. See cObject). 0: Item won't displayed, if count_hits is 0.
            displayInCaseOfNoCounting = 1
              // [Boolean] 1: Items display the number of hits. 0: Items doesn't display any number of hits.
            display_hits = 1
            display_hits {
                // [Boolean] 1: Hits are displayed behind the item. 0: Hits are displayed in front of the item.
              behindItem = 1
              stdWrap {
                noTrimWrap = | (|)|
              }
                // [Boolean] 0 (default): Hit must be greater 0 for displaying. 1: Hit will displayed.
              display_empty_hits = 0
            }
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 16
              // [String] Class for a selected item
            selected = selected
              // [String] Default HTML class
            class = ###ITEM_SELECTED### ###ONCHANGE###
              // [String] Default HTML style
            style =
              // DEPRECATED - please use the cObject!
            wraps =
              // Wrap the filter item
            cObject = COA
            cObject {
                // Value in case of a value
              20 = TEXT
              20 {
                if {
                  isTrue {
                    field = value
                  }
                }
                field = value
                htmlSpecialChars  = 1
                crop              = 60 | ... | 1
              }
                // Value in case of no value
              21 = TEXT
              21 {
                if {
                  isTrue {
                    field = value
                  }
                  negate  = 1
                }
                data = LLL:EXT:browser/pi1/locallang.xml:label_categorymenu_noValue
              }
                // Hits
              30 = TEXT
              30 {
                if {
                    // Display hits only in case of one hit at least
                  isTrue {
                    field = hits
                  }
                }
                field = hits
                noTrimWrap  = | (|)|
              }
              if {
                isTrue {
                    // Display with hits only or in case of a tree view
                  cObject = COA
                  cObject {
                      // field = hits
                    10 = TEXT
                    10 {
                      field = hits
                    }
                      // field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    20 = TEXT
                    20 {
                      field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    }
                      // field = tx_browser_pi1.flag_treeview
                    30 = TEXT
                    30 {
                      field = tx_browser_pi1.flag_treeview
                    }
                  }
                }
              }
              wrap = <li><a href="###URL###"###CLASS######STYLE######TITLE###>|</a></li>
            }
          }
        }
        order {
            // [String] value || uid
          field = value
            // [String] ASC || DESC
          orderFlag = ASC
        }
        sql {
            // [String, SQL] And where statement. Example: tt_news_cat.author.field LIKE 'A%' AND tt_news.uid < 99
          andWhere =
        }
          // [BOOLEAN] 1: Display an area filter, 0: Don't display an area (default)
        area = 0
      }
        // Master template for a check box
      checkbox = CHECKBOX
      checkbox {
          // If condition is met, filter will display. If not, filter will hide.
        condition = TEXT
        condition {
            // True (default)
          value = 1
        }
          // [String/optional] Name for the piVar. Name must be unique.
        nice_piVar =
          // [Boolean] 1 (default): Hits are counted. 0: Any hit is counted (best performance!)
        count_hits = 1
          // [Boolean] Is a selecting obligatory?
        required = 0
          // [String] OR || AND: OR (default)
        modeAndOr = OR
          // [Boolean]: 1: If value is changing, AJAX will reload the form. 0: No auto reload.
        ajax_onchange = 1
        wrap (
<fieldset class="checkbox ###ONCHANGE###">
              <legend>###TITLE###</legend>
                |
            </fieldset>
)
        wrap {
            // [Integer] Space characters from left margin in the HTML code
          nice_html_spaceLeft = 12
          title_stdWrap {
              // [String] Title of the object. If it shouldn't displayed, please delete the title array
            value = ###TABLE.FIELD###
            wrap  = |
          }
            // [String] Wrap the items
          object = |
            // [String] Wrap one item
          item = ###VALUE###
          item {
              // DEPRECATED - please use the cObject! [Boolean] 0: Item without any hit will not be displayed. 1: Any item will be displayed.
            display_without_any_hit = 0
              // [Boolean] 1 (default): If hits aren't counted, the item will displayed (flag_displayInCaseOfNoCounting is set. See cObject). 0: Item won't displayed, if count_hits is 0.
            displayInCaseOfNoCounting = 1
              // DEPRECATED - please use the cObject! [Boolean] 1: Items display the number of hits. 0: Items doesn't display any number of hits.
            display_hits = 1
            display_hits {
                // [Boolean] 1: Hits are displayed behind the item. 0: Hits are displayed in front of the item.
              behindItem = 1
              stdWrap {
                noTrimWrap = | (|)|
              }
                // [Boolean] 0 (default): Hit must be greater 0 for displaying. 1: Hit will displayed.
              display_empty_hits = 0
            }
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 16
              // [String] HTML code for a selected item
            selected = checked="checked"
              // [String] Default HTML class
            class =
              // [String] Default HTML style
            style =
              // DEPRECATED - please use the cObject!
            wraps =
              // Wrap the filter item
            cObject = COA
            cObject {
                // Input
              10 = TEXT
              10 {
                value       = <input###CLASS######STYLE### type="checkbox" name="###TABLE.FIELD###" value="###UID###"###ITEM_SELECTED###>
                noTrimWrap  = | ||
              }
                // Value in case of a value
              20 = TEXT
              20 {
                if {
                  isTrue {
                    field = value
                  }
                }
                field = value
                htmlSpecialChars  = 1
                crop              = 60 | ... | 1
              }
                // Value in case of no value
              21 = TEXT
              21 {
                if {
                  isTrue {
                    field = value
                  }
                  negate  = 1
                }
                data = LLL:EXT:browser/pi1/locallang.xml:label_checkbox_noValue
              }
                // Hits
              30 = TEXT
              30 {
                if {
                    // Display hits only in case of one hit at least
                  isTrue {
                    field = hits
                  }
                }
                field = hits
                noTrimWrap  = | (|)|
              }
              if {
                isTrue {
                    // Display with hits only or in case of a tree view
                  cObject = COA
                  cObject {
                      // field = hits
                    10 = TEXT
                    10 {
                      field = hits
                    }
                      // field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    20 = TEXT
                    20 {
                      field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    }
                  }
                }
              }
            }
          }
            // [Integer] Maximum items per row. 0: unlimited
          itemsPerRow = 3
          itemsPerRow {
              // [String] Wrap the row
            wrap = <div class="row row-###EVEN_ODD###">|</div>
              // [String] if there aren't enough items to fill up a row
            noItemValue = &nbsp;
          }
        }
        order {
            // [String] value || uid
          field = value
            // [String] ASC || DESC
          orderFlag = ASC
        }
        sql {
            // [String, SQL] And where statement. Example: tt_news_cat.author.field LIKE 'A%' AND tt_news.uid < 99
          andWhere =
        }
          // [BOOLEAN] You have to allocate one of the areas of the mastertemplate
        area = 0
      }
        // Master template for radio buttons
      radiobuttons = RADIOBUTTONS
      radiobuttons {
          // If condition is met, filter will display. If not, filter will hide.
        condition = TEXT
        condition {
            // True (default)
          value = 1
        }
          // [String/optional] Name for the piVar. Name must be unique.
        nice_piVar =
          // [Boolean] 1 (default): Hits are counted. 0: Any hit is counted (best performance!)
        count_hits = 1
          // [Boolean] Is a selecting obligatory?
        required = 1
          // [String] OR || AND: OR (default)
        modeAndOr = OR
          // [Boolean]: 1: If value is changing, AJAX will reload the form. 0: No auto reload.
        ajax_onchange = 1
          // [String] Wrap the object
        wrap (
<fieldset class="radiobuttons ###ONCHANGE###">
              <legend>###TITLE###</legend>
                |
            </fieldset>
)
        wrap {
            // [Integer] Space characters from left margin in the HTML code
          nice_html_spaceLeft = 12
          title_stdWrap {
              // [String] Title of the object. If it shouldn't displayed, please delete the title array
            value = ###TABLE.FIELD###
            wrap  = |
          }
            // [String] Wrap the items
          object = |
            // [String] Wrap one item
          item = ###VALUE###
          item {
              // DEPRECATED - please use the cObject! [Boolean] 0: Item without any hit will not be displayed. 1: Any item will be displayed.
            display_without_any_hit = 0
              // [Boolean] 1 (default): If hits aren't counted, the item will displayed (flag_displayInCaseOfNoCounting is set. See cObject). 0: Item won't displayed, if count_hits is 0.
            displayInCaseOfNoCounting = 1
              // DEPRECATED - please use the cObject! [Boolean] 1: Items display the number of hits. 0: Items doesn't display any number of hits.
            display_hits = 1
            display_hits {
                // [Boolean] 1: Hits are displayed behind the item. 0: Hits are displayed in front of the item.
              behindItem = 1
              stdWrap {
                noTrimWrap = | (|)|
              }
                // [Boolean] 0 (default): Hit must be greater 0 for displaying. 1: Hit will displayed.
              display_empty_hits = 0
            }
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 16
              // [String] HTML code for a selected item
            selected = checked="checked"
              // [String] Default HTML class
            class =
              // [String] Default HTML style
            style =
              // DEPRECATED - please use the cObject!
            wraps =
              // Wrap the filter item
            cObject = COA
            cObject {
                // Input
              10 = TEXT
              10 {
                value       = <input###CLASS######STYLE### type="radio" name="###TABLE.FIELD###" value="###UID###"###ITEM_SELECTED###>
                noTrimWrap  = | ||
              }
                // Value in case of a value
              20 = TEXT
              20 {
                if {
                  isTrue = ###VALUE###
                }
                value             = ###VALUE###
                htmlSpecialChars  = 1
                crop              = 60 | ... | 1
              }
                // Value in case of no value
              21 = TEXT
              21 {
                if {
                  isTrue  = ###VALUE###
                  negate  = 1
                }
                data = LLL:EXT:browser/pi1/locallang.xml:label_selectbox_noValue
              }
                // Hits
              30 = TEXT
              30 {
                if {
                    // Display hits only in case of one hit at least
                  isTrue = hits
                }
                field       = hits
                noTrimWrap  = | (|)|
              }
              if {
                isTrue {
                    // Display with hits only or in case of a tree view
                  cObject = COA
                  cObject {
                      // field = hits
                    10 = TEXT
                    10 {
                      field = hits
                    }
                      // field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    20 = TEXT
                    20 {
                      field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    }
                  }
                }
              }
            }
          }
            // [Integer] Maximum items per row. 0: unlimited
          itemsPerRow = 3
          itemsPerRow {
              // [String] Wrap the row
            wrap = <div class="row row-###EVEN_ODD###">|</div>
              // [String] if there aren't enough items to fill up a row
            noItemValue = &nbsp;
          }
        }
        order {
            // [String] value || uid
          field = value
            // [String] ASC || DESC
          orderFlag = ASC
        }
        sql {
            // [String, SQL] And where statement. Example: tt_news_cat.author.field LIKE 'A%' AND tt_news.uid < 99
          andWhere =
        }
          // [BOOLEAN] You have to allocate one of the areas of the mastertemplate
        area = 0
      }
        // [Array] conf, content. Master template for a radialsearch with a sword input and a radius select box. You are needing the extension radialsearch!
      radialsearch = RADIALSEARCH
      radialsearch {
          // [Array] constanteditor
        conf =
        conf {
            // [Array] lat, lon, radius, sword
          constanteditor =
          constanteditor {
              // [BOOLEAN] true (recommended): If a coordinate is 0,0, it won't handled and won't displayed
            coordinatesNotEmpty = {$plugin.tx_browser_pi1.map.controlling.dontHandle00Coordinates}
              // Distance: field label for the distance. Field isn't part of your database. Field will generated while runtime and will added to SELECT statements. Example: distance, Don't use tableField syntax like tx_myext_main.distance: You will get an SQL error!
            distance    = {$plugin.tx_radialsearch_pi1.distance.fieldLabel}
              // [String] Latitude: table.field label for the latitude. Example: tx_myext_address.lat, tx_org_headquarters.mail_lat
            lat         = {$plugin.tx_browser_pi1.radialsearch.lat}
              // [String] Longitude: table.field label for the longitude. Example: tx_myext_address.lon, , tx_org_headquarters.mail_lon
            lon         = {$plugin.tx_browser_pi1.radialsearch.lon}
              // [String] Radius: HTML name of the select box with the radius options.
            radius      = {$plugin.tx_browser_pi1.radialsearch.radius}
              // [String] Display hits. Options: Within the radius only, Within and without the radius
            searchmode  = {$plugin.tx_browser_pi1.radialsearch.searchmode}
              // [String] Sword: HTML name of the input field for the search word (sword).
            sword       = {$plugin.tx_browser_pi1.radialsearch.sword}
          }
            // [Array] admin_code1, country_code
          filter < plugin.tx_radialsearch_pi1.res.js.tx_radialsearch_pi1.filter
            // [Array] GETPOST: fieldradius, fieldsword, parameter
          gp < plugin.tx_radialsearch_pi1.gp
        }
          // [Array] plugin.tx_radialsearch_pi1, plugin.tx_radialsearch_pi2
        content = COA
        content {
          wrap = <div class="filter-radialsearch">|</div>
          10 < plugin.tx_radialsearch_pi1
          20 < plugin.tx_radialsearch_pi2
        }
      }
        // Master template for a selectbox
      selectbox = SELECTBOX
      selectbox {
          // If condition is met, filter will display. If not, filter will hide.
        condition = TEXT
        condition {
            // True (default)
          value = 1
        }
          // [String/optional] Name for the piVar. Name must be unique.
        nice_piVar =
          // [Boolean] 1 (default): Hits are counted. 0: Any hit is counted (best performance!)
        count_hits = 1
          // [String] OR || AND: OR (default)
        modeAndOr = OR
          // [Boolean] Should be added a default item?
        first_item = 1
        first_item {
            // [Boolean] 1 (default): First item will displayed ever. 0: First item will displayed in case of items only.
          display_wo_items = 1
            // Wrap the first items
          cObject = COA
          cObject {
              // Value from locallang.xml
            20 = TEXT
            20 {
              data = LLL:EXT:browser/pi1/locallang.xml:label_filter_firstitem_default
                // Append hits
              append = TEXT
              append {
                if {
                  isTrue {
                      // Display hits only in case of one hit at least
                    field = hits
                  }
                }
                field = hits
                noTrimWrap  = | (|)|
              }
            }
            wrap = <option###CLASS######STYLE### value="###UID###">|</option>
          }
            // [STRING, unique] Value attribute in the option tag (#11401)
          option_value = 0
        }
          // [Boolean] Should it be possible to select more than one item?
        multiple  = 1
        multiple {
            // [String] HTML code for multiple
          selected  = multiple="multiple"
        }
          // [Integer] How many items should the select box display in the visible area?
        size = 1
          // [Boolean]: 1: If value is changing, AJAX will reload the form. 0: No auto reload.
        ajax_onchange = 1
          // [String] Wrap the object
        wrap = <div class="selectbox">###TITLE###|</div>
        wrap {
            // [Integer] Space characters from left margin in the HTML code
          nice_html_spaceLeft = 12
          title_stdWrap {
              // [String] Title of the object. If it shouldn't displayed, please delete the title array
            value = ###TABLE.FIELD###
            wrap  = <div class="selectbox_title">|</div>
          }
            // [String] Wrap the items
          object = <select class="###ONCHANGE###" name="###TABLE.FIELD###" id="###ID###" size="###SIZE###"###MULTIPLE###>|</select>
          object {
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 14
          }
            // [String] Wrap one item
          item = ###VALUE###
          item {
              // DEPRECATED - please use the cObject! [Boolean] 0: Item without any hit will not be displayed. 1: Any item will be displayed.
            display_without_any_hit = 0
              // [Boolean] 1 (default): If hits aren't counted, the item will displayed (flag_displayInCaseOfNoCounting is set. See cObject). 0: Item won't displayed, if count_hits is 0.
            displayInCaseOfNoCounting = 1
              // DEPRECATED - please use the cObject! [Boolean] 1: Items display the number of hits. 0: Items doesn't display any number of hits.
            display_hits = 1
            display_hits {
                // [Boolean] 1: Hits are displayed behind the item. 0: Hits are displayed in front of the item.
              behindItem = 1
              stdWrap {
                noTrimWrap = | (|)|
              }
                // [Boolean] 0 (default): Hit must be greater 0 for displaying. 1: Hit will displayed.
              display_empty_hits = 0
            }
              // [Integer] Space characters from left margin in the HTML code
            nice_html_spaceLeft = 16
              // [String] HTML code for a selected item
            selected = selected="selected"
              // [String] Default HTML class
            class =
              // [String] Default HTML style
            style =
              // DEPRECATED - please use the cObject!
            wraps =
              // Wrap the filter item
            cObject = COA
            cObject {
                // Value in case of a value
              20 = TEXT
              20 {
                if {
                  isTrue {
                    field = value
                  }
                }
                field             = value
                htmlSpecialChars  = 1
                crop              = 60 | ... | 1
              }
                // Value in case of no value
              21 = TEXT
              21 {
                if {
                  isTrue {
                    field = value
                  }
                  negate  = 1
                }
                data = LLL:EXT:browser/pi1/locallang.xml:label_selectbox_noValue
              }
                // Hits
              30 = TEXT
              30 {
                if {
                  isTrue {
                      // Display hits only in case of one hit at least
                    field = hits
                  }
                }
                field = hits
                noTrimWrap  = | (|)|
              }
              if {
                isTrue {
                    // Display with hits only or in case of a tree view
                  cObject = COA
                  cObject {
                      // field = hits
                    10 = TEXT
                    10 {
                      field = hits
                    }
                      // field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    20 = TEXT
                    20 {
                      field = tx_browser_pi1.flag_displayInCaseOfNoCounting
                    }
                  }
                }
              }
              wrap = <option###CLASS######STYLE### value="###UID###"###ITEM_SELECTED###>|</option>
            }
          }
        }
        order {
            // [String] value || uid
          field = value
            // [String] ASC || DESC
          orderFlag = ASC
        }
        sql {
            // [String, SQL] And where statement. Example: tt_news_cat.author.field LIKE 'A%' AND tt_news.uid < 99
          andWhere =
        }
          // [BOOLEAN] You have to allocate one of the areas of the master template. Tutorial: http://typo3.org/extensions/repository/view/browser_tut_search_en/current/
        area = 0
      }
      treeview < .category_menu
        // Master template for a treeview in checkbox format. It is based on CATEGORY_MENU and not on CHECKBOX!
      treeview = TREEVIEW
      treeview {
        first_item.cObject.wrap = <a href="###URL###"###CLASS######STYLE######TITLE###>|</a>
        wrap.item.cObject.wrap  = <a href="###URL###"###CLASS######STYLE######TITLE###>|</a>
      }
        // sample_currency, sample_period
      areas =
      areas {
        sample_currency = 1
        sample_currency {
          faq = Tutorial: http://typo3.org/extensions/repository/view/browser_tut_search_en/current/
            // [BOOLEAN] 1: One field - with individual from-to-string, 0: Don't take it
          strings = 1
          strings {
            faq = FAQ: How to configure first_item, hits and nice_piVar?
            faq {
              first_item    = Properties for the first_item will be taken from first_item above
              display_hits  = Properties will be taken from wrap.item above
              nice_piVar    = Property will be taken from nice_piVar above
            }
            label_stdWrap {
              data = LLL:EXT:browser/pi1/locallang.xml:label_fromTo
            }
            options {
              faq = FAQ: How do I configure a currency area?
              faq {
                area      = Define valueFrom and valueTo. If one is empty it is unlimited.
                keys      = A key of an item should be an integer like: 10, 20, 30, 40, etc.
                10_to_30  = Example for three items of a currency area
              }
              fields {
                10 {
                  comment = Upto 10.000 EUR
                  valueFrom_stdWrap {
                    value =
                  }
                  valueTo_stdWrap {
                    value = 10000
                  }
                  value_stdWrap {
                    value = Upto 10.000 EUR
                    lang {
                      de = Bis 10.000 EUR
                    }
                  }
                }
                20 {
                  comment = From 10.000 EUR to 20.000 EUR
                  valueFrom_stdWrap {
                    value = 10000
                  }
                  valueTo_stdWrap {
                    value = 20000
                  }
                  value_stdWrap {
                    value = 10.000 - 20.000 EUR
                  }
                }
                30 {
                  comment = From 20.000 EUR without any limit
                  valueFrom_stdWrap {
                    value = 20000
                  }
                  valueTo_stdWrap {
                    value =
                  }
                  value_stdWrap {
                    value = From 20.000 EUR
                    lang {
                      de = Ab 20.000 EUR
                    }
                  }
                }
              }
            }
          }
        }
        sample_period = 1
        sample_period {
          faq = Tutorial: http://typo3.org/extensions/repository/view/browser_tut_search_en/current/
            // [BOOLEAN] 1: One field - with individual from-to-string, 0: Don't take it
          strings = 0
          strings {
            faq = FAQ: How to configure first_item, hits and nice_piVar?
            faq {
              first_item    = Properties for the first_item will be taken from first_item above
              display_hits  = Properties will be taken from wrap.item above
              nice_piVar    = Property will be taken from nice_piVar above
            }
            label_stdWrap {
              data = LLL:EXT:browser/pi1/locallang.xml:label_fromTo
            }
              // [STRING: number || string] number: sql query with >= and <=, string: sql query with LIKE ???
            sql = number
            options {
              faq = FAQ: How do I configure an area for a period?
              faq {
                area      = Define valueFrom and valueTo. If one is empty it is unlimited.
                keys      = A key of an item should be an integer like: 10, 20, 30, 40, etc.
              }
              fields {
                10 {
                  comment = Before 2010
                  valueFrom_stdWrap {
                    value =
                  }
                  valueTo_stdWrap {
                    value = 1262300400
                  }
                  value_stdWrap {
                    value = Before 2010
                    lang {
                      de = Vor 2010
                    }
                  }
                }
                20 {
                  comment = From 2010 to 2011
                  valueFrom_stdWrap {
                    value = 1262300400
                  }
                  valueTo_stdWrap {
                    value = 1293836400
                  }
                  value_stdWrap {
                    value = 2010 - 2011
                  }
                }
                30 {
                  comment = After 2011
                  valueFrom_stdWrap {
                    value = 1293836400
                  }
                  valueTo_stdWrap {
                    value =
                  }
                  value_stdWrap {
                    value = After 2011
                    lang {
                      de = Nach 2011
                    }
                  }
                }
              }
            }
          }
          interval = 1
          interval {
              // [BOOLEAN]: 1: dates are converted to timestamp. 0: undefined
            compare_wiTimeStamp = 1
              // [STRING] 'day', 'week', 'month' or 'year'
            case = year
            day {
              start_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: last Monday, -1 day, now, today 0:00, next Monday
                  value = this week
                }
              }
              selected_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: last Monday, -1 day, now, today 0:00, next Monday
                  value = today 0:00
                }
              }
              times_stdWrap {
                  // [INTEGER] Display the period n times
                value = 7
              }
              value_stdWrap {
                  // [STRING] Leave it empty! It will be handled while runtime.
                value =
                  // [STRING] See php method strftime
                strftime = %A
              }
              url_stdWrap {
                  // PHP strftime: %Y%m%d example: 20101231
                strftime      = %Y%m%d
                rawUrlEncode  = 1
              }
            }
            week {
              start_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: -3 weeks, now, today 0:00, next week
                  value = last week
                }
              }
              selected_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: last Monday, -1 day, now, today 0:00, next Monday
                  value = today 0:00
                }
              }
              times_stdWrap {
                  // [INTEGER] Display the period n times
                value = 6
              }
              firstday_stdWrap {
                  // [INTEGER] 1: Mon, 2: Thu, ... 7: Sun
                value = 1
              }
              value_stdWrap {
                  // [STRING] Leave it empty! It will be handled while runtime.
                value =
                  // [STRING] See php method strftime
                strftime = %V. ###MY_WEEK###
              }
              url_stdWrap {
                  // PHP strftime: %Y%V example: 201053
                strftime      = %Y%V
                rawUrlEncode  = 1
              }
            }
            month {
              start_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: January, -1 month, now, today 0:00, next month
                  value = now
                }
              }
              selected_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: last Monday, -1 day, now, today 0:00, next Monday
                  value = today 0:00
                }
              }
              times_stdWrap {
                  // [INTEGER] Display the period n times
                value = 6
              }
              value_stdWrap {
                  // [STRING] Leave it empty! It will be handled while runtime.
                value =
                  // [STRING] See php method strftime
                strftime = %b %y
              }
              url_stdWrap {
                  // PHP strftime: %B_%Y example: december_2010
                strftime      = %B_%Y
                rawUrlEncode  = 1
              }
            }
            year {
              start_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: -1 year, 2010, now, today 0:00, next year
                  value = -1 year
                }
              }
              selected_period {
                  // [BOOLEAN] 1: Value is handled after stdWrap with php strtotime. 0: stdWrap only.
                use_php_strtotime = 1
                stdWrap {
                    // Examples with strtotime: last Monday, -1 day, now, today 0:00, next Monday
                  value = today 0:00
                }
              }
              times_stdWrap {
                  // [INTEGER] Display the period n times
                value = 3
              }
              value_stdWrap {
                  // [STRING] Leave it empty! It will be handled while runtime.
                value =
                  // [STRING] See php method strftime
                strftime = %Y
              }
              url_stdWrap {
                  // PHP strftime: %Y example: 2010
                strftime      = %Y
                rawUrlEncode  = 1
              }
            }
          }
        }
      }
    }
  }
}