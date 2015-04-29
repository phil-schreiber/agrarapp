<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_agrarapp_infos", field "bodytext"
	# ***************************************************************************************
RTE.config.tx_agrarapp_infos.bodytext {
  hidePStyleItems = H1, H4, H5, H6
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
    keepNonMatchedTags=1
    tags.font.allowedAttribs= color
    tags.font.rmTagIfNoAttrib = 1
    tags.font.nesting = global
  }
}
');
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_agrarapp_infos", field "bodytext2"
	# ***************************************************************************************
RTE.config.tx_agrarapp_infos.bodytext2 {
  hidePStyleItems = H1, H4, H5, H6
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
    keepNonMatchedTags=1
    tags.font.allowedAttribs= color
    tags.font.rmTagIfNoAttrib = 1
    tags.font.nesting = global
  }
}
');
t3lib_extMgm::addPageTSConfig('

	# ***************************************************************************************
	# CONFIGURATION of RTE in table "tx_agrarapp_infos", field "bodytext3"
	# ***************************************************************************************
RTE.config.tx_agrarapp_infos.bodytext3 {
  hidePStyleItems = H1, H4, H5, H6
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
    keepNonMatchedTags=1
    tags.font.allowedAttribs= color
    tags.font.rmTagIfNoAttrib = 1
    tags.font.nesting = global
  }
}
');

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_agrarapp_pi1.php', '_pi1', 'list_type', 1);

$TYPO3_CONF_VARS['FE']['eID_include']['importEventData'] = 'EXT:agrarapp/res/classes/importEventData.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importNewsData'] = 'EXT:agrarapp/res/classes/importNewsData.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importZipData'] = 'EXT:agrarapp/res/classes/importZipData.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importWeatherData'] = 'EXT:agrarapp/res/classes/importWeatherData_alpha.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importMixedPricesData'] = 'EXT:agrarapp/res/classes/importMixedPricesData.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importMarketPricesData'] = 'EXT:agrarapp/res/classes/importMarketPricesData.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importCurrentWeather'] = 'EXT:agrarapp/res/classes/importCurrentWeather.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importAmiHistory'] = 'EXT:agrarapp/res/classes/importAmiHistory.php';
$TYPO3_CONF_VARS['FE']['eID_include']['importWeatherMaps'] = 'EXT:agrarapp/res/classes/importWeatherMaps.php';

?>