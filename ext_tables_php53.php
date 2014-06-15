<?php

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

