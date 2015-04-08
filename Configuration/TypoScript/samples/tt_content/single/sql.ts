plugin.tx_browser_pi1 {
  views {
    single {
      43746 {
        select (
          tt_content.header,
          tt_content.header_layout,
          tt_content.tstamp,
          tt_content.CType,
          tt_content.bodytext,
          tt_content.list_type,
          tt_content.image,
          tt_content.imageheight,
          tt_content.imagewidth,
          tt_content.uid
)
        functions {
          clean_up {
            csvTableFields (
              tt_content.imageheight,
              tt_content.imagewidth,
              tt_content.uid
            )
          }
        }
      }
    }
  }
}