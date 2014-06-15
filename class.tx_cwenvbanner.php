<?php
/**
 * 
 *
 * @author	Carsten Windler <carsten@carstenwindler.de>
 * @package TYPO3
 * @subpackage tx_cwenvbanner
 */
class tx_cwenvbanner implements t3lib_Singleton {
	/**
	 * Extension key
	 * @var string
	 */
	protected $extKey = 'cwenvbanner';
	
	/**
	 * Extension configuration
	 * @var array
	 */
	protected $conf = array();

	/**
	 * Store the bannerText here
	 * @var string
	 */
	protected $bannerText = null;
	
	/**
	 * Constructor
	 * @return \tx_cwenvbanner
	 */
	public function __construct() {
		if(isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey])) {
			 $this->setConf(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]));
		}
	}
	
	/**
	 * Setter for $conf
	 * @param array $conf
	 * @return \tx_cwenvbanner
	 */
	public function setConf($conf) {
		$this->conf = $conf;
		
		return $this;
	}
	
	/**
	 * Whether banner is configured to be alwqys shown in Frontend 
	 * @return boolean
	 */
	protected function isShownAlwaysInFrontend() {
		return (isset($this->conf['showFEBannerAlways']) && $this->conf['showFEBannerAlways'] == 1);	
	}

	/**
	 * Whether banner is configured to be shown in backend 
	 * @return boolean
	 */
	protected function isShownInBackend() {
		return (isset($this->conf['showBEBanner']) && $this->conf['showBEBanner'] == 1);
	}
		
	/**
	 * Check whether a backend user is logged in
	 * @return boolean
	 */
	protected function isBeUserLoggedIn() {
		return ($GLOBALS['TSFE']->beUserLogin == 1);
	}
	
	/**
	 * Whether banner should be shown in frontend when a BE user is logged in
	 * @return boolean
	 */
	protected function isShownInFrontendForLoggedInBEUser() {
		return (isset($this->conf['showFEBannerIfBEUserIsLoggedIn']) 
			&& $this->conf['showFEBannerIfBEUserIsLoggedIn'] == 1 
			&& $this->isBeUserLoggedIn());
	}
	
	
	/**
	 * Whether banner should be shown in frontend when a BE user is logged in
	 * @return boolean
	 */
	protected function isShownInBackendForLoggedInBEUser() {
		if (!empty($this->conf['showBEBannerForBEUserIdsOnly'])) {
			$tempArr = explode(',', $this->conf['showBEBannerForBEUserIdsOnly']);
			
			if(in_array($GLOBALS['BE_USER']->user['uid'], $tempArr)) {
				return true;
			}
		} else {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check whether the extension configuration is actually loaded
	 * @return boolean
	 */
	protected function isConfigurationLoaded() {
		if (count($this->conf) == 0) {
			t3lib_div::devLog('No configuration found in localconf', 'cwenvbanner', 3);
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Whether the FE banner is to be shown
	 * @return boolean
	 */
	public function isFrontendBannerShown() {
		return $this->isConfigurationLoaded() 
			&& ($this->isShownAlwaysInFrontend() || $this->isShownInFrontendForLoggedInBEUser());
	}

	/**
	 * Whether the BE banner is to be shown
	 * @return boolean
	 */
	public function isBackendBannerShown() {
		return $this->isShownInBackend() && $this->isShownInBackendForLoggedInBEUser();
	}

	/**
	 * Hooked into contentPostProc_output
	 * @param $params
	 * @return void
	 */
	public function contentPostProc_output(&$params) {
		$feobj = &$params['pObj'];

		// @TODO eId = ??

		if($this->isFrontendBannerShown()) {
			$outputArray = array();
			preg_match("/<body[^<]*>/", $feobj->content, $outputArray);

			// We expect the first occurence of <body> to be the correct one
			// there should be only one anyway
			$bodyTag = array_shift($outputArray);

			$feobj->content = str_replace($bodyTag, $bodyTag . $this->renderFEBanner(), $feobj->content);
		}

		$outputArray = array();
		preg_match("/<title[^<]*>/", $feobj->content, $outputArray);

		// We expect the first occurence of <title> to be the correct one
		// there should be only one anyway
		$titleTag = array_shift($outputArray);

		$feobj->content = str_replace($titleTag, $titleTag . $this->getBannerText() . ' - ', $feobj->content);
	}

	/**
	 * Hooked into backendRenderPreProcess
	 * @param $params
	 * @param $test
	 */
	public function backendRenderPreProcess(&$params, $test) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = $this->getBannerText();
	}
	
	/**
	 * Return the inline css
	 * @return string
	 */
	protected function getInlineCss() {
		$css = (!empty($this->conf['bannerInlineCss'])) 
			?  ' style="' . $this->conf['bannerInlineCss'] . '"'
			: '';

		return $css;
	}
	
	/**
	 * Returns the environment name
	 * @return string
	 */
	protected function getEnvName() {
		if (!empty($this->conf['takeEnvNameFromServerVariable']) 
			&& isset($GLOBALS['_SERVER'][$this->conf['takeEnvNameFromServerVariable']])
		) {
			return $GLOBALS['_SERVER'][$this->conf['takeEnvNameFromServerVariable']];
		}
		
		return (!empty($this->conf['envName'])) 
			? $this->conf['envName']
			: 'n/a';
	}
	
	/**
	 * Returns the banner text
	 * @return string
	 */
	protected function getBannerText() {
		if (!empty($this->bannerText)) {
			return $this->bannerText;
		}

		$siteName	= $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
		$envName    = $this->getEnvName();

		if (!empty($this->conf['bannerTemplate'])) {
			$this->bannerText = str_replace(
				array('###sitename###', '###env###'),
				array($siteName, $envName), 
				$this->conf['bannerTemplate']
				); 
		} else {
			$this->bannerText = $siteName . ' - ' . $envName;
		}
		
		return $this->bannerText;
	}

	/**
	 * Renders the banner
	 * @return string
	 */
	protected function renderBanner() {
		return '<div' . $this->getInlineCss() . '>' . $this->getBannerText() . '</div>';
	}
	
	/**
	 * Render the BE Banner
	 * @return string
	 */
	public function renderBEBanner() {
		// right now, both banners are the same
		return $this->renderBanner();
	}
	
	/**
	 * Render the FE Banner
	 * @return string
	 */
	public function renderFEBanner() {
		// right now, both banners are the same
		return $this->renderBanner();
	}
}
?>