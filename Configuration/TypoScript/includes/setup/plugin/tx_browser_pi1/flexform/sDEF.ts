plugin.tx_browser_pi1 {
  flexform {
    sDEF {
      controlling {
          // Control the display of the plugin by URL parameters
        enabled = TEXT
        enabled {
            // [Boolean] The display of the plugin isn't controlled by URL parameters (default)
          value = 0
        }
        adjustment {
            // Display the plugin only, if one of the following parameters is part of the URL ...
          display_if_in_list = TEXT
          display_if_in_list {
              // [String/csv] Comma separated list of parameters
            value =
          }
            // Hide the plugin, if one of the following parameters is part of the URL ...
          hide_if_in_list = TEXT
          hide_if_in_list {
              // [String/csv] Comma separated list of parameters
            value = ignore
          }
        }
      }
      downloads {
          // Allow to download files
        enabled = TEXT
        enabled {
            // [Boolean] Downloading is forbidden by default
          value = 0
        }
      }
      statistics {
          // Enable the statistics module
        enabled = TEXT
        enabled {
            // [Boolean] The statistics module is disabled by default
          value = 0
        }
        adjustment {
          fields {
              // Label and type of the field for counting downloads
            downloads {
                // Label of the field for counting downloads
              label = TEXT
              label {
                  // [String] Name of the field in the SQL table. Will be overriden by flexform value while runtime.
                value = statistics_downloads
              }
                // Type of the field in the TCA
              type = TEXT
              type {
                  // [String] Type of the field in the TCA
                value = none
              }
            }
              // Label and type of the field for counting downloadsByVisits
            downloadsByVisits {
                // Label of the field for counting downloads (with respect for timeout)
              label = TEXT
              label {
                  // [String] Name of the field in the SQL table. Will be overriden by flexform value while runtime.
                value = statistics_downloads_by_visits
              }
                // Type of the field in the TCA
              type = TEXT
              type {
                  // [String] Type of the field in the TCA
                value = none
              }
            }
              // Label and type of the field for counting hits
            hits {
                // Label of the field for counting hits (without any respect for timeout)
              label = TEXT
              label {
                  // [String] Name of the field in the SQL table. Will be overriden by flexform value while runtime.
                value = statistics_hits
              }
                // Type of the field in the TCA
              type = TEXT
              type {
                  // [String] Type of the field in the TCA
                value = none
              }
            }
              // Label and type of the field for counting visits
            visits {
                // Label of the field for counting visits (hits with respect for timeout)
              label = TEXT
              label {
                  // [String] Name of the field in the SQL table. Will be overriden by flexform value while runtime.
                value = statistics_visits
              }
                // Type of the field in the TCA
              type = TEXT
              type {
                  // [String] Type of the field in the TCA
                value = none
              }
            }
          }
            // Period between a current and a new download and visit in seconds
          timeout = TEXT
          timeout {
              // [Integer] Period in seconds. Default is 1800 (60 sec x 30 min = 1.800 sec)
            value = 1800
          }
            // List of IPs, which won't counted for downloads, hits and visits
          dontAccountIPsOfCsvList = TEXT
          dontAccountIPsOfCsvList {
              // [String/csv] Comma seperated list of IPs, which won't counted
            value = 
          }
            // Echo a debug report in the frontend in case of an unexpected result
          debugging = TEXT
          debugging {
              // [Boolean] 0: debug report is disabled (default), 1: debug report is enabled
            value = 0
          }
        }
      }
    }
  }
}