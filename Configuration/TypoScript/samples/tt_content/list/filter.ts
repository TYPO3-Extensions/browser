plugin.tx_browser_pi1 {
  views {
    list {
      43746 {
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
      }
    }
  }
}