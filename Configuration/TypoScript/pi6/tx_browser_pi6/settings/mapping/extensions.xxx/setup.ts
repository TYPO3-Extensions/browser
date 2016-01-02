plugin.tx_browser_pi6 {
  settings {
      // empty statement for proper comments only
    mapping {
    }
      // tx_org_job
    mapping =
    mapping {
        // empty statement for proper comments only
      tx_org_job {
      }
        // allowedFields, defaults
      tx_org_job =
      tx_org_job {
        allowedFields = title, description, mail_zip, mail_city, image
          // empty statement for proper comments only
        defaults {
        }
          // pid, usergroup
        defaults =
        defaults {
            // value: {$plugin.tx_browser_pi6.settings.tx_org_job.pid}
          pid = TEXT
          pid {
            value = {$plugin.tx_browser_pi6.settings.tx_org_job.pid}
          }
        }
      }
    }
  }
}