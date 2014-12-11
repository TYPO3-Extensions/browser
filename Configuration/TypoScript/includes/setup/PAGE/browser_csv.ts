  // TYPO3-Browser: CSV export page object
browser_csv < plugin.tx_browser_pi1.export.csv.page

[globalVar = GP:type = {$plugin.tx_browser_pi1.typeNum.csvPageObj}]
    // Don't wrap the content of the CSV export page object by default
  tt_content.stdWrap >
[global]
  // pages: browser_csv