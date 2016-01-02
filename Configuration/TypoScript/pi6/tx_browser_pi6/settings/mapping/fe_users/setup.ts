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
          password = COA
          password {
              // Return a value only, if an user isn't logged in and if we have a new record
            if =
            if {
              isFalse {
                data = TSFE:fe_user|user|uid
              }
            }
            10 = CONTENT
            10 {
              table = be_users
              select {
                pidInList = root
                selectFields = FLOOR(10 + (RAND() * 100000000)) AS 'password'
                max = 1
              }
              renderObj = TEXT
              renderObj {
                field = password
              }
            }
          }
          username = TEXT
          username {
              // Return a value only, if an user isn't logged in and if we have a new record
            if =
            if {
              isFalse {
                data = TSFE:fe_user|user|uid
              }
            }
            field = fe_users.email
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