plugin.tx_browser_pi1 {
  views {
    list {
      43746 {
        tt_content {
          image = FILES
          image {
            references {
              table = tt_content
              uid {
                field = tt_content.uid
              }
              fieldName = image
            }
            begin = 0
            maxItems = 1
            renderObj < plugin.tx_browser_pi1.displayList.master_templates.tableFields.image.0.default
            renderObj {
              file {
                import {
                  data = file:current:uid
                  stdWrap >
                }
                treatIdAsReference = 1
              }
            }
          }
          list_type = COA
          list_type {
              // If it is default content
            10 = TEXT
            10 {
              if {
                isFalse {
                  field = tt_content.list_type
                }
              }
              value = No plugin
              lang {
                de = Kein Plugin
                en = No plugin
              }
            }
              // If it is a plugin
            20 = TEXT
            20 {
              if {
                isTrue {
                  field = tt_content.list_type
                }
              }
              field = tt_content.list_type
            }
          }
        }
      }
    }
  }
}