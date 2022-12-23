<?php
defined('TYPO3') || die();

call_user_func(function () {
    $extensionKey = 'c1_svg_viewhelpers';

    /**
     * Default TypoScript for c1_svg_viewhelpers
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'sitepackage'
    );
});
