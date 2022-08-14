<?php
namespace C1\C1SvgViewHelpers\Tests\Unit\ViewHelpers\Svg;

use C1\C1SvgViewHelpers\ViewHelpers\SymbolViewHelper;
use Nimut\TestingFramework\TestCase\AbstractViewHelperBaseTestcase;

/**
 * Class SymbolViewHelperTest
 */
class SymbolViewHelperTest extends AbstractViewHelperBaseTestcase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock(
            SymbolViewHelper::class,
            [
                'getTypoScriptSettings',
                'getSymbolFilePath',
                'getAbsoluteFilename',
                'getCacheBuster'
            ]
        );

        $this->viewHelper->expects($this->any())->method('getTypoScriptSettings')->willReturn(
            [
                'svg' => [
                    'symbol' => [
                        'presets' => [
                            'default' => [
                                'file' =>  'EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg',
                                'baseClass' => 'testiconset'
                            ]
                        ]
                    ]
                ]
            ]
        );
        $this->viewHelper->expects($this->any())->method('getAbsoluteFilename')->willReturn('path');
        $this->viewHelper->expects($this->any())->method('getCacheBuster')->willReturn('?cb=cachebuster');
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
    }

    /**
     * @test
     * @dataProvider renderProvider
     * @param $arguments
     * @param $settings
     * @param $expected
     */
    public function renderTest($arguments, $settings, $expected)
    {
        $this->setArgumentsUnderTest($this->viewHelper, $arguments);
        $this->viewHelper->initialize();
        $this->viewHelper->_set('settings', $settings);
        $result = $this->viewHelper->render();
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            'identifier only' => [
                [
                    'identifier' => 'identifier',
                ],
                [],
                '<span class="testiconset testiconset-identifier testiconset-identifier-dims"><svg role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'custom symbolFile' => [
                [
                    'identifier' => 'identifier',
                    'symbolFile' => 'EXT:foo/symbolfile.svg'
                ],
                [],
                '<span class="icon-default icon-default-identifier icon-default-identifier-dims"><svg role="graphics-symbol"><use xlink:href="EXT:foo/symbolfile.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'no cache buster' => [
                [
                    'identifier' => 'identifier',
                    'cacheBuster' => 0
                ],
                [],
                '<span class="testiconset testiconset-identifier testiconset-identifier-dims"><svg role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg#identifier" /></svg></span>'
            ],
            'role set to "img"' => [
                [
                    'identifier' => 'identifier',
                    'role' => 'img',
                ],
                [],
                '<span class="testiconset testiconset-identifier testiconset-identifier-dims"><svg role="img"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'with ariaLabel' => [
                [
                    'identifier' => 'identifier',
                    'ariaLabel' => 'my aria label',
                ],
                [],
                '<span class="testiconset testiconset-identifier testiconset-identifier-dims"><svg aria-label="my aria label" role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'with custom baseClass' => [
                [
                    'identifier' => 'identifier',
                    'baseClass' => 'myicon',
                ],
                [],
                '<span class="myicon myicon-identifier myicon-identifier-dims"><svg role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'with title' => [
                [
                    'identifier' => 'identifier',
                    'title' => 'myicontitle',
                ],
                [],
                '<span title="myicontitle" class="testiconset testiconset-identifier testiconset-identifier-dims"><svg role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
            'with extra css class' => [
                [
                    'identifier' => 'identifier',
                    'class' => 'mycustomclass',
                ],
                [],
                '<span class="testiconset testiconset-identifier testiconset-identifier-dims mycustomclass"><svg role="graphics-symbol"><use xlink:href="EXT:c1_svg_viewhelpers/Resources/Public/Default/symbol/default-symbol.svg?cb=cachebuster#identifier" /></svg></span>'
            ],
        ];
    }
}
