plugin.tx_browser_pi1 {
  views {
    list {
      1000 {
        filter {
          tt_news {
            datetime < plugin.tx_browser_pi1.displayList.master_templates.category_menu
            datetime {
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
          tt_news_cat {
            title < plugin.tx_browser_pi1.displayList.master_templates.selectbox
            title {
              first_item {
                cObject {
                  20 {
                    data >
                    value = Category
                    lang {
                      de = Kategorie
                      en = Category
                    }
                  }
                }
              }
              wrap = <div class="selectbox">|</div>
            }
          }
        }
      }
    }
  }
}