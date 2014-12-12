plugin.tx_browser_pi1 {

  javascript {
      // library, plugins, ui, cleanup
    jquery =
    jquery {
        // [STRING] Path to jQuery library
      library = EXT:browser/Resources/Public/JavaScript/jQuery/jquery-1.8.3.min.js
      library {
          // [Boolean] 1 (default): place JSS scripts in the footer section
        footer = {$plugin.tx_browser_pi1.jss.placement.footer}
          // [Boolean] 0 (default): link to the script file, 1: include the script inline
        inline = 0
      }
        // jstree, t3browser
      plugins =
      plugins {
        jstree {
          plugin = EXT:browser/Resources/Public/JavaScript/jQuery/plugins/jquery.jstree/jquery.jstree_1.0-rc3.js
          plugin {
              // [Boolean] 1 (default): place JSS scripts in the footer section
            footer = {$plugin.tx_browser_pi1.jss.placement.footer}
              // [Boolean] 0 (default): link to the script file, 1: include the script inline
            inline = 0
          }
          plugins {
            cookie = EXT:browser/Resources/Public/JavaScript/jQuery/plugins/jquery.jstree/_lib/jquery.cookie.js
            cookie {
                // [Boolean] 1 (default): place JSS scripts in the footer section
              footer = {$plugin.tx_browser_pi1.jss.placement.footer}
                // [Boolean] 0 (default): link to the script file, 1: include the script inline
              inline = 0
            }
          }
          library = EXT:browser/Resources/Public/JavaScript/jQuery/tx_browser_pi1_jstree_4.1.22.js
          library {
              // [Boolean] 1 (default): place JSS scripts in the footer section
            footer = {$plugin.tx_browser_pi1.jss.placement.footer}
              // [Boolean] 0: link to the script file, 1 (default): include the script inline
            inline = 1
            marker {
                // Id 1 of the div-wrap of the treeview (selector). Should correspond with displayList.master_templates.category_menu.treeview.html_id
              selector_01 = TEXT
              selector_01 {
                value = #{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_01}
              }
                // Id 2 of the div-wrap of the 2nd treeview (selector). Should correspond with displayList.master_templates.category_menu.treeview.html_id
              selector_02 = TEXT
              selector_02 {
                value = #{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_02}
              }
                // Id 3 of the div-wrap of the 3rd treeview (selector). Should correspond with displayList.master_templates.category_menu.treeview.html_id
              selector_03 = TEXT
              selector_03 {
                value = #{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_03}
              }
                // Id 4 of the div-wrap of the 4th treeview (selector). Should correspond with displayList.master_templates.category_menu.treeview.html_id
              selector_04 = TEXT
              selector_04 {
                value = #{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_04}
              }
                // Id 5 of the div-wrap of the 5th treeview (selector). Should correspond with displayList.master_templates.category_menu.treeview.html_id
              selector_05 = TEXT
              selector_05 {
                value = #{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_05}
              }
                // Dots for selector 1
              dots_01 = TEXT
              dots_01 {
                  // [Boolean] true: set dots; false: don't set dots
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots}
              }
                // Dots for selector 2
              dots_02 = TEXT
              dots_02 {
                  // [Boolean] true: set dots; false: don't set dots
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots}
              }
                // Dots for selector 3
              dots_03 = TEXT
              dots_03 {
                  // [Boolean] true: set dots; false: don't set dots
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots}
              }
                // Dots for selector 4
              dots_04 = TEXT
              dots_04 {
                  // [Boolean] true: set dots; false: don't set dots
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots}
              }
                // Dots for selector 5
              dots_05 = TEXT
              dots_05 {
                  // [Boolean] true: set dots; false: don't set dots
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots}
              }
                // Theme for selector 1
              icons_01 = TEXT
              icons_01 {
                  // [Boolean] true: set icons; false: don't set icons
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
              }
                // Icons for selector 2
              icons_02 = TEXT
              icons_02 {
                  // [Boolean] true: set icons; false: don't set icons
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
              }
                // Icons for selector 3
              icons_03 = TEXT
              icons_03 {
                  // [Boolean] true: set icons; false: don't set icons
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
              }
                // Icons for selector 4
              icons_04 = TEXT
              icons_04 {
                  // [Boolean] true: set icons; false: don't set icons
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
              }
                // Icons for selector 5
              icons_05 = TEXT
              icons_05 {
                  // [Boolean] true: set icons; false: don't set icons
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
              }
                // Path to theme for all selectors
              path_to_themes = TEXT
              path_to_themes {
                  // [String] Path to the folder, which contains the themes (with ending slash)
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.pathToTheme}
              }
                // Plugins for selector 1: category_menu or checkbox
              plugins_01 = COA
              plugins_01 {
                  // Plugins for the CATEGORY_MENU
                10 = TEXT
                10 {
                    // [STRING] list of plugins
                  value = "themes", "html_data", "cookies"
                  if {
                    value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.plugins}
                    equals = category_menu
                  }
                }
                  // Plugins for the CHECKBOX
                20 = TEXT
                20 {
                    // [STRING] list of plugins
                  value = "themes", "html_data", "checkbox",  "ui", "cookies"
                  if {
                    value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.plugins}
                    equals = checkbox
                  }
                }
              }
              plugins_02 < .plugins_01
                // Plugins for selector 2
              plugins_02 = COA
              plugins_03 < .plugins_01
                // Plugins for selector 3
              plugins_03 = COA
              plugins_04 < .plugins_01
                // Plugins for selector 4
              plugins_04 = COA
              plugins_05 < .plugins_01
                // Plugins for selector 5
              plugins_05 = COA
                // table.field of Selector 1: Example tx_greencars_manufacturer.title
              tablefield_01 = TEXT
              tablefield_01 {
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_01}
              }
                // table.field of Selector 2: Example tx_greencars_manufacturer.title
              tablefield_02 = TEXT
              tablefield_02 {
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_02}
              }
                // table.field of Selector 3: Example tx_greencars_manufacturer.title
              tablefield_03 = TEXT
              tablefield_03 {
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_03}
              }
                // table.field of Selector 4: Example tx_greencars_manufacturer.title
              tablefield_04 = TEXT
              tablefield_04 {
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_04}
              }
                // table.field of Selector 5: Example tx_greencars_manufacturer.title
              tablefield_05 = TEXT
              tablefield_05 {
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_05}
              }
                // Theme for selector 1
              theme_01 = TEXT
              theme_01 {
                  // [String] supported are: apple, classic, default, default-rtl
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}
              }
                // Theme for selector 2
              theme_02 = TEXT
              theme_02 {
                  // [String] supported are: apple, classic, default, default-rtl
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}
              }
                // Theme for selector 3
              theme_03 = TEXT
              theme_03 {
                  // [String] supported are: apple, classic, default, default-rtl
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}
              }
                // Theme for selector 4
              theme_04 = TEXT
              theme_04 {
                  // [String] supported are: apple, classic, default, default-rtl
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}
              }
                // Theme for selector 5
              theme_05 = TEXT
              theme_05 {
                  // [String] supported are: apple, classic, default, default-rtl
                value = {$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}
              }
            }
          }
        }
        t3browser {
            // [String] Path to the jss file of the t3browser plugin
          plugin = EXT:browser/Resources/Public/JavaScript/jQuery/plugins/jquery.t3browser/jquery.t3browser-0.0.5.js
          plugin {
              // [Boolean] 1 (default): place JSS scripts in the footer section
            footer = {$plugin.tx_browser_pi1.jss.placement.footer}
              // [Boolean] 0 (default): link to the script file, 1: include the script inline
            inline = 0
          }
          library = EXT:browser/Resources/Public/JavaScript/jQuery/tx_browser_pi1-0.0.7.js
          library {
              // [Boolean] 1 (default): place JSS scripts in the footer section
            footer = {$plugin.tx_browser_pi1.jss.placement.footer}
              // [Boolean] 0: link to the script file, 1 (default): include the script inline
            inline = 1
            marker {
              ajax_url_single = TEXT
              ajax_url_single {
                dataWrap = {getIndpEnv:TYPO3_SITE_URL}|
                typolink {
                  parameter = {page:uid},{$plugin.tx_browser_pi1.typeNum.ajaxPageObj}
                  parameter {
                    insertData = 1
                  }
                  //additionalParams = &tx_browser_pi1[showUid]=###SHOWUID###&###CHASH###
                  additionalParams = &tx_browser_pi1[showUid]={GP:tx_browser_pi1|showUid}
                  additionalParams {
                    insertData = 1
                  }
                  returnLast = url
                }
              }
                // Enable/Disable JSS alerts
              t3browseralert = TEXT
              t3browseralert {
                value = {$plugin.tx_browser_pi1.jQuery.debugging.alerts.enable}
              }
              typenum_ajax = TEXT
              typenum_ajax {
                value = {$plugin.tx_browser_pi1.typeNum.ajaxPageObj}
              }
              typenum_csv = TEXT
              typenum_csv {
                value = {$plugin.tx_browser_pi1.typeNum.csvPageObj}
              }
            }
          }
            // [STRING] Path to the jss file of the t3browser localisation. ###LANG### will be replacedwhile runtime
          localisation = EXT:browser/Resources/Public/JavaScript/jQuery/plugins/jquery.t3browser/localisation.###LANG###.js
          localisation {
              // [Boolean] 1 (default): place JSS scripts in the footer section
            footer = {$plugin.tx_browser_pi1.jss.placement.footer}
              // [Boolean] 0 (default): link to the script file, 1: include the script inline
            inline = 0
          }
        }
      }
        // [STRING] Path to the jss file of the user interface (ui). All features are enabled in the default script file!
      ui = EXT:browser/Resources/Public/JavaScript/jQuery/ui/jquery-ui-1.8.14.custom.min.js
      ui {
          // [Boolean] 1 (default): place JSS scripts in the footer section
        footer = {$plugin.tx_browser_pi1.jss.placement.footer}
          // [Boolean] 0 (default): link to the script file, 1: include the script inline
        inline = 0
      }
      cleanup {
        library = EXT:browser/Resources/Public/JavaScript/jQuery/tx_browser_pi1_cleanup_4.2.0.js
        library {
            // [Boolean] 1 (default): place JSS scripts in the footer section
          footer = {$plugin.tx_browser_pi1.jss.placement.footer}
            // [Boolean] 0: link to the script file, 1 (default): include the script inline
          inline = 1
          marker < plugin.tx_browser_pi1.javascript.jquery.plugins.jstree.library.marker
            // marker is copied by plugins.jstree.library.marker
          marker =
        }
      }
    }
    ajax {
        // [STRING] Path to Javascript file with ajax methods
      file    = EXT:browser/Resources/Public/JavaScript/tx_browser_pi1_ajax-0.0.5.js
      file {
          // [Boolean] 1 (default): place JSS scripts in the footer section
        footer = {$plugin.tx_browser_pi1.jss.placement.footer}
          // [Boolean] 0 (default): link to the script file, 1: include the script inline
        inline = 0
      }
        // [STRING] Path to Javascript file with ajax language values
      fileLL  = EXT:browser/Resources/Public/JavaScript/tx_browser_pi1_ajax_languages.js
      fileLL {
          // [Boolean] 1 (default): place JSS scripts in the footer section
        footer = {$plugin.tx_browser_pi1.jss.placement.footer}
          // [Boolean] 0 (default): link to the script file, 1: include the script inline
        inline = 0
      }
      html {
        marker {
            // [STRING / UPPERCASE] Name of the class in CHECKBOX, RADIOBUTTONS, SELECTBOX.
          ajax_onchange = ONCHANGE
        }
      }
        // [PAGE] page object
      page = PAGE
      page {
        typeNum = 0
        config {
          disableAllHeaderCode  = 1
          xhtml_cleaning        = 0
          admPanel              = 0
        }
        10 = CONTENT
        10 {
          table = tt_content
          select {
              // only use current page
            pidInList     = this
              // only use current language
            languageField = sys_language_uid
            andWhere {
              cObject = COA
              cObject {
                  // choose all Browser plugins...
                10 = TEXT
                10 {
                  value = list_type = 'browser_pi1'
                }
                  // if an UID is provided in the querystring, choose only that plugin
                20 = TEXT
                20 {
                  data = GP:tx_browser_pi1|plugin
                  if {
                    isTrue {
                      data = GP:tx_browser_pi1|plugin
                    }
                  }
                  outerWrap = AND uid = |
                }
              }
            }
          }
        }
      }
      jQuery {
          // [PAGE] page object II
        default = PAGE
        default {
          typeNum = {$plugin.tx_browser_pi1.typeNum.ajaxPageObj}
          config {
            disableAllHeaderCode  = 1
            xhtml_cleaning        = 0
            admPanel              = 0
            metaCharset           = UTF-8
          }
          10 < styles.content.get
        }
        en < .default
        en {
          config {
              // [Integer] Uid of the language. See record in sys_language
            sys_language_uid      = {$plugin.tx_browser_pi1.typeNum.sys_language_en}
              // [Keyword] page: content_fallback, strict, ignore
            sys_language_mode     = content_fallback
              // [Boolean / Keyword] records: 0, 1, hideNonTranslated
            sys_language_overlay  = 1
            language              = en
            locale_all            = en_GB
            htmlTag_langKey       = en
          }
          10 < styles.content.get
        }
        de < .en
        de {
          config {
              // [Integer] Uid of the language. It has to corresponded with the id of the record in sys_language
            sys_language_uid      = {$plugin.tx_browser_pi1.typeNum.sys_language_de}
            language              = de
            locale_all            = de_DE
            htmlTag_langKey       = de
          }
        }
        fr < .en
        fr {
          config {
              // [Integer] Uid of the language. It has to corresponded with the id of the record in sys_language
            sys_language_uid      = {$plugin.tx_browser_pi1.typeNum.sys_language_fr}
            language              = fr
            locale_all            = fr_FR
            htmlTag_langKey       = fr
          }
        }
        it < .en
        it {
          config {
              // [Integer] Uid of the language. It has to corresponded with the id of the record in sys_language
            sys_language_uid      = {$plugin.tx_browser_pi1.typeNum.sys_language_it}
            language              = it
            locale_all            = it_IT
            htmlTag_langKey       = it
          }
        }
        es < .en
        es {
          config {
              // [Integer] Uid of the language. It has to corresponded with the id of the record in sys_language
            sys_language_uid      = {$plugin.tx_browser_pi1.typeNum.sys_language_es}
            language              = es
            locale_all            = es_ES
            htmlTag_langKey       = es
          }
        }
      }
    }
    general {
        // [STRING] Path to Javascript file general methods
      file = EXT:browser/Resources/Public/JavaScript/tx_browser_pi1.js
      file {
          // [Boolean] 1 (default): place JSS scripts in the footer section
        footer = {$plugin.tx_browser_pi1.jss.placement.footer}
          // [Boolean] 0 (default): link to the script file, 1: include the script inline
        inline = 0
      }
    }
  }
}