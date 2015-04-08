plugin.tx_browser_pi1 {
  views {
    list {
      43746 {
        select (
          tt_content.header,
          tt_content.bodytext,
          tt_content.list_type,
          tt_content.image,
          tt_content.uid
        )
        orderBy             = tt_content.header, tt_content.list_type
        csvLinkToSingleView = tt_content.header
        functions {
          clean_up {
            csvTableFields = tt_content.uid
          }
        }
      }
    }
  }
}