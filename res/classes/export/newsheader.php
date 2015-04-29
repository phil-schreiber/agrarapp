<?php
header('Content-Type: text/html; charset=UTF-8');

if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');
require_once(PATH_tslib.'class.tslib_pibase.php');

class newsheader extends tslib_pibase {


	function exportEvents(){

		$GLOBALS['TYPO3_DB']->connectDB();

		echo "eID triggered";

		$getCriteria = json_decode(stripslashes($_GET['criteria']),1);

		$params = $getCriteria['params'];


		foreach($params AS $key => $value){

			$cultureID = intval($value['categoryID']);
			$zipcode = intval($value['zipCode']);

			$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			 	'tx_agrarapp_regions.title,tx_agrarapp_regions.uid',
			 	'tx_agrarapp_zipcodes
				LEFT JOIN tx_agrarapp_regions_zipcodes_mm ON (tx_agrarapp_regions_zipcodes_mm.uid_foreign = tx_agrarapp_zipcodes.zip)
				LEFT JOIN tx_agrarapp_regions ON (tx_agrarapp_regions.uid = tx_agrarapp_regions_zipcodes_mm.uid_local)',
			 	'tx_agrarapp_zipcodes.zip = '.$zipcode
			 );

			echo $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;

			if($GLOBALS['TYPO3_DB']->sql_num_rows($res)){
				$regionResult = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

				t3lib_div::debug($regionResult);
			}





		}



		/*
		$getNewsQuery = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,title,cultivar,starttime,endtime',
			'tx_agrarapp_infos',
			''
		);
		*/

	}



}

$import = t3lib_div::makeInstance('newsheader');
$import->exportEvents();


?>