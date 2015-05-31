  // TYPO3-Browser: vCard page object
browser_vCard < plugin.tx_browser_pi1.export.vCard.page

[globalVar = GP:type = {$plugin.tx_browser_pi1.typeNum.vCardPageObj}]
    // Don't wrap the content of the vCard page object by default
  tt_content.stdWrap >
[global]
  // pages: browser_vCard