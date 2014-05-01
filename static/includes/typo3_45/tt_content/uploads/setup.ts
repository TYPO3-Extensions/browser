plugin.tx_browser_pi1 {
  tt_content {
    uploads {
      20 = USER
      20 {
        userFunc = tx_browser_cssstyledcontent->render_uploads
        userFunc {
            // add the value of a field to another field in cObj->data
          cObjDataFieldWrapper =
          cObjDataFieldWrapper {
              // Adds the value from table.field to filelink_size. e.g: tx_org_downloads.documentssize
            filelink_size =
              // Adds the value from table.field to imagecaption. e.g: tx_org_downloads.documentscaption
            imagecaption =
              // Adds the value from table.field to layout. e.g: tx_org_downloads.documentslayout
            layout =
              // Adds the value from table.field to tx_flipit_layout. e.g: tx_org_downloads.tx_flipit_layout
            tx_flipit_layout =
              // Adds the value from table.field to tx_flipit_quality. e.g: tx_org_downloads.tx_flipit_quality
            tx_flipit_quality =
              // Adds the value from table.field to tx_flipit_pagelist. e.g: tx_org_downloads.tx_flipit_pagelist
            tx_flipit_pagelist =
          }
            // Enable the DRS (you need devlog)
          drs = TEXT
          drs {
            value = {$plugin.tx_browser_pi1.drs.tt_content.uploads.20}
          }
        }
        field = media
        filePath.field = select_key
          // Don't outerWrap with <table> by default
        outerWrap = |
        # Rendering for each file (e.g. rows of the table) as a cObject
        itemRendering = COA
        itemRendering {
          wrap = <tr class="tr-odd tr-first">|</tr> |*| <tr class="tr-even">|</tr> || <tr class="tr-odd">|</tr> |*|

          10 = TEXT
          10.data = register:linkedIcon
          10.wrap = <td class="csc-uploads-icon">|</td>
          10.if.isPositive.field = layout

          20 = COA
          20.wrap = <td class="csc-uploads-fileName">|</td>
          20.1 = TEXT
          20.1 {
            data = register:linkedLabel
            wrap = <p>|</p>
          }
          20.2 = TEXT
          20.2 {
            data = register:description
            wrap = <p class="csc-uploads-description">|</p>
            required = 1
            htmlSpecialChars = 1
          }

          30 = TEXT
          30.if.isTrue.field = filelink_size
          30.data = register:fileSize
          30.wrap = <td class="csc-uploads-fileSize">|</td>
          30.bytes = 1
          30.bytes.labels = {$styles.content.uploads.filesizeBytesLabels}
        }
        useSpacesInLinkText = 0
        stripFileExtensionFromLinkText = 0
        color {
          default =
          1 = #EDEBF1
          2 = #F5FFAA
        }
        tableParams_0 {
          border =
          cellpadding =
          cellspacing =
        }
        tableParams_1 {
          border =
          cellpadding =
          cellspacing =
        }
        tableParams_2 {
          border =
          cellpadding =
          cellspacing =
        }
        tableParams_3 {
          border =
          cellpadding =
          cellspacing =
        }
        linkProc {
          target = _blank
          jumpurl = {$styles.content.uploads.jumpurl}
          jumpurl.secure = {$styles.content.uploads.jumpurl_secure}
          jumpurl.secure.mimeTypes = {$styles.content.uploads.jumpurl_secure_mimeTypes}
          removePrependedNumbers = 1

          iconCObject = IMAGE
          iconCObject.file.import.data = register : ICON_REL_PATH
          iconCObject.file.width = 150
        }
        filesize {
          bytes = 1
          bytes.labels = {$styles.content.uploads.filesizeBytesLabels}
        }
        stdWrap {
          editIcons = tt_content: media, layout [table_bgColor|table_border|table_cellspacing|table_cellpadding], filelink_size, imagecaption
          editIcons.iconTitle.data = LLL:EXT:css_styled_content/pi1/locallang.xml:eIcon.filelist

          prefixComment = 2 | File list:
        }
      }
    }
  }
}