<?php

header('Content-Type: text/html; charset=UTF-8');


class importMixedPricesData {

	function importMixedPrices(){

		$starttime = microtime(true);

		//Datei mit News ermitteln und als Pfad-String übernehmen
		$sourceFile = $this->getSourceFile();

		//Dateiinhalt in Array parsen
		$dataArray = json_decode(json_encode((array) simplexml_load_file($sourceFile)), 1);
		$dataArray = array_map('array_filter', $dataArray['Energiepreis']);
		//Verarbeitung der einzelnen Events


		for($i=0;$i<count($dataArray);$i++){

			$priceData = $this->parsePriceData($dataArray[$i]);
			print_r($priceData);
			//$this->updateCategoryTable($priceData);
			//$this->archiveCurrentData($priceData);
			$this->updatePriceData($priceData);

		}

	}

	/**
	 * importNewsData::updateNewsEntry()
	 * Aktualisierung der Daten für die News, anschließend Übernahme in
	 * eine Lookup-Tabelle.
	 *
	 * @param mixed $dataArray: Array mit den Daten der News
	 * @return
	 */
	function updateCategoryTable($dataArray){
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");

		$categoryArray = array(
			'uid' => $dataArray['catid'],
			'title' => $dataArray['title']
		);

		foreach($categoryArray AS $key => $value){
			$updateStringArray[] = $key.'= \''.$value.'\'';
		}

		$keyArray = array();
		$valueArray = array();
		foreach($categoryArray AS $key => $value){
			$keyArray[] = $key;
			$valueArray[] = '\''. mysql_real_escape_string($value)  .'\'';
		}


		$insertQueryCurrent = 'INSERT INTO tx_agrarapp_mixedprices_categories ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);

		mysql_query($insertQueryCurrent);
		mysql_close($connection);

	}

	function archiveCurrentData($dataArray){
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");

		$queryResult = mysql_query($query);



		while($row = mysql_fetch_assoc($queryResult)){

			$keyArray = array();
			$valueArray = array();

			foreach($row AS $key => $value){
				$keyArray[] = $key;
				$valueArray[] = '\''. mysql_real_escape_string($value)  .'\'';
			}

			$insertQueryCurrent = 'INSERT INTO tx_agrarapp_marketdata_history ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .')';
			mysql_query($insertQueryCurrent);
		}
		mysql_close($connection);

	}

	function updatePriceData($dataArray){
		$connection = mysql_connect('localhost','goldg','OwO@B@2r') or die ("Verbindungsversuch fehlgeschlagen");
                mysql_select_db('T3_B2CAPPT',$connection);
                mysql_query("SET NAMES 'utf8'");
                mysql_query("SET CHARACTER SET 'utf8'");

		$marketDataLookup = file_get_contents('/var/www/t/fileadmin/files/marketdata_lookup.txt');

                $marketDataLookupArray = json_decode($marketDataLookup,1);

		$historyQuery1 = 'SELECT uid,details FROM tx_agrarapp_marketdata_history WHERE originalid LIKE \'baywa_'. $dataArray['category'] .'\' ORDER BY tstamp LIMIT 1,1';
		$historyResult1 = mysql_query($historyQuery1);
		$result = mysql_fetch_array($historyResult1,MYSQL_ASSOC);
		$previousDetails = json_decode($result['details'],1);
		$previousPrice = $previousDetails['settlement'];

		$changeNet = $previousPrice - $dataArray['price'];
		$changePercent = ($changeNet / $dataArray['price']) * 100 ;
		$changePercent = $changeNet > 0 ? $changePercent : ($changePercent * -1); 	

		$detailsArray = array(
		        'name' => $marketDataLookupArray['baywa_'. $dataArray['category']]['title'],
		        'description' => '',
		        'isin' => '',
		        'currency' => 'EUR',
		        'unit' => 'EUR/'. $marketDataLookupArray['baywa_'. $dataArray['category']]['unit'],
		        'openprice' => $dataArray['price'],
		        'highprice' => $dataArray['price'],
		        'lowprice' => $dataArray['price'],
		        'currentprice' => $dataArray['price'],
		        'changeNet' => $changeNet,
		        'changePercent' => $changePercent,
		        'settlement' => $dataArray['price'],
		        'previousClosePrice' => $dataArray['price'],
		        'previousCloseDate' => $dataArray['datetime'],
		        'dataGranularity' => 1,
		        'dataSource' => 'baywa'
		);

		$dataArrayItem = array(
			'tstamp' => time(),
			'crdate' => time(),
		        'price' => $dataArray['price'],
		        'datetime' => $dataArray['datetime'],
		        'originalid' => 'baywa_'. $dataArray['category'],
		        'details' => json_encode($detailsArray)
		);

		$dataArrayItem['details'] = str_replace('u20ac','\u20ac',$dataArrayItem['details']);

		foreach($dataArrayItem AS $key => $value){
			$updateStringArray[] = $key.'= \''.$value.'\'';
			$keyArray[] = $key;
			$valueArray[] = '\''. mysql_real_escape_string($value)  .'\'';
		}


                $insertQueryCurrent = 'INSERT INTO tx_agrarapp_marketdata ('. implode(',',$keyArray)  .') VALUES ('. implode(',',$valueArray)  .') ON DUPLICATE KEY UPDATE '. implode(',',$updateStringArray);

                mysql_query($insertQueryCurrent);

		$historyQuery = 'SELECT uid FROM tx_agrarapp_marketdata_history WHERE originalid LIKE \''. $dataArrayItem['originalid'] .'\' AND datetime = '. $dataArray['datetime'];


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

		}


                mysql_close($connection);



	}


	/**
	 * importNewsData::parseNewsData()
	 * Verarbeitung der einzelnen News-Datensätze in der XML-Datei.
	 * Aufbau eines Arrays, das die Daten für die nachfolgende Übernahme in die
	 * Datenbank für jede einzelne News enthält.
	 *
	 * @param mixed $dataArray: Array mit den Rohdaten aus der XML-Datei
	 * @return
	 */
	function parsePriceData($dataArray){

		//News-Details für Update oder Insert zusammenstellen
		$priceDataArray = array(
			'catid' => $dataArray['Id'],
			'title' => $dataArray['Name'],
			'price' => floatval($dataArray['Preis']),
			'unit' => $dataArray['Einheit'],
			'category' => $dataArray['Id'],
			'datetime' => $this->parseTimeToTimestamp($dataArray['GeaendertAm'])
		);

		return $priceDataArray;

	}

	/**
	 * importNewsData::getSourceFile()
	 * Ermittelt die zuletzt hochgeladene Datei im Verzeichnis und übernimmt sie
	 * als Quelle für den Import-Prozess
	 *
	 * WICHTIG: Pfad für die News ist hartkodiert!!
	 *
	 *
	 * @return $filename Absolute Pfad zur aktuellen Datei
	 */
	function getSourceFile(){

	$path = '/home/aptagricheck/files/shp/mixedprices';
	$dh  = opendir($path);


	while (false !== ($filename = readdir($dh))) {
		if(is_file($path.'/'.$filename)){
			$files[filemtime($path.'/'.$filename)] = $filename;
		}
	}

	krsort($files);

	$latestFile = array_slice($files,0,1);
	$filename = $path .'/'.reset($latestFile);

	foreach ($files as $k=>$filenameItem) {
		
		if($k < time() - 604800){
			unlink($path.'/'.$filenameItem);
		}
		
	}
	return $filename;

	}

	function parseTimeToTimestamp($timeString){

                return strtotime($timeString);

        }


}

$import = new importMixedPricesData;
$import->importMixedPrices();

?>
