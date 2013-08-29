<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = t3lib_extMgm::extPath('browser');
return array(
    'tx_browser_geoupdate'                          => $extensionPath . 'lib/scheduler/class.tx_browser_geoupdate.php',
    'tx_browser_geoupdate_additionalfieldprovider'  => $extensionPath . 'lib/scheduler/class.tx_browser_geoupdate_additionalfieldprovider.php',
    'tx_browser_testtask'                           => $extensionPath . 'lib/scheduler/class.tx_browser_testtask.php',
    'tx_browser_testtask_additionalfieldprovider'   => $extensionPath . 'lib/scheduler/class.tx_browser_testtask_additionalfieldprovider.php',
);
?>