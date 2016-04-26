<?php

namespace CarstenWindler\Cwenvbanner;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Environment Banner Renderer
 *
 * @author    Carsten Windler <carsten@carstenwindler.de>
 */
class Renderer
{
    /**
     * Extension key
     *
     * @var string
     */
    protected $extKey = 'cwenvbanner';
    /**
     * The predefined banner styles
     *
     * @var array
     */
    protected $bannerStyles = array();
    /**
     * Extension configuration
     *
     * @var array
     */
    protected $conf = array();
    /**
     * Store the bannerText here
     *
     * @var string
     */
    protected $bannerText = null;

    /**
     * Constructor
     *
     * @return Renderer
     */
    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey])) {
            $this->init(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]));
        }

        $this->bannerStyles = array(
            'green' => 'z-index: 10000; position: fixed; top: 0px; ' .
                'left: 0px; padding: 6px; background: #00FF00; colour: #000000;',
            'yellow' => 'z-index: 10000; position: fixed; top: 0px; ' .
                'left: 0px; padding: 6px; background: #FFFF00; colour: #000000;',
            'red' => 'z-index: 10000; position: fixed; top: 0px; ' .
                'left: 0px; padding: 6px; background: #FF0000; colour: #000000;'
        );
    }

    /**
     * Initialize this instance
     *
     * @param array $conf Configuration array (optional)
     *
     * @return Renderer
     */
    public function init(array $conf = null)
    {
        if ($conf !== null) {
            $this->setConf($conf);
        }

        $this->bannerText = null;

        return $this;
    }

    /**
     * Setter for $conf
     *
     * @param array $conf Configuration array
     *
     * @return Renderer
     */
    public function setConf(array $conf)
    {
        $this->conf = $conf;

        return $this;
    }

    /**
     * Whether banner is configured to be alwqys shown in Frontend
     *
     * @return bool
     */
    protected function isShownAlwaysInFrontend()
    {
        return (isset($this->conf['showFEBannerAlways']) && $this->conf['showFEBannerAlways'] == 1);
    }

    /**
     * Whether banner is configured to be shown in backend
     *
     * @return bool
     */
    protected function isShownInBackend()
    {
        return (isset($this->conf['showBEBanner']) && $this->conf['showBEBanner'] == 1);
    }

    /**
     * Check whether a backend user is logged in
     *
     * @return bool
     */
    protected function isBeUserLoggedIn()
    {
        return ($GLOBALS['TSFE']->beUserLogin == 1);
    }

    /**
     * Whether banner should be shown in frontend when a BE user is logged in
     *
     * @return bool
     */
    protected function isShownInFrontendForLoggedInBackendUser()
    {
        if (!empty($this->conf['showFEBannerIfBEUserIsLoggedIn'])) {
            if ($this->isBeUserLoggedIn()) {
                if (!empty($this->conf['showFEBannerForBEUserIdsOnly'])) {
                    $userIdsArr = explode(',', $this->conf['showFEBannerForBEUserIdsOnly']);

                    if (in_array($GLOBALS['BE_USER']->user['uid'], $userIdsArr)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Whether banner should be shown in frontend when a BE user is logged in
     *
     * @return bool
     */
    protected function isShownInBackendForLoggedInBackendUser()
    {
        if (!empty($this->conf['showBEBannerForBEUserIdsOnly'])) {
            $userIdsArr = explode(',', $this->conf['showBEBannerForBEUserIdsOnly']);

            if (in_array($GLOBALS['BE_USER']->user['uid'], $userIdsArr)) {
                return true;
            }
        } else {
            return true;
        }

        return false;
    }

    /**
     * Check whether the extension configuration is actually loaded
     *
     * @return bool
     */
    protected function isConfigurationLoaded()
    {
        if (count($this->conf) == 0) {
            GeneralUtility::devLog('No configuration found in localconf', 'cwenvbanner', 3);

            return false;
        }

        return true;
    }

    /**
     * Whether the FE banner is to be shown
     *
     * @return bool
     */
    public function isFrontendBannerShown()
    {
        return $this->isConfigurationLoaded()
        && ($this->isShownAlwaysInFrontend() || $this->isShownInFrontendForLoggedInBackendUser());
    }

    /**
     * Whether the BE banner is to be shown
     *
     * @return bool
     */
    public function isBackendBannerShown()
    {
        return $this->isShownInBackend() && $this->isShownInBackendForLoggedInBackendUser();
    }

    /**
     * Hooked into contentPostProc_output
     *
     * @param array $params Parameter array passed by caller
     *
     * @return void
     */
    public function contentPostProcOutputHook(array &$params)
    {
        $feobj = &$params['pObj'];

        // @TODO eId = ??

        if ($this->isFrontendBannerShown()) {
            $outputArray = array();
            preg_match('/<body[^<]*>/', $feobj->content, $outputArray);

            // We expect the first occurence of <body> to be the correct one
            // there should be only one anyway
            $bodyTag = array_shift($outputArray);

            $feobj->content = str_replace($bodyTag, $bodyTag . $this->renderFrontendBanner(), $feobj->content);
        }

        $outputArray = array();
        preg_match('/<title[^<]*>/', $feobj->content, $outputArray);

        // We expect the first occurence of <title> to be the correct one
        // there should be only one anyway
        $titleTag = array_shift($outputArray);

        $feobj->content = str_replace($titleTag, $titleTag . $this->getBannerText() . ' - ', $feobj->content);
    }

    /**
     * Hooked into backendRenderPreProcess
     *
     * @param array $params Parameter array passed by caller
     *
     * @return void
     */
    public function backendRenderPreProcessHook(array &$params)
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = $this->getBannerText();
    }

    /**
     * Hooked into backendRenderPreProcess
     *
     * @param array $params Parameter array passed by caller
     *
     * @return void
     */
    public function backendRenderPostProcessHook(array &$params)
    {
        if ($this->isBackendBannerShown()) {
            $outputArray = array();
            preg_match('/<body[^<]*>/', $params['content'], $outputArray);

            // We expect the first occurance of <body> to be the correct one
            // there should be only one anyway
            $bodyTag = array_shift($outputArray);

            $params['content'] = str_replace($bodyTag, $bodyTag . $this->renderBackendBanner(), $params['content']);
        }
    }

    /**
     * Return the inline css
     *
     * @return string
     */
    protected function getInlineCss()
    {
        if (!isset($this->conf['bannerStyle'])
            || $this->conf['bannerStyle'] == 'custom'
            || !isset($this->bannerStyles[$this->conf['bannerStyle']])
        ) {
            $style = $this->conf['bannerInlineCss'];
        } else {
            $style = $this->bannerStyles[$this->conf['bannerStyle']];
        }

        if (empty($style)) {
            return '';
        } else {
            return ' style="' . $style . '"';
        }
    }

    /**
     * Returns the environment name
     *
     * @return string
     */
    protected function getEnvName()
    {
        if (!empty($this->conf['takeEnvNameFromServerVariable'])
            && isset($GLOBALS['_SERVER'][$this->conf['takeEnvNameFromServerVariable']])
        ) {
            return $GLOBALS['_SERVER'][$this->conf['takeEnvNameFromServerVariable']];
        }

        return (!empty($this->conf['envName'])) ? $this->conf['envName'] : 'n/a';
    }

    /**
     * Returns the banner text
     *
     * @return string
     */
    protected function getBannerText()
    {
        if (!empty($this->bannerText)) {
            return $this->bannerText;
        }

        $siteName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];
        $envName = $this->getEnvName();

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
     *
     * @return string
     */
    protected function renderBanner()
    {
        return '<div' . $this->getInlineCss() . '>' . $this->getBannerText() . '</div>';
    }

    /**
     * Render the BE Banner
     *
     * @return string
     */
    public function renderBackendBanner()
    {
        // right now, both banners are the same
        return $this->renderBanner();
    }

    /**
     * Render the FE Banner
     *
     * @return string
     */
    public function renderFrontendBanner()
    {
        // right now, both banners are the same
        return $this->renderBanner();
    }
}
