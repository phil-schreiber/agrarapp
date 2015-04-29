<?php

header('Content-Type: text/html; charset=UTF-8');

if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');
require_once(PATH_tslib.'class.tslib_pibase.php');

class importAmiHistory extends tslib_pibase {

	function importMarketPrices(){

		$this->xmlParse('fileadmin/files/import/Baywavieh_Zeitreihe_Ergaenzung.xml','Datensatz','importEventData:callback');

		$this->processMarketData();



	}

	function processMarketData(){
		$GLOBALS['TYPO3_DB']->connectDB();


		$dataArray = $this->storageArray;

		foreach($dataArray AS $key => $value){


			$detailsArray = array(
				'name' => $value['Bezeichnung'],
				'description' => '',
				'isin' => $value['isin'],
				'currency' => 'Euro',
				'unit' => 'Stück',
				'openprice' => '',
				'highprice' => $value['PreisAktuell'],
				'lowprice' => $value['PreisAktuell'],
				'currentprice' => $value['PreisAktuell'],
				'changeNet' => $value['PreisDif'],
				'changePercent' => $value['PreisDifProzent'],
				'settlement' => 0,
				'previousClosePrice' => 0,
				'previousCloseDate' => '',
				'dataGranularity' => 1,
				'dataSource' => 'ami'
			);


			$dataArray = array(
					'tstamp' => time(),
					'crdate' => time(),
					'price' => $value['PreisAktuell'],
					'datetime' => strtotime($value['DatumBis']),
					'originalid' => $value['ID'],
					'details' => json_encode($detailsArray)
			);

			$checkHistoryData = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',
				'tx_agrarapp_marketdata_history',
				'originalid LIKE \''. $value['ID'] .'\' AND datetime = '. strtotime($value['DatumBis'])
			);

			if($GLOBALS['TYPO3_DB']->sql_num_rows($checkHistoryData) == 0){

				$insertQueryPast = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_agrarapp_marketdata_history',$dataArray);
			}

		}

	}

	function callback($array){
		$this->storageArray[] = $array;
	}

	function xmlParse($file,$wrapperName,$callback,$limit=NULL){
		$xml = new XMLReader();
		if(!$xml->open($file)){
			die("Failed to open input file.");
		}
		$n=0;
		$x=0;
		while($xml->read()){
			if($xml->nodeType==XMLReader::ELEMENT && $xml->name == $wrapperName){
				$doc = new DOMDocument('1.0', 'UTF-8');
				$xmlText = simplexml_import_dom($doc->importNode($xml->expand(),true));
				$xmlData = json_decode(json_encode((array) $xmlText), 1);
				if($limit==NULL || $x<$limit){
					if($this->callback($xmlData)){
						$x++;
					}
					unset($xmlData);
				}
				$n++;
			}
		}
		$xml->close();
	}



}

$import = t3lib_div::makeInstance('importAmiHistory');
$import->importMarketPrices();

?>