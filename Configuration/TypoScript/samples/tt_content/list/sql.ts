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
        orderBy = tt_content.header, tt_content.list_type
          // Link the header to the single view
        csvLinkToSingleView = tt_content.header
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