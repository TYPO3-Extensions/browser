plugin.tx_browser_pi1 {
  displayList {
    master_templates {
        // 140703: empty statement: for proper comments only
      modules {
      }
        // download
      modules =
      modules {
        download {
          thumbnails {
            withLinkToListView < tt_content.uploads.20
            withLinkToListView {
              userFunc = tx_browser_cssstyledcontent->render_uploads
              userFunc {
                  // Enable the DRS (you need devlog)
                drs = TEXT
                drs {
                  value = {$plugin.tx_browser_pi1.downloadmodule.drs}
                }
              }
                // Don't outerWrap the thumbnail with <table> by default
              outerWrap = |
              field         >
              filePath      >
              color         >
              tableParams_0 >
              tableParams_1 >
              tableParams_2 >
              tableParams_3 >
              itemRendering >
              itemRendering = TEXT
              itemRendering {
                data = register:linkedIcon
                wrap (
                  <div class="csc-uploads-thumbnail csc-uploads-thumbnail-first">|</div>
                  |*|
                  <div class="csc-uploads-thumbnail csc-uploads-thumbnail-even">|</div>
                  ||
                  <div class="csc-uploads-thumbnail">|</div>
                  |*|
                  <div class="csc-uploads-thumbnail csc-uploads-thumbnail-last">|</div>
)
              }
              linkProc {
                iconCObject {
                  file {
                    width = {$plugin.tx_browser_pi1.downloadmodule.thumbnailListViewMaxW}
                  }
                }
                  // Render the thumbnail
                tx_browser_pi1 = COA
                tx_browser_pi1 {
                    // Link with preview or application icon
                  10 = IMAGE
                  10 {
                    file {
                      import {
                        data = register : ICON_REL_PATH
                      }
                      width = {$plugin.tx_browser_pi1.downloadmodule.thumbnailListViewMaxW}
                    }
                    imageLinkWrap = 1
                    imageLinkWrap {
                      enable = 1
                      typolink {
                        parameter = {$plugin.tx_browser_pi1.downloadmodule.pageUid} - linktosingle
                        title = Download: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
                        additionalParams  = &tx_browser_pi1[{$plugin.tx_browser_pi1.downloadmodule.urlParam}]=###{$plugin.tx_browser_pi1.downloadmodule.uid}###
                        useCacheHash      = 1
                      }
                    }
                  }
                    // Link devider
                  20 = TEXT
                  20 {
                    value = //**//
                  }
                    // Link with label
                  30 = TEXT
                  30 {
                    data = register : filename
                    typolink {
                      parameter         = ###{$plugin.tx_browser_pi1.downloadmodule.pageUid}### - linktosingle
                      title = Download: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
// 120515, dwildt: Bug: TYPO3 renders content as "TEXT"
//                      title = TEXT
//                      title {
//                        value = Download: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
//                        lang {
//                          de  = Herunterladen: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
//                          en  = Download: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
//                        }
//                      }
                      additionalParams  = &tx_browser_pi1[{$plugin.tx_browser_pi1.downloadmodule.urlParam}]=###{$plugin.tx_browser_pi1.downloadmodule.uid}###
                      useCacheHash      = 1
                    }
                  }
                }
              }
              tableField = {$plugin.tx_browser_pi1.downloadmodule.tableField}
              fields {
                from_path = ###{$plugin.tx_browser_pi1.downloadmodule.path}###
                files     = ###{$plugin.tx_browser_pi1.downloadmodule.files}###
                layout    >
              }
              stdWrap {
                if.isTrue = ###{$plugin.tx_browser_pi1.downloadmodule.files}###{$plugin.tx_browser_pi1.downloadmodule.path}###
              }
            }
            withDownloadLink < .withLinkToListView
            withDownloadLink {
              linkProc {
                tx_browser_pi1 {
                  10 {
                    file {
                      width = {$plugin.tx_browser_pi1.downloadmodule.thumbnailSingleViewMaxW}
                    }
                    imageLinkWrap {
                      typolink {
                        parameter         = ###{$plugin.tx_browser_pi1.downloadmodule.pageUid}###,{$plugin.tx_browser_pi1.typeNum.downloadPageObj} - linktosingle
                        additionalParams  = &tx_browser_pi1[plugin]=###TT_CONTENT.UID###&tx_browser_pi1[file]=single.{$plugin.tx_browser_pi1.downloadmodule.uidSingleView}.tx_dam.###TX_DAM.UID###.file_name.###KEY###
                        useCacheHash      = 1
                      }
                    }
                  }
                  30 = TEXT
                  30 {
                    data = register : filename
                    typolink {
                      parameter         = ###{$plugin.tx_browser_pi1.downloadmodule.pageUid}###,{$plugin.tx_browser_pi1.typeNum.downloadPageObj} - linktosingle
                      additionalParams  = &tx_browser_pi1[plugin]=###TT_CONTENT.UID###&tx_browser_pi1[file]=single.{$plugin.tx_browser_pi1.downloadmodule.uidSingleView}.tx_dam.###TX_DAM.UID###.file_name.###KEY###
                      useCacheHash      = 1
                    }
                  }
                }
              }
            }
          }
          textlinks {
            withLinkToListView = TEXT
            withLinkToListView {
              value = Proceed &raquo;
              lang {
                de  = Weiter &raquo;
                en  = Proceed &raquo;
              }
              typolink {
                parameter         = {$plugin.tx_browser_pi1.downloadmodule.pageUid} - linktosingle
                title             = Download: '###{$plugin.tx_browser_pi1.downloadmodule.titles}###'
                additionalParams  = &tx_browser_pi1[{$plugin.tx_browser_pi1.downloadmodule.urlParam}]=###{$plugin.tx_browser_pi1.downloadmodule.uid}###
                useCacheHash      = 1
              }
            }
            withDownloadLink = TEXT
            withDownloadLink {
              value = Download &raquo;
              lang {
                de  = Download &raquo;
                en  = Download &raquo;
              }
              typolink {
                parameter         = ###{$plugin.tx_browser_pi1.downloadmodule.pageUid}###,{$plugin.tx_browser_pi1.typeNum.downloadPageObj} - linktosingle
                additionalParams  = &tx_browser_pi1[plugin]=###TT_CONTENT.UID###&tx_browser_pi1[file]=single.{$plugin.tx_browser_pi1.downloadmodule.uidSingleView}.tx_dam.###TX_DAM.UID###.file_name.###KEY###
                useCacheHash      = 1
              }
            }
          }
        }
      }
    }
  }
}