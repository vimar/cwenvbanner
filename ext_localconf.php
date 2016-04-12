<?php

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Make this extension known in the Typo3 6.x world
if (TYPO3_MODE === 'BE') {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
			'Carstenwindler.' . $_EXTKEY,
			'tools',
			'envbanner'
	);
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['cwenvbanner']
	= 'EXT:cwenvbanner/Classes/Renderer.php:&Carstenwindler\\Cwenvbanner\\Renderer->contentPostProcOutputHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['cwenvbanner']
	= 'EXT:cwenvbanner/Classes/Renderer.php:&Carstenwindler\\Cwenvbanner\\Renderer->backendRenderPreProcessHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPostProcess']['cwenvbanner']
		= 'EXT:cwenvbanner/Classes/Renderer.php:&Carstenwindler\\Cwenvbanner\\Renderer->backendRenderPostProcessHook';

?>