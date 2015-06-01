plugin.tx_browser_pi1 {
  displaySingle {
    master_templates {
    }
      // tableFields
    master_templates =
    master_templates {
        // 140703: empty statement: for proper comments only
      tableFields {
      }
        // vCard
      tableFields =
      tableFields {
          // 140707: empty statement: for proper comments only
        vCard {
        }
          // 0
        vCard =
        vCard {
            // BEGIN, VERSION, content, END
          0 = COA
          0 {
              // BEGIN:
            10 = TEXT
            10 {
              char = 10
              wrap = BEGIN:VCARD|
            }
              // VERSION:
            20 = TEXT
            20 {
              char = 10
              wrap = VERSION:4.0|
            }
              // content: General Properties, Identification Properties, Delivery Addressing Properties, Communications Properties, Geographical Properties, Organizational Properties, Explanatory Properties
            30 = COA
            30 {
                // General Properties: KIND, SOURCE
              10 = COA
              10 {
                  // KIND:individual
                10 = TEXT
                10 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  value = individual
                  wrap  = KIND:|
                }
                  // SOURCE:
                20 = TEXT
                20 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  typolink {
                      // page:uid
                    parameter = TEXT
                    parameter {
                      data = page:uid
                    }
                    additionalParams {
                      field = {$plugin.tx_browser_pi1.templates.listview.url.0.record}
                      wrap  = &tx_browser_pi1[{$plugin.tx_browser_pi1.navigation.showUid}]=|&type={$plugin.tx_browser_pi1.typeNum.vCardPageObj}
                    }
                    forceAbsoluteUrl  = 1
                    useCacheHash      = 0
                    returnLast        = url
                  }
                  wrap = SOURCE:|
                }
              }
                // Identification Properties: FN, N
              20 = COA
              20 {
                  // FN:
                10 = TEXT
                10 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.fn}
                  required  = 1
                  wrap      = FN:|
                }
                  // N:
                20 = COA
                20 {
                    // last name
                  10 = TEXT
                  10 {
                    field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.n.1}
                    wrap  = |;
                  }
                    // first Name
                  20 = TEXT
                  20 {
                    field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.n.2}
                    wrap = |;
                  }
                    // [undefined]
                  30 = TEXT
                  30 {
                    field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.n.3}
                    wrap = |;
                  }
                    // title
                  40 = TEXT
                  40 {
                    field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.n.4}
                    wrap  = |;
                  }
                    // linefeed
                  50 = TEXT
                  50 {
                    char = 10
                  }
                  wrap = N:|
                }
              }
                // Delivery Addressing Properties: ADR
              30 = COA
              30 {
                  // the post office box;
                10 = TEXT
                10 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.1}
                  wrap  = |;
                  stdWrap {
                    replacement {
                        // Move , to \,
                      10 = TEXT
                      10 {
                        search  = ,
                        replace = \,
                      }
                        // Move ; to \;
                      20 = TEXT
                      20 {
                        search  = ;
                        replace = \;
                      }
                    }
                  }
                }
                  // extended address (e.g., apartment or suite number);
                20 < .10
                20 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.2}
                }
                  // street address
                30 < .10
                30 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.3}
                }
                  // locality (e.g., city);
                40 < .10
                40 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.4}
                }
                  // region (e.g., state or province);
                50 < .10
                50 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.5}
                }
                  // postal code
                60 < .10
                60 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.6}
                }
                  // country name
                70 < .10
                70 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.adr.7}
                }
                  // linefeed
                80 = TEXT
                80 {
                  char = 10
                }
                wrap = ADR;TYPE=work:|
              }
                // Geographical Properties: GEO:
              40 = COA
              40 {
                if {
                  isTrue {
                    field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.geo.lat} // {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.geo.lon}
                  }
                }
                  // latitude
                10 = TEXT
                10 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.geo.lat}
                  wrap  = |,
                }
                  // longitude
                20 = TEXT
                20 {
                  field = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.geo.lon}
                }
                  // linefeed
                30 = TEXT
                30 {
                  char = 10
                }
                wrap = GEO:geo:|
              }
                // Organizational Properties: ORG:, TITLE:
              50 = COA
              50 {
                  // ORG:
                10 = TEXT
                10 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.org}
                  required  = 1
                  wrap      = ORG:|
                  stdWrap {
                    replacement {
                        // Move , to \,
                      10 = TEXT
                      10 {
                        search  = ,
                        replace = \,
                      }
                        // Move ; to \;
                      20 = TEXT
                      20 {
                        search  = ;
                        replace = \;
                      }
                        // Move [linefeed] to \,
                      30 = TEXT
                      30 {
                        search {
                          char = 10
                        }
                        replace = \,
                        replace {
                          noTrimWrap = || |
                        }
                      }
                        // Remove [carriage return]
                      40 = TEXT
                      40 {
                        search {
                          char = 13
                        }
                        replace =
                      }
                    }
                  }
                }
                  // TITLE:
                20 = TEXT
                20 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.title}
                  required  = 1
                  wrap      = TITLE:|
                }
              }
                // Communications Properties: EMAIL, IMPP, TEL
              60 = COA
              60 {
                  // EMAIL;
                10 = TEXT
                10 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  field     = tx_org_staff.contact_email
                  field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.email.work}
                  required  = 1
                  wrap      = EMAIL;TYPE=work:|
                }
                  // IMPP;skype:
                20 = TEXT
                20 {
                  append = TEXT
                  append {
                    char = 10
                  }
                  field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.impp.skype}
                  required  = 1
                  wrap      = IMPP;TYPE="voice,video":skype:|
                }
                  // TEL;
                30 = COA
                30 {
                    // TYPE="voice,work":
                  10 = TEXT
                  10 {
                    append = TEXT
                    append {
                      char = 10
                    }
                    field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.tel.work.voice}
                    required  = 1
                    wrap      = TEL;TYPE="voice,work":|
                  }
                    // TYPE=fax:
                  20 = TEXT
                  20 {
                    append = TEXT
                    append {
                      char = 10
                    }
                    field     = {$plugin.tx_browser_pi1.templates.singleview.vCard.0.field.tel.work.fax}
                    required  = 1
                    wrap      = TEL;TYPE="fax,work":|
                  }
                }
              }
            }
              // END:
            40 = TEXT
            40 {
              value = END:VCARD
            }
          }
        }
      }
    }
  }
}