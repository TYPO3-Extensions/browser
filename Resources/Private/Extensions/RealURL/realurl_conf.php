<?php

// Add this snippet at the bottom of your realurl_conf.php

$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'az' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[indexBrowser]',
  )
);
$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'ib' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[azTab]',
  )
);
$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'mode' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[mode]',
  )
);
$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'pointer' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[pointer]',
  )
);
$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'sort' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[sort]',
  )
);
$TYPO3_CONF_VARS[ 'EXTCONF' ][ 'realurl' ][ '_DEFAULT' ][ 'postVarSets' ][ '_DEFAULT' ][ 'sword' ] = array(
  array(
    'GETvar' => 'tx_browser_pi1[sword]',
  )
);
