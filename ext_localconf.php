<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\View\\LogoView'] = array(
	'className' => 'Cw\\Cwenvbanner\\Xclass\\LogoView',
);

// Make this extension known in the Typo3 6.x world
if (TYPO3_MODE === 'BE') {
	// Check for the major version, because this will be parsed by all versions
	$typo3Version = explode('.', TYPO3_version);
	$majorVersion = intval($typo3Version[0]);
	if($majorVersion >= 6) {
		\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'Cw.' . $_EXTKEY,
			'tools',
			'envbanner'
		);
	}
}

// http://wiki.typo3.org/XCLASS
$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['typo3/classes/class.typo3logo.php'] 
	= t3lib_extMgm::extPath('cwenvbanner') . 'class.ux_typo3logo.php';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['tx_cwenvbanner']
	= 'EXT:cwenvbanner/class.tx_cwenvbanner.php:&tx_cwenvbanner->contentPostProc_output';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['tx_cwenvbanner']
	= 'EXT:cwenvbanner/class.tx_cwenvbanner.php:&tx_cwenvbanner->backendRenderPreProcess';

?>