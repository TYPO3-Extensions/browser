<?php

if ( !defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}



/**
 *
 * INDEX
 * Configuration by the extension manager
 * Methods for backend workflows
 * TypoScript: Include Static Templates
 * Plugin general configuration
 * Wizard Icons
 * Plugin 1 configuration
 * Plugin 4 configuration
 * Plugin 3 configuration
 * Add pagetree icon
 *
 */
/**
 *
 * Configuration by the extension manager
 *
 */
$confArr = unserialize( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXT' ][ 'extConf' ][ 'browser' ] );

// Language for labels of static templates and page tsConfig
$llStatic = $confArr[ 'LLstatic' ];
switch ( $llStatic )
{
  case($llStatic == 'German'):
    $llStatic = 'de';
    break;
  default:
    $llStatic = 'default';
}
// Language for labels of static templates and page tsConfig
/**
 *
 * Methods for backend workflows
 *
 */
require_once(t3lib_extMgm::extPath( $_EXTKEY ) . 'pi1/class.tx_browser_pi1_backend.php');
require_once(t3lib_extMgm::extPath( $_EXTKEY ) . 'pi5/class.tx_browser_pi5_backend.php');

// Enables the Include Static Templates
require_once( PATH_typo3conf . 'ext/browser/Configuration/ExtTables/includeStaticTemplates.php' );



/**
 *
 * Plugin general configuration
 *
 */
t3lib_div::loadTCA( 'tt_content' );
// Plugin general configuration
/**
 *
 * Wizard Icons
 *
 */
if ( TYPO3_MODE == 'BE' )
{
  $TBE_MODULES_EXT[ 'xMOD_db_new_content_el' ][ 'addElClasses' ][ 'tx_browser_pi1_backend_wizicon' ] = t3lib_extMgm::extPath( $_EXTKEY ) . 'pi1/class.tx_browser_pi1_backend_wizicon.php';
  $TBE_MODULES_EXT[ 'xMOD_db_new_content_el' ][ 'addElClasses' ][ 'tx_browser_pi5_backend_wizicon' ] = t3lib_extMgm::extPath( $_EXTKEY ) . 'pi5/class.tx_browser_pi5_backend_wizicon.php';
  $TBE_MODULES_EXT[ 'xMOD_db_new_content_el' ][ 'addElClasses' ][ 'tx_browser_pi4_backend_wizicon' ] = t3lib_extMgm::extPath( $_EXTKEY ) . 'pi4/class.tx_browser_pi4_backend_wizicon.php';
  $TBE_MODULES_EXT[ 'xMOD_db_new_content_el' ][ 'addElClasses' ][ 'tx_browser_pi3_backend_wizicon' ] = t3lib_extMgm::extPath( $_EXTKEY ) . 'pi3/class.tx_browser_pi3_backend_wizicon.php';
}
// Wizard Icons

/**
 * Include Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        $_EXTKEY
        , 'Pi1'
        , 'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi1'
        , t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        $_EXTKEY
        , 'Pi6'
        , 'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi6'
        , t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
);

/**
 * Include Flexforms
 */
// pi1
$fileName = 'pi1/flexform.xml';
$pluginSignature = str_replace( '_', '', $_EXTKEY ) . '_pi1';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $pluginSignature ] = 'layout,select_key';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $pluginSignature ] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/' . $fileName
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        array(
  'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi1'
  , $_EXTKEY . '_pi1'
  , 'EXT:browser/ext_icon.gif'
        )
        , 'list_type'
);

// pi6
$fileName = 'pi6/flexform.xml';
$pluginSignature = str_replace( '_', '', $_EXTKEY ) . '_pi6';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $pluginSignature ] = 'layout,select_key,pages,recursive';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $pluginSignature ] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/' . $fileName
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        array(
  'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi6'
  , $_EXTKEY . '_pi6'
  , 'EXT:browser/ext_icon.gif'
        )
        , 'list_type'
);

/**
 *
 * Plugin 5 configuration
 *
 */
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi5' ] = 'layout,select_key';
// Remove the default tt_content fields layout, select_key, pages and recursive.
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi5' ] = 'pi_flexform';
// Display the field pi_flexform
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi5', 'FILE:EXT:' . $_EXTKEY . '/pi5/flexform.xml' );
// Register our file with the flexform structure
t3lib_extMgm::addPlugin( array( 'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi5', $_EXTKEY . '_pi5', 'EXT:browser/ext_icon.gif' ), 'list_type' );
// Add the Flexform to the Plugin List
// Plugin 1 configuration

/**
 *
 * Plugin 4 configuration
 *
 */
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi4' ] = 'layout,select_key';
// Remove the default tt_content fields layout, select_key, pages and recursive.
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi4' ] = 'pi_flexform';
// Display the field pi_flexform
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi4', 'FILE:EXT:' . $_EXTKEY . '/pi1/flexform.xml' );
// Register our file with the flexform structure
t3lib_extMgm::addPlugin( array( 'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi4', $_EXTKEY . '_pi4', 'EXT:browser/ext_icon.gif' ), 'list_type' );
// Add the Flexform to the Plugin List
// Plugin 4 configuration

/**
 *
 * Plugin 3 configuration
 *
 */
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi3' ] = 'layout,select_key,pages,recursive';
// Remove the default tt_content fields layout, select_key, pages and recursive.
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi3' ] = 'pi_flexform';
// Display the field pi_flexform
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi3', 'FILE:EXT:' . $_EXTKEY . '/pi3/flexform.xml' );
// Register our file with the flexform structure
t3lib_extMgm::addPlugin( array( 'LLL:EXT:browser/locallang_db.xml:tt_content.list_type_pi3', $_EXTKEY . '_pi3', 'EXT:browser/ext_icon.gif' ), 'list_type' );
// Add the Flexform to the Plugin List
// Plugin 3 configuration

/**
 *
 * Add pagetree icon
 *
 */
$TCA[ 'pages' ][ 'columns' ][ 'module' ][ 'config' ][ 'items' ][] = array( 'Browser', 'browser', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
t3lib_SpriteManager::addTcaTypeIcon( 'pages', 'contains-browser', '../typo3conf/ext/browser/ext_icon.gif' );
// Add pagetree icon