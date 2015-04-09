plugin.tx_browser_pi1 {
  views {
    list {
      43746 {
          // [String] Select clause (don't confuse it with the SQL select)
        select (
          tt_content.header,
          tt_content.bodytext,
          tt_content.list_type,
          tt_content.image,
          tt_content.uid
        )
          // Order of the records
        orderBy             = tt_content.header, tt_content.list_type
          // Don't link any field automatically with the link to the single view
        csvLinkToSingleView = dummy
          // Don't display fields as columns in the table
        functions {
          clean_up {
            csvTableFields = tt_content.uid
          }
        }
      }
    }
  }
}