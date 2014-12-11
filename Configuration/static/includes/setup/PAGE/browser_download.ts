  // TYPO3-Browser: Download page object
browser_download < plugin.tx_browser_pi1.download.page

  // TYPO3-Browser: Page object for the map category markers
browser_map < plugin.tx_browser_pi1.export.map.page

[globalVar = GP:type = {$plugin.tx_browser_pi1.typeNum.mapPageObj}]
    // Don't wrap the content of the page object for the map category markers by default
  tt_content.stdWrap >
[global]
