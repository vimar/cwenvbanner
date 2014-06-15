<?php

if (!defined ("TYPO3_MODE")) {
	die ("Access denied.");
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\View\\LogoView'] = array(
	'className' => 'Cw\\Cwenvbanner\\Xclass\\LogoView',
);

?>