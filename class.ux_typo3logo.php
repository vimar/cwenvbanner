<?php

/**
 * (overwrites the) class to render the TYPO3 logo in the backend
 *
 * This file supports Typo3 4.x - see Classes/Xclass/LogoView.php for the Typo3 6.x version
 * 
 * @author	Carsten Windler <carsten@carstenwindler.de>
 * @package TYPO3
 * @subpackage tx_cwenvbanner
 */
class ux_TYPO3Logo extends TYPO3Logo {
	/**
	 * renders the actual logo code
	 *
	 * @return	string	logo html code snippet to use in the backend
	 */
	public function render() {
		// Autoloader doesn't seem to work here
		require_once t3lib_extMgm::extPath('cwenvbanner') . 'class.tx_cwenvbanner.php';
		$envBanner = t3lib_div::makeInstance('tx_cwenvbanner');

		if ($envBanner->isBackendBannerShown()) {
			return $envBanner->renderBEBanner() .  parent::render();
		}
		
		return parent::render();
	}
}