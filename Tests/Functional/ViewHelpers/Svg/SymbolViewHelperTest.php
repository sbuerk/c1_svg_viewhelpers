<?php

declare(strict_types=1);

use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Configuration\SiteConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class SymbolViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/Extensions/fluid_test',
        'typo3conf/ext/c1_svg_viewhelpers',
    ];

    protected $defaultArguments = [
            'id' => 1,
            'identifier' => 'house',
            'symbolFile' => 'default',
            'baseClass' => 'icon-default',
            'role' => 'graphics-symbol',
            'ariaLabel' => '',
            'cacheBuster' => '1',
            'preload' => '1',
            'title' => '',
            'class' => '',
        ];

    protected $backupGlobals = true;

    protected array $configurationToUseInTestInstance = [
//        'EXTCONF' => [
//            'extbase' => [
//                'extensions' => [
//                    'FluidTest' => [
//                        'plugins' => [
//                            'Pi' => [
//                                'controllers' => [
//                                    TemplateController::class => [
//                                        'className' => TemplateController::class,
//                                        'alias' => 'Template',
//                                        'actions' => [
//                                            'baseTemplate',
//                                        ],
//                                        'nonCacheableActions' => [
//                                            'baseTemplate',
//                                        ],
//                                    ],
//                                ],
//                            ],
//                        ],
//                    ],
//                ],
//            ],
//        ],
        'FE' => [
            'cacheHash' => [
                'enforceValidation' => false,
            ],
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(ORIGINAL_ROOT . '/../../Tests/Fixtures/Database/pages.csv');

        $siteConfiguration = new SiteConfiguration(
            $this->instancePath . '/typo3conf/sites/',
            $this->get(EventDispatcherInterface::class),
            $this->get('cache.core')
        );
        $identifier = 'default';
        $configuration = [
            'rootPageId' => 1,
            'base' => 'https://website.local',
        ];

        try {
            // ensure no previous site configuration influences the test
            GeneralUtility::rmdir($this->instancePath . '/typo3conf/sites/' . $identifier, true);
            $siteConfiguration->write($identifier, $configuration);
        } catch (\Exception $exception) {
            $this->markTestSkipped($exception->getMessage());
        }

        $this->setUpFrontendRootPage(
            1,
            [
            'constants' => [
                'EXT:c1_svg_viewhelpers/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:c1_svg_viewhelpers/Configuration/TypoScript/setup.typoscript',
                'EXT:fluid_test/Configuration/TypoScript/Basic.typoscript',
            ]
        ],
        );
        $this->addTypoScriptToTemplateRecord(
            1,
            'plugin.tx_c1svgviewhelpers.svg.symbol.presets.default.file = EXT:c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg' . LF,
        );
    }

    public function renderSymbolDataProvider(): array
    {
        return [
            'default' => [
                [],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                    '<link rel="preload" href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14" as="image" fetchpriority="high" />',
                ],
            ],
            'identifier is mail' => [
                ['identifier' => 'mail'],
                '',
                [
                    '<span class="icon-default icon-default-mail icon-default-mail-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#mail" /></svg></span>',
                ],
            ],
            'custom symbolFile' => [
                [
                    'symbolFile' => 'EXT:c1_svg_viewhelpers/Tests/Fixtures/sprite-alternative.svg'
                ],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-alternative.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'no_cache_buster' => [
                ['cacheBuster' => '0'],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg#house" /></svg></span>',
                ],
            ],
            'role set to img' => [
                ['role' => 'img'],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims"><svg role="img"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'with ariaLabel' => [
                ['ariaLabel' => 'my aria label'],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims"><svg aria-label="my aria label" role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'with custom baseClass' => [
                [
                    'baseClass' => 'myicon',
                ],
                '',
                [
                    '<span class="myicon myicon-house myicon-house-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'with title' => [
                ['title' => 'myicontitle'],
                '',
                [
                    '<span title="myicontitle" class="icon-default icon-default-house icon-default-house-dims"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'with extra css class' => [
                [
                    'class' => 'mycustomclass',
                    'preload' => '1'
                ],
                '',
                [
                    '<span class="icon-default icon-default-house icon-default-house-dims mycustomclass"><svg role="graphics-symbol"><use xlink:href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg?cb=ccb77e624e62a33b420baff0de4eef14#house" /></svg></span>',
                ],
            ],
            'disable preload by vh argument' => [
                [
                    'preload' => 0
                ],
                '',
                [],
                [
                    '<link rel="preload" href="/typo3conf/ext/c1_svg_viewhelpers/Tests/Fixtures/sprite-default.svg'
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider renderSymbolDataProvider
     */
    public function renderSymbol(
        array $arguments,
        string $typoScript,
        array $expectedStrings,
        array $notExpectedStrings = []
    ): void {
        $requestArguments = array_merge(
            $this->defaultArguments,
            $arguments
        );

        if ($typoScript !== '') {
            $this->addTypoScriptToTemplateRecord(
                1,
                $typoScript . LF,
            );
        }

        $response = $this->fetchFrontendResponse($requestArguments);

        foreach ($expectedStrings as $expected) {
            self::assertStringContainsString($expected, (string)$response->getBody());
        }

        foreach ($notExpectedStrings as $notExpected) {
            self::assertStringNotContainsString($notExpected, (string)$response->getBody());
        }
    }

    protected function fetchFrontendResponse(array $requestArguments): \TYPO3\CMS\Core\Http\Response
    {
        $response = $this->executeFrontendSubRequest(
            (new InternalRequest('https://website.local/'))->withQueryParameters($requestArguments)
        );

        return $response;
    }
}
