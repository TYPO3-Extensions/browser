<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = t3lib_extMgm::extPath('browser');
return array(
    'tx_browser_importtask'                         => $extensionPath . 'lib/scheduler/class.tx_browser_importtask.php',
    'tx_browser_importtask_additionalfieldprovider' => $extensionPath . 'lib/scheduler/class.tx_browser_importtask_additionalfieldprovider.php',
    'tx_browser_testtask'                           => $extensionPath . 'lib/scheduler/class.tx_browser_testtask.php',
    'tx_browser_testtask_additionalfieldprovider'   => $extensionPath . 'lib/scheduler/class.tx_browser_testtask_additionalfieldprovider.php',
);
?>