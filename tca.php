<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_agrarapp_infos'] = array(
	'ctrl' => $TCA['tx_agrarapp_infos']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,region,cultivar,title,abstract,bodytext,image,bodytext2,image2,bodytext3,image3,wffinished,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_infos']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_infos',
				'foreign_table_where' => 'AND tx_agrarapp_infos.pid=###CURRENT_PID### AND tx_agrarapp_infos.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'region' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.region',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_regions',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_regions.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_infos_region_mm",
			)
		),
		'cultivar' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.cultivar',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_cultivar',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_cultivar.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_infos_cultivar_mm",
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'abstract' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.abstract',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'bodytext' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.bodytext',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_agrarapp',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'bodytext2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.bodytext2',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'image2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.image2',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_agrarapp',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'bodytext3' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.bodytext3',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'image3' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.image3',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_agrarapp',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'wffinished' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.wffinished',
			'config' => array(
				'type'     => 'input',
				'size'     => '12',
				'max'      => '20',
				'eval'     => 'datetime',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_infos.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, region, cultivar, title;;;;2-2-2, abstract;;;;3-3-3, bodytext;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_agrarapp/rte/], image, bodytext2;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_agrarapp/rte/], image2, bodytext3;;;richtext[]:rte_transform[mode=ts_css|imgpath=uploads/tx_agrarapp/rte/], image3, wffinished, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_agrarapp_zipcodes'] = array(
	'ctrl' => $TCA['tx_agrarapp_zipcodes']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,country,language,iso2,region1,region2,region3,region4,zip,city,area1,area2,latitude,longitude,tz,utc,dst'
	),
	'feInterface' => $TCA['tx_agrarapp_zipcodes']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'country' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.country',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'language' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.language',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'iso2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.iso2',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'region1' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.region1',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'region2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.region2',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'region3' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.region3',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'region4' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.region4',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.zip',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.city',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'area1' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.area1',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'area2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.area2',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'latitude' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.latitude',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'longitude' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.longitude',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'tz' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.tz',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'utc' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.utc',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'dst' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_zipcodes.dst',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, country, language, iso2, region1, region2, region3, region4, zip, city, area1, area2, latitude, longitude, tz, utc, dst')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_regions'] = array(
	'ctrl' => $TCA['tx_agrarapp_regions']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,zipcodes,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_regions']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_regions',
				'foreign_table_where' => 'AND tx_agrarapp_regions.pid=###CURRENT_PID### AND tx_agrarapp_regions.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_regions.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'zipcodes' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_regions.zipcodes',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_regions_zipcodes_mm",
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_regions.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;;;2-2-2, zipcodes;;;;3-3-3, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_cultivar'] = array(
	'ctrl' => $TCA['tx_agrarapp_cultivar']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_cultivar']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_cultivar.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_cultivar.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, baywaid;;;;3-3-3')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_mixedprices'] = array(
	'ctrl' => $TCA['tx_agrarapp_mixedprices']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,price,unit,category,history,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_mixedprices']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_mixedprices',
				'foreign_table_where' => 'AND tx_agrarapp_mixedprices.pid=###CURRENT_PID### AND tx_agrarapp_mixedprices.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices.price',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'unit' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices.unit',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_mixedprices_categories',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_mixedprices_categories.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'history' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices.history',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, price, unit, category, history, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_agrarapp_mixedprices_categories'] = array(
	'ctrl' => $TCA['tx_agrarapp_mixedprices_categories']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_agrarapp_mixedprices_categories']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_categories.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_mixedprices_archive'] = array(
	'ctrl' => $TCA['tx_agrarapp_mixedprices_archive']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,price,unit,category,history'
	),
	'feInterface' => $TCA['tx_agrarapp_mixedprices_archive']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_mixedprices_archive',
				'foreign_table_where' => 'AND tx_agrarapp_mixedprices_archive.pid=###CURRENT_PID### AND tx_agrarapp_mixedprices_archive.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_archive.price',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'unit' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_archive.unit',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_archive.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_mixedprices_categories',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_mixedprices_categories.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'history' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_mixedprices_archive.history',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, price, unit, category, history')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_agrarapp_profiles'] = array(
	'ctrl' => $TCA['tx_agrarapp_profiles']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,name,street,zip,city,email,phone,fax,mobile,picture,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_profiles']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_profiles',
				'foreign_table_where' => 'AND tx_agrarapp_profiles.pid=###CURRENT_PID### AND tx_agrarapp_profiles.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'street' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.street',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.zip',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_profiles_zip_mm",
			)
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.city',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.email',
			'config' => array(
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'phone' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.phone',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'fax' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.fax',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'mobile' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.mobile',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'picture' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.picture',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => 'gif,png,jpeg,jpg',
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_agrarapp',
				'show_thumbs' => 1,
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'baywaid' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_profiles.baywaid',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'default'  => '0',
				'checkbox' => '0'
			)
		)
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name, street, zip, city, email, phone, fax, mobile, picture, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_agrarapp_events'] = array(
	'ctrl' => $TCA['tx_agrarapp_events']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,abstract,street,zip,city,address_addition,datetime_start,datetime_end,regions,markdeleted,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_events']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_agrarapp_events',
				'foreign_table_where' => 'AND tx_agrarapp_events.pid=###CURRENT_PID### AND tx_agrarapp_events.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'abstract' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.abstract',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'street' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.street',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.zip',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.city',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'address_addition' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.address_addition',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'datetime_start' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.datetime_start',
			'config' => array(
				'type'     => 'input',
				'size'     => '12',
				'max'      => '20',
				'eval'     => 'datetime',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'datetime_end' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.datetime_end',
			'config' => array(
				'type'     => 'input',
				'size'     => '12',
				'max'      => '20',
				'eval'     => 'datetime',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'regions' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.regions',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_regions',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_regions.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_events_regions_mm",
			)
		),
		'markdeleted' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.markdeleted',
			'config' => array(
				'type' => 'check',
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_events.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;;;2-2-2, abstract;;;;3-3-3, street, zip, city, address_addition, datetime_start, datetime_end, regions, markdeleted, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_agrarapp_weatherdata'] = array(
	'ctrl' => $TCA['tx_agrarapp_weatherdata']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,zip,current,forecast'
	),
	'feInterface' => $TCA['tx_agrarapp_weatherdata']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weatherdata.zip',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'current' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weatherdata.current',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'forecast' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weatherdata.forecast',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, zip, current, forecast')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_weathermaps'] = array(
	'ctrl' => $TCA['tx_agrarapp_weathermaps']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,zip,title,category,image'
	),
	'feInterface' => $TCA['tx_agrarapp_weathermaps']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps.zip',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_weathermaps_categories',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_weathermaps_categories.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'image' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps.image',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
				'uploadfolder' => 'uploads/tx_agrarapp',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, zip, title;;;;2-2-2, category;;;;3-3-3, image')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_weathermaps_categories'] = array(
	'ctrl' => $TCA['tx_agrarapp_weathermaps_categories']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_agrarapp_weathermaps_categories']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_weathermaps_categories.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_marketdata_categories'] = array(
	'ctrl' => $TCA['tx_agrarapp_marketdata_categories']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title'
	),
	'feInterface' => $TCA['tx_agrarapp_marketdata_categories']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_categories.title',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_marketdata'] = array(
	'ctrl' => $TCA['tx_agrarapp_marketdata']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,price,originalid,datetime,details,category,datatype'
	),
	'feInterface' => $TCA['tx_agrarapp_marketdata']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata.price',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'double2',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '99999',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'originalid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata.originalid',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'datetime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_db.xml:tx_agrarapp_marketdata.datetime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'details' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata.details',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_marketdata_categories',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_marketdata_categories.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'datatype' => array(
	        'exclude' => 0,
	        'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata.datatype',
	        'config' => array(
	            'type' => 'select',
	            'items' => array(
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.0', '0'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.1', '1'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.2', '1'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.3', '1'),
	            ),
	            'size' => 1,
	            'maxitems' => 1,
	        )
	    ),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, price, originalid, datetime, details, category, datatype')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_marketdata_history'] = array(
	'ctrl' => $TCA['tx_agrarapp_marketdata_history']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,price,originalid,datetime,details,category,datatype'
	),
	'feInterface' => $TCA['tx_agrarapp_marketdata_history']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'price' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history.price',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'double2',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '99999',
					'lower' => '0'
				),
				'default' => 0
			)
		),
		'originalid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history.originalid',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'datetime' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_db.xml:tx_agrarapp_marketdata_history.datetime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'details' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history.details',
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history.category',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_marketdata_categories',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_marketdata_categories.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'datatype' => array(
	        'exclude' => 0,
	        'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_marketdata_history.datatype',
	        'config' => array(
	            'type' => 'select',
	            'items' => array(
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.0', '0'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.1', '1'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.2', '1'),
	                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.3', '1'),
	            ),
	            'size' => 1,
	            'maxitems' => 1,
	        )
	    ),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, price, originalid, datetime, details, category, datatype')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);



$TCA['tx_agrarapp_locations'] = array(
	'ctrl' => $TCA['tx_agrarapp_locations']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,location,division,street,zip,city,phone,fax,baywaid'
	),
	'feInterface' => $TCA['tx_agrarapp_locations']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'location' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.location',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'division' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.division',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'street' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.street',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.zip',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.city',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'phone' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.phone',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required',
			)
		),
		'fax' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.fax',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.email',
			'config' => array(
				'type' => 'input',
				'size' => '30',
			)
		),
		'zipcodes' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.zipcodes',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_agrarapp_zipcodes',
				'foreign_table_where' => 'ORDER BY tx_agrarapp_zipcodes.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 100,
				"MM" => "tx_agrarapp_locations_zipcodes_mm",
			)
		),
		'baywaid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_locations.baywaid',
			'config' => array(
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array(
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, location, division, street, zip, city, phone, fax, baywaid')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

$TCA['tx_agrarapp_subscriptions'] = array(
'ctrl' => $TCA['tx_agrarapp_subscriptions']['ctrl'],
'interface' => array(
    'showRecordFieldList' => 'hidden,category,zipcode,subtype,deviceid'
),
'feInterface' => $TCA['tx_agrarapp_subscriptions']['feInterface'],
'columns' => array(
    'hidden' => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
        'config'  => array(
            'type'    => 'check',
            'default' => '0'
        )
    ),
    'category' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.category',
        'config' => array(
            'type'     => 'input',
            'size'     => '5',
            'max'      => '5',
            'eval'     => 'int',
            'checkbox' => '0',
            'range'    => array(
                'upper' => '99999',
                'lower' => '0'
            ),
            'default' => 0
        )
    ),
    'zipcode' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.zipcode',
        'config' => array(
            'type'     => 'input',
            'size'     => '5',
            'max'      => '5',
            'eval'     => 'int',
            'checkbox' => '0',
            'range'    => array(
                'upper' => '99999',
                'lower' => '0'
            ),
            'default' => 0
        )
    ),
    'subtype' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype',
        'config' => array(
            'type' => 'select',
            'items' => array(
                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.0', '0'),
                array('LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.subtype.I.1', '1'),
            ),
            'size' => 1,
            'maxitems' => 1,
        )
    ),
	'deviceid' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_subscriptions.deviceid',
		'config' => array(
			'type' => 'input',
			'size' => '30',
		)
	),
),
'types' => array(
    '0' => array('showitem' => 'hidden;;1;;1-1-1, category, zipcode, subtype, deviceid')
),
'palettes' => array(
    '1' => array('showitem' => '')
)
);


$TCA['tx_agrarapp_devices'] = array(
'ctrl' => $TCA['tx_agrarapp_devices']['ctrl'],
'interface' => array(
    'showRecordFieldList' => 'hidden,deviceid,ostype'
),
'feInterface' => $TCA['tx_agrarapp_devices']['feInterface'],
'columns' => array(
    'hidden' => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
        'config'  => array(
            'type'    => 'check',
            'default' => '0'
        )
    ),
    'deviceid' => array(
    	'exclude' => 0,
    	'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_devices.deviceid',
    	'config' => array(
    		'type' => 'input',
    		'size' => '30',
    	)
    ),
	'ostype' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:agrarapp/locallang_db.xml:tx_agrarapp_devices.ostype',
		'config' => array(
			'type' => 'input',
			'size' => '30',
		)
	)
),
'types' => array(
    '0' => array('showitem' => 'hidden;;1;;1-1-1, deviceid, ostype')
),
'palettes' => array(
    '1' => array('showitem' => '')
)
);

?>