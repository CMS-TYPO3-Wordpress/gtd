<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'ThomasWoehlke.' . $extKey,
            'Frontendgtd',
            'FrontendGTD'
        );




        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'GTD');


        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_useraccount', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_useraccount.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_useraccount');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_usermessage', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_usermessage.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_usermessage');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_context', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_context.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_context');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_project', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_project.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_project');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_task', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_task.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_task');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gtd_domain_model_userconfig', 'EXT:gtd/Resources/Private/Language/locallang_csh_tx_gtd_domain_model_userconfig.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gtd_domain_model_userconfig');



    },
    $_EXTKEY
);
