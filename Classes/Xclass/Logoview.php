<?php

namespace Cw\Cwenvbanner\Xclass;

/**
 * (overwrites the) class to render the TYPO3 logo in the backend
 *
 * This file supports Typo3 6.x - see class.ux_typo3logo.ophp for the Typo3 4.x version 
 *
 * @author	Carsten Windler <carsten@carstenwindler.de>
 * @package TYPO3
 * @subpackage tx_cwenvbanner
 */
class LogoView extends \TYPO3\CMS\Backend\View\LogoView {
	/**
	 * renders the actual logo code
	 *
	 * @return	string	logo html code snippet to use in the backend
	 */
	public function render() {
		// Autoloader doesn't seem to work here
		require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('cwenvbanner') . "class.tx_cwenvbanner.php";
		$envBanner = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_cwenvbanner');
		
		if ($envBanner->isBackendBannerShown()) {
			return $envBanner->renderBEBanner();
		}
		
		return parent::render();
	}
}