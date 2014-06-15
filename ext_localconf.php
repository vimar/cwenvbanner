<?php

if (!defined ("TYPO3_MODE")) {   
	die ("Access denied.");
}

// http://wiki.typo3.org/XCLASS
$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['typo3/classes/class.typo3logo.php'] 
	= t3lib_extMgm::extPath('cwenvbanner') . "class.ux_typo3logo.php";

// Help poor people still have to use php 5.2 (including myself at the moment :-(
// this "feature" will be removed pretty soon, though
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	require_once t3lib_extMgm::extPath('cwenvbanner') . "ext_localconf_php53.php";
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['tx_cwenvbanner']
	= 'EXT:cwenvbanner/class.tx_cwenvbanner.php:&tx_cwenvbanner->contentPostProc_output';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['tx_cwenvbanner']
	= 'EXT:cwenvbanner/class.tx_cwenvbanner.php:&tx_cwenvbanner->backendRenderPreProcess';

?>