<?php

namespace C1\C1SvgViewHelpers\ViewHelpers;

use C1\C1SvgViewHelpers\Utilities\TypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

class SymbolViewHelper extends AbstractTagBasedViewHelper
{
    protected string $symbolsFile = '';
    protected string $baseClass = 'icon';
    protected array $settings = [];

    // Initialize the viewhelper
    public function initialize(): void
    {
        parent::initialize();
        $this->settings = $this->getTypoScriptSettings();
        $this->setSymbolFile();
        $this->setBaseClass();
    }

    // Return the TypoScript settings for this extension
    protected function getTypoScriptSettings(): array
    {
        return TypoScript::getSettings();
    }

    // Initialize viewhelper arguments
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('identifier', 'string', 'the identifier of the Icon as given in the svg-sprite', true);
        $this->registerArgument('symbolFile', 'string', 'Path to a symbolfile or key from typoscript presets to use.', false, 'default');
        $this->registerArgument('baseClass', 'string', 'base css classname', false);
        $this->registerArgument('role', 'string', 'the role-attribute, default is graphics-symbol', false, 'graphics-symbol');
        $this->registerArgument('ariaLabel', 'string', 'the aria-label attribute which describes the svg image', false);
        $this->registerArgument('cacheBuster', 'boolean', 'Add a cache buster', false, true);
    }

    // Get a tagBuilder instance
    private function getTagBuilder(): TagBuilder
    {
        /** @var TagBuilder */
        return GeneralUtility::makeInstance('TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder');
    }

    /*
     * Set the symbolFile, either from
     * - TypoScript presets
     * - symbolFile argument
     */
    public function setSymbolFile(): void
    {
        $presets = $this->settings['svg']['symbol']['presets'];
        if (
            isset($presets[$this->arguments['symbolFile']]) &&
            array_key_exists('file', $presets[$this->arguments['symbolFile']])
        ) {
            $this->symbolsFile = $presets[$this->arguments['symbolFile']]['file'];
        } else {
            $this->symbolsFile = $this->arguments['symbolFile'];
        }
    }

    /*
     * Set the baseClass either from
     * - baseClass argument
     * - TypoScript Presets
     * - fallback to "icon-default" if no base class could be set from the two above
     */
    public function setBaseClass(): void
    {
        if ($this->arguments['baseClass']) {
            $this->baseClass = $this->arguments['baseClass'];
        } else {
            $presets = $this->settings['svg']['symbol']['presets'];
            if (
                isset($presets[$this->arguments['symbolFile']]) &&
                array_key_exists('baseClass', $presets[$this->arguments['symbolFile']])
            ) {
                $this->baseClass = $presets[$this->arguments['symbolFile']]['baseClass'];
            } else {
                $this->baseClass = 'icon-default';
            }
        }
    }

    /*
     * Get css class names based on
     * - baseClass argument
     * - class argument
     */
    public function getCssClassNames(): string
    {
        $classNames = $this->baseClass . ' ' . $this->baseClass . '-' . $this->arguments['identifier'];
        $classNames .= ' ' . $this->baseClass . '-' . $this->arguments['identifier'] . '-dims';
        if ($this->hasArgument('class')) {
            $classNames .= ' ' . $this->arguments['class'];
        }
        return $classNames;
    }

    // Get absolute file name and path of the symbolFile
    public function getAbsoluteFilename(): string
    {
        return GeneralUtility::getFileAbsFileName($this->symbolsFile);
    }

    // Get the resolved path to the symbolFile
    public function getSymbolFilePath(): string
    {
        return PathUtility::getAbsoluteWebPath($this->getAbsoluteFileName());
    }

    // Get public path of the symbolFile
    public function getSvgPublicFile(): string
    {
        if ($this->symbolsFile && $this->getSymbolFilePath()) {
            return $this->getSymbolFilePath();
        }
        return $this->symbolsFile;
    }

    // Return cache buster enabled or not
    public function cacheBusterEnabled(): bool
    {
        return $this->arguments['cacheBuster'] ? true : false;
    }

    // Get cache buster string
    public function getCacheBuster(): string
    {
        if (!empty($this->symbolsFile) && file_exists($this->getAbsoluteFilename())) {
            return '?cb=' . md5_file($this->getAbsoluteFilename());
        }
        return '';
    }

    // Build the use tag
    public function buildUseTag(): string
    {
        if ($this->cacheBusterEnabled()) {
            $xlink = $this->getSvgPublicFile() . $this->getCacheBuster() . '#' . $this->arguments['identifier'];
        } else {
            $xlink = $this->getSvgPublicFile() . '#' . $this->arguments['identifier'];
        }
        $tagBuilder = $this->getTagBuilder();
        $tagBuilder->setTagName('use');
        $tagBuilder->addAttribute('xlink:href', $xlink);
        return $tagBuilder->render();
    }

    // Build the SVG tag
    public function buildSvgTag(): string
    {
        $tagBuilder = $this->getTagBuilder();
        $tagBuilder->setTagName('svg');
        $tagBuilder->removeAttribute('title');

        if ($this->hasArgument('ariaLabel') && $this->arguments['ariaLabel'] != '') {
            $tagBuilder->addAttribute('aria-label', ($this->arguments['ariaLabel']));
        }

        if ($this->hasArgument('role')) {
            $tagBuilder->addAttribute('role', ($this->arguments['role']));
        }
        $tagBuilder->setContent($this->buildUseTag());

        return $tagBuilder->render();
    }

    // Build the outer span tag
    public function buildTag(): string
    {
        $this->tag->setTagName('span');
        $this->tag->addAttribute('class', $this->getCssClassNames());
        if ($this->hasArgument('title') && $this->arguments['title'] != '') {
            $this->tag->addAttribute('title', $this->arguments['title']);
        }
        $this->tag->setContent($this->buildSvgTag());

        return $this->tag->render();
    }

    // Render the viewhelper output
    public function render(): string
    {
        return $this->buildTag();
    }
}
