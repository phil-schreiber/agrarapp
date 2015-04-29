<?php

error_reporting(E_ALL ^ E_NOTICE);

class importMarketPricesData  {

	function importMarketPrices(){
		$starttime = microtime(true);
		$marketData = $this->readMarketData();
		$marketDataLookup = file_get_contents('/var/www/t/fileadmin/files/marketdata_lookup.txt');
		//mail('gregor.gold@denkfabrik-group.com','BayWa Testmail','testmail vom BayWa Server');			
		$marketDataLookupArray = json_decode($marketDataLookup,1);
	
	
		$this->processMarketData($marketData, $marketDataLookupArray);

		$runtime = microtime(true) - $starttime;
		//echo date('d.m.Y - H:i:s',time()). " - ImportCurrentWeather: Laufzeit: ". $runtime ." Sekunden\n";

	}

	function processMarketData($dataArray, $marketDataLookupArray){
				
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
		mysql_select_db('T3_B2CAPPT',$connection);
		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET 'utf8'");
	
				
		foreach($dataArray AS $key => $value){
			
			$cycle = 1;
			$checkDate = strtotime($value['date']);

			if(!$value['previousCloseDate'] && !$value['date']){
				$checkDate = 0;
				$cycle = 5;
			}
			
			
			$detailsArray = array(
				'name' => $marketDataLookupArray[$value['#vwdcode']]['title'],
				'description' => '',
				'unit' =>  $marketDataLookupArray[$value['#vwdcode']]['unit'],
				'isin' => '',
				'currency' => strpos($marketDataLookupArray[$value['#vwdcode']]['title'],'AX') ? 'Punkte' : $value['currency'],
				'openprice' => $value['open'],
				'highprice' => $value['high'],
				'lowprice' => $value['low'],
				'currentprice' => $value['last'] != '' ? $value['last'] : $value['open'],
				'changeNet' => $value['changeNet'] != '' ? $value['changeNet'] : 0,
				'changePercent' => $value['changePercent'] != '' ? ($value['changePercent'] * 100)  : 0,
				'settlement' => $value['close'],
				'previousClosePrice' => $value['previousClose'],
				'previousCloseDate' => $checkDate - 86400,
				'dataGranularity' => $cycle,
				'dataSource' => strpos($value['#vwdcode'], 'AMI') == false ? 'vwd' : 'ami'
			);
		
									
			$dataArrayItem = array(
					'tstamp' => time(),
					'crdate' => time(),
					'price' => $value['close'],
					'datetime' => $checkDate,
					'originalid' => $value['#vwdcode'],
					'details' => json_encode($detailsArray)
			);

			$historyQuery = 'SELECT uid FROM tx_agrarapp_marketdata_history WHERE originalid LIKE \''. $value['#vwdcode'] .'\' AND datetime = '. $checkDate;
				
			$historyResult = mysql_query($historyQuery);
			
			

			
			$keyArray = array();
			$valueArray = array();
			foreach($dataArrayItem AS $pastKey => $pastValue){
				$keyArray[] = $pastKey;
				$valueArray[] = '\''. mysql_real_escape_string($pastValue)  .'\'';
			}
				
			if(mysql_num_rows($historyResult) == 0){

				$insertQuery = 'INSERT INTO tx_agrarapp_marketdata_history ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .')';
				mysql_query($insertQuery);
				
			}else{
				echo $value['date'] ." know \n";
			}
	
		}
		



		mysql_close($connection);

	}


	function parseTimeToTimestamp($string){

		return strtotime($string);

	}


	function getSourceFile(){

		$filename = '/var/www/t/fileadmin/files/importHistory/historyData.csv';
				
		return $filename;
	}

	function readMarketData(){

		$filename = $this->getSourceFile();

		if (($handle = fopen($filename, "r")) !== FALSE) {
			// read the column headers in an array.
			$head = fgetcsv($handle, 1000, ";");
			
			// read the actual data.
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				print_r($data);
				// create a new array with the elements in $head as keys
				// and elements in array $data as values.
				if(count($head) == count($data)){
					$marketDataArray[] = array_combine($head,$data);
				}

			}
			// done using the file..close it,
			fclose($handle);
		}
		
		return $marketDataArray;

	}


}

$import = new importMarketPricesData;
$import->importMarketPrices();

?>


