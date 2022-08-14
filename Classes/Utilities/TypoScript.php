<?php

namespace C1\C1SvgViewHelpers\Utilities;

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class TypoScript
{
    public static function getSettings(): array
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        /** @var TypoScriptService $typoScriptService */
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

        $typoScript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'tx_c1svgviewhelpers',
            null
        );
        return $typoScriptService->convertTypoScriptArrayToPlainArray($typoScript['plugin.']['tx_c1svgviewhelpers.'] ?? []);
    }
}
