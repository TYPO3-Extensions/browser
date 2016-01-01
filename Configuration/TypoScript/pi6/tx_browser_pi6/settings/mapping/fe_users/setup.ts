plugin.tx_browser_pi6 {
  settings {
      // empty statement for proper comments only
    mapping {
    }
      // fe_users
    mapping =
    mapping {
        // empty statement for proper comments only
      fe_users {
      }
        // allowedFields, defaults
      fe_users =
      fe_users {
        allowedFields = {$plugin.tx_browser_pi6.settings.fe_users.allowed}
          // empty statement for proper comments only
        defaults {
        }
          // pid, usergroup
        defaults =
        defaults {
            // value: {$plugin.tx_browser_pi6.settings.fe_users.pid}
          pid = TEXT
          pid {
              // Return a value only, if an user isn't logged in and if we have a new record
            if =
            if {
              isFalse {
                data = TSFE:fe_user|user|uid
              }
            }
            value = {$plugin.tx_browser_pi6.settings.fe_users.pid}
          }
            // value: {$plugin.tx_browser_pi6.settings.fe_users.usergroup}
          usergroup = TEXT
          usergroup {
              // Return a value only, if an user isn't logged in and if we have a new record
            if =
            if {
              isFalse {
                data = TSFE:fe_user|user|uid
              }
            }
            value = {$plugin.tx_browser_pi6.settings.fe_users.usergroup}
          }
        }
      }
    }
  }
}