plugin.tx_browser_pi1 {
  advanced {
      // 5_0_0, 3_6_0
    downgrade =
    downgrade {
        // typoscriptEngine4x
      5_0_0 =
      5_0_0 {
          // Use the typoscript engine 4x (deprecated!)
        typoscriptEngine4x = 0
      }
        // marker
      3_6_0 =
      3_6_0 {
          // in_typoscript
        marker =
        marker {
            // remove_emptyMarker
          in_typoscript =
          in_typoscript {
              // [Boolean] 1: Behaviour like <= 3.6.0; 0: Behaviour from 3.6.1
            remove_emptyMarker = 0
          }
        }
      }
    }
    localisation {
      realURL {
          // [BOOLEAN] Default 1 (it is compliant with realURL). 1: Record will get the link to the record in default language. I.e record title won't be translated in the realURL path. You can switch to the record in another language. 0: Record will get the link to the translated record. I.e record title will be translated in the realURL path. You can't switch to the record in another language.
        defaultLanguageLink = 1
      }
      TCA {
        field {
            // [string] Fields with this appendix will be used for translation. I.e. tt_news_cat.title_lang_ol
          appendix = _lang_ol
        }
        value {
            // [string] Devider for seperating language entries like de:Aufmacher|fr:Accroche or Aufmacher|Accroche
          devider = |
            // [Boolean] 1: lang_ol values have a lang_prefix like en:Lead Story|de:Aufmacher|fr:Accroche, 0: lang_ol values are without lang_prefix like Lead Story|Aufmacher|Accroche
          langPrefix = 1
        }
      }
    }
    performance {
      GLOBALS {
          // [Boolean] Will be overriden by the flexform while runtime! True: Don't look for markers with keys of the TYPO3 array $GLOBALS like TSFE:fe_user|... False: replace it
        dont_replace = 1
      }
    }
    php {
      multisort {
        eval {
          sort_numeric {
            db {
                // [String, CSV] Comma seperated values for any database field name, which should order by php SORT_NUMERIC
              csv_sortNumeric = bigint, decimal, double, float, int, mediumint, smallint, timestamp, tinyint
            }
            tca {
                // [String, CSV] Comma seperated values for any TCA eval value, which should order by php SORT_NUMERIC
              csv_sortNumeric = date, datetime, datetimeend, datetimestart, double2, int, num, md5, time, timesec, year
            }
          }
        }
      }
    }
      // [INTEGER] Guard for recursive methods. Every loop will break after x times.
    recursionGuard = {$plugin.tx_browser_pi1.advanced.recursionGuard}
    security {
      sword {
          // [Integer] The minimum length of a word in the sword. Example: "I am searching for" becomes "searching for"
        minLenWord = 3
        addSlashes {
            // [String/CSV] Comma seperated list of chars, which will get a slash in every mysql query. You don't need \x00, \n, \r, \, ', " and \x1a, because it will be processed by mysql_real_escape_string().
          csvChars = %
        }
      }
    }
    session_manager {
      session {
          // [Boolean] 1 (default): enable sessions; 0: disable sessions
        enabled = 1
      }
    }
    sql {
      orderBy {
          // [Boolean] FALSE: There is no different handling for capitals and lower-case letters
        caseSensitive = 0
      }
      devider {
        childrenRecords = TEXT
        childrenRecords {
            // [String] Values of children records will be devided by ... This value will be displayed, if there is no other TypoScript configured for the current element.
          value      = ,
          noTrimWrap = || |
        }
        workflow = TEXT
        workflow {
            // [String] Devider for internal workflow
          value = ;|;
        }
      }
    }
  }
}