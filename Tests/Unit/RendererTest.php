<?php

use CarstenWindler\Cwenvbanner\Renderer;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * @covers \CarstenWindler\Cwenvbanner\Renderer
 */
class RendererTest extends UnitTestCase
{
    /**
     * @var Renderer
     */
    protected $fixture;

    /**
     * @var array
     */
    protected $defaultConfiguration;

    protected function setUp()
    {
        $this->defaultConfiguration = array(
            'showEnvironment' => 1,
            'envName' => 'DEV',
            'takeEnvNameFromServerVariable' => '',
            'bannerTemplate' => '',
            'bannerInlineCss' => '',
            'showFEBannerIfBEUserIsLoggedIn' => 1,
            'showFEBannerForBEUserIdsOnly' => '',
            'showFEBannerAlways' => 0,
            'showBEBanner' => 1,
            'showBEBannerForBEUserIdsOnly' => 0,
            );

        $this->fixture = new Renderer();

        $this->fixture->init($this->defaultConfiguration);

        // This way we don't need to start the whole FE rendering process
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->content = '<body><div id="someotherdiv"></div></body>';
    }

    protected function tearDown()
    {
        unset($this->fixture);
        unset($this->testingFramework);
        unset($this->defaultConfiguration);
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::contentPostProcOutputHook
     */
    public function doNotShowFrontendBannerIfNoConfigurationWasFound()
    {
        $this->fixture->setConf([]);

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->fixture->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body><div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was shown, although no configuration was found at all'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::contentPostProcOutputHook
     */
    public function frontendBannerIsShownWhenBeUserIsLoggedIn()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showFEBannerIfBEUserIsLoggedIn' => 1)
        ));

        $GLOBALS['TSFE']->beUserLogin = 1;

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->fixture->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body>' . $this->fixture->renderFrontendBanner(). '<div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was not shown, although it is configured and BE user is logged in'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::isFrontendBannerShown
     */
    public function frontendBannerIsShownWhenCertainBeUserIsLoggedIn()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'showFEBannerIfBEUserIsLoggedIn' => 1,
                'showFEBannerForBEUserIdsOnly' => '1,3,13',
            )
        ));

        $GLOBALS['TSFE']->beUserLogin = 1;
        $GLOBALS['BE_USER']->user['uid'] = 3;

        $this->assertTrue(
            $this->fixture->isFrontendBannerShown(),
            'Frontend Banner was not shown, although BE User with configured ID is logged in'
        );

        $GLOBALS['BE_USER']->user['uid'] = 2;

        $this->assertFalse(
            $this->fixture->isFrontendBannerShown(),
            'Frontend Banner was shown, although BE User with ID other than the configured is logged in'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::isFrontendBannerShown
     */
    public function frontendBannerIsShownWhenNotRestrictedToLoggedInBeUser()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'showFEBannerIfBEUserIsLoggedIn' => 1,
                'showFEBannerForBEUserIdsOnly' => '',
            )
        ));

        $GLOBALS['TSFE']->beUserLogin = 1;
        $GLOBALS['BE_USER']->user['uid'] = 3;

        $this->assertTrue(
            $this->fixture->isFrontendBannerShown(),
            'Frontend Banner was not shown, although it is not restricted to certain BE User IDs'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::isBackendBannerShown
     */
    public function backendBannerIsShown()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showBEBanner' => 0)
        ));

        $this->assertFalse($this->fixture->isBackendBannerShown());

        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showBEBanner' => 1)
        ));

        $this->assertTrue($this->fixture->isBackendBannerShown());
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::isBackendBannerShown
     */
    public function backendBannerIsShownForCertainBEUsersOnly()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showBEBanner' => 1)
        ));

        $this->assertTrue($this->fixture->isBackendBannerShown());

        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showBEBannerForBEUserIdsOnly' => '1,3,13')
        ));

        $GLOBALS['BE_USER']->user['uid'] = 2;

        $this->assertFalse(
            $this->fixture->isBackendBannerShown(),
            'Backend Banner is shown, although current user ID is not amongst the allowed IDs'
        );

        $GLOBALS['BE_USER']->user['uid'] = 1;

        $this->assertTrue(
            $this->fixture->isBackendBannerShown(),
            'Backend Banner is not shown, although current user ID is amongst the allowed IDs'
        );
    }



    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::contentPostProcOutputHook
     */
    public function frontendBannerIsNotShownWhenNoBeUserIsLoggedIn()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showFEBannerIfBEUserIsLoggedIn' => 1)
        ));

        $GLOBALS['TSFE']->beUserLogin = 0;

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->fixture->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body><div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was shown, although no BE user was logged in'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::contentPostProcOutputHook
     */
    public function frontendBannerIsNotShownWhenBeUserIsLoggedIn()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array('showFEBannerIfBEUserIsLoggedIn' => 0)
        ));

        $GLOBALS['TSFE']->beUserLogin = 1;

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->fixture->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body><div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was shown, although it was not configured to do so when BE user is logged in'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersAreRenderedAsConfigured()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'showEnvironment' => 1,
                'envName' => 'myEnv',
                'takeEnvNameFromServerVariable' => '',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => 'font-weight: bold',
                'showBEBanner' => 1,
                'showFEBannerAlways' => 1,
                )
        ));

        $expectedFeBanner = $expectedBeBanner = '<div style="font-weight: bold">myEnv - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersAreRenderedAsConfiguredUsingServerVariable()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                // we implicitly test overwriting envName using SERVER variable here
                'envName' => 'myEnv',
                'takeEnvNameFromServerVariable' => 'ENVTEST',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => 'font-weight: bold',
                )
        ));

        $expectedFeBanner = $expectedBeBanner = '<div style="font-weight: bold">envVarTest - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';
        $GLOBALS['_SERVER']['ENVTEST'] = 'envVarTest';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersHaveNoStyleTagIfNoInlineCssConfigured()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'envName' => 'myEnv',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => '',
                )
        ));

        $expectedFeBanner = $expectedBeBanner = '<div>myEnv - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersRenderCustomInlineStyle()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'envName' => 'myEnv',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => 'background: white;',
            )
        ));

        $expectedFeBanner = $expectedBeBanner = '<div style="background: white;">myEnv - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersUsePredefinedStyle()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'envName' => 'myEnv',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => '',
                'bannerStyle' => 'yellow',
            )
        ));

        $expectedFeBanner = $expectedBeBanner =
            '<div style="z-index: 10000; position: fixed; top: 0px; left: 0px; padding: 6px;' .
            ' background: #FFFF00; colour: #000000;">myEnv - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderFrontendBanner
     * @covers CarstenWindler\Cwenvbanner\Renderer::renderBackendBanner
     */
    public function bannersUseCustomStyle()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'envName' => 'myEnv',
                'bannerTemplate' => '###env### - ###sitename###',
                'bannerInlineCss' => 'background: yellow;',
                'bannerStyle' => 'custom',
            )
        ));

        $expectedFeBanner = $expectedBeBanner = '<div style="background: yellow;">myEnv - mySitename</div>';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = 'mySitename';

        $this->assertEquals(
            $expectedFeBanner,
            $this->fixture->renderFrontendBanner(),
            'The FE banner is not rendered correctly'
        );

        $this->assertEquals(
            $expectedBeBanner,
            $this->fixture->renderBackendBanner(),
            'The BE banner is not rendered correctly'
        );
    }

    /**
     * @test
     * @covers CarstenWindler\Cwenvbanner\Renderer::contentPostProcOutputHook
     */
    public function showFEBannerAlwaysOverwritesOtherSettings()
    {
        $this->fixture->setConf(array_merge(
            $this->defaultConfiguration,
            array(
                'showFEBannerAlways' => 1,
                'showFEBannerIfBEUserIsLoggedIn' => 1,
                )
        ));

        $GLOBALS['TSFE']->beUserLogin = 0;

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->fixture->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body>' . $this->fixture->renderFrontendBanner(). '<div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was not shown, although it is configured as showAlways'
        );
    }
}
