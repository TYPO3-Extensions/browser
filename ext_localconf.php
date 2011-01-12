<?php

if (!defined ('TYPO3_MODE'))  die ('Access denied.');



  ////////////////////////////////////////////////////
  //
  // Extending TypoScript from static template uid=43 to set up userdefined tag
  
t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_browser_pi1.php','_pi1','list_type',1);
t3lib_extMgm::addPItoST43($_EXTKEY,'pi3/class.tx_browser_pi3.php','_pi3','list_type',1);
t3lib_extMgm::addPItoST43($_EXTKEY,'pi4/class.tx_browser_pi4.php','_pi4','list_type',1);
  // Extending TypoScript from static template uid=43 to set up userdefined tag


?>