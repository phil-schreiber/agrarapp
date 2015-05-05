<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_agrarapp_infos'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate DESC',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_infos.gif',
	),
);

$TCA['tx_agrarapp_zipcodes'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes',
		'label'     => 'zip',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_zipcodes.gif',
	),
);

$TCA['tx_agrarapp_regions'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_regions',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_regions.gif',
	),
);

$TCA['tx_agrarapp_cultivar'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_cultivar',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_cultivar.gif',
	),
);

$TCA['tx_agrarapp_mixedprices'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_mixedprices.gif',
	),
);

$TCA['tx_agrarapp_mixedprices_categories'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_categories',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_mixedprices_categories.gif',
	),
);

$TCA['tx_agrarapp_mixedprices_archive'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_archive',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_mixedprices_archive.gif',
	),
);

$TCA['tx_agrarapp_profiles'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles',
		'label'     => 'lastname',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_profiles.gif',
	),
);

$TCA['tx_agrarapp_events'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_events.gif',
	),
);

$TCA['tx_agrarapp_weatherdata'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weatherdata',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_weatherdata.gif',
	),
);

$TCA['tx_agrarapp_weathermaps'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_weathermaps.gif',
	),
);

$TCA['tx_agrarapp_weathermaps_categories'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps_categories',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_weathermaps_categories.gif',
	),
);

$TCA['tx_agrarapp_marketdata_categories'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_categories',
		'label'     => 'title',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_marketdata_categories.gif',
	),
);

$TCA['tx_agrarapp_marketdata'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_marketdata.gif',
	),
);

$TCA['tx_agrarapp_marketdata_history'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history',
		'label'     => 'uid',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_marketdata_history.gif',
	),
);

$TCA['tx_agrarapp_futures'] = array(
'ctrl' => array(
	'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_futures',
	'label'     => 'uid',
	'tstamp'    => 'tstamp',
	'crdate'    => 'crdate',
	'cruser_id' => 'cruser_id',
	'sortby' => 'sorting',
	'delete' => 'deleted',
	'enablecolumns' => array(
		'disabled' => 'hidden',
	),
	'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
	'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'tx_agrarapp_futures.gif',
),
);

$TCA['tx_agrarapp_weatherwarnings'] = array(
'ctrl' => array(
	'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weatherwarnings',
	'label'     => 'title',
	'tstamp'    => 'tstamp',
	'crdate'    => 'crdate',
	'cruser_id' => 'cruser_id',
	'default_sortby' => 'ORDER BY crdate DESC',
	'delete' => 'deleted',
	'enablecolumns' => array(
		'disabled' => 'hidden',
		'starttime' => 'starttime',
		'endtime' => 'endtime',
		'fe_group' => 'fe_group',
	),
	'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
	'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_weatherwarnings.gif',
),
);

$TCA['tx_agrarapp_locations'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations',
		'label'     => 'location',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_locations.gif',
	),
);

$TCA['tx_agrarapp_subscriptions'] = array(
'ctrl' => array(
    'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions',
    'label'     => 'uid',
    'tstamp'    => 'tstamp',
    'crdate'    => 'crdate',
    'cruser_id' => 'cruser_id',
    'default_sortby' => 'ORDER BY crdate',
    'delete' => 'deleted',
    'enablecolumns' => array(
        'disabled' => 'hidden',
    ),
    'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_subscriptions.gif',
),
);

$TCA['tx_agrarapp_devices'] = array(
'ctrl' => array(
    'title'     => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_devices',
    'label'     => 'uid',
    'tstamp'    => 'tstamp',
    'crdate'    => 'crdate',
    'cruser_id' => 'cruser_id',
    'default_sortby' => 'ORDER BY crdate',
    'delete' => 'deleted',
    'enablecolumns' => array(
        'disabled' => 'hidden',
    ),
    'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
    'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_agrarapp_devices.gif',
),
);





t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:agrarapp/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');


if (TYPO3_MODE === 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_agrarapp_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'pi1/class.tx_agrarapp_pi1_wizicon.php';
}
?>


